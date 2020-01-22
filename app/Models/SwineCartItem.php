<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Product;
use App\Models\TransactionLog;
use App\Models\ProductReservation;
use Illuminate\Database\Eloquent\Model;

class SwineCartItem extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'swine_cart_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id',
        'quantity'];

    /**
     * Get the respective customer of the Swine Cart Item
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the Transaction Logs that are related to the Swine Cart Item
     */
    public function transactionLogs()
    {
        return $this->hasMany(TransactionLog::class, 'swineCart_id');
    }

    /**
     * Get the Product Reservation tied to this Swine Cart item
     */
    public function productReservation()
    {
        return $this->belongsTo(ProductReservation::class, 'reservation_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
}
