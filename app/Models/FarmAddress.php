<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmAddress extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'farm_addresses';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['status_instance'];

    /**
     * Get all of the farm addressable models
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Get all products of the farm
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'farm_from_id');
    }
}
