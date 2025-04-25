<?php

namespace App\Models\Certificate;
use HP;
use App\User; 
use App\AttachFile;
use App\CertificateExport;
use Kyslik\ColumnSortable\Sortable;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\MessageRecordTrackingTransaction;
use App\Models\Bcertify\BoardAuditorTrackingMsRecordInfo;

class  TrackingAuditors extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking_auditors";
    protected $primaryKey = 'id';
    protected $fillable = ['tracking_id','certificate_type', 'reference_refno', 'ref_table', 'ref_id', 'no', 
    'auditor', 'vehicle', 'status', 'remark', 'step_id', 'status_cancel', 'reason_cancel', 'created_cancel', 'date_cancel', 'state', 'created_by', 'updated_by','message_record_status'];



    public function tracking_to()
    {
        return $this->belongsTo(Tracking::class,'tracking_id');
    }


    public function auditors_date_many()
    {
     return $this->hasMany(TrackingAuditorsDate::class, 'auditors_id','id' );
    }

    public function auditors_status_many()
    {
     return $this->hasMany(TrackingAuditorsStatus::class, 'auditors_id','id' );
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
        //  สถานะขั้นตอนการทำงาน
      public function certi_auditors_step_to()
      {
          return $this->belongsTo(TrackingAuditorsStep::class,'step_id');
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

 
    public function getStatusTitleAttribute() {
      $list = '';
        if($this->status == null){
            $list =  'ขอความเห็นการแต่งตั้งคณะผู้ตรวจประเมิน';
        }elseif($this->status == 1){
            $list =  'เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน';
         }elseif($this->status == 2){
          $list =  'ไม่เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน';
        }elseif($this->status == 3){
          $list =  'ยกเลิกแต่งตั้งคณะผู้ตรวจประเมินเรียบร้อยแล้ว';
        }
        
        return  $list ?? '-';
     }
   public function getCertiAuditorsDateTitleAttribute() {
      $data = HP::getArrayFormSecondLevel($this->auditors_date_many->toArray(), 'id');
      $datas = TrackingAuditorsDate::select('start_date','end_date')->whereIn('id', $data)->get();
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
      return implode(", ", json_decode($datas,true));
    }
  
    public function getStartDateBoardAuditorAttribute() {
      $data = HP::getArrayFormSecondLevel($this->auditors_date_many->toArray(), 'id');
      $start_date = TrackingAuditorsDate::select('start_date','end_date')->whereIn('id', $data)->orderby('start_date','asc')->first();
      return  !is_null($start_date) ? $start_date : null;
    }
  
    // จำนวนเงินทั้งหมดที่ประเมินได้
    public function getSumCostConFirmAttribute() {
      $data = $this->auditors_status_many;
      $countItem = 0;
      if(count($data) > 0){
          foreach($data as $item){
              $countItem += $item->amount * $item->amount_date;
          }
      }
      return number_format($countItem,2) ?? 0;
     }
     


    public function FileAuditors1()
    {

    //   $latestFile = $this->belongsTo(AttachFile::class, 'id', 'ref_id')
    // ->select('id', 'new_filename', 'filename', 'url')
    // ->where('ref_table', $this->table)
    // ->where('section', 'other_attach')
    // ->orderBy('created_at', 'desc')
    // ->get()
    // ->first()
    // return $latestFile;
      //  dd($this->belongsTo(AttachFile::class,'id','ref_id')
      //  ->select('id','new_filename','filename','url')
      //  ->where('ref_table',$this->table)
      //  ->where('section','other_attach')->get());
       return $this->belongsTo(AttachFile::class,'id','ref_id')
                   ->select('id','new_filename','filename','url')
                   ->where('ref_table',$this->table)
                   ->where('section','other_attach');
    }  

    public function FileAuditorsLatestFile()
    {

      $latestFile = $this->belongsTo(AttachFile::class, 'id', 'ref_id')
            ->select('id', 'new_filename', 'filename', 'url')
            ->where('ref_table', $this->table)
            ->where('section', 'other_attach')
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();
    return $latestFile;

    }  

    public function FileAuditors2()
    {
 
       return $this->belongsTo(AttachFile::class,'id','ref_id')
                   ->select('id','new_filename','filename','url')
                   ->where('ref_table',$this->table)
                   ->where('section','attach');
    }    
    
    public function messageRecordTrackingTransactions()
    {
        return $this->hasMany(MessageRecordTrackingTransaction::class, 'ba_tracking_id', 'id');
    }

    public function boardAuditorTrackingMsRecordInfos()
    {
        return $this->hasMany(BoardAuditorTrackingMsRecordInfo::class, 'tracking_auditor_id', 'id');
    }
    

}
