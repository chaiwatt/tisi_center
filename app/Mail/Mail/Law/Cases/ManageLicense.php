<?php

namespace App\Mail\Mail\Law\Cases;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageLicense extends Mailable
{
    use Queueable, SerializesModels;
    private $case;
    private $result;
    private $license_result;
    private $attachs;
    private $title;
    private $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->case             = $item['case'];
        $this->result           = $item['result'];
        $this->license_result   = $item['license_result'];
        $this->title            = $item['title'];
        $this->attachs          = $item['attachs'];
        $this->url              = $item['url'];
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
                        ->view('mail.Law.Cases.manage_license')
                       ->with([
                                'case'              => $this->case,
                                'url'               => $this->url,
                                'result'            => $this->result,
                                'license_result'    => $this->license_result,
                                'title'             => $this->title
                               ]);
         return $mail;


    }
}
