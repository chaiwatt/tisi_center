<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\District as district;
use App\Models\Basic\Amphur;
use Illuminate\Http\Request;

class DistrictController extends Controller
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
        $model = str_slug('district','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = $request->all();
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new district;

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                                    $query_search->where('DISTRICT_CODE', 'LIKE', '%'.$filter['search'].'%')
                                                 ->orWhere('DISTRICT_NAME', 'LIKE', '%'.$filter['search'].'%');
                                });
            }

            if (array_key_exists('filter_state', $filter) && $filter['filter_state']!='') {
                if($filter['filter_state'] == '1'){
                    $Query = $Query->whereNull('state');
                }else{
                    $Query = $Query->where('state', $filter['filter_state']);
                }
            }

            if (array_key_exists('filter_province', $filter) && $filter['filter_province']!='') {
                $Query = $Query->where('PROVINCE_ID', $filter['filter_province']);
            }

            if (array_key_exists('filter_amphur', $filter) && $filter['filter_amphur']!='') {
                $Query = $Query->where('AMPHUR_ID', $filter['filter_amphur']);
            }

            $district = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.district.index', compact('district', 'filter'));
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
        $model = str_slug('district','-');
        if(auth()->user()->can('add-'.$model)) {

            $amphurs = [];

            return view('basic.district.create', compact('amphurs'));

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
        $model = str_slug('district','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            district::create($requestData);
            return redirect('basic/district')->with('flash_message', 'เพิ่ม district เรียบร้อยแล้ว');
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
        $model = str_slug('district','-');
        if(auth()->user()->can('view-'.$model)) {
            $district = district::findOrFail($id);
            return view('basic.district.show', compact('district'));
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
        $model = str_slug('district','-');
        if(auth()->user()->can('edit-'.$model)) {

            $district = district::findOrFail($id);

            $amphurs = Amphur::where('PROVINCE_ID', $district->PROVINCE_ID)->pluck('AMPHUR_NAME', 'AMPHUR_ID');

            return view('basic.district.edit', compact('district', 'amphurs'));
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
        $model = str_slug('district','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            $district = district::findOrFail($id);
            $district->update($requestData);

            return redirect('basic/district')->with('flash_message', 'แก้ไข district เรียบร้อยแล้ว!');
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
        $model = str_slug('district','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new district;
            district::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            district::destroy($id);
          }

          return redirect('basic/district')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('district','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new district;
          district::whereIn($db->getKeyName(), $ids)->update(['state' => ($requestData['state'] == '1' ? null : '0' )]);
        }

        return redirect('basic/district')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
