<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Specify path for retailer user home page
     */
    protected $breederPath = '/home/breeder';

    /**
     * Specify path for consumer user home page
     */
    protected $customerPath = '/home/customer';

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
        if($user->hasRole('breeder')) return redirect($this->breederPath);
        else if($user->hasRole('customer')) return redirect($this->customerPath);

    }
}
