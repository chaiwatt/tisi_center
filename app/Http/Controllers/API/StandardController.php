<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tis\Standard;
use App\Models\Certify\Standard AS Certify_Standard;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\WS\Log;
use HP_API;
use HP;


class StandardController extends Controller
{

    public function tis_standards(Request $request)
    {

        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('search', 'tis_tisno', 'tis_type', 'tis_year', 'page');
        $rule  = [
                   'search'    => 'string',
                   'tis_tisno' => 'string',
                   'tis_type'  => 'in:ท,บ',
                   'tis_year'  => 'digits:4',
                   'page'      => 'integer'
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

        $search    = array_key_exists('search', $input) ? $input['search'] : null ; //คำค้น
        $tis_tisno = array_key_exists('tis_tisno', $input) ? $input['tis_tisno'] : null ; //เลขที่มอก.
        $tis_type  = array_key_exists('tis_type', $input) ? $input['tis_type'] : null ; //ประเภทมาตรฐาน
        $tis_year  = array_key_exists('tis_year', $input) ? $input['tis_year'] : null ; //ปีมาตรฐาน

        $query = Standard::with('set_format')
                        ->with('product_group')
                        ->with('standard_format')
                        ->with('standard_type')
                        ->where('publishing_status', 1)
                        ->when($search, function($query, $search){
                            $query->where(function($query) use ($search) {
                                $query->where('title', 'LIKE', "%$search%")
                                      ->orWhere('title_en', 'LIKE', "%$search%");
                            });
                        })
                        ->when($tis_tisno, function($query, $tis_tisno){
                            $query->where('tis_tisno', $tis_tisno);
                        })
                        ->when($tis_type, function($query, $tis_type){
                            $query->where('tis_force', $tis_type);
                        })
                        ->when($tis_year, function($query, $tis_year){
                            $query->where('tis_year', $tis_year);
                        });

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $standards = $query->skip($start)
                           ->take($per_page)
                           ->get();

        $all_standard = [];

        foreach ($standards as $key => $std) {

            $set_format = $std->set_format;
            $product_group = $std->product_group;
            $standard_format = $std->standard_format;
            $standard_type = $std->standard_type;

            $item = (object)[];
            $item->id                 = $std->id;
            $item->tis_no             = !empty($std->tis_no)?$std->tis_no:null;
            $item->tis_book           = !empty($std->tis_book)?$std->tis_book:null;
            $item->tis_year           = !empty($std->tis_year)?$std->tis_year:null;
            $item->tis_tisno          = !empty($std->tis_tisno)?$std->tis_tisno:null;
            $item->tis_tisshortno     = !empty($std->tis_tisshortno)?$std->tis_tisshortno:null;
            $item->title              = !empty($std->title)?$std->title:null;
            $item->title_en           = !empty($std->title_en)?$std->title_en:null;
            $item->tis_product_name     = !empty($std->tis_product_name)?$std->tis_product_name:null;

            $item->product_group_title  =  !is_null($product_group) ? $product_group->title : null;
            $item->standard_format_title  = !is_null($standard_format) ? $standard_format->title : null;
            $item->standard_type_title  = !is_null($standard_type) ? $standard_type->title : null;

            $item->set_format_title  = !is_null($set_format) ? $set_format->title : null;

            $item->tisno_ref  = !empty($std->tisno_ref)?$std->tisno_ref:null;
            $item->created_at  = !empty($std->created_at)?( \Carbon\Carbon::parse( $std->created_at )->format('Y-m-d H:i:s') ):null;
            $item->updated_at  = !empty($std->updated_at)?( \Carbon\Carbon::parse( $std->updated_at )->format('Y-m-d H:i:s') ):null;

            $all_standard[] = $item;
        }

        if(count($all_standard) > 0){

            $data = array(
                        'page_current' => (int)$page,
                        'page_all'     => (int)ceil($item_all/$per_page),
                        'item_all'     => (int)$item_all,
                        'item_list'    => $all_standard
                    );

            $message = 'Found the Data.';
            $status  = '000';
            Log::Add($app_name, __FUNCTION__, $status, $message);
            return response()->json(['status' => '000', 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        }else{
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
        }


    }

    //API ดึงข้อมูลมาตรฐานสก.
    public function estandard(Request $request)
    {

        //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
        $header = $request->header();

        $check_header = HP_API::check_client($header, __FUNCTION__);

        $app_name = array_key_exists('app-name', $header) ? $header['app-name'][0] : null ;

        if($check_header['status']===false){//ข้อมูลไม่ถูกต้องหรือไม่มีสิทธิ์
            Log::Add($app_name, __FUNCTION__, $check_header['code'], $check_header['msg']);
            return response()->json(['status' => $check_header['code'], 'message' => $check_header['msg']]);
        }

        $input = $request->only('search', 'std_full', 'std_type', 'std_year', 'page');
        $rule  = [
                   'search'    => 'string',
                   'std_full' => 'string',
                   'std_type'  => 'string',
                   'std_year'  => 'digits:4',
                   'page'      => 'integer'
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

        $search    = array_key_exists('search', $input) ? $input['search'] : null ; //คำค้น
        $std_full = array_key_exists('std_full', $input) ? $input['std_full'] : null ; //เลขที่มอก.
        $std_type  = array_key_exists('std_type', $input) ? $input['std_type'] : null ; //ประเภทมาตรฐาน
        $std_year  = array_key_exists('std_year', $input) ? $input['std_year'] : null ; //ปีมาตรฐาน

        $query = Certify_Standard::with('standard_type')
                        ->with('method')
                        ->with('isbn_created')
                        ->with('ics')
                        ->with('industry_target_data')
                        ->when($search, function($query, $search){
                            $query->where(function($query) use ($search) {
                                $query->where('std_title', 'LIKE', "%$search%")
                                      ->orWhere('std_title_en', 'LIKE', "%$search%");
                            });
                        })
                        ->when($std_full, function($query, $std_full){
                            $query->where('std_full', $std_full);
                        })
                        ->when($std_type, function($query, $std_type){
                            $query->where('std_type', $std_type);
                        })
                        ->when($std_year, function($query, $std_year){
                            $query->where('std_year', $std_year);
                        });

        //จำนวนผลลัพธ์ทั้งหมด
        $item_all  = $query->count();

        //รายการข้อมูล
        $standards = $query->skip($start)
                           ->take($per_page)
                           ->get();

        $all_standard = [];

        foreach ($standards as $key => $std) {

            $standard_type   = $std->standard_type;
            $method          = $std->method;
            $isbn_created    = $std->isbn_created;
            $ics             = $std->ics;
            $industry_target = $std->industry_target_data;

            $item = (object)[];
            $item->std_type               = !is_null($standard_type) ? $standard_type->title : null ;
            $item->std_no                 = $std->std_no;
            $item->std_book               = $std->std_book;
            $item->std_year               = $std->std_year;
            $item->std_full               = $std->std_full;
            $item->std_title              = $std->std_title;
            $item->std_title_en           = $std->std_title_en;
            $item->std_page               = $std->std_page;
            $item->method_title           = !is_null($method) ? $method->title : null ;
            $item->std_abstract           = $std->std_abstract;
            $item->std_abstract_en        = $std->std_abstract_en;
            $item->isbn_no                = $std->isbn_no;
            $item->isbn_by                = !is_null($isbn_created) ? $isbn_created->FullName : null ;
            $item->ICS                    = $ics->count() > 0 ? implode(', ', $ics->pluck('code')->toArray()) : null;
            $item->std_sign_date          = $std->std_sign_date;
            $item->gazette_book           = $std->gazette_book;
            $item->gazette_section        = $std->gazette_section;
            $item->gazette_no             = $std->gazette_no;
            $item->gazette_post_date      = $std->gazette_post_date;
            $item->gazette_effective_date = $std->gazette_effective_date;
            $item->publish_state          = $std->publish_state;
            $item->standard_id            = $std->standard_id;
            $item->ref_document           = $std->ref_document;
            $item->reason                 = $std->reason;
            $item->confirm_time           = $std->confirm_time;
            $item->std_price              = $std->std_price;
            $item->industry_target        = !is_null($industry_target) ? $industry_target->title : null ;
            $item->remark                 = $std->remark;

            $all_standard[] = $item;
        }

        if(count($all_standard) > 0){

            $data = array(
                        'page_current' => (int)$page,
                        'page_all'     => (int)ceil($item_all/$per_page),
                        'item_all'     => (int)$item_all,
                        'item_list'    => $all_standard
                    );

            $message = 'Found the Data.';
            $status  = '000';
            Log::Add($app_name, __FUNCTION__, $status, $message);
            return response()->json(['status' => '000', 'message' => $message, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        }else{
            Log::Add($app_name, __FUNCTION__, '503', 'Data not found.');
            return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
        }


    }
  //API get ดึงข้อมูลมาตรฐานสก.
  public function get_estandard(Request $request)
  {
      $no =  $request->no;
      $query = Certify_Standard::with('standard_type')
                      ->with('method')
                      ->with('isbn_created')
                      ->with('ics')
                      ->with('industry_target_data')
                      ->where('std_full', $no);


      //จำนวนผลลัพธ์ทั้งหมด
      $item_all  = $query->count();

      //รายการข้อมูล
      $standards = $query ->get();

      $all_standard = [];
    if($item_all > 0 &&  $no != ''){
      foreach ($standards as $key => $std) {

          $standard_type   = $std->standard_type;
          $method          = $std->method;
          $isbn_created    = $std->isbn_created;
          $ics             = $std->ics;
          $industry_target = $std->industry_target_data;

          $item = (object)[];
          $item->std_type               = !is_null($standard_type) ? $standard_type->title : null ;
          $item->std_no                 = $std->std_no;
          $item->std_book               = $std->std_book;
          $item->std_year               = $std->std_year;
          $item->std_full               = $std->std_full;
          $item->std_title              = $std->std_title;
          $item->std_title_en           = $std->std_title_en;
          $item->std_page               = $std->std_page;
          $item->method_title           = !is_null($method) ? $method->title : null ;
          $item->std_abstract           = $std->std_abstract;
          $item->std_abstract_en        = $std->std_abstract_en;
          $item->isbn_no                = $std->isbn_no;
          $item->isbn_by                = !is_null($isbn_created) ? $isbn_created->FullName : null ;
          $item->ICS                    = $ics->count() > 0 ? implode(', ', $ics->pluck('code')->toArray()) : null;
          $item->std_sign_date          = $std->std_sign_date;
          $item->gazette_book           = $std->gazette_book;
          $item->gazette_section        = $std->gazette_section;
          $item->gazette_no             = $std->gazette_no;
          $item->gazette_post_date      = $std->gazette_post_date;
          $item->gazette_effective_date = $std->gazette_effective_date;
          $item->publish_state          = $std->publish_state;
          $item->standard_id            = $std->standard_id;
          $item->ref_document           = $std->ref_document;
          $item->reason                 = $std->reason;
          $item->confirm_time           = $std->confirm_time;
          $item->std_price              = $std->std_price;
          $item->industry_target        = !is_null($industry_target) ? $industry_target->title : null ;
          $item->remark                 = $std->remark;
          $item->created_by             = $std->created_by;
          $item->updated_by             = $std->updated_by;
          $item->created_at             = !empty($std->created_at) ? (\Carbon\Carbon::parse($std->created_at)->format('Y-m-d H:i:s')) : null ;
          $item->updated_at             = !empty($std->updated_at) ? (\Carbon\Carbon::parse($std->updated_at)->format('Y-m-d H:i:s')) : null ;
          $all_standard[] = $item;
        }
      }
      if(count($all_standard) > 0){
          $data = array( 'result'    =>  $all_standard);
          return response()->json(['status' => '000', 'message' => $no, 'data' => $data], 200, [], JSON_UNESCAPED_UNICODE);
      }else{
          return response()->json(['status'=> '503', 'message'=> 'Data not found.']);
      }


  }




}
