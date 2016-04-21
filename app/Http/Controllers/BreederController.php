<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Video;
use App\Models\Breed;

use Auth;

class BreederController extends Controller
{
    protected $user;

	/**
     * Create new BreederController instance
     */
    public function __construct()
    {
        $this->middleware('role:breeder');
        $this->middleware('updateProfile:breeder',['except' => ['index','storeProfile']]);
        $this->user = Auth::user();
    }

	/**
	 * Show Home Page of breeder
	 *
	 * @return View
	 */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.breeder.createProfile');
        return view('user.breeder.home');
    }

    /**
     * Show Page for Breeder to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        return view('user.breeder.createProfile');
    }

    /**
     * Create and store Breeder profile data to database
     * Associate User to Breeder user type as well
     *
     * @param  Request $request
     * @return Redirect
     */
    public function storeProfile(BreederProfileRequest $request)
    {
        $user = $this->user;
        $breeder = Breeder::create($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'website',
            'produce',
            'contactPerson_name',
            'contactPerson_mobile']
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
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $breeder->users()->save($user);
        $breeder->farmAddresses()->saveMany($farmAddressArray);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('breeder.edit')
            ->with('message','Profile completed.');
    }

    /**
     * Show Page for Breeder to update profile
     *
     * @return View
     */
    public function editProfile(Request $request)
    {
        $breeder = $this->user->userable;
        $farmAddresses = $breeder->farmAddresses()->where('status_instance','active')->get();
        return view('user.breeder.editProfile', compact('breeder', 'farmAddresses'));
    }

    /**
     * Update Breeder's personal information
     * AJAX
     *
     * @return JSON / View
     */
    public function updatePersonal(BreederPersonalProfileRequest $request)
    {
        $breeder = Auth::user()->userable;
        $breeder->fill($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'website',
            'produce',
            'contactPerson_name',
            'contactPerson_mobile']
        ))->save();

        if($request->ajax()) return $breeder->toJson();
        else return redirect()->route('breeder.edit');
    }

    /**
     * Add Breeder's farm information instance
     * AJAX
     *
     * @return JSON / View
     */
    public function addFarm(BreederFarmProfileRequest $request)
    {
        $breeder = $this->user->userable;
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

        $breeder->farmAddresses()->saveMany($farmAddressArray);

        if($request->ajax()) return collect($farmAddressArray)->toJson();
        else return redirect()->route('breeder.edit');

    }

    /**
     * Update Breeder's farm information instance
     * AJAX
     *
     * @return JSON / View
     */
    public function updateFarm(BreederFarmProfileRequest $request)
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
        else return redirect()->route('breeder.edit');
    }

    /**
     * Delete Breeder's farm information instance
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
        else return redirect()->route('breeder.edit');
    }

    /**
     * Show the Breeder's products
     *
     * @return View
     */
    public function showProducts()
    {
        $breeder = $this->user->userable;
        $products = $breeder->products()->where('status_instance','active')->get();
        $farms = $breeder->farmAddresses;

        foreach ($products as $product) {
            $product->img_path = '/images/product/'.Image::find($product->primary_img_id)->name;
            $product->type = ucfirst($product->type);
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
        }

        return view('user.breeder.showProducts', compact('products', 'farms'));
    }

    /**
     * Store the Breeder's products
     * AJAX
     *
     * @param Request $request
     * @return JSON
     */
    public function storeProducts(Request $request)
    {
        $breeder = $this->user->userable;
        if($request->ajax()){
            $product = new Product;
            $productDetail= [];
            if($request->type == 'boar') $image = Image::firstOrCreate(['name' => 'boar_default.jpg']);
            else if($request->type == 'sow') $image = Image::firstOrCreate(['name' => 'sow_default.jpg']);
            else $image = Image::firstOrCreate(['name' => 'semen_default.jpg']);

            $product->farm_from_id = $request->farm_from_id;
            $product->primary_img_id = $image->id;
            $product->name = $request->name;
            $product->type = $request->type;
            $product->age = $request->age;
            $product->breed_id = $this->findOrCreateBreed($request->breed);
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->adg = $request->adg;
            $product->fcr = $request->fcr;
            $product->backfat_thickness = $request->backfat_thickness;
            $product->other_details = $request->other_details;
            // $breeder->products()->save($product);

            $productDetail['product_id'] = $product->id;
            $productDetail['name'] = $product->name;
            $productDetail['type'] = ucfirst($request->type);
            $productDetail['breed'] = $request->breed;

            return collect($productDetail)->toJson();
        }

    }

    /**
     * Upload media for a product
     *
     * @param Request $request
     * @return JSON
     */
    public function uploadMedia(Request $request)
    {
        return "OK";
        // Check if request contains media files
        if($request->hasFile('media')) {
            $files = $request->file('media.*');
            $fileDetails = [];

            foreach ($files as $file) {

                // Check if file has no problems in uploading
                if($file->isValid()){
                    $fileExtension = $file->getClientOriginalExtension();
                    $originalName = $file->getClientOriginalName();

                    // Get media (Image/Video) info according to extension
                    if($this->isImage($fileExtension)) $mediaInfo = $this->createMediaInfo($fileExtension, $request->productId, $request->type, $request->breed, $originalName);
                    else if($this->isVideo($fileExtension)) $mediaInfo = $this->createMediaInfo($fileExtension, $request->productId, $request->type, $request->breed, $originalName);

                    $file = $file->move(public_path() . $mediaInfo['directoryPath'], $mediaInfo['filename']);

                    // Check if file is successfully moved to desired path
                    if($file){
                        $product = Product::find($request->productId);

                        // Make Image/Video instance
                        $media = $mediaInfo['type'];
                        $media->name = $mediaInfo['filename'];

                        if($this->isImage($fileExtension)) $product->images()->save($media);
                        else if($this->isVideo($fileExtension)) $product->videos()->save($media);

                        array_push($fileDetails, ['id' => $media->id, 'name' => $mediaInfo['filename']]);
                    }
                    else return response()->json('Move file failed', 500);
                }
                else return response()->json('Upload failed', 500);
            }

            return response()->json(collect($fileDetails)->toJson(),200);
        }
        else return response()->json('No files detected', 500);
    }

    /**
     * Delete and Image of a Product
     * AJAX
     *
     * @param Request $request
     * @return JSON
     */
    public function deleteMedium(Request $request)
    {
        if($request->ajax()){
            $image = Image::find($request->imageId);
        }
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

    /**
     * Parse $other_details
     */
    private function transformOtherDetailsSyntax($otherDetails)
    {
        $details = explode(',',$otherDetails);
        $transformedSyntax = '';
        foreach ($details as $detail) {
            $transformedSyntax += $detail.'\\n';
        }
        return $transformedSyntax;
    }

    /**
     * Find breed_id through breed name ($breed)
     * or create another breed if not found
     *
     * @param String $Breed
     * @return Integer
     */
    private function findOrCreateBreed($breed)
    {
        $breedInstance = Breed::where('name','like',$breed)->get()->first();
        if($breedInstance) return $breedInstance->id;
        else{
            $newBreed = Breed::create(['name' => $breed]);
            return $newBreed->id;
        }
    }

    /**
     * Get appropriate media (Image/Video) info depending on extension
     *
     * @param String $extension
     * @return Associative Array $mediaInfo
     */
    private function createMediaInfo($extension, $productId, $type, $breed, $originalName)
    {
        $mediaInfo = [];

        if($this->isImage($extension)){
            $mediaInfo['directoryPath'] = '/images/product';
            $mediaInfo['filename'] = $productId . '_' . $type . '_' . $breed . str_random(6) . '.' . $extension;
            $mediaInfo['type'] = new Image;
        }

        else if($this->isVideo($extension)){
            $mediaInfo['directoryPath'] = '/videos/product';
            $mediaInfo['filename'] = $productId . '_' . $type . '_' . $breed . str_random(6) . '.' . $extension;
            $mediaInfo['type'] = new Video;
        }

        return $mediaInfo;

    }

    /**
     * Check if media is Image depending on extension
     *
     * @param String $extension
     * @return Boolean
     */
    private function isImage($extension)
    {
        return ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') ? true : false;
    }

    /**
     * Check if media is Video depending on extension
     *
     * @param String $extension
     * @return Boolean
     */
    private function isVideo($extension)
    {
        return ($extension == 'mp4' || $extension == 'mkv' || $extension == 'avi' || $extension == 'flv') ? true : false;
    }

}
