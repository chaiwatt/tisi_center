<?php

namespace App\Http\Controllers\Besurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Tis as tis_unit;
use Illuminate\Http\Request;

class TisUnitController extends Controller
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_tis'] = $request->get('filter_tis', '');
            $filter['filter_unit_code'] = $request->get('filter_unit_code', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new tis_unit;

            if ($filter['filter_state']!='') {
              if($filter['filter_state']=='1'){
                $Query = $Query->whereNotNull('unitcode_id');
              }elseif($filter['filter_state']=='0'){
                $Query = $Query->whereNull('unitcode_id');
              }
            }

            if ($filter['filter_tis']!='') {
                $Query = $Query->where('tb3_Tisno', 'LIKE', '%'.$filter['filter_tis'].'%')->orWhere('tb3_TisThainame', 'LIKE', '%'.$filter['filter_tis'].'%');
            }

            if ($filter['filter_unit_code']!='') {
                $Query = $Query->where('id_unit', $filter['filter_unit_code']);
            }

            $tis_unit = $Query->sortable()->with('user_updated')
                                          ->paginate($filter['perPage']);

            return view('besurv.tis_unit.index', compact('tis_unit', 'filter'));
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('besurv.tis_unit.create');
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            tis_unit::create($requestData);
            return redirect('besurv/tis_unit')->with('flash_message', 'เพิ่ม tis_unit เรียบร้อยแล้ว');
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('view-'.$model)) {
            $tis_unit = tis_unit::findOrFail($id);
            return view('besurv.tis_unit.show', compact('tis_unit'));
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $tis_unit = tis_unit::findOrFail($id);
            return view('besurv.tis_unit.edit', compact('tis_unit'));
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $tis_unit = tis_unit::findOrFail($id);
            $tis_unit->update($requestData);

            return redirect('besurv/tis_unit')->with('flash_message', 'แก้ไข tis_unit เรียบร้อยแล้ว!');
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
        $model = str_slug('tis_unit','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new tis_unit;
            tis_unit::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            tis_unit::destroy($id);
          }

          return redirect('besurv/tis_unit')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('tis_unit','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new tis_unit;
          tis_unit::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('besurv/tis_unit')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
