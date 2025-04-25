<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\RegisterExpert;
use Illuminate\Database\Eloquent\Model;
use App\CommitteeSpecial;
class CommitteeLists extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bcertify_committee_lists';

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
    protected $fillable = ['committee_special_id', 'expert_id', 'expert_name', 'department_name', 'committee_qualified', 'committee_position', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable =  ['committee_special_id', 'expert_id', 'expert_name', 'department_name', 'committee_qualified', 'committee_position', 'created_by', 'updated_by'];

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
    public function  committee_special_to(){
      return $this->belongsTo(CommitteeSpecial::class, 'committee_special_id');
    }

    public function register_expert_to(){
      return $this->belongsTo(RegisterExpert::class, 'expert_id');
    }

    public function getCreatedNameAttribute() {
  		return $this->user_created->reg_fname.' '.$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    
	
}
