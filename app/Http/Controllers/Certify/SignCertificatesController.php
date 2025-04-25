<?php

namespace App\Http\Controllers\Certify;

use HP;
use DB; 

use HP_DGA;
use QrCode;
use App\User;
use Storage; 
use App\Http\Requests;

use App\CertificateExport;
use Illuminate\Http\Request;
use  App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;
use App\Mail\Lab\OtpNofitication;
use App\Models\Basic\SubDepartment;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Models\Certify\SendCertificates;
use App\Models\Certify\Applicant\CertiLab;

use App\Models\Certify\SignCertificateOtp;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\SendCertificateLists;
use App\Models\Certify\SendCertificateHistory;
use App\Models\Certify\SignCertificateConfirms;

use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Http\Controllers\Certify\SendCertificatesController;


class SignCertificatesController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'files/sendcertificatelists';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('signcertificates','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.sign-certificates.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $model = str_slug('sendcertificates', '-');
        $tax_number = auth()->user()->reg_13ID;
        $tax_number =  str_replace("-","",$tax_number);
        $tax_number =  str_replace("","",$tax_number);

        $sign_id = Signer::where(DB::raw("REPLACE(REPLACE(tax_number,'-',''),' ','')"),$tax_number)->value('id') ; 
        if(is_null($sign_id)){
            $sign_id = '';
        }
   

        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');
        $filter_certificate_type = $request->input('filter_certificate_type');
        $filter_date_s = !empty($request->input('filter_start_date'))?HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_date_e = !empty($request->input('filter_end_date'))?HP::convertDate($request->input('filter_end_date'),true):null;

        $query = SendCertificateLists::query()
                                        ->select('certify_send_certificate_lists.*', 's_depart.sub_departname AS depart_name')
                                        ->LeftJoin('certify_send_certificates AS cer', 'cer.id', '=', 'certify_send_certificate_lists.send_certificate_id')
                                        ->LeftJoin((new User)->getTable()." AS user", 'user.runrecno', '=', 'cer.created_by')
                                        ->LeftJoin((new SubDepartment)->getTable()." AS s_depart", 's_depart.sub_id', '=', 'user.reg_subdepart')
                                        ->whereIn('certify_send_certificate_lists.sign_status',[1,2,3]) 
                                        ->where('cer.sign_id',$sign_id)  
                                        ->when($filter_search, function ($query, $filter_search){

                                            return $query->Where(function($query2) use ($filter_search) {
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        $export = CertificateExport::Where(DB::raw("REPLACE(certificate_no,' ','')"), 'LIKE', "%".$search_full."%")  ->select('id');
                                                        $export_ib = CertiIBExport::Where(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%")  ->select('id');
                                                        $export_cb = CertiCBExport::Where(DB::raw("REPLACE(certificate,' ','')"), 'LIKE', "%".$search_full."%")  ->select('id');
                        
         
                                                        // $app_lab = CertiLab::Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        //                         ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")  
                                                        //                         ->select('id');
                                                        // $app_ib = CertiIb::Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        //                         ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")  
                                                        //                         ->select('id');
                                                        //  $app_cb = CertiCb::Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        //                         ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")  
                                                        //                         ->select('id');
                 
                                                        $query2->whereIn('certificate_id', $export)
                                                              ->OrwhereIn('certificate_id', $export_ib)
                                                              ->OrwhereIn('certificate_id', $export_cb) ;


                                                  });
                                    
                                        }) 
                                        ->when($filter_state, function ($query, $filter_state){
                                            $query->where('certify_send_certificate_lists.sign_status', $filter_state);
                                         })
                                         ->when($filter_certificate_type, function ($query, $filter_certificate_type){
                                            $query->where('certify_send_certificate_lists.certificate_type', $filter_certificate_type);
                                          })
                                        ->when($filter_date_s, function ($query, $filter_date_s) use($filter_date_e){
                                                    if(!is_null($filter_date_s) && !is_null($filter_date_e) ){
                                                        return $query->whereBetween('certify_send_certificate_lists.created_at',[$filter_date_s,$filter_date_e]); 
                                                    }else if(!is_null($filter_date_s) && is_null($filter_date_e)){
                                                        return $query->whereDate('certify_send_certificate_lists.created_at',$filter_date_s);
                                                    }
                                         }) ; 
   
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                if($item->sign_status == 2){
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-certificate_type="'.$item->certificate_type.'"  value="'. $item->id .'">'; 
                                }else{
                                    return '';
                                }
                            })
                            ->addColumn('app_no', function ($item) {
                                    $text = '';
                                if($item->certificate_type ==  3){ // ห้องปฏิบัติการ
                                     $text =      !empty($item->cert_export_to->certificate_no)? $item->cert_export_to->certificate_no:'';
                                     $text .=     !empty($item->app_cert_to->app_no)? '<br/>('.$item->app_cert_to->app_no.')':'';

                                }else if($item->certificate_type ==   2){ // หน่วยตรวจสอบ
                                    $text =      !empty($item->cert_export_ib_to->certificate)? $item->cert_export_ib_to->certificate:'';
                                    $text .=     !empty($item->app_cert_ib_to->app_no)? '<br/>('.$item->app_cert_ib_to->app_no.')':'';
                                }else  if($item->certificate_type ==  1){ // ห้องหน่วยรับรอง
                                     $text =      !empty($item->cert_export_cb_to->certificate)? $item->cert_export_cb_to->certificate:'';
                                     $text .=     !empty($item->app_cert_cb_to->app_no)? '<br/>('.$item->app_cert_cb_to->app_no.')':'';
                                }
                                return    $text;   
                            })
                            ->addColumn('name', function ($item) {
                                $text = '';
                            if($item->certificate_type == 3){ // ห้องปฏิบัติการ
                                 $text =      !empty($item->app_cert_to->name)? $item->app_cert_to->name:'';
                                 $text .=     !empty($item->app_cert_to->tax_id)? '<br/>'.$item->app_cert_to->tax_id:'';

                            }else if($item->certificate_type ==  2){ // หน่วยตรวจสอบ
                                $text =      !empty($item->app_cert_ib_to->name)? $item->app_cert_ib_to->name:'';
                                $text .=     !empty($item->app_cert_ib_to->tax_id)? '<br/>'.$item->app_cert_ib_to->tax_id:'';
                            }else  if($item->certificate_type ==  1){ // ห้องหน่วยรับรอง
                                 $text =      !empty($item->app_cert_cb_to->name)? $item->app_cert_cb_to->name:'';
                                 $text .=     !empty($item->app_cert_cb_to->tax_id)? '<br/>'.$item->app_cert_cb_to->tax_id:'';
                            }
                            // $text = (!empty($item->depart_name)?'<br>('.$item->depart_name.')':null);
                            return    $text;   
                            })
                            ->addColumn('lab_type', function ($item) {
                                     $text = '';
                                    if($item->certificate_type == 3){ // ห้องปฏิบัติการ
                                        if( !empty($item->app_cert_to->lab_type)  && $item->app_cert_to->lab_type == 3){  // ทดสอบ
                                            $text = 'ห้องปฏิบัติการทดสอบ';
                                        }else{
                                            $text = 'ห้องปฏิบัติการสอบเทียบ ';
                                        }
                                    }else if($item->certificate_type ==  2){ // หน่วยตรวจสอบ
                                        $text = 'หน่วยตรวจสอบ';
                                    }else  if($item->certificate_type == 1){ // ห้องหน่วยรับรอง
                                        $text = 'หน่วยรับรอง';
                                    }
                                      return    $text;   
                            })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->created_at)? HP::DateTimeThai($item->created_at):'';   
                            })
                            ->addColumn('confirm_date', function ($item) {
                                return   !empty($item->sign_certificate_confirms_to->sign_certificate_otp_to->Confirm_date)? HP::DateTimeThai($item->sign_certificate_confirms_to->sign_certificate_otp_to->Confirm_date):'';   
                            })
                            ->addColumn('full_name', function ($item) {
                                return   !empty($item->send_certificates_to->user_created->FullName) ?  $item->send_certificates_to->user_created->FullName :'';   
                            })
                            ->addColumn('status', function ($item) {
                                return   !empty($item->SignStatusTitle) ?  $item->SignStatusTitle :'';   
                            })
                            ->addColumn('action', function ($item) {
                                     $text = '';
                                if($item->certificate_type == 3 && !empty($item->app_cert_to->check->id)){ // ห้องปฏิบัติการ
                                      $text =    '<a   class="btn btn-info btn-xs" href="'.(route('check_certificate.show', ['cc' => $item->app_cert_to->check->id])).'" target="_blank"> <i class="fa fa-eye"></i> </a>';  
                                }else if($item->certificate_type == 2  && !empty($item->app_cert_ib_to)){ // หน่วยตรวจสอบ
                                    $text =    '<a   class="btn btn-info btn-xs" href="'.( url('/certify/check_certificate-ib/' . $item->app_cert_ib_to->token)).'" target="_blank"> <i class="fa fa-eye"></i> </a>';  
                                }else  if($item->certificate_type ==  1  && !empty($item->app_cert_cb_to)){ // ห้องหน่วยรับรอง
                                    $text =  '<a   class="btn btn-info btn-xs" href="'.( url('/certify/check_certificate-cb/' . $item->app_cert_cb_to->token)).'" target="_blank"> <i class="fa fa-eye"></i> </a>';  
                                }
                                  return    $text;   
                            })
                            ->addColumn('preview', function ($item) {
                                $text = '';
                                    if($item->certificate_type == 3){ // ห้องปฏิบัติการ
                                            if(!empty($item->cert_export_to->certificate_newfile)){
                                                $cert = $item->cert_export_to;
                                                $text =   '<a href="'. ( url('funtions/get-view').'/'.$cert->certificate_path.'/'.$cert->certificate_newfile.'/'.$cert->certificate_no.'_'.date('Ymd_hms').'.pdf' ).'" target="_blank">
                                                                 <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                          </a> ';
                                            }else{
                                                $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->certificate_id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                                            }
                                    }else if($item->certificate_type ==  2){ // หน่วยตรวจสอบ
                                        if(!empty($item->cert_export_ib_to->certificate_newfile)){
                                            $cert = $item->cert_export_ib_to;
                                            $text =   '<a href="'. ( url('funtions/get-view').'/'.$cert->certificate_path.'/'.$cert->certificate_newfile.'/'.$cert->certificate.'_'.date('Ymd_hms').'.pdf' ).'" target="_blank">
                                                             <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                      </a> ';
                                        }else{
                                            $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->certificate_id.'/2')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                                        }
                                    }else  if($item->certificate_type ==  1){ // ห้องหน่วยรับรอง
                                            if(!empty($item->cert_export_cb_to->certificate_newfile)){
                                                $cert = $item->cert_export_cb_to;
                                                $text =   '<a href="'. ( url('funtions/get-view').'/'.$cert->certificate_path.'/'.$cert->certificate_newfile.'/'.$cert->certificate.'_'.date('Ymd_hms').'.pdf' ).'" target="_blank">
                                                                 <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                                          </a> ';
                                            }else{
                                                $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->certificate_id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                                            }
                                    }
                                return    $text;   
                             })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox','app_no','name', 'action','preview', 'status'])
                            ->make(true);
    } 

    public function getsign(Request $request)
    {
   
    
        $send  =  SendCertificateLists::whereIn('id',$request->ids)->first();
        if(!is_null($send)){
            if( !empty($send->send_certificates_to->signer_to)){
                $sign                   =  $send->send_certificates_to->signer_to;
                $send->sign_name        = !empty($send->send_certificates_to->sign_name) ? $send->send_certificates_to->sign_name : null;
                $send->sign_position    = !empty($send->send_certificates_to->sign_position) ? $send->send_certificates_to->sign_position : null;
                if( !empty($send->send_certificates_to->signer_to->AttachFileAttachTo) ){
                   $attach    = $send->send_certificates_to->signer_to->AttachFileAttachTo; 
                   if(HP::checkFileStorage($attach->url)){
                    HP::getFileStoragePath($attach->url);
                     $send->attach_sign   =  url('uploads/'.$attach->url);
                   }else{
                     $send->attach_sign   = '';
                   }
                }else{
                    $send->attach_sign   = '';
                }
                //  for ($x = 0; $x <= 100; $x++) {
                //     $input                  = [];
                //     $input['Ref_otp']       =   self::quickRandom(6);
                //     $input['otp']           =   rand(100000,999999);  
                //     $input['Req_date']      =    date('Y-m-d H:i:s'); 
                //     $input['Req_by']        =   auth()->user()->getKey(); 
                //     $input['state']         =  1; 
                //     $detail =    SignCertificateOtp::where('Ref_otp',$input['Ref_otp'])->where('otp', $input['otp'])->first();
                //     if(is_null($detail)){
                //          $otp_sign =  SignCertificateOtp::create($input);

                //          self::get_line_otp($otp_sign->Ref_otp, $otp_sign->otp,$sign->line_token);	
                //          $send->Ref_otp         = !empty($otp_sign->Ref_otp) ? $otp_sign->Ref_otp : null;
                //          $send->attach          = !empty($sign->attach) ? $sign->attach : null;
                         
                   

                         return response()->json([
                                        'message' =>  true,
                                        'send' => $send
                              ]);
                        exit;
                    // }
                //  }

            }else{
                return response()->json([
                                         'message' =>  false 
                                     ]);
            }

   
        }else{
            return response()->json([
                                     'message' =>  false 
                                 ]);
        }
    } 

    public static function getOtp(Request $request)
    {
        $send  =  SendCertificateLists::whereIn('id',$request->id)->first();
        if(!is_null($send)){
            if( !empty($send->send_certificates_to->signer_to)){
                    $sign                       =  $send->send_certificates_to->signer_to;
                    // for ($x = 0; $x <= 100; $x++) {
                        $input                  = [];
                        $input['Ref_otp']       = self::quickRandom(6);
                        $input['otp']           = rand(100000,999999);  
                        $input['Req_date']      = date('Y-m-d H:i:s'); 
                        $input['Req_by']        = auth()->user()->getKey(); 
                        $input['state']         = 1; 
                        $detail =    SignCertificateOtp::where('Ref_otp',$input['Ref_otp'])->where('otp', $input['otp'])->first();
                        if(is_null($detail)){
                            $otp_sign =  SignCertificateOtp::create($input);

                            SignCertificateOtp::where('Ref_otp',$request->ref_otp)->update(['state'=> 3]);
                            
                            $mail = auth()->user()->reg_email;
                            $app = $send->app_cert_to;
                            
                            if($mail !== null){
                                $config = HP::getConfig();
                                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                                $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
                    
                                $data_app = [
                                                'certi_lab'     => $app,
                                                'otp'           => $otp_sign->otp,
                                                'ref_otp'       => $otp_sign->Ref_otp,
                                                'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                            ];                            
                                $user = User::find($sign->user_register_id);
                
                                $mail = $user->reg_email;

                                $html = new  OtpNofitication($data_app);
                                $mail = Mail::to($mail)->send($html);
                              }

                            return response()->json([
                                                    'message' =>  true,
                                                    'Ref_otp' =>  $otp_sign->Ref_otp,
                                                ]);
                            exit;
                        }
                    // }
                }else{
                    return response()->json([
                                             'message' =>  false 
                                         ]);
                }
        }else{
            return response()->json([
                                     'message' =>  false 
                                 ]);
        }
    } 

    public static function getOtpTimeOut(Request $request)
    {

        SignCertificateOtp::where('Ref_otp',$request->ref_otp)->update(['state'=> 3]);	
        return response()->json([
                                  'message' =>  true 
                              ]);
    } 

    public static function getCheckOtp(Request $request)
    {

        $sign =  SignCertificateOtp::where('Ref_otp',$request->ref_otp)->where('otp',$request->otp)->first();
        if(!is_null($sign)){
            $sign->state            = 2;
            $sign->Confirm_date     = date('Y-m-d H:i:s');
            $sign->Confirm_by       = auth()->user()->getKey();
            $sign->save();

            if(count($request->ids) > 0){
                foreach($request->ids as $index => $item){
                    $confirm = SignCertificateConfirms::where('send_certificate_list_id',$item)->first();
                    if(is_null($confirm)){
                        $confirm = new SignCertificateConfirms;
                    }
                    $confirm->send_certificate_list_id  = $item;
                    $confirm->certificate_otp_id        = $sign->id;
                    $confirm->save();
                }
            }
            return response()->json([
                                      'message' =>  true,
                                      'otp_id'  =>  $sign->id
                                   ]);
        }else{
            return response()->json([
                                        'message' =>  false 
                                     ]);
        }
        
      
    } 

    
    public  function save_sign(Request $request)
    {
        //  dd('ok');
         $otp_id  =   $request->otp_id;
         if(count($request->ids) > 0){
                $send_list  =  SendCertificateLists::whereIn('id',$request->ids)->first();
                if(isset($send_list)){
                    
                    if ($request->hasFile('attach_sign')) {
                        $file_upload =   HP::singleFileUpload(
                                            $request->file('attach_sign') ,
                                            $this->attach_path,
                                            !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                                            (auth()->user()->FullName ?? null),
                                            'Center',
                                            (  (new SendCertificateLists)->getTable() ),
                                                $send_list->id,
                                            'attach_sign',
                                            'ไฟล์แนบลายเซ็น'
                                      );
                       HP::getFileStoragePath($file_upload->url);            
                       $sign_path = $file_upload->url;                                   
                    }else if(!empty($send_list->send_certificates_to->signer_to->AttachFileAttachTo)){
                       $sign_path  = $send_list->send_certificates_to->signer_to->AttachFileAttachTo->url;  
                    }else{
                       $sign_path = null;
                    }
            
                }else{
                    $sign_path = null;
                }
             

            foreach($request->ids as $id){
                    $send_list = SendCertificateLists::findOrFail($id);
                    if(!is_null($send_list)){
                        //   $send_list->sign_status = 2;    
                             $send_list->sign_path = $sign_path;
                             $send_list->save();

                            // อัพเดท ออกให้ ณ วันที่ ใบรับรอง
                            if($send_list->certificate_type == 3){ //ห้องปฏิบัติการ
                                $cer_exp = CertificateExport::findOrFail($send_list->certificate_id);
                                $cer_exp->certificate_date_start    =  date('Y-m-d');
                            }else if($send_list->certificate_type ==2){ // หน่วยตรวจสอบ
                                $cer_exp = CertiIBExport::findOrFail($send_list->certificate_id);
                                $cer_exp->date_start                 =  date('Y-m-d');
                            }else {  // ห้องหน่วยรับรอง
                                $cer_exp = CertiCBExport::findOrFail($send_list->certificate_id);
                                $cer_exp->date_start                =  date('Y-m-d');
                            }
                            
                            if(!is_null($cer_exp)){
                                $cer_exp->save();
                            }   

                             $cer  = self::save_cer($send_list->certificate_type,$send_list->certificate_id,'F');
                             if($cer ==  'อัพเดพเรียบร้อยแล้ว'){
                                $send_list->sign_status = 3;
                                $send_list->save();
                                // อัพเดพสถานะ 
                                self::update_status_send($send_list->send_certificate_id);
 

                             }else{
                                $send_list->sign_status = 4;
                                $send_list->save();
                             }
                    }
            }

         }
      
    } 

    public static  function save_cer($certificate_type,$id,$type ="I")
    {
  
        $i = 1;
        start:
        $send       = new SendCertificatesController;
        $data_pdf   = $send->view_pdf($id,$certificate_type,$type);

        if($type = 'F'){
         
         if($i <= 3){
     
            if(!empty($data_pdf->file_path)){
                $datas  =   HP_DGA::getRegister($data_pdf->file_path,$data_pdf->path,$certificate_type,$data_pdf->certificate_no);  // ,$data_pdf->attachment
           
                if(!empty($datas->SignatureID)){
                    if($certificate_type == 3){ //ห้องปฏิบัติการ
                        $cer_exp = CertificateExport::findOrFail($id);
                    }else if($certificate_type ==2){ // หน่วยตรวจสอบ
                        $cer_exp = CertiIBExport::findOrFail($id);
                    }else {  // ห้องหน่วยรับรอง
                        $cer_exp = CertiCBExport::findOrFail($id);
                    }
             
                    if(!is_null($cer_exp)){
                        $cer_exp->certificate_path 	    =  $data_pdf->attach_path;
                        $cer_exp->certificate_file 	    =  !empty($data_pdf->file_path) ? basename($data_pdf->file_path) : null ;
                        $cer_exp->certificate_newfile 	=  $datas->Certificate_newfile;
                        $cer_exp->documentId 	        =  $datas->DocumentID;
                        $cer_exp->signtureid 	        =  $datas->SignatureID;
                        $cer_exp->status 	            =  3;//ลงนามใบรับรองระบบงานเรียบร้อย
                        $cer_exp->save();

                        if($certificate_type == 3){ //ห้องปฏิบัติการ
                            $app = CertiLab::findOrFail($cer_exp->certificate_for);
                        }else if($certificate_type ==2){ // หน่วยตรวจสอบ
                            $app = CertiIb::findOrFail($cer_exp->app_certi_ib_id);
                        }else {  // ห้องหน่วยรับรอง
                            $app = CertiCb::findOrFail($cer_exp->app_certi_cb_id);
                        }

                        if(!is_null($app) && $app->status <= 19){ 
                            $app->status  =  19 ;  // ลงนามเรียบร้อย  
                            $app->save();
                        }
                 
                     
                        //  อัพไฟล์ ftp
                        $file_path    = $data_pdf->path.'/'.$cer_exp->certificate_newfile;
                        $file         = file_get_contents($file_path);
                        $file_ftp     = Storage::put($data_pdf->attach_path.'/'.$cer_exp->certificate_newfile , $file);
                        // if($file_ftp == true && !empty($data_pdf->list_id)){
                            self::save_history($data_pdf,$cer_exp,$certificate_type);
                        // }

                        return 'อัพเดพเรียบร้อยแล้ว';
              
                    }
                }else{
                   $i ++;
                   goto start;
                }
             }else{
                $i ++;
                goto start;
             }
          }else{
            $i ++;
            goto start;
         }
             return 'เกิดข้อผิดพลาด';   
        }
      
    } 
    
    public static  function save_new_cer(Request $request)
    {
        // dd('ok');
        $id                 = $request->input('certificate_id');
        $certificate_type   = $request->input('certificate_type');
        $type               = $request->input('type');
        $i = 1;
        start:
        $send       = new SendCertificatesController;
        $data_pdf   = $send->view_pdf($id,$certificate_type,$type);
       
        if($type = 'F'){
            
         if($i <= 3){
     
            if(!empty($data_pdf->file_path)){
                // dd('data_pdf',$data_pdf,$type,$i,$data_pdf->file_path);
                $datas  =   HP_DGA::getRegister($data_pdf->file_path,$data_pdf->path,$certificate_type,$data_pdf->certificate_no);  // ,$data_pdf->attachment
                
                if(!empty($datas->SignatureID)){
                    if($certificate_type == 3){ //ห้องปฏิบัติการ
                        $cer_exp = CertificateExport::findOrFail($id);
                    }else if($certificate_type ==2){ // หน่วยตรวจสอบ
                        $cer_exp = CertiIBExport::findOrFail($id);
                    }else {  // ห้องหน่วยรับรอง
                        $cer_exp = CertiCBExport::findOrFail($id);
                    }
                    
                    if(!is_null($cer_exp)){
                      
                        // เพิกถอนการใช้งานเอกสาร
                          $history  =  SendCertificateHistory::where('certificate_id',$id)->where('certificate_type',$certificate_type)->whereNull('status_revoke')->orderby('id','desc')->first();
                          if(!is_null($history)){
                            // dd($history);
                                $revoked =    HP_DGA::getRevoked($history->documentId,'ยกเลิกใช้งานเอกสาร');
                                if(!empty($revoked->Result)){
                                    $history->certificate_oldfile = $history->certificate_newfile ?? null;
                                    $history->certificate_newfile = $datas->Certificate_newfile ?? null;
                                    $history->status_revoke = '1';
                                    $history->reason_revoke = 'ยกเลิกใช้งานเอกสาร';
                                    $history->date_revoke   =  date("Y-m-d H:i:s") ;
                                    $history->user_revoke   =  auth()->user()->getKey();
                                    $history->save();
                                }
                          } 
                        //   dd($history );
                        
                        $cer_exp->certificate_path 	    =  $data_pdf->attach_path;
                        $cer_exp->certificate_file 	    =  !empty($data_pdf->file_path) ? basename($data_pdf->file_path) : null ;
                        $cer_exp->certificate_newfile 	=  $datas->Certificate_newfile;
                        $cer_exp->documentId 	        =  $datas->DocumentID;
                        $cer_exp->signtureid 	        =  $datas->SignatureID;
                        // $cer_exp->status 	            =  3;//ลงนามใบรับรองระบบงานเรียบร้อย
                        $cer_exp->save();

 
                        //  อัพไฟล์ ftp
                        $file_path    = $data_pdf->path.'/'.$cer_exp->certificate_newfile;

                       
                        $file         = file_get_contents($file_path);
                        $file_ftp     = Storage::put($data_pdf->attach_path.'/'.$cer_exp->certificate_newfile , $file);
                        
                        self::save_history($data_pdf,$cer_exp,$certificate_type);
                    
                        $object = (object)[];
                        $object->documentId 	        =  $datas->DocumentID;
                        $object->signtureid 	        =  $datas->SignatureID;

                          return response()->json([
                                                    'message' =>  true,
                                                    'datas'  =>  $object
                                                 ]);
                    }else{
                        return response()->json([
                                                'message' =>  false,
                                                'datas'  =>  []
                                              ]);
                    }
                }else{
                   $i ++;
                   goto start;
                }
             }else{
                $i ++;
                goto start;
             }
          }else{
            $i ++;
            goto start;
         }
             return 'เกิดข้อผิดพลาด';   
        }
      
    } 
    

    
    public static  function save_history($data_pdf,$cer_exp,$certificate_type)
    {
        $cer_type = ['1'=>(new CertiCBExport)->getTable(),'2'=>(new CertiIBExport)->getTable(),'3'=>(new CertificateExport)->getTable()];
        // $history =   SendCertificateHistory::where('send_certificate_list_id',$data_pdf->list_id)->first();
        // if(is_null($history)){
            $history = new SendCertificateHistory;
            $history->created_by = auth()->user()->getKey();
        // }else{
            // $history->updated_by = auth()->user()->getKey();
        // }
             $history->send_certificate_list_id = $data_pdf->list_id;
             $history->certificate_type         = $certificate_type;
             $history->certificate_tb           = array_key_exists($certificate_type,$cer_type) ? $cer_type[$certificate_type] : null; 
             $history->certificate_id           = $cer_exp->id ?? null;

             $history->certificate_path         = $cer_exp->certificate_path  ?? null;
             $history->certificate_file         = $cer_exp->certificate_file  ?? null;
             $history->certificate_newfile      = $cer_exp->certificate_newfile  ?? null;
             $history->documentId               = $cer_exp->documentId  ?? null;
             $history->signtureid               = $cer_exp->signtureid  ?? null;

             $history->app_no                   = $data_pdf->app_no ?? null;
             $history->name                     = $data_pdf->name  ?? null;
             $history->tax_id                   = $data_pdf->tax_id  ?? null;
             $history->sign_id                  = $data_pdf->sign_id  ?? null;
             $history->certificate_no           = $data_pdf->certificate_no ?? null;
             $history->status                   =  '1';
             $history->save();
    } 
    


    public static  function update_status_send($id)
    {
            $send_list = SendCertificateLists::where('send_certificate_id',$id);
 
            $send = SendCertificates::findOrFail($id);  
            if(!is_null($send)){
                if($send_list->whereIn('sign_status',[3,4])->get()->count() == $send_list->get()->count()){
                    $send->state           = 3; //ลงนามใบรับรองเรียบร้อย
                }else{
                    $send->state           = 2; // อยู่ระหว่างยืนยันการลงนาม
                }
                $send->save();
            }  
            return  $send;
    } 
    


    public static  function check_update_sign(Request $request)
    {
        // dd('ok');
        $send_list  =  SendCertificateLists::whereIn('id',$request->ids)->whereIn('sign_status',[3,4])->get()->count(); 
        return response()->json([
                                    'message' =>  true,
                                    'count'  =>  $send_list
                                 ]);

    } 
    


    
    public  static function get_line_otp($otp,$token,$token_otp){
        define('LINE_API',"https://notify-api.line.me/api/notify");
        $message = "OTP=$otp [รหัสอ้างอิง:$token] \n เพื่อใช้งานระบบใบรับรองอิเล็กทรอนิกส์ \n ภายใน3นาที"; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
   
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData,'','&');
        $headerOptions = array( 
              'http'=>array(
               'method'=>'POST',
               'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                       ."Authorization: Bearer ".$token_otp."\r\n"
                       ."Content-Length: ".strlen($queryData)."\r\n",
               'content' => $queryData
              ),
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents(LINE_API,FALSE,$context);
        $res = json_decode($result);
        return $res;
   }
   
   public static function quickRandom($length = 6)
   {
       $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
       return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
   }
   


   public static  function save_cancel(Request $request)
   {
       $id                 = $request->get('certificate_id');
       $certificate_type   = $request->get('certificate_type');
       $remark             = $request->get('remark');
       $message = false;
         if($certificate_type == 3 || $certificate_type == 4){ //ห้องปฏิบัติการ
            $cer_exp = CertificateExport::findOrFail($id);
            $certificate_type = 3;
        }else if($certificate_type ==2){ // หน่วยตรวจสอบ
            $cer_exp = CertiIBExport::findOrFail($id);
        }else {  // ห้องหน่วยรับรอง
            $cer_exp = CertiCBExport::findOrFail($id);
        }

        // เพิกถอนการใช้งานเอกสาร
        $history  =  SendCertificateHistory::where('certificate_id',$id)->where('certificate_type',$certificate_type)->whereNull('status_revoke')->orderby('id','desc')->first();
        if(!is_null($history)){
            $revoked =    HP_DGA::getRevoked($history->documentId,$remark);
            if(!empty($revoked->Result)){
                $history->certificate_oldfile = $history->certificate_newfile ?? null;
                $history->certificate_newfile = null;
                $history->status_revoke = '1';
                $history->reason_revoke = $remark;
                $history->date_revoke   =  date("Y-m-d H:i:s") ;
                $history->user_revoke   =  auth()->user()->getKey();
                $history->save();

                $cer_exp->status_revoke = '1';
                $cer_exp->reason_revoke = $remark;
                $cer_exp->date_revoke   =  date("Y-m-d H:i:s") ;
                $cer_exp->user_revoke   =  auth()->user()->getKey();
                $cer_exp->status = '5';
                $cer_exp->save();
                $message = true;

            }
        }else{

            $cer_exp->status_revoke = '1';
            $cer_exp->reason_revoke = $remark;
            $cer_exp->date_revoke   =  date("Y-m-d H:i:s") ;
            $cer_exp->user_revoke   =  auth()->user()->getKey();
            $cer_exp->status = '5';
            $cer_exp->save();
            $message = true;
        } 


        return response()->json([ 'message' =>  $message, 'data'  =>  $cer_exp ]);

   } 
   



 
}



