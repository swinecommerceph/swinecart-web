<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductReservation extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id',
        'customer_id',
        'quantity',
        'order_status'];

    /**
     * Get the product that owns this product reservation
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
