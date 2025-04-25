<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class ExpertDepartment extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_expert_department';

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
    protected $fillable = ['expert_id', 'department_id', 'created_by', 'created_at', 'updated_at'];

    /*
      Sorting
    */
    public $sortable = ['expert_id', 'department_id', 'created_by', 'created_at', 'updated_at'];

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }
    
}
