<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class Meetingtype extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bcertify_meetingtype';

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
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getStateIconAttribute(){

      $btn = '';
      if ($this->state == 1) {
          $btn = '<span class="js-state pointer"  data-id="'.$this->id.'" data-state="0"   title="ปิดใช้งาน">   <i class="fa fa-check-circle fa-lg text-success"></i>  </span>';
      }else {
         $btn = '<span class="js-state pointer"  data-id="'.$this->id.'"  data-state="1" title="เปิดใช้งาน" >      <i class="fa fa-times-circle fa-lg text-danger"></i> </span>';    
      }
      return $btn;
  }
}
