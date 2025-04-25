<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestEditScope extends Mailable
{
    use Queueable, SerializesModels;

     
    public $certi_lab;
    public $url;
    public $email;
    public $email_cc;
    public $email_reply;  
    public $request_message;  

    public function __construct($item)
    {
        $this->certi_lab = $item['certi_lab'];
        $this->request_message = $item['request_message'];
        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
        // 
    }

    public function build()
    {
        // dd($this->message);
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
        ->cc($this->email_cc)
        ->replyTo($this->email_reply)
        ->subject('ขอให้แก้ไขขอบข่าย')
        ->view('mail.Lab.mail_request_edit_scope')
        ->with([
                'certi_lab' => $this->certi_lab,
                'url' => $this->url,
                'request_message' => $this->request_message,
             ]);
    }
}
