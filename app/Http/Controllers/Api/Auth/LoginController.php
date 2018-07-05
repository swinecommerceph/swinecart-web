<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Response;
use \Illuminate\Http\Response as Res;
use Validator;
use JWTAuth;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends ApiController
{
    public function __construct() 
    {
        $this->middleware('guest:api');
    }

    public function normalLogin(Request $request) {
        $email = $request->email;
        $password = $request->password;
        $credentials = ['email' => $email, 'password' => $password];
        
        if(! $token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError("User does not exist!");
        }

        $user = JWTAuth::toUser($token);
        $user->api_token = $token;
        $user->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'message' => 'Login',
            'data' => $user
        ]);
    }
}
