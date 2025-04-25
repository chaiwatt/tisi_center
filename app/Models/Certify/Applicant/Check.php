<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use App\User; 
use HP;
use DB;
class Check extends Model
{
    protected $table = "app_certi_lab_checks";
    protected $primaryKey = 'id';
    protected $fillable = [
        'app_certi_lab_id', 'checker_id', 'desc', 'amount', 'invoice', 'reporter', 'report_date', 'state',
        'payment_file'
    ];
    protected $dates = [
        'report_date'
    ];

    public function applicant() {
        return $this->belongsTo(CertiLab::class, 'app_certi_lab_id');
    }
    public function files4() {
        return $this->hasMany(CheckFile::class, 'check_id')->where('status',4);
    }
    public function checker() {
        return $this->belongsTo('App\User', 'reporter_id');
    }

    //  ไฟล์ 3. ขอเอกสารเพิ่มเติม 
    public function files() {
        return $this->hasMany(CheckFile::class, 'check_id');
    }

    public function CheckExaminers() {
        return $this->hasMany(CheckExaminer::class, 'app_certi_lab_checks_id');
    }
    // วันที่ เจ้าหน้าที่มอนหมาย
    public function getResultReportDateAttribute() {

        $strDate = $this->report_date;
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $month = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai = $month[$strMonth];
        return "วันที่ $strDay เดือน $strMonthThai พ.ศ. $strYear";
        }
    /*
     * 1 - รอดำเนินการตรวจสอบ
     */
    public static function getCertiLabs() {
        return CertiLab::where('status', '>=', StatusTrait::$STATUS_WAIT_PROGRESS)->orderBy('created_at', 'desc');
    }
    public function getFullNameAttribute() {
        $data = HP::getArrayFormSecondLevel($this->CheckExaminers->toArray(), 'user_id');
        $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
        foreach ($datas as $key => $list) {
               $datas[$key] = $list ;
            
        }
        return  (count($datas) > 0) ?  implode(',<br/>', $datas) : '-';
      }
      public function getFullRegNameAttribute() {   
        $data = HP::getArrayFormSecondLevel($this->CheckExaminers->toArray(), 'user_id');
        $datas = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))->whereIn('runrecno', $data)->pluck('title')->toArray();
        foreach ($datas as $key => $list) {
               $datas[$key] = $list ;
        }
        return  (count($datas) > 0) ?  implode(', ', $datas) : '-';
      }
        // Mail เจ้าหน้าที่มอบหมาย
        public function getEmailStaffAssignAttribute() {
            $datas = [];
             if(count($this->CheckExaminers) > 0){  //e-mail เจ้าหน้าที่มอบหมาย
                 $examiner = HP::getArrayFormSecondLevel($this->CheckExaminers->toArray(), 'user_id');
                 $Users = User::whereIn('runrecno', $examiner)->pluck('reg_email')->toArray();
                  foreach ($Users as $key => $item) {
                     if(!is_null($item)){
                         $datas[] = $item;
                     }   
                  }
              }
           return $datas;
         }

    public function getChecker()
    {
        return $this->belongsTo('App\User', 'checker_id');
    }
}
