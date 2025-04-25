<?php

namespace App\Http\Controllers\FuntionCenter;

use HP;
use App\User;
use stdClass;
use App\Http\Requests;
use App\Models\Basic\Tis;
use Illuminate\Support\Str;
use App\Models\Basic\Prefix;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Basic\TisiLicense;
use Illuminate\Support\Facades\DB;
use App\Models\Basic\SubDepartment;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Law\Basic\LawBookType;

use App\Models\Law\File\AttachFileLaw;
use Illuminate\Support\Facades\Cookie;
use App\Models\Law\Basic\LawDepartment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Law\Books\LawBookManageVisit;


class LawController extends Controller
{

    public function SaveVisitBook(Request $request)
    {

        if( !empty($request->get('law_book_manage_id')) ){
            $sessionId = Session::getId();

            $requestData['section_id'] = $sessionId;
            $requestData['law_book_manage_id'] = $request->get('law_book_manage_id');
            $requestData['system_type'] = 2;
            $requestData['action'] = 1;
            $requestData['visit_at'] = date('Y-m-d H:i:s');
            LawBookManageVisit::create($requestData);
        }

    }

    public function SaveVisitDonwload(Request $request)
    {

        $id = $request->get('id');
        $law_book_manage_id =  $request->get('law_book_manage_id');
        $attach = AttachFileLaw::where('id', $id )->first();

        if( !empty($attach) ){
            $sessionId = Session::getId();

            $requestData['section_id'] = $sessionId;
            $requestData['law_book_manage_id'] = $law_book_manage_id;
            $requestData['system_type'] = 2;
            $requestData['action'] = 2;
            $requestData['visit_at'] = date('Y-m-d H:i:s');
            LawBookManageVisit::create($requestData);

        }

        $url = HP::getFileStorage($attach->url);

        return redirect($url);

    }

    public function LawBookType(Request $request)
    {
        $group = $request->get('id');

        if( $group === 'all' ){
            $data = LawBookType::where('state',1)->select('id', 'title')->get();
        }else{
            $data = LawBookType::where('book_group_id',  $group )->where('state',1)->select('id', 'title')->get();
        }

        return response()->json($data);
    }

    public function SubDepartments(Request $request)
    {
        $did = $request->get('id');
            $data = SubDepartment::where('did',  $did )->select('sub_id', 'sub_departname')->get();

        return response()->json($data);
    }

    public function UserDepartments(Request $request)
    {
        $did = $request->get('id');
        $role = $request->get('role');
        $sub_id = SubDepartment::where('did',  $did )->select('sub_id');
        $user =  User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->pluck('runrecno');


        if(!empty($user) && count($user) > 0){
            $user =  User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->where('role',$role)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->get();
        }else{
            $user =  User::where('status', 1)->whereIn('reg_subdepart',$sub_id)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->get();
        }

        return response()->json($user);
    }


    public function LawUserData($reg_subdepart)
    {
        $data = User::where('status', 1)->where('reg_subdepart',$reg_subdepart)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->get();

        return response()->json($data);
    }

    public function SearchStandardsTb3(Request $request){//ค้นหามาตรฐาน

        $search_query = $request->get('searchTerm');
        $searchTerm = str_replace(' ', '', $search_query);
        $data_std =  Tis::where(function($query) use($searchTerm){
                                    $query->Where(DB::raw("CONCAT( REPLACE(tb3_Tisno,' ',''),' :', REPLACE(tb3_TisThainame,' ','') )"), 'LIKE', "%".$searchTerm."%")
                                            ->OrWhere(DB::raw("CONCAT( REPLACE(tb3_Tisno,' ',''),' :', REPLACE(tb3_TisEngname,' ','') )"), 'LIKE', "%".$searchTerm."%");
                                })
                                ->select( DB::raw('tb3_TisAutono AS id') , DB::raw('tb3_Tisno AS tis_no'), DB::raw('tb3_TisThainame AS title') )
                                ->get();

        $data_list = [];

        foreach($data_std as $datas){

            $tis_tisno =  ($datas->tis_no ).' : '.($datas->title);

            $data = new stdClass;
            $data->id = $datas->id;
            $data->text = $tis_tisno;
            $data_list[] = $data;
        }

        echo json_encode($data_list,JSON_UNESCAPED_UNICODE);

    }

    public function SearchLicenseTb4(Request $request)
    {
        $search_query = $request->get('searchTerm');
        $searchTerm = str_replace(' ', '', $search_query);
        $data_resulte = TisiLicense::Where(function($query) use($searchTerm) {
                                            $query->where('tbl_licenseNo', 'LIKE' ,'%'.$searchTerm.'%')
                                                    ->OrWhere(DB::raw("REPLACE(`tbl_tradeName`, ' ', '')"), 'LIKE', "%".$searchTerm."%")
                                                    ->OrWhere(DB::raw("REPLACE(`tbl_taxpayer`, ' ', '')"), 'LIKE', "%".$searchTerm."%");
                                        })
                                        ->where(function($query) {
                                            $query->WhereNotNull('tbl_taxpayer');
                                        })
                                        ->where(function($query) {
                                            $query->where('tbl_licenseStatus',1);
                                        })
                                        ->select(
                                            'Autono', 'tbl_licenseNo', 'tbl_tradeName', 'tbl_taxpayer'   
                                        )
                                        ->get();

        $data_list = [];

        foreach($data_resulte as $datas){

            $data = new stdClass;
            $data->id = $datas->Autono;
            $data->text = $datas->tbl_licenseNo.' | '.$datas->tbl_tradeName;
            $data_list[] = $data;
        }

        echo json_encode($data_list,JSON_UNESCAPED_UNICODE);
    }

    public function LawDepartmentOther(Request $request)
    {
            $id = $request->get('id');
            $department = LawDepartment::where('id',$id )->first();
            $msg =  (!empty($department->other) && $department->other==1)?true:false;

        return response()->json($msg);
    }

    public function UploadFileTemp(Request $request)
    {
        $requestData = $request->all();
        if ($request->hasFile('attachment')) {

            $path_temp = 'Temp-file-law/';

            //ลบไฟล์
            $all_files = Storage::disk('uploads')->allFiles($path_temp);
            foreach( $all_files AS $ifile ){

                $time = Storage::disk('uploads')->lastModified($ifile);
             
                $remain = intval( (strtotime(date("Y-m-d H:i:s")) - strtotime($time)) );
                $wan = floor($remain/86400); // วัน
                $l_wan = $remain%86400;
                $hour = floor($l_wan/3600); // ชั่วโมง
                $l_hour = $l_wan%3600;
                $minute = floor($l_hour/60);// นาที
                $second = $l_hour%60;
                if( round($minute) > 300 ){ // มากกว่า 300 นาที
                    Storage::disk('uploads')->delete($ifile);
                }
            }

            //บันทึกลง Temp
            $file      = $request->file('attachment');
            $file_name = uniqid().'-'.date('Ymd_hms').'.'.$file->getClientOriginalExtension();

            $path      = Storage::putFileAs( $path_temp, $file,  str_replace(" ","",$file_name) );

            $mgs       = new stdClass;
            $mgs->url  = HP::getFileStorage($path);
            $mgs->path = $path;

            return response()->json($mgs);
        }
    }
    
    // เช็คเลข 13 หลัก
    public function get_taxid(Request $req)
    {

    if(!empty($req->tax_id) && HP::check_number_counter($req->tax_id, 13)){
        $entity  =  self::CheckLegalEntity($req->tax_id);   // นิติบุคคล
        if($entity != 'false' && !in_array($entity,[1,2,3])){
            $response['status']            = 'หมายเลข  '. $req->tax_id . ' เป็นนิติบุคคล ไม่สามารถดำเนินการได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>'.$entity.'</u>';
            $response['check_api']         = true;
            $response['type']              = 1;
        }else    if(in_array($entity,[1,2,3])){
            $response['status']            = 'หมายเลข ' . $req->tax_id .' เป็นนิติบุคคล ท่านต้องการทำรายการต่อเป็นประเภทนิติบุคคลหรือไม่';
            $response['check_api']         = true;
            $response['type']              = 1;
        }else{
            $person = $this->getPerson($req->tax_id, $req->ip);  // บุคคลธรรมดา
            if(is_null($person)){//ไม่พบข้อมูลในทะเบียนราษฎร์
                $faculty = self::getFaculty($req->tax_id);
                if($faculty == 'คณะบุคคล'){
                    $response['status']    =  'หมายเลข ' . $req->tax_id .' เป็นคณะบุคคล  ท่านต้องการทำรายการต่อเป็นประเภทคณะบุคคลหรือไม่';
                    $response['check_api'] = true;
                    $response['type']      = 3;
                }else{
                    $response['status']    = false;
                    $response['check_api'] = false;
                    $response['type']      = 3;
                }
            }elseif($person->statusOfPersonCode == '1'){//เสียชีวิต
                $response['status']         =  'หมายเลข  '. $req->tax_id . ' เป็นเลขประจำตัวประชาชน ไม่สามารถดำเนินการได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>เสียชีวิต</u>';
                $response['check_api']      = true;
                $response['type']           = 2;
                $response['person']         = 1;
            }else{
                $response['status']         =  'หมายเลข  '. $req->tax_id . ' เป็นบุคคลธรรมดา ท่านต้องการทำรายการต่อเป็นประเภทบุคคลธรรมดาหรือไม่';
                $response['check_api']      = true;
                $response['type']           = 2;
                $response['person']         = 0;
            }
        }
    }else{
        $response['check_api']      = false;
    }

        return response()->json($response);
    }

    public function CheckLegalEntity($tax_number)
    {

        $config = HP::getConfig();

            $response = 'false';
            $url = $config->tisi_api_corporation_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';
            $data = array(
                    'val' => $tax_number,
                    'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
                    'Refer' => 'center.tisi.go.th'
                    );
            $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    )
            );
            if(strpos($url, 'https')===0){//ถ้าเป็น https
                $options["ssl"] = array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                  );
            }
            $context  = stream_context_create($options);
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            if(!empty($api->JuristicName_TH)){
                // $response = 'true';
                $juristic_status = ['ยังดำเนินกิจการอยู่' => '1', 'ฟื้นฟู' => '2', 'คืนสู่ทะเบียน' => '3'];
                $status =  array_key_exists($api->JuristicStatus,$juristic_status) ? $juristic_status[$api->JuristicStatus] : $api->JuristicStatus ;  //สถานะนิติบุคคล
                $response =  $status;


            }
            return $response;

    }

    public function getFaculty($tax_number)
    {

             $config = HP::getConfig();

            $response = 'false';
            $url = $config->tisi_api_faculty_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
            $data = array(
                    'val' => $tax_number,
                    'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
                    'Refer' => 'center.tisi.go.th'
                    );
            $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    )
            );
            if(strpos($url, 'https')===0){//ถ้าเป็น https
                $options["ssl"] = array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                  );
            }
            $context  = stream_context_create($options);
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            if(!empty($api->vBranchTitleName)){
                $response =  $api->vBranchTitleName;
            }
            return $response;

    }

    private function getPerson($tax_id, $ip){

        $person = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';

        $data = array(
                'val'   => $tax_id,
                'IP'    => $ip,
                'Refer' =>  'center.tisi.go.th'
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                )
        );
        if(strpos($url, 'https')===0){//ถ้าเป็น https
            $options["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
        $context  = stream_context_create($options);
        $json_data = file_get_contents($url, false, $context);
        $api = json_decode($json_data);
        if(!empty($api->firstName)){
            $person = $api;
        }

        return $person;
    }

    public function get_tax_number(Request $req)
    {
        $response = [];
        $person = HP::check_number_counter($req->tax_id, 13) ? $this->getPerson($req->tax_id, $req->ip) : null ;
        if(is_null($person)){//ไม่พบข้อมูลในทะเบียนราษฎร์
            $response['person'] =  'ขออภัยเลขประจำตัวประชาชน '. $req->tax_id . ' ไม่พบในทะเบียนราษฎร์กรุณาติดต่อเจ้าหน้าที่';
            $response['check'] = false;
        }elseif($person->statusOfPersonCode == '1'){//เสียชีวิต
            $response['person'] =  'เลขประจำตัวประชาชน '. $req->tax_id . ' ไม่สามารถลงทะเบียนได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>เสียชีวิต</u>';
            $response['check'] = false;
        }else{
            $response['person'] =   true;
        }

        return response()->json($response);
    }



    public function datatype(Request $req)
    {
        $config = HP::getConfig();

        $response = [];
        if($req->applicanttype_id == 1){ // การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก 0105553080958

            if(HP::check_number_counter($req->tax_id, 14)){//เป็นเลข 14 หลัก

                $url = $config->tisi_api_factory_url;//'https://www3.tisi.go.th/moiapi/srv.asp?pid=4';
                $data = array(
                            'val'    => $req->tax_id,
                            'IP'     => $req->ip,    // IP Address,
                            'Refer'  => 'center.tisi.go.th'
                            );
                $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded",
                            'method'  => 'POST',
                            'content' => http_build_query($data),
                        )
                );
                if(strpos($url, 'https')===0){//ถ้าเป็น https
                    $options["ssl"] = array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false,
                                      );
                }
                $context  = stream_context_create($options);
                $json_data = file_get_contents($url, false, $context);
                $api = json_decode($json_data);

                if(!empty($api->result)){ // Start   14 หลัก
                    $result                        = $api->result[0];
                    $response['applicanttype_id']  = 1;       // ประเภทผู้ประกอบการ
                    $response['JuristicType']      = $result->FID ;
                    $response['prefix_id']         =   ''  ;        // คำนำหน้า
                    $response['juristic_status']   = '';
                    $response['tax_id']            = $result->FID ?? '';        // Username สำหรับเข้าใช้งาน
                    $response['name']              = $result->FNAME ?? '';
                    $response['name_last']         = '';
                    $response['RegisterDate']       = !empty($result->STARTDATE) ? HP::revertDate(date('Y-m-d', strtotime($result->STARTDATE)),false) : '';
                    $response['address']            =  $result->FADDR ?? ''; // ที่อยู่
                    $response['moo']                =  $result->FMOO ?? ''; //  หมู่
                    $response['soi']                =  $result->SOI ?? ''; // ซอย
                    $response['road']               =  $result->ROAD ?? ''; //  ถนน
                    $response['ampur']              =  $result->AMPNAME ?? ''; // แขวง/อำเภอ
                    $response['tumbol']             =  $result->TUMNAME ?? ''; //  ตำบล/แขวง
                    $response['province']           =  $result->PRONAME ?? ''; // จังหวัด
                    $zipcode  = HP::getZipcode($result->TUMNAME,$result->AMPNAME, $result->PRONAME);

                    $address_id = HP::GetIDAddress($result->TUMNAME,$result->AMPNAME, $result->PRONAME);
                    $response['ampur_id']              =  $address_id->district_id ?? ''; // id แขวง/อำเภอ
                    $response['tumbol_id']             =  $address_id->subdistrict_id ?? ''; // id ตำบล/แขวง
                    $response['province_id']           =  $address_id->province_id ?? ''; // id จังหวัด

                    if(!empty($zipcode)){
                        $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                    }else{
                        $response['zipcode']            =  ''; // รหัสไปรษณีย์
                    }
                    $response['country_code']   =  '';  // รหัสประเทศ

                    $response['phone']              =  $result->Phone ?? ''; // โทรศัพท์
                    $response['email']              =  $result->Email ?? ''; // อีเมล

                }

            }elseif(HP::check_number_counter($req->tax_id, 13)){
                    $url = $config->tisi_api_corporation_url;//'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';
                    $data = array(
                            'val'   => $req->tax_id,
                            'IP'    => $req->ip,       // IP Address,
                            'Refer' => 'center.tisi.go.th'
                            );
                    $options = array(
                            'http' => array(
                                'header'  => "Content-type: application/x-www-form-urlencoded",
                                'method'  => 'POST',
                                'content' => http_build_query($data),
                            )
                    );
                    if(strpos($url, 'https')===0){//ถ้าเป็น https
                        $options["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $context  = stream_context_create($options);
                    $json_data = file_get_contents($url, false, $context);
                    $api = json_decode($json_data);

                    $data_prefix                   = ['บริษัทจำกัด'=>'1','บริษัทมหาชนจำกัด'=>'2','ห้างหุ้นส่วนจำกัด'=>'3','ห้างหุ้นส่วนสามัญนิติบุคคล'=>'4'];
                    $juristic_status               = ['ยังดำเนินกิจการอยู่'=>'1', 'ฟื้นฟู'=>'2', 'คืนสู่ทะเบียน'=>'3'];
                if(!empty($api->JuristicName_TH)){ // Start การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก
                    $response['applicanttype_id']  = 1;       // ประเภทผู้ประกอบการ
                    $response['JuristicType']      =  $api->JuristicType ;
                    $response['prefix_id']         =  array_key_exists($api->JuristicType,$data_prefix) ? $data_prefix[$api->JuristicType] : ''  ;        // คำนำหน้า
                    $response['juristic_status']   =  array_key_exists($api->JuristicStatus,$juristic_status) ? $juristic_status[$api->JuristicStatus] : $api->JuristicStatus ;  //สถานะนิติบุคคล
                    $response['tax_id']            = $api->JuristicID ?? '';        // Username สำหรับเข้าใช้งาน
                    if(in_array($api->JuristicType,['บริษัทจำกัด','บริษัทมหาชนจำกัด'])){
                        $response['name']              = 'บริษัท '.$api->JuristicName_TH ?? '';
                    }else{
                        $response['name']              = $api->JuristicName_TH ?? '';
                    }

                    $response['name_last']         = '';
                    $response['RegisterDate']      = !empty($api->RegisterDate) ? substr($api->RegisterDate,6) .'/'.substr($api->RegisterDate,4,-2).'/'.substr($api->RegisterDate,0,4) : '';

                    if(!empty($api->CommitteeInformations)){  // ข้อมูลคณะกรรมการ
                        $prefixs                            = Prefix::pluck('id', 'initial');
                        $informations                       =  min($api->CommitteeInformations);
                        $response['first_name']             =  $informations->FirstName ?? ''; // ชื่อ
                        $response['last_name']              =  $informations->LastName ?? ''; // สกุล
                        if($informations->Title == 'น.ส.'){
                            $response['contact_prefix_name']    =   '3'; // คำนำหน้า
                        }else{
                            $response['contact_prefix_name']    =  array_key_exists($informations->Title,$prefixs) ? $prefixs[$informations->Title] : ''; // คำนำหน้า
                        }

                    }else{
                        $response['first_name']             =  ''; // ชื่อ
                        $response['last_name']              =  ''; // สกุล
                        $response['contact_prefix_name']    =  ''; // คำนำหน้า
                    }

                if( count($api->AddressInformations) > 0){  // in_array($api->JuristicType,['บริษัทจำกัด']) &&
                    $address = max($api->AddressInformations);
                    $response['address']            =  $address->AddressNo ?? ''; // ที่อยู่
                    $response['moo']                =  $address->Moo ?? ''; //  หมู่
                    $response['soi']                =  $address->Soi ?? ''; // ซอย
                    $response['road']               =  $address->Road ?? ''; //  ถนน
                    $response['ampur']              =  $address->Ampur ?? ''; // แขวง/อำเภอ
                    $response['tumbol']             =  $address->Tumbol ?? ''; //  ตำบล/แขวง
                    $response['province']           =  $address->Province ?? ''; // จังหวัด

                    //ค้นไอดีจากชื่อ
                    $address_id = HP::GetIDAddress($address->Tumbol,$address->Ampur , $address->Province);
                    $response['ampur_id']              =  $address_id->district_id ?? ''; // id แขวง/อำเภอ
                    $response['tumbol_id']             =  $address_id->subdistrict_id ?? ''; // id ตำบล/แขวง
                    $response['province_id']           =  $address_id->province_id ?? ''; // id จังหวัด

                    $zipcode  = HP::getZipcode($address->Tumbol,$address->Ampur , $address->Province);
                    if(!empty($zipcode)){
                        $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                    }else{
                        $response['zipcode']            =  ''; // รหัสไปรษณีย์
                    }

                    $response['phone']              =  $address->Phone ?? ''; // โทรศัพท์
                    $response['email']              =  $address->Email ?? ''; // อีเมล
                    $response['country_code']       =  '';  // รหัสประเทศ

                 }else{
                    $response['address']            =  ''; // ที่อยู่
                    $response['moo']                =  ''; //  หมู่
                    $response['soi']                =  ''; // ซอย
                    $response['road']               =  ''; //  ถนน
                    $response['tumbol']             =  ''; //  ตำบล/แขวง
                    $response['ampur']              =  ''; // แขวง/อำเภอ
                    $response['province']           =  ''; // จังหวัด
                    $response['zipcode']            =  ''; // รหัสไปรษณีย์
                    $response['phone']              =  ''; // โทรศัพท์
                    $response['email']              =  ''; // อีเมล
                    $response['country_code']       =  '';  // รหัสประเทศ
                 }
              }
            }

        }else if(in_array($req->applicanttype_id, [2, 4, 5]) && HP::check_number_counter($req->tax_id, 13)){
            $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';
            $data = array(
                    'val'   => $req->tax_id,
                    'IP'    => $req->ip,
                    'Refer' => 'center.tisi.go.th'
                    );
            $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    )
            );
            if(strpos($url, 'https')===0){//ถ้าเป็น https
                $options["ssl"] = array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                  );
            }
            $context  = stream_context_create($options);
            $json_data = file_get_contents($url, false, $context);
            $api = json_decode($json_data);
            if(!empty($api->firstName)){
                $prefixs                      = Prefix::pluck('id', 'initial')->toArray();
                $response['applicanttype_id']  = 2;       // ประเภทผู้ประกอบการ
                $response['JuristicType']      =  $api->titleName ;
                $response['prefix_id']         = array_key_exists($api->titleDesc,$prefixs) ? $prefixs[$api->titleDesc] : ''; // คำนำหน้า
                $response['juristic_status']   = '';
                $response['tax_id']            = $api->JuristicID ?? '';        // Username สำหรับเข้าใช้งาน
                $response['name']              = $api->firstName ?? '';
                $response['name_last']         = $api->lastName ?? '';
                $response['RegisterDate']      = !empty($api->dateOfBirth) ? substr($api->dateOfBirth,6) .'/'.substr($api->dateOfBirth,4,-2).'/'.substr($api->dateOfBirth,0,4) : '';
            }else{
                $response['applicanttype_id']  = 2;       // ประเภทผู้ประกอบการ
                $response['JuristicType']      = '';
                $response['prefix_id']         = '';        // คำนำหน้า
                $response['juristic_status']   = '';
                $response['tax_id']            = '';        // Username สำหรับเข้าใช้งาน
                $response['name']              = '';
                $response['name_last']         = '';
                $response['RegisterDate']      = '';
            }

            $url = $config->tisi_api_house_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=3';
            $data = array(
                    'val'       => $req->tax_id,
                    'IP'        => $req->ip,      // IP Address,
                    'Refer'     => 'center.tisi.go.th'
                    );
            $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    )
            );
            if(strpos($url, 'https')===0){//ถ้าเป็น https
                $options["ssl"] = array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                  );
            }
            $context  = stream_context_create($options);
            $json_data = file_get_contents($url, false, $context);
            $address = json_decode($json_data);
            if(!empty($address->houseNo)){
                $response['address']            =  $address->houseNo ?? ''; // ที่อยู่
                $response['moo']                =  $address->villageNo ?? ''; //  หมู่
                $response['soi']                =  $address->alleyDesc ?? ''; // ซอย
                $response['road']               =  $address->roadDesc ?? ''; //  ถนน
                $response['tumbol']             =  $address->subdistrictDesc ?? ''; //  ตำบล/แขวง
                $response['ampur']              =  $address->districtDesc ?? ''; // แขวง/อำเภอ
                $response['province']           =  $address->provinceDesc ?? ''; // จังหวัด

                //ค้นไอดีจากชื่อ
                $address_id = HP::GetIDAddress($address->subdistrictDesc,$address->districtDesc, $address->provinceDesc);
                $response['ampur_id']              =  $address_id->district_id ?? ''; // id แขวง/อำเภอ
                $response['tumbol_id']             =  $address_id->subdistrict_id ?? ''; // id ตำบล/แขวง
                $response['province_id']           =  $address_id->province_id ?? ''; // id จังหวัด

                $zipcode  = HP::getZipcode($address->subdistrictDesc,$address->districtDesc, $address->provinceDesc);
                if(!empty($zipcode)){
                    $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                }else{
                    $response['zipcode']            =  ''; // รหัสไปรษณีย์
                }
                $response['phone']              =  ''; // โทรศัพท์
                $response['email']              =  ''; // อีเมล

            }else{
                $response['address']            =  ''; // ที่อยู่
                $response['moo']                =  ''; //  หมู่
                $response['soi']                =  ''; // ซอย
                $response['road']               =  ''; //  ถนน
                $response['tumbol']             =  ''; //  ตำบล/แขวง
                $response['ampur']              =  ''; // แขวง/อำเภอ
                $response['province']           =  ''; // จังหวัด
                $response['zipcode']            =  ''; // รหัสไปรษณีย์
                $response['phone']              =  ''; // โทรศัพท์
                $response['email']              =  ''; // อีเมล
                $response['country_code']   =  '';  // รหัสประเทศ
            }
        }else if($req->applicanttype_id == 3 && HP::check_number_counter($req->tax_id, 13)){
                $url = $config->tisi_api_faculty_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
                $data = array(
                            'val'   => $req->tax_id,
                            'IP'    => $req->ip,       // IP Address,
                            'Refer' => 'center.tisi.go.th'
                            );
                    $options = array(
                            'http' => array(
                                'header'  => "Content-type: application/x-www-form-urlencoded",
                                'method'  => 'POST',
                                'content' => http_build_query($data),
                            )
                    );
                    if(strpos($url, 'https')===0){//ถ้าเป็น https
                        $options["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $context  = stream_context_create($options);
                    $json_data = file_get_contents($url, false, $context);
                    $api = json_decode($json_data);
                    if(!empty($api->vName)){
                        $response['juristic_status']   = '';
                        $response['applicanttype_id']  = 3;       // ประเภทผู้ประกอบการ
                        $response['prefix_id']         = $api->vBranchTitleName; // คำนำหน้า
                        $response['tax_id']            = $api->vNID ?? '';        // Username สำหรับเข้าใช้งาน
                        $response['name']              = $api->vBranchName ?? '';
                        $response['name_last']         =  '';
                        if(!empty($api->vBusinessFirstDate)){
                             $date =     explode("/",$api->vBusinessFirstDate);
                            $response['RegisterDate']      = $date[2].'/'.$date[1].'/'.($date[0] +543);
                        }else{
                            $response['RegisterDate']      =  '';
                        }

                        $response['address']            =  $api->vHouseNumber ?? '';  // ที่อยู่
                        $response['moo']                =  $api->vMooNumber ?? ''; //  หมู่
                        $response['soi']                =  $api->vSoiName ?? ''; // ซอย
                        $response['road']               =   ''; //  ถนน
                        $response['tumbol']             =  $api->vThambol ?? ''; //  ตำบล/แขวง
                        $response['ampur']              =  $api->vAmphur ?? ''; // แขวง/อำเภอ
                        $response['province']           =  $api->vProvince ?? ''; // จังหวัด
                        $response['zipcode']            =  $api->vPostCode ?? ''; // รหัสไปรษณีย์
                        $response['phone']              =  ''; // โทรศัพท์
                        $response['email']              =  ''; // อีเมล
                        $response['country_code']   =  '';  // รหัสประเทศ
                    }

        }
        return response()->json($response);
    }
}