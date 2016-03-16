<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmAddress extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'farm_addresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['addressLine1',
        'addressLine2',
        'province',
        'zipCode',
        'farmType',
        'landline',
        'mobile'];

    /**
     * Get all of the farm addressable models
     */
    public function addressable()
    {
        return $this->morphTo();
    }
}
