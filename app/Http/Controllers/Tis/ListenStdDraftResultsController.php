<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\SetStandard;
use App\Models\Tis\Standard;
use App\Models\Tis\NoteStdDraft;
use App\Models\Tis\ListenStdDraft;
use App\Models\Tis\ListenStdDraftDetail;

use Illuminate\Http\Request;

class ListenStdDraftResultsController extends Controller
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['perPage'] = $request->get('perPage', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_result_draft'] = $request->get('filter_result_draft', '');

            $Query = new NoteStdDraft;

            if ($filter['filter_search'] != ''){
                $Query = $Query->where(function ($query) use ($filter) {
                    $search_text = $filter['filter_search'];
                                  $query->where('tis_no', 'LIKE', "%{$search_text}%")
                                  ->orWhere('title', 'LIKE', "%{$search_text}%");
                       });
            }

            if ($filter['filter_status']!='') {
                $Query = $Query->where('state',$filter['filter_status']);
            }

            if ($filter['filter_result_draft']!='' && $filter['filter_result_draft']!='w') {

                $Query = $Query->where('result_draft',$filter['filter_result_draft']);
            } else if ($filter['filter_result_draft']=='w'){
                $Query = $Query->whereNull('result_draft');
            }
            
            $listenstddraftresults = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('tis.listen-std-draft-results.index', compact('listenstddraftresults', 'filter'));
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('tis.listen-std-draft-results.create');
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            
            ListenStdDraftResult::create($requestData);
            return redirect('tis/listen-std-draft-results')->with('flash_message', 'เพิ่ม ListenStdDraftResult เรียบร้อยแล้ว');
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('view-'.$model)) {
            $listenstddraftresult = ListenStdDraftResult::findOrFail($id);
            return view('tis.listen-std-draft-results.show', compact('listenstddraftresult'));
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('edit-'.$model)) {
            $listenstddraftresult = ListenStdDraftResult::findOrFail($id);
            return view('tis.listen-std-draft-results.edit', compact('listenstddraftresult'));
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $listenstddraftresult = ListenStdDraftResult::findOrFail($id);
            $listenstddraftresult->update($requestData);

            return redirect('tis/listen-std-draft-results')->with('flash_message', 'แก้ไข ListenStdDraftResult เรียบร้อยแล้ว!');
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
        $model = str_slug('listenstddraftresults','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new ListenStdDraftResult;
            ListenStdDraftResult::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ListenStdDraftResult::destroy($id);
          }

          return redirect('tis/listen-std-draft-results')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('listenstddraftresults','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new ListenStdDraftResult;
          ListenStdDraftResult::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/listen-std-draft-results')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
