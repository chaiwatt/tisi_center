<?php

namespace App\Models\Certify\ApplicantIB;

use DB;
use HP;

use App\User;
use App\RoleUser;

use Carbon\Carbon;
use App\Models\Basic\Amphur;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate\IbDocReviewAuditor;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\CertiEmailLt;  //E-mail ลท.
use App\Models\Certify\ApplicantIB\CertiIbExportMapreq;

class CertiIb extends Model
{
    use Sortable;

    protected $table = "app_certi_ib";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'org_name',
							'app_no',
                            'name',
                            'status',
                            'standard_change',
                            'type_unit',
                            'name_unit',
                            'branch',
                            'branch_type',
                            'type_standard',
                            'app_certi_ib_export_id',
                            'accereditation_no',
                             'checkbox_address',
                             'address',
                             'allay',
                             'village_no',
                             'road',
                             'province_id',
                             'amphur_id',
                             'district_id',
                             'postcode',
                             'tel',
                             'tel_fax',
                             'contactor_name',
                             'email',
                             'contact_tel',
                             'telephone',
                             'petitioner',
                             'details',
                             'desc_delete',
                             'deleted_by',
                             'deleted_at',
                             'review',
                             'token',
                             'save_date',
                             'checkbox_confirm',
                             'created_by', //tb10_nsw_lite_trader
                             'updated_by',
                             'created_at',
                             'updated_at',
                             'get_date',
                             'tax_id',
                             'start_date',
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
                             'ib_latitude', 
                             'ib_longitude', 
                             'name_en_unit',
                             'name_short_unit',
                             'ib_address_no_eng',
                             'ib_moo_eng',
                             'ib_soi_eng',
                             'ib_street_eng',
                             'ib_province_eng',
                             'ib_amphur_eng',
                             'ib_district_eng',
                             'ib_postcode_eng',
                             'doc_auditor_assignment',
                             'doc_review_update',
                             'doc_review_reject',
                             'doc_review_reject_message',
                             'require_scope_update',
                             'scope_view_signer_id',
                             'scope_view_status',
                            ];



public function basic_province() {
    return $this->belongsTo(Province::class, 'province_id');
 }
public function basic_amphur() {
    return $this->belongsTo(Amphur::class, 'amphur_id');
 }
public function basic_district() {
    return $this->belongsTo(District::class, 'district_id');
}
 public function TitleStatus()
 {
    return $this->belongsTo(CertiIBStatus::class,'status','id');
 }

 public function EsurvTrader()
 {
    return $this->belongsTo(SSO_User::class, 'created_by');
 }

 // ประมาณการค่าใช้จ่าย
 public function CertiIBCostTo()
 {
     return $this->belongsTo(CertiIBCost::class,'id','app_certi_ib_id')->orderby('id','desc');
 }
  // แต่งตั้งคณะผู้ตรวจประเมิน
 public function CertiIBAuditorsMany()
 {
     return $this->hasMany(CertiIBAuditors::class,'app_certi_ib_id');
 }
 public function CertiIBAuditorsManyBy()
 {
     return $this->hasMany(CertiIBAuditors::class,'app_certi_ib_id')->whereNull('status_cancel')->orderby('id','desc');
 }

   // แจ้งรายละเอียดค่าตรวจประเมิน

 public function CertiIBPayInOneMany()
 {
     return $this->hasMany(CertiIBPayInOne::class,'app_certi_ib_id')->orderby('id','desc');
 }
 public function CertiIBPayInOneStatusMany()
 {
     return $this->hasMany(CertiIBPayInOne::class,'app_certi_ib_id')->where('status',1)->orderby('id','desc');
 }
 public function CertiIBReportTo()
 {
     return $this->belongsTo(CertiIBReport::class,'id','app_certi_ib_id')->orderby('id','desc');
 }

 public function CertiIBSaveAssessmentTo()
 {
     return $this->belongsTo(CertiIBSaveAssessment::class,'id','app_certi_ib_id')->orderby('id','desc');
 }
 public function CertiIBSaveAssessmentMany()
 {
     return $this->hasMany(CertiIBSaveAssessment::class,'app_certi_ib_id')->orderby('id','desc');
 }
 public function CertiIBReviewTo()
 {
     return $this->belongsTo(CertiIBReview::class,'id','app_certi_ib_id')->orderby('id','desc');
 }
    // แนบใบ Pay-in ครั้งที่ 2
 public function CertiIBPayInTwoTo()
 {
     return $this->belongsTo(CertiIBPayInTwo::class,'id','app_certi_ib_id')->orderby('id','desc');
 }

 public function CertiIBExportTo()
 {
     return $this->belongsTo(CertiIBExport::class,'id','app_certi_ib_id')->orderby('id','desc');
 }


 public function certi_ib_checks()
 {
     return $this->hasMany(CertiIBCheck::class, 'app_certi_ib_id');
 }

 public function CertiIBFileTo()
 {
     return $this->belongsTo(CertiIBFileAll::class,'id','app_certi_ib_id');
 }

 public function certi_iBFile_state1_to()
 {
     return $this->belongsTo(CertiIBFileAll::class,'id','app_certi_ib_id')->where('state',1);
 }

 public function CertiIBFileAlls()
 {
     return $this->hasMany(CertiIBFileAll::class, 'app_certi_ib_id')->orderby('id','desc');
 }

 public function cert_ibs_file_all()
 {
     return $this->hasMany(CertiIBFileAll::class, 'app_certi_ib_id')->orderby('id','desc')->whereNotIn('status_cancel',[1]);
 }

 public function cert_ibs_file_all_order_desc()
 {
     return $this->hasMany(CertiIBFileAll::class, 'app_certi_ib_id')->orderby('created_at','desc')->whereNotIn('status_cancel',[1]);
 }

 public function CertiIbHistorys()
 {
     return $this->hasMany(CertiIbHistory::class, 'app_certi_ib_id');
 }

 public function FileAttach1()
 {
     $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',1)->whereNull('ref_id');
 }

 public function FileAttach2()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',2)->whereNull('ref_id');
 }
 public function FileAttach3()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',3)->whereNull('ref_id');
 }
 public function FileAttach4()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',4)->whereNull('ref_id');
 }
 public function FileAttach5()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',5)->whereNull('ref_id');
 }
 public function FileAttach6()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',6)->whereNull('ref_id');
 }
 public function FileAttach7()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',7)->whereNull('ref_id');
 }
 public function FileAttach8()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',8)->whereNull('ref_id');
 }

 // จนท
 public function FileAttach9()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',9)->whereNull('ref_id');
 }
 // จนท
 public function FileAttach10()
 {
    $tb = new CertiIb;
     return $this->hasMany(CertiIBAttachAll::class, 'app_certi_ib_id')->where('table_name',$tb->getTable())->where('file_section',10)->whereNull('ref_id');
 }
 // Mail  ผู้ประกอบการ +  ผก.
 public function getEmailChiefAndOperatorAttribute() {
    $datas = [];
        $User = User::select('runrecno','reg_email')->whereIn('reg_subdepart',[1802])->get();
        if(count($User) > 0){
            foreach ($User as $key => $item) {
                $role_user = RoleUser::where('user_runrecno',$item->runrecno)
                                    ->where('role_id',30)
                                    ->first();
                if(!is_null($role_user)){
                   $datas[] = $item->reg_email ;
                }
            }
        }
        if(!is_null($this->email)){
             $datas[$this->emails] = $this->email;
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
                                    ->where('role_id',30)
                                    ->first();
                if(!is_null($role_user)){
                    $datas[$item->reg_email] = $item->reg_email ;
                }
            }
        }
        $email = CertiEmailLt::select('emails')->whereIn('certi',[3])->get();
        if(count($email) > 0){
            foreach ($email as $key => $item) {
                if(!is_null($item)){
                    $datas[$item->emails] = $item->emails ;
                }
            }
        }
      return $datas;
  }
  // Mail เจ้าหน้าที่มอบหมาย
 public function getEmailStaffAssignAttribute() {
    $datas = [];
        if(count($this->certi_ib_checks) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
            $examiner = HP::getArrayFormSecondLevel($this->certi_ib_checks->toArray(), 'user_id');
            $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
             foreach ($Users as $key => $item) {
                if(!is_null($item)){
                    $datas[$item] = $item;
                }
             }
         }
      return $datas;
  }


 // Mail กลาง ใบรับรอง IB
 public function getDataEmailCertifyCenterAttribute() {
    $email =  CertiEmailLt::whereIn('certi',[1802])->whereIn('roles',[3])->orderby('id','desc')->first();
    return (array)$email->emails ?? '';
    // return  'e-Accreditation@tisi.mail.go.th';
}

  // mail  ผก. +  เจ้าหน้าที่มอบหมาย
  public function getDataEmailDirectorIBCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->whereIn('certi',[1802])->whereIn('roles',[1,3])->get();
    if(count($email) > 0){       // ผก.
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
      return $datas;
  }

  // mail  ผก. +  ลท.
  public function getDataEmailDirectorAndLtIBCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->whereIn('certi',[1802])->whereIn('roles',[1,2])->get();
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
  public function getDataEmailDirectorIBReplyAttribute() {
    $datas = [];
    $email =  CertiEmailLt::whereIn('certi',[1802])->where('reply_to',1)->orderby('id','desc')->get();
    if(count($email) > 0){
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
      return $datas;
  }


  public function getStandardChangeTitleAttribute() {
    $datas = ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
      return array_key_exists($this->standard_change,$datas) ? $datas[$this->standard_change] : '';
  }

 // ชื่อเจ้าหน้าที่มอบหมาย
 public function getFullNameAttribute() {
    $data = HP::getArrayFormSecondLevel($this->certi_ib_checks->toArray(), 'user_id');
    $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
    foreach ($datas as $key => $list) {
           $datas[$key] = $list ;
    }
    return  (count($datas) > 0) ?  implode(',<br/>', $datas) : '-';
  }
  public function getFullRegNameAttribute() {  // show.blade.php
    $data = HP::getArrayFormSecondLevel($this->certi_ib_checks->toArray(), 'user_id');
    $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
    foreach ($datas as $key => $list) {
           $datas[$key] = $list ;
    }
    return  (count($datas) > 0) ?  implode(', ', $datas) : '-';
  }
  public function getCertiIBAuditorsStatusAttribute() {
    $data = HP::getArrayFormSecondLevel($this->CertiIBAuditorsManyBy->toArray(), 'id');
    $list = '';
    $datas = CertiIBAuditors::whereIn('id', $data)->pluck('status')->toArray();
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

  public function getCertiIBPayInOneStatusAttribute() {
    $data = HP::getArrayFormSecondLevel($this->CertiIBPayInOneMany->toArray(), 'id');
    $list = '';
    $datas = CertiIBPayInOne::whereIn('id', $data)->pluck('status')->toArray();
    $states = CertiIBPayInOne::whereIn('id', $data)->pluck('state')->toArray();
    // $states1 = CertiIBPayInOne::whereIn('id', $data)->where('state',1)->pluck('state')->toArray();  // จำนวนส่งไปให้ ผปก.
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

  public function getCertiIBSaveAssessmentStatusAttribute() {
    $list = '';
    $pay = HP::getArrayFormSecondLevel($this->CertiIBPayInOneMany->toArray(), 'id');
    $data = HP::getArrayFormSecondLevel($this->CertiIBSaveAssessmentMany->toArray(), 'id');

    $pays = CertiIBPayInOne::whereIn('id', $pay)->where('status',1)->pluck('status')->toArray();

    $data_degree = CertiIBSaveAssessment::whereIn('id', $data)->pluck('degree')->toArray();  // ทั้งหมด
    $receive = CertiIBSaveAssessment::whereIn('id', $data)->whereIn('degree',[2,5])->pluck('degree')->toArray();//  จำนวน ผปก. ส่งไปให้
    $sent = CertiIBSaveAssessment::whereIn('id', $data)->whereIn('degree',[1,3,4,6])->pluck('degree')->toArray();// จำนวน จนท. ส่งไปให้
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
    return $list;
  }

    public function getFormatAddressAttribute() {
        $address   = [];
        $address[] = @$this->address;
            if($this->ProvinceCode == 10){
                $pre_subdis = 'แขวง';
                $pre_dis = 'เขต';
                $pre_pro = '';
            }else{
                $pre_subdis = 'ตำบล';
                $pre_dis = 'อำเภอ';
                $pre_pro = 'จังหวัด';
            }
            if($this->allay!='' && $this->allay !='-'  && $this->allay !='--'){
            $address[] =  "หมู่ที่ " . $this->allay;
            }
            if($this->village_no!='' && $this->village_no !='-'  && $this->village_no !='--'){
                $address[] = "ซอย "  . $this->village_no;
            }
            if($this->road !='' && $this->road !='-'  && $this->road !='--'){
                $address[] =  "ถนน "  . $this->road;
            }
            if($this->district_id!=''){
                $address[] =  $pre_subdis . $this->district_id;
            }
            if($this->amphur_id!=''){
                $address[] =  $pre_dis . $this->amphur_id;
            }
            if($this->province_id!=''){
                $address[] =  $pre_pro . $this->ProvinceName;
            }
            if($this->postcode!=''){
                $address[] =  "รหัสไปรษณีย์ " . $this->postcode;
            }
        return implode(' ', $address);
   }

    // TEXT  หน่วยตรวจประเภท
   public function getTypeUnitTitleAttribute() {
    $datas = ['1'=>'A','2'=>'B','3'=>'C'];
      return array_key_exists($this->type_unit,$datas) ? $datas[$this->type_unit] : '';
  }
  public function hq_province()
  {
      return $this->belongsTo(Province::class,'hq_province_id','PROVINCE_ID');
  }
  public function hq_district()
  {
      return $this->belongsTo(Amphur::class,'hq_district_id','AMPHUR_ID');
  }
  public function hq_subdistrict()
  {
      return $this->belongsTo(District::class,'hq_subdistrict_id','DISTRICT_ID');
  }

  public function getHqSubdistrictNameAttribute() {
      return !empty($this->hq_subdistrict)?$this->hq_subdistrict->DISTRICT_NAME:null;
  }

  public function getHqDistrictNameAttribute() {
      return !empty($this->hq_district)?$this->hq_district->AMPHUR_NAME:null;
  }

  public function getHqProvinceNameAttribute() {
      return !empty($this->hq_province)?$this->hq_province->PROVINCE_NAME:null;
  }

  public function getProvinceNameAttribute() {
      return !empty($this->basic_province->PROVINCE_NAME)?$this->basic_province->PROVINCE_NAME:null;
  }

  public function getProvinceCodeAttribute() {
      return !empty($this->basic_province->PROVINCE_CODE)?$this->basic_province->PROVINCE_CODE:null;
  }
  
    // คารางใบรับรอง
    public function app_certi_ib_export()
    {
        return $this->hasOne(CertiIBExport::class, 'app_certi_ib_id');
    }
    public function app_certi_ib_export_to2() {
        return $this->belongsTo(CertiIBExport::class,'app_certi_ib_id', 'id')->orderby('id','desc');
    }
    // เช็คขอบข่ายใน mapreq
    public function certi_ib_export_mapreq_to()
    {
        return $this->hasOne(CertiIbExportMapreq::class, 'app_certi_ib_id');
    }
  
    // วันที่ยื่นคำขอรับใบรับรอง
    public function getStartDateShowAttribute()
    {
        return Carbon::hasFormat($this->start_date, 'Y-m-d')?Carbon::parse($this->start_date)->addYear(543)->isoFormat('D MMM YYYY'):null;
    }

    public function ibDocReviewAuditor()
    {
        return $this->hasOne(IbDocReviewAuditor::class, 'app_certi_ib_id');
    }

    public function fullyApprovedAuditorNoCancels()
    {
        return $this->CertiAuditors()
         ->whereNull('status_cancel')
         ->whereDoesntHave('messageRecordTransactions', function ($query) {
             $query->where('approval', 0);
         });
    }
   
    public function fullyApprovedAuditors()
    {
        return $this->CertiAuditors()->whereDoesntHave('messageRecordTransactions', function ($query) {
            $query->where('approval', 0);
        });
    }

    // แต่งตั้งคณะผู้ตรวจประเมิน
    public function CertiAuditors()
    {
        return $this->hasMany(CertiIBAuditors::class, 'app_certi_ib_id');
    }
     
    public function paidPayIn1BoardAuditors()
    {
        $appCertiAssessmentIds = CertiIBPayInOne::where('app_certi_ib_id', $this->id)
            ->where('status', 1)
            ->pluck('auditors_id')
            ->toArray();

        if (!empty($appCertiAssessmentIds)) {
            
            $boardAuditors = CertiIBAuditors::whereIn('id', $appCertiAssessmentIds)->get();
            // dd($boardAuditors);
            return $boardAuditors;
        }
        // ถ้าไม่มีข้อมูล รีเทิร์นค่าเปล่า
        return null;
    }

   
}
