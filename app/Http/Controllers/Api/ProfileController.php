<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Controller;

use App\Models\Image;
use JWTAuth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            $this->accountType = explode('\\', $this->user->userable_type)[2];
            return $next($request);
        });
    }

    // "address_addressLine1": "sunt",
    // "address_addressLine2": "5894 Elisa Via Suite 970\nWest Nadia, MI 66084",
    // "address_province": "Tarlac",
    // "address_zipCode": "4446",
    // "landline": "24984577",
    // "mobile": "09776749666",
    // "status_instance": "active"

    public function getProfile(Request $request)
    {
        $user = $this->user->userable;

        $profile = [];

        if ($this->accountType === 'Breeder') {

            $logo = Image::find($user->logo_img_id);

            $logoUrl = $logo
                ? '/images/breeder/'.$logo->name
                : '/images/default_logo.png';
            $logoUrl = URL::to('/').$logoUrl;

            $profile['addressLine1'] = $user->officeAddress_addressLine1;
            $profile['addressLine2'] = $user->officeAddress_addressLine2;
            $profile['province'] = $user->officeAddress_province;
            $profile['zipCode'] = $user->officeAddress_zipCode;
            $profile['landline'] = $user->office_landline;
            $profile['mobile'] = $user->office_mobile;

            $profile['website'] = $user->website;
            $profile['produce'] = $user->produce;

            $profile['contactPerson'] = [
                'name' => $user->contactPerson_name,
                'mobile' => $user->contactPerson_mobile,
            ];

            $profile['logoUrl'] = $logoUrl;

        }
        else {
            $profile['addressLine1'] = $user->address_addressLine1;
            $profile['addressLine2'] = $user->address_addressLine2;
            $profile['province'] = $user->address_province;
            $profile['zipCode'] = $user->address_zipCode;
            $profile['landline'] = $user->landline;
            $profile['mobile'] = $user->mobile;
        }

        return response()->json([
            'data' => [
                'profile' => $profile,
            ]
        ], 200);
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

    public function editProfile(Request $request)
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
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            $breeder->fill($data)->save();

            return response()->json([
                'message' => 'Update Personal successful!'
            ], 200);
        }
    }

    // public function updatePersonal(CustomerPersonalProfileRequest $request)
    // {
    //     $customer = $this->user->userable;
    //     $data = $request->only([
    //         'address_addressLine1',
    //         'address_addressLine2',
    //         'address_province',
    //         'address_zipCode',
    //         'landline',
    //         'mobile'
    //     ]);

    //     $validator = Validator::make($data, [
    //         'address_addressLine1' => 'required',
    //         'address_addressLine2' => 'required',
    //         'address_province' => 'required',
    //         'address_zipCode' => 'required|digits:4',
    //         'mobile' => 'required|digits:11|regex:/^09/',
    //     ]);

    //     if($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors(),
    //         ], 422);
    //     }
    //     else {
    //         $customer->fill($data)->save();

    //         return response()->json([
    //             'message' => 'Update Personal successful!'
    //         ], 200);
    //     }
    // }

    // public function uploadLogo(Request $request) 
    // {
    //     if($request->hasFile('logo')){
    //         $file = $request->file('logo');
    //         $imageDetails = [];

    //         if($file->isValid()){
    //             $fileExtension = $file->getClientOriginalExtension();

    //             if($this->isImage($fileExtension)) {
    //                 $imageInfo = $this->createImageInfo($fileExtension);
    //             }
    //             else return response()->json([
    //                 'message' => 'Invalid file extension'
    //             ], 500);

    //             Storage::disk('public')->put(
    //                 $imageInfo['directoryPath'].$imageInfo['filename'],
    //                 file_get_contents($file)
    //             );

    //             if($file){
    //                 $breeder = $this->user->userable;

    //                 $image = $imageInfo['type'];
    //                 $image->name = $imageInfo['filename'];

    //                 $breeder->images()->save($image);

    //                 $imageDetails['id'] = $image->id;
    //                 $imageDetails['name'] = $image->name;
    //             }
    //             else return response()->json([
    //                 'message' => 'Move file failed'
    //             ], 500);
    //         }
    //         else return response()->json([
    //             'message' => 'Upload failed'
    //         ], 500);

    //         return response()->json(collect($imageDetails)->toJson(), 200);
    //     }
    //     else return response()->json([
    //         'message' => 'No files detected'
    //     ], 500);
    // }
}
