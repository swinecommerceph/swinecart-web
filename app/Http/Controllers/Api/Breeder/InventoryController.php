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

    private function transformReservedProduct($reservation)
    {   
        $order = [];

        $status_time = $reservation->transactionLogs->first()->created_at;
        $user = $reservation->customer->users()->first();

        $order['status'] = $reservation->order_status;

        $order['product']['id'] = $reservation->product->id;
        $order['product']['name'] = $reservation->product->name;
        $order['product']['type'] = $reservation->product->type;
        $order['product']['breed'] = $this->transformBreedSyntax($reservation->product->breed->name);
        $order['product']['image'] = route('serveImage', ['size' => 'small', 'filename' => $reservation->product->primaryImage->name]);
    
        $order['reservation'] = null;
        $order['reservation']['id'] = $reservation->id;
        $order['reservation']['quantity'] = $reservation->quantity;
        $order['reservation']['status_time'] = $status_time;
        $order['reservation']['date_needed'] = $reservation->date_needed;
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

    private function getReservation($reservation_id)
    {
        $breeder = $this->user->userable;
        $reservation = $breeder->reservations()->with('product')->find($reservation_id);

        // return $reservation;
        return $this->transformReservedProduct($reservation);
    }

    public function getProducts(Request $request)
    {
        $breeder = $this->user->userable;

        if ($request->status === 'requested') {
            $limit = $request->limit;

            $orders = $breeder
                ->products()
                ->with('breed', 'primaryImage')
                ->where('status','requested')
                ->where('quantity','<>', 0)
                ->paginate($limit)
                ->map(function ($item) {
                    $order = [];
                    
                    $order['status'] = $item->status;
                    $order['request_count'] = $this->getRequests($item->id)->count();

                    $order['product']['id'] = $item->id;
                    $order['product']['name'] = $item->name;
                    $order['product']['type'] = $item->type;
                    $order['product']['breed'] = $this->transformBreedSyntax($item->breed->name);
                    $order['product']['image'] = route('serveImage', ['size' => 'small', 'filename' => $item->primaryImage->name]);

                    // $product['reservation'] = null;
                    // $product['reservation']['id'] = null;
                    // $product['reservation']['quantity'] = null;
                    // $product['reservation']['status'] = null;
                    // $product['reservation']['status_time'] = null;
                    // $product['reservation']['date_needed'] = null;
                    // $product['reservation']['delivery_date'] = null;
                    // $product['reservation']['special_request'] = null;
                    // $product['reservation']['customer_id'] = null;
                    // $product['reservation']['customer_name'] = null;

                    return $order;
                });

            return response()->json([
                'message' => 'Get Inventory Products successful!',
                'data' => [
                    'orders' => $orders
                ]
            ], 200);
        }
        else {

            $order_status = $request->status;
            $limit = $request->limit;

            $orders = $breeder
                ->reservations()
                ->with(['transactionLogs' => function ($query) use ($order_status) {
                    $query->where('status', $order_status)->orderBy('created_at', 'desc');
                }])
                ->with('product.breed', 'product.primaryImage', 'customer') 
                ->where('order_status', $order_status)
                ->paginate($limit)
                ->map(function ($item) {
                    return $this->transformReservedProduct($item);
                });

            return response()->json([
                'message' => 'Get Inventory Products successful!',
                'data' => [
                    'orders' => $orders

                ]
            ], 200);
        }
    }

    private function getRequests($product_id)
    {
        return SwineCartItem::where('product_id', $product_id)
            ->with('customer.users')
            ->where('if_requested', 1)
            ->where('reservation_id', 0);
    }

    public function getProductRequests(Request $request, $product_id)
    {
        $limit = $request->limit;

        $requests = $this->getRequests($product_id)
            ->paginate($limit)
            ->map(function ($item) {
                $request = [];

                $customer = $item->customer;
                $user = $customer->users[0];

                $request['product_id'] = $item->product_id;
                $request['customer_id'] = $item->customer->id;
                $request['swinecart_id'] = $item->id;
                $request['request_quantity'] = $item->quantity;
                $request['date_needed'] = $item->date_needed == '0000-00-00' ? null : $item->date_needed;
                $request['special_request'] = $item->special_request;

                $request['customer_name'] = $user->name;
                $request['customer_province'] = $customer->address_province;
                $request['user_id'] = $user->id;

                return $request;
            });

        return response()->json([
            'message' => 'Get Product Requests successful!',
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
                            'message' => 'Update Order Status successful!',
                            'data' => [
                                'product' => $this->getReservation($result[2])
                            ]
                        ], 200);
                    }
                }
                else if($status == 'on_delivery') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'message' => 'Update Order Status successful!',
                            'data' => [
                                'product' => $this->getReservation($request->reservation_id)
                            ]
                        ], 200);
                    }
                }
                else if($status == 'sold') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'message' => 'Update Order Status successful!',
                            'data' => [
                                'product' => $this->getReservation($request->reservation_id)
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
        $customer = Customer::find($customer_id);
        $user = $customer->users()->first();

        $c = [];

        // $c['id'] = $customer->id;
        $c['name'] = $user->name;
        $c['addressLine1'] = $customer->address_addressLine1;
        $c['addressLine2'] = $customer->address_addressLine2;
        $c['province'] = $customer->address_province;
        $c['mobile'] = $customer->mobile;


        return response()->json([
            'message' => 'Get Customer successful!',
            'data' => [
                'customer' => $c
            ]
        ], 200);
    }
}
