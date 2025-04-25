<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\AttachFile;

use App\Models\Basic\BranchTis;
use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Basic\Tis;
use App\Models\Tis\Standard;

//Dashboard Menu
Route::get('accepting-request-section5', 'Section5\\DashboardController@index');

//Route
Route::prefix('/section5')->group(function () {

    //Funtion
    Route::get('/get-branch-data/{id?}', 'FuntionCenter\\Section5Controller@GetBranchData');
    Route::get('/get-test-tools/{id?}', 'FuntionCenter\\Section5Controller@GetTestItemTools');
    Route::get('/get-test-item/{tis_id?}', 'FuntionCenter\\Section5Controller@GetTestItem');
    Route::POST('/save_test_tools', 'FuntionCenter\\Section5Controller@save_test_tools');
    Route::get('/get-basic-tools/{test_item_id?}', 'FuntionCenter\\Section5Controller@GetBasicTools');
    Route::get('/application-lab-scope/{id?}', 'FuntionCenter\\Section5Controller@ApplicationLabsScope');
    Route::get('/application/workgroup_ib_staff', 'FuntionCenter\\Section5Controller@workgroup_ib_staff');
    Route::get('/application/workgroup_lab_staff', 'FuntionCenter\\Section5Controller@workgroup_lab_staff');
    Route::get('/sign_position/{id}', 'FuntionCenter\\Section5Controller@signPosition');

    Route::get('/function/get-branche/{branch_group?}', function( $branch_group ){
        $data = Branch::where('branch_group_id', $branch_group)->get();
        return response()->json($data);
    });
    
    Route::get('/function/get-branche-tis/{branch_group?}', function( $branch_ids ){
        $branch_ids = explode(',', $branch_ids);
        $data = DB::table((new BranchTis)->getTable().' AS branch')
                    ->leftJoin((new Tis)->getTable().' AS std', 'std.tb3_TisAutono', '=', 'branch.tis_id')
                    ->leftJoin((new Branch)->getTable().' AS b', 'b.id', '=', 'branch.branch_id')
                    ->when(count($branch_ids) > 0, function($query) use ($branch_ids) {
                        $query->whereIn('branch.branch_id', $branch_ids);
                    })
                    ->selectRaw('CONCAT_WS(" : ", std.tb3_Tisno, std.tb3_TisThainame) AS title, std.tb3_TisAutono AS id, b.title AS branch_title')
                    ->get();
    
        return response()->json($data);
    });
    Route::get('/get-view/files/{systems}/{tax_number}/{new_filename}/{filename}', function($systems,$tax_number,$new_filename,$filename)
    {
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
                return $filePath;
            }
        }else{
            return 'ไม่พบไฟล์';
        }
    });

    Route::get('/delete-files/{id?}/{url_send?}', function( $id, $url_send){
        $attach =  App\AttachFile::findOrFail($id);
        if( !empty($attach) && !empty($attach->url) ){

            if( HP::checkFileStorage( '/'.$attach->url) ){
                Storage::delete( '/'.$attach->url );

                $attach->delete();
            }
            return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');
        }
    });
    //END Funtion

    //IB
    //รับคำขอเป็นหน่วยตรวจสอบ (IB)
    Route::get('/accept-inspection-unit/data_list', 'Section5\AcceptInspectionUnitController@data_list');
    Route::PATCH('/accept-inspection-unit/approve-save/{id?}', 'Section5\\AcceptInspectionUnitController@approve_save');
    Route::get('/accept-inspection-unit/approve/{id?}', 'Section5\\AcceptInspectionUnitController@approve');
    Route::Post('/accept-inspection-unit/assing_data_update', 'Section5\\AcceptInspectionUnitController@assing_data_update');
    Route::resource('/accept-inspection-unit', 'Section5\\AcceptInspectionUnitController');

    //รับคำขอเป็นผู้ตรวจ/ผู้ประเมิน
    Route::get('/application_inspectors_accept/data_list', 'Section5\ApplicationInspectorsAcceptController@data_list');
    Route::PATCH('/application_inspectors_accept/approve-save/{id?}', 'Section5\\ApplicationInspectorsAcceptController@approve_save');
    Route::get('/application_inspectors_accept/approve/{id?}', 'Section5\\ApplicationInspectorsAcceptController@approve');
    Route::Post('/application_inspectors_accept/assing_data_update', 'Section5\\ApplicationInspectorsAcceptController@assing_data_update');
    Route::resource('/application_inspectors_accept', 'Section5\\ApplicationInspectorsAcceptController');

    // ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม
    Route::PATCH('/application-inspectors-agreement/attach-save/{id?}', 'Section5\\ApplicationInspectorsAgreementController@attach_save');
    Route::get('/application-inspectors-agreement/attach_document/{id?}', 'Section5\\ApplicationInspectorsAgreementController@attach_document');
    Route::get('/application-inspectors-agreement/preview_document/{id?}', 'Section5\\ApplicationInspectorsAgreementController@preview_document');
    Route::PATCH('/application-inspectors-agreement/document-save/{id?}', 'Section5\\ApplicationInspectorsAgreementController@document_save');
    Route::get('/application-inspectors-agreement/create_document/{id?}', 'Section5\\ApplicationInspectorsAgreementController@create_document');
    Route::get('/application-inspectors-agreement/data_list', 'Section5\ApplicationInspectorsAgreementController@data_list');
    Route::resource('/application-inspectors-agreement', 'Section5\\ApplicationInspectorsAgreementController');

    //ระบบรายชื่อผู้ตรวจแล้วผู้ประเมิน
    Route::get('/inspectors/get-scope-detail/{id}', 'Section5\ManageInspectorsController@get_scope_detail');
    Route::Post('/inspectors/minus_scope', 'Section5\\ManageInspectorsController@minus_scope');
    Route::Post('/inspectors/plus_scope', 'Section5\\ManageInspectorsController@plus_scope');
    Route::PATCH('/inspectors/infomation-save/{id?}', 'Section5\\ManageInspectorsController@infomation_save');
    Route::get('/inspectors/data_list', 'Section5\\ManageInspectorsController@data_list');
    Route::get('/inspectors/data_std_list', 'Section5\\ManageInspectorsController@data_std_list');
    Route::get('/inspectors/get-branche-tis/{id?}', 'Section5\\ManageInspectorsController@getDataBrancheTis');
    Route::resource('/inspectors', 'Section5\\ManageInspectorsController');

    //End IB

    //LAB
    //รับคำขอเป็นผู้ตรวจสอบ (LAB)
    Route::get('/application_lab_accept/data_list', 'Section5\ApplicationLabAcceptController@data_list');
    Route::PATCH('/application_lab_accept/approve-save/{id?}', 'Section5\\ApplicationLabAcceptController@approve_save');
    Route::get('/application_lab_accept/approve/{id?}', 'Section5\\ApplicationLabAcceptController@approve');
    Route::Post('/application_lab_accept/assing_data_update', 'Section5\\ApplicationLabAcceptController@assing_data_update');
    Route::get('/application_lab_accept/print/{id?}', 'Section5\\ApplicationLabAcceptController@print');
    Route::resource('/application_lab_accept', 'Section5\\ApplicationLabAcceptController');

    // ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)
    Route::get('/application_lab_audit/get-application-summary', 'Section5\ApplicationLabAuditController@get_application_summary');
    Route::get('/application_lab_audit/word', 'Section5\ApplicationLabAuditController@export_word');
    Route::Post('/application_lab_audit/gen_lab_reports', 'Section5\ApplicationLabAuditController@gen_lab_reports');
    Route::Post('/application_lab_audit/update_lab_approve', 'Section5\ApplicationLabAuditController@update_lab_approve');
    Route::Post('/application_lab_audit/update_lab_reports', 'Section5\ApplicationLabAuditController@update_lab_reports');
    Route::Post('/application_lab_audit/update_lab_checkings', 'Section5\ApplicationLabAuditController@update_lab_checkings');
    Route::get('/application_lab_audit/get-application-data', 'Section5\ApplicationLabAuditController@GetdataApplicationLab');
    Route::get('/application_lab_audit/data_list', 'Section5\ApplicationLabAuditController@data_list');
    Route::get('/application_lab_audit/lab_report/{id?}', 'Section5\ApplicationLabAuditController@lab_report');
    Route::get('/application_lab_audit/lab_report_approve/{id?}', 'Section5\ApplicationLabAuditController@lab_report_approve');
    Route::PATCH('/application_lab_audit/lab_report/{id?}', 'Section5\ApplicationLabAuditController@lab_report_save');
    Route::PATCH('/application_lab_audit/lab_report_approve/{id?}', 'Section5\ApplicationLabAuditController@lab_report_approve_save');
    Route::put('/applicationlabaudit/update-state', 'Section5\ApplicationLabAuditController@update_state');
    Route::resource('/application_lab_audit', 'Section5\\ApplicationLabAuditController');

    //ระบบรายชื่อหน่วยตรวจสอบ (LAB)
    Route::get('/labs/data_list_cer', 'Section5\\ManageLabsController@data_list_cer');
    Route::get('/labs/get-log-certify/{id?}', 'Section5\ManageLabsController@get_log_certify');
    Route::POST('/labs/update_expiration_date_scope', 'Section5\\ManageLabsController@update_expiration_date_scope');
    Route::get('/labs/html_scope/{id?}', 'Section5\ManageLabsController@html_scope');
    Route::POST('/labs/minus_scope', 'Section5\\ManageLabsController@minus_scope');
    Route::POST('/labs/sync_to_elicense', 'Section5\\ManageLabsController@sync_to_elicense');
    Route::get('/labs/get_scope_active/{id?}', 'Section5\ManageLabsController@get_scope_active');
    Route::get('/labs/delete_std_tools/{id?}', 'Section5\ManageLabsController@DeleteScopeDetail');
    Route::POST('/labs/save_std_tools', 'Section5\\ManageLabsController@SaveScopeDetail');
    Route::POST('/labs/save_std_test_item', 'Section5\\ManageLabsController@SaveStdTestItem');
    Route::get('/labs/treeview_scope', 'Section5\ManageLabsController@GetDataTestItem');
    Route::PATCH('/labs/contact-save/{id?}', 'Section5\ManageLabsController@contact_save');
    Route::PATCH('/labs/infomation-save/{id?}', 'Section5\ManageLabsController@infomation_save');
    Route::PATCH('/labs/account-save/{id?}', 'Section5\ManageLabsController@account_save');
    Route::get('/labs/data_list', 'Section5\ManageLabsController@data_list');
    Route::get('/labs/get-scope-detail/{id}', 'Section5\ManageLabsController@get_scope_detail');
    Route::get('/labs/search_user/{sso_username}', 'Section5\ManageLabsController@search_user');
    Route::resource('/labs', 'Section5\\ManageLabsController');

    //ระบบผลการเสนออนุมัติ (LAB)
    Route::get('/application-lab-board-approve/get_issue_gazette', 'Section5\ApplicationLabBoardApproveController@getIssueGazette');
    Route::post('/application-lab-board-approve/update_tisi_approve', 'Section5\ApplicationLabBoardApproveController@update_tisi_approve');
    Route::post('/application-lab-board-approve/update_board_approve', 'Section5\ApplicationLabBoardApproveController@update_board_approve');
    Route::post('/application-lab-board-approve/update_approve', 'Section5\ApplicationLabBoardApproveController@update_approve');
    Route::Post('/application-lab-board-approve/save_data_gazette', 'Section5\ApplicationLabBoardApproveController@save_data_gazette');
    Route::get('/application-lab-board-approve/load_data_gazette/{id?}', 'Section5\ApplicationLabBoardApproveController@load_data_gazette');
    Route::get('/application-lab-board-approve/word/{id?}', 'Section5\ApplicationLabBoardApproveController@GenWord');
    Route::Post('/application-lab-board-approve/save_announcement', 'Section5\ApplicationLabBoardApproveController@save_announcement');
    Route::PATCH('/application-lab-board-approve/gazette-save/{id?}', 'Section5\\ApplicationLabBoardApproveController@gazette_save');
    Route::get('/application-lab-board-approve/gazette/{id?}', 'Section5\\ApplicationLabBoardApproveController@gazette');
    Route::PATCH('/application-lab-board-approve/approve-save/{id?}', 'Section5\\ApplicationLabBoardApproveController@approve_save');
    Route::get('/application-lab-board-approve/approve/{id?}', 'Section5\\ApplicationLabBoardApproveController@approve');
    Route::get('/application-lab-board-approve/data_list', 'Section5\ApplicationLabBoardApproveController@data_list');
    Route::get('/application-lab-board-approve/tisi_approve/{id?}', 'Section5\\ApplicationLabBoardApproveController@tisi_approve');
    Route::PATCH('/application-lab-board-approve/tisi-approve-save/{id?}', 'Section5\\ApplicationLabBoardApproveController@tisi_approve_save');
    Route::resource('/application-lab-board-approve', 'Section5\\ApplicationLabBoardApproveController');

    //ระบบผลการเสนออนุมัติ (LAB)
    Route::Post('/application-inspectors-audit/update_application_approve', 'Section5\ApplicationInspectorsAuditController@update_application_approve');
    Route::Post('/application-inspectors-audit/update_application_checkings', 'Section5\ApplicationInspectorsAuditController@update_application_checkings');
    Route::get('/application-inspectors-audit/get-application-data', 'Section5\ApplicationInspectorsAuditController@GetdataApplication');
    Route::PATCH('/application-inspectors-audit/approve-save/{id?}', 'Section5\\ApplicationInspectorsAuditController@approve_save');
    Route::get('/application-inspectors-audit/approve/{id?}', 'Section5\\ApplicationInspectorsAuditController@approve');
    Route::PATCH('/application-inspectors-audit/checkings_save/{id?}', 'Section5\ApplicationInspectorsAuditController@checkings_save');
    Route::get('/application-inspectors-audit/checkings/{id?}', 'Section5\\ApplicationInspectorsAuditController@checkings');
    Route::get('/application-inspectors-audit/data_list', 'Section5\ApplicationInspectorsAuditController@data_list');
    Route::resource('/application-inspectors-audit', 'Section5\\ApplicationInspectorsAuditController');

    //END LAB

    //IB/CB
    //ระบบรับคำขอเป็น IB/CB
    Route::get('/application_ibcb_accept/data_list', 'Section5\ApplicationIbcbAcceptController@data_list');
    Route::PATCH('/application_ibcb_accept/approve-save/{id?}', 'Section5\\ApplicationIbcbAcceptController@approve_save');
    Route::get('/application_ibcb_accept/approve/{id?}', 'Section5\\ApplicationIbcbAcceptController@approve');
    Route::Post('/application_ibcb_accept/assing_data_update', 'Section5\\ApplicationIbcbAcceptController@assing_data_update');
    Route::resource('/application_ibcb_accept', 'Section5\\ApplicationIbcbAcceptController');

    //บันทึกผลตรวจประเมิน IB/CB
    Route::Post('/application-ibcb-audit/update_application_approve', 'Section5\ApplicationIbcbAuditController@update_application_approve');
    Route::Post('/application-ibcb-audit/update_application_reports', 'Section5\ApplicationIbcbAuditController@update_application_reports');
    Route::Post('/application-ibcb-audit/update_application_checkings', 'Section5\ApplicationIbcbAuditController@update_application_checkings');
    Route::get('/application-ibcb-audit/get-application-data', 'Section5\ApplicationIbcbAuditController@GetdataApplication');
    Route::PATCH('/application-ibcb-audit/approve-save/{id?}', 'Section5\\ApplicationIbcbAuditController@approve_save');
    Route::get('/application-ibcb-audit/approve/{id?}', 'Section5\\ApplicationIbcbAuditController@approve');
    Route::PATCH('/application-ibcb-audit/report-save/{id?}', 'Section5\\ApplicationIbcbAuditController@report_save');
    Route::get('/application-ibcb-audit/report/{id?}', 'Section5\\ApplicationIbcbAuditController@report');
    Route::PATCH('/application-ibcb-audit/results-save/{id?}', 'Section5\\ApplicationIbcbAuditController@results_save');
    Route::get('/application-ibcb-audit/results/{id?}', 'Section5\\ApplicationIbcbAuditController@results');
    Route::get('/application-ibcb-audit/data_list', 'Section5\ApplicationIbcbAuditController@data_list');
    Route::resource('/application-ibcb-audit', 'Section5\\ApplicationIbcbAuditController');

    //ระบบผลการเสนออนุมัติ (IB/CB )
    Route::get('/application-ibcb-board-approve/get_issue_gazette', 'Section5\ApplicationIbcbBoardApproveController@getIssueGazette');
    Route::Post('/application-ibcb-board-approve/update_tisi_approve', 'Section5\ApplicationIbcbBoardApproveController@update_tisi_approve');
    Route::post('/application-ibcb-board-approve/update_board_approve', 'Section5\ApplicationIbcbBoardApproveController@update_board_approve');
    Route::get('/application-ibcb-board-approve/load_data_gazette/{id?}', 'Section5\ApplicationIbcbBoardApproveController@load_data_gazette');
    Route::Post('/application-ibcb-board-approve/save_data_gazette', 'Section5\ApplicationIbcbBoardApproveController@save_data_gazette');
    Route::Post('/application-ibcb-board-approve/save_announcement', 'Section5\ApplicationIbcbBoardApproveController@save_announcement');
    Route::post('/application-ibcb-board-approve/update_approve', 'Section5\ApplicationIbcbBoardApproveController@update_approve');
    Route::PATCH('/application-ibcb-board-approve/gazette-save/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@gazette_save');
    Route::get('/application-ibcb-board-approve/preview_document/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@preview_document');
    Route::get('/application-ibcb-board-approve/gazette/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@gazette');
    Route::PATCH('/application-ibcb-board-approve/approve-save/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@approve_save');
    Route::get('/application-ibcb-board-approve/approve/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@approve');
    Route::get('/application-ibcb-board-approve/data_list', 'Section5\ApplicationIbcbBoardApproveController@data_list');
    Route::get('/application-ibcb-board-approve/tisi_approve/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@tisi_approve');
    Route::PATCH('/application-ibcb-board-approve/tisi-approve-save/{id?}', 'Section5\\ApplicationIbcbBoardApproveController@tisi_approve_save');
    Route::resource('/application-ibcb-board-approve', 'Section5\\ApplicationIbcbBoardApproveController');

    //รายชื่อหน่วยตรวจสอบ IB/CB
    Route::get('/ibcb/data_scope_std', 'Section5\\ManageIbcbsController@data_scope_std');
    Route::Post('/ibcb/minus_scope', 'Section5\\ManageIbcbsController@minus_scope');
    Route::Post('/ibcb/plus_scope', 'Section5\\ManageIbcbsController@plus_scope');
    Route::POST('/ibcb/sync_to_elicense', 'Section5\\ManageIbcbsController@sync_to_elicense');
    Route::Post('/ibcb/update_ibcb_gazette', 'Section5\\ManageIbcbsController@update_ibcb_gazette');
    Route::get('/ibcb/data_government_gazette', 'Section5\\ManageIbcbsController@data_government_gazette');
    Route::get('/ibcb/getDataInspectors', 'Section5\\ManageIbcbsController@getDataInspectors');
    Route::get('/ibcb/get-branche-tis/{id?}', 'Section5\\ManageIbcbsController@getDataBrancheTis');
    Route::get('/ibcb/get-branche/{id?}', 'Section5\\ManageIbcbsController@getDataBranche');
    Route::get('/ibcb/get-standards/{type}', 'Section5\\ManageIbcbsController@getStandards');
    Route::get('/ibcb/getDataCertificate', 'Section5\\ManageIbcbsController@getDataCertificate');
    Route::PATCH('/ibcb/update-ibcb-save/{id?}', 'Section5\ManageIbcbsController@update_ibcb_save');
    Route::get('/ibcb/data_list', 'Section5\\ManageIbcbsController@data_list');
    Route::resource('/ibcb', 'Section5\\ManageIbcbsController');

    //END IB/CB

    //รายงาน
    //การตรวจโรงงาน
    Route::get('/report-factory-inspection/data_list', 'Section5\\ReportFactoryInspectionController@data_list');
    Route::resource('/report-factory-inspection', 'Section5\\ReportFactoryInspectionController');
    //คำขอรับการแต่งตั้งเป็นผู้ตรวจสอบ LAB
    Route::get('/report-labs/data_list', 'Section5\\ReportLabsController@data_list');
    Route::get('/report-labs/export_excel', 'Section5\\ReportLabsController@export_excel');
    Route::resource('/report-labs', 'Section5\\ReportLabsController');

    //การทดสอบผลิตภัณฑ์ (กค.)
    Route::get('/report-product-lab/data_list', 'Section5\\ReportProductLabController@data_list');
    Route::resource('/report-product-lab', 'Section5\\ReportProductLabController');
    Route::get('/report-product-lab/sample/{sample_id}', 'Section5\\ReportProductLabController@sample');
    Route::get('/report-product-lab/test_result/{product_lab_id}', 'Section5\\ReportProductLabController@test_result');

    //การทดสอบผลิตภัณฑ์ (กต.)
    Route::get('/report-example-lab/data_list', 'Section5\\ReportExampleLabController@data_list');
    Route::resource('/report-example-lab', 'Section5\\ReportExampleLabController');
    Route::get('/report-example-lab/test_result/{save_example_map_lap_id}', 'Section5\\ReportExampleLabController@test_result');
    Route::get('/report-example-lab/test_result_item/{save_example_map_lap_id}', 'Section5\\ReportExampleLabController@test_result_item');

    //รายสาขามาตรฐานมอก.
    Route::get('/report-standard-branch/data_list', 'Section5\\ReportStandardBranchController@data_list');
    Route::get('/report-standard-branch/export_excel', 'Section5\\ReportStandardBranchController@export_excel');
    Route::resource('/report-standard-branch', 'Section5\\ReportStandardBranchController');

});




























