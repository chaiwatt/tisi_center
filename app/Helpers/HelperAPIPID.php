<?php

use App\Models\WS\Client;
use App\Models\Asurv\EsurvTers20;
use App\Models\Asurv\EsurvBiss20;
use App\Models\Asurv\EsurvTers21;
use App\Models\Asurv\EsurvBiss21;
use App\Models\Asurv\EsurvOwns21;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
class HP_API_PID
{

    static function CheckDataApiPid($item,$table){

        $object = (object)[];

        if($table  == (new EsurvTers20)->getTable()){ //รับคำขอการทำผลิตภัณฑ์เพื่อส่งออก (20 ตรี)

            $object->user_created      =  $item->user_created;
            $object->applicanttype_id  =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number        =  $item->tax_id  ;
            $object->name              =  $item->made_factory_name;
            $object->address_no        =  $item->made_factory_addr_no;
            $object->soi               =  $item->made_factory_soi;
            $object->street            =  $item->made_factory_road;
            $object->moo               =  $item->made_factory_moo;
            $object->subdistrict       =  $item->made_factory_subdistrict;
            $object->district          =  $item->made_factory_district;
            $object->province          =  $item->made_factory_province;
            $object->applicantion       =  0;

        } else if($table  == (new EsurvBiss20)->getTable()){ // ระบบรับคำขอการทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (20 ทวิ)

            $object->user_created       =  $item->user_created;
            $object->applicanttype_id   =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->made_factory_name;
            $object->address_no         =  $item->made_factory_addr_no;
            $object->soi                =  $item->made_factory_soi;
            $object->street             =  $item->made_factory_road;
            $object->moo                =  $item->made_factory_moo;
            $object->subdistrict        =  $item->made_factory_subdistrict;
            $object->district           =  $item->made_factory_district;
            $object->province           =  $item->made_factory_province;
            $object->applicantion       =  0;

        }  else if($table  == (new EsurvTers21)->getTable()){ // ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อส่งออก (21 ตรี)

            $object->user_created       =  $item->user_created;
            $object->applicanttype_id   =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->made_factory_name;
            $object->address_no         =  $item->made_factory_addr_no;
            $object->soi                =  $item->made_factory_soi;
            $object->street             =  $item->made_factory_road;
            $object->moo                =  $item->made_factory_moo;
            $object->subdistrict        =  $item->made_factory_subdistrict;
            $object->district           =  $item->made_factory_district;
            $object->province           =  $item->made_factory_province;
            $object->applicantion       =  0;

        }  else if($table  == (new EsurvBiss21)->getTable()){ // ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ)

            $object->user_created       =  $item->user_created;
            $object->applicanttype_id   =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->made_factory_name;
            $object->address_no         =  $item->made_factory_addr_no;
            $object->soi                =  $item->made_factory_soi;
            $object->street             =  $item->made_factory_road;
            $object->moo                =  $item->made_factory_moo;
            $object->subdistrict        =  $item->made_factory_subdistrict;
            $object->district           =  $item->made_factory_district;
            $object->province           =  $item->made_factory_province;
            $object->applicantion       =  0;

        }  else if($table  == (new EsurvOwns21)->getTable()){ // ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อนำเข้ามาใช้เอง (21)

            $object->user_created       =  $item->user_created;
            $object->applicanttype_id   =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->made_factory_name;
            $object->address_no         =  $item->made_factory_addr_no;
            $object->soi                =  $item->made_factory_soi;
            $object->street             =  $item->made_factory_road;
            $object->moo                =  $item->made_factory_moo;
            $object->subdistrict        =  $item->made_factory_subdistrict;
            $object->district           =  $item->made_factory_district;
            $object->province           =  $item->made_factory_province;
            $object->applicantion       =  0;

        }  else if($table  == (new CertiLab)->getTable()){ // lab

            if( !is_null($item->hq_subdistrict)  ){
                $hq_subdistrict = trim($item->hq_subdistrict->DISTRICT_NAME);
                $hq_subdistrict = !empty($hq_subdistrict) && (mb_strpos($hq_subdistrict, 'แขวง')===0 || mb_strpos($hq_subdistrict, 'ตำบล')===0) ? trim(mb_substr($hq_subdistrict, 4)) : $hq_subdistrict ; //ตัดคำว่าตำบล/แขวง คำแรกออก
            }

            if( !is_null($item->hq_district)  ){
                $hq_district = trim($item->hq_district->AMPHUR_NAME);
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'อำเภอ')===0 ? trim(mb_substr($hq_district, 5)) : $hq_district ; //ตัดคำว่าอำเภอ คำแรกออก
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'เขต')===0 ? trim(mb_substr($hq_district, 3)) : $hq_district ; //ตัดคำว่าเขต คำแรกออก
            }

            $object->user_created       =  $item->user_created;
            $object->applicanttype_id   =  $item->user_created->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->name;
            $object->address_no         =  $item->hq_address;
            $object->soi                =  $item->hq_soi;
            $object->street             =  $item->hq_road;
            $object->moo                =  $item->hq_moo;
            $object->subdistrict        =  !empty($hq_subdistrict)?$hq_subdistrict : null ;
            $object->district           =  !empty($hq_district)?$hq_district : null ;
            $object->province           =  !is_null($item->hq_province) ? trim($item->hq_province->PROVINCE_NAME) : null ;
            $object->applicantion       =  1;

        }  else if($table  == (new CertiCb)->getTable()){ // cb

            if( !is_null($item->hq_subdistrict)  ){
                $hq_subdistrict = trim($item->hq_subdistrict->DISTRICT_NAME);
                $hq_subdistrict = !empty($hq_subdistrict) && (mb_strpos($hq_subdistrict, 'แขวง')===0 || mb_strpos($hq_subdistrict, 'ตำบล')===0) ? trim(mb_substr($hq_subdistrict, 4)) : $hq_subdistrict ; //ตัดคำว่าตำบล/แขวง คำแรกออก
            }

            if( !is_null($item->hq_district)  ){
                $hq_district = trim($item->hq_district->AMPHUR_NAME);
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'อำเภอ')===0 ? trim(mb_substr($hq_district, 5)) : $hq_district ; //ตัดคำว่าอำเภอ คำแรกออก
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'เขต')===0 ? trim(mb_substr($hq_district, 3)) : $hq_district ; //ตัดคำว่าเขต คำแรกออก
            }

            $object->user_created       =  $item->EsurvTrader;
            $object->applicanttype_id   =  $item->EsurvTrader->applicanttype_id ?? null  ;
            $object->tax_number         =  $item->tax_id  ;
            $object->name               =  $item->name;
            $object->address_no         =  $item->hq_address;
            $object->soi                =  $item->hq_soi;
            $object->street             =  $item->hq_road;
            $object->moo                =  $item->hq_moo;
            $object->subdistrict        =  !empty($hq_subdistrict)?$hq_subdistrict : null ;
            $object->district           =  !empty($hq_district)?$hq_district : null ;
            $object->province           =  !is_null($item->hq_province) ? trim($item->hq_province->PROVINCE_NAME) : null ;
            $object->applicantion       =  1;

        }  else if($table  == (new CertiIb)->getTable()){ // ib

            if( !is_null($item->hq_subdistrict)  ){
                $hq_subdistrict = trim($item->hq_subdistrict->DISTRICT_NAME);
                $hq_subdistrict = !empty($hq_subdistrict) && (mb_strpos($hq_subdistrict, 'แขวง')===0 || mb_strpos($hq_subdistrict, 'ตำบล')===0) ? trim(mb_substr($hq_subdistrict, 4)) : $hq_subdistrict ; //ตัดคำว่าตำบล/แขวง คำแรกออก
            }

            if( !is_null($item->hq_district)  ){
                $hq_district = trim($item->hq_district->AMPHUR_NAME);
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'อำเภอ')===0 ? trim(mb_substr($hq_district, 5)) : $hq_district ; //ตัดคำว่าอำเภอ คำแรกออก
                $hq_district = !empty($hq_district) && mb_strpos($hq_district, 'เขต')===0 ? trim(mb_substr($hq_district, 3)) : $hq_district ; //ตัดคำว่าเขต คำแรกออก
            }

            $object->user_created       =  $item->EsurvTrader;
            $object->applicanttype_id   =  $item->EsurvTrader->applicanttype_id ?? null ;
            $object->tax_number         =  $item->tax_id;
            $object->name               =  $item->name;
            $object->address_no         =  $item->hq_address;
            $object->soi                =  $item->hq_soi;
            $object->street             =  $item->hq_road;
            $object->moo                =  $item->hq_moo;
            $object->subdistrict        =  !empty($hq_subdistrict)?$hq_subdistrict : null ;
            $object->district           =  !empty($hq_district)?$hq_district : null ;
            $object->province           =  !is_null($item->hq_province) ? trim($item->hq_province->PROVINCE_NAME) : null ;
            $object->applicantion       =  1;

        }

        return self::CheckLegalEntityANDPerson($object);   // นิติบุคคล
    }

    static function CheckLegalEntityANDPerson($data_user){

        $response    =  '';

        if(!empty($data_user->tax_number)  &&  strlen($data_user->tax_number) == 13){

            if($data_user->applicanttype_id == 1){ // เช็ค นิติบุคคล ใน DBD

                $entity  =  self::CheckLegalEntity($data_user->tax_number);   // นิติบุคคล

                if($entity['status'] === 'false'){
                    $response     = 'ขออภัยเลขนิติบุคคล '.$data_user->tax_number .' ไม่พบการขึ้นทะเบียนกับกรมพัฒนาธุรกิจการค้า';
                }else if($entity['status'] === true){ /// request api ไม่ได้อนุญาตให้ login ได้
                    $response = '';
                }else if(!in_array($entity['status'], [1,2,3])){ //1.ยังดำเนินกิจการอยู่, 2.ฟื้นฟู, 3.คืนสู่ทะเบียน
                    $response = 'เลขนิติบุคคล'. $data_user->tax_number . ' เนื่องจากมีสถานะกิจการเป็น:&nbsp;<u>'.$entity['status'].'</u>';
                }else if(in_array($entity['status'], [1,2,3])){ //1.ยังดำเนินกิจการอยู่, 2.ฟื้นฟู, 3.คืนสู่ทะเบียน // Login ปกติ
                    $compare_msg =  self::compareCompanyAndUpdate($data_user, $entity['data']);//เปรียบเทียบข้อมูลและอัพเดทลงฐานข้อมูล
                    if($compare_msg != ''){
                        $response =  $compare_msg;
                    }else{
                        $response =  '';
                    }
                }

            }elseif($data_user->applicanttype_id == 2){ // เช็ค บุคคลธรรมดา ใน DOPA

                $person = self::getPerson($data_user->tax_number);

                if(is_null($person)){//ไม่พบข้อมูลในทะเบียนราษฎร์
                    $response =  'ขออภัยเลขประจำตัวประชาชน '. $data_user->tax_number . ' ไม่พบในทะเบียนราษฎร์';
                }elseif($person === true){ // request api ไม่ได้อนุญาตให้ login ได้
                    $response =  '';
                }elseif($person->statusOfPersonCode == '1'){//เสียชีวิต
                    $response =  'เลขประจำตัวประชาชน '. $data_user->tax_number . ' ไม่สามารถ Login ได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>เสียชีวิต</u>';
                }else{// Login ปกติ
                    $compare_msg = self::comparePersonAndUpdate($data_user,$person);//เปรียบเทียบข้อมูลและอัพเดทลงฐานข้อมูล
                    if($compare_msg != ''){
                        $response =  $compare_msg;
                    }else{
                        $response =  '';
                    }
                }
            }

        }
        return  $response;

    }

    //  DBD
    static function CheckLegalEntity($tax_number){

        $response = ['status' => 'false', 'data' => null];

        $config = HP::getConfig();

        $url = $config->tisi_api_corporation_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';

        $data = array(
                        'val' => $tax_number,
                        'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
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
        $i = 1;

        start:
        if($i <= 3){
            try {
                $json_data = file_get_contents($url, false, $context);
                $api = json_decode($json_data);
                if(!empty($api->JuristicName_TH)){
                    $juristic_status = ['ยังดำเนินกิจการอยู่' => '1', 'ฟื้นฟู' => '2', 'คืนสู่ทะเบียน' => '3'];
                    $status = array_key_exists($api->JuristicStatus,$juristic_status) ? $juristic_status[$api->JuristicStatus] : $api->JuristicStatus ;  //สถานะนิติบุคคล
                    $response['status'] = $status;
                    $response['data'] = $api;
                }
            } catch (\Exception $e) {
                $i ++;
                goto start;
            }
        }else{
            $response['status'] = true;
        }

        return $response;

    }

    //  ประชาชน
    static function getPerson($tax_id){

        $person = null;

        $config = HP::getConfig();

        $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';

        $data = array(
                        'val'   => $tax_id,
                        'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
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

        $i = 1;
        start:
        if($i <= 3){
            try {
                $json_data = file_get_contents($url, false, $context);
                $api = json_decode($json_data);
                if(!empty($api->firstName)){
                    $person = $api;
                }elseif(property_exists($api, 'Code') && $api->Code=='90001'){//ไม่ได้ login ที่ API สปอ. ให้ผ่านเข้าระบบได้
                    $person = true;
                }
            } catch (\Exception $e) {
                $i ++;
                goto start;
            }
        }else{
            $person = true;
        }

        return $person;
    }

    //เปรียบเทียบข้อมูลและอัพเดทลงฐานข้อมูล นิติบุคคล
    static function compareCompanyAndUpdate($user, $company){

        $data_changes = [];//เก็บข้อมูลที่ไม่ตรงเพื่อแสดงผล
        $msg_html     = '';

        if(in_array($company->JuristicType, ['บริษัทจำกัด', 'บริษัทมหาชนจำกัด'])){
            $company_name = 'บริษัท '.$company->JuristicName_TH;
        }else if(in_array($company->JuristicType, ['ห้างหุ้นส่วนจำกัด'])){
            $company_name = 'ห้างหุ้นส่วนจำกัด '.$company->JuristicName_TH;
        }else{
            $company_name = $company->JuristicName_TH;
        }

        replace_space:
        $company_name = str_replace('  ', ' ', trim($company_name));
        if(mb_strpos($company_name, '  ')!==false){//ยังมีการช่องว่างมากกว่า 1 ช่องติดกัน
            goto replace_space;
        }

        if($user->name!=$company_name){//ชื่อบริษัทไม่ตรง
            $data_changes[] = ['label' => 'ชื่อบริษัท', 'old' => $user->name, 'new' => $company_name];
            $user->name = $company_name;
        }

        //ที่ตั้งสำนักงาน
        $address = [];
        if(property_exists($company, 'AddressInformations') && count($company->AddressInformations) > 0){

            foreach ($company->AddressInformations as $info) {
                if($info->AddressName=='สำนักงานใหญ่'){
                    $address = $info;
                    break;
                }
            }

            if(count((array)$address)==0){//ไม่มี สำนักงานใหญ่ ให้เอาข้อมูล Array ชุดแรกเป็นสำนักงานใหญ่
                $address = $company->AddressInformations[0];
            }

            $address = self::format_address_company_api($address);

            if(  $user->applicantion == 1 ){
                //เช็คข้อมูล ใบสมัคร
                if($user->address_no!=$address->AddressNo){//เลขที่ไม่ตรง
                    $data_changes[] = ['label' => 'เลขที่ (สำนักงานใหญ่)', 'old' => $user->address_no, 'new' => $address->AddressNo];
                    $user->address_no = $address->AddressNo;
                }
    
                if($user->moo!=$address->Moo){//หมู่ไม่ตรง
                    $data_changes[] = ['label' => 'หมู่ (สำนักงานใหญ่)', 'old' => $user->moo, 'new' => $address->Moo];
                    $user->moo = $address->Moo;
                }
    
                if($user->soi!=$address->Soi){//ซอยไม่ตรง
                    $data_changes[] = ['label' => 'ตรอก/ซอย (สำนักงานใหญ่)', 'old' => $user->soi, 'new' => $address->Soi];
                    $user->soi = $address->Soi;
                }
    
                if($user->street!=$address->Road){//ถนนไม่ตรง
                    $data_changes[] = ['label' => 'ถนน (สำนักงานใหญ่)', 'old' => $user->street, 'new' => $address->Road];
                    $user->street = $address->Road;
                }
    
                if($user->subdistrict!=$address->Tumbol){//ตำบลไม่ตรง
                    $data_changes[] = ['label' => 'ตำบล/แขวง (สำนักงานใหญ่)', 'old' => $user->subdistrict, 'new' => $address->Tumbol];
                    $user->subdistrict = $address->Tumbol;
                }
    
                if($user->district!=$address->Ampur){//อำเภอไม่ตรง
                    $data_changes[] = ['label' => 'อำเภอ/เขต (สำนักงานใหญ่)', 'old' => $user->district, 'new' => $address->Ampur];
                    $user->district = $address->Ampur;
                }
    
                if($user->province!=$address->Province){//จังหวัดไม่ตรง
                    $data_changes[] = ['label' => 'จังหวัด (สำนักงานใหญ่)', 'old' => $user->province, 'new' => $address->Province];
                    $user->province = $address->Province;
                }
            }else{
                //User SSO
                if( !is_null( $user->user_created ) ){

                    $user_created = $user->user_created;

                    if($user_created->address_no != $address->AddressNo){//เลขที่ไม่ตรง
                        $data_changes[] = ['label' => 'เลขที่ (สำนักงานใหญ่)', 'old' => $user_created->address_no, 'new' => $address->AddressNo];
                    }

                    if($user_created->moo != $address->Moo){//หมู่ไม่ตรง
                        $data_changes[] = ['label' => 'หมู่ (สำนักงานใหญ่)', 'old' => $user_created->moo, 'new' => $address->Moo];
                    }

                    if($user_created->soi != $address->Soi){//ซอยไม่ตรง
                        $data_changes[] = ['label' => 'ตรอก/ซอย (สำนักงานใหญ่)', 'old' => $user_created->soi, 'new' => $address->Soi];
                    }

                    if($user_created->street != $address->Road){//ถนนไม่ตรง
                        $data_changes[] = ['label' => 'ถนน (สำนักงานใหญ่)', 'old' => $user_created->street, 'new' => $address->Road];
                    }

                    if($user_created->subdistrict != $address->Tumbol){//ตำบลไม่ตรง
                        $data_changes[] = ['label' => 'ตำบล/แขวง (สำนักงานใหญ่)', 'old' => $user_created->subdistrict, 'new' => $address->Tumbol];
                    }

                    if($user_created->district != $address->Ampur){//อำเภอไม่ตรง
                        $data_changes[] = ['label' => 'อำเภอ/เขต (สำนักงานใหญ่)', 'old' => $user_created->district, 'new' => $address->Ampur];
                    }

                    if($user_created->province != $address->Province){//จังหวัดไม่ตรง
                        $data_changes[] = ['label' => 'จังหวัด (สำนักงานใหญ่)', 'old' => $user_created->province, 'new' => $address->Province];
                    }

                }
            }

        }

        //HTML แสดงข้อมูลที่เปลี่ยนแปลง
        if(count($data_changes) > 0){

            //อัพเดทข้อมูล
            // $user->save();

            $title = 'พบข้อมูลของคุณไม่ตรงกับกรมพัฒนาธุรกิจการค้า ระบบได้ปรับปรุงข้อมูลแล้วดังนี้';
            $msg_html .= view('function.modal.change_address',compact('data_changes', 'title'));
        }

        return $msg_html;

    }

    //เปรียบเทียบข้อมูลและอัพเดทลงฐานข้อมูล บุคคลธรรมดา
    static function comparePersonAndUpdate($user, $person){

        $data_changes = [];//เก็บข้อมูลที่ไม่ตรงเพื่อแสดงผล
        $msg_html     = '';

        $person_name = $person->titleName.$person->firstName.' '.$person->lastName;//ชื่อเต็ม

        replace_space:
        $person_name = str_replace('  ', ' ', trim($person_name));
        if(mb_strpos($person_name, '  ')!==false){//ยังมีการช่องว่างมากกว่า 1 ช่องติดกัน
            goto replace_space;
        }

        // if($user->name != $person_name){
        //     $data_changes[] = ['label' => 'ชื่อ', 'old' => $user->name, 'new' => $person_name];
        //     $user->name = $person_name;//ชื่อเต็ม
        // }

        //User SSO
        if( !is_null( $user->user_created ) ){
            $user_created = $user->user_created;
            if($user_created->name != $person_name){
                $data_changes[] = ['label' => 'ชื่อ', 'old' => $user_created->name, 'new' => $person_name];
                $user->name = $person_name;//ชื่อเต็ม
            }
        }

        //HTML แสดงข้อมูลที่เปลี่ยนแปลง
        if(count($data_changes) > 0){

            //อัพเดทข้อมูล
            // $user->save();

            $title = 'พบข้อมูลของคุณไม่ตรงกับทะเบียนราษฎร์ ระบบได้ปรับปรุงข้อมูลแล้วดังนี้';
            $msg_html .= view('function.modal.change_address',compact('data_changes', 'title'));
        }

        return $msg_html;

    }



    //ประเภทการลงทะเบียน
    static function applicant_types(){
        return [
                    '1' => 'นิติบุคคล',
                    '2' => 'บุคคลธรรมดา',
                    '3' => 'คณะบุคคล',
                    '4' => 'ส่วนราชการ',
                    '5' => 'อื่นๆ'
               ];
    }

    //ประเภทนิติบุคคล
    static function juristic_types(){
        return [
                    '1' => 'บริษัทจำกัด',
                    '2' => 'บริษัทมหาชนจำกัด',
                    '3' => 'ห้างหุ้นส่วนจำกัด',
                    '4' => 'ห้างหุ้นส่วนสามัญนิติบุคคล'
               ];
    }

    //ประเภทสาขา
    static function branch_types(){
        return [
                    '1' => 'สำนักงานใหญ่',
                    '2' => 'สาขา'
               ];
    }

    static function format_address_company_api($address){
        $FullAddress = $address->FullAddress;

        $address_no = $building = $floor = $room_no = $village_name = $moo = $soi = $road = null;

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

        //ค้นหาถนน
        $index_road = mb_strpos($FullAddress, 'ถนน');

        //หาเลขที่
        $address_no = self::cut_string($FullAddress, 0, [$index_moo, $index_soi, $index_road]);

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
            $address->Building = self::replace_multi_space(mb_substr($building, mb_strlen('หมู่ที่')));
        }elseif(!is_null($address->Building)){
            $address->Building = trim($address->Building);
            $address->Building = !empty($address->Building) && mb_strpos($address->Building, 'อาคาร')===0 ? trim(mb_substr($address->Building, 5)) : $address->Building ; //ตัดคำว่าอาคาร คำแรกออก
        }

        if(is_null($address->Moo) && !is_null($moo)){//ถ้าหมู่ที่ในข้อมูลย่อยไม่มีให้เอาไปใส่แทน
            $address->Moo = self::replace_multi_space(mb_substr($moo, mb_strlen('หมู่ที่')));
        }elseif(!is_null($address->Moo)){
            $address->Moo = trim($address->Moo);
            $address->Moo = !empty($address->Moo) && mb_strpos($address->Moo, 'หมู่ที่')===0 ? trim(mb_substr($address->Moo, 7)) : $address->Moo ; //ตัดคำว่าหมู่ที่ คำแรกออก
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

        //เปลี่ยนข้อมูลเลขที่ใหม่โดยรวมเอา อาคาร ชั้น ห้อง หมู่บ้าน มาต่อท้าย
        $address->AddressNo = self::replace_multi_space($address_no);

        return $address;
    }

    static function cut_string($FullAddress, $index_source, $index_compares){

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

    static function replace_multi_space($string){//ลบช่องว่างที่อยู่ติดกันให้เหลือเว้นแค่ 1 ช่อง

        replace_space:
        $string = str_replace('  ', ' ', trim($string));
        if(mb_strpos($string, '  ')!==false){//ยังมีการช่องว่างมากกว่า 1 ช่องติดกัน
            goto replace_space;
        }

        return $string;

    }

    static function check_api($key_page){ //true=เปิดใช้, false=ปิดใช้
        $config = HP::getConfig();
        $check_api = property_exists($config, $key_page) ? $config->{$key_page} : 0 ;//0=ไม่ต้องเช็ค api, 1=เช็ค api
        return $check_api==1 ? true : false ;
    }

}
