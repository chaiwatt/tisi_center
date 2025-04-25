<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Basic\Amphur;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\User;
class ControlCheck extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_check';

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
    protected $fillable = ['auto_id_doc', 'tradeName', 'tbl_tisiNo', 'located_check','located_keep','located_sell', 'address_no', 'address_industrial_estate'
        , 'address_alley', 'address_road', 'address_village_no', 'address_district', 'address_amphoe', 'address_province'
        , 'address_zip_code', 'tel', 'fax', 'latitude', 'Longitude', 'officer_name', 'checking_date'
        , 'checking_time','police_station','this_checking','location_check','remake_location_check','police_station_value'
        ,'production_site','product_not_legally','location_keep','product_sell','num_of_hold','num_of_freeze','num_of_hold_value','num_of_freeze_value','reference_num','detail_location_offense'
        ,'detail_product_not_standard','premise','seller_name','seller_address','officer_check','num_of_time','last_time'
        ,'ever_warning','ever_warned','this_operation','more_notes','operation','status','status_history','check_officer','date_now','status_check','status_res'
        ,'status_res_remake','poko_approve', 'poko_approve_text', 'poko_assessor', 'poko_approve_date', 'poao_approve', 'poao_approve_text', 'poao_assessor', 'poao_approve_date',
        'check_status','remake_location_check2',
        'sub_id'
    ];
    /*
      Sorting
    */
    public $sortable = ['auto_id_doc', 'tradeName', 'tbl_tisiNo', 'located_check','located_keep','located_sell', 'address_no', 'address_industrial_estate'
        , 'address_alley', 'address_road', 'address_village_no', 'address_district', 'address_amphoe', 'address_province'
        , 'address_zip_code', 'tel', 'fax', 'latitude', 'Longitude', 'officer_name', 'checking_date','num_of_hold_value','num_of_freeze_value'
        , 'checking_time','police_station','this_checking','location_check','remake_location_check','police_station_value'
        ,'production_site','product_not_legally','location_keep','product_sell','num_of_hold','num_of_freeze','reference_num','detail_location_offense'
        ,'detail_product_not_standard','premise','seller_name','seller_address','officer_check','num_of_time','last_time'
        ,'ever_warning','ever_warned','this_operation','more_notes','operation','status','status_history','check_officer','date_now','status_check','status_res'
        ,'status_res_remake','check_status','remake_location_check2', 'sub_id'];


    public function provinces(){
      return $this->belongsTo(Province::class, 'address_province');
    }

    public function districts(){
      return $this->belongsTo(District::class, 'address_district');
    }

    public function amphoes(){
      return $this->belongsTo(Amphur::class, 'address_amphoe');
    }
    public function User_FullName(){
      return $this->belongsTo(User::class, 'status_check');
    }
    public function getProvinceNameAttribute() {
  		return @$this->provinces->PROVINCE_NAME;
    }

    public function getDistrictNameAttribute() {
  		return @$this->districts->DISTRICT_NAME;
    }

    public function getAmphurNameAttribute() {
  		return @$this->amphoes->AMPHUR_NAME;
  	}

}
