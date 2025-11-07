<?php

//D227 ระบบแต่งตั้งคณะผู้ตรวจประเมิน
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;

Route::group(['middleware' => 'auth'],function (){

    Route::get('certify/function/check_api_pid', function(Request $request)
    {
        $table = $request->table;

        if( (new CertiLab)->getTable() == $table ){
            $data  =  CertiLab::findOrFail($request->id);
        }else if( (new CertiCb)->getTable() == $table ){
            $data  =  CertiCb::findOrFail($request->id);
        }else if( (new CertiIb)->getTable() == $table ){
            $data  =  CertiIb::findOrFail($request->id);
        }

        return response()->json([ 'message' =>  HP_API_PID::CheckDataApiPid( $data , $table )  ]);

    });


    
Route::get('certify/auditor/create', 'BoardAuditorController@create');
Route::post('certify/auditor/create', 'BoardAuditorController@create');
Route::get('certify/auditor', 'BoardAuditorController@index')->name('certify.auditor.index');
Route::get('certify/auditor/{ba}', 'BoardAuditorController@show');
Route::post('certify/auditor', 'BoardAuditorController@store');
Route::get('certify/auditor/{ba}/edit/{app?}', 'BoardAuditorController@edit');
Route::put('certify/auditor/{ba}', 'BoardAuditorController@update');
Route::delete('certify/auditor/multiple/{app?}', 'BoardAuditorController@destroyMultiple');
Route::delete('certify/auditor/{ba}/{app?}', 'BoardAuditorController@destroy');
Route::get('certify/auditor/status/{id}/{app_id?}', 'BoardAuditorController@getAuditorFromStatus'); // ของมาค
Route::get('certify/api/auditor/{ba?}', 'BoardAuditorController@apiGetAuditor')->name('board_auditor.api.get');
Route::get('certify/auditor/certi_no/{id}', 'BoardAuditorController@DataCertiNo'); 
Route::get('certify/auditor/delete-file/{id?}', 'BoardAuditorController@DeleteFile');
Route::get('certify/auditor/delete-attach/{id?}', 'BoardAuditorController@DeleteAttach');
Route::post('certify/auditor/update_delete/{id?}', 'BoardAuditorController@update_delete');
Route::get('certify/auditor/create-lab-message-record/{id?}', 'BoardAuditorController@CreateLabMessageRecord')->name('certify.create_lab_message_record');
Route::post('certify/auditor/save-lab-message-record/{id?}', 'BoardAuditorController@SaveLabMessageRecord')->name('save.create_lab_message_record');
Route::get('certify/auditor/view-lab-message-record/{id?}', 'BoardAuditorController@ViewLabMessageRecord')->name('view.create_lab_message_record');

Route::get('certify/auditor/create-lab-message-record-pdf/{id?}', 'BoardAuditorController@CreateLabMessageRecordPdf')->name('create.create_lab_message_record_pdf');

Route::post('certify/api/text-splitter', 'BoardAuditorController@apiTextSplitter')->name('certify.api.test_splitter');

Route::get('certify/auditor/files/{path}/{filename}', function($path,$filename)
{

    $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
    $filePath =  response()->download($public.'files/' . $path.'/'.$filename);
    return $filePath;
    // $filePath = storage_path().'/files/board_auditor_files/'.$path;
    // if (!File::exists($filePath)) {
    //     return Response::make("File does not exist.", 404);
    // }

    // $fileContents = File::get($filePath);
    // $type = File::mimeType($filePath);

    // if ($type == 'application/pdf'){
    //     return response()->file($filePath, [
    //         'Content-Type' => 'application/pdf'
    //     ]);
    // }
    // else {
    //     return Response::make($fileContents, 200)->header("Content-Type", 'blob');

    // }
//    return Response::download($filePath);
})->name('board_auditor.file');

// Route::get('certify/auditor/files/{path}/{filename}', function($path, $filename)
// {
//     $filePath = storage_path().'/files/'.$path.'/'.$filename;
//     if (!File::exists($filePath)) {
//         return Response::make("File does not exist.", 404);
//     }

//     $fileContents = File::get($filePath);
//     $type = File::mimeType($filePath);

//     if ($type == 'application/pdf'){
//         return response()->file($filePath, [
//             'Content-Type' => 'application/pdf'
//         ]);
//     }
//     else {
//         return Response::make($fileContents, 200)->header("Content-Type", 'blob');

//     }
// //    return Response::download($filePath);
// })->name('board_auditor.file');

Route::get('certify', function () {
    return view('admin.certify');
});



// D225 ระบบข้อมูลคณะกรรมการเฉพาะด้าน
Route::get('certify/committee', function (){
	return view('certify/committee/index');
});
Route::get('certify/committee/create', function (){
    $departments = DB::table('basic_departments')->where('state',1)->get();
	return view('certify/committee/create',['departments'=>$departments]);
});

Route::resource('committee','CommitteeSpecialController'); // submit form กรรมการเฉพาะด้าน

Route::post('certificate/api/getCheckExistLevel.api', [
    'as' => 'certificate.api.getCheckExistLevel',
    'uses' => 'CertificateController@getCheckExistLevel']);

Route::get('committee/authorize/file/{path}', function($path)
{
    $filePath = storage_path().'/files/authorize_files/'.$path;
    if (!File::exists($filePath)) {
        return Response::make("File does not exist.", 404);
    }
    return Response::download($filePath);
});

Route::get('committee/appointment/files/{path}', function($path)
{
    $filePath = storage_path().'/files/appointment_files/'.$path;
    if (!File::exists($filePath)) {
        return Response::make("File does not exist.", 404);
    }
    return Response::download($filePath);
});

Route::get('committee/appointment/file/delete/{type}/{token}/{path}', 'CommitteeSpecialController@removeFiles')->name('committee.file.delete');
Route::get('committee/in/department/delete/{token}', 'CommitteeSpecialController@removeCommitteeInDepartment')->name('committee.in.department.delete');
Route::post('committee/in/department/update/{token}', 'CommitteeSpecialController@updateCommitteeInDepartment')->name('committee.in.department.update');
Route::get('/committee/get_position_name/{expert_id}/{group_id?}', 'CommitteeSpecialController@get_position_name');
Route::get('/committee/get_expert_groups/{id}', 'CommitteeSpecialController@get_expert_groups');


// D226 ระบบข้อมูลใบรับรองระบบงาน
Route::resource('certificate','CertificateController'); // resource certificate
Route::put('certificate/update/state', 'CertificateController@update_state')->name('certificate.update.state');
Route::get('certificate/file/delete/{type}/{token}/{path}', 'CertificateController@removeFiles')->name('certificate.file.delete');

Route::post('certificate/api/getApplicantType.api', [
    'as' => 'certificate.api.getApplicantType',
    'uses' => 'CertificateController@getApplicantType']);

Route::post('certificate/api/getBranch.api', [
    'as' => 'certificate.api.getBranch',
    'uses' => 'CertificateController@getBranch']);

Route::get('certificate/files/{path}', function($path)
{
    $filePath = storage_path().'/files/certificate_files/'.$path;
    if (!File::exists($filePath)) {
        return Response::make("File does not exist.", 404);
    }
    return Response::download($filePath);
});

Route::get('certificate/files/others/{path}', function($path)
{
    $filePath = storage_path().'/files/otherCertificate_files/'.$path;
    if (!File::exists($filePath)) {
        return Response::make("File does not exist.", 404);
    }
    return Response::download($filePath);
});

Route::get('certify/delete-files/{id?}/{url_send?}', function( $id, $url_send){

    $attach =  App\AttachFile::findOrFail($id);
    if( !empty($attach) && !empty($attach->url) ){

        if( HP::checkFileStorage( '/'.$attach->url) ){
            Storage::delete( '/'.$attach->url );

            $attach->delete();
        }

        return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');

    }
    
});

//Route::get('certify/certificate', function (){
//	return view('certify/certificate/index');
//});
//Route::get('certify/certificate/create', function (){
//	return view('certify/certificate/create');
//});

// D229 ระบบคณะทบทวนผลการตรวจประเมิน
Route::group(['prefix' => 'certify'], function () {

 
    Route::group(['prefix' => 'board_review'], function () {
        Route::get('/','Certify\BoardReviewController@index');
        Route::post('/','Certify\BoardReviewController@store');
        Route::get('/create','Certify\BoardReviewController@create');
        Route::delete('/{ba}', 'Certify\BoardReviewController@destroy');
        Route::delete('/multiple', 'Certify\BoardReviewController@destroyMultiple');
        Route::get('/{board}', 'Certify\BoardReviewController@show');
        Route::get('/{board}/edit', 'Certify\BoardReviewController@edit');
        Route::put('/{board}/edit', 'Certify\BoardReviewController@update');
        Route::get('/files/{path}', function($path)
        {
            $filePath = storage_path().'/files/board_review_files/'.$path;
            if (!File::exists($filePath)) {
                return Response::make("File does not exist.", 404);
            }
            return Response::download($filePath);
        });
    });

    // D231
    Route::group(['prefix' => 'check_certificate'], function () {
    
        Route::get('/','Certify\CheckCertificateLabController@index')->name('check_certificate.index');
        Route::get('/api/get/files/{cc}','Certify\CheckCertificateLabController@apiGetFiles')->name('check_certificate.api.get.file');
        Route::get('/{cc}/show','Certify\CheckCertificateLabController@show')->name('check_certificate.show');
        Route::post('/assign','Certify\CheckCertificateLabController@assign')->name('check_certificate.assign');
        Route::post('/update/status/pay_in1/{id?}','Certify\CheckCertificateLabController@DataPayIn'); // แนบใบ Pay-in ครั้งที่ 1
        Route::post('/update/status_confirmed/pay_in1/{id?}','Certify\CheckCertificateLabController@DataStatusConfirmed');
        Route::get('/Print/Pay_In1/{amount?}/{start_date?}/{id?}', 'Certify\\CheckCertificateLabController@GetPayInOnePrint');
        Route::post('/update/report_assessments/{id?}','Certify\CheckCertificateLabController@ReportAssessments');
        Route::post('/update/status/cost_certificate/{id?}','Certify\CheckCertificateLabController@CostCertificate');
        Route::post('/update/status/receive_certificate/{id?}','Certify\CheckCertificateLabController@ReceiveCertificate');
        Route::post('/update_attach','Certify\CheckCertificateLabController@UpDateAttach');

        Route::post('/ask-to-edit-lab-scope','Certify\CheckCertificateLabController@askToEditLabScope')->name('check_certificate-lab.ask-to-edit-lab-scope');

        Route::get('/export_word/{id?}','Certify\CheckCertificateLabController@export_word');

        Route::get('/generate-pdf-lab-cal-scope/{id?}','Certify\CheckCertificateLabController@generatePdfLabCalScope')->name('certify.generate_pdf_lab_cal_scope');
        Route::get('/generate-pdf-lab-test-scope/{id?}','Certify\CheckCertificateLabController@generatePdfLabTestScope')->name('certify.generate_pdf_lab_test_scope');

        Route::put('/update/{cc}','Certify\CheckCertificateLabController@update')->name('check_certificate.update');
        Route::get('/api/get/app/{app}','Certify\CheckCertificateLabController@apiGetApp')->name('check_certificate.api.get.app');
        Route::get('/data_show/{id?}','Certify\CheckCertificateLabController@DataShow');
        Route::get('/delete_file/{id?}','Certify\CheckCertificateLabController@DeleteReportFile');
        Route::get('/delete_attach/{id?}','Certify\CheckCertificateLabController@delete_attach');

        Route::get('/Pay_In1/{id?}','Certify\CheckCertificateLabController@update_payin1');

        Route::get('/Pay_In2/{id?}/{token?}', 'Certify\CheckCertificateLabController@GetPayInTwo');
        Route::get('/Print/Pay_In2/{amount?}/{start_date?}/{id?}/{state?}', 'Certify\CheckCertificateLabController@GetPayInTwoPrint'); 
        Route::post('/check_pay_in_lab','Certify\CheckCertificateLabController@check_pay_in_lab');
        Route::post('/update_delete','Certify\CheckCertificateLabController@update_delete');
        Route::get('certificate/download/{filename}', function($filename) {

            $storagePath  = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();

            $filePath = $storagePath . 'files/applicants/check_files/' . $filename;

            if (!File::exists($filePath)) {
                return Response::make($filePath, 404);
            }

            $fileContents = File::get($filePath);
            $type = File::mimeType($filePath);

            if ($type == 'application/pdf') {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf'
                ]);
            } else {
                return Response::make($fileContents, 200)->header("Content-Type", 'blob');
            }
        })->name('show.file');

        Route::get('/check_api_pid/labs', 'Certify\CheckCertificateLabController@check_api_pid');
        
    });

    // D232
    Route::group(['prefix' => 'check_assessment'], function () {
        Route::get('/','Certify\CheckAssessmentController@index')->name('check_assessment.index');
        Route::get('/{ca}/show','Certify\CheckAssessmentController@show')->name('check_assessment.show');
        Route::post('/assign','Certify\CheckAssessmentController@assign')->name('check_assessment.assign');
        Route::put('/agree/update/{ca}','Certify\CheckAssessmentController@agree')->name('check_assessment.agree');
        Route::put('/cost/update/{cost}','Certify\CheckAssessmentController@updateCost')->name('check_assessment.update.cost');
        Route::put('/report/update/{report}','Certify\CheckAssessmentController@updateReport')->name('check_assessment.update.report');
        Route::put('/cost/certificate/update/{costcertificate}','Certify\CheckAssessmentController@updateCostCertificate')->name('check_assessment.update.cost_certificate');
        Route::put('/cost/confirmed/update/{cost}','Certify\CheckAssessmentController@updateCostConfirmed')->name('check_assessment.update.cost.confirm');
        Route::put('/status/update/{ca}','Certify\CheckAssessmentController@updateStatus')->name('check_assessment.update.status');
        Route::put('/notice/confirmed/update/{ca}','Certify\CheckAssessmentController@updateNoticeConfirmed')->name('check_assessment.update.status.notice');
        Route::post('/api/store','Certify\CheckAssessmentController@apiStore')->name('check_assessment.api.store');
        Route::get('/api/get/auditors','Certify\CheckAssessmentController@apiGetAuditors')->name('check_assessment.api.get.auditors');
        Route::get('/api/get/groups/{ca}','Certify\CheckAssessmentController@apiGetGroups')->name('check_assessment.api.get.groups');
    });

    Route::get('certificate-applicant/show/{certilab}','Certify\CheckAssessmentController@showCertificateLabDetail')->name('show.certificate.applicant.detail');
    Route::get('certificate-applicant/edit/{certilab}','Certify\CheckAssessmentController@edit')->name('show.certificate.applicant.edit');
    Route::post('certificate-applicant/update/{certilab}','Certify\CheckAssessmentController@update')->name('show.certificate.applicant.update');

    Route::group(['prefix' => 'request-service'],function (){
        Route::get('/list','RequestServiceController@index')->name('request.service.index');
    });

    // D233
    Route::group(['prefix' => 'estimated_cost'], function () {
        Route::get('/','Certify\EstimatedCostController@index')->name('estimated_cost.index');
        Route::get('/create/{app?}','Certify\EstimatedCostController@create')->name('estimated_cost.create');
        Route::put('/store/{app?}','Certify\EstimatedCostController@store')->name('estimated_cost.store');
        Route::put('/update/{cost}/{app?}','Certify\EstimatedCostController@update')->name('estimated_cost.update');
        Route::get('/{ca}/show','Certify\EstimatedCostController@show')->name('estimated_cost.show');
        Route::get('/{ec}/edit/{app?}','Certify\EstimatedCostController@edit')->name('estimated_cost.edit');
        Route::post('/api/store','Certify\EstimatedCostController@apiStore')->name('estimated_cost.api.store');
        Route::get('/api/get/app/{app_no?}','Certify\EstimatedCostController@apiGetApp')->name('estimated_cost.api.get.app');
        Route::delete('/{ec}/destroy','Certify\EstimatedCostController@destroy')->name('estimated_cost.destroy');
        Route::delete('/destroy/multiple/{app?}','Certify\EstimatedCostController@destroyMultiple')->name('estimated_cost.destroy.multiple');
        Route::get('/delete_file/{cost?}/{keys?}','Certify\EstimatedCostController@delete_file');
        Route::get('/data/app_no/{id?}','Certify\EstimatedCostController@GetDataAppNo');
    });

    // D234
    Route::group(['prefix' => 'save_assessment'], function () {
        Route::get('/','Certify\SaveAssessmentController@index')->name('save_assessment.index');
        Route::get('/create/{app?}','Certify\SaveAssessmentController@create')->name('save_assessment.create');
        Route::get('/create/{board_auditor_id?}','Certify\SaveAssessmentController@create')->name('save_assessment.create');
        Route::get('/create-expert/{board_auditor_id?}','Certify\SaveAssessmentController@createExpert')->name('save_assessment.create_expert');
        
        Route::put('/store/{app?}','Certify\SaveAssessmentController@store')->name('save_assessment.store');
        Route::post('/status/update','Certify\SaveAssessmentController@updateStatus')->name('save_assessment.status.update');
        Route::put('/update/{notice}/{app?}','Certify\SaveAssessmentController@update')->name('save_assessment.update');
        Route::get('/{notice}/edit/{app?}','Certify\SaveAssessmentController@edit')->name('save_assessment.edit');
        Route::post('/api/store','Certify\SaveAssessmentController@apiStore')->name('save_assessment.api.store');
        Route::get('/api/get/app/{app_no?}','Certify\SaveAssessmentController@apiGetApp')->name('save_assessment.api.get.app');
        Route::delete('/{notice}/destroy','Certify\SaveAssessmentController@destroy')->name('save_assessment.destroy');
        Route::delete('/destroy/multiple/{app?}','Certify\SaveAssessmentController@destroyMultiple')->name('save_assessment.destroy.multiple');
        Route::get('/api/get/notices/{ca}','Certify\SaveAssessmentController@apiGetNotices')->name('save_assessment.api.get.notices');

        Route::post('/email-to-expert','Certify\SaveAssessmentController@emailToExpert')->name('save_assessment.email_to_expert');
        Route::post('/check-complete-report-one-sign','Certify\SaveAssessmentController@checkCompleteReportOneSign')->name('save_assessment.check_complete_report_one_sign');
        Route::post('/check-complete-report-two-sign','Certify\SaveAssessmentController@checkCompleteReportTwoSign')->name('save_assessment.check_complete_report_two_sign');

        Route::get('/{notice}/assess_edit/{app?}','Certify\SaveAssessmentController@assess_edit')->name('save_assessment.assess_edit');
        Route::put('/assess_update/{notice}/{app?}','Certify\SaveAssessmentController@assess_update')->name('save_assessment.assess_update');
        Route::put('/assessupdate/{notice}/{app?}','Certify\SaveAssessmentController@assessupdate')->name('save_assessment.assessupdate');

        Route::get('/show/{id?}','Certify\SaveAssessmentController@show');
        Route::get('/remove_file/{id?}','Certify\SaveAssessmentController@RemoveFile');
        Route::get('/remove_attachs/{id?}/{keys?}','Certify\SaveAssessmentController@RemoveAttachs');
        Route::get('/remove_file_scope/{id?}','Certify\SaveAssessmentController@RemoveFileScope');
        Route::get('/remove_file_car/{id?}/{keys?}','Certify\SaveAssessmentController@RemoveAttachsCar');

        Route::get('/create-lab-info/{notice_id?}','Certify\SaveAssessmentController@createLabInfo')->name('save_assessment.create_lab_info');
        Route::post('/save-lab-info','Certify\SaveAssessmentController@saveLabInfo')->name('save_assessment.save_lab_info');

        Route::get('/view-lab-info/{id?}','Certify\SaveAssessmentController@viewLabInfo')->name('save_assessment.view_lab_info');
        Route::post('/update-lab-info','Certify\SaveAssessmentController@updateLabInfo')->name('save_assessment.update_lab_info');


        Route::get('/view-lab-report2-info/{id?}', 'Certify\SaveAssessmentController@viewLabReportTwoInfo')->name('save_assessment.view_lab_report2_info');
        Route::post('/update-report2-lab-info', 'Certify\SaveAssessmentController@updateLabReportTwoInfo')->name('save_assessment.update_lab_report2_info');


        Route::post('/api/request-edit-scope','Certify\SaveAssessmentController@apiRequestEditScope')->name('save_assessment.api.request_edit_scope');
        Route::post('/api/request-admin-group-scope-sign','Certify\SaveAssessmentController@requestAdminGroupScopeSign')->name('save_assessment.api.request_admin_group_scope_sign');
        
    });

    Route::group(['prefix' => 'alert'], function () {
        Route::get('/setting','CertificateController@alertSettingPage')->name('alert.setting.page');
        Route::post('/setting/store','CertificateController@alertSetting')->name('setting.store');
        Route::get('check/expire/date','CertificateController@checkEXP')->name('certificate.check.exp');
    });

    // D235 ออกใบรับรองงาน
    Route::group(['prefix' => 'certificate-export'], function () {
        Route::get('/'                          ,'Certify\CertificateExportController@index')->name('certificate-export.index');
        Route::get('/create/{lang}'             ,'Certify\CertificateExportController@create')->name('certificate-export.create');
        Route::post('/create/{lang}'            ,'Certify\CertificateExportController@createStore')->name('certificate-export.create.store');
        Route::get('/{cer}/edit'                ,'Certify\CertificateExportController@edit')->name('certificate-export.edit');
        Route::post('/{cer}/edit'               ,'Certify\CertificateExportController@editStore')->name('certificate-export.edit.store');

        //Print
        Route::get('/{request}/{lang}/pdf'       ,'Certify\CertificateExportController@printPDF')->name('certificate-export.printPDF');
        Route::get('/{request}/{lang}/pdf/scope' ,'Certify\CertificateExportController@printPDFScope')->name('certificate-export.printPDFScope');

        // APIs
        Route::get('/api/getAddress.api'        ,'Certify\CertificateExportController@apiGetAddress')->name('certificate-export.apiGetAddress');
        Route::get('/api/getYear.api'           ,'Certify\CertificateExportController@getYear')->name('certificate-export.getYear');
        Route::get('/api/getScope.api'          ,'Certify\CertificateExportController@getScope')->name('certificate-export.getScope');


    });


    Route::group(['prefix' => 'certificate_detail'], function () { 
        Route::post('/del_attach','Certify\CheckCertificateLabController@del_attach');
        Route::get('/{token?}','Certify\CheckCertificateLabController@certificate_detail');
        Route::post('/update_document','Certify\CheckCertificateLabController@update_document');
   
    });
    // Route::get('/check/files/assessment/{filename}', function( $filename)
    // {
    //     $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
    //     $filePath =  response()->download($public.'/files/applicants/save_assessment/' . $filename);
    //     return $filePath;
    // });



    // Route::get('/check/files/{filename}', function( $filename)
    // {
    //     $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
    //     $filePath =  response()->download($public.'/files/applicants/check_files/' . $filename);
    //     return $filePath;
        // $filePath = storage_path().'/files/applicants/'.$path.'/'.$filename;
        // if (!File::exists($filePath)) {
        //     return Response::make("File does not exist.", 404);
        // }

        // $fileContents = File::get($filePath);
        // $type = File::mimeType($filePath);

        // if ($type == 'application/pdf'){
        //     return response()->file($filePath, [
        //         'Content-Type' => 'application/pdf'
        //     ]);
        // }
        // else {
        //     return Response::make($fileContents, 200)->header("Content-Type", 'blob');

        // }
//    return Response::download($filePath);
    // })->name('applicants.file');


    Route::post('/download-ib-template','IbPdfGeneratorController@loadIbTemplate')->name('download-ib-template');
    Route::post('/save-ib-template','IbPdfGeneratorController@saveHtml')->name('ib.save-html-template');


    

    Route::get('/doc-review-ib-template/{id}','IbPdfGeneratorController@docReviewHtml')->name('ib.doc-review-html-template');
    Route::post('/save-doc-review-ib-template','IbPdfGeneratorController@saveDocReviewHtml')->name('ib.save-doc-review-html-template');
    Route::post('/download-doc-review-ib-template','IbPdfGeneratorController@loadIbDocReviewTemplate')->name('ib.download-doc-review-html-template');
    Route::post('/ib-default-download-doc-review-html-template','IbPdfGeneratorController@loadDefaultIbDocReviewTemplate')->name('ib.default-download-doc-review-html-template');


    Route::get('/summary-report-ib-template/{id}','IbPdfGeneratorController@summaryReportHtml')->name('ib.summary-report-html-template');
    Route::post('/save-summary-report-ib-template','IbPdfGeneratorController@savesummaryReportHtml')->name('ib.save-summary-report-html-template');
    Route::post('/download-summary-report-ib-template','IbPdfGeneratorController@loadIbSummaryReportTemplate')->name('ib.download-summary-report-html-template');
    Route::post('/ib-default-download-summary-report-html-template','IbPdfGeneratorController@loadDefaultSummaryReportHtmlTemplate')->name('ib.default-download-summary-report-html-template');


    Route::get('/show-ib-editor/{templateType}/{assessmentId}','IbPdfGeneratorController@showEditor')->name('ib.editor.show');
    Route::get('/ib-generate-pdf-from-db','IbPdfGeneratorController@generatePdfFromDb')->name('ib.generate-pdf-from-db');


    Route::get('/ib-doc-assessment-review-html/{id}','IbPdfGeneratorController@docAssessmentReviewHtml')->name('ib.doc-assessment-review-html');
    Route::post('/save-ib-doc-assessment-review-html','IbPdfGeneratorController@saveAssessmentReviewHtml')->name('ib.save-assessment-review-html-template');
    Route::post('/download-ib-doc-assessment-review-html','IbPdfGeneratorController@downloadAssessmentReviewHtml')->name('ib.download-assessment-review-html-template');
    Route::post('/ib-default-download-doc-assessment-review-html','IbPdfGeneratorController@downloadDefaultDocAssessmentReviewHtml')->name('ib.default-download-assessment-review-html-template');


    Route::get('/ib-tangtung-tobtoun-html/{id}','IbPdfGeneratorController@tangtungTobtounReviewHtml')->name('ib.tangtung-tobtoun-html');
    Route::post('/save-ib-tangtung-tobtoun-html','IbPdfGeneratorController@saveTangtungTobtounHtml')->name('ib.save-tangtung-tobtoun-template');
    Route::post('/download-ib-tangtung-tobtoun-html','IbPdfGeneratorController@downloadTangtungTobtounHtml')->name('ib.download-tangtung-tobtoun-template');
    Route::post('/ib-default-download-tangtung-tobtoun-html','IbPdfGeneratorController@downloadDefaultTangtungTobtounHtml')->name('ib.default-download-tangtung-tobtoun-template');



    Route::get('/ib-doc-result-review-html/{id}','IbPdfGeneratorController@docResultReviewHtml')->name('ib.doc-result-review-html');
    Route::post('/save-ib-doc-result-review-html','IbPdfGeneratorController@saveResultReviewHtml')->name('ib.save-result-review-html-template');
    Route::post('/download-ib-doc-result-review-html','IbPdfGeneratorController@downloadResultReviewHtml')->name('ib.download-result-review-html-template');
    Route::post('/ib-default-download-doc-result-review-html','IbPdfGeneratorController@downloadDefaultDocResultReviewHtml')->name('ib.default-download-result-review-html-template');


    Route::put('/checkcertificateib/update-state', 'Certify\IB\CheckCertificateIBController@update_state');
    Route::resource('/check_certificate-ib', 'Certify\IB\\CheckCertificateIBController');
    Route::post('/check_certificate-ib','Certify\IB\CheckCertificateIBController@assign')->name('check_certificate-ib.assign');
    Route::get('check_certificate-ib/show/{certiIb}','Certify\IB\CheckCertificateIBController@showCertificateIbDetail');
    Route::get('/check_certificate-ib/data_show/{id?}','Certify\IB\CheckCertificateIBController@DataShow');
    // Pay-in ครั้งที่ 1
    Route::get('/check_certificate-ib/Pay_In1/{id?}/{token?}', 'Certify\IB\\CheckCertificateIBController@GetIBPayInOne');
    Route::post('/check_certificate-ib/pay-in/{id?}', 'Certify\IB\\CheckCertificateIBController@CertiIBPayInOne');
    Route::get('/check_certificate-ib/Print/Pay_In1/{amount?}/{start_date?}/{id?}', 'Certify\IB\\CheckCertificateIBController@GetIBPayInOnePrint');
    Route::post('/check_certificate_ib/check_pay_in_ib','Certify\IB\\CheckCertificateIBController@check_pay_in_ib');
    // สรุปรายงานและเสนออนุกรรมการฯ

    Route::post('/check_certificate-ib/save-review/{id?}', 'Certify\IB\\CheckCertificateIBController@SaveReview');
    Route::post('/check_certificate-ib/ask-to-edit-ib-scope', 'Certify\IB\\CheckCertificateIBController@askToEditIbScope')->name('check_certificate-ib.ask-to-edit-ib-scope');
    

    Route::post('/check_certificate-ib/report/{id?}', 'Certify\IB\\CheckCertificateIBController@UpdateReport');
    // แนบใบ Pay-in ครั้งที่ 2
    Route::get('/check_certificate-ib/Pay_In2/{id?}/{token?}', 'Certify\IB\\CheckCertificateIBController@GetIBPayInTwo');
    Route::post('/check_certificate-ib/create/pay-in2/{id?}', 'Certify\IB\\CheckCertificateIBController@CreatePayInTwo');
    Route::post('/check_certificate-ib/update/pay-in2/{id?}', 'Certify\IB\\CheckCertificateIBController@UpdatePayInTwo');
    Route::post('/check_certificate-ib/update/update_attach/{id?}', 'Certify\IB\\CheckCertificateIBController@UpdateAttacho');
    Route::get('/check_certificate-ib/Print/Pay_In2/{amount?}/{start_date?}/{id?}/{state?}', 'Certify\IB\\CheckCertificateIBController@GetIBPayInTwoPrint');

    Route::get('check_certificate-ib/check_api_pid/ibs', 'Certify\IB\\CheckCertificateIBController@check_api_pid');
    Route::post('/check_certificate-ib/update_delete','Certify\IB\\CheckCertificateIBController@update_delete');

    //การประมาณค่าใช้จ่าย (IB)
    Route::resource('estimated_cost-ib', 'Certify\IB\\EstimatedCostIBController');
    Route::get('estimated_cost-ib/app_no/{id?}','Certify\IB\EstimatedCostIBController@GetDataTraderOperaterName');
    Route::get('estimated_cost-ib/delete_file/{id?}/{keys?}','Certify\IB\EstimatedCostIBController@delete_file');


   //แต่งตั้งคณะผู้ตรวจประเมินเอกสาร (IB)
   Route::get('/auditor_ib_doc_review/auditor_ib_doc_review_index', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_index')->name('auditor_ib_doc_review_index');
   Route::get('/auditor_ib_doc_review/auditor_ib_doc_review/{id}', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review')->name('auditor_ib_doc_review');
   Route::post('/auditor_ib_doc_review/auditor_ib_doc_review_store', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_store')->name('auditor_ib_doc_review_store');
   Route::get('/auditor_ib_doc_review/auditor_ib_doc_review_edit/{id}', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_edit')->name('auditor_ib_doc_review_edit');
   Route::put('/auditor_ib_doc_review/auditor_ib_doc_review_update/{id}', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_update')->name('auditor_ib_doc_review_update');
   Route::post('/auditor_ib_doc_review/bypass_doc_auditor_assignment', 'Certify\IB\\AuditorIBController@bypass_doc_auditor_assignment')->name('bypass_ib_doc_auditor_assignment');
   Route::post('/auditor_ib_doc_review/cancel_doc_review_team', 'Certify\IB\\AuditorIBController@cancel_doc_review_team')->name('ib_cancel_doc_review_team');
   Route::post('/auditor_ib_doc_review/reject_doc_review', 'Certify\IB\\AuditorIBController@reject_doc_review')->name('ib_reject_doc_review');
   Route::post('/auditor_ib_doc_review/accept_doc_review', 'Certify\IB\\AuditorIBController@accept_doc_review')->name('ib_accept_doc_review');
   
   Route::get('/auditor_ib_doc_review/auditor_ib_doc_review_result_show/{id}', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_result_show')->name('auditor_ib_doc_review_result_show');
   Route::put('/auditor_ib_doc_review/auditor_ib_doc_review_result_update/{id}', 'Certify\IB\\AuditorIBController@auditor_ib_doc_review_result_update')->name('auditor_ib_doc_review_result_update');

   Route::get('/auditor_ib_doc_review/save_board_auditor_doc_review_index', 'Certify\IB\\AuditorIBController@save_board_auditor_doc_review_index')->name('save_board_auditor_doc_review_index');


    
    //แต่งตั้งคณะผู้ตรวจประเมิน (IB)
    Route::get('/auditor-ib/create/{id?}', 'Certify\IB\\AuditorIBController@create');
    Route::resource('/auditor-ib', 'Certify\IB\\AuditorIBController');
    Route::get('/auditor-ib/app_no/{id}', 'Certify\IB\\AuditorIBController@DataCertiNo');
    Route::post('/auditor-ib/update_delete/{id?}', 'Certify\IB\\AuditorIBController@update_delete');


    Route::get('/auditor-ib/create-ib-message-record/{id?}/ib', 'Certify\IB\\AuditorIBController@CreateIbMessageRecord')->name('certify.create_ib_message_record');
    Route::post('/auditor-ib/save-ib-message-record/{id?}', 'Certify\IB\\AuditorIBController@SaveIbMessageRecord')->name('save.create_ib_message_record');
    Route::get('/auditor-ib/view-ib-message-record/{id?}', 'Certify\IB\\AuditorIBController@ViewIbMessageRecord')->name('view.create_ib_message_record');


    
    Route::resource('/save_assessment-ib', 'Certify\IB\\SaveAssessmentIbController');






    Route::get('/save_assessment-ib/create/{id?}','Certify\IB\\SaveAssessmentIbController@create')->name('save_ib_assessment.create');
    Route::post('/save_assessment-ib/store/{id?}','Certify\IB\\SaveAssessmentIbController@store')->name('save_ib_assessment.store');

    Route::get('/save_assessment-ib/ib-report-create/{id}','Certify\IB\\SaveAssessmentIbController@createIbReport')->name('save_assessment.ib_report_create');
    Route::post('/save_assessment-ib/ib-report-store','Certify\IB\\SaveAssessmentIbController@storeIbReport')->name('save_assessment.ib_report_store');
    Route::get('/save_assessment-ib/ib-report-view/{id}','Certify\IB\\SaveAssessmentIbController@viewIbReport')->name('save_assessment.ib_report_view');

    Route::get('/save_assessment-ib/ib-report-two-create/{id}','Certify\IB\\SaveAssessmentIbController@createIbReportTwo')->name('save_assessment.ib_report_two_create');
    Route::post('/save_assessment-ib/ib-report-two-store','Certify\IB\\SaveAssessmentIbController@storeIbReportTwo')->name('save_assessment.ib_report_two_store');
    Route::get('/save_assessment-ib/ib-report-two-view/{id}','Certify\IB\\SaveAssessmentIbController@viewIbReportTwo')->name('save_assessment.ib_report_two_view');

    Route::get('/save_assessment-ib/certi_ib/{id?}', 'Certify\IB\\SaveAssessmentIbController@DataCertiIb');
    Route::get('/save_assessment-ib/assessment/{id?}/edit', 'Certify\IB\\SaveAssessmentIbController@DataAssessment');
    Route::post('/save_assessment-ib/update/{id?}', 'Certify\IB\\SaveAssessmentIbController@UpdateAssessment');

    Route::post('/save_assessment-ib/check-complete-report-one-sign','Certify\IB\\SaveAssessmentIbController@checkCompleteReportOneSign')->name('save_assessment.check_complete_ib_report_one_sign');
    Route::post('/save_assessment-ib/check-complete-report-two-sign','Certify\IB\\SaveAssessmentIbController@checkCompleteReportTwoSign')->name('save_assessment.check_complete_ib_report_two_sign');

    Route::post('/save_assessment-ib/add-auditor-representative','Certify\IB\\SaveAssessmentIbController@addAuditorIbRepresentative')->name('save_assessment.add_auditor_ib_representative');
    Route::post('/save_assessment-ib/delete-auditor-representative','Certify\IB\\SaveAssessmentIbController@deleteAuditorIbRepresentative')->name('delete_assessment.add_auditor_ib_representative');

    Route::post('/save_assessment-ib/email-to-cb-expert/{id?}', 'Certify\IB\\SaveAssessmentIbController@EmailToIbExpert')->name('save_assessment.email_to_ib_expert');


    Route::post('/save_assessment-ib/add-reference-document','Certify\IB\\SaveAssessmentIbController@addIbReferenceDocument')->name('delete_assessment.add_ib_reference_document');
    Route::post('/save_assessment-ib/delete-reference-document','Certify\IB\\SaveAssessmentIbController@deleteIbReferenceDocument')->name('delete_assessment.add_ib_reference_document');

    Route::post('/save_assessment-ib/request-admin-group-scope-sign','Certify\IB\\SaveAssessmentIbController@requestAdminGroupScopeSign')->name('save_assessment_ib.api.request_admin_group_scope_sign');

    Route::get('setting-team-ib', 'Certify\IB\\IbAuditorTeamController@index');
    Route::get('setting-team-ib/create', 'Certify\IB\\IbAuditorTeamController@create');
    Route::post('setting-team-ib/store', 'Certify\IB\\IbAuditorTeamController@store');
    Route::get('setting-team-ib/view/{id}', 'Certify\IB\\IbAuditorTeamController@view');
    Route::put('setting-team-ib/update/{id}', 'Certify\IB\\IbAuditorTeamController@update');
    Route::delete('setting-team-ib/delete/{id}', 'Certify\IB\\IbAuditorTeamController@delete');
    Route::put('setting-team-ib/update-state', 'Certify\IB\\IbAuditorTeamController@updateState');


    //ออกใบรับรอง (IB)
    Route::get('certificate-export-ib/delete_file_certificate/{id?}','Certify\IB\\CertificateExportIBController@delete_file_certificate');
    Route::post('certificate-export-ib/create', 'Certify\IB\\CertificateExportIBController@create');
    Route::resource('/certificate-export-ib', 'Certify\IB\\CertificateExportIBController');
    Route::group(['prefix' => 'certificate-export-ib'], function () {

        Route::get('/api/{id?}'        ,'Certify\IB\\CertificateExportIBController@apiGetAddress');
        Route::get('/api/date/{date?}'        ,'Certify\IB\\CertificateExportIBController@apiGetDate');
        // ที่อยู่
        Route::get('/api/address/{id?}/{address?}','Certify\IB\\CertificateExportIBController@GetAddress');

        // ไฟล์แนบท้าย
        Route::post('/add_attach/{id?}','Certify\IB\\CertificateExportIBController@addAttach');
        Route::post('/update_status/{id?}', 'Certify\IB\\CertificateExportIBController@update_status');
        Route::get('/sign_position/{id}', 'Certify\IB\\CertificateExportIBController@signPosition'); 
        Route::post('/update_document', 'Certify\IB\\CertificateExportIBController@update_document');
        Route::get('/delete-file/{id}', 'Certify\IB\\CertificateExportIBController@deleteAttach'); 


  
    });

    Route::group(['prefix' => 'certificate_detail-ib'], function () { 
        Route::post('/del_attach','Certify\IB\\CheckCertificateIBController@del_attach');
        Route::get('/{token?}','Certify\IB\\CheckCertificateIBController@certificate_detail');
        Route::post('/update_document','Certify\IB\\CheckCertificateIBController@update_document');
   
    }); 
    //แต่งตั้งคณะทบทวนฯ  (CB)
    Route::post('/check_certificate-ib/update_review/{id?}', 'Certify\IB\\CheckCertificateIBController@UpdateReview');

    Route::get('/auditor/status/ib_and_cb/{id?}/{type?}', 'Certify\IB\\AuditorIBController@ApiAuditorExpertise');
    
    // อ่านไฟล์ที่แนบมา
    // Route::get('check/files_ib/{filename}', function($filename)
    // {
    //    $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
    //     $filePath =  response()->download($public.'/files/applicants/check_files_ib/' . $filename);
    //    return $filePath;
    // });


    // Route::resource('/check_certificate-cb', 'Certify\CB\\CheckCertificateCBController');
    Route::post('/download-cb-template','CbPdfGeneratorController@loadCbTemplate')->name('download-cb-template');
    Route::post('/save-cb-template','CbPdfGeneratorController@saveHtml')->name('cb.save-html-template');

    Route::get('/show-cb-editor/{templateType}/{assessmentId}','CbPdfGeneratorController@showEditor')->name('cb.editor.show');


   Route::get('/cb-doc-assessment-review-html/{id}','CbPdfGeneratorController@docAssessmentReviewHtml')->name('cb.doc-assessment-review-html');
    Route::post('/save-cb-doc-assessment-review-html','CbPdfGeneratorController@saveAssessmentReviewHtml')->name('cb.save-assessment-review-html-template');
    Route::post('/download-cb-doc-assessment-review-html','CbPdfGeneratorController@downloadAssessmentReviewHtml')->name('cb.download-assessment-review-html-template');
    Route::post('/cb-default-download-doc-assessment-review-html','CbPdfGeneratorController@downloadDefaultDocAssessmentReviewHtml')->name('cb.default-download-assessment-review-html-template');

   Route::get('/cb-tangtung-tobtoun-html/{id}','CbPdfGeneratorController@tangtungTobtounReviewHtml')->name('cb.doc-tangtung-tobtoun-html');
    Route::post('/save-cb-tangtung-tobtoun-html','CbPdfGeneratorController@saveTangtungTobtounHtml')->name('cb.save-tangtung-tobtoun-template');
    Route::post('/download-cb-tangtung-tobtoun-html','CbPdfGeneratorController@downloadTangtungTobtounHtml')->name('cb.download-tangtung-tobtoun-template');
    Route::post('/cb-default-download-tangtung-tobtoun-html','CbPdfGeneratorController@downloadDefaultTangtungTobtounHtml')->name('cb.default-download-tangtung-tobtoun-template');


    Route::get('/cb-doc-result-review-html/{id}','CbPdfGeneratorController@docResultReviewHtml')->name('cb.doc-result-review-html');
    Route::post('/save-cb-doc-result-review-html','CbPdfGeneratorController@saveResultReviewHtml')->name('cb.save-doc-result-review-template');
    Route::post('/download-cb-doc-result-review-html','CbPdfGeneratorController@downloadResultReviewHtml')->name('cb.download-doc-result-review-template');
    Route::post('/cb-default-download-doc-result-review-html','CbPdfGeneratorController@downloadDefaultDocResultReviewHtml')->name('cb.default-download-doc-result-review-template');


    Route::get('/doc-review-cb-template/{id}','CbPdfGeneratorController@docReviewHtml')->name('cb.doc-review-html-template');
    Route::post('/save-doc-review-cb-template','CbPdfGeneratorController@saveDocReviewHtml')->name('cb.save-doc-review-html-template');
    Route::post('/download-doc-review-cb-template','CbPdfGeneratorController@loadCbDocReviewTemplate')->name('cb.download-doc-review-html-template');
    Route::post('/cb-default-download-doc-review-html-template','CbPdfGeneratorController@loadDefaultCbDocReviewTemplate')->name('cb.default-download-doc-review-html-template');

    
    Route::get('/summary-report-cb-template/{id}','CbPdfGeneratorController@summaryReportHtml')->name('cb.summary-report-html-template');
    Route::post('/save-summary-report-cb-template','CbPdfGeneratorController@savesummaryReportHtml')->name('cb.save-summary-report-html-template');
    Route::post('/download-summary-report-cb-template','CbPdfGeneratorController@loadCbSummaryReportHtmlTemplate')->name('cb.download-summary-report-html-template');
    Route::post('/cb-default-download-summary-report-html-template','CbPdfGeneratorController@loadDefaultSummaryReportHtmlTemplate')->name('cb.default-download-summary-report-html-template');



    // แสดงรายการข้อมูลทั้งหมด (index)
    Route::get('/check_certificate-cb', 'Certify\CB\CheckCertificateCBController@index')->name('check_certificate_cb.index');

    // แสดงฟอร์มสร้างข้อมูลใหม่ (create)
    Route::get('/check_certificate-cb/create', 'Certify\CB\CheckCertificateCBController@create')->name('check_certificate_cb.create');

    // บันทึกข้อมูลใหม่ (store)
    Route::post('/check_certificate-cb', 'Certify\CB\CheckCertificateCBController@store')->name('check_certificate_cb.store');

    // แสดงข้อมูลเดี่ยว (show)
    // Route::get('/check_certificate-cb/{token}/show/{id}', 'Certify\CB\CheckCertificateCBController@show')->name('check_certificate_cb.show');
    Route::get('/check_certificate-cb/{token}/show/{id}', 'Certify\CB\CheckCertificateCBController@show')->name('check_certificate_cb.show');

    // แสดงฟอร์มแก้ไขข้อมูล (edit)
    Route::get('/check_certificate-cb/{id}/edit', 'Certify\CB\CheckCertificateCBController@edit')->name('check_certificate_cb.edit');

    // อัปเดตข้อมูล (update)
    Route::PUT('/check_certificate-cb/{id}', 'Certify\CB\CheckCertificateCBController@update')->name('check_certificate_cb.update');

    // ลบข้อมูล (destroy)
    Route::delete('/check_certificate-cb/{id}', 'Certify\CB\CheckCertificateCBController@destroy')->name('check_certificate_cb.destroy');


    
    Route::post('/check_certificate-cb','Certify\CB\CheckCertificateCBController@assign')->name('check_certificate-cb.assign');
    Route::get('check_certificate-cb/show/{certiIb}','Certify\CB\CheckCertificateCBController@showCertificatecbDetail');
    Route::get('/check_certificate-cb/data_show/{id?}','Certify\CB\CheckCertificateCBController@DataShow');
    Route::post('/check_certificate-cb/update_delete','Certify\CB\CheckCertificateCBController@update_delete');


    // ลบไฟล์หลักฐาน 
    Route::get('check_certificate-cb/delete_file/{id?}','Certify\CB\CheckCertificateCBController@delete_file');


    // การประมาณค่าใช้จ่าย (CB)
    Route::resource('/estimated_cost-cb', 'Certify\CB\\EstimatedCostCBController');
    Route::get('estimated_cost-cb/app_no/{id?}','Certify\CB\EstimatedCostCBController@GetDataTraderOperaterName');

   //แต่งตั้งคณะผู้ตรวจประเมินเอกสาร (CB)
    Route::get('/auditor_cb_doc_review/auditor_cb_doc_review_index', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_index')->name('auditor_cb_doc_review_index');
    Route::get('/auditor_cb_doc_review/auditor_cb_doc_review/{id}', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review')->name('auditor_cb_doc_review');
    Route::post('/auditor_cb_doc_review/auditor_cb_doc_review_store', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_store')->name('auditor_cb_doc_review_store');
    Route::get('/auditor_cb_doc_review/auditor_cb_doc_review_edit/{id}', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_edit')->name('auditor_cb_doc_review_edit');
    Route::put('/auditor_cb_doc_review/auditor_cb_doc_review_update/{id}', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_update')->name('auditor_cb_doc_review_update');
    Route::post('/auditor_cb_doc_review/bypass_doc_auditor_assignment', 'Certify\CB\\AuditorCBController@bypass_doc_auditor_assignment')->name('bypass_doc_auditor_assignment');
    Route::post('/auditor_cb_doc_review/cancel_doc_review_team', 'Certify\CB\\AuditorCBController@cancel_doc_review_team')->name('cancel_doc_review_team');
    Route::post('/auditor_cb_doc_review/reject_doc_review', 'Certify\CB\\AuditorCBController@reject_doc_review')->name('reject_doc_review');
    Route::post('/auditor_cb_doc_review/accept_doc_review', 'Certify\CB\\AuditorCBController@accept_doc_review')->name('accept_doc_review');
    
    Route::get('/auditor_cb_doc_review/auditor_cb_doc_review_result_show/{id}', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_result_show')->name('auditor_cb_doc_review_result_show');
    Route::put('/auditor_cb_doc_review/auditor_cb_doc_review_result_update/{id}', 'Certify\CB\\AuditorCBController@auditor_cb_doc_review_result_update')->name('auditor_cb_doc_review_result_update');

    Route::get('/auditor_cb_doc_review/save_board_auditor_doc_review_index', 'Certify\CB\\AuditorCBController@save_board_auditor_doc_review_index')->name('save_board_auditor_doc_review_index');
    
    //แต่งตั้งคณะผู้ตรวจประเมิน (CB)

    Route::post('/auditor-cb/create', 'Certify\CB\\AuditorCBController@create');
    Route::resource('/auditor-cb', 'Certify\CB\\AuditorCBController');
    Route::get('/auditor-cb/app_no/{id}', 'Certify\CB\\AuditorCBController@DataCertiNo');
    Route::post('/auditor-cb/update_delete/{id?}', 'Certify\CB\\AuditorCBController@update_delete');


    Route::get('/auditor-cb/create-cb-message-record/{id?}/cb', 'Certify\CB\\AuditorCBController@CreateCbMessageRecord')->name('certify.create_cb_message_record');
    Route::post('/auditor-cb/save-cb-message-record/{id?}', 'Certify\CB\\AuditorCBController@SaveCbMessageRecord')->name('save.create_cb_message_record');
    Route::get('/auditor-cb/view-cb-message-record/{id?}', 'Certify\CB\\AuditorCBController@ViewCbMessageRecord')->name('view.create_cb_message_record');

    // Pay-in ครั้งที่ 1 (CB)
    Route::get('/check_certificate-cb/Pay_In1/{id?}/{token?}', 'Certify\CB\\CheckCertificateCBController@GetCBPayInOne');
    Route::post('/check_certificate-cb/pay-in/{id?}', 'Certify\CB\\CheckCertificateCBController@CertiCBPayInOne');
    Route::get('/check_certificate-cb/Print/Pay_In1/{amount?}/{start_date?}/{id?}', 'Certify\CB\\CheckCertificateCBController@GetCBPayInOnePrint');
    Route::post('/check_certificate_cb/check_pay_in_cb','Certify\CB\\CheckCertificateCBController@check_pay_in_cb');
    // บันทึกผลการตรวจประเมิน  (CB)
    Route::resource('/save_assessment-cb', 'Certify\CB\\SaveAssessmentCBController');
    Route::get('/save_assessment-cb/create/{id?}','Certify\CB\\SaveAssessmentCBController@create')->name('save_cb_assessment.create');
    Route::post('/save_assessment-cb/store/{id?}','Certify\CB\\SaveAssessmentCBController@store')->name('save_cb_assessment.store');
    Route::get('/save_assessment-cb/certi_cb/{id?}', 'Certify\CB\\SaveAssessmentCBController@DataCertiCb');
    Route::get('/save_assessment-cb/assessment/{id?}/edit', 'Certify\CB\\SaveAssessmentCBController@DataAssessment');
    Route::post('/save_assessment-cb/update/{id?}', 'Certify\CB\\SaveAssessmentCBController@UpdateAssessment');

    Route::post('/save_assessment-cb/email-to-cb-expert/{id?}', 'Certify\CB\\SaveAssessmentCBController@EmailToCbExpert')->name('save_assessment.email_to_cb_expert');

    Route::get('/save_assessment-cb/cb-report-create/{id}','Certify\CB\\SaveAssessmentCBController@createCbReport')->name('save_assessment.cb_report_create');
    Route::post('/save_assessment-cb/cb-report-store','Certify\CB\\SaveAssessmentCBController@storeCbReport')->name('save_assessment.cb_report_store');
    Route::get('/save_assessment-cb/cb-report-view/{id}','Certify\CB\\SaveAssessmentCBController@viewCbReport')->name('save_assessment.cb_report_view');

    Route::get('/save_assessment-cb/cb-report-two-create/{id}','Certify\CB\\SaveAssessmentCBController@createCbReportTwo')->name('save_assessment.cb_report_two_create');
    Route::post('/save_assessment-cb/cb-report-two-store','Certify\CB\\SaveAssessmentCBController@storeCbReportTwo')->name('save_assessment.cb_report_two_store');
    Route::get('/save_assessment-cb/cb-report-two-view/{id}','Certify\CB\\SaveAssessmentCBController@viewCbReportTwo')->name('save_assessment.cb_report_two_view');


    Route::get('/view-cb-info/{id?}','Certify\CB\\SaveAssessmentCBController@viewCbInfo')->name('save_assessment.view_cb_info');
    Route::post('/update-cb-info','Certify\CB\\SaveAssessmentCBController@updateCbInfo')->name('save_assessment.update_cb_info');



    Route::post('/save_assessment-cb/check-complete-report-one-sign','Certify\CB\\SaveAssessmentCBController@checkCompleteReportOneSign')->name('save_assessment.check_complete_cb_report_one_sign');
    Route::post('/save_assessment-cb/check-complete-report-two-sign','Certify\CB\\SaveAssessmentCBController@checkCompleteReportTwoSign')->name('save_assessment.check_complete_cb_report_two_sign');

    Route::post('/save_assessment-cb/add-auditor-representative','Certify\CB\\SaveAssessmentCBController@addAuditorRepresentative')->name('save_assessment.add_auditor_representative');
    Route::post('/save_assessment-cb/delete-auditor-representative','Certify\CB\\SaveAssessmentCBController@deleteAuditorRepresentative')->name('delete_assessment.add_auditor_representative');

    Route::post('/save_assessment-cb/add-reference-document','Certify\CB\\SaveAssessmentCBController@addReferenceDocument')->name('delete_assessment.add_reference_document');
    Route::post('/save_assessment-cb/delete-reference-document','Certify\CB\\SaveAssessmentCBController@deleteReferenceDocument')->name('delete_assessment.add_reference_document');

    Route::post('/save_assessment-cb/request-admin-group-scope-sign','Certify\CB\\SaveAssessmentCBController@requestAdminGroupScopeSign')->name('save_assessment_cb.api.request_admin_group_scope_sign');

     // สรุปรายงานและเสนออนุกรรมการฯ
    Route::post('/check_certificate-cb/save-review/{id?}', 'Certify\CB\\CheckCertificateCBController@SaveReview');
    Route::post('/check_certificate-cb/ask-to-edit-cb-scope', 'Certify\CB\\CheckCertificateCBController@askToEditCbScope')->name('check_certificate-cb.ask-to-edit-cb-scope');

    Route::post('/check_certificate-cb/report/{id?}', 'Certify\CB\\CheckCertificateCBController@UpdateReport');
    // แนบใบ Pay-in ครั้งที่ 2
    Route::get('/check_certificate-cb/Pay_In2/{id?}/{token?}', 'Certify\CB\\CheckCertificateCBController@GetCBPayInTwo');
    Route::post('/check_certificate-cb/create/pay-in2/{id?}', 'Certify\CB\\CheckCertificateCBController@CreatePayInTwo');
    Route::post('/check_certificate-cb/update/pay-in2/{id?}', 'Certify\CB\\CheckCertificateCBController@UpdatePayInTwo');
    Route::post('/check_certificate-cb/update/update_attach/{id?}', 'Certify\CB\\CheckCertificateCBController@UpdateAttacho');
    Route::get('/check_certificate-cb/Print/Pay_In2/{amount?}/{start_date?}/{id?}/{state?}', 'Certify\CB\\CheckCertificateCBController@GetCBPayInTwoPrint');

    Route::resource('certificate-export-cb', 'Certify\CB\\CertificateExportCBController');
    Route::get('/api/certificate-export-cb/{id?}'        ,'Certify\CB\\CertificateExportCBController@apiGetAddress');
    Route::get('/api/certificate-export-cb/date/{date?}'        ,'Certify\CB\\CertificateExportCBController@apiGetDate');
    // ที่อยู่
    Route::get('/api/certificate-export-cb/address/{id?}/{address?}','Certify\CB\\CertificateExportCBController@GetAddress');
    //สร้างคณะทบทวน (CB)
    Route::get('/check_certificate-cb/create_team_review/{id?}', 'Certify\CB\\CheckCertificateCBController@create_team_review');
    Route::post('/check_certificate-cb/save_team_review/{id?}', 'Certify\CB\\CheckCertificateCBController@save_team_review');
    Route::get('/check_certificate-cb/view_team_review/{id?}', 'Certify\CB\\CheckCertificateCBController@view_team_review');
    Route::get('/check_certificate-cb/update_team_review/{id?}', 'Certify\CB\\CheckCertificateCBController@update_team_review');
    //แต่งตั้งคณะทบทวนฯ  (CB)
    Route::post('/check_certificate-cb/update_review/{id?}', 'Certify\CB\\CheckCertificateCBController@UpdateReview');

    Route::post('certificate-export-cb/add_attach/{id?}','Certify\CB\\CertificateExportCBController@addAttach');
    Route::get('certificate-export-cb/delete-file/{id}', 'Certify\CB\\CertificateExportCBController@deleteAttach'); 
    Route::get('certificate-export-cb/sign_position/{id}', 'Certify\CB\\CertificateExportCBController@signPosition'); 

    Route::post('certificate-export-cb/update_status', 'Certify\CB\\CertificateExportCBController@update_status');
    Route::post('certificate-export-cb/update_document', 'Certify\CB\\CertificateExportCBController@update_document');
    Route::post('certificate-export-cb/create', 'Certify\CB\\CertificateExportCBController@create');
    Route::get('certificate-export-cb/delete_file_certificate/{id?}','Certify\CB\\CertificateExportCBController@delete_file_certificate');
    Route::get('check_certificate-cb/check_api_pid/cbs', 'Certify\CB\\CertificateExportCBController@check_api_pid');


    Route::get('setting-team-cb', 'Certify\CB\\CbAuditorTeamController@index');
    Route::get('setting-team-cb/create', 'Certify\CB\\CbAuditorTeamController@create');
    Route::post('setting-team-cb/store', 'Certify\CB\\CbAuditorTeamController@store');
    Route::get('setting-team-cb/view/{id}', 'Certify\CB\\CbAuditorTeamController@view');
    Route::put('setting-team-cb/update/{id}', 'Certify\CB\\CbAuditorTeamController@update');
    Route::delete('setting-team-cb/delete/{id}', 'Certify\CB\\CbAuditorTeamController@delete');
    Route::put('setting-team-cb/update-state', 'Certify\CB\\CbAuditorTeamController@updateState');

    Route::group(['prefix' => 'certificate_detail-cb'], function () { 
        Route::post('/del_attach','Certify\CB\\CheckCertificateCBController@del_attach');
        Route::get('/{token?}','Certify\CB\\CheckCertificateCBController@certificate_detail');
        Route::post('/update_document','Certify\CB\\CheckCertificateCBController@update_document');
   
    });
    // อ่านไฟล์ที่แนบมา
    //  Route::get('check/files_cb/{filename}', function($filename)
    // {
    //  $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
    //   $filePath =  response()->download($public.'/files/applicants/check_files_cb/' . $filename);
    //  return $filePath;
    // });



    // ออกใบรับรอง LAB
    Route::resource('certificate-export-lab', 'Certify\\CertificateExportLABController');
    Route::get('/api/certificate-export-lab/{id?}'        ,'Certify\\CertificateExportLABController@apiGetAddress');
    Route::get('/api/certificate-export-lab/date/{date?}'        ,'Certify\\CertificateExportLABController@apiGetDate');
    // ที่อยู่
    Route::get('/api/certificate-export-lab/address/{id?}/{address?}','Certify\\CertificateExportLABController@GetAddress');

    Route::post('certificate-export-lab/add_attach/{id?}','Certify\\CertificateExportLABController@addAttach');
    Route::post('certificate-export-lab/update_status/{id?}', 'Certify\\CertificateExportLABController@update_status');
    Route::get('certificate-export-lab/sign_position/{id}', 'Certify\\CertificateExportLABController@signPosition'); 

    Route::get('certificate-export-lab/delete_file/{id?}','Certify\\CertificateExportLABController@delete_file');
    Route::get('certificate-export-lab/delete_file_certificate/{id?}','Certify\\CertificateExportLABController@delete_file_certificate');
    Route::post('certificate-export-lab/update_document', 'Certify\\CertificateExportLABController@update_document');
    Route::post('certificate-export-lab/create', 'Certify\\CertificateExportLABController@create');
 
    // ตั้งค่าสาขาตามมาตรฐาน
    Route::resource('formulas', 'Certify\\FormulasController');
    // การประมาณค่าใช้จ่าย
    Route::resource('Cost-Estimation', 'Certify\\CostEstimationController');
    // emsil ลท ใบรับรอง LAB ,CB ,IB
    Route::resource('authorities-lt', 'Certify\\AuthoritiesLtController');

    // ระบบนำส่งใบรับรองระบบงาน 
    Route::post('send-certificates/delete', 'Certify\\SendCertificatesController@delete');
    Route::get('send-certificates/view-pdf/{id?}/{cer?}/{type?}', 'Certify\\SendCertificatesController@view_pdf');
    Route::get('send-certificates/getsign', 'Certify\\SendCertificatesController@getsign');
    Route::get('send-certificates/getsign_position', 'Certify\\SendCertificatesController@getsign_position');
    Route::get('send-certificates/data_list', 'Certify\\SendCertificatesController@data_list');
    Route::resource('send-certificates', 'Certify\\SendCertificatesController');

    Route::get('auditor-assignment/', 'Certify\\AuditorAssignmentController@index');
    Route::get('auditor-assignment/data-list', 'Certify\\AuditorAssignmentController@dataList');
    Route::get('auditor-assignment/get-signer', 'Certify\\AuditorAssignmentController@getSigner')->name('auditor_assignment.get_signer');
    Route::post('auditor-assignment/sign-document', 'Certify\\AuditorAssignmentController@signDocument')->name('auditor_assignment.signDocument');


    

    // ระบบลงนามใบรับรองระบบงาน 
    Route::get('sign-certificates/save_new_cer', 'Certify\\SignCertificatesController@save_new_cer');
    Route::get('sign-certificates/save_cer/{cer?}/{id?}/{type?}', 'Certify\\SignCertificatesController@save_cer');
    Route::get('sign-certificates/check_update_sign', 'Certify\\SignCertificatesController@check_update_sign');
    Route::POST('sign-certificates/save', 'Certify\\SignCertificatesController@save');
    Route::POST('sign-certificates/save_sign', 'Certify\\SignCertificatesController@save_sign');
    Route::POST('sign-certificates/save_cancel', 'Certify\\SignCertificatesController@save_cancel');
    Route::get('sign-certificates/getCheckOtp', 'Certify\\SignCertificatesController@getCheckOtp');
    Route::get('sign-certificates/getOtpTimeOut', 'Certify\\SignCertificatesController@getOtpTimeOut');
    Route::get('sign-certificates/getOtp', 'Certify\\SignCertificatesController@getOtp');
    Route::get('sign-certificates/getsign', 'Certify\\SignCertificatesController@getsign');
    Route::get('sign-certificates/data_list', 'Certify\\SignCertificatesController@data_list');
    Route::resource('sign-certificates', 'Certify\\SignCertificatesController');

    Route::get('assessment-report-assignment/', 'Certify\\SignAssessmentReportController@index');
    Route::get('assessment-report-assignment/data-list', 'Certify\\SignAssessmentReportController@dataList')->name('assessment_report_assignment.dataList');
    Route::get('assessment-report-assignment/get-signer', 'Certify\\SignAssessmentReportController@getSigner')->name('assessment_report_assignment.get_signer');
    Route::post('assessment-report-assignment/sign-document', 'Certify\\SignAssessmentReportController@signDocument')->name('assessment_report_assignment.signDocument');
    Route::post('assessment-report-assignment/api/get-signers', 'Certify\\SignAssessmentReportController@apiGetSigners')->name('assessment_report_assignment.api.get_signers');
    Route::get('assessment-report-assignment/get-signers', 'Certify\\SignAssessmentReportController@apiGetSigners')->name('assessment_report_assignment.get_signers');

    Route::get('lab-scope-review/', 'Certify\\LabScopeReviewController@index');
    Route::get('lab-scope-review/data-list', 'Certify\\LabScopeReviewController@dataList')->name('lab_scope_review.dataList');
    Route::get('lab-scope-review/get-signer', 'Certify\\LabScopeReviewController@getSigner')->name('lab_scope_review.get_signer');
    Route::post('lab-scope-review/sign-document', 'Certify\\LabScopeReviewController@signDocument')->name('lab_scope_review.signDocument');
    Route::post('lab-scope-review/api/get-signers', 'Certify\\LabScopeReviewController@apiGetSigners')->name('lab_scope_review.api.get_signers');


    Route::get('cb-scope-review/', 'Certify\\CbScopeReviewController@index');
    Route::get('cb-scope-review/data-list', 'Certify\\CbScopeReviewController@dataList')->name('cb_scope_review.dataList');
    Route::get('cb-scope-review/get-signer', 'Certify\\CbScopeReviewController@getSigner')->name('cb_scope_review.get_signer');
    Route::post('cb-scope-review/sign-document', 'Certify\\CbScopeReviewController@signDocument')->name('cb_scope_review.signDocument');
    Route::post('cb-scope-review/api/get-signers', 'Certify\\CbScopeReviewController@apiGetSigners')->name('cb_scope_review.api.get_signers');


    //ระบบจัดทำมาตรฐานรับรอง
    Route::post('standards/save_standards', 'Certify\StandardController@save_standards');
    Route::post('standards/publish_state', 'Certify\\StandardController@publish_state');
    Route::put('standards/update-state', 'Certify\\StandardController@update_state');
    Route::get('standards/data_list', 'Certify\\StandardController@data_list');
    Route::get('standards/cover_pdf', 'Certify\\StandardController@cover_pdf');
    Route::get('standards/load_data_isbn/{id?}', 'Certify\StandardController@load_data_isbn');
    Route::post('standards/update_isbn', 'Certify\StandardController@update_isbn');
    Route::get('standards/sign_position/{id}', 'Certify\StandardController@signPosition'); 
    Route::get('standards/load-isbn-req-info/{id}', 'Certify\StandardController@loadIsbnReqInfo'); 
    Route::resource('standards', 'Certify\\StandardController');
    
});   


    Route::get('certify/internalrequest', 'Certify\\InternalRequestController@index'); 
    Route::get('certify/internalrequest/create', 'Certify\\InternalRequestController@create')->name('certify.internalrequest.create'); 
    Route::post('certify/internalrequest/store', 'Certify\\InternalRequestController@store')->name('certify.internalrequest.store'); 
    Route::get('certify/internalrequest/edit/{id}', 'Certify\\InternalRequestController@edit')->name('certify.internalrequest.edit'); 
    Route::get('certify/internalrequest/show/{id}', 'Certify\\InternalRequestController@view')->name('certify.internalrequest.show');
    Route::put('certify/internalrequest/update/{id}', 'Certify\\InternalRequestController@update')->name('certify.internalrequest.update');


    Route::get('certify/dashboard', 'Certify\\DashboardController@index'); 
    Route::get('certify/dashboard/draw_app', 'Certify\\DashboardController@draw_app');
    Route::get('certify/dashboard/chart_cer', 'Certify\\DashboardController@chart_cer');


    Route::put('certify/tisusercertify/update-state', 'Certify\SetStandardUserController@update_state');
    Route::resource('certify/set-standard-user', 'Certify\\SetStandardUserController');
    Route::get('certify/set-standard-user/department/{department_id?}', 'Certify\\SetStandardUserController@DataSubDepartment');
    Route::get('certify/set-standard-user/sub_department/{id?}', 'Certify\\SetStandardUserController@DataDepartment');
    
    // ร่างแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง
    Route::get('certify/standard-drafts/get_bcertify_reason', 'Certify\\StandardDraftsController@get_bcertify_reason');
    Route::get('certify/standard-drafts/data_list', 'Certify\\StandardDraftsController@data_list');
    Route::get('certify/standard-drafts/destroy/{id?}', 'Certify\\StandardDraftsController@destroy');
    Route::post('certify/standard-drafts/update_status', 'Certify\\StandardDraftsController@update_status');
    Route::POST('certify/standard-drafts/update_publish', 'Certify\\StandardDraftsController@update_publish');
    Route::POST('certify/standard-drafts/update_assign', 'Certify\\StandardDraftsController@update_assign');
    Route::POST('certify/standard-drafts/delete', 'Certify\\StandardDraftsController@delete');
    Route::put('certify/standard-drafts/update-state', 'Certify\StandardDraftsController@update_state');
    Route::resource('certify/standard-drafts', 'Certify\\StandardDraftsController');

    Route::POST('certify/standard-drafts/get_examminer', 'Certify\\StandardDraftsController@getExamine')->name('standard-drafts.get_examminer');
    
    // จัดทำแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง
    Route::get('certify/standard-plans/set_date_end', 'Certify\\StandardPlansController@set_date_end');
    Route::get('certify/standard-plans/data_list', 'Certify\\StandardPlansController@data_list');
    Route::get('certify/standard-plans/data_log_list', 'Certify\\StandardPlansController@data_log_list');
    Route::resource('certify/standard-plans', 'Certify\\StandardPlansController');

    // พิจารณาแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง /{pid?}/{appno?}
    Route::get('certify/standard-confirmplans/data_list', 'Certify\\StandardConfirmplansController@data_list');
    Route::resource('certify/standard-confirmplans', 'Certify\\StandardConfirmplansController');


    Route::get('certify/set-standards/data_list', 'Certify\\SetStandardsController@data_list');
    // Route::get('certify/set-standards/search_data_list', 'Certify\\SetStandardsController@search_data_list');
    Route::get('certify/set-standards/search_data_list', 'Certify\\SetStandardsController@search_data_list')->name('set-standards.search_data_list');
    Route::get('/certify/set-standards/get-estandard-plan/{plan_id?}', 'Certify\\SetStandardsController@GetEstandardPlan');

    Route::get('certify/set-standards/{id?}/edit_sub_appointment', 'Certify\\SetStandardsController@editSubApointment');
    // Route::put('certify/set-standards/{id?}/update_sub_appointment', 'Certify\\SetStandardsController@updateSubApointment');
    Route::patch('certify/set-standards/{id?}/update_sub_appointment', 'Certify\\SetStandardsController@updateSubApointment');


    Route::resource('certify/set-standards', 'Certify\\SetStandardsController');


    Route::POST('certify/standard-drafts/end-standard', 'Certify\\SetStandardsController@endStandard');
    Route::POST('certify/standard-drafts/renew-standard', 'Certify\\SetStandardsController@reNewStandard');
     Route::POST('certify/standard-drafts/accept-standard', 'Certify\\SetStandardsController@acceptStandard');
     Route::POST('certify/standard-drafts/get-agreement', 'Certify\\SetStandardsController@getAgreement');
     Route::POST('certify/standard-drafts/update-standard-circular-doc', 'Certify\\SetStandardsController@updateStandardCircularDoc');

    // นัดหมายการประชุม
    Route::get('certify/meeting-standards/get_committee_lists', 'Certify\\MeetingStandardsController@get_committee_lists');
    Route::post('certify/meeting-standards/update_conclusion/{id?}', 'Certify\\MeetingStandardsController@update_conclusion');
    Route::get('certify/meeting-standards/conclusion/{id?}', 'Certify\\MeetingStandardsController@conclusion');
    Route::get('certify/meeting-standards/data_list', 'Certify\\MeetingStandardsController@data_list');
    Route::resource('certify/meeting-standards', 'Certify\\MeetingStandardsController');
    Route::get('certify/meeting-standards/create/{id?}', 'Certify\\MeetingStandardsController@create')->name('certify.meeting-standards.create');






    Route::get('certify/appointed-committee-lt', 'Certify\\AppointedCommitteeLtController@index')->name('certify.meeting-standards.lt.index');
    Route::get('certify/appointed-committee-lt/create', 'Certify\\AppointedCommitteeLtController@create')->name('certify.meeting-standards.lt.create');
    Route::post('certify/appointed-committee-lt/store', 'Certify\\AppointedCommitteeLtController@store')->name('certify.meeting-standards.lt.store');

    Route::get('certify/appointed-committee-lt/show/{id}', 'Certify\\AppointedCommitteeLtController@show')->name('certify.meeting-standards.lt.show');
    Route::post('certify/appointed-committee-lt/update/{id}', 'Certify\\AppointedCommitteeLtController@update')->name('certify.meeting-standards.lt.update');

    Route::get('certify/appointed-committee-lt/plan-list', 'Certify\\AppointedCommitteeLtController@getPlanList')->name('certify.meeting-standards.lt.plan-list');
    Route::get('certify/appointed-committee-lt/std-list', 'Certify\\AppointedCommitteeLtController@getStdList')->name('certify.meeting-standards.lt.std-list');


     Route::get('certify/appointed-committee-lt/conclusion/{id?}', 'Certify\\AppointedCommitteeLtController@conclusion')->name('certify.meeting-standards.lt.conclusion');


    // conclusion-std.blade
    
    Route::get('certify/report/standard-status/data_list', 'Certify\\ReportStandardStatusController@data_list');
    Route::resource('certify/report/standard-status', 'Certify\\ReportStandardStatusController');


    Route::post('certify/isbn/upload', 'Estandard\isbn\EstandardIsbnController@uploadData')->name('isbn.upload-data');
    Route::get('certify/isbn/status', 'Estandard\isbn\EstandardIsbnController@checkStatus')->name('isbn.status');
    
    // ประกาศราชกิจจานุเบกษา
    Route::get('certify/gazette/data_list', 'Certify\\GazetteController@data_list');
    Route::get('certify/gazette/destroy/{id?}', 'Certify\\GazetteController@destroy');
    Route::post('certify/gazette/update_status', 'Certify\\GazetteController@update_status');
    Route::POST('certify/gazette/update_publish', 'Certify\\GazetteController@update_publish');
    Route::POST('certify/gazette/update_assign', 'Certify\\GazetteController@update_assign');
    Route::POST('certify/gazette/delete', 'Certify\\GazetteController@delete');
    Route::put('certify/gazette/update-state', 'Certify\GazetteController@update_state');
    Route::get('certify/gazette/get_json_by_standard/{std_type_id?}', 'Certify\\GazetteController@get_json_by_standard');
    Route::resource('certify/gazette', 'Certify\\GazetteController');


    Route::get('certify/appointed-lt-committee', 'Certify\\AppointedLtCommitteeController@index')->name('certify.appointed-lt-committee');
    Route::get('certify/appointed-lt-committee/create', 'Certify\\AppointedLtCommitteeController@create')->name('certify.appointed-lt-committee.create');
    Route::post('certify/appointed-lt-committee/store', 'Certify\\AppointedLtCommitteeController@store')->name('certify.appointed-lt-committee.store');
    Route::get('certify/appointed-lt-committee/{id?}/edit', 'Certify\\AppointedLtCommitteeController@edit')->name('certify.appointed-lt-committee.edit');
    Route::put('certify/appointed-lt-committee/update/{id?}', 'Certify\\AppointedLtCommitteeController@update')->name('certify.appointed-lt-committee.update');
    Route::get('certify/appointed-lt-committee/{id}/view', 'Certify\\AppointedLtCommitteeController@view')->name('certify.appointed-lt-committee.view');

    Route::get('certify/appointed-lt-committee/get-request-list', 'Certify\\AppointedLtCommitteeController@getRequestList')->name('certify.appointed-lt-committee.get_request_list');


    Route::get('certify/set-standard-lt', 'Certify\\SetStandardLtController@index')->name('certify.set-standard-lt');
    
    // http://127.0.0.1:8081/certify/appointed-academic-sub-committee

    Route::get('certify/appointed-academic-sub-committee', 'Certify\\AppointedAcademicSubCommitteeController@index')->name('certify.appointed-academic-sub-committee');
    Route::get('certify/appointed-academic-sub-committee/create/{id?}', 'Certify\\AppointedAcademicSubCommitteeController@create')->name('certify.appointed-academic-sub-committee.create');
    Route::post('certify/appointed-academic-sub-committee/store', 'Certify\\AppointedAcademicSubCommitteeController@store')->name('certify.appointed-academic-sub-committee.store');
    Route::get('certify/appointed-academic-sub-committee/{id?}/edit', 'Certify\\AppointedAcademicSubCommitteeController@edit')->name('certify.appointed-academic-sub-committee.edit');
    Route::put('certify/appointed-academic-sub-committee/update/{id?}', 'Certify\\AppointedAcademicSubCommitteeController@update')->name('certify.appointed-academic-sub-committee.update');
    Route::get('certify/appointed-academic-sub-committee/{id}/view', 'Certify\\AppointedAcademicSubCommitteeController@view')->name('certify.appointed-academic-sub-committee.view');
    
    // http://127.0.0.1:8081/certify/appointed-committee

    Route::get('certify/appointed-committee', 'Certify\\AppointedCommitteeController@index')->name('certify.appointed-committee');
    Route::post('certify/appointed-committee/sign-document', 'Certify\\AppointedCommitteeController@signDocument')->name('certify.appointed-committee.sign-document');

    
    Route::get('certify/appointed-committee-sign-lt', 'Certify\\AppointedLtSignCommitteeController@index')->name('certify.appointed-lt-sign-committee');
    Route::post('certify/appointed-committee-sign-lt/sign-document', 'Certify\\AppointedLtSignCommitteeController@signDocument')->name('certify.appointed-lt-sign-committee.sign-document');


   });
