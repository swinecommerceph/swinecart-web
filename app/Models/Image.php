<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class Image extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get all of the imageable models
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    public function productPrimaryImage()
    {
        return $this->belongsTo(Product::class, 'primary_img_id');
    }
}
