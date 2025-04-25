<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Basic\Config;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certificate\TrackingPayInTwo;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingAuditors; 
use App\Models\Certificate\TrackingHistory; 
use App\Models\Certificate\TrackingReport; 
use HP;
use DB;
use App\Models\Certificate\Tracking;

use App\Models\Certify\Applicant\CertiLab;
use App\CertificateExport;
use App\Models\Certify\Applicant\CertLabsFileAll;

use App\Models\Certify\ApplicantCB\CertiCb; 
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certify\ApplicantCB\CertiCBFileAll;

use App\Models\Certify\ApplicantIB\CertiIb; 
use App\Models\Certify\ApplicantIB\CertiIBExport; 
use App\Models\Certify\ApplicantIB\CertiIBFileAll;

use Illuminate\Support\Facades\Mail; 
use App\Mail\Tracking\InformPayInOne; 


use App\Models\Certify\PayInAll;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\CostCertificate;

use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
use App\Models\Certify\ApplicantIB\CertiIBPayInTwo;

use App\Models\Certify\ApplicantCB\CertiCBPayInOne;
use App\Models\Certify\ApplicantCB\CertiCBPayInTwo;

 
use Mpdf\Mpdf;

class CertificateController extends Controller
{

    public function index(Request $request)
    {

            $type      = $request->input('type');
            $id        = $request->input('id');
            $cer       = $request->input('cer');
            if(!empty($type) && !empty($id)){
                if($type == 'CB'){
                $export  =  CertiCBExport::select('certificate_newfile','certificate_path', 'attachs','attach_client_name','certificate')
                                            ->where('id',$id)
                                            ->first();
                $attach_path = 'files/applicants/check_files_cb';
                }else if($type == 'IB'){
                    $export  =  CertiIBExport::select('certificate_newfile','certificate_path', 'attachs','attach_client_name','certificate')
                                            ->where('id',$id)
                                            ->first();
                    $attach_path = 'files/applicants/check_files_ib';
                }else if($type == 'LAB'){
                    $export  =  CertificateExport::select('certificate_newfile','certificate_path', 'attachs','attachs_client_name AS attach_client_name','certificate_no AS certificate')
                                            ->where('id',$id)
                                            ->first();
                    $attach_path = 'files/applicants/check_files';
                }
            
                if(!empty($export)){
                    $i=1;
                    $public = public_path();
                    if(!is_null($export->certificate_newfile) &&  HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile) ){  // ใบรับรองอิเล็กทรอนิกส์ 
                        HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile);
                            // $filePath =  response()->file($public.'/uploads/'.$export->certificate_path .'/'. $export->certificate_newfile, [
                            //                                                                                                                     'Content-Disposition' => 'filename="'.$export->certificate.'_'.date('Ymd_hms').'.pdf'.'"'
                            //                                                                                                                 ]);
                            // return $filePath;
                            $mpdf = new Mpdf([
                                'format'            => 'A4'
                             ]);
                             $mpdf->SetImportUse();
                             $dashboard_pdf_file =    $public.'/uploads/'.$export->certificate_path .'/'. $export->certificate_newfile;
                             $mpdf->SetSourceFile($dashboard_pdf_file);
                             $mpdf->AddPage();
                             $import_page = $mpdf->ImportPage($i);
                             $mpdf->UseTemplate($import_page);
                             $title =  $export->certificate.'_'.date('Ymd_hms').'.pdf';
                             $mpdf->SetTitle($title);
                             $mpdf->Output($title, 'I');


                    }else if(!is_null($export->attachs) && HP::checkFileStorage($attach_path .'/'. $export->attachs)){   // ใบรับรอง
                            HP::getFileStoragePath($attach_path.'/'. $export->attachs);
                            $mpdf = new Mpdf([
                                'format'            => 'A4'
                             ]);
                             $mpdf->SetImportUse();
                             $dashboard_pdf_file = $public.'/uploads/'.$attach_path .'/'. $export->attachs;
                             $mpdf->SetSourceFile($dashboard_pdf_file);
                             $mpdf->AddPage();
                             $import_page = $mpdf->ImportPage($i);
                             $mpdf->UseTemplate($import_page);
                             $title =  !empty($export->attach_client_name) ? $export->attach_client_name : $export->certificate.'_'.date('Ymd_hms').'.pdf';
                             $mpdf->SetTitle($title);
                             $mpdf->Output($title, 'I');

                            // $filePath =  response()->file($public.'/uploads/'.$attach_path .'/'. $export->attachs, [
                            //                                                                                            'Content-Disposition' => 'filename="'.$export->attach_client_name.'"'
                            //                                                                                        ]);
                            // return $filePath;
                    }else{ 
                        return view('report.view-certificate.index');
                    }
                }else{
                    return view('report.view-certificate.index');
                }

            }else if(!empty($cer)){
                $exports = [];
                $export_cb  =  CertiCBExport::select('certificate_newfile','certificate_path', 'attachs','attach_client_name','certificate')
                                                ->selectRaw('"CB" as certify')
                                                // ->when($cer, function ($query, $cer){
                                                //     return  $query->Where('certificate',$cer) ;
                                                // })
                                                ->where('certificate',$cer)
                                                ->get();
                if(count($export_cb) > 0){ //cb
                    $exports = $export_cb;
                    $attach_path = 'files/applicants/check_files_cb';
                }else{
                     $export_ib  =  CertiIBExport::select('certificate_newfile','certificate_path', 'attachs','attach_client_name','certificate')
                                            ->selectRaw('"IB" as certify')
                                            // ->when($cer, function ($query, $cer){
                                            //         return  $query->Where('certificate',$cer) ;
                                            // })
                                            ->where('certificate',$cer)
                                            ->get();
                    if(count($export_ib) > 0){ //ib
                            $exports = $export_ib;
                            $attach_path = 'files/applicants/check_files_ib';
                    }else{
                        $export_lab  =  CertificateExport::select('certificate_newfile','certificate_path', 'attachs','attachs_client_name AS attach_client_name','certificate_no AS certificate')
                                                 ->selectRaw('"LAB" as certify')
                                                //   ->when($cer, function ($query, $cer){
                                                //        return  $query->Where('certificate_no',$cer) ;
                                                //   })
                                                ->where('certificate_no',$cer)
                                                  ->get(); 
                         if(!is_null($export_lab)){ //lab
                           $exports = $export_lab;
                           $attach_path = 'files/applicants/check_files';

                         }
                    }
                }
       
           
                
           
                if(count($exports) > 0){

                    $public = public_path();
                    
             try { 
                    $mpdf = new Mpdf([
                        'format'            => 'A4'
                     ]);
                     $i=1;
                     foreach($exports as $export){
                        if(!is_null($export->certificate_newfile) &&  HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile)){  // ใบรับรองอิเล็กทรอนิกส์ 
                            HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile);
                            $mpdf->SetImportUse();
                            $dashboard_pdf_file =    $public.'/uploads/'.$export->certificate_path .'/'. $export->certificate_newfile;
                            $mpdf->SetSourceFile($dashboard_pdf_file);
                            $mpdf->AddPage();
                            $import_page = $mpdf->ImportPage($i);
                            $mpdf->UseTemplate($import_page);
                            $title =  $export->certificate.'_'.date('Ymd_hms').'.pdf';
                        }else if(!is_null($export->attachs) && HP::checkFileStorage($attach_path .'/'. $export->attachs)){   // ใบรับรอง
                            HP::getFileStoragePath($attach_path.'/'. $export->attachs);
                            $mpdf->SetImportUse();
                            $dashboard_pdf_file = $public.'/uploads/'.$attach_path .'/'. $export->attachs;
                            $mpdf->SetSourceFile($dashboard_pdf_file);
                            $mpdf->AddPage();
                            $import_page = $mpdf->ImportPage($i);
                            $mpdf->UseTemplate($import_page);
                            $title =  !empty($export->attach_client_name) ? $export->attach_client_name : $export->certificate.'_'.date('Ymd_hms').'.pdf';
                        }
                     }

                     $exports_first = $exports->first();
                     if(count($exports) > 1 ){
                         $title =  $exports_first->certificate.'_'.date('Ymd_hms').'.pdf';
                      }
                     $mpdf->SetTitle($title);
                     $mpdf->Output($title, 'I');

          } catch (\Exception $e) {
                           $export = $export->first();
                    if(!is_null($export->certificate_newfile) &&  HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile) ){  // ใบรับรองอิเล็กทรอนิกส์ 
                        HP::checkFileStorage($export->certificate_path .'/'. $export->certificate_newfile);
                            $filePath =  response()->file($public.'/uploads/'.$export->certificate_path .'/'. $export->certificate_newfile, [
                                                                                                                                                'Content-Disposition' => 'filename="'.$export->certificate.'_'.date('Ymd_hms').'.pdf'.'"'
                                                                                                                                            ]);
                            return $filePath;
                    }else if(!is_null($export->attachs) && HP::checkFileStorage($attach_path .'/'. $export->attachs)){   // ใบรับรอง
                            HP::getFileStoragePath($attach_path.'/'. $export->attachs);
                            $filePath =  response()->file($public.'/uploads/'.$attach_path .'/'. $export->attachs, [
                                                                                                                       'Content-Disposition' => 'filename="'.$export->attach_client_name.'"'
                                                                                                                   ]);
                            return $filePath;
                    }else{ 
                        return view('report.view-certificate.index');
                    }
          }



                }else{
                    return view('report.view-certificate.index');
                }
            }else {
                return view('report.view-certificate.index');
            }
        

    }

    
    
    public function update_status_export(Request $request)
    {

            $config = HP::getConfig(false);

            $number_cb = !empty($config->reference_number_cb) ? $config->reference_number_cb : '180';
            $date_cb  = HP::DatePlus(date('Y-m-d'),$number_cb);
           $certicb_file_alls = CertiCBFileAll::select('app_certi_cb_id')->whereNOtNull('end_date')->WhereDate('end_date','<=',$date_cb)->where('state',1)->get();
            if(count($certicb_file_alls) > 0){ 
                foreach($certicb_file_alls as $item){
                    if(!empty($item->certi_cb_to->CertiCBExportTo)){
                                $export  =    $item->certi_cb_to->CertiCBExportTo;
                                $tracking = Tracking::where('ref_table',(new CertiCBExport)->getTable())->where('ref_id',$export->id)->orderby('id','desc')->first();
                        if(is_null($tracking)){  // ครั้งแรกที่ต่อขอบข่าย
                                $track                =   new Tracking;   
                                $track->certificate_type     =  1;   
                                $track->ref_table     =   (new CertiCBExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->certi_cb_to->tax_id) ?  $item->certi_cb_to->tax_id: null;
                                $track->user_id       = !empty($item->certi_cb_to->created_by) ?  $item->certi_cb_to->created_by: null;
                                $track->agent_id      = !empty($item->certi_cb_to->agent_id) ?  $item->certi_cb_to->agent_id: null;
                                $track->save();

                                    echo "update success";
                                    echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate) ?  $export->certificate : null ).'</span>';
                                    echo '<br/>';
                        }else if(!is_null($tracking) && $tracking->status_id == 8){  // ต่อขอบข่ายมากกว่าหนึ่ง
                                $track                =  new Tracking;  
                                $track->certificate_type     =  1;     
                                $track->ref_table     =  (new CertiCBExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->certi_cb_to->tax_id) ?  $item->certi_cb_to->tax_id: null;
                                $track->user_id       = !empty($item->certi_cb_to->created_by) ?  $item->certi_cb_to->created_by: null;
                                $track->agent_id      = !empty($item->certi_cb_to->agent_id) ?  $item->certi_cb_to->agent_id: null;
                                $track->save();

                                echo "update success";
                                echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate) ?  $export->certificate : null ).'</span>';
                                echo '<br/>';
                        }
                    } 
                }
            }

     

            $number_ib = !empty($config->reference_number_ib) ? $config->reference_number_ib : '180';
            $date_ib  = HP::DatePlus(date('Y-m-d'),$number_ib);
            $certiib_file_alls = CertiIBFileAll::select('app_certi_ib_id')->whereNOtNull('end_date')->WhereDate('end_date','<=',$date_ib)->where('state',1)->get();
            if(count($certiib_file_alls) > 0){ 
                foreach($certiib_file_alls as $item){
                    if(!empty($item->certi_ib_to->CertiIBExportTo)){
                                $export  =    $item->certi_ib_to->CertiIBExportTo;
                                $tracking = Tracking::where('ref_table',(new CertiIBExport)->getTable())->where('ref_id',$export->id)->orderby('id','desc')->first();
                        if(is_null($tracking)){  // ครั้งแรกที่ต่อขอบข่าย
                                $track                =   new Tracking;    
                                $track->certificate_type     =  2;   
                                $track->ref_table     =   (new CertiIBExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->certi_ib_to->tax_id) ?  $item->certi_ib_to->tax_id: null;
                                $track->user_id       = !empty($item->certi_ib_to->created_by) ?  $item->certi_ib_to->created_by: null;
                                $track->agent_id      = !empty($item->certi_ib_to->agent_id) ?  $item->certi_ib_to->agent_id: null;
                                $track->save();

                                echo "update success";
                                echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate) ?  $export->certificate : null ).'</span>';
                                echo '<br/>';
                        }else if(!is_null($tracking) && $tracking->status_id == 8){  // ต่อขอบข่ายมากกว่าหนึ่ง
                                $track                =  new Tracking;  
                                $track->certificate_type     =  2;     
                                $track->ref_table     =  (new CertiIBExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->certi_ib_to->tax_id) ?  $item->certi_ib_to->tax_id: null;
                                $track->user_id       = !empty($item->certi_ib_to->created_by) ?  $item->certi_ib_to->created_by: null;
                                $track->agent_id      = !empty($item->certi_ib_to->agent_id) ?  $item->certi_ib_to->agent_id: null;
                                $track->save();

                                echo "update success";
                                echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate) ?  $export->certificate : null ).'</span>';
                                echo '<br/>';
                        }
                    } 
                }
            }

            $number_lab = !empty($config->reference_number_lab) ? $config->reference_number_lab : '180';
            $date_lab  = HP::DatePlus(date('Y-m-d'),$number_lab);
            $certilab_file_alls = CertLabsFileAll::select('app_certi_lab_id')->whereNOtNull('end_date')->WhereDate('end_date','<=',$date_lab)->where('state',1)->get();
            if(count($certilab_file_alls) > 0){ 
                foreach($certilab_file_alls as $item){
                    if(!empty($item->CertiLabTo->certificate_export_to)){
                                $export  =    $item->CertiLabTo->certificate_export_to;
                                $tracking = Tracking::where('ref_table',(new CertificateExport)->getTable())->where('ref_id',$export->id)->orderby('id','desc')->first();
                        if(is_null($tracking)){  // ครั้งแรกที่ต่อขอบข่าย
                                $track                =   new Tracking; 
                                $track->certificate_type     =  3;      
                                $track->ref_table     =   (new CertificateExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->CertiLabTo->tax_id) ?  $item->CertiLabTo->tax_id: null;
                                $track->user_id       = !empty($item->CertiLabTo->created_by) ?  $item->CertiLabTo->created_by: null;
                                $track->agent_id      = !empty($item->CertiLabTo->agent_id) ?  $item->CertiLabTo->agent_id: null;
                                $track->save();

                                echo "update success";
                                echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate_no) ?  $export->certificate_no : null ).'</span>';
                                echo '<br/>';
                        }else if(!is_null($tracking) && $tracking->status_id == 8){  // ต่อขอบข่ายมากกว่าหนึ่ง
                                $track                =  new Tracking;  
                                $track->certificate_type     =  3;     
                                $track->ref_table     =  (new CertificateExport)->getTable();
                                $track->ref_id        =  $export->id;
                                $track->status_id     =  1;  
                                $track->tax_id        = !empty($item->CertiLabTo->tax_id) ?  $item->CertiLabTo->tax_id: null;
                                $track->user_id       = !empty($item->CertiLabTo->created_by) ?  $item->CertiLabTo->created_by: null;
                                $track->agent_id      = !empty($item->CertiLabTo->agent_id) ?  $item->CertiLabTo->agent_id: null;
                                $track->save();

                                echo "update success";
                                echo '-<span style="color:#33cc33;">เลขที่ใบรับรอง : '.(  !empty($export->certificate_no) ?  $export->certificate_no : null ).'</span>';
                                echo '<br/>';
                        }
                    } 
                }
            }
        
    }

   
    public function insert_payin_all(Request $request)
    {
        $type  =   $request->input('type');
        if($type == 1){ // lab

            $assessments = CostAssessment::get();
            if(count($assessments) > 0){
                foreach($assessments as $item){
                    if(!empty($item->applicant) && !is_null($item->conditional_type)){
                        $app = $item->applicant;
                        $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CostAssessment)->getTable())->first();
                        if(is_null($pay_in)){
                            $pay_in = new  PayInAll;
                        }
                        $pay_in->ref_id             =  $item->id ;
                        $pay_in->ref_table          = (new CostAssessment)->getTable() ;
                        $pay_in->conditional_type = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                        $pay_in->amount           = !empty($item->amount) ?  $item->amount: null ;
                        $pay_in->start_date       = !empty($item->report_date) ?  $item->report_date: null ;
                        $pay_in->detail           = !empty($item->detail) ?  $item->detail: null ;
                        $pay_in->start_date_feewaiver    = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                        $pay_in->end_date_feewaiver      = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                        $pay_in->app_no         = !empty($app->app_no) ?  $app->app_no: null ;
                        $pay_in->name           = !empty($app->name) ?  $app->name: null ;
                        $pay_in->tax_id         = !empty($app->tax_id) ?  $app->tax_id: null ;
                        $pay_in->name_unit      = !empty($app->lab_name) ?  $app->lab_name: null ;
                        $pay_in->auditors_name  = !empty($item->assessment->board_auditor_to->auditor) ?  $item->assessment->board_auditor_to->auditor : null ;
                        $pay_in->certify        = 1;
                        $pay_in->state          = 1;
                        $pay_in->created_by     = !empty($item->reporter_id) ?  $item->reporter_id: null ; 
                        $pay_in->created_at     = !empty($item->created_at) ?  $item->created_at: null ;
                        $pay_in->updated_at     = !empty($item->updated_at) ?  $item->updated_at: null ;
                        if( !empty($item->amount_invoice)   && !empty($item->file_client_name) ){
                            $attach = [];
                            $amount_invoice         =   basename($item->amount_invoice);
                            $attach['url']            =   'certify/check/file_client/'.str_replace($amount_invoice,"",$item->amount_invoice)   ;
                            $attach['new_filename']   = $amount_invoice;
                            $attach['filename']       =  $item->file_client_name;
                            $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                        }
                        $pay_in->save();  
                        echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                        echo '<br/>';
                    }
                }
            }
        
        }else   if($type == 2){ // ib

            $pay_ins = CertiIBPayInOne::get();
            if(count($pay_ins) > 0){
                foreach($pay_ins as $item){
                    if(!empty($item->CertiIBCostTo) && !is_null($item->conditional_type)){
                        $app = $item->CertiIBCostTo;
                        $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiIBPayInOne)->getTable())->first();
                        if(is_null($pay_in)){
                            $pay_in = new  PayInAll;
                        }
                        $pay_in->ref_id             =  $item->id ;
                        $pay_in->ref_table          = (new CertiIBPayInOne)->getTable() ;
                        $pay_in->conditional_type = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                        $pay_in->amount           = !empty($item->amount) ?  $item->amount: null ;
                        $pay_in->start_date       = !empty($item->start_date) ?  $item->start_date: null ;
                        $pay_in->detail           = !empty($item->detail) ?  $item->detail: null ;
                        $pay_in->start_date_feewaiver    = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                        $pay_in->end_date_feewaiver      = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                        $pay_in->app_no         = !empty($app->app_no) ?  $app->app_no: null ;
                        $pay_in->name           = !empty($app->name) ?  $app->name: null ;
                        $pay_in->tax_id         = !empty($app->tax_id) ?  $app->tax_id: null ;
                        $pay_in->name_unit      = !empty($app->name_unit) ?  $app->name_unit: null ;
                        $pay_in->auditors_name  = !empty($item->CertiIBAuditorsTo->auditor) ?  $item->CertiIBAuditorsTo->auditor : null ;
                        $pay_in->certify        = 2;
                        $pay_in->state          = 1;
                        $pay_in->created_by     = !empty($item->created_by) ?  $item->created_by: null ; 
                        $pay_in->created_at     = !empty($item->created_at) ?  $item->created_at: null ;
                        $pay_in->updated_at     = !empty($item->updated_at) ?  $item->updated_at: null ;

                        if( !empty($item->FileAttachPayInOne1To)    ){
                            $attach = [];
                            $file                     =   basename($item->FileAttachPayInOne1To->file);
                            $attach['url']            =   'files/applicants/check_files_ib/'.str_replace($file,"",$item->FileAttachPayInOne1To->file)   ;
                            $attach['new_filename']   = $file;
                            $attach['filename']       =  $item->FileAttachPayInOne1To->file_client_name;
                            $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                        }
                        $pay_in->save();  
                        echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                        echo '<br/>';
                    }
                }
            }

        }else   if($type == 3){ // cb

            $pay_ins = CertiCBPayInOne::get();
 
            if(count($pay_ins) > 0){
                foreach($pay_ins as $item){
                    if(!empty($item->CertiCbCostTo) && !is_null($item->conditional_type)){
                        $app = $item->CertiCbCostTo;
                        $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiCBPayInOne)->getTable())->first();
                        if(is_null($pay_in)){
                            $pay_in = new  PayInAll;
                        }
                        $pay_in->ref_id             =  $item->id ;
                        $pay_in->ref_table          = (new CertiCBPayInOne)->getTable() ;
                        $pay_in->conditional_type = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                        $pay_in->amount           = !empty($item->amount) ?  $item->amount: null ;
                        $pay_in->start_date       = !empty($item->start_date) ?  $item->start_date: null ;
                        $pay_in->detail           = !empty($item->detail) ?  $item->detail: null ;
                        $pay_in->start_date_feewaiver    = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                        $pay_in->end_date_feewaiver      = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                        $pay_in->app_no         = !empty($app->app_no) ?  $app->app_no: null ;
                        $pay_in->name           = !empty($app->name) ?  $app->name: null ;
                        $pay_in->tax_id         = !empty($app->tax_id) ?  $app->tax_id: null ;
                        $pay_in->name_unit      = !empty($app->name_standard) ?  $app->name_standard: null ;
                        $pay_in->auditors_name  = !empty($item->CertiCBAuditorsTo->auditor) ?  $item->CertiCBAuditorsTo->auditor : null ;
                        $pay_in->certify        = 3;
                        $pay_in->state          = 1;
                        $pay_in->created_by     = !empty($item->created_by) ?  $item->created_by: null ; 
                        $pay_in->created_at     = !empty($item->created_at) ?  $item->created_at: null ;
                        $pay_in->updated_at     = !empty($item->updated_at) ?  $item->updated_at: null ;

                        if( !empty($item->FileAttachPayInOne1To)){
                            $attach = [];
                            $file                     =   basename($item->FileAttachPayInOne1To->file);
                            $attach['url']            =   'files/applicants/check_files_cb/'.str_replace($file,"",$item->FileAttachPayInOne1To->file)   ;
                            $attach['new_filename']   = $file;
                            $attach['filename']       =  $item->FileAttachPayInOne1To->file_client_name;
                            $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                        }
                        $pay_in->save();  
                        echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                        echo '<br/>';
                    }
                }
            }

        }
        
    }
}
