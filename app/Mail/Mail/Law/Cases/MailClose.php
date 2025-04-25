<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailClose extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $case;
    private $title;
    private $url;

    public function __construct($item)
    { 
        $this->case             = $item['case'];
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
 
        $mail =   $mail->subject($this->title)
                    ->view('mail.Law.Cases.close')
                    ->with([
                            'case'             => $this->case,
                            'title'            => $this->title,
                            'url'              => $this->url
                          ]);
         return $mail;
    }
}
