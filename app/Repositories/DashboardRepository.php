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
use App\Notifications\ProductReserved;
use App\Notifications\ProductReservationUpdate;
use App\Notifications\ProductReservedToOtherCustomer;

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
            $itemDetail['age'] = $this->computeAge($product->birthdate);
            $itemDetail['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $itemDetail['quantity'] = $product->quantity;
            $itemDetail['adg'] = $product->adg;
            $itemDetail['fcr'] = $product->fcr;
            $itemDetail['bft'] = $product->backfat_thickness;
            $itemDetail['status'] = $product->status;
            $itemDetail['customer_id'] = 0;
            $itemDetail['customer_name'] = '';
            $itemDetail['date_needed'] = '';
            $itemDetail['special_request'] = '';
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
            $itemDetail['age'] = $this->computeAge($product->birthdate);
            $itemDetail['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $itemDetail['quantity'] = $reservation->quantity;
            $itemDetail['adg'] = $product->adg;
            $itemDetail['fcr'] = $product->fcr;
            $itemDetail['bft'] = $product->backfat_thickness;
            $itemDetail['status'] = $reservation->order_status;
            $itemDetail['customer_id'] = $reservation->customer_id;
            $itemDetail['customer_name'] = Customer::find($reservation->customer_id)->users()->first()->name;
            $itemDetail['date_needed'] = $this->transformDateSyntax($reservation->date_needed);
            $itemDetail['special_request'] = $reservation->special_request;
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

        if($status == 'hidden' || $status == 'displayed' || $status == 'requested'){
            $products = $breeder->products;

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
            // $reservations = ProductReservation::with('product')->get();
            $reservations = $breeder->reservations()->with('product')->get();

            foreach ($reservations as $reservation) {
                $reservation->type = $reservation->product->type;
            }
            $overallQuery = $reservations->where('order_status',$status);
            $boarQuery = $reservations->where('order_status',$status)->where('type','boar');
            $sowQuery = $reservations->where('order_status',$status)->where('type','sow');
            $giltQuery = $reservations->where('order_status',$status)->where('type','gilt');
            $semenQuery = $reservations->where('order_status',$status)->where('type','semen');

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
        $productRequests = SwineCartItem::where('product_id', $productId)->where('if_requested', 1)->where('reservation_id', 0)->get();
        $productRequestDetails = [];

        foreach ($productRequests as $productRequest) {
            $customer = Customer::find($productRequest->customer_id);
            $province = $customer->address_province;
            $name = $customer->users()->first()->name;
            array_push($productRequestDetails,
                [
                    'swineCartId' => $productRequest->id,
                    'customerId' => $productRequest->customer_id,
                    'customerName' => $name,
                    'customerProvince' => $province,
                    'requestQuantity' => $productRequest->quantity,
                    'dateNeeded' => ($productRequest->date_needed == '0000-00-00') ? '' : $this->transformDateSyntax($productRequest->date_needed),
                    'specialRequest' => $productRequest->special_request
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
                // Check if product is available for reservations
                if($product->quantity){
                    $customerName = Customer::find($request->customer_id)->users()->first()->name;

                    // Update quantity of product
                    if($product->type != 'semen') $product->quantity = 0;
                    $product->save();

                    // Make a product reservation
                    $reservation = new ProductReservation;
                    $reservation->customer_id = $request->customer_id;
                    $reservation->quantity = $request->request_quantity;
                    $reservation->date_needed = date_format(date_create($request->date_needed), 'Y-n-j');
                    $reservation->special_request = $request->special_request;
                    $reservation->order_status = 'reserved';
                    $product->reservations()->save($reservation);

                    // Update the Swine Cart item
                    $swineCartItem = SwineCartItem::find($request->swinecart_id);
                    $swineCartItem->reservation_id = $reservation->id;
                    $swineCartItem->save();

                    // Update Transaction Log
                    // This must be put in an event for better performance
                    $transactionLog = $reservation->transactionLog()->first();
                    $decodedStatusTransaction = json_decode($transactionLog->status_transactions, true);
                    $decodedStatusTransaction['reserved'] = date('j M Y (D) g:iA', time());
                    $transactionLog->status_transactions = collect($decodedStatusTransaction)->toJson();
                    $transactionLog->save();

                    // ******** ********
                    // Notify reserved customer
                    $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                    $reservedCustomerUser->notify(new ProductReserved(
                        [
                            'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been reserved to you',
                            'time' => $decodedStatusTransaction['reserved'],
                            'url' => route('cart.items')
                        ]
                    ));
                    // ******** ********

                    // If product type is not semen remove other requests to this product
                    $productRequests = SwineCartItem::where('product_id', $product->id)->where('customer_id', '<>', $request->customer_id)->where('reservation_id',0);

                    if($product->type != 'semen'){
                        // ******** ********
                        // Notify Customer users that the product has been reserved to another customer
                        foreach ($productRequests->get() as $productRequest) {
                            $customerUser = $productRequest->customer->users()->first();
                            $breederName = Product::find($productRequest->product_id)->breeder->users()->first()->name;
                            $customerUser->notify(new ProductReservedToOtherCustomer(
                                [
                                    'description' => 'Sorry, product ' . $product->name . ' was reserved by ' . $breederName . ' to another customer',
                                    'time' => $decodedStatusTransaction['reserved'],
                                    'url' => route('cart.items')
                                ]
                            ));
                        }
                        // ******** ********

                        // Delete requests to this product after notifying Customer users
                        $productRequests->delete();
                    }
                    else{
                        if($productRequests->count() == 0){
                            $product->status = 'displayed';
                            $product->save();

                            return ['success', $product->name.' reserved to '.$customerName, $reservation->id, (string) Uuid::uuid4(), true];
                        }
                    }

                    // [0] - success/fail operation flag
                    // [1] - toast message
                    // [2] - reservation_id
                    // [3] - generated UUID
                    // [4] - flag for removing the parent product display in the UI component
                    return ['success', $product->name.' reserved to '.$customerName, $reservation->id, (string) Uuid::uuid4(), false];
                }
                else {
                    return ['fail', $product->name.' is already reserved to another customer'];
                }

            case 'on_delivery':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'on_delivery';
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $decodedStatusTransaction = json_decode($transactionLog->status_transactions, true);
                $decodedStatusTransaction['on_delivery'] = date('j M Y (D) g:iA', time());
                $transactionLog->status_transactions = collect($decodedStatusTransaction)->toJson();
                $transactionLog->save();

                // ******** ********
                // Notify customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' is on delivery',
                        'time' => $decodedStatusTransaction['on_delivery'],
                        'url' => route('cart.items')
                    ]
                ));
                // ******** ********

                return "OK";

            case 'paid':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'paid';
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $decodedStatusTransaction = json_decode($transactionLog->status_transactions, true);
                $decodedStatusTransaction['paid'] = date('j M Y (D) g:iA', time());
                $transactionLog->status_transactions = collect($decodedStatusTransaction)->toJson();
                $transactionLog->save();

                // ******** ********
                // Notify customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been marked as paid',
                        'time' => $decodedStatusTransaction['paid'],
                        'url' => route('cart.items')
                    ]
                ));
                // ******** ********

                return "OK";

            case 'sold':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'sold';
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $decodedStatusTransaction = json_decode($transactionLog->status_transactions, true);
                $decodedStatusTransaction['sold'] = date('j M Y (D) g:iA', time());
                $transactionLog->status_transactions = collect($decodedStatusTransaction)->toJson();
                $transactionLog->save();

                // ******** ********
                // Notify reserved customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been marked as sold',
                        'time' => $decodedStatusTransaction['sold'],
                        'url' => route('cart.items')
                    ]
                ));
                // ******** ********

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

    /**
     * Transform date original (YYYY-MM-DD) syntax to Month Day, Year
     * @param  String   $birthdate
     * @return String
     */
    private function transformDateSyntax($date)
    {
        return date_format(date_create($date), 'M j, Y');
    }

    /**
     * Compute age (in days) of product with the use of its birthdate
     *
     * @param  String   $birthdate
     * @return Integer
     */
    private function computeAge($birthdate)
    {
        $rawSeconds = time() - strtotime($birthdate);
        $age = ((($rawSeconds/60)/60))/24;
        return floor($age);
    }
}
