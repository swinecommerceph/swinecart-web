<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Log;

class Admin extends Model
{
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

}
