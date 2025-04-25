<?php

namespace App\Http\Controllers\Laws;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use HP_Law;

use App\Models\Law\Cases\LawCasesForm;


class DashboardLawsController extends Controller
{
    public function index(Request $request)
    {
        $filter_year     = !empty($request->input('filter_year'))?$request->input('filter_year'):date('Y');
        $query_lawcasesform =  new LawCasesForm;
        //จำนวนงานคดีสะสมทั้งหมด
        $count_lawcase_status_all = $query_lawcasesform->whereNotIn('status',['99','0'])->count();
        //ส่งเรื่องงานคดีสำเร็จ
        $count_lawcase_status_success = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->whereNotIn('status',['99','0'])->count();
        //หน่วยงานภายใน
        $count_depart_type_in = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->where('owner_depart_type',1)->count(); //ภายใน
        //หน่วยงานภายนอก
        $count_depart_type_out = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->where('owner_depart_type',2)->count(); //ภายนอก
        //อยู่ระหว่างดำเนินการ
        $count_lawcase_status_process = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->whereIn('status',['2','3','4','5','6','7','8','9','10','11','12','13','14'])->count();
        //ปิดงานคดี
        $count_lawcase_status_close = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->where('status_close',1)->count();
        //รอปิดงาน
        $count_lawcase_status_close_wait = $query_lawcasesform->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->where('status_close',0)->count();
        //ปีงบประมาณ
        $option_offend_date = $query_lawcasesform->selectRaw('year(offend_date) year, year(offend_date)+543 year_thai')->where('offend_date','<>','0000-00-00')->groupBy('year')->pluck('year_thai','year');

        //เปรียบเทียบปรับ
        $count_prosecute_compare = $query_lawcasesform->whereHas('result_section',function($query){
                                                    $query->where('prosecute','0');
                                                })
                                                ->when($filter_year, function ($query, $filter_year){
                                                    return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
                                                })
                                                ->count();
        //ดำเนินคดี
        $count_prosecute_prosecute = $query_lawcasesform->whereHas('result_section',function($query){
                                                    $query->where('prosecute','1');
                                                })
                                                ->when($filter_year, function ($query, $filter_year){
                                                    return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
                                                })
                                                ->count();


        $data_for_chart = $query_lawcasesform->with('law_basic_offend_type_to')->selectRaw('law_basic_offend_type_id, COUNT(law_basic_offend_type_id) as cnt_offend_type')->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->whereNotIn('status',['99','0','6'])->groupBy('law_basic_offend_type_id')->get();

        $arr_offend_type = [];
        foreach( $data_for_chart as $key => $data ){

            $law_basic_offend_type = $data->law_basic_offend_type_to;
            $arr_offend_type[] = [
                'label'     => (!empty($law_basic_offend_type)?$law_basic_offend_type->title:null),
                'value'     => $data->cnt_offend_type,
                'formatted' => (!empty($law_basic_offend_type)?$law_basic_offend_type->title_en:null)
            ];
        }
        $data_arr =  json_encode($arr_offend_type, JSON_UNESCAPED_UNICODE);

        // dd($data_arr);

        $data_for_chart2 = $query_lawcasesform->with('tis')->selectRaw('tis_id, tb3_tisno, COUNT(tis_id) as cnt_tis')->when($filter_year, function ($query, $filter_year){
            return $query->where('offend_date','<>','0000-00-00')->where(DB::raw("YEAR(offend_date)"), $filter_year);
        })->whereNotIn('status',['99','0','6'])->groupBy('tis_id')->limit(5)->get();

        $arr_offend_type2 = [];
        foreach($data_for_chart2 as $key=> $data){
            $arr_offend_type2[] = [
                'y' => !empty($data_for_chart2[$key]->tis)?$data_for_chart2[$key]->tb3_tisno.' '.$data_for_chart2[$key]->tis->tb3_productgroup:'n/a',
                'a' => $data_for_chart2[$key]->cnt_tis,
            ];
        }
        $data_arr2 =  json_encode($arr_offend_type2, JSON_UNESCAPED_UNICODE);

        return view('laws.home.index', compact(
                                                'count_lawcase_status_all',
                                                'count_lawcase_status_success',
                                                'count_depart_type_in',
                                                'count_depart_type_out',
                                                'count_lawcase_status_process',
                                                'count_lawcase_status_close',
                                                'count_lawcase_status_close_wait',
                                                'count_prosecute_compare',
                                                'count_prosecute_prosecute',
                                                'option_offend_date',
                                                'data_arr',
                                                'data_arr2',
                                                'filter_year'
                                            ));
    }
}
