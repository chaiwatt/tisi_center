<?php
namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;

use App\Models\Section5\ApplicationInspectorAudit;
use App\Models\Section5\ApplicationInspectorScope;
use App\Models\Section5\ApplicationInspectorStatus;
use App\Models\Section5\ApplicationInspectorsStaff;
use App\Models\Section5\ApplicationInspectorsAccept;

use App\Models\Section5\Inspectors;
use App\Models\Section5\InspectorsAgreement;
use HP;
use App\AttachFile;
use App\Models\Config\ConfigsEvidence;
use Carbon\Carbon;
use App\User;
use App\Models\Sso\User AS SSO_USER;

class ApplicationInspector extends Model
{
    protected $table = 'section5_application_inspectors';

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
        'applicant_prefix',
        'applicant_first_name',
        'applicant_last_name',
        'applicant_full_name',
        'applicant_taxid',
        'applicant_date_of_birth',
        'applicant_address',
        'applicant_moo',
        'applicant_soi',
        'applicant_road',
        'applicant_subdistrict',
        'applicant_district',
        'applicant_province',
        'applicant_zipcode',
        'applicant_position',
        'applicant_phone',
        'applicant_fax',
        'applicant_mobile',
        'applicant_email',
        'agency_id',
        'agency_name',
        'agency_taxid',
        'agency_address',
        'agency_moo',
        'agency_soi',
        'agency_road',
        'agency_subdistrict',
        'agency_district',
        'agency_province',
        'agency_zipcode',
        'configs_evidence',
        'created_by',
        'updated_by',
        'remarks_delete',
        'delete_by',
        'delete_at',
        'accept_date',
        'accept_by'
    ];

    public function applicant_subdistricts(){
        return $this->belongsTo(District::class, 'applicant_subdistrict');
    }

    public function applicant_districts(){
        return $this->belongsTo(Amphur::class,  'applicant_district');
    }

    public function applicant_provinces(){
        return $this->belongsTo(Province::class, 'applicant_province');
    }

    public function agency_subdistricts(){
        return $this->belongsTo(District::class, 'agency_subdistrict');
    }

    public function agency_districts(){
        return $this->belongsTo(Amphur::class,  'agency_district');
    }

    public function agency_provinces(){
        return $this->belongsTo(Province::class, 'agency_province');
    }

    public function getAppSubdistrictNameAttribute() {
        return !empty($this->applicant_subdistricts)?$this->applicant_subdistricts->DISTRICT_NAME:null;
    }

    public function getAppDistrictNameAttribute() {
        return !empty($this->applicant_districts)?str_replace('เขต','',$this->applicant_districts->AMPHUR_NAME):null;
    }

    public function getAppProvinceNameAttribute() {
        return !empty($this->applicant_provinces)?$this->applicant_provinces->PROVINCE_NAME:null;
    }

    public function getAgencySubdistrictNameAttribute() {
        return !empty($this->agency_subdistricts)?$this->agency_subdistricts->DISTRICT_NAME:null;
    }

    public function getAgencyDistrictNameAttribute() {
        return !empty($this->agency_districts)?str_replace('เขต','',$this->agency_districts->AMPHUR_NAME):null;
    }

    public function getAgencyProvinceNameAttribute() {
        return !empty($this->agency_provinces)?$this->agency_provinces->PROVINCE_NAME:null;
    }

    public function inspector_audit(){
        return $this->belongsTo(ApplicationInspectorAudit::class, 'id', 'application_id');
    }

    public function inspector_status(){
        return $this->belongsTo(ApplicationInspectorStatus::class, 'application_status', 'id');
    }

    public function app_scope(){
        return $this->hasMany(ApplicationInspectorScope::class, 'application_id');
    }

    public function inspectors(){
        return $this->belongsTo(Inspectors::class, 'application_no', 'ref_inspector_application_no');
    }

    public function section5_inspectors(){
        return $this->belongsTo(Inspectors::class, 'applicant_taxid', 'inspectors_taxid');
    }

    public function inspector_agreement(){
        return $this->belongsTo(InspectorsAgreement::class, 'id', 'application_id');
    }

    public function inspectors_accepts(){
        return $this->hasMany(ApplicationInspectorsAccept::class, 'application_id');
    }

    public function accepter(){
        return $this->belongsTo(User::class, 'accept_by');
    }

    public function user_created(){
        return $this->belongsTo(SSO_USER::class, 'created_by');
    }

    public function getInspectorsAcceptsFirstAttribute(){
        return $this->inspectors_accepts->first();
    }

    public function attach_files() {
        return $this->hasMany(AttachFile::class, 'ref_id')->where('ref_table', self::getTable());
    }

    public function getInspectorScopeGroupAttribute() {
        return @$this->app_scope->groupBy('branch_group_id');
    }

    public function getAppConfigEidenceAttribute() {
        return !empty($this->configs_evidence)?json_decode($this->configs_evidence):[];
    }

    public function getConfigsEvidencesAttribute() {
        return ConfigsEvidence::whereHas('configs_evidence_groups', function ($query) {
                                    return $query->where('state', 1);
                                })
                                ->where('evidence_group_id', 2)
                                ->where('state', 1)
                                ->orderBy('ordering')
                                ->get();
    }

    public function getScopeGroupAttribute(){

        $app_scope = $this->app_scope()->select('branch_group_id')->groupBy('branch_group_id')->get();
        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch_group = $item->bs_branch_group;

            if( !is_null($bs_branch_group) ){
                $list[] = $bs_branch_group->title;
            }

        }

        $txt = implode( ', ',  $list );

        return $txt;
    }

    public function getBranchGroupBranchNameAttribute() {
        $expenses  = $this->app_scope->groupBy('BranchGroupTitle');
        $html = '';

            foreach($expenses as $k1=> $datas){
                $html .= $k1."<br>";
                $arr = $datas->pluck('BranchTitle')->implode(', ');
                $html .= "<small><i>(".$arr.")</i></small><br>";
            }

        return $html;
    }

    public function getAgencyDataAdressAttribute()
    {

        $agency_provinces    = $this->agency_provinces;
        $agency_districts    = $this->agency_districts;
        $agency_subdistricts = $this->agency_subdistricts;


        $text = '';
        $text .= (!empty($this->agency_address)?$this->agency_address:null);
        $text .= ' ';
        $text .= (!empty($this->agency_moo)?'หมู่ที่ '.$this->agency_moo:null);
        $text .= ' ';

        if(!is_null($this->agency_soi) &&  $this->agency_soi != '-'){
            $text .= (!empty($this->agency_soi)?'ตรอก/ซอย '.$this->agency_soi:null);
            $text .= ' ';
        }
        if(!is_null($this->agency_road) &&  $this->agency_road != '-'){
            $text .= (!empty($this->agency_road)?'ถนน '.$this->agency_road:null);
            $text .= ' ';
        }

        $subdistrict = !empty($agency_provinces) && ($agency_provinces->PROVINCE_ID == 1) ? 'แขวง' : 'ตำบล';
        $text .= (!empty($agency_subdistricts)?$subdistrict.' '.$agency_subdistricts->DISTRICT_NAME:null);
        $text .= ' ';

        $district_name = !empty($agency_provinces) && ($agency_provinces->PROVINCE_ID  == 1) ? 'เขต' : 'อำเภอ';
        $text .= (!empty($agency_districts)?$district_name.' '.str_replace('เขต','',$agency_districts->AMPHUR_NAME):null);
        $text .= ' ';

        $text .= (!empty($agency_provinces)?'จังหวัด '.$agency_provinces->PROVINCE_NAME:null);
        $text .= ' ';
        $text .= (!empty($this->agency_zipcode)?$this->agency_zipcode:null);

        return  $text;
    }

    public function app_assign(){
        return $this->hasMany(ApplicationInspectorsStaff::class, 'application_id');
    }

    public function getListStaffAttribute(){

        $app_assign = $this->app_assign()->select('staff_id', 'assign_date')->get();
        $list = [];
        $date = null;
        foreach( $app_assign AS $item ){
            if( !is_null(  $item->StaffName ) ){
                $list[] = $item->StaffName;
            }
            $date  = HP::DateThaiFull($item->assign_date);
        }

        $txt = implode( ',<br>',  $list );
        return  $txt.( !empty( $date )?'<br>('.$date.')':null );
    }

    public function getApplicationStatusTitleAttribute(){
        $status = !is_null($this->inspector_status) ? $this->inspector_status->title : null ;
        if($this->application_status == 11 && !empty($this->remarks_delete)){
            $status .= "<br>({$this->remarks_delete})";
        }
        return $status;
    }

    // วันที่ขึ้นทะเบียนครั้งแรก default date current
    public function getFirstRegistrationDateAttribute(){
        $date = null;

        if(!empty(@$this->section5_inspectors->inspector_first_date)){
            $date = Carbon::parse($this->section5_inspectors->inspector_first_date)->addYear(543)->format('d/m/Y');
        }else if( !empty($this->inspector_agreement->start_date) &&  Carbon::hasFormat(@$this->inspector_agreement->start_date, 'Y-m-d')){
            $date = Carbon::parse($this->inspector_agreement->start_date)->addYear(543)->format('d/m/Y');
        }

        return $date;
    }

    // วันที่รับคำขอครั้งแรก
    public function getDateOfFirstRequestFullAttribute(){
        return @$this->InspectorsAcceptsFirst->DateOfFirstRequestFull;
    }

    // ผู้รับรับคำขอครั้งแรก
    public function getRequestRecipientAttribute(){
        return @$this->InspectorsAcceptsFirst->RequestRecipient;
    }


}
