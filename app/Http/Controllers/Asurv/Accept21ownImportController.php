<?php

namespace App\Http\Controllers\Asurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\accept21own_import;
use App\Models\Asurv\EsurvOwns21;
use App\Models\Asurv\EsurvOwns21detail;

use App\Models\Besurv\Signer;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Besurv\Department;

use App\Models\Basic\Tis;
use App\Models\Basic\SubDepartment;
use App\Models\Sso\User AS SSO_User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use HP;
use HP_API_PID;
class Accept21ownImportController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/applicant_21own/';
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_start_month'] = $request->get('filter_start_month', '');
        $filter['filter_start_year']  = $request->get('filter_start_year', '');
        $filter['filter_end_month']   = $request->get('filter_end_month', '');
        $filter['filter_end_year']    = $request->get('filter_end_year', '');
        $filter['filter_notify']      = $request->get('filter_notify', '');
        $filter['filter_request']     = $request->get('filter_request', '');
        $filter['filter_title']       = $request->get('filter_title', '');
        $filter['filter_department']        = $request->get('filter_department', '');
        $filter['filter_sub_department']    = $request->get('filter_sub_department', '');
        $filter['filter_tis']               = $request->get('filter_tis', '');
        $Query = new EsurvOwns21;

        if ($filter['filter_request']!='') {
            $Query = $Query->where('state', $filter['filter_request']);
        }
        if ($filter['filter_notify']!='') {
            $Query = $Query->where('state_check', $filter['filter_notify']);
        }
        if ($filter['filter_start_month']!='') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'].'-'.$filter['filter_start_month'].'-01'.' 00:00:00');
        }

        if ($filter['filter_end_month']!='') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'].'-'.$filter['filter_end_month'].'-31'.' 00:00:00');
        }

        if($filter['filter_title']!=''){
            $trader_ids = SSO_User::where('name', 'LIKE', '%'.$filter['filter_title'].'%')->pluck('id');
            $Query = $Query->where('ref_no', 'LIKE', '%'.$filter['filter_title'].'%')->orWhere('title', 'like', '%'.$filter['filter_title'].'%')->orWhereIn('created_by', $trader_ids);
        }

        if($filter['filter_tis']!=''){
            $Query = $Query->orwhereJsonContains('different_no',$filter['filter_tis']);
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
                        $esurv_owns21s = EsurvOwns21::orwhereJsonContains('different_no',explode(" ",$item))->select('id')->get();
                        if(count($esurv_owns21s) > 0){
                            foreach($esurv_owns21s as $item1){
                                $select[$item1->id] = $item1->id;
                           }
                        }
                }
                $Query = $Query->whereIn('id', $select);
            }else{
                $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
            }
        }

        $accept21own_import = $Query->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        $temp_num           = $accept21own_import->firstItem();

        //สิทธิ์การตรวจตามกลุ่มงานย่อย
        // $user_tis = $user->tis->pluck('tb3_Tisno');

        return view('asurv.accept21own_import.index', compact('accept21own_import', 'filter', 'temp_num'));
    }

    public function create()
    {
        return view('asurv.accept21own_import.create');
    }

    public function show($id)
    {
        return view('asurv.accept21own_import.show');
    }

    public function edit($id)
    {
        $data = EsurvOwns21::findOrFail($id);

        $data->start_date = $data->start_date?HP::revertDate($data->start_date, true):null;
        $data->end_date = $data->end_date?HP::revertDate($data->end_date, true):null;

        $data_detail = EsurvOwns21detail::query()->where('applicant_21own_id',$id)->get();

        $applicant_21ter = EsurvOwns21::findOrFail($id);

        $product_details = [(object)['id'=>'']];

        $attach_product_plan = json_decode($applicant_21ter['attach_product_plan']);
        $attach_product_plan = !empty($attach_product_plan)?$attach_product_plan:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_hiring_book = json_decode($applicant_21ter['attach_hiring_book']);
        $attach_hiring_book = !empty($attach_hiring_book)?$attach_hiring_book:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_factory_license = json_decode($applicant_21ter['attach_factory_license']);
        $attach_factory_license = !empty($attach_factory_license)?$attach_factory_license:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_standard_to_made = json_decode($applicant_21ter['attach_standard_to_made']);
        $attach_standard_to_made = !empty($attach_standard_to_made)?$attach_standard_to_made:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_difference_standard = json_decode($applicant_21ter['attach_difference_standard']);
        $attach_difference_standard = !empty($attach_difference_standard)?$attach_difference_standard:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_drawing = json_decode($applicant_21ter['attach_drawing']);
        $attach_drawing = !empty($attach_drawing)?$attach_drawing:(object)['file_name'=>'', 'file_client_name'=>''];

        //ไฟล์แนบ

        $data_file_check = EsurvOwns21::query()->where('id',$id)->first();
        if ($data_file_check->attach_other!='[]' and $data_file_check->attach_other!=null){
            $attachs = json_decode($applicant_21ter['attach_other']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
        }else{
            $attachs = null;
        }

        $attach_path = $this->attach_path; //path ไฟล์แนบ

        $tb3_tisno      = [];
        $signer_options = [];
        $user = auth()->user();
         if($user->isAdmin() === true){
            $signer_options         =  Signer::where('state',1)->pluck('name', 'id');
         }else{
            $sub_depart             = SubDepartment::where('sub_id', $user->reg_subdepart)->pluck('did');
            $department             = Department::whereIn('did', $sub_depart)->pluck('did');
            $signer_options         =  Signer::whereJsonContains('main_group', $department)->where('state',1)->pluck('name', 'id');
         }
        $tis_no = json_decode($data->different_no);
        if(!empty($tis_no)){
            $tb3_tisno              = Tis::select('tb3_Tisno','tb3_TisThainame')->whereIn('tb3_TisAutono',$tis_no)->get();
            // $tis_sub_department     = TisSubDepartment::whereIn('tb3_Tisno', $tb3_tisno->pluck('tb3_Tisno'))->pluck('sub_id');
            // $sub_depart             = SubDepartment::whereIn('sub_id', $tis_sub_department)->pluck('did');
            // $department             = Department::whereIn('did', $sub_depart)->pluck('did');
            // $signer_options         = Signer::whereJsonContains('main_group', $department)->where('state',1)->pluck('name', 'id');
        }

        return view('asurv.accept21own_import.edit',["data"=>$data,'data_detail'=>$data_detail], compact(
            'applicant_21ter',
            'product_details',
            'attach_product_plan',
            'attach_hiring_book',
            'attach_factory_license',
            'attach_standard_to_made',
            'attach_difference_standard',
            'attach_drawing',
            'attachs',
            'attach_path',
            'signer_options',
            'tb3_tisno'
        ));
    }

    public function update(Request $request)
    {
        return redirect('accept21own_import/accept21own_import');
    }

    public function save_data(Request $request){
        $data = EsurvOwns21::findOrFail($request->id);
        $data->remake_officer_export    = $request->remake_officer_export;
        $data->state                    = $request->state;
        $data->officer_export           = $request->officer_export;
        if ($data->state=='4'){
            $data->state_check = 1;
            $data->signer_id                = $request->signer_id;
            $data->signer_name              = $request->signer_name;
            $data->signer_position          = $request->signer_position;
            $data->state_approved_date      = date('Y-m-d');
        }else{
            $data->state_check = 0;
        }
        if ($data->save()){
            return response()->json([
                'status'=>'success'
            ]);
        }
    }
    public function download_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->download($public . $this->attach_path.$NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
            // return $attach_path. $NAME;
        if(HP::checkFileStorage($attach_path. $NAME)){
            HP::getFileStoragePath($attach_path. $NAME);
            $filePath =  response()->download($public.'/uploads/'.$attach_path.$NAME);
                return $filePath;
        }else{
            return 'ไม่พบไฟล์';
        }
    }
    public function detail($ID){
        $data = EsurvOwns21::findOrFail($ID);

        $data->start_date = $data->start_date?HP::revertDate($data->start_date, true):null;
        $data->end_date = $data->end_date?HP::revertDate($data->end_date, true):null;

        $data_detail = EsurvOwns21detail::query()->where('applicant_21own_id',$ID)->get();

        $applicant_21ter = EsurvOwns21::findOrFail($ID);

        $product_details = [(object)['id'=>'']];

         $attach_product_plan = json_decode($applicant_21ter['attach_product_plan']);
        $attach_product_plan = !empty($attach_product_plan)?$attach_product_plan:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_hiring_book = json_decode($applicant_21ter['attach_hiring_book']);
        $attach_hiring_book = !empty($attach_hiring_book)?$attach_hiring_book:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_factory_license = json_decode($applicant_21ter['attach_factory_license']);
        $attach_factory_license = !empty($attach_factory_license)?$attach_factory_license:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_standard_to_made = json_decode($applicant_21ter['attach_standard_to_made']);
        $attach_standard_to_made = !empty($attach_standard_to_made)?$attach_standard_to_made:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_difference_standard = json_decode($applicant_21ter['attach_difference_standard']);
        $attach_difference_standard = !empty($attach_difference_standard)?$attach_difference_standard:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_drawing = json_decode($applicant_21ter['attach_drawing']);
        $attach_drawing = !empty($attach_drawing)?$attach_drawing:(object)['file_name'=>'', 'file_client_name'=>''];
        //ไฟล์แนบ

        $data_file_check = EsurvOwns21::query()->where('id',$ID)->first();
        if ($data_file_check->attach_other!='[]' and $data_file_check->attach_other!=null){
            $attachs = json_decode($applicant_21ter['attach_other']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
        }else{
            $attachs = null;
        }

        $attach_path = $this->attach_path; //path ไฟล์แนบ

        $tb3_tisno      = [];
        $tis_no = json_decode($data->different_no);
        if(!empty($tis_no)){
            $tb3_tisno              = Tis::select('tb3_Tisno','tb3_TisThainame')->whereIn('tb3_TisAutono',$tis_no)->get();
        }
        return view('asurv.accept21own_import.detail',["data"=>$data,'data_detail'=>$data_detail], compact(
            'applicant_21ter',
            'product_details',
            'attach_product_plan',
            'attach_hiring_book',
            'attach_factory_license',
            'attach_standard_to_made',
            'attach_difference_standard',
            'attach_drawing',
            'attachs',
            'attach_path',
            'tb3_tisno'
        ));
    }
    public function update_status($ID,$STATE){
        $data = EsurvOwns21::findOrFail($ID);
        if ($STATE=='0'){
            $data->state_check = 1;
        }else{
            $data->state_check = 0;
        }

        if ($data->save()){
            $filter = [];
            $filter['perPage'] =10;
            $filter['filter_department']        = '';
            $filter['filter_sub_department']    = '';
            $filter['filter_tis']               = '';
            $Query = new EsurvOwns21;

            $accept21own_import = $Query->sortable()->paginate($filter['perPage']);
            $temp_num = $accept21own_import->firstItem();

            return view('asurv.accept21own_import.index', compact('accept21own_import', 'filter', 'temp_num'));
        }
    }

    public function check_api_pid(Request $request)
    {

        $data  =  EsurvOwns21::findOrFail($request->id);

         return response()->json([
                                    'message' =>  HP_API_PID::CheckDataApiPid($data,(new EsurvOwns21)->getTable())
                                 ]);
    }

}
