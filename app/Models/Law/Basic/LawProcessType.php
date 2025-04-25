<?php

namespace App\Models\Law\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class LawProcessType extends Model
{
    use Sortable;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'law_basic_process_type';

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
  protected $fillable = ['title', 'state', 'created_by', 'updated_by'];

  /*
    Sorting
  */
  public $sortable = ['title', 'state', 'created_by', 'updated_by'];

  

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
    return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
  }

  public function getUpdatedNameAttribute() {
    return (@$this->user_updated->reg_fname).(!empty($this->user_updated->reg_lname)?' '.$this->user_updated->reg_lname:null);
  }

}
