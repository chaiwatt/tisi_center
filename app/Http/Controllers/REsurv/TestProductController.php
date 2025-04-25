<?php

namespace App\Http\Controllers\REsurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\REsurv\ResultProduct;
use App\Models\REsurv\ResultProductDetail;
use App\Models\Ssurv\SaveExample;
use App\Models\Ssurv\SaveExampleDetail;
use App\Models\Ssurv\SaveExampleFile;
use App\Models\Ssurv\SaveExampleMaplap;
use App\Models\Ssurv\SaveExampleTypeDetail;
use App\test_product;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Bsection5\ReportTestProduct;
use App\Models\Bsection5\ReportTestProductDetail;
use App\Models\Bsection5\ReportTestProductDetailItem;
use App\Models\Section5\Labs;

use Illuminate\Http\Request;

class TestProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['filter_status'] = $request->get('filter_status', '');
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
        $filter['filter_test_status'] = $request->get('filter_test_status', '');
        $filter['filter_status3'] = $request->get('filter_status3', '');

        // start query : SELECT * FROM `save_example` WHERE `id` not in (SELECT example_id FROM `save_example_map_lap` WHERE `status` in (1,2,4) and type_send = 'some') and user_register = 'ชื่อที่ล็อกอินอยู่'
        $status_for_maplap = ['1','2','4'];
        $maplap_status = SaveExampleMaplap::wherein('status', $status_for_maplap)->where('type_send', 'some')->pluck('example_id');
        // dd($maplap_status);
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;

        $Query = new SaveExample;

        if(auth()->user()->isAdmin() || in_array('5', auth()->user()->RoleListId) || in_array('7', auth()->user()->RoleListId) ){
            $Query = $Query->whereNotIn('id', $maplap_status);
        }else{
            $Query = $Query->whereNotIn('id', $maplap_status)->where('user_register', $full_name);
        }

        if ($filter['filter_search']!='') {
            $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('licensee', 'LIKE', "%{$filter['filter_search']}%");
                     });
        }

        if ($filter['filter_tb3_Tisno']!='') {
            $Query = $Query->where('tis_standard', $filter['filter_tb3_Tisno']);
        }
        if ($filter['filter_department']!='') {
            $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
            $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
            $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
            $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
        }else{
            $subDepartments = [];
        }
        if ($filter['filter_sub_department']!='') {
            $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
            $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
        }

        if ($filter['filter_status']!='') {
            $Query = $Query->where('status2', $filter['filter_status']);
        }

        if ($filter['filter_test_status']!='') {
            $Query = $Query->where('test_status', $filter['filter_test_status']);
        }

        if ($filter['filter_status3']!='') {
            $Query = $Query->where('status3', $filter['filter_status3']);
        }

        // $Query = $Query->wherein('status2',$status);

        $test_product = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $test_product->firstItem();

        return view('resurv.test_product.index', compact('test_product', 'filter', 'temp_num', 'subDepartments'));

    }

    public function edit($id)
    {
        $previousUrl = app('url')->previous();
        $data = SaveExample::find($id);
        $data_detail = SaveExampleMaplap::query()->where('example_id',$id)->groupBy('no_example_id')->get();
        return view('resurv.test_product.edit', compact('data', 'data_detail', 'previousUrl'));
    }

    public function update(Request $request)
    {
        if ($request->get('res_status')=='เลือกผลการประเมิน'){
            return response()->json([
                "status" => "error",
                "message"=>"กรุณาเลือกผลการประเมิน!"
            ]);
        }

        /*
            สถานะ status2 คือ
            1 รอประเมินผล
            2 ประเมินผลแล้ว

            ผลการประเมิน จะขึ้นเมื่อ ประเมินผลแล้ว test_status คือ
            1 ผ่าน
            2 ไม่ผ่าน

            สถานะ ผก. จะแสดง เมื่อ สถานะเป็นประเมินผลแล้ว มี 3 สถานะ  status3 คือ
            1 อยู่ระหว่าง ผก. รับรอง
            2 ผก. รับรองแล้ว
            3 ผก. ไม่เห็นด้วย
        */

        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;

        $data = SaveExample::find($request->get('id'));
        $user_assign = $data->user_assign;

        if( $request->get('status2') == 1 && $request->get('test_status') != ''){

            $data->status2 = 2;
            $data->test_status = $request->get('test_status');
            $data->test_remark = $request->get('test_remark');
            $data->test_user = $full_name;
            $data->status3 = 1;

        } else if($request->get('status2') == 2) {

            if($request->get('poko_comment') == 'yes'){//เห็นชอบและโปรดดำเนินการต่อไป
                $data->poko_comment = 'yes';
                $data->status3 = 2;

                $map_lap = SaveExampleMaplap::where('example_id', $data->id)->where('status', 3)->first();//อาจมีหลายอันเอามาอันเดียว
                ReportTestProduct::where('sample_bill_no', $map_lap->no_example_id)->delete();//ลบไปก่อน

                $lab     = $map_lap->lab; //Lab
                $tis     = $map_lap->tis; //มาตรฐาน
                $license = $data->license; //ใบอนุญาต
                $trader  = !is_null($license) ? $license->user : null ; //ผู้ประกอบการ

                //บันทึกลงตาราง Report
                $test_product = new ReportTestProduct;
                $test_product->sample_id      = null;
                $test_product->sample_bill_no = $map_lap->no_example_id;
                $test_product->lab_code       = $lab->lab_code;
                $test_product->lab_name       = $lab->lab_name;
                $test_product->tis_no         = $tis->tb3_Tisno;
                $test_product->trader_name    = !is_null($license) ? $license->tbl_tradeName : null;
                $test_product->trader_taxid   = !is_null($license) ? $license->tbl_taxpayer : null;
                $test_product->trader_email   = !is_null($trader) ? $trader->email : null ;
                $test_product->sample_from    = $data->sample_pay;
                $test_product->department     = null;
                $test_product->sub_department = null;
                $test_product->receive_date   = $data->sample_submission_date;
                $test_product->test_date      = null;
                $test_product->test_finish_date = null;
                $test_product->test_duration  = null;
                $test_product->test_price     = null;
                $test_product->total_test_price = null;
                $test_product->report_date    = $data->date_approved;
                $test_product->payment_date   = null;
                $test_product->ref_report_no  = null;
                $test_product->remark         = $request->get('poko_remark');
                $test_product->created_by     = auth()->user()->getKey();
                $test_product->save();

                $details = SaveExampleDetail::where('id_example', $data->id)->get();//การเก็บตัวอย่าง

                foreach ($details as $detail) {
                    $test_product_detail = new ReportTestProductDetail;
                    $test_product_detail->test_product_id = $test_product->id;
                    $test_product_detail->sample_bill_no  = $map_lap->no_example_id;
                    $test_product_detail->product_detail  = !is_null($detail->license_detail) ? $detail->license_detail->standard_detail : null ;
                    $test_product_detail->sample_no       = $detail->num_ex;
                    $test_product_detail->sample_qty      = $detail->number;
                    $test_product_detail->save();

                    $map_lap = SaveExampleMaplap::where('example_id', $data->id)->where('detail_product_maplap', $detail->detail_volume)->where('status', 3)->first();//รายละเอียดผลิตภัณฑ์
                    foreach ($map_lap->details as $map_lap_detail) {
                        $test_item = $map_lap_detail->test_item;
                        $test_product_detail_item = new ReportTestProductDetailItem;
                        $test_product_detail_item->detail_id       = $test_product_detail->id;
                        $test_product_detail_item->test_product_id = $test_product->id;
                        $test_product_detail_item->test_item_id    = !is_null($test_item) ? $test_item->id : null;
                        $test_product_detail_item->test_item_name  = !is_null($test_item) ? $test_item->title : null;
                        $test_product_detail_item->test_result     = null;
                        $test_product_detail_item->state           = 1;
                        $test_product_detail_item->save();
                    }

                }

            }else{//อื่นๆ
                $data->poko_comment = 'no';
                $data->status3 = 3;
            }
            $data->user_approved = $user_assign;
            $data->poko_remark = $request->get('poko_remark');
            $data->user_approved = $user_assign;
            $data->date_approved = $request->get('date_approved');

        }

        $data->save();

        return response()->json([
                                    "status" => "success",
                                ]);


    }

    public function detail($ID, $ID_MAIN)
    {
        $id_map = SaveExampleMaplap::query()->where('no_example_id', $ID)->first();
        $data = SaveExample::find($id_map->example_id);
        $data_detail = SaveExampleDetail::query()->where('id_example', $id_map->example_id)->get();
        $data_res = ResultProduct::query()->where('tis_standard', $data->tis_standard)->pluck('id');
        $data_res_check = ResultProduct::query()->where('tis_standard', $data->tis_standard)->first();
        if ($data_res_check != null) {
            $data_res_detail = ResultProductDetail::query()->where('id_result', $data_res)->get();
        } else {
            $data_res_detail = null;
        }

        $data_res_detail_id = SaveExampleDetail::query()->where('id_example', $id_map->example_id)->pluck('id');
        $data_save_check = SaveExampleTypeDetail::query()->whereIn('example_detail_id', $data_res_detail_id)->where('no_example_id', $ID)->first();
        $data_save_type = SaveExampleTypeDetail::query()->whereIn('example_detail_id', $data_res_detail_id)->where('no_example_id', $ID)->get();
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;

        if ($data_save_check == null) {
            $data_save_type = null;
        }
        $dis = (explode('-', $ID));
        $data_file = SaveExampleFile::query()->where('example_id', $ID)->get();
        $data_file_check = SaveExampleFile::query()->where('example_id', $ID)->first();
        $data_map = SaveExampleMaplap::query()->where('example_id', $id_map->example_id)->where('detail_product_maplap', '!=', null)->where('no_example_id', $ID)->get();
        $temp_res_detail = ResultProduct::query()->where('tis_standard', $data->tis_standard)->first();
        return view('resurv.test_product.detail', ['data_map' => $id_map, 'data' => $data, 'data_detail' => $data_detail, 'data_map_table' => $data_map
            , 'data_res_detail' => $data_res_detail, 'data_save_type' => $data_save_type
            , 'full_name' => $full_name, 'main_res_id' => $temp_res_detail
            , 'dis0' => $dis[0], 'dis1' => $dis[1], 'dis2' => $dis[2]
            , 'data_file' => $data_file, 'data_file_check' => $data_file_check
            , 'ID_MAIN' => $ID_MAIN,'ID'=>$ID]);
    }

}
