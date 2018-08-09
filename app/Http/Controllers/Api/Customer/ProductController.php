<?php

namespace App\Http\Controllers\Api\Customer;

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

    const IMG_PATH = '/images/';

    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }


    public function getProductDetail(Request $request, $product_id)
    {
        $product = Product::find($product_id);

        if($product) {
            $product->img_path = route('serveImage', ['size' => 'large', 'filename' => Image::find($product->primary_img_id)->name]);
            $product->def_img_path = route('serveImage', ['size' => 'default', 'filename' => Image::find($product->primary_img_id)->name]);
            $product->breeder = Breeder::find($product->breeder_id)->users->first()->name;
            $product->birthdate = $this->transformDateSyntax($product->birthdate);
            $product->age = $this->computeAge($product->birthdate);
            $product->type = ucfirst($product->type);
            $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
            $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
            $product->other_details = $this->transformOtherDetailsSyntax($product->other_details);
            $product->imageCollection = $product->images()->where('id', '!=', $product->primary_img_id)->get();
            $product->videoCollection = $product->videos;
            $product->userid = Breeder::find($product->breeder_id)->users->first()->id;

            $reviews = Breeder::find($product->breeder_id)->reviews;
        
            $breederRatings = [
                'deliveryRating' => $reviews->avg('rating_delivery') ?? 0,
                'transactionRating' => $reviews->avg('rating_transaction') ?? 0,
                'productQualityRating' => $reviews->avg('rating_productQuality') ?? 0
            ];

            return response()->json([
                'message' => 'Get Product Detail succesful!',
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

    public function getBreederProfile(Request $request, $breeder_id)
    {
        $breeder = Breeder::find($breeder_id);
        $breeder->farms = $breeder->farmAddresses()->get();
        $breeder->logoImage = ($breeder->logo_img_id) ? self::BREEDER_IMG_PATH.Image::find($breeder->logo_img_id)->name : self::IMG_PATH.'default_logo.png' ;

        return response()->json([
            'message' => 'Get Breeder Profile successful',
            'data' => $breeder
        ], 200);
    }

    public function getProducts(Request $request)
    {
        $products = Product::whereIn('status', ['displayed', 'requested'])
            ->where('quantity', '!=', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $products = $products->reduce(function($array, $product) {

            if($product->farmFrom->accreditation_status == 'active') {
                $product->img_path = route('serveImage', ['size' => 'medium', 'filename' => Image::find($product->primary_img_id)->name]);
                $product->type = ucfirst($product->type);
                $product->birthdate = $this->transformDateSyntax($product->birthdate);
                $product->age = $this->computeAge($product->birthdate);
                $product->breed = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);
                $product->breeder = Breeder::find($product->breeder_id)->users()->first()->name;
                $product->farm_province = FarmAddress::find($product->farm_from_id)->province;
                $product->score = 0;

                array_push($array, $product);
            }

            return $array;
        }, []);

        return response()->json([
            'message' => 'Get Products successful',
            'data' => $products
        ], 200);
    }

    public function getBreeds(Request $request)
    {
        $breeds = Breed::where('name','not like', '%+%')
            ->where('name','not like', '')
            ->orderBy('name','asc')
            ->get();

        return response()->json([
            'message' => 'Get Breeds successful',
            'data' => $breeds
        ], 200);
    }
}
