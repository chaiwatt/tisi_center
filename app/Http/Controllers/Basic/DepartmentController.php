<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Department as department;
use App\Models\Basic\Amphur;
use App\Models\Basic\DepartmentDepartmentType;
use App\Models\Basic\District;
use Illuminate\Http\Request;

use HP;

class DepartmentController extends Controller
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
        $model = str_slug('department','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new department;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('title', 'LIKE', '%'.$filter['filter_search'].'%')
                                          ->orWhere('tel', 'LIKE', '%'.$filter['filter_search'].'%');
                         });
            }

            $department = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.department.index', compact('department', 'filter'));
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
        $model = str_slug('department','-');
        if(auth()->user()->can('add-'.$model)) {

          $amphurs = [];
          $districts = [];

          return view('basic.department.create', compact('amphurs', 'districts'));

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
        $model = str_slug('department','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
        			'title' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create

            $department = department::create($requestData);

            $this->SaveDepartmentType($department, $requestData);

            return redirect('basic/department')->with('flash_message', 'เพิ่ม department เรียบร้อยแล้ว');
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
        $model = str_slug('department','-');
        if(auth()->user()->can('view-'.$model)) {
            $department = department::findOrFail($id);
            return view('basic.department.show', compact('department'));
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
        $model = str_slug('department','-');
        if(auth()->user()->can('edit-'.$model)) {

            $department = department::findOrFail($id);
           
            $department->department_type_id = $department->department_type_list->pluck('department_type_id', 'department_type_id');
          
            $amphurs = Amphur::whereNull('state')->where('PROVINCE_ID', $department->province_id)->pluck('AMPHUR_NAME', 'AMPHUR_ID');
            $districts = District::whereNull('state')->where('AMPHUR_ID', $department->amphur_id)->pluck('DISTRICT_NAME', 'DISTRICT_ID');

            return view('basic.department.edit', compact('department', 'amphurs', 'districts'));
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
        $model = str_slug('department','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			          'title' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $department = department::findOrFail($id);
            $department->update($requestData);

            $this->SaveDepartmentType($department, $requestData);


            return redirect('basic/department')->with('flash_message', 'แก้ไข department เรียบร้อยแล้ว!');
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
        $model = str_slug('department','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new department;
            department::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            department::destroy($id);
          }

          return redirect('basic/department')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('department','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new department;
          department::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/department')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    private function SaveDepartmentType($department, $requestData){

      DepartmentDepartmentType::where('department_id', $department->id)->delete();

      foreach ((array)@$requestData['department_type_id'] as $department_type) {
        $input_group = [];
        $input_group['department_id'] = $department->id;
        $input_group['department_type_id'] = $department_type;
        DepartmentDepartmentType::create($input_group);
      }

  }


}
