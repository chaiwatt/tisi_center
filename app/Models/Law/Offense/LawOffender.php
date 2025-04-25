<?php

namespace App\Models\Law\Offense;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;

use App\Models\Basic\Province AS Province;
use App\Models\Basic\Amphur AS District;
use App\Models\Basic\District AS Subdistrict;


use App\Models\Sso\User AS SSO_USER;

use App\Models\Law\Offense\LawOffenderCases;
use App\Models\Basic\TisiLicense;
class LawOffender extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_offenders';

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
    protected $fillable = [
        'sso_users_id',
        'type_id',
        'name',
        'taxid',
        'address_no',
        'moo',
        'soi',
        'building',
        'street',
        'subdistrict_id',
        'district_id',
        'province_id',
        'zipcode',
        'tel',
        'fax',
        'email',
        'power',
        'contact_name',
        'contact_position',
        'contact_mobile',
        'contact_phone',
        'contact_fax',
        'contact_email',
        'date_offender',
        'import_data',
        'state',
        'remark',
        'created_by',
        'updated_by',
 
    ];
    protected $casts = ['power' => 'json'];
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
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function user_sso(){
        return $this->belongsTo(SSO_USER::class, 'sso_users_id');
    }

    public function bs_subdistrict(){
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }  
    
    public function bs_district(){
        return $this->belongsTo(District::class,  'district_id');
    }  

    public function bs_province(){
        return $this->belongsTo(Province::class, 'province_id');
    }  

    public function getAddressFullAttribute()
    {

        $bs_province    = $this->bs_province;
        $bs_district    = $this->bs_district;
        $bs_subdistrict = $this->bs_subdistrict;

        $text = null;
        $text .= (!empty($this->address_no)?$this->address_no.' ':null);
        $text .= (!empty($this->moo)?'หมู่ที่ '.$this->moo.' ':null);

        if(!is_null($this->soi) &&  $this->soi != '-'){
            $text .= (!empty($this->soi)?'ตรอก/ซอย '.$this->soi.' ':null);
        }
        if(!is_null($this->street) &&  $this->street != '-'){
            $text .= (!empty($this->street)?'ถนน '.$this->street.' ':null);
        }

        $subdistrict = !empty($bs_province) && ($bs_province->PROVINCE_ID == 1) ? 'แขวง' : 'ตำบล';
        $text .= (!empty($bs_subdistrict)?$subdistrict.' '.$bs_subdistrict->DISTRICT_NAME.' ':null);

        $district_name = !empty($bs_province) && ($bs_province->PROVINCE_ID  == 1) ? 'เขต' : 'อำเภอ';
        $text .= (!empty($bs_district)?$district_name.' '.$bs_district->AMPHUR_NAME.' ':null);

        $text .= (!empty($bs_province)?'จังหวัด '.$bs_province->PROVINCE_NAME:null);
        $text .= (!empty($this->zipcode)?' '.$this->zipcode:null);

        return  $text;
    }

    public function offender_cases(){
        return $this->hasMany(LawOffenderCases::class, 'law_offender_id');
    }
    public function offender_cases_to(){
        return $this->belongsTo(LawOffenderCases::class, 'id', 'law_offender_id');
    }
    public function offender_product_to(){
        return $this->belongsTo(LawOffenderProduct::class, 'id', 'law_offender_id');
    }

    
    public function tisi_license()
    {
        return $this->belongsToMany(TisiLicense::class, (new LawOffenderCases)->getTable() , 'law_offender_id', 'tb4_tisilicense_id');
    }

    public function getApplicantTypeTitleAttribute() {
        $applicanttype =  ['1'=>'นิติบุคคล','2'=>'บุคคลธรรมดา','3'=>'คณะบุคคล','4'=>'ส่วนราชการ','5'=>'อื่นๆ'];
        return  array_key_exists($this->type_id,$applicanttype) ?  $applicanttype[$this->type_id] : null;
    }
}
