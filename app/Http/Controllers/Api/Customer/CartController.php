<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\CustomHelpers;

use JWTAuth;

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
                'product.breeder.user',
                'product.primaryImage'
            )
            ->where('if_rated', 0)
            ->where('if_requested', 0)
            ->orderBy('id', 'desc');
    }

    private function findItem($item_id)
    {
        return $this->getCartItems()->where('id', $item_id)->first();
    }

    private function formatCartItem($cart_item)
    {
        $product = $cart_item->product;
        $breed = $product->breed;
        $breeder = $product->breeder->user;

        $is_deleted = $product->trashed();

        return [
            'id' => $cart_item->id,
            'product' => [
                'id' =>  $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'age' => $product->birthdate === '0000-00-00'
                    ? null
                    : $this->computeAge($product->birthdate),
                'breed' => $this->transformBreedSyntax($breed->name),
                'breederName' => $breeder->name,
                'farmLocation' => $product->farmFrom->province,
                'imageUrl' => route('serveImage',
                    [
                        'size' => 'small',
                        'filename' => $is_deleted
                        ? $this->defaultImages[$product->type]
                        : $product->primaryImage->name
                    ]
                ),
                'isDeleted' => $is_deleted,
                'isUnique' => $product->is_unique === 1
            ]
        ];
    }

    public function getItems(Request $request)
    {
        $items = $this->getCartItems()->paginate($request->limit);

        $formatted = $items
            ->map(function ($item) {
                return $this->formatCartItem($item);
            });

        return response()->json([
            'data' => [
                'hasNextPage' => $items->hasMorePages(),
                'items' => $formatted,
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

        if ($item) {
            if ($item->if_requested) {
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
                'data' => [
                    'itemId' => $cart_item->id,
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Cart Item not found!'
        ], 404);
    }
}
