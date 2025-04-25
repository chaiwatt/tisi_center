<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\Tis;
use App\Models\Sso\User AS SSO_User;

class LicenseNotification extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_license_notifications';

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
    protected $fillable = ['tb3_Tisno', 'detail', 'attach', 'applicant_name', 'tel', 'email', 'state','remake', 'created_by', 'updated_by','trader_id','agent_id'];

    /*
      Sorting
    */
    public $sortable = ['tb3_Tisno', 'detail', 'attach', 'applicant_name', 'tel', 'email', 'state','remake', 'created_by', 'updated_by','trader_id','agent_id'];

    /*
      User Relation
    */
    public function trader_created(){
      return $this->belongsTo(SSO_User::class, 'created_by');
    }
    public function getTraderIdNameAttribute() {
  		return @$this->trader_created->trader_id;
  	}
    public function getCreatedNameAttribute() {
        return !is_null($this->trader_created) ? $this->trader_created->name : null ;
    }
    public function user_updated(){
        return $this->belongsTo(SSO_User::class, 'updated_by');
    }
    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->name;
  	}

    /* Tis */
    public function Basic_Tis(){
      return $this->belongsTo(Tis::class, 'tb3_Tisno','tb3_Tisno');
    }
}
