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


class LoginController extends Controller
{   

    public function __construct() 
    {
        $this->middleware('jwt:auth', ['except' => 'normalLogin']);
    }

    public function normalLogin(Request $request) {

        $credentials = $request->only(['email', 'password']);
        
        if(! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized!'], 401);
        }

        $user = JWTAuth::user();
        $userLog = new UserLog;
        $userLog->user_id = $user->id;
        $userLog->user_type = $user->roles()->first()->title;
        $userLog->ip_address = $request->ip();
        $userLog->activity = 'login';
        $userLog->created_at = Carbon::now();
        $userLog->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function me(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json([
            'user' => $user,
        ]);
    }
}
