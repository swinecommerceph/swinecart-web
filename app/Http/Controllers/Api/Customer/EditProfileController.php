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
use Validator;

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

        return response()->json([
            'message' => 'Customer Me successful!',
            'data' => [
                'customer' => $customer
            ]
        ], 200);
    }

    public function getFarms(Request $request)
    {   
        $customer = $this->user->userable;
        $farms = $customer
            ->farmAddresses()
            ->paginate($request->limit)
            ->map(function ($item) {
                $farm = [];

                $farm['id'] = $item->id;
                $farm['name'] = ucfirst($item->name);
                $farm['province'] = $item->province;
                // $farm['addressLine1'] = $item->addressLine1;
                // $farm['addressLine2'] = $item->addressLine2;
                // $farm['zipCode'] = $item->zipCode;
                // $farm['farmType'] = $item->farmType;
                // $farm['landline'] = $item->landline;
                // $farm['mobile'] = $item->mobile;

                return $farm;
            });

        return response()->json([
            'message' => 'Get Farms successful!',
            'data' => [
                'count' => $farms->count(),
                'farms' => $farms,
            ]
        ], 200);
    }

    public function getFarm(Request $request, $farm_id)
    {   
        $customer = $this->user->userable;
        $item = $customer->farmAddresses()->find($farm_id);

        if($item) {
            $farm = [];

            $farm['id'] = $item->id;
            $farm['name'] = ucfirst($item->name);
            $farm['province'] = $item->province;
            $farm['addressLine1'] = $item->addressLine1;
            $farm['addressLine2'] = $item->addressLine2;
            $farm['zipCode'] = $item->zipCode;
            $farm['farmType'] = ucfirst($item->farmType);
            $farm['landline'] = $item->landline;
            $farm['mobile'] = $item->mobile;

            return response()->json([
                'message' => 'Get Farm successful!',
                'data' => [
                    'farm' => $farm
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm does not exist!',
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
            'data' => [
                'customer' => $customer
            ]
        ], 200);
    }

    public function addFarm(Request $request)
    {
        $customer = $this->user->userable;
        $farm = $request->only([
            'name',
            'addressLine1',
            'addressLine2',
            'province',
            'zipCode',
            'landline',
            'farmType',
            'mobile'
        ]);

        $validator = Validator::make($farm, [
            'name' => 'required',
            'addressLine1' => 'required',
            'addressLine2' => 'required',
            'province' => 'required',
            'zipCode' => 'required|digits:4',
            'farmType' => 'required',
            'mobile' => 'required|digits:11|regex:/^09/',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        else {

            $farmAddress = new FarmAddress;
            $farmAddress->name = $farm['name'];
            $farmAddress->addressLine1 = $farm['addressLine1'];
            $farmAddress->addressLine2 = $farm['addressLine2'];
            $farmAddress->province = $farm['province'];
            $farmAddress->zipCode = $farm['zipCode'];
            $farmAddress->farmType = $farm['farmType'];
            $farmAddress->landline = $farm['landline'];
            $farmAddress->mobile = $farm['mobile'];

            $customer->farmAddresses()->save($farmAddress);

            return response()->json([
                'message' => 'Add Farm successful!',
                'data' => [
                    'farm' => $farmAddress
                ]
            ], 200);
            
        }

    }

    public function updateFarm(Request $request, $farm_id)
    {
        $customer = $this->user->userable;
        $farmAddress = $customer->farmAddresses()->find($farm_id);

        $data = $request->only([
            'name',
            'addressLine1',
            'addressLine2',
            'province',
            'zipCode',
            'landline',
            'farmType',
            'mobile'
        ]);

        $validator = Validator::make($data, [
            'name' => 'required',
            'addressLine1' => 'required',
            'addressLine2' => 'required',
            'province' => 'required',
            'zipCode' => 'required|digits:4',
            'farmType' => 'required',
            'mobile' => 'required|digits:11|regex:/^09/',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in Update Farm!',
                'data' => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        if($farmAddress) {
            $farmAddress->name = $data['name'];
            $farmAddress->addressLine1 = $data['addressLine1'];
            $farmAddress->addressLine2 = $data['addressLine2'];
            $farmAddress->province = $data['province'];
            $farmAddress->zipCode = $data['zipCode'];
            $farmAddress->farmType = $data['farmType'];
            $farmAddress->landline = $data['landline'];
            $farmAddress->mobile = $data['mobile'];
            $farmAddress->save();

            return response()->json([
                'message' => 'Update Farm Info successful!',
                'data' => [
                    'farm' => $farmAddress
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }

    public function deleteFarm(Request $request, $farm_id)
    {
        $customer = $this->user->userable;
        $farm = $customer->farmAddresses()->find($farm_id);

        if($farm) {
            $farm->delete();
            return response()->json([
                'message' => 'Delete Farm successful!'
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }


    public function changePassword(Request $request) 
    {
        $data = $request->only([
            'current_password',
            'new_password',
            'new_password_confirmation'
        ]);
        
        $validator = Validator::make($data, [
            'current_password' => 'required|is_current_password',
            'new_password' => 'required|confirmed|min:8'
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            $this->user->password = bcrypt($request->new_password);
            $this->user->save();

            return response()->json([
                'message' => 'Change Password successful!',
            ], 200);
        }
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
