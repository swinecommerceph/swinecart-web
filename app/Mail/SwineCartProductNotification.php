<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartProductNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $type;
    protected $user;
    protected $product;
    protected $information;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $user, $product, $information)
    {
        $this->type = $type;
        $this->user = $user;
        $this->product = $product;
        $this->information = $information;
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
        if($this->type==0){
            $introlines = ["The product ".$product." is needed by ". $user->name." on ". $information->date_needed".", "The customer's special request ".$this->information->special_request];
            $outrolines = ["Please attend to the customer's request as soon as possible"];
        }else if($this->type==1){
            $introlines = ["The product ".$product." reservation to ". $user->name." will expire on ". $information->expiration_date"."];
            $outrolines = ["Please attend to the transaction request as soon as possible"];
        }else if($this->type==2){
            $introlines = ['Your transaction was cancelled due to problems in the product or the other party'];
            $outrolines = ['Sorry for the inconvenience'];
        }
        return $this->view('emails.productNotification')
                    ->subject('SwineCart Product Notification')
                    ->with([
                        'level' => 'success',
         				'introLines' => $introlines,
         				'outroLines' => $outrolines ,
                        'type'=> $this->type,
                        'user'=> $this->user,
                        'product' => $this->product,
                        'information' => $this->information
                    ]);
    }
}
