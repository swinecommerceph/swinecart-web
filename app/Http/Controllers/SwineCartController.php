<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Http\Requests;
use App\Models\Customer;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Breed;
use App\Models\Image;
use App\Models\SwineCartItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\TransactionLog;
use App\Models\ProductReservation;

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
        $this->middleware(function($request, $next){
            $this->user = Auth::user();

            return $next($request);
        });
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
            $checkProduct = $swineCartItems->where('product_id',$request->productId)->where('reservation_id', 0)->get();

            // --------- WEBSOCKET SEND DATA -------------
            // $product = Product::find($request->productId);
            // $breeder = $product->breeder;
            // $topic = $breeder->users()->first()->name;
            // $data = $repo->forBreeder($breeder);
            // $data['topic'] = str_slug($topic);
            //
            // // This is our new stuff
    	    // $context = new \ZMQContext();
    	    // $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'Breeder Dashboard Pusher');
    	    // $socket->connect("tcp://127.0.0.1:5555");
            //
    	    // $socket->send(collect($data)->toJson());

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
                if(Product::find($request->productId)->type == 'semen') $item->quantity = 2;
                else $item->quantity = 1;

                $swineCartItems->save($item);

                $returnArray = ['success', Product::find($request->productId)->name, $customer->swineCartItems()->where('if_requested',0)->count()];
                return $returnArray;
            }
        }
    }

    /**
     * Rates breeder from Swine Cart
     * AJAX
     *
     * @param  Request $request
     */
    public function rateBreeder(Request $request){
        if($request->ajax()){
            $customer = $this->user->userable;
            $reviews = Breeder::find($request->breederId)->reviews();

            $review = new Review;
            $review->customer_id = $request->customerId;
            $review->comment = $request->comment;
            $review->rating_delivery = $request->delivery;
            $review->rating_transaction = $request->transaction;
            $review->rating_productQuality = $request->productQuality;

            $swineCartItems = $customer->swineCartItems();
            $reviewed = $swineCartItems->where('product_id',$request->productId)->first();
            $reviewed->if_rated = 1;
            $reviewed->save();
            $reviews->save($review);

            // Update Transaction Log
            // This must be put in an event for better performance
            $transactionLog = $reviewed->transactionLog()->first();
            $decodedStatusTransaction = json_decode($transactionLog->status_transactions, true);
            $decodedStatusTransaction['rated'] = date('j M Y (D) g:iA', time());
            $transactionLog->status_transactions = collect($decodedStatusTransaction)->toJson();
            $transactionLog->save();

            return "OK";
        }
    }

    /**
     * Requests item from Swine Cart
     * AJAX
     *
     * @param  Request $request
     * @return Array
     */
    public function requestSwineCartItem(Request $request)
    {
        if ($request->ajax()) {
            $customer = $this->user->userable;
            $swineCartItems = $customer->swineCartItems();

            // Update Swine Cart
            $requested = $swineCartItems->find($request->itemId);
            $requested->if_requested = 1;
            $requested->quantity = $request->requestQuantity;
            if($request->dateNeeded) $requested->date_needed = date_format(date_create($request->dateNeeded), 'Y-n-j');
            else $request->date_needed = '';
            $requested->special_request = $request->specialRequest;
            $requested->save();

            // Update Product
            $product = Product::find($request->productId);
            $product->status = "requested";
            $product->save();

            // Bind Swine Cart to Transaction Log
            // Update Transaction Log
            // This must be put in an event for better performance
            $productDetails = [
                "id" => $product->id,
                "name" => $product->name,
                "type" => $product->type,
                "breed" => $this->transformBreedSyntax(Breed::find($product->breed_id)->name),
                "breeder_name" => Breeder::find($product->breeder_id)->users()->first()->name,
                "farm_from" => FarmAddress::find($product->farm_from_id)->province,
                "img_path" => '/images/product/'.Image::find($product->primary_img_id)->name
            ];
            $statusTransactions = [
                "requested" => date('j M Y (D) g:iA', time()),
                "reserved" => '',
                "on_delivery" => '',
                "paid" => '',
                "sold" => '',
                "rated" => ''
            ];

            $transactionLog = new TransactionLog;
            $transactionLog->customer_id = $requested->customer_id;
            $transactionLog->product_details = collect($productDetails)->toJson();
            $transactionLog->status_transactions = collect($statusTransactions)->toJson();
            $requested->transactionLog()->save($transactionLog);

            return [$customer->swineCartItems()->where('if_requested',0)->count(), $statusTransactions['requested']];
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
     * [!]AJAX
     *
     * @param  Request $request
     * @return JSON/Array
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
            $swineCartItems = $customer->swineCartItems()->where('if_rated',0)->get();
            $products = [];
            $log = $customer->transactionLogs()->get();
            $history = [];

            foreach ($swineCartItems as $item) {
                $itemDetail = [];
                $product = Product::find($item->product_id);
                $reviews = Breeder::find($product->breeder_id)->reviews()->get();

                $itemDetail['request_status'] = $item->if_requested;
                $itemDetail['request_quantity'] = $item->quantity;
                $itemDetail['status'] = ($item->reservation_id) ? ProductReservation::find($item->reservation_id)->order_status : $product->status;
                $itemDetail['staus'] = $product->status;
                $itemDetail['item_id'] = $item->id;
                $itemDetail['customer_id'] = $customer->id;
                $itemDetail['breeder_id'] = $product->breeder_id;
                $itemDetail['breeder'] = Breeder::find($product->breeder_id)->users()->first()->name;
                $itemDetail['product_id'] = $item->product_id;
                $itemDetail['product_province'] = FarmAddress::find($product->farm_from_id)->province;
                $itemDetail['product_name'] = $product->name;
                $itemDetail['product_type'] = $product->type;
                $itemDetail['product_quantity'] = $product->quantity;
                $itemDetail['product_breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
                $itemDetail['product_birthdate'] = $this->transformDateSyntax($product->birthdate);
                $itemDetail['product_age'] = $this->computeAge($product->birthdate);
                $itemDetail['product_adg'] = $product->adg;
                $itemDetail['product_fcr'] = $product->fcr;
                $itemDetail['product_backfat_thickness'] = $product->backfat_thickness;
                $itemDetail['other_details'] = $product->other_details;
                $itemDetail['avg_delivery'] = $reviews->avg('rating_delivery');
                $itemDetail['avg_transaction'] = $reviews->avg('rating_transaction');
                $itemDetail['avg_productQuality'] = $reviews->avg('rating_productQuality');
                if($item->date_needed == '0000-00-00') $itemDetail['date_needed'] = '';
                else $itemDetail['date_needed'] = $this->transformDateSyntax($item->date_needed);
                $itemDetail['special_request'] = $item->special_request;
                $itemDetail['img_path'] = '/images/product/'.Image::find($product->primary_img_id)->name;
                if($item->transactionLog) $itemDetail['status_transactions'] = json_decode($item->transactionLog->status_transactions,true);
                else{
                    $itemDetail['status_transactions'] = [
                        "requested" => '',
                        "reserved" => '',
                        "on_delivery" => '',
                        "paid" => '',
                        "sold" => '',
                        "rated" => ''
                    ];
                }
                array_push($products,(object) $itemDetail);
            }

            $products = collect($products);
            $history = collect($history);
            $token = csrf_token();
            $customerId = $customer->id;
            return view('user.customer.swineCart', compact('products', 'history', 'token', 'customerId'));
        }
    }

    /**
     * Record activity to Logs
     * AJAX
     *
     * @param  Request $request
     */
    public function getTransactionHistory(Request $request){
        if($request->ajax()){
            $history = Customer::find($request->customerId)->transactionLogs;
            return collect($history)->toJson();
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

    /**
     * Transform birthdate original (YYYY-MM-DD) syntax to Month Day, Year
     * @param  String   $birthdate
     * @return String
     */
    private function transformDateSyntax($birthdate)
    {
        return date_format(date_create($birthdate), 'F j, Y');
    }

}
