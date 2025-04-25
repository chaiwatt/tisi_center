<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Section5\InspectorsScopeTis;
use App\Models\Section5\ApplicationInspector;
use App\Models\Section5\ApplicationInspectorAudit;
use App\Models\Section5\ApplicationInspectorScope;
use App\Models\Section5\ApplicationInspectorsStaff;
use App\Models\Section5\ApplicationInspectorsAccept;
use App\Models\Section5\ApplicationLab;

use App\Models\Section5\Inspectors;
use App\Models\Section5\InspectorsScope;
use App\Models\Basic\Branch;
use App\Models\Basic\BranchGroup;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\WorkGroupIBBranch;


use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Mail\Section5\ApplicationInspectorsAuditMail;
use Illuminate\Support\Facades\Mail;

class ApplicationInspectorsAuditController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_inspectors_audit/';
        $this->attach_path_crop = 'tis_attach/application_inspectors_audit_crop/';
    }

    public function data_list(Request $request)
    {

        $model = str_slug('application-inspectors-audit','-');

        $can_edit         = auth()->user()->can('edit-'.$model);
        $can_poko_approve = auth()->user()->can('poko_approve-'.$model);
        $can_view         = auth()->user()->can('view-'.$model);

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch = $request->input('filter_branch');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_audit_result  = $request->input('filter_audit_result');
        $filter_audit_approve = $request->input('filter_audit_approve');

        $filter_audit_start_date  = $request->input('filter_audit_start_date');
        $filter_audit_end_date    = $request->input('filter_audit_end_date');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationInspector::query()->when($filter_search, function ($query, $filter_search){
                                                        $search_full = str_replace(' ', '', $filter_search);

                                                        if( strpos( $search_full , 'INS-' ) !== false){
                                                            $query->where('application_no', 'LIKE', "%$search_full%");
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
                                                                                        ->OrwhereHas('app_assign.staff', function($query) use ($search_full){
                                                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                                        })
                                                                                        ->OrwhereIn('id', $ids)
                                                                                        ->OrWhere('application_no',  'LIKE', "%$search_full%");
                                                                            });
                                                        }

                                                    })
                                                    ->when($filter_branch_group, function ($query, $filter_branch_group){
                                                        $query->whereHas('app_scope', function($query) use ($filter_branch_group){
                                                            $query->where('branch_group_id', $filter_branch_group);
                                                        });
                                                    })
                                                    ->when($filter_branch, function ($query, $filter_branch){
                                                        $query->whereHas('app_scope', function($query) use ($filter_branch){
                                                            $query->where('branch_id', $filter_branch);
                                                        });
                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        $query->where('application_status', $filter_status);
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
                                                    ->when($filter_audit_result, function ($query, $filter_audit_result){

                                                        if( $filter_audit_result == '-1'){
                                                            return $query->Has('inspector_audit','==',0);
                                                        }else{
                                                            $query->whereHas('inspector_audit', function($query) use ($filter_audit_result){
                                                                $query->where('audit_result', $filter_audit_result);
                                                            });
                                                        }

                                                    })
                                                    ->when($filter_audit_approve, function ($query, $filter_audit_approve){
                                                        return  $query->whereHas('inspector_audit', function($query) use ($filter_audit_approve){
                                                                        $query->where('audit_approve', $filter_audit_approve);
                                                                    });
                                                    })
                                                    ->when($filter_audit_start_date, function ($query, $filter_audit_start_date){
                                                        $filter_audit_start_date = HP::convertDate($filter_audit_start_date, true);
                                                        return  $query->whereHas('inspector_audit', function($query) use ($filter_audit_start_date){
                                                                            $query->where('audit_date', '>=', $filter_audit_start_date);
                                                                        });
                                                    })
                                                    ->when($filter_audit_end_date, function ($query, $filter_audit_end_date){
                                                        $filter_audit_end_date = HP::convertDate($filter_audit_end_date, true);
                                                        return  $query->whereHas('inspector_audit', function($query) use ($filter_audit_end_date){
                                                                            $query->where('audit_date', '<=', $filter_audit_end_date);
                                                                        });
                                                    })
                                                    ->when(!$user->isAdmin(), function($query) use ($user) {//ถ้าไม่ใช่ admin

                                                        //id ตาราง basic_branch_groups สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
                                                        $branch_group_ids = WorkGroupIB::UserBranchGroupIDs($user->getKey());

                                                        $id_query         = ApplicationInspectorScope::whereIn('branch_group_id', $branch_group_ids)->select('application_id');
                                                        $query->whereIn('id', $id_query);
                                                    })->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                                        $id_query = ApplicationInspectorsStaff::where('staff_id', $user->getKey())->select('application_id');
                                                        $query->whereIn('id', $id_query);
                                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox"
                                                name="item_checkbox[]"
                                                class="item_checkbox"
                                                data-application_no="'. $item->application_no .'"
                                                data-applicant_name="'. $item->applicant_full_name .'"
                                                data-applicant_taxid="'. $item->applicant_taxid .'"
                                                data-application_status="'. $item->application_status .'"
                                                value="'. $item->id .'">';
                            })
                            ->addColumn('refno_application', function ($item) {
                                return (!empty($item->application_no)?$item->application_no:'-').'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>';
                            })
                            ->addColumn('authorized_name', function ($item) {
                                return (!empty($item->applicant_full_name)?$item->applicant_full_name:'-').'<div>('.(!empty($item->applicant_taxid)?$item->applicant_taxid:'-').')</div>';
                            })
                            ->addColumn('scope', function ($item) {
                                return @$item->BranchGroupBranchName;
                            })
                            ->addColumn('audit_result', function ($item) {
                                $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                                $inspector_audit = $item->inspector_audit;
                                return (!empty($inspector_audit->audit_result)  &&  array_key_exists( $inspector_audit->audit_result,  $arr ) ? $arr[$inspector_audit->audit_result]:'รอดำเนินการ').'<div>'.(!empty($inspector_audit->audit_date)?'('.HP::DateThai($inspector_audit->audit_date).')':null).'</div>';
                            })
                            ->addColumn('audit_approve', function ($item) {
                                $inspector_audit = $item->inspector_audit;
                                return (!empty($inspector_audit->AuditApproveStatusTitle)?$inspector_audit->AuditApproveStatusTitle:'รอดำเนินการ').'<div>'.(!empty($inspector_audit->audit_approve_at)?'('.HP::DateThai($inspector_audit->audit_approve_at).')':null).'</div>';
                            })
                            ->addColumn('status_application', function ($item) {
                                $inspector_status = $item->inspector_status;
                                return !empty($inspector_status->title)?$inspector_status->title:'-';
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->ListStaff)?$item->ListStaff:'รอดำเนินการ');
                            })
                            ->addColumn('action', function ($item) use ($can_view, $can_edit, $can_poko_approve) {

                                $btn = '';

                                if($can_view){
                                    $btn =  ' <a href="'. url('section5/application-inspectors-audit/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if($can_edit){

                                    if(in_array($item->application_status, [4,5,6,7])){
                                        $btn .= ' <a  class="btn btn-success btn-xs waves-effect waves-light" href="'. url('section5/application-inspectors-audit/checkings/'.$item->id) .'" data-toggle="tooltip" data-placement="top" title="บันทึกผลตรวจประเมิน"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-success btn-xs waves-effect waves-light"  title="บันทึกผลตรวจประเมิน" disabled><i class="fa fa-check-square-o" aria-hidden="true"></i></button> ';
                                    }

                                    if($can_poko_approve){
                                        if(in_array($item->application_status, [6, 7, 8])){
                                            $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light" href="'. url('section5/application-inspectors-audit/approve/'.$item->id) .'" data-toggle="tooltip" data-placement="top" title="อนุมัติผลตรวจประเมิน"><i class="icon-note" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= ' <button type="button" class="btn btn-warning btn-xs waves-effect waves-light"  title="อนุมัติผลตรวจประเมิน" disabled><i class="icon-note" aria-hidden="true"></i></button> ';
                                        }
                                    }
                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'scope', 'action', 'assign_by','authorized_name','refno_application','audit_approve','audit_result'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-audit",  "name" => 'บันทึกผลตรวจผู้ตรวจ และผู้ประเมิน' ],
            ];
            return view('section5.application-inspectors-audit.index',compact('breadcrumbs'));
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
        $model = str_slug('application-inspectors-audit','-');
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
        $model = str_slug('application-inspectors-audit','-');
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
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('view-'.$model)) {

            $application_inspectors = ApplicationInspector::findOrFail($id);
            $application_inspectors->show = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-audit",  "name" => 'บันทึกผลตรวจผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-audit/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('section5/application-inspectors-audit.show', compact('application_inspectors','breadcrumbs'));

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
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

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
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

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
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function checkings($id)
    {
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application_inspectors = ApplicationInspector::with([
                                                                    'app_scope' => function($query){
                                                                        $query->with(['bs_branch', 'bs_branch_group'])
                                                                                ->select('application_id', 'branch_id', 'branch_group_id');
                                                                    },
                                                                    'agency_subdistricts',
                                                                    'agency_districts',
                                                                    'agency_provinces',
                                                                    'attach_files'
                                                                ])->findOrFail($id);
            $application_inspectors->checkings = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-audit",  "name" => 'บันทึกผลตรวจผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-audit/checkings/$id",  "name" => 'ผลตรวจประเมิน' ],

            ];
            return view('section5.application-inspectors-audit.checkings',compact('application_inspectors','breadcrumbs'));

        }
        abort(403);
    }

    public function checkings_save(Request $request, $id)
    {
        $application_inspectors = ApplicationInspector::findOrFail($id);

        $requestData = $request->all();

        $audit = ApplicationInspectorAudit::where('application_id', $application_inspectors->id )->first();

        if( is_null($audit) ){
            $audit = new ApplicationInspectorAudit;
            $audit->created_by = auth()->user()->getKey();
        }else{
            $audit->updated_by = auth()->user()->getKey();
            $audit->updated_at = date('Y-m-d H:i:s');
        }
        $audit->application_id = $application_inspectors->id;
        $audit->application_no = $application_inspectors->application_no;

        $audit->audit_date =  !empty($requestData['audit_date'])?HP::convertDate($requestData['audit_date'], true):null;
        $audit->audit_result = !empty($requestData['audit_result'])?$requestData['audit_result']:null;
        $audit->audit_remark = !empty($requestData['audit_remark'])?$requestData['audit_remark']:null;
        if(!empty($request->input('noti_email'))){
            $audit->noti_email = json_encode(explode(',', $request->input('noti_email')));
        }
        $audit->save();

        if($request->input('submit_type') == 1){
            if(  $audit->audit_result  == 1 ){
                $application_inspectors->update(['application_status' => 6]);
            }else{
                $application_inspectors->update(['application_status' => 5]);
            }
        }

        if( !is_null( $audit ) ){

            $tax_number = !empty($application_inspectors->applicant_taxid )?$application_inspectors->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            $folder_app = ($application_inspectors->application_no);

            if(isset($requestData['audit_file'])){
                if ($request->hasFile('audit_file')) {
                    HP::singleFileUpload(
                        $request->file('audit_file') ,
                        $this->attach_path.$folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationInspectorAudit)->getTable() ),
                        $audit->id,
                        'file_application_inspectors_audit',
                        'เอกสารการตรวจประเมิน'
                    );
                }
            }

        }

        $scope_group = ApplicationInspectorScope::where('application_id', $application_inspectors->id )->select('branch_group_id')->groupBy('branch_group_id')->get();

        foreach( $scope_group as $key => $group ){
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

        HP::LogInsertNotification(
            $application_inspectors->id ,
            ( (new ApplicationInspector)->getTable() ),
            $application_inspectors->application_no,
            $application_inspectors->application_status,
            'อนุมัติผลตรวจประเมิน (IB)',
            null,
            'section5/application-inspectors-audit',
            $application_inspectors->created_by,
            1
        );

        HP::LogInsertNotification(
            $application_inspectors->id ,
            ( (new ApplicationInspector)->getTable() ),
            $application_inspectors->application_no,
            $application_inspectors->application_status,
            'บันทึกผลตรวจประเมิน',
            'อนุมัติผลตรวจประเมิน (IB)',
            'section5/application-inspectors-audit/checkings/'.$application_inspectors->id,
            auth()->user()->getKey(),
            4
        );

        return redirect('section5/application-inspectors-audit/checkings/'.$application_inspectors->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');

    }

    public function approve($id)
    {
        $model = str_slug('application-inspectors-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application_inspectors = ApplicationInspector::findOrFail($id);
            $application_inspectors->approve = true;
            // $application_inspectors->checkings = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-audit",  "name" => 'บันทึกผลตรวจผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-audit/approve/$id",  "name" => 'พิจารณาอนุมัติ' ],

            ];
            return view('section5.application-inspectors-audit.approve', compact('application_inspectors','breadcrumbs'));
        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $requestData = $request->all();

        $application_inspectors = ApplicationInspector::findOrFail($id);
        $audit = ApplicationInspectorAudit::where('application_id', $application_inspectors->id )->first();

        if( !is_null($audit) ){

            if( !empty($audit->audit_approve) ){
                $audit->audit_updated_by = auth()->user()->getKey();
                $audit->audit_updated_at = date('Y-m-d H:i:s');
            }else{
                $audit->audit_approve_by = auth()->user()->getKey();
                $audit->audit_approve_at = date('Y-m-d H:i:s');
            }
            $audit->audit_approve = !empty($requestData['audit_approve'])?$requestData['audit_approve']:null;
            $audit->audit_approve_description = !empty($requestData['audit_approve_description'])?$requestData['audit_approve_description']:null;

            //Mail Status
            $audit->approve_noti_email =  !empty($requestData['approve_send_mail_status'])?$requestData['approve_send_mail_status']:null;
            if(!empty($request->input('approve_noti_email'))){
                $audit->approve_noti_email = json_encode(explode(',', $request->input('approve_noti_email')));
                $requestData['approve_noti_email'] = json_encode(explode(',', $request->input('approve_noti_email')));
            }

            $audit->save();

            if( $audit->audit_approve  == 8 ){
                $application_inspectors->update(['application_status' => 8]);

                $this->GenInspector( $application_inspectors , $audit);

            }else{
                $application_inspectors->update(['application_status' => 7]);
            }

            //ส่งเมลแจ้งผปก.ผู้ยื่นคำขอ
            if(array_key_exists('approve_send_mail_status', $requestData) && $requestData['approve_send_mail_status']==1 && in_array($application_inspectors->application_status, [7,8])){
                //เมลผู้รับ
                $emails = array_key_exists('approve_noti_email', $requestData) ? json_decode($requestData['approve_noti_email']) : [] ;
                foreach ($emails as $key => $email) {
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        unset($emails[$key]);
                    }
                }

                if(count($emails) > 0){
                    $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                    $mail_format = new ApplicationInspectorsAuditMail([
                                                                        'applicant_name'     => $application_inspectors->applicant_full_name,
                                                                        'application_no'     => $application_inspectors->application_no,
                                                                        'audit_date'         => HP::DateThaiFull($audit->audit_date),
                                                                        'audit_result'       => (!empty($audit->audit_result)  &&  array_key_exists( $audit->audit_result,  $arr ) ? $arr[$audit->audit_result]:'รอดำเนินการ'),
                                                                        'audit_remark'       => (!empty($audit->audit_approve_description)?$audit->audit_approve_description:'-'),
                                                                    ]);

                    Mail::to($emails)->send($mail_format);
                }
            }

            HP::LogInsertNotification(
                $application_inspectors->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application_inspectors->application_no,
                $application_inspectors->application_status,
                'อนุมัติผลตรวจประเมิน (IB)',
                null,
                'section5/application-inspectors-audit',
                $application_inspectors->created_by,
                1
            );

            HP::LogInsertNotification(
                $application_inspectors->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application_inspectors->application_no,
                $application_inspectors->application_status,
                'บันทึกพิจารณาอนุมัติ',
                'อนุมัติผลตรวจประเมิน (IB)',
                'section5/application-inspectors-audit/approve/'.$application_inspectors->id,
                auth()->user()->getKey(),
                3
            );

        }

        return redirect('section5/application-inspectors-audit/approve/'.$application_inspectors->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');


    }

    public function GenInspector( $application , $approve )
    {

        $start_date = null;
        $end_date = null;
        // !empty($approve->audit_approve_at)?$approve->audit_approve_at:null;
        //  !empty($approve->lab_end_date)?$approve->lab_end_date:null;

        $inspectors = Inspectors::where( 'inspectors_taxid', $application->applicant_taxid )->first();

        if( is_null($inspectors) ){

            $running_no = $this->GenNemberLabCode();
            $check = Inspectors::where('inspectors_code', $running_no )->first();
            if(!empty($check)){
                $running_no = $this->GenNemberLabCode();
            }
            $inspectors = new Inspectors;
            $inspectors->inspectors_code = $running_no;
            $inspectors->state = 1;
            $inspectors->application_id = $application->id;
            $inspectors->ref_inspector_application_no = $application->application_no;
            $inspectors->inspector_first_date = $start_date;
            $inspectors->created_by = auth()->user()->getKey();

            $inspectors->inspectors_prefix = !empty($application->applicant_prefix)?$application->applicant_prefix:null;
            $inspectors->inspectors_first_name = !empty($application->applicant_first_name)?$application->applicant_first_name:null;
            $inspectors->inspectors_last_name = !empty($application->applicant_last_name)?$application->applicant_last_name:null;
            $inspectors->inspectors_taxid = !empty($application->applicant_taxid)?$application->applicant_taxid:null;

            //ที่อยู่
            $inspectors->inspectors_address = !empty($application->applicant_address)?$application->applicant_address:null;
            $inspectors->inspectors_moo = !empty($application->applicant_moo)?$application->applicant_moo:null;
            $inspectors->inspectors_soi = !empty($application->applicant_soi)?$application->applicant_soi:null;
            $inspectors->inspectors_road = !empty($application->applicant_road)?$application->applicant_road:null;
            $inspectors->inspectors_subdistrict = !empty($application->applicant_subdistrict)?$application->applicant_subdistrict:null;
            $inspectors->inspectors_district = !empty($application->applicant_district)?$application->applicant_district:null;
            $inspectors->inspectors_province = !empty($application->applicant_province)?$application->applicant_province:null;
            $inspectors->inspectors_zipcode = !empty($application->applicant_zipcode)?$application->applicant_zipcode:null;

            $inspectors->inspectors_position = !empty($application->applicant_position)?$application->applicant_position:null;
            $inspectors->inspectors_phone = !empty($application->applicant_phone)?$application->applicant_phone:null;
            $inspectors->inspectors_fax = !empty($application->applicant_fax)?$application->applicant_fax:null;
            $inspectors->inspectors_mobile = !empty($application->applicant_mobile)?$application->applicant_mobile:null;
            $inspectors->inspectors_email = !empty($application->applicant_email)?$application->applicant_email:null;

            // Agency
            $inspectors->agency_id = !empty($application->agency_id)?$application->agency_id:null;
            $inspectors->agency_name = !empty($application->agency_name)?$application->agency_name:null;
            $inspectors->agency_taxid = !empty($application->agency_taxid)?$application->agency_taxid:null;
            $inspectors->agency_address = !empty($application->agency_address)?$application->agency_address:null;
            $inspectors->agency_moo = !empty($application->agency_moo)?$application->agency_moo:null;
            $inspectors->agency_soi = !empty($application->agency_soi)?$application->agency_soi:null;
            $inspectors->agency_road = !empty($application->agency_road)?$application->agency_road:null;
            $inspectors->agency_subdistrict = !empty($application->agency_subdistrict)?$application->agency_subdistrict:null;
            $inspectors->agency_district = !empty($application->agency_district)?$application->agency_district:null;
            $inspectors->agency_province = !empty($application->agency_province)?$application->agency_province:null;
            $inspectors->agency_zipcode = !empty($application->agency_zipcode)?$application->agency_zipcode:null;

            $inspectors->save();

        }

        //บันทึกขอบข่ายที่ผ่านการประเมิน
        $scope_app = ApplicationInspectorScope::where('application_id', $application->id )->where('audit_result', 1)->orderBy('branch_group_id')->get();
        foreach( $scope_app AS $item ){

            $scope = InspectorsScope::where('inspectors_id', $inspectors->id)
                                    ->where('ref_inspector_application_no', $application->application_no)
                                    ->where('branch_group_id', $item->branch_group_id)
                                    ->where('branch_id', $item->branch_id)
                                    ->first();
            if(is_null($scope)){
                $scope = new InspectorsScope;
            }

            $scope->inspectors_id   = $inspectors->id;
            $scope->inspectors_code = $inspectors->inspectors_code;
            $scope->application_id  = $application->id;
            $scope->ref_inspector_application_no = $application->application_no;
            $scope->agency_id       = $application->agency_id;
            $scope->agency_taxid    = $application->agency_taxid;
            $scope->created_by      = auth()->user()->getKey();

            $scope->branch_id = !empty($item->branch_id)?$item->branch_id:null;
            $scope->branch_group_id = !empty($item->branch_group_id)?$item->branch_group_id:null;
            $scope->state = 1;
            $scope->start_date =  $start_date;
            $scope->end_date =  $end_date;
            $scope->save();

            //มอก.ตามรายสาขาที่อยู่ในใบสมัคร
            foreach ($item->scope_tis as $scope_tis) {

                $standard = $scope_tis->standard;

                if(!is_null($standard)){//ถ้ามีมอก.

                    $inspector_tis = InspectorsScopeTis::where('inspector_scope_id', $scope->id)
                                                        ->where('tis_id', $standard->id)
                                                        ->first();
                    if(is_null($inspector_tis)){
                        $inspector_tis = new InspectorsScopeTis;
                    }

                    $inspector_tis->inspector_scope_id = $scope->id ;
                    $inspector_tis->inspectors_code    = $inspectors->inspectors_code;
                    $inspector_tis->tis_id             = $standard->getKey();
                    $inspector_tis->tis_no             = $standard->tb3_Tisno;
                    $inspector_tis->tis_name           = $standard->tb3_TisThainame;
                    $inspector_tis->state              = 1;
                    $inspector_tis->save();
                }

            }

        }


    }

    public static function GenNemberLabCode(){

        $Type = 'INS-';
        $new_run = null;
        $list_code = Inspectors::select('inspectors_code')->where('inspectors_code',  'LIKE', "%$Type%")->orderBy('inspectors_code')->pluck('inspectors_code')->toArray();

        usort($list_code, function($x, $y) {
            return $x > $y;
        });

        $last = end($list_code);

        $number = 0;
        if( count($list_code) > 0 ){

            $cut = explode('-', $last );
            $number = (int)$cut[1];
            $Seq = substr("0000".((string)$number + 1),-4,4);
            $new_run = $Type.$Seq;

            $check = Inspectors::where('inspectors_code', $new_run )->first();
            if(!empty($check)){
                $number = (int)$cut[1];
                $Seq = substr("0000".((string)$number + 2),-4,4);
                $new_run = $Type.$Seq;
            }

        }else{
            $Seq = substr("0000".((string)$number + 1),-4,4);
            $new_run = $Type.$Seq;
        }

        return $new_run;
    }


    public function GetdataApplication(Request $request)
    {
        $requestData = $request->all();
        $application = [];
        if(array_key_exists('id', $requestData)){
            $ids = explode(',',$requestData['id']);
            $application = ApplicationInspector::whereIn('id', $ids)->get();
        }
        return view('section5.application-inspectors-audit.modals.table',compact('application'));
    }

    public function update_application_checkings(Request $request)
    {
        $requestData = $request->all();
        $msg = 'error';
        if(array_key_exists('id', $requestData)){

            $ids = $requestData['id'];

            $application = ApplicationInspector::whereIn('id', $ids)->get();

            foreach( $application  AS $application_inspectors ){

                $audit = ApplicationInspectorAudit::where('application_id', $application_inspectors->id )->first();

                if( is_null($audit) ){
                    $audit = new ApplicationInspectorAudit;
                    $audit->created_by = auth()->user()->getKey();
                }else{
                    $audit->updated_by = auth()->user()->getKey();
                    $audit->updated_at = date('Y-m-d H:i:s');
                }
                $audit->application_id = $application_inspectors->id;
                $audit->application_no = $application_inspectors->application_no;
                $audit->audit_date     = !empty($requestData['m_audit_date'])?HP::convertDate($requestData['m_audit_date'], true):null;
                $audit->audit_result   = !empty($requestData['m_audit_result'])?$requestData['m_audit_result']:null;
                $audit->audit_remark   = !empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null;
                $audit->save();

                if(  $audit->audit_result  == 1 ){

                    if(isset($requestData['scope_id']) && is_array($requestData['scope_id']) && count($requestData['scope_id']) > 0){

                        $scope_ids = (isset($requestData['scope_id']) && is_array($requestData['scope_id']))?$requestData['scope_id']:[];
                        $scopes = ApplicationInspectorScope::find($requestData['scope_id']);
                        foreach( $scopes as $scope ){
                            $arr = [];
                            if(in_array($scope->id, $scope_ids)){
                                $arr['audit_result'] = 1;
                            }else{
                                $arr['audit_result'] = 2;
                            }
                            $arr['remark'] = !empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null;
                            $scope->update($arr);
                        }
                    }

                    $application_inspectors->update(['application_status' => 6]);

                }else{
                    ApplicationInspectorScope::where('application_id', $application_inspectors->id)->update(['audit_result' => (!empty( $requestData['m_audit_result'] )?$requestData['m_audit_result']:null), 'remark' => (!empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null)  ]);
                    $application_inspectors->update(['application_status' => 5]);
                }

                $tax_number = !empty($application_inspectors->applicant_taxid )?$application_inspectors->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                $folder_app = ($application_inspectors->application_no);

                if(isset($requestData['m_audit_file'])){
                    if ($request->hasFile('m_audit_file')) {
                        HP::singleFileUpload(
                            $request->file('m_audit_file') ,
                            $this->attach_path.$folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationInspectorAudit)->getTable() ),
                            $audit->id,
                            'file_application_inspectors_audit',
                            'เอกสารการตรวจประเมิน'
                        );
                    }
                }

            }
            $msg = 'success';
        }
        return response()->json(['msg' => $msg ]);
    }

    public function update_application_approve(Request $request)
    {
        $requestData = $request->all();

        $msg = 'error';

        if(array_key_exists('id', $requestData)){

            $ids = $requestData['id'];

            $application = ApplicationInspector::whereIn('id', $ids)->get();

            foreach( $application  AS $application_inspectors ){
                $audit = ApplicationInspectorAudit::where('application_id', $application_inspectors->id )->first();
                if( !is_null($audit) ){
                    if( !empty($audit->audit_approve) ){
                        $audit->audit_updated_by = auth()->user()->getKey();
                        $audit->audit_updated_at = date('Y-m-d H:i:s');
                    }else{
                        $audit->audit_approve_by = auth()->user()->getKey();
                        $audit->audit_approve_at = date('Y-m-d H:i:s');
                    }
                    $audit->audit_approve = !empty($requestData['audit_approve'])?$requestData['audit_approve']:null;
                    $audit->audit_approve_description = !empty($requestData['audit_approve_description'])?$requestData['audit_approve_description']:null;

                    $audit->save();

                    if( $audit->audit_approve  == 8 ){
                        $application_inspectors->update(['application_status' => 8]);

                        $this->GenInspector( $application_inspectors , $audit);

                    }else{
                        $application_inspectors->update(['application_status' => 7]);
                    }

                }
            }

            $msg = 'success';
        }
        return response()->json(['msg' => $msg ]);
    }
}
