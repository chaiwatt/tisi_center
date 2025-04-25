<?php
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'auth'],function (){


Route::get('bcertify/calibration_branch/list/{formula_id}', function ($formula_id){
    return response()->json(App\Models\Bcertify\CalibrationBranch::where('formula_id', $formula_id)->pluck('title', 'id'));
});

Route::get('bcertify/calibration_group/list/{formula_id?}/{calibration_branch_id?}', function ($formula_id, $calibration_branch_id){
    return response()->json(App\Models\Bcertify\CalibrationGroup::where('formula_id', $formula_id)->where('calibration_branch_id', $calibration_branch_id)->pluck('title', 'id'));
});

Route::get('bcertify/test_branch/list/{formula_id}', function ($formula_id){
    return response()->json(App\Models\Bcertify\TestBranch::where('formula_id', $formula_id)->pluck('title', 'id'));
});

Route::get('bcertify/product_category/list/{test_branch_id}', function ($test_branch_id){
    return response()->json(App\Models\Bcertify\ProductCategory::where('test_branch_id', $test_branch_id)->pluck('title', 'id'));
});

Route::put('bcertify/formula/update-state', 'Bcertify\FormulaController@update_state');
Route::resource('bcertify/formula', 'Bcertify\\FormulaController');

Route::put('bcertify/signer/update-state', 'Bcertify\SignerController@update_state');
Route::resource('bcertify/signer', 'Bcertify\\SignerController');

Route::put('bcertify/lab_condition/update-state', 'Bcertify\LabConditionController@update_state');
Route::resource('bcertify/lab_condition', 'Bcertify\\LabConditionController');

Route::put('bcertify/calibration_branch/update-state', 'Bcertify\CalibrationBranchController@update_state');
Route::resource('bcertify/calibration_branch', 'Bcertify\\CalibrationBranchController');

Route::put('bcertify/calibration_group/update-state', 'Bcertify\CalibrationGroupController@update_state');
Route::resource('bcertify/calibration_group', 'Bcertify\\CalibrationGroupController');

Route::put('bcertify/calibration_item/update-state', 'Bcertify\CalibrationItemController@update_state');
Route::resource('bcertify/calibration_item', 'Bcertify\\CalibrationItemController');

Route::put('bcertify/test_branch/update-state', 'Bcertify\TestBranchController@update_state');
Route::resource('bcertify/test_branch', 'Bcertify\\TestBranchController');

Route::put('bcertify/product_category/update-state', 'Bcertify\ProductCategoryController@update_state');
Route::resource('bcertify/product_category', 'Bcertify\\ProductCategoryController');

Route::put('bcertify/product_item/update-state', 'Bcertify\ProductItemController@update_state');
Route::resource('bcertify/product_item', 'Bcertify\\ProductItemController');

Route::put('bcertify/test_item/update-state', 'Bcertify\TestItemController@update_state');
Route::resource('bcertify/test_item', 'Bcertify\\TestItemController');

Route::put('bcertify/inspect_type/update-state', 'Bcertify\InspectTypeController@update_state');
Route::resource('bcertify/inspect_type', 'Bcertify\\InspectTypeController');

Route::put('bcertify/inspect_category/update-state', 'Bcertify\InspectCategoryController@update_state');
Route::resource('bcertify/inspect_category', 'Bcertify\\InspectCategoryController');

Route::put('bcertify/inspect_branch/update-state', 'Bcertify\InspectBranchController@update_state');
Route::resource('bcertify/inspect_branch', 'Bcertify\\InspectBranchController');

Route::put('bcertify/inspect_kind/update-state', 'Bcertify\InspectKindController@update_state');
Route::resource('bcertify/inspect_kind', 'Bcertify\\InspectKindController');

Route::put('bcertify/certification_branch/update-state', 'Bcertify\CertificationBranchController@update_state');
Route::resource('bcertify/certification_branch', 'Bcertify\\CertificationBranchController');

Route::put('bcertify/industry_type/update-state', 'Bcertify\IndustryTypeController@update_state');
Route::resource('bcertify/industry_type', 'Bcertify\\IndustryTypeController');

Route::put('bcertify/iaf/update-state', 'Bcertify\IafController@update_state');
Route::resource('bcertify/iaf', 'Bcertify\\IafController');

Route::put('bcertify/enms/update-state', 'Bcertify\EnmsController@update_state');
Route::resource('bcertify/enms', 'Bcertify\\EnmsController');

Route::put('bcertify/ghg/update-state', 'Bcertify\GhgController@update_state');
Route::resource('bcertify/ghg', 'Bcertify\\GhgController');

Route::put('bcertify/status_auditor/update-state', 'Bcertify\StatusAuditorController@update_state');
Route::resource('bcertify/status_auditor', 'Bcertify\\StatusAuditorController');

Route::put('bcertify/status_progress/update-publish', 'Bcertify\StatusProgressController@update_publish');
Route::put('bcertify/status_progress/update-state', 'Bcertify\StatusProgressController@update_state');
Route::resource('bcertify/status_progress', 'Bcertify\\StatusProgressController');

Route::put('bcertify/config_attach/update-state', 'Bcertify\ConfigAttachController@update_state');
Route::resource('bcertify/config_attach', 'Bcertify\\ConfigAttachController');

Route::put('bcertify/certification_scope/update-state', 'Bcertify\CertificationScopeController@update_state');
Route::resource('bcertify/certification_scope', 'Bcertify\\CertificationScopeController');

Route::put('bcertify/ghg_activity/update-state', 'Bcertify\GhgActivityController@update_state');
Route::resource('bcertify/ghg_activity', 'Bcertify\\GhgActivityController');

// ระบบผู้ตรวจประเมิน

Route::get('/bcertify/auditor', [
    'as' => 'bcertify.auditor',
    'uses' => 'Bcertify\\AuditorController@index']);

Route::get('/bcertify/create/auditor', [
    'as' => 'bcertify.auditor.create',
    'uses' => 'Bcertify\\AuditorController@create']);

Route::get('/bcertify/edit/auditor/{token}', [
    'as' => 'bcertify.auditor.edit',
    'uses' => 'Bcertify\\AuditorController@edit']);

Route::get('/bcertify/edit/auditor/education/{token}', [
    'as' => 'bcertify.auditor.edit.education',
    'uses' => 'Bcertify\\AuditorController@editEducation']);

Route::get('/bcertify/edit/auditor/training/{token}', [
    'as' => 'bcertify.auditor.edit.training',
    'uses' => 'Bcertify\\AuditorController@editTraining']);

Route::get('/bcertify/edit/auditor/expertise/{token}', [
    'as' => 'bcertify.auditor.edit.expertise',
    'uses' => 'Bcertify\\AuditorController@editExpertise']);

Route::get('/bcertify/edit/auditor/work/{token}', [
    'as' => 'bcertify.auditor.edit.work',
    'uses' => 'Bcertify\\AuditorController@editWork']);

Route::get('/bcertify/edit/auditor/assessment/{token}', [
    'as' => 'bcertify.auditor.edit.assessment',
    'uses' => 'Bcertify\\AuditorController@editAssessment']);

Route::get('/bcertify/show/auditor/{token}', [
    'as' => 'bcertify.auditor.show',
    'uses' => 'Bcertify\\AuditorController@show']);

Route::get('/bcertify/destroy/auditor/{token}', [
    'as' => 'bcertify.auditor.destroy',
    'uses' => 'Bcertify\\AuditorController@destroy']);


Route::get('/bcertify/auditor/update/{id}', [
    'as' => 'bcertify.auditor.update',
    'uses' => 'Bcertify\\AuditorController@update']);

Route::post('/bcertify/auditor/store', [
    'as' => 'bcertify.auditor.store',
    'uses' => 'Bcertify\\AuditorController@store']);

Route::post('/bcertify/auditor/store/update', [
    'as' => 'bcertify.auditor.store.update',
    'uses' => 'Bcertify\\AuditorController@updateStore']);

Route::post('/bcertify/auditor/update/education', [
    'as' => 'bcertify.auditor.update.education',
    'uses' => 'Bcertify\\AuditorController@updateEducation']);

Route::post('/bcertify/auditor/update/training', [
    'as' => 'bcertify.auditor.update.training',
    'uses' => 'Bcertify\\AuditorController@updateTraining']);

Route::post('/bcertify/auditor/update/work', [
    'as' => 'bcertify.auditor.update.work',
    'uses' => 'Bcertify\\AuditorController@updateWork']);

Route::post('/bcertify/auditor/update/expertise', [
    'as' => 'bcertify.auditor.update.expertise',
    'uses' => 'Bcertify\\AuditorController@updateExpertise']);

Route::post('/bcertify/auditor/update/assessment', [
    'as' => 'bcertify.auditor.update.assessment',
    'uses' => 'Bcertify\\AuditorController@updateAssessment']);

Route::post('/bcertify/api/standard', [  // api มาตรฐาน
    'as' => 'bcertify.api.standard',
    'uses' => 'Bcertify\\AuditorController@apiStandard']);

Route::post('/bcertify/api/scope', [  // api ขอบข่าย
    'as' => 'bcertify.api.scope',
    'uses' => 'Bcertify\\AuditorController@apiScope']);

Route::post('/bcertify/api/calibration', [  // api รายการสอบเทียบ
    'as' => 'bcertify.api.calibration',
    'uses' => 'Bcertify\\AuditorController@apiCalibration']);

Route::post('/bcertify/api/inspection', [  // api ประเภทหน่วยตรวจ
    'as' => 'bcertify.api.inspection',
    'uses' => 'Bcertify\\AuditorController@apiInspection']);

Route::post('/bcertify/api/product', [  // api ประเภทผลิตภัณฒ์ and api รายการทดสอบ
    'as' => 'bcertify.api.product',
    'uses' => 'Bcertify\\AuditorController@apiProduct']);

Route::post('/bcertify/api/province', [  // api province
    'as' => 'bcertify.api.province',
    'uses' => 'Bcertify\\AuditorController@apiProvince']);

Route::post('/bcertify/api/amphur', [  // api amphur
    'as' => 'bcertify.api.amphur',
    'uses' => 'Bcertify\\AuditorController@apiAmphur']);



    Route::resource('bcertify/auditors', 'Bcertify\\AuditorsController');

    Route::get('/bcertify/auditors/create', [
        'as' => 'bcertify.auditors.create',
        'uses' => 'Bcertify\\AuditorsController@create']);

    Route::get('/bcertify/edit/auditors/{token}', [ 
        'as' => 'bcertify.auditors.edit',
        'uses' => 'Bcertify\\AuditorsController@edit']);

   Route::POST('/bcertify/api/check_standard', [  // api มาตรฐาน
        'as' => 'bcertify.api.check_standard',
        'uses' => 'Bcertify\\AuditorsController@apiStandard']);
                
   Route::get('/bcertify/api/check_scope', [  // api ขอบข่าย
        'as' => 'bcertify.api.check_scope',
        'uses' => 'Bcertify\\AuditorsController@apiScope']);
                        
   Route::post('/bcertify/api/check_inspection', [  // api ประเภทหน่วยตรวจ
        'as' => 'bcertify.api.check_inspection',
        'uses' => 'Bcertify\\AuditorsController@apiInspection']);
  Route::post('/bcertify/api/check_calibration', [  // api รายการสอบเทียบ
        'as' => 'bcertify.api.check_calibration',
        'uses' => 'Bcertify\\AuditorsController@apiCalibration']);
  Route::post('/bcertify/api/check_product', [  // api ประเภทผลิตภัณฒ์ and api รายการทดสอบ
            'as' => 'bcertify.api.check_product',
            'uses' => 'Bcertify\\AuditorsController@apiProduct']);
  Route::get('/bcertify/auditors/update_status/{id}', [
         'as' => 'bcertify.auditors.update_status',
        'uses' => 'Bcertify\\AuditorsController@update_status']);

    Route::get('bcertify/standardtypes/data_list', 'Bcertify\\StandardtypesController@data_list');
    Route::get('bcertify/standardtypes/destroy/{id?}', 'Bcertify\\StandardtypesController@destroy');
    Route::post('bcertify/standardtypes/update_status', 'Bcertify\\StandardtypesController@update_status');
    Route::POST('bcertify/standardtypes/update_publish', 'Bcertify\\StandardtypesController@update_publish');
    Route::POST('bcertify/standardtypes/delete', 'Bcertify\\StandardtypesController@delete');
    Route::resource('bcertify/standardtypes', 'Bcertify\\StandardtypesController');

    // ความเห็นการกำหนดมาตรฐานการตรวจสอบและรับรอง
    Route::POST('bcertify/standards-offers/save_assign', 'Bcertify\\StandardsOffersController@save_assign');
    Route::POST('bcertify/standards-offers/data_refno', 'Bcertify\\StandardsOffersController@data_refno');
    Route::get('bcertify/standards-offers/data_list', 'Bcertify\\StandardsOffersController@data_list');
    Route::resource('bcertify/standards-offers', 'Bcertify\\StandardsOffersController');

    Route::get('bcertify/meetingtypes/destroy/{id?}', 'Bcertify\\MeetingtypesController@destroy');
    Route::post('bcertify/meetingtypes/update_status', 'Bcertify\\MeetingtypesController@update_status');
    Route::POST('bcertify/meetingtypes/update_publish', 'Bcertify\\MeetingtypesController@update_publish');
    Route::POST('bcertify/meetingtypes/delete', 'Bcertify\\MeetingtypesController@delete');
    Route::get('bcertify/meetingtypes/data_list', 'Bcertify\\MeetingtypesController@data_list');
    Route::resource('bcertify/meetingtypes', 'Bcertify\\MeetingtypesController');

    Route::get('bcertify/setting-fee', 'Bcertify\SettingFeeController@index');
    Route::POST('bcertify/setting-fee/store', 'Bcertify\\SettingFeeController@store');

    Route::get('bcertify/reason/data_list', 'Bcertify\ReasonController@data_list');
    Route::resource('bcertify/reason', 'Bcertify\\ReasonController');

    Route::POST('bcertify/setting-running/delete', 'Bcertify\\SettingRunningController@delete');
    Route::get('bcertify/setting-running/destroy/{id?}', 'Bcertify\\SettingRunningController@destroy');
    Route::POST('bcertify/setting-running/update_publish', 'Bcertify\\SettingRunningController@update_publish');
    Route::POST('bcertify/setting-running/update_status', 'Bcertify\\SettingRunningController@update_status');
    Route::get('bcertify/setting-running/data_list', 'Bcertify\\SettingRunningController@data_list');
    Route::get('bcertify/setting-running/data_history', 'Bcertify\\SettingRunningController@data_history');
    Route::resource('bcertify/setting_running', 'Bcertify\\SettingRunningController');

    Route::get('bcertify/setting-config', 'Bcertify\SettingConfigController@index');
    Route::POST('bcertify/setting-config/store', 'Bcertify\\SettingConfigController@store');

    Route::POST('bcertify/setting-config/store', 'Bcertify\\SettingConfigController@store');


    //ปรับปรุงระบบโดย npcsolution
    Route::group(['prefix' => 'bcertify'], function(){
        Route::group(['prefix' => 'setting_scope_lab_cal'], function(){
            Route::put('update-state', 'Bcertify\\SettingScopeLabCalController@update_state');
            Route::group(['prefix' => 'instrument-group'], function(){
                Route::get('/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@index');
                Route::get('create/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@create');
                Route::post('store/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@store');
                Route::get('show/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@show');
                Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@edit');
                Route::post('update/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupController@update');
                Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabCalInstrumentGroupController@update_state');
                Route::group(['prefix' => 'instrument'], function(){
                    Route::get('/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@index');
                    Route::get('create/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@create');
                    Route::post('store/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@store');
                    Route::get('show/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@show');
                    Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@edit');
                    Route::post('update/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupInstrumentController@update');
                    Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabCalInstrumentGroupInstrumentController@update_state');
                });
                Route::group(['prefix' => 'parameter-one'], function(){
                    Route::get('/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@index');
                    Route::get('create/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@create');
                    Route::post('store/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@store');
                    Route::get('show/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@show');
                    Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@edit');
                    Route::post('update/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterOneController@update');
                    Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabCalInstrumentGroupParameterOneController@update_state');
                });
                Route::group(['prefix' => 'parameter-two'], function(){
                    Route::get('/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@index');
                    Route::get('create/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@create');
                    Route::post('store/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@store');
                    Route::get('show/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@show');
                    Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@edit');
                    Route::post('update/{id?}', 'Bcertify\\SettingScopeLabCalInstrumentGroupParameterTwoController@update');
                    Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabCalInstrumentGroupParameterTwoController@update_state');
                });
            });


        });


    Route::group(['prefix' => 'setting_scope_lab_test'], function(){
        Route::put('update-state', 'Bcertify\\SettingScopeLabTestController@update_state');
        Route::group(['prefix' => 'category'], function(){
            Route::get('/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@index');
            Route::get('create/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@create');
            Route::post('store/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@store');
            Route::get('show/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@show');
            Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@edit');
            Route::post('update/{id?}', 'Bcertify\\SettingScopeLabTestCategoryController@update');
            Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabTestCategoryController@update_state');
            Route::group(['prefix' => 'parameter'], function(){
                Route::get('/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@index');
                Route::get('create/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@create');
                Route::post('store/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@store');
                Route::get('show/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@show');
                Route::get('edit/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@edit');
                Route::post('update/{id?}', 'Bcertify\\SettingScopeLabTestCategoryParameterController@update');
                Route::put('update-state/{id?}', 'Bcertify\SettingScopeLabTestCategoryParameterController@update_state');
            });
        });
    });

    
    Route::resource('setting_scope_lab_cal', 'Bcertify\\SettingScopeLabCalController');

    Route::resource('setting_scope_lab_test', 'Bcertify\\SettingScopeLabTestController');

    Route::resource('setting_scope_cb', 'Bcertify\\SettingScopeCbController');

    Route::resource('setting_scope_ib', 'Bcertify\\SettingScopeIbController');

    });


});


