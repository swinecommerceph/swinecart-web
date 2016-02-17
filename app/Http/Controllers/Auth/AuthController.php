<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use SocialAuth;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;
use Illuminate\Http\Request;
use Mail;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Specify rediret URI after login
     */
    protected $redirectPath = '/home';

    /**
     * Specify login path for user login view
     */
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout','verifyCode']]);
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
            'password' => 'required|confirmed|min:6',
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
     * Redirect user to chosen provider of third-party authentication
     */
    public function redirectToProvider($provider)
    {
        if($provider == 'google' || $provider == 'facebook') return SocialAuth::authorize($provider);
        else return response('Not Found', 404);
    }

    /**
     * Handle the information from the provider and authenticate User
     */
    public function handleProviderCallback($provider)
    {
        try {
            if($provider == 'google' || $provider == 'facebook'){

                SocialAuth::login($provider, function($user, $details) {
                    $user->email = $details->email;
                    $user->name = $details->full_name;
                    $user->email_verified = 1;
                    $user->save();
                    if(!$user->hasRole('customer'))$user->assignRole('customer');
                });
            }
            else return response('Not Found', 404);
        } catch (ApplicationRejectedException $e) {
            // User rejected application
            return redirect()->route('getRegister_path');
        } catch (InvalidAuthorizationCodeException $e) {
            // Authorization was attempted with invalid
            // code,likely forgery attempt
            return redirect()->route('getLogin_path');
        }

        return redirect()->route('home_path');
    }

    /**
     * Handle a registration request for the application.
     * Override default register method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $verCode = str_random('10');
        $user = $this->create($request->all(), $verCode);
        $user->assignRole('customer');
        $data = [
            'email' => $request->input('email'),
            'verCode' => $verCode,
            'type' => 'sent'
        ];

        Mail::send('emails.verification', $data, function ($message) use($data){
            $message->to($data['email'])->subject('Verification code for Swine E-Commerce PH');
        });

        // return view('emails.verification', $data);
        return view('emails.message', $data);

    }

    /**
     * Authenticate user if email and verification
     * code matches in the database
     *
     * @param   string  $email
     * @param   string  $verCode
     * @return  View or Redirect
     */
    public function verifyCode($email, $verCode)
    {
        if(Auth::check()){
            Auth::logout();
        }

        $verUser = User::where('email', $email)->first();
        if($verUser->verification_code == $verCode){
            Auth::login($verUser);
            $verUser->email_verified = 1;
            $verUser->save();
            return redirect($this->redirectPath());
        }
        else return redirect()->route('getRegister_path')->with('message','Verification code invalid!');
    }

    /**
     * Resend verifcation code to user
     *
     * @param   string  $email
     * @param   string  $verCode
     * @return  View or Redirect
     */
    public function resendCode($email, $verCode)
    {
        $user = User::where('email', $email)->first();
        if($user->verification_code == $verCode){
            $data = [
                'email' => $email,
                'verCode' => $verCode,
                'type' => 'resent'
            ];

            Mail::send('emails.verification', $data, function ($message) use($data){
                $message->to($data['email'])->subject('Verification code for Swine E-Commerce PH');
            });

            return view('emails.message', $data);
        }
        else return redirect()->route('getRegister_path')->with('message','Verification code invalid!');
    }

}
