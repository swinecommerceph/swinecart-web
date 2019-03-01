<?php

namespace App\Repositories;

use App\Models\SwineCartItem;
use App\Models\TransactionLog;

trait CustomHelpers
{
    /**
    * Parse $breed if it contains '+' (ex. landrace+duroc)
    * to "Landrace x Duroc"
    *
    * @param  String   $breed
    * @return String
    */
    public function transformBreedSyntax($breed)
    {
       if(str_contains($breed,'+')){
           $part = explode("+", $breed);
           $breed = ucfirst($part[0])." x ".ucfirst($part[1]);
           return $breed;
       }
       return ucfirst($breed);
    }

    /**
     * Transform date to desired date format
     *
     * @param  String   $date
     * @return String
     */
    public function transformDateSyntax($date, $format = 0)
    {
        switch ($format) {
            case 1:
                // Log format for SMS
                return date_format(date_create($date), 'm-d-Y h:i:sA');

            case 2:
                // Same as log format for SMS except that it has fully-spelled month, date with leading zeros, and full year
                return date_format(date_create($date), 'F j, Y h:i:sA');

            case 3: 
                return date_format(date_create($date), 'M j Y (D), h:iA');

            default:
                // Default format would be fully-spelled month, date with leading zeros, and full year
                return date_format(date_create($date), 'F j, Y');
        }

    }

    /**
     * Compute age (in days) of product with the use of its birthdate
     *
     * @param  String   $birthdate
     * @return Integer
     */
    public function computeAge($birthdate)
    {
        $rawSeconds = time() - strtotime($birthdate);
        $age = ((($rawSeconds/60)/60))/24;
        return floor($age);
    }

    /**
     * Parse $other_details
     *
     * @param  String   $otherDetails
     * @return String
     */
    public function transformOtherDetailsSyntax($otherDetails)
    {
        $details = explode(',',$otherDetails);
        $transformedSyntax = '';
        foreach ($details as $detail) {
            $transformedSyntax .= $detail."<br>";
        }
        return $transformedSyntax;
    }

    /**
     * Add the passed transactionDetails data to the user's Transaction Log
     *
     * @param   Array   $transactionDetails
     * @return  void
     */
    public function addToTransactionLog($transactionDetails)
    {
        $swineCartItem = SwineCartItem::findOrFail($transactionDetails['swineCart_id']);

        $transactionLog = new TransactionLog;
        $transactionLog->customer_id = $transactionDetails['customer_id'];
        $transactionLog->breeder_id = $transactionDetails['breeder_id'];
        $transactionLog->product_id = $transactionDetails['product_id'];
        $transactionLog->status = $transactionDetails['status'];
        $transactionLog->created_at = $transactionDetails['created_at'];

        $swineCartItem->transactionLogs()->save($transactionLog);
    }
}
