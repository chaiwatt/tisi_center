<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CBScopeEditAlertMail extends Mailable
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
        $this->message = $item['message'];
        
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
                        ->subject('ขอให้แก้ไขขอบข่าย')
                        ->view('mail.CB.scope_edit')
                        ->with([
                              'certi_cb' => $this->certi_cb,
                              'desc' => $this->message,
                              'url' => $this->url
                            ]);
    }
}
