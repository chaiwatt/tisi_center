<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Cases\LawCasesForm;
class LawlRewardWithdrawsDetails extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_withdraws_details';

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
        'withdraws_id', 'case_number', 'income_number','amount','remark','created_by'
    ];
   
        /*
      User Relation
    */
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'case_number','case_number');
    }
    public function law_rewards(){
        return $this->belongsTo(LawRewards::class, 'case_number','case_number');
    }

    public function law_reward_withdraws_to(){
        return $this->belongsTo(LawlRewardWithdraws::class, 'withdraws_id','id');
    }
    
    public function law_reward_withdraws_detail_sub_many() {
        return $this->hasMany(LawlRewardWithdrawsDetailsSub::class, 'withdraws_details_id','id');
    }

}
