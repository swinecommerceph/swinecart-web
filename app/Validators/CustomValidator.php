<?php

namespace App\Validators;

use Auth;
use Hash;

class CustomValidator
{

    /**
     * Custom validator if typed password is the current password.
     * Will be used during change password
     *
     * @param   String      $attribute
     * @param   String      $value
     * @param   Array       $parameters
     * @param   Validator   $validator
     * @return  Boolean
     */
    public function currentPasswordValidator($attribute, $value, $parameters, $validator)
    {
        return Hash::check($value, Auth::user()->password);
    }
}
