<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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

    private function getBreedIds($breedParameter)
    {
        $tempBreedIds = [];
        foreach (explode(',', $breedParameter) as $breedName) {
            if($breedName == 'crossbreed') {
                // Get all breed ids that contain '+' in their breed name
                $crossbreeds = Breed::where('name','like','%+%')->get();
                foreach ($crossbreeds as $crossbreed) {
                    array_push($tempBreedIds, $crossbreed->id);
                }
                continue;
            }
            else {
                $breedInstance = Breed::where('name', $breedName)->get()->first();
                array_push($tempBreedIds, $breedInstance && $breedInstance->id);
            }
            
        }

        return $tempBreedIds;
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

    public function getProducts(Request $request, ProductRepository $repository)
    {   
        $products = Product::whereIn('status', ['displayed', 'requested'])->where('quantity', '<>', 0);

        // Search
        if($request->input('q')) {
            // $products = $repository->search($request->q);
            // $scores = $products->scores;
        }

        // Filter
        if($request->input('type')) {
            $product = $products->whereIn('type', explode(' ', $request->input('type')));
        }

        if($request->input('breed')) {
            $breedIds = $this->getBreedIds($request->input('breed'));
            $products = $products->whereIn('breed_id', $breedIds);
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
            ->reduce(function($array, $product) {
                if($product->farmFrom->accreditation_status == 'active') {
                    $p = [];
                    $p['id'] = $product->id;
                    // $p['breeder_id'] = $product->breeder_id;
                    // $p['farm_from_id'] = $product->farm_from_id;
                    // $p['primary_img_id'] = $product->primary_img_id;
                    $p['img_path'] = route('serveImage', ['size' => 'medium', 'filename' => Image::find($product->primary_img_id)->name]);
                    $p['name'] = $product->name;
                    $p['age'] = $this->computeAge($product->birthdate);
                    $p['type'] = ucfirst($product->type);
                    $p['breed'] = $this->transformBreedSyntax(Breed::find($product->breed_id)->name);

                    $array->push($p);
                }

                return $array;
            }, collect([]));
    
        return response()->json([
            'message' => 'Get Products successful',
            'data' => [
                'count' => $products->count(),
                'products' => $products,
            ]
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
