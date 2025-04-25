<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\AppointDepartment as appoint_department;
use App\Models\Basic\Amphur;
use App\Models\Basic\DepartmentDepartmentType;
use App\Models\Basic\District;
use Illuminate\Http\Request;

use HP;

class AppointDepartmentController extends Controller
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new appoint_department;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('title', 'LIKE', '%'.$filter['filter_search'].'%')
                                          ->orWhere('tel', 'LIKE', '%'.$filter['filter_search'].'%');
                         });
            }

            $appoint_department = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.appoint_department.index', compact('appoint_department', 'filter'));
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('add-'.$model)) {

          $amphurs = [];
          $districts = [];

          return view('basic.appoint_department.create', compact('amphurs', 'districts'));

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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
        			'title' => 'required'
        		]);

            $requestData = $request->all();

            $requestData['created_by'] = auth()->user()->getKey();//user create

            $appoint_department = appoint_department::create($requestData);


            return redirect('basic/appoint_department')->with('flash_message', 'เพิ่ม appoint_department เรียบร้อยแล้ว');
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('view-'.$model)) {
            $appoint_department = appoint_department::findOrFail($id);
            return view('basic.appoint_department.show', compact('appoint_department'));
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('edit-'.$model)) {

            $appoint_department = appoint_department::findOrFail($id);

            $amphurs = Amphur::where('PROVINCE_ID', $appoint_department->province_id)->pluck('AMPHUR_NAME', 'AMPHUR_ID');
            $districts = District::where('AMPHUR_ID', $appoint_department->amphur_id)->pluck('DISTRICT_NAME', 'DISTRICT_ID');

            return view('basic.appoint_department.edit', compact('appoint_department', 'amphurs', 'districts'));
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			          'title' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $appoint_department = appoint_department::findOrFail($id);
            $appoint_department->update($requestData);


            return redirect('basic/appoint_department')->with('flash_message', 'แก้ไข appoint_department เรียบร้อยแล้ว!');
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
        $model = str_slug('appoint_department','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new appoint_department;
            appoint_department::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            appoint_department::destroy($id);
          }

          return redirect('basic/appoint_department')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('appoint_department','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new appoint_department;
          appoint_department::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/appoint_department')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }


    public function save_appoint_department(Request $request)
    {
      $requestData = $request->all();
      $requestData['created_by'] = auth()->user()->getKey(); //user create
      $appoint_department = appoint_department::create($requestData);
      $last_id = $appoint_department->id;
      $last_insert_data = appoint_department::where('id',$last_id)->first();
      if($appoint_department){
          return response()->json([
          'status' => 'success',
          'id' => $last_insert_data->id,
          'title' => $last_insert_data->title
          ]);
      } else {
          return response()->json([
          'status' => 'error'
          ]);
      }
    }


}
