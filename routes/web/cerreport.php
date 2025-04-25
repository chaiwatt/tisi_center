<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;

// รายงาน e-Payment
Route::get('cerreport/epayments/export_excel', 'Cerreport\EpaymentsController@export_excel');
Route::get('cerreport/epayments/data_list', 'Cerreport\EpaymentsController@data_list');
Route::resource('cerreport/epayments', 'Cerreport\\EpaymentsController');
// รายงาน Pay In
Route::get('cerreport/payins/export_excel', 'Cerreport\PayInController@export_excel');
Route::get('cerreport/payins/data_list', 'Cerreport\PayInController@data_list');
Route::get('cerreport/payins', 'Cerreport\PayInController@index'); 
Route::get('cerreport/payins/{id?}', 'Cerreport\PayInController@show');
// รายงานติดตามใบรับรอง
Route::get('cerreport/certificates/export_excel', 'Cerreport\CertificateController@export_excel');
Route::get('cerreport/certificates/data_list', 'Cerreport\CertificateController@data_list');
Route::get('cerreport/certificates', 'Cerreport\CertificateController@index'); 
Route::get('cerreport/certificates/{id?}', 'Cerreport\CertificateController@show');
// สถิติการออกใบรับรอง
Route::get('cerreport/certificate_export', 'Cerreport\CertificateExportController@index'); 
// รายงานคำขอตาม มอก.
Route::get('cerreport/certified-formula', 'Cerreport\CertifiedFormulaController@index'); 
// รายงานประวัติการส่งอีเมล
Route::get('cerreport/certify-applicant/data_list', 'Cerreport\CertifyApplicant@data_list');
Route::get('cerreport/certify-applicant/{id}', 'Cerreport\CertifyApplicant@show');
Route::get('cerreport/certify-applicant', 'Cerreport\CertifyApplicant@index'); 

// รายงานสถานะการลงนามใบอนุญาตอิเล็กทรอนิกส์
Route::get('cerreport/logesignaures/preview', 'Cerreport\LogesignauresController@view_pdf');
Route::get('cerreport/logesignaures/get-address', 'Cerreport\LogesignauresController@GetAddress');
Route::get('cerreport/logesignaures/datas_cer', 'Cerreport\LogesignauresController@datas_cer');
Route::get('cerreport/logesignaures/data_list', 'Cerreport\LogesignauresController@data_list');
Route::resource('cerreport/logesignaures', 'Cerreport\\LogesignauresController');

// รายงานข้อมูลใบรับรองระบบงาน
Route::get('cerreport/system-certification/preview', 'Cerreport\SystemCertificationController@view_pdf');
Route::get('cerreport/system-certification/get-address', 'Cerreport\SystemCertificationController@GetAddress');
Route::get('cerreport/system-certification/datas_cer', 'Cerreport\SystemCertificationController@datas_cer');
Route::get('cerreport/system-certification/data_list', 'Cerreport\SystemCertificationController@data_list');
Route::get('cerreport/system-certification/{id?}/{certificate_type?}/edit', 'Cerreport\SystemCertificationController@edit');
Route::get('cerreport/system-certification/{id}/{certificate_type}', 'Cerreport\SystemCertificationController@show');
Route::PATCH('cerreport/system-certification/save-modal/{id?}', 'Cerreport\SystemCertificationController@save_contract');
Route::resource('cerreport/system-certification', 'Cerreport\\SystemCertificationController');
