<?php

namespace App\Mail\Ib;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailToIbExpert extends Mailable
{
    use Queueable, SerializesModels;

    
    public $certi_ib;
    public $url;
    public $email;
    public $email_cc;
    public $email_reply;  

    public function  __construct($items)
    {
        $this->certi_ib = $items['certi_ib'];
        $this->url = $items['url'];
        $this->email = $items['email'];
        $this->email_cc = $items['email_cc'];
        $this->email_reply = $items['email_reply'];

    }

    public function build()
    {
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc)
                    ->replyTo($this->email_reply)
                    ->subject('เพิ่มรายการข้อบกพร่อง / ข้อสังเกต')
                    ->view('mail.IB.mail_ib_expert')
                    ->with([
                            'certi_ib' => $this->certi_ib,
                            'url' => $this->url,
                        ]);
    }
}

