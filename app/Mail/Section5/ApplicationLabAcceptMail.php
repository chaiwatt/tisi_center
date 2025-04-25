<?php

namespace App\Mail\Section5;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationLabAcceptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->applicant_name     = $item['applicant_name'];
        $this->application_status = $item['application_status'];
        $this->lab_name           = $item['lab_name'];
        $this->application_no     = $item['application_no'];
        $this->application_date   = $item['application_date'];
        $this->description        = $item['description'];
        $this->accept_date        = $item['accept_date'];
        $this->operation_date     = $item['operation_date'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->get_subject($this->application_status);
        $body    = $this->get_body($this->application_status);
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('แจ้งผลการตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)')
                        ->view('mail/Section5.application_lab_accept')
                        ->with([
                               'subject'          => $subject,
                               'body'             => $body,
                               'name'             => $this->applicant_name,
                               'lab_name'         => $this->lab_name,
                               'application_no'   => $this->application_no,
                               'application_date' => $this->application_date,
                               'description'      => $this->description,
                               'accept_date'      => $this->accept_date,
                            ]);
    }

    private function get_subject($status){
        $subjects = ['2' => 'แจ้งผลตรวจสอบคำขอ - ขอเอกสารเพิ่มเติม',
                     '3' => 'แจ้งผลตรวจสอบคำขอ - รับคำขอรับการแต่งตั้งเป็น LAB',
                     '4' => 'แจ้งผลตรวจสอบคำขอ - รับคำขอรับการแต่งตั้งเป็น LAB',
                     '6' => 'แจ้งผลตรวจสอบคำขอ - ไม่รับคำขอรับการแต่งตั้งเป็น LAB'
                    ];
        return array_key_exists($status, $subjects) ? $subjects[$status] : null ;
    }

    public function get_body($status){
        $bodys =    ['2' => 'ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) ห้องปฏิบัติการ
                             {lab_name}
                             หมายเลขคำขอ {application_no}
                             เมื่อ {application_date}
                             เจ้าหน้าที่ได้ตรวจสอบคำขอแล้ว และขอแจ้งให้ท่านดำเนินการปรับปรุงแก้ไข/แนบเอกสารเพิ่มเติม
                             <br>&nbsp; &nbsp; &nbsp;โดยมีรายละเอียดดังนี้ {description}

                             <br><br>จึงเรียนมาเพื่อโปรดดำเนินการ',
                     '3' => 'ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) ห้องปฏิบัติการ
                             {lab_name} เจ้าหน้าที่ได้ตรวจสอบคำขอ และรับคำขอ หมายเลขคำขอ {application_no}
                             เมื่อ {accept_date} เรียบร้อยแล้ว

                             <br><br>จึงเรียนมาเพื่อทราบ',
                     '4' => 'ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) ห้องปฏิบัติการ
                             {lab_name} เจ้าหน้าที่ได้ตรวจสอบคำขอ และรับคำขอ
                             หมายเลขคำขอ {application_no}
                             เมื่อ {accept_date}
                             เรียบร้อยแล้ว

                             <br><br>จึงเรียนมาเพื่อทราบ',
                     '6' => 'ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) ห้องปฏิบัติการ
                             {lab_name} เจ้าหน้าที่ได้ตรวจสอบคำขอ หมายเลขคำขอ {application_no}
                             และพบว่าไม่สอดคล้องกับการขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)
                             เมื่อ {operation_date}
                             <br><br>จึงเรียนมาเพื่อทราบ'
                    ];
        return array_key_exists($status, $bodys) ? $bodys[$status] : null ;
    }

}
