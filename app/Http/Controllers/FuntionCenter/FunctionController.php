<?php

namespace App\Http\Controllers\FuntionCenter;

use HP;
use HP_Law;
use App\User;
use stdClass;
use Carbon\Carbon;
use App\Http\Requests;
use App\Models\Basic\Tis;
use App\Models\Esurv\Soko;
use Illuminate\Support\Str;
use App\Models\Basic\Amphur;
use App\Models\Tis\Standard;
use Illuminate\Http\Request;

use App\Models\Basic\Zipcode;

use Illuminate\Http\Response;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\Models\Funtion\SendMail;

use App\Models\Basic\TisiLicense;

use Illuminate\Support\Facades\DB;

use App\Mail\Personal\SendMailUser;

use App\Models\Log\LogNotification;
use App\Http\Controllers\Controller;
use App\Models\Sso\User AS SSO_USER;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Models\Law\Cases\LawCasesForm;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Storage;
use App\Models\Law\Cases\LawCasesStaffList;
use App\Models\Section5\ApplicationLabStatus;
use App\Models\Section5\ApplicationIbcbStatus;
use App\Models\Section5\ApplicationInspectorStatus;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;
class FunctionController extends Controller
{
    public function __construct()
    {
        set_time_limit(0);
    }

    public $attach_path = 'SendMails/File/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function infomation(Request $request){

        $Query = new SendMail;
        $filter = [];
        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['perPage'] = $request->get('perPage', 10);

        $data = $Query->sortable()->paginate($filter['perPage']);

        

        return view('function.user.mail-infomation',compact('data'));
    }

    public function infomation_show($id){

        $data = SendMail::findOrFail($id);

        return view('function.user.show',compact('data'));

    }

    public function send_mail_user()
    {

        $user = Soko::Where(function($query) {
                       // $query->whereNull('trader_password');
                    })
                    ->Where(function($query) {
                        $query->whereNotNull('trader_username');
                    })
                    ->Where(function($query) {
                        $query->where('is_nsw','n');
                    })
                    ->get();

        return view('function.user.mail-send',compact('user'));
    }

    public function save_send_mail_user(Request $request)
    {
        $requestData = $request->all();

        if( isset($requestData['file_attach']) ){

            $attach = $requestData['file_attach'];

            if(!Storage::directories($this->attach_path)){
                Storage::makeDirectory($this->attach_path);
            }
            //Upload File
            $storagePath = Storage::put($this->attach_path, $attach );
            $storageName = basename($storagePath); // Extract the filename

            $file_name = $attach->getClientOriginalName();

            $requestData['file_attach'] = $storageName;
            $requestData['file_attach_name'] = $file_name;

            $requestData['send_date'] = date('Y-m-d H:i:s');
         
        }
        
        if(  $requestData['send_type'] == 2 ){

            SendMail::create($requestData);
      
            $mail_form = new SendMailUser([
                'operater_name' => $requestData['name_send'],
                'invite' => isset($requestData['invite'])?$requestData['invite']:null,
                'quality' => isset($requestData['quality'])?$requestData['quality']:null,
                'information' => isset($requestData['information'])?$requestData['information']:null,
                'username' => null,
                'password' => null,
                'sender_name' => isset($requestData['sender_name'])?$requestData['sender_name']:null,
                'file_attach' => isset($requestData['file_attach'])?$requestData['file_attach']:null,
            ]); 
            Mail::to($requestData['emails'])->send($mail_form);



            return redirect('page/send-mails/user')->with('flash_message', 'ส่ง เรียบร้อยแล้ว');
        }else{

            if( isset($requestData['user_trader_id']) ){

                $trader_autonumber = $requestData['user_trader_id'];

                $user = Soko::Where(function($query) {
                                    // $query->whereNull('trader_password');
                                })
                                ->Where(function($query) {
                                    $query->whereNotNull('trader_username');
                                })
                                ->Where(function($query) {
                                    $query->where('is_nsw','n');
                                })
                                ->Where(function($query) use($trader_autonumber){
                                    $query->whereIn('trader_autonumber', $trader_autonumber);
                                })
                                ->get();

                $list = [];
                foreach($user as $item){
                    
                    if( $requestData['password_type'] = 1 ){
                        $password = str_random(8);
                    }else if( $requestData['password_type'] = 2 ){
                        $password = !empty($item->trader_id)?$item->trader_id:null;
                    }else if( $requestData['password_type'] = 3 ){
                        $password = isset($requestData['password_type'])?$requestData['password_type']:null;
                    }

                    try {

                        $data = new stdClass;
                        $data->trader_operater_name = $item->trader_operater_name;
                        $data->trader_username = $item->trader_username;
                        $data->agent_email = $item->agent_email;
                        $data->password = $password;
                        $data->status = 'สำเร็จ';

                        $mail_form = new SendMailUser([
                            'operater_name' => $item->trader_operater_name,
                            'invite' => isset($requestData['invite'])?$requestData['invite']:null,
                            'quality' => isset($requestData['quality'])?$requestData['quality']:null,
                            'information' => isset($requestData['information'])?$requestData['information']:null,
                            'username' => $item->trader_username,
                            'password' => $password,
                            'sender_name' => isset($requestData['sender_name'])?$requestData['sender_name']:null,
                            'file_attach' => isset($requestData['file_attach'])?$requestData['file_attach']:null,
                        ]); 
                        Mail::to($item->agent_email)->send($mail_form);

                        $item->trader_password = $password;
                        $item->save();

                        $list[$item->trader_autonumber] = $data;

                    } catch (\Exception $e) {
                        $data = new stdClass;
                        $data->trader_operater_name = $item->trader_operater_name;
                        $data->trader_username = $item->trader_username;
                        $data->agent_email = $item->agent_email;
                        $data->password = null;
                        $data->status = 'ไม่สำเร็จ :ไม่พบอีเมลหรือไม่ใช่รูปแบบอีเมล และUsernameเป็นค่าว่าง';
                        $list[$item->trader_autonumber] = $data;
                    }

                }

                $requestData['data_multi'] = json_encode($list);

                SendMail::create($requestData);

                return view('function.user.show-error',compact('list'));

            }

            return redirect('page/send-mails/user')->with('flash_message', 'Error ส่งไม่สำเร็จ');
        }


    }

    public function SearchAddreess(Request $request){//ค้นหาที่อยู่จากตัวSelect 2
        
        $searchTerm = !empty($request->searchTerm)?$request->searchTerm:null;
        $searchTerm = str_replace(' ', '', $searchTerm);

        $address_data  =  DB::table((new District)->getTable().' AS sub') //ตำบล 
                            ->leftJoin((new Amphur)->getTable().' AS dis', 'dis.AMPHUR_ID', '=', 'sub.AMPHUR_ID') // อำเภอ
                            ->leftJoin((new Province)->getTable().' AS pro', 'pro.PROVINCE_ID', '=', 'sub.PROVINCE_ID')  // จังหวัด
                            ->where(function($query) use($searchTerm){

                                // $query->Where(DB::raw("CONCAT( REPLACE(sub.DISTRICT_NAME,' ',''),'_', REPLACE(dis.AMPHUR_NAME,' ',''),'_', REPLACE(pro.PROVINCE_NAME,' ',''),'_', REPLACE(code.zipcode,' ','') )"), 'LIKE', "%".$searchTerm."%");
                                $query->where(DB::raw("REPLACE(sub.DISTRICT_NAME,' ','')"),  'LIKE', "%$searchTerm%")
                                        ->orWhere(DB::raw("REPLACE(dis.AMPHUR_NAME,' ','')"),  'LIKE', "%$searchTerm%")
                                        ->orWhere(DB::raw("REPLACE(pro.PROVINCE_NAME,' ','')"),  'LIKE', "%$searchTerm%")
                                        ->orWhere(DB::raw("REPLACE(dis.POSTCODE,' ','')"),  'LIKE', "%$searchTerm%");
                            })
                            ->where(function($query){
                                // $query->where(DB::raw("REPLACE(sub.DISTRICT_NAME,' ','')"),  'NOT LIKE', "%*%");
                                $query->whereNull('sub.state');
                                //  $query->where('sub.state', '1')->where('dis.state', '1')->where('pro.state', '1');
                            })
                            ->select(

                                DB::raw("sub.DISTRICT_ID AS sub_ids"),
                                DB::raw("TRIM(sub.DISTRICT_NAME) AS sub_title"),

                                DB::raw("dis.AMPHUR_ID AS dis_id"),
                                DB::raw("TRIM(dis.AMPHUR_NAME) AS dis_title"),

                                DB::raw("pro.PROVINCE_ID AS pro_id"),
                                DB::raw("TRIM(pro.PROVINCE_NAME) AS pro_title"),

                                DB::raw("dis.POSTCODE AS sub_zip_code")

                            )
                            ->get();
        $data_list = [];

        foreach($address_data as $datas){

            $address = '';

            if(  strpos( $datas->dis_title , 'เขต' ) !== false ||  strpos( $datas->sub_title , 'แขวง' ) !== false  ){
                $address .= 'แขวง'.$datas->sub_title. ' | ';
            }else{
                $address .= 'ต.'.$datas->sub_title. ' | ';
            }

            if( strpos( $datas->dis_title , 'เขต' ) !== false  ){
                $address .= ' '.$datas->dis_title. ' | ';
            }else{
                $address .= ' อ.'.$datas->dis_title. ' | ';
            }

            $address .= ' จ.'.$datas->pro_title. ' | ';
            $address .= ' '.$datas->sub_zip_code;

            $data = new stdClass;
            $data->id = $datas->sub_ids;
            $data->text = $address;

            $data_list[] = $data;
        }
        echo json_encode($data_list,JSON_UNESCAPED_UNICODE);
    }

    
    public function GetAddreess(Request $request, $subdistrict_id){ // ดึงข้อมูลที่อยู่จาก ตำบล

        $address_data  =  DB::table((new District)->getTable().' AS sub') // ตำบล
                    ->leftJoin((new Amphur)->getTable().' AS dis', 'dis.AMPHUR_ID', '=', 'sub.AMPHUR_ID') // อำเภอ
                    ->leftJoin((new Province)->getTable().' AS pro', 'pro.PROVINCE_ID', '=', 'sub.PROVINCE_ID')  // จังหวัด
                    ->where(function($query) use($subdistrict_id){
                        $query->where('sub.DISTRICT_ID', $subdistrict_id);
                    })
                    ->where(function($query){
                        // $query->where(DB::raw("REPLACE(sub.DISTRICT_NAME,' ','')"),  'NOT LIKE', "%*%");
                        $query->whereNull('sub.state');
                    })
                    ->select(

                        DB::raw("sub.DISTRICT_ID AS sub_ids"),
                        DB::raw("TRIM(sub.DISTRICT_NAME) AS sub_title"), 

                        DB::raw("dis.AMPHUR_ID AS dis_id"),
                        DB::raw("TRIM(dis.AMPHUR_NAME) AS dis_title"),

                        DB::raw("pro.PROVINCE_ID AS pro_id"),
                        DB::raw("TRIM(pro.PROVINCE_NAME) AS pro_title"),

                        DB::raw("dis.POSTCODE AS zip_code")

                    )
                    ->first();

                    if(isset($request->khet) && $request->khet==1){
                        $address_data->dis_title = !empty($address_data->dis_title) && mb_strpos($address_data->dis_title, 'เขต')===0 ? trim(mb_substr($address_data->dis_title, 3)) : $address_data->dis_title ; //ตัดคำว่าเขต คำแรกออก
                    }
                   
        return response()->json($address_data);
    }

    public function SearchStandards(Request $request){//ค้นหามาตรฐาน

        $search_query = $request->get('searchTerm');
        $searchTerm = str_replace(' ', '', $search_query);
        $data_std =  Standard::where(function($query) use($searchTerm){
                                    $query->Where(DB::raw("CONCAT( REPLACE(tis_tisno,' ',''),' :', REPLACE(title,' ','') )"), 'LIKE', "%".$searchTerm."%")
                                            ->OrWhere(DB::raw("CONCAT( REPLACE(tis_tisno,' ',''),' :', REPLACE(title_en,' ','') )"), 'LIKE', "%".$searchTerm."%");
                                })
                                ->select( 'id','tis_tisno','tis_no','tis_book', 'tis_year', 'title' , 'title_en' )
                                ->get();

        $data_list = [];

        foreach($data_std as $datas){

            $tis_tisno =  ($datas->tis_no ). (!empty($datas->tis_book) ? ' เล่ม '.$datas->tis_book : ''). '-'.($datas->tis_year);
            $tis_tisno .= ' : '.($datas->title);
            
            $data = new stdClass;
            $data->id = $datas->id;
            $data->text = $tis_tisno;
            $data_list[] = $data;
        }

        echo json_encode($data_list,JSON_UNESCAPED_UNICODE);

    }

    public function setCookie(Request $request) {
        $minutes = time() + (20 * 365 * 24 * 60 * 60);
        $response = new Response('Set Cookie');
        $response->withCookie(cookie('active_cookie', 'active', $minutes));
        return $response;
    }

    public function getCookie(Request $request) {
        $value = $request->cookie('active_cookie');
        echo $value;
    }

    public function getNotification()
    {
        $log = [];

        if( Schema::hasTable((new LogNotification)->getTable()) ){  //เช็คว่ามีตารางจริงใหม่
            if( Auth::check() ){
                $log = LogNotification::where('users_id',  auth()->user()->getKey() )
                                        ->orderby('created_at', 'desc')
                                        ->limit('99')
                                        ->get();
            }

            $arr_status_ibcb = [];
            if(  Schema::hasTable((new ApplicationIbcbStatus)->getTable()) ){ //เช็คว่ามีตารางจริงใหม่
                $arr_status_ibcb = ApplicationIbcbStatus::pluck('title', 'id')->toArray();
            }

            $arr_status_insp = [];
            if(  Schema::hasTable((new ApplicationInspectorStatus)->getTable()) ){ //เช็คว่ามีตารางจริงใหม่
                $arr_status_insp = ApplicationInspectorStatus::pluck('title', 'id')->toArray();
            }
       
            $arr_status_lab = [];
            if(  Schema::hasTable((new ApplicationLabStatus)->getTable()) ){ //เช็คว่ามีตารางจริงใหม่
                $arr_status_lab = ApplicationLabStatus::pluck('title', 'id')->toArray();
            }

            foreach( $log AS $item ){

                $item->created_ats = HP::dateTimeFormatN($item->created_at);

                if( $item->ref_table == 'section5_application_ibcb' ){
                    $item->ref_status =  array_key_exists( $item->status,  $arr_status_ibcb )?$arr_status_ibcb [ $item->status ]:'-';
                }else if( $item->ref_table == 'section5_application_inspectors' ){
                    $item->ref_status =  array_key_exists( $item->status,  $arr_status_insp )?$arr_status_insp [ $item->status ]:'-';
                }else if( $item->ref_table == 'section5_application_labs' ){
                    $item->ref_status =  array_key_exists( $item->status,  $arr_status_lab )?$arr_status_lab [ $item->status ]:'-';
                }
            }
        
        }

        return response()->json($log); 
    }

    public function Notification_redirect($id)
    {
        $data = LogNotification::findOrFail($id);
        $data->update(['read' => 1]);
        return redirect($data->root_site.'/'.$data->url);
    }

    public function NotificationReadAll(Request $request)
    {
        $id_array = $request->input('id');
        $result = LogNotification::whereIn('id', $id_array);
        if($result->update(['read_all' => 1]))
        {
            echo 'Data Deleted';
        }
    }

    public function GetTimeNow()
    {
        return "ข้อมูล ณ วันที่ ".HP::DateThai(date('Y-m-d'))."  เวลา ".(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))."   น.";
    }

    public function GetSSOuser(Request $request){ // ดึงไอดีจาก tax_number
        
        $search_query = $request->get('query');
        $search = str_replace(' ', '', $search_query);
        $branch_type = $request->get('branch_type');

            $data = SSO_USER::where(function($query) use($search) {
                $query->where('tax_number','LIKE', "%$search%")
                      ->OrWhere(DB::raw("REPLACE(`name`, ' ', '')"), 'LIKE', "%".$search."%");
            })
            ->where(function($query) {
                $query->WhereNotNull('tax_number');
            })
            ->when($branch_type, function ($query, $branch_type){
                return $query->where('branch_type',$branch_type);
              })
            ->select(
                        'id',
                        'tax_number',
                        'name',
                        'contact_address_no',
                        'contact_moo',
                        'contact_soi',
                        'contact_building',
                        'contact_street',
                        'contact_subdistrict',
                        'contact_district',
                        'contact_province',
                        'contact_zipcode',
                        'contact_tel',
                        'email'
                    )
            ->get();

            $list = [];
            foreach( $data as $item ){
                $address = HP::GetIDAddress( $item->contact_subdistrict, $item->contact_district, $item->contact_province );
                $data = new stdClass;
                $data->id                     = $item->id; 
                $data->name                   = $item->name.' | '.$item->tax_number;  
                $data->tax_number             = $item->tax_number;  
                $data->name_show              = $item->name;  
                $data->contact_address_no     = $item->contact_address_no;  
                $data->contact_moo            = $item->contact_moo;  
                $data->contact_soi            = $item->contact_soi;  
                $data->contact_building       = $item->contact_building;  
                $data->contact_street         = $item->contact_street;  
                $data->contact_subdistrict    = $item->contact_subdistrict;  
                $data->contact_district       = $item->contact_district;  
                $data->contact_province       = $item->contact_province;  
                $data->contact_zipcode        = $item->contact_zipcode;  
                $data->contact_tel            = $item->contact_tel;  
                $data->email                  = $item->email;  
                $data->contact_subdistrict_id = $address->subdistrict_id;  
                $data->contact_district_id    = $address->district_id;  
                $data->contact_province_id    = $address->province_id;  
                $list[] = $data;
            }
        return response()->json($list, JSON_UNESCAPED_UNICODE);
    }

    public function search_users(Request $request)
    {

        $search_query     = $request->get('query');
        $applicanttype_id = $request->get('applicanttype_id');
        $search           = str_replace(' ', '', $search_query);

        $subdistricts = District::selectRaw('DISTRICT_ID, TRIM(DISTRICT_NAME) AS DISTRICT_NAME')->pluck('DISTRICT_NAME', 'DISTRICT_ID')->toArray();
        $districts = Amphur::selectRaw('AMPHUR_ID, TRIM( REPLACE(AMPHUR_NAME,"เขต","") ) AS AMPHUR_NAME')->pluck('AMPHUR_NAME', 'AMPHUR_ID')->toArray();
        $provinces = Province::selectRaw('PROVINCE_ID, TRIM(PROVINCE_NAME) AS PROVINCE_NAME')->pluck('PROVINCE_NAME', 'PROVINCE_ID')->toArray();

        $data = SSO_USER::where(function($query) use($search) {
                                $query->where('tax_number', 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`name`, ' ', '')"), 'LIKE', "%".$search."%");
                            })
                            ->when($applicanttype_id, function($query, $applicanttype_id) {
                                $query->where('applicanttype_id', $applicanttype_id);
                            })
                            ->where(function($query) {
                                $query->WhereNotNull('tax_number');
                            })
                            ->select(
                                'id', 'tax_number', 'name','applicanttype_id','date_of_birth', 'date_niti', 'branch_type', 'branch_code', 
                                'prefix_name', 'prefix_text', 'person_first_name', 'person_last_name',

                                'address_no', 'building','street', 'moo','soi','subdistrict','district','province','zipcode','tel', 'fax', 'email', 
                                'latitude', 'longitude',

                                'contact_prefix_text', 'contact_first_name', 'contact_last_name', 'contact_position', 'contact_tel', 'contact_phone_number', 'contact_fax',
                                'contact_address_no','contact_street', 'contact_moo','contact_soi','contact_subdistrict','contact_district','contact_province','contact_zipcode'

                            )
                            ->get();

        $list = [];
        foreach( $data as $key => $item ){

            $address = HP::GetIDAddress( $item->subdistrict, $item->district, $item->province );
            $address_co = HP::GetIDAddress( $item->contact_subdistrict, $item->contact_district, $item->contact_province );

            $data = new stdClass;
            $data->id                   = $item->id;
            $data->name_full            = $item->name;
            $data->name                 = $item->TypeaheadDropdownTitle;
            $data->taxid                = $item->tax_number;
            $data->prefix_name          = $item->prefix_name;
            $data->prefix_text          = $item->prefix_text;
            $data->person_first_name    = $item->person_first_name;
            $data->person_last_name     = $item->person_last_name;
            $data->email                = !empty($item->email)?$item->email:null;
            $data->phone                = !empty($item->tel)?$item->tel:null;
            $data->fax                  = !empty($item->fax)?$item->fax:null;
            $data->date_of_birth        = !empty($item->date_of_birth)?$item->date_of_birth:null;
            $data->date_niti            = !empty($item->date_niti)?$item->date_niti:null;
            $data->date_of_birth_format = !empty($item->date_of_birth)?HP::revertDate($item->date_of_birth,true):null;
            $data->date_niti_format     = !empty($item->date_niti)?HP::revertDate($item->date_niti,true):null;
            $data->branch_type_title    = !empty($item->BranchTypeTitle)?$item->BranchTypeTitle:null;
            $data->branch_type          = !empty($item->branch_type)?$item->branch_type:null;
            $data->branch_code          = !empty($item->branch_code)?$item->branch_code:null;
            $data->applicanttype_id     = !empty($item->applicanttype_id)?$item->applicanttype_id:null; 
            $data->applicanttype        = !empty($item->ApplicantTypeTitle)?$item->ApplicantTypeTitle:null; 

            //ที่อยู่สำนักงานใหญ่
            $data->hq_address_no        = @$item->address_no;
            $data->hq_building          = @$item->building;
            $data->hq_street            = @$item->street;
            $data->hq_moo               = @$item->moo;
            $data->hq_soi               = @$item->soi;
            $data->hq_subdistrict_id    = @$address->subdistrict_id;
            $data->hq_district_id       = @$address->district_id;
            $data->hq_province_id       = @$address->province_id;
            $data->hq_subdistrict_title = array_key_exists(@$address->subdistrict_id, $subdistricts)?$subdistricts[@$address->subdistrict_id]:null;
            $data->hq_district_title    = array_key_exists(@$address->district_id, $districts)?$districts[@$address->district_id]:null;
            $data->hq_province_title    = array_key_exists(@$address->province_id, $provinces)?$provinces[@$address->province_id]:null;
            $data->hq_zipcode           = @$address->zipcode;
            $data->longitude            = @$item->longitude;
            $data->latitude             = @$item->latitude;

            //ข้อมูลผู้ติดต่อ
            $data->contact_full_name          = $item->ContactFullName; 
            $data->contact_prefix_name        = $item->contact_prefix_name;  
            $data->contact_prefix_text        = $item->contact_prefix_text;  
            $data->contact_first_name         = $item->contact_first_name;  
            $data->contact_last_name          = $item->contact_last_name;  
            $data->contact_position           = $item->contact_position;  
            $data->contact_tel                = $item->contact_tel;  
            $data->contact_phone_number       = $item->contact_phone_number;  
            $data->contact_fax                = $item->contact_fax;  
            $data->contact_address_no         = $item->contact_address_no;
            $data->contact_street             = $item->contact_street;
            $data->contact_moo                = $item->contact_moo;
            $data->contact_soi                = $item->contact_soi;
            $data->contact_subdistrict_id     = @$address_co->subdistrict_id;
            $data->contact_district_id        = @$address_co->district_id;
            $data->contact_province_id        = @$address_co->province_id;
            $data->contact_subdistrict_title  = array_key_exists(@$address_co->subdistrict_id, $subdistricts)?$subdistricts[@$address_co->subdistrict_id]:null;
            $data->contact_district_title     = array_key_exists(@$address_co->district_id, $districts)?$districts[@$address_co->district_id]:null;
            $data->contact_province_title     = array_key_exists(@$address_co->province_id, $provinces)?$provinces[@$address_co->province_id]:null;
            $data->contact_zipcode            = @$address_co->zipcode;
            
            $list[] = $data;

        }

        return response()->json($list,JSON_UNESCAPED_UNICODE);

    }

    public function search_user_registers(Request $request)
    {

        $search_query      = $request->get('query');
        $sub_department_id = $request->get('sub_department_id');
        $search = str_replace(' ', '', $search_query);

        $data = User::where(function($query) use($search) {
                                $query->where(DB::raw("REPLACE(reg_13ID,'-','')"), 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`reg_fname`, ' ', '')"), 'LIKE', "%".$search."%");
                            })
                            ->where(function($query) {
                                $query->WhereNotNull(DB::raw("REPLACE(reg_13ID,'-','')"));
                            })
                            ->when($sub_department_id, function ($query, $sub_department_id){
                                return   $query->where(function($query) use($sub_department_id) {
                                                $query->where('reg_subdepart', $sub_department_id);
                                            });
                            })
                            ->select(
                                DB::raw("REPLACE(reg_13ID,'-','') AS reg_13ID"), 'reg_fname', 'reg_lname', 'reg_email', 'reg_phone', 'reg_wphone',  'reg_subdepart'   
                            )
                            ->get();

        $list = [];
        foreach( $data as $item ){

            $data = new stdClass;
            $data->id          = $item->runrecno;
            $data->name        = $item->reg_fname.' '.$item->reg_lname.' | '.$item->reg_13ID;
            $data->full_name   = $item->reg_fname.' '.$item->reg_lname;
            $data->taxid       = $item->reg_13ID;
            $data->email       = $item->reg_email;
            $data->phone       = $item->reg_phone;
            $data->wphone      = $item->reg_wphone;
            $data->sub_depart  = $item->reg_subdepart;
    
            $list[] = $data;
        }
   

        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }

    public function search_user_law_case(Request $request)
    {

        $search_query      = $request->get('query');
        $search = str_replace(' ', '', $search_query);
        $owner_basic_department_id = $request->get('owner_basic_department_id');

        $data = LawCasesForm::where(function($query) use($search) {
                                $query->Where('owner_name', 'LIKE', "%".$search."%")
                                ->OrWhere('owner_taxid', 'LIKE', "%".$search."%");
                            })
                            ->when($owner_basic_department_id, function ($query, $owner_basic_department_id){
                                return   $query->where(function($query) use($owner_basic_department_id) {
                                                $query->where('owner_basic_department_id', $owner_basic_department_id);
                                            });
                            })
                            ->select(
                                'owner_name', 'owner_email', 'owner_taxid', 'owner_tel', 'owner_phone'
                            )
                            ->groupby('owner_taxid')
                            ->get();

        $list = [];
        foreach( $data as $item ){

            $data = new stdClass;
            $data->id          = auth()->user()->getKey();
            $data->name        = $item->owner_name .' | '.$item->owner_taxid;
            $data->full_name   = $item->owner_name;
            $data->taxid       = $item->owner_taxid;
            $data->email       = $item->owner_email;
            $data->phone       = $item->owner_phone;
            $data->wphone      = $item->owner_tel;
    
            $list[] = $data;
        }
  

        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }

    
    public function search_law_user_registers(Request $request)
    {
        $list = [];
        $search_query      = $request->get('query');
        $sub_department_id = $request->get('sub_department_id');
        $departmen_type    = $request->get('departmen_type');
        $search = str_replace(' ', '', $search_query);
   
        if($departmen_type == '1'){  // ภายใน (สมอ.)
            $data = User::where(function($query) use($search) {
                                $query->where(DB::raw("REPLACE(reg_13ID,'-','')"), 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`reg_fname`, ' ', '')"), 'LIKE', "%".$search."%");
                                })
                                ->where(function($query) {
                                    $query->WhereNotNull(DB::raw("REPLACE(reg_13ID,'-','')"));
                                })
                                ->when($sub_department_id, function ($query, $sub_department_id){
                                    return   $query->where(function($query) use($sub_department_id) {
                                                    $query->where('reg_subdepart', $sub_department_id);
                                                });
                                })
                                ->select(
                                    DB::raw("REPLACE(reg_13ID,'-','') AS reg_13ID"), 'reg_fname', 'reg_lname', 'reg_email', 'reg_phone', 'reg_wphone',  'reg_subdepart'   
                                )
                                ->get();

            
                foreach( $data as $item ){

                    $data = new stdClass;
                    $data->id          = $item->runrecno;
                    $data->full_name   = $item->reg_fname.' '.$item->reg_lname;
                    $data->name        = $item->reg_fname.' '.$item->reg_lname.' |  '. $item->reg_13ID;
                    $data->taxid       = $item->reg_13ID;
                    $data->email       = $item->reg_email;
                    $data->phone       = $item->reg_phone;
                    $data->wphone      = $item->reg_wphone;

                    $data->basic_reward_group_id   = '';
                    $data->depart_type             = '';
                    $data->sub_depart              = $item->reg_subdepart; 
                    $data->basic_sub_depart        = '';
                    $data->department_name         = '';
                    $data->address                 = 'อยู่บ้านเลขที่ 75/42 ถนนพระราม 6 แขวงทุ่งพญาไท เขตราชเทวี จังหวัดกรุงเทพมหานคร';
    
                    $data->basic_bank_id           = '';
                    $data->bank_account_name       = '';
                    $data->bank_account_number     = '';
            
            
                    $list[] = $data;
                }
       }else{ // ภายนอก
         $data = LawCasesStaffList::where(function($query) use($search) {
                                $query->where('taxid', 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`name`, ' ', '')"), 'LIKE', "%".$search."%");
                                })
                                ->where('depart_type','2')
                                ->select(
                                    'id',
                                    'depart_type',
                                    'sub_department_id',
                                    'basic_department_id',
                                    'department_name',
                                    'name',
                                    'address',
                                    'basic_reward_group_id',
                                    'created_by',
                                    'updated_by',
                                    'taxid',
                                    'mobile',
                                    'email',
                                    'basic_bank_id',
                                    'bank_account_name',
                                    'bank_account_number'
                                )
                              
                                // ->groupBy('taxid')
                                ->orderby('id','desc')
                                ->get();
      
        if(count($data)  > 0){
            $taxids = [];
            foreach( $data as $item ){
                if(!empty( $item->taxid) && !in_array($item->taxid,$taxids)){
                    $data                       = new stdClass;
                    $data->id                   = '';
                    $data->full_name            = $item->name;
                    $data->name                 = $item->name.' |  '. $item->taxid;
                    $data->taxid                = $item->taxid;
                    $data->email                = $item->email;
                    $data->phone                = $item->mobile;
                    $data->wphone               = '';
    
                    $data->basic_reward_group_id   = $item->basic_reward_group_id;
                    $data->depart_type             = $item->depart_type;
                    $data->sub_depart              = $item->sub_department_id; 
                    $data->basic_sub_depart        = $item->basic_department_id;
                    $data->department_name         = $item->department_name;
                    $data->address                 = $item->address;
    
                    $data->basic_bank_id           = $item->basic_bank_id;
                    $data->bank_account_name       = $item->bank_account_name;
                    $data->bank_account_number     = $item->bank_account_number; 
                    $list[]                        = $data;
                    $taxids[]  = $item->taxid;
                }
            }
        }else{
       
            $data = SSO_USER::where(function($query) use($search) {
                $query->where('tax_number','LIKE', "%$search%")
                      ->OrWhere(DB::raw("REPLACE(`name`, ' ', '')"), 'LIKE', "%".$search."%");
            })
            ->where(function($query) {
                $query->WhereNotNull('tax_number');
            })
            ->where('applicanttype_id','2')
            ->select(
                        'id',
                        'tax_number',
                        'name',
                        'address_no',
                        'moo',
                        'soi',
                        'street',
                        'subdistrict',
                        'district',
                        'province',
                        'zipcode',
                        'contact_zipcode',
                        'tel',
                        'email'
                    )
            ->get();
            foreach( $data as $item ){
 
                $data                       = new stdClass;
                $data->id                   = '';
                $data->full_name            = $item->name;
                $data->name                 = $item->name.' |  '. $item->tax_number;
                $data->taxid                = $item->tax_number;
                $data->email                = $item->email;
                $data->phone                = $item->tel;
                $data->wphone               = '';

                $data->basic_reward_group_id   = '';
                $data->depart_type             = '2';
                $data->sub_depart              = ''; 
                $data->basic_sub_depart        = '';
                $data->department_name         = '';
                $data->address                 = $item->FormatAddress;

                $data->basic_bank_id           = '';
                $data->bank_account_name       = '';
                $data->bank_account_number     = '';
                $list[]                        = $data;
 
            }
        }

        }

 


        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }


    public function search_tb4tisilicense(Request $request)
    {

        $search_query   = $request->get('query');
        $taxpayer_query = $request->get('taxpayer_query');

        $search         = str_replace(' ', '', $search_query);

        $data = TisiLicense::where(function($query) use($search) {
                                $query->where('tbl_licenseNo', 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`tbl_tradeName`, ' ', '')"), 'LIKE', "%".$search."%")
                                        ->OrWhere(DB::raw("REPLACE(`tbl_taxpayer`, ' ', '')"), 'LIKE', "%".$search."%");
                            })
                            ->where(function($query) {
                                $query->WhereNotNull('tbl_taxpayer');
                            })
                            ->where(function($query) {
                                $query->where('tbl_licenseStatus',1);
                            })
                            ->when($taxpayer_query, function ($query, $taxpayer_query){
                                $query->Where(DB::raw("REPLACE(`tbl_taxpayer`, ' ', '')"), $taxpayer_query );
                            })
                            ->select(
                                'Autono', 'tbl_licenseNo', 'tbl_tradeName', 'tbl_taxpayer', 'tbl_tradeAddress', 'license_pdf', 'tbl_tisiNo','tbl_factoryName'  
                            )
                            ->get();

                            $list = [];
                            foreach( $data as $item ){

                                $user = $item->user;
                    
                                $data = new stdClass;
                                $data->id               = $item->Autono;
                                $data->name             = $item->tbl_licenseNo.' | '.$item->tbl_tradeName.' | '.$item->tbl_taxpayer;
                                $data->trade_name       = $item->tbl_tradeName;
                                $data->factory_name     = $item->tbl_factoryName;
                                $data->license_no       = $item->tbl_licenseNo;
                                $data->taxid            = $item->tbl_taxpayer;
                                $data->trade_address    = $item->tbl_tradeAddress;
                                $data->license_pdf      = $item->license_pdf;

                                $data->tis_id           = !empty($item->tis)?$item->tis->tb3_TisAutono:null;
                                $data->tis_no           = !empty($item->tis)?$item->tis->tb3_Tisno:null;
                                $data->tis_name         = !empty($item->tis)?$item->tis->tb3_TisThainame:null;

                                $data->sso_user_id      = !empty( $user->getKey() )?$user->getKey():null;

                                $address   = !empty($item->tbl_tradeAddress)?$item->tbl_tradeAddress:null;
                                $separate_address = HP_Law::SeparateAddress($address);
                    
                                //ที่อยู่
                                $data->address_no       = !empty($separate_address->address_no)?$separate_address->address_no:null;
                                $data->moo              = !empty($separate_address->moo)?$separate_address->moo:null;
                                $data->soi              = !empty($separate_address->soi)?$separate_address->soi:null;
                                $data->building         = !empty($separate_address->building)?$separate_address->building:null;
                                $data->street           = !empty($separate_address->road)?$separate_address->road:null;
                                $data->subdistrict_id   = !empty($separate_address->subdistrict_id)?$separate_address->subdistrict_id:null;
                                $data->subdistrict      = !empty($separate_address->subdistrict)?$separate_address->subdistrict:null;
                                $data->district_id      = !empty($separate_address->district_id)?$separate_address->district_id:null;
                                $data->district         = !empty($separate_address->district)?$separate_address->district:null;
                                $data->province_id      = !empty($separate_address->province_id)?$separate_address->province_id:null;
                                $data->province         = !empty($separate_address->province)?$separate_address->province:null;
                                $data->zipcode          = !empty($separate_address->zipcode)?$separate_address->zipcode:null;

                                $data->tel              = !empty($user->tel)?$user->tel:null;
                                $data->email            = !empty($user->email)?$user->email:null;
                                $data->contact_name     = !empty($user->contact_name)?$user->contact_name:null;
                                $data->contact_tel     = !empty($user->contact_tel)?$user->contact_tel:null;
                                $data->contact_phone_number = !empty($user->contact_phone_number)?$user->contact_phone_number:null;
                             
                                


                                $list[] = $data;
                            }

        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }

    public function search_tb3tis(Request $request)
    {

        $search_query = $request->get('query');
        $search = str_replace(' ', '', $search_query);

        $data = Tis::where(function($query) use($search) {
                                $query->where('tb3_Tisno', 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`tb3_TisThainame`, ' ', '')"), 'LIKE', "%".$search."%");
                            })
                            ->where(function($query) {
                                $query->where('status',1);
                            })
                       
                            ->select(
                                'tb3_TisAutono', 'tb3_Tisno', 'tb3_TisThainame'
                            )
                            ->get();

                            $list = [];
                            foreach( $data as $item ){
                    
                                $data = new stdClass;
                                $data->id                   = $item->tb3_TisAutono;
                                $data->name                 = $item->tb3_Tisno.' | '.$item->tb3_TisThainame;
                                $data->tb3_tisno            = $item->tb3_Tisno;
                                $data->tb3_tis_thainame     = $item->tb3_TisThainame;
                     
                                $list[] = $data;
                            }

        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }

    public function search_sub_department_tb3tis(Request $request)
    {

        $search_query = $request->get('query');
        $search = str_replace(' ', '', $search_query);
        $list = [];
        $user = auth()->user();
        $owner_depart_type          = $request->get('owner_depart_type');
    
        // if($owner_depart_type == '2'){
        //     $owner_sub_department_id   =  !empty( $user->subdepart->sub_id )?$user->subdepart->sub_id:'';
        // }else{
        //     $owner_sub_department_id   = $request->get('owner_sub_department_id');
        // }
 
        // $check  =  TisSubDepartment::where('sub_id',$owner_sub_department_id)->where('tb3_Tisno','All')->value('id');
        
        // if(!empty($check)){  // ทั้งหมด
              $data = Tis::where(function($query) use($search) {
                                $query->where('tb3_Tisno', 'LIKE' ,'%'.$search.'%')
                                        ->OrWhere(DB::raw("REPLACE(`tb3_TisThainame`, ' ', '')"), 'LIKE', "%".$search."%");
                            })
                            ->where(function($query) {
                                $query->where('status',1);
                            })
                       
                            ->select(
                                'tb3_TisAutono', 'tb3_Tisno', 'tb3_TisThainame'
                            )
                            ->get();

                        
                            foreach( $data as $item ){
                    
                                $data = new stdClass;
                                $data->id                   = $item->tb3_TisAutono;
                                $data->name                 = $item->tb3_Tisno.' | '.$item->tb3_TisThainame;
                                $data->tb3_tisno            = $item->tb3_Tisno;
                                $data->tb3_tis_thainame     = $item->tb3_TisThainame;
                     
                                $list[] = $data;
                            }

        // }else{
 
        //     $data = TisSubDepartment::where(function($query) use($search) {
        //                                 $query ->whereHas('tis_no', function ($query2) use ($search) {
        //                                     $query2->where('tb3_Tisno', 'LIKE' ,'%'.$search.'%')
        //                                             ->OrWhere(DB::raw("REPLACE(`tb3_TisThainame`, ' ', '')"), 'LIKE', "%".$search."%");
        //                                   });
        //                                 })
        //                                 ->where('sub_id',$owner_sub_department_id)
        //                                 ->get();
        //        foreach( $data as $item ){
        //            if(!empty($item->tis_no)){
        //                $tis_no =   $item->tis_no;
        //                $data = new stdClass;  
        //                $data->id                   = $tis_no->tb3_TisAutono ;
        //                $data->name                 = $item->tb3_Tisno.' | '.$tis_no->tb3_TisThainame;
        //                $data->tb3_tisno            = $item->tb3_Tisno;
        //                $data->tb3_tis_thainame     = $tis_no->tb3_TisThainame;
        //                $list[]                      = $data;
        //            }
        //        }

        // }
 
    
        return response()->json($list, JSON_UNESCAPED_UNICODE);

    }

    public function Manuals(){

        return view('function.manuals.preview');

    }

}
