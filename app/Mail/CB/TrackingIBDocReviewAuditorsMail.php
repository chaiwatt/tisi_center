<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackingIBDocReviewAuditorsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($item)
    { 
 
        $this->tracking = $item['tracking'];
        $this->certi_ib = $item['certi_ib'];
        $this->auditors = $item['auditors'];
        $this->cbDocReviewAuditor = $item['cbDocReviewAuditor'];
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
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) ) // $this->email
                        ->cc($this->email_cc)
                        ->replyTo($this->email_reply)
                        ->subject('การแต่งตั้งคณะผู้ตรวจประเมินเอกสาร')
                        ->view('mail.Tracking.ib_auditor_doc_review')
                        ->with([
                               'tracking' => $this->tracking,
                               'certi_ib' => $this->certi_ib,
                               'auditors' => $this->auditors,
                               'url' => $this->url, 
                               'cbDocReviewAuditor' => $this->cbDocReviewAuditor, 
                            ]);
    }
}
