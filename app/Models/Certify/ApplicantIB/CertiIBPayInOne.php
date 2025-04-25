<?php

namespace App\Models\Certify\ApplicantIB;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use HP;
use DB;

use App\User;
use App\Models\Certify\TransactionPayIn;

class CertiIBPayInOne extends Model
{
    use Sortable;

    protected $table = "app_certi_ib_pay_in1";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_ib_id',
                            'amount',
                            'auditors_id',
                            'start_date',
                            'status',
                             'state',
                             'remark',
                             'created_by',
                             'updated_by',
                             'amount_bill',
                             'detail','conditional_type',
                              'start_date_feewaiver','end_date_feewaiver','condition_pay'
                            ];
 public function CertiIBCostTo()
 {
     return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
 }

// ค่าธรรมเนียมใบตรวจประเมิน
    public function FileAttachPayInOne1To()
  {
     $tb = new CertiIBPayInOne;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',1)
                    ->orderby('id','desc');
  }
  public function FileAttachPayInOne2To()
  {
     $tb = new CertiIBPayInOne;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2)
                    ->orderby('id','desc');
  }
  public function CertiIBAuditorsTo()
  {
      return $this->belongsTo(CertiIBAuditors::class,'auditors_id');
  }

  public function getDateFeewaiverAttribute() {
    $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    $date = '';
    $start_date_feewaiver =  $this->start_date_feewaiver;
    $end_date_feewaiver =  $this->end_date_feewaiver;
  if(!is_null($start_date_feewaiver) &&!is_null($end_date_feewaiver)){
             // ปี
             $StartYear = date("Y", strtotime($start_date_feewaiver)) +543;
             $EndYear = date("Y", strtotime($end_date_feewaiver)) +543;
            // เดือน
            $StartMonth= date("n", strtotime($start_date_feewaiver));
            $EndMonth= date("n", strtotime($end_date_feewaiver));
            //วัน
            $StartDay= date("j", strtotime($start_date_feewaiver));
            $EndDay= date("j", strtotime($end_date_feewaiver));
            if($StartYear == $EndYear){
                if($StartMonth == $EndMonth){
                      if($StartDay == $EndDay){
                        $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                      }else{
                        $date =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                      }
                }else{
                    $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                }
            }else{
                $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
            }
    }
    return $date;
  }
  public function transaction_payin_to() {
    return $this->belongsTo(TransactionPayIn::class, 'id', 'ref_id')->where('table_name',$this->table)->orderby('id','desc');
}
        //   เงื่อนไขการชำระ
        public function getConditionPayNameAttribute() {
        $condition_pay = [  '1'=> 'pay-in เกินกำหนด (ชำระที่ สมอ.)', '2'=> 'ได้รับการยกเว้นค่าธรรมเนียม', '3'=> 'ชำระเงินนอกระบบ, กรณีอื่นๆ' ];
        return  !empty($this->condition_pay)  && array_key_exists($this->condition_pay,$condition_pay) ? $condition_pay[$this->condition_pay]  : '';
        }

}
