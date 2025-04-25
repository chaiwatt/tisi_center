<?php

namespace App\Http\Controllers\Certify;

use HP;
use DB;  

use File;
use HP_DGA;

use QrCode;
use Segment;
use Storage;
use App\User;

use Mpdf\Mpdf;
use App\Http\Requests;
use App\CertificateExport;
use Illuminate\Http\Request;

use  App\Models\Besurv\Signer;
use App\Models\Bcertify\Formula;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Certify\SendCertificates;


 


use App\Mail\Lab\DirectorSignNotification;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Certify\SendCertificateLists;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use App\Models\Certify\ApplicantIB\CertiIBFileAll;

class SendCertificatesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {

        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.send-certificates.index');
        }
        abort(403);

    }


    public function data_list(Request $request)
    {
        $model = str_slug('sendcertificates', '-');
        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');
        $filter_certificate_type = $request->input('filter_certificate_type');
        $filter_signer_id = $request->input('filter_signer_id');
        $query = SendCertificates::query()
                                        ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search ); 
                                            $query->where(function ($query2) use($search_full) { 
                                            $query2->Where(DB::raw("REPLACE(sign_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(sign_position,' ','')"), 'LIKE', "%".$search_full."%") ;
                                            });
                                        }) 
                                        ->when($filter_state, function ($query, $filter_state){
                                            $query->where('state', $filter_state);
                                    })
                                        ->when($filter_certificate_type, function ($query, $filter_certificate_type){
                                                $query->where('certificate_type', $filter_certificate_type);
                                        })
                                        ->when($filter_signer_id, function ($query, $filter_signer_id){
                                                $query->where('sign_id', $filter_signer_id);
                                        }) ; 
                                      
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                if($item->state == 99){
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">'; 
                                }else{
                                    return '';
                                }
                            })
                            ->addColumn('certificate_type', function ($item) {
                                return   !empty($item->CertificateTypeTitle)? $item->CertificateTypeTitle:'';   
                            })
                            ->addColumn('number', function ($item) {
                                return   count($item->send_certificate_lists_approve_many) .'/'. count($item->send_certificate_lists_many) ;
                            })
                            ->addColumn('sign_name', function ($item) {
                                return   !empty($item->sign_name)? $item->sign_name:'';   
                            })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->created_at)? HP::DateThai($item->created_at):'';   
                            })
                            ->addColumn('updated_at', function ($item) {
                                return  !empty($item->SendCertificateListsUpdated)   ?  $item->SendCertificateListsUpdated :'';   
                            })
                            ->addColumn('status', function ($item) {
                                return   !empty($item->SendCertificateStatus) ? $item->SendCertificateStatus : '';   
                            })
                            ->addColumn('action', function ($item) use($model) {
                                if($item->state == 99){
                                    return HP::buttonAction( $item->id, 'certify/send-certificates','Certify\\SendCertificatesController@destroy', 'sendcertificates',true,true,true);
                                }else{
                                    return HP::buttonAction( $item->id, 'certify/send-certificates','Certify\\SendCertificatesController@destroy', 'sendcertificates',true,true,false);
                                }
                              
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'certificate_type', 'status','action'])
                            ->make(true);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
 
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('add-'.$model)) {

            $signs = Signer::select('id','name')->whereJsonContains('main_group', auth()->user()->DepartmentId)->pluck('name', 'id');
 
            return view('certify.send-certificates.create', compact('signs'));
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('add-'.$model)) {
 
            $requestData                = $request->all();
            $requestData['created_by']  =  auth()->user()->getKey();

            $signer = Signer::findOrFail($requestData['sign_id']);
            if(!is_null($signer)){
                $requestData['sign_name']       =   $signer->name ?? null;
                $requestData['sign_position']   =   $signer->position ?? null;
            }  

           

            $send = SendCertificates::create($requestData);

            $lists  = $requestData['lists'];
       
            if(!empty($lists) && count($lists) > 0){
                $cer_type = [
                    '1'=>(new CertiCBExport)->getTable(),
                    '2'=>(new CertiIBExport)->getTable(),
                    '3'=>(new CertificateExport)->getTable()
                ];
                $certificate_type = $requestData['certificate_type']; 
                foreach($lists['id'] as $item){ 
                    $input                          = [];
                    $input['send_certificate_id']   =  $send->id; 
                    $input['certificate_type']      =   $certificate_type; 
                    $input['certificate_tb']        =   array_key_exists($certificate_type,$cer_type) ? $cer_type[$certificate_type] : null; 
                    $input['certificate_id']        =   $item; 
                    if($certificate_type == 1){ // ห้องหน่วยรับรอง
                        $car = CertiCBExport::where('id',$item)->value('app_certi_cb_id');
                        $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                    }else  if($certificate_type == 2){ // ห้องหน่วยรับรอง
                        $car = CertiIBExport::where('id',$item)->value('app_certi_ib_id');
                        $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                    }else  if($certificate_type == 3){ // ห้องหน่วยรับรอง
                        $car = CertificateExport::where('id',$item)->value('certificate_for');
                        $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                    }
                    $input['sign_status']           =   !empty($request->state) && $request->state == 1 ?  2 : 1; 

                    
                    $sendCertificateList = SendCertificateLists::create($input);

                    if($certificate_type == 1){ // ห้องหน่วยรับรอง

                    }else  if($certificate_type == 2){ // ห้องหน่วยรับรอง
                        
                    }else  if($certificate_type == 3){ // ห้องหน่วยรับรอง
                        $export = CertificateExport::find($item);
                        if($export->hold_status != null)
                        {
                            $appLab = CertiLab::where('app_no',$export->request_number)->first();
                            if($appLab != null){
                                if($appLab->purpose_type == 6){
                                    CertiLab::where('app_no',$export->request_number)->update([
                                        'transferer_user_id' => null,
                                        'transferer_export_id' => null,
                                    ]);
                                }
                            }

                            CertificateExport::find($item)->update([
                                'status' => $export->hold_status,
                                'hold_status' => null
                            ]);
                        }
                    }

                    

                }
            }

            // if(!empty($request->state) && $request->state == 1 && !empty($send->signer_to->line_token) ){
            if(!empty($request->state) && $request->state == 1  ){
                // self::api_line($send->id,$send->signer_to->line_token);


            $signer = Signer::findOrFail($requestData['sign_id']);
            $user = User::find($signer->user_register_id);
                
             $mail = $user->reg_email;
             
             if($mail !== null){

                 $config = HP::getConfig();
                 $url  =   !empty($config->url_center) ? $config->url_center : url('');
     
                 $data_app = [
                                 'url' =>  $url.'/certify/send-certificates/create'
                             ];

                   $html = new  DirectorSignNotification($data_app);
                   $mail = Mail::to($mail)->send($html);

               }
            }



            return redirect('certify/send-certificates')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('view-'.$model)) {
            $sendcertificate = SendCertificates::findOrFail($id);
            $certify =  ['1'=>'ห้องหน่วยรับรอง','2'=>'หน่วยตรวจสอบ','3'=>'ห้องปฏิบัติการ'];
            $sendcertificate->certification = !empty($sendcertificate->certificate_type) && array_key_exists($sendcertificate->certificate_type,$certify) ? $certify[$sendcertificate->certificate_type] :null;
            $sendcertificate->status        = !empty($sendcertificate->SendCertificateStatus)   ? $sendcertificate->SendCertificateStatus :null;
            return view('certify.send-certificates.show', compact('sendcertificate'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('edit-'.$model)) {
            $sendcertificate = SendCertificates::findOrFail($id);
            $certify =  ['1'=>'ห้องหน่วยรับรอง','2'=>'หน่วยตรวจสอบ','3'=>'ห้องปฏิบัติการ'];
            $sendcertificate->certification = !empty($sendcertificate->certificate_type) && array_key_exists($sendcertificate->certificate_type,$certify) ? $certify[$sendcertificate->certificate_type] :null;
            $sendcertificate->status        = !empty($sendcertificate->SendCertificateStatus)   ? $sendcertificate->SendCertificateStatus :null;
            
            return view('certify.send-certificates.edit', compact('sendcertificate'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('edit-'.$model)) {

            $sendcertificate = SendCertificates::findOrFail($id);
            $requestData = $request->all();
            if(in_array($sendcertificate->state,[99,1])){
                    $requestData['created_by']  =  auth()->user()->getKey();
                    $requestData['created_at']  =  date('Y-m-d H:i:s');
                    $sendcertificate->update($requestData);

                    $lists  = $requestData['lists'];
                    if(!empty($lists) && count($lists) > 0){
                    //ลบที่ถูกกดลบ
                    $lit_id = array_diff($lists['id'], [null]);
                    SendCertificateLists::where('send_certificate_id', $id)
                                        ->when($lit_id, function ($query, $lit_id){
                                            return $query->whereNotIn('certificate_id', $lit_id);
                                        })->delete();

                            $cer_type = ['1'=>(new CertiCBExport)->getTable(),'2'=>(new CertiIBExport)->getTable(),'3'=>(new CertificateExport)->getTable()];
                        $certificate_type = $requestData['certificate_type']; 
                        foreach($lists['id'] as $item){
                            $input                          = [];
                            $input['send_certificate_id']   =   $id; 
                            $input['certificate_type']      =   $certificate_type; 
                            $input['certificate_tb']        =   array_key_exists($certificate_type,$cer_type) ? $cer_type[$certificate_type] : null; 
                            $input['certificate_id']        =   $item;
                            
                            if($certificate_type == 1){ // ห้องหน่วยรับรอง
                                $car = CertiCBExport::where('id',$item)->value('app_certi_cb_id');
                                $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                            }else  if($certificate_type == 2){ // ห้องหน่วยรับรอง
                                $car = CertiIBExport::where('id',$item)->value('app_certi_ib_id');
                                $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                            }else  if($certificate_type == 3){ // ห้องหน่วยรับรอง
                                $car = CertificateExport::where('id',$item)->value('certificate_for');
                                $input['app_certi_id']        =    !empty($car) ?  $car : null; 
                            }
                          
                            $input['sign_status']           =   !empty($request->state) && $request->state == 1 ?  2 : 1; 
                            $send_cer =   SendCertificateLists::where('send_certificate_id',$id)->where('certificate_id',$item)->first() ; 
                            if(!is_null($send_cer)){
                                $send_cer->update($input);
                            }
                        }
                    }

                    // if(!empty($request->state) && $request->state == 1 && !empty($sendcertificate->signer_to->line_token) ){
                    if(!empty($request->state) && $request->state == 1 ){
                        // self::api_line($id,$sendcertificate->signer_to->line_token);
                        $mail = auth()->user()->reg_email;
             
                        if($mail !== null){
           
                            $config = HP::getConfig();
                            $url  =   !empty($config->url_center) ? $config->url_center : url('');
                
                            $data_app = [
                                            'url' =>  $url.'/certify/send-certificates/create'
                                        ];
           
                              $html = new  DirectorSignNotification($data_app);
                              $mail = Mail::to($mail)->send($html);
           
                          }
                    }
                
            }else{
 
                $requestData['updated_by']  =  auth()->user()->getKey();
                $sendcertificate->update($requestData);
                 
            }
            
            return redirect('certify/send-certificates')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public  static function api_line($id,$token){
         $url =   url('certify/sign-certificates') ;
       	 define('LINE_API',"https://notify-api.line.me/api/notify");
		$message = "กรุณาลงนาม \n เข้าสู่ระบบ $url"; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
		 
		 $queryData = array('message' => $message);
		 $queryData = http_build_query($queryData,'','&');
		 $headerOptions = array( 
			   'http'=>array(
				'method'=>'POST',
				'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
					    ."Authorization: Bearer ".$token."\r\n"
					    ."Content-Length: ".strlen($queryData)."\r\n",
				'content' => $queryData
			   ),
		 );
		 $context = stream_context_create($headerOptions);
		 $result = file_get_contents(LINE_API,FALSE,$context);
		 $res = json_decode($result);
		 return $res;
	}





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('sendcertificates','-');
        if(auth()->user()->can('delete-'.$model)) {
 
            SendCertificates::destroy($id);
            // SendCertificates::where('send_certificate_id', $id)->delete();
          return redirect('certify/send-certificates')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $student = SendCertificates::whereIn('id', $id_array);
        if($student->delete())
        {
            // SendCertificateLists::whereIn('send_certificate_id', $id_array)->delete();
            echo 'Data Deleted';
        }

    }




 
    public function getsign_position(Request $request)
    {
        $signer = Signer::findOrFail($request->id);
        if(!is_null($signer)){
          return response()->json([
                                    'message' =>  true,
                                    'signer' => $signer
                                 ]);
        }else{
         return response()->json([
                                'message' =>  false
                                ]);
        }

    }
 
    public function getsign(Request $request)
    {
        
        $certificate_type = $request->input('certificate_type');
        $signer = Signer::findOrFail($request->id);
      
        if(!is_null($signer))
        {
            $user = User::where(DB::raw("REPLACE(REPLACE(reg_13ID,'-',''),' ','')"),str_replace("-","", $signer->tax_number))->first() ; 
            // $certify =  ['1'=>'ห้องหน่วยรับรอง','2'=>'หน่วยตรวจสอบ','3'=>'ห้องปฏิบัติการ'];
            $reg_subdepart =  !empty(auth()->user()->reg_subdepart)  ? auth()->user()->reg_subdepart : null ;
            $datas = [];
            $purpose_type           =   ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
            if($certificate_type == 3)
            { // ห้องปฏิบัติการ
                
                $signer->certify            =  'ห้องปฏิบัติการ';
                $signer->certificate_type    = 3;
                $table              =  (new CertificateExport)->getTable();
                $export             =  CertificateExport::where('sign_id',$signer->id)->whereIn('status',[2])->get();   
                // dd($export);
                if(count($export) > 0){
                    foreach($export as $key => $item){
                   
                        if(!empty($item->CertiLabTo)){
                            $send_cer_list =   SendCertificateLists::select('id','sign_status')->where('certificate_id',$item->id)->where('certificate_tb',$table)->first();   
                            // dd($send_cer_list,$send_cer_list->sign_status,$item->id);
                            if( is_null($send_cer_list) || ( !is_null($send_cer_list)  &&  $send_cer_list->sign_status == 4 ) ){
                                $lab                    = $item->CertiLabTo;
                                $list                   = (object)[];
                                $list->id               =  $item->id; 
                                $list->checkbox         = '<input type="checkbox" name="lists[id][]" class="item_checkbox"  value="'. $item->id .'">'; 
                                $list->name             =  $lab->name ??  '';
                                $list->tax_id           =  $lab->tax_id ?? '';
                                $list->room             =  $lab->lab_name ?? ''; 
                                $list->cer_link         = '<a class="btn btn-link" href="'.(route('check_certificate.show', ['cc' => $lab->id])).'" target="_blank">  '.($lab->app_no ?? '').' </a>';  
                                $list->purpose_type     =  array_key_exists($lab->purpose_type,$purpose_type) ? $purpose_type[$lab->purpose_type] : null; 
                                $list->accereditatio_no =   $item->accereditatio_no  ??  '';
                                $list->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  

                                $certilab_file          = $lab->CertLabsFileScope;   
                                if(is_null($certilab_file)){
                                    $certilab_file          = CertLabsFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_lab_id',$lab->id)->where('state',1)->first();   
                                }
                                if(!is_null($certilab_file)){
                                    $list->cer_file     =   ' <a href="'.(url('certify/check/file_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                }else{
                                    $list->cer_file     =  '';
                                }
                                $datas[]                = $list;  
                            }
                        }
                    }
                }
            }else  if($certificate_type == 2){ // หน่วยตรวจสอบ
             
                $signer->certify            =  'หน่วยตรวจสอบ';
                $signer->certificate_type    = 2;
                $table              =  (new CertiIBExport)->getTable();
                $export             =  CertiIBExport::where('sign_id',$signer->id)->whereIn('status',[2])->get();   
                if(count($export) > 0){
                    foreach($export as $key => $item){
                        if(!empty($item->CertiIBCostTo) ){
                            $send_cer_list =   SendCertificateLists::select('id','sign_status')->where('certificate_id',$item->id)->where('certificate_tb',$table)->first();   
                            if( is_null($send_cer_list) || ( !is_null($send_cer_list)  &&  $send_cer_list->sign_status == 4 ) ){
                                $ib                     = $item->CertiIBCostTo;
                                $list                   = (object)[];
                                $list->id               =  $item->id; 
                                $list->checkbox         = '<input type="checkbox" name="lists[id][]" class="item_checkbox"  value="'. $item->id .'">'; 
                                $list->name             =  $ib->name   ??  '';
                                $list->tax_id           =  $ib->tax_id   ??  '';
                                $list->room             =  $item->name_unit  ??  '';
                                $list->cer_link         = '<a class="btn btn-link" href="'.( url('/certify/check_certificate-ib/' . $ib->token)).'" target="_blank">  '.($ib->app_no ?? '').' </a>';  
                                $list->purpose_type     =  array_key_exists($ib->standard_change,$purpose_type) ? $purpose_type[$ib->standard_change] : null; 
                                $list->accereditatio_no =   $item->accereditatio_no  ??  '';
                                $list->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->id.'/2')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  
                                $certilab_file          = CertiIBFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_ib_id',$ib->id)->where('state',1)->first();   
                                if(!is_null($certilab_file)){
                                    $list->cer_file     =   ' <a href="'.(url('certify/check/file_ib_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                }else{
                                    $list->cer_file     =  '';
                                }
                                $datas[]                = $list;  
                          }
                        }
                    }
                }
            }else  if($certificate_type == 1){ // ห้องหน่วยรับรอง
                $signer->certify            =  'ห้องหน่วยรับรอง';
                $signer->certificate_type    = 1;
                $table              =  (new CertiCBExport)->getTable();
                $export             =  CertiCBExport::where('sign_id',$signer->id)->whereIn('status',[2])->get();  
                if(count($export) > 0){
                    foreach($export as $key => $item){
   
                        if(!empty($item->CertiCbTo)  ){
                            $send_cer_list =   SendCertificateLists::select('id','sign_status')->where('certificate_id',$item->id)->where('certificate_tb',$table)->first();   
                            if( is_null($send_cer_list) || ( !is_null($send_cer_list)  &&  $send_cer_list->sign_status == 4 ) ){
                                $cb                     = $item->CertiCbTo;
                                $list                   = (object)[];
                                $list->id               =  $item->id; 
                                $list->checkbox         = '<input type="checkbox" name="lists[id][]" class="item_checkbox"  value="'. $item->id .'">'; 
                                $list->name             =  $cb->name  ??  '';
                                $list->tax_id           =  $cb->tax_id   ??  '';
                                $list->room             =  $item->name_standard   ??  '';
                                $list->cer_link         = '<a class="btn btn-link" href="'.( url('/certify/check_certificate-cb/' . $cb->token)).'" target="_blank">  '.($cb->app_no ?? '').' </a>';  
                                $list->purpose_type     =  array_key_exists($cb->standard_change,$purpose_type) ? $purpose_type[$cb->standard_change] : null; 
                                $list->accereditatio_no =   $item->accereditatio_no   ??  '';
                                $list->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  
                                $certilab_file          = CertiCBFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_cb_id',$cb->id)->where('state',1)->first();   
                                if(!is_null($certilab_file)){
                                    $list->cer_file     =   ' <a href="'.(url('certify/check/file_cb_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                }else{
                                    $list->cer_file     =  '';
                                }
                                $datas[]                = $list;  
                             }
                        }
                    }
                }
            }else{
                
                $signer->certify            =  'ห้องปฏิบัติการ';
                $signer->certificate_type    = 3;
                $table              =  (new CertificateExport)->getTable();
                $export             =  CertificateExport::where('sign_id',$signer->id)->whereIn('status',[2])->get();   
               
                if(count($export) > 0){
                    foreach($export as $key => $item){
                        if(!empty($item->CertiLabTo)){
                            $send_cer_list =   SendCertificateLists::select('id','sign_status')->where('certificate_id',$item->id)->where('certificate_tb',$table)->first();   
                            if( is_null($send_cer_list) || ( !is_null($send_cer_list)  &&  $send_cer_list->sign_status == 4 ) ){
                                $lab                    = $item->CertiLabTo;
                                $list                   = (object)[];
                                $list->id               =  $item->id; 
                                $list->checkbox         = '<input type="checkbox" name="lists[id][]" class="item_checkbox"  value="'. $item->id .'">'; 
                                $list->name             =  $lab->name ??  '';
                                $list->tax_id           =  $lab->tax_id ?? '';
                                $list->room             =  $lab->lab_name ?? ''; 
                                $list->cer_link         = '<a class="btn btn-link" href="'.(route('check_certificate.show', ['cc' => $lab->id])).'" target="_blank">  '.($lab->app_no ?? '').' </a>';  
                                $list->purpose_type     =  array_key_exists($lab->purpose_type,$purpose_type) ? $purpose_type[$lab->purpose_type] : null; 
                                $list->accereditatio_no =   $item->accereditatio_no  ??  '';
                                $list->cer_pdf          =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$item->id.'/3')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>';  

                                $certilab_file          = $lab->CertLabsFileScope;   
                                if(is_null($certilab_file)){
                                    $certilab_file          = CertLabsFileAll::select('attach_pdf','attach_pdf_client_name')->where('app_certi_lab_id',$lab->id)->where('state',1)->first();   
                                }
                                
                                if(!is_null($certilab_file)){
                                    $list->cer_file     =   ' <a href="'.(url('certify/check/file_client/'.$certilab_file->attach_pdf.'/'.( !empty($certilab_file->attach_pdf_client_name) ? $certilab_file->attach_pdf_client_name :  basename($certilab_file->attach_pdf)  ))).'" target="_blank"> <i class="fa fa-paperclip" aria-hidden="true"></i> </a>';
                                }else{
                                    $list->cer_file     =  '';
                                }
                                $datas[]                = $list;  
                            }
                        }
                    }
                }
            }    
          
            
 
     
          return response()->json([
                                    'message' =>  true,
                                    'signer' => $signer,
                                    'datas' => $datas
                                 ]);
        }else{
         return response()->json([
                                'message' =>  false
                                ]);
        }

    }

    public function view_pdf($id,$cer,$type ='I')
    {
       
        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
         ]);         

        if($type == 'I'){
            $mpdf->SetWatermarkText("DRAFT");
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.15;
        }

        if($cer == 3){ // ห้องปฏิบัติการ
            // dd('aha');
            $cer_exp_lab = CertificateExport::findOrFail($id);
      
            if(!is_null($cer_exp_lab)){
                
                $certi_lab = CertiLab::findOrFail($cer_exp_lab->certificate_for);
                    if(!is_null($certi_lab)){
                    $no = '17025';
                    $formula = Formula::where('title', 'like', '%'.$no.'%')
                                            ->whereState(1)->first();
                    //ข้อมูลภาพ QR Code
                    if(!is_null($certi_lab->attach_pdf) && $certi_lab->attach_pdf != '' ){
                    $url       =       url('/certify/check_files_lab/'. rtrim(strtr(base64_encode($certi_lab->id), '+/', '-_'), '=') );
                        //ข้อมูลภาพ QR Code
                    $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                                        ->size(500)->errorCorrection('H')
                                        ->generate($url);
                    }
                    // dd($certi_lab->attach_pdf,$formula,$url);

                        if(!empty($cer_exp_lab->set_format)){
                            $set_format = explode(",",$cer_exp_lab->set_format);
                            if(!empty($set_format)){
                                $format_name        =   !empty($set_format[0]) ?  $set_format[0] : '80';
                                $format_names_en    =   !empty($set_format[1]) ?  $set_format[1] : '135';
                                $format_address_th  =   !empty($set_format[2]) ?  $set_format[2] :  '100';
                                $format_address_en  =   !empty($set_format[3]) ?  $set_format[3] :  '120';
                            }else{
                                $format_name = '80';
                                $format_names_en = '135';
                                $format_address_th = '100';
                                $format_address_en = '120';
                            }
                        }else{
                                $format_name = '80';
                                $format_names_en = '135';
                                $format_address_th = '100';
                                $format_address_en = '120';
                        }       
        
                        // ชื่อภาษาไทย
                        $names         = !empty($cer_exp_lab->lab_name) ?  self::format_data($cer_exp_lab->lab_name,$format_name)  : [];
       
                        // ชื่อภาษาอังกฤษ
                         $names_en     = !empty($cer_exp_lab->lab_name_en) ?  self::format_data2('('.$cer_exp_lab->lab_name_en.')',$format_names_en)  : [];
                        
                        // ตั้งอยู่เลขที่ Th
                         $address_th  = self::FormatAddress($cer_exp_lab);
                         $address_ths = !empty($address_th) ?  self::format_data($address_th,$format_address_th)  : [];
         
                         // ตั้งอยู่เลขที่ en
                         $address_en  = self::FormatAddressEn($cer_exp_lab);
                         $address_ens =  !empty($address_en) ?  self::format_data2($address_en,$format_address_en)  : [];

                         $condition_th = !empty($formula->condition_th) ? self::format_data($formula->condition_th,65)   : [] ;
                         $condition_en = !empty($formula->condition_en) ? self::format_data2($formula->condition_en,120)   : [] ;
                        

                        $send_list = SendCertificateLists::where('certificate_id',$cer_exp_lab->id)->where('certificate_tb', (new CertificateExport)->getTable())->first();

                   


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
                            $sign_path        =   '<span style="color:#ffffff;"> &emsp;</span>';
                            //  $sign_name  = '';
                            //  $sign_position  = '';
                            $sign_name        = !empty($send_list->send_certificates_to->sign_name) ? '('.$send_list->send_certificates_to->sign_name.')' : null;
                            $sign_position    = !empty($send_list->send_certificates_to->sign_position) ? $send_list->send_certificates_to->sign_position : null;
                         
                        }
      
                     

                        $data_export = ['certificate'        => $cer_exp_lab->certificate_no ?? null ,
                                        'app_no'             => $certi_lab->app_no,
                                        'names'              =>  $names,  
                                        'names_en'           => $names_en,
                                        'address_ths'        => $address_ths,
                                        'address_ens'        => !empty($address_en) ? '('.$address_en.')' : '' ,
                                        'formula'            =>  isset($cer_exp_lab->formula) ?   $cer_exp_lab->formula : '', 
                                        'formula_en'         =>  isset($cer_exp_lab->formula_en) ?   $cer_exp_lab->formula_en : '',
                                        'condition_th'       =>  $condition_th ,
                                        'condition_en'       =>  !empty($formula->condition_en) ? '('.$formula->condition_en.')' : '' ,
                                        'accereditatio_no'   => $cer_exp_lab->accereditatio_no,
                                        'accereditatio_no_en'=> $cer_exp_lab->accereditatio_no_en,
                                        'date_start'         =>  $cer_exp_lab->certificate_date_start,
                                        'date_start_en'      =>  !empty($cer_exp_lab->certificate_date_start) ? HP::formatDateENertify($cer_exp_lab->certificate_date_start) : null
                                    ]; 
             
                  
                    $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                    $mpdf->AddPage('P');
                    $html  = view('certify/send-certificates/pdf.lab', $data_export);
                    $mpdf->WriteHTML($html);
       
                    $footer  = view('certify/send-certificates/pdf.footer-lab',[ 'image_qr'          => isset($image_qr) ? $image_qr : null,
                                                                                 'url'               => isset($url) ? $url : null,
                                                                                 'sign_path'         =>  $sign_path,
                                                                                 'sign_name'         =>  $sign_name,
                                                                                 'sign_position'     =>  $sign_position,
                                                                                 'sign_instead'      =>  $cer_exp_lab->sign_instead ?? null
                                                                              ]);
                    
                    $mpdf->SetHTMLFooter($footer);
  
                    $title = "ใบรับรองห้องปฏิบัติการ".date('Ymd_hms').".pdf";  
                    $mpdf->SetTitle($title);
                    // dd($footer);
                     if($type == 'F'){
                        
                            $object 		= (object)[]; 
                          $path             = public_path('uploads/');
                          $attach_path  = 'files/sendcertificatelists/'.($certi_lab->tax_id ?? '0000000000000');
                          if(!File::isDirectory($path.$attach_path)){
                              File::makeDirectory($path.$attach_path, 0777, true, true);
                          }  
                          $file_path = $path.$attach_path.'/'.$title;
                          $mpdf->Output($file_path, "F");

                        //   dd($attach_path);
                         if(is_file($file_path)){
                            
                           //  อัพไฟล์ ftp
                            $file_ftp     = Storage::put($attach_path.'/'.$title, $file_path);
                            
                            if($file_ftp == true){
                              $object->file_path 	        =  $file_path;
                              $object->certificate_no 	    =  $cer_exp_lab->certificate_no;
                              $object->attach_path 	        =  $attach_path;
                              $object->path 	            =  $path.$attach_path;
                              
                              $object->app_no 	            =  $certi_lab->app_no ?? null;
                              $object->name 	            =  $certi_lab->name ?? null;
                              $object->tax_id 	            =  $certi_lab->tax_id ?? null;

                            //          $path_scope = 'files/applicants/check_files';
                            //   if(!empty($certi_lab->attach_pdf) && HP::checkFileStorage($path_scope.'/' .$certi_lab->attach_pdf)){
                            //     HP::getFileStorage($path_scope.'/' .$certi_lab->attach_pdf);
                            //          $object->attachment 	        =    $path.$path_scope.'/' .$certi_lab->attach_pdf;
                            //   }else{
                            //          $object->attachment 	        =  '';
                            //   }
                            
                              $object->sign_id 	            =  !empty($send_list->send_certificates_to->sign_id) ? $send_list->send_certificates_to->sign_id : null;
                              $object->list_id 	            =  !empty($send_list->id) ? $send_list->id : null;

                            //   dd($send_list->send_certificates_to);
                            }
                          }  
                        //   dd($object);
                          return $object;
                     }else{
                        // dd($send_list->sign_path,$sign_path, $sign_name,$sign_position,$cer_exp_lab->sign_instead);
                        $mpdf->Output($title, $type);
                     } 

                     
                }   	                        
            }
            
        }else if($cer == 2){  //หน่วยตรวจสอบ
   
            $cer_exp_ib = CertiIBExport::findOrFail($id);	
            if(!is_null($cer_exp_ib)){
                $certi_ib = CertiIb::findOrFail($cer_exp_ib->app_certi_ib_id);
                if(!is_null($certi_ib)){
                            $file = CertiIBFileAll::where('state',1)
                                                    ->where('app_certi_ib_id',$certi_ib->id)
                                                    ->first();
                            // dd($file);
                             if(!empty($cer_exp_ib->set_format)){
                                $set_format = explode(",",$cer_exp_ib->set_format);
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

                            $no = '17020';
                            $formula = Formula::where('title', 'like', '%'.$no.'%')
                                                    ->whereState(1)->first();
                    
                            if(!is_null($file) && !is_null($file->attach_pdf)){
                                $url  =   url('/certify/check_files_ib/'. rtrim(strtr(base64_encode($certi_ib->id), '+/', '-_'), '=') );
                                $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                                            ->size(500)->errorCorrection('H')
                                            ->generate($url);
                            }
                            $type_unit = ['1'=>'A','2'=>'B','3'=>'C'];		
        
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
       
                        $data_export = ['certificate'        => $cer_exp_ib->certificate ?? null ,
                                        'app_no'             => $certi_ib->app_no,
                                        'names'              =>$names,  
                                        'names_en'           => $names_en,
                                        'address_ths'         => $address_ths,
                                        'address_ens'         => !empty($address_ens) ? $address_ens : [],
                                        'formula'            =>  isset($cer_exp_ib->formula) ?   $cer_exp_ib->formula : '&emsp;', 
                                        'formula_en'         =>  isset($cer_exp_ib->formula_en) ?   $cer_exp_ib->formula_en : '&emsp;',
                                        'condition_th'       =>  $condition_th ,
                                        'condition_en'       => !empty($formula->condition_en) ? '('.$formula->condition_en.')' : '' ,
                                        'accereditatio_no'   => $cer_exp_ib->accereditatio_no,
                                        'accereditatio_no_en' => $cer_exp_ib->accereditatio_no_en,
                                        'date_start'         =>  $cer_exp_ib->date_start,
                                        'date_start_en'      =>  !empty($cer_exp_ib->date_start) ? HP::formatDateENertify($cer_exp_ib->date_start) : null
                                    ]; 
             
                    // dd($cer_exp_ib);
                    $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                    $mpdf->AddPage('P');
                    $html  = view('certify/send-certificates/pdf.ib', $data_export);
                    $mpdf->WriteHTML($html);

                    $footer  = view('certify/send-certificates/pdf.footer-ib',[ 'image_qr'           => isset($image_qr) ? $image_qr : null,
                                                                                 'url'               => isset($url) ? $url : null,
                                                                                 'sign_path'         =>  $sign_path,
                                                                                 'sign_name'         =>  $sign_name,
                                                                                 'sign_position'     =>  $sign_position,
                                                                                 'sign_instead'      =>  $cer_exp_ib->sign_instead ?? null
                                                                              ]);
                    $mpdf->SetHTMLFooter($footer);

                    $title = "ใบรับรองหน่วยตรวจ".date('Ymd_hms').".pdf";
                    $mpdf->SetTitle($title);

                    if($type == 'F'){
                        $object 		= (object)[]; 
                      $path             = public_path('uploads/');
                      $attach_path  = 'files/sendcertificatelists/'.($certi_ib->tax_id ?? '0000000000000');
                      if(!File::isDirectory($path.$attach_path)){
                          File::makeDirectory($path.$attach_path, 0777, true, true);
                      }  
                      $file_path = $path.$attach_path.'/'.$title;
                      $mpdf->Output($file_path, "F");
                     if(is_file($file_path)){
                          //  อัพไฟล์ ftp
                        $file_ftp     = Storage::put($attach_path.'/'.$title, $file_path);
                        if($file_ftp == true){
                          $object->file_path 	        =  $file_path;
                          $object->certificate_no 	    =  $cer_exp_ib->certificate;
                          $object->attach_path 	        =  $attach_path;
                          $object->path 	            =  $path.$attach_path;

                          $object->app_no 	            =  $certi_ib->app_no ?? null;
                          $object->name 	            =  $certi_ib->name ?? null;
                          $object->tax_id 	            =  $certi_ib->tax_id ?? null;

                        //   $path_scope =  'files/applicants/check_files_ib';
                        //   if(!empty($file->attach_pdf) && HP::checkFileStorage($file->attach_pdf )){
                        //        HP::getFileStorage($file->attach_pdf);
                        //        $object->attachment 	        =    $path.$file->attach_pdf;
                        //   }else{
                        //        $object->attachment 	        =  '';
                        //   }
                          $object->sign_id 	            =  !empty($send_list->send_certificates_to->sign_id) ? $send_list->send_certificates_to->sign_id : null;
                          $object->list_id 	            =  !empty($send_list->id) ? $send_list->id : null;
                        }
                      }  
                       return $object;
                    }else{
                        $mpdf->Output($title, $type);
                    } 

                } 
            }
        }else if($cer == 1){  //ห้องหน่วยรับรอง
            
            $cer_exp_cb = CertiCBExport::findOrFail($id);	
            if(!is_null($cer_exp_cb)){
                $CertiCb = CertiCb::findOrFail($cer_exp_cb->app_certi_cb_id);

                $file = CertiCBFileAll::where('state',1)
                                        ->where('app_certi_cb_id',$CertiCb->id)
                                        ->first();      
    

                 $formula = Formula::where('id', 'like', $CertiCb->type_standard)
                                    ->whereState(1)->first();
                
                if(!is_null($file) && !is_null($file->attach_pdf) ){
                     $url  =   url('/certify/check_files_cb/'. rtrim(strtr(base64_encode($CertiCb->id), '+/', '-_'), '=') );
                    //ข้อมูลภาพ QR Code
                     $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                                  ->size(500)->errorCorrection('H')
                                  ->generate($url);
    
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
                                        'date_start'         => $cer_exp_cb->date_start,
                                        'date_start_en'      => !empty($cer_exp_cb->date_start) ? HP::formatDateENertify($cer_exp_cb->date_start) : null
                                    ]; 
             
                        $mpdf->SetDefaultBodyCSS('background', "url('images/certificate01.png')");
                        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
                        $mpdf->AddPage('P');
                        $html  = view('certify/send-certificates/pdf.cb', $data_export);
                        $mpdf->WriteHTML($html);

                        $footer  = view('certify/send-certificates/pdf.footer-cb',[ 'image_qr'          => isset($image_qr) ? $image_qr : null,
                                                                                    'url'               => isset($url) ? $url : null,
                                                                                    'image'             =>  !empty($CertiCb->CertiCBFormulasTo->image) ?  $CertiCb->CertiCBFormulasTo->image : '-',
                                                                                    'check_badge'       => isset($cer_exp_cb->check_badge) ? $cer_exp_cb->check_badge : null,
                                                                                    'sign_path'         =>  $sign_path,
                                                                                    'sign_name'         =>  $sign_name,
                                                                                    'sign_position'     =>  $sign_position,
                                                                                    'sign_instead'      =>  $cer_exp_cb->sign_instead ?? null
                                                                                  ]); 
                                                                                    
                        $mpdf->SetHTMLFooter($footer);

                        $title = "ใบรับรองหน่วยรับรอง".date('Ymd_hms').".pdf";
                        $mpdf->SetTitle($title);
                     
                        
                        
                        if($type == 'F'){
                                $object 		= (object)[]; 
                                $path             = public_path('uploads/');
                                $attach_path  = 'files/sendcertificatelists/'.($CertiCb->tax_id ?? '0000000000000');
                            if(!File::isDirectory($path.$attach_path)){
                                File::makeDirectory($path.$attach_path, 0777, true, true);
                            }  
                                $file_path = $path.$attach_path.'/'.$title;
                                $mpdf->Output($file_path, "F");
                            if(is_file($file_path)){
                                 //  อัพไฟล์ ftp
                                $file_ftp     = Storage::put($attach_path.'/'.$title, $file_path);
                                if($file_ftp == true){
                                    $object->file_path 	        =  $file_path;
                                    $object->certificate_no 	=  $cer_exp_cb->certificate;
                                    $object->attach_path 	    =  $attach_path;
                                    $object->path 	            =  $path.$attach_path;

                                    $object->app_no 	        =  $CertiCb->app_no ?? null;
                                    $object->name 	            =  $CertiCb->name ?? null;
                                    $object->tax_id 	        =  $CertiCb->tax_id ?? null;

                                    // $path_scope =  'files/applicants/check_files_cb';
                                    // if(!empty($file->attach_pdf) && HP::checkFileStorage($file->attach_pdf)){
                                    //      HP::getFileStorage($file->attach_pdf);
                                    //      $object->attachment 	        =  $path.$file->attach_pdf;
                                    // }else{
                                    //      $object->attachment 	        =  '';
                                    // } 
                                    $object->sign_id 	        =  !empty($send_list->send_certificates_to->sign_id) ? $send_list->send_certificates_to->sign_id : null;
                                    $object->list_id 	        =  !empty($send_list->id) ? $send_list->id : null;
                                }
                            }  
                                return $object; 
                        }else{
                            $mpdf->Output($title, $type);
                        } 
                

            }
        }
    }

    public function format_data($str,$number=55){
       
        $result =      !empty($str) ?  self::get_segment_array($str,$number,16)  : [];

        // if(count($result) == 2){
        //     $number2    = ($number*2);
        //     $result     =     !empty($str) ?  self::get_segment_array($str,$number2,15)  : [];
        // }else    if(count($result) == 3){
        //     $number3    = ($number*3);
        //     $result     =     !empty($str) ?  self::get_segment_array($str,$number3,14)  : [];
        //  }
        return $result;
    }

    public function format_data2($str,$number=100){
        $result =      !empty($str) ?  self::get_segment_array($str,$number,10)  : [];
        if(count($result) == 3){
             $number2    = ($number*2);
            $result     =     !empty($str) ?  self::get_segment_array($str,$number2,9)  : [];
           if(count($result) == 3){
              $number3    = ($number*3);
              $result     =     !empty($str) ?  self::get_segment_array($str,$number3,8)  : [];
           }
        }
        return $result;
    }



    public function get_segment_array($str,$number =75,$font = 16){
 
        $segment    =  new   Segment;
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
            $object  =  (object)[];
            $object->font =   'font-'.$font;
            $object->title =    implode('', $data1);
            $result[] =    $object;
        }

        if(count($data2) > 0){
            $object  =  (object)[];
            $object->font =   'font-'.$font;
            $object->title =    implode('', $data2);
            $result[] =    $object;
        }

        if(count($data3) > 0){
            $object  =  (object)[];
            $object->font =   'font-'.$font;
            $object->title =    implode('', $data3);
            $result[] =    $object;
        }
        return  $result;
    }

 
    private function FormatAddress($request,$cer = null){

        $address   = [];

        if($cer == 2 || $cer == 1){
            $address[] = $request->address;

            if($request->allay!=''){
            $address[] =  "หมู่ที่ " . $request->allay;
            }
    
            if($request->village_no!='' && $request->village_no !='-'  && $request->village_no !='--'){
            $address[] = "ซอย". $request->village_no;
            }
    
            if($request->road!='' && $request->road !='-'  && $request->road !='--'){
            $address[] =  "ถนน"  . $request->road;
            }
            if($request->district_name!=''){
                $address[] =  (($request->province_name=='กรุงเทพมหานคร')?" แขวง":" ตำบล").$request->district_name;
            }
            if($request->amphur_name!=''){
                $address[] =  (($request->province_name=='กรุงเทพมหานคร')?" เขต":" อำเภอ").$request->amphur_name;
            }
    
            if($request->province_name=='กรุงเทพมหานคร'){
                $address[] =  " ".$request->province_name;
            }else{
                $address[] =  " จังหวัด".$request->province_name;
            }

        }else{
            if(isset($request->address_no)){
                $address[] = $request->address_no;
             }
            if($request->address_moo!=''){
              $address[] =  "หมู่ที่ " . $request->address_moo;
            }

            if($request->address_soi!='' && $request->address_soi !='-'  && $request->address_soi !='--'){
              $address[] = "ซอย"  . $request->address_soi;
            }

            if($request->address_road!='' && $request->address_road !='-'  && $request->address_road !='--'){
              $address[] =  "ถนน"  . $request->address_road;
            }

            if($request->address_subdistrict!=''){
                $address[] =  (($request->address_province=='กรุงเทพมหานคร')?" แขวง":" ตำบล").$request->address_subdistrict;
             }

            if($request->address_district!=''){
                $address[] =  (($request->address_province=='กรุงเทพมหานคร')?" เขต":" อำเภอ").$request->address_district;
            }

            if($request->address_province=='กรุงเทพมหานคร'){
                $address[] =  " ".$request->address_province;
            }else{
                $address[] =  " จังหวัด".$request->address_province;
            }
        }     

        return implode(' ', $address);
    }
        
        private function FormatAddressEn($request,$cer = null){
            $address   = [];


            if($cer == 2 || $cer == 1){
                $address[] = $request->address_en;

                if($request->allay_en!=''){
                  $address[] =    'Moo '.$request->allay_en;
                }
        
                if($request->village_no_en!='' && $request->village_no_en !='-'  && $request->village_no_en !='--'){
                  $address[] =   $request->village_no_en;
                }
                if($request->road_en!='' && $request->road_en !='-'  && $request->road_en !='--'){
                    $address[] =   $request->road_en.',';
                }
                if($request->district_name_en!='' && $request->district_name_en !='-'  && $request->district_name_en !='--'){
                    $address[] =   $request->district_name_en.',';
                }
                if($request->amphur_name_en!='' && $request->amphur_name_en !='-'  && $request->amphur_name_en !='--'){
                    $address[] =   $request->amphur_name_en.',';
                }
                if($request->province_name_en!='' && $request->province_name_en !='-'  && $request->province_name_en !='--'){
                    $address[] =   $request->province_name_en;
                }
             
            }else{
                if(isset($request->address_no_en)){
                    $address[] = $request->address_no_en;
                 }
                 
                if($request->address_moo!=''){
                  $address[] =    'Moo '.$request->address_moo_en.',';
                }
    
                if($request->address_soi_en!='' && $request->address_soi_en !='-'  && $request->address_soi_en !='--'){
                  $address[] =   $request->address_soi_en.',';
                }
                if($request->address_road_en!='' && $request->address_road_en !='-'  && $request->address_road_en !='--'){
                    $address[] =   $request->address_road_en.',';
                }
    
                if($request->address_subdistrict_en!='' && $request->address_subdistrict_en !='-'  && $request->address_subdistrict_en !='--'){
                    $address[] =   $request->address_subdistrict_en.',';
                }
                if($request->address_district_en!='' && $request->address_district_en !='-'  && $request->address_district_en !='--'){
                    $address[] =   $request->address_district_en.',';
                }
                if($request->address_province_en!='' && $request->address_province_en !='-'  && $request->address_province_en !='--'){
                    $address[] =   $request->address_province_en;
                }
            }


            return implode(' ', $address);
        }

        private function Format_Address_En($request,$cer = null){
            $address   = '';


            if($cer == 2 || $cer == 1){
                  $address .=   '(';
                  $address .=  $request->address_en;
                if($request->allay_en!=''){
                  $address .=    'Moo '.$request->allay_en;
                }
        
                if($request->village_no_en!='' && $request->village_no_en !='-'  && $request->village_no_en !='--'){
                  $address .=   ' '.$request->village_no_en;
                }
                if($request->road_en!='' && $request->road_en !='-'  && $request->road_en !='--'){
                    $address .=   ' '.$request->road_en.',';
                }
                if($request->district_name_en!='' && $request->district_name_en !='-'  && $request->district_name_en !='--'){
                    $address .=   ' '.$request->district_name_en.',';
                }
                if($request->amphur_name_en!='' && $request->amphur_name_en !='-'  && $request->amphur_name_en !='--'){
                    $address .=   ' '.$request->amphur_name_en.',';
                }
                if($request->province_name_en!='' && $request->province_name_en !='-'  && $request->province_name_en !='--'){
                    $address .=   ' '.$request->province_name_en;
                }
                   $address .=   ')';
            }else{
                     $address .=   '(';
                 if(isset($request->address_no_en)){
                    $address .=   $request->address_no_en;
                 }
                 
                if($request->address_moo!=''){
                    $address .=    'Moo '.$request->address_moo_en.',';
                }
    
                if($request->address_soi_en!='' && $request->address_soi_en !='-'  && $request->address_soi_en !='--'){
                  $address .=    ' '.$request->address_soi_en.',';
                }
                if($request->address_road_en!='' && $request->address_road_en !='-'  && $request->address_road_en !='--'){
                    $address .=    ' '.$request->address_road_en.',';
                }
    
                if($request->address_subdistrict_en!='' && $request->address_subdistrict_en !='-'  && $request->address_subdistrict_en !='--'){
                    $address .=    ' '.$request->address_subdistrict_en.',';
                }
                if($request->address_district_en!='' && $request->address_district_en !='-'  && $request->address_district_en !='--'){
                    $address .=   ' '. $request->address_district_en.',';
                }
                if($request->address_province_en!='' && $request->address_province_en !='-'  && $request->address_province_en !='--'){
                    $address .=   ' '.$request->address_province_en;
                }
                  $address .=   ')';
            }


            return $address;
        }

}
