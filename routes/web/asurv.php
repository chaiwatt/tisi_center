<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Models\Asurv\EsurvTers21;
use App\Models\Asurv\EsurvBiss21;
use App\Models\Asurv\EsurvOwns21;
use App\Models\Asurv\EsurvBiss20;
use App\Models\Asurv\EsurvTers20;

Route::group(['middleware' => 'auth'],function (){

/* e-Surveillance */
Route::get('asurv/monitoring', function (){
    return view('asurv/monitoring/index');
});

Route::get('asurv/sample_transfer', function (){
    return view('asurv/sample_transfer/index');
});

Route::get('asurv/seize', function (){
    return view('asurv/seize/index');
});

Route::get('asurv/check_qc', function (){
    return view('asurv/check_qc/index');
});

Route::get('asurv/test_result', function (){
    return view('asurv/test_result/index');
});

Route::get('asurv/set_test_result', function (){
    return view('asurv/set_test_result/index');
});

Route::get('asurv/inspect_qc', function (){
    return view('asurv/inspect_qc/index');
});


Route::get('asurv/function/check_api_pid', function(Request $request)
{
    $table = $request->table;

    if( (new EsurvTers21)->getTable() == $table ){

        $data  =  EsurvTers21::findOrFail($request->id);

    }else if( (new EsurvBiss21)->getTable() == $table ){

        $data  =  EsurvBiss21::findOrFail($request->id);

    }else if( (new EsurvOwns21)->getTable() == $table ){

        $data  =  EsurvOwns21::findOrFail($request->id);

    }else if( (new EsurvBiss20)->getTable() == $table ){

        $data  =  EsurvBiss20::findOrFail($request->id);

    }else if( (new EsurvTers20)->getTable() == $table ){

        $data  =  EsurvTers20::findOrFail($request->id);

    }

    return response()->json([ 'message' =>  HP_API_PID::CheckDataApiPid( $data , $table )  ]);

});

Route::get('/asurv/accept_export/check_api_pid', 'Asurv\AcceptExportController@check_api_pid');
Route::get('/asurv/accept_import/check_api_pid', 'Asurv\AcceptImportController@check_api_pid');
Route::get('/asurv/accept21_export/check_api_pid', 'Asurv\Accept21ExportController@check_api_pid');
Route::get('/asurv/accept21_import/check_api_pid', 'Asurv\Accept21ImportController@check_api_pid');
Route::get('/asurv/accept21own_import/check_api_pid', 'Asurv\Accept21ownImportController@check_api_pid');



Route::get('/asurv/search-tis', 'Asurv\AcceptExportController@GetTisLike');
Route::put('asurv/accept_export/update-state', 'Asurv\AcceptExportController@update_state');
Route::resource('asurv/accept_export', 'Asurv\\AcceptExportController');

Route::put('asurv/accept_import/update-state', 'Asurv\AcceptImportController@update_state');
Route::resource('asurv/accept_import', 'Asurv\\AcceptImportController');

Route::put('asurv/report_export/update-state', 'Asurv\ReportExportController@update_state');
Route::resource('asurv/report_export', 'Asurv\\ReportExportController');

Route::put('asurv/report_import/update-state', 'Asurv\ReportImportController@update_state');
Route::resource('asurv/report_import', 'Asurv\\ReportImportController');

Route::post('/asurv/accept_export/save', 'Asurv\AcceptExportController@save_data');
Route::get('/asurv/accept_export/download/{NAME}', 'Asurv\AcceptExportController@download_file');
Route::get('/asurv/accept_export/detail/{ID}', 'Asurv\AcceptExportController@detail');
Route::get('/asurv/accept_export/pdf_download/{id}', 'Asurv\AcceptExportController@pdf_download');
Route::get('/asurv/accept_export/update_status/{ID}/{STATE}', 'Asurv\AcceptExportController@update_status');


Route::post('/asurv/accept_import/save', 'Asurv\AcceptImportController@save_data');
Route::get('/asurv/accept_import/download/{NAME}', 'Asurv\AcceptImportController@download_file');
Route::get('/asurv/accept_import/detail/{ID}', 'Asurv\AcceptImportController@detail');
Route::get('/asurv/accept_import/update_status/{ID}/{STATE}', 'Asurv\AcceptImportController@update_status');

Route::post('/asurv/report_export/save', 'Asurv\ReportExportController@save_data');
Route::get('/asurv/report_export/get_signer_position/{signer_id}', 'Asurv\ReportExportController@get_signer_position');
Route::get('/asurv/report_export/download/{NAME}', 'Asurv\ReportExportController@download_file');
Route::get('/asurv/report_export/preview/{NAME}', 'Asurv\ReportExportController@preview_file');

Route::post('/asurv/report_import/save', 'Asurv\ReportImportController@save_data');
Route::get('/asurv/report_import/download/{NAME}', 'Asurv\ReportImportController@download_file');
Route::get('/asurv/report_import/preview/{NAME}', 'Asurv\ReportImportController@preview_file');

Route::put('asurv/accept21_export/update-state', 'Asurv\Accept21ExportController@update_state');
Route::resource('asurv/accept21_export', 'Asurv\\Accept21ExportController');

Route::put('asurv/accept21_import/update-state', 'Asurv\Accept21ImportController@update_state');
Route::resource('asurv/accept21_import', 'Asurv\\Accept21ImportController');

Route::put('asurv/accept21own_import/update-state', 'Asurv\Accept21ownImportController@update_state');
Route::resource('asurv/accept21own_import', 'Asurv\\Accept21ownImportController');

Route::put('asurv/report21_export/update-state', 'Asurv\Report21ExportController@update_state');
Route::resource('asurv/report21_export', 'Asurv\\Report21ExportController');

Route::put('asurv/report21_import/update-state', 'Asurv\Report21ImportController@update_state');
Route::resource('asurv/report21_import', 'Asurv\\Report21ImportController');

Route::post('/asurv/report21own_import/save', 'Asurv\Report21ownImportController@save_data');
Route::put('asurv/report21own_import/update-state', 'Asurv\Report21ownImportController@update_state');
Route::resource('asurv/report21own_import', 'Asurv\\Report21ownImportController');

Route::post('/asurv/accept21_export/save', 'Asurv\Accept21ExportController@save_data');
Route::get('/asurv/accept21_export/download/{NAME}', 'Asurv\Accept21ExportController@download_file');
Route::get('/asurv/accept21_export/detail/{ID}', 'Asurv\Accept21ExportController@detail');
Route::get('/asurv/accept21_export/pdf_download/{id}', 'Asurv\Accept21ExportController@pdf_download');
Route::get('/asurv/accept21_export/update_status/{ID}/{STATE}', 'Asurv\Accept21ExportController@update_status');

Route::post('/asurv/accept21_import/save', 'Asurv\Accept21ImportController@save_data');
Route::get('/asurv/accept21_import/download/{NAME}', 'Asurv\Accept21ImportController@download_file');
Route::get('/asurv/accept21_import/detail/{ID}', 'Asurv\Accept21ImportController@detail');
Route::get('/asurv/accept21_import/update_status/{ID}/{STATE}', 'Asurv\Accept21ImportController@update_status');

Route::post('/asurv/accept21own_import/save', 'Asurv\Accept21ownImportController@save_data');
Route::get('/asurv/accept21own_import/download/{NAME}', 'Asurv\Accept21ownImportController@download_file');
Route::get('/asurv/accept21own_import/detail/{ID}', 'Asurv\Accept21ownImportController@detail');
Route::get('/asurv/accept21own_import/update_status/{ID}/{STATE}', 'Asurv\Accept21ownImportController@update_status');

Route::post('/asurv/report21_export/save', 'Asurv\Report21ExportController@save_data');
Route::get('/asurv/report21_export/download/{NAME}', 'Asurv\Report21ExportController@download_file');
Route::get('/asurv/report21_export/preview/{NAME}', 'Asurv\Report21ExportController@preview_file');

Route::post('/asurv/report21_import/save', 'Asurv\Report21ImportController@save_data');
Route::get('/asurv/report21_import/download/{NAME}', 'Asurv\Report21ImportController@download_file');
Route::get('/asurv/report21_import/preview/{NAME}', 'Asurv\Report21ImportController@preview_file');

Route::resource('asurv/receive_applicant_21bis', 'Asurv\\ReceiveApplicant21bisController');

});
