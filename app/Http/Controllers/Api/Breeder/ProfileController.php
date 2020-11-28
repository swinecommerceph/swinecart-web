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

class ProfileController extends Controller
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

    public function updatePersonal(Request $request) 
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
}
