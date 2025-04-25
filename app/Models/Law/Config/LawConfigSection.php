<?php

namespace App\Models\Law\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Law\Basic\LawSection;
use HP;

class LawConfigSection extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_config_section';

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
    protected $fillable = ['section_id', 'power', 'section_relation', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['section_id', 'power', 'section_relation', 'state', 'created_by', 'updated_by'];

    

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

    public function law_config_reward_max_to(){
      return $this->belongsTo(LawConfigRewardMax::class, 'id', 'law_config_section_id');
    }
    public function law_config_reward_max_many(){
      return $this->hasMany(LawConfigRewardMax::class, 'law_config_section_id');
    }
    
   public function getLawConfigRewardMaxTitleAttribute() {
        $datas = [];
        $conditions =   ['='=>'เท่ากับ','<='=>'ไม่เกิน','>='=>'เกิน'];
        if(count($this->law_config_reward_max_many) > 0){  
             foreach ($this->law_config_reward_max_many as $key => $item) {
                $arrest = !empty($item->arrest->title) ? '- '.$item->arrest->title : '';
                $arrest .=  !empty($item->amount) ? ' หักได้ '.$item->amount .' % ' : '';
                $arrest .=  !empty($item->condition_money) && array_key_exists($item->condition_money,$conditions) ? ' '.$conditions[$item->condition_money] : '';
                $arrest .=  !empty($item->money) ? ' '. number_format($item->money,2) : '';
                $datas[] = $arrest;

             }
         }
      return count($datas) > 0 ? implode("<br/>",$datas) :'';
  }

    public function basic_section(){
      return $this->belongsTo(LawSection::class, 'section_id');
    }

    public function getSectionTitleAttribute(){
     	return @$this->basic_section->title;
    }

    public function getSectionNumberAttribute() {
  		return @$this->basic_section->number;
 
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

    public function getPowerTitleAttribute() {
      $power = '';
        if( $this->power == 1 ){
            $power = 'เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม (สมอ)';
        }else if( $this->power == 2 ){
            $power = 'คณะกรรมการเปรียบเทียบ';
        }else if( $this->power == 3 ){
            $power = 'ปรับเป็นพินัย';
        }else {
            $power = 'คณะกรรมการเปรียบเทียบ';
        }
        return @$power;
    }

    public function getSectionRelationNumberAttribute() {
      $datas = '';
      if(!empty($this->section_relation)){
          $data = json_decode($this->section_relation);
          if(count($data)> 0){
              foreach ($data as $key => $list) {
                  $title = LawSection::where('id',$list)->first() ;
                  if(!is_null($title)){
                      $datas .= ' <p class="label label-rounded label-primary " style="line-height: 2.3;">'.$title->number .'</p>';
                  }
              }
          }
      }
      return  @$datas;
    }
}
