<?php

namespace App\Http\Controllers\Certify;

use HP;
use App\AttachFile;
use App\CommitteeSpecial;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;
use App\Certificate\MeetingInvitation;
use App\Certificate\LtMeetingInvitation;
use App\Models\Tis\TisiEstandardDraftPlan;

class AppointedLtCommitteeController extends Controller
{
    public function index(Request $request)
    {
        
        $model = str_slug('appointed-academic-sub-committee','-');
        if(auth()->user()->can('view-'.$model)) {

            // $meetingInvitations = LtMeetingInvitation::whereIn('status',[1,2,3])
            //     ->orderBy('id','desc')
            //     ->paginate(10);


                   // --- ข้อมูลสำหรับแท็บที่ 1 ---
    $ltMeetingInvitations = LtMeetingInvitation::whereIn('status', [1, 2, 3])
        ->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'lt_page'); // ตั้งชื่อ page parameter

    // --- ข้อมูลสำหรับแท็บที่ 2 ---
    $meetingInvitations = MeetingInvitation::whereIn('status', [1, 2, 3])
        ->where('type',"1")
        ->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'std_page'); // ตั้งชื่อ page parameter ให้ต่างกัน

    // ส่งตัวแปรทั้ง 2 ชุดไปที่ view
    return view('certify.appointed-lt-committee.index', [
        'ltMeetingInvitations' => $ltMeetingInvitations,
        'meetingInvitations' => $meetingInvitations,
    ]);


            // return view('certify.appointed-lt-committee.index',[
            //     'meetingInvitations' => $meetingInvitations
            // ]);
        }
        abort(403);
    }

     public function create()
    {
        // dd('ok');
        $model = str_slug('appointed-academic-sub-committee','-');
        $singers = Signer::all();

        $tisiEstandardDraftPlans = TisiEstandardDraftPlan::where('status_id', 1)
            ->whereHas('estandard_offers_to', function ($query) {
                $query->whereNotNull('standard_name');
            })
            ->get();

        if(auth()->user()->can('add-'.$model)) {
            return view('certify.appointed-lt-committee.create',[
                'signers' =>  $singers,
                'tisiEstandardDraftPlans' =>  $tisiEstandardDraftPlans,
            ]);
            }
        abort(403);
    }

    
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'header' => 'required|string|max:255', // ต้องระบุและไม่เกิน 255 ตัวอักษร
            'order_date' => 'required|string', // ต้องระบุและเป็นวันที่ที่ถูกต้อง
            'title' => 'required|string|max:500', // ต้องระบุและไม่เกิน 500 ตัวอักษร
            'attachment_text' => 'required|string', // ต้องระบุและเป็นข้อความ
            'detail' => 'required|string', // ต้องระบุและเป็นข้อความ
            'ps_text' => 'required|string',
            'doc_type' => 'required',
            'signer' => 'required|exists:besurv_signers,id', // ต้องระบุและต้องมีในตาราง besurv_signers
            'signer_position' => 'required|string|max:255', // ต้องระบุและไม่เกิน 255 ตัวอักษร
            'board' => 'required|array', // ต้องระบุและเป็น array
            // 'board.*' => 'exists:bcertify_committee_specials,id', // ทุกค่าใน array ต้องมีในตาราง bcertify_committee_specials
            'set_standard' => 'required|array', // ต้องระบุและเป็น array
            'image_file' => 'required|image|max:2048', // อนุญาตให้เป็น null หรือไฟล์ภาพไม่เกิน 2MB
            'google_form_qr' => 'required|image|max:2048', // อนุญาตให้เป็น null หรือไฟล์ภาพไม่เกิน 2MB
            
            'action' => 'required|in:save,submit' // ตรวจสอบค่า action
        ]);
        $model = str_slug('appointed-academic-sub-committee','-');

        // dd($request->all());

        if(auth()->user()->can('add-'.$model)) {
            if($request->doc_type == 1){
                $meetingInvitation = new LtMeetingInvitation();
                $meetingInvitation->type = 3;
                $meetingInvitation->reference_no = $validatedData['header'];
                $meetingInvitation->date = $validatedData['order_date'];
                $meetingInvitation->subject = $validatedData['title'];
                $meetingInvitation->attachments = $validatedData['attachment_text'];
                $meetingInvitation->details = $validatedData['detail'];
                $meetingInvitation->ps_text = $validatedData['ps_text'];
                $meetingInvitation->signer_id = $validatedData['signer'];
                $meetingInvitation->doc_type = $validatedData['doc_type'];
                $meetingInvitation->signer_position = $validatedData['signer_position'];
                $meetingInvitation->status = $validatedData['action'] === 'save' ? 1 : 2;

                // --- ส่วนที่เพิ่มเข้ามา ---

                if (!empty($validatedData['board'])) {
                    $meetingInvitation->board_json = json_encode($validatedData['board']);
                }

                if (!empty($validatedData['set_standard'])) {
                    $standardsData = collect($validatedData['set_standard'])->map(function($standardId) {
                        return [
                            'id' => $standardId,
                            'status' => 0
                        ];
                    });

                    $meetingInvitation->standard_json = json_encode($standardsData);
                }

                $meetingInvitation->save();

                // อัปโหลดไฟล์ QR ด้วย HP::singleFileUpload
                if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

                    HP::singleFileUploadRefno(
                            $request->file('image_file') ,
                            'files/meetingqr/'.$meetingInvitation->id,
                            null,
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new LtMeetingInvitation)->getTable() ),
                            $meetingInvitation->id,
                            '5678',
                            null
                    );

                    $attachedQr = AttachFile::where('ref_table',(new LtMeetingInvitation)->getTable())
                        ->where('ref_id',$meetingInvitation->id)
                        ->where('section',5678)->first();

                        if($attachedQr != null)
                        {
                            $meetingInvitation->qr_file_path = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename; // สมมติว่า HP::singleFileUpload คืนค่า path
                            $meetingInvitation->save();
                        }
                    
                } else {
                    throw new \Exception('ไม่สามารถอัปโหลดไฟล์ภาพได้');
                }

                if ($request->hasFile('google_form_qr') && $request->file('google_form_qr')->isValid()) {

                    HP::singleFileUploadRefno(
                            $request->file('google_form_qr') ,
                            'files/meetingqr/'.$meetingInvitation->id,
                            null,
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new LtMeetingInvitation)->getTable() ),
                            $meetingInvitation->id,
                            '135',
                            null
                    );

                    $attachedQr = AttachFile::where('ref_table',(new LtMeetingInvitation)->getTable())
                        ->where('ref_id',$meetingInvitation->id)
                        ->where('section',135)->first();

                        if($attachedQr != null)
                        {
                            $meetingInvitation->google_form_qr = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename; // สมมติว่า HP::singleFileUpload คืนค่า path
                            $meetingInvitation->save();
                        }
                    
                } else {
                    throw new \Exception('ไม่สามารถอัปโหลดไฟล์ภาพได้');
                }

            }else if($request->doc_type == 2)
            {
                // dd("ok");
                $meetingInvitation = new MeetingInvitation();
                $meetingInvitation->type = 1;  // 1 คือคณะกำหนด
                $meetingInvitation->reference_no = $validatedData['header'];
                $meetingInvitation->date = $validatedData['order_date'];
                $meetingInvitation->subject = $validatedData['title'];
                $meetingInvitation->attachments = $validatedData['attachment_text'];
                $meetingInvitation->details = $validatedData['detail'];
                $meetingInvitation->ps_text = $validatedData['ps_text'];
                $meetingInvitation->signer_id = $validatedData['signer'];
                $meetingInvitation->signer_position = $validatedData['signer_position'];
                $meetingInvitation->save();
                $meetingInvitation->setStandards()->sync($validatedData['set_standard']);
                $meetingInvitation->committeeSpecials()->sync($validatedData['board']);
                $meetingInvitation->status = $validatedData['action'] === 'save' ? 1 : 2;

                
                $meetingInvitation->save();

                    // อัปโหลดไฟล์ QR ด้วย HP::singleFileUpload
                if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {

                    HP::singleFileUploadRefno(
                            $request->file('image_file') ,
                            'files/meetingqr/'.$meetingInvitation->id,
                            null,
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new MeetingInvitation)->getTable() ),
                            $meetingInvitation->id,
                            '567',
                            null
                    );

                    $attachedQr = AttachFile::where('ref_table','meeting_invitations')
                        ->where('ref_id',$meetingInvitation->id)
                        ->where('section',567)->first();

                        if($attachedQr != null)
                        {
                            $meetingInvitation->qr_file_path = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename; // สมมติว่า HP::singleFileUpload คืนค่า path
                            $meetingInvitation->save();
                        }
                    
                } else {
                    throw new \Exception('ไม่สามารถอัปโหลดไฟล์ภาพได้');
                }

                
                if ($request->hasFile('google_form_qr') && $request->file('google_form_qr')->isValid()) {

                    HP::singleFileUploadRefno(
                            $request->file('google_form_qr') ,
                            'files/meetingqr/'.$meetingInvitation->id,
                            null,
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new MeetingInvitation)->getTable() ),
                            $meetingInvitation->id,
                            '789',
                            null
                    );

                    $attachedQr = AttachFile::where('ref_table','meeting_invitations')
                        ->where('ref_id',$meetingInvitation->id)
                        ->where('section',789)->first();

                        if($attachedQr != null)
                        {
                            $meetingInvitation->google_form_qr = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename; // สมมติว่า HP::singleFileUpload คืนค่า path
                            $meetingInvitation->save();
                        }
                    
                } else {
                    throw new \Exception('ไม่สามารถอัปโหลดไฟล์ภาพได้');
                }
            }


            return redirect(url('certify/appointed-lt-committee'))->with('success', 'บันทึกข้อมูลสำเร็จ');
                
        }
        abort(403);
    }




public function view($id)
{
    // dd($id);
    if (auth()->user()->can('view-' . str_slug('appointed-academic-sub-committee'))) {
        $meetingInvitation = LtMeetingInvitation::with(['signer'])->findOrFail($id);
  
        $standardIds = [];
        if (!empty($meetingInvitation->standard_json)) {
            $standardIds = collect($meetingInvitation->standard_json)->pluck('id')->all();
        }
        $tisiEstandardDraftPlans = TisiEstandardDraftPlan::whereIn('id', $standardIds)->get();

        // ตัวแปรสุดท้ายที่จะเก็บ ID ที่เป็น Integer
        $boardIds = [];

        // ดึงข้อมูลต้นทางออกมาใส่ตัวแปรก่อนเพื่อความกระชับ
        $sourceData = $meetingInvitation->board_json;

        // ตรวจสอบก่อนว่าข้อมูลไม่ว่างเปล่า
        if (!empty($sourceData)) {

            // ถ้าเป็น String (มีลักษณะเป็น "[\"9\"]")
            if (is_string($sourceData)) {
                // พยายาม decode JSON ให้เป็น PHP Array
                $decodedIds = json_decode($sourceData, true);

                // ถ้า decode สำเร็จและผลลัพธ์เป็น Array จริงๆ
                if (is_array($decodedIds)) {
                    // แปลงค่าทุกตัวใน Array ให้เป็น Integer
                    $boardIds = array_map('intval', $decodedIds);
                }
            }
            // แต่ถ้าเป็น Array อยู่แล้ว (มีลักษณะเป็น ["9"])
            elseif (is_array($sourceData)) {
                // ไม่ต้อง decode, แปลงค่าทุกตัวใน Array ให้เป็น Integer ได้เลย
                $boardIds = array_map('intval', $sourceData);
            }
        }


        $committeeSpecials = CommitteeSpecial::whereIn('id', $boardIds)->get();

       
        $attachFile = AttachFile::where('ref_table', 'lt_meeting_invitations')
            ->where('ref_id', $meetingInvitation->id)
            ->where('section', 'order_book')
            ->latest() // เพิ่มบรรทัดนี้เพื่อเรียงจากล่าสุด
            ->first();

        $order_book_url = null;
        if( $attachFile != null){
            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');
            $order_book_url = $url . 'funtions/get-view/' . $attachFile->url . '/' .$attachFile->filename ;


        }
  


        $signers = Signer::all();
        return view('certify.appointed-lt-committee.view', compact('meetingInvitation', 'signers','tisiEstandardDraftPlans','committeeSpecials','order_book_url'));
    }
    abort(403);
}

// public function getRequestList(Request $request)
// {
//             $tisiEstandardDraftPlans = TisiEstandardDraftPlan::where('status_id', 1)
//             ->whereHas('estandard_offers_to', function ($query) {
//                 $query->whereNotNull('standard_name');
//             })
//             ->get();
//     dd($request->doc_type_id);
// }


public function getRequestList(Request $request)
{
    // 1. รับค่า doc_type_id จาก AJAX request
    $doc_type_id = $request->input('doc_type_id');
    if($doc_type_id == 1){
      
        $tisiEstandardDraftPlans = TisiEstandardDraftPlan::where('status_id', 1)
            ->whereHas('estandard_offers_to', function ($query) {
                $query->whereNotNull('standard_name');
            })
            ->get();
        
        $formattedData = $tisiEstandardDraftPlans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'text' => $plan->estandard_offers_to->standard_name . ' (' . $plan->estandard_offers_to->refno . ')'
            ];
        });
    }else{

            // $setStandards = SetStandards::query()
            // ->whereHas('estandard_plan_to.estandard_offers_to', function ($query) {
            //     $query->whereNotNull('standard_name');
            // })
            // ->where(function ($query) {
            //     // เงื่อนไข A: status_sub_appointment_id เท่ากับ 5
            //     $query->where('status_sub_appointment_id', 5)
            //         // เงื่อนไข B: หรือ (OR) มี relationship ที่ proposer_type เป็น 'sdo_advanced'
            //         ->orWhereHas('estandard_plan_to.estandard_offers_to', function ($subQuery) {
            //             $subQuery->where('proposer_type', 'sdo_advanced');
            //         });
            // })
            // ->with('estandard_plan_to.estandard_offers_to')
            // ->get();

            $setStandards = SetStandards::with('estandard_plan_to.estandard_offers_to')
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


        $tisiEstandardDraftPlans = $setStandards->pluck('estandard_plan_to')->filter()->unique('id');

        $formattedData = $tisiEstandardDraftPlans
        
        ->map(function ($plan) {
            $standardName = optional($plan->estandard_offers_to)->standard_name;
            $refno = optional($plan->estandard_offers_to)->refno;
            $newRefno = str_replace('Req', 'CSD', $refno);

            return [
                'id'   => $plan->id,
                'text' => "{$standardName} ({$newRefno})",
            ];
        }) ->reverse()->values(); 



    }

    // dd($formattedData);

    // 4. ส่งข้อมูลกลับไปในรูปแบบ JSON
    return response()->json($formattedData);
}



}
