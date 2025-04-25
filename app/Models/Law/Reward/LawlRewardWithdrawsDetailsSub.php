<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawRewardGroup; 
 
class LawlRewardWithdrawsDetailsSub extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_withdraws_details_sub';

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
        'withdraws_id','withdraws_details_id', 'law_basic_reward_group_id',  'law_reward_recepts_id','name','amount','status','remark','law_reward_staff_lists_id'
    ];
   // ใบสำคัญรับเงิน
       public function law_reward_recepts_to()
       {
           return $this->belongsTo(LawlRewardRecepts::class, 'law_reward_recepts_id', 'id');
       } 
   // ส่วนร่วมในคดี
    public function law_reward_group_to()
    {
        return $this->belongsTo(LawRewardGroup::class, 'law_basic_reward_group_id');
    } 
    public function attach_evidence_file()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','attach_receipt')->orderby('id','desc');
    } 

    public function law_reward_staff_lists_to() {
        return $this->belongsTo(LawlRewardStaffLists::class, 'law_reward_staff_lists_id','id');
    }
}
 