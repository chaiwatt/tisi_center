<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
use  App\Models\Basic\AppointDepartment;
use  App\Models\Basic\ExpertGroup;
use  App\Models\Basic\BoardType;

class RegisterExpertsHistorys extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'register_experts_historys';

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
    protected $fillable = ['expert_id', 'operation_at', 'department_id', 'committee_no', 'expert_group_id', 'position_id'];
    /*
      Sorting
    */
    public $sortable = ['expert_id', 'operation_at', 'department_id', 'committee_no', 'expert_group_id', 'position_id'];

   

    public function appoint_department_to()
    { 
        return $this->belongsTo(AppointDepartment::class,'department_id');
    }

    public function expert_group_to()
    { 
        return $this->belongsTo(ExpertGroup::class,'expert_group_id');
    }

    public function board_type_to()
    { 
        return $this->belongsTo(BoardType::class,'position_id');
    }

}
 