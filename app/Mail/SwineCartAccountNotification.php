<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;
use Carbon\Carbon;

class SwineCartAccountNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $type)
    {
         $this->user = $user;
         $this->type = $type;
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
            $introlines = ['Your account has been blocked', 'Reason: '.$this->user->block_reason.''];
            $outrolines = ['Please contact the site adminitstrator for more details'];
        }else if($this->type==1){
            $introlines = ['Your account has been unblocked'];
            $outrolines = '';
        }else if($this->type==2){
            $introlines = ['Your account has been deleted', 'Reason: '.$this->user->delete_reason.''];
            $outrolines = ['Please contact the site adminitstrator for more details'];
        }
        return $this->view('emails.adminNotifications')
                    ->subject('SwineCart Account Notification')
                    ->with([
                        'level' => 'success',
         				'introLines' => $introlines,
         				'outroLines' => $outrolines ,
                        'type'=>$this->type,
                        'user'=>$this->user
                    ]);
    }
}
