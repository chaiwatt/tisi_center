<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Config\SettingSystem;
use App\Models\Config\SettingSystemGroup;
use Illuminate\Http\Request;

class SsoUrlController extends Controller
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('view-'.$model)) {

            $groups = SettingSystemGroup::pluck('title', 'id');

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_group_id'] = $request->get('filter_group_id', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new SettingSystem;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_group_id']!='') {
                $Query = $Query->where('group_id', $filter['filter_group_id']);
            }

            $ssourl = $Query->sortable()->with('user_created')
                                        ->with('user_updated')
                                        ->with('group')
                                        ->paginate($filter['perPage']);

            return view('config/sso_url.index', compact('ssourl', 'filter', 'groups'));
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('config/sso_url.create');
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
    			'title'   => 'required',
    			'details' => 'required',
    			'urls'    => 'required',
    			'icons'   => 'required',
    			'colors'  => 'required'
    		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            SettingSystem::create($requestData);
            return redirect('config/sso-url')->with('flash_message', 'เพิ่ม URL เรียบร้อยแล้ว');
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('view-'.$model)) {
            $ssourl = SettingSystem::findOrFail($id);
            return view('config/sso_url.show', compact('ssourl'));
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('edit-'.$model)) {
            $ssourl = SettingSystem::findOrFail($id);
            return view('config/sso_url.edit', compact('ssourl'));
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
                                        'title'   => 'required',
                                        'details' => 'required',
                                        'urls'    => 'required',
                                        'icons'   => 'required',
                                        'colors'  => 'required'
                        		      ]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $request->request->add(['updated_at' => date('Y-m-d H:i:s')]); //user update
            $requestData = $request->all();

            $ssourl = SettingSystem::findOrFail($id);
            $ssourl->update($requestData);

            return redirect('config/sso-url')->with('flash_message', 'แก้ไข SsoUrl เรียบร้อยแล้ว!');
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
        $model = str_slug('ssourl','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new SettingSystem;
            SettingSystem::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            SettingSystem::destroy($id);
          }

          return redirect('config/sso-url')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('ssourl','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new SettingSystem;
          SettingSystem::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('config/sso-url')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
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
            SettingSystem::where('id', $id)->update(array('ordering' => $orders[$key]));
        }

        //อัพเดททั้งหมดอีกที
        $items = SettingSystem::orderby('ordering')->get();
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
