<?php

namespace App\Http\Controllers\Certify;
use HP;
use App\CommitteeLists;
use App\CommitteeSpecial;
use Illuminate\Http\Request;
use App\MeetingLtTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Tis\EstandardOffers;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;
use Illuminate\Support\Facades\Mail;
use App\Mail\Certify\MeetingStandards;
use App\Models\Certify\MeetingStandard;
use App\Models\Tis\TisiEstandardDraftPlan;
use App\Models\Certify\MeetingStandardRecord;
use App\Models\Certify\MeetingStandardCommitee;
use App\Models\Certify\MeetingStandardRecordCost;
use App\Models\Certify\MeetingStandardRecordExperts;
use App\Models\Certify\CertifySetstandardMeetingType;
use App\Models\Certify\CertifySetstandardMeetingRecordParticipant;

class AppointedCommitteeLtController extends Controller
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


    public function index()
    {
       
        $model = str_slug('appointed-committee-lt','-');
        if(auth()->user()->can('view-'.$model)) {
            $meetingLtTransactions  = MeetingLtTransaction::paginate(10, ['*'], 'lt_page');


        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

    


        $meetingStdTransactions = MeetingStandard::query()->when($not_admin, function ($query){
                                            return $query->where('created_by', auth()->user()->getKey());
                                        })
                                        ->orderByDesc('id')
                                        ->paginate(10, ['*'], 'lt_page');
                                       

            return view('certify.meeting-standards.lt.index',[
                'meetingLtTransactions' => $meetingLtTransactions,
                 'meetingStdTransactions' => $meetingStdTransactions, 
            ]);
        }
        abort(403);

    }

    public function create()
    {
        // dd("ok");
       $draftPlans = TisiEstandardDraftPlan::where('status_id', 1)
            ->whereHas('estandard_offers_to', function ($query) {
                $query->whereNotNull('standard_name');
            })
            ->whereNull('approve')
            ->get();


        $model = str_slug('appointed-committee-lt','-');
        if(auth()->user()->can('add-'.$model)) {
            $setstandard_meeting_types = [new CertifySetstandardMeetingType];
            return view('certify.meeting-standards.lt.create',[
                'draftPlans' => $draftPlans,
                'setstandard_meeting_types' => $setstandard_meeting_types
                
            ]);
        }
        abort(403);

    }
    public function store(Request $request)
    {
        // dd($request->all());

        $model = str_slug('appointed-committee-lt', '-');
        if (auth()->user()->can('add-' . $model)) {

            $data = $request->only([
                'title',
                'start_date',
                'start_time',
                'end_date',
                'end_time',
                'meeting_place',
                'meeting_detail',
            ]);

            if($request->doc_type == 1)
            {
                $draftPlanIds = $request->input('draft_plan_id', []); // ['35']
                $meetingGroupData = collect($draftPlanIds)->map(function ($id) {
                    return [
                        'id' => $id,
                        'status' => null, // ค่าเริ่มต้นสำหรับ status
                        'note' => '',     // ค่าเริ่มต้นสำหรับ note
                    ];
                });

                $data['meeting_group'] = $meetingGroupData->toJson();
                $data['meeting_team_id'] = is_array($request->commitee_id) ? implode(',', $request->commitee_id) : $request->commitee_id;
                $data['status_id'] = 1; // สมมติว่าสถานะเริ่มต้นคือ 1
                $data['created_by'] = auth()->id();

                $data['start_date']  =  !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
                $data['end_date']    =  !empty($request->end_date)   ?  HP::convertDate($request->end_date,true)   : null;

                $meetingLtTransaction = MeetingLtTransaction::create($data);

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
                                (  (new MeetingLtTransaction)->getTable() ),
                                $meetingLtTransaction->id,
                                'file_meeting_lt_standard',
                                !empty($file['file_desc'])?$file['file_desc']:null
                            );
                        }
                    }
                }
            }else if($request->doc_type == 2)
            {

                // 
                
                $committeeId = $request->input('commitee_id'); // $committeeId มีค่าเป็น "7"

                // สร้าง array ใหม่โดยนำค่าเดิมมาเป็นสมาชิกตัวแรก
                $committeeIdAsArray = [$committeeId];
                // dd($committeeIdAsArray);
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
// dd("กำหนด");  
            // คณะวิชาการกำหนด
                $this->save_committee($committeeIdAsArray,$meeting_standard);
                
              
            // วาระการประชุม
                $this->save_term($requestData['detail'],$meeting_standard);



                $latestMeetingStandardCommittee = MeetingStandardCommitee::where('setstandard_meeting_id', $meeting_standard->id)
                    ->orderByDesc('id')
                    ->first();

                if ($latestMeetingStandardCommittee) {
                    $latestMeetingStandardCommittee->update([
                        'meeting_group' => 1
                    ]);
                }

                $latestMeetingType = CertifySetstandardMeetingType::where('setstandard_meeting_id', $meeting_standard->id)
                    ->orderByDesc('id')
                    ->first();

                if ($latestMeetingType) {
                    $latestMeetingType->update([
                        'meeting_group' => 1
                    ]);
                }
            }

            // dd("break");



            return redirect()->route('certify.meeting-standards.lt.index')->with('success', 'บันทึกข้อมูลการประชุมสำเร็จ');
        }
        
        abort(403);
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

    public function show($id)
    {
        
        $model = str_slug('appointed-committee-lt', '-');
        // ใช้ can() เพื่อตรวจสอบ permission 'edit'
        if (auth()->user()->can('edit-' . $model)) {

            // 1. ดึงข้อมูลการประชุมที่ต้องการแก้ไข ถ้าไม่เจอจะเกิด 404 Not Found
            $meetingstandard = MeetingLtTransaction::findOrFail($id);

            // 2. ดึงข้อมูลแผนร่างมาตรฐานทั้งหมดเพื่อใช้เป็น options ใน dropdown
            $draftPlans = TisiEstandardDraftPlan::where('status_id', 1)
                ->whereHas('estandard_offers_to', function ($query) {
                    $query->whereNotNull('standard_name');
                })
                ->get();

            $meetingstandard_commitees = !empty($meetingstandard->meeting_team_id) ? explode(',', $meetingstandard->meeting_team_id) : [];

            // แผนร่างมาตรฐานที่ถูกเลือก (จาก meeting_group ที่เป็น JSON)
            $selected_draft_plans_data = !empty($meetingstandard->meeting_group) ? json_decode($meetingstandard->meeting_group, true) : [];
            $selected_draft_plans = is_array($selected_draft_plans_data) ? array_column($selected_draft_plans_data, 'id') : [];

            $committeeLists = CommitteeSpecial::find($meetingstandard->meeting_team_id)->committeeLists;

            return view('certify.meeting-standards.lt.show', compact(
                'meetingstandard',
                'draftPlans',
                'meetingstandard_commitees',
                'selected_draft_plans',
                'committeeLists'
            ));
        }

        abort(403);
    }

    public function update(Request $request,$id)
    {
        // dd($request->all());
        $meeting = MeetingLtTransaction::findOrFail($id);
        $updatedItems = $request->input('items', []);
        $existingItems = json_decode($meeting->meeting_group, true);

        foreach ($existingItems as &$item) {
            
            // ตรวจสอบว่าในข้อมูลใหม่ที่ส่งมา มี ID ของรายการปัจจุบันอยู่หรือไม่
            if (isset($updatedItems[$item['id']])) {

               

                if($updatedItems[$item['id']]['status'] == 2){
                     
                    // dd($item['id'],$updatedItems[$item['id']]['status']);
                    $tisiEstandardDraftPlan = TisiEstandardDraftPlan::find($item['id']);
                    // dd($tisiEstandardDraftPlan );
                    EstandardOffers::find($tisiEstandardDraftPlan->offer_id)->update([
                        'state' => 3
                    ]);
                    $tisiEstandardDraftPlan->update([
                        'status_id' => 4
                    ]);

                }else if($updatedItems[$item['id']]['status'] == 1)
                {
                    TisiEstandardDraftPlan::find($item['id'])->update([
                        'approve' => 1
                    ]);
                }
                
                // ถ้ามี ให้อัปเดตค่า status และ note ของรายการปัจจุบันด้วยข้อมูลใหม่
                $item['status'] = $updatedItems[$item['id']]['status'];
                $item['note']   = $updatedItems[$item['id']]['note'];
            }
        }
        unset($item); // ยกเลิก reference เพื่อป้องกันการแก้ไขโดยไม่ตั้งใจในภายหลัง

        $meeting->meeting_group = json_encode($existingItems, JSON_UNESCAPED_UNICODE);
        $meeting->finish = 1;
        $meeting->budget = $request->budget;
        $meeting->save();

        return redirect()->route('certify.meeting-standards.lt.index')->with('success', 'บันทึกข้อมูลการประชุมสำเร็จ');
    }


public function getPlanList(Request $request)
{

        try {
            $draftPlans = TisiEstandardDraftPlan::with('estandard_offers_to')
                ->where('status_id', 1)
                ->whereHas('estandard_offers_to', function ($query) {
                    $query->whereNotNull('standard_name');
                })
                ->whereNull('approve')
                ->get();

            // ส่งข้อมูลกลับไปโดยไม่ผ่านการจัดรูปแบบ
            return response()->json($draftPlans);

        } catch (\Exception $e) {
            Log::error('Error fetching plan list: ' . $e->getMessage());
            return response()->json(['error' => 'An internal server error occurred.'], 500);
        }
  

}

// public function getStdList(Request $request)
// {


//     $standards = SetStandards::with('estandard_plan_to')
//                 ->whereHas('estandard_plan_to', function ($query) {
//                     $query->whereNotNull('approve');
//                 })
//                 ->where(function ($query) {
//                     $query->whereIn('status_id', [2, 3])
//                         ->orWhereIn('status_sub_appointment_id', [2, 3]);
//                 })
//                 ->doesntHave('standards')

//                 ->where('status_sub_appointment_id', 5)
//                 ->orderBy('id', 'desc')
//                 ->get();

//     return response()->json($standards);
    

// }

public function getStdList(Request $request)
{
    $standards = SetStandards::with('estandard_plan_to.estandard_offers_to')
    ->whereHas('estandard_plan_to', function ($query) {
        $query->whereNotNull('approve');
    })
    ->where(function ($query) {
        $query->whereIn('status_id', [2, 3])
              ->orWhereIn('status_sub_appointment_id', [2, 3]);
    })
    ->doesntHave('standards')
    
    ->where(function ($query) {
        // เงื่อนไข A
        $query->where('status_sub_appointment_id', 5)
              // เงื่อนไข B (ใช้ OR)
              ->orWhereHas('estandard_plan_to.estandard_offers_to', function ($subQuery) {
                  $subQuery->where('proposer_type', 'sdo_advanced');
              });
    })
    // -------------------------

    ->orderBy('id', 'desc')
    ->get();

    return response()->json($standards);
}

public function conclusion($id)
{
      // dd($id);
        $model = str_slug('meetingstandards','-');
        // if(auth()->user()->can('edit-'.$model)) {

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
 
            
            return view('certify.meeting-standards.lt.conclusion-std', compact('meetingstandard',
                                                                            'setstandard_meeting_types',
                                                                            'meetingstandard_commitees',
                                                                            'record_participants',
                                                                            'meetingstandard_record',
                                                                            'meeting_types'
                                                                            ));
}




}
