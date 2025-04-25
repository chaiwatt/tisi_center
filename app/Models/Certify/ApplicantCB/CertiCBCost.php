<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;
class CertiCBCost  extends Model
{
    protected $table = 'app_certi_cb_costs';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id', 'draft', 'check_status', 'remark', 'vehicle',
                            'status_scope', 'remark_scope', 'created_by', 'updated_by','date'
                          ];

 public function CertiCBCostTo()
 {
     return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
 }
   
 public function UserTo()
 {
     return $this->belongsTo(User::class,'created_by','runrecno');
 }
 public function items() {
  return $this->hasMany(CertiCBCostItem::class, 'app_certi_cost_id');
}
public function CertiCbHistorys()
{
    $tb = new CertiCBCost;
    return $this->hasMany(CertiCbHistory::class, 'ref_id')
              ->where('table_name',$tb->getTable()) 
              ->where('system',4);
}

 //ขอบข่าย
  public function FileAttachCost1()
  {
     $tb = new CertiCBCost;
     return $this->hasMany(CertiCBAttachAll::class, 'ref_id','id')
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
    $details = CertiCBCostItem::select('amount_date','amount')->whereIn('id',$data)->get();
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
 
}
