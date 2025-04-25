<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifySaveAssessment extends Mailable
{
    use Queueable, SerializesModels;

    public $certi_lab;
    public $data;
    public $url;
    public $email;
    public $email_cc;
    public $email_reply;  

    public function __construct($item)
    {
        $this->certi_lab = $item['certi_lab'];
        $this->data = $item['data'];
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
                        ->subject('นำส่งรายงานการตรวจประเมิน')
                        ->view('mail.Lab.save_assessment')
                        ->with([
                                'certi_lab' => $this->certi_lab,
                                'data' => $this->data,
                                'url' => $this->url
                             ]);
    }
}
