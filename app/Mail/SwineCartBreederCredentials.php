<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartBreederCredentials extends Mailable
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
        $introlines = ['Use these credentials to login to SwineCart', 'Email: '.$this->email, 'Password: '.$this->password.''];
        $outrolines = [];
        return $this->view('emails.credentials')
                    ->subject('SwineCart Breeder Account Credentials')
                    ->with([
                        'level' => 'success',
         				'introLines' => $introlines,
         				'outroLines' => $outrolines ,
                        'type' => $this->type
                    ]);
    }
}
