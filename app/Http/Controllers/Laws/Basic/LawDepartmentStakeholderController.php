<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawDepartmentStakeholder;
use App\Models\Basic\Amphur;
use App\Models\Basic\District;


class LawDepartmentStakeholderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');
        $filter_created_at = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawDepartmentStakeholder::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                    });
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('state', $filter_status);
                                    }else{
                                        return $query->where('state', '<>', 1)->orWhereNull('state');
                                    }
                                })
                                ->when($filter_created_at, function ($query, $filter_created_at){
                                    return $query->whereDate('created_at', $filter_created_at);
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('province', function ($item) {
                                return !is_null($item->province) ? $item->province->PROVINCE_NAME : '-' ;
                            })
                            ->addColumn('email', function ($item) {
                                return $item->email;
                            })
                            ->addColumn('status', function ($item) {
                                return  @$item->StateIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                                return !empty($item->CreatedName)?$item->CreatedName:'-';
                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/basic/department-stakeholder','Laws\Basic\\LawDepartmentStakeholderController@destroy', 'law-department-stakeholder');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department-stakeholder",  "name" => 'หน่วยงานผู้มีส่วนได้เสีย' ],
            ];
            return view('laws.basic.department-stakeholder.index',compact('breadcrumbs'));
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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('add-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department-stakeholder",  "name" => 'หน่วยงานผู้มีส่วนได้เสีย' ],
                [ "link" => "/law/basic/department-stakeholder/create",  "name" => 'เพิ่ม' ],

            ];

            return view('laws.basic.department-stakeholder.create', compact('breadcrumbs'));
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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('add-'.$model)) {


            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['tis_id']   = !empty( $request->input('tis_id') )?json_encode($request->input('tis_id')):null;

            LawDepartmentStakeholder::create($requestData);
            return redirect('law/basic/department-stakeholder')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');

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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {

            $lawdepartment = LawDepartmentStakeholder::findOrFail($id);
            $amphurs       = Amphur::where('PROVINCE_ID', $lawdepartment->province_id)->pluck('AMPHUR_NAME', 'AMPHUR_ID');
            $districts     = District::where('AMPHUR_ID', $lawdepartment->district_id)->pluck('DISTRICT_NAME', 'DISTRICT_ID');
            $lawdepartment->tis_id  =  !empty( $lawdepartment->tis_id )?json_decode($lawdepartment->tis_id,true):null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department-stakeholder",  "name" => 'หน่วยงานผู้มีส่วนได้เสีย' ],
                [ "link" => "/law/basic/department-stakeholder/$id",  "name" => 'รายละเอียด' ],

            ];

            return view('laws.basic.department-stakeholder.show', compact('lawdepartment', 'amphurs', 'districts', 'breadcrumbs'));
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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('edit-'.$model)) {

            $lawdepartment = LawDepartmentStakeholder::findOrFail($id);
            $amphurs       = Amphur::whereNull('state')->where('PROVINCE_ID', $lawdepartment->province_id)->pluck('AMPHUR_NAME', 'AMPHUR_ID');
            $districts     = District::whereNull('state')->where('AMPHUR_ID', $lawdepartment->district_id)->pluck('DISTRICT_NAME', 'DISTRICT_ID');
            $lawdepartment->tis_id  =  !empty( $lawdepartment->tis_id )?json_decode($lawdepartment->tis_id,true):null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department-stakeholder",  "name" => 'หน่วยงานผู้มีส่วนได้เสีย' ],
                [ "link" => "/law/basic/department-stakeholder/$id/edit",  "name" => 'แก้ไข' ],

            ];

            return view('laws.basic.department-stakeholder.edit', compact('lawdepartment', 'amphurs', 'districts', 'breadcrumbs'));
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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('edit-'.$model)) {
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $lawdepartment = LawDepartmentStakeholder::findOrFail($id);
            $requestData['tis_id']   = !empty( $request->input('tis_id') )?json_encode($request->input('tis_id')):null;

            $lawdepartment->update($requestData);

            return redirect('law/basic/department-stakeholder')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-department-stakeholder','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawDepartmentStakeholder::destroy($id);
            return redirect('law/basic/department-stakeholder')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('testtools','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawDepartmentStakeholder;
            $resulte = LawDepartmentStakeholder::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result     = LawDepartmentStakeholder::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

}
