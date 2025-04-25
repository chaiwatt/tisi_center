<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\Certify\TransactionPayIn;

use App\Models\Certify\ApplicantIB\CertiIb;
use App\Http\Controllers\API\Checkbill2Controller;

use App\Models\Certify\ApplicantIB\CertiIBPayInTwo;

use App\Models\Certify\ApplicantIB\CertiIBAttachAll;

class CheckIbPayInTwo extends Command
{
    public function callCheckBill($ref1)
    {
         // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'ref1'

        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }
    protected $signature = 'check:ib-payin-two';
    protected $description = 'ตรวจสอบการชำระเงินระบบ epayment ของ payin2 ib';
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
        $this->check_payin2_ib();
    }

    public function check_payin2_ib()
    {
    // การทดสอบต้องลด invoiceStartDate ลง 1 วัน
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
          $query->where('ref1', 'like', 'IB%');
      })
      ->get();
    //   dd($transactionPayIns);
      
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
                    $tb = new CertiIBPayInTwo;

                    // $appCertiLabCostCertificateId = $transactionPayIn->ref_id;
                    $PayIn = CertiIBPayInTwo::findOrFail($transactionPayIn->ref_id);
                    $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
                    $certiIBAttachAll = CertiIBAttachAll::where('table_name', $tb->getTable())
                        ->where('app_certi_ib_id', $PayIn->app_certi_ib_id)
                        ->where('ref_id', $PayIn->id)
                        ->orderBy('created_at', 'desc') // หรือกรณีที่ใช้ฟิลด์อื่นในการจัดลำดับ
                        ->first();


                    $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
                    $certi_ib_attach_more                    = new CertiIBAttachAll();
                    $certi_ib_attach_more->app_certi_ib_id   = $certi_ib->id;
                    $certi_ib_attach_more->ref_id            = $PayIn->id;
                    $certi_ib_attach_more->table_name        = $tb->getTable();
                    $certi_ib_attach_more->file              = $certiIBAttachAll->file;
                    $certi_ib_attach_more->file_client_name  = $certiIBAttachAll->file_client_name;
                    $certi_ib_attach_more->file_section      = '2';
                    $certi_ib_attach_more->token             = str_random(16);
                    $certi_ib_attach_more->save();

                    $PayIn->degree = 3 ; 
                    $PayIn->status = null ; 
                    $PayIn->report_date = null ; 
                    $PayIn->status = 2 ; 
                    $PayIn->condition_pay = null ; 
                    $PayIn->save();

                    $certi_ib->status = 17;
                    $certi_ib->save();

                    $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiIBPayInTwo)->getTable())->orderby('id','desc')->first();
                    if(!is_null($transaction_payin)){
                        $transaction_payin->ReceiptCreateDate     =  Carbon::now(); 
                        $transaction_payin->ReceiptCode           =  '123456' ; 
                        $transaction_payin->save();
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
    $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin2 ib เสร็จสิ้น');
    
    }
}
