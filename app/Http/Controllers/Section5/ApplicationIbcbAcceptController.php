<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Section5\ApplicationIbcbStaff;
use App\Models\Section5\ApplicationIbcb;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\ApplicationIbcbScopeDetail;
use App\Models\Section5\ApplicationIbcbAccept;
use App\Models\Section5\ApplicationIbcbAudit;
use App\Models\Tis\Standard;

use App\Mail\Section5\ApplicationIBCBAcceptMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Bsection5\WorkGroupIB;

class ApplicationIbcbAcceptController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_ibcb_accept/';
        $this->attach_path_crop = 'tis_attach/application_ibcb_accept_crop/';
    }


    public function data_list(Request $request)
    {
        $model = str_slug('application-ibcb-accept','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');

        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch = $request->input('filter_branch');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationIbcb::query()->with([
                                            'scopes_group.bs_branch_group',
                                            'app_assign.staff',
                                            'application_ibcb_status'
                                        ])
                                        ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            if( (strpos( $search_full , 'IB-' ) !== false) || (strpos( $search_full , 'CB-' ) !== false)){
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
                                                                                                                        $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%$search_full%");
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
                                        ->when( $filter_status , function ($query, $filter_status){
                                            return $query->where('application_status', $filter_status );
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

                                            $id_query = ApplicationIbcbScope::whereIn('branch_group_id', $branch_group_ids)->select('application_id');
                                            $query->whereIn('id', $id_query);

                                        })
                                        ->when(( !auth()->user()->can('view_all-'.$model)) , function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                            $id_query = ApplicationIbcbStaff::where('staff_id', $user->getKey())->select('application_id');
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
                            ->addColumn('application_no', function ($item) {
                                return $item->application_no;
                            })
                            ->addColumn('applicant_full_name', function ($item) {
                                return (!empty($item->applicant_name)?$item->applicant_name:'-').'<div>('.(!empty($item->applicant_taxid)?$item->applicant_taxid:'-').')</div>';
                            })
                            ->addColumn('standards', function ($item) {
                                return !empty($item->ScopeGroup)?$item->ScopeGroup:'-';
                            })
                            ->addColumn('application_date', function ($item) {
                                return !empty($item->application_date)?HP::DateThai($item->application_date):'-';
                            })
                            ->addColumn('application_status', function ($item) {
                                if( !empty($item->delete_state) ){
                                    return (!empty($item->StatusFullTitle)?'<div class="text-danger">'.$item->StatusFullTitle.'<div>':'-').'<div><em>'.(!empty($item->delete_at)?HP::DateThai($item->delete_at):null).'</em><div>';
                                }else{
                                    return !empty($item->StatusFullTitle)?$item->StatusFullTitle:'ฉบับร่าง';
                                }
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->ListStaff)?$item->ListStaff:'รอดำเนินการ');
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn =  ' <a href="'. url('section5/application_ibcb_accept/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('edit-'.$model) ){
                                    if(in_array($item->application_status, [1])){
                                        $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/application_ibcb_accept/'.$item->id.'/edit') .'" data-toggle="tooltip" data-placement="top" title="พิจารณาคำขอ"><i class="fa fa-search" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" title="พิจารณาคำขอ" disabled><i class="fa fa-search" aria-hidden="true"></i></button>';
                                    }

                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by','standards','applicant_full_name','application_status'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_ibcb_accept",  "name" => 'รับคำขอแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB/CB)' ]
            ];
            return view('section5.application_ibcb_accept.index', compact('breadcrumbs'));

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
        $model = str_slug('application-ibcb-accept','-');
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
        $model = str_slug('application-ibcb-accept','-');
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
        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('view-'.$model)) {

            $applicationIbcb = ApplicationIbcb::findOrFail($id);
            $application_labs_scope = ApplicationIbcbScope::where('application_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

            $branch_groups = BranchGroup::whereIn('id', ApplicationIbcbScope::where('application_id', $applicationIbcb->id)->select('branch_group_id'))->pluck('title', 'id')->toArray();

            $app_configs_evidences = !empty($applicationIbcb->configs_evidence)?json_decode($applicationIbcb->configs_evidence):[];

            $application_ibcb_accept = ApplicationIbcbAccept::where('application_id', $applicationIbcb->id)->orderByDesc('id')->first();

            $appointment_dates = !empty($application_ibcb_accept)?json_decode($application_ibcb_accept->appointment_date):[];

            $applicationIbcb->show  = true;

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_ibcb_accept",  "name" => 'รับคำขอแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB/CB)' ],
                [ "link" => "/section5/application_ibcb_accept/$id",  "name" => 'รายละเอียด' ]

            ];

            return view('section5/application_ibcb_accept.show', compact('applicationIbcb', 'application_labs_scope_groups', 'app_configs_evidences', 'branch_scopes', 'branch_groups', 'application_ibcb_accept', 'appointment_dates','breadcrumbs'));

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
        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $applicationIbcb = ApplicationIbcb::findOrFail($id);
            $application_labs_scope = ApplicationIbcbScope::where('application_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');

            $applicationIbcb->edited  = true;

            $branch_groups = BranchGroup::whereIn('id', ApplicationIbcbScope::where('application_id', $applicationIbcb->id)->select('branch_group_id'))->pluck('title', 'id')->toArray();

            $app_configs_evidences = !empty($applicationIbcb->configs_evidence)?json_decode($applicationIbcb->configs_evidence):[];

            $application_ibcb_accept = ApplicationIbcbAccept::where('application_id', $applicationIbcb->id)->orderByDesc('id')->first();

            $appointment_dates = !empty($application_ibcb_accept)?json_decode($application_ibcb_accept->appointment_date):[];

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application_ibcb_accept",  "name" => 'รับคำขอแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB/CB)' ],
                [ "link" => "/section5/application_ibcb_accept/$id/edit",  "name" => 'ตรวจสอบคำขอ' ]

            ];

            return view('section5/application_ibcb_accept.edit', compact('applicationIbcb', 'application_labs_scope_groups', 'app_configs_evidences', 'branch_scopes', 'branch_groups', 'application_ibcb_accept', 'appointment_dates','breadcrumbs'));

        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $applicationInspector = ApplicationIbcb::findOrFail($id);
            $application_labs_scope = ApplicationIbcbScope::where('application_lab_id', $id);
            $application_labs_scope_groups = $application_labs_scope->get()->keyBy('id')->groupBy('tis_id');
            $standards = Standard::selectRaw('id, CONCAT_WS(" : ", CONCAT_WS(" - ", tis_year, tis_no), title) AS standard_title')->whereIn('id', $application_labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();

            $applicationInspector->approve  = true;

            return view('section5/application_ibcb_accept.approve', compact('applicationInspector', 'application_labs_scope_groups', 'standards'));
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
        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $application_status = !empty($requestData['application_status'])?$requestData['application_status']:null;

            $applicationibcb = ApplicationIbcb::findOrFail($id);
            
            if(!empty($requestData['repeater-date']) && count($requestData['repeater-date']) > 0){
                $appointment_dates = $requestData['repeater-date'];
                $date_arr = [];
                foreach($appointment_dates as $key=>$appointment_date){
                    $date_arr[] = !empty($appointment_date['appointment_date'])?HP::convertDate($appointment_date['appointment_date'], true):null;
                }
                $requestData['appointment_date'] = json_encode($date_arr, JSON_UNESCAPED_UNICODE);
            }

            //บันทึกเก็บ Log Accept
            $app_accept = new ApplicationIbcbAccept;
            $app_accept->created_by = auth()->user()->getKey();
            $app_accept->application_id = $applicationibcb->id;
            $app_accept->application_no = $applicationibcb->application_no;
            $app_accept->application_status = !empty($application_status)?$application_status:null;
            $app_accept->appointment_date =  !empty($requestData['appointment_date'])?$requestData['appointment_date']:null;
            $app_accept->description = !empty($requestData['description'])?$requestData['description']:null;
            $app_accept->send_mail_status = !empty($requestData['send_mail_status'])?$requestData['send_mail_status']:null;
            if(!empty($request->input('noti_email'))){
                $app_accept->noti_email = json_encode(explode(',', $request->input('noti_email')));
            }
            $app_accept->save();

            $applicationibcb->update(['application_status' => !empty($application_status)?$application_status:null]);

            if(empty($applicationibcb->accept_by)){ //ถ้ายังไม่มีข้อมูลผู้รับคำขอ
                if(in_array($applicationibcb->application_status, [3, 4])){ //สถานะเป็น 3=เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน, 4=เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน
                    $applicationibcb->update(['accept_date' => date('Y-m-d'),  'accept_by' => auth()->user()->getKey()]   );
                }
            }

            if(in_array($applicationibcb->application_status, [4])){ //4=เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน

                $audit = ApplicationIbcbAudit::where('application_id', $applicationibcb->id )->first();
                if( is_null($audit) ){
                    $audit = new ApplicationIbcbAudit;
                    $audit->created_by = auth()->user()->getKey();
                }else{
                    $audit->updated_by = auth()->user()->getKey();
                    $audit->updated_at = date('Y-m-d H:i:s');
                }

                $audit_date_arr = [];

                if( !empty($audit->audit_date) ){
                    $audit_date_json = json_decode( $audit->audit_date , true );
                    array_merge( $audit_date_arr,$audit_date_json );
                }
              
                $audit_date_arr[] = date('Y-m-d');
                $requestData['audit_date'] = json_encode($audit_date_arr, JSON_UNESCAPED_UNICODE);

                $audit->application_id = $applicationibcb->id;
                $audit->application_no = $applicationibcb->application_no;
                $audit->audit_date     =  !empty($requestData['audit_date'])?$requestData['audit_date']:null;
                $audit->audit_result   = 1;
                $audit->audit_remark   = !empty($requestData['description'])?$requestData['description']:null;
                $audit->save();

            }

            if( isset($requestData['repeater-scope']) ){

                $list_detail = $requestData['repeater-scope'];
                foreach($list_detail as $detail){

                    if (array_key_exists("detail_id",$detail)){

                        if($application_status==4){
                            ApplicationIbcbScopeDetail::where('id',  $detail['detail_id'] )->update(['audit_result' => ( isset($detail['audit_result'])?1:2 ) ]);

                        }

                    }

                }
            }

            HP::LogInsertNotification(  
                $applicationibcb->id ,
                ( (new ApplicationIbcb)->getTable() ),
                $applicationibcb->application_no,
                $applicationibcb->application_status,
                'รับคำขอเป็น IB/CB - พิจารณาคำขอ',
                null,
                'section5/application_ibcb_accept',
                $applicationibcb->created_by,
                1
            );

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
                    $mail_format = new ApplicationIBCBAcceptMail([
                                                                    'applicant_name'=> $applicationibcb->applicant_name,
                                                                    'application_status'=> $applicationibcb->application_status,
                                                                    'application_no'=> $applicationibcb->application_no,
                                                                    'application_date'=> HP::DateThaiFull($applicationibcb->application_date),
                                                                    'description'=> $requestData['description'],
                                                                    'accept_date'=> HP::DateThaiFull($applicationibcb->accept_date),
                                                                    'operation_date'     => HP::DateThaiFull(date('Y-m-d'))

                                                                ]);
                    Mail::to($emails)->send($mail_format);
                }
            }
            
            return redirect('section5/application_ibcb_accept')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');

        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationIbcb::findOrFail($id);
            $requestData = $request->all();

            $requestData['approve_date'] = date('Y-m-d');
            $requestData['approve_by'] = auth()->user()->getKey();
            $requestData['application_status'] = $requestData['approve_status'];

            $application->update($requestData);

            return redirect('section5/application_ibcb_accept')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');


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
        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function assing_data_update(Request $request){

        $model = str_slug('application-ibcb-accept','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();
            $arr_publish = $request->input('id');
            $create_by = auth()->user()->getKey();

            $application_ibcbs = ApplicationIbcb::find($arr_publish);

            ApplicationIbcbStaff::whereIn('application_id', $arr_publish)->delete();

            if(isset($requestData['assign_by'])){

                foreach($application_ibcbs as $application_ibcb){

                    foreach($requestData['assign_by'] as $assign_by){

                        $new_arr = [];
                        $new_arr['application_id'] = @$application_ibcb->id;
                        $new_arr['application_no'] = @$application_ibcb->application_no;
                        $new_arr['staff_id'] = @$assign_by;
                        $new_arr['assign_date'] = date('Y-m-d');
                        $new_arr['created_by'] = @$create_by;
                        $result = ApplicationIbcbStaff::create($new_arr);

                        HP::LogInsertNotification( 
                            $application_ibcb->id ,
                            ( (new ApplicationIbcb)->getTable() ),
                            $application_ibcb->application_no,
                            $application_ibcb->application_status,
                            'รับคำขอเป็น IB/CB - พิจารณาคำขอ',
                            null,
                            'section5/application_ibcb_accept',
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
