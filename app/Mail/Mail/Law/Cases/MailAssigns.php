<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailAssigns extends Mailable
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
        $this->assign           = $item['assign'];
        $this->user           = $item['user'];
        $this->title            = $item['title'];
 
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'),  config('mail.from.name'));
 
        $mail =   $mail->subject($this->title)
                    ->view('mail.Law.Cases.assigns')
                    ->with([
                            'case'             => $this->case,
                            'assign'           => $this->assign,
                            'user'           => $this->user,
                            'title'            => $this->title
                          ]);
         return $mail;
    }
}
