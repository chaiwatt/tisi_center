<?php

namespace App\Console\Commands;

use HP;
use Storage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\Assessment;
use App\Jobs\QueSendEmailGeneratePayInOneLabJob;
use App\Models\Certify\Applicant\CostAssessment;
use Illuminate\Support\Facades\DB;

class GeneratePayInOneLab extends Command
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    protected $signature = 'command:generate-payin-lab';
    protected $description = 'Generate Pay In for OneLab';

    public function __construct()
    {
        $this->attach_path = 'files/applicants/check_files/';
        parent::__construct();
    }

    public function CheckPayInExpire()
    {
        $this->CancelCertiLab();
        // 
        $today = now(); // กำหนดวันปัจจุบัน

        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->whereNull('status_confirmed') // เงื่อนไข status_confirmed เป็น NULL
            ->whereNotNull('suffix') // เงื่อนไข suffix ต้องไม่เป็น NULL
            ->where('state',1)
            ->where('count','<',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();
        // Return หรือจัดการข้อมูล
        return $expiredTransactionPayIns;
    }

    public function CancelCertiLab()
    {
        $today = now();
        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->where('count','>=',3)
            ->where('state',1)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();

        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();        
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $data = $expiredTransactionPayIn-> ref1;
                $splitData = explode('-', $data); // แยกข้อมูลด้วย '-'
                $lastPart = end($splitData); // ดึงค่าตัวสุดท้าย
                $assessmentId = (int)$lastPart; // แปลงเป็น int
                $assessment = Assessment::find($assessmentId);
                $appCertiLab = $assessment->applicant;

                HP::CancelCertiLab($appCertiLab,'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีชำระภายใน 90 วัน');

            }
        }
    }

    public function handle()
    {
        $arrContextOptions=array();
        $expiredTransactionPayIns = $this->CheckPayInExpire();
     
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $data = $expiredTransactionPayIn->ref1;
                $splitData = explode('-', $data); // แยกข้อมูลด้วย '-'
                $lastPart = end($splitData); // ดึงค่าตัวสุดท้าย
                $assessmentId = (int)$lastPart; // แปลงเป็น int
                $assessment = Assessment::find($assessmentId);
                $appCertiLab = $assessment->applicant;

                $find_cost_assessment   =  $assessment->cost_assessment ;  //  ตารางธรรรมเนียม

                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',1)->first();

                $app_no =  $appCertiLab->app_no;

                if(!is_null($setting_payment)){
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $ao = new CostAssessment;
                    $timestamp = Carbon::now()->timestamp;
                    $refNo = $app_no.'-'.$find_cost_assessment->app_certi_assessment_id.$timestamp;
                    
                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));

                    $api = json_decode($content,false);
                    
                    // if(strpos($setting_payment->data, '127.0.0.1')===0){
                    if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                        
                        $find_cost_assessment->amount_invoice =   $this->storeFilePayin($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                    }else{
                        
                        $find_cost_assessment->amount_invoice =   $this->storeFilePayinDemo($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                    }

                    $find_cost_assessment->file_client_name =   isset($find_cost_assessment->amount_invoice) ? basename($find_cost_assessment->amount_invoice)  : null;
                    
                    $find_cost_assessment->save();

                    HP::TransactionPayIn1($find_cost_assessment->id,$ao->getTable(),'1','1',$api,$app_no.'-'.$find_cost_assessment->app_certi_assessment_id,$timestamp);

                    // ส่ง Job เข้าคิว
                    QueSendEmailGeneratePayInOneLabJob::dispatch($appCertiLab->id,$find_cost_assessment->id);

                    $this->info('Email queued for delivery.');
                 }
            }
        }
        $this->info('ตรวจสอบการขยาย Pay In เสร็จสิ้น');
    } 

    
    public function storeFilePayinDemo($setting_payment, $app_no = 'files_lab', $auditor_id = '')
    {
       $baseUrl = strstr($setting_payment->data, '/api', true);

       $url = $baseUrl. '/images/Payin2.pdf';

       // ดาวน์โหลดเนื้อหา PDF (Demo)
       $pdf_content = file_get_contents($url);

       // dd($pdf_content);
            
        $no  = str_replace("RQ-","",$app_no);
        $no  = str_replace("-","_",$no);
        if ($pdf_content) {
            $attach_path  =  $this->attach_path.$no;
            $fullFileName =  $no.'-'.date('Ymd_hms').'.pdf';
            $storagePath = Storage::put($attach_path.'/'.$fullFileName, $pdf_content);


           $filePath = $attach_path .'/'. $fullFileName;
           if (Storage::disk('ftp')->exists($filePath)) {
            //    dd('File Path on Server: ' . $filePath);
                return  $no.'/'.$fullFileName;
           } else {
              return null;
           }
            
        }else{
            return null;
        }
     }

          // สำหรับเพิ่มรูปไปที่ store
    public function storeFilePayin($setting_payment, $app_no = 'files_lab', $auditor_id = '')
    {
            $arrContextOptions=array();
            if($auditor_id != ''){
                $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no-$auditor_id";
            }else{
                $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no";
            }
            if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                $arrContextOptions["ssl"] = array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                  );
            }


            $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));
            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
        if ($url_pdf) {
            $attach_path  =  $this->attach_path.$no;
            $fullFileName =  $no.'-'.date('Ymd_hms').'.pdf';
            $storagePath = Storage::put($attach_path.'/'.$fullFileName, $url_pdf);
            return  $no.'/'.$fullFileName;
        }else{
            return null;
        }
     }
}
