<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\TransactionLog;
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
     * Get the transaction log that tracks the Swine Cart Item
     */
    public function transactionLog()
    {
        return $this->hasOne(TransactionLog::class, 'swineCart_id');
    }
}
