<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;
use App\Models\Section5\ApplicationLabStaff;
use App\Models\Section5\ApplicationLabBoardApprove;
use App\Models\Section5\ApplicationLabAudit;
use App\Models\Section5\ApplicationLabStatus;
use App\Models\Section5\ApplicationLabGazetteDetail;
use App\Models\Section5\ApplicationLabAccept;
use App\Models\Section5\ApplicationLabsReport;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\ApplicationLabCertificate;
use App\Models\Section5\ApplicationLabSummary;
use App\Models\Section5\ApplicationLabSummaryDetail;

use App\Models\Section5\Labs;
use App\Models\Section5\LabsScope;

use App\User;
use App\Models\Sso\User AS SSO_USER;

class ApplicationLab extends Model
{
    protected $table = 'section5_application_labs';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_no',
        'application_date',
        'application_status',
        'applicant_taxid',
        'applicant_name',
        'applicant_date_niti',
        'hq_address',
        'hq_moo',
        'hq_soi',
        'hq_road',
        'hq_building',
        'hq_subdistrict_id',
        'hq_district_id',
        'hq_province_id',
        'hq_zipcode',
        'lab_name',
        'lab_address',
        'lab_moo',
        'lab_soi',
        'lab_road',
        'lab_building',
        'lab_subdistrict_id',
        'lab_district_id',
        'lab_province_id',
        'lab_zipcode',
        'lab_phone',
        'lab_fax',
        'co_name',
        'co_position',
        'co_mobile',
        'co_phone',
        'co_fax',
        'co_email',
        'audit_type',
        'audit_date',
        'created_by',
        'updated_by',
        'config_evidencce',
        'assign_by',
        'assign_date',
        'assign_comment',
        'remarks_delete',
        'delete_by',
        'delete_at',
        'delete_state',
        'accept_date',
        'accept_by',
        'applicant_type', 
        'lab_id', 
        'lab_code'
    ];

    public function hq_subdistrict(){
        return $this->belongsTo(District::class, 'hq_subdistrict_id');
    }

    public function hq_district(){
        return $this->belongsTo(Amphur::class,  'hq_district_id');
    }

    public function hq_province(){
        return $this->belongsTo(Province::class, 'hq_province_id');
    }

    public function lab_subdistrict(){
        return $this->belongsTo(District::class, 'lab_subdistrict_id');
    }

    public function lab_district(){
        return $this->belongsTo(Amphur::class,  'lab_district_id');
    }

    public function lab_province(){
        return $this->belongsTo(Province::class, 'lab_province_id');
    }

    public function app_scope_standard(){
        return $this->hasMany(ApplicationLabScope::class, 'application_lab_id');
    }

    public function getHqSubdistrictNameAttribute() {
        return !empty($this->hq_subdistrict)?$this->hq_subdistrict->DISTRICT_NAME:null;
    }

    public function getHqDistrictNameAttribute() {
        return !empty($this->hq_district)?str_replace('เขต','',$this->hq_district->AMPHUR_NAME):null;
    }

    public function getHqProvinceNameAttribute() {
        return !empty($this->hq_province)?$this->hq_province->PROVINCE_NAME:null;
    }

    public function getLabSubdistrictNameAttribute() {
        return !empty($this->lab_subdistrict)?($this->lab_subdistrict->DISTRICT_NAME):null;
    }

    public function getLabDistrictNameAttribute() {
        return !empty($this->lab_district)?str_replace('เขต','',$this->lab_district->AMPHUR_NAME):null;
    }

    public function getLabProvinceNameAttribute() {
        return !empty($this->lab_province)?$this->lab_province->PROVINCE_NAME:null;
    }

    public function applications_status(){
        return $this->belongsTo(ApplicationLabStatus::class, 'application_status');
    }

    public function getAppStatusAttribute(){

        return @$this->applications_status->title;
    }

    public function getStatusFullTitleAttribute() {
        $status = @$this->applications_status->title;
        if($this->application_status == 100 && !empty($this->remarks_delete)){
            $status .= "<br>({$this->remarks_delete})";
        }
        return $status;
    }

    public function getScopeStandardTisTisNoAttribute() {
        return implode(', ', @$this->app_scope_standard->pluck('StandardTisTisNo')->unique()->toArray());
    }

    public function getScopeStandardAttribute(){

        $app_scope_standard = $this->app_scope_standard()->select('tis_id')->groupBy('tis_id')->get();
        $list = [];

        foreach( $app_scope_standard AS $item ){
            $tis_standards = $item->tis_standards;
            if( !is_null($tis_standards) ){

                if( !empty( $tis_standards->tb3_Tisno) ){
                    $list[] = $tis_standards->tb3_Tisno;
                }             
            }

        }
        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function getScopeStandardTisNameAttribute(){

        $app_scope_standard = $this->app_scope_standard()->select('tis_id')->groupBy('tis_id')->get();
        $list = [];

        foreach( $app_scope_standard AS $item ){
            $tis_standards = $item->tis_standards;
            if( !is_null($tis_standards) ){

                if( !empty( $tis_standards->tb3_TisThainame) ){
                    $list[] = $tis_standards->tb3_TisThainame;
                }             
            }

        }
        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function board_approve(){
        return $this->belongsTo(ApplicationLabBoardApprove::class, 'id', 'app_id');
    }

    public function app_staff(){
        return $this->hasMany(ApplicationLabStaff::class, 'application_lab_id');
    }

    public function app_audit(){
        return $this->belongsTo(ApplicationLabAudit::class, 'id', 'application_lab_id');
    }

    public function getAssignStaffAttribute(){

        $data = $this->app_staff()->select('staff_id')->groupBy('staff_id')->get();

        $list = [];
        foreach( $data AS $item ){
            $user_staff = $item->user_staff;

            if( !is_null($user_staff) ){
                $list[] = $item->StaffName;
            }

        }

        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function app_gazette_details(){
        return $this->belongsTo(ApplicationLabGazetteDetail::class, 'id', 'app_lab_id');
    }

    public function app_accept(){
        return $this->hasMany(ApplicationLabAccept::class, 'application_lab_id');
    }

    public function app_report(){
        return $this->belongsTo(ApplicationLabsReport::class, 'id', 'application_lab_id');
    }

    public function accepter(){
        return $this->belongsTo(User::class, 'accept_by');
    }

    public function user_created(){
        return $this->belongsTo(SSO_USER::class, 'created_by');
    }

    public function application_certificate(){
        return $this->hasMany(ApplicationLabCertificate::class, 'application_lab_id');
    }

    public function app_summary_list(){
        return $this->belongsToMany(ApplicationLabSummary::class, (new ApplicationLabSummaryDetail)->getTable(), 'application_lab_id', 'app_summary_id');
    }

    public function app_summary_detail(){
        return $this->hasMany(ApplicationLabSummaryDetail::class, 'application_lab_id');
    }

    public function getLabDataAdressAttribute()
    {

        $lab_province = $this->lab_province;
        $lab_district = $this->lab_district;
        $lab_subdistrict = $this->lab_subdistrict;

        $text = '';
        $text .= (!empty($this->lab_address)?$this->lab_address:null);
        $text .= ' ';
        $text .= (!empty($this->lab_moo)?'หมู่ที่ '.$this->lab_moo:null);
        $text .= ' ';

        if(!is_null($this->lab_soi) &&  $this->lab_soi != '-'){
            $text .= (!empty($this->lab_soi)?'ตรอก/ซอย '.$this->lab_soi:null);
            $text .= ' ';
        }
        if(!is_null($this->lab_road) &&  $this->lab_road != '-'){
            $text .= (!empty($this->lab_road)?'ถนน '.$this->lab_road:null);
            $text .= ' ';
        }

        $subdistrict = (!empty($lab_province->PROVINCE_ID) &&  $lab_province->PROVINCE_ID == 1) ? 'แขวง' : 'ตำบล';
        $text .= (!empty($lab_subdistrict)?$subdistrict.' '.$lab_subdistrict->DISTRICT_NAME:null);
        $text .= ' ';

        $district_name = (!empty($lab_province->PROVINCE_ID) &&  $lab_province->PROVINCE_ID == 1) ? 'เขต' : 'อำเภอ';
        $text .= (!empty($lab_district)?$district_name.' '.$lab_district->AMPHUR_NAME:null);
        $text .= ' ';

        $text .= (!empty($lab_province)?'จังหวัด '.$lab_province->PROVINCE_NAME:null);
        $text .= ' ';
        $text .= (!empty($this->lab_zipcode)?$this->lab_zipcode:null);

        return  $text;
    }

    public function section5_labs(){
        return $this->belongsTo(Labs::class, 'lab_id');
    }

    public function section5_labs_scope()
    {
        return $this->hasMany(LabsScope::class, 'lab_id', 'lab_id');
    }

    public function users_assign()
    {
        return $this->belongsToMany(User::class, (new ApplicationLabStaff)->getTable() , 'application_lab_id', 'staff_id');
    }


}
