<?php
Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('rsurv/monitoring', function (){
    return view('rsurv/monitoring/index');
});

Route::get('rsurv/sample_transfer', function (){
    return view('rsurv/sample_transfer/index');
});

Route::get('rsurv/seize', function (){
    return view('rsurv/seize/index');
});

Route::get('rsurv/check_qc', function (){
    return view('rsurv/check_qc/index');
});

Route::get('rsurv/test_result', function (){
    return view('rsurv/test_result/index');
});

Route::get('rsurv/set_test_result', function (){
    return view('rsurv/set_test_result/index');
});

Route::get('rsurv/inspect_qc', function (){
    return view('rsurv/inspect_qc/index');
});

Route::put('rsurv/report_volume/update-state', 'Rsurv\ReportVolumeController@update_state');
Route::resource('rsurv/report_volume', 'Rsurv\ReportVolumeController');

//รายงานแจ้งเปลี่ยนแปลงที่มีผลกระทบต่อคุณภาพ
Route::get('rsurv/report_change/export_excel', 'Rsurv\ReportChangeController@export_excel');
Route::resource('rsurv/report_change', 'Rsurv\\ReportChangeController');

//รายงานแจ้งผลการประเมิน QC
Route::get('rsurv/report_quality_control/export_excel', 'Rsurv\ReportQualityControlController@export_excel');
Route::resource('rsurv/report_quality_control', 'Rsurv\\ReportQualityControlController');

//รายงานแจ้งผลการทดสอบผลิตภัณฑ์
Route::get('rsurv/report_inspection/export_excel', 'Rsurv\ReportInspectionController@export_excel');
Route::resource('rsurv/report_inspection', 'Rsurv\\ReportInspectionController');

//รายงานแจ้งผลการทดสอบผลิตภัณฑ์
Route::get('rsurv/report_calibrate/export_excel', 'Rsurv\ReportCalibrateController@export_excel');
Route::resource('rsurv/report_calibrate', 'Rsurv\\ReportCalibrateController');


});

Route::get('rsurv/export_excel', 'Rsurv\ReportVolumeController@export_excel');

