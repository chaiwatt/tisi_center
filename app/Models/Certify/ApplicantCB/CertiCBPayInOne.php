<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use HP;
use DB;
use App\Models\Certify\TransactionPayIn;
use App\User;

class CertiCBPayInOne extends Model
{
    use Sortable;

    protected $table = "app_certi_cb_pay_in1";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id',
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
  public function CertiCbCostTo()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }
  public function CertiCBAuditorsTo()
  {
      return $this->belongsTo(CertiCBAuditors::class,'auditors_id');
  }
    // หลักฐานการชำระ จนท.
   public function FileAttachPayInOne1To()
  {
     $tb = new CertiCBPayInOne;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',1)
                    ->orderby('id','desc');
  }
  // หลักฐานการชำระ ผปก.
  public function FileAttachPayInOne2To()
  {
     $tb = new CertiCBPayInOne;
     return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2)
                    ->orderby('id','desc');
  }
  public function UserTo()
  {
      return $this->belongsTo(User::class,'created_by','runrecno');
  }
  public function getUserToContactAttribute() {

   if(!is_null($this->UserTo)){
      $html = $this->UserTo->reg_fname ?? '-'." ".$this->UserTo->reg_lname ?? '-';
      $html .= '<br>';
      $html .= 'มือถือ : '.$this->UserTo->reg_phone ?? '-';
      $html .= '<br>';
      $html .= 'โทรศัพท์ : '.$this->UserTo->reg_wphone ?? '-';
      $html .= '<br>';
      $html .= 'E-mail : '.$this->UserTo->reg_email ?? '-';
      $html .= '<br>';
      $html .= 'ตำแหน่งงาน : '.$this->UserTo->subdepart->sub_departname ?? '-';
     return  $html ?? '-';
   }
      return    '-';
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
