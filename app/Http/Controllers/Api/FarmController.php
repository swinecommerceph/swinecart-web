<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Breeder;
use App\Models\Customer;
use App\Models\FarmAddress;

use JWTAuth;
use Validator;

class FarmController extends Controller
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

    private function formatFarm($item)
    {
        $farm = [];

        $farm['id'] = $item->id;
        $farm['name'] = $item->name;
        $farm['addressLine1'] = $item->addressLine1;
        $farm['addressLine2'] = $item->addressLine2;
        $farm['farmType'] = $item->farmType;
        $farm['province'] = $item->province;
        $farm['zipCode'] = $item->zipCode;
        $farm['landline'] = $item->landline;
        $farm['mobile'] = $item->mobile;

        if ($this->accountType === 'Breeder') {
            $farm['accreditation']['number'] = $item->accreditation_no;
            $farm['accreditation']['status'] = $item->accreditation_status;
            $farm['accreditation']['date'] = $item->accreditation_date;
            $farm['accreditation']['expiry'] = $item->accreditation_expiry;
        }

        return $farm;
    }

    private function getUserFarms()
    {
        $farms = $this->user->userable->farmAddresses();

        if ($this->accountType === 'Breeder') {
            $farms = $farms->where('accreditation_status', 'active');
        }

        return $farms;
    }

    public function getFarms(Request $request)
    {
        $roleUser = $this->user->userable;

        $farms = $this->getUserFarms()
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                $farm = [];

                $farm['id'] = $item->id;
                $farm['name'] = $item->name;
                $farm['province'] = $item->province;

                return $farm;
            });

        return response()->json([
            'data' => [
                'hasNextPage' => false,
                'farms' => $farms,
            ]
        ], 200);
    }

    public function getFarm(Request $request, $farm_id)
    {
        $farm = $this->getUserFarms()->find($farm_id);

        if ($farm) {
            $formatted = $this->formatFarm($farm);
            return response()->json([
                'data' => [
                    'farm' => $formatted,
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm does not exist!'
        ], 404);
    }

    public function addFarm(Request $request)
    {
        $user = $this->user->userable;

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
                'error' => $validator->errors()
            ], 422);
        }

        else {

            $farm = new FarmAddress;
            $farm->name = $data['name'];
            $farm->addressLine1 = $data['addressLine1'];
            $farm->addressLine2 = $data['addressLine2'];
            $farm->province = $data['province'];
            $farm->zipCode = $data['zipCode'];
            $farm->farmType = $data['farmType'];
            $farm->landline = $data['landline'];
            $farm->mobile = $data['mobile'];

            $user->farmAddresses()->save($farm);

            $formatted = $this->formatFarm($farm);

            return response()->json([
                'success' => true,
                'data' => [
                    'farm' => $formatted
                ]
            ], 200);
        }
    }

    public function updateFarm(Request $request, $farm_id)
    {
        $farm = $this->getUserFarms()->find($farm_id);

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
                'error' => $validator->errors()
            ], 422);
        }

        if ($farm) {

            $farm->name = $data['name'];
            $farm->addressLine1 = $data['addressLine1'];
            $farm->addressLine2 = $data['addressLine2'];
            $farm->province = $data['province'];
            $farm->zipCode = $data['zipCode'];
            $farm->farmType = $data['farmType'];
            $farm->landline = $data['landline'];
            $farm->mobile = $data['mobile'];
            $farm->save();

            $formatted = $this->formatFarm($farm);

            return response()->json([
                'success' => true,
                'data' => [
                    'farm' => $formatted
                ]
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }

    public function deleteFarm(Request $request, $farm_id)
    {
        $farm = $this->getUserFarms()->find($farm_id);

        if ($farm) {

            $farm->delete();

            return response()->json([
                'success' => true
            ], 200);
        }
        else return response()->json([
            'error' => 'Farm Address does not exist!',
        ], 404);
    }
}
