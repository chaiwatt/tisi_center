<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\WS\Log;
use App\Models\Setting\SettingSystem;
use App\Models\Elicense\ELoc\RosManufacturerForeignScope;
use App\Models\Elicense\ELoc\RosManufacturerForeign;
use App\Models\Elicense\Tis\RosStandardTisi;
use App\Models\Elicense\Basic\Country;
use App\Models\Elicense\Basic\States;
use App\Models\Elicense\Basic\Citys;
use App\Sessions;
use App\User;
use HP_API;
use HP;

class ElicenseCerticate extends Controller
{
    public function elicense_no(Request $request)
    {
        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('permit_no' );
        $rule = [ 'permit_no' => 'required|string' ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {

            $error = $validator->messages();
            Log::Add($app_name, __FUNCTION__, '200', $error->toJson());
            return response()->json(['status'=> '200', 'message'=> $error]);

        }

        $permit_nos = !empty($input['permit_no'])?$input['permit_no']:null;

        $message = 'Found the Data.';

        $tisi_licenses =  DB::connection('mysql_elicense')->table("ros_rform_tisi_licenses")->whereNotNull('permit_no')->where('permit_no', $permit_nos )->first();

        if(is_null($tisi_licenses)){//ไม่พบข้อมูลผู้ใช้งาน
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
        }

        $result = (object)[];

        if( !is_null($tisi_licenses) ){

            $tis_number = null;
            $tis_name =null;
            $applicant_name = null;
            if( !empty($tisi_licenses->tb_ref) && !empty($tisi_licenses->ref_id)  ){

                $tb_ref = str_replace("#_","ros",$tisi_licenses->tb_ref);

                $sql_ref = DB::connection('mysql_elicense')->table( $tb_ref )->where('id', $tisi_licenses->ref_id )->first();
                if( !is_null( $sql_ref ) ){
                    $tis_number =  !empty($sql_ref->tis_number)?$sql_ref->tis_number:null;
                    $tis_name =  !empty($sql_ref->tis_name)?$sql_ref->tis_name:null;
                    $applicant_name =  !empty($sql_ref->applicant_name)?$sql_ref->applicant_name:null;
                }
            }

            $result->permit_no = !empty($tisi_licenses->permit_no)?$tisi_licenses->permit_no:null;
            $result->permit_date = !empty($tisi_licenses->permit_date)?$tisi_licenses->permit_date:null;
            // $result->permit_date = !empty($tisi_licenses->permit_date)?( \Carbon\Carbon::parse($tisi_licenses->permit_date)->addYears(543)->format('d-m-Y') ):null;
            $result->tis_number = !empty($tis_number)?$tis_number:null;
            $result->tis_name = !empty($tis_name)?$tis_name:null;
            $result->tbl_licenseType = !empty($tisi_licenses->tbl_licenseType)?$tisi_licenses->tbl_licenseType:null;
            $result->applicant_name = !empty($applicant_name)?$applicant_name:null;
            $result->tax_number = !empty($tisi_licenses->tax_number)?$tisi_licenses->tax_number:null;

            //head_address
            $result->head_address['head_address_no'] = !empty($tisi_licenses->head_address_no)?$tisi_licenses->head_address_no:null;
            $result->head_address['head_street'] = !empty($tisi_licenses->head_street)?$tisi_licenses->head_street:null;
            $result->head_address['head_moo'] = !empty($tisi_licenses->head_moo)?$tisi_licenses->head_moo:null;
            $result->head_address['head_soi'] = !empty($tisi_licenses->head_soi)?$tisi_licenses->head_soi:null;
            $result->head_address['head_subdistrict'] = !empty($tisi_licenses->head_subdistrict)?$tisi_licenses->head_subdistrict:null;
            $result->head_address['head_district'] = !empty($tisi_licenses->head_district)?$tisi_licenses->head_district:null;
            $result->head_address['head_province'] = !empty($tisi_licenses->head_province)?$tisi_licenses->head_province:null;
            $result->head_address['head_zipcode'] = !empty($tisi_licenses->head_zipcode)?$tisi_licenses->head_zipcode:null;
            $result->head_tel = !empty($tisi_licenses->head_tel)?$tisi_licenses->head_tel:null;
            $result->head_fax = !empty($tisi_licenses->head_fax)?$tisi_licenses->head_fax:null;

            //factory
            $result->factory_name = !empty($tisi_licenses->factory_name)?$tisi_licenses->factory_name:null;
            $result->factory_regis_no_new = !empty($tisi_licenses->factory_regis_no_new)?$tisi_licenses->factory_regis_no_new:null;
            $result->factory_address['factory_address_no'] = !empty($tisi_licenses->factory_address_no)?$tisi_licenses->factory_address_no:null;
            $result->factory_address['factory_street'] = !empty($tisi_licenses->factory_street)?$tisi_licenses->factory_street:null;
            $result->factory_address['factory_moo'] = !empty($tisi_licenses->factory_moo)?$tisi_licenses->factory_moo:null;
            $result->factory_address['factory_soi'] = !empty($tisi_licenses->factory_soi)?$tisi_licenses->factory_soi:null;
            $result->factory_address['factory_subdistrict'] = !empty($tisi_licenses->factory_subdistrict)?$tisi_licenses->factory_subdistrict:null;
            $result->factory_address['factory_district'] = !empty($tisi_licenses->factory_district)?$tisi_licenses->factory_district:null;
            $result->factory_address['factory_province'] = !empty($tisi_licenses->factory_province)?$tisi_licenses->factory_province:null;
            $result->factory_address['factory_zipcode'] = !empty($tisi_licenses->factory_zipcode)?$tisi_licenses->factory_zipcode:null;
            $result->factory_tel = !empty($tisi_licenses->factory_tel)?$tisi_licenses->factory_tel:null;
            $result->factory_fax = !empty($tisi_licenses->factory_fax)?$tisi_licenses->factory_fax:null;


            $license_details_ids =  DB::connection('mysql_elicense')->table("ros_rform_tisi_license_details")->where('license_id', $tisi_licenses->id )->select('id');



            $details_subs =  DB::connection('mysql_elicense')->table("ros_rform_tisi_license_detail_subs")->whereIn('detail_id', $license_details_ids )->whereNotNull('standard_detail')->orderBy('detail_id')->get();

            $data_sub = [];
            foreach(  $details_subs as $subs ){
                $data_sub[] = $subs->standard_detail;
            }

            $result->standard_detail_list = $data_sub;


        }

        $status  = '000';
        Log::Add($app_name, __FUNCTION__, $status, $message);

        return response()->json(['status' => '000', 'message' => $message, 'result' => $result], 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function tis_number(Request $request){

        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('tis_number', 'page');
        $rule = [
                 'tis_number' => 'required|string',
                 'page' => 'integer'
                ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $error = $validator->messages();
            Log::Add($app_name, __FUNCTION__, '200', $error->toJson());
            return response()->json(['status'=> '200', 'message'=> $error]);
        }

        $page     = array_key_exists('page', $input) ? $input['page'] : 1 ;//หน้า
        $per_page = 50; //จำนวนรายการต่อหน้า
        $start = ($page-1) * $per_page;

        $filter_tis_number = !empty($input['tis_number'])?$input['tis_number']:null;

        $message = 'Found the Data.';

        $query = DB::connection('mysql_elicense')
                   ->table("ros_rform_tisi_licenses")
                   ->whereNotNull('permit_no')
                   ->when($filter_tis_number, function($query, $filter_tis_number){
                       $query->where('tis_number', $filter_tis_number);
                   });

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $_licenses = $query->skip($start)
                           ->take($per_page)
                           ->get();

        if(count($_licenses) == 0){//ไม่พบข้อมูลผู้ใช้งาน
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
        }

        $result_list = [];

        foreach($_licenses as $tisi_licenses){

            $result = (object)[];

            $tis_number = null;
            $tis_name =null;
            $applicant_name = null;
            if( !empty($tisi_licenses->tb_ref) && !empty($tisi_licenses->ref_id)  ){

                $tb_ref = str_replace("#_","ros",$tisi_licenses->tb_ref);

                $sql_ref =  DB::connection('mysql_elicense')->table( $tb_ref )->where('id', $tisi_licenses->ref_id )->first();
                if( !is_null( $sql_ref ) ){
                    $tis_number =  !empty($sql_ref->tis_number)?$sql_ref->tis_number:null;
                    $tis_name =  !empty($sql_ref->tis_name)?$sql_ref->tis_name:null;
                    $applicant_name =  !empty($sql_ref->applicant_name)?$sql_ref->applicant_name:null;
                }
            }

            $result->permit_no = !empty($tisi_licenses->permit_no)?$tisi_licenses->permit_no:null;
            $result->permit_date = !empty($tisi_licenses->permit_date)?$tisi_licenses->permit_date:null;
            $result->tis_number = !empty($tis_number)?$tis_number:null;
            $result->tis_name = !empty($tis_name)?$tis_name:null;
            $result->tbl_licenseType = !empty($tisi_licenses->tbl_licenseType)?$tisi_licenses->tbl_licenseType:null;
            $result->applicant_name = !empty($applicant_name)?$applicant_name:null;
            $result->tax_number = !empty($tisi_licenses->tax_number)?$tisi_licenses->tax_number:null;

            //head_address
            $result->head_address['head_address_no'] = !empty($tisi_licenses->head_address_no)?$tisi_licenses->head_address_no:null;
            $result->head_address['head_street'] = !empty($tisi_licenses->head_street)?$tisi_licenses->head_street:null;
            $result->head_address['head_moo'] = !empty($tisi_licenses->head_moo)?$tisi_licenses->head_moo:null;
            $result->head_address['head_soi'] = !empty($tisi_licenses->head_soi)?$tisi_licenses->head_soi:null;
            $result->head_address['head_subdistrict'] = !empty($tisi_licenses->head_subdistrict)?$tisi_licenses->head_subdistrict:null;
            $result->head_address['head_district'] = !empty($tisi_licenses->head_district)?$tisi_licenses->head_district:null;
            $result->head_address['head_province'] = !empty($tisi_licenses->head_province)?$tisi_licenses->head_province:null;
            $result->head_address['head_zipcode'] = !empty($tisi_licenses->head_zipcode)?$tisi_licenses->head_zipcode:null;
            $result->head_tel = !empty($tisi_licenses->head_tel)?$tisi_licenses->head_tel:null;
            $result->head_fax = !empty($tisi_licenses->head_fax)?$tisi_licenses->head_fax:null;

            //factory
            $result->factory_name = !empty($tisi_licenses->factory_name)?$tisi_licenses->factory_name:null;
            $result->factory_regis_no_new = !empty($tisi_licenses->factory_regis_no_new)?$tisi_licenses->factory_regis_no_new:null;
            $result->factory_address['factory_address_no'] = !empty($tisi_licenses->factory_address_no)?$tisi_licenses->factory_address_no:null;
            $result->factory_address['factory_street'] = !empty($tisi_licenses->factory_street)?$tisi_licenses->factory_street:null;
            $result->factory_address['factory_moo'] = !empty($tisi_licenses->factory_moo)?$tisi_licenses->factory_moo:null;
            $result->factory_address['factory_soi'] = !empty($tisi_licenses->factory_soi)?$tisi_licenses->factory_soi:null;
            $result->factory_address['factory_subdistrict'] = !empty($tisi_licenses->factory_subdistrict)?$tisi_licenses->factory_subdistrict:null;
            $result->factory_address['factory_district'] = !empty($tisi_licenses->factory_district)?$tisi_licenses->factory_district:null;
            $result->factory_address['factory_province'] = !empty($tisi_licenses->factory_province)?$tisi_licenses->factory_province:null;
            $result->factory_address['factory_zipcode'] = !empty($tisi_licenses->factory_zipcode)?$tisi_licenses->factory_zipcode:null;
            $result->factory_tel = !empty($tisi_licenses->factory_tel)?$tisi_licenses->factory_tel:null;
            $result->factory_fax = !empty($tisi_licenses->factory_fax)?$tisi_licenses->factory_fax:null;

            $license_details_ids = DB::connection('mysql_elicense')->table("ros_rform_tisi_license_details")->where('license_id', $tisi_licenses->id )->select('id');

            $details_subs = DB::connection('mysql_elicense')->table("ros_rform_tisi_license_detail_subs")->whereIn('detail_id', $license_details_ids )->whereNotNull('standard_detail')->orderBy('detail_id')->get();

            $data_sub = [];
            foreach($details_subs as $subs){
                $data_sub[] = $subs->standard_detail;
            }

            $result->standard_detail_list = $data_sub;

            $result_list[] = $result;
        }

        $data = array(
                    'page_current' => (int)$page,
                    'page_all'     => (int)ceil($item_all/$per_page),
                    'item_all'     => (int)$item_all,
                    'item_list'    => $result_list
                );

        $status = '000';
        Log::Add($app_name, __FUNCTION__, $status, $message);

        return response()->json(['status' => '000', 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function tax_number(Request $request){

        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('tax_number', 'page');
        $rule  = [
                   'tax_number' => 'required|string',
                   'page'       => 'integer'
                 ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $error = $validator->messages();
            Log::Add($app_name, __FUNCTION__, '200', $error->toJson());
            return response()->json(['status'=> '200', 'message'=> $error]);
        }

        $page     = array_key_exists('page', $input) ? $input['page'] : 1 ; //หน้า
        $per_page = 50; //จำนวนรายการต่อหน้า
        $start = ($page-1) * $per_page;

        $tax_numbers = !empty($input['tax_number']) ? $input['tax_number'] : null ;

        $message = 'Found the Data.';

        $query =  DB::connection('mysql_elicense')
                    ->table("ros_rform_tisi_licenses")
                    ->whereNotNull('permit_no')
                    ->where(DB::raw("REPLACE(tax_number,' ','')"), $tax_numbers);

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $_licenses = $query->skip($start)
                           ->take($per_page)
                           ->get();

        if(count($_licenses) == 0){//ไม่พบข้อมูลผู้ใช้งาน
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status' => '503', 'message' => 'Data not found.']);
        }

        $result_list = [];

        foreach($_licenses as $tisi_licenses){

            $result = (object)[];

            $tis_number = null;
            $tis_name = null;
            $applicant_name = null;
            if(!empty($tisi_licenses->tb_ref) && !empty($tisi_licenses->ref_id)){

                $tb_ref = str_replace("#_","ros",$tisi_licenses->tb_ref);

                $sql_ref = DB::connection('mysql_elicense')->table( $tb_ref )->where('id', $tisi_licenses->ref_id )->first();
                if( !is_null( $sql_ref ) ){
                    $tis_number = !empty($sql_ref->tis_number)?$sql_ref->tis_number:null;
                    $tis_name = !empty($sql_ref->tis_name)?$sql_ref->tis_name:null;
                    $applicant_name = !empty($sql_ref->applicant_name)?$sql_ref->applicant_name:null;
                }
            }

            $result->permit_no = !empty($tisi_licenses->permit_no)?$tisi_licenses->permit_no:null;
            $result->permit_date = !empty($tisi_licenses->permit_date)?$tisi_licenses->permit_date:null;
            // $result->permit_date = !empty($tisi_licenses->permit_date)?( \Carbon\Carbon::parse($tisi_licenses->permit_date)->addYears(543)->format('d-m-Y') ):null;
            $result->tis_number = !empty($tis_number)?$tis_number:null;
            $result->tis_name = !empty($tis_name)?$tis_name:null;
            $result->tbl_licenseType = !empty($tisi_licenses->tbl_licenseType)?$tisi_licenses->tbl_licenseType:null;
            $result->applicant_name = !empty($applicant_name)?$applicant_name:null;
            $result->tax_number = !empty($tisi_licenses->tax_number)?$tisi_licenses->tax_number:null;

            //head_address
            $result->head_address['head_address_no'] = !empty($tisi_licenses->head_address_no)?$tisi_licenses->head_address_no:null;
            $result->head_address['head_street'] = !empty($tisi_licenses->head_street)?$tisi_licenses->head_street:null;
            $result->head_address['head_moo'] = !empty($tisi_licenses->head_moo)?$tisi_licenses->head_moo:null;
            $result->head_address['head_soi'] = !empty($tisi_licenses->head_soi)?$tisi_licenses->head_soi:null;
            $result->head_address['head_subdistrict'] = !empty($tisi_licenses->head_subdistrict)?$tisi_licenses->head_subdistrict:null;
            $result->head_address['head_district'] = !empty($tisi_licenses->head_district)?$tisi_licenses->head_district:null;
            $result->head_address['head_province'] = !empty($tisi_licenses->head_province)?$tisi_licenses->head_province:null;
            $result->head_address['head_zipcode'] = !empty($tisi_licenses->head_zipcode)?$tisi_licenses->head_zipcode:null;
            $result->head_tel = !empty($tisi_licenses->head_tel)?$tisi_licenses->head_tel:null;
            $result->head_fax = !empty($tisi_licenses->head_fax)?$tisi_licenses->head_fax:null;

            //factory
            $result->factory_name = !empty($tisi_licenses->factory_name)?$tisi_licenses->factory_name:null;
            $result->factory_regis_no_new = !empty($tisi_licenses->factory_regis_no_new)?$tisi_licenses->factory_regis_no_new:null;
            $result->factory_address['factory_address_no'] = !empty($tisi_licenses->factory_address_no)?$tisi_licenses->factory_address_no:null;
            $result->factory_address['factory_street'] = !empty($tisi_licenses->factory_street)?$tisi_licenses->factory_street:null;
            $result->factory_address['factory_moo'] = !empty($tisi_licenses->factory_moo)?$tisi_licenses->factory_moo:null;
            $result->factory_address['factory_soi'] = !empty($tisi_licenses->factory_soi)?$tisi_licenses->factory_soi:null;
            $result->factory_address['factory_subdistrict'] = !empty($tisi_licenses->factory_subdistrict)?$tisi_licenses->factory_subdistrict:null;
            $result->factory_address['factory_district'] = !empty($tisi_licenses->factory_district)?$tisi_licenses->factory_district:null;
            $result->factory_address['factory_province'] = !empty($tisi_licenses->factory_province)?$tisi_licenses->factory_province:null;
            $result->factory_address['factory_zipcode'] = !empty($tisi_licenses->factory_zipcode)?$tisi_licenses->factory_zipcode:null;
            $result->factory_tel = !empty($tisi_licenses->factory_tel)?$tisi_licenses->factory_tel:null;
            $result->factory_fax = !empty($tisi_licenses->factory_fax)?$tisi_licenses->factory_fax:null;

            $license_details_ids = DB::connection('mysql_elicense')->table("ros_rform_tisi_license_details")->where('license_id', $tisi_licenses->id )->select('id');

            $details_subs = DB::connection('mysql_elicense')->table("ros_rform_tisi_license_detail_subs")->whereIn('detail_id', $license_details_ids )->whereNotNull('standard_detail')->orderBy('detail_id')->get();

            $data_sub = [];
            foreach($details_subs as $subs){
                $data_sub[] = $subs->standard_detail;
            }

            $result->standard_detail_list = $data_sub;

            $result_list[] = $result;
        }

        $data = array(
                    'page_current' => (int)$page,
                    'page_all'     => (int)ceil($item_all/$per_page),
                    'item_all'     => (int)$item_all,
                    'item_list'    => $result_list
                );

        $status  = '000';
        Log::Add($app_name, __FUNCTION__, $status, $message);

        return response()->json(['status' => '000', 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function license_type(Request $request){

        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }


        $input = $request->only('license_type', 'page');
        $rule  = [
                    'license_type' => 'required|string',
                    'page'         => 'integer'
                ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $error = $validator->messages();
            Log::Add($app_name, __FUNCTION__, '200', $error->toJson());
            return response()->json(['status'=> '200', 'message'=> $error]);
        }

        $page     = array_key_exists('page', $input) ? $input['page'] : 1 ; //หน้า
        $per_page = 50; //จำนวนรายการต่อหน้า
        $start = ($page-1) * $per_page;

        $tbl_licenseTypes = !empty($input['license_type'])?$input['license_type']:null;

        $message = 'Found the Data.';

        $query = DB::connection('mysql_elicense')
                   ->table("ros_rform_tisi_licenses")
                   ->whereNotNull('permit_no')
                   ->where(DB::raw("REPLACE(tbl_licenseType, ' ', '')"), $tbl_licenseTypes);

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $_licenses = $query->skip($start)
                           ->take($per_page)
                           ->get();

        if(count($_licenses) == 0){//ไม่พบข้อมูลผู้ใช้งาน
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
        }

        $result_list = [];

        foreach(  $_licenses as $tisi_licenses ){

            $result = (object)[];

            $tis_number = null;
            $tis_name =null;
            $applicant_name = null;
            if( !empty($tisi_licenses->tb_ref) && !empty($tisi_licenses->ref_id)  ){

                $tb_ref = str_replace("#_","ros",$tisi_licenses->tb_ref);

                $sql_ref =  DB::connection('mysql_elicense')->table( $tb_ref )->where('id', $tisi_licenses->ref_id )->first();
                if( !is_null( $sql_ref ) ){
                    $tis_number =  !empty($sql_ref->tis_number)?$sql_ref->tis_number:null;
                    $tis_name =  !empty($sql_ref->tis_name)?$sql_ref->tis_name:null;
                    $applicant_name =  !empty($sql_ref->applicant_name)?$sql_ref->applicant_name:null;
                }
            }

            $result->permit_no = !empty($tisi_licenses->permit_no)?$tisi_licenses->permit_no:null;
            $result->permit_date = !empty($tisi_licenses->permit_date)?$tisi_licenses->permit_date:null;
            // $result->permit_date = !empty($tisi_licenses->permit_date)?( \Carbon\Carbon::parse($tisi_licenses->permit_date)->addYears(543)->format('d-m-Y') ):null;
            $result->tis_number = !empty($tis_number)?$tis_number:null;
            $result->tis_name = !empty($tis_name)?$tis_name:null;
            $result->tbl_licenseType = !empty($tisi_licenses->tbl_licenseType)?$tisi_licenses->tbl_licenseType:null;
            $result->applicant_name = !empty($applicant_name)?$applicant_name:null;
            $result->tax_number = !empty($tisi_licenses->tax_number)?$tisi_licenses->tax_number:null;

            //head_address
            $result->head_address['head_address_no'] = !empty($tisi_licenses->head_address_no)?$tisi_licenses->head_address_no:null;
            $result->head_address['head_street'] = !empty($tisi_licenses->head_street)?$tisi_licenses->head_street:null;
            $result->head_address['head_moo'] = !empty($tisi_licenses->head_moo)?$tisi_licenses->head_moo:null;
            $result->head_address['head_soi'] = !empty($tisi_licenses->head_soi)?$tisi_licenses->head_soi:null;
            $result->head_address['head_subdistrict'] = !empty($tisi_licenses->head_subdistrict)?$tisi_licenses->head_subdistrict:null;
            $result->head_address['head_district'] = !empty($tisi_licenses->head_district)?$tisi_licenses->head_district:null;
            $result->head_address['head_province'] = !empty($tisi_licenses->head_province)?$tisi_licenses->head_province:null;
            $result->head_address['head_zipcode'] = !empty($tisi_licenses->head_zipcode)?$tisi_licenses->head_zipcode:null;
            $result->head_tel = !empty($tisi_licenses->head_tel)?$tisi_licenses->head_tel:null;
            $result->head_fax = !empty($tisi_licenses->head_fax)?$tisi_licenses->head_fax:null;

            //factory
            $result->factory_name = !empty($tisi_licenses->factory_name)?$tisi_licenses->factory_name:null;
            $result->factory_regis_no_new = !empty($tisi_licenses->factory_regis_no_new)?$tisi_licenses->factory_regis_no_new:null;
            $result->factory_address['factory_address_no'] = !empty($tisi_licenses->factory_address_no)?$tisi_licenses->factory_address_no:null;
            $result->factory_address['factory_street'] = !empty($tisi_licenses->factory_street)?$tisi_licenses->factory_street:null;
            $result->factory_address['factory_moo'] = !empty($tisi_licenses->factory_moo)?$tisi_licenses->factory_moo:null;
            $result->factory_address['factory_soi'] = !empty($tisi_licenses->factory_soi)?$tisi_licenses->factory_soi:null;
            $result->factory_address['factory_subdistrict'] = !empty($tisi_licenses->factory_subdistrict)?$tisi_licenses->factory_subdistrict:null;
            $result->factory_address['factory_district'] = !empty($tisi_licenses->factory_district)?$tisi_licenses->factory_district:null;
            $result->factory_address['factory_province'] = !empty($tisi_licenses->factory_province)?$tisi_licenses->factory_province:null;
            $result->factory_address['factory_zipcode'] = !empty($tisi_licenses->factory_zipcode)?$tisi_licenses->factory_zipcode:null;
            $result->factory_tel = !empty($tisi_licenses->factory_tel)?$tisi_licenses->factory_tel:null;
            $result->factory_fax = !empty($tisi_licenses->factory_fax)?$tisi_licenses->factory_fax:null;


            $license_details_ids =  DB::connection('mysql_elicense')->table("ros_rform_tisi_license_details")->where('license_id', $tisi_licenses->id )->select('id');



            $details_subs =  DB::connection('mysql_elicense')->table("ros_rform_tisi_license_detail_subs")->whereIn('detail_id', $license_details_ids )->whereNotNull('standard_detail')->orderBy('detail_id')->get();

            $data_sub = [];
            foreach(  $details_subs as $subs ){
                $data_sub[] = $subs->standard_detail;
            }

            $result->standard_detail_list = $data_sub;

            $result_list[] =  $result;
        }

        $data = array(
                    'page_current' => (int)$page,
                    'page_all'     => (int)ceil($item_all/$per_page),
                    'item_all'     => (int)$item_all,
                    'item_list'    => $result_list
                );

        $status  = '000';
        Log::Add($app_name, __FUNCTION__, $status, $message);

        return response()->json(['status' => '000', 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function manufacturer_foreigns(Request $request)
    {
        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('search', 'registration_no', 'tis_number', 'country_initial', 'page');
        $rule  = [
                    'search'          => 'string',
                    'registration_no' => 'string',
                    'tis_number'      => 'string',
                    'country_initial' => 'string',
                    'page'            => 'integer'
                ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $error = $validator->messages();
            Log::Add($app_name, __FUNCTION__, '200', $error->toJson());
            return response()->json(['status'=> '200', 'message'=> $error]);
        }

        $page     = array_key_exists('page', $input) ? $input['page'] : 1 ; //หน้า
        $per_page = 50; //จำนวนรายการต่อหน้า
        $start = ($page-1) * $per_page;

        $filter_search          = array_key_exists('search', $input) ? $input['search'] : null ;
        $filter_registration_no = array_key_exists('registration_no', $input) ? $input['registration_no'] : null ; //เลขที่รับรอง
        $filter_tis_number      = array_key_exists('tis_number', $input) ? $input['tis_number'] : null ; //เลขที่มอก.
        $filter_country_initial = array_key_exists('country_initial', $input) ? $input['country_initial'] : null ; //อักษรย่อประเทศ

        $message = 'Found the Data.';

        $status_arr = [ 1 => 'ใช้งานปกติ', 2 => 'หมดอายุ', 3 => 'ยกเลิก'];

        $query = DB::connection('mysql_elicense')
                                    ->table((new RosManufacturerForeignScope)->getTable().' AS scope')
                                    ->leftJoin((new RosManufacturerForeign)->getTable().' AS foreign', 'foreign.id', '=', 'scope.ros_manufacturer_foreign_id')
                                    ->leftJoin((new RosStandardTisi)->getTable().' AS standard', 'standard.tis_number', '=', 'scope.tis_standards_tisno')
                                    ->leftJoin((new Country)->getTable().' AS countrys', 'countrys.id', '=', 'foreign.country_id')
                                    ->leftJoin((new States)->getTable().' AS states', 'states.id', '=', 'foreign.state_id')
                                    ->leftJoin((new Citys)->getTable().' AS citys', 'citys.id', '=', 'foreign.city_id')
                                    ->select(
                                        'scope.*',
                                        DB::raw('foreign.name AS foreign_name'), DB::raw('foreign.email AS foreign_email'),
                                        DB::raw('foreign.tel AS foreign_telephone'),  DB::raw('foreign.fax AS foreign_fax'),
                                        DB::raw('foreign.address AS foreign_address') , DB::raw('foreign.street AS foreign_street'),
                                        DB::raw('citys.title AS foreign_citys'), DB::raw('states.title AS foreign_states'),
                                        DB::raw('countrys.title_en AS foreign_countrys'),

                                        DB::raw('standard.tis_number AS standard_tis_number'),
                                        DB::raw('standard.tis_name AS standard_tis_name'),
                                        DB::raw('standard.eng_name AS standard_tis_name_en')

                                    )
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        return  $query->where(function ($query2) use($search_full) {
                                                    $query2->Where(DB::raw("REPLACE(foreign.name,' ','')"), 'LIKE', "%".$search_full."%");
                                                });
                                    })
                                    ->when($filter_registration_no, function($query, $filter_registration_no){
                                        $query->where('scope.cer_on', $filter_registration_no);
                                    })
                                    ->when($filter_tis_number, function($query, $filter_tis_number){
                                        $query->where('scope.tis_standards_tisno', $filter_tis_number);
                                    })
                                    ->when($filter_country_initial, function($query, $filter_country_initial){
                                        $query_country_ids = DB::connection('mysql_elicense')
                                                               ->table("ros_rbasicdata_country")
                                                               ->where('initial', $filter_country_initial)
                                                               ->select('id');
                                        $query->whereIn('foreign.country_id', $query_country_ids);
                                    });

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $_licenses = $query->skip($start)
                           ->take($per_page)
                           ->get();

        $result_list = [];
        foreach($_licenses as $key => $item  ){
            $result = (object)[];
            $result->runrecno = $key+1;

            $result->name =  !empty($item->foreign_name)?$item->foreign_name:null;
            $result->address =  !empty($item->foreign_address)?$item->foreign_address:null;
            $result->street =  !empty($item->foreign_street)?$item->foreign_street:null;
            $result->city =  !empty($item->foreign_citys)?$item->foreign_citys:null;
            $result->state =  !empty($item->foreign_states)?$item->foreign_states:null;
            $result->country =  !empty($item->foreign_countrys)?$item->foreign_countrys:null;
            $result->email =  !empty($item->foreign_email)?$item->foreign_email:null;
            $result->telephone =  !empty($item->foreign_telephone)?$item->foreign_telephone:null;
            $result->tis_number =  !empty($item->standard_tis_number)?$item->standard_tis_number:null;
            $result->tis_name =  !empty($item->standard_tis_name)?$item->standard_tis_name:null;
            $result->tis_name_en =  !empty($item->standard_tis_name_en)?$item->standard_tis_name_en:null;

            $result->registration_no =  !empty($item->cer_on)?$item->cer_on:null;

            $result->issue_date =  !empty($item->date_registration)?$item->date_registration:null;
            $result->expire_date =  !empty($item->date_expiry)?$item->date_expiry:null;

            $result->status =  !empty($item->status_id)?$item->status_id:null;
            $result->status_name =  array_key_exists( $item->status_id,  $status_arr)?$status_arr[ $item->status_id ]:null;

            $result_list[] =  $result;
        }

        $data = array(
                    'page_current' => (int)$page,
                    'page_all'     => (int)ceil($item_all/$per_page),
                    'item_all'     => (int)$item_all,
                    'item_list'    => $result_list
                );

        $status = '000';
        Log::Add($app_name, __FUNCTION__, $status, $message);
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);

    }

}
