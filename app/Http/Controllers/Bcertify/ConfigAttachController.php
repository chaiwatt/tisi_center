<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\ConfigAttach as config_attach;
use App\Models\Bcertify\ConfigAttachForm;
use Illuminate\Http\Request;

class ConfigAttachController extends Controller
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new config_attach;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $config_attach = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('bcertify.config_attach.index', compact('config_attach', 'filter'));
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.config_attach.create');
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'essential' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $config_attach = config_attach::create($requestData);

            $this->SaveForm($config_attach, $requestData);//บันทึกข้อมูลฟอร์ม

            return redirect('bcertify/config_attach')->with('flash_message', 'เพิ่ม config_attach เรียบร้อยแล้ว');
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('view-'.$model)) {
            $config_attach = config_attach::findOrFail($id);
            return view('bcertify.config_attach.show', compact('config_attach'));
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('edit-'.$model)) {

            $config_attach = config_attach::findOrFail($id);

            $config_attach->form = $config_attach->form_list->pluck('form', 'form');

            return view('bcertify.config_attach.edit', compact('config_attach'));

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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'essential' => 'required'
        		]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $config_attach = config_attach::findOrFail($id);
            $config_attach->update($requestData);

            $this->SaveForm($config_attach, $requestData);//บันทึกข้อมูลประเภทผู้ยื่น

            return redirect('bcertify/config_attach')->with('flash_message', 'แก้ไข config_attach เรียบร้อยแล้ว!');
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
        $model = str_slug('config_attach','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new config_attach;
            config_attach::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            config_attach::destroy($id);
          }

          return redirect('bcertify/config_attach')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('config_attach','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new config_attach;
          config_attach::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/config_attach')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save Form
    */
    private function SaveForm($config_attach, $requestData){

        ConfigAttachForm::where('config_attach_id', $config_attach->id)->delete();

        /* บันทึกข้อมูลประเภทผู้ยื่น */
        foreach ((array)@$requestData['form'] as $form) {
          $input_group = [];
          $input_group['form'] = $form;
          $input_group['config_attach_id'] = $config_attach->id;
          ConfigAttachForm::create($input_group);
        }

    }

}
