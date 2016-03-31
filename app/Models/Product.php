<?php

namespace App\Models;

use App\Models\Breeder;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['farm_from_id',
        'primary_img_id',
        'name',
        'type',
        'age',
        'breed_id',
        'price',
        'quantity',
        'adg',
        'fcr',
        'backfat_thickness',
        'other_details'];

    /**
     * Get the breeder that owns this product
     */
    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }

    /**
     * Get all of the Product's images
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get all of the Product's videos
     */
    public function videos()
    {
        return $this->morphMany(Video::class, 'videoable');
    }
}
