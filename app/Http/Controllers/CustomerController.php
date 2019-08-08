<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notification;
use App\Http\Requests\ChangePasswordRequest;
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
        if($request->user()->updateProfileNeeded()){
            $provinces = $this->getProvinces();
            return view('user.customer.createProfile', compact('provinces'));
        }
        if(\App::isDownForMaintenance()) return view('errors.503_home');
        if($request->user()->blocked_at != NULL) return view('errors.user_blocked');
        return view('user.customer.home');
    }

    /**
     * Change password of Customer user
     *
     * @param   ChangePasswordRequest $request
     * @return  String
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        if($request->ajax()){

            $this->user->password = bcrypt($request->new_password);
            $this->user->save();

            return "OK";
        }
    }

    /**
     * Show Page for Customer to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        $provinces = $this->getProvinces();

        return view('user.customer.createProfile', compact('provinces'));
    }

    /**
     * Show Page for Customer to Terms of Agreement
     *
     * @return View
     */
    public function getTermsOfAgreement()
    {
        return view('user.customer.getTermsOfAgreement');
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
        $provinces = $this->getProvinces();
        return view('user.customer.editProfile', compact('customer','farmAddresses', 'provinces'));
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
        $farmAddress->province = $request->input('farmAddress.1.province');
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

    public function viewBreeders(){

        // getting the breeders having the attribute 'farm addresses'
        $breeders = Breeder::with(['farmAddresses'])->get();
        $results = [];

        // inserting the farm address per breeder and returning it
        foreach ($breeders as $breeder) {
            foreach ($breeder->farmAddresses as $fas) {
                $results[] = $fas;
            }
        }

        //$results = Breeder::all();
        
        //dd($results);
        return view('user.customer.viewBreeders', compact('results'));
    }

    public function viewBreedersChange(Request $request){
        if($request->ajax()){
            $breeders = Breeder::whereHas('products', function($q){
                $arr = [];
                $first = true;
                foreach ($_POST as $key => $value) {
                    if($value == 'on' || $value == 'true'){
                        $arr[] = $key;
                    }
                }
                $q->whereIn('type', $arr);
            })->get();

            foreach ($breeders as $breeder) {
                $breeder->name = $breeder->users()->first()->name;
            }
            return $breeders;
        }

    }

    /**
     * Get the sorted provinces all over the Philippines
     *
     * @return  Array
     */
    private function getProvinces()
    {
        return collect([
            // Negros Island Rregion
            'Negros Occidental' => 'Negros Occidental',
            'Negros Oriental' => 'Negros Oriental',
            // Cordillera Administrative Region
            'Mountain Province' => 'Mountain Province',
            'Ifugao' => 'Ifugao',
            'Benguet' => 'Benguet',
            'Abra' => 'Abra',
            'Apayao' => 'Apayao',
            'Kalinga' => 'Kalinga',
            // Region I
            'La Union' => 'La Union',
            'Ilocos Norte' => 'Ilocos Norte',
            'Ilocos Sur' => 'Ilocos Sur',
            'Pangasinan' => 'Pangasinan',
            // Region II
            'Nueva Vizcaya' => 'Nueva Vizcaya',
            'Cagayan' => 'Cagayan',
            'Isabela' => 'Isabela',
            'Quirino' => 'Quirino',
            'Batanes' => 'Batanes',
            // Region III
            'Bataan' => 'Bataan',
            'Zambales' => 'Zambales',
            'Tarlac' => 'Tarlac',
            'Pampanga' => 'Pampanga',
            'Bulacan' => 'Bulacan',
            'Nueva Ecija' => 'Nueva Ecija',
            'Aurora' => 'Aurora',
            // Region IV-A
            'Rizal' => 'Rizal',
            'Cavite' => 'Cavite',
            'Laguna' => 'Laguna',
            'Batangas' => 'Batangas',
            'Quezon' => 'Quezon',
            // Region IV-B
            'Occidental Mindoro' => 'Occidental Mindoro',
            'Oriental Mindoro' => 'Oriental Mindoro',
            'Romblon' => 'Romblon',
            'Palawan' => 'Palawan',
            'Marinduque' => 'Marinduque',
            // Region V
            'Catanduanes' => 'Catanduanes',
            'Camarines Norte' => 'Camarines Norte',
            'Sorsogon' => 'Sorsogon',
            'Albay' => 'Albay',
            'Masbate' => 'Masbate',
            'Camarines Sur' => 'Camarines Sur',
            // Region VI
            'Capiz' => 'Capiz',
            'Aklan' => 'Aklan',
            'Antique' => 'Antique',
            'Iloilo' => 'Iloilo',
            'Guimaras' => 'Guimaras',
            // Region VII
            'Cebu' => 'Cebu',
            'Bohol' => 'Bohol',
            'Siquijor' => 'Siquijor',
            // Region VIII
            'Southern Leyte' => 'Southern Leyte',
            'Eastern Samar' => 'Eastern Samar',
            'Northern Samar' => 'Northern Samar',
            'Western Samar' => 'Western Samar',
            'Leyte' => 'Leyte',
            'Biliran' => 'Biliran',
            // Region IX
            'Zamboanga Sibugay' => 'Zamboanga Sibugay',
            'Zamboanga del Norte' => 'Zamboanga del Norte',
            'Zamboanga del Sur' => 'Zamboanga del Sur',
            // Region X
            'Misamis Occidental' => 'Misamis Occidental',
            'Bukidnon' => 'Bukidnon',
            'Lanao del Norte' => 'Lanao del Norte',
            'Misamis Oriental' => 'Misamis Oriental',
            'Camiguin' => 'Camiguin',
            // Region XI
            'Davao Oriental' => 'Davao Oriental',
            'Compostela Valley' => 'Compostela Valley',
            'Davao del Sur' => 'Davao del Sur',
            'Davao Occidental' => 'Davao Occidental',
            'Davao del Norte' => 'Davao del Norte',
            // Region XII
            'South Cotabato' => 'South Cotabato',
            'Sultan Kudarat' => 'Sultan Kudarat',
            'North Cotabato' => 'North Cotabato',
            'Sarangani' => 'Sarangani',
            // Region XIII
            'Agusan del Norte' => 'Agusan del Norte',
            'Agusan del Sur' => 'Agusan del Sur',
            'Surigao del Sur' => 'Surigao del Sur',
            'Surigao del Norte' => 'Surigao del Norte',
            'Dinagat Islands' => 'Dinagat Islands',
            // ARMM
            'Tawi-tawi' => 'Tawi-tawi',
            'Basilan' => 'Basilan',
            'Sulu' => 'Sulu',
            'Maguindanao' => 'Maguindanao',
            'Lanao del Sur' => 'Lanao del Sur',
            // NCR
            'Caloocan City' => 'Caloocan City',
            'Las Piñas' => 'Las Piñas',
            'Makati' => 'Makati',
            'Malabon' => 'Malabon',
            'Mandaluyong' => 'Mandaluyong',
            'Manila' => 'Manila',
            'Marikina' => 'Marikina',
            'Muntinlupa' => 'Muntinlupa',
            'Navotas' => 'Navotas',
            'Parañaque' => 'Parañaque',
            'Pasay City' => 'Pasay City',
            'Pasig' => 'Pasig',
            'Pateros' => 'Pateros',
            'Quezon City' => 'Quezon City',
            'San Juan' => 'San Juan',
            'Taguig' => 'Taguig',
            'Valenzuela' => 'Valenzuela'
        ])->sort();
    }

}
