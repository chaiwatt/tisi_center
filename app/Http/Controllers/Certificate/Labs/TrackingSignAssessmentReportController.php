<?php

namespace App\Http\Controllers\Certificate\Labs;

use HP;
use App\AttachFile;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Services\CreateTrackingLabReportPdf;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingInspection;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;

class TrackingSignAssessmentReportController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'files/sendcertificatelists';
    }

    public function index()
    {
        $model = str_slug('tracking_assessment_report_assignment','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.labs.tracking-assessment-report-assignment.index');
        }
        abort(403);

    }

    public function dataList(Request $request)
    {
        // dd('ok');
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
        
            $query = SignAssessmentTrackingReportTransaction::query();
            $query->where('signer_id',$signer->id)
                ->whereHas('trackingLabReportInfo', function ($query) {
                    $query->whereHas('trackingAssessment', function ($query) {
                        $query->where('degree', 4);
                    });
                });

         


   
            if ($filter_approval) {
                $query->where('approval', $filter_approval);
            }else{
                $query->whereNull('approval');
            }
            
            if ($filter_certificate_type !== null) {
                
                $query->where('certificate_type', $filter_certificate_type);
            }
        
            // $aa= $query->get();
            // dd($aa,$filter_certificate_type);

            // dd($filter_approval,$query->get());
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

         
            // dd($query->get());
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

        // dd($signer);

        // ตรวจสอบว่า AttachFileAttachTo มีข้อมูลหรือไม่
        $attach = !empty($signer->AttachFileAttachTo) ? $signer->AttachFileAttachTo : null;

        if ($attach !== null) {
            // สร้าง URL สำหรับ sign_url
            $sign_url = url('funtions/get-view/' . $attach->url . '/' . (!empty($attach->filename) ? $attach->filename : basename($attach->url)));
        } else {
            $sign_url = null; // กรณีที่ไม่มีไฟล์แนบ
        }

        // dd($sign_url);
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

        $signAssessmentTrackingReportTransaction = SignAssessmentTrackingReportTransaction::find($request->id);

        if($signAssessmentTrackingReportTransaction->certificate_type == 2)
        {
            SignAssessmentTrackingReportTransaction::find($request->id)->update([
                'approval' => 1
            ]);
        
            $signAssessmentReportTransaction = SignAssessmentTrackingReportTransaction::find($request->id);
            $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$signAssessmentReportTransaction->report_info_id)
                                    ->where('certificate_type',2)                    
                                    ->whereNotNull('signer_id')
                                    ->where('approval',0)
                                    ->get();           
    
            if($signAssessmentReportTransactions->count() == 0){
                $pdfService = new CreateTrackingLabReportPdf($signAssessmentReportTransaction->tracking_report_info_id,"ia");
                $pdfContent = $pdfService->generateTrackingLabReportPdf();
    
                $this->downloadScopeAndReUpload($request->id);
            }  
        }
        
                      
        
    }

    public function downloadScopeAndReUpload($id)
    {
        $signAssessmentReportTransaction = SignAssessmentTrackingReportTransaction::find($id);
        $appId = $signAssessmentReportTransaction->app_id;
        $certiLab = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;

        $certiLabFileAll = CertLabsFileAll::where('app_certi_lab_id',$certiLab->id)->first();

        $filePath = 'files/applicants/check_files/' . $certiLabFileAll->attach_pdf ;

        $localFilePath = HP::downloadFileFromTisiCloud($filePath);

        $inspection = TrackingInspection::where('reference_refno',$appId)->first();

        $check = AttachFile::where('systems','Center')
        ->where('ref_id',$inspection->id)
        ->where('section','file_scope')
        ->first();
        if($check != null)
        {
            $check->delete();
        }

        // dd($localFilePath);
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $localFilePath,      // Path ของไฟล์
            basename($localFilePath), // ชื่อไฟล์
            mime_content_type($localFilePath), // MIME type
            null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
            true                // เป็นไฟล์ที่ valid แล้ว
        );

        $attach_path = "files/trackinglabs";
        // ใช้ไฟล์ที่จำลองในการอัปโหลด
        HP::singleFileUploadRefno(
            $uploadedFile,
            $attach_path.'/'.$inspection->reference_refno,
            ( $tax_number),
            (auth()->user()->FullName ?? null),
            'Center',
            (  (new TrackingInspection)->getTable() ),
            $inspection->id,
            'file_scope',
            null
        );


    }

}
