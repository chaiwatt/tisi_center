<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\ConfigTerm as config_term;
use Illuminate\Http\Request;

class ConfigTermController extends Controller
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('view-'.$model)) {

            $config_terms = config_term::get();

            $config_term = [];
            foreach ($config_terms as $key => $item) {
              $config_term[$item->variable] = $item->data;
            }

            $config_term['alert10'] = $config_term['condition1']!=','?'':explode(',', $config_term['alert1'])[0];
            $config_term['alert1'] = $config_term['condition1']!=','?$config_term['alert1']:explode(',', $config_term['alert1'])[1];

            $config_term['alert20'] = $config_term['condition2']!=','?'':explode(',', $config_term['alert2'])[0];
            $config_term['alert2'] = $config_term['condition2']!=','?$config_term['alert2']:explode(',', $config_term['alert2'])[1];

            $config_term['alert30'] = $config_term['condition3']!=','?'':explode(',', $config_term['alert3'])[0];
            $config_term['alert3'] = $config_term['condition3']!=','?$config_term['alert3']:explode(',', $config_term['alert3'])[1];

            return view('basic.config_term.index', compact('config_term'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.config_term.create');
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();

            $array = [];
            $array['data'] = $requestData['age'];
            config_term::where('variable', 'age')->update($array);

            $array = [];
            $array['data'] = $requestData['amount'];
            config_term::where('variable', 'amount')->update($array);

            $array = [];
            $array['data'] = array_key_exists('state1', $requestData)?1:0;
            config_term::where('variable', 'state1')->update($array);

            $array = [];
            $array['data'] = $requestData['condition1'];
            config_term::where('variable', 'condition1')->update($array);

            $array = [];
            $array['data'] = $requestData['condition1']!=','?$requestData['alert1']:$requestData['alert10'].','.$requestData['alert1'];
            config_term::where('variable', 'alert1')->update($array);

            $array = [];
            $array['data'] = array_key_exists('state2', $requestData)?1:0;
            config_term::where('variable', 'state2')->update($array);

            $array = [];
            $array['data'] = $requestData['condition2'];
            config_term::where('variable', 'condition2')->update($array);

            $array = [];
            $array['data'] = $requestData['condition2']!=','?$requestData['alert2']:$requestData['alert20'].','.$requestData['alert2'];
            config_term::where('variable', 'alert2')->update($array);

            $array = [];
            $array['data'] = array_key_exists('state3', $requestData)?1:0;
            config_term::where('variable', 'state3')->update($array);

            $array = [];
            $array['data'] = $requestData['condition3'];
            config_term::where('variable', 'condition3')->update($array);

            $array = [];
            $array['data'] = $requestData['condition3']!=','?$requestData['alert3']:$requestData['alert30'].','.$requestData['alert3'];
            config_term::where('variable', 'alert3')->update($array);

            return redirect('basic/config_term')->with('flash_message', 'เพิ่ม config_term เรียบร้อยแล้ว');

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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('view-'.$model)) {
            $config_term = config_term::findOrFail($id);
            return view('basic.config_term.show', compact('config_term'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('edit-'.$model)) {
            $config_term = config_term::findOrFail($id);
            return view('basic.config_term.edit', compact('config_term'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $config_term = config_term::findOrFail($id);
            $config_term->update($requestData);

            return redirect('basic/config_term')->with('flash_message', 'แก้ไข config_term เรียบร้อยแล้ว!');
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new config_term;
            config_term::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            config_term::destroy($id);
          }

          return redirect('basic/config_term')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('config_term','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new config_term;
          config_term::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/config_term')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
