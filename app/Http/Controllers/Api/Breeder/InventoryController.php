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
        $product = [];

        $status_time = $reservation->transactionLogs->where('status', $reservation->order_status)->sortByDesc('created_at')->first()->created_at;

        $product['id'] = $reservation->product->id;
        $product['name'] = $reservation->product->name;
        $product['type'] = ucfirst($reservation->product->type);
        $product['breed'] = $this->transformBreedSyntax(Breed::find($reservation->product->breed_id)->name);
        $product['img_path'] = route('serveImage', ['size' => 'small', 'filename' => Image::find($reservation->product->primary_img_id)->name]);
        $product['status'] = $reservation->product->status;
        
        $product['reservation'] = null;
        $product['reservation']['id'] = $reservation->id;
        $product['reservation']['quantity'] = $reservation->quantity;
        $product['reservation']['order_status'] = $reservation->order_status;
        $product['reservation']['status_time'] = $this->transformDateSyntax($status_time, 3);
        $product['reservation']['date_needed'] = $this->transformDateSyntax($reservation->date_needed);
        $product['reservation']['delivery_date'] = $this->transformDateSyntax($reservation->delivery_date);
        $product['reservation']['special_request'] = $reservation->special_request;
        $product['reservation']['customer_id'] = $reservation->customer_id;
        $product['reservation']['customer_name'] = Customer::find($reservation->customer_id)->users()->first()->name;

        return $product;
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
        
        if ($request->input('status') === 'requested') {
            $perpage = $request->perpage;

            $results = $breeder
                ->products()
                ->where('status','requested')
                ->where('quantity','<>', 0)
                ->paginate($perpage);

            $products = $results->items();
            $count = $results->count();

            $products = array_map(function ($item) {
                $product = [];

                $product['id'] = $item->id;
                $product['name'] = $item->name;
                $product['type'] = ucfirst($item->type);
                $product['breed'] = $this->transformBreedSyntax(Breed::find($item->breed_id)->name);
                $product['img_path'] = route('serveImage', ['size' => 'small', 'filename' => Image::find($item->primary_img_id)->name]);
                $product['status'] = $item->status;
                
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

                return $product;
            }, $products);
        
            return response()->json([
                'message' => 'Get Inventory Products successful!',
                'data' => [
                    'count' => $count,
                    'products' => $products

                ]
            ], 200);
        }
        else {

            $order_status = $request->input('status');
            $perpage = $request->perpage;

            $results = $breeder
                ->reservations()
                ->with('product')
                ->where('order_status', $order_status)
                ->paginate($perpage);

            $products = $results->items();
            $count = $results->count();

            $products = array_map(function ($item) {
                return $this->transformReservedProduct($item);
            }, $products);

            return response()->json([
                'message' => 'Get Inventory Products successful!',
                'data' => [
                    'count' => $count,
                    'products' => $products

                ]
            ], 200);
        }
    }

    public function getProductRequests(Request $request, $product_id)
    {   
        $perpage = $request->perpage;

        $results = SwineCartItem::where('product_id', $product_id)
            ->where('if_requested', 1)
            ->where('reservation_id', 0)
            ->paginate($perpage);

        $requests = $results->items();
        $count = $results->count();

        $requests = array_map(function ($item) {
            $customer = Customer::find($item->customer_id);
            $request = [];

            $request['product_id'] = $item->product_id;
            $request['customer_id'] = $item->customer_id;
            $request['swinecart_id'] = $item->id;
            $request['request_quantity'] = $item->quantity;
            $request['date_needed'] = $item->date_needed == '0000-00-00' ? null : $this->transformDateSyntax($item->date_needed);
            $request['special_request'] = $item->special_request;

            $request['customer_name'] = $customer->users()->first()->name;
            $request['customer_province'] = $customer->address_province;

            return $request;

        }, $requests);

        return response()->json([
            'message' => 'Get Product Requests successful!',
            'data' => [
                'count' => $count,
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

}
