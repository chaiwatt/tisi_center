<?php

namespace App\Models\Law\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;
use App\Models\Law\Basic\LawArrest;
 

class LawConfigReward extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_config_reward';

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
    protected $fillable = ['title', 'arrest_id', 'operation_id', 'unit_type', 'state', 'created_by', 'updated_by'];

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
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

 
    // การจับกุม
    public function arrest(){
      return $this->belongsTo(LawArrest::class, 'arrest_id');
    }

    public function law_config_reward_sub_many() {
      return $this->hasMany(LawConfigRewardSub::class, 'law_config_reward_id')->orderby('ordering','asc');
  }
    
    

    // การดำเนินการ
    public function getOperationTitleAttribute() {
      $array = [];
      if(!is_null($this->operation_id)){
         $operations = json_decode($this->operation_id,true);
      
         if(!is_null($operations) && $operations != '0'){
            if(in_array('1',$operations)){
              $array[] = 'ทุกกรณี';
            }
            if(in_array('2',$operations)){
              $array[] = 'เปรียบเทียบปรับ';
            }
            if(in_array('3',$operations)){
              $array[] = 'ส่งดำเนินคดี';
            }
         }
      }
      return $array   ;
    }
    // สถานะ
    public function getStateTitleAttribute() {
      $btn = '';

      if( $this->state == 1 ){
          $btn = 'เปิดใช้งาน';
      }else{
          $btn = 'ปิดใช้งาน';
      }
      return @$btn;
    }
    public function getStateIconAttribute() {
      $btn = '';

      if( $this->state != 1 ){
          $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
      }else{
          $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
      }
      return @$btn;
    }

}
