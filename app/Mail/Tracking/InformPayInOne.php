<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InformPayInOne extends Mailable
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
        $this->PayIn = $item['PayIn']; 
        $this->assign = $item['assign'];

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
                    ->subject('แจ้งตรวจสอบการชำระค่าบริการในการตรวจประเมิน')
                    ->view('mail.Tracking.inform_pay_in_one')
                    ->with([
                            'data' => $this->data,
                            'PayIn' => $this->PayIn,
                            'assign' => $this->assign
                            ]);

        if(!empty($this->email_cc)){
           $mail =   $mail->cc($this->email_cc);
        }
        if(!empty($this->email_reply)){
            $mail =   $mail->replyTo($this->email_reply);
        }

       return $mail;


    }
}
