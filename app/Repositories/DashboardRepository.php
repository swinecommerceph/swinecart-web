<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

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
        $reservations = $breeder->reservations()->with('product')->get();
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
            $itemDetail['expiration_date'] = '';
            array_push($items, (object)$itemDetail);
        }

        // Include "reserved" / "paid" / "on_delivery" products
        foreach ($reservations as $reservation) {
            $product = $reservation->product;

            // Check if the reservation has expired already
            if($reservation->expiration_date && Carbon::now() > $reservation->expiration_date ){
                // Update Swine Cart item
                $swineCartItem = SwineCartItem::where('reservation_id', $reservation->id)->first();
                $swineCartItem->reservation_id = 0;
                $swineCartItem->quantity = ($product->type == 'semen') ? 2 : 1;
                $swineCartItem->if_requested = 0;
                $swineCartItem->date_needed = null;
                $swineCartItem->special_request = "";
                $swineCartItem->save();

                // Update product
                $product->status = "displayed";
                $product->quantity = ($product->type == 'semen') ? -1 : 0;
                $product->save();

                // Delete reservation
                $reservation->delete();

                // Update Transaction Log
                $transactionLog = $reservation->transactionLog->first();
                if($transactionLog->expiration_dates == 'null'){

                }
                else{

                }

                // Notify Customer


                continue;
            }

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
            $itemDetail['userid'] = Customer::find($reservation->customer_id)->users()->first()->id;
            $itemDetail['date_needed'] = $this->transformDateSyntax($reservation->date_needed);
            $itemDetail['special_request'] = $reservation->special_request;
            $itemDetail['expiration_date'] = $reservation->expiration_date;
            array_push($items, (object)$itemDetail);
        }

        return collect($items)->toJson();
    }

    /**
     * Get the number statuses of the products of a Breeder
     * Include hidden, displayed, requested,
     * reserved, paid, on_delivery,
     * and sold quantity
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getProductNumberStatus(Breeder $breeder, $status)
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
     * Get sold products of Breeder on a specified time frequency
     *
     * @param  Request  $request
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getSoldProducts($request, Breeder $breeder)
    {

        $dateFrom = Carbon::createFromFormat('Y-m-d', $request->dateFrom);
        $dateTo = $request->dateTo ? Carbon::createFromFormat('Y-m-d', $request->dateTo) : '';
        $soldData = [
            'title' => '',
            'labels' => [],
            'dataSets' => [
                [], [], [], []
            ],
        ];

        // Fetch sold products data depending on the chosen frequency
        switch ($request->frequency) {
            case 'monthly':
                // Get boar, sow, gilt, semen count for the months specified
                $monthFromInt = $dateFrom->month;
                $monthToInt = $dateTo->month;

                // Make sure to get the correct month difference
                $diff = ($monthFromInt <= $monthToInt) ? $monthToInt - $monthFromInt : ($monthToInt + 12) - $monthFromInt;
                $currentDate = $dateFrom;

                $soldData['title'] = 'No. of Products Sold Monthly from ' . $dateFrom->format('F Y') . ' - ' . $dateTo->format('F Y');

                for ($i = 0; $i < $diff + 1; $i++) {

                    $boarQuery = $breeder->transactionLogs()->whereYear('sold', $currentDate->year)->whereMonth('sold', $currentDate->month)->where('product_details->type', 'boar');
                    $sowQuery = $breeder->transactionLogs()->whereYear('sold', $currentDate->year)->whereMonth('sold', $currentDate->month)->where('product_details->type', 'sow');
                    $giltQuery = $breeder->transactionLogs()->whereYear('sold', $currentDate->year)->whereMonth('sold', $currentDate->month)->where('product_details->type', 'gilt');
                    $semenQuery = $breeder->transactionLogs()->whereYear('sold', $currentDate->year)->whereMonth('sold', $currentDate->month)->where('product_details->type', 'semen');

                    array_push($soldData['labels'], $currentDate->format('M \'y'));
                    // dataSets refer to boar, sow, gilt, and semen respectively
                    array_push($soldData['dataSets'][0], $boarQuery->count());
                    array_push($soldData['dataSets'][1], $sowQuery->count());
                    array_push($soldData['dataSets'][2], $giltQuery->count());
                    array_push($soldData['dataSets'][3], $semenQuery->count());

                    $currentDate->addMonth();
                }

                break;

            case 'weekly':
                // Get boar, sow, gilt, semen count for the weeks specified

                $startDay = $dateFrom;
                $startDay->day = 1;
                $endDay = $startDay->copy()->addWeek();
                $endDayOfMonth = Carbon::create($startDay->year, $startDay->month, $startDay->daysInMonth);

                $soldData['title'] = 'No. of Products Sold Weekly on ' . $startDay->format('F Y');

                for ($i = 0; $i < ($startDay->daysInMonth/7) ; $i++) {

                    if($endDay->gte($endDayOfMonth)) $endDay = $endDayOfMonth;

                    $boarQuery = $breeder->transactionLogs()->whereDate('sold', '>=', $startDay->format('Y-m-d'))->whereDate('sold', '<', $endDay->format('Y-m-d'))->where('product_details->type', 'boar');
                    $sowQuery = $breeder->transactionLogs()->whereDate('sold', '>=', $startDay->format('Y-m-d'))->whereDate('sold', '<', $endDay->format('Y-m-d'))->where('product_details->type', 'sow');
                    $giltQuery = $breeder->transactionLogs()->whereDate('sold', '>=', $startDay->format('Y-m-d'))->whereDate('sold', '<', $endDay->format('Y-m-d'))->where('product_details->type', 'gilt');
                    $semenQuery = $breeder->transactionLogs()->whereDate('sold', '>=', $startDay->format('Y-m-d'))->whereDate('sold', '<', $endDay->format('Y-m-d'))->where('product_details->type', 'semen');

                    array_push($soldData['labels'], $startDay->format('M j'). ' - ' . $endDay->format('M j'));
                    // dataSets refer to boar, sow, gilt, and semen respectively
                    array_push($soldData['dataSets'][0], $boarQuery->count());
                    array_push($soldData['dataSets'][1], $sowQuery->count());
                    array_push($soldData['dataSets'][2], $giltQuery->count());
                    array_push($soldData['dataSets'][3], $semenQuery->count());

                    $startDay->addWeek();
                    $endDay->addWeek();
                }

                break;

            case 'daily':
                // Get boar, sow, gilt, semen count for the days specified
                $dayFromInt = $dateFrom->dayOfWeek;
                $dayToInt = $dateTo->dayOfWeek;

                // Make sure to get the correct month difference
                $diff = ($dayFromInt <= $dayToInt) ? $dayToInt - $dayFromInt : ($dayToInt + 7) - $dayFromInt;
                $currentDate = $dateFrom;

                $soldData['title'] = 'No. of Products Sold Daily from ' . $dateFrom->format('M j, Y') . ' - ' . $dateTo->format('M j, Y');

                for ($i = 0; $i < $diff + 1; $i++) {

                    $boarQuery = $breeder->transactionLogs()->whereDate('sold', $currentDate->format('Y-m-d'))->where('product_details->type', 'boar');
                    $sowQuery = $breeder->transactionLogs()->whereDate('sold', $currentDate->format('Y-m-d'))->where('product_details->type', 'sow');
                    $giltQuery = $breeder->transactionLogs()->whereDate('sold', $currentDate->format('Y-m-d'))->where('product_details->type', 'gilt');
                    $semenQuery = $breeder->transactionLogs()->whereDate('sold', $currentDate->format('Y-m-d'))->where('product_details->type', 'semen');

                    array_push($soldData['labels'], $currentDate->format('M j (D)'));
                    // dataSets refer to boar, sow, gilt, and semen respectively
                    array_push($soldData['dataSets'][0], $boarQuery->count());
                    array_push($soldData['dataSets'][1], $sowQuery->count());
                    array_push($soldData['dataSets'][2], $giltQuery->count());
                    array_push($soldData['dataSets'][3], $semenQuery->count());

                    $currentDate->addDay();
                }

                break;

            default: break;
        }

        return $soldData;
    }

    /**
     * Get the summary of reviews and ratings of the Breeder.
     * Include overall, delivery,
     * transaction, and product
     * quality rating
     *
     * @param  Breeder  $breeder
     * @return Array
     */
    public function getSummaryReviewsAndRatings(Breeder $breeder)
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

    /**
     * Get Customers who requested for a respective Product
     *
     * @param  Integer   $productId
     * @return Array
     */
    public function getProductRequests($productId)
    {
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
                    'userId' => $customer->users()->first()->id,
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
     * @param  Request  $request
     * @param  Product  $product
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
                    $reservation->expiration_date = Carbon::now()->addDays($request->days_after_expiration);
                    $product->reservations()->save($reservation);

                    // Update the Swine Cart item
                    $swineCartItem = SwineCartItem::find($request->swinecart_id);
                    $swineCartItem->reservation_id = $reservation->id;
                    $swineCartItem->save();

                    // Update Transaction Log
                    // This must be put in an event for better performance
                    $transactionLog = $reservation->transactionLog()->first();
                    $transactionLog->reserved = Carbon::now();
                    $transactionLog->save();

                    // Notify reserved customer
                    $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                    $reservedCustomerUser->notify(new ProductReserved(
                        [
                            'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been reserved to you',
                            'time' => $transactionLog->reserved,
                            'url' => route('cart.items')
                        ]
                    ));

                    // If product type is not semen remove other requests to this product
                    $productRequests = SwineCartItem::where('product_id', $product->id)->where('customer_id', '<>', $request->customer_id)->where('reservation_id',0);

                    if($product->type != 'semen'){
                        // Notify Customer users that the product has been reserved to another customer
                        foreach ($productRequests->get() as $productRequest) {
                            $customerUser = $productRequest->customer->users()->first();
                            $breederName = Product::find($productRequest->product_id)->breeder->users()->first()->name;
                            $customerUser->notify(new ProductReservedToOtherCustomer(
                                [
                                    'description' => 'Sorry, product ' . $product->name . ' was reserved by ' . $breederName . ' to another customer',
                                    'time' => $transactionLog->reserved,
                                    'url' => route('cart.items')
                                ]
                            ));
                        }

                        // Delete requests to this product after notifying Customer users
                        $productRequests->delete();
                    }
                    else{
                        if($productRequests->count() == 0){
                            $product->status = 'displayed';
                            $product->save();

                            return ['success', $product->name.' reserved to '.$customerName, $reservation->id, (string) Uuid::uuid4(), true, $reservation->expiration_date];
                        }
                    }

                    // [0] - success/fail operation flag
                    // [1] - toast message
                    // [2] - reservation_id
                    // [3] - generated UUID
                    // [4] - flag for removing the parent product display in the UI component
                    return ['success', $product->name.' reserved to '.$customerName, $reservation->id, (string) Uuid::uuid4(), false, $reservation->expiration_date];
                }
                else {
                    return ['fail', $product->name.' is already reserved to another customer'];
                }

            case 'on_delivery':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'on_delivery';
                $reservation->expiration_date = null;
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $transactionLog->on_delivery = Carbon::now();
                $transactionLog->save();

                // Notify customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' is on delivery',
                        'time' => $transactionLog->on_delivery,
                        'url' => route('cart.items')
                    ]
                ));

                return "OK";

            case 'paid':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'paid';
                $reservation->expiration_date = null;
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $transactionLog->paid = Carbon::now();
                $transactionLog->save();

                // Notify customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been marked as paid',
                        'time' => $transactionLog->paid,
                        'url' => route('cart.items')
                    ]
                ));

                return "OK";

            case 'sold':
                $reservation = ProductReservation::find($request->reservation_id);
                $reservation->order_status = 'sold';
                $reservation->save();

                // Update Transaction Log
                // This must be put in an event for better performance
                $transactionLog = $reservation->transactionLog()->first();
                $transactionLog->sold = Carbon::now();
                $transactionLog->save();

                // Notify reserved customer
                $reservedCustomerUser = Customer::find($reservation->customer_id)->users()->first();
                $reservedCustomerUser->notify(new ProductReservationUpdate(
                    [
                        'description' => 'Product ' . $product->name . ' by ' . $product->breeder->users()->first()->name . ' has been marked as sold',
                        'time' => $transactionLog->sold,
                        'url' => route('cart.items')
                    ]
                ));

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
