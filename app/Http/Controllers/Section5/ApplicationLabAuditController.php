<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Section5\ApplicationLabAudit;
use Illuminate\Http\Request;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabsReport;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\ApplicationLabStaff;
use App\Models\Section5\ApplicationLabSummary;
use App\Models\Section5\ApplicationLabSummaryDetail;
use App\Models\Bsection5\TestItem;

use stdClass;
use App\Models\Tis\Standard;
use App\Models\Bsection5\Workgroup;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\AttachFile;
use HP;

use App\Mail\Section5\ApplicationLabAuditMail;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;

class ApplicationLabAuditController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_lab_audit/';
        $this->attach_path_crop = 'tis_attach/application_lab_audit_crop/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_audit",  "name" => 'บันทึกผลตรวจประเมิน (LAB)' ],
            ];
            return view('section5.application_lab_audit.index', compact('breadcrumbs'));
        }
        abort(403);

    }

    public function data_list(Request $request)
    {

        $model = str_slug('application-lab-audit','-');

        $can_edit         = auth()->user()->can('edit-'.$model);
        $can_poko_approve = auth()->user()->can('poko_approve-'.$model);

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_report_start_date  = $request->input('filter_report_start_date');
        $filter_report_end_date    = $request->input('filter_report_end_date');

        $filter_tis_id = $request->input('filter_tis_id');

        $filter_audit_result       = $request->input('filter_audit_result');

        $filter_applicant_type    = $request->input('filter_applicant_type');
        $filter_audit_type        = $request->input('filter_audit_type');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationLab::query()->when( $filter_search , function ($query, $filter_search){
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
                                                                            ->OrwhereIn('id', $ids)
                                                                            ->OrwhereHas('app_staff.user_staff', function($query) use ($search_full){
                                                                                $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                            })
                                                                            ->OrWhere('application_no',  'LIKE', "%$search_full%");
                                                                });
                                            }
                                        })
                                        ->when( $filter_status , function ($query, $filter_status){
                                            return $query->where('application_status', $filter_status );
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
                                        ->when($filter_report_start_date, function ($query, $filter_report_start_date){
                                            $filter_report_start_date = HP::convertDate($filter_report_start_date, true);
                                            return  $query->whereHas('app_report', function($query) use ($filter_report_start_date){
                                                                $query->where('report_date', '>=', $filter_report_start_date);
                                                            });
                                        })
                                        ->when($filter_report_end_date, function ($query, $filter_report_end_date){
                                            $filter_report_end_date = HP::convertDate($filter_report_end_date, true);
                                            return  $query->whereHas('app_report', function($query) use ($filter_report_end_date){
                                                                $query->where('report_date', '<=', $filter_report_end_date);
                                                            });
                                        })
                                        ->when($filter_audit_result, function ($query, $filter_audit_result){

                                            if( $filter_audit_result == '-1'){
                                                return $query->Has('app_audit','==',0);
                                            }else{
                                                $query->whereHas('app_audit', function($query) use ($filter_audit_result){
                                                    $query->where('audit_result', $filter_audit_result);
                                                });
                                            }

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
                                        ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                            $id_query = ApplicationLabStaff::where('staff_id', $user->getKey())->select('application_lab_id');
                                            $query->whereIn('id', $id_query);
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox"
                                                name="item_checkbox[]"
                                                class="item_checkbox"
                                                data-audit_type="'. $item->audit_type .'"
                                                data-application_no="'. $item->application_no .'"
                                                data-applicant_name="'. $item->applicant_name .'"
                                                data-applicant_taxid="'. $item->applicant_taxid .'"
                                                data-lab_name="'. $item->lab_name .'"
                                                data-application_status="'. $item->application_status .'"
                                                value="'. $item->id .'">';
                            })
                            ->addColumn('application_no', function ($item) {
                                $application_type_arr = [ 1 => 'ขอขึ้นทะเบียนใหม่', 2 => 'ขอเพิ่มเติมขอบข่าย', 3 => 'ขอลดขอบข่าย', 4 => 'ขอแก้ไขข้อมูล'];
                                $applicant_type = array_key_exists( $item->applicant_type,  $application_type_arr )?$application_type_arr [ $item->applicant_type ]:'-';

                                $audit_type_arr = [1 => '<span class="text-success">ตรวจตามใบรับรอง</span>', 2 => '<span class="text-info">ตรวจตามภาคผนวก ก.</span>'];
                                $audit_type     = array_key_exists($item->audit_type, $audit_type_arr) ? $audit_type_arr[$item->audit_type] : '-' ;

                                return $item->application_no.'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>'.'<em>'.( $applicant_type).'</em>'."<p><u>({$audit_type})</u></p>";
                            })
                            ->addColumn('applicant_name', function ($item) {
                                return '<div>'.(!empty($item->lab_name)?$item->lab_name:'-').'</div>'.(!empty($item->applicant_name)?'('.$item->applicant_name.')':'-');
                            })
                            ->addColumn('applicant_taxid', function ($item) {
                                return !empty($item->applicant_taxid)?$item->applicant_taxid:'-';
                            })
                            ->addColumn('standards', function ($item) {
                                // return '<button class="btn btn-link modal_show_scope" data-id="'.($item->id).'" data-application_no="'.($item->application_no).'">'.(!empty($item->ScopeStandard)?$item->ScopeStandard:'-').'</button>';
                                return (!empty($item->ScopeStandard)?$item->ScopeStandard:'-');
                            })
                            ->addColumn('report_date', function ($item) {
                                $app_report = $item->app_report;
                                return !empty($app_report->report_date)?HP::DateThai($app_report->report_date):'รอดำเนินการ';
                            })
                            ->addColumn('status_application', function ($item) {
                                $app_audit = $item->app_audit;
                                $arr = ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'];
                                return '<div>'.(!empty($item->AppStatus)?$item->AppStatus:'ไม่มีสถานะ').'</div>('.( !empty($app_audit->audit_result)  &&  array_key_exists( $app_audit->audit_result,  $arr ) ? $arr[$app_audit->audit_result]:'รอดำเนินการ' ).')';
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->assign_by)?$item->AssignStaff:'รอดำเนินการ').(!empty($item->assign_date)?'<br>'.HP::DateThaiFull($item->assign_date):null);
                            })
                            ->addColumn('action', function ($item) use ($can_edit, $can_poko_approve) {

                                $app_summary_list = $item->app_summary_list;

                                $btn = '';
                                $btn =  '<a href="'. url('section5/application_lab_audit/'.$item->id) .'" class="btn btn-info btn-xs m-r-5" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                                if($can_edit){

                                    if( in_array( $item->application_status , [ 3,4,7] ) ){
                                        $btn .= '<a class="btn btn-success btn-xs waves-effect waves-light m-r-5" href="'. url('section5/application_lab_audit/'.$item->id.'/edit') .'" data-toggle="tooltip" data-placement="top" title="บันทึกผลตรวจประเมิน"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= '<button type="button" class="btn btn-success btn-xs m-l-1"  title="บันทึกผลตรวจประเมิน" disabled><i class="fa fa-check-square-o" aria-hidden="true"></i></button> ';
                                    }

                                    if( in_array( $item->application_status , [ 4,7,8] ) ){
                                        $btn .=  '<a href="'. url('section5/application_lab_audit/lab_report/'.$item->id) .'" class="btn btn-info btn-xs m-r-5" data-toggle="tooltip" data-placement="top" title="บันทึกสรุปรายงาน"><i class="fa fa-paste" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= '<button type="button" class="btn btn-info btn-xs m-l-1" title="บันทึกสรุปรายงาน" disabled><i class="fa fa-paste" aria-hidden="true"></i></button> ';
                                    }

                                    if($can_poko_approve){
                                        if(in_array($item->application_status, [8, 9, 10])){
                                            $btn .= '<a class="btn btn-warning btn-xs waves-effect waves-light m-r-5" href="'. url('section5/application_lab_audit/lab_report_approve/'.$item->id) .'" data-toggle="tooltip" data-placement="top" title="พิจารณาสรุปรายงานผลตรวจประเมิน"><i class="icon-note" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= '<button type="button" class="btn btn-warning btn-xs waves-effect waves-light m-l-1"  title="พิจารณาสรุปรายงานผลตรวจประเมิน" disabled><i class="icon-note" aria-hidden="true"></i></button> ';
                                        }
                                    }

                                    if( count( $app_summary_list) >= 1 ){
                                        $btn .= '<button type="button" class="btn btn-primary btn-xs m-r-5 btn_print_reports" data-id="'.($item->id).'"  title="พิมพ์"><i class="fa fa-file-word-o" aria-hidden="true"></i></button> ';
                                    }
                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by','applicant_name','application_no','status_application','standards'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('section5.application_lab_audit.create');
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            ApplicationLabAudit::create($requestData);
            return redirect('section5/application_lab_audit')->with('flash_message', 'เพิ่ม ApplicationLabAudit เรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('view-'.$model)) {
            $applicationlabaudit = ApplicationLab::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_audit",  "name" => 'บันทึกผลตรวจประเมิน (LAB)' ],
                [ "link" => "/section5/application_lab_audit/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('section5.application_lab_audit.show', compact('applicationlabaudit','breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationlabaudit = ApplicationLab::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_audit",  "name" => 'บันทึกผลตรวจประเมิน (LAB)' ],
                [ "link" => "/section5/application_lab_audit/$id/edit",  "name" => 'ผลตรวจประเมิน ' ],

            ];
            return view('section5.application_lab_audit.edit', compact('applicationlabaudit','breadcrumbs'));
        }
        abort(403);
    }

    public function lab_report($id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationlabaudit = ApplicationLab::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_audit",  "name" => 'บันทึกผลตรวจประเมิน (LAB)' ],
                [ "link" => "/section5/application_lab_audit/lab_report/$id",  "name" => 'บันทึกสรุปรายงาน ' ],

            ];
            return view('section5.application_lab_audit.lab_report.edit', compact('applicationlabaudit','breadcrumbs'));
        }
        abort(403);
    }

    public function lab_report_approve($id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationlabaudit = ApplicationLab::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_lab_audit",  "name" => 'บันทึกผลตรวจประเมิน (LAB)' ],
                [ "link" => "/section5/application_lab_audit/lab_report_approve/$id",  "name" => 'พิจารณาอนุมัติ ' ],

            ];
            return view('section5.application_lab_audit.lab_report_approve.edit', compact('applicationlabaudit','breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $submit_type = 1;   // บันทึก
            if( isset($requestData['submit_type']) && $requestData['submit_type'] == 2 ){
                $submit_type = 2;  // ฉบับร่าง
            }

            $application = ApplicationLab::findOrFail($id);
            if($request->input('audit_result') == 1){
                $standardList = $application->app_scope_standard->pluck('tis_id','tis_id');
                foreach( $standardList AS $standardID ){
                    $GroupName = 'list-standard-'.$standardID;
                    if( isset( $requestData[$GroupName] ) ){
                        foreach( $requestData[$GroupName] AS $ScopeData ){
                            $Where = [
                                'application_lab_id' => $application->id,
                                'tis_id'             => $standardID,
                                'test_item_id'       => $ScopeData['test_item_id']
                            ];
                            ApplicationLabScope::where( $Where )->update(['remark' => !empty($ScopeData['remark'])?$ScopeData['remark']:null, 'audit_result' => ( isset($ScopeData['audit_result'])?1:2 ) ]);
                        }
                    }
                }

                if( $submit_type == 1){
                    $application->update(['application_status' => '4']);
                }
            }else{
                ApplicationLabScope::where('application_lab_id', $id)->update($request->only(['audit_result']));
                if( $submit_type == 1){
                    $application->update(['application_status' => '7']);
                }
            }

            $requestData['application_lab_id'] = $id;
            $requestData['application_no'] = @$application->application_no;
            $requestData['created_by'] = auth()->user()->getKey();
            if(!empty($request->input('noti_email'))){
                $requestData['noti_email'] = json_encode(explode(',', $request->input('noti_email')));
            }
            if(!empty($requestData['repeater-date']) && count($requestData['repeater-date']) > 0){
                $audit_dates = $requestData['repeater-date'];
                $date_arr = [];
                foreach($audit_dates as $key=>$audit_date){
                    $date_arr[] = !empty($audit_date['audit_date'])?HP::convertDate($audit_date['audit_date'], true):null;
                }
                $requestData['audit_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
            }

            $application_labs_audit = ApplicationLabAudit::where('application_lab_id', $application->id )->first();
            if( is_null($application_labs_audit) ){
                $application_labs_audit = ApplicationLabAudit::create($requestData);
            }else{
                $application_labs_audit->update($requestData);
            }
            $folder_app = ($application->application_no).'/';

            $tax_number = !empty($application->applicant_taxid )?$application->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            if(isset($requestData['audit_file'])){
                if ($request->hasFile('audit_file')) {
                    HP::singleFileUpload(
                        $request->file('audit_file') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabAudit)->getTable() ),
                        $application_labs_audit->id,
                        'audit_file',
                        'เอกสารการตรวจประเมิน'
                    );
                }
            }


            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                null,
                'section5/application_lab_audit',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'บันทึกผลตรวจประเมิน',
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                'section5/application_lab_audit/'.$application->id.'/edit',
                auth()->user()->getKey(),
                4
            );

            return redirect('section5/application_lab_audit')->with('flash_message', 'แก้ไข ApplicationLabAudit เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function lab_report_save(Request $request, $id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();
            $requestData['application_lab_id'] = @$id;
            $requestData['report_date'] = !empty($requestData['report_date'])?HP::convertDate($requestData['report_date'], true):null;
            $requestData['created_by'] = auth()->user()->getKey();
            $application = ApplicationLab::findOrFail($id);
            $requestData['application_no'] = @$application->application_no;

            $application_report = ApplicationLabsReport::where('application_lab_id', $id)->first();
            if( is_null($application_report) ){
                $application_report = ApplicationLabsReport::create($requestData);
            }else{
                $application_report->update($requestData);
            }

            $tax_number = !empty($application->applicant_taxid )?$application->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $folder_app = ($application->application_no).'/';

            if ($request->hasFile('file_attach_report')) {
                HP::singleFileUpload(
                    $request->file('file_attach_report') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationLabsReport)->getTable() ),
                    $application_report->id,
                    'file_attach_report',
                    'เอกสารสรุปรายงาน'
                );
            }

            if( isset( $requestData['repeater-file'] ) && count($requestData['repeater-file']) > 0 ){

                $repeater_file = $requestData['repeater-file'];

                foreach( $repeater_file as $key=>$file ){

                    if( $request->hasFile("repeater-file.{$key}.file_attach_other") ){
                        HP::singleFileUpload(
                            $request->file("repeater-file.{$key}.file_attach_other"),
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationLabsReport)->getTable() ),
                            $application_report->id,
                            'file_attach_other',
                            $request->input("repeater-file.{$key}.caption")
                        );
                    }

                }

            }

            $application->update(['application_status' => 8]);

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                null,
                'section5/application_lab_audit',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'บันทึกสรุปรายงาน',
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                'section5/application_lab_audit/lab_report/'.$application->id,
                auth()->user()->getKey(),
                4
            );

            return redirect('section5/application_lab_audit')->with('flash_message', 'เพิ่ม ApplicationLabAudit เรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function lab_report_approve_save(Request $request, $id)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $application = ApplicationLab::findOrFail($id);
            $report = ApplicationLabsReport::where('application_lab_id', $id)->first();
            if(!empty($report)){
                if(empty($report->report_approve_at)){
                    $requestData['report_approve_at'] = date('Y-m-d H:i:s');
                    $requestData['report_approve_by'] = auth()->user()->getKey();
                }else{
                    $requestData['report_updated_at'] = date('Y-m-d H:i:s');
                    $requestData['report_updated_by'] = auth()->user()->getKey();
                }

                $requestData['send_mail_status'] = !empty($requestData['report_approve_send_mail_status'])?$requestData['report_approve_send_mail_status']:null;

                if(!empty($request->input('report_approve_noti_email'))){
                    $requestData['noti_email'] = json_encode(explode(',', $request->input('report_approve_noti_email')));
                }

                $report->update($requestData);

            }
            $application->update(['application_status' => $request->input('report_approve')]);

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                null,
                'section5/application_lab_audit',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationLab)->getTable() ),
                $application->application_no,
                $application->application_status,
                'พิจารณาอนุมัติ',
                'ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)',
                'section5/application_lab_audit/lab_report_approve/'.$application->id,
                auth()->user()->getKey(),
                3
            );

            if( $report->report_approve == 9 ){ //อนุมัติ อยู่ระหว่างเสนอคณะอนุกรรมการ

                $audit = ApplicationLabAudit::where('application_lab_id', $application->id )->first();

                $audit_date =  $audit->audit_date?json_decode( $audit->audit_date ):[];

                $list_date = [];
                foreach ($audit_date as  $date ) {
                    $list_date[$date] = HP::DateThaiFull($date);
                }

                //ส่งเมลแจ้งผปก.ผู้ยื่นคำขอ
                if(array_key_exists('send_mail_status', $requestData) && $requestData['send_mail_status']==1){

                    $requestData['noti_email'] = json_encode(explode(',', $request->input('report_approve_noti_email')));
                    //เมลผู้รับ
                    $emails = array_key_exists('noti_email', $requestData) ? json_decode($requestData['noti_email']) : [] ;
                    foreach ($emails as $key => $email) {
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                            unset($emails[$key]);
                        }
                    }

                    if(count($emails) > 0){
                        $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                        $mail_format = new ApplicationLabAuditMail([
                                                                            'applicant_name'  => $application->applicant_name,
                                                                            'application_no'  => $application->application_no,
                                                                            'audit_date'      => implode( ', ' ,$list_date ),
                                                                            'audit_result'    => (!empty($audit->audit_result)  &&  array_key_exists( $audit->audit_result,  $arr ) ? $arr[$audit->audit_result]:'รอดำเนินการ'),
                                                                            'audit_remark'    => (!empty($report->report_description)?$report->report_description:'-'),
                                                                    ]);

                        Mail::to($emails)->send($mail_format);
                    }
                }
            }


            return redirect('section5/application_lab_audit')->with('flash_message', 'แก้ไข ApplicationLabAudit เรียบร้อยแล้ว!');
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('application-lab-audit','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new ApplicationLabAudit;
            ApplicationLabAudit::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ApplicationLabAudit::destroy($id);
          }

          return redirect('section5/application_lab_audit')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('application-lab-audit','-');
      if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            if(array_key_exists('cb', $requestData)){
                $ids = $requestData['cb'];
                $db = new ApplicationLabAudit;
                ApplicationLabAudit::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
            }

            return redirect('section5/application_lab_audit')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }

        abort(403);

    }


    public function GetdataApplicationLab(Request $request)
    {
        $requestData = $request->all();
        $application = [];
        if(array_key_exists('id', $requestData)){
            $ids = explode(',',$requestData['id']);
            $application = ApplicationLab::whereIn('id', $ids)->get();
        }
        return view('section5.application_lab_audit.modals.table',compact('application'));
    }

    public function update_lab_checkings(Request $request)
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

            $application = ApplicationLab::whereIn('id', $ids)->get();

            foreach( $application  AS $item ){

                // Save Scope AS Status
                if($request->input('m_audit_result') == 1){

                    if(isset($requestData['scope_id']) && is_array($requestData['scope_id']) && count($requestData['scope_id']) > 0){

                        $scope_ids = (isset($requestData['scope_id']) && is_array($requestData['scope_id']))?$requestData['scope_id']:[];
                        $scopes = ApplicationLabScope::find($requestData['scope_id']);
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

                    $item->update(['application_status' => '4']);

                }else{
                    ApplicationLabScope::where('application_lab_id', $item->id)->update(['audit_result' => (!empty( $requestData['m_audit_result'] )?$requestData['m_audit_result']:null), 'remark' => (!empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null)  ]);
                    $item->update(['application_status' => '7']);
                }
                // End Save Scope AS Status

                // Save Audit
                $requestAudit['audit_date']         = !empty($requestData['audit_date'])?$requestData['audit_date']:null;
                $requestAudit['audit_result']       = !empty($requestData['m_audit_result'])?$requestData['m_audit_result']:null;
                $requestAudit['audit_remark']       = !empty($requestData['m_audit_remark'])?$requestData['m_audit_remark']:null;
                $requestAudit['application_lab_id'] = $item->id;
                $requestAudit['application_no']     = $item->application_no;

                $audit = ApplicationLabAudit::where('application_lab_id', $item->id )->first();
                if( is_null($audit) ){
                    $requestAudit['created_by'] = auth()->user()->getKey();
                    $audit = ApplicationLabAudit::create($requestAudit);
                }else{
                    $requestAudit['updated_by'] = auth()->user()->getKey();
                    $requestAudit['updated_at'] = date('Y-m-d H:i:s');
                    $audit->update($requestAudit);
                }

                $folder_app = ($item->application_no).'/';

                $tax_number = !empty($item->applicant_taxid )?$item->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                if(isset($requestData['m_audit_file'])){
                    if ($request->hasFile('m_audit_file')) {
                        HP::singleFileUpload(
                            $request->file('m_audit_file') ,
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationLabAudit)->getTable() ),
                            $audit->id,
                            'audit_file',
                            'เอกสารการตรวจประเมิน'
                        );
                    }
                }
                // End Save Audit
            }
            $msg = 'success';
        }

        return response()->json(['msg' => $msg ]);

    }

    public function update_lab_reports(Request $request)
    {
        $requestData = $request->all();

        $msg = 'error';

        if(array_key_exists('id', $requestData)){

            $ids = $requestData['id'];

            $application = ApplicationLab::whereIn('id', $ids)->get();

            foreach( $application  AS $item ){

                $requestReport['report_date']        = !empty($requestData['report_date'])?HP::convertDate($requestData['report_date'], true):null;
                $requestReport['report_description'] = !empty($requestData['report_description'])?$requestData['report_description']:null;
                $requestReport['report_by']          = !empty($requestData['report_by'])?$requestData['report_by']:null;
                $requestReport['application_lab_id'] = $item->id;
                $requestReport['application_no']     = $item->application_no;

                $report = ApplicationLabsReport::where('application_lab_id', $item->id )->first();
                if( is_null($report) ){
                    $report = ApplicationLabsReport::create($requestReport);
                }else{
                    $report->update($requestReport);
                }

                $folder_app = ($item->application_no).'/';
                $tax_number = !empty($item->applicant_taxid )?$item->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                if ($request->hasFile('file_attach_report')) {
                    HP::singleFileUpload(
                        $request->file('file_attach_report') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabsReport)->getTable() ),
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
                                (  (new ApplicationLabsReport)->getTable() ),
                                $report->id,
                                'file_attach_other',
                                !empty($file['caption'])?$file['caption']:null
                            );
                        }


                    }

                }

                $item->update(['application_status' => 8]);

                $msg = 'success';

            }

        }
        return response()->json(['msg' => $msg ]);
    }

    public function update_lab_approve(Request $request)
    {
        $requestData = $request->all();

        $msg = 'error';

        if(array_key_exists('id', $requestData)){

            $ids = $requestData['id'];

            $application = ApplicationLab::whereIn('id', $ids)->get();

            foreach( $application  AS $item ){

                $report = ApplicationLabsReport::where('application_lab_id', $item->id )->first();
                if(!is_null($report)){
                    if(empty($report->report_approve_at)){
                        $requestData['report_approve_at'] = date('Y-m-d H:i:s');
                        $requestData['report_approve_by'] = auth()->user()->getKey();
                    }else{
                        $requestData['report_updated_at'] = date('Y-m-d H:i:s');
                        $requestData['report_updated_by'] = auth()->user()->getKey();
                    }
                    $report->update($requestData);
                }
                $item->update(['application_status' => $request->input('report_approve')]);

            }

            $msg = 'success';
        }

        return response()->json(['msg' => $msg ]);
    }

    public function gen_lab_reports(Request $request)
    {
        $requestData = $request->all();

        $msg = 'error';

        if(array_key_exists('id', $requestData)){

            $requestSummary['meeting_date']        = !empty($requestData['meeting_date'])?HP::convertDate($requestData['meeting_date'], true):null;
            $requestSummary['meeting_no']          = !empty($requestData['meeting_no'])?$requestData['meeting_no']:null;
            $requestSummary['meeting_description'] = !empty($requestData['meeting_description'])?$requestData['meeting_description']:null;
            $requestSummary['created_at']          = date('Y-m-d H:i:s');
            $requestSummary['created_by']          = auth()->user()->getKey();

            $summary = ApplicationLabSummary::where('meeting_date', $requestSummary['meeting_date'])->where('meeting_no', $requestSummary['meeting_no'])->first();

            if( is_null($summary) ){
                $summary = ApplicationLabSummary::create($requestSummary);
            }

            $ids = $requestData['id'];

            $application = ApplicationLab::whereIn('id', $ids)->get();

            $no = 0;
            foreach( $application  AS $item ){

                $detail = ApplicationLabSummaryDetail::where('application_lab_id', $item->id )->where('app_summary_id', $summary->id )->first();

                if( is_null($detail) ){

                    $no++;

                    $requestDetail['app_summary_id']     = $summary->id;
                    $requestDetail['application_lab_id'] = $item->id;
                    $requestDetail['application_no']     = $item->application_no;
                    $requestDetail['meeting_no']         = $no;
                    $requestDetail['agenda_no']          = ($item->audit_type == 1)?'5.2.1':'5.2.2';

                    ApplicationLabSummaryDetail::create($requestDetail);

                }

            }

            $msg = 'success';
        }

        return response()->json(['msg' => $msg ]);
    }


    public function get_application_summary(Request $request)
    {
        $requestData = $request->all();
        $application = [];
        if(array_key_exists('id', $requestData)){
            $ids = explode(',',$requestData['id']);
            $application = ApplicationLab::whereIn('id', $ids)->get();
        }

        return view('section5.application_lab_audit.modals.table-summary',compact('application'));

    }
    public function export_word(Request $request)
    {
        $requestData = $request->all();
        $id = explode(',',$requestData['id']);
   
        $detail = ApplicationLabSummaryDetail::whereIn('id', $id)->first();

        $app_summary = $detail->app_summary;
        $application = $detail->app_lab;

        if(  $application->audit_type == 1 ){
            return $this->word_type_1($application , $detail ,$app_summary);
        }else{
            return $this->word_type_2($application , $detail ,$app_summary);

        }


    }

    public function word_type_1( $application , $detail ,$app_summary  ){

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();

        $templateProcessor = new TemplateProcessor(public_path('/word/application_audit_type_1.docx'));

        $templateProcessor->setValue('meet_no', ( (!empty($app_summary->meeting_no)?$app_summary->meeting_no:null).'-'.(!empty($detail->meeting_no)?$detail->meeting_no:null) ) );
        $templateProcessor->setValue('meet_date', ( !empty($app_summary->meeting_date)?HP::DateThaiFull($app_summary->meeting_date):null ) );
        $templateProcessor->setValue('meet_type', ( !empty($detail->agenda_no)?$detail->agenda_no:null ) );

        $templateProcessor->setValue('applicant_name', ( !empty($application->applicant_name)?$application->applicant_name:null ) );
        $templateProcessor->setValue('lab_name', ( !empty($application->lab_name)?$application->lab_name:null ) );
        $templateProcessor->setValue('lab_address', ( !empty($application->LabDataAdress)?$application->LabDataAdress:null ) );

        $text_scope = '';
        $app_scope_standard =  $application->app_scope_standard()->select('tis_id')->groupBy('tis_id')->get();

        $lab_scope_standard = $application->section5_labs_scope()->select('tis_id')->groupBy('tis_id')->get();

        $templateProcessor->setValue('count_std', ( !empty($application)?count($lab_scope_standard):null ) );

        $s = 0;
        foreach( $app_scope_standard AS $ks => $std ){

            $br_tap1 = ($s == count($app_scope_standard) ) || ($s == 0 )?'':' <w:br/>';

            if( !is_null( $std->tis_standards ) ){
                
                $s++;
                $text_scope .= $br_tap1.'('.$s.') ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($std->StandardTitle)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($std->StandardTisTisNo) );
            }

        }

        $templateProcessor->setValue('text_scope', ( !empty($text_scope)?$text_scope:null ) );

        $application_certificate = $application->application_certificate()->get();
        $text_cer = '';
        foreach( $application_certificate AS $kc => $cer ){

            $br_tap2 = ($kc+1 == count($application_certificate) )?'':' <w:br/>';

            $text_cer .= 'ใบรับรองเลขที่ '.($cer->certificate_no).'หมายเลขการรับรองที่ ทดสอบ '.($cer->accereditatio_no).' <w:br/>';
            $text_cer .= 'ออกให้ตั้งแต่วันที่ '.(HP::DateThaiFull($cer->certificate_start_date)).' ถึงวันที่ '.(HP::DateThaiFull($cer->certificate_end_date)). $br_tap2 ;

        }
        $templateProcessor->setValue('text_cer', ( !empty($text_cer)?$text_cer:null ) );

        $text_lab_scope = '-';
        if( !empty($application->section5_labs) && !empty( $application->section5_labs->scope_standard ) ){
            $section5_labs = $application->section5_labs;

            $scope_standard = $section5_labs->scope_standard()->select('tis_id')->groupBy('tis_id')->get();
            $text_lab_scope = '';
            $sl = 0;
            foreach( $scope_standard AS $kls => $Sitem ){
                $br_tap3 = ($sl == count($scope_standard) ) || ($sl == 0 )?'':' <w:br/>';

                if( !is_null($Sitem->tis_standards) ){
                    $sl++;
                    $text_lab_scope .=  $br_tap3.'('.$sl.') ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($Sitem->tis_standards->title)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($Sitem->tis_standards->tis_tisno) );
                }
            }

        }

        $templateProcessor->setValue('text_lab_scope', ( !empty($text_lab_scope)?$text_lab_scope:null ) );

        $myFontStyle = array('name' => 'TH SarabunPSK', 'size' => 16);
        $myFontStyle2 = array('name' => 'TH SarabunPSK', 'size' => 16);
        $myParagraphStyle = array('align'=>'left', 'spaceBefore'=>50, 'spaceafter' => 50);
        $cellColSpan = array('gridSpan' => 6 );
        $cellRowSpan = array('vMerge' => 'continue');
        
        $table_report_scope = new Table(array('borderSize' => 0,'borderColor' => 'white', 'width' => 9000, 'unit' => TblWidth::TWIP));

        $s = 0;
        foreach( $app_scope_standard AS  $std ){

            $s++;

            $title = $s.' ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($std->StandardTitle)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($std->StandardTisTisNo) ).' (ทุกรายการ/เฉพาะรายการ)';
            $table_report_scope->addRow();
            $table_report_scope->addCell(9000, $cellColSpan)->addText( $title , $myFontStyle, $myParagraphStyle  );

            $styleCell_th1  = [ 'borderTopColor' =>'blank', 'borderBottomColor' =>'white', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];
            $styleCell_th2  = [ 'borderTopColor' =>'white', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5, 'vMerge' => 'continue' ];
            $styleCell      = [ 'borderTopColor' =>'blank', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];

            $cellColSpanStd = [ 'gridSpan' => 2 , 'borderTopColor' =>'blank', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];

            $myParagraphStd = array( 'align'=>'center', 'spaceBefore'=>50, 'spaceafter' => 50);

            //สร้าง ตารางรายการทดสอบ
            $table_report_scope->addRow();
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('ที่', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(3000, $styleCell_th1)->addText('รายการ', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('ประวัติการได้รับแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('การขอรับการแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(2500, $cellColSpanStd)->addText('ผลการตรวจประเมิน', $myFontStyle2, $myParagraphStd  );

            $table_report_scope->addRow();
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(3000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell)->addText('เสนอแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1500, $styleCell)->addText('เหตุผล', $myFontStyle2, $myParagraphStd  );

            $scope_item = $this->GetDataTestItem( $std->tis_id, $application );

            $i = 0;
            $chek = [];
            foreach( $scope_item AS $items ){

                $table_report_scope->addRow();
                if( !array_key_exists( $items['main_topic_id'],  $chek ) ){
                    $i++;  
                    $table_report_scope->addCell(1000, $styleCell_th1)->addText( $i  , $myFontStyle2, $myParagraphStd  );
                    $chek[ $items['main_topic_id'] ] = $items['main_topic_id'];
                }else{
                    $table_report_scope->addCell(1000, $styleCell_th2 );  
                }
                $table_report_scope->addCell(3000, $styleCell)->addText( $items['text']         , $myFontStyle2, $myParagraphStyle  );
                $table_report_scope->addCell(1000, $styleCell)->addText( $items['old']          , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1000, $styleCell)->addText( $items['status']       , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1000,  $styleCell)->addText( $items['audit_result'] , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1500,  $styleCell)->addText( $items['remark']       , $myFontStyle2, $myParagraphStyle  );
        
            }

        }
        $templateProcessor->setComplexBlock('table_report_scope', $table_report_scope);

        $perfix = [ 1 => 'นาย', 2 => 'นางสาว', 3 => 'นาง' ];

        $assign_name = '';
        foreach( $application->users_assign AS $ka => $users_assign ){

            $br_tap4 = ($ka+1 == count($application->users_assign) ) && $ka > 2  ?' และ':'';

            $assign_name .=   $br_tap4.( array_key_exists( $users_assign->reg_intital , $perfix )?$perfix[ $users_assign->reg_intital ]:'' ).($users_assign->reg_fname.' '.$users_assign->reg_lname);
        }
        $templateProcessor->setValue('assign_name', ( !empty($assign_name)?$assign_name:'-' ) );


        $date_time =  date('His_dmY');

        $directory = storage_path('/Temp-file');

        if( !is_dir($directory) ){
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), array('.', '..'));

        foreach( $files as $filename){
            $time = date ("Y-m-d H:i:s", filemtime( $directory.'/'.$filename));

            $remain = intval( (strtotime(date("Y-m-d H:i:s")) - strtotime($time)) );
            $wan = floor($remain/86400); // วัน
            $l_wan = $remain%86400;
            $hour = floor($l_wan/3600); // ชั่วโมง
            $l_hour = $l_wan%3600;
            $minute = floor($l_hour/60);// นาที
            $second = $l_hour%60;

            if( round($minute) > 1 ){ // มากกว่า 10 นาที
                unlink( $directory.'/'.$filename );
            }

        }

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'สรุปรายงาน_ผู้ตรวจสอบผลิตภัณฑ์_'.$application->application_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'สรุปรายงาน_ผู้ตรวจสอบผลิตภัณฑ์_'.$application->application_no .'_'. $date_time  . '.docx'));
    }

    public function word_type_2( $application , $detail ,$app_summary ){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();

        $templateProcessor = new TemplateProcessor(public_path('/word/application_audit_type_2.docx'));

        $templateProcessor->setValue('meet_no', ( (!empty($app_summary->meeting_no)?$app_summary->meeting_no:null).'-'.(!empty($detail->meeting_no)?$detail->meeting_no:null) ) );
        $templateProcessor->setValue('meet_date', ( !empty($app_summary->meeting_date)?HP::DateThaiFull($app_summary->meeting_date):null ) );
        $templateProcessor->setValue('meet_type', ( !empty($detail->agenda_no)?$detail->agenda_no:null ) );

        $templateProcessor->setValue('applicant_name', ( !empty($application->applicant_name)?$application->applicant_name:null ) );
        $templateProcessor->setValue('lab_name', ( !empty($application->lab_name)?$application->lab_name:null ) );
        $templateProcessor->setValue('lab_address', ( !empty($application->LabDataAdress)?$application->LabDataAdress:null ) );

        $text_scope = '';
        $app_scope_standard = $application->app_scope_standard()->select('tis_id')->groupBy('tis_id')->get();
        $lab_scope_standard = $application->section5_labs_scope()->select('tis_id')->groupBy('tis_id')->get();
        $templateProcessor->setValue('count_std', ( !empty($application)?count($lab_scope_standard):null ) );

        $s = 0;
        foreach( $app_scope_standard AS $ks => $std ){

            $br_tap1 = ($s == count($app_scope_standard) ) || ($s == 0 )?'':' <w:br/>';

            if( !is_null( $std->tis_standards ) ){
                
                $s++;
                $text_scope .= $br_tap1.'('.$s.') ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($std->StandardTitle)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($std->StandardTisTisNo) );
            }

        }

        $templateProcessor->setValue('text_scope', ( !empty($text_scope)?$text_scope:null ) );

        
        $application_certificate = $application->application_certificate()->get();
        $text_cer = '';
        foreach( $application_certificate AS $kc => $cer ){

            $br_tap2 = ($kc+1 == count($application_certificate) )?'':' <w:br/>';

            $text_cer .= 'ใบรับรองเลขที่ '.($cer->certificate_no).'หมายเลขการรับรองที่ ทดสอบ '.($cer->accereditatio_no).' <w:br/>';
            $text_cer .= 'ออกให้ตั้งแต่วันที่ '.(HP::DateThaiFull($cer->certificate_start_date)).' ถึงวันที่ '.(HP::DateThaiFull($cer->certificate_end_date)). $br_tap2 ;

        }
        $templateProcessor->setValue('text_cer', ( !empty($text_cer)?$text_cer:'-' ) );

        $text_lab_scope = '-';
        if( !empty($application->section5_labs) && !empty( $application->section5_labs->scope_standard ) ){
            $section5_labs = $application->section5_labs;

            $scope_standard = $section5_labs->scope_standard()->select('tis_id')->groupBy('tis_id')->get();
            $text_lab_scope = '';
            $sl = 0;
            foreach( $scope_standard AS $kls => $Sitem ){
                $br_tap3 = ($sl == count($scope_standard) ) || ($sl == 0 )?'':' <w:br/>';

                if( !is_null($Sitem->tis_standards) ){
                    $sl++;
                    $text_lab_scope .=  $br_tap3.'('.$sl.') ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($Sitem->tis_standards->title)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($Sitem->tis_standards->tis_tisno) );
                }
            }

        }

        $templateProcessor->setValue('text_lab_scope', ( !empty($text_lab_scope)?$text_lab_scope:null ) );

        $app_audit = $application->app_audit;

        $audit_date_txet = '';
        if( !is_null($app_audit) && !empty($app_audit->audit_date) ){
            $audit_date = json_decode($app_audit->audit_date,true);

            foreach( $audit_date  AS $kad => $ADitem ){

                $br_tap1 = ($kad+1 == count($audit_date) ) || ($kad == 0 )?'':' <w:br/>';

                $audit_date_txet .= $br_tap1.'วันที่ตรวจประเมิน : '. HP::DateThaiFull($ADitem, true);
            }
        }
        $templateProcessor->setValue('audit_date_txet', ( !empty($audit_date_txet)?$audit_date_txet:'วันที่ตรวจประเมิน : -' ) );

        $perfix = [ 1 => 'นาย', 2 => 'นางสาว', 3 => 'นาง' ];
        $assign_name = '';
        $kas = 0;
        foreach( $application->users_assign AS $ka => $users_assign ){

            $br_tap2 = ($ka+1 == count($application->users_assign) ) || ($ka == 0 )?'':' <w:br/>';
            $kas++;
            $assign_name .= $br_tap2.'('.$kas.') '.( array_key_exists( $users_assign->reg_intital , $perfix )?$perfix[ $users_assign->reg_intital ]:'' ).($users_assign->reg_fname.' '.$users_assign->reg_lname).'   ผู้ตรวจประเมิน';
        }
        $templateProcessor->setValue('table_assign', ( !empty($assign_name)?$assign_name:'-' ) );

        
        $myFontStyle = array('name' => 'TH SarabunPSK', 'size' => 16);
        $myFontStyle2 = array('name' => 'TH SarabunPSK', 'size' => 16);
        $myParagraphStyle = array('align'=>'left', 'spaceBefore'=>50, 'spaceafter' => 50);
        $cellColSpan = array('gridSpan' => 6 );
        $cellRowSpan = array('vMerge' => 'continue');
        
        $table_report_scope = new Table(array('borderSize' => 0,'borderColor' => 'white', 'width' => 9000, 'unit' => TblWidth::TWIP));

        $s = 0;
        foreach( $app_scope_standard AS  $std ){

            $s++;

            $title = $s.' ผลิตภัณฑ์อุตสาหกรรม'.(htmlspecialchars($std->StandardTitle)).' มาตรฐานเลขที่ มอก. '.( htmlspecialchars($std->StandardTisTisNo) ).' (ทุกรายการ/เฉพาะรายการ)';
            $table_report_scope->addRow();
            $table_report_scope->addCell(9000, $cellColSpan)->addText( $title , $myFontStyle, $myParagraphStyle  );

            $styleCell_th1  = [ 'borderTopColor' =>'blank', 'borderBottomColor' =>'white', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];
            $styleCell_th2  = [ 'borderTopColor' =>'white', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5, 'vMerge' => 'continue' ];
            $styleCell      = [ 'borderTopColor' =>'blank', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];

            $cellColSpanStd = [ 'gridSpan' => 2 , 'borderTopColor' =>'blank', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];

            $myParagraphStd = array('align'=>'center', 'spaceBefore'=>50, 'spaceafter' => 50);

            //สร้าง ตารางรายการทดสอบ
            $table_report_scope->addRow();
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('ที่', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(3000, $styleCell_th1)->addText('รายการ', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('ประวัติการได้รับแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1000, $styleCell_th1)->addText('การขอรับการแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(2500, $cellColSpanStd)->addText('ผลการตรวจประเมิน', $myFontStyle2, $myParagraphStd  );

            $table_report_scope->addRow();
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(3000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell_th2 );
            $table_report_scope->addCell(1000, $styleCell)->addText('เสนอแต่งตั้ง', $myFontStyle2, $myParagraphStd  );
            $table_report_scope->addCell(1500, $styleCell)->addText('เหตุผล', $myFontStyle2, $myParagraphStd  );

            $scope_item = $this->GetDataTestItem( $std->tis_id, $application );

            $i = 0;
            $chek = [];
            foreach( $scope_item AS $items ){

                $table_report_scope->addRow();
                if( !array_key_exists( $items['main_topic_id'],  $chek ) ){
                    $i++;  
                    $table_report_scope->addCell(1000, $styleCell_th1)->addText( $i  , $myFontStyle2, $myParagraphStd  );
                    $chek[ $items['main_topic_id'] ] = $items['main_topic_id'];
                }else{
                    $table_report_scope->addCell(1000, $styleCell_th2 );  
                }
                $table_report_scope->addCell(3000, $styleCell)->addText( $items['text']         , $myFontStyle2, $myParagraphStyle  );
                $table_report_scope->addCell(1000, $styleCell)->addText( $items['old']          , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1000, $styleCell)->addText( $items['status']       , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1000,  $styleCell)->addText( $items['audit_result'] , $myFontStyle2, $myParagraphStd  );
                $table_report_scope->addCell(1500,  $styleCell)->addText( $items['remark']       , $myFontStyle2, $myParagraphStyle  );
        
            }

        }
        $templateProcessor->setComplexBlock('table_report_scope', $table_report_scope);

        $date_time =  date('His_dmY');

        $directory = storage_path('/Temp-file');

        if( !is_dir($directory) ){
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), array('.', '..'));

        foreach( $files as $filename){
            $time = date ("Y-m-d H:i:s", filemtime( $directory.'/'.$filename));

            $remain = intval( (strtotime(date("Y-m-d H:i:s")) - strtotime($time)) );
            $wan = floor($remain/86400); // วัน
            $l_wan = $remain%86400;
            $hour = floor($l_wan/3600); // ชั่วโมง
            $l_hour = $l_wan%3600;
            $minute = floor($l_hour/60);// นาที
            $second = $l_hour%60;

            if( round($minute) > 1 ){ // มากกว่า 10 นาที
                unlink( $directory.'/'.$filename );
            }

        }

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'สรุปรายงาน_ผู้ตรวจสอบผลิตภัณฑ์_'.$application->application_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'สรุปรายงาน_ผู้ตรวจสอบผลิตภัณฑ์_'.$application->application_no .'_'. $date_time  . '.docx'));


    }

    public function GetDataTestItem($tis_id, $application )
    {
        $test_item_id = $application->app_scope_standard()->where('tis_id', $tis_id  )->select('test_item_id')->pluck('test_item_id', 'test_item_id')->toArray();
        $scope_app    = $application->app_scope_standard()->where('tis_id', $tis_id  )->get()->keyBy('test_item_id')->toArray();
        $section5_labs_scope = $application->section5_labs_scope()->select('test_item_id')->pluck('test_item_id', 'test_item_id')->toArray();

        $test_item_id = array_merge( $test_item_id,  $section5_labs_scope );

        $orderby = "CAST(SUBSTRING_INDEX(no,'.',1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',2),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',3),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',4),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',5),'.',-1) as UNSIGNED)";

        $testitem = TestItem::Where('tis_id', $tis_id)->where('type',1)->groupBy('main_topic_id')->orderby(DB::raw($orderby))->get();
        $level = 0;

        
        $txt = [];
        $list =   $this->LoopItem($testitem , $level, $test_item_id, $scope_app, $section5_labs_scope  , $txt);

        return $list;

    }

    public function LoopItem($testitem , $level,  $test_item_id, $scope_app , $section5_labs_scope , $txt = [])
    {
        
        $txt = [];
        $level++;
        $i = 0;
        foreach ( $testitem as $key => $item ){

            //ผลตรวจ
            $result =  array_key_exists( $item->id,  $scope_app ) ? $scope_app[  $item->id ]:null;

            $audit_result = null;
            if( in_array( $item->id,  $test_item_id ) && array_key_exists( $item->id,  $scope_app )  ){
                $audit_result =  !empty($result['audit_result']) && ( $result['audit_result'] == 1) ?'/':'x';
            }else{
                $audit_result = in_array( $item->id,  $section5_labs_scope )?'⎯':null;
            }

            $txt[] = [ 
                        'text'          => ( !empty($item->no)?'ข้อ '.$item->no.' ':'('.(++$i).') ' ).$item->title, 
                        'remark'        => ( !empty($result['remark'])?$result['remark']:null ),
                        'audit_result'  => ( $audit_result ) ,
                        'old'           => ( in_array( $item->id,  $section5_labs_scope )?'/':null ),
                        'status'        => ( in_array( $item->id,  $test_item_id ) && array_key_exists( $item->id,  $scope_app )?'/':( in_array( $item->id,  $section5_labs_scope )?'⎯':null) ),
                        'main_topic_id' => ( $item->main_topic_id )
                    ];

            $result = $this->LoopItem($item->TestItemParentData, $level, $test_item_id, $scope_app, $section5_labs_scope  , $txt);

            if( count( $result) == 0 ){
                $txt = array_merge( $txt,  $result );

                if( !in_array( $item->id,  $test_item_id ) ){

                    // $last =  array_key_last($txt);

                    // unset( $txt[ $last ] );
                    // --$i;
                }
            }else{
                $txt = array_merge( $txt,  $result );
            }

        }
        return $txt;

    }
}
