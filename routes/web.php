<?php

use App\AttachFile;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use App\Models\Certify\ApplicantIB\CertiIBFileAll;
use App\Models\Certify\ApplicantCB\CertiCbExportMapreq;
use App\Models\Certify\ApplicantIB\CertiIbExportMapreq;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Test function

Route::get('/test','MyTestController@index');
Route::get('/call-demo-pmt1','MyTestController@apiPmt1');
Route::get('/call-demo-pdf','MyTestController@apiPdf');
Route::get('/downloadpdf','MyTestController@downloadpdf');
Route::get('/getmaillist','MyTestController@getmaillist');
Route::get('/getcompayinfo','MyTestController@getCompayInfo');
Route::get('/generate-pdf-lab-scope','MyTestController@generatePdfLabScope');
Route::get('/create_folder','MyTestController@create_folder');
Route::get('/check-payin','MyTestController@check_payin');
Route::get('/create-bill','MyTestController@create_bill');
Route::get('/create-lab-report','MyTestController@CreateLabReport');
Route::get('/create-lab-report-pdf','MyTestController@CreateLabReportPdf');
Route::get('/create-lab-report-pdf-demo','MyTestController@CreateLabMessageRecordPdfDemo');
Route::get('/check-payin-expire','MyTestController@upDatePayin');
Route::get('/merge-pdf','MyTestController@mergePdf');
Route::get('/check-payin-2','MyTestController@checkPayIn2');
Route::get('/regen-payin-2','MyTestController@regenPayin2');
Route::get('/get-otp','MyTestController@getOtp');
Route::get('/director-sign-mail','MyTestController@directorSignMail');
Route::get('/check-notice-expire','MyTestController@checkNoticeExpire');
Route::get('/generate-scope-pdf','MyTestController@generateScopePDF');
Route::get('/create-tracking-message-record','MyTestController@CreateTrackingLabMessageRecord');
Route::get('/create-message-record','MyTestController@CreateLabMessageRecord');
Route::get('/gen-tracking-lab-message-record-pdf','MyTestController@genTrackingLabMessageRecordPdf');
Route::get('/call-create-bill','MyTestController@callCreateBill');
Route::get('/update-tracking-lab-payin1','MyTestController@updateTrackingLabPayin1');
Route::get('/tracking-lab-report','MyTestController@trackingLabReportPdf');
Route::get('/tracking-data-list','MyTestController@trackingDataList');

Route::get('/download-from-cloud','MyTestController@downloadFromCloud');
Route::get('/get-attached-file-from-request','MyTestController@getAttachedFileFromRequest');

Route::get('/get-doc-review-auditor','MyTestController@getDocReviewAuditor');

Route::get('/cb-scope','MyTestController@copyScopeCbFromAttachement');

Route::get('/demo_html_pdf_editor','MyTestController@demo_html_pdf_editor');

Route::get('/create-cb-assessment-report-pdf','MyTestController@createCbAssessmentReportPdf');

Route::get('/create-cb-assessment-report-two-pdf','MyTestController@createCbAssessmentReportTwoPdf');

Route::get('/create-cb-message-record-pdf','MyTestController@createCbMessageRecordPdf');

Route::get('/run-all-schedule','MyTestController@runAllSchedules');
Route::get('/check-payin2-cb','MyTestController@check_payin2_cb');


Route::get('/create-lab-assessment-report-two-pdf','MyTestController@createLabAssessmentReportTwoPdf');
Route::get('/get-email-info','MyTestController@getEmailInfo');
Route::get('/check-ib-payin','MyTestController@check_ib_payin');

Route::get('/create-ib-assessment-report-pdf','MyTestController@createIbAssessmentReportPdf');

Route::get('/demo-email-otp','MyTestController@demoEmailOtp');


Route::get('/create-ib-assessment-report-two-pdf','MyTestController@createIbAssessmentReportTwoPdf');







Route::get('/proxy', function (\Illuminate\Http\Request $request) {
    $url = $request->query('url'); // รับ URL ของ PDF ที่ต้องการ

    // ตรวจสอบว่ามี URL หรือไม่
    if (!$url) {
        return response()->json(['error' => 'URL parameter is required'], 400);
    }

    try {
        $client = new Client();
        $response = $client->get($url);

        return response($response->getBody()->getContents(), $response->getStatusCode())
            ->header('Content-Type', $response->getHeader('Content-Type')[0])
            ->header('Access-Control-Allow-Origin', '*'); // เพิ่มส่วนหัว CORS
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch the URL'], 500);
    }
});

// Route::get('/create-by-expert/{notice_id?}','MyTestController@createByExpert');
// Route::post('/store-by-expert','MyTestController@storeByExpert')->name('store_by_expert');
// Route::get('/store-by-expert-get-app/{app_no?}','MyTestController@storeByExpertGetApp')->name('store_by_expert_get_app');

Route::get('/create-by-expert/{notice_id?}','ExternalExpertActionController@createByExpert');
Route::post('/store-by-expert','ExternalExpertActionController@storeByExpert')->name('store_by_expert');
Route::get('/store-by-expert-get-app/{app_no?}','ExternalExpertActionController@storeByExpertGetApp')->name('store_by_expert_get_app');

Route::get('/create-by-expert-lab-sur/{notice_id?}','ExternalExpertActionController@createByExpertLabSur')->name('create_by_expert.lab_sur');
Route::post('/store-by-expert-lab-sur','ExternalExpertActionController@storeByExpertLabSur')->name('store_by_expert.lab_sur');
Route::get('/store-by-expert-get-app-lab-sur/{app_no?}','ExternalExpertActionController@storeByExpertGetAppLabSur')->name('store_by_expert_get_app.lab_sur');


Route::get('/create-by-cb-expert/{assessment_id?}','ExternalCBExpertActionController@createByCbExpert');
Route::post('/store-by-cb-expert','ExternalCBExpertActionController@storeByCbExpert')->name('store_by_cb_expert');
Route::get('/store-by-cb-expert-get-app/{app_no?}','ExternalCBExpertActionController@storeByExpertGetApp')->name('store_by_cb_expert_get_app');

Route::get('/create-by-ib-expert/{assessment_id?}','ExternalIBExpertActionController@createByIbExpert');
Route::post('/store-by-ib-expert','ExternalIBExpertActionController@storeByIbExpert')->name('store_by_ib_expert');
Route::get('/store-by-ib-expert-get-app/{app_no?}','ExternalIBExpertActionController@storeByExpertGetApp')->name('store_by_ib_expert_get_app');


// HelperController
Route::get('/add-permission','HelperController@addPermission')->name('add-permission');
//

Route::get('page/manuals','FuntionCenter\FunctionController@Manuals');
Route::get('page/send-mails/show/{id}','FuntionCenter\FunctionController@infomation_show');
Route::get('page/send-mails/infomation','FuntionCenter\FunctionController@infomation');
Route::get('page/send-mails/user','FuntionCenter\FunctionController@send_mail_user');
Route::Post('page/send-mails/user/save','FuntionCenter\FunctionController@save_send_mail_user');
Route::get('/funtions/get-addreess/{subdistrict_id?}', 'FuntionCenter\FunctionController@GetAddreess');
Route::get('funtions/get-ssouser', 'FuntionCenter\\FunctionController@GetSSOuser');
Route::get('/funtions/search-addreess', 'FuntionCenter\FunctionController@SearchAddreess');
Route::get('funtions/search-standards', 'FuntionCenter\\FunctionController@SearchStandards');

Route::get('funtions/set-cookie', 'FuntionCenter\\FunctionController@setCookie');
Route::get('funtions/get-cookie', 'FuntionCenter\\FunctionController@getCookie');

Route::get('funtions/auto-refresh/notification', 'FuntionCenter\\FunctionController@getNotification');
Route::get('funtions/redirect/notification/{id?}', 'FuntionCenter\\FunctionController@Notification_redirect');
Route::Post('funtions/read_all/notification', 'FuntionCenter\\FunctionController@NotificationReadAll');
Route::get('funtions/get-time-now', 'FuntionCenter\FunctionController@GetTimeNow');
Route::get('funtions/search-users', 'FuntionCenter\\FunctionController@search_users');
Route::get('funtions/search-user-registers', 'FuntionCenter\\FunctionController@search_user_registers');
Route::get('funtions/search-user-lawcase', 'FuntionCenter\\FunctionController@search_user_law_case');
Route::get('funtions/search-user-law-registers', 'FuntionCenter\\FunctionController@search_law_user_registers');
Route::get('funtions/search-tb4tisilicense', 'FuntionCenter\\FunctionController@search_tb4tisilicense');
Route::get('funtions/search-tb3tis', 'FuntionCenter\\FunctionController@search_tb3tis');
Route::get('funtions/search_sub_department_tb3tis', 'FuntionCenter\\FunctionController@search_sub_department_tb3tis');

Route::get('funtions/delete-file/{id}','FuntionCenter\\FunctionController@deleteFile');

Route::get('funtions/file-manager', 'FuntionCenter\\ManagerFileController@index');
Route::get('funtions/file-manager/show_all', 'FuntionCenter\\ManagerFileController@show_all');
Route::get('funtions/file-manager/load-folder', 'FuntionCenter\\ManagerFileController@LoadFolder');

Route::get('imp/data/manufacture','FuntionCenter\ElicenseController@ImpDataManufacture');

//API ดึงข้อมูลมาตรฐานสก.
Route::get('estandard', 'API\StandardController@get_estandard');

Route::get('/law/listen/ministry/accept/success/{id}', 'FuntionCenter\\LawMailController@accept_save_success');
Route::get('/law/listen/ministry/accept/{id}', 'FuntionCenter\\LawMailController@accept');
Route::POST('/law/listen/ministry/accept-save', 'FuntionCenter\\LawMailController@accept_save');


Route::get('/',function (){
    return view('welcome');
});

//หน้า Dash Board
Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::any('/blog/category/{category}',
    array(
        /*'before'=>'auth_check', -- maybe add a pre-filter */
        'uses' =>'BlogController@category',
        'as' => 'category_browse'
    )
);

// คลีนข้อมูล
Route::get('cleandata/test-connect-elicense-database','CleanData\CleanDataController@testConnectionElicenseDatabase');
Route::get('cleandata/tb4tisilicense-moao8','CleanData\CleanDataController@cleanTb4TisilicenseMoao8');
Route::get('cleandata/tb4tisilicense-moao81','CleanData\CleanDataController@cleanTb4TisilicenseMoao81');
Route::get('cleandata/tb4tisilicense-moao9','CleanData\CleanDataController@cleanTb4TisilicenseMoao9');
Route::get('cleandata/tb4tisilicense-changelicense','CleanData\CleanDataController@cleanTb4TisilicenseChangeLicense');
Route::get('cleandata/tb4tisilicense-revertdata','CleanData\CleanDataController@revertDataTb4');
Route::get('cleandata/display-tb4','CleanData\CleanDataController@updateDisplayTb4');
Route::get('cleandata/display-tb4-show','CleanData\CleanDataController@displayTb4Show');


// แจ้งเตือน Mail
Route::get('certify/Alert-Certify','Certify\EmailController@emails');

// รายการใบรับรองระบบงาน
Route::get('report/certificate/data_list','Report\CertificateController@data_list');
Route::get('report/certificate-en','Report\CertificateController@show_en');
Route::get('report/certificate-th','Report\CertificateController@show_th');
Route::get('report/certificate','Report\CertificateController@index');

//ข้อมูลที่จะแสดงใน dash board
Route::get('dashboard/elicense', 'Dashboard\DashboardController@elicense');//elicense
Route::get('dashboard/nsw/{month_year}', 'Dashboard\DashboardController@nsw');//NSW
Route::get('dashboard/certify', 'Dashboard\DashboardController@certify');//รับรองระบบงาน


Route::get('funtions/get-view/files/{systems}/{tax_number}/{new_filename}/{filename}', function($systems,$tax_number,$new_filename,$filename)
{
    // dd('here');
    $public = public_path();
    $attach_path = 'files/'.$systems.'/'.$tax_number;

    if(HP::checkFileStorage($attach_path.'/'. $new_filename)){

        $file_name = $attach_path .'/'. $new_filename;
        $info = pathinfo( $file_name , PATHINFO_EXTENSION ) ;

        if( $info == "txt" || $info == "doc" || $info == "docx" || $info == "ppt" || $info == "7z" || $info == "zip"  ){
            return Storage::download($attach_path.'/'.  $new_filename);
        }else{
            HP::getFileStoragePath($attach_path .'/'. $new_filename);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.'/'.  $new_filename);
            // dd($public.'/uploads/'.$attach_path.'/'.  $new_filename);
            return $filePath;
        }
    }else{
        return 'ไม่พบไฟล์';
    }
});

Route::get('funtions/get-law-view/files/{law_attach}/{systems}/{tax_number}/{new_filename}/{filename}', function($law_attach,$systems,$tax_number,$new_filename,$filename)
{
 
    $public = public_path();
    $attach_path = $law_attach.'/'.$systems.'/'.$tax_number;

    if(HP::checkFileStorage($attach_path.'/'. $new_filename)){

        $file_name = $attach_path .'/'. $new_filename;
        $info = pathinfo( $file_name , PATHINFO_EXTENSION ) ;

        if( $info == "txt" || $info == "doc" || $info == "docx" || $info == "ppt" || $info == "7z" || $info == "zip"  ){
            return Storage::download($attach_path.'/'.  $new_filename);
        }else{
            HP::getFileStoragePath($attach_path .'/'. $new_filename);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.'/'.  $new_filename);
            return $filePath;
        }
    }else{
        return 'ไม่พบไฟล์';
    }
});

 // อ่านไฟล์ที่แนบ pay-in LAB
Route::get('certify/check/files/pay_in_lab/{filename}', function($filename)
{
    $public = public_path();
    $attach_path = 'files/applicants/files_pay_in_lab/';
    if(HP::checkFileStorage($attach_path . $filename)){
    HP::getFileStoragePath($attach_path. $filename);
    $filePath =  response()->file($public.'/uploads/'.$attach_path. $filename);
        return $filePath;
    }else{
        return 'ไม่พบไฟล์';
    }
});

Route::get('/certify/check/files/assessment/{filename}', function( $filename)
{

    $public = public_path();
    $attach_path = 'files/applicants/save_assessment/';
    if(HP::checkFileStorage($attach_path . $filename)){
        HP::getFileStoragePath($attach_path. $filename);
        $filePath =  response()->file($public.'/uploads/'.$attach_path. $filename);
        return $filePath;
    }else{
        return 'ไม่พบไฟล์';
    }
});

     // อ่านไฟล์ที่แนบมา   (LAB)
     Route::get('certify/check_files_lab/{id?}','Report\\CertificateController@check_files_lab');
//  Route::get('certify/check_files_lab/{id?}', function($id)
//      {
//              $certi_id = base64_decode(str_pad(strtr($id, '-_', '+/'), strlen($id) % 4, '=', STR_PAD_RIGHT));
//              $certi_lab = CertiLab::findOrFail($certi_id);
//              $public = public_path();
//              $attach_path1 = 'files/applicants/check_files/';
//              if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
//                 $attach_path2 = $certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
//                 HP::getFileStorage($attach_path2);
//                 $filePath =  response()->file($public.'/uploads/'.$attach_path2);
//                  return $filePath;
//               }else   if(!empty($certi_lab->Certi_Lab_State1_FileTo->attach_pdf)  && HP::checkFileStorage($attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf)){
//                     $attach_path2 = $attach_path1.$certi_lab->Certi_Lab_State1_FileTo->attach_pdf;
//                     HP::getFileStorage($attach_path2);
//                     $filePath =  response()->file($public.'/uploads/'.$attach_path2);
//                      return $filePath;
//              }else if(HP::checkFileStorage($attach_path1.'/' .$certi_lab->attach_pdf)){
//                  HP::getFileStorage($attach_path1.'/' .$certi_lab->attach_pdf);
//                  $filePath =  response()->file($public.'/uploads/'.$attach_path1.'/' . $certi_lab->attach_pdf);
//                   return $filePath;
//              }else{
//                 return 'ไม่พบไฟล์';
//              }

//     });


     // อ่านไฟล์ที่แนบมา   (LAB)
     Route::get('certify/check/file_client/{app_no}/{filename}/{client_name}', function($app_no,$filename,$client_name)
     {
        
        $public = public_path();
        
        $attach_path = 'files/applicants/check_files/';
        // dd($attach_path. $app_no .'/'. $filename);

       if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
        //    if($client_name != 'null'){
                // HP::getFileStorageClientName($attach_path. $app_no .'/'. $filename,$client_name);
        //         HP::getFileStorage($attach_path. $app_no .'/'. $filename);
        //         $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
        //         return $filePath;
        //    }else{
                HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
                $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
                // dd($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
                return $filePath;
        //    }

       }else{
          return 'ไม่พบไฟล์';
       }
     });

    Route::get('certify/check/files/{app_no}/{filename}', function($app_no,$filename)
    {
    $public = public_path();
    $attach_path = 'files/applicants/check_files/';
   if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
       HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
       $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
        return $filePath;
   }else{
      return 'ไม่พบไฟล์';
   }
  });

  Route::get('certify/check/file_cb_client/{app_no}/{filename}/{client_name}', function($app_no,$filename,$client_name)
  {

     $public = public_path();
     $attach_path = 'files/applicants/check_files_cb/';
    if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
             HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
             $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
             return $filePath;
    }else{
       return 'ไม่พบไฟล์';
    }
  });

 // อ่านไฟล์ที่แนบมา CB
    Route::get('certify/check_files_cb/{id?}', function($id)
    {
        $public = public_path();
        $certi_id = base64_decode(str_pad(strtr($id, '-_', '+/'), strlen($id) % 4, '=', STR_PAD_RIGHT));

        if($certi_id == 7){
                $certi_cb = CertiCBFileAll::where('state',1)
                                            ->where('app_certi_cb_id',21)
                                            ->first();
        }else{
                $certificate_cb_export_mapreq = CertiCbExportMapreq::where('app_certi_cb_id', $certi_id)->firstOrFail();
                $certi_cb = $certificate_cb_export_mapreq->CertiCBFilePrimary;
    
                // $certi_cb = CertiCBFileAll::where('state',1)
                //                             ->where('app_certi_cb_id',$certi_id)
                //                             ->first();
        }

        $attach_path = 'files/applicants/check_files_cb/';
        if(!empty($certi_cb->attach_pdf)  && HP::checkFileStorage($certi_cb->attach_pdf)){
            $attach_path2 = $certi_cb->attach_pdf;
            HP::getFileStoragePath($attach_path2);
            $filePath =  response()->file($public.'/uploads/'.$attach_path2);
             return $filePath;
         }else  if( !empty($certi_cb) && HP::checkFileStorage($attach_path.'/' .$certi_cb->attach_pdf)  ){
            HP::getFileStoragePath($attach_path.'/' .$certi_cb->attach_pdf);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $certi_cb->attach_pdf);
             return $filePath;
        }else  if(!empty($certi_cb) && HP::checkFileStorage($certi_cb->attach_pdf)){

            $file_name = $certi_cb->attach_pdf;
            HP::getFileStoragePath($file_name);
            $filePath =  response()->file($public.'/uploads/'.$file_name);
            return $filePath;

        }else{
           return 'ไม่พบไฟล์';
        }
    });

    Route::get('certify/check/files_cb/{app_no}/{filename}', function($app_no,$filename)
    {


        $public = public_path();
        $attach_path = 'files/applicants/check_files_cb/';
       if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
           HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
            return $filePath;
       }else{
          return 'ไม่พบไฟล์';
       }
    });

         // อ่านไฟล์ที่แนบมา   (IB)
         Route::get('certify/check/file_ib_client/{app_no}/{filename}/{client_name}', function($app_no,$filename,$client_name)
         {

            $public = public_path();
            $attach_path = 'files/applicants/check_files_ib/';
           if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
                    HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
                    $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
                    return $filePath;
           }else{
              return 'ไม่พบไฟล์';
           }
         });

         Route::get('funtions/get-view-file/{filename}/{client_name?}', function($filename,$client_name = null)
         {

            $public = public_path();
            $attach_path = base64_decode($filename);
           if(HP::checkFileStorage($attach_path)){
                    HP::getFileStoragePath($attach_path);
                    $filePath =  response()->file($public.'/uploads/'.$attach_path);
                    return $filePath;
           }else{
              return 'ไม่พบไฟล์';
           }
         });


    // อ่านไฟล์ที่แนบมา IB
    Route::get('certify/check_files_ib/{id?}', function($id)
    {

        $certi_id = base64_decode(str_pad(strtr($id, '-_', '+/'), strlen($id) % 4, '=', STR_PAD_RIGHT));
        // $certi_ib = CertiIBFileAll::findOrFail($certi_id);
        //  $certi_ib = CertiIBFileAll::where('state',1)
        //                             ->where('app_certi_ib_id',$certi_id)
        //                             ->first();
        
        $certificate_ib_export_mapreq = CertiIbExportMapreq::where('app_certi_ib_id', $certi_id)->firstOrFail();
        $certi_ib_file = $certificate_ib_export_mapreq->CertiIBFilePrimary;

        $public = public_path();
        $attach_path = 'files/applicants/check_files_ib/';
 
        if(!empty($certi_ib_file->attach_pdf)  && HP::checkFileStorage($certi_ib_file->attach_pdf)){
            $attach_path2 = $certi_ib_file->attach_pdf;
            HP::getFileStoragePath($attach_path2);
            $filePath =  response()->file($public.'/uploads/'.$attach_path2);
             return $filePath;
         }else if(!is_null($certi_ib_file) && HP::checkFileStorage($attach_path.$certi_ib_file->attach_pdf)   ){
           HP::getFileStoragePath($attach_path.$certi_ib_file->attach_pdf);
           $filePath =  response()->file($public.'/uploads/'.$attach_path. $certi_ib_file->attach_pdf);
            return $filePath;
       }else  if(HP::checkFileStorage($certi_ib_file->attach_pdf)){
            $file_name = $certi_ib_file->attach_pdf;
            HP::getFileStoragePath($file_name);
            $filePath =  response()->file($public.'/uploads/'.$file_name);
            return $filePath;
        }else{
          return 'ไม่พบไฟล์';
       }
    });
    Route::get('certify/check/files_ib/{app_no}/{filename}', function($app_no,$filename)
    {
        $public = public_path();
        $attach_path = '/files/applicants/check_files_ib/';
       if(HP::checkFileStorage($attach_path. $app_no .'/'. $filename)){
           HP::getFileStoragePath($attach_path. $app_no .'/'. $filename);
           $filePath =  response()->file($public.'/uploads/'.$attach_path.'/' . $app_no .'/'. $filename);
            return $filePath;
       }else{
          return 'ไม่พบไฟล์';
       }

    });

    Route::get('funtions/get-delete/files/{id?}/{url_send?}', function($id,$url_send){

        if( !is_null($id) && is_numeric($id) && !is_null( AttachFile::where('id',$id )->first() ) ){

            $file = AttachFile::where('id', $id )->first();

            if( HP::checkFileStorage( $file->url ) ){
                Storage::delete( "/".$file->url );
            }
            $file->delete();

            return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');
        }

    });

 // ไฟล์แนบ หลักฐาน แนบท้าย
 Route::get('certify/certificate-export/pdf/{app_no}'   ,'PDFController@PrintAttachPDF');
 Route::get('certify/certificate-export/ib_pdf/{id?}'   ,'PDFController@PrintAttachIBPDF');
 Route::get('certify/certificate-export/cb_pdf/{id?}'   ,'PDFController@PrintAttachCBPDF');
 Route::get('certify/certificate-export/FilePdf/{files?}' ,'PDFController@FilePrintAttachIBPDF');
Route::group(['middleware' => 'auth'],function (){

	Route::get('/home', function () {
        // dd(auth()->user());
        return view('admin.index');
    });
    Route::get('/standards', function () {
        return view('admin.standards');
    });


    Route::get('account-settings','UsersController@getSettings');
    Route::post('account-settings','UsersController@saveSettings');

    //หน้าโปรไฟล์จากเมนูข้างภาพ profile
    Route::get('profile','ProfileController@show');
    Route::get('profile/login_list','ProfileController@login_list');//ประวัติการเข้าใช้งานระบบ

    /*routes for blog*/
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/create','BlogController@create');
        Route::post('/create','BlogController@store');
        Route::get('delete/{id}', 'BlogController@destroy')->name('blog.delete');
        Route::get('edit/{id}', 'BlogController@edit')->name('blog.edit');
        Route::post('edit/{id}', 'BlogController@update');
        Route::get('view/{id}', 'BlogController@show');
        //Route::get('{blog}/restore', 'BlogController@restore')->name('blog.restore');
        Route::post('{id}/storecomment', 'BlogController@storeComment')->name('storeComment');
    });
    Route::resource('blog', 'BlogController');

    /*routes for blog category*/
    Route::group(['prefix' => 'blog-category'], function () {
        Route::get('/','BlogCategoryController@getIndex');
        Route::get('/create','BlogCategoryController@create');
        Route::post('/create','BlogCategoryController@save');
        Route::get('/delete/{id}','BlogCategoryController@delete');
        Route::get('/edit/{id}','BlogCategoryController@edit');
        Route::post('/edit/{id}','BlogCategoryController@update');
    });
    Route::resource('blogcategory', 'BlogCategoryController');

});

Route::group(['middleware' => ['auth', 'roles']], function () {

    Route::get('index2', function (){
        return view('dashboard.index2');
    });
    Route::get('index3', function (){
        return view('dashboard.index3');
    });
    Route::get('index4', function (){
        return view('ecommerce.index4');
    });
    Route::get('products', function (){
        return view('ecommerce.products');
    });
    Route::get('product-detail', function (){
        return view('ecommerce.product-detail');
    });
    Route::get('product-edit', function (){
        return view('ecommerce.product-edit');
    });
    Route::get('product-orders', function (){
        return view('ecommerce.product-orders');
    });
    Route::get('product-cart', function (){
        return view('ecommerce.product-cart');
    });
    Route::get('product-checkout', function (){
        return view('ecommerce.product-checkout');
    });
    Route::get('panels-wells', function (){
        return view('ui-elements.panels-wells');
    });
    Route::get('panel-ui-block', function (){
        return view('ui-elements.panel-ui-block');
    });
    Route::get('portlet-draggable', function (){
        return view('ui-elements.portlet-draggable');
    });
    Route::get('buttons', function (){
        return view('ui-elements.buttons');
    });
    Route::get('tabs', function (){
        return view('ui-elements.tabs');
    });
    Route::get('modals', function (){
        return view('ui-elements.modals');
    });
    Route::get('progressbars', function (){
        return view('ui-elements.progressbars');
    });
    Route::get('notification', function (){
        return view('ui-elements.notification');
    });
    Route::get('carousel', function (){
        return view('ui-elements.carousel');
    });
    Route::get('user-cards', function (){
        return view('ui-elements.user-cards');
    });
    Route::get('timeline', function (){
        return view('ui-elements.timeline');
    });
    Route::get('timeline-horizontal', function (){
        return view('ui-elements.timeline-horizontal');
    });
    Route::get('range-slider', function (){
        return view('ui-elements.range-slider');
    });
    Route::get('ribbons', function (){
        return view('ui-elements.ribbons');
    });
    Route::get('steps', function (){
        return view('ui-elements.steps');
    });
    Route::get('session-idle-timeout', function (){
        return view('ui-elements.session-idle-timeout');
    });
    Route::get('session-timeout', function (){
        return view('ui-elements.session-timeout');
    });
    Route::get('bootstrap-ui', function (){
        return view('ui-elements.bootstrap');
    });
    Route::get('starter-page', function (){
        return view('pages.starter-page');
    });
    Route::get('blank', function (){
        return view('pages.blank');
    });
    Route::get('blank', function (){
        return view('pages.blank');
    });
    Route::get('search-result', function (){
        return view('pages.search-result');
    });
    Route::get('custom-scroll', function (){
        return view('pages.custom-scroll');
    });
    Route::get('lock-screen', function (){
        return view('pages.lock-screen');
    });
    Route::get('recoverpw', function (){
        return view('pages.recoverpw');
    });
    Route::get('animation', function (){
        return view('pages.animation');
    });
    // Route::get('profile', function (){
    //     return view('pages.profile');
    // });
    Route::get('invoice', function (){
        return view('pages.invoice');
    });
    Route::get('gallery', function (){
        return view('pages.gallery');
    });
    Route::get('pricing', function (){
        return view('pages.pricing');
    });
    Route::get('400', function (){
        return view('pages.400');
    });
    Route::get('403', function (){
        return view('pages.403');
    });
    Route::get('404', function (){
        return view('pages.404');
    });
    Route::get('500', function (){
        return view('pages.500');
    });
    Route::get('503', function (){
        return view('pages.503');
    });
    Route::get('form-basic', function (){
        return view('forms.form-basic');
    });
    Route::get('form-layout', function (){
        return view('forms.form-layout');
    });
    Route::get('icheck-control', function (){
        return view('forms.icheck-control');
    });
    Route::get('form-advanced', function (){
        return view('forms.form-advanced');
    });
    Route::get('form-upload', function (){
        return view('forms.form-upload');
    });
    Route::get('form-dropzone', function (){
        return view('forms.form-dropzone');
    });
    Route::get('form-pickers', function (){
        return view('forms.form-pickers');
    });
    Route::get('basic-table', function (){
        return view('tables.basic-table');
    });
    Route::get('table-layouts', function (){
        return view('tables.table-layouts');
    });
    Route::get('data-table', function (){
        return view('tables.data-table');
    });
    Route::get('bootstrap-tables', function (){
        return view('tables.bootstrap-tables');
    });
    Route::get('responsive-tables', function (){
        return view('tables.responsive-tables');
    });
    Route::get('editable-tables', function (){
        return view('tables.editable-tables');
    });
    Route::get('inbox', function (){
        return view('inbox.inbox');
    });
    Route::get('inbox-detail', function (){
        return view('inbox.inbox-detail');
    });
    Route::get('compose', function (){
        return view('inbox.compose');
    });
    Route::get('contact', function (){
        return view('inbox.contact');
    });
    Route::get('contact-detail', function (){
        return view('inbox.contact-detail');
    });
    Route::get('calendar', function (){
        return view('extra.calendar');
    });
    Route::get('widgets', function (){
        return view('extra.widgets');
    });
    Route::get('morris-chart', function (){
        return view('charts.morris-chart');
    });
    Route::get('peity-chart', function (){
        return view('charts.peity-chart');
    });
    Route::get('knob-chart', function (){
        return view('charts.knob-chart');
    });
    Route::get('sparkline-chart', function (){
        return view('charts.sparkline-chart');
    });
    Route::get('simple-line', function (){
        return view('icons.simple-line');
    });
    Route::get('fontawesome', function (){
        return view('icons.fontawesome');
    });
    Route::get('map-google', function (){
        return view('maps.map-google');
    });
    Route::get('map-vector', function (){
        return view('maps.map-vector');
    });

    #Permission management
    Route::get('permission-management','PermissionController@getIndex');
    Route::get('permission/create','PermissionController@create');
    Route::post('permission/create','PermissionController@save');
    Route::get('permission/delete/{id}','PermissionController@delete');
    Route::get('permission/edit/{id}','PermissionController@edit');
    Route::post('permission/edit/{id}','PermissionController@update');

    #Role management
    Route::get('role/data_list','RoleController@data_list');
    Route::get('role-management','RoleController@getIndex');
    Route::get('role/create','RoleController@create');
    Route::post('role/create','RoleController@save');
    Route::get('role/delete/{id}','RoleController@delete');
    Route::get('role/edit/{id}','RoleController@edit');
    Route::post('role/edit/{id}','RoleController@update');
    Route::get('role/edit_right/{id}','RoleController@edit_right');
    Route::post('role/edit_right/{id}','RoleController@update_right');

    #CRUD Generator
    Route::get('/crud-generator', ['uses' => 'ProcessController@getGenerator']);
    Route::post('/crud-generator', ['uses' => 'ProcessController@postGenerator']);

    # Activity log
    Route::get('activity-log','LogViewerController@getActivityLog');
    Route::get('activity-log/data', 'LogViewerController@activityLogData')->name('activity-log.data');

    #User Management routes
    Route::post('user/update_user_group','UsersController@update_user_group');
    Route::get('user/load_data_role/{id?}','UsersController@load_data_role');
    Route::get('user/data_list','UsersController@data_list');
    Route::get('users','UsersController@getIndex');
    Route::get('user/create','UsersController@create');
    Route::post('user/create','UsersController@save');
    Route::get('user/edit/{id}','UsersController@edit');
    Route::patch('user/edit/{id}','UsersController@update');
    Route::get('user/delete/{id}','UsersController@delete');
    Route::get('user/deleted/','UsersController@getDeletedUsers');
    Route::get('user/restore/{id}','UsersController@restoreUser');
    Route::get('user/check_email_repeat/{email}/{id_edit?}','UsersController@check_email_repeat');
    Route::get('user/check-taxid/{taxid?}', 'UsersController@check_taxid');

    Route::POST('role-setting-group/update_order', 'Roles\\RoleSettingGroupController@update_order');
    Route::get('role-setting-group/data_list','Roles\RoleSettingGroupController@data_list');
    Route::get('/role-setting-group{id}/destroy', 'Roles\RoleSettingGroupController@destroy');
    Route::POST('/role-setting-group/delete', 'Roles\RoleSettingGroupController@delete');
    Route::put('/role-setting-group/update-state', 'Roles\RoleSettingGroupController@update_state');
    Route::resource('role-setting-group', 'Roles\\RoleSettingGroupController');
});

//Log Viewer
Route::get('log-viewers', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@index')->name('log-viewers');
Route::get('log-viewers/logs', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@listLogs')->name('log-viewers.logs');
Route::delete('log-viewers/logs/delete', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@delete')->name('log-viewers.logs.delete');
Route::get('log-viewers/logs/{date}', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@show')->name('log-viewers.logs.show');
Route::get('log-viewers/logs/{date}/download', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@download')->name('log-viewers.logs.download');
Route::get('log-viewers/logs/{date}/{level}', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@showByLevel')->name('log-viewers.logs.filter');
Route::get('log-viewers/logs/{date}/{level}/search', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@search')->name('log-viewers.logs.search');
Route::get('log-viewers/logcheck', '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@logCheck')->name('log-viewers.logcheck');

#blog routes frontend
Route::get('/','BlogController@getBlogList');
Route::get('blogs/{slug}','BlogController@getBlog');
Route::get('blogs/category/{slug}','BlogController@getCategoryBlog');
Route::get('blogs/tag/{slug}','BlogController@getTagBlog');
Route::get('blogs/author/{slug}','BlogController@getAuthorBlog');

#ข้อมูลอ้างอิง frontend
Route::get('reference/role', 'Reference\RoleController@index');//กลุ่มผู้ใช้งาน
Route::get('reference/sub_department', 'Reference\SubDepartmentController@index');//หน่วยงาน
Route::get('reference/country', 'Reference\ElicenseCountryController@index');//ข้อมูลประเทศในระบบ e-license

//Route::get('auth/{provider}/','Auth\SocialLoginController@redirectToProvider');
//Route::get('{provider}/callback','Auth\SocialLoginController@handleProviderCallback');
Route::get('logout','Auth\LoginController@logout');

Route::get('user/savetheme/{theme_name}', 'UsersController@savetheme');
Route::get('user/savefix-header/{fix_header}', 'UsersController@savefix_header');
Route::get('user/savefix-sidebar/{fix_sidebar}', 'UsersController@savefix_sidebar');
Route::get('user/savetype-sidebar/{type?}', 'UsersController@update_type_sidebar');

Auth::routes();

//Change Image Profile
Route::get('image-crop', 'UsersController@imageCrop');
Route::post('image-crop', 'UsersController@imageCropPost');

//ดูข้อมูลนิติบุคคล
Route::get('ws/juristic', 'WS\\JuristicController@index');
Route::post('ws/juristic', 'WS\\JuristicController@index');

//ดูข้อมูลบุคคลธรรมดา
Route::get('ws/personal', 'WS\\PersonalController@index');
Route::post('ws/personal', 'WS\\PersonalController@index');

//ดูข้อมูลผู้ประกอบการ VAT จาก กรมสรรพากร
Route::get('ws/rd-vat', 'WS\\RdVatController@index');
Route::post('ws/rd-vat', 'WS\\RdVatController@index');

//ดูข้อมูลโรงงาน จาก กรมโรงงานอุตสาหกรรม
Route::get('ws/industry', 'WS\\IndustryController@index');
Route::post('ws/industry', 'WS\\IndustryController@index');

//log การเรียกใช้ Web Service
Route::get('ws/log', 'WS\\LogController@index');

//log การเรียกใช้ Web Service
Route::get('ws/moi_log', 'WS\\MOILogController@index');


#idea public frontend
Route::get('idea-public/ideas/create','Tis\IdeaPublicController@getCreateIdeas')->name('idea-public.ideas.create');
Route::post('idea-public/store-ideas', 'Tis\IdeaPublicController@storeIdeas')->name('idea-public.store-ideas');
	//ดูข้อมูลความคิดเห็นสาธารณะ
Route::put('/ideapublic/update-state', 'Tis\IdeaPublicController@update_state');
Route::resource('idea-public', 'Tis\IdeaPublicController');

Route::put('/lawoperation/update-state', 'Esurv\LawOperationController@update_state');
Route::resource('esurv/law-operation', 'Esurv\\LawOperationController');
Route::get('law-operation/control-check/{control_check_id}', 'Esurv\\LawOperationController@getControlCheck');
Route::get('law-operation/control-performance/{control_performance_id}', 'Esurv\\LawOperationController@getControlPerformance');

Route::put('besurv/tisuseresurvs/update-state', 'Besurv\TisUserEsurvsController@update_state');
Route::resource('besurv/tis-user-esurvs', 'Besurv\\TisUserEsurvsController');

Route::get('api/migrate/tis_appoints/{start?}', 'Migrate\TisController@tis_appoints');
Route::get('api/migrate/view_tis_appoints/{start?}', 'Migrate\TisController@view_tis_appoints');

Route::get('api/v1/law_pmt1.php', 'API\CertifyController@law_pmt1');
Route::get('api/v1/law_pmt2.php', 'API\CertifyController@pmt2');
Route::get('api/v1/pmt1.php', 'API\CertifyController@pmt1');
Route::get('api/v1/pmt2.php', 'API\CertifyController@pmt2');
Route::get('api/v1/mail', 'API\CertifyController@mail'); 
// เช็คการชำระ
Route::get('api/v1/export_excel', 'API\Checkbill2Controller@export_excel');
Route::get('api/v1/checkbill', 'API\Checkbill2Controller@check_bill');
Route::get('api/v1/export_excel_certilab', 'API\CertifyController@export_excel_certilab');
Route::get('api/v1/law_checkbill', 'API\Checkbill2Controller@law_checkbill');
Route::post('api/v1/law_checkbill', 'API\Checkbill2Controller@law_checkbill');

//ลบไฟล์ที่เก็บชั่วคราวไว้สำหรับโชว์ใน uploads
Route::get('schedule/delete-uploads', 'ScheduleController@delete_uploads');


Route::put('basic/expert-groups/update-state', 'Basic\ExpertGroupsController@update_state');
Route::resource('basic/expert-groups', 'Basic\\ExpertGroupsController');

Route::put('certify/register-experts/update-state', 'Certify\RegisterExpertsController@update_state');
Route::post('/register-experts','Certify\RegisterExpertsController@assign')->name('register-experts.assign');
Route::resource('certify/register-experts', 'Certify\\RegisterExpertsController');

// อ่านไฟล์ที่แนบมา   (LAB)
Route::get('certify/check_files_test/{file_name}', function($file_name)
{
        $public = public_path();
        $attach_path = 'files/applicants/check_files/';
        if(HP::checkFileStorage($file_name)){
            HP::getFileStoragePath($file_name);
            $filePath =  response()->file($public.'/uploads/'.$file_name);
            // dd($filePath);
            return $filePath;
        }else{
        return 'ไม่พบไฟล์';
        }

});

// start ระบบตรวจติดตามใบรับรองระบบงานห้องปฏิบัติการ (LAB)
Route::get('certificate/tracking-labs/delete_file/{id?}', 'Certificate\Labs\\TrackingLabsController@delete_file');
Route::get('certificate/tracking-labs/assign_labs', 'Certificate\Labs\\TrackingLabsController@assign_labs');
Route::get('certificate/tracking-labs/data_list', 'Certificate\Labs\\TrackingLabsController@data_list');
Route::get('certificate/tracking-labs/modal_status_auditor', 'Certificate\Labs\\TrackingLabsController@modal_status_auditor');
Route::resource('certificate/tracking-labs', 'Certificate\Labs\\TrackingLabsController');
// แต่งตั้งคณะฯ (LAB)
Route::post('certificate/auditor-labs/create', 'Certificate\Labs\\AuditorLabsController@create');
Route::post('certificate/auditor-labs/update_delete/{id}', 'Certificate\Labs\\AuditorLabsController@update_delete');
Route::get('certificate/auditor-labs/data_list', 'Certificate\Labs\\AuditorLabsController@data_list');
Route::resource('certificate/auditor-labs', 'Certificate\Labs\\AuditorLabsController');
Route::get('certificate/auditor-labs/create-tracking-lab-message-record/{id?}', 'Certificate\Labs\\AuditorLabsController@CreateTrackingLabMessageRecord')->name('certificate.auditor-labs.create-tracking-lab-message-record');
Route::post('certificate/auditor-labs/save-lab-tracking-message-record/{id?}', 'Certificate\Labs\\AuditorLabsController@SaveTrackingLabMessageRecord')->name('certificate.auditor-labs.save-tracking-lab-message-record');
Route::get('certificate/auditor-labs/view-lab-tracking-message-record/{id?}', 'Certificate\Labs\\AuditorLabsController@ViewTrackingLabMessageRecord')->name('certificate.auditor-labs.view-tracking-lab-message-record');

Route::get('certificate/auditor-labs/data_list', 'Certificate\Labs\\AuditorLabsController@data_list');

Route::get('certificate/auditor-tracking-assignment/', 'Certificate\Labs\\AuditorTrackingAssignmentController@index');
Route::get('certificate/auditor-tracking-assignment/data-list', 'Certificate\Labs\\AuditorTrackingAssignmentController@dataList');
Route::get('certificate/auditor-tracking-assignment/get-signer', 'Certificate\Labs\\AuditorTrackingAssignmentController@getSigner')->name('auditor_tracking_assignment.get_signer');
Route::post('certificate/auditor-tracking-assignment/sign-document', 'Certificate\Labs\\AuditorTrackingAssignmentController@signDocument')->name('auditor_tracking_assignment.signDocument');


// Pay-in ครั้งที่ 1
Route::post('certificate/tracking-labs/pay-in/{id?}', 'Certificate\Labs\\TrackingLabsController@update_payin1');
Route::get('certificate/tracking-labs/check/pay_in', 'Certificate\Labs\\TrackingLabsController@check_pay_in');
Route::get('certificate/tracking-labs/Pay_In1/{id?}', 'Certificate\Labs\\TrackingLabsController@Pay_In1');
// บันทึกผลการตรวจประเมิน
Route::post('certificate/assessment-labs/update/{id?}', 'Certificate\Labs\\AssessmentLabsController@update_assessment');
Route::get('certificate/assessment-labs/certi_labs', 'Certificate\Labs\\AssessmentLabsController@data_certi');
Route::get('certificate/assessment-labs/data_list', 'Certificate\Labs\\AssessmentLabsController@data_list');
Route::resource('certificate/assessment-labs', 'Certificate\Labs\\AssessmentLabsController');
Route::post('certificate/assessment-labs/email-to-expert','Certificate\Labs\\AssessmentLabsController@emailToExpert')->name('certificate.assessment-labs.email_to_expert');
Route::get('certificate/assessment-labs/view-lab-info/{id?}', 'Certificate\Labs\\AssessmentLabsController@viewLabInfo')->name('certificate.assessment-labs.view_lab_info');
Route::post('certificate/assessment-labs/update-lab-info', 'Certificate\Labs\\AssessmentLabsController@updateLabInfo')->name('certificate.assessment-labs.update-lab-info');

Route::get('certificate/tracking-assessment-report-assignment/', 'Certificate\Labs\\TrackingSignAssessmentReportController@index');
Route::get('certificate/tracking-assessment-report-assignment/data-list', 'Certificate\Labs\\TrackingSignAssessmentReportController@dataList')->name('certificate.assessment_report_assignment.dataList');
Route::get('certificate/tracking-assessment-report-assignment/get-signer', 'Certificate\Labs\\TrackingSignAssessmentReportController@getSigner')->name('certificate.assessment_report_assignment.get_signer');
Route::post('certificate/tracking-assessment-report-assignment/sign-document', 'Certificate\Labs\\TrackingSignAssessmentReportController@signDocument')->name('certificate.assessment_report_assignment.signDocument');
Route::get('certificate/tracking-assessment-report-assignment/get-signers', 'Certificate\Labs\\TrackingSignAssessmentReportController@apiGetSigners')->name('certificate.assessment_report_assignment.get_signers');



// สรุปผลตรวจประเมิน
Route::get('certificate/inspection-labs/{id?}', 'Certificate\Labs\\TrackingLabsController@inspection');
Route::post('certificate/tracking-labs/update_inspection/{id?}', 'Certificate\Labs\\TrackingLabsController@update_inspection');
Route::post('certificate/api/request-edit-scope', 'Certificate\Labs\\TrackingLabsController@apiRequestEditScopeFromTracking')->name('api_request_edit_scope_from_tracking');
// สรุปรายงาน
Route::post('certificate/tracking-labs/update_report/{id?}', 'Certificate\Labs\\TrackingLabsController@update_report');
// ทบทวนฯ
Route::post('certificate/tracking-labs/update_review/{id?}', 'Certificate\Labs\\TrackingLabsController@update_review');
//  Pay-in ครั้งที่ 2
Route::get('certificate/inspection-labs/pay-in2/{id?}', 'Certificate\Labs\\TrackingLabsController@pay_in2');
Route::post('certificate/tracking-labs/update_pay_in2/{id?}', 'Certificate\Labs\\TrackingLabsController@update_pay_in2');
// แนบท้าย
Route::get('certificate/tracking-labs/append/{id?}', 'Certificate\Labs\\TrackingLabsController@append');
Route::post('certificate/tracking-labs/update_append/{id?}', 'Certificate\Labs\\TrackingLabsController@update_append');
//รับเรื่องตรวจติดตาม
Route::POST('certificate/tracking-labs/save_receiver', 'Certificate\Labs\\TrackingLabsController@save_receiver');
//ตรวจติดตามก่อนกำหนด
Route::POST('certificate/tracking-labs/save_check', 'Certificate\Labs\\TrackingLabsController@save_check');
Route::get('certificate/tracking-labs/data/certificate', 'Certificate\Labs\\TrackingLabsController@data_certificate');
// end ระบบตรวจติดตามใบรับรองระบบงานห้องปฏิบัติการ (LAB)

// start ระบบตรวจติดตามใบรับรองระบบหน่วยรับรอง (CB)
Route::get('certificate/tracking-cb/delete_file/{id?}', 'Certificate\Cb\\TrackingCbController@delete_file');
Route::get('certificate/tracking-cb/assign_cb', 'Certificate\Cb\\TrackingCbController@assign_cb');
Route::get('certificate/tracking-cb/data_list', 'Certificate\Cb\\TrackingCbController@data_list');
Route::get('certificate/tracking-cb/modal_status_auditor', 'Certificate\Cb\\TrackingCbController@modal_status_auditor');
Route::resource('certificate/tracking-cb', 'Certificate\Cb\\TrackingCbController');
// แต่งตั้งคณะฯ (LAB)
Route::post('certificate/auditor-cbs/create', 'Certificate\Cb\\AuditorCbController@create');
Route::get('certificate/auditor-cbs/data_list', 'Certificate\Cb\\AuditorCbController@data_list');
Route::resource('certificate/auditor-cbs', 'Certificate\Cb\\AuditorCbController');
// Pay-in ครั้งที่ 1
Route::post('certificate/tracking-cb/pay-in/{id?}', 'Certificate\Cb\\TrackingCbController@update_payin1');
Route::get('certificate/tracking-cb/check/pay_in', 'Certificate\Cb\\TrackingCbController@check_pay_in');
Route::get('certificate/tracking-cb/Pay_In1/{id?}', 'Certificate\Cb\\TrackingCbController@Pay_In1');

// บันทึกผลการตรวจประเมิน
Route::post('certificate/assessment-cb/update/{id?}', 'Certificate\Cb\\AssessmentCbController@update_assessment');
Route::get('certificate/assessment-cb/certi_cb', 'Certificate\Cb\\AssessmentCbController@data_certi');
Route::get('certificate/assessment-cb/data_list', 'Certificate\Cb\\AssessmentCbController@data_list');
Route::resource('certificate/assessment-cb', 'Certificate\Cb\\AssessmentCbController');
// สรุปผลตรวจประเมิน
Route::get('certificate/inspection-cb/{id?}', 'Certificate\Cb\\TrackingCbController@inspection');
Route::post('certificate/tracking-cb/update_inspection/{id?}', 'Certificate\Cb\\TrackingCbController@update_inspection');
// สรุปรายงาน
Route::post('certificate/tracking-cb/update_report/{id?}', 'Certificate\Cb\\TrackingCbController@update_report');
// ทบทวนฯ
Route::post('certificate/tracking-cb/update_review/{id?}', 'Certificate\Cb\\TrackingCbController@update_review');
//  Pay-in ครั้งที่ 2
Route::get('certificate/tracking-cb/pay-in2/{id?}', 'Certificate\Cb\\TrackingCbController@pay_in2');
Route::post('certificate/tracking-cb/update_pay_in2/{id?}', 'Certificate\Cb\\TrackingCbController@update_pay_in2');
// แนบท้าย
Route::get('certificate/tracking-cb/append/{id?}', 'Certificate\Cb\\TrackingCbController@append');
Route::post('certificate/tracking-cb/update_append/{id?}', 'Certificate\Cb\\TrackingCbController@update_append');
//รับเรื่องตรวจติดตาม
Route::POST('certificate/tracking-cb/save_receiver', 'Certificate\Cb\\TrackingCbController@save_receiver');
//ตรวจติดตามก่อนกำหนด
Route::POST('certificate/tracking-cb/save_check', 'Certificate\Cb\\TrackingCbController@save_check');
Route::get('certificate/tracking-cb/data/certificate', 'Certificate\Cb\\TrackingCbController@data_certificate');
// end ระบบตรวจติดตามใบรับรองระบบหน่วยรับรอง (CB)

// start ระบบตรวจติดตามใบรับรองระบบหน่วยตรวจ (IB)
Route::get('certificate/tracking-ib/delete_file/{id?}', 'Certificate\Ib\\TrackingIbController@delete_file');
Route::get('certificate/tracking-ib/assign_ib', 'Certificate\Ib\\TrackingIbController@assign_ib');
Route::get('certificate/tracking-ib/data_list', 'Certificate\Ib\\TrackingIbController@data_list');
Route::get('certificate/tracking-ib/modal_status_auditor', 'Certificate\Ib\\TrackingIbController@modal_status_auditor');
Route::resource('certificate/tracking-ib', 'Certificate\Ib\\TrackingIbController');
// แต่งตั้งคณะฯ (IB)
Route::post('certificate/auditor-ibs/create', 'Certificate\Ib\\AuditorIbController@create');
Route::post('certificate/auditor-ibs/update_delete/{id}', 'Certificate\Ib\\AuditorIbController@update_delete');
Route::get('certificate/auditor-ibs/data_list', 'Certificate\Ib\\AuditorIbController@data_list');
Route::resource('certificate/auditor-ibs', 'Certificate\Ib\\AuditorIbController');
// Pay-in ครั้งที่ 1
Route::post('certificate/tracking-ib/pay-in/{id?}', 'Certificate\Ib\\TrackingIbController@update_payin1');
Route::get('certificate/tracking-ib/check/pay_in', 'Certificate\Ib\\TrackingIbController@check_pay_in');
Route::get('certificate/tracking-ib/Pay_In1/{id?}', 'Certificate\Ib\\TrackingIbController@Pay_In1');
// บันทึกผลการตรวจประเมิน
Route::post('certificate/assessment-ib/update/{id?}', 'Certificate\Ib\\AssessmentIbController@update_assessment');
Route::get('certificate/assessment-ib/certi_ib', 'Certificate\Ib\\AssessmentIbController@data_certi');
Route::get('certificate/assessment-ib/data_list', 'Certificate\Ib\\AssessmentIbController@data_list');
Route::resource('certificate/assessment-ib', 'Certificate\Ib\\AssessmentIbController');
// สรุปผลตรวจประเมิน
Route::get('certificate/inspection-ib/{id?}', 'Certificate\Ib\\TrackingIbController@inspection');
Route::post('certificate/tracking-ib/update_inspection/{id?}', 'Certificate\Ib\\TrackingIbController@update_inspection');
// สรุปรายงาน
Route::post('certificate/tracking-ib/update_report/{id?}', 'Certificate\Ib\\TrackingIbController@update_report');
// ทบทวนฯ
Route::post('certificate/tracking-ib/update_review/{id?}', 'Certificate\Ib\\TrackingIbController@update_review');
//  Pay-in ครั้งที่ 2
Route::get('certificate/tracking-ib/pay-in2/{id?}', 'Certificate\Ib\\TrackingIbController@pay_in2');
Route::post('certificate/tracking-ib/update_pay_in2/{id?}', 'Certificate\Ib\\TrackingIbController@update_pay_in2');
// แนบท้าย
Route::get('certificate/tracking-ib/append/{id?}', 'Certificate\Ib\\TrackingIbController@append');
Route::post('certificate/tracking-ib/update_append/{id?}', 'Certificate\Ib\\TrackingIbController@update_append');
//รับเรื่องตรวจติดตาม
Route::POST('certificate/tracking-ib/save_receiver', 'Certificate\Ib\\TrackingIbController@save_receiver');
//ตรวจติดตามก่อนกำหนด
Route::POST('certificate/tracking-ib/save_check', 'Certificate\Ib\\TrackingIbController@save_check');
Route::get('certificate/tracking-ib/data/certificate', 'Certificate\Ib\\TrackingIbController@data_certificate');
// end ระบบตรวจติดตามใบรับรองระบบหน่วยตรวจ (IB)

//รายงาน Power BI
Route::get('report-power-bi', 'Report\PowerBIController@index');
Route::get('report-power-bi/{id?}', 'Report\PowerBIController@show');

Route::get('env/info', function()
{
    return view('env.view');

});

Route::post('/certify/api/test','Certify\ApiController@apiTest')->name('api.test');
Route::post('/certify/api/calibrate','Certify\ApiController@apiCalibrate')->name('api.calibrate');

Route::post('/certify/api/instrumentgroup','Certify\ApiController@apiInstrumentGroup')->name('api.instrumentgroup');
Route::post('/certify/api/instrument','Certify\ApiController@apiInstrumentAndParameter')->name('api.instrument_and_parameter');

Route::post('/certify/api/get_certificated','Certify\ApiController@apiGetCertificated')->name('api.get_certificated');
Route::post('/certify/api/get_scope','Certify\ApiController@apiGetScope')->name('api.get_scope');

Route::get('/estandard', function () {
    return view('estandard.index'); // Display the upload form
});

Route::post('/isbn/upload', 'Estandard\isbn\EstandardIsbnController@uploadData')->name('isbn.upload-data');
Route::get('/isbn/status', 'Estandard\isbn\EstandardIsbnController@checkStatus')->name('isbn.status');