<?php

namespace App\Models\Law\Config;

use HP;
use App\User;
use App\Models\Law\Basic\LawArrest;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Basic\LawRewardGroup;
use App\Models\Law\Config\LawConfigNotificationDetail;

class LawConfigNotification extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_config_notification';

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
    protected $fillable = ['title', 'state', 'created_by', 'updated_by'];

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

    public function reward_group(){
      return $this->belongsTo(LawRewardGroup::class, 'reward_group_id');
    }

    public function arrest(){
      return $this->belongsTo(LawArrest::class, 'arrest_id');
    }

    public function law_config_notification_details(){
      return $this->hasMany(LawConfigNotificationDetail::class, 'law_config_notification_id');
    }

    public function color_list() {
        return ['danger' => 'แดง', 'warning' => 'เหลือง', 'success' => 'เขียว'];
    }

    public function condition_list() {
        return ['<' => 'น้อยกว่า', '=' => 'เท่ากับ', '>' => 'มากกว่า'];
    }

    public function operation_css() {
        return [1 => 'text-success', 2 => 'text-danger'];
    }

    public function getOperationHtmlAttribute() {
        return array_key_exists($this->operation_id, $this->operation_list()) ? '<span class="'.$this->operation_css()[$this->operation_id].'">'.$this->operation_list()[$this->operation_id].'</span>' : '-';
    }

    public function getColorHtmlAttribute() {
        return array_key_exists($this->color, $this->color_list()) ? '<span class="text-'.$this->color.'">'.$this->color_list()[$this->color].'</span>' : '-';
    }

    public function getCoditionHtmlAttribute() {

        $details = $this->law_config_notification_details;
        $text = '';
        foreach( $details AS $item ){

            $icon      =  !empty($item->color) && array_key_exists($item->color, $this->color_list()) ?'<i class="fa fa-circle text-'.($item->color).'"></i>':null;
            $condition =  !empty($item->condition) && array_key_exists($item->condition, $this->condition_list()) ? $this->condition_list()[$item->condition]:null;
            $day       =  !empty($item->amount)?($item->amount.' วัน'):null;
            $text      .= '<div>'.( $icon ).' '.( $condition ).' '.( $day ).'</div>';
        }
        return $text;
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
