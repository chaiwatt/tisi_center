<?php
namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyConfirmedPayIn1 extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->certi_lab = $item['certi_lab']; 
        $this->PayIn = $item['PayIn'];
 
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
                        ->view('mail.Lab.inform_pay_in_one')
                        ->with([
                                'certi_lab' => $this->certi_lab,
                                'PayIn' => $this->PayIn
                             ]);

        // if(count($this->email_cc) > 0){
        //    $mail =   $mail->cc($this->email_cc);
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
