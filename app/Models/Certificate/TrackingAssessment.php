<?php

namespace App\Models\Certificate;

use App\User;
use App\AttachFile;
use App\CertificateExport;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate\TrackingLabReportInfo;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;

class TrackingAssessment extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking_assessment";
    protected $primaryKey = 'id';
    protected $fillable = ['tracking_id','certificate_type', 'reference_refno', 'ref_table', 'ref_id', 'auditors_id', 'name', 'laboratory_name', 'report_date', 'bug_report',
                              'degree', 'status', 'main_state', 'date_car', 'status_car', 'details', 'state', 'created_by', 'updated_by','vehicle','submit_type','expert_token','accept_fault','notice_duration','notice_confirm_date'
                       ];

      
    public function tracking_to()
    {
        return $this->belongsTo(Tracking::class,'tracking_id');
    }                 

        /*
      User Relation
    */
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
      }
  
      public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
      }
  
    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }
  
      public function getUpdatedNameAttribute() {
            return @$this->user_updated->trader_operater_name;
        }
  
        public function auditors_to()
        {
         return $this->belongsTo(TrackingAuditors::class, 'auditors_id','id' );
        }

        public function tracking_assessment_bug_many(){
          return $this->hasMany(TrackingAssessmentBug::class,'assessment_id','id');
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

                
        public function getStatusTitleAttribute() {
          $list = '';
            if($this->bug_report == 1){
              $list =  'พบข้อบกพร่อง';
            }else{
              $list =  'ไม่พบข้อบกพร่อง';
            }
            return  $list ?? '-';
      }
    
 //รายงานการตรวจประเมิน
 public function FileAttachAssessment1To()
 {
 
     return $this->belongsTo(AttachFile::class, 'id','ref_id')
                  ->select('id','new_filename','filename','url')
                  ->where('ref_table',$this->table)
                 ->where('section',1)
                 ->orderby('id','desc');
 }
 //รายงาน Scope
 public function FileAttachAssessment2Many()
 {
     return $this->hasMany(AttachFile::class, 'ref_id','id')
                  ->select('id','new_filename','filename','url')
                  ->where('ref_table',$this->table)
                 ->where('section',2)
                 ->orderby('id','desc');
 }
 //สรุปรายงานการตรวจทุกครั้ง
 public function FileAttachAssessment3Many()
 {
    return $this->hasMany(AttachFile::class, 'ref_id','id')
                ->select('id','new_filename','filename','url')
                ->where('ref_table',$this->table)
                ->where('section',3)
                ->orderby('id','desc');
 }
 //ไฟล์แนบ
 public function FileAttachAssessment4Many()
 {
    return $this->hasMany(AttachFile::class, 'ref_id','id')
                ->select('id','new_filename','filename','url')
                ->where('ref_table',$this->table)
                ->where('section',4)
                ->orderby('id','desc');
 }
 //ไฟล์แนบ car
     public function FileAttachAssessment5To()
     {
         return $this->belongsTo(AttachFile::class, 'id','ref_id')
                    ->select('id','new_filename','filename','url')
                    ->where('ref_table',$this->table)
                    ->where('section',5)
                    ->orderby('id','desc');
     }

    //  TrackingLabReportInfo

    public function trackingLabReportInfo()
    {
        return $this->hasOne(TrackingLabReportInfo::class, 'tracking_assessment_id','id');
    }


}
