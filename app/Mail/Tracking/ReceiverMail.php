<?php

namespace App\Mail\Tracking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiverMail extends Mailable
{
    use Queueable, SerializesModels;
    private $date_start;
    private $date_end;
    private $certificate;
    private $title;
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->date_start = $item['date_start'];
        $this->date_end = $item['date_end']; 
        $this->certificate = $item['certificate'];
        $this->title = $item['title'];
        $this->name = $item['name'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         
        return $this->from( config('mail.from.address'), config('mail.from.name')) 
                      ->subject('แจ้งการตรวจติดตามใบรับรอง')
                      ->view('mail.Tracking.receiver')
                      ->with([
                              'date_start' => $this->date_start,
                              'date_end' => $this->date_end,
                              'certificate' => $this->certificate,
                              'name' => $this->name,
                              'title' => $this->title
                            ]);
    }
}
