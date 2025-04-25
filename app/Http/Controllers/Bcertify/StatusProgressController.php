<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\StatusProgress as status_progress;
use App\Models\Bcertify\StatusProgressApplicantType;
use Illuminate\Http\Request;

class StatusProgressController extends Controller
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_applicant_type'] = $request->get('filter_applicant_type', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new status_progress;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_applicant_type']!='') {
                $applicant_types = StatusProgressApplicantType::where('applicant_type', $filter['filter_applicant_type'])->pluck('status_progress_id', 'status_progress_id');
                $Query = $Query->whereIn('id', $applicant_types);
            }

            $status_progress = $Query->sortable()->with('user_created')
                                                 ->with('user_updated')
                                                 ->paginate($filter['perPage']);

            return view('bcertify.status_progress.index', compact('status_progress', 'filter'));
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.status_progress.create');
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'publish' => 'required'
        		]);
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $status_progress = status_progress::create($requestData);

            $this->SaveApplicantType($status_progress, $requestData);//บันทึกข้อมูลประเภทผู้ยื่น

            return redirect('bcertify/status_progress')->with('flash_message', 'เพิ่ม status_progress เรียบร้อยแล้ว');
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('view-'.$model)) {
            $status_progress = status_progress::findOrFail($id);
            return view('bcertify.status_progress.show', compact('status_progress'));
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('edit-'.$model)) {

            $status_progress = status_progress::findOrFail($id);

            $status_progress->applicant_type = $status_progress->applicant_type_list->pluck('applicant_type', 'applicant_type');

            return view('bcertify.status_progress.edit', compact('status_progress'));

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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'publish' => 'required'
        		]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $status_progress = status_progress::findOrFail($id);
            $status_progress->update($requestData);

            $this->SaveApplicantType($status_progress, $requestData);//บันทึกข้อมูลประเภทผู้ยื่น

            return redirect('bcertify/status_progress')->with('flash_message', 'แก้ไข status_progress เรียบร้อยแล้ว!');
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
        $model = str_slug('status_progress','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new status_progress;
            status_progress::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            status_progress::destroy($id);
          }

          return redirect('bcertify/status_progress')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('status_progress','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new status_progress;
          status_progress::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/status_progress')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Update Publish ****
    */
    public function update_publish(Request $request){

      $model = str_slug('status_progress','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new status_progress;
          status_progress::whereIn($db->getKeyName(), $ids)->update(['publish' => $requestData['publish']]);
        }

        return redirect('bcertify/status_progress')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save Product Group
    */
    private function SaveApplicantType($status_progress, $requestData){

        StatusProgressApplicantType::where('status_progress_id', $status_progress->id)->delete();

        /* บันทึกข้อมูลประเภทผู้ยื่น */
        foreach ((array)@$requestData['applicant_type'] as $applicant_type) {
          $input_group = [];
          $input_group['applicant_type'] = $applicant_type;
          $input_group['status_progress_id'] = $status_progress->id;
          StatusProgressApplicantType::create($input_group);
        }

    }

}
