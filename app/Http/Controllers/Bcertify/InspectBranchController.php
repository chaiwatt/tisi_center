<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\InspectBranch as inspect_branch;
use Illuminate\Http\Request;

class InspectBranchController extends Controller
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_inspect_category'] = $request->get('filter_inspect_category', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new inspect_branch;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_inspect_category']!='') {
                $Query = $Query->where('inspect_category_id', $filter['filter_inspect_category']);
            }

            $inspect_branch = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->with('inspect_category')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.inspect_branch.index', compact('inspect_branch', 'filter'));
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.inspect_branch.create');
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'title_en' => 'required',
			'inspect_category_id' => 'required'
		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            inspect_branch::create($requestData);
            return redirect('bcertify/inspect_branch')->with('flash_message', 'เพิ่ม inspect_branch เรียบร้อยแล้ว');
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('view-'.$model)) {
            $inspect_branch = inspect_branch::findOrFail($id);
            return view('bcertify.inspect_branch.show', compact('inspect_branch'));
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $inspect_branch = inspect_branch::findOrFail($id);
            return view('bcertify.inspect_branch.edit', compact('inspect_branch'));
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'title_en' => 'required',
			'inspect_category_id' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $inspect_branch = inspect_branch::findOrFail($id);
            $inspect_branch->update($requestData);

            return redirect('bcertify/inspect_branch')->with('flash_message', 'แก้ไข inspect_branch เรียบร้อยแล้ว!');
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
        $model = str_slug('inspect_branch','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new inspect_branch;
            inspect_branch::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            inspect_branch::destroy($id);
          }

          return redirect('bcertify/inspect_branch')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('inspect_branch','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new inspect_branch;
          inspect_branch::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/inspect_branch')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
