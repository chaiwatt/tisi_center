<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyEmailAssessment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->name = $item['name'];
        $this->app_no = $item['app_no'];
        $this->reg_email = $item['reg_email'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->reg_email
                ->view('mail/certify.certify_email_assessment')
                ->with(['name' => $this->name,
                       'app_no' => $this->app_no]);
    }
}
