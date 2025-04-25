<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyAuditFees extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        
        $this->app_no = $item['app_no'];
        $this->url = $item['url'];
        $this->email = $item['email'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                ->view('mail/certify.cerify_audit_fees')
                ->with([
                       'app_no' => $this->app_no,
                       'url' => $this->url,
                       ]);
    }
}
