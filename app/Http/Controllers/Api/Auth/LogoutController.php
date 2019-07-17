<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use \Illuminate\Http\Response as Res;
use Validator;
use JWTAuth;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;


class LogoutController extends Controller
{   

    public function __construct() 
    {
        $this->middleware('jwt:auth');
    }

    public function logout(Request $request)
    {   
        $user = JWTAuth::user();
        $userLog = new UserLog;
        $userLog->user_id = $user->id;
        $userLog->user_type = $user->roles()->first()->title;
        $userLog->ip_address = $request->ip();
        $userLog->activity = 'logout';
        $userLog->created_at = Carbon::now();
        $userLog->save();

        $token = JWTAuth::fromUser($user);
        JWTAuth::invalidate($token);

        return response()->json([
            'message' => 'Logout successful!'
        ]);
    }
}
