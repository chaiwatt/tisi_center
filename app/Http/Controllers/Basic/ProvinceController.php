<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Province as province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
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
        $model = str_slug('province','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = $request->all();
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new province;

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                                    $query_search->where('PROVINCE_CODE', 'LIKE', '%'.$filter['search'].'%')
                                                 ->orWhere('PROVINCE_NAME', 'LIKE', '%'.$filter['search'].'%');
                                });
            }

            if (array_key_exists('filter_state', $filter) && $filter['filter_state']!='') {
                if($filter['filter_state'] == '1'){
                    $Query = $Query->whereNull('state');
                }else{
                    $Query = $Query->where('state', $filter['filter_state']);
                }
        
            }

            if (array_key_exists('filter_geo', $filter) && $filter['filter_geo']!='') {
                $Query = $Query->where('GEO_ID', $filter['filter_geo']);
            }

            $province = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.province.index', compact('province', 'filter'));
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
        $model = str_slug('province','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.province.create');
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
        $model = str_slug('province','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            province::create($requestData);
            return redirect('basic/province')->with('flash_message', 'เพิ่ม province เรียบร้อยแล้ว');
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
        $model = str_slug('province','-');
        if(auth()->user()->can('view-'.$model)) {
            $province = province::findOrFail($id);
            return view('basic.province.show', compact('province'));
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
        $model = str_slug('province','-');
        if(auth()->user()->can('edit-'.$model)) {
            $province = province::findOrFail($id);
            return view('basic.province.edit', compact('province'));
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
        $model = str_slug('province','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            $province = province::findOrFail($id);
            $province->update($requestData);

            return redirect('basic/province')->with('flash_message', 'แก้ไข province เรียบร้อยแล้ว!');
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
        $model = str_slug('province','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new province;
            province::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            province::destroy($id);
          }

          return redirect('basic/province')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('province','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new province;
          province::whereIn($db->getKeyName(), $ids)->update(['state' => ($requestData['state'] == '1' ? null : '0' )]);
        }

        return redirect('basic/province')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
