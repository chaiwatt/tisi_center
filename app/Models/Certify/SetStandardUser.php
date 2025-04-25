<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;

use App\Models\Certify\SetStandardUserSub;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertifyLabCalibrate;

class SetStandardUser extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'set_standard_user';

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
    protected $fillable = ['department_id', 'sub_department_id','lab_ability', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['department_id', 'sub_department_id','lab_ability', 'created_by', 'updated_by'];


    

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
    
        /*
      Department Relation
    */
    public function department(){
      	return $this->belongsTo(Department::class, 'department_id', 'did')->withDefault();
    }
    public function subdepartment(){
      	return $this->belongsTo(SubDepartment::class, 'sub_department_id', 'sub_id')->withDefault();
    }
    public function DataSetStandardUserSub(){
      	return $this->hasMany(SetStandardUserSub::class, 'standard_user_id', 'id');
    }

    public function app_lab_test_scope()
    {
        return $this->hasManyThrough(CertifyTestScope::class, SetStandardUserSub::class, 'standard_user_id', 'branch_id', 'id', 'test_branch_id' );
    }

	public function app_lab_calibrate()
    {
        return $this->hasManyThrough(CertifyLabCalibrate::class, SetStandardUserSub::class, 'standard_user_id', 'branch_id', 'id', 'items_id' );
    }
    
}
