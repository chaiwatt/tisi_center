<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\CalibrationItem as calibration_item;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CalibrationGroup;
use Illuminate\Http\Request;

class CalibrationItemController extends Controller
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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_formula'] = $request->get('filter_formula', '');
            $filter['filter_calibration_group'] = $request->get('filter_calibration_group', '');
            $filter['filter_calibration_branch'] = $request->get('filter_calibration_branch', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new calibration_item;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_formula']!='') {
                $Query = $Query->where('formula_id', $filter['filter_formula']);
            }

            if ($filter['filter_calibration_group']!='') {
                $Query = $Query->where('calibration_group_id', $filter['filter_calibration_group']);
            }

            if ($filter['filter_calibration_branch']!='') {
                $Query = $Query->where('calibration_branch_id', $filter['filter_calibration_branch']);
            }

            $calibration_item = $Query->sortable()->with('user_created')
                                                  ->with('user_updated')
                                                  ->with('formula')
                                                  ->with('calibration_branch')
                                                  ->with('calibration_group')
                                                  ->paginate($filter['perPage']);

            return view('bcertify.calibration_item.index', compact('calibration_item', 'filter'));
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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('add-'.$model)) {

            $calibration_branchs = [];
            $calibration_groups = [];

            return view('bcertify.calibration_item.create', compact('calibration_branchs', 'calibration_groups'));

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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
        			'title' => 'required',
        			'formula_id' => 'required',
        			'calibration_branch_id' => 'required',
        			'calibration_group_id' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            calibration_item::create($requestData);
            return redirect('bcertify/calibration_item')->with('flash_message', 'เพิ่ม calibration_item เรียบร้อยแล้ว');
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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('view-'.$model)) {
            $calibration_item = calibration_item::findOrFail($id);
            return view('bcertify.calibration_item.show', compact('calibration_item'));
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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('edit-'.$model)) {

            $calibration_item = calibration_item::findOrFail($id);

            $calibration_branchs = CalibrationBranch::where('formula_id', $calibration_item->formula_id)->pluck('title', 'id');

            $calibration_groups = CalibrationGroup::where('formula_id', $calibration_item->formula_id)->where('calibration_branch_id', $calibration_item->calibration_branch_id)->pluck('title', 'id');

            return view('bcertify.calibration_item.edit', compact('calibration_item', 'calibration_branchs', 'calibration_groups'));

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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'formula_id' => 'required',
			'calibration_branch_id' => 'required',
			'calibration_group_id' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $calibration_item = calibration_item::findOrFail($id);
            $calibration_item->update($requestData);

            return redirect('bcertify/calibration_item')->with('flash_message', 'แก้ไข calibration_item เรียบร้อยแล้ว!');
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
        $model = str_slug('calibration_item','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new calibration_item;
            calibration_item::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            calibration_item::destroy($id);
          }

          return redirect('bcertify/calibration_item')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('calibration_item','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new calibration_item;
          calibration_item::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/calibration_item')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
