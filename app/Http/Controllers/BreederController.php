<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;

class BreederController extends Controller
{

	/**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:breeder');
        // $this->middleware('updateProfile:breeder',['except' => ['index']]);
    }

	/**
	 * Show Home Page of breeder
	 * @return View
	 */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.breeder.createProfile');
    	return view('user.breeder.home');
    }

    /**
     * Show Page for User to complete profile
     * @return View
     */
    public function createProfile()
    {
        return view('user.breeder.createProfile');
    }

    /**
     * Create and store Breeder profile data to database
     * Associate User to Breeder user type as well
     * @param  Request $request
     * @return Redirect
     */
    public function storeProfile(Request $request)
    {
        // Validate first the input fields
        $this->validate($request, [
            'officeAddress_addressLine1' => 'required',
            'officeAddress_addressLine2' => 'required',
            'officeAddress_province' => 'required',
            'officeAddress_zipCode' => 'required',
            'office_mobile' => 'required',
            'farmAddress_addressLine1' => 'required',
            'farmAddress_addressLine2' => 'required',
            'farmAddress_province' => 'required',
            'farmAddress_zipCode' => 'required',
            'farm_type' => 'required',
            'farm_mobile' => 'required',
            'contactPerson_name' => 'required',
            'contactPerson_mobile' => 'required',
        ]);

        $user = $request->user();
        $breeder = Breeder::create($request->all());
        $breeder->users()->save($user);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('breeder.edit');
    }

    /**
     * Show Page for User to update profile
     * @return View
     */
    public function editProfile(Request $request)
    {
        $breeder = $request->user()->userable;
        return view('user.breeder.editProfile', compact('breeder'));
    }

    /**
     * Update User's profile
     * @return View
     */
    public function updateProfile(Request $request)
    {
        $breeder = $request->user()->userable;
        $breeder->fill($request->input())->save();
        return redirect()->route('breeder.edit');
    }

}
