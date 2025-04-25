<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;

Route::get('accepting-request-setion5', function (){
    return view('admin/setion5');
});


Route::get('setion5/get-view/files/{systems}/{tax_number}/{new_filename}/{filename}', function($systems,$tax_number,$new_filename,$filename)
{
    $public = public_path();
    $attach_path = 'files/'.$systems.'/'.$tax_number;

    if(HP::checkFileStorage($attach_path.'/'. $new_filename)){

        $file_name = $attach_path .'/'. $new_filename;
        $info = pathinfo( $file_name , PATHINFO_EXTENSION ) ;

        if( $info == "txt" || $info == "doc" || $info == "docx" || $info == "ppt" || $info == "7z" || $info == "zip"  ){
            return Storage::download($attach_path.'/'.  $new_filename);
        }else{
            HP::getFileStoragePath($attach_path .'/'. $new_filename);
            $filePath =  response()->file($public.'/uploads/'.$attach_path.'/'.  $new_filename);
            return $filePath;
        }
    }else{
        return 'ไม่พบไฟล์';
    }
});

//รับคำขอเป็นหน่วยตรวจสอบ (IB)
Route::get('setion5/accept-inspection-unit/data_list', 'Section5\AcceptInspectionUnitController@data_list');
Route::PATCH('setion5/accept-inspection-unit/approve-save/{id?}', 'Section5\\AcceptInspectionUnitController@approve_save');
Route::get('setion5/accept-inspection-unit/approve/{id?}', 'Section5\\AcceptInspectionUnitController@approve');
Route::Post('setion5/accept-inspection-unit/assing_data_update', 'Section5\\AcceptInspectionUnitController@assing_data_update');
Route::resource('setion5/accept-inspection-unit', 'Section5\\AcceptInspectionUnitController');

//รับคำขอเป็นผู้ตรวจสอบ (LAB)
Route::get('setion5/application_lab_accept/data_list', 'Section5\ApplicationLabAcceptController@data_list');
Route::PATCH('setion5/application_lab_accept/approve-save/{id?}', 'Section5\\ApplicationLabAcceptController@approve_save');
Route::get('setion5/application_lab_accept/approve/{id?}', 'Section5\\ApplicationLabAcceptController@approve');
Route::Post('setion5/application_lab_accept/assing_data_update', 'Section5\\ApplicationLabAcceptController@assing_data_update');
Route::resource('setion5/application_lab_accept', 'Section5\\ApplicationLabAcceptController');

//ระบบรายชื่อหน่วยตรวจสอบ (LAB)
Route::PATCH('section5/labs/infomation-save/{id?}', 'Section5\ManageLabsController@infomation_save');
Route::get('section5/labs/data_list', 'Section5\ManageLabsController@data_list');
Route::resource('section5/labs', 'Section5\\ManageLabsController');

