<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantIB\CertiIBFileAll;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use HP;
use Storage;
use File;
use Imagick;
use Response;


class PDFController extends Controller
{
  public function PrintAttachPDF($app_no){ 
        $certi_lab = CertiLab::where('app_no',$app_no)->first();
          if (!$certi_lab){
              abort(404);
          }
          $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
          $file = File::get($public.$certi_lab->attach_pdf);
          $response = Response::make($file, 200);
          $response->header('Content-Type', 'application/pdf');
          return $response;
      } 

      public function PrintAttachIBPDF($id){ 
            $file = CertiIBFileAll::where('state',1)
                                    ->where('app_certi_ib_id',$id)
                                    ->first();
          if (!$file){
              abort(404);
          }
    
          $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
          $file = File::get($public.$file->attach_pdf);
          $response = Response::make($file, 200);
          $response->header('Content-Type', 'application/pdf');
          return $response;
      } 

      public function PrintAttachCBPDF($id){ 
        $file = CertiCBFileAll::where('state',1)
                                ->where('app_certi_cb_id',$id)
                                ->first();
      if (!$file){
          abort(404);
      }

      $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
      $file = File::get($public.$file->attach_pdf);
      $response = Response::make($file, 200);
      $response->header('Content-Type', 'application/pdf');
      return $response;
    } 
    // url ใบรับรอง LAB IB CB
     public function FilePrintAttachIBPDF($list){ 
        $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        $file = File::get($public.'files/applicants/CertifyFilePdf/'.$list.'.pdf');
        $response = Response::make($file, 200);
        $response->header('Content-Type', 'application/pdf');
        return $response;
     } 
}
