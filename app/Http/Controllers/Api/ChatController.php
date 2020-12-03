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
        $user_id = $this->user->id;
        $user_str = $this->accountType === 'Breeder'
            ? 'breeder_id'
            : 'customer_id';
        $other_user_str = $user_str === 'breeder_id'
            ? 'customer_id'
            : 'breeder_id';

        $threads = Message::where($user_str, '=', $user_id)
	    		->orderBy('id', 'DESC')
                ->get()
                ->unique($other_user_str)
                ->values()
                ->forPage($request->page, $request->limit)
                ->map(function ($item) use ($other_user_str) {

                    $thread = [];

                    $user = User::find($item->{$other_user_str});

                    $thread['user'] = [
                        'id' => $user->id,
                        'name' => $user->name
                    ];

                    $thread['message'] = [
                        'id' => $item->id,
                        'direction' => $item->direction,
                        'content' => $item->message,
                        'readAt' => $item->read_at,
                        'createdAt' => $item->created_at->toDateTimeString(),
                        'fromId' => $item->direction === 1
                            ? $item->breeder_id : $item->customer_id,
                        'toId' => $item->direction === 1
                            ? $item->customer_id : $item->breeder_id,
                    ];

                    return $thread;
                })
                ->values()
                ->all();

        return response()->json([
            'data' => [
                'threads' => $threads,
            ]
        ], 200);

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
                'hasNextPage' => $result->hasMorePages(),
                'messages' => $messages,
            ]
        ], 200);
    }
}
