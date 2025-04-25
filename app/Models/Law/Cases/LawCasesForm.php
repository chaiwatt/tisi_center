<?php

namespace App\Models\Law\Cases;

use DB;
use HP;
use HP_Law;
use App\User;
use App\Models\Basic\Tis;
use App\Models\Sso\User as SSO_USER;

use App\Models\Basic\TisiLicense;
use App\Models\Besurv\Department;

use App\Models\Basic\SubDepartment;
use App\Models\Law\Basic\LawArrest;
use App\Models\Basic\Amphur as District;
use App\Models\Law\Basic\LawSection;
use App\Models\Law\Basic\LawBookType;   
use App\Models\Law\Basic\LawResource; 

use App\Models\Law\Log\LawLogWorking; 

use App\Models\Law\Reward\LawRewards;
use App\Models\Basic\Province as Province;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawDepartment;  
use App\Models\Law\Basic\LawOffendType;
use App\Models\Law\Offense\LawOffender;
use Illuminate\Database\Eloquent\Model; 
use App\Models\Law\Basic\LawRewardGroup;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Basic\District as Subdistrict;
use App\Models\Law\Cases\LawCasesImpound;

use App\Models\Law\Cases\LawCasesFactBook;
use App\Models\Law\Cases\LawCasesLicenses;


use App\Models\Law\Cases\LawCasesStandard;
use App\Models\Law\Config\LawConfigReward;
use App\Models\Law\Cases\LawCasesOperation;

use App\Models\Law\Cases\LawCasesStaffList;
use App\Models\Law\Cases\LawCasesBookOffend;

use App\Models\Law\Offense\LawOffenderCases;

use App\Models\Law\Config\LawConfigRewardSub;
use App\Models\Law\Cases\LawCasesLevelApprove;
use App\Models\Law\Cases\LawCasesLicenseResult;

use App\Models\Law\Cases\LawCasesResultSection;
use App\Models\Law\Cases\LawCasesImpoundProduct;

class LawCasesForm extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_cases';
  
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

    protected $fillable =
    [
        'ref_no', 'case_number', 'assign_by', 'assign_at', 'lawyer_check', 'lawyer_by',   'lawyer_at', 'offend_applicanttype_id', 'offend_person_type', 'offend_condition', 'owner_department_name', 'owner_sub_department_id', 'owner_basic_department_id','owner_basic_department_other',
        'owner_case_by', 'owner_name', 'owner_email', 'owner_taxid', 'owner_tel', 'owner_phone', 'owner_contact_options', 'owner_contact_name', 'owner_contact_phone', 'owner_depart_type',
        'owner_contact_email', 'offend_date', 'offend_license_type', 'offend_tb4_tisilicense_id', 'offend_license_number', 'offend_license_notify', 'offend_sso_users_id', 'offend_name',
        'offend_taxid', 'offend_address', 'offend_tel', 'offend_email', 'offend_power', 'tis_id', 'tb3_tisno', 'offend_contact_name', 'offend_contact_name', 'offend_contact_tel',
        'offend_contact_email', 'law_basic_arrest_id', 'law_basic_offend_type_id', 'offend_ref_tb', 'offend_ref_no', 'law_basic_section_id', 'config_evidence', 'status',
        'notify_email_type', 'notify_email_list', 'cancel_by', 'cancel_at', 'cancel_remark', 'accept_by', 'accept_at', 'accept_remark', 'status_close', 'close_date', 'close_by', 'close_remark', 'created_by', 'updated_by',
        'compare_type', 'compare_remark', 'compare_by', 'compare_at', 'compare_date',
        'offend_moo', 'offend_soi', 'offend_building', 'offend_street','offend_subdistrict_id','offend_district_id','offend_province_id','offend_zipcode', 'status_license', 'status_impound', 'offend_report_date','offend_accept_date',
        'offend_impound_type','offend_location_detail','offend_product_detail','foreign','approve_type','status_approve'
    ];

    protected $casts    = ['offend_power' => 'array', 'law_basic_section_id' => 'array', 'notify_email_type' => 'array'];
    protected $appends  = ['sectionlist'];

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
  
    public function user_close_by(){
        return $this->belongsTo(User::class, 'close_by');
    }

    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function getCloseNameAttribute() {
        return @$this->user_close_by->reg_fname.' '.@$this->user_close_by->reg_lname;
    }
  
    public function getStateIconAttribute() {
        $btn = '';
  
        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function tis(){
        return $this->belongsTo(Tis::class, 'tis_id');
    }

    public function getTisNameAttribute() {
        return @$this->tis->tb3_TisThainame;
    }
  
     //  ผู้บันทึกผลการพิจารณาแจ้งงานคดี 
    public function user_accept_to(){
        return $this->belongsTo(User::class,'accept_by');
    }

    //  ข้อมูลมอบหมายครั้งล่าสุด
    public function law_cases_assign_to(){
        return $this->belongsTo(LawCasesAssign::class,'id','law_case_id')->orderby('id','desc');
    }
  
    // ผลการพิจารณาแจ้งงานคดี  (หลักฐานผลพิจารณา)
    public function AttachFileDocument(){
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_document');
    }

    public function status_color_list() {
        return [ 
                    '99' => 'dark', 
                    '98' => 'success',
                    '0'  => 'danger', 
                    '1'  => 'success',
                    '2'  => 'warning',
                    '3'  => 'warning',
                    '4'  => 'success',
                    '5'  => 'dark',
                    '6'  => 'dark', 
                    '7'  => 'danger',
                    '8'  => 'dark',
                    '9'  => 'success',
                    '10' => 'dark',
                    '11' => 'dark',
                    '12' => 'success',
                    '13' => 'dark',
                    '14' => 'dark',
                    '15' => 'success'
                ];
    }

    public static function status_list() {
        return [ 
                    '99' => 'ยกเลิก',
                    '98' => 'แจ้งงานคดีสำเร็จ(รอผู้มีอำนาจพิจารณา)',
                    '0'  => 'ฉบับร่าง',
                    '1'  => 'แจ้งงานคดีสำเร็จ',
                    '2'  => 'อยู่ระหว่างตรวจสอบข้อมูล',
                    '3'  => 'ขอข้อมูลเพิ่มเติม (ตีกลับ)',
                    '4'  => 'ข้อมูลครบถ้วน (อยู่ระหว่างพิจารณา)',
                    '5'  => 'พบการกระทำความผิด',
                    '6'  => 'ไม่พบการกระทำความผิด',
                    '7'  => 'ส่งเรื่องดำเนินคดี',
                    '8'  => 'แจ้งการกระทำความผิด',
                    '9'  => 'ยินยอมเปรียบเทียบปรับ',
                    '10' => 'ไม่ยินยอมเปรียบเทียบปรับ',
                    '11' => 'บันทึกผลแจ้งเปรียบเทียบปรับ',
                    '12' => 'ตรวจสอบการชำระเงินแล้ว',
                    '13' => 'ดำเนินการกับใบอนุญาต',
                    '14' => 'ดำเนินการกับผลิตภัณฑ์',
                    '15' => 'ดำเนินการเสร็จสิ้น'
                ];
    }

    public static function status_list_filter() {
        return [ 
                    '99' => 'ยกเลิก',
                    '98' => 'แจ้งงานคดีสำเร็จ(รอผู้มีอำนาจพิจารณา)',
                    '0'  => 'ฉบับร่าง',
                    '1'  => 'แจ้งงานคดีสำเร็จ',
                    '2'  => 'อยู่ระหว่างตรวจสอบข้อมูล',
                    '3'  => 'ขอข้อมูลเพิ่มเติม (ตีกลับ)',
                    '4'  => 'ข้อมูลครบถ้วน (อยู่ระหว่างพิจารณา)',
                    '5'  => 'พบการกระทำความผิด',
                    '6'  => 'ไม่พบการกระทำความผิด',
                    '7'  => 'ส่งเรื่องดำเนินคดี',
                    '8'  => 'แจ้งการกระทำความผิด',
                    '9'  => 'ยินยอมเปรียบเทียบปรับ',
                    '10' => 'ไม่ยินยอมเปรียบเทียบปรับ',
                    '11' => 'บันทึกผลแจ้งเปรียบเทียบปรับ',
                    '12' => 'ตรวจสอบการชำระเงินแล้ว',
                    '15' => 'ดำเนินการเสร็จสิ้น'
                ];
    }

    //  สถานะ
    public function getStatusTextAttribute() {
        return   !is_null($this->status) && array_key_exists($this->status,$this->status_list()) ? $this->status_list()[$this->status] : ''  ;
    }

    // สถานะ (สี)
    public function getStatusColorHtmlAttribute() {
        return  !is_null($this->status) && array_key_exists($this->status, $this->status_color_list()) ? '<span class="text-'.$this->status_color_list()[$this->status].'">'.$this->status_list()[$this->status].'</span>' : '-';
    }

    // สถานะ : ดำเนินการใบอนุญาต (สี)
    public function getStatusLicenseColorHtmlAttribute() {
        return  (!is_null($this->status_license) && $this->status_license==1) ? '<span class="text-success">ดำเนินการเรียบร้อย</span>' : '<span class="text-muted">รอดำเนินการ</span>';
    }

    // สถานะ : ดำเนินการผลิตภัณฑ์ (สี)
    public function getStatusImpoundColorHtmlAttribute() {
        return  (!empty($this->status_impound) && $this->status_impound==1) ? '<span class="text-success">ดำเนินการเรียบร้อย</span>' : '<span class="text-muted">รอดำเนินการ</span>';
    }

    // มอบหมาย
    public function user_assign_to(){
        return $this->belongsTo(User::class, 'assign_by');
    }
    
    // นิติกรผู้รับผิดชอบ
    public function user_lawyer_to(){
        return $this->belongsTo(User::class, 'lawyer_by');
    }

    public function getLawyerNameAttribute() {
        return @$this->user_lawyer_to->reg_fname.' '.@$this->user_lawyer_to->reg_lname;
    }

    public function getSectionlistAttribute() {
        return LawSection::whereIn('id', (array)$this->law_basic_section_id)->get();
    }

    public function getSectionListNameAttribute(){
        $result = '<i class="text-muted">ไม่ระบุ</i>';
        if(!empty($this->law_basic_section_id)){
            $result = LawSection::whereIn('id', $this->law_basic_section_id)->pluck('number','number')->implode(', ');
        }
        return  $result;
    }

    public function getNameAndTaxidAttribute() {
          $html = '';
          $html .= !empty($this->offend_name)?$this->offend_name:'';
          $html .= '<br>';
          $html .= !empty($this->offend_taxid)?$this->offend_taxid:'';
        return  $html;
    }

    public function getTisnoAndLicenseNumberAttribute() {
          $html = '';
          $html .= !empty($this->tb3_tisno)?$this->tb3_tisno:'';
          $html .= '<br>';
          $html .= !empty($this->offend_license_number)?$this->offend_license_number:'';
        return  $html;

    }   
    // มีการจับกุม
    public function law_basic_arrest_to(){
        return $this->belongsTo(LawArrest::class, 'law_basic_arrest_id');
    }

   // สาเหตุที่พบ
    public function law_basic_offend_type_to(){
        return $this->belongsTo(LawOffendType::class, 'law_basic_offend_type_id');
    }

    // เลขที่อ้างอิงยึด-อายัด
    public function law_offend_type_to(){
        return $this->belongsTo(LawBookType::class, 'ref_id');
    }

    
    // ข้อมูลการกระทำความผิด
    public function law_cases_result_to(){
        return $this->belongsTo(LawCasesResult::class, 'id','law_case_id');
    }

    // บันทึกดำเนินการกับผลิตภัณฑ์
    public function law_cases_impound_to(){
        return $this->belongsTo(LawCasesImpound::class, 'id','law_case_id');
    }

    public function getCasesIDAttribute() {
        return @$this->cases_impound->law_case_id;
    }

    //ยึดอายัด
    public function cases_impound() {
        return $this->hasMany(LawCasesImpound::class, 'law_case_id')->with(['impound_product']);
    }

    public function attach_files(){
        return $this->hasMany(AttachFileLaw::class,'ref_id')->where('ref_table',$this->table);
    }

    // หลักฐานบันทึกคำให้การ (เปรียบเทียบปรับ)
    public function file_law_cases_compare_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','compare')->orderby('id','desc');
    }


    // ข้อมูลการชำระเงินค่าปรับ
    public function law_cases_payments_to()
    {
         return $this->belongsTo(LawCasesPayments::class,'id','ref_id')->where('ref_table',$this->getTable())->orderby('id','desc');
    }
    public function law_cases_payments_cancel_status_to()
    {
         return $this->belongsTo(LawCasesPayments::class,'id','ref_id')->where('ref_table',$this->getTable())->whereNull('cancel_status')->orderby('id','desc');
    }
    public function law_cases_payments_cancel_status_many()
    {
         return $this->hasMany(LawCasesPayments::class,'ref_id')->where('ref_table',$this->getTable())->where('cancel_status','1');
    }
    public function law_cases_payments_many() {
        return $this->hasMany(LawCasesPayments::class, 'ref_id')->where('ref_table',$this->getTable());
    }

    //ผลเปรียบเทียบปรับ
    public function law_cases_compare_to(){
        return $this->belongsTo(LawCasesCompare::class, 'id','law_cases_id');
    }

    public function law_deparment(){
        return $this->belongsTo(LawDepartment::class, 'owner_basic_department_id');
    }

    public function sub_deparment(){
        return $this->belongsTo(SubDepartment::class, 'owner_sub_department_id');
    }

    public function getDeparmentTypeNameAttribute() {
        $type = [ 1 => 'ภายใน', 2 => 'ภายนอก' ];
        return array_key_exists( $this->owner_depart_type, $type )?$type[ $this->owner_depart_type ]:null;
    }

    public function getOwnerDeparmentNameAttribute() {
        $deparment = null;
        if( $this->owner_depart_type == 1){
            $deparment = !is_null($this->sub_deparment) && !empty($this->sub_deparment->sub_departname)?$this->sub_deparment->sub_departname:null;
        }else{
            $deparment = !is_null($this->law_deparment) && !empty($this->law_deparment->title)?$this->law_deparment->title:null;
        }
        return  !empty($deparment)?$deparment:$this->owner_department_name;
    }

    public function getOwnerDeparmentOtherAttribute() {
        $deparment_other = null;
        if( $this->owner_depart_type == 2){
            $deparment_other = (!empty($this->law_deparment->other) && $this->law_deparment->other == 1 )?$this->owner_basic_department_other:null;
        }
        return  !empty($deparment_other)?$deparment_other:null;
    }

    public function tb4_tisilicense(){
        return $this->belongsTo(TisiLicense::class, 'offend_tb4_tisilicense_id');
    }

    // คำนวณสินบน
    public function law_reward_to(){
        return $this->belongsTo(LawRewards::class, 'id','law_case_id');
    }
    public function law_reward_many() {
        return $this->hasMany(LawRewards::class, 'law_case_id','id');
    }
    public function user_offend(){
        return $this->belongsTo(SSO_USER::class,'offend_sso_users_id');
 
    }

    public function law_case_operations(){
        return $this->hasOne(LawCasesOperation::class,'law_cases_id');
    }

    public function offend_subdistricts(){
        return $this->belongsTo(Subdistrict::class, 'offend_subdistrict_id');
    }

    public function offend_districts(){
        return $this->belongsTo(District::class,  'offend_district_id');
    }

    public function offend_provinces(){
        return $this->belongsTo(Province::class, 'offend_province_id');
    }

    public function getOffendSubdistrictNameAttribute() {
        return !empty($this->offend_subdistrict)?$this->offend_subdistrict->DISTRICT_NAME:null;
    }

    public function getOffendDistrictNameAttribute() {
        return !empty($this->offend_district)?$this->offend_district->AMPHUR_NAME:null;
    }

    public function getOffendProvinceNameAttribute() {
        return !empty($this->offend_province)?$this->offend_province->PROVINCE_NAME:null;
    }

    public function getOffendDataAdressAttribute()
    {
        $offend_provinces    = $this->offend_provinces;
        $offend_districts    = $this->offend_districts;
        $offend_subdistricts = $this->offend_subdistricts;

        $text = '';
        $text .= (!empty($this->offend_address)?trim($this->offend_address):null);
        $text .= !empty($this->offend_address)?' ':'';
        $text .= (!empty($this->offend_moo)?'หมู่ที่ '.trim($this->offend_moo):null);
        $text .= !empty($this->offend_moo)?' ':'';

        if(!is_null($this->offend_soi) &&  $this->offend_soi != '-'){
            $text .= (!empty($this->offend_soi)?'ตรอก/ซอย '.trim($this->offend_soi):null);
            $text .= ' ';
        }
        if(!is_null($this->offend_street) &&  $this->offend_street != '-'){
            $text .= (!empty($this->offend_street)?'ถนน '.trim($this->offend_street):null);
            $text .= ' ';
        }

        $subdistrict_perfix = !empty($offend_provinces) && ($offend_provinces->PROVINCE_ID == 1) ? 'แขวง' : 'ตำบล';
        $text .= (!empty($offend_subdistricts)?$subdistrict_perfix.trim( str_replace("แขวง","",$offend_subdistricts->DISTRICT_NAME) ):null);
        $text .= !empty($offend_subdistricts)?' ':'';

        $district_prefix = !empty($offend_provinces) && ($offend_provinces->PROVINCE_ID  == 1) ? 'เขต' : 'อำเภอ';
        $text .= (!empty($offend_districts)?$district_prefix.trim( str_replace("เขต","",$offend_districts->AMPHUR_NAME) ):null);
        $text .= !empty($offend_districts)?' ':'';

        $text .= (!empty($offend_provinces)?'จังหวัด'.trim($offend_provinces->PROVINCE_NAME):null);
        $text .= !empty($offend_provinces)?' ':'';

        $text .= (!empty($this->offend_zipcode)?$this->offend_zipcode:(  !empty($offend_districts)?trim($offend_districts->POSTCODE):null ));

        return  $text;
    }

    public function impound_products()
    {
        return $this->hasManyThrough(LawCasesImpoundProduct::class, LawCasesImpound::class, 'law_case_id', 'law_case_impound_id', 'id', 'id' );
    }

    public function result_section()
    {
        return $this->hasManyThrough(LawCasesResultSection::class, LawCasesResult::class, 'law_case_id', 'law_case_result_id', 'id', 'id' );
    }

    public function product_result(){
        return $this->belongsTo(LawCasesProductResult::class, 'id', 'law_cases_id');
    }

    //ประวัติความผิด
    public function offender(){
        return $this->belongsTo(LawOffender::class, 'offend_taxid', 'taxid');
    }

    //ประวัติความผิดที่ฝ่าฝืน
    public function offender_cases()
    {
        return $this->hasManyThrough(LawOffenderCases::class, LawOffender::class, 'taxid', 'law_offender_id', 'offend_taxid', 'id' );
    }
    public function offender_cases_many()
    {
        return $this->hasMany(LawOffenderCases::class,'law_cases_id','id');
    }
   // จัดทําหนังสือแจ้งเปรียบเทียบปรับ พิมพ์หนังสือข้อเท็จจริง (เปรียบเทียบปรับ)
    public function fact_books(){
        return $this->belongsTo(LawCasesFactBook::class, 'id', 'law_cases_id');
    }

    public function offend_books(){
        return $this->belongsTo(LawCasesBookOffend::class, 'id', 'law_cases_id');
    }

    public function cases_staff() {
        return $this->hasMany(LawCasesStaffList::class, 'law_cases_id');
    }

     // จัดทําหนังสือแจ้งเปรียบเทียบปรับ คำนวน (เปรียบเทียบปรับ)
    public function compare_calculate(){
        return $this->hasMany(LawCasesCompareCalculate::class, 'law_cases_id');
    }
     // จัดทําหนังสือแจ้งเปรียบเทียบปรับ พิมพ์หนังสือเปรียบเทียบ (เปรียบเทียบปรับ)
     public function compare_book(){
        return $this->belongsTo(LawCasesCompareBook::class, 'id', 'law_cases_id');
    }
    // บันทึกผลยินยอมเปรียบเทียบปรับ (เปรียบเทียบปรับ)
    public function law_log_working_compares_to()
    {
         return $this->belongsTo(LawLogWorking::class,'id','ref_id')->where('ref_table',$this->getTable())->whereIn('status',['ยินยอมเปรียบเทียบปรับ','ไม่ยินยอมเปรียบเทียบปรับ'])->orderby('id','desc');
    }
      // ผลพิจารณาเปรียบเทียบปรับ (เปรียบเทียบปรับ)
    public function law_log_working_consider_adjusting_to()
    {
         return $this->belongsTo(LawLogWorking::class,'id','ref_id')->where('ref_table',$this->getTable())->whereIn('status',['บันทึกผลแจ้งเปรียบเทียบปรับ','ส่งเรื่องดำเนินคดี'])->orderby('id','desc');
    }

    // ประวัติ (ตีกลับ)
    public function law_log_working_bounce_many()
    {
        return $this->hasMany(LawLogWorking::class,'ref_id','id')->where('ref_table',$this->getTable())->whereIn('status',['ขอข้อมูลเพิ่มเติม (ตีกลับ)']);
    }

    // ประวัติ บันทึกผลตรวจสอบเอกสาร
    public function law_log_working_title_many()
    {
        return $this->hasMany(LawLogWorking::class,'ref_id','id')->where('ref_table',$this->getTable())->whereIn('title',['บันทึกผลตรวจสอบเอกสาร']);
    }

    public function getWorkingBounceTextAttribute(){
        $result = '';
        if(!empty($this->law_log_working_bounce_many) && count($this->law_log_working_bounce_many) > 0){
            foreach($this->law_log_working_bounce_many as $key => $bounce){
                    if($key > 0){
                        $result .= '<br/>';
                    }
                    if(!empty($bounce->remark)){
                        $result .= 'ครั้งที่ '.($key+1).' : '.$bounce->remark;
                    }else{
                        $result .= 'ครั้งที่ '.($key+1).' : -';
                    }
            }
        }
    
        return $result; 
    }

    public function getWorkingBounceListAttribute(){
        $results = [];
        if(!empty($this->law_log_working_bounce_many) && count($this->law_log_working_bounce_many) > 0){
            foreach($this->law_log_working_bounce_many as $key => $bounce){

                $file_attachs = $bounce->AttachFiles->get();
                $file_list    = [];
                foreach($file_attachs as $file_attach){
                    if(HP::checkFileStorage($file_attach->url)){
                        $file_list[] = HP::getFileStorage($file_attach->url);
                    }
                }

                $results[] = [
                                'index' => $key+1,
                                'remark' => (!empty($bounce->remark) ? nl2br($bounce->remark) : '-'),
                                'user_created' => $bounce->CreatedName,
                                'file_attach' => $file_list
                             ];
            }
                            
        }

        return $results; 
    }

    public function getSectionListTextAttribute(){
        $result = [];
        if(!empty($this->offender_cases_many) && count($this->offender_cases_many) > 0){
            foreach($this->offender_cases_many as $cases){
                $sections = LawSection::whereIn('id', $cases->section)->pluck('number');
                if(count($sections)){
                    foreach($sections as $itme){
                        $result[$itme]   = $itme;
                    }
                }
            }
        }
    
        return  count($result) > 0 ? implode(', ',$result) : ''; 
    }

    public function license_result(){
        return $this->belongsTo(LawCasesLicenseResult::class, 'id','law_case_id');
    }
    
    public function cases_standards() {
        return $this->hasMany(LawCasesStandard::class, 'law_cases_id','id');
    }

    public function getStandardNoAttribute(){
        $result = '';
        if(!empty($this->cases_standards) && count($this->cases_standards) > 0){
            $result =  $this->cases_standards->pluck('tb3_tisno')->implode(', ');
            
        }
        return  $result; 
    }

    public function getStandardNameAttribute(){
        $result = '';
        if(!empty($this->cases_standards) && count($this->cases_standards) > 0){
            $tis_id =  $this->cases_standards->pluck('tis_id');

            if(!empty($tis_id) && count($tis_id) > 0){
                $result = Tis::whereIn('tb3_TisAutono', $tis_id)->pluck('tb3_TisThainame')->implode(', ');
            }
        }
        return  $result;  
    }

    public function cases_licenses() {
        return $this->hasMany(LawCasesLicenses::class, 'law_cases_id','id');
    }

    public function getLicenseNumberAttribute(){
        $result = '';
        if(!empty($this->cases_standards) && count($this->cases_standards) > 0){
            $result =  $this->cases_licenses->pluck('license_number')->implode(', ');
            
        }
        return  $result; 
    }
    // start พิจารณางานคดี

    public function  law_cases_level_approve_to() {
       
        return $this->belongsTo(LawCasesLevelApprove::class, 'id','law_cases_id');
    }
    public function law_cases_level_approve1(){
        return $this->belongsTo(LawCasesLevelApprove::class,'id','law_cases_id')->where('level','1');
    }
    public function law_cases_level_approve2(){
        return $this->belongsTo(LawCasesLevelApprove::class,'id','law_cases_id')->where('level','2');
    }
    public function law_cases_level_approve3(){
        return $this->belongsTo(LawCasesLevelApprove::class,'id','law_cases_id')->where('level','3');
    }
    public function law_cases_level_approve4(){
        return $this->belongsTo(LawCasesLevelApprove::class,'id','law_cases_id')->where('level','4');
    }
    public function law_cases_level_approve5(){
        return $this->belongsTo(LawCasesLevelApprove::class,'id','law_cases_id')->where('level','5');
    }
    // end พิจารณางานคดี

    //พิจารณา
    public function cases_level_approve() {
        return $this->hasMany(LawCasesLevelApprove::class, 'law_cases_id');
    }

    public static function approve_list() {
        return [ 
                    '1' => 'ขอพิจารณาผ่านระบบ',
                    '2' => 'พิจารณานอกระบบ'
                ];
    }

    public function getApproveTypeTextAttribute() {
        $list = self::approve_list();
        $text = array_key_exists($this->approve_type,$list)?$list[$this->approve_type]:null;
       return $text;
    }

    
    public function getSubDepartShortnameAttribute(){
        $result = '';
        if(!empty($this->cases_level_approve) && count($this->cases_level_approve) > 0){
            $did =  $this->cases_level_approve->pluck('send_department');

            if(!empty($did) && count($did) > 0){
                $depart =  Department::whereIn('did', $did)->pluck('depart_nameShort');
                $result = '('.(count($depart) > 0?$depart->implode(', ') :'ไม่ระบุ').')';
            }
            
        }
        return  $result; 
    }

    public function getCsesLevelApproveRoleAttribute(){
        $result = [];
        $roles  = [ 
            '7'=>'จนท',
            '6'=>'ผก',
            '5'=>'ผอ',
            '4'=>'ทป',
            '2'=>'รมอ',
            '1'=>'ลมอ'
          ];
        if(!empty($this->cases_level_approve) && count($this->cases_level_approve) > 0){
            foreach($this->cases_level_approve as $approve){
                  if(  array_key_exists($approve->role,$roles) ){
                    $result[]  =  $roles[$approve->role];
                   }
            }
            
        }
        return   '('.(count($result) > 0? implode(', ',$result) :'ไม่ระบุ').')';  
    }

    public function getShortnameDepartmentAttribute(){
        $result = '';

        $depart_type =  !empty($this->owner_depart_type)?$this->owner_depart_type:1;
        $owner_sub_department = [];
          if($depart_type == '1'){
      
              $sql = "(CASE 
                          WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                          ELSE  department.depart_nameShort
                      END) AS title";
      
              $owner_sub_department = SubDepartment::leftjoin((new Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                      ->select( DB::raw($sql), 'sub_id' )
                                      ->pluck('title','sub_id')->toArray();
      

              $result   = array_key_exists($this->owner_sub_department_id, $owner_sub_department) ? $owner_sub_department[$this->owner_sub_department_id]:'';
          }
      
        $owner_basic_department = [];
          if($depart_type == '2'){
              $owner_basic_department = LawDepartment::where('type', 2)->where('state',1)->pluck('title_short','id')->toArray();
              $result   = array_key_exists($this->owner_basic_department_id, $owner_basic_department) ? $owner_basic_department[$this->owner_basic_department_id]:'';
      
          }

        return  $result; 
    }

    // สัดส่วนผู้มีสิทธิ์ได้รับเงิน
    public function law_config_reward()
    {
        return $this->hasMany(LawConfigReward::class, 'arrest_id', 'law_basic_arrest_id');
    }

    public function getLawRewardGroupArrayIDAttribute(){
        $result = [];
        if(!empty($this->law_config_reward) && count($this->law_config_reward) > 0){
             $config_reward_id  =  $this->law_config_reward->pluck('id');
             if( count($config_reward_id) > 0){
                $result            = LawConfigRewardSub::whereIn('law_config_reward_id',$config_reward_id)->pluck('reward_group_id');
                $law_reward_group_id = LawRewardGroup::where('title', 'ผู้แจ้งเบาะแส')->value('id');
                if(!empty($law_reward_group_id)){
                    $result->push($law_reward_group_id);
                }
             }
        }
        return  $result; 
    }

    public function getUserAssignsArrayAttribute(){
        $result = [];

        $subdepart_ids     = ['0600','0601','0602','0603','0604'];//เจ้าหน้าที่ กม.
        $users = User::selectRaw('runrecno AS id, reg_subdepart, reg_email AS email, CONCAT(reg_fname," ",reg_lname) As title')
                        ->whereHas('data_list_roles', function($query){
                                $query->whereIn('role_id', ['49']);
                        })
                        ->whereIn('reg_subdepart',$subdepart_ids)
                        ->get();  
        if(count($users) > 0){
            foreach($users as $user){
                $object                 = (object)[]; 
                $object->id             =  $user->id; 
                $object->title          =  $user->title;
                $object->email          =  $user->email;
                $result[]               = $object;
            }
        }
        return  $result; 
    }

    public function getUserLawyerArrayAttribute(){
        $result = [];
        if(!empty($this->assign_by) ){
       
                $reg_subdepart  = User::where('runrecno',$this->assign_by)->value('reg_subdepart');
            
             if(!empty($reg_subdepart)){
                $users = User::selectRaw('runrecno AS id, reg_subdepart,  reg_email AS email,  CONCAT(reg_fname," ",reg_lname) As title')
                                    ->whereHas('data_list_roles', function($query){
                                            $query->whereNotIn('role_id', ['49']);
                                    })
                                ->where('reg_subdepart',$reg_subdepart)
                                ->get();  
                if(count($users) > 0){
                    foreach($users as $user){
                        $object                 = (object)[]; 
                        $object->id             =  $user->id; 
                        $object->title          =  $user->title;
                        $object->email          =  $user->email;
                        $result[]               = $object;
                    }
                }
             }
        }
        return  $result; 
    }
}
 