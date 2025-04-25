<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\CertificationScope as certification_scope;
use Illuminate\Http\Request;

use HP;

class CertificationScopeController extends Controller
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new certification_scope;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $certification_scope = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.certification_scope.index', compact('certification_scope', 'filter'));
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.certification_scope.create');
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'certification_branch_id' => 'required',
        			'start_date' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['start_date'] = ($requestData['start_date']!='')?HP::convertDate($requestData['start_date']):null;//แปลงวันที่
            $requestData['end_date'] = ($requestData['end_date']!='')?HP::convertDate($requestData['end_date']):null;//แปลงวันที่

            certification_scope::create($requestData);
            return redirect('bcertify/certification_scope')->with('flash_message', 'เพิ่ม certification_scope เรียบร้อยแล้ว');
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('view-'.$model)) {
            $certification_scope = certification_scope::findOrFail($id);
            return view('bcertify.certification_scope.show', compact('certification_scope'));
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('edit-'.$model)) {

            $certification_scope = certification_scope::findOrFail($id);
            $certification_scope['start_date'] = is_null($certification_scope['start_date'])?'':HP::revertDate($certification_scope['start_date']);
            $certification_scope['end_date'] = is_null($certification_scope['end_date'])?'':HP::revertDate($certification_scope['end_date']);


            return view('bcertify.certification_scope.edit', compact('certification_scope'));

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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'certification_branch_id' => 'required',
        			'start_date' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['start_date'] = ($requestData['start_date']!='')?HP::convertDate($requestData['start_date']):null;//แปลงวันที่
            $requestData['end_date'] = ($requestData['end_date']!='')?HP::convertDate($requestData['end_date']):null;//แปลงวันที่

            $certification_scope = certification_scope::findOrFail($id);
            $certification_scope->update($requestData);

            return redirect('bcertify/certification_scope')->with('flash_message', 'แก้ไข certification_scope เรียบร้อยแล้ว!');
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
        $model = str_slug('certification_scope','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new certification_scope;
            certification_scope::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            certification_scope::destroy($id);
          }

          return redirect('bcertify/certification_scope')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('certification_scope','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new certification_scope;
          certification_scope::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/certification_scope')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
