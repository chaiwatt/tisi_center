<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

use Yajra\Datatables\Datatables;
use HP;
use HP_DGA;
use QrCode;
use Mpdf\Mpdf;

use App\Models\Bcertify\Formula;

use App\Models\Certify\SendCertificateHistory;
use App\Models\Certify\SendCertificates;
use App\Models\Certify\SendCertificateLists;

use App\Models\Sso\User AS SSO_User;

use App\CertificateExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\ApplicantIB\CertiIBFileAll;

use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\ApplicantCB\CertiCb;

use App\Http\Controllers\Certify\SendCertificatesController;

use HP_Law;
use stdClass;
use Segment;

class LogesignauresController extends Controller
{

    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('cerreport-logesignaures','-');
    }

    public function index(Request $request)
    {
        $model = str_slug('cerreport-logesignaures','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('cerreport.logesignaures.index');
        }
        abort(403);
    }


    public function data_list(Request $request)
    {
        
        $filter_search  = $request->input('filter_search');
        $filter_certify = $request->input('filter_certify');

        $query = SendCertificateHistory::query()    
                                    ->whereNotNull('certificate_type')                   
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search ); 
                                        $query->where(function ($query2) use($search_full) {
                                                return $query2->where(DB::raw("REPLACE(app_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->Orwhere(DB::raw("REPLACE(certificate_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->Orwhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->Orwhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%");
                                        });
                                    }) 
                                    ->when($filter_certify, function ($query, $filter_certify){
                                        return  $query->where('certificate_type', $filter_certify);
                                    })
                                    ->groupBy('certificate_tb','certificate_id');

     
    return Datatables::of($query)
                        ->addIndexColumn()
                        ->addColumn('app_no', function ($item) {
                            return $item->app_no ?? '';
                        })
                        ->addColumn('certificate_no', function ($item) {
                            $history  =  SendCertificateHistory::where('certificate_id',$item->certificate_id)->where('certificate_type',$item->certificate_type)->orderby('id','desc')->first();
                            if(!is_null($history)){
                                return $history->certificate_no ?? '';
                            }else{
                                return $item->certificate_no ?? '';
                            }
                        })
                        ->addColumn('name', function ($item) {
                            $text =  !empty($item->name)? $item->name:'';
                            $text .= !empty($item->tax_id)? '<br/>'.$item->tax_id:'';
                            return $text;
                        })
                        ->addColumn('certify', function ($item) {
                            return  !empty($item->CertifyTitle) ? $item->CertifyTitle :null;
                        })
                        ->addColumn('certification', function ($item) {
                            $text     = '';
                            if(auth()->user()->can('edit-'. $this->permission)) {
                                $data =  'data-certificate_type="'.($item->certificate_type).'"';
                                $data .= 'data-certificate_id="'.($item->certificate_id).'"';
                                $text .= '<a href="#" onclick="return false;" class="send_esignaures"  style="color:#33cc33" '.($data).' >สร้างใหม่</a>';
                            }
                            $history  = SendCertificateHistory::where('certificate_id',$item->certificate_id)->where('certificate_type',$item->certificate_type)->orderby('id','desc')->first();
                            if(!is_null($history)){
                                $url = url('funtions/get-view').'/'.$history->certificate_path.'/'.$history->certificate_newfile.'/'.$history->certificate_no.'_'.date('Ymd_hms').'.pdf';
                                $text .= '<a class="a_certification ml-15"  href="'. (  $url ).'" target="_blank"> <img src="'.(asset('images/icon-certification.jpg')).'" width="25px"  class="img_certification"></a> ';
                            }
                            return   $text;
                        })
                        ->addColumn('status', function ($item) {
                            $text =   !empty($item->StatusHtml) ? $item->StatusHtml :'';
                            $historys  =  SendCertificateHistory::where('certificate_id',$item->certificate_id)->where('certificate_type',$item->certificate_type)->get();
                            if(count($historys) >= 2){
                                $data =  'data-certificate_type="'.($item->certificate_type).'"';
                                $data .= 'data-certificate_id="'.($item->certificate_id).'"';
                                $text .= '<br/><a href="#" onclick="return false;" style="color:#ffb84d;"  class="log_cer_moao"  '.( $data ).' >    ประวัติสร้างใหม่ </a>';
                            }
                            return $text;
                        })
                        ->addColumn('action', function ($item) {
                            $action = '';

                            if(auth()->user()->can('view-'. $this->permission) ) {
                                $action .= '<a href="' .( url('/cerreport/logesignaures/'.$item->id) ). '" title="รายละเอียด" class="btn btn-icon btn-circle btn-light-info"> <i class="fa fa-eye"></i></a>';
                            }

                            if(auth()->user()->can('edit-'. $this->permission)) {

                                $action .= ' <a href="' .( url('/cerreport/logesignaures/'.$item->id.'/edit') ). '" title="แก้ไข" class="btn btn-icon btn-circle btn-light-warning"> <i class="fa fa-pencil-square-o"></i></a>';
                                $data   =  'data-certificate_type="'.($item->certificate_type).'"';
                                $data   .= 'data-certificate_id="'.($item->certificate_id).'"';
                                $action .= '<span class="send_sign" '.($data).' > <img src="'.(asset('icon/icon_send_email.jpg')).'" width="28px"  > </span>';
                            }

                            return   $action;
                        })
                        ->order(function ($query) {
                            $query->orderBy('id','desc');
                        })
                        ->rawColumns(['checkbox', 'name', 'certification', 'status' ,'action'])
                        ->make(true); 
                                    
    }

    public function datas_cer(Request $request)
    {   
        $message = false;
        $datas   =  [];
        $certificate_id     = $request->input('certificate_id');
        $certificate_type   = $request->input('certificate_type');
        $query = SendCertificateHistory::where('certificate_id',$certificate_id)->where('certificate_type',$certificate_type)->get();
        if(count($query) > 0){
            foreach($query as $itme){
                $object                         = (object)[]; 
                $object->certificate_oldfile 	=  !empty($itme->certificate_oldfile)?  $itme->certificate_oldfile : '';
                $object->certificate_newfile    =  !empty($itme->certificate_newfile)?  $itme->certificate_newfile : '';
                $object->certificate_path       =  !empty($itme->certificate_path)?  $itme->certificate_path : '';
                $object->certificate_no         =   $itme->certificate_no.'_'.date('Ymd_hms').'.pdf';
                $object->user_created 	        =  !empty($itme->user_created->FullName) ?  $itme->user_created->FullName : '';
                $object->date_revoke 	        =  !empty($itme->date_revoke)?  HP::DateTimeThai($itme->date_revoke) : '';
                $datas[]                        =   $object;
            }
            $message = true;
        }
        return response()->json([ 'message' =>  true, 'datas'  =>  $datas ]);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'. $this->permission)) {
            $certificate = SendCertificateHistory::findOrFail($id);
            return view('cerreport.logesignaures.edit',compact('certificate'));
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'. $this->permission)) {
            $certificate = SendCertificateHistory::findOrFail($id);
            return view('cerreport.logesignaures.show',compact('certificate'));
        }
        abort(403);
    }

    public function GetAddress(Request $request)
    {
        $id          = $request->input('id');
        $address     = $request->input('address');

        $certificate = SendCertificateHistory::find($id);

        $data                = new stdClass;
        if( !empty($certificate) ){

            if( in_array($certificate->certificate_type, [1]) ){

                $certify = CertiCBExport::find($certificate->certificate_id);
                $app     = !is_null($certify->applications)?$certify->applications:null;
                if( in_array($address, [2]) && !empty(  $app )){ //ที่อยู่สาขา
                    $data->address       = $app->address_no ?? null;
                    $data->allay         = $app->allay ?? null;
                    $data->village_no    = $app->village_no ?? null;
                    $data->road          = $app->road ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress($app->district_id, $app->amphur_id, $app->province_id, $app->postcode);

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;

                }else if( in_array($address, [1]) && !empty(  $app )){// ที่อยู่บริษัท
                    $data->address       = $app->EsurvTrader->address_no ?? null;
                    $data->allay         = $app->EsurvTrader->moo ?? null;
                    $data->village_no    = $app->EsurvTrader->soi ?? null;
                    $data->road          = $app->EsurvTrader->street ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress(($app->EsurvTrader->subdistrict ?? null), ($app->EsurvTrader->district ?? null), ($app->EsurvTrader->province ?? null), ( $app->EsurvTrader->zipcode ?? null ));

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;
                }


            }else if( in_array($certificate->certificate_type, [2]) ){

                $certify = CertiIBExport::find($certificate->certificate_id);
                $app     = !is_null($certify->CertiIBCostTo)?$certify->applications:null;
                if( in_array($address, [2]) && !empty(  $app )){ //ที่อยู่สาขา
                    $data->address       = $app->address_no ?? null;
                    $data->allay         = $app->allay ?? null;
                    $data->village_no    = $app->village_no ?? null;
                    $data->road          = $app->road ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress($app->district_id, $app->amphur_id, $app->province_id, $app->postcode);

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;

                }else if( in_array($address, [1]) && !empty(  $app )){// ที่อยู่บริษัท
                    $data->address       = $app->EsurvTrader->address_no ?? null;
                    $data->allay         = $app->EsurvTrader->moo ?? null;
                    $data->village_no    = $app->EsurvTrader->soi ?? null;
                    $data->road          = $app->EsurvTrader->street ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress(($app->EsurvTrader->subdistrict ?? null), ($app->EsurvTrader->district ?? null), ($app->EsurvTrader->province ?? null), ( $app->EsurvTrader->zipcode ?? null ));

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;
                }


            }else if( in_array($certificate->certificate_type, [3]) ){

                $certify = CertificateExport::find($certificate->certificate_id);
        
                $app     = !is_null($certify->applications)?$certify->applications:null;
                if( in_array($address, [2]) && !empty(  $app )){ //ที่อยู่สาขา
                    $data->address       = $app->address_no ?? null;
                    $data->allay         = $app->allay ?? null;
                    $data->village_no    = $app->village_no ?? null;
                    $data->road          = $app->road ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress($app->district, $app->amphur, $app->province, $app->postcode);

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;

                }else if( in_array($address, [1]) && !empty(  $app )){// ที่อยู่บริษัท
                    $data->address       = $app->EsurvTrader->address_no ?? null;
                    $data->allay         = $app->EsurvTrader->moo ?? null;
                    $data->village_no    = $app->EsurvTrader->soi ?? null;
                    $data->road          = $app->EsurvTrader->street ?? null;

                    $GetDataAddress = HP_Law::GetDataAddress(($app->EsurvTrader->subdistrict ?? null), ($app->EsurvTrader->district ?? null), ($app->EsurvTrader->province ?? null), ( $app->EsurvTrader->zipcode ?? null ));

                    $data->province_name = !empty($GetDataAddress->province_name) ?$GetDataAddress->province_name: null;
                    $data->province_name_en = !empty($GetDataAddress->province_name_en) ?$GetDataAddress->province_name_en: null;

                    $data->district_name   = !empty($GetDataAddress->district_name) ?$GetDataAddress->district_name: null;
                    $data->district_name_en   = !empty($GetDataAddress->district_name_en) ?$GetDataAddress->district_name_en: null;

                    $data->subdistrict_name = !empty($GetDataAddress->subdistrict_name) ?$GetDataAddress->subdistrict_name: null;
                    $data->subdistrict_name_en = !empty($GetDataAddress->subdistrict_name_en) ?$GetDataAddress->subdistrict_name_en: null;

                    $data->postcode      = !empty($GetDataAddress->zipcode) ?$GetDataAddress->zipcode: null;
                }

            }

            return response()->json([ 'message' =>  true, 'data'  =>  $data ]);

        }

    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $send_certify = SendCertificateHistory::findOrFail($id);
            $requestData  = $request->all();

            $certificate_id   = $request->input('certificate_id');
            $certificate_type = $request->input('certificate_type');

            if(  in_array($send_certify->certificate_type, [1] ) ){// ห้องหน่วยรับรอง
                $cer_exp  = CertiCBExport::findOrFail($certificate_id);
                $cer_exp->update($requestData);
            }else if(  in_array($send_certify->certificate_type, [2] ) ){// หน่วยตรวจสอบ
                $cer_exp = CertiIBExport::findOrFail($certificate_id);
                $cer_exp->update($requestData);
            }else if(  in_array($send_certify->certificate_type, [3] ) ){//ห้องปฏิบัติการ
                $cer_exp = CertificateExport::findOrFail($certificate_id);
                $cer_exp->update($requestData);
            }

            $type             = 'F';
            $i                = 1;
            start:
            $SendController   = new SendCertificatesController;
            $DataPdf          = $SendController->view_pdf( $certificate_id ,$certificate_type, $type );

            if( $type = 'F' ){
         
                if($i <= 3){
            
                    if(!empty($DataPdf->file_path)){
                        $datas  =   HP_DGA::getRegister($DataPdf->file_path,$DataPdf->path,$certificate_type,$DataPdf->certificate_no);  // ,$DataPdf->attachment

                        if(!empty($datas->SignatureID)){
         
                            // เพิกถอนการใช้งานเอกสาร
                            $history  =  SendCertificateHistory::where('certificate_id',$certificate_id)->where('certificate_type',$certificate_type)->whereNull('status_revoke')->orderby('id','desc')->first();
                            if(!is_null($history)){
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

                            $cer_exp->certificate_path 	    =  $DataPdf->attach_path;
                            $cer_exp->certificate_file 	    =  !empty($DataPdf->file_path) ? basename($DataPdf->file_path) : null ;
                            $cer_exp->certificate_newfile 	=  $datas->Certificate_newfile;
                            $cer_exp->documentId 	        =  $datas->DocumentID;
                            $cer_exp->signtureid 	        =  $datas->SignatureID;
                            $cer_exp->save();
                            //  อัพไฟล์ ftp
                            $file_path    = $DataPdf->path.'/'.$cer_exp->certificate_newfile;
                            $file         = file_get_contents($file_path);
                            $file_ftp     = Storage::put($DataPdf->attach_path.'/'.$cer_exp->certificate_newfile , $file);

                             
                            self::save_history($DataPdf,$cer_exp,$certificate_type);

                            $object = (object)[];
                            $object->documentId 	        =  $datas->DocumentID;
                            $object->signtureid 	        =  $datas->SignatureID;
                            return redirect('cerreport/logesignaures')->with('success_message', ('<div>DocumentID:'.$datas->DocumentID.'<div>'.'<div>SignatureID:'.$datas->SignatureID.'<div>'));
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
                return redirect('cerreport/logesignaures/'.$send_certify->id.'/edit')->with('success_message', 'เกิดข้อผิดพลาด!');
            }

        }
        abort(403);
    }

    public static  function save_history($data_pdf, $cer_exp, $certificate_type)
    {
        $cer_type = ['1'=>(new CertiCBExport)->getTable(),'2'=>(new CertiIBExport)->getTable(),'3'=>(new CertificateExport)->getTable()];
        $history = new SendCertificateHistory;
        $history->created_by = auth()->user()->getKey();

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

    public function view_pdf(Request $request)
    {

        $requestData = $request->all();
        $id          = !empty($requestData['certificate_id'])?$requestData['certificate_id']:null;
        $cer         = !empty($requestData['certificate_type'])?$requestData['certificate_type']:null;

        $type        = 'I';
  
        $mpdf = new Mpdf([
            'PDFA' 	            =>  $type == 'F' ? true : false,
            'PDFAauto'	        =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
        ]);         

        if($type == 'I'){
            $mpdf->SetWatermarkText("DRAFT");
            $mpdf->watermark_font     = 'DejaVuSansCondensed';
            $mpdf->showWatermarkText  = true;
            $mpdf->watermarkTextAlpha = 0.15;
        }

        if( in_array($cer, [3]) ){ // ห้องปฏิบัติการ LAB

            $cer_exp_lab = CertificateExport::findOrFail($id);

            if(!is_null($cer_exp_lab)){

                $certi_lab = CertiLab::findOrFail($cer_exp_lab->certificate_for);

                //Set PDF
                $requestData['certificate_date_start'] = !empty($requestData['certificate_date_start'])?HP::convertDate($requestData['certificate_date_start'],true):null;
                $requestData['certificate_date_end']   = !empty($requestData['certificate_date_end'])?HP::convertDate($requestData['certificate_date_end'],true):null;
                $requestData['certificate_date_first'] = !empty($requestData['certificate_date_first'])?HP::convertDate($requestData['certificate_date_first'],true):null;
                foreach( $requestData AS $Keyname => $Irequest ){

                    if( Schema::hasColumn( $cer_exp_lab->getTable(), $Keyname ) ){
                        $cer_exp_lab->{$Keyname} =  $Irequest;
                    }
                 
                }

                if(!is_null($certi_lab)){
                    $no      = '17025';
                    $formula = Formula::where('title', 'like', '%'.$no.'%')->whereState(1)->first();

                    //ข้อมูลภาพ QR Code
                    if( !is_null($certi_lab->attach_pdf) && $certi_lab->attach_pdf != '' ){
                        $url       =       url('/certify/check_files_lab/'. rtrim(strtr(base64_encode($certi_lab->id), '+/', '-_'), '=') );
                        //ข้อมูลภาพ QR Code
                        $image_qr  = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)->size(500)->errorCorrection('H')->generate($url);
                    }

                    if(!empty($cer_exp_lab->set_format)){
                        $set_format = explode(",",$cer_exp_lab->set_format);
                        if(!empty($set_format)){
                            $format_name        =   !empty($set_format[0]) ?  $set_format[0] : '80';
                            $format_names_en    =   !empty($set_format[1]) ?  $set_format[1] : '135';
                            $format_address_th  =   !empty($set_format[2]) ?  $set_format[2] :  '75';
                            $format_address_en  =   !empty($set_format[3]) ?  $set_format[3] :  '120';
                        }else{
                            $format_name = '80';
                            $format_names_en = '135';
                            $format_address_th = '75';
                            $format_address_en = '120';
                        }
                    }else{
                            $format_name = '80';
                            $format_names_en = '135';
                            $format_address_th = '75';
                            $format_address_en = '120';
                    }       
    
                      // ชื่อภาษาไทย
                      $names         = !empty($cer_exp_lab->lab_name) ?  self::format_data($cer_exp_lab->lab_name,$format_name)  : [];
   
                    // ชื่อภาษาอังกฤษ
                     $names_en     = !empty($cer_exp_lab->lab_name_en) ?  self::format_data2('('.$cer_exp_lab->lab_name_en.')',$format_names_en)  : [];
                    
                    // ตั้งอยู่เลขที่ Th
                     $address_th  = self::FormatAddress($cer_exp_lab);
                     $address_ths =      !empty($address_th) ?  self::format_data($address_th,$format_address_th)  : [];

                     // ตั้งอยู่เลขที่ en
                     $address_en  = self::FormatAddressEn($cer_exp_lab);
                     $address_ens =  !empty($address_en) ?  self::format_data2($address_en,$format_address_en)  : [];

                     $condition_th = !empty($formula->condition_th) ? self::format_data($formula->condition_th,65)   : [] ;
                     $condition_en = !empty($formula->condition_en) ? self::format_data2($formula->condition_en,120)   : [] ;
                   
                    $send_list = SendCertificateLists::where('certificate_id',$cer_exp_lab->id)->where('certificate_tb', (new CertificateExport)->getTable())->first();

                    if(!is_null($send_list) && !empty($send_list->sign_path)){
                        $image         = public_path('uploads/') .$send_list->sign_path;
                        $image_info    = getimagesize($image);
                        list($width, $height, $types, $attr) = $image_info;
                        $height 	   = round($width*$image_info[1]/$image_info[0]);

                        if($height <= 2000){
                            $heights   = 50;
                            $widths    = round($heights*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="50px" width="'.$widths.'px">';
                        }else if($height > 2000){
                            $heights   = 100;
                            $widths    = round($heights*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="100px" width="'.$widths.'px">';
                        }else{
                            $widths  = round($height*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="'.$height.'px" width="'.$widths.'px">';
                        }
                        $sign_name     = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                        $sign_position = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;

                    }else{
                        $sign_path     =   '<span style="color:#ffffff;"> &emsp;</span>';
                        $sign_name     = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                        $sign_position = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                    }

                    $data_export = [
                                        'certificate'         => $cer_exp_lab->certificate_no ?? null ,
                                        'app_no'              => $certi_lab->app_no,
                                        'names'               => $names,  
                                        'names_en'            => $names_en,
                                        'address_ths'         => $address_ths,
                                        'address_ens'         => !empty($address_en) ? '('.$address_en.')' : '' ,
                                        'formula'             => isset($cer_exp_lab->formula) ?   $cer_exp_lab->formula : '', 
                                        'formula_en'          => isset($cer_exp_lab->formula_en) ?   $cer_exp_lab->formula_en : '',
                                        'condition_th'        => $condition_th ,
                                        'condition_en'        => !empty($formula->condition_en) ? '('.$formula->condition_en.')' : '' ,
                                        'accereditatio_no'    => $cer_exp_lab->accereditatio_no,
                                        'accereditatio_no_en' => $cer_exp_lab->accereditatio_no_en,
                                        'date_start'          => $cer_exp_lab->certificate_date_start,
                                        'date_start_en'       => !empty($cer_exp_lab->certificate_date_start) ? HP::formatDateENertify($cer_exp_lab->certificate_date_start) : null
                                    ];

                    $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                    $mpdf->AddPage('P');
                    $html  = view('certify/send-certificates/pdf.lab', $data_export);
                    $mpdf->WriteHTML($html);

                    $set_footer = [
                                        'image_qr'          => isset($image_qr) ? $image_qr : null,
                                        'url'               => isset($url) ? $url : null,
                                        'sign_path'         =>  $sign_path,
                                        'sign_name'         =>  $sign_name,
                                        'sign_position'     =>  $sign_position,
                                        'sign_instead'      =>  $cer_exp_lab->sign_instead ?? null
                                    ];

                    $footer  = view('certify/send-certificates/pdf.footer-lab',$set_footer);

                    $mpdf->SetHTMLFooter($footer);
  
                    $title = "ใบรับรองห้องปฏิบัติการ".date('Ymd_hms').".pdf";  
                    $mpdf->SetTitle($title);
                    $mpdf->Output($title, $type);

                }

            }

        }else if(  in_array($cer, [2]) ){ //หน่วยตรวจสอบ IB

            $cer_exp_ib = CertiIBExport::findOrFail($id);

            if(!is_null($cer_exp_ib)){
                $certi_ib = CertiIb::findOrFail($cer_exp_ib->app_certi_ib_id);

                //Set PDF
                $requestData['date_start'] = !empty($requestData['date_start'])?HP::convertDate($requestData['date_start'],true):null;
                $requestData['date_end']   = !empty($requestData['date_end'])?HP::convertDate($requestData['date_end'],true):null;
                foreach( $requestData AS $Keyname => $Irequest ){
                    if( Schema::hasColumn( $cer_exp_ib->getTable(), $Keyname ) ){
                        $cer_exp_ib->{$Keyname} =  $Irequest;
                    }
                }

                if(!is_null($certi_ib)){
                    $file = CertiIBFileAll::where('state',1)->where('app_certi_ib_id',$certi_ib->id)->first();
                    //Set ค่าเริ่มต้น
                    $format_name           = '80';
                    $format_names_en       = '135';
                    $format_address_th     = '75';
                    $format_address_en     = '120';

                    //ค่าใหม่
                    if(!empty($cer_exp_ib->set_format)){
                        $set_format            = explode(",",$cer_exp_ib->set_format);
                        if(!empty($set_format)){
                            $format_name       =   !empty($set_format[0]) ?  $set_format[0] : '80';
                            $format_names_en   =   !empty($set_format[1]) ?  $set_format[1] : '135';
                            $format_address_th =   !empty($set_format[2]) ?  $set_format[2] :  '75';
                            $format_address_en =   !empty($set_format[3]) ?  $set_format[3] :  '120';
                        }
                    }

                    $no      = '17020';
                    $formula = Formula::where('title', 'like', '%'.$no.'%')->whereState(1)->first();

                    if(!is_null($file) && !is_null($file->attach_pdf)){
                        $url      =   url('/certify/check_files_ib/'. rtrim(strtr(base64_encode($certi_ib->id), '+/', '-_'), '=') );
                        $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)->size(500)->errorCorrection('H')->generate($url);
                    }

                    $type_unit    = ['1'=>'A','2'=>'B','3'=>'C'];	

                        // ชื่อภาษาไทย
                        $names         = !empty($cer_exp_ib->name_unit) ?  self::format_data($cer_exp_ib->name_unit,$format_name)  : [];
       
                        // ชื่อภาษาอังกฤษ
                         $names_en     = !empty($cer_exp_ib->name_unit_en) ?  self::format_data2('('.$cer_exp_ib->name_unit_en.')',$format_names_en)  : [];
                        
                        // ตั้งอยู่เลขที่ Th
                         $address_th  = self::FormatAddress($cer_exp_ib,2);
                         $address_ths =      !empty($address_th) ?  self::format_data($address_th,$format_address_th)  : [];
 
                         // ตั้งอยู่เลขที่ en
                         $address_en  = self::Format_Address_En($cer_exp_ib,2);
                         $address_ens =  !empty($address_en) ?  self::format_data2($address_en,$format_address_en)  : [];
              

                         $condition_th = !empty($formula->condition_th) ? self::format_data($formula->condition_th,65)   : [] ;
                         $condition_en = !empty($formula->condition_en) ? self::format_data2($formula->condition_en,120)   : [] ;

                    $send_list = SendCertificateLists::where('certificate_id',$cer_exp_ib->id)->where('certificate_tb', (new CertiIBExport)->getTable())->first();
                    if(!is_null($send_list) && !empty($send_list->sign_path)){
                        $image         = public_path('uploads/') .$send_list->sign_path;
                        $image_info    = getimagesize($image);
                        list($width, $height, $types, $attr) = $image_info;
                        $height 	   = round($width*$image_info[1]/$image_info[0]);
                        if( $height <= 2000 ){
                            $heights   = 50;
                            $widths    = round($heights*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="50px" width="'.$widths.'px">';
                        }else if( $height > 2000 ){
                            $heights   = 100;
                            $widths    = round($heights*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="100px" width="'.$widths.'px">';
                        }else{
                            $widths    = round($height*$image_info[0]/$image_info[1]);
                            $sign_path = '<img src="'.$image.'" height="'.$height.'px" width="'.$widths.'px">';
                        }
                        $sign_name     = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                        $sign_position = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                    }else{
                        $sign_path     =   '<span style="color:#ffffff;"> &emsp;</span>';
                        $sign_name     = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                        $sign_position = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                    }

                    $data_export = [
                                        'certificate'         => $cer_exp_ib->certificate ?? null ,
                                        'app_no'              => $certi_ib->app_no,
                                        'names'               => $names,  
                                        'names_en'            => $names_en,
                                        'address_ths'         => $address_ths,
                                        'address_ens'         => !empty($address_ens) ? $address_ens : [],
                                        'formula'             => isset($cer_exp_ib->formula) ?   $cer_exp_ib->formula : '&emsp;', 
                                        'formula_en'          => isset($cer_exp_ib->formula_en) ?   $cer_exp_ib->formula_en : '&emsp;',
                                        'condition_th'        => $condition_th ,
                                        'condition_en'        => !empty($formula->condition_en) ? '('.$formula->condition_en.')' : '' ,
                                        'accereditatio_no'    => $cer_exp_ib->accereditatio_no,
                                        'accereditatio_no_en' => $cer_exp_ib->accereditatio_no_en,
                                        'date_start'          => $cer_exp_ib->date_start,
                                        'date_start_en'       => !empty($cer_exp_ib->date_start) ? HP::formatDateENertify($cer_exp_ib->date_start) : null
                                    ];

                    $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                    $mpdf->AddPage('P');
                    $html  = view('certify/send-certificates/pdf.ib', $data_export);
                    $mpdf->WriteHTML($html);

                    $set_footer = [
                                        'image_qr'           => isset($image_qr) ? $image_qr : null,
                                        'url'               => isset($url) ? $url : null,
                                        'sign_path'         =>  $sign_path,
                                        'sign_name'         =>  $sign_name,
                                        'sign_position'     =>  $sign_position,
                                        'sign_instead'      =>  $cer_exp_ib->sign_instead ?? null
                                    ];

                    $footer = view('certify/send-certificates/pdf.footer-ib',$set_footer);
                    $mpdf->SetHTMLFooter($footer);

                    $title  = "ใบรับรองหน่วยตรวจ".date('Ymd_hms').".pdf";
                    $mpdf->SetTitle($title);
                    $mpdf->Output($title, $type);

                }
            }


        }else if(  in_array($cer, [1]) ){ //ห้องหน่วยรับรอง CB

            $cer_exp_cb  = CertiCBExport::findOrFail($id);
            if(!is_null($cer_exp_cb)){

                //Set PDF
                $requestData['date_start'] = !empty($requestData['date_start'])?HP::convertDate($requestData['date_start'],true):null;
                $requestData['date_end']   = !empty($requestData['date_end'])?HP::convertDate($requestData['date_end'],true):null;
                foreach( $requestData AS $Keyname => $Irequest ){
                    if( Schema::hasColumn( $cer_exp_cb->getTable(), $Keyname ) ){
                        $cer_exp_cb->{$Keyname} =  $Irequest;
                    }
                }

                $CertiCb = CertiCb::findOrFail($cer_exp_cb->app_certi_cb_id);
                $file    = CertiCBFileAll::where('state',1)->where('app_certi_cb_id',$CertiCb->id)->first();
                $formula = Formula::where('id', 'like', $CertiCb->type_standard)->whereState(1)->first();

                if(!is_null($file) && !is_null($file->attach_pdf) ){
                    $url      = url('/certify/check_files_cb/'. rtrim(strtr(base64_encode($CertiCb->id), '+/', '-_'), '=') );
                    //ข้อมูลภาพ QR Code
                    $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)->size(500)->errorCorrection('H')->generate($url);
                }

                         if(!empty($cer_exp_cb->set_format)){
                            $set_format = explode(",",$cer_exp_cb->set_format);
                            if(!empty($set_format)){
                                $format_name        =   !empty($set_format[0]) ?  $set_format[0] : '80';
                                $format_names_en    =   !empty($set_format[1]) ?  $set_format[1] : '135';
                                $format_address_th  =   !empty($set_format[2]) ?  $set_format[2] :  '75';
                                $format_address_en  =   !empty($set_format[3]) ?  $set_format[3] :  '120';
                                $format_condition_th  =   !empty($set_format[4]) ?  $set_format[4] :  '60';
                                $format_condition_en  =   !empty($set_format[5]) ?  $set_format[5] :  '140';
                                $format_branch_th   =   !empty($set_format[6]) ?  $set_format[6] :  '60';
                            }else{
                                $format_name = '80';
                                $format_names_en = '135';
                                $format_address_th = '75';
                                $format_condition_th = '60';
                                $format_condition_en = '140';
                                $format_branch_th = '60';

                            }
                         }else{
                            $format_name = '80';
                            $format_names_en = '135';
                            $format_address_th = '75';
                            $format_address_en = '120';
                            $format_condition_th = '60';
                            $format_condition_en = '140';
                            $format_branch_th = '60';
                         }                       

                        // ชื่อภาษาไทย
                        $names         = !empty($cer_exp_cb->name_standard) ?  self::format_data($cer_exp_cb->name_standard,$format_name)  : [];
    
                        // ชื่อภาษาอังกฤษ
                        $names_en     = !empty($cer_exp_cb->name_standard_en) ?  self::format_data2('('.$cer_exp_cb->name_standard_en.')',$format_names_en)  : [];
                        
                        // ตั้งอยู่เลขที่ Th
                        $address_th  = self::FormatAddress($cer_exp_cb,2);
                        $address_ths =      !empty($address_th) ?  self::format_data($address_th,$format_address_th)  : [];

                        // ตั้งอยู่เลขที่ en
                        $address_en  = self::Format_Address_En($cer_exp_cb,2);
                        $address_ens =  !empty($address_en) ?  self::format_data2($address_en,$format_address_en)  : [];


                         $condition_th = !empty($formula->condition_th) ? self::format_data($formula->condition_th,$format_condition_th)   : [] ;
                         $condition_en = !empty($formula->condition_en) ? self::format_data2($formula->condition_en,$format_condition_en)   : [] ;

                         $branch_th = !empty($CertiCb->CertificationBranchTo->title) ? self::format_data($CertiCb->CertificationBranchTo->title,$format_branch_th)   : [] ;
                         $branch_en = !empty($CertiCb->CertificationBranchTo->title_en) ?  '('.$CertiCb->CertificationBranchTo->title_en.')'   : '' ;
                      
     
                         $send_list = SendCertificateLists::where('certificate_id',$cer_exp_cb->id)->where('certificate_tb', (new CertiCBExport)->getTable())->first();
                         if(!is_null($send_list) && !empty($send_list->sign_path)){
                              $image =    public_path('uploads/') .$send_list->sign_path;
                             $image_info = getimagesize($image);
                             list($width, $height, $types, $attr) = $image_info;
                               $height 	= round($width*$image_info[1]/$image_info[0]);
                              if($height <= 2000){
                                 $heights = 50;
                                  $widths  = round($heights*$image_info[0]/$image_info[1]);
                                  $sign_path = '<img    src="'.$image.'"  height="50px" width="'.$widths.'px">';
                             }else if($height > 2000){
                                 $heights = 100;
                                 $widths  = round($heights*$image_info[0]/$image_info[1]);
                                 $sign_path = '<img    src="'.$image.'"  height="100px" width="'.$widths.'px">';
                             }else{
                                 $widths  = round($height*$image_info[0]/$image_info[1]);
                                 $sign_path = '<img    src="'.$image.'"  height="'.$height.'px" width="'.$widths.'px">';
                             }
                             $sign_name        = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                             $sign_position    = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                         }else{
                             $sign_path      =   '<span style="color:#ffffff;"> &emsp;</span>';
                            //  $sign_name  = '';
                            //  $sign_position  = '';
                            $sign_name        = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                            $sign_position    = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                          
                         }
                        $data_export = ['certificate'        => $cer_exp_cb->certificate ?? null ,
                                        'app_no'             => $CertiCb->app_no,
                                        'names'              =>$names,  
                                        'names_en'           => $names_en,
                                        'address_ths'        => $address_ths,
                                        'address_ens'         => !empty($address_ens) ? $address_ens : [],
                                        'formula'            =>  isset($cer_exp_cb->formula) ?   $cer_exp_cb->formula : '&emsp;', 
                                        'formula_en'         =>  isset($cer_exp_cb->formula_en) ?   $cer_exp_cb->formula_en : '&emsp;',
                                        'condition_th'       =>  $condition_th ,
                                        'condition_en'       => !empty($formula->condition_en) ? '('.$formula->condition_en.')' : '' ,
                                        'branch_th'          => $branch_th ,
                                        'branch_en'          => $branch_en,
                                        'accereditatio_no'   => $cer_exp_cb->accereditatio_no,
                                        'accereditatio_no_en'=> $cer_exp_cb->accereditatio_no_en,
                                        'date_start'         =>  $cer_exp_cb->date_start,
                                        'date_start_en'      =>  !empty($cer_exp_cb->date_start) ? HP::formatDateENertify($cer_exp_cb->date_start) : null
                                    ];
                $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                $mpdf->AddPage('P');
          
                $html  = view('certify/send-certificates/pdf.cb', $data_export);
                $mpdf->WriteHTML($html);

                $set_footer = [ 'image_qr'          => isset($image_qr) ? $image_qr : null,
                                'url'               => isset($url) ? $url : null,
                                'image'             =>  !empty($CertiCb->CertiCBFormulasTo->image) ?  $CertiCb->CertiCBFormulasTo->image : '-',
                                'check_badge'       => isset($cer_exp_cb->check_badge) ? $cer_exp_cb->check_badge : null,
                                'sign_path'         =>  $sign_path,
                                'sign_name'         =>  $sign_name,
                                'sign_position'     =>  $sign_position,
                                'sign_instead'      =>  $cer_exp_cb->sign_instead ?? null
                             ];

                $footer     = view('certify/send-certificates/pdf.footer-cb',$set_footer);
                $mpdf->SetHTMLFooter($footer);
                $title      = "ใบรับรองหน่วยรับรอง".date('Ymd_hms').".pdf";
                $mpdf->SetTitle($title);
                $mpdf->Output($title, $type);
            }
        }
    }

    public function format_data($str,$number=55){
       
        $result = !empty($str) ? self::get_segment_array($str,$number,16)  : [];
        return $result;
    }

    public function format_data2($str,$number=100){
        $result          = !empty($str) ?  self::get_segment_array($str,$number,10)  : [];
        if(count($result) == 3){
            $number2     = ($number*2);
            $result      = !empty($str) ?  self::get_segment_array($str,$number2,9)  : [];
            if(count($result) == 3){
                $number3 = ($number*3);
                $result  = !empty($str) ?  self::get_segment_array($str,$number3,8)  : [];
            }
        }
        return $result;
    }

    public function get_segment_array($str,$number =75,$font = 16){
 
        $segment    =  new Segment;
        $array      =  $segment->get_segment_array($str);
        $data1      =  [];
        $data2      =  [];
        $data3      =  [];
        $count_word = 0;
        $number2    = ($number*2) + 10;
        foreach ($array as $key => $value) {
			$count_word += HP::countString($value);
			if ($count_word <= $number) {
				$data1[] = $value;
			} else if ($count_word > $number && $count_word <= $number2) {
				$data2[] = $value;
			} else if ($count_word > $number2) {
				$data3[] = $value;
			}
		}
  
        $result = [];
        if(count($data1) > 0){
            $object        = (object)[];
            $object->font  = 'font-'.$font;
            $object->title = implode('', $data1);
            $result[]      = $object;
        }

        if(count($data2) > 0){
            $object        = (object)[];
            $object->font  = 'font-'.$font;
            $object->title = implode('', $data2);
            $result[]      = $object;
        }

        if(count($data3) > 0){
            $object        = (object)[];
            $object->font  = 'font-'.$font;
            $object->title = implode('', $data3);
            $result[]      = $object;
        }
        return  $result;
    }

    private function FormatAddress($request,$cer = null){

        $address   = [];

        if($cer == 2 || $cer == 1){
            $address[] = $request->address;

            if($request->allay!=''){
                $address[] = "หมู่ที่ " . $request->allay;
            }
    
            if($request->village_no!='' && $request->village_no !='-'  && $request->village_no !='--'){
                $address[] = "ซอย". $request->village_no;
            }
    
            if($request->road!='' && $request->road !='-'  && $request->road !='--'){
                $address[] = "ถนน"  . $request->road;
            }
            if($request->district_name!=''){
                $address[] = (($request->province_name=='กรุงเทพมหานคร')?" แขวง":" ตำบล").$request->district_name;
            }
            if($request->amphur_name!=''){
                $address[] = (($request->province_name=='กรุงเทพมหานคร')?" เขต":" อำเภอ").$request->amphur_name;
            }
    
            if($request->province_name=='กรุงเทพมหานคร'){
                $address[] = " ".$request->province_name;
            }else{
                $address[] = " จังหวัด".$request->province_name;
            }

        }else{
            if(isset($request->address_no)){
                $address[] = $request->address_no;
            }
            if($request->address_moo!=''){
                $address[] = "หมู่ที่ " . $request->address_moo;
            }

            if($request->address_soi!='' && $request->address_soi !='-'  && $request->address_soi !='--'){
                $address[] = "ซอย"  . $request->address_soi;
            }

            if($request->address_road!='' && $request->address_road !='-'  && $request->address_road !='--'){
                $address[] = "ถนน"  . $request->address_road;
            }

            if($request->address_subdistrict!=''){
                $address[] = (($request->address_province=='กรุงเทพมหานคร')?" แขวง":" ตำบล").$request->address_subdistrict;
            }

            if($request->address_district!=''){
                $address[] = (($request->address_province=='กรุงเทพมหานคร')?" เขต":" อำเภอ").$request->address_district;
            }

            if($request->address_province=='กรุงเทพมหานคร'){
                $address[] = " ".$request->address_province;
            }else{
                $address[] = " จังหวัด".$request->address_province;
            }
        }     

        return implode(' ', $address);
    }
    
    private function FormatAddressEn($request,$cer = null){
        $address           = [];
        if($cer == 2 || $cer == 1){
            $address[]     = $request->address_en;

            if($request->allay_en!=''){
                $address[] = 'Moo '.$request->allay_en;
            }
    
            if($request->village_no_en!='' && $request->village_no_en !='-'  && $request->village_no_en !='--'){
                $address[] = $request->village_no_en;
            }

            if($request->road_en!='' && $request->road_en !='-'  && $request->road_en !='--'){
                $address[] = $request->road_en.',';
            }

            if($request->district_name_en!='' && $request->district_name_en !='-'  && $request->district_name_en !='--'){
                $address[] = $request->district_name_en.',';
            }

            if($request->amphur_name_en!='' && $request->amphur_name_en !='-'  && $request->amphur_name_en !='--'){
                $address[] = $request->amphur_name_en.',';
            }

            if($request->province_name_en!='' && $request->province_name_en !='-'  && $request->province_name_en !='--'){
                $address[] = $request->province_name_en;
            }
         
        }else{
            if(isset($request->address_no_en)){
                $address[] = $request->address_no_en;
            }

            if($request->address_moo!=''){
                $address[] = 'Moo '.$request->address_moo_en.',';
            }

            if($request->address_soi_en!='' && $request->address_soi_en !='-'  && $request->address_soi_en !='--'){
                $address[] = $request->address_soi_en.',';
            }

            if($request->address_road_en!='' && $request->address_road_en !='-'  && $request->address_road_en !='--'){
                $address[] = $request->address_road_en.',';
            }

            if($request->address_subdistrict_en!='' && $request->address_subdistrict_en !='-'  && $request->address_subdistrict_en !='--'){
                $address[] = $request->address_subdistrict_en.',';
            }

            if($request->address_district_en!='' && $request->address_district_en !='-'  && $request->address_district_en !='--'){
                $address[] = $request->address_district_en.',';
            }

            if($request->address_province_en!='' && $request->address_province_en !='-'  && $request->address_province_en !='--'){
                $address[] = $request->address_province_en;
            }
        }
        return implode(' ', $address);
    }

    
    private function Format_Address_En($request,$cer = null){
        $address   = '';

        if($cer == 2 || $cer == 1){
            $address     .= '(';
            $address     .=  $request->address_en;
            if($request->allay_en!=''){
                $address .= 'Moo '.$request->allay_en;
            }
    
            if($request->village_no_en!='' && $request->village_no_en !='-'  && $request->village_no_en !='--'){
                $address .= ' '.$request->village_no_en;
            }

            if($request->road_en!='' && $request->road_en !='-'  && $request->road_en !='--'){
                $address .= ' '.$request->road_en.',';
            }

            if($request->district_name_en!='' && $request->district_name_en !='-'  && $request->district_name_en !='--'){
                $address .= ' '.$request->district_name_en.',';
            }

            if($request->amphur_name_en!='' && $request->amphur_name_en !='-'  && $request->amphur_name_en !='--'){
                $address .= ' '.$request->amphur_name_en.',';
            }

            if($request->province_name_en!='' && $request->province_name_en !='-'  && $request->province_name_en !='--'){
                $address .= ' '.$request->province_name_en;
            }

            $address     .=   ')';
        }else{
            $address     .=   '(';
            if(isset($request->address_no_en)){
                $address .= $request->address_no_en;
            }
             
            if($request->address_moo!=''){
                $address .= 'Moo '.$request->address_moo_en.',';
            }

            if($request->address_soi_en!='' && $request->address_soi_en !='-'  && $request->address_soi_en !='--'){
                $address .= ' '.$request->address_soi_en.',';
            }

            if($request->address_road_en!='' && $request->address_road_en !='-'  && $request->address_road_en !='--'){
                $address .= ' '.$request->address_road_en.',';
            }

            if($request->address_subdistrict_en!='' && $request->address_subdistrict_en !='-'  && $request->address_subdistrict_en !='--'){
                $address .= ' '.$request->address_subdistrict_en.',';
            }

            if($request->address_district_en!='' && $request->address_district_en !='-'  && $request->address_district_en !='--'){
                $address .= ' '. $request->address_district_en.',';
            }

            if($request->address_province_en!='' && $request->address_province_en !='-'  && $request->address_province_en !='--'){
                $address .= ' '.$request->address_province_en;
            }

            $address .=   ')';
        }

        return $address;
    }

}
