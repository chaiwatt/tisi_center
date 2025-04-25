<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailCompares extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->case             = $item['case'];
        $this->law_compare      = $item['law_compare'];
        $this->title            = $item['title'];
        $this->attachs          = $item['attachs'];
        $this->file_payin       = $item['file_payin'];
    }


    /** 
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail =  $this->from( config('mail.from.address'),  config('mail.from.name'));
        if($this->attachs != ''){  
            $location =  public_path(). '/uploads/'.$this->attachs; 
            $mail =    $mail->attach($location);
        }
        if($this->file_payin != ''){  
            $file_payin =  public_path(). '/uploads/'.$this->file_payin; 
            $mail =    $mail->attach($file_payin);
        }
           $mail =   $mail->subject($this->title)
                        ->view('mail.Law.Cases.compares')
                       ->with([
                                'case'              => $this->case,
                                'law_compare'       => $this->law_compare,
                                 'title'            => $this->title
                               ]);
         return $mail;
    }
}
