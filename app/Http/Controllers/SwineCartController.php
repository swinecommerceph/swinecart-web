<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Http\Requests;
use App\Models\Customer;
use App\Models\Breeder;
use App\Models\Breed;
use App\Models\Image;
use App\Models\SwineCartItem;
use App\Models\Product;
use App\Models\Review;
use Auth;

class SwineCartController extends Controller
{
    protected $user;

    /**
     * Create new CustomerController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
        $this->middleware('updateProfile:customer');
        $this->user = Auth::user();
    }

    /**
     * Add to Swine Cart the product picked by the user
     * AJAX
     *
     * @param  Request $request
     * @return Array
     */
    public function addToSwineCart(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            $swineCartItems = $customer->swineCartItems();
            $checkProduct = $swineCartItems->where('product_id',$request->productId)->get();

            // Check first if product is already in Swine Cart
            if(!$checkProduct->isEmpty()){
                // Then check if it is already requested
                if($checkProduct->first()->ifRequested) $returnArray = ['requested', Product::find($request->productId)->name];
                else $returnArray = ['fail', Product::find($request->productId)->name];
                return $returnArray;
            }
            else{
                $item = new SwineCartItem;
                $item->product_id = $request->productId;
                $item->quantity = 1;

                $swineCartItems->save($item);

                $returnArray = ['success', Product::find($request->productId)->name, $customer->swineCartItems()->where('if_requested',0)->count()];
                return $returnArray;
            }
        }
    }

    public function rate(Request $request){
      if($request->ajax()){
        $reviews = Breeder::find($request->breederId)->reviews();
        $review = new Review;
        $review->comment = $request->comment;
        $review->rating_delivery = $request->delivery;
        $review->rating_transaction = $request->transaction;
        $review->rating_productQuality = $request->productQuality;
        $review->rating_afterSales = $request->afterSales;
        $reviews->save($review);
      }
    }

    /**
     * Requests item from Swine Cart
     * AJAX
     *
     * @param  Request $request
     */
    public function requestSwineCart(Request $request)
    {
      if ($request->ajax()) {
        $customer = $this->user->userable;
        $swineCartItems = $customer->swineCartItems();
        $requested = $swineCartItems->find($request->itemId);
        $requested->if_requested = 1;
        $product = Product::find($request->productId);
        $product->status = "requested";
        $product->save();
        $requested->save();
        // dd($checkProduct->if_requested);
      }
    }

    /**
     * Delete item from Swine Cart
     * AJAX
     *
     * @param  Request $request
     * @return Array
     */
    public function deleteFromSwineCart(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            $item = $customer->swineCartItems()->where('id',$request->itemId)->get()->first();
            $productName = Product::find($item->product_id)->name;
            if($item) {
                $item->delete();
                return ["success", $productName, $customer->swineCartItems()->where('if_requested',0)->count()];
            }
            else return ["not found", $item->product_id];
        }
        else {
          $customer = $this->user->userable;
          $item = $customer->swineCartItems()->where('id',$request->itemId)->get()->first();
          $productName = Product::find($item->product_id)->name;
          if($item) {
              $item->delete();
              return ["success", $productName, $customer->swineCartItems()->where('if_requested',0)->count()];
          }
          else return ["not found", $item->product_id];
        }
    }

    /**
     * Get items in the Swine Cart
     * AJAX
     *
     * @param  Request $request
     * @return JSON
     */
    public function getSwineCartItems(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            $swineCartItems = $customer->swineCartItems()->where('if_requested',0)->get();
            $items = [];

            foreach ($swineCartItems as $item) {
                $itemDetail = [];
                $product = Product::find($item->product_id);
                $itemDetail['item_id'] = $item->id;
                $itemDetail['product_id'] = $item->product_id;
                $itemDetail['product_name'] = $product->name;
                $itemDetail['product_type'] = $product->type;
                $itemDetail['product_breed'] = Breed::find($product->breed_id)->name;
                $itemDetail['img_path'] = '/images/product/'.Image::find($product->primary_img_id)->name;
                $itemDetail['breeder'] = Breeder::find($product->breeder_id)->users()->first()->name;
                $itemDetail['token'] = csrf_token();
                array_push($items,$itemDetail);
            }

            $itemsCollection = collect($items);
            return $itemsCollection->toJson();
        }
        else {
          $customer = $this->user->userable;
          $swineCartItems = $customer->swineCartItems()->get();
          $products = [];

          foreach ($swineCartItems as $item) {
              $product = Product::find($item->product_id);
              $itemDetail['request_status'] = $item->if_requested;
              $itemDetail['status'] = $product->status;
              $itemDetail['item_id'] = $item->id;
              $itemDetail['customer_id'] = $customer->id;
              $itemDetail['breeder_id'] = $product->breeder_id;
              $itemDetail['product_id'] = $item->product_id;
              $itemDetail['product_name'] = $product->name;
              $itemDetail['product_type'] = $product->type;
              $itemDetail['product_breed'] = Breed::find($product->breed_id)->name;
              $itemDetail['img_path'] = '/images/product/'.Image::find($product->primary_img_id)->name;
              $itemDetail['breeder'] = Breeder::find($product->breeder_id)->users()->first()->name;
              $itemDetail['token'] = csrf_token();
              array_push($products,(object) $itemDetail);
          }

          return view('user.customer.swineCart', compact('products'));
        }
    }

    /**
     * Get number of items in the Swine Cart
     * AJAX
     *
     * @param  Request $request
     * @return Integer
     */
    public function getSwineCartQuantity(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            return $customer->swineCartItems()->where('if_requested',0)->count();
        }
    }
}
