<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Config\SettingSystemGroup;
use Illuminate\Http\Request;

class SsoUrlGroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new SettingSystemGroup;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $ssourl = $Query->sortable()->with('user_created')
                                        ->with('user_updated')
                                        ->paginate($filter['perPage']);

            return view('config/sso_url_group.index', compact('ssourl', 'filter'));
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
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('config/sso_url_group.create');
        }
        abort(403);

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
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
    			'title'   => 'required'
    		]);

            $max_order = SettingSystemGroup::orderby('ordering', 'desc')->value('ordering');

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['ordering'] = !is_null($max_order) ? $max_order+1 : 1 ;

            SettingSystemGroup::create($requestData);
            return redirect('config/sso-url-group')->with('flash_message', 'เพิ่ม กลุ่ม เรียบร้อยแล้ว');
        }
        abort(403);
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
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('view-'.$model)) {
            $ssourl = SettingSystemGroup::findOrFail($id);
            return view('config/sso_url_group.show', compact('ssourl'));
        }
        abort(403);
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
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('edit-'.$model)) {
            $ssourl = SettingSystemGroup::findOrFail($id);
            return view('config/sso_url_group.edit', compact('ssourl'));
        }
        abort(403);
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
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
                                        'title'   => 'required'
                        		      ]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $request->request->add(['updated_at' => date('Y-m-d H:i:s')]); //user update
            $requestData = $request->all();

            $ssourl = SettingSystemGroup::findOrFail($id);
            $ssourl->update($requestData);

            return redirect('config/sso-url-group')->with('flash_message', 'แก้ไข กลุ่ม เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('ssourlgroup','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new SettingSystemGroup;
            SettingSystemGroup::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            SettingSystemGroup::destroy($id);
          }

          return redirect('config/sso-url-group')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /*
        อัพเดทลำดับฟิลด์ ordering
    */
    public function update_order(Request $request){

        $ids = $request->get('ids');
        $orders = $request->get('orders');
        $direction = $request->get('direction');//desc|asc

        $direction=='desc' ? rsort($orders) : sort($orders) ;

        foreach ($ids as $key => $id) {
            SettingSystemGroup::where('id', $id)->update(array('ordering' => $orders[$key]));
        }

        //อัพเดททั้งหมดอีกที
        $items = SettingSystemGroup::orderby('ordering')->get();
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
