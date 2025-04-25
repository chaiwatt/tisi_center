<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyCertificateExport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
 
        $this->certi_lab = $item['certi_lab'];
        $this->export_ib = $item['export_ib'];
        $this->attachs = $item['attachs'];
 
        $this->url = $item['url'];
        $this->email = $item['email'];
        $this->email_cc = $item['email_cc'];
        $this->email_reply = $item['email_reply'];
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
                    ->cc($this->email_cc) 
                    ->replyTo($this->email_reply)
                    ->subject('ใบรับรองห้องปฏิบัติการ')
                     ->view('mail.Lab.export')
                    ->with([
                            'certi_lab' => $this->certi_lab,
                            'export_ib' => $this->export_ib,
                            'url' => $this->url
                            ])
                    ->attach($location, [
                        'as' => 'sample.pdf',
                        'mime' => 'application/pdf',
                    ]);

        }else{
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc) 
                    ->replyTo($this->email_reply)
                    ->subject('ใบรับรองห้องปฏิบัติการ')
                     ->view('mail.Lab.export')
                        ->with([
                                'certi_lab' => $this->certi_lab,
                                'export_ib' => $this->export_ib,
                                'url' => $this->url
                               ]);
        }
      
    }
}
