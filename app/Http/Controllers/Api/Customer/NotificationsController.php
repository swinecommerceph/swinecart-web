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
        $notifications = $this->user->notifications();

        $notifications = $notifications
            ->paginate($request->limit)
            ->map(function ($item) {
                $notification = [];
                $type = explode('\\', $item->type);

                $notification['id'] = $item->id;
                $notification['type'] = end($type);
                $notification['message'] = strip_tags($item->data['description']);
                $notification['createdAt'] = $item->created_at->toDateTimeString();
                $notification['readAt'] = $item->read_at ? $item->read_at->toDateTimeString() : null;

                return $notification;
            });

        return response()->json([
            'data' => [
                'notifications' => $notifications
            ]
        ], 200);

    }

    public function seeNotification(Request $request, $notification_id)
    {   

        $notification = $this->user->notifications()->where('id', $notification_id)->get()->first();

        if($notification) {
            if($notification->read_at) {
                return response()->json([
                    'error' => 'Notification already seen!',
                ], 409);
            }
            else {
                $notification->markAsRead();

                return response()->json([
                    'data' => [
                        'read_at' => $notification->read_at->toDateTimeString()
                    ]
                ]);
            }
        }
        else return response()->json([
            'error' => 'Notification does not exist!',
        ], 404);
    }
}
