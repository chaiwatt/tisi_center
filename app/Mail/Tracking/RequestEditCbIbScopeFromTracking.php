<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestEditCbIbScopeFromTracking extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->certi = $item['certi'];
        $this->request_message = $item['request_message'];
        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
    }

                    //   $data_app =   [
                    //             'certi'          => $certiIb,
                    //             'data'           => $inspection ,
                    //             'export'         => $inspection->certificate_export_to ?? '' ,
                    //             'url'            => $url.'certify/tracking-ib',
                    //             'email'          =>  !empty($certiIb->DataEmailCertifyCenter) ? $certiIb->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                    //             'email_cc'       =>  !empty($certiIb->DataEmailDirectorIBCC) ? $certiIb->DataEmailDirectorIBCC :  [],
                    //             'email_reply'    => !empty($certiIb->DataEmailDirectorIBReply) ? $certiIb->DataEmailDirectorIBReply :[]
                    //         ] ;  

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) );

        if(!empty($this->email_cc) ){
        $mail =      $mail->cc($this->email_cc);
        }
        
        if(!empty($this->email_reply) ){
        $mail =      $mail->replyTo($this->email_reply);
        }

       $mail =   $mail->subject('ขอให้แก้ไขขอบข่าย')
                     ->view('mail.Tracking.mail_request_edit_cb_ib_scope')
                    ->with([
                            'certi' => $this->certi,
                            'url' => $this->url,
                            'request_message' => $this->request_message,
                          ]);
       return $mail;
    }
}
