<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\CostCertificate;
use App\Http\Controllers\API\Checkbill2Controller;
use App\Models\Certify\Applicant\CertiLabExportMapreq;

class CheckLabPayInTwo extends Command
{
    public function callCheckBill($ref1)
    {
         // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'ref1'

        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }
    protected $signature = 'check:lab-payin-two';
    protected $description = 'ตรวจสอบการชำระเงินระบบ epayment ของ payin2 lab';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        // type = 2 ใบเสร็จ
        $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',2)->first();

        $arrContextOptions = array();
  

        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
            $arrContextOptions["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
    
        $today = now(); // กำหนดวันปัจจุบัน

        $now = Carbon::now();

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $now)
            ->where('invoiceEndDate', '>=', $now)
            ->whereNull('status_confirmed')
            ->where('state',2)
            ->where('count','<=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%')
                      ->orWhere('ref1', 'like', 'CAL%');
            })
            ->get();
        

        foreach ($transactionPayIns as $transactionPayIn) {
            $ref1 = $transactionPayIn->ref1;
            $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
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
                        $ref1List = array_map(function ($item) {
                            return isset($item['ref1']) ? $item['ref1'] : null;
                        }, $response);
        
                        // กรองเฉพาะ ref1 ที่ไม่เป็น null
                        $validRef1 = array_filter($ref1List);
        
                        // แสดงผล ref1 ทั้งหมด
                        $this->info("Valid ref1: " . implode(", ", $validRef1));

                        $appCertiLabCostCertificateId = $transactionPayIn->ref_id;
                        $costCertificate = CostCertificate::find($appCertiLabCostCertificateId);
                        $amountInvoice = $costCertificate->invoice;
                        $fileClientName = $costCertificate->invoice_client_name;
                        if($costCertificate->status_confirmed === null){
                            CostCertificate::find($appCertiLabCostCertificateId)->update([
                                'invoice' => $amountInvoice,
                                'invoice_client_name' => $fileClientName,
                            ]);

                        $find_certi_lab = CertiLab::findOrFail($costCertificate->app_certi_lab_id);
                        $find_certi_lab->status = 24; //แจ้งหลักฐานการชำระค่าใบรับรอง
                        $find_certi_lab->save();

                        $status_confirmed = 1;
                        $costcerti =   CostCertificate::findOrFail($appCertiLabCostCertificateId);
                        $attach =  $costcerti->attach ?? null ;//
                        $invoice =  $costcerti->invoice ?? null ;//
                        $costcerti->update([
                                            'status_confirmed'  =>  1 ?? 2,
                                            'detail'            =>  null,
                                            'condition_pay'     =>   null,
                                            ]);
        
                        $CertiLab = CertiLab::findOrFail($costCertificate->app_certi_lab_id);
                        if($CertiLab->purpose_type == 1 || (!is_null($CertiLab) && is_null($CertiLab->certificate_export_to2)) ){ // ขอใบรับรอง
                            $CertiLab->update([ 'status' =>25 ]);   // ยืนยันการชำระเงินค่าใบรับรอง
                        }else{
                                $CertiLab->update([ 'status' =>28 ]);   // ออกใบรับรอง และ ลงนาม
                        }
                        
                        // เงื่อนไขเช็คมีใบรับรอง 
                        $this->save_certilab_export_mapreq( $CertiLab );
    
                    
                        // $ao = new CostCertificate;
                        // $detail_costcerti =   CostCertificate::select('amount','amount_fee','notification_date','detail','start_date_feewaiver','end_date_feewaiver','remark','conditional_type')
                        //                                 ->where('id',$costcerti->id)
                        //                                 ->orderby('id','desc')
                        //                                 ->first();
                        // CertificateHistory::create([
                        //                             'app_no'        => $CertiLab->app_no ?? null,
                        //                             'system'        => 6, // Pay-In ครั้งที่ 2
                        //                             'table_name'    => $ao->getTable(),
                        //                             'ref_id'        => $costcerti->id,
                        //                             'details'       =>  json_encode($detail_costcerti) ?? null,
                        //                             'status'        => $status_confirmed ?? null,
                        //                             'attachs'       => $attach ?? null,
                        //                             'attachs_file'  => $invoice ?? null,
                        //                             'created_by'    =>  448
                        //                         ]);

                        }

                    } else {
                        $this->info("Response is empty or not an array.");
                    }
                } else {
                    $this->info("No valid message or response.");
                }
            } else {
                $this->info("Invalid response type. Expected JsonResponse.");
            }
        }
        
        
        
        
        
        $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin2 lab เสร็จสิ้น');
    }

    private function save_certilab_export_mapreq($certi_lab)
    {
          $app_certi_lab = CertiLab::with([
                                                    'certificate_exports_to' => function($q){
                                                        $q->whereIn('status',['0','1','2','3','4']);
                                                    }
                                                ])
                                                ->where('created_by', $certi_lab->created_by)
                                                ->whereNotIn('status', ['0','4'])
                                                ->where('standard_id', $certi_lab->standard_id)
                                                ->where('lab_type', $certi_lab->lab_type)
                                                ->first();
         // if(!Is_null($app_certi_lab)){
         if($app_certi_lab !== null){    
             $certificate_exports_id = !empty($app_certi_lab->certificate_exports_to->id) ? $app_certi_lab->certificate_exports_to->id : null;
             // dd($certificate_exports_id);
             //  if(!Is_null($certificate_exports_id)){
             if($certificate_exports_id !== null){
                  $mapreq =  CertiLabExportMapreq::where('app_certi_lab_id',$certi_lab->id)->where('certificate_exports_id', $certificate_exports_id)->first();
                  if(Is_null($mapreq)){
                      $mapreq = new  CertiLabExportMapreq;
                  }
                  $mapreq->app_certi_lab_id       = $certi_lab->id;
                  $mapreq->certificate_exports_id = $certificate_exports_id;
                  $mapreq->save();
                  $this->info("CertiLabExportMapreq created");
              }else
              {
                $this->info("CertiLabExportMapreq not create");
              }
         }
    }
}