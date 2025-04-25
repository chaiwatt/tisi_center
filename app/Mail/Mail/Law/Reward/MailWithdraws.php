<?php

namespace App\Mail\Mail\Law\Reward;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailWithdraws extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
 
        $this->withdraws        = $item['withdraws'];
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
                        ->view('mail.Law.Reward.withdraws')
                       ->with([
                                'title'             => $this->title,
                                'withdraws'         => $this->withdraws
                             ]);
         return $mail;
    }
}
