<?php

namespace App\Jobs;
use HP;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\GeneratePayInOneLabJobMail;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Certify\Applicant\CertiLab;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Certify\Applicant\CostAssessment;

class QueSendEmailGeneratePayInOneLabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certiLab;
    protected $costAssessment;
    public function __construct($certiLabId,$costAssessmentId)
    {
        $this->certiLab = CertiLab::find($certiLabId);
        $this->costAssessment   =  CostAssessment::findOrFail($costAssessmentId); 
    }

    // รันคำสั่ง php artisan queue:work (ทำงาน เฉพาะครั้งเดียว และจะหยุดหลังจากงานในคิวเสร็จ) 
    // หรือ php artisan queue:listen (ทำงาน ต่อเนื่อง และจะฟังคิวใหม่ที่เข้ามาตลอดเวลา)
    // การเรียกใน centOs สามารถใช้ 
    // crone เช่น 

    // ใน cron * * * * * php /path/to/your/laravel/project/artisan queue:work --sleep=3 --tries=3 >> /dev/null 2>&1
    // หรือ supervisord ก็ได้ เช่น  [program:laravel-worker] command=php /path/to/your/laravel/project/artisan queue:work --sleep=3 --tries=3

    // check que: 
    public function handle()
    {
      
        $examinerEmails = $this->certiLab->EmailStaff;
        if(count($examinerEmails) !== 0){
            $this->sendEmailGeneratePayInOneLab(
                'สร้าง Payin อัตโนมัติ(ขยายเวลา) สำหรับคำขอ',
                'mail.automail.inform_pay_in_one_lab_examiner',
                 $examinerEmails
            );
        }

        if($this->certiLab->email !== null){
            $this->sendEmailGeneratePayInOneLab(
                'แจ้งค่าบริการในการตรวจประเมิน (ต่ออายุ)',
                'mail.automail.inform_pay_in_one_lab_customer',
                 $this->certiLab->email
            );
        }
    }

    public function sendEmailGeneratePayInOneLab($emailSubject,$emailBladeView,$mailTo)
    {
        $dataMail = [
            '1804'=> 'lab1@tisi.mail.go.th',
            '1805'=> 'lab2@tisi.mail.go.th',
            '1806'=> 'lab3@tisi.mail.go.th'
        ];
        $EMail =  array_key_exists($this->certiLab->subgroup,$dataMail)  
        ? $dataMail[$this->certiLab->subgroup] :'admin@admin.com';

        if(!empty($this->certiLab->DataEmailDirectorLABCC)){
            $mail_cc = $this->certiLab->DataEmailDirectorLABCC;
            array_push($mail_cc, auth()->user()->reg_email);
        }

        $data_app = [
                        'costAssessmentId'=>  $this->costAssessment->id,
                        'email'=>  auth()->user()->email ?? 'admin@admin.com',
                        'certiLabId'=> $this->certiLab->id,
                        'email'=>  !empty($this->certiLab->DataEmailCertifyCenter) 
                                    ? $this->certiLab->DataEmailCertifyCenter : $EMail,
                        'emailCc'=>  !empty($mail_cc) ? $mail_cc :  $EMail,
                        'emailReply' => !empty($this->certiLab->DataEmailDirectorLABReply) 
                                    ? $this->certiLab->DataEmailDirectorLABReply :  $EMail,
                        'emailSubject'=>  $emailSubject,
                        'emailBladeView'=>  $emailBladeView
                    ];

        $html = new  GeneratePayInOneLabJobMail($data_app);
        $mail = Mail::to($mailTo)->send($html);
    }

}
