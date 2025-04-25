<?php

use App\User;
use Carbon\Carbon;
use App\AttachFile;
use App\IpaymentMain;
use App\Models\Basic\Tis;
use App\Models\WS\MOILog;
use App\CertificateExport;
use App\IpaymentCompanycode;
use App\Models\Basic\Amphur;
use App\Models\Basic\Config;
use App\Models\Basic\Prefix;
use App\Models\Basic\Zipcode;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\Models\Esurv\FollowUp;
use App\Models\Basic\SetAttach;
use App\Models\Besurv\Inspector;
use App\Models\Basic\Subdistrict;
use App\Models\Basic\TisiLicense;
use App\Models\WS\IndustryRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Csurv\ControlFreeze;
use App\Models\Log\LogNotification;
use App\Models\WS\IndustryJuristic;
use App\Models\WS\IndustryPersonal;
use App\Models\Certificate\Tracking;
use App\Models\Esurv\ElicenseDetail;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Bcertify\StatusAuditor;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Asurv\EsurvVolumeBiss20;
use App\Models\Asurv\EsurvVolumeBiss21;
use App\Models\Asurv\EsurvVolumeOwns21;
use App\Models\Asurv\EsurvVolumeTers20;
use App\Models\Asurv\EsurvVolumeTers21;
use App\Models\Basic\TisiLicenseDetail;
use App\Models\Certify\CertifyLogEmail;
use Illuminate\Support\Facades\Storage;
use App\Models\Certify\TransactionPayIn;
use App\Models\Config\ConfigsFormatCode;

use App\Models\Certify\BoardAuditorGroup;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Esurv\ReceiveVolumeLicense;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Config\ConfigsFormatCodeLog;
use App\Models\Config\ConfigsFormatCodeSub;
use App\Models\Besurv\InspectorInspectorType;
use App\Models\Certificate\TrackingAuditorsList;
use App\Models\Esurv\ReceiveVolumeLicenseDetail;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certificate\TrackingAuditorsStatus;
use App\Models\Certify\Applicant\CertiLabDeleteFile;
/**
 *
 */
class HP
{



    public static function CancelCertiLab($appCertiLab,$reason)
    {//บันทึกลงตารางข้อมูลบุคคลธรรมดา

        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();   
        CertificateHistory::create([
            'app_no'=> $appCertiLab->app_no ?? null,
            'system'=> 9,
            'table_name'=> 'app_certi_labs',
            'status'=> 4,
            'ref_id'=> $appCertiLab->id,
            'details'=> $reason,
            'attachs'=> null,
            'created_by' =>  $admin->runrecno
            ]);
        CertiLab::find($appCertiLab->id)->update([
            'status' => 4
        ]);
    }

    public static function cbDocAuditorStatus($id)
    {
        return StatusAuditor::find($id);
    }

    public static function ibDocAuditorStatus($id)
    {
        return StatusAuditor::find($id);
    }

    public static function CancelCertiCb($appCertiCb,$reason)
    {//บันทึกลงตารางข้อมูลบุคคลธรรมดา
       if($appCertiCb != null)
       {
        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();   
        CertificateHistory::create([
            'app_no'=> $appCertiCb->app_no ?? null,
            'system'=> 9,
            'table_name'=> 'app_certi_cb',
            'status'=> 4,
            'ref_id'=> $appCertiCb->id,
            'details'=> $reason,
            'attachs'=> null,
            'created_by' =>  $admin->runrecno
            ]);
        CertiCb::find($appCertiCb->id)->update([
            'status' => 4
        ]);
       }

    }


    public static function isTisiOfficer($email)
    {
        // dd(User::where('reg_email',$email)->first());
        return User::where('reg_email',$email)->first();
    }

    public static function dateTimeFormatN($date)
    {//$date format d/m/Y OR d/m/Y H.i น.

        $dates = explode('-', $date);
        if (count($dates) != 3) {
            return '-';
        }

        $time = explode(' ', $date);
        $times = isset($time[1])?$time[1]:null;
        if(!is_null($times)){
            $times       = explode(':', $times);
            $time_string = $times[0].'.'.$times[1].' น.';
        }else{
            $time_string = '';
        }

        $year = ($dates[0] + 543);

        return substr($dates[2], 0, 2) . '/' . $dates[1] . '/' . $year.' '.$time_string;
    }


    static function DateThai($strDate)
    {
        if ($strDate != '') {

            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("j", strtotime($strDate));

            $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear";
        } else {
            return "";
        }
    }

    static function DateThaiFull($strDate)
    {
        if ($strDate != '') {

            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("m", strtotime($strDate));
            $strDay = date("j", strtotime($strDate));

            $strMonthCut = self::MonthList();
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear";
        } else {
            return "";
        }
    }

     static function DateThaiFormal($strDate)
    {
        if ($strDate != '') {

            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("m", strtotime($strDate));
            $strDay = date("d", strtotime($strDate));

            $strMonthCut = self::MonthList();
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai พ.ศ. $strYear";
        } else {
            return "";
        }
    }

    static function DateTimeThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute น.";
    }

    static function DateTimeThaiPipe($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear | <br> $strHour:$strMinute น.";
    }

    static function DateTimeFullThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute น.";
    }


    public static function formatDateThaiFull($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $month = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai = $month[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    public static function formatDateThaiFullPoint($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $month = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai = $month[$strMonth];
        return "$strDay $strMonthThai พ.ศ.  $strYear";
    }
    public static function formatDateThaiFullPointNotDate($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = ' ';
        $month = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai = $month[$strMonth];
        return " $strDay $strMonthThai พ.ศ. $strYear";
    }


    static function DateTimeThaiAndTime($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute:$strSeconds น.";
    }

    public static function FullDateTimeThai($strDate)
    {
        $strYear   = date("Y", strtotime($strDate))+543;
        $strMonth  = date("m", strtotime($strDate));
        $strDay    = date("j", strtotime($strDate));
        $strHour   = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        //$strSeconds= date("s", strtotime($strDate));
        $strMonthCut = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai=$strMonthCut[$strMonth];
        return "วันที่ $strDay $strMonthThai $strYear เวลา $strHour:$strMinute น.";
    }

    public static  function formatDateENFull($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthThai =  self::getMonthEn($strMonth);
        return "$strDay $strMonthThai $strYear";
    }

    public static  function formatDateENFullPoint($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthThai =  self::getMonthEn($strMonth);
        return "$strDay $strMonthThai B.E. $strYear";
    }

    public static  function formatDateENertify($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthThai =  self::getMonthEn($strMonth);
        return "$strDay $strMonthThai B.E. ".($strYear+543)." ($strYear)";
    }

    static function formatTime($strDate)
    {
        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        return "$strHour:$strMinute น.";
    }

    static  function getMonthEn($months)
    {
        $thai_month_arr = array(
            "00" => "",
            "01" => "January",
            "02" => "February",
            "03" => "March",
            "04" => "April",
            "05" => "May",
            "06" => "June",
            "07" => "July",
            "08" => "August",
            "09" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December"
        );

        return $thai_month_arr[$months];
    }
    static function MonthList()
    {
        $month = ['01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'];
        return $month;
    }

    static function MonthShortList()
    {
        $month = ['01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'];
        return $month;
    }

    static function MonthConvertList($month)
    {

        if ($month == '01') {
            $month_long = 'มกราคม';
        }
        if ($month == '02') {
            $month_long = 'กุมภาพันธ์';
        }
        if ($month == '03') {
            $month_long = 'มีนาคม';
        }
        if ($month == '04') {
            $month_long = 'เมษายน';
        }
        if ($month == '05') {
            $month_long = 'พฤษภาคม';
        }
        if ($month == '06') {
            $month_long = 'มิถุนายน';
        }
        if ($month == '07') {
            $month_long = 'กรกฎาคม';
        }
        if ($month == '08') {
            $month_long = 'สิงหาคม';
        }
        if ($month == '09') {
            $month_long = 'กันยายน';
        }
        if ($month == '10') {
            $month_long = 'ตุลาคม';
        }
        if ($month == '11') {
            $month_long = 'พฤศจิกายน';
        }
        if ($month == '12') {
            $month_long = 'ธันวาคม';
        }
        return $month_long;
    }

    static function MonthShortConvertList($month)
    {

        if ($month == '01') {
            $month_short = 'ม.ค.';
        }
        if ($month == '02') {
            $month_short = 'ก.พ.';
        }
        if ($month == '03') {
            $month_short = 'มี.ค';
        }
        if ($month == '04') {
            $month_short = 'เม.ย.';
        }
        if ($month == '05') {
            $month_short = 'พ.ค.';
        }
        if ($month == '06') {
            $month_short = 'มิ.ย.';
        }
        if ($month == '07') {
            $month_short = 'ก.ค.';
        }
        if ($month == '08') {
            $month_short = 'ส.ค.';
        }
        if ($month == '09') {
            $month_short = 'ก.ย.';
        }
        if ($month == '10') {
            $month_short = 'ต.ค.';
        }
        if ($month == '11') {
            $month_short = 'พ.ย.';
        }
        if ($month == '12') {
            $month_short = 'ธ.ค.';
        }
        return $month_short;
    }

    static function YearList()
    {

        $start_year = 2019;
        $end_year = date('Y');

        $year = [];
        for ($i = 0; $i < 5; $i++) {
            $year[$i] = $end_year + 543 + $i;
        }

        return $year;

    }

    static function YearListReport()
    {

        $start_year = date('Y') - 2;
        $end_year = date('Y') + 2;

        $year = [];
        for ($i = $start_year; $i <= $end_year; $i++) {
            $year[$i] = $i + 543;
        }

        return $year;

    }

    static function fiveYearFWList()
    {

        $start_year = date('Y') - 2;
        $end_year = date('Y') + 5;

        $year = [];
        for ($i = $start_year; $i <= $end_year; $i++) {
            $year[$i] = $i + 543;
        }

        return $year;

    }

    static function FiveYearListMinus()
    {
        $start_year = date('Y') - 4;
        $end_year = date('Y');

        $year = [];

        for ($i = $start_year; $i <= $end_year; $i++) {
            $year[$i] = $i + 543;
        }

        return $year;
    }

    static function TenYearListReport()
    {
        $start_year = date('Y') - 10;
        $end_year = date('Y');

        $year = [];

        for ($i = $start_year; $i <= $end_year; $i++) {
            $year[$i] = $i + 543;
        }

        return $year;
    }

    static function TenYearThaiListReport()
    {
        $start_year = date('Y') - 10;
        $end_year = date('Y');

        $year = [];

        for ($i = $start_year; $i <= $end_year; $i++) {
            $year[$i + 543] = $i + 543;
        }

        return $year;
    }

    //ช่วงปี [ค.ศ. => พ.ศ. ] $year_start = ปี ค.ศ. เริ่มต้น, $year_amount = จำนวนปีที่เพิ่มเพื่อแสดง
    static function YearRange($year_start=null, $year_amount=1)
    {

        $year_start = is_null($year_start) ? date('Y') : $year_start;

        $year = [];
        for ($i = 0; $i <= $year_amount; $i++) {
            $year[$year_start+$i] = $year_start + 543 + $i;
        }

        return $year;

    }

    static function year_list ($end_year=2008)
    {
        $years = [];
        for ($start_year = date('Y') + 1; $start_year >= $end_year; $start_year--) {
            $year = $start_year + 543;
            $years[$start_year] = $year;
        }

        return $years;

    }


    static function DateTimeToDate($date){
        $explode_date = explode(' ', $date);
        return (count($explode_date) == 2)?$explode_date[0]:null;
    }

    static function elicense_detail()
    {
        $e_detail = DB::table('elicense_detail')
            ->select()
            ->pluck('standard_detail');
        return $e_detail;
    }

    static function get_volume($id)
    {
        $test = DB::table('esurv_inform_volumes AS a')
            ->select(DB::raw('(SELECT t.tb3_Tisforce FROM tb3_tis t WHERE t.tb3_Tisno=a.tb3_Tisno ) AS b'))
            ->where('a.id', $id)
            ->first(['b']);

        if ($test->b == 'บ') {
            $volume = DB::table('esurv_inform_volume_licenses AS c')
                ->select(DB::raw('(SELECT t.volume1 FROM esurv_inform_volume_license_details t WHERE t.inform_volume_license_id=c.id ) AS volume1'))
                ->where('c.inform_volume_id', $id)
                ->first(['volume1']);
            if ($volume == null) {
                return '';
            }
            return $volume->volume1;
        } elseif ($test->b == 'ท') {
            $volume = DB::table('esurv_inform_volume_licenses AS c')
                ->select(DB::raw('(SELECT t.volume2 FROM esurv_inform_volume_license_details t WHERE t.inform_volume_license_id=c.id ) AS volume2'))
                ->where('c.inform_volume_id', $id)
                ->first(['volume2']);
            if ($volume == null) {
                return '';
            }
            return $volume->volume2;
        } else {
            $volume = '';
            return $volume;
        }
    }

    static function get_volume3($id)
    {
        $volume = DB::table('esurv_inform_volume_licenses AS c')
            ->select(DB::raw('(SELECT t.volume3 FROM esurv_inform_volume_license_details t WHERE t.inform_volume_license_id=c.id ) AS volume3'))
            ->where('c.inform_volume_id', $id)
            ->first(['volume3']);
        if ($volume == null) {
            return '';
        }
        return $volume->volume3;
    }

    static function get_Elicense_detail($id)
    {
        $e_id = DB::table('esurv_inform_volume_licenses AS c')
            ->select(DB::raw('(SELECT t.elicense_detail_id FROM esurv_inform_volume_license_details t WHERE t.inform_volume_license_id=c.id ) AS elicense_detail_id ,(SELECT n.standard_detail FROM elicense_detail n WHERE n.id=elicense_detail_id ) AS standard_detail'))
            ->where('c.inform_volume_id', $id)
            ->first(['standard_detail']);
        if ($e_id == null) {
            return '';
        }
        return $e_id->standard_detail;
    }

    static function get_sum_row_volume($num1, $num2)
    {
        $total = 0;
        if ($num1 != '' || $num2 != '') {
            $total += (int)$num1 + (int)$num2;
        }
        return $total;
    }

    static function get_Create_name($id)
    {
        $sso_user_table = (new SSO_User)->getTable();
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw("(Select a.created_by
                               from esurv_inform_volumes a
                               where a.id = c.inform_volume_id
                              ) as in_vol_id,
                              (Select b.name
                               from $sso_user_table b
                               where b.id = in_vol_id
                              ) as name"))
            ->where('c.id', $id)
            ->first(['name']);
        return @$name->name;
    }

    static function get_Create_name_trader($id)
    {
        $sso_user_table = (new SSO_User)->getTable();
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw("(Select a.created_by from esurv_inform_volumes a where a.id = c.inform_volume_id) as in_vol_id,(Select b.name from $sso_user_table b where b.id = in_vol_id) as name"))
            ->where('c.id', $id)
            ->first(['name']);
        return $name->name;
    }

    static function get_tb3_Tisno($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.tb3_Tisno from esurv_inform_volumes a where a.id = c.inform_volume_id) as tb3_Tisno'))
            ->where('c.id', $id)
            ->first(['tb3_Tisno']);
        return $name->tb3_Tisno;
    }

    static function get_tb3_TisThainame($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.tb3_Tisno from esurv_inform_volumes a where a.id = c.inform_volume_id) as in_vol_id,(Select b.tb3_TisThainame from tb3_tis b where b.tb3_Tisno = in_vol_id) as name'))
            ->where('c.id', $id)
            ->first(['name']);
        return $name->name;
    }

    static function get_created_at($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.created_at from esurv_inform_volumes a where a.id = c.inform_volume_id) as created_at'))
            ->where('c.id', $id)
            ->first(['created_at']);
        return $name->created_at;
    }


    static function get_consider_name($id)
    {
        $f_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_fname']);
        $l_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_lname']);
        $fname = !empty($f_name)?$f_name->reg_fname:'n/a';
        $lname = !empty($l_name)?$l_name->reg_lname:'n/a';
        return $fname." ".$lname;
    }

    static function get_consider($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.consider from esurv_inform_volumes a where a.id = c.inform_volume_id) as consider'))
            ->where('c.id', $id)
            ->first(['consider']);
        return $name->consider;
    }

    static function get_inform_month($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.inform_month from esurv_inform_volumes a where a.id = c.inform_volume_id) as inform_month'))
            ->where('c.id', $id)
            ->first(['inform_month']);
        return $name->inform_month;
    }

    static function get_inform_year($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.inform_year from esurv_inform_volumes a where a.id = c.inform_volume_id) as inform_year'))
            ->where('c.id', $id)
            ->first(['inform_year']);
        return $name->inform_year;
    }

    static function get_detail_e($id)
    {
        $e_id = DB::table('elicense_detail AS c')
            ->select()
            ->where('c.id', $id)
            ->first(['standard_detail']);
        if ($e_id == null) {
            return '';
        }
        return $e_id->standard_detail;
    }

    static function get_applicant_name($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.applicant_name from esurv_inform_volumes a where a.id = c.inform_volume_id) as applicant_name'))
            ->where('c.id', $id)
            ->first(['applicant_name']);
        return $name->applicant_name;
    }

    static function get_tel($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.tel from esurv_inform_volumes a where a.id = c.inform_volume_id) as tel'))
            ->where('c.id', $id)
            ->first(['tel']);
        return $name->tel;
    }

    static function get_email($id)
    {
        $name = DB::table('esurv_inform_volume_licenses as c')
            ->select(DB::Raw('(Select a.email from esurv_inform_volumes a where a.id = c.inform_volume_id) as email'))
            ->where('c.id', $id)
            ->first(['email']);
        return $name->email;
    }


    //แปลงวันที่รูปแบบ 31/01/2018 เป็น 2018-01-31
    static function convertDate($date, $minus = false)
    {
        if(Carbon::hasFormat($date, 'd/m/Y')){
            $negative = $minus === true ? 543 : 0;
            $CutDate  = explode('/', $date);
            $result = Carbon::parse($CutDate[2]-$negative.'-'.$CutDate[1].'-'.$CutDate[0]);
            return $result->format('Y-m-d');
        }
    }

    //แปลงวันที่รูปแบบ 2018-01-31 เป็น 31/01/2018
    static function revertDate($date, $plus = false)
    {
        if(Carbon::hasFormat($date, 'Y-m-d') || Carbon::hasFormat($date, 'Y-m-d H:i:s')){
            $positive = $plus === true ? 543 : 0;
            $result = Carbon::parse($date);
            return $result->format('d/m/').$result->addYear($positive)->format('Y');
        }
    }
	
    static function revertDateThaiShort($date, $plus = false)
    {
        if(Carbon::hasFormat($date, 'Y-m-d') || Carbon::hasFormat($date, 'Y-m-d H:i:s')){
            $positive = $plus === true ? 543 : 0;
            $CutDate  = explode( '-', $date);
            if( $CutDate[1] == '02' && $CutDate[2] == 29 ){
                $strYear  = Carbon::createFromFormat("Y", $CutDate[0] )->format('YYYY');          
                $strMonth = Carbon::createFromFormat("m", $CutDate[1] )->format('MMM'); 
                $strDay   = Carbon::createFromFormat("d", $CutDate[2] )->format('D'); 
                return  $strDay.' '.$strMonth.' '.( $strYear +  $positive);
            }
            return Carbon::parse($date)->addYear($positive)->isoFormat('D MMM YYYY');
        }
    }
	
    static function revertDateThaiFull($date, $plus = false)
    {
        if(Carbon::hasFormat($date, 'Y-m-d') || Carbon::hasFormat($date, 'Y-m-d H:i:s')){
            $positive = $plus === true ? 543 : 0;
            $CutDate  = explode( '-', $date);
            if( $CutDate[1] == '02' && $CutDate[2] == 29 ){
                $strYear  = Carbon::createFromFormat("Y", $CutDate[0] )->format('YYYY');          
                $strMonth = Carbon::createFromFormat("m", $CutDate[1] )->format('MMMM'); 
                $strDay   = Carbon::createFromFormat("d", $CutDate[2] )->format('D'); 
                return  $strDay.' '.$strMonth.' '.( $strYear +  $positive);
            }
            return Carbon::parse($date)->addYear($positive)->isoFormat('D MMMM YYYY');
        }
    }
	
    static function revertDateThaiShortWithTime($date, $plus = false)
    {
        if(Carbon::hasFormat($date, 'Y-m-d H:i:s')){
            $positive = $plus === true ? 543 : 0;
            $CutDate  = explode( '-', $date);
            if( $CutDate[1] == '02' && $CutDate[2] == 29 ){
                $strYear  = Carbon::createFromFormat("Y", $CutDate[0] )->format('YYYY');          
                $strMonth = Carbon::createFromFormat("m", $CutDate[1] )->format('MMMM'); 
                $strDay   = Carbon::createFromFormat("d", $CutDate[2] )->format('D'); 
                $strTime  = Carbon::parse($date)->isoFormat('HH:mm:ss');
                return  $strDay.' '.$strMonth.' '.( $strYear +  $positive).' '. $strTime ;
            }
            return Carbon::parse($date)->addYear($positive)->isoFormat('D MMM YYYY HH:mm:ss');
        }
    }
	
    static function revertDateThaiFullWithTime($date, $plus = false)
    {
        if(Carbon::hasFormat($date, 'Y-m-d H:i:s')){
            $positive = $plus === true ? 543 : 0;
            $CutDate  = explode( '-', $date);
            if( $CutDate[1] == '02' && $CutDate[2] == 29 ){
                $strYear  = Carbon::createFromFormat("Y", $CutDate[0] )->format('YYYY');          
                $strMonth = Carbon::createFromFormat("m", $CutDate[1] )->format('MMMM'); 
                $strDay   = Carbon::createFromFormat("d", $CutDate[2] )->format('D'); 
                $strTime  = Carbon::parse($date)->isoFormat('HH:mm:ss');
                return  $strDay.' '.$strMonth.' '.( $strYear +  $positive).' '. $strTime ;
            }
            return Carbon::parse($date)->addYear($positive)->isoFormat('D MMMM YYYY HH:mm:ss');
        }
    }

    //แปลงวันที่เวลารูปแบบ 2018-01-31 เป็น 31/01/2018
    static function revertDateTime($date, $plus = false)
    {
        $time =   date("H:i:s", strtotime($date));
        $date = \Carbon\Carbon::parse($date)->format('Y-m-d');

        $positive = $plus === true ? 543 : 0;
        $dates = explode('-', $date);
        $time = !empty($time) ? ' '.$time : '';
        return (count($dates) == 3 ) ? $dates[2] . '/' . $dates[1] . '/' . ($dates[0] + $positive).$time   : null ;
    }
    // user
    static function UserTitle($id)
    {
        $user =  User::where('runrecno', $id)->first();
        return $user;
    }

    static function OwnTisList()
    {//รายการมาตรฐานบังคับที่ได้รับใบอนุญาต

        $tax = auth()->user()->trader_id;
        $licenses = TisiLicense::where("tbl_taxpayer", $tax)->pluck('tbl_tisiNo', 'tbl_tisiNo');

        $tis = Tis::select(
            DB::raw("CONCAT('มอก.', tb3_Tisno, ' ', tb3_tis.tb3_TisThainame, ' ', IF(tb3_Tisforce='บ', '(มาตรฐานบังคับ)', '(มาตรฐานทั่วไป)')) AS name, tb3_Tisno")
        )->whereIn("tb3_Tisno", $licenses)
            ->pluck('name', 'tb3_Tisno');
        return $tis;
    }

    static function OwnLicenseByTis($tis_no)
    {//รายการเลขที่ใบอนุญาตของผปก.ตามมาตรฐาน

        $tax = auth()->user()->trader_id;

        $licenses = TisiLicense::where("tbl_taxpayer", $tax)->where("tbl_tisiNo", $tis_no)->get();

        return $licenses;
    }

    static function UserRegister()
    {
        $sql = DB::table('user_register as a')
            ->select()
            ->get();
        return $sql;
    }

    static function LicenseDetailByLicenseNo($Autono)
    {//รายการรายละเอียดผลิตภัณฑ์ใบอนุญาตตามเลขรันใบอนุญาต

        $tax = auth()->user()->trader_id;

        $license = TisiLicense::where("Autono", $Autono)->first();//ใบอนุญาต

        $details = TisiLicenseDetail::where("tbl_licenseNo", $license->tbl_licenseNo)->get();//รายละเอียดผลิตภัณฑ์ในใบอนุญาต

        return $details;
    }

    static function License($Autono)
    {//ข้อมูลใบอนุญาต

        $license = TisiLicense::where("Autono", $Autono)->first();//ข้อมูลใบอนุญาต

        return $license;
    }

    static function TisList($status_not_ins=null)
    {//รายการมาตรฐาน

        $tis =  Tis::select(
                    DB::raw("CONCAT('มอก.', tb3_Tisno, ' ', tb3_tis.tb3_TisThainame, ' ', IF(tb3_Tisforce='บ', '(มาตรฐานบังคับ)', '(มาตรฐานทั่วไป)')) AS name, tb3_Tisno")
                )->when(is_array($status_not_ins), function($query) use ($status_not_ins) {
                    $query->whereNotIn('status', $status_not_ins);
                })
                ->pluck('name', 'tb3_Tisno');
        return $tis;
    }

    static function TisListSample(){
        $sub_query = TisiLicense::select('tbl_tisiNo')->distinct()->get();
        $tis = Tis::select(DB::raw("CONCAT('มอก.', tb3_Tisno, ' ', tb3_tis.tb3_TisThainame, ' ', IF(tb3_Tisforce='บ', '(มาตรฐานบังคับ)', '(มาตรฐานทั่วไป)')) AS name, tb3_Tisno")
        )->whereIN('tb3_Tisno',$sub_query)->pluck('name','tb3_Tisno');
        // dd($tis);
        return $tis;
    }

    static function Ref_number($name)
    {
        $ref_num = ControlFreeze::query()->where('check_officer', $name)->pluck('auto_id_doc');
        return $ref_num;
    }

    static function TisLicense()
    {

        $tis_license = DB::table('tb4_tisilicense')->groupBy('tbl_tradeName')->pluck('tbl_tradeName');
        return $tis_license;
    }

    static function LicenseByTis($tis_no)
    {//รายการเลขที่ใบอนุญาตของผปก.ตามมาตรฐาน

        $licenses = TisiLicense::where("tbl_tisiNo", $tis_no)->get();

        return $licenses;
    }

    static function LicenseByTraderTis($trader_autonumber, $tis_no)
    {//รายการเลขที่ใบอนุญาตตามมาตรฐาน และผปก.

        $user = SSO_User::find($trader_autonumber);
        $licenses = !is_null($user) ? TisiLicense::where("tbl_taxpayer", $user->tax_number)->where("tbl_tisiNo", $tis_no)->get() : collect([]);
        return $licenses;
    }

    static function LicenseByTraderTis2($trader_autonumber, $tis_no)
    {
        //รายการเลขที่ใบอนุญาตตามมาตรฐาน และผปก.
        $licenses = TisiLicense::where("tbl_taxpayer", $trader_autonumber)->where("tbl_tisiNo", $tis_no)->where("tbl_licenseStatus",'1')->get();
        return $licenses;
    }

    static function getArrayFormSecondLevel($array, $key_value, $key_index = NULL)
    {//ดึงค่าจาก Array ชั้นที่ 2
        $array_result = array();
        foreach ($array as $array_two) {
            $array_two = (array)$array_two;
            if (is_null($key_index)) {
                $array_result[] = $array_two[$key_value];
            } else {
                $array_result[$array_two[$key_index]] = $array_two[$key_value];
            }
        }
        return $array_result;
    }

    static function YearCal($date)
    {
        $dates = explode('-', $date);
        $year_def = date('Y') - $dates[0];
        return $year_def;
    }

    static function BankAccounts()
    {
        return array('1' => 'ออมทรัพย์', '2' => 'ประจำ', '3' => 'กระแสรายวัน');
    }

    static function CertifyApplicantTypes()
    {
        return array('1' => 'CB', '2' => 'IB', '3' => 'LAB');
    }

    static function GHGKinds()
    {
        return array('1' => 'องค์กร', '2' => 'โครงการ');
    }

    static function AuditorKinds()
    {
        return array('1' => 'ผู้ตรวจประเมิน', '2' => 'ผู้ตรวจทบทวน');
    }

    static function Forms()
    {
        return array('4' => 'FCB-AP04', '5' => 'FCB-AP05', '6' => 'FCB-AP06');
    }

    static function Scopes()
    {
        return array('ISIC' => 'ISIC', 'IAF' => 'IAF', 'ENMS' => 'ENMS', 'GHG' => 'GHG');
    }

    static function DepartmentTypes()
    {
        return array('1' => 'ผู้ทำ', '2' => 'ผู้ใช้', '3' => 'นักวิชาการ');
    }

    static function Mades()
    {
        return array('tisi' => 'สมอ.', 'SDO' => 'SDO');
    }

    static function InspectorTypes()
    {
        return array('1' => 'IB', '2' => 'LAB (ทดสอบ)', '3' => 'LAB (สอบเทียบ)');
    }

    static function OtherTypes()
    {//การแจ้งอื่นๆ
        return ['1'=>'แนะนำ', '2'=>'สอบถาม', '3'=>'ขอให้ดำเนินการ', '4'=>'ขอให้ปรับปรุงฐานข้อมูลใบอนุญาต', '5'=>'อื่นๆ'];
    }

    static function ReasonTypes()
    {//เหตุผลที่ยกเลิกใบอนุญาต
        return ['1' => 'ผู้รับใบอนุญาตเลิกประกอบกิจการ',
            '2' => 'ผู้รับใบอนุญาตแจ้งยกเลิก',
            '3' => 'มอก. มีการ แก้ไข เปลี่ยนแปลง หรือ ยกเลิก',
            '4' => 'เลขาธิการเพิกถอนใบอนุญาต',
            '99' => 'อื่นๆ (ระบุ)'
        ];
    }

    static function Years()
    {
        $years = [];
        for ($start_year = date('Y') + 1; $start_year >= 2008; $start_year--) {
            $year = $start_year + 543;
            $years[$year] = $year;
        }

        return $years;

    }

    static function getFileStorage($file_path)
    {//get file from storage

        $result = '';
        $root_url = config('filesystems.root_url');

        if(filter_var($root_url, FILTER_VALIDATE_URL)){ //แบบใช้ลิงค์จากเซิร์ฟเวอร์เก็บไฟล์โดยตรง

            $exists = Storage::exists($file_path);
            if ($exists) {//ถ้ามีไฟล์ใน storage
                $result = $root_url.'/'.$file_path;
            }

        }else{ //แบบโหลดไฟล์ลงมาที่เครื่องเซิร์เวอร์

            $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

            if (is_file($public . $file_path)) { //ถ้ามีไฟล์ที่พร้อมแสดงอยู่แล้ว
                $result = Storage::disk('uploads')->url($file_path);
            } else {

                $exists = Storage::exists($file_path);
                if ($exists) {//ถ้ามีไฟล์ใน storage
                    $stream = Storage::getDriver()->readStream($file_path);

                    $attach = str_replace(basename($file_path),"",$file_path);

                    if(!Storage::disk('uploads')->has($attach)){
                        Storage::disk('uploads')->makeDirectory($attach) ;
                    }
                    $byte_put = file_put_contents($public . $file_path, stream_get_contents($stream), FILE_APPEND);
                    if ($byte_put !== false) {
                        $result = Storage::disk('uploads')->url($file_path);
                    }
                }
            }

        }
        
        return $result;
    }

    public static function getFileStorageClientName($file_path,$client_name)
    {//get file from storage
        $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();
        $attach =  str_replace(basename($file_path),"",$file_path);
        if(!Storage::disk('uploads')->has($attach)){
           Storage::disk('uploads')->makeDirectory($attach) ;
        }

        if (!is_file($public.$attach.$client_name)) {//ถ้ามีไลฟ์ที่พร้อมแสดงอยู่แล้ว
            $result = Storage::disk('uploads')->url($attach.$client_name);
        } else {

            $exists = Storage::exists($file_path);
            if ($exists) {//ถ้ามีไฟล์ใน storage
                $stream = Storage::getDriver()->readStream($file_path);
                $byte_put = file_put_contents($public.$attach.$client_name, stream_get_contents($stream), FILE_APPEND);
                if ($byte_put!==false) {
                    $result = Storage::disk('uploads')->url($file_path);
                }
            }
        }
        return $result;
    }

    static function checkFileStorage($file_path)
    {//get file from storage

        $result = false;

        $root_url = config('filesystems.root_url');

        if(filter_var($root_url, FILTER_VALIDATE_URL)){ //แบบใช้ลิงค์จากเซิร์ฟเวอร์เก็บไฟล์โดยตรง
           
            $exists = Storage::exists($file_path);

            if ($exists) { //ถ้ามีไฟล์ใน storage
                
                $result = true;
            }

        }else{ //แบบโหลดไฟล์ลงมาที่เครื่องเซิร์เวอร์
            
            $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

            if (is_file($public . $file_path)) {//ถ้ามีไฟล์ที่พร้อมแสดงอยู่แล้ว
                $result = true;
            } else {

                $exists = Storage::exists($file_path);
                if ($exists) {//ถ้ามีไฟล์ใน storage
                    $result = true;
                }
            }

        }

        return $result;

    }

    public static function downloadFileFromTisiCloud($filePath)
    {
        $isExistingFile = self::checkFileStorage($filePath);
        
        if($isExistingFile)
        {
            // dd($isExistingFile);
            return self::getFileStoragePath($filePath); 
        }else
        {
            return null;
        }
    }


    //return เป็น path ของเซิร์ฟเวอร์
    static function getFileStoragePath($file_path){

        $result = '';
        $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();
        
        if (is_file($public . $file_path)) { //ถ้ามีไลฟ์ที่พร้อมแสดงอยู่แล้ว
            
            $result = Storage::disk('uploads')->path($file_path);
            // dd($result);
        } else {

            $exists = Storage::exists($file_path);
            if ($exists) { //ถ้ามีไฟล์ใน storage
                $stream = Storage::getDriver()->readStream($file_path);

                $attach = str_replace(basename($file_path), "", $file_path);

                if (!Storage::disk('uploads')->has($attach)) {
                    Storage::disk('uploads')->makeDirectory($attach);
                }
                $byte_put = file_put_contents($public . $file_path, stream_get_contents($stream), FILE_APPEND);
                if ($byte_put !== false) {
                    $result = Storage::disk('uploads')->path($file_path);
                }
            }
        }

        return $result;

    }



    static function DateFormatGroupTh($start_date = null,$end_date = null) {
        $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $date = '';
      if(!is_null($start_date) &&!is_null($end_date)){
                 // ปี
                 $StartYear = date("Y", strtotime($start_date)) +543;
                 $EndYear = date("Y", strtotime($end_date)) +543;
                // เดือน
                $StartMonth= date("n", strtotime($start_date));
                $EndMonth= date("n", strtotime($end_date));
                //วัน
                $StartDay= date("j", strtotime($start_date));
                $EndDay= date("j", strtotime($end_date));
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

        static function DateFormatGroupFullTh($start_date = null,$end_date = null) {
        $strMonthCut =  array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $date = '';
      if(!is_null($start_date) &&!is_null($end_date)){
                 // ปี
                 $StartYear = date("Y", strtotime($start_date)) +543;
                 $EndYear = date("Y", strtotime($end_date)) +543;
                // เดือน
                $StartMonth= date("n", strtotime($start_date));
                $EndMonth= date("n", strtotime($end_date));
                //วัน
                $StartDay= date("j", strtotime($start_date));
                $EndDay= date("j", strtotime($end_date));
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

      static function TimeFormat($start_time = null,$end_time = null) {

        $time = '';
      if(!is_null($start_time) &&!is_null($end_time)){
            $strMinute1 = date("i", strtotime($start_time));
            $strSeconds1 = date("s", strtotime($start_time));
            $strMinute2 = date("i", strtotime($end_time));
            $strSeconds2 = date("s", strtotime($end_time));
              $time =  $strMinute1.'.'.$strSeconds1.' - '.$strMinute2.'.'.$strSeconds2;
        }
        return $time;
      }




    //Config ระบบ
    static function getConfig($cache = true)
    {

        $key    = __CLASS__.__FUNCTION__;
        $result = new stdClass();

        if(session()->exists($key) && $cache){//มีข้อมูลที่เก็บไว้ใน session แล้ว
            $result = session($key, (object)[]);
        }else{
            $generalList = Config::select(['variable', 'data'])->get();
            foreach ($generalList as $general) {
                $variable = $general->variable;
                $result->$variable = $general->data;
            }
            session([$key => $result]);
        }

        return $result;
    }

    static function StatusReceiveVolumes()
    {

        return array('1' => 'รอดำเนินการ', '2' => 'รับเรื่อง', '3' => 'ไม่รับเรื่อง');

    }

    static function StatusReceiveApplicants()
    {

        return array('1' => 'ยื่นคำขอ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'เอกสารไม่ครบถ้วน', '4' => 'อนุมัติ', '5' => 'ไม่อนุมัติ');

    }

	 static function getErrorCode($code,$lang='th'){
        $error_code = [
			"000" => ["en" => "Success", "th" => "สำเร็จ"],
			"001" => ["en" => "Username or Password incorrect", "th" => "รหัสผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"],
			"003" => ["en" => "Cost center code not correct", "th" => "เลขรหัสศูนย์ต้นทุนไม่ถูกต้อง"],
			"004" => ["en" => "Catalog code not correct", "th" => "รหัส Catalog ไม่ถูกต้อง"],
			"005" => ["en" => "Description is so long", "th" => "รายละเอียดยาวเกินกำหนด"],
			"006" => ["en" => "Start date is not correct", "th" => "วันที่เริ่มชำระไม่ถูกต้อง"],
			"007" => ["en" => "End date is not correct", "th" => "วันครบกำหนดชำระไม่ถูกต้อง"],
			"008" => ["en" => "Amount value is not correct", "th" => "จำนวนเงินไม่ถูกต้อง"],
			"011" => ["en" => "CitizenNo is not correct", "th" => "หมายเลขประจำตัวประชาชนไม่ถูกต้อง"],
			"012" => ["en" => "First name is so long", "th" => "ชื่อยาวเกินกำหนด"],
			"013" => ["en" => "Middle name is so long", "th" => "ชื่อกลางยาวเกินกำหนด"],
			"014" => ["en" => "Last name is so long", "th" => "นามสกุลยาวเกินกำหนด"],
			"015" => ["en" => "Business No. is not correct", "th" => "ชื่อบริษัทไม่ถูกต้อง"],
			"016" => ["en" => "Business name is so long", "th" => "ชื่อบริษัทยาวเกินกำหนด"],
			"018" => ["en" => "Tax code is not correct", "th" => "เลขที่ผู้เสียภาษีไม่ถูกต้อง"],
			"019" => ["en" => "House no is not correct", "th" => "บ้านเลขที่ไม่ถูกต้อง"],
			"020" => ["en" => "Province/Amphur/Tambon/PostCode is not correct or not relation", "th" => "ข้อมูล อำเภอ ตำบล จังหวัด และรหัสไปรษณีย์ ไม่สัมพันธ์กัน"],
			"021" => ["en" => "Mobile number is not correct", "th" => "หมายเลขมือถือไม่ถูกต้อง"],
			"022" => ["en" => "Email format is not correct", "th" => "รูปแบบอีเมล์ไม่ถูกต้อง"],
			"023" => ["en" => "Control code is not correct", "th" => "โค้ดควบคุมไม่ถูกต้อง"],
			"024" => ["en" => "Create Date is not correct", "th" => "วันที่สร้างไม่ถูกต้อง"],
			"999" => ["en" => "Unknow Error", "th" => "Error ที่ไม่สามารถระบุได้"]
		];
        return   array_key_exists($code, $error_code) ? $error_code[$code][$lang] : 'เกิดข้อผิดพลาด';
	}


        // icon สกุลไฟล์แนบต่างๆ
    static function FileExtension($file) {
              $result = '';
        if(!is_null($file) && $file != ''){
            $type = strrchr(basename($file),".");
            if($type == '.pdf'    || $type ==  '.PDF'){
                $result =  '<i class="fa fa-file-pdf-o" style="font-size:20px; color:red" aria-hidden="true"></i>';
            }elseif($type == '.xlsx'){
                $result =  '<i class="fa  fa-file-excel-o" style="font-size:20px; color:#00b300" aria-hidden="true"></i>';
            }elseif($type == '.doc' || $type == '.docx'  || $type == '.DOCX'){
                $result =  '<i  class="fa fa-file-word-o"  style="font-size:20px; color:#0000ff" aria-hidden="true"></i>';
            }elseif($type == '.png' || $type == '.jpg'  || $type == '.jpeg'){
                $result =  '<i class="fa  fa-file-photo-o" style="font-size:20px; color:#ff9900" aria-hidden="true"></i>';
            }elseif($type == '.zip' || $type == '.7z' ){
                $result =  '<i class="fa fa-file-zip-o" style="font-size:20px; color:#ff0000" aria-hidden="true"></i>';
            }else{
                $result =  '<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
            }
        }else{
                 $result =  '<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
        }
        return $result;
    }

    static function FileIcon($file, $font_size='20px')
    {
        $result = '';
        if (!is_null($file) && $file != '') {
            $type = strrchr(basename($file), ".");
            if ($type == '.pdf'    || $type ==  '.PDF') {
                $result =  '<i class="fa fa-file-pdf-o" style="font-size:'.$font_size.'; color:red" aria-hidden="true"></i>';
            } elseif ($type == '.xlsx') {
                $result =  '<i class="fa  fa-file-excel-o" style="font-size:'.$font_size.'; color:#00b300" aria-hidden="true"></i>';
            } elseif ($type == '.doc' || $type == '.docx' || $type == '.DOCX'){
                $result =  '<i  class="fa fa-file-word-o"  style="font-size:'.$font_size.'; color:#0000ff" aria-hidden="true"></i>';
            } elseif ($type == '.png' || $type == '.jpg'  || $type == '.jpeg') {
                $result =  '<i class="fa  fa-file-photo-o" style="font-size:'.$font_size.'; color:#ff9900" aria-hidden="true"></i>';
            } elseif ($type == '.zip' || $type == '.7z') {
                $result =  '<i class="fa fa-file-zip-o" style="font-size:'.$font_size.'; color:#ff0000" aria-hidden="true"></i>';
            } else {
                $result =  '<i class="fa  fa-file-text" style="font-size:'.$font_size.'; color:#92b9b9" aria-hidden="true"></i>';
            }
        } else {
            $result =  '<i class="fa  fa-file-text" style="font-size:'.$font_size.'; color:#92b9b9" aria-hidden="true"></i>';
        }
        return $result;
    }

    static function DataStatusCertify()
    {
       $data = [
                // '0'=> 'ฉบับร่าง',
                '1'=> 'รอดำเนินการตรวจ',
                '2'=> 'อยู่ระหว่างการตรวจสอบ',
                '3'=> 'ขอเอกสารเพิ่มเติม',
                '4'=> 'ยกเลิกคำขอ',
                '5'=> 'ไม่ผ่านการตรวจสอบ',
              //   '6'=> 'รอดำเนินการตรวจ',
              //   '7'=> 'รอดำเนินการตรวจ',
              //   '8'=> 'รอดำเนินการตรวจ',
                '9'=> 'รับคำขอ',
                '10'=> 'ประมาณการค่าใช้จ่าย',
                '11'=> 'ขอความเห็นประมาณการค่าใช้จ่าย',
                '12'=> 'อยู่ระหว่างแต่งตั้งคณะผู้ตรวจประเมิน',
                '13'=> 'ขอความเห็นแต่งคณะผู้ตรวจประเมิน',
                '14'=> 'เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน',
                '15'=> 'แจ้งรายละเอียดค่าตรวจประเมิน',
                '16'=> 'แจ้งหลักฐานการชำระเงิน',
                '17'=> 'ยืนยันการชำระเงินค่าตรวจประเมิน',
                '18'=> 'ผ่านการตรวจสอบประเมิน',
                '19'=> 'แก้ไขข้อบกพร่อง/ข้อสังเกต',
                '20'=> 'สรุปรายงานและเสนออนุกรรมการฯ',
                '21'=> 'รอยืนยันคำขอ',
                '22'=> 'ยืนยันจัดทำใบรับรอง',
                '23'=> 'แจ้งรายละเอียดการชำระค่าใบรับรอง',
                '24'=> 'แจ้งหลักฐานการชำระค่าใบรับรอง',
                '25'=> 'ยืนยันการชำระเงินค่าใบรับรอง',
                '26'=> 'ออกใบรับรอง และ ลงนาม',
                '27'=> 'ลงนามเรียบร้อย',
               ];
      return $data;
    }

    static  function bahtText(float $amount,$currency): string
    {
        [$integer, $fraction] = explode('.', number_format(abs($amount), 2, '.', ''));

        $currency_title =  !empty($currency) ? $currency : "บาท";
        $currency =  $currency_title === "บาท" ? "สตางค์" : "ถ้วน";

        $baht = convert($integer);
        $satang = convert($fraction);

        $output = $amount < 0 ? 'ลบ' : '';
        $output .= $baht ? $baht.$currency_title : '';
        $output .= $satang ? $satang.$currency : 'ถ้วน';

        return $baht.$satang === '' ? 'ศูนย์'.$currency_title.'ถ้วน' : $output;
    }
    //บวกวันที่รับค่า $date = วันที่ในรูปแบบ ปี-เดือน-วัน, $number=จำนวนที่บวก, $type= ประเภทการบวก day หรือ month หรือ year
  static  function DatePlus($date, $number, $type='day'){
    return date ("Y-m-d", strtotime("+$number $type", strtotime($date)));
   }
   static function DatePlusBack($date, $number, $type='day'){
    return date ("Y-m-d", strtotime("-$number $type", strtotime($date)));
   }


   // รันหมายเลข pay-in ใบรับรอง LAB,CB,IB
   static function TransactionPayIn($id,$table_name = null,$certify = null,$trader = null,$amount = null,$state = null,$tis = null)
   {
        if(date('m') >= 10){
            $date = date('Y')+544;
        }else{
            $date = date('Y')+543;
        }

        $payment =  IpaymentCompanycode::where('CompanyCode','707844')->first();
        $taxid = !is_null($payment) ? $payment->TaxidAndServiceCode : '099400016516106';

        $today = date('Y-m-d');
        $dates = explode('-', $today);
        $running =  TransactionPayIn::whereYear('created_at',$dates[0])->get()->count();

        $transaction =  TransactionPayIn::where('ref_id',$id)
                                        ->where('table_name',$table_name)
                                        ->where('state',$state)
                                        ->first();
        $str =  chr(13) . chr(10);
        // $str =  chr(13);
        $number =  str_replace(".","",$amount);
    if(is_null($transaction)){
        $running_no =  str_pad(($running + 1), 5, '0', STR_PAD_LEFT);
        $no =  $running_no;
        $transaction = new TransactionPayIn;
        $transaction->running_no = $running_no;
        $transaction->state = $state;
        $transaction->created_by = auth()->user()->runrecno;
    }else{
        $transaction->updated_by = auth()->user()->runrecno;
        $no =   $transaction->running_no;
    }

        if($tis != null){
            $BarCode =  '10'.$certify.$tis.$no.$date;
        }else{
            $BarCode =  '0'.$certify.$no.$date;
        }


        $transaction->ref_id = $id;
        $transaction->table_name = $table_name;
        $transaction->certify = $certify; // 1.LAB 2.IB 3.CB
        $transaction->amount = $amount ?? null;
        $transaction->Ref_1 = $trader;
        $transaction->Ref_2 = $BarCode;
        $transaction->BarCode = "|$taxid$str$trader$str$BarCode$str$number";
        $transaction->save();

        return $transaction;
   }


   static function TransactionPayIn1($id,$table_name = null,$certify = null,$state =null,$api = [],$ref1 = null,$suffix=null)
   {
         $transaction =  TransactionPayIn::where('ref_id',$id)
                                          ->where('table_name',$table_name)
                                          ->where('state',$state)
                                         ->first();
        $suffix_data = null;
        if($suffix !== null){
            $suffix_data = $suffix;
        }                                 
        if(is_null($transaction)){
            $transaction = new TransactionPayIn;
            $transaction->state = $state;
            $transaction->created_by = @auth()->user()->runrecno;
        }else{
            $countTransaction = $transaction->count + 1;
            $transaction->updated_by = @auth()->user()->runrecno;
            $transaction->count = $countTransaction;
        }

            $transaction->ref_id                    = $id;
            $transaction->table_name                = $table_name;
            $transaction->ref1                      = $ref1;
            $transaction->suffix                    = $suffix_data;
            $transaction->certify                   = $certify; // 1.LAB 2.IB 3.CB 4.LAB(ติดตาม) 5.IB(ติดตาม) 6.CB(ติดตาม)
            $transaction->running_no                = $api->returnCode ?? null;
            $transaction->returnCode                = $api->returnCode ?? null;
            $transaction->appno                     = $api->appno ?? null;
            $transaction->bus_name                  = $api->bus_name ?? null;
            $transaction->address                   = $api->address_no ?? null;
            $transaction->allay                     = $api->allay ?? null;
            $transaction->village_no                = $api->village_no ?? null;
            $transaction->road                      = $api->road ?? null;
            $transaction->district_id               = $api->district ?? null;
            $transaction->amphur_id                 = $api->amphur ?? null;
            $transaction->province_id               = $api->province ?? null;
            $transaction->postcode                  = $api->postcode ?? null;
            $transaction->email                     = $api->email ?? null;
            $transaction->vatid                     = $api->vatid ?? null;
            $transaction->Perpose                   = $api->Perpose ?? null;
            $transaction->billNo                    = $api->billNo ?? null;
          if($certify == '1'){ // 1.LAB
            $transaction->app_certi_assessment_id   = $api->app_certi_assessment_id ?? null;
            $transaction->status_confirmed          = $api->status_confirmed ?? null;
            $transaction->auditor                   = $api->auditor?? null;
          }else if($certify == '2'){ //  2.IB
            $transaction->status_confirmed          = $api->status ?? null;
            $transaction->auditor                   = $api->auditor ?? null;
          } else if($certify == '3'){ //  3.CB
            $transaction->status_confirmed          = $api->status ?? null;
            $transaction->auditor                   = $api->auditor ?? null;
          } else if(in_array($certify,[4,5,6])){ //  4.LAB(ติดตาม) 5.IB(ติดตาม) 6.CB(ติดตาม)
            $transaction->status_confirmed          = $api->status ?? null;
            $transaction->auditor                   = $api->auditor ?? null;
          }
            $transaction->Ref_1                     = $api->CGDRef1 ?? null;
            $transaction->Ref_2                     = $api->CGDRef2 ?? null;
            $transaction->invoiceStartDate          = $api->invoiceStartDate ?? null;
            $transaction->invoiceEndDate            = $api->invoiceEndDate ?? null;
            $transaction->amount                    = !empty(str_replace(",","",$api->allPaymentAmount))?str_replace(",","",$api->allPaymentAmount):null;
            $transaction->amount_bill               = !empty(str_replace(",","",$api->amount_bill))?str_replace(",","",$api->amount_bill):null;
            $transaction->allAmountTH               = $api->allAmountTH ?? null;
            $transaction->barcodeString             = $api->barcodeString ?? null;
            $transaction->barcodeSub                = $api->barcodeSub ?? null;
            $transaction->QRCodeString              = $api->QRCodeString ?? null;
            $transaction->save();
            return $transaction;
   }
   static function TransactionPayIn2($id,$table_name = null,$certify = null,$state =null,$api = [],$suffix=null)
   {
         $transaction =  TransactionPayIn::where('ref_id',$id)
                                          ->where('table_name',$table_name)
                                          ->where('state',$state)
                                         ->first();
        $suffix_data = null;
        if($suffix !== null){
            $suffix_data = $suffix;
        }  

        if(is_null($transaction)){
            $transaction = new TransactionPayIn;
            $transaction->state = $state;
            $transaction->created_by = @auth()->user()->runrecno;
        }else{
            $countTransaction = $transaction->count + 1;
            $transaction->updated_by = @auth()->user()->runrecno;
            $transaction->count = $countTransaction;
        }
            $transaction->ref_id            = $id;
            $transaction->table_name        = $table_name;
            $transaction->ref1              = $api->appno ?? null;
            $transaction->suffix            = $suffix_data;
            $transaction->certify           = $certify; // 1.LAB 2.IB 3.CB
            $transaction->returnCode        = $api->returnCode ?? null;
            $transaction->appno             = $api->appno ?? null;
            $transaction->bus_name          = $api->bus_name ?? null;
            $transaction->address           = $api->address ?? null;
            $transaction->allay             = $api->allay ?? null;
            $transaction->village_no        = $api->village_no ?? null;
            $transaction->road              = $api->road ?? null;
            $transaction->district_id       = $api->district_id ?? null;
            $transaction->amphur_id         = $api->amphur_id ?? null;
            $transaction->province_id       = $api->province_id ?? null;
            $transaction->postcode          = $api->postcode ?? null;
            $transaction->email             = $api->email ?? null;
            $transaction->vatid             = $api->vatid ?? null;
            $transaction->Perpose           = $api->Perpose ?? null;
            $transaction->billNo            = $api->billNo ?? null;
            $transaction->Ref_1             = $api->CGDRef1 ?? null;
            $transaction->Ref_2             = $api->CGDRef2 ?? null;
            $transaction->invoiceStartDate  = $api->invoiceStartDate ?? null;
            $transaction->invoiceEndDate    = $api->invoiceEndDate ?? null;
            $transaction->amount            = !empty(str_replace(",","",$api->allPaymentAmount))?str_replace(",","",$api->allPaymentAmount):null;
            $transaction->allAmountTH       = $api->allAmountTH ?? null;
            $transaction->barcodeString     = $api->barcodeString ?? null;
            $transaction->barcodeSub        = $api->barcodeSub ?? null;
            $transaction->QRCodeString      = $api->QRCodeString ?? null;
            $transaction->save();

            return $transaction;
   }



   static function UpdateTransactionBill($id,$data =[])
   {
        $transaction =  TransactionPayIn::findOrFail($id);
        if(!is_null($transaction)){
            $transaction->update($data);
        }
    }
   // 1. ID
   // 2. Table
   // 3. วันที่ชำระ
   // 4. Data ใบรับรอง LAB/CB/IB
   // 5. หน่วยงาน
   // 6. state 1.ใบแจ้งชำระเงินค่าตรวจประเมินและค่าธรรมเนียมคำขอ 2.ค่าธรรมเนียมใบรับรอง
   static function IpaymentMain($id, $table_name = null,$date = null,$Certi = null,$subgroup = null,$state = null)
   {
       $transaction =  TransactionPayIn::where('ref_id',$id)
                                        ->where('table_name',$table_name)
                                        ->where('state',$state)
                                        ->first();

      $group = ['1802'=>'กลุ่มรับรองหน่วยตรวจ','1803'=>'กลุ่มรับรองหน่วยรับรอง','1804'=>'กลุ่มรับรองห้องปฏิบัติการ 1','1805'=>'กลุ่มรับรองห้องปฏิบัติการ 2','1806'=>'กลุ่มรับรองห้องปฏิบัติการ 3'];
      $short = ['1802'=>'รต.','1803'=>'รร.','1804'=>'รป. 1','1805'=>'รป. 2','1806'=>'รป. 3'];
      $ex_name = ['1802'=>'ใบรับรอง','1803'=>'ใบรับรอง','1804'=>'ใบรับรอง','1805'=>'ใบรับรอง 2','1806'=>'ใบรับรอง'];

     $bill_no = IpaymentMain::where('BillNo', 'LIKE', '%B%') ->orderby('BillNo','desc')->first();
     if(!is_null($bill_no)){
         $running_no =  str_replace("B","",$bill_no->BillNo);
         $BillNo =  'B'.str_pad(((int)$running_no +1), 10, '0', STR_PAD_LEFT);
     }

      if(!is_null($transaction)){
            $ipayment = new IpaymentMain;
            $ipayment->CompanyCode = '707845';
            $ipayment->BillNo = isset($BillNo) ? $BillNo : null;
            $ipayment->DateMake = date('Y-m-d h:m:s');
            $ipayment->TaxId = $Certi->EsurvTrader->trader_id ?? null;
            $ipayment->Ref_1 = $transaction->Ref_1 ?? null;
            $ipayment->CustName = $Certi->name ?? null;
            $ipayment->CustAddress = $Certi->EsurvTrader->FormatAddress ?? null;
            $ipayment->Email = $Certi->email ?? null;
            $ipayment->Ref_2 = $transaction->Ref_2 ?? null;
            $ipayment->did = 18;
            $ipayment->depart_name = 'สำนักงานคณะกรรมการการมาตรฐานแห่งชาติ';
            $ipayment->depart_nameShort = 'สก.';
            $ipayment->sub_id = $subgroup ?? null;
            $ipayment->sub_departname = array_key_exists($subgroup,$group) ? $group[$subgroup] : null;
            $ipayment->sub_depart_shortname =  array_key_exists($subgroup,$short) ? $short[$subgroup] : null;
            $ipayment->Ex_name =  array_key_exists($subgroup,$ex_name) ? $ex_name[$subgroup] : null;
            $ipayment->BookDate =  $date ??  null;
            $ipayment->MoneyBill = $transaction->amount ?? null;
            $ipayment->BarCode = $transaction->BarCode ?? null;
            $ipayment->save();
            return $ipayment;
        }else{
            return 'false';
        }
   }

   public static function formatDateThaiFullNumThai($strDate)
    {
        if (is_null($strDate) || $strDate == '' || $strDate == '-') {
            return '-';
        }

        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $month = [
            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
            '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', 
            '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', 
            '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
        ];
        $strMonthThai = $month[$strMonth];

        // แปลงตัวเลขเป็นเลขไทย
        $numthai = ["๑","๒","๓","๔","๕","๖","๗","๘","๙","๐"];
        $numarabic = ["1","2","3","4","5","6","7","8","9","0"];
        $strDayThai = str_replace($numarabic, $numthai, $strDay);
        $strYearThai = str_replace($numarabic, $numthai, $strYear);

        return "$strDayThai $strMonthThai $strYearThai";
    }


  static  function toThaiNumber($number){
    $numthai = array("๑","๒","๓","๔","๕","๖","๗","๘","๙","๐");
    $numarabic = array("1","2","3","4","5","6","7","8","9","0");
    $str = str_replace($numarabic, $numthai, $number);
    return $str;
   }

   static function getExpertInfo($groupId, $auditorId)
    {
   
        $boardAuditorGroup = BoardAuditorGroup::find($groupId);
        $auditorInformation = AuditorInformation::find($auditorId);

        $statusAuditor= StatusAuditor::find($groupId);
        
        
        return (object)[
            'statusAuditor' => $statusAuditor,
            'auditorInformation' => $auditorInformation
        ];
    }

    static function getExpertTrackingInfo($statusId, $auditorId)
    {
        $trackingAuditorsList = TrackingAuditorsList::find($auditorId);
        $statusAuditor= StatusAuditor::find($statusId);
        // dd($statusAuditor);

        return (object)[
            'statusAuditor' => $statusAuditor,
            'trackingAuditorsList' => $trackingAuditorsList
        ];
    }


   static  function CheckAsurvTis($different_no = null){

       $user = auth()->user();
      //สิทธิ์การตรวจตามกลุ่มงานย่อย
      $user_tis         = $user->tis->pluck('tb3_Tisno');
      $user_tis_list    = $user_tis->toArray();
    if($different_no == null){
        return false;
    }else if(in_array('All', $user_tis_list)){
        return true;
    }else{
        $tis_no = json_decode($different_no);
        if(!empty($tis_no)){
            $tb3_tisno = Tis::whereIn('tb3_TisAutono',$tis_no)->get();
            if(count($tb3_tisno) > 0){
                foreach ($tb3_tisno as $item) {
                    if(in_array($item->tb3_Tisno,$user_tis_list)){
                        return true;
                    }
                }
            }
        }
    }
    return false;
   }
   static  function CheckAsurv($different_no = null,$tb3_tis_autono = []){
    if($different_no == null || $tb3_tis_autono = []){
        return false;
    }else{
        $tis_no = json_decode($different_no);
        if(!empty($tis_no)){
            $tb3_tisno = Tis::whereIn('tb3_TisAutono',$tis_no)->get();
            if(count($tb3_tisno) > 0){
                foreach ($tb3_tisno as $item) {
                    if(in_array($item->tb3_Tisno,$tb3_tis_autono)){
                        return true;
                    }
                }
            }
        }
    }
    return false;
    }


    //ผู้ตรวจ รับค่าประเภทผู้ตรวจ
    static function InspectorList($inspector_types = ['1', '2', '3'])
    {

        $inspector_ids = InspectorInspectorType::whereIn('inspector_type_id', $inspector_types)->pluck('inspector_id', 'id')->toArray();

        return $inspector_list = Inspector::whereIn('id', $inspector_ids)->where('state', '1')->pluck('title', 'id')->toArray();
    }

    //ระบบ2
    static function get_detail_res_3($id1, $id2)
    {
        $detail = DB::table('save_example_type_detail')->where('example_detail_id', $id1)->where('result_id', $id2)->first(['type_detail']);
        if ($detail != null) {
            return $detail->type_detail;
        }
    }

    static function get_detail_map_lap($id1, $id2)
    {
        $maplap_ck = DB::table('ros_rbasicdata_maplab AS a')
            ->select()
            ->where('a.lab_id', $id1)
            ->where('a.tis_number', $id2)
            ->first();
        if ($maplap_ck != null) {
            $maplap = DB::table('ros_rbasicdata_maplab AS a')
                ->select()
                ->where('a.lab_id', $id1)
                ->where('a.tis_number', $id2)
                ->get();
            return $maplap;
        } else {
            $maplap = null;
            return $maplap;
        }
    }

    static function get_detail_map_lap_table($id)
    {
        $maplap = DB::table('save_example_map_lap')
            ->select()
            ->where('example_id', $id)
            ->get();
        $detail = DB::table('save_example_detail')
            ->select()
            ->where('id_example', $id)
            ->get();
        $data = null;
        foreach ($detail as $list_detail) {
            foreach ($maplap as $list_map) {
                if ($list_map->detail_product_maplap == $list_detail->detail_volume) {
                    $data[] = $list_detail->detail_volume;
                }
            }
        }

        if ($data != null) {
            $detail_main = DB::table('save_example_detail')
                ->select()
                ->whereNotIn('detail_volume', $data)
                ->where('id_example', $id)
                ->get();
            return $detail_main;
        }
    }

    static function get_detail_map_lap_table2($id)
    {
        $maplap = DB::table('save_example_map_lap')
            ->select()
            ->where('example_id', $id)
            ->get();
        $detail = DB::table('save_example_detail')
            ->select()
            ->where('id_example', $id)
            ->get();
        $data = null;
        foreach ($detail as $list_detail) {
            foreach ($maplap as $list_map) {
                if ($list_map->detail_product_maplap == $list_detail->detail_volume) {
                    $data[] = $list_detail->detail_volume;
                }
            }
        }

        if ($data != null) {
            $detail_main = null;
            return $detail_main;
        } else {
            $detail_main = DB::table('save_example_detail')
                ->select()
                ->where('id_example', $id)
                ->get();
            return $detail_main;
        }
    }

    static function get_detail_map_lap_table3($id)
    {
        $maplap = DB::table('save_example_map_lap')
            ->select()
            ->where('example_id', $id)
            ->get();
        $detail = DB::table('save_example_detail')
            ->select()
            ->where('id_example', $id)
            ->get();
        $data = null;
        foreach ($detail as $list_detail) {
            foreach ($maplap as $list_map) {
                if ($list_map->detail_product_maplap == $list_detail->detail_volume) {
                    $data[] = $list_detail->detail_volume;
                }
            }
        }

        if ($data != null) {
            $detail_main = DB::table('save_example_detail')
                ->select()
                ->whereIn('detail_volume', $data)
                ->where('id_example', $id)
                ->get();
            return $detail_main;
        } else {
            $detail_main = null;
            return $detail_main;
        }
    }

    //ระบบ3
    static function get_tb4_name()
    {
        $q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $name = $q->pluck('tbl_tradeName', 'Autono');
        return $name;
    }

    static function get_tb4_tradeName()
    {
        $query = TisiLicense::whereNotNull('tbl_taxpayer')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1')->groupBy('tbl_taxpayer')->having('tbl_taxpayer', '<>', '')->having('tbl_tradeName', '<>', '')->orderbyRaw('CONVERT(tbl_tradeName USING tis620)');
        $trader_name = $query->pluck('tbl_tradeName', 'tbl_taxpayer');
        return $trader_name;
    }

    static function get_tb4_tradername_and_oldname() // เรียงเอาชื่อล่าสุดไว้ข้างหน้าสุด แล้วเรียงลำดับ ย้อนหลังไป ชื่อก่อนสุดท้าย ไปหาชื่อแรก
    {
        $tisilicenses = TisiLicense::whereNotNull('tbl_taxpayer')->where('tbl_taxpayer','<>','')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1')->groupBy('tbl_taxpayer','tbl_tradeName')->latest('Autono')->get();
        $arr_test = [];
        $temp_arr = '';
        foreach($tisilicenses as $k => $item){
            $temp_arr = $item->tbl_taxpayer;
            if($temp_arr==$item->tbl_taxpayer){
              $arr_test[$temp_arr][] = $item->tbl_tradeName;
              continue;
            }
        }
        $arr_trader = [];
    
        foreach($arr_test as $k1=>$item1){
            $html = '';
            foreach($item1 as $k2=>$item_name){
                if($k2==0){
                $html .= $item_name;
                }else{
                $html .= ' ('.$item_name.')';
                }
            }
            $arr_trader[$k1] = $html;
        }
   
        return $arr_trader;
    }

    static function get_tb4_tradername_oldname_orderlatest() // เรียงเอาชื่อล่าสุดไว้ข้างหน้าสุด แล้วเรียงลำดับชื่อเก่าตามชื่อแรก ชื่อที่สอง ชื่อที่สาม ...
    {
        $tisilicenses = TisiLicense::whereNotNull('tbl_taxpayer')->where('tbl_taxpayer','<>','')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1')->groupBy('tbl_taxpayer','tbl_tradeName')->orderBy('Autono')->get();
        $arr_test = [];
        $temp_arr = '';
        foreach($tisilicenses as $k => $item){
            $temp_arr = $item->tbl_taxpayer;
            if($temp_arr==$item->tbl_taxpayer){
              $arr_test[$temp_arr][] = $item->tbl_tradeName;
              continue;
            }
        }

        $arr_trader = [];
        foreach($arr_test as $k1=>$item1){
            $arr_end = end($item1);
            array_pop($item1);
            $html = $arr_end;
            foreach($item1 as $k2=>$item_name){
                $html .= ' ('.$item_name.')';
            }
            $arr_trader[$k1] = $html;
        }
   
        return $arr_trader;
    }

    static function get_followup_tradename_and_oldname()
    {
        $followups = FollowUp::whereNotNull('tradename')->where('tradename','<>','')->groupBy('trader_autonumber','tradename')->latest('id')->get();
        $arr_test = [];
        $temp_arr = '';
        foreach($followups as $k => $item){
            $temp_arr = $item->trader_autonumber;
            if($temp_arr==$item->trader_autonumber){
              $arr_test[$temp_arr][] = $item->tradename;
              continue;
            }
        }

        $arr_trader = [];
        foreach($arr_test as $k1=>$item1){
            $html = '';
            foreach($item1 as $k2=>$item_name){
                if($k2==0){
                $html .= $item_name;
                }else{
                $html .= ' ('.$item_name.')';
                }
            }
            $arr_trader[$k1] = $html;
        }

        return $arr_trader;
    }

    static function get_followup_tradename_oldname_orderlatest()
    {
        $followups = FollowUp::whereNotNull('tradename')->where('tradename','<>','')->groupBy('trader_autonumber','tradename')->orderBy('id')->get();
        $arr_test = [];
        $temp_arr = '';
        foreach($followups as $k => $item){
            $temp_arr = $item->trader_autonumber;
            if($temp_arr==$item->trader_autonumber){
              $arr_test[$temp_arr][] = $item->tradename;
              continue;
            }
        }
        
        $arr_trader = [];
        foreach($arr_test as $k1=>$item1){
            $arr_end = end($item1);
            array_pop($item1);
            $html = $arr_end;
            foreach($item1 as $k2=>$item_name){
                $html .= ' ('.$item_name.')';
            }
            $arr_trader[$k1] = $html;
        }
        return $arr_trader;
    }

    static function get_tbl_tradeName($id)
    {
        $id_data = DB::table('tb4_tisilicense')->where('Autono', $id)->pluck('tbl_tisiNo');
        $data = DB::table('tb4_tisilicense')->where('tbl_tisiNo', $id_data)->pluck('tbl_tradeName', 'Autono');
        return $data;
    }

    static function get_address_province()
    {
        $name = DB::table('province')->whereNull('state')->pluck('PROVINCE_NAME', 'PROVINCE_ID');
        return $name;
    }

    static function get_people_found()
    {
        $name = DB::table('user_register')->get();
        return $name;
    }

    static function get_people_found_old($id)
    {
        $name = DB::table('user_register')->where('runrecno', $id)->first();
        return $name;
    }

    static function get_people_found_old_no($id)
    {
        $name = DB::table('user_register')->whereNotIn('runrecno', $id)->get();
        return $name;
    }

    static function get_tb4_name_index($id)
    {
        if ($id != null && $id != 'เลือกมาตรฐาน') {
            $name = DB::table('tb4_tisilicense')->where('Autono', $id)->first(['tbl_tradeName']);
            if ($name != null) {
                return $name->tbl_tradeName;
            }
        }
    }

    static function get_tb4_name_index2($trade_name)
    {
        if ($trade_name != null && $trade_name != 'เลือกมาตรฐาน') {
            $name = DB::table('tb4_tisilicense')->where('tbl_taxpayer', 'LIKE', "%{$trade_name}%")->latest('Autono')->value('tbl_tradeName');
            return $name??'n/a';
        }
    }

    static function get_tb4_tisiNo_index($id)
    {
        if ($id != null && $id != 'เลือกมาตรฐาน') {
            $name = DB::table('tb4_tisilicense')->where('Autono', $id)->first(['tbl_tisiNo']);
            if ($name != null) {
                return $name->tbl_tisiNo;
            }
        }
    }

    static function get_tb4_tisiNo_index2($trade_name)
    {
        if ($trade_name != null && $trade_name != 'เลือกมาตรฐาน') {
            $name = DB::table('tb4_tisilicense')->where('tbl_taxpayer', 'LIKE', "%{$trade_name}%")->value('tbl_tisiNo');
            return $name??'n/a';
        }
    }

    static function get_tb3_tis_index($id)
    {
        if ($id != null && $id != 'เลือกมาตรฐาน') {
            $name = DB::table('tb3_tis')->where('tb3_Tisno', $id)->first(['tb3_TisThainame']);
            if ($name != null) {
                return $name->tb3_TisThainame;
            }
        }
    }

    static function get_tb3_tis_for_select($id)
    {
        if ($id != null && $id != 'เลือกมาตรฐาน') {
            $name = DB::table('tb3_tis')->where('tb3_Tisno', $id)->first(['tb3_Tisno','tb3_TisThainame']);
            if ($name != null) {
                return $name->tb3_Tisno." : ".$name->tb3_TisThainame;
            }
        }
    }

    static function gat_province($id)
    {
        $name = DB::table('province')->where('PROVINCE_ID', $id)->first(['PROVINCE_NAME']);
        if ($name != null) {
            return $name->PROVINCE_NAME;
        }
    }

    static function gat_amphur($id)
    {
        $name = DB::table('amphur')->where('AMPHUR_ID', $id)->first(['AMPHUR_NAME']);
        if ($name != null) {
            return $name->AMPHUR_NAME;
        }
    }

    static function gat_district($id)
    {
        $name = DB::table('district')->where('DISTRICT_ID', $id)->first(['DISTRICT_NAME']);
        if ($name != null) {
            return $name->DISTRICT_NAME;
        }
    }

    static function gat_amphur_default($id)
    {
        $name = DB::table('amphur')->where('PROVINCE_ID', $id)->first(['AMPHUR_NAME']);
        if ($name != null) {
            return $name->AMPHUR_NAME;
        }
    }

    static function gat_district_default($id)
    {
        $data = DB::table('amphur')->where('PROVINCE_ID', $id)->first();
        $name = DB::table('district')->where('AMPHUR_ID', $data->AMPHUR_ID)->first(['DISTRICT_NAME']);
        if ($name != null) {
            return $name->DISTRICT_NAME;
        }
    }

    static function get_license($id, $id2)
    {
        $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $id_data = $q_data->where('tbl_taxpayer', 'LIKE', "%{$id}%")->pluck('tbl_tisiNo');
        $data_q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $data = $data_q->where('tbl_tisiNo', $id2)->where('tbl_tisiNo', $id_data)->get();
        return $data;
    }



    static function get_license2($id, $id2)
    {
        // $q_data = DB::table('tb4_tisilicense')->whereNotNull('tbl_taxpayer')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1')->groupBy('tbl_taxpayer')->having('tbl_taxpayer', '<>', '');
        // $id_data = $q_data->where('tbl_taxpayer', $id)->pluck('tbl_tisiNo');
        // dd($id_data);
        // $data_q = DB::table('tb4_tisilicense')->select('tbl_licenseNo')->whereNotNull('tbl_taxpayer')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1')->groupBy('tbl_taxpayer')->having('tbl_taxpayer', '<>', '');
        $data_q = DB::table('tb4_tisilicense')->select('tbl_licenseNo')->whereNotNull('tbl_taxpayer')->whereNotNull('tbl_tradeName')->where('tbl_licenseStatus', '1');
        $data = $data_q->where('tbl_taxpayer', $id)->where('tbl_tisiNo', $id2)->get();
        // dd($data);
        return $data;
    }
    static function get_license3($id, $id2)
    {
        $data_q = DB::table('tb4_tisilicense')->select('tbl_licenseNo', 'tbl_licenseStatus', 'license_pdf')->whereNotNull('tbl_taxpayer')->whereNotNull('tbl_tradeName');
        $data = $data_q->where('tbl_taxpayer', $id)->where('tbl_tisiNo', $id2)->get();
        return $data;
    }
    static function get_license_check($id, $id2)
    {
        // echo $id." ".$id2."<br>";
        $data = DB::table('control_performance_permission')->where('id_perform', $id)->where('license', trim($id2))->first();
        // var_dump($data);
        return $data;
    }

    static function get_license_check2($id, $id2)
    {
        $data = DB::table('control_check_permission')->where('id_check', $id)->where('license', trim($id2))->first();
        return $data;
    }

    static function get_mog($id)
    {
        $name = DB::table('tb4_tisilicense')->where('Autono', $id)->groupBy('tbl_taxpayer')->first(['tbl_tisiNo']);
        if ($name != null) {
            return $name->tbl_tisiNo;
        }
    }

    static function get_mog2($id,$id2)
    {
        $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $id_data = $q_data->where('tbl_taxpayer', $id)->pluck('tbl_tisiNo');
        $data_q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $data = $data_q->where('tbl_tisiNo', $id2)->where('tbl_tisiNo', $id_data)->get();
        if (count($data) != 0) {
            return $data[0]->tbl_tisiNo;
        }
    }

    static function get_mog2_edited($trade_name,$id2)
    {
        $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $id_data = $q_data->where('tbl_taxpayer', 'LIKE', "%{$trade_name}%")->pluck('tbl_tisiNo');
        $data_q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        // dd($id_data);
        $data = $data_q->where('tbl_tisiNo', $id2)->where('tbl_tisiNo', $id_data)->get();

        if (count($data) != 0) {
            return $data[0]->tbl_tisiNo??'n/a';
        }
    }

    static function get_id_control($name)
    {
        $r_name = DB::table('control_check')->where('check_officer', $name)->pluck('auto_id_doc');
        return $r_name;
    }

    static function check_quality_controls($id)
    {
        $c_data = SSO_User::where('tax_number', $id)->first();
        if ($c_data != null) {
            $c_data = DB::table('esurv_inform_inspections')->where('created_by', $c_data->id)->orderByDesc('check_date')->first();
            if ($c_data != null) {
                return $c_data->check_date;
            }
        }
    }

    static function check_esurv_inform_inspections($id)
    {
        $c_data = SSO_User::where('tax_number', $id)->first();
        if ($c_data != null) {
            $c_data = DB::table('esurv_follow_ups')->where('created_by', $c_data->id)->orderByDesc('check_date')->first();
            if ($c_data != null) {
                return $c_data->check_date;
            }
        }
    }

    static function check_esurv_follow_ups($name)
    {
        $c_name = DB::table('control_check')->where('tradeName', $name)->orderByDesc('created_at')->first();
        if ($c_name != null) {
            return $c_name->created_at;
        }
        return null;
    }

    static function grade_sim($y, $operator_name)
    {
        $get_year = $y - 1;
        $year = DB::table('control_follow')->where('make_annual', $get_year)->first();
        if ($year != null) {
            $c_name = DB::table('control_follow_list_table')->where('operator_name', $operator_name)->where('id_follow', $year->id)->first(['consider_grades']);
            if ($c_name != null) {
                return $c_name->consider_grades;
            }
        }
    }

    static function notification_3($id, $y)
    {
        $get_year = $y - 1;
        $c_data = SSO_User::where('tax_number', $id)->first();
        if ($c_data != null) {
            $c_data = DB::table('esurv_inform_volumes')->where('created_by', $c_data->trader_autonumber)
                ->where('inform_year', $get_year)->first();
            if ($c_data != null) {
                return 'แจ้ง';
            } else {
                return 'ไม่แจ้ง';
            }
        } else {
            return 'ไม่แจ้ง';
        }
    }

    static function status_save_example($key, $val){
        // echo $key; echo $val; exit;
        $arr = [];
        $arr['test_status'] = ['1'=>'ผ่าน','2'=>'ไม่ผ่าน'];
        $arr['status2'] =  ['0' => 'ฉบับร่าง','1' => 'อยู่ระหว่าง ผก.รับรอง','2' => 'ผก.รับรองแล้ว','3' => 'อยู่ระหว่าง ผอ.รับรอง','4' => 'ผอ.รับรองแล้ว','5' => 'ปรับปรุงแก้ไข' ];
        $arr['status3'] = ['1'=>'อยู่ระหว่าง ผก. รับรอง','2'=>'ผก. รับรองแล้ว', '3'=>'ผก. ไม่เห็นด้วย'];
        return $arr[''.$key.''][$val] ?? 'n/a';
    }

    static function system_control_check_3($id)
    {
        $c_data = SSO_User::where('tax_number', $id)->first();
        if ($c_data != null) {
            $c_data = DB::table('esurv_inform_quality_controls')->where('created_by', $c_data->trader_autonumber)->orderByDesc('check_date')->first();
            if ($c_data != null) {
                return $c_data->check_date;
            }
        }
    }

    static function map_lap_number3($id, $id2)
    {
        $c_data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first();
        if ($c_data != null) {
            $data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first(['number']);
            return $data->number;
        }
    }

    static function map_lap_unit3($id, $id2)
    {
        $c_data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first();
        if ($c_data != null) {
            $data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first(['unit']);
            return $data->unit;
        }
    }

    static function map_lap_num_ex3($id, $id2)
    {
        $c_data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first();
        if ($c_data != null) {
            $data = DB::table('save_example_detail')->where('detail_volume', $id)->where('id_example', $id2)->first(['num_ex']);
            return $data->num_ex;
        }
    }

    static function map_lap_sizedetail($id)
    {
        $data = DB::table('tb4_licensesizedetial')->where('autoNo', $id)->first();
        if ($data != null) {
            return $data->sizeDetial;
        }
    }

    static function map_lap_status($id)
    {
        if($id == 1){
            return "นำส่งตัวอย่าง";
        }elseif($id == 2){
            return "อยู่ระหว่างดำเนินการ";
        }elseif($id == 3){
            return "ส่งผลการทดสอบ";
        }elseif($id == 4){
            return "ไม่รับเรื่อง";
        }
        elseif($id == 'ยกเลิก'){
            return "ยกเลิก";
        }elseif($id == '-'){
            return "-";
        }
    }

    static function map_lap_file($id,$noexample_id)
    {
        $data = DB::table('save_example_file')->where('example_id', $noexample_id)->where('example_id_no', $id)->first();
        if ($data != null) {
            return $data->file;
        }
    }

    static function map_lap_test_detail($id,$i,$example_id)
    {
        $data = DB::table('save_example_map_lap_detail')->where('maplap_id', $id)->where('example_id', $example_id)->get();
        if($data != null){
            foreach($data as $datas){
                $str[]  = $datas->test_id;
            }
            if(isset($str)){
                $data2 = DB::table('result_product_detail')->whereIn('id', $str)->get();

                if($data2 != null){
                    $name = '<div class="row">';
                    $j = 0;
                    foreach($data2 as $data2s){
                        $name  .= '<div class="col-md-12"><p align="left">' . $data2s->name_result . '</p>';
                        if($data2s->type_result == 'Text'){
                            $name .= '<input name="type_detail['.$i.'][]" type="text" class="form-control" value="'.$data[$j]->lab_input.'"><br></div>';
                        }elseif($data2s->type_result == 'ตัวเลข'){
                            $name .= '<input name="type_detail['.$i.'][]" type="number" class="form-control" value="'.$data[$j]->lab_input.'"><br></div>';
                        }elseif($data2s->type_result == 'Yes / No'){
                            $name .= '<select name="type_detail['.$i.'][]" class="form-control" >';

                            if($data[$j]->lab_input != null && $data[$j]->lab_input == 'Yes'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>No</option>';
                            }elseif($data[$j]->lab_input != null && $data[$j]->lab_input == 'No'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>Yes</option>';
                            }else{
                                $name .= '<option>Yes</option>';
                                $name .= '<option>No</option>';
                            }
                            $name .= '</select><br>';
                            $name .= '</div>';
                        }
                        $j++;

                    }
                    $name .= '</div>';

                    return $name;

                }
            }
        }
    }

     static function map_lap_test_detail2($id,$i,$example_id)
    {
        $data = DB::table('save_example_map_lap_detail')->where('maplap_id', $id)->where('example_id', $example_id)->get();
        if($data != null){
            foreach($data as $datas){
                $str[]  = $datas->test_id;
            }
            if(isset($str)){
                $data2 = DB::table('result_product_detail')->whereIn('id', $str)->get();

                if($data2 != null){
                    $name = '';
                    $j = 0;
                    foreach($data2 as $data2s){
                        $name  .= '<div class="col-md-12"><span style="display:block; float:left;">' . $data2s->name_result . '</span>';
                        if($data2s->type_result == 'Text'){
                            $name .= '<input name="type_detail['.$i.'][]" type="text" class="form-control input-sm"  value="'.$data[$j]->lab_input.'"></div>';
                        }elseif($data2s->type_result == 'ตัวเลข'){
                            $name .= '<input name="type_detail['.$i.'][]" type="number" class="form-control input-sm" value="'.$data[$j]->lab_input.'"></div>';
                        }elseif($data2s->type_result == 'Yes / No'){
                            $name .= '<select name="type_detail['.$i.'][]" class="form-control input-sm" >';

                            if($data[$j]->lab_input != null && $data[$j]->lab_input == 'Yes'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>No</option>';
                            }elseif($data[$j]->lab_input != null && $data[$j]->lab_input == 'No'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>Yes</option>';
                            }else{
                                $name .= '<option>Yes</option>';
                                $name .= '<option>No</option>';
                            }
                            $name .= '</select>';
                            $name .= '</div>';
                        }
                        $j++;

                    }
                    $name .= '';

                    return $name;

                }
            }
        }
    }

    static function map_lap_test_detail_disable($id,$i,$example_id)
    {
        $data = DB::table('save_example_map_lap_detail')->where('maplap_id', $id)->where('example_id', $example_id)->get();
        if($data != null){
            foreach($data as $datas){
                $str[]  = $datas->test_id;
            }
            if(isset($str)){
                $data2 = DB::table('result_product_detail')->whereIn('id', $str)->get();

                if($data2 != null){

                    $name = '<div class="row">';
                    $j = 0;
                    foreach($data2 as $data2s){
                        $name  .= '<div class="col-md-12"><p align="left">' . $data2s->name_result . '</p>';
                        if($data2s->type_result == 'Text'){
                            $name .= '<input name="type_detail['.$i.'][]" type="text" class="form-control" value="'.$data[$j]->lab_input.'" disabled><br></div>';
                        }elseif($data2s->type_result == 'ตัวเลข'){
                            $name .= '<input name="type_detail['.$i.'][]" type="number" class="form-control" value="'.$data[$j]->lab_input.'" disabled><br></div>';
                        }elseif($data2s->type_result == 'Yes / No'){
                            $name .= '<select name="type_detail['.$i.'][]" class="form-control" disabled>';

                            if($data[$j]->lab_input != null && $data[$j]->lab_input == 'Yes'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>No</option>';
                            }elseif($data[$j]->lab_input != null && $data[$j]->lab_input == 'No'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>Yes</option>';
                            }else{
                                $name .= '<option>Yes</option>';
                                $name .= '<option>No</option>';
                            }
                            $name .= '</select><br>';
                            $name .= '</div>';
                        }
                        $j++;
                    }
                    $name .= '</div>';

                    return $name;
                }
            }
        }
    }

    static function map_lap_test_detail_disable2($id,$i,$example_id)
    {
        $data = DB::table('save_example_map_lap_detail')->where('maplap_id', $id)->where('example_id', $example_id)->get();
        if($data != null){
            foreach($data as $datas){
                $str[]  = $datas->test_id;
            }
            if(isset($str)){
                $data2 = DB::table('result_product_detail')->whereIn('id', $str)->get();

                if($data2 != null){

                    $name = '';
                    $j = 0;
                    foreach($data2 as $data2s){
                        $name  .= '<div class="col-md-12"><span style="display:block; float:left;">' . $data2s->name_result . '</span>';
                        if($data2s->type_result == 'Text'){
                            $name .= '<input name="type_detail['.$i.'][]" type="text" class="form-control input-sm" value="'.$data[$j]->lab_input.'" disabled></div>';
                        }elseif($data2s->type_result == 'ตัวเลข'){
                            $name .= '<input name="type_detail['.$i.'][]" type="number" class="form-control input-sm" value="'.$data[$j]->lab_input.'" disabled></div>';
                        }elseif($data2s->type_result == 'Yes / No'){
                            $name .= '<select name="type_detail['.$i.'][]" class="form-control input-sm" disabled>';

                            if($data[$j]->lab_input != null && $data[$j]->lab_input == 'Yes'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>No</option>';
                            }elseif($data[$j]->lab_input != null && $data[$j]->lab_input == 'No'){
                                $name .= '<option>'.$data[$j]->lab_input.'</option>';
                                $name .= '<option>Yes</option>';
                            }else{
                                $name .= '<option>Yes</option>';
                                $name .= '<option>No</option>';
                            }
                            $name .= '</select>';
                            $name .= '</div>';
                        }
                        $j++;
                    }
                    $name .= '';

                    return $name;
                }
            }
        }
    }

    static function map_lap_detail($id,$example_id)
    {
        $data = App\Models\Ssurv\SaveExampleMapLapDetail::where('maplap_id', $id)->where('example_id', $example_id)->get();

        if( count($data) > 0 ){

            $check = false;
            foreach($data as $datas){

                if( !empty($datas->test_id) ){
                    $str[]  = $datas->test_id;
                }

                if( !empty($datas->test_item_id) ){ //เช็คว่ามี รายการทดสอบไหม
                    $check = true;
                    break;
                }

            }

            if( $check == true ){ //รายการทดสอบ

                $name = '<p align="left">';
                $i = 0;
                $len = count($data);
                foreach($data as $datas){
                    $name  .= !empty($datas->test_item)?($datas->test_item->ItemHtml):'-';
                    // if ($i != $len - 1) {
                    //     $name .= ' | ';
                    // }
                    $i ++;
                }
                $name .= '</p>';

                return $name;

            }else{
                if(isset($str)){
                    $data2 = DB::table('result_product_detail')->whereIn('id', $str)->get();
                    if($data2 != null){

                        $name = '<p align="left">';
                        $i = 0;
                        $len = count($data2);
                        foreach($data2 as $data2s){
                            $name  .= $data2s->name_result;
                            if ($i != $len - 1) {
                                $name .= ' | ';
                            }
                            $i ++;
                        }

                        $name .= '</p>';

                        return $name;
                    }
                }
            }

        }
    }

    static function map_lap_detail2($id,$example_id)
    {
        
    }

    static function LapList($tis)
    {//รายการแล็บ

        $maplap[] = DB::table('ros_rbasicdata_maplab AS a')
            ->select()
            ->where('a.tis_number', $tis)
            ->pluck('a.lab_id');

        if($maplap != null){

            foreach ($maplap as $id) {
                $user[] = DB::table('ros_users as c')->select()
                    ->wherein('c.id', $maplap)
                    ->get();
            }
            return $user;
        }
    }

    //ระบบที่ 4
    static function get_name_4($id)
    {
        $c_name = SSO_User::where('id', $id)->select('name')->first();
        return !is_null($c_name) ? $c_name->name : null ;
    }

    static function get_date_start_4($id)
    {
        $c_name = DB::table('esurv_applicant_20ters')->where('id', $id)->first(['start_date']);
        if ($c_name != null) {
            return $c_name->start_date;
        }
    }

    static function get_date_end_4($id)
    {
        $c_name = DB::table('esurv_applicant_20ters')->where('id', $id)->first(['end_date']);
        if ($c_name != null) {
            return $c_name->end_date;
        }
    }

    static function get_date_start2_4($id)
    {
        $c_name = DB::table('esurv_applicant_20biss')->where('id', $id)->first(['start_date']);
        if ($c_name != null) {
            return $c_name->start_date;
        }
    }

    static function get_date_end2_4($id)
    {
        $c_name = DB::table('esurv_applicant_20biss')->where('id', $id)->first(['end_date']);
        if ($c_name != null) {
            return $c_name->end_date;
        }
    }

    static function get_unit_4($id)
    {
        $c_name = DB::table('tb_unitcode')->where('Auto_num', $id)->first(['name_unit']);
        if ($c_name != null) {
            return $c_name->name_unit;
        }
    }

    static function get_unit_name($id)
    {
        $unit_code = DB::table('tb_unitcode')->where('id_unit', $id)->first(['name_unit']);
        if ($unit_code != null) {
            return $unit_code->name_unit;
        }
    }

    static function get_id_unit($id)
    {
        $detail = DB::table('esurv_applicant_20bis_product_details')->where('id', $id)->first(['id_unit']);
        return $detail->id_unit;
    }

     static function get_unit_for_report_volume($id)
    {
        $unit_name = DB::table('import_unit')->where('id', $id)->first(['acronym']);
        if ($unit_name != null) {
            return $unit_name->acronym;
        }
    }

    static function get_unitcode_for_report_volume($id)
    {
        $unitcode = DB::table('tb_unitcode')->where('Auto_num', $id)->first(['name_unit']);
        if ($unitcode != null) {
            return $unitcode->name_unit;
        }
    }

    static function get_county_4($id)
    {
        $c_name = DB::table('tb_country')->where('id', $id)->first(['title']);
        if ($c_name != null) {
            return $c_name->title;
        }
    }

    static function get_create_4($id)
    {

        $f_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_fname']);
        $l_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_lname']);
        if($l_name == null|| $l_name == null) {
            return '';
        }
        $name = $f_name->reg_fname . ' ' . $l_name->reg_lname;
        return $name;
    }

    static function get_detail_4($id)
    {
        $detail = DB::table('esurv_applicant_20ter_product_details')->where('id', $id)->first(['detail']);
        if ($detail != null) {
            return $detail->detail;
        }
    }

    static function get_quantity_4($id)
    {
        $detail = DB::table('esurv_applicant_20ter_product_details')->where('id', $id)->first(['quantity']);
        return $detail->quantity;
    }

    static function get_unit1_4($id)
    {
        $detail = DB::table('esurv_applicant_20ter_product_details')->where('id', $id)->first(['unit']);
        return $detail->unit;
    }

    static function get_detail2_4($id)
    {
        $detail = DB::table('esurv_applicant_20bis_product_details')->where('id', $id)->first(['detail']);
        return $detail->detail;
    }

    static function get_quantity2_4($id)
    {
        $detail = DB::table('esurv_applicant_20bis_product_details')->where('id', $id)->first(['quantity']);
        return $detail->quantity;
    }

    static function get_unit2_4($id)
    {
        $detail = DB::table('esurv_applicant_20bis_product_details')->where('id', $id)->first(['unit']);
        return $detail->unit;
    }

    static function get_inform_4($id)
    {
        $detail = DB::table('esurv_volume_20ters')->where('applicant_20ter_id', $id)->first(['inform_close']);
        if ($detail != null) {
            return $detail->inform_close;
        }
    }

    static function get_inform2_4($id)
    {
        $detail = DB::table('esurv_volume_20biss')->where('applicant_20bis_id', $id)->first(['inform_close']);
        if ($detail != null) {
            return $detail->inform_close;
        }
    }

    static function get_ref_no1_4($id)
    {
        $detail = DB::table('esurv_applicant_20ters')->where('id', $id)->first(['ref_no']);
        if ($detail != null) {
            return $detail->ref_no;
        }
    }

    static function get_title1_4($id)
    {
        $detail = DB::table('esurv_applicant_20ters')->where('id', $id)->first(['title']);
        if ($detail != null) {
            return $detail->title;
        }
    }

    static function get_ref_no2_4($id)
    {
        $detail = DB::table('esurv_applicant_20biss')->where('id', $id)->first(['ref_no']);
        if ($detail != null) {
            return $detail->ref_no;
        }
    }

    static function get_title2_4($id)
    {
        $detail = DB::table('esurv_applicant_20biss')->where('id', $id)->first(['title']);
        if ($detail != null) {
            return $detail->title;
        }
    }

    static function get_sum_quantity1_4($id_main,$id2)
    {
        // $data_test = EsurvVolumeTers20::query()->where('applicant_20ter_id', $id)->get();
        // $id_main = array();
        // foreach ($data_test as $list) {
        //     $id_main[] = $list->id;
        // }

            $data_volume_detail = DB::table('esurv_volume_20ter_product_details')->where('volume_20ter_id', $id_main)->where('detail_id', $id2)->sum('quantity');
        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }else{
            return null;
        }
    }

    static function get_sum_quantity2_4($id, $id2)
    {
        $data_test = EsurvVolumeBiss20::query()->where('applicant_20bis_id', $id)->get();
        $id_main = array();
        foreach ($data_test as $list) {
            $id_main[] = $list->id;
        }
        $data_volume_detail = DB::table('esurv_volume_20bis_product_details')->whereIn('volume_20bis_id', $id_main)->where('detail_id', $id2)->sum('quantity');
        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }
    }


     //ระบบที่ 5
    static function get_name_5($id)
    {
        $c_name = SSO_User::where('id', $id)->select('name')->first();
        return !is_null($c_name) ? $c_name->name : null ;
    }

    static function get_date_start_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['start_date']);
        if ($c_name != null) {
            return $c_name->start_date;
        }
    }

    static function get_date_end_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['end_date']);
        if ($c_name != null) {
            return $c_name->end_date;
        }
    }

    static function get_date_import_start_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['start_import_date']);
        if ($c_name != null) {
            return $c_name->start_import_date;
        }
    }

    static function get_date_import_end_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['end_import_date']);
        if ($c_name != null) {
            return $c_name->end_import_date;
        }
    }

      static function get_date_export_start_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['start_export_date']);
        if ($c_name != null) {
            return $c_name->start_export_date;
        }
    }

    static function get_date_export_end_5($id)
    {
        $c_name = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['end_export_date']);
        if ($c_name != null) {
            return $c_name->end_export_date;
        }
    }

    static function get_date_start2_5($id)
    {
        $c_name = DB::table('esurv_applicant_21biss')->where('id', $id)->first(['start_date']);
        if ($c_name != null) {
            return $c_name->start_date;
        }
    }

    static function get_date_end2_5($id)
    {
        $c_name = DB::table('esurv_applicant_21biss')->where('id', $id)->first(['end_date']);
        if ($c_name != null) {
            return $c_name->end_date;
        }
    }

    static function get_unit_5($id)
    {
        $c_name = DB::table('tb_unitcode')->where('Auto_num', $id)->first(['name_unit']);
        if ($c_name != null) {
            return $c_name->name_unit;
        }
    }

    static function get_detail_5($id)
    {
        $detail = DB::table('esurv_applicant_21ter_product_details')->where('id', $id)->first(['detail']);
        if ($detail != null) {
            return $detail->detail;
        }
    }

    static function get_quantity_5($id)
    {
        $detail = DB::table('esurv_applicant_21ter_product_details')->where('id', $id)->first(['quantity']);
        return $detail->quantity;
    }

    static function get_quantity_export_5($id)
    {
        $detail = DB::table('esurv_volume_21ter_product_details')->where('id', $id)->first(['quantity_export']);
        return $detail->quantity;
    }

    static function get_unit1_5($id)
    {
        $detail = DB::table('esurv_applicant_21ter_product_details')->where('id', $id)->first(['unit']);
        return $detail->unit;
    }

    static function get_id_unit_app21ter($id)
    {
        $detail = DB::table('esurv_applicant_21ter_product_details')->where('id', $id)->first(['id_unit']);
        return $detail->id_unit;
    }

    static function get_detail2_5($id)
    {
        $detail = DB::table('esurv_applicant_21bis_product_details')->where('id', $id)->first(['detail']);
        return $detail->detail;
    }

    static function get_quantity2_5($id)
    {
        $detail = DB::table('esurv_applicant_21bis_product_details')->where('id', $id)->first(['quantity']);
        return $detail->quantity;
    }

    static function get_unit2_5($id)
    {
        $detail = DB::table('esurv_applicant_21bis_product_details')->where('id', $id)->first(['id_unit']);
        return $detail->id_unit;
    }

    static function get_inform_5($id)
    {
        $detail = DB::table('esurv_volume_21ters')->where('applicant_21ter_id', $id)->first(['inform_close']);
        if ($detail != null) {
            return $detail->inform_close;
        }
    }

    static function get_inform2_1($id)
    {
        $detail = DB::table('esurv_volume_21biss')->where('applicant_21bis_id', $id)->first(['inform_close']);
        if ($detail != null) {
            return $detail->inform_close;
        }
    }

    static function get_ref_no1_5($id)
    {
        $detail = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['ref_no']);
        if ($detail != null) {
            return $detail->ref_no;
        }
    }

    static function get_title1_5($id)
    {
        $detail = DB::table('esurv_applicant_21ters')->where('id', $id)->first(['title']);
        if ($detail != null) {
            return $detail->title;
        }
    }

    static function get_ref_no2_5($id)
    {
        $detail = DB::table('esurv_applicant_21biss')->where('id', $id)->first(['ref_no']);
        if ($detail != null) {
            return $detail->ref_no;
        }
    }

    static function get_title2_5($id)
    {
        $detail = DB::table('esurv_applicant_21biss')->where('id', $id)->first(['title']);
        if ($detail != null) {
            return $detail->title;
        }
    }

    static function get_sum_quantity1_5($id, $id2,$id3 =null)
    {
        $id_main = array();
        if(is_null($id3)){
            // $data_test = EsurvVolumeTers21::where('applicant_21ter_id', $id)->select('id')->get();
            // foreach ($data_test as $list) {
            //     $id_main[] = $list->id;
            // }
            $id_main = EsurvVolumeTers21::where('applicant_21ter_id', $id)->select('id');
        }else{
            $id_main[] = $id3;
        }

        $data_volume_detail = DB::table('esurv_volume_21ter_product_details')
                                ->whereIn('volume_21ter_id', $id_main)
                                ->where('detail_id', $id2)
                                ->sum('quantity');

        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }else{
            return 0;
        }
    }

    static function get_sum_quantity1_export_5($id, $id2, $id3=null)
    {
        $id_main = array();
        if(is_null($id3)){
            // $data_test = EsurvVolumeTers21::query()->where('applicant_21ter_id', $id)->get();
            // foreach ($data_test as $list) {
            //     $id_main[] = $list->id;
            // }
            $id_main = EsurvVolumeTers21::where('applicant_21ter_id', $id)->select('id');
        }else{
            $id_main[] = $id3;
        }

        $data_volume_detail = DB::table('esurv_volume_21ter_product_details')
                                ->whereIn('volume_21ter_id', $id_main)
                                ->where('detail_id', $id2)
                                ->sum('quantity_export');
        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }else{
            return 0;
        }
    }

    static function get_sum_quantity2_5($id, $id2)
    {
        $data_test = EsurvVolumeBiss21::query()->where('applicant_21bis_id', $id)->get();
        $id_main = array();
        foreach ($data_test as $list) {
            $id_main[] = $list->id;
        }
        $data_volume_detail = DB::table('esurv_volume_21bis_product_details')->whereIn('volume_21bis_id', $id_main)->where('detail_id', $id2)->sum('quantity');
        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }
    }

    static function get_ref_no2_6($id)
    {
        $detail = DB::table('esurv_applicant_21owns')->where('id', $id)->first(['ref_no']);
        if ($detail != null) {
            return $detail->ref_no;
        }
    }

    static function get_title2_6($id)
    {
        $detail = DB::table('esurv_applicant_21owns')->where('id', $id)->first(['title']);
        if ($detail != null) {
            return $detail->title;
        }
    }

    static function get_date_start2_6($id)
    {
        $c_name = DB::table('esurv_applicant_21owns')->where('id', $id)->first(['start_date']);
        if ($c_name != null) {
            return $c_name->start_date;
        }
    }

    static function get_date_end2_6($id)
    {
        $c_name = DB::table('esurv_applicant_21owns')->where('id', $id)->first(['end_date']);
        if ($c_name != null) {
            return $c_name->end_date;
        }
    }

    static function get_detail2_6($id)
    {
        $detail = DB::table('esurv_applicant_21own_product_details')->where('id', $id)->first(['detail']);
        return $detail->detail;
    }

    static function get_quantity2_6($id)
    {
        $detail = DB::table('esurv_applicant_21own_product_details')->where('id', $id)->first(['quantity']);
        return $detail->quantity;
    }

    static function get_unit2_6($id)
    {
        $detail = DB::table('esurv_applicant_21own_product_details')->where('id', $id)->first(['id_unit']);
        return $detail->id_unit;
    }


    static function get_sum_quantity2_6($id, $id2)
    {
        $data_test = EsurvVolumeOwns21::query()->where('applicant_21own_id', $id)->get();
        $id_main = array();
        foreach ($data_test as $list) {
            $id_main[] = $list->id;
        }
        $data_volume_detail = DB::table('esurv_volume_21own_product_details')->whereIn('volume_21own_id', $id_main)->where('detail_id', $id2)->sum('quantity');
        if ($data_volume_detail != null) {
            return $data_volume_detail;
        }
    }

    static function get_different_no_4($id)
    {
        $data_test = DB::table('tb3_tis')->where('tb3_TisAutono', $id)->first(['tb3_Tisno']);
        if ($data_test != null) {
            return $data_test->tb3_Tisno;
        }
    }

    static function get_check_box_permiss_control_per_4($id, $id2)
    {
        $data = DB::table('control_performance_permission')->where('license', $id)->where('id_perform', $id2)->first();
        if ($data != null) {
            return $data->license;
        }
    }

    static function get_different_no_5($id)
    {
        $data_test = DB::table('tb3_tis')->where('tb3_TisAutono', $id)->first(['tb3_Tisno']);
        if ($data_test != null) {
            return $data_test->tb3_Tisno;
        }
    }

    static function get_check_box_permiss_control_per_5($id, $id2)
    {
        $data = DB::table('control_performance_permission')->where('license', $id)->where('id_perform', $id2)->first();
        if ($data != null) {
            return $data->license;
        }
    }

    static function get_create_5($id)
    {
        $f_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_fname']);
        $l_name = DB::table('user_register')->where('runrecno', $id)->first(['reg_lname']);
        $name = $f_name->reg_fname . ' ' . $l_name->reg_lname;
        return $name;
    }

    //เดือน และ ปี
    static public function month_years(){

      $year_now = date('Y');
      $months = self::MonthList();

      $results = [];
      for($year = 2018; $year <= $year_now; $year++){

        foreach($months as $key => $month){

          $results[$key.'-'.$year] = $month.' '.($year+543);

          if($year==date('Y') && $key==date('m')){
            break;
          }

        }

      }

      return $results;

    }

    //ตรวจสอบกลุ่มเมนูว่ามีเปิดใช้งานเมนุข้างไหนบ้างหรือไม่
    static public function check_group_menu($laravelAdminMenus){

        $result = false;
        if( auth()->check() ){
            foreach($laravelAdminMenus->menus as $section){
                if(count(collect($section->items)) > 0){
                    foreach($section->items as $menu){
                        if( isset($menu->sub_menus) ){
                            if( isset($menu->title) ){
                                foreach($menu->sub_menus as $sub_menus){
                                    if(isset($sub_menus->title) && auth()->user()->can('view-'.str_slug($sub_menus->title))){
                                        $result = true;
                                        break;
                                    }
                                }
                            }
                        }else{
                            if( isset($menu->title) ){
                                if(auth()->user()->can('view-'.str_slug($menu->title))){
                                    $result = true;
                                    break;
                                }
                            }
                        }

                    }
                }
            }
        }

        return $result;
    }

    static public function CheckMenuItem($laravelMenus)
    {
        $result = false;
        if( auth()->check() ){
            foreach($laravelMenus as $menu){
                if( isset($menu->sub_menus) ){
                    if( isset($menu->title) ){
                        foreach($menu->sub_menus as $sub_menus){
                            if(  isset($sub_menus->title) && auth()->user()->can('view-'.str_slug($sub_menus->title))){
                                $result = true;
                                break;
                            }
                        }
                    }
                }else if( !isset($menu->sub_menus) &&  isset($menu->title) ){
                    if(auth()->user()->can('view-'.str_slug($menu->title))){
                        $result = true;
                        break;
                    }
                }
                else if( !isset($menu->sub_menus) && !isset($menu->title) ){
                    $result = true;
                    break;
                }
            }
        }
        return $result;

    }

    static public function CheckRoleMenuItem($laravelMenus, $permissions_list)
    {
        $result = false;

        if( is_array($laravelMenus) ){
            foreach($laravelMenus as $menu){
                if( isset($menu->sub_menus) ){
                    if( isset($menu->title) ){
                        foreach($menu->sub_menus as $sub_menus){
                            if(  isset($sub_menus->title) && array_key_exists( str_slug($sub_menus->title) , $permissions_list ) ){
                                $result = true;
                                break;
                            }
                        }
                    }
                }else if( !isset($menu->sub_menus) &&  isset($menu->title) ){
                    if( array_key_exists( str_slug($menu->title) , $permissions_list ) ){
                        $result = true;
                        break;
                    }
                }
                else if( !isset($menu->sub_menus) && !isset($menu->title) ){
                    $result = true;
                    break;
                }
            }
        }else{
            if( isset($laravelMenus->title) ){
                if( array_key_exists( str_slug($laravelMenus->title) , $permissions_list ) ){
                    $result = true;
                }
            }
        }

        return $result;

    }

    static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    //เช็คค่า reCaptcha
    static function check_recaptcha_google($google_recaptcha_response){

        $config = HP::getConfig();

        $recaptcha_secret = $config->recaptcha_secret_key;
        $recaptcha_response = $google_recaptcha_response;
        $recaptcha_remote_ip = $_SERVER['REMOTE_ADDR'];

        $recaptcha_api = "https://www.google.com/recaptcha/api/siteverify?".
            http_build_query(array(
                'secret'=>$recaptcha_secret,
                'response'=>$recaptcha_response,
                'remoteip'=>$recaptcha_remote_ip
            )
        );

        $response = json_decode(file_get_contents($recaptcha_api), true);
        return $response;
    }

   //แปลงเลขที่ใบอนุญาตเป็นชื่อไฟล์
   static function ConvertLicenseNoToFileName($license_no){
    $license_no = str_replace('(', '', $license_no);
    $license_no = str_replace(')', '_', $license_no);
    $license_no = str_replace(' ', '', $license_no);
    $license_no = str_replace('-', '_', $license_no);
    $license_no = str_replace('/', '_', $license_no);

    return $license_no;
}

static function ConvertCertifyFileName($name){
    $name = str_replace('#', '', $name);
    $name = str_replace('/', '', $name);
    return $name;
}



    public static function getSetAttachName($set_attach_id)
        {
            $set_attach = SetAttach::Where('id',$set_attach_id)->select('title')->first();

            return $set_attach->title??'ไม่มีชื่อไฟล์แนบ';
        }

    static function applicant_types(){
        $applicant_types = [
                            '1' => 'นิติบุคคล',
                            '2' => 'บุคคลธรรมดา',
                            '3' => 'คณะบุคคล',
                            '4' => 'ส่วนราชการ',
                            '5' => 'อื่นๆ'
                           ];
        return $applicant_types;
    }

    static function company_prefixs(){
        $company_prefixs = [
                            '1' => 'บริษัทจำกัด',
                            '2' => 'บริษัทมหาชนจำกัด',
                            '3' => 'ห้างหุ้นส่วนจำกัด',
                            '4' => 'ห้างหุ้นส่วนสามัญนิติบุคคล',
                           ];
        return $company_prefixs;
    }
    static function person_prefixs(){
        return Prefix::where('state', 1)->pluck('initial', 'id');
    }
    static function getZipcode($sub = '',$dis = '',$pro = '')
    {
        $result = '';
        $address_data  =  DB::table((new Subdistrict)->getTable().' AS sub') // อำเภอ
                                    ->leftJoin((new Amphur)->getTable().' AS amp', 'amp.AMPHUR_ID', '=', 'sub.AMPHUR_ID') // ตำบล
                                    ->leftJoin((new Province)->getTable().' AS pro', 'pro.PROVINCE_ID', '=', 'sub.PROVINCE_ID')  // จังหวัด
                                    ->leftJoin((new Zipcode)->getTable().' AS code', 'code.district_code', '=', 'sub.DISTRICT_CODE')  // รหัสไปรษณีย์
                                    ->where(DB::raw("REPLACE(sub.DISTRICT_NAME,' ','')"),  '=',  str_replace(' ', '', $sub ))
                                    ->where(DB::raw("REPLACE(amp.AMPHUR_NAME,' ','')"),  '=',   str_replace(' ', '', $dis ))
                                    ->where(DB::raw("REPLACE(pro.PROVINCE_NAME,' ','')"),  '=',  str_replace(' ', '', $pro ))
                                    ->selectRaw('code.zipcode AS zipcode')
                                    ->first();

        if(!is_null($address_data)){
            $result =  $address_data->zipcode ?? null;
        }

        return $result;
    }
    static function formatDateThai($strDate) {

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

    static function replace_multi_space($string){//ลบช่องว่างที่อยู่ติดกันให้เหลือเว้นแค่ 1 ช่อง

        replace_space:
        $string = str_replace('  ', ' ', trim($string));
        if(mb_strpos($string, '  ')!==false){//ยังมีการช่องว่างมากกว่า 1 ช่องติดกัน
            goto replace_space;
        }

        return $string;

    }

    //จัดข้อมูลที่อยู่จาก API นิติบุคคลจาก DBD
    //$address=Object ที่อยู่
    static function format_address_company_api($address)
    {

        $FullAddress = $address->FullAddress;

        $address_no = $building = $floor = $room_no = $village_name = $moo = $soi = $road = null;

        //ค้นหาคู่วงเล็บได้เป็นชุด array
        $brackets = self::search_brackets($FullAddress);

        //ค้นหาอาคาร
        $index_building = mb_strpos($FullAddress, 'อาคาร');

        //ค้นหาชั้นที่
        $index_floor = mb_strpos($FullAddress, 'ชั้นที่');

        //ค้นหาห้องเลขที่
        $index_room_no = mb_strpos($FullAddress, 'ห้องเลขที่');

        //ค้นหาหมู่บ้าน
        $index_village_name = mb_strpos($FullAddress, 'หมู่บ้าน');

        //ค้นหาหมู่
        $index_moo = mb_strpos($FullAddress, 'หมู่ที่');

        //ค้นหาซอย
        $index_soi = mb_strpos($FullAddress, 'ซอย');
        $index_soi = self::check_between($index_soi, $brackets)===false ? $index_soi : false ; //ถ้าซอยอยู่ในวงเล็บถือว่าไม่มี
        
        //ค้นหาถนน
        $index_road = mb_strpos($FullAddress, 'ถนน');
        $index_road = self::check_between($index_road, $brackets)===false ? $index_road : false ; //ถ้าถนนอยู่ในวงเล็บถือว่าไม่มี

        //หาเลขที่
        $address_no = self::cut_string($FullAddress, 0, [$index_moo, $index_soi, $index_road]);

        //หาซอยที่ไม่มีคำว่าซอยนำหน้า
        if(!empty($address->Soi)){
            $index_soi_name = mb_strpos($address_no, $address->Soi);
            if($index_soi===false && $index_soi_name!==false){
                if($index_building!==false && ($index_soi_name-($index_building+5) <= 1)){//ถ้ามีอาคารและตำแหน่งใกล้กับซอย ให้ไปค้นคำถัดไป
                    $index_soi_name = mb_strpos($address_no, $address->Soi, ($index_soi_name+1));
                }
                if($index_village_name!==false && ($index_soi_name-($index_village_name+7) <= 1)){//ถ้ามีหมู่บ้านและตำแหน่งใกล้กับซอย ให้ไปค้นคำถัดไป
                    $index_soi_name = mb_strpos($address_no, $address->Soi, ($index_soi_name+1));
                }
                $address_no = self::cut_string($address_no, 0, [$index_soi_name]);
            }
        }

        //หาถนนที่ไม่มีคำว่าถนนนำหน้า
        if(!empty($address->Road)){
            $index_road_name = mb_strpos($address_no, $address->Road);
            if($index_road===false && $index_road_name!==false){
                if($index_building!==false && ($index_road_name-($index_building+5) <= 1)){//ถ้ามีอาคารและตำแหน่งใกล้กับซอย ให้ไปค้นคำถัดไป
                    $index_road_name = mb_strpos($address_no, $address->Road, ($index_road_name+1));
                }
                if($index_village_name!==false && ($index_road_name-($index_village_name+7) <= 1)){//ถ้ามีหมู่บ้านและตำแหน่งใกล้กับซอย ให้ไปค้นคำถัดไป
                    $index_road_name = mb_strpos($address_no, $address->Road, ($index_road_name+1));
                }
                $address_no = self::cut_string($address_no, 0, [$index_road_name]);
            }
        }

        //หาชื่ออาคาร
        if($index_building!==false){
            $building = self::cut_string($FullAddress, $index_building, [$index_floor, $index_room_no, $index_village_name, $index_moo, $index_soi, $index_road]);
        }

        //หาชื่อชั้น
        if($index_floor!==false){
            $floor = self::cut_string($FullAddress, $index_floor, [$index_room_no, $index_village_name, $index_moo, $index_soi, $index_road]);
        }

        //หาชื่อห้อง
        if($index_room_no!==false){
            $room_no = self::cut_string($FullAddress, $index_room_no, [$index_village_name, $index_moo, $index_soi, $index_road]);
        }

        //หาชื่อหมู่บ้าน
        if($index_village_name!==false){
            $village_name = self::cut_string($FullAddress, $index_village_name, [$index_moo, $index_soi, $index_road]);
        }

        //หาชื่อหมู่ที่
        if($index_moo!==false){
            $moo = self::cut_string($FullAddress, $index_moo, [$index_soi, $index_road]);
        }

        //หาชื่อซอย
        if($index_soi!==false){
            $soi = self::cut_string($FullAddress, $index_soi, [$index_road]);
        }

        //หาชื่อถนน
        if($index_road!==false){
            $road = self::cut_string($FullAddress, $index_road, [mb_strlen($FullAddress)]);
        }

        if((is_null($address->AddressNo) && !is_null($address_no)) || (strlen(trim($address->AddressNo)) > strlen(trim($address_no)) && !is_null($address_no))){//ถ้าเลขที่ในข้อมูลย่อยไม่มีให้เอาไปใส่แทน หรือข้อมูลย่อยยาวกว่า
            $address->AddressNo = self::replace_multi_space($address_no);
        }

        if(is_null($address->Building) && !is_null($building)){//ถ้าหมู่ที่ในข้อมูลย่อยไม่มีให้เอาไปใส่แทน
            $address->Building = self::replace_multi_space(mb_substr($building, mb_strlen('อาคาร')));
        }elseif(!is_null($address->Building)){
            $address->Building = trim($address->Building);
            $address->Building = !empty($address->Building) && mb_strpos($address->Building, 'อาคาร')===0 ? trim(mb_substr($address->Building, 5)) : $address->Building ; //ตัดคำว่าอาคาร คำแรกออก
        }

        if(is_null($address->Moo) && !is_null($moo)){//ถ้าหมู่ที่ในข้อมูลย่อยไม่มีให้เอาไปใส่แทน
            $address->Moo = self::replace_multi_space(mb_substr($moo, mb_strlen('หมู่ที่')));
        }elseif(!is_null($address->Moo)){
            $address->Moo = trim($address->Moo);
            $address->Moo = !empty($address->Moo) && mb_strpos($address->Moo, 'หมู่ที่')===0 ? trim(mb_substr($address->Moo, 7)) : $address->Moo ; //ตัดคำว่าหมู่ที่ คำแรกออก
            $address->Moo = !empty($address->Moo) && mb_strpos($address->Moo, 'ซอย')!==false ? trim(mb_substr($address->Moo, 0, mb_strpos($address->Moo, 'ซอย'))) : $address->Moo ; //ตัดซอยออกถ้ามีรวมอยู่ด้วย
        }

        if(is_null($address->Soi) && !is_null($soi)){//ถ้าซอยในข้อมูลย่อยไม่มีให้เอาไปใส่แทน
            $address->Soi = self::replace_multi_space(mb_substr($soi, mb_strlen('ซอย')));
        }elseif(!is_null($address->Soi)){
            $address->Soi = trim($address->Soi);
            $address->Soi = !empty($address->Soi) && mb_strpos($address->Soi, 'ซอย')===0 ? trim(mb_substr($address->Soi, 3)) : $address->Soi ; //ตัดคำว่าซอย คำแรกออก
        }

        if(is_null($address->Road) && !is_null($road)){//ถ้าถนนในข้อมูลย่อยไม่มีให้เอาไปใส่แทน
            $address->Road = self::replace_multi_space(mb_substr($road, mb_strlen('ถนน')));
        }elseif(!is_null($address->Road)){
            $address->Road = trim($address->Road);
            $address->Road = !empty($address->Road) && mb_strpos($address->Road, 'ถนน')===0 ? trim(mb_substr($address->Road, 3)) : $address->Road ; //ตัดคำว่าถนน คำแรกออก
        }

        $address->Tumbol = trim($address->Tumbol);
        $address->Tumbol = !empty($address->Tumbol) && (mb_strpos($address->Tumbol, 'แขวง')===0 || mb_strpos($address->Tumbol, 'ตำบล')===0) ? trim(mb_substr($address->Tumbol, 4)) : $address->Tumbol ; //ตัดคำว่าตำบล/แขวง คำแรกออก

        $address->Ampur = trim($address->Ampur);
        $address->Ampur = !empty($address->Ampur) && mb_strpos($address->Ampur, 'อำเภอ')===0 ? trim(mb_substr($address->Ampur, 5)) : $address->Ampur ; //ตัดคำว่าอำเภอ คำแรกออก
        $address->Ampur = !empty($address->Ampur) && mb_strpos($address->Ampur, 'เขต')===0 ? trim(mb_substr($address->Ampur, 3)) : $address->Ampur ; //ตัดคำว่าเขต คำแรกออก

        //เปลี่ยนข้อมูลเลขที่ใหม่
        $address->AddressNo = self::replace_multi_space($address_no);

        return $address;
    }

    static function cut_string($FullAddress, $index_source, $index_compares){

        sort($index_compares);

        $result = null;

        foreach ($index_compares as $key => $index_compare) {
            if($index_compare!==false){//ถ้าพบข้อมูล
                $result = mb_substr($FullAddress, $index_source, $index_compare-$index_source);
                break;
            }
        }

        if(is_null($result)){//ถ้าเป็น null แสดงว่าเป็นคำสุดท้ายของ FullAddress
            $result = mb_substr($FullAddress, $index_source);
        }

        return $result;

    }

    //หาตำแหน่งคู่วงเล็บในข้อความ ไม่รองรับวงเล็บที่ซ้อนกัน
    static function search_brackets($input){

        //เก็บตำแหน่งวงเล็บเปิดทั้งหมด
        $needle = "(";
        $lastPos = 0;
        $opens = array();

        while (($lastPos = mb_strpos($input, $needle, $lastPos))!== false) {
            $opens[] = $lastPos;
            $lastPos = $lastPos + mb_strlen($needle);
        }

        //เก็บตำแหน่งวงเล็บปิดทั้งหมด
        $needle = ")";
        $lastPos = 0;
        $closes = array();

        while (($lastPos = mb_strpos($input, $needle, $lastPos))!== false) {
            $closes[] = $lastPos;
            $lastPos  = $lastPos + mb_strlen($needle);
        }

        $results = [];
        foreach ($opens as $key => $open) {
            $results[] = (object)['open' => $open, 'close' => $closes[$key]];
        }

        return $results;

    }

    //เช็ค $index ว่ามีค่าอยู่ระหว่าง $brackets หรือไม่
    static function check_between($index, $brackets){
        $result = false;
        foreach ($brackets as $bracket) {
            if($index > $bracket->open && $index < $bracket->close){
                $result = true;
                break;
            }
        }
        return $result;
    }

    public static function StateEstandardOffers(){
        $request = [
                            '1' => 'เสนอความเห็น',
                            '2' => 'สมควรบรรจุในแผน',
                            '3' => 'ไม่สมควรบรรจุในแผน',
                            '4' => 'จัดทำแผน',
                           ];
        return $request;
    }
    public static function StandardTypes(){
        $request =  [
            '1' => 'มอก.',
            '2' => 'มอก.เอส',
            '3' => 'มตช.',
            '4' => 'มตช./ข้อกำหนดเผยแพร่',
            '5' => 'ข้อตกลงร่วม',
            '6' => 'มผช.'
        ];
        return $request;
    }

    public static function singleFileUpload($request_file, $attach_path = '', $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null){

        $attach             = $request_file;
        $file_size          = (method_exists($attach, 'getSize')) ? $attach->getSize() : 0;
        $file_extension     = $attach->getClientOriginalExtension();
        $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;

        $path               = Storage::putFileAs($attach_path.'/'.$tax_number, $attach,  str_replace(" ","",$fullFileName) );
        $file_name          = self::ConvertCertifyFileName($attach->getClientOriginalName());

       $request =  AttachFile::create([
                            'tax_number'        => $tax_number,
                            'username'          => $username,
                            'systems'           => $systems,
                            'ref_table'         => $table_name,
                            'ref_id'            => $ref_id,
                            'url'               => $path,
                            'filename'          => $file_name,
                            'new_filename'      => $fullFileName,
                            'caption'           => $attach_text,
                            'size'              => $file_size,
                            'file_properties'   => $file_extension,
                            'section'           => $section,
                            'created_by'        => auth()->user()->getKey(),
                            'created_at'        => date('Y-m-d H:i:s')
                          ]);
        return $request;
       }

       public static function singleFileUploadRefno($request_file, $attach_path = '', $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null){
        // dd($request_file,$attach_path,$tax_number,$username,$systems,$table_name,$ref_id,$section,$attach_text);
        $attach             = $request_file;
        $file_size          = (method_exists($attach, 'getSize')) ? $attach->getSize() : 0;
        $file_extension     = $attach->getClientOriginalExtension();
        $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;

        $path               = Storage::putFileAs($attach_path, $attach,  str_replace(" ","",$fullFileName) );
        $file_name          = self::ConvertCertifyFileName($attach->getClientOriginalName());

       $request =  AttachFile::create([
                            'tax_number'        => $tax_number,
                            'username'          => $username,
                            'systems'           => $systems,
                            'ref_table'         => $table_name,
                            'ref_id'            => $ref_id,
                            'url'               => $path,
                            'filename'          => $file_name,
                            'new_filename'      => $fullFileName,
                            'caption'           => $attach_text,
                            'size'              => $file_size,
                            'file_properties'   => $file_extension,
                            'section'           => $section,
                            'created_by' => auth()->check() ? auth()->user()->getKey() : 448,

                            'created_at'        => date('Y-m-d H:i:s')
                          ]);
        return $request;
       }

    public static function singleFileUpdate($request_file, $id, $attach_path = '', $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null){

        try {

            $attach_files =  AttachFile::findOrFail($id);
            if(!empty($attach_files)){
                self::deleteFileStorage($attach_files->url);

                $attach             = $request_file;
                $file_size          = (method_exists($attach, 'getSize')) ? $attach->getSize() : 0;
                $file_extension     = $attach->getClientOriginalExtension();
                $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;

                $path               = Storage::putFileAs($attach_path.'/'.$tax_number, $attach,  str_replace(" ","",$fullFileName) );
                $file_name          = self::ConvertCertifyFileName($attach->getClientOriginalName());

                $attach_files->update([
                                    'tax_number'        => $tax_number,
                                    'username'          => $username,
                                    'systems'           => $systems,
                                    'ref_table'         => $table_name,
                                    'ref_id'            => $ref_id,
                                    'url'               => $path,
                                    'filename'          => $file_name,
                                    'new_filename'      => $fullFileName,
                                    'caption'           => $attach_text,
                                    'size'              => $file_size,
                                    'file_properties'   => $file_extension,
                                    'section'           => $section,
                                    'updated_by'        => auth()->user()->getKey(),
                                    'updated_at'        => date('Y-m-d H:i:s')
                                    ]);
                return $attach_files;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public static function deleteFileStorage($file_path){
        if(!empty($file_path)){
            if(Storage::exists($file_path)){
                Storage::delete($file_path);
            }
            if(Storage::disk('uploads')->exists($file_path)){
                Storage::disk('uploads')->delete($file_path);
            }
        }
    }

    public static function singleLabCancalFileUpload($request_file, $attach_path = '', $app_certi, $file_name = null){
        $attach             = $request_file;
        $file_extension     = $attach->getClientOriginalExtension();
        $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension;
        $new_file_name      = str_replace(" ","", $fullFileName);
        $app_no             = (!empty($app_certi->app_no)?$app_certi->app_no:'APP0000');
        $app_no             = str_replace("RQ-","", $app_no);
        $app_no             = str_replace("-","_", $app_no);
        $path_save          = $app_no.'/'.$new_file_name;

        $path               = Storage::putFileAs($attach_path.$app_no, $attach, $new_file_name);

        $request            =  CertiLabDeleteFile::create([
                                                            'app_certi_lab_id'  => @$app_certi->id,
                                                            'name'              => $file_name,
                                                            'path'              => $path_save,
                                                            'created_by'        => auth()->user()->getKey(),
                                                            'created_at'        => date('Y-m-d H:i:s')
                                                        ]);
        return $request;
    }

    public static function singleFileUploadlaw($request_file, $attach_path = '', $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null, $setting_file_id = null, $gen_taxid = false){

        $attach             = $request_file;
        $file_size          = (method_exists($attach, 'getSize')) ? $attach->getSize() : 0;
        $file_extension     = $attach->getClientOriginalExtension();
        $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;
        
        $path_full          = $attach_path.($gen_taxid ?'/'.$tax_number:'');
        $path               = Storage::putFileAs( $path_full, $attach,  str_replace(" ","",$fullFileName) );
        $file_name          = self::ConvertCertifyFileName($attach->getClientOriginalName());

       $request =  AttachFileLaw::create([
                            'tax_number'        => $tax_number,
                            'username'          => $username,
                            'systems'           => $systems,
                            'ref_table'         => $table_name,
                            'ref_id'            => $ref_id,
                            'url'               => $path,
                            'filename'          => $file_name,
                            'new_filename'      => $fullFileName,
                            'caption'           => $attach_text,
                            'size'              => $file_size,
                            'file_properties'   => $file_extension,
                            'section'           => $section,
                            'setting_file_id'   => $setting_file_id,
                            'created_by'        => !empty(auth()->id())?auth()->id():'0',
                            'created_at'        => date('Y-m-d H:i:s')
                          ]);
        return $request;
    }

    public static function CopyFile( $old_path_file, $new_path_file, $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null, $setting_file_id = null    ){

        if( !empty($old_path_file) &&  Storage::exists("/".$old_path_file)){

            $cut = explode("/", $old_path_file );
            if(count($cut) != 0){


                $file_size                    = Storage::size($old_path_file);
                $file_name                    = end($cut);
                $file_extension               = pathinfo( $file_name , PATHINFO_EXTENSION );

                $fullFileName = str_random(10).'-date_time'.date('Ymd_hms');

                $path = $new_path_file.'/'.$fullFileName.'.'.$file_extension;

                if( $old_path_file != $path){

                    if(Storage::exists("/".$path)){
                        Storage::delete("/".$path);
                    }

                    if(!Storage::exists("/".$path)){
                        Storage::copy($old_path_file, $path );
                    }

                    AttachFileLaw::create([
                        'tax_number'        => $tax_number,
                        'username'          => $username,
                        'systems'           => $systems,
                        'ref_table'         => $table_name,
                        'ref_id'            => $ref_id,
                        'url'               => $path,
                        'filename'          => $file_name,
                        'new_filename'      => $fullFileName,
                        'caption'           => $attach_text,
                        'size'              => $file_size,
                        'file_properties'   => $file_extension,
                        'section'           => $section,
                        'setting_file_id'   => $setting_file_id,
                        'created_by'        => auth()->user()->getKey(),
                        'created_at'        => date('Y-m-d H:i:s')
                    ]);

                }
            }
        }
    }

    public static function buttonAction($id, $action_url, $controller_action, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true)
    {
        $form_action = '';
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= '<a href="' . url('/' . $action_url . '/' . $id) . '"
                                                title="View ' . substr($str_slug_name, 0, -1) . '" class="btn btn-info btn-xs">
                                                        <i class="fa fa-eye"></i>
                                                </a>';
        endif;
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/edit') . '"
                                                title="Edit ' . substr($str_slug_name, 0, -1) . '" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                </a>';
        endif;
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-' . str_slug($str_slug_name)) && $show_delete === true):
            $form_action .= '<form action="' . action($controller_action, ['id' => $id]) . '" method="POST" style="display:inline">
                                                ' . csrf_field() . method_field('DELETE') . '
                                                <button type="submit" class="btn btn-danger btn-xs" title="Delete ' . substr($str_slug_name, 0, -1) . '" onclick="return confirm_delete()"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                </form>';
        endif;
        return $form_action;
    }

    public static function buttonActionLaw($id, $action_url, $controller_action, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true,  $show_word = true)
    {
        $form_action = '';

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id) . '" title="ดูรายละเอียด ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-info"><i class="fa fa-info-circle"  style="font-size: 1.5em;"></i></a>';
            
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/edit') . '"title="แก้ไข ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-warning"><i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i></a>';

        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-' . str_slug($str_slug_name)) && $show_delete === true):
            $form_action .= '<form action="' . action($controller_action, ['id' => $id]) . '" method="POST" style="display:inline" id="form_law_delete">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-circle btn-light-danger" title="ลบ' . substr($str_slug_name, 0, -1) . '" onclick="return law_confirm_delete()"><i class="fa fa-trash-o" style="font-size: 1.5em;" aria-hidden="true"></i></button>
                            </form>';
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('word-' . str_slug($str_slug_name)) && $show_word === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/export-word/' . $id) . '" title="Word ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-primary"><i class="fa fa-file-word-o" aria-hidden="true"style="font-size: 1.5em;"></i></a>';
            
        endif;

        return $form_action;
    }

    public static function buttonActionLawCasesform($id, $action_url, $controller_action, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true,  $show_word = true, $status_id )
    {
        $form_action = '';

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id) . '" title="ดูรายละเอียด ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-info"><i class="fa fa-info-circle"  style="font-size: 1.5em;"></i></a>';
            
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            if(!in_array($status_id,[0,3])):
                // $form_action .= ' <a disabled href="javascript: void(0)" title="แก้ไข ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-warning"><i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i></a>';
            else:
                $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/edit') . '" title="แก้ไข ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-warning"><i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i></a>';
            endif;
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-' . str_slug($str_slug_name)) && $show_delete === true):
            if(!in_array($status_id,[0,3])):
            // $form_action .= ' <button disabled type="button" class="btn btn-icon btn-circle btn-light-danger" data-id="'.$id.'" title="ยกเลิก"' . substr($str_slug_name, 0, -1) . '" onclick="return law_cases_delete('.$id.')"><i class="fa fa-close" style="font-size: 1.5em;" aria-hidden="true"></i></button>';
                        else:
            $form_action .= '<form action="' . action($controller_action, ['id' => $id]) . '" method="POST" style="display:inline" id="form_law_delete">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-circle btn-light-danger" data-id="'.$id.'" title="ยกเลิก ' . substr($str_slug_name, 0, -1) . '" onclick="return law_cases_delete('.$id.')"><i class="fa fa-close" style="font-size: 1.5em;" aria-hidden="true"></i></button>
                            </form>';
            endif;
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            if(!in_array($status_id,[0,15,99])){
                $form_action .= ' <span class="btn btn-icon btn-circle btn-light-info attachments" data-id="'.$id.'" ><i class="fa fa-file-o"  style="font-size: 1em;"></i></span>';
            }
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('word-' . str_slug($str_slug_name)) && $show_word === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/export-word/' . $id) . '" title="Word ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-primary"><i class="fa fa-file-word-o" aria-hidden="true"style="font-size: 1.5em;"></i></a>';
            
        endif;

        return $form_action;
    }

    public static function RangeData($start, $end){
        $range =  range( $start , $end );

        $data = [];
        foreach( $range as $list){
            $data[ $list ] = $list;
        }
        return $data;


    }

    public  static function SendCertificateStatus()
    {
       $data = [
                '99'=> 'ร่าง',
                '1'=> 'อยู่ระหว่าง',
                '2'=> 'ลงนามเรียบร้อย',
                '3'=> 'ไม่อนุมัติการลงนาม'
               ];
      return $data;
    }
    public static function countString($data){
        $notCount =[ "ั", "็", "ิ", "่", "ุ", "ู", "ึ", "ี", "ื", "้", "๊", "๋", "ํ", "ำ", "์"];

        $num = 0;
        $datas = self::getMBStrSplit($data);//exit;
        foreach($datas as $value){
            if(in_array($value,$notCount)){
                continue;
            }
            $num++;
        }
        return $num;

    }

    // Convert a string to an array with multibyte string
    public static function getMBStrSplit($string, $split_length = 1){
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');

        $split_length = ($split_length <= 0) ? 1 : $split_length;
        $mb_strlen = mb_strlen($string, 'utf-8');
        $array = array();
        $i = 0;

        while($i < $mb_strlen)
        {
            $array[] = mb_substr($string, $i, $split_length);
            $i = $i+$split_length;
        }

        return $array;
    }

    static function ApplicationStatusIBCB(){

        return App\Models\Section5\ApplicationIbcbStatus::pluck('title', 'id')->toArray();

    }

    static function ApplicationSytemConfig(){
        return [

            'ระบบมาตรตรา 5' => [
                'APP-IB-CB'      => 'ระบบคำขอเป็น IB/CB',
                'APP-LAB'        => 'ระบบคำขอเป็นผู้ตรวจสอบ (LAB)',
                'APP-Inspectors' => 'ระบบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมิน',
                'IB-CB'          => 'หน่วยตรวจสอบ IB/CB',
                'Inspectors'     => 'ผู้ตรวจ/ผู้ประเมิน (IB)',
                'LAB'            => 'หน่วยตรวจสอบ LAB',
            ],
            'ระบบงานคดี' => [
                'LawTrackReceive'       => 'แจ้งงานเข้ากองกฎหมาย',
                'LawListenMinistry'     => 'ร่างกฏหมายกระทรวง',
                'LawCasesForm'          => 'ระบบงานคดีผลิตภัณฑ์อุตสาหกรรม',
                'LawCasesNumber'        => 'เลขคดีผลิตภัณฑ์อุตสาหกรรม',

                'LawCasesBookCharges'   => 'หนังสือแจ้งการกระทำความผิด',
                'LawCasesBookCompare'   => 'หนังสือแจ้งเปรียบเทียบปรับ',
                'LawListenBookMinistry' => 'หนังสือแบบฟังความคิดเห็นร่างกฎกระทรวง',
            ]
        ];
    }

    static function ReferenceRefno($cer = 3){
        $config = HP::getConfig(false);
        $unstable = 0;//จำนวนตัวแปรไม่แน่นอน
		$prototype = [];
        if($cer == 3){ // ห้องปฏิบัติการ
            $format           =   !empty($config->reference_refno_lab) ?  $config->reference_refno_lab  :  '__Sur,#_-,BE2,#_-,NO3' ;
            $array_format     =  explode(",",$format);
            // $export    =  CertificateExport::select('reference_refno')->whereNotNull('reference_refno')->whereIn('status',[3,19,28])->pluck('reference_refno');
        }else    if($cer == 2){ // หน่วยตรวจสอบ
            $format           =     !empty($config->reference_refno_ib) ?  $config->reference_refno_ib  :  '__IB,#_-,BE2,#_-,NO3';
            $array_format     =  explode(",",$format);
            // $export    =  CertiIBExport::select('reference_refno')->whereNotNull('reference_refno')->whereIn('status',[3,19,28])->pluck('reference_refno');
        }else   if($cer == 1){ // ห้องหน่วยรับรอง
            $format           =      !empty($config->reference_refno_cb) ?  $config->reference_refno_cb  : '__CB,#_-,BE2,#_-,NO3';
            $array_format     =  explode(",",$format);
            // $export    =  CertiCBExport::select('reference_refno')->whereNotNull('reference_refno')->whereIn('status',[3,19,28])->pluck('reference_refno');
        }
            $export = Tracking::select('reference_refno')
                                ->whereNotNull('reference_refno')
                                ->where('certificate_type',$cer)
                                ->pluck('reference_refno');


		foreach($array_format as $key=>$item_format){
            if(!empty($item_format)){
                   if(strpos($item_format, '#_')===false && strpos($item_format, '__')===false){
                        $item_format = self::SplitData2($format, $key); //get ประเภทข้อมูล

                        if($item_format=='AC'){// กรณี ปี ค.ศ.
                            $amount = self::SplitSubData($format, $key); //จำนวนหลัก
                              $year = date('Y');
                              if($amount==2){//กรณี 2 หลัก
                                   $year = substr($year, 2, 2);
                              }
                              $prototype[] = $year;
                        }else if($item_format=='BE'){// กรณี ปี พ.ศ.
                              $amount = self::SplitSubData($format, $key); //จำนวนหลัก
                              $year = date('Y')+543;
                              if($amount==2){//กรณี 2 หลัก
                                   $year = substr($year, 2, 2);
                              }
                              $prototype[] = $year;
                         }else if(strpos($item_format, 'NO')===0){
                             $amount = self::SplitSubData($format, $key); //จำนวนหลัก
                            if(count($export) > 0){
                                $list_code = [];
                                foreach($export as $max_no ){
                                    $new_run = explode('-',  $max_no);
                                    if(strlen($new_run[2]) == $amount){
                                     $list_code[$new_run[2]] = $new_run[2];
                                    }
                                }

                                if(count($list_code) > 0){
                                    usort($list_code, function($x, $y) {
                                        return $x > $y;
                                       });
                                    $last = end($list_code);
                                    $max_new = ((int)$last  + 1);; //บวกค่า 1
                                }else{
                                    $max_new = 1;
                                }

                            }else{
                                $max_new = 1;
                            }
                            $prototype[] = str_pad($max_new, $amount, $unstable, STR_PAD_LEFT); //แทนค่าให้ครบตามจำนวนหลัก ด้วย 0 แล้ว เก็บลง Array

                        }else{
                            $prototype[] = $item_format;
                        }
                    }else{
                        $prototype[] = self::SplitData1($format, $key);
                    }

                }
            }

        return implode('', $prototype);
    }


	public static function SplitData1($source, $index){ //get ค่า free text
		$source = explode(',', $source);
		$item = $source[$index];
        $item =  str_replace('#_', '',  $item);
        $item =  str_replace('__', '',  $item);
		return $item;
	}

    public static function SplitData2($source, $index){ //get ค่า dropdown list
		$numberList = range(0,9);
		$source = explode(',', $source);
		$item = $source[$index];
		foreach($numberList as $number){
		   $item = str_replace($number, '', $item);
		}
		return $item;
	}

	public static function SplitSubData($source, $index){ //get ค่าข้อมูลย่อย จำนวน หลักของข้อมูล
		$numberList = array('BE','BF','AC','NO');
		$source = explode(',', $source);
		$item = $source[$index];
		foreach($numberList as $number){
		   $item = str_replace($number, '', $item);
		}
		return (int)$item;
	}


    public static function LogInsertNotification( $ref_id, $ref_table, $raf_app_no, $status, $title, $detail, $url , $users_id , $type = 1 )
    {

        $check = LogNotification::where('ref_table', $ref_table )->where('ref_id', $ref_id )->where('ref_id', $ref_id )->select('status')->orderBy('id', 'desc')->first();

        if( $type == 1 ){

            if( is_null($check) || ( !is_null($check) && ( $check->status  != $status  )) ){

                $log = array();

                $log['title'] = !empty($title)?$title:null;
                $log['details'] = !empty($detail)?$detail:null;

                $log['ref_applition_no'] = !empty($raf_app_no)?$raf_app_no:null;
                $log['ref_table'] = !empty($ref_table)?$ref_table:null;
                $log['ref_id'] = !empty($ref_id)?$ref_id:null;
                $log['status'] = !empty($status)?$status:null;

                $log['site'] = 'center';
                $log['root_site'] = url('/');
                $log['url'] = !empty($url)?$url:null;

                $log['users_id'] = !empty($users_id)?$users_id:null;

                $log['type'] = !empty($type)?$type:null;

                $log['ref_table_user'] = (new SSO_User)->getTable() ;

                LogNotification::create($log);
            }

        }else{

            $log = array();

            $log['title'] = !empty($title)?$title:null;
            $log['details'] = !empty($detail)?$detail:null;

            $log['ref_applition_no'] = !empty($raf_app_no)?$raf_app_no:null;
            $log['ref_table'] = !empty($ref_table)?$ref_table:null;
            $log['ref_id'] = !empty($ref_id)?$ref_id:null;
            $log['status'] = !empty($status)?$status:null;

            $log['site'] = 'center';
            $log['root_site'] = url('/');
            $log['url'] = !empty($url)?$url:null;

            $log['users_id'] = !empty($users_id)?$users_id:null;

            $log['type'] = !empty($type)?$type:null;
            $log['ref_table_user'] = (new User)->getTable();

            LogNotification::create($log);
        }

    }

	public static function LawPayrName($name ='',$taxid = ''){ //get ค่าข้อมูลย่อย จำนวน หลักของข้อมูล
         $app_id =  SSO_User::where('tax_number',$taxid)->value('applicanttype_id');
         $request = '';
         if(!empty($app_id) &&  $app_id != '2' ){
            $request =  $name.' (สำนักงานใหญ่)';
         }else{
            if( !empty($name) && strpos($name,'บริษัท')){
                $request =  $name.' (สำนักงานใหญ่)';
            }else{
                $request = $name;
            }
         }
		return  $request;
	}

 
    public static function SplitDataType( $val, $type  = 1)
    {
        if($type == 1){
            return  mb_substr($val,0,2);
        }else{
            return  mb_substr($val,2);
        }

    }


    static function get_certify_export_status(){
        $status_text = [
                            '0' => 'จัดทำใบรับรอง และอยู่ระหว่างลงนาม',
                            '1' => 'อยู่ระหว่างจัดทำลงนามใบรับรองระบบงาน',
                            '2' => 'นำส่งใบรับรองระบบงานเพื่อลงนาม',
                            '3' => 'ลงนามใบรับรองระบบงานเรียบร้อย',
                            '4' => 'จัดส่งใบรับรองระบบงาน',
                            '5' => ' ยกเลิกการใช้งาน'
                           ];
        return $status_text;
    }

    //แปลงข้อมูล UserAgent เป็นชื่อโปรแกรมเว็บเบราว์เซอร์สั้นๆ
    static function FormatUserAgent($user_agent, $show_icon=false){

        $icons = [
                    'Opera' => '<i class="mdi mdi-opera"></i>',
                    'Edge' => '<i class="mdi mdi-edge"></i>',
                    'Chrome' => '<i class="mdi mdi-google-chrome"></i>',
                    'Safari' => '<i class="mdi mdi-apple-safari"></i>',
                    'Firefox' => '<i class="mdi mdi-firefox"></i>',
                    'Internet Explorer' => '<i class="mdi mdi-internet-explorer"></i>',
                    'Unkown' => '<i class="mdi mdi-incognito"></i>'
                ];

        $result = '';
        $t = strtolower($user_agent);
        $t = " " . $t;
        if(strpos($t, 'opera') || strpos($t, 'opr/')){
            $result = 'Opera'            ;
        }elseif (strpos($t, 'edge')){
            $result = 'Edge'             ;
        }elseif (strpos($t, 'chrome')){
            $result = 'Chrome'           ;
        }elseif (strpos($t, 'safari')){
            $result = 'Safari'           ;
        }elseif (strpos($t, 'firefox')){
            $result = 'Firefox'          ;
        }elseif (strpos($t, 'msie') || strpos($t, 'trident/7')){
            $result = 'Internet Explorer';
        }else{
            $result = 'Unkown';
        }

        $result = $show_icon===true ? $icons[$result].' '.$result : $result ;
        return $result;
    }

    //เช็คข้อมูลเป็นตัวเลขทั้งหมดหรือไม่ ครบ 13 หลักหรือไม่
    static function check_number_counter($input, $counter=13){
        $converted = preg_replace("/[^0-9]/", '', $input);
        return $converted===$input && strlen($input)===$counter ? true : false;
    }

    //เช็ครูปแบบเลขทะเบียนโรงงานแบบเดิมว่ามีอักษรที่ไม่อนุญาตหรือไม่ (ป้องกัน SQL Injection ก่อนส่งไปเรียก API)
	static function check_factory_format_old($input){
		if(preg_match("/^[0-9ก-ฮ|(\-\(\)\/\.)]+$/", $input)){//อนุญาต 0-9 และ ภาษาไทย และ - ( ) / .
			return true;
		}else{
			return false;
		}
	}

    // log การแจ้งเตือนส่งอีเมล สก.
    public static function getInsertCertifyLogEmail($app_no =  null, $app_id =  null, $app_table =  null,$ref_id =  null, $ref_table =  null, $certify = null, $subject = null,  $html = null, $user_id = null , $agent_id = null , $created_by = null , $email = null , $email_to = null  , $email_cc = null , $email_reply = null , $attach = null ){
        // $requestData = ['app_no'            => $app_no,
        //                 'app_id'            => $app_id,
        //                 'app_table'         => $app_table,
        //                 'ref_id'            => $ref_id,
        //                 'ref_table'         => $ref_table,
        //                 'certify'           => $certify,
        //                 'subject'           => $subject,
        //                 'detail'            => $html,
        //                 'user_id'           => $user_id,
        //                 'agent_id'          => $agent_id,
        //                 'created_by'        => $created_by,
        //                 'email'             => $email,
        //                 'email_to'          => $email_to,
        //                 'email_cc'          => $email_cc,
        //                 'email_reply'       => $email_reply,
        //                 'attach'            => $attach,
        //                 'status'            => 2
        //               ];
        //   return CertifyLogEmail::create($requestData);
            $certify_log                    = new  CertifyLogEmail;
            $certify_log->status            = 2;
            $certify_log->save();

            $request =  CertifyLogEmail::findOrFail($certify_log->id);
            if(!is_null($request)){

                   $requestData = ['app_no'    => $app_no,
                        'app_id'            => $app_id,
                        'app_table'         => $app_table,
                        'ref_id'            => $ref_id,
                        'ref_table'         => $ref_table,
                        'certify'           => $certify,
                        'subject'           => $subject,
                        'detail'            => $html,
                        'user_id'           => $user_id,
                        'agent_id'          => $agent_id,
                        'created_by'        => $created_by,
                        'email'             => $email,
                        'email_to'          => $email_to,
                        'email_cc'          => $email_cc,
                        'email_reply'       => $email_reply,
                        'attach'            => $attach,
                        'status'            => 2
                      ];
                $request->update($requestData);
            }
           return $certify_log;

       }
    // อัพเดพสถานะ log การแจ้งเตือนส่งอีเมล สก.
    public static function getUpdateCertifyLogEmail($id =  null){
        $request =  CertifyLogEmail::findOrFail($id);
        if(!is_null($request)){
            $request->status = 1;
            $request->save();
        }
        return  $request;

    }

    static function getSsoUser($user_id){
        return SSO_User::find($user_id);
    }


    static function ConfigFormat( $systems , $table , $column, $application_type = null , $tisi_shortnumber = null, $tisi_number = null   )
    {
        $config = ConfigsFormatCode::where( 'system', $systems)->where('state', 1)->first();

        $today = date('Y-m-d');
        $dates = explode('-', $today);

        $ref_no_run = null;

        $Item_format = [];
        $year_bf_check = false;

        if( !is_null($config) ){

            $sub = ConfigsFormatCodeSub::where('format_id', $config->id )->select( 'format','data','sub_data' )->get();

            //ข้อมูลเลขรัน
            $datas_set_no = null;
            $datas_key_no = null;

            $array_format = [];
            $strNextSeq = '';

            $json =  (count( $sub ) > 0 )?json_encode( $sub, JSON_UNESCAPED_UNICODE ):null;

            $right_no = false;
            $right_arr = [];
            $left_arr = [];

            //วนรูปแบบ
            foreach( $sub AS $key => $item ){

                $format = $item->format;

                $dataSet = null;

                if( $format == 'character' ){ //อักษรนำ
                    $dataSet .= !empty($item->data)?$item->data:null;
                }else if( $format == 'separator' ){ //อักษรคั่น
                    $dataSet .= !empty($item->data)?$item->data:null;
                }else if( $format == 'month' ){ //เดือน
                    $dataSet .= $dates[1];
                }else if( $format == 'year-be' ){ //ปี พ.ศ.

                    if( $item->data == '4'){
                        $dataSet = $dates[0] + 543;
                    }else{
                        $dataSet = (substr( ($dates[0] + 543) , 2) );
                    }

                }else if( $format == 'year-bf' ){ //ปี พ.ศ.ตามปีงบประมาณ

                    $yaer  = ( $dates[0] >= 10 )?($dates[0] + 544):($dates[0] + 543);

                    if( $item->data == '4'){
                        $dataSet = $yaer;
                    }else{
                        $dataSet = (substr( $yaer , 2) );
                    }
                    $year_bf_check = true;
                }else if( $format == 'year-ac' ){ //ปี ค.ศ.

                    if( $item->data == '4'){
                        $dataSet = $dates[0];
                    }else{
                        $dataSet = (substr( ($dates[0]) , 2) );
                    }

                }else if( $format == 'no' ){ //เลขรัน

                    $numbers = !empty($item->data) ?($item->data):0;

                    $number_set = !empty($item->data) && ($item->data >= 2) ?($item->data - 1):0;

                    $zero = str_repeat( '0',  $number_set  );

                    $datas_set_no = $item;
                    $datas_key_no = $key;

                    $dataSet =  substr( $zero .(1),- $numbers,  $numbers );

                }

                if(  $right_no === true ){
                    $right_arr[ $format ] = strlen( $dataSet );
                }

                if( $format == 'tisi_shortnumber' ){ // เลขที่มอก
                    $dataSet = !empty($tisi_shortnumber)?$tisi_shortnumber:null;

                    $right_no = true;

                }else if( $format == 'tisi_number' ){ //รหัสมาตรฐาน
                    $dataSet = !empty($tisi_number)?$tisi_number:null;

                    $right_no = true;

                }else if( $format == 'application_type' ){ //ประเภทใบสมัคร
                    $dataSet = !empty($application_type)?$application_type:null;
                }

                if( $right_no === false ){
                    $left_arr[ $format ] = strlen( $dataSet );
                }

                $dataSet = !empty($dataSet)?str_replace(' ', '', $dataSet):null;

                $item->data_set = $dataSet;

                $array_format[ $key ] = $item;

                $Item_format[ $key ] = (string)$dataSet;

                $strNextSeq .= $dataSet;

            }

            //ถ้ามีเลขรัน format = no
            if( !is_null($datas_set_no) ){

                $font_search = null;
                $back_search = null;

                //หาข้อความก่อน เลขรัน
                foreach( $array_format AS $kf => $Fitem ){

                    if( $Fitem->format != 'tisi_shortnumber' && $Fitem->format != 'tisi_number' ){

                        if( $kf < $datas_key_no ){
                            $font_search .= $Fitem->data_set;
                            if( $Fitem->format != 'separator' ){
                                break;
                            }
                        }

                    }

                }

                //หาข้อความหลัง เลขรัน
                foreach( $array_format AS $kf => $Fitem ){

                    if( $Fitem->format != 'tisi_shortnumber' && $Fitem->format != 'tisi_number' ){

                        if( $kf > $datas_key_no ){
                            $back_search .= $Fitem->data_set;
                            if( $Fitem->format != 'separator' ){
                                break;
                            }

                        }

                    }

                }

                // หาช่วงการรันต่อ
                $sub_data = $datas_set_no->sub_data;

                if( $sub_data == 'o'){
                    $query_check = DB::table( $table )
                                        ->where(function($query) use($column, $font_search, $back_search ){
                                            if( !empty($font_search) ){
                                                $query->where($column, 'LIKE', "%$font_search%");
                                            }else if( empty($font_search) && !empty($back_search) ){
                                                $query->where($column, 'LIKE', "%$back_search%");
                                            }
                                        })
                                        ->select($column, 'created_at' )
                                        ->orderBy($column)
                                        ->get();
                }else if( $sub_data == 'm'){
                    $query_check = DB::table( $table )
                                        ->select($column, 'created_at' )
                                        ->where(function($query) use($column, $font_search, $back_search ){
                                            if( !empty($font_search) ){
                                                $query->where($column, 'LIKE', "%$font_search%");
                                            }else if( empty($font_search) && !empty($back_search) ){
                                                $query->where($column, 'LIKE', "%$back_search%");
                                            }
                                        })
                                        ->where(function($query) use($dates){
                                            $query->whereYear('created_at',$dates[0])->whereMonth('created_at', $dates[1] );
                                        })
                                        ->orderBy($column)
                                        ->get();
                }else if( $sub_data == 'y'){
                    $query_check = DB::table( $table )
                                        ->select($column, 'created_at' )
                                        ->where(function($query) use($column, $font_search, $back_search ){
                                            if( !empty($font_search) ){
                                                $query->where($column, 'LIKE', "%$font_search%");
                                            }else if( empty($font_search) && !empty($back_search) ){
                                                $query->where($column, 'LIKE', "%$back_search%");
                                            }
                                        })
                                        ->where(function($query) use($dates, $year_bf_check){
                                            if( $year_bf_check == false ){ // ตามปี
                                                $query->whereYear('created_at',$dates[0]);
                                            }else{ // ตามปีงบ
                                                $startDate = \Carbon\Carbon::parse( $dates[0].'-10-01' )->format('Y-m-d');
                                                $endDate   = \Carbon\Carbon::parse( $dates[0].'-09-30' )->addYears(1)->format('Y-m-d');
                                                $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
                                            }
                                        })
                                        ->orderBy($column)
                                        ->get();
                }

                $numbers = !empty($datas_set_no->data) ?($datas_set_no->data):0;

                $number_set = !empty($datas_set_no->data) && ($datas_set_no->data >= 2) ?($datas_set_no->data - 1):0; //จำนวนหลักเฉพาะเลขรัน ที่ -1

                $number_search = !empty($datas_set_no->data)?$datas_set_no->data:0; //จำนวนหลักเฉพาะเลขรัน

                $zero = str_repeat( '0',  $number_set  );

                $query = 0;
                if(count($query_check) != 0){

                    $last_data = $query_check->last();

                    $checks_state = false;

                    $log_old = ConfigsFormatCodeLog::where('format_id', $config->id )
                                                    ->where( 'system', $systems)
                                                    ->select('data', 'start_date', 'end_date', 'state')
                                                    ->get();

                    $check_log = true;
                    if( count($log_old) == 1 && !is_null( $log_old->where('state', 1)->last() ) ){  // กรณีเพิ่ม log ใหม่
                        $check_log = false;
                    }

                    foreach( $log_old AS $old ){

                        //หา Format ที่ใช้งาน ก่อนหน้าว่ารูปแบบตรงกันไหม
                        if( mb_strpos($old->data, $json ) !== false  ){

                            if( $check_log == true ){  // กรณี log มีมากกว่า 1

                                //หาจากช่วงวันที่ จากใบก่อนหน้า
                                if( !is_null($old->end_date) && ( $last_data->created_at >= $old->start_date) && ( $last_data->created_at <= $old->end_date) ){
                                    $checks_state = true;
                                    break;
                                }else if( ( $last_data->created_at >= $old->start_date ) && is_null($old->end_date) ){ //หาจากช่วงวันที่ จากก่อนหน้า ที่ใช้งานปัจจุบัน
                                    $checks_state = true;
                                    break;
                                }

                            }else{ // กรณีเพิ่ม log ใหม่
                                $checks_state = true;
                            }

                        }
                    }

                    // Format ที่ใช้งาน
                    if( $checks_state === true ){

                        $_no =  $last_data->{$column};
                        $_no = !empty($_no)?str_replace(' ', '', $_no):null;

                        $_arr_format = [];

                        if( $right_no === true){
                            $sum = array_sum($right_arr);
                            $suc_string = mb_substr($_no,  - $sum ,  $sum );
                            $_arr_format = $right_arr;

                        }else{

                            $sum = array_sum($left_arr);
                            $suc_string = mb_substr($_no,  - $sum ,  $sum );
                            $_arr_format = $left_arr;
                        }

                        foreach(  $_arr_format AS $ka => $Atiem  ){

                            if( $ka == 'no' ){

                                $next = next( $_arr_format ) === false ? strlen($suc_string):$Atiem;

                                $suc_string = mb_substr( $suc_string , 0,  $next );
                                break;
                            }
                            $suc_string = mb_substr( $suc_string , $Atiem  );

                        }

                        $Max = str_repeat( '9',  $number_search  ); //ค่า MAX ของเลขรัน
                        $check_number_max = ($Max == $suc_string)?( $suc_string + 1 ):$suc_string; //เช็คว่าเกินค่า Max หรือยัง

                        if( mb_strlen( $check_number_max  ) > (int)$numbers ){ //กรณีที่รันเกินจำนวนหลักเลขรัน
                            $Seq_max = substr( $zero .( (int)$suc_string + 1 ),- $numbers,  $numbers );
                            $Seq = ( $check_number_max ) + (int)$Seq_max;

                        }else{
                            $Seq = substr( $zero .( (int)$suc_string + 1 ),- $numbers,  $numbers );
                        }

                    }else{
                        $Seq = substr( $zero .(1),- $numbers,  $numbers );
                    }

                }else{
                    $Seq = substr( $zero .(1),- $numbers,  $numbers );
                }

                $Item_format[ $datas_key_no ]=  $Seq;
                $check_run = implode('', $Item_format );

                $no_check = DB::table( $table )->where($column, $check_run )->first();
                if(is_null($no_check)){
                    $Item_format[ $datas_key_no ]=  $Seq;
                }else{
                    $Seq = substr( $zero .( (int)$Seq + 1 ),- $numbers,  $numbers );
                    $Item_format[ $datas_key_no ]=  $Seq;
                }
            }

        }

        return implode('', $Item_format );
    }

    private static $data_provinces_lsit;//จังหวัด
    private static $data_districts_list;//อำเภอ
    private static $data_sub_districts_lsit;//ตำบล
    private static $data_zipcode_lsit;//รหัสไปรษณีย์
    private static $data_district_groups_list;//Group อำเภอ By ID จังหวัด
    private static $data_sub_district_groups_list;// Group ตำบล By ID อำเภอ

    public static function GetIDAddress( $txt_sub = null, $txt_dis = null, $txt_pro = null )
    {
        $data = new stdClass;

        if( !empty($txt_sub) && !empty($txt_dis) && !empty($txt_pro)  ){

            $txt_sub = trim($txt_sub);
            $txt_dis = trim($txt_dis);
            $txt_pro = trim($txt_pro);

            if( strpos( $txt_sub , "ตำบล" ) === 0 ){
                $txt_sub =  !empty($txt_sub)?str_replace('ตำบล','',$txt_sub):null;
            }

            if( strpos( $txt_dis , "อำเภอ/เขต" ) === 0 ){
                $txt_dis =  !empty($txt_dis)?str_replace('อำเภอ/เขต','',$txt_dis):null;
            }else if( strpos( $txt_dis , "เขต" ) === 0 ){
                $txt_dis =  !empty($txt_dis)?str_replace('เขต','',$txt_dis):null;
            }else if( strpos( $txt_dis , "อำเภอ" ) === 0 ){
                $txt_dis =  !empty($txt_dis)?str_replace('อำเภอ','',$txt_dis):null;
            }

            if( strpos( $txt_pro , "จังหวัด" ) === 0 ){
                $txt_pro =  !empty($txt_pro)?str_replace('จังหวัด','',$txt_pro):null;
            }

            //จัดหวัด
            if( !is_array(self::$data_provinces_lsit) ){
                self::$data_provinces_lsit =  Province::select(DB::raw("TRIM(`PROVINCE_NAME`) AS PROVINCE_NAME"), 'PROVINCE_ID')->pluck( 'PROVINCE_NAME', 'PROVINCE_ID')->toArray();
            }
            $provinces = self::$data_provinces_lsit;

            //อำเภอ
            if( !is_array(self::$data_districts_list) ){
                $districts = Amphur::selectRaw("
                                                    AMPHUR_ID,
                                                    IF(POSITION('เขต' IN TRIM(`AMPHUR_NAME`)) = 1,
                                                        REPLACE(TRIM(`AMPHUR_NAME`), 'เขต', '')
                                                    ,
                                                        IF(POSITION('อำเภอ' IN TRIM(`AMPHUR_NAME`)) = 1,
                                                            REPLACE(TRIM(`AMPHUR_NAME`), 'อำเภอ', '')
                                                        ,
                                                            TRIM(`AMPHUR_NAME`)
                                                        )
                                                    ) AS AMPHUR_NAME,
                                                    PROVINCE_ID
                                                ")
                                                ->where(DB::raw("REPLACE(AMPHUR_NAME,' ','')"),  'NOT LIKE', "%*%")
                                                ->get();

                $district_group_tmps = $districts->groupBy('PROVINCE_ID')->toArray();

                $district_groups = [];
                foreach ($district_group_tmps as $key => $tmp) {
                    $district_groups[$key] = collect($tmp)->pluck('AMPHUR_NAME', 'AMPHUR_ID')->toArray();
                }
                self::$data_districts_list       = $districts->pluck('AMPHUR_NAME', 'AMPHUR_ID')->toArray();
                self::$data_district_groups_list = $district_groups;
            }
            $districts = self::$data_districts_list;
            $district_groups = self::$data_district_groups_list;

            //ตำบล
            if( !is_array(self::$data_sub_districts_lsit) ){
                $sub_districts = District::select('DISTRICT_ID', DB::raw("TRIM(`DISTRICT_NAME`) AS DISTRICT_NAME"), 'AMPHUR_ID')->where(DB::raw("REPLACE(DISTRICT_NAME,' ','')"),  'NOT LIKE', "%*%")->get()->makeHidden(['districtname', 'provincename']);
                $sub_district_group_tmps = collect($sub_districts->toArray())->groupBy('AMPHUR_ID')->toArray();
                $sub_district_groups     = [];
                foreach ($sub_district_group_tmps as $key => $tmp) {
                    $sub_district_groups[$key] = collect($tmp)->pluck('DISTRICT_NAME', 'DISTRICT_ID')->toArray();
                }
                self::$data_sub_districts_lsit = $sub_districts->pluck('DISTRICT_NAME', 'DISTRICT_ID')->toArray();
                self::$data_sub_district_groups_list = $sub_district_groups;

            }
            $sub_districts = self::$data_sub_districts_lsit;
            $sub_district_groups = self::$data_sub_district_groups_list;

            //รหัสไปรษณีย์
            if( !is_array(self::$data_zipcode_lsit) ){
                self::$data_zipcode_lsit =  DB::table((new District)->getTable().' AS sub') // ตำบล
                                                    ->leftJoin((new Amphur)->getTable().' AS code', 'code.AMPHUR_ID', '=', 'sub.AMPHUR_ID')  // รหัสไปรษณีย์
                                                    ->select('sub.DISTRICT_ID', DB::raw('code.POSTCODE AS zipcode') )
                                                    ->pluck( 'zipcode', 'DISTRICT_ID')
                                                    ->toArray();
            }
            $zipcode = self::$data_zipcode_lsit;


            $province_ids = array_search($txt_pro, $provinces);
            $district_ids = array_search($txt_dis, $districts);
            $subdistrict_ids = array_search( $txt_sub , $sub_districts);

            if($province_ids!==false){
                $data->province_id = $province_ids;
            }else{
                $data->province_id = null;
            }

            if($province_ids!==false && $district_ids!==false){
                $district_ids = array_key_exists($province_ids, $district_groups) ? array_search( $txt_dis , $district_groups[ $province_ids ]) : false;
                $data->district_id = ( $district_ids!==false ? $district_ids : null );
            }else{
                $data->district_id = null;
            }

            if($district_ids!==false && $subdistrict_ids!==false){
                $subdistrict_ids = array_key_exists($district_ids, $sub_district_groups) ? array_search( $txt_sub , $sub_district_groups[ $district_ids ]) : false;
                $data->subdistrict_id = ( $subdistrict_ids!==false ? $subdistrict_ids : null );
            }else{
                $data->subdistrict_id = null;
            }

            if( $subdistrict_ids!==false ){
                $data->zipcode = array_key_exists(  $subdistrict_ids , $zipcode )?$zipcode[ $subdistrict_ids ]:null;
            }else{
                $data->zipcode = null;
            }

        }else{
            $data->province_id = null;
            $data->district_id = null;
            $data->subdistrict_id = null;
            $data->zipcode = null;
        }

        return $data;
    }


    static function MenuSidebar( $check_auth = true )
    {
        $menu = [];

        //ระบบกำหนดมาตรฐาน
        if (File::exists(base_path('resources/laravel-admin/new-menu-standards.json'))) {
            $laravelMenuStandards = json_decode(File::get(base_path('resources/laravel-admin/new-menu-standards.json')));
            if( HP::CheckMenuItem($laravelMenuStandards->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuStandards->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuStandards->menus[0];
            }

        }

        //ระบบกำหนดมาตรฐานรับรอง
        if (File::exists(base_path('resources/laravel-admin/new-menu-set-standards.json'))) {
            $laravelMenueSetStd = json_decode(File::get(base_path('resources/laravel-admin/new-menu-set-standards.json')));
            if( HP::CheckMenuItem($laravelMenueSetStd->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenueSetStd->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenueSetStd->menus[0];
            }
        }

        //ระบบรับรองระบบงาน
        if (File::exists(base_path('resources/laravel-admin/new-menu-certify.json'))) {
            $laravelMenuCertify = json_decode(File::get(base_path('resources/laravel-admin/new-menu-certify.json')));
            if( HP::CheckMenuItem($laravelMenuCertify->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuCertify->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuCertify->menus[0];
            }
        }

        //ระบบตรวจการอิเล็กทรอนิกส์(e-Surv)
        if (File::exists(base_path('resources/laravel-admin/new-menu-e-surv.json'))) {
            $laravelMenueSurv = json_decode(File::get(base_path('resources/laravel-admin/new-menu-e-surv.json')));
            if( HP::CheckMenuItem($laravelMenueSurv->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenueSurv->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenueSurv->menus[0];
            }
        }

        //ระบบขึ้นทะเบียนตาม(ม.5)
        if (File::exists(base_path('resources/laravel-admin/new-menu-section5.json'))) {
            $laravelMenuSection5 = json_decode(File::get(base_path('resources/laravel-admin/new-menu-section5.json')));
            if( HP::CheckMenuItem($laravelMenuSection5->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuSection5->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuSection5->menus[0];
            }
        }

        //ระบบบันทึกคดีผลิตภัณฑ์อุตสาหกรรม
        if (File::exists(base_path('resources/laravel-admin/new-menu-law.json'))) {
            $laravelMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/new-menu-law.json')));
            if( HP::CheckMenuItem($laravelMenuLaw->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuLaw->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuLaw->menus[0];
            }
        }

        //ระบบสืบค้น
        if (File::exists(base_path('resources/laravel-admin/new-menu-search.json'))) {
            $laravelMenuSearch = json_decode(File::get(base_path('resources/laravel-admin/new-menu-search.json')));
            if( HP::CheckMenuItem($laravelMenuSearch->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuSearch->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuSearch->menus[0];
            }
        }

        //เว็บเซอร์วิส
        if (File::exists(base_path('resources/laravel-admin/new-menu-ws.json'))) {
            $laravelMenueWs = json_decode(File::get(base_path('resources/laravel-admin/new-menu-ws.json')));
            if( HP::CheckMenuItem($laravelMenueWs->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenueWs->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenueWs->menus[0];
            }
        }

        //ระบบรายงาน
        if (File::exists(base_path('resources/laravel-admin/new-menu-report.json'))) {
            $laravelMenuReport = json_decode(File::get(base_path('resources/laravel-admin/new-menu-report.json')));
            if( HP::CheckMenuItem($laravelMenuReport->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuReport->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuReport->menus[0];
            }
        }

        //ระบบการเงิน
        if (File::exists(base_path('resources/laravel-admin/new-menu-accounting.json'))) {
            $laravelMenuAccounting = json_decode(File::get(base_path('resources/laravel-admin/new-menu-accounting.json')));
            if( HP::CheckMenuItem($laravelMenuAccounting->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuAccounting->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuAccounting->menus[0];
            }
        }

        //HR
        if( $check_auth == true  ){
            $item = new stdClass;
            $item->hr = true;
            $menu[] = $item;
        }

        //ระบบจัดการผู้ใช้งาน
        if (File::exists(base_path('resources/laravel-admin/new-menu-users.json'))) {
            $laravelMenuUser = json_decode(File::get(base_path('resources/laravel-admin/new-menu-users.json')));
            if( HP::CheckMenuItem($laravelMenuUser->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuUser->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuUser->menus[0];
            }
        }

        //ระบบตั้งค่า
        if (File::exists(base_path('resources/laravel-admin/new-menu-config.json'))) {
            $laravelMenueConfig = json_decode(File::get(base_path('resources/laravel-admin/new-menu-config.json')));
            if( HP::CheckMenuItem($laravelMenueConfig->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenueConfig->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenueConfig->menus[0];
            }
        }

        //ระบบข่าวประชาสัมพันธ์
        if (File::exists(base_path('resources/laravel-admin/new-menu-blog.json'))) {
            $laravelMenuBlog = json_decode(File::get(base_path('resources/laravel-admin/new-menu-blog.json')));
            if( HP::CheckMenuItem($laravelMenuBlog->menus[0]->items) && $check_auth == true  ){
                $menu[] = $laravelMenuBlog->menus[0];
            }else if( $check_auth == false  ){
                $menu[] = $laravelMenuBlog->menus[0];
            }
        }

        return $menu;

    }

    static function MenuTraderSidebar( $check_auth = true )
    {
        $menu = [];

        //ตรวจติดตามออนไลน์
        if (File::exists(base_path('resources/laravel-admin/trader-menu-esurvs.json'))) {
            $TraderMenuEsurvs = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-esurvs.json')));
            if( HP::check_group_menu($TraderMenuEsurvs) && $check_auth == true  ){
                $menu[] = $TraderMenuEsurvs->menus[0];
            }else{
                $menu[] = $TraderMenuEsurvs->menus[0];
            }
        }

        //ใบรับรองระบบงาน
        if (File::exists(base_path('resources/laravel-admin/trader-menu-certifys.json'))) {
            $TraderMenuCertifys = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-certifys.json')));
            if( HP::check_group_menu($TraderMenuCertifys) && $check_auth == true  ){
                $menu[] = $TraderMenuCertifys->menus[0];
            }else{
                $menu[] = $TraderMenuCertifys->menus[0];
            }
        }

        //ตรวจติดตามใบรับรอง
        if (File::exists(base_path('resources/laravel-admin/trader-menu-certificate.json'))) {
            $TraderMenuCertificates = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-certificate.json')));
            if( HP::check_group_menu($TraderMenuCertificates) && $check_auth == true  ){
                $menu[] = $TraderMenuCertificates->menus[0];
            }else{
                $menu[] = $TraderMenuCertificates->menus[0];
            }
        }

        //รับ-แจ้งผลการทดสอบ (LAB)
        if (File::exists(base_path('resources/laravel-admin/trader-menu-labs.json'))) {
            $TraderMenuLabs = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-labs.json')));
            if( HP::check_group_menu($TraderMenuLabs) && $check_auth == true  ){
                $menu[] = $TraderMenuLabs->menus[0];
            }else{
                $menu[] = $TraderMenuLabs->menus[0];
            }
        }

        //ระบบขึ้นทะเบียนตาม(ม.5)
        if (File::exists(base_path('resources/laravel-admin/trader-menu-section5.json'))) {
            $TraderMenuSection5 = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-section5.json')));
            if( HP::check_group_menu($TraderMenuSection5) && $check_auth == true  ){
                $menu[] = $TraderMenuSection5->menus[0];
            }else{
                $menu[] = $TraderMenuSection5->menus[0];
            }
        }

        //ระบบงานคดีผลิตภัณฑ์อุตสาหกรรม
        if (File::exists(base_path('resources/laravel-admin/trader-menu-law.json'))) {
            $TraderMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/trader-menu-law.json')));
            if( HP::check_group_menu($TraderMenuLaw) && $check_auth == true  ){
                $menu[] = $TraderMenuLaw->menus[0];
            }else{
                $menu[] = $TraderMenuLaw->menus[0];
            }
        }


        return $menu;

    }

    public static function permissionList($menu, $permissions_list )
    {
        $name = str_slug($menu);
        // if($menu == 'assessment_report_assignment'){
        //     dd($name ,$permissions_list );
        // }
        $permissions['add']          = array_key_exists( ('add-'.$name), $permissions_list  )?$permissions_list[ ('add-'.$name) ]:null;
        $permissions['edit']         = array_key_exists( ('edit-'.$name), $permissions_list  )?$permissions_list[ ('edit-'.$name) ]:null;
        $permissions['view']         = array_key_exists( ('view-'.$name), $permissions_list  )?$permissions_list[ ('view-'.$name) ]:null;
        $permissions['delete']       = array_key_exists( ('delete-'.$name), $permissions_list  )?$permissions_list[ ('delete-'.$name) ]:null;
        $permissions['other']        = array_key_exists( ('other-'.$name), $permissions_list  )?$permissions_list[ ('other-'.$name) ]:null;

        $permissions['poko_approve'] = array_key_exists( ('poko_approve-'.$name), $permissions_list  )?$permissions_list[ ('poko_approve-'.$name) ]:null;
        $permissions['poao_approve'] = array_key_exists( ('poao_approve-'.$name), $permissions_list  )?$permissions_list[ ('poao_approve-'.$name) ]:null;
        $permissions['assign_work']  = array_key_exists( ('assign_work-'.$name), $permissions_list  )?$permissions_list[ ('assign_work-'.$name) ]:null;
        $permissions['printing']     = array_key_exists( ('printing-'.$name), $permissions_list  )?$permissions_list[ ('printing-'.$name) ]:null;
        $permissions['export']     = array_key_exists( ('export-'.$name), $permissions_list  )?$permissions_list[ ('export-'.$name) ]:null;
        $permissions['view_all']     = array_key_exists( ('view_all-'.$name), $permissions_list  )?$permissions_list[ ('view_all-'.$name) ]:null;
        $permissions['sync_to_elicense'] = array_key_exists(('sync_to_elicense-'.$name), $permissions_list) ? $permissions_list[('sync_to_elicense-'.$name)] : null ;
        $permissions['follow_up_before'] = array_key_exists(('follow_up_before-'.$name), $permissions_list) ? $permissions_list[('follow_up_before-'.$name)] : null ;
        $permissions['receive_inspection'] = array_key_exists(('receive_inspection-'.$name), $permissions_list) ? $permissions_list[('receive_inspection-'.$name)] : null ;

        return  $permissions;
    }

    public static function MenuListAllRoleCount($type,$permissions)
    {
        if( $type=='staff' ){
            $ListMenu = HP::MenuSidebar(false);
        }else{
            $ListMenu = HP::MenuTraderSidebar();
        }

        $list = 0;
        foreach(  $ListMenu AS $Menu ){
            if(isset( $Menu->items )){
                foreach(  $Menu->items  as $Item ){
                    if( isset($Item->sub_menus) ){
                        foreach ( $Item->sub_menus as $sub_menus ){
                            if( property_exists($sub_menus, 'title') && array_key_exists( str_slug($sub_menus->title), $permissions ) ){
                                // $data           = new stdClass;
                                // $data->str_slug = str_slug($sub_menus->title);
                                // $data->display  = $sub_menus->display;
                                // $list[]         = $data;
                                $list++;
                            }
                        }
                    }else{
                        if( property_exists($Item, 'title')  && array_key_exists( str_slug($Item->title), $permissions )  ){
                            // $data           = new stdClass;
                            // $data->str_slug = str_slug($Item->title);
                            // $data->display  = $Item->display;
                            // $list[]         = $data;
                            $list++;
                        }
                    }
                }
            }
        }

        return  $list;
    }

    //แปลง 0 หรือ - หรือ empty เป็น null
    static function FormatToNull($input){
        return ($input==='0' || $input===0 || $input==='-' || $input==='') ? null : $input ;
    }

    /*
        ดึงค่าขนาดไฟล์สูงสุดที่จะให้อัพโหลดได้แต่จะไม่เกินที่กำหนดไว้ในระบบ
        $max_allow = ขนาดไฟล์หน่วยเป็น MB เช่น 5MB
        $output = ชนิดผลลัพท์ที่ส่งกลับ string ข้อความขนาดไฟล์หน่วยเป็น เช่น 5MB, int = ขนาดไฟล์หน่วยเป็น Byte เช่น 5242880
    */
    static function get_upload_max_filesize($max_allow, $output='string'){

        $max_allow = (int)$max_allow;
        $upload_max_filesize = (int)ini_get('upload_max_filesize');

        if($max_allow > $upload_max_filesize){//ถ้าค่าที่ต้องการน้อยกว่าที่ระบบอนุญาต
            $max_allow = $upload_max_filesize;
        }

        if($output=='int'){
            $result = $max_allow*1048576;
        }else if($output=='string'){
            $result = $max_allow.'MB';
        }

        return $result;
    }


    static function number_format($num, $decimals = 0)
    {
        $request = '';
     
        if(!empty($num)){
     
            $nums = explode('.', $num);   
          
            if(count($nums) == 2){
                $sval = str_replace(' ', '', $nums[0]) ; //จำนวนเต็ม
                $n = 0;
                $result = "";
                for( $i = strlen( $sval ) - 1 ; $i >= 0 ; $i-- )
                {
                   if ( $n == 3 )
                    {
                        $result = ",$result"; //ใส่ comma
                        $n = 0;
                    }
                    $n++;
                    $result = $sval[$i].$result;
                }
                $request =  $result;
                if($decimals > 0 && $nums[1] > 0){
                    $request .=  '.';
                    $request .=  mb_substr($nums[1],0,2);
                }
            }else if(count($nums) == 1){
                $sval = str_replace(' ', '', $num); //จำนวนเต็ม
                $n = 0;
                $result = "";
    
                for( $i = strlen( $sval ) - 1 ; $i >= 0 ; $i-- )
                {
                    if ( $n == 3 )
                    {
                        $result = ",$result"; //ใส่ comma
                        $n = 0;
                    }
                    $n++;
                    
                    $result = $sval[$i].$result;
                }

                $request = $result;
            }
        }
  
        return ( $request != '' ) ? $request : $num;
    }

}

Class HP_WS
{

    static function SaveRequest($Reason)
    {

        $user = auth()->user();

        $array = array();

        $array['user_id'] = $user->getKey(); //id ผู้ดึงข้อมูล
        $array['AgentID'] = $user->reg_13ID; //เลขประจำตัวผู้เสียภาษีผู้ดึงข้อมูล
        $array['reason'] = $Reason; //เหตุผลที่ดึงข้อมูล
        $array['ip_request'] = Request::ip();
        $array['User_Agent'] = $_SERVER['HTTP_USER_AGENT'];

        $industry_request = IndustryRequest::create($array);

        return $industry_request;

    }

    static function getJuristic($JuristicID, $ip)
    {//รับค่า $JuristicID=เลขทะเบียนพาณิชน์ที่ต้องการทราบข้อมูล, $AgentID=เลขประชาชนของเจ้าหน้าที่

        $response = (object)[];

        $config = HP::getConfig();

        $url = $config->tisi_api_corporation_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';

        $data = array(
                'val' => $JuristicID,
                'IP' => $ip, // IP Address,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $response = $api;
            if(!empty($api->JuristicName_TH)){
                $response->status = 'success';
            }elseif(!empty($api->Message)){
                $response->Result = $api->Message;
            }else{
                $response->status = 'fail';
            }
        } catch (\Exception $e) {
            $response->status = 'no-connect';
        }

        //บันทึก Log
        MOILog::Add($JuristicID, $url, 'corporation', $request_start, @$http_response_header, ($response->status!='success' ? $api : null));

        return $response;

    }

    static function getPersonal($PersonalID, $ip)
    {//รับค่า $PersonalID=เลขประจำตัวประชาชนที่ต้องการทราบข้อมูล

        $person = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';
        $data = array(
                'val'   => $PersonalID,
                'IP'    => $ip,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $person = $api;
            if(!empty($api->firstName)){
                $person->status = 'success';
            }else{
                $person->status = 'fail';
            }
        } catch (\Exception $e) {
            $person         = (object)[];
            $person->status = 'no-connect';
        }

        //บันทึก Log
        MOILog::Add($PersonalID, $url, 'person', $request_start, @$http_response_header, ($person->status!='success' ? $api : null));

        return $person;

    }

    //ดึงข้อมูลทะเบียนบ้านจากรมการปกครอง
    //รับค่า $PersonalID=เลขประจำตัวประชาชนที่ต้องการทราบข้อมูล
    static function getPersonalHouse($PersonalID, $ip)
    {

        $person = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_house_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=3';
        $data = array(
                'val'   => $PersonalID,
                'IP'    => $ip,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $person = $api;
            if(!empty($api->houseID)){
                $person->status = 'success';
            }else{
                $person->status = 'fail';
            }
        } catch (\Exception $e) {
            $person         = (object)[];
            $person->status = 'no-connect';
        }

        //บันทึก Log
        MOILog::Add($PersonalID, $url, 'person-house', $request_start, @$http_response_header, ($person->status!='success' ? $api : null));

        return $person;

    }

    static function getRdVat($JuristicID, $ip)
    {//รับค่า $JuristicID=เลขประจำตัวผู้เสียภาษีที่ต้องการทราบข้อมูล

        $response = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_faculty_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
        $data = array(
                'val' => $JuristicID,
                'IP' => $ip, // IP Address,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $response = $api;
            if(empty($api->vMessageErr) && empty($api->Message)){
                $response->status = 'success';
            }else{
                if(!empty($api->Message) && empty($api->vMessageErr)){//ใส่ Message ใน vMessageErr
                    $response->vMessageErr = $api->Message;
                }
                $response->status = 'fail';
            }
        } catch (\Exception $e) {
            $response         = (object)[];
            $response->status = 'no-connect';
        }

        //บันทึก Log
        MOILog::Add($JuristicID, $url, 'rd', $request_start, @$http_response_header, ($response->status!='success' ? $api : null));

        return $response;

    }

    static function getIndustry($JuristicID, $ip)
    {//รับค่า $JuristicID=เลขทะเบียนโรงงาน 14 หลัก

        $response = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_factory_url.$JuristicID; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=4&val=';
      
        $data = array(
                'IP' => $ip, // IP Address,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $response = $api;
            if(!empty($api->status) && $api->status==true){
                $response->status = 'success';
            }else{
                $response->status = 'fail';
            }
        } catch (\Exception $e) {
            $response         = (object)[];
            $response->status = 'fail';
        }

        //บันทึก Log
        MOILog::Add($JuristicID, $url, 'industry', $request_start, @$http_response_header, ($response->status!='success' ? $api : null));

        return $response;

    }

    static function getIndustry2($JuristicID, $ip)
    {//รับค่า $JuristicID=เลขทะเบียนโรงงาน 14 หลัก

        $response = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_factory_url2.$JuristicID; //'https://www3.tisi.go.th/json/moi.asp?srv=diwfac&fid=';
        $data = array(
                'IP' => $ip, // IP Address,
                'Refer' => 'center.tisi.go.th'
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null ;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $response = clone $api;
            
            if(!empty($api->status) && $api->status===true){
                $response->status = 'success';
            }else{
                $response->status = 'fail';
            }
        } catch (\Exception $e) {
            $response         = (object)[];
            $response->status = 'fail';
        }

        //บันทึก Log
        MOILog::Add($JuristicID, $url, 'industry2', $request_start, @$http_response_header, ($response->status!='success' ? $api : null));

        return $response;

    }

    static function getIndustry3($JuristicID, $ip)
    {//รับค่า $JuristicID=เลขทะเบียนโรงงาน 14 หลัก

        $response = null;

        $config = HP::getConfig(false);

        $url = $config->tisi_api_factory_url3.$JuristicID; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=9&val=';
        $data = array(
                'IP' => $ip, // IP Address,
                'Refer' => 'center.tisi.go.th',
                'Login' => auth()->user()->reg_uname
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $request_start = date('Y-m-d H:i:s');
        $api = null;

        try {
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            $response = $api;
            if(!empty($api->status) && $api->status==true){
                $response->status = 'success';
            }else{
                $response->status = 'fail';
            }
        } catch (\Exception $e) {
            $response         = (object)[];
            $response->status = 'fail';
        }

        //บันทึก Log
        MOILog::Add($JuristicID, $url, 'industry3', $request_start, @$http_response_header, ($response->status!='success' ? $api : null));

        return $response;

    }


    static function ValidateAPI($AgentID)
    {//ขั้นตอนการ Validate ขอ Token จาก กระทรวงอุตสาหกรรม

        $response = (object)[]; //Result Response

        $config = HP::getConfig();
        $url = $config->industry_auth_url . $AgentID; //URL

        $header = array("Content-Type: application/json");

        $data_string = json_encode(array('ClientID' => $config->industry_client_id, 'ClientSecret' => $config->industry_client_secret));

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Disable SSL verification host
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Accept Header Response
        curl_setopt($ch, CURLOPT_HEADER, 1);

        //Method
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Add Header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // Body JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        // Execute
        $response->Result = curl_exec($ch);

        //get Status Code
        @$response->HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Retudn headers seperatly from the Response Body
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response->Result, 0, $header_size);
        $body = substr($response->Result, $header_size);

        // Closing
        curl_close($ch);

        //Extract Header To Array
        $headers = self::extract_header($headers);

        //Format Body And Token
        $response->Body = str_replace('"', '', $body);
        $response->Token = (array_key_exists('Token', $headers)) ? $headers['Token'] : '';

        // Will dump a beauty json :3
        return $response;
    }

    static function JuristicAPI($JuristicID, $TokenIndustry)
    {//ดึงข้อมูลนิติบุคคล (DBD) ผ่านกระทรางอุตสาหกรรม

        $response = (object)[]; //Result Response

        $config = HP::getConfig();
        $url = $config->industry_juristic_url . $JuristicID; //URL

        $header = array("Token: $TokenIndustry");

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Disable SSL verification host
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Add Header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Execute
        $response = json_decode(curl_exec($ch));

        //get Status Code
        @$response->HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Closing
        curl_close($ch);

        // Will dump a beauty json :3
        return $response;
    }

    static function PersonalAPI($PersonalID, $TokenIndustry)
    {//ดึงข้อมูลบุคคลธรรมดา (DOPA) ผ่านกระทรางอุตสาหกรรม

        $response = (object)[]; //Result Response

        $config = HP::getConfig();
        $url = $config->industry_personal_url . $PersonalID; //URL

        $header = array("Token: $TokenIndustry");

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Disable SSL verification host
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Add Header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Execute
        $response = json_decode(curl_exec($ch));

        //get Status Code
        @$response->HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Closing
        curl_close($ch);

        // Will dump a beauty json :3
        return $response;
    }

    // สร้างฟังก์ชั้นแยกตัวเลข ออกจากตัวแปรข้อความ
    static function extract_int($str)
    {

        $strings = str_split((string)$str, 1);

        $results = [];
        foreach ($strings as $key => $string) {
            if ($string === '0' || $string === '1' || $string === '2' || $string === '3' || $string === '4' || $string === '5' || $string === '6' || $string === '7' || $string === '8' || $string === '9') {
                $results[] = $string;
            }
        }

        return implode('', $results);
    }

    static function extract_header($header_input)
    {

        $headers = [];
        $data = explode("\n", $header_input);
        $headers['status'] = $data[0];
        array_shift($data);

        foreach ($data as $part) {
            $middle = explode(":", $part);
            if (count($middle) >= 2) {
                $headers[trim($middle[0])] = trim($middle[1]);
            }
        }

        return $headers;

    }

    static function SaveToIndustryJuristic($juristic, $AgentID)
    {//บันทึกลงตารางข้อมูลนิติบุคคล

        unset($juristic->HttpCode);
        $juristic = (array)$juristic;
        $juristic['AgentID'] = $AgentID;//ผู้เรียกดูข้อมูล
        $juristic['committeeInformationType'] = array_key_exists('committeeInformationType', $juristic) ? json_encode($juristic['committeeInformationType']) : '[]';
        $juristic['addressInformationType'] = array_key_exists('addressInformationType', $juristic) ? json_encode($juristic['addressInformationType']) : '[]';
        $juristic['authorizeDescriptionType'] = array_key_exists('authorizeDescriptionType', $juristic) ? json_encode($juristic['authorizeDescriptionType']) : '[]';
        $juristic['standardObjectiveType'] = array_key_exists('standardObjectiveType', $juristic) ? json_encode($juristic['standardObjectiveType']) : '[]';

        return IndustryJuristic::create($juristic);

    }

    static function SaveToIndustryPersonal($personal, $AgentID)
    {//บันทึกลงตารางข้อมูลบุคคลธรรมดา

        unset($personal->HttpCode);
        $personal = (array)$personal;
        $personal['AgentID'] = $AgentID;//ผู้เรียกดูข้อมูล

        return IndustryPersonal::create($personal);

    }





}


function convert(string $number): string
{
    $values = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
    $places = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
    $exceptions = ['หนึ่งสิบ' => 'สิบ', 'สองสิบ' => 'ยี่สิบ', 'สิบหนึ่ง' => 'สิบเอ็ด'];

    $output = '';

    foreach (str_split(strrev($number)) as $place => $value) {
        if ($place % 6 === 0 && $place > 0) {
            $output = $places[6].$output;
        }

        if ($value !== '0') {
            $output = $values[$value].$places[$place % 6].$output;
        }
    }

    foreach ($exceptions as $search => $replace) {
        $output = str_replace($search, $replace, $output);
    }

    return $output;
}
