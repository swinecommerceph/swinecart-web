<?php

namespace App\Http\Controllers\Api\Breeder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt:role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }


    public function countUnread()
    {   
        $user_id = $this->user->id;

        $count = Message::where('breeder_id', '=', $user_id)
				->where('read_at', NULL)
				->where('direction', 0) //from customer to breeder
	    		->orderBy('created_at', 'ASC')
	    		->groupBy('customer_id')
	    		->get();
    }

    public function getMessages()
    {
        $threads = Message::where('breeder_id', '=', $userId)
	    		->orderBy('created_at', 'DESC')
	    		->get()
                ->unique('customer_id');
            
    }
}
