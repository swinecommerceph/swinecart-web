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

    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:breeder');
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


}
