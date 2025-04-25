<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\InspectKind as inspect_kind;
use Illuminate\Http\Request;

class InspectKindController extends Controller
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new inspect_kind;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $inspect_kind = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.inspect_kind.index', compact('inspect_kind', 'filter'));
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.inspect_kind.create');
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'title_en' => 'required'
		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            inspect_kind::create($requestData);
            return redirect('bcertify/inspect_kind')->with('flash_message', 'เพิ่ม inspect_kind เรียบร้อยแล้ว');
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('view-'.$model)) {
            $inspect_kind = inspect_kind::findOrFail($id);
            return view('bcertify.inspect_kind.show', compact('inspect_kind'));
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('edit-'.$model)) {
            $inspect_kind = inspect_kind::findOrFail($id);
            return view('bcertify.inspect_kind.edit', compact('inspect_kind'));
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'title' => 'required',
			'title_en' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $inspect_kind = inspect_kind::findOrFail($id);
            $inspect_kind->update($requestData);

            return redirect('bcertify/inspect_kind')->with('flash_message', 'แก้ไข inspect_kind เรียบร้อยแล้ว!');
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
        $model = str_slug('inspect_kind','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new inspect_kind;
            inspect_kind::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            inspect_kind::destroy($id);
          }

          return redirect('bcertify/inspect_kind')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('inspect_kind','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new inspect_kind;
          inspect_kind::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/inspect_kind')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
