<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Certify\SetStandardUser;
use App\Models\Certify\SetStandardUserSub;
use Illuminate\Http\Request; 

use  App\Models\Basic\SubDepartment;
class SetStandardUserController extends Controller
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('view-'.$model)) {


            $filter = [];
            $filter['filter_sub_department_id'] = $request->get('filter_sub_department_id', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new SetStandardUser;
            if ($filter['filter_sub_department_id']!='') {
                $Query = $Query->where('sub_department_id', $filter['filter_sub_department_id']);
            }
            $tisusercertify = $Query ->orderby('id','desc')->sortable()->paginate($filter['perPage']);

            return view('certify.tis-user-certify.index', compact('tisusercertify', 'filter'));
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify.tis-user-certify.create');
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $SetStandardUser =SetStandardUser::create($requestData);
            $this->save_detail($SetStandardUser,$request);
            return redirect('certify/set-standard-user')->with('flash_message', 'เพิ่ม TisUserCertify เรียบร้อยแล้ว');
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('view-'.$model)) {
            $SetStandardUser = SetStandardUser::findOrFail($id);
            return view('certify.tis-user-certify.show', compact('tisusercertify'));
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('edit-'.$model)) {
            $setstandard = SetStandardUser::findOrFail($id);
            // return $setstandard;
            return view('certify.tis-user-certify.edit', compact('setstandard'));
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $setstandard = SetStandardUser::findOrFail($id);
            $setstandard->update($requestData);
            $this->save_detail($setstandard,$request);
            return redirect('certify/set-standard-user')->with('flash_message', 'เพิ่ม TisUserCertify เรียบร้อยแล้ว');
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
        $model = str_slug('tisusercertify','-');
        if(auth()->user()->can('delete-'.$model)) {
             SetStandardUser::destroy($id);
            SetStandardUserSub::where('standard_user_id', $id)->delete();
            return redirect('certify/set-standard-user')->with('flash_message', 'เพิ่ม TisUserCertify เรียบร้อยแล้ว');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('tisusercertify','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new TisUserCertify;
          TisUserCertify::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/tis-user-certify')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }
    private function save_detail($main, $request){
            if(isset($request->formula_id)){
                SetStandardUserSub::where('standard_user_id', $main->id)->delete();
                foreach((array)$request->formula_id as $key => $itme) {
                        $input = [];
                        $input['standard_user_id'] = $main->id;
                        $input['standard_id'] = $itme;
                    if(isset($request->branch) && $request->lab_ability ==1){
                        $input['test_branch_id'] = $request->branch[$key] ?? null;
                    }
                    if(isset($request->branch) && $request->lab_ability ==2){
                        $input['items_id'] =  $request->branch[$key] ?? null;
                    }
                    SetStandardUserSub::create($input);
                }
            }
      }
    // กลุ่มงานหลัก ->  กลุ่มงานย่อย
    public function DataSubDepartment($department_id){
       $SubDepartment =SubDepartment::select('sub_id','sub_departname')
                                    ->where('did',$department_id)
                                    ->orderbyRaw('CONVERT(sub_departname USING tis620)')
                                    ->get();
        return response()->json($SubDepartment);
      }

      public function DataDepartment($main){
        $SetStandardUser =SetStandardUser::select('sub_department_id')->get();
        return response()->json($SetStandardUser);
       }
}
