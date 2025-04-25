<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

class ApplicationInspectorRegister extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sso_application_inspector_registers';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
                            'refno_application',
                            'date_application',
                            'register_taxid',
                            'register_name',
                            'register_position',
                            'register_date_niti',
                            'register_email',
                            'register_mobile',
                            'register_tel',
                            'register_fax',
                            'agency_id',
                            'agency_address',
                            'agency_moo',
                            'agency_soi',
                            'agency_road',
                            'agency_building',
                            'agency_subdistrict_id',
                            'agency_district_id',
                            'agency_province_id',
                            'agency_postcode',
                            'status_application',
                            'remarks',
                            'config_evidencce',
                            'checking_comment',
                            'checking_by',
                            'checking_date',
                            'checking_status',
                            'approve_comment',
                            'approve_by',
                            'approve_date',
                            'approve_status',
                            'assign_by',
                            'assign_date',
                            'assign_comment'

                        ];


    public function agency_subdistrict(){
        return $this->belongsTo(Subdistrict::class, 'agency_subdistrict_id');
    }  
    
    public function agency_district(){
        return $this->belongsTo(District::class,  'agency_district_id');
    }  

    public function agency_province(){
        return $this->belongsTo(Province::class, 'agency_province_id');
    }   

    public function getAgencySubdistrictNameAttribute() {
        return !empty($this->agency_subdistrict)?$this->agency_subdistrict->DISTRICT_NAME:null;
    }

    public function getAgencyDistrictNameAttribute() {
        return !empty($this->agency_district)?$this->agency_district->AMPHUR_NAME:null;
    }

    public function getAgencyProvinceNameAttribute() {
        return !empty($this->agency_province)?$this->agency_province->PROVINCE_NAME:null;
    }

    public function getAgencyPostcodeNameAttribute() {
        return !empty($this->agency_postcode)?$this->agency_postcode:null;
    }


}
