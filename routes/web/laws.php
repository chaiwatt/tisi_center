<?php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Carbon\Carbon;

    use App\Models\Law\File\AttachFileLaw;
    use App\Models\Law\Cases\LawCasesForm;
    use App\Models\Law\Cases\LawCasesDelivery;

    Route::get('law/dashboard', 'Laws\DashboardLawsController@index')->middleware('auth');
    Route::get('law/update/download-file', 'FuntionCenter\LawController@SaveVisitDonwload');
    Route::get('law/funtion/get-book-type', 'FuntionCenter\LawController@LawBookType');
    Route::get('law/funtion/get-sub-departments', 'FuntionCenter\LawController@SubDepartments');
    Route::get('law/funtion/get-user-departments', 'FuntionCenter\LawController@UserDepartments');
    Route::get('law/funtion/get-other-departments', 'FuntionCenter\LawController@LawDepartmentOther');
    Route::get('law/funtion/get-users-data/{reg_subdepart?}', 'FuntionCenter\LawController@LawUserData');
    Route::get('law/funtion/search-standards-td3', 'FuntionCenter\LawController@SearchStandardsTb3');
    Route::get('law/funtion/search-license-tb4', 'FuntionCenter\LawController@SearchLicenseTb4');
    Route::POST('law/funtion/upload-file-temp', 'FuntionCenter\LawController@UploadFileTemp');

    Route::POST('law/funtion/datatype','FuntionCenter\LawController@datatype');
    Route::POST('law/funtion/check_tax_number','FuntionCenter\LawController@check_tax_number');
    Route::POST('law/funtion/get_tax_number','FuntionCenter\LawController@get_tax_number');
    Route::POST('law/funtion/get_legal_entity','FuntionCenter\LawController@get_legal_entity');
    Route::POST('law/funtion/get_legal_faculty','FuntionCenter\LawController@get_legal_faculty');
    Route::POST('law/funtion/get_taxid','FuntionCenter\LawController@get_taxid');


    Route::get('law/funtion/get-time-now', function (){
        return "ข้อมูล ณ วันที่ ".HP::formatDateThaiFull(date('Y-m-d'))."  เวลา ".(Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))."   น.";
    });

    //Route ข้อมูลพื้นฐาน
    Route::prefix('/law/basic')->group(function () {

        Route::get('/department/{id}/destroy', 'Laws\Basic\LawDepartmentController@destroy');
        Route::get('/department/data_list', 'Laws\Basic\LawDepartmentController@data_list');
        Route::POST('/department/delete', 'Laws\Basic\LawDepartmentController@delete');
        Route::put('/department/update-state', 'Laws\Basic\LawDepartmentController@update_state');
        Route::resource('/department', 'Laws\Basic\LawDepartmentController');

        Route::get('/department-stakeholder/{id}/destroy', 'Laws\Basic\LawDepartmentStakeholderController@destroy');
        Route::get('/department-stakeholder/data_list', 'Laws\Basic\LawDepartmentStakeholderController@data_list');
        Route::POST('/department-stakeholder/delete', 'Laws\Basic\LawDepartmentStakeholderController@delete');
        Route::put('/department-stakeholder/update-state', 'Laws\Basic\LawDepartmentStakeholderController@update_state');
        Route::resource('/department-stakeholder', 'Laws\Basic\LawDepartmentStakeholderController');

        Route::get('/section/{id}/destroy', 'Laws\Basic\LawSectionController@destroy');
        Route::get('/section/data_list', 'Laws\Basic\LawSectionController@data_list');
        Route::POST('/section/delete', 'Laws\Basic\LawSectionController@delete');
        Route::put('/section/update-state', 'Laws\Basic\LawSectionController@update_state');
        Route::resource('/section', 'Laws\Basic\LawSectionController');

        Route::get('/process-product/{id}/destroy', 'Laws\Basic\LawProcessProductController@destroy');
        Route::get('/process-product/data_list', 'Laws\Basic\LawProcessProductController@data_list');
        Route::POST('/process-product/delete', 'Laws\Basic\LawProcessProductController@delete');
        Route::put('/process-product/update-state', 'Laws\Basic\LawProcessProductController@update_state');
        Route::resource('/process-product', 'Laws\Basic\LawProcessProductController');

        Route::get('/resource/{id}/destroy', 'Laws\Basic\LawResourceController@destroy');
        Route::get('/resource/data_list', 'Laws\Basic\LawResourceController@data_list');
        Route::POST('/resource/delete', 'Laws\Basic\LawResourceController@delete');
        Route::put('/resource/update-state', 'Laws\Basic\LawResourceController@update_state');
        Route::resource('/resource', 'Laws\Basic\LawResourceController');

        // Route::get('/division-type/{id}/destroy', 'Laws\Basic\LawDivisionTypeController@destroy');
        // Route::get('/division-type/data_list', 'Laws\Basic\LawDivisionTypeController@data_list');
        // Route::POST('/division-type/delete', 'Laws\Basic\LawDivisionTypeController@delete');
        // Route::put('/division-type/update-state', 'Laws\Basic\LawDivisionTypeController@update_state');
        // Route::resource('/division-type', 'Laws\Basic\LawDivisionTypeController');

        Route::get('/reward-group/{id}/destroy', 'Laws\Basic\LawRewardGroupController@destroy');
        Route::get('/reward-group/data_list', 'Laws\Basic\LawRewardGroupController@data_list');
        Route::POST('/reward-group/delete', 'Laws\Basic\LawRewardGroupController@delete');
        Route::put('/reward-group/update-state', 'Laws\Basic\LawRewardGroupController@update_state');
        Route::resource('/reward-group', 'Laws\Basic\LawRewardGroupController');

        Route::get('/type-file/{id}/destroy', 'Laws\Basic\LawTypeFileController@destroy');
        Route::get('/type-file/data_list', 'Laws\Basic\LawTypeFileController@data_list');
        Route::POST('/type-file/delete', 'Laws\Basic\LawTypeFileController@delete');
        Route::put('/type-file/update-state', 'Laws\Basic\LawTypeFileController@update_state');
        Route::resource('/type-file', 'Laws\Basic\LawTypeFileController');

        Route::POST('/book-group/save_and_copy', 'Laws\Basic\LawBookGroupController@save_and_copy');
        Route::get('/book-group/{id}/destroy', 'Laws\Basic\LawBookGroupController@destroy');
        Route::get('/book-group/data_list', 'Laws\Basic\LawBookGroupController@data_list');
        Route::POST('/book-group/delete', 'Laws\Basic\LawBookGroupController@delete');
        Route::put('/book-group/update-state', 'Laws\Basic\LawBookGroupController@update_state');
        Route::resource('/book-group', 'Laws\Basic\LawBookGroupController');

        Route::POST('/book-type/save_and_copy', 'Laws\Basic\LawBookTypeController@save_and_copy');
        Route::get('/book-type/{id}/destroy', 'Laws\Basic\LawBookTypeController@destroy');
        Route::get('/book-type/data_list', 'Laws\Basic\LawBookTypeController@data_list');
        Route::POST('/book-type/delete', 'Laws\Basic\LawBookTypeController@delete');
        Route::put('/book-type/update-state', 'Laws\Basic\LawBookTypeController@update_state');
        Route::resource('/book-type', 'Laws\Basic\LawBookTypeController');

        Route::POST('/listen-type/save_and_copy', 'Laws\Basic\LawListenTypeController@save_and_copy');
        Route::get('/listen-type/{id}/destroy', 'Laws\Basic\LawListenTypeController@destroy');
        Route::get('/listen-type/data_list', 'Laws\Basic\LawListenTypeController@data_list');
        Route::POST('/listen-type/delete', 'Laws\Basic\LawListenTypeController@delete');
        Route::put('/listen-type/update-state', 'Laws\Basic\LawListenTypeController@update_state');
        Route::resource('/listen-type', 'Laws\Basic\LawListenTypeController');

        Route::get('/offend-type/{id}/destroy', 'Laws\Basic\LawOffendTypeController@destroy');
        Route::get('/offend-type/data_list', 'Laws\Basic\LawOffendTypeController@data_list');
        Route::POST('/offend-type/delete', 'Laws\Basic\LawOffendTypeController@delete');
        Route::put('/offend-type/update-state', 'Laws\Basic\LawOffendTypeController@update_state');
        Route::resource('/offend-type', 'Laws\Basic\LawOffendTypeController');

        //ประเภทงาน
        Route::POST('/job-type/save_and_copy', 'Laws\Basic\LawJobTypeController@save_and_copy');
        Route::get('/job-type/{id}/destroy', 'Laws\Basic\LawJobTypeController@destroy');
        Route::get('/job-type/data_list', 'Laws\Basic\LawJobTypeController@data_list');
        Route::POST('/job-type/delete', 'Laws\Basic\LawJobTypeController@delete');
        Route::put('/job-type/update-state', 'Laws\Basic\LawJobTypeController@update_state');
        Route::resource('/job-type', 'Laws\Basic\LawJobTypeController');

        //หมวดหมู่การดำเนินงาน
        Route::POST('/category-operation/save_and_copy', 'Laws\Basic\LawCategoryOperationController@save_and_copy');
        Route::get('/category-operation/{id}/destroy', 'Laws\Basic\LawCategoryOperationController@destroy');
        Route::get('/category-operation/data_list', 'Laws\Basic\LawCategoryOperationController@data_list');
        Route::POST('/category-operation/delete', 'Laws\Basic\LawCategoryOperationController@delete');
        Route::put('/category-operation/update-state', 'Laws\Basic\LawCategoryOperationController@update_state');
        Route::resource('/category-operation', 'Laws\Basic\LawCategoryOperationController');

        //สถานะการดำเนินงาน
        Route::POST('/status-operation/save_and_copy', 'Laws\Basic\LawStatusOperationController@save_and_copy');
        Route::get('/status-operation/{id}/destroy', 'Laws\Basic\LawStatusOperationController@destroy');
        Route::get('/status-operation/data_list', 'Laws\Basic\LawStatusOperationController@data_list');
        Route::POST('/status-operation/delete', 'Laws\Basic\LawStatusOperationController@delete');
        Route::put('/status-operation/update-state', 'Laws\Basic\LawStatusOperationController@update_state');
        Route::resource('/status-operation', 'Laws\Basic\LawStatusOperationController');

        //ประเภทการจัดส่ง
        Route::POST('/delivery/save_and_copy', 'Laws\Basic\LawDeliveryController@save_and_copy');
        Route::get('/delivery/{id}/destroy', 'Laws\Basic\LawDeliveryController@destroy');
        Route::get('/delivery/data_list', 'Laws\Basic\LawDeliveryController@data_list');
        Route::POST('/delivery/delete', 'Laws\Basic\LawDeliveryController@delete');
        Route::put('/delivery/update-state', 'Laws\Basic\LawDeliveryController@update_state');
        Route::resource('/delivery', 'Laws\Basic\LawDeliveryController');

    });
    

    //ลบไฟล์แนบ
    Route::get('law/delete-files/{id?}/{url_send?}', function( $id, $url_send){
        $attach =  AttachFileLaw::findOrFail($id);
        if( !empty($attach) && !empty($attach->url) ){    
            if( HP::checkFileStorage( '/'.$attach->url) ){
                Storage::delete( '/'.$attach->url );
                $attach->delete();
            }
            return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');     
        }   
    });

    //ลบไฟล์แนบ
    Route::get('law/attach/delete', function( Request $request ){

        $id  = $request->get('id');
        $path = $request->get('path');

        $msg = 'error';
        if( !empty($id) ){
            $attach =  AttachFileLaw::findOrFail($id);
            if( !empty($attach) && !empty($attach->url) ){    
                if( HP::checkFileStorage( '/'.$attach->url) ){
                    Storage::delete( '/'.$attach->url );
                    $attach->delete();
                    $msg = 'success';
                }  
            } 
        }else{
            if( HP::checkFileStorage( '/'.$path) ){
                Storage::delete( '/'.$path );
                $msg = 'success';
            }  
        }

        return response()->json($msg);
    });

    //Route หมวดห้องสมุด
    Route::prefix('/law/book')->group(function () {

        //สืบค้นข้อมูลห้องสมุด
        Route::get('/search/download/{id}', 'Laws\Books\LawBookSearchController@download');
        Route::get('/search/get-book-data', 'Laws\Books\LawBookSearchController@Details');
        Route::get('/search/data_list', 'Laws\Books\LawBookSearchController@data_list');
        Route::resource('/search', 'Laws\Books\LawBookSearchController');

        //จัดการข้อมูลห้องสมุด
        Route::get('/manage/{id}/destroy', 'Laws\Books\LawBookManageController@destroy');
        Route::get('/manage/data_list', 'Laws\Books\LawBookManageController@data_list');
        Route::POST('/manage/delete', 'Laws\Books\LawBookManageController@delete');
        Route::put('/manage/update-state', 'Laws\Books\LawBookManageController@update_state');
        Route::get('/manage/basic_section/{id}', 'Laws\Books\LawBookManageController@basic_section'); 
        Route::POST('/manage/save_file', 'Laws\Books\LawBookManageController@save_file');
        Route::resource('/manage', 'Laws\Books\LawBookManageController');

    });

    
    //Route สืบค้นข้อมูล
    Route::prefix('/law/search')->group(function () {

        //สืบค้นข้อมูลใบอนุญาต
        Route::get('/license-report/history', 'Laws\Search\LicenseReportController@history');
        Route::get('/license-report/data_list', 'Laws\Search\LicenseReportController@data_list');
        Route::resource('/license-report', 'Laws\Search\LicenseReportController');

    });

    //Route ติดตามสถานะงาน 
    Route::prefix('/law/track')->group(function () {

        //แจ้งงานเข้ากองกฎหมาย
        Route::POST('/receive/save_assign', 'Laws\Track\LawTrackReceiveController@save_assign');
        Route::get('/receive/{id}/destroy', 'Laws\Track\LawTrackReceiveController@destroy');
        Route::get('/receive/data_list', 'Laws\Track\LawTrackReceiveController@data_list');
        Route::POST('/receive/delete', 'Laws\Track\LawTrackReceiveController@delete');
        Route::POST('/receive/save-cancel', 'Laws\Track\LawTrackReceiveController@save_cancel');
        Route::put('/receive/update-state', 'Laws\Track\LawTrackReceiveController@update_state');
        Route::resource('/receive', 'Laws\Track\LawTrackReceiveController');

        //บันทึกผลดำเนินการ
        Route::get('/operation/{id}/destroy', 'Laws\Track\LawTrackOperationController@destroy');
        Route::get('/operation/data_list', 'Laws\Track\LawTrackOperationController@data_list');
        Route::POST('/operation/delete', 'Laws\Track\LawTrackOperationController@delete');
        Route::put('/operation/update-state', 'Laws\Track\LawTrackOperationController@update_state');
        Route::resource('/operation', 'Laws\Track\LawTrackOperationController');

        //ติดตามงาน
        Route::get('/views/{id}/destroy', 'Laws\Track\LawTrackViewsController@destroy');
        Route::get('/views/data_list', 'Laws\Track\LawTrackViewsController@data_list');
        Route::POST('/views/delete', 'Laws\Track\LawTrackViewsController@delete');
        Route::put('/views/update-state', 'Laws\Track\LawTrackViewsController@update_state');
        Route::resource('/views', 'Laws\Track\LawTrackViewsController');
    });

    //Route ร่างกฏกระทรวง
    Route::prefix('/law/listen')->group(function () {

        //จัดทำแบบรับฟังความเห็น
        Route::get('/ministry/{id}/destroy', 'Laws\Listen\LawListenMinistryController@destroy');
        Route::get('/ministry/data_list', 'Laws\Listen\LawListenMinistryController@data_list');
        Route::get('/ministry/data_department_takeholder', 'Laws\Listen\LawListenMinistryController@data_department_takeholder');
        Route::get('/ministry/data_tb4_tisilicense', 'Laws\Listen\LawListenMinistryController@data_tb4_tisilicense');
        Route::POST('/ministry/delete', 'Laws\Listen\LawListenMinistryController@delete');
        Route::put('/ministry/update-state', 'Laws\Listen\LawListenMinistryController@update_state');
        Route::get('/ministry/export-word/{id}', 'Laws\Listen\LawListenMinistryController@export_word');
        Route::get('/ministry/sign_position/{id}', 'Laws\Listen\LawListenMinistryController@signPosition'); 
        Route::resource('/ministry', 'Laws\Listen\LawListenMinistryController');

        //ตรวจสอบความเห็น
        Route::get('/ministry-response/{id}/destroy', 'Laws\Listen\LawListenMinistryResponseController@destroy');
        Route::get('/ministry-response/data_list', 'Laws\Listen\LawListenMinistryResponseController@data_list');
        Route::POST('/ministry-response/delete', 'Laws\Listen\LawListenMinistryResponseController@delete');
        Route::put('/ministry-response/update-state', 'Laws\Listen\LawListenMinistryResponseController@update_state');
        Route::get('/ministry-response/data_ministry/{id}', 'Laws\Listen\\LawListenMinistryResponseController@data_ministry'); 
        Route::get('/ministry-response/export_excel', 'Laws\Listen\LawListenMinistryResponseController@export_excel');
        Route::resource('/ministry-response', 'Laws\Listen\LawListenMinistryResponseController');

        //สรุปความเห็น
        Route::get('/ministry-summary/data_list', 'Laws\Listen\LawListenMinistrySummaryController@data_list');
        Route::POST('/ministry-summary/save_result', 'Laws\Listen\LawListenMinistrySummaryController@save_result');
        Route::POST('/ministry-summary/save_close', 'Laws\Listen\LawListenMinistrySummaryController@save_close');
        Route::get('/ministry-summary/export_excel', 'Laws\Listen\LawListenMinistrySummaryController@export_excel');
        Route::get('/ministry-summary/select_mail', 'Laws\Listen\LawListenMinistrySummaryController@select_mail');
        Route::resource('/ministry-summary', 'Laws\Listen\LawListenMinistrySummaryController');

        //บันทึกติดตาม/ประกาศราชกิจจา
        Route::get('/ministry-track/data_list', 'Laws\Listen\LawListenMinistryTrackController@data_list');
        Route::get('/ministry-track/data_list_ministry_summary', 'Laws\Listen\LawListenMinistryTrackController@data_list_ministry_summary');
        Route::resource('/ministry-track', 'Laws\Listen\LawListenMinistryTrackController');
    });

    //Route รายงาน
    Route::prefix('/law/report')->group(function () {

        //รายงานประวัติการดำเนินงาน
        Route::get('/log-working/data_list', 'Laws\Report\LawReportLogWorkingController@data_list');
        Route::resource('/log-working', 'Laws\Report\LawReportLogWorkingController');

        //รายงานสรุปภาพรวมผลงาน
        Route::get('/summary-track-person/data_chart', 'Laws\Report\LawReportSummaryTrackPersonController@data_chart');
        Route::get('/summary-track-person/export_excel_person', 'Laws\Report\LawReportSummaryTrackPersonController@export_excel_person');
        Route::get('/summary-track-person/export_excel', 'Laws\Report\LawReportSummaryTrackPersonController@export_excel');
        Route::get('/summary-track-person/data_track_receive_list', 'Laws\Report\LawReportSummaryTrackPersonController@data_track_receive_list');
        Route::get('/summary-track-person/data_list', 'Laws\Report\LawReportSummaryTrackPersonController@data_list');
        Route::resource('/summary-track-person', 'Laws\Report\LawReportSummaryTrackPersonController');

        //รายงานสรุปข้อมูลห้องสมุด 
        Route::get('/book-list/export_excel', 'Laws\Report\LawReportBooklistController@export_excel');
        Route::get('/book-list/data_list', 'Laws\Report\LawReportBooklistController@data_list');
        Route::resource('/book-list', 'Laws\Report\LawReportBooklistController');

        //รายงานสรุปคดี
        Route::get('/summary-law-cases/export_excel', 'Laws\Report\LawReportSummaryLawCasesController@export_excel');
        Route::get('/summary-law-cases/data_list', 'Laws\Report\LawReportSummaryLawCasesController@data_list');
        Route::resource('/summary-law-cases', 'Laws\Report\LawReportSummaryLawCasesController');

        //รายงานผู้มีส่วนได้ส่วนเสีย
        Route::get('/department-stakeholder/export_excel', 'Laws\Report\LawReportDepartmentStakeholderController@export_excel');
        Route::get('/department-stakeholder/data_list', 'Laws\Report\LawReportDepartmentStakeholderController@data_list');
        Route::resource('/department-stakeholder', 'Laws\Report\LawReportDepartmentStakeholderController');

        
        //รายงานผู้มีส่วนได้ส่วนเสีย 
        Route::get('/rewards/search_users', 'Laws\Report\LawReportRewardsController@search_users');
        Route::get('/rewards/export_excel', 'Laws\Report\LawReportRewardsController@export_excel');
        Route::get('/rewards/data_list', 'Laws\Report\LawReportRewardsController@data_list');
        Route::resource('/rewards', 'Laws\Report\LawReportRewardsController');

        // รายงานผู้มีสิทธิ์ได้รับเงินรางวัล จำแนกตามบุคคล
        Route::get('/rewards_persons/export_excel', 'Laws\Report\LawRewardsPersonsController@export_excel');
        Route::get('/rewards_persons/data_list', 'Laws\Report\LawRewardsPersonsController@data_list');
        Route::resource('/rewards_persons', 'Laws\Report\LawRewardsPersonsController');

        // รายงานการชำระเงินค่าปรับ
        Route::get('/payments/export_excel', 'Laws\Report\LawPaymentsController@export_excel');
        Route::get('/payments/data_list', 'Laws\Report\LawPaymentsController@data_list');
        Route::resource('/payments', 'Laws\Report\LawPaymentsController');

        //รายงานประวัติกระทำความผิด
        Route::get('/summary-law-offender-cases/export_excel', 'Laws\Report\LawReportSummaryLawOffenderCasesController@export_excel');
        Route::get('/summary-law-offender-cases/data_list', 'Laws\Report\LawReportSummaryLawOffenderCasesController@data_list');
        Route::resource('/summary-law-offender-cases', 'Laws\Report\LawReportSummaryLawOffenderCasesController');

        // รายงานการชำระเงินค่าปรับ
        Route::get('/listen/ministry/mail/export_excel', 'Laws\Report\LawListenMinistryNotifysController@export_excel');
        Route::get('/listen/ministry/mail/data_list', 'Laws\Report\LawListenMinistryNotifysController@data_list');
        Route::resource('/listen/ministry/mail', 'Laws\Report\LawListenMinistryNotifysController');
        
    });
     
    //Route แจ้งงานคดี
    Route::prefix('/law/cases')->group(function () {
        
        //แจ้งงานคดี 
        Route::get('/forms/get_offend_ref_tb', 'Laws\Cases\LawCasesFormController@get_offend_ref_tb');
        Route::get('/forms/license_numbers', 'Laws\Cases\LawCasesFormController@license_numbers');
        Route::POST('/forms/save_additionals', 'Laws\Cases\LawCasesFormController@save_additionals');
        Route::POST('/forms/update_assign', 'Laws\Cases\LawCasesFormController@update_assign');
        Route::POST('/forms/infomation-save', 'Laws\Cases\LawCasesFormController@infomation_save');
        Route::get('/forms/{id}/destroy', 'Laws\Cases\LawCasesFormController@destroy');
        Route::get('/forms/data_list', 'Laws\Cases\LawCasesFormController@data_list');
        Route::get('/forms/data_department', 'Laws\Cases\LawCasesFormController@data_department');
        Route::POST('/forms/delete', 'Laws\Cases\LawCasesFormController@delete'); 
        Route::put('/forms/update-state', 'Laws\Cases\LawCasesFormController@update_state');
        Route::POST('/forms/save-cancel', 'Laws\Cases\LawCasesFormController@save_cancel');
        Route::get('/forms/get_owner_department/{owner_depart_type}', 'Laws\Cases\LawCasesFormController@get_owner_department');
        Route::get('/forms/get_level_approves/{cases_id}', 'Laws\Cases\LawCasesFormController@get_level_approves');
        Route::get('/forms/section-relation/{section_id}', 'Laws\Cases\LawCasesFormController@section_relation');
        Route::get('/forms/user_register/{userid}', 'Laws\Cases\LawCasesFormController@user_register');
        Route::get('/forms/table_tbody_approve/{sub_department_id}', 'Laws\Cases\LawCasesFormController@table_tbody_approve');
        Route::get('/forms/get_m_bs_reward_group', 'Laws\Cases\LawCasesFormController@get_m_bs_reward_group'); 
        Route::get('/forms/get_user_departments', 'Laws\Cases\LawCasesFormController@get_user_departments'); 
        Route::get('/forms/get_file_additionals', 'Laws\Cases\LawCasesFormController@get_file_additionals');
        Route::get('/forms/delete_file_additionals', 'Laws\Cases\LawCasesFormController@delete_file_additionals');
        Route::resource('/forms', 'Laws\Cases\LawCasesFormController');
        
        // รายชื่อเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
        // Route::resource('/forms', 'Laws\Cases\LawCasesStaffListController');

        // มอบหมายงานคดีผลิตภัณฑ์ฯ
        Route::POST('assigns/save_close_assign', 'Laws\Cases\AssignController@save_close_assign');
        Route::POST('assigns/save_assign', 'Laws\Cases\AssignController@save_assign');
        Route::POST('assigns/save_select_assign', 'Laws\Cases\AssignController@save_select_assign');
        Route::get('assigns/data_list', 'Laws\Cases\AssignController@data_list');
        Route::get('assigns/{id}', 'Laws\Cases\AssignController@show');
        Route::resource('/assigns ', 'Laws\Cases\AssignController');
        
        // พิจารณาความผิด
        Route::get('/results/log_document', 'Laws\Cases\ResultController@log_document');
        Route::get('/results/word_accept/{id}', 'Laws\Cases\ResultController@word_accept');
        Route::get('/results/word_offend/{id}', 'Laws\Cases\ResultController@word_offend');
        Route::POST('/results/printing/{id}', 'Laws\Cases\ResultController@save_printing');
        Route::get('/results/{id}/printing', 'Laws\Cases\ResultController@printing');
        Route::get('/results/consider_punish', 'Laws\Cases\ResultController@consider_punish');
        Route::get('/results/check_case_number', 'Laws\Cases\ResultController@check_case_number');
        Route::POST('/results/consider/{id}', 'Laws\Cases\ResultController@save_consider');
        Route::get('/results/{id}/consider', 'Laws\Cases\ResultController@consider');
        Route::POST('/results/document/{id}', 'Laws\Cases\ResultController@save_document');
        Route::get('/results/{id}/document', 'Laws\Cases\ResultController@document');
        Route::get('results/data_list', 'Laws\Cases\ResultController@data_list');
        Route::get('/results', 'Laws\Cases\ResultController@index');

        //สืบค้นประวัติการกระทำความผิด
        Route::POST('offender/update_cases', 'Laws\Cases\LawCasesOffenderController@update_cases');    
        Route::get('offender/html', 'Laws\Cases\LawCasesOffenderController@GetHtmlCases');
        Route::get('offender/data_offender_history', 'Laws\Cases\LawCasesOffenderController@data_offender_history');
        Route::PATCH('/offender/infomation-save/{id?}', 'Laws\Cases\LawCasesOffenderController@infomation_save');
        Route::get('offender/data_offender_files', 'Laws\Cases\LawCasesOffenderController@data_offender_files');
        Route::get('offender/data_offender_certify', 'Laws\Cases\LawCasesOffenderController@data_offender_certify');
        Route::get('offender/data_offender_cases', 'Laws\Cases\LawCasesOffenderController@data_offender_cases');
        Route::get('offender/data_list', 'Laws\Cases\LawCasesOffenderController@data_list');    
        Route::resource('/offender', 'Laws\Cases\\LawCasesOffenderController');

        //ดำเนินการกับผลิตภัณฑ์
        Route::get('manage-products/data_list', 'Laws\Cases\LawCasesManageProductsController@data_list');
        Route::get('manage-products/{id?}/report', 'Laws\Cases\LawCasesManageProductsController@report');
        Route::PATCH('manage-products/save-report/{id?}', 'Laws\Cases\LawCasesManageProductsController@save_report');
        Route::resource('/manage-products', 'Laws\Cases\\LawCasesManageProductsController');

        //บันทึกจัดส่งหนังสือ
        Route::get('/delivery/get-law-cases/{id?}', function ($id){
            $data = LawCasesForm::find($id);
            if( !is_null($data) ){
                $data->assign_email = !is_null($data->user_assign_to) && !empty($data->user_assign_to->reg_email)?$data->user_assign_to->reg_email:null;
                $data->lawyer_email = !is_null($data->user_lawyer_to) && !empty($data->user_lawyer_to->reg_email)?$data->user_lawyer_to->reg_email:null;
                $data->create_email = !is_null($data->user_created) && !empty($data->user_created->reg_email)?$data->user_created->reg_email:null;

            }
            return response()->json($data, JSON_UNESCAPED_UNICODE);
        });
        Route::get('/delivery/get-send-no', function (Request $request){

            $law_case_id =  $request->get('law_case_id');
            $send_type   =  $request->get('send_type');

            $data = LawCasesDelivery::where('law_case_id',$law_case_id)->where('send_type', $send_type)->max('send_no');

            $no = 1;
            if( !is_null($data) ){
                $no = ( (int)$data + 1 );
            }
            return response()->json($no, JSON_UNESCAPED_UNICODE);
        });
        Route::get('delivery/data_file_list', 'Laws\Cases\LawCasesDeliveryController@data_file_list');
        Route::get('delivery/data_list', 'Laws\Cases\LawCasesDeliveryController@data_list');
        Route::resource('/delivery', 'Laws\Cases\\LawCasesDeliveryController');

         // บันทึกการดำเนินงานคดี
        Route::POST('operations/save_close_assign', 'Laws\Cases\LawOperationsController@save_close_assign');
        Route::get('operations/data_list', 'Laws\Cases\LawOperationsController@data_list');
        Route::resource('/operations',  'Laws\Cases\LawOperationsController');

        //  ดำเนินการกับใบอนุญาต
        Route::POST('manage_license/update_cancel_cancel', 'Laws\Cases\LawManageLicenseController@update_cancel_cancel');
        Route::get('manage_license/data_list', 'Laws\Cases\LawManageLicenseController@data_list');
        Route::resource('/manage_license',  'Laws\Cases\LawManageLicenseController');

         //  ดำเนินการกับใบอนุญาต
        Route::get('tracks/data_list', 'Laws\Cases\LawTracksController@data_list');
        Route::get('tracks/{id}', 'Laws\Cases\LawTracksController@show');
        Route::get('/tracks',  'Laws\Cases\LawTracksController@index');

        //  เปรียบเทียบปรับ
        Route::get('compares/data_list', 'Laws\Cases\LawComparesController@data_list');
        Route::get('compares/check_pay_in', 'Laws\Cases\LawComparesController@check_pay_in');
        Route::get('compares/check_payments_date', 'Laws\Cases\LawComparesController@check_payments_date');
        Route::POST('compares/consider_adjusting/{id}', 'Laws\Cases\LawComparesController@save_consider_adjusting');
        Route::get('compares/{id}/consider-adjusting', 'Laws\Cases\LawComparesController@consider_adjusting');
        Route::PATCH('compares/fact-update/{id?}', 'Laws\Cases\LawComparesController@fact_update');
        Route::PATCH('compares/calculate-update/{id?}', 'Laws\Cases\LawComparesController@calculate_update');
        Route::PATCH('compares/printing-update/{id?}', 'Laws\Cases\LawComparesController@printing_update');
        Route::get('compares/printing/{id?}', 'Laws\Cases\LawComparesController@printing');
        Route::POST('compares/save_compares', 'Laws\Cases\LawComparesController@save_compares');
        Route::resource('/compares',  'Laws\Cases\LawComparesController');

        //  สร้างใบชำระเงิน (Pay-in)
        Route::get('payin/data_payments', 'Laws\Cases\LawPayinController@data_payments');
        Route::get('payin/check_pay_in', 'Laws\Cases\LawPayinController@check_pay_in');
        Route::POST('payin/save_payin', 'Laws\Cases\LawPayinController@save_payin');
        Route::POST('payin/{id}', 'Laws\Cases\LawPayinController@update');
        Route::get('payin/{id}/edit', 'Laws\Cases\LawPayinController@edit');
        Route::get('payin/data_list', 'Laws\Cases\LawPayinController@data_list');
        Route::get('payin', 'Laws\Cases\LawPayinController@index');
        
        // ตรวจสอบการชำระ
        Route::POST('payment/{id}', 'Laws\Cases\LawPaymentController@update');
        Route::get('payment/data_list', 'Laws\Cases\LawPaymentController@data_list');
        Route::resource('/payment',  'Laws\Cases\LawPaymentController');

        
        // พิจารณางานคดี
        Route::get('/forms_approved/get_level_approves/{cases_id}/{group?}', 'Laws\Cases\FormsApprovedController@get_level_approves');
        Route::get('/forms_approved/get_user_approve',  'Laws\Cases\FormsApprovedController@get_user_approve');
        Route::get('forms_approved/data_list', 'Laws\Cases\FormsApprovedController@data_list');
        Route::POST('forms_approved/save', 'Laws\Cases\FormsApprovedController@update');
        Route::POST('forms_approved/update_form', 'Laws\Cases\FormsApprovedController@update_form');
        Route::get('forms_approved/{id}', 'Laws\Cases\FormsApprovedController@show');
        Route::get('/forms_approved',  'Laws\Cases\FormsApprovedController@index');
        
    });

    //Route ตั้งค่า
    Route::prefix('/law/config')->group(function () {
    
        //หมวดหมู่ระบบงานหลัก
        Route::get('/system-category/{id}/destroy', 'Laws\Config\LawConfigSystemCategoryController@destroy');
        Route::get('/system-category/data_list', 'Laws\Config\LawConfigSystemCategoryController@data_list');
        Route::POST('/system-category/delete', 'Laws\Config\LawConfigSystemCategoryController@delete');
        Route::put('/system-category/update-state', 'Laws\Config\LawConfigSystemCategoryController@update_state');
        Route::put('/system-category/update-notify', 'Laws\Config\LawConfigSystemCategoryController@update_notify');
        Route::resource('/system-category', 'Laws\Config\LawConfigSystemCategoryController');
    
        //ตั้งค่างานคดี
        Route::resource('/config-law', 'Laws\Config\\LawConfigController');
        Route::POST('/config-law/sendemail', 'Laws\Config\LawConfigController@sendemail');
        Route::POST('/config-law/sendemail/update', 'Laws\Config\LawConfigController@sendemail_update');
        Route::POST('/config-receipt', 'Laws\Config\LawConfigController@save_receipt');

        Route::get('/sections/{id}/destroy', 'Laws\Config\LawConfigSectionController@destroy');
        Route::get('/sections/data_list', 'Laws\Config\LawConfigSectionController@data_list');
        Route::POST('/sections/delete', 'Laws\Config\LawConfigSectionController@delete');
        Route::put('/sections/update-state', 'Laws\Config\LawConfigSectionController@update_state');
        Route::POST('/sections/section-relation', 'Laws\Config\LawConfigSectionController@section_relation');
        Route::get('/sections/basic_section/{id}', 'Laws\Config\LawConfigSectionController@basic_section');
        Route::resource('/sections', 'Laws\Config\LawConfigSectionController');
    
        // Route::get('/reward/{id}/destroy', 'Laws\Config\LawConfigRewardController@destroy');
        // Route::get('/reward/data_list', 'Laws\Config\LawConfigRewardController@data_list');
        // Route::POST('/reward/delete', 'Laws\Config\LawConfigRewardController@delete');
        // Route::put('/reward/update-state', 'Laws\Config\LawConfigRewardController@update_state');
        // Route::resource('/reward', 'Laws\Config\LawConfigRewardController');
    
        Route::get('/notification/{id}/destroy', 'Laws\Config\LawConfigNotificationController@destroy');
        Route::get('/notification/data_list', 'Laws\Config\LawConfigNotificationController@data_list');
        Route::POST('/notification/delete', 'Laws\Config\LawConfigNotificationController@delete');
        Route::put('/notification/update-state', 'Laws\Config\LawConfigNotificationController@update_state');
        Route::resource('/notification', 'Laws\Config\LawConfigNotificationController');

    });

    //แจ้งเตือน
    Route::get('/law/notifys/get-json-menu', 'Laws\Notification\LawNotifysController@loadJsonMenu');
    Route::get('/law/notifys/menu', 'Laws\Notification\LawNotifysController@menu');
    Route::get('/law/notifys/data_list', 'Laws\Notification\LawNotifysController@data_list');
    Route::put('/law/notifys/update-marked', 'Laws\Notification\LawNotifysController@update_marked');    
    Route::put('/law/notifys/update-state', 'Laws\Notification\LawNotifysController@update_state');        
    Route::resource('/law/notifys', 'Laws\Notification\LawNotifysController');
    
    
    
    //Route สินบน
    Route::prefix('/law/reward')->group(function () {
        //ประเภทการแบ่งเงิน
        Route::get('/divsion-type/data_list', 'Laws\Reward\LawCdivsionTypeController@data_list');
        Route::resource('/divsion-type', 'Laws\Reward\LawCdivsionTypeController');

       //หมวดหมู่ระบบงานหลัก
        Route::get('/reward_max/get_data_reward_max', 'Laws\Reward\LawRewardMaxController@get_data_reward_max');
        Route::POST('/reward_max/save', 'Laws\Reward\LawRewardMaxController@save');
        Route::get('/reward_max/data_list', 'Laws\Reward\LawRewardMaxController@data_list');
        Route::get('/reward_max', 'Laws\Reward\LawRewardMaxController@index');

        //ประเภทการแบ่งเงิน
        Route::get('/reward/data_list', 'Laws\Reward\LawRewardController@data_list');
        Route::resource('/reward', 'Laws\Reward\LawRewardController');

        //คำนวณสินบน 
        Route::get('/calculations/get_taxid', 'Laws\Reward\LawCalculationsController@get_taxid');
        Route::get('/calculations/config_reward', 'Laws\Reward\LawCalculationsController@config_reward');
        Route::get('/calculations/print_pdf/{id}', 'Laws\Reward\LawCalculationsController@print_pdf');
        Route::post('calculations/update_document','Laws\Reward\\LawCalculationsController@update_document');
        Route::get('/calculations/data_list', 'Laws\Reward\LawCalculationsController@data_list');
        Route::resource('calculations', 'Laws\Reward\LawCalculationsController');

        //ใบสำคัญรับเงิน  
        Route::post('/receipts/update_receipts', 'Laws\Reward\LawReceiptsController@update_receipts');
        Route::get('/receipts/get_datas_html', 'Laws\Reward\LawReceiptsController@get_datas_html');
        Route::get('/receipts/preview', 'Laws\Reward\LawReceiptsController@preview');
        Route::get('/receipts/create', 'Laws\Reward\LawReceiptsController@create');
        Route::get('/receipts/data_case_list', 'Laws\Reward\LawReceiptsController@data_case_list');
        Route::get('/receipts/data_list', 'Laws\Reward\LawReceiptsController@data_list');
        Route::post('/receipts', 'Laws\Reward\LawReceiptsController@store');
        Route::get('/receipts', 'Laws\Reward\LawReceiptsController@index');
        
        // เบิกเงินรางวัล
        Route::get('/withdraws/download/{id}', 'Laws\Reward\LawWithdrawsController@download');
        Route::post('/withdraws/update_withdraws', 'Laws\Reward\LawWithdrawsController@update_withdraws');
        Route::get('/withdraws/print_pdf/{id}', 'Laws\Reward\LawWithdrawsController@print_pdf');
        Route::get('/withdraws/data_detail_list', 'Laws\Reward\LawWithdrawsController@data_detail_list');
        Route::get('/withdraws/data_list', 'Laws\Reward\LawWithdrawsController@data_list');
        Route::resource('withdraws', 'Laws\Reward\LawWithdrawsController');

        
    }); 

    //Route Export
    Route::prefix('/law/export')->group(function () {

        //พิมพ์หนังสือเปรียบเทียบ
        Route::get('/compares/book', 'Laws\Word\Cases\LawComparesController@word_cases_compare');
        //พิมพ์หนังสือข้อเท็จจริง
        Route::get('/compares/fact', 'Laws\Word\Cases\LawComparesController@word_cases_fact');
        //ไฟล์หนังสือการกระทำผิด
        Route::get('/results/book_charges', 'Laws\Word\Cases\LawResultController@word_book_charges');
        //ไฟล์หนังสือบันทึกคำให้การ
        Route::get('/results/book_statements', 'Laws\Word\Cases\LawResultController@word_book_statements');


    });

    