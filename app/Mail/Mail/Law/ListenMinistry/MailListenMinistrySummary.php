<?php

namespace App\Mail\Mail\Law\ListenMinistry;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailListenMinistrySummary extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->title            = $item['title'];
        $this->lawlistministry  = $item['lawlistministry'];
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
                    ->view('mail.Law.ListenMinistry.listen_ministry_summary')
                    ->with([
                            'lawlistministry' => $this->lawlistministry
                        ]);
    }
}
