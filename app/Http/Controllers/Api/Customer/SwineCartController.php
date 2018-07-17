<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use App\Http\Requests\ProductRequest;

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
    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }


    public function addToCart(Request $request, $product_id)
    {
        $customer = $this->user->userable;
        $items = $customer->swineCartItems();
        $cart = $items->get();

        $item = $items->where([
            ['product_id', $product_id],
            ['reservation_id', 0]
        ])->first();
        
        $product = Product::find($product_id);

        if($item) {
            if($item->if_requested) {
                return response()->json([
                    'message' => 'Product already requested!',
                    'cart' => $cart,
                    'data' => $product->name
                ]);
            }
            else {
               return response()->json([
                    'message' => 'Item already Added!',
                    'cart' => $cart,
                    'data' => $product->name
                ]); 
            }
        }
        else {
            $new_item = new SwineCartItem;
            $new_item->product_id = $product_id;
            $new_item->quantity = $product->type == 'semen' ? 2 : 1;
            
            $items->save($new_item);

            return response()->json([
                'message' => 'Add to Cart successful!',
                'cart' => $cart,
                'data' => [
                    'product' => $product,
                    'count' => $customer->swineCartItems()
                        ->where('if_requested', 0)
                        ->count()
                ]
            ]);
        }

        
    }
}
