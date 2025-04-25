<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\Enms as enms;
use App\Models\Bcertify\EnmsIndustryType;
use Illuminate\Http\Request;

class EnmsController extends Controller
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new enms;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $enms = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.enms.index', compact('enms', 'filter'));
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.enms.create');
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $enms = enms::create($requestData);

            $this->SaveIndustryType($enms, $requestData);

            return redirect('bcertify/enms')->with('flash_message', 'เพิ่ม enm เรียบร้อยแล้ว');
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('view-'.$model)) {
            $enm = enms::findOrFail($id);
            return view('bcertify.enms.show', compact('enm'));
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('edit-'.$model)) {

            $enm = enms::findOrFail($id);

            $enm->industry_type_id = $enm->industry_type_list->pluck('industry_type_id', 'industry_type_id');

            return view('bcertify.enms.edit', compact('enm'));
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $enm = enms::findOrFail($id);
            $enm->update($requestData);

            $this->SaveIndustryType($enm, $requestData);//บันทึกข้อมูลกลุ่มผลิตภัณฑ์

            return redirect('bcertify/enms')->with('flash_message', 'แก้ไข enm เรียบร้อยแล้ว!');
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
        $model = str_slug('enms','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new enms;
            enms::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            enms::destroy($id);
          }

          return redirect('bcertify/enms')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('enms','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new enms;
          enms::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/enms')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save Industry Type
    */
    private function SaveIndustryType($enms, $requestData){

        EnmsIndustryType::where('enms_id', $enms->id)->delete();

        /* บันทึกประเภทอุตสาหกรรม */
        foreach ((array)@$requestData['industry_type_id'] as $industry_type) {
          $input_group = [];
          $input_group['industry_type_id'] = $industry_type;
          $input_group['enms_id'] = $enms->id;
          EnmsIndustryType::create($input_group);
        }

    }

}
