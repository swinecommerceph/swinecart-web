<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Video;
use App\Models\Breed;
use App\Models\Admin;
use App\Models\User;

use DB;
use Auth;
use Input;


class SpectatorController extends Controller
{
    protected $user;

    public function __construct()
    {
      $this->middleware('role:spectator');
      $this->middleware(function($request, $next){
          $this->user = Auth::user();

          return $next($request);
      });
    }

    public function index(Request $request)
    {
        return view('user.spectator.home');
    }

    public function viewUsers()
    {
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->whereIn('role_id', [2, 3])->paginate(10);
        return view(('user.spectator.users'), compact('users'));
    }

    public function viewProducts()
    {
        $products = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->where('quantity', '>=', 0 )
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->paginate(9);
        $minPrice = INF;
        $maxPrice = 0;
        $minBackfatThickness = INF;
        $maxBackfatThickness = 0;
        $minADG = INF;
        $maxADG = 0;
        $minFCR = INF;
        $maxFCR = 0;
        $minQuantity = INF;
        $maxQuantity = 0;
        foreach ($products as $product) {
            $product->image_name = '/images/product/'.$product->image_name;
            $product->type = ucfirst($product->type);
            $product->status = ucfirst($product->status);

            if($minPrice > $product->price){
                $minPrice = $product->price;
            }

            if($maxPrice < $product->price){
                $maxPrice = $product->price;
            }

            if($minQuantity > $product->quantity){
                $minQuantity = $product->quantity;
            }

            if($maxQuantity < $product->quantity){
                $maxQuantity = $product->quantity;
            }

            if($minADG > $product->adg){
                $minADG = $product->adg;
            }

            if($maxADG < $product->adg){
                $maxADG = $product->adg;
            }

            if($minFCR > $product->fcr){
                $minFCR = $product->fcr;
            }

            if($maxFCR < $product->fcr){
                $maxFCR = $product->fcr;
            }

            if($minBackfatThickness > $product->backfat_thickness){
                $minBackfatThickness = $product->backfat_thickness;
            }

            if($maxBackfatThickness < $product->backfat_thickness){
                $maxBackfatThickness = $product->backfat_thickness;
            }
        }

        return view(('user.spectator.products'),compact('products', 'minPrice', 'maxPrice', 'minQuantity', 'maxQuantity', 'minADG', 'maxADG', 'minFCR', 'maxFCR', 'minBackfatThickness', 'maxBackfatThickness'));
        //return($products);
    }

    // public function viewLogs()
    // {
    //     return view('user.spectator.logs');
    // }

    public function viewStatistics()
    {
        $charts = [];
        $lineChart = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->element('lineChartTest')
        ->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July'])
        ->datasets([
            [
                "label" => "Sample Dataset1",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [65, 59, 80, 81, 56, 55, 40],
            ],
            [
                "label" => "Sample Dataset2",
                'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "rgba(38, 185, 154, 0.7)",
                "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                'data' => [12, 33, 44, 44, 55, 23, 40],
            ]
        ])
        ->options([]);
        $charts[] = $lineChart;
        $barChart = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->element('barChartTest')
         ->labels(['Label x', 'Label y'])
         ->datasets([
             [
                 "label" => "My First dataset",
                 'backgroundColor' => ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
                 'data' => [69, 59]
             ],
             [
                 "label" => "My First dataset",
                 'backgroundColor' => ['rgba(255, 99, 132, 0.3)', 'rgba(54, 162, 235, 0.3)'],
                 'data' => [65, 12]
             ]
         ])
         ->options([]);
         $charts[] = $barChart;

        //return view('user.spectator.statistics');
        return view(('user.spectator.statistics'), compact('charts'));
    }

    public function searchProduct(Request $request)
    {
        $products = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->where('products.name', 'LIKE', "%$request->search%")
                    ->paginate(9);
        return view('user.spectator.products', compact('products'));
    }

    public function advancedSearchProduct(Request $request)
    {

        $type = [];
        if($request->boar == NULL && $request->sow == NULL && $request->gilt == NULL && $request->semen == NULL){
            dd("type:null");
        }else{
            $type = [$request->boar, $request->sow, $request->gilt, $request->semen];
            $type = array_filter($type);
        }
        $products = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->where('products.name', 'LIKE', "%$request->name%")
                    ->whereIn('type', $type)
                    ->whereBetween('price', [$request->minPrice, $request->maxPrice])
                    ->whereBetween('quantity', [$request->minQuantity, $request->maxQuantity])
                    ->whereBetween('adg', [$request->minADG, $request->maxADG])
                    ->whereBetween('fcr', [$request->minFCR, $request->maxFCR])
                    ->whereBetween('backfat_thickness', [$request->minBackfatThickness, $request->maxBackfatThickness])
                    ->paginate(9);
        dd($products);
        // TODO: RETURN ALL MIN AND MAX VALUES AND DISPLAY RESULTS USING INPUT TYPE HIDDEN 
        // return view('user.spectator.products', compact('products'));
    }

}
