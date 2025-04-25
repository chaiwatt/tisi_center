<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Amphur as amphur;
use Illuminate\Http\Request;

class AmphurController extends Controller
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = $request->all();
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new amphur;

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                                    $query_search->where('AMPHUR_CODE', 'LIKE', '%'.$filter['search'].'%')
                                                 ->orWhere('AMPHUR_NAME', 'LIKE', '%'.$filter['search'].'%');
                                });
            }

            if (array_key_exists('filter_state', $filter) && $filter['filter_state']!='') {
                if($filter['filter_state'] == '1'){
                    $Query = $Query->whereNull('state');
                }else{
                    $Query = $Query->where('state', $filter['filter_state']);
                }
            }

            if (array_key_exists('filter_province', $filter) && $filter['filter_province']!='') {
                $Query = $Query->where('PROVINCE_ID', $filter['filter_province']);
            }

            $amphur = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.amphur.index', compact('amphur', 'filter'));
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.amphur.create');
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            amphur::create($requestData);
            return redirect('basic/amphur')->with('flash_message', 'เพิ่ม amphur เรียบร้อยแล้ว');
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('view-'.$model)) {
            $amphur = amphur::findOrFail($id);
            return view('basic.amphur.show', compact('amphur'));
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('edit-'.$model)) {
            $amphur = amphur::findOrFail($id);
            return view('basic.amphur.edit', compact('amphur'));
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['state']     = array_key_exists('state', $requestData) ? null : '0';
            $amphur = amphur::findOrFail($id);
            $amphur->update($requestData);

            return redirect('basic/amphur')->with('flash_message', 'แก้ไข amphur เรียบร้อยแล้ว!');
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
        $model = str_slug('amphur','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new amphur;
            amphur::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            amphur::destroy($id);
          }

          return redirect('basic/amphur')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('amphur','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new amphur;
          amphur::whereIn($db->getKeyName(), $ids)->update(['state' => ($requestData['state'] == '1' ? null : '0' )]);
        }

        return redirect('basic/amphur')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
