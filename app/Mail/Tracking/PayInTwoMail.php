<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayInTwoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->data = $item['data'];
        $this->pay_in = $item['pay_in'];
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
        if($this->attachs != ''){ // ส่ง mail พร้อมไฟล์ pdf
 
            $location =  public_path(). '/uploads/'.$this->attachs; 
       
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                      ->cc($this->email_cc)
                      ->replyTo($this->email_reply)
                      ->subject('แจ้งค่าบริการในการตรวจประเมิน')
                      ->view('mail.Tracking.pay_in_two')
                      ->with([
                              'data' => $this->data,
                              'pay_in' => $this->pay_in,
                              'url' => $this->url
                            ])
                      ->attach($location);
        }else{
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                      ->cc($this->email_cc)
                      ->replyTo($this->email_reply)
                      ->subject('แจ้งค่าบริการในการตรวจประเมิน')
                      ->view('mail.Tracking.pay_in_two')
                      ->with([
                              'data' => $this->data,
                              'pay_in' => $this->pay_in,
                              'url' => $this->url
                            ]);
        }
 
    }
}
