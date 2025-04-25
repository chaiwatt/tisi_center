<?php
Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('ssurv/monitoring', function (){
    return view('ssurv/monitoring/index');
});

Route::get('ssurv/sample_transfer', function (){
    return view('ssurv/sample_transfer/index');
});

Route::get('ssurv/seize', function (){
    return view('ssurv/seize/index');
});

Route::get('ssurv/check_qc', function (){
    return view('ssurv/check_qc/index');
});

Route::get('ssurv/test_result', function (){
    return view('ssurv/test_result/index');
});

Route::get('ssurv/set_test_result', function (){
    return view('ssurv/set_test_result/index');
});

Route::get('ssurv/inspect_qc', function (){
    return view('ssurv/inspect_qc/index');
});

Route::get('/ssurv/save_example/get_filter_tb4_License/list/{id?}', 'Ssurv\SaveExampleController@GetFilterTb4License');
Route::get('/ssurv/save_example/add_sub_department', 'Ssurv\SaveExampleController@add_sub_department');
Route::put('ssurv/save_example/update-state', 'Ssurv\SaveExampleController@update_state');
Route::get('/ssurv/save_example/get_filter_tb4_License', 'Ssurv\SaveExampleController@get_filter_tb4_License');
Route::get('/ssurv/save_example/get_filter_tb4_License_no', 'Ssurv\SaveExampleController@get_filter_tb4_License_no');
Route::get('/ssurv/save_example/get_item_detail', 'Ssurv\SaveExampleController@get_item_detail');
Route::get('/ssurv/save_example/get_detail_maplap', 'Ssurv\SaveExampleController@get_detail_maplap');
Route::get('/ssurv/save_example/get_result', 'Ssurv\SaveExampleController@get_result');
Route::get('/ssurv/save_example/get_head', 'Ssurv\SaveExampleController@get_head');
Route::get('/ssurv/save_example/get_type2', 'Ssurv\SaveExampleController@get_type2');
Route::get('/ssurv/save_example/get_lab_test_items', 'Ssurv\SaveExampleController@get_lab_test_items');
Route::resource('ssurv/save_example', 'Ssurv\\SaveExampleController');
Route::post('/ssurv/save_example/save', 'Ssurv\SaveExampleController@save_data');
Route::post('/ssurv/save_example/check', 'Ssurv\SaveExampleController@check_result');
Route::post('/ssurv/save_example/delete', 'Ssurv\SaveExampleController@delete_example');
Route::post('/ssurv/save_example/delete_select', 'Ssurv\SaveExampleController@delete_select');
Route::post('/ssurv/save_example/update', 'Ssurv\SaveExampleController@update');
Route::post('/ssurv/save_example/delete_detail', 'Ssurv\SaveExampleController@delete_detail');
Route::post('/ssurv/save_example/save_attach', 'Ssurv\SaveExampleController@save_attach');
Route::get('/ssurv/save_example/detail/{Example_ID}', 'Ssurv\SaveExampleController@detail');
Route::get('/ssurv/save_example/print/{example_id}', 'Ssurv\SaveExampleController@print');
Route::get('/ssurv/save_example/export_word/{example_id}', 'Ssurv\SaveExampleController@export_word');

});
