<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Ics as ics;
use Illuminate\Http\Request;

use HP;

class IcsController extends Controller
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new ics;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('title', 'LIKE', '%'.$filter['filter_search'].'%')
                                          ->orWhere('tel', 'LIKE', '%'.$filter['filter_search'].'%');
                         });
            }

            $ics = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.ics.index', compact('ics', 'filter'));
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('add-'.$model)) {

          $amphurs = [];
          $districts = [];

          return view('basic.ics.create', compact('amphurs', 'districts'));

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
        $model = str_slug('ics','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
        			'title_en' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create

            $ics = ics::create($requestData);

            return redirect('basic/ics')->with('flash_message', 'เพิ่ม ICS เรียบร้อยแล้ว');
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('view-'.$model)) {
            $ics = ics::findOrFail($id);
            return view('basic.ics.show', compact('ics'));
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('edit-'.$model)) {

            $ics = ics::findOrFail($id);

            return view('basic.ics.edit', compact('ics', 'amphurs', 'districts'));
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			          'title_en' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $ics = ics::findOrFail($id);
            $ics->update($requestData);

            return redirect('basic/ics')->with('flash_message', 'แก้ไข ICS เรียบร้อยแล้ว!');
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
        $model = str_slug('ics','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new ics;
            ics::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ics::destroy($id);
          }

          return redirect('basic/ics')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('ics','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new ics;
          ics::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/ics')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
