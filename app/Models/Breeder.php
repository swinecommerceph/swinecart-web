<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Breeder extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'breeder_user';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['officeAddress_addressLine1',
        'officeAddress_addressLine2',
        'officeAddress_province',
        'officeAddress_zipCode',
        'office_landline',
        'office_mobile',
        'farmAddress_addressLine1',
        'farmAddress_addressLine2',
        'farmAddress_province',
        'farmAddress_zipCode',
        'farm_type',
        'farm_landline',
        'farm_mobile',
        'contactPerson_name',
        'contactPerson_mobile'];

    /**
     * Get all Breeder type users
     */
    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }
}
