<?php

namespace App\Http\Controllers\Api\Customer;

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
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }


    public function unreadCount(Request $request)
    {   
        $user_id = $this->user->id;

        $count = Message::where('customer_id', '=', $user_id)
				->where('read_at', NULL)
				->where('direction', 1) //from customer to breeder
	    		->orderBy('created_at', 'ASC')
	    		->groupBy('breeder_id')
                ->get();
                
        return response()->json([
            'message' => 'Unread Count successful!',
            'data' => sizeof($count)
        ], 200);
    }

    public function getMessages(Request $request, $breeder_id)
    {   
        $user_id = $this->user->id;

        $messages = Message::where('customer_id', '=', $user_id)
                ->where('breeder_id', $breeder_id)
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

        $threads = Message::where('customer_id', '=', $user_id)
	    		->orderBy('created_at', 'DESC')
                ->get()
                ->unique('breeder_id');

        return response()->json([
            'message' => 'Get Threads successful!',
            'data' => $threads
        ], 200);
        
    }
}
