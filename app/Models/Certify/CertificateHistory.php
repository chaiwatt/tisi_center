<?php

namespace App\Models\Certify;

use HP;
use App\User;
use App\Models\Tis\BoardBoardType;
use Illuminate\Support\Facades\DB;
use App\Models\Certify\BoardAuditor;
use Illuminate\Database\Eloquent\Model;


class CertificateHistory extends Model
{

    protected $table = "certificate_history";
    protected $primaryKey = 'id';
    protected $fillable = ['app_no','system', 'table_name', 'ref_id', 'details','details_table','file','attachs','status','created_by','updated_by','date','details_date','details_cost_confirm',
                            'check_status','status_scope','attachs_file','evidence','details_auditors_cancel','attach_client_name','file_client_name','scope_group'
   ];
   public function user_created(){
    return $this->belongsTo(User::class, 'created_by','runrecno');
   }
    public function getMaxAmountDateAttribute() {
            $details =   json_decode($this->details_table);
            $count_date = [];
            if(count($details) > 0) {
            foreach($details  as $item){
                $amount_date = !empty($item->amount_date) ? $item->amount_date : 0 ;
                $count_date[] = $amount_date;

            }
            }
            return  max($count_date) ?? '-';
    }

    public function getSumAmountAttribute() {
        $details =   json_decode($this->details_table);
        $countItem = 0;
        if(count($details) > 0) {
            foreach($details  as $item){
                $amount_date = !empty($item->amount_date) ? $item->amount_date : 0 ;
                $amount = !empty($item->amount) ? $item->amount : 0 ;
                $countItem += ($amount*$amount_date);
            }
        }
        return  number_format($countItem,2) ?? '-';
    }

    public function getDataBoardAuditorDateTitleAttribute() {
        $details =   json_decode($this->details_date);
        $datas = [];
        $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        if(count($details) > 0) {
       
           foreach($details  as $key => $list){
      
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
        }

        return implode("<br>",$datas);
      }

      public function getDataSystemAttribute() {
        $details = ['1'=>'การประมาณค่าใช้จ่าย',
                    '2'=>'คณะผู้ตรวจประเมิน',
                    '3'=>'Pay-In ครั้งที่ 1',
                    '4'=>'ข้อบกพร่อง/ข้อสังเกต',
                    '5'=>'สรุปรายงานและเสนออนุกรรมการฯ',
                    '6'=>'Pay-In ครั้งที่ 2',
                    '7'=>'แนบท้าย',
                    '8'=>'ขอเอกสารเพิ่มเติม',
                    '9'=>'ยกเลิกคำขอ',
                    '10'=>'ไม่ผ่านการตรวจสอบ',
                    '11'=>'บันทึกผลการตรวจประเมิน',
                    '12'=> 'ออกใบรับรอง'
                  ];
        return  array_key_exists($this->system,$details) ?  $details[$this->system] : '-';
    }

    public function getBoardAuditorAttribute() {
        $boardAuditor = BoardAuditor::find($this->ref_id);
        return $boardAuditor;
    }

    // $ref_id = DB::table('board_auditors')->where('ref_id', $value)->first();
}
