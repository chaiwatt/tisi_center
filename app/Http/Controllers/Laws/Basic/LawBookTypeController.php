<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawBookType;
use App\Models\Law\Basic\LawBookGroup;

class LawBookTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_search      = $request->input('filter_search');
        $filter_book_group      = $request->input('filter_book_group');
        $filter_status      = $request->input('filter_status');
        $filter_created_at = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawBookType::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $book_group  = LawBookGroup::Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")->select('id');
            
                                    $query->where( function($query) use($search_full,$book_group) {
                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                              ->orwhereIn('book_group_id', $book_group);
                                    });
                                })
                                ->when($filter_book_group, function ($query, $filter_book_group){
                                    return $query->where('book_group_id', $filter_book_group);
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('state', $filter_status);
                                    }else{
                                        return $query->where('state', '<>', 1);
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
                            ->addColumn('icon', function ($item) {
                                return !empty($item->icons)?$item->Icon:null;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/basic/book-type','Laws\Basic\\LawBookTypeController@destroy', 'law-book-type');
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/book-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
            ];
            return view('laws.basic.book-type.index',compact('breadcrumbs'));
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/book-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/book-type/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.basic.book-type.create',compact('breadcrumbs'));
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            LawBookType::create($requestData);
            return redirect('law/basic/book-type')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $booktype = LawBookType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/book-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/book-type/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('laws.basic.book-type.show', compact('booktype','breadcrumbs'));
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            $booktype = LawBookType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/book-type",  "name" => 'ประเภทเอกสารห้องสมุด' ],
                [ "link" => "/law/basic/book-type/$id/edit",  "name" => 'แก้ไข' ],
            ];
            return view('laws.basic.book-type.edit', compact('booktype','breadcrumbs'));
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
           $booktype = LawBookType::findOrFail($id);
           $booktype->update($requestData);

            return redirect('law/basic/book-type')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
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
        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawBookType::destroy($id);
            return redirect('law/basic/book-type')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-book-type','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawBookType;
            $resulte =  LawBookType::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawBookType::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function save_and_copy(Request $request)
    {
        $model = str_slug('law-book-type','-');

        $msg = 'error';
        $data = null;
        if(auth()->user()->can('add-'.$model)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $data =  LawBookType::create($requestData);
        
            $msg = 'success';
        }

        return response()->json(['msg' =>  $msg, 'data' => $data ]);
    }

}



