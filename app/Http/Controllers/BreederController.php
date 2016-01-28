<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BreederProfileRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use Auth;

class BreederController extends Controller
{

	/**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:breeder');
        $this->middleware('updateProfile:breeder',['except' => ['index','storeProfile']]);
    }

	/**
	 * Show Home Page of breeder
	 * @return View
	 */
    public function index(Request $request)
    {
        $farmAddresses = [];
        if($request->user()->updateProfileNeeded()) return view('user.breeder.createProfile', ['farmAddresses'=>[]]);
    	return view('user.breeder.home');
    }

    /**
     * Show Page for User to complete profile
     * @return View
     */
    public function createProfile()
    {
        return view('user.breeder.createProfile', ['farmAddresses'=>[]]);
    }

    /**
     * Create and store Breeder profile data to database
     * Associate User to Breeder user type as well
     * @param  Request $request
     * @return Redirect
     */
    public function storeProfile(BreederProfileRequest $request)
    {
        $user = Auth::user();
        $breeder = Breeder::create($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'contactPerson_name',
            'contactPerson_mobile']
        ));

        $farmAddressArray = [];

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/7; $i++) {
            $farmAddress = new FarmAddress;
            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $breeder->farmAddresses()->saveMany($farmAddressArray);
        $breeder->users()->save($user);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('breeder.edit')
            ->with('message','Profile updated successfully!');
    }

    /**
     * Show Page for User to update profile
     * @return View
     */
    public function editProfile(Request $request)
    {
        $breeder = $request->user()->userable;
        $farmAddresses = $breeder->farmAddresses;
        return view('user.breeder.editProfile', compact('breeder', 'farmAddresses'));
    }

    /**
     * Update User's profile
     * @return View
     */
    public function updateProfile(Request $request)
    {
        $breeder = Auth::user()->userable;
        $breeder->fill($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'contactPerson_name',
            'contactPerson_mobile']
        ))->save();

        $i = 1;
        foreach ($breeder->farmAddresses as $farmAddress) {

            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            $farmAddress->save();
            $i++;
        }

        return redirect()->route('breeder.edit')
            ->with('message','Profile updated successfully!');
    }

}
