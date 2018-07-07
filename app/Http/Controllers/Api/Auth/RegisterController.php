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
use Mail;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data, $verCode)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verification_code' => $verCode
        ]);
    }


    /**
     * Handle a registration request for the application.
     * Override default register method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json string
     */

    public function register(Request $request) 
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return $this->respondValidationError('Fields Validation Failed.', 
            $validator->errors());
        }
        else {
            $verCode = str_random('10');
            $user = $this->create($request->all(), $verCode);
            $user->assignRole('customer');
            $email = $request->email;

            $data = [
                'email' => $email,
                'verCode' => $verCode,
                'level' => 'success',
                'introLines' => ['Registration is almost complete.', "Click the 'Verify Code' button to verify your email."],
                'outroLines' => ['If you did not plan to register on this site, no further action is required.'],
                'actionText' => 'Verify Code',
                'actionUrl' => route('verCode.send', ['email' => $email, 'verCode' => $verCode]),
                'type' => 'sent'
            ];

            // Mail::send('vendor.notifications.email', $data, function ($message) use($data){
            //     $message->to($data['email'])->subject('Verification code for SwineCart');
            // });

            return $this->respond([
                'status' => 'success',
                'status_code' => Res::HTTP_OK,
                'message' => 'Register successful!',
                'data' => $data
            ]);

        }
    }
}
