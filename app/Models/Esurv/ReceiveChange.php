<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Esurv\Tis;
use App\Models\Esurv\ReceiveChangeLicense;
use App\User;
use App\Models\Sso\User AS SSO_User;

class ReceiveChange extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_inform_changes';

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
    protected $fillable = ['state', 'consider', 'consider_comment','trader_id','agent_id'];

    /*
      Sorting
    */
    public $sortable = ['created_by', 'tb3_Tisno', 'detail', 'created_at', 'state', 'consider', 'consider_comment','trader_id','agent_id'];

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
      return $this->belongsTo(User::class, 'consider');
    }
    public function getCreatedAndTradeNameAttribute() {
  		return @$this->trader_created->name.' '.@$this->trader_created->tax_number;
  	}
    /*
      Tis Relation มาตรฐาน
    */
    public function tis(){
      return $this->belongsTo(Tis::class, 'tb3_Tisno', 'tb3_Tisno');
    }

    /* License */
    public function license_list(){
      return $this->hasMany(ReceiveChangeLicense::class, 'inform_change_id', 'id');
    }

}
