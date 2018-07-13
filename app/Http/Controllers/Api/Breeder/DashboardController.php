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
        $this->middleware('jwt:role:breeder');
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
}
