<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::group(['middleware' => 'auth'],function (){
 
    Route::put('tis/appoint/update-state', 'Tis\AppointController@update_state');
    Route::resource('tis/appoint', 'Tis\\AppointController');
    Route::get('tis/set_standard/data_list', 'Tis\SetStandardController@data_list');
    Route::get('api/tis/set_standard/standard',  'Tis\\SetStandardController@apiGetStandards');
    Route::get('api/tis/set_standard/standards', 'Tis\\SetStandardController@apiGet_Standards');
    Route::get('api/tis/set_standard/standard-first/{id?}', 'Tis\\SetStandardController@apiFirst_Standards');
    Route::get('tis/set_standard/update-plans/{id?}', 'Tis\SetStandardController@apiStorePlans');
    Route::get('tis/set_standard/set_standard_plans', 'Tis\SetStandardController@apiDataStorePlans');
    Route::get('tis/set_standard/delete_set_standard_plans', 'Tis\SetStandardController@apiDeleteStorePlans');
    Route::get('tis/set_standard/destroy/{id?}', 'Tis\SetStandardController@destroy');
    Route::post('tis/set_standard/update_status', 'Tis\SetStandardController@update_status');
    Route::POST('tis/set_standard/update_publish', 'Tis\SetStandardController@update_publish');
    Route::get('tis/set_standard/filter_method_detail/{id?}', 'Tis\SetStandardController@filter_method_detail');

 
    Route::get('tis/set_standard/update-results/{id?}',  'Tis\\SetStandardController@apiStoreResults');
    Route::get('tis/set_standard/set_standard_result', 'Tis\SetStandardController@apiDataStoreResults');
    Route::get('tis/set_standard/delete_set_standard_result', 'Tis\SetStandardController@apiDeleteStoreResults');

    Route::put('tis/set_standard/update-state', 'Tis\SetStandardController@update_state');
    // Route::put('tis/set_standard/update-plan/{id}', 'Tis\SetStandardController@storePlan');
    Route::get('tis/set_standard/update-plan/{id?}', 'Tis\SetStandardController@apiStorePlan');
    Route::post('tis/set_standard/update-result/{id?}', 'Tis\SetStandardController@apiStoreResult');
    Route::delete('tis/set_standard/multiple', 'Tis\\SetStandardController@destroyMultiple');
    Route::get('tis/set_standard/get_secretary', 'Tis\\SetStandardController@get_secretary');
    Route::post('tis/set_standard/standard_announcement', 'Tis\\SetStandardController@standard_announcement');
    Route::post('tis/set_standard/cancel_announcement', 'Tis\\SetStandardController@cancel_announcement');
    Route::resource('tis/set_standard', 'Tis\\SetStandardController');
    Route::get('api/tis/set_standard/{id?}', 'Tis\\SetStandardController@apiGetSetStandard');
    Route::get('api/tis/years', 'Tis\\SetStandardController@apiGetYears');
    Route::get('api/tis/status_operations', 'Tis\\SetStandardController@apiGetStatusOperations');
    Route::get('api/tis/appoint_names', 'Tis\\SetStandardController@apiGetAppointNames');
    Route::get('api/tis/plans/{id?}', 'Tis\\SetStandardController@apiGetPlans');
    Route::get('api/tis/result/{id?}', 'Tis\\SetStandardController@apiGetStoreResult');
    Route::get('api/tis/results/{id?}', 'Tis\\SetStandardController@apiGetResults');


    Route::get('api/tis/set_standard_plan/{id}', 'Tis\\SetStandardController@apiGetSetStandardPlan');
    Route::get('api/tis/set_standard_result/{id}', 'Tis\\SetStandardController@apiGetSetStandardResult');
    Route::get('api/tis/get_method_detail/{method_id}', 'Tis\\SetStandardController@get_method_detail');
    Route::get('tis/set_standard_plan/{id}/edit', 'Tis\\SetStandardController@editPlan')->name('editplansetstandard');
    Route::post('tis/set_standard_plan/update', 'Tis\\SetStandardController@updatePlan');
    Route::delete('tis/set_standard_plan/{id}/delete', 'Tis\\SetStandardController@destroyPlan');
    // Route::delete('tis/set_standard_result/{id}/delete', 'Tis\\SetStandardController@destroyResult');
    Route::delete('api/tis/set_standard_plan/{id?}/delete', 'Tis\\SetStandardController@apiDestroyPlan');
    Route::delete('api/tis/set_standard_result/{id?}/delete', 'Tis\\SetStandardController@apiDestroyResult');

    Route::get('/tis/standard/data_list', 'Tis\StandardController@data_list');
    Route::get('/tis/standard/add_method_detail', 'Tis\StandardController@add_method_detail');
    Route::get('tis/standard/export_excel', 'Tis\StandardController@export_excel');
    Route::put('tis/standard/update-state', 'Tis\StandardController@update_state');
    Route::resource('tis/standard', 'Tis\\StandardController');
    Route::get('api/tis/standards', 'Tis\\StandardController@apiGetStandards');
    Route::get('create-zip', 'Tis\StandardController@index')->name('create-zip');
    Route::get('/tis/standard/download-filezip/{id?}', 'Tis\\StandardController@downloadfileZip');


    Route::put('tis/board/update-state', 'Tis\BoardController@update_state');
    Route::POST('tis/board/save_board', 'Tis\BoardController@save_board');
    Route::resource('tis/board', 'Tis\\BoardController');

    Route::put('tis/public-draft/update/status', 'Tis\PublicDraftController@update_status');
    Route::resource('tis/public_draft', 'Tis\\PublicDraftController');
    Route::get('api/tis/public_drafts', 'Tis\\PublicDraftController@apiGetStandards');
    Route::post('tis/public_draft/api/getFormat.api','Tis\PublicDraftController@getFormatApi');
    Route::post('tis/public_draft/api/getNumberFormula.api','Tis\PublicDraftController@getNumberFormula');
    Route::post('tis/public_draft/api/getStandardName_branch.api','Tis\PublicDraftController@standardName_branch');
    Route::get('tis/delete/file/{path}/{token}','Tis\PublicDraftController@removeFilesWithMessage');



    Route::put('tis/report_comment/update-state', 'Tis\ReportCommentController@update_state');
    Route::resource('tis/report_comment', 'Tis\\ReportCommentController');

    Route::put('tis/import_comment/update-state', 'Tis\ImportCommentController@update_state');
    Route::get('tis/import_comment/upload/{id}', 'Tis\ImportCommentController@UploadExcel');
    Route::get('tis/import_comment/insert_data/{id}', 'Tis\ImportCommentController@InsertDataExcel');
    Route::get('tis/import_comment/result-import/{id}', 'Tis\ImportCommentController@showResultImport');
    Route::resource('tis/import_comment', 'Tis\\ImportCommentController');

    //รายงานข้อมูลมาตรฐานที่เปิดใช้ในปัจจุบัน
    Route::get('tis/standard_report/export_excel', 'Tis\StandardReportController@export_excel');
    Route::resource('tis/standard_report', 'Tis\\StandardReportController');


    Route::put('tis/comment_standard_drafts/update-state', 'Tis\CommentStandardDraftsController@update_state');
    Route::resource('tis/comment_standard_drafts', 'Tis\CommentStandardDraftsController');


    Route::put('tis/comment_standard_reviews/update-state', 'Tis\CommentStandardReviewsController@update_state');
    Route::get('tis/comment_standard_reviews/show/{id}', 'Tis\CommentStandardReviewsController@show');
    Route::resource('tis/comment_standard_reviews', 'Tis\CommentStandardReviewsController');

    Route::put('tis/listen_std_draft/update-state', 'Tis\ListenStdDraftController@update_state');
    Route::get('tis/listen_std_draft/show/{id}', 'Tis\ListenStdDraftController@show');
    Route::resource('tis/listen_std_draft', 'Tis\ListenStdDraftController');

    Route::get('tis/report_comment_standard_drafts/export_excel', 'Tis\ReportCommentStandardDraftsController@export_excel');
    Route::get('tis/report_comment_standard_drafts/show/{id}', 'Tis\ReportCommentStandardDraftsController@show');
    Route::resource('tis/report_comment_standard_drafts', 'Tis\ReportCommentStandardDraftsController');

    Route::get('tis', function (){
        return view('admin/tis');
    });

    //รายงานแผน-ผล การปฏิบัติงาน (งาน, เงิน)
    Route::get('tis/report_performance/export_excel', 'Tis\ReportPerformanceController@export_excel');
    Route::resource('tis/report_performance', 'Tis\\ReportPerformanceController');

    Route::get('tis/product_name/data_list', 'Tis\\StandardProductNameController@data_list');
    Route::POST('tis/product_name/update_description', 'Tis\\StandardProductNameController@update_description');
    Route::resource('tis/product_name', 'Tis\\StandardProductNameController');


});


Route::get('tis/comment_standard_drafts/create', 'Tis\CommentStandardDraftsController@create');
Route::patch('tis/comment_standard_drafts/store', 'Tis\CommentStandardDraftsController@store');
Route::get('tis/comment_standard_drafts/form/{token}', 'Tis\CommentStandardDraftsController@form');
Route::patch('tis/comment_standard_drafts/save/{token}', 'Tis\CommentStandardDraftsController@save');
Route::get('tis/comment_standard_drafts/success', function (){
    return view('tis.comment_standard_drafts.success');
})->name('draft_success');

Route::get('tis/comment_standard_reviews/create', 'Tis\CommentStandardReviewsController@create');
Route::patch('tis/comment_standard_reviews/store', 'Tis\CommentStandardReviewsController@store');
Route::get('tis/comment_standard_reviews/form/{token}', 'Tis\CommentStandardReviewsController@form');
Route::patch('tis/comment_standard_reviews/save/{token}', 'Tis\CommentStandardReviewsController@save');
Route::get('tis/comment_standard_reviews/success', function () {
    return view('tis.comment_standard_reviews.success');
})->name('review_success');

Route::get('tis/listen_std_draft/create', 'Tis\ListenStdDraftController@create');
Route::patch('tis/listen_std_draft/store', 'Tis\ListenStdDraftController@store');
Route::get('tis/listen_std_draft/form/{id}', 'Tis\ListenStdDraftController@form');
Route::patch('tis/listen_std_draft/save/{token}', 'Tis\ListenStdDraftController@save');
Route::get('tis/listen_std_draft/success', function () {
    return view('tis.listen_std_draft.success');
})->name('review_success');

// Route::get('tis/note_std_draft/{id}/edit', 'Tis\NoteStdDraftController@edit');

Route::put('tis/note_std_draft/update/status', 'Tis\NoteStdDraftController@update_status');
Route::get('tis/note_std_draft/form/{id}', 'Tis\NoteStdDraftController@form');
Route::post('tis/note_std_draft/save_note_std_draft', 'Tis\NoteStdDraftController@save_note_std_draft');
Route::resource('tis/note_std_draft', 'Tis\\NoteStdDraftController');
Route::get('tis/delete/file/{path}/{token}','Tis\NoteStdDraftController@removeFilesWithMessage');


// อ่านไฟล์ที่แนบมา
Route::get('tis/public_draft/files/{filename}', function($filename)
{
    $public = public_path();
    $attach_path = 'files/public_draft/';
    if(HP::checkFileStorage($attach_path. $filename)){
        HP::getFileStoragePath($attach_path. $filename);
        $filePath =  response()->file($public.'/uploads/'.$attach_path.$filename);
            return $filePath;
    }else{
        return Response::make("File does not exist.", 404);
    }

    // $filePath = storage_path().'/files/public_draft/'.$filename;

    // if (!File::exists($filePath))
    // {
    //     return Response::make("File does not exist.", 404);
    // }

    // $fileContents = File::get($filePath);
    // $type = File::mimeType($filePath);

    // if ($type == 'application/pdf'){
    //     return response()->file($filePath, [
    //         'Content-Type' => 'application/pdf'
    //     ]);
    // }
    // else {
    //     return Response::make($fileContents, 200)->header("Content-Type", 'blob');

    // }
})->name('show.file');

Route::get('tis/tis_attach/set_standard/files/{filename}', function($filename)
{
    $public = public_path();
    $attach_path = 'tis_attach/set_standard/';
   if(HP::checkFileStorage($attach_path. $filename)){
       HP::getFileStoragePath($attach_path. $filename);
       $filePath =  response()->file($public.'/uploads/'.$attach_path.$filename);
        return $filePath;
   }else{
       return Response::make("File does not exist.", 404);
   }
});

// ระบบสรุปผลความคิดเห็นต่อร่างกฎกระทรวง
// Route::resource('tis/listen_std_draft', 'Tis\\ListenStdDraftController');

Route::put('tis/listenstddraftresults/update-state', 'Tis\ListenStdDraftResultsController@update_state');
Route::resource('tis/listen-std-draft-results', 'Tis\\ListenStdDraftResultsController');
