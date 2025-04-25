<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\RegisterMail;

class RegisterMail extends Mailable
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
        $this->email = $item['email']; 
        $this->link = $item['link']; 
        $this->username = $item['username']; 
        $this->password = $item['password']; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                    ->view('mail/register')
                    ->subject('แจ้งการลงทะเบียนสำเร็จ')
                    ->with([
                        'name' => $this->name,
                        'link' => $this->link,
                        'username' => $this->username,
                        'password' => $this->password
                    ]);
    }
}
