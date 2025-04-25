<?php

namespace App\Models\Besurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\Tis;

class TisSubDepartment extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'besurv_tis_sub_departments';

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
    protected $fillable = ['sub_id', 'tb3_Tisno', 'created_by', 'created_at'];

    /*
      Sorting
    */
    public $sortable = ['sub_id', 'tb3_Tisno', 'created_by', 'created_at'];

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    /* มอก. */
    public function tis(){
      return $this->belongsTo(Tis::class, 'tb3_Tisno');
    }

    /* มอก. */
    public function tis_no(){
      return $this->belongsTo(Tis::class, 'tb3_Tisno', 'tb3_Tisno');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getTisNameAttribute() {
  		return $this->tis_no->tb3_TisThainame??'n/a';
  	}

}
