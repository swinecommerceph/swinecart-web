<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;

use Auth;
use DB;

class BreederController extends Controller
{
    protected $user;

	/**
     * Create new BreederController instance
     */
    public function __construct()
    {
        $this->middleware('role:breeder');
        $this->middleware('updateProfile:breeder',['except' => ['index','storeProfile']]);
        $this->middleware(function($request, $next){
            $this->user = Auth::user();

            return $next($request);
        });
    }

	/**
	 * Show Home Page of breeder
	 *
	 * @param  Request $request
	 * @return View
	 */
    public function index(Request $request)
    {
        if($request->user()->updateProfileNeeded()) return view('user.breeder.createProfile');
        $homeContent = DB::table('home_images')->get();
        return view('user.breeder.home',compact('homeContent'));
    }

    /**
     * Show Page for Breeder to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        return view('user.breeder.createProfile');
    }

    /**
     * Create and store Breeder profile data to database and
     * Associate User to Breeder user type as well
     *
     * @param  BreederProfileRequest $request
     * @return Redirect
     */
    public function storeProfile(BreederProfileRequest $request)
    {
        $user = $this->user;
        $breeder = Breeder::create($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'website',
            'produce',
            'contactPerson_name',
            'contactPerson_mobile']
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
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            array_push($farmAddressArray,$farmAddress);
        }

        $breeder->users()->save($user);
        $breeder->farmAddresses()->saveMany($farmAddressArray);
        $user->update_profile = 0;
        $user->save();
        return redirect()->route('breeder.edit')
            ->with('message','Profile completed.');
    }

    /**
     * Show Page for Breeder to update profile
     *
     * @param  Request $request
     * @return View
     */
    public function editProfile(Request $request)
    {
        $breeder = $this->user->userable;
        $farmAddresses = $breeder->farmAddresses;
        return view('user.breeder.editProfile', compact('breeder', 'farmAddresses'));
    }

    /**
     * Update Breeder's personal information
     * AJAX
     *
     * @param  BreederPersonalProfileRequest $request
     * @return JSON / View
     */
    public function updatePersonal(BreederPersonalProfileRequest $request)
    {
        $breeder = Auth::user()->userable;
        $breeder->fill($request->only(['officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'website',
            'produce',
            'contactPerson_name',
            'contactPerson_mobile']
        ))->save();

        if($request->ajax()) return $breeder->toJson();
        else return redirect()->route('breeder.edit');
    }

    /**
     * Add Breeder's farm information instance
     * AJAX
     *
     * @param  BreederFarmProfileRequest $request
     * @return JSON / View
     */
    public function addFarm(BreederFarmProfileRequest $request)
    {
        $breeder = $this->user->userable;
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

        $breeder->farmAddresses()->saveMany($farmAddressArray);

        if($request->ajax()) return collect($farmAddressArray)->toJson();
        else return redirect()->route('breeder.edit');

    }

    /**
     * Update Breeder's farm information instance
     * AJAX
     *
     * @param  BreederFarmProfileRequest $request
     * @return JSON / View
     */
    public function updateFarm(BreederFarmProfileRequest $request)
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
        else return redirect()->route('breeder.edit');
    }

    /**
     * Delete Breeder's farm information instance
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
        else return redirect()->route('breeder.edit');
    }

    /**
     * Show Breeder's Notification Page
     *
     * @return  View
     */
    public function showNotificationsPage()
    {
        return view('user.breeder.notifications');
    }

    /**
     * Get Breeder's notification instances
     * AJAX
     *
     * @param  Request $request
     * @return JSON
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
     * Get count of Breeder's notification instances
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
