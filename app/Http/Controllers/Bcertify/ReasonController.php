<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables; 
use App\Models\Bcertify\Reason;
use Illuminate\Http\Request;
use HP;
use DB;
class ReasonController extends Controller
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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bcertify.reason.index');
        }
         return abort(403);

    }

    public function data_list(Request $request)
    { 
 
      $model = str_slug('bcertify-reason', '-');
      $filter_search = $request->input('filter_search');
      $filter_state = $request->input('filter_state');
      $filter_condition = $request->input('filter_condition');
 
      $query = Reason::query()                                      
                            ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search ); 
                                    return   $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")  ;    
                            }) 
                            ->when($filter_state, function ($query, $filter_state){
                                if($filter_state == '2'){
                                    return  $query->where('condition','!=',1);
                                }else{
                                    return  $query->where('state', $filter_state);
                                }
                            })
                            ->when($filter_condition, function ($query, $filter_condition){
                                if($filter_condition == '2'){
                                    return  $query->where('condition','!=',1);
                                }else{
                                    return  $query->where('condition', $filter_condition);
                                }
                            }); 
                                  
                                                  
      return Datatables::of($query)
                          ->addIndexColumn()
                          ->addColumn('checkbox', function ($item) {
                              return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                          })
                          ->addColumn('title', function ($item) {
                              return   !empty($item->title) ? $item->title :'';
                          })
                          ->addColumn('condition', function ($item) {
                              return   !empty($item->ConditionIcon) ? $item->ConditionIcon :'';
                          })
                          ->addColumn('state', function ($item) {
                            return   !empty($item->StateIcon) ? $item->StateIcon :'';
                        })
                          ->addColumn('created_at', function ($item) {
                              return   !empty($item->created_at) ?HP::DateThai($item->created_at):'-';
                          })
                          ->addColumn('full_name', function ($item) {
                              return   !empty($item->user_created->FullName) ? $item->user_created->FullName :'-';
                          })

                          ->addColumn('action', function ($item) use($model) {
                                  return HP::buttonAction( $item->id, 'bcertify/reason','Bcertify\\ReasonController@destroy', 'bcertify-reason');
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns([ 'checkbox',  'condition','state',  'action']) 
                          ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.reason.create');
        }
         return abort(403);

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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['condition'] = isset($request->condition) ? 1 : 2;
            $requestData['state'] = isset($request->state) ? 1 : 2;
            Reason::create($requestData);
            return redirect('bcertify/reason')->with('flash_message', 'เรียบร้อยแล้ว');
        }
         return abort(403);
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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('view-'.$model)) {
            $reason = Reason::findOrFail($id);
            return view('bcertify.reason.show', compact('reason'));
        }
         return abort(403);
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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('edit-'.$model)) {
            $reason = Reason::findOrFail($id);
            return view('bcertify.reason.edit', compact('reason'));
        }
         return abort(403);
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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['condition'] = isset($request->condition) ? 1 : 2;
            $requestData['state'] = isset($request->state) ? 1 : 2;
            $reason = Reason::findOrFail($id);
            $reason->update($requestData);

            return redirect('bcertify/reason')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
         return abort(403);

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
        $model = str_slug('bcertify-reason','-');
        if(auth()->user()->can('delete-'.$model)) {
           Reason::destroy($id);
          return redirect('bcertify/reason')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
         return abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('bcertify-reason','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Reason;
          Reason::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/reason')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

       return abort(403);

    }

}
