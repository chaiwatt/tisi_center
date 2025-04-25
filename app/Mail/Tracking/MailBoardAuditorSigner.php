<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailBoardAuditorSigner extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->title    = $item['title'];
        $this->auditors = $item['auditors']; 
        $this->data = $item['data']; 
        $this->title = $item['title'];
        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
    }

    public function build()
    {
            $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) );

         if(!empty($this->email_cc)){
            $mail =      $mail->cc($this->email_cc);
         }
         if(!empty($this->email_reply)){
            $mail =      $mail->replyTo($this->email_reply);
         }
            $mail =     $mail->subject('ลงนามบันทึกข้อความ การแต่งตั้งคณะผู้ตรวจประเมิน')
                        ->view('mail.Tracking.mail_board_auditor_signer')
                        ->with([
                               'title' => $this->title,
                               'data' => $this->data,
                               'auditors' => $this->auditors,
                               'url' => $this->url
                            ]);
          return $mail;

    }
}
