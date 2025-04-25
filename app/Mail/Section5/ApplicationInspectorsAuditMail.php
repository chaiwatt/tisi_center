<?php

namespace App\Mail\Section5;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationInspectorsAuditMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->applicant_name     = $item['applicant_name'];
        $this->application_no     = $item['application_no'];
        $this->audit_date         = $item['audit_date'];
        $this->audit_result       = $item['audit_result'];
        $this->audit_remark       = $item['audit_remark'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'แจ้งผลการตรวจประเมิน';
        $body =   '';
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('แจ้งผลการตรวจประเมินผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม')
                        ->view('mail/Section5.application_inspector_audit')
                        ->with([
                               'subject'          => $subject,
                               'body'             => $body,
                               'name'             => $this->applicant_name,
                               'application_no'   => $this->application_no,
                               'audit_date'       => $this->audit_date,
                               'audit_result'     => $this->audit_result,
                               'audit_remark'     => $this->audit_remark,
                            ]);
    }

}
