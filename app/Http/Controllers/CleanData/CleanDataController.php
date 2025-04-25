<?php

namespace App\Http\Controllers\CleanData;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CleanDataController extends Controller
{

    function testConnectionElicenseDatabase(){// Test database connection
        try {
            DB::connection('mysql_elicense')->getPdo();
            dd('Connected Successfully');
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
    }

    // Clean data moao8
    function cleanTb4TisilicenseMoao8()
    {
        // ini_set('max_execution_time', 3600);
        // ini_set('default_socket_timeout', 6000);
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            $connect = DB::connection('mysql_elicense');
            $main_table = 'ros_rform_moao8';
            $permit_no_table = 'ros_rform_moao10';
            $condition_table = 'ros_rform_tisi_license_changes';
            $tb_change_field = str_replace('ros_', '#__', $main_table);
            $ros_rform_moaos  =   $connect->table("$main_table AS a")
                                        // ->leftJoin("$permit_no_table AS permit_no_li", 'permit_no_li.ref_id', '=', 'a.id')
                                        ->select( 'a.*',
                                                    DB::raw("(select ordering from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_ordering"), 
                                                    DB::raw("(select permit_date3 from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_date"), 
                                                    DB::raw("(select REPLACE(permit_no,' ','') from $permit_no_table where ref_id = a.id order by id desc limit 1) as permit_no")
                                                    // DB::raw("REPLACE(permit_no_li.permit_no,' ','') AS permit_no")
                                                )->whereExists(function ($query) use($condition_table, $tb_change_field){
                                                    $query->select(DB::raw(1))
                                                        ->from("$condition_table AS change_li")
                                                        ->whereRaw('change_li.change_id = a.id and change_li.tb_change = "'.$tb_change_field.'" and change_li.ordering >= 2');
                                                })->get();
                
                // $this->checkFieldExistMoao8($ros_rform_moaos->first() ?? (object)[]);
    // dd($ros_rform_moaos);
            $tb4_tisilicenses = DB::table('tb4_tisilicense')->get();
            $data_insert = [];
            $update_count = [];
            foreach ($ros_rform_moaos as $key => $data_moao) {
                $check_address = (!empty($data_moao->factory_address_no_new) || !empty($data_moao->factory_moo_new) || !empty($data_moao->factory_soi_new) || !empty($data_moao->factory_street_new) || !empty($data_moao->factory_subdistrict_new) || !empty($data_moao->factory_district_new) || !empty($data_moao->factory_province_new));
                if((!empty($data_moao->factory_regis_no_new) || !empty($data_moao->factory_name_new) || $check_address) && !empty($data_moao->permit_no)){
                    $tb4_tisilicense = $tb4_tisilicenses->where('tbl_licenseNo', $data_moao->permit_no)->first();
                    if(!empty($tb4_tisilicense)){
                        $is_update = false;
                        $data_update = [];
                        $arr_for_json = [];
                        if(!empty($data_moao->factory_regis_no_new)){
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_factoryID';
                            $new_object->change_from = $data_moao->factory_regis_no;
                            $new_object->change_to = $data_moao->factory_regis_no_new;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_factoryID'] = $data_moao->factory_regis_no_new;
                            $is_update = true;
                        }
                        if(!empty($data_moao->factory_name_new)){
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_factoryName';
                            $new_object->change_from = $data_moao->factory_name;
                            $new_object->change_to = $data_moao->factory_name_new;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_factoryName'] = $data_moao->factory_name_new;
                            $is_update = true;
                        }
                        if($check_address){
                            $address_from = self::setAddressFromTextMoao8($data_moao);
                            $address_to = self::setAddressToTextMoao8($data_moao);
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_factoryAddress';
                            $new_object->change_from = $address_from;
                            $new_object->change_to = $address_to;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_factoryAddress'] = $address_to;
                            $is_update = true;
                        }
                        if(!empty($data_moao->factory_province_new)){
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_factoryInprovince';
                            $new_object->change_from = $data_moao->factory_province;
                            $new_object->change_to = $data_moao->factory_province_new;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_factoryInprovince'] = $data_moao->factory_province_new;
                            $is_update = true;
                        }
                        if($is_update){
                            DB::table('tb4_tisilicense')->where('Autono', $tb4_tisilicense->Autono)->update($data_update);
                            $update_count[] = $tb4_tisilicense->Autono;
                            if(!empty($arr_for_json) && count($arr_for_json) > 0){
                                $arr_insert = [];
                                $arr_insert['tbl_licenseNo'] = $tb4_tisilicense->tbl_licenseNo;
                                $arr_insert['tbl_licenseType'] = $tb4_tisilicense->tbl_licenseType;
                                $arr_insert['change_detail'] = json_encode($arr_for_json);
                                $arr_insert['change_display'] = self::displayTb4($arr_for_json, 1);
                                $arr_insert['change_date'] = $data_moao->change_date;
                                $arr_insert['refno'] = $data_moao->refno;
                                $arr_insert['pageNo'] = $data_moao->change_ordering;
                                $arr_insert['change_type'] = 1;
                                $arr_insert['ordering'] = $data_moao->change_ordering;
                                $arr_insert['created_at'] = date('Y-m-d H:i:s');
                                $arr_insert['created_by'] = 'Admin';
                                $arr_insert['updated_at'] = date('Y-m-d H:i:s');
                                $arr_insert['updated_by'] = 'Admin';
                                $data_insert[] = $arr_insert;
                            }
                        }
                    }
                }
            }
            DB::table('tb4_tisilicense_change')->insert($data_insert);
            DB::commit();
            dd(implode(', ', $update_count), 'Update '.count($update_count), 'Insert '.count($data_insert));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    // Clean data moao8/1
    function cleanTb4TisilicenseMoao81()
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            $connect = DB::connection('mysql_elicense');
            $main_table = 'ros_rform_moao81';
            $permit_no_table = 'ros_rform_moao101';
            $condition_table = 'ros_rform_tisi_license_changes';
            $tb_change_field = str_replace('ros_', '#__', $main_table);
            $ros_rform_moaos  =   $connect->table("$main_table AS a")
                                        ->select( 'a.*',
                                                    DB::raw("(select ordering from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_ordering"), 
                                                    DB::raw("(select permit_date3 from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_date"), 
                                                    DB::raw("(select REPLACE(permit_no,' ','') from $permit_no_table where ref_id = a.id order by id desc limit 1) as permit_no")
                                                )->whereExists(function ($query) use($condition_table, $tb_change_field){
                                                    $query->select(DB::raw(1))
                                                        ->from("$condition_table AS change_li")
                                                        ->whereRaw('change_li.change_id = a.id and change_li.tb_change = "'.$tb_change_field.'" and change_li.ordering >= 2');
                                                })->get();
                
                // $this->checkFieldExistMoao81($ros_rform_moaos->first() ?? (object)[]);
    // dd($ros_rform_moaos);
            $tb4_tisilicenses = DB::table('tb4_tisilicense')->get();
            $data_insert = [];
            $update_count = [];
            foreach ($ros_rform_moaos as $key => $data_moao) {
                $check_address = (!empty($data_moao->factory_address_no_new) || !empty($data_moao->factory_moo_new) || !empty($data_moao->factory_soi_new) || !empty($data_moao->factory_street_new) || !empty($data_moao->factory_subdistrict_new) || !empty($data_moao->factory_district_new) || !empty($data_moao->factory_province_new));
                if(($check_address) && !empty($data_moao->permit_no)){
                    $tb4_tisilicense = $tb4_tisilicenses->where('tbl_licenseNo', $data_moao->permit_no)->first();
                    if(!empty($tb4_tisilicense)){
                        $is_update = false;
                        $data_update = [];
                        $arr_for_json = [];
                        if($check_address){
                            $address_from = self::setAddressFromTextMoao81($data_moao);
                            $address_to = self::setAddressToTextMoao81($data_moao);
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_tradeAddress';
                            $new_object->change_from = $address_from;
                            $new_object->change_to = $address_to;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_tradeAddress'] = $address_to;
                            $is_update = true;
                        }
                        if($is_update){
                            DB::table('tb4_tisilicense')->where('Autono', $tb4_tisilicense->Autono)->update($data_update);
                            $update_count[] = $tb4_tisilicense->Autono;
                            if(!empty($arr_for_json) && count($arr_for_json) > 0){
                                $arr_insert = [];
                                $arr_insert['tbl_licenseNo'] = $tb4_tisilicense->tbl_licenseNo;
                                $arr_insert['tbl_licenseType'] = $tb4_tisilicense->tbl_licenseType;
                                $arr_insert['change_detail'] = json_encode($arr_for_json);
                                $arr_insert['change_display'] = self::displayTb4($arr_for_json, 2);
                                $arr_insert['change_date'] = $data_moao->change_date;
                                $arr_insert['refno'] = $data_moao->refno;
                                $arr_insert['pageNo'] = $data_moao->change_ordering;
                                $arr_insert['change_type'] = 2;
                                $arr_insert['ordering'] = $data_moao->change_ordering;
                                $arr_insert['created_at'] = date('Y-m-d H:i:s');
                                $arr_insert['created_by'] = 'Admin';
                                $arr_insert['updated_at'] = date('Y-m-d H:i:s');
                                $arr_insert['updated_by'] = 'Admin';
                                $data_insert[] = $arr_insert;
                            }
                        }
                    }
                }
            }
            DB::table('tb4_tisilicense_change')->insert($data_insert);
            DB::commit();
            dd(implode(', ', $update_count), 'Update '.count($update_count), 'Insert '.count($data_insert));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    // Clean data moao9
    function cleanTb4TisilicenseMoao9()
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            $connect = DB::connection('mysql_elicense');
            $main_table = 'ros_rform_moao9';
            $permit_no_table = 'ros_rform_moao11';
            $condition_table = 'ros_rform_tisi_license_changes';
            $tb_change_field = str_replace('ros_', '#__', $main_table);
            $ros_rform_moaos  =   $connect->table("$main_table AS a")
                                        ->select( 'a.*',
                                                    DB::raw("(select ordering from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_ordering"), 
                                                    DB::raw("(select permit_date3 from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_date"), 
                                                    DB::raw("(select REPLACE(permit_no,' ','') from $permit_no_table where ref_id = a.id order by id desc limit 1) as permit_no")
                                                )->whereExists(function ($query) use($condition_table, $tb_change_field){
                                                    $query->select(DB::raw(1))
                                                        ->from("$condition_table AS change_li")
                                                        ->whereRaw('change_li.change_id = a.id and change_li.tb_change = "'.$tb_change_field.'" and change_li.ordering >= 2');
                                                })->get();
                
            // $this->checkFieldExistMoao9($ros_rform_moaos->first() ?? (object)[]);
    // dd($ros_rform_moaos);
            $tb4_tisilicenses = DB::table('tb4_tisilicense')->get();
            $data_insert = [];
            $update_count = [];
            foreach ($ros_rform_moaos as $key => $data_moao) {
                $check_address = (!empty($data_moao->transferee_head_address_no) || !empty($data_moao->transferee_head_moo) || !empty($data_moao->transferee_head_soi) || !empty($data_moao->transferee_head_street) || !empty($data_moao->transferee_head_subdistrict) || !empty($data_moao->transferee_head_district) || !empty($data_moao->transferee_head_province));
                if((!empty($data_moao->transferee_tax_number) || !empty($data_moao->transferee_name) || $check_address) && !empty($data_moao->permit_no)){
                    $tb4_tisilicense = $tb4_tisilicenses->where('tbl_licenseNo', $data_moao->permit_no)->first();
                    if(!empty($tb4_tisilicense)){
                        $is_update = false;
                        $data_update = [];
                        $arr_for_json = [];
                        if(!empty($data_moao->transferee_tax_number)){
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_taxpayer';
                            $new_object->change_from = $data_moao->tax_number;
                            $new_object->change_to = $data_moao->transferee_tax_number;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_taxpayer'] = $data_moao->transferee_tax_number;
                            $is_update = true;
                        }
                        if(!empty($data_moao->transferee_name)){
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_tradeName';
                            $new_object->change_from = $data_moao->applicant_name;
                            $new_object->change_to = $data_moao->transferee_name;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_tradeName'] = $data_moao->transferee_name;
                            $is_update = true;
                        }
                        if($check_address){
                            $address_from = self::setAddressFromTextMoao9($data_moao);
                            $address_to = self::setAddressToTextMoao9($data_moao);
                            $new_object = (object)[];
                            $new_object->change_field = 'tbl_tradeAddress';
                            $new_object->change_from = $address_from;
                            $new_object->change_to = $address_to;
                            $arr_for_json[] = $new_object;
                            $data_update['tbl_tradeAddress'] = $address_to;
                            $is_update = true;
                        }
                        if($is_update){
                            DB::table('tb4_tisilicense')->where('Autono', $tb4_tisilicense->Autono)->update($data_update);
                            $update_count[] = $tb4_tisilicense->Autono;
                            if(!empty($arr_for_json) && count($arr_for_json) > 0){
                                $arr_insert = [];
                                $arr_insert['tbl_licenseNo'] = $tb4_tisilicense->tbl_licenseNo;
                                $arr_insert['tbl_licenseType'] = $tb4_tisilicense->tbl_licenseType;
                                $arr_insert['change_detail'] = json_encode($arr_for_json);
                                $arr_insert['change_display'] = self::displayTb4($arr_for_json, 3);
                                $arr_insert['change_date'] = $data_moao->change_date;
                                $arr_insert['refno'] = $data_moao->refno;
                                $arr_insert['pageNo'] = $data_moao->change_ordering;
                                $arr_insert['change_type'] = 3;
                                $arr_insert['ordering'] = $data_moao->change_ordering;
                                $arr_insert['created_at'] = date('Y-m-d H:i:s');
                                $arr_insert['created_by'] = 'Admin';
                                $arr_insert['updated_at'] = date('Y-m-d H:i:s');
                                $arr_insert['updated_by'] = 'Admin';
                                $data_insert[] = $arr_insert;
                            }
                        }
                    }
                }
            }
            DB::table('tb4_tisilicense_change')->insert($data_insert);
            DB::commit();
            dd(implode(', ', $update_count), 'Update '.count($update_count), 'Insert '.count($data_insert));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    // Clean data change license
    function cleanTb4TisilicenseChangeLicense()
    {
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            $connect = DB::connection('mysql_elicense');
            $tb4_tisilicenses = DB::table('tb4_tisilicense')->get();
            $moao2s = $connect->table('ros_rform_moao2')->select(DB::raw("REPLACE(permit_no,' ','') AS permit_no"), 'id')->pluck('permit_no', 'id')->toArray();
            $moao4s = $connect->table('ros_rform_moao4')->select(DB::raw("REPLACE(permit_no,' ','') AS permit_no"), 'id')->pluck('permit_no', 'id')->toArray();
            $moao6s = $connect->table('ros_rform_moao6')->select(DB::raw("REPLACE(permit_no,' ','') AS permit_no"), 'id')->pluck('permit_no', 'id')->toArray();
            $main_table = 'ros_rform_changelicense';
            $permit_no_table = 'ros_rform_changelicense_sub';
            $condition_table = 'ros_rform_tisi_license_changes';
            $tb_change_field = str_replace('ros_', '#__', $main_table);
            $ros_rform_moaos  =   $connect->table("$main_table AS a")
                                        ->select( 'a.*',
                                                    DB::raw("(select ordering from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_ordering"), 
                                                    DB::raw("(select permit_date3 from $condition_table where change_id = a.id and tb_change = '$tb_change_field' order by id desc limit 1) as change_date"), 
                                                    DB::raw("(select param from $permit_no_table where changelicense_id = a.id order by id desc limit 1) as param")
                                                )->whereExists(function ($query) use($condition_table, $tb_change_field){
                                                    $query->select(DB::raw(1))
                                                        ->from("$condition_table AS change_li")
                                                        ->whereRaw('change_li.change_id = a.id and change_li.tb_change = "'.$tb_change_field.'" and change_li.ordering >= 2');
                                                })
                                        ->whereIn('a.change_type', [2,3,4,5,6,9,10])
                                        // ->whereIn('a.change_type', [9,10])
                                        ->get();
                                        // dd($ros_rform_moaos);
            $data_insert = [];
            $update_count = [];
            $tb4change_changetypes = ['2' => '4', '3' => '5', '4' => '6', '5' => '7', '6' => '8', '9' => '11', '10' => '12'];
            foreach($ros_rform_moaos as $data_moao){
                $permit_no = null;
                $arr_for_json = [];
                if($data_moao->permit_type == 2){
                    $permit_no = array_key_exists($data_moao->license_no, $moao2s)?str_replace(' ', '', $moao2s[$data_moao->license_no]):null;
                }else if($data_moao->permit_type == 4){
                    $permit_no = array_key_exists($data_moao->license_no, $moao4s)?str_replace(' ', '', $moao4s[$data_moao->license_no]):null;
                }else if($data_moao->permit_type == 6){
                    $permit_no = array_key_exists($data_moao->license_no, $moao6s)?str_replace(' ', '', $moao6s[$data_moao->license_no]):null;
                }
                if(!empty($permit_no)){
                    $tb4_tisilicense = $tb4_tisilicenses->where('tbl_licenseNo', $permit_no)->first();
                    if(!empty($tb4_tisilicense) && !empty($data_moao->param)){
                        $is_update = false;
                        $data_update = [];
                        $param = json_decode($data_moao->param);
                        if(!empty($param)){
                            $this->checkChangeTypeForUpdate($data_moao, $param, $tb4_tisilicense, $data_update, $arr_for_json, $is_update);
                            if($is_update){
                                DB::table('tb4_tisilicense')->where('tbl_licenseNo', $permit_no)->update($data_update);
                                $update_count[] = $tb4_tisilicense->Autono;
                                $changetype = array_key_exists($data_moao->change_type, $tb4change_changetypes)?$tb4change_changetypes[$data_moao->change_type]:null;
                                if(!empty($arr_for_json) && count($arr_for_json) > 0){
                                    $arr_insert = [];
                                    $arr_insert['tbl_licenseNo'] = $tb4_tisilicense->tbl_licenseNo;
                                    $arr_insert['tbl_licenseType'] = $tb4_tisilicense->tbl_licenseType;
                                    $arr_insert['change_detail'] = json_encode($arr_for_json);
                                    $arr_insert['change_display'] = self::displayTb4($arr_for_json, $changetype);
                                    $arr_insert['change_date'] = $data_moao->change_date;
                                    $arr_insert['refno'] = $data_moao->refno;
                                    $arr_insert['pageNo'] = $data_moao->change_ordering;
                                    $arr_insert['change_type'] = $changetype;
                                    $arr_insert['ordering'] = $data_moao->change_ordering;
                                    $arr_insert['created_at'] = date('Y-m-d H:i:s');
                                    $arr_insert['created_by'] = 'Admin';
                                    $arr_insert['updated_at'] = date('Y-m-d H:i:s');
                                    $arr_insert['updated_by'] = 'Admin';
                                    $data_insert[] = $arr_insert;
                                }
                            }
                        }
                    }
                }
            } 
            DB::table('tb4_tisilicense_change')->insert($data_insert);
            DB::commit();
            dd(implode(', ', $update_count), 'Update '.count($update_count), 'Insert '.count($data_insert));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    function checkChangeTypeForUpdate($data_moao, $param, $tb4_tisilicense, &$data_update, &$arr_for_json, &$is_update)
    {
        if($data_moao->change_type == 2){
            if(!empty($param->received_name)){
                $new_object = (object)[];
                $new_object->change_field = 'tbl_tradeName';
                $new_object->change_from = !empty($param->applicant_name)?$param->applicant_name:$tb4_tisilicense->tbl_tradeName;
                $new_object->change_to = $param->received_name;
                $arr_for_json[] = $new_object;
                $data_update['tbl_tradeName'] = $param->received_name;
                $is_update = true;
            }
        }else if($data_moao->change_type == 3){
            $check_address = (!empty($param->head_address_no) || !empty($param->head_moo) || !empty($param->head_soi) || !empty($param->head_street) || !empty($param->head_subdistrict) || !empty($param->head_district) || !empty($param->head_province));
            if($check_address){
                $address_from = (self::checkPropertyParamExistChangeLicenseType3($param))?self::setAddressFromTextChangeLicenseType3($param):$tb4_tisilicense->tbl_tradeAddress;
                $address_to = self::setAddressToTextChangeLicenseType3($param);
                $new_object = (object)[];
                $new_object->change_field = 'tbl_tradeAddress';
                $new_object->change_from = $address_from;
                $new_object->change_to = $address_to;
                $arr_for_json[] = $new_object;
                $data_update['tbl_tradeAddress'] = $address_to;
                $is_update = true;
            }
        }else if($data_moao->change_type == 4){
            if(!empty($param->new_factory_name)){
                $new_object = (object)[];
                $new_object->change_field = 'tbl_factoryName';
                $new_object->change_from = !empty($param->factory_name)?$param->factory_name:$tb4_tisilicense->tbl_factoryName;
                $new_object->change_to = $param->new_factory_name;
                $arr_for_json[] = $new_object;
                $data_update['tbl_factoryName'] = $param->new_factory_name;
                $is_update = true;
            }
        }else if($data_moao->change_type == 5){
            $check_address = (!empty($param->new_fac_address_no) || !empty($param->new_fac_moo) || !empty($param->new_fac_soi) || !empty($param->new_fac_street) || !empty($param->new_fac_subdistrict) || !empty($param->new_fac_district) || !empty($param->new_fac_province));
            if($check_address){
                $address_from = (self::checkPropertyParamExistChangeLicenseType5($param))?self::setAddressFromTextChangeLicenseType5($param):$tb4_tisilicense->tbl_factoryAddress;
                $address_to = self::setAddressToTextChangeLicenseType5($param);$new_object = (object)[];
                $new_object->change_field = 'tbl_factoryAddress';
                $new_object->change_from = $address_from;
                $new_object->change_to = $address_to;
                $arr_for_json[] = $new_object;
                $data_update['tbl_factoryAddress'] = $address_to;
                $is_update = true;
            }
            if(!empty($param->new_fac_province)){
                $new_object = (object)[];
                $new_object->change_field = 'tbl_factoryInprovince';
                $new_object->change_from = !empty($param->old_fac_province)?$param->old_fac_province:$tb4_tisilicense->tbl_factoryInprovince;
                $new_object->change_to = $param->new_fac_province;
                $arr_for_json[] = $new_object;
                $data_update['tbl_factoryInprovince'] = $param->new_fac_province;
                $is_update = true;
            }
        }else if($data_moao->change_type == 6){
            if(!empty($param->new_factory_regis_no)){
                $new_object = (object)[];
                $new_object->change_field = 'tbl_factoryID';
                $new_object->change_from = !empty($param->old_factory_regis_no)?$param->old_factory_regis_no:$tb4_tisilicense->tbl_factoryID;
                $new_object->change_to = $param->new_factory_regis_no;
                $arr_for_json[] = $new_object;
                $data_update['tbl_factoryID'] = $param->new_factory_regis_no;
                $is_update = true;
            }
        }else if($data_moao->change_type == 9){
            if(!empty($param->new_manufacturer)){
                $new_object = (object)[];
                $new_object->change_field = 'tbl_factoryName';
                $new_object->change_from = !empty($param->manufacturer)?$param->manufacturer:$tb4_tisilicense->tbl_factoryName;
                $new_object->change_to = $param->new_manufacturer;
                $arr_for_json[] = $new_object;
                $data_update['tbl_factoryName'] = $param->new_manufacturer;
                $is_update = true;
            }
        }
    }

    function revertDataTb4()
    {
        DB::beginTransaction();
        try {
            $start = '558';
            $end = '593';
            $tb4changes = DB::table('tb4_tisilicense_change')->where('id', '>=', $start)->where('id', '<=', $end)->get();
            foreach($tb4changes AS $tb4change) {
                $data_update = [];
                $data_update[$tb4change->change_field] = $tb4change->change_from;
                DB::table('tb4_tisilicense')->where('tbl_licenseNo', $tb4change->tbl_licenseNo)->update($data_update);
            }
            DB::table('tb4_tisilicense_change')->where('id', '>=', $start)->where('id', '<=', $end)->delete();
            $increment = (DB::table('tb4_tisilicense_change')->get()->last()->id) + 1;
            DB::statement("ALTER TABLE tb4_tisilicense_change AUTO_INCREMENT = $increment");
            DB::commit();
            dd('Success');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function setAddressFromTextMoao8($data_moao){
        $address = $data_moao->factory_address_no;
        $address .= !empty($data_moao->factory_moo) ? ' หมู่ '.$data_moao->factory_moo : '';
        $address .= !empty($data_moao->factory_soi) ? ' ซอย'.$data_moao->factory_soi : '';
        $address .= !empty($data_moao->factory_street) ? ' ถนน'.$data_moao->factory_street : '';
        $address .= !empty($data_moao->factory_subdistrict) ? ' ตำบล'.$data_moao->factory_subdistrict : '';
        $address .= !empty($data_moao->factory_district) ? ' อำเภอ'.$data_moao->factory_district : '';
        if(!empty($data_moao->factory_province)){
            $krungtew_check = strpos($data_moao->factory_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->factory_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressToTextMoao8($data_moao){
        $address = $data_moao->factory_address_no_new;
        $address .= !empty($data_moao->factory_moo_new) ? ' หมู่ '.$data_moao->factory_moo_new : '';
        $address .= !empty($data_moao->factory_soi_new) ? ' ซอย'.$data_moao->factory_soi_new : '';
        $address .= !empty($data_moao->factory_street_new) ? ' ถนน'.$data_moao->factory_street_new : '';
        $address .= !empty($data_moao->factory_subdistrict_new) ? ' ตำบล'.$data_moao->factory_subdistrict_new : '';
        $address .= !empty($data_moao->factory_district_new) ? ' อำเภอ'.$data_moao->factory_district_new : '';
        if(!empty($data_moao->factory_province_new)){
            $krungtew_check = strpos($data_moao->factory_province_new, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->factory_province_new;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressFromTextMoao81($data_moao){
        $address = $data_moao->factory_address_no;
        $address .= !empty($data_moao->factory_moo) ? ' หมู่ '.$data_moao->factory_moo : '';
        $address .= !empty($data_moao->factory_soi) ? ' ซอย'.$data_moao->factory_soi : '';
        $address .= !empty($data_moao->factory_street) ? ' ถนน'.$data_moao->factory_street : '';
        $address .= !empty($data_moao->factory_subdistrict) ? ' ตำบล'.$data_moao->factory_subdistrict : '';
        $address .= !empty($data_moao->factory_district) ? ' อำเภอ'.$data_moao->factory_district : '';
        if(!empty($data_moao->factory_province)){
            $krungtew_check = strpos($data_moao->factory_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->factory_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressToTextMoao81($data_moao){
        $address = $data_moao->factory_address_no_new;
        $address .= !empty($data_moao->factory_moo_new) ? ' หมู่ '.$data_moao->factory_moo_new : '';
        $address .= !empty($data_moao->factory_soi_new) ? ' ซอย'.$data_moao->factory_soi_new : '';
        $address .= !empty($data_moao->factory_street_new) ? ' ถนน'.$data_moao->factory_street_new : '';
        $address .= !empty($data_moao->factory_subdistrict_new) ? ' ตำบล'.$data_moao->factory_subdistrict_new : '';
        $address .= !empty($data_moao->factory_district_new) ? ' อำเภอ'.$data_moao->factory_district_new : '';
        if(!empty($data_moao->factory_province_new)){
            $krungtew_check = strpos($data_moao->factory_province_new, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->factory_province_new;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressFromTextMoao9($data_moao){
        $address = $data_moao->head_address_no;
        $address .= !empty($data_moao->head_moo) ? ' หมู่ '.$data_moao->head_moo : '';
        $address .= !empty($data_moao->head_soi) ? ' ซอย'.$data_moao->head_soi : '';
        $address .= !empty($data_moao->head_street) ? ' ถนน'.$data_moao->head_street : '';
        $address .= !empty($data_moao->head_subdistrict) ? ' ตำบล'.$data_moao->head_subdistrict : '';
        $address .= !empty($data_moao->head_district) ? ' อำเภอ'.$data_moao->head_district : '';
        if(!empty($data_moao->head_province)){
            $krungtew_check = strpos($data_moao->head_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->head_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressToTextMoao9($data_moao){
        $address = $data_moao->transferee_head_address_no;
        $address .= !empty($data_moao->transferee_head_moo) ? ' หมู่ '.$data_moao->transferee_head_moo : '';
        $address .= !empty($data_moao->transferee_head_soi) ? ' ซอย'.$data_moao->transferee_head_soi : '';
        $address .= !empty($data_moao->transferee_head_street) ? ' ถนน'.$data_moao->transferee_head_street : '';
        $address .= !empty($data_moao->transferee_head_subdistrict) ? ' ตำบล'.$data_moao->transferee_head_subdistrict : '';
        $address .= !empty($data_moao->transferee_head_district) ? ' อำเภอ'.$data_moao->transferee_head_district : '';
        if(!empty($data_moao->transferee_head_province)){
            $krungtew_check = strpos($data_moao->transferee_head_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$data_moao->transferee_head_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressFromTextChangeLicenseType3($param){
        $address = $param->head_address_no_old;
        $address .= !empty($param->head_moo_old) ? ' หมู่ '.$param->head_moo_old : '';
        $address .= !empty($param->head_soi_old) ? ' ซอย'.$param->head_soi_old : '';
        $address .= !empty($param->head_street_old) ? ' ถนน'.$param->head_street_old : '';
        $address .= !empty($param->head_subdistrict_old) ? ' ตำบล'.$param->head_subdistrict_old : '';
        $address .= !empty($param->head_district_old) ? ' อำเภอ'.$param->head_district_old : '';
        if(!empty($param->head_province_old)){
            $krungtew_check = strpos($param->head_province_old, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$param->head_province_old;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressToTextChangeLicenseType3($param){
        $address = $param->head_address_no;
        $address .= !empty($param->head_moo) ? ' หมู่ '.$param->head_moo : '';
        $address .= !empty($param->head_soi) ? ' ซอย'.$param->head_soi : '';
        $address .= !empty($param->head_street) ? ' ถนน'.$param->head_street : '';
        $address .= !empty($param->head_subdistrict) ? ' ตำบล'.$param->head_subdistrict : '';
        $address .= !empty($param->head_district) ? ' อำเภอ'.$param->head_district : '';
        if(!empty($param->head_province)){
            $krungtew_check = strpos($param->head_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$param->head_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressFromTextChangeLicenseType5($param){
        $address = $param->old_fac_address_no;
        $address .= !empty($param->old_fac_moo) ? ' หมู่ '.$param->old_fac_moo : '';
        $address .= !empty($param->old_fac_soi) ? ' ซอย'.$param->old_fac_soi : '';
        $address .= !empty($param->old_fac_street) ? ' ถนน'.$param->old_fac_street : '';
        $address .= !empty($param->old_fac_subdistrict) ? ' ตำบล'.$param->old_fac_subdistrict : '';
        $address .= !empty($param->old_fac_district) ? ' อำเภอ'.$param->old_fac_district : '';
        if(!empty($param->old_fac_province)){
            $krungtew_check = strpos($param->old_fac_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$param->old_fac_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function setAddressToTextChangeLicenseType5($param){
        $address = $param->new_fac_address_no;
        $address .= !empty($param->new_fac_moo) ? ' หมู่ '.$param->new_fac_moo : '';
        $address .= !empty($param->new_fac_soi) ? ' ซอย'.$param->new_fac_soi : '';
        $address .= !empty($param->new_fac_street) ? ' ถนน'.$param->new_fac_street : '';
        $address .= !empty($param->new_fac_subdistrict) ? ' ตำบล'.$param->new_fac_subdistrict : '';
        $address .= !empty($param->new_fac_district) ? ' อำเภอ'.$param->new_fac_district : '';
        if(!empty($param->new_fac_province)){
            $krungtew_check = strpos($param->new_fac_province, 'กรุงเทพมหานคร');
            if($krungtew_check || $krungtew_check === 0){
                $address .= ' กรุงเทพมหานคร';
            }else{
                $address .= ' จังหวัด'.$param->new_fac_province;
            }
        }
        $check = strpos($address, 'กรุงเทพมหานคร');
        if($check || $check === 0){
            $address = str_replace('จังหวัด', '', $address);
            $address = str_replace('ตำบล', 'แขวง', $address);
            $address = str_replace('อำเภอ', 'เขต', $address);
        }
        return $address;
    }

    public function checkPropertyParamExistChangeLicenseType3($param){
        return  property_exists($param, 'head_address_no_old') && 
                property_exists($param, 'head_moo_old') && 
                property_exists($param, 'head_soi_old') && 
                property_exists($param, 'head_street_old') && 
                property_exists($param, 'head_subdistrict_old') && 
                property_exists($param, 'head_district_old') && 
                property_exists($param, 'head_province_old');
    }

    public function checkPropertyParamExistChangeLicenseType5($param){
        return  property_exists($param, 'old_fac_address_no') && 
                property_exists($param, 'old_fac_soi') && 
                property_exists($param, 'old_fac_street') && 
                property_exists($param, 'old_fac_moo') && 
                property_exists($param, 'old_fac_subdistrict') && 
                property_exists($param, 'old_fac_district') && 
                property_exists($param, 'old_fac_province');
    }
    
    public function displayTb4($change_details, $change_type){
        $file_names = [
                        '1' => 'moao8', 
                        '2' => 'moao81', 
                        '3' => 'moao9', 
                        '4' => 'change_license_type4', 
                        '5' => 'change_license_type5', 
                        '6' => 'change_license_type6', 
                        '7' => 'change_license_type7', 
                        '8' => 'change_license_type8', 
                        '11' => 'change_license_type11'
                    ];
        if(array_key_exists($change_type, $file_names)){
            $file_name = $file_names[$change_type];
            return view('chang_display.'.$file_name, compact('change_details'))->render();
        }
    }
    
    public function updateDisplayTb4(){
        $changes = DB::table('tb4_tisilicense_change')->where('id', '>=', 608)->where('id', '<=', 861)->get();
        $changes->each(function($change, $key){
            $change_details = json_decode($change->change_detail);
            $change_type = $change->change_type;
            if($change_type == 1){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.moao8', compact('change_details'))->render()]);
            }else if($change_type == 2){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.moao81', compact('change_details'))->render()]);
            }else if($change_type == 3){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.moao9', compact('change_details'))->render()]);
            }else if($change_type == 4){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.change_license_type'.$change_type, compact('change_details'))->render()]);
            }else if($change_type == 5){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.change_license_type'.$change_type, compact('change_details'))->render()]);
            }else if($change_type == 7){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.change_license_type'.$change_type, compact('change_details'))->render()]);
            }else if($change_type == 8){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.change_license_type'.$change_type, compact('change_details'))->render()]);
            }else if($change_type == 11){
                DB::table('tb4_tisilicense_change')->where('id', $change->id)->update(['change_display' => view('chang_display.change_license_type'.$change_type, compact('change_details'))->render()]);
            }
        });
        dd('Success!!');
    }
    
    public function displayTb4Show(){
        $changes = DB::table('tb4_tisilicense_change')->get();
        foreach($changes as $key=>$change){
           echo $change->change_display.'<hr>'; 
        }
    }

    // public function checkFieldExistMoao81($data_moao){
    //     $check_field =  property_exists($data_moao, 'factory_address_no_new') && 
    //                     property_exists($data_moao, 'factory_moo_new') && 
    //                     property_exists($data_moao, 'factory_soi_new') && 
    //                     property_exists($data_moao, 'factory_street_new') && 
    //                     property_exists($data_moao, 'factory_subdistrict_new') && 
    //                     property_exists($data_moao, 'factory_district_new') && 
    //                     property_exists($data_moao, 'factory_province_new') && 
    //                     property_exists($data_moao, 'factory_tel_new') && 
    //                     property_exists($data_moao, 'permit_no');
    //     if(!$check_field){
    //         dd('Field is not exists');
    //     }
    // }

    // public function checkFieldExistMoao9($data_moao){
    //     $check_field =  property_exists($data_moao, 'transferee_tax_number') && 
    //     property_exists($data_moao, 'transferee_name') && 
    //                     property_exists($data_moao, 'transferee_head_address_no') && 
    //                     property_exists($data_moao, 'transferee_head_street') && 
    //                     property_exists($data_moao, 'transferee_head_moo') && 
    //                     property_exists($data_moao, 'transferee_head_soi') && 
    //                     property_exists($data_moao, 'transferee_head_subdistrict') && 
    //                     property_exists($data_moao, 'transferee_head_district') && 
    //                     property_exists($data_moao, 'transferee_head_province') && 
    //                     property_exists($data_moao, 'permit_no');
    //     if(!$check_field){
    //         dd('Field is not exists');
    //     }
    // }

    // public function checkFieldExistChangeLicense($data_moao){
    //     $check_field =  property_exists($data_moao, 'tax_number') && 
    //                     property_exists($data_moao, 'applicant_name') && 
    //                     property_exists($data_moao, 'permit_no');
    //     if(!$check_field){
    //         dd('Field is not exists');
    //     }
    // }

}
