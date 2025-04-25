<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Certify\Applicant\CostDetails;
use App\CostEstimation;
use Illuminate\Http\Request;

class CostEstimationController extends Controller
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_title'] = $request->get('filter_title', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);
            $Query = new CostDetails;

            if ($filter['filter_search']!='') {
                if($filter['filter_search'] == 1){
                    $Query = $Query->where('lab',1);
                }elseif($filter['filter_search'] == 2){
                    $Query = $Query->where('ib',1);
                }elseif($filter['filter_search'] == 3){
                    $Query = $Query->where('cb',1);
                }
            }
            if ($filter['filter_title'] != '') {
                $Query = $Query->where('title','LIKE', '%'.$filter['filter_title'].'%');
            }
      
            $costestimation = $Query->orderby('id','desc')->sortable() ->paginate($filter['perPage']);

            return view('certify.costestimation.index', compact('costestimation', 'filter'));
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify.costestimation.create');
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['lab'] = isset($request->lab) ? 1 : null;
            $requestData['ib'] = isset($request->ib) ? 1 : null;
            $requestData['cb'] = isset($request->cb) ? 1 : null;
            CostDetails::create($requestData);
            return redirect('certify/Cost-Estimation')->with('flash_message', 'เพิ่ม CostEstimation เรียบร้อยแล้ว');
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('view-'.$model)) {
            $costestimation = CostDetails::findOrFail($id);
            return view('certify.costestimation.show', compact('costestimation'));
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('edit-'.$model)) {
            $costestimation = CostDetails::findOrFail($id);
            return view('certify.costestimation.edit', compact('costestimation'));
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['lab'] = isset($request->lab) ? 1 : null;
            $requestData['ib'] = isset($request->ib) ? 1 : null;
            $requestData['cb'] = isset($request->cb) ? 1 : null;
            $costestimation = CostDetails::findOrFail($id);
            $costestimation->update($requestData);

            return redirect('certify/Cost-Estimation')->with('flash_message', 'แก้ไข CostEstimation เรียบร้อยแล้ว!');
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
        $model = str_slug('costestimation','-');
        if(auth()->user()->can('delete-'.$model)) {
            CostDetails::destroy($id);
          return redirect('certify/Cost-Estimation')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


 

}
