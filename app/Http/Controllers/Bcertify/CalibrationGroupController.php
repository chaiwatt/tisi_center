<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\CalibrationGroup as calibration_group;
use App\Models\Bcertify\CalibrationBranch;
use Illuminate\Http\Request;

class CalibrationGroupController extends Controller
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_formula'] = $request->get('filter_formula', '');
            $filter['filter_calibration_branch'] = $request->get('filter_calibration_branch', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new calibration_group;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_formula']!='') {
                $Query = $Query->where('formula_id', $filter['filter_formula']);
            }

            if ($filter['filter_calibration_branch']!='') {
                $Query = $Query->where('calibration_branch_id', $filter['filter_calibration_branch']);
            }

            $calibration_group = $Query->sortable()->with('user_created')
                                                   ->with('user_updated')
                                                   ->with('formula')
                                                   ->with('calibration_branch')
                                                   ->paginate($filter['perPage']);

            return view('bcertify.calibration_group.index', compact('calibration_group', 'filter'));
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('add-'.$model)) {

            $calibration_brachs = [];

            return view('bcertify.calibration_group.create', compact('calibration_brachs'));

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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'formula_id' => 'required',
        			'calibration_branch_id' => 'required'
        		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            calibration_group::create($requestData);
            return redirect('bcertify/calibration_group')->with('flash_message', 'เพิ่ม calibration_group เรียบร้อยแล้ว');
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('view-'.$model)) {
            $calibration_group = calibration_group::findOrFail($id);
            return view('bcertify.calibration_group.show', compact('calibration_group'));
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('edit-'.$model)) {

            $calibration_group = calibration_group::findOrFail($id);

            $calibration_brachs = CalibrationBranch::where('formula_id', $calibration_group->formula_id)->pluck('title', 'id');

            return view('bcertify.calibration_group.edit', compact('calibration_group', 'calibration_brachs'));
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'formula_id' => 'required',
			'calibration_branch_id' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $calibration_group = calibration_group::findOrFail($id);
            $calibration_group->update($requestData);

            return redirect('bcertify/calibration_group')->with('flash_message', 'แก้ไข calibration_group เรียบร้อยแล้ว!');
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
        $model = str_slug('calibration_group','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new calibration_group;
            calibration_group::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            calibration_group::destroy($id);
          }

          return redirect('bcertify/calibration_group')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('calibration_group','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new calibration_group;
          calibration_group::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/calibration_group')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
