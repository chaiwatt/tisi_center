<?php

namespace App\Mail\Lab;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Certify\Applicant\CheckExaminer;

class MailBoardAuditorSigner extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $certi_lab;
    public $auditors;
    public $url;
    public $email;
    public $email_cc;
    public $email_reply;  

    public function  __construct($items)
    {
        $this->certi_lab = $items['certi_lab'];
        $this->auditors = $items['auditors'];
        $this->url = $items['url'];
        $this->email = $items['email'];
        $this->email_cc = $items['email_cc'];
        $this->email_reply = $items['email_reply'];

    }

    public function build()
    {
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc)
                    ->replyTo($this->email_reply)
                    ->subject('ลงนามบันทึกข้อความ การแต่งตั้งคณะผู้ตรวจประเมิน')
                    ->view('mail/Lab.mail_board_auditor_signer')
                    ->with(['certi_lab' => $this->certi_lab,
                            'url' => $this->url,
                            'auditors' => $this->auditors,
                        ]);
    }
}
