<?php

namespace App\Mail\Mail\Law\Track;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailTrackReceive extends Mailable
{
    use Queueable, SerializesModels;

    private $track;
    private $title;
    private $assign;
    private $user;
    private $url;
    private $deperment_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->track             = $item['track'];
        $this->assign            = $item['assign'];
        $this->user              = $item['user'];
        $this->title             = $item['title'];
        $this->deperment_name    = $item['deperment_name'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'),  config('mail.from.name'));
 
        $mail =   $mail->subject($this->title)
                    ->view('mail.Law.Track.assigns')
                    ->with([
                            'track'            => $this->track,
                            'assign'           => $this->assign,
                            'user'             => $this->user,
                            'title'            => $this->title,
                            'deperment_name'   => $this->deperment_name
                          ]);
         return $mail;
    }
}
