<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserLog;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Auth;
use DB;
use Mail;
use SocialAuth;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;
use Validator;

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

    use AuthenticatesUsers{
        logout as performLogout;
    }

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
     *
     * @param   String              $provider
     * @return  Redirect/Response
     */
    public function redirectToProvider($provider)
    {
        if($provider == 'google' || $provider == 'facebook') return SocialAuth::authorize($provider);
        else return response('Not Found', 404);
    }

    /**
     * Handle the information from the provider and authenticate User
     *
     * @param   Request             $request
     * @param   String              $provider
     * @return  Redirect/Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {

        try {
            if($provider == 'google' || $provider == 'facebook'){

                SocialAuth::login($provider, function($user, $details) {

                    // Set-up account for verification checking on existing accounts.
                    // Note: This is a fix for SocialAuth's current
                    // lack of functionality for checking
                    // current users in the
                    // users table
                    $userAlreadyExists = \App\Models\User::where('email', $details->email)->first();

                    if(!$userAlreadyExists){
                        // Store new user to database
                        $user->email = $details->email;
                        $user->name = $details->full_name;
                        $user->email_verified = 1;
                        $user->save();

                        // Assign customer role
                        if(!$user->hasRole('customer')) $user->assignRole('customer');
                    }
                    else{
                        // Adding '<<temp>>' suffix means that the email
                        // is already used in the system
                        $user->email = $details->email . '<<temp>>';
                        $user->save();
                    }
                });

            }
            else return response('Not Found', 404);
        } catch (ApplicationRejectedException $e) {
            // User rejected application
            return redirect()->route('register');
        } catch (InvalidAuthorizationCodeException $e) {
            // Authorization was attempted with invalid
            // code,likely forgery attempt
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If email has '<<temp>>' suffix, then it means the authenticated user
        // is already existing so we must delete it from
        // the users and oauth_identities table
        // and return an error
        if(strstr($user->email, '<<temp>>')){
            $oauthEmail = str_replace('<<temp>>', "", $user->email);
            DB::table('oauth_identities')->where('user_id', $user->id)->delete();
            $user->forceDelete();

            return redirect()->back()->with('customErrors', ['User with email address ' . $oauthEmail . ' already exists.']);
        }
        else{
            // Add user access log.
            $userLog = new UserLog;
            $userLog->user_id = $user->id;
            $userLog->user_type = $user->roles()->first()->title;
            $userLog->ip_address = $request->ip();
            $userLog->activity = 'login';
            $userLog->created_at = Carbon::now();
            $userLog->save();
        }

        return redirect()->route('home_path');
    }

    /**
     * Authenticate user if email and verification
     * code matches in the database
     *
     * @param   Request         $request
     * @param   String          $email
     * @param   String          $verCode
     * @return  View/Redirect
     */
    public function verifyCode(Request $request, $email, $verCode)
    {
        if(Auth::check()) Auth::logout();

        $verUser = User::where('email', $email)->first();
        if($verUser->verification_code == $verCode){
            Auth::login($verUser);

            $verUser->email_verified = 1;
            $verUser->save();

            // Add user access log
            $userLog = new UserLog;
            $userLog->user_id = $verUser->id;
            $userLog->user_type = $this->guard()->user()->roles()->first()->title;
            $userLog->ip_address = $request->ip();
            $userLog->activity = 'login';
            $userLog->created_at = Carbon::now();
            $userLog->save();

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
        else return redirect()->route('register')->with('message','Verification code invalid!');
    }

    /**
     * Override Laravel's login authenticate to include
     * adding a User log activity
     *
     * @param   Request     $request
     * @param   User        $user
     * @return  void
     */
    protected function authenticated(Request $request, $user)
    {
        // Add user access log
        $userLog = new UserLog;
        $userLog->user_id = Auth::user()->id;
        $userLog->user_type = $this->guard()->user()->roles()->first()->title;
        $userLog->ip_address = $request->ip();
        $userLog->activity = 'login';
        $userLog->created_at = Carbon::now();
        $userLog->save();

        return;
    }

    /**
     * Override Laravel's logout to include
     * adding a User log activity
     *
     * @param   Request $request
     * @return  Redirect
     */
    public function logout(Request $request)
    {
        // Add user access log
        $userLog = new UserLog;
        $userLog->user_id = $this->guard()->id();
        $userLog->user_type = $this->guard()->user()->roles()->first()->title;
        $userLog->ip_address = $request->ip();
        $userLog->activity = 'logout';
        $userLog->created_at = Carbon::now();
        $userLog->save();

        $this->performLogout($request);
        return redirect('/');
    }

}
