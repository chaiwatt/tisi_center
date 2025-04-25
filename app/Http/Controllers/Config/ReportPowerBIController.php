<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Config\ConfigsReportPowerBI;
use App\Models\Config\ConfigsReportPowerBIRole;
use App\Models\Config\ConfigsReportPowerBIGroup;
use Illuminate\Http\Request;

class ReportPowerBIController extends Controller
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

        $model = str_slug('configs-report-power-bi', '-');
        if(auth()->user()->can('view-'.$model)) {

            $groups = ConfigsReportPowerBIGroup::pluck('title', 'id');

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_group_id'] = $request->get('filter_group_id', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new ConfigsReportPowerBI;

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

            return view('config/report_power_bi.index', compact('ssourl', 'filter', 'groups'));
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
        $model = str_slug('configs-report-power-bi', '-');
        if(auth()->user()->can('add-'.$model)) {
            return view('config/report_power_bi.create');
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
    			'title'    => 'required',
    			'group_id' => 'required',
    			'url'      => 'required'
    		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $ssourl = ConfigsReportPowerBI::create($requestData);

            //อัพเดทสิทธิ์
            if(isset($requestData['roles'])){
                $roles = [];
                foreach ((array)$requestData['roles'] as $role_id) {
                    $roles[] = ['role_id' => $role_id, 'power_bi_id' => $ssourl->id];
                }
                ConfigsReportPowerBIRole::insert($roles);
            }

            return redirect('config/report-power-bi')->with('flash_message', 'เพิ่มรายงานเรียบร้อยแล้ว');
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
            $item = ConfigsReportPowerBI::findOrFail($id);
            return view('config/report_power_bi.show', compact('item'));
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
            $ssourl = ConfigsReportPowerBI::findOrFail($id);
            return view('config/report_power_bi.edit', compact('ssourl'));
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
                                        'title'    => 'required',
                                        'group_id' => 'required',
                                        'url'      => 'required'
                        		      ]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $request->request->add(['updated_at' => date('Y-m-d H:i:s')]); //user update
            $requestData = $request->all();
            $requestData['role_all'] = array_key_exists('role_all', $requestData) ? $requestData['role_all'] : '0' ; //1=ดูได้ทุกกลุ่ม, 0=ไม่ใช่ (ดูตาม configs_report_power_bi_role)

            $ssourl = ConfigsReportPowerBI::findOrFail($id);
            $ssourl->update($requestData);

            //อัพเดทสิทธิ์
            ConfigsReportPowerBIRole::where('power_bi_id', $ssourl->id)->delete();//ลบออกก่อน
            if(isset($requestData['roles'])){
                $roles = [];
                foreach ((array)$requestData['roles'] as $role_id) {
                    $roles[] = ['role_id' => $role_id, 'power_bi_id' => $ssourl->id];
                }
                ConfigsReportPowerBIRole::insert($roles);
            }

            return redirect('config/report-power-bi')->with('flash_message', 'แก้ไขรายงานเรียบร้อยแล้ว!');
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
            $db = new ConfigsReportPowerBI;
            ConfigsReportPowerBI::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ConfigsReportPowerBI::destroy($id);
          }

          return redirect('config/report-power-bi')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
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
          $db = new ConfigsReportPowerBI;
          ConfigsReportPowerBI::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('config/report-power-bi')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
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
            ConfigsReportPowerBI::where('id', $id)->update(array('ordering' => $orders[$key]));
        }

        //อัพเดททั้งหมดอีกที
        $items = ConfigsReportPowerBI::orderby('ordering')->get();
        foreach ($items as $key => $item) {
            $item->ordering = $key+1;
            $item->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'บันทึกลำดับสำเร็จแล้ว'
        ]);
    }

    //Preview Report URL
    public function preview_url($url_base64 = null){
        if(!is_null($url_base64)){
            $url = base64_decode($url_base64);
            return view('config/report_power_bi.preview', compact('url'));
        }else{
            abort(400);
        }
    }

}
