<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\File\AttachFileLaw;
 
class LawlRewardWithdraws extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_withdraws';

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
    protected $fillable = [
        'reference_no','plan_name','cost_center','category','year_code','activity_main_name','activity_main_code','activity_small_name','activity_small_code','forerunner_id','status','filter_type','filter_case_number', 
        'filter_paid_date_month','filter_paid_date_year', 'filter_paid_date_start','filter_paid_date_end','check_file','approve_date','approve_remark','approve_status','approve_emails','approve_by','approve_at','created_by','updated_by'
    
    ];
    protected $casts = [ 'approve_emails' => 'array'];

        /*
      User Relation
    */
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function forerunner_created(){
        return $this->belongsTo(User::class, 'forerunner_id');
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

    public function status_list() {
        return [ 1 => 'อยู่ระหว่างเบิกจ่าย', 2 => 'เบิกจ่ายเรียบร้อย'];
    }

    public function status_css() {
        return [ 1 => 'text-warning', 2 => 'text-success'];
    }
    public function getStatusHtmlAttribute() {
        return array_key_exists($this->status, $this->status_list()) ? '<span class="'.$this->status_css()[$this->status].'">'.$this->status_list()[$this->status].'</span>' : '';
    }

    public function getStatusTextAttribute() {
        return array_key_exists($this->status, $this->status_list()) ?  $this->status_list()[$this->status] : '';
    }

    public function  withdraws_list() {
        return [ 1 => 'รายคดี', 2 => 'รายเดือน', 3 => 'ช่วงวันที่'];
    }
    public function getWithdrawsTypeTextAttribute() {
        return array_key_exists($this->filter_type, $this->withdraws_list()) ?  $this->withdraws_list()[$this->filter_type]  : '';
    }
    
    
    public function law_reward_withdraws_detail_many() {
        return $this->hasMany(LawlRewardWithdrawsDetails::class, 'withdraws_id','id');
    }

    public function attach_file()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','evidence')->orderby('id','desc');
    }
    
    // แจ้งเตือนไปยังเมล
    public function getEmailsTitleAttribute() {
        $emails = [];
          if(count($this->law_reward_withdraws_detail_many) > 0){
              $details  = $this->law_reward_withdraws_detail_many;
              foreach($details as $item){
                  $subs  = $item->law_reward_withdraws_detail_sub_many;
                   if(count($subs) > 0){
                        foreach($subs as $item1){
                            $email = !empty($item1->law_reward_staff_lists_to->email) ?  $item1->law_reward_staff_lists_to->email : '';
                            if(!empty($email) && !in_array($email,$emails)){
                                $emails[] = $email;
                            }
                        }
                   }
              }
          }
          return $emails;
    }

}
 