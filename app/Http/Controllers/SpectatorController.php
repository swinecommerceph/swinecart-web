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
     * Search for user with the name containing the input string
     *
     * @param String
     * @return collection of users
     *
     */
    public function searchUser(Request $request){
        $in_role = [];
        if(!is_null($request->breeder)){
            $in_role[] = 2;
        }
        if(!is_null($request->customer)){
            $in_role[] = 3;
        }
        if(is_null($request->breeder) && is_null($request->customer)){
            $in_role[] = 2;
            $in_role[] = 3;
        }
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->whereIn('role_id', $in_role)
                ->where('name', 'LIKE', "%$request->search%")
                ->paginate(10);

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

        return view(('user.spectator.products'),compact('products'));
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
                        ->where('role_user.role_id','=',3)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();

        $deletedCustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',3)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('deleted_at', '=', $month)
                        ->whereYear('deleted_at', '=', $year)
                        ->count();

        $blockedCustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',3)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('blocked_at', '=', $month)
                        ->whereYear('blocked_at', '=', $year)
                        ->count();


        $activeBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',2)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();

        $deletedBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',2)
                        ->where('users.email_verified','=', 1)
                        ->whereMonth('deleted_at', '=', $month)
                        ->whereYear('deleted_at', '=', $year)
                        ->count();

        $blockedBreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',2)
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

        $data = [$activeBreeders, $deletedBreeders, $blockedBreeders, $activeCustomers, $deletedCustomers, $blockedCustomers, count($products), $boar, $gilt, $sow, $semen];
        return view('user.spectator.statisticsDashboard', compact('data'));
        // return view(('user.spectator.statistics'), compact('charts'));
    }

    /*
     * Search product in the database
     *
     * @param request array
     * @return collection of products
     *
     */
    public function searchProduct(Request $request)
    {

        $type = array(
            $request->boar,
            $request->gilt,
            $request->sow,
            $request->semen
        );

        $type = array_filter($type);
        if(empty($type)){
            $type = ['boar', 'gilt', 'sow', 'semen'];
        }

        $products = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->where('products.name', 'LIKE', "%$request->search%")
                    ->whereIn('type', $type)
                    ->whereBetween('price', [$request->minPrice, $request->maxPrice])
                    ->whereBetween('quantity', [$request->minQuantity, $request->maxQuantity])
                    ->whereBetween('adg', [$request->minADG, $request->maxADG])
                    ->whereBetween('fcr', [$request->minFCR, $request->maxFCR])
                    ->whereBetween('backfat_thickness', [$request->minBackfatThickness, $request->maxBackfatThickness])
                    ->paginate(9);


        return view('user.spectator.products', compact('products', 'productMinMax'));
    }

    /*
     * Helper function to query the active user counts
     *
     * @param integer, Carbon->year
     * @return Collection count
     *
     */
    public function getSpectatorActiveUserStatistics($userType, $year){
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=', $userType)
                ->where('users.email_verified','=', 1)
                ->whereNull('blocked_at')
                ->whereNull('deleted_at')
                ->select(DB::raw('YEAR(approved_at) year, MONTH(approved_at) month, MONTHNAME(approved_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('approved_at', $year)
                ->get();
        return $counts;
    }

    /*
     * Helper function to query the blocked user counts
     *
     * @param integer, Carbon->year
     * @return Collection count
     *
     */
    public function getSpectatorBlockedUserStatistics($userType, $year){
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=', $userType)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('blocked_at', $year)
                ->get();

        return $counts;
    }

    /*
     * Helper function to query the deleted user counts
     *
     * @param integer, Carbon->year
     * @return Collection count
     *
     */
    public function getSpectatorDeletedUserStatistics($userType, $year){
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_user.role_id','=', $userType)
                ->where('users.email_verified','=', 1)
                ->whereNotNull('approved_at')
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*) user_count'))
                ->groupBy('year')
                ->groupBy('month')
                ->whereYear('deleted_at', $year)
                ->get();

        return $counts;
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
        $counts = $this->getSpectatorActiveUserStatistics(3, $year);
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
        $counts = $this->getSpectatorActiveUserStatistics(3, $year);
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
        $counts = $this->getSpectatorBlockedUserStatistics(3, $year);
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
        $counts = $this->getSpectatorBlockedUserStatistics(3, $year);
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
        $counts = $this->getSpectatorDeletedUserStatistics(3, $year);
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
        $counts =$this->getSpectatorDeletedUserStatistics(3, $year);
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
        $counts = $this->getSpectatorActiveUserStatistics(2, $year);
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
        $counts = $this->getSpectatorActiveUserStatistics(2, $year);
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
        $counts = $this->getSpectatorBlockedUserStatistics(2, $year);
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
        $counts = $this->getSpectatorBlockedUserStatistics(2, $year);
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
        $counts = $this->getSpectatorDeletedUserStatistics(2, $year);
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
        $counts = $this->getSpectatorDeletedUserStatistics(2, $year);
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
