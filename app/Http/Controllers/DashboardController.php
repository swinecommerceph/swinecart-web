<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests;
use App\Models\Customer;
use App\Models\Product;
use App\Repositories\DashboardRepository;

use Auth;

class DashboardController extends Controller
{
    protected $user;
    protected $dashboard;

    /**
     * Create new DashboardController instance
     *
     * @param  DashboardRepository $dashboard
     */
    public function __construct(DashboardRepository $dashboard)
    {
        $this->middleware('role:breeder');
        $this->middleware('updateProfile:breeder');
        $this->middleware(function($request, $next){
            $this->user = Auth::user();

            return $next($request);
        });
        $this->dashboard = $dashboard;
    }

    /**
     * Show the Breeder' Dashboard
     *
     * @return View
     */
    public function showDashboard()
    {
        $dashboardStats = [];
        $breeder = $this->user->userable;

        $dashboardStats['hidden'] = $this->dashboard->getProductNumberStatus($breeder,'hidden');
        $dashboardStats['displayed'] = $this->dashboard->getProductNumberStatus($breeder,'displayed');
        $dashboardStats['requested'] = $this->dashboard->getProductNumberStatus($breeder,'requested');
        $dashboardStats['reserved'] = $this->dashboard->getProductNumberStatus($breeder,'reserved');
        $dashboardStats['on_delivery'] = $this->dashboard->getProductNumberStatus($breeder,'on_delivery');
        $dashboardStats['paid'] = $this->dashboard->getProductNumberStatus($breeder,'paid');
        $dashboardStats['ratings'] = $this->dashboard->getSummaryReviewsAndRatings($breeder);

        $latestAccreditation = $this->user->userable->latest_accreditation;
        $serverDateNow = Carbon::now();
        $soldData = $this->dashboard->getSoldProducts(
            (object) [
                'dateFrom' => $serverDateNow->copy()->subMonths(2)->format('Y-m-d'),
                'dateTo' => $serverDateNow->format('Y-m-d'),
                'frequency' => 'monthly'
            ], $breeder);

        $topic = str_slug($this->user->name);

        return view('user.breeder.dashboard', compact('dashboardStats', 'latestAccreditation', 'serverDateNow', 'soldData', 'topic'));
    }

    /**
     * Show the statuses of the Breeder's products
     * Basically, more like an inventory
     *
     * @return View
     */
    public function showProductStatus()
    {
        $products = $this->dashboard->forBreeder($this->user->userable);
        $token = csrf_token();
        return view('user.breeder.dashboardProductStatus', compact('products', 'token'));
    }

    /**
     * Show the reviews and ratings of the Breeder from the Customers
     *
     * @return View
     */
    public function showReviewsAndRatings()
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

        return view('user.breeder.reviews', compact('reviews', 'overallRating'));
    }

    /**
     * Get Customers who requested for a respective Product
     * AJAX
     *
     * @param  Request  $request
     * @return Array
     */
    public function retrieveProductRequests(Request $request)
    {
        if($request->ajax()){
            return $this->dashboard->getProductRequests($request->product_id);
        }
    }

    /**
     * Get sold products of Breeder on a specified time frequency
     * AJAX
     *
     * @param  Request  $request
     * @return Array
     */
    public function retrieveSoldProducts(Request $request)
    {
        if($request->ajax()){
            $breeder = $this->user->userable;
            return $this->dashboard->getSoldProducts($request, $breeder);
        }
    }

    /**
     * Get Customers who requested for a respective Product
     * AJAX
     *
     * @param  Request  $request
     * @return Array
     */
    public function updateProductStatus(Request $request)
    {
        if($request->ajax()){
            $product = Product::find($request->product_id);
            return $this->dashboard->updateStatus($request, $product);
        }
    }
}
