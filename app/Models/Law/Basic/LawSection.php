<?php

namespace App\Models\Law\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class LawSection extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'law_basic_section';

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
  protected $fillable = ['number','title', 'remark', 'date_announce', 'section_type', 'state', 'conditon_cert', 'created_by', 'updated_by', 'adjustment_type','adjustment', 'adjustment_max'];
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
      return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
  }

  public function getUpdatedNameAttribute() {
      return (@$this->user_updated->reg_fname).(!empty($this->user_updated->reg_lname)?' '.$this->user_updated->reg_lname:null);
  }

  public function getStateIconAttribute() {
      $btn = '';

      if( $this->state != 1 ){
          $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
      }else{
          $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
      }
      return $btn;
  }

  static function list_section_type() {
    $status = [
                "1" => "ความผิด",
                "2" => "อัตราโทษ",
            ];
    return $status;
  }

  public function getSectionTypeTextAttribute() {
    $list = self::list_section_type();
    $text = array_key_exists($this->section_type,$list)?$list[$this->section_type]:null;
  return $text;
  }

}