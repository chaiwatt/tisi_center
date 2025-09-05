<?php

namespace App\Http\Controllers\Certify;

use HP;
use App\AttachFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Certificate\MeetingInvitation;
use Illuminate\Support\Facades\Mail;   
use App\Certificate\LtMeetingInvitation;
use App\Mail\Certify\MeetingAppointment;
use App\Services\MeetingAppointmentCommitteePdf;


class AppointedCommitteeController extends Controller
{
    public function index()
    {


        $model = str_slug('appointed-committee','-');
        if(auth()->user()->can('view-'.$model)) {


                // $meetingInvitations = MeetingInvitation::whereHas('signer.user', function ($query) {
                //     $query->where('runrecno', auth()->user()->runrecno);
                // })
                // ->where('status', 2) // เพิ่มเงื่อนไข status = 2
                // ->with(['setStandards', 'signer.user'])->get();

                // 1. ดึงข้อมูลชุดแรก (LtMeetingInvitation)
        // สังเกตว่าเปลี่ยนชื่อตัวแปรเป็น $ltMeetingInvitations เพื่อไม่ให้ซ้ำกัน
        $ltMeetingInvitations = LtMeetingInvitation::whereHas('signer.user', function ($query) {
                $query->where('runrecno', auth()->user()->runrecno);
            })
            ->where('status', 2)
            ->with(['signer.user'])
            ->get();

        // 2. ดึงข้อมูลชุดที่สอง (MeetingInvitation)
        // เปลี่ยนชื่อตัวแปรเป็น $regularMeetingInvitations
        $regularMeetingInvitations = MeetingInvitation::whereHas('signer.user', function ($query) {
                $query->where('runrecno', auth()->user()->runrecno);
            })
            ->where('status', 2)
            ->with(['setStandards', 'signer.user'])
            ->get();

        // 3. รวม Collection ทั้งสองเข้าด้วยกัน
        // สามารถเรียงลำดับตามวันที่สร้างล่าสุดได้ด้วย sortByDesc
        $allMeetingInvitations = $ltMeetingInvitations->merge($regularMeetingInvitations)
                                                    ->sortByDesc('created_at');

            return view('certify.appointed-committee.index',[
                'allMeetingInvitations' => $allMeetingInvitations
            ]);
        }
        abort(403);

    }


    public function signDocument(Request $request)
    {

        // dd($request->all());
       $meetingInvitation = MeetingInvitation::find($request->id);



       $setStandards = $meetingInvitation->setStandards;

      

       foreach($setStandards as $setStandard)
       {
            if($setStandard->status_id < 2){
                $setStandard->update([
                    'status_id' => 2
                ]);
            }

            if($setStandard->status_sub_appointment_id < 2){
                $setStandard->update([
                    'status_sub_appointment_id' => 2
                ]);
            }
            
       }

        $pdfService = new MeetingAppointmentCommitteePdf($request->id);
        $pdfContent = $pdfService->generateMeetingAppointmentCommitteePdf();
        
       // ดึง expert_name และ department_name จาก committeeLists
        $experts = $meetingInvitation->committeeSpecials->flatMap->committeeLists->map(function ($committeeList) {
            return [
                'expert_name' => $committeeList->expert_name,
                'department_name' => $committeeList->department_name,
                'email' => optional($committeeList->register_expert_to)->email

            ];
        });

        $emails = $experts->pluck('email')->unique()->toArray();

        $attachFile = AttachFile::where('ref_table', 'meeting_invitations')
            ->where('ref_id', $meetingInvitation->id)
            ->where('section', 'order_book')
            ->latest() // เพิ่มบรรทัดนี้เพื่อเรียงจากล่าสุด
            ->first();


        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
        $order_book_url = $url . 'funtions/get-view/' . $attachFile->url . '/' .$attachFile->filename ;

        $data_app = [ 
                        'mail_header'      => $meetingInvitation->attachments,
                        'mail_subject'      => $meetingInvitation->subject,
                        'mail_body'      => $meetingInvitation->details,
                        'qr_url'      => $meetingInvitation->details,
                        'order_book_url'      => $order_book_url,
                    ];

        $html = new MeetingAppointment($data_app);
        $mail =  Mail::to($emails)->send($html);

        $meetingInvitation = MeetingInvitation::with([
            'setStandards',
            'signer.user',
            'committeeSpecials.committeeLists' // โหลด committeeSpecials และ committeeLists
        ])->findOrFail($request->id);

         MeetingInvitation::find($request->id)->update([
            'status' => 3
         ]);

    }

}
