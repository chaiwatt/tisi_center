<?php

namespace App\Mail\Section5;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageLabSyncMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->applicant_name   = $item['applicant_name'];
        $this->lab_name         = $item['lab_name'];
        $this->start_date       = $item['start_date'];
        $this->url              = $item['url'];
        $this->username         = $item['username'];
        $this->password         = $item['password'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'การแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)';
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject($subject)
                        ->view('mail/Section5.manage_lab_sync')
                        ->with([
                               'subject'          => $subject,
                               'lab_name'         => $this->lab_name,
                               'applicant_name'   => $this->applicant_name,
                               'start_date'       => $this->start_date,
                               'url'              => $this->url,
                               'username'         => $this->username,
                               'password'         => $this->password
                            ]);
    }

}
