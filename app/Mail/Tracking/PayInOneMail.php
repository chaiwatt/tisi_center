<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayInOneMail extends Mailable
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

            $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) );

            if(!empty($this->email_cc)){
            $mail =      $mail->cc($this->email_cc);
            }
            
            if(!empty($this->email_reply)){
            $mail =      $mail->cc($this->email_reply);
            }

            if($this->attachs != ''){ // ส่ง mail พร้อมไฟล์ pdf
                $location =  public_path(). '/uploads/'.$this->attachs; 
                $mail =    $mail->attach($location);
            }

           $mail =   $mail->subject('แจ้งค่าบริการในการตรวจประเมิน')
                       ->view('mail.Tracking.pay_in_one')
                       ->with([
                                'data' => $this->data,
                                'pay_in' => $this->pay_in,
                                'url' => $this->url
                               ]);
         return $mail;


    }
}
