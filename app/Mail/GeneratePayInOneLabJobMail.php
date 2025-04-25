<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CostAssessment;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneratePayInOneLabJobMail extends Mailable
{
    use Queueable, SerializesModels;

    public $certiLab;
    public $email;
    public $emailCc;
    public $emailReply;  
    public $emailSubject;
    public $emailBladeView;
    public $certiLabId;
    public $costAssessment;
    

    public function __construct($item)
    {
        $this->certiLab = CertiLab::find($item['certiLabId']);
        $this->costAssessment = CostAssessment::find($item['costAssessmentId']);
        $this->email = $item['email'];
        $this->emailCc = $item['emailCc'];
        $this->emailReply = $item['emailReply'];
        $this->emailSubject = $item['emailSubject'];
        $this->emailReply = $item['emailReply'];
        $this->emailBladeView = $item['emailBladeView'];
        // $this->certiLabId = $item['certiLabId'];
    }

    public function build()
    {
        // return $this->subject($this->details['subject'])
        //             ->view('emails.custom')
        //             ->with('details', $this->details);

        return $this->from(
                config('mail.from.address'),
                !empty($this->email) ? $this->email : config('mail.from.name')
            )
            ->cc($this->emailCc)
            ->replyTo($this->emailReply)
            ->subject($this->emailSubject)
            ->view($this->emailBladeView)
            ->with([
                'certi_lab' => $this->certiLab,
                'PayIn' => $this->costAssessment,
            ]);
    }
}
