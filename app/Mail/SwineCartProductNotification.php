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
        return $this->view('emails.productNotification')
                    ->subject('SwineCart Product Notification')
                    ->with([
                        'type'=> $this->type,
                        'user'=> $this->user,
                        'product' => $this->product,
                        'information' => $this->information
                    ]);
    }
}
