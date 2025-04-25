<?php

namespace App\Mail\IB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IBDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->title = $item['title']; 
        $this->certi_ib = $item['certi_ib']; 
        $this->desc = $item['desc'];  
        $this->status = $item['status'];
        $this->attachs = $item['attachs'];

        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc)
                    ->replyTo($this->email_reply)
                    ->subject($this->title)
                    ->view('mail/IB.documents')
                    ->with(['title' => $this->title,
                            'certi_ib' => $this->certi_ib,
                            'desc' => $this->desc,
                            'url' => $this->url,
                            'attachs' => $this->attachs,
                            'status' => $this->status
                        ]);
    }
}
