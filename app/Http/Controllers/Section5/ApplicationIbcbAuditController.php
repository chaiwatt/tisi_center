<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Section5\ApplicationIbcb;
use App\Models\Section5\ApplicationIbcbCertify;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\ApplicationIbcbScopeDetail;
use App\Models\Section5\ApplicationIbcbInspectors;
use App\Models\Section5\ApplicationIbcbInspectorsScope;
use App\Models\Section5\ApplicationIbcbAudit;
use App\Models\Section5\ApplicationIbcbStaff;
use App\Models\Section5\ApplicationIbcbReport;
use App\Models\Bsection5\WorkGroupIB;

use App\Mail\Section5\ApplicationIBCBAuditMail;
use Illuminate\Support\Facades\Mail;

class ApplicationIbcbAuditController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_ibcb_audit/';
        $this->attach_path_crop = 'tis_attach/application_ibcb_audit_crop/';
    }

    public function data_list(Request $request)
    {

        $model = str_slug('application-ibcb-audit','-');

        $can_edit         = auth()->user()->can('edit-'.$model);
        $can_poko_approve = auth()->user()->can('poko_approve-'.$model);

        $filter_search =  $request->get('filter_search');
        $filter_status =  $request->get('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch = $request->input('filter_branch');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');
        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_report_start_date  = $request->input('filter_report_start_date');
        $filter_report_end_date    = $request->input('filter_report_end_date');

        $filter_audit_result       = $request->input('filter_audit_result');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationIbcb::query()->with(['ibcb_audit','app_assign'])
                                        ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            if( strpos( $search_full , 'IB-' ) !== false || strpos( $search_full , 'CB-' ) !== false ){
                                                return $query->where('application_no',  'LIKE', "%$search_full%");
                                            }else{
                                                return  $query->where(function ($query2) use($search_full) {

                                                            $ids = ApplicationIbcbScope::where(function ($query) use($search_full) {
                                                                                    $query->whereHas('bs_branch_group', function($query) use ($search_full){
                                                                                                $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                            })
                                                                                            ->OrwhereHas('scopes_details.bs_branch', function($query) use ($search_full){
                                                                                                $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                            })
                                                                                            ->OrwhereHas('ibcb_scopes_tis.tis_standards', function($query) use ($search_full){
                                                                                                $query->where(function ($query) use($search_full) {
                                                                                                        $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                                    });
                                                                                            }); 
                                                                                })->select('application_id');

                                                            $query2->Where(DB::raw("REPLACE(applicant_name,' ','')"), 'LIKE', "%".$search_full."%")
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
                                            $query->whereHas('scopes_group', function($query) use ($filter_branch_group){
                                                $query->where('branch_group_id', $filter_branch_group);
                                            });
                                        })
                                        ->when($filter_branch, function ($query, $filter_branch){
                                            $query->whereHas('scopes_group.scopes_details', function($query) use ($filter_branch){
                                                $query->where('branch_id', $filter_branch);
                                            });
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return $query->where('application_status', $filter_status);
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
                                        ->when($filter_report_start_date, function ($query, $filter_report_start_date){
                                            $filter_report_start_date = HP::convertDate($filter_report_start_date, true);
                                            return  $query->whereHas('ibcb_report', function($query) use ($filter_report_start_date){
                                                                $query->where('report_date', '>=', $filter_report_start_date);
                                                            });
                                        })
                                        ->when($filter_report_end_date, function ($query, $filter_report_end_date){
                                            $filter_report_end_date = HP::convertDate($filter_report_end_date, true);
                                            return  $query->whereHas('ibcb_report', function($query) use ($filter_report_end_date){
                                                                $query->where('report_date', '<=', $filter_report_end_date);
                                                            });
                                        })
                                        ->when($filter_audit_result, function ($query, $filter_audit_result){

                                            if( $filter_audit_result == '-1'){
                                                return $query->Has('ibcb_audit','==',0);
                                            }else{
                                                $query->whereHas('ibcb_audit', function($query) use ($filter_audit_result){
                                                    $query->where('audit_result', $filter_audit_result);
                                                });
                                            }

                                        })
                                        ->when(!$user->isAdmin(), function($query) use ($user) {//ถ้าไม่ใช่ admin

                                            //id ตาราง basic_branch_groups สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
                                            $branch_group_ids = WorkGroupIB::UserBranchGroupIDs($user->getKey());

                                            $id_query = ApplicationIbcbScope::whereIn('branch_group_id', $branch_group_ids)->select('application_id');
                                            $query->whereIn('id', $id_query);

                                        })
                                        ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                            $id_query = ApplicationIbcbStaff::where('staff_id', $user->getKey())->select('application_id');
                                            $query->whereIn('id', $id_query);
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox"
                                                name="item_checkbox[]"
                                                class="item_checkbox"
                                                data-application_no="'. $item->application_no .'"
                                                data-applicant_name="'. $item->applicant_name .'"
                                                data-applicant_taxid="'. $item->applicant_taxid .'"
                                                data-application_status="'. $item->application_status .'"
                                                value="'. $item->id .'">';
                            })
                            ->addColumn('application_no', function ($item) {
                                return $item->application_no;
                            })
                            ->addColumn('applicant_name', function ($item) {
                                return (!empty($item->applicant_name)?$item->applicant_name:'-').'<div>('.(!empty($item->applicant_taxid)?$item->applicant_taxid:'-').')</div>';
                            })
                            ->addColumn('application_type', function ($item) {

                                $application_type_arr = [1 => 'IB', 2 => 'CB'];
                                return array_key_exists( $item->application_type,  $application_type_arr )?$application_type_arr [ $item->application_type ]:'-';
                            })
                            ->addColumn('scope', function ($item) {
                                return !empty($item->ScopeGroup)?$item->ScopeGroup:'-';
                            })
                            ->addColumn('application_date', function ($item) {
                                return !empty($item->application_date)?HP::DateThai($item->application_date):'-';
                            })
                            ->addColumn('status_application', function ($item) {
                                $arr = HP::ApplicationStatusIBCB();
                                return array_key_exists( $item->application_status,  $arr )?$arr [ $item->application_status ]:'-';
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->ListStaff)?$item->ListStaff:'รอดำเนินการ');
                            })
                            ->addColumn('audit_result', function ($item) {
                                $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                                $ibcb_audit = $item->ibcb_audit;
                                return (!empty($ibcb_audit->audit_result)  &&  array_key_exists( $ibcb_audit->audit_result,  $arr ) ? $arr[$ibcb_audit->audit_result]:'รอดำเนินการ');
                            })
                            ->addColumn('action', function ($item) use ($can_edit, $can_poko_approve) {

                                $btn = '';

                                if($can_edit){

                                    $ibcb_audit = $item->ibcb_audit;
                                    $ibcb_reports = $item->ibcb_report;

                                    $btn =  ' <a href="'. url('section5/application-ibcb-audit/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                                    if( in_array( $item->application_status , [ 3,4,7] ) ){
                                        $btn .= ' <a href="'. url('section5/application-ibcb-audit/results/'.$item->id) .'" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="บันทึกผลตรวจประเมิน"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';

                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-success btn-xs"  title="บันทึกผลตรวจประเมิน" disabled><i class="fa fa-check-square-o" aria-hidden="true"></i></button> ';
                                    }

                                    if( in_array( $item->application_status , [ 4,7,8] ) ){
                                        $btn .= ' <a href="'. url('section5/application-ibcb-audit/report/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="บันทึกสรุปรายงาน"><i class="fa fa-paste" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-info btn-xs" title="บันทึกสรุปรายงาน" disabled><i class="fa fa-paste" aria-hidden="true"></i></button> ';
                                    }

                                    if($can_poko_approve){
                                        if(in_array($item->application_status, [8, 9, 10])){
                                            $btn .= ' <a href="'. url('section5/application-ibcb-audit/approve/'.$item->id) .'" class="btn btn-warning btn-xs waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="พิจารณาสรุปรายงานผลตรวจประเมิน"><i class="icon-note" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= ' <button type="button" class="btn btn-warning btn-xs waves-effect waves-light" title="พิจารณาสรุปรายงานผลตรวจประเมิน" disabled><i class="icon-note" aria-hidden="true"></i></button> ';
                                        }
                                    }


                                }
                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action','applicant_name','assign_by','application_no'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-audit",  "name" => 'บันทึกผลตรวจประเมิน (IB/CB)' ],
            ];

            return view('section5.application-ibcb-audit.index',compact('breadcrumbs'));
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
        $model = str_slug('application-ibcb-audit','-');
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
        $model = str_slug('application-ibcb-audit','-');
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
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('view-'.$model)) {
            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->show = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-audit",  "name" => 'บันทึกผลตรวจประเมิน (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-audit/$id",  "name" => 'รายละเอียด' ]
            ];
            return view('section5.application-ibcb-audit.show',compact('applicationibcb','breadcrumbs'));
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
        $model = str_slug('application-ibcb-audit','-');
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
        $model = str_slug('application-ibcb-audit','-');
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
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function results($id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->results = true;
            
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-audit",  "name" => 'บันทึกผลตรวจประเมิน (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-audit/results/$id",  "name" => 'ผลตรวจประเมิน' ],

            ];

            return view('section5.application-ibcb-audit.results',compact('applicationibcb','breadcrumbs'));

        }
        abort(403);
    }

    public function results_save(Request $request, $id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $applicationibcb = ApplicationIbcb::findOrFail($id);

            if( isset($requestData['repeater-date']) ){

                $audit_dates = $requestData['repeater-date'];
                $date_arr = [];
                foreach($audit_dates as $audit_date){
                    $date_arr[] = !empty($audit_date['audit_date'])?HP::convertDate($audit_date['audit_date'], true):null;
                }
                $requestData['audit_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
            }

            $audit = ApplicationIbcbAudit::where('application_id', $applicationibcb->id )->first();

            if( is_null($audit) ){
                $audit = new ApplicationIbcbAudit;
                $audit->created_by = auth()->user()->getKey();
            }else{
                $audit->updated_by = auth()->user()->getKey();
                $audit->updated_at = date('Y-m-d H:i:s');
            }
            $audit->application_id = $applicationibcb->id;
            $audit->application_no = $applicationibcb->application_no;
            $audit->audit_date =  !empty($requestData['audit_date'])?$requestData['audit_date']:null;
            $audit->audit_result = !empty($requestData['audit_result'])?$requestData['audit_result']:null;
            $audit->audit_remark = !empty($requestData['audit_remark'])?$requestData['audit_remark']:null;
            $audit->send_mail_status = !empty($requestData['send_mail_status'])?$requestData['send_mail_status']:null;
            if(!empty($request->input('noti_email'))){
                $audit->noti_email = json_encode(explode(',', $request->input('noti_email')));
            }
            $audit->save();


            $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            $folder_app = ($applicationibcb->application_no);

            if(isset($requestData['audit_file'])){
                if ($request->hasFile('audit_file')) {
                    HP::singleFileUpload(
                        $request->file('audit_file') ,
                        $this->attach_path.$folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationIbcbAudit)->getTable() ),
                        $audit->id,
                        'file_application_ibcb_audit',
                        'เอกสารการตรวจประเมิน'
                    );
                }
            }

            if(  $audit->audit_result  == 1 ){
                if($request->input('submit_type') == 1){
                    $applicationibcb->update(['application_status' => 4]);
                }
                if( isset($requestData['repeater-scope']) ){

                    $list_detail = $requestData['repeater-scope'];
                    $remarks = $requestData['remark'];
                    foreach($list_detail as $detail){

                        $arr = [];
                        $arr['audit_result'] = ( isset($detail['audit_result'])?1:2 );
                        $arr['remark'] = array_key_exists(@$detail['branch_group_id'], $remarks)?$remarks[$detail['branch_group_id']]:null;

                        ApplicationIbcbScopeDetail::where('id',  $detail['detail_id'] )->update($arr);

                    }
                }
            }else{
                if($request->input('submit_type') == 1){
                    $applicationibcb->update(['application_status' => 7]);
                }
                ApplicationIbcbScopeDetail::where('application_no',  $applicationibcb->application_no )->update(['audit_result' => 2]);
            }

            return redirect('section5/application-ibcb-audit/results/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function report($id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->report = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-audit",  "name" => 'บันทึกผลตรวจประเมิน (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-audit/report/$id",  "name" => 'บันทึกสรุปรายงาน' ],

            ];
            return view('section5.application-ibcb-audit.report',compact('applicationibcb','breadcrumbs'));

        }
        abort(403);
    }


    public function report_save(Request $request, $id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $requestData = $request->all();

            $applicationibcb = ApplicationIbcb::findOrFail($id);

            $report = ApplicationIbcbReport::where('application_id', $applicationibcb->id )->first();

            if( is_null($report) ){
                $report = new ApplicationIbcbReport;
                $report->created_by = auth()->user()->getKey();
            }else{
                $report->updated_by = auth()->user()->getKey();
                $report->updated_at = date('Y-m-d H:i:s');
            }

            $report->application_id = $applicationibcb->id;
            $report->application_no = $applicationibcb->application_no;
            $report->report_date =  !empty($requestData['report_date'])?HP::convertDate($requestData['report_date'], true):null;
            $report->report_by = !empty($requestData['report_by'])?$requestData['report_by']:null;
            $report->report_description = !empty($requestData['report_description'])?$requestData['report_description']:null;
            $report->save();

            $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $folder_app = ($applicationibcb->application_no);

            if ($request->hasFile('file_attach_report')) {
                HP::singleFileUpload(
                    $request->file('file_attach_report') ,
                    $this->attach_path.$folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationIbcbReport)->getTable() ),
                    $report->id,
                    'file_attach_report',
                    'เอกสารสรุปรายงาน'
                );
            }

            if( isset( $requestData['repeater-file'] ) ){

                $repeater_file = $requestData['repeater-file'];

                foreach( $repeater_file as $file ){

                    if( isset(  $file['file_attach_other'] ) ){
                        HP::singleFileUpload(
                            $file['file_attach_other'] ,
                            $this->attach_path.$folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationIbcbReport)->getTable() ),
                            $report->id,
                            'file_attach_other',
                            !empty($file['caption'])?$file['caption']:null
                        );
                    }


                }

            }

            $applicationibcb->update(['application_status' => 8 ]);

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'บันทึกผลตรวจประเมิน IB/CB',
                null,
                'section5/application-ibcb-audit',
                $applicationibcb->created_by,
                1
            );

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'บันทึกผลตรวจประเมิน (IB/CB) : บันทึกสรุปรายงาน',
                'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                'section5/application-ibcb-audit/report/'.$applicationibcb->id,
                auth()->user()->getKey(),
                4
            );

            return redirect('section5/application-ibcb-audit/report/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }


    public function approve($id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->report = true;
            $applicationibcb->approve = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-audit",  "name" => 'บันทึกผลตรวจประเมิน (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-audit/approve/$id",  "name" => 'พิจารณาอนุมัติ' ],

            ];
            return view('section5.application-ibcb-audit.approve',compact('applicationibcb','breadcrumbs'));

        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $model = str_slug('application-ibcb-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $applicationibcb = ApplicationIbcb::findOrFail($id);

            $report = ApplicationIbcbReport::where('application_id', $applicationibcb->id )->first();

            if( !is_null( $report) ){

                if(empty($report->report_approve_at)){
                    $report->report_approve_at = date('Y-m-d H:i:s');
                    $report->report_approve_by = auth()->user()->getKey();
                }else{
                    $report->report_updated_at = date('Y-m-d H:i:s');
                    $report->report_updated_by = auth()->user()->getKey();
                }

                $report->report_approve = !empty($requestData['report_approve'])?$requestData['report_approve']:null;
                $report->report_approve_description = !empty($requestData['report_approve_description'])?$requestData['report_approve_description']:null;
                $report->send_mail_status = !empty($requestData['send_mail_status'])?$requestData['send_mail_status']:null;
                if(!empty($request->input('noti_email'))){
                    $report->noti_email = json_encode(explode(',', $request->input('noti_email')));
                }
                $report->save();

                $applicationibcb->update(['application_status' => $report->report_approve ]);

                HP::LogInsertNotification(
                    $applicationibcb->id ,
                    ( (new ApplicationIbcb)->getTable() ),
                    $applicationibcb->application_no,
                    $applicationibcb->application_status,
                    'บันทึกผลตรวจประเมิน IB/CB',
                    null,
                    'section5/application-ibcb-audit',
                    $applicationibcb->created_by,
                    1
                );

                HP::LogInsertNotification(
                    $applicationibcb->id ,
                    ( (new ApplicationIbcb)->getTable() ),
                    $applicationibcb->application_no,
                    $applicationibcb->application_status,
                    'บันทึกผลตรวจประเมิน (IB/CB) : พิจารณาอนุมัติ',
                    'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                    'section5/application-ibcb-audit/approve/'.$applicationibcb->id,
                    auth()->user()->getKey(),
                    3
                );

                if( $report->report_approve == 9 ){ //อนุมัติ อยู่ระหว่างเสนอคณะอนุกรรมการ

                    $audit = ApplicationIbcbAudit::where('application_id', $applicationibcb->id )->first();

                    $audit_date =  $audit->audit_date?json_decode( $audit->audit_date ):[];

                    $list_date = [];
                    foreach ($audit_date as  $date ) {
                        $list_date[$date] = HP::DateThaiFull($date);
                    }

                    //ส่งเมลแจ้งผปก.ผู้ยื่นคำขอ
                    if(array_key_exists('send_mail_status', $requestData) && $requestData['send_mail_status']==1){

                        $requestData['noti_email'] = json_encode(explode(',', $request->input('noti_email')));
                        //เมลผู้รับ
                        $emails = array_key_exists('noti_email', $requestData) ? json_decode($requestData['noti_email']) : [] ;
                        foreach ($emails as $key => $email) {
                            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                                unset($emails[$key]);
                            }
                        }

                        if(count($emails) > 0){

                            $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                            $mail_format = new ApplicationIBCBAuditMail([
                                                                                'applicant_name'  => $applicationibcb->applicant_name,
                                                                                'application_no'  => $applicationibcb->application_no,
                                                                                'audit_date'      => implode( ', ' ,$list_date ),
                                                                                'audit_result'    => (!empty($audit->audit_result)  &&  array_key_exists( $audit->audit_result,  $arr ) ? $arr[$audit->audit_result]:'รอดำเนินการ'),
                                                                                'audit_remark'    => (!empty($audit->report_approve_description)?$audit->report_approve_description:'-'),
                                                                        ]);

                            Mail::to($emails)->send($mail_format);
                        }
                    }
                }

            }

            return redirect('section5/application-ibcb-audit/approve/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');


        }
        abort(403);
    }

    public function GetdataApplication(Request $request)
    {
        $requestData = $request->all();
        $application = [];
        if(array_key_exists('id', $requestData)){
            $ids = explode(',',$requestData['id']);
            $application = ApplicationIbcb::whereIn('id', $ids)->get();
        }
        return view('section5.application-ibcb-audit.modals.table',compact('application'));
    }

    public function update_application_checkings(Request $request)
    {
        $requestData = $request->all();
        $msg = 'error';
        if(array_key_exists('id', $requestData)){

            if(!empty($requestData['repeater-date']) && count($requestData['repeater-date']) > 0){
                $audit_dates = $requestData['repeater-date'];
                $date_arr = [];
                foreach($audit_dates as $key=>$audit_date){
                    $date_arr[] = !empty($audit_date['m_audit_date'])?HP::convertDate($audit_date['m_audit_date'], true):null;
                }
                $requestData['audit_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
            }

            $ids = $requestData['id'];

            $application = ApplicationIbcb::whereIn('id', $ids)->get();

            foreach( $application  AS $applicationibcb ){

                // Save Scope AS Status
                if($request->input('m_audit_result') == 1){

                    if(isset($requestData['scope_id']) && is_array($requestData['scope_id']) && count($requestData['scope_id']) > 0){

                        $scope_ids = (isset($requestData['scope_id']) && is_array($requestData['scope_id']))?$requestData['scope_id']:[];
                        $scopes = ApplicationIbcbScopeDetail::find($requestData['scope_id']);
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

                    $applicationibcb->update(['application_status' => '4']);

                }else{
                    ApplicationIbcbScopeDetail::where('application_lab_id', $applicationibcb->id)->update(['audit_result' => (!empty( $requestData['m_audit_result'] )?$requestData['m_audit_result']:null), 'remark' => (!empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null)  ]);
                    $applicationibcb->update(['application_status' => '7']);
                }
                // End Save Scope AS Status

                // Save Audit
                $audit = ApplicationIbcbAudit::where('application_id', $applicationibcb->id )->first();
                if( is_null($audit) ){
                    $audit = new ApplicationIbcbAudit;
                    $audit->created_by = auth()->user()->getKey();
                }else{
                    $audit->updated_by = auth()->user()->getKey();
                    $audit->updated_at = date('Y-m-d H:i:s');
                }
                $audit->application_id = $applicationibcb->id;
                $audit->application_no = $applicationibcb->application_no;
                $audit->audit_date     = !empty($requestData['audit_date'])?$requestData['audit_date']:null;
                $audit->audit_result   = !empty($requestData['m_audit_result'])?$requestData['m_audit_result']:null;
                $audit->audit_remark   = !empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null;
                $audit->save();

                $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $folder_app = ($applicationibcb->application_no);

                if(isset($requestData['m_audit_file'])){
                    if ($request->hasFile('m_audit_file')) {
                        HP::singleFileUpload(
                            $request->file('m_audit_file') ,
                            $this->attach_path.$folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationIbcbAudit)->getTable() ),
                            $audit->id,
                            'file_application_ibcb_audit',
                            'เอกสารการตรวจประเมิน'
                        );
                    }
                }
                // End Save Audit

                $msg = 'success';

            }

        }

        return response()->json(['msg' => $msg ]);
    }

    public function update_application_reports(Request $request)
    {
        $requestData = $request->all();

        $msg = 'error';

        if(array_key_exists('id', $requestData)){

            $ids = $requestData['id'];

            $application = ApplicationIbcb::whereIn('id', $ids)->get();

            foreach( $application  AS $applicationibcb ){

                $report = ApplicationIbcbReport::where('application_id', $applicationibcb->id )->first();
                if( is_null($report) ){
                    $report = new ApplicationIbcbReport;
                    $report->created_by = auth()->user()->getKey();
                }else{
                    $report->updated_by = auth()->user()->getKey();
                    $report->updated_at = date('Y-m-d H:i:s');
                }
                $report->application_id     = $applicationibcb->id;
                $report->application_no     = $applicationibcb->application_no;
                $report->report_date        =  !empty($requestData['report_date'])?HP::convertDate($requestData['report_date'], true):null;
                $report->report_by          = !empty($requestData['report_by'])?$requestData['report_by']:null;
                $report->report_description = !empty($requestData['report_description'])?$requestData['report_description']:null;
                $report->save();

                $applicationibcb->update(['application_status' => 8 ]);

                $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $folder_app = ($applicationibcb->application_no);

                if ($request->hasFile('file_attach_report')) {
                    HP::singleFileUpload(
                        $request->file('file_attach_report') ,
                        $this->attach_path.$folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationIbcbReport)->getTable() ),
                        $report->id,
                        'file_attach_report',
                        'เอกสารสรุปรายงาน'
                    );
                }

                if( isset( $requestData['repeater-file'] ) ){

                    $repeater_file = $requestData['repeater-file'];

                    foreach( $repeater_file as $file ){

                        if( isset(  $file['file_attach_other'] ) ){
                            HP::singleFileUpload(
                                $file['file_attach_other'] ,
                                $this->attach_path.$folder_app,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Center',
                                (  (new ApplicationIbcbReport)->getTable() ),
                                $report->id,
                                'file_attach_other',
                                !empty($file['caption'])?$file['caption']:null
                            );
                        }


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

            $application = ApplicationIbcb::whereIn('id', $ids)->get();

            foreach( $application  AS $applicationibcb ){

                $report = ApplicationIbcbReport::where('application_id', $applicationibcb->id )->first();

                if( !is_null( $report ) ){
                    if(empty($report->report_approve_at)){
                        $report->report_approve_at = date('Y-m-d H:i:s');
                        $report->report_approve_by = auth()->user()->getKey();
                    }else{
                        $report->report_updated_at = date('Y-m-d H:i:s');
                        $report->report_updated_by = auth()->user()->getKey();
                    }

                    $report->report_approve             = !empty($requestData['report_approve'])?$requestData['report_approve']:null;
                    $report->report_approve_description = !empty($requestData['report_approve_description'])?$requestData['report_approve_description']:null;
                    $report->save();

                    $applicationibcb->update(['application_status' => $report->report_approve ]);
                }

            }
            $msg = 'success';
        }
        return response()->json(['msg' => $msg ]);
    }
}
