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

        $stats = [];
        
        $stats['hidden'] = $this->dashboard->getProductNumberStatus($breeder,'hidden');
        $stats['displayed'] = $this->dashboard->getProductNumberStatus($breeder,'displayed');
        $stats['requested'] = $this->dashboard->getProductNumberStatus($breeder,'requested');
        $stats['reserved'] = $this->dashboard->getProductNumberStatus($breeder,'reserved');
        $stats['on_delivery'] = $this->dashboard->getProductNumberStatus($breeder,'on_delivery');
        $stats['sold'] = $this->dashboard->getProductNumberStatus($breeder,'sold');

        return response()->json([
            'data' => [
                'stats' => $stats
            ]

        ], 200);
    }

    public function getRatings(Request $request)

    {
        $breeder = $this->user->userable;
        $reviews = $breeder->reviews()->get();

        $deliveryRating = $reviews->avg('rating_delivery');
        $transactionRating = $reviews->avg('rating_transaction');
        $productQualityRating = $reviews->avg('rating_productQuality');
        $overallRating = round(($deliveryRating + $transactionRating + $productQualityRating)/3, 2);

        return response()->json([
            'data' => [
                'ratings' => [
                    'delivery' => $deliveryRating ?? 0,
                    'transaction' => $transactionRating ?? 0,
                    'productQuality' => $productQualityRating ?? 0,
                    'overall' => $overallRating ?? 0
                ]
            ]
        ], 200);
    }

    public function getReviews(Request $request)
    {
        $breeder = $this->user->userable;

        $reviews = $breeder
            ->reviews()
            ->with('customer.user')
            ->orderBy('created_at', 'desc')
            ->paginate($request->limit)
            ->map(function ($item) {
                $review = [];

                $customer = $item->customer;

                $review['id'] = $item->id;
                $review['comment'] = $item->comment;
                $review['customer_name'] = $customer->users()->first()->name;
                $review['customer_province'] = $customer->address_province;
                $review['created_at'] = $item->created_at->toDateTimeString();
                $review['rating']['delivery'] = $item->rating_delivery;
                $review['rating']['transaction'] = $item->rating_transaction;
                $review['rating']['productQuality'] = $item->rating_productQuality;

                return $review;
            });

        return response()->json([
            'data' => [
                'reviews' => $reviews
            ]
        ], 200);
    }

    public function getReviewCount(Request $request)
    {
        $breeder = $this->user->userable;

        $review_count = $breeder
            ->reviews()
            ->count();

        return response()->json([
            'data' => [
                'review_count' => $review_count
            ]
        ], 200);
    }
}