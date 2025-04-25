<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawDepartment;


class LawDepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');
        $filter_type        = $request->input('filter_type');
        $filter_created_at  = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawDepartment::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                        $query->Orwhere(DB::Raw("REPLACE(title_short,' ','')"),  'LIKE', "%$search_full%");
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
                                })
                                ->when($filter_type, function ($query, $filter_type){
                                    return $query->where('type', $filter_type);
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('type', function ($item) {
                                $type_arr = [1 => 'หน่วยงานภายใน', 2 => 'หน่วยงานภายนอก'];
                                return array_key_exists( $item->type,  $type_arr )?$type_arr [ $item->type ]:'-';
                            })
                            ->addColumn('title_short', function ($item) {
                                return !empty($item->title_short)?$item->title_short:null;
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
                                return HP::buttonActionLaw( $item->id, 'law/basic/department','Laws\Basic\\LawDepartmentController@destroy', 'law-departments');
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department",  "name" => 'หน่วยงานต้นเรื่อง' ],
            ];
            return view('laws.basic.department.index',compact('breadcrumbs'));
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department",  "name" => 'หน่วยงานต้นเรื่อง' ],
                [ "link" => "/law/basic/department/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.basic.department.create',compact('breadcrumbs'));
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('add-'.$model)) {


            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['other']  =  isset($request->other)?1:0;

            LawDepartment::create($requestData);
            return redirect('law/basic/department')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
 
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('view-'.$model)) {
            $lawdepartment = LawDepartment::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department",  "name" => 'หน่วยงานต้นเรื่อง' ],
                [ "link" => "/law/basic/department/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('laws.basic.department.show',compact('lawdepartment','breadcrumbs'));
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('edit-'.$model)) {
            $lawdepartment = LawDepartment::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/department",  "name" => 'หน่วยงานต้นเรื่อง' ],
                [ "link" => "/law/basic/department/$id/edit",  "name" => 'แก้ไข' ],
            ];
            return view('laws.basic.department.edit',compact('lawdepartment','breadcrumbs'));
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('edit-'.$model)) {
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['other']  =  isset($request->other)?1:0;
            
            $lawdepartment = LawDepartment::findOrFail($id);
            $lawdepartment->update($requestData);

            return redirect('law/basic/department')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-departments','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawDepartment::destroy($id);
            return redirect('law/basic/department')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
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

            $db = new LawDepartment;
            $resulte =  LawDepartment::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawDepartment::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

}
