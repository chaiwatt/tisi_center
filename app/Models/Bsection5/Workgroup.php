<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

use App\Models\Bsection5\Workgrouptis;
use App\Models\Bsection5\Workgroupstaff;

class Workgroup extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_workgroups';

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

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getStateIconAttribute(){

        $btn = '';
        if ($this->state == 1) {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'" checked></div>';
        }else {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'"></div>';
        }
        return $btn;

    }

    public function workgroup_staff(){
        return $this->hasMany(Workgroupstaff::class, 'workgroup_id');
    }

    public function workgroup_std(){
        return $this->hasMany(Workgrouptis::class, 'workgroup_id');
    }

    //ดึง id ตาราง tis_standards ที่เจ้าหน้าที่อยู่ในกลุ่มที่เปิดใช้งาน
    static function UserTisIds($user_id){

        //query id สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
        $staff_query      = Workgroupstaff::where('user_reg_id', $user_id)->select('workgroup_id');
        $workgroup_query  = Workgroup::where('state', 1)->whereIn('id', $staff_query)->select('id'); //เฉพาะกลุ่มที่เปิดใช้งาน
        $tis_ids = Workgrouptis::whereIn('workgroup_id', $workgroup_query)->pluck('tis_id')->toArray();
        return $tis_ids;

    }

}
