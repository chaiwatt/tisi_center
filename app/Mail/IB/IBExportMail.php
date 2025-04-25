<?php

namespace App\Mail\IB;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IBExportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->email = $item['email'];
        $this->certi_ib = $item['certi_ib'];
  
        $this->attachs = $item['attachs'];
        $this->url = $item['url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->attachs != ''){ // ส่ง mail พร้อมไฟล์ pdf
            $location =  public_path(). '/uploads/'.$this->attachs; 
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->subject('ใบรับรองหน่วยรับรอง')
                    ->view('mail.IB.export')
                    ->with([
                        'certi_ib' => $this->certi_ib,
                        'url' => $this->url
                        ])
                        ->attach($location, [
                            'as' => 'sample.pdf',
                            'mime' => 'application/pdf',
                        ]);
  
          }else{
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                        ->subject('ใบรับรองหน่วยรับรอง')
                        ->view('mail.IB.export')
                        ->with([
                            'certi_ib' => $this->certi_ib,
                            'url' => $this->url
                            ]);
          }



    }
}
