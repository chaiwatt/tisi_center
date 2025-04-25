<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sso\ApplicationInspector;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Section5\ApplicationLabStaff;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabAccept;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\ApplicationLabAudit;
use App\Mail\Section5\ApplicationLabAcceptMail;
use App\Models\Basic\Tis;
use Mail;
use App\Models\Tis\Standard;
use App\Models\Bsection5\Workgroup;
use Mpdf\Mpdf;

class ApplicationLabAcceptController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_lab_accept/';
        $this->attach_path_crop = 'tis_attach/application_lab_accept_crop/';
    }


    public function data_list(Request $request)
    {

        $model = str_slug('application-lab-accept','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');

        $filter_start_date = $request->input('filter_start_date');
        $filter_end_date   = $request->input('filter_end_date');

        $filter_assign_start_date = $request->input('filter_assign_start_date');
        $filter_assign_end_date   = $request->input('filter_assign_end_date');

        $filter_tis_id = $request->input('filter_tis_id');
        $filter_applicant_type    = $request->input('filter_applicant_type');

        $filter_orderby    = $request->input('filter_orderby');
        $filter_audit_type    = $request->input('filter_audit_type');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationLab::query()->with([
                                            'app_scope_standard.tis_standards',
                                            'app_staff.user_staff'
                                        ])
                                        ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            if( strpos( $search_full , 'LAB-' ) !== false){
                                                return $query->where('application_no',  'LIKE', "%$search_full%");
                                            }else{

                                                return  $query->where(function ($query2) use($search_full) {

                                                    $ids = ApplicationLabScope::where(function ($query) use($search_full) {
                                                                            $query->whereHas('tis_standards', function($query) use ($search_full){
                                                                                        $query->where(function ($query) use($search_full) {
                                                                                                $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                            });
                                                                                    }); 
                                                                        })
                                                                        ->select('application_lab_id');

                                                    $query2->Where(DB::raw("REPLACE(applicant_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(applicant_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrwhereHas('app_staff.user_staff', function($query) use ($search_full){
                                                                $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                            })
                                                            ->OrwhereIn('id', $ids)
                                                            ->OrWhere('application_no',  'LIKE', "%$search_full%");
                                                });
                                            }
                                        })
                                        ->when( $filter_status , function ($query, $filter_status){
                                            return $query->where('application_status', $filter_status);
                                        })
                                        ->when( $filter_applicant_type , function ($query, $filter_applicant_type){
                                            return $query->where('applicant_type', $filter_applicant_type);
                                        })
                                        ->when($filter_start_date, function ($query, $filter_start_date){
                                            $filter_start_date = HP::convertDate($filter_start_date, true);
                                            return $query->where('application_date', '>=', $filter_start_date);
                                        })
                                        ->when($filter_end_date, function ($query, $filter_end_date){
                                            $filter_end_date = HP::convertDate($filter_end_date, true);
                                            return $query->where('application_date', '<=', $filter_end_date);
                                        })
                                        ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                            $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                            return  $query->whereHas('app_staff', function($query) use ($filter_assign_start_date){
                                                                $query->where('assign_date', '>=', $filter_assign_start_date);
                                                            });
                                        })
                                        ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                            $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                            return  $query->whereHas('app_staff', function($query) use ($filter_assign_end_date){
                                                                $query->where('assign_date', '<=', $filter_assign_end_date);
                                                            });
                                        })
                                        ->when($filter_tis_id, function ($query, $filter_tis_id){
                                            $query->whereHas('app_scope_standard', function($query) use ($filter_tis_id){
                                                $query->where('tis_id', $filter_tis_id);
                                            });
                                        })
                                        ->when( $filter_audit_type , function ($query, $filter_audit_type){
                                            return $query->where('audit_type', $filter_audit_type );
                                        })
                                        ->when(!$user->isAdmin(), function($query) use ($user) {//ถ้าไม่ใช่ admin

                                            //id ตาราง basic_branch_groups สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
                                            $tis_ids = Workgroup::UserTisIds($user->getKey());
                                      
                                            $id_query = ApplicationLabScope::whereIn('tis_id', $tis_ids)->select('application_lab_id');
                                            $query->whereIn('id', $id_query);

                                        })
                                        ->when( (!auth()->user()->can('view_all-'.$model)) , function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                            $id_query = ApplicationLabStaff::where('staff_id', $user->getKey())->select('application_lab_id');
                                            $query->whereIn('id', $id_query);
                                        })
                                        ->where(function($query){
                                            $query->whereNotIn('application_status', [0]);
                                        });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-app_no="'. $item->application_no .'" value="'. $item->id .'">';
                            })
                            ->addColumn('applicant_type', function ($item) {
                                $application_type_arr = [ 1 => 'ขอขึ้นทะเบียนใหม่', 2 => 'ขอเพิ่มเติมขอบข่าย', 3 => 'ขอลดขอบข่าย', 4 => 'ขอแก้ไขข้อมูล'];
                                $applicant_type = array_key_exists( $item->applicant_type,  $application_type_arr )?$application_type_arr [ $item->applicant_type ]:'-';

                                $audit_type_arr = [1 => '<span class="text-success">ตรวจตามใบรับรอง</span>', 2 => '<span class="text-info">ตรวจตามภาคผนวก ก.</span>'];
                                $audit_type     = array_key_exists($item->audit_type, $audit_type_arr) ? $audit_type_arr[$item->audit_type] : '-' ;

                                return '<em>'.( $applicant_type).'</em>'."<p><u>({$audit_type})</u></p>";

                            })
                            ->addColumn('application_no', function ($item) {
                                return $item->application_no.'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>';
                            })
                            ->addColumn('applicant_name', function ($item) {
                                return '<div>'.(!empty($item->lab_name)?$item->lab_name:'-').'</div>'.(!empty($item->applicant_name)?'('.$item->applicant_name.')':'-');
                            })
                            ->addColumn('applicant_taxid', function ($item) {
                                return !empty($item->applicant_taxid)?$item->applicant_taxid:'-';
                            })
                            ->addColumn('standards', function ($item) {
                                // return '<button class="btn btn-link modal_show_scope" data-id="'.($item->id).'" data-application_no="'.($item->application_no).'">'.(!empty($item->ScopeStandard)?$item->ScopeStandard:'-').'</button>';
                                $item->ScopeStandard;
                                return (!empty($item->ScopeStandard)?$item->ScopeStandard:'-');
                            })
                            ->addColumn('status_application', function ($item) {
                                if( !empty($item->delete_state) ){
                                    return (!empty($item->StatusFullTitle)?'<div class="text-danger">'.$item->StatusFullTitle.'<div>':'-').'<div><em>'.(!empty($item->delete_at)?HP::DateThai($item->delete_at):null).'</em><div>';
                                }else{
                                    return !empty($item->StatusFullTitle)?$item->StatusFullTitle:'ฉบับร่าง';
                                }
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->assign_by)?$item->AssignStaff:'รอดำเนินการ').(!empty($item->assign_date)?'<br>'.HP::DateThaiFull($item->assign_date):null);
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn =  ' <a href="'. url('section5/application_lab_accept/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('edit-'.$model) ){
                                    if(in_array($item->application_status, [1])){
                                        $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/application_lab_accept/'.$item->id.'/edit') .'" data-toggle="tooltip" data-placement="top" title="พิจารณาคำขอ"><i class="fa fa-search" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle"  title="พิจารณาคำขอ" disabled><i class="fa fa-search" aria-hidden="true"></i></button>';
                                    }
                                    // $btn .= ' <a class="btn btn-primary btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/application_lab_accept/approve/'.$item->id) .'" ><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('view-'.$model) ){
                                    $btn .= ' <a class="btn btn-danger btn-xs waves-effect waves-light" target="_blank" href="'. url('section5/application_lab_accept/print/'.$item->id) .'" data-toggle="tooltip" data-placement="top" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                                }

                                return $btn;

                            })
                            ->order(function ($query) use ($filter_orderby) {
                                // ['1'=>'วันที่ยื่นมากไปน้อย','2'=>'วันที่ยื่นน้อยไปมาก','3'=>'เลขที่คำขอมากไปน้อย','4'=>'เลขที่คำขอน้อยไปมาก']
                                switch ($filter_orderby) {
                                    case "1":
                                        $query->orderBy('application_date', 'DESC');
                                      break;
                                    case "2":
                                        $query->orderBy('application_date', 'ASC');
                                      break;
                                    case "3":
                                        $query->orderBy('application_no', 'DESC');
                                      break;
                                    case "4":
                                        $query->orderBy('application_no', 'ASC');
                                      break;
                                    default:
                                        $query->orderBy('application_date', 'DESC');
                                  }
                                
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by', 'applicant_type', 'applicant_name','application_no','standards','status_application'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_accept",  "name" => 'ตรวจสอบคำขอ LAB' ],
            ];

            return view('section5.application_lab_accept.index', compact('breadcrumbs'));

        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('add-'.$model)) {

        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('add-'.$model)) {

        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('view-'.$model)) {

            $applicationlab = ApplicationLab::findOrFail($id);
            $application_labs_scope = ApplicationLabScope::where('application_lab_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');
            $standards = Standard::selectRaw('id, CONCAT_WS(" : ", CONCAT_WS(" - ", tis_year, tis_no), title) AS standard_title')->whereIn('id', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();

            $applicationlab->show  = true;

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_accept",  "name" => 'ตรวจสอบคำขอ LAB' ],
                [ "link" => "/section5/application_lab_accept/$id",  "name" => 'รายละเอียด' ],

            ];

            return view('section5/application_lab_accept.show', compact('applicationlab', 'standards','breadcrumbs'));

        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationlab = ApplicationLab::findOrFail($id);
            $application_labs_scope = ApplicationLabScope::where('application_lab_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');
            $standards = Tis::selectRaw('tb3_TisAutono AS id, CONCAT_WS(" : ", tb3_Tisno, tb3_TisThainame) AS standard_title')->whereIn('tb3_TisAutono', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();
      
            $applicationlab->edited  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_accept",  "name" => 'ตรวจสอบคำขอ LAB' ],
                [ "link" => "/section5/application_lab_accept/$id/edit",  "name" => 'พิจารณาคำขอ' ],

            ];
            return view('section5/application_lab_accept.edit', compact('applicationlab', 'standards','breadcrumbs'));

        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationlab = ApplicationLab::findOrFail($id);
            $application_labs_scope = ApplicationLabScope::where('application_lab_id', $id);

            $standards = Standard::selectRaw('id, CONCAT_WS(" : ", CONCAT_WS(" - ", tis_year, tis_no), title) AS standard_title')->whereIn('id', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();

            $applicationlab->approve  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_accept",  "name" => 'ตรวจสอบคำขอ LAB' ],
                [ "link" => "/section5/application_lab_accept/approve/$id",  "name" => 'พิจารณาคำขอ' ],

            ];
            return view('section5/application_lab_accept.approve', compact('applicationlab', 'standards','breadcrumbs'));
        }
        abort(403);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationLab::findOrFail($id);
            $requestData = $request->all();

            if(in_array($requestData['application_status'], [3, 4])){//3=เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน, 4=เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน
                $requestData['accept_date'] = date('Y-m-d');
                $requestData['accept_by']   = auth()->user()->getKey();
            }

            $application->update($requestData);

            $requestData['application_lab_id'] = $id;
            $requestData['application_no'] = @$application->application_no;
            if(!empty($request->input('noti_email'))){
                $requestData['noti_email'] = json_encode(explode(',', $request->input('noti_email')));
            }
            if( isset($requestData['repeater-date']) && !empty($requestData['repeater-date']) && count($requestData['repeater-date']) > 0){
                $appointment_dates = $requestData['repeater-date'];
                $date_arr = [];
                foreach($appointment_dates as $key=>$appointment_date){
                    $date_arr[] = !empty($appointment_date['appointment_date'])?HP::convertDate($appointment_date['appointment_date'], true):null;
                }
                $requestData['appointment_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
            }
            $requestData['created_by'] = auth()->user()->getKey();

            if($request->input('application_status') == 4){

                if(isset($requestData['scope_id']) && is_array($requestData['scope_id']) && count($requestData['scope_id']) > 0){
                    $application_scope_ids = (isset($requestData['application_scope_id']) && is_array($requestData['application_scope_id']))?$requestData['application_scope_id']:[];
                    $remarks = (isset($requestData['remark']) && is_array($requestData['remark']))?$requestData['remark']:[];
                    $application_scopes = ApplicationLabScope::find($requestData['scope_id']);

                    $check_audit_result = 0;
                    foreach($application_scopes as $application_scope){
                        $arr = [];
                        if(in_array($application_scope->id, $application_scope_ids)){
                            $arr['audit_result'] = 1;
                            $check_audit_result++;
                        }else{
                            $arr['audit_result'] = 2;
                        }
                        $arr['remark'] = array_key_exists($application_scope->id, $remarks)?$remarks[$application_scope->id]:null;
                        $application_scope->update($arr);
                    }

                    $date_arr = [];
                    $date_arr[] = date('Y-m-d');
                    $AuditData['created_by'] = auth()->user()->getKey();
                    $AuditData['audit_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
                    $AuditData['audit_remark'] = !empty($requestData['description'])?$requestData['description']:null;

                    if($check_audit_result >= 1){
                        $AuditData['audit_result'] = 1;
                    }else{
                        $AuditData['audit_result'] = 2;
                    }

                    $audit = ApplicationLabAudit::where('application_lab_id', $application->id )->first();
                    if( is_null($audit) ){
                        $AuditData['application_lab_id'] = $id;
                        $AuditData['application_no'] = @$application->application_no;
                        $audit = ApplicationLabAudit::create($AuditData);
                    }else{
                        $audit->update($AuditData);
                    }

                }

            }

            ApplicationLabAccept::create($requestData);

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                null,
                'section5/application_lab_accept',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ตรวจสอบคำขอ',
                'ระบบตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                'section5/application_lab_accept/'.$application->id.'/edit',
                auth()->user()->getKey(),
                3
            );

            //ส่งเมลแจ้งผปก.ผู้ยื่นคำขอ
            if(array_key_exists('send_mail_status', $requestData) && $requestData['send_mail_status']==1){

                //เมลผู้รับ
                $emails = array_key_exists('noti_email', $requestData) ? json_decode($requestData['noti_email']) : [] ;
                foreach ($emails as $key => $email) {
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        unset($emails[$key]);
                    }
                }

                if(count($emails) > 0){
                    $mail_format = new ApplicationLabAcceptMail([
                                    'applicant_name'=> $application->applicant_name,
                                    'lab_name'=> $application->lab_name,
                                    'application_status'=> $application->application_status,
                                    'application_no'=> $application->application_no,
                                    'application_date'=> HP::DateThaiFull($application->application_date),
                                    'description'=> $requestData['description'],
                                    'accept_date'=> HP::DateThaiFull($application->accept_date),
                                    'operation_date'     => HP::DateThaiFull(date('Y-m-d'))

                                ]);
                    Mail::to($emails)->send($mail_format);
                }
            }

            return redirect('section5/application_lab_accept')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');

        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationLab::findOrFail($id);
            $requestData = $request->all();

            $requestData['approve_date'] = date('Y-m-d');
            $requestData['approve_by'] = auth()->user()->getKey();
            $requestData['status_application'] = $requestData['approve_status'];

            $application->update($requestData);

            return redirect('section5/application_lab_accept')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');


        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function assing_data_update(Request $request){

        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $arr_publish = $request->input('id');
            $create_by = auth()->user()->getKey();

            $assignData['assign_by'] = json_encode($requestData['assign_by']);
            $assignData['assign_comment'] = !empty($requestData['assign_commen'])?$requestData['assign_commen']:null;
            $assignData['assign_date'] = date('Y-m-d H:i:s');

            $query = ApplicationLab::whereIn('id', $arr_publish);
            $applicationlabs = $query->get();
            $query->update($assignData);

            ApplicationLabStaff::whereIn('application_lab_id', $arr_publish)->delete();

            if(isset($requestData['assign_by'])){

                foreach($applicationlabs as $applicationlab){

                    foreach($requestData['assign_by'] as $assign_by){

                            $new_arr = [];
                            $new_arr['application_lab_id'] = @$applicationlab->id;
                            $new_arr['application_no'] = @$applicationlab->refno_application;
                            $new_arr['staff_id'] = @$assign_by;
                            $new_arr['assign_date'] = date('Y-m-d');
                            $new_arr['assign_comment'] = @$requestData['assign_commen'];
                            $new_arr['created_by'] = @$create_by;
                            $result = ApplicationLabStaff::create($new_arr);

                            HP::LogInsertNotification(
                                $applicationlab->id,
                                ( (new ApplicationLab)->getTable() ),
                                $applicationlab->application_no,
                                $applicationlab->application_status,
                                'ระบบตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                                null,
                                'section5/application_lab_accept',
                                $assign_by,
                                2
                            );

                    }

                }

            }

            if($result) {
                return 'success';
            } else {
                return "not success";
            }

        }
        abort(403);

    }

    public function print($id) {
        $model = str_slug('application-lab-accept','-');
        if(auth()->user()->can('view-'.$model)) {
            $applicationlab         = ApplicationLab::findOrFail($id);
            $application_labs_scope = ApplicationLabScope::where('application_lab_id', $id);
  
            $view = view('section5.application-request-form.pdf.application-lab',compact('applicationlab', 'application_labs_scope'));
            $mpdf = new Mpdf([
                                'format'            => 'A4',
                                'mode'              => 'UTF-8',
                                'default_font'      => 'thiasarabun',
                                'default_font_size' => '15',
                            ]);

            $mpdf->AddPageByArray([
                'orientation'   => 'P',
                'margin-left'   => "25",
                'margin-right'  => "20",
                'margin-top'    => "25",
                'margin-bottom' => "20",
            ]);

            $mpdf->use_kwt = true;
            $mpdf->SetDisplayMode('fullpage','continuous');
            $mpdf->shrink_tables_to_fit = 0;
            $mpdf->useFixedNormalLineHeight = false;
            $mpdf->useFixedTextBaseline = false;
            $mpdf->adjustFontDescLineheight = 1;
            $mpdf->text_input_as_HTML = true;
            $mpdf->allow_charset_conversion=true;
            $mpdf->autoLangToFont = true;
            $mpdf->useAdobeCJK = true;
            $mpdf->useSubstitutions = TRUE;
            $mpdf->list_indent_first_level = 0;
            
            $mpdf->WriteHTML($view, \Mpdf\HTMLParserMode::HTML_BODY);

            $filename = 'เลขที่คำขอ : '.$applicationlab->application_no;
            $mpdf->SetTitle( $filename );
            $mpdf->Output($filename.'.pdf', 'I');
            exit;
        }
        abort(403);   
    }
}
