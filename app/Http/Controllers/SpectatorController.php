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
use App\Models\TransactionLog;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SwineCartProductNotification;
use App\Http\Requests\ChangePasswordRequest;

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
        $date = Carbon::now();
        $month = $date->month;
        $year = $date->year;

        if(\App::isDownForMaintenance()) return view('errors.503_home');

        $totalusers = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                   ->join('roles', 'role_user.role_id','=','roles.id')
                                   ->whereIn('role_id', [2, 3])
                                   ->whereNull('blocked_at')
                                   ->whereNull('deleted_at')
                                   ->where('email_verified','=','1')
                                   ->count();

        $totalbreeders = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                            ->join('roles', 'role_user.role_id','=','roles.id')
                                            ->where('role_id', '=', 2)
                                            ->whereNull('blocked_at')
                                            ->whereNull('deleted_at')
                                            ->where('email_verified','=','1')
                                            ->count();

        $totalcustomers = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                            ->join('roles', 'role_user.role_id','=','roles.id')
                                            ->where('role_id', '=', 3)
                                            ->whereNull('blocked_at')
                                            ->whereNull('deleted_at')
                                            ->where('email_verified','=','1')
                                            ->count();

        $products = DB::table('products')
                        ->where('status', '=', 'displayed')
                        ->whereNull('deleted_at')
                        ->select('type',DB::raw('COUNT(*) as count'))
                        ->groupBy('type')
                        ->get();
        $totalproduct = 0;
        $boar = 0;
        $gilt = 0;
        $sow = 0;
        $semen = 0;
        foreach ($products as $type) {
            $totalproduct = $totalproduct + $type->count;
            if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'sow'){
                $sow = $type->count;
            }
            else if($type->type == 'gilt'){
                $gilt = $type->count;
            }
            else if($type->type == 'semen'){
                $semen = $type->count;
            }
        }

        $newcustomers = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',3)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();


        $newbreeders = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->where('role_user.role_id','=',2)
                        ->where('users.email_verified','=', 1)
                        ->where('users.deleted_at','=', NULL)
                        ->where('users.blocked_at','=', NULL)
                        ->whereMonth('approved_at', '=', $month)
                        ->whereYear('approved_at', '=', $year)
                        ->count();


        $date = Carbon::now();
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $transactions =  DB::table('transaction_logs')
                        ->where('status', '=', 'sold')
                        ->whereBetween('created_at',[$start, $end])
                        ->count();

        return view(('user.spectator.home'), compact('totalusers', 'totalbreeders', 'totalcustomers', 'totalproduct', 'boar', 'gilt', 'sow', 'semen', 'newcustomers', 'newbreeders','transactions'));
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

    public function fetchUserInformation(Request $request){
        if($request->userRole == 2){
            $details = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_id', '=', 2)
                    ->where('users.id', '=', $request->userId)
                    ->join('breeder_user', 'breeder_user.id', '=', 'users.userable_id')
                    ->select('users.id as user_id', 'users.name as user_name', 'roles.title as role',
                            'breeder_user.officeAddress_addressLine1 as addressLine1', 'breeder_user.officeAddress_addressLine2 as addressLine2',
                            'breeder_user.officeAddress_province as province', 'breeder_user.officeAddress_zipCode as zipcode', 'breeder_user.office_landline',
                            'breeder_user.website', 'breeder_user.produce','breeder_user.status_instance'
                            )
                    ->get();
        }else{
            $details = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->whereIn('role_id', [3])
                    ->where('users.id', '=', $request->userId)
                    ->join('customer_user', 'customer_user.id', '=', 'users.userable_id')
                    ->select('users.id as user_id', 'users.name as user_name', 'roles.title as role',
                            'customer_user.address_addressLine1 as addressLine1', 'customer_user.address_addressLine2 as addressLine2',
                            'customer_user.address_province as province', 'customer_user.address_zipCode as zipcode',
                            'customer_user.status_instance'
                            )
                    ->get();

        }


        $details->first()->role = ucfirst($details->first()->role);
        $details->first()->status_instance = ucfirst($details->first()->status_instance);
        return $details;
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

    /*
     * Get product breakdown data
     *
     * @param none
     * @return product counts
     *
     */
    public function fetchProductDetails(Request $request){
        $product = DB::table('products')
                    ->join('images', 'products.primary_img_id', '=', 'images.imageable_id')
                    ->select('products.id', 'images.name as image_name', 'products.name', 'products.breeder_id',
                    'products.farm_from_id', 'products.type', 'products.birthdate', 'products.price', 'products.adg',
                    'products.fcr', 'products.backfat_thickness', 'products.other_details', 'products.status', 'products.quantity')
                    ->where('products.id','=',$request->id)
                    ->get();
        $product->first()->image_name = '/images/product/'.$product->first()->image_name;
        $product->first()->type = ucfirst($product->first()->type);
        $product->first()->status = ucfirst($product->first()->status);
        return $product;
    }


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
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
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
                        ->select('type',DB::raw('COUNT(*) as count'))
                        ->groupBy('type')
                        ->get();
        $total = 0;
        $boar = 0;
        $gilt = 0;
        $sow = 0;
        $semen = 0;
        foreach ($products as $type) {
            $total = $total + $type->count;
            if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'sow'){
                $sow = $type->count;
            }
            else if($type->type == 'gilt'){
                $gilt = $type->count;
            }
            else if($type->type == 'semen'){
                $semen = $type->count;
            }
        }

        $sold =  DB::table('transaction_logs')
                        ->join('product_reservations', 'product_reservations.product_id', '=','transaction_logs.product_id')
                        ->where([
                                ['transaction_logs.status', '=','sold'],
                                ['product_reservations.order_status', '=','sold']
                            ])
                        ->whereBetween('created_at', [$start, $end])
                        ->groupBy('product_reservations.product_id')
                        ->get();
        $sold = count($sold);
        $reserved =  DB::table('transaction_logs')
                        ->join('product_reservations', 'product_reservations.product_id', '=','transaction_logs.product_id')
                        ->where([
                                ['transaction_logs.status', '=','reserved'],
                                ['product_reservations.order_status', '=','reserved']
                            ])
                        ->whereBetween('created_at', [$start, $end])
                        ->groupBy('product_reservations.product_id')
                        ->get();

        $reserved = count($reserved);
        $paid =  DB::table('transaction_logs')
                        ->join('product_reservations', 'product_reservations.product_id', '=','transaction_logs.product_id')
                        ->where([
                                ['transaction_logs.status', '=','paid'],
                                ['product_reservations.order_status', '=','paid']
                            ])
                        ->whereBetween('created_at', [$start, $end])
                        ->groupBy('product_reservations.product_id')
                        ->get();

        $paid = count($paid);
        $on_delivery =  DB::table('transaction_logs')
                        ->join('product_reservations', 'product_reservations.product_id', '=','transaction_logs.product_id')
                        ->where([
                                ['transaction_logs.status', '=','on_delivery'],
                                ['product_reservations.order_status', '=','on_delivery']
                            ])
                        ->whereBetween('created_at', [$start, $end])
                        ->groupBy('product_reservations.product_id')
                        ->get();

        $on_delivery = count($on_delivery);
        $requested = DB::table('transaction_logs')
                        ->select('product_id', DB::raw('MAX(created_at) as date, MAX(status) as status'))
                        ->orderBy('date')->groupBy('product_id')
                        ->where('status','=','requested')
                        ->whereBetween('created_at', [$start, $end])
                        ->get();
        $requested = count($requested);

        $data = [$activeBreeders, $deletedBreeders, $blockedBreeders, $activeCustomers, $deletedCustomers, $blockedCustomers, $total, $boar, $gilt, $sow, $semen];
        return view('user.spectator.statisticsDashboard', compact('data', 'sold', 'reserved', 'paid', 'on_delivery', 'requested'));
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
                    ->when(!(
                        // is_null($type) &&
                        is_null($request->minPrice) &&
                        is_null($request->maxPrice) &&
                        is_null($request->minQuantity) &&
                        is_null($request->maxQuantity) &&
                        is_null($request->minADG) &&
                        is_null($request->maxADG) &&
                        is_null($request->minFCR) &&
                        is_null($request->maxFCR) &&
                        is_null($request->minBackfatThickness) &&
                        is_null($request->maxBackfatThickness)
                    ), function($query) use ($request, $type){
                        return $query->whereIn('type', $type)
                        ->whereBetween('price', [$request->minPrice, $request->maxPrice])
                        ->whereBetween('quantity', [$request->minQuantity, $request->maxQuantity])
                        ->whereBetween('adg', [$request->minADG, $request->maxADG])
                        ->whereBetween('fcr', [$request->minFCR, $request->maxFCR])
                        ->whereBetween('backfat_thickness', [$request->minBackfatThickness, $request->maxBackfatThickness]);
                    })
                    ->paginate(9);

        return view('user.spectator.products', compact('products', 'productMinMax'));
    }

    /*
     * Helper function to get the monthly count in the count collection
     *
     * @param collection count
     * @return array count
     *
     */
    public function getMonthlyCount($counts){
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
        return $monthlyCount;
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
                // ->where('users.email_verified','=', 1)
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
                // ->where('users.email_verified','=', 1)
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
                // ->where('users.email_verified','=', 1)
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
        $monthlyCount = $this->getMonthlyCount($counts);

        return view('user.spectator.activeCustomerStatistics', compact('monthlyCount', 'year'));
    }


    /*
     * Display the view for the active customer statistics
     *
     * @param integer year
     * @return view with array of counts and year string
     *
     */
     public function viewActiveCustomerStatisticsYear(Request $request)
     {
        $year = $request->year;

        $counts = $this->getSpectatorActiveUserStatistics(3, $year);
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

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
        $monthlyCount = $this->getMonthlyCount($counts);

        return view('user.spectator.deletedBreederStatistics', compact('monthlyCount', 'year'));
    }

    /*
     * Get product breakdown data
     *
     * @param none
     * @return product counts
     *
     */
    public function viewProductBreakdown(){
        $products = DB::table('products')
                        ->where('status', '=', 'displayed')
                        ->whereNull('deleted_at')
                        ->select('type',DB::raw('COUNT(*) as count'))
                        ->groupBy('type')
                        ->get();
        $total = 0;
        $boar = 0;
        $gilt = 0;
        $sow = 0;
        $semen = 0;
        foreach ($products as $type) {
            $total = $total + $type->count;
            if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'boar'){
                $boar = $type->count;
            }
            else if($type->type == 'sow'){
                $sow = $type->count;
            }
            else if($type->type == 'gilt'){
                $gilt = $type->count;
            }
            else if($type->type == 'semen'){
                $semen = $type->count;
            }
        }
        return view('user.spectator.productBreakdown', compact('boar', 'gilt', 'sow', 'semen', 'total'));
    }

    /*
     * Get current user's information
     *
     * @param none
     * @return array
     *
     */
    public function getSpectatorInformation(){
        $user_data = [$this->user->name, $this->user->email];
        return $user_data;
    }

    /*
     * Display average statistics for monthly breeders created
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersCreated(){
        $select = ['selected','',''];
        $formroute = 'spectator.averageBreederStatisticsCreatedYear';
        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                // ->whereNull('deleted_at')
                // ->whereNull('blocked_at')
                ->whereBetween('created_at', $temp)
                ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }


        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly breeders created
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersCreatedYear(Request $request){
        $select = ['selected','',''];
        $formroute = 'spectator.averageBreederStatisticsCreatedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                // ->whereNull('deleted_at')
                // ->whereNull('blocked_at')
                ->whereBetween('created_at', $temp)
                ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear, $temp[1]->year];
        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly breeders blocked
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersBlocked(){
        $select = ['','selected',''];
        $formroute = 'spectator.averageBreederStatisticsBlockedYear';

        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                ->whereNotNull('approved_at')
                // ->whereNull('deleted_at')
                ->whereBetween('blocked_at', $temp)
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }


        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly breeders blocked
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersBlockedYear(Request $request){
        $select = ['','selected',''];
        $formroute = 'spectator.averageBreederStatisticsBlockedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                ->whereNotNull('approved_at')
                // ->whereNull('deleted_at')
                ->whereBetween('blocked_at', $temp)
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear, $temp[1]->year];
        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly breeders deleted
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersDeleted(){
        $select = ['','','selected'];
        $formroute = 'spectator.averageBreederStatisticsDeletedYear';
        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                ->whereNotNull('approved_at')
                ->whereNotNull('deleted_at')
                ->whereBetween('deleted_at', $temp)
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly breeders deleted
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageBreedersDeletedYear(Request $request){
        $select = ['','','selected'];
        $formroute = 'spectator.averageBreederStatisticsDeletedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',2)
                ->whereNotNull('approved_at')
                ->whereNotNull('deleted_at')
                ->whereBetween('deleted_at', $temp)
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear, $temp[1]->year];
        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageBreederStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers created
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerCreated(){
        $select = ['selected','',''];
        $formroute = 'spectator.averageCustomerStatisticsCreatedYear';
        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                // ->whereNull('deleted_at')
                // ->whereNull('blocked_at')
                ->whereBetween('created_at', $temp)
                ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }


        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers created
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerCreatedYear(Request $request){
        $select = ['selected','',''];
        $formroute = 'spectator.averageCustomerStatisticsCreatedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                // ->whereNull('deleted_at')
                // ->whereNull('blocked_at')
                ->whereBetween('created_at', $temp)
                ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear, $temp[1]->year];
        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers blocked
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerBlocked(){
        $select = ['','selected',''];
        $formroute = 'spectator.averageCustomerStatisticsBlockedYear';
        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                ->whereNotNull('approved_at')
                // ->whereNull('deleted_at')
                ->whereBetween('blocked_at', $temp)
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }


        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers blocked
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerBlockedYear(Request $request){
        $select = ['','selected',''];
        $formroute = 'spectator.averageCustomerStatisticsBlockedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                ->whereNotNull('approved_at')
                // ->whereNull('deleted_at')
                ->whereBetween('blocked_at', $temp)
                ->select(DB::raw('YEAR(blocked_at) year, MONTH(blocked_at) month, MONTHNAME(blocked_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }


        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers deleted
     *
     * @param none
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerDeleted(){
        $select = ['','','selected'];
        $formroute = 'spectator.averageCustomerStatisticsDeletedYear';
        $now = Carbon::now()->endOfYear();
        $yearnow = $now->year;
        $past =  Carbon::now()->subYear(5)->startofYear();
        $yearpast = $past->year;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];

        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                ->whereNotNull('approved_at')
                ->whereNotNull('deleted_at')
                ->whereBetween('deleted_at', $temp)
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }

    /*
     * Display average statistics for monthly customers deleted
     *
     * @param year
     * @return view, string array, string route, array of year, array of count
     *
     */
    public function averageCustomerDeletedYear(Request $request){
        $select = ['','','selected'];
        $formroute = 'spectator.averageCustomerStatisticsDeletedYear';
        $now = $request->yearmax."-12-31";
        $now = Carbon::parse($now);
        $yearnow = $request->yearmax;
        $past =  $request->yearmin."-01-01";
        $past = Carbon::parse($past);
        $yearpast = $request->yearmin;
        $midyear = ceil(($yearpast+$yearnow)/2);
        $temp = [$past, $now];
        sort($temp);
        $counts = DB::table('users')
                ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                ->join('roles', 'role_user.role_id','=','roles.id')
                ->where('role_id','=',3)
                ->whereNotNull('approved_at')
                ->whereNotNull('deleted_at')
                ->whereBetween('deleted_at', $temp)
                ->select(DB::raw('YEAR(deleted_at) year, MONTH(deleted_at) month, MONTHNAME(deleted_at) month_name, COUNT(*)/12 as average_count'))
                ->groupBy('year')
                ->get();

        $year = [$temp[0]->year, $midyear,$temp[1]->year];

        $averageCount = array_fill($year[0],($year[2]-$year[0])+1 ,0);
        // fill the array of counts using the year from the query as the index while the value is the cieling of count average to allow integers only.
        foreach ($counts as $count) {
            $averageCount[$count->year] = ceil($count->average_count);
        }

        return view('user.spectator.averageCustomerStatistics',compact('select', 'formroute', 'year', 'averageCount'));
    }
    public function accountSettings(){
        return view('user.spectator.accountSettings');
    }
    public function changePassword(ChangePasswordRequest $request){
        dd($request);
        return view('user.spectator.accountSettings');
    }
}
