<?php

namespace App\Http\Controllers\Certify;

use HP;
use PDF;
use File;
use QrCode;
use App\User;
use Response;
use stdClass;
use Exception;
use Carbon\Carbon;
use App\Http\Requests;
use App\CertificateExport;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;

use App\Models\Bcertify\Formula;
use Illuminate\Support\Facades\DB;
use App\Services\CreateLabScopePdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Certify\Applicant\Report;
use App\Mail\Lab\CertifyCertificateExport;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CheckExaminer;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Certify\Applicant\AssessmentExaminer;
use App\Models\Certify\Applicant\CertiLabExportMapreq;

class CertificateExportLABController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        // dd('ok');
        $model = str_slug('certificateexportlab','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['perPage'] = $request->get('perPage', '');

            $table_name = (new CertificateExport)->getTable();

            $Query = new CertificateExport;
            $Query = $Query->select('certificate_exports.*') ;

            if ($filter['filter_status']!='') {
                $Query = $Query->where("{$table_name}.status", $filter['filter_status']);
            }else{
                $Query = $Query->where("{$table_name}.status", '!=', '99');
            }

            if ($filter['filter_search'] != '') {
                $CertiIb  = CertiLab::where(function($query) use($filter){
                                            $query->where('app_no', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('org_name', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('tax_id', 'like', '%'.$filter['filter_search'].'%');

                                        })
                                        ->select('id');



                $Query = $Query->where(function($query) use($filter, $CertiIb ){
                                    $query->where('request_number', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('certificate_no', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('lab_name', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('lab_name_en', 'like', '%'.$filter['filter_search'].'%')
                                            ->OrwhereIn('certificate_for', $CertiIb);
                                });

            }

            if ($filter['filter_start_date'] != '' && $filter['filter_end_date'] != '') {
                $date_start = HP::convertDate($filter['filter_start_date'],true);
                $date_end = HP::convertDate($filter['filter_end_date'],true);

                $Query = $Query->whereDate('certificate_date_start','>=', $date_start )->whereDate('certificate_date_end','<=', $date_end );
            }else if($filter['filter_start_date'] != '' && $filter['filter_end_date'] == ''){
                $date_start = HP::convertDate($filter['filter_start_date'],true);
                $Query = $Query->whereDate('certificate_date_start', $date_start );
            }else if($filter['filter_start_date'] == '' && $filter['filter_end_date'] != ''){
                $date_end = HP::convertDate($filter['filter_end_date'],true);
                $Query = $Query->whereDate('certificate_date_end','<=', $date_end );
            }

            $examiner = AssessmentExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); //เจ้าหน้าที่ รับผิดชอบ  สก.
            $User =   User::where('runrecno',auth()->user()->runrecno)->first();
            $select_users = array();
            if($User->IsGetIdRoles() == 'false'){  //ไม่ใช่ admin , ผอ , ลท

                if(!is_null($examiner) && count($examiner) > 0 && !in_array('22',auth()->user()->RoleListId)){
                     $Query = $Query->LeftJoin('app_certi_lab_assessments_examiner','app_certi_lab_assessments_examiner.app_certi_lab_id','=','certificate_exports.certificate_for')
                                    ->where('app_certi_lab_assessments_examiner.user_id',auth()->user()->runrecno);  //เจ้าหน้าที่ที่ได้มอบหมาย
                }else{
                    if(isset($User) && !is_null($User->reg_subdepart) && (in_array('11',$User->BasicRoleUser) || in_array('22',$User->BasicRoleUser))  ) {  //ผู้อำนวยการกอง ของ สก.
                              $Query = $Query->LeftJoin('app_certi_labs','app_certi_labs.id','=','certificate_exports.certificate_for')
                                            ->where('app_certi_labs.subgroup',$User->reg_subdepart);  //เจ้าหน้าที่ที่ได้มอบหมาย

                    }else{
                        $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                    }
                }


            }else{

            }


            $certificates = $Query->orderby('id','desc')->paginate($filter['perPage']);

            return view('certify.certificate_export_lab.index', compact('certificates', 'filter'));
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
        // dd('ok');
        $model = str_slug('certificateexportlab','-');
        if(auth()->user()->can('add-'.$model)) {

            $app_token = $request->get('app_token');

            $app_no = [];
            if( !empty( $app_token  ) ){
                // $requests = CertiLab::where('token', $app_token )
                //                     ->select('id','lab_type','trader_id','app_no','name')
                //                     ->orderby('id','desc')
                //                     ->get();

                // foreach ($requests as $request){
                //     $app_no[$request->id] = $request->name . " ( $request->app_no )";
                // }
                 $requests =   CertiLab::where('token', $app_token)->first();
                 $app_no[$requests->id] = $requests->name . " ( $requests->app_no )";
            }else{

                //เจ้าหน้าที่ LAB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
                if(in_array("28",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                    $check = CheckExaminer::where('user_id',auth()->user()->runrecno)->select('app_certi_lab_id'); // เช็คเจ้าหน้าที่ IB
                    if(count($check->get()) > 0 ){
                        $requests = CertiLab::whereNotIn('status',[0,4,5])
                                            ->whereIn('id',$check)
                                            // ->whereIn('status',[17,18])
                                            ->whereIn('status',[25,26])
                                            ->select('id','lab_type','trader_id','app_no','name')
                                            ->orderby('id','desc')
                                            ->get();

                        if(count($requests) > 0){
                            foreach ($requests as $request){
                                $app_no[$request->id] = $request->name . " ( $request->app_no )";
                            }
                        }
                    }
                }else{
                    $requests = CertiLab::whereNotIn('status',[0,4,5])
                                        // ->whereIn('status',[17,18])
                                        ->whereIn('status',[25,26])
                                        ->select('id','lab_type','trader_id','app_no','name')
                                        ->orderby('id','desc')
                                        ->get();

                    //dd($requests);
                    if(count($requests) > 0){
                        foreach ($requests as $request){
                            $app_no[$request->id] = @$request->name . " ($request->app_no)";
                        }
                    }

                }
            }

            return view('certify.certificate_export_lab.create',['app_no'=> $app_no, 'app_token' => $app_token,'attach_path'=> $this->attach_path]);
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

    function generateCode($num,$year) {

        $yearSuffix =  (int) substr($year, -2); // ตัดเลขท้าย 2 หลักของปี
        $yearSuffixPlusOne  = $yearSuffix + 1; 
        $formattedNum = str_pad($num, 4, '0', STR_PAD_LEFT); // เติม 0 ข้างหน้า $num ให้ครบ 4 ตัว
        return "{$yearSuffixPlusOne }-LB{$formattedNum}"; // รวมรหัสที่ต้องการ
    }

    public function store(Request $request)
    {
        // $certi_lab = CertiLab::findOrFail($request->app_certi_lab_id);
        // $export_lab = CertificateExport::where('request_number', $certi_lab->app_no)->first();
        // dd($export_lab);
        // dd($request->all());

        $model = str_slug('certificateexportlab','-');
        if(auth()->user()->can('add-'.$model)) {
            try {
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();
                $certi_lab = CertiLab::findOrFail($request->app_certi_lab_id);

                $request->validate([
                    'app_certi_lab_id' => 'required',
                ]);
                
                if($request->submit == "submit"){
                    
                    $requestData = $request->all();
                    $requestData['created_by'] =   auth()->user()->runrecno;
                    $certi_lab = CertiLab::findOrFail($request->app_certi_lab_id);

                    if(in_array($request->status, ['0','1','2'])){
                        if(!empty($certi_lab) && $certi_lab->status <= 26){
                            $certi_lab->status  =  26 ;  // ออกใบรับรอง และ ลงนาม
                            $certi_lab->save();
                        }

                     }else  if($request->status == 3){
                            $certi_lab->status  =  27 ;  // ลงนามเรียบร้อย
                            $certi_lab->save();
                     }else  if($request->status == 4){
                            $certi_lab->status  =  28 ;  // จัดส่งใบรับรองระบบงาน
                            $certi_lab->save();
                     }


                    $requestData['request_number']      =  $certi_lab->app_no ?? null;
                    $requestData['certificate_for']     =  $certi_lab->id ?? null;
                    $requestData['lab_type']            =  $certi_lab->lab_type ?? null;
                    $requestData['org_name']            =  $certi_lab->name ?? null;
                    $requestData['contact_name']        =  $certi_lab->contactor_name ?? null;
                    $requestData['contact_mobile']      =  $certi_lab->telephone ?? null;
                    $requestData['contact_tel']         =  $certi_lab->contact_tel ?? null;
                    $requestData['contact_email']       =  $certi_lab->email ?? null;
                    // $requestData['certificate_no']      =  $request->certificate ?? null;

                    $requestData['certificate_date_start'] = (is_null($request->certificate_date_start) ||  empty($request->certificate_date_start) )?NULL: (!empty($request->certificate_date_start)?HP::convertDate($request->certificate_date_start,true):null);
                    $requestData['certificate_date_end'] = (is_null($request->certificate_date_end) || empty($request->certificate_date_end) )? NULL: (!empty($request->certificate_date_end)?HP::convertDate($request->certificate_date_end,true):null) ;

                    $config                              = HP::getConfig();
                    $requestData['cer_type']             =  !empty($config->check_electronic_certificate)  && $config->check_electronic_certificate == 1 ? '2' :'1';

                    //Upload File
                    if($request->hasFile('certificate_file')) {
                        $files = $request->file('certificate_file');
                        $requestData['attachs_client_name'] = $files->getClientOriginalName();
                        $requestData['attachs']     =  $this->storeFile($request->certificate_file, $certi_lab->app_no) ;
                    }

                    $requestData['sign_instead'] = isset($request->sign_instead)? '1':'0';

                    $export_lab = CertificateExport::where('request_number', $certi_lab->app_no)->first();
                    
                    if( !is_null( $export_lab) ){
                        $export_lab->update($requestData);
                    }else{
                        
                        $fisCal = $this->getCurrentFiscalYearData();
                        $num = $fisCal['count'] + 1;
                        $year = $fisCal['fiscal_year'];
                      
                        $cerNo = $this->generateCode($num,$year);
                        $requestData['certificate_no'] = $cerNo;
                        
                        if(!$request->has('status') && !empty($request->app_certi_lab_id)){ // บันทึกเฉพาะแท็บไฟล์แนบ
                            $certi_lab = $this->apiGetAddress($request->app_certi_lab_id)->getData()->certi_lab;
                            // ที่อยู่
                           
                            $requestData['address_no'] = @$certi_lab->address_no;
                            $requestData['address_no_en'] = @$certi_lab->lab_address_no_eng;
                            $requestData['address_moo'] = @$certi_lab->allay;
                            $requestData['address_moo_en'] = @$certi_lab->lab_moo_eng;
                            $requestData['address_soi'] = @$certi_lab->village_no;
                            $requestData['address_soi_en'] = @$certi_lab->lab_soi_eng;
                            $requestData['address_road'] = @$certi_lab->road;
                            $requestData['address_road_en'] = @$certi_lab->lab_street_eng;
                            $requestData['address_province'] = @$certi_lab->province_name;
                            $requestData['address_province_en'] = @$certi_lab->province_name_en;
                            $requestData['address_district'] = @$certi_lab->amphur_name;
                            $requestData['address_district_en'] = @$certi_lab->lab_amphur_eng;
                            $requestData['address_subdistrict'] = @$certi_lab->district_name;
                            $requestData['address_subdistrict_en'] = @$certi_lab->lab_district_eng;
                            $requestData['address_postcode'] = @$certi_lab->postcode;
                            // มาตรฐาน
                            $requestData['formula'] = @$certi_lab->formula;
                            $requestData['formula_en'] = @$certi_lab->formula_en;
                            // หมายเลขการรับรองที่
                            $requestData['accereditatio_no'] = @$certi_lab->accereditatio_no;
                            // ออกให้ ณ วันที่
                            $requestData['certificate_date_start'] = @$certi_lab->date_start_ce;

                        }
                        // dd($requestData);
                        $export_lab = CertificateExport::create($requestData);
                    }

                    // dd($$requestData);

                    if( isset($requestData['detail']) ){

                        $list_detail = $requestData['detail'];

                        $new_path_file = $this->attach_path.$requestData['app_no'];
                        CertLabsFileAll::where('app_certi_lab_id', $export_lab->certificate_for)->update(['state' => 0]);
                        foreach( $list_detail AS $item ){

                            // if( isset($item['file_word']) || isset($item['file_pdf']) ){

                               if(isset($item['id'])){
                                    $obj =     CertLabsFileAll::findOrFail($item['id']);
                                 if(is_null($obj)){
                                    $obj = new CertLabsFileAll;
                                 }
                                }else{
                                    $obj = new CertLabsFileAll;
                                }

                                    $obj->app_no            = $export_lab->request_number;
                                    $obj->app_certi_lab_id  =  $export_lab->certificate_for;
                                    $obj->ref_id            = $export_lab->id;
                                    $obj->ref_table         =  (new CertificateExport)->getTable();
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

                                    $obj->start_date    =  !empty($item['start_date']) ? HP::convertDate($item['start_date'],true) : null;
                                    $obj->end_date      =  !empty($item['end_date']) ? HP::convertDate($item['end_date'],true) : null;
                                    $obj->state         = isset($item['state'])?1:null;
                                    $obj->save();
                            // }

                        }

                    }

                    $pathfileTemp = 'files/Tempfile/'.($requestData['app_no']);

                    if(Storage::directories($pathfileTemp)){
                        Storage::deleteDirectory($pathfileTemp);
                    }

                    $this->save_certilab_export_mapreq($certi_lab->id,$export_lab->id);


                    $pdfService = new CreateLabScopePdf($certi_lab);
                    $pdfContent = $pdfService->generatePdf();
            
                    $json = $this->copyScopeLabFromAttachement($certi_lab);
                    $copiedScopes = json_decode($json, true);
            
                    Report::where('app_certi_lab_id',$certi_lab->id)->update([
                        'file_loa' =>  $copiedScopes[0]['attachs'],
                        'file_loa_client_name' =>  $copiedScopes[0]['file_client_name']
                    ]);

                    //เคลียร์ state ไฟล์
                    $exportMapreqs = $certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many;
                    if($exportMapreqs->count() !=0 )
                    {
                        $certiLabIds = $exportMapreqs->pluck('app_certi_lab_id')->toArray();
                        CertLabsFileAll::whereIn('app_certi_lab_id',$certiLabIds)
                        ->whereNotNull('attach_pdf')
                        ->update([
                            'state' => 0
                        ]);
                    }
            
                    CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)
                        ->orderBy('id', 'desc') // เรียงตาม id ล่าสุด
                        ->first()->update([
                            'attach_pdf' => $copiedScopes[0]['attachs'],
                            'attach_pdf_client_name' => $copiedScopes[0]['file_client_name'],
                            'state' => 1
                        ]);

                    if($export_lab->status == 4){
                        //E-mail
                        $this->set_mail($export_lab,$certi_lab);
                    }
                    return redirect('certify/certificate-export-lab')->with('flash_message', 'เรียบร้อยแล้ว');
                }else{
                    // dd('ok');
                    return  $this->ExportLAB($request,$request->app_certi_lab_id);
                }

                return redirect('certify/certificate-export-lab')->with('flash_message', 'เรียบร้อยแล้ว');

            } catch (\Exception $e) {
                echo $e->getMessage();
                exit;
                return redirect('certify/certificate-export-lab')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
            }

        }
        abort(403);
    }

    public function copyScopeLabFromAttachement($app)
    {
        $copiedScoped = null;
        $fileSection = null;

        if($app->lab_type == 3){
           $fileSection = "61";
        }else if($app->lab_type == 4){
           $fileSection = "62";
        }

        $latestRecord = CertiLabAttachAll::where('app_certi_lab_id', $app->id)
        ->where('file_section', $fileSection)
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();

        $existingFilePath = 'files/applicants/check_files/' . $latestRecord->file ;

        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files/'.$no.'/';

            if (file_exists($localFilePath)) {
                $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                $filePath = $attach_path . $dlName;
                if (Storage::disk('ftp')->exists($filePath)) {
                    $list  = new  stdClass;
                    $list->attachs =  $no.'/'.$dlName;
                    $list->file_client_name =  $dlName;
                    $scope[] = $list;
                    $copiedScoped = json_encode($scope);
                } 
                unlink($localFilePath);
            }
        }

        return $copiedScoped;
    }

    public function edit($id)
    {
        // dd($id);
        $model = str_slug('certificateexportlab','-');
        if(auth()->user()->can('edit-'.$model)) {
            $export_lab = CertificateExport::findOrFail($id);
            // dd($export_lab);
            $app_no = $export_lab->request_number ?? null;

            $export_lab->app_no = $app_no ?? null;
            $export_lab->app_certi_lab_id = $export_lab->certificate_for ?? null;
            $export_lab->certificate =  $export_lab->certificate_no ?? null;

            $export_lab->purpose_type = !empty($export_lab->CertiLabTo->purpose_type)?$export_lab->CertiLabTo->purpose_type:null;


			if(!empty($export_lab->org_name)){
				$certi_lab = CertiLab::where('id',$export_lab->app_certi_lab_id)->first();
				$export_lab->title =  @$certi_lab->name;
			}else{
				$export_lab->title =@$export_lab->org_name;
			}

            $export_lab->certificate_date_start = (is_null($export_lab->certificate_date_start) ||  empty($export_lab->certificate_date_start) )?'': HP::revertDate($export_lab->certificate_date_start,true);
            $export_lab->certificate_date_end = (is_null($export_lab->certificate_date_end) || empty($export_lab->certificate_date_end) )? '': HP::revertDate($export_lab->certificate_date_end,true) ;


              // ขอบข่าย
                //  $cert_labs_file_all  = !empty($export_lab->CertiLabTo->cert_labs_file_all)?$export_lab->CertiLabTo->cert_labs_file_all:[];
                // $export_nos        =  CertificateExport::where('certificate_no',$export_lab->certificate_no)->pluck('certificate_for');
                // if(count($export_nos) > 0){

                //     $lab_ids = [];
                //     foreach ($export_nos as $item) {
                //         if(!in_array($item,$lab_ids)){
                //             $lab_ids[] =  $item;
                //         }
                //     }

                //     if(!empty($export_lab->CertiLabTo->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many) &&
                //         $export_lab->CertiLabTo->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0){
                //         foreach ($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) {
                //             if(!in_array($item,$lab_ids)){
                //                 $lab_ids[] =  $item;
                //             }
                //         }
                //     }

                //     $file_alls =  CertLabsFileAll::whereIn('app_certi_lab_id',$lab_ids)->get();
                //     if(count($file_alls) > 0){
                //         $cert_labs_file_all =  $file_alls;
                //     }

                // }
                $cert_labs_file_all =  !empty($export_lab->CertiLabFileAll) ? $export_lab->CertiLabFileAll : [];

                $exportMapreqs = $certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many;
                if($exportMapreqs->count() !=0)
                {
                    $certiLabIds = $exportMapreqs->pluck('app_certi_lab_id')->toArray();
                    $cert_labs_file_all = CertLabsFileAll::whereIn('app_certi_lab_id',$certiLabIds)
                    ->whereNotNull('attach_pdf')
                    ->get();
                }
                // $files = CertLabsFileAll::
           
                // dd($export_lab->status);

            $attach_path  = $this->attach_path;
            return view('certify.certificate_export_lab.edit', compact('export_lab','cert_labs_file_all','attach_path'));
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {
        // dd(CertiLab::findOrFail($request->app_certi_lab_id)->status,$request->all(),$request->certificate_date_start,$request->certificate_date_end);
        $model = str_slug('certificateexportlab','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->validate([
                'app_certi_lab_id' => 'required',
            ]);

            // try {
                $export_lab = CertificateExport::findOrFail(base64_decode($id));

                $request->request->add(['updated_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();

                if($request->submit == "submit"){
                       $certi_lab = CertiLab::findOrFail($request->app_certi_lab_id);

                    if(in_array($request->status, ['0','1','2'])){
                        if( !empty($certi_lab) && $certi_lab->status <= 26){
                            $certi_lab->status  =  26 ;  // ออกใบรับรอง และ ลงนาม
                            $certi_lab->save();
                        }

                        $requestData['request_number'] =  $certi_lab->app_no ?? null;
                        $requestData['certificate_for'] =  $certi_lab->id ?? null;
                        $requestData['lab_type'] =  $certi_lab->lab_type ?? null;
                        // $requestData['certificate_no'] =  $request->certificate ?? null;
                        $requestData['certificate_date_start'] =  !empty($request->certificate_date_start)?HP::convertDate($request->certificate_date_start,true):null;
                        $requestData['certificate_date_end'] =  !empty($request->certificate_date_end)?HP::convertDate($request->certificate_date_end,true):null;
                        $requestData['sign_instead'] = isset($request->sign_instead)? '1':'0';

                     }else  if($request->status == 3){
                        $certi_lab->status  =  27 ;  // ลงนามเรียบร้อย
                        $certi_lab->save();
                     }else  if($request->status == 4){
                       
                            // $certi_lab->status  =  21 ;  //  เปิดใช้งานใบใบรับรองระบบงาน
                            $certi_lab->status  =  28 ;  // จัดส่งใบรับรองระบบงาน
                            $certi_lab->save();

                     }
                     
                    //Upload File
                    if($request->hasFile('certificate_file')) {
                        $files = $request->file('certificate_file');
                        $requestData['attachs_client_name'] = $files->getClientOriginalName();
                        $requestData['attachs']     =  $this->storeFile($request->certificate_file, $certi_lab->app_no) ;
                    }
                    // dd(CertiLab::findOrFail($request->app_certi_lab_id)->status);
                    $export_lab->update($requestData);

                    
                    if( isset($requestData['detail']) ){
                        
                        $list_detail = $requestData['detail'];
                        $new_path_file = $this->attach_path.$requestData['app_no'];
                        CertLabsFileAll::where('app_certi_lab_id', $export_lab->certificate_for)->update(['state' => 0]);
                        foreach( $list_detail AS $item ){
                            // if( isset($item['file_word']) || isset($item['file_pdf']) ){
                               if(isset($item['id'])){
                                    $obj =     CertLabsFileAll::findOrFail($item['id']);
                                 if(is_null($obj)){
                                    $obj = new CertLabsFileAll;
                                 }
                                }else{
                                    $obj = new CertLabsFileAll;
                                }

                                    $obj->app_no            = $export_lab->request_number;
                                    $obj->app_certi_lab_id  =  $export_lab->certificate_for;
                                    $obj->ref_id            = $export_lab->id;
                                    $obj->ref_table         =  (new CertificateExport)->getTable();
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

                                    $obj->start_date    =  !empty($item['start_date']) ? HP::convertDate($item['start_date'],true) : null;
                                    $obj->end_date      =  !empty($item['end_date']) ? HP::convertDate($item['end_date'],true) : null;
                                    $obj->state         = isset($item['state'])?1:null;
                                    $obj->save();
                            // }

                        }
                    }
                    
                    if( isset($requestData['delete_flie']) ){
                        
                        $list_delete_flie  = $requestData['delete_flie'];
                        foreach($list_delete_flie as $item){
                            $obj =     CertLabsFileAll::findOrFail($item);
                            if(!is_null($obj)){
                                $obj->status_cancel  = 1;
                                $obj->created_cancel =  auth()->user()->getKey();
                                $obj->date_cancel    =  date('Y-m-d H:i:s');
                                $obj->save();
                            }
                        }
                    }

                    $this->save_certilab_export_mapreq($certi_lab->id,$export_lab->id);

                    // $pdfService = new CreateLabScopePdf($certi_lab);
                    // $pdfContent = $pdfService->generatePdf();
            
                    // $json = $this->copyScopeLabFromAttachement($certi_lab);
                    // $copiedScopes = json_decode($json, true);
            
                    // Report::where('app_certi_lab_id',$certi_lab->id)->update([
                    //     'file_loa' =>  $copiedScopes[0]['attachs'],
                    //     'file_loa_client_name' =>  $copiedScopes[0]['file_client_name']
                    // ]);
            
                    // CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)
                    //     ->orderBy('id', 'desc') // เรียงตาม id ล่าสุด
                    //     ->first()->update([
                    //         'attach_pdf' => $copiedScopes[0]['attachs'],
                    //         'attach_pdf_client_name' => $copiedScopes[0]['file_client_name']
                    //     ]);
                 
                    if($export_lab->status == 4){
                        
                        //E-mail
                        $this->set_mail($export_lab,$certi_lab);
                    }

                    return redirect('certify/certificate-export-lab')->with('flash_message', 'เรียบร้อยแล้ว');
                }else{
                    return  $this->ExportLAB($request,$request->app_certi_lab_id);
                }

                return redirect('certify/certificate-export-lab')->with('flash_message', 'แก้ไข CertificateExportLAB เรียบร้อยแล้ว!');

            // } catch (\Exception $e) {
            //     return redirect('certify/certificate-export-lab')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
            // }

        }
        abort(403);

    }

    private function save_certilab_export_mapreq($app_certi_lab_id, $certificate_exports_id)
    {
        $mapreq =  CertiLabExportMapreq::where('app_certi_lab_id',$app_certi_lab_id)->where('certificate_exports_id', $certificate_exports_id)->first();
        if(Is_null($mapreq)){
            $mapreq = new  CertiLabExportMapreq;
        }
        $mapreq->app_certi_lab_id       = $app_certi_lab_id;
        $mapreq->certificate_exports_id = $certificate_exports_id;
        $mapreq->save();
    }

    public function set_mail($export_ib,$certi_lab) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
        $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';
        $attachs = '';
        if(!is_null($certi_lab->email)){
           
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
               
              $mail = new  CertifyCertificateExport([

                                                    'export_ib' => $export_ib,
                                                    'certi_lab'=> $certi_lab,
                                                    'attachs'=> !empty($attachs) ? $attachs : '',
                                                    'url' => $url.'certify/applicant' ,
                                                    'email'=>  !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                                    'email_cc'=>  !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  $EMail,
                                                    'email_reply' => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                                                     ]);
                                                    


              try {
                Mail::to($certi_lab->email)->send($mail);
            } catch (Exception $e) {
                // จัดการกับข้อผิดพลาดที่เกิดขึ้น
                // dd($e->getMessage());
                // คุณอาจต้องการบันทึกข้อผิดพลาดใน log หรือจัดการอย่างอื่นตามต้องการ
            }

      }
    }

    public function storeFile($files, $app_no = 'files_lab',$name =null)
    {
        $no  = str_replace("RQ-","",$app_no);
        $no  = str_replace("-","_",$no);
        if ($files) {
            $file_extension = $files->getClientOriginalExtension();
            $attach_path  =  $this->attach_path.$no;
            $file_extension = $files->getClientOriginalExtension();
            $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName =   str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

            $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $no.'/'.$storageName;
        }else{
            return null;
        }
    }

    public function ExportLAB($request,$certi_id = null)
    {
        // dd('ok');
        if(!is_null($certi_id)){
            $certi_lab = CertiLab::findOrFail($certi_id);
            $no = '17025';
            $formula = Formula::where('title', 'like', '%'.$no.'%')
                                    ->whereState(1)->first();
         //ข้อมูลภาพ QR Code
          if(!is_null($certi_lab)  && !is_null($certi_lab->attach_pdf) && $certi_lab->attach_pdf != '' ){
            // $url       =    url("certify/check/files/".$certi_lab->attach_pdf );
            
                $url       =       url('/certify/check_files_lab/'. rtrim(strtr(base64_encode($certi_lab->id), '+/', '-_'), '=') );
                // $url       =       url('/certify/check/file_client/'. rtrim(strtr(base64_encode($certi_lab->id), '+/', '-_'), '=') );
               //ข้อมูลภาพ QR Code
            //    $string = mb_convert_encoding($url, 'ISO-8859-1', 'UTF-8');
              $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                         ->size(500)->errorCorrection('H')
                         ->generate($url);
           }

			//$trader_tb = Trader::where('trader_autonumber',$certi_lab->trader_id)->first();
			$trd_org_name = !empty($certi_lab->trader->trader_operater_name)?$certi_lab->trader->trader_operater_name:null;

			if(is_null($request->id)){ //เมื่อปริ้น แต่ยังไม่บันทึก

				$certi_lab_name= $trd_org_name ; //ดึงชื่อจาก ข้อมูลองค์กร

			}else{
				$cer_exp_lab = CertificateExport::where('id', $request->id)->first();

				if(!empty($cer_exp_lab->org_name)){
					$certi_lab_name = $cer_exp_lab->name;
				}else{
					$certi_lab_name = @$cer_exp_lab->org_name;
				}
			}


            $req_date_start = (is_null($request->certificate_date_start) ||  empty($request->certificate_date_start) )? NULL: (!empty($request->certificate_date_start)?HP::convertDate($request->certificate_date_start,true):null);
            $req_date_end = (is_null($request->certificate_date_end) || empty($request->certificate_date_end) ) ? NULL: (!empty($request->certificate_date_end)?HP::convertDate($request->certificate_date_end,true):null) ;

           $data_export = [
                        'app_no'             => $request->app_no,
                        // 'name'               => $certi_lab_name ?? null,
                        // 'name_en'            =>  isset($request->title_en) ?   $request->title_en  : '&emsp;',
                        'name'              => isset($request->lab_name) ?   $request->lab_name  : '&emsp;',
                        'name_en'            =>  isset($request->lab_name_en) ?    '('.$request->lab_name_en.')' : '&emsp;',
                        'lab_name_font_size' => $this->CalFontSize($request->lab_name),
                        'certificate'        => $request->certificate,
                        'lab_name'           =>  $request->lab_name ?? null,
                        'address'            => $this->FormatAddress($request),
                        'address_en'         => $this->FormatAddressEn($request),
                        'lab_name_font_size_address' => $this->CalFontSize($this->FormatAddress($request)),
                        'formula'            =>  isset($request->formula) ?   $request->formula : '&emsp;',
                        'formula_en'         =>  isset($request->formula_en) ?   $request->formula_en : '',
                        'accereditatio_no'   => $request->accereditatio_no,
                        'accereditatio_no_en'=>  isset($request->accereditatio_no_en) ?   $request->accereditatio_no_en : '',
                        'date_start'         => $req_date_start,
                        'date_end'           => $req_date_end,
                        'date_start_en'      => !empty($req_date_start) ? HP::formatDateENertify($req_date_start) : '' ,
                        'date_end_en'        => !empty($request->certificate_date_end) ? HP::formatDateENFull($request->certificate_date_end) : null ,
                        'image_qr'           => isset($image_qr) ? $image_qr : null,
                        'url'                => isset($url) ? $url : null,
                        'attach_pdf'         => isset($certi_lab->attach_pdf) ? $certi_lab->attach_pdf : null,
                        'laboratory'         => $certi_lab->LabTypeTitle ?? null,
                        'condition_th'       => !empty($formula->condition_th ) ? $formula->condition_th  : null ,
                        'condition_en'       => !empty($formula->condition_en ) ? $formula->condition_en  : null,
                        'lab_name_font_size_condition' => !empty($formula->condition_th) ? $this->CalFontSizeCondition($formula->condition_th)  : '11'
                       ];

         $pdf = PDF::loadView('certify/certificate_export_lab/pdf/certificate-thai', $data_export);
           return $pdf->stream("scope-thai.pdf");
        //  $files =   $certi_lab->EsurvTrader->trader_id.'_LAB_'.$certi_lab->app_no;  // ชื่อไฟล์

        //  $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
        //  $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        //       //delete old pic if exists
        //     if (File::exists($public . $path)) {
        //         File::delete($public . $path);
        //      }
        //  Storage::put($path, $pdf->output());
        //  return redirect('certify/certificate-export/FilePdf/'.$files);
        //  return  $pdf->download($request->certificate.'-scope-thai.pdf');
        }
        abort(403);
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
                }  else{
                    $font = 11;
                }

                return $font;

         }



            private function FormatAddress($request){

                $address   = [];
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
                // if($request->address_postcode!=''){
                //     $address[] =  "รหัสไปรษณีย์ " . $request->address_postcode;
                // }
                return implode(' ', $address);
            }

            private function FormatAddressEn($request){
                $address   = [];

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
                // if($request->address_postcode!='' && $request->address_postcode !='-'  && $request->address_postcode !='--'){
                //     $address[] =   $request->address_postcode;
                // }
                return implode(' ', $address);
            }



                    public function apiGetDate($date)
                    {
                        $data_date =  HP::DatePlus($date,5,'year');
                        $date_end = HP::revertDate($data_date,true);

                        return response()->json([
                            'date' => $date_end ?? '-',
                        ]);
                    }

                    public function GetAddress($id,$address = null)
                    {
                        $certi_lab = CertiLab::findOrFail($id);
                        $data = [];
                        if($address == 2){ //ที่อยู่สาขา
                            $data['address_no'] =        $certi_lab->address_no ?? null;
                            $data['allay'] =             $certi_lab->allay ?? null;
                            $data['village_no'] =        $certi_lab->village_no ?? null;
                            $data['road'] =              $certi_lab->road ?? null;
                            $data['province_name'] =     $certi_lab->basic_province->PROVINCE_NAME ?? null;
                            $data['amphur'] =            $certi_lab->amphur ?? null;
                            $data['district'] =          $certi_lab->district ?? null;
                            $data['postcode'] =          $certi_lab->postcode ?? null;
                        }else{ // ที่อยู่บริษัท
                            $data['address_no'] =        $certi_lab->EsurvTrader->address_no ?? null;
                            $data['allay'] =             $certi_lab->EsurvTrader->moo ?? null;
                            $data['village_no'] =        $certi_lab->EsurvTrader->soi ?? null;
                            $data['road'] =              $certi_lab->EsurvTrader->street ?? null;
                            $data['province_name'] =     $certi_lab->EsurvTrader->province ?? null;
                            $data['amphur'] =            $certi_lab->EsurvTrader->district ?? null;
                            $data['district'] =          $certi_lab->EsurvTrader->subdistrict ?? null;
                            $data['postcode'] =          $certi_lab->EsurvTrader->zipcode ?? null;
                        }
                        return response()->json([
                            'data' => $data ?? '-',
                        ]);
                    }

                    public function running()
                    {
                        if(date('m') >= 10){
                            $date = date('y')+44;
                        }else{
                            $date = date('y')+43;
                        }
                        $running =  CertificateExport::get()->count();
                        $running_no =  str_pad(($running + 1), 4, '0', STR_PAD_LEFT);
                        return (date('y') + 43).'L:LAB'.$running_no;
                    }



    // ไฟล์แนบท้าย
    public function addAttach(Request $request)
    {
        try {
            $certi_lab = CertiLab::where('id', $request->app_certi_lab_id)->first();
            if (!is_null($certi_lab)) {

                // ประวัติการแนบไฟล์ แนบท้าย
                // if ($request->attach  &&   $request->attach_pdf) {

                //     CertLabsFileAll::where('app_certi_lab_id', $request->app_certi_lab_id)->update(['state' => 0]);
                //     $certLabs = CertLabsFileAll::create([
                //         'app_certi_lab_id'      => $request->app_certi_lab_id,
                //         'attach'                => ($request->attach && $request->hasFile('attach')) ? $this->storeFile($request->attach, $certi_lab->app_no) : null,
                //         'attach_client_name'    => ($request->attach && $request->hasFile('attach')) ? HP::ConvertCertifyFileName($request->attach->getClientOriginalName()) : null,
                //         'attach_pdf'            => ($request->attach_pdf && $request->hasFile('attach_pdf')) ? $this->storeFile($request->attach_pdf, $certi_lab->app_no) : null,
                //         'attach_pdf_client_name' => ($request->attach_pdf && $request->hasFile('attach_pdf')) ? HP::ConvertCertifyFileName($request->attach_pdf->getClientOriginalName()) : null,
                //         'start_date'      =>   HP::convertDate($request->start_date, true) ?? null,
                //         'end_date'      =>   HP::convertDate($request->end_date, true) ?? null,
                //         'state' => 1
                //     ]);
                //     // แนบท้าย ที่ใช้งาน
                //     $certi_lab->update([
                //         'attach'                 => $certLabs->attach ?? @$certi_lab->attach,
                //         'attach_pdf'             => $certLabs->attach_pdf ?? @$certi_lab->attach_pdf,
                //         'attach_pdf_client_name' => $certLabs->attach_pdf_client_name ?? @$certi_lab->attach_pdf_client_name
                //     ]);
                // } else {

                //     if ($request->state) {
                //         CertLabsFileAll::where('app_certi_lab_id', $request->app_certi_lab_id)->update(['state' => 0]);
                //         $certLabs = CertLabsFileAll::findOrFail($request->state);
                //         $certLabs->update(['state' => 1]);
                //         // แนบท้าย ที่ใช้งาน
                //         $certi_lab->update([
                //             'attach'                 => $certLabs->attach ?? @$certi_lab->attach,
                //             'attach_pdf'             => $certLabs->attach_pdf ?? @$certi_lab->attach_pdf,
                //             'attach_pdf_client_name' => $certLabs->attach_pdf_client_name ?? @$certi_lab->attach_pdf_client_name
                //         ]);
                //     }
                // }
                $requestData = $request->all();

                $obj = new CertLabsFileAll;
                $obj->app_certi_lab_id = $request->app_certi_lab_id;

                $obj->start_date = !empty($request->start_date)?HP::convertDate($request->start_date, true):null;
                $obj->end_date =  !empty($request->end_date)?HP::convertDate($request->end_date, true):null;
                $obj->state = 1;

                $check = false;
                if( $request->hasFile('attach')  ){
                    $check = true;
                    $attach = $request->file('attach');
                    $obj->attach = $this->storeFile( $attach , $certi_lab->app_no);
                    $obj->attach_client_name  = $attach->getClientOriginalName();
                }

                if( $request->hasFile('attach_pdf')  ){
                    $check = true;
                    $attach_pdf = $request->file('attach_pdf');
                    $obj->attach_pdf = $this->storeFile( $attach_pdf , $certi_lab->app_no);
                    $obj->attach_pdf_client_name  = $attach_pdf->getClientOriginalName();
                }

                if( $check == true){
                    $obj->save();
                }


                if (!is_null($request->id)) {
                    return redirect('certify/certificate-export-lab/' . $request->id . '/edit')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                } else {
                    return redirect('certify/certificate-export-lab')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                }
            }
            return redirect('certify/certificate-export-lab')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect('certify/certificate-export-lab')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }
    }

    public function signPosition($id) {
        $signer =  Signer::where('id',$id)->first();
        if(!is_null($signer)){
            return response()->json([
                    'sign_position'=> !empty($signer->position) ? $signer->position : ' ' ,
                    'sign_name'=> !empty($signer->title) ? $signer->title : ' '
                ]);
        }

    }

    public function delete_file($id)
    {
        $Cost = CertLabsFileAll::findOrFail($id);
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

    public function delete_file_certificate($id)
    {
        try {
            $certificate_export = CertificateExport::findOrFail($id);
            if(!empty($certificate_export->certificate_path) && !empty($certificate_export->certificate_newfile)){
                $filePath = $certificate_export->certificate_path.'/'.$certificate_export->certificate_newfile;
                if(HP::checkFileStorage($filePath)){
                    HP::deleteFileStorage($filePath);
                    $certificate_export->certificate_path = null;
                    $certificate_export->certificate_file = null;
                    $certificate_export->certificate_newfile = null;
                    $certificate_export->save();
                    return redirect()->back()->with('flash_message', 'ลบไฟล์เรียบร้อยแล้ว');
                }else{
                    $certificate_export->certificate_path = null;
                    $certificate_export->certificate_file = null;
                    $certificate_export->certificate_newfile = null;
                    $certificate_export->save();
                    return redirect()->back()->with('message_error', 'ลบไฟล์เรียบร้อยแล้ว');
                }
            }else if(!empty($certificate_export->attachs)){
                $filePath = 'files/applicants/check_files/'.$certificate_export->attachs;
                if(HP::checkFileStorage($filePath)){
                    HP::deleteFileStorage($filePath);
                    $certificate_export->attachs = null;
                    $certificate_export->attachs_client_name = null;
                    $certificate_export->save();
                    return redirect()->back()->with('flash_message', 'ลบไฟล์เรียบร้อยแล้ว');
                }else{
                    $certificate_export->attachs = null;
                    $certificate_export->attachs_client_name = null;
                    $certificate_export->save();
                    return redirect()->back()->with('message_error', 'ลบไฟล์เรียบร้อยแล้ว');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('message_error', 'เกิดข้อผิดพลาดกรุณาลบใหม่');
        }
    }

    public function update_status(Request $request)
    {
        $model = str_slug('certificateexportlab', '-');
        if (auth()->user()->can('edit-' . $model)) {
            $files = $request->switches;
            foreach($files as $file)
            {
                CertLabsFileAll::find($file['certilab_file_id'])->update([
                    'state' => $file['state']
                ]);
            }
            return 'success';
        }else{
            return response(view('403'), 403);
        }
    }

    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status_old_by_rama(Request $request)
    {
        // dd($request->all());
        $model = str_slug('certificateexportlab', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $id = $request->input('certilab_file_id');
            $state = $request->input('state');

            $result = CertLabsFileAll::findOrFail($id);

            $certi_lab = CertiLab::where('id',$result->app_certi_lab_id)->first();

            if(!empty($certi_lab->certi_lab_export_mapreq_to))
            {
                $lab_ids = [];
                if($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0)
                {
                    foreach ($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) 
                    {
                        if(!in_array($item,$lab_ids))
                        {
                            $lab_ids[] =  $item;
                        }
                    }
                }

                $certificate_no =  !empty($certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no) ? $certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no : null;
                
                if(!is_null($certificate_no))
                {
                        $export_no         =  CertificateExport::where('certificate_no',$certificate_no);
                        if(count($export_no->get()) > 0){
                          if($export_no->pluck('certificate_for')->count() > 0)
                          {
                              foreach ($export_no->pluck('certificate_for') as $item) 
                              {
                                  if(!in_array($item,$lab_ids))
                                  {
                                     $lab_ids[] =  $item;
                                  }
                              }
                          }
                   }
                }
                // ขอบข่าย
                CertLabsFileAll::whereIn('app_certi_lab_id',$lab_ids)->update(['state' => '0']);

            }else{
                CertLabsFileAll::where('app_certi_lab_id', $result->app_certi_lab_id)->update(['state' => '0']);
            }

            $result->state = '1';
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


    function getCurrentFiscalYearData()
    {
        // คำนวณช่วงปีงบประมาณปัจจุบัน
        $currentDate = now();
        $currentYear = $currentDate->month >= 10 ? $currentDate->year : $currentDate->year - 1;
    
        $startOfFiscalYear = Carbon::createFromDate($currentYear, 10, 1)->startOfDay();
        $endOfFiscalYear = Carbon::createFromDate($currentYear + 1, 9, 30)->endOfDay();
    
        // นับจำนวนรายการในปีงบประมาณปัจจุบัน
        $count = CertificateExport::whereBetween('created_at', [$startOfFiscalYear, $endOfFiscalYear])->count();
    
        // ดึง request_no ทั้งหมดในปีงบประมาณปัจจุบัน
        $requestNumbers = CertificateExport::whereBetween('created_at', [$startOfFiscalYear, $endOfFiscalYear])
            ->pluck('request_number');
    
        // คืนค่าข้อมูลปีงบประมาณปัจจุบัน
        return [
            'fiscal_year' => $currentYear,
            'count' => $count,
            'request_numbers' => $requestNumbers,
        ];
    }
    

    public function apiGetAddress($id)
    {
    //    dd($id);
        $certi_lab = CertiLab::findOrFail($id);
        if(!is_null($certi_lab)){
            $last   = CertificateExport::where('lab_type',$certi_lab->lab_type)->whereYear('created_at',Carbon::now())->count() + 1;
            $all   = CertificateExport::count() + 1;

            // $runningNo = $this->running() ?? null;
            // $report = Report::where('app_certi_lab_id',$certi_lab->id)->first();
            // // dd($report,$certi_lab);
            // if($report !== null){
            //     if($report->certificate_no !== null){
            //         $runningNo = $report->certificate_no;
            //     }
            // }

            
            $certi_lab->certificate          =  $this->running() ?? null;
            // $certi_lab->certificate          =  $runningNo;
            $certi_lab->trader_operater_name =  $certi_lab->name ?? null ;
            $certi_lab->province_name        =  $certi_lab->basic_province->PROVINCE_NAME ?? null;
            $certi_lab->province_name_en     =  $certi_lab->basic_province->PROVINCE_NAME_EN ?? null;
            $certi_lab->amphur_name          =  $certi_lab->amphur ?? null;
            $certi_lab->district_name        =  $certi_lab->district ?? null;

            // if(!empty($certi_lab->lab_name_en) &&  mb_substr($certi_lab->lab_name_en,0,1) !== '('  &&  mb_substr($certi_lab->lab_name_en,-1) !== ')'){
            //    $certi_lab->lab_name_en          =  !empty($certi_lab->lab_name_en)  ? '('.$certi_lab->lab_name_en.')' : null;
            // }
          
            $no = '17025';
            $formula = Formula::where('title', 'like', '%'.$no.'%')
                                    ->whereState(1)->first();
            $certi_lab->formula =  !is_null($formula) ? $formula->title   : null;
            $certi_lab->formula_en =   !is_null($formula)  ? $formula->title_en   : null;

            $lab_type = ['1'=>'Testing','2'=>'LAB','3'=>'IB','4'=>'CB'];
            
            $accereditatio_no = '';
            if(array_key_exists("2",$lab_type)){
                $accereditatio_no .=  $lab_type[2].'-';
            }
            if(!is_null($certi_lab->app_no)){
                $app_no = explode('-', $certi_lab->app_no);
                $accereditatio_no .= $app_no[2].'-';
            }
            if(!is_null($last)){
                $accereditatio_no .=  str_pad($last, 3, '0', STR_PAD_LEFT).'-'.(date('Y') +543);
            }
            // dd($accereditatio_no);
            // $certi_lab->accereditatio_no =   $accereditatio_no ? $accereditatio_no : null;
            // $certi_lab->accereditatio_no =   $accereditatio_no ? $this->convertToThaiNumbers($accereditatio_no) : null;
            // $certi_lab->accereditatio_no_en =   $accereditatio_no ? $accereditatio_no : null;

            if($certi_lab->lab_type == 3)
            {
                $certi_lab->accereditatio_no =   'ทดสอบ ' .  $this->convertToThaiNumbers(str_pad($all, 4, '0', STR_PAD_LEFT));
                $certi_lab->accereditatio_no_en =   'Testing '.  str_pad($all, 4, '0', STR_PAD_LEFT);
            }else if($certi_lab->lab_type == 4)
            {
                $certi_lab->accereditatio_no =   'สอบเทียบ ' .  $this->convertToThaiNumbers(str_pad($all, 4, '0', STR_PAD_LEFT));
                $certi_lab->accereditatio_no_en =   'Calibration '.  str_pad($all, 4, '0', STR_PAD_LEFT);
            }
          
            $certi_lab->date_start =  HP::revertDate(date('Y-m-d'),true);
            $certi_lab->date_start_ce =  $certi_lab->date_start;
            $date_end =  HP::DatePlus(date('Y-m-d'),3,'year');
            $certi_lab->date_end = HP::revertDate($date_end,true);
        }
        return response()->json([
            'certi_lab'      => $certi_lab ?? '-',
        ]);
    }

    function convertToThaiNumbers($input)
    {
        // ตัวเลขอารบิกและตัวเลขไทย
        $arabicNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $thaiNumbers = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];

        // แทนที่ตัวเลขอารบิกด้วยตัวเลขไทย
        return str_replace($arabicNumbers, $thaiNumbers, $input);
    }


    public function update_document(Request $request)
    {

        $requestData = $request->all();

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

}
