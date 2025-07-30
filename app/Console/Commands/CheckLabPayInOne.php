<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\CostAssessment;
use App\Http\Controllers\API\Checkbill2Controller;

class CheckLabPayInOne extends Command
{

    public function callCheckBill($ref1)
    {
        // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'ref1'

        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }
    protected $signature = 'check:lab-payin-one';
    protected $description = 'ตรวจสอบการชำระเงินระบบ epayment ของ payin1 lab';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('เริ่มต้นการทำงาน check:lab-payin-one');
        // type = 2 ใบเสร็จ
    
        $now = Carbon::now();

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $now)
            ->where('invoiceEndDate', '>=', $now)
            // ->whereNull('status_confirmed')
            ->where(function ($query) {
                $query->where('status_confirmed', 0)
                    ->orWhereNull('status_confirmed');
            })
            ->where('state',1)
            ->where('count','<=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%')
                        ->orWhere('ref1', 'like', 'CAL%');
            })
            ->get();
        
        Log::info('พบ Transaction PayIn1 (Lab) ที่ต้องตรวจสอบจำนวน: ' . $transactionPayIns->count() . ' รายการ');
        
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

                        $appCertiLabCostAssessmentId = $transactionPayIn->ref_id;
                        $costAssessment = CostAssessment::find($appCertiLabCostAssessmentId);

                        if (!$costAssessment) {
                            Log::warning('ไม่พบ CostAssessment ID: ' . $appCertiLabCostAssessmentId . ' สำหรับ ref1: ' . $ref1);
                            continue;
                        }

                        $amountInvoice = $costAssessment->amount_invoice;
                        $fileClientName = $costAssessment->file_client_name;

                        if($costAssessment->status_confirmed === null){
                            Log::info('อัปเดตสถานะสำหรับ CostAssessment ID: ' . $appCertiLabCostAssessmentId);
                            CostAssessment::find($appCertiLabCostAssessmentId)->update([
                                'invoice' => $amountInvoice,
                                'invoice_client_name' => $fileClientName,
                                'state' => 3,
                                'status_confirmed' => 1,
                                'remark' => null,
                                'condition_pay' => null,
                            ]);
    
                            $find_cost_assessment = CostAssessment::find($appCertiLabCostAssessmentId);
                            if(!empty($find_cost_assessment->assessment->auditor_id)){
                                // สถานะ แต่งตั้งคณะกรรมการ
                                $auditor = BoardAuditor::find($find_cost_assessment->assessment->auditor_id);
                                if(!is_null($auditor)){
                                    Log::info('อัปเดต step_id สำหรับ BoardAuditor ID: ' . $auditor->id);
                                    if($find_cost_assessment->state == 3){
                                        $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                                    }else{
                                        $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                                    }
                                    $auditor->save();
                                } else {
                                    Log::warning('ไม่พบ BoardAuditor ID: ' . $find_cost_assessment->assessment->auditor_id);
                                }
                            }
                        } else {
                            Log::info('CostAssessment ID: ' . $appCertiLabCostAssessmentId . ' มี status_confirmed อยู่แล้ว ไม่มีการอัปเดต');
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
        
        $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin1 lab เสร็จสิ้น');
        Log::info('สิ้นสุดการทำงาน check:lab-payin-one');
    }
}
