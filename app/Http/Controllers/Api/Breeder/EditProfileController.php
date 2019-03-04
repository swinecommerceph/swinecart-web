<?php

namespace App\Http\Controllers\Api\Breeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;


use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\BreederPersonalProfileRequest;

use App\Models\Image;
use App\Models\User;
use App\Models\Breeder;
use App\Models\Customer;
use App\Models\FarmAddress;
use App\Models\Product;

use Response;
use Validator;
use JWTAuth;
use Mail;
use Storage;

class EditProfileController extends Controller
{

    public function __construct() 
    {
        $this->middleware('jwt:auth');
        $this->middleware('jwt.role:breeder');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    private function getBreederFarms($breeder) {
        $farms = FarmAddress::where('addressable_id', $breeder->id)
            ->where('accreditation_status', 'active');
        return $farms;
    }

    public function getProfile(Request $request) 
    {
        $breeder = $this->user->userable;
        $breeder->logoImage = ($breeder->logo_img_id) 
            ? '/images/breeder/'.Image::find($breeder->logo_img_id)->name 
            : '/images/default_logo.png' ;
        
        $profile = [];

        $profile['id'] = $breeder->id;
        $profile['officeAddress_addressLine1'] = $breeder->officeAddress_addressLine1;
        $profile['officeAddress_addressLine2'] = $breeder->officeAddress_addressLine2;
        $profile['officeAddress_province'] = $breeder->officeAddress_province;
        $profile['officeAddress_zipCode'] = $breeder->officeAddress_zipCode;
        $profile['office_landline'] = $breeder->office_landline;
        $profile['office_mobile'] = $breeder->office_mobile;
        $profile['website'] = $breeder->website;
        $profile['produce'] = $breeder->produce;
        $profile['contactPerson_name'] = $breeder->contactPerson_name;
        $profile['contactPerson_mobile'] = $breeder->contactPerson_mobile;
        $profile['img_path'] = $breeder->logoImage;

        return response()->json([
            'message' => 'Get Profile successful!',
            'data' => [
                'profile' => $profile,
            ]
        ], 200);
    }

    public function getFarms(Request $request)
    {
        $breeder = $this->user->userable;

        $results = $this->getBreederFarms($breeder)->paginate($request->perpage);
        $farms = $results->items();
        $count = $results->count();

        $farms = array_map(function ($item) {
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
        }, $farms);

        return response()->json([
            'message' => 'Get Farms successful!',
            'data' => [
                'count' => $count,
                'farms' => $farms,
            ]
        ], 200);
    }

    public function getFarm(Request $request, $farm_id) 
    {
        $breeder = $this->user->userable;
        $farms = $this->getBreederFarms($breeder);

        $item = $farms->find($farm_id);

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
            $farm['accreditation_no'] = $item->accreditation_no;
            $farm['accreditation_date'] = $item->accreditation_date;
            $farm['accreditation_expiry'] = $item->accreditation_expiry;

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

    public function updateFarm(Request $request, $farm_id) 
    {
        $breeder = $this->user->userable;
        $farms = $this->getBreederFarms($breeder);
        $item = $farms->find($farm_id);

        if($item) {

            $validator = Validator::make($request->all(), [
                'addressLine1' => 'required',
                'addressLine2' => 'required',
                'province' => 'required',
                'zipCode' => 'required|digits:4',
                'farmType' => 'required',
                'mobile' => 'required|digits:11|regex:/^09/',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 422);
            }
            else {
                $item->addressLine1 = $request->addressLine1;
                $item->addressLine2 = $request->addressLine2;
                $item->province = $request->province;
                $item->zipCode = $request->zipCode;
                $item->farmType = $request->farmType;
                $item->landline = $request->landline;
                $item->mobile = $request->mobile;
                $item->save();

                $farm = [];

                $farm['addressLine1'] = $item->addressLine1;
                $farm['addressLine2'] = $item->addressLine2;
                $farm['province'] = $item->province;
                $farm['zipCode'] = $item->zipCode;
                $farm['farmType'] = $item->farmType;
                $farm['landline'] = $item->landline;
                $farm['mobile'] = $item->mobile;

                return response()->json([
                    'message' => 'Update Farm Info successful!',
                    'data' => [
                        'farm' => $farm
                    ]
                ], 200);
            }
        }
        else return response()->json([
            'error' => 'Farm does not exist!',
        ], 404);

    }

    public function deleteFarm(Request $request, $farm_id) 
    {
        $breeder = $this->user->userable;
        $farms = $this->getBreederFarms($breeder);
        $farm = $farms->find($farm_id);

        if($farm) {
            $farm->delete();
            return response()->json([
                'message' => 'Delete Farm successful!',
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm does not exist!',
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

    public function updatePersonal(BreederPersonalProfileRequest $request) 
    {
        $breeder = $this->user->userable;

        $data = $request->only([
            'officeAddress_addressLine1',
            'officeAddress_addressLine2',
            'officeAddress_province',
            'officeAddress_zipCode',
            'office_landline',
            'office_mobile',
            'website',
            'produce',
            'contactPerson_name',
            'contactPerson_mobile'
        ]);

        $validator = Validator::make($data, [
            'officeAddress_addressLine1' => 'required',
            'officeAddress_addressLine2' => 'required',
            'officeAddress_province' => 'required',
            'officeAddress_zipCode' => 'required|digits:4',
            'office_mobile' => 'required|digits:11|regex:/^09/',
            'contactPerson_name' => 'required',
            'contactPerson_mobile' => 'required|digits:11|regex:/^09/',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Update Personal Info successful!',
                'data' => [
                    'farm' => $validator->errors()
                ]
            ], 422);
        }
        else {
            $breeder->fill($data)->save();
            return response()->json([
                'message' => 'Update Personal successful!',
                'data' => [
                    'profile' => $breeder
                ]
            ], 200);
        }
    }

    public function uploadLogo(Request $request) 
    {
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $imageDetails = [];

            if($file->isValid()){
                $fileExtension = $file->getClientOriginalExtension();

                if($this->isImage($fileExtension)) {
                    $imageInfo = $this->createImageInfo($fileExtension);
                }
                else return response()->json([
                    'message' => 'Invalid file extension'
                ], 500);

                Storage::disk('public')->put(
                    $imageInfo['directoryPath'].$imageInfo['filename'],
                    file_get_contents($file)
                );

                if($file){
                    $breeder = $this->user->userable;

                    $image = $imageInfo['type'];
                    $image->name = $imageInfo['filename'];

                    $breeder->images()->save($image);

                    $imageDetails['id'] = $image->id;
                    $imageDetails['name'] = $image->name;
                }
                else return response()->json([
                    'message' => 'Move file failed'
                ], 500);
            }
            else return response()->json([
                'message' => 'Upload failed'
            ], 500);

            return response()->json(collect($imageDetails)->toJson(), 200);
        }
        else return response()->json([
            'message' => 'No files detected'
        ], 500);
    }

    public function deleteLogo(Request $request) 
    {
        $image = Image::find($request->imageId);
        $fullFilePath = '/images/breeder/' . $image->name;

        if(Storage::disk('public')->exists($fullFilePath)) {
            Storage::disk('public')->delete($fullFilePath);
        }

        $image->delete();

        return response()->json([
            'message' => 'Logo deleted'
        ], 200);
    }

    public function setLogo(Request $request) 
    {
        $breeder = $this->user->userable;
        $breeder->logo_img_id = $request->imageId;
        $breeder->save();

        $redundantImages = $breeder->images->where(
            'id', '<>', $breeder->logo_img_id
        );

        if(!$redundantImages->isEmpty()){
            $redundantImages->each(function($item, $key){
                $fullFilePath = '/images/breeder/' . $item->name;

                // Check if file exists in the storage
                if(Storage::disk('public')->exists($fullFilePath)) {
                    Storage::disk('public')->delete($fullFilePath);
                }

                $item->delete();
            });
        }

        return '/images/breeder/'.Image::find($request->imageId)->name;
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
