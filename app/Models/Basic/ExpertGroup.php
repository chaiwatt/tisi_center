<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\ExpertDepartment;
use App\User;

class ExpertGroup extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_expert_groups';

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
    protected $fillable = ['title', 'state', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    /*
      Sorting
    */
    public $sortable = ['title', 'state', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function basic_expert_department(){
      return $this->hasMany(ExpertDepartment::class, 'expert_id');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getExpertDepartmentIdAttribute() {
  		return @$this->basic_expert_department->pluck('department_id')->toArray();
  	}
    
}
