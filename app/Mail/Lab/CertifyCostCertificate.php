<?php

namespace App\Mail\Lab;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CertifyCostCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    { 
        $this->CertiLab = $item['CertiLab']; 
        $this->PayIn = $item['PayIn'];
        $this->attach = $item['attach'];
        
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
      
        if($this->attach != ''){ // ส่ง mail พร้อมไฟล์ pdf
            if($this->PayIn->conditional_type == 1 ||$this->PayIn->conditional_type == 3){
                $location =  public_path(). '/uploads/files/applicants/check_files/'.$this->attach;
            }else{
                $location =  public_path(). '/uploads/'.$this->attach; 
            }
            return $this->from( config('mail.from.address'), (!empty($this->email)  ? $this->email : config('mail.from.name')) )
                    ->cc($this->email_cc)
                    ->replyTo($this->email_reply)
                    ->subject('แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง')
                    ->view('mail.Lab.pay_in_two')
                    ->with([
                            'certi_lab' => $this->CertiLab,
                            'PayIn' => $this->PayIn,
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
            ->subject('แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง')
            ->view('mail.Lab.pay_in_two')
            ->with([
                    'certi_lab' => $this->CertiLab,
                    'PayIn' => $this->PayIn,
                    'url' => $this->url
            ]);
        }
    }
}
