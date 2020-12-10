<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Breeder;
use App\Models\Breed;
use App\Models\Product;

use App\Repositories\ProductRepository;
use App\Repositories\CustomHelpers;

use Response;
use JWTAuth;

class ShopController extends Controller
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
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getProducts(Request $request, ProductRepository $repository)
    {
        $products = Product::with(
                'breed',
                'breeder.user',
                'primaryImage'
            )
            ->join('farm_addresses', function ($join) {
                $join
                    ->on(
                        'products.farm_from_id',
                        '=',
                        'farm_addresses.id'
                    )
                    ->where('accreditation_status', 'active');
            })
            ->select(
                'products.*',
                'farm_addresses.name as fa.name',
                'farm_addresses.id as fa.id'
            )
            ->whereIn('status', ['displayed', 'requested'])
            ->where(function ($query) {
                $query
                    ->where('quantity', '<>', 0)
                    ->orWhere([
                        ['is_unique', '=', 0],
                        ['quantity', '=', 0]
                    ]);
            });


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
            $products = $products->orderBy('id', 'DESC');
        }

        // Paginate and Transform Product
        $products = $products->paginate($request->limit);
        $formatted = $products->map(function($item) {

            $product = [];

            $breeder = $item->breeder;
            $breed = $item->breed;

            $product['id'] = $item->id;
            $product['name'] = $item->name;
            $product['type'] = $item->type;
            $product['quantity'] = $item->quantity;
            $product['isUnique'] = $item->is_unique === 1;
            $product['age'] = $item->birthdate === '0000-00-00'
                ? null
                : $this->computeAge($item->birthdate);
            $product['breed'] = $this->transformBreedSyntax($breed->name);
            $product['breederName'] = $breeder->user->name;
            $product['imageUrl'] = route('serveImage',
                [
                    'size' => 'small',
                    'filename' => $item->primaryImage->name
                ]
            );

            return $product;
        });

        return response()->json([
            'data' => [
                'hasNextPage' => $products->hasMorePages(),
                'products' => $formatted,
            ]
        ]);
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

}
