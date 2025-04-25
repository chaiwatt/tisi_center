<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
//รายงาน ผลติดตาม IB
Route::get('report/test-factory/data_list', 'Report\ReportTestFacctoryController@data_list');
Route::resource('report/test-factory', 'Report\\ReportTestFacctoryController');

//รายงาน ผลติดตาม IB
Route::get('report/test-product/data_list', 'Report\ReportTestProductController@data_list');
Route::resource('report/test-product', 'Report\\ReportTestProductController');

//รายงานการลงชื่อเข้าใช้งานของผปก.
Route::get('report/report-sso-login', 'Report\SsoLoginController@index');

//รายงานการลงชื่อเข้าใช้งานของจนท.
Route::get('report/report-user-login', 'Report\UserLoginController@index');

//รายงานการกำหนดสิทธิ์
Route::get('report/roles/export_role', 'Report\ReportRolesController@expoet_excel_role');
Route::get('report/roles/export', 'Report\ReportRolesController@expoet_excel');
Route::get('report/roles/data_trader_list', 'Report\ReportRolesController@data_trader_list');
Route::get('report/roles/data_staff_list', 'Report\ReportRolesController@data_staff_list');
Route::get('report/roles/data_list', 'Report\ReportRolesController@data_list');
Route::get('report/roles/system/{id?}', 'Report\ReportRolesController@show_system');
Route::get('report/roles/users/{id?}', 'Report\ReportRolesController@show_users');
Route::resource('report/roles', 'Report\\ReportRolesController');

//รายงานการกำหนดสิทธิ์ (Elicense)
Route::get('report/elicense-roles/data_users_list', 'Report\ReportElicenseRoleController@data_users_list');
Route::get('report/elicense-roles/export_role', 'Report\ReportElicenseRoleController@expoet_excel_role');
Route::get('report/elicense-roles/export', 'Report\ReportElicenseRoleController@expoet_excel');
Route::get('report/elicense-roles/data_list', 'Report\ReportElicenseRoleController@data_list');
Route::get('report/elicense-roles/system/{id?}', 'Report\ReportElicenseRoleController@show_system');
Route::get('report/elicense-roles/users/{id?}', 'Report\ReportElicenseRoleController@show_users');
Route::resource('report/elicense-roles', 'Report\\ReportElicenseRoleController');