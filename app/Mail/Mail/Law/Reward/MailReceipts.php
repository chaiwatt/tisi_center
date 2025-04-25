<?php

namespace App\Mail\Mail\Law\Reward;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailReceipts extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->staff_lists      = $item['staff_lists'];
        $this->recepts          = $item['recepts'];
        $this->title            = $item['title'];
        $this->attachs          = $item['attachs'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'),  config('mail.from.name'));
        if($this->attachs != ''){ // ส่ง mail พร้อมไฟล์ pdf
            $location =  public_path(). '/uploads/'.$this->attachs; 
            $mail =    $mail->attach($location);
        }
           $mail =   $mail->subject($this->title)
                        ->view('mail.Law.Reward.receipts')
                       ->with([
                                'staff_lists'       => $this->staff_lists,
                                'recepts'           => $this->recepts,
                                'title'             => $this->title
                               ]);
         return $mail;
    }
}
