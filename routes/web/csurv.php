<?php
Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('csurv/monitoring', function (){
    return view('csurv/monitoring/index');
});

Route::get('csurv/sample_transfer', function (){
    return view('csurv/sample_transfer/index');
});

Route::get('csurv/seize', function (){
    return view('csurv/seize/index');
});

Route::get('csurv/check_qc', function (){
    return view('csurv/check_qc/index');
});

Route::get('csurv/test_result', function (){
    return view('csurv/test_result/index');
});

Route::get('csurv/set_test_result', function (){
    return view('csurv/set_test_result/index');
});

Route::get('csurv/inspect_qc', function (){
    return view('csurv/inspect_qc/index');
});

Route::put('csurv/control_check/update-state', 'Csurv\ControlCheckController@update_state');
Route::get('/csurv/control_check/add_filter_reference_num', 'Csurv\ControlCheckController@add_filter_reference_num');
Route::get('/csurv/control_check/delete_status', 'Csurv\ControlCheckController@delete_status');
Route::resource('csurv/control_check', 'Csurv\\ControlCheckController');

Route::put('csurv/control_follow/update-state', 'Csurv\ControlFollowController@update_state');
Route::resource('csurv/control_follow', 'Csurv\\ControlFollowController');

Route::put('csurv/control_freeze/update-state', 'Csurv\ControlFreezeController@update_state');
Route::get('/csurv/control_freeze/delete_status', 'Csurv\ControlFreezeController@delete_status');
Route::resource('csurv/control_freeze', 'Csurv\\ControlFreezeController');

Route::put('csurv/control_performance/update-state', 'Csurv\ControlPerformanceController@update_state');
Route::get('/csurv/control_performance/add_filter_License', 'Csurv\ControlPerformanceController@add_filter_License');
Route::get('/csurv/control_performance/add_license', 'Csurv\ControlPerformanceController@add_license');
Route::get('/csurv/control_performance/add_filter_address_province', 'Csurv\ControlPerformanceController@add_filter_address_province');
Route::get('/csurv/control_performance/add_filter_address_district', 'Csurv\ControlPerformanceController@add_filter_address_district');
Route::resource('csurv/control_performance', 'Csurv\\ControlPerformanceController');

Route::post('/csurv/control_performance/save', 'Csurv\ControlPerformanceController@save_data');
Route::post('/csurv/control_performance/update', 'Csurv\ControlPerformanceController@update_data');
Route::post('/csurv/control_performance/update_status', 'Csurv\ControlPerformanceController@update_status');
Route::get('/csurv/control_performance/detail/{ID}', 'Csurv\ControlPerformanceController@detail');
Route::post('/csurv/control_performance/delete_status', 'Csurv\ControlPerformanceController@delete_status');
Route::post('/csurv/control_performance/delete_status_all', 'Csurv\ControlPerformanceController@delete_status_all');
Route::get('/csurv/control_performance/download/{NAME}', 'Csurv\ControlPerformanceController@download_file');
Route::post('csurv/control_performance/delete', 'Csurv\ControlPerformanceController@delete');

Route::post('/csurv/control_check/save', 'Csurv\ControlCheckController@save_data');
Route::post('/csurv/control_check/update', 'Csurv\ControlCheckController@update_data');
Route::post('/csurv/control_check/update_status', 'Csurv\ControlCheckController@update_status');
Route::get('/csurv/control_check/detail/{ID}', 'Csurv\ControlCheckController@detail');
Route::post('/csurv/control_check/delete_status', 'Csurv\ControlCheckController@delete_status');
Route::get('/csurv/control_check/download/{NAME}', 'Csurv\ControlCheckController@download_file');
Route::post('csurv/control_check/delete', 'Csurv\ControlCheckController@delete');

Route::post('/csurv/control_follow/save', 'Csurv\ControlFollowController@save_data');
Route::post('/csurv/control_follow/update', 'Csurv\ControlFollowController@update_data');
Route::get('/csurv/control_follow/excel/{id}', 'Csurv\ControlFollowController@export_excel');
Route::get('/csurv/control_follow/del/{id}', 'Csurv\ControlFollowController@del_data');

Route::post('/csurv/control_freeze/save', 'Csurv\ControlFreezeController@save_data');
Route::post('/csurv/control_freeze/update', 'Csurv\ControlFreezeController@update_data');
Route::get('/csurv/control_freeze/detail/{ID}', 'Csurv\ControlFreezeController@detail');
Route::get('/csurv/control_freeze/add_filter_address_province/{ID}', 'Csurv\ControlFreezeController@add_filter_address_province');
Route::get('/csurv/control_freeze/add_filter_address_district/{ID}', 'Csurv\ControlFreezeController@add_filter_address_district');

});
