<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\StaffGroup as staff_group;
use App\Models\Basic\StaffGroupProductGroup;
use Illuminate\Http\Request;

class StaffGroupController extends Controller
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new staff_group;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $staff_group = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.staff_group.index', compact('staff_group', 'filter'));
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.staff_group.create');
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			           'order' => 'required'
		        ]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $staff_group = staff_group::create($requestData);

            $this->SaveProductGroup($staff_group, $requestData);//บันทึกข้อมูลกลุ่มผลิตภัณฑ์

            return redirect('basic/staff_group')->with('flash_message', 'เพิ่ม staff_group เรียบร้อยแล้ว');
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('view-'.$model)) {
            $staff_group = staff_group::findOrFail($id);
            return view('basic.staff_group.show', compact('staff_group'));
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('edit-'.$model)) {

            $staff_group = staff_group::findOrFail($id);

            $staff_group->product_group_id = $staff_group->product_group_list->pluck('product_group_id', 'product_group_id');

            return view('basic.staff_group.edit', compact('staff_group'));

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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			          'order' => 'required'
		        ]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $staff_group = staff_group::findOrFail($id);
            $staff_group->update($requestData);

            $this->SaveProductGroup($staff_group, $requestData);//บันทึกข้อมูลกลุ่มผลิตภัณฑ์

            return redirect('basic/staff_group')->with('flash_message', 'แก้ไข staff_group เรียบร้อยแล้ว!');
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
        $model = str_slug('staff_group','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new staff_group;
            staff_group::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            staff_group::destroy($id);
          }

          return redirect('basic/staff_group')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('staff_group','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new staff_group;
          staff_group::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/staff_group')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save Product Group
    */
    private function SaveProductGroup($staff_group, $requestData){

        StaffGroupProductGroup::where('staff_group_id', $staff_group->id)->delete();

        /* บันทึกข้อมูลใบอนุญาต */
        foreach ((array)@$requestData['product_group_id'] as $product_group) {
          $input_group = [];
          $input_group['product_group_id'] = $product_group;
          $input_group['staff_group_id'] = $staff_group->id;
          StaffGroupProductGroup::create($input_group);
        }

    }

}
