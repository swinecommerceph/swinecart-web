<?php

namespace App\Http\Controllers\Api\Customer;

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
    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getNotifications(Request $request)
    {
        $notifications = [];

        foreach ($this->user->notifications as $notification) {
            $notificationInstance = [];
            $notificationInstance['id'] = $notification->id;
            $notificationInstance['data'] = $notification->data;
            $notificationInstance['read_at'] = $notification->read_at;
            array_push($notifications, $notificationInstance);
        }

        
        return response()->json([
            'message' => 'Get Notifications successful!',
            'data' => $notifications
        ], 200);

    }

    public function getNotificationsCount(Request $request)
    {
        $notifications = $this->user->unreadNotifications->count();

        return response()->json([
            'message' => 'Get Notifications Count successful!',
            'data' => $notifications
        ]);
        
    }

    public function seeNotification(Request $request, $notification_id)
    {   

        $notification = $this->user->notifications()->where('id', $notification_id)->get()->first();

        if($notification) {
            $notification->markAsRead();
            return response()->json([
                'message' => 'See Notifications successful!',
            ]);
        }
        else return response()->json([
            'error' => 'Notification does not exist!',
        ]);
    }
}
