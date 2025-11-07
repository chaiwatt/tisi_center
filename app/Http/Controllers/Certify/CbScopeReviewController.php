<?php

namespace App\Http\Controllers\Certify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CbScopeReviewController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('lab_scope_review','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.cb.cb-scope-review.index');
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
        
            $query = CertiCb::query();
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
                // $report = Report::where('app_certi_lab_id',$item->id)->first();
               
                $item->view_url = "";
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
       
        // dd($request->all())
        
        $certi_lab = CertiLab::find($request->id);

        $isExported = $certi_lab->certi_lab_export_mapreq_to;
       
        
        if($isExported !== null)
        {

            $lab_ability = "test";
            if($certi_lab->lab_type == "4")
            {
                $lab_ability = "calibrate";
            }

            $ssoUser = DB::table('sso_users')->where('username', $certi_lab->tax_id)->first();  

            $labHtmlTemplate = LabHtmlTemplate::where('user_id', $ssoUser->id)
                        ->where('according_formula',$certi_lab->standard_id)
                        ->where('purpose',$certi_lab->purpose_type)
                        ->where('lab_ability',$lab_ability)
                        ->where('app_certi_lab_id',$certi_lab->id)
                        ->first();


            $certificateExport = CertificateExport::where('accereditatio_no', $certi_lab->accereditation_no)->first();
            if (!$certificateExport) { return 'CertificateExport not found'; }

            $report = Report::where('app_certi_lab_id', $certi_lab->id)->first();
            if (!$report) { return 'Report not found'; }


            // 2. เตรียมข้อความ "ใหม่"
            $newAccreditationTh = $certificateExport->accereditatio_no;
            $newAccreditationEn = '(' . $certificateExport->accereditatio_no_en . ')';
            $newCertNoValue     = $certificateExport->certificate_no;
            $newStartDateStrings = $this->formatDateStrings($report->start_date, 'start');
            $newEndDateStrings   = $this->formatDateStrings($report->end_date, 'end');

            // (ในอนาคตควรดึงค่า issue no มาจาก $report หรือ $certificateExport)
            $issueNo = "01"; 


            // 3. Decode JSON ที่มีหลายหน้าออกมาเป็น Array
            $allHtmlPages = json_decode($labHtmlTemplate->html_pages, true);
            $updatedPages = []; // เตรียม Array ว่างสำหรับเก็บหน้าที่แก้ไขแล้ว

            // 4. วนลูปเพื่อแก้ไข HTML ในแต่ละหน้า
            foreach ($allHtmlPages as $htmlContent) {
                
                // 4.1) อ่านและบันทึกสถานะของ Checkbox จาก HTML เดิม (ที่เป็น string) เก็บไว้ก่อน
                $originallyCheckedLabels = [];
                preg_match_all('/<input[^>]+?checked[^>]*?>\s*<span[^>]*?>([^<\s]+)/is', $htmlContent, $matches);
                if (!empty($matches[1])) {
                    $originallyCheckedLabels = $matches[1]; // จะได้ค่าเป็น Array เช่น ['ถาวร']
                }

                // 4.2) ห่อหุ้ม HTML ด้วยโครงสร้าง HTML5 เพื่อป้องกัน Tag หาย (เช่น <input>)
                $dom = new \DOMDocument('1.0', 'utf-8');
                $html5Wrapper = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>' . $htmlContent . '</body></html>';


                
                libxml_use_internal_errors(true);
                $dom->loadHTML($html5Wrapper);
                libxml_clear_errors();

                $xpath = new \DOMXPath($dom);

                // 4.3) สร้างฟังก์ชันสำหรับ "แทนที่ข้อความทั้งหมด" ใน node ที่ต้องการ
                $replaceNodeContent = function(string $class, string $newValue) use ($xpath) {
                    $node = $xpath->query("//span[contains(@class, '{$class}')]")->item(0);
                    if ($node) {
                        $node->nodeValue = trim($newValue);
                    }
                };

                // 5) เรียกใช้ฟังก์ชันเพื่อแทนที่ข้อมูลทั้งหมด
                
                // 5.1) แทนที่ Issue No. (ทั้ง th และ en)
                $replaceNodeContent('issue_no', $issueNo);
                $replaceNodeContent('issue_no_en', $issueNo);

                // 5.2) แทนที่วันที่ (แบบเต็มข้อความ)
                $replaceNodeContent('issue_from_date_th', $newStartDateStrings['th']);
                $replaceNodeContent('issue_from_date_en', $newStartDateStrings['en']);
                $replaceNodeContent('issue_to_date_th', $newEndDateStrings['th']);
                $replaceNodeContent('issue_to_date_en', $newEndDateStrings['en']);

                // 5.3) แทนที่ Accreditation No. (แบบเต็มข้อความ)
                $replaceNodeContent('accreditation_no_th', $newAccreditationTh);
                $replaceNodeContent('accreditation_no_en', $newAccreditationEn);
                
                // 5.4) แทนที่ Certificate No. (แบบบางส่วน)
                $certThNode = $xpath->query("//span[contains(@class, 'certificate_no_th')]")->item(0);
                if ($certThNode) {
                    $certThNode->nodeValue = preg_replace('/\d{1,2}-[A-Z]{2}\d{4}/', $newCertNoValue, $certThNode->nodeValue);
                }
                $certEnNode = $xpath->query("//span[contains(@class, 'certificate_no_en')]")->item(0);
                if ($certEnNode) {
                    $certEnNode->nodeValue = preg_replace('/\d{1,2}-[A-Z]{2}\d{4}/', $newCertNoValue, $certEnNode->nodeValue);
                }

                
                // 7) บันทึก HTML ที่แก้ไขสมบูรณ์แล้ว
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $cleanedHtml = '';
                foreach ($bodyNode->childNodes as $childNode) {
                    $cleanedHtml .= $dom->saveHTML($childNode);
                }
                
                $mpdfCompatibleHtml = str_replace(
                    'type="checkbox" checked', 
                    'type="checkbox" checked="checked"', 
                    $cleanedHtml
                );
                
                // 9) เก็บ HTML ที่พร้อมสำหรับ mPDF ลงใน Array
                $updatedPages[] = $mpdfCompatibleHtml;

             
                // $updatedPages[] = $cleanedHtml;
            }


            // dd($updatedPages);
            // 8) บันทึก Array ที่มีทุกหน้าที่แก้ไขแล้วกลับไปที่ฐานข้อมูล
            $labHtmlTemplate->html_pages = json_encode($updatedPages, JSON_UNESCAPED_UNICODE);
            $labHtmlTemplate->save();


            $this->exportScopePdf($certi_lab->id,$labHtmlTemplate);
        

        
            $json = $this->copyScopeLabFromAttachement($certi_lab);
            $copiedScopes = json_decode($json, true);

            Report::where('app_certi_lab_id',$certi_lab->id)->update([
                'file_loa' =>  $copiedScopes[0]['attachs'],
                'file_loa_client_name' =>  $copiedScopes[0]['file_client_name']
            ]);

            $exportMapreqs = $certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many;


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

}
