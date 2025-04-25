<?php

namespace App\Http\Controllers\Certify;

use HP;
use stdClass;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\DataTables\DataTables;
use App\Services\CreateLabScopePdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Certify\Applicant\Report;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\Applicant\CertiLabAttachAll;

class LabScopeReviewController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/sendcertificatelists';
    }

    public function index(Request $request)
    {
        $model = str_slug('lab_scope_review','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.lab-scope-review.index');
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

        // dd($signer);

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if ($signer) {
            $filter_approval = $request->input('filter_state');
            $filter_certificate_type = $request->input('filter_certificate_type');
        
            $query = CertiLab::query();
            $query->where('scope_view_signer_id',$signer->id);
            $query->whereHas('report_to');
            
        
            if ($filter_approval) {
                // dd('ลงนามแล้ว');
                $query->where('scope_view_status', $filter_approval);
            }else{
                // dd('รอดำเนินการ');
                $query->whereNull('scope_view_status');
            }
      
            if ($filter_certificate_type !== null) {
                
                $query->where('lab_type', $filter_certificate_type);
            }
        
            $config = HP::getConfig();
            $url  =   $config->url_center;
            $data = $query->get();
            $data = $data->map(function($item, $index)  use ($url,$signer){
                // dd($item->lab_type);
                $item->DT_Row_Index = $index + 1;

                // แปลง certificate_type เป็นข้อความ
                switch ($item->lab_type) {
                    case 1:
                        $item->certificate_type = 'IB';
                        break;
                    case 2:
                        $item->certificate_type = 'CB';
                        break;
                    case 3 || 4:
                        $item->certificate_type = 'LAB';
                        break;
                    default:
                        $item->certificate_type = 'Unknown';
                }

                // แปลง approval เป็นข้อความ
                $item->approval = $item->scope_view_status == null ? 'รอดำเนินการ' : 'ลงนามเรียบร้อย';
                $report = Report::where('app_certi_lab_id',$item->id)->first();
               
                $item->view_url = $url. '/certify/check/file_client/'.$report->file_loa .'/'.$report->file_loa_client_name;
                $item->signer_name = $signer->name;
                $item->signer_position = $signer->position;
                $item->signer_id = $signer->id;

                return $item;
            });

            $data = $data->sortByDesc('id')->values(); 
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    // สร้างปุ่มสองปุ่มที่ไม่มี action พิเศษ
                    $button1 = '<a href="' . $item->view_url . '" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>';
                    $button2 = '<a type="button" class="btn btn-warning btn-xs btn-sm sign-document" data-id="'.$item->signer_id.'"  data-app_id="'.$item->id.' "><i class="fa fa-file-text"></i></a>';
                    
                    return $button1 . ' ' . $button2; // รวมปุ่มทั้งสองเข้าด้วยกัน
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
        CertiLab::find($request->id)->update([
            'scope_view_status' => 1
        ]);

        $certi_lab = CertiLab::find($request->id);

        $pdfService = new CreateLabScopePdf($certi_lab);
        $pdfContent = $pdfService->generatePdf();

        $json = $this->copyScopeLabFromAttachement($certi_lab);
        $copiedScopes = json_decode($json, true);

        Report::where('app_certi_lab_id',$certi_lab->id)->update([
            'file_loa' =>  $copiedScopes[0]['attachs'],
            'file_loa_client_name' =>  $copiedScopes[0]['file_client_name']
        ]);
        // dd($certi_lab);
        //เคลียร์ state ไฟล์
        // dd($certi_lab->id);


        $checkExportMapreqs = $certi_lab->certi_lab_export_mapreq_to;

        if($checkExportMapreqs != null){
            $exportMapreqs =$checkExportMapreqs->certilab_export_mapreq_group_many;
        
            if($exportMapreqs->count() !=0 )
            {
                $certiLabIds = $exportMapreqs->pluck('app_certi_lab_id')->toArray();
                CertLabsFileAll::whereIn('app_certi_lab_id',$certiLabIds)
                ->whereNotNull('attach_pdf')
                ->update([
                    'state' => 0
                ]);
            }
    
            CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)
                ->orderBy('id', 'desc') // เรียงตาม id ล่าสุด
                ->first()->update([
                    'attach_pdf' => $copiedScopes[0]['attachs'],
                    'attach_pdf_client_name' => $copiedScopes[0]['file_client_name'],
                    'state' => 1
                ]);
        }
        

    }

    public function copyScopeLabFromAttachement($app)
    {
        $copiedScoped = null;
        $fileSection = null;

        if($app->lab_type == 3){
           $fileSection = "61";
        }else if($app->lab_type == 4){
           $fileSection = "62";
        }

        $latestRecord = CertiLabAttachAll::where('app_certi_lab_id', $app->id)
        ->where('file_section', $fileSection)
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();

        $existingFilePath = 'files/applicants/check_files/' . $latestRecord->file ;

        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files/'.$no.'/';

            if (file_exists($localFilePath)) {
                $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                $filePath = $attach_path . $dlName;
                if (Storage::disk('ftp')->exists($filePath)) {
                    $list  = new  stdClass;
                    $list->attachs =  $no.'/'.$dlName;
                    $list->file_client_name =  $dlName;
                    $scope[] = $list;
                    $copiedScoped = json_encode($scope);
                } 
                unlink($localFilePath);
            }
        }

        return $copiedScoped;
    }
}