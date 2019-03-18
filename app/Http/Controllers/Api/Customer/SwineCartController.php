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

class SwineCartController extends Controller
{

    use CustomHelpers {
        transformBreedSyntax as private;
        transformDateSyntax as private;
        computeAge as private;
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

    public function getItem(Request $request, $item_id)
    {
        $customer = $this->user->userable;
        $item = $customer
            ->swineCartItems()
            ->where('if_rated', 0)
            ->where('id', $item_id)
            ->first();

        if ($item) {
            return response()->json([
                'message' => 'Get Item successful!',
                'data' => [
                    'item' => $item
                ]
            ]);
        }
        else return response()->json([
            'error' => 'SwineCart Item does not exist!' 
        ], 404);
    }

    public function getItems(Request $request)
    {
        $customer = $this->user->userable;
        $status = $request->status;
        
        if ($status) {
            if ($status == 'not_requested') {
                $items = $customer
                    ->swineCartItems()
                    ->where('if_rated', 0)
                    ->where('if_requested', 0)
                    ->paginate($request->limit)
                    ->map(function ($it) {
                        $item = [];
                        $product = Product::find($it->product_id);
                        $breeder = Breeder::find($product->breeder_id)->users()->first();

                        $item['id'] = $it->id;
                        $item['product'] = [
                            'id' =>  $it->product_id,
                            'name' => $product->name,
                            'type' =>  ucfirst($product->type),
                            'breed' =>  $this->transformBreedSyntax(Breed::find($product->breed_id)->name),
                            'img_path' => route('serveImage', ['size' => 'small', 'filename' => Image::find($product->primary_img_id)->name]),
                            'breeder' => $breeder->name
                        ];

                        return $item;
                    });

                return response()->json([
                    'message' => 'Get SwineCart items successful!',
                    'data' => [
                        'count' => $items->count(),
                        'items' => $items,
                    ]
                ]);
            }
            else if ($status == 'requested') {
                $items = $customer
                    ->swineCartItems()
                    ->where('if_rated', 0)
                    ->where('if_requested', 1)
                    ->doesntHave('productReservation')
                    ->paginate($request->limit)
                    ->map(function ($data) {
                        $item = [];

                        $product = Product::find($data->product_id);
                        $breeder = Breeder::find($product->breeder_id)->users()->first();     

                        $item['id'] = $data->id;
                        $item['status'] = 'requested';
                        $item['status_time'] = $this->transformDateSyntax(
                            $data
                                ->transactionLogs()
                                ->where('status', 'requested')
                                ->latest()->first()->created_at
                        , 2);

                        $item['product'] = [
                            'id' =>  $data->product_id,
                            'name' => $product->name,
                            'type' =>  ucfirst($product->type),
                            'breed' =>  $this->transformBreedSyntax(Breed::find($product->breed_id)->name),
                            'img_path' => route('serveImage', ['size' => 'small', 'filename' => Image::find($product->primary_img_id)->name]),
                            'breeder' => $breeder->name
                        ];

                        $item['request'] = [
                            'special_request' => $data->special_request,
                            'quantity' => $data->quantity,
                            'date_needed' => ($data->date_needed == '0000-00-00') ? '' : $this->transformDateSyntax($data->date_needed),
                        ];

                        return $item;
                    });

                return response()->json([
                    'message' => 'Get SwineCart items successful!',
                    'data' => [
                        'count' => $items->count(),
                        'items' => $items,
                    ]
                ]);
            }
            else if ($status == 'reserved' || $status == 'on_delivery' || $status == 'sold') {
                $items = $customer
                    ->swineCartItems()
                    ->where('if_rated', 0)
                    ->where('if_requested', 1)
                    ->whereHas('productReservation', function ($query) use ($status) {
                        $query->where('order_status', $status);
                    })
                    ->with(
                        'productReservation.product',
                        'productReservation.product.breed',
                        'productReservation.product.breeder.users'
                    )
                    ->paginate($request->limit)
                    ->map(function ($data) use ($status) {
                        $item = [];

                        $reservation = $data->productReservation;
                        $product = $data->productReservation->product;
                        $breed = $data->productReservation->product->breed;
                        $breeder = $data->productReservation->product->breeder->users->first()->name;
                        $logs = $data->transactionLogs();

                        $item['id'] = $data->id;
                        $item['status'] = $reservation->order_status;
                        $item['status_time'] = $logs->where('status', $status)->latest()->first() 
                                ? 
                                    $this->transformDateSyntax($logs->where('status', $status)->latest()->first()->created_at, 2)
                                :
                                    '';
                        $item['product'] = [
                            'id' => $product->id,
                            'name' => $product->name,
                            'type' => ucwords($product->type),
                            'breed' => $this->transformBreedSyntax($breed->name),
                            'img_path' => route('serveImage', ['size' => 'small', 'filename' => Image::find($product->primary_img_id)->name]),
                            'breeder' => $breeder
                        ];

                        $item['reservation'] = [
                            'quantity' => $data->quantity,
                            'special_request' => $data->special_request,
                            'delivery_date' => $reservation->delivery_date ? $this->transformDateSyntax($reservation->delivery_date) : '',
                            'date_needed' => ($data->date_needed == '0000-00-00') ? '' : $this->transformDateSyntax($data->date_needed)
                        ];
    
                        return $item;
                    });

                return response()->json([
                    'message' => 'Get SwineCart items successful!',
                    'data' => [
                        'count' => $items->count(),
                        'items' => $items,
                    ]
                ]);
            }
            else return response()->json([
                'error' => 'Invalid Status!' 
            ], 400);
        }
        else return response()->json([
            'error' => 'Invalid Status!' 
        ], 400);
    }

    public function addItem(Request $request, $product_id)
    {
        $customer = $this->user->userable;
        $item = $customer
            ->swineCartItems()
            ->where('product_id', $product_id)
            ->where('reservation_id', 0)
            ->first();

        $product = Product::find($product_id);

        if($product) {
            if($item) {
                if($item->if_requested) {
                    return response()->json([
                        'error' => 'Product already requested!',
                    ], 409);
                }
                else {
                    return response()->json([
                        'error' => 'Item already added!',
                    ], 409); 
                }
            }
            else {
                $new_item = new SwineCartItem;
                $new_item->product_id = $product_id;
                $new_item->quantity = $product->type == 'semen' ? 2 : 1;
                
                $customer->swineCartItems()->save($new_item);

                return response()->json([
                    'message' => 'Add to Cart successful!'
                ], 200);
            }
        }
        else return response()->json([
            'error' => 'Product does not exist!' 
        ], 404);
    }

    public function deleteItem(Request $request, $item_id)
    {
        $customer = $this->user->userable;
        $item = $customer
            ->swineCartItems()
            ->where('if_rated', 0)    
            ->where('id', $item_id)
            ->first();

        if($item) {
            if(!$item->if_requested) {
                $item->delete();

                return response()->json([
                    'message' => 'Delete SwineCart item successful!',
                ], 200);
            }
            else return response()->json([
                'error' => 'Product already requested!' 
            ], 400);
        }
        else return response()->json([
            'error' => 'SwineCart Item does not exist!' 
        ], 404);
    }

    public function requestItem(Request $request, $item_id)
    {
        $customer = $this->user->userable;
        $item = $customer
            ->swineCartItems()
            ->where('id', $item_id)
            ->first();

        if($item) {
            if(!$item->if_requested) {
                $item->if_requested = 1;
                $item->quantity = $request->requestQuantity;
                $item->date_needed = ($request->dateNeeded) ? date_format(date_create($request->dateNeeded), 'Y-n-j') : '';
                $item->special_request = $request->specialRequest;
                $item->save();

                $product = Product::find($item->product_id);
                $product->status = "requested";
                $product->save();

                $breeder = Breeder::find($product->breeder_id);

                $transactionDetails = [
                    'swineCart_id' => $item->id,
                    'customer_id' => $item->customer_id,
                    'breeder_id' => $product->breeder_id,
                    'product_id' => $product->id,
                    'status' => 'requested',
                    'created_at' => Carbon::now()
                ];

                $notificationDetails = [
                    'description' => '<b>' . $this->user->name . '</b> requested for Product <b>' . $product->name . '</b>.',
                    'time' => $transactionDetails['created_at'],
                    'url' => route('dashboard.productStatus')
                ];

                $smsDetails = [
                    'message' => 'SwineCart ['. $this->transformDateSyntax($transactionDetails['created_at'], 1) .']: ' . $this->user->name . ' requested for Product ' . $product->name . '.',
                    'recipient' => $breeder->office_mobile
                ];

                $pubsubData = [
                    'body' => [
                        'uuid' => (string) Uuid::uuid4(),
                        'id' => $product->id,
                        'reservation_id' => 0,
                        'img_path' => route('serveImage', ['size' => 'small', 'filename' => Image::find($product->primary_img_id)->name]),
                        'breeder_id' => $product->breeder_id,
                        'farm_province' => FarmAddress::find($product->farm_from_id)->province,
                        'name' => $product->name,
                        'type' => $product->type,
                        'age' => $this->computeAge($product->birthdate),
                        'breed' => $this->transformBreedSyntax(Breed::find($product->breed_id)->name),
                        'quantity' => $product->quantity,
                        'adg' => $product->adg,
                        'fcr' => $product->fcr,
                        'bft' => $product->backfat_thickness,
                        'status' => $product->status,
                        'status_time' => '',
                        'customer_id' => 0,
                        'customer_name' => '',
                        'date_needed' => '',
                        'special_request' => '',
                        'delivery_date' => ''
                    ]
                ];

                $breederUser = $breeder->users()->first();

                $this->addToTransactionLog($transactionDetails);

                dispatch(new SendSMS($smsDetails['message'], $smsDetails['recipient']));
                dispatch(new NotifyUser('product-requested', $breederUser->id, $notificationDetails));
                dispatch(new SendToPubSubServer('notification', $breederUser->email));
                dispatch(new SendToPubSubServer('db-productRequest', $breederUser->email, $pubsubData));
                dispatch(new SendToPubSubServer('db-requested', $breederUser->email, ['product_type' => $product->type]));

                return response()->json([
                    'message' => 'Request SwineCart Item successful'
                ], 200);
            }
            else return response()->json([
                'error' => 'SwineCart Item already requested!' 
            ], 409);
        }
        else return response()->json([
            'error' => 'SwineCart Item does not exist!' 
        ], 404);
        
    }

    public function getTransactionHistory(Request $request)
    {
        $customer = $this->user->userable;
        $history = $customer->transactionLogs;

        $restructuredHistory = $history->groupBy('product_id')->map(function($item, $key){
            $restructuredItem = [];
            $product = Product::find($key);
            $reviews = $product->breeder->reviews;

            $restructuredItem['logs'] = $item->toArray();
            $restructuredItem['product_details'] = [
                "quantity" => (SwineCartItem::find($restructuredItem['logs'][0]['swineCart_id'])->quantity) ?? '',
                "name" => $product->name,
                "type" => $product->type,
                "s_img_path" => route('serveImage', ['size' => 'small', 'filename' => Image::find($product->primary_img_id)->name]),
                "l_img_path" => route('serveImage', ['size' => 'large', 'filename' => Image::find($product->primary_img_id)->name]),
                "breed" => $this->transformBreedSyntax(Breed::find($product->breed_id)->name),
                "breeder_name" => Breeder::find($product->breeder_id)->users()->first()->name,
                "farm_from" => FarmAddress::find($product->farm_from_id)->province,
                "birthdate" => $product->birthdate,
                "adg" => $product->adg,
                "fcr" => $product->fcr,
                "bft" => $product->backfat_thickness,
                "other_details" => $product->other_details,
                "avg_delivery" => ($reviews->avg('rating_delivery')) ? $reviews->avg('rating_delivery') : 0,
                "avg_transaction" => ($reviews->avg('rating_transaction')) ? $reviews->avg('rating_transaction') : 0,
                "avg_productQuality" => ($reviews->avg('rating_productQuality')) ? $reviews->avg('rating_productQuality') : 0
            ];
            return $restructuredItem;
        });

        return response()->json([
            'message' => 'Get Transaction History successful',
            'data' => $restructuredHistory
        ]);

    }

    public function rateBreeder(Request $request, $breeder_id)
    {
        $customer = $this->user->userable;
        $reviews = Breeder::find($breeder_id)->reviews();

        $review = new Review;
        $review->customer_id = $customer->id;
        $review->comment = $request->comment;
        $review->rating_delivery = $request->delivery;
        $review->rating_transaction = $request->transaction;
        $review->rating_productQuality = $request->productQuality;

        $swineCartItems = $customer->swineCartItems();
        $reviewed = $swineCartItems->where('product_id', $request->productId)->first();
        $reviewed->if_rated = 1;
        $reviewed->save();
        $reviews->save($review);

        $breeder = Breeder::find($breeder_id);

        $transactionDetails = [
            'swineCart_id' => $reviewed->id,
            'customer_id' => $customer->id,
            'breeder_id' => $breeder_id,
            'product_id' => $request->productId,
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

        return response()->json([
            'message' => 'Rate Breeder successful',
            'data' => $review
        ], 200);
    }
}
