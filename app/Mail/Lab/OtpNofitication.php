<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OtpNofitication extends Mailable
{
    use Queueable, SerializesModels;

    public $certi_lab;
    public $otp;
    public $ref_otp;
    public $email;

    public function __construct($item)
    {
        $this->certi_lab = $item['certi_lab'];
        $this->otp = $item['otp'];
        $this->ref_otp = $item['ref_otp'];
        $this->email = $item['email'];
    }

    public function build()
    {
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
        ->subject('OTP ลงนามใบรับรอง')
        ->view('mail.Lab.mail_otp_notification')
        ->with([
                'certi_lab' => $this->certi_lab,
                'otp' => $this->otp,
                'ref_otp' => $this->ref_otp,
             ]);
    }
}
