<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Certify\Standard;
use App\Models\Certify\SetStandards;
use App\Models\Certify\SetStandardExperts;
use App\Models\Certify\SetStandardCommitee;
use App\Models\Certify\SetStandardSummeetings;

use App\Models\Certify\MeetingStandard;
use App\Models\Certify\MeetingStandardProject;
use App\Models\Certify\MeetingStandardExperts;
use App\Models\Certify\MeetingStandardRecord;
use App\Models\Certify\MeetingStandardRecordCost;

use App\Models\Tis\TisiEstandardDraftPlan;
use App\Models\Tis\TisiEstandardDraft;
use App\Models\Tis\TisiEstandardDraftPlanHistorys;
use Yajra\Datatables\Datatables;
use HP;
use DB;

class SetStandardsController extends Controller
{

    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/certify_setstandard_summeetings/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */


    public function index(Request $request)
    {
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.set-standards.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

        $model = str_slug('setstandard', '-');
        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');

        $query = SetStandards::query()->with(
                                        [
                                            'estandard_plan_to'
                                        ])
                                        ->when($not_admin, function ($query){
                                            return $query->where(function ($query){
                                                return $query->whereHas('estandard_plan_to', function($query){
                                                                return $query->where('assign_id', auth()->user()->getKey());
                                                            })
                                                            ->orWhereHas('estandard_plan_to.tisi_estandard_draft_to', function($query){
                                                                return $query->where('created_by', auth()->user()->getKey());
                                                            });
                                            });
                                        })
                                        ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where(function ($query2) use($search_full) {
                                                $query2->whereHas('estandard_plan_to', function ($query) use($search_full) {
                                                    return $query->where(DB::raw("REPLACE(tis_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                })
                                                ->OrWhere(DB::raw("REPLACE(projectid,' ','')"), 'LIKE', "%".$search_full."%");
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
                                            
                                                if($filter_status == '-1'){
                                                    $query->where('status_id', 0);
                                                }else{
                                                    $query->where('status_id', $filter_status);
                                                }
                                            
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
                                return   !empty($item->projectid)? $item->projectid:'อยู่ระหว่างกำหนดมาตรฐาน';
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
                            ->addColumn('status', function ($item) {
                                return   !empty($item->StatusText) ? $item->StatusText : '';
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
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify.set-standards.create');
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
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('add-'.$model)) {
            $requestData = $request->all();

            $rid  =  SetStandards::orderBy('id', 'desc')->first();
            $projectid  = 'SET'.'-'.(date('y')+ 43).'-'.sprintf("%03d",(($rid->id ?? 0)+1));//ยังไม่มีตั้งค่า
            $requestData['created_by']  =  auth()->user()->getKey();
            $requestData['projectid']   =  $projectid;
            $requestData['status_id']   =  1;
            $requestData['estimate_cost']  = !empty(str_replace(",","", $requestData['estimate_cost']))?str_replace(",","",$requestData['estimate_cost']):null;

            $set_standards              =  SetStandards::create($requestData);

            $commitee_id = $requestData['commitee_id'];
            if(!empty($commitee_id) && count($commitee_id) > 0){
                $this->save_commitee($commitee_id, $set_standards);
            }

            return redirect('certify/set-standards')->with('flash_message', 'เพิ่มมาตรฐานการตรวจสอบและรับรองเรียบร้อยแล้ว');
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
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('view-'.$model)) {
            $setstandard = SetStandards::findOrFail($id);
            $standardplan           =  $setstandard->estandard_plan_to;
            $setstandard_commitees  = SetStandardCommitee::where('setstandard_id', $setstandard->id)->select('commitee_id')->get();
            $setstandard_summeeting = SetStandardSummeetings::where('setstandard_id', $setstandard->id)->first();
            
            $meetingstandards = [];

            $certify_setstandard_meeting_type = $setstandard->certify_setstandard_meeting_type_many;

            if($certify_setstandard_meeting_type->count() > 0){
                $meetingstandards = MeetingStandardRecordCost::whereIn('setstandard_id', $certify_setstandard_meeting_type->pluck('setstandard_id'))->get();
            }
            return view('certify.set-standards.show', compact('setstandard',
                                                            'setstandard_commitees',
                                                            'setstandard_summeeting',
                                                            'meetingstandards',
                                                            'standardplan'
                                                      ));
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
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('edit-'.$model)) {

            $setstandard            = SetStandards::findOrFail($id);
            $setstandard->condition =  'form_edit';

            $standardplan           =  $setstandard->estandard_plan_to;
            $setstandard_commitees  = SetStandardCommitee::where('setstandard_id', $setstandard->id)->select('commitee_id')->get();
            $setstandard_summeeting = SetStandardSummeetings::where('setstandard_id', $setstandard->id)->first();
            
            $meetingstandards = collect();

            $certify_setstandard_meeting_type = $setstandard->certify_setstandard_meeting_type_many;

            if($certify_setstandard_meeting_type->count() > 0){
                $meetingstandards = MeetingStandardRecordCost::whereIn('setstandard_id', $certify_setstandard_meeting_type->pluck('setstandard_id'))->get();
            }
      

            return view('certify.set-standards.edit', compact('setstandard',
                                                              'setstandard_commitees',
                                                              'setstandard_summeeting',
                                                              'meetingstandards',
                                                              'standardplan'
                                                            ));
        }

        about('403');

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
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('edit-'.$model)) {

          
            $requestData = $request->all();

            $set_standards = SetStandards::findOrFail($id);
            if(is_null($set_standards->projectid)){
                $requestData['projectid']   =  self::get_projectid();
                $requestData['created_by']  =  auth()->user()->getKey();
            }

            if($request->step_state == 1){
                if($request->has('is_save')){
                    $requestData['status_id']   =  1;
                }
            }else  if($request->step_state == 2){
                if($request->has('is_save')){
                    $requestData['status_id']   =  2;
                }
            }else  if($request->step_state == 3){
                if($request->has('is_save')){
                    $requestData['status_id']   =  4;
                }
            }else  if($request->step_state == 4){
                if($request->has('is_save')){
                    $requestData['status_id']   =  5;
                }

                $summeetings = SetStandardSummeetings::where('setstandard_id', $set_standards->id)->first();
                if(is_null($summeetings)){
                    $summeetings = new  SetStandardSummeetings;
                }
                $summeetings->setstandard_id    = $set_standards->id;
                $summeetings->amount_sum        =  !empty($request->amount_sum)   ? str_replace(",","",$request->amount_sum)   : null ;
                $summeetings->cost_sum          =  !empty($request->cost_sum)   ? str_replace(",","",$request->cost_sum) : null ;
                $summeetings->detail            =  !empty($request->detail)   ? $request->detail : null ;
                $summeetings->save();
                $this->save_standard($set_standards);     
            }

            $draft_plan = TisiEstandardDraftPlan::findOrFail($set_standards->plan_id);
            if(!is_null($draft_plan)){
              $state          =  TisiEstandardDraftPlanHistorys::select(DB::raw('count(*) as state_count, state'))->where('draft_plan_id',$draft_plan->id)->groupBy('state')->get()->count();
              $draft_plan_array = $draft_plan->toArray();
              $draft_plan_list = [];
              $draft_plan_list['std_type']          = !empty($request->std_type) ?  $request->std_type : @$draft_plan->std_type;
              $draft_plan_list['start_std']         = !empty($request->start_std) ?  $request->start_std : @$draft_plan->start_std;
              $draft_plan_list['tis_number']        = !empty($request->tis_number) ?  $request->tis_number : @$draft_plan->tis_number;
              $draft_plan_list['tis_book']          = !empty($request->tis_book) ?  $request->tis_book : @$draft_plan->tis_book;
              $draft_plan_list['tis_year']          = !empty($request->tis_year) ?  $request->tis_year : @$draft_plan->tis_year;
              $draft_plan_list['tis_name']          = !empty($request->tis_name) ?  $request->tis_name : @$draft_plan->tis_name;
              $draft_plan_list['tis_name_eng']      = !empty($request->tis_name_eng) ?  $request->tis_name_eng : @$draft_plan->tis_name_eng;
              $draft_plan_list['ref_document']      = !empty($request->ref_document) ?  $request->ref_document : @$draft_plan->ref_document;
              $draft_plan_list['reason']            = !empty($request->reason) ?  $request->reason : @$draft_plan->reason;
              $draft_plan_list['confirm_time']      = !empty($request->confirm_time) ?  $request->confirm_time : @$draft_plan->confirm_time;
              $draft_plan_list['industry_target']   = !empty($request->industry_target) ?  $request->industry_target : @$draft_plan->industry_target;
              $draft_plan_list['plan_startdate']    = !empty($request->plan_startdate) ?  HP::convertDate($request->plan_startdate, true) : @$draft_plan->plan_startdate;
              $draft_plan_list['plan_enddate']      = !empty($request->plan_enddate) ?  HP::convertDate($request->plan_enddate, true) : @$draft_plan->plan_enddate;
              $draft_plan_list['period']            = !empty($request->period) ?  (int)$request->period : @$draft_plan->period;
              $draft_plan_list['budget']            = !empty($request->budget) ?  str_replace(',', '', $request->budget) : @$draft_plan->budget;
              $state = ($state+1);
              //เก็บ Log
              foreach ($draft_plan_list as $key => $value) {
                  if(array_key_exists($key, $draft_plan_array) ){
                      if($draft_plan_array[$key]!=$value){
                          TisiEstandardDraftPlanHistorys::Add($draft_plan->id,
                                                              $key,
                                                              $draft_plan_array[$key],
                                                              $value,
                                                              $state
                                                              );
                      }
                  }
              }

            //   $draft_plan_data   = [];
            //   $draft_plan_data['tis_number']   = !empty($request->tis_number) ?  $request->tis_number : @$draft_plan->tis_number ;
            //   $draft_plan_data['tis_book']     = !empty($request->tis_book) ?  $request->tis_book : @$draft_plan->tis_book ;
            //   $draft_plan_data['tis_year']     = !empty($request->tis_year) ?  $request->tis_year : @$draft_plan->tis_year ;
              $draft_plan->update($draft_plan_list);
            }

            $set_standards->update($requestData);
  
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $projectid =  !empty($set_standards->projectid) ?  str_replace("-","",  $set_standards->projectid  ): '0000000000000';
            
              // เอกสารที่เกี่ยวข้อง step ที่ 1
            if($set_standards->status_id == 1  &&   isset( $requestData['repeater-attach_step1'] ) ){
                $attach_step1 = $requestData['repeater-attach_step1'];

                foreach( $attach_step1 as $file ){
    
                    if( isset($file['attach_step1']) && !empty($file['attach_step1']) ){
                        HP::singleFileUpload(
                            $file['attach_step1'],
                            $this->attach_path.$projectid,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new SetStandards)->getTable() ),
                            $set_standards->id,
                            'file_set_standards_details',
                            !empty($file['file_attach_step1_documents'])?$file['file_attach_step1_documents']:null
                        );
                    }
                }
            }
    

         // เอกสารที่เกี่ยวข้อง step ที่ 4
            if($set_standards->status_id == 5  &&   isset( $requestData['repeater-attach_step4'] ) ){
                $attach_step4 = $requestData['repeater-attach_step4'];

                foreach( $attach_step4 as $file ){

                    if( isset($file['attach_step4']) && !empty($file['attach_step4']) ){
                        HP::singleFileUpload(
                            $file['attach_step4'],
                            $this->attach_path.$projectid,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new SetStandards)->getTable() ),
                            $set_standards->id,
                            'file_set_standards',
                            !empty($file['file_attach_step4_documents'])?$file['file_attach_step4_documents']:null
                        );
                    }
                }
            }
            
     
         

            // $this->save_commitee($requestData['commitee_id'], $set_standards);

            // if(!empty($requestData['amount_sum'])){

            //     $set_standards_summeeting = SetStandardSummeetings::where('setstandard_id', $id)->first();

            //     if(!is_null($set_standards_summeeting)){
            //         $set_standards_summeeting->update($requestData);

            //     }else{
            //         $requestData['setstandard_id'] = $id;
            //         $requestData['created_by'] = auth()->user()->runrecno;
            //         $set_standards_summeeting = SetStandardSummeetings::create($requestData);

            //     }

            //     $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            //     if ($request->file && $request->hasFile('file')){
            //         foreach($request->file as $key => $item) {
            //             HP::singleFileUpload(
            //                 $item,
            //                 $this->attach_path,
            //                 ($tax_number),
            //                 (auth()->user()->FullName ?? null),
            //                 'Center',
            //                 ((new SetStandardSummeetings)->getTable()),
            //                 $set_standards_summeeting->id,
            //                 'file_set_standards_summeeting',
            //                 @$request->file_desc[$key] ?? null
            //             );
            //         }

            //     }

            // }


            return redirect('certify/set-standards/'.$id.'/edit')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
        abort(403);

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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('setstandard','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new SetStandards;
            SetStandards::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            SetStandards::destroy($id);
          }

          return redirect('certify/set-standards')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('setstandard','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new SetStandards;
          SetStandards::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/set-standards')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function GetEstandardPlan($plan_id){

        $plan_data  =  DB::table((new TisiEstandardDraftPlan)->getTable().' AS plan') // อำเภอ
                    ->where(function($query) use($plan_id){
                        $query->where('plan.id', $plan_id);
                    })->first();

        return response()->json($plan_data);
    }

    //ไม่ได้ใช้
    private function save_expert($experts_id, $set_standards){
        if(!empty($experts_id) && count($experts_id) > 0){

            SetStandardExperts::where('setstandard_id', $set_standards->id)->delete();
            foreach($experts_id as $key => $item) {
                $input = [];
                $input['setstandard_id'] = $set_standards->id;
                $input['experts_id']     = $item;
                $input['created_by']     = auth()->user()->getKey();
                SetStandardExperts::create($input);
            }

         }
      }

    private function save_commitee($commitee_id, $set_standards){
        if(!empty($commitee_id) && count($commitee_id) > 0){

            SetStandardCommitee::where('setstandard_id', $set_standards->id)->delete();
            foreach($commitee_id as $key => $item) {
                $input = [];
                $input['setstandard_id'] = $set_standards->id;
                $input['commitee_id']    = $item;
                $input['created_by']     = auth()->user()->getKey();
                SetStandardCommitee::create($input);
            }

        }
    }

    private function save_standard($set_standards){
                $standard = Standard::where('setstandard_id',$set_standards->id)->first();
                if(is_null($standard)){
                    $standard = new Standard;
                }
                $standard->setstandard_id = $set_standards->id;
            if(!is_null($set_standards->estandard_plan_to)){
                $standardplan                =  $set_standards->estandard_plan_to;
                $standard->std_type          = !empty($standardplan->std_type) ? $standardplan->std_type : null;
                $standard->format_id         = !empty($standardplan->start_std) ? $standardplan->start_std : null;
                $standard->standard_id       = !empty($standardplan->ref_std) ? $standardplan->ref_std : null;
                $standard->std_no            = !empty($standardplan->tis_number) ? $standardplan->tis_number : null;
                $standard->std_book          = !empty($standardplan->tis_book) ? $standardplan->tis_book : null;
                $standard->std_year          = !empty($standardplan->tis_year) ? $standardplan->tis_year : null;
                $standard->std_title         = !empty($standardplan->tis_name) ? $standardplan->tis_name : null;
                $standard->std_title_en      = !empty($standardplan->ref_document) ? $standardplan->ref_document : null;
                $standard->method_id         = !empty($standardplan->method_id) ? $standardplan->method_id : null;
                $standard->ref_document      = !empty($standardplan->ref_document) ? $standardplan->method_id : null;
                $standard->reason            = !empty($standardplan->reason_to->title) ? $standardplan->reason_to->title : null;
                $standard->confirm_time      = !empty($standardplan->confirm_time) ? $standardplan->confirm_time : null;
                $standard->industry_target   = !empty($standardplan->industry_target) ? $standardplan->industry_target : null;
                $standard->industry_target   = !empty($standardplan->industry_target) ? $standardplan->industry_target : null;
                $standard->status_id         =  4;  // ขั้นตอนการดำเนินงาน : อยู่ระหว่างจัดทำมาตรฐานการรับรอง
                $standard->publish_state     =  1;  // สถานะการเผยแพร่ : รอเผยแพร่
                $standard->created_by        =  auth()->user()->getKey();
            }
                $standard->save(); 
    }

}
