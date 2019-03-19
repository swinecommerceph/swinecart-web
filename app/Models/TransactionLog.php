<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Breeder;
use App\Models\SwineCartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    public $timestamps = false;

    /**
     * Get the rescpective customer of the Transaction Log
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the breeder associated to the Transaction Log
     */
    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }

    /**
     * Get the Swine Cart Item this Transaction Log is associated to
     */
    public function swineCartItem()
    {
        return $this->belongsTo(SwineCartItem::class, 'swineCart_id');
    }

    /**
     * Get the related Product of the Transaction Log
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
