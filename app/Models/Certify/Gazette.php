<?php

namespace App\Models\Certify;

use App\User;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\GazetteStandard;
use Illuminate\Database\Eloquent\Model;

class Gazette extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_gazette';

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
    protected $fillable = ['title', 'gazette_book', 'gazette_govbook', 'gazette_no', 'gazette_space', 'gazette_date', 'enforce_day', 'enforce_date', 'gazette_signname', 'gazette_position', 'gazette_attach', 'std_type_id', 'state', 'send_tis', 'gaz_page', 'committee', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable =  ['title', 'gazette_book', 'gazette_govbook', 'gazette_no', 'gazette_space', 'gazette_date', 'enforce_day', 'enforce_date', 'gazette_signname', 'gazette_position', 'gazette_attach', 'std_type_id', 'state', 'send_tis',  'gaz_page', 'committee', 'created_by', 'updated_by'];

    

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function certify_gazette_standard(){
      return $this->hasOne(GazetteStandard::class, 'gazette_id');
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
