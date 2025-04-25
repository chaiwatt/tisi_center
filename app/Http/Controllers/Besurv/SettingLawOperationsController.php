<?php

namespace App\Http\Controllers\Besurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Besurv\SettingLawOperation;
use Illuminate\Http\Request;

class SettingLawOperationsController extends Controller
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new SettingLawOperation;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $masterlawoperations = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('besurv.setting-law-operations.index', compact('masterlawoperations', 'filter'));
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('besurv.setting-law-operations.create');
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			'title' => 'required'
		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            SettingLawOperation::create($requestData);
            return redirect('setting-law-operations')->with('flash_message', 'เพิ่ม MasterLawOperation เรียบร้อยแล้ว');
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('view-'.$model)) {
            $masterlawoperation = SettingLawOperation::findOrFail($id);
            return view('besurv.setting-law-operations.show', compact('masterlawoperation'));
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('edit-'.$model)) {
            $masterlawoperation = SettingLawOperation::findOrFail($id);
            return view('besurv.setting-law-operations.edit', compact('masterlawoperation'));
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $masterlawoperation = SettingLawOperation::findOrFail($id);
            $masterlawoperation->update($requestData);

            return redirect('setting-law-operations')->with('flash_message', 'แก้ไข MasterLawOperation เรียบร้อยแล้ว!');
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
        $model = str_slug('masterlawoperations','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new SettingLawOperation;
            SettingLawOperation::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            SettingLawOperation::destroy($id);
          }

          return redirect('setting-law-operations')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('masterlawoperations','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new SettingLawOperation;
          SettingLawOperation::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('setting-law-operations')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
