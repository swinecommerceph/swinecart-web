<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\TransactionLog;
use App\Models\ProductReservation;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;

class Breeder extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'breeder_user';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['officeAddress_addressLine1',
        'officeAddress_addressLine2',
        'officeAddress_province',
        'officeAddress_zipCode',
        'office_landline',
        'office_mobile',
        'website',
        'produce',
        'registration_number',
        'contactPerson_name',
        'contactPerson_mobile',
        'latest_accreditation',
        'notification_date'
    ];

    /**
     * Get all Breeder type users
     */
    public function users()
    {
        return $this->morphMany(User::class, 'userable');
    }

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * Get all of the Breeder's farm address/es
     */
    public function farmAddresses()
    {
        return $this->morphMany(FarmAddress::class, 'addressable');
    }

    /**
     * Get all of the Breeder's images
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get all of the Breeder's videos
     */
    public function videos()
    {
        return $this->morphMany(Video::class, 'videoable');
    }

    /**
     * Get all of the Breeder's products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all Breeder's reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all of the product reservations for the Breeder
     */
    public function reservations()
    {
        return $this->hasManyThrough(ProductReservation::class, Product::class);
    }

    /**
     * Get all transaction logs wher Breeder is associated with
     */
    public function transactionLogs()
    {
        return $this->hasMany(TransactionLog::class);
    }
}
