<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
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
        
        // dd($transactionPayIns);
        foreach ($transactionPayIns as $transactionPayIn) {
            $ref1 = $transactionPayIn->ref1;
            $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
            // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // แปลง JsonResponse เป็น array
                $resultArray = $result->getData(true);

                // dd($resultArray);
        
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
                        // $this->info("Valid ref1: " . implode(", ", $validRef1));

                        $appCertiLabCostAssessmentId = $transactionPayIn->ref_id;
                        $costAssessment = CostAssessment::find($appCertiLabCostAssessmentId);
                        $amountInvoice = $costAssessment->amount_invoice;
                        $fileClientName = $costAssessment->file_client_name;
                        if($costAssessment->status_confirmed === null){
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
                                $auditor = BoardAuditor::findOrFail($find_cost_assessment->assessment->auditor_id);
                                if(!is_null($auditor)){
                                    if($find_cost_assessment->state == 3){
                                        $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                                    }else{
                                        $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                                    }
                                    $auditor->save();
                                }
                            }
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
        
        
        
        
        
        $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin1 lab เสร็จสิ้น');
    }
}
