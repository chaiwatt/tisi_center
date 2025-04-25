<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;

Route::group(['middleware' => 'auth'],function (){

    Route::put('basic/product_group/update-state', 'Basic\ProductGroupController@update_state');
    Route::resource('basic/product_group', 'Basic\\ProductGroupController');

    Route::put('basic/standard_type/update-state', 'Basic\StandardTypeController@update_state');
    Route::resource('basic/standard_type', 'Basic\\StandardTypeController');

    Route::put('basic/cluster/update-state', 'Basic\ClusterController@update_state');
    Route::resource('basic/cluster', 'Basic\\ClusterController');

    Route::put('basic/method/update-state', 'Basic\MethodController@update_state');
    Route::resource('basic/method', 'Basic\\MethodController');

    Route::put('basic/industry_target/update-state', 'Basic\IndustryTargetController@update_state');
    Route::resource('basic/industry_target', 'Basic\\IndustryTargetController');

    Route::put('basic/status_operation/update-budget_state', 'Basic\StatusOperationController@update_budget_state');
    Route::put('basic/status_operation/update-state', 'Basic\StatusOperationController@update_state');
    Route::resource('basic/status_operation', 'Basic\\StatusOperationController');

    Route::put('basic/board_type/update-state', 'Basic\BoardTypeController@update_state');
    Route::resource('basic/board_type', 'Basic\\BoardTypeController');

    Route::put('basic/standard_format/update-state', 'Basic\StandardFormatController@update_state');
    Route::resource('basic/standard_format', 'Basic\\StandardFormatController');

    Route::put('basic/set_format/update-state', 'Basic\SetFormatController@update_state');
    Route::resource('basic/set_format', 'Basic\\SetFormatController');

    Route::put('basic/config_term/update-state', 'Basic\ConfigTermController@update_state');
    Route::resource('basic/config_term', 'Basic\\ConfigTermController');

    Route::put('basic/department/update-state', 'Basic\DepartmentController@update_state');
    Route::resource('basic/department', 'Basic\\DepartmentController');

    Route::put('basic/ics/update-state', 'Basic\IcsController@update_state');
    Route::resource('basic/ics', 'Basic\\IcsController');

    Route::put('basic/appoint_department/update-state', 'Basic\AppointDepartmentController@update_state');
    Route::POST('basic/appoint_department/save_appoint_department', 'Basic\AppointDepartmentController@save_appoint_department');
    Route::resource('basic/appoint_department', 'Basic\\AppointDepartmentController');

    Route::get('basic/amphur/list/{province_id}', function ($province_id){
        return response()->json(App\Models\Basic\Amphur::whereNull('state')->where('PROVINCE_ID', $province_id)->pluck('AMPHUR_NAME', 'AMPHUR_ID'));
    });

    Route::get('basic/district/list/{amphur_id}', function ($amphur_id){
        return response()->json(App\Models\Basic\District::whereNull('state')->where('AMPHUR_ID', $amphur_id)->pluck('DISTRICT_NAME', 'DISTRICT_ID'));
    });

    Route::put('basic/province/update-state', 'Basic\ProvinceController@update_state');
    Route::resource('basic/province', 'Basic\\ProvinceController');

    Route::put('basic/geography/update-state', 'Basic\GeographyController@update_state');
    Route::resource('basic/geography', 'Basic\\GeographyController');

    Route::put('basic/amphur/update-state', 'Basic\AmphurController@update_state');
    Route::resource('basic/amphur', 'Basic\\AmphurController');

    Route::put('basic/district/update-state', 'Basic\DistrictController@update_state');
    Route::resource('basic/district', 'Basic\\DistrictController');

    Route::put('basic/staff_group/update-state', 'Basic\StaffGroupController@update_state');
    Route::resource('basic/staff_group', 'Basic\\StaffGroupController');

    Route::get('basic/config/delete-file/{id?}', 'Basic\ConfigController@delete_file');
    Route::get('basic/config/get-file', 'Basic\ConfigController@get_file');
    Route::post('basic/config/upload_file', 'Basic\ConfigController@upload_file');
    Route::resource('basic/config', 'Basic\\ConfigController');

    Route::resource('basic/feewaiver', 'Basic\\FeewaiverController');
    Route::get('basic/feewaiver/remove_file/{payin_id?}/{certify?}', 'Basic\\FeewaiverController@remove_file');


    Route::get('basic/license-list/{tis_no}', function ($tis_no){
        return response()->json(HP::LicenseByTis($tis_no));
    });

    Route::get('basic/license-list-trader/{trader_autonumber}/{tis_no}', function ($trader_autonumber, $tis_no){
        return response()->json(HP::LicenseByTraderTis($trader_autonumber, $tis_no));
    });

    Route::get('basic/license-list-trader2/{trader_autonumber}/{tis_no}', function ($trader_autonumber, $tis_no){
        return response()->json(HP::LicenseByTraderTis2($trader_autonumber, $tis_no));
    });

    Route::put('basic/promote_trader/update-state', 'Basic\PromoteTraderController@update_state');
    Route::resource('basic/promote_trader', 'Basic\\PromoteTraderController');

    Route::put('basic/set_attach/update-state', 'Basic\SetAttachController@update_state');
    Route::resource('basic/set_attach', 'Basic\\SetAttachController');

    Route::put('basic/branch-groups/update-state', 'Basic\BranchGroupController@update_state');
    Route::resource('basic/branch-groups', 'Basic\\BranchGroupController');

    Route::put('basic/branches/update-state', 'Basic\Branch\BranchController@update_state');
    Route::resource('basic/branches', 'Basic\Branch\\BranchController');

    // ตั้งค่าตรวจติดตามใบรับรอง
    Route::get('basic/setting-tracking', 'Basic\\SettingTrackingController@index');
    Route::post('basic/setting-tracking', 'Basic\\SettingTrackingController@store');

    // ดึงค่ากลุ่มงานจาก id กอง เป็น json
    Route::get('basic/sub-department/get_json_by_department/{did?}', 'Basic\\SubDepartmentController@get_json_by_department');

    // ทดสอบส่งอีเมล
    Route::get('basic/mail-test', 'Basic\\MailTestController@index');
    Route::POST('basic/send_mail', 'Basic\\MailTestController@send_mail');

    
    Route::get('basic/holiday/elicense-holiday', 'Basic\HolidayController@elicense_holiday');
    Route::get('basic/holiday/data_google_holiday_list', 'Basic\HolidayController@data_google_holiday_list');
    Route::get('basic/holiday/data_list', 'Basic\HolidayController@data_list');
    Route::put('basic/holiday/update-state', 'Basic\HolidayController@update_state');
    Route::POST('basic/holiday/update-holiday', 'Basic\\HolidayController@update_holiday');
    Route::resource('basic/holiday', 'Basic\\HolidayController');

// ทดสอบส่งอีเมล
Route::get('basic/mail-test', 'Basic\\MailTestController@index');
Route::POST('basic/send_mail', 'Basic\\MailTestController@send_mail');

});
