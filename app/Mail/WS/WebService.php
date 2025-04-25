<?php

namespace App\Mail\WS;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebService extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($item)
    {
        
        $this->topic = $item['topic'];
        $this->subject = $item['subject'];
        $this->learn = $item['learn'];
        $this->content = $item['content'];
        $this->email = $item['email'];
        $this->study = (isset($item['study']) && !empty($item['study'])) ? $item['study'] : 'จึงเรียนมาเพื่อทราบ' ;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // return $this->from( 'nsc@mail.tisi.go.th', 'nsc@mail.tisi.go.th' )
        return $this->from( config('mail.from.address'),config('mail.from.name') ) // $this->email
                    ->view('emails/ws.web_service')
                    ->subject($this->topic)
                    ->with([
                        'subject' => $this->subject,
                        'learn' => $this->learn,
                        'content' => $this->content,
                        'study' => $this->study
                    ]);
    }
}
