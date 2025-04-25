<?php

namespace App\Http\Controllers\Roles;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\RoleSettingGroup;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

class RoleSettingGroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    function data_list(Request $request)
    {
        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        $filter_role   = $request->input('filter_role');


        $query = RoleSettingGroup::query()->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search );

                                                $query->where(function ($query2) use($search_full) {
                                                            $query2->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(details,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(urls,' ','')"), 'LIKE', "%".$search_full."%");

                                                        });
                                            })
                                            ->when($filter_status, function ($query, $filter_status){
                                                if( $filter_status == 1){
                                                    return $query->where('state', $filter_status);
                                                }else{
                                                    return $query->where('state', '<>', 1)->orWhereNull('state');
                                                }
                                            })
                                            ->when($filter_role, function ($query, $filter_role){
                                                $query->whereHas('role', function($query) use ($filter_role){
                                                            $query->where('id', $filter_role);
                                                        });
                                            });


        return Datatables::of($query)
                            ->addIndexColumn()   
                            ->addColumn('title', function ($item) {
                                $html = '<input type="hidden" name="item_checkbox[]" class="item_checkbox" value="'.($item->id).'">';
                                $html .= '<input type="hidden" name="order[]" class="order" value="'.($item->ordering).'">';

                                return @$item->title.( $html );
                            })
                            ->addColumn('details', function ($item) {
                                return @$item->description;
                            })
                            ->addColumn('urls', function ($item) {
                                return @$item->urls;
                            })
                            ->addColumn('state', function ($item) {
                                return @$item->StateIcon;
                            })
                            ->addColumn('icons', function ($item) {
                                return !empty($item->icons)?'<i class="pre-icon mdi '.$item->icons.'"></i>':null;
                            })
                            ->addColumn('colors', function ($item) {
                                return !empty($item->colors)?'<div class="input-group"><ul class="icolors"><li class="'.$item->colors.'"></li></ul></div>':null;
                            })
                            ->addColumn('action', function ($item) {

                                $delete = $item->role->count() >= 1?false:true;
                                return $this->buttonAction( $item->id, 'role-setting-group','Roles\\RoleSettingGroupController@destroy', 'role-setting-group',true,true, $delete );
                            })
                            ->rawColumns(['title', 'state', 'urls', 'action', 'icons', 'colors'])
                            ->order(function ($query) {
                                $query->orderBy('ordering');
                            })
                            ->make(true);  

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('roles.role-setting-group.inedx');

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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('roles.role-setting-group.create');
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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('add-'.$model)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $role_setting_group = RoleSettingGroup::create($requestData);

            if( empty(  $role_setting_group->ordering) ){
                $ordering = RoleSettingGroup::orderby('ordering')->max('ordering');
                $requestData['ordering'] = $ordering + 1;
                $role_setting_group->update($requestData);
            }
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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('view-'.$model)) {
            $role_setting_group = RoleSettingGroup::findOrFail($id);

            return view('roles.role-setting-group.show',compact('role_setting_group'));

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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('edit-'.$model)) {

            $role_setting_group = RoleSettingGroup::findOrFail($id);

            return view('roles.role-setting-group.edit',compact('role_setting_group'));

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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $role_setting_group = RoleSettingGroup::findOrFail($id);

            if( empty(  $role_setting_group->ordering) ){
                $ordering = RoleSettingGroup::orderby('ordering')->max('ordering');
                $requestData['ordering'] = $ordering + 1;
            }

            $role_setting_group->update($requestData);
            return redirect('role-setting-group')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
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
        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('delete-'.$model)) {
            RoleSettingGroup::destroy($id);
            return redirect('role-setting-group')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('role-setting-group','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new RoleSettingGroup;
            $resulte =  RoleSettingGroup::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = RoleSettingGroup::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function buttonAction($id, $action_url, $controller_action, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true)
    {
        $form_action = '';

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= '<a href="' . url('/' . $action_url . '/' . $id) . '" title="View ' . substr($str_slug_name, 0, -1) . '"  class="btn btn-info btn-xs m-l-5" ><i class="fa fa-eye"></i></a>';
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            $form_action .= '<a href="' . url('/' . $action_url . '/' . $id . '/edit') . '"title="Edit ' . substr($str_slug_name, 0, -1) . '" class="btn btn-warning btn-xs m-l-5"><i class="fa fa-pencil-square-o"></i></a>';
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-' . str_slug($str_slug_name)) && $show_delete === true):
            $form_action .=  '<a href="'.url('/' . $action_url . '/' . $id . '/destroy').'"  title="Delete"  onclick="return confirm_delete()" class="btn btn-danger btn-xs m-l-5"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        endif;

        return $form_action;
    }
        /*
        อัพเดทลำดับฟิลด์ ordering
    */
    public function update_order(Request $request){

        $ids = $request->get('ids');
        $orders = $request->get('orders');

        foreach ($ids as $key => $id) {
            RoleSettingGroup::where('id', $id)->update(array('ordering' => $orders[$key]));
        }

        //อัพเดททั้งหมดอีกที
        $items = RoleSettingGroup::orderby('ordering')->get();
        foreach ($items as $key => $item) {
            $item->ordering = $key+1;
            $item->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'บันทึกลำดับสำเร็จแล้ว'
        ]);
    }
}
