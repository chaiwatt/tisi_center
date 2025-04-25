<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'],function (){

    Route::put('basic/trader/update-state', 'Basic\TraderController@update_state');
    Route::resource('basic/trader', 'Basic\\TraderController');

    Route::put('basic/soko/update-state', 'Basic\SokoController@update_state');
    Route::resource('basic/soko', 'Basic\\SokoController');

    Route::post('basic/soko/checkemailexits', 'Basic\SokoController@checkemailexits');

    Route::get('sso/user-sso/data_list', 'SSO\UserController@data_list');
    Route::resource('sso/user-sso', 'SSO\UserController');
    Route::post('sso/user-sso/block', 'SSO\UserController@block');
    Route::post('sso/user-sso/unblock', 'SSO\UserController@unblock');
    Route::post('sso/user-sso/confirm-status', 'SSO\UserController@confirm_status');
    Route::post('sso/user-sso/checkemailexits', 'SSO\UserController@checkemailexits');
    Route::post('sso/user-sso/compare-personal', 'SSO\UserController@ComparePersonal');
    Route::post('sso/user-sso/compare-company', 'SSO\UserController@CompareCompany');
    Route::post('sso/user-sso/compare-rd', 'SSO\UserController@CompareRd');
    Route::post('sso/user-sso/auto-edit-applicanttype', 'SSO\UserController@auto_edit_applicanttype');

    //รายงานการแก้ไข
    Route::get('sso/user-sso-report-edit-production', 'SSO\UserReportEditController@production');
    Route::get('sso/user-sso-report-edit-test', 'SSO\UserReportEditController@test');

    //ลงทะเบียน
    Route::POST('sso/datatype','SSO\UserController@datatype');
    Route::POST('sso/check_tax_number','SSO\UserController@check_tax_number');
    Route::POST('sso/get_tax_number','SSO\UserController@get_tax_number');
    Route::POST('sso/get_legal_entity','SSO\UserController@get_legal_entity');
    Route::POST('sso/get_legal_faculty','SSO\UserController@get_legal_faculty');
    Route::POST('sso/get_taxid','SSO\UserController@get_taxid');
    Route::POST('sso/check_email','SSO\UserController@check_email');
    Route::POST('sso/check_branch_code','SSO\UserController@check_branch_code');
    Route::POST('sso/get_next_username_branch','SSO\UserController@get_next_username_branch');

    Route::get('sso/migrate-e/{start?}', 'SSO\MigrateController@migrate_e');
    Route::get('sso/migrate-nsw/{start?}', 'SSO\MigrateController@migrate_nsw');
    Route::get('sso/migrate-trader/{start?}', 'SSO\MigrateController@migrate_trader');
    Route::get('sso/migrate-role/{start?}', 'SSO\MigrateController@migrate_role');
    Route::get('sso/migrate-date_niti/{start?}', 'SSO\MigrateController@migrate_date_niti');
    Route::get('sso/migrate-factory/{start?}', 'SSO\MigrateController@migrate_factory');

    //นำเข้าผู้ตรวจประเมิน
    Route::get('sso/migrate-inspector', 'SSO\MigrateSection5Controller@inspector');

    //นำเข้าหน่วยตรวจ (IB)
    Route::get('sso/migrate-ib', 'SSO\MigrateSection5Controller@ib');

    //นำเข้าหน่วยตรวจ (IB)
    Route::get('sso/migrate-lab', 'SSO\MigrateSection5Controller@lab');
});
