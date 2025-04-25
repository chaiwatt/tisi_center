<?php

namespace App\Models\Certificate;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\CertificateExport;
use App\AttachFile;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;

use App\Models\Certify\TransactionPayIn;


class  TrackingPayInOne extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking_pay_in1";
    protected $primaryKey = 'id';
    protected $fillable = ['tracking_id','certificate_type', 'reference_refno', 'ref_table', 'ref_id', 'conditional_type', 'auditors_id', 'amount_bill', 'start_date', 'end_date', 'status','state', 'remark', 'detail', 'start_date_feewaiver', 'end_date_feewaiver', 'created_by', 'updated_by'];

    public function tracking_to()
    {
        return $this->belongsTo(Tracking::class,'tracking_id');
    }

    public function auditors_to()
    {
     return $this->belongsTo(TrackingAuditors::class, 'auditors_id','id' );
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
    public function getDateFeewaiverAttribute() {
      $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
      $date = '';
      $start_date_feewaiver =  $this->start_date_feewaiver;
      $end_date_feewaiver =  $this->end_date_feewaiver;
    if(!is_null($start_date_feewaiver) &&!is_null($end_date_feewaiver)){
               // ปี
               $StartYear = date("Y", strtotime($start_date_feewaiver)) +543;
               $EndYear = date("Y", strtotime($end_date_feewaiver)) +543;
              // เดือน
              $StartMonth= date("n", strtotime($start_date_feewaiver));
              $EndMonth= date("n", strtotime($end_date_feewaiver));
              //วัน
              $StartDay= date("j", strtotime($start_date_feewaiver));
              $EndDay= date("j", strtotime($end_date_feewaiver));
              if($StartYear == $EndYear){
                  if($StartMonth == $EndMonth){
                        if($StartDay == $EndDay){
                          $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                        }else{
                          $date =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                        }
                  }else{
                      $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                  }
              }else{
                  $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
              }
      }
      return $date;
    }

   // หลักฐานการชำระ จนท.
    public function FileAttachPayInOne1To()
    {
        return $this->belongsTo(AttachFile::class, 'id','ref_id')
                        ->select('id','new_filename','filename','url')
                        ->where('ref_table',$this->table)
                        ->where('section','attach_payin1')
                        ->orderby('id','desc');
    }
    // หลักฐานการชำระ ผปก.
    public function FileAttachPayInOne2To()
    {
 
        return $this->belongsTo(AttachFile::class, 'id','ref_id')
                        ->select('id','new_filename','filename','url')
                        ->where('ref_table',$this->table)
                        ->where('section','attachs_file')
                        ->orderby('id','desc');
    }

    public function transaction_payin_to() {
      return $this->belongsTo(TransactionPayIn::class, 'id', 'ref_id')->where('table_name',$this->table)->whereIn('status_confirmed',[1])->orderby('id','desc');
    }
}
