<?php
namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyReport extends Mailable
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
        $this->report = $item['report'];
        $this->report_file = $item['report_file'];
     
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
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc)
                    ->replyTo($this->email_reply)
                    ->subject('สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ')
                    ->view('mail.Lab.report')
                    ->with(['certi_lab' => $this->certi_lab,
                            'report_file' => $this->report_file,
                            'url' => $this->url,
                            'report' => $this->report 
                           ]);
    }
}
