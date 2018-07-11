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
        $this->middleware('jwt:auth');
        $this->middleware('jwt:role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
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
        $product->other_details = $this->transformOtherDetailsSyntax($product->other_details);
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

    public function getProducts(Request $request) 
    {
        $breeder = $this->user->userable;
        $products = $this->getBreederProducts($breeder);
        $products = $products->orderBy('id', 'desc')->paginate(self::RESULTS_PER_PAGE);
        
        foreach ($products as $product) {
            $product = $this->transformProduct($product);
        }

        return response()->json([
            'message' => 'Get Products successful',
            'data' => $products
        ], 200);
    }

    public function filterProducts(Request $request) 
    {
        $breeder = $this->user->userable;
        $products = $this->getBreederProducts($breeder);

        $request->type  = $request->type ?? 'all';
        $request->status  = $request->status ?? 'all';
        $request->sort  = $request->sort ?? 'none';

        if($request->type != 'all') {
            $products = $products->where('type', $request->type)->paginate(self::RESULTS_PER_PAGE);
        }
        if($request->status != 'all') {
            $products = $products->where('status', $request->status)->paginate(self::RESULTS_PER_PAGE);
        }
        if($request->sort != 'none') {
            $part = explode('-', $request->sort);
            // $part[0] is the field (birthdate, adg, fcr, backfat_thickness)
            // $part[1] is the order (asc, desc)
            $products = $products->orderBy($part[0], $part[1])->paginate(self::RESULTS_PER_PAGE);
        }

        if($request->type == 'all' && $request->status == 'all' && $request->sort == 'none') {
            $products = $products->orderBy('id', 'desc')->paginate(self::RESULTS_PER_PAGE);
        } 

        foreach ($products as $product) {
            $product = $this->transformProduct($product);
        }

        return response()->json([
            'message' => 'Get Products successful',
            'data' => $products
        ], 200);
    }

    public function displayProduct(Request $request, $product_id)
    {   
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $product->status = 'displayed';
            $product->save();

            return response()->json([
                'message' => 'Display Product successful!'
            ], 200);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 200);
    }

    public function getProductSummary(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $product = $this->transformProduct($product);
            return response()->json([
                'message' => 'Get Product Summary succesful!',
                'data' => $product
            ]);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 200);
    }

    public function updateProduct(ProductRequest $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
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

            return response()->json([
                'message' => 'Update Product succesful!',
                'data' => $product
            ]);
        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);
    }

    public function getProductDetail(Request $request, $product_id) 
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $product->img_path = route('serveImage', ['size' => 'large', 'filename' => Image::find($product->primary_img_id)->name]);
            $product->def_img_path = route('serveImage', ['size' => 'default', 'filename' => Image::find($product->primary_img_id)->name]);
            $product->breeder = Breeder::find($product->breeder_id)->users->first()->name;
            $product->type = ucfirst($product->type);
            $product->birthdate = $this->transformDateSyntax($product->birthdate);
            $product->age = $this->computeAge($product->birthdate);
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
            $product->other_details = $this->transformOtherDetailsSyntax($product->other_details);
            $product->imageCollection = $product->images()->where('id', '!=', $product->primary_img_id)->get();
            $product->videoCollection = $product->videos;

            $reviews = Breeder::find($product->breeder_id)->reviews;
        
            $breederRatings = [
                'deliveryRating' => ($reviews->avg('rating_delivery')) ? $reviews->avg('rating_delivery') : 0,
                'transactionRating' => ($reviews->avg('rating_transaction')) ? $reviews->avg('rating_transaction') : 0,
                'productQualityRating' => ($reviews->avg('rating_productQuality')) ? $reviews->avg('rating_productQuality') : 0
            ];

            return response()->json([
                'message' => 'Update Product succesful!',
                'data' => [
                    'product' => $product,
                    'breederRatings' => $breederRatings  
                ]
            ]);

        }
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 404);



    }

    public function storeProduct(ProductRequest $request) 
    {
        $breeder = $this->user->userable;

        $product = new Product;
        $productDetail= [];

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
        // $breeder->products()->save($product);

        $productDetail['product_id'] = $product->id;
        $productDetail['name'] = $product->name;
        $productDetail['type'] = ucfirst($request->type);
        $productDetail['breed'] = $request->breed;

        return response()->json([
            'message' => 'Store Product succesful!',
            // 'data' => [
            //     'product' => $productDetail,
            // ]
        ]);

    }

}
