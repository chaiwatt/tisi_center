<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CBSaveAssessmentPastMail extends Mailable
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
        $this->assessment = $item['assessment'];

 
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
                    ->subject('รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย')
                    ->view('mail/CB.save_assessment_past')
                    ->with([ 
                              'certi_cb' => $this->certi_cb,
                              'assessment' => $this->assessment,
                              'url' => $this->url
                    ]);
    }
}
