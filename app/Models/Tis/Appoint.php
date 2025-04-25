<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Tis\AppointBoardType;
use App\Models\Tis\AppointBoard;
use App\Models\Basic\BoardType;
use App\Models\Basic\AppointDepartment;
use App\User;

class Appoint extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_appoints';

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
    protected $fillable = ['product_group_id', 'command', 'command_type', 'subject', 'board_position', 'title', 'board_type_id', 'secretary', 'secretary_assistant', 'publish_date', 'attach', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['product_group_id', 'command', 'command_type', 'subject', 'board_position', 'title', 'board_type_id', 'secretary', 'secretary_assistant', 'publish_date', 'attach', 'state', 'created_by', 'updated_by'];



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
  		return !empty($this->user_created->reg_fname) && !empty($this->user_created->reg_lname)?$this->user_created->reg_fname.' '.$this->user_created->reg_lname:'n/a';
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    /* Product Group List */
    public function board_list(){
      return $this->hasMany(AppointBoard::class);
    }

    public function appoint_department_list(){
      return $this->hasMany(AppointBoard::class, 'appoint_id', 'id');
    }

    public function department_set_list(){
      return $this->hasMany(AppointBoard::class);
    }

    /* Work List */
    public function board_type_list(){
      return $this->hasMany(AppointBoardType::class);
    }

    public function board_type(){
      return $this->belongsTo(BoardType::class, 'board_type_id');
    }

    public function getBoardTypeNameAttribute() {
  		return @$this->board_type->title;
  	}
}
