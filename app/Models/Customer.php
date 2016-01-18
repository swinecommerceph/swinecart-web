<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_user';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['address_addressLine1',
        'address_addressLine2',
        'address_province',
        'address_zipCode',
        'landline',
        'mobile',
        'farmAddress_addressLine1',
        'farmAddress_addressLine2',
        'farmAddress_province',
        'farmAddress_zipCode',
        'farm_type',
        'farm_landline',
        'farm_mobile'];

	/**
	 * Get all Customer type users
	 */
    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }
}
