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

class ProductController extends Controller
{   

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
        $this->middleware('jwt:auth', ['except' => ['getProductDetails']]);
        $this->middleware('jwt.role:breeder', ['except' => ['getProductDetails']]);
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        }, ['except' => ['getProductDetails']]);
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
        $perpage = $request->perpage;
        
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
        $results = $products->paginate($perpage);
        $products = $results->items();
        $count = $results->count();
    
        $products = array_map(function ($item) {
            $p = $this->transformProduct($item);
            $product = [];

            $product['id'] = $p->id;
            $product['name'] = $p->name;
            $product['type'] = $p->type;
            $product['breed'] = $p->breed;
            $product['status'] = $p->status;
            $product['age'] = $p->age;
            $product['adg'] = $p->adg;
            $product['fcr'] = $p->fcr;
            $product['backfat_thickness'] = $p->backfat_thickness;
            $product['img_path'] = $p->img_path;

            return $product;
        }, $products);

        return response()->json([
            'message' => 'Get Products successful',
            'data' => [
                'count' => $count,
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
            $product = $this->transformProduct($product);
            return response()->json([
                'message' => 'Get Product successful!',
                'data' => $product
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function updateProduct(ProductRequest $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        $breeder = $this->user->userable;
        $farms = $breeder->farmAddresses;

        if($product) {
            $farm = $farms->find($request->farm_from_id);

            if($farm) {
                $product->farm_from_id = $request->farm_from_id;
                $product->name = $request->name;
                $product->type = $request->type;
                $product->birthdate = date_format(date_create($request->birthdate), 'Y-n-j');
                $product->breed_id = $this->findOrCreateBreed(strtolower($request->breed));
                $product->price = $request->price;
                $product->adg = $request->adg;
                $product->fcr = $request->fcr;
                $product->backfat_thickness = $request->backfat_thickness;
                $product->other_details = $request->other_details;
                $product->save();

                $product = $this->getBreederProduct($breeder, $product->id);
                $product = $this->transformProduct($product);

                return response()->json([
                    'message' => 'Update Product successful!',
                    'data' => [
                        'product' => $product
                    ]
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

    public function getProductDetails(Request $request, $product_id) 
    {
        $product = Product::find($product_id);

        if($product) {

            $primaryImg = Image::find($product->primary_img_id);
            $breeder = Breeder::find($product->breeder_id);
            $user = $breeder->users->first();

            $product->img_path = route('serveImage', ['size' => 'large', 'filename' => $primaryImg->name]);
            $product->def_img_path = route('serveImage', ['size' => 'default', 'filename' => $primaryImg->name]);
            $product->breeder = $user->name;
            $product->user_id = $user->id;
            $product->birthdate = $this->transformDateSyntax($product->birthdate);
            $product->age = $this->computeAge($product->birthdate);
            $product->type = ucfirst($product->type);
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
            $product->other_details = $product->other_details;
            $product->imageCollection = $product->images;
            $product->videoCollection = $product->videos;
            
            $reviews = $breeder->reviews;    
        
            $ratings = [
                'deliveryRating' => $reviews->avg('rating_delivery') ?? 0,
                'transactionRating' => $reviews->avg('rating_transaction') ?? 0,
                'productQualityRating' => $reviews->avg('rating_productQuality') ?? 0
            ];

            return response()->json([
                'message' => 'Get Product Details successful!',
                'data' => [
                    'product' => $product,
                    'ratings' => $ratings
                ]
            ], 200);

        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function addProduct(ProductRequest $request) 
    {
        $breeder = $this->user->userable;

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
        $product->price = $request->price;
        $product->quantity = ($request->type == 'semen') ? -1 : 1;
        $product->adg = $request->adg;
        $product->fcr = $request->fcr;
        $product->backfat_thickness = $request->backfat_thickness;
        $product->other_details = $request->other_details;
        $breeder->products()->save($product);

        $product = $this->getBreederProduct($breeder, $product->id);
        $product = $this->transformProduct($product);

        return response()->json([
            'message' => 'Add Product successful!',
            'data' => [
                'product' => $product,
            ]
        ], 200);
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
            'data' => [
                'products' => '$products'
            ]
        ], 200);
    }
    
    public function deleteProducts(Request $request)
    {   
        $breeder = $this->user->userable;
        $undeletedProducts = [];
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
            else {
                array_push($undeletedProducts, $id);
            }
        }

        return response()->json([
            'message' => 'Delete Products successful!',
            'data' => [
                'ids' => $ids,
                'undeletedIds' => $undeletedProducts,
            ]
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
