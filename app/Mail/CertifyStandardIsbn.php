<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyStandardIsbn extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {

        $this->standard_full = $item['standard_full'];
        $this->name =  $item['name'];
        $this->url = $item['url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                    ->subject('แจ้งขอเลข ISBN')
                    ->view('mail.certify.certify_standard_isbn')
                    ->with(['standard_full' => $this->standard_full,
                            'name' => $this->name,
                            'url' => $this->url,
                        ]);


    }
}
