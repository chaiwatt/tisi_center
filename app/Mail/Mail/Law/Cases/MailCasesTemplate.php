<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailCasesTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->topic   = $item['topic'];
        $this->subject = $item['subject'];
        $this->learn   = $item['learn'];
        $this->content = $item['content'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'), config('mail.from.name')) 
                        ->subject($this->topic)
                        ->view('mail.Law.Cases.template-mail')
                        ->with([
                            'subject' => $this->subject,
                            'learn'   => $this->learn,
                            'content' => $this->content,
                        ]);
    }
}
