<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CBDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->certi_cb = $item['certi_cb'];
        $this->status = $item['status'];
        $this->desc = $item['desc'];   
         $this->data = $item['data']; 
        $this->title = $item['title']; 
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
                        ->view('mail/CB.documents')
                        ->with([
                                'certi_cb' => $this->certi_cb,
                                'status' => $this->status,
                                'desc' => $this->desc,
                                'data' => $this->data,
                                'title' => $this->title,
                                'url' => $this->url,
                                'attachs' => $this->attachs
                              ]);
    }
}
