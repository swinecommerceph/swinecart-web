<?php

namespace App\Http\Controllers\Api\Customer;

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
use JWTAuth;

class EditProfileController extends Controller
{
    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:customer');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function me(Request $request)
    {
        $customer = $this->user->userable;

        if($customer) {
            return response()->json([
                'message' => 'Customer Me successful!',
                'data' => $customer
            ], 200);
        }
        else return response()->json([
            'error' => 'Customer does not exist!',
        ], 404);
    }

    public function getFarmAddresses(Request $request)
    {
        $farmAddresses = $customer->farmAddresses;

        if($farmAddresses) {
            return response()->json([
                'message' => 'Get Farm Addresses successful!',
                'data' => $farmAddresses
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Addresses does not exist!',
        ], 404);

    }

    public function updatePersonal(CustomerPersonalProfileRequest $request)
    {
        $customer = $this->user->userable;

        $customer->fill($request->only([
            'address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile'
        ]))->save();

        return response()->json([
            'message' => 'Update Personal successful!',
            'data' => $customer
        ], 200);
    }

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
            array_push($farmAddressArray, $farmAddress);
        }

        $customer->farmAddresses()->saveMany($farmAddressArray);

        return response()->json([
            'message' => 'Update Personal successful!',
            'data' => $farmAddressArray
        ], 200);


    }

    public function updateFarm(CustomerFarmProfileRequest $request, $farm_id)
    {
        $customer = $this->user->userable;
        $farmAddress = $customer->farmAddresses()->find($farm_id);

        if($farmAddress) {
            $farmAddress->name = $request['name'];
            $farmAddress->addressLine1 = $request['addressLine1'];
            $farmAddress->addressLine2 = $request['addressLine2'];
            $farmAddress->province = $request['province'];
            $farmAddress->zipCode = $request['zipCode'];
            $farmAddress->farmType = $request['farmType'];
            $farmAddress->landline = $request['landline'];
            $farmAddress->mobile = $request['mobile'];
            $farmAddress->save();

            return response()->json([
                'message' => 'Update Farm Info successful!',
                'data' => $farmAddress
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }

    public function deleteFarm(Request $request, $farm_id)
    {
        $customer = $this->user->userable;
        $farmAddress = $customer->farmAddresses()->find($farm_id);

        if($farmAddress) {
            $farmAddress->delete();
            return response()->json([
                'message' => 'Delete Farm successful!'
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }


    public function changePassword(ChangePasswordRequest $request) 
    {
        $this->user->password = bcrypt($request->new_password);
        $this->user->save();

        return response()->json([
            'message' => 'Change Password successful!',
        ], 200);

    }

    public function getBreeders(Request $request)
    {
        $breeders = Breeder::all();

        return response()->json([
            'message' => 'Get Breeders successful!',
            'data' => $breeders
        ], 200);

    }

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
