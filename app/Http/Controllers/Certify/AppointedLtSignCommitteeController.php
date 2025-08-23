<?php

namespace App\Http\Controllers\Certify;

use HP;
use App\AttachFile;
use App\CommitteeSpecial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use App\Certificate\LtMeetingInvitation;
use App\Mail\Certify\MeetingAppointment;
use App\Services\MeetingAppointmentLtCommitteePdf;


class AppointedLtSignCommitteeController extends Controller
{
     public function index()
    {
        // dd('ok');

        $model = str_slug('appointed-committee-sign-lt','-');
        if(auth()->user()->can('view-'.$model)) {
            // $meetingInvitations = MeetingInvitation::whereHas('setStandards', function ($query) {
            //        $query->where('status_id', 0)
            //         ->orWhere('status_sub_appointment_id', 0);
            //     })
            //     ->whereHas('signer.user', function ($query) {
            //         $query->where('runrecno', auth()->user()->runrecno);
            //     })
            //     ->where('status', 2) // เพิ่มเงื่อนไข status = 2
            //     ->with(['setStandards', 'signer.user'])->get();

                $meetingInvitations = LtMeetingInvitation::whereHas('signer.user', function ($query) {
                    $query->where('runrecno', auth()->user()->runrecno);
                })
                ->where('status', 2) // เพิ่มเงื่อนไข status = 2
                ->with(['signer.user'])->get();

                // dd( $meetingInvitations);
                

            return view('certify.appointed-lt-sign-committee.index',[
                'meetingInvitations' => $meetingInvitations
            ]);
        }
        abort(403);

    }

    public function signDocument(Request $request)
    {
       $meetingInvitation = LtMeetingInvitation::find($request->id);
       
        $pdfService = new MeetingAppointmentLtCommitteePdf($request->id);
        $pdfContent = $pdfService->generateMeetingAppointmentLtCommitteePdf();


        // $boardIds = [];
        // if (!empty($meetingInvitation->board_json)) {
        //     $boardIds = $meetingInvitation->board_json;
        // }


        $boardIds = [];
        if (!empty($meetingInvitation->board_json)) {

            $decodedIds = json_decode($meetingInvitation->board_json);


            if (is_array($decodedIds)) {
                $boardIds = array_map('intval', $decodedIds);
            }
        }

      
        $committeeSpecials = CommitteeSpecial::whereIn('id', $boardIds)->get();

        $emails = $committeeSpecials->pluck('get_user_to.reg_email');

        // dd($emails);

        // $emails = $experts->pluck('email')->unique()->toArray();

        $attachFile = AttachFile::where('ref_table', 'lt_meeting_invitations')
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

         LtMeetingInvitation::find($request->id)->update([
            'status' => 3
         ]);

    }
}
