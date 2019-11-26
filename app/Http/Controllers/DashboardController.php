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
        //$this->middleware('updateProfile:breeder');
        $this->middleware(function($request, $next){
            $this->user = Auth::user();

            return $next($request);
        });
        $this->dashboard = $dashboard;
    }

    /**
     * Show the Breeder' Dashboard
     * @param Request $request
     * @return View
     */
    public function showDashboard(Request $request)
    {
      // dd($request->all());
      $breeder = $this->user->userable;
      $farmAddresses = $breeder->farmAddresses;
      
      //dd($farmAddresses);

      if($request->user()->updateProfileNeeded()){
        $provinces = $this->getProvinces();
        return view('user.breeder.createProfile', compact('breeder', 'farmAddresses', 'provinces'));
      }

      $dashboardStats = [];
      // $breeder = $this->user->userable;


      /* $products = ($request->farm_address === 'all-farms') 
                    ? 
                    $breeder->products() : 
                    $breeder->products()->where('farm_from_id', $request->farm_address);
      
      $products = $products
        ->get()
        ->groupBy('status')
        ->map(function ($status) {
            return $status->groupBy('type')->map(function ($type) {
              return $type->count();
            });
          });

      dd($products); */

      $dashboardStats['hidden'] = $this->dashboard->getProductNumberStatus($breeder,'hidden');
      $dashboardStats['displayed'] = $this->dashboard->getProductNumberStatus($breeder,'displayed');
      $dashboardStats['requested'] = $this->dashboard->getProductNumberStatus($breeder,'requested');
      $dashboardStats['reserved'] = $this->dashboard->getProductNumberStatus($breeder,'reserved');
      $dashboardStats['on_delivery'] = $this->dashboard->getProductNumberStatus($breeder,'on_delivery');
      $dashboardStats['ratings'] = $this->dashboard->getSummaryReviewsAndRatings($breeder);

      $serverDateNow = Carbon::now();
      $latestAccreditation = $this->user->userable->latest_accreditation;
      $soldData = $this->dashboard->getSoldProducts(
        (object) [
            'dateFrom' => $serverDateNow->copy()->subMonths(2)->format('Y-m-d'),
            'dateTo' => $serverDateNow->format('Y-m-d'),
            'frequency' => 'monthly'
        ], $breeder);
      
      return view('user.breeder.dashboard', compact('farmAddresses', 'dashboardStats', 'latestAccreditation', 'serverDateNow', 'soldData'));
    }

    /**
     * Show the Breeder' Reports
     * @param Request $request
     * @return View
     */
    public function showReports(Request $request)
    {      
      return view('user.breeder.reports');
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
     * Update transaction status of Product
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

    /**
     * Get customer's information
     *
     * @param  Request  $request
     * @return JSON
     */
    public function getCustomerInfo(Request $request)
    {
        if($request->ajax()){
            return Customer::find($request->customer_id);
        }
    }

    /**
     * Get the sorted provinces all over the Philippines
     *
     * @return  Array
     */
    private function getProvinces()
    {
        return collect([
            // Negros Island Rregion
            'Negros Occidental' => 'Negros Occidental',
            'Negros Oriental' => 'Negros Oriental',
            // Cordillera Administrative Region
            'Mountain Province' => 'Mountain Province',
            'Ifugao' => 'Ifugao',
            'Benguet' => 'Benguet',
            'Abra' => 'Abra',
            'Apayao' => 'Apayao',
            'Kalinga' => 'Kalinga',
            // Region I
            'La Union' => 'La Union',
            'Ilocos Norte' => 'Ilocos Norte',
            'Ilocos Sur' => 'Ilocos Sur',
            'Pangasinan' => 'Pangasinan',
            // Region II
            'Nueva Vizcaya' => 'Nueva Vizcaya',
            'Cagayan' => 'Cagayan',
            'Isabela' => 'Isabela',
            'Quirino' => 'Quirino',
            'Batanes' => 'Batanes',
            // Region III
            'Bataan' => 'Bataan',
            'Zambales' => 'Zambales',
            'Tarlac' => 'Tarlac',
            'Pampanga' => 'Pampanga',
            'Bulacan' => 'Bulacan',
            'Nueva Ecija' => 'Nueva Ecija',
            'Aurora' => 'Aurora',
            // Region IV-A
            'Rizal' => 'Rizal',
            'Cavite' => 'Cavite',
            'Laguna' => 'Laguna',
            'Batangas' => 'Batangas',
            'Quezon' => 'Quezon',
            // Region IV-B
            'Occidental Mindoro' => 'Occidental Mindoro',
            'Oriental Mindoro' => 'Oriental Mindoro',
            'Romblon' => 'Romblon',
            'Palawan' => 'Palawan',
            'Marinduque' => 'Marinduque',
            // Region V
            'Catanduanes' => 'Catanduanes',
            'Camarines Norte' => 'Camarines Norte',
            'Sorsogon' => 'Sorsogon',
            'Albay' => 'Albay',
            'Masbate' => 'Masbate',
            'Camarines Sur' => 'Camarines Sur',
            // Region VI
            'Capiz' => 'Capiz',
            'Aklan' => 'Aklan',
            'Antique' => 'Antique',
            'Iloilo' => 'Iloilo',
            'Guimaras' => 'Guimaras',
            // Region VII
            'Cebu' => 'Cebu',
            'Bohol' => 'Bohol',
            'Siquijor' => 'Siquijor',
            // Region VIII
            'Southern Leyte' => 'Southern Leyte',
            'Eastern Samar' => 'Eastern Samar',
            'Northern Samar' => 'Northern Samar',
            'Western Samar' => 'Western Samar',
            'Leyte' => 'Leyte',
            'Biliran' => 'Biliran',
            // Region IX
            'Zamboanga Sibugay' => 'Zamboanga Sibugay',
            'Zamboanga del Norte' => 'Zamboanga del Norte',
            'Zamboanga del Sur' => 'Zamboanga del Sur',
            // Region X
            'Misamis Occidental' => 'Misamis Occidental',
            'Bukidnon' => 'Bukidnon',
            'Lanao del Norte' => 'Lanao del Norte',
            'Misamis Oriental' => 'Misamis Oriental',
            'Camiguin' => 'Camiguin',
            // Region XI
            'Davao Oriental' => 'Davao Oriental',
            'Compostela Valley' => 'Compostela Valley',
            'Davao del Sur' => 'Davao del Sur',
            'Davao Occidental' => 'Davao Occidental',
            'Davao del Norte' => 'Davao del Norte',
            // Region XII
            'South Cotabato' => 'South Cotabato',
            'Sultan Kudarat' => 'Sultan Kudarat',
            'North Cotabato' => 'North Cotabato',
            'Sarangani' => 'Sarangani',
            // Region XIII
            'Agusan del Norte' => 'Agusan del Norte',
            'Agusan del Sur' => 'Agusan del Sur',
            'Surigao del Sur' => 'Surigao del Sur',
            'Surigao del Norte' => 'Surigao del Norte',
            'Dinagat Islands' => 'Dinagat Islands',
            // ARMM
            'Tawi-tawi' => 'Tawi-tawi',
            'Basilan' => 'Basilan',
            'Sulu' => 'Sulu',
            'Maguindanao' => 'Maguindanao',
            'Lanao del Sur' => 'Lanao del Sur'
        ])->sort();
    }
}
