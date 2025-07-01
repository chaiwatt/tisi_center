<?php

namespace App\Http\Controllers\Certify;

use App\AttachFile;
use HP;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;
use App\Certificate\MeetingInvitation;


class AppointedAcademicSubCommitteeController extends Controller
{
    public function index(Request $request)
    {
        
        $model = str_slug('appointed-academic-sub-committee','-');
        if(auth()->user()->can('view-'.$model)) {

            $meetingInvitations = MeetingInvitation::whereHas('setStandards', function ($query) {
                    $query->where('status_id', 0)
                          ->orWhere('status_sub_appointment_id',0);
                })
                ->whereIn('status',[1,2])
                ->with('setStandards') // Eager load เพื่อลด query
                ->get();
                

                        // dd($meetingInvitations);

                        return view('certify.appointed-academic-sub-committee.index',[
                            'meetingInvitations' => $meetingInvitations
                        ]);
        }
        abort(403);
    }

    public function create()
    {
        // dd("ok");
        $model = str_slug('appointed-academic-sub-committee','-');
        $singers = Signer::all();
        $setStandards = SetStandards::where('status_id', 0)
                ->orWhere('status_sub_appointment_id', 0)
                ->orderBy('id', 'desc')
                ->get();

        if(auth()->user()->can('add-'.$model)) {
            return view('certify.appointed-academic-sub-committee.create',[
                'signers' =>  $singers,
                'setStandards' =>  $setStandards,
            ]);
            }
        abort(403);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'doc_type' => 'required|in:1,2', // ต้องระบุและต้องเป็น 1 หรือ 2
            'header' => 'required|string|max:255', // ต้องระบุและไม่เกิน 255 ตัวอักษร
            'order_date' => 'required|string', // ต้องระบุและเป็นวันที่ที่ถูกต้อง
            'title' => 'required|string|max:500', // ต้องระบุและไม่เกิน 500 ตัวอักษร
            'attachment_text' => 'required|string', // ต้องระบุและเป็นข้อความ
            'detail' => 'required|string', // ต้องระบุและเป็นข้อความ
            'ps_text' => 'required|string',
            'signer' => 'required|exists:besurv_signers,id', // ต้องระบุและต้องมีในตาราง besurv_signers
            'signer_position' => 'required|string|max:255', // ต้องระบุและไม่เกิน 255 ตัวอักษร
            'board' => 'required|array', // ต้องระบุและเป็น array
            'board.*' => 'exists:bcertify_committee_specials,id', // ทุกค่าใน array ต้องมีในตาราง bcertify_committee_specials
            'set_standard' => 'required|array', // ต้องระบุและเป็น array
            'set_standard.*' => 'exists:certify_setstandards,id', // ทุกค่าใน array ต้องมีในตาราง certify_setstandards
            'image_file' => 'required|image|max:2048', // อนุญาตให้เป็น null หรือไฟล์ภาพไม่เกิน 2MB
            'google_form_qr' => 'required|image|max:2048', // อนุญาตให้เป็น null หรือไฟล์ภาพไม่เกิน 2MB
            
            'action' => 'required|in:save,submit' // ตรวจสอบค่า action
        ]);
        $model = str_slug('appointed-academic-sub-committee','-');

        if(auth()->user()->can('add-'.$model)) {
            $meetingInvitation = new MeetingInvitation();
            $meetingInvitation->type = $validatedData['doc_type'];
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


            return redirect(url('certify/appointed-academic-sub-committee'))->with('success', 'บันทึกข้อมูลสำเร็จ');
                
            }
        abort(403);
    }

    public function edit($id)
    {
        $model = str_slug('appointed-academic-sub-committee','-');

        $attachedQr = AttachFile::where('ref_table','meeting_invitations')
        ->where('ref_id');

        if(auth()->user()->can('edit-'.$model)) {
            $meetingInvitation = MeetingInvitation::with(['setStandards', 'committeeSpecials', 'signer'])
            ->findOrFail($id);

            if ($meetingInvitation->status != 1) {
                return redirect()->route('certify.appointed-academic-sub-committee.index')
                    ->with('error', 'ไม่สามารถแก้ไขได้: รายการนี้อยู่ในสถานะ ' . ($meetingInvitation->status == 2 ? 'ส่งลงนาม' : 'ไม่ทราบสถานะ'));
            }

            // $setStandards = SetStandards::where('projectid', null)->get();
              $setStandards = SetStandards::where('status_id', 0)
                ->orWhere('status_sub_appointment_id', 0)
                ->orderBy('id', 'desc')
                ->get();
            $signers = Signer::all();

            return view('certify.appointed-academic-sub-committee.edit', compact('meetingInvitation', 'setStandards', 'signers'));
        }
        abort(403);
    }

public function update(Request $request, $id)
{
    // dd($id);
    $validatedData = $request->validate([
        'doc_type' => 'required|in:1,2',
        'header' => 'required|string|max:255',
        'order_date' => 'required|string',
        'title' => 'required|string|max:500',
        'attachment_text' => 'required|string',
        'detail' => 'required|string',
        'signer' => 'required|exists:besurv_signers,id',
        'signer_position' => 'required|string|max:255', 
        'board' => 'required|array',
        'board.*' => 'exists:bcertify_committee_specials,id',
        'ps_text' => 'required|string',
        'set_standard' => 'required|array',
        'set_standard.*' => 'exists:certify_setstandards,id',
        'image_file' => 'nullable|image|max:2048', // อนุญาตให้เป็น null
        'google_form_qr' => 'nullable|image|max:2048',
        'action' => 'required|in:save,submit'
    ]);

    
    $model = str_slug('appointed-academic-sub-committee', '-');


    if (auth()->user()->can('edit-' . $model)) {

        try {
            $meetingInvitation = MeetingInvitation::findOrFail($id);


            // dd($meetingInvitation);

            if ($meetingInvitation->status != 1) {
                return redirect()->route('certify.appointed-academic-sub-committee.index')
                    ->with('error', 'ไม่สามารถแก้ไขได้: รายการนี้อยู่ในสถานะ ' . ($meetingInvitation->status == 2 ? 'ส่งลงนาม' : 'ไม่ทราบสถานะ'));
            }

            // อัปเดตข้อมูล MeetingInvitation
            $meetingInvitation->type = $validatedData['doc_type'];
            $meetingInvitation->reference_no = $validatedData['header'];
            $meetingInvitation->date = $validatedData['order_date'];
            $meetingInvitation->subject = $validatedData['title'];
            $meetingInvitation->attachments = $validatedData['attachment_text'];
            $meetingInvitation->details = $validatedData['detail'];
            $meetingInvitation->ps_text = $validatedData['ps_text'];
            $meetingInvitation->signer_id = $validatedData['signer'];
            $meetingInvitation->signer_position = $validatedData['signer_position'];
            $meetingInvitation->status = $validatedData['action'] === 'save' ? 1 : 2;

            // อัปโหลดไฟล์ QR ใหม่ถ้ามี
            if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
                // ลบไฟล์ QR เดิมใน attach_files (ถ้ามี)
                $oldAttachedQr = AttachFile::where('ref_table', 'meeting_invitations')
                    ->where('ref_id', $meetingInvitation->id)
                    ->where('section', 567)
                    ->first();

                if ($oldAttachedQr) {

                    $oldAttachedQr->delete();
                }

                // อัปโหลดไฟล์ใหม่ด้วย HP::singleFileUploadRefno
                HP::singleFileUploadRefno(
                    $request->file('image_file'),
                    'files/meetingqr/' . $meetingInvitation->id,
                    null,
                    auth()->user()->FullName ?? null,
                    'Center',
                    (new MeetingInvitation)->getTable(),
                    $meetingInvitation->id,
                    '567',
                    null
                );

                // ดึงข้อมูลไฟล์ใหม่จาก attach_files
                $attachedQr = AttachFile::where('ref_table', 'meeting_invitations')
                    ->where('ref_id', $meetingInvitation->id)
                    ->where('section', 567)
                    ->first();

                if ($attachedQr) {
                    $meetingInvitation->qr_file_path = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename;
                } else {
                    throw new \Exception('ไม่สามารถบันทึกไฟล์ QR ได้');
                }
            }

                        // อัปโหลดไฟล์ QR ใหม่ถ้ามี
            if ($request->hasFile('google_form_qr') && $request->file('google_form_qr')->isValid()) {
                // ลบไฟล์ QR เดิมใน attach_files (ถ้ามี)
                $oldAttachedQr = AttachFile::where('ref_table', 'meeting_invitations')
                    ->where('ref_id', $meetingInvitation->id)
                    ->where('section', 789)
                    ->first();

                if ($oldAttachedQr) {

                    $oldAttachedQr->delete();
                }

                // อัปโหลดไฟล์ใหม่ด้วย HP::singleFileUploadRefno
                HP::singleFileUploadRefno(
                    $request->file('google_form_qr'),
                    'files/meetingqr/' . $meetingInvitation->id,
                    null,
                    auth()->user()->FullName ?? null,
                    'Center',
                    (new MeetingInvitation)->getTable(),
                    $meetingInvitation->id,
                    '789',
                    null
                );

                // ดึงข้อมูลไฟล์ใหม่จาก attach_files
                $attachedQr = AttachFile::where('ref_table', 'meeting_invitations')
                    ->where('ref_id', $meetingInvitation->id)
                    ->where('section', 789)
                    ->first();

                if ($attachedQr) {
                    $meetingInvitation->google_form_qr = 'funtions/get-view/' . $attachedQr->url . '/' . $attachedQr->filename;
                } else {
                    throw new \Exception('ไม่สามารถบันทึกไฟล์ QR ได้');
                }
            }

            // บันทึกข้อมูลและความสัมพันธ์
            $meetingInvitation->save();
            $meetingInvitation->setStandards()->sync($validatedData['set_standard']);
            $meetingInvitation->committeeSpecials()->sync($validatedData['board']);

            // DB::commit();
            $message = $validatedData['action'] === 'save' ? 'แก้ไขข้อมูลสำเร็จ' : 'ส่งลงนามสำเร็จ';
            return redirect(url('certify/appointed-academic-sub-committee'))->with('success', 'บันทึกข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล: ' . $e->getMessage()]);
        }
    }

    abort(403, 'คุณไม่มีสิทธิ์ในการแก้ไขข้อมูลนี้');
}

public function view($id)
{
    if (auth()->user()->can('view-' . str_slug('appointed-academic-sub-committee'))) {
        $meetingInvitation = MeetingInvitation::with(['setStandards', 'committeeSpecials', 'signer'])->findOrFail($id);
        $setStandards = SetStandards::where('projectid', null)->get();
        $signers = Signer::all();
        return view('certify.appointed-academic-sub-committee.view', compact('meetingInvitation', 'setStandards', 'signers'));
    }
    abort(403);
}
}


