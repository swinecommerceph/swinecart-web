<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Get the users under unique roles
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
