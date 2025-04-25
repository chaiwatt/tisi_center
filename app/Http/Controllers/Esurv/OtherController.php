<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\Other as other;
use Illuminate\Http\Request;

use HP;

class OtherController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/other/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('other','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_inform_type'] = $request->get('filter_inform_type', '');
            $filter['filter_date_start'] = $request->get('filter_date_start', '');
            $filter['filter_date_end'] = $request->get('filter_date_end', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new other;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_created_by']!='') {//ผู้แจ้ง
                $Query = $Query->where('created_by', $filter['filter_created_by']);
            }

            if ($filter['filter_inform_type']!='') {//มาตรฐาน
                $Query = $Query->where('inform_type', $filter['filter_inform_type']);
            }

            if($filter['filter_date_start']!='' && $filter['filter_date_end']!=''){//ช่วงวันที่รับแจ้ง
                $Query = $Query->where('created_at', '>=', HP::convertDate($filter['filter_date_start']).' 00:00:00')
                               ->where('created_at', '<=', HP::convertDate($filter['filter_date_end']).' 23:59:59');
            }

            $other = $Query->where('state', '!=', 0)
                           ->sortable()
                           ->with('trader_created')
                           ->paginate($filter['perPage']);

            return view('esurv.other.index', compact('other', 'filter'));
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
        $model = str_slug('other','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('esurv.other.create');
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
        $model = str_slug('other','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            other::create($requestData);
            return redirect('esurv/other')->with('flash_message', 'เพิ่ม other เรียบร้อยแล้ว');
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
        $model = str_slug('other','-');
        if(auth()->user()->can('view-'.$model)) {
            $previousUrl = app('url')->previous();
            $other = other::findOrFail($id);
             //มาตรฐาน
             $other->tb3_Tisno = $other->tis_list->pluck('tb3_Tisno', 'tb3_Tisno');
             //ไฟล์แนบ
             $attachs = json_decode($other['attach']);
             $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
             $attach_path = $this->attach_path;

            return view('esurv.other.show', compact('other', 'attachs', 'attach_path','previousUrl'));

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
        $model = str_slug('other','-');
        if(auth()->user()->can('edit-'.$model)) {
            $other = other::findOrFail($id);
            $previousUrl = app('url')->previous();
            //มาตรฐาน
            $other->tb3_Tisno = $other->tis_list->pluck('tb3_Tisno', 'tb3_Tisno');

            //ไฟล์แนบ
            $attachs = json_decode($other['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            return view('esurv.other.edit', compact('other', 'attachs', 'attach_path','previousUrl'));
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
        $model = str_slug('other','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $other = other::findOrFail($id);
            $other->update($requestData);

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('flash_message', 'แก้ไข other เรียบร้อยแล้ว');
            }else{
               return redirect('esurv/other')->with('flash_message', 'แก้ไข other เรียบร้อยแล้ว!');
            }

         
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
        $model = str_slug('other','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new other;
            other::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            other::destroy($id);
          }

          return redirect('esurv/other')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('other','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new other;
          other::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/other')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function report(Request $request)
    {
        $model = str_slug('other','-');
        if(auth()->user()->can('view-'.$model)) {

            $Query = new other;
            $Query = $Query->selectRaw('
                        COUNT(DISTINCT (created_by)) as list_name,

                        COUNT(`esurv_others`.id) as total
                    ');

            $other = $Query->where('state', '!=', 0)
                           ->sortable()
                           ->with('trader_created')
                           ->paginate();

            return view('esurv.other.report', compact('other'));
        }
        abort(403);

    }

}
