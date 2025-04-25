<?php

namespace App\Mail\Mail\Law\Result;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailConsider extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->case     = $item['case'];
        $this->title    = $item['title'];
        $this->result   = $item['result'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'), config('mail.from.name')) 
                    ->subject($this->title)
                    ->view('mail.Law.Result.consider')
                    ->with([
                            'case' => $this->case,
                            'result' => $this->result
                        ]);
    }
}
