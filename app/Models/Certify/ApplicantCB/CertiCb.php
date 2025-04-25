<?php

namespace App\Models\Certify\ApplicantCB;

use DB;

use HP;

use App\User;
use App\RoleUser;
use Carbon\Carbon;
use App\Models\Basic\Amphur;

use App\Certify\CbReportInfo;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\Models\Bcertify\Formula;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantCB\CertiCBStatus;
use App\Models\Certify\CertiEmailLt;  //E-mail ลท.
use App\Models\Certify\ApplicantCB\CertiCbExportMapreq;
use App\Models\Certify\SignAssessmentReportTransaction;



class CertiCb extends Model
{
    use Sortable;

    protected $table = "app_certi_cb";
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_no',
                            'name',
                            'cb_name',
                            'start_date',
                            'status',
                            'standard_change',
                            'app_certi_cb_export_id',
                            'accereditation_no',
                            'type_standard',
                            'name_standard',
                            'branch_type',
                            'branch',
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
                             'check_badge',
                             'created_by', //tb10_nsw_lite_trader
                             'updated_by',
                             'get_date',
                             'tax_id',
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
                             'cb_latitude',
                             'cb_longitude',
                             'cb_address_no_eng',
                             'cb_moo_eng',
                             'cb_soi_eng',
                             'cb_street_eng',
                             'cb_province_eng',
                             'cb_amphur_eng',
                             'cb_district_eng',
                             'cb_postcode_eng',
                             'name_en_standard',
                             'name_short_standard',
                            'more_doc_require',
                            'petitioner_id',
                            'trust_mark_id',
                            'doc_auditor_assignment',
                            'doc_review_update',
                            'doc_review_reject',
                            'doc_review_reject_message',
                            'require_scope_update',
                            'scope_view_signer_id',
                            'scope_view_status',
                            ];


    public function EsurvTrader()
    {
        return $this->belongsTo(SSO_User::class, 'created_by');
    }

    public function getEsurvTraderTitleAttribute() {
        return @$this->EsurvTrader->name ?? '-';
    }

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
     return $this->belongsTo(CertiCBStatus::class,'status','id');
 }
   //มาตรฐาน
 public function FormulaTo() {
    return $this->belongsTo(Formula::class, 'type_standard');
 }
   //สาขา มาตรฐาน
   public function CertiCBFormulasTo()
   {
       return $this->belongsTo(CertiCBFormulas::class,'petitioner');
   }

   //สาขา มาตรฐาน
   public function  CertificationBranchTo()
   {
       return $this->belongsTo(CertificationBranch::class,'petitioner');
   }


 public function FileAttach1()
 {
     $tb = new CertiCb;
     return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',1)->whereNull('ref_id');
 }

 public function FileAttach2()
 {
    $tb = new CertiCb;
     return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',2)->whereNull('ref_id');
 }
 public function FileAttach3()
 {
    $tb = new CertiCb;
     return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',3)->whereNull('ref_id');
 }
 public function FileAttach4()
 {
    $tb = new CertiCb;
     return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',4)->whereNull('ref_id');
 }

 public function FileAttach5()
 {
    $tb = new CertiCb;
     return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',5)->whereNull('ref_id');
 }
  //ขอเอกสารเพิ่มเติม
  public function FileAttach6()
  {
     $tb = new CertiCb;
      return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',6)->whereNull('ref_id');
  }
  // ยกเลิกคำขอ
  public function FileAttach7()
  {
     $tb = new CertiCb;
      return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',7)->whereNull('ref_id');
  }
   // ไม่ผ่านการตรวจสอบ
  public function FileAttach8()
  {
     $tb = new CertiCb;
      return $this->hasMany(CertiCBAttachAll::class, 'app_certi_cb_id')->where('table_name',$tb->getTable())->where('file_section',8)->whereNull('ref_id');
  }
 public function certi_cb_checks()
 {
     return $this->hasMany(CertiCBCheck::class, 'app_certi_cb_id');
 }
   // แต่งตั้งคณะผู้ตรวจประเมิน
   public function CertiCBAuditorsTo()
   {
       return $this->belongsTo(CertiCBAuditors::class,'id','app_certi_cb_id')->orderby('id','desc');
   }
    // แต่งตั้งคณะผู้ตรวจประเมิน step
    public function CertiCBAuditorsStepMany()
    {
        return $this->hasMany(CertiCBAuditors::class,'app_certi_cb_id')->where('step_id',3)->orderby('id','desc');
    }
    public function CertiCBAuditorsMany()
    {
        return $this->hasMany(CertiCBAuditors::class,'app_certi_cb_id');
    }
    public function CertiCBAuditorsManyBy()
    {
        return $this->hasMany(CertiCBAuditors::class,'app_certi_cb_id')
        ->whereNull('status_cancel')
        ->whereNull('is_review_state')
        ->orderby('id','desc');
    }

    public function CertiCBAuditorsManyByReview()
    {
        return $this->hasMany(CertiCBAuditors::class,'app_certi_cb_id')
        ->whereNull('status_cancel')
        ->whereNotNull('is_review_state')
        ->orderby('id','desc');
    }
   // Pay-IN ครั้งที่ 1
   public function CertiCBPayInOneMany()
   {
       return $this->hasMany(CertiCBPayInOne::class,'app_certi_cb_id')->orderby('id','desc');
   }
   public function CertiCBPayInOneStatusMany()
   {
       return $this->hasMany(CertiCBPayInOne::class,'app_certi_cb_id')->where('status',1)->orderby('id','desc');
   }
 // ประมาณการค่าใช้จ่าย
 public function CertiCBCostTo()
 {
     return $this->belongsTo(CertiCBCost::class,'id','app_certi_cb_id')->orderby('id','desc');
 }

 public function CertiCBSaveAssessmentTo()
 {
     return $this->belongsTo(CertiCBSaveAssessment::class,'id','app_certi_cb_id')->orderby('id','desc');
 }
 public function CertiCBSaveAssessmentMany()
 {
     return $this->hasMany(CertiCBSaveAssessment::class,'app_certi_cb_id')->orderby('id','desc');

    //  return CertiCBAuditors::where('app_certi_cb_id',$this->id)
    //  ->whereNotNull('is_review_state')
    //  ->where('status',1);

 }
 public function CertiCBReviewTo()
 {
     return $this->belongsTo(CertiCBReview::class,'id','app_certi_cb_id')->orderby('id','desc');
 }
// ตารางใบรับรอง
public function app_certi_cb_export()
{
    return $this->hasOne(CertiCBExport::class, 'app_certi_cb_id');
}
   // Mail  ผู้ประกอบการ +  ผก.
 public function getEmailChiefAndOperatorAttribute() {
    $datas = [];
        $User = User::select('runrecno','reg_email')->whereIn('reg_subdepart',[1803])->get();
        if(count($User) > 0){
            foreach ($User as $key => $item) {
                $role_user = RoleUser::where('user_runrecno',$item->runrecno)
                                    ->where('role_id',31)
                                    ->first();
                if(!is_null($role_user)){
                   $datas[] = $item->reg_email ;
                }
            }
        }
        if(!is_null($this->email)){
             $datas[] = $this->email;
        }
      return $datas;
  }

   //e-mail   Mail แจ้งเตือน ผก. + ลท.
   public function getCertiEmailDirectorAndLtAttribute() {
    $datas = [];
        $User = User::select('runrecno','reg_email')->whereIn('reg_subdepart',[1803])->get();
        if(count($User) > 0){
            foreach ($User as $key => $item) {
                $role_user = RoleUser::where('user_runrecno',$item->runrecno)
                                    ->where('role_id',31)
                                    ->first();
                if(!is_null($role_user)){
                    $datas[] = $item->reg_email ;
                }
            }
        }
        $email = CertiEmailLt::select('emails')->whereIn('certi',[2])->get();
        if(count($email) > 0){
            foreach ($email as $key => $item) {
                if(!is_null($item)){
                    $datas[] = $item->emails ;
                }
            }
        }
      return $datas;
  }


  // Mail เจ้าหน้าที่มอบหมาย
  public function getEmailStaffAssignAttribute() {
    $datas = [];
        if(count($this->certi_cb_checks) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
            $examiner = HP::getArrayFormSecondLevel($this->certi_cb_checks->toArray(), 'user_id');
            $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
             foreach ($Users as $key => $item) {
                if(!is_null($item)){
                    $datas[] = $item;
                }
             }
         }
      return $datas;
  }



 // Mail กลาง ใบรับรอง CB
 public function getDataEmailCertifyCenterAttribute() {
    $email =  CertiEmailLt::whereIn('certi',[1803])->whereIn('roles',[3])->orderby('id','desc')->first();
    return (array)$email->emails ?? '';
    // return  'e-Accreditation@tisi.mail.go.th';
}

  // mail  ผก. +  เจ้าหน้าที่มอบหมาย
  public function getDataEmailDirectorCBCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->whereIn('certi',[1803])->whereIn('roles',[1,3])->get();
    if(count($email) > 0){       // ผก.
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
    // if(count($this->certi_cb_checks) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
    //     $examiner = HP::getArrayFormSecondLevel($this->certi_cb_checks->toArray(), 'user_id');
    //     $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
    //      foreach ($Users as $key => $item) {
    //         if(!is_null($item)){
    //             $datas[] = $item;
    //         }
    //      }
    //  }
      return $datas;
  }

    // mail    ผก. +  เจ้าหน้าที่มอบหมาย
    public function getDataEmailDirectorCBAttribute() {
        $datas = [];
        $email = CertiEmailLt::select('emails')->whereIn('certi',[1803])->whereIn('roles',[1])->get();
        if(count($email) > 0){       // ผก.
            foreach ($email as $key => $item) {
                if(!is_null($item)){
                    $datas[$item->emails] = $item->emails ;
                }
            }
        }
        if(count($this->certi_cb_checks) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
            $examiner = HP::getArrayFormSecondLevel($this->certi_cb_checks->toArray(), 'user_id');
             $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
         foreach ($Users as $key => $item) {
            if(!is_null($item)){
                $datas[$item] = $item;
            }
         }
         }
          return $datas;
      }

  // mail  ผก. +  ลท.
  public function getDataEmailDirectorAndLtCBCCAttribute() {
    $datas = [];
    $email = CertiEmailLt::select('emails')->where('cc',1)->whereIn('certi',[1803])->whereIn('roles',[1,2])->get();
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
  public function getDataEmailDirectorCBReplyAttribute() {
    $datas = [];
    $email =  CertiEmailLt::whereIn('certi',[1803])->where('reply_to',1)->orderby('id','desc')->get();
    if(count($email) > 0){
        foreach ($email as $key => $item) {
            if(!is_null($item)){
                $datas[$item->emails] = $item->emails ;
            }
        }
    }
      return $datas;
  }


 // ชื่อเจ้าหน้าที่มอบหมาย
 public function getFullNameAttribute() {
    $data = HP::getArrayFormSecondLevel($this->certi_cb_checks->toArray(), 'user_id');
    $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
    foreach ($datas as $key => $list) {
           $datas[$key] = $list ;
    }
    return  (count($datas) > 0) ?  implode(',<br/>', $datas) : '-';
  }
  public function getFullRegNameAttribute() {  // show.blade.php
    $data = HP::getArrayFormSecondLevel($this->certi_cb_checks->toArray(), 'user_id');
    $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
    foreach ($datas as $key => $list) {
           $datas[$key] = $list ;
    }
    return  (count($datas) > 0) ?  implode(', ', $datas) : '-';
  }
    public function CertiCBReportTo()
    {
        return $this->belongsTo(CertiCBReport::class,'id','app_certi_cb_id')->orderby('id','desc');
    }
        // แนบใบ Pay-in ครั้งที่ 2
    public function CertiCBPayInTwoTo()
    {
        return $this->belongsTo(CertiCBPayInTwo::class,'id','app_certi_cb_id')->orderby('id','desc');
    }


    public function CertiCBFileTo()
    {
        return $this->belongsTo(CertiCBFileAll::class,'id','app_certi_cb_id');
    }

    public function certi_cBFile_state1_to()
    {
        return $this->belongsTo(CertiCBFileAll::class,'id','app_certi_cb_id')->where('state',1);
    }

 
    public function CertiCBFileAlls()
    {
        return $this->hasMany(CertiCBFileAll::class, 'app_certi_cb_id')->orderby('id','desc');
    }
    
    public function cert_cbs_file_all()
    {
        return $this->hasMany(CertiCBFileAll::class, 'app_certi_cb_id')->orderby('id','desc')->whereNotIn('status_cancel',[1]);
    }

    public function cert_cbs_file_all_order_desc()
    {
        return $this->hasMany(CertiCBFileAll::class, 'app_certi_cb_id')->orderby('created_at','desc')->whereNotIn('status_cancel',[1]);
    }

    public function CertiCBExportTo()
    {
        return $this->belongsTo(CertiCBExport::class,'id','app_certi_cb_id')->orderby('id','desc');
    }

    public function getCertiCBAuditorsDocReviewStatusFinishAttribute() {
        return CertiCBAuditors::where('app_certi_cb_id',$this->id)
        ->whereNotNull('is_review_state')
        ->where('status',1);
    }

    public function getCertiCBAuditorsStatusFinishAttribute() {
        return CertiCBAuditors::where('app_certi_cb_id',$this->id)
        ->whereNull('is_review_state')
        ->where('status',1);
    }


    public function getCertiCBAuditorsDocReviewStatusAttribute() {
        $data = HP::getArrayFormSecondLevel($this->CertiCBAuditorsManyByReview->toArray(), 'id');
        $list = '';
        $datas = CertiCBAuditors::whereIn('id', $data)
        ->whereNotNull('is_review_state')
        ->pluck('status')
        ->toArray();

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


    public function getCertiCBAuditorsStatusAttribute() {
        $data = HP::getArrayFormSecondLevel($this->CertiCBAuditorsManyBy->toArray(), 'id');
        $list = '';
        $datas = CertiCBAuditors::whereIn('id', $data)
        ->whereNull('is_review_state')
        ->pluck('status')
        ->toArray();
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

      // Pay In ครั้งที่ 1
      public function getCertiCBPayInOneStatusAttribute() {
        $data = HP::getArrayFormSecondLevel($this->CertiCBPayInOneMany->toArray(), 'id');
        $list = '';
        $datas = CertiCBPayInOne::whereIn('id', $data)->pluck('status')->toArray();
        $states = CertiCBPayInOne::whereIn('id', $data)->pluck('state')->toArray();
        // $states1 = CertiCBPayInOne::whereIn('id', $data)->where('state',1)->pluck('state')->toArray();  // จำนวนส่งไปให้ ผปก.
       // สถานะส่งไปให้ ผปก.
        $state1 = array_filter($states, function($v, $k) {
                            return $v == 1 && $v != 3;
                        }, ARRAY_FILTER_USE_BOTH);
        $state2 = array_filter($states, function($v, $k) {
                            return $v == 2  &&  $v != 3;
                        }, ARRAY_FILTER_USE_BOTH);
         $stateNull = array_filter($states, function($v, $k) {
                            return $v == null &&  $v != 3;
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
        }elseif(count($state2) > 0){
            $list = "StatusPayInOneNotNeat";
        }elseif(count($state1) > 0){
            $list = "StatePayInOne";
        }

        return $list;
      }

      public function getCertiCBSaveAssessmentStatusAttribute() {
        $list = '';
        $pay = HP::getArrayFormSecondLevel($this->CertiCBPayInOneMany->toArray(), 'id');
        $data = HP::getArrayFormSecondLevel($this->CertiCBSaveAssessmentMany->toArray(), 'id');

        $pays = CertiCBPayInOne::whereIn('id', $pay)->where('status',1)->pluck('status')->toArray();

        $data_degree = CertiCBSaveAssessment::whereIn('id', $data)->pluck('degree')->toArray();  // ทั้งหมด
        $receive = CertiCBSaveAssessment::whereIn('id', $data)->whereIn('degree',[2,5])->pluck('degree')->toArray();//  จำนวน ผปก. ส่งไปให้
        $sent = CertiCBSaveAssessment::whereIn('id', $data)->whereIn('degree',[1,3,4,6])->pluck('degree')->toArray();// จำนวน จนท. ส่งไปให้
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

      public function getStandardChangeTitleAttribute() {
        $datas = ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
          return array_key_exists($this->standard_change,$datas) ? $datas[$this->standard_change] : '';
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
  
        // เช็คขอบข่ายใน mapreq
        public function certi_cb_export_mapreq_to()
        {
            return $this->hasOne(CertiCbExportMapreq::class, 'app_certi_cb_id');
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
  
    // วันที่ยื่นคำขอรับใบรับรอง
    public function getStartDateShowAttribute()
    {
        return Carbon::hasFormat($this->start_date, 'Y-m-d')?Carbon::parse($this->start_date)->addYear(543)->isoFormat('D MMM YYYY'):null;
    }

    // เลขที่มาตรฐาน
    public function getCertificationBranchNameAttribute() {
    return !empty($this->CertificationBranchTo->title)?$this->CertificationBranchTo->title:'N/A';
    }

    // สาขา
    public function getFormulaTiTleAttribute() {
    return !empty($this->FormulaTo->title)?$this->FormulaTo->title:'N/A';
    }

    // 

    public function cbDocReviewAuditor()
    {
        return $this->hasOne(CbDocReviewAuditor::class, 'app_certi_cb_id');
    }


    public function paidPayIn1BoardAuditors()
    {
        $appCertiAssessmentIds = CertiCBPayInOne::where('app_certi_cb_id', $this->id)
            ->where('status', 1)
            ->pluck('auditors_id')
            ->toArray();

        if (!empty($appCertiAssessmentIds)) {
            
            $boardAuditors = CertiCBAuditors::whereIn('id', $appCertiAssessmentIds)->get();
            // dd($boardAuditors);
            return $boardAuditors;
        }
        // ถ้าไม่มีข้อมูล รีเทิร์นค่าเปล่า
        return null;
    }



    public function fullyApproveReport()
    {
        $auditors = $this->paidPayIn1BoardAuditors();
        // Check if auditors exist
        if($auditors !== null)
        {
            // Get array of auditor IDs
            $auditorIds = $this->paidPayIn1BoardAuditors()->pluck('id')->toArray();
            
            // Get assessment IDs for these auditors
            $certiCBSaveAssessmentIds = CertiCBSaveAssessment::whereIn('auditors_id', $auditorIds)->pluck('id');
            
            // Get report info records
            $cbReportInfos = CbReportInfo::whereIn('cb_assessment_id', $certiCBSaveAssessmentIds)->get();
            
            foreach ($cbReportInfos as $cbReportInfo)
            {
                // Check for existence of signing transaction
                $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                    ->where('certificate_type', 0)
                    ->where('report_type',1)
                    ->first();
                    
                if($signAssessmentReportTransaction == null){
                    return false;  // No signing transaction exists
                } else {
                    // Check for any unapproved transactions
                    $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                        ->where('certificate_type', 0)
                        ->where('report_type',1)
                        ->where('approval', 0)
                        ->get();
                        
                    if($signAssessmentReportTransactions->count() != 0){
                        return false;  // There are unapproved transactions
                    }
                }
            }
            return true;  // All reports are signed and approved
        }
        return false;  // No auditors found
    }


    public function report_to()
    {
        return $this->belongsTo(CertiCBReport::class,'id','app_certi_cb_id');
    }
        


    
}
