<?php

Route::group(['middleware' => 'auth'],function (){


Route::put('besurv/tis_unit/update-state', 'Besurv\TisUnitController@update_state');
Route::resource('besurv/tis_unit', 'Besurv\\TisUnitController');

Route::put('besurv/inspector/update-state', 'Besurv\InspectorController@update_state');
Route::resource('besurv/inspector', 'Besurv\\InspectorController');

Route::put('/setting-law-operations/update-state', 'Besurv\SettingLawOperationsController@update_state');
Route::resource('setting-law-operations', 'Besurv\\SettingLawOperationsController');


Route::put('besurv/signers/update-state', 'Besurv\SignersController@update_state');
Route::resource('besurv/signers', 'Besurv\\SignersController');

Route::put('besurv/qrcodes/update-state', 'Besurv\QrcodesController@update_state');
Route::resource('besurv/qrcodes', 'Besurv\\QrcodesController');
Route::get('besurv/qrcodes/remove_file/{type?}', 'Besurv\\QrcodesController@remove_file');
});
