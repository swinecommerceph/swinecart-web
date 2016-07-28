<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\Breeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SwineCartItem;

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
        return $breeder->products;
    }

    /**
     * Get sold products of a Breeder.
     * Include boar, sow, and semen
     * quantity
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getSoldProducts(Breeder $breeder)
    {
        $products = $breeder->products;
        $overallQuery = $products->where('status','sold');
        $boarQuery = $products->where('status','sold')->where('type','boar');
        $sowQuery = $products->where('status','sold')->where('type','sow');
        $semenQuery = $products->where('status','sold')->where('type','semen');

        return [
            'overall' => $overallQuery->count(),
            'boar' => $boarQuery->count(),
            'sow' => $sowQuery->count(),
            'semen' => $semenQuery->count()
            ];
    }

    /**
     * Get available products of a Breeder.
     * Include boar, sow, and semen
     * quantity
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getAvailableProducts(Breeder $breeder)
    {
        $products = $breeder->products;
        $overallQuery = $products->where('status','displayed');
        $boarQuery = $products->where('status','displayed')->where('type','boar');
        $sowQuery = $products->where('status','displayed')->where('type','sow');
        $semenQuery = $products->where('status','displayed')->where('type','semen');

        return [
            'overall' => $overallQuery->count(),
            'boar' => $boarQuery->count(),
            'sow' => $sowQuery->count(),
            'semen' => $semenQuery->count()
            ];
    }

    /**
     * Get the statuses of the products of a Breeder
     * Include hidden, displayed, requested,
     * reserved, paid, on_delivery,
     * and sold quantity
     *
     * @param  Breeder $breeder
     * @return Array
     */
    public function getProductStatuses(Breeder $breeder)
    {
        $products = $breeder->products;
        $hiddenQuery = $products->where('status','hidden');
        $displayedQuery = $products->where('status','displayed');
        $requestedQuery = $products->where('status','requested');
        $reservedQuery = $products->where('status','reserved');
        $onDeliveryQuery = $products->where('status','on_delivery');
        $paidQuery = $products->where('status','paid');
        $soldQuery = $products->where('status','sold');

        return [
            'hidden' => $hiddenQuery->count(),
            'displayed' => $displayedQuery->count(),
            'requested' => $requestedQuery->count(),
            'reserved' => $reservedQuery->count(),
            'onDelivery' => $onDeliveryQuery->count(),
            'paid' => $paidQuery->count(),
            'sold' => $soldQuery->count(),
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
        $reviews = $breeder->reviews;
        $deliveryRating = $reviews->avg('rating_delivery');
        $transactionRating = $reviews->avg('rating_transaction');
        $productQualityRating = $reviews->avg('rating_productQuality');
        $overallRating = (($deliveryRating + $transactionRating + $productQualityRating)/15)*100;


        return [
            'overall' => round($overallRating,2),
            'delivery' => round($deliveryRating,1),
            'transaction' => round($transactionRating,1),
            'productQuality' => round($productQualityRating,1)
            ];
    }

    public function getHeatMap(Breeder $breeder)
    {
        # code...
    }

    /**
     * Update product status
     *
     * @param  Request      $request
     * @return Array/String
     */
    public function updateStatus(Request $request, Product $product, $status)
    {
        switch ($status) {
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
                if(!$product->code) $product->code = str_random(6);
                $product->save();
                return "Product on delivery";

            case 'paid':
                $product->status = 'paid';
                if(!$product->code) $product->code = str_random(6);
                $product->save();
                return "Product Paid";

            default:
                return "Invalid operation";
        }
    }

    /**
     * Get customers who requested a specific product
     *
     * @param  Integer   $productId
     * @return Array
     */
    public function getProductRequests($productId)
    {
        $productRequests = SwineCartItem::where('product_id', $productId)->where('if_requested', 1)->get();
        $productRequestDetails = [];

        foreach ($productRequests as $productRequest) {
            $customer = Customer::find($productRequest->customer_id);
            $province = $customer->address_province;
            $name = $customer->users()->first()->name;
            array_push($productRequestDetails,
                [
                    'customer_id' => $productRequest->customer_id,
                    'customer_name' => $name,
                    'province' => $province,
                    '_token' => csrf_token()
                ]
            );
        }

        return $productRequestDetails;
    }
}
