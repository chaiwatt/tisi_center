<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Section5\ApplicationInspector;
use App\Models\Section5\ApplicationInspectorAudit;
use App\Models\Section5\ApplicationInspectorScope;
use App\Models\Section5\ApplicationInspectorsStaff;

use App\Models\Section5\Inspectors;
use App\Models\Section5\InspectorsScope;
use App\Models\Section5\InspectorsAgreement;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\WorkGroupIBBranch;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use Mpdf\Mpdf;

class ApplicationInspectorsAgreementController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/application_inspectors_agreement/';
        $this->attach_path_crop = 'tis_attach/application_inspectors_agreement_crop/';
    }


    public function data_list(Request $request)
    {

        $model = str_slug('application-inspectors-agreement','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch = $request->input('filter_branch');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_agreement_start_date  = $request->input('filter_agreement_start_date');
        $filter_agreement_end_date    = $request->input('filter_agreement_end_date');

        $filter_agreement_status  = $request->input('filter_agreement_status');

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
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        $query->where('application_status', $filter_status);
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
                                                    ->where(function($query){
                                                        $query->whereIn('application_status', [8,9,10]);
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
                                                    ->when($filter_agreement_start_date, function ($query, $filter_agreement_start_date){
                                                        $filter_agreement_start_date = HP::convertDate($filter_agreement_start_date, true);
                                                        return  $query->whereHas('inspector_agreement', function($query) use ($filter_agreement_start_date){
                                                                            $query->where('start_date', '<=', $filter_agreement_start_date);
                                                                        });
                                                    })
                                                    ->when($filter_agreement_end_date, function ($query, $filter_agreement_end_date){
                                                        $filter_agreement_end_date = HP::convertDate($filter_agreement_end_date, true);
                                                        return  $query->whereHas('inspector_agreement', function($query) use ($filter_agreement_end_date){
                                                                            $query->where('start_date', '<=', $filter_agreement_end_date);
                                                                        });
                                                    })
                                                    ->when($filter_agreement_status, function ($query, $filter_agreement_status){

                                                        if( $filter_agreement_status == '-1'){
                                                            return $query->Has('inspector_agreement','==',0)
                                                                            ->OrwhereHas('inspector_agreement', function($query) {
                                                                                $query->whereNull('agreement_status');
                                                                            });
                                                        }else{
                                                            $query->whereHas('inspector_agreement', function($query) use ($filter_agreement_status){
                                                                $query->where('agreement_status', $filter_agreement_status);
                                                            });
                                                        }

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
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-app_no="'. $item->application_no .'" value="'. $item->id .'">';
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
                            ->addColumn('inspector_agreement_date', function ($item) {
                                $inspector_agreement = $item->inspector_agreement;
                                return !empty($inspector_agreement->start_date)?HP::DateThai($inspector_agreement->start_date):'รอดำเนินการ';
                            })
                            ->addColumn('agreement_status', function ($item) {
                                $arr = [ 1 => 'ออกเอกสารแล้ว', 2 => 'แนบไฟล์เอกสารแล้ว' ];
                                $inspector_agreement = $item->inspector_agreement;
                                if( !empty($inspector_agreement->agreement_status) && !empty($inspector_agreement->file_attach_document) ){
                                    $file_attach_document = $inspector_agreement->file_attach_document;
                                    return '<a href="'.(HP::getFileStorage($file_attach_document->url)).'" target="_blank">แนบไฟล์เอกสารแล้ว</a>';
                                }else{
                                    return (!empty($inspector_agreement->agreement_status)  &&  array_key_exists( $inspector_agreement->agreement_status,  $arr ) ? $arr[$inspector_agreement->agreement_status]:'รอดำเนินการ');
                                }
                            })
                            ->addColumn('status_application', function ($item) {
                                $inspector_status = $item->inspector_status;
                                return !empty($inspector_status->title)?$inspector_status->title:'-';
                            })
                            ->addColumn('preview_document', function ($item) {

                                $agreement = $item->inspector_agreement;
                                $btn = '';
                                if( !is_null($agreement) && empty($agreement->file_attach_document) ){
                                    $btn.= ' <a class="btn btn-danger btn-xs waves-effect waves-light" href="'. url('section5/application-inspectors-agreement/preview_document/'.$item->id) .'" target="_blank" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                                }else if( !is_null($agreement) && !empty($agreement->file_attach_document) ){
                                    $file_attach_document = $agreement->file_attach_document;
                                    $btn = ' <a href="'.(HP::getFileStorage($file_attach_document->url)).'"  class="btn btn-success btn-xs waves-effect waves-light" target="_blank"><i class="fa fa-file-archive-o" aria-hidden="true"></i></a>';
                                }else{
                                    $btn = ' <button type="button" class="btn btn-danger btn-xs waves-effect waves-light" disabled><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button> ';
                                }
                                return $btn;
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                $agreement = $item->inspector_agreement;

                                if( auth()->user()->can('view-'.$model) ){
                                    $btn =  ' <a href="'. url('section5/application-inspectors-agreement/'.$item->id) .'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="รายละเอียด"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('edit-'.$model) ){

                                    if( $item->application_status != 10 ){
                                        $btn .= ' <a class="btn btn-warning btn-xs waves-effect waves-light" href="'. url('section5/application-inspectors-agreement/create_document/'.$item->id) .'" title="บันทึกเอกสารขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน" ><i class="fa fa-file-text-o" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-warning btn-xs waves-effect waves-light" title="บันทึกเอกสารขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน"  disabled><i class="fa fa-file-text-o" aria-hidden="true"></i></button> ';
                                    }
         
                                    if( !is_null($agreement) ){
                                        $btn .= ' <a class="btn btn-primary btn-xs waves-effect waves-light" href="'. url('section5/application-inspectors-agreement/attach_document/'.$item->id) .'" title="ไฟล์แนบเอกสารขึ้นทะเบียนผู้ตรวจ/ผู้ประเมิน"><i class="mdi mdi-paperclip" aria-hidden="true"></i></a>';
                                    }else{
                                        $btn .= ' <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" title="ไฟล์แนบเอกสารขึ้นทะเบียนผู้ตรวจ/ผู้ประเมิน" disabled><i class="mdi mdi-paperclip" aria-hidden="true"></i></button> ';
                                    }

                                }

                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'scope', 'action', 'preview_document','refno_application','authorized_name','agreement_status'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('application-inspectors-agreement','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-agreement",  "name" => 'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน' ],
            ];
            return view('section5.application-inspectors-agreement.index',compact('breadcrumbs'));
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
        $model = str_slug('application-inspectors-agreement','-');
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
        $model = str_slug('application-inspectors-agreement','-');
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
        $model = str_slug('application-inspectors-agreement','-');
        if(auth()->user()->can('view-'.$model)) {
            $application_inspectors = ApplicationInspector::findOrFail($id);
            $application_inspectors->attach = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-agreement",  "name" => 'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-agreement/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('section5.application-inspectors-agreement.show',compact('application_inspectors','breadcrumbs'));
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
        $model = str_slug('application-inspectors-agreement','-');
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
        $model = str_slug('application-inspectors-agreement','-');
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
        $model = str_slug('application-inspectors-agreement','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function create_document($id)
    {
        $model = str_slug('application-inspectors-agreement','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application_inspectors = ApplicationInspector::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-agreement",  "name" => 'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-agreement/create_document/$id",  "name" => 'บันทึกเอกสารขึ้นทะเบียน' ],

            ];
            return view('section5.application-inspectors-agreement.document',compact('application_inspectors','breadcrumbs'));
        }
        abort(403);
    }

    public function document_save(Request $request, $id)
    {
        $requestData = $request->all();


        $application = ApplicationInspector::findOrFail($id);

        $inspectors = $application->section5_inspectors;

        $agreement = InspectorsAgreement::where('application_id', $application->id )->first();

        if( is_null($agreement) ){
            $agreement = new InspectorsAgreement;
            $agreement->created_by = auth()->user()->getKey();
        }else{
            $agreement->updated_by = auth()->user()->getKey();
            $agreement->updated_at = date('Y-m-d H:i:s');
        }

        $agreement->application_id = $application->id;
        $agreement->application_no = $application->application_no;
        $agreement->inspectors_id = @$inspectors->id;
        $agreement->inspectors_code = @$inspectors->inspectors_code;

        $agreement->inspectors_prefix = !empty($application->applicant_prefix)?$application->applicant_prefix:null;
        $agreement->inspectors_first_name = !empty($application->applicant_first_name)?$application->applicant_first_name:null;
        $agreement->inspectors_last_name = !empty($application->applicant_last_name)?$application->applicant_last_name:null;
        $agreement->inspectors_taxid = !empty($application->applicant_taxid)?$application->applicant_taxid:null;

        // Agency
        $agreement->agency_name = !empty($application->agency_name)?$application->agency_name:null;
        $agreement->agency_taxid = !empty($application->agency_taxid)?$application->agency_taxid:null;
        $agreement->agency_address = !empty($application->agency_address)?$application->agency_address:null;
        $agreement->agency_moo = !empty($application->agency_moo)?$application->agency_moo:null;
        $agreement->agency_soi = !empty($application->agency_soi)?$application->agency_soi:null;
        $agreement->agency_road = !empty($application->agency_road)?$application->agency_road:null;
        $agreement->agency_subdistrict = !empty($application->agency_subdistrict)?$application->agency_subdistrict:null;
        $agreement->agency_district = !empty($application->agency_district)?$application->agency_district:null;
        $agreement->agency_province = !empty($application->agency_province)?$application->agency_province:null;
        $agreement->agency_zipcode = !empty($application->agency_zipcode)?$application->agency_zipcode:null;

        $agreement->start_date =  !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'], true):null;
        $agreement->end_date =  !empty($requestData['end_date'])?HP::convertDate($requestData['end_date'], true):null;
        $agreement->first_date =  !empty($requestData['first_date'])?HP::convertDate($requestData['first_date'], true):null;

        $agreement->agreement_status = 1;
        $agreement->save();

        if( !is_null($agreement) ){

            Inspectors::where('inspectors_taxid', $application->applicant_taxid )->update(['inspector_first_date' => $agreement->first_date ]);
            InspectorsScope::where('application_id', $application->id )->update(['start_date' => $agreement->start_date, 'end_date' =>$agreement->end_date ]);

            $application->update(['application_status' => 9]);

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB)',
                null,
                'section5/application-inspectors-agreement',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application->application_no,
                $application->application_status,
                'บันทึกเอกสารผู้ตรวจ/ผู้ประเมิน',
                'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                'section5/application-inspectors-agreement/create_document/'.$application->id,
                auth()->user()->getKey(),
                4
            );
        }

        return redirect('section5/application-inspectors-agreement/create_document/'.$application->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');
    }

    public function preview_document($id){

        $application = ApplicationInspector::findOrFail($id);

        $agreement = $application->inspector_agreement;

        $mpdf = new Mpdf( [
                            'format'            => 'A4',
                            'mode'              => 'UTF-8',
                            'default_font' => 'thiasarabun',
                            'default_font_size' => '15',
                        ] );

        $mpdf->AddPageByArray([
                                'orientation' => 'P',
                                'margin-left' => "30",
                                'margin-right' => "20",
                                'margin-top' => "25",
                                'margin-bottom' => "20",
                            ]);

        $filename = "เงื่อนไขการขึ้นทะเบียนผู้ตรวจแล้วผู้ประเมิน_".($application->application_no)."_".date('Ymd_hms').".pdf";

        $html = view('section5/application-inspectors-agreement.pdf.index',compact('application'));

        $mpdf->WriteHTML( $html );

        $scope_group_result = InspectorsScope::where('application_id', $application->id )->select('branch_group_id')->groupBy('branch_group_id')->get();


        $list_group = [];
        $group_number = [];

        $i_group = 0;
        $i_ex = 0;
        $i_rx = 1;
        $scope_result = InspectorsScope::where('application_id', $application->id )->get();

        foreach( $scope_group_result AS $group  ){
            $i_group++;

            $scopes =  $scope_result->where('branch_group_id', $group->branch_group_id );
            $group_number[ $group->branch_group_id ] =  $group->bs_branch_group->id ?? null;

            if( $scopes->count() > 0 ){
                foreach( $scopes  AS $scope ){
                    if($i_rx <= 15){
                        $list_group[$i_ex][$group->branch_group_id][ $scope->branch_id ] = $scope;
                        $i_rx ++;
                    }else{
                        $list_group[$i_ex][$group->branch_group_id][ $scope->branch_id ] = $scope;
                        $i_rx = 1;
                        $i_ex++;
                    }
                }

            }else{
                if($i_rx <= 15){
                    $list_group[$i_ex][$group->branch_group_id][ ] = null;
                    $i_rx ++;
                }else{
                    $list_group[$i_ex][$group->branch_group_id][ ] = null;
                    $i_rx = 1;
                    $i_ex++;
                }
            }


        }

        $show_page = 0;
        foreach(   $list_group  AS $Bgroup ){

            $mpdf->AddPageByArray([
                'orientation' => 'P',
                'margin-left' => "30",
                'margin-right' => "20",
                'margin-top' => "25",
                'margin-bottom' => "20",
            ]);

            $html_scope = view('section5/application-inspectors-agreement.pdf.scope',compact('application', 'Bgroup', 'scope_group_result', 'group_number'));
            $mpdf->WriteHTML( $html_scope );

            $mpdf->SetHTMLFooter('
			<table width="100%" border="0">
				<tr>
					<td width="100%" align="center" class="font-14"  style="padding-top: -110px;"> หน้าที่ '.($show_page+1).'/'.( count($list_group)).'<td>
				</tr>
                <tr>
                    <td width="100%" align="left" class="font-14"  style="padding-top: -70px;">กองกำกับองค์กรด้านมาตรฐาน สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม<td>
                </tr>
			</table>');

        }

        // $mpdf->SetTitle( $application->application_no );
        $mpdf->Output($filename, 'I');
        exit;
    }

    public function attach_document($id)
    {
        $model = str_slug('application-inspectors-agreement','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application_inspectors = ApplicationInspector::findOrFail($id);
            $application_inspectors->attach = true;
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/application-inspectors-agreement",  "name" => 'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมิน' ],
                [ "link" => "/section5/application-inspectors-agreement/attach_document/$id",  "name" => 'ไฟล์แนบเอกสารขึ้นทะเบียน' ],

            ];
            return view('section5.application-inspectors-agreement.attach',compact('application_inspectors','breadcrumbs'));
        }
        abort(403);
    }

    public function attach_save(Request $request, $id)
    {
        $requestData = $request->all();
        $application = ApplicationInspector::findOrFail($id);
        $agreement = InspectorsAgreement::where('application_id', $application->id )->first();

        if( !is_null($agreement) ){


            $tax_number = !empty($application->applicant_taxid )?$application->applicant_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $folder_app = ($application->application_no);

            if( !empty($agreement->file_created_by) ){
                $agreement->file_created_by = auth()->user()->getKey();
                $agreement->file_created_at = date('Y-m-d H:i:s');
            }else{
                $agreement->file_updated_by = auth()->user()->getKey();
                $agreement->file_updated_at = date('Y-m-d H:i:s');
            }
            $agreement->agreement_status = !empty($requestData['agreement_status'])?$requestData['agreement_status']:null;
            $agreement->description = !empty($requestData['description'])?$requestData['description']:null;
            $agreement->save();

            if(  $agreement->agreement_status  == 1 ){
                $application->update(['application_status' => 9]);
            }else{
                $application->update(['application_status' => 10]);
            }

            if(isset($requestData['attach_document'])){
                if ($request->hasFile('attach_document')) {
                    HP::singleFileUpload(
                        $request->file('attach_document') ,
                        $this->attach_path.$folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new InspectorsAgreement)->getTable() ),
                        $agreement->id,
                        'file_attach_document',
                        'เอกสารการตรวจประเมิน'
                    );
                }
            }

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application->application_no,
                $application->application_status,
                'ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB)',
                null,
                'section5/application-inspectors-agreement',
                $application->created_by,
                1
            );

            HP::LogInsertNotification(
                $application->id ,
                ( (new ApplicationInspector)->getTable() ),
                $application->application_no,
                $application->application_status,
                'บันทึกแนบไฟล์เอกสารขึ้นทะเบียนผู้ตรวจ/ผู้ประเมิน',
                'ขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม ',
                'section5/application-inspectors-agreement/attach_document/'.$application->id,
                auth()->user()->getKey(),
                4
            );

            return redirect('section5/application-inspectors-agreement/attach_document/'.$application->id)->with('success_message', 'อัพเดทข้อมูลเรียบร้อยแล้ว!');

        }

    }
}
