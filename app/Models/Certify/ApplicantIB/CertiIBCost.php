<?php

namespace App\Models\Certify\ApplicantIB;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;

class CertiIBCost  extends Model
{
    protected $table = 'app_certi_ib_costs';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_ib_id', 'draft', 'check_status', 'remark', 'vehicle',
                            'status_scope', 'remark_scope','state', 'created_by', 'updated_by'
                          ];

 public function CertiIBCostTo()
 {
     return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
 }
   
 public function UserTo()
 {
     return $this->belongsTo(User::class,'created_by','runrecno');
 }
 public function items() {
  return $this->hasMany(CertiIBCostItem::class, 'app_certi_cost_id');
}
   
public function CertiIbHistorys()
{
    $tb = new CertiIBCost;
    return $this->hasMany(CertiIbHistory::class, 'ref_id')
              ->where('table_name',$tb->getTable()) 
              ->where('system',4);
}

  public function FileAttachCost1()
  {
     $tb = new CertiIBCost;
     return $this->hasMany(CertiIBAttachAll::class, 'ref_id','id')
                 ->select('id','file','file_client_name')
                 ->where('table_name',$tb->getTable())
                 ->where('file_section',1);
  }
     
  public function getMaxAmountDateAttribute() {
    $details =    HP::getArrayFormSecondLevel($this->items->toArray(), 'amount_date');
    if(count($details) > 0) {
    foreach($details  as $item){
        $amount_date = !empty($item) ? $item : 0 ;
        $count_date[] = $amount_date;
    }
    }
    return  max($count_date) ?? '-';
  }
        
  public function getSumAmountAttribute() {
    $data =   HP::getArrayFormSecondLevel($this->items->toArray(), 'id');
    $details = CertiIBCostItem::select('amount_date','amount')->whereIn('id',$data)->get();
    $countItem = 0;
    if(count($details) > 0) {
        foreach($details  as $item){
            $amount_date = !empty($item->amount_date) ? $item->amount_date : 0 ;
            $amount = !empty($item->amount) ? $item->amount : 0 ;
            $countItem += $amount*$amount_date;
        }
    }
    return  number_format($countItem,2) ?? '-';
  }
  public function getStatusTitleAttribute() {
    $list = '';
      if($this->draft == 0){
          $list =  'ฉบับร่าง';
      }else{
        if($this->draft == 1){
            $list =   'ขอความเห็นประมาณค่าใชจ่าย';
        }else{
          if($this->check_status == 1 && $this->status_scope == 1){
            $list =  'เห็นชอบ';
          }else{
             $list =  'ไม่เห็นชอบ';
          }
        }
      }
      return  $list ?? '-';
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
 
}
