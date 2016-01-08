<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect user to a specified controller according to role
     * @return  View 
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if($user->hasRole('breeder')) return redirect()->action('BreederController@index');
        else if($user->hasRole('customer')) return redirect()->action('CustomerController@index');
        else redirect()->route('logout_path');

    }
}
