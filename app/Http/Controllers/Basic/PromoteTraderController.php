<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\PromoteTrader as promote_trader;
use Illuminate\Http\Request;

class PromoteTraderController extends Controller
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new promote_trader;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $promote_trader = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('basic.promote_trader.index', compact('promote_trader', 'filter'));
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.promote_trader.create');
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            
            promote_trader::create($requestData);
            return redirect('basic/promote_trader')->with('flash_message', 'เพิ่ม PromoteTrader เรียบร้อยแล้ว');
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('view-'.$model)) {
            $promote_trader = promote_trader::findOrFail($id);
            return view('basic.promote_trader.show', compact('promote_trader'));
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('edit-'.$model)) {
            $promote_trader = promote_trader::findOrFail($id);
            return view('basic.promote_trader.edit', compact('promote_trader'));
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $promote_trader = promote_trader::findOrFail($id);
            $promote_trader->update($requestData);

            return redirect('basic/promote_trader')->with('flash_message', 'แก้ไข PromoteTrader เรียบร้อยแล้ว!');
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
        $model = str_slug('promote_trader','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new promote_trader;
            promote_trader::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            promote_trader::destroy($id);
          }

          return redirect('basic/promote_trader')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('promote_trader','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new promote_trader;
          promote_trader::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/promote_trader')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
