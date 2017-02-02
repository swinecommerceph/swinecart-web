<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notification;
use App\Http\Requests\CustomerProfileRequest;
use App\Http\Requests\CustomerPersonalProfileRequest;
use App\Http\Requests\CustomerFarmProfileRequest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Breed;
use App\Models\TransactionLog;
use Auth;
use DB;

class CustomerController extends Controller
{
    protected $user;

    /**
     * Create new CustomerController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
        $this->middleware('updateProfile:customer',['except' => ['index', 'storeProfile']]);
        $this->middleware(function($request, $next){
            $this->user = Auth::user();

            return $next($request);
        });
    }

    /**
     * Show Home Page of customer
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.customer.createProfile');
        $homeContent = DB::table('home_images')->get();
        return view('user.customer.home',compact('homeContent'));
    }

    /**
     * Show Page for Customer to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        return view('user.customer.createProfile');
    }

    /**
     * Create and store Customer profile data to database and
     * Associate User to Customer user type as well
     *
     * @param  CustomerProfileRequest $request
     * @return Redirect
     */
    public function storeProfile(CustomerProfileRequest $request)
    {
        $user = $this->user;
        $customer = Customer::create($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
        ));
        $farmAddressArray = [];

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/8; $i++) {
            $farmAddress = new FarmAddress;
            $farmAddress->name = $request->input('farmAddress.'.$i.'.name');
            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress$farmAddressArray.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $customer->users()->save($user);
        $customer->farmAddresses()->saveMany($farmAddressArray);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('customer.edit')
            ->with('message','Profile completed.');
    }

    /**
     * Show Page for Customer to update profile
     *
     * @return View
     */
    public function editProfile()
    {
        $customer = $this->user->userable;
        $farmAddresses = $customer->farmAddresses;
        return view('user.customer.editProfile', compact('customer','farmAddresses'));
    }

    /**
     * Update Customer's personal information
     * AJAX
     *
     * @param  CustomerPersonalProfileRequest $request
     * @return JSON / View
     */
    public function updatePersonal(CustomerPersonalProfileRequest $request)
    {
        $customer = Auth::user()->userable;
        $customer->fill($request->only(['address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile']
        ))->save();

        if($request->ajax()) return $customer->toJson();
        else return redirect()->route('customer.edit');
    }

    /**
     * Add Customer's farm information instance
     * AJAX
     *
     * @param  CustomerFarmProfileRequest $request
     * @return JSON / View
     */
    public function addFarm(CustomerFarmProfileRequest $request)
    {
        $customer = $this->user->userable;
        $farmAddressArray = [];

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/8; $i++) {
            $farmAddress = new FarmAddress;
            $farmAddress->name = $request->input('farmAddress.'.$i.'.name');
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

        if($request->ajax()) return collect($farmAddressArray)->toJson();
        else return redirect()->route('customer.edit');
    }

    /**
     * Update Customer's farm information instance
     * AJAX
     *
     * @param  CustomerFarmProfileRequest $request
     * @return JSON / View
     */
    public function updateFarm(CustomerFarmProfileRequest $request)
    {
        $farmAddress = FarmAddress::find($request->id);

        $farmAddress->name = $request->input('farmAddress.1.name');
        $farmAddress->addressLine1 = $request->input('farmAddress.1.addressLine1');
        $farmAddress->addressLine2 = $request->input('farmAddress.1.addressLine2');
        $farmAddress->zipCode = $request->input('farmAddress.1.zipCode');
        $farmAddress->farmType = $request->input('farmAddress.1.farmType');
        $farmAddress->landline = $request->input('farmAddress.1.landline');
        $farmAddress->mobile = $request->input('farmAddress.1.mobile');
        $farmAddress->save();

        if($request->ajax()) return $farmAddress->toJson();
        else return redirect()->route('customer.edit');
    }

    /**
     * Delete Customer's farm information instance
     * AJAX
     *
     * @param  Request $request
     * @return String / View
     */
    public function deleteFarm(Request $request)
    {
        $farmAddress = FarmAddress::find($request->id);
        $farmAddress->delete();
        if($request->ajax()) return "OK";
        else return redirect()->route('customer.edit');
    }

    /**
     * Show Customer's Notification Page
     *
     * @return  View
     */
    public function showNotificationsPage()
    {
        return view('user.customer.notifications');
    }

    /**
     * Get Customer's notification instances
     * AJAX
     *
     * @param  Request $request
     * @return JSON / View
     */
    public function getNotifications(Request $request)
    {
        if($request->ajax()){
            $notificationInstances = [];

            foreach ($this->user->notifications as $notification) {
                $notificationInstance = [];
                $notificationInstance['id'] = $notification->id;
                $notificationInstance['data'] = $notification->data;
                $notificationInstance['read_at'] = $notification->read_at;
                array_push($notificationInstances, $notificationInstance);
            }

            return [collect($notificationInstances)->toJson(), csrf_token()];
        }
    }

    /**
     * Get count of Customer's notification instances
     *
     * @param  Request $request
     * @return Integer
     */
    public function getNotificationsCount(Request $request)
    {
        if($request->ajax()){
            return $this->user->unreadNotifications->count();
        }
    }

    /**
     * Mark the notification instance as read
     *
     * @param  Request $request
     * @return String
     */
    public function seeNotification(Request $request)
    {
        if($request->ajax()){
            $notification = $this->user->notifications()->where('id', $request->notificationId)->get()->first();
            $notification->markAsRead();
            return 'OK';
        }
    }

}
