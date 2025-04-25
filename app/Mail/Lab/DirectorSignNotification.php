<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DirectorSignNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct($item)
    {
        $this->url = $item['url'];
    }

    public function build()
    {
        return $this->from( config('mail.from.address'), config('mail.from.name') )
        ->subject('แจ้งลงนามใบรับรอง')
        ->view('mail.Lab.mail_director_sign_notification')
        ->with([
                'url' => $this->url,
             ]);
    }
}
