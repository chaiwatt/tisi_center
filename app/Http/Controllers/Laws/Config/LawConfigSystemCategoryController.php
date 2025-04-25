<?php


namespace App\Http\Controllers\Laws\Config;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Law\Log\LawSystemCategory;

class LawConfigSystemCategoryController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission = str_slug('law-config-system-category','-');

    }

    public function data_list(Request $request)
    {
        $filter_search     = $request->input('filter_search');
        $filter_status     = $request->input('filter_status');
        $filter_created_at = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawSystemCategory::query()
                                    ->when($filter_search, function ($query, $filter_search){
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
                                return $item->name;
                            })
                            ->addColumn('color', function ($item) {
                                return $item->ColorHtml;
                            })
                            ->addColumn('status', function ($item) {
                                return  @$item->StateIcon;
                            })
                            ->addColumn('notify', function ($item) {
                                return  @$item->NotifyIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';

                                if( !empty($item->created_by) ){
                                    $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                    $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                }else{
                                    $created_by .= !empty($item->UpdatedName)?$item->UpdatedName:'-';
                                    $created_by .= !empty($item->updated_at)?' <br> '.'('.HP::DateThai($item->updated_at).')':null;
                                }
            
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                $delete = count( $item->law_notify ) >=1?false:true;
                                return HP::buttonActionLaw( $item->id, 'law/config/system-category','Laws\Config\\LawConfigSystemCategoryController@destroy', 'law-config-system-category',true,true, $delete);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by', 'color', 'condition', 'created_at','notify'])
                            ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/system-category",  "name" => 'หมวดหมู่ระบบงานหลัก' ],
            ];
            return view('laws.config.system-category.index',compact('breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/system-category",  "name" => 'หมวดหมู่ระบบงานหลัก' ],
                [ "link" => "/law/config/system-category/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.config.system-category.create',compact('breadcrumbs'));

        }
        return response(view('403'), 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            LawSystemCategory::create($requestData);
            return redirect('law/config/system-category')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/system-category",  "name" => 'หมวดหมู่ระบบงานหลัก' ],
                [ "link" => "/law/config/system-category/$id",  "name" => 'รายละเอียด' ],
            ];

            $config_system_category = LawSystemCategory::findOrFail($id);
            return view('laws.config.system-category.show',compact('breadcrumbs','config_system_category'));

        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/system-category",  "name" => 'หมวดหมู่ระบบงานหลัก' ],
                [ "link" => "/law/config/system-category/$id/edit",  "name" => 'แก้ไข' ],

            ];

            $config_system_category = LawSystemCategory::findOrFail($id);
            return view('laws.config.system-category.edit',compact('breadcrumbs','config_system_category'));

        }
        return response(view('403'), 403);
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
        if(auth()->user()->can('add-'.$this->permission)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $config_sytems = LawSystemCategory::findOrFail($id);
            $config_sytems->update($requestData);

            return redirect('law/config/system-category')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');

        }
        return response(view('403'), 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->can('add-'.$this->permission)) {

            LawSystemCategory::destroy($id);
            return redirect('law/config/reward')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);
    }

        /*
      **** Update State ****
    */
    public function update_state(Request $request){

        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawSystemCategory;
            $resulte = LawSystemCategory::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawSystemCategory::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    
    public function update_notify(Request $request){

        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawSystemCategory;
            $resulte = LawSystemCategory::whereIn($db->getKeyName(), $id_publish)->update(['state_notify' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

}
