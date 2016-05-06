<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\CustomerProfileRequest;
use App\Http\Requests\CustomerPersonalProfileRequest;
use App\Http\Requests\CustomerFarmProfileRequest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Breed;
use App\Models\SwineCartItem;
use App\Models\TransactionLog;
use Auth;

class CustomerController extends Controller
{
    protected $user;

    /**
     * Create new CustomerController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
        $this->middleware('updateProfile:customer',['except' => ['index', 'storeProfile']]);
        $this->user = Auth::user();
    }

    /**
     * Show Home Page of customer
     *
     * @return View
     */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.customer.createProfile');
        return view('user.customer.home');
    }

    /**
     * Show Page for Customer to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        return view('user.customer.createProfile');
    }

    /**
     * Create and store Customer profile data to database
     * Associate User to Customer user type as well
     *
     * @param  Request $request
     * @return Redirect
     */
    public function storeProfile(CustomerProfileRequest $request)
    {
        $user = $this->user;
        $customer = Customer::create($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
        ));

        $farmAddressArray = [];

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/8; $i++) {
            $farmAddress = new FarmAddress;
            $farmAddress->name = $request->input('farmAddress.'.$i.'.name');
            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress$farmAddressArray.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $customer->users()->save($user);
        $customer->farmAddresses()->saveMany($farmAddressArray);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('customer.edit')
            ->with('message','Profile completed.');
    }

    /**
     * Show Page for Customer to update profile
     *
     * @return View
     */
    public function editProfile()
    {
        $customer = $this->user->userable;
        $farmAddresses = $customer->farmAddresses()->where('status_instance','active')->get();
        return view('user.customer.editProfile', compact('customer','farmAddresses'));
    }

    /**
     * Update Customer's personal information
     * AJAX
     *
     * @return JSON / View
     */
    public function updatePersonal(CustomerPersonalProfileRequest $request)
    {
        $customer = Auth::user()->userable;
        $customer->fill($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
        ))->save();

        if($request->ajax()) return $customer->toJson();
        else return redirect()->route('customer.edit');
    }

    /**
     * Add Customer's farm information instance
     * AJAX
     *
     * @return JSON / View
     */
    public function addFarm(CustomerFarmProfileRequest $request)
    {
        $customer = $this->user->userable;
        $farmAddressArray = [];

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/8; $i++) {
            $farmAddress = new FarmAddress;
            $farmAddress->name = $request->input('farmAddress.'.$i.'.name');
            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $customer->farmAddresses()->saveMany($farmAddressArray);

        if($request->ajax()) return collect($farmAddressArray)->toJson();
        else return redirect()->route('customer.edit');

    }

    /**
     * Update Customer's farm information instance
     * AJAX
     *
     * @return JSON / View
     */
    public function updateFarm(CustomerFarmProfileRequest $request)
    {
        $farmAddress = FarmAddress::find($request->id);

        $farmAddress->name = $request->input('farmAddress.1.name');
        $farmAddress->addressLine1 = $request->input('farmAddress.1.addressLine1');
        $farmAddress->addressLine2 = $request->input('farmAddress.1.addressLine2');
        $farmAddress->zipCode = $request->input('farmAddress.1.zipCode');
        $farmAddress->farmType = $request->input('farmAddress.1.farmType');
        $farmAddress->landline = $request->input('farmAddress.1.landline');
        $farmAddress->mobile = $request->input('farmAddress.1.mobile');
        $farmAddress->save();

        if($request->ajax()) return $farmAddress->toJson();
        else return redirect()->route('customer.edit');
    }

    /**
     * Delete Customer's farm information instance
     * AJAX
     *
     * @return String / View
     */
    public function deleteFarm(Request $request)
    {
        $farmAddress = FarmAddress::find($request->id);
        $farmAddress->status_instance = 'inactive';
        $farmAddress->save();
        if($request->ajax()) return "OK";
        else return redirect()->route('customer.edit');
    }

    /**
     * View Products of all Breeders
     *
     * @return View
     */
    public function viewProducts(Request $request)
    {
        // Check if empty search parameters
        if (!$request->type && !$request->breed){
            if($request->sort && $request->sort != 'none'){
                $part = explode('-',$request->sort);
                $products = Product::where('status','showcased')->orderBy($part[0], $part[1])->paginate(10);
            }
            else $products = Product::where('status','showcased')->paginate(10);
        }
        else{
            if($request->type) $products = Product::where('status','showcased')->whereIn('type', explode(' ',$request->type));
            if($request->breed) {
                $breedIds = $this->getBreedIds($request->breed);
                if(!$request->type) $products = Product::where('status','showcased')->whereIn('breed_id', $breedIds);
                else $products = $products->whereIn('breed_id', $breedIds);
            }
            if($request->sort) {
                if($request->sort != 'none'){
                    $part = explode('-',$request->sort);
                    $products = $products->orderBy($part[0], $part[1]);
                }
            }
            $products = $products->paginate(10);
        }

        $filters = $this->parseThenJoinFilters($request->type, $request->breed, $request->sort);
        $breedFilters = Breed::where('name','not like', '%+%')->where('name','not like', '')->orderBy('name','asc')->get();
        $urlFilters = $this->toUrlFilter($request->type, $request->breed, $request->sort);

        foreach ($products as $product) {
            $product->img_path = '/images/product/'.Image::find($product->primary_img_id)->name;
            $product->type = ucfirst($product->type);
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $product->breeder = Breeder::find($product->breeder_id)->users()->first()->name;
            $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
        }

        return view('user.customer.viewProducts', compact('products', 'filters', 'breedFilters', 'urlFilters'));
    }

    /**
     * View Details of a Product
     *
     * @return View
     */
    public function viewProductDetail($productId)
    {
        $product = Product::find($productId);
        $product->img_path = '/images/product/'.Image::find($product->primary_img_id)->name;
        $product->breeder = Breeder::find($product->breeder_id)->users->first()->name;
        $product->type = ucfirst($product->type);
        $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
        $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
        return view('user.customer.viewProductDetail', compact('product'));
    }

    /**
     * Add to Swine Cart the product picked by the user
     * AJAX
     *
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

    /**
     * Delete item from Swine Cart
     * AJAX
     *
     * @return Array
     */
    public function deleteFromSwineCart(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            $item = $customer->swineCartItems()->where('id',$request->itemId)->get()->first();
            $product_name = Product::find($item->product_id)->name;
            if($item) {
                $item->delete();
                return ["success", $product_name, $customer->swineCartItems()->where('if_requested',0)->count()];
            }
            else return ["not found", $product_id];

        }
    }

    /**
     * Get items in the Swine Cart
     * AJAX
     *
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
    }

    /**
     * Get number of items in the Swine Cart
     * AJAX
     *
     * @return int
     */
    public function getSwineCartQuantity(Request $request)
    {
        if($request->ajax()){
            $customer = $this->user->userable;
            return $customer->swineCartItems()->where('if_requested',0)->count();
        }
    }

    /**
     * Parse the Filters according to Type, Breed, and Sort By
     *
     * @param   $typeParameter String
     * @param   $breedParameter String
     * @param   $sortParameter String
     * @return  Assocative Array
     */
    private function parseThenJoinFilters($typeParameter, $breedParameter, $sortParameter)
    {
        $tempFilters = [];

        if($typeParameter){
            // Parse if there is more than one type filter value
            $types = explode(' ',$typeParameter);
            foreach ($types as $type) {
                $tempFilters[$type] = 'checked';
            }
        }

        if($breedParameter){
            // Parse if there is more than one breed filter value
            $breeds = explode(' ',$breedParameter);
            foreach ($breeds as $breed) {
                $tempFilters[$breed] = 'checked';
            }
        }

        $tempFilters[$sortParameter] = 'selected';

        return $tempFilters;
    }

    /**
     * Parse the Filters according to Type, Breed, and Sort By
     *
     * @param   $typeParameter String
     * @param   $breedParameter String
     * @param   $sortParameter String
     * @return  Assocative Array
     */
    private function toUrlFilter($typeParameter, $breedParameter, $sortParameter)
    {
        $tempUrlFilters = [];

        if($typeParameter)  $tempUrlFilters['type'] = $typeParameter;
        if($breedParameter) $tempUrlFilters['breed'] = $breedParameter;
        if($sortParameter) $tempUrlFilters['sort'] = $sortParameter;

        return $tempUrlFilters;
    }

    /**
     * Get breed ids of products based from breed filter value
     *
     * @param   $breedParameter String
     * @return  Array
     */
    private function getBreedIds($breedParameter)
    {
        $tempBreedIds = [];
        foreach (explode(' ', $breedParameter) as $breedName) {
            if($breedName == 'crossbreed') {
                // Get all breed ids that contain '+' in their breed name
                $crossbreeds = Breed::where('name','like','%+%')->get();
                foreach ($crossbreeds as $crossbreed) {
                    array_push($tempBreedIds, $crossbreed->id);
                }
                continue;
            }
            else $breedInstance = Breed::where('name',$breedName)->get()->first()->id;
            array_push($tempBreedIds, $breedInstance);
        }

        // dd($tempBreedIds);
        return $tempBreedIds;
    }

    /**
     * Parse $breed if it contains '+' (ex. landrace+duroc)
     * to "Landrace x Duroc"
     *
     * @param  String $breed
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
