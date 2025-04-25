<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\SubDepartment;
use App\Models\Law\Basic\LawDepartment;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawRewardGroup; 
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesPayments;
class LawlRewardStaffLists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_staff_lists';

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
        'law_case_id','case_number','law_reward_id', 'depart_type','depart_name','sub_department_id','basic_department_id','taxid','name','address','mobile','email','basic_bank_id','basic_bank_name','bank_account_name','bank_account_number','basic_reward_group_id','created_by','updated_by'
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

    // กอง/กลุ่ม (กรณีภายใน)
    public function sub_department()
    {
        return $this->belongsTo(SubDepartment::class, 'sub_department_id', 'sub_id');
    } 
    //  กอง/กลุ่ม (กรณีภายนอก)
    public function law_deparment(){
        return $this->belongsTo(LawDepartment::class, 'basic_department_id', 'id');
    }
    // ส่วนร่วมในคดี
    public function law_reward_group_to()
    {
        return $this->belongsTo(LawRewardGroup::class, 'basic_reward_group_id', 'id');
    }  

    // คำนวณสินบน
    public function law_reward_to()
    {
        return $this->belongsTo(LawRewards::class, 'law_reward_id', 'id');
    } 


    public function file_law_attach_calculations_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','attach_calculations')->orderby('id','desc');
    }
    // ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน
    public function law_calculation2_to() {
        return $this->belongsTo(LawlRewardCalculation2::class, 'law_reward_id','law_reward_id')->where('basic_division_type_id','3');
    }
    public function law_calculation2_many() {
        return $this->hasMany(LawlRewardCalculation2::class, 'law_reward_id','law_reward_id')->where('basic_division_type_id','3');
    }

    // ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล
    public function law_calculation3_to() {
        return $this->belongsTo(LawlRewardCalculation3::class, 'law_reward_id','law_reward_id')->where('law_basic_reward_group_id',$this->basic_reward_group_id);
    }
    public function law_calculation3_many() {
        return $this->hasMany(LawlRewardCalculation3::class, 'law_reward_id','law_reward_id')->where('law_basic_reward_group_id',$this->basic_reward_group_id);
    }
    public function law_calculation3_no_many() {
        return $this->hasMany(LawlRewardCalculation3::class, 'law_reward_id','law_reward_id');
    }

    public function getAwardeesNameAttribute(){
        $result = '';
        if(count($this->law_calculation3_many) > 0){
            $result =  $this->law_calculation3_many->pluck('name','name')->implode(', ');
        }
        return  $result;
    }
   
    // ใบสำคัญรับเงิน
    public function law_reward_recepts_detail_to()
    {
        return $this->belongsTo(LawlRewardReceptsDetails::class, 'id', 'law_reward_staff_lists_id')->orderby('id','desc');
    } 
    public function law_reward_recepts_detail_many() {
        return $this->hasMany(LawlRewardReceptsDetails::class, 'law_reward_staff_lists_id','id');
    }
    public function law_reward_recepts_detail_case_number_to()
    {
        return $this->belongsTo(LawlRewardReceptsDetails::class, 'case_number', 'case_number');
    }
    public function law_reward_withdraws_detail_case_number_to()
    {
        return $this->belongsTo(LawlRewardWithdrawsDetails::class, 'case_number', 'case_number');
    }
    public function getRewardReceptsToAttribute(){
        $recepts = '';
        if(!empty($this->law_reward_recepts_detail_many->last())){
               $detail   = $this->law_reward_recepts_detail_many->last();
               if(!empty($detail)){
                    $recepts_detail =  $detail->law_reward_recepts_to;
                    if(!empty($recepts_detail) && is_null($recepts_detail->cancel_by)){
                        $recepts =  $recepts_detail;
                    }
               }
        }
        return  $recepts;
    }
    

    public function law_case_to(){
        return $this->belongsTo(LawCasesForm::class, 'law_case_id');
      }

     // ข้อมูลการชำระเงินค่าปรับ
    public function law_cases_payments_to()
    {
         return $this->belongsTo(LawCasesPayments::class,'law_case_id','ref_id')->where('ref_table',(new LawCasesForm)->getTable())->where('paid_status','2')->orderby('id','desc');
    }
}

