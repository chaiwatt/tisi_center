<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Tis\Standard;
use App\User;

class Branchgroupstis extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_branch_groups_tis';

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
    protected $fillable = ['branch_groups_id', 'tis_id', 'tis_tisno', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['branch_groups_id', 'tis_id', 'tis_tisno', 'created_by', 'updated_by'];



    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function tis_standards(){
        return $this->belongsTo(Standard::class,  'tis_id');
    }  

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getTisTisNoTitleAttribute() {
  		return @$this->tis_standards->tis_tisno;
  	}

}
