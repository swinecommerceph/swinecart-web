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
                        'type'=> $this->type,
                    ]);
    }
}
