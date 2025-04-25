<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveAssessmentPastMail extends Mailable
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
        $this->assessment = $item['assessment'];
        $this->export = $item['export'];
        
 
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
        $mail =      $mail->replyTo($this->email_reply);
        }

       $mail =   $mail->subject('แจ้งผลการประเมิน')
                    ->view('mail.Tracking.save_assessment_past')
                    ->with([ 
                              'data' => $this->data,
                              'assessment' => $this->assessment,
                              'export' => $this->export,
                              'url' => $this->url
                    ]);
       return $mail;


 

    }
}
