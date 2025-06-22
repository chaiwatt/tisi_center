<?php

namespace App\Mail\Certify;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingAppointment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->mail_header = $item['mail_header'];
        $this->mail_subject  = $item['mail_subject'];
        $this->order_book_url  = $item['order_book_url'];
        $this->mail_body = str_replace("\r\n", '<br>', $item['mail_body']);
        
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
                        ->view('mail.certify.meeting_appointment')
                        ->with([
                               'mail_subject'  => $this->mail_subject,
                               'mail_header'  => $this->mail_header,
                               'mail_body'   => $this->mail_body,
                               'order_book_url'   => $this->order_book_url
                              ]);
    }
}
