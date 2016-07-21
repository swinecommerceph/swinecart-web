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
     */
    public function __construct(DashboardRepository $dashboard)
    {
        $this->middleware('role:breeder');
        $this->middleware('updateProfile:breeder');
        $this->user = Auth::user();
        $this->dashboard = $dashboard;
    }

    /**
     * Show the Breeder' Dashboard
     *
     * @return View
     */
    public function showDashboard()
    {
        $dashboardCollection = [];
        $dashboardCollection['soldProducts'] = $this->dashboard->getSoldProducts($this->user->userable);
        $dashboardCollection['availableProducts'] = $this->dashboard->getAvailableProducts($this->user->userable);
        $topic = str_slug($this->user->name);
        return view('user.breeder.dashboard', compact('dashboardCollection', 'topic'));
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
        // $products = Product::all();
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
     * Reserve a Product to a Customer
     * AJAX
     *
     * @param  Request  $request
     * @return Array
     */
    public function reserveProduct(Request $request)
    {
        if($request->ajax()){
            $product = Product::find($request->product_id);
            return $this->dashboard->updateStatus($request, $product, 'reserved');
        }
    }

    /**
     * Change Product status to 'on_delivery'
     *
     * @param  Request $request
     */
    public function productDelivery(Request $request)
    {
        if($request->ajax()){
            $product = Product::find($request->product_id);
            return $this->dashboard->updateStatus($request, $product, 'on_delivery');
        }
    }

    /**
     * Change Product status to 'paid'
     *
     * @param Request $request
     */
    public function productPaid(Request $request)
    {
        if($request->ajax()){
            $product = Product::find($request->product_id);
            return $this->dashboard->updateStatus($request, $product, 'paid');
        }
    }
}
