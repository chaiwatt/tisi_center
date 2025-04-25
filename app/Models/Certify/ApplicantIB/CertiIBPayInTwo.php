<?php

namespace App\Models\Certify\ApplicantIB;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use HP;
use DB;

use App\User; 
use App\Models\Certify\TransactionPayIn;

class CertiIBPayInTwo extends Model
{
    use Sortable;

    protected $table = "app_certi_ib_pay_in2";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_ib_id',
                            'amount',
                            'amount_fee',
                            'report_date',
                            'status',
                             'detail',
                             'degree',
                             'created_by',
                             'updated_by',
                              'remark','conditional_type', 'start_date_feewaiver','end_date_feewaiver',
                             'amount_fixed','condition_pay'
                            ];
 public function CertiIBCostTo()
 {
     return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
 }
  public function FileAttachPayInTwo1To()
  {
     $tb = new CertiIBPayInTwo;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',1)
                    ->orderby('id','desc');
  }
  public function FileAttachPayInTwo2To()
  {
     $tb = new CertiIBPayInTwo;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2)
                    ->orderby('id','desc');
  }

  public function FileAttachPayInTwo5To()
  {
     $tb = new CertiIBPayInTwo;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',5)
                    ->orderby('id','desc');
   }


  public function FileAttachPayInTwo3To()
  {
     $tb = new CertiIBPayInTwo;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',3)
                    ->orderby('id','desc');
  }
  public function FileAttachPayInTwo4To()
  {
     $tb = new CertiIBPayInTwo;
     return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',4)
                    ->orderby('id','desc');
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
