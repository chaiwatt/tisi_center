<?php

namespace App\Mail\Mail\Law\Result;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailDocument extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->case = $item['case'];
        $this->title = $item['title'];
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
                    ->view('mail.Law.Result.document')
                    ->with([
                            'case' => $this->case
                        ]);
    }
}
