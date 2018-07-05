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


class LogoutController extends ApiController
{
    public function __construct() 
    {
        $this->middleware('guest:api');
    }

    public function logout($api_token)
    {
        try{
            $user = JWTAuth::toUser($api_token);
            $user->api_token = NULL;
            $user->save();
            JWTAuth::setToken($api_token)->invalidate();
            $this->setStatusCode(Res::HTTP_OK);

            $userLog = new UserLog;
            $userLog->user_id = $this->guard()->id();
            $userLog->user_type = $this->guard()->user()->roles()->first()->title;
            $userLog->ip_address = $request->ip();
            $userLog->activity = 'logout';
            $userLog->created_at = Carbon::now();
            $userLog->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Logout successful!',
                'data' => $userLog
            ]);

        }catch(JWTException $e){
            return $this->respondInternalError("An error occurred while performing an action!");
        }
}
