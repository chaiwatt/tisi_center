<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Section5\Ibcbs;
use App\Models\Section5\IbcbsScope;
use App\Models\Section5\IbcbsScopeDetail;
use App\Models\Section5\IbcbsScopeTis;
use App\Models\Section5\IbcbsInspectors;
use App\Models\Section5\IbcbsCertificate;
use App\Models\Section5\IbcbsGazette;
use App\Models\Section5\IbcbsHistory;

use App\Models\Section5\Inspectors;
use App\Models\Section5\InspectorsScope;

use App\Models\Section5\ApplicationIbcbBoardApprove;
use App\Models\Section5\ApplicationIbcbGazette;

use App\Models\Sso\User AS SSO_USER;

use App\Models\Basic\Amphur;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use App\Models\Basic\Subdistrict;
use App\Models\Basic\Zipcode;
use App\Models\Basic\BranchTis;
use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;

use App\Models\Bsection5\Standard AS BStandard;

use stdClass;

use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCBExport;

use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\Tis\RosStandardTisi;

use App\AttachFile;

use App\Mail\Section5\ManageIBCBSyncMail;
use App\Models\Basic\Tis;
use Mail;

class ManageIbcbsController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/Ibcbs/';
        $this->attach_path_crop = 'tis_attach/Ibcbs/';

        $this->provinces_lsit     = [];
        $this->districts_list     = [];
        $this->sub_districts_lsit = [];
        $this->zipcode_lsit       = [];

    }


    public function data_list(Request $request)
    {
        $model = str_slug('manage-ibcb','-');

        $filter_search       = $request->input('filter_search');
        $filter_status       = $request->input('filter_status');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch       = $request->input('filter_branch');
        $filter_type         = $request->input('filter_type');

        $query = Ibcbs::query()->when( $filter_search , function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        if(strpos($search_full, 'CB-') !== false || strpos($search_full, 'IB-') !== false ){
                                            return $query->where('ibcb_code',  'LIKE', "%$search_full%");
                                        }else{
                                            return  $query->where(function ($query2) use($search_full) {
                                                                $ids = IbcbsScope::where(function ($query) use($search_full) {
                                                                                        $query->OrwhereHas('bs_branch_group', function($query) use ($search_full){
                                                                                                    $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                })
                                                                                                ->OrwhereHas('scopes_details.bs_branch', function($query) use ($search_full){
                                                                                                    $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                                                })
                                                                                                ->OrwhereHas('scopes_tis.scope_tis_std', function($query) use ($search_full){
                                                                                                    $query->where(function ($query) use($search_full) {
                                                                                                            $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                                        });
                                                                                                });
                                                                                    })->select('ibcb_id');

                                                                $query2->Where(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(ibcb_code,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(ibcb_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrwhereIn('id', $ids);

                                                            });
                                        }
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if( $filter_status == 1){
                                            $query->whereHas('scopes_group', function($query){
                                                        $query->where('end_date', '>=', date('Y-m-d') );
                                                    })
                                                    ->Orwhere(function ($query2) {
                                                        $query2->whereHas('scopes_group', function($query){
                                                                    $query->where('type', 2 )->where('state', 1);
                                                                });
                                                    })
                                                    ->Orwhere(function ($query2) {
                                                        $query2->whereHas('scopes_group', function($query){
                                                                    $query->where('type', 2 )->where('end_date', '>', date('Y-m-d') );
                                                                });
                                                    });
                                        }else{
                                            $query->whereHas('scopes_group', function($query){
                                                        $query->where('end_date', '<', date('Y-m-d') );
                                                    })
                                                    ->Orwhere(function ($query2) {
                                                        $query2->whereHas('scopes_group', function($query){
                                                                    $query->where('type', 2 )->where('state', '<>', 1);
                                                                });
                                                    })
                                                    ->Orwhere(function ($query2) {
                                                        $query2->whereHas('scopes_group', function($query){
                                                                    $query->where('type', 2 )->where('end_date', '<', date('Y-m-d') );
                                                                });
                                                    });
                                        }
                                    })
                                    ->when($filter_branch_group, function ($query, $filter_branch_group){
                                        $scope_query = IbcbsScope::where('branch_group_id', $filter_branch_group)->select('ibcb_id');
                                        $query->whereIn('id', $scope_query);
                                    })
                                    ->when($filter_branch, function ($query, $filter_branch){
                                        $scope_detail_query = IbcbsScopeDetail::where('branch_id', $filter_branch)->select('ibcb_id');
                                        $query->whereIn('id', $scope_detail_query);
                                    })
                                    ->when($filter_type, function ($query, $filter_type){
                                        $query->where('ibcb_type', $filter_type);
                                    });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('ibcb_code', function ($item) {
                                return !empty($item->ibcb_code)?$item->ibcb_code:'-';
                            })
                            ->addColumn('ibcb_name', function ($item) {
                                $html  = !empty($item->ibcb_name) ? $item->ibcb_name : '-';
                                $html .= '<div>(ชื่อย่อ: '.(!empty($item->initial) ? $item->initial : '-').')</div>';
                                return $html;
                            })
                            ->addColumn('taxid', function ($item) {
                                return (!empty($item->taxid)?$item->taxid:'-');
                            })
                            ->addColumn('scope_group', function ($item) {
                                return !empty($item->ScopeGroup)?$item->ScopeGroup:'-';
                            })
                            ->addColumn('state', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:'-';
                            })
                            ->addColumn('type', function ($item) {
                                $type_arr = [1 => 'IB', 2 => 'CB'];
                                return array_key_exists( $item->ibcb_type,  $type_arr )?$type_arr [ $item->ibcb_type ]:'-';
                            })
                            ->addColumn('ibcb_start_date', function ($item) {
                                return !empty($item->ibcb_start_date)?HP::DateThai($item->ibcb_start_date):'-';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                return ' <a href="'. url('section5/ibcb/'.$item->id) .'" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['action', 'state','ibcb_name'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/ibcb",  "name" => 'หน่วยตรวจสอบ IB/CB' ]
            ];
            return view('section5.manage-ibcb.index',compact('breadcrumbs'));
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
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('add-'.$model)) {

            $breadcrumbs = [
                                [ "link" => "/home", "name" => "Home"],
                                [ "link" => "/section5/ibcb",  "name" => 'หน่วยตรวจสอบ IB/CB' ],
                                [ "link" => "/section5/ibcb/create",  "name" => 'เพิ่ม' ]
                            ];

            return view('section5.manage-ibcb.create',compact('breadcrumbs'));
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
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('add-'.$model)) {

            try {
                $requestData = $request->all();

                $application_type  =  !empty($requestData['application_type'])?$requestData['application_type']:null;
                $ref =( $application_type == 1)? 'IB':'CB';

                $running_no =  HP::ConfigFormat( 'IB-CB' , (new Ibcbs)->getTable()  , 'ibcb_code', $ref , null,null );
                $application_check = Ibcbs::where('ibcb_code', $running_no)->first();
                if(!is_null($application_check)){
                    $running_no =  HP::ConfigFormat( 'IB-CB' , (new Ibcbs)->getTable()  , 'ibcb_code', $ref , null,null );
                }

                $requestIBCB['ibcb_code']       =  $running_no;
                $requestIBCB['ibcb_type']       =  !empty($requestData['application_type'])?$requestData['application_type']:null;
                $requestIBCB['name']            =  !empty($requestData['applicant_name'])?$requestData['applicant_name']:null;
                $requestIBCB['taxid']           =  !empty($requestData['applicant_taxid'])?$requestData['applicant_taxid']:null;
                $requestIBCB['ibcb_user_id']    =  !empty($requestData['agency_id'])?$requestData['agency_id']:null;
                $requestIBCB['type']            =  2;
                $requestIBCB['ibcb_start_date'] =  !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                $requestIBCB['state']           = 1;
                $requestIBCB['created_by']      = auth()->user()->getKey();


                //หน่วยงาน
                $requestIBCB['ibcb_name']           =  !empty($requestData['ibcb_name'])?$requestData['ibcb_name']:null;
                $requestIBCB['ibcb_address']        =  !empty($requestData['ibcb_address'])?$requestData['ibcb_address']:null;
                $requestIBCB['ibcb_building']       =  !empty($requestData['ibcb_building'])?$requestData['ibcb_building']:null;
                $requestIBCB['ibcb_soi']            =  !empty($requestData['ibcb_soi'])?$requestData['ibcb_soi']:null;
                $requestIBCB['ibcb_moo']            =  !empty($requestData['ibcb_moo'])?$requestData['ibcb_moo']:null;
                $requestIBCB['ibcb_subdistrict_id'] =  !empty($requestData['ibcb_subdistrict_id'])?$requestData['ibcb_subdistrict_id']:null;
                $requestIBCB['ibcb_district_id']    =  !empty($requestData['ibcb_district_id'])?$requestData['ibcb_district_id']:null;
                $requestIBCB['ibcb_province_id']    =  !empty($requestData['ibcb_province_id'])?$requestData['ibcb_province_id']:null;
                $requestIBCB['ibcb_zipcode']        =  !empty($requestData['ibcb_zipcode'])?$requestData['ibcb_zipcode']:null;

                //ข้อมูลติดต่อ
                $requestIBCB['co_name']      =  !empty($requestData['co_name'])?$requestData['co_name']:null;
                $requestIBCB['co_position']  =  !empty($requestData['co_position'])?$requestData['co_position']:null;
                $requestIBCB['co_mobile']    =  !empty($requestData['co_mobile'])?$requestData['co_mobile']:null;
                $requestIBCB['co_phone']     =  !empty($requestData['co_phone'])?$requestData['co_phone']:null;
                $requestIBCB['co_fax']       =  !empty($requestData['co_fax'])?$requestData['co_fax']:null;
                $requestIBCB['co_email']     =  !empty($requestData['co_email'])?$requestData['co_email']:null;

                $ibcb = Ibcbs::create($requestIBCB);

                //ใบประกาศ
                $this->SaveCertify( $ibcb, $requestData  );

                //ขอบข่าย
                $this->SaveScope( $ibcb, $requestData  );

                //ผู้ตรวจสอบ
                $this->SaveInspectors( $ibcb, $requestData  );

            return redirect('section5/ibcb')->with('flash_message', 'เรียบร้อยแล้ว!');

            } catch (\Exception $e) {

                echo $e->getMessage();
                exit;
                return redirect('section5/ibcb/create')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
            }

        }
        abort(403);
    }
    public function SaveCertify( $ibcb ,  $requestData )
    {
        if( isset($requestData['repeater-certificate'])  ){

            $certificate = $requestData['repeater-certificate'];

            foreach( $certificate  AS $certify ){

                $cer = IbcbsCertificate::where('certificate_no', $certify['certificate_no'] )->where('issued_by', (!empty($certify['certificate_id'])?1:2) )->first();

                if( is_null( $cer ) ){
                    $cer = new IbcbsCertificate;
                }

                $cer->ibcb_id   = $ibcb->id;
                $cer->ibcb_code = $ibcb->ibcb_code;

                $cer->certificate_std_id     = !empty($certify['certificate_std_id'])?$certify['certificate_std_id']:null;
                $cer->certificate_id         = !empty($certify['certificate_id'])?$certify['certificate_id']:null;
                $cer->certificate_no         = !empty($certify['certificate_no'])?$certify['certificate_no']:null;
                $cer->certificate_table      = !empty($certify['certificate_table'])?$certify['certificate_table']:null;
                $cer->certificate_start_date = !empty($certify['certificate_start_date'])?HP::convertDate($certify['certificate_start_date'],true):null;
                $cer->certificate_end_date   = !empty($certify['certificate_end_date'])?HP::convertDate($certify['certificate_end_date'],true):null;
                $cer->issued_by              = !empty($certify['certificate_id'])?1:2;
                $cer->type                   = 2;
                $cer->save();

            }

        }
    }


    public function SaveInspectors( $ibcb ,  $requestData )
    {
        if( isset($requestData['repeater-inspectors']) ){

            $inspectors_list = $requestData['repeater-inspectors'];

            foreach( $inspectors_list  AS $Iinspes ){

                if( !empty($Iinspes['inspector_id']) ){
                    $inspectors = IbcbsInspectors::where('ibcb_id', $ibcb->id )->where('inspector_id', $Iinspes['inspector_id'])->first();
                    if(is_null($inspectors)){
                        $inspectors = new IbcbsInspectors;
                    }

                    $inspectors->ibcb_id   = $ibcb->id;
                    $inspectors->ibcb_code = $ibcb->ibcb_code;

                    $inspectors->inspector_id         = $Iinspes['inspector_id'];
                    $inspectors->inspector_prefix     = !empty($Iinspes['inspector_prefix'])?$Iinspes['inspector_prefix']:null;
                    $inspectors->inspector_first_name = !empty($Iinspes['inspector_first_name'])?$Iinspes['inspector_first_name']:null;
                    $inspectors->inspector_last_name  = !empty($Iinspes['inspector_last_name'])?$Iinspes['inspector_last_name']:null;
                    $inspectors->inspector_taxid      = !empty($Iinspes['inspector_taxid'])?$Iinspes['inspector_taxid']:null;
                    $inspectors->inspector_type       = !empty($Iinspes['inspector_type'])?$Iinspes['inspector_type']:null;
                    $inspectors->type                 = 2;
                    $inspectors->save();

                }

            }
        }
    }

    function SaveScope($ibcb, $requestData){

        if( isset($requestData['repeater-scope']) ){
            $scope_list = $requestData['repeater-scope'];

            foreach( $scope_list  AS $Iscope ){

                $group = IbcbsScope::where('ibcb_id',  $ibcb->id )->where('branch_group_id', $Iscope['branch_group_id'] )->where('isic_no', $Iscope['isic_no'] )->first();

                if( is_null($group) ){
                    $group = new IbcbsScope;
                    $group->created_by = auth()->user()->getKey();
                }else{
                    $group->updated_by = auth()->user()->getKey();
                    $group->updated_at = date('Y-m-d H:i:s');
                }

                $group->ibcb_id         = $ibcb->id;
                $group->ibcb_code       = $ibcb->ibcb_code;
                $group->branch_group_id = $Iscope['branch_group_id'];
                $group->isic_no         = $Iscope['isic_no'];
                $group->start_date      = !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                $group->end_date        = !empty($requestData['end_date'])?HP::convertDate($requestData['end_date'],true):null;
                $group->state           = 1;
                $group->type            = 2;
                $group->save();

                if( !empty($Iscope['branch_id']) ){

                    $branch_lits = $Iscope['branch_id'];

                    foreach( $branch_lits as $Ibranch ){

                        $detail = IbcbsScopeDetail::where('ibcb_scope_id', $group->id )
                                                    ->where( function($query) use($Ibranch){
                                                        $query->where('branch_id', $Ibranch );
                                                    })
                                                    ->first();

                        if(is_null($detail)){
                            $detail = new IbcbsScopeDetail;
                        }

                        $detail->ibcb_scope_id = $group->id;
                        $detail->ibcb_id       = $ibcb->id;
                        $detail->ibcb_code     = $ibcb->ibcb_code;
                        $detail->branch_id     = $Ibranch;
                        $detail->audit_result  = 1;
                        $detail->type          = 2;
                        $detail->save();

                        if( !empty($Iscope['tis_id']) ){

                            $tis_ids = $Iscope['tis_id'];

                            $standards = Tis::select('tb3_TisAutono', 'tb3_TisThainame', 'tb3_Tisno')->whereIn('tb3_TisAutono', $tis_ids)->get()->keyBy('tb3_TisAutono')->toArray();

                            foreach( $tis_ids as $tis_id ){

                                if( array_key_exists($tis_id, $standards) ){
                                    $standard = array_key_exists($tis_id, $standards)?$standards[$tis_id]:null;

                                    $scope_tis = IbcbsScopeTis::where('ibcb_scope_id', $group->id)->where('tis_id', $tis_id)->first();

                                    if(is_null($scope_tis)){
                                        $scope_tis = new IbcbsScopeTis;
                                    }
                                    $scope_tis->ibcb_scope_id        = $group->id;
                                    $scope_tis->ibcb_scope_detail_id = $detail->id;
                                    $scope_tis->tis_id               = !empty($standard['tb3_TisAutono'])?$standard['tb3_TisAutono']:null;
                                    $scope_tis->tis_no               = !empty($standard['tb3_Tisno'])?$standard['tb3_Tisno']:null;
                                    $scope_tis->ibcb_code            = $ibcb->ibcb_code;
                                    $scope_tis->type                 = 2;
                                    $scope_tis->save();
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
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('view-'.$model)) {

            $ibcb = Ibcbs::findOrFail($id);

            $breadcrumbs = [
                                [ "link" => "/home", "name" => "Home"],
                                [ "link" => "/section5/ibcb",  "name" => 'หน่วยตรวจสอบ IB/CB' ],
                                [ "link" => "/section5/ibcb/$id",  "name" => 'รายละเอียด' ]
                            ];
            return view('section5.manage-ibcb.show',compact('ibcb','breadcrumbs'));

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
        $model = str_slug('manage-ibcb','-');
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
        $model = str_slug('manage-ibcb','-');
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
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function update_ibcb_save(Request $request, $id)
    {
        $model = str_slug('manage-ibcb','-');
        if(auth()->user()->can('edit-'.$model)) {

            $ibcb = Ibcbs::findOrFail($id);

            $requestData = $request->all();
            $requestData['ibcb_end_date'] = !empty($requestData['ibcb_end_date'])?HP::convertDate($requestData['ibcb_end_date'],true):null;

            $columns = [
                "ibcb_name",
                "initial",
                // "name",
                "taxid",

                "ibcb_address",
                "ibcb_building",
                "ibcb_moo",
                "ibcb_soi",
                "ibcb_road",
                "ibcb_subdistrict_id",
                "ibcb_district_id",
                "ibcb_province_id",
                "ibcb_zipcode",
                "ibcb_phone",
                "ibcb_fax",

                "co_name",
                "co_position",
                "co_mobile",
                "co_phone",
                "co_fax",
                "co_email",
                'ibcb_end_date'
            ];

            foreach( $columns AS $column ){
                if( array_key_exists($column , $requestData)&&  $requestData[ $column ] !=  $ibcb->{$column}  ){
                    IbcbsHistory::Add($ibcb->id, $column , $ibcb->{$column} , $requestData[ $column ] , 'เปลี่ยนแปลงข้อมูล');
                }
            }

            if( !empty($requestData['cancel_state']) && $requestData['cancel_state'] == 1 ){
                IbcbsHistory::Add($ibcb->id, 'state' , 1 , 2 , 'ยกเลิกการเป็นหน่วยตรวจสอบ');
                $requestData['state'] = 2;
            }else{
                $requestData['state'] = 1;
            }

            $ibcb->update($requestData);

            return redirect('section5/ibcb/'.$ibcb->id)->with('success_message', 'Manage updated!');
        }
        abort(403);
    }

    public function getDataCertificate(Request $request)
    {

        $table = $request->get('table');
        $taxid = $request->get('applicant_taxid');
        $search = $request->get('search');

        if( $table == ( new CertiIBExport )->getTable() || is_null( $table ) ){
            $query = CertiIBExport::query()->where(function($query) use($taxid){
                                                $ids = CertiIb::where('tax_id',  $taxid)->whereNotNull('tax_id')->select('id');
                                                $query->whereIn('app_certi_ib_id',  $ids );
                                            })
                                            ->when( $search , function ($query, $search){
                                                $search_full = str_replace(' ', '', $search);
                                                return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->Where(DB::raw("REPLACE(org_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                            })
                                            ->select('id','certificate', DB::raw('org_name AS cb_name'), 'date_start', 'date_end', 'formula', 'status');

        }else if( $table == ( new CertiCBExport )->getTable() ){

            $query = CertiCBExport::query()->where(function($query) use($taxid){
                                                $ids = CertiCb::where('tax_id',  $taxid)->whereNotNull('tax_id')->select('id');
                                                $query->whereIn('app_certi_cb_id',  $ids );
                                            })
                                            ->when( $search , function ($query, $search){
                                                $search_full = str_replace(' ', '', $search);
                                                return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->Where(DB::raw("REPLACE(name_standard,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                            })
                                            ->select('id','certificate', DB::raw('name_standard AS cb_name') , 'date_start', 'date_end', 'formula', 'status');

        }


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('certificate', function ($item) {
                                return $item->certificate;
                            })
                            ->addColumn('cb_name', function ($item) {
                                return $item->cb_name;
                            })
                            ->addColumn('formula', function ($item) {
                                return $item->formula;
                            })
                            ->addColumn('date_start', function ($item) {
                                return HP::revertDate($item->date_start,true);
                            })
                            ->addColumn('date_end', function ($item) {
                                return HP::revertDate($item->date_end,true);
                            })
                            ->addColumn('table', function ($item) use( $table ) {
                                return $table;
                            })
                            ->addColumn('status', function ($item) {
                                $certificate_date_end = !is_null($item->date_end)?$item->date_end:null;
                                if( $certificate_date_end >= date('Y-m-d') ){
                                    return 'ใช้งาน';
                                }else{
                                    return 'หมดอายุ';
                                }
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->addColumn('action', function ($item) use( $table ) {

                                $date_end = !is_null($item->date_end)?$item->date_end:null;
                                if( $date_end >= date('Y-m-d') ){
                                    return '<button class="btn btn-info btn_select_cer" type="button" data-id="'.($item->id).'" data-table="'.($table).'" data-certificate_no="'.(!is_null($item->certificate)?$item->certificate:null).'" data-date_end="'.( !is_null($item->date_end)?HP::revertDate($item->date_end):null ).'" data-date_start="'.( !is_null($item->date_start)?HP::revertDate($item->date_start):null ).'"> เลือก </button>';
                                }else{
                                    return '<button class="btn btn-info" type="button" disabled> เลือก </button>';
                                }
                            })
                            ->rawColumns([ 'action'])
                            ->make(true);

    }

    public function getDataInspectors(Request $request)
    {
        $filter_search = $request->get('search');
        $filter_branch_group = $request->input('branch_group');
        $filter_branch = $request->input('branch');
        $filter_freelance = $request->input('freelance');
        $agency_taxid = $request->input('agency_taxid');
        $request_branch_group_id = $request->input('branch_group_id');
        $request_branch_ids = $request->input('branch_id', [null]);//ไอดีสาขาจากคำขอ


        $query = Inspectors::query()->where(function($query) use($filter_freelance) {
                                        // if($filter_freelance != 'All'){
                                        //     $query->whereIn('agency_taxid', Inspectors::select('inspectors_taxid'));
                                        // }
                                    })
                                    ->where('state', 1)
                                    ->when( $filter_branch_group , function ($query, $filter_branch_group){
                                        $query->whereHas('scopes', function ($query) use($filter_branch_group) {
                                                    $query->where('branch_group_id', $filter_branch_group );
                                                });
                                    })
                                    ->when( $filter_branch , function ($query, $filter_branch){
                                        $query->whereHas('scopes', function ($query) use($filter_branch) {
                                                    $query->where('branch_id', $filter_branch );
                                                });
                                    })
                                    ->when( $filter_search , function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search);
                                        return  $query->where(function ($query2) use($search_full) {
                                                            $query2->Where(DB::raw("REPLACE(inspectors_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("CONCAT(REPLACE(inspectors_prefix,' ',''),'', REPLACE(inspectors_first_name,' ',''),'', REPLACE(inspectors_last_name,' ',''))"), 'LIKE', "%".$search_full."%");
                                                        });
                                    })
                                    ->when($request_branch_ids, function ($query, $request_branch_ids){//กรองตามสาขาตามคำขอ
                                        $query_branch = InspectorsScope::query()->whereIn('branch_id', $request_branch_ids)->select('inspectors_id');
                                        $query->whereIn('id', $query_branch);
                                    });



        return Datatables::of($query)
                        ->addIndexColumn()
                        ->addColumn('checkbox', function ($item) use ($request_branch_ids, $agency_taxid) {

                            $ScopeDataSet = $this->ScopeDataSet($item, $request_branch_ids);
                            $json_scope = !empty($ScopeDataSet) ? json_encode($ScopeDataSet, JSON_UNESCAPED_UNICODE) : null ;
                            $scope = !empty($json_scope) ? str_replace('"', "'", $json_scope) : '';

                            $data = 'data-full_name="'.($item->AgencyFullName).'"';
                            $data .= 'data-taxid="'.($item->inspectors_taxid).'"';
                            $data .= 'data-scope="'.($scope).'"';
                            $data .= 'data-id="'.($item->id).'"';
                            $data .= 'data-inspectors_prefix="'.($item->inspectors_prefix).'"';
                            $data .= 'data-inspectors_first_name="'.($item->inspectors_first_name).'"';
                            $data .= 'data-inspectors_last_name="'.($item->inspectors_last_name).'"';

                            $data .= 'data-inspector_type="'.( ($item->agency_taxid == $agency_taxid)?1:2 ).'"';


                            return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" '.($data).'  value="'.$item->id.'">';
                        })
                        ->addColumn('full_name', function ($item) {
                            return $item->AgencyFullName;
                        })
                        ->addColumn('inspectors_taxid', function ($item) {
                            return $item->inspectors_taxid;
                        })
                        ->addColumn('inspector_type', function ($item) use ($agency_taxid) {
                            return $item->agency_taxid == $agency_taxid ? 'ผู้ตรวจของหน่วยตรวจ' : 'ผู้ตรวจอิสระ' ;
                        })
                        ->addColumn('scope', function ($item) use ($request_branch_ids){
                            $ScopeShow = $this->ScopeShow($item, $request_branch_ids);
                            return $ScopeShow;
                        })
                        ->rawColumns([ 'checkbox', 'scope'])
                        ->make(true);
    }

    public function ScopeDataSet($Inspectors, $branch_ids)
    {

        $app_scope = InspectorsScope::whereIn('branch_id', is_array($branch_ids) ? $branch_ids : [] )
                                    ->where('inspectors_id', $Inspectors->id)
                                    ->select('branch_group_id')
                                    ->groupBy('branch_group_id')
                                    ->get();

        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch_group = $item->bs_branch_group;

            if(!is_null($bs_branch_group)){
                $scope = InspectorsScope::where('branch_group_id', $bs_branch_group->id)
                                        ->whereIn('branch_id', $branch_ids)
                                        ->where('inspectors_id', $Inspectors->id)
                                        ->select('branch_id')
                                        ->get();
                $list_branch = [];
                foreach( $scope as $branch ){
                    $bs_branch =  $branch->bs_branch;
                    if( !is_null($bs_branch) ){
                        $dataB = new stdClass;
                        $dataB->branch_title = (string)$bs_branch->title;
                        $dataB->branch_id = (string)$bs_branch->id;
                        $list_branch[$bs_branch->id] = $dataB;
                    }
                }

                $data = new stdClass;
                $data->branch_group_title = (string)$bs_branch_group->title;
                $data->branch_group_id = (string)$bs_branch_group->id;
                $data->branch = $list_branch;
                $list[$bs_branch_group->id] = $data;
            }

        }
        return $list;
    }

    public function ScopeShow($Inspectors, $branch_ids){

        $app_scope = InspectorsScope::whereIn('branch_id', is_array($branch_ids) ? $branch_ids : [])
                                    ->where('inspectors_id', $Inspectors->id)
                                    ->select('branch_group_id')
                                    ->groupBy('branch_group_id')
                                    ->get();

        $html = '<ul  class="list-unstyled">';
        foreach($app_scope AS $item){
            $bs_branch_group = $item->bs_branch_group;

            if( !is_null($bs_branch_group) ){

                $html .= '<li>'.($bs_branch_group->title).'</li>';
                $scope = InspectorsScope::where('branch_group_id', $bs_branch_group->id)
                                        ->whereIn('branch_id', $branch_ids)
                                        ->where('inspectors_id', $Inspectors->id)
                                        ->select('branch_id')
                                        ->get();
                $html .= '<li>';
                $html .= '<ul>';
                $list = [];
                foreach( $scope as $branch ){
                    $bs_branch = $branch->bs_branch;
                    $list[] = $bs_branch->title;
                }
                $html .= '<li>'.( implode( ' ,',  $list ) ).'</li>';
                $html .= '</ul>';
                $html .= '</li>';
            }

        }

        $html .= '</ul>';

        return $html;
    }

    public function getStandards($type)
    {
        $data = BStandard::whereJsonContains('standard_type', $type)->get();

        return response()->json($data);
    }

    public function getDataBrancheTis($branch_ids)
    {
        $branch_ids = explode(',', $branch_ids);
        $data = DB::table((new BranchTis)->getTable().' AS branch')
                    ->leftJoin((new Tis)->getTable().' AS std', 'std.tb3_TisAutono', '=', 'branch.tis_id')
                    ->when(count($branch_ids) > 0, function($query) use ($branch_ids) {
                        $query->whereIn('branch.branch_id', $branch_ids);
                    })
                    ->selectRaw('CONCAT_WS(" : ", std.tb3_Tisno, std.tb3_TisThainame) AS title, std.tb3_TisAutono AS id')
                    ->get();

        return response()->json($data);
    }

    public function getDataBranche($branch_group)
    {
        $data = Branch::where('branch_group_id', $branch_group)->get();
        return response()->json($data);
    }

    public function data_government_gazette(Request $request)
    {

        $id = $request->input('id');

        $Model_board_approve = DB::table((new ApplicationIbcbBoardApprove)->getTable().' AS ibcb_board_approve')
                                    ->leftJoin((new ApplicationIbcbGazette)->getTable().' AS gazette', 'gazette.application_id', '=', 'ibcb_board_approve.application_id')
                                    ->leftJoin((new AttachFile)->getTable().' AS attach_file_gazette', 'attach_file_gazette.ref_id', '=', 'ibcb_board_approve.id')
                                    ->where(function ($query2) use($id) {
                                        $query2->where('attach_file_gazette.section', 'file_attach_government_gazette')->whereIn('attach_file_gazette.ref_table', ['section5_application_ibcb_board_approves']);
                                    })
                                    ->where(function ($query) use($id) {
                                        $ref_app_no = IbcbsScope::where('ibcb_id', $id )->whereNotNull('ref_ibcb_application_no')->select('ref_ibcb_application_no');
                                        $query->whereIn('ibcb_board_approve.application_no',  $ref_app_no);
                                    })
                                    ->select('attach_file_gazette.*', DB::raw('ibcb_board_approve.application_no AS code'),'gazette.issue','gazette.year', 'ibcb_board_approve.government_gazette_date',  DB::raw('"ibcb_board_approve" AS type_app') );

        $Model_ibcb_gazette = DB::table((new IbcbsGazette)->getTable().' AS ibcbs_gazette')
                                    ->leftJoin((new AttachFile)->getTable().' AS attach_file_gazette', 'attach_file_gazette.ref_id', '=', 'ibcbs_gazette.id')
                                    ->where(function ($query2) use($id) {
                                        $query2->where('attach_file_gazette.section', 'file_attach_government_gazette')->whereIn('attach_file_gazette.ref_table', ['section5_ibcbs_gazettes']);
                                    })
                                    ->where(function ($query) use($id) {
                                        $query->where('ibcbs_gazette.ibcb_id',  $id);
                                    })
                                    ->select('attach_file_gazette.*', DB::raw('ibcbs_gazette.ibcb_code AS code'),'ibcbs_gazette.issue','ibcbs_gazette.year', 'ibcbs_gazette.government_gazette_date', DB::raw('"ibcbs_gazette" AS type_app') );


        $query =  $Model_board_approve->union($Model_ibcb_gazette)->orderBy('id', 'DESC')->get();

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('government_gazette_date', function ($item) {
                                return  !empty($item->government_gazette_date)?HP::DateThai($item->government_gazette_date):null;
                            })
                            ->addColumn('government_gazette', function ($item) {
                                $issue  =  !empty($item->issue)?$item->issue:null;
                                $year  =  !empty($item->year)?((int)$item->year + 543):null;
                                return  ($issue).'-'.($year);
                            })
                            ->addColumn('url', function ($item) {
                                return  '<a href="'.(HP::getFileStorage($item->url)).'" target="_blank">'.(HP::FileExtension($item->filename)  ?? '').'</a>';
                            })
                            ->addColumn('type', function ($item) {
                                if( $item->ref_table == 'section5_application_ibcb_board_approves'){
                                    return ('จากระบบใบสมัคร').'<div><em>('.( !empty($item->code)?$item->code:null ).')</em></div>';
                                }else{
                                    return ('หน่วยตรวจสอบ IB/CB');
                                }
                            })
                            ->rawColumns(['action', 'government_gazette','url','type'])
                            ->make(true);

    }

    public function update_ibcb_gazette(Request $request)
    {
        $requestData = $request->all();

        $id   =  $requestData['id'];
        $ibcb = Ibcbs::findOrFail($id);

        $gazetteData['ibcb_id']                        = $ibcb->id;
        $gazetteData['ibcb_code']                      = $ibcb->ibcb_code;
        $gazetteData['issue']                          = !empty($requestData['m_issue'])?($requestData['m_issue']):null;
        $gazetteData['year']                           = !empty($requestData['m_year'])?($requestData['m_year']):null;
        $gazetteData['sign_id']                        = !empty($requestData['m_sign_id'])?($requestData['m_sign_id']):null;
        $gazetteData['sign_name']                      = !empty($requestData['m_sign_name'])? $requestData['m_sign_name']:null;
        $gazetteData['sign_position']                  = !empty($requestData['m_sign_position'])? $requestData['m_sign_position']:null;
        $gazetteData['announcement_date']              = !empty($requestData['m_announcement_date'])?HP::convertDate($requestData['m_announcement_date'],true):null;
        $gazetteData['government_gazette_date']        = !empty($requestData['m_government_gazette_date'])?HP::convertDate($requestData['m_government_gazette_date'],true):null;
        $gazetteData['government_gazette_description'] = !empty($requestData['m_government_gazette_description'])? $requestData['m_government_gazette_description']:null;
        $gazetteData['created_by']                     = auth()->user()->getKey();
        $gazette = IbcbsGazette::create($gazetteData);

        // $gazette = IbcbsGazette::where('ibcb_id', $ibcb->id )->first();

        // if( is_null($gazette) ){
        //     $gazetteData['created_by'] = auth()->user()->getKey();
        //     $gazette = IbcbsGazette::create($gazetteData);

        // }else{
        //     $gazetteData['updated_by'] = auth()->user()->getKey();
        //     $gazetteData['updated_at'] = date('Y-m-d H:i:s');
        //     $gazette->update( $gazetteData );
        // }

        $tax_number = !empty($ibcb->taxid )?$ibcb->taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        $folder_app = ($ibcb->ibcb_code).'/';

        if(isset($requestData['m_file_gazette'])){
            if ($request->hasFile('m_file_gazette')) {
                HP::singleFileUpload(
                    $request->file('m_file_gazette') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new IbcbsGazette)->getTable() ),
                    $gazette->id,
                    'file_attach_government_gazette',
                    'เอกสารประกาศราชกิจจา'
                );
            }
        }

        return response()->json(['msg' => 'success' ]);

    }

    public function plus_scope(Request $request)
    {
        $requestData = $request->all();

        $id   =  $requestData['ibcb_id'];
        $ibcbs = Ibcbs::findOrFail($id);

        $msg = 'error';

        $requestData['start_date'] = !empty($requestData['scope_start_date'])?HP::convertDate($requestData['scope_start_date'],true):null;
        $requestData['end_date']   = !empty($requestData['scope_end_date'])?HP::convertDate($requestData['scope_end_date'],true):null;

        if( isset($requestData['repeater-scope']) ){

            $scope_list = $requestData['repeater-scope'];

            foreach( $scope_list  AS $Iscope ){

                $scopes = IbcbsScope::where('ibcb_id', $ibcbs->id )
                                        ->where( function($query) use($Iscope){
                                            $query->where('branch_group_id', $Iscope['branch_group_id'] );
                                        })
                                        ->where( function($query) use($requestData){
                                            $query->where('start_date', $requestData['start_date'] )
                                                    ->where('end_date', $requestData['end_date'] );
                                        })
                                        ->first();
                //สร้าง สาขาผลิตภัณฑ์
                if( is_null($scopes) ){
                    $scopes                  = new IbcbsScope;
                    $scopes->created_by      = auth()->user()->getKey();
                    $scopes->ibcb_id         = $ibcbs->id;
                    $scopes->ibcb_code       = $ibcbs->ibcb_code;

                    $scopes->isic_no         = !empty($Iscope['isic_no'])?$Iscope['isic_no']:null;
                    $scopes->branch_group_id = !empty($Iscope['branch_group_id'])?$Iscope['branch_group_id']:null;
                    $scopes->state           = 1;
                    $scopes->start_date      = $requestData['start_date'];
                    $scopes->end_date        = $requestData['end_date'];
                    $scopes->type            = 2;
                    $scopes->save();
                }

                //รายสาขา
                if( !empty($Iscope['branch_id']) ){

                    $branch_lits = $Iscope['branch_id'];

                    foreach( $branch_lits as $Ibranch ){

                        if( !empty( $Ibranch ) ){

                            $detail = IbcbsScopeDetail::where('ibcb_scope_id', $scopes->id )
                                                        ->where( function($query) use($Ibranch){
                                                            $query->where('branch_id', $Ibranch );
                                                        })
                                                        ->first();

                            if( is_null($detail) ){

                                $detail                = new IbcbsScopeDetail;
                                $detail->ibcb_scope_id = $scopes->id;
                                $detail->ibcb_id       = $ibcbs->id;
                                $detail->ibcb_code     = $ibcbs->ibcb_code;
                                $detail->branch_id     = $Ibranch;
                                $detail->type          = 2;
                                $detail->audit_result  = 1;
                                $detail->save();

                            }

                            // มอก.
                            if( !empty($Iscope['tis_id']) ){

                                $tis_ids = $Iscope['tis_id'];

                                $standards = Tis::select('tb3_TisAutono AS id', 'tb3_TisThainame', 'tb3_Tisno')->whereIn('tb3_TisAutono', $tis_ids)->get()->keyBy('id')->toArray();

                                foreach( $tis_ids as $tis_id ){

                                    $standard = array_key_exists($tis_id, $standards)?$standards[$tis_id]:null;

                                    if( !empty($standard) && !empty($standard['id']) ){
                                        $scope_tis = IbcbsScopeTis::where('ibcb_scope_id', $scopes->id)->where('ibcb_scope_detail_id',$detail->id)->where('tis_id', $standard['id'] )->first();
                                        if(is_null($scope_tis)){
                                            $scope_tis                       = new IbcbsScopeTis;
                                            $scope_tis->ibcb_scope_id        = $scopes->id;
                                            $scope_tis->ibcb_scope_detail_id = $detail->id;
                                            $scope_tis->tis_id               = $standard['id'];
                                            $scope_tis->tis_no               = !empty($standard['tb3_Tisno'])?$standard['tb3_Tisno']:null;
                                            $scope_tis->ibcb_code            = $ibcbs->ibcb_code;
                                            $scope_tis->type                 = 2;
                                            $scope_tis->save();
                                        }
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

                $scope                   = IbcbsScope::find($scope_id);
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

    public function data_scope_std(Request $request)
    {
        $id = $request->input('id');
        $filter_search       = $request->input('filter_search');
        $filter_branch_group = $request->input('filter_branch_group');
        $filter_branch       = $request->input('filter_branch');

        $query = IbcbsScopeTis::query()->with([
                                                'scope_detail',
                                                'ibcb_scope',
                                                'scope_tis_std',
                                                'ibcb_data'
                                        ])
                                        ->leftJoin((new IbcbsScope)->getTable().' AS scope', 'scope.id', '=', 'section5_ibcbs_scopes_tis.ibcb_scope_id')
                                        ->leftJoin((new BranchGroup)->getTable().' AS branch_group', 'branch_group.id', '=', 'scope.branch_group_id')
                                        ->leftJoin((new IbcbsScopeDetail)->getTable().' AS scope_detail', 'scope_detail.id', '=', 'section5_ibcbs_scopes_tis.ibcb_scope_detail_id')
                                        ->leftJoin((new Branch)->getTable().' AS branch', 'branch.id', '=', 'scope_detail.branch_id')
                                        ->leftJoin((new Tis)->getTable().' AS standard', 'standard.tb3_TisAutono', '=', 'section5_ibcbs_scopes_tis.tis_id')
                                        ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return  $query->where(function ($query2) use($search_full) {
                                                                $query2->Where(DB::raw("REPLACE(branch_group.title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(branch.title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(standard.tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(standard.tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                        })
                                        ->when($filter_branch_group, function ($query, $filter_branch_group){
                                            $query->whereHas('ibcb_scope', function($query) use ($filter_branch_group){
                                                $query->where('branch_group_id', $filter_branch_group);
                                            });
                                        })
                                        ->when($filter_branch, function ($query, $filter_branch){
                                            $query->whereHas('scope_detail', function($query) use ($filter_branch){
                                                $query->where('branch_id', $filter_branch);
                                            });
                                        })
                                        ->where(function($query) use($id){
                                            $query->Where('scope.ibcb_id', $id);
                                        })
                                        ->select(
                                            DB::raw('standard.tb3_Tisno AS tb3_Tisno'),
                                            DB::raw('standard.tb3_TisThainame AS tb3_TisThainame'),
                                            DB::raw('standard.status AS tb3_status'),
                                            DB::raw('branch_group.title AS branch_group_name'),
                                            DB::raw('branch.title AS branch_name'),
                                            DB::raw('scope.end_date AS end_date')
                                        )
                                        ->distinct();

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('bs_branch_group', function ($item) {
                                return !empty($item->branch_group_name)?$item->branch_group_name:' - ';
                            })
                            ->addColumn('bs_branch', function ($item) {
                                return !empty($item->branch_name)?$item->branch_name:' - ';
                            })
                            ->addColumn('tis_tisno', function ($item) {
                                $text = '';
                                if(!empty($item->tb3_Tisno)){
                                    $text .= $item->tb3_Tisno;
                                    if($item->tb3_status=="5"){ //ยกเลิก
                                        $text .= ' <span class="label label-rounded label-danger font-15" style="margin-bottom:-20px;">มอก. ยกเลิก</span>';
                                    }
                                }
                                return $text;
                            })
                            ->addColumn('tis_title', function ($item) {
                                return !empty($item->tb3_TisThainame)?$item->tb3_TisThainame:null;
                            })
                            ->addColumn('end_date', function ($item) {
                                return !empty($item->end_date)?HP::revertDate($item->end_date,true):null;
                            })
                            ->order(function ($query) {
                                $query->orderbyRaw('CONVERT(branch_group.title USING tis620)')
                                        ->orderbyRaw('CONVERT(branch.title USING tis620)')
                                        ->orderbyRaw('CONVERT(standard.tb3_Tisno USING tis620)');
                            })
                            ->rawColumns(['checkbox', 'ibcb_name', 'tis_tisno', 'state','contact', 'gazette', 'action'])
                            ->make(true);

    }

    public function sync_to_elicense(Request $request){

        $result = null;
        $ibcb_id = $request->get('ibcb_id', null);

        if(!is_null($ibcb_id)){
            $this->sync_to_elicense_action($ibcb_id);
            $result = 'success';
        }else{
            $result = 'fail';
        }

        return $result;
    }

    //อัพเดทข้อมูล Lab ไป e-license
    private function sync_to_elicense_action($ibcb_id){

        $ibcb = Ibcbs::find($ibcb_id);

        //สร้างบัญชีผู้ใช้งาน
        $e_user = RosUsers::where('ibcb_code', $ibcb->ibcb_code)->first();
        if(is_null($e_user)){//ไม่พบบัญชีจากรหัส
            $e_user = RosUsers::where('username', $ibcb->ibcb_code)->first();
        }else{//พบบัญชีจากรหัส
            //ค้นหาเพื่อเช็คว่ามีบัญชีอื่นที่ username=ibcb_code แต่คนละบัญชี
            $e_user_temp = RosUsers::where('username', $ibcb->ibcb_code)->first();
            if(!is_null($e_user_temp) && $e_user_temp->id!=$e_user->id){//พบบัญชี แต่ไม่ใช่บัญชีเดียวกับที่จะใช้อัพเดท
                $e_user_temp->username = $e_user_temp->username.'-'.str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
                $e_user_temp->save();
            }
        }

        if(is_null($e_user)){//ยังไม่มีบัญชีผู้ใช้งาน
            $user_sso = SSO_USER::find($ibcb->ibcb_user_id);
            if(!is_null($user_sso)){
                $user_data = $user_sso->toArray();
                $user_columns = (new RosUsers)->Columns;//ชื่อคอลัมภ์ใน user elicense
                $user_columns = array_flip($user_columns);//สลับชื่อคอลัมภ์(value) มาเป็น key ของ Array;
                $user_data = array_intersect_key($user_data, $user_columns);//ตัดเอาเฉพาะฟิลด์ข้อมูลที่มีใน user elicense ไว้

                unset($user_data['id']); //ตัด id ออก
                $user_data['ibcb_code'] = $ibcb->ibcb_code;//รหัส IBCB ที่ใช้อ้างอิง
                $user_data['username']  = $ibcb->ibcb_code;//เปลี่ยน username ใช้รหัสห้อง Lab
                $password               = uniqid();
                $user_data['password']  = Hash::make($password);//gen รหัสผ่าน
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

                    $mail_format = new ManageIBCBSyncMail([
                        'applicant_name'     => $ibcb->name,
                        'start_date'         => HP::DateThaiFull($ibcb->ibcb_start_date),
                        'url'                => $url,
                        'username'           => $user_data['username'],
                        'password'           => $password
                    ]);
                    Mail::to($user_data['email'])->send($mail_format);
                }
            }
        }else{//มีบัญชีผู้ใช้งานแล้ว

            $e_user->username  = $ibcb->ibcb_code;
            $e_user->ibcb_code = $ibcb->ibcb_code;
            $e_user->save();

            $user_id = $e_user->id;
        }

        if(isset($user_id)){//ได้ผู้ใช้งานในระบบ e-License

            //บันทึกกลุ่มผู้ใช้งาน
            RosUserGroupMap::where('user_id', $user_id)->where('group_id', 16)->delete();//ลบออกก่อนถ้ามี
            $group_map = new RosUserGroupMap;
            $group_map->user_id  = $user_id;
            $group_map->group_id = 16;
            $group_map->save();

            /*** อัพเดทข้อมูลมาตรฐานมอก. Lab ***/
            //ลบออกจากมอก.ที่ตรวจได้ก่อน
            $standards = RosStandardTisi::where('for_ib_use', 'LIKE', '%"'.$user_id.'"%')->get();
            foreach ($standards as $standard) {
                $standard_ibs = (array)json_decode($standard->for_ib_use, true);
                if (($key = array_search($user_id, $standard_ibs)) !== false) {
                    unset($standard_ibs[$key]);
                    $standard->for_ib_use = json_encode(array_values(array_unique($standard_ibs)));
                    $standard->save();
                }
            }

            //อัพเดทข้อมูลมาตรฐานมอก. Lab ที่สามารถทดสอบผลิตภัณฑ์ตามมาตรฐานนั้นๆได้
            if($ibcb->state==1){//ถ้าสถานะ lab ไม่ถูกยกเลิก
                $tis_numbers = $ibcb->scope_standard_active()
                                   ->get()
                                   ->pluck('tis_no')
                                   ->toArray();
                $tis_numbers = array_unique($tis_numbers);
                foreach ($tis_numbers as $tis_number) {
                    $standard = RosStandardTisi::where('tis_number', $tis_number)->first();
                    if(!is_null($standard)){
                        $standard_ibs = array_values((array)json_decode($standard->for_ib_use, true));
                        $standard_ibs[] = (string)$user_id;
                        $standard->for_ib_use = json_encode(array_unique($standard_ibs));
                        $standard->save();
                    }
                }
            }

        }

    }

}
