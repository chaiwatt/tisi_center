<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\TisiLicense;
use App\Models\NSW\LiteOrder;

//ระบบรับรองระบบงาน
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\ApplicantCB;
use App\Models\Certify\Applicant\ApplicantIB;

use DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    //ข้อมูลผู้ประกอบการที่ยื่นคำขอผ่าน elicense
    public function elicense(){

      $tisi_licenses = TisiLicense::select(DB::raw('count(DISTINCT tbl_taxpayer) as user_count, tbl_licenseType'))
                                  ->whereIn('tbl_licenseType', ['ส', 'ท', 'น', 'นค'])
                                  ->groupBy('tbl_licenseType')
                                  ->get();

      return response()->json($tisi_licenses);
    }

    //ข้อมูลนำเข้าจาก NSW
    public function nsw($month_year){

      $filter = explode('-', $month_year);

      $month = $filter[0];//เดือนที่แสดง
      $year = $filter[1];//ปีที่แสดง

      $lite_orders = LiteOrder::select(DB::raw('SUM(IF(`have_license`>0, 1, 0)) AS sum_have_license,
                                                SUM(IF(`nhave_license`>0, 1, 0)) AS sum_nhave_license,
                                                day(order_inputdatetine) AS day'
                                              )
                                      )
                              ->whereRaw("month(order_inputdatetine) = $month")
                              ->whereRaw(DB::raw("year(order_inputdatetine) = $year"))
                              ->groupBy(DB::raw('day(order_inputdatetine)'))
                              ->get();

      $items = ['labels'=>[], 'have_licenses'=>[], 'nhave_licenses'=>[], 'max'=>0];

      foreach($lite_orders as $lite_order){

        $items['labels'][] = $lite_order->day;
        $items['have_licenses'][] = (int)$lite_order->sum_have_license;
        $items['nhave_licenses'][] = (int)$lite_order->sum_nhave_license;

      }

      $max_have_license = count($items['have_licenses']) > 0 ? max($items['have_licenses']) : 0;//จำนวนมากสุดของมีใบอนุญาต
      $max_nhave_license = count($items['nhave_licenses']) > 0 ? max($items['nhave_licenses']) : 0;//จำนวนมากสุดของไม่มีใบอนุญาต
      $items['max'] = $max_have_license > $max_nhave_license ? $max_have_license : $max_nhave_license;

      return response()->json($items);

    }

    //ระบบรับรองระบบงาน
    public function certify(){

      $year_now = date('Y');
      $items = [];
      for($year = $year_now-4; $year<=$year_now; $year++){


          $cb = ApplicantCB::select(DB::raw('COUNT(*) AS amount'))->whereRaw(DB::raw("year(created_at) = $year"))->first();
          $ib = ApplicantIB::select(DB::raw('COUNT(*) AS amount'))->whereRaw(DB::raw("year(created_at) = $year"))->first();
          $lab = CertiLab::select(DB::raw('COUNT(*) AS amount'))->whereRaw(DB::raw("year(created_at) = $year"))->first();

          if($cb->amount!=0 || $ib->amount!=0 || $lab->amount!=0){
            $items[] = ['year' => $year+543, 'cb' => $cb->amount, 'ib' => $ib->amount, 'lab' => $lab->amount];
          }

      }

      return response()->json($items);

    }


}
