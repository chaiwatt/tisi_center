<?php

namespace App\Http\Controllers\Certify;
use HP;
use App\CommitteeSpecial;
use Illuminate\Http\Request;
use App\MeetingLtTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Tis\EstandardOffers;
use App\Http\Controllers\Controller;
use App\Models\Tis\TisiEstandardDraftPlan;

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
            $meetingLtTransactions = MeetingLtTransaction::all();
            return view('certify.meeting-standards.lt.index',[
                'meetingLtTransactions' => $meetingLtTransactions
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
            return view('certify.meeting-standards.lt.create',[
                'draftPlans' => $draftPlans,
                
            ]);
        }
        abort(403);

    }
    public function store(Request $request)
    {
        
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

            return redirect()->route('certify.meeting-standards.lt.index')->with('success', 'บันทึกข้อมูลการประชุมสำเร็จ');
        }
        
        abort(403);
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


}
