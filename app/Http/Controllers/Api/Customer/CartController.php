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

class CartController extends Controller
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
    
    private $defaultImages = [
        'boar' => 'boar_default.jpg',
        'sow' => 'sow_default.jpg',
        'semen' => 'semen_default.jpg',
        'gilt' => 'gilt_default.jpg',
    ];

    private function getCartItems()
    {   
        $customer = $this->user->userable;

        return $customer
            ->swineCartItems()
            ->with(
                'product', 
                'product.breed', 
                'product.breeder.users', 
                'product.primaryImage'
            )
            ->where('if_rated', 0)
            ->where('if_requested', 0);
    }

    private function findItem($item_id)
    {   
        return $cart_item = $this->getCartItems()
            ->where('id', $item_id)
            ->first();
    }

    private function formatCartItem($cart_item)
    {

        $product = $cart_item->product;
        $breed = $product->breed;
        $breeder = $product->breeder->users()->first();

        $is_deleted = $product->trashed();

        return [
            'id' => $cart_item->id,
            'product' => [
                'id' =>  $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'breed' => $this->transformBreedSyntax($breed->name),
                'imageUrl' => route('serveImage',
                    [
                        'size' => 'medium', 
                        'filename' => $is_deleted 
                            ? $this->defaultImages[$product->type]
                            : $product->primaryImage->name
                    ]
                ),
                'breederName' => $breeder->name,
                'isDeleted' => $is_deleted
            ]
        ];
    }

    public function getItem(Request $request, $item_id)
    {
        $cart_item = $this->findItem($item_id);

        if ($cart_item) {
            return response()->json([
                'data' => [
                    'item' => $this->formatCartItem($cart_item),
                ]
            ], 200);
        }
        else {
            return response()->json([
                'data' => 'Cart Item not found!'
            ], 404);
        }

    }

    public function getItemCount(Request $request)
    {
        $count = $this->getCartItems()->count();

        return response()->json([
            'data' => [
                'itemCount' => $count
            ]
        ], 200);
    }

    public function getItems(Request $request)
    {
        $items = $this->getCartItems()
            ->paginate($request->limit)
            ->map(function ($element) {
                return $this->formatCartItem($element);
            });

        return response()->json([
            'data' => [
                'items' => $items,
            ]
        ]);
    }

    public function addItem(Request $request, $product_id)
    {
        $customer = $this->user->userable;

        $item = $customer
            ->swineCartItems()
            ->where('product_id', $product_id)
            ->where('reservation_id', 0)
            ->first();

        if($item) {
            if($item->if_requested) {
                return response()->json([
                    'error' => 'Product already requested!',
                ], 409);
            } 
            else {
                return response()->json([
                    'error' => 'Cart Item already added!',
                ], 409); 
            }
        }
        else {

            $product = Product::withTrashed()->find($product_id);

            $new_item = new SwineCartItem;
            $new_item->product_id = $product_id;
            $new_item->quantity = $product->type == 'semen' ? 2 : 1;

            $is_inserted = $customer->swineCartItems()->save($new_item);

            if ($is_inserted) {
                
                $cart_item = $this->findItem($new_item->id);
                
                return response()->json([
                    'data' => [
                        'item' => $this->formatCartItem($cart_item)
                    ]
                ], 200);
            }
            else return response()->json([
                'error' => 'Something went wrong!'
            ], 500);
        }
    }

    public function deleteItem(Request $request, $item_id)
    {
        $cart_item = $this->findItem($item_id);

        if($cart_item) {
            $cart_item->delete();
            return response()->json([
                'itemId' => $cart_item->id,
            ], 200);
        }
        else return response()->json([
            'error' => 'Cart Item not found!' 
        ], 404);
    }

    public function requestItem(Request $request, $item_id)
    {
        $cart_item = $this->findItem($item_id);

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
}
