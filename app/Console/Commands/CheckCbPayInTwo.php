<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Http\Controllers\API\Checkbill2Controller;
use App\Models\Certify\ApplicantCB\CertiCBPayInTwo;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;

class CheckCbPayInTwo extends Command
{
    public function callCheckBill($ref1)
    {
        // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'ref1'

        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }
    protected $signature = 'check:cb-payin-two';
    protected $description = 'ตรวจสอบการชำระเงินระบบ epayment ของ payin2 cb';
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
        Log::info('เริ่มต้นการทำงาน check:cb-payin-two');
        $this->check_payin2_cb();
        Log::info('สิ้นสุดการทำงาน check:cb-payin-two');
    }

    public function check_payin2_cb()
    {
        // การทดสอบต้องลด invoiceStartDate ลง 1 วัน
        $now = Carbon::now();

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $now)
            ->where('invoiceEndDate', '>=', $now)
            ->where(function ($query) {
                $query->where('status_confirmed', 0)
                    ->orWhereNull('status_confirmed');
            })
            ->where('state', 2)
            ->where('count', '<=', 3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'CB%');
            })
            ->get();
        
        Log::info('พบ Transaction PayIn2 ที่ต้องตรวจสอบจำนวน: ' . $transactionPayIns->count() . ' รายการ');

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
                        $tb = new CertiCBPayInTwo;

                        $PayIn = CertiCBPayInTwo::find($transactionPayIn->ref_id);
                        if (!$PayIn) {
                            Log::warning('ไม่พบ CertiCBPayInTwo ID: ' . $transactionPayIn->ref_id . ' สำหรับ ref1: ' . $ref1);
                            continue;
                        }

                        $certi_cb = CertiCb::find($PayIn->app_certi_cb_id);
                        if (!$certi_cb) {
                            Log::warning('ไม่พบ CertiCb ID: ' . $PayIn->app_certi_cb_id . ' สำหรับ PayIn ID: ' . $PayIn->id);
                            continue;
                        }

                        $certiCBAttachAll = CertiCBAttachAll::where('table_name', $tb->getTable())
                            ->where('app_certi_cb_id', $PayIn->app_certi_cb_id)
                            ->where('ref_id', $PayIn->id)
                            ->orderBy('created_at', 'desc')
                            ->first();

                        if ($certiCBAttachAll) {
                            Log::info('กำลังสร้างสำเนา CertiCBAttachAll สำหรับ PayIn ID: ' . $PayIn->id);
                            $certi_cb_attach_more                       = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id    = $certi_cb->id;
                            $certi_cb_attach_more->ref_id             = $PayIn->id;
                            $certi_cb_attach_more->table_name         = $tb->getTable();
                            $certi_cb_attach_more->file               = $certiCBAttachAll->file;
                            $certi_cb_attach_more->file_client_name   = $certiCBAttachAll->file_client_name;
                            $certi_cb_attach_more->file_section       = '2';
                            $certi_cb_attach_more->token              = str_random(16);
                            $certi_cb_attach_more->save();
                        } else {
                            Log::warning('ไม่พบ CertiCBAttachAll สำหรับ PayIn ID: ' . $PayIn->id);
                        }

                        Log::info('กำลังอัปเดตสถานะ PayIn ID: ' . $PayIn->id);
                        $PayIn->degree = 3 ; 
                        $PayIn->status = 2 ; 
                        $PayIn->report_date = null ; 
                        $PayIn->condition_pay = null ; 
                        $PayIn->save();

                        Log::info('กำลังอัปเดตสถานะ CertiCb ID: ' . $certi_cb->id);
                        $certi_cb->status = 17;
                        $certi_cb->save();

                        $transaction_payin  = TransactionPayIn::where('ref_id', $PayIn->id)->where('table_name', (new CertiCBPayInTwo)->getTable())->orderby('id','desc')->first();
                        if(!is_null($transaction_payin)){
                            Log::info('กำลังอัปเดตข้อมูลใบเสร็จสำหรับ TransactionPayIn ID: ' . $transaction_payin->id);
                            $transaction_payin->ReceiptCreateDate   = Carbon::now(); 
                            $transaction_payin->ReceiptCode         = '123456' ; 
                            $transaction_payin->save();
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
        $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin2 cb เสร็จสิ้น');
    }
}
