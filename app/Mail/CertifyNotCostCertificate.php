<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyNotCostCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($certi)
    { 
        $this->email = $certi['email'];
        $this->app_no = $certi['app_no'];
        $this->detail = $certi['detail'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                    ->view('mail/certify.certify_not_cost_certi_ficate')
                    ->with([
                              'app_no' => $this->app_no,
                              'detail' => $this->detail
                           ]);
    }
}
