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

class ProfileController extends Controller
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

    public function getProfile(Request $request)
    {
        $customer = $this->user->userable;

        return response()->json([
            'message' => 'Customer Me successful!',
            'data' => [
                'profile' => $customer
            ]
        ], 200);
    }

    public function updatePersonal(CustomerPersonalProfileRequest $request)
    {
        $customer = $this->user->userable;
        $data = $request->only([
            'address_addressLine1',
            'address_addressLine2',
            'address_province',
            'address_zipCode',
            'landline',
            'mobile'
        ]);

        $validator = Validator::make($data, [
            'address_addressLine1' => 'required',
            'address_addressLine2' => 'required',
            'address_province' => 'required',
            'address_zipCode' => 'required|digits:4',
            'mobile' => 'required|digits:11|regex:/^09/',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }
        else {
            $customer->fill($data)->save();

            return response()->json([
                'message' => 'Update Personal successful!'
            ], 200);
        }
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
}