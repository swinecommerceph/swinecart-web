<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SwineCartAnnouncement extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $announcement;
    public $emailSubject;
    public $attachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($announcement, $emailSubject, $attachment)
    {
        $this->announcement = $announcement;
        $this->emailSubject = $emailSubject;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if($this->attachment==NULL){
            return $this->view('emails.announcement')
                        ->subject($this->emailSubject)
                        ->with([
                            'level' => 'success',
                            'introLines' => [],
             				'outroLines' => [],
                            'announcement'=>$this->announcement
                        ]);
        }else{
            $email =   $this->view('emails.announcement')->with([
                        'level' => 'success',
                        'introLines' => [],
                        'outroLines' => [],
                        'announcement'=>$this->announcement])
                        ->subject($this->emailSubject);
            foreach ($this->attachment as $file) {
                $email->attach(public_path().$file->path);
            }
            return $email;
        }

    }
}
