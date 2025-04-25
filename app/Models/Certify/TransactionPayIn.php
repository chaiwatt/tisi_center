<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use DB;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
use App\Models\Certify\ApplicantCB\CertiCBPayInOne;
use App\Models\Certificate\TrackingPayInOne;
class TransactionPayIn extends Model
{
    use Sortable;
    protected $table      = "app_certi_transaction_pay_in";
    protected $primaryKey = 'id';
    protected $fillable   = [
                                'ref_id', 'table_name','certify','amount','running_no','Ref_1','Ref_2','BarCode','state','created_by','updated_by',
                                'returnCode','appno','bus_name','address','allay','village_no','road','district_id',
                                'amphur_id','province_id','postcode','email','vatid','Perpose','billNo','invoiceStartDate',
                                'invoiceEndDate','allAmountTH','barcodeString','barcodeSub','QRCodeString',
                                'app_certi_assessment_id','amount_bill','status_confirmed','auditor',
                                'BankCode','BillCreateDate','Etc1Data','Etc2Data','InvoiceCode','PaymentDate','ReceiptCode','ReceiptCreateDate','ReconcileDate','SourceID','PayAmountBill','app_no','ref1','suffix','count','CGDRef1'
                            ];
 
    public function getFormatAddressAttribute() {
        $address   = [];
        $address[] = @$this->address;

        if($this->village_no!='' && $this->village_no !='-'  && $this->village_no !='--'){
            $address[] =  "หมู่ที่ " . $this->village_no;
        }
        if($this->allay!='' && $this->allay !='-'  && $this->allay !='--'){
            $address[] = "ซอย "  . $this->allay;
        }
        if($this->road !='' && $this->road !='-'  && $this->road !='--'){
            $address[] =  "ถนน "  . $this->road;
        }
        if($this->province_id!=''){
            $address[] =  "จังหวัด " . $this->province_id;
        }
        if($this->amphur_id!=''){
            $address[] =  "เขต/อำเภอ " . $this->amphur_id;
        }
        if($this->district_id!=''){
            $address[] =  "แขวง/ตำบล " . $this->district_id;
        }
        if($this->postcode!=''){
            $address[] =  "รหัสไปรษณีย " . $this->postcode;
        }
        return implode(' ', $address);
    }

    public function cost_assessment()
    {
        if( (new CostAssessment)->getTable() === $this->table_name ){
            return $this->belongsTo(CostAssessment::class, 'ref_id');
        }
        
    }

    public function certiib_payin_one()
    {
        if( (new CertiIBPayInOne)->getTable() === $this->table_name ){
            return $this->belongsTo(CertiIBPayInOne::class, 'ref_id');
        }
        
    }

    public function certicb_payin_one()
    {
        if( (new CertiCBPayInOne)->getTable() === $this->table_name ){
            return $this->belongsTo(CertiCBPayInOne::class, 'ref_id');
        }
        
    }

    public function tracking_payin_one()
    {
        if( (new TrackingPayInOne)->getTable() === $this->table_name ){
            return $this->belongsTo(TrackingPayInOne::class, 'ref_id');
        }
    }

    public function getDateExaminationAttribute() {
        try {
            $request = '';
            $tables  = [
                        'app_certi_lab_cost_assessments' =>  'app_certi_lab_cost_assessments',
                        'app_certi_ib_pay_in1'           =>  'app_certi_ib_pay_in1',
                        'app_certi_cb_pay_in1'           =>  'app_certi_cb_pay_in1',
                        'app_certi_tracking_pay_in1'     =>  'app_certi_tracking_pay_in1'
                    ];
            if( !is_null($this->table_name)  && !is_null($this->ref_id)  && $this->state == 1 &&  array_key_exists($this->table_name,$tables) ){

                if( in_array( $this->table_name, [ (new CostAssessment)->getTable() ] ) && !empty($this->cost_assessment) ){
                    if( !empty($this->cost_assessment->assessment->board_auditor_to) ){
                        $request =  @$this->cost_assessment->assessment->board_auditor_to->DataBoardAuditorDateMail ;
                    }
                }else if( in_array( $this->table_name, [ (new CertiIBPayInOne)->getTable() ] ) && !empty($this->certiib_payin_one) ){
                    if( !empty($this->certiib_payin_one->CertiIBAuditorsTo->CertiIBAuditorsDateTitle) ){
                        $request = @$this->certiib_payin_one->CertiIBAuditorsTo->CertiIBAuditorsDateTitle;
                    }
                }else if( in_array( $this->table_name, [ (new CertiCBPayInOne)->getTable() ] ) && !empty($this->certicb_payin_one) ){
                    if( !empty($this->certicb_payin_one->CertiCBAuditorsTo->CertiCBAuditorsDateTitle) ){
                        $request = @$this->certicb_payin_one->CertiCBAuditorsTo->CertiCBAuditorsDateTitle;
                    }
                }else if( in_array( $this->table_name, [ (new TrackingPayInOne)->getTable() ] ) && !empty($this->tracking_payin_one) ){
                    if( !empty($this->tracking_payin_one->auditors_to->CertiAuditorsDateTitle) ){
                        $request = @$this->tracking_payin_one->auditors_to->CertiAuditorsDateTitle;
                    }
                }

              
            //     if($this->table_name == 'app_certi_lab_cost_assessments'){  // lab
            //         $pay_in  =  CostAssessment::findOrFail($this->ref_id);
            //         if(!empty($pay_in)){
            //             if(!empty($pay_in->assessment)){
            //                 $assessment =  $pay_in->assessment;
            //                 if(!empty($assessment->board_auditor_to)){
            //                     $board_auditor_to  =  $assessment->board_auditor_to;
            //                     if(!empty($board_auditor_to)){
            //                         $request =   @$board_auditor_to->DataBoardAuditorDateMail ;
            //                     }
            //                 }
            //             }
            //         }
            //     }else if($this->table_name == 'app_certi_ib_pay_in1'){  // ib
            //         $pay_in  =  CertiIBPayInOne::findOrFail($this->ref_id);
            //         $request =  !empty($pay_in->CertiIBAuditorsTo->CertiIBAuditorsDateTitle) ?  $pay_in->CertiIBAuditorsTo->CertiIBAuditorsDateTitle : '';
            //     }else if($this->table_name == 'app_certi_cb_pay_in1'){  // cb
            //         $pay_in  =  CertiCBPayInOne::findOrFail($this->ref_id);
            //         $request =  !empty($pay_in->CertiCBAuditorsTo->CertiCBAuditorsDateTitle) ?  $pay_in->CertiCBAuditorsTo->CertiCBAuditorsDateTitle : '';
            //     }else if($this->table_name == 'app_certi_tracking_pay_in1'){  // ติดตาม lab,cb,ib
            //         $pay_in  =  TrackingPayInOne::findOrFail($this->ref_id);
            //         $request =  !empty($pay_in->auditors_to->CertiAuditorsDateTitle) ?  $pay_in->auditors_to->CertiAuditorsDateTitle : '';
            //     }
            }
            return $request;
        } catch (\Exception $e) {
            return  '';
        }
    }

}
