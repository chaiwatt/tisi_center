<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\User;
use App\Models\Besurv\Department;
use App\Models\Besurv\TisSubDepartment;

class SubDepartment extends Model
{

    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sub_department';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'sub_id';

    /* ประเภท primaryKey */
    public $keyType = 'string';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['did', 'sub_departname', 'sub_depart_shortname'];

    /*
      Department Relation
    */
    public function department(){
      return $this->belongsTo(Department::class, 'did', 'did');
    }

    /* ตั้งค่าสิทธิ์รับแจ้ง E-Surv กับมาตรฐาน */
    public function tis_users()
    {
        return $this->hasMany(TisSubDepartment::class, 'sub_id');
    }
    
    public function getDepartmentIdAttribute() {
        return @$this->department->did;
    }

    public function getDepartmentNameShortAttribute() {
      return @$this->department->depart_nameShort;
    }


    public function getCheckRightAttribute() {
      $request   = [];
      if(count($this->tis_users) > 0){
          foreach($this->tis_users as $item){
              if(!in_array($item->tb3_Tisno,$request)){
                  $request[] = $item->tb3_Tisno;
              }
          }
      }
      return $request;
    }


    public function getDepartmentNameAttribute() {
      return @$this->department->depart_name;
    }

}
