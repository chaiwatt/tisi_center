<?php

namespace App\Console\Commands;
use HP;
use App\AttachFile;
use App\CertificateExport;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Certificate\Tracking;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certificate\TrackingHistory;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certificate\TrackingAssessment;
use App\Http\Controllers\API\Checkbill2Controller;

class CheckTrackingLabPayInOne extends Command
{
    public function callCheckBill($ref1)
    {
         // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'ref1'

        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }

    protected $signature = 'check:tracking-lab-payin-one';
    protected $description = 'ตรวจสอบการชำระเงินระบบ epayment ของการติดตาม payin1 lab';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateTrackingLabPayin1();
    }

    public function updateTrackingLabPayin1()
    {
        $today = now(); // กำหนดวันปัจจุบัน

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
            ->where('invoiceEndDate', '>=', $today)
            ->where(function ($query) {
                $query->whereNull('status_confirmed')
                      ->orWhere('status_confirmed', 0);
            })
            ->where('state',1)
            ->where('count','<=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'SurLab%');
            })
            ->get();

        foreach ($transactionPayIns as $transactionPayIn) 
        {
            $ref1 = $transactionPayIn->ref1;
            $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
            // dd($result);
            // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // แปลง JsonResponse เป็น array
                $resultArray = $result->getData(true);
        
                // ตรวจสอบค่า message
                if (!empty($resultArray['message']) && $resultArray['message'] === true) {
                    // ดึงค่าทั้งหมดจาก response
                    $response = $resultArray['response'] ?? null;
        
                    // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
                    if (is_array($response) && count($response) > 0) {
                        // ใช้ array_map เพื่อดึง ref1
            
                        $parts = explode('-', $ref1);
                        $trackingBoardAuditorId = end($parts); 
                        $auditor = TrackingAuditors::findOrFail($trackingBoardAuditorId);
                        $referenceRefno = implode('-', array_slice($parts, 0, -1));

                        $attachFile = AttachFile::where('section',"attach_payin1")
                        ->where('ref_id',$transactionPayIn->ref_id)
                        ->first();
                        // dd($transactionPayIn->id,$attachFile);
                        $this->info($transactionPayIn->ref_id);
                        $url = null;
                        $new_filename = null;
                        $filename = null;
                        if($attachFile != null)
                        {
                            if($attachFile->url != null)
                            {
                                $url = $attachFile->url;
                            }
                        }
                        
                        if ($url != null) {

                            // upload file ด้วยวิธีการจำลองการอัพโหลดเพื่อให้เข้ากับฟังก์ชัน singleFileUploadRefno 
                            if (HP::checkFileStorage($url)) {
                                // ดึงไฟล์ที่ดาวน์โหลดมา
                                $localFilePath = HP::getFileStoragePath($url);

                                $tb       = new TrackingPayInOne;
                                $pay_in   = TrackingPayInOne::find($transactionPayIn->ref_id);
                                // $config   = HP::getConfig();
                                $app_certi_tracking = Tracking::find($pay_in->tracking_id);
                                $taxNumber = $app_certi_tracking->tax_id;

                                $filePath = 'files/trackinglabs/' . $referenceRefno;
                              
                                $localFilePath = HP::getFileStoragePath($url);

                                if (file_exists($localFilePath)) {
                                    // จำลองไฟล์อัปโหลด
                                    $uploadedFile = new \Illuminate\Http\UploadedFile(
                                        $localFilePath,      // Path ของไฟล์
                                        basename($localFilePath), // ชื่อไฟล์
                                        mime_content_type($localFilePath), // MIME type
                                        null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
                                        true                // เป็นไฟล์ที่ valid แล้ว
                                    );
                        
                                    // ใช้ไฟล์ที่จำลองในการอัปโหลด
                                    $file_payin = HP::singleFileUploadRefno(
                                        $uploadedFile,                          // ใช้ไฟล์ที่จำลองแทนไฟล์ input
                                        $filePath,
                                        $taxNumber,
                                        $auditor->no,
                                        'ACC',
                                        $tb->getTable(),
                                        $pay_in->id,
                                        'attachs_file',
                                        null
                                    ); 
                                    unlink($localFilePath);
                                }
                            }
                            TrackingPayInOne::find($transactionPayIn->ref_id)->update([
                                'state' => 2,
                                'status' => null,
                                'remark' => null,
                            ]);

                            $auditor = TrackingAuditors::findOrFail($trackingBoardAuditorId);
                            if(!is_null($auditor) && $pay_in->state == 2){
                                $auditor->step_id = 5; // แจ้งหลักฐานการชำระเงิน
                                $auditor->save();
                            }

                            $PayIn = TrackingPayInOne::find($transactionPayIn->ref_id);
                           
                            $PayIn->remark =  null;
                            $PayIn->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                            $PayIn->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                            $PayIn->save();
                            $assessment  =  TrackingAssessment::where('auditors_id',$PayIn->auditors_id)->first();
                            if(is_null($assessment)){
                                $assessment = new TrackingAssessment;
                            }
                            //  dd(TrackingPayInOne::where('reference_refno',$referenceRefno)->first());
                        
                            $assessment->certificate_type   = 3;
                            $assessment->tracking_id        = $PayIn->tracking_id ?? null;
                            $assessment->reference_refno    = $PayIn->reference_refno ?? null;
                            $assessment->ref_table          = $PayIn->ref_table ?? null;
                            $assessment->ref_id             = $PayIn->ref_id ?? null;
                            $assessment->auditors_id        = $PayIn->auditors_id ?? null;
                            $assessment->name               =  !empty($PayIn->certificate_export_to->CertiLabTo->name) ? $PayIn->certificate_export_to->CertiLabTo->name : null;
                            $assessment->laboratory_name    =  !empty($PayIn->certificate_export_to->CertiLabTo->lab_name) ?  $PayIn->certificate_export_to->CertiLabTo->lab_name  : null;
                            $assessment->save();

                            // สถานะ แต่งตั้งคณะกรรมการ
                            $auditor = TrackingAuditors::findOrFail($PayIn->auditors_id);
                            if(!is_null($auditor)){
                            if($PayIn->state == 3){
                                $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                            }else{
                                $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                            }
                                $auditor->save();
                            }

                            $data = TrackingPayInOne::select('id','conditional_type', 'auditors_id', 'amount_bill', 'start_date', 'status','state', 'remark', 'detail', 'start_date_feewaiver', 'end_date_feewaiver')
                                        ->where('id',$PayIn->id)
                                        ->first();

                            $file = [];
                                if( !empty($PayIn->FileAttachPayInOne1To->url)){
                                    $file['url'] =  $PayIn->FileAttachPayInOne1To->url;
                                }
                                if( !empty($PayIn->FileAttachPayInOne1To->new_filename)){
                                    $file['new_filename'] =  $PayIn->FileAttachPayInOne1To->new_filename;
                                }
                                if( !empty($PayIn->FileAttachPayInOne1To->filename)){
                                    $file['filename'] =  $PayIn->FileAttachPayInOne1To->filename;
                                }  

                                $attachs_file = [];
                                if( !empty($PayIn->FileAttachPayInOne2To->url)){
                                    $attachs_file['url'] =  $PayIn->FileAttachPayInOne2To->url;
                                }
                                if( !empty($PayIn->FileAttachPayInOne2To->new_filename)){
                                    $attachs_file['new_filename'] =  $PayIn->FileAttachPayInOne2To->new_filename;
                                }
                                if( !empty($PayIn->FileAttachPayInOne2To->filename)){
                                    $attachs_file['filename'] =  $PayIn->FileAttachPayInOne2To->filename;
                                }  
                                TrackingHistory::create([ 
                                        'tracking_id'       => $PayIn->tracking_id ?? null,
                                        'certificate_type'  => 3,
                                         'reference_refno'   => $PayIn->reference_refno ?? null,
                                        'ref_table'         =>  (new CertificateExport)->getTable() ,
                                        'ref_id'            =>  $PayIn->ref_id ?? null,
                                        'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                        'system'            => 5, //Pay-In ครั้งที่ 1
                                        'table_name'        => $tb->getTable(),
                                        'refid'             => $PayIn->id,
                                        'status'            => $PayIn->status ?? null,
                                        'details_one'       =>  json_encode($data) ?? null,
                                        'attachs'           => (count($file) > 0) ? json_encode($file) : null,
                                        'attachs_file'      =>  (count($attachs_file) > 0) ? json_encode($attachs_file) : null,
                                        'created_by'        =>  448
                                    ]);
                        }
 
                    } else {
                        $this->info("Response is empty or not an array.");
                    }
                } 
            } 
        }
        $this->info("ตรวจสอบการชำระเงินระบบ epayment ของการตรวจติดตาม payin1 lab เสร็จสิ้น");
    }
}
