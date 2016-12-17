<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SwineCartItem;
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
     * Get the Swine Cart Item this Transaction Log is associated to
     */
    public function swineCartItem()
    {
        return $this->belongsTo(SwineCartItem::class);
    }
}
