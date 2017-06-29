<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use App\Notifications\BreederRated;
use App\Notifications\ProductRequested;
use App\Notifications\ProductReserved;
use App\Notifications\ProductReservationUpdate;
use App\Notifications\ProductReservedToOtherCustomer;

class NotifyUser implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $transactionType;
    protected $notificationDetails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transactionType, $userId, $notificationDetails)
    {
        $this->transactionType = $transactionType;
        $this->userId = $userId;
        $this->notificationDetails = $notificationDetails;
    }

    /**
     * Execute the job. Send passed notificationDetails data to respective user
     * This is a database notification
     *
     * @return void
     */
    public function handle()
    {

        switch ($this->transactionType) {
            case 'product-requested':
                $breederUser = User::find($this->userId);
                $breederUser->notify(new ProductRequested($this->notificationDetails));

                break;

            case 'breeder-rated':
                $breederUser = User::find($this->userId);
                $breederUser->notify(new BreederRated($this->notificationDetails));

                break;

            case 'product-reserved':
                $customerUser = User::find($this->userId);
                $customerUser->notify(new ProductReserved($this->notificationDetails));

                break;

            case 'product-reserved-to-other-customer':
                $customerUser = User::find($this->userId);
                $customerUser->notify(new ProductReservedToOtherCustomer($this->notificationDetails));

                break;

            case 'product-reservation-update':
                $customerUser = User::find($this->userId);
                $customerUser->notify(new ProductReservationUpdate($this->notificationDetails));

                break;

            default: break;

        }
    }
}
