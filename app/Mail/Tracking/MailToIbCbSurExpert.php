<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailToIbCbSurExpert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->certi    = $item['certi'];
        $this->assessment    = $item['assessment'];

        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
    }

   

    public function build()
    {
      // dd($this->certi,$this->assessment,$this->url);
        $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) );

        if(!empty($this->email_cc)){
           $mail =      $mail->cc($this->email_cc);
        }
        if(!empty($this->email_reply)){
           $mail =      $mail->replyTo($this->email_reply);
        }
           $mail =     $mail->subject('เพิ่มรายการข้อบกพร่อง / ข้อสังเกต')
                       ->view('mail.Tracking.mail_cb_ib_expert')
                       ->with([
                              'certi' => $this->certi,
                              'assessment' => $this->assessment,
                              'url' => $this->url
                           ]);
         return $mail;
    }
}
