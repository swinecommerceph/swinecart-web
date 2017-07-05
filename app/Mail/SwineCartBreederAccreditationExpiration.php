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
        $introlines = [];
        $outrolines = [];
        if($this->type == 0){
            $title = "Breeder Accreditation Expiration";
            $introlines = ["Dear ".$this->$username.",", "Your account ". $this->email ." accreditation will expire on ". $expiration];
            $outrolines = ["Please consider renewing your accreditation as soon as possible"];
        }else if($this->type == 1){
            $title = "Breeder Accreditation Expiration";
            $introlines = ["Dear ".$this->$username.",", "Your account ". $this->email ." accreditation will expire on ". $expiration];
            $outrolines = ["Please consider renewing your accreditation as soon as possible"];
        }else if($this->type == 2){
            $title = "Breeder Accreditation Expiration";
            $introlines = ["Your account ".$this->email." has been temporarily blocked due to expired breeder accreditation."];
            $outrolines = ["Please consider renewing your accreditation to continue using our services. Thank you"];
        }
        return $this->view('emails.breederAccountExpiration')
                    ->subject('SwineCart Account Notification')
                    ->with([
                        'level' => 'success',
                        'type'=>$this->type,
                        'title'=>$title,
                        'introLines' => $introlines,
         				'outroLines' => $outrolines ,
                        'username'=>$this->username,
                        'email' => $this->email,
                        'expiration' => $this->expiration
                    ]);
    }
}
