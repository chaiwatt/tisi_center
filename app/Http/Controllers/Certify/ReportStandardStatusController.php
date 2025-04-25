<?php

namespace App\Http\Controllers\Certify;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Certify\SetStandards;
use App\Models\Certify\SetStandardExperts;
use App\Models\Certify\SetStandardSummeetings;

use App\Models\Certify\MeetingStandard;
use App\Models\Certify\MeetingStandardProject;
use App\Models\Certify\MeetingStandardExperts;
use App\Models\Certify\MeetingStandardRecord;
use App\Models\Certify\MeetingStandardRecordCost;

use App\Models\Tis\TisiEstandardDraftPlan;
use App\Models\Tis\TisiEstandardDraft;

use Yajra\Datatables\Datatables;
use HP;
use DB; 

use Illuminate\Http\Request;

class ReportStandardStatusController extends Controller
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
        $model = str_slug('report-standard-status','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.report-standard-status.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $model = str_slug('report-standard-status', '-');
        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');
        
        $query = SetStandards::query()
                                        ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search ); 
                                            $query->where(function ($query2) use($search_full) { 
                                            $query2->Where(DB::raw("REPLACE(sign_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(sign_position,' ','')"), 'LIKE', "%".$search_full."%") ;
                                        });
                                        }) 
                                        ->when($filter_method_id, function ($query, $filter_method_id){
                                            $query->where('method_id', $filter_method_id);
                                        })
                                        ->when($filter_standard_type, function ($query, $filter_standard_type){
                                            $draft_plan = TisiEstandardDraftPlan::select('id')->where('std_type', $filter_standard_type);
                                            $query->whereIn('plan_id', $draft_plan);
                                         })
                                        ->when($filter_year, function ($query, $filter_year){
                                            $draft = TisiEstandardDraft::select('id')->where('draft_year', $filter_year);
                                            $draft_plan = TisiEstandardDraftPlan::select('id')->whereIn('draft_id', $draft);
                                            $query->whereIn('plan_id', $draft_plan);
                                         })
                                         ->when($filter_status, function ($query, $filter_status){
                                               $query->where('status_id', $filter_status);
                                         });
                                      
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                if($item->state == 99){
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">'; 
                                }else{
                                    return '';
                                }
                            })
                            ->addColumn('certificate_type', function ($item) {
                                return   !empty($item->CertificateTypeTitle)? $item->CertificateTypeTitle:'';   
                            })
                            ->addColumn('projectid', function ($item) {
                                return   !empty($item->projectid)? $item->projectid:'';   
                            })
                            ->addColumn('tis_name', function ($item) {
                                return   !empty($item->TisName)? $item->TisName:'';   
                            })
                            ->addColumn('std_type', function ($item) {
                                return   !empty($item->StdTypeName)? $item->StdTypeName:'';   
                            })
                            ->addColumn('method_id', function ($item) {
                                return   !empty($item->MetThodName)? $item->MetThodName:'';   
                            })
                            ->addColumn('tis_year', function ($item) {
                                return   !empty($item->TisYear) ? $item->TisYear : '';   
                            })
                            ->addColumn('period', function ($item) {
                                return   !empty($item->Period) ? $item->Period.' เดือน' : '';   
                            })
                            ->addColumn('cost', function ($item) {
                                $cost_all_sum = null;
                                $meetingstandard_record_costs  = MeetingStandardRecordCost::where('setstandard_id',$item->id)->select('meeting_record_id');
                                if(!empty($meetingstandard_record_costs)){           
                                    $cost_all_sum  = MeetingStandardRecordCost::whereIn('meeting_record_id',$meetingstandard_record_costs)->sum('cost');
    
                                }
                                return  !empty($cost_all_sum)? number_format($cost_all_sum,2).' บาท':null;   

                            })
                            ->addColumn('action', function ($item) use($model) {
                                if($item->state == 99){
                                    return HP::buttonAction( $item->id, 'certify/set-standards','Certify\\SetStandardsController@destroy', 'setstandard',true,true,true);
                                }else{
                                    return HP::buttonAction( $item->id, 'certify/set-standards','Certify\\SetStandardsController@destroy', 'setstandard',true,true,false);
                                }
                              
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'certificate_type', 'status','action'])
                            ->make(true);
    } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('reportstandardstatus.reportstandardstatus.create');
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
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            
            reportstandardstatus::create($requestData);
            return redirect('reportstandardstatus/reportstandardstatus')->with('flash_message', 'เพิ่ม reportstandardstatus เรียบร้อยแล้ว');
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
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('view-'.$model)) {
            $reportstandardstatus = reportstandardstatus::findOrFail($id);
            return view('reportstandardstatus.reportstandardstatus.show', compact('reportstandardstatus'));
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
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('edit-'.$model)) {
            $reportstandardstatus = reportstandardstatus::findOrFail($id);
            return view('reportstandardstatus.reportstandardstatus.edit', compact('reportstandardstatus'));
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
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $reportstandardstatus = reportstandardstatus::findOrFail($id);
            $reportstandardstatus->update($requestData);

            return redirect('reportstandardstatus/reportstandardstatus')->with('flash_message', 'แก้ไข reportstandardstatus เรียบร้อยแล้ว!');
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
        $model = str_slug('reportstandardstatus','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new reportstandardstatus;
            reportstandardstatus::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            reportstandardstatus::destroy($id);
          }

          return redirect('reportstandardstatus/reportstandardstatus')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('reportstandardstatus','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new reportstandardstatus;
          reportstandardstatus::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('reportstandardstatus/reportstandardstatus')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
