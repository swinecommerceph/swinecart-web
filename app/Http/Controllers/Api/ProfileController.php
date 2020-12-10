<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

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

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            $this->user->password = bcrypt($request->new_password);
            $this->user->save();

            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    public function editProfile(Request $request)
    {
        $user = $this->user->userable;

        $validator;
        $data;

        if ($this->accountType === 'Breeder') {

            $data = $request->only([
                'addressLine1',
                'addressLine2',
                'province',
                'zipCode',
                'landline',
                'mobile',
                'website',
                'produce',
                'contactPersonName',
                'contactPersonMobile'
            ]);

            $validator = Validator::make($data, [
                'addressLine1' => 'required',
                'addressLine2' => 'required',
                'province' => 'required',
                'zipCode' => 'required|digits:4',
                'mobile' => 'required|digits:11|regex:/^09/',
                'contactPersonName' => 'required',
                'contactPersonMobile' => 'required|digits:11|regex:/^09/',
            ]);
        }
        else {

            $data = $request->only([
                'addressLine1',
                'addressLine2',
                'province',
                'zipCode',
                'landline',
                'mobile'
            ]);

            $validator = Validator::make($data, [
                'addressLine1' => 'required',
                'addressLine2' => 'required',
                'province' => 'required',
                'zipCode' => 'required|digits:4',
                'mobile' => 'required|digits:11|regex:/^09/',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            if ($this->accountType === 'Breeder') {
                $user->officeAddress_addressLine1 = $data['addressLine1'];
                $user->officeAddress_addressLine2 = $data['addressLine2'];
                $user->officeAddress_province = $data['province'];
                $user->officeAddress_zipCode = $data['zipCode'];
                $user->office_landline = $data['landline'];
                $user->office_mobile = $data['mobile'];
                $user->website = $data['website'];
                $user->produce = $data['produce'];
                $user->contactPerson_name = $data['contactPersonName'];
                $user->contactPerson_mobile = $data['contactPersonMobile'];
            }
            else {
                $user->address_addressLine1 = $data['addressLine1'];
                $user->address_addressLine2 = $data['addressLine2'];
                $user->address_province = $data['province'];
                $user->address_zipCode = $data['zipCode'];
                $user->landline = $data['landline'];
                $user->mobile = $data['mobile'];
            }

            $user->save();

            return response()->json([
                'success' =>  true,
                'user' => $user,
            ], 200);
        }
    }

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
