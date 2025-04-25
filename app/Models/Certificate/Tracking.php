<?php

namespace App\Models\Certificate;

use DB;
use HP;
use App\User;
use App\AttachFile;
use App\CertificateExport;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\Applicant\CertiLabExportMapreq;

class  Tracking extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking";
    protected $primaryKey = 'id';
    protected $fillable = ['certificate_type', 'reference_refno','reference_date',  'ref_table', 'ref_id', 'status_id','tax_id','user_id','agent_id','send_mail'];


    public function sso_user_to()
    {
        return $this->belongsTo(SSO_User::class, 'user_id');
    }


    public function certificate_export_to()
    {
        if($this->certificate_type == 1){
            return $this->belongsTo(CertiCBExport::class,'ref_id','id');
         }else if($this->certificate_type == 2){
            return $this->belongsTo(CertiIBExport::class,'ref_id','id');
        }else{
            return $this->belongsTo(CertificateExport::class,'ref_id','id');
        }
    }


    // start เจ้าหน้าที่ได้รับมอบหมาย
    public function assigns_to(){
        return $this->belongsTo(TrackingAssigns::class,'id','tracking_id');
    }
    public function assigns_many(){
        return $this->hasMany(TrackingAssigns::class,'tracking_id','id');
    }
    public function getAssignNameAttribute() {
            $datas = [];
            if(count($this->assigns_many) > 0){  // เจ้าหน้าที่มอบหมาย
                $assigns = HP::getArrayFormSecondLevel($this->assigns_many->toArray(), 'user_id');
                foreach ($assigns as $key => $item) {
                    $users = User::where('runrecno', $item)->value(DB::raw("CONCAT(reg_fname,' ',reg_lname)"));
                    if(!is_null($users)){
                        $datas[] = $users;
                    }
                }
            }
        return $datas;
    }

    public function getAssignEmailsAttribute() {
        $datas = [];
    if(count($this->assigns_many) > 0){  // เจ้าหน้าที่มอบหมาย
        $assigns = HP::getArrayFormSecondLevel($this->assigns_many->toArray(), 'user_id');
            foreach ($assigns as $key => $item) {
            $users = User::where('runrecno', $item)->value('reg_email');
            if(!is_null($users) && !in_array($users,$datas)){
                $datas[] = $users;
            }
            }
        }
    return $datas;
    }

    public function tracking_status()
    {
    return $this->belongsTo(TrackingStatus::class,'status_id');
    }

    public function history_many(){
        return $this->hasMany(TrackingHistory::class,'tracking_id') ->orderBy('id', 'desc');
    }

    // public function certiLabExportMapreqs(){
    //     return $this->hasMany(CertiLabExportMapreq::class,'id','certificate_exports_id');
    // }
    // end ประวัติคำขอรับใบรับรองหน่วยรับรอง
  
    public function AuditorsManyBy()
    {
        return $this->hasMany(TrackingAuditors::class,'tracking_id','id')->orderBy('id', 'asc');
    }
  
    public function auditors_status_cancel_many()
    {
        return $this->hasMany(TrackingAuditors::class,'tracking_id','id')->whereNull('status_cancel') ->whereIn('step_id',[7,9]) ->orderBy('id', 'desc');
    }
  
    public function tracking_payin_one_many()
    {
        return $this->hasMany(TrackingPayInOne::class,'tracking_id','id')->orderBy('id', 'asc');
    }
  
    public function tracking_payin_one_status1_many()
    {
        return $this->hasMany(TrackingPayInOne::class,'tracking_id','id')->where('status',1)->orderBy('id', 'asc');
    }
  
    public function tracking_assessment_many()
    {
        return $this->hasMany(TrackingAssessment::class,'tracking_id','id')->orderBy('id', 'asc');
    }
  
    public function tracking_inspection_to()
    {
        return $this->belongsTo(TrackingInspection::class,'id','tracking_id')->orderBy('id', 'desc');
    }
    public function tracking_report_to()
    {
        return $this->belongsTo(TrackingReport::class,'id','tracking_id')->orderBy('id', 'desc');
    }
    public function tracking_review_to()
    {
        return $this->belongsTo(TrackingReview::class,'id','tracking_id')->orderBy('id', 'desc');
    }
  
    public function tracking_payin_two_to()
    {
        return $this->belongsTo(TrackingPayInTwo::class,'id','tracking_id')->orderBy('id', 'desc');
    }
    
    public function getAuditorsStatusAttribute() {
        $data = HP::getArrayFormSecondLevel($this->AuditorsManyBy->toArray(), 'id');
        $list = '';
        $datas = TrackingAuditors::whereIn('id', $data)->pluck('status')->toArray();
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
        public function getCertiPayInOneStatusAttribute() {
          $data = HP::getArrayFormSecondLevel($this->tracking_payin_one_many->toArray(), 'id');
          $list = '';
          $datas = TrackingPayInOne::whereIn('id', $data)->pluck('status')->toArray();
          $states = TrackingPayInOne::whereIn('id', $data)->pluck('state')->toArray();
        
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
  
        public function getCertiSaveAssessmentStatusAttribute() {
          $list = '';
          $pay = HP::getArrayFormSecondLevel($this->tracking_payin_one_many->toArray(), 'id');
          $data = HP::getArrayFormSecondLevel($this->tracking_assessment_many->toArray(), 'id');
  
          $pays = TrackingPayInOne::whereIn('id', $pay)->where('status',1)->pluck('status')->toArray();
  
          $data_degree = TrackingAssessment::whereIn('id', $data)->pluck('degree')->toArray();  // ทั้งหมด
          $receive = TrackingAssessment::whereIn('id', $data)->whereIn('degree',[2,5])->pluck('degree')->toArray();//  จำนวน ผปก. ส่งไปให้
          $sent = TrackingAssessment::whereIn('id', $data)->whereIn('degree',[1,3,4,6])->pluck('degree')->toArray();// จำนวน จนท. ส่งไปให้
           // สถานะส่งไปให้ ผปก.
           $degree7 = array_filter($data_degree, function($v, $k) {
              return $v == 4  || $v == 7  || $v == 8  ;
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
  
  
        public function getAmountBillAllAttribute() {
          $datas = 0;
          if(count($this->tracking_payin_one_status1_many) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
              $amounts = HP::getArrayFormSecondLevel($this->tracking_payin_one_status1_many->toArray(), 'amount_bill');
                  foreach ($amounts as $key => $amount) {
                      if(!is_null($amount)){
                          $datas += $amount;
                      }   
                  }
              }
            return $datas;
        }
  
     // ไฟล์แนบท้าย pdf
    public function FileAttachPDFTo()
    {
        return $this->belongsTo(AttachFile::class, 'id','ref_id')
                        ->select('id','new_filename','filename','url')
                        ->where('ref_table',$this->table)
                        ->where('section','attach_pdf')
                        ->orderby('id','desc');
    }
    // ไฟล์แนบท้าย docx,doc
    public function FileAttachFilesTo()
    {
        return $this->belongsTo(AttachFile::class, 'id','ref_id')
                        ->select('id','new_filename','filename','url')
                        ->where('ref_table',$this->table)
                        ->where('section','attach')
                        ->orderby('id','desc');
    }



}
