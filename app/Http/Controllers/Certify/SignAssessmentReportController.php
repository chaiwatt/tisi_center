<?php

namespace App\Http\Controllers\Certify;

use HP;
use DB; 

use HP_DGA;
use QrCode;
use App\User;
// use Storage; 
use Carbon\Carbon;

use App\Http\Requests;
use App\CertificateExport;
use Illuminate\Support\Str;
use App\Jobs\GeneratePdfJob;
use Illuminate\Http\Request;
use  App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;

use App\Certify\CbReportTemplate;
use App\Certify\IbReportTemplate;
use App\Models\Basic\SubDepartment;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Certify\SendCertificates;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\SignCertificateOtp;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\SendCertificateLists;
use App\Services\CreateCbAssessmentReportPdf;
use App\Services\CreateIbAssessmentReportPdf;
use App\Jobs\GenerateCbCarReportProcessOnePdf;
use App\Models\Certify\SendCertificateHistory;
use App\Services\CreateLabAssessmentReportPdf;
use App\Models\Certify\SignCertificateConfirms;
use App\Jobs\GenerateCbFinalReportProcessOnePdf;

use App\Jobs\GenerateIbFinalReportProcessOnePdf;
use App\Models\Certify\MessageRecordTransaction;
use App\Services\CreateCbAssessmentReportTwoPdf;
use App\Services\CreateIbAssessmentReportTwoPdf;
use App\Jobs\GenerateIbCarReportTwoProcessOnePdf;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Services\CreateLabAssessmentReportTwoPdf;
use App\Jobs\GenerateIbCbFinalReportProcessOnePdf;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;

class SignAssessmentReportController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'files/sendcertificatelists';
    }

    public function index(Request $request)
    {
        $model = str_slug('assessment_report_assignment','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.assessment-report-assignment.index');
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
        // ดึงข้อมูล signer โดยใช้ user_register_id
        // $signer = Signer::where('user_register_id', $userId)->first();
        $cleanId = preg_replace('/[\s-]/', '', $user->reg_13ID);
        // ดึงข้อมูล signer โดยใช้ user_register_id
        // $signer = Signer::where('user_register_id', $userId)->first();
        $signer = Signer::where('tax_number', $cleanId)->first();

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($signer) {
            $filter_approval = $request->input('filter_state');
            $filter_certificate_type = $request->input('filter_certificate_type');
        
            $query = SignAssessmentReportTransaction::query();
            // $query->where('signer_id',$signer->id);
        
            // if ($filter_approval) {
            //     $query->where('approval', $filter_approval);
            // }else{
            //     $query->where('approval', 0);
            // }

            // if ($filter_certificate_type !== null) {
                
            //     $query->where('certificate_type', $filter_certificate_type);
            // }

            
            $query->where(function ($q) use ($signer) {
                $q->where('certificate_type', 0)
                  ->where('signer_id', $signer->id)
                  ->where('approval', 0);
                //   ->where(function ($subQ) {
                //       $subQ->whereHas('cbReportInfo', function ($query) {
                //           $query->where('status', 2);
                //       })
                //       ->orWhereHas('cbReportTwoInfo', function ($query) {
                //           $query->where('status', 2);
                //       });
                //   });
            })
            ->orWhere(function ($q) use ($signer) {
                $q->where('certificate_type', 1)
                  ->where('signer_id', $signer->id)
                  ->where('approval', 0);
                //   ->where(function ($subQ) {
                //       $subQ->whereHas('ibReportInfo', function ($query) {
                //           $query->where('status', 2);
                //       })
                //       ->orWhereHas('ibReportTwoInfo', function ($query) {
                //           $query->where('status', 2);
                //       });
                //   });
            })
            ->orWhere(function ($q) use ($signer) {
                $q->where('certificate_type', 2)
                  ->where('signer_id', $signer->id)
                  ->where('approval', 0)
                  ->where(function ($subQ) {
                      $subQ->whereHas('labReportInfo', function ($query) {
                          $query->where('status', 2);
                      })
                      ->orWhereHas('labReportTwoiInfo', function ($query) {
                          $query->where('status', 2);
                      });
                  });
            });
            
        
        //     $query->where('certificate_type', 1)
        //     ->whereHas('ibReportInfo', function ($query) {
        //         $query->where('status', 2);
        //     })
        //     ->orWhereHas('ibReportTwoInfo', function ($query) {
        //         $query->where('status', 2);
        //     })
        // ->orWhere('certificate_type', 0)
        //     ->whereHas('cbReportInfo', function ($query) {
        //         $query->where('status', 2);
        //     })
        //     ->orWhereHas('cbReportTwoInfo', function ($query) {
        //         $query->where('status', 2);
        //     })
        // ->orWhere('certificate_type', 2)
        //     ->whereHas('labReportInfo', function ($query) {
        //         $query->where('status', 2);
        //     })
        //     ->orWhereHas('labReportTwoiInfo', function ($query) {
        //         $query->where('status', 2);
        //     });

            
            $data = $query->get();

            // dd($data);
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
            return response()->json(['error' => 'signer info not found'], 404);
        }
    }

    public function apiGetSigners()
    {
        $signers = Signer::all();

        return response()->json([
            'signers'=> $signers,
         ]);
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
            // สร้าง URL สำหรับ sign_url
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
    
        // certificate_type 0=CB, 1=IB, 2=LAB
        $signAssessmentReportTransaction = SignAssessmentReportTransaction::find($request->id);

        // dd($signAssessmentReportTransaction,$signAssessmentReportTransaction->certificate_type);

        SignAssessmentReportTransaction::find($request->id)->update([
            'approval' => 1
        ]);


        if($signAssessmentReportTransaction->certificate_type == 2)
        {
            if($signAssessmentReportTransaction->report_type == 1){
                $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
                                ->whereNotNull('signer_id')
                                ->where('certificate_type',2)
                                ->where('report_type',1)
                                ->where('approval',0)
                                ->get();           

                if($signAssessmentReportTransactions->count() == 0){
                    $pdfService = new CreateLabAssessmentReportPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateLabAssessmentReportPdf();

                }   
            }else if($signAssessmentReportTransaction->report_type == 2)
            {
                $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
                                ->whereNotNull('signer_id')
                                ->where('certificate_type',2)
                                ->where('report_type',2)
                                ->where('approval',0)
                                ->get();           

                if($signAssessmentReportTransactions->count() == 0){
                    $pdfService = new CreateLabAssessmentReportTwoPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateLabReportTwoPdf();
                }   
            }
            // LAB

        }
        else if($signAssessmentReportTransaction->certificate_type == 0)
        {
            if($signAssessmentReportTransaction->report_type == 1){
                // CB
                $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
                            ->whereNotNull('signer_id')
                            ->where('certificate_type',0)
                            ->where('report_type',1)
                            ->where('approval',0)
                            ->get();           
                
                if($signAssessmentReportTransactions->count() == 0){
                    // $pdfService = new CreateCbAssessmentReportPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    // $pdfContent = $pdfService->generateCbAssessmentReportPdf();

                    // dd("ok");

                    $this->generateCbFinalReport($signAssessmentReportTransaction->report_info_id);
                } 
            }
            else if($signAssessmentReportTransaction->report_type == 2)
            {
               // CB รายงานที่2
               $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
               ->whereNotNull('signer_id')
               ->where('certificate_type',0)
               ->where('report_type',2)
               ->where('approval',0)
               ->get();           
   
                if($signAssessmentReportTransactions->count() == 0){
                    // $pdfService = new CreateCbAssessmentReportTwoPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    // $pdfContent = $pdfService->generateCbAssessmentReportTwoPdf();

                    $this->generateCbCarReport($signAssessmentReportTransaction->report_info_id);
                } 
            }

        }
        else if($signAssessmentReportTransaction->certificate_type == 1)
        {
            
            if($signAssessmentReportTransaction->report_type == 1)
            {
               
                  // IB
                  $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
                  ->whereNotNull('signer_id')
                  ->where('certificate_type',1)
                  ->where('report_type',1)
                  ->where('approval',0)
                  ->get();           
      
                if($signAssessmentReportTransactions->count() == 0){
                    $this->generateIbFinalReport($signAssessmentReportTransaction->report_info_id);
                } 
            }
            else if($signAssessmentReportTransaction->report_type == 2)
            {
                
                  // IB
                  $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
                  ->whereNotNull('signer_id')
                  ->where('certificate_type',1)
                  ->where('report_type',2)
                  ->where('approval',0)
                  ->get();  

                //   dd($signAssessmentReportTransaction->report_type,$signAssessmentReportTransactions->count());
      
                if($signAssessmentReportTransactions->count() == 0){
                    $this->generateIbCarReportTwo($signAssessmentReportTransaction->report_info_id);
                } 
            }

        }

                     
        
    }

    public function generateIbFinalReport($reportId)
    {
        try {
            // --- ส่วนเตรียมข้อมูล (เหมือนเดิม) ---
            $ibReportTemplate = IbReportTemplate::find($reportId);
            $assessment = $ibReportTemplate->certiIBSaveAssessment;
            $certi_ib = CertiIb::findOrFail($assessment->app_certi_ib_id);

            if (empty($ibReportTemplate->template)) {
                throw new \Exception('ไม่พบเนื้อหาของรายงานที่บันทึกไว้');
            }

            $htmlContent = $ibReportTemplate->template;

            // --- ส่วนเตรียม HTML ---
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);

            // ลบปุ่ม "เลือกผู้ลงนาม" ทั้งหมด
            $buttons = $xpath->query("//button[contains(@class, 'select-signer-btn')]");
            foreach ($buttons as $button) {
                $button->parentNode->removeChild($button);
            }

            // แปลง Checkbox เป็นสัญลักษณ์
            $checkboxes = $xpath->query('//input[@type="checkbox"]');
            foreach ($checkboxes as $checkbox) {
                $symbolText = $checkbox->hasAttribute('checked') ? '☑' : '☐';
                $symbolNode = $dom->createTextNode($symbolText);
                $checkbox->parentNode->replaceChild($symbolNode, $checkbox);
            }

            // --- ส่วนจัดการลายเซ็นและวันที่ ---
            $signerDivs = $xpath->query("//div[@data-signer-name]");
            foreach ($signerDivs as $signerDiv) {
                $signerName = trim($signerDiv->getAttribute('data-signer-name'));
                $signatureFileUrl = ''; // กำหนดค่าเริ่มต้นสำหรับ URL รูปภาพ

                // --- ส่วนจัดการรูปลายเซ็น (เหมือนเดิม) ---
                $signer = Signer::where('name', $signerName)->first();
                if ($signer && $signer->AttachFileAttachTo) {
                    $relativePathWithUploads = $this->getSignature($signer->AttachFileAttachTo);
                    $pathForStorage = str_replace('uploads/', '', $relativePathWithUploads);
                    $fullServerPath = Storage::disk('uploads')->path($pathForStorage);

                    if (File::exists($fullServerPath)) {
                        $signatureFileUrl = 'file:///' . str_replace('\\', '/', $fullServerPath);
                    } else {
                        Log::warning("Signature file not found for '{$signerName}' at path: {$fullServerPath}");
                    }
                } else {
                    Log::warning("Signer or attachment not found for name: '{$signerName}'");
                }

                $imgNodeList = $xpath->query("preceding-sibling::div/img", $signerDiv);
                if ($imgNodeList->length > 0) {
                    $imgNode = $imgNodeList->item(0);
                    $imgNode->setAttribute('src', $signatureFileUrl);
                    $imgNode->setAttribute('alt', 'ลายเซ็น ' . $signerName);
                }

                // --- [ส่วนที่เพิ่มใหม่] จัดการวันที่ลงนาม ---
                $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $reportId)
                    ->where('signer_name', $signerName)
                    ->where('certificate_type', 1)
                    ->where('report_type', 1)
                    ->where('approval', 1) // ตามโค้ดที่คุณให้มา
                    ->first();

                // ค้นหา <p> ที่มีคำว่า "วันที่" ภายใน div ของผู้ลงนามปัจจุบัน
                $dateNodeList = $xpath->query(".//p[starts-with(normalize-space(.), 'วันที่')]", $signerDiv);
                if ($dateNodeList->length > 0) {
                   
                    $dateNode = $dateNodeList->item(0);
                    $dateText = 'วันที่ '; // ค่าเริ่มต้น

                    if ($signAssessmentReportTransaction) {
                        // ถ้าเจอ transaction, ให้แปลงวันที่และนำมาต่อท้าย
                        $formattedDate = HP::formatDateThaiFull($signAssessmentReportTransaction->updated_at);
                        $dateText .= $formattedDate;
                    }
                    //  dd($dateText);
                    // อัปเดตเนื้อหาของ <p>
                    $dateNode->nodeValue = $dateText;
                }
            }
            // --- จบส่วนจัดการลายเซ็นและวันที่ ---

            $processedHtml = $dom->saveHTML();
            
            // --- ส่วนเตรียมข้อมูลสำหรับ Job (เหมือนเดิม) ---
            $footerTextLeft = '';
            $footerTextRight = 'FCI-AS06-01<br>01/10/2567';
            // use Carbon\Carbon;

            $footerTextRight = 'FCI-AS06-01<br>' . Carbon::now()->format('d/m') . '/' . (Carbon::now()->year + 543);


            $no = str_replace("RQ-", "", $certi_ib->app_no);
            $no = str_replace("-", "_", $no);
            $fullFileName = $ibReportTemplate->report_type . "_" . uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
            $outputPdfPath = Storage::disk('uploads')->path($fullFileName);
            $certi_ib_id = $certi_ib->id;
            $assessment_id = $assessment->id;
            
            $attachPath = '/files/applicants/check_files_ib/' . $no . '/';
            
            // --- ส่ง Job ไปสร้างไฟล์ PDF พร้อมพารามิเตอร์ที่ถูกต้อง (9 ตัว) ---
            GenerateIbFinalReportProcessOnePdf::dispatch(
                $processedHtml, 
                $outputPdfPath, 
                $footerTextLeft, 
                $footerTextRight,
                $fullFileName,
                $no,
                $certi_ib_id,
                $assessment_id,
                $attachPath
            );

            // --- ตอบกลับทันที (ถูกต้องแล้ว) ---
            return response()->json(['message' => 'ระบบกำลังสร้างรายงาน PDF ของคุณ โปรดรอสักครู่']);

        } catch (\Exception $e) {
            Log::error('Generate PDF from DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage()
            ], 500);
        }
    }

    public function generateIbCarReportTwo($reportId)
    {
        try {
            // --- ส่วนเตรียมข้อมูล (เหมือนเดิม) ---
            $ibReportTemplate = IbReportTemplate::find($reportId);
            $assessment = $ibReportTemplate->certiIBSaveAssessment;
            $certi_ib = CertiIb::findOrFail($assessment->app_certi_ib_id);

            // dd($assessment->id);

            if (empty($ibReportTemplate->template)) {
                throw new \Exception('ไม่พบเนื้อหาของรายงานที่บันทึกไว้');
            }

            $htmlContent = $ibReportTemplate->template;

            // --- ส่วนเตรียม HTML ---
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);

            // ลบปุ่ม "เลือกผู้ลงนาม" ทั้งหมด
            $buttons = $xpath->query("//button[contains(@class, 'select-signer-btn')]");
            foreach ($buttons as $button) {
                $button->parentNode->removeChild($button);
            }

            // แปลง Checkbox เป็นสัญลักษณ์
            $checkboxes = $xpath->query('//input[@type="checkbox"]');
            foreach ($checkboxes as $checkbox) {
                $symbolText = $checkbox->hasAttribute('checked') ? '☑' : '☐';
                $symbolNode = $dom->createTextNode($symbolText);
                $checkbox->parentNode->replaceChild($symbolNode, $checkbox);
            }

            // --- ส่วนจัดการลายเซ็นและวันที่ ---

            // $signerDivs = $xpath->query("//div[@data-signer-name]");
            // foreach ($signerDivs as $signerDiv) {
            //     $signerName = trim($signerDiv->getAttribute('data-signer-name'));
            //     $signatureFileUrl = ''; // กำหนดค่าเริ่มต้นสำหรับ URL รูปภาพ
            //     $signer = Signer::where('name', $signerName)->first();
            //     if ($signer && $signer->AttachFileAttachTo) {
            //         $relativePathWithUploads = $this->getSignature($signer->AttachFileAttachTo);
            //         $pathForStorage = str_replace('uploads/', '', $relativePathWithUploads);
            //         $fullServerPath = Storage::disk('uploads')->path($pathForStorage);

            //         if (File::exists($fullServerPath)) {
            //             $signatureFileUrl = 'file:///' . str_replace('\\', '/', $fullServerPath);
            //         } else {
            //             Log::warning("Signature file not found for '{$signerName}' at path: {$fullServerPath}");
            //         }
            //     } else {
            //         Log::warning("Signer or attachment not found for name: '{$signerName}'");
            //     }

            //     $imgNodeList = $xpath->query("preceding-sibling::div/img", $signerDiv);
            //     if ($imgNodeList->length > 0) {
            //         $imgNode = $imgNodeList->item(0);
            //         $imgNode->setAttribute('src', $signatureFileUrl);
            //         $imgNode->setAttribute('alt', 'ลายเซ็น ' . $signerName);
            //     }

            //     $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $reportId)
            //         ->where('signer_name', $signerName)
            //         ->where('certificate_type', 1)
            //         ->where('report_type', 2)
            //         ->where('approval', 1) // ตามโค้ดที่คุณให้มา
            //         ->first();

            //     $dateNodeList = $xpath->query(".//p[starts-with(normalize-space(.), 'วันที่')]", $signerDiv);
            //     if ($dateNodeList->length > 0) {
                   
            //         $dateNode = $dateNodeList->item(0);
            //         $dateText = 'วันที่ '; 

            //         if ($signAssessmentReportTransaction) {
            //             $formattedDate = HP::formatDateThaiFull($signAssessmentReportTransaction->updated_at);
            //             $dateText .= $formattedDate;
            //         }
            //         $dateNode->nodeValue = $dateText;
            //     }
            // }


            // --- ส่วนจัดการลายเซ็นและวันที่ (ฉบับแก้ไข) ---
            $signerDivs = $xpath->query("//div[@data-signer-name]");

            foreach ($signerDivs as $signerDiv) {
                $signerName = trim($signerDiv->getAttribute('data-signer-name'));
                $signatureFileUrl = ''; // กำหนดค่าเริ่มต้น

                // --- ส่วนจัดการรูปลายเซ็น (ใช้โค้ดเดิมได้เลยเพราะทำงานถูกต้อง) ---
                $signer = Signer::where('name', $signerName)->first();
                if ($signer && $signer->AttachFileAttachTo) {
                    $relativePathWithUploads = $this->getSignature($signer->AttachFileAttachTo);
                    $pathForStorage = str_replace('uploads/', '', $relativePathWithUploads);
                    $fullServerPath = Storage::disk('uploads')->path($pathForStorage);

                    if (File::exists($fullServerPath)) {
                        $signatureFileUrl = 'file:///' . str_replace('\\', '/', $fullServerPath);
                    } else {
                        Log::warning("Signature file not found for '{$signerName}' at path: {$fullServerPath}");
                    }
                } else {
                    Log::warning("Signer or attachment not found for name: '{$signerName}'");
                }

                $imgNodeList = $xpath->query("preceding-sibling::div/img", $signerDiv);
                if ($imgNodeList->length > 0) {
                    $imgNode = $imgNodeList->item(0);
                    $imgNode->setAttribute('src', $signatureFileUrl);
                    $imgNode->setAttribute('alt', 'ลายเซ็น ' . $signerName);
                }

                // --- [ส่วนที่แก้ไข] จัดการวันที่ลงนามให้รองรับทุก Layout ---
                $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $reportId)
                    ->where('signer_name', $signerName)
                    ->where('certificate_type', 1)
                    ->where('report_type', 2)
                    ->where('approval', 1)
                    ->first();

                $dateNode = null;

                // ตรวจสอบว่าเป็นผู้ลงนามคนที่ 4 หรือไม่ (โดยเช็คจาก id ของ td แม่)
                $parentTd = $xpath->query("ancestor::td[1]", $signerDiv)->item(0);
                if ($parentTd && $parentTd->getAttribute('id') === 'assessment_head') {
                    // ถ้าใช่, ให้หา <p> ที่มีคำว่า "วันที่" ใน <td> ที่อยู่ถัดไป
                    $dateNodeList = $xpath->query("following-sibling::td[1]//p[contains(., 'วันที่')]", $parentTd);
                    if ($dateNodeList->length === 0) {
                        // ถ้าหา p ไม่เจอ ให้หาแค่ td ถัดไปแล้วอัปเดตทั้ง td
                        $dateNodeList = $xpath->query("following-sibling::td[1]", $parentTd);
                    }
                } else {
                    // ถ้าเป็นผู้ลงนามคนอื่น (1-3), ให้หา <p> ที่มีคำว่า "วันที่" ภายใน div เดิม
                    $dateNodeList = $xpath->query(".//p[starts-with(normalize-space(.), 'วันที่')]", $signerDiv);
                }

                // อัปเดต Node ที่เจอด้วยวันที่ที่ถูกต้อง
                if ($dateNodeList && $dateNodeList->length > 0) {
                    $dateNode = $dateNodeList->item(0);
                    $dateText = 'วันที่ ';

                    if ($signAssessmentReportTransaction) {
                        $formattedDate = HP::formatDateThaiFull($signAssessmentReportTransaction->updated_at);
                        $dateText .= $formattedDate;
                    } else {
                        $dateText .= '...../...../.....'; // fallback
                    }
                    
                    // อัปเดตเนื้อหาของ Node
                    $dateNode->nodeValue = $dateText;
                }
            }
            // --- จบส่วนจัดการลายเซ็นและวันที่ ---


            // --- จบส่วนจัดการลายเซ็นและวันที่ ---

            $processedHtml = $dom->saveHTML();
            
            // --- ส่วนเตรียมข้อมูลสำหรับ Job (เหมือนเดิม) ---
            $footerTextLeft = '';
            // $footerTextRight = 'FCI-AS06-02<br>01/10/2567';
      

            $footerTextRight = 'FCI-AS07-01<br>' . Carbon::now()->format('d/m') . '/' . (Carbon::now()->year + 543);


            $no = str_replace("RQ-", "", $certi_ib->app_no);
            $no = str_replace("-", "_", $no);
            $fullFileName = $ibReportTemplate->report_type . "_" . uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
            $outputPdfPath = Storage::disk('uploads')->path($fullFileName);
            $certi_ib_id = $certi_ib->id;
            $assessment_id = $assessment->id;
        
            $attachPath = '/files/applicants/check_files_ib/' . $no . '/';

            // dd("before distacth work well");

            // $certiIBSaveAssessment = CertiIBSaveAssessment::find($assessment_id);
            // $certiIb = $certiIBSaveAssessment->CertiIBCostTo;
            // dd($certiIBSaveAssessment->id,$certiIb->id,$certi_ib_id);
            
            // --- ส่ง Job ไปสร้างไฟล์ PDF พร้อมพารามิเตอร์ที่ถูกต้อง (9 ตัว) ---
            GenerateIbCarReportTwoProcessOnePdf::dispatch(
                $processedHtml, 
                $outputPdfPath, 
                $footerTextLeft, 
                $footerTextRight,
                $fullFileName,
                $no,
                $certi_ib_id,
                $assessment_id,
                $attachPath
            );

            // --- ตอบกลับทันที (ถูกต้องแล้ว) ---
            return response()->json(['message' => 'ระบบกำลังสร้างรายงาน PDF ของคุณ โปรดรอสักครู่']);

        } catch (\Exception $e) {
            Log::error('Generate PDF from DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage()
            ], 500);
        }
    }

    public function generateCbFinalReport($reportId)
    {
        try {
            // --- ส่วนเตรียมข้อมูล (เหมือนเดิม) ---
            $cbReportTemplate = CbReportTemplate::find($reportId);
            $assessment = $cbReportTemplate->certiCBSaveAssessment;
            $certi_cb = CertiCb::findOrFail($assessment->app_certi_cb_id);

            if (empty($cbReportTemplate->template)) {
                throw new \Exception('ไม่พบเนื้อหาของรายงานที่บันทึกไว้');
            }

            $htmlContent = $cbReportTemplate->template;

            // --- ส่วนเตรียม HTML ---
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);

            // ลบปุ่ม "เลือกผู้ลงนาม" ทั้งหมด
            $buttons = $xpath->query("//button[contains(@class, 'select-signer-btn')]");
            foreach ($buttons as $button) {
                $button->parentNode->removeChild($button);
            }

            // แปลง Checkbox เป็นสัญลักษณ์
            $checkboxes = $xpath->query('//input[@type="checkbox"]');
            foreach ($checkboxes as $checkbox) {
                $symbolText = $checkbox->hasAttribute('checked') ? '☑' : '☐';
                $symbolNode = $dom->createTextNode($symbolText);
                $checkbox->parentNode->replaceChild($symbolNode, $checkbox);
            }

            // --- ส่วนจัดการลายเซ็นและวันที่ ---
            $signerDivs = $xpath->query("//div[@data-signer-name]");
            foreach ($signerDivs as $signerDiv) {
                $signerName = trim($signerDiv->getAttribute('data-signer-name'));
                $signatureFileUrl = ''; // กำหนดค่าเริ่มต้นสำหรับ URL รูปภาพ

                // --- ส่วนจัดการรูปลายเซ็น (เหมือนเดิม) ---
                $signer = Signer::where('name', $signerName)->first();
                if ($signer && $signer->AttachFileAttachTo) {
                    $relativePathWithUploads = $this->getSignature($signer->AttachFileAttachTo);
                    $pathForStorage = str_replace('uploads/', '', $relativePathWithUploads);
                    $fullServerPath = Storage::disk('uploads')->path($pathForStorage);

                    if (File::exists($fullServerPath)) {
                        $signatureFileUrl = 'file:///' . str_replace('\\', '/', $fullServerPath);
                    } else {
                        Log::warning("Signature file not found for '{$signerName}' at path: {$fullServerPath}");
                    }
                } else {
                    Log::warning("Signer or attachment not found for name: '{$signerName}'");
                }

                $imgNodeList = $xpath->query("preceding-sibling::div/img", $signerDiv);
                if ($imgNodeList->length > 0) {
                    $imgNode = $imgNodeList->item(0);
                    $imgNode->setAttribute('src', $signatureFileUrl);
                    $imgNode->setAttribute('alt', 'ลายเซ็น ' . $signerName);
                }

                // --- [ส่วนที่เพิ่มใหม่] จัดการวันที่ลงนาม ---
                $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $reportId)
                    ->where('signer_name', $signerName)
                    ->where('certificate_type', 0)
                    ->where('report_type', 1)
                    ->where('approval', 1) // ตามโค้ดที่คุณให้มา
                    ->first();

                // ค้นหา <p> ที่มีคำว่า "วันที่" ภายใน div ของผู้ลงนามปัจจุบัน
                $dateNodeList = $xpath->query(".//p[starts-with(normalize-space(.), 'วันที่')]", $signerDiv);
                if ($dateNodeList->length > 0) {
                   
                    $dateNode = $dateNodeList->item(0);
                    $dateText = 'วันที่ '; // ค่าเริ่มต้น

                    if ($signAssessmentReportTransaction) {
                        // ถ้าเจอ transaction, ให้แปลงวันที่และนำมาต่อท้าย
                        $formattedDate = HP::formatDateThaiFull($signAssessmentReportTransaction->updated_at);
                        $dateText .= $formattedDate;
                    }
                    //  dd($dateText);
                    // อัปเดตเนื้อหาของ <p>
                    $dateNode->nodeValue = $dateText;
                }
            }
            // --- จบส่วนจัดการลายเซ็นและวันที่ ---

            $processedHtml = $dom->saveHTML();
            
            // --- ส่วนเตรียมข้อมูลสำหรับ Job (เหมือนเดิม) ---
            $footerTextLeft = '';
            $footerTextRight = 'FCI-AS06-01<br>01/10/2567';

            $no = str_replace("RQ-", "", $certi_cb->app_no);
            $no = str_replace("-", "_", $no);
            $fullFileName = $cbReportTemplate->report_type . "_" . uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
            $outputPdfPath = Storage::disk('uploads')->path($fullFileName);
            $certi_cb_id = $certi_cb->id;
            $assessment_id = $assessment->id;
            
            $attachPath = '/files/applicants/check_files_cb/' . $no . '/';
            
            // --- ส่ง Job ไปสร้างไฟล์ PDF พร้อมพารามิเตอร์ที่ถูกต้อง (9 ตัว) ---
            GenerateCbFinalReportProcessOnePdf::dispatch(
                $processedHtml, 
                $outputPdfPath, 
                $footerTextLeft, 
                $footerTextRight,
                $fullFileName,
                $no,
                $certi_cb_id,
                $assessment_id,
                $attachPath
            );

            // --- ตอบกลับทันที (ถูกต้องแล้ว) ---
            return response()->json(['message' => 'ระบบกำลังสร้างรายงาน PDF ของคุณ โปรดรอสักครู่']);

        } catch (\Exception $e) {
            Log::error('Generate PDF from DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage()
            ], 500);
        }
    }


    public function generateCbCarReport($reportId)
    {
        try {
            // --- ส่วนเตรียมข้อมูล (เหมือนเดิม) ---
            $cbReportTemplate = CbReportTemplate::find($reportId);
            $assessment = $cbReportTemplate->certiCBSaveAssessment;
            $certi_cb = CertiCb::findOrFail($assessment->app_certi_cb_id);

            if (empty($cbReportTemplate->template)) {
                throw new \Exception('ไม่พบเนื้อหาของรายงานที่บันทึกไว้');
            }

            $htmlContent = $cbReportTemplate->template;

            // --- ส่วนเตรียม HTML ---
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);

            // ลบปุ่ม "เลือกผู้ลงนาม" ทั้งหมด
            $buttons = $xpath->query("//button[contains(@class, 'select-signer-btn')]");
            foreach ($buttons as $button) {
                $button->parentNode->removeChild($button);
            }

            // แปลง Checkbox เป็นสัญลักษณ์
            $checkboxes = $xpath->query('//input[@type="checkbox"]');
            foreach ($checkboxes as $checkbox) {
                $symbolText = $checkbox->hasAttribute('checked') ? '☑' : '☐';
                $symbolNode = $dom->createTextNode($symbolText);
                $checkbox->parentNode->replaceChild($symbolNode, $checkbox);
            }

            // --- ส่วนจัดการลายเซ็นและวันที่ ---
            $signerDivs = $xpath->query("//div[@data-signer-name]");
            foreach ($signerDivs as $signerDiv) {
                $signerName = trim($signerDiv->getAttribute('data-signer-name'));
                $signatureFileUrl = ''; // กำหนดค่าเริ่มต้นสำหรับ URL รูปภาพ

                // --- ส่วนจัดการรูปลายเซ็น (เหมือนเดิม) ---
                $signer = Signer::where('name', $signerName)->first();
                if ($signer && $signer->AttachFileAttachTo) {
                    $relativePathWithUploads = $this->getSignature($signer->AttachFileAttachTo);
                    $pathForStorage = str_replace('uploads/', '', $relativePathWithUploads);
                    $fullServerPath = Storage::disk('uploads')->path($pathForStorage);

                    if (File::exists($fullServerPath)) {
                        $signatureFileUrl = 'file:///' . str_replace('\\', '/', $fullServerPath);
                    } else {
                        Log::warning("Signature file not found for '{$signerName}' at path: {$fullServerPath}");
                    }
                } else {
                    Log::warning("Signer or attachment not found for name: '{$signerName}'");
                }

                $imgNodeList = $xpath->query("preceding-sibling::div/img", $signerDiv);
                if ($imgNodeList->length > 0) {
                    $imgNode = $imgNodeList->item(0);
                    $imgNode->setAttribute('src', $signatureFileUrl);
                    $imgNode->setAttribute('alt', 'ลายเซ็น ' . $signerName);
                }

                // --- [ส่วนที่เพิ่มใหม่] จัดการวันที่ลงนาม ---
                $signAssessmentReportTransaction = SignAssessmentReportTransaction::where('report_info_id', $reportId)
                    ->where('signer_name', $signerName)
                    ->where('certificate_type', 0)
                    ->where('report_type', 2)
                    ->where('approval', 1) // ตามโค้ดที่คุณให้มา
                    ->first();

                // ค้นหา <p> ที่มีคำว่า "วันที่" ภายใน div ของผู้ลงนามปัจจุบัน
                $dateNodeList = $xpath->query(".//p[starts-with(normalize-space(.), 'วันที่')]", $signerDiv);
                if ($dateNodeList->length > 0) {
                   
                    $dateNode = $dateNodeList->item(0);
                    $dateText = 'วันที่ '; // ค่าเริ่มต้น

                    if ($signAssessmentReportTransaction) {
                        // ถ้าเจอ transaction, ให้แปลงวันที่และนำมาต่อท้าย
                        $formattedDate = HP::formatDateThaiFull($signAssessmentReportTransaction->updated_at);
                        $dateText .= $formattedDate;
                    }
                    //  dd($dateText);
                    // อัปเดตเนื้อหาของ <p>
                    $dateNode->nodeValue = $dateText;
                }
            }
            // --- จบส่วนจัดการลายเซ็นและวันที่ ---

            $processedHtml = $dom->saveHTML();
            
            // --- ส่วนเตรียมข้อมูลสำหรับ Job (เหมือนเดิม) ---
            $footerTextLeft = '';
            $footerTextRight = 'FCI-AS06-01<br>01/10/2567';

            $no = str_replace("RQ-", "", $certi_cb->app_no);
            $no = str_replace("-", "_", $no);
            $fullFileName = $cbReportTemplate->report_type . "_" . uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
            $outputPdfPath = Storage::disk('uploads')->path($fullFileName);
            $certi_cb_id = $certi_cb->id;
            $assessment_id = $assessment->id;
            
            $attachPath = '/files/applicants/check_files_cb/' . $no . '/';
            
            // --- ส่ง Job ไปสร้างไฟล์ PDF พร้อมพารามิเตอร์ที่ถูกต้อง (9 ตัว) ---
            GenerateCbCarReportProcessOnePdf::dispatch(
                $processedHtml, 
                $outputPdfPath, 
                $footerTextLeft, 
                $footerTextRight,
                $fullFileName,
                $no,
                $certi_cb_id,
                $assessment_id,
                $attachPath
            );

            // --- ตอบกลับทันที (ถูกต้องแล้ว) ---
            return response()->json(['message' => 'ระบบกำลังสร้างรายงาน PDF ของคุณ โปรดรอสักครู่']);

        } catch (\Exception $e) {
            Log::error('Generate PDF from DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage()
            ], 500);
        }
    }

        public function getSignature($attach)
    {
        
        $existingFilePath = $attach->url;//  'files/signers/3210100336046/tvE4QPMaEC-date_time20241211_011258.png'  ;

        $attachPath = 'bcertify_attach/signer';
        $fileName = basename($existingFilePath) ;// 'tvE4QPMaEC-date_time20241211_011258.png';

        // ตรวจสอบไฟล์ใน disk uploads ก่อน
        if (Storage::disk('uploads')->exists("{$attachPath}/{$fileName}")) {
            // หากพบไฟล์ใน disk
            $storagePath = Storage::disk('uploads')->path("{$attachPath}/{$fileName}");
            $filePath = 'uploads/'.$attachPath .'/'.$fileName;
            // dd('File already exists in uploads',  $filePath);
            return $filePath;
        } else {
            // หากไม่พบไฟล์ใน disk ให้ไปตรวจสอบในเซิร์ฟเวอร์
            if (HP::checkFileStorage($existingFilePath)) {
                // ดึง path ของไฟล์ที่อยู่ในเซิร์ฟเวอร์
                $localFilePath = HP::getFileStoragePath($existingFilePath);

                // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
                if (file_exists($localFilePath)) {
                    // บันทึกไฟล์ลง disk 'uploads' โดยใช้ subfolder ที่กำหนด
                    $storagePath = Storage::disk('uploads')->putFileAs($attachPath, new \Illuminate\Http\File($localFilePath), $fileName);

                    // ตอบกลับว่าพบไฟล์และบันทึกสำเร็จ
                    $filePath = 'uploads/'.$attachPath .'/'.$fileName;
                    return $filePath;
                    // dd('File exists in server and saved to uploads', $storagePath);
                } else {
                    // กรณีไฟล์ไม่สามารถเข้าถึงได้ใน path เดิม
                    return null;
                }
            } else {
                // ตอบกลับกรณีไม่มีไฟล์ในเซิร์ฟเวอร์
                return null;
            }
        }
        
    }
    
    public function generateIbFinalReport_old($reportId, $templateType)
    {
        try {
            // --- ส่วนเตรียมข้อมูล (เหมือนเดิม) ---
            $ibReportTemplate = IbReportTemplate::find($reportId);
            $assessment = $ibReportTemplate->certiIBSaveAssessment;
            $certi_ib = CertiIb::findOrFail($assessment->app_certi_ib_id);

            if (empty($ibReportTemplate->template)) {
                throw new \Exception('ไม่พบเนื้อหาของรายงานที่บันทึกไว้');
            }

            $htmlContent = $ibReportTemplate->template;

            // --- ส่วนเตรียม HTML (เหมือนเดิม) ---
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);

            // ลบปุ่ม "เลือกผู้ลงนาม" ทั้งหมด
            $buttons = $xpath->query("//button[contains(@class, 'select-signer-btn')]");
            foreach ($buttons as $button) {
                $button->parentNode->removeChild($button);
            }

            // แปลง Checkbox เป็นสัญลักษณ์
            $checkboxes = $xpath->query('//input[@type="checkbox"]');
            foreach ($checkboxes as $checkbox) {
                $symbolText = $checkbox->hasAttribute('checked') ? '☑' : '☐';
                $symbolNode = $dom->createTextNode($symbolText);
                $checkbox->parentNode->replaceChild($symbolNode, $checkbox);
            }

            $processedHtml = $dom->saveHTML();
            
            // --- ส่วนเตรียมข้อมูลสำหรับ Job (เหมือนเดิม) ---
            $footerTextLeft = '';
            $footerTextRight = 'FCI-AS06-01<br>01/10/2567';

            $no = str_replace("RQ-", "", $certi_ib->app_no);
            $no = str_replace("-", "_", $no);
            $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
            $outputPdfPath = Storage::disk('uploads')->path($fullFileName);
            $certi_ib_id = $certi_ib->id;
            $assessment_app_certi_ib_id = $assessment->id;
            
            // Path สำหรับ FTP ที่จะส่งไปให้ Job
            $attachPath = '/files/applicants/check_files_ib/' . $no . '/';
            
            // --- [แก้ไข] ส่ง Job ไปสร้างไฟล์ PDF พร้อมพารามิเตอร์ที่ถูกต้อง (9 ตัว) ---
            GenerateIbCbFinalReportProcessOnePdf::dispatch(
                $processedHtml, 
                $outputPdfPath, 
                $footerTextLeft, 
                $footerTextRight,
                $fullFileName,
                $no,
                $certi_ib_id,
                $assessment_app_certi_ib_id,
                $attachPath // << เพิ่มพารามิเตอร์ตัวนี้เข้าไป
            );

            // --- ตอบกลับทันที (ถูกต้องแล้ว) ---
            return response()->json(['message' => 'ระบบกำลังสร้างรายงาน PDF ของคุณ โปรดรอสักครู่']);

        } catch (\Exception $e) {
            Log::error('Generate PDF from DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage()
            ], 500);
        }
    }


}
