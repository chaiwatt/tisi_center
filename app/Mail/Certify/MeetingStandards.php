<?php

namespace App\Mail\Certify;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingStandards extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->committee_special = $item['committee_special'];
        $this->meeting_standard  = $item['meeting_standard'];
        $this->mail_subject  = !empty($item['mail_subject'])? $item['mail_subject'] : 'ขอแจ้งนัดหมายการประชุมการกำหนดมาตรฐานการตรวจสอบและรับรอง';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),   config('mail.from.name'))
                        ->subject($this->mail_subject)
                        ->view('mail.certify.meeting_standards')
                        ->with([
                               'committee_special'  => $this->committee_special,
                               'meeting_standard'   => $this->meeting_standard
                              ]);
    }
}
