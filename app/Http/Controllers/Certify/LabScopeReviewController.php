<?php

namespace App\Http\Controllers\Certify;

use HP;
use App\Role;
use App\User;
use stdClass;
use Mpdf\Mpdf;
use App\RoleUser;
use App\LabHtmlTemplate;
use Mpdf\HTMLParserMode;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
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
        // $signer = Signer::where('user_register_id', $userId)->first();
        $cleanId = preg_replace('/[\s-]/', '', $user->reg_13ID);
        // ดึงข้อมูล signer โดยใช้ user_register_id
        // $signer = Signer::where('user_register_id', $userId)->first();
        $signer = Signer::where('tax_number', $cleanId)->first();

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

        // $certi_lab = CertiLab::find($request->id);


        // $pdfService = new CreateLabScopePdf($certi_lab);
        // $pdfContent = $pdfService->generatePdf();

        // $json = $this->copyScopeLabFromAttachement($certi_lab);
        // $copiedScopes = json_decode($json, true);

        // Report::where('app_certi_lab_id',$certi_lab->id)->update([
        //     'file_loa' =>  $copiedScopes[0]['attachs'],
        //     'file_loa_client_name' =>  $copiedScopes[0]['file_client_name']
        // ]);
  


        // $checkExportMapreqs = $certi_lab->certi_lab_export_mapreq_to;

        // if($checkExportMapreqs != null){
        //     $exportMapreqs =$checkExportMapreqs->certilab_export_mapreq_group_many;
        
        //     if($exportMapreqs->count() !=0 )
        //     {
        //         $certiLabIds = $exportMapreqs->pluck('app_certi_lab_id')->toArray();
        //         CertLabsFileAll::whereIn('app_certi_lab_id',$certiLabIds)
        //         ->whereNotNull('attach_pdf')
        //         ->update([
        //             'state' => 0
        //         ]);
        //     }
    
        //     CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)
        //         ->orderBy('id', 'desc') // เรียงตาม id ล่าสุด
        //         ->first()->update([
        //             'attach_pdf' => $copiedScopes[0]['attachs'],
        //             'attach_pdf_client_name' => $copiedScopes[0]['file_client_name'],
        //             'state' => 1
        //         ]);
        // }



    }

    

    
 public function exportScopePdf($id,$labHtmlTemplate)
    {
        $htmlPages = json_decode($labHtmlTemplate->html_pages);

        if (!is_array($htmlPages)) {
          
            return response()->json(['message' => 'Invalid or empty HTML content received.'], 400);
        }
        // กรองหน้าเปล่าออก (โค้ดเดิมที่เพิ่มไป)
        $filteredHtmlPages = [];
        foreach ($htmlPages as $pageHtml) {
            $trimmedPageHtml = trim(strip_tags($pageHtml, '<img>'));
            if (!empty($trimmedPageHtml)) {
                $filteredHtmlPages[] = $pageHtml;
            }
        }
  
        if (empty($filteredHtmlPages)) {
            return response()->json(['message' => 'No valid HTML content to export after filtering empty pages.'], 400);
        }
        $htmlPages = $filteredHtmlPages;

        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')];

        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
            'dejavusans' => [
                'R' => "DejaVuSans.ttf",
                'B' => "DejaVuSans-Bold.ttf",
                'I' => "DejaVuSerif-Italic.ttf",
                'BI' => "DejaVuSerif-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA'              => $type == 'F' ? true : false,
            'PDFAauto'          => $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => 15,
            'fontDir'           => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'          => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'      => 'thsarabunnew',
            'fontdata_fallback' => ['dejavusans', 'freesans', 'arial'],
            'margin_left'       => 13,
            'margin_right'      => 13,
            'margin_top'        => 10,
            'margin_bottom'     => 0,
            // 'tempDir'           => sys_get_temp_dir(),
        ]);

    
        // Log::info('MPDF Temp Dir: ' . $tempDirPath);

        $stylesheet = file_get_contents(public_path('css/pdf-css/cb.css'));
        $mpdf->WriteHTML($stylesheet, 1);

        $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, [23, 23], [170, 12]);
        $mpdf->showWatermarkImage = true;

        // --- เพิ่ม Watermark Text "DRAFT" ตรงนี้ ---
        // $mpdf->SetWatermarkText('DRAFT');
        // $mpdf->showWatermarkText = true; // เปิดใช้งาน watermark text
        // $mpdf->watermark_font = 'thsarabunnew'; // กำหนด font (ควรใช้ font ที่โหลดไว้แล้ว)
        // $mpdf->watermarkTextAlpha = 0.1;

$selectedCertiLab = CertiLab::find($id);

          
           $subGroup = $selectedCertiLab->subgroup;
          

            // $appCertiMail = CertiEmailLt::where('certi',$subGroup)->where('roles',1)->pluck('admin_group_email')->toArray();

       

       
            // //   $groupAdminUsers = User::whereIn('reg_email',$appCertiMail)->get();

            //    $groupAdminUsers = DB::table('user_register')->where('reg_email', $appCertiMail)->get();    

            //     //    dd($groupAdminUsers);
            // $firstSignerGroups = [];
            // if(count($groupAdminUsers) != 0){
            //      $allReg13Ids = [];
            //      foreach ($groupAdminUsers as $groupAdminUser) {
            //         $reg13Id = str_replace('-', '', $groupAdminUser->reg_13ID);
            //         $allReg13Ids[] = $reg13Id;
            //     }

            //     $firstSignerGroups = Signer::whereIn('tax_number',$allReg13Ids)->get();
            // }

            // $user =  auth()->user();
        //   $targetRoleId = 22;
                   // ผู้อำนวยการกลุ่ม สก. (LAB) 22
            $role = Role::where('name','ผู้อำนวยการกลุ่ม สก. (LAB)')->first();
            $targetRoleId = $role->id;
            $userRunrecnos = RoleUser::where('role_id', $targetRoleId)->pluck('user_runrecno');
            $groupAdminUsers = User::whereIn('runrecno', $userRunrecnos)->where('reg_subdepart',$selectedCertiLab->subgroup)->get();


            $firstSignerGroups = [];
            if(count($groupAdminUsers) != 0){
                 $allReg13Ids = [];
                 foreach ($groupAdminUsers as $groupAdminUser) {
                    $reg13Id = str_replace('-', '', $groupAdminUser->reg_13ID);
                    $allReg13Ids[] = $reg13Id;
                }

                $firstSignerGroups = Signer::whereIn('tax_number',$allReg13Ids)->get();
            }




$attach1 = !empty($firstSignerGroups->first()->AttachFileAttachTo) ? $firstSignerGroups->first()->AttachFileAttachTo : null;

  $sign_url1 = $this->getSignature($attach1);


$footerHtml = '
<div width="100%" style="display:inline;line-height:12px">

    <div style="display:inline-block;line-height:16px;float:left;width:70%;">
      <span style="font-size:20px;">กระทรวงอุตสาหกรรม สํานักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</span><br>
      <span style="font-size: 16px">(Ministry of Industry, Thai Industrial Standards Institute)</span>
    </div>

    <div style="display: inline-block; width: 15%;float:right;width:25%">
            <img src="' . $sign_url1 . '" style="height:40px;">
    </div>

    <div width="100%" style="display:inline;text-align:center">
      <span>หน้าที่ {PAGENO}/{nbpg}</span>
    </div>
</div>';

// แล้วนำไปกำหนดให้ mPDF เป็น Footer
$mpdf->SetHTMLFooter($footerHtml);

        foreach ($htmlPages as $index => $pageHtml) {
            if ($index > 0) {
                $mpdf->AddPage();
            }
            $mpdf->WriteHTML($pageHtml,HTMLParserMode::HTML_BODY);
        }

    //  $mpdf->Output('', 'S');
    //  $title = "mypdf.pdf";
    //  $mpdf->Output($title, "I");  

      $app_certi_lab = CertiLab::find($id);
      $no = str_replace("RQ-", "", $app_certi_lab->app_no);
      $no = str_replace("-", "_", $no);
  
      $attachPath = '/files/applicants/check_files/' . $no . '/';
      $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
  
      // สร้างไฟล์ชั่วคราว
      $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
  
      // บันทึก PDF ไปยังไฟล์ชั่วคราว
      $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);
  
      // ใช้ Storage::putFileAs เพื่อย้ายไฟล์
      Storage::putFileAs($attachPath, new \Illuminate\Http\File($tempFilePath), $fullFileName);
  
      $storePath = $no  . '/' . $fullFileName;
        $fileSection = "61";
        if($app_certi_lab->lab_type == 3){
           $fileSection = "61";
        }else if($app_certi_lab->lab_type == 4){
           $fileSection = "62";
        }
    //   dd($fileSection);
      $certi_lab_attach = new CertiLabAttachAll();
      $certi_lab_attach->app_certi_lab_id = $id;
      $certi_lab_attach->file_section     = $fileSection;
      $certi_lab_attach->file             = $storePath;
      $certi_lab_attach->file_client_name = $no . '_scope_'.now()->format('Ymd_His').'.pdf';
      $certi_lab_attach->token            = str_random(16);
      $certi_lab_attach->default_disk = config('filesystems.default');
      $certi_lab_attach->save();

    }

      public function getSignature($attach)
    {
        
        $existingFilePath = $attach->url;//  'files/signers/3210100336046/tvE4QPMaEC-date_time20241211_011258.png'  ;

        $attachPath = 'bcertify_attach/signer';
        $fileName = basename($existingFilePath) ;// 'tvE4QPMaEC-date_time20241211_011258.png';
        // dd($existingFilePath);

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