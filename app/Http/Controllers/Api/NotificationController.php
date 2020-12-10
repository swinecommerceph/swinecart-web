<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use JWTAuth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getNotifications(Request $request)
    {
        $notifications = $this->user->notifications();

        $notifications = $notifications->paginate($request->limit);

        $formatted = $notifications->map(function ($item) {

            $notification = [];
            $type = explode('\\', $item->type);

            $notification['id'] = $item->id;
            $notification['type'] = end($type);
            $notification['message'] = strip_tags($item->data['description']);
            $notification['createdAt'] =
                $item->created_at
                    ?
                        $item->created_at->toDateTimeString()
                    : null;
            $notification['readAt'] =
                $item->read_at
                    ? $item->read_at->toDateTimeString()
                : null;

            return $notification;
        });

        return response()->json([
            'data' => [
                'hasNextPage' => $notifications->hasMorePages(),
                'notifications' => $formatted
            ]
        ], 200);
    }

    public function seeNotification(Request $request, $notification_id)
    {

        $notification = $this->user->notifications()
            ->where('id', $notification_id)
            ->get()
            ->first();

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
