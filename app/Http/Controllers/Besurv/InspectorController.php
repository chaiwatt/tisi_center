<?php

namespace App\Http\Controllers\Besurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Besurv\Inspector as inspector;
use App\Models\Besurv\InspectorInspectorType;
use Illuminate\Http\Request;

class InspectorController extends Controller
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_inspector_type'] = $request->get('filter_inspector_type', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new inspector;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_inspector_type']!='') {
                $inspector_ids = InspectorInspectorType::where('inspector_type_id', $filter['filter_inspector_type'])->pluck('inspector_id');
                $Query = $Query->whereIn('id', $inspector_ids);
            }

            if ($filter['filter_search']!='') {
                $Query = $Query->where('title', 'LIKE', '%'.$filter['filter_search'].'%');
            }

            $inspector = $Query->sortable()->with('user_created')
                                           ->with('user_updated')
                                           ->paginate($filter['perPage']);

            return view('besurv.inspector.index', compact('inspector', 'filter'));
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('besurv.inspector.create');
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			        'title' => 'required'
		        ]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $inspector = inspector::create($requestData);

            $this->SaveInspectorType($inspector, $requestData);//บันทึกข้อมูลประเภทผู้ตรวจ

            return redirect('besurv/inspector')->with('flash_message', 'เพิ่ม inspector เรียบร้อยแล้ว');
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('view-'.$model)) {
            $inspector = inspector::findOrFail($id);
            return view('besurv.inspector.show', compact('inspector'));
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('edit-'.$model)) {

            $inspector = inspector::findOrFail($id);

            $inspector->inspector_type_id = $inspector->inspector_type_list->pluck('inspector_type_id', 'inspector_type_id');

            return view('besurv.inspector.edit', compact('inspector'));
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required'
        		]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $inspector = inspector::findOrFail($id);
            $inspector->update($requestData);

            $this->SaveInspectorType($inspector, $requestData);//บันทึกข้อมูลประเภทผู้ตรวจ

            return redirect('besurv/inspector')->with('flash_message', 'แก้ไข inspector เรียบร้อยแล้ว!');
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
        $model = str_slug('inspector','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new inspector;
            inspector::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            inspector::destroy($id);
          }

          return redirect('besurv/inspector')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('inspector','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new inspector;
          inspector::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('besurv/inspector')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save Inspector Type
    */
    private function SaveInspectorType($inspector, $requestData){

        InspectorInspectorType::where('inspector_id', $inspector->id)->delete();

        /* บันทึกข้อมูลประเภทหน่วยงาน */
        foreach ((array)@$requestData['inspector_type_id'] as $inspector_type_id) {
          $input = [];
          $input['inspector_type_id'] = $inspector_type_id;
          $input['inspector_id'] = $inspector->id;
          InspectorInspectorType::create($input);
        }

    }

}
