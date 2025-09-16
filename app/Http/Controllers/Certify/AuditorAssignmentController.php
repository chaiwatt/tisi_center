<?php

namespace App\Http\Controllers\Certify;

use HP;
use App\User;
use DOMXPath;
use DOMDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\ApplicantCB\CbTobToun;
use App\ApplicantIB\IbTobToun;
use Yajra\Datatables\Datatables;
use App\Mail\Lab\MailBoardAuditor;
use App\Http\Controllers\Controller;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\Mail;
use App\ApplicantCB\CbDocReviewAssessment;
use App\ApplicantIB\IbDocReviewAssessment;
use App\Models\Certify\Applicant\CertiLab;
use App\Services\CreateCbMessageRecordPdf;
use App\Services\CreateIbMessageRecordPdf;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Services\CreateLabMessageRecordPdf;
use App\Models\Certify\Applicant\CheckExaminer;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Bcertify\BoardAuditorMsRecordInfo;
use App\Models\Bcertify\CbBoardAuditorMsRecordInfo;
use App\Models\Bcertify\IbBoardAuditorMsRecordInfo;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBAuditors;
use App\Models\Certify\LabMessageRecordTransaction;

class AuditorAssignmentController extends Controller
{
    public function index(Request $request)
    {
        
        $model = str_slug('auditorassignment','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.auditor-assignment.index');
        }
        abort(403);

    }

    public function dataList(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'ผู้ใช้ไม่ได้เข้าสู่ระบบ'], 401);
        }

        $userId = $user->runrecno;
        $cleanId = preg_replace('/[\s-]/', '', $user->reg_13ID);

        $signer = Signer::where('tax_number', $cleanId)->first();

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($signer) {

            $filter_approval = $request->input('filter_state');
            $filter_certificate_type = $request->input('filter_certificate_type');
        
            $query = MessageRecordTransaction::query();
            $query->where('signer_id',$signer->id);


            if ($filter_approval) {
                $query->where('approval', $filter_approval);
            }else{
                $query->where('approval', 0);
            }
        
            if ($filter_certificate_type !== null) {
                
                $query->where('certificate_type', $filter_certificate_type);
            }
         
            $data = $query->get();
            $data = $data->map(function($item, $index) {
                $item->DT_Row_Index = $index + 1;

                // แปลง certificate_type เป็นข้อความ
                switch ($item->certificate_type) {
                    case 0:
                        $item->certificate_type = 'CB';
                        break;
                    case 1:
                        $item->certificate_type = 'IB';
                        break;
                    case 2:
                        $item->certificate_type = 'LAB';
                        break;
                    default:
                        $item->certificate_type = 'Unknown';
                }

                // แปลง approval เป็นข้อความ
                $item->approval = $item->approval == 0 ? 'รอดำเนินการ' : 'ลงนามเรียบร้อย';

                return $item;
            });
                
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    // สร้างปุ่มสองปุ่มที่ไม่มี action พิเศษ
                    $button1 = '<a href="' . $item->view_url . '" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>';
                    $button2 = '<a type="button" class="btn btn-warning btn-xs btn-sm sign-document" data-id="'.$item->signer_id.'"  data-transaction_id="'.$item->id.' "><i class="fa fa-file-text"></i></a>';
                    
                    return $button1 . ' ' . $button2; // รวมปุ่มทั้งสองเข้าด้วยกัน
                })
                ->editColumn('certificate_type', function ($item) {
                    switch ($item->certificate_type) {
                        case 0:
                            return 'CB';
                        case 1:
                            return 'IB';
                        case 2:
                            return 'LAB';
                        default:
                            return '-';
                    }
                })
                ->editColumn('approval', function ($item) {
                    return $item->approval == 1 ? 'ลงนามเรียบร้อย' : 'รอดำเนินการ';
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'DESC');
                })
                ->make(true);
        }else{
            return response()->json(['error' => 'ไม่พบข้อมูล signer'], 404);
        }
    }

    public function getSigner(Request $request)
    {
        // รับ signer_id จาก request
        $signer_id = $request->input('signer_id');

        // ดึงข้อมูล Signer ตาม ID ที่ส่งมา
        $signer = Signer::find($signer_id);

        // ตรวจสอบว่า AttachFileAttachTo มีข้อมูลหรือไม่
        $attach = !empty($signer->AttachFileAttachTo) ? $signer->AttachFileAttachTo : null;

        if ($attach !== null) {
            $sign_url = url('funtions/get-view/' . $attach->url . '/' . (!empty($attach->filename) ? $attach->filename : basename($attach->url)));
        } else {
            $sign_url = null; // กรณีที่ไม่มีไฟล์แนบ
        }

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($signer) {
            // เพิ่ม sign_url เข้าไปใน response data
            return response()->json([
                'success' => true,
                'data' => array_merge($signer->toArray(), [
                    'sign_url' => $sign_url
                ])
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบข้อมูลผู้ลงนามที่ต้องการ'
            ], 404);
        }
    }


    public function signDocument(Request $request)
    {
        // 1. ค้นหา Transaction ที่ต้องการลงนาม
        $currentTransaction = MessageRecordTransaction::find($request->id);

       
        // dd($currentTransaction);

        if (!$currentTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่พบรายการที่ต้องการลงนาม'
            ]);
        }

        if ($currentTransaction->approval == 1) {
            return response()->json([
                'success' => false,
                'message' => 'เอกสารนี้ได้ถูกลงนามไปแล้ว'
            ]);
        }

        if($currentTransaction->job_type == "ib-doc-review-assessment" || $currentTransaction->job_type == "cb-doc-review-assessment" )
        {
            //  dd($currentTransaction);
           $nextPendingTransaction = MessageRecordTransaction::where('board_auditor_id', $currentTransaction->board_auditor_id)
                ->whereIn('job_type', ["ib-doc-review-assessment","cb-doc-review-assessment"])
                ->where('certificate_type', $currentTransaction->certificate_type)
                ->where('approval', 0)
                ->where('signer_order', '<', $currentTransaction->signer_order)
                ->where('signer_id', '!=', $currentTransaction->signer_id)
                ->orderBy('signer_order', 'asc') // จัดลำดับเพื่อหา order ที่น้อยที่สุด
                ->first(); // เอาแค่รายการแรกที่เจอ


            if($currentTransaction->job_type == "cb-doc-review-assessment")
            {
                $certiCb = CertiCb::where('app_no', $currentTransaction->app_id)->first();
            
                $cbDocReviewAssessment=  CbDocReviewAssessment::where('app_certi_cb_id', $certiCb->id)
                                ->where('report_type',"cb-doc-review-assessment")
                                ->first();

                                                // ดึง HTML content เริ่มต้น
                $htmlContent = $cbDocReviewAssessment->template;

                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
 
                $signedDateNodes = $xpath->query('//*[contains(@class, "signed_date")]');
                foreach ($signedDateNodes as $node) {
                    // parentNode คือ <div> ที่มี data-signer-id
                    $parentDiv = $node->parentNode;
                    if ($parentDiv && $parentDiv->hasAttribute('data-signer-id')) {
                        $signerId = $parentDiv->getAttribute('data-signer-id');

                         if ($signerId == $currentTransaction->signer_id) {
                            // 4. สร้างวันที่และจัดรูปแบบ (dd/mm/yyyy พ.ศ.)
                            // ใช้เวลาที่ transaction อัปเดตล่าสุดเพื่อความแม่นยำ หรือใช้ Carbon::now() หากไม่มี
                            $signatureDate = $currentTransaction->updated_at ?? Carbon::now();
                            $formattedDate = $signatureDate->addYears(543)->format('d/m/Y');

                            // 5. อัปเดตเนื้อหาของ node
                            $node->nodeValue = 'วันที่ ' . $formattedDate;
                        }
                    }
                }
                  // 6. แปลง DOM ที่แก้ไขแล้วกลับเป็น HTML String (เอาเฉพาะเนื้อหาใน body)
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtml = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtml .= $dom->saveHTML($child);
                }
                
                // 7. อัปเดตค่าใน object
                $cbDocReviewAssessment->template = $updatedHtml;

                // 8. บันทึกการเปลี่ยนแปลงลงฐานข้อมูล
                $cbDocReviewAssessment->save();

            }else if($currentTransaction->job_type == "ib-doc-review-assessment")
            {
                $certiIb = CertiIb::where('app_no', $currentTransaction->app_id)->first();
            
                $ibDocReviewAssessment=  IbDocReviewAssessment::where('app_certi_ib_id', $certiIb->id)
                                ->where('report_type',"ib-doc-review-assessment")
                                ->first();

                                                // ดึง HTML content เริ่มต้น
                $htmlContent = $ibDocReviewAssessment->template;

                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
 
                $signedDateNodes = $xpath->query('//*[contains(@class, "signed_date")]');
                foreach ($signedDateNodes as $node) {
                    // parentNode คือ <div> ที่มี data-signer-id
                    $parentDiv = $node->parentNode;
                    if ($parentDiv && $parentDiv->hasAttribute('data-signer-id')) {
                        $signerId = $parentDiv->getAttribute('data-signer-id');

                         if ($signerId == $currentTransaction->signer_id) {
                            // 4. สร้างวันที่และจัดรูปแบบ (dd/mm/yyyy พ.ศ.)
                            // ใช้เวลาที่ transaction อัปเดตล่าสุดเพื่อความแม่นยำ หรือใช้ Carbon::now() หากไม่มี
                            $signatureDate = $currentTransaction->updated_at ?? Carbon::now();
                            $formattedDate = $signatureDate->addYears(543)->format('d/m/Y');

                            // 5. อัปเดตเนื้อหาของ node
                            $node->nodeValue = 'วันที่ ' . $formattedDate;
                        }
                    }
                }
                  // 6. แปลง DOM ที่แก้ไขแล้วกลับเป็น HTML String (เอาเฉพาะเนื้อหาใน body)
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtml = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtml .= $dom->saveHTML($child);
                }
                
                // 7. อัปเดตค่าใน object
                $ibDocReviewAssessment->template = $updatedHtml;

                // 8. บันทึกการเปลี่ยนแปลงลงฐานข้อมูล
                $ibDocReviewAssessment->save();
            }

            // หากมี Transaction ที่ต้องลงนามก่อนหน้า
            if ($nextPendingTransaction) {
                // ค้นหาชื่อผู้ลงนามโดยตรงจาก Model Signer โดยไม่ใช้ relation
                $signer = Signer::find($nextPendingTransaction->signer_id);
                
                // กำหนดชื่อ fallback กรณีไม่พบข้อมูล
                $signerName = $signer ? $signer->name : 'ผู้ลงนามลำดับก่อนหน้า';

                return response()->json([
                    'success' => false,
                    'message' => 'กรุณารอการลงนามจาก: ' . $signerName
                ]);
            }

            // 3. ถ้าผ่านการตรวจสอบ ให้ทำการอัปเดตสถานะการลงนาม
            $currentTransaction->update(['approval' => 1]);


        }else if($currentTransaction->job_type == "ib-tangtung-tobtoun" || $currentTransaction->job_type == "cb-tangtung-tobtoun" )
        {
             $nextPendingTransaction = MessageRecordTransaction::where('board_auditor_id', $currentTransaction->board_auditor_id)
                ->whereIn('job_type', ["ib-tangtung-tobtoun","cb-tangtung-tobtoun"])
                ->where('certificate_type', $currentTransaction->certificate_type)
                ->where('approval', 0)
                ->where('signer_order', '<', $currentTransaction->signer_order)
                ->where('signer_id', '!=', $currentTransaction->signer_id)
                ->orderBy('signer_order', 'asc') // จัดลำดับเพื่อหา order ที่น้อยที่สุด
                ->first(); // เอาแค่รายการแรกที่เจอ

            // หากมี Transaction ที่ต้องลงนามก่อนหน้า
            if ($nextPendingTransaction) {
                // ค้นหาชื่อผู้ลงนามโดยตรงจาก Model Signer โดยไม่ใช้ relation
                $signer = Signer::find($nextPendingTransaction->signer_id);
                
                // กำหนดชื่อ fallback กรณีไม่พบข้อมูล
                $signerName = $signer ? $signer->name : 'ผู้ลงนามลำดับก่อนหน้า';

                return response()->json([
                    'success' => false,
                    'message' => 'กรุณารอการลงนามจาก: ' . $signerName
                ]);
            }

            // 3. ถ้าผ่านการตรวจสอบ ให้ทำการอัปเดตสถานะการลงนาม
            $currentTransaction->update(['approval' => 1]);







         if($currentTransaction->job_type == "cb-tangtung-tobtoun")
            {
                $certiCb = CertiCb::where('app_no', $currentTransaction->app_id)->first();
            
                $cbDocReviewAssessment=  CbTobToun::where('app_certi_cb_id', $certiCb->id)
                                ->where('report_type',"cb-tangtung-tobtoun")
                                ->first();
                $htmlContent = $cbDocReviewAssessment->template;

                                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
 
                $signedDateNodes = $xpath->query('//*[contains(@class, "signed_date")]');
                foreach ($signedDateNodes as $node) {
                    // parentNode คือ <div> ที่มี data-signer-id
                    $parentDiv = $node->parentNode;
                    if ($parentDiv && $parentDiv->hasAttribute('data-signer-id')) {
                        $signerId = $parentDiv->getAttribute('data-signer-id');

                         if ($signerId == $currentTransaction->signer_id) {
                            // 4. สร้างวันที่และจัดรูปแบบ (dd/mm/yyyy พ.ศ.)
                            // ใช้เวลาที่ transaction อัปเดตล่าสุดเพื่อความแม่นยำ หรือใช้ Carbon::now() หากไม่มี
                            $signatureDate = $currentTransaction->updated_at ?? Carbon::now();
                            $formattedDate = $signatureDate->addYears(543)->format('d/m/Y');

                            // 5. อัปเดตเนื้อหาของ node
                            $node->nodeValue = 'วันที่ ' . $formattedDate;
                        }
                    }
                }
                  // 6. แปลง DOM ที่แก้ไขแล้วกลับเป็น HTML String (เอาเฉพาะเนื้อหาใน body)
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtml = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtml .= $dom->saveHTML($child);
                }
                
                // 7. อัปเดตค่าใน object
                $cbDocReviewAssessment->template = $updatedHtml;

                // 8. บันทึกการเปลี่ยนแปลงลงฐานข้อมูล
                $cbDocReviewAssessment->save();

                // dd($cbDocReviewAssessment->template);
            }else if($currentTransaction->job_type == "ib-tangtung-tobtoun")
            {
                $certiIb = CertiIb::where('app_no', $currentTransaction->app_id)->first();
            
                $ibDocReviewAssessment=  IbTobToun::where('app_certi_ib_id', $certiIb->id)
                                ->where('report_type',"ib-tangtung-tobtoun")
                                ->first();
                $htmlContent = $ibDocReviewAssessment->template;

                                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
 
                $signedDateNodes = $xpath->query('//*[contains(@class, "signed_date")]');
                foreach ($signedDateNodes as $node) {
                    // parentNode คือ <div> ที่มี data-signer-id
                    $parentDiv = $node->parentNode;
                    if ($parentDiv && $parentDiv->hasAttribute('data-signer-id')) {
                        $signerId = $parentDiv->getAttribute('data-signer-id');

                         if ($signerId == $currentTransaction->signer_id) {
                            // 4. สร้างวันที่และจัดรูปแบบ (dd/mm/yyyy พ.ศ.)
                            // ใช้เวลาที่ transaction อัปเดตล่าสุดเพื่อความแม่นยำ หรือใช้ Carbon::now() หากไม่มี
                            $signatureDate = $currentTransaction->updated_at ?? Carbon::now();
                            $formattedDate = $signatureDate->addYears(543)->format('d/m/Y');

                            // 5. อัปเดตเนื้อหาของ node
                            $node->nodeValue = 'วันที่ ' . $formattedDate;
                        }
                    }
                }
                  // 6. แปลง DOM ที่แก้ไขแล้วกลับเป็น HTML String (เอาเฉพาะเนื้อหาใน body)
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtml = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtml .= $dom->saveHTML($child);
                }
                
                // 7. อัปเดตค่าใน object
                $ibDocReviewAssessment->template = $updatedHtml;

                // 8. บันทึกการเปลี่ยนแปลงลงฐานข้อมูล
                $ibDocReviewAssessment->save();
            }






        }
        else{
               // 2. ตรวจสอบและค้นหาผู้ลงนามลำดับก่อนหน้าที่ยังไม่ได้ลงนาม
            $nextPendingTransaction = MessageRecordTransaction::where('board_auditor_id', $currentTransaction->board_auditor_id)
                ->where('certificate_type', $currentTransaction->certificate_type)
                ->where('approval', 0)
                ->where('signer_order', '<', $currentTransaction->signer_order)
                ->where('signer_id', '!=', $currentTransaction->signer_id)
                ->orderBy('signer_order', 'asc') // จัดลำดับเพื่อหา order ที่น้อยที่สุด
                ->first(); // เอาแค่รายการแรกที่เจอ

            // หากมี Transaction ที่ต้องลงนามก่อนหน้า
            if ($nextPendingTransaction) {
                // ค้นหาชื่อผู้ลงนามโดยตรงจาก Model Signer โดยไม่ใช้ relation
                $signer = Signer::find($nextPendingTransaction->signer_id);
                
                // กำหนดชื่อ fallback กรณีไม่พบข้อมูล
                $signerName = $signer ? $signer->name : 'ผู้ลงนามลำดับก่อนหน้า';

                return response()->json([
                    'success' => false,
                    'message' => 'กรุณารอการลงนามจาก: ' . $signerName
                ]);
            }

           
//  $record = BoardAuditorMsRecordInfo::where('board_auditor_id',$currentTransaction->board_auditor_id)->first();
            // dd(CbBoardAuditorMsRecordInfo::where('board_auditor_id',$currentTransaction->board_auditor_id)->first());

            if($currentTransaction->certificate_type == 2){
                $record = BoardAuditorMsRecordInfo::where('board_auditor_id',$currentTransaction->board_auditor_id)->first();
                if($record == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'อยู่ระหว่างจัดทำรายงานแต่งตั้ง'
                    ]);
                }
            }else if($currentTransaction->certificate_type == 1) {
            $record = IbBoardAuditorMsRecordInfo::where('board_auditor_id',$currentTransaction->board_auditor_id)->first();
                if($record == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'อยู่ระหว่างจัดทำรายงานแต่งตั้ง'
                    ]);
                }
            }elseif ($currentTransaction->certificate_type == 0)
            {
                $record = CbBoardAuditorMsRecordInfo::where('board_auditor_id',$currentTransaction->board_auditor_id)->first();
                if($record == null){
                    return response()->json([
                        'success' => false,
                        'message' => 'อยู่ระหว่างจัดทำรายงานแต่งตั้ง'
                    ]);
                }
            }

            //  dd("ok");
            // 3. ถ้าผ่านการตรวจสอบ ให้ทำการอัปเดตสถานะการลงนาม
            $currentTransaction->update(['approval' => 1]);


            // 4. ตรวจสอบว่าการลงนามทั้งหมดเสร็จสิ้นแล้วหรือไม่
            $remainingApprovals = MessageRecordTransaction::where('board_auditor_id', $currentTransaction->board_auditor_id)
                ->where('certificate_type', $currentTransaction->certificate_type)
                ->whereNotNull('signer_id')
                ->where('approval', 0)
                ->count();

            // 5. หากลงนามครบทุกคนแล้ว ให้ทำขั้นตอนสุดท้าย (เช่น สร้าง PDF, ส่งอีเมล)
            if ($remainingApprovals === 0) {
                $boardAuditor = $currentTransaction->boardAuditor;

                switch ($currentTransaction->certificate_type) {
                    case 2: // LAB
                        if (is_null($boardAuditor->boardAuditorMsRecordInfos) || is_null($boardAuditor->boardAuditorMsRecordInfos->first())) {
                            // Log an error or handle the case where the record is missing but signing is complete.
                            // This part of the logic might need review based on business requirements.
                        } else {
                            $this->set_mail($boardAuditor, $boardAuditor->CertiLabs);
                            $pdfService = new CreateLabMessageRecordPdf($boardAuditor, "ia");
                            $pdfService->generateBoardAuditorMessageRecordPdf();
                        }
                        break;

                    case 0: // CB
                        if (is_null($boardAuditor->cbBoardAuditorMsRecordInfos) || is_null($boardAuditor->cbBoardAuditorMsRecordInfos->first())) {
                            // Handle missing record
                        } else {
                            $board = CertiCBAuditors::find($currentTransaction->board_auditor_id);
                            $pdfService = new CreateCbMessageRecordPdf($board, "ia");
                            $pdfService->generateBoardAuditorMessageRecordPdf();
                        }
                        break;

                    case 1: // IB
                        if (is_null($boardAuditor->ibBoardAuditorMsRecordInfos) || is_null($boardAuditor->ibBoardAuditorMsRecordInfos->first())) {
                            // Handle missing record
                        } else {
                            $board = CertiIBAuditors::find($currentTransaction->board_auditor_id);
                            $pdfService = new CreateIbMessageRecordPdf($board, "ia");
                            $pdfService->generateBoardAuditorMessageRecordPdf();
                        }
                        break;
                }
            }
        }

     

        return response()->json([
            'success' => true,
            'message' => 'ลงนามเอกสารเรียบร้อยแล้ว'
        ]);
    }



    public function signDocument_old(Request $request)
    {
        
        $messageRecordTransaction = MessageRecordTransaction::find($request->id);
        $boardAuditor = $messageRecordTransaction->boardAuditor;
        
        if ($messageRecordTransaction->certificate_type == 2)
        {

            if (is_null($boardAuditor->boardAuditorMsRecordInfos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            $boardAuditorMsRecordInfo = $boardAuditor->boardAuditorMsRecordInfos->first();
            

               if (is_null($boardAuditorMsRecordInfo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            MessageRecordTransaction::find($request->id)->update([
                'approval' => 1
            ]);
            // LAB
            $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id',$messageRecordTransaction->board_auditor_id)
                    ->whereNotNull('signer_id')
                    ->where('certificate_type',2)
                    ->where('approval',0)
                    ->get();           

            if($messageRecordTransactions->count() == 0){
                $board = BoardAuditor::find($messageRecordTransaction->board_auditor_id);

                $this->set_mail($board,$board->CertiLabs);
                $pdfService = new CreateLabMessageRecordPdf($board,"ia");
                $pdfContent = $pdfService->generateBoardAuditorMessageRecordPdf();
            }                        

        }else if($messageRecordTransaction->certificate_type == 0)
        {

             if (is_null($boardAuditor->cbBoardAuditorMsRecordInfos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            $cbBoardAuditorMsRecordInfo = $boardAuditor->cbBoardAuditorMsRecordInfos->first();

            if (is_null($cbBoardAuditorMsRecordInfo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            MessageRecordTransaction::find($request->id)->update([
                'approval' => 1
            ]);
            // CB
            $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id',$messageRecordTransaction->board_auditor_id)
                    ->whereNotNull('signer_id')
                    ->where('certificate_type',0)
                    ->where('approval',0)
                    ->get();           
            // dd($messageRecordTransactions->count());
            if($messageRecordTransactions->count() == 0){
                $board = CertiCBAuditors::find($messageRecordTransaction->board_auditor_id);
                $pdfService = new CreateCbMessageRecordPdf($board,"ia");
                $pdfContent = $pdfService->generateBoardAuditorMessageRecordPdf();
            }    

        }else if($messageRecordTransaction->certificate_type == 1)
        {

            if (is_null($boardAuditor->ibBoardAuditorMsRecordInfos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            $ibBoardAuditorMsRecordInfos = $boardAuditor->ibBoardAuditorMsRecordInfos->first();

            if (is_null($ibBoardAuditorMsRecordInfos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'บันทึกข้อความยังไม่ได้สร้าง'
                ]);
            }

            // dd($cbBoardAuditorMsRecordInfo);
            MessageRecordTransaction::find($request->id)->update([
                'approval' => 1
            ]);
            // IB
            $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id',$messageRecordTransaction->board_auditor_id)
                    ->whereNotNull('signer_id')
                    ->where('certificate_type',1)
                    ->where('approval',0)
                    ->get();           

            if($messageRecordTransactions->count() == 0){
                $board = CertiIBAuditors::find($messageRecordTransaction->board_auditor_id);
                $pdfService = new CreateIbMessageRecordPdf($board,"ia");
                $pdfContent = $pdfService->generateBoardAuditorMessageRecordPdf();
            }  
        }
        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);

    }

    public function set_mail($auditors,$certi_lab) 
    {

        if(!is_null($certi_lab->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';

            if(!empty($certi_lab->DataEmailDirectorLABCC)){
                $mail_cc = $certi_lab->DataEmailDirectorLABCC;
                // array_push($mail_cc, auth()->user()->reg_email) ;
            }
           
            $data_app = [
                            'email'=>  'admin@admin.com',
                            'auditors' => $auditors,
                            'certi_lab'=> $certi_lab,
                            'url' => $url.'certify/applicant/auditor/'.$certi_lab->token,
                            'email'=>  !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                            'email_cc'=>  !empty($mail_cc) ? $mail_cc :  $EMail,
                            'email_reply' => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                        ];
        
            $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                        $certi_lab->id,
                                                        (new CertiLab)->getTable(),
                                                        $auditors->id,
                                                        (new BoardAuditor)->getTable(),
                                                        1,
                                                        'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                        view('mail.Lab.mail_board_auditor', $data_app),
                                                        $certi_lab->created_by,
                                                        $certi_lab->agent_id,
                                                        auth()->user()->getKey(),
                                                        !empty($certi_lab->DataEmailCertifyCenter) ?  implode(',',(array)$certi_lab->DataEmailCertifyCenter)  : $EMail,
                                                        $certi_lab->email,
                                                        !empty($mail_cc) ? implode(',',(array)$mail_cc)   :  $EMail,
                                                        !empty($certi_lab->DataEmailDirectorLABReply) ?implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :  $EMail,
                                                        null
                                                        );
    
             $html = new  MailBoardAuditor($data_app);
              $mail = Mail::to($certi_lab->email)->send($html);

              if(is_null($mail) && !empty($log_email)){
                   HP::getUpdateCertifyLogEmail($log_email->id);
              }

        }
    }
}
