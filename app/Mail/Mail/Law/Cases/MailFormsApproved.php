<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailFormsApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->case             = $item['case'];
        $this->user             = $item['user'];
        $this->approve          = $item['approve'];
        $this->level_approve    = $item['level_approve']; 
        $this->title            = $item['title'];
        $this->url              = $item['url'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'),  config('mail.from.name'));
 
        $mail =   $mail->subject('e-Legal : '.$this->title)
                    ->view('mail.Law.Cases.forms_approved')
                    ->with([
                            'case'              => $this->case,
                            'user'              => $this->user,
                            'approve'           => $this->approve,
                            'level_approve'     => $this->level_approve,
                            'title'             => $this->title,
                            'url'     => $this->url
                          ]);
         return $mail;
    }
}
