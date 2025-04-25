<?php

namespace App\Models\Certify\Applicant;

use HP;

use App\User;
use App\RoleUser;
use Carbon\Carbon;

use App\CertificateExport;
use App\Models\Basic\Amphur;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\Models\Bcertify\Formula;
use Illuminate\Support\Facades\DB;
use App\Models\Bcertify\TestBranch;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Bcertify\PurposeType;
use App\Models\Certify\BoardAuditor;
use App\Models\Sso\User AS SSO_User;
use App\Models\Bcertify\InspectBranch;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\Applicant\Report;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certify\CertificateHistory;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Bcertify\LabRequestRejectTracking;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\CertiEmailLt;  //E-mail ลท.
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\Applicant\Cost;  // การประมาณค่าใช้จ่าย

class CertiLab extends Model
{
    use Sortable;

    protected $table = "app_certi_labs";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_no',
                            'applicanttype_id',
                            'name',
                            'status',
                            'trader_id',
                            'token',
                            'email',
                            'attach',
                            'attach_pdf',
                            'attach_pdf_client_name',
                            'checkbox_confirm',
                            'province',
                            'amphur',
                            'district',
                            'postcode',
                            'tel',
                            'tel_fax',
                            'contactor_name',
                            'contact_tel',
                            'telephone',
                            'management_lab',
                            'desc_delete',
                            'deleted_by',
                            'deleted_at',
                            'created_at',
                            'updated_at',
                            'lab_latitude',
                            'lab_longitude',
                            'lab_name',
                            'lab_name_en',
                            'lab_name_short',
                            'lab_address_no_eng',
                            'lab_moo_eng',
                            'lab_soi_eng',
                            'lab_street_eng',
                            'lab_province_eng',
                            'lab_amphur_eng',
                            'lab_district_eng',
                            'lab_postcode_eng',
                            'subgroup',
                            'get_date',
                            'purpose_type',
                            'standard_id',
                            'lab_type',
                            'created_by',
                            'tax_id',
                            'agent_id',
                            'certificate_exports_id',
                            'accereditation_no',
                            'type_standard',
                            'branch_name',
                            'branch_type',
                            'branch',
                            'start_date',
                            'same_address',
                            'address_no',
                            'allay',
                            'village_no',
                            'road',
                            'hq_address',
                            'hq_moo',
                            'hq_soi',
                            'hq_road',
                            'hq_subdistrict_id',
                            'hq_district_id',
                            'hq_province_id',
                            'hq_zipcode',
                            'hq_date_registered',
                            'hq_telephone',
                            'hq_fax',
                            'require_scope_update',
                            'scope_view_signer_id',
                            'scope_view_status',
                            'transferer_user_id',
                            'transferer_export_id'
                         ];
 public $sortable = ['id','app_no','name','trader_id','lab_type'];


    public function getStatusTitleAttribute() {
         return array_key_exists($this->status, HP::DataStatusCertify())  ?  HP::DataStatusCertify()[$this->status] : null;
      }


    public function attach()
    {
        return $this->hasMany( CertiLabAttach::class,'app_certi_lab_id' );
    }

    public function checkbox()
    {
        return $this->hasMany( CertiLabCheckBox::class,'app_certi_lab_id' );
    }

    public function employee()
    {
        return $this->hasMany( CertiLabEmployee::class,'app_certi_lab_id' );
    }

    public function info()
    {
        return $this->hasMany( CertiLabInfo::class,'app_certi_lab_id' );
    }

    public function purposeType()
    {
        return $this->belongsTo(PurposeType::class, 'purpose_type');
    }   

    public function material()
    {
        return $this->hasMany( CertiLabMaterialLef::class,'app_certi_lab_id' );
    }

    public function place()
    {
        return $this->hasMany( CertiLabPlace::class,'app_certi_lab_id' );
    }

    public function program()
    {
        return $this->hasMany( CertiLabProgram::class,'app_certi_lab_id' );
    }

    public function information()
    {
        return $this->hasMany( Information::class,'app_certi_lab_id' );
    }


    public function check() {
        return $this->hasOne(Check::class, 'app_certi_lab_id');
    }

    public function assessment() {
        return $this->hasOne(Assessment::class, 'app_certi_lab_id');
    }

    public function notices() {
        return $this->hasMany(Notice::class, 'app_certi_lab_id');
    }

    public function costs() {
        return $this->hasMany(Cost::class, 'app_certi_lab_id');
    }

    public function costs_draft_not() {
        return $this->hasMany(Cost::class, 'app_certi_lab_id','id')->whereNotIn('draft',[0,1,3]);
    }

    public function trader() {
        return $this->belongsTo(SSO_User::class, 'trader_id', 'id');
    }

    public function EsurvTrader()
    {
        return $this->belongsTo(SSO_User::class, 'tax_id', 'tax_number');
    }
    public function user_created(){
        return $this->belongsTo(SSO_User::class, 'created_by');
      }



    public function cost_assessment() {
        return $this->hasOne(CostAssessment::class, 'app_certi_lab_id');
    }

    public function cost_certificate() {
        return $this->hasOne(CostCertificate::class, 'app_certi_lab_id');
    }

    public function get_standard(){
        return $this->hasOne(Formula::class,'id','standard_id');
    }

    public function certi_test_scope(){
        return $this->hasMany(CertifyTestScope::class,'app_certi_lab_id');
    }

    public function certi_lab_calibrate(){
        return $this->hasMany(CertifyLabCalibrate::class,'app_certi_lab_id');
    }
    public function certi_tools_test(){

        return $this->hasMany(CertiToolsTest::class,'app_certi_lab_id');
    }
    // ขอบข่าย
    public function Certi_Lab_State1_FileTo()
    {
        return $this->belongsTo(CertLabsFileAll::class,'id','app_certi_lab_id')->where('state',1);
    }


    public function CertiLABFileTo()
    {
        return $this->belongsTo(CertLabsFileAll::class,'id','app_certi_lab_id');
    }

    public function app_cert_lab_file_all()
    {
        return $this->hasMany(CertLabsFileAll::class, 'app_certi_lab_id');
    }
   
    public function CertiLABFileAlls()
    {
        return $this->hasMany(CertLabsFileAll::class, 'app_certi_lab_id')->orderby('id','desc');
    }
    public function cert_labs_file_all(){
        return $this->hasMany(CertLabsFileAll::class,'app_certi_lab_id')->whereNotIn('status_cancel',[1]);
    }

    public function many_cost_assessment() {
        $cost_ids = CostAssessment::select('id')->where('app_certi_lab_id',$this->id)->whereNotIn('status_confirmed',[3])->OrwhereNull('status_confirmed');
        return $this->hasMany(CostAssessment::class, 'app_certi_lab_id')
                                    ->when($cost_ids, function ($query, $cost_ids){
                                        $query->whereIn('id',$cost_ids);
                                    });
    }

    public function paidPayIn1BoardAuditors()
    {
        $appCertiAssessmentIds = CostAssessment::where('app_certi_lab_id', $this->id)
            ->where('status_confirmed', 1)
            ->pluck('app_certi_assessment_id')
            ->toArray();

        if (!empty($appCertiAssessmentIds)) {
            $boardAuditorIds = Assessment::whereIn('id', $appCertiAssessmentIds)
                ->pluck('auditor_id')
                ->toArray();

            $boardAuditors = BoardAuditor::whereIn('id', $boardAuditorIds)->get();

            if ($boardAuditors->isNotEmpty()) {
                return $boardAuditors;
            }
        }

        // ถ้าไม่มีข้อมูล รีเทิร์นค่าเปล่า
        return null;
    }


 
    public function basic_province() { 
        return $this->belongsTo(Province::class, 'province');
    }
 
    public function lab_province_eng() { 
        return $this->belongsTo(Province::class, 'lab_province_eng');
    }
    
    public function basic_amphur() {
        return $this->belongsTo(Amphur::class, 'amphur');
    }
    public function basic_district() {
        return $this->belongsTo(District::class, 'district');
    }

    //สำนักงานใหญ่
    public function hq_province() {//จังหวัด สนญ.
        return $this->belongsTo(Province::class, 'hq_province_id');
    }

    public function hq_district() {//อำเภอ สนญ.
        return $this->belongsTo(Amphur::class, 'hq_district_id');
    }

    public function hq_subdistrict() {//ตำบล สนญ.
        return $this->belongsTo(District::class, 'hq_subdistrict_id');
    }

    public function certificate_export() {
        // dd($this->app_no);
        return $this->belongsTo(CertificateExport::class, 'app_no','request_number')->orderby('id','desc');
    }
    public function getCertificateExportIdAttribute() {
        return $this->certificate_export->id ??null;
    }

    public function certificate_export_to() {
        return $this->belongsTo(CertificateExport::class, 'id','certificate_for')->orderby('id','desc');
    }
    public function certificate_export_to2() {
        return $this->belongsTo(CertificateExport::class,'certificate_exports_id', 'id')->orderby('id','desc');
    }
    public function certificate_exports_to()
    {
        return $this->hasOne(CertificateExport::class, 'certificate_for');
    }

    public function getBranchTitleAttribute() {
        $data = HP::getArrayFormSecondLevel($this->certi_test_scope->toArray(), 'branch_id');
        // $datas = DB::table('bcertify_test_branches')->whereIn('id', $data)->pluck('title')->toArray();
        $datas = [];
        foreach ($data as $key => $list) {
            $branches = DB::table('bcertify_test_branches')->where('id',$list)->first() ;
            if(!is_null($branches) && !is_null($branches->title)){
                $datas[$key] = $branches->title ?? '' ;
            }
        }
        return implode(', ', $datas);
      }
    public function getClibrateBranchTitleAttribute() {
        $data = HP::getArrayFormSecondLevel($this->certi_lab_calibrate->toArray(), 'branch_id');
        // $datas = DB::table('bcertify_calibration_branches')->whereIn('id', $data)->pluck('title')->toArray();
        $datas = [];
        foreach ($data as $key => $list) {
            $branches = DB::table('bcertify_calibration_branches')->where('id',$list)->first() ;
            if(!is_null($branches) && !is_null($branches->title)){
                $datas[$key] = $branches->title ?? '' ;
            }
        }
        return implode(', ', $datas);
      }
      public function certi_lab_calibrate_groupBy(){
        return $this->hasMany(CertifyLabCalibrate::class,'app_certi_lab_id')->groupBy('branch_id');
    }
      public function certi_test_scope_groupBy(){
        return $this->hasMany(CertifyTestScope::class,'app_certi_lab_id')->groupBy('branch_id');
       }
      public function BelongsInformation()
      {
          return $this->belongsTo(Information::class,'id','app_certi_lab_id');
      }
      public function CertifyBoardAuditor()
      {
          return $this->belongsTo(BoardAuditor::class,'app_no','certi_no')->orderby('id','desc');
      }

        public function certify_board_auditor_to()
        {
            return $this->belongsTo(BoardAuditor::class,'id','app_certi_lab_id')->orderby('id','desc');
        }

      public function certi_ficate_historys()
      {
          return $this->hasMany(CertificateHistory::class,'app_no','app_no');
      }

      public function certiLab_delete_file()
      {
          return $this->hasMany(CertiLabDeleteFile::class, 'app_certi_lab_id');
      }

      public function report_to()
      {
          return $this->belongsTo(Report::class,'id','app_certi_lab_id');
      }


    public function arrStatus() {
         return [
            '0'=>'ฉบับร่าง',
            '1'=>'รอมอบหมายดำเนินการตรวจสอบ',
            '2'=>'รอดำเนินการตรวจสอบ',
            '3'=>'ขอเอกสารเพิ่มเติม',
            '4'=>'ยกเลิกคำขอ',
            '5'=>'ไม่ผ่านการตรวจสอบ',
            '6'=>'ผ่านการตรวจสอบ',
            '7'=>'รอดำเนินการตรวจสอบ',
            '8'=>'แจ้งชำระค่าธรรมเนียม',
            '9'=>'รับคำขอ',
            '10'=>'ประมาณค่าใช้จ่าย',
            '11'=>'ขอความเห็นประมาณค่าใช้จ่าย',
            '12'=>'แต่งตั้งคณะผู้ตรวจประเมิน',
            '13'=>'ขอความเห็นแต่งตั้งคณะผู้ตรวจประเมิน',
            '14'=>'แจ้งรายละเอียดค่าตรวจประเมิน',
            '15'=>'ชำระเงินค่าตรวจประเมิน',
            '16'=>'ตรวจสอบการชำระค่าตรวจประเมิน',
            '17'=>'ตรวจประเมิน',
            '18'=>'สรุปรายงานและเสนออนุกรรมการฯ',
            '19'=>'แจ้งรายละเอียดการชำระค่าใบรับรอง',
            '20'=>'ชำระเงินค่าใบรับรอง',
            '21'=>'ตรวจสอบการชำระค่าใบรับรอง',
            '22'=>'ออกใบรับรอง',
            '23'=>'ยืนยันความถูกต้อง',
            '24'=>'แก้ไขใบรับรอง',
            '25'=>'ออกใบรับรองและลงนาม',
            '26'=>'ลงนามเรียบร้อยแล้ว'
        ];
    }

    public function arrStatus2()
    {
        return [
               // '0'=> 'ฉบับร่าง',
                '1'=> 'รอดำเนินการตรวจ',
                '2'=> 'อยู่ระหว่างการตรวจสอบ',
                '3'=> 'ขอเอกสารเพิ่มเติม',
                '4'=> 'ยกเลิกคำขอ',
                '5'=> 'ไม่ผ่านการตรวจสอบ',
              //   '6'=> 'รอดำเนินการตรวจ',
              //   '7'=> 'รอดำเนินการตรวจ',
              //   '8'=> 'รอดำเนินการตรวจ',
                '9'=> 'รับคำขอ',
                '10'=> 'ประมาณการค่าใช้จ่าย',
                '11'=> 'ขอความเห็นประมาณการค่าใช้จ่าย',
                '12'=> 'อยู่ระหว่างแต่งตั้งคณะผู้ตรวจประเมิน',
                '13'=> 'ขอความเห็นแต่งคณะผู้ตรวจประเมิน',
                '14'=> 'เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน',
                '15'=> 'แจ้งรายละเอียดค่าตรวจประเมิน',
                '16'=> 'แจ้งหลักฐานการชำระเงิน',
                '17'=> 'ยืนยันการชำระเงินค่าตรวจประเมิน',
                '18'=> 'ผ่านการตรวจสอบประเมิน',
                '19'=> 'แก้ไขข้อบกพร่อง/ข้อสังเกต',
                '20'=> 'สรุปรายงานและเสนออนุกรรมการฯ',
                '21'=> 'รอยืนยันคำขอ',
                '22'=> 'ยืนยันจัดทำใบรับรอง',
                '23'=> 'แจ้งรายละเอียดการชำระค่าใบรับรอง',
                '24'=> 'แจ้งหลักฐานการชำระค่าใบรับรอง',
                '25'=> 'ยืนยันการชำระเงินค่าใบรับรอง',
                '26'=> 'ออกใบรับรอง และ ลงนาม',
                '27'=> 'ลงนามเรียบร้อย',
               ];

    }



    public function certi_lab_status_to()
    {
        return $this->belongsTo(CertiLabStatus::class,'status');
    }

    public function getStatus()
    {
      return  !empty($this->certi_lab_status_to->title)   ? $this->certi_lab_status_to->title : null;
    }

    public function get_branch(){
        $lab_type = $this->lab_type; // ประเภทการตรวจ
        $branch_ob = $this->branch_name;
        $branch_ob = $this->branch_name;
        if ($lab_type == '2'){
            $branch = InspectBranch::whereId($branch_ob)->first();
        }elseif ($lab_type == '1'){
            $branch = CertificationBranch::whereId($branch_ob)->first();
        }elseif ($lab_type == '3'){
            $branch = TestBranch::whereId($branch_ob)->first();
        }elseif ($lab_type == '4'){
            $branch = CalibrationBranch::whereId($branch_ob)->first();
        }else{
             $branch = ' ';
        }
        return $branch;
    }

    public function get_branches($lab_type = null){
        if ($lab_type == null) {
            $lab_type = $this->lab_type; // ประเภทการตรวจ
        }

        if ($lab_type == '2'){
            $branches = InspectBranch::get();
        }elseif ($lab_type == '1'){
            $branches = CertificationBranch::get();
        }elseif ($lab_type == '3'){
            $branches = TestBranch::get();
        }elseif ($lab_type == '4'){
            $branches = CalibrationBranch::get();
        }
        return $branches ?? collect();
    }

    public function assessment_type($ln = null)
    {
        $assessment_list = ['CB','IB','LAB ทดสอบ','LAB สอบเทียบ'];
        if ($ln == "th") {
            $assessment_list = ['CB','IB','ทดสอบ','สอบเทียบ'];
        }
        $assessment = $this->lab_type-1;
        return $assessment_list[$assessment] ?? ' ';
    }
    public function getStandardChangeTitleAttribute() {
        $datas = ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
          return array_key_exists($this->purpose_type,$datas) ? $datas[$this->purpose_type] : '';
      }

    public function getSelectAuditors() {
        $group = $this->assessment->groups()->where('status', 1)->first();
    }

    public function get_this_attach_config($config_id)
    {
        $file = CertiLabAttach::where('app_certi_lab_id',$this->id)->where('config_attach_id',$config_id)->first();
        return $file ?? null;
    }

    public function TableAssessmentGroup()
    {
        return $this->hasMany(AssessmentGroup::class ,'app_certi_lab_id' ,'id')->where('status', 1)->groupBy('checker_id');
    }


    public function getDataBoardAuditorDateTitleAttribute() {
        $Group = HP::getArrayFormSecondLevel($this->TableAssessmentGroup->toArray(), 'checker_id');
        $User = AuditorInformation::whereIn('id', $Group)->pluck('fname_th','id');
        return $User;
      }


      public function CheckExaminers() {
        return $this->hasMany(CheckExaminer::class, 'app_certi_lab_id','id');
       }
       
      public function getEmailStaffAttribute() {
        $datas = [];
        // ทดสอบ
         $data = HP::getArrayFormSecondLevel($this->CheckExaminers->toArray(), 'user_id');
         if(count($data) > 0){
            $datas = User::whereIn('runrecno', $data)->pluck('reg_email')->toArray();
            foreach ($datas as $key => $list) {
                    $datas[$key] = $list ;
            }
         }
          return $datas;
      }
    // Mail  ผู้ประกอบการ +  ผก.
    public function getEmailChiefAndOperatorAttribute() {
                  $datas = [];
            if(!is_null($this->subgroup)){     //e-mail ผก.
                $User = User::select('runrecno','reg_email')->where('reg_subdepart',$this->subgroup)->get();
                if(count($User) > 0){
                    foreach ($User as $key => $list) {
                        $role_user = RoleUser::where('user_runrecno',$list->runrecno)
                                            ->where('role_id',22)
                                            ->first();
                        if(!is_null($role_user)){
                            $datas[$list->reg_email] = $list->reg_email ;
                        }
                    }
                }
             }
            if(!is_null($this->email)){
                $datas[$this->email] = $this->email;
            }
        return $datas;
    }

         //e-mail   Mail แจ้งเตือน ผก. + ลท.
   public function getCertiEmailDirectorAndLtAttribute() {
    $datas = [];
        $User = User::select('runrecno','reg_email')->whereIn('reg_subdepart',[1802])->get();
        if(count($User) > 0){
            foreach ($User as $key => $item) {
                $role_user = RoleUser::where('user_runrecno',$item->runrecno)
                                    ->where('role_id',22)
                                    ->first();
                if(!is_null($role_user)){
                    $datas[$item->reg_email] = $item->reg_email ;
                }
            }
        }
        $email = CertiEmailLt::select('emails')->whereIn('certi',[1])->get();
        if(count($email) > 0){
            foreach ($email as $key => $item) {
                if(!is_null($item)){
                    $datas[$item->emails] = $item->emails ;
                }
            }
        }
      return $datas;
  }

  public function getLabTypeTitleAttribute(){
    $lab_type = $this->lab_type; // ประเภทการตรวจ
    $data =   ['3'=> 'ทดสอบ','4'=>'สอบเทียบ'];
    return   array_key_exists($lab_type,$data) ? $data[$lab_type] : '';
  }

// <!---  ------->
 // Mail กลาง ใบรับรอง LAB
 public function getDataEmailCertifyCenterAttribute() {
    $email =  CertiEmailLt::where('certi',$this->subgroup)->whereIn('roles',[3])->orderby('id','desc')->first();
    return  $email->emails ?? 'e-Accreditation@tisi.mail.go.ths';
    // return  'e-Accreditation@tisi.mail.go.th';
}

  // mail  ผก. +  เจ้าหน้าที่มอบหมาย
  public function getDataEmailDirectorLABCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->where('certi',$this->subgroup)->whereIn('roles',[1,3])->get();
    if(count($email) > 0){       // ผก.
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
    // if(count($this->CheckExaminers) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
    //     $examiner = HP::getArrayFormSecondLevel($this->CheckExaminers->toArray(), 'user_id');
    //     $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
    //      foreach ($Users as $key => $item) {
    //         if(!is_null($item)){
    //             $datas[] = $item;
    //         }
    //      }
    //  }
      return $datas;
  }

  // mail  ผก. +  ลท.
  public function getDataEmailDirectorAndLtLABCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->where('certi',$this->subgroup)->whereIn('roles',[1,2])->get();
    if(count($email) > 0){       // ผก.
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
      return $datas;
  }

    // mail replyTo
  public function getDataEmailDirectorLABReplyAttribute() {
    $datas = [];
    $email =  CertiEmailLt::where('certi',$this->subgroup)->where('reply_to',1)->orderby('id','desc')->get();
    if(count($email) > 0){
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
      return $datas;
  }
// <!---  ------->



public function certi_auditors()
{
    return $this->hasMany(BoardAuditor::class, 'app_certi_lab_id');
}
public function certi_auditors_null_many()
{
    return $this->hasMany(BoardAuditor::class, 'app_certi_lab_id')->whereNull('status')->orderby('id','desc');
}

 public function certi_auditors_many()
 {
     return $this->hasMany(BoardAuditor::class,'app_certi_lab_id') ->whereNull('status_cancel')   ->orderby('id','desc');
 }


public function getCertiAuditorsStatusAttribute() {
    $data = HP::getArrayFormSecondLevel($this->certi_auditors_many->toArray(), 'id');
    $list = '';
    $datas = BoardAuditor::whereIn('id', $data)->pluck('status')->toArray();
   // สถานะส่งไปให้ ผปก.
    $statusNull = array_filter($datas, function($v, $k) {
                        return $v == null;
                    }, ARRAY_FILTER_USE_BOTH);
   // สถานะ  ผปก. เห็นด้วย
    $status1 = array_filter($datas, function($v, $k) {
                        return $v == 1;
                    }, ARRAY_FILTER_USE_BOTH);
  // สถานะ  ผปก. ไม่เห็นด้วย
    $status2 = array_filter($datas, function($v, $k) {
                        return $v == 2;
                    }, ARRAY_FILTER_USE_BOTH);

     if(count($status2) > 0){
                $list = "StatusNotView";
     }else{
        if(count($statusNull) > 0 || count($datas) == 0){
                $list = "StatusSent";
        }else{
            if(count($datas) == count($status1)){
                $list = "StatusView";
            }else{
                $list = "StatusNotView";
            }
         }
    }
    return $list;
  }


  public function many_cost_assessment_state3() {
    return $this->hasMany(CostAssessment::class, 'app_certi_lab_id')->where('state',3);
}

  public function getCertiLabPayInOneStatusAttribute() {
    $data = HP::getArrayFormSecondLevel($this->many_cost_assessment->toArray(), 'id');
    $list = '';
    $datas = CostAssessment::whereIn('id', $data)->pluck('status_confirmed')->toArray();
    $states = CostAssessment::whereIn('id', $data)->pluck('state')->toArray();

   // สถานะส่งไปให้ ผปก.
    $state1 = array_filter($states, function($v, $k) {
                        return $v == 1  && $v != 3;
                    }, ARRAY_FILTER_USE_BOTH);
    $state2 = array_filter($states, function($v, $k) {
                        return $v == 2  && $v != 3;
                    }, ARRAY_FILTER_USE_BOTH);
     $stateNull = array_filter($states, function($v, $k) {
                        return $v == null  && $v != 3;
                    }, ARRAY_FILTER_USE_BOTH);
   // สถานะ ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
    $status1 = array_filter($datas, function($v, $k) {
                        return $v == 1 || $v == 3;
                    }, ARRAY_FILTER_USE_BOTH);
    $state_null = array_filter($states, function($v, $k) {
                        return $v == null;
                    }, ARRAY_FILTER_USE_BOTH);
     if(count($datas) == count($status1)){  // เช็คได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
          $list = "StatusPayInOneNeat";
     }elseif(count($state_null) > 0){
         $list = "null";
    } elseif(count($state2) > 0){
         $list = "StatusPayInOneNotNeat";
     }elseif(count($state1) > 0){
         $list = "StatePayInOne";
     }
    return $list;
  }


  public function getCertiLabSaveAssessmentStatusAttribute() {
    $list = '';
    $pay = HP::getArrayFormSecondLevel($this->many_cost_assessment->toArray(), 'id');
    $data = HP::getArrayFormSecondLevel($this->notices->toArray(), 'id');
    // dd($this->notices->toArray());
    $pays = CostAssessment::whereIn('id', $pay)->where('status_confirmed',1)->pluck('status_confirmed')->toArray();

    $data_degree = Notice::whereIn('id', $data)->pluck('degree')->toArray();  // ทั้งหมด
    $receive = Notice::whereIn('id', $data)->whereIn('degree',[2,5])->pluck('degree')->toArray();//  จำนวน ผปก. ส่งไปให้
    $sent = Notice::whereIn('id', $data)->whereIn('degree',[1,3,4,6])->pluck('degree')->toArray();// จำนวน จนท. ส่งไปให้

     // สถานะส่งไปให้ ผปก.
     $degree7 = array_filter($data_degree, function($v, $k) {
        return $v == 7  || $v == 8 ;
    }, ARRAY_FILTER_USE_BOTH);

   
    // ฉบับร่าง
    $degree0 = array_filter($data_degree, function($v, $k) {
        return $v == 0;
    }, ARRAY_FILTER_USE_BOTH);

    if(count($pays) != count($data_degree) ){
        $list = "statusWarning";
      }elseif(count($degree0) > 0){ // ฉบับร่าง
        $list = "statusPrimary";
      }elseif(count($degree7) == count($data_degree)){  // ผ่านทั้งหมด
        $list = "statusInfo";
     }elseif(count($receive) > 0){ //  จำนวน ผปก. ส่งไปให้
        $list = "statusDanger";
    }elseif(count($sent) > 0){ // จำนวน จนท. ส่งไปให้
        $list = "statusSuccess";
    }

    // dd(count($pays),count($data_degree),$sent,count($degree7) ,count($data_degree),$list);
    return $list;
  }


  public function getFormatAddressAttribute() {
    $address   = [];
    $address[] = @$this->address_no;

        if($this->allay!='' && $this->allay !='-'  && $this->allay !='--'){
          $address[] =  "หมู่ที่ " . $this->allay;
        }
        if($this->village_no!='' && $this->village_no !='-'  && $this->village_no !='--'){
            $address[] = "ซอย "  . $this->village_no;
        }
        if($this->road !='' && $this->road !='-'  && $this->road !='--'){
            $address[] =  "ถนน "  . $this->road;
        }
        if($this->province!=''){
            $address[] =  "จังหวัด " . $this->province;
        }
        if($this->amphur!=''){
            $address[] =  "เขต/อำเภอ " . $this->amphur;
        }
        if($this->district!=''){
            $address[] =  "แขวง/ตำบล " . $this->district;
        }
        if($this->postcode!=''){
            $address[] =  "รหัสไปรษณีย " . $this->postcode;
        }
    return implode(' ', $address);
    }

    public function getAppCertLabFileAllFirstAttribute() {
        return @$this->app_cert_lab_file_all->where('state', 1)->orderByDesc('id')->first();
    }


    // เช็คขอบข่ายใน mapreq
    public function certi_lab_export_mapreq_to()
    {
        return $this->belongsTo(CertiLabExportMapreq::class,'id', 'app_certi_lab_id')  ;
    }
     // ขอบข่าย
    public function getCertLabsFileScopeAttribute()
    {
        $attach_pdf = null;
        if(!empty($this->certi_lab_export_mapreq_to)){
            $certificate_no =  !empty($this->certi_lab_export_mapreq_to->certificate_export->certificate_no) ? $this->certi_lab_export_mapreq_to->certificate_export->certificate_no : null;
            if(!is_null($certificate_no)){
                $export_no         =  CertificateExport::where('certificate_no',$certificate_no);
                if(count($export_no->get()) > 0){
                    
                    $lab_ids = [];
                    if($export_no->pluck('certificate_for')->count() > 0){
                        foreach ($export_no->pluck('certificate_for') as $item) {
                            if(!in_array($item,$lab_ids)){
                               $lab_ids[] =  $item;
                            }
                        }
                    }

                    if($this->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0){
                        foreach ($this->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) {
                            if(!in_array($item,$lab_ids)){
                                $lab_ids[] =  $item;
                            }
                        }
                    }

                    // ขอบข่าย
                    $attach_pdf =  CertLabsFileAll::select('attach_pdf','attach_pdf_client_name')->whereIn('app_certi_lab_id',$lab_ids)->where('state', 1)->orderby('id','desc')->first();   
              } 
         }
      }
       return $attach_pdf;
        
    }
  
    // วันที่ยื่นคำขอรับใบรับรอง
    public function getStartDateShowAttribute()
    {
        return Carbon::hasFormat($this->start_date, 'Y-m-d')?Carbon::parse($this->start_date)->addYear(543)->isoFormat('D MMM YYYY'):null;
    }

    public function labCalRequests()
    {
        return $this->hasMany(LabCalRequest::class, 'app_certi_lab_id', 'id');
    }

    public function labTestRequests()
    {
        return $this->hasMany(LabTestRequest::class, 'app_certi_lab_id', 'id');
    }

    public function labRequestRejectTrackings()
    {
        return $this->hasMany(LabRequestRejectTracking::class, 'app_certi_lab_id', 'id');
    }

    public function pendingSignAssessmentReportTransaction()
    {
        $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('app_id',$this->app_no)
        ->where('certificate_type',2)
        ->where('report_type',1)
        ->where('approval',0)
        ->get();     
        return $signAssessmentReportTransactions;
    }

    public function allLabTestTransactionCategories()
    {
        $categories = [];

        foreach ($this->labTestRequests as $labTestRequest) {
            foreach ($labTestRequest->labTestTransactions as $labTestTransaction) {
                $categories[] = $labTestTransaction->category_th;
            }
        }
        return implode(', ', array_unique($categories)); // ใช้ array_unique เพื่อลบค่าซ้ำ
    }

    public function allLabCalTransactionCategories()
    {
        $categories = [];

        foreach ($this->labCalRequests as $labCalRequest) {
            foreach ($labCalRequest->labCalTransactions as $labCalTransaction) {
                $categories[] = $labCalTransaction->category_th;
            }
        }
        return implode(', ', array_unique($categories)); // ใช้ array_unique เพื่อลบค่าซ้ำ
    }

}
