<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\RegisterExpert;
use App\CommitteeSpecial;

class MeetingStandardRecordExperts extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_setstandard_meeting_record_experts';

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
    protected $fillable = ['meeting_record_id', 'experts_id', 'created_by', 'updated_by','participate','detail','commitee_id'];

    /*
      Sorting
    */
    public $sortable = ['meeting_record_id', 'experts_id', 'created_by', 'updated_by','participate','detail','commitee_id'];

    

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

    public function committee(){
      return $this->belongsTo(CommitteeSpecial::class, 'commitee_id');
    }
    public function register_expert_to(){
      return $this->belongsTo(RegisterExpert::class, 'experts_id');
    }

    public function getRegisterExpertEmailAttribute() {
  		return @$this->register_expert_to->email;
  	}
    
}
