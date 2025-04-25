<?php

namespace App\Models\Law\Cases;

use App\User;
 
use Illuminate\Database\Eloquent\Model;

use App\Models\Certify\TransactionPayIn;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Config\LawConfigNotification;
use App\Models\Law\Config\LawConfigNotificationDetail;
class LawCasesPayments extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_payments';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
  
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */

    protected $fillable =
    [
        'ref_table','ref_id', 'status', 'condition_type', 'name', 'start_date','amount_date','end_date','amount','paid_status','paid_date','paid_type',
        'app_certi_transaction_pay_in_id','paid_channel','paid_channel_remark','remark','created_by','updated_by','ordering','cancel_status','cancel_remark','cancel_by','cancel_at' 
    ];

  


    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
      }
    
      public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
      }
    
      public function getCreatedNameAttribute() {
          return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
      }
    
      public function getUpdatedNameAttribute() {
          return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
      }
  
     // ข้อมูลใบแจ้งการชำระ (Pay-in)
     public function law_cases_payments_detail_to(){
        return $this->belongsTo(LawCasesPaymentsDetail::class, 'id','law_case_payments_id');
     }
   
   public function app_certi_transaction_pay_in_to(){
     return $this->belongsTo(TransactionPayIn::class, 'app_certi_transaction_pay_in_id','id');
   }

   // ช่องทางชำระ  
   public function getPaidChannelTextAttribute() {
    $btn = '';
      if( $this->paid_channel == '1' ){
          $btn = 'โอนเงิน';
      }elseif( $this->paid_channel == '2' ){
          $btn = 'เงินสด';
      }elseif( $this->paid_channel == '3' ){
          $btn = 'เช็คธนาคาร (ระบุ)';
       }else{
          $btn = '-';
       }
      return $btn;
   }
    // ใบแจ้งกกรชำระ (Pay-in)
    public function file_law_cases_pay_in_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','attach_payin')->orderby('id','desc');
    }
    // ใบชำระ
    public function file_law_cases_attachs_bill_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','attachs_bill')->orderby('id','desc');
    }

    // ข้อมูล
    public function law_data_to()
    {   
        $datas = '';
        if($this->ref_table ==  (new LawCasesForm)->getTable()){
            $datas  = $this->belongsTo(LawCasesForm::class,'ref_id');
        }
          return $datas;
    }

    public function getNumberOfDaysHtmlAttribute() {
            $btn = '';
          if(!is_null($this->end_date)){

            $end_date =  $this->end_date;

             $day =  ((strtotime($end_date) - strtotime(date('Y-m-d'))) / (60*60*24));
             if(!is_null($day)){
                $config =   LawConfigNotification::where('id','1')->where('state','1')->first();
                if(!empty($config)  && count($config->law_config_notification_details) > 0){
                    $details  =  LawConfigNotificationDetail::select('id','condition','amount','color')->where('law_config_notification_id',$config->id)->orderby('amount','asc')->get();
                    $last = $details->last();
                    $condition = $last->condition;
                    $amount = $last->amount;
               
                    if($condition == '<'){
                        if($amount < $day){
                              $btn = '<span class="badge rounded-pill bg-'.$last->color.'">'.$day.' วัน</span>';
                              goto end;
                        }
                    }else  if($condition == '>'){
                        if($amount > $day){
                              $btn = '<span class="badge rounded-pill bg-'.$last->color.'">'.$day.' วัน</span>';
                              goto end;
                        }
                    } if($condition == '='){
                        if($amount != $day){
                              $btn = '<span class="badge rounded-pill bg-'.$last->color.'">'.$day.' วัน</span>';
                              goto end;
                        }
                    }

                    foreach($details as $item){
                            $condition = $item->condition;
                            $amount = $item->amount;
                            if($condition == '<'){
                                if($day < $amount){
                                      $btn = '<span class="badge rounded-pill bg-'.$item->color.'">'.$day.' วัน</span>';
                                      goto end;
                                }
                            }else   if($condition == '>'){
                                if($day > $amount){
                                      $btn = '<span class="badge rounded-pill bg-'.$item->color.'">'.$day.' วัน</span>';
                                      goto end;
                                }
                            }else   if($condition == '='){
                                if($day == $amount){
                                      $btn = '<span class="badge rounded-pill bg-'.$item->color.'">'.$day.' วัน</span>';
                                      goto end;
                                }
                            }
                    }
                        $btn = '<span class="badge rounded-pill bg-muted">'.$day.' วัน</span>';
                        goto end;
                }else{
                   $btn = '<span class="badge rounded-pill bg-danger">'.$day.' วัน</span>';
                }
             }
          }
          end:
          return $btn;
       }


}
