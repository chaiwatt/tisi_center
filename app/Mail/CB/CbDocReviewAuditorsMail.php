<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CbDocReviewAuditorsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($item)
    { 
 
        $this->certi_cb = $item['certi_cb'];
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
                        ->view('mail.CB.auditor_doc_review')
                        ->with([
                               'certi_cb' => $this->certi_cb,
                               'auditors' => $this->auditors,
                               'url' => $this->url, 
                               'cbDocReviewAuditor' => $this->cbDocReviewAuditor, 
                            ]);
    }
}
