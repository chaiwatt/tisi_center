<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ReceiveVolumeLicenseDetail extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_inform_volume_license_details';

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
    protected $fillable = ['id','inform_volume_license_id', 'elicense_detail_id', 'volume1', 'volume2', 'volume3', 'id_unit', 'unit', 'created_at', 'updated_at'];

    /*
      Sorting
    */
    public $sortable = ['id','inform_volume_license_id', 'elicense_detail_id', 'volume1', 'volume2', 'volume3', 'id_unit', 'unit', 'created_at', 'updated_at'];


    public function volume_license(){
      return $this->belongsTo(ReceiveVolumeLicense::class, 'inform_volume_license_id');
    }

    public function getLicenseNoAttribute() {
  		return $this->volume_license->tbl_licenseNo;
  	}


}
