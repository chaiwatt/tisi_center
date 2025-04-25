<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\Geography;

class Province extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'province';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'PROVINCE_ID';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['PROVINCE_CODE', 'PROVINCE_NAME', 'PROVINCE_NAME_EN', 'GEO_ID', 'state', 'created_by', 'updated_by'];

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

    /*
      GEO Relation
    */
    public function geography(){
      return $this->belongsTo(Geography::class, 'GEO_ID');
    }


}
