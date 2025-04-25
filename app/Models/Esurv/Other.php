<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Sso\User AS SSO_User;

class Other extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_others';

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
    protected $fillable = ['title', 'state', 'remake','trader_id',  'created_by', 'updated_by','agent_id'];

    /*
      Sorting
    */
    public $sortable = ['title', 'state', 'created_by', 'updated_by','agent_id'];

    /*
      Trader Relation
    */
    public function trader_created(){
      return $this->belongsTo(SSO_User::class, 'created_by');
    }
    public function getTraderIdNameAttribute() {
  		return @$this->trader_created->tax_number;
  	}
    public function getCreatedNameAttribute() {
        return !is_null($this->trader_created) ? $this->trader_created->name : null ;
    }
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
    /* Tis */
    public function tis_list(){
      return $this->hasMany(OtherTis::class, 'other_id');
    }

}
