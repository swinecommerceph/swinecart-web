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
    public $attachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($announcement, $attachment)
    {
        $this->announcement = $announcement;
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
                        ->subject('SwineCart Announcement')
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
                        ->subject('SwineCart Announcement');
            foreach ($attachment as $file) {
                $email->attach(public_path().$file->path);
            }
            return $email;
        }

    }
}
