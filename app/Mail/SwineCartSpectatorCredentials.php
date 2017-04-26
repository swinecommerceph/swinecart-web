<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartSpectatorCredentials extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $password;
    protected $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $password, $type)
    {
        $this->email = $email;
        $this->password = $password;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.credentials')
                    ->subject('SwineCart Spectator Account Credentials')
                    ->with([
                        'email'=> $this->email,
                        'password'=> $this->password,
                        'type' => $this->type
                    ]);
    }
}
