<?php

namespace App\Http\Controllers\Api\Breeder;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Customer;
use App\Models\Product;
use App\Repositories\DashboardRepository;
use App\Repositories\CustomHelpers;

use Auth;
use Response;
use Validator;
use JWTAuth;
use Mail;
use Storage;
use Config;

class DashboardController extends Controller
{   

    use CustomHelpers {
        transformDateSyntax as private;
        computeAge as private;
    }

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

    public function getDashboardStats(Request $request) 
    {
        $breeder = $this->user->userable;

        $dashboardStats = [];
        
        $dashboardStats['hidden'] = $this->dashboard->getProductNumberStatus($breeder,'hidden');
        $dashboardStats['displayed'] = $this->dashboard->getProductNumberStatus($breeder,'displayed');
        $dashboardStats['requested'] = $this->dashboard->getProductNumberStatus($breeder,'requested');
        $dashboardStats['reserved'] = $this->dashboard->getProductNumberStatus($breeder,'reserved');
        $dashboardStats['on_delivery'] = $this->dashboard->getProductNumberStatus($breeder,'on_delivery');

        return response()->json([
            'message' => 'Get Dashboard Stats successful!',
            'data' => $dashboardStats
        ], 200);
    }

    public function getServerDate(Request $request)
    {
        return response()->json([
            'message' => 'Get Server Date successful!',
            'data' => [
                'server_date' => Carbon::now()
            ]
        ], 200);
    }

    public function getRatings(Request $request)
    {
        $breeder = $this->user->userable;
        $reviews = $breeder->reviews()->orderBy('created_at', 'desc')->get();

        $deliveryRating = $reviews->avg('rating_delivery');
        $transactionRating = $reviews->avg('rating_transaction');
        $productQualityRating = $reviews->avg('rating_productQuality');
        $overallRating = round(($deliveryRating + $transactionRating + $productQualityRating)/3, 2);

        return response()->json([
            'message' => 'Get Ratings successful!',
            'data' => [
                'ratings' => [
                    'delivery' => $deliveryRating,
                    'transaction' => $transactionRating,
                    'productQuality' => $productQualityRating,
                    'overall' => $overallRating
                ]
            ]
        ], 200);
    }

    public function getReviews(Request $request)
    {
        $breeder = $this->user->userable;

        $reviews = $breeder
            ->reviews()
            ->orderBy('created_at', 'desc')
            ->paginate($request->limit)
            ->map(function ($item) {
                $review = [];
                $customer = Customer::find($item->customer_id);

                $review['id'] = $item->id;
                $review['comment'] = $item->comment;
                $review['customer_name'] = $customer->users()->first()->name;
                $review['customer_province'] = $customer->address_province;
                $review['created_at'] = $this->transformDateSyntax($item->created_at, 3);
                $review['rating']['delivery'] = $item->rating_delivery;
                $review['rating']['transaction'] = $item->rating_transaction;
                $review['rating']['productQuality'] = $item->rating_productQuality;

                return $review;
            });

        return response()->json([
            'message' => 'Get Reviews successful!',
            'data' => [
                'count' => $reviews->count(),
                'reviews' => $reviews
            ]
        ], 200);
    }
}