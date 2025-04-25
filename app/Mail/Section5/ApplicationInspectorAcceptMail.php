<?php

namespace App\Mail\Section5;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationInspectorAcceptMail extends Mailable
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
                        ->subject('แจ้งผลการตรวจสอบคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม')
                        ->view('mail/Section5.application_inspector_accept')
                        ->with([
                               'subject'          => $subject,
                               'body'             => $body,
                               'name'             => $this->applicant_name,
                               'application_no'   => $this->application_no,
                               'application_date' => $this->application_date,
                               'description'      => $this->description,
                               'accept_date'      => $this->accept_date,
                               'operation_date'   => $this->operation_date
                            ]);
    }

    private function get_subject($status){
        $subjects = [
                     '2' => 'แจ้งผลตรวจสอบคำขอ - คำขอไม่เข้าเกณฑ์ประเมิน',
                     '3' => 'แจ้งผลตรวจสอบคำขอ - ขอเอกสารเพิ่มเติม',
                     '4' => 'แจ้งผลตรวจสอบคำขอ - รับคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน',
                     '6' => 'แจ้งผลตรวจสอบคำขอ - รับคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน'
                    ];
        return array_key_exists($status, $subjects) ? $subjects[$status] : null ;
    }

    public function get_body($status){
        $bodys =    ['2' => 'ตามที่ท่านได้ยื่นคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม เจ้าหน้าที่ได้ตรวจสอบคำขอ หมายเลขคำขอ {application_no} และพบว่าไม่เข้าเกณฑ์ประเมินการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม เมื่อ {operation_date}',
                     '3' => 'ตามที่ท่านได้ยื่นคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม หมายเลขคำขอ {application_no} เมื่อ {application_date} เจ้าหน้าที่ได้ตรวจสอบคำขอแล้วและขอแจ้งให้ท่านดำเนินการปรับปรุงแก้ไข/แนบเอกสารเพิ่มเติม
                            <br> &nbsp; &nbsp; &nbsp; โดยมีรายละเอียดดังนี้ {description}
                            ',
                     '4' => 'ตามที่ท่านได้ยื่นคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม เจ้าหน้าที่ได้ตรวจสอบคำขอ และรับคำขอ หมายเลขคำขอ {application_no}  เมื่อ {accept_date} เรียบร้อยแล้ว',
                     '6' => 'ตามที่ท่านได้ยื่นคำขอรับการขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม เจ้าหน้าที่ได้ตรวจสอบคำขอ และรับคำขอ หมายเลขคำขอ {application_no}  เมื่อ {accept_date} เรียบร้อยแล้ว'
                    ];
        return array_key_exists($status, $bodys) ? $bodys[$status] : null ;
    }

}
