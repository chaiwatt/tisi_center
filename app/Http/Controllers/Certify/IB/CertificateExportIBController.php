<?php

namespace App\Http\Controllers\Certify\IB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use HP;
use Illuminate\Support\Facades\DB;
use QrCode;
use File;
use Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\User;
use App\Models\Bcertify\Formula;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\ApplicantIB\CertiIBCheck;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\ApplicantIB\CertiIBFileAll;
use App\Models\Certify\ApplicantIB\CertiIbExportMapreq;
use App\CertificateExportIB;
use App\Models\Sso\User AS SSO_User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\IB\IBExportMail;
use App\Models\Besurv\Signer;
use stdClass;

class CertificateExportIBController extends Controller
{


    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_ib/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['perPage'] = $request->get('perPage', 10);


            $Query = new CertiIBExport;
            $Query = $Query->select('app_certi_ib_export.*');
            if ($filter['filter_status']!='') {
                $Query = $Query->where('status', $filter['filter_status']);
            }else{
                $Query = $Query->where('status', '!=', '99');
            }
            if ($filter['filter_search'] != '') {
                $CertiIb  = CertiIb::where(function($query) use($filter){
                                            $query->where('app_no', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('org_name', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('tax_id', 'like', '%'.$filter['filter_search'].'%');

                                        })
                                        ->select('id');

                $Query = $Query->where(function($query) use($filter, $CertiIb ){
                                    $query->where('app_no', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('certificate', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('name_unit', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('name_unit_en', 'like', '%'.$filter['filter_search'].'%')
                                            ->OrwhereIn('app_certi_ib_id', $CertiIb);
                                });
                                
            }

            if ($filter['filter_start_date'] != '' && $filter['filter_end_date'] != '') {
                $date_start = HP::convertDate($filter['filter_start_date'],true);
                $date_end = HP::convertDate($filter['filter_end_date'],true);

                $Query = $Query->whereDate('date_start','>=', $date_start )->whereDate('date_end','<=', $date_end );
            }else if($filter['filter_start_date'] != '' && $filter['filter_end_date'] == ''){
                $date_start = HP::convertDate($filter['filter_start_date'],true);
                $Query = $Query->whereDate('date_start', $date_start );
            }else if($filter['filter_start_date'] == '' && $filter['filter_end_date'] != ''){
                $date_end = HP::convertDate($filter['filter_end_date'],true);
                $Query = $Query->whereDate('date_end','<=', $date_end );
            }
        
            //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_ib_check','app_certi_ib_check.app_certi_ib_id','=','app_certi_ib_export.app_certi_ib_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }
            
            $export_ib = $Query->orderby('id','desc')
                                        // ->sortable()
                                        ->paginate($filter['perPage']);

            return view('certify/ib.certificate_export_ib.index', compact('export_ib', 'filter'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('add-'.$model)) {

            $app_token = $request->get('app_token');

            $app_no = [];
            if( !empty($app_token) ){
                // $app_no = CertiIb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                //                     ->where('token', $app_token )
                //                     ->orderby('id','desc')
                //                     ->pluck('title', 'id');
                 $requests =   CertiIb::where('token', $app_token)->first();
                 $app_no[$requests->id] = $requests->name . " ( $requests->app_no )";
            }else{
                        //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
                        if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                            $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                            if(count($check) > 0 ){
                                $app_no= CertiIb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                                                    ->whereNotIn('status',[0,4,5])
                                                    ->whereIn('id',$check)
                                                    ->whereIn('status',[17,18])
                                                    ->orderby('id','desc')
                                                    ->pluck('title', 'id');
                            }
                        }else{
                            $app_no = CertiIb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                                                        ->whereNotIn('status',[0,4,5])
                                                        ->whereIn('status',[17,18])
                                                        ->orderby('id','desc')
                                                        ->pluck('title', 'id');
                        }
 
            }
            return view('certify/ib.certificate_export_ib.create',['app_no' => $app_no,'app_token' => $app_token,'attach_path'=> $this->attach_path]);
        }
        abort(403);

    }

    
    public function CopyFile($old_path_file, $new_path_file)
    {
        if( !empty($old_path_file) &&  Storage::exists("/".$old_path_file)){

            $cut = explode("/", $old_path_file );
            $file_name = end($cut);
            $file_extension = pathinfo( $file_name , PATHINFO_EXTENSION );

            $path = $new_path_file.'/'.(str_random(10).'-date_time'.date('Ymd_hms') . '.').'.'.$file_extension;
            Storage::copy($old_path_file, $path );

            return $path;

        }
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

        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('add-'.$model)) {
            $request->validate([
                'app_certi_ib_id' => 'required',
            ]);

            if($request->submit == "submit"){
                $requestData = $request->all();

                $certi_ib = CertiIb::findOrFail($request->app_certi_ib_id);
                $config = HP::getConfig();
                // if(!$request->status == 2 && !is_null($certi_ib) && $certi_ib->status <= 18){ 
                //     $certi_ib->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                //     $certi_ib->save();
                // }

                if(in_array($request->status, ['0','1','2'])){
                    if(!is_null($certi_ib) && $certi_ib->status <= 18){ 
                        $certi_ib->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                        $certi_ib->save();
                    }

                 }else  if($request->status == 3){ 
                    $certi_ib->status  =  19;  // ลงนามเรียบร้อย
                    $certi_ib->save();
                 }else  if($request->status == 4){ 
                        $certi_ib->status  =  20;  // จัดส่งใบรับรองระบบงาน
                        $certi_ib->save();
                 }

                $requestData['created_by'] =   auth()->user()->runrecno;
        
				$trader_tb = SSO_User::find($certi_ib->created_by);

                $requestData['type_unit'] = $certi_ib->type_unit ?? null;
                $requestData['date_start'] = (is_null($request->date_start) || empty($request->date_start))? NULL : HP::convertDate($request->date_start,true);
                $requestData['date_end'] = (is_null($request->date_start) || empty($request->date_start))? NULL : HP::convertDate($request->date_end,true);
                $requestData['org_name'] = !is_null($trader_tb)?$trader_tb->name:null;
                $requestData['cer_type']        =  (!empty($config->check_electronic_certificate) && $config->check_electronic_certificate == 1)?2:1;
                $requestData['contact_name']    =  $certi_ib->contactor_name ?? null;
                $requestData['contact_mobile']  =  $certi_ib->telephone ?? null;
                $requestData['contact_tel']     =  $certi_ib->contact_tel ?? null;
                $requestData['contact_email']   =  $certi_ib->email ?? null;
                if($request->hasFile('attachs')) {
                    $files = $request->file('attachs');
                    $requestData['attach_client_name'] = $files->getClientOriginalName();
                    $requestData['attachs']     =  $this->storeFile($request->attachs, $certi_ib->app_no) ;
                }
        
                $export_ib = CertiIBExport::where('app_certi_ib_id', $certi_ib->id )->first();
                if( !is_null( $export_ib) ){
                    $requestData['sign_instead'] = isset($request->sign_instead)? 1:0;
                    $export_ib->update($requestData);
                }else{
                    $requestData['sign_instead'] = isset($request->sign_instead)? 1:0;
                    $export_ib = CertiIBExport::create($requestData);
                }

                if( isset($requestData['detail']) ){

                    $list_detail = $requestData['detail'];
    
                    $new_path_file = $this->attach_path.$certi_ib->app_no ;
                                 CertiIBFileAll::where('app_certi_ib_id', $export_ib->app_certi_ib_id)->update(['state' => 0]);
                    foreach( $list_detail AS $item ){

                       if(isset($item['id'])){
                            $obj =     CertiIBFileAll::findOrFail($item['id']);
                            if(is_null($obj)){
                            $obj = new CertiIBFileAll;
                            } 
                        }else{
                            $obj = new CertiIBFileAll;
                        }

                            $obj->app_no            =  $export_ib->app_no;
                            $obj->app_certi_ib_id   =  $export_ib->app_certi_ib_id;
                            $obj->ref_id            =  $export_ib->id;
                            $obj->ref_table         =  (new CertiIBExport)->getTable();
                            if( isset($item['file_word']) ){
                                $file_word  =  $this->CopyFile( $item['file_word'], $new_path_file );
                                $obj->attach_client_name = !empty($item['input_file_word_name'])?$item['input_file_word_name']:null;
                                $obj->attach = str_replace($this->attach_path,"",$file_word);
                            }
                            
                            if( isset($item['file_pdf']) ){
                                $file_pdf  =  $this->CopyFile( $item['file_pdf'], $new_path_file );
                                $obj->attach_pdf_client_name = !empty($item['input_file_pdf_name'])?$item['input_file_pdf_name']:null;
                                $obj->attach_pdf = str_replace($this->attach_path,"",$file_pdf);
                            }
    
                            $obj->start_date =  !empty($item['start_date']) ? HP::convertDate($item['start_date'],true) : null;
                            $obj->end_date =  !empty($item['end_date']) ? HP::convertDate($item['end_date'],true) : null;
                            $obj->state = isset($item['state'])?1:null;
                            $obj->save();  
                    }
    
                }

                $this->save_certiib_export_mapreq($certi_ib->id,$export_ib->id);

                $pathfileTemp = 'files/Tempfile/'.($requestData['app_no']);

                if(Storage::directories($pathfileTemp)){
                    Storage::deleteDirectory($pathfileTemp);
                }

                if($export_ib->status == 4){
                    //E-mail
                    $this->set_mail($export_ib,$certi_ib);
                }
                return redirect('certify/certificate-export-ib')->with('flash_message', 'เพิ่ม เรียบร้อยแล้ว');
            }else{
                return  $this->ExportIB($request,$request->app_certi_ib_id);
            }

        }
        abort(403);
    }
    public function storeFile($files, $app_no = 'files_ib',$name =null)
    {
        $no  = str_replace("RQ-","",$app_no);
        $no  = str_replace("-","_",$no);
        if ($files) {
            $attach_path  =  $this->attach_path.$no;
            $file_extension = $files->getClientOriginalExtension();
            $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

            $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $no.'/'.$storageName;
        }else{
            return null;
        }
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
        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('view-'.$model)) {
            $certificateexportib = CertificateExportIB::findOrFail($id);
            return view('certify/ib.certificate_export_ib.show', compact('certificateexportib'));
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
        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('edit-'.$model)) {

            $export_ib = CertiIBExport::findOrFail($id);
            $app_no = $export_ib->app_no ?? null;

			if(is_null($export_ib->org_name)){ 

				$app_no = CertiIb::where('id',$export_ib->app_certi_ib_id)->first(); 
				$export_ib->title =  @$app_no->name; 
			}else{
				$export_ib->title =@$export_ib->org_name;
			}

            $export_ib->date_start = (is_null($export_ib->date_start) ||  empty($export_ib->date_start) )?'': HP::revertDate($export_ib->date_start,true);
            $export_ib->date_end = (is_null($export_ib->date_start) || empty($export_ib->date_start) )? '': HP::revertDate($export_ib->date_end,true) ;
	 
            $certiib_file_all  = $export_ib->CertiIBFileAll;
            
             $attach_path       = $this->attach_path;
            return view('certify.ib.certificate_export_ib.edit', compact('export_ib','certiib_file_all','attach_path'));
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
        $model = str_slug('certificateexportib','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $export_ib =  CertiIBExport::findOrFail(base64_decode($id));
            $certi_ib = CertiIb::findOrFail($export_ib->app_certi_ib_id);

            if($request->submit == "submit"){
            
             $requestData['updated_by'] =   auth()->user()->runrecno;
                // if($request->status <= 2){
                //     if($request->status == 2 && !is_null($certi_ib) && $certi_ib->status <= 18){ 
                //         $certi_ib->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                //         $certi_ib->save();
                //     }
                //         $requestData['date_start'] = (is_null($request->date_start) ||  empty($request->date_start) )? NULL: HP::convertDate($request->date_start,true);
                //         $requestData['date_end'] = (is_null($request->date_start) || empty($request->date_start) ) ? NULL: HP::convertDate($request->date_end,true) ;
                // }else  if($request->status == 4){ 
                //     // $certi_ib->status  =  21 ;  //  เปิดใช้งานใบใบรับรองระบบงาน
                //     $certi_ib->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                //     $certi_ib->save();
                // }
                        $requestData['date_start'] = (is_null($request->date_start) ||  empty($request->date_start) )? NULL: HP::convertDate($request->date_start,true);
                        $requestData['date_end'] = (is_null($request->date_start) || empty($request->date_start) ) ? NULL: HP::convertDate($request->date_end,true) ;
                if(in_array($request->status, ['0','1','2'])){
                    if(!is_null($certi_ib) && $certi_ib->status <= 18){ 
                        $certi_ib->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                        $certi_ib->save();
                    }
                 }else  if($request->status == 3){ 
                    $certi_ib->status  =  19;  // ลงนามเรียบร้อย
                    $certi_ib->save();
                 }else  if($request->status == 4){ 
                        $certi_ib->status  =  20;  // จัดส่งใบรับรองระบบงาน
                        $certi_ib->save();
                 }

                 if($request->hasFile('attachs')) {
                    $files = $request->file('attachs');
                    $requestData['attach_client_name'] = $files->getClientOriginalName();
                    $requestData['attachs']     =  $this->storeFile($request->attachs, $certi_ib->app_no) ;
                }

                $export_ib->update($requestData);
                
                if( isset($requestData['detail']) ){

                    $list_detail = $requestData['detail'];
    
                    $new_path_file = $this->attach_path.$requestData['app_no'];

                    $app_certi_ib_id = CertiIbExportMapreq::where('certificate_exports_id', $export_ib->id)->pluck('app_certi_ib_id');
                    CertiIBFileAll::whereIn('app_certi_ib_id',$app_certi_ib_id)->update(['state' => 0]);

                    foreach( $list_detail AS $item ){
                           if(isset($item['id'])){
                                $obj =     CertiIBFileAll::findOrFail($item['id']);
                                if(is_null($obj)){
                                 $obj = new CertiIBFileAll;
                                } 
                           }else{
                               $obj = new CertiIBFileAll;
                           }
 
                            $obj->app_no            =  $export_ib->app_no;
                            $obj->app_certi_ib_id   =  $export_ib->app_certi_ib_id;
                            $obj->ref_id            =  $export_ib->id;
                            $obj->ref_table         =  (new CertiIBExport)->getTable();
                            if( isset($item['file_word']) ){
                                $file_word  =  $this->CopyFile( $item['file_word'], $new_path_file );
                                $obj->attach_client_name = !empty($item['input_file_word_name'])?$item['input_file_word_name']:null;
                                $obj->attach = str_replace($this->attach_path,"",$file_word);
                            }
                            
                            if( isset($item['file_pdf']) ){
                                $file_pdf  =  $this->CopyFile( $item['file_pdf'], $new_path_file );
                                $obj->attach_pdf_client_name = !empty($item['input_file_pdf_name'])?$item['input_file_pdf_name']:null;
                                $obj->attach_pdf = str_replace($this->attach_path,"",$file_pdf);
                            }
    
                            $obj->start_date =  !empty($item['start_date']) ? HP::convertDate($item['start_date'],true) : null;
                            $obj->end_date =  !empty($item['end_date']) ? HP::convertDate($item['end_date'],true) : null;
                            $obj->state = isset($item['state'])?1:null;
                            $obj->save();  
                    }
    
                }

                
                if( isset($requestData['delete_flie']) ){
                    $list_delete_flie  = $requestData['delete_flie'];
                    foreach($list_delete_flie as $item){
                        $obj =     CertiIBFileAll::findOrFail($item);
                        if(!is_null($obj)){
                            $obj->status_cancel  = 1;
                            $obj->created_cancel =  auth()->user()->getKey();
                            $obj->date_cancel    =  date('Y-m-d H:i:s');
                            $obj->save();
                        }
                    }
                }

                $this->save_certiib_export_mapreq($certi_ib->id,$export_ib->id);

                if($export_ib->status == 4){
                    //E-mail
                    $this->set_mail($export_ib,$certi_ib);
                }

                return redirect('certify/certificate-export-ib')->with('flash_message', 'เรียบร้อยแล้ว');
            }else{
                $export_ib =  CertiIBExport::findOrFail(base64_decode($id));
                return    $this->ExportIB($request,$export_ib->app_certi_ib_id);
            }
        }
        abort(403);

    }

    private function save_certiib_export_mapreq($app_certi_ib_id, $certificate_exports_id)
    {
        $mapreq =  CertiIbExportMapreq::where('app_certi_ib_id',$app_certi_ib_id)->where('certificate_exports_id', $certificate_exports_id)->first();
        if(Is_null($mapreq)){
            $mapreq = new  CertiIbExportMapreq;
        }
        $mapreq->app_certi_ib_id       = $app_certi_ib_id;
        $mapreq->certificate_exports_id = $certificate_exports_id;
        $mapreq->save();
    }

 
    public function apiGetAddress($id){
        $certi_ib = CertiIb::findOrFail($id);
        if(!is_null($certi_ib)){
            $last   = CertiIBExport::where('type_unit',$certi_ib->type_unit)->whereYear('created_at',Carbon::now())->count() + 1;
            $all   = CertiIBExport::count() + 1;
            // $certificate    = Carbon::now()->format("y")."I".sprintf("%03d", $last)."/".sprintf("%04d", $all);
            // $certi_ib->certificate =  $certificate ?? null;
            $certi_ib->certificate = $this->running() ?? null;
            $certi_ib->province_name = $certi_ib->basic_province->PROVINCE_NAME ?? null;
            $certi_ib->province_name_en    =  $certi_ib->basic_province->PROVINCE_NAME_EN ?? null;

            $certi_ib->amphur_name = $certi_ib->amphur_id ?? null;
            $certi_ib->district_name = $certi_ib->district_id ?? null;
            $certi_ib->trader_operater_name = !is_null($certi_ib->EsurvTrader) ? $certi_ib->EsurvTrader->name : null;
            // $certi_ib->amphur_name =  $certi_ib->basic_amphur->AMPHUR_NAME ?? null;
            // $certi_ib->district_name =  $certi_ib->basic_district->DISTRICT_NAME ?? null;

            $no = '17020';
            $formula = Formula::where('title', 'like', '%'.$no.'%')
                                    ->whereState(1)->first();

            $certi_ib->formula =  !is_null($formula) ? $formula->title   : null;
            $certi_ib->formula_en =   !is_null($formula)  ? $formula->title_en   : null;

            $lab_type = ['1'=>'Testing','2'=>'Cal','3'=>'IB','4'=>'CB'];
            $accereditatio_no = '';
            if(array_key_exists("3",$lab_type)){
                $accereditatio_no .=  $lab_type[3].'-';
            }
            if(!is_null($certi_ib->app_no)){
                $app_no = explode('-', $certi_ib->app_no);
                $accereditatio_no .= $app_no[2].'-';
            }
            if(!is_null($last)){
                $accereditatio_no .=  str_pad($last, 3, '0', STR_PAD_LEFT).'-'.(date('Y') +543);
            }
            $certi_ib->accereditatio_no =   $accereditatio_no ? $accereditatio_no : null;
            $certi_ib->date_start =  HP::revertDate(date('Y-m-d'),true);
            $date_end =  HP::DatePlus(date('Y-m-d'),3,'year');
            $certi_ib->date_end = HP::revertDate($date_end,true);
        }
        return response()->json([
            'certi_ib'      => $certi_ib ?? '-',
         ]);
    }

    public function apiGetDate($date)
    {
        $data_date =  HP::DatePlus($date,5,'year');
        $date_end = HP::revertDate($data_date,true);

        return response()->json([
            'date' => $date_end ?? '-',
        ]);
    }


    public function ExportIB($request,$certi_id = null)
    {
		//dd($request);
        if(!is_null($certi_id)){
            $certi_ib = CertiIb::findOrFail($certi_id);
            $file = CertiIBFileAll::where('state',1)
                                    ->where('app_certi_ib_id',$certi_id)
                                    ->first();

             $no = '17020';
             $formula = Formula::where('title', 'like', '%'.$no.'%')
                                    ->whereState(1)->first();

            if(!is_null($file) && !is_null($file->attach_pdf)){
                // $url  =  url('certify/check/files_ib/'.$file->attach_pdf);
                $url  =   url('/certify/check_files_ib/'. rtrim(strtr(base64_encode($certi_id), '+/', '-_'), '=') );
                //  $url  =  url('certify/check/files_ib/'.$certi_ib->id);
                //ข้อมูลภาพ QR Code
                //  $string = mb_convert_encoding($url, 'ISO-8859-1', 'UTF-8');
                 $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                              ->size(500)->errorCorrection('H')
                              ->generate($url);
            }
            $type_unit = ['1'=>'A','2'=>'B','3'=>'C'];

            $request->date_start = (is_null($request->date_start) ||  empty($request->date_start) )? NULL: HP::convertDate($request->date_start,true);
            $request->date_end = (is_null($request->date_end) || empty($request->date_end) ) ? NULL: HP::convertDate($request->date_end,true) ;

 

           $data_export = [
                        'app_no'             => $request->app_no,
                        'name'               =>  isset($request->name_unit) ?  $request->name_unit : '&emsp;',
                        'name_en'            =>  isset($request->name_unit_en) ?   '('.$request->name_unit_en.')' : '&emsp;',
                        'lab_name_font_size' => $this->CalFontSize($request->name_unit),
                        'certificate'        => $request->certificate,
                        'name_unit'          => $request->name_unit ?? null,
                        'lab_name'           =>  $request->lab_name ?? null,
                        'address'            => $this->FormatAddress($request),
                        'lab_name_font_size_address' => $this->CalFontSize($this->FormatAddress($request)),
                        'address_en'         => $this->FormatAddressEn($request),
                        'formula'            =>  isset($request->formula) ?   $request->formula : '&emsp;',
                        'formula_en'         =>  isset($request->formula_en) ?   $request->formula_en : '&emsp;',
                        'accereditatio_no'   => $request->accereditatio_no,
                        'accereditatio_no_en'   => $request->accereditatio_no_en,
                        'date_start'         =>  $request->date_start,
                        'date_end'           => $request->date_end,
                        'date_start_en'      => !empty($request->date_start) ? HP::formatDateENertify($request->date_start) : null ,
                        'date_end_en'        => !empty($request->date_end) ? HP::formatDateENFull($request->date_end) : null ,
                        'type_unit'          =>  array_key_exists($certi_ib->type_unit,$type_unit) ? 'หน่วยตรวจประเภท '.$type_unit[$certi_ib->type_unit] : null,
                        'image_qr'           => isset($image_qr) ? $image_qr : null,
                        'url'                => isset($url) ? $url : null,
                        'attach_pdf'         => isset($file->attach_pdf) ? $file->attach_pdf : null,
                        'condition_th'       => !empty($formula->condition_th ) ? $formula->condition_th  : null ,
                        'condition_en'       => !empty($formula->condition_en ) ? $formula->condition_en  : null ,
                        'lab_name_font_size_condition' => !empty($formula->condition_th) ? $this->CalFontSizeCondition($formula->condition_th)  : '11'
                       ];

         $pdf = PDF::loadView('certify/ib/certificate_export_ib/pdf/certificate-thai', $data_export);
        return $pdf->stream("scope-thai.pdf");

        }
        abort(403);
    }

    private function CalFontSizeAddress($certificate_for){
        $alphas = array_combine(range('A', 'Z'), range('a', 'z'));
        $thais = array('ก','ข', 'ฃ', 'ค', 'ฅ', 'ฆ','ง','จ','ฉ','ช','ซ','ฌ','ญ', 'ฎ', 'ฏ', 'ฐ','ฑ','ฒ'
        ,'ณ','ด','ต','ถ','ท','ธ','น','บ','ป','ผ','ฝ','พ','ฟ','ภ','ม','ย','ร','ล'
        ,'ว','ศ','ษ','ส','ห','ฬ','อ','ฮ', 'ำ', 'า', 'แ');

                if(function_exists('mb_str_split')){
                $chars = mb_str_split($certificate_for);
                }else if(function_exists('preg_split')){
                $chars = preg_split('/(?<!^)(?!$)/u', $certificate_for);
                }

                $i = 0;
                foreach ($chars as $char) {
                    if(in_array($char, $alphas) || in_array($char, $thais)){
                        $i++;
                    }
                }


                if($i>40 && $i<50){
                    $font = 11;
                }  else if($i>50 && $i<60){
                    $font = 10;
                }  else if($i>60 && $i<70){
                    $font = 9;
                }  else if($i>70 && $i<80){
                    $font = 8;
                }  else if($i>80){
                    $font = 7;
                }  else{
                    $font = 12;
                }
                return $font;
      }

     //คำนวนขนาดฟอนต์ของชื่อหน่วยงานผู้ได้รับรอง
     private function CalFontSize($certificate_for){
        $alphas = array_combine(range('A', 'Z'), range('a', 'z'));
        $thais = array('ก','ข', 'ฃ', 'ค', 'ฅ', 'ฆ','ง','จ','ฉ','ช','ซ','ฌ','ญ', 'ฎ', 'ฏ', 'ฐ','ฑ','ฒ'
        ,'ณ','ด','ต','ถ','ท','ธ','น','บ','ป','ผ','ฝ','พ','ฟ','ภ','ม','ย','ร','ล'
        ,'ว','ศ','ษ','ส','ห','ฬ','อ','ฮ', 'ำ', 'า', 'แ');

                if(function_exists('mb_str_split')){
                $chars = mb_str_split($certificate_for);
                }else if(function_exists('preg_split')){
                $chars = preg_split('/(?<!^)(?!$)/u', $certificate_for);
                }

                $i = 0;
                foreach ($chars as $char) {
                    if(in_array($char, $alphas) || in_array($char, $thais)){
                        $i++;
                    }
                }

                // if($i>40 && $i<50){
                //     $font = 12;
                // }  else if($i>50 && $i<60){
                //     $font = 11;
                // }  else if($i>60 && $i<70){
                //     $font = 10;
                // }  else if($i>70 && $i<80){
                //     $font = 9;
                // }  else if($i>80){
                //     $font = 8;
                // }  else{
                //     $font = 12;
                // }
                if($i>60 && $i<70){
                    $font = 10;
                }  else if($i>70 && $i<80){
                    $font = 9;
                }  else if($i>80 && $i<90){
                    $font = 8;
                }  else if($i>90 && $i<100){
                    $font = 7;
                }  else if($i>100 && $i<120){
                    $font = 6;
                }  else if($i>120){
                    $font = 5;
                }  else{
                    $font = 11;
                }

                return $font;

            }

            private function CalFontSizeCondition($certificate_for){
                $alphas = array_combine(range('A', 'Z'), range('a', 'z'));
                $thais = array('ก','ข', 'ฃ', 'ค', 'ฅ', 'ฆ','ง','จ','ฉ','ช','ซ','ฌ','ญ', 'ฎ', 'ฏ', 'ฐ','ฑ','ฒ'
                ,'ณ','ด','ต','ถ','ท','ธ','น','บ','ป','ผ','ฝ','พ','ฟ','ภ','ม','ย','ร','ล'
                ,'ว','ศ','ษ','ส','ห','ฬ','อ','ฮ', 'ำ', 'า', 'แ');

                        if(function_exists('mb_str_split')){
                        $chars = mb_str_split($certificate_for);
                        }else if(function_exists('preg_split')){
                        $chars = preg_split('/(?<!^)(?!$)/u', $certificate_for);
                        }

                        $i = 0;
                        foreach ($chars as $char) {
                            if(in_array($char, $alphas) || in_array($char, $thais)){
                                $i++;
                            }
                        }

                        if($i>60 && $i<70){
                            $font = 10;
                        }  else if($i>70 && $i<80){
                            $font = 9;
                        }  else if($i>80 && $i<90){
                            $font = 8;
                        }  else if($i>90 && $i<100){
                            $font = 7;
                        }  else if($i>100 && $i<120){
                            $font = 6;
                        }  else if($i>120){
                            $font = 5;
                        } else{
                            $font = 11;
                        }
                        return $font;

                 }
    private function FormatAddress($request){

        $address   = [];
        $address[] = $request->address;

        if($request->allay!=''){
          $address[] =  "หมู่ที่ " . $request->allay;
        }

        if($request->village_no!='' && $request->village_no !='-'  && $request->village_no !='--'){
          $address[] = "ซอย"  . $request->village_no;
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
      /*  if($request->postcode!=''){
            $address[] =  "รหัสไปรษณีย์ " . $request->postcode;
        }*/
        return implode(' ', $address);
    }



    private function FormatAddressEn($request){
        $address   = [];
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
        // if($request->postcode!='' && $request->postcode !='-'  && $request->postcode !='--'){
        //     $address[] =   $request->postcode;
        // }
        return implode(' ', $address);
    }

    public function GetAddress($id,$address = null)
    {
        $certi_ib = CertiIb::findOrFail($id);
        $data = [];
        if($address == 2){ //ที่อยู่สาขา
            $data['address'] =           $certi_ib->address ?? null;
            $data['allay'] =             $certi_ib->allay ?? null;
            $data['village_no'] =        $certi_ib->village_no ?? null;
            $data['road'] =              $certi_ib->road ?? null;
            $data['province_name'] =     $certi_ib->basic_province->PROVINCE_NAME ?? null;
            $data['amphur_name'] =       $certi_ib->amphur_id ?? null;
            $data['district_name'] =     $certi_ib->district_id ?? null;
            $data['postcode'] =          $certi_ib->postcode ?? null;
        }else{ // ที่อยู่บริษัท
            $data['address'] =           $certi_ib->EsurvTrader->address_no ?? null;
            $data['allay'] =             $certi_ib->EsurvTrader->moo ?? null;
            $data['village_no'] =        $certi_ib->EsurvTrader->soi ?? null;
            $data['road'] =              $certi_ib->EsurvTrader->street ?? null;
            $data['province_name'] =     $certi_ib->EsurvTrader->province ?? null;
            $data['amphur_name'] =       $certi_ib->EsurvTrader->district ?? null;
            $data['district_name'] =     $certi_ib->EsurvTrader->subdistrict ?? null;
            $data['postcode'] =          $certi_ib->EsurvTrader->zipcode ?? null;
        }
        return response()->json([
            'data' => $data ?? '-',
        ]);
    }

    public function set_mail($export_ib,$certi_ib) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        if(!is_null($certi_ib->email)){
                $attachs = '';
                $attach_path  =  $this->attach_path;
                if(!empty($export_ib->certificate_path) && !empty($export_ib->certificate_newfile)){
                    $attachs =  $export_ib->certificate_path.'/' .$export_ib->certificate_newfile;
                    if(HP::checkFileStorage($attachs)){
                        HP::getFileStoragePath($attachs);
                    }
                }else if(!empty($export_ib->attachs)){
                    $attachs =  $attach_path.$export_ib->attachs;
                    if(HP::checkFileStorage($attachs)){
                           HP::getFileStoragePath($attachs);
                     }
             }

              $mail = new  IBExportMail([
                                       'email'      =>  auth()->user()->email ?? 'admin@admin.com',
                                       'export_ib'  => $export_ib,
                                       'certi_ib'   => $certi_ib,
                                       'attachs'    => !empty($attachs) ? $attachs : '',
                                       'url'        => $url.'certify/applicant-ib' 
                                    ]);

            Mail::to($certi_ib->email)->send($mail);
        }
      }

      public function running()
      {
          if(date('m') >= 10){
              $date = date('y')+44;
          }else{
              $date = date('y')+43;
          }
          $running =  CertiIBExport::get()->count();
          $running_no =  str_pad(($running + 1), 4, '0', STR_PAD_LEFT);
          return (date('y') + 43).'L:IB'.$running_no;
      }

          // ไฟล์แนบท้าย
    public function addAttach(Request $request)
    {
        try {
            $certi_ib = CertiIb::where('id', $request->app_certi_ib_id)->first();
            if (!is_null($certi_ib)) {

                // ประวัติการแนบไฟล์ แนบท้าย
                if ($request->attach  &&   $request->attach_pdf) {

                    CertiIBFileAll::where('app_certi_ib_id', $request->app_certi_ib_id)->update(['state' => 0]);
                    $certIbs = CertiIBFileAll::create([
                        'app_certi_ib_id'      => $request->app_certi_ib_id,
                        'attach'                => ($request->attach && $request->hasFile('attach')) ? $this->storeFile($request->attach, $certi_ib->app_no) : null,
                        'attach_client_name'    => ($request->attach && $request->hasFile('attach')) ? HP::ConvertCertifyFileName($request->attach->getClientOriginalName()) : null,
                        'attach_pdf'            => ($request->attach_pdf && $request->hasFile('attach_pdf')) ? $this->storeFile($request->attach_pdf, $certi_ib->app_no) : null,
                        'attach_pdf_client_name' => ($request->attach_pdf && $request->hasFile('attach_pdf')) ? HP::ConvertCertifyFileName($request->attach_pdf->getClientOriginalName()) : null,
                        'start_date'      =>   HP::convertDate($request->start_date, true) ?? null,
                        'end_date'      =>   HP::convertDate($request->end_date, true) ?? null,
                        'state' => 1
                    ]);
                    // แนบท้าย ที่ใช้งาน 
                    $certi_ib->update([
                        'attach'                 => $certIbs->attach ?? @$certi_ib->attach,
                        'attach_pdf'             => $certIbs->attach_pdf ?? @$certi_ib->attach_pdf,
                        'attach_pdf_client_name' => $certIbs->attach_pdf_client_name ?? @$certi_ib->attach_pdf_client_name
                    ]);
                } else {

                    if ($request->state) {
                        CertiIBFileAll::where('app_certi_ib_id', $request->app_certi_ib_id)->update(['state' => 0]);
                        $certIbs = CertiIBFileAll::findOrFail($request->state);
                        $certIbs->update(['state' => 1]);
                        // แนบท้าย ที่ใช้งาน
                        $certi_ib->update([
                            'attach'                 => $certIbs->attach ?? @$certi_ib->attach,
                            'attach_pdf'             => $certIbs->attach_pdf ?? @$certi_ib->attach_pdf,
                            'attach_pdf_client_name' => $certIbs->attach_pdf_client_name ?? @$certi_ib->attach_pdf_client_name
                        ]);
                    }
                }

                if (!is_null($request->id)) {
                    return redirect('certify/certificate-export-ib/' . $request->id . '/edit')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                } else {
                    return redirect('certify/certificate-export-ib')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                }
            }
            return redirect('certify/certificate-export-ib')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect('certify/certificate-export-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }
    }

    public function signPosition($id) {
        $signer =  Signer::where('id',$id)->first();
        if(!is_null($signer)){
                return response()->json([
                    'sign_position'=> !empty($signer->position) ? $signer->position : ' ' ,
                 ]);
        }
   
    }

    public function delete_file($id)
    {
        $Cost = CertiIBFileAll::findOrFail($id);
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
            if (!is_null($Cost)) {
                // $filePath =  $public.'/' .$Cost->file;
                // if( File::exists($filePath)){
                //     File::delete($filePath);
                    $Cost->delete();
                    $file = 'true';
                // }else{
                //     $file = 'false';
                // }
            }else{
                $file = 'false';
            }
          return  $file;
    }


    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = str_slug('certificateexportib', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $id = $request->input('certiib_file_id');
            $state = $request->input('state');
 
            $result = CertiIBFileAll::findOrFail($id);
                        CertiIBFileAll::where('app_certi_ib_id', $result->app_certi_ib_id)->update(['state' => 0]);
            $result->state = 1;          
            $result->save();
            if ($result) {
                return 'success';
            } else {
                return "not success";
            }
        } else {
            return response(view('403'), 403);
        }
    }

    public function update_document(Request $request)
    {
        
        $requestData = $request->all();
        // dd($requestData);
        $pathfile = 'files/Tempfile/'.($requestData['modal_app_no']);
        $obj = new stdClass;

        if( $request->hasFile('file_word') ){
            $file_word = $request->file('file_word');
            $file_extension = $file_word->getClientOriginalExtension();
            $storageName = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;
            $storagePath = Storage::putFileAs( $pathfile, $file_word,  str_replace(" ","",$storageName) );
            $obj->file_word =  HP::getFileStorage($storagePath);
            $obj->file_word_odl =  $file_word->getClientOriginalName();
            $obj->file_word_path = $storagePath;
        }

        if( $request->hasFile('file_pdf') ){
            $file_pdf = $request->file('file_pdf');
            $file_extension_pdf = $file_pdf->getClientOriginalExtension();
            $storageNamePdf = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension_pdf ;
            $storagePathPdf = Storage::putFileAs( $pathfile, $file_pdf,  str_replace(" ","",$storageNamePdf) );
            $obj->file_pdf = HP::getFileStorage($storagePathPdf);
            $obj->file_pdf_odl =  $file_pdf->getClientOriginalName();
            $obj->file_pdf_path = $storagePathPdf;
        }

        return response()->json( $obj );

    }

    public function deleteAttach($id)
    {
        $data = CertiIBFileAll::where('id', $id)->first();
        if( !is_null($data) ){

            $attach = $data->attach; 
            if( !empty($attach) && HP::checkFileStorage( $attach ) ){
                Storage::delete( $attach );
            }
            
            $attach_pdf = $data->attach_pdf; 
            if( !empty($attach_pdf) && HP::checkFileStorage( $attach_pdf ) ){
                Storage::delete( $attach_pdf );
            }

            $data->delete();
        }

        echo 'success';
    }

    public function delete_file_certificate($id)
    {
      try {
            $export_ib = CertiIBExport::findOrFail($id);
            if(!is_null($export_ib)){
                $attach_path  =  $this->attach_path;
                if(!empty($export_ib->certificate_path) && !empty($export_ib->certificate_newfile)){
                     $attachs =  $export_ib->certificate_path.'/' .$export_ib->certificate_newfile;
                      if(HP::checkFileStorage($attachs)){
                        Storage::delete("/".$attachs);
                      }
                      $export_ib->certificate_path = null;
                      $export_ib->certificate_file = null;
                      $export_ib->certificate_newfile = null;
                      $export_ib->save();
                 }else if(!empty($export_ib->attachs)){
                        $attachs =  $attach_path.$export_ib->attachs;
                        if(HP::checkFileStorage($attachs)){
                               HP::getFileStoragePath($attachs);
                         }
                         $export_ib->attachs = null;
                         $export_ib->attach_client_name = null;
                         $export_ib->save();
                    }
                 }
           return redirect()->back()->with('flash_message', 'ลบไฟล์เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('message_error', 'เกิดข้อผิดพลาดกรุณาลบใหม่');
        }
    }


}
