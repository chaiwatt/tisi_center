<?php

namespace App\Console\Commands;
use HP;
use Storage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\CostCertificate;

class GeneratePayInTwoLab extends Command
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    protected $signature = 'command:generate-payin-two-lab';
    protected $description = 'Generate Pay In for Two Lab';

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
            ->where('state',2)
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
            ->whereNull('status_confirmed')
            ->where('state',2)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();

        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();        
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $appCertiLab = CertiLab::where('app_no',$expiredTransactionPayIn->ref1)->first();
                HP::CancelCertiLab($appCertiLab,'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีชำระภายใน 90 วัน');
            }
        }
    }
    
    public function handle()
    {
        $arrContextOptions=array();
        $expiredTransactionPayIns = $this->CheckPayInExpire();
        $ao = new CostCertificate;
        $attach_path =  $this->attach_path ;
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn)
            {
                $costCertificateId = $expiredTransactionPayIn->ref_id;
                $costcerti = CostCertificate::findOrFail($costCertificateId);
                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',1)->first();
                if(!is_null($setting_payment)){
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $timestamp = Carbon::now()->timestamp;
                    $CertiLab = CertiLab::where('app_no',$expiredTransactionPayIn->ref1)->first();
                    $refNo = $CertiLab->app_no.'-'.$timestamp;

                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));
                    // dd($content);

                    $api = json_decode($content);
                    
                    // $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);

                    // if(strpos($setting_payment->data, '127.0.0.1')===0){
                    if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                        
                        $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);
                    }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                        
                        $costcerti->attach              =   $this->storeFilePayinDemo($setting_payment,$CertiLab->app_no);
                    }

                    $costcerti->attach_client_name  =   basename($costcerti->attach);
                    $costcerti->amount_fixed        =   1000;
                    $costcerti->amount_fee          =  !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                    $costcerti->save();
                    
                    $transaction = HP::TransactionPayIn2($costCertificateId,$ao->getTable(),'1','2',$api,$timestamp);
                    // dd($api);
                    if(HP::checkFileStorage($attach_path.$costcerti->attach)){
                        HP::getFileStoragePath($attach_path.$costcerti->attach);
                    }
               }
            }
        }
        $this->info('ตรวจสอบการขยาย Pay In 2 เสร็จสิ้น');
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
