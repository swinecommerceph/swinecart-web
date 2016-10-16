<?php

namespace App\Models;

use App\Models\User;
use App\Models\AdministratorLog;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'administrator_user';


    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

    public function logs()
    {
        return $this->hasMany(AdministratorLog::class);
    }

}
