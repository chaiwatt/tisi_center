<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use Mail;

use App\User;
use App\Models\Section5\ApplicationInspectorsStaff;
use App\Models\Section5\ApplicationInspector;
use App\Models\Section5\ApplicationInspectorScope;
use App\Models\Section5\ApplicationInspectorAudit;
use App\Models\Section5\ApplicationInspectorsAccept;
use App\Mail\Section5\ApplicationInspectorAcceptMail;
use App\Models\Tis\Standard;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\WorkGroupIBBranch;

class ApplicationInspectorsAcceptController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_inspectors_accept/';
        $this->attach_path_crop = 'tis_attach/application_inspectors_accept_crop/';
    }


    public function data_list(Request $request)
    {
        $model = str_slug('application-inspectors-accept','-');

        $filter_search       = $request->input('filter_search');
        $filter_status       = $request->input('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch       = $request->input('filter_branch');

        $filter_start_date        = $request->input('filter_start_date');
        $filter_end_date          = $request->input('filter_end_date');
        $filter_assign_start_date = $request->input('filter_assign_start_date');
        $filter_assign_end_date   = $request->input('filter_assign_end_date');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationInspector::query()->with([
                                                    'app_assign.staff',
                                                    'app_scope.bs_branch_group',
                                                    'inspector_status'
                                                ])
                                                ->when( $filter_search , function ($query, $filter_search){
                                                    $search_full = str_replace(' ', '', $filter_search);

                                                    if( strpos( $search_full , 'INS-' ) !== false){
                                                        return $query->where('application_no',  'LIKE', "%$search_full%");
                                                    }else{
                                                        return  $query->where(function ($query2) use($search_full) {

                                                                            $ids = ApplicationInspectorScope::where(function ($query) use($search_full) {
                                                                                                                $query->whereHas('bs_branch', function($query) use ($search_full){
                                                                                                                            $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                                        })
                                                                                                                        ->OrwhereHas('bs_branch_group', function($query) use ($search_full){
                                                                                                                            $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                                        })
                                                                                                                        ->OrwhereHas('scope_tis.standard', function($query) use ($search_full){
                                                                                                                            $query->where(function ($query) use($search_full) {
                                                                                                                                    $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                                                                });
                                                                                                                        }); 
                                                                                                            })->select('application_id');
                                                                                                            
                                                                            $query2->Where(DB::raw("REPLACE(applicant_full_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                    ->OrWhere(DB::raw("REPLACE(applicant_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                    ->OrWhere('application_no',  'LIKE', "%$search_full%")
                                                                                    ->OrwhereHas('app_assign.staff', function($query) use ($search_full){
                                                                                        $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                                    })
                                                                                    ->OrwhereIn('id', $ids);
                                                                        });
                                                    }
                                                })
                                                ->when( $filter_status , function ($query, $filter_status){
                                                    return $query->where('application_status', $filter_status );
                                                })
                                                ->when($filter_branch_group, function ($query, $filter_branch_group){
                                                    return  $query->whereHas('app_scope', function($query) use ($filter_branch_group){
                                                                $query->where('branch_group_id', $filter_branch_group);
                                                            });
                                                })
                                                ->when($filter_branch, function ($query, $filter_branch){
                                                    return  $query->whereHas('app_scope', function($query) use ($filter_branch){
                                                                $query->where('branch_id', $filter_branch);
                                                            });
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
                                                    return  $query->whereHas('app_assign', function($query) use ($filter_assign_start_date){
                                                                        $query->where('assign_date', '>=', $filter_assign_start_date);
                                                                    });
                                                })
                                                ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                                    $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                                    return  $query->whereHas('app_assign', function($query) use ($filter_assign_end_date){
                                                                        $query->where('assign_date', '<=', $filter_assign_end_date);
                                                                    });
                                                })
                                                ->when(!$user->isAdmin(), function($query) use ($user) {//ถ้าไม่ใช่ admin

                                                    //id ตาราง basic_branch_groups สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
                                                    $branch_group_ids = WorkGroupIB::UserBranchGroupIDs($user->getKey());

                                                    $id_query = ApplicationInspectorScope::whereIn('branch_group_id', $branch_group_ids)->select('application_id');
                                                    $query->whereIn('id', $id_query);
                                                })->when(( !auth()->user()->can('view_all-'.$model)) , function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                                    $id_query = ApplicationInspectorsStaff::where('staff_id', $user->getKey())->select('application_id');
                                                    $query->whereIn('id', $id_query);
                                                })
                                                ->where(function($query){
                                                    $query->whereNotIn('application_status', [12]);
                                                });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-app_no="'. $item->application_no .'" value="'. $item->id .'">';
                            })
                            ->addColumn('application_no', function ($item) {
                                return (!empty($item->application_no)?$item->application_no:'-').'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>';
                            })
                            ->addColumn('applicant_full_name', function ($item) {
                                return (!empty($item->applicant_full_name)?$item->applicant_full_name:'-').'<div>('.(!empty($item->applicant_taxid)?$item->applicant_taxid:'-').')</div>';
                            })
                            ->addColumn('standards', function ($item) {
                                return !empty($item->BranchGroupBranchName)?$item->BranchGroupBranchName:'-';
                            })
                            ->addColumn('application_status', function ($item) {
                                return (!empty($item->ApplicationStatusTitle)?$item->ApplicationStatusTitle:'-').(!empty($item->delete_at)?('<div><em>'.HP::DateThai($item->delete_at).'</em><div>'):null);;
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->ListStaff)?$item->ListStaff:'รอดำเนินการ');
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn =  ' <a href="'. url('section5/application_inspectors_accept/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('edit-'.$model) ){
                                    if(in_array($item->application_status, [1, 4])){
                                        $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/application_inspectors_accept/'.$item->id.'/edit') .'" data-toggle="tooltip" data-placement="top" title="พิจารณาคำขอ"><i class="fa fa-search" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" title="พิจารณาคำขอ" disabled><i class="fa fa-search" aria-hidden="true"></i></button>';
                                    }
                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by', 'application_status', 'standards','applicant_full_name','application_no'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_inspectors_accept",  "name" => 'ตรวจสอบคำขอผู้ตรวจ และผู้ประเมิน' ],
            ];
            return view('section5.application_inspectors_accept.index', compact('breadcrumbs'));

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
        $model = str_slug('application-inspectors-accept','-');
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
        $model = str_slug('application-inspectors-accept','-');
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
        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('view-'.$model)) {


            $applicationInspector = ApplicationInspector::with([
                                                                'inspectors_accepts' => function($query){
                                                                    $query->where('application_status', 4)->orderBy('id');
                                                                }
                                                            ])->findOrFail($id);
            $application_labs_scope = ApplicationInspectorScope::where('application_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

            $applicationInspector->edited  = true;

            $branch_scopes = ApplicationInspectorScope::where('application_id', $applicationInspector->id)
            ->leftjoin((new Branch)->getTable().' AS branch', 'branch.id', '=', 'section5_application_inspectors_scope.branch_id')
            ->selectRaw('section5_application_inspectors_scope.*, branch.title as branch_title')
            ->get()->keyBy('id')
            ->groupBy('branch_group_id')
            ->toArray();
            $branch_groups = BranchGroup::whereIn('id', ApplicationInspectorScope::where('application_id', $applicationInspector->id)->select('branch_group_id'))->pluck('title', 'id')->toArray();

            $app_configs_evidences = !empty($applicationInspector->configs_evidence)?json_decode($applicationInspector->configs_evidence):[];

            $application_inspectors_accept = ApplicationInspectorsAccept::where('application_id', $applicationInspector->id)->orderByDesc('id')->first();
            $applicationInspector->show  = true;

            $inspectors_accepts = ApplicationInspectorsAccept::where('application_id', $applicationInspector->id)->get();

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_inspectors_accept",  "name" => 'ตรวจสอบคำขอผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application_inspectors_accept/$id",  "name" => 'รายละเอียด' ],

            ];

            return view('section5/application_inspectors_accept.show', compact('applicationInspector', 'application_labs_scope_groups', 'app_configs_evidences', 'branch_scopes', 'branch_groups', 'application_inspectors_accept', 'inspectors_accepts','breadcrumbs'));

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
        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationInspector = ApplicationInspector::with([
                                                                'inspectors_accepts' => function($query){
                                                                    $query->where('application_status', 4)->orderBy('id');
                                                                }
                                                            ])
                                                            ->findOrFail($id);


            $application_labs_scope = ApplicationInspectorScope::where('application_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

            $applicationInspector->edited  = true;

            $branch_scopes = ApplicationInspectorScope::where('application_id', $applicationInspector->id)
                                                        ->leftjoin((new Branch)->getTable().' AS branch', 'branch.id', '=', 'section5_application_inspectors_scope.branch_id')
                                                        ->selectRaw('section5_application_inspectors_scope.*, branch.title as branch_title')
                                                        ->get()->keyBy('id')
                                                        ->groupBy('branch_group_id')
                                                        ->toArray();
            $branch_groups = BranchGroup::whereIn('id', ApplicationInspectorScope::where('application_id', $applicationInspector->id)->select('branch_group_id'))->pluck('title', 'id')->toArray();

            $app_configs_evidences = !empty($applicationInspector->configs_evidence)?json_decode($applicationInspector->configs_evidence):[];

            $inspectors_accepts = ApplicationInspectorsAccept::where('application_id', $applicationInspector->id)->get();


            return view('section5/application_inspectors_accept.edit', compact('applicationInspector', 'application_labs_scope_groups', 'app_configs_evidences', 'branch_scopes', 'branch_groups', 'inspectors_accepts'));

        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationInspector = ApplicationInspector::findOrFail($id);
            $application_labs_scope = ApplicationInspectorScope::where('application_lab_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');
            $standards = Standard::selectRaw('id, CONCAT_WS(" : ", CONCAT_WS(" - ", tis_year, tis_no), title) AS standard_title')->whereIn('id', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();

            $applicationInspector->approve  = true;

            return view('section5/application_inspectors_accept.approve', compact('applicationInspector', 'application_labs_scope_groups', 'standards'));
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
        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationInspector::findOrFail($id);
            $requestData = $request->all();

            if(empty($application->accept_by)){ //ถ้ายังไม่มีข้อมูลผู้รับคำขอ
                if(in_array($requestData['application_status'], [4, 6])){ //สถานะเป็น 4=เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน, 6=อยู่ระหว่างการพิจารณาอนุมัติ
                    $application->update([ 'accept_date' => date('Y-m-d'), 'accept_by' =>   auth()->user()->getKey() ]);
                }
            }

            $application->update($requestData);

            $requestData['application_id'] = $id;
            $requestData['application_no'] = @$application->application_no;
            if(!empty($request->input('noti_email'))){
                $requestData['noti_email'] = json_encode(explode(',', $request->input('noti_email')));
            }
            $requestData['created_by'] = auth()->user()->getKey();

            ApplicationInspectorsAccept::create($requestData);

            //อยู่ระหว่างการพิจารณาอนุมัติ
            if($application->application_status==6){

                $scope_group = ApplicationInspectorScope::where('application_id', $application->id)->select('branch_group_id')->groupBy('branch_group_id')->get();

                foreach($scope_group as $key => $group){

                    if(isset($requestData['repeater-group-'.$group->branch_group_id])){

                        $repeater_group = $requestData['repeater-group-'.$group->branch_group_id];
                        $remark = isset($requestData['remark'][$group->branch_group_id])?$requestData['remark'][$group->branch_group_id]:null;

                        foreach(  $repeater_group as $repeater ){

                            $audit_result = isset($repeater['audit_result'])?1:2;

                            $scope = ApplicationInspectorScope::Where('id', $repeater['scope_id'] )->first();

                            if( !is_null( $scope) ){
                                $scope->audit_result = $audit_result;
                                $scope->remark = $remark;
                                $scope->save();
                            }

                        }

                    }
                }

                //เพิ่ม/อัพเดทตารางผลตรวจประเมิน
                $audit = ApplicationInspectorAudit::where('application_id', $application->id)->first();

                if( is_null($audit) ){
                    $audit = new ApplicationInspectorAudit;
                    $audit->created_by = auth()->user()->getKey();
                }else{
                    $audit->updated_by = auth()->user()->getKey();
                    $audit->updated_at = date('Y-m-d H:i:s');
                }
                $audit->application_id = $application->id;
                $audit->application_no = $application->application_no;

                $audit->audit_date   = date('Y-m-d');
                $audit->audit_result = 1;
                if(array_key_exists('noti_email', $requestData)){
                    $audit->noti_email = $requestData['noti_email'];
                }
                $audit->save();

            }

            //ส่งเมลแจ้งผปก.ผู้ยื่นคำขอ
            if(array_key_exists('send_mail_status', $requestData) && $requestData['send_mail_status']==1 && in_array($application->application_status, [2, 3, 4, 6])){
                //เมลผู้รับ
                $emails = array_key_exists('noti_email', $requestData) ? json_decode($requestData['noti_email']) : [] ;
                foreach ($emails as $key => $email) {
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        unset($emails[$key]);
                    }
                }

                if(count($emails) > 0){
                    $mail_format = new ApplicationInspectorAcceptMail([
                                    'applicant_name'     => $application->applicant_full_name,
                                    'application_status' => $application->application_status,
                                    'application_no'     => $application->application_no,
                                    'application_date'   => HP::DateThaiFull($application->application_date),
                                    'description'        => $requestData['description'],
                                    'accept_date'        => HP::DateThaiFull($application->accept_date),
                                    'operation_date'     => HP::DateThaiFull(date('Y-m-d'))
                                ]);

                    Mail::to($emails)->send($mail_format);
                }
            }

            return redirect('section5/application_inspectors_accept')->with('message', 'บันทึก เรียบร้อยแล้ว!');

        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationInspector::findOrFail($id);
            $requestData = $request->all();

            $requestData['approve_date'] = date('Y-m-d');
            $requestData['approve_by'] = auth()->user()->getKey();
            $requestData['application_status'] = $requestData['approve_status'];

            $application->update($requestData);

            return redirect('section5/application_inspectors_accept')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');


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
        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function assing_data_update(Request $request){

        $model = str_slug('application-inspectors-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();
            $arr_publish = $request->input('id');
            $create_by = auth()->user()->getKey();

            $application_inspectors = ApplicationInspector::find($arr_publish);

            ApplicationInspectorsStaff::whereIn('application_id', $arr_publish)->delete();

            if(isset($requestData['assign_by'])){

                foreach($application_inspectors as $application_inspector){

                    foreach($requestData['assign_by'] as $assign_by){

                            $new_arr = [];
                            $new_arr['application_id'] = @$application_inspector->id;
                            $new_arr['application_no'] = @$application_inspector->application_no;
                            $new_arr['staff_id'] = @$assign_by;
                            $new_arr['created_by'] = @$create_by;
                            $new_arr['assign_date'] = date('Y-m-d');
                            $result = ApplicationInspectorsStaff::create($new_arr);

                            HP::LogInsertNotification(
                                $application_inspector->id,
                                ( (new ApplicationInspector)->getTable() ),
                                $application_inspector->application_no,
                                $application_inspector->application_status,
                                'ระบบตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม',
                                null,
                                'section5/application_inspectors_accept',
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


}
