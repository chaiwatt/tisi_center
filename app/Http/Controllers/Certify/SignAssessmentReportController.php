<?php

namespace App\Http\Controllers\Certify;

use HP;
use DB; 

use HP_DGA;
use QrCode;
use App\User;
use Storage; 
use App\Http\Requests;

use App\CertificateExport;
use Illuminate\Http\Request;
use  App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;
use App\Models\Basic\SubDepartment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use App\Models\Certify\SendCertificates;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\SignCertificateOtp;

use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\SendCertificateLists;
use App\Services\CreateCbAssessmentReportPdf;
use App\Services\CreateIbAssessmentReportPdf;
use App\Models\Certify\SendCertificateHistory;
use App\Services\CreateLabAssessmentReportPdf;
use App\Models\Certify\SignCertificateConfirms;
use App\Models\Certify\MessageRecordTransaction;
use App\Services\CreateCbAssessmentReportTwoPdf;
use App\Services\CreateIbAssessmentReportTwoPdf;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Services\CreateLabAssessmentReportTwoPdf;
use App\Models\Certify\SignAssessmentReportTransaction;

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
        $signer = Signer::where('user_register_id', $userId)->first();

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
                  ->where('approval', 0)
                  ->where(function ($subQ) {
                      $subQ->whereHas('cbReportInfo', function ($query) {
                          $query->where('status', 2);
                      })
                      ->orWhereHas('cbReportTwoInfo', function ($query) {
                          $query->where('status', 2);
                      });
                  });
            })
            ->orWhere(function ($q) use ($signer) {
                $q->where('certificate_type', 1)
                  ->where('signer_id', $signer->id)
                  ->where('approval', 0)
                  ->where(function ($subQ) {
                      $subQ->whereHas('ibReportInfo', function ($query) {
                          $query->where('status', 2);
                      })
                      ->orWhereHas('ibReportTwoInfo', function ($query) {
                          $query->where('status', 2);
                      });
                  });
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
            return response()->json(['error' => 'ไม่พบข้อมูล signer'], 404);
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
                    $pdfService = new CreateCbAssessmentReportPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateCbAssessmentReportPdf();
                } 
            }
            else if($signAssessmentReportTransaction->report_type == 2)
            {
               // CB
               $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$signAssessmentReportTransaction->report_info_id)
               ->whereNotNull('signer_id')
               ->where('certificate_type',0)
               ->where('report_type',2)
               ->where('approval',0)
               ->get();           
   
                if($signAssessmentReportTransactions->count() == 0){
                    $pdfService = new CreateCbAssessmentReportTwoPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateCbAssessmentReportTwoPdf();
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
                    $pdfService = new CreateIbAssessmentReportPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateIbAssessmentReportPdf();
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
      
                if($signAssessmentReportTransactions->count() == 0){
                    $pdfService = new CreateIbAssessmentReportTwoPdf($signAssessmentReportTransaction->report_info_id,"ia");
                    $pdfContent = $pdfService->generateIbAssessmentReportTwoPdf();
                } 
            }

        }

                     
        
    }



}
