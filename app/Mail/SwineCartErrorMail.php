<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartErrorMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mailError')
                    ->subject('SwineCart Error Mail')
                    ->with([
                        'level' => 'success',
                        'introLines' => ['Transaction was cancelled due to problems in the availability of the product or the other party that you are trying to transact to.',
                        'We are sorry for the inconvenience. Thank you for your understanding and continued support to our services.'],
                        'outroLines' => [],
                        'type'=> $this->type,
                    ]);
    }
}
