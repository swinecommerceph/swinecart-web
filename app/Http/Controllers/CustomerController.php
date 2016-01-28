<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerProfileRequest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FarmAddress;
use Auth;

class CustomerController extends Controller
{
    /**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
        $this->middleware('updateProfile:customer',['except' => ['index', 'storeProfile']]);
    }

    /**
     * Show Home Page of customer
     * @return View
     */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.customer.createProfile', ['farmAddresses'=>[]]);
        return view('user.customer.home');
    }

    /**
     * Show Page for User to complete profile
     * @return View
     */
    public function createProfile()
    {
        return view('user.customer.createProfile', ['farmAddresses'=>[]]);
    }

    /**
     * Create and store Customer profile data to database
     * Associate User to Customer user type as well
     * @param  Request $request
     * @return Redirect
     */
    public function storeProfile(CustomerProfileRequest $request)
    {
        $user = Auth::user();
        $customer = Customer::create($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
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

        $customer->farmAddresses()->saveMany($farmAddressArray);
        $customer->users()->save($user);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('customer.edit')
            ->with('message','Profile completed.');
    }

    /**
     * Show Page for User to update profile
     * @return View
     */
    public function editProfile(Request $request)
    {
        $customer = Auth::user()->userable;
        $farmAddresses = $customer->farmAddresses;
        return view('user.customer.editProfile', compact('customer','farmAddresses'));
    }

    /**
     * Update User's profile
     * @return View
     */
    public function updateProfile(CustomerProfileRequest $request)
    {

        $customer = Auth::user()->userable;
        $customer->fill($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
        ))->save();

        $i = 1;
        foreach ($customer->farmAddresses as $farmAddress) {

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

        return redirect()->route('customer.edit')
            ->with('message','Profile updated successfully!');
    }


}
