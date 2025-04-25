<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Section5\Labs;
use App\Models\Section5\LabsScope;
use App\Models\Section5\LabsScopeDetail;
use App\Models\Section5\LabsHistory;
use App\Models\Section5\LabsCertify;
use App\Models\Section5\LabsCertifyLog;
use App\Models\Section5\LabsScopeLog;

use App\Models\Sso\User AS SSO_USER;

use App\Models\Bsection5\TestItem;
use App\Models\Bsection5\TestItemTools;
use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\Tis\RosStandardTisi;
use App\Models\Elicense\RosUsers;
use stdClass;

use App\Mail\Section5\ManageLabSyncMail;
use Mail;

use App\CertificateExport;
use App\Models\Certify\Applicant\CertiLab;

class ManageLabsController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/labs/';
        $this->attach_path_crop = 'tis_attach/labs/';
    }

    public function data_list(Request $request)
    {
        $model = str_slug('manage-lab','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        $filter_tis_id = $request->input('filter_tis_id');
        $filter_expired = $request->input('filter_expired');

        $query = Labs::query()->when( $filter_search , function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search);

                                    if( strpos( $search_full , 'LAB-' ) !== false){
                                        return $query->where('lab_code',  'LIKE', "%$search_full%");
                                    }else{
                                        return  $query->where(function ($query2) use($search_full) {
                                                            $ids = LabsScope::where(function ($query) use($search_full) {
                                                                                    $query->whereHas('tis_standards', function($query) use ($search_full){
                                                                                                $query->where(function ($query) use($search_full) {
                                                                                                        $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                                    });
                                                                                            });
                                                                                })->select('lab_id');

                                                            $query2->Where(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(lab_code,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrwhereIn('id', $ids);
                                                        });

                                    }
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        $query->whereHas('scope_standard', function($query){
                                                    $query->where('end_date', '>=', date('Y-m-d') );
                                                });
                                    }else{

                                        $query->Has('scope_standard','==',0)
                                                ->OrWhere('state', '<>', 1)
                                                ->OrwhereHas('scope_standard', function($query){
                                                    $query->where('end_date', '<', date('Y-m-d') );
                                                });

                                    }

                                })
                                ->when($filter_tis_id, function ($query, $filter_tis_id){
                                    $query->whereHas('scope_standard', function($query) use ($filter_tis_id){
                                        $query->where('tis_id', $filter_tis_id);
                                    });
                                })
                                ->when($filter_expired, function ($query, $filter_expired){
                                        if($filter_expired==1){
                                            $query->whereNotNull('lab_end_date')->where('lab_end_date', '<', date('Y-m-d')); 
                                        }else{
                                            $query->whereNull('lab_end_date');
                                        }
                                });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('lab_code', function ($item) {
                                return !empty($item->lab_code)?$item->lab_code:'-';
                            })
                            ->addColumn('lab_name', function ($item) {
                                if(!is_null($item->lab_end_date) && $item->lab_end_date<date('Y-m-d')){
                                    $html = '<span style="color:red">';
                                    $html .= !empty($item->lab_name) ? $item->lab_name : '-';
                                    $html .= !is_null($item->user) ? '<div>('.$item->user->name.')</div>' : '';//ชื่อผปก.ใน sso_user
                                    $html .= '</span>';
                                }else{
                                    $html = !empty($item->lab_name) ? $item->lab_name : '-';
                                    $html .= !is_null($item->user) ? '<div>('.$item->user->name.')</div>' : '';//ชื่อผปก.ใน sso_user
                                }
                                  
                                return $html;
                            })
                            ->addColumn('taxid', function ($item) {
                                return !empty($item->taxid)?$item->taxid:'-';
                            })
                            ->addColumn('standards', function ($item) {
                                return !empty($item->ScopeStandardActive)?$item->ScopeStandardActive:'-';
                            })
                            ->addColumn('lab_start_date', function ($item) {
                                return HP::DateThaiFull($item->lab_start_date);
                            })
                            ->addColumn('lab_end_date', function ($item) {
                                return HP::DateThaiFull($item->lab_end_date);
                            })
                            ->addColumn('state', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:'-';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                return ' <a href="'. url('section5/labs/'.$item->id) .'" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['lab_name', 'action', 'state'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                                [ "link" => "/home", "name" => "Home"],
                                [ "link" => "/section5/labs",  "name" => 'หน่วยตรวจสอบ LAB' ]
                            ];

            //LAB ที่หมดอายุการรับรองแล้ว
            $labs = Labs::whereNotNull('lab_end_date')->where('lab_end_date', '<', date('Y-m-d'))->get(); 
     
            return view('section5.manage-lab.index',compact('breadcrumbs', 'labs'));
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
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/labs",  "name" => 'หน่วยตรวจสอบ LAB' ],
                [ "link" => "/section5/labs/create",  "name" => 'เพิ่ม' ]
            ];
            return view('section5.manage-lab.create',compact('breadcrumbs'));

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
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();

            try {


                $running_no =  HP::ConfigFormat( 'LAB' , (new Labs)->getTable()  , 'lab_code', null , null, null );
                $application_check = Labs::where('lab_code', $running_no)->first();
                if(!is_null($application_check)){
                    $running_no =  HP::ConfigFormat( 'LAB' , (new Labs)->getTable()  , 'lab_code', null , null, null );
                }

                $requestLab['lab_code']        = $running_no;
                $requestLab['name']            = !empty($requestData['applicant_name'])?$requestData['applicant_name']:null;
                $requestLab['taxid']           = !empty($requestData['applicant_taxid'])?$requestData['applicant_taxid']:null;

                $requestLab['lab_user_id']     = !empty($requestData['lab_user_id'])?$requestData['lab_user_id']:null;
                $requestLab['type']            = 2;
                $requestLab['lab_start_date']  = !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                $requestLab['state']           = 1;
                $requestLab['created_by']      = auth()->user()->getKey();

                //หน่วยงาน
                $requestLab['lab_name']           = !empty($requestData['lab_name'])?$requestData['lab_name']:null;
                $requestLab['lab_address']        = !empty($requestData['lab_address'])?$requestData['lab_address']:null;
                $requestLab['lab_building']       = !empty($requestData['lab_building'])?$requestData['lab_building']:null;
                $requestLab['lab_soi']            = !empty($requestData['lab_soi'])?$requestData['lab_soi']:null;
                $requestLab['lab_moo']            = !empty($requestData['lab_moo'])?$requestData['lab_moo']:null;
                $requestLab['lab_subdistrict_id'] = !empty($requestData['lab_subdistrict_id'])?$requestData['lab_subdistrict_id']:null;
                $requestLab['lab_district_id']    = !empty($requestData['lab_district_id'])?$requestData['lab_district_id']:null;
                $requestLab['lab_province_id']    = !empty($requestData['lab_province_id'])?$requestData['lab_province_id']:null;
                $requestLab['lab_zipcode']        = !empty($requestData['lab_zipcode'])?$requestData['lab_zipcode']:null;
                $requestLab['lab_phone']          = !empty($requestData['lab_phone'])?$requestData['lab_phone']:null;
                $requestLab['lab_fax']            = !empty($requestData['lab_fax'])?$requestData['lab_fax']:null;

                //ข้อมูลติดต่อ
                $requestLab['co_name']      = !empty($requestData['co_name'])?$requestData['co_name']:null;
                $requestLab['co_position']  = !empty($requestData['co_position'])?$requestData['co_position']:null;
                $requestLab['co_mobile']    = !empty($requestData['co_mobile'])?$requestData['co_mobile']:null;
                $requestLab['co_phone']     = !empty($requestData['co_phone'])?$requestData['co_phone']:null;
                $requestLab['co_fax']       = !empty($requestData['co_fax'])?$requestData['co_fax']:null;
                $requestLab['co_email']     = !empty($requestData['co_email'])?$requestData['co_email']:null;

                $labs = Labs::create($requestLab);
                //ขอบข่าย
                $this->SaveScope( $labs, $requestData  );

                //ใบประกาศ
                $this->SaveCertify($labs, $requestData  );

                return redirect('section5/labs')->with('flash_message', 'เรียบร้อยแล้ว!');

            } catch (\Exception $e) {

                echo $e->getMessage();
                exit;
                return redirect('section5/labs/create')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
            }


        }
        abort(403);
    }

    public function SaveScope( $labs ,  $requestData )
    {

        if( isset($requestData['section_box_tis']) ){

            $section_box_tis = $requestData['section_box_tis'];

            $section_ids = [];
            foreach( $section_box_tis AS $box ){
                $section_ids[$box] = $box;
            }

            foreach( $section_box_tis AS $box ){

                if(  isset($requestData['repeater-group-'.$box ]) ){

                    $repeater_scope = $requestData['repeater-group-'.$box];

                    foreach( $repeater_scope as $scope ){

                        $scopes = LabsScope::where('lab_id', $labs->id )
                                            ->where( function($query) use($scope){
                                                $query->where('tis_id', $scope['tis_id'] )->where('test_item_id', $scope['test_item_id'] );
                                            })
                                            ->first();

                        if(is_null($scopes)){
                            $scopes = new LabsScope;
                        }

                        $scopes->lab_id       = $labs->id;
                        $scopes->lab_code     = $labs->lab_code;
                        $scopes->tis_id       = !empty($scope['tis_id'])?$scope['tis_id']:null;
                        $scopes->tis_tisno    = !empty($scope['tis_tisno'])?$scope['tis_tisno']:null;
                        $scopes->test_item_id = !empty($scope['test_item_id'])?$scope['test_item_id']:null;
                        $scopes->start_date   = !empty($requestData['start_date'])?HP::convertDate($requestData['start_date'],true):null;
                        $scopes->end_date     = !empty($requestData['end_date'])?HP::convertDate($requestData['end_date'],true):null;
                        $scopes->state        = 1;
                        $scopes->type         = 2;
                        $scopes->save();

                        //เพิ่มตาราง Detail
                        $detail = LabsScopeDetail::where('lab_id', $labs->id )
                                                    ->where('lab_scope_id', $scopes->id )
                                                    ->where( function($query) use($scope){
                                                        $query->where('test_tools_id', $scope['test_tools_id'] )
                                                            ->where('test_tools_no', $scope['test_tools_no'] )
                                                            ->where('capacity', $scope['capacity'] )
                                                            ->where('range', $scope['range'] )
                                                            ->where('true_value', $scope['true_value'] )
                                                            ->where('fault_value', $scope['fault_value'] )
                                                            ->where('test_duration', $scope['test_duration'] )
                                                            ->where('test_price', $scope['test_price'] );

                                                    })
                                                    ->first();
                        if(is_null($detail)){
                            $detail = new LabsScopeDetail;

                            $detail->lab_id                       = $labs->id;
                            $detail->lab_code                     = $labs->lab_code;
                            $detail->lab_scope_id                 = !empty($scopes->id)?$scopes->id:null;
                            $detail->test_tools_id                = !empty($scope['test_tools_id'])?$scope['test_tools_id']:null;
                            $detail->test_tools_no                = !empty($scope['test_tools_no'])?$scope['test_tools_no']:null;
                            $detail->capacity                     = !empty($scope['capacity'])?$scope['capacity']:null;
                            $detail->range                        = !empty($scope['range'])?$scope['range']:null;
                            $detail->true_value                   = !empty($scope['true_value'])?$scope['true_value']:null;
                            $detail->fault_value                  = !empty($scope['fault_value'])?$scope['fault_value']:null;
                            $detail->test_duration                = !empty($scope['test_duration'])?$scope['test_duration']:null;
                            $detail->test_price                   = !empty($scope['test_price'])?$scope['test_price']:null;

                            $detail->type                         = 2;
                            $detail->save();
                        }

                    }

                }

            }

        }

    }

    public function SaveCertify($labs ,  $requestData)
    {
        if( isset($requestData['repeater-audit-1']) ){

            $repeater_audit_1 = $requestData['repeater-audit-1'];

            $attach_path =  $this->attach_path.($labs->lab_code).'/';

            foreach( $repeater_audit_1 as $item ){

                $cer = LabsCertify::where('lab_id', $labs->id )
                                    ->where( function($query) use($item){
                                        $query->where('certificate_no', $item['certificate_no'] )
                                            ->where('accereditatio_no', $item['accereditatio_no'] );
                                    })
                                    ->first();

                if( is_null( $cer ) ){
                    $cer = new LabsCertify;
                }
                $cer->lab_id                 = $labs->id;
                $cer->lab_code               = $labs->lab_code;
                $cer->certificate_id         = !empty($item['certificate_id'])?$item['certificate_id']:null;
                $cer->certificate_no         = !empty($item['certificate_no'])?$item['certificate_no']:null;
                $cer->certificate_start_date = !empty($item['certificate_start_date'])?HP::convertDate($item['certificate_start_date']):null;
                $cer->certificate_end_date   = !empty($item['certificate_end_date'])?HP::convertDate($item['certificate_end_date']):null;
                $cer->issued_by              = isset($item['issued_by'])?1:2;
                $cer->accereditatio_no       = !empty($item['accereditatio_no'])?$item['accereditatio_no']:null;
                $cer->save();

                if( isset( $item['certificate_file'] ) && !empty($item['certificate_file']) ){

                    HP::singleFileUpload(

                        $item['certificate_file'],
                        $attach_path,
                        (auth()->user()->tax_number ?? null),
                        (auth()->user()->username ?? null),
                        'SSO',
                        (  (new LabsCertify)->getTable() ),
                        $cer->id,
                        'audit_certificate_file',
                        null,
                        null

                    );

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
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('view-'.$model)) {

            $labs = Labs::findOrFail($id);
            $list_scope = LabsScope::where('lab_id', $labs->id)->select('tis_id')->with('tis_standards')->groupBy('tis_id')->get();

            $breadcrumbs = [
                [ "link" => "/home", "name" => "Home"],
                [ "link" => "/section5/labs",  "name" => 'หน่วยตรวจสอบ LAB' ],
                [ "link" => "/section5/labs/$id",  "name" => 'รายละเอียด' ]
            ];

            $this->update_expiration_date_cer($labs);

            return view('section5.manage-lab.show',compact('labs', 'list_scope','breadcrumbs'));
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
        $model = str_slug('manage-lab','-');
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
        $model = str_slug('manage-lab','-');
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
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function html_scope($lab_id){
        $list_scope = LabsScope::where('lab_id', $lab_id)->select('tis_id')->with('tis_standards')->groupBy('tis_id')->get();

        $labs = Labs::findOrFail($lab_id);

        return view('section5.manage-lab.form-scopes-show', ['list_scope' => $list_scope, 'labs' => $labs ]);

    }

    public function infomation_save(Request $request, $id)
    {
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('edit-'.$model)) {

            $labs = Labs::findOrFail($id);

            $requestData = $request->all();
            $requestData['lab_end_date'] = !empty($requestData['lab_end_date'])?HP::convertDate($requestData['lab_end_date'],true):null;

            $columns = [
                "lab_name",
                "lab_address",
                "lab_building",
                "lab_soi",
                "lab_moo",
                "lab_phone",
                "lab_fax",
                "lab_subdistrict_id",
                "lab_district_id",
                "lab_province_id",
                "lab_zipcode",
                "lab_end_date"
            ];

            foreach( $columns AS $column ){
                if( array_key_exists($column , $requestData)&&  $requestData[ $column ] !=  $labs->{$column}  ){
                    LabsHistory::Add($labs->id, $column , $labs->{$column} , $requestData[ $column ] , 'เปลี่ยนแปลงข้อมูล');
                }
            }

            if( !empty($requestData['cancel_state']) && $requestData['cancel_state'] == 1 ){
                LabsHistory::Add($labs->id, 'state' , 1 , 2 , 'ยกเลิกการเป็นหน่วยตรวจสอบ');
                $requestData['state'] = 2;
            }else{
                $requestData['state'] = 1;
            }

            $labs->update($requestData);

            return redirect('section5/labs/'.$labs->id)->with('success_message', 'Manage updated!');
        }
        abort(403);
    }

    public function contact_save(Request $request, $id)
    {
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('edit-'.$model)) {

            $labs = Labs::findOrFail($id);

            $requestData = $request->all();

            $columns = [
                "co_name",
                "co_position",
                "co_mobile",
                "co_phone",
                "co_fax",
                "co_email"
            ];

            foreach( $columns AS $column ){
                if( array_key_exists($column , $requestData)&&  $requestData[ $column ] !=  $labs->{$column}  ){
                    LabsHistory::Add($labs->id, $column , $labs->{$column} , $requestData[ $column ] , 'เปลี่ยนแปลงข้อมูล');
                }
            }

            $labs->update($requestData);

            return redirect('section5/labs/'.$labs->id)->with('success_message', 'Manage updated!');
        }
        abort(403);
    }

    public function account_save(Request $request, $id)
    {
        $model = str_slug('manage-lab','-');
        if(auth()->user()->can('edit-'.$model)) {

            $labs = Labs::findOrFail($id);
            $lab_user_id_new = $request->get('lab_user_id_new');
            $remark          = $request->get('remark');

            if(!empty($lab_user_id_new)){
                if($labs->lab_user_id!=$lab_user_id_new){
                    LabsHistory::Add($labs->id, 'lab_user_id', $labs->lab_user_id, $lab_user_id_new, $remark);
                }

                $labs->lab_user_id = $lab_user_id_new;
                $labs->save();
            }

            return redirect('section5/labs/'.$labs->id)->with('success_message', 'Manage updated!');
        }
        abort(403);
    }

    public function get_scope_detail($id)
    {
        $scope = LabsScope::findOrFail($id);

        $detail = LabsScopeDetail::where('lab_scope_id', $scope->id )->get();

        return view('section5.manage-lab.scopes.scope-detail', ['scope' => $scope, 'detail' => $detail ]);

    }

    //ค้นหาชื่อผู้ใช้งาน
    public function search_user($username){
        $user = SSO_USER::where('username', $username)->first();
        return response()->json(['status' => (!is_null($user) ? true : false), 'user' => $user]);
    }

    public function GetDataTestItem(Request $request)
    {
        $tis_id = $request->get('tis_id');
        $lab_id = $request->get('lab_id');

        $test_item_id = LabsScope::where('lab_id', $lab_id)
                                ->with('test_item')
                                ->whereHas('test_item', function ($query) use($tis_id) {
                                    $query->where('tis_id', $tis_id);
                                })
                                ->select('test_item_id')
                                ->pluck('test_item_id', 'test_item_id')
                                ->toArray();

        $testitem = TestItem::Where('tis_id', $tis_id)
                            ->where('type',1)
                            ->where( function($query) use($lab_id, $tis_id){
                                $ids = DB::table((new LabsScope)->getTable().' AS scope')
                                            ->leftJoin((new TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                            ->where('scope.lab_id', $lab_id )
                                            ->where('test.tis_id', $tis_id )
                                            ->select('test.main_topic_id');
                                $query->whereIn('id', $ids  );
                            })
                            ->groupBy('main_topic_id')
                            ->orderby('no')
                            ->get();

        $scope_list = LabsScope::where('lab_id', $lab_id)
                                ->whereHas('test_item', function ($query) use($tis_id) {
                                    $query->where('tis_id', $tis_id);
                                })
                                ->with(['test_item'])
                              
                                ->get()
                                ->keyBy('id');
        $level = 0;
        $list =   $this->LoopItem($testitem , $level, $test_item_id, $scope_list);

        return response()->json($list);
    }

    public function LoopItem($testitem , $level, $test_item_id, $scope_list)
    {
        $txt = [];
        $level++;
        $i = 0;
        $un_set_arr = [];
        $StateHtml = [ 1 => '<span class=" text-success">Active</span>', 2 => '<span class="text-danger">Not Active</span>' ];

        foreach ( $testitem AS $item ){

            //กรณี ที่มี รายการทดสอบ เดียวกันมากกว่า 2 รายการ
            if( count($scope_list->where('test_item_id',$item->id )) >= 2 ){

                foreach( $scope_list->where('test_item_id',$item->id ) AS $scope ){

                    $date_end  =  !empty($scope->end_date)?'<span class="'.( ($scope->state == 1)? "text-success" : "text-danger" ).'">Exp. '.HP::revertDate($scope->end_date,true).'</span>':null;

                    $active = array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ];

                    $btn = null;
                    if( !empty($scope->id) && $scope->test_item_id == $item->id ){
                        $btn =  '<a href="javascript:void(0)" class="modal_tools" data-id="'.$scope->id.'" >'.((( !empty($item->no)?'ข้อ '.$item->no.' ':'' ).$item->title)).'</a>';
                    }

                    $import = null;
                    if( $scope->type == 2 ){
                        $import = '<span class="text-muted"><em>(นำเข้าข้อมูลเมื่อ :'.(HP::revertDate($scope->created_at,true)).')</em></span>';
                    }

                    $remarks = null;
                    if( !empty($scope->remarks) ){
                        $remarks = '<span class="text-muted"><em>('.($scope->remarks).')</em></span>';
                    }

                    $key_on = $i++;

                    $data = new stdClass;
                    $data->text = (!empty($btn)?$btn:( (( !empty($item->no)?'ข้อ '.$item->no.' ':'' ).$item->title) )).(isset($remarks)?' '.$remarks:null).'<span class="pull-right">'.(isset($import)?' '.$import:null).(isset($date_end)?' '.$date_end:null).(isset($active)?' <span class="text-muted">|</span> '.$active:null).'</span>';
                    $data->href = '#parent_level_id-'.$item->id;
                    $result = $this->LoopItem($item->TestItemParentData, $level, $test_item_id, $scope_list);
                    $data->tags = [ count($result) ];
                    if(count( $result) >= 1 ){
                        $data->nodes =  $result;
                        $txt[] =   $data;
                    }else{
                        if( in_array( $item->id,  $test_item_id ) ){
                            $txt[] =   $data;
                        }
                    }
 
                }

            }else{

                //รายการทดสอบ จาก scope Lab
                $scope    = $scope_list->where('test_item_id',$item->id )->last();
                $import   = null;
                $remarks  = null;
                $btn      = null;
                $date_end = null;
                $active   = null;
                
                if( !empty($scope)){
                    $date_end  =  !empty($scope->end_date)?'<span class="'.( ($scope->state == 1)? "text-success" : "text-danger" ).'">Exp. '.HP::revertDate($scope->end_date,true).'</span>':null;

                    if(  !empty($scope->state) ){
                        $active =  array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ];
                    }
         
                    if( !empty($scope->id) && $scope->test_item_id == $item->id ){
                        $btn =  '<a href="javascript:void(0)" class="modal_tools" data-id="'.$scope->id.'" >'.((( !empty($item->no)?'ข้อ '.$item->no.' ':'' ).$item->title)).'</a>';
                    }

                    if( !empty($scope->type) && $scope->type == 2 ){
                        $import = '<span class="text-muted"><em>(นำเข้าข้อมูลเมื่อ :'.(HP::revertDate($scope->created_at,true)).')</em></span>';
                    }

                    if( !empty($scope->remarks) ){
                        $remarks = '<span class="text-muted"><em>('.($scope->remarks).')</em></span>';
                    }

                }

                $key_on = $i++;

                $data = new stdClass;
                $data->text = (!empty($btn)?$btn:( (( !empty($item->no)?'ข้อ '.$item->no.' ':'' ).$item->title) )).(isset($remarks)?' '.$remarks:null).'<span class="pull-right">'.(isset($import)?' '.$import:null).(isset($date_end)?' '.$date_end:null).(isset($active)?' <span class="text-muted">|</span> '.$active:null).'</span>';
                $data->href = '#parent_level_id-'.$item->id;
                $result = $this->LoopItem($item->TestItemParentData, $level, $test_item_id, $scope_list);
                $data->tags = [ count($result) ];
                if(count( $result) >= 1 ){
                    $data->nodes =  $result;
                    $txt[] =   $data;
                }else{
                    if( in_array( $item->id,  $test_item_id ) ){
                        $txt[] =   $data;
                    }
                }
    

            }

        }

        return $txt;
    }


    public function SaveStdTestItem(Request $request)
    {
        $requestData = $request->all();

        $id = $requestData['id'];

        $labs = Labs::findOrFail($id);

        $tax_number = !empty($labs->taxid )?$labs->taxid:(!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        $folder_app = ($labs->lab_code).'/';

        $msg = 'error';
        if( isset( $requestData['repeater-scope'] ) ){

            $repeater_scope =  $requestData['repeater-scope'];

            foreach( $repeater_scope as $itemS ){

                $itemS['start_date'] = !empty($itemS['start_date'])?HP::convertDate($itemS['start_date'],true):null;
                $itemS['end_date'] = !empty($itemS['start_date'])?HP::convertDate($itemS['end_date'],true):null;

                $scopes = LabsScope::where('lab_id', $labs->id )
                                    ->where( function($query) use($itemS){
                                        $query->where('tis_id', $itemS['tis_id'] )->where('test_item_id', $itemS['test_item_id'] );
                                    })
                                    ->where( function($query) use($itemS){
                                        $query->where('start_date', $itemS['start_date'] )->where('end_date', $itemS['end_date'] );
                                    })
                                    ->first();

                if(is_null($scopes)){
                    $scopes = new LabsScope;

                    $scopes->lab_id                 = $labs->id;
                    $scopes->lab_code               = $labs->lab_code;
                    $scopes->ref_lab_application_no = null;
                    $scopes->tis_id                 = !empty($itemS['tis_id'])?$itemS['tis_id']:null;
                    $scopes->tis_tisno              = !empty($itemS['tis_tisno'])?$itemS['tis_tisno']:null;
                    $scopes->test_item_id           = !empty($itemS['test_item_id'])?$itemS['test_item_id']:null;
                    $scopes->state                  = 1;
                    $scopes->start_date             = $itemS['start_date'];
                    $scopes->end_date               = $itemS['end_date'];

                    $scopes->type                  = 2;
                    $scopes->remarks               = !empty($itemS['remarks'])?$itemS['remarks']:null;

                    $scopes->save();
                }

                //เพิ่มตาราง Detail
                $detail = LabsScopeDetail::where('lab_id', $labs->id )
                                            ->where('lab_scope_id', $scopes->id )
                                            ->where( function($query) use($itemS){
                                                $query->where('test_tools_id', $itemS['test_tools_id'] )
                                                    ->where('test_tools_no', $itemS['test_tools_no'] )
                                                    ->where('capacity', $itemS['capacity'] )
                                                    ->where('range', $itemS['range'] )
                                                    ->where('true_value', $itemS['true_value'] )
                                                    ->where('fault_value', $itemS['fault_value'] )
                                                    ->where('test_duration', $itemS['test_duration'] )
                                                    ->where('test_price', $itemS['test_price'] );

                                            })
                                            ->first();
                if(is_null($detail)){
                    $detail = new LabsScopeDetail;

                    $detail->lab_id                       = $labs->id;
                    $detail->lab_code                     = $labs->lab_code;
                    $detail->ref_lab_application_no       = null;
                    $detail->ref_lab_application_scope_id = null;
                    $detail->lab_scope_id                 = !empty($scopes->id)?$scopes->id:null;

                    $detail->test_tools_id                = !empty($itemS['test_tools_id'])?$itemS['test_tools_id']:null;
                    $detail->test_tools_no                = !empty($itemS['test_tools_no'])?$itemS['test_tools_no']:null;
                    $detail->capacity                     = !empty($itemS['capacity'])?$itemS['capacity']:null;
                    $detail->range                        = !empty($itemS['range'])?$itemS['range']:null;
                    $detail->true_value                   = !empty($itemS['true_value'])?$itemS['true_value']:null;
                    $detail->fault_value                  = !empty($itemS['fault_value'])?$itemS['fault_value']:null;
                    $detail->test_duration                = !empty($itemS['test_duration'])?$itemS['test_duration']:null;
                    $detail->test_price                   = !empty($itemS['test_price'])?$itemS['test_price']:null;

                    $detail->type                         = 2;
                    $detail->save();
                }

                //บันทึกไฟล์
                if( isset($itemS['attach_file']) && !empty($itemS['attach_file']) ){
                    HP::singleFileUpload(
                        $itemS['attach_file'],
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new LabsScope)->getTable() ),
                        $scopes->id,
                        'file_labs_scopes',
                        'ไฟล์แนบเพิ่มรายการทดสอบที่รับการแต่งตั้ง ผ่านระบบหน่วยตรวจสอบ (LAB)'
                    );
                }

            }

            $msg = 'success';
        }

        return response()->json($msg);

    }

    public function SaveScopeDetail(Request $request)
    {
        $requestData = $request->all();

        $id = $requestData['id'];

        $labs = Labs::findOrFail($id);

        $lab_scope_id = $requestData['lab_scope_id'];
        $detail_id = $requestData['mt_id'];

        if( !empty($detail_id) ){
            $detail = LabsScopeDetail::where('id', $detail_id  )->first();

            $detail->test_tools_id                = !empty($requestData['mt_test_tools'])?$requestData['mt_test_tools']:null;
            $detail->test_tools_no                = !empty($requestData['mt_test_tools_no'])?$requestData['mt_test_tools_no']:null;
            $detail->capacity                     = !empty($requestData['mt_capacity'])?$requestData['mt_capacity']:null;
            $detail->range                        = !empty($requestData['mt_range'])?$requestData['mt_range']:null;
            $detail->true_value                   = !empty($requestData['mt_true_value'])?$requestData['mt_true_value']:null;
            $detail->fault_value                  = !empty($requestData['mt_fault_value'])?$requestData['mt_fault_value']:null;
            $detail->test_duration                = !empty($requestData['mt_test_duration'])?$requestData['mt_test_duration']:null;
            $detail->test_price                   = !empty($requestData['mt_test_price'])?$requestData['mt_test_price']:null;
            $detail->save();
        }else{
            $detail = new LabsScopeDetail;

            $detail->lab_id                       = $labs->id;
            $detail->lab_code                     = $labs->lab_code;
            $detail->ref_lab_application_no       = null;
            $detail->ref_lab_application_scope_id = null;
            $detail->lab_scope_id                 = !empty($lab_scope_id)?$lab_scope_id:null;

            $detail->test_tools_id                = !empty($requestData['mt_test_tools'])?$requestData['mt_test_tools']:null;
            $detail->test_tools_no                = !empty($requestData['mt_test_tools_no'])?$requestData['mt_test_tools_no']:null;
            $detail->capacity                     = !empty($requestData['mt_capacity'])?$requestData['mt_capacity']:null;
            $detail->range                        = !empty($requestData['mt_range'])?$requestData['mt_range']:null;
            $detail->true_value                   = !empty($requestData['mt_true_value'])?$requestData['mt_true_value']:null;
            $detail->fault_value                  = !empty($requestData['mt_fault_value'])?$requestData['mt_fault_value']:null;
            $detail->test_duration                = !empty($requestData['mt_test_duration'])?$requestData['mt_test_duration']:null;
            $detail->test_price                   = !empty($requestData['mt_test_price'])?$requestData['mt_test_price']:null;

            $detail->type                         = 2;
            $detail->save();
        }
        return response()->json('success');
    }

    public function DeleteScopeDetail($id)
    {
        LabsScopeDetail::destroy($id);
        return response()->json('success');
    }

    public function get_scope_active($id)
    {
        $labs = Labs::findOrFail($id);

        $scope_group = LabsScope::where('lab_id', $labs->id )->where('state',1)->select('tis_id')->with(['tis_standards'])->groupBy('tis_id')->get();

        $scope = LabsScope::where('lab_id', $labs->id )->where('state',1)->with(['test_item'])->get();

        return view('section5.manage-lab.scopes.minus-scope', compact('scope_group','scope'));

    }

    //บันทึกลดขอบข่าย
    public function minus_scope(Request $request){

        $data = $request->all();

        if(array_key_exists('scope_id', $data) && count($data['scope_id']) > 0){
            foreach ($data['scope_id'] as $scope_id) {
                $scope                   = LabsScope::find($scope_id);
                $scope->close_state_date = date('Y-m-d H:i:s');
                $scope->close_remarks    = !empty($data['mn_close_remarks'])?$data['mn_close_remarks']:null;
                $scope->close_by         = auth()->user()->getKey();
                $scope->state            = 2 ;
                $scope->save();
            }

            // if(array_key_exists('lab_id', $data)){
            //     $this->sync_to_elicense($data['lab_id']);
            // }

            return 'success';
        }
    }

    public function update_expiration_date_cer($labs){

        $cer = LabsCertify::where('lab_id', $labs->id )->whereNotNull('certificate_id')->get();

        foreach( $cer AS $certify ){

            if( !empty($certify->CheckCertifyRenew) && !empty($certify->CheckCertifyRenew->end_date) && ($certify->CheckCertifyRenew->end_date >  $certify->certificate_end_date) ){

                $new_end_date = $certify->CheckCertifyRenew->end_date;

                if( $certify->certificate_end_date < $new_end_date ){

                    $log_cer                           =  new LabsCertifyLog;
                    $log_cer->labs_certify_id          =  $certify->id;
                    $log_cer->old_end_date             =  $certify->certificate_end_date;
                    $log_cer->new_end_date             =  $new_end_date ;
                    $log_cer->app_cert_lab_file_all_id =  $certify->CheckCertifyRenew->id;
                    $log_cer->created_by               =  auth()->user()->getKey();
                    $log_cer->save();

                    $certify->certificate_end_date  =  $new_end_date;
                    $certify->save();

                    foreach( $certify->certify_scope AS $scope ){

                        if( $scope->end_date <  $new_end_date ){

                            $log_scope                           =  new LabsScopeLog;
                            $log_scope->labs_certify_id          =  $certify->id;
                            $log_scope->labs_scopes_id           =  $scope->id;
                            $log_scope->old_end_date             =  $scope->end_date;
                            $log_scope->new_end_date             =  $new_end_date;
                            $log_scope->app_cert_lab_file_all_id =  $certify->CheckCertifyRenew->id;
                            $log_scope->created_by               =  auth()->user()->getKey();
                            $log_scope->save();

                            LabsScope::where('id', $scope->id )->update(['end_date' =>  $new_end_date ]);
                        }

                    }

                }

            }

        }


    }



    public function update_expiration_date_scope(Request $request)
    {
        $data = $request->all();
        $msg = 'error';
        if( array_key_exists('id', $data) ){

           $cer = LabsCertify::where('id', $data['id'] )->first();

           if( !is_null( $cer ) ){

                if( $cer->certificate_end_date < $data['end_date'] ){
                    $log_cer                           =  new LabsCertifyLog;
                    $log_cer->labs_certify_id          =  $cer->id;
                    $log_cer->old_end_date             =  $cer->certificate_end_date;
                    $log_cer->new_end_date             =  $data['end_date'];
                    $log_cer->app_cert_lab_file_all_id =  $data['app_cert_lab_file_all_id'];
                    $log_cer->created_by               =  auth()->user()->getKey();
                    $log_cer->save();

                    $cer->certificate_end_date  =  $data['end_date'];
                    $cer->save();

                    foreach( $cer->certify_scope AS $scope ){

                        if( $scope->end_date < $data['end_date'] ){

                            $log_scope                           =  new LabsScopeLog;
                            $log_scope->labs_certify_id          =  $cer->id;
                            $log_scope->labs_scopes_id           =  $scope->id;
                            $log_scope->old_end_date             =  $scope->end_date;
                            $log_scope->new_end_date             =  $data['end_date'];
                            $log_scope->app_cert_lab_file_all_id =  $data['app_cert_lab_file_all_id'];
                            $log_scope->created_by               =  auth()->user()->getKey();
                            $log_scope->save();

                            LabsScope::where('id', $scope->id )->update(['end_date' =>  $data['end_date'] ]);
                        }

                    }
                }

           }
           $msg = 'success';
        }

        return $msg;
    }

    public function get_log_certify($id)
    {
        $datalog = LabsCertifyLog::where('labs_certify_id', $id )->get();

        return view('section5.manage-lab.html.log-certify', compact('datalog'));
    }

    public function sync_to_elicense(Request $request){

        $result = null;
        $lab_id = $request->get('lab_id', null);

        if(!is_null($lab_id)){
            $this->sync_to_elicense_action($lab_id);
            $result = 'success';
        }else{
            $result = 'fail';
        }

        return $result;
    }

    //อัพเดทข้อมูล Lab ไป e-license
    private function sync_to_elicense_action($lab_id){

        $lab = Labs::find($lab_id);

        //สร้างบัญชีผู้ใช้งาน
        $e_user = RosUsers::where('lab_code', $lab->lab_code)->first();
        if(is_null($e_user)){//ไม่พบบัญชีจากรหัส
            $e_user = RosUsers::where('username', $lab->lab_code)->first();
        }else{//พบบัญชีจากรหัส
            //ค้นหาเพื่อเช็คว่ามีบัญชีอื่นที่ username=lab_code แต่คนละบัญชี
            $e_user_temp = RosUsers::where('username', $lab->lab_code)->first();
            if(!is_null($e_user_temp) && $e_user_temp->id!=$e_user->id){//พบบัญชี แต่ไม่ใช่บัญชีเดียวกับที่จะใช้อัพเดท
                $e_user_temp->username = $e_user_temp->username.'-'.str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
                $e_user_temp->save();
            }
        }

        if(is_null($e_user)){//ยังไม่มีบัญชีผู้ใช้งาน
            $user_sso = SSO_USER::find($lab->lab_user_id);
            if(!is_null($user_sso)){
                $user_data = $user_sso->toArray();
                $user_columns = (new RosUsers)->Columns;//ชื่อคอลัมภ์ใน user elicense
                $user_columns = array_flip($user_columns);//สลับชื่อคอลัมภ์(value) มาเป็น key ของ Array;
                $user_data = array_intersect_key($user_data, $user_columns);//ตัดเอาเฉพาะฟิลด์ข้อมูลที่มีใน user elicense ไว้

                unset($user_data['id']); //ตัด id ออก
                $user_data['lab_code'] = $lab->lab_code;//รหัส Lab ที่ใช้อ้างอิง
                $user_data['username'] = $lab->lab_code;//เปลี่ยน username ใช้รหัสห้อง Lab
                $user_data['name']     = $lab->lab_name;//เปลี่ยน name ใช้ชื่อห้อง Lab
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

                    $mail_format = new ManageLabSyncMail([
                        'applicant_name'     => $lab->name,
                        'lab_name'           => $lab->lab_name,
                        'start_date'         => HP::DateThaiFull($lab->lab_start_date),
                        'url'                => $url,
                        'username'           => $user_data['username'],
                        'password'           => $password
                    ]);
                    Mail::to($user_data['email'])->send($mail_format);
                }
            }
        }else{//มีบัญชีผู้ใช้งานแล้ว

            $e_user->username = $lab->lab_code;
            $e_user->lab_code = $lab->lab_code;
            $e_user->save();

            $user_id = $e_user->id;
        }

        if(isset($user_id)){//ได้ผู้ใช้งานในระบบ e-License

            //บันทึกกลุ่มผู้ใช้งาน
            RosUserGroupMap::where('user_id', $user_id)->where('group_id', 15)->delete();//ลบออกก่อนถ้ามี
            $group_map = new RosUserGroupMap;
            $group_map->user_id  = $user_id;
            $group_map->group_id = 15;
            $group_map->save();

            /*** อัพเดทข้อมูลมาตรฐานมอก. Lab ***/
            //ลบออกจากมอก.ที่ตรวจได้ก่อน
            $standards = RosStandardTisi::where('for_lab_use', 'LIKE', '%"'.$user_id.'"%')->get();
            foreach ($standards as $standard) {
                $standard_labs = (array)json_decode($standard->for_lab_use, true);
                if (($key = array_search($user_id, $standard_labs)) !== false) {
                    unset($standard_labs[$key]);
                    $standard->for_lab_use = json_encode(array_values(array_unique($standard_labs)));
                    $standard->save();
                }
            }

            //อัพเดทข้อมูลมาตรฐานมอก. Lab ที่สามารถทดสอบผลิตภัณฑ์ตามมาตรฐานนั้นๆได้
            if($lab->state==1){//ถ้าสถานะ lab ไม่ถูกยกเลิก
                $tis_numbers = $lab->scope_standard_active()
                                   ->get()
                                   ->pluck('tis_tisno')
                                   ->toArray();
                $tis_numbers = array_unique($tis_numbers);
                foreach ($tis_numbers as $tis_number) {
                    $standard = RosStandardTisi::where('tis_number', $tis_number)->first();
                    if(!is_null($standard)){
                        $standard_labs = array_values((array)json_decode($standard->for_lab_use, true));
                        $standard_labs[] = (string)$user_id;
                        $standard->for_lab_use = json_encode(array_unique($standard_labs));
                        $standard->save();
                    }
                }
            }

        }

    }

    public function data_list_cer(Request $request)
    {

        $tax_id =  $request->get('tax_id');
        $filter_search =  $request->get('filter_search');

        $query = CertificateExport::query()->when( $filter_search , function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search);
                                                return  $query->where(function ($query2) use($search_full) {
                                                                $ids = CertiLab::Where(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%")->select('id');
                                                                $query2->Where(DB::raw("REPLACE(certificate_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhereIn('certificate_for',  $ids  );
                                                            });
                                            })
                                            ->where(function($query) use( $tax_id ){
                                                $ids = CertiLab::where('tax_id', $tax_id )->whereNotNull('tax_id')->select('id');
                                                $query->whereIN('certificate_for',  $ids  );
                                            })
                                            ->whereHas('cert_labs_file', function ($query)  {
                                                $query->where('state',  1  );
                                            });
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('lab_name', function ($item) {
                                $CertiLabTo = $item->CertiLabTo;
                                return !is_null($CertiLabTo)?$CertiLabTo->lab_name:null;
                            })
                            ->addColumn('certificate_no', function ($item) {
                                return !is_null($item->certificate_no)?$item->certificate_no:null;
                            })
                            ->addColumn('accereditatio_no', function ($item) {
                                return !is_null($item->accereditatio_no)?$item->accereditatio_no:null;
                            })
                            ->addColumn('certificate_date_start', function ($item) {
                                $cert_labs_file_all =  $item->cert_labs_file_all()->where('state', 1)->get()->last();
                                return !empty($cert_labs_file_all->start_date)?HP::revertDate($cert_labs_file_all->start_date):null;
                            })
                            ->addColumn('certificate_date_end', function ($item) {
                                $cert_labs_file_all =  $item->cert_labs_file_all()->where('state', 1)->get()->last();
                                return !empty($cert_labs_file_all->end_date)?HP::revertDate($cert_labs_file_all->end_date):null;
                            })
                            ->addColumn('status', function ($item) {
                                $cert_labs_file_all =  $item->cert_labs_file_all()->where('state', 1)->get()->last();
                                $certificate_date_end = !empty($cert_labs_file_all->end_date)?$cert_labs_file_all->end_date:null;
                                if( $certificate_date_end >= date('Y-m-d') ){
                                    return 'ใช้งาน';
                                }else{
                                    return 'หมดอายุ';
                                }
                            })
                            ->addColumn('action', function ($item) {
                                $cert_labs_file_all =  $item->cert_labs_file_all()->where('state', 1)->get()->last();
                                $certificate_date_end = !is_null($cert_labs_file_all->end_date)?$cert_labs_file_all->end_date:null;
                                if( $certificate_date_end >= date('Y-m-d') ){
                                    return '<button class="btn btn-info btn_select_cer" type="button" data-accereditatio_no="'.($item->accereditatio_no).'" data-id="'.($item->id).'" data-table="'.((new CertificateExport)->getTable() ).'" data-certificate_no="'.(!is_null($item->certificate_no)?$item->certificate_no:null).'" data-date_end="'.( !empty($cert_labs_file_all->end_date)?HP::revertDate($cert_labs_file_all->end_date):null ).'" data-date_start="'.( !empty($cert_labs_file_all->start_date)?HP::revertDate($cert_labs_file_all->start_date):null ).'"> เลือก </button>';
                                }else{
                                    return '<button class="btn btn-info" type="button" disabled> เลือก </button>';
                                }

                            })
                            ->rawColumns(['checkbox', 'action'])
                            ->make(true);
    }

}
