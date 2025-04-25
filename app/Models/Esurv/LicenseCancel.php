<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Esurv\Tis;
use App\Models\Esurv\LicenseCancelLicense;
use App\User;

class LicenseCancel extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_license_cancels';

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
    protected $fillable = ['cancel_no', 'tb3_Tisno', 'cancel_date', 'reason_type', 'reason_other', 'remark', 'attach', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['cancel_no', 'tb3_Tisno', 'cancel_date', 'reason_type', 'reason_other', 'remark', 'attach', 'state', 'created_by', 'updated_by'];



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
      Tis Relation มาตรฐาน
    */
    public function tis(){
      return $this->belongsTo(Tis::class, 'tb3_Tisno');
    }

    /* License */
    public function license_list(){
      return $this->hasMany(LicenseCancelLicense::class, 'license_cancel_id', 'id');
    }

}
