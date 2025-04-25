<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tis\Standard;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Section5\InspectorsAgreement;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\InspectorsScopeTis;
use HP;


use App\Models\Section5\Inspectors;
use App\Models\Section5\InspectorsScope;
use App\Models\Basic\Branch;
use App\Models\Basic\BranchTis;
use App\Models\Basic\Prefix;
use App\Models\Basic\Tis;

class ManageInspectorsController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/Inspectors/';
        $this->attach_path_crop = 'tis_attach/Inspectors/';
    }


    public function data_list(Request $request)
    {
        $model = str_slug('manage-inspector','-');

        $filter_search       = $request->input('filter_search');
        $filter_status       = $request->input('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch       = $request->input('filter_branch');
        $filter_agency       = $request->input('filter_agency');

        $filter_start_date = !empty($request->input('filter_start_date'))?HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date = !empty($request->input('filter_end_date'))?HP::convertDate($request->input('filter_end_date'),true):null;

        $query = Inspectors::query()->with([
                                        'scopes.bs_branch_group',
                                        'inspectors_agreements'
                                    ])
                                    ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            if( strpos( $search_full , 'INS-' ) !== false){
                                                        return $query->where('inspectors_code',  'LIKE', "%$search_full%");
                                            }else{
                                                return  $query->where(function ($query2) use($search_full) {

                                                            $ids = InspectorsScope::where(function ($query) use($search_full) {
                                                                                                    $query->whereHas('bs_branch_group', function($query) use ($search_full){
                                                                                                                $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                            })
                                                                                                            ->OrwhereHas('bs_branch', function($query) use ($search_full){
                                                                                                                $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                            })
                                                                                                            ->OrwhereHas('ins_scopes_tis.scope_tis_std', function($query) use ($search_full){
                                                                                                                $query->where(function ($query) use($search_full) {
                                                                                                                        $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                                                    });
                                                                                                            });
                                                                                                })->select('inspectors_id');

                                                            $query2->Where(DB::raw("REPLACE(inspectors_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                   ->OrWhere(DB::raw("CONCAT(REPLACE(inspectors_first_name,' ',''),'',REPLACE(inspectors_first_name,' ',''),'', REPLACE(inspectors_last_name,' ',''))"), 'LIKE', "%".$search_full."%")
                                                                   ->OrWhere(DB::raw("REPLACE(inspectors_code,' ','')"), 'LIKE', "%".$search_full."%")
                                                                   ->OrwhereIn('id', $ids);
                                                        });
                                        }
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        $query->where('state', $filter_status);
                                    })
                                    ->when($filter_agency, function ($query, $filter_agency){
                                        $query->where('agency_taxid', $filter_agency);
                                    })
                                    ->when($filter_branch_group, function ($query, $filter_branch_group){
                                        $query->whereHas('scopes', function($query) use ($filter_branch_group){
                                            $query->where('branch_group_id', $filter_branch_group);
                                        });
                                    })
                                    ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                            return $query->whereBetween('inspector_first_date',[$filter_start_date,$filter_end_date]);
                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                            return $query->whereDate('inspector_first_date',$filter_start_date);
                                        }
                                    })
                                    ->when($filter_branch, function ($query, $filter_branch){
                                        $query->whereHas('scopes', function($query) use ($filter_branch){
                                            $query->where('branch_id', $filter_branch);
                                        });
                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('inspectors_code', function ($item) {
                                return !empty($item->inspectors_code)?$item->inspectors_code:'-';
                            })
                            ->addColumn('inspectors_name', function ($item) {
                                return (!empty($item->AgencyFullName)?$item->AgencyFullName:'-').'<div>'.(!empty($item->inspectors_taxid)?'('.$item->inspectors_taxid.')':'-').'</div>';
                            })
                            ->addColumn('scope_group', function ($item) {
                                return !empty($item->BranchGroupBranchName) ? $item->BranchGroupBranchName : '-';
                            })
                            ->addColumn('agency_name', function ($item) {
                                $agency_user = $item->agency_user;
                                return !is_null($agency_user) ? $agency_user->name : '-' ;
                            })
                            ->addColumn('inspector_first_date', function ($item) {
                                return !empty($item->inspector_first_date)?HP::DateThai($item->inspector_first_date):'-';
                            })
                            ->addColumn('state', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:'-';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                return ' <a href="'. url('section5/inspectors/'.$item->id) .'" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            })
                            ->order(function ($query) {
                                // $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['action', 'scope_group', 'state','inspectors_name'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('manage-inspector','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/inspectors",  "name" => 'ผู้ตรวจ/ผู้ประเมิน (IB)' ]
            ];

            return view('section5.manage-inspectors.index',compact('breadcrumbs'));
        }
        abort(403);

    }

    public function data_std_list(Request $request)
    {

        $filter_search       = $request->input('filter_search');
        $inspectors_code       = $request->input('inspectors_code');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch       = $request->input('filter_branch');

        $query = InspectorsScopeTis::query()->with([
            'scope_tis_std',
            'inspector_scope'
        ])
            ->where('inspectors_code', $inspectors_code)
            ->when($filter_search, function ($query, $filter_search) {
                $search_full = str_replace(' ', '', $filter_search);
                return $query->whereHas('scope_tis_std', function ($query) use ($search_full) {
                    $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%" . $search_full . "%")
                        ->orWhere(DB::raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%" . $search_full . "%");
                });
            })
            ->when($filter_branch_group, function ($query, $filter_branch_group){
                return $query->whereHas('inspector_scope', function ($query) use ($filter_branch_group) {
                    $query->where('branch_group_id', $filter_branch_group);
                });
            })
            ->when($filter_branch, function ($query, $filter_branch){
                return $query->whereHas('inspector_scope', function ($query) use ($filter_branch) {
                    $query->where('branch_id', $filter_branch);
                });
            });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('scope_tis_std', function ($item) {
                                return !is_null($item->scope_tis_std) ? $item->scope_tis_std->tb3_TisThainame : '<i>ข้อมูลไม่สมบูรณ์</i>';
                            })
                            ->addColumn('tis_no', function ($item) {
                                return $item->tis_no;
                            })
                            ->addColumn('branch_title', function ($item) {
                                return !is_null($item->inspector_scope) ? $item->inspector_scope->BranchTitle : '<i>ข้อมูลไม่สมบูรณ์</i>';
                            })
                            ->addColumn('branch_group_title', function ($item) {
                                return !is_null($item->inspector_scope) ? $item->inspector_scope->BranchGroupTitle : '<i>ข้อมูลไม่สมบูรณ์</i>' ;
                            })
                            ->order(function ($query) {
                                // $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['scope_tis_std', 'tis_no', 'branch_title'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('manage-inspector','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                                [ "link" => "/home", "name" => "Home"],
                                [ "link" => "/section5/inspectors",  "name" => 'ผู้ตรวจ/ผู้ประเมิน (IB)' ],
                                [ "link" => "/section5/inspectors/create",  "name" => 'เพิ่ม' ]
                            ];
            return view('section5.manage-inspectors.create',compact('breadcrumbs'));
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
        $model = str_slug('manage-inspector','-');
        if(auth()->user()->can('add-'.$model)) {

            try {

                $requestData = $request->all();

                $requestDataIns['inspectors_taxid'] = !empty( $requestData['applicant_taxid'] )?$requestData['applicant_taxid']:null;

                $inspectors = Inspectors::where( 'inspectors_taxid', $requestDataIns['inspectors_taxid'] )->first();

                if( is_null($inspectors) ){

                    $running_no =  HP::ConfigFormat( 'Inspectors' , (new Inspectors)->getTable()  , 'inspectors_code', null , null,null );
                    $application_check = Inspectors::where('inspectors_code', $running_no)->first();
                    if(!is_null($application_check)){
                        $running_no =  HP::ConfigFormat( 'Inspectors' , (new Inspectors)->getTable()  , 'inspectors_code', null , null,null );
                    }
                    $requestDataIns['type']                  = 2;
                    $requestDataIns['inspectors_code']       = $running_no;
                    $requestDataIns['state']                 = 1;
                    $requestDataIns['inspector_first_date']  = !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                    $requestDataIns['created_by']            = auth()->user()->getKey();

                    $requestDataIns['inspectors_user_id']     = !empty( $requestData['inspectors_user_id'] )?$requestData['inspectors_user_id']:null;

                    $requestDataIns['inspectors_prefix']     = !empty( $requestData['applicant_prefix'] )? Prefix::find($requestData['applicant_prefix'])->initial : null ;
                    $requestDataIns['inspectors_first_name'] = !empty( $requestData['applicant_first_name'] )?$requestData['applicant_first_name']:null;
                    $requestDataIns['inspectors_last_name']  = !empty( $requestData['applicant_last_name'] )?$requestData['applicant_last_name']:null;

                    $requestDataIns['inspectors_position']   = !empty( $requestData['applicant_position'] )?$requestData['applicant_position']:null;
                    $requestDataIns['inspectors_phone']      = !empty( $requestData['applicant_phone'] )?$requestData['applicant_phone']:null;
                    $requestDataIns['inspectors_fax']        = !empty( $requestData['applicant_fax'] )?$requestData['applicant_fax']:null;
                    $requestDataIns['inspectors_mobile']     = !empty( $requestData['applicant_mobile'] )?$requestData['applicant_mobile']:null;
                    $requestDataIns['inspectors_email']      = !empty( $requestData['applicant_email'] )?$requestData['applicant_email']:null;

                    //ที่อยู่
                    $requestDataIns['inspectors_address']     = !empty( $requestData['inspectors_address'] )?$requestData['inspectors_address']:null;
                    $requestDataIns['inspectors_soi']         = !empty( $requestData['inspectors_soi'] )?$requestData['inspectors_soi']:null;
                    $requestDataIns['inspectors_moo']         = !empty( $requestData['inspectors_moo'] )?$requestData['inspectors_moo']:null;
                    $requestDataIns['inspectors_road']        = !empty( $requestData['inspectors_road'] )?$requestData['inspectors_road']:null;
                    $requestDataIns['inspectors_subdistrict'] = !empty( $requestData['inspectors_subdistrict'] )?$requestData['inspectors_subdistrict']:null;
                    $requestDataIns['inspectors_district']    = !empty( $requestData['inspectors_district'] )?$requestData['inspectors_district']:null;
                    $requestDataIns['inspectors_province']    = !empty( $requestData['inspectors_province'] )?$requestData['inspectors_province']:null;
                    $requestDataIns['inspectors_zipcode']     = !empty( $requestData['inspectors_zipcode'] )?$requestData['inspectors_zipcode']:null;

                    //หน่ายงาน
                    $requestDataIns['agency_id']          = !empty( $requestData['agency_id'] )?$requestData['agency_id']:null;
                    $requestDataIns['agency_name']        = !empty( $requestData['agency_name'] )?$requestData['agency_name']:null;
                    $requestDataIns['agency_taxid']       = !empty( $requestData['agency_taxid'] )?$requestData['agency_taxid']:null;
                    $requestDataIns['agency_address']     = !empty( $requestData['agency_address'] )?$requestData['agency_address']:null;
                    $requestDataIns['agency_moo']         = !empty( $requestData['agency_moo'] )?$requestData['agency_moo']:null;
                    $requestDataIns['agency_soi']         = !empty( $requestData['agency_soi'] )?$requestData['agency_soi']:null;
                    $requestDataIns['agency_road']        = !empty( $requestData['agency_road'] )?$requestData['agency_road']:null;
                    $requestDataIns['agency_subdistrict'] = !empty( $requestData['agency_subdistrict'] )?$requestData['agency_subdistrict']:null;
                    $requestDataIns['agency_district']    = !empty( $requestData['agency_district'] )?$requestData['agency_district']:null;
                    $requestDataIns['agency_province']    = !empty( $requestData['agency_province'] )?$requestData['agency_province']:null;
                    $requestDataIns['agency_zipcode']     = !empty( $requestData['agency_zipcode'] )?$requestData['agency_zipcode']:null;

                    $inspectors = Inspectors::create($requestDataIns);

                }else{
                    //ใช้ค่าที่กรอกมาเพื่อเอาไปใส่ในตาราง scope
                    $inspectors->agency_taxid = !empty($requestData['agency_taxid']) ? $requestData['agency_taxid'] : null;
                    $inspectors->agency_id    = !empty($requestData['agency_id']) ? $requestData['agency_id'] : null;
                }

                //ขอบข่าย
                $this->SaveScope( $inspectors, $requestData  );

                return redirect('section5/inspectors')->with('flash_message', 'เรียบร้อยแล้ว!');

            } catch (\Exception $e) {

                echo $e->getMessage();
                exit;
                return redirect('section5/inspectors/create')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
            }

        }
        abort(403);
    }

    function SaveScope($inspectors, $requestData){

        if( isset($requestData['repeater-branch']) ){

            $branchs = $requestData['repeater-branch'];

            foreach( $branchs  AS $branch ){

                $branch_group_id = $branch['branch_group_id'];

                if( !empty($branch['branch_id']) ){

                    $branch_id = explode(',', $branch['branch_id'] );

                    foreach( $branch_id AS  $branch_ids ){

                        $scope = InspectorsScope::where('inspectors_id', $inspectors->id )
                                                    ->where('agency_id', $inspectors->agency_id)
                                                    ->where('agency_taxid', $inspectors->agency_taxid)
                                                    ->whereNull('ref_inspector_application_no' )
                                                    ->where('branch_group_id', $branch_group_id )
                                                    ->where('branch_id', $branch_ids )->first();

                        if(is_null($scope)){
                            $scope = new InspectorsScope;
                        }

                        $scope->inspectors_id   = $inspectors->id;
                        $scope->inspectors_code = $inspectors->inspectors_code;
                        $scope->agency_id       = $inspectors->agency_id;
                        $scope->agency_taxid    = $inspectors->agency_taxid;
                        $scope->created_by      = auth()->user()->getKey();
                        $scope->type            = 2;

                        $scope->branch_id       = !empty($branch_ids)?$branch_ids:null;
                        $scope->branch_group_id = !empty($branch_group_id)?$branch_group_id:null;
                        $scope->state           = 1;
                        $scope->start_date      = !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                        $scope->end_date        = !empty($requestData['end_date'])?HP::convertDate($requestData['end_date'],true):null;
                        $scope->save();

                        //บันทึกขอบข่ายมอก.
                        if(!empty($branch['tis_id'])){
                            $tis_ids = explode(',', $branch['tis_id']);
                            foreach ($tis_ids as $tis_id) {
                                $branch_tis = BranchTis::where('branch_id', $scope->branch_id)->where('tis_id', $tis_id)->first();
                                if(!is_null($branch_tis)){//มอก.ที่เลือกมามีในรายสาขานี้

                                    $standard = Tis::find($tis_id);//มอก.
                                    if(!is_null($standard)){
                                        $scope_tis = InspectorsScopeTis::where('inspector_scope_id', $scope->id)
                                                                        ->where('tis_id', $standard->getKey())
                                                                        ->first();
                                        if(is_null($scope_tis)){
                                            $scope_tis = new InspectorsScopeTis;
                                        }
                                        $scope_tis->inspector_scope_id = $scope->id;
                                        $scope_tis->inspectors_code    = $inspectors->inspectors_code;
                                        $scope_tis->tis_id             = $standard->getKey();
                                        $scope_tis->tis_no             = $standard->tb3_Tisno;
                                        $scope_tis->tis_name           = $standard->tb3_TisThainame;
                                        $scope_tis->state              = 1;
                                        $scope_tis->save();
                                    }

                                }
                            }
                        }

                    }


                }

            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('manage-inspector','-');
        if(auth()->user()->can('view-'.$model)) {
            $inspector = Inspectors::with([
                                        'inspectors_agreements' => function($query){
                                            $query->with([
                                                'inspectors_scopes' => function($query){
                                                    $query->with(['bs_branch_group', 'bs_branch']);
                                                }
                                            ]);
                                        }
                                    ])
                                    ->where('id', $id)
                                    ->first();

            $breadcrumbs = [
                                [ "link" => "/home", "name" => "Home"],
                                [ "link" => "/section5/inspectors",  "name" => 'ผู้ตรวจ/ผู้ประเมิน (IB)' ],
                                [ "link" => "/section5/inspectors/$id",  "name" => 'รายละเอียด' ]
                            ];

            return view('section5.manage-inspectors.show',compact('inspector','breadcrumbs'));
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
        $model = str_slug('manage-inspector','-');
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
        $model = str_slug('manage-inspector','-');
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
        $model = str_slug('manage-inspector','-');
        if(auth()->user()->can('delete-'.$model)) {



        }
        abort(403);
    }

    public function infomation_save(Request $request, $id)
    {

        $requestData = $request->all();
        $inspector = Inspectors::findOrFail($id);

        $requestData['updated_by'] = auth()->user()->getKey();
        $requestData['updated_at'] = date('Y-m-d H:i:s');

        $inspector->update($requestData);

        return redirect('section5/inspectors/'.$inspector->id)->with('success_message', 'Manage updated!');
    }

    //ดึงค่า มอก. ตามรายสาขา
    public function getDataBrancheTis($branch_ids){

        $branch_ids = explode(',', $branch_ids);
        $data = DB::table((new BranchTis)->getTable().' AS branch')
                    ->leftJoin((new Tis)->getTable().' AS std', 'std.tb3_TisAutono', '=', 'branch.tis_id')
                    ->leftJoin((new Branch)->getTable().' AS b', 'b.id', '=', 'branch.branch_id')
                    ->when(count($branch_ids) > 0, function($query) use ($branch_ids) {
                        $query->whereIn('branch.branch_id', $branch_ids);
                    })
                    ->selectRaw('CONCAT_WS(" : ", std.tb3_Tisno, std.tb3_TisThainame) AS title, std.tb3_TisAutono AS id, b.title AS branch_title')
                    ->get();

        return response()->json($data);
    }

    public function plus_scope(Request $request)
    {
        $requestData = $request->all();

        $id         =  $requestData['inspectors_id'];
        $inspectors = Inspectors::findOrFail($id);

        $msg = 'error';

        $tax_number = !empty($inspectors->inspectors_taxid )?$inspectors->inspectors_taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        $folder_app = ($inspectors->inspectors_code);

        $requestData['agency_taxid'] = !empty( $requestData['plus_agency_taxid'] )? $requestData['plus_agency_taxid'] :null;
        $requestData['start_date']   = !empty($requestData['scope_start_date'])?HP::convertDate($requestData['scope_start_date'],true):null;
        $requestData['end_date']     = !empty($requestData['scope_end_date'])?HP::convertDate($requestData['scope_end_date'],true):null;

        if( isset($requestData['repeater-branch']) ){

            $branchs = $requestData['repeater-branch'];

            foreach( $branchs  AS $branch ){

                $branch_group_id = $branch['branch_group_id'];

                if( !empty($branch['branch_id']) ){

                    $branch_id = explode(',', $branch['branch_id'] );

                    foreach( $branch_id AS  $branch_ids ){

                        $scope = InspectorsScope::where('inspectors_id', $inspectors->id )
                                                    ->where('agency_taxid',  $requestData['agency_taxid'] )
                                                    ->whereNull('ref_inspector_application_no' )
                                                    ->where('branch_group_id', $branch_group_id )
                                                    ->where('branch_id', $branch_ids )
                                                    ->where( function($query) use($requestData){
                                                        $query->where('start_date', $requestData['start_date'] )
                                                                ->where('end_date', $requestData['end_date'] );
                                                    })
                                                    ->first();

                        if(is_null($scope)){
                            $scope = new InspectorsScope;
                        }

                        $scope->inspectors_id   = $inspectors->id;
                        $scope->inspectors_code = $inspectors->inspectors_code;
                        $scope->agency_taxid    = $requestData['agency_taxid'];
                        $scope->created_by      = auth()->user()->getKey();
                        $scope->type            = 2;

                        $scope->branch_id       = !empty($branch_ids)?$branch_ids:null;
                        $scope->branch_group_id = !empty($branch_group_id)?$branch_group_id:null;
                        $scope->state           = 1;
                        $scope->start_date      = $requestData['start_date'];
                        $scope->end_date        = $requestData['end_date'];
                        $scope->save();

                        //เอกสารแนบ
                        if(isset($requestData['file_attach_scope'])){
                            if ($request->hasFile('file_attach_scope')) {
                                HP::singleFileUpload(
                                    $request->file('file_attach_scope') ,
                                    $this->attach_path.$folder_app,
                                    ( $tax_number),
                                    (auth()->user()->FullName ?? null),
                                    'Center',
                                    (  (new InspectorsScope)->getTable() ),
                                    $scope->id,
                                    'file_attach_scope',
                                    'เอกสารแนบเพิ่มขอบข่าย'
                                );
                            }
                        }
            
                        //บันทึกขอบข่ายมอก.
                        if(!empty($branch['tis_id'])){
                            $tis_ids = explode(',', $branch['tis_id']);
                            foreach ($tis_ids as $tis_id) {
                                $branch_tis = BranchTis::where('branch_id', $scope->branch_id)->where('tis_id', $tis_id)->first();
                                if(!is_null($branch_tis)){//มอก.ที่เลือกมามีในรายสาขานี้

                                    $standard = Standard::find($tis_id);//มอก.
                                    if(!is_null($standard)){

                                        $scope_tis = InspectorsScopeTis::where('inspector_scope_id', $scope->id)
                                                                            ->where('tis_id', $standard->id)
                                                                            ->first();
                                        if(is_null($scope_tis)){
                                            $scope_tis = new InspectorsScopeTis;
                                        }

                                        $scope_tis->inspector_scope_id = $scope->id;
                                        $scope_tis->inspectors_code    = $inspectors->inspectors_code;
                                        $scope_tis->tis_id             = $standard->id;
                                        $scope_tis->tis_no             = $standard->tis_tisno;
                                        $scope_tis->tis_name           = $standard->title;
                                        $scope_tis->state              = 1;
                                        $scope_tis->save();
                                    }

                                }
                            }
                        }

                    }


                }

            }

            $msg = 'success';

        }

        return response()->json(['msg' => $msg ]);
    }

    //บันทึกลดขอบข่าย
    public function minus_scope(Request $request){

        $data = $request->all();
        $msg = 'error';
        if(array_key_exists('scope_id', $data) && count($data['scope_id']) > 0){
            foreach ($data['scope_id'] as $scope_id) {

                $scope                   = InspectorsScope::find($scope_id);
                $scope->close_state_date = date('Y-m-d H:i:s');
                $scope->close_remarks    = !empty($data['mn_close_remarks'])?$data['mn_close_remarks']:null;
                $scope->close_by         = auth()->user()->getKey();
                $scope->state            = 2 ;
                $scope->save();
            }

            $msg = 'success';
        }

        return response()->json(['msg' => $msg ]);
    }

    
    public function get_scope_detail($id)
    {
        $scope = InspectorsScope::findOrFail($id);

        $detail = InspectorsScopeTis::where('inspector_scope_id', $scope->id )->get();

        return view('section5.manage-inspectors.scopes.html-scope', ['scope' => $scope, 'detail' => $detail ]);

    }

}
