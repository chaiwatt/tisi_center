<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
use App\Models\Section5\ApplicationIbcbBoardApprove;
use App\Models\Section5\ApplicationIbcbGazette;

use App\Models\Section5\Ibcbs;
use App\Models\Section5\IbcbsScope;
use App\Models\Section5\IbcbsScopeDetail;
use App\Models\Section5\IbcbsScopeTis;
use App\Models\Section5\IbcbsCertificate;
use App\Models\Section5\IbcbsInspectors;

use App\Models\Tis\Standard;

use App\Models\Elicense\RosUsers;
use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\Tis\RosStandardTisi;
use App\Models\Bsection5\WorkGroupIB;

use App\Models\Sso\User AS SSO_USER;

use App\Mail\Section5\ApplicationIBCBBoardApproveMail;
use Mail;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

use stdClass;

class ApplicationIbcbBoardApproveController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_ibcb_approve/';
        $this->attach_path_crop = 'tis_attach/application_ibcb_approve_crop/';
    }

    public function data_list(Request $request)
    {

        $filter_search =  $request->get('filter_search');
        $filter_status =  $request->get('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch = $request->input('filter_branch');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_board_meeting_result  = $request->input('filter_board_meeting_result');
        $filter_tisi_board_meeting_result    = $request->input('filter_tisi_board_meeting_result');

        $filter_gazette_start_date  = $request->input('filter_gazette_start_date');
        $filter_gazette_end_date    = $request->input('filter_gazette_end_date');

        $filter_board_meeting_start_date  = $request->input('filter_board_meeting_start_date');
        $filter_board_meeting_end_date    = $request->input('filter_board_meeting_end_date');

        $filter_tisi_board_meeting_start_date  = $request->input('filter_tisi_board_meeting_start_date');
        $filter_tisi_board_meeting_end_date    = $request->input('filter_tisi_board_meeting_end_date');

        $model = str_slug('application-ibcb-approve','-');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationIbcb::query()->with(['board_approve','ibcb_gazette','user_created'])
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
                            ->addColumn('application_no', function ($item) {
                                return $item->application_no.'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>';
                            })
                            ->addColumn('checkbox', function ($item) {
                                $board_meeting_date = !is_null($item->board_approve) && !empty($item->board_approve->board_meeting_date) ? $item->board_approve->board_meeting_date : '' ;
                                $board_approve = $item->board_approve; //ข้อมูลการอนุมัติ
                                $user_created  = $item->user_created;
                                $gazette       = $item->ibcb_gazette; //ข้อมูลประกาศราชกิจจานุเบกษา
                                $application_type_arr = [1 => 'IB', 2 => 'CB'];

                                return '<input type="checkbox"
                                               name="item_checkbox[]"
                                               class="item_checkbox"
                                               data-app_no="'. $item->application_no .'"
                                               data-application_status="'. $item->application_status .'"
                                               data-meeting_date="'. $board_meeting_date .'"
                                               data-meeting_date_txt="'. (!empty($item->board_approve->board_meeting_date) ? HP::DateThai($item->board_approve->board_meeting_date):null) .'"
                                               data-applicant_name="'. (!empty($item->applicant_name)?$item->applicant_name:'-') .'"
                                               data-board_approve_id="'. (!empty($board_approve->id)?$board_approve->id:'') .'"
                                               data-email="'. (!is_null($user_created) ? $user_created->email : '') .'"
                                               data-scope="'. (!empty($item->ScopeGroup)?$item->ScopeGroup:'-') .'"
                                               data-type="'.(array_key_exists( $item->application_type,  $application_type_arr )?$application_type_arr [ $item->application_type ]:'-').'"
                                               data-gazette_issue="'.(!is_null($gazette) ? $gazette->issue : '').'"
                                               data-gazette_announcement_date="'.(!is_null($gazette) ? HP::revertDate($gazette->announcement_date, true) : '').'"
                                               data-gazette_sign_id="'.(!is_null($gazette) ? $gazette->sign_id : '').'"
                                               data-gazette_year="'.(!is_null($gazette) ? $gazette->year : '').'"
                                               value="'. $item->id .'"
                                               >';
                            })
                            ->addColumn('applicant_name', function ($item) {
                                return (!empty($item->applicant_name)?$item->applicant_name:'-').'<div>('.(!empty($item->applicant_taxid)?$item->applicant_taxid:'-').')</div>';
                            })
                            ->addColumn('scope', function ($item) {
                                return !empty($item->ScopeGroup)?$item->ScopeGroup:'-';
                            })
                            ->addColumn('application_date', function ($item) {
                                return !empty($item->application_date)?HP::DateThai($item->application_date):'-';
                            })
                            ->addColumn('government_gazette_date', function ($item) {
                                $board_approve = $item->board_approve;
                                return !empty($board_approve->government_gazette_date)?HP::DateThai($board_approve->government_gazette_date):'รอดำเนินการ';
                            })
                            ->addColumn('status_application', function ($item) {
                                $arr = HP::ApplicationStatusIBCB();
                                return array_key_exists( $item->application_status,  $arr )?$arr [ $item->application_status ]:'-';
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->ListStaff)?$item->ListStaff:'รอดำเนินการ');
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
                            ->addColumn('action', function ($item) use($model) {
                                $btn = '';
                                if( auth()->user()->can('edit-'.$model) ){

                                    $btn = '';

                                    $board_approve = $item->board_approve;
                                    $ibcb_gazette = $item->ibcb_gazette;

                                    $btn =  ' <a href="'. url('section5/application-ibcb-board-approve/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                                    if( auth()->user()->can('edit-'.$model) ){

                                        if(in_array($item->application_status, [9, 10, 11])){
                                            $btn .= ' <a class="btn btn-primary btn-xs waves-effect waves-light" href="'. url('section5/application-ibcb-board-approve/approve/'.$item->id) .'"  title="บันทึกผล" ><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= ' <button type="button" class="btn btn-primary btn-xs waves-effect waves-light"  title="บันทึกผล" disabled><i class="fa fa-pencil" aria-hidden="true"></i></button>';
                                        }

                                        if(in_array($item->application_status, [11, 12, 13])){
                                            $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light" href="'. url('section5/application-ibcb-board-approve/tisi_approve/'.$item->id) .'"  title="ผลเสนอ กมอ."><i class="fa fa-edit" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= ' <button type="button" class="btn btn-warning btn-xs waves-effect waves-light" title="ผลเสนอ กมอ." disabled><i class="fa fa-edit" aria-hidden="true"></i></button>';
                                        }

                                        $btn .= ' <button type="button" class="btn btn-success btn-xs waves-effect waves-light btn_edit_gazette" title="จัดทำประกาศ" data-id="'.($item->id).'" '.(in_array($item->application_status, [13, 14, 15]) ? '' : 'disabled').'><i class="fa fa-book" aria-hidden="true"></i></button> ';

                                        // $btn .= ' <a class="btn btn-danger btn-xs waves-effect waves-light" href="'. url('section5/application-ibcb-board-approve/preview_document/'.$item->id) .'" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                                        if(!empty($board_approve->board_meeting_result) && $board_approve->board_meeting_result == 1){
                                            $btn .= ' <a class="btn btn-info btn-xs waves-effect waves-light" href="'. url('section5/application-ibcb-board-approve/gazette/'.$item->id) .'" title="บันทึกประกาศ" ><i class="fa fa-search" aria-hidden="true"></i></a>';
                                        }else{
                                            $btn .= ' <button type="button" class="btn btn-info btn-xs waves-effect waves-light" title="บันทึกประกาศ" disabled><i class="fa fa-search" aria-hidden="true"></i></button> ';
                                        }
                                    }

                                    return $btn;

                                }
                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action','applicant_name','application_no','result','tisi_result'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (IB/CB)' ],
            ];
            return view('section5.application-ibcb-board-approve.index',compact('breadcrumbs'));
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
        $model = str_slug('application-ibcb-approve','-');
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
        $model = str_slug('application-ibcb-approve','-');
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
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('add-'.$model)) {

            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->show  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-board-approve/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('section5.application-ibcb-board-approve.show', compact('applicationibcb','breadcrumbs'));

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
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('add-'.$model)) {

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
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('add-'.$model)) {

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
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('add-'.$model)) {

        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->approve  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-board-approve/approve/$id",  "name" => 'บันทึกผลเสนอคณะอนุกรรมการ' ],
            ];
            return view('section5.application-ibcb-board-approve.approve', compact('applicationibcb','breadcrumbs'));
        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationibcb = ApplicationIbcb::findOrFail($id);

        $approve = ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id )->first();

        if( is_null($approve) ){
            $approve = new ApplicationIbcbBoardApprove;
            $approve->created_by = auth()->user()->getKey();
        }else{
            $approve->updated_by = auth()->user()->getKey();
            $approve->updated_at = date('Y-m-d H:i:s');
        }
        $approve->application_id = $applicationibcb->id;
        $approve->application_no = $applicationibcb->application_no;
        $approve->board_meeting_result = !empty($requestData['board_meeting_result'])?$requestData['board_meeting_result']:null;
        $approve->board_meeting_date =  !empty($requestData['board_meeting_date'])?HP::convertDate($requestData['board_meeting_date'], true):null;
        $approve->board_meeting_description = !empty($requestData['board_meeting_description'])?$requestData['board_meeting_description']:null;
        $approve->save();

        if(  $approve->board_meeting_result  == 1 ){
            $applicationibcb->update(['application_status' => 11]);
        }else{
            $applicationibcb->update(['application_status' => 12]);
        }

        $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        $folder_app = ($applicationibcb->application_no).'/';

        if(isset($requestData['file_approve'])){
            if ($request->hasFile('file_approve')) {
                HP::singleFileUpload(
                    $request->file('file_approve') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationIbcbBoardApprove)->getTable() ),
                    $approve->id,
                    'file_application_ibcb_board_approve',
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
                        (  (new ApplicationIbcbBoardApprove)->getTable() ),
                        $approve->id,
                        'file_approve_other',
                        !empty($file['file_approve_documents'])?$file['file_approve_documents']:null
                    );
                }

            }

        }

        HP::LogInsertNotification(
            $applicationibcb->id ,
            ( (new ApplicationIbcb)->getTable() ),
            $applicationibcb->application_no,
            $applicationibcb->application_status,
            'บันทึกผลการเสนอพิจารณา IB/CB',
            null,
            'section5/application-ibcb-board-approve',
            $applicationibcb->created_by,
            1
        );

        HP::LogInsertNotification(
            $applicationibcb->id ,
            ( (new ApplicationIbcb)->getTable() ),
            $applicationibcb->application_no,
            $applicationibcb->application_status,
            'บันทึกผลการเสนอพิจารณา IB/CB : บันทึกผลเสนอคณะอนุกรรมการ',
            'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
            'section5/application-ibcb-board-approve/approve/'.$applicationibcb->id,
            auth()->user()->getKey(),
            4
        );

        return redirect('section5/application-ibcb-board-approve/approve/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
    }

    //หน้าบันทึกผลกมอ.
    public function tisi_approve($id)
    {
        $model = str_slug('application-ibcb-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->approve = true;
            $applicationibcb->tisi_approve = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-board-approve/tisi_approve/$id",  "name" => 'บันทึกผลเสนอกมอ.' ],
            ];
            return view('section5.application-ibcb-board-approve.tisi-approve', compact('applicationibcb','breadcrumbs'));
        }
        abort(403);
    }

    //บันทึกข้อมูลผลกมอ.
    public function tisi_approve_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationibcb = ApplicationIbcb::findOrFail($id);

        $approve = ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id)->first();

        if( is_null($approve) ){
            $approve = new ApplicationIbcbBoardApprove;
            $approve->created_by = auth()->user()->getKey();
        }else{
            $approve->updated_by = auth()->user()->getKey();
            $approve->updated_at = date('Y-m-d H:i:s');
        }
        $approve->application_id = $applicationibcb->id;
        $approve->application_no = $applicationibcb->application_no;
        $approve->tisi_board_meeting_result = !empty($requestData['tisi_board_meeting_result'])?$requestData['tisi_board_meeting_result']:null;
        $approve->tisi_board_meeting_date =  !empty($requestData['tisi_board_meeting_date'])?HP::convertDate($requestData['tisi_board_meeting_date'], true):null;
        $approve->tisi_board_meeting_description = !empty($requestData['tisi_board_meeting_description'])?$requestData['tisi_board_meeting_description']:null;
        $approve->save();

        if($approve->tisi_board_meeting_result == 1){
            $applicationibcb->update(['application_status' => 13]);//อยู่ระหว่างจัดทำประกาศ
        }else{
            $applicationibcb->update(['application_status' => 12]);//กมอ.ไม่อนุมัติ ตรวจสอบอีกครั้ง
        }

        $tax_number = !empty($applicationibcb->applicant_taxid) ? $applicationibcb->applicant_taxid : (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');

        $folder_app = ($applicationibcb->application_no).'/';

        if(isset($requestData['file_tisi_approve'])){
            if ($request->hasFile('file_tisi_approve')) {
                HP::singleFileUpload(
                    $request->file('file_tisi_approve') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new ApplicationIbcbBoardApprove)->getTable() ),
                    $approve->id,
                    'file_application_ibcbs_tisi_board_approve',
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
                        (  (new ApplicationIbcbBoardApprove)->getTable() ),
                        $approve->id,
                        'file_tisi_approve_other',
                        !empty($file['file_approve_documents'])?$file['file_approve_documents']:null
                    );
                }

            }

        }

        HP::LogInsertNotification(
            $applicationibcb->id ,
            ( (new ApplicationIbcb)->getTable() ),
            $applicationibcb->application_no,
            $applicationibcb->application_status,
            'ระบบผลการเสนอพิจารณาอนุมัติ (IBCB)',
            null,
            'section5/application-ibcb-board-approve',
            $applicationibcb->created_by,
            1
        );

        HP::LogInsertNotification(
            $applicationibcb->id ,
            ( (new ApplicationIbcb)->getTable() ),
            $applicationibcb->application_no,
            $applicationibcb->application_status,
            'บันทึกผลเสนอกมอ.',
            'ระบบผลการเสนอพิจารณาอนุมัติ (IBCB)',
            'section5/application-ibcb-board-approve/tisi_approve/'.$applicationibcb->id,
            auth()->user()->getKey(),
            4
        );

        return redirect('section5/application-ibcb-board-approve/tisi_approve/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
    }

    public function gazette($id)
    {
        $model = str_slug('application-lab-approve','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationibcb = ApplicationIbcb::findOrFail($id);
            $applicationibcb->gazette  = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-ibcb-board-approve",  "name" => 'ผลการเสนอพิจารณาอนุมัติ (IB/CB)' ],
                [ "link" => "/section5/application-ibcb-board-approve/gazette/$id",  "name" => 'บันทึกประกาศราชกิจจานุเบกษา' ],
            ];
            return view('section5.application-ibcb-board-approve.gazette', compact('applicationibcb','breadcrumbs'));
        }
        abort(403);
    }

    public function gazette_save(Request $request, $id)
    {
        $requestData = $request->all();

        $applicationibcb = ApplicationIbcb::findOrFail($id);

        $approve = ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id )->first();

        if( !is_null($approve) ){

            if( !empty($approve->government_gazette_date) ){
                $approve->government_gazette_updated_by = auth()->user()->getKey();
                $approve->government_gazette_updated_at = date('Y-m-d H:i:s');
            }else{
                $approve->government_gazette_created_by = auth()->user()->getKey();
                $approve->government_gazette_created_at = date('Y-m-d H:i:s');
            }

            $approve->government_gazette_date =  !empty($requestData['government_gazette_date'])?HP::convertDate($requestData['government_gazette_date'], true):null;
            $approve->start_date =  !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'], true):null;
            $approve->end_date =  !empty($requestData['end_date'])?HP::convertDate($requestData['end_date'], true):null;
            $approve->government_gazette_description = !empty($requestData['government_gazette_description'])?$requestData['government_gazette_description']:null;
            $approve->save();

            $applicationibcb->update(['application_status' => 15 ]);

            $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $folder_app = ($applicationibcb->application_no).'/';


            if(isset($requestData['file_gazette'])){
                if ($request->hasFile('file_gazette')) {
                    HP::singleFileUpload(
                        $request->file('file_gazette') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationIbcbBoardApprove)->getTable() ),
                        $approve->id,
                        'file_attach_government_gazette',
                        'เอกสารประกาศราชกิจจา'
                    );
                }
            }

            $this->GenIBCB( $applicationibcb , $approve);

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'บันทึกผลการเสนอพิจารณา IB/CB',
                null,
                'section5/application-ibcb-board-approve',
                $applicationibcb->created_by,
                1
            );

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'บันทึกผลการเสนอพิจารณา IB/CB : บันทึกประกาศราชกิจจานุเบกษา',
                'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                'section5/application-ibcb-board-approve/gazette/'.$applicationibcb->id,
                auth()->user()->getKey(),
                4
            );

        }

        return redirect('section5/application-ibcb-board-approve/gazette/'.$applicationibcb->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');

    }

    public function GenIBCB( $application , $approve)
    {

        $ibcb_start_date = !empty($approve->start_date)?$approve->start_date:null;
        $ibcb_end_date = !empty($approve->end_date)?$approve->end_date:null;

        $ibcbs = Ibcbs::where( 'ref_ibcb_application_no', $application->application_no )->first();

        if( is_null($ibcbs) ){

            $running_no = $this->GenNemberCode(  $application->application_type );
            $check = Ibcbs::where('ibcb_code', $running_no )->first();
            if(!empty($check)){
                $running_no = $this->GenNemberCode( $application->application_type );
            }
            $ibcbs = new Ibcbs;
            $ibcbs->ibcb_code = $running_no;
            $ibcbs->state = 1;
            $ibcbs->ref_ibcb_application_no = $application->application_no;
            $ibcbs->created_by = auth()->user()->getKey();
        }
        $ibcbs->ibcb_type       = !empty($application->application_type)?$application->application_type:null;
        $ibcbs->ibcb_name       = !empty($application->ibcb_name)?$application->ibcb_name:null;
        $ibcbs->ibcb_user_id    = !empty($application->created_by) ? $application->created_by : null ;
        $ibcbs->ibcb_start_date = $ibcb_start_date;
        $ibcbs->name            = !empty($application->applicant_name)?$application->applicant_name:null;
        $ibcbs->taxid           = !empty($application->applicant_taxid)?$application->applicant_taxid:null;

        //ข้อมูลที่อยู่
        $ibcbs->ibcb_address        = !empty($application->ibcb_address)?$application->ibcb_address:null;
        $ibcbs->ibcb_moo            = !empty($application->ibcb_moo)?$application->ibcb_moo:null;
        $ibcbs->ibcb_soi            = !empty($application->ibcb_soi)?$application->ibcb_soi:null;
        $ibcbs->ibcb_building       = !empty($application->ibcb_building)?$application->ibcb_building:null;
        $ibcbs->ibcb_road           = !empty($application->ibcb_road)?$application->ibcb_road:null;
        $ibcbs->ibcb_subdistrict_id = !empty($application->ibcb_subdistrict_id)?$application->ibcb_subdistrict_id:null;
        $ibcbs->ibcb_district_id    = !empty($application->ibcb_district_id)?$application->ibcb_district_id:null;
        $ibcbs->ibcb_province_id    = !empty($application->ibcb_province_id)?$application->ibcb_province_id:null;
        $ibcbs->ibcb_zipcode        = !empty($application->ibcb_zipcode)?$application->ibcb_zipcode:null;
        $ibcbs->ibcb_phone          = !empty($application->ibcb_phone)?$application->ibcb_phone:null;
        $ibcbs->ibcb_fax            = !empty($application->ibcb_fax)?$application->ibcb_fax:null;

        //ข้อมูลผู้ประสานงาน
        $ibcbs->co_name     = !empty($application->co_name)?$application->co_name:null;
        $ibcbs->co_position = !empty($application->co_position)?$application->co_position:null;
        $ibcbs->co_mobile   = !empty($application->co_mobile)?$application->co_mobile:null;
        $ibcbs->co_phone    = !empty($application->co_phone)?$application->co_phone:null;
        $ibcbs->co_fax      = !empty($application->co_fax)?$application->co_fax:null;
        $ibcbs->co_email    = !empty($application->co_email)?$application->co_email:null;
        $ibcbs->save();

        //ข้อมูล Scope
        $app_scope_group = ApplicationIbcbScope::where('application_id', $application->id )
                                                ->where(function($query) use($application){
                                                    $ids = DB::table((new ApplicationIbcbScopeDetail)->getTable().' AS detail')
                                                                ->leftJoin((new ApplicationIbcbScope)->getTable().' AS scope', 'scope.id', '=', 'detail.ibcb_scope_id')
                                                                ->where('scope.application_id', $application->id )
                                                                ->where('detail.audit_result', 1)
                                                                ->select('scope.id');

                                                    $query->whereIn('id', $ids);
                                                } )
                                                ->get();

        foreach(  $app_scope_group as $Igroup ){

            if( !empty( $Igroup->branch_group_id ) ){

                $scopes = IbcbsScope::where('ibcb_id', $ibcbs->id )
                                    ->where( function($query) use($Igroup){
                                        $query->where('branch_group_id', $Igroup->branch_group_id );
                                    })
                                    ->first();

                if( is_null($scopes) ){
                    $scopes = new IbcbsScope;
                    $scopes->created_by = auth()->user()->getKey();
                }else{
                    $scopes->updated_by = auth()->user()->getKey();
                    $scopes->updated_at = date('Y-m-d H:i:s');
                }
                $scopes->ibcb_id                 = $ibcbs->id;
                $scopes->ibcb_code               = $ibcbs->ibcb_code;
                $scopes->ref_ibcb_application_no = $application->application_no;
                $scopes->isic_no                 = !empty($Igroup->isic_no)?$Igroup->isic_no:null;
                $scopes->branch_group_id         = !empty($Igroup->branch_group_id)?$Igroup->branch_group_id:null;
                $scopes->state                   = 1;
                $scopes->start_date              = $ibcb_start_date;
                $scopes->end_date                = $ibcb_end_date;
                $scopes->save();

                //เอาเฉพาะสาขาที่ผ่าน
                $app_detail =  ApplicationIbcbScopeDetail::where('audit_result', 1)->where('ibcb_scope_id', $Igroup->id )->get();

                foreach( $app_detail AS $Idteil ){

                    if( !empty( $Idteil->branch_id ) ){
                        $detail = IbcbsScopeDetail::where('ibcb_scope_id', $scopes->id )
                                                    ->where( function($query) use($Idteil){
                                                        $query->where('branch_id', $Idteil->branch_id );
                                                    })
                                                    ->first();

                        if(is_null($detail)){
                            $detail = new IbcbsScopeDetail;
                        }

                        $detail->ibcb_scope_id = $scopes->id;
                        $detail->ibcb_id = $ibcbs->id;
                        $detail->ibcb_code = $ibcbs->ibcb_code;
                        $detail->branch_id = $Idteil->branch_id;
                        $detail->audit_result = !empty($Idteil->audit_result)?$Idteil->audit_result:null;
                        $detail->save();

                        //มันทึกขอบข่ายมอก.
                        $tis_list = $Idteil->ibcb_scopes_tis;
                        foreach($tis_list AS $tis_item){

                            $tis = $tis_item->tis_standards;

                            if(!is_null($tis)){

                                $scope_tis = IbcbsScopeTis::where('ibcb_scope_id', $scopes->id)->where('ibcb_scope_detail_id',$detail->id)->where('tis_id', $tis->getKey())->first();

                                if(is_null($scope_tis)){
                                    $scope_tis = new IbcbsScopeTis;
                                }
                                $scope_tis->ibcb_scope_id        = $scopes->id;
                                $scope_tis->ibcb_scope_detail_id = $detail->id;
                                $scope_tis->tis_id               = $tis->getKey();
                                $scope_tis->tis_no               = !empty($tis->tb3_Tisno)?$tis->tb3_Tisno:null;
                                $scope_tis->ibcb_code            = $ibcbs->ibcb_code;
                                $scope_tis->save();
                            }

                        }

                    }


                }

            }

        }

        //ข้อมูลผู้ตรวจประเมิน
        $app_inspectors = ApplicationIbcbInspectors::where('application_id', $application->id )->get();

        foreach( $app_inspectors AS $Iinspes ){

            if( !empty($Iinspes->inspector_id) ){
                $inspectors = IbcbsInspectors::where('ibcb_id', $ibcbs->id )->where('inspector_id', $Iinspes->inspector_id)->first();
                if(is_null($inspectors)){
                    $inspectors = new IbcbsInspectors;
                }

                $inspectors->ibcb_id = $ibcbs->id;
                $inspectors->ibcb_code = $ibcbs->ibcb_code;

                $inspectors->inspector_id            = $Iinspes->inspector_id;
                $inspectors->inspector_prefix        = !empty($Iinspes->inspector_prefix)?$Iinspes->inspector_prefix:null;
                $inspectors->inspector_first_name    = !empty($Iinspes->inspector_first_name)?$Iinspes->inspector_first_name:null;
                $inspectors->inspector_last_name     = !empty($Iinspes->inspector_last_name)?$Iinspes->inspector_last_name:null;
                $inspectors->inspector_taxid         = !empty($Iinspes->inspector_taxid)?$Iinspes->inspector_taxid:null;
                $inspectors->inspector_type          = !empty($Iinspes->inspector_type)?$Iinspes->inspector_type:null;
                $inspectors->ref_ibcb_application_no = $application->application_no;
                $inspectors->save();
            }

        }


        $app_certify = ApplicationIbcbCertify::where('application_id', $application->id )->get();
        foreach( $app_certify  AS $certify ){

            $cer = IbcbsCertificate::where('certificate_no', $certify->certificate_no )->where('issued_by', $certify->issued_by )->first();

            if( is_null( $cer ) ){
                $cer = new IbcbsCertificate;
            }

            $cer->ibcb_id   = $ibcbs->id;
            $cer->ibcb_code = $ibcbs->ibcb_code;

            $cer->certificate_std_id     = !empty($certify->certificate_std_id)?$certify->certificate_std_id:null;
            $cer->certificate_id         = !empty($certify->certificate_id)?$certify->certificate_id:null;
            $cer->certificate_no         = !empty($certify->certificate_no )?$certify->certificate_no :null;
            $cer->certificate_table      = !empty($certify->certificate_table)?$certify->certificate_table:null;
            $cer->certificate_start_date = !empty($certify->certificate_start_date)?$certify->certificate_start_date:null;
            $cer->certificate_end_date   = !empty($certify->certificate_end_date)?$certify->certificate_end_date:null;
            $cer->issued_by              = !empty($certify->issued_by)?$certify->issued_by:null;
            $cer->save();

        }


        /******** Update In e-License *******/
        //สร้างบัญชีผู้ใช้งาน
        $e_user = RosUsers::where('ibcb_code', $ibcbs->ibcb_code)->first();
        if(is_null($e_user)){//ไม่พบบัญชีจากรหัส
            $e_user = RosUsers::where('username', $ibcbs->ibcb_code)->first();
        }else{//พบบัญชีจากรหัส
            //ค้นหาเพื่อเช็คว่ามีบัญชีอื่นที่ username=ibcb_code แต่คนละบัญชี
            $e_user_temp = RosUsers::where('username', $ibcbs->ibcb_code)->first();
            if(!is_null($e_user_temp) && $e_user_temp->id!=$e_user->id){//พบบัญชี แต่ไม่ใช่บัญชีเดียวกับที่จะใช้อัพเดท
                $e_user_temp->username = $e_user_temp->username.'-'.str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
                $e_user_temp->save();
            }
        }

        if(is_null($e_user)){//ยังไม่มีบัญชีผู้ใช้งาน
            $user_sso = SSO_USER::find($ibcbs->ibcb_user_id);
            if(!is_null($user_sso)){
                $user_data = $user_sso->toArray();
                $user_columns = (new RosUsers)->Columns;//ชื่อคอลัมภ์ใน user elicense
                $user_columns = array_flip($user_columns);//สลับชื่อคอลัมภ์(value) มาเป็น key ของ Array;
                $user_data = array_intersect_key($user_data, $user_columns);//ตัดเอาเฉพาะฟิลด์ข้อมูลที่มีใน user elicense ไว้

                unset($user_data['id']); //ตัด id ออก
                $user_data['ibcb_code'] = $ibcbs->ibcb_code;//รหัส IBCB ที่ใช้อ้างอิง
                $user_data['username'] = $ibcbs->ibcb_code;//เปลี่ยน username ใช้รหัสห้อง Lab
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

                    $mail_format = new ApplicationIBCBBoardApproveMail([
                        'applicant_name'     => $application->applicant_name,
                        'application_no'     => $application->application_no,
                        'application_date'   => HP::DateThaiFull($application->application_date),
                        'start_date'         => HP::DateThaiFull($ibcbs->ibcb_start_date),
                        'url'                => $url,
                        'username'           => $user_data['username'],
                        'password'           => $password
                    ]);
                    Mail::to($user_data['email'])->send($mail_format);
                }
            }
        }else{//มีบัญชีผู้ใช้งานแล้ว

            $e_user->username  = $ibcbs->ibcb_code;
            $e_user->ibcb_code = $ibcbs->ibcb_code;
            $e_user->save();

            $user_id = $e_user->id;
        }

        if(isset($user_id)){//ได้ผู้ใช้งานในระบบ e-License
            //บันทึกกลุ่มผู้ใช้งาน
            RosUserGroupMap::where('user_id', $user_id)->where('group_id', 16)->delete();
            $group_map = new RosUserGroupMap;
            $group_map->user_id  = $user_id;
            $group_map->group_id = 16;
            $group_map->save();

            //อัพเดทข้อมูลมาตรฐานมอก. IB ที่สามารถตรวจสอบผลิตภัณฑ์ตามมาตรฐานนั้นๆได้
            $tis_numbers = $ibcbs->scope_standard_active()
                                 ->get()
                                 ->pluck('tis_no')
                                 ->toArray();
            $tis_numbers = array_unique($tis_numbers);
            foreach ($tis_numbers as $tis_number) {
                $standard = RosStandardTisi::where('tis_number', $tis_number)->first();
                if(!is_null($standard)){//ถ้าพบข้อมูลมาตรฐาน
                    $standard_ibs = array_values((array)json_decode($standard->for_ib_use, true));
                    $standard_ibs[] = (string)$user_id;
                    $standard->for_ib_use = json_encode(array_unique($standard_ibs));
                    $standard->save();
                }
            }
        }

    }

    public static function GenNemberCode($type){

        $Type =( $type == 1)? 'IB-':'CB-';
        $new_run = null;
        $list_code = Ibcbs::select('ibcb_code')->where('ibcb_code',  'LIKE', "%$Type%")->orderBy('ibcb_code')->pluck('ibcb_code')->toArray();

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

            $check = Ibcbs::where('ibcb_code', $new_run )->first();
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

    static function formatDateThai($strDate) {

        if(is_null($strDate) || $strDate == '' || $strDate == '-' ){
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("m", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $month = ['01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฎาคม', '08'=>'สิงหาคม', '09'=>'กันยายน', '10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม'];
        $strMonthThai = $month[$strMonth];
        return "  $strMonthThai พ.ศ. $strYear";
    }

    public function preview_document($id)
    {
        $application = ApplicationIbcb::where('id', $id)->first();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();

        $ibcb_scopes_ties = $application->ApplicationIbcbScopeTis;

        //นับมอก.ที่ผ่าน
        $amount = 0;
        foreach ($ibcb_scopes_ties as $ibcb_scopes_tie) {
            $detail = $ibcb_scopes_tie->application_ibcb_scope_detail;
            if(!is_null($detail) && $detail->audit_result==1){ //มีผลประเมิน และผ่านการประเมิน
                $amount++;
            }
        }

        $gazette = $application->ibcb_gazette;

        $templateProcessor = new TemplateProcessor(public_path('/word/application_ibcb_approve.docx'));

        $announcement_date =  $this->formatDateThai($gazette->announcement_date);

        $templateProcessor->setValue('issue', HP::toThaiNumber($gazette->issue));
        $templateProcessor->setValue('year', HP::toThaiNumber($gazette->year+543));

        if($application->audit_type=='1'){
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
        $templateProcessor->setValue('announcement_date', HP::toThaiNumber($announcement_date) );
        $templateProcessor->setValue('sign_name', $gazette->sign_name);
        $templateProcessor->setValue('sign_position', $gazette->sign_position);
        $templateProcessor->setValue('app_name', $application->applicant_name);
        $templateProcessor->setValue('amount', $amount);

        $templateProcessor->cloneRow('no', $amount);
        $i = 1;

        $scopes_group = $application->scopes_group()->with([
                                        'ibcb_scopes_tis' => function($query){
                                            $query->with([
                                                            'tis_standards'
                                                        ])
                                                        ->orderBy('id');
                                        }
                                    ])->get();

        foreach ($scopes_group as $application_ibcb) {
            $ibcb_scopes_tis = $application_ibcb->ibcb_scopes_tis()->with('tis_standards')->get();
            foreach ($ibcb_scopes_tis as $key => $item) {

                $detail = $item->application_ibcb_scope_detail;
                if(!is_null($detail) && $detail->audit_result==1){ //มีผลประเมิน และผ่านการประเมิน
                    $templateProcessor->setValue('no#'.$i, $i);
                    $templateProcessor->setValue('tis_no#'.$i, $item->tis_no);
                    $templateProcessor->setValue('tis_name#'.$i, $item->tis_name);
                    $i++;
                }
            }
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

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม_'.$application->application_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'จัดทำประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม_'.$application->application_no .'_'. $date_time  . '.docx'));
    }

    public function update_approve(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $application = ApplicationIbcb::whereIn('id',$arr_publish)->get();

        foreach( $application AS $item ){

            $approve = ApplicationIbcbBoardApprove::where('application_id', $item->id )->first();

            if( is_null($approve) ){
                $approve = new ApplicationIbcbBoardApprove;
                $approve->created_by = auth()->user()->getKey();
            }else{
                $approve->updated_by = auth()->user()->getKey();
                $approve->updated_at = date('Y-m-d H:i:s');
            }
            $approve->application_id = $item->id;
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
                        (  (new ApplicationIbcbBoardApprove)->getTable() ),
                        $approve->id,
                        'file_application_ibcb_board_approve',
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
                            (  (new ApplicationIbcbBoardApprove)->getTable() ),
                            $approve->id,
                            'file_approve_other',
                            !empty($file['m_file_approve_documents'])?$file['m_file_approve_documents']:null
                        );
                    }

                }

            }

            HP::LogInsertNotification(
                $item->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $item->application_no,
                $item->application_status,
                'บันทึกผลการเสนอพิจารณา IB/CB',
                null,
                'section5/application-ibcb-board-approve',
                $item->created_by,
                1
            );

            HP::LogInsertNotification(
                $item->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $item->application_no,
                $item->application_status,
                'บันทึกผลการเสนอพิจารณา IB/CB : บันทึกผลเสนอคณะอนุกรรมการ',
                'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                'section5/application-ibcb-board-approve/approve/'.$item->id,
                auth()->user()->getKey(),
                4
            );
        }

        return response()->json(['msg' => 'success' ]);
    }

    public function save_announcement(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $application = ApplicationIbcb::whereIn('id',$arr_publish)->get();

        foreach( $application AS $applicationibcb ){

            $gazetteData['application_id'] = $applicationibcb->id;
            $gazetteData['application_no'] = $applicationibcb->application_no;

            $gazetteData['issue'] = !empty($requestData['issue'])?($requestData['issue']):null;
            $gazetteData['year'] = !empty($requestData['year'])?($requestData['year']):null;
            $gazetteData['sign_id'] = !empty($requestData['sign_id'])?($requestData['sign_id']):null;
            $gazetteData['sign_name'] = !empty($requestData['sign_name'])? $requestData['sign_name']:null;
            $gazetteData['sign_position'] = !empty($requestData['sign_position'])? $requestData['sign_position']:null;
            $gazetteData['announcement_date'] = !empty($requestData['announcement_date'])?HP::convertDate($requestData['announcement_date'],true):null;

            $gazette = ApplicationIbcbGazette::where('application_id', $applicationibcb->id )->first();

            if( is_null($gazette) ){
                $gazetteData['created_by'] = auth()->user()->getKey();
                $gazette = ApplicationIbcbGazette::create($gazetteData);

            }else{
                $gazetteData['updated_by'] = auth()->user()->getKey();
                $gazetteData['updated_at'] = date('Y-m-d H:i:s');
                $gazette->update( $gazetteData );
            }

            //อัพเดทสถานะคำขอ
            $applicationibcb->application_status = 14;//จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ
            $applicationibcb->save();
        }

        return response()->json(['msg' => 'success']);

    }

    public function load_data_gazette($id)
    {
        $application = ApplicationIbcb::findOrFail($id);

        $gazette = $application->ibcb_gazette;

        $data = new stdClass;
        $data->id = $application->id;
        $data->application_no = $application->application_no;
        $data->applicant_name = $application->applicant_name;
        $data->applicant_taxid = $application->applicant_taxid;

        $data->issue = !empty($gazette->issue)?$gazette->issue:null;
        $data->year = !empty($gazette->year)?$gazette->year:null;
        $data->announcement_date = !empty($gazette->issue)?HP::revertDate($gazette->announcement_date,true):'-';
        $data->sign_id = !empty($gazette->sign_id)?$gazette->sign_id:null;
        $data->sign_position = !empty($gazette->sign_position)?$gazette->sign_position:null;
        $data->board_meeting_date = !is_null($application->board_approve) && !empty($application->board_approve->board_meeting_date) ? HP::revertDate($application->board_approve->board_meeting_date,true) : '-';

        return response()->json($data);
    }

    public function save_data_gazette(Request $request)
    {
        $requestData = $request->all();

        $id =  $requestData['id'];

        $application = ApplicationIbcb::findOrFail($id);

        $ibcb_gazette = $application->ibcb_gazette;
        $msg = 'error';

        if( !is_null($ibcb_gazette) ){

            $gazetteData['issue'] = !empty($requestData['issue'])?($requestData['issue']):null;
            $gazetteData['year'] = !empty($requestData['year'])?($requestData['year']):null;
            $gazetteData['sign_id'] = !empty($requestData['sign_id'])?($requestData['sign_id']):null;
            $gazetteData['sign_name'] = !empty($requestData['sign_name'])? $requestData['sign_name']:null;
            $gazetteData['sign_position'] = !empty($requestData['sign_position'])? $requestData['sign_position']:null;
            $gazetteData['announcement_date'] = !empty($requestData['announcement_date'])?HP::convertDate($requestData['announcement_date'],true):null;
            $gazetteData['created_by'] = auth()->user()->getKey();

            $gazette = ApplicationIbcbGazette::where('application_id', $application->id )->first();
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

            $gazette = ApplicationIbcbGazette::where('application_id', $application->id )->first();
            if( is_null($gazette) ){
                $gazetteData['created_by'] = auth()->user()->getKey();
                $gazette = ApplicationIbcbGazette::create($gazetteData);

            }else{
                $gazetteData['updated_by'] = auth()->user()->getKey();
                $gazetteData['updated_at'] = date('Y-m-d H:i:s');
                $gazette->update( $gazetteData );
            }

            //อัพเดทสถานะคำขอ
            $application->application_status = 14;//จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ
            $application->save();

            $msg = 'success';

        }

        return response()->json(['msg' => $msg ]);
    }

    public function update_board_approve(Request $request)
    {
        $requestData = $request->all();

        $arr_publish = $request->input('id');
        $application = ApplicationIbcb::whereIn('id',$arr_publish)->get();

        foreach( $application AS $applicationibcb ){
            $approve = ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id )->first();

            if( !is_null($approve) ){

                if( !empty($approve->government_gazette_date) ){
                    $approve->government_gazette_updated_by = auth()->user()->getKey();
                    $approve->government_gazette_updated_at = date('Y-m-d H:i:s');
                }else{
                    $approve->government_gazette_created_by = auth()->user()->getKey();
                    $approve->government_gazette_created_at = date('Y-m-d H:i:s');
                }

                $approve->government_gazette_date =  !empty($requestData['mb_government_gazette_date'])?HP::convertDate($requestData['mb_government_gazette_date'], true):null;
                $approve->start_date =  !empty($requestData['mb_ibcb_start_date'])?HP::convertDate($requestData['mb_ibcb_start_date'], true):null;
                $approve->end_date =  !empty($requestData['mb_ibcb_end_date'])?HP::convertDate($requestData['mb_ibcb_end_date'], true):null;
                $approve->government_gazette_description = !empty($requestData['mb_government_gazette_description'])?$requestData['mb_government_gazette_description']:null;
                $approve->save();

                $applicationibcb->update(['application_status' => 15 ]);

                $tax_number = !empty($applicationibcb->applicant_taxid )?$applicationibcb->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $folder_app = ($applicationibcb->application_no).'/';


                if(isset($requestData['mb_file_gazette'])){
                    if ($request->hasFile('mb_file_gazette')) {
                        HP::singleFileUpload(
                            $request->file('mb_file_gazette') ,
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ApplicationIbcbBoardApprove)->getTable() ),
                            $approve->id,
                            'file_attach_government_gazette',
                            'เอกสารประกาศราชกิจจา'
                        );
                    }
                }

                $this->GenIBCB( $applicationibcb , $approve);

                HP::LogInsertNotification(
                    $applicationibcb->id ,
                    ( (new ApplicationIbcb)->getTable() ),
                    $applicationibcb->application_no,
                    $applicationibcb->application_status,
                    'บันทึกผลการเสนอพิจารณา IB/CB',
                    null,
                    'section5/application-ibcb-board-approve',
                    $applicationibcb->created_by,
                    1
                );

                HP::LogInsertNotification(
                    $applicationibcb->id ,
                    ( (new ApplicationIbcb)->getTable() ),
                    $applicationibcb->application_no,
                    $applicationibcb->application_status,
                    'บันทึกผลการเสนอพิจารณา IB/CB : บันทึกประกาศราชกิจจานุเบกษา',
                    'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                    'section5/application-ibcb-board-approve/gazette/'.$applicationibcb->id,
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
        $application = ApplicationIbcb::whereIn('id',$arr_publish)->get();

        foreach( $application AS $applicationibcb ){

            $approve = ApplicationIbcbBoardApprove::where('application_id', $applicationibcb->id)->first();

            if( is_null($approve) ){
                $approve = new ApplicationIbcbBoardApprove;
                $approve->created_by = auth()->user()->getKey();
            }else{
                $approve->updated_by = auth()->user()->getKey();
                $approve->updated_at = date('Y-m-d H:i:s');
            }
            $approve->application_id = $applicationibcb->id;
            $approve->application_no = $applicationibcb->application_no;
            $approve->tisi_board_meeting_result = !empty($requestData['m_tisi_board_meeting_result'])?$requestData['m_tisi_board_meeting_result']:null;
            $approve->tisi_board_meeting_date =  !empty($requestData['m_tisi_board_meeting_date'])?HP::convertDate($requestData['m_tisi_board_meeting_date'], true):null;
            $approve->tisi_board_meeting_description = !empty($requestData['m_tisi_board_meeting_description'])?$requestData['m_tisi_board_meeting_description']:null;
            $approve->save();

            if($approve->tisi_board_meeting_result == 1){
                $applicationibcb->update(['application_status' => 13]);//อยู่ระหว่างจัดทำประกาศ
            }else{
                $applicationibcb->update(['application_status' => 12]);//กมอ.ไม่อนุมัติ ตรวจสอบอีกครั้ง
            }

            $tax_number = !empty($applicationibcb->applicant_taxid) ? $applicationibcb->applicant_taxid : (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');

            $folder_app = ($applicationibcb->application_no).'/';

            if(isset($requestData['m_file_tisi_approve'])){
                if ($request->hasFile('m_file_tisi_approve')) {
                    HP::singleFileUpload(
                        $request->file('m_file_tisi_approve') ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new ApplicationIbcbBoardApprove)->getTable() ),
                        $approve->id,
                        'file_application_ibcbs_tisi_board_approve',
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
                            (  (new ApplicationIbcbBoardApprove)->getTable() ),
                            $approve->id,
                            'file_tisi_approve_other',
                            !empty($file['m_file_tisi_approve_documents'])?$file['m_file_tisi_approve_documents']:null
                        );
                    }

                }

            }

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'ระบบผลการเสนอพิจารณาอนุมัติ (IBCB)',
                null,
                'section5/application-ibcb-board-approve',
                $applicationibcb->created_by,
                1
            );

            HP::LogInsertNotification(
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'บันทึกผลเสนอกมอ.',
                'ระบบผลการเสนอพิจารณาอนุมัติ (IBCB)',
                'section5/application-ibcb-board-approve/tisi_approve/'.$applicationibcb->id,
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

            $gazette = ApplicationIbcbGazette::where('year' , $filter_year )->whereNotNull('issue')->select(DB::raw('CAST(issue AS UNSIGNED) AS issue'))->orderBy('issue')->get();

            $gazette_last = $gazette->last();

            $_no = !is_null($gazette_last)?$gazette_last->issue:0;

            return ((int)$_no + 1);
        }else{
            $gazette = ApplicationIbcbGazette::where('year' , $filter_year )->where('issue' , $filter_issue )->first();
            return !is_null($gazette)?"true":"false";

        }


    }
}
