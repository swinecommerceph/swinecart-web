<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Jobs\AddToTransactionLog;
use App\Jobs\NotifyUser;
use App\Jobs\SendSMS;
use App\Jobs\SendToPubSubServer;
use App\Http\Requests;
use App\Models\Customer;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Breed;
use App\Models\Image;
use App\Models\SwineCartItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\TransactionLog;
use App\Models\ProductReservation;

use App\Repositories\ProductRepository;
use App\Repositories\CustomHelpers;

use Auth;
use Response;
use Validator;
use JWTAuth;
use Mail;
use Storage;
use Config;
use DB;

class TransactionsController extends Controller
{

    use CustomHelpers {
        transformBreedSyntax as private;
        transformDateSyntax as private;
        computeAge as private;
    }

    private function dispatchRatedNotif($item, $product, $review, $customer, $breeder)
    {
        $transactionDetails = [
            'swineCart_id' => $item->id,
            'customer_id' => $customer->id,
            'breeder_id' => $breeder->id,
            'product_id' => $product->id,
            'status' => 'rated',
            'created_at' => Carbon::now()
        ];

        $notificationDetails = [
            'description' => 'Customer <b>' . $this->user->name . ' rated</b> you with ' . round(($review->rating_delivery + $review->rating_transaction + $review->rating_productQuality)/3, 2) . ' (overall average).',
            'time' => $transactionDetails['created_at'],
            'url' => route('dashboard')
        ];

        $smsDetails = [
            'message' => 'SwineCart ['. $this->transformDateSyntax($transactionDetails['created_at'], 1) .']: Customer ' . $this->user->name . ' rated you with ' . round(($review->rating_delivery + $review->rating_transaction + $review->rating_productQuality)/3, 2) . ' (overall average).',
            'recipient' => $breeder->office_mobile
        ];

        $pubsubData = [
            'rating_delivery' => $review->rating_delivery,
            'rating_transaction' => $review->rating_transaction,
            'rating_productQuality' => $review->productQuality,
            'review_comment' => $review->comment,
            'review_customerName' => $this->user->name
        ];

        $breederUser = $breeder->users()->first();

        // Add new Transaction Log
        $this->addToTransactionLog($transactionDetails);

        // Queue notifications (SMS, database, notification, pubsub server)
        dispatch(new SendSMS($smsDetails['message'], $smsDetails['recipient']));
        dispatch(new NotifyUser('breeder-rated', $breederUser->id, $notificationDetails));
        dispatch(new SendToPubSubServer('notification', $breederUser->email));
        dispatch(new SendToPubSubServer('db-rated', $breederUser->email, $pubsubData));
    }

    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getTransactionHistory(Request $request)
    {
        $customer = $this->user->userable;
        $history = $customer
            ->transactionLogs()
            ->with(
                'product.breed', 'swineCartItem', 'product.farmFrom',
                'product.breeder.users'
            )
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy('swineCart_id')
            ->reduce(function ($history, $item) {

                $transaction = [];

                $product = $item[0]->product;
                $swineCartItem = $item[0]->swineCartItem;
                $province = $product->farmFrom->province;
                $breed = $product->breed;
                $breeder = $product->breeder->users()->first()->name;

                $transaction['id'] = $item[0]->swineCart_id;

                $transaction['product'] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'breed' => $this->transformBreedSyntax($breed->name),
                    'type' => ucfirst($product->type),
                    'province' => $province,
                    'breeder' => $breeder,
                ];

                $transaction['logs'] = $item->map(function ($item) {
                    $log = [];
                    $log['status'] = $item->status;
                    $log['created_at'] = $this->transformDateSyntax($item->created_at, 3);
                    return $log;
                });

                $transaction['request'] = [
                    'quantity' => $swineCartItem->quantity
                ];

                $history->push($transaction);

                return $history;

            }, collect([]))
            ->forPage($request->page, $request->limit);

        return response()->json([
            'message' => 'Get Transaction History successful',
            'data' => [
                'count' => $history->count(),
                'history' => $history,
            ]
        ]);

    }

    public function reviewBreeder(Request $request, $breeder_id)
    {
        $customer = $this->user->userable;

        $breeder = Breeder::find($breeder_id);
        $reviews = $breeder->reviews();
        $item = $customer
            ->swineCartItems()
            ->with('product')
            ->where('id', $request->item_id)
            ->first();

        if ($item) {
            $product = $breeder
                ->products()
                ->where('id', $item->product->id)
                ->first();

            if ($product) {
                if ($item->if_rated == 0) {
                    // Create Review
                    $review = new Review;
                    $review->customer_id = $customer->id;
                    $review->comment = $request->comment;
                    $review->rating_delivery = $request->delivery;
                    $review->rating_transaction = $request->transaction;
                    $review->rating_productQuality = $request->productQuality;

                    $item->if_rated = 1;
                    $item->save();
                    $reviews->save($review);

                    $this->dispatchRatedNotif($item, $product, $review, $customer, $breeder);

                    return response()->json([
                        'message' => 'Review Breeder successful',
                    ], 200);
                }
                else return response()->json([
                    'error' => 'Item already rated!' 
                ], 409);

            }
            else return response()->json([
                'error' => 'Product does not exist!' 
            ], 404);
        }
        else return response()->json([
            'error' => 'Cart Item does not exist!' 
        ], 404);
    }
}
