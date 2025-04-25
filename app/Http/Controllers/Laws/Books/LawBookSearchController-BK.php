<?php

namespace App\Http\Controllers\Laws\Books;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Law\Books\LawBookManage;
use App\Models\Law\Books\LawBookManageVisit;

use App\Models\Tis\Standard;
use Illuminate\Support\Facades\Session;

class LawBookSearchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {

        $filter_search    = $request->input('filter_search');
        $filter_group     = $request->input('filter_group');
        $filter_type      = $request->input('filter_type');
        $filter_tap_type  = $request->input('filter_tap_type');

        $query = LawBookManage::query()->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where( function($query) use($search_full) {
                                                        $query->Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                                                ->OrwhereHas('book_type', function ($query) use($search_full) {
                                                                    $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                })
                                                                ->OrwhereHas('book_group', function ($query) use($search_full) {
                                                                    $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                });
                                                    });
                                        })
                                        ->when($filter_group, function ($query, $filter_group){
                                            return $query->where('basic_book_group_id', $filter_group);
                                        })
                                        ->when($filter_type, function ($query, $filter_type){
                                            return $query->where('basic_book_type_id', $filter_type);
                                        })
                                        ->when($filter_tap_type, function ($query, $filter_tap_type){
                                            return $query->WhereIn('basic_book_type_id', $filter_tap_type );
                                        })
                                        ->where( function($query){
                                            $query->whereHas('manage_access', function ($query) {
                                                        // $query->whereJsonContains('access',  2);
                                                        $query->where(DB::Raw("REPLACE(access,' ','')"),  'LIKE', "%2%");
                                                    });
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            // ->addColumn('accordion', function ($item){
                            //     return view('laws.books.search.table.accordion',compact('item'));
                      
                            // })
                            ->addColumn('title', function ($item) {
                                $html  = !empty($item->title)?$item->title:null;
                                $html .= '<input type="hidden" class="item_checkbox" value="'. $item->id .'">';

                                $view = view('laws.books.search.table.accordion',compact('item'));
                                $html .= '<div class="box_details" style="display: none">'.($view).'</div>';
                                return  $html;
                            })
                            ->addColumn('date_publish', function ($item) {
                                return !empty($item->date_publish)?HP::DateThai($item->date_publish):null;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['title'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/search",  "name" => 'สืบค้นข้อมูลห้องสมุด' ],
            ];
            return view('laws.books.search.index',compact('breadcrumbs'));

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {
            $book_manage = LawBookManage::findOrFail($id);
            $this->SaveVisitView($book_manage->id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/search",  "name" => 'สืบค้นข้อมูลห้องสมุด' ],
                [ "link" => "/law/book/search/$id",  "name" => 'รายละเอียด' ],
            ];

            return view('laws.books.search.show',compact('book_manage','breadcrumbs'));

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    public function SaveVisitView($law_book_manage_id)
    {
        $sessionId = Session::getId();
        $requestData['section_id'] = $sessionId;
        $requestData['law_book_manage_id'] = $law_book_manage_id;
        $requestData['system_type'] = 2;
        $requestData['action'] = 1;
        $requestData['visit_at'] = date('Y-m-d H:i:s');
        LawBookManageVisit::create($requestData);
    }

    public function Details(Request $request)
    {

        $id = $request->input('id');

        $item = LawBookManage::findOrFail($id);

        return view('laws.books.search.table.accordion',compact('item'));
    }
}
