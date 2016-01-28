<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BreederProfileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'officeAddress_addressLine1' => 'required',
            'officeAddress_addressLine2' => 'required',
            'officeAddress_province' => 'required',
            'officeAddress_zipCode' => 'required',
            'office_mobile' => 'required',
            'farmAddress.*.addressLine1' => 'required',
            'farmAddress.*.addressLine2' => 'required',
            'farmAddress.*.province' => 'required',
            'farmAddress.*.zipCode' => 'required',
            'farmAddress.*.farmType' => 'required',
            'farmAddress.*.mobile' => 'required',
            'contactPerson_name' => 'required',
            'contactPerson_mobile' => 'required',
        ];
    }
}
