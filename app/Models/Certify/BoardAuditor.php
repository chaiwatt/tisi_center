<?php

namespace App\Models\Certify;

use HP;
use App\User;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

use App\Models\Certify\BoardAuditorDate;
use App\Models\Certify\CertificateHistory;
use  App\Models\Certify\Applicant\CertiLab;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\BoardAuditorHistory;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\CertiLabStep;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Bcertify\BoardAuditorMsRecordInfo;
use App\Models\Certify\Applicant\CostItemConFirm;

class BoardAuditor extends Model
{
    use Sortable;
    protected $table = "board_auditors";
    protected $primaryKey = 'id';
    protected $fillable = ['certi_no','app_certi_lab_id', 'no', 'check_date', 'check_end_date', 'file', 'attach','created_by', 'updated_by','status','state','vehicle','status_cancel',
    'reason_cancel','created_cancel','date_cancel','attach_client_name','file_client_name','step_id','auditor','message_record_status'];
    protected $dates = ['check_date','check_end_date'];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function certi_lab_step_to(){
        return $this->belongsTo(CertiLabStep::class, 'step_id');
    }

    public function groups() {
        return $this->hasMany(BoardAuditorGroup::class, 'board_auditor_id');
    }
    public function DataBoardAuditorDate() {
        return $this->hasMany(BoardAuditorDate::class, 'board_auditors_id','id');
    }

    public function board_auditors_date() {
        return $this->hasOne(BoardAuditorDate::class, 'board_auditors_id','id')->orderby('id','desc');
    }

    public function CertiLabs() {
        return $this->belongsTo(CertiLab::class, 'certi_no','app_no');
    }
    public function applicant() {
        return $this->belongsTo(CertiLab::class, 'app_certi_lab_id');
    }
    public function assessment_to() {
        return $this->belongsTo(Assessment::class, 'id', 'auditor_id');
    }

    //ประวัติ
     public function CertificateHistorys() {
             $ao = new BoardAuditor;
    return $this->hasMany(CertificateHistory::class,'ref_id', 'id')->where('table_name',$ao->getTable())->whereNotNull('status');
 }
 
    public function BoardAuditorHistorys() {
        return $this->hasMany(BoardAuditorHistory::class, 'board_auditor_id','id');
    }

    public function getDataBoardAuditorDateTitleAttribute() {
        $data = HP::getArrayFormSecondLevel($this->DataBoardAuditorDate->toArray(), 'id');
        $datas = BoardAuditorDate::select('start_date','end_date')->whereIn('id', $data)->get();
        $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        foreach ($datas as $key => $list) {
              if(!is_null($list->start_date) &&!is_null($list->end_date)){
                 // ปี
                 $StartYear = date("Y", strtotime($list->start_date)) +543;
                 $EndYear = date("Y", strtotime($list->end_date)) +543;
                // เดือน
                $StartMonth= date("n", strtotime($list->start_date));
                $EndMonth= date("n", strtotime($list->end_date));
                //วัน
                $StartDay= date("j", strtotime($list->start_date));
                $EndDay= date("j", strtotime($list->end_date));
                if($StartYear == $EndYear){
                    if($StartMonth == $EndMonth){
                          if($StartDay == $EndDay){
                            $datas[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                          }else{
                            $datas[$key] =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                          }
                    }else{
                        $datas[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                    }
                }else{
                    $datas[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                }
             }
        }
        return implode("<br/>", json_decode($datas,true));
      }

        //ส่ง E-mail
      public function getDataBoardAuditorDateMailAttribute() {
        try {
        $datas1 = [];
        if(count($this->DataBoardAuditorDate) > 0){
            $data = HP::getArrayFormSecondLevel($this->DataBoardAuditorDate->toArray(), 'id');
            $datas = BoardAuditorDate::select('start_date','end_date')->whereIn('id', $data)->get();
            $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            foreach ($datas as $key => $list) {
                  if(!is_null($list->start_date) &&!is_null($list->end_date)){
                     // ปี
                     $StartYear = date("Y", strtotime($list->start_date)) +543;
                     $EndYear = date("Y", strtotime($list->end_date)) +543;
                    // เดือน
                    $StartMonth= date("n", strtotime($list->start_date));
                    $EndMonth= date("n", strtotime($list->end_date));
                    //วัน
                    $StartDay= date("j", strtotime($list->start_date));
                    $EndDay= date("j", strtotime($list->end_date));
                    if($StartYear == $EndYear){
                        if($StartMonth == $EndMonth){
                              if($StartDay == $EndDay){
                                $datas1[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                              }else{
                                $datas1[$key] =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                              }
                        }else{
                            $datas1[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                        }
                    }else{
                        $datas1[$key] =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                    }
                 }
            }
            return  count($datas1) > 0 ? implode(", ",$datas1) : '';
        }
      } catch (\Exception $e) {
         return  '';
      }
      }
      public function getStartDateBoardAuditorAttribute() {
        $data = HP::getArrayFormSecondLevel($this->DataBoardAuditorDate->toArray(), 'id');
        $start_date = BoardAuditorDate::select('start_date','end_date')->whereIn('id', $data)->orderby('start_date','asc')->first();
        return  !is_null($start_date) ? $start_date : null;
      }


      public function cost_item_confirm() {
        return $this->hasMany(CostItemConFirm::class, 'board_auditors_id')->select('amount_date','amount','desc');
     }

     public function getSumCostItemConFirmAttribute() {
        $data = $this->cost_item_confirm;
        $countItem = 0;
        if(count($data) > 0){
            foreach($data as $item){
                $countItem += $item->amount * $item->amount_date;
            }
        }
        return  $countItem;
       }


    public function auditor_information() {
        return $this->hasManyThrough(
            BoardAuditorInformation::class,
            BoardAuditorGroup::class,
            'board_auditor_id',
            'group_id'
        );
    }

    public function getgroupsTitleAttribute() {
        $Group = HP::getArrayFormSecondLevel($this->groups->toArray(), 'id');
        $BoardAuditor = BoardAuditorInformation::whereIn('group_id', $Group)->pluck('auditor_id');
        if(count($BoardAuditor) > 0){
            return AuditorInformation::whereIn('id',$BoardAuditor)->get();
        }else{
            return [];
        }
      
      }

    public function messageRecordTransactions()
    {
        return $this->hasMany(MessageRecordTransaction::class, 'board_auditor_id')->where('certificate_type',2);
    }

    public function boardAuditorMsRecordInfos()
    {
        return $this->hasMany(BoardAuditorMsRecordInfo::class, 'board_auditor_id', 'id');
    }
        
}
