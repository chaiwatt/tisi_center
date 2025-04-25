<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Sso\User AS SSO_User;

class ApplicantIB extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applicant_ib';

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
    protected $fillable = ['app_no', 'name', 'age', 'nationality', 'address_no', 'alley', 'road', 'village_no', 'district', 'amphur', 'province', 'postcode', 'tel', 'identify_number', 'tax_number', 'headquarter_address', 'headquarter_alley', 'headquarter_road', 'headquarter_village_no', 'headquarter_district', 'headquarter_amphur', 'headquarter_province', 'headquarter_postcode', 'headquarter_tel', 'headquarter_fax', 'regis_juristic_date', 'regis_number', 'regis_commercial_number', 'inspect_type', 'formula_id', 'Inspect_name', 'Inspect_address', 'Inspect_address', 'Inspect_village_no', 'Inspect_alley', 'Inspect_road', 'Inspect_province_id', 'Inspect_amphur_id', 'Inspect_district_id', 'Inspect_postcode', 'Inspect_tel', 'Inspect_fax', 'Inspect_contract', 'Inspect_contract_tel', 'Inspect_contract_mobile', 'Inspect_contract_email', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['app_no', 'name', 'age', 'nationality', 'address_no', 'alley', 'road', 'village_no', 'district', 'amphur', 'province', 'postcode', 'tel', 'identify_number', 'tax_number', 'headquarter_address', 'headquarter_alley', 'headquarter_road', 'headquarter_village_no', 'headquarter_district', 'headquarter_amphur', 'headquarter_province', 'headquarter_postcode', 'headquarter_tel', 'headquarter_fax', 'regis_juristic_date', 'regis_number', 'regis_commercial_number', 'inspect_type', 'formula_id', 'Inspect_name', 'Inspect_address', 'Inspect_address', 'Inspect_village_no', 'Inspect_alley', 'Inspect_road', 'Inspect_province_id', 'Inspect_amphur_id', 'Inspect_district_id', 'Inspect_postcode', 'Inspect_tel', 'Inspect_fax', 'Inspect_contract', 'Inspect_contract_tel', 'Inspect_contract_mobile', 'Inspect_contract_email', 'state', 'created_by', 'updated_by'];



    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(SSO_User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(SSO_User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
  		return $this->user_created->name;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->name;
  	}
}
