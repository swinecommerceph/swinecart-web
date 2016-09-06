<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Customer;
use App\Models\Product;
use App\Repositories\DashboardRepository;

use Auth;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;

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
        $dashboardStats['hidden'] = $this->dashboard->getProductStatus($breeder,'hidden');
        $dashboardStats['displayed'] = $this->dashboard->getProductStatus($breeder,'displayed');
        $dashboardStats['requested'] = $this->dashboard->getProductStatus($breeder,'requested');
        $dashboardStats['reserved'] = $this->dashboard->getProductStatus($breeder,'reserved');
        $dashboardStats['on_delivery'] = $this->dashboard->getProductStatus($breeder,'on_delivery');
        $dashboardStats['paid'] = $this->dashboard->getProductStatus($breeder,'paid');
        $dashboardStats['sold'] = $this->dashboard->getProductStatus($breeder,'sold');
        $dashboardStats['ratings'] = $this->dashboard->getRatings($breeder);
        $topic = str_slug($this->user->name);
        return view('user.breeder.dashboard', compact('dashboardStats', 'topic'));
    }

    /**
     * Show the statuses of the Breeder's products
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
