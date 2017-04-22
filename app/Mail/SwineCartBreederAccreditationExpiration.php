<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartBreederAccreditationExpiration extends Mailable
{
    use Queueable, SerializesModels;

    protected $type;
    protected $username;
    protected $email;
    protected $expiration;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $username, $email, $expiration)
    {
        $this->type = $type;
        $this->username = $username;
        $this->email = $email;
        $this->expiration = $expiration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.breederAccountExpiration')
                    ->with([
                        'type'=>$this->type,
                        'username'=>$this->username,
                        'email' => $this->email,
                        'expiration' => $this->expiration
                    ]);
    }
}
