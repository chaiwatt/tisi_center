<?php

namespace App\Mail\CB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CBInformPayInOne extends Mailable
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
        $this->PayIn = $item['PayIn'];
        
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
        $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->subject('ใบรับรองหน่วยรับรอง')
                    ->view('mail.CB.inform_pay_in_one')
                    ->with([
                            'certi_cb' => $this->certi_cb,
                            'PayIn' => $this->PayIn,
                            'url' => $this->url
                            ]);
                                            
        // if(count($this->email_cc) > 0){
        //     $mail =   $mail->cc($this->email_cc);
        // }
        // if(count($this->email_reply) > 0){
        //     $mail =   $mail->replyTo($this->email_reply);
        // }

        if (!empty($this->email_cc)) {
            $mail = $mail->cc($this->email_cc);
        }
        
        if (!empty($this->email_reply)) {
            $mail = $mail->replyTo($this->email_reply);
        }
       
    
          return $mail;
    }
}
