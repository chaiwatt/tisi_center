<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('rights','API\RightController@rights');

//Authen จากตาราง web_service
Route::group([  'prefix' => 'v1' ], function () {

    //API ดึงข้อมูลใบอนุญาต ด้วยเลขที่ใบอนุญาต
    Route::post('elicense_no', 'API\ElicenseCerticate@elicense_no');

    //API เชื่อมโยงข้อมูลใบอนุญาตด้วยเลขที่ มอก.
    Route::post('tis_number', 'API\ElicenseCerticate@tis_number');

    //API เชื่อมโยงข้อมูลใบอนุญาตด้วยเลขนิติบุคคล
    Route::post('tax_number', 'API\ElicenseCerticate@tax_number');

    //API เชื่อมโยงข้อมูลใบอนุญาตด้วยประเภทใบอนุญาต
    Route::post('license_type', 'API\ElicenseCerticate@license_type');

    //API เชื่อมโยงข้อมูลโรงงานที่ให้บริการ.
    Route::post('manufacturer_foreigns', 'API\ElicenseCerticate@manufacturer_foreigns');

    //API เชื่อมโยงข้อมูล มอก.
    Route::post('tis_standards', 'API\StandardController@tis_standards');

    // API   ตรวจติดตามใบรับรอง
    Route::get('update_status_export/{date?}', 'API\CertificateController@update_status_export');
 
    // API insert pay in
    Route::get('insert_payin/{type?}', 'API\CertificateController@insert_payin_all');

    // ข้อมูลแสดงใบรับรอง pdf cb แล้ว ib
    Route::get('certificate', 'API\CertificateController@index');

    //API ดึงข้อมูลมาตรฐานสก.
    Route::post('estandard', 'API\StandardController@estandard');

    // API insert pay in
    Route::get('update_status_certify_lab', 'API\CertifyController@update_status_certify_lab');

    // API insert transaction pay in
    Route::get('insert_transaction_payin', 'API\CertifyController@insert_transaction_payin');

    Route::get('reward/receipts/{id}', 'API\ReceiptsController@index');
    Route::post('reward/receipts/update', 'API\ReceiptsController@update');
    
    // API insert transaction pay in
    Route::get('mail_listen_ministry', 'API\SendMailListenMinisTry@run_send_mail');

});
