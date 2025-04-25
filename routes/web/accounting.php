<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;

Route::group(['middleware' => 'auth'],function (){

    //ธนาคาร
    Route::get('/accounting/basic/banks/{id}/destroy', 'Accounting\BanksController@destroy');
    Route::get('/accounting/basic/banks/data_list', 'Accounting\BanksController@data_list');
    Route::POST('/accounting/basic/banks/delete', 'Accounting\BanksController@delete');
    Route::put('/accounting/basic/banks/update-state', 'Accounting\BanksController@update_state');
    Route::resource('/accounting/basic/banks', 'Accounting\\BanksController');

    //ข้อมูลผู้รับใบเสร็จเงิน
    Route::get('/accounting/receipt-info/{id}/destroy', 'Accounting\ReceiptInfoController@destroy');
    Route::get('/accounting/receipt-info/data_list', 'Accounting\ReceiptInfoController@data_list');
    Route::POST('/accounting/receipt-info/delete', 'Accounting\ReceiptInfoController@delete');
    Route::put('/accounting/receipt-info/update-state', 'Accounting\ReceiptInfoController@update_state');
    Route::resource('/accounting/receipt-info', 'Accounting\\ReceiptInfoController');
});