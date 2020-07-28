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

class ShopController extends Controller
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

    public function getProducts(Request $request, ProductRepository $repository)
    {   
        $products = Product::whereIn('status', ['displayed', 'requested'])
            ->with('breed', 'breeder.user', 'primaryImage')
            ->where('quantity', '<>', 0);

        // Search
        if($request->input('q')) {
            $keyword = $request->input('q');

            $ids = Breed::where('name', 'LIKE', '%' . $keyword . '%')
                ->get()
                ->map(function ($breed) {
                    return $breed->id;
                });

            $products = $products->where('name', 'LIKE', '%' . $keyword . '%');
            $products = $products->orWhere('type', 'LIKE', '%' . $keyword . '%');
            $products = $products->orWhereIn('breed_id', $ids);
        }

        // Filter
        if($request->input('type')) {
            $types = explode(',', $request->input('type'));
            $product = $products->whereIn('type', $types);
        }

        if($request->input('breed')) {
            $breedIds = explode(',', $request->input('breed'));
            $products = $products->whereIn('breed_id', $breedIds);
        }

        if($request->input('breeder')) {
            $breeders = explode(',', $request->input('breeder'));
            $products = $products->whereIn('breeder_id', $breeders);
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
                    $breeder = $product->breeder;
                    $breed = $product->breed;

                    $p = [];

                    $p['id'] = $product->id;
                    $p['name'] = $product->name;
                    $p['type'] = $product->type;
                    $p['age'] = $product->birthdate === '0000-00-00'
                        ? null
                        : $this->computeAge($product->birthdate);
                    $p['breed'] = $this->transformBreedSyntax($breed->name);
                    $p['breederName'] = $breeder->user->name;
                    $p['farmLocation'] = $product->farmFrom->province;
                    $p['imageUrl'] = route('serveImage',
                        [
                            'size' => 'medium',
                            'filename' => $product->primaryImage->name
                        ]
                    );

                    $array->push($p);
                }

                return $array;
            }, collect([]));

        return response()->json([
            'data' => [
                'products' => $products,
            ]
        ], 200);
    }

    public function getFilterOptions(Request $request)
    {

        $breeds = Breed::where('name','not like', '%+%')
            ->where('name','not like', '')
            ->orderBy('name','asc')
            ->get(['id', 'name'])
            ->map(function ($item) {
                $breed = [];
                $breed['id'] = $item->id;
                $breed['name'] = ucwords($item->name);
                return $breed;
            });
        
        $breeders = Breeder::with('users')
            ->get(['id'])
            ->map(function ($item) {
                $breeder = [];
                $breeder['id'] = $item->id;
                $breeder['name'] = ucwords($item->users()->first()->name);
                return $breeder;
            });

        return response()->json([
            'data' => [
                'breeds' => $breeds,
                'breeders' => $breeders
            ]
        ], 200);
    }


    public function getBreeds(Request $request)
    {
        $breeds = Breed::where('name','not like', '%+%')
            ->where('name','not like', '')
            ->orderBy('name','asc')
            ->get(['id', 'name'])
            ->map(function ($item) {
                $breed = [];
                $breed['id'] = $item->id;
                $breed['name'] = ucwords($item->name);
                return $breed;
            });

        return response()->json([
            'data' => [
                'breeds' => $breeds
            ]
        ], 200);
    }

    public function getBreeders(Request $request)
    {
        $breeders = Breeder::with('users')
            ->get(['id'])
            ->map(function ($item) {
                $breeder = [];
                $breeder['id'] = $item->id;
                $breeder['name'] = ucwords($item->users()->first()->name);
                return $breeder;
            });

        return response()->json([
            'data' => [
                'breeders' => $breeders
            ]
        ], 200);
    }
}
