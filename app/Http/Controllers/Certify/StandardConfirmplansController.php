<?php

namespace App\Http\Controllers\Certify;

use DB;
use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Tis\TisiEstandardDraft;
use App\Models\Tis\TisiEstandardDraftPlan;
use App\Models\Tis\TisiEstandardDraftPlanLog;

class StandardConfirmplansController extends Controller
{

        private $attach_path;//ที่เก็บไฟล์แนบ
        public function __construct()
        {
            $this->middleware('auth');
            $this->attach_path = 'files/standardconfirmplans';
        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standardconfirmplans','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.standard-confirmplans.index');
        }
           abort(403);

    }

    public function data_list(Request $request)
    {
         $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
         $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.
 
        $model = str_slug('standardconfirmplans', '-');
        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');
        $query = TisiEstandardDraftPlan::query()->when($not_admin, function ($query){
                                                    return $query->where(function ($query){
                                                        return $query->where('assign_id', auth()->user()->getKey())
                                                                        ->orWhereHas('tisi_estandard_draft_to', function($query){
                                                                            return $query->where('created_by', auth()->user()->getKey());
                                                                        });
                                                    });
                                                })
                                                ->WhereIn('status_id',[3,4,5,6])
                                                ->when($filter_search, function ($query, $filter_search){
                                                 $search_full = str_replace(' ', '', $filter_search ); 
                                                  $query->where(function ($query2) use($search_full) { 
                                                    $query2->Where(DB::raw("REPLACE(tis_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tis_book,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tis_year,' ','')"), 'LIKE', "%".$search_full."%") 
                                                                ->OrWhere(DB::raw("REPLACE(tis_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tis_name_eng,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(confirm_time,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                  });
                                              }) 
                                             ->when($filter_year, function ($query, $filter_year){
                                                $draft = TisiEstandardDraft::select('id')->where('draft_year', $filter_year);
                                                $query->whereIn('draft_id', $draft);
                                              })
                                              ->when($filter_standard_type, function ($query, $filter_standard_type){
                                                    $query->where('std_type', $filter_standard_type);
                                              })
                                              ->when($filter_method_id, function ($query, $filter_method_id){
                                                    $query->where('method_id', $filter_method_id);
                                               })
                                               ->when($filter_status, function ($query, $filter_status){
                                                     $query->where('status_id', $filter_status);
                                               }); 
 
                                                    
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                            })
                            ->addColumn('draft_year', function ($item) {
                                return   !empty($item->tisi_estandard_draft_to->draft_year)? $item->tisi_estandard_draft_to->draft_year:'';
                            })
                            ->addColumn('tis_name', function ($item) {
                                return   !empty($item->tis_name)? $item->tis_name:'';
                            })
                            ->addColumn('standard_type', function ($item) {
                                return   !empty($item->standard_type_to->title)? $item->standard_type_to->title:'';
                            })
                            ->addColumn('method', function ($item) {
                                return   !empty($item->method_to->title)? $item->method_to->title:'';
                            })
                            ->addColumn('plan_date', function ($item) {
                                return   !empty($item->plan_startdate) && !empty($item->plan_enddate) ? HP::DateFormatGroupTh($item->plan_startdate,$item->plan_enddate):'-';
                            })
                            ->addColumn('status', function ($item) {
                                return   !empty($item->StatusName)   ?  $item->StatusName :'';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                if(!is_null($item->status_id) && $item->status_id >= 4){
                                         return HP::buttonAction( $item->id, 'certify/standard-confirmplans','Certify\\StandardConfirmplansController@destroy', 'standardconfirmplans',true,false,false);
                               }else{
                                        return HP::buttonAction( $item->id, 'certify/standard-confirmplans','Certify\\StandardConfirmplansController@destroy', 'standardconfirmplans',true,true,false);
                                }
                              
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'checkbox','created_name', 'action'])
                            ->make(true);
    }


    public function show($id)
    {
        $model = str_slug('standardconfirmplans','-');
        if(auth()->user()->can('view-'.$model)) {
            $standardconfirmplans = TisiEstandardDraftPlan::findOrFail($id);
            $standardconfirmplans->objectve         = !empty($standardconfirmplans->estandard_offers_to->objectve) ?  $standardconfirmplans->estandard_offers_to->objectve: null ;
            $standardconfirmplans->name             = !empty($standardconfirmplans->estandard_offers_to->name) ?  $standardconfirmplans->estandard_offers_to->name: null ;
            $standardconfirmplans->plan_startdate   =  !empty($standardconfirmplans->plan_startdate) ? HP::revertDate(date('Y-m-d', strtotime($standardconfirmplans->plan_startdate)) ,true) : '';   
            $standardconfirmplans->plan_enddate     =  !empty($standardconfirmplans->plan_enddate) ? HP::revertDate(date('Y-m-d', strtotime($standardconfirmplans->plan_enddate)),true) : '';   
            $standardconfirmplans->budget           =  !empty($standardconfirmplans->budget) ?   number_format($standardconfirmplans->budget,2) :  null; 
            $standardconfirmplans->reason           = !empty($standardconfirmplans->reason_to->title) ?  $standardconfirmplans->reason_to->title: null ;
            return view('certify.standard-confirmplans.show', compact('standardconfirmplans'));
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
        $model = str_slug('standardconfirmplans','-');
        if(auth()->user()->can('edit-'.$model)) {
            $standardconfirmplans = TisiEstandardDraftPlan::findOrFail($id);
            $standardconfirmplans->objectve         = !empty($standardconfirmplans->estandard_offers_to->objectve) ?  $standardconfirmplans->estandard_offers_to->objectve: null ;
            $standardconfirmplans->name             = !empty($standardconfirmplans->estandard_offers_to->name) ?  $standardconfirmplans->estandard_offers_to->name: null ;
            $standardconfirmplans->plan_startdate   =  !empty($standardconfirmplans->plan_startdate) ? HP::revertDate(date('Y-m-d', strtotime($standardconfirmplans->plan_startdate)) ,true) : '';   
            $standardconfirmplans->plan_enddate     =  !empty($standardconfirmplans->plan_enddate) ? HP::revertDate(date('Y-m-d', strtotime($standardconfirmplans->plan_enddate)),true) : '';   
            $standardconfirmplans->budget           =  !empty($standardconfirmplans->budget) ?   number_format($standardconfirmplans->budget,2) :  null; 
            $standardconfirmplans->reason           = !empty($standardconfirmplans->reason_to->title) ?  $standardconfirmplans->reason_to->title: null ;
            return view('certify.standard-confirmplans.edit', compact('standardconfirmplans'));
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
        $model = str_slug('standardconfirmplans','-');
        if(auth()->user()->can('edit-'.$model)) {
            
           
            $requestData = $request->all();
            $requestData['confirm_by']          =  auth()->user()->getKey(); 
            $requestData['confirm_at']          =  date('Y-m-d H:i:s'); 
            $standardconfirmplan = TisiEstandardDraftPlan::findOrFail($id);
            $standardconfirmplan->update($requestData);
            if($request->input('status_id') == 6){
                $data_log = [];
                $data_log['plan_id'] = @$standardconfirmplan->id; 
                $data_log['reverse_date'] = date('Y-m-d'); 
                $data_log['reverse_user'] = @$standardconfirmplan->ConfirmName; 
                $data_log['reverse_detail'] = @$standardconfirmplan->confirm_detail; 
                $data_log['update_status'] = @$standardconfirmplan->StatusName; 
                TisiEstandardDraftPlanLog::create($data_log);
            }
            if ( $request->confirm_attach && $request->hasFile('confirm_attach')) {
                HP::singleFileUpload(
                    $request->file('confirm_attach') ,
                    $this->attach_path,
                    !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new TisiEstandardDraftPlan)->getTable() ),
                     $standardconfirmplan->id,
                    'confirm_attach',
                    !empty($request->document_details) ? $request->document_details : null
                );
            }
            return redirect('certify/standard-confirmplans')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
           abort(403);

    }


}
