<?php

namespace App\Http\Controllers\Bsection5;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bsection5\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
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
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_text'] = $request->get('filter_text', '');
            $filter['filter_standard_type'] = $request->get('filter_standard_type', '');
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new Standard;

            if ($filter['filter_text']!='') {
                $Query = $Query->where('title', 'LIKE', '%'.$filter['filter_text'].'%')->orWhere('description', 'LIKE', '%'.$filter['filter_text'].'%');
            }
            if ($filter['filter_standard_type']!='') {
                $Query = $Query->whereJsonContains('standard_type', $filter['filter_standard_type']);
            }
            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $standard = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('bsection5.standards.index', compact('standard', 'filter'));
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
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bsection5.standards.create');
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
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['standard_type'] = !empty($requestData['standard_type'])?json_encode($requestData['standard_type'], JSON_UNESCAPED_UNICODE):null;
            
            Standard::create($requestData);
            return redirect('bsection5/standards')->with('flash_message', 'เพิ่ม Standard เรียบร้อยแล้ว');
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
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('view-'.$model)) {
            $standard = Standard::findOrFail($id);
            return view('bsection5.standards.show', compact('standard'));
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
    public function edit(Standard $standard)
    {
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('edit-'.$model)) {
            $standard->standard_type = !empty($standard->standard_type)?json_decode($standard->standard_type):null;
            return view('bsection5.standards.edit', compact('standard'));
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
    public function update(Request $request, Standard $standard)
    {
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['standard_type'] = !empty($requestData['standard_type'])?json_encode($requestData['standard_type'], JSON_UNESCAPED_UNICODE):null;
            
            $standard->update($requestData);

            return redirect('bsection5/standards')->with('flash_message', 'แก้ไข Standard เรียบร้อยแล้ว!');
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
        $model = str_slug('bsection5-standard','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Standard;
            Standard::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Standard::destroy($id);
          }

          return redirect('bsection5/standards')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('bsection5-standard','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Standard;
          Standard::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bsection5/standards')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
