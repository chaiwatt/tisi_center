<?php

Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('esurv/monitoring', function (){
    return view('esurv/monitoring/index');
});

Route::get('esurv/sample_transfer', function (){
    return view('esurv/sample_transfer/index');
});

Route::get('esurv/seize', function (){
    return view('esurv/seize/index');
});

Route::get('esurv/check_qc', function (){
    return view('esurv/check_qc/index');
});

Route::get('esurv/test_result', function (){
    return view('esurv/test_result/index');
});

Route::get('esurv/set_test_result', function (){
    return view('esurv/set_test_result/index');
});

Route::get('esurv/inspect_qc', function (){
    return view('esurv/inspect_qc/index');
});

Route::get('esurv', function (){
    return view('admin/esurv');
});

Route::get('/esurv/receive_volume/add_subdepartment', 'Esurv\ReceiveVolumeController@add_subdepartment');
Route::get('/esurv/receive_volume/report', 'Esurv\ReceiveVolumeController@report');
Route::put('esurv/receive_volume/update-state', 'Esurv\ReceiveVolumeController@update_state');
Route::resource('esurv/receive_volume', 'Esurv\\ReceiveVolumeController');

Route::get('/esurv/receive_change/report', 'Esurv\ReceiveChangeController@report');
Route::put('esurv/receive_change/update-state', 'Esurv\ReceiveChangeController@update_state');
Route::resource('esurv/receive_change', 'Esurv\\ReceiveChangeController');

Route::get('/esurv/receive_quality_control/report', 'Esurv\ReceiveQualityControlController@report');
Route::put('esurv/receive_quality_control/update-state', 'Esurv\ReceiveQualityControlController@update_state');
Route::resource('esurv/receive_quality_control', 'Esurv\\ReceiveQualityControlController');

Route::get('/esurv/receive_inspection/report', 'Esurv\ReceiveInspectionController@report');
Route::put('esurv/receive_inspection/update-state', 'Esurv\ReceiveInspectionController@update_state');
Route::resource('esurv/receive_inspection', 'Esurv\\ReceiveInspectionController');

Route::get('/esurv/receive_calibrate/report', 'Esurv\ReceiveCalibrateController@report');
Route::put('esurv/receive_calibrate/update-state', 'Esurv\ReceiveCalibrateController@update_state');
Route::resource('esurv/receive_calibrate', 'Esurv\\ReceiveCalibrateController');

Route::get('/esurv/other/report', 'Esurv\OtherController@report');
Route::put('esurv/other/update-state', 'Esurv\OtherController@update_state');
Route::resource('esurv/other', 'Esurv\\OtherController');

Route::put('esurv/license_cancel/update-state', 'Esurv\LicenseCancelController@update_state');
Route::resource('esurv/license_cancel', 'Esurv\\LicenseCancelController');

Route::put('esurv/follow_up/update-state', 'Esurv\FollowUpController@update_state');
Route::get('/esurv/follow_up/add_filter_license', 'Esurv\FollowUpController@add_filter_license');
Route::get('/esurv/follow_up/add_factory_address_province', 'Esurv\FollowUpController@add_factory_address_province');
Route::get('/esurv/follow_up/add_factory_address_tambon', 'Esurv\FollowUpController@add_factory_address_tambon');
Route::get('/esurv/follow_up/add_warehouse_address_province', 'Esurv\FollowUpController@add_warehouse_address_province');
Route::get('/esurv/follow_up/add_warehouse_address_tambon', 'Esurv\FollowUpController@add_warehouse_address_tambon');
Route::get('/esurv/follow_up/get_trader_autono', 'Esurv\FollowUpController@get_trader_autono');
Route::get('/esurv/follow_up/data_sub_department/{id?}', 'Esurv\FollowUpController@data_sub_department');
Route::resource('esurv/follow_up', 'Esurv\\FollowUpController');
Route::post('esurv/follow_up/list/delete', 'Esurv\FollowUpController@delete');

Route::resource('esurv/tisi_license_notification', 'Esurv\\TisiLicenseNotificationController');

});
