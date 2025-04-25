<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestEditScopeFromTracking extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->certi_lab = $item['certi_lab'];
        $this->request_message = $item['request_message'];
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
        $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) );

        if(!empty($this->email_cc) ){
        $mail =      $mail->cc($this->email_cc);
        }
        
        if(!empty($this->email_reply) ){
        $mail =      $mail->replyTo($this->email_reply);
        }

       $mail =   $mail->subject('ขอให้แก้ไขขอบข่าย')
                     ->view('mail.Tracking.mail_request_edit_scope')
                    ->with([
                            'certi_lab' => $this->certi_lab,
                            'url' => $this->url,
                            'request_message' => $this->request_message,
                          ]);
       return $mail;
    }
}
