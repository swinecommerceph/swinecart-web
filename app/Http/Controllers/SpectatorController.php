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
use Carbon\Carbon;

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

    /*
     * Get product using advanced search creteria
     *
     * @param none
     * @return array of min and maximum product information values
     *
     */
    public function getMinMax(){

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

        $productMinMax = [$minPrice, $maxPrice, $minQuantity, $maxQuantity, $minADG, $maxADG, $minFCR, $maxFCR, $minBackfatThickness,$maxBackfatThickness];
        return $productMinMax;
    }

    /*
     * Display the spectator view
     *
     * @param none
     * @return view
     *
     */
    public function index()
    {
        return view('user.spectator.home');
    }

    /*
     * Get all breeders and customer accounts
     *
     * @param none
     * @return collection of users
     *
     */
    public function viewUsers()
    {
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->whereIn('role_id', [2, 3])->paginate(10);
        return view(('user.spectator.users'), compact('users'));
    }

    /*
     * Get all product and its information
     *
     * @param none
     * @return collection of products
     *
     */
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

        $productMinMax = [$minPrice, $maxPrice, $minQuantity, $maxQuantity, $minADG, $maxADG, $minFCR, $maxFCR, $minBackfatThickness,$maxBackfatThickness];

        return view(('user.spectator.products'),compact('products', 'productMinMax'));
        //return($products);
    }

    // public function viewLogs()
    // {
    //     return view('user.spectator.logs');
    // }

    /*
     * Get the statistics view for the spectator
     *
     * @param none
     * @return view and count collection
     *
     */
    public function viewStatisticsDashboard()
    {
        $date = Carbon::now();
        $month = $date->month;
        $year = $date->year;

        $activeCustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',3)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();

        $deletedCustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',3)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('deleted_at', '=', $month)
                        ->whereYear('deleted_at', '=', $year)
                        ->count();

        $blockedCustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',3)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('blocked_at', '=', $month)
                        ->whereYear('blocked_at', '=', $year)
                        ->count();


        $activeBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',2)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();

        $deletedBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',2)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('deleted_at', '=', $month)
                        ->whereYear('deleted_at', '=', $year)
                        ->count();

        $blockedBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','!=',2)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('blocked_at', '=', $month)
                        ->whereYear('blocked_at', '=', $year)
                        ->count();


        $products = DB::table('products')
                        ->where('status', '=', 'displayed')
                        ->whereNull('deleted_at')
                        ->get();

        $boar = 0;
        $gilt = 0;
        $sow = 0;
        $semen = 0;
        foreach ($products as $product) {
            if(strcmp($product->type, 'boar')){
                $boar++;
            }
            if(strcmp($product->type, 'gilt')){
                $gilt++;
            }
            if(strcmp($product->type, 'sow')){
                $sow++;
            }
            if(strcmp($product->type, 'semen')){
                $semen++;
            }
        }

        $data = [$activeCustomers, $deletedCustomers, $blockedCustomers, $activeBreeders, $deletedBreeders, $blockedBreeders, count($products), $boar, $gilt, $sow, $semen];

        return view('user.spectator.statisticsDashboard', compact('data'));
        // return view(('user.spectator.statistics'), compact('charts'));
    }

    /*
     * Search product in the database
     *
     * @param string (name of product)
     * @return collection of products
     *
     */
    public function searchProduct(Request $request)
    {
        $productMinMax = $this->getMinMax();
        $products = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->where('products.name', 'LIKE', "%$request->search%")
                    ->paginate(9);
        return view('user.spectator.search', compact('products', 'productMinMax'));
    }

    /*
     * Get product using advanced search creteria
     *
     * @param search criterion
     * @return collection of products
     *
     */
    public function advancedSearchProduct(Request $request)
    {
        $productMinMax = $this->getMinMax();
        $type = [];
        if($request->boar == NULL && $request->sow == NULL && $request->gilt == NULL && $request->semen == NULL){
            $products = DB::table('products')
                        ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                        ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                        'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                        'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                        ->where('products.name', 'LIKE', "%$request->name%")
                        ->whereBetween('price', [$request->minPrice, $request->maxPrice])
                        ->whereBetween('quantity', [$request->minQuantity, $request->maxQuantity])
                        ->whereBetween('adg', [$request->minADG, $request->maxADG])
                        ->whereBetween('fcr', [$request->minFCR, $request->maxFCR])
                        ->whereBetween('backfat_thickness', [$request->minBackfatThickness, $request->maxBackfatThickness])
                        ->paginate(9);

        }else{
            $type = [$request->boar, $request->sow, $request->gilt, $request->semen];
            $type = array_filter($type);
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
        }


        return view('user.spectator.search', compact('products', 'productMinMax'));
    }

    /*
     * Display the view for the active customer statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewActiveCustomerStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNull('blocked_at')
                ->whereNull('deleted_at')
                ->select(DB::raw('YEAR(approved_at) year, MONTH(approved_at) month, MONTHNAME(approved_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('approved_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.activeCustomerStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the active customer statistics
     *
     * @param request year
     * @return view with array of counts and year string
     *
     */
    public function viewActiveCustomerStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNull('blocked_at')
                ->whereNull('deleted_at')
                ->select(DB::raw('YEAR(approved_at) year, MONTH(approved_at) month, MONTHNAME(approved_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('approved_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.activeCustomerStatistics', compact('monthlyCount', 'year'));
    }


    /*
     * Display the view for the blocked customer statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewBlockedCustomerStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('blocked_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.blockedCustomerStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the blocked customer statistics
     *
     * @param request year
     * @return view with array of counts and year string
     *
     */
    public function viewBlockedCustomerStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('blocked_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.blockedCustomerStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the deleted customer statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewDeletedCustomerStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('deleted_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.deletedCustomerStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the blocked customer statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewDeletedCustomerStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',3)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('deleted_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.deletedCustomerStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the active breeder statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewActiveBreederStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNull('blocked_at')
                ->whereNull('deleted_at')
                ->select(DB::raw('YEAR(approved_at) year, MONTH(approved_at) month, MONTHNAME(approved_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('approved_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.activeBreederStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the active breeder statistics
     *
     * @param request date
     * @return view with array of counts and year string
     *
     */
    public function viewActiveBreederStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNull('blocked_at')
                ->whereNull('deleted_at')
                ->select(DB::raw('YEAR(approved_at) year, MONTH(approved_at) month, MONTHNAME(approved_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('approved_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }

        return view('user.spectator.activeBreederStatistics', compact('monthlyCount', 'year'));
    }


    /*
     * Display the view for the blocked breeder statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewBlockedBreederStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('blocked_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.blockedBreederStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the blocked breeder statistics
     *
     * @param request year
     * @return view with array of counts and year string
     *
     */
    public function viewBlockedBreederStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('blocked_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.blockedBreederStatistics', compact('monthlyCount', 'year'));
    }


    /*
     * Display the view for the deleted breeder statistics
     *
     * @param none
     * @return view with array of counts and year string
     *
     */
    public function viewDeletedBreederStatistics()
    {
        $date = Carbon::now();
        $year = $date->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('deleted_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.deletedBreederStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Display the view for the deleted breeder statistics
     *
     * @param requst year
     * @return view with array of counts and year string
     *
     */
    public function viewDeletedBreederStatisticsYear(Request $request)
    {
        $year = $request->year;
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=',2)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('deleted_at', $year)
                ->get();

        $monthlyCount = array_fill(0, 12, 0);
        foreach($counts as $count){
            if($count->month == 1){
                $monthlyCount[0] = $count->user_count;
            }
            if($count->month == 2){
                $monthlyCount[1] = $count->user_count;
            }
            if($count->month == 3){
                $monthlyCount[2] = $count->user_count;
            }
            if($count->month == 4){
                $monthlyCount[3] = $count->user_count;
            }
            if($count->month == 5){
                $monthlyCount[4] = $count->user_count;
            }
            if($count->month == 6){
                $monthlyCount[5] = $count->user_count;
            }
            if($count->month == 7){
                $monthlyCount[6] = $count->user_count;
            }
            if($count->month == 8){
                $monthlyCount[7] = $count->user_count;
            }
            if($count->month == 9){
                $monthlyCount[8] = $count->user_count;
            }
            if($count->month == 10){
                $monthlyCount[9] = $count->user_count;
            }
            if($count->month == 11){
                $monthlyCount[10] = $count->user_count;
            }
            if($count->month == 12){
                $monthlyCount[11] = $count->user_count;
            }
        }
        return view('user.spectator.deletedBreederStatistics', compact('monthlyCount', 'year'));
    }

}
