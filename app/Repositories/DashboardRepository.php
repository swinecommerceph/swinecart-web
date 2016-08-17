<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\Breeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Breed;
use App\Models\SwineCartItem;
use App\Models\Image;

class DashboardRepository
{
    /**
     * Get all of the products for the Customer
     *
     * @return Collection
     */
    public function forCustomer()
    {
        return Product::all();
    }

    /**
     * Get all of the products of a given Breeder
     *
     * @param  Breeder      $breeder
     * @return Collection
     */
    public function forBreeder(Breeder $breeder)
    {
        $products = $breeder->products()->whereIn('status',['requested','reserved','on_delivery','paid','sold'])->get();
        foreach ($products as $product) {
            $product->img_path = '/images/product/'.Image::find($product->primary_img_id)->name;
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);

            // Attach Customer name if it exists
            if($product->customer_id){
                $customer = Customer::find($product->customer_id);
                $product->customer_name = $customer->users()->first()->name;
            }
        }
        return $products;
    }

    /**
     * Get the statuses of the products of a Breeder
     * Include hidden, displayed, requested,
     * reserved, paid, on_delivery,
     * and sold quantity
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getProductStatus(Breeder $breeder, $status)
    {
        $products = $breeder->products;
        $overallQuery = $products->where('status',$status);
        $boarQuery = $products->where('status',$status)->where('type','boar');
        $sowQuery = $products->where('status',$status)->where('type','sow');
        $semenQuery = $products->where('status',$status)->where('type','semen');

        return [
            'overall' => $overallQuery->count(),
            'boar' => $boarQuery->count(),
            'sow' => $sowQuery->count(),
            'semen' => $semenQuery->count()
            ];
    }

    /**
     * Get the ratings of the Breeder.
     * Include overall, delivery,
     * transaction, and product
     * quality rating
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getRatings(Breeder $breeder)
    {
        $reviewDetails = [];
        $query = $breeder->reviews()->orderBy('created_at','desc')->get();
        $reviews = $query->take(3);
        $deliveryRating = $query->avg('rating_delivery');
        $transactionRating = $query->avg('rating_transaction');
        $productQualityRating = $query->avg('rating_productQuality');
        $overallRating = ($deliveryRating + $transactionRating + $productQualityRating)/3;

        foreach ($reviews as $review) {
            $reviewDetail = [];
            $reviewDetail['customerName'] = Customer::find($review->customer_id)->users()->first()->name;
            $reviewDetail['comment'] = $review->comment;
            array_push($reviewDetails, $reviewDetail);
        }

        return [
            'overall' => round($overallRating,2),
            'delivery' => round($deliveryRating,1),
            'transaction' => round($transactionRating,1),
            'productQuality' => round($productQualityRating,1),
            'reviews' => $reviewDetails
            ];
    }

    public function getHeatMap(Breeder $breeder)
    {
        # code...
    }

    /**
     * Get customers who requested a specific product
     *
     * @param  Integer   $productId
     * @return Array
     */
    public function getProductRequests($productId)
    {
        // dd($productId);
        $productRequests = SwineCartItem::where('product_id', $productId)->where('if_requested', 1)->get();
        $productRequestDetails = [];

        foreach ($productRequests as $productRequest) {
            $customer = Customer::find($productRequest->customer_id);
            $province = $customer->address_province;
            $name = $customer->users()->first()->name;
            array_push($productRequestDetails,
                [
                    'customerId' => $productRequest->customer_id,
                    'customerName' => $name,
                    'customerProvince' => $province
                ]
            );
        }

        return $productRequestDetails;
    }

    /**
     * Update product status
     *
     * @param  Request      $request
     * @return Array/String
     */
    public function updateStatus(Request $request, Product $product)
    {
        switch ($request->status) {
            case 'reserved':
                // Check if product is already reserved
                if(!$product->customer_id){
                    $product->status = 'reserved';
                    $product->customer_id = $request->customer_id;
                    $product->save();
                    return ['success', $product->name.' reserved to '.Customer::find($request->customer_id)->users()->first()->name];
                }
                else {
                    return ['fail', $product->name.' is already reserved to '.Customer::find($product->customer_id)->users()->first()->name];
                }

            case 'on_delivery':
                $product->status = 'on_delivery';
                $product->save();
                return "OK";

            case 'paid':
                $product->status = 'paid';
                $product->save();
                return "OK";

            case 'sold':
                $product->status = 'sold';
                $product->save();
                return "OK";

            default:
                return "Invalid operation";
        }
    }

    /**
     * Parse $breed if it contains '+' (ex. landrace+duroc)
     * to "Landrace x Duroc"
     *
     * @param  String   $breed
     * @return String
     */
    private function transformBreedSyntax($breed)
    {
        if(str_contains($breed,'+')){
            $part = explode("+", $breed);
            $breed = ucfirst($part[0])." x ".ucfirst($part[1]);
            return $breed;
        }

        return ucfirst($breed);
    }
}
