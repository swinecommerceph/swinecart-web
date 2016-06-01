<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Customer;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
    protected $fillable = ['name', 'email', 'password', 'verification_code'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'status_instance'];

    /**
     * Get the roles that the user has
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all of the owning userable models
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Assign Role to a User
     *
     * @param  String   $role
     */
    public function assignRole($role)
    {
        if(is_string($role)){
            return $this->roles()->attach(
                Role::whereTitle($role)->firstOrFail()
            );
        }

        return $this->roles()->save($role);
    }

    /**
     * Check User if it has a certain role
     *
     * @param   String  $role
     * @return  Boolean
     */
    public function hasRole($role)
    {
        if(is_string($role)){
            return $this->roles->contains('title',$role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Check if User still needs to update profile
     *
     * @return Boolean
     */
    public function updateProfileNeeded()
    {
        if($this->update_profile) return true;
        return false;
    }

}
