<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Sso\ApplicationInspectionUnitStandard;
use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;

class ApplicationInspectionUnit extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sso_application_inspection_units';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [

                        'refno_application',
                        'date_application',
                        'authorized_taxid',
                        'authorized_name',
                        'authorized_date_niti',
                        'authorized_email',
                        'authorized_mobile',
                        'authorized_tel',
                        'authorized_fax',
                        'authorized_address',
                        'authorized_moo',
                        'authorized_soi',
                        'authorized_road',
                        'authorized_building',
                        'authorized_subdistrict_id',
                        'authorized_district_id',
                        'authorized_province_id',
                        'authorized_postcode',
                        'laboratory_address',
                        'laboratory_moo',
                        'laboratory_soi',
                        'laboratory_road',
                        'laboratory_building',
                        'laboratory_subdistrict_id',
                        'laboratory_district_id',
                        'laboratory_province_id',
                        'laboratory_postcode',
                        'laboratory_tel',
                        'laboratory_fax',
                        'contact_name',
                        'contact_position',
                        'contact_mobile',
                        'contact_tel',
                        'contact_fax',
                        'contact_email',
                        'status_application',
                        'remarks',
                        'checking_comment',
                        'checking_by',
                        'checking_date',
                        'approve_comment',
                        'approve_by',
                        'approve_date',
                        'created_by',
                        'updated_by',
                        'assign_by', 
                        'assign_date', 
                        'assign_comment',
                        'checking_status',
                        'approve_status'
                        ];

                              
    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function user_assign(){
        return $this->belongsTo(User::class, 'assign_by');
    }

    public function user_checking(){
        return $this->belongsTo(User::class, 'checking_by');
    }
  
    public function user_approve(){
        return $this->belongsTo(User::class, 'approve_by');
    }
  

    public function app_units_standard(){
        return $this->hasMany(ApplicationInspectionUnitStandard::class, 'app_units_id');
    }

    public function getCreatedNameAttribute() {
  		return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getAssignNameAttribute() {
        return @$this->user_assign->reg_fname.' '.@$this->user_assign->reg_lname;
    }

    public function getCheckingNameAttribute() {
        return @$this->user_checking->reg_fname.' '.@$this->user_checking->reg_lname;
    }

    public function getApproveNameAttribute() {
        return @$this->user_approve->reg_fname.' '.@$this->user_approve->reg_lname;
    }

    public function getUnitsStandardAttribute(){

        $app_units_standard = $this->app_units_standard()->get();
        $list = [];
        foreach( $app_units_standard AS $item ){
            $tis_standards = $item->tis_standards;

            if( !is_null($tis_standards) ){
                $list[] = $tis_standards->tis_tisno;
            }

        }
        
        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function getAppStatusAttribute(){

        $status_application = $this->status_application;

        $txt = '';

        if( $status_application  == 1 ){
            $txt = 'อยู่ระหว่างการตรวจสอบ';
        }else if( $status_application  == 2 ){
            $txt = 'เอกสารไม่ครบถ้วน';
        }else if( $status_application  == 3 ){
            $txt = 'เอกสารครบถ้วน ส่งต่อให้ผู้อนุมัติ';
        }else if( $status_application  == 4 ){
            $txt = 'ตรวจสอบเอกสารอีกครั้ง';
        }else if( $status_application  == 5 ){
            $txt = 'อนุมัติ';
        }else if( $status_application  == 6 ){
            $txt = 'ไม่อนุมัติ ตรวจสอบอีกครั้ง';
        }else if( $status_application  == 7 ){
            $txt = 'ไม่รับคำขอ/Reject';
        }else{
            $txt = 'อยู่ระหว่างการตรวจสอบ';
        }

        return $txt;
    }

    public function authorized_subdistrict(){
        return $this->belongsTo(District::class, 'authorized_subdistrict_id');
    }  
    
    public function authorized_district(){
        return $this->belongsTo(Amphur::class,  'authorized_district_id');
    }  

    public function authorized_province(){
        return $this->belongsTo(Province::class, 'authorized_province_id');
    }   

    public function getAuthorizedSubdistrictNameAttribute() {
        return !empty($this->authorized_subdistrict)?$this->authorized_subdistrict->DISTRICT_NAME:null;
    }

    public function getAuthorizedDistrictNameAttribute() {
        return !empty($this->authorized_district)?$this->authorized_district->AMPHUR_NAME:null;
    }

    public function getAuthorizedProvinceNameAttribute() {
        return !empty($this->authorized_province)?$this->authorized_province->PROVINCE_NAME:null;
    }

    public function getAuthorizedPostcodeNameAttribute() {
        return !empty($this->authorized_postcode)?$this->authorized_postcode:null;
    }

    public function laboratory_subdistrict(){
        return $this->belongsTo(District::class, 'laboratory_subdistrict_id');
    }  
    
    public function laboratory_district(){
        return $this->belongsTo(Amphur::class,  'laboratory_district_id');
    }  

    public function laboratory_province(){
        return $this->belongsTo(Province::class, 'laboratory_province_id');
    }   

    public function getLaboratorySubdistrictNameAttribute() {
        return !empty($this->laboratory_subdistrict)?$this->laboratory_subdistrict->DISTRICT_NAME:null;
    }

    public function getLaboratoryDistrictNameAttribute() {
        return !empty($this->laboratory_district)?$this->laboratory_district->AMPHUR_NAME:null;
    }

    public function getLaboratoryProvinceNameAttribute() {
        return !empty($this->laboratory_province)?$this->laboratory_province->PROVINCE_NAME:null;
    }

    public function getLaboratoryPostcodeNameAttribute() {
        return !empty($this->laboratory_postcode)?$this->laboratory_postcode:null;
    }


}
