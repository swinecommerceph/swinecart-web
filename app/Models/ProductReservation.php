<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Customer;
use App\Models\SwineCartItem;
use App\Models\TransactionLog;
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
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the transaction log related to this product reservation
     */
    public function transactionLogs()
    {
        return $this->hasManyThrough(TransactionLog::class, SwineCartItem::class, 'reservation_id', 'swineCart_id', 'id');
    }

    /**
     * Get the Swine Cart item related to this Product Reservation
     */
    public function swineCartItem()
    {
        return $this->hasOne(SwineCartItem::class, 'reservation_id');
    }

    public function statusTime()
    {
        return $this->hasManyThrough(TransactionLog::class, SwineCartItem::class, 'reservation_id', 'swineCart_id', 'id')->latest();
    }

}
