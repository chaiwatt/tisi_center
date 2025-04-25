<?php

namespace App\Http\Controllers\Basic\Branch;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Branch;
use App\Models\Basic\BranchTis;
use App\Models\Basic\Tis;
use Illuminate\Http\Request;

class BranchController extends Controller
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_branch_groups'] = $request->get('filter_branch_groups', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new Branch;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }
            if ($filter['filter_branch_groups']!='') {
                $Query = $Query->where('branch_group_id', $filter['filter_branch_groups']);
            }

            $branch = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.branch.index', compact('branch', 'filter'));
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.branch.create');
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			'title' => 'required'
		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $branch = Branch::create($requestData);

            if(!empty($requestData['tis_id']) && count($requestData['tis_id']) > 0){
                $this->save_tis_standards($requestData['tis_id'], $branch->id, $branch->branch_group_id);
            }

            return redirect('basic/branches')->with('flash_message', 'เพิ่ม Branch เรียบร้อยแล้ว');
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('view-'.$model)) {
            $branch = Branch::findOrFail($id);
            return view('basic.branch.show', compact('branch'));
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $branch = Branch::findOrFail($id);
            return view('basic.branch.edit', compact('branch'));
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $branch = Branch::findOrFail($id);
            $branch->update($requestData);

            if(!empty($requestData['tis_id']) && count($requestData['tis_id']) > 0){
                $this->save_tis_standards($requestData['tis_id'], $branch->id, $branch->branch_group_id);
            }

            return redirect('basic/branches')->with('flash_message', 'แก้ไข Branch เรียบร้อยแล้ว!');
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
        $model = str_slug('branch','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Branch;
            Branch::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Branch::destroy($id);
          }

          return redirect('basic/branches')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('branch','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Branch;
          Branch::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/branches')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    //บันทึก/อัพเดท มาตรฐาน
    public function save_tis_standards($datas, $id, $branch_groups_id){
        BranchTis::where('branch_id', $id)->delete();
        foreach($datas as $key => $item) {

            $tis = Tis::find($item);

            $input = [];
            $input['branch_groups_id'] = $branch_groups_id;
            $input['branch_id']        = $id;
            $input['tis_id']           = $item;
            $input['tis_tisno']        = $tis->tb3_Tisno;
            BranchTis::create($input);
        }
    }

}
