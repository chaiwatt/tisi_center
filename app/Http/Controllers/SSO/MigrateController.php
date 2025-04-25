<?php

namespace App\Http\Controllers\SSO;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sso\User AS SSO_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use HP;
use HP_WS;
use DB;
use Storage;

class MigrateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Migrate ข้อมูลจาก e-License
     */
    public function migrate_e($start=0)
    {
        $e_license_site = 'https://i.tisi.go.th/e-license/';

        $limit = 100;
        echo "ดำเนินการครั้งละ $limit รายการ<br>";

        $db_e = DB::connection('mysql_elicense');
        $sub_query = $db_e->table("ros_user_usergroup_map")->select('user_id')->where('group_id', 2);
        $e_license_users = $db_e->table("ros_users")
                              ->whereIn("id", $sub_query)
                              ->where('block', '!=', '1')
                              ->offset($start*$limit)
                              ->limit($limit)
                              ->get();

        //ประเภทผู้ลงทะเบียน
        $applicanttypes = ['1' => '2', '2' => '1'];

        //dd($e_license_users);
        foreach ($e_license_users as $key => $item) {

            echo $key.'-'.$item->username;

            $sso_user = SSO_User::where('username', $item->username)->first();
            if(!is_null($sso_user)){
                echo ' <span style="color:red;">มีในระบบแล้ว</span><br>';
                continue;
            }

            if(strlen($item->tax_number)!=13 || !$this->check_id_card($item->tax_number)){
                echo ' <span style="color:Maroon;">เลขไม่ถูกต้อง</span><br>';
                continue;
            }

            if($item->applicanttype_id==2){//นิติบุคคล
                // $api_data = DB::table("DBD_profile")->where('trade', $item->tax_number)->first();
                // if(!is_null($api_data)){
                //     if(trim($api_data->tname) != trim($item->name)){
                //         echo ' <span style="color:red;">ชื่อผปก. ไม่ตรงกับ DBD</span><br>';
                //         continue;
                //     }
                // }else{
                //     echo ' <span style="color:red;">ไม่พบข้อมูลใน DBD</span><br>';
                //     continue;
                // }

                echo '<span style="color:Coral;">นิติบุคคล</span><br>';

                $person = DB::table('no_data')->where('tax_number', $item->tax_number)->first();
                if(!is_null($person)){
                    echo '<span style="color:Magenta;">ค้นแล้วไม่เข้าเงื่อนไข</span><br>';
                    continue;
                }

                $data = $this->getCompany($item->tax_number);

                if(property_exists($data, 'result') && $data->result=='Bad Request'){

                    $no_data = array('tax_number' => $item->tax_number, 'remark' => 'รูปแบบเลขนิติไม่ถูก');
                    DB::table('no_data')->insert($no_data);

                    echo '<span style="color:blue;">รูปแบบเลขประจำตัวผู้เสียภาษีไม่ถูกต้อง</span><br>';
                    continue;
                }elseif(property_exists($data, 'JuristicType')){
                    echo '<span style="color:aqua;">พบข้อมูลนิติ</span><br>';

                    $no_data = array('tax_number' => $item->tax_number, 'remark' => 'พบข้อมูลนิติ');
                    DB::table('no_data')->insert($no_data);

                    if(!in_array($data->JuristicStatus, ['ยังดำเนินกิจการอยู่', 'คืนสู่ทะเบียน', 'ฟื้นฟู'])){
                        echo '<span style="color:DarkBlue;">ไม่อยู่ในสถานะดำเนินกิจการ</span><br>';
                        continue;
                    }
                }else{
                    echo '<pre>'.print_r($data).'</pre>';
                    continue;
                }

            }

            if($item->applicanttype_id==1){//บุคคลธรรมดา
                // $api_data = DB::table("DOPA_data")->where('personalID', $item->tax_number)->first();
                // if(!is_null($api_data)){
                //     $dopa_name = trim($api_data->titleName).trim($api_data->firstName).' '.trim($api_data->lastName);
                //     if($dopa_name != trim($item->name)){
                //         echo ' <span style="color:red;">ชื่อผปก. ไม่ตรงกับ DOPA</span><br>';
                //         continue;
                //     }
                // }else{
                //     echo ' <span style="color:red;">ไม่พบข้อมูลใน DOPA</span><br>';
                //     continue;
                // }

                echo '<span style="color:Coral;">บุคคลธรรมดา</span><br>';

                $person = DB::table('no_data')->where('tax_number', $item->tax_number)->first();
                if(!is_null($person)){
                    echo '<span style="color:Magenta;">ค้นแล้วไม่เข้าเงื่อนไข</span><br>';
                    continue;
                }

                $data = $this->getPerson($item->tax_number);

                if(property_exists($data, 'titleCode')){

                    $no_data = array('tax_number' => $item->tax_number, 'remark' => 'พบข้อมูลบุคคล');
                    DB::table('no_data')->insert($no_data);

                    echo '<span style="color:aqua;">พบข้อมูลบุคคล</span><br>';

                    if($data->statusOfPersonCode=='1'){
                        echo '<span style="color:Crimson;">เสียชีวิต</span><br>';
                        continue;
                    }
                }elseif(property_exists($data, 'Code') && $data->Code=='00404'){

                    $no_data = array('tax_number' => $item->tax_number, 'remark' => 'ไม่พบข้อมูลบุคคล');
                    DB::table('no_data')->insert($no_data);

                    echo '<span style="color:LightSalmon;">ไม่พบข้อมูลบุคคล</span><br>';
                    continue;
                }else{
                    echo '<pre>'.print_r($data).'</pre>';
                    continue;
                }
            }

            $user = new SSO_User;
            $user->name                = $item->name;
            $user->username            = $item->username;
            $user->password            = $item->password;
            $user->picture             = null;
            $user->email               = $item->email;
            $user->contact_name        = $item->contact_name;
            $user->contact_tax_id      = $item->contact_tax_id;
            $user->contact_prefix_name = $item->contact_prefix_name;
            $user->contact_prefix_text = $item->contact_prefix_text;
            $user->contact_first_name  = $item->contact_first_name;
            $user->contact_last_name   = $item->contact_last_name;
            $user->contact_tel         = $item->contact_tel;
            $user->contact_fax         = $item->contact_fax;
            $user->contact_phone_number= $item->contact_phone_number;
            $user->block               = $item->block;
            $user->sendEmail           = $item->sendEmail;
            $user->registerDate        = $item->registerDate;
            $user->lastvisitDate       = $item->lastvisitDate;
            $user->params              = '{}';
            $user->lastResetTime       = $item->lastResetTime;
            $user->resetCount          = $item->resetCount;
            $user->applicanttype_id    = array_key_exists($item->applicanttype_id, $applicanttypes) ? $applicanttypes[$item->applicanttype_id] : $item->applicanttype_id ;
            $user->date_niti           = $item->date_niti;
            $user->person_type         = $item->person_type;
            $user->branch_type         = $item->branch_type;
            $user->tax_number          = $item->tax_number;
            $user->nationality         = $item->nationality;
            $user->date_of_birth       = $item->date_of_birth;
            $user->branch_code         = $item->branch_code;
            $user->prefix_name         = $item->prefix_name;
            $user->prefix_text         = $item->prefix_text;
            $user->person_first_name   = $item->person_first_name;
            $user->person_last_name    = $item->person_last_name;

            $user->address_no          = $item->head_address_no;
            $user->building            = $item->head_building;
            $user->street              = $item->head_street;
            $user->moo                 = $item->head_moo;
            $user->soi                 = $item->head_soi;
            $user->subdistrict         = $item->head_subdistrict;
            $user->district            = $item->head_district;
            $user->province            = $item->head_province;
            $user->zipcode             = $item->head_zipcode;
            $user->tel                 = $item->head_tel;
            $user->fax                 = $item->head_fax;

            $user->contact_address_no  = $item->address_no;
            $user->contact_building    = $item->building;
            $user->contact_street      = $item->street;
            $user->contact_moo         = $item->moo;
            $user->contact_soi         = $item->soi;
            $user->contact_subdistrict = $item->subdistrict;
            $user->contact_district    = $item->district;
            $user->contact_province    = $item->province;
            $user->contact_zipcode     = $item->zipcode;

            $user->personfile          = $item->personfile;
            $user->corporatefile       = $item->corporatefile;
            $user->state               = 2;
            $user->google2fa_status    = 0;
            $user->google2fa_secret    = null;
            $user->latitude            = null;
            $user->longitude           = null;

            $user->save();

            $roles[] = ['role_id' => 2, 'user_id' => $user->getKey()];
            $roles[] = ['role_id' => 14, 'user_id' => $user->getKey()];
            $roles[] = ['role_id' => 21, 'user_id' => $user->getKey()];
            $roles[] = ['role_id' => 24, 'user_id' => $user->getKey()];
            RoleUser::insert($roles);

            //คัดลอกไฟล์ บริษัท
            $corporate_infos = (array)json_decode($item->corporatefile);
            if(count($corporate_infos) > 0){
                foreach ($corporate_infos as $corporate_info) {
                    $corporate_url  = $e_license_site.'media/com_user/'.$user->username.'/'.$corporate_info->realfile;
                    try {
                        $corporate_data = file_get_contents($corporate_url);
                        $this->storeFile($corporate_info->realfile, $corporate_data, $user->tax_number);
                    } catch (\Exception $e) {
                        echo " ไม่พบไฟล์บริษัท: $corporate_info->realfile <br>";
                    }
                }
            }

            //คัดลอกไฟล์ บุคคล
            $person_infos = (array)json_decode($item->personfile);
            if(count($person_infos) > 0){
                foreach ($person_infos as $person_info) {
                    $person_url = $e_license_site.'media/com_user/'.$user->username.'/'.$person_info->realfile;
                    try {
                        $person_data = file_get_contents($person_url);
                        $this->storeFile($person_info->realfile, $person_data, $user->tax_number);
                    } catch (\Exception $e) {
                        echo " ไม่พบไฟล์บุคคล: $person_info->realfile <br>";
                    }
                }
            }

            echo ' <span style="color:green;">บันทึกเรียบร้อย</span><br>';
        }

        exit;
    }

    /**
     * Migrate ข้อมูลจาก NSW
     */
    public function migrate_nsw($start=0)
    {

        $limit = 500;
        echo "ดำเนินการครั้งละ $limit รายการ<br>";

        $nsw_users = DB::table("tb10_nsw_lite_trader")
                             ->offset($start*$limit)
                             ->limit($limit)
                             ->get();

        //ประเภทผู้ลงทะเบียน
        $applicanttypes = ['1' => 'นิติบุคคล', '2' => 'บุคคลธรรมดา'];

        foreach ($nsw_users as $key => $item) {

            echo $key.'-'.$item->trader_id;

            $sso_user = SSO_User::where('tax_number', $item->trader_id)->first();
            if(!is_null($sso_user)){
                echo ' <span style="color:red;">มีในระบบแล้ว</span><br>';
                continue;
            }

            $user = new SSO_User;
            $user->name                = $item->trader_operater_name;
            $user->username            = $item->trader_id;
            $user->password            = Hash::make($item->trader_password);
            $user->picture             = null;
            $user->email               = $item->agent_email;
            $user->contact_name        = is_null($item->agent_name) ? '-' : $item->agent_name;
            $user->contact_tax_id      = null;
            $user->contact_prefix_name = null;
            $user->contact_prefix_text = null;
            $user->contact_first_name  = null;
            $user->contact_last_name   = null;
            $user->contact_tel         = $item->trader_mobile;
            $user->contact_fax         = null;
            $user->contact_phone_number= null;
            $user->block               = is_null($item->deleted_at) ? 0 : 1;
            $user->sendEmail           = 0;
            $user->registerDate        = $item->date_of_data;
            $user->lastvisitDate       = null;
            $user->params              = is_null($user->params) ? '{}' : $user->params ;
            $user->lastResetTime       = null;
            $user->resetCount          = 0;
            $user->applicanttype_id    = array_search($item->trader_type, $applicanttypes)!==false ? array_search($item->trader_type, $applicanttypes) : null ;
            $user->date_niti           = $item->trader_id_register;
            $user->person_type         = 1;
            $user->tax_number          = $item->trader_id;
            $user->nationality         = null;
            $user->date_of_birth       = null;
            $user->branch_code         = null;
            $user->prefix_name         = null;
            $user->prefix_text         = null;
            $user->person_first_name   = null;
            $user->person_last_name    = null;

            $user->address_no          = $item->trader_address;
            $user->building            = null;
            $user->street              = $item->trader_address_road;
            $user->moo                 = $item->trader_address_moo;
            $user->soi                 = $item->trader_address_soi;
            $user->subdistrict         = $item->trader_address_tumbol;
            $user->district            = $item->trader_address_amphur;
            $user->province            = $item->trader_provinceID;
            $user->zipcode             = $item->trader_address_poscode;
            $user->tel                 = $item->trader_phone;
            $user->fax                 = $item->trader_fax;

            $user->contact_address_no  = null;
            $user->contact_building    = null;
            $user->contact_street      = null;
            $user->contact_moo         = null;
            $user->contact_soi         = null;
            $user->contact_subdistrict = null;
            $user->contact_district    = null;
            $user->contact_province    = null;
            $user->contact_zipcode     = null;

            $user->personfile          = null;
            $user->corporatefile       = null;
            $user->state               = 2;
            $user->google2fa_status    = 0;
            $user->google2fa_secret    = null;
            $user->latitude            = null;
            $user->longitude           = null;

            $user->save();

            $roles[] = ['role_id' => 2, 'user_id' => $user->getKey()];
            RoleUser::insert($roles);

            echo ' <span style="color:green;">บันทึกเรียบร้อย</span><br>';

        }

    }

    /**
     * Migrate ข้อมูลจากข้อมูลเดิม
     */
    public function migrate_trader($start=0)
    {

        $limit = 100;
        echo "ดำเนินการครั้งละ $limit รายการ<br>";

        $trader_users = DB::table("user_trader")
                          ->whereIn('trader_type', ['นิติบุคคล', 'บุคคลธรรมดา'])
                          ->where('is_nsw', 'n')
                          ->whereIn('trader_autonumber', ['14915'
                          ,'15014'
                          ,'15023'
                          ,'15032'
                          ,'15041'
                          ,'15044'
                          ,'15051'])
                          ->offset($start*$limit)
                          ->limit($limit)
                          ->get();

        //ประเภทผู้ลงทะเบียน
        $applicanttypes  = ['1' => 'นิติบุคคล', '2' => 'บุคคลธรรมดา'];
        $applicanttypes2 = ['3' => 'รัฐวิสาหกิจ', '5' => 'อื่น ๆ'];

        foreach ($trader_users as $key => $item) {

            $branch_type = 1;
            $username    = $item->trader_id;
            $branch_code = null;

            echo $key.'-'.$item->trader_id;

            if($item->trader_type=='บุคคลธรรมดา' && (strlen($item->trader_id)!=13 || !$this->check_id_card($item->trader_id))){
                echo ' <span style="color:Maroon;">เลขไม่ถูกต้อง</span><br>';
                continue;
            }
            if(strlen($item->trader_id)!=13){
                echo ' <span style="color:Maroon;">เลขไม่ถูกต้อง</span><br>';
                continue;
            }

            if(empty($item->agent_email)){
                echo ' <span style="color:Maroon;">อีเมลว่าง</span><br>';
                continue;
            }

            $sso_user = SSO_User::where('tax_number', $item->trader_id)
                                ->orderby('branch_type', 'DESC')
                                ->orderby('branch_code', 'DESC')
                                ->first();

            if(!is_null($sso_user)){
                if($item->trader_type=='นิติบุคคล'){
                    $sso_email = SSO_User::where('email', $item->agent_email)
                                         ->where('tax_number', $item->trader_id)
                                         ->first();
                    if(!is_null($sso_email)){//มีในระบบแล้ว
                        echo ' <span style="color:red;">มีในระบบแล้ว</span><br>';
                        continue;
                    }else{//เลขผู้เสียภาษีซ้ำ แต่อีเมลไม่ซ้ำ ให้เป็นสาขา

                        $branch_type = 2;

                        $branch_code_length = strlen($sso_user->branch_code);
                        $branch_code_length = $branch_code_length==0 ? 4 : $branch_code_length;
                        $branch_int = (int)$sso_user->branch_code;
                        plus_branch_code:
                        $branch_int++;
                        $branch_code = str_pad($branch_int, $branch_code_length, '0', STR_PAD_LEFT);
                        $branch_check = SSO_User::where('tax_number', $item->trader_id)->where('branch_code', $branch_code)->first();
                        if(!is_null($branch_check)){
                            goto plus_branch_code;
                        }
                        $username = $item->trader_id.$branch_code;
                    }
                }else{
                    echo ' <span style="color:red;">มีในระบบแล้ว</span><br>';
                    continue;
                }
            }

            $API_JuristicName_TH = '';
            if($item->trader_type=='นิติบุคคล' && $item->trader_inti!='รัฐวิสาหกิจ' && trim($item->trader_inti)!='อื่น ๆ'){//นิติบุคคล
                /*$api_data = DB::table("DBD_profile")->where('trade', $item->trader_id)->first();
                if(!is_null($api_data)){
                    if(trim($api_data->tname) != trim($item->trader_operater_name)){
                        echo ' <span style="color:red;">ชื่อผปก. ไม่ตรงกับ DBD</span><br>';
                        continue;
                    }
                }else{
                    echo ' <span style="color:red;">ไม่พบข้อมูลใน DBD</span><br>';
                    continue;
                }*/

                echo '<span style="color:Coral;">นิติบุคคล</span><br>';

                $person = DB::table('no_data')->where('tax_number', $item->trader_id)->first();
                if(!is_null($person)){
                    echo '<span style="color:Magenta;">ค้นแล้วไม่เข้าเงื่อนไข</span><br>';
                    continue;
                }

                $data = $this->getCompany($item->trader_id);//DBD

                if(property_exists($data, 'result') && $data->result=='Bad Request'){

                    $data = $this->getRD($item->trader_id);//RD
                    if(!empty($data->vMessageErr)){
                        $no_data = array('tax_number' => $item->trader_id, 'remark' => 'ไม่พบข้อมูลนิติบุคคล');
                        DB::table('no_data')->insert($no_data);
                        echo '<span style="color:blue;">ไม่พบข้อมูลนิติบุคคล</span><br>';
                        continue;
                    }else{//พบข้อมูลในสรรพากร ให้นำเข้าเป็นอื่น ๆ
                        $item->trader_inti = 'อื่น ๆ';
                    }

                }elseif(property_exists($data, 'JuristicType')){

                    echo '<span style="color:aqua;">พบข้อมูลนิติ</span><br>';

                    if(in_array($data->JuristicType, ['บริษัทจำกัด', 'บริษัทมหาชนจำกัด'])){
                        $API_JuristicName_TH = 'บริษัท '.$data->JuristicName_TH;
                    }else  if(in_array($data->JuristicType, ['ห้างหุ้นส่วนจำกัด'])){
                        $API_JuristicName_TH = 'ห้างหุ้นส่วนจำกัด '.$data->JuristicName_TH;
                    }else{
                        $API_JuristicName_TH = $data->JuristicName_TH;
                    }

                    if(!in_array($data->JuristicStatus, ['ยังดำเนินกิจการอยู่', 'คืนสู่ทะเบียน', 'ฟื้นฟู'])){

                        $no_data = array('tax_number' => $item->trader_id, 'remark' => 'ไม่ดำเนินกิจการ');
                        DB::table('no_data')->insert($no_data);

                        echo '<span style="color:DarkBlue;">ไม่อยู่ในสถานะดำเนินกิจการ</span><br>';
                        continue;
                    }
                }else{
                    echo '<pre>'.print_r($data).'</pre>';
                    continue;
                }
            }

            if($item->trader_type=='บุคคลธรรมดา'){//บุคคลธรรมดา
                /*$api_data = DB::table("DOPA_data")->where('personalID', $item->trader_id)->first();
                if(!is_null($api_data)){
                    $dopa_name = trim($api_data->titleName).trim($api_data->firstName).' '.trim($api_data->lastName);
                    $trader_name = trim($item->trader_inti).trim($item->trader_operater_name);
                    if($dopa_name != $trader_name){
                        echo ' <span style="color:red;">ชื่อผปก. ไม่ตรงกับ DOPA</span><br>';
                        continue;
                    }
                }else{
                    echo ' <span style="color:red;">ไม่พบข้อมูลใน DOPA</span><br>';
                    continue;
                }*/

                echo '<span style="color:Coral;">บุคคลธรรมดา</span><br>';

                $person = DB::table('no_data')->where('tax_number', $item->trader_id)->first();
                if(!is_null($person)){
                    echo '<span style="color:Magenta;">ค้นแล้วไม่เข้าเงื่อนไข</span><br>';
                    continue;
                }

                $data = $this->getPerson($item->trader_id);

                if(property_exists($data, 'titleCode')){

                    echo '<span style="color:aqua;">พบข้อมูลบุคคล</span><br>';
                    if($data->statusOfPersonCode=='1'){

                        $no_data = array('tax_number' => $item->trader_id, 'remark' => 'เสียชีวิต');
                        DB::table('no_data')->insert($no_data);

                        echo '<span style="color:Crimson;">เสียชีวิต</span><br>';
                        continue;
                    }
                }elseif(property_exists($data, 'Code') && $data->Code=='00404'){

                    $no_data = array('tax_number' => $item->trader_id, 'remark' => 'ไม่พบข้อมูลบุคคล');
                    DB::table('no_data')->insert($no_data);

                    echo '<span style="color:LightSalmon;">ไม่พบข้อมูลบุคคล</span><br>';
                    continue;
                }else{
                    echo '<pre>'.print_r($data).'</pre>';
                    continue;
                }

            }

            $user = new SSO_User;
            $user->name                = empty($API_JuristicName_TH) ? $item->trader_operater_name : $API_JuristicName_TH;
            $user->username            = $username;
            $user->password            = Hash::make($item->trader_password);
            $user->picture             = null;
            $user->email               = $item->agent_email;
            $user->contact_name        = is_null($item->agent_name) ? '-' : $item->agent_name;
            $user->contact_tax_id      = null;
            $user->contact_prefix_name = null;
            $user->contact_prefix_text = null;
            $user->contact_first_name  = null;
            $user->contact_last_name   = null;
            $user->contact_tel         = $item->trader_mobile;
            $user->contact_fax         = null;
            $user->contact_phone_number= null;
            $user->block               = is_null($item->deleted_at) ? 0 : 1;
            $user->sendEmail           = 0;
            $user->registerDate        = $item->date_of_data;
            $user->lastvisitDate       = $item->date_of_data;
            $user->params              = is_null($user->params) ? '{}' : $user->params ;
            $user->lastResetTime       = null;
            $user->resetCount          = 0;

            $applicanttype_id = 5;
            if(array_search($item->trader_type, $applicanttypes)!==false){
                $applicanttype_id = array_search($item->trader_type, $applicanttypes);
                if($applicanttype_id==1 && array_search(trim($item->trader_inti), $applicanttypes2)!==false){//
                    $applicanttype_id = array_search(trim($item->trader_inti), $applicanttypes2);
                }
            }
            $user->applicanttype_id    = $applicanttype_id;

            $user->date_niti           = $item->trader_id_register;
            $user->person_type         = 1;
            $user->tax_number          = $item->trader_id;
            $user->nationality         = null;
            $user->date_of_birth       = null;
            $user->branch_type         = $branch_type;
            $user->branch_code         = $branch_code;
            $user->prefix_name         = null;
            $user->prefix_text         = null;
            $user->person_first_name   = null;
            $user->person_last_name    = null;

            $user->address_no          = $item->trader_address;
            $user->building            = null;
            $user->street              = $item->trader_address_road;
            $user->moo                 = $item->trader_address_moo;
            $user->soi                 = $item->trader_address_soi;
            $user->subdistrict         = $item->trader_address_tumbol;
            $user->district            = $item->trader_address_amphur;
            $user->province            = $item->trader_provinceID;
            $user->zipcode             = $item->trader_address_poscode;
            $user->tel                 = $item->trader_phone;
            $user->fax                 = $item->trader_fax;

            $user->contact_address_no  = null;
            $user->contact_building    = null;
            $user->contact_street      = null;
            $user->contact_moo         = null;
            $user->contact_soi         = null;
            $user->contact_subdistrict = null;
            $user->contact_district    = null;
            $user->contact_province    = null;
            $user->contact_zipcode     = null;

            $user->personfile          = null;
            $user->corporatefile       = null;
            $user->state               = 2;
            $user->google2fa_status    = 0;
            $user->google2fa_secret    = null;
            $user->latitude            = null;
            $user->longitude           = null;

            $user->save();

            // $roles[] = ['role_id' => 2, 'user_id' => $user->getKey()];
            // $roles[] = ['role_id' => 14, 'user_id' => $user->getKey()];
            // $roles[] = ['role_id' => 21, 'user_id' => $user->getKey()];
            // $roles[] = ['role_id' => 24, 'user_id' => $user->getKey()];
            // RoleUser::insert($roles);

            echo ' <span style="color:green;">บันทึกเรียบร้อย</span><br>';

        }
        exit;
    }

    // สำหรับเพิ่มรูปไปที่ store
    private function storeFile($file_name, $data, $tax_number)
    {

        if ($data) {
            $attach_path = 'media/com_user/'.$tax_number.'/'.$file_name;
            $result  = Storage::put($attach_path, $data);
            return $result;
        }else{
            return false;
        }
    }

    //เรียกข้อมูล API
    private function getCompany($tax_number)
    {

        $response = null;

        $url = 'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';
        $data = array(
                'val' => $tax_number,
                'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
                'Refer' => 'i.tisi.go.th/e-license'
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                ),
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                )
        );
        $context  = stream_context_create($options);

        $json_data = file_get_contents($url, false, $context);
        $response = json_decode($json_data);

        return $response;

    }

    //เรียกข้อมูล API สรรพากร
    private function getRD($tax_number)
    {

        $response = null;

        $url = 'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
        $data = array(
                'val' => $tax_number,
                'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
                'Refer' => 'i.tisi.go.th/e-license'
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                ),
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                )
        );
        $context  = stream_context_create($options);

        $json_data = file_get_contents($url, false, $context);
        $response = json_decode($json_data);

        return $response;

    }

    private function getPerson($tax_id){

        $person = null;

        $url = 'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';
        $data = array(
                'val'   => $tax_id,
                'IP'    => '127.0.0.1',
                'Refer' => 'sso.tisi.go.th'
                );
        $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 30
                ),
                "ssl"=>array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                )
        );

        $context  = stream_context_create($options);
        $json_data = file_get_contents($url, false, $context);
        $person = json_decode($json_data);

        return $person;
    }

    public function check_id_card($cardid) {

        $num_id = $cardid;
        $group_1 = substr($num_id, 0, 1); // ดึงเอาเลขเลขตัวที่ 1 ของบัตรประชาชนออกมา
        $group_5 = substr($num_id, 12, 12);  // ดึงเอาเลขเลขตัวที่ 13 ของบัตรประชาชนออกมา

        $num1 = $group_1;
        $num2 = substr($num_id, 1, 1); // ดึงเอาเลขเลขตัวที่ 2 ของบัตรประชาชนออกมา
        $num3 = substr($num_id, 2, 1); // ดึงเอาเลขเลขตัวที่ 3 ของบัตรประชาชนออกมา
        $num4 = substr($num_id, 3, 1); // ดึงเอาเลขเลขตัวที่ 4 ของบัตรประชาชนออกมา
        $num5 = substr($num_id, 4, 1); // ดึงเอาเลขเลขตัวที่ 5 ของบัตรประชาชนออกมา
        $num6 = substr($num_id, 5, 1); // ดึงเอาเลขเลขตัวที่ 6 ของบัตรประชาชนออกมา
        $num7 = substr($num_id, 6, 1); // ดึงเอาเลขเลขตัวที่ 7 ของบัตรประชาชนออกมา
        $num8 = substr($num_id, 7, 1); // ดึงเอาเลขเลขตัวที่ 8 ของบัตรประชาชนออกมา
        $num9 = substr($num_id, 8, 1);// ดึงเอาเลขเลขตัวที่ 9 ของบัตรประชาชนออกมา
        $num10 = substr($num_id, 9, 1); // ดึงเอาเลขเลขตัวที่ 10 ของบัตรประชาชนออกมา
        $num11 = substr($num_id, 10, 1);// ดึงเอาเลขเลขตัวที่ 11 ของบัตรประชาชนออกมา
        $num12 = substr($num_id, 11, 1); // ดึงเอาเลขเลขตัวที่ 12 ของบัตรประชาชนออกมา
        $num13 = $group_5;

        if(!is_numeric($num1) || !is_numeric($num2) || !is_numeric($num3) || !is_numeric($num4) || !is_numeric($num5) || !is_numeric($num6) ||
           !is_numeric($num7) || !is_numeric($num8) || !is_numeric($num9) || !is_numeric($num10) || !is_numeric($num11) || !is_numeric($num12) ||
           !is_numeric($num13)
          ){
            return false;
        }

        // จากนั้นนำเลขที่ได้มา คูณ  กันดังนี้
        $cal_num1 = $num1 * 13; // เลขตัวที่ 1 ของบัตรประชาชน
        $cal_num2 = $num2 * 12; // เลขตัวที่ 2 ของบัตรประชาชน
        $cal_num3 = $num3 * 11; // เลขตัวที่ 3 ของบัตรประชาชน
        $cal_num4 = $num4 * 10; // เลขตัวที่ 4 ของบัตรประชาชน
        $cal_num5 = $num5 * 9; // เลขตัวที่ 5 ของบัตรประชาชน
        $cal_num6 = $num6 * 8; // เลขตัวที่ 6 ของบัตรประชาชน
        $cal_num7 = $num7 * 7; // เลขตัวที่ 7 ของบัตรประชาชน
        $cal_num8 = $num8 * 6; // เลขตัวที่ 8 ของบัตรประชาชน
        $cal_num9 = $num9 * 5; // เลขตัวที่  9  ของบัตรประชาชน
        $cal_num10 = $num10 * 4; // เลขตัวที่ 10 ของบัตรประชาชน
        $cal_num11 = $num11 * 3; // เลขตัวที่ 11 ของบัตรประชาชน
        $cal_num12 = $num12 * 2; // เลขตัวที่ 12 ของบัตรประชาชน


        //นำผลลัพธ์ทั้งหมดจากการคูณมาบวกกัน

        $cal_sum = $cal_num1 + $cal_num2 + $cal_num3 + $cal_num4 + $cal_num5 + $cal_num6 + $cal_num7 + $cal_num8 + $cal_num9 + $cal_num10 + $cal_num11 + $cal_num12;

        //นำผลบวกมา modulation ด้วย 11 เพื่อหาเศษส่วน
        $cal_mod = $cal_sum % 11;
        //นำ 11 ลบ กับส่วนที่เหลือจากการ  modulation
        $cal_2 = 11 - $cal_mod;

        //ถ้าหากเลขที่ได้มา มีค่าเท่ากับเลขสุดท้ายของเลขบัตรประชาชน ถูกว่ามีความถูกต้อง
        if ($cal_2 == $num13) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    public function migrate_role($start=0){
        $limit = 100;
        $users = SSO_User::offset($start*$limit)
                         ->limit($limit)
                         ->get();

        foreach ($users as $user) {

            echo $user->id;

            $roles = RoleUser::where('user_id', $user->id);

            $role_list = ['2' => 0, '14' => 0, '21' => 0, '24' => 0];

            foreach ($roles->get() as $role) {
                if(array_key_exists($role->role_id, $role_list)){
                    $role_list[$role->role_id]++;
                }
            }

            if($role_list['2']!=1 || $role_list['14']!=1 || $role_list['21']!=1 || $role_list['24']!=1){
                echo '-ไม่ถูกต้อง';
                $roles->delete();

                $roles   = [];
                $roles[] = ['role_id' => 2, 'user_id' => $user->getKey()];
                $roles[] = ['role_id' => 14, 'user_id' => $user->getKey()];
                $roles[] = ['role_id' => 21, 'user_id' => $user->getKey()];
                $roles[] = ['role_id' => 24, 'user_id' => $user->getKey()];
                RoleUser::insert($roles);

            }else{
                echo '-ถูกต้อง';
            }

            echo '<br>';
        }

        exit;

    }

    /**
     * Migrate update วันที่จดทะเบียน
     */
    public function migrate_date_niti($start=0)
    {

        $limit = 10;
        echo "ดำเนินการครั้งละ $limit รายการ<br>";

        $nsw_users =  SSO_User::select('id','date_niti','tax_number')
                             ->whereDate('date_niti','>=',date('Y-m-d'))
                             ->offset($start*$limit)
                             ->limit($limit)
                             ->get();
        if(count($nsw_users) > 0){
            foreach($nsw_users as $item){
                if(!Is_null($item->date_niti)){
                    $date_niti = explode("-",$item->date_niti);
                    if(count($date_niti) == 3 && $date_niti[0] >= 2022){
                         $requestData               = [];
                         $requestData['date_niti']  =  ($date_niti[0] - 543).'-'.$date_niti[1].'-'.$date_niti[2];;
                         $user = SSO_User::findOrFail($item->id);
                         if(!is_null($user)){
                            $user->update($requestData);
                            echo ' <span style="color:green;">บันทึกเรียบร้อย</span>เลข 13 หลัก : '.$item->tax_number.'-'.$user->date_niti.'<br>';
                         }
                    }

                }
            }
        }
    }

   /**
    * เช็คเลขที่ทะเบียนโรงงานจาก API กรมโรงงาน
    */
    public function migrate_factory(Request $request, $start=0){

        $limit = 100;
        echo "ดำเนินการครั้งละ $limit รายการ<br>";

        $db_e = DB::connection('mysql_elicense');
        $factorys = $db_e->table("ros_rform_setfactory")
                         ->offset($start*$limit)
                         ->limit($limit)
                         ->get();

        foreach ($factorys as $key => $factory) {

            echo 'id: '.$factory->id;

            if(empty($factory->factory_regis_no_new)){//เลขใหม่ว่าง
                if(!empty($factory->factory_regis_no)){//แต่มีเลขเดิม
                    $factory_regis_no = trim($factory->factory_regis_no);
                    if(mb_strlen($factory_regis_no)!=14){//ไม่ใช่เลข 14 หลัก
                        $factory->state = 0;//ปิดไว้
                        $factory->check_no = 2;//ไม่มีเลขทะเบียนโรงงาน
                        echo '-เคส 01 อัพเดทเรียบร้อย';
                    }else{
                        $industry = HP_WS::getIndustry($factory_regis_no, $request->ip());
                        if(isset($industry->status) && $industry->status=='success'){//ถ้าพบข้อมูล
                            $industry = $industry->result[0];
                            $factory->state = 1;//ใช้งาน
                            $factory->check_no = 1;//่มีเลขทะเบียนโรงงาน
                            $factory->tax_number           = $industry->TAX;
                            $factory->factory_name         = $industry->FNAME;
                            $factory->factory_address_no   = $industry->FADDR;
                            $factory->indus_estate_name    = $industry->COLONY_INDUST_DESC;
                            $factory->factory_street       = $industry->ROAD;
                            $factory->factory_moo          = $industry->FMOO;
                            $factory->factory_soi          = $industry->SOI;
                            $factory->factory_subdistrict  = $industry->TUMNAME;
                            $factory->factory_district     = $industry->AMPNAME;
                            $factory->factory_province     = $industry->PRONAME;
                            $factory->factory_zipcode      = $industry->ZIPCODE;
                            $factory->factory_regis_no     = $industry->DISPFACREG;
                            $factory->factory_regis_no_new = $industry->FID;
                            $factory->factory_latitude     = $industry->LATITUDE;
                            $factory->factory_longitude    = $industry->LONGITUDE;

                            echo '-เคส 02 อัพเดทเรียบร้อย';
                        }else{//ไม่พบข้อมูล
                            $factory->state = 0;//ปิดไว้
                            $factory->check_no = 2;//ไม่มีเลขทะเบียนโรงงาน
                            echo '-เคส 03 อัพเดทเรียบร้อย';
                        }
                    }
                }else{
                    $factory->check_no = 2;//ไม่มีเลขทะเบียนโรงงาน
                    echo '-เคส 04 อัพเดทเรียบร้อย';
                }
            }else{
                $factory_regis_no_new = trim($factory->factory_regis_no_new);
                if(mb_strlen($factory_regis_no_new)!=14){
                    $factory->state = 0;//ปิดไว้
                    $factory->check_no = 2;//ไม่มีเลขทะเบียนโรงงาน
                    echo '-เคส 05 อัพเดทเรียบร้อย';
                }else{
                    $industry = HP_WS::getIndustry($factory_regis_no_new, $request->ip());
                    if(isset($industry->status) && $industry->status=='success'){//ถ้าพบข้อมูล
                        $industry = $industry->result[0];
                        $factory->state = 1;//ใช้งาน
                        $factory->check_no = 1;//่มีเลขทะเบียนโรงงาน
                        $factory->tax_number           = $industry->TAX;
                        $factory->factory_name         = $industry->FNAME;
                        $factory->factory_address_no   = $industry->FADDR;
                        $factory->indus_estate_name    = $industry->COLONY_INDUST_DESC;
                        $factory->factory_street       = $industry->ROAD;
                        $factory->factory_moo          = $industry->FMOO;
                        $factory->factory_soi          = $industry->SOI;
                        $factory->factory_subdistrict  = $industry->TUMNAME;
                        $factory->factory_district     = $industry->AMPNAME;
                        $factory->factory_province     = $industry->PRONAME;
                        $factory->factory_zipcode      = $industry->ZIPCODE;
                        $factory->factory_regis_no     = $industry->DISPFACREG;
                        $factory->factory_regis_no_new = $industry->FID;
                        $factory->factory_latitude     = $industry->LATITUDE;
                        $factory->factory_longitude    = $industry->LONGITUDE;

                        echo '-เคส 06 อัพเดทเรียบร้อย';
                    }else{//ไม่พบข้อมูล
                        $factory->state = 0;//ปิดไว้
                        $factory->check_no = 2;//ไม่มีเลขทะเบียนโรงงาน
                        echo '-เคส 07 อัพเดทเรียบร้อย';
                    }
                }
            }

            $db_e->table("ros_rform_setfactory")
                 ->where('id', $factory->id)
                 ->update((array)$factory);

            echo '<br>';

        }


    }

}
