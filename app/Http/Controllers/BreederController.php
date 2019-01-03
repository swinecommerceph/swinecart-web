<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\Customer;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Sessions;
use App\Models\User;

use Auth;
use Mail;
use App\Models\Image;

use Storage;

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
        if($request->user()->updateProfileNeeded()){
            $breeder = $this->user->userable;
            $farmAddresses = $breeder->farmAddresses;
            $provinces = $this->getProvinces();
            return view('user.breeder.createProfile', compact('breeder', 'farmAddresses', 'provinces'));
        }
        if(\App::isDownForMaintenance()) return view('errors.503_home');
        if($request->user()->blocked_at != NULL) return view('errors.user_blocked');
        return view('user.breeder.dashboard');
    }

    /**
     * Change password of Breeder user
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
     * Show Page for Breeder to complete profile
     *
     * @return View
     */
    public function createProfile()
    {
        $provinces = $this->getProvinces();

        return view('user.breeder.createProfile', compact('provinces'));
    }

    /**
     * Update initial Breeder profile with new data to database and
     * Associate User to Breeder user type as well
     *
     * @param  BreederProfileRequest $request
     * @return Redirect
     */
    public function storeProfile(BreederProfileRequest $request)
    {
        $user = $this->user;
        $breeder = $user->userable;

        $breeder->officeAddress_addressLine1 = $request->input('officeAddress_addressLine1');
        $breeder->officeAddress_addressLine2 = $request->input('officeAddress_addressLine2');
        $breeder->officeAddress_province = $request->input('officeAddress_province');
        $breeder->officeAddress_zipCode = $request->input('officeAddress_zipCode');
        $breeder->office_landline = $request->input('office_landline');
        $breeder->office_mobile = $request->input('office_mobile');
        $breeder->website = $request->input('website');
        $breeder->produce = $request->input('produce');
        $breeder->contactPerson_name = $request->input('contactPerson_name');
        $breeder->contactPerson_mobile = $request->input('contactPerson_mobile');
        $breeder->save();

        for ($i = 1; $i <= count($request->input('farmAddress.*.*'))/8; $i++) {
            $farmAddress = FarmAddress::find($request->input('farmAddress.'.$i.'.id'));
            $farmAddress->addressLine1 = $request->input('farmAddress.'.$i.'.addressLine1');
            $farmAddress->addressLine2 = $request->input('farmAddress.'.$i.'.addressLine2');
            $farmAddress->province = $request->input('farmAddress.'.$i.'.province');
            $farmAddress->zipCode = $request->input('farmAddress.'.$i.'.zipCode');
            $farmAddress->farmType = $request->input('farmAddress.'.$i.'.farmType');
            $farmAddress->landline = $request->input('farmAddress.'.$i.'.landline');
            $farmAddress->mobile = $request->input('farmAddress.'.$i.'.mobile');
            $farmAddress->save();
        }

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
        $breeder->logoImage = ($breeder->logo_img_id) ? '/images/breeder/'.Image::find($breeder->logo_img_id)->name : '/images/default_logo.png' ;
        $farmAddresses = $breeder->farmAddresses;
        $provinces = $this->getProvinces();
        return view('user.breeder.editProfile', compact('breeder', 'farmAddresses', 'provinces'));
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
        $breeder = $this->user->userable;
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
     * Update Breeder's farm information instance
     * AJAX
     *
     * @param  BreederFarmProfileRequest $request
     * @return JSON / View
     */
    public function updateFarm(BreederFarmProfileRequest $request)
    {
        $farmAddress = FarmAddress::find($request->id);

        $farmAddress->addressLine1 = $request->input('farmAddress.1.addressLine1');
        $farmAddress->addressLine2 = $request->input('farmAddress.1.addressLine2');
        $farmAddress->province = $request->input('farmAddress.1.province');
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

    /**
     * View Customers of Breeder
     *
     * @return View
     */
    public function viewCustomers(){
        $breeder = $this->user->userable;

        $uniqueCustomerTransactions = $breeder->transactionLogs->unique('customer_id')->values()->all();

        $customers = [];
        if ($uniqueCustomerTransactions != NULL) {
          foreach ($uniqueCustomerTransactions as $transaction) {
            $uniqueCustomerTransaction = $transaction->customer;
            $customers[] = $uniqueCustomerTransaction;
          }
        }

        return view('user.breeder.viewCustomers', compact('customers'));
    }

    /**
     * Upload logo of Breeder
     *
     * @param   Request $request
     * @return  JSON
     */
    public function uploadLogo(Request $request)
    {
        // Check request if it contains logo file input
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $imageDetails = [];

            // Check if the file had a problem in uploading
            if($file->isValid()){
                $fileExtension = $file->getClientOriginalExtension();

                if($this->isImage($fileExtension)) $imageInfo = $this->createImageInfo($fileExtension);
                else return response()->json('Invalid file extension', 500);

                Storage::disk('public')->put($imageInfo['directoryPath'].$imageInfo['filename'], file_get_contents($file));

                if($file){
                    $breeder = $this->user->userable;

                    // Make Image instance
                    $image = $imageInfo['type'];
                    $image->name = $imageInfo['filename'];

                    $breeder->images()->save($image);

                    $imageDetails['id'] = $image->id;
                    $imageDetails['name'] = $image->name;
                }
                else return response()->json('Move file failed', 500);
            }
            else return response()->json('Upload failed', 500);

            return response()->json(collect($imageDetails)->toJson(), 200);
        }
        else return response()->json('No files detected', 500);
    }

    /**
     * Set logo image of Breeder
     *
     * @param   Request  $request
     * @return  String
     */
    public function setLogo(Request $request)
    {
        if($request->ajax()){
            $breeder = $this->user->userable;
            $breeder->logo_img_id = $request->imageId;
            $breeder->save();

            $redundantImages = $breeder->images->where('id', '<>', $breeder->logo_img_id);

            if(!$redundantImages->isEmpty()){
                $redundantImages->each(function($item, $key){
                    $fullFilePath = '/images/breeder/' . $item->name;

                    // Check if file exists in the storage
                    if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);

                    $item->delete();
                });
            }

            return '/images/breeder/'.Image::find($request->imageId)->name;
        }

    }

    /**
     * Delete logo of Breeder
     *
     * @param   Request $request
     * @return  JSON
     */
    public function deleteLogo(Request $request)
    {
        if($request->ajax()){
            $image = Image::find($request->imageId);
            $fullFilePath = '/images/breeder/' . $image->name;

            // Check if file exists in the storage
            if(Storage::disk('public')->exists($fullFilePath)) Storage::disk('public')->delete($fullFilePath);

            $image->delete();

            return response()->json('Logo deleted', 200);
        }
    }

    /**
     * Get appropriate image info depending on extension
     *
     * @param  String           $extension
     * @return AssociativeArray $imageInfo
     */
    private function createImageInfo($extension)
    {
        $imageInfo = [];

        $imageInfo['filename'] = snake_case($this->user->name) . '_logo_' . md5(time()) . '_' . $extension;
        $imageInfo['directoryPath'] = '/images/breeder/';
        $imageInfo['type'] = new Image;

        return $imageInfo;
    }

    /**
     * Check if media is Image depending on extension
     *
     * @param  String   $extension
     * @return Boolean
     */
    private function isImage($extension)
    {
        return ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') ? true : false;
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
            'Lanao del Sur' => 'Lanao del Sur'
        ])->sort();
    }

}
