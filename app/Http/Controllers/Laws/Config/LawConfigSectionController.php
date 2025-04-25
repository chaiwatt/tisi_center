<?php

namespace App\Http\Controllers\Laws\Config;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Basic\LawSection;
use  App\Models\Law\Config\LawConfigSection;

class LawConfigSectionController extends Controller
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

        $query = LawConfigSection::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->whereHas('basic_section', function ($query) use ($search_full) {
                                            $query->where(DB::Raw("REPLACE(number,' ','')"),  'LIKE', "%$search_full%");
                                        });
                                        $check1 = mb_strpos('เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม(สมอ)', $search_full);
                                        $check2 = mb_strpos('คณะกรรมการเปรียบเทียบ', $search_full);
                                        if($check1 || $check1 === 0){
                                            $query->Orwhere('power', 1);
                                        }else if($check2 || $check2 === 0){
                                            $query->Orwhere('power', 2);
                                        }
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
                            ->addColumn('number', function ($item) {
                                return !empty($item->SectionNumber)?$item->SectionNumber:null;
                            })
                            ->addColumn('pover', function ($item) {
                                return !empty($item->PowerTitle)?$item->PowerTitle:null;
                            })
                            ->addColumn('section_relation', function ($item) {
                                return !empty($item->SectionRelationNumber)?$item->SectionRelationNumber:null;
                            })
                            ->addColumn('date', function ($item) {
                                $date = '';
                                $date .= !empty($item->start_date)?HP::DateThai($item->start_date):null;
                                $date .= !empty($item->end_date)?'-'.HP::DateThai($item->end_date):null;
                             return  $date;
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
                                return HP::buttonActionLaw( $item->id, 'law/config/sections','Laws\Config\\LawConfigSectionController@destroy', 'law-config-sections');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','section_relation', 'created_at','date'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/sections",  "name" => 'กำหนดอัตราโทษตามมาตราความผิด' ],
            ];
            return view('laws.config.section.index',compact('breadcrumbs'));
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/sections",  "name" => 'กำหนดอัตราโทษตามมาตราความผิด' ],
                [ "link" => "/law/config/sections/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.config.section.create',compact('breadcrumbs'));
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            if( !empty( $request->input('section_relation') ) && count( $request->input('section_relation')) > 0 ){
                $requestData['section_relation'] = json_encode($request->input('section_relation'));
            }

            LawConfigSection::create($requestData);
            return redirect('law/config/sections')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('view-'.$model)) {
            $config_section = LawConfigSection::findOrFail($id);
            $config_section->section_relation = !empty($config_section->section_relation)?json_decode($config_section->section_relation):null;
            $config_section->section_title = !empty($config_section->SectionTitle)?$config_section->SectionTitle:null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/sections",  "name" => 'กำหนดอัตราโทษตามมาตราความผิด' ],
                [ "link" => "/law/config/sections/$id",  "name" => 'รายละเอียด' ],
            ];

            return view('laws.config.section.show', compact('config_section','breadcrumbs'));
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('edit-'.$model)) {
            $config_section = LawConfigSection::findOrFail($id);
            $config_section->section_relation = !empty($config_section->section_relation)?json_decode($config_section->section_relation):null;
            $config_section->section_title = !empty($config_section->SectionTitle)?$config_section->SectionTitle:null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/sections",  "name" => 'กำหนดอัตราโทษตามมาตราความผิด' ],
                [ "link" => "/law/config/sections/$id/edit",  "name" => 'แก้ไข' ],

            ];
            
            return view('laws.config.section.edit', compact('config_section','breadcrumbs'));
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            if( !empty( $request->input('section_relation') ) && count( $request->input('section_relation')) > 0 ){
                $requestData['section_relation'] = json_encode($request->input('section_relation'));
            }
           
            $config_section = LawConfigSection::findOrFail($id);
            $config_section->update($requestData);

            return redirect('law/config/sections')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
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
        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawConfigSection::destroy($id);
            return redirect('law/config/sections')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-config-sections','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawConfigSection;
            $resulte =  LawConfigSection::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawConfigSection::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function basic_section($id) {
        $basic_section =  LawSection::where('id',$id)->first();
        if(!is_null($basic_section)){
                return response()->json([
                    'title'=> !empty($basic_section->title) ? $basic_section->title : ' ' ,
                 ]);
        }
   
    }

    public function section_relation(Request $request){
        $requestData   =  $request->all();
        $section_id    =  $requestData['section_id'];
        $section_title =  LawSection::whereIn('id',$section_id)->select('number','title')->get();

    return response()->json($section_title);
    }
}
