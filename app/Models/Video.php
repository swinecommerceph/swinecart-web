<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'videos';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * Get all of the videoable models
     */
    public function videoable()
    {
        return $this->morphTo();
    }
}
