<?php

namespace App\Http\Controllers\Certificate\Labs;

use HP;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\DataTables\DataTables;
use App\Mail\Tracking\AuditorsMail;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Mail;
use App\Models\Certificate\TrackingAuditors;
use App\Services\CreateTrackingIbMessageRecordPdf;
use App\Services\CreateTrackingLabMessageRecordPdf;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\MessageRecordTrackingTransaction;
use App\Services\CreateTrackingLabAssessmentReportOnePdf;
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;

class AuditorTrackingAssignmentController extends Controller
{
    public function index(Request $request)
    {
        
        $model = str_slug('auditor-tracking-assignment','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.labs.auditor-tracking-assignment.index');
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
        // $signer = Signer::where('user_register_id', $userId)->first();
        $cleanId = preg_replace('/[\s-]/', '', $user->reg_13ID);
        // ดึงข้อมูล signer โดยใช้ user_register_id
        // $signer = Signer::where('user_register_id', $userId)->first();
        $signer = Signer::where('tax_number', $cleanId)->first();
        // dd($signer->id);
        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($signer) {

            $filter_approval = $request->input('filter_state');
            $filter_certificate_type = $request->input('filter_certificate_type');
        
            // $query = MessageRecordTrackingTransaction::query();
            $query = SignAssessmentTrackingReportTransaction::query();
          
            // 
            $query->where('signer_id',$signer->id);

            
            if ($filter_approval) {
                $query->where('approval', $filter_approval);
            }else{
                $query->where('approval', 0);
            }

            // $query->where('approval',0);

            // dd($filter_certificate_type);
            // ->whereHas('trackingAuditor', function ($query) {
            //     $query->where('message_record_status', 2);
                
            // });
        
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

        
            // dd($query->get()->count())    ;
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    // dd($item->view_url,$item->signer_id,$item->id);
                    // สร้างปุ่มสองปุ่มที่ไม่มี action พิเศษ
                    $button1 = '<a href="' . $item->view_url . '" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>';
                    $button2 = '<a type="button" class="btn btn-warning btn-xs btn-sm sign-document" data-id="'.$item->signer_id.'"  data-transaction_id="'.$item->id.' "><i class="fa fa-file-text"></i></a>';
                    
                    return $button1 . ' ' . $button2; // รวมปุ่มทั้งสองเข้าด้วยกัน
                })
                ->editColumn('certificate_type', function ($item) {
                    // dd ($item);
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
                ->editColumn('tracking_ref', function ($item) {
                    return $item->app_id;
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

    public function signDocument(Request $request)
    {
        $signAssessmentTrackingReportTransaction = SignAssessmentTrackingReportTransaction::find($request->id);
        if ($signAssessmentTrackingReportTransaction->certificate_type == 0)
        {
            // CB
            SignAssessmentTrackingReportTransaction::find($request->id)
                    ->update([
                        'approval' => 1
                    ]);
                    
            $signAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$signAssessmentTrackingReportTransaction->tracking_report_info_id)
                    ->where('certificate_type',0)  
                    ->where('report_type',1)                      
                    ->whereNotNull('signer_id')
                    ->where('approval',0)
                    ->get();  
            if($signAssessmentTrackingReportTransactions->count() == 0)
            {
                dd('create cb pdf report');
            }                     
        }
        elseif ($signAssessmentTrackingReportTransaction->certificate_type == 1)
        {
            // IB
            SignAssessmentTrackingReportTransaction::find($request->id)
                    ->update([
                        'approval' => 1
                    ]);
            $signAssessmentTrackingReportTransaction = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$signAssessmentTrackingReportTransaction->tracking_report_info_id)
                    ->where('certificate_type',1)                        
                    ->whereNotNull('signer_id')
                    ->where('report_type',1)
                    ->where('approval',0)
                    ->get();  
            if($signAssessmentTrackingReportTransaction->count() == 0)
            {
                // dd('create ib pdf report');
                $trackingLabReportOne = $signAssessmentTrackingReportTransaction->trackingLabReportOne;
                dd($trackingLabReportOne);
                // $board = TrackingAuditors::find($signAssessmentTrackingReportTransaction->tracking_report_info_id);
                // $pdfService = new CreateTrackingIbMessageRecordPdf($board,"ia");
            }  
        }
        elseif ($signAssessmentTrackingReportTransaction->certificate_type == 2)
        {
            // LAB
            SignAssessmentTrackingReportTransaction::find($request->id)
                    ->update([
                        'approval' => 1
                    ]);
            $messageRecordTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$signAssessmentTrackingReportTransaction->tracking_report_info_id)
                                ->where('certificate_type',2)                        
                                ->whereNotNull('signer_id')
                                ->where('approval',0)
                                ->where('report_type',1)
                                ->get();           

            if($messageRecordTransactions->count() == 0)
            {

                
                $pdfService = new CreateTrackingLabAssessmentReportOnePdf(179);
                $pdfContent = $pdfService->generateLabAssessmentReportPdf();
                dd('create lab pdf report');
                // $board = TrackingAuditors::find($signAssessmentTrackingReportTransaction->tracking_report_info_id);
                // $pdfService = new CreateTrackingLabAssessmentReportOnePdf($board,"ia");
                // $pdfContent = $pdfService->generateBoardTrackingAuditorMessageRecordPdf();
                // $this->set_mail($board);
            }  
        }

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
        
    }

    public function set_mail($auditors) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        
        if( !empty($auditors->certificate_export_to->CertiLabTo)){
             $certi = $auditors->certificate_export_to->CertiLabTo;

             if(!empty($certi->DataEmailDirectorLABCC)){
                $mail_cc = $certi->DataEmailDirectorLABCC;
                array_push($mail_cc, auth()->user()->reg_email) ;
             }
          if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                    $data_app = [
                                  'title'          =>  'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                  'auditors'       => $auditors,
                                  'data'           => $certi,
                                  'export'         => $auditors->certificate_export_to  ,
                                  'url'            => $url.'certify/tracking-labs',
                                  'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'lab1@tisi.mail.go.th',
                                  'email_cc'       =>  !empty($mail_cc) ? $mail_cc : [],
                                  'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                              ];
          
                  $log_email =  HP::getInsertCertifyLogEmail(!empty($auditors->tracking_to->reference_refno)? $auditors->tracking_to->reference_refno:null,   
                                                              $auditors->tracking_id,
                                                              (new Tracking)->getTable(),
                                                              $auditors->id ?? null,
                                                              (new TrackingAuditors)->getTable(),
                                                              4,
                                                              'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                              view('mail.Tracking.auditors', $data_app),
                                                              !empty($certi->created_by)? $certi->created_by:null,   
                                                              !empty($certi->agent_id)? $certi->agent_id:null, 
                                                              auth()->user()->getKey(),
                                                              !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter : null,
                                                              $certi->email,
                                                              !empty($mail_cc) ? implode(",",$mail_cc) : null,
                                                              !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                            );

                $html = new AuditorsMail($data_app);
                $mail =  Mail::to($certi->email)->send($html);  

                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
           }  
        }
      }
  
}
