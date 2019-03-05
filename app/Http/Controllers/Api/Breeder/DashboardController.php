<?php

namespace App\Http\Controllers\Api\Breeder;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Customer;
use App\Models\Product;
use App\Repositories\DashboardRepository;

use Auth;
use Response;
use Validator;
use JWTAuth;
use Mail;
use Storage;
use Config;

class DashboardController extends Controller
{   

    protected $dashboard;

    public function __construct(DashboardRepository $dashboard) 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
        $this->dashboard = $dashboard;
    }

    private function getBreederProduct($breeder, $product_id)
    {
        $breeder_id = $breeder->id;
        return Product::where([
            ['breeder_id', '=', $breeder_id],
            ['id', '=', $product_id]
        ])->first();
    }

    public function getDashboardStats(Request $request) 
    {
        $breeder = $this->user->userable;

        $dashboardStats = [];
        
        $dashboardStats['hidden'] = $this->dashboard->getProductNumberStatus($breeder,'hidden');
        $dashboardStats['displayed'] = $this->dashboard->getProductNumberStatus($breeder,'displayed');
        $dashboardStats['requested'] = $this->dashboard->getProductNumberStatus($breeder,'requested');
        $dashboardStats['reserved'] = $this->dashboard->getProductNumberStatus($breeder,'reserved');
        $dashboardStats['on_delivery'] = $this->dashboard->getProductNumberStatus($breeder,'on_delivery');

        $dashboardStats['ratings'] = $this->dashboard->getSummaryReviewsAndRatings($breeder);

        return response()->json([
            'message' => 'Get Dashboard Stats successful!',
            'data' => $dashboardStats
        ], 200);
    }

    public function getLatestAccre(Request $request) 
    {
        $latestAccreditation = $this->user->userable->latest_accreditation;

        return response()->json([
            'message' => 'Get Latest Accrediation successful!',
            'data' => $latestAccreditation
        ], 200);
    }

    public function getServerDate(Request $request)
    {
        $serverDateNow = Carbon::now();

        return response()->json([
            'message' => 'Get Server Date successful!',
            'data' => $serverDateNow
        ], 200);
    }

    public function getSoldData(Request $request)
    {   
        $breeder = $this->user->userable;
        $serverDateNow = Carbon::now();

        $soldData = $this->dashboard->getSoldProducts(
            (object) [
                'dateFrom' => $serverDateNow->copy()->subMonths(2)->format('Y-m-d'),
                'dateTo' => $serverDateNow->format('Y-m-d'),
                'frequency' => 'monthly'
            ], $breeder);

        return response()->json([
            'message' => 'Get Sold Data successful!',
            'data' => $soldData
        ], 200);
    }

    public function getRatings(Request $request)
    {
        $breeder = $this->user->userable;
        $reviews = $breeder->reviews()->orderBy('created_at', 'desc')->get();

        foreach ($reviews as $review) {
            $review->date = Carbon::createFromFormat('Y-m-d H:i:s', $review->created_at)->toFormattedDateString();
            $customer = Customer::find($review->customer_id);
            $review->customerName = $customer->users()->first()->name;
            $review->customerProvince = $customer->address_province;
            $review->showDetailedRatings = false;
        }

        $deliveryRating = $reviews->avg('rating_delivery');
        $transactionRating = $reviews->avg('rating_transaction');
        $productQualityRating = $reviews->avg('rating_productQuality');
        $overallRating = round(($deliveryRating + $transactionRating + $productQualityRating)/3, 2);

        return response()->json([
            'message' => 'Get Ratings successful!',
            'data' => [
                'ratings' => $overallRating
            ]
        ]);
    }

    public function getSoldProducts(Request $request)
    {
        $breeder = $this->user->userable;
        
        $soldProducts = $this->dashboard->getSoldProducts($request, $breeder);

        return response()->json([
            'message' => 'Get Sold Products successful!',
            'data' => $soldProducts
        ]);
    }

    public function updateProductStatus(Request $request, $product_id)
    {
        $breeder = $this->user->userable;
        $product = $this->getBreederProduct($breeder, $product_id);

        if($product) {
            $result = $this->dashboard->updateStatus($request, $product);
            return response()->json([
                'message' => 'Update Product Status successful!',
                'data' => $result
            ]);
        }
        
        else return response()->json([
            'error' => 'Product does not exist!'
        ], 200);
    }

    public function getCustomer(Request $request, $customer_id)
    {
        $customer = Customer::find($customer_id);

        if($customer) {
            return response()->json([
                'message' => 'Get Customer Info successful!',
                'data' => [
                    'customer' => $customer
                ]
            ]);
        }

        else return response()->json([
            'error' => 'Customer does not exist!'
        ], 404); 
    }

    public function getCustomers(Request $request)
    {
        $breeder = $this->user->userable;

        // $customers = $breeder->transactionLogs()->get();
        $customers = $breeder->transactionLogs()->where('status', '!=', 'cancel_transaction')->get();

        $customers = $customers->map(function ($log) {
            $log->customer = $log->customer()->first();
            return $log;
        });


        return response()->json([
            'message' => 'Get Customers successful!',
            'data' => $customers
        ]);
    }
}