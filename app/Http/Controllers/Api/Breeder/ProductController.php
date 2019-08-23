<?php

namespace App\Http\Controllers\Api\Breeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use App\Http\Requests\ProductRequest;

use App\Models\Image;
use App\Models\User;
use App\Models\Breeder;
use App\Models\Breed;
use App\Models\Customer;
use App\Models\FarmAddress;
use App\Models\Product;

use App\Repositories\ProductRepository;
use App\Repositories\CustomHelpers;

use Auth;
use Response;
use Validator;
use JWTAuth;
use Mail;
use Storage;
use Config;

class ProductController extends Controller {
    use CustomHelpers {
        transformBreedSyntax as private;
        transformDateSyntax as private;
        transformOtherDetailsSyntax as private;
        computeAge as private;
    }

    /**
     * Image and Video constant variable paths
     */
    const IMG_PATH = '/images/';
    const VID_PATH = '/videos/';
    const BREEDER_IMG_PATH = '/images/breeder/';
    const PRODUCT_IMG_PATH = '/images/product/';
    const PRODUCT_VID_PATH = '/videos/product/';
    const PRODUCT_SIMG_PATH = '/images/product/resize/small/';
    const PRODUCT_MIMG_PATH = '/images/product/resize/medium/';
    const PRODUCT_LIMG_PATH = '/images/product/resize/large/';

    /** 
     * Paginate Variables 
    */

    const RESULTS_PER_PAGE = 15;

    protected $guard = 'api';

    public function __construct() 
    {
        $this->middleware('jwt:auth', ['except' => [
            'getProductDetails', 'getProductMedia'
        ]]);
        $this->middleware('jwt.role:breeder', ['except' => [
            'getProductDetails', 'getProductMedia'
        ]]);
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        }, ['except' => [
            'getProductDetails', 'getProductMedia'
        ]]);
    }
    /**
     * ---------------------------------------
     *  PRIVATE METHODS
     * ---------------------------------------
     */

    
     /**
     * Find breed_id through breed name ($breed)
     * or create another breed if not found
     *
     * @param  String   $breed
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

    private function getBreederProducts($breeder) 
    {
        return $breeder->products()->whereIn('status', [
            'hidden',
            'displayed',
            'requested'
        ])->where('quantity','<>', 0);
    }
    
    private function transformProduct($product) 
    {   
        $product->img_path = route('serveImage', [
            'size' => 'medium', 
            'filename' => Image::find($product->primary_img_id)->name
        ]);
        $product->type = ucfirst($product->type);
        $product->birthdate = $this->transformDateSyntax($product->birthdate);
        $product->age = $this->computeAge($product->birthdate);
        $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
        $product->other_details = $product->other_details;

        return $product;
    }

    private function getBreederProduct($breeder, $product_id)
    {
        $breeder_id = $breeder->id;
        return Product::where([
            ['breeder_id', '=', $breeder_id],
            ['id', '=', $product_id]
        ])->first();
    }
    
    /**
     * ---------------------------------------
     *  PUBLIC METHODS
     * ---------------------------------------
     */
    
    public function getProducts(Request $request) 
    {
        $breeder = $this->user->userable;
        $products = $this->getBreederProducts($breeder);
        
        // Check for Filtering and Sorting
        if($request->input('type')) {
            $products = $products->where('type', $request->input('type'));
        }

        if($request->input('status')) {
            $products = $products->where('status', $request->input('status'));
        }

        if($request->input('sort')) {
            $part = explode('-', $request->input('sort'));
            $products = $products->orderBy($part[0], $part[1]);
        }
        else {
            $products = $products->orderBy('id', 'desc');
        }

        // Paginate and Transform Product
        $products = $products
            ->paginate($request->limit)
            ->map(function ($item) {
                $p = $this->transformProduct($item);
                $product = [];

                $product['id'] = $p->id;
                $product['name'] = $p->name;
                $product['type'] = $p->type;
                $product['breed'] = $p->breed;
                $product['status'] = $p->status;
                // $product['age'] = $p->age;
                // $product['adg'] = $p->adg;
                // $product['fcr'] = $p->fcr;
                // $product['bft'] = $p->backfat_thickness;
                $product['img_path'] = $p->img_path;

                return $product;
            });

        return response()->json([
            'message' => 'Get Products successful',
            'data' => [
                'count' => $products->count(),
                'products' => $products
            ]
        ], 200);
    }

    public function toggleProductStatus(Request $request, $product_id)
    {   
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            if($product->status === 'hidden') {
                $product->status = 'displayed';
            }
            else if($product->status === 'displayed') {
                $product->status = 'hidden';
            }

            $product->save();

            return response()->json([
                'message' => 'Toggle Product Status successful!',
                'data' => [
                    'id' => $product->id,
                    'status' => $product->status
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function getProduct(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $p = $this->transformProduct($product);

            $product = [];

            $product['id'] = $p->id;
            $product['breeder_id'] = $p->breeder_id;
            $product['farm_from_id'] = $p->farm_from_id;
            $product['name'] = $p->name;
            $product['type'] = $p->type;
            $product['birthdate'] = $p->birthdate;
            $product['breed'] = $p->breed;
            $product['breed_id'] = $p->breed_id;
            $product['price'] = $p->price;
            $product['status'] = $p->status;
            $product['quantity'] = $p->quantity;
            $product['age'] = $p->age;
            $product['adg'] = $p->adg;
            $product['fcr'] = $p->fcr;
            $product['bft'] = $p->backfat_thickness;
            $product['other_details'] = $p->other_details;
            $product['img_path'] = $p->img_path;

            return response()->json([
                'message' => 'Get Product successful!',
                'data' => [
                    'product' => $product
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function updateProduct(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        $farms = $breeder->farmAddresses;

        $data = $request->only([
            'farm_from_id',
            'name',
            'type',
            'quantity',
            'is_unique',
            'breed',
            'birthdate',
            'price',
            'adg',
            'fcr',
            'bft',
            'other_details'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required',
            'type' => 'required',
            'farm_from_id' => 'required',
            'breed' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            if($product) {
                $farm = $farms->find($request->farm_from_id);
                if($farm) {
                    $product->farm_from_id = $farm->id;
                    $product->name = $data['name'];
                    $product->type = strtolower($data['type']);
                    $product->breed_id = $this->findOrCreateBreed(strtolower($data['breed']));
                    $product->birthdate = date_format(date_create($data['birthdate']), 'Y-n-j');
                    $product->price = $data['price'];
                    $product->adg = $data['adg'];
                    $product->quantity = $data['quantity'];
                    $product->is_unique = $data['is_unique'];
                    $product->fcr = $data['fcr'];
                    $product->backfat_thickness = $data['bft'];
                    $product->other_details = $data['other_details'];
                    $product->save();

                    return response()->json([
                        'message' => 'Update Product successful!'
                    ], 200);
                }
                else return response()->json([
                    'error' => 'Farm does not exist!'
                ], 404);
            }
            else return response()->json([
                'error' => 'Product does not exist!'
            ], 404);
        }
        
    }

    public function getProductDetails(Request $request, $product_id) 
    {
        $product = Product::find($product_id);

        if($product) {

            $primaryImg = Image::find($product->primary_img_id);
            $breeder = Breeder::find($product->breeder_id);
            $user = $breeder->users->first();

            $p = [];

            $p['id'] = $product->id;
            $p['breeder_id'] = $product->breeder_id;
            // $p['farm_from_id'] = $product->farm_from_id;
            // $p['primary_img_id'] = $product->primary_img_id;
            $p['name'] = $product->name;
            $p['type'] = ucfirst($product->type);
            $p['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $p['birth_date'] = $this->transformDateSyntax($product->birthdate);
            $p['quantity'] = $product->quantity;
            $p['adg'] = $product->adg;
            $p['fcr'] = $product->fcr;
            $p['bft'] = $product->backfat_thickness;
            $p['lsba'] = $product->lsba;
            $p['house_type'] = $product->house_type;
            $p['min_price'] = $product->min_price;
            $p['max_price'] = $product->max_price;
            $p['left_teats'] = $product->left_teats;
            $p['right_teats'] = $product->right_teats;
            $p['birth_weight'] = $product->birthweight;
            $p['age'] = $thi s->computeAge($product->birthdate);
            $p['other_details'] = strip_tags($product->other_details);
            $p['user_id'] = $user->id;
            $p['breeder'] = $user->name;
            $p['farm_name'] = FarmAddress::find($product->farm_from_id)->name;
            $p['farm_from_id'] = $product->farm_from_id;
            $p['img_path'] = route('serveImage', ['size' => 'default', 'filename' => $primaryImg->name]);

            return response()->json([
                'message' => 'Get Product Details successful!',
                'data' => [
                    'product' => $p
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function getProductMedia(Request $request, $product_id)
    {
        $product = Product::find($product_id);

        if($product) {

            $images = $product->images->map(function ($item) {
                $image = [];

                $image['id'] = $item->id;
                $image['link'] = route('serveImage', [
                    'size' => 'large', 
                    'filename' => $item->name
                ]);

                return $image;
            });

            $videos = $product->videos->map(function ($item) {
                $video = [];

                $video['id'] = $item->id;
                $video['link'] = route('serveImage', [
                    'size' => 'large', 
                    'filename' => $item->name
                ]);

                return $video;
            });

            return response()->json([
                'message' => 'Get Product Media successful!',
                'data' => [
                    'images' => $images,
                    // 'videos' => $videos,
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function addProduct(Request $request)
    {
        $breeder = $this->user->userable;
        $farms = $breeder->farmAddresses;

        $farm = $farms->find($request->farm_from_id);

        $data = $request->only([
            'farm_from_id',
            'name',
            'type',
            'quantity',
            'is_unique',
            'breed',
            'birthdate',
            'adg',
            'fcr',
            'bft',
            'lsba',
            'house_type',
            'birth_weight',
            'min_price',
            'max_price',
            'left_teats',
            'right_teats',
            'other_details'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required',
            'type' => 'required',
            'farm_from_id' => 'required',
            'breed' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            if ($farm) {
                $product = new Product;

                if($request->type == 'boar') $image = Image::firstOrCreate(['name' => 'boar_default.jpg']);
                else if($request->type == 'sow') $image = Image::firstOrCreate(['name' => 'sow_default.jpg']);
                else if($request->type == 'gilt') $image = Image::firstOrCreate(['name' => 'gilt_default.jpg']);
                else $image = Image::firstOrCreate(['name' => 'semen_default.jpg']);

                $product->farm_from_id = $request->farm_from_id;
                $product->primary_img_id = $image->id;
                $product->name = $request->name;
                $product->type = $request->type;
                $product->birthdate = date_format(date_create($request->birthdate), 'Y-n-j');
                $product->breed_id = $this->findOrCreateBreed(strtolower($request->breed));
                $product->min_price = $request->min_price;
                $product->max_price = $request->max_price;
                $product->left_teats = $request->left_teats;
                $product->right_teats = $request->right_teats;
                $product->birthweight = $request->birth_weight;
                $product->house_type = $request->house_type;
                $product->quantity = $request->quantity;
                $product->is_unique = $request->is_unique;
                $product->adg = $request->adg;
                $product->fcr = $request->fcr;
                $product->lsba = $request->lsba;
                $product->backfat_thickness = $request->bft;
                $product->other_details = $request->other_details;
                $breeder->products()->save($product);

                $p = Product::find($product->id);
                $p = $this->transformProduct($p);

                $product = [];

                $product['id'] = $p->id;
                $product['name'] = $p->name;
                $product['quantity'] = $p->quantity;
                $product['is_unique'] = $p->is_unique;
                $product['type'] = $p->type;
                $product['breed'] = $p->breed;
                $product['status'] = $p->status;
                $product['age'] = $p->age;
                $product['adg'] = $p->adg;
                $product['fcr'] = $p->fcr;
                $product['bft'] = $p->backfat_thickness;
                $product['img_path'] = $p->img_path;

                return response()->json([
                    'message' => 'Add Product successful!',
                    'data' => [
                        'product' => $product
                    ]
                ], 200);
            }
            else return response()->json([
                'error' => 'Farm does not exist!'
            ], 404);
        }
    }

    public function toggleProductStatuses(Request $request)
    {
        $breeder = $this->user->userable;
        $products = [];
        $ids = $request->ids;

        foreach (explode(',', $ids) as $id) {
            $product = $this->getBreederProduct($breeder, $id);
            
            if($product) {
                if($product->status === 'hidden') {
                    $product->status = 'displayed';
                }
                else if($product->status === 'displayed') {
                    $product->status = 'hidden';
                }
                $product->save();
                array_push($products, [
                    'id' => $id,
                    'status' => $product->status
                ]);
            }
        }

        return response()->json([
            'message' => 'Toggle Product Statuses successful!',
        ], 200);
    }
    
    public function deleteProducts(Request $request)
    {
        $breeder = $this->user->userable;
        $ids = $request->ids;

        foreach (explode(',', $ids) as $id) {
            $product = $this->getBreederProduct($breeder, $id);

            if($product) {
                foreach ($product->images as $image) {
                    $fullFilePath = self::PRODUCT_IMG_PATH.$image->name;
                    $sFullFilePath = self::PRODUCT_SIMG_PATH.$image->name;
                    $mFullFilePath = self::PRODUCT_MIMG_PATH.$image->name;
                    $lFullFilePath = self::PRODUCT_LIMG_PATH.$image->name;

                    if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);
                    if(Storage::disk('public')->exists($sFullFilePath)) Storage::disk('public')->delete($sFullFilePath);
                    if(Storage::disk('public')->exists($mFullFilePath)) Storage::disk('public')->delete($mFullFilePath);
                    if(Storage::disk('public')->exists($lFullFilePath)) Storage::disk('public')->delete($lFullFilePath);

                    $image->delete();
                }

                foreach ($product->videos as $video) {
                    $fullFilePath = self::PRODUCT_VID_PATH.$video->name;

                    if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);

                    $video->delete();
                }

                $breedId = $product->breed_id;
                $product->delete();

                $breedInstance = Product::where('breed_id', $breedId)->get()->first();
                if(!$breedInstance){
                    $breed = Breed::find($breedId);
                    $breed->delete();
                }
            }
        }

        return response()->json([
            'message' => 'Delete Products successful!'
        ], 200);
    }

    public function setPrimaryPicture(Request $request) 
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $product->primary_img_id = $request->img_id;
            $product->save();

            return response()->json([
                'message' => 'Set Primary Picture succesful!',
            ]);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
        
    }

    public function deleteMedium(Request $request)
    {
        if($request->mediaType == 'image'){
            $image = Image::find($request->mediaId);
            $fullFilePath = self::PRODUCT_IMG_PATH.$image->name;
            $sFullFilePath = self::PRODUCT_SIMG_PATH.$image->name;
            $mFullFilePath = self::PRODUCT_MIMG_PATH.$image->name;
            $lFullFilePath = self::PRODUCT_LIMG_PATH.$image->name;

            // Check if file exists in the storage
            if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);
            if(Storage::disk('public')->exists($sFullFilePath)) Storage::disk('public')->delete($sFullFilePath);
            if(Storage::disk('public')->exists($mFullFilePath)) Storage::disk('public')->delete($mFullFilePath);
            if(Storage::disk('public')->exists($lFullFilePath)) Storage::disk('public')->delete($lFullFilePath);

            $image->delete();
        }
        else if($request->mediaType = 'video'){
            $video = Video::find($request->mediaId);
            $fullFilePath = self::PRODUCT_VID_PATH.$video->name;

            // Check if file exists in the storage
            if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);

            $video->delete();
        }

        return response()->json([
            'message' => 'Delete Medium succesful!',
        ], 200);
    }
}
