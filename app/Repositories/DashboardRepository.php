<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

use App\Models\Breeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductReservation;
use App\Models\Breed;
use App\Models\SwineCartItem;
use App\Models\FarmAddress;
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
        $products = $breeder->products()->where('status','requested')->get();
        $reservations = $breeder->reservations()->get();
        $items = [];

        // Include all "requested" products
        foreach ($products as $product) {
            if($product->quantity == 0) continue;
            $itemDetail = [];
            $itemDetail['uuid'] = (string) Uuid::uuid4();
            $itemDetail['id'] = $product->id;
            $itemDetail['reservation_id'] = 0;
            $itemDetail['img_path'] = '/images/product/'.Image::find($product->primary_img_id)->name;
            $itemDetail['breeder_id'] = $product->breeder_id;
            $itemDetail['farm_province'] = FarmAddress::find($product->farm_from_id)->province;
            $itemDetail['name'] = $product->name;
            $itemDetail['type'] = $product->type;
            $itemDetail['age'] = $product->age;
            $itemDetail['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $itemDetail['quantity'] = $product->quantity;
            $itemDetail['adg'] = $product->adg;
            $itemDetail['fcr'] = $product->fcr;
            $itemDetail['bft'] = $product->backfat_thickness;
            $itemDetail['status'] = $product->status;
            $itemDetail['customer_id'] = 0;
            $itemDetail['customer_name'] = '';
            array_push($items, (object)$itemDetail);
        }

        // Include "reserved" / "paid" / "on_delivery" / "sold" products
        foreach ($reservations as $reservation) {
            $product = Product::find($reservation->product_id);
            $itemDetail = [];
            $itemDetail['uuid'] = (string) Uuid::uuid4();
            $itemDetail['id'] = $product->id;
            $itemDetail['reservation_id'] = $reservation->id;
            $itemDetail['img_path'] = '/images/product/'.Image::find($product->primary_img_id)->name;
            $itemDetail['breeder_id'] = $product->breeder_id;
            $itemDetail['farm_province'] = FarmAddress::find($product->farm_from_id)->province;
            $itemDetail['name'] = $product->name;
            $itemDetail['type'] = $product->type;
            $itemDetail['age'] = $product->age;
            $itemDetail['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $itemDetail['quantity'] = $reservation->quantity;
            $itemDetail['adg'] = $product->adg;
            $itemDetail['fcr'] = $product->fcr;
            $itemDetail['bft'] = $product->backfat_thickness;
            $itemDetail['status'] = $reservation->order_status;
            $itemDetail['customer_id'] = $reservation->customer_id;
            $itemDetail['customer_name'] = Customer::find($reservation->customer_id)->users()->first()->name;
            array_push($items, (object)$itemDetail);
        }

        // dd($items);
        return collect($items)->toJson();
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
        if($status == 'hidden' || $status == 'displayed' || $status == 'requested'){
            $overallQuery = $products->where('status',$status);
            $boarQuery = $products->where('status',$status)->where('type','boar');
            $sowQuery = $products->where('status',$status)->where('type','sow');
            $giltQuery = $products->where('status',$status)->where('type','gilt');
            $semenQuery = $products->where('status',$status)->where('type','semen');

            return [
                'overall' => $overallQuery->count(),
                'boar' => $boarQuery->count(),
                'sow' => $sowQuery->count(),
                'gilt' => $giltQuery->count(),
                'semen' => $semenQuery->count()
            ];
        }
        else{
            // dd($products);
            $overallQuery = $products->where('status',$status);
            $boarQuery = $products->where('type','boar')->where('quantity', 0);
            $sowQuery = $products->where('type','sow')->where('quantity', 0);;
            $giltQuery = $products->where('type','gilt')->where('quantity', 0);;
            $semenQuery = $products->where('type','semen')->where('quantity', 0);;

            return [
                'overall' => $overallQuery->count(),
                'boar' => $boarQuery->count(),
                'sow' => $sowQuery->count(),
                'gilt' => $giltQuery->count(),
                'semen' => $semenQuery->count()
            ];

        }

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
                    'customerProvince' => $province,
                    'requestQuantity' => $productRequest->quantity
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
                // Check if product is not yet reserved
                if($product->quantity){
                    $resultingQuantity = $product->quantity - $request->request_quantity;
                    $requestQuantity = $request->request_quantity;

                    // Check if requested quantity is greater than the available quantity
                    if($resultingQuantity >= 0) $product->quantity = $resultingQuantity;
                    else{
                        $requestQuantity = $product->quantity;
                        $product->quantity = 0;
                    }
                    $product->save();

                    $reservation = new ProductReservation;
                    $reservation->customer_id = $request->customer_id;
                    $reservation->quantity = $requestQuantity;
                    $reservation->order_status = 'reserved';
                    $product->reservations()->save($reservation);

                    // If product type is not semen remove other requests to this product
                    $productRequests = SwineCartItem::where('product_id', $product->id)->where('if_requested', 1)->where('customer_id', '<>', $request->customer_id);
                    if($product->type != 'semen'){
                        $productRequests->delete();
                        // For further development. Code here must send notification
                        // to the users that the product has been reserved
                        // or already not available for purchase
                        // <code>
                    }
                    else{
                        if($productRequests->count() == 0){
                            $product->status = 'displayed';
                            $product->save();
                        }
                    }

                    return ['success', $product->name.' reserved to '.Customer::find($request->customer_id)->users()->first()->name];
                }
                else {
                    return ['fail', $product->name.' is already reserved to another customer'];
                }

            case 'on_delivery':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'on_delivery';
                $reservation->save();
                return "OK";

            case 'paid':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'paid';
                $reservation->save();
                return "OK";

            case 'sold':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'sold';
                $reservation->save();
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
