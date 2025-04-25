<?php

use App\Models\Law\Log\LawNotify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Law\Log\LawLogWorking;
use Illuminate\Support\Facades\Storage;

use App\Models\Basic\Province AS Province;
use App\Models\Basic\Amphur AS District;
use App\Models\Basic\District AS Subdistrict;

use App\Models\Law\Log\LawSystemCategory;
use App\Models\Law\Log\LawNotifyUser;

use App\Models\Basic\Holiday;

use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;
use App\User;
class HP_Law
{

    static function MenuLaw()
    {
        $menu = []; 

        //ระบบบันทึกคดีผลิตภัณฑ์อุตสาหกรรม
        if (File::exists(base_path('resources/laravel-admin/new-menu-law.json'))) {
            $laravelMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/new-menu-law.json')));
            if( HP::check_group_menu($laravelMenuLaw) ){
                $menu[] = $laravelMenuLaw->menus[0];
            }
        }

        return $menu;

    }

    static public function CheckElicenseDB()
    {
        try {
            //เชื่อม DB elicense ได้
            DB::connection('mysql_elicense')->getPdo();
            return true;
        } catch (\Exception $e) {
            //เชื่อม DB elicense ไม่ได้
            return false; 
        }
    }

    static function dateRangeNotPublicHoliday( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {

        set_time_limit(0);
        
        $first = \Carbon\Carbon::parse( $first )->format('Y-m-d');
        $last   = \Carbon\Carbon::parse( $last )->format('Y-m-d');

        $dates = [];
        $PublicHoliday = [];
     
        //วันที่เริ่ม
        $current = strtotime( $first );
        //วันที่สิ้นสุด
        $last = strtotime( $last );

        $PublicHoliday = Holiday::whereNotNull('holiday_date')
                                    ->where(function( $query ) use($current, $format){
                                        $query->whereDate('holiday_date', '>=',  date( $format, $current ) );
                                    })
                                    ->where(function( $query ) use($last,$format){
                                        $query->whereDate('holiday_date', '<=', date( $format, $last ) );
                                    })
                                    ->pluck('holiday_date','holiday_date')
                                    ->toArray();
                                    
        while( $current <= $last ) {

            //วันที่
            $days = date( $format, $current );

            //วันเสาร์
            $saturday = date("Y-m-d", strtotime('saturday this week', strtotime($days))); 
            $PublicHoliday[  $saturday ] =  $saturday;

            //วันอาทิตย์
            $sunday =  date("Y-m-d", strtotime('sunday this week', strtotime($days)));  
            $PublicHoliday[  $sunday ] =  $sunday;

            //จับวันที่ที่ไม่ตรงกับวันหยุด
            if( !array_key_exists( $days,  $PublicHoliday  ) ){
                $dates[$days] = $days;
            }
            $current = strtotime( $step, $current );
            
        }

        return $dates;

    }

    public static function getMonthThais()//เดือนภาษาไทย
    {
        $thai_month_arr = array(
            "01" => "มกราคม",
            "02" => "กุมภาพันธ์",
            "03" => "มีนาคม",
            "04" => "เมษายน",
            "05" => "พฤษภาคม",
            "06" => "มิถุนายน",
            "07" => "กรกฎาคม",
            "08" => "สิงหาคม",
            "09" => "กันยายน",
            "10" => "ตุลาคม",
            "11" => "พฤศจิกายน",
            "12" => "ธันวาคม"
        );

        return $thai_month_arr;
    }

    public static function getLawStatus()
    {
        $law_status_arr = array(
            '99' => 'ยกเลิก',
            '0' => 'ฉบับร่าง',
            '1' => 'แจ้งงานคดีสำเร็จ',
            '2' => 'อยู่ระหว่างตรวจสอบข้อมูล',
            '3' => 'ขอข้อมูลเพิ่มเติม (ตีกลับ)',
            '4' => 'ข้อมูลครบถ้วน (อยู่ระหว่างพิจารณา)',
            '5' => 'พบการกระทำความผิด',
            '6' => 'ไม่พบการกระทำความผิด',
            '7' => 'ส่งเรื่องดำเนินคดี',
            '8' => 'แจ้งการกระทำความผิด',
            '9' => 'ยินยอมเปรียบเทียบปรับ',
            '10' => 'ไม่ยินยอมเปรียบเทียบปรับ',
            '11' => 'บันทึกผลแจ้งเปรียบเทียบปรับ',
            '12' => 'ตรวจสอบการชำระเงินแล้ว',
            '13' => 'ดำเนินการกับใบอนุญาต',
            '14' => 'ดำเนินการกับผลิตภัณฑ์',
            '15' => 'ดำเนินการเสร็จสิ้น'
        );

        return $law_status_arr;
    }

    
    public static function RangeDate(){

        $start = 1;
        $end   = 31;

        $range =  range( $start , $end );

        $data = [];
        foreach( $range as $list){

            $strlen = strlen($list);

            if( $strlen == 1){
                $list = "0".$list;

                $data[ (string)$list ] = $list;
            }else{
                $data[ (string)$list ] = $list;
            }
            
        }
        return $data;
    }

    // 
    public static function getInsertLawNotifyEmail($category_id = null, $ref_table = null, $ref_id = null, $name_system = null, $title = null, $content = null, $channel = null, $notify_type = null, $email = null, $created_by = null)
    {
        $law_notify                             = new  LawNotify;
        $law_notify->law_system_category_id     = $category_id;
        $law_notify->ref_table                  = $ref_table;
        $law_notify->ref_id                     = $ref_id;
        $law_notify->name_system                = $name_system;
        $law_notify->title                      = $title;
        $law_notify->content                    = $content;
        $law_notify->channel                    = $channel;
        $law_notify->notify_type                = $notify_type;
        $law_notify->email                      = $email;
        $law_notify->created_by                 = !empty($created_by)?$created_by:@auth()->user()->getKey();
        $law_notify->save();
        return $law_notify;
    }

    public static function InsertLawLogWorking($category_id = null, $ref_table = null, $ref_id = null, $ref_no = null, $ref_system = null, $title = null, $status = null, $remark = null)
    {
        $law_log_working                             = new  LawLogWorking;
        $law_log_working->law_system_category_id     = $category_id; //ระบบงานหลัก tb:law_system_categories 
        $law_log_working->ref_table                  = $ref_table;   //ชื่อตาราง 
        $law_log_working->ref_id                     = $ref_id;      //idของตาราง
        $law_log_working->ref_no                     = $ref_no;      //เลขที่อ้างอิง
        $law_log_working->ref_system                 = $ref_system;  //ชื่อระบบงานย่อย
        $law_log_working->title                      = $title;       //ชื่อเรื่อง,หัวข้อ
        $law_log_working->status                     = $status;      //สถานะ เก็บเป็น text
        $law_log_working->remark                     = $remark;      
        $law_log_working->created_by                 = !empty(auth()->user())?@auth()->user()->getKey():null;
        $law_log_working->save();
        return $law_log_working;
    }

    public static function  DeleteLawSingleFile($attach_files){
        if( !empty($attach_files) && !empty($attach_files->url) ){    
            if( HP::checkFileStorage( '/'.$attach_files->url) ){
                Storage::delete( '/'.$attach_files->url );
                $attach_files->delete();
            }
        }
    }

    //อ่านค่าเลขเป็นภาษาไทย
    public static function numwordsThai($number){   
        
        $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
        $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $number = $number + 0;
        $ret = "";
        if ($number == 0) return $ret;
        if ($number > 1000000)
        {
            $ret .= self::numwordsThai(intval($number / 1000000)) . "ล้าน";
            $number = intval(fmod($number, 1000000));
        }
        
        $divider = 100000;
        $pos = 0;
        while($number > 0)
        {
            $d = intval($number / $divider);
            $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
                ((($divider == 10) && ($d == 1)) ? "" :
                ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
            $ret .= ($d ? $position_call[$pos] : "");
            $number = $number % $divider;
            $divider = $divider / 10;
            $pos++;
        }
        return $ret;

    } 

    private static $data_provinces_lsit;//จังหวัด
    private static $data_districts_list;//อำเภอ
    private static $data_sub_districts_lsit;//ตำบล
    private static $data_zipcode_lsit;//รหัสไปรษณีย์
    private static $data_district_groups_list;//Group อำเภอ By ID จังหวัด
    private static $data_sub_district_groups_list;// Group ตำบล By ID อำเภอ

    public static function SeparateAddress($full_address)
    {

        $data = new stdClass;
        if( !empty($full_address) ){

            $address_no_check  =  ( mb_strpos( $full_address , 'เลขที่') !== false )?true:false;
            $moo_check         =  ( mb_strpos( $full_address , 'หมู่ที่') !== false ||  mb_strpos( $full_address , 'หมู่') !== false   )?true:false;
            $building_check    =  ( mb_strpos( $full_address , 'หมู่บ้าน/อาคาร') !== false )?true:false;
            $floor_check       =  ( mb_strpos( $full_address , 'ชั้น') !== false )?true:false;
            $soi_check         =  ( mb_strpos( $full_address , 'ตรอก/ซอย') !== false || mb_strpos( $full_address , 'ซอย') !== false  )?true:false;
            $road_check        =  ( mb_strpos( $full_address , 'ถนน') !== false )?true:false;

            $subdistrict_check =  ( mb_strpos( $full_address , 'ตำบล') !== false || mb_strpos( $full_address , 'แขวง') !== false )?true:false;
            $district_check    =  ( mb_strpos( $full_address , 'อำเภอ') !== false || mb_strpos( $full_address , 'เขต') !== false  )?true:false;
            $province_check    =  ( mb_strpos( $full_address , 'จังหวัด') !== false )?true:false;

            //หา เลขที่
            $positon_check = 0;
            if( $moo_check ==  true ){
                if( mb_strpos( $full_address , 'หมู่ที่') !== false ){
                    $positon_check = mb_strpos($full_address, 'หมู่ที่', 1); //ค้นหาตำแหน่ง string หมู่ที่
                }else if( mb_strpos( $full_address , 'หมู่') !== false ){
                    $positon_check = mb_strpos($full_address, 'หมู่', 1); //ค้นหาตำแหน่ง string หมู่
                }
            }else if( $moo_check ==  false && $building_check == true ){
                $positon_check = mb_strpos($full_address, 'หมู่บ้าน/อาคาร', 1); //ค้นหาตำแหน่ง string หมู่บ้าน/อาคาร
            }else if( $moo_check ==  false && $building_check == false && $floor_check == true ){
                $positon_check = mb_strpos($full_address, 'ชั้น', 1); //ค้นหาตำแหน่ง string ชั้น
            }else if( $moo_check ==  false && $building_check == false && $floor_check == false && $soi_check == true ){
                if( mb_strpos( $full_address , 'ตรอก/ซอย') !== false ){
                    $positon_check = mb_strpos($full_address, 'ตรอก/ซอย', 1); //ค้นหาตำแหน่ง string ตรอก/ซอย
                }else if( mb_strpos( $full_address , 'ซอย') !== false ){
                    $positon_check = mb_strpos($full_address, 'ซอย', 1); //ค้นหาตำแหน่ง string ซอย
                }
            }else if( $moo_check ==  false && $building_check == false && $floor_check == false && $soi_check == false && $road_check == true ){
                $positon_check = mb_strpos($full_address, 'ถนน', 1); //ค้นหาตำแหน่ง string ถนน
            }else if( $moo_check ==  false && $building_check == false && $floor_check == false && $soi_check == false && $road_check == false && $subdistrict_check == true ){
                if( mb_strpos( $full_address , 'ตำบล') !== false ){
                    $positon_check = mb_strpos($full_address, 'ตำบล', 1); //ค้นหาตำแหน่ง string ตำบล
                }else if( mb_strpos( $full_address , 'แขวง') !== false ){
                    $positon_check = mb_strpos($full_address, 'แขวง', 1); //ค้นหาตำแหน่ง string แขวง
                }
            }

            $address_no = null;
            if( mb_strpos( $full_address , 'เลขที่') !== false ){
                $address_no =  mb_substr($full_address, 0, $positon_check);
                $address_no = !empty( $address_no)?str_replace("เลขที่", "", $address_no):null;
                $address_no = trim( $address_no );
            }else{
                $address_no =  mb_substr($full_address, 0, $positon_check);
                $address_no = trim( $address_no );
            }
            $data->address_no = $address_no;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check));

            //หา หมู่ที่
            $positon_check2 = 0;
            if( $building_check == true ){
                $positon_check2 = mb_strpos($full_address, 'หมู่บ้าน/อาคาร', 1); //ค้นหาตำแหน่ง string หมู่บ้าน/อาคาร
            }else if( $building_check == false && $floor_check == true ){
                $positon_check2 = mb_strpos($full_address, 'ชั้น', 1); //ค้นหาตำแหน่ง string ชั้น
            }else if( $building_check == false && $floor_check == false && $soi_check == true ){
                if( mb_strpos( $full_address , 'ตรอก/ซอย') !== false ){
                    $positon_check2 = mb_strpos($full_address, 'ตรอก/ซอย', 1); //ค้นหาตำแหน่ง string ตรอก/ซอย
                }else if( mb_strpos( $full_address , 'ซอย') !== false ){
                    $positon_check2 = mb_strpos($full_address, 'ซอย', 1); //ค้นหาตำแหน่ง string ซอย
                }
            }else if( $building_check == false && $floor_check == false && $soi_check == false && $road_check == true ){
                $positon_check2 = mb_strpos($full_address, 'ถนน', 1); //ค้นหาตำแหน่ง string ถนน
            }

            $moo = null;
            if( mb_strpos( $full_address , 'หมู่ที่') !== false ){
                $moo =  mb_substr($full_address, 0, $positon_check2);
                $moo = !empty( $moo)?str_replace("หมู่ที่", "", $moo):null;
                $moo = trim( $moo );
            }else if( mb_strpos( $full_address , 'หมู่') !== false ){
                $moo =  mb_substr($full_address, 0, $positon_check2);
                $moo = !empty( $moo)?str_replace("หมู่", "", $moo):null;
                $moo = trim( $moo );
            }
            $data->moo = $moo;

            //หา หมู่บ้าน/อาคาร
            $positon_check3 = 0;
            if( $floor_check == true ){
                $positon_check3 = mb_strpos($full_address, 'ชั้น', 1); //ค้นหาตำแหน่ง string ชั้น
            }else if( $floor_check == false && $soi_check == true ){
                if( mb_strpos( $full_address , 'ตรอก/ซอย') !== false ){
                    $positon_check3 = mb_strpos($full_address, 'ตรอก/ซอย', 1); //ค้นหาตำแหน่ง string ตรอก/ซอย
                }else if( mb_strpos( $full_address , 'ซอย') !== false ){
                    $positon_check3 = mb_strpos($full_address, 'ซอย', 1); //ค้นหาตำแหน่ง string ซอย
                }
            }else if( $floor_check == false && $soi_check == false && $road_check == true ){
                $positon_check3 = mb_strpos($full_address, 'ถนน', 1); //ค้นหาตำแหน่ง string ถนน
            }

            $building = null;
            if( mb_strpos( $full_address , 'หมู่บ้าน/อาคาร') !== false ){
                $building =  mb_substr($full_address, 0, $positon_check3);
                $building = !empty( $building)?str_replace("หมู่บ้าน/อาคาร", "", $building):null;
                $building = trim( $building );
            }
            $data->building = $building;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check3));

            //หา ชั้น
            $positon_check4 = 0;
            if( $soi_check == true ){
                if( mb_strpos( $full_address , 'ตรอก/ซอย') !== false ){
                    $positon_check4 = mb_strpos($full_address, 'ตรอก/ซอย', 1); //ค้นหาตำแหน่ง string ตรอก/ซอย
                }else if( mb_strpos( $full_address , 'ซอย') !== false ){
                    $positon_check4 = mb_strpos($full_address, 'ซอย', 1); //ค้นหาตำแหน่ง string ซอย
                }
            }else if( $soi_check == false && $road_check == true ){
                $positon_check4 = mb_strpos($full_address, 'ถนน', 1); //ค้นหาตำแหน่ง string ถนน
            }

            $floor = null;
            if( mb_strpos( $full_address , 'ชั้น') !== false ){
                $floor =  mb_substr($full_address, 0, $positon_check4);
                $floor = !empty( $floor)?str_replace("ชั้น", "", $floor):null;
                $floor = trim( $floor );
            }
            $data->floor = $floor;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check4));

            //หา ตรอก/ซอย
            $positon_check5 = 0;
            if( $road_check == true ){
                $positon_check5 = mb_strpos($full_address, 'ถนน', 1); //ค้นหาตำแหน่ง string ถนน
            }

            $soi = null;          
            if( mb_strpos( $full_address , 'ตรอก/ซอย') !== false ){
                $soi =  mb_substr($full_address, 0, $positon_check5);
                $soi = !empty( $soi)?str_replace("ตรอก/ซอย", "", $soi):null;
                $soi = trim( $soi );
            }else if( mb_strpos( $full_address , 'ซอย') !== false ){
                $soi =  mb_substr($full_address, 0, $positon_check5);
                $soi = !empty( $soi)?str_replace("ซอย", "", $soi):null;
                $soi = trim( $soi );
            }

            $data->soi = $soi;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check5));
          
            //หา ถนน
            $positon_check6 = 0;
            if( $subdistrict_check == true ){
                if( mb_strpos( $full_address , 'ตำบล') !== false ){
                    $positon_check6 = mb_strpos($full_address, 'ตำบล', 1); //ค้นหาตำแหน่ง string ตำบล
                }else if( mb_strpos( $full_address , 'แขวง') !== false ){
                    $positon_check6 = mb_strpos($full_address, 'แขวง', 1); //ค้นหาตำแหน่ง string แขวง
                }
            }else if( $subdistrict_check == false && $district_check == true  ){
                if( mb_strpos( $full_address , 'อำเภอ') !== false ){
                    $positon_check6 = mb_strpos($full_address, 'อำเภอ', 1); //ค้นหาตำแหน่ง string อำเภอ
                }else if( mb_strpos( $full_address , 'เขต') !== false ){
                    $positon_check6 = mb_strpos($full_address, 'เขต', 1); //ค้นหาตำแหน่ง string เขต
                }
            }else if( $subdistrict_check == false && $district_check == false  && $province_check == true ){
                $positon_check6 = mb_strpos($full_address, 'จังหวัด', 1); //ค้นหาตำแหน่ง string แขวง
            }

            $road = null;
            if( mb_strpos( $full_address , 'ถนน') !== false ){
                $road =  mb_substr($full_address, 0, $positon_check6);
                $road = !empty( $road)?str_replace("ถนน", "", $road):null;
                $road = trim( $road );
            }
            $data->road = $road;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check6));

            //หา ตำบล/แขวง
            $positon_check7 = 0;
            if( $district_check == true  ){
                if( mb_strpos( $full_address , 'อำเภอ') !== false ){
                    $positon_check7 = mb_strpos($full_address, 'อำเภอ', 1); //ค้นหาตำแหน่ง string อำเภอ
                }else if( mb_strpos( $full_address , 'เขต') !== false ){
                    $positon_check7 = mb_strpos($full_address, 'เขต', 1); //ค้นหาตำแหน่ง string เขต
                }
            }else if( $district_check == false  && $province_check == true ){
                $positon_check7 = mb_strpos($full_address, 'จังหวัด', 1); //ค้นหาตำแหน่ง string จังหวัด
            }

            $subdistrict = null;
            if( mb_strpos( $full_address , 'ตำบล') !== false || mb_strpos( $full_address , 'แขวง') !== false ){
                $subdistrict =  mb_substr($full_address, 0, $positon_check7);
                $subdistrict = !empty( $subdistrict)?str_replace("ตำบล/แขวง", "", $subdistrict):null;
                $subdistrict = !empty( $subdistrict)?str_replace("ตำบล", "", $subdistrict):null;
                $subdistrict = !empty( $subdistrict)?str_replace("แขวง", "", $subdistrict):null;
                $subdistrict = trim( $subdistrict );
            }
            $data->subdistrict = $subdistrict;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check7));
           
            //จัดหวัด
            if( !is_array(self::$data_provinces_lsit) ){
                self::$data_provinces_lsit =  Province::select(DB::raw("TRIM(`PROVINCE_NAME`) AS PROVINCE_NAME"), 'PROVINCE_ID')->pluck( 'PROVINCE_NAME', 'PROVINCE_ID')->toArray();
            }
            $pro_array = self::$data_provinces_lsit;

            //หา อำเภอ/เขต
            $positon_check8 = 0;
            if( $province_check == true ){
                $positon_check8 = mb_strpos($full_address, 'จังหวัด', 1); //ค้นหาตำแหน่ง string จังหวัด
            }else{

                foreach( $pro_array as $pro_item ){

                    if( mb_strpos( $full_address , $pro_item ) !== false ){
                        $positon_check8 = mb_strpos($full_address, $pro_item, 1); //ค้นหาตำแหน่ง string ชื่อจังหวัด
                        break;
                    }

                }

            }
          
            $district = null;
            if( mb_strpos( $full_address , 'อำเภอ') !== false || mb_strpos( $full_address , 'เขต') !== false ){
                $district =  mb_substr($full_address, 0, $positon_check8);
                $district = !empty( $district)?str_replace("อำเภอ/เขต", "", $district):null;
                $district = !empty( $district)?str_replace("อำเภอ", "", $district):null;
                $district = trim( $district );
                if( mb_strpos( $full_address , 'กรุงเทพมหานคร') !== false ){
                    if( mb_strpos( $full_address , 'เขต') !== false ){
                        $district = !empty( $district)?str_replace("เขต", "", $district):null;
                    }
                }
                $district = trim( $district );
            }
            $data->district = $district;

            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check8));
            $positon_check9 = 0;
            $zipcode_district = null;
            if(  mb_strpos( $full_address , 'รหัสไปรษณีย์') !== false ){
                $positon_check9 = mb_strpos($full_address, 'รหัสไปรษณีย์', 1); //ค้นหาตำแหน่ง string รหัสไปรษณีย์
            }else{
                $zipcode_array = District::pluck('POSTCODE', 'AMPHUR_ID')->toArray();
                foreach( $zipcode_array as $zipcode_item ){

                    if( mb_strpos( $full_address , $zipcode_item ) !== false ){
                        $positon_check9 = mb_strpos($full_address, $zipcode_item, 1); //ค้นหาตำแหน่ง string  zipcode
                        $zipcode_district =  $zipcode_item;
                        break;
                    }

                }
            }

            //หา จังหวัด
            $province = null;
            if( mb_strpos( $full_address , 'จังหวัด') !== false ){

                if( $positon_check9 == 0){
                    $province =  $full_address;
                }else{
                    $province =  mb_substr($full_address, 0, $positon_check9);
                }

                $province = !empty( $province)?str_replace("จังหวัด", "", $province):null;
                if( mb_strpos( $province , 'รหัส') !== false ){
                    $province = !empty( $province)?str_replace("รหัส", "", $province):null;
                }
                $province = trim( $province );
            }else{
                if( $positon_check9 == 0){
                    $province = trim( $full_address );
                }else{
                    $province =  mb_substr($full_address, 0, $positon_check9);
                }
              
                $province = !empty( $province)?str_replace("จังหวัด", "", $province):null;
                if( mb_strpos( $province , 'รหัส') !== false ){
                    $province = !empty( $province)?str_replace("รหัส", "", $province):null;
                }
                $province = trim( $province );
            }

            $data->province = $province;
            //ข้อความที่เหลือ
            $full_address = trim(mb_substr($full_address , $positon_check9));
            //หา รหัสไปรษณีย์
            $zipcode = null;
            if(  mb_strpos( $full_address , 'รหัสไปรษณีย์') !== false ){
                $zipcode = !empty( $full_address)?str_replace("รหัสไปรษณีย์", "", $full_address):null;
                $zipcode = trim( $zipcode );
            }else{
                $zipcode = is_numeric( $full_address )? $full_address:$zipcode_district;
                $zipcode = trim( $zipcode );
            }
            $data->zipcode = $zipcode;

            //หา ID 
            if( !empty($data->subdistrict) && !empty($data->district) && !empty($data->province)  ){
                //จัดหวัด
                $provinces = $pro_array;

                //อำเภอ
                if( !is_array(self::$data_districts_list) ){
                    $districts = District::selectRaw("
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
                    $sub_districts = Subdistrict::select('DISTRICT_ID', DB::raw("TRIM(`DISTRICT_NAME`) AS DISTRICT_NAME"), 'AMPHUR_ID')->where(DB::raw("REPLACE(DISTRICT_NAME,' ','')"),  'NOT LIKE', "%*%")->get()->makeHidden(['districtname', 'provincename']);
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
              
                $subdistrict = array_key_exists($district, $sub_district_groups) ? array_search(  $data->subdistrict , $sub_district_groups[$district]) : false;

                $province_ids = array_search($data->province, $provinces);
                $district_ids = array_search($data->district, $districts);
                $subdistrict_ids = array_search( $data->subdistrict , $sub_districts);
              
                if($province_ids!==false){
                    $data->province_id = $province_ids;
                }

                if($province_ids!==false && $district_ids!==false){
                    $district_ids = array_key_exists($province_ids, $district_groups) ? array_search($data->district, $district_groups[ $province_ids ]) : false;
                    $data->district_id = ( $district_ids!==false ? $district_ids : null );
                }

                if($district_ids!==false && $subdistrict_ids!==false){
                    $subdistrict_ids = array_key_exists($district_ids, $sub_district_groups) ? array_search($data->subdistrict, $sub_district_groups[ $district_ids ]) : false;
                    $data->subdistrict_id = ( $subdistrict_ids!==false ? $subdistrict_ids : null );
                }

                //กรณีไม่ รหัสไปรษณีย์ส่งมา
                if( empty( $data->zipcode ) && !empty($data->district_id) ){
                    $DISTRICT_ZIPCODE = District::find($data->district_id);
                    $data->zipcode = !empty($DISTRICT_ZIPCODE->POSTCODE)?$DISTRICT_ZIPCODE->POSTCODE:null;
                }
          

            }
    
        }
        return $data;
    }

    private static $bs_provinces_lsit;//จังหวัด
    private static $bs_provinces_en_lsit;//จังหวัด EN

    private static $bs_districts_list;//อำเภอ
    private static $bs_districts_en_list;//อำเภอ EN

    private static $bs_sub_districts_lsit;//ตำบล
    private static $bs_sub_districts_en_lsit;//ตำบล EN

    private static $bs_zipcode_lsit;//รหัสไปรษณีย์
    private static $bs_district_groups_list;//Group อำเภอ By ID จังหวัด
    private static $bs_sub_district_groups_list;// Group ตำบล By ID อำเภอ

    public static function GetDataAddress( $txt_sub = null, $txt_dis = null, $txt_pro = null, $txt_code = null  )
    {

   
        //จัดหวัด
        if( !is_array(self::$bs_provinces_lsit) ){
            $provinces                  = Province::select(DB::raw("TRIM(`PROVINCE_NAME`) AS PROVINCE_NAME"), DB::raw("TRIM(`PROVINCE_NAME_EN`) AS PROVINCE_NAME_EN"), 'PROVINCE_ID')->get();
            self::$bs_provinces_lsit    = $provinces->pluck('PROVINCE_NAME', 'PROVINCE_ID')->toArray();
            self::$bs_provinces_en_lsit = $provinces->pluck('PROVINCE_NAME_EN', 'PROVINCE_ID')->toArray();
        }
        $provincesTH = self::$bs_provinces_lsit;
        $provincesEN = self::$bs_provinces_en_lsit;

        //อำเภอ
        if( !is_array(self::$bs_districts_list) ){
            $districts = District::select(
                                            "AMPHUR_ID",
                                            "PROVINCE_ID",
                                            "POSTCODE",
                                            DB::raw("CASE WHEN POSITION('เขต' IN TRIM(`AMPHUR_NAME`)) = 1 THEN  TRIM(REPLACE(TRIM(`AMPHUR_NAME`), 'เขต', '')) WHEN POSITION('อำเภอ' IN TRIM(`AMPHUR_NAME`)) = 1 THEN  TRIM(REPLACE(TRIM(`AMPHUR_NAME`), 'อำเภอ', '')) ELSE  TRIM(`AMPHUR_NAME`) END AS AMPHUR_NAME"),
                                            DB::raw("CASE WHEN POSITION('Khet' IN TRIM(`AMPHUR_NAME_EN`)) = 1 THEN  TRIM(REPLACE(TRIM(`AMPHUR_NAME_EN`), 'Khet', '')) WHEN POSITION('Amphur' IN TRIM(`AMPHUR_NAME_EN`)) = 1 THEN  TRIM(REPLACE(TRIM(`AMPHUR_NAME_EN`), 'Amphur', '')) ELSE  TRIM(`AMPHUR_NAME_EN`) END AS AMPHUR_NAME_EN")
                                        )
                                        ->where(DB::raw("REPLACE(AMPHUR_NAME,' ','')"),  'NOT LIKE', "%*%")
                                        ->get();

            $district_group_tmps = $districts->groupBy('PROVINCE_ID')->toArray();

            $district_groups = [];
            foreach ($district_group_tmps as $key => $tmp) {
                $district_groups[$key] = collect($tmp)->pluck('AMPHUR_NAME', 'AMPHUR_ID')->toArray();
            }
            self::$bs_districts_list     = $districts->pluck('AMPHUR_NAME', 'AMPHUR_ID')->toArray();
            self::$bs_districts_en_list  = $districts->pluck('AMPHUR_NAME_EN', 'AMPHUR_ID')->toArray();
            self::$bs_zipcode_lsit       = $districts->pluck('POSTCODE', 'AMPHUR_ID')->toArray();


            self::$bs_district_groups_list = $district_groups;
        }

        $districtsTH     = self::$bs_districts_list;
        $districtsEN     = self::$bs_districts_en_list;
        $district_groups = self::$bs_district_groups_list;
        $zipcode_lsit    = self::$bs_zipcode_lsit;


        //ตำบล
        if( !is_array(self::$bs_sub_districts_lsit) ){
            $sub_districts           = Subdistrict::select(
                                                            'DISTRICT_ID',
                                                            'AMPHUR_ID',
                                                            DB::raw("TRIM(`DISTRICT_NAME`) AS DISTRICT_NAME"), 
                                                            DB::raw("TRIM(`DISTRICT_NAME_EN`) AS DISTRICT_NAME_EN")
                                                        )
                                                        ->where(DB::raw("REPLACE(DISTRICT_NAME,' ','')"),  'NOT LIKE', "%*%")
                                                        ->get()
                                                        ->makeHidden(['districtname', 'provincename']);

            $sub_district_group_tmps = collect($sub_districts->toArray())->groupBy('AMPHUR_ID')->toArray();
            $sub_district_groups     = [];
            foreach ($sub_district_group_tmps as $key => $tmp) {
                $sub_district_groups[$key] = collect($tmp)->pluck('DISTRICT_NAME', 'DISTRICT_ID')->toArray();
            }
            self::$bs_sub_districts_lsit    = $sub_districts->pluck('DISTRICT_NAME', 'DISTRICT_ID')->toArray();
            self::$bs_sub_districts_en_lsit = $sub_districts->pluck('DISTRICT_NAME_EN', 'DISTRICT_ID')->toArray();

            self::$bs_sub_district_groups_list = $sub_district_groups;

        }
        $sub_districtsTH     = self::$bs_sub_districts_lsit;
        $sub_districtsEN     = self::$bs_sub_districts_en_lsit;

        $sub_district_groups = self::$bs_sub_district_groups_list;

        $data    = new stdClass;
        if( !empty($txt_sub) || !empty($txt_dis) || !empty($txt_pro)  ){

            $txt_sub = is_numeric($txt_sub)?$txt_sub:trim($txt_sub);
            $txt_dis = is_numeric($txt_dis)?$txt_dis:trim($txt_dis);
            $txt_pro = is_numeric($txt_pro)?$txt_pro:trim($txt_pro);
   
            if( !is_numeric($txt_sub) ){
                if( !empty($txt_sub) && strpos( $txt_sub , "ตำบล" ) === 0 ){
                    $txt_sub =  !empty($txt_sub)?str_replace('ตำบล','',$txt_sub):null;
                }
            }
    
            if( !is_numeric($txt_dis) ){
                if( !empty($txt_dis) && strpos( $txt_dis , "อำเภอ/เขต" ) === 0 ){
                    $txt_dis =  !empty($txt_dis)?str_replace('อำเภอ/เขต','',$txt_dis):null;
                }else if( !empty($txt_dis) && strpos( $txt_dis , "เขต" ) === 0 ){
                    $txt_dis =  !empty($txt_dis)?str_replace('เขต','',$txt_dis):null;
                }else if( !empty($txt_dis) && strpos( $txt_dis , "อำเภอ" ) === 0 ){
                    $txt_dis =  !empty($txt_dis)?str_replace('อำเภอ','',$txt_dis):null;
                }
            }
    
            if( !is_numeric($txt_pro) ){
                if( strpos( $txt_pro , "จังหวัด" ) === 0 ){
                    $txt_pro =  !empty($txt_pro)?str_replace('จังหวัด','',$txt_pro):null;
                }
            }

            $province_ids    = is_numeric($txt_pro)?$txt_pro:array_search( $txt_pro, $provincesTH );
            $district_ids    = is_numeric($txt_dis)?$txt_dis:array_search( $txt_dis, $districtsTH );
            $subdistrict_ids = is_numeric($txt_sub)?$txt_sub:array_search( $txt_sub , $sub_districtsTH );

            if($province_ids!==false){
                $data->province_id      = $province_ids;
                $data->province_name    = array_key_exists($province_ids, $provincesTH)?$provincesTH[$province_ids]:null;
                $data->province_name_en = array_key_exists($province_ids, $provincesEN)?$provincesEN[$province_ids]:null;
            }else{
                $data->province_id      = null;
                $data->province_name    = null;
                $data->province_name_en = null;
            }

            if($province_ids!==false && $district_ids!==false){
                $district_ids           = array_key_exists($province_ids, $district_groups) ? array_search( $txt_dis , $district_groups[ $province_ids ]) : false;
                $data->district_id      = ( $district_ids!==false ? $district_ids : null );
                $data->district_name    = array_key_exists( $data->district_id, $districtsTH)?$districtsTH[  $data->district_id ]:null;
                $data->district_name_en = array_key_exists( $data->district_id, $districtsEN)?$districtsEN[  $data->district_id ]:null;
            }else{
                $data->district_id      = null;
                $data->district_name    = null;
                $data->district_name_en = null;
            }

            if($district_ids!==false && $subdistrict_ids!==false){
                $subdistrict_ids           = array_key_exists($district_ids, $sub_district_groups) ? array_search( $txt_sub , $sub_district_groups[ $district_ids ]) : false;
                $data->subdistrict_id      = ( $subdistrict_ids!==false ? $subdistrict_ids : null );
                $data->subdistrict_name    = array_key_exists( $data->subdistrict_id, $sub_districtsTH)?$sub_districtsTH[  $data->subdistrict_id ]:null;
                $data->subdistrict_name_en = array_key_exists( $data->subdistrict_id, $sub_districtsEN)?$sub_districtsEN[  $data->subdistrict_id ]:null;
            }else{
                $data->subdistrict_id      = null;
                $data->subdistrict_name    = null;
                $data->subdistrict_name_en = null;
            }

            if( $province_ids!==false && $district_ids!==false && !empty($data->district_id) && empty($txt_code) ){
                $data->zipcode =  array_key_exists( $data->district_id, $zipcode_lsit)?$zipcode_lsit[  $data->district_id ]:null;
            }else{
                $data->zipcode = !empty($txt_code)?$txt_code:null;
            }

        }

        return $data;

      
    }

    public static function TextBathFormat($number) {

        if(is_null($number) && empty($number)){
            return 'ศูนย์บาทถ้วน';
        }

        $numberstr = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
        $digitstr = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');

        $number = str_replace(",","",$number);
        $number = str_replace(" ","",$number);
        $number = str_replace("บาท","",$number);
        $number = str_replace(",","",$number); // ลบ comma
        $number = explode(".",$number); // แยกจุดทศนิยมออก

        // เลขจำนวนเต็ม
        $strlen = strlen($number[0]);
        $result = '';
        for($i=0;$i<$strlen;$i++) {
            $n = substr($number[0], $i,1);
            if($n!=0) {
                if($i==($strlen-1) AND $n==1){ $result .= 'เอ็ด'; }
                elseif($i==($strlen-2) AND $n==2){ $result .= 'ยี่'; }
                elseif($i==($strlen-2) AND $n==1){ $result .= ''; }
                else{ $result .= $numberstr[$n]; }
                $result .= $digitstr[$strlen-$i-1];
            }
        }
        
        if( !isset($number[1]) ){
            $number[1] = '00';
        }
        // จุดทศนิยม
        $strlen = strlen($number[1]);

        if ($strlen>2) { // ทศนิยมมากกว่า 2 ตำแหน่ง คืนค่าเป็นตัวเลข
            $result .= 'จุด';
            for($i=0;$i<$strlen;$i++) {
                $result .= $numberstr[(int)$number[1][$i]];
            }
        } else { // คืนค่าเป็นจำนวนเงิน (บาท)
            $result .= 'บาท';
            if ($number[1]=='0' OR $number[1]=='00' OR $number[1]=='') {
                $result .= 'ถ้วน';
            } else {
                // จุดทศนิยม (สตางค์)
                for($i=0;$i<$strlen;$i++) {
                    $n = substr($number[1], $i,1);

                    if($n!=0){
                        if($i==($strlen-1) AND $n==1){$result .= 'เอ็ด';}
                        elseif($i==($strlen-2) AND $n==2){$result .= 'ยี่';}
                        elseif($i==($strlen-2) AND $n==1){$result .= '';}
                        else{ $result .= $numberstr[$n];}
                        $result .= $digitstr[$strlen-$i-1];
                    }
                }
                $result .= 'สตางค์';
            }
        }


        return $result;

    }

    public static function TextBathFormatEn($number) {

        if(is_null($number) && empty($number)){
            return 'ZERO BATH';
        }

        $ones = array(
                        0 =>"ZERO",
                        1 => "ONE",
                        2 => "TWO",
                        3 => "THREE",
                        4 => "FOUR",
                        5 => "FIVE",
                        6 => "SIX",
                        7 => "SEVEN",
                        8 => "EIGHT",
                        9 => "NINE",
                        10 => "TEN",
                        11 => "ELEVEN",
                        12 => "TWELVE",
                        13 => "THIRTEEN",
                        14 => "FOURTEEN",
                        15 => "FIFTEEN",
                        16 => "SIXTEEN",
                        17 => "SEVENTEEN",
                        18 => "EIGHTEEN",
                        19 => "NINETEEN",
                        "014" => "FOURTEEN"
                    );
        $tens = array(
                        0 => "ZERO",
                        1 => "TEN",
                        2 => "TWENTY",
                        3 => "THIRTY",
                        4 => "FORTY",
                        5 => "FIFTY",
                        6 => "SIXTY",
                        7 => "SEVENTY",
                        8 => "EIGHTY",
                        9 => "NINETY"
                    );

        $hundreds = array(
                            "HUNDRED",
                            "THOUSAND",
                            "MILLION",
                            "BILLION",
                            "TRILLION",
                            "QUARDRILLION"
                        ); /*limit t quadrillion */

        $number = number_format($number,2,".",",");
        $num_arr = explode(".",$number);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholenum));
        krsort($whole_arr,1);
        $rettxt = "";

        foreach($whole_arr as $key => $i){

        while(substr($i,0,1)=="0")
            $i=substr($i,1,5);
            if($i < 20){
                /* echo "getting:".$i; */
                $rettxt .=  array_key_exists($i , $ones )? $ones[$i]:'';
            }elseif($i < 100){
                if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)];
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)];
            }else{
                if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)];
                if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $rettxt .= " ".$hundreds[$key]." ";
            }
        }

        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $ones[$decnum];
            }elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
        }
        return $rettxt;
    }

    public static function CategoryNotify(){
        $category = LawSystemCategory::withCount([
                        'law_notify' => function ($query) {
                            $query->whereDoesntHave('notify_user_by_user', function($query){
                                        $query->where('read_type','1')->where('user_register', Auth::user()->getKey() );
                                    })
                                    ->when(!auth()->user()->can('view_all-'.str_slug('law-notifys','-')), function($query) {//ดูได้เฉพาะรายการที่ได้รับ
                                        return $query->where(function($query){
                                                            $query->Where('email', 'LIKE', "%".(Auth::user()->reg_email)."%" );
                                                        });
                                    });
                        }  
                    ])
                    ->where(function($query){
                        $query->where('state_notify',1);
                    })
                    ->get();

        return $category;
    } 

    public static function GoogleCalendars( $year = null )
    {
        $year = !empty($year)?$year:date('Y');
        $list = [];

        //API Google Calendars
        $content = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/th.th%23holiday%40group.v.calendar.google.com/events?key=AIzaSyBZT-WGFjtXUWpCQodP2qNaTYPipkMKPSU');

        if( !empty($content) ){
            $query   = json_decode($content,true);
            if( !empty($query['items']) ){
                foreach( $query['items'] AS $item ){
                    if( !empty($item['start']['date']) && ( \Carbon\Carbon::parse( $item['start']['date'] )->format('Y')  ==  $year )){
                        $data            = new stdClass;
                        $data->title     = $item['summary'];
                        $data->startDate = !empty($item['start']['date'])?$item['start']['date']:null;
                        $data->endDate   = !empty($item['end']['date'])?$item['end']['date']:null;
                        $list[]          =  $data;
                    }
                }
            }
        }

        return $list;
    }

    public static function UserDepartments()
    {
         $list  = [];
         $defaults = ['7', '6', '5','2','1' ];   
        $subs = Department::select('did')->get();

        if(count($subs) > 0){
            foreach($subs as $sub){
                  $did = $sub->did;
                  $sub_id = SubDepartment::where('did',  $did )->select('sub_id');
                  $array1 =    [];
                foreach($defaults as $role){
                    $array4 = [];
                    $users =  User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno', 'position')->get();
                    if(count($users) > 0){
                    
                       foreach($users as $user){
                           $object                     = (object)[]; 
                           $object->id                 = $user->runrecno;
                           $object->name               = $user->name;
                           $object->position           = $user->position ?? '';
                           $array4[$user->runrecno]    = $object;
                       }
                    }
                       $array2 = [];
                      $users =  User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno', 'position')->get();
                     if(count($users) > 0){
                 
                        foreach($users as $user){
                            $object                     = (object)[]; 
                            $object->id                 = $user->runrecno;
                            $object->name               = $user->name;
                            $object->position           = $user->position ?? '';
                            $array2[$user->runrecno]    = $object;
                        }
                         
                     }
                        if(count($array2) > 0){
                            $array1[$role] =  $array2;
                        }else{
                            $array1[$role] =  $array4;
                        }
                   
                   
              }  

         
                    $list[$did]['role']     =  $array1  ;
                    // $list[$did]['not_role']  = $array3;
               
            }
        }

        return $list;
    }

}


