<?php

namespace App\Models\Law\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class LawBasicDivisionCategory extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_basic_division_category';

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
      Sorting
    */
    public $sortable = ['title', 'state', 'created_by', 'updated_by'];


    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
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
      return $btn;
  }
}
