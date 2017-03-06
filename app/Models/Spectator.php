<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Spectator extends Model
{
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spectators';


    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

}
