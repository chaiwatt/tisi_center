<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        Log::info('เริ่มต้นการทำงาน check:lab-payin-two');
        // type = 2 ใบเสร็จ
        
        $now = Carbon::now();

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $now)
            ->where('invoiceEndDate', '>=', $now)
            ->where(function ($query) {
                $query->where('status_confirmed', 0)
                    ->orWhereNull('status_confirmed');
            })
            ->where('state',2)
            ->where('count','<=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%')
                        ->orWhere('ref1', 'like', 'CAL%');
            })
            ->get();

        // dd($transactionPayIns);
        
        Log::info('พบ Transaction PayIn2 (Lab) ที่ต้องตรวจสอบจำนวน: ' . $transactionPayIns->count() . ' รายการ');

        foreach ($transactionPayIns as $transactionPayIn) {
            $ref1 = $transactionPayIn->ref1;
            Log::info('กำลังตรวจสอบ ref1: ' . $ref1);
            $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
            // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // แปลง JsonResponse เป็น array
                $resultArray = $result->getData(true);
                Log::info('Response จาก CheckBill API สำหรับ ref1: ' . $ref1, $resultArray);
        
                // ตรวจสอบค่า message
                if (!empty($resultArray['message']) && $resultArray['message'] === true) {
                    // ดึงค่าทั้งหมดจาก response
                    $response = $resultArray['response'] ?? null;
            
                    // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
                    if (is_array($response) && count($response) > 0) {
                        Log::info('พบการชำระเงินสำหรับ ref1: ' . $ref1);

                        $appCertiLabCostCertificateId = $transactionPayIn->ref_id;
                        $costCertificate = CostCertificate::find($appCertiLabCostCertificateId);

                        if (!$costCertificate) {
                            Log::warning('ไม่พบ CostCertificate ID: ' . $appCertiLabCostCertificateId . ' สำหรับ ref1: ' . $ref1);
                            continue;
                        }

                        if($costCertificate->status_confirmed === null){
                            Log::info('อัปเดตสถานะสำหรับ CostCertificate ID: ' . $appCertiLabCostCertificateId);
                            
                            $costCertificate->update([
                                'status_confirmed'  => 1,
                                'detail'            => null,
                                'condition_pay'     => null,
                            ]);
    
                            $CertiLab = CertiLab::find($costCertificate->app_certi_lab_id);
                            if (!$CertiLab) {
                                Log::warning('ไม่พบ CertiLab ID: ' . $costCertificate->app_certi_lab_id . ' สำหรับ CostCertificate ID: ' . $costCertificate->id);
                                continue;
                            }

                            Log::info('อัปเดตสถานะ CertiLab ID: ' . $CertiLab->id);
                            if($CertiLab->purpose_type == 1 || is_null($CertiLab->certificate_export_to2)){ // ขอใบรับรอง
                                $CertiLab->update(['status' => 25]);  // ยืนยันการชำระเงินค่าใบรับรอง
                            } else {
                                $CertiLab->update(['status' => 28]);  // ออกใบรับรอง และ ลงนาม
                            }
                            
                            // เงื่อนไขเช็คมีใบรับรอง 
                            $this->save_certilab_export_mapreq($CertiLab);

                        } else {
                             Log::info('CostCertificate ID: ' . $appCertiLabCostCertificateId . ' มี status_confirmed อยู่แล้ว ไม่มีการอัปเดต');
                        }

                    } else {
                        Log::info('ไม่พบข้อมูลการชำระเงินใน response สำหรับ ref1: ' . $ref1);
                        $this->info("Response is empty or not an array.");
                    }
                } else {
                    Log::info('CheckBill API แจ้งว่ายังไม่มีการชำระเงินสำหรับ ref1: ' . $ref1);
                    $this->info("No valid message or response.");
                }
            } else {
                Log::error('ได้รับ Response ที่ไม่ใช่ JsonResponse สำหรับ ref1: ' . $ref1);
                $this->info("Invalid response type. Expected JsonResponse.");
            }
        }
        
        $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin2 lab เสร็จสิ้น');
        Log::info('สิ้นสุดการทำงาน check:lab-payin-two');
    }

    private function save_certilab_export_mapreq($certi_lab)
    {
        Log::info('เริ่มต้นการทำงาน save_certilab_export_mapreq สำหรับ CertiLab ID: ' . $certi_lab->id);
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

       if($app_certi_lab !== null){
            $certificate_exports_id = !empty($app_certi_lab->certificate_exports_to->id) ? $app_certi_lab->certificate_exports_to->id : null;
            if($certificate_exports_id !== null){
                $mapreq = CertiLabExportMapreq::where('app_certi_lab_id', $certi_lab->id)->where('certificate_exports_id', $certificate_exports_id)->first();
                if(is_null($mapreq)){
                    $mapreq = new CertiLabExportMapreq;
                    Log::info('สร้าง CertiLabExportMapreq ใหม่สำหรับ CertiLab ID: ' . $certi_lab->id);
                } else {
                    Log::info('อัปเดต CertiLabExportMapreq ที่มีอยู่สำหรับ CertiLab ID: ' . $certi_lab->id);
                }
                $mapreq->app_certi_lab_id       = $certi_lab->id;
                $mapreq->certificate_exports_id = $certificate_exports_id;
                $mapreq->save();
                $this->info("CertiLabExportMapreq saved successfully.");
                Log::info("บันทึก CertiLabExportMapreq ສຳเร็จสำหรับ CertiLab ID: " . $certi_lab->id . " และ CertificateExports ID: " . $certificate_exports_id);
            } else {
                $this->info("Certificate Exports ID is null. CertiLabExportMapreq not created.");
                Log::warning('ไม่พบ Certificate Exports ID สำหรับ CertiLab ID: ' . $certi_lab->id . ' จึงไม่ได้สร้าง CertiLabExportMapreq');
            }
       } else {
           Log::warning('ไม่พบ App Certi Lab ที่ตรงเงื่อนไขสำหรับ CertiLab ID: ' . $certi_lab->id);
       }
    }
}
