<?php

namespace App\Models\Certify\Applicant;

use HP;
use App\User;
use Kyslik\ColumnSortable\Sortable;
 
use  App\Models\Certify\BoardAuditor;
use App\Models\Certify\LabReportInfo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\CertificateHistory;
use App\Models\Certificate\LabReportTwoInfo;

class Notice extends Model
{
    use Sortable;

    protected $table = "app_certi_lab_notices";
    protected $primaryKey = 'id';
    protected $fillable = ['app_certi_assessment_id','app_certi_lab_id','assessment_date','step','file','attachs','evidence','remark','file_scope','file_report','file_car','date_car','status_car',
    'draft','status','report_status','group','desc','send_email','date_record','created_by','updated_by','file_client_name','file_car_client_name' ,'degree','submit_type','expert_token','accept_fault','notice_duration','notice_confirm_date'];
    protected $dates = [
        'assessment_date',
    ];
    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
     }
    public function assessment() {
        return $this->belongsTo(Assessment::class, 'app_certi_assessment_id');
    }

    public function assessment_group() {
        return $this->belongsTo(AssessmentGroup::class, 'app_certi_assessment_group_id');
    }

    public function applicant() {
        return $this->belongsTo(CertiLab::class, 'app_certi_lab_id');
    }

    public function files() {
        return $this->hasMany(NoticeFile::class, 'app_certi_lab_notice_id');
    }

    public function items() {
        return $this->hasMany(NoticeItem::class, 'app_certi_lab_notice_id');
    }


    public function getNoticeItemTitleAttribute() {
        $data = HP::getArrayFormSecondLevel($this->items->toArray(), 'id');
        $datas = NoticeItem::whereIn('id', $data)->pluck('file_status')->toArray();
        foreach ($datas as $key => $list) {
            if(!is_null($list)){
                $datas[$key] = 'true' ;
            }else{
                $datas[$key] = 'false' ;
            }
        }
        return $datas;
      }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
      }
    //ประวัติ
    public function CertificateHistorys() {
    $ao = new Notice;
    return $this->hasMany(CertificateHistory::class,'ref_id', 'id')->where('system',4)->where('table_name',$ao->getTable());
    }
     //ประวัติ
    public function LogNotice() {
    $ao = new Notice;
    return $this->hasMany(CertificateHistory::class,'ref_id', 'id')->where('system',11)->where('table_name',$ao->getTable());
    }
    public function getStatus() {
        if ($this->draft == 0) {
            return "ฉบับร่าง";
        }
        if ($this->report_status == 1) {
            return "พบข้อบกพร่อง";
        } 
         if ($this->report_status == 2) {
            
            return "ไม่พบข้อบกพร่อง";
        }

        // if ($this->report_status == 1) {
        //     if ($this->status == 1) {
        //         return "ผ่าน";
        //     } else if ($this->status == 2) {
        //         return "ไม่ผ่าน";
        //     }else if ($this->status == 3) {
        //         return "ผ่านผลการตรวจประเมิน ";
        //     }
        //     return "พบข้อบกพร่อง";
        // } else if ($this->report_status == 2) {
            
        //     return "ไม่พบข้อบกพร่อง";
        // }

 

        return "ผิดพลาด";
    }

    public function getDataGroupeTitleAttribute() {
        $group =   json_decode($this->group,true);
        $groups = [];
        if(count($group) > 0) {
           foreach($group  as $key => $list){
            $auditors = BoardAuditor::select('id','no')->where('id',@$list)->groupBy('no')->orderby('id','desc')->first();
            if(!is_null($auditors)){
                $groups[$key] = $auditors->no;
            }
           }
        }
        return implode("<br>",$groups);
      }




    public function labReportTwoInfos(){
        return $this->hasMany(LabReportTwoInfo::class, 'app_certi_lab_notice_id', 'id');
    }
    
    public function getLabReportInfoAttribute() {
        $labReportInfo = LabReportInfo::where('app_certi_lab_notice_id',$this->id)->first();
        return $labReportInfo;
    }

    public function getLabReportTwoInfoAttribute() {
        $labReportTwoInfo = LabReportTwoInfo::where('app_certi_lab_notice_id',$this->id)->first();
        return $labReportTwoInfo;
    }
}
