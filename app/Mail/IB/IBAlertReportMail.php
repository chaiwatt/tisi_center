<?php

namespace App\Mail\IB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IBAlertReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->email = $item['email'];
        $this->app_no = $item['app_no'];
        $this->name = $item['name'];
        $this->number = $item['number'];
        $this->url = $item['url'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                        ->subject('แจ้งเตือนยืนยันใบรับรองหน่วยตรวจสอบ')
                        ->view('mail/IB.alert_report')
                        ->with([
                               'app_no' => $this->app_no,
                               'name' => $this->name,
                               'number' => $this->number,
                               'url' => $this->url
                              ]);
    }
}
