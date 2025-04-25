<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlFreeze extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_freeze';

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
    protected $fillable = ['auto_id_doc', 'tis_standard','tradeName', 'owner', 'address_no', 'address_village_no', 'address_alley'
        , 'address_road', 'address_province', 'address_amphoe', 'address_district', 'address_zip_code', 'id_control','address_phone','keep_product_address_phone'
        , 'total_list_seizure', 'total_value_seizure', 'total_list_freeze', 'total_value_freeze', 'keep_product_address_no'
        , 'keep_product_address_village_no','keep_product_address_alley','keep_product_address_road','keep_product_address_province'
        ,'keep_product_address_amphoe','keep_product_address_district','keep_product_address_zip_code','control_between_defect'
        ,'check_officer','date_now','date_pluck','pluck_by','status','date_freeze','officer_freeze','attach','document_number'];

    /*
      Sorting
    */
    public $sortable = ['auto_id_doc','tis_standard', 'tradeName', 'owner', 'address_no', 'address_village_no', 'address_alley'
        , 'address_road', 'address_province', 'address_amphoe', 'address_district', 'address_zip_code', 'id_control','address_phone','keep_product_address_phone'
        , 'total_list_seizure', 'total_value_seizure', 'total_list_freeze', 'total_value_freeze', 'keep_product_address_no'
        , 'keep_product_address_village_no','keep_product_address_alley','keep_product_address_road','keep_product_address_province'
        ,'keep_product_address_amphoe','keep_product_address_district','keep_product_address_zip_code','control_between_defect'
        ,'check_officer','date_now','date_pluck','pluck_by','status','date_freeze','officer_freeze','attach','document_number'];

}
