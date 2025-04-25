<?php

namespace App\Models\Section5;

use HP;
use App\User;
use Carbon\Carbon;
use App\Models\Basic\Amphur;

use App\Models\Basic\District;

use App\Models\Basic\Province;
use App\Models\Sso\User AS SSO_USER;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\ApplicationIbcbAudit;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\ApplicationIbcbStaff;
use App\Models\Section5\ApplicationIbcbAccept;

use App\Models\Section5\ApplicationIbcbReport;
use App\Models\Section5\ApplicationIbcbStatus;
use App\Models\Section5\ApplicationIbcbGazette;
use App\Models\Section5\ApplicationIbcbBoardApprove;

class ApplicationIbcb extends Model
{
    protected $table = 'section5_application_ibcb';

    protected $primaryKey = 'id';

    protected $fillable = [ 
        'application_no',
        'application_date',
        'application_status',
        'application_type',
        'applicant_taxid',
        'applicant_name',
        'applicant_date_niti',
        'hq_address',
        'hq_building',
        'hq_moo',
        'hq_soi',
        'hq_road',
        'hq_subdistrict_id',
        'hq_district_id',
        'hq_province_id',
        'hq_zipcode',
        'ibcb_name',
        'ibcb_address',
        'ibcb_building',
        'ibcb_moo',
        'ibcb_soi',
        'ibcb_road',
        'ibcb_subdistrict_id',
        'ibcb_district_id',
        'ibcb_province_id',
        'ibcb_zipcode',
        'ibcb_phone',
        'ibcb_fax',
        'co_name',
        'co_position',
        'co_mobile',
        'co_phone',
        'co_fax',
        'co_email',
        'audit_type',
        'created_by',
        'updated_by',
        'config_evidencce',
        'remarks_delete', 
        'delete_by', 
        'delete_at',
        'delete_state',
        'accept_date',
        'accept_by',
        'ibcb_id', 
        'ibcb_code',
        'applicant_request_type'

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

    public function ibcb_subdistrict(){
        return $this->belongsTo(District::class, 'ibcb_subdistrict_id');
    }  
    
    public function ibcb_district(){
        return $this->belongsTo(Amphur::class,  'ibcb_district_id');
    }  

    public function ibcb_province(){
        return $this->belongsTo(Province::class, 'ibcb_province_id');
    }  

    public function application_ibcb_status(){
        return $this->belongsTo(ApplicationIbcbStatus::class, 'application_status');
    }  

    public function application_ibcb_accepts(){
        return $this->hasMany(ApplicationIbcbAccept::class, 'application_id');
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

    public function getHQPostcodeNameAttribute() {
        return !empty($this->hq_zipcode)?$this->hq_zipcode:null;
    }

    public function getIbcbSubdistrictNameAttribute() {
        return !empty($this->ibcb_subdistrict)?$this->ibcb_subdistrict->DISTRICT_NAME:null;
    }

    public function getIbcbDistrictNameAttribute() {
        return !empty($this->ibcb_district)?str_replace('เขต','',$this->ibcb_district->AMPHUR_NAME):null;
    }

    public function getIbcbProvinceNameAttribute() {
        return !empty($this->ibcb_province)?$this->ibcb_province->PROVINCE_NAME:null;
    }

    public function getIbcbPostcodeNameAttribute() {
        return !empty($this->ibcb_zipcode)?$this->ibcb_zipcode:null;
    }

    public function getStatusTitleAttribute() {
        return @$this->application_ibcb_status->title;
    }

    public function getStatusFullTitleAttribute() {
        $status = @$this->application_ibcb_status->title;
        if($this->application_status == 99 && !empty($this->remarks_delete)){
            $status .= "<br>({$this->remarks_delete})";
        }
        return $status;
    }

    public function scopes_group(){
        return $this->hasMany(ApplicationIbcbScope::class, 'application_id');
    } 

    public function getApplicationIbcbScopeTisAttribute() {
        return $this->scopes_group->pluck('ibcb_scopes_tis')->flatten();
    }

    public function getScopeGroupAttribute(){

        $app_scope = $this->scopes_group()->select('branch_group_id')->groupBy('branch_group_id')->get();
        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch_group = $item->bs_branch_group;

            if( !is_null($bs_branch_group) ){
                $list[] = $bs_branch_group->title;
            }

        }
        
        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function getBranchGroupBranchNameAttribute() {
        $expenses  = $this->scopes_group()->with(['bs_branch_group','scopes_details'])->groupBy('branch_group_id')->get();
        $html = '';

        foreach($expenses as $datas){
            $bs_branch_group = $datas->bs_branch_group;
            $html .= ($bs_branch_group->title)."<br>";
            $arr = $datas->scopes_details->pluck('BranchTitle')->implode(', ');
            $html .= "<small><i>(".$arr.")</i></small><br>";
        }

        return $html;
    }

    public function ibcb_audit(){
        return $this->belongsTo(ApplicationIbcbAudit::class, 'id','application_id');
    }  

    public function ibcb_report(){
        return $this->belongsTo(ApplicationIbcbReport::class, 'id', 'application_id');
    }  

    public function board_approve(){
        return $this->belongsTo(ApplicationIbcbBoardApprove::class, 'id', 'application_id');
    }  

    public function app_assign(){
        return $this->hasMany(ApplicationIbcbStaff::class, 'application_id');
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

    // วันที่ยื่นคำขอ รูปแบบ 31 มกราคม 2565
    public function getApplicationDateFullAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->application_date, 'Y-m-d')){
            $date = Carbon::parse($this->application_date)->addYear(543)->isoFormat('D MMMM YYYY');
        }
        return $date;
    }

    public function user_accept(){
        return $this->belongsTo(User::class, 'accept_by');
    }

    public function getAcceptNameAttribute() {
        return @$this->user_accept->reg_fname.' '.@$this->user_accept->reg_lname;
    }

    public function getAcceptDateFullAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->accept_date, 'Y-m-d')){
            $date = Carbon::parse($this->accept_date)->addYear(543)->isoFormat('D MMMM YYYY');
        }
        return $date;
    }

    public function user_created(){
        return $this->belongsTo(SSO_USER::class, 'created_by');
    }

    public function ibcb_gazette(){
        return $this->belongsTo(ApplicationIbcbGazette::class, 'id','application_id');
    }  

}
