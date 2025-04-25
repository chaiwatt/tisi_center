<?php

namespace App\Http\Controllers\REsurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\REsurv\ResultProduct;
use App\Models\REsurv\ResultProductDetail;
use App\Models\Ssurv\SaveExampleTypeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_detail'] = $request->get('filter_detail', '');
        $filter['filter_status'] = $request->get('filter_status', '');

        $Query = new ResultProduct;

        if ($filter['filter_tb3_Tisno']!='') {
            $Query = $Query->where('tis_standard', $filter['filter_tb3_Tisno']);
        }
        if ($filter['filter_status']!='') {
            $Query = $Query->where('status', $filter['filter_status']);
        }
        if ($filter['filter_detail']!='') {

            $ResultProductDetail = new ResultProductDetail;
            $id_result = $ResultProductDetail->where('name_result', 'like', $filter['filter_detail'])->pluck('id_result');

            $Query = $Query->whereIn('id', $id_result);
        }

        $data = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $data->firstItem();

        return view('resurv.results_product.index', compact('data', 'filter', 'temp_num'));
    }

    public function create()
    {
        return view('resurv.results_product.create');
    }

    public function show($id)
    {
        $results_product = ResultProduct::findOrFail($id);
        return view('resurv.results_product.show', compact('results_product'));
    }

    public function edit($id)
    {
        $data = ResultProduct::find($id);
        $data_detail = ResultProductDetail::query()->where('id_result',$id)->get();
        return view('resurv.results_product.edit',['data'=>$data,'data_detail'=>$data_detail]);
    }

    public function update(Request $request)
    {
        if ($request->name_result != null){
            $error_name_result = 0;
            for ($i = 0; $i < count($request->name_result); $i++) {
                if ($request->name_result[$i] == null) {
                    $error_name_result += 1;
                }
            }
            if ($error_name_result > 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกชื่อรายการผลทดสอบ!"
                ]);
            }
        }
        if ($request->type_result != null){
            $error_type_result = 0;
            for ($i = 0; $i < count($request->type_result); $i++) {
                if ($request->name_result[$i] == 'เลือกประเภทข้อมูล') {
                    $error_type_result += 1;
                }
            }
            if ($error_type_result > 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณาเลือกประเภทข้อมูล!"
                ]);
            }
        }
        if ($request->get('state')=='0'){
            $check_data = DB::table('save_example_type_detail')->where('id_res',$request->get('result_id'))->first();
            if ($check_data!=null){
                return response()->json([
                    "status" => "error",
                    "message"=> "สถานะไม่สามรถปิดได้!"
                ]);
            }
        }
        $data_res = ResultProduct::find($request->get('result_id'));
        $data_res->status = $request->get('state');
        if ($data_res->save()){
            $check_data_table = ResultProductDetail::query()->where('id_result',$request->get('result_id'))->first();
            if ($check_data_table!=null){
                $del_data_table = ResultProductDetail::query()->where('id_result',$request->get('result_id'))->get();
                foreach ($del_data_table as $list_del){
                    $del_data = ResultProductDetail::find($list_del->id);
                    $del_data->delete();
                }
            }
            if($request->name_result!=null){
                for ($i = 0; $i < count($request->name_result); $i++) {
                    $data_table = new ResultProductDetail([
                        'id_result' => $request->get('result_id'),
                        'name_result' => $request->name_result[$i],
                        'type_result' => $request->type_result[$i],
                        'action'=>'create'
                    ]);
                    if ($request->type_result[$i]=='เลือกประเภทข้อมูล'){
                        return response()->json([
                            "status" => "error",
                            "message"=> "กรุณาเลือกประเภทข้อมูล!"
                        ]);
                    }
                    $data_table->save();
                }
            }

            return response()->json([
                "status" => "success"
            ]);
        }
    }

    public function save_data(Request $request)
    {
        if ($request->get('tis_standard')=='เลือกมาตรฐาน'){
            return response()->json([
                "status" => "error",
                "message"=> "กรุณาเลือกมาตรฐาน!"
            ]);
        }
        if ($request->name_result != null){
            $error_name_result = 0;
            for ($i = 0; $i < count($request->name_result); $i++) {
                if ($request->name_result[$i] == null) {
                    $error_name_result += 1;
                }
            }
            if ($error_name_result > 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกชื่อรายการผลทดสอบ!"
                ]);
            }
        }
        if ($request->type_result != null){
            $error_type_result = 0;
            for ($i = 0; $i < count($request->type_result); $i++) {
                if ($request->name_result[$i] == 'เลือกประเภทข้อมูล') {
                    $error_type_result += 1;
                }
            }
            if ($error_type_result > 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณาเลือกประเภทข้อมูล!"
                ]);
            }
        }

        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $result_product = new ResultProduct([
            'tis_standard' => $request->get('tis_standard'),
            'status' => $request->get('state'),
            'user_create' => $full_name,

        ]);
        if ($result_product->save()){
            if($request->name_result!=null){
                for ($i = 0; $i < count($request->name_result); $i++) {
                    $data_table = new ResultProductDetail([
                        'id_result' => $result_product->id,
                        'name_result' => $request->name_result[$i],
                        'type_result' => $request->type_result[$i],
                        'action'=>'create'
                    ]);
                    if ($request->type_result[$i]=='เลือกประเภทข้อมูล'){
                        return response()->json([
                            "status" => "error",
                            "message"=> "กรุณาเลือกประเภทข้อมูล!"
                        ]);
                    }
                    $data_table->save();
                }
            }

            return response()->json([
                "status" => "success"
            ]);
        }else{
            return response()->json([
                "status" => "error",
                "message"=> "เพิ่มข้อมูลไม่สำเร็จ!"
            ]);
        }
    }

    public function delete(Request $request)
    {
        $cut_data = explode(',',$request->get('id'));
        foreach ($cut_data as $list){
            if ($list!=""){
                $check_data = DB::table('save_example_type_detail')->where('id_res',$list)->first();
                if ($check_data!=null){
                    $data_res = ResultProduct::find($list);
                    return response()->json([
                        "status" => "error",
                        "message"=> "เลข มอก. ". $data_res->tis_standard ." ไม่สามรถยกเลิกได้!"
                    ]);
                }else{
                    $data_res = ResultProduct::find($list);
                    $data_res->status = 'ยกเลิก';
                    $data_res->save();
                }
            }
        }
        return response()->json([
            "status" => "success",
        ]);
    }

    public function delete_detail(Request $request)
    {
        $data = ResultProductDetail::find($request->get('id'));
        $check = SaveExampleTypeDetail::query()->where('result_id',$request->get('id'))->pluck('type_detail');
        $save_type = SaveExampleTypeDetail::query()->where('result_id',$request->get('id'))->pluck('id');
        $i=0;
        $data->delete();
        foreach ($check as $temp){
            if ($temp==null){
                $data_detail = SaveExampleTypeDetail::find($save_type[$i]);
                $data_detail->delete();
            }else{
                return response()->json([
                    "status" => "error",
                    "message" => "ข้อมูลนี้ถูกนำไปใช้แล้วไม่สามารถลบได้!"
                ]);
            }
            $i++;
        }
        return response()->json([
            "status" => "success",
        ]);


    }
    public function update_status_off(Request $request){
        $cut_data = explode(',',$request->get('id'));
        foreach ($cut_data as $list){
            if ($list!=""){
                $check_data = DB::table('save_example_type_detail')->where('id_res',$list)->first();
                if ($check_data!=null){
                    $data_res = ResultProduct::find($list);
                    return response()->json([
                        "status" => "error",
                        "message"=> "เลข มอก. ". $data_res->tis_standard ." ไม่สามรถปิดด้!"
                    ]);
                }else{
                    $data_res = ResultProduct::find($list);
                    $data_res->status = '0';
                    $data_res->save();
                }
            }
        }
        return response()->json([
            "status" => "success",
        ]);
    }
    public function update_status_on(Request $request){
        $cut_data = explode(',',$request->get('id'));
        foreach ($cut_data as $list){
            if ($list!=""){
                $check_data = DB::table('save_example_type_detail')->where('id_res',$list)->first();
                if ($check_data!=null){
                    $data_res = ResultProduct::find($list);
                    return response()->json([
                        "status" => "error",
                        "message"=> "เลข มอก. ". $data_res->tis_standard ." ไม่สามรถเปิดได้!"
                    ]);
                }else{
                    $data_res = ResultProduct::find($list);
                    $data_res->status = '1';
                    $data_res->save();
                }
            }
        }
        return response()->json([
            "status" => "success",
        ]);
    }

}
