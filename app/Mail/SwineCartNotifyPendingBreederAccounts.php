<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartNotifyPendingBreederAccounts extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.pendingNotification')
                    ->subject('SwineCart Breeder Account Credentials')
                    ->with([
                        'level' => 'success',
         				'introLines' => ["You haven't updated your profile for the past 30 days, please update your profile as soon as possible"],
         				'outroLines' => [] ,
                    ]);
    }
}
