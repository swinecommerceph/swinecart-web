<?php

namespace App\Http\Controllers\Api\Breeder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Models\Message;
use App\Models\User;

use JWTAuth;
use Auth;

class MessageController extends Controller
{
    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }


    public function unreadCount(Request $request)
    {   
        $user_id = $this->user->id;

        $count = Message::where('breeder_id', '=', $user_id)
				->where('read_at', NULL)
				->where('direction', 0) //from customer to breeder
	    		->orderBy('created_at', 'ASC')
	    		->groupBy('customer_id')
                ->get();
                
        return response()->json([
            'message' => 'Unread Count successful!',
            'data' => sizeof($count)
        ], 200);
    }

    public function getMessages(Request $request, $customer_id)
    {   
        $user_id = $this->user->id;

        $messages = Message::where('breeder_id', '=', $user_id)
                ->where('customer_id', $customer_id)
	    		->orderBy('created_at', 'DESC')
                ->get();
        
        return response()->json([
            'message' => 'Get Messages successful!',
            'data' => $messages
        ], 200);
    }

    public function getThreads(Request $request)
    {   
        $user_id = $this->user->id;

        $threads = Message::where('breeder_id', '=', $user_id)
	    		->orderBy('created_at', 'DESC')
                ->get()
                ->unique('customer_id');

        return response()->json([
            'message' => 'Get Threads successful!',
            'data' => $threads
        ], 200);
        
    }
}
