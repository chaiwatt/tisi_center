<?php

namespace App\Http\Controllers\REsurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\assign_product;
use App\Models\REsurv\ResultProduct;
use App\Models\REsurv\ResultProductDetail;
use App\Models\Ssurv\SaveExample;
use App\Models\Ssurv\SaveExampleDetail;
use App\Models\Ssurv\SaveExampleFile;
use App\Models\Ssurv\SaveExampleMaplap;
use App\Models\Ssurv\SaveExampleTypeDetail;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;

use Illuminate\Http\Request;

class AssignProductController extends Controller
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
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
        $filter['filter_created_by'] = $request->get('filter_created_by', '');
        $filter['filter_status'] = $request->get('filter_status', '');

        // start query : SELECT * FROM `save_example` WHERE `id` not in (SELECT example_id FROM `save_example_map_lap` WHERE `status` in (1,2,4) and type_send = 'some')
        $status_for_maplap = ['1','2','4'];
        $maplap_status = SaveExampleMaplap::wherein('status',$status_for_maplap)->where('type_send','some')->pluck('example_id');

        $Query = new SaveExample;
        $Query = $Query->whereNotIn('id', $maplap_status);
        // end query

        if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('licensee', 'LIKE', "%{$filter['filter_search']}%");
                         });
        }

        if ($filter['filter_tb3_Tisno'] != '') {
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
        if ($filter['filter_created_by'] != '') {
            $Query = $Query->where('licensee', $filter['filter_created_by']);
        }
        if ($filter['filter_status'] != '') {
            if ($filter['filter_status'] == 'รอมอบหมายงาน') {
                $Query = $Query->where('status2', '-');
            } elseif ($filter['filter_status'] == 'รอประเมินผล') {
                $Query = $Query->where('status2', $filter['filter_status'])->where('status3', '-');
            } elseif ($filter['filter_status'] == 'ประเมินผลแล้ว') {
                $Query = $Query->where('status2', $filter['filter_status']);
            }
        }

        $status = ['3'];
        $Query = $Query->wherein('status', $status);

        $assign_product = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $assign_product->firstItem();
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;

        return view('resurv.assign_product.index', (['name_create' => $full_name]), compact('assign_product', 'filter', 'temp_num', 'subDepartments'));

    }

    public function edit($id)
    {
        $previousUrl = app('url')->previous();
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $data = SaveExample::find($id);
        $data_detail = SaveExampleMaplap::query()->where('example_id', $id)->groupBy('no_example_id')->get();
        return view('resurv.assign_product.edit', (['name_created' => $full_name, 'data' => $data, 'data_detail' => $data_detail, 'previousUrl' => $previousUrl]));
    }

    public function update_reg_cb(Request $request)
    {
        if ($request->user_reg == 'เลือกเจ้าหน้าที่ผู้รับผิดชอบ') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกเจ้าหน้าที่ผู้รับผิดชอบ!"
            ]);
        }
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $data_id = explode(',', $request->get('id_user_reg'));
        foreach ($data_id as $id) {
            if ($id != null) {
                $data = SaveExample::find($id);
                $data->user_register = $request->get('user_reg');
                $data->status2 = 'รอประเมินผล';
                $data->remake_assign = $request->get('remark');
                $data->user_assign = $full_name;
                $data->save();
            }
        }
        return response()->json([
            "status" => "success",
        ]);

    }

    public function update_reg(Request $request)
    {
        if ($request->user_reg == 'เลือกเจ้าหน้าที่ผู้รับผิดชอบ') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกเจ้าหน้าที่ผู้รับผิดชอบ!"
            ]);
        }
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $data = SaveExample::find($request->get('id'));
        $data->user_register = $request->get('user_reg');
        $data->remake_assign = $request->get('remark');
        $data->user_assign = $full_name;
        $data->status2 = 1;
        $data->save();
        return response()->json([
            "status" => "success",
        ]);

    }

    public function detail($ID, $ID_MAIN)
    {
        $previousUrl = app('url')->previous();

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
        return view('resurv.assign_product.detail', ['data_map' => $id_map, 'data' => $data, 'data_detail' => $data_detail, 'data_map_table' => $data_map
            , 'data_res_detail' => $data_res_detail, 'data_save_type' => $data_save_type
            , 'full_name' => $full_name, 'main_res_id' => $temp_res_detail, 'previousUrl' => $previousUrl
            , 'dis0' => $dis[0], 'dis1' => $dis[1], 'dis2' => $dis[2]
            , 'data_file' => $data_file, 'data_file_check' => $data_file_check
            , 'ID_MAIN' => $ID_MAIN,'ID'=>$ID]);
    }


    public function load_form_result($id)
    {
        $data_map = SaveExampleMaplap::findOrFail($id);

        return view('resurv.assign_product.modals.form',compact('data_map'));
    }
}
