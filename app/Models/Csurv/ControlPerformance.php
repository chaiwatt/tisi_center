<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Basic\Amphur;
use App\Models\Basic\District;
use App\Models\Basic\Province;


class ControlPerformance extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_performance';

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
    protected $fillable = ['auto_id_doc', 'tradeName', 'tbl_tisiNo', 'factory_name', 'address_no', 'address_industrial_estate'
        , 'address_alley', 'address_road', 'address_village_no', 'address_district', 'address_amphoe', 'address_province'
        , 'address_zip_code', 'tel', 'fax', 'latitude', 'Longitude', 'checking_date', 'material_res'
        , 'material_ofsev','material_ofsev_remake','material_defect','material_defect_remake','control_between_res'
        ,'control_between_ofsev','control_between_ofsev_remake','control_between_defect','control_between_defect_remake'
        ,'control_finish_res','control_finish_ofsev','control_finish_ofsev_remake','control_finish_defect'
        ,'control_finish_defect_remake','control_standard_res','control_standard_ofsev','control_standard_ofsev_remake'
        ,'control_standard_defect','control_standard_defect_remake','test_machine_res','test_machine_ofsev'
        ,'test_machine_ofsev_remake','test_machine_defect','test_machine_defect_remake','conclude_result','remake','status','status_history'
        ,'check_officer','date_now','status_check','status_res','status_res_remake'
        ,'material_remark','control_between_remark','control_finish_remark','control_standard_remark','test_machine_remark'
        ,'sub_id'
    ];

    /*
      Sorting
    */
    public $sortable = ['auto_id_doc', 'tradeName', 'tbl_tisiNo', 'factory_name', 'address_no', 'address_industrial_estate'
        , 'address_alley', 'address_road', 'address_village_no', 'address_district', 'address_amphoe', 'address_province'
        , 'address_zip_code', 'tel', 'fax', 'latitude', 'Longitude', 'checking_date', 'material_res'
        , 'material_ofsev','material_ofsev_remake','material_defect','material_defect_remake','control_between_res'
        ,'control_between_ofsev','control_between_ofsev_remake','control_between_defect','control_between_defect_remake'
        ,'control_finish_res','control_finish_ofsev','control_finish_ofsev_remake','control_finish_defect'
        ,'control_finish_defect_remake','control_standard_res','control_standard_ofsev','control_standard_ofsev_remake'
        ,'control_standard_defect','control_standard_defect_remake','test_machine_res','test_machine_ofsev'
        ,'test_machine_ofsev_remake','test_machine_defect','test_machine_defect_remake','conclude_result','remake','status','status_history'
        ,'check_officer','date_now','status_check','status_res','status_res_remake','material_remark','control_between_remark'
        ,'control_finish_remark','control_standard_remark','test_machine_remark', 'sub_id'];


    public function provinces(){
      return $this->belongsTo(Province::class, 'address_province');
    }

    public function districts(){
      return $this->belongsTo(District::class, 'address_district');
    }

    public function amphoes(){
      return $this->belongsTo(Amphur::class, 'address_amphoe');
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
