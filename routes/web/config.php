<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

Route::group(['middleware' => 'auth'],function (){

    Route::get('config/delete-files/{id?}/{url_send?}', function( $id, $url_send){

        $attach =  App\AttachFile::findOrFail($id);
        if( !empty($attach) && !empty($attach->url) ){
    
            if( HP::checkFileStorage( '/'.$attach->url) ){
                Storage::delete( '/'.$attach->url );
    
                $attach->delete();
            }
    
            return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');
    
        }
    
    });

    Route::POST('config/sso-url-group/update_order', 'Config\\SsoUrlGroupController@update_order');
    Route::resource('config/sso-url-group', 'Config\\SsoUrlGroupController');

    Route::POST('config/sso-url/update_order', 'Config\\SsoUrlController@update_order');
    Route::put('config/sso-url/update-state', 'Config\SsoUrlController@update_state');
    Route::resource('config/sso-url', 'Config\\SsoUrlController');

    Route::POST('config/evidence/system/delete', 'Config\\ConfigsEvidenceSystemController@delete');
    Route::POST('config/evidence/system/update_publish', 'Config\\ConfigsEvidenceSystemController@update_publish');
    Route::POST('config/evidence/system/update_status', 'Config\\ConfigsEvidenceSystemController@update_status');
    Route::get('config/evidence/system/data_list', 'Config\\ConfigsEvidenceSystemController@data_list');
    Route::resource('config/evidence/system', 'Config\\ConfigsEvidenceSystemController');


    Route::POST('config/evidence/group/delete', 'Config\\ConfigsEvidenceGroupController@delete');
    Route::POST('config/evidence/group/update_publish', 'Config\\ConfigsEvidenceGroupController@update_publish');
    Route::POST('config/evidence/group/update_status', 'Config\\ConfigsEvidenceGroupController@update_status');
    Route::get('config/evidence/group/data_list', 'Config\\ConfigsEvidenceGroupController@data_list');
    Route::resource('config/evidence/group', 'Config\\ConfigsEvidenceGroupController');

    Route::POST('config/format-code/delete', 'Config\\ConfigsFormatCodeController@delete');
    Route::POST('config/format-code/update_publish', 'Config\\ConfigsFormatCodeController@update_publish');
    Route::POST('config/format-code/update_status', 'Config\\ConfigsFormatCodeController@update_status');
    Route::get('config/format-code/data_list', 'Config\\ConfigsFormatCodeController@data_list');
    Route::get('config/format-code/data_histrory', 'Config\\ConfigsFormatCodeController@data_histrory');
    Route::resource('config/format-code', 'Config\\ConfigsFormatCodeController');

    Route::POST('config/import-holiday/delete', 'Config\\ConfigsImportHolidayController@delete');
    Route::POST('config/import-holiday/update_publish', 'Config\\ConfigsImportHolidayController@update_publish');
    Route::POST('config/import-holiday/update_status', 'Config\\ConfigsImportHolidayController@update_status');
    Route::get('config/import-holiday/data_list', 'Config\\ConfigsImportHolidayController@data_list');
    Route::get('config/import-holiday/data_histrory', 'Config\\ConfigsImportHolidayController@data_histrory');
    Route::resource('config/import-holiday', 'Config\\ConfigsImportHolidayController');

    Route::get('config/manual/delete-files/{id?}', function( $id){

        $data = App\Models\Config\ConfigsManual::findOrFail($id);
        if( !empty($data) && !empty($data->file) ){

            $attach = json_decode($data->file);

            if( !empty($attach) && HP::checkFileStorage( 'tis_attach/config_manual/'.$attach->file_name ) ){
                Storage::delete( '/tis_attach/config_manual/'.$attach->file_name );
            }
            $data->update(['file'=>null, 'file_url' => null ]);
            return redirect('config/manual')->with('delete_message', 'Delete Complete!');

        }

    });
    Route::resource('config/manual', 'Config\\ConfigsManualController');

    Route::POST('config/report-power-bi-group/update_order', 'Config\\ReportPowerBIGroupController@update_order');
    Route::resource('config/report-power-bi-group', 'Config\\ReportPowerBIGroupController');

    Route::get('config/report-power-bi/preview-url/{url_base64?}', 'Config\\ReportPowerBIController@preview_url');
    Route::POST('config/report-power-bi/update_order', 'Config\\ReportPowerBIController@update_order');
    Route::put('config/report-power-bi/update-state', 'Config\ReportPowerBIController@update_state');
    Route::resource('config/report-power-bi', 'Config\\ReportPowerBIController');

    Route::POST('config/faqs/delete', 'Config\\ConfigsFaqController@delete');
    Route::POST('config/faqs/update_publish', 'Config\\ConfigsFaqController@update_publish');
    Route::put('config/faqs/update_status', 'Config\\ConfigsFaqController@update_status');
    Route::get('config/faqs/data_list', 'Config\\ConfigsFaqController@data_list');
    Route::resource('config/faqs', 'Config\\ConfigsFaqController');

});
