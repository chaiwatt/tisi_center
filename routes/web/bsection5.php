<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;



Route::prefix('bsection5')->group(function () {

    Route::put('/test_method/update-state', 'Bsection5\TestMethodController@update_state');
    Route::resource('/test_method', 'Bsection5\TestMethodController');

    Route::POST('/test_tools/delete', 'Bsection5\\TestToolsController@delete');
    Route::put('/test_tools/update-state', 'Bsection5\TestToolsController@update_state');
    Route::get('/test_tools/data_list', 'Bsection5\\TestToolsController@data_list');
    Route::resource('/test_tools', 'Bsection5\\TestToolsController');

    Route::put('/basic/unit/update-state', 'Bsection5\UnitController@update_state');
    Route::resource('/basic/unit', 'Bsection5\\UnitController');


    Route::get('/test_item/delete_std_test_item/{id}', 'Bsection5\\TestItemController@delete_std_test_item');
    Route::get('/test_item/get-testitem-data/{id}', 'Bsection5\\TestItemController@GetTestItemData');
    Route::POST('/test_item/save_std_test_item', 'Bsection5\\TestItemController@SaveStdTestItem');
    Route::get('/test_item/std-data-item', 'Bsection5\\TestItemController@StdFormDataItem');
    Route::POST('/test_item/delete', 'Bsection5\\TestItemController@delete');
    Route::POST('/test_item/update_publish', 'Bsection5\\TestItemController@update_publish');
    Route::POST('/test_item/update_status', 'Bsection5\\TestItemController@update_status');
    Route::get('/test_item/data_list', 'Bsection5\\TestItemController@data_list');
    Route::get('/test_item/get-data-item', 'Bsection5\\TestItemController@GetDataTestItem');
    Route::get('/test_item/main/get-data-item', 'Bsection5\\TestItemController@GetTestItemTypeMain');
    Route::get('/test_item/example_input', 'Bsection5\\TestItemController@example_input');
    Route::POST('/test_item/example_input', 'Bsection5\\TestItemController@example_input_submit');
    Route::resource('/test_item', 'Bsection5\\TestItemController');

    //กลุ่มงาน LAB
    Route::get('/workgroup/data_user_register', 'Bsection5\WorkgroupController@data_user_register');
    Route::get('/workgroup/data_tis_standards', 'Bsection5\WorkgroupController@data_tis_standards');
    Route::get('/workgroup/data_list', 'Bsection5\WorkgroupController@data_list');
    Route::POST('/workgroup/delete', 'Bsection5\WorkgroupController@delete');
    Route::POST('/workgroup/update_publish', 'Bsection5\WorkgroupController@update_publish');
    Route::POST('/workgroup/update_status', 'Bsection5\WorkgroupController@update_status');
    Route::resource('/workgroup', 'Bsection5\\WorkgroupController');

    //กลุ่มงาน IB
    Route::get('/workgroup-ib/data_user_register', 'Bsection5\WorkGroupIBController@data_user_register');
    Route::get('/workgroup-ib/data_branch', 'Bsection5\WorkGroupIBController@data_branch');
    Route::get('/workgroup-ib/data_list', 'Bsection5\WorkGroupIBController@data_list');
    Route::POST('/workgroup-ib/delete', 'Bsection5\WorkGroupIBController@delete');
    Route::POST('/workgroup-ib/update_publish', 'Bsection5\WorkGroupIBController@update_publish');
    Route::POST('/workgroup-ib/update_status', 'Bsection5\WorkGroupIBController@update_status');
    Route::resource('/workgroup-ib', 'Bsection5\\WorkGroupIBController');

    // มาตรฐานรับรองระบบงาน
    Route::put('/standards/update-state', 'Bsection5\StandardController@update_state');
    Route::resource('/standards', 'Bsection5\\StandardController');

});
