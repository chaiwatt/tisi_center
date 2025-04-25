<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use HP;
use DB;
use Storage;

use App\CertificateExport;
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certify\ApplicantIB\CertiIBExport; 

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CertLabsFileAll;
use Mpdf\Mpdf;
class CertificateController extends Controller
{
  
    public function index(Request $request)
    {
        $sort = $request->get('sort');
        return view('report.certificate.index', compact('sort'));
    }

    public function data_list(Request $request)
    {
        
        $sort = $request->input('sort');
        $is_sort = in_array($sort, ['CB', 'IB', 'Lab-Test', 'Lab-Calibrate']);
        if($is_sort){
            if($sort=='CB'){
                $type_sorts = ['CB' => 1, 'IB' => 2, 'Lab' => 3];
            }elseif($sort=='IB'){
                $type_sorts = ['CB' => 2, 'IB' => 1, 'Lab' => 3];
            }elseif($sort=='Lab-Test'){
                $type_sorts = ['CB' => 2, 'IB' => 3, 'Lab' => 'IF(certi.lab_type=3, -1, 0)'];
            }elseif($sort=='Lab-Calibrate'){
                $type_sorts = ['CB' => 2, 'IB' => 3, 'Lab' => 'IF(certi.lab_type=4, -1, 0)'];
            }
        }else{
            $type_sorts = ['CB' => 0, 'IB' => 0, 'Lab' => 0];
        }

        $filter_search     = $request->input('filter_search');
        $filter_type_unit  = $request->input('filter_type_unit','');
        $filter_province   = $request->input('filter_province');
        $export_cb = CertiCBExport::select('id','name_standard AS name','certificate AS certificate_no','accereditatio_no')->selectRaw('"1" as certify, "CB" as certify_type, '.$type_sorts['CB'].' as type_sort')
                                     ->where('status','4')
                                     ->whereNotNull('name_standard')
                                    ->where(function ($query) {
                                        $query->WhereNotNull('certificate_newfile') 
                                              ->OrWhereNotNull('attachs');
                                      })
                                    ->when($filter_search, function ($query, $filter_search){
                                        return $query->where(function($query2) use($filter_search){
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $query2->Where(DB::raw("REPLACE(name_standard,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(accereditatio_no,' ','')"), 'LIKE', "%".$search_full."%")  ;   
                                        });                             
                                     })  
                                     ->when($filter_province, function ($query, $filter_province){
                                        return $query->where(function($query2) use($filter_province){
                                            $province_full = str_replace(' ', '', $filter_province );
                                                $query2->Where(DB::raw("REPLACE(province_name,' ','')"), 'LIKE', "%".$province_full."%") ;
                                        });            
                                     }) ; 
        $export_ib = CertiIBExport::select('id','name_unit AS name','certificate AS certificate_no','accereditatio_no')->selectRaw('"2" as certify, "IB" as certify_type, '.$type_sorts['IB'].' as type_sort')
                                    ->whereNotNull('name_unit')
                                    ->where('status','4')
                                    ->where(function ($query) {
                                        $query->WhereNotNull('certificate_newfile')
                                              ->OrWhereNotNull('attachs');
                                      })
                                      ->when($filter_search, function ($query, $filter_search){
                                        return $query->where(function($query2) use($filter_search){
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $query2->Where(DB::raw("REPLACE(name_unit,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(accereditatio_no,' ','')"), 'LIKE', "%".$search_full."%")  ;   
                                        });                             
                                     })  
                                     ->when($filter_province, function ($query, $filter_province){
                                        return $query->where(function($query2) use($filter_province){
                                               $province_full = str_replace(' ', '', $filter_province );
                                                $query2->Where(DB::raw("REPLACE(province_name,' ','')"), 'LIKE', "%".$province_full."%") ;
                                         });            
                                     }) ; 
                           

        $export_lab =  DB::table((new CertificateExport)->getTable().' AS export')
                                    ->select('export.id','export.lab_name AS name','export.certificate_no','export.accereditatio_no','certi.lab_type AS certify_type')->selectRaw('"LAB" as certify, '.$type_sorts['Lab'].' as type_sort')
                                    ->leftjoin((new CertiLab)->getTable().' AS certi', 'certi.id', '=', 'export.certificate_for')
                                    ->whereNotNull('export.lab_name')
                                    ->where('export.status','4')
                                    ->where(function ($query) {
                                        $query->WhereNotNull('export.certificate_newfile')
                                            ->OrWhereNotNull('export.attachs');
                                    })
                                    ->when($filter_search, function ($query, $filter_search){
                                        return $query->where(function($query2) use($filter_search){
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $query2->Where(DB::raw("REPLACE(export.lab_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(export.certificate_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(export.accereditatio_no,' ','')"), 'LIKE', "%".$search_full."%")  ;
                                        });                             
                                     })  
                                     ->when($filter_type_unit, function ($query, $filter_type_unit){
                                        return $query ->Where('certi.lab_type', $filter_type_unit) ;          
                                     })  
                                     ->when($filter_province, function ($query, $filter_province){
                                        return $query->where(function($query2) use($filter_province){
                                               $province_full = str_replace(' ', '', $filter_province );
                                                $query2->Where(DB::raw("REPLACE(export.address_province,' ','')"), 'LIKE', "%".$province_full."%") ;
                                         });            
                                     }) ; 
 
        //  dd($export_lab->toSql());                            
         if($filter_type_unit == '1'){ // หน่วยรับรอง
            $query =  $export_cb;
         } else   if($filter_type_unit == '2'){ // หน่วยตรวจ
            $query =  $export_ib;
         } else   if($filter_type_unit == '3' || $filter_type_unit == '4'){ // ทดสอบ สอบเทียบ
           $query =  $export_lab;
         } else{
            $query =  $export_lab->union($export_ib)->union($export_cb);
         }                         
      
         $certify_type =     ['1'=>'หน่วยรับรอง','2'=>'หน่วยตรวจ','3'=>'ห้องปฏิบัติการทดสอบ','4'=>'ห้องปฏิบัติการสอบเทียบ'];
                                                    
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('certificate_no', function ($item) {
                                return    !empty($item->certificate_no)  ? $item->certificate_no : '';
                             })
                             ->addColumn('name', function ($item) {
                                return  $item->name;
                            })
                            ->addColumn('certify_type', function ($item) use ($certify_type) {
                                return   !empty($item->certify_type) && array_key_exists($item->certify_type,$certify_type) ? $certify_type[$item->certify_type]: '';
                            })
                             ->addColumn('accereditatio_no', function ($item) {
                                return    !empty($item->accereditatio_no)  ? $item->accereditatio_no : '';  
                             })
                             ->addColumn('action', function ($item)  {
                                  $id       =   rtrim(strtr(base64_encode($item->id), '+/', '-_'), '=');
                                  $certify  =   rtrim(strtr(base64_encode($item->certify), '+/', '-_'), '=');
                                return '<a href="'.url('report/certificate-th?&id='.$id.'&certify='.$certify).'" class="btn btn-info btn-xs"  ><i class="fa fa-eye"></i></a>';
                             })
                            ->order(function ($query) use ($is_sort) {
                                if($is_sort){ //เรียงตามประเภท
                                    $query->orderBy('type_sort', 'ASC')
                                          ->orderBy('certificate_no', 'ASC');
                                }else{
                                    $query->orderBy('name', 'ASC');
                                }
                            })
                            ->rawColumns([ 'action']) 
                            ->make(true);
      }

      public function show_th(Request $request)
      {
        $certi_id   = base64_decode(str_pad(strtr($request->id, '-_', '+/'), strlen($request->id) % 4, '=', STR_PAD_RIGHT));
        $certify    = base64_decode(str_pad(strtr($request->certify, '-_', '+/'), strlen($request->certify) % 4, '=', STR_PAD_RIGHT));
        if($certify == 'CB' || $certify == "1"){
            $item      = CertiCBExport::findOrfail($certi_id);
        }else if($certify == 'IB'  || $certify == "2"){
            $item      = CertiIBExport::findOrfail($certi_id);
        }else{
            $item      = CertificateExport::findOrfail($certi_id);
        }
 
         return view('report.certificate.show_th', compact('item','certify'));
      }


      public function show_en(Request $request)
      {
        $certi_id   = base64_decode(str_pad(strtr($request->id, '-_', '+/'), strlen($request->id) % 4, '=', STR_PAD_RIGHT));
        $certify    = base64_decode(str_pad(strtr($request->certify, '-_', '+/'), strlen($request->certify) % 4, '=', STR_PAD_RIGHT));
        if($certify == 'CB' || $certify == "1"){
            $item      = CertiCBExport::findOrfail($certi_id);
        }else if($certify == 'IB'  || $certify == "2"){
            $item      = CertiIBExport::findOrfail($certi_id);
        }else{
            $item      = CertificateExport::findOrfail($certi_id);
        }
 
         return view('report.certificate.show_en', compact('item','certify'));
      }

      public function check_files_lab($id)
      {
  
             $certi_id = base64_decode(str_pad(strtr($id, '-_', '+/'), strlen($id) % 4, '=', STR_PAD_RIGHT));

            //  dd($certi_id);
             $certi_lab = CertiLab::findOrFail($certi_id);
       
             $public = public_path();
             $attach_path1 = 'files/applicants/check_files/';
             $arrContextOptions = array();

              // ใบรับรอง และ ขอบข่าย    
                if(!is_null($certi_lab->certi_lab_export_mapreq_to)){
                    
                    $certificate_no =  !empty($certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no) ? $certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no : null;
                    if(!is_null($certificate_no)){
                        $export_no         =  CertificateExport::where('certificate_no',$certificate_no);
                        if(count($export_no->get()) > 0){
                            
                            $lab_ids = [];
                            if($export_no->pluck('certificate_for')->count() > 0){
                                foreach ($export_no->pluck('certificate_for') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                       $lab_ids[] =  $item;
                                    }
                                }
                            }

                            if($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0){
                                foreach ($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                        $lab_ids[] =  $item;
                                    }
                                }
                            }

                            // ขอบข่าย
                            $attach_pdf =  CertLabsFileAll::whereIn('app_certi_lab_id',$lab_ids)->where('state', 1)->orderby('id','desc')->value('attach_pdf');
                            // dd($attach_pdf);
                      } 
                 }
              }
       
    if(!empty($attach_pdf)){
        // dd($attach_pdf);
        try {       
            if(!empty($attach_pdf)  && HP::checkFileStorage($attach_pdf))
            {
                // dd('1');
                   $attach_path2 = $attach_pdf;
                   HP::getFileStoragePath($attach_path2);

                   // $mpdf->SetImportUse();
                   $dashboard_pdf_file         =  url('uploads/'.$attach_path2);
                   $arrContextOptions = array();
                   if(strpos($dashboard_pdf_file, 'https')===0){//ถ้าเป็น https
                       $arrContextOptions["ssl"] = array(
                                                       "verify_peer" => false,
                                                       "verify_peer_name" => false,
                                                   );
                   }
                   $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                                       //Specify that the content has PDF Mime Type
                   header("Content-Type: application/pdf");
                   //Display it
                   echo $content_pdf;
                   exit;

                   
             }else   if(!empty($attach_pdf)  && HP::checkFileStorage($attach_path1.$attach_pdf))
             {
                   $attach_path2 = $attach_path1.$attach_pdf;
                   HP::getFileStoragePath($attach_path2);

                   $dashboard_pdf_file             =  url('uploads/'.$attach_path2);

                //    ใช้การโหลดจากเครื่องโดยตรงไม่ใช้ api
                   // ตั้งค่า path ในเครื่องที่มีไฟล์
                    $file_path = public_path('uploads/'.$attach_path2);

                    // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
                    if (file_exists($file_path)) {
                        // อ่านไฟล์โดยตรงจาก path
                        $content_pdf = file_get_contents($file_path);
                        // กำหนด Content-Type ให้เป็น PDF
                        header("Content-Type: application/pdf");
                        // แสดงไฟล์ PDF
                        echo $content_pdf;
                        exit;
                    } else {
                        // ถ้าไฟล์ไม่พบ
                        echo "ไม่พบไฟล์";
                        exit;
                    }
                   
                   $arrContextOptions = array();
                   if(strpos($dashboard_pdf_file, 'https')===0){//ถ้าเป็น https
                       $arrContextOptions["ssl"] = array(
                                                       "verify_peer" => false,
                                                       "verify_peer_name" => false,
                                                   );
                   }

                   $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                    //Specify that the content has PDF Mime Type
                   header("Content-Type: application/pdf");
   
                   //Display it
                   echo $content_pdf;
                   exit;


            }else if(HP::checkFileStorage($attach_path1.'/' .$certi_lab->attach_pdf))
            {
                // dd('3');
                   $attach_path2 = $attach_path1.$certi_lab->attach_pdf;
                   HP::getFileStoragePath($attach_path2);
                   // $mpdf->SetImportUse();
                   $dashboard_pdf_file         =  url('uploads/'.$attach_path2);
                   $arrContextOptions = array();
                   if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                       $arrContextOptions["ssl"] = array(
                                                       "verify_peer" => false,
                                                       "verify_peer_name" => false,
                                                   );
                   }
                   $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                                       //Specify that the content has PDF Mime Type
                   header("Content-Type: application/pdf");
                   //Display it
        
                   echo $content_pdf;
                   exit;


            }else{
               return 'ไม่พบไฟล์';
            }
      } catch (\Exception $e) {
               if(!empty($attach_pdf)  && HP::checkFileStorage($attach_pdf)){
                   $attach_path2 = $attach_pdf;
                   HP::getFileStoragePath($attach_path2);
                   $filePath =  response()->file($public.'/uploads/'.$attach_path2);
                    return $filePath;
                 }else   if(!empty($attach_pdf)  && HP::checkFileStorage($attach_path1.$attach_pdf)){
                       $attach_path2 = $attach_path1.$attach_pdf;
                       HP::getFileStoragePath($attach_path2);
                       $filePath =  response()->file($public.'/uploads/'.$attach_path2);
                        return $filePath;
                }else if(HP::checkFileStorage($attach_path1.'/' .$certi_lab->attach_pdf)){
                    HP::getFileStoragePath($attach_path1.'/' .$certi_lab->attach_pdf);
                    $filePath =  response()->file($public.'/uploads/'.$attach_path1.'/' . $certi_lab->attach_pdf);
                     return $filePath;
                }else{
                   return 'ไม่พบไฟล์';
                }
       }


    }else{


      try {       
             if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
                    $attach_path2 = $certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
                    HP::getFileStoragePath($attach_path2);

                    // $mpdf->SetImportUse();
                    $dashboard_pdf_file         =  url('uploads/'.$attach_path2);
                    $arrContextOptions = array();
                    if(strpos($dashboard_pdf_file, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                        "verify_peer" => false,
                                                        "verify_peer_name" => false,
                                                    );
                    }
                    $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                                        //Specify that the content has PDF Mime Type
                    header("Content-Type: application/pdf");
                    //Display it
                    echo $content_pdf;
                    exit;

                    
              }else   if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
                    $attach_path2 = $attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
                    HP::getFileStoragePath($attach_path2);


                    $dashboard_pdf_file             =  url('uploads/'.$attach_path2);
                    $arrContextOptions = array();
                    if(strpos($dashboard_pdf_file, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                        "verify_peer" => false,
                                                        "verify_peer_name" => false,
                                                    );
                    }
                    $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                                        //Specify that the content has PDF Mime Type
                    header("Content-Type: application/pdf");
    
                    //Display it
                    echo $content_pdf;
                    exit;


             }else if(HP::checkFileStorage($attach_path1.'/' .$certi_lab->attach_pdf)){
                    $attach_path2 = $attach_path1.$certi_lab->attach_pdf;
                    HP::getFileStoragePath($attach_path2);
                    // $mpdf->SetImportUse();
                    $dashboard_pdf_file         =  url('uploads/'.$attach_path2);
                    $arrContextOptions = array();
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                        "verify_peer" => false,
                                                        "verify_peer_name" => false,
                                                    );
                    }
                    $content_pdf =  file_get_contents($dashboard_pdf_file, false, stream_context_create($arrContextOptions));
                                        //Specify that the content has PDF Mime Type
                    header("Content-Type: application/pdf");
                    //Display it
         
                    echo $content_pdf;
                    exit;


             }else{
                return 'ไม่พบไฟล์';
             }
       } catch (\Exception $e) {
                if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
                    $attach_path2 = $certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
                    HP::getFileStoragePath($attach_path2);
                    $filePath =  response()->file($public.'/uploads/'.$attach_path2);
                     return $filePath;
                  }else   if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
                        $attach_path2 = $attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
                        HP::getFileStoragePath($attach_path2);
                        $filePath =  response()->file($public.'/uploads/'.$attach_path2);
                         return $filePath;
                 }else if(HP::checkFileStorage($attach_path1.'/' .$certi_lab->attach_pdf)){
                     HP::getFileStoragePath($attach_path1.'/' .$certi_lab->attach_pdf);
                     $filePath =  response()->file($public.'/uploads/'.$attach_path1.'/' . $certi_lab->attach_pdf);
                      return $filePath;
                 }else{
                    return 'ไม่พบไฟล์';
                 }
        }
              }

 



      }
}
