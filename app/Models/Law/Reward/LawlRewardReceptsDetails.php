<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
 
class LawlRewardReceptsDetails extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_recepts_details';

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
        'law_reward_recepts_id','case_number','item','amount','created_by','updated_by','law_reward_staff_lists_id'
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
    
    // ใบสำคัญรับเงิน
    public function law_reward_recepts_to()
    {
        return $this->belongsTo(LawlRewardRecepts::class, 'law_reward_recepts_id', 'id');
    } 
 
    public function law_reward_staff_lists_to() {
        return $this->belongsTo(LawlRewardStaffLists::class, 'law_reward_staff_lists_id','id');
    }

    public function law_reward_recepts_details_many() {
        return $this->hasMany(LawlRewardReceptsDetails::class, 'case_number','case_number')
                                                    ->with(['law_reward_recepts_to'])  
                                                    ->whereHas('law_reward_recepts_to', function ($query2) {
                                                        return  $query2->WhereIn('status',['1','2'])->WhereNull('cancel_by');
                                                    })
                                                    ->groupBy('case_number')
                                                    ->groupBy('law_reward_staff_lists_id') ;
    }
    // 
    public function law_reward_recepts_details2_many() {
        return $this->hasMany(LawlRewardReceptsDetails::class, 'case_number','case_number')
                                                    ->with(['law_reward_recepts_to'])  
                                                    ->whereHas('law_reward_recepts_to', function ($query2) {
                                                        return  $query2->WhereIn('status',['2'])->WhereNull('cancel_by');
                                                    })
                                                    ->groupBy('case_number')
                                                    ->groupBy('law_reward_staff_lists_id') ;
    }
    
    public function law_reward_withdraws_detail_to() {
        return $this->belongsTo(LawlRewardWithdrawsDetails::class, 'case_number','case_number');
    }


}
 