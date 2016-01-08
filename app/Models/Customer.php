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
    protected $fillable = ['address', 'landline', 'mobile',
        'farm_address', 'farm_type', 'farm_landline', 'farm_mobile'];

	/**
	 * Get all Customer type users
	 */
    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }
}
