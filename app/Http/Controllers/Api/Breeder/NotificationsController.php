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
        $this->middleware('jwt.role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getNotifications(Request $request)
    {

        $results = $this->user->notifications()->paginate($request->perpage);
        $notifications = $results->items();
        $count = $results->count();

        $notifications = array_map(function ($item) {

            $notification = [];
            $type = explode('\\', $item->type);

            $notification['id'] = $item->id;
            $notification['type'] = end($type);
            $notification['message'] = strip_tags($item->data['description']);
            $notification['created_at'] = $item->created_at->toDateTimeString();
            $notification['read_at'] = $item->read_at ? $item->read_at->toDateTimeString() : null;

            return $notification;
        }, $notifications);

        return response()->json([
            'message' => 'Get Notifications successful!',
            'data' => [
                'count' => $count,
                'notifications' => $notifications
            ]
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
                'data' => $notification
            ]);
        }
        else return response()->json([
            'error' => 'Notification does not exist!',
        ]);
    }
    
}
