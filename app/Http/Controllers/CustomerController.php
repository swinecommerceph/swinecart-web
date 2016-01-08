<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
        // $this->middleware('updateProfile:customer',['except' => ['index']]);
    }

    /**
     * Show Home Page of breeder
     * @return View
     */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.customer.createProfile');
        return view('user.customer.home');
    }

    /**
     * Show Page for User to complete profile
     * @return View 
     */
    public function createProfile()
    {
        return view('user.customer.createProfile');
    }
    
    /**
     * Create and store Customer profile data to database
     * Associate User to Customer user type as well
     * @param  Request $request 
     * @return Redirect           
     */
    public function storeProfile(Request $request)
    {
        $user = $request->user();
        $customer = Customer::create($request->all());
        $customer->users()->save($user);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('customer.edit');
    }

    /**
     * Show Page for User to update profile
     * @return View 
     */
    public function editProfile(Request $request)
    {
        $customer = $request->user()->userable;
        return view('user.customer.editProfile', compact('customer'));
    }

    /**
     * Show Page for User to update profile
     * @return View 
     */
    public function updateProfile(Request $request)
    {
        $customer = $request->user()->userable;
        $customer->fill($request->input())->save();
        return redirect()->route('customer.edit');
    }

}
