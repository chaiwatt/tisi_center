<?php

namespace App\Http\Controllers\Laws\Basic;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawSection;

class LawSectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function data_list(Request $request)
    {
        $filter_search        = $request->input('filter_search');
        $filter_status        = $request->input('filter_status');
        $filter_section_id    = $request->input('filter_section_id');
        $filter_section_type    = $request->input('filter_section_type');
        $filter_date_announce = !empty($request->input('filter_date_announce'))? HP::convertDate($request->input('filter_date_announce'),true):null;
        $filter_created_at    = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawSection::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(number,' ','')"),  'LIKE', "%$search_full%");
                                        $query->Orwhere(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                    });
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('state', $filter_status);
                                    }else{
                                        return $query->where('state', '<>', 1)->orWhereNull('state');
                                    }
                                })
                                ->when($filter_section_id, function ($query, $filter_section_id){
                                    return $query->where('id', $filter_section_id);
                                })
                                ->when($filter_section_type, function ($query, $filter_section_type){
                                    return $query->where('section_type', $filter_section_type);
                                })
                                ->when($filter_date_announce, function ($query, $filter_date_announce){
                                    return $query->whereDate('date_announce', $filter_date_announce);
                                })
                                ->when($filter_created_at, function ($query, $filter_created_at){
                                    return $query->whereDate('created_at', $filter_created_at);
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('number', function ($item) {
                                return !empty($item->number)?$item->number:null;
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('section_type', function ($item) {
                                return !empty($item->SectionTypeText)?$item->SectionTypeText:null;
                            })
                            ->addColumn('remark', function ($item) {
                                return !empty($item->remark)?$item->remark:null;
                            })
                            ->addColumn('date', function ($item) {
                             return  !empty($item->date_announce)?HP::DateThai($item->date_announce):null;
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
                                return HP::buttonActionLaw( $item->id, 'law/basic/section','Laws\Basic\\LawSectionController@destroy', 'law-sections');
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/section",  "name" => 'มาตราความผิด' ],
            ];
            return view('laws.basic.section.index',compact('breadcrumbs'));
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/section",  "name" => 'มาตราความผิด' ],
                [ "link" => "/law/basic/section/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.basic.section.create',compact('breadcrumbs'));
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();
            $requestData['created_by']     =  auth()->user()->getKey();
            $requestData['date_announce']  = !empty($request->date_announce) ?  HP::convertDate($request->date_announce,true) : null;
            $requestData['conditon_cert']  =  isset($request->conditon_cert)?1:0;
            $requestData['adjustment']     = !empty($requestData['adjustment'])?(str_replace(",", '', $requestData['adjustment'])):null;
            $requestData['adjustment_max'] = !empty($requestData['adjustment_max'])?(str_replace(",", '', $requestData['adjustment_max'])):null;

            LawSection::create($requestData);
            return redirect('law/basic/section')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('view-'.$model)) {
            $law_section = LawSection::findOrFail($id);
            $law_section->date_announce = !empty($law_section->date_announce)?HP::revertDate($law_section->date_announce,true):null;
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/section",  "name" => 'มาตราความผิด' ],
                [ "link" => "/law/basic/section/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('laws.basic.section.show', compact('law_section','breadcrumbs'));
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('edit-'.$model)) {
            $law_section = LawSection::findOrFail($id);
            $law_section->date_announce = !empty($law_section->date_announce)?HP::revertDate($law_section->date_announce,true):null;
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/basic/section",  "name" => 'มาตราความผิด' ],
                [ "link" => "/law/basic/section/$id/edit",  "name" => 'แก้ไข' ],

            ];
            return view('laws.basic.section.edit', compact('law_section','breadcrumbs'));
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('edit-'.$model)) {
            $law_section = LawSection::findOrFail($id);

            $requestData = $request->all();
            $requestData['created_by']     =  auth()->user()->getKey();
            $requestData['date_announce']  =  !empty($request->date_announce) ?  HP::convertDate($request->date_announce,true) : null;
            $requestData['conditon_cert']  =  isset($request->conditon_cert)?1:0;
            $requestData['adjustment']     = !empty($requestData['adjustment'])?(str_replace(",", '', $requestData['adjustment'])):null;
            $requestData['adjustment_max'] = !empty($requestData['adjustment_max'])?(str_replace(",", '', $requestData['adjustment_max'])):null;

            $law_section->update($requestData);

            return redirect('law/basic/section')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-sections','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawSection::destroy($id);
            return redirect('law/basic/section')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-sections','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawSection;
            $resulte =  LawSection::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawSection::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

}
