<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\WorkGroupIBBranch;

class WorkGroupIB extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_workgroup_ib';

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
    
    public function ib_workgroup_staff(){
        return $this->hasMany(WorkGroupIBStaff::class, 'workgroup_id');
    }

    public function ib_workgroup_branch(){
        return $this->hasMany(WorkGroupIBBranch::class, 'workgroup_id');
    }

    //ดึง id ตาราง basic_branch_groups ที่เจ้าหน้าที่อยู่ในกลุ่มที่เปิดใช้งาน
    static function UserBranchGroupIDs($user_id){

        //query id สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
        $staff_query      = WorkGroupIBStaff::where('user_reg_id', $user_id)->select('workgroup_id');
        $workgroup_query  = WorkGroupIB::where('state', 1)->whereIn('id', $staff_query)->select('id'); //เฉพาะกลุ่มที่เปิดใช้งาน
        $branch_group_ids = WorkGroupIBBranch::whereIn('workgroup_id', $workgroup_query)->pluck('branch_group_id')->toArray();
        return $branch_group_ids;

    }

}
