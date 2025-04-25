<?php

namespace App\Models\Certify\ApplicantIB;

use DB;
use HP;

use App\User;
use App\Certify\IbReportInfo;

use App\Certify\IbReportTwoInfo;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class CertiIBSaveAssessment extends Model
{
    use Sortable;
    protected $table = 'app_certi_ib_assessment';
    protected $primaryKey = 'id';
    protected $fillable = ['app_certi_ib_id','auditors_id', 'name','laboratory_name','report_date','bug_report','degree','main_state','details','date_car','status_car','state','created_by', 'updated_by','date_scope_edit','submit_type','expert_token','accept_fault','notice_duration','notice_confirm_date'];



    public function CertiIBCostTo()
    {
        return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
    }


    public function getDegreeTitleAttribute() {
        $degree = ['0'=>'ฉบับร่าง','1'=>'พบข้อบกพร่อง','2'=>'พบข้อบกพร่อง','3'=>'ไม่ผ่าน','4'=>'ผ่าน'];
        if( array_key_exists($this->degree,$degree)){
            return $degree[$this->degree];
        }else{
            return "เกิดข้อผิดพลาด";
        }

      }

    public function UserTo()
    {
        return $this->belongsTo(User::class,'created_by','runrecno');
    }
    public function CertiIBTo()
    {
        return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
    }
       // แต่งตั้งคณะผู้ตรวจประเมิน
   public function CertiIBAuditorsTo()
   {
       return $this->belongsTo(CertiIBAuditors::class,'auditors_id');
   }
   public function getAuditorsTitleAttribute() {
    $list = '';
     $auditor = @$this->CertiIBAuditorsTo->auditor ?? null;
     $app_no =  @$this->CertiIBTo->app_no ?? null;
      return  $auditor.' ('. $app_no .')' ?? '-';
  }
    public function CertiIBBugMany()
    {
       return $this->hasMany(CertiIBSaveAssessmentBug::class, 'assessment_id','id');
    }
    public function CertiIbHistorys()
    {
      $tb = new CertiIBSaveAssessment;
    return $this->hasMany(CertiIbHistory::class, 'ref_id')
              ->where('table_name',$tb->getTable())
              ->where('system',7);
    }

    public function LogCertiIbHistorys()
    {
     $tb = new CertiIBSaveAssessment;
     return $this->hasMany(CertiIbHistory::class, 'ref_id','id')
               ->where('table_name',$tb->getTable())
               ->where('system',8);
    }

    //รายงานการตรวจประเมิน
    public function FileAttachAssessment1To()
    {
        $tb = new CertiIBSaveAssessment;
        return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',1)
                    ->orderby('id','desc');
    }
    //รายงาน Scope
    public function FileAttachAssessment2Many()
    {
        $tb = new CertiIBSaveAssessment;
        return $this->hasMany(CertiIBAttachAll::class, 'ref_id','id')
                    ->select('id','file','file_client_name')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2)
                    ->orderby('id','desc');
    }
    //สรุปรายงานการตรวจทุกครั้ง
    public function FileAttachAssessment3Many()
    {
       $tb = new CertiIBSaveAssessment;
       return $this->hasMany(CertiIBAttachAll::class, 'ref_id','id')
                   ->select('id','file','file_client_name')
                   ->where('table_name',$tb->getTable())
                   ->where('file_section',3);
    }
    //ไฟล์แนบ
    public function FileAttachAssessment4Many()
    {
       $tb = new CertiIBSaveAssessment;
       return $this->hasMany(CertiIBAttachAll::class, 'ref_id','id')
                   ->select('id','file','file_client_name')
                   ->where('table_name',$tb->getTable())
                   ->where('file_section',4);
    }
    //ไฟล์แนบ car
        public function FileAttachAssessment5To()
        {
            $tb = new CertiIBSaveAssessment;
            return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                        ->where('table_name',$tb->getTable())
                        ->where('file_section',5)
                        ->orderby('id','desc');
        }

      // ไม่เห็นชอบกับ Scope
      public function FileAttachAssessment6Many()
      {
         $tb = new CertiIBSaveAssessment;
          return $this->hasMany(CertiIBAttachAll::class,'ref_id','id')
                         ->where('table_name',$tb->getTable())
                         ->where('file_section',6)
                         ->orderby('id','desc');
       }



    public function getStatusTitleAttribute() {
        $list = '';
          if($this->bug_report == 1){
            $list =  'พบข้อบกพร่อง';
          }else{
            $list =  'ไม่พบข้อบกพร่อง';
          }
          return  $list ?? '-';
    }

    public function auditorIbRepresentatives()
    {
        return $this->hasMany(AuditorIbRepresentative::class, 'assessment_id','id');
    }

    public function ibReportInfo() {
      return $this->hasOne(IbReportInfo::class, 'ib_assessment_id','id');
    }

    public function ibReportTwoInfo() {
      return $this->hasOne(IbReportTwoInfo::class, 'ib_assessment_id','id');
    }
}
