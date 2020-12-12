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
use DB;

class OrderController extends Controller
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

    private $defaultImages = [
        'boar' => 'boar_default.jpg',
        'sow' => 'sow_default.jpg',
        'semen' => 'semen_default.jpg',
        'gilt' => 'gilt_default.jpg',
    ];

    private function formatOrder($item)
    {
        $order = [];

        $product = $item->product;
        $customer = $item->customer;
        $is_product_deleted = $product->trashed();

        $order['id'] = $item->id;
        $order['status'] = $item->order_status;
        $order['statusTime'] = $item->created_at;

        $order['product'] = [
            'id' => $product->id,
            'name' => $product->name,
            'type' => $product->type,
            'breed' => $this->transformBreedSyntax($product->breed->name),
            'imageUrl' => route('serveImage',
                [
                    'size' => 'small',
                    'filename' => $is_product_deleted
                        ? $this->defaultImages[$product->type]
                        : $product->primaryImage->name
                ]
                ),
            'isDeleted' => $is_product_deleted,
            'isUnique' => $product->is_unique === 1
        ];

        $order['customer'] = [
            'id' => $customer->user->id,
            'name' => $customer->user->name
        ];

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

    private function getReservation($id, $status)
    {
        $breeder = $this->user->userable;

        $order = $breeder
            ->reservations()
            ->with(
                'product.breed',
                'product.primaryImage',
                'customer.user'
            )
            ->join('swine_cart_items', function ($join) {
                $join
                    ->on(
                        'product_reservations.id',
                        '=',
                        'swine_cart_items.reservation_id'
                    );
            })
            ->join('transaction_logs', function ($join) use ($status) {
                $join
                    ->on(
                        'swine_cart_items.id',
                        '=',
                        'transaction_logs.swineCart_id'
                    )
                    ->where('transaction_logs.status', $status);
            })
            ->select(
                'product_reservations.*',
                'transaction_logs.created_at as created_at'
            )
            ->where('order_status', $status)
            ->where('product_reservations.id', $id)
            ->first();

        return $this->formatOrder($order);
    }

    public function getOrders(Request $request)
    {
        $breeder = $this->user->userable;
        $status = $request->status;

        $statuses = [
            'requested' => true,
            'reserved' => true,
            'on_delivery' => true,
            'sold' => true,
        ];

        if ($status && array_key_exists($status, $statuses)) {
            if ($status === 'requested') {

                $orders = $breeder
                    ->products()
                    ->with('breed', 'primaryImage')
                    ->withCount(['swineCartItem' => function ($query) {
                        return $query
                            ->where('if_requested', 1)
                            ->where('reservation_id', 0);
                    }])
                    ->where('status', 'requested')
                    ->paginate($request->limit);

                $formatted = $orders->map(function ($item) {

                    $order = [];

                    $is_product_deleted = $item->trashed();

                    $order['status'] = $item->status;
                    $order['requestCount'] = $item->swine_cart_item_count;

                    $order['product'] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'type' => $item->type,
                        'breed' => $this->transformBreedSyntax(
                            $item->breed->name
                        ),
                        'imageUrl' => route('serveImage',
                            [
                                'size' => 'small',
                                'filename' => $is_product_deleted
                                    ? $this->defaultImages[$item->type]
                                    : $item->primaryImage->name
                            ]
                        ),
                        'isDeleted' => $is_product_deleted,
                        'isUnique' => $item->is_unique === 1
                    ];
    
                    return $order;
                });

                return response()->json([
                    'data' => [
                        'hasNextPage' => $orders->hasMorePages(),
                        'orders' => $formatted,
                    ]
                ], 200);
            }
            else {

                $orders = $breeder
                    ->reservations()
                    ->with(
                        'product.breed',
                        'product.primaryImage',
                        'customer.user'
                    )
                    ->join('swine_cart_items', function ($join) {
                        $join
                            ->on(
                                'product_reservations.id',
                                '=',
                                'swine_cart_items.reservation_id'
                            );
                    })
                    ->join('transaction_logs', function ($join) use ($status) {
                        $join
                            ->on(
                                'swine_cart_items.id',
                                '=',
                                'transaction_logs.swineCart_id'
                            )
                            ->where('transaction_logs.status', $status);
                    })
                    ->select(
                        'product_reservations.*',
                        'transaction_logs.created_at as created_at'
                    )
                    ->where('order_status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($request->limit);

                $formatted = $orders->map(function ($item) {
                    return $this->formatOrder($item);
                });

                return response()->json([
                    'data' => [
                        'hasNextPage' => $orders->hasMorePages(),
                        'orders' => $formatted,
                    ]
                ], 200);
            }
        }
        else return response()->json([
            'error' => 'Invalid Status!'
        ], 400);

    }

    public function getOrder(Request $request, $id)
    {
        $breeder = $this->user->userable;

        $item = $breeder
            ->reservations()
            ->with(
                'customer.user',
                'product'
            )
            ->with(['transactionLogs' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->find($id);

        if ($item) {

            $order = [];

            $product = $item->product;
            $breed = $product->breed;
            $customer = $item->customer;
            $logs = $item->transactionLogs;
            $is_product_deleted = $product->trashed();

            $order['id'] = $item->id;

            $order['product'] = [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'breed' => $this->transformBreedSyntax($breed->name),
                'farmLocation' => $product->farmFrom->province,
                'imageUrl' => route('serveImage',
                    [
                        'size' => 'small',
                        'filename' => $is_product_deleted
                            ? $this->defaultImages[$product->type]
                            : $product->primaryImage->name
                    ]
                ),
                'isDeleted' => $is_product_deleted,
                'isUnique' => $product->is_unique === 1
            ];

            $special_request = trim($item->special_request);

            $order['details'] = [
                'quantity' => $item->quantity,
                'deliveryDate' => $item->delivery_date,
                'dateNeeded' => $item->date_needed === '0000-00-00'
                    ? null
                    : $item->date_needed,
                'specialRequest' => $special_request === ''
                    ? null
                    : $special_request,
            ];

            $order['logs'] = $logs->map(function ($item) {
                return [
                    'status' => $item->status,
                    'createdAt' => $item->created_at,
                ];
            });

            $order['customer'] = [
                'id' => $customer->user->id,
                'name' => $customer->user->name
            ];

            $latestLog = $order['logs'][0];

            $order['status'] = $latestLog['status'];
            $order['statusTime'] = $latestLog['createdAt'];

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => $order
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Order does not exist!'
        ], 404);
    }

    public function getRequests(Request $request, $product_id)
    {
        $requests = SwineCartItem::where('product_id', $product_id)
            ->with('product', 'customer.user')
            ->where('if_requested', 1)
            ->where('reservation_id', 0)
            ->paginate($request->limit);

        $formatted = $requests->map(function ($item) {
            $request = [];

            $customer = $item->customer;
            $product_type = $item->product->type;
            $user = $customer->user;

            $request['id'] = $item->id;
            $request['quantiy'] = $item->quantity;
            $request['dateNeeded'] = $product_type == 'semen'
                ? $item->date_needed == '0000-00-00'
                    ?
                        null
                    :
                        $item->date_needed
                : null;

            $request['specialRequest'] = trim($item->special_request) === ''
                ? null
                : trim($item->special_request);

            $request['customer'] = [
                'id' => $user->id,
                'name' => $user->name,
                'province' => $customer->address_province
            ];

            return $request;
        });

        return response()->json([
            'data' => [
                'hasNextPage' => $requests->hasMorePages(),
                'requests' => $formatted
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
                            'success' => true,
                            'data' => [
                                'order' => $this->getReservation(
                                    $result[2],
                                    $status
                                )
                            ],
                        ], 200);
                    }
                }
                else if($status == 'on_delivery') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'success' => true,
                            'data' => [
                                'order' => $this->getReservation(
                                    $request->reservation_id,
                                    $status
                                )
                            ],
                        ], 200);
                    }
                }
                else if($status == 'sold') {
                    if($result[0] == 'OK') {
                        return response()->json([
                            'success' => true,
                            'data' => [
                                'order' => $this->getReservation(
                                    $request->reservation_id,
                                    $status
                                )
                            ],
                        ], 200);
                    }
                }
            }

        }

        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function deleteRequest(Request $request, $cart_id)
    {

        $cart_item = SwineCartItem::with('product')->find($cart_id);

        $product = $cart_item->product;

        $cart_item->reservation_id = 0;
        $cart_item->quantity = ($product->type == 'semen') ? 2 : 1;
        $cart_item->if_requested = 0;
        $cart_item->date_needed = '0000-00-00';
        $cart_item->special_request = "";
        $cart_item->save();

        $count = SwineCartItem::where('product_id', $product->id)
            ->where('if_requested', 1)
            ->get()
            ->count();

        if ($count == 0) {
            $product->status = 'displayed';
            $product->save();
        }

        return response()->json([
            'success' => true,
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
                            'success' => true
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
