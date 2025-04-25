<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Section5\ApplicationLabStaff;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabAccept;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\ApplicationLabBoardApprove;
use App\Models\Section5\ApplicationLabGazette;
use App\Models\Section5\ApplicationLabGazetteDetail;
use App\Models\Section5\ApplicationLabCertificate;
use App\Models\Tis\Standard;
use App\Models\Bsection5\TestItem;
use App\Models\Bsection5\Workgroup;

use App\Models\Section5\Labs;
use App\Models\Section5\LabsScope;
use App\Models\Section5\LabsScopeDetail;
use App\Models\Section5\LabsCertify;

use App\Models\Elicense\RosUsers;
use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\Tis\RosStandardTisi;

use App\Models\Sso\User AS SSO_USER;

use App\Mail\Section5\ApplicationLabBoardApproveMail;
use Mail;

use PhpOffice\PhpWord\TemplateProcessor;
use stdClass;
use App\AttachFile;
use Illuminate\Support\Facades\Storage;

class ApplicationLabBoardApproveController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_lab_approve/';
        $this->attach_path_crop = 'tis_attach/application_lab_approve_crop/';
    }


    public function data_list(Request $request)
    {

        $model = str_slug('application-lab-approve','-');

        $filter_search     = $request->input('filter_search');
        $filter_status     = $request->input('filter_status');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_board_meeting_start_date  = $request->input('filter_board_meeting_start_date');
        $filter_board_meeting_end_date    = $request->input('filter_board_meeting_end_date');

        $filter_tisi_board_meeting_start_date  = $request->input('filter_tisi_board_meeting_start_date');
        $filter_tisi_board_meeting_end_date    = $request->input('filter_tisi_board_meeting_end_date');

        $filter_board_meeting_result       = $request->input('filter_board_meeting_result');
        $filter_tisi_board_meeting_result  = $request->input('filter_tisi_board_meeting_result');
        $filter_tis_id                     = $request->input('filter_tis_id');

        $filter_gazette_start_date  = $request->input('filter_gazette_start_date');
        $filter_gazette_end_date    = $request->input('filter_gazette_end_date');

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
                                        ->when( $filter_audit_type , function ($query, $filter_audit_type){
                                            return $query->where('audit_type', $filter_audit_type );
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
                                        ->when($filter_board_meeting_start_date, function ($query, $filter_board_meeting_start_date){
                                            $filter_board_meeting_start_date = HP::convertDate($filter_board_meeting_start_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_board_meeting_start_date){
                                                                $query->where('board_meeting_date', '<=', $filter_board_meeting_start_date);
                                                            });
                                        })
                                        ->when($filter_board_meeting_end_date, function ($query, $filter_board_meeting_end_date){
                                            $filter_board_meeting_end_date = HP::convertDate($filter_board_meeting_end_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_board_meeting_end_date){
                                                                $query->where('board_meeting_date', '<=', $filter_board_meeting_end_date);
                                                            });
                                        })
                                        ->when($filter_tisi_board_meeting_start_date, function ($query, $filter_tisi_board_meeting_start_date){
                                            $filter_tisi_board_meeting_start_date = HP::convertDate($filter_tisi_board_meeting_start_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_tisi_board_meeting_start_date){
                                                                $query->where('tisi_board_meeting_date', '<=', $filter_tisi_board_meeting_start_date);
                                                            });
                                        })
                                        ->when($filter_tisi_board_meeting_end_date, function ($query, $filter_tisi_board_meeting_end_date){
                                            $filter_tisi_board_meeting_end_date = HP::convertDate($filter_tisi_board_meeting_end_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_tisi_board_meeting_end_date){
                                                                $query->where('tisi_board_meeting_date', '<=', $filter_tisi_board_meeting_end_date);
                                                            });
                                        })
                                        ->when($filter_gazette_start_date, function ($query, $filter_gazette_start_date){
                                            $filter_gazette_start_date = HP::convertDate($filter_gazette_start_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_gazette_start_date){
                                                                $query->where('government_gazette_date', '>=', $filter_gazette_start_date);
                                                            });
                                        })
                                        ->when($filter_gazette_end_date, function ($query, $filter_gazette_end_date){
                                            $filter_gazette_end_date = HP::convertDate($filter_gazette_end_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_gazette_end_date){
                                                                $query->where('government_gazette_date', '<=', $filter_gazette_end_date);
                                                            });
                                        })
                                        ->when($filter_board_meeting_result, function ($query, $filter_board_meeting_result){
                                            if( $filter_board_meeting_result == '-1'){
                                                return $query->Has('board_approve','==',0)
                                                                ->OrwhereHas('board_approve', function($query) {
                                                                    $query->whereNull('board_meeting_result');
                                                                });
                                            }else{
                                                $query->whereHas('board_approve', function($query) use ($filter_board_meeting_result){
                                                    $query->where('board_meeting_result', $filter_board_meeting_result);
                                                });
                                            }
                                        })
                                        ->when($filter_tisi_board_meeting_result, function ($query, $filter_tisi_board_meeting_result){
                                            if( $filter_tisi_board_meeting_result == '-1'){
                                                return $query->Has('board_approve','==',0)
                                                            ->OrwhereHas('board_approve', function($query){
                                                                $query->whereNull('tisi_board_meeting_result');
                                                            });
                                            }else{
                                                $query->whereHas('board_approve', function($query) use ($filter_tisi_board_meeting_result){
                                                    $query->where('tisi_board_meeting_result', $filter_tisi_board_meeting_result);
                                                });
                                            }
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
                                        })
                                        ->with('board_approve');

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                $board_meeting_date = !is_null($item->board_approve) && !empty($item->board_approve->board_meeting_date) ? $item->board_approve->board_meeting_date : '' ;
                                $government_gazette = ($item->application_status == 99 )?$item->application_status:null;
                                $board_approve      = $item->board_approve;
                                $gazette            = !empty($item->app_gazette_details->app_gazette)?$item->app_gazette_details->app_gazette:null; //ข้อมูลประกาศราชกิจจานุเบกษา
                                $user_created       = $item->user_created;
                                $app_cer            = $item->application_certificate->max('certificate_end_date');


                                return '<input type="checkbox"
                                               name="item_checkbox[]"
                                               class="item_checkbox"
                                               data-app_no="'. $item->application_no .'"
                                               data-application_status="'. $item->application_status .'"
                                               data-meeting_date="'. $board_meeting_date .'"
                                               data-meeting_date_txt="'. (!empty($item->board_approve->board_meeting_date) ? HP::DateThai($item->board_approve->board_meeting_date):null) .'"
                                               data-standards="'. (!empty($item->ScopeStandard)?$item->ScopeStandard:'-') .'"
                                               data-applicant_name="'. (!empty($item->applicant_name)?$item->applicant_name:'-') .'"
                                               data-lab_name="'. (!empty($item->lab_name)?$item->lab_name:'-') .'"
                                               data-board_approve_id="'. (!empty($board_approve->id)?$board_approve->id:'') .'"
                                               data-email="'. (!is_null($user_created) ? $user_created->email : '') .'"
                                               data-gazette_issue="'.(!is_null($gazette) ? $gazette->issue : '').'"
                                               data-gazette_announcement_date="'.(!is_null($gazette) ? HP::revertDate($gazette->announcement_date, true) : '').'"
                                               data-gazette_sign_id="'.(!is_null($gazette) ? $gazette->sign_id : '').'"
                                               data-gazette_year="'.(!is_null($gazette) ? $gazette->year : '').'"
                                               data-audit_type="'. (!empty($item->audit_type)?$item->audit_type:'-') .'"
                                               data-certificate_end_date="'. (!empty($app_cer)?date('Y-m-d',strtotime($app_cer)):'') .'"
                                               value="'. $item->id .'"
                                               >';
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
                            ->addColumn('status_application', function ($item) {
                                return !empty($item->AppStatus)?$item->AppStatus:'ไม่มีสถานะ';
                            })
                            ->addColumn('result', function ($item) {
                                $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                                $board_approve = $item->board_approve;
                                $board_meeting_date = (!empty($board_approve->board_meeting_date)?'<div>('.HP::DateThai($board_approve->board_meeting_date).')</div>':null);
                                return (!empty($board_approve->board_meeting_result) &&  array_key_exists( $board_approve->board_meeting_result,  $arr ) ? $arr[$board_approve->board_meeting_result]:'รอดำเนินการ').$board_meeting_date;
                            })
                            ->addColumn('tisi_result', function ($item) {
                                $arr = [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
                                $board_approve = $item->board_approve;
                                $tisi_board_meeting_date = (!empty($board_approve->tisi_board_meeting_date)?'<div>('.HP::DateThai($board_approve->tisi_board_meeting_date).')</div>':null);
                                return (!empty($board_approve->tisi_board_meeting_result) &&  array_key_exists( $board_approve->tisi_board_meeting_result,  $arr ) ? $arr[$board_approve->tisi_board_meeting_result]:'รอดำเนินการ').$tisi_board_meeting_date;
                            })
                            ->addColumn('government_gazette_date', function ($item) {
                                $board_approve = $item->board_approve;
                                return !empty($board_approve->government_gazette_date)?HP::DateThai($board_approve->government_gazette_date):'รอดำเนินการ';
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                $board_approve = $item->board_approve;
                                $app_gazette_details = $item->app_gazette_details;

                                $btn =  ' <a href="'. url('section5/application-lab-board-approve/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                                if( auth()->user()->can('edit-'.$model) ){

                                    if(in_array($item->application_status, [9, 10, 11])){
                                        $btn .= ' <a class="btn btn-primary btn-xs waves-effect waves-light" href="'. url('section5/application-lab-board-approve/approve/'.$item->id) .'" title="บันทึกผลเสนอคณะอนุกรรมการ" ><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" title="บันทึกผลเสนอคณะอนุกรรมการ" disabled><i class="fa fa-pencil" aria-hidden="true"></i></button>';
                                    }

                                    if(in_array($item->application_status, [11, 12, 13])){
                                        $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light" href="'. url('section5/application-lab-board-approve/tisi_approve/'.$item->id) .'" title="บันทึกผลเสนอ กมอ."  ><i class="fa fa-edit" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-warning btn-xs waves-effect waves-light" title="บันทึกผลเสนอ กมอ."  disabled><i class="fa fa-edit" aria-hidden="true"></i></button>';
                                    }

                                    $btn .= ' <button type="button" class="btn btn-success btn-xs waves-effect waves-light btn_edit_gazette" title="จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม"  data-id="'.($item->id).'" '.(in_array(  $item->application_status ,[13,14,99] )? '' : 'disabled').'><i class="fa fa-book" aria-hidden="true"></i></button> ';

                                    if(!empty($board_approve->board_meeting_result) && $board_approve->board_meeting_result == 1){
                                        $btn .= ' <a class="btn btn-info btn-xs waves-effect waves-light" href="'. url('section5/application-lab-board-approve/gazette/'.$item->id) .'" title="บันทึกประกาศราชกิจจานุเบกษา" ><i class="fa fa-search" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-info btn-xs waves-effect waves-light" title="บันทึกประกาศราชกิจจานุเบกษา" disabled><i class="fa fa-search" aria-hidden="true"></i></button> ';
                                    }
                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by', 'applicant_name','application_no','result','tisi_result','standards'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-lab-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (LAB)' ],
            ];
            return view('section5.application-lab-board-approve.index',compact('breadcrumbs'));
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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {
            $applicationlab = ApplicationLab::findOrFail($id);
            $applicationlab->show  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-lab-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (LAB)' ],
                [ "link" => "/section5/application-lab-board-approve/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('section5.application-lab-board-approve.show', compact('applicationlab','breadcrumbs'));
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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationlab = ApplicationLab::findOrFail($id);
            $applicationlab->approve  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-lab-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (LAB)' ],
                [ "link" => "/section5/application-lab-board-approve/approve/$id",  "name" => 'บันทึกผลเสนอคณะอนุกรรมการ' ],

            ];
            return view('section5.application-lab-board-approve.approve', compact('applicationlab','breadcrumbs'));
        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationlab = ApplicationLab::findOrFail($id);

        $approve = ApplicationLabBoardApprove::where('app_id', $applicationlab->id )->first();

        if( is_null($approve) ){
            $approve = new ApplicationLabBoardApprove;
            $approve->created_by = auth()->user()->getKey();
        }else{
            $approve->updated_by = auth()->user()->getKey();
            $approve->updated_at = date('Y-m-d H:i:s');
        }
        $approve->app_id = $applicationlab->id;
        $approve->application_no = $applicationlab->application_no;
        $approve->board_meeting_result = !empty($requestData['board_meeting_result'])?$requestData['board_meeting_result']:null;
        $approve->board_meeting_date =  !empty($requestData['board_meeting_date'])?HP::convertDate($requestData['board_meeting_date'], true):null;
        $approve->board_meeting_description = !empty($requestData['board_meeting_description'])?$requestData['board_meeting_description']:null;
        $approve->save();

        if(  $approve->board_meeting_result  == 1 ){
            $applicationlab->update(['application_status' => 11]);
        }else{
            $applicationlab->update(['application_status' => 4]);
        }

        $tax_number = !empty($applicationlab->applicant_taxid )?$applicationlab->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        $folder_app = ($applicationlab->application_no).'/';

        if(isset($requestData['file_approve'])){
            if ($request->hasFile('file_approve')) {

                HP::singleFileUpload(
                    $request->file('file_approve') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationLabBoardApprove)->getTable() ),
                    $approve->id,
                    'file_application_labs_board_approve',
                    'เอกสารมติคณะอนุกรรมการ'
                );
            }
        }

        if( isset( $requestData['repeater-file-approve'] ) ){

            $repeater_file = $requestData['repeater-file-approve'];

            foreach( $repeater_file as $file ){

                if( isset($file['file_approve_other']) && !empty($file['file_approve_other']) ){
                    HP::singleFileUpload(
                        $file['file_approve_other'],
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabBoardApprove)->getTable() ),
                        $approve->id,
                        'file_approve_other',
                        !empty($file['file_approve_documents'])?$file['file_approve_documents']:null
                    );
                }

            }

        }

        HP::LogInsertNotification(
            $applicationlab->id ,
            ( (new ApplicationLab)->getTable() ),
            $applicationlab->application_no,
            $applicationlab->application_status,
            'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
            null,
            'section5/application-lab-board-approve',
            $applicationlab->created_by,
            1
        );

        HP::LogInsertNotification(
            $applicationlab->id ,
            ( (new ApplicationLab)->getTable() ),
            $applicationlab->application_no,
            $applicationlab->application_status,
            'บันทึกผลเสนอคณะอนุกรรมการ',
            'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
            'section5/application-lab-board-approve/approve/'.$applicationlab->id,
            auth()->user()->getKey(),
            4
        );

        return redirect('section5/application-lab-board-approve/approve/'.$applicationlab->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
    }

    //หน้าบันทึกผลกมอ.
    public function tisi_approve($id)
    {
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationlab = ApplicationLab::findOrFail($id);
            $applicationlab->approve = true;
            $applicationlab->tisi_approve = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-lab-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (LAB)' ],
                [ "link" => "/section5/application-lab-board-approve/tisi_approve/$id",  "name" => 'บันทึกผลเสนอกมอ.' ],

            ];
            return view('section5.application-lab-board-approve.tisi-approve', compact('applicationlab','breadcrumbs'));
        }
        abort(403);
    }

    //บันทึกข้อมูลผลกมอ.
    public function tisi_approve_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationlab = ApplicationLab::findOrFail($id);

        $approve = ApplicationLabBoardApprove::where('app_id', $applicationlab->id)->first();

        if( is_null($approve) ){
            $approve = new ApplicationLabBoardApprove;
            $approve->created_by = auth()->user()->getKey();
        }else{
            $approve->updated_by = auth()->user()->getKey();
            $approve->updated_at = date('Y-m-d H:i:s');
        }
        $approve->app_id = $applicationlab->id;
        $approve->application_no = $applicationlab->application_no;
        $approve->tisi_board_meeting_result = !empty($requestData['tisi_board_meeting_result'])?$requestData['tisi_board_meeting_result']:null;
        $approve->tisi_board_meeting_date =  !empty($requestData['tisi_board_meeting_date'])?HP::convertDate($requestData['tisi_board_meeting_date'], true):null;
        $approve->tisi_board_meeting_description = !empty($requestData['tisi_board_meeting_description'])?$requestData['tisi_board_meeting_description']:null;
        $approve->save();

        if($approve->tisi_board_meeting_result == 1){
            $applicationlab->update(['application_status' => 13]);//อยู่ระหว่างจัดทำประกาศ
        }else{
            $applicationlab->update(['application_status' => 12]);//กมอ.ไม่อนุมัติ ตรวจสอบอีกครั้ง
        }

        $tax_number = !empty($applicationlab->applicant_taxid) ? $applicationlab->applicant_taxid : (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');

        $folder_app = ($applicationlab->application_no).'/';

        if(isset($requestData['file_tisi_approve'])){
            if ($request->hasFile('file_tisi_approve')) {
                HP::singleFileUpload(
                    $request->file('file_tisi_approve') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationLabBoardApprove)->getTable() ),
                    $approve->id,
                    'file_application_labs_tisi_board_approve',
                    'เอกสารมติกมอ.'
                );
            }
        }

        if( isset( $requestData['repeater-file-tisi-approve'] ) ){

            $repeater_file = $requestData['repeater-file-tisi-approve'];

            foreach( $repeater_file as $file ){

                if( isset($file['file_approve_other']) && !empty($file['file_approve_other']) ){
                    HP::singleFileUpload(
                        $file['file_approve_other'],
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabBoardApprove)->getTable() ),
                        $approve->id,
                        'file_tisi_approve_other',
                        !empty($file['file_approve_documents'])?$file['file_approve_documents']:null
                    );
                }

            }

        }

        HP::LogInsertNotification(
            $applicationlab->id ,
            ( (new ApplicationLab)->getTable() ),
            $applicationlab->application_no,
            $applicationlab->application_status,
            'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
            null,
            'section5/application-lab-board-approve',
            $applicationlab->created_by,
            1
        );

        HP::LogInsertNotification(
            $applicationlab->id ,
            ( (new ApplicationLab)->getTable() ),
            $applicationlab->application_no,
            $applicationlab->application_status,
            'บันทึกผลเสนอกมอ.',
            'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
            'section5/application-lab-board-approve/tisi_approve/'.$applicationlab->id,
            auth()->user()->getKey(),
            4
        );

        return redirect('section5/application-lab-board-approve/tisi_approve/'.$applicationlab->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
    }

    public function gazette($id)
    {
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationlab = ApplicationLab::findOrFail($id);
            $applicationlab->gazette  = true;
            $applicationlab->show  = true;

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-lab-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (LAB)' ],
                [ "link" => "/section5/application-lab-board-approve/gazette/$id",  "name" => 'บันทึกประกาศราชกิจจานุเบกษา' ],

            ];

            return view('section5.application-lab-board-approve.gazette', compact('applicationlab','breadcrumbs'));
        }
        abort(403);
    }

    public function gazette_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationlab = ApplicationLab::findOrFail($id);

        $approve = ApplicationLabBoardApprove::where('app_id', $applicationlab->id )->first();

        if( !is_null($approve) ){

            if( !empty($approve->government_gazette_date) ){
                $approve->government_gazette_updated_by = auth()->user()->getKey();
                $approve->government_gazette_updated_at = date('Y-m-d H:i:s');
            }else{
                $approve->government_gazette_created_by = auth()->user()->getKey();
                $approve->government_gazette_created_at = date('Y-m-d H:i:s');
            }

            $approve->government_gazette_date =  !empty($requestData['government_gazette_date'])?HP::convertDate($requestData['government_gazette_date'], true):null;
            $approve->lab_start_date =  !empty($requestData['lab_start_date'])?HP::convertDate($requestData['lab_start_date'], true):null;
            $approve->lab_end_date =  !empty($requestData['lab_end_date'])?HP::convertDate($requestData['lab_end_date'], true):null;
            $approve->government_gazette_description = !empty($requestData['government_gazette_description'])?$requestData['government_gazette_description']:null;
            $approve->save();

            $applicationlab->update(['application_status' => 99 ]);

            $tax_number = !empty($applicationlab->applicant_taxid )?$applicationlab->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $folder_app = ($applicationlab->application_no).'/';


            if(isset($requestData['file_gazette'])){
                if ($request->hasFile('file_gazette')) {
                    HP::singleFileUpload(
                        $request->file('file_gazette') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabBoardApprove)->getTable() ),
                        $approve->id,
                        'file_attach_government_gazette',
                        'เอกสารประกาศราชกิจจา'
                    );
                }
            }

            $this->GenLabs($applicationlab, $approve);

            HP::LogInsertNotification(
                $applicationlab->id ,
                ( (new ApplicationLab)->getTable() ),
                $applicationlab->application_no,
                $applicationlab->application_status,
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                null,
                'section5/application-lab-board-approve',
                $applicationlab->created_by,
                1
            );

            HP::LogInsertNotification(
                $applicationlab->id ,
                ( (new ApplicationLab)->getTable() ),
                $applicationlab->application_no,
                $applicationlab->application_status,
                'บันทึกประกาศราชกิจจานุเบกษา',
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                'section5/application-lab-board-approve/gazette/'.$applicationlab->id,
                auth()->user()->getKey(),
                4
            );

        }

        return redirect('section5/application-lab-board-approve/gazette/'.$applicationlab->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');

    }


    public function GenLabs( $applicationlab , $approve )
    {

        $lab_start_date = !empty($approve->lab_start_date)?$approve->lab_start_date:null;
        $lab_end_date = !empty($approve->lab_end_date)?$approve->lab_end_date:null;

        //กรณีมี ID section5_labs id
        if( !empty($applicationlab->lab_id) ){
            $labs = Labs::where('id', $applicationlab->lab_id)->first();
        }else{
            $labs = Labs::where('ref_lab_application_no', $applicationlab->application_no)->first();
        }
        //กรณีไม่พบ section5_labs
        if(is_null($labs)){
            $running_no = $this->GenNemberLabCode();
            $check = Labs::where('lab_code', $running_no )->first();
            if(!empty($check)){
                $running_no = $this->GenNemberLabCode();
            }
            $labs                         = new Labs;
            $labs->lab_code               = $running_no;
            $labs->state                  = 1;
            $labs->ref_lab_application_no = $applicationlab->application_no;
            $labs->created_by             = auth()->user()->getKey();
        }

        $labs->lab_name           = !empty($applicationlab->lab_name)?$applicationlab->lab_name:null;
        $labs->lab_start_date     = $lab_start_date;
        $labs->name               = !empty($applicationlab->applicant_name)?$applicationlab->applicant_name:null;
        $labs->taxid              = !empty($applicationlab->applicant_taxid)?$applicationlab->applicant_taxid:null;
        //ข้อมูลที่อยู่
        $labs->lab_address        = !empty($applicationlab->lab_address)?$applicationlab->lab_address:null;
        $labs->lab_moo            = !empty($applicationlab->lab_moo)?$applicationlab->lab_moo:null;
        $labs->lab_soi            = !empty($applicationlab->lab_soi)?$applicationlab->lab_soi:null;
        $labs->lab_building       = !empty($applicationlab->lab_building)?$applicationlab->lab_building:null;
        $labs->lab_road           = !empty($applicationlab->lab_road)?$applicationlab->lab_road:null;
        $labs->lab_subdistrict_id = !empty($applicationlab->lab_subdistrict_id)?$applicationlab->lab_subdistrict_id:null;
        $labs->lab_district_id    = !empty($applicationlab->lab_district_id)?$applicationlab->lab_district_id:null;
        $labs->lab_province_id    = !empty($applicationlab->lab_province_id)?$applicationlab->lab_province_id:null;
        $labs->lab_zipcode        = !empty($applicationlab->lab_zipcode)?$applicationlab->lab_zipcode:null;
        $labs->lab_phone          = !empty($applicationlab->lab_phone)?$applicationlab->lab_phone:null;
        $labs->lab_fax            = !empty($applicationlab->lab_fax)?$applicationlab->lab_fax:null;
        //ข้อมูลผู้ประสานงาน
        $labs->co_name            = !empty($applicationlab->co_name)?$applicationlab->co_name:null;
        $labs->co_position        = !empty($applicationlab->co_position)?$applicationlab->co_position:null;
        $labs->co_mobile          = !empty($applicationlab->co_mobile)?$applicationlab->co_mobile:null;
        $labs->co_phone           = !empty($applicationlab->co_phone)?$applicationlab->co_phone:null;
        $labs->co_fax             = !empty($applicationlab->co_fax)?$applicationlab->co_fax:null;
        $labs->co_email           = !empty($applicationlab->co_email)?$applicationlab->co_email:null;
        $labs->lab_user_id        = $applicationlab->created_by; //id ผปก. sso_users
        $labs->save();

        $app_scope_group = ApplicationLabScope::where('application_lab_id', $applicationlab->id )
                                                ->where('audit_result', 1)
                                                ->select('tis_id', 'tis_tisno', 'test_item_id')
                                                ->groupBy('tis_id', 'tis_tisno', 'test_item_id')
                                                ->orderBy('tis_id')
                                                ->get();

        $remark_arr = ApplicationLabScope::where('application_lab_id', $applicationlab->id )->where('audit_result', 1 )->select( DB::raw("CONCAT_WS('_', tis_id, test_item_id) AS scope_keys"), 'remark')->pluck('remark', 'scope_keys')->toArray();

        foreach( $app_scope_group as $group ){

            $scopes = LabsScope::where('lab_id', $labs->id )
                                ->where( function($query) use($group){
                                    $query->where('tis_id', $group->tis_id )->where('test_item_id', $group->test_item_id );
                                })
                                ->first();

            if(is_null($scopes)){
                $scopes = new LabsScope;
            }
            $scopes->lab_id                 = $labs->id;
            $scopes->lab_code               = $labs->lab_code;
            $scopes->ref_lab_application_no = $applicationlab->application_no;
            $scopes->tis_id                 = !empty($group->tis_id)?$group->tis_id:null;
            $scopes->tis_tisno              = !empty($group->tis_tisno)?$group->tis_tisno:null;
            $scopes->test_item_id           = !empty($group->test_item_id)?$group->test_item_id:null;
            $scopes->state                  = 1;
            $scopes->start_date             = $lab_start_date;
            $scopes->end_date               = $lab_end_date;
            $scopes->test_item_id           = !empty($group->test_item_id)?$group->test_item_id:null;
            $scopes->remarks                = array_key_exists( ($group->tis_id.'_'.$group->test_item_id) ,  $remark_arr )?$remark_arr[$group->tis_id.'_'.$group->test_item_id]:null;
            $scopes->save();

            $app_scope = ApplicationLabScope::where('application_lab_id', $applicationlab->id )
                                            ->where('tis_id', $group->tis_id )
                                            ->where('test_item_id', $group->test_item_id )
                                            ->where('audit_result', 1)
                                            ->get();

            foreach( $app_scope as $item_scope ){

                $detail = LabsScopeDetail::where('lab_id', $labs->id )->where('ref_lab_application_scope_id', $item_scope->id )->first();
                if(is_null($detail)){
                    $detail = new LabsScopeDetail;
                }
                //Set ข้อมูล Section5 Labs
                $detail->lab_id                       = $labs->id;
                $detail->lab_code                     = $labs->lab_code;
                $detail->ref_lab_application_no       = $applicationlab->application_no;
                $detail->ref_lab_application_scope_id = $item_scope->id;
                $detail->lab_scope_id                 = !empty($scopes->id)?$scopes->id:null;
                //Set รายการทดสอบ
                $detail->test_tools_id                = !empty($item_scope->test_tools_id)?$item_scope->test_tools_id:null;
                $detail->test_tools_no                = !empty($item_scope->test_tools_no)?$item_scope->test_tools_no:null;
                $detail->capacity                     = !empty($item_scope->capacity)?$item_scope->capacity:null;
                $detail->range                        = !empty($item_scope->range)?$item_scope->range:null;
                $detail->true_value                   = !empty($item_scope->true_value)?$item_scope->true_value:null;
                $detail->fault_value                  = !empty($item_scope->fault_value)?$item_scope->fault_value:null;
                $detail->test_duration                = !empty($item_scope->test_duration)?$item_scope->test_duration:null;
                $detail->test_price                   = !empty($item_scope->test_price)?$item_scope->test_price:null;
                $detail->save();
            }

        }

        //Certify
        $app_certify = ApplicationLabCertificate::where('application_lab_id', $applicationlab->id )->get();
        foreach( $app_certify  AS $certify ){

            $cer = LabsCertify::where('application_labs_cer_id', $certify->id )->first();

            if( is_null( $cer ) ){
                $cer = new LabsCertify;
            }

            $cer->lab_id                  = $labs->id;
            $cer->lab_code                = $labs->lab_code;
            $cer->ref_lab_application_no  = $applicationlab->application_no;
            $cer->application_labs_cer_id = $applicationlab->id;

            $cer->certificate_id          = !empty($certify->certificate_id)?$certify->certificate_id:null;
            $cer->certificate_no          = !empty($certify->certificate_no )?$certify->certificate_no :null;
            $cer->accereditatio_no        = !empty($certify->accereditatio_no )?$certify->accereditatio_no :null;
            $cer->issued_by               = !empty($certify->issued_by)?$certify->issued_by:null;
            $cer->certificate_start_date  = !empty($certify->certificate_start_date)?$certify->certificate_start_date:null;
            $cer->certificate_end_date    = !empty($certify->certificate_end_date)?$certify->certificate_end_date:null;
            $cer->save();

        }


        /******** Update In e-License *******/
        //สร้างบัญชีผู้ใช้งาน
        $e_user = RosUsers::where('lab_code', $labs->lab_code)->first();
        if(is_null($e_user)){//ไม่พบบัญชีจากรหัส
            $e_user = RosUsers::where('username', $labs->lab_code)->first();
        }else{//พบบัญชีจากรหัส
            //ค้นหาเพื่อเช็คว่ามีบัญชีอื่นที่ username=lab_code แต่คนละบัญชีหรือไม่
            $e_user_temp = RosUsers::where('username', $labs->lab_code)->first();
            if(!is_null($e_user_temp) && $e_user_temp->id!=$e_user->id){//พบบัญชี แต่ไม่ใช่บัญชีเดียวกับที่จะใช้อัพเดท
                $e_user_temp->username = $e_user_temp->username.'-'.str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
                $e_user_temp->save();
            }
        }

        if(is_null($e_user)){//ยังไม่มีบัญชีผู้ใช้งาน
            $user_sso = SSO_USER::find($labs->lab_user_id);
            if(!is_null($user_sso)){
                $user_data = $user_sso->toArray();
                $user_columns = (new RosUsers)->Columns;//ชื่อคอลัมภ์ใน user elicense
                $user_columns = array_flip($user_columns);//สลับชื่อคอลัมภ์(value) มาเป็น key ของ Array;
                $user_data = array_intersect_key($user_data, $user_columns);//ตัดเอาเฉพาะฟิลด์ข้อมูลที่มีใน user elicense ไว้

                unset($user_data['id']); //ตัด id ออก
                $user_data['lab_code'] = $labs->lab_code;//รหัส Lab ที่ใช้อ้างอิง
                $user_data['username'] = $labs->lab_code;//เปลี่ยน username ใช้รหัสห้อง Lab
                $user_data['name']     = $labs->lab_name;//เปลี่ยน name ใช้ชื่อห้อง Lab
                $password              = uniqid();
                $user_data['password'] = Hash::make($password);//gen รหัสผ่าน
                $user_data['department_id'] = 0;
                $user_data['agency_tel'] = '';
                $user_data['authorize_data'] = '';

                //บันทึกบัญชี
                $user_id = RosUsers::insertGetId($user_data);

                //ส่งอีเมลแจ้งบัญชีผู้ใช้งาน
                if(!empty($user_data['email']) && filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)){

                    $config = HP::getConfig();
                    $urls   = property_exists($config, 'url_elicense_staff') ? explode('?', $config->url_elicense_staff) : null ;
                    $url    = is_array($urls) && count($urls) > 0 ? $urls[0] : null ;
                    $url    = filter_var($url, FILTER_VALIDATE_URL) ? '<a href="'.$url.'">'.$url.'</a>' : '<i>โปรดสอบถามเจ้าหน้าที่</i>' ;

                    $mail_format = new ApplicationLabBoardApproveMail([
                        'applicant_name'     => $applicationlab->applicant_name,
                        'lab_name'           => $applicationlab->lab_name,
                        'application_no'     => $applicationlab->application_no,
                        'start_date'         => HP::DateThaiFull($lab_start_date),
                        'end_date'           => HP::DateThaiFull($lab_end_date),
                        'url'                => $url,
                        'username'           => $user_data['username'],
                        'password'           => $password
                    ]);
                    Mail::to($user_data['email'])->send($mail_format);
                }
            }
        }else{//มีบัญชีผู้ใช้งานแล้ว

            $e_user->username = $labs->lab_code;
            $e_user->lab_code = $labs->lab_code;
            $e_user->save();

            $user_id = $e_user->id;
        }

        if(isset($user_id)){//ได้ผู้ใช้งานในระบบ e-License
            //บันทึกกลุ่มผู้ใช้งาน
            RosUserGroupMap::where('user_id', $user_id)->where('group_id', 15)->delete();
            $group_map = new RosUserGroupMap;
            $group_map->user_id  = $user_id;
            $group_map->group_id = 15;
            $group_map->save();

            //อัพเดทข้อมูลมาตรฐานมอก. Lab ที่สามารถทดสอบผลิตภัณฑ์ตามมาตรฐานนั้นๆได้
            $tis_numbers = array_unique($labs->scope_standard_active()->get()->pluck('tis_tisno')->toArray());
            foreach ($tis_numbers as $tis_number) {
                $standard = RosStandardTisi::where('tis_number', $tis_number)->first();
                $standard_labs = array_values((array)json_decode($standard->for_lab_use, true));
                $standard_labs[] = (string)$user_id;
                $standard->for_lab_use = json_encode(array_unique($standard_labs));
                $standard->save();
            }
        }


    }

    public static function GenNemberLabCode(){

        $Type = 'LAB-';
        $new_run = null;
        $list_code = Labs::select('lab_code')->where('lab_code',  'LIKE', "%$Type%")->orderBy('lab_code')->pluck('lab_code')->toArray();

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

            $check = Labs::where('lab_code', $new_run )->first();
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

    public function save_announcement(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');

        $applicationlab = ApplicationLab::whereIn('id', $arr_publish)->get();

        //ลบใบเดิม
        foreach($applicationlab as $labs){
            $check =  ApplicationLabGazetteDetail::where('app_lab_id', $labs->id  )->delete();
        }

        //ที่ไม่มีใบสมัคร
        ApplicationLabGazette::Has('gazette_detail','==',0)->delete();

        //สร้างใหม่
        $gazetteData['issue'] = !empty($requestData['issue'])?($requestData['issue']):null;
        $gazetteData['year'] = !empty($requestData['year'])?($requestData['year']):null;
        $gazetteData['sign_id'] = !empty($requestData['sign_id'])?($requestData['sign_id']):null;
        $gazetteData['sign_name'] = !empty($requestData['sign_name'])? $requestData['sign_name']:null;
        $gazetteData['sign_position'] = !empty($requestData['sign_position'])? $requestData['sign_position']:null;
        $gazetteData['announcement_date'] = !empty($requestData['announcement_date'])?HP::convertDate($requestData['announcement_date'],true):null;
        $gazetteData['created_by'] = auth()->user()->getKey();
        $gazette = ApplicationLabGazette::create($gazetteData);

        $app_lab_id = null;
        foreach($applicationlab as $labs){

            //อัพเดทสถานะใบสมัคร
            $labs->application_status = 14;//จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ
            $labs->save();

            $SubData['app_lab_id']     = $labs->id;
            $SubData['application_no'] = $labs->application_no;
            $SubData['app_gazette_id'] =  $gazette->id;

            $detail = ApplicationLabGazetteDetail::where('app_lab_id', $labs->id  )->where('app_gazette_id', $gazette->id  )->first();
            if( is_null($detail) ){
                $detail = ApplicationLabGazetteDetail::create($SubData);
            }else{
                $detail->update( $SubData );
            }

            $app_lab_id = $labs->id;

        }

        $url = url('/section5/application-lab-board-approve/word/'.$app_lab_id);

        return response()->json(['msg' => 'success', 'word' => $url  ]);
    }


    public function GenWord($id)
    {
        $gazette = ApplicationLabGazette::whereHas('gazette_detail', function ($query) use($id) {
                                            return $query->where('app_lab_id', $id);
                                        })
                                        ->first();

        $audit_type = $gazette->gazette_detail()->first()->app_lab()->first()->audit_type;

        $phpWord =  new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/AppGazetteLabs.docx'));

        $templateProcessor->setValue(
            [
                'issue',
                'year',
                'announcement_date',
                'sign',
                'sign_position',
                'labs_name'
            ],
            [
                !empty($gazette->issue)?HP::toThaiNumber($gazette->issue):'',
                !empty($gazette->year)?HP::toThaiNumber( (int)$gazette->year + 543 ):'',
                !empty($gazette->announcement_date)?HP::formatDateThaiFullPointNotDate($gazette->announcement_date):'',
                !empty($gazette->sign_name)?$gazette->sign_name:'',
                !empty($gazette->sign_position)?$gazette->sign_position:'',
                // !empty($applicationlab->lab_name)?$applicationlab->lab_name:'',

            ]
        );

        $list_scope_std = [];
        $list_app_std = [];

        $gazette_detail_app = $gazette->gazette_detail()->select('app_lab_id')->GroupBy('app_lab_id')->get();

        foreach(  $gazette_detail_app AS $item ){

            $app_lab = $item->app_lab;

            if( !is_null( $app_lab ) ){
                $app_scope_std = $app_lab->app_scope_standard()->select('tis_id')->GroupBy('tis_id')->get();

                $scope_txt = '';
                foreach ($app_scope_std as $itemS ) {

                    $tis_standards = $itemS->tis_standards;
                    $std_title = !is_null( $tis_standards )? $tis_standards->tb3_TisThainame:null;
                    $std_title .= !is_null( $tis_standards )?'<w:br/>(มอก. '. $tis_standards->tb3_Tisno.')':null;

                    $sub_std = new stdClass;
                    $sub_std->std_title = $std_title;

                    $html_address = '<w:br/>';
                    $html_address .= !empty($app_lab->lab_address)?'เลขที่ '.$app_lab->lab_address:'';
                    $html_address .= (!empty($app_lab->lab_moo) && $app_lab->lab_building!='-')?' หมู่ '.$app_lab->lab_moo:'';
                    $html_address .= !empty($app_lab->lab_soi)?' ซอย '.$app_lab->lab_soi:'';
                    $html_address .= !empty($app_lab->lab_road)?' ถนน '.$app_lab->lab_road:'';
                    $html_address .= (!empty($app_lab->lab_building) && $app_lab->lab_building!='-')?' อาคาร '.$app_lab->lab_building:'';
                    $html_address .= '<w:br/>';
                        if(!empty($app_lab->lab_province_id) && $app_lab->lab_province_id == 1){
                            $html_address .= !empty($app_lab->lab_subdistrict_id)?'แขวง'.trim($app_lab->LabSubdistrictName):'';
                            $html_address .= !empty($app_lab->lab_district_id)?' เขต'.trim($app_lab->LabDistrictName):'';
                            $html_address .= '<w:br/>';
                            $html_address .= !empty($app_lab->lab_province_id)?trim($app_lab->LabProvinceName):'';
                        }else{
                            $html_address .= !empty($app_lab->lab_subdistrict_id)?'ตำบล'.trim($app_lab->LabSubdistrictName):'';
                            $html_address .= !empty($app_lab->lab_district_id)?' อำเภอ'.trim($app_lab->LabDistrictName):'';
                            $html_address .= '<w:br/>';
                            $html_address .= !empty($app_lab->lab_province_id)?'จังหวัด'.trim($app_lab->LabProvinceName):'';
                        }
                    $html_address .= !empty($app_lab->lab_zipcode)?' '.$app_lab->lab_zipcode:'';
                    $sub_std->lab_name = $app_lab->lab_name.' '.$html_address;

                    $list_scope_std[] =  $sub_std;

                    $std_titles = !is_null( $tis_standards )? $tis_standards->tb3_TisThainame:null;
                    $std_titles .= !is_null( $tis_standards )?' มาตรฐานเลขที่ มอก.  '.( $tis_standards->tb3_Tisno ).(str_repeat('<w:t xml:space="preserve"> </w:t>', 3 )).'<w:t>เฉพาะรายการ</w:t>':null;

                    $scope_txt .= $std_titles.'<w:br/>';
                    $scope_txt .= $this->ListTestItem( $app_lab , $itemS->tis_id );

                }

                $appSub = new stdClass;
                $appSub->applicationlab = $app_lab;
                $appSub->lab_name = $app_lab->lab_name;
                $appSub->scope = $scope_txt;

                $list_app_std[] = $appSub;

            }

        }

        if($audit_type=='1'){
            $html_condition = '';
            $html_condition .= 'ทั้งนี้ ให้มีผลใช้บังคับตั้งแต่วันที่ประกาศในราชกิจจานุเบกษาเป็นต้นไป';
        }else{
            $html_condition = '';
            $html_condition .= 'ทั้งนี้ ผู้ตรวจสอบผลิตภัณฑ์อุตสาหกรรมตามประกาศนี้ให้มีผลใช้บังคับ ๓ ปี นับจาก';
            $html_condition .= '<w:br/>';
            $html_condition .= 'วันที่ประกาศในราชกิจจานุเบกษาเว้นแต่จะได้รับการรับรองความสามารถห้องปฏิบัติการตาม มอก. 17025';
            $html_condition .= '<w:br/>';
            $html_condition .= 'ในขอบข่ายที่ได้รับการแต่งตั้ง';
        }

        $templateProcessor->setValue('i_condition', $html_condition);

        $templateProcessor->cloneRow('i_no', count( $list_scope_std ));
        $i = 1;
        foreach ($list_scope_std as $item ) { //item is key

            $templateProcessor->setValue('i_no#'.$i, $i);
            $templateProcessor->setValue('i_testitem#'.$i, ($item->std_title));
            $templateProcessor->setValue('i_labs#'.$i, ($item->lab_name));
            $templateProcessor->setValue('i_remark#'.$i, ('ตรวจสอบเฉพาะรายการตาม<w:br/>รายละเอียดท้ายประกาศนี้'));

            $i++;
        }

        $templateProcessor->cloneRow('s_labs_name', count($list_app_std));
        $i = 1;
        foreach ($list_app_std as $item ) { //item is key

            $templateProcessor->setValue('s_labs_name#'.$i, $item->lab_name );
            $templateProcessor->setValue('s_item#'.$i, $item->scope );

            $i++;
        }

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

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม_' . $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม_' . $date_time  . '.docx'));
    }

    public function ListTestItem($applicationlab, $tis_id)
    {
        $test_item_id = $applicationlab->app_scope_standard()->where('tis_id', $tis_id )->where('audit_result',1)->select('test_item_id')->pluck('test_item_id', 'test_item_id')->toArray();

        $app_remark = ApplicationLabScope::where('application_lab_id', $applicationlab->id )->where('tis_id', $tis_id )->where('audit_result', 1 )->select( DB::raw("test_item_id AS scope_keys"), 'remark')->pluck('remark', 'scope_keys')->toArray();

        $testitem = TestItem::Where('tis_id', $tis_id)
                            ->where('type',1)
                            ->where( function($query) use($applicationlab, $tis_id){
                                $ids = DB::table((new ApplicationLabScope)->getTable().' AS scope')
                                            ->leftJoin((new TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                            ->where('scope.application_lab_id', $applicationlab->id )
                                            ->where('test.tis_id', $tis_id )
                                            ->select('test.main_topic_id');
                                $query->whereIn('id', $ids  );
                            })
                            // ->groupBy('main_topic_id')
                            ->orderby('no')
                            ->get();
        $level = 0;
        $result = $this->LoopItem($testitem, $app_remark, $level, $test_item_id );

        return  implode( '',collect($result)->pluck('text')->toArray());

    }

    public function LoopItem($testitem, $app_remark, $level, $test_item_id)
    {

        $txt = [];
        $level++;
        $i = 0;
        foreach ( $testitem as $key => $item ){

            $remark =  array_key_exists( $item->id,  $app_remark ) && !empty($app_remark[ $item->id ]) ?' (หมายเหตุ : '.$app_remark[ $item->id ].')':'';

            $txt[] = [ 'text' => str_repeat('<w:t xml:space="preserve"> </w:t>', ($level >= 2 ?$level * 4:4 )).( !empty($item->no)?'ข้อ '.$item->no.' ':'('.(++$i).') ' ).$item->title. $remark.'<w:br/>', 'status' => true ];

            $result = $this->LoopItem($item->TestItemParentData, $app_remark, $level, $test_item_id);

            if( count( $result) == 0 ){
                $txt = array_merge( $txt,  $result );

                if( !in_array( $item->id,  $test_item_id ) ){

                    $last =  array_key_last($txt);

                    unset( $txt[ $last ] );
                    --$i;
                }
            }else{
                $txt = array_merge( $txt,  $result );
            }

        }
        return $txt;

    }

    public function load_data_gazette($id)
    {
        $applicationlab = ApplicationLab::findOrFail($id);

        $app_gazette_details = $applicationlab->app_gazette_details;
        $gazette = !is_null($app_gazette_details)? $app_gazette_details->app_gazette:null;

        $data = new stdClass;
        $data->id = $applicationlab->id;
        $data->application_no = $applicationlab->application_no;
        $data->applicant_name = $applicationlab->applicant_name;
        $data->applicant_taxid = $applicationlab->applicant_taxid;
        $data->standards = $applicationlab->ScopeStandard;

        $data->issue = !empty($gazette->issue)?$gazette->issue:null;
        $data->year               = !empty($gazette->year)?$gazette->year:null;
        $data->announcement_date  = !empty($gazette->issue)?HP::revertDate($gazette->announcement_date,true):null;
        $data->sign_id            = !empty($gazette->sign_id)?$gazette->sign_id:null;
        $data->sign_position      = !empty($gazette->sign_position)?$gazette->sign_position:null;
        $data->board_meeting_date = !is_null($applicationlab->board_approve) && !empty($applicationlab->board_approve->board_meeting_date) ? HP::revertDate($applicationlab->board_approve->board_meeting_date,true) : '-';
        $data->gazette            = !is_null($app_gazette_details)?true:false;

        return response()->json($data);
    }

    public function save_data_gazette(Request $request)
    {
        $requestData = $request->all();

        $id =  $requestData['id'];

        $labs = ApplicationLab::findOrFail($id);

        $app_gazette_details = $labs->app_gazette_details;
        $msg = 'error';

        if( !is_null($app_gazette_details) && !is_null($app_gazette_details->app_gazette) ){
            $gazetteData['issue'] = !empty($requestData['issue'])?($requestData['issue']):null;
            $gazetteData['year'] = !empty($requestData['year'])?($requestData['year']):null;
            $gazetteData['sign_id'] = !empty($requestData['sign_id'])?($requestData['sign_id']):null;
            $gazetteData['sign_name'] = !empty($requestData['sign_name'])? $requestData['sign_name']:null;
            $gazetteData['sign_position'] = !empty($requestData['sign_position'])? $requestData['sign_position']:null;
            $gazetteData['announcement_date'] = !empty($requestData['announcement_date'])?HP::convertDate($requestData['announcement_date'],true):null;
            $gazetteData['created_by'] = auth()->user()->getKey();

            $gazette = ApplicationLabGazette::where('id', $app_gazette_details->app_gazette_id  )->first();
            $gazette->update( $gazetteData );
            $msg = 'success';

        }else{

            $gazetteData['issue'] = !empty($requestData['issue'])?($requestData['issue']):null;
            $gazetteData['year'] = !empty($requestData['year'])?($requestData['year']):null;
            $gazetteData['sign_id'] = !empty($requestData['sign_id'])?($requestData['sign_id']):null;
            $gazetteData['sign_name'] = !empty($requestData['sign_name'])? $requestData['sign_name']:null;
            $gazetteData['sign_position'] = !empty($requestData['sign_position'])? $requestData['sign_position']:null;
            $gazetteData['announcement_date'] = !empty($requestData['announcement_date'])?HP::convertDate($requestData['announcement_date'],true):null;
            $gazetteData['created_by'] = auth()->user()->getKey();
            $gazette = ApplicationLabGazette::create($gazetteData);

            //อัพเดทสถานะใบสมัคร
            $labs->application_status = 14;//จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ
            $labs->save();

            $SubData['app_lab_id'] = $labs->id;
            $SubData['application_no'] = $labs->application_no;
            $SubData['app_gazette_id'] =  $gazette->id;

            $detail = ApplicationLabGazetteDetail::where('app_lab_id', $labs->id  )->where('app_gazette_id', $gazette->id  )->first();
            if( is_null($detail) ){
                $detail = ApplicationLabGazetteDetail::create($SubData);
            }else{
                $detail->update( $SubData );
            }
            $msg = 'success';
        }

        return response()->json(['msg' => $msg ]);

    }

    public function update_approve(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $applicationlab = ApplicationLab::whereIn('id',$arr_publish)->get();

        foreach( $applicationlab AS $item ){

            $approve = ApplicationLabBoardApprove::where('app_id', $item->id )->first();
            if( is_null($approve) ){
                $approve = new ApplicationLabBoardApprove;
                $approve->created_by = auth()->user()->getKey();
            }else{
                $approve->updated_by = auth()->user()->getKey();
                $approve->updated_at = date('Y-m-d H:i:s');
            }
            $approve->app_id = $item->id;
            $approve->application_no = $item->application_no;
            $approve->board_meeting_result = !empty($requestData['m_board_meeting_result'])?$requestData['m_board_meeting_result']:null;
            $approve->board_meeting_date =  !empty($requestData['m_board_meeting_date'])?HP::convertDate($requestData['m_board_meeting_date'], true):null;
            $approve->board_meeting_description = !empty($requestData['m_board_meeting_description'])?$requestData['m_board_meeting_description']:null;
            $approve->save();

            if(  $approve->board_meeting_result  == 1 ){
                $item->update(['application_status' => 11]);
            }else{
                $item->update(['application_status' => 12]);
            }

            $tax_number = !empty($item->applicant_taxid )?$item->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            $folder_app = ($item->application_no).'/';

            if(isset($requestData['m_file_approve'])){
                if ($request->hasFile('m_file_approve')) {
                    HP::singleFileUpload(
                        $request->file('m_file_approve') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabBoardApprove)->getTable() ),
                        $approve->id,
                        'file_application_labs_board_approve',
                        'เอกสารมติคณะอนุกรรมการ'
                    );
                }
            }

            if( isset( $requestData['repeater-file-approve'] ) ){

                $repeater_file = $requestData['repeater-file-approve'];

                foreach( $repeater_file as $file ){

                    if( isset($file['m_file_approve_other']) && !empty($file['m_file_approve_other']) ){
                        HP::singleFileUpload(
                            $file['m_file_approve_other'],
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationLabBoardApprove)->getTable() ),
                            $approve->id,
                            'file_approve_other',
                            !empty($file['m_file_approve_documents'])?$file['m_file_approve_documents']:null
                        );
                    }

                }

            }

            HP::LogInsertNotification(
                $item->id ,
                ( (new ApplicationLab)->getTable() ),
                $item->application_no,
                $item->application_status,
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                null,
                'section5/application-lab-board-approve',
                $item->created_by,
                1
            );

            HP::LogInsertNotification(
                $item->id ,
                ( (new ApplicationLab)->getTable() ),
                $item->application_no,
                $item->application_status,
                'บันทึกผลเสนอคณะอนุกรรมการ',
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                'section5/application-lab-board-approve/approve/'.$item->id,
                auth()->user()->getKey(),
                4
            );
        }

        return response()->json(['msg' => 'success' ]);
    }

    public function update_board_approve(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $result = ApplicationLab::whereIn('id',$arr_publish)->get();

        foreach( $result AS $applicationlab ){
            $approve = ApplicationLabBoardApprove::where('app_id', $applicationlab->id )->first();

            if( !is_null($approve) ){

                if( !empty($approve->government_gazette_date) ){
                    $approve->government_gazette_updated_by = auth()->user()->getKey();
                    $approve->government_gazette_updated_at = date('Y-m-d H:i:s');
                }else{
                    $approve->government_gazette_created_by = auth()->user()->getKey();
                    $approve->government_gazette_created_at = date('Y-m-d H:i:s');
                }

                $approve->government_gazette_date =  !empty($requestData['mb_government_gazette_date'])?HP::convertDate($requestData['mb_government_gazette_date'], true):null;
                $approve->lab_start_date =  !empty($requestData['mb_lab_start_date'])?HP::convertDate($requestData['mb_lab_start_date'], true):null;
                $approve->lab_end_date =  !empty($requestData['mb_lab_end_date'])?HP::convertDate($requestData['mb_lab_end_date'], true):null;
                $approve->government_gazette_description = !empty($requestData['mb_government_gazette_description'])?$requestData['mb_government_gazette_description']:null;
                $approve->save();

                $applicationlab->update(['application_status' => 99 ]);

                $tax_number = !empty($applicationlab->applicant_taxid )?$applicationlab->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $folder_app = ($applicationlab->application_no).'/';

                if(isset($requestData['mb_file_gazette'])){
                    if ($request->hasFile('mb_file_gazette')) {
                        HP::singleFileUpload(
                            $request->file('mb_file_gazette') ,
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationLabBoardApprove)->getTable() ),
                            $approve->id,
                            'file_attach_government_gazette',
                            'เอกสารประกาศราชกิจจา'
                        );
                    }
                }

                $this->GenLabs( $applicationlab , $approve);

                HP::LogInsertNotification(
                    $applicationlab->id ,
                    ( (new ApplicationLab)->getTable() ),
                    $applicationlab->application_no,
                    $applicationlab->application_status,
                    'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                    null,
                    'section5/application-lab-board-approve',
                    $applicationlab->created_by,
                    1
                );

                HP::LogInsertNotification(
                    $applicationlab->id ,
                    ( (new ApplicationLab)->getTable() ),
                    $applicationlab->application_no,
                    $applicationlab->application_status,
                    'บันทึกประกาศราชกิจจานุเบกษา',
                    'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                    'section5/application-lab-board-approve/gazette/'.$applicationlab->id,
                    auth()->user()->getKey(),
                    4
                );

            }


        }

        return response()->json(['msg' => 'success' ]);
    }

    public function update_tisi_approve(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $result = ApplicationLab::whereIn('id',$arr_publish)->get();

        foreach( $result AS $applicationlab ){

            $approve = ApplicationLabBoardApprove::where('app_id', $applicationlab->id)->first();

            if( is_null($approve) ){
                $approve = new ApplicationLabBoardApprove;
                $approve->created_by = auth()->user()->getKey();
            }else{
                $approve->updated_by = auth()->user()->getKey();
                $approve->updated_at = date('Y-m-d H:i:s');
            }
            $approve->app_id                         = $applicationlab->id;
            $approve->application_no                 = $applicationlab->application_no;
            $approve->tisi_board_meeting_result      = !empty($requestData['m_tisi_board_meeting_result'])?$requestData['m_tisi_board_meeting_result']:null;
            $approve->tisi_board_meeting_date        = !empty($requestData['m_tisi_board_meeting_date'])?HP::convertDate($requestData['m_tisi_board_meeting_date'], true):null;
            $approve->tisi_board_meeting_description = !empty($requestData['m_tisi_board_meeting_description'])?$requestData['m_tisi_board_meeting_description']:null;
            $approve->save();

            if($approve->tisi_board_meeting_result == 1){
                $applicationlab->update(['application_status' => 13]);//อยู่ระหว่างจัดทำประกาศ
            }else{
                $applicationlab->update(['application_status' => 12]);//กมอ.ไม่อนุมัติ ตรวจสอบอีกครั้ง
            }

            $tax_number = !empty($applicationlab->applicant_taxid) ? $applicationlab->applicant_taxid : (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');

            $folder_app = ($applicationlab->application_no).'/';

            if(isset($requestData['m_file_tisi_approve'])){
                if ($request->hasFile('m_file_tisi_approve')) {
                    HP::singleFileUpload(
                        $request->file('m_file_tisi_approve') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationLabBoardApprove)->getTable() ),
                        $approve->id,
                        'file_application_labs_tisi_board_approve',
                        'เอกสารมติกมอ.'
                    );
                }
            }

            if( isset( $requestData['repeater-file-tisi-approve'] ) ){

                $repeater_file = $requestData['repeater-file-tisi-approve'];

                foreach( $repeater_file as $file ){

                    if( isset($file['m_file_tisi_approve_other']) && !empty($file['m_file_tisi_approve_other']) ){
                        HP::singleFileUpload(
                            $file['m_file_tisi_approve_other'],
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationLabBoardApprove)->getTable() ),
                            $approve->id,
                            'file_tisi_approve_other',
                            !empty($file['m_file_tisi_approve_documents'])?$file['m_file_tisi_approve_documents']:null
                        );
                    }

                }

            }

            HP::LogInsertNotification(
                $applicationlab->id ,
                ( (new ApplicationLab)->getTable() ),
                $applicationlab->application_no,
                $applicationlab->application_status,
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                null,
                'section5/application-lab-board-approve',
                $applicationlab->created_by,
                1
            );

            HP::LogInsertNotification(
                $applicationlab->id ,
                ( (new ApplicationLab)->getTable() ),
                $applicationlab->application_no,
                $applicationlab->application_status,
                'บันทึกผลเสนอกมอ.',
                'ระบบผลการเสนอพิจารณาอนุมัติ (LAB)',
                'section5/application-lab-board-approve/tisi_approve/'.$applicationlab->id,
                auth()->user()->getKey(),
                4
            );

        }
        return response()->json(['msg' => 'success' ]);
    }

    public function getIssueGazette(Request $request)
    {

        $filter_year =  $request->get('year');
        $filter_issue =  $request->get('issue');

        $type =  $request->get('type');


        if(  $type == 'get'){

            $gazette = ApplicationLabGazette::where('year' , $filter_year )->whereNotNull('issue')->select(DB::raw('CAST(issue AS UNSIGNED) AS issue'))->orderBy('issue')->get();

            $gazette_last = $gazette->last();

            $_no = !is_null($gazette_last)?$gazette_last->issue:0;

            return ((int)$_no + 1);
        }else{
            $gazette = ApplicationLabGazette::where('year' , $filter_year )->where('issue' , $filter_issue )->first();
            return !is_null($gazette)?"true":"false";

        }


    }
}
