<?php

namespace App\Http\Controllers\Bsection5;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Bsection5\Workgroup;
use App\Models\Bsection5\Workgrouptis;
use App\Models\Bsection5\Workgroupstaff;
use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;
use App\Models\Basic\Tis;
use App\User;
use App\Models\Tis\Standard;
use HP;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkgroupController extends Controller
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

    public function index()
    {
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bsection5.workgroup.index');
        }
        abort(403);
    }

    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        
        $query = Workgroup::query()->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );  
                                        $user_id = User::Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%")->select('runrecno');
                                        $query->WhereIn('created_by',$user_id)->orWhere('title',  'LIKE', "%$search_full%");
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if( $filter_status == 1){
                                            return $query->where('state', $filter_status);
                                        }else{
                                            return $query->where('state', '<>', 1)->orWhereNull('state');
                                        }
                                    });
                                    

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {  
                                return (!empty($item->title)?$item->title:null);
                            })
                            ->addColumn('created_by', function ($item) {
                                return !empty($item->CreatedName)?$item->CreatedName:null;
                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'bsection5/workgroup','Bsection5\WorkgroupController@destroy', 'bsection5-workgroup');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'action','title'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bsection5.workgroup.create');
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
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('add-'.$model)) {
           
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();    
 
            $workgroup = Workgroup::create($requestData);

            $this->save_user_segister($requestData['user_reg_id'],$workgroup->id);
            $this->save_tis_standards($requestData['tis_id'],$workgroup->id);

            return redirect('bsection5/workgroup')->with('flash_message', 'เพิ่ม workgroup เรียบร้อยแล้ว');
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
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('view-'.$model)) {
            $workgroup = Workgroup::findOrFail($id);
            $workgroup_tis = Workgrouptis::where('workgroup_id', $id)->get();
            $workgroup_staff = Workgroupstaff::where('workgroup_id', $id)->get();
            return view('bsection5.workgroup.show',compact('workgroup',
                                                           'workgroup_tis',
                                                           'workgroup_staff'
                                                        ));
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
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('edit-'.$model)) {
            $workgroup = Workgroup::findOrFail($id);
            $workgroup_tis = Workgrouptis::where('workgroup_id', $id)
                                         ->with(['tis' => function ($query) {
                                                $query->select('tb3_TisAutono', 'tb3_Tisno', 'tb3_TisThainame');
                                         }])->get();
            $workgroup_staff = Workgroupstaff::where('workgroup_id', $id)->get();

            return view('bsection5.workgroup.edit', compact('workgroup',
                                                            'workgroup_tis',
                                                            'workgroup_staff'
                                                          ));
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
        $model = str_slug('bsection5-workgroup','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $workgroup = Workgroup::findOrFail($id);
            $workgroup->update($requestData);

            $this->save_user_segister($requestData['user_reg_id'],$workgroup->id);
            $this->save_tis_standards($requestData['tis_id'],$workgroup->id);

            return redirect('bsection5/workgroup')->with('flash_message', 'แก้ไข workgroup เรียบร้อยแล้ว!');
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


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('bsection5-workgroup','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Workgroup;
          Workgroup::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bsection5/workgroup')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }


    public function data_user_register(Request $request){

        $filter_search = $request->input('filter_search');
        $filter_sub_department_id = $request->input('filter_sub_department_id');
        $filter_department = $request->input('filter_department');

        $query = User::query()->when($filter_search, function ($query, $filter_search){
                                     $search_full = str_replace(' ', '', $filter_search );
                                     $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                })
                                ->when($filter_sub_department_id, function ($query, $filter_sub_department_id){
                                    return $query->where('reg_subdepart', $filter_sub_department_id);
                                })
                                ->when($filter_department, function ($query, $filter_department){
                                 $sub_department =   SubDepartment::where('did', $filter_department)->select('sub_id');
                                    return $query->whereIn('reg_subdepart', $sub_department);
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_user_checkbox[]" class="item_user_checkbox"
                                               value="'. $item->runrecno .'"
                                               data-fullname="'.$item->fullname.'"
                                               data-position="'.(!is_null($item->position)?$item->position:'-').'"
                                               data-subdepart="'.(!is_null($item->subdepart)?$item->subdepart->sub_departname:'-').'"
                                               data-department="'.(!is_null($item->subdepart->department)?$item->subdepart->department->depart_name:'-').'"
                                               >';
                            })          
                            ->addColumn('fullname', function ($item) {
                                return '<small>'.$item->FullName.'<small>';
                            })
                            ->addColumn('position', function ($item) {
                                return !is_null($item->position)?'<small>'.$item->position.'<small>':'-';
                            })
                            ->addColumn('department', function ($item) {
                                return !is_null($item->subdepart->department)?'<small>'.$item->subdepart->department->depart_name.'<small>':'-';
                            })
                            ->addColumn('subdepart', function ($item) {
                                return !is_null($item->subdepart)?'<small>'.$item->subdepart->sub_departname.'<small>':'-';
                            })
                            ->rawColumns(['checkbox', 'state', 'action','department','subdepart','position','fullname'])
                            ->make(true);
    }

    public function data_tis_standards(Request $request){

        $filter_search = $request->get('filter_search_std');

        $query = Tis::query()->when($filter_search, function ($query, $filter_search){
                            return $query->where(function ($query) use ($filter_search) {
                                        $query->where('tb3_TisThainame', 'LIKE', "%{$filter_search}%")
                                                ->orWhere('tb3_TisEngname', 'LIKE', "%{$filter_search}%")
                                                ->orWhere('tb3_Tisno', 'LIKE', "%{$filter_search}%");
                                    });
                        })  
                        ->Where(function($query) {
                            $query->whereIn('status', ['-1', '0', '1', '2', '3']);
                        })->select('tb3_TisAutono', 'tb3_Tisno', 'tb3_TisThainame');

        return Datatables::of($query)
                        ->addIndexColumn()
                        ->addColumn('checkbox', function ($item) {
                            return '<input type="checkbox" name="item_std_checkbox[]" class="item_std_checkbox"
                                        value="'. $item->getKey() .'"
                                        data-tis_name="'.('มอก. '.$item->tb3_Tisno.' : '.$item->tb3_TisThainame).'"
                                        >';
                        })          
                        ->addColumn('tis_name', function ($item) {
                            return '<small>'.'มอก. '.$item->tb3_Tisno.' : '.$item->tb3_TisThainame.'<small>';
                        })
                        ->rawColumns(['checkbox','tis_name'])
                        ->make(true);

    }

    public function save_user_segister($datas, $id){
        Workgroupstaff::where('workgroup_id', $id)->delete();
        foreach($datas as $key => $item) {
          $input = [];
          $input['workgroup_id']   = $id;
          $input['user_reg_id']    = $item;
          Workgroupstaff::create($input);
        }
      }

      public function save_tis_standards($datas, $id){
        Workgrouptis::where('workgroup_id', $id)->delete();

        $input = [];
        foreach($datas as $key => $item) {
            $input[] = ['workgroup_id' => $id, 'tis_id' => $item];
        }
        Workgrouptis::insert($input);
      }

      public function destroy($id)
      {
          $model = str_slug('bsection5-workgroup','-');
          if(auth()->user()->can('delete-'.$model)) {
            Workgroup::destroy($id);
              return redirect('bsection5/workgroup')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
          }
          abort(403);
      }
  
      //เลือกลบแบบทั้งหมดได้
      public function delete(Request $request)
      {
          $id_array = $request->input('id');
          $result = Workgroup::whereIn('id', $id_array);
          if($result->delete())
          {
              echo 'Data Deleted';
          }
  
      }
  
      //เลือกเผยแพร่สถานะทั้งหมดได้
      public function update_publish(Request $request)
      {
          $arr_publish = $request->input('id_publish');
          $state = $request->input('state');
  
          $result = Workgroup::whereIn('id', $arr_publish)->update(['state' => $state]);
          if($result)
          {
              echo 'success';
          } else {
              echo "not success";
          }
  
      }
  
      //เลือกเผยแพร่สถานะได้ที่ละครั้ง
      public function update_status(Request $request)
      {
          $id_status = $request->input('id_status');
          $state = $request->input('state');
  
          $result = Workgroup::where('id', $id_status)  ->update(['state' => $state]);
          
          if($result)
          {
              echo 'success';
          } else {
              echo "not success";
          }
  
      }
}
