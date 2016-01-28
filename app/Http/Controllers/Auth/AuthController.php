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
        $this->middleware('guest', ['except' => 'getLogout']);
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
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
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
}
