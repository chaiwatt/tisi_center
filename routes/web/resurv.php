<?php
Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('resurv/monitoring', function (){
    return view('resurv/monitoring/index');
});

Route::get('resurv/sample_transfer', function (){
    return view('resurv/sample_transfer/index');
});

Route::get('resurv/seize', function (){
    return view('resurv/seize/index');
});

Route::get('resurv/check_qc', function (){
    return view('resurv/check_qc/index');
});

Route::get('resurv/test_result', function (){
    return view('resurv/test_result/index');
});

Route::get('resurv/set_test_result', function (){
    return view('resurv/set_test_result/index');
});

Route::get('resurv/inspect_qc', function (){
    return view('resurv/inspect_qc/index');
});

Route::put('resurv/assign_product/update-state', 'REsurv\AssignProductController@update_state');
Route::resource('resurv/assign_product', 'REsurv\\AssignProductController');

Route::put('resurv/report_product/update-state', 'REsurv\ReportProductController@update_state');
Route::resource('resurv/report_product', 'REsurv\\ReportProductController');

Route::put('resurv/results_product/update-state', 'REsurv\ResultsProductController@update_state');
Route::resource('resurv/results_product', 'REsurv\\ResultsProductController');

Route::put('resurv/test_product/update-state', 'REsurv\TestProductController@update_state');
Route::resource('resurv/test_product', 'REsurv\\TestProductController');

Route::post('/resurv/results_product/save', 'REsurv\ResultsProductController@save_data');
Route::post('/resurv/results_product/delete', 'REsurv\ResultsProductController@delete');
Route::post('/resurv/results_product/update_status_on', 'REsurv\ResultsProductController@update_status_on');
Route::post('/resurv/results_product/update_status_off', 'REsurv\ResultsProductController@update_status_off');
Route::post('/resurv/results_product/update', 'REsurv\ResultsProductController@update');
Route::post('/resurv/results_product/delete_detail', 'REsurv\ResultsProductController@delete_detail');

Route::post('/resurv/report_product/update', 'REsurv\ReportProductController@update');
Route::get('/resurv/report_product/detail/{ID}', 'REsurv\ReportProductController@detail');
Route::get('/resurv/report_product/download/{NAME}', 'REsurv\ReportProductController@download_file');

Route::post('/resurv/assign_product/update_reg_cb', 'REsurv\AssignProductController@update_reg_cb');
Route::post('/resurv/assign_product/update_reg', 'REsurv\AssignProductController@update_reg');

Route::post('/resurv/test_product/update', 'REsurv\TestProductController@update');

Route::get('/resurv/assign_product/form-result/{id?}', 'REsurv\AssignProductController@load_form_result');
Route::get('/resurv/assign_product/detail/{ID}/{ID_MAIN}', 'REsurv\AssignProductController@detail');
Route::get('/resurv/test_product/detail/{ID}/{ID_MAIN}', 'REsurv\TestProductController@detail');
Route::get('resurv/assign_product/download/{filename}', function($filename)
{

    $storagePath  = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

    $filePath = $storagePath.'esurv_attach/report_product/'.$filename;

    if (!File::exists($filePath))
    {
        return Response::make($filePath, 404);
    }

    $fileContents = File::get($filePath);
    $type = File::mimeType($filePath);

    if ($type == 'application/pdf'){
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf'
        ]);
    }
    else {
        return Response::make($fileContents, 200)->header("Content-Type", 'blob');

    }
})->name('show.file');

Route::get('resurv/test_product/download/{filename}', function($filename)
{

    $storagePath  = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

    $filePath = $storagePath.'esurv_attach/report_product/'.$filename;

    if (!File::exists($filePath))
    {
        return Response::make($filePath, 404);
    }

    $fileContents = File::get($filePath);
    $type = File::mimeType($filePath);

    if ($type == 'application/pdf'){
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf'
        ]);
    }
    else {
        return Response::make($fileContents, 200)->header("Content-Type", 'blob');

    }
})->name('show.file');


   });

