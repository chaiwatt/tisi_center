<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawCategoryOperation;

class LawCategoryOperationController extends Controller
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

        $query = LawCategoryOperation::query()->when($filter_search, function ($query, $filter_search){
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
                                return !empty($item->title)?$item->title:null;
                            })                
                            ->addColumn('status', function ($item) {
                                return  @$item->StateIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/basic/category-operation','Laws\Basic\\LawCategoryOperationController@destroy', 'law-basic-category-operation');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at','date'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/category-operation",  "name" => 'หมวดหมู่การดำเนินงาน' ],
            ];
    
            return view('laws.basic.category-operation.index',compact('breadcrumbs'));
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/category-operation",  "name" => 'หมวดหมู่การดำเนินงาน' ],
                [ "link" => "/law/basic/category-operation/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.basic.category-operation.create',compact('breadcrumbs'));
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            LawCategoryOperation::create($requestData);
            return redirect('law/basic/category-operation')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
    
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('view-'.$model)) {
            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/category-operation",  "name" => 'หมวดหมู่การดำเนินงาน' ],
                [ "link" => "/law/basic/category-operation/".$id,  "name" => 'รายละเอียด' ],
            ];
            $categoryoperation = LawCategoryOperation::findOrFail($id);
            return view('laws.basic.category-operation.show',compact('breadcrumbs','categoryoperation'));
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/category-operation",  "name" => 'หมวดหมู่การดำเนินงาน' ],
                [ "link" => "/law/basic/category-operation/".$id."/edit",  "name" => 'แก้ไข' ],
            ];

            $categoryoperation = LawCategoryOperation::findOrFail($id);
            return view('laws.basic.category-operation.edit',compact('breadcrumbs','categoryoperation'));
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
                
            $categoryoperation = LawCategoryOperation::findOrFail($id);
            $categoryoperation->update($requestData);
            return redirect('law/basic/category-operation')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');

    
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
        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('delete-'.$model)) {
            
            LawCategoryOperation::destroy($id);
            return redirect('law/basic/category-operation')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
    
        }
        abort(403);
    }

            /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-basic-category-operation','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawCategoryOperation;
            $resulte =  LawCategoryOperation::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawCategoryOperation::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function save_and_copy(Request $request)
    {
        $model = str_slug('law-basic-category-operation','-');

        $msg = 'error';
        $data = null;
        if(auth()->user()->can('add-'.$model)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $data =  LawCategoryOperation::create($requestData);
        
            $msg = 'success';
        }

        return response()->json(['msg' =>  $msg, 'data' => $data ]);
    }
}
