<?php

namespace App\Http\Controllers\Asurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Asurv\EsurvTers21;
use App\Models\Asurv\EsurvTers21detail;
use App\Models\Asurv\EsurvVolumeTers21;
use App\Models\Asurv\EsurvVolumeTers21Detail;
use App\Models\Asurv\Applicant21TerProductDetail;
use App\Models\Asurv\Volume21TerProductDetail;

use App\Models\Besurv\Signer;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Besurv\Department;

use App\Models\Basic\Tis;
use App\Models\Basic\SubDepartment;
use App\Models\Sso\User AS SSO_User;

use App\report21_export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use HP;

class Report21ExportController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/volume_21ter/';
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_request']  = $request->get('filter_request', '');
        $filter['filter_start_month']  = $request->get('filter_start_month', '');
        $filter['filter_start_year']   = $request->get('filter_start_year', '');
        $filter['filter_end_month']    = $request->get('filter_end_month', '');
        $filter['filter_end_year']     = $request->get('filter_end_year', '');
        $filter['filter_notify'] = $request->get('filter_notify', '');
        $filter['filter_status']       = $request->get('filter_status', '');
        $filter['filter_title']        = $request->get('filter_title', '');
        $filter['filter_department']        = $request->get('filter_department', '');
        $filter['filter_sub_department']    = $request->get('filter_sub_department', '');
        $filter['filter_tis']               = $request->get('filter_tis', '');

        $Query = new EsurvVolumeTers21;

        if ($filter['filter_status'] != '') {
            $Query = $Query->where('state_notify_report', $filter['filter_status']);
        }
        if ($filter['filter_notify'] != '') {
            $Query = $Query->where('inform_close', $filter['filter_notify']);
        }
        if ($filter['filter_start_month']!='') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'].'-'.$filter['filter_start_month'].'-01'.' 00:00:00');
        }

        if ($filter['filter_end_month']!='') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'].'-'.$filter['filter_end_month'].'-31'.' 00:00:00');
        }

        if($filter['filter_title']!=''){
            $trader_ids = SSO_User::where('name', 'LIKE', '%'.$filter['filter_title'].'%')->pluck('id');
            $applicant_21ter_ids = EsurvTers21::where('ref_no', 'LIKE', '%'.$filter['filter_title'].'%')->orWhere('title', 'LIKE', '%'.$filter['filter_title'].'%')->orWhereIn('created_by', $trader_ids)->pluck('id');
            $Query = $Query->whereIn('applicant_21ter_id', $applicant_21ter_ids);
        }

        if($filter['filter_tis']!=''){
            $applicant_21ter_ids = EsurvTers21::orwhereJsonContains('different_no',$filter['filter_tis'])->select('id');
            $Query = $Query->whereIn('applicant_21ter_id', $applicant_21ter_ids);
        }

        if($filter['filter_department']!='' || $filter['filter_sub_department']!=''){
            $department                  = $filter['filter_department'];
            $filter_sub_department       = $filter['filter_sub_department'];
            if($filter_sub_department != ''){
                $sub_department = SubDepartment::where('did', $department)->where('sub_id', $filter_sub_department)->select('sub_id');
            }else{
                $sub_department = SubDepartment::where('did', $department)->select('sub_id');
            }
            $tis_sub        = TisSubDepartment::whereIn('sub_id', $sub_department)->select('tb3_Tisno');
            $tb3_tis_autono = Tis::whereIn('tb3_Tisno',$tis_sub)->pluck('tb3_TisAutono')->toArray();
            if(count($tb3_tis_autono) > 0){
                $select = [];
                foreach($tb3_tis_autono as $item){
                        $applicant_21ter_ids = EsurvTers21::orwhereJsonContains('different_no',explode(" ",$item))->select('id')->get();
                        if(count($applicant_21ter_ids) > 0){
                            foreach($applicant_21ter_ids as $item1){
                                $select[$item1->id] = $item1->id;
                           }
                        }
                }
                $Query = $Query->whereIn('applicant_21ter_id', $select);
            }else{
                $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
            }
        }

        $report21_export = $Query->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        $temp_num = $report21_export->firstItem();

        //สิทธิ์การตรวจตามกลุ่มงานย่อย
        $user_tis = $user->tis->pluck('tb3_Tisno');

        return view('asurv.report21_export.index', compact('report21_export', 'filter', 'temp_num','user_tis'));
    }

    public function create()
    {
        return view('asurv.report21_export.create');
    }

    public function show($id)
    {
        return view('asurv.report21_export.show');
    }

    public function edit($ID)
    {

        $data = EsurvVolumeTers21::findOrFail($ID);

        $data->start_date = $data->start_date?HP::revertDate($data->start_date, true):null;
        $data->end_date = $data->end_date?HP::revertDate($data->end_date, true):null;

        $data_volume_main = EsurvVolumeTers21::query()->where('applicant_21ter_id',$data->applicant_21ter_id)->orderby('id','asc')->get();
        $id_detail = array();
        foreach ($data_volume_main as $list_get_detail){
            $id_detail[] = $list_get_detail->id;
        }

        $data_detail = EsurvVolumeTers21Detail::query()->where('volume_21ter_id', $data->id)->get();//รายละเอียด

        foreach($data_detail as $key=>$val){
            $sum = EsurvVolumeTers21Detail::query()->where('volume_21ter_id', '<', $val->volume_21ter_id)->where('detail_id', $val->detail_id)->sum('quantity');
            $data_detail[$key]['quantity_old'] = $sum;
        }

        $data_detail_ck = EsurvVolumeTers21Detail::query()->whereIn('volume_21ter_id', $id_detail)->groupBy('volume_21ter_id')->get();//รายละเอียด

        // $data_detail_app = EsurvTers21detail::query()->where('applicant_21ter_id',$ID)->get();
        $data_get = EsurvVolumeTers21::query()->where('applicant_21ter_id',$data->applicant_21ter_id)->get();
        $id = array();
        foreach ($data_get as $list) {
            $id[] = $list->id;
        }
        $data_volume_detail = DB::table('esurv_volume_21ter_product_details')->whereIn('volume_21ter_id',$id)->get();

        $data_file_check = EsurvVolumeTers21::query()->where('id',$ID)->first();
        if ($data_file_check->attach!='[]' and $data_file_check->attach!=null){
            $attachs = json_decode($data['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
        }else{
            $attachs = null;
        }
        $attach_path = $this->attach_path;



        $esurv_ter21 = EsurvTers21::where('id',$data->applicant_21ter_id)->first();
        $signer_options = [];
    //     if(!is_null($esurv_ter21)){
    //         $tis_no = json_decode($esurv_ter21->different_no);
    //     if(!empty($tis_no) &&  count($tis_no) > 0){
    //         $tb3_tisno              = Tis::where('tb3_TisAutono',$tis_no[0])->pluck('tb3_Tisno');
    //         $tis_sub_department     = TisSubDepartment::whereIn('tb3_Tisno', $tb3_tisno)->pluck('sub_id');
    //         $sub_depart             = SubDepartment::whereIn('sub_id', $tis_sub_department)->pluck('did');
    //         $department             = Department::whereIn('did', $sub_depart)->pluck('did');
    //         $signer_options         = Signer::whereJsonContains('main_group', $department)->where('state',1)->pluck('name', 'id');
    //      }
    //    }
        //ใบขนขาออก
        $file_leave = json_decode($data->file_leave,true);
        $file_leave = !is_null($file_leave) && count($file_leave)>0 ? $file_leave : null;

                    //รายละเอียดผลิตภัณฑ์อุตสาหกรรม
        $volume_21ter_details = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('quantity', 'detail_id')->toArray();//ที่ถูกเลือก
        $volume_21ter_details2 = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('quantity_export', 'detail_id')->toArray();//ที่ถูกเลือก
        $volume_21ter_detail_start_product_date = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('start_product_date', 'detail_id')->toArray();//ที่ถูกเลือก
        $volume_21ter_detail_end_product_date = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('end_product_date', 'detail_id')->toArray();//ที่ถูกเลือก
        $volume_21ter_detail_start_export_date = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('start_export_date', 'detail_id')->toArray();//ที่ถูกเลือก
        $volume_21ter_detail_end_export_date = Volume21TerProductDetail::where("volume_21ter_id", $data->id)->pluck('end_export_date', 'detail_id')->toArray();//ที่ถูกเลือก
        $applicant_21ter_details = Applicant21TerProductDetail::where("applicant_21ter_id", $data->applicant_21ter_id)
                                                                  ->with('data_unit')
                                                                  ->with('informed')
                                                                  ->get();//ทั้งหมดที่มี ตามเลขอ้างอิง


        return view('asurv.report21_export.edit', ["data" => $data, 'data_detail' => $data_detail
            ,'data_volume_detail'=>$data_volume_detail,'data_volume_main'=>$data_volume_main
        ],compact('attachs','attach_path','data_detail_ck','signer_options','file_leave',  'volume_21ter_details',
                                                                                            'volume_21ter_details2',
                                                                                            'volume_21ter_detail_start_product_date',
                                                                                            'volume_21ter_detail_end_product_date',
                                                                                            'volume_21ter_detail_start_export_date',
                                                                                            'volume_21ter_detail_end_export_date',
                                                                                            'applicant_21ter_details'));
    }

    public function update(Request $request)
    {
        return redirect('report21_export/report21_export');
    }

    public function save_data(Request $request)
    {
        if ($request->state_notify_report === 'เลือกสถานะ') {
            return response()->json([
                'status' => 'error',
                'message' => 'กรุณาเลือกสถานะ'
            ]);
        }

        $data                           = EsurvVolumeTers21::findOrFail($request->id);
        $data->officer_report           = $request->officer_report;
        // $data->signer_id                = $request->signer_id;
        // $data->signer_name              = $request->signer_name;
        // $data->signer_position          = $request->signer_position;
        $data->remark_officer_report    = $request->remark_officer_report;
        $data->state_notify_report      = $request->state_notify_report;

        if($data->state_notify_report==0){
            $data2 = EsurvTers21::findOrFail($data->applicant_21ter_id);
            $data2->state_check = 0;
            $data2->save();
        }

        if ($data->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

    }

    public function download_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->download($public . $this->attach_path.$NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
        if(HP::checkFileStorage($attach_path. $NAME)){
            HP::getFileStoragePath($attach_path. $NAME);
            $filePath =  response()->download($public.'/uploads/'.$attach_path.$NAME);
                return $filePath;
        }else{
            return 'ไม่พบไฟล์';
        }
    }

    public function preview_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->file($public . $this->attach_path.$NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
        if(HP::checkFileStorage($attach_path. $NAME)){
            HP::getFileStoragePath($attach_path. $NAME);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.$NAME);
                return $filePath;
        }else{
            return 'ไม่พบไฟล์';
        }
    }

    public function get_khrang_thi($applicant_21ter_id, $created_by){
           $result = EsurvVolumeTers21::where('applicant_21ter_id',$applicant_21ter_id)->where('created_by')->count();
           return $result;
    }

}
