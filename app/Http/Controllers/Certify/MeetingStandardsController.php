<?php

namespace App\Http\Controllers\Certify;

use DB;
use HP;

use App\Http\Requests;
use App\CommitteeLists;
use App\CommitteeSpecial;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;

use  App\Mail\Certify\MeetingStandards;
use App\Models\Certify\MeetingStandard;
use Illuminate\Support\Facades\Mail;   
use App\Models\Certify\MeetingStandardRecord;
use App\Models\Certify\MeetingStandardExperts;
use App\Models\Certify\MeetingStandardProject;

use App\Mail\Certify\MeetingStandardsConclusion;
use App\Models\Certify\MeetingStandardCommitee; 
use App\Models\Certify\MeetingStandardRecordCost;
use App\Models\Certify\CertifySetstandardMeetingType;
use App\Models\Certify\MeetingStandardRecordExperts; 
use App\Models\Certify\CertifySetstandardMeetingRecordParticipant;

class MeetingStandardsController extends Controller
{

    private $attach_path;
    private $attach_path_record;
    private $mail_subject = 'ขอแจ้งนัดหมายการประชุมการกำหนดมาตรฐานการตรวจสอบและรับรอง';
    private $mail_subject_conclusion = 'แจ้งผลนัดหมายการประชุมการกำหนดมาตรฐานการตรวจสอบและรับรอง';

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/certify_setstandard_meeting';
        $this->attach_path_record = 'tis_attach/certify_setstandard_meeting_record';
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.meeting-standards.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

        $model = str_slug('meetingstandards', '-');
        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');
        $filter_meeting_type_id = $request->input('filter_meeting_type_id');

        $query = MeetingStandard::query()->when($not_admin, function ($query){
                                            return $query->where('created_by', auth()->user()->getKey());
                                        })
                                        ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where(function ($query2) use($search_full) {
                                            $query2->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                        });
                                        })
                                        ->when($filter_state, function ($query, $filter_state){
                                            $query->where('status_id', $filter_state);
                                        })
                                        ->when($filter_meeting_type_id, function ($query, $filter_meeting_type_id){
                                               $setstandard_meeting_ids =  CertifySetstandardMeetingType::where('meetingtype_id', $filter_meeting_type_id)->select('setstandard_meeting_id');
                                                $query->whereIn('id', $setstandard_meeting_ids);
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
                            ->addColumn('title', function ($item) {
                                return   !empty($item->title)? $item->title:'';
                            })
                            ->addColumn('meeting_type', function ($item) {
                                return   !empty($item->MeetingTypesName)?  implode(",<br/>",$item->MeetingTypesName)  :'';
                            })
                            ->addColumn('date_meet', function ($item) {
                                return   !empty($item->start_date)?HP::DateThaiFull($item->start_date):'';
                            })
                            ->addColumn('meeting_place', function ($item) {
                                return   !empty($item->meeting_place)? $item->meeting_place:'';
                            })
                            ->addColumn('status_id', function ($item) {
                                return   !empty($item->StatusText)? $item->StatusText:'';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                if($item->status_id == 2){
                                    $button = '';
                                    if (auth()->user()->can('view-' . $model)) {
                                        $button .=  '<a href="' . url('/certify/meeting-standards/' . $item->id) . '" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> </a>';
                                    }
                                    if (auth()->user()->can('edit-' . $model)) {
                                        $button .=  ' <span  class="btn btn-warning btn-xs" disabled><i class="fa fa-pencil-square-o"></i></span>';
                                    }
                                    return $button;
                                }else{
                                    return HP::buttonAction( $item->id, 'certify/meeting-standards','Certify\\MeetingStandardsController@destroy', 'meetingstandards',true,true,false);
                      
                                }
                            })
                            ->addColumn('action_meeting', function ($item) use($model) {
                                if($item->status_id == 2){
                                    return '<span  class="btn btn-primary btn-xs" disabled><i class="fa fa-desktop"></i> </span>';
                                }else{
                                    return   '<a href="' . url('/certify/meeting-standards/conclusion/' . $item->id) . '" class="btn btn-primary btn-xs"><i class="fa fa-desktop"></i> </a>';
                                }

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox','meeting_type', 'certificate_type', 'status','action','action_meeting'])
                            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('add-'.$model)) {
            $setstandard_meeting_types = [new CertifySetstandardMeetingType];
            return view('certify.meeting-standards.create', compact('setstandard_meeting_types'));
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
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('add-'.$model)) {


            $requestData = $request->all();

            $requestData['created_by']  =  auth()->user()->getKey();
            $requestData['status_id']   =  3;
            $requestData['start_date']  =  !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
            $requestData['end_date']    =  !empty($request->end_date)   ?  HP::convertDate($request->end_date,true)   : null;

        
            $meeting_standard  =  MeetingStandard::create($requestData);


            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            if(isset( $requestData['repeater-attach'] ) ){
                $attachs = $requestData['repeater-attach'];
                foreach( $attachs as $file ){

                    if( isset($file['file_meet']) && !empty($file['file_meet']) ){
                        HP::singleFileUpload(
                            $file['file_meet'],
                            $this->attach_path,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new MeetingStandard)->getTable() ),
                            $meeting_standard->id,
                            'file_meeting_standard',
                            !empty($file['file_desc'])?$file['file_desc']:null
                        );
                    }
                }
            }

            // คณะวิชาการกำหนด
                $this->save_committee($requestData['commitee_id'],$meeting_standard);
                
                
            // วาระการประชุม
                $this->save_term($requestData['detail'],$meeting_standard);

            
 
            return redirect('certify/meeting-standards')->with('flash_message', 'เรียบร้อยแล้ว');
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
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('view-'.$model)) {
      
            $meetingstandard  = MeetingStandard::findOrFail($id);
            $meetingstandard_commitees  = MeetingStandardCommitee::where('setstandard_meeting_id', $meetingstandard->id)->pluck('commitee_id');
            $setstandard_meeting_types = CertifySetstandardMeetingType::where('setstandard_meeting_id',$meetingstandard->id) ->groupBy('meetingtype_id')->orderby('id','asc')->get();
            if(count($setstandard_meeting_types) == 0){
                $setstandard_meeting_types = [new CertifySetstandardMeetingType];
            }
         
            return view('certify.meeting-standards.show', compact('meetingstandard',
                                                                  'setstandard_meeting_types',
                                                                  'meetingstandard_commitees'
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
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('edit-'.$model)) {

            $meetingstandard  = MeetingStandard::findOrFail($id);
            $meetingstandard_commitees  = MeetingStandardCommitee::where('setstandard_meeting_id', $meetingstandard->id)->pluck('commitee_id');
            $setstandard_meeting_types = CertifySetstandardMeetingType::where('setstandard_meeting_id',$meetingstandard->id) ->groupBy('meetingtype_id')->orderby('id','asc')->get();
            if(count($setstandard_meeting_types) == 0){
                $setstandard_meeting_types = [new CertifySetstandardMeetingType];
            }
         

            return view('certify.meeting-standards.edit', compact('meetingstandard',
                                                                  'setstandard_meeting_types',
                                                                  'meetingstandard_commitees'
                                                                ));
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
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->mail_subject = 'ขอแจ้งแก้ไขนัดหมายการประชุมการกำหนดมาตรฐานการตรวจสอบและรับรอง';
            $meetingstandard = MeetingStandard::findOrFail($id);

            $requestData = $request->all();
            $requestData['updated_by']  =  auth()->user()->getKey();
            $requestData['status_id']   =  3;
            $requestData['start_date']  =  !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
            $requestData['end_date']    =  !empty($request->end_date)   ?  HP::convertDate($request->end_date,true)   : null;
            $meetingstandard->update($requestData);

            // คณะวิชาการกำหนด
            $this->save_committee($requestData['commitee_id'],$meetingstandard);
        
           // วาระการประชุม
            $this->save_term($requestData['detail'],$meetingstandard);
 
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            
            if( !empty( $requestData['repeater-attach-old'] ) ){
                $key_name = 'repeater-attach-old';
                $repeater_file = $requestData[$key_name];

                foreach( $repeater_file as $key=>$file ){

                    if($request->hasFile("{$key_name}.{$key}.file_meet")){
                        HP::singleFileUpdate(
                            $request->file("{$key_name}.{$key}.file_meet"),
                            $request->input("{$key_name}.{$key}.old_id"),
                            $this->attach_path,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new MeetingStandard)->getTable() ),
                            $meetingstandard->id,
                            'file_meeting_standard',
                            $request->input("{$key_name}.{$key}.file_desc")
                        );
                    }

                }

            }

            if(isset( $requestData['repeater-attach'] ) ){
               $attachs = $requestData['repeater-attach'];
               foreach( $attachs as $file ){

                   if( isset($file['file_meet']) && !empty($file['file_meet']) ){
                       HP::singleFileUpload(
                           $file['file_meet'],
                           $this->attach_path,
                           ( $tax_number),
                           (auth()->user()->FullName ?? null),
                           'Center',
                           (  (new MeetingStandard)->getTable() ),
                           $meetingstandard->id,
                           'file_meeting_standard',
                           !empty($file['file_desc'])?$file['file_desc']:null
                       );
                   }
               }
           }

            return redirect('certify/meeting-standards')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function conclusion($id)
    {
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('edit-'.$model)) {

            $meetingstandard  = MeetingStandard::findOrFail($id);



            $setstandard_meeting_types = CertifySetstandardMeetingType::where('setstandard_meeting_id',$meetingstandard->id)->get();
            if(count($setstandard_meeting_types) == 0){
                $setstandard_meeting_types = [new CertifySetstandardMeetingType];
            }

      

            $meetingstandard_record = MeetingStandardRecord::where('setstandard_meeting_id',$meetingstandard->id)->first();
            if(!is_null($meetingstandard_record)){
                $record_participants  = CertifySetstandardMeetingRecordParticipant::where('meeting_record_id', $meetingstandard_record->id)->get();
                if(count($record_participants) == 0){
                    $record_participants = [new CertifySetstandardMeetingRecordParticipant];
                }
                $meeting_types = MeetingStandardRecordCost::whereNull('setstandard_id')->where('meeting_record_id',$meetingstandard_record->id)->get();
                if(count($meeting_types) == 0){
                    $meeting_types = [new MeetingStandardRecordCost];
                }
                $meetingstandard_commitees  = MeetingStandardRecordExperts::where('meeting_record_id', $meetingstandard_record->id)->get();
                if(count($meetingstandard_commitees) == 0){
                    $meetingstandard_commitees = [new MeetingStandardRecordExperts];
                }
            }else{
                $meetingstandard_record = new CertifySetstandardMeetingRecordParticipant;
                $record_participants = [new CertifySetstandardMeetingRecordParticipant];
                $meeting_types = [new MeetingStandardRecordCost];
                $meetingstandard_commitees = [new MeetingStandardRecordExperts];
            }
 
            
            return view('certify.meeting-standards.edit-conclusion', compact('meetingstandard',
                                                                            'setstandard_meeting_types',
                                                                            'meetingstandard_commitees',
                                                                            'record_participants',
                                                                            'meetingstandard_record',
                                                                            'meeting_types'
                                                                            ));
        }
        abort(403);
    }

    public function update_conclusion(Request $request, $id)
    {

        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('edit-'.$model)) {

            $meetingstandard = MeetingStandard::findOrFail($id);

             $requestData = $request->all();
            // $requestData['updated_by']  =  auth()->user()->getKey();
            // $requestData['start_date']  =  !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
            // $requestData['end_date']    =  !empty($request->end_date)   ?  HP::convertDate($request->end_date,true)   : null;
            $requestmeetingstandardData['status_id']   =  2;
            $meetingstandard->update($requestmeetingstandardData);

            $meetingstandard_record = MeetingStandardRecord::where('setstandard_meeting_id',$meetingstandard->id)->first();
            if(is_null($meetingstandard_record)){
                $meetingstandard_record = new  MeetingStandardRecord;
                $meetingstandard_record->created_by       =    auth()->user()->getKey();
            }else{
                $meetingstandard_record->updated_by       =    auth()->user()->getKey();
            }
            $meetingstandard_record->setstandard_meeting_id       =    !empty($meetingstandard->id) ?  $meetingstandard->id : null;
            $meetingstandard_record->status_id                    =   2;
            $meetingstandard_record->amount                       =    !empty($request->amount) ?   str_replace(",","",$request->amount): null;   
            $meetingstandard_record->start_date                   =    !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
            $meetingstandard_record->start_time                   =    !empty($request->end_date)   ?  HP::convertDate($request->end_date,true)   : null;
            $meetingstandard_record->end_date                     =    !empty($request->end_date) ?  $request->end_date : null;
            $meetingstandard_record->end_time                     =    !empty($request->end_time) ?  $request->end_time : null;
            $meetingstandard_record->meeting_detail               =    !empty($request->meeting_detail) ?  $request->meeting_detail : null;
            $meetingstandard_record->save();

               // คณะวิชาการกำหนด
               $this->save_commitees_record_update($requestData['commitees'],$meetingstandard_record);

               // ผู้เข้าร่วมประชุม
              $this->save_record_participant($requestData['participants'],$meetingstandard_record);
               
               // วาระการประชุม / ค่าใช้จ่าย
               $this->save_record_cost($requestData['meeting'],$meetingstandard_record);
               
               // ส่งอีเมล
               $this->sent_mail_conclusion($meetingstandard, $meetingstandard_record);

            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            if(isset( $requestData['repeater-attach'] ) ){
               $attachs = $requestData['repeater-attach'];
               foreach( $attachs as $file ){
                   if( isset($file['file_meet']) && !empty($file['file_meet']) ){
                       HP::singleFileUpload(
                           $file['file_meet'],
                           $this->attach_path_record,
                           ( $tax_number),
                           (auth()->user()->FullName ?? null),
                           'Center',
                           (  (new MeetingStandardRecord)->getTable() ),
                           $meetingstandard_record->id,
                           'file_meeting_standard',
                           !empty($file['file_desc'])?$file['file_desc']:null
                       );
                   }
               }
           }

           return redirect('certify/meeting-standards')->with('flash_message', 'เรียบร้อยแล้ว!');
           
            // return redirect('certify/meeting-standards/conclusion/'.$meetingstandard->id)->with('flash_message', 'เรียบร้อยแล้ว!');
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
        $model = str_slug('meetingstandards','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new MeetingStandard;
            MeetingStandard::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            MeetingStandard::destroy($id);
          }

          return redirect('certify/meeting-standards')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('meetingstandards','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new MeetingStandard;
          MeetingStandard::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/meeting-standards')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    private function save_expert($experts_id, $meeting_standard){
        if(!empty($experts_id) && count($experts_id) > 0){

            MeetingStandardExperts::where('setstandard_meeting_id', $meeting_standard->id)->delete();
            foreach($experts_id as $key => $item) {
                $input = [];
                $input['setstandard_meeting_id'] = $meeting_standard->id;
                $input['experts_id']     = $item;
                $input['created_by']     = auth()->user()->getKey();
                MeetingStandardExperts::create($input);
            }
        }
    }
    // คณะวิชาการกำหนด
    private function save_committee($commitee_id, $meeting_standard){
        if(!empty($commitee_id) && count($commitee_id) > 0){

            MeetingStandardCommitee::where('setstandard_meeting_id', $meeting_standard->id)->delete();
            foreach($commitee_id as $key => $item) {
                $input = [];
                $input['setstandard_meeting_id'] = $meeting_standard->id;
                $input['commitee_id']    = $item;
                $input['created_by']     = auth()->user()->getKey();
                $commitee =   MeetingStandardCommitee::create($input);

                $this->save_commitees_record_create($commitee,$meeting_standard);
 
             
            }
        }
    }

    // วาระการประชุม
    private function save_term($details, $meeting_standard){
        if(!empty($details) && count($details) > 0){
            $projectids  = (array)$details['projectid'];
            CertifySetstandardMeetingType::where('setstandard_meeting_id', $meeting_standard->id)->delete();
            foreach($details['meetingtype_id'] as $key => $item) {
                 $projectid =  array_key_exists($item,$projectids) ? $projectids[$item] : [] ; 
                 if(count($projectid) > 0){
                    foreach($projectid as $project ){
                            $input = [];
                            $input['setstandard_id']         = $project;
                            $input['setstandard_meeting_id'] = $meeting_standard->id;
                            $input['meetingtype_id']         = $item;
                            $input['created_by']             = auth()->user()->getKey();
                            CertifySetstandardMeetingType::create($input);
                    }
                 }

            }
        }
    }

    private function save_commitees_record_create($commitee, $meetingstandard){
         $commitee_lists =  CommitteeLists::where('committee_special_id', $commitee->commitee_id)->get();
        if(!empty($commitee_lists) && count($commitee_lists) > 0){
                $meetingstandard_record = MeetingStandardRecord::where('setstandard_meeting_id',$meetingstandard->id)->first();
             if(is_null($meetingstandard_record)){
                 $meetingstandard_record = new  MeetingStandardRecord;
                 $meetingstandard_record->created_by       =    auth()->user()->getKey();
             }else{
                 $meetingstandard_record->updated_by       =    auth()->user()->getKey();
             }
                $meetingstandard_record->setstandard_meeting_id       =    !empty($meetingstandard->id) ?  $meetingstandard->id : null;
                $meetingstandard_record->save();

                MeetingStandardRecordExperts::where('meeting_record_id', $meetingstandard_record->id)->delete();
             $emails = [];
            foreach($commitee_lists as $key => $item) {
                if(!is_null($item->register_expert_to)){
                    $register_expert =  $item->register_expert_to;
                    $input['meeting_record_id']   = $meetingstandard_record->id; 
                    $input['commitee_id']         = $commitee->commitee_id;  
                    $input['experts_id']          = $item->expert_id;
                    $input['created_by']          = auth()->user()->getKey();
                    MeetingStandardRecordExperts::create($input);
                    if( filter_var($register_expert->email, FILTER_VALIDATE_EMAIL) && !in_array($register_expert->email,$emails) ){
                        $emails[] = $register_expert->email;
                    }
                }
            }
          
            $committee_special =  CommitteeSpecial::where('id',$commitee->commitee_id)->first();
            if(count($emails) > 0 && !is_null($committee_special)){
                    //E-mail 
                    $this->set_mail($emails,$committee_special,$meetingstandard);
            }
        }
    }
    private function save_commitees_record_update($commitees, $record){
        if(!empty($commitees) && count($commitees) > 0){
            $participates   = (array)$commitees['participate'];
            $details        = (array)$commitees['detail'];
            foreach($commitees['ids'] as $key => $item) {
                $record_expert =  MeetingStandardRecordExperts::where('id',$item)->first();
                if(!is_null($record_expert)){
                    $input = [];
                    $input['participate']         =  array_key_exists($item,$participates) ? $participates[$item] : null;
                    $input['detail']              =  array_key_exists($item,$details) ? $details[$item] : null;
                    $input['updated_by']    = auth()->user()->getKey();
                    $record_expert->update($input);
               }
            }
        }
    }
    // ผู้เข้าร่วมประชุม
    private function save_record_participant($expense, $meeting_record){
        if(!empty($expense) && count($expense) > 0){
            $department_ids  =  (array)$expense['department_id'];
            CertifySetstandardMeetingRecordParticipant::where('meeting_record_id', $meeting_record->id)->delete();
            foreach($expense['name'] as $key => $item) {
                $input = [];
                $input['meeting_record_id'] = $meeting_record->id;
                $input['name']               = $item;
                $input['department_id']      =  array_key_exists($key,$department_ids) ? $department_ids[$key] : null;
                CertifySetstandardMeetingRecordParticipant::create($input);
            }
        }
    }

    // วาระการประชุม / ค่าใช้จ่าย
    private function save_record_cost($meeting, $meeting_record){
        if(!empty($meeting) && count($meeting) > 0){ 
            $status             =  (array)$meeting['status'];
            $costs              =  (array)$meeting['cost'];
            $setstandard_ids    =  (array)$meeting['setstandard_id'];
            $setstandard_title  =  (array)$meeting['setstandard_title'];
            $expense_other      =  (array)$meeting['expense_other'];
            MeetingStandardRecordCost::where('meeting_record_id', $meeting_record->id)->delete();
            foreach($meeting['cost_id'] as $key => $item) {
                $input = [];
                $input['meeting_record_id'] =   $meeting_record->id;
                $input['status']            =   array_key_exists($item,$status)?  $status[$item] : null;
                $input['setstandard_id']    =   array_key_exists($item,$setstandard_ids)?  $setstandard_ids[$item] : null;
                $input['cost']              =   array_key_exists($item,$costs) ?  str_replace(",","",$costs[$item]) : null;
                $input['created_by']         = auth()->user()->getKey();
                if( array_key_exists($item,$expense_other)){
                    $input['expense_other']    =   $expense_other[$item] ;
                }else   if( array_key_exists($item,$setstandard_title)){ 
                    $input['expense_other']    =   $setstandard_title[$item];
                }
                MeetingStandardRecordCost::create($input);
            }
        }
    }
     
    public function set_mail($emails,$committee_special,$meeting_standard) {
            // $config = HP::getConfig();
            // $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $data_app = [
                          'committee_special'      => $committee_special,
                          'meeting_standard'      => $meeting_standard,
                          'mail_subject'      => $this->mail_subject,
                       ];
            
            $html = new MeetingStandards($data_app);
            $mail =  Mail::to($emails)->send($html);
     
   }

    public function set_mail_conclusion($emails,$committee_special,$meeting_standard) {
            $data_app = [
                            'committee_special'      => $committee_special,
                            'meeting_standard'      => $meeting_standard,
                            'mail_subject'      => $this->mail_subject_conclusion,
                        ];
            
            $html = new MeetingStandardsConclusion($data_app);
            $mail =  Mail::to($emails)->send($html);
    }

    public function sent_mail_conclusion($meeting_standard, $meetingstandard_record) {
        $meeting_commitees = MeetingStandardCommitee::with(['committee'])->where('setstandard_meeting_id', $meeting_standard->id)->get();
        foreach($meeting_commitees as $commitee){
            $emails_arr =  MeetingStandardRecordExperts::where('meeting_record_id', $meetingstandard_record->id)->where('commitee_id', $commitee->commitee_id)->where('participate', 1)->get()->pluck('RegisterExpertEmail')->toArray();
            $emails = array_diff(filter_var_array($emails_arr, FILTER_VALIDATE_EMAIL), [false]);
            if(!empty($emails) && is_array($emails) && count($emails) > 0){
                $this->set_mail_conclusion($emails, $commitee->committee, $meeting_standard);
            }
        }
    }

   public function get_committee_lists(Request $request)
   {
       $id = $request->input('id');
       $commitee_lists =  CommitteeLists::whereIn('committee_special_id', $id)->get();
       $datas = [];
       $head_name = [];
       if(count($commitee_lists) > 0){
            foreach ($commitee_lists as $key => $item) { 
                if(!is_null($item->register_expert_to)){
                    $register_expert =  $item->register_expert_to;
                    if(!in_array($register_expert->head_name,$head_name)){
                        $result                     = (object)[];
                        $result->name               = $register_expert->head_name;
                        $result->committee_group    = !empty($register_expert->appoint_department_to->title) ? $register_expert->appoint_department_to->title : '';
                        $datas[]                    = $result;
                        $head_name[]                = $register_expert->head_name;
                    }
                   
                }
            }
       }

       return response()->json(['message'=> count($datas) > 0 ? true : false,'datas'=>$datas]);
   }
 

}
