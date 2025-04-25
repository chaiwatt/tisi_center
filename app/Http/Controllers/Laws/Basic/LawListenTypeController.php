<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawListenType;

class LawListenTypeController extends Controller
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

        $query = LawListenType::query()->when($filter_search, function ($query, $filter_search){
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
                            ->addColumn('bookgroup', function ($item) {
                                return !empty($item->BookGroupTitle)?$item->BookGroupTitle:null;
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
                                return HP::buttonActionLaw( $item->id, 'law/basic/listen-type','Laws\Basic\\LawListenTypeController@destroy', 'law-basic-listen-type');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at','date','icon'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/listen-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
            ];
            return view('laws.basic.listen-type.index',compact('breadcrumbs'));
        }
        abort(403);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/listen-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/listen-type/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.basic.listen-type.create',compact('breadcrumbs'));
        }
        return response(view('403'), 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            LawListenType::create($requestData);
            return redirect('law/basic/listen-type')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $listentype = LawListenType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/listen-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/listen-type/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('laws.basic.listen-type.show', compact('listentype','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            $listentype = LawListenType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/listen-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/listen-type/$id/edit",  "name" => 'แก้ไข' ],
            ];
            return view('laws.basic.listen-type.edit', compact('listentype','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
           $listentype = LawListenType::findOrFail($id);
           $listentype->update($requestData);

            return redirect('law/basic/listen-type')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
        return response(view('403'), 403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawListenType::destroy($id);
            return redirect('law/basic/listen-type')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-basic-listen-type','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawListenType;
            $resulte =  LawListenType::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawListenType::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function save_and_copy(Request $request)
    {
        $model = str_slug('law-basic-listen-type','-');

        $msg = 'error';
        $data = null;
        if(auth()->user()->can('add-'.$model)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $data =  LawListenType::create($requestData);
        
            $msg = 'success';
        }

        return response()->json(['msg' =>  $msg, 'data' => $data ]);
    }

}



