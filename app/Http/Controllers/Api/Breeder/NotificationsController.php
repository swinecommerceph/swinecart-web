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

class NotificationsController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt:role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getNotifications(Request $request)
    {
        
    }
}
