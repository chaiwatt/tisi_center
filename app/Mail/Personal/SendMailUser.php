<?php

namespace App\Mail\Personal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->operater_name = $item['operater_name'];
        $this->invite = $item['invite'];
        $this->quality = $item['quality'];
        $this->information = $item['information'];
        $this->username = $item['username'];
        $this->password = $item['password'];
        $this->sender_name = $item['sender_name'];
        $this->file_attach = $item['file_attach'];
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.personal.user_mail')
                    ->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                    ->subject($this->invite)
                    ->with([
                        'operater_name' => $this->operater_name,
                        'invite' => $this->invite,
                        'quality' => $this->quality,
                        'information' => $this->information,
                        'username' => $this->username,
                        'password' => $this->password,
                        'file_attach' => $this->file_attach,
                   ]);
    }
}
