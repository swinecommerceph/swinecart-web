<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    public $timestamps = false;

    /**
     * Get the rescpective user of the User Log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
