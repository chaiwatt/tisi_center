<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Models\WS\Client as web_service;

Route::group(['middleware' => 'auth'],function (){

    Route::post('ws/web_service/send-mail', 'WS\WebServiceController@send_mail');
    Route::put('ws/web_service/update-state', 'WS\WebServiceController@update_state');
    Route::resource('ws/web_service', 'WS\WebServiceController');

    Route::get('ws/web_service/delete-files/{id?}/{url_send?}', function( $id, $url_send){

        $web_service = web_service::findOrFail($id);
        if( !empty($web_service) && !empty($web_service->file) ){

            $attach = json_decode($web_service->file);

            if( !empty($attach) && HP::checkFileStorage( 'tis_attach/web_service/'.$attach->file_name ) ){
                Storage::delete( '/tis_attach/web_service/'.$attach->file_name );
            }
            $web_service->update(['file'=>null]);
            return redirect(base64_decode($url_send))->with('delete_message', 'Delete Complete!');

        }
        
    });


    Route::get('ws/api_service/data_list', 'WS\ApiServiceController@data_list');
    Route::resource('ws/api_service', 'WS\ApiServiceController');

});