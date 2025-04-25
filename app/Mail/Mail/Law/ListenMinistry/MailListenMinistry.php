<?php

namespace App\Mail\Mail\Law\ListenMinistry;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailListenMinistry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $title;
    private $url;
    private $dear;
    private $lawlistministry;

    public function __construct($item)
    { 
        $this->title            = $item['title'];
        $this->url              = $item['url'];
        $this->dear              = $item['dear'];
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
                    ->view('mail.Law.ListenMinistry.listen_ministry')
                    ->with([
                            'dear' => $this->dear,
                            'url' => $this->url,
                            'lawlistministry' => $this->lawlistministry
                        ]);
    }
}
