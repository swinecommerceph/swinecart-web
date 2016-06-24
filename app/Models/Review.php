<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $table = 'reviews';

    public function breeder(){
      return $this->belongsTo(Breeder::class);
    }
}
