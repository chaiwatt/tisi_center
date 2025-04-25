<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\ExpertGroup;
use App\Models\Basic\ExpertDepartment;
use Illuminate\Http\Request;

class ExpertGroupsController extends Controller
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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('view-'.$model)) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $expertgroups = ExpertGroup::where('title', 'LIKE', "%$keyword%")
                ->orWhere('state', 'LIKE', "%$keyword%")
                ->orWhere('created_by', 'LIKE', "%$keyword%")
                ->orWhere('updated_by', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $expertgroups = ExpertGroup::sortable()->with('user_created')
                                                         ->with('user_updated')
                                                         ->paginate($perPage);
            }

            return view('basic.expert-groups.index', compact('expertgroups'));
        }
         return abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.expert-groups.create');
        }
         return abort(403);

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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            
            $expert_group = ExpertGroup::create($requestData);

            if($request->has('department_id')){
                $department_ids = $request->get('department_id');
                $created_by = auth()->user()->getKey();
                foreach($department_ids as $department_id){
                    $new_arr = [];
                    $new_arr['expert_id'] = $expert_group->id;
                    $new_arr['department_id'] = $department_id;
                    $new_arr['created_by'] = $created_by;
                    ExpertDepartment::create($new_arr);
                }
            }
            return redirect('basic/expert-groups')->with('flash_message', 'เพิ่ม ExpertGroup เรียบร้อยแล้ว');
        }
         return abort(403);
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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('view-'.$model)) {
            $expertgroup = ExpertGroup::with('basic_expert_department')->findOrFail($id);
            return view('basic.expert-groups.show', compact('expertgroup'));
        }
         return abort(403);
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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('edit-'.$model)) {
            $expertgroup = ExpertGroup::with('basic_expert_department')->findOrFail($id);
            return view('basic.expert-groups.edit', compact('expertgroup'));
        }
         return abort(403);
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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $expertgroup = ExpertGroup::findOrFail($id);
            $expertgroup->update($requestData);

            ExpertDepartment::where('expert_id', $id)
                            ->when($request->get('department_id'), function ($query, $department_ids){
                                return $query->whereNotIn('department_id', $department_ids);
                            })->delete();
            $olds = ExpertDepartment::where('expert_id', $id)->pluck('department_id')->all();

            if($request->has('department_id')){
                $department_ids = $request->get('department_id');
                $created_by = auth()->user()->getKey();
                foreach($department_ids as $department_id){
                    if(!in_array($department_id, $olds)){
                        $new_arr = [];
                        $new_arr['expert_id'] = $expertgroup->id;
                        $new_arr['department_id'] = $department_id;
                        $new_arr['created_by'] = $created_by;
                        ExpertDepartment::create($new_arr);
                    }
                }
            }

            return redirect('basic/expert-groups')->with('flash_message', 'แก้ไข ExpertGroup เรียบร้อยแล้ว!');
        }
         return abort(403);

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
        $model = str_slug('expertgroups','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new ExpertGroup;
            ExpertGroup::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ExpertGroup::destroy($id);
            ExpertDepartment::where('expert_id', $id)->delete();
          }

          return redirect('basic/expert-groups')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
         return abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('expertgroups','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new ExpertGroup;
          ExpertGroup::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/expert-groups')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

       return abort(403);

    }

}
