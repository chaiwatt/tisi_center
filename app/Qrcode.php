<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class Qrcode extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'besurv_qrcodes';

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
    protected $fillable = ['qrcode_state', 'qrcode_link', 'qrcode_announce', 'index_state', 'index_link', 'index_announce', 'type_of_ter', 'state', 'created_by', 'updated_by','attach','file_client_name','attach_state'];

    /*
      Sorting
    */
    public $sortable = ['qrcode_state', 'qrcode_link', 'qrcode_announce', 'index_state', 'index_link', 'index_announce', 'type_of_ter', 'state', 'created_by', 'updated_by','attach','file_client_name','attach_state'];



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
  		return $this->user_created->reg_fname.' '.$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}
}
