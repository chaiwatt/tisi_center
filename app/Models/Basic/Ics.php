<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class Ics extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_ics';

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
    protected $fillable = ['code', 'title', 'title_en', 'category', 'state', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    /*
      Sorting
    */
    public $sortable = ['code', 'title', 'title_en', 'category', 'state', 'created_by', 'created_at', 'updated_by', 'updated_at'];

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
}
