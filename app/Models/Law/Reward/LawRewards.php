<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Cases\LawCasesForm;
class LawRewards extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_rewards';

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
        'law_case_id','case_number','law_case_payments_id','paid_amount','paid_date','receiptcode','step_froms','government_total','group_total','operate_total',
        'bribe_total','reward_total','status', 'edit_income','edit_proportion','edit_reward',  'created_by','updated_by', 'law_config_reward_id'
    ];
        /*
      User Relation
    */
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

    public function law_reward_staff_lists_many() {
        return $this->hasMany(LawlRewardStaffLists::class, 'law_reward_id','id');
    }

    public function status_list() {
        return [ 1 => 'อยู่ระหว่างคำนวณ', 2 => 'ยืนยันการคำนวณ', 3 => 'อยู่ระหว่างรวบรวมหลักฐานเพื่อเบิกจ่าย', 4 => 'อยู่ระหว่างขอเบิกจ่าย', 5 => 'เบิกจ่ายเรียบร้อย', 99 => 'ฉบับร่าง'];
    }

    public function status_css() {
        return [ 1 => 'text-warning', 2 => 'text-success', 3 => 'text-warning', 4 => 'text-warning', 5 => 'text-success', 99 => 'text-danger'];
    }
    public function getStatusHtmlAttribute() {
        return array_key_exists($this->status, $this->status_list()) ? '<span class="'.$this->status_css()[$this->status].'">'.$this->status_list()[$this->status].'</span>' : '';
    }
    public function getStatusTextAttribute() {
        return array_key_exists($this->status, $this->status_list()) ?  $this->status_list()[$this->status]  : '';
    }

    // ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน
    public function law_calculation1_to() {
        return $this->belongsTo(LawlRewardCalculation1::class, 'id','law_reward_id');
    }
    public function law_calculation1_many() {
        return $this->hasMany(LawlRewardCalculation1::class, 'law_reward_id','id');
    }
    // ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน
    public function law_calculation2_to() {
        return $this->belongsTo(LawlRewardCalculation2::class, 'id','law_reward_id');
    }
    public function law_calculation2_many() {
        return $this->hasMany(LawlRewardCalculation2::class, 'law_reward_id','id');
    }
    // ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล
    public function law_calculation3_to() {
        return $this->belongsTo(LawlRewardCalculation3::class, 'id','law_reward_id');
    }
    public function law_calculation3_many() {
        return $this->hasMany(LawlRewardCalculation3::class, 'law_reward_id','id');
    }

    public function law_case_to(){
        return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }

    public function law_reward_withdraws_detail_to() {
        return $this->belongsTo(LawlRewardWithdrawsDetails::class, 'case_number','case_number');
    }

}
