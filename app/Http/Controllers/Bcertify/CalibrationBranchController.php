<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;
use App\Models\Bcertify\CalibrationBranch as calibration_branch;

class CalibrationBranchController extends Controller
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_formula'] = $request->get('filter_formula', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new calibration_branch;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_formula']!='') {
                $Query = $Query->where('formula_id', $filter['filter_formula']);
            }

            $calibration_branch = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->with('formula')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.calibration_branch.index', compact('calibration_branch', 'filter'));
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.calibration_branch.create');
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required',
        			'formula_id' => 'required'
        		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            calibration_branch::create($requestData);
            return redirect('bcertify/calibration_branch')->with('flash_message', 'เพิ่ม calibration_branch เรียบร้อยแล้ว');
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('view-'.$model)) {
            $calibration_branch = calibration_branch::findOrFail($id);
            return view('bcertify.calibration_branch.show', compact('calibration_branch'));
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $calibration_branch = calibration_branch::findOrFail($id);
            return view('bcertify.calibration_branch.edit', compact('calibration_branch'));
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'title_en' => 'required',
			'formula_id' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $calibration_branch = calibration_branch::findOrFail($id);
            $calibration_branch->update($requestData);

            return redirect('bcertify/calibration_branch')->with('flash_message', 'แก้ไข calibration_branch เรียบร้อยแล้ว!');
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
        $model = str_slug('calibration_branch','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new calibration_branch;
            calibration_branch::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            calibration_branch::destroy($id);
          }

          return redirect('bcertify/calibration_branch')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('calibration_branch','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new calibration_branch;
          calibration_branch::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/calibration_branch')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function calibrationBranchInstrumentGroups()
    {
        return $this->hasMany(CalibrationBranchInstrumentGroup::class, 'bcertify_calibration_branche_id', 'id');
    }

}
