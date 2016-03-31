<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    /**
     * Get the rescpective customer of the Transaction Log
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
