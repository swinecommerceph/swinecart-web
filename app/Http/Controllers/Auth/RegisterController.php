<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
        $this->middleware('guest');
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
        $email = $request->input('email');

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

        Mail::send('vendor.notifications.email', $data, function ($message) use($data){
            $message->to($data['email'])->subject('Verification code for SwineCart');
        });

        return view('emails.message', $data);

    }

    /**
     * Show Page for Customer to Privacy Policy
     *
     * @return View
     */
    public function getPrivacyPolicy()
    {
        return view('user.customer.getPrivacyPolicy');
    }

    

}
