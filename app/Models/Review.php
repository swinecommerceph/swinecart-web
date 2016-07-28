<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * Get the Breeder of the Review
     */
    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }
}
