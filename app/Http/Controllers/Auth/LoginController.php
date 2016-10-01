<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use SocialAuth;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;
use Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout','verifyCode']]);
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
     * Authenticate user if email and verification
     * code matches in the database
     *
     * @param   String  $email
     * @param   String  $verCode
     * @return  View or Redirect
     */
    public function verifyCode($email, $verCode)
    {
        if(Auth::check()) Auth::logout();

        $verUser = User::where('email', $email)->first();
        if($verUser->verification_code == $verCode){
            Auth::login($verUser);
            $verUser->email_verified = 1;
            $verUser->save();
            return redirect($this->redirectPath());
        }
        else return redirect('register')->with('message','Verification code invalid!');
    }

    /**
     * Resend verifcation code to user
     *
     * @param   String          $email
     * @param   String          $verCode
     * @return  View/Redirect
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
