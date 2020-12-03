<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Models\Message;
use App\Models\User;

use JWTAuth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            $this->accountType = explode('\\', $this->user->userable_type)[2];
            return $next($request);
        });
    }

    public function getChats(Request $request)
    {
        return response()->json([
            'data' => 'hello'
        ], 200);
    }

    public function getConversation(Request $request, $other_user_id)
    {
        $user_id = $this->user->id;
        $user_str = $this->accountType === 'Breeder'
            ? 'breeder_id'
            : 'customer_id';
        $other_user_str = $user_str === 'breeder_id'
            ? 'customer_id'
            : 'breeder_id';

        $result = Message::where($user_str, '=', $user_id)
            ->where($other_user_str, '=', $other_user_id)
            ->orderBy('created_at', 'DESC')
            ->paginate($request->limit);

        $messages = collect($result->items())
            ->map(function ($item) {

                $message = [];

                $message['id'] = $item->id;
                $message['direction'] = $item->direction;
                $message['content'] = $item->message;
                $message['readAt'] = $item->read_at;
                $message['createdAt'] = $item->created_at->toDateTimeString();
                $message['fromId'] = $item->direction === 1
                    ? $item->breeder_id : $item->customer_id;
                $message['toId'] = $item->direction === 1
                    ? $item->customer_id : $item->breeder_id;

                return $message;
            });

        return response()->json([
            'data' => [
                'count' => $result->count(),
                'hasNext' => $result->hasMorePages(),
                'messages' => $messages,
            ]
        ], 200);
    }
}
