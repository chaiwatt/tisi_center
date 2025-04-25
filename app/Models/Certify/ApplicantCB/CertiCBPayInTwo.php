<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use HP;
use DB;

use App\User;
use App\Models\Certify\TransactionPayIn;
class CertiCBPayInTwo extends Model
{
    use Sortable;

    protected $table = "app_certi_cb_pay_in2";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id',
                            'amount',
                            'amount_fee',
                            'report_date',
                            'status',
                             'detail',
                             'degree',
                             'created_by',
                             'updated_by',
                             'amount_fixed',
                             'remark','conditional_type', 'start_date_feewaiver','end_date_feewaiver','condition_pay'
                            ];
  public function CertiCbCostTo()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }
  //ค่าธรรมเนียมคำขอ
  public function FileAttachPayInTwo1To()
  {
     $tb = new CertiCBPayInTwo;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',1)
                    ->orderby('id','desc');
  }
  //ค่าธรรมเนียมใบรับรอง
  public function FileAttachPayInTwo2To()
  {
     $tb = new CertiCBPayInTwo;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2)
                    ->orderby('id','desc');
  }
  //หลักฐานการชำระเงินค่าใบคำขอ
  public function FileAttachPayInTwo3To()
  {
     $tb = new CertiCBPayInTwo;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',3)
                    ->orderby('id','desc');
  }
  //หลักฐานการชำระเงินค่าใบรับรอง
  public function FileAttachPayInTwo4To()
  {
     $tb = new CertiCBPayInTwo;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',4)
                    ->orderby('id','desc');
  }

    //หลักฐานการยกเลิกค่าธรรมเนียม
    public function FileAttachPayInTwo5To()
    {
       $tb = new CertiCBPayInTwo;
       return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                      ->where('table_name',$tb->getTable())
                      ->where('file_section',5)
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
