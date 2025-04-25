<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckCertificateLab extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($desc) 
    {
        $this->desc = $desc['desc'];
        $this->title = $desc['title'];
        $this->amount = $desc['amount'];
    } 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->from('noreply@example.com')
                ->view('mail/certify.certificate')
                ->with(['desc' => $this->desc,'title' => $this->title,'amount'=>$this->amount]);
    }
    
}
