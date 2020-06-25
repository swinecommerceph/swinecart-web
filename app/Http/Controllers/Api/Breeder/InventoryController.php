<?php

namespace App\Http\Controllers\Api\Breeder;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Breed;
use App\Models\Image;
use App\Models\SwineCartItem;
use App\Repositories\DashboardRepository;
use App\Repositories\CustomHelpers;

use Auth;
use JWTAuth;
use Mail;
use Storage;
use Config;

class InventoryController extends Controller
{   

    use CustomHelpers {
        transformBreedSyntax as private;
        transformDateSyntax as private;
        computeAge as private;
    }

    protected $dashboard;

    public function __construct(DashboardRepository $dashboard) 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
        $this->dashboard = $dashboard;
    }

    private function transformOrder($reservation)
    {   
        $order = [];

        $status_time = $reservation->transactionLogs->first()->created_at;
        $user = $reservation->customer->users()->first();
        $product = $reservation->product;

        $order['status'] = $reservation->order_status;
        
        $order['product']['id'] = $product->id;
        $order['product']['name'] = $product->name;
        $order['product']['type'] = $product->type;
        $order['product']['breed'] = $this->transformBreedSyntax($product->breed->name);
        $order['product']['image'] = route('serveImage', ['size' => 'small', 'filename' => $product->primaryImage->name]);
    
        $order['reservation'] = null;
        $order['reservation']['id'] = $reservation->id;
        $order['reservation']['quantity'] = $reservation->quantity;
        $order['reservation']['status_time'] = $status_time;
        $order['reservation']['date_needed'] = $product->type == 'semen' ? $reservation->date_needed == '0000-00-00' ? null : $reservation->date_needed : null;
        $order['reservation']['delivery_date'] = $reservation->delivery_date;
        $order['reservation']['special_request'] = $reservation->special_request;
        $order['reservation']['customer_id'] = $reservation->customer_id;
        $order['reservation']['customer_name'] = $user->name;
        $order['reservation']['user_id'] = $user->id;

        return $order;
    }

    private function getBreederProduct($breeder, $product_id)
    {
        $breeder_id = $breeder->id;

        return Product::where([
            ['breeder_id', '=', $breeder_id],
            ['id', '=', $product_id]
        ])->first();
    }

    private function getReservation($reservation_id, $order_status)
    {
        $breeder = $this->user->userable;
        $reservation = $breeder
            ->reservations()
            ->with(['transactionLogs' => function ($query) use ($order_status) {
                    return $query->where('status', $order_status);
                }])
            ->with('product.breed', 'product.primaryImage', 'customer') 
            ->find($reservation_id);

        // return $reservation;
        return $this->transformOrder($reservation);
    }

    public function getProducts(Request $request)
    {
        $breeder = $this->user->userable;
        $limit = $request->limit;

        if ($request->status === 'requested') {

            $orders = $breeder
                ->products()
                ->with('breed', 'primaryImage')
                ->withCount(['swineCartItem' => function ($query) {
                    return $query->where('if_requested', 1)->where('reservation_id', 0);
                }])
                ->where('status','requested')
                // ->where('quantity','<>', 0)
                ->paginate($limit)
                ->map(function ($item) {
                    $order = [];
                    
                    $order['status'] = $item->status;
                    $order['requestCount'] = $item->swine_cart_item_count;

                    $order['product']['id'] = $item->id;
                    $order['product']['name'] = $item->name;
                    $order['product']['type'] = $item->type;
                    $order['product']['breed'] = $this->transformBreedSyntax($item->breed->name);
                    $order['product']['image'] = route('serveImage', ['size' => 'small', 'filename' => $item->primaryImage->name]);

                    return $order;
                });

            return response()->json([
                'data' => [
                    'orders' => $orders
                ]
            ], 200);
        }
        else {

            $order_status = $request->status;

            $orders = $breeder
                ->reservations()
                ->with(['transactionLogs' => function ($query) use ($order_status) {
                    return $query->where('status', $order_status)->latest();
                }])
                ->with('product.breed', 'product.primaryImage', 'customer')
                ->where('order_status', $order_status)
                ->get()
                ->map(function ($item) {
                    return $this->transformOrder($item);
                })
                ->sortByDesc('reservation.status_time')
                ->forPage($request->page, $request->limit)
                ->values()
                ->all();

            return response()->json([
                'data' => [
                    'orders' => $orders
                ]
            ], 200);
        }
    }

    public function getProductRequests(Request $request, $product_id)
    {
        $limit = $request->limit;

        $requests = SwineCartItem::where('product_id', $product_id)
            ->with('product', 'customer.users')
            ->where('if_requested', 1)
            ->where('reservation_id', 0)
            ->paginate($limit)
            ->map(function ($item) {
                $request = [];

                $customer = $item->customer;
                $product_type = $item->product->type;
                $user = $customer->users[0];

                $request['product_id'] = $item->product_id;
                $request['customer_id'] = $item->customer->id;
                $request['swinecart_id'] = $item->id;
                $request['request_quantity'] = $item->quantity;
                $request['date_needed'] = $product_type == 'semen' ? $item->date_needed == '0000-00-00' ? null : $item->date_needed : null;
                $request['special_request'] = $item->special_request;

                $request['customer_name'] = $user->name;
                $request['customer_province'] = $customer->address_province;
                $request['user_id'] = $user->id;

                return $request;
            });

        return response()->json([
            'data' => [
                'requests' => $requests,
            ]
        ], 200);
    }

    public function updateOrderStatus(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        $isAddedToCart = SwineCartItem::where('product_id', $product_id)->first();

        if($product && $isAddedToCart) {
            $result = $this->dashboard->updateStatus($request, $product);
            $status = $request->status;

            if(is_string($result) && $result == 'Invalid operation') {
                return response()->json([
                    'error' => 'Invalid Operation',
                ], 400);
            }
            else {
                if($status == 'reserved') {
                    if($result[0] == 'fail') {
                        return response()->json([
                            'error' => $result[1],
                        ], 409);
                    }
                    else {
                        return response()->json([
                            'data' => [
                                'product' => $this->getReservation($result[2], $status)
                            ]
                        ], 200);
                    }
                }
                else if($status == 'on_delivery') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'data' => [
                                'product' => $this->getReservation($request->reservation_id, $status)
                            ]
                        ], 200);
                    }
                }
                else if($status == 'sold') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'data' => [
                                'product' => $this->getReservation($request->reservation_id, $status)
                            ]
                        ], 200);
                    }
                }
            }

        }

        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function removeProductRequest(Request $request, $cart_id)
    {
        $cart_item = SwineCartItem::with('product')->find($cart_id);

        $product = $cart_item->product;

        $cart_item->reservation_id = 0;
        $cart_item->quantity = ($product->type == 'semen') ? 2 : 1;
        $cart_item->if_requested = 0;
        $cart_item->date_needed = '0000-00-00';
        $cart_item->special_request = "";
        $cart_item->save();

        $product->status = "displayed";
        // $product->quantity = ($product->type == 'semen') ? -1 : 1;
        $product->save();

        return response()->json([
            'data' => [
                'cartItem' => $cart_item,
            ]
        ], 200);
    }

    public function cancelTransaction(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);
        $isAddedToCart = SwineCartItem::where('product_id', $product_id)->first();

        if($product && $isAddedToCart) {
            $reservation = $breeder->reservations()->with('product')->find($request->reservation_id);

            if($reservation) {
                $result = $this->dashboard->updateStatus($request, $product);
                $status = $request->status;

                if(is_string($result) && $result == 'Invalid operation') {
                    return response()->json([
                        'error' => 'Invalid Operation',
                    ], 400);
                }
                else {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'message' => 'Update Order Status successful!'
                        ], 200);
                    }
                }
            }
            else return response()->json([
                'error' => 'Product does not exist!'
            ], 404);

        }

        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);

    }

    public function getCustomer(Request $request, $customer_id)
    {
        $customer = Customer::find($customer_id)->with('user')->first();
        $user = $customer->user->first();
    
        return response()->json([
            'data' => [
                'customer' => [
                    'name' => $user->name,
                    'addressLine1' => $customer->address_addressLine1,
                    'addressLine2' => $customer->address_addressLine2,
                    'province' => $customer->address_province,
                    'mobile' => $customer->mobile,
                ]
            ]
        ], 200);
    }
}
