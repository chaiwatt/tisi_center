<?php

namespace App\Http\Controllers\Certify;

use DB;
use HP;

use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;
use App\Models\Tis\TisiEstandardDraft;
use App\Models\Tis\TisiEstandardDraftPlan;
use App\Models\Tis\TisiEstandardDraftPlanLog;

class StandardPlansController extends Controller
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
        $model = str_slug('standardplans','-');
        if(auth()->user()->can('view-'.$model)) {
 
            return view('certify.standard-plans.index');
        }
        abort(403);

    }


    public function data_list(Request $request)
    {

        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles)); // ไม่ใช่ Admin หรือไม่ใช่ ผอ.
 
        $model = str_slug('standardplans', '-');
        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');
        $query = TisiEstandardDraftPlan::query()->with(['tisi_estandard_draft_to'])
                                            ->whereHas('tisi_estandard_draft_to', function($query){
                                                return $query->where('status_id', 2);
                                            })
                                            ->when($not_admin, function ($query){
                                                return $query->where(function ($query){
                                                    return $query->where('assign_id', auth()->user()->getKey())
                                                                ->orWhereHas('tisi_estandard_draft_to', function($query){
                                                                    return $query->where('created_by', auth()->user()->getKey());
                                                                });
                                                });
                                            })
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
                                    if(!is_null($item->status_id) && ($item->status_id >= 3 && $item->status_id != 6)){
                                        return HP::buttonAction( $item->id, 'certify/standard-plans','Certify\\StandardPlansController@destroy', 'standardplans',true,false,false);
                                    }else{
                                        return HP::buttonAction( $item->id, 'certify/standard-plans','Certify\\StandardPlansController@destroy', 'standardplans',true,true,false);
                                    }
                                  
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'checkbox','created_name', 'action'])
                            ->make(true);
    }

    public function data_log_list(Request $request)
    {
 
        $plan_id = $request->input('plan_id');
        $query = TisiEstandardDraftPlanLog::query()->where('plan_id', $plan_id);
 
                                                    
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('reverse_user', function ($item) {
                                return !empty($item->reverse_user)? $item->reverse_user:null;
                            })
                            ->addColumn('reverse_detail', function ($item) {
                                return !empty($item->reverse_detail)? $item->reverse_detail:null;
                            })
                            ->addColumn('reverse_date', function ($item) {
                                return !empty($item->reverse_date)? $item->reverse_date:null;
                            })
                            ->addColumn('update_user', function ($item) {
                                return !empty($item->update_user)? $item->update_user:null;
                            })
                            ->addColumn('update_date', function ($item) {
                                return !empty($item->update_date)? $item->update_date:null;
                            })
                            ->addColumn('update_detail', function ($item) {
                                return !empty($item->update_detail)? $item->update_detail:null;
                            })
                            ->addColumn('update_status', function ($item) {
                                return !empty($item->update_status)? $item->update_status:null;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'ASC');
                            })
                            ->rawColumns([])
                            ->make(true);
    }


 
    public function show($id)
    {
        $model = str_slug('standardplans','-');
        if(auth()->user()->can('view-'.$model)) {
            $standardplan                   = TisiEstandardDraftPlan::findOrFail($id);
            $standardplan->objectve         = !empty($standardplan->estandard_offers_to->objectve) ?  $standardplan->estandard_offers_to->objectve: null ;
            $standardplan->name             = !empty($standardplan->estandard_offers_to->name) ?  $standardplan->estandard_offers_to->name: null ;
            $standardplan->plan_startdate   =  !empty($standardplan->plan_startdate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_startdate)) ,true) : '';   
            $standardplan->plan_enddate     =  !empty($standardplan->plan_enddate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_enddate)),true) : '';   
            $standardplan->budget           =  !empty($standardplan->budget) ?   number_format($standardplan->budget,2) :  null; 
            $standardplan->reason           =  !empty($standardplan->reason_to->title) ? $standardplan->reason_to->title : null;
            return view('certify.standard-plans.show', compact('standardplan'));
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
        $model = str_slug('standardplans','-');
        if(auth()->user()->can('edit-'.$model)) {
            $standardplan                   = TisiEstandardDraftPlan::findOrFail($id);
            $standardplan->objectve         = !empty($standardplan->estandard_offers_to->objectve) ?  $standardplan->estandard_offers_to->objectve: null ;
            $standardplan->name             = !empty($standardplan->estandard_offers_to->name) ?  $standardplan->estandard_offers_to->name: null ;
            $standardplan->plan_startdate   =  !empty($standardplan->plan_startdate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_startdate)) ,true) : '';   
            $standardplan->plan_enddate     =  !empty($standardplan->plan_enddate) ? HP::revertDate(date('Y-m-d', strtotime($standardplan->plan_enddate)),true) : '';   
            $standardplan->budget           =  !empty($standardplan->budget) ?   number_format($standardplan->budget,2) :  null; 
            $standardplan->reason           =  !empty($standardplan->reason_to->title) ? $standardplan->reason_to->title : null;
            return view('certify.standard-plans.edit', compact('standardplan'));
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
        $model = str_slug('standardplans','-');
        if(auth()->user()->can('edit-'.$model)) {
            
           
            $requestData = $request->all();
            $requestData['updated_by']          =  auth()->user()->getKey();
            $requestData['plan_startdate']      =  !empty($requestData['plan_startdate']) ? HP::convertDate($requestData['plan_startdate'],true) : null;   
            $requestData['plan_enddate']        =   !empty($requestData['plan_enddate']) ? HP::convertDate($requestData['plan_enddate'],true) : null;  
            $requestData['budget']              =  !empty($requestData['budget'])   ? str_replace(",","",$requestData['budget']) : null; 
            $standardplan                       = TisiEstandardDraftPlan::findOrFail($id);
            $data_old = $standardplan->toArray();
            $standardplan->update($requestData);
            $data_changes = $standardplan->getChanges();

             $set_standards  =  SetStandards::where('plan_id', $standardplan->id)->first();
            if($standardplan->status_id == 3 && is_null($set_standards)){  // นำส่งแผน
                  $standards            = new  SetStandards;
                  $standards->plan_id   = $standardplan->id;
                //   $standards->projectid =  self::get_projectid();
                $standards->created_by  =  auth()->user()->getKey();
                  $standards->status_id = 0; // รอกำหนดมาตรฐาน
                  $standards->save();
            }   
            
            if($standardplan->status_id == 3 && (!empty($data_old['status_id']) && @$data_old['status_id'] == 6)){
                $data_old = new TisiEstandardDraftPlan($data_old);
                $change_fields = [
                    'status_id' => "- จาก สถานะ : {$data_old->StatusName} เป็น {$standardplan->StatusName} ", 
                    'method_id' => "- จาก วิธีการ : {$data_old->MethodTitle} เป็น {$standardplan->MethodTitle} ", 
                    'period' => "- จาก ระยะเวลา : {$data_old->period} เป็น {$standardplan->period}", 
                    'plan_startdate' => "", 
                    'plan_enddate' => "", 
                    'plan_date1' => "- จาก กำหนด : {$data_old->plan_startdate} ถึง {$data_old->plan_enddate} เป็น {$standardplan->plan_startdate} ถึง {$standardplan->plan_enddate} ", 
                    'plan_date2' => "- จาก กำหนด : {$data_old->plan_startdate} ถึง {$data_old->plan_enddate} เป็น {$standardplan->plan_startdate} ถึง {$data_old->plan_enddate} ", 
                    'plan_date3' => "- จาก กำหนด : {$data_old->plan_startdate} ถึง {$data_old->plan_enddate} เป็น {$data_old->plan_startdate} ถึง {$standardplan->plan_enddate} ", 
                    'budget' => "- จาก งบประมาณ : {$data_old->budget} เป็น {$standardplan->budget}", 
                    'ref_budget' => "- จาก แหล่งที่มางบประมาณ : {$data_old->RefBudgetTitle} เป็น {$standardplan->RefBudgetTitle} ", 
                    'budget_by' => "- จาก ผู้สนับสนุน : {$data_old->budget_by} เป็น {$standardplan->budget_by} ", 
                    'remark' => "- จาก หมายเหตุ : {$data_old->remark} เป็น {$standardplan->remark} "
                ];
                $check_date_start = array_key_exists('plan_startdate', $data_changes);
                $check_date_end = array_key_exists('plan_enddate', $data_changes);
                $check_date = true;
                $update_detail = '';
                foreach($data_changes as $key=>$data_changes){
                    if(array_key_exists($key, $change_fields)){
                        if($check_date && in_array($key, ['plan_startdate', 'plan_enddate'])){
                            if($check_date_start && $check_date_end){
                                $update_detail .= $change_fields['plan_date1'];
                                $check_date = false;
                            }else if($check_date_start){
                                $update_detail .= $change_fields['plan_date2'];
                                $check_date = false;
                            }else if($check_date_end){
                                $update_detail .= $change_fields['plan_date3'];
                                $check_date = false;
                            }
                        }else{
                            $update_detail .= $change_fields[$key];
                        }
                    }
                }
                $data_log = [];
                $data_log['update_user']    = @$standardplan->UpdatedName; 
                $data_log['update_date']    = date('Y-m-d'); 
                $data_log['update_detail']  = trim(@$update_detail); 
                $data_log['update_status']  = @$standardplan->StatusName; 
                $log = TisiEstandardDraftPlanLog::where('plan_id', $id)->latest()->first();
                if(!empty($log)){
                    $log->update($data_log);
                }
            }

            return redirect('certify/standard-plans')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function set_date_end(Request $request)
    {   
         $period = $request->period;
         $plan_startdate =  !empty($request->plan_startdate) ? HP::convertDate($request->plan_startdate ,true) : '';   
         $plan_startdate =  HP::DatePlus($plan_startdate ,$period,'month');
         $plan_startdate =  !empty($plan_startdate) ? HP::revertDate($plan_startdate ,true) : '';   
        return response()->json([
                                   'date'      => $plan_startdate,
                               ]);
    }
    
    public static function get_projectid()
    {
            $today = date('Y-m-d');
            $dates = explode('-', $today);
        start:
             $projectids = SetStandards::whereYear('created_at',$dates[0])->whereNotNull('projectid')->pluck('projectid','projectid')->toArray();
             $list_code = [];
             if(count($projectids) > 0){
                foreach($projectids as $projectid ){
                    $new_run =  explode('-', $projectid);
                    if(count($new_run) == 3){
                        $list_code[$new_run[2]] = $new_run[2];
                    }
                }
             }
 
         
             if(count($list_code) > 0){
         
                 usort($list_code, function($x, $y) {
                     return $x > $y;
                 });
         
                 $last = end($list_code);
                 $number = ((int)$last  + 1);
         
             }else{
                 $number = '001';
             }
             $no =  str_pad($number, 3, '0', STR_PAD_LEFT);
             $running  = 'SET'.'-'.(date('y')+ 43).'-'.$no;//ยังไม่มีตั้งค่า
             $projectid = SetStandards::where('projectid',$running)->value('projectid');
        if(is_null($projectid)){
             return $running;
        }else{
            goto start;
        }
    }


}
