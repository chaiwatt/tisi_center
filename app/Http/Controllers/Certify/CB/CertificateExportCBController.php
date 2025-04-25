<?php

namespace App\Http\Controllers\Certify\CB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use HP;
use Illuminate\Support\Facades\DB;
use QrCode;
use File;
use HP_API_PID;
use Carbon\Carbon;
use App\User;
use App\Models\Bcertify\Formula;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantCB\CertiCBCheck;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use App\Models\Certify\ApplicantCB\CertiCbExportMapreq;
use App\CertificateExport;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\CB\CBExportMail;

use App\Models\Besurv\Signer;
use stdClass;
use Illuminate\Support\Facades\Storage;

class CertificateExportCBController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_cb/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['perPage'] = $request->get('perPage', 10);


            $Query = new CertiCBExport;
            $Query = $Query->select('app_certi_cb_export.*');

            if ($filter['filter_search'] != '') {
                $CertiIb  = CertiCb::where(function($query) use($filter){
                                            $query->where('app_no', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('cb_name', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('tax_id', 'like', '%'.$filter['filter_search'].'%')
                                                    ->orwhere('name_short_standard', 'like', '%'.$filter['filter_search'].'%');

                                        })
                                        ->select('id');

                $Query = $Query->where(function($query) use($filter, $CertiIb ){
                                    $query->where('app_no', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('certificate', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('name_standard', 'like', '%'.$filter['filter_search'].'%')
                                            ->orwhere('name_standard_en', 'like', '%'.$filter['filter_search'].'%')
                                            ->OrwhereIn('app_certi_cb_id', $CertiIb);
                                });

            }

            if ($filter['filter_status']!='') {
                $Query = $Query->where('status', $filter['filter_status']);
            }else{
                $Query = $Query->where('status', '!=', '99');
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

            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_cb_check','app_certi_cb_check.app_certi_cb_id','=','app_certi_cb_export.app_certi_cb_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }

            $export_cb = $Query->orderby('id','desc')
                                        // ->sortable()
                                        ->paginate($filter['perPage']);

            return view('certify/cb.certificate_export_cb.index', compact('export_cb', 'filter'));
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
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('add-'.$model)) {

            $app_token = $request->get('app_token');
            $app_no = [];
            if( !empty($app_token) ){
                    //  $app_no = CertiCb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                    //                         ->where('token', $app_token )
                    //                         ->orderby('id','desc')
                    //                         ->pluck('title', 'id');
                 $requests =   CertiCb::where('token', $app_token)->first();
                 $app_no[$requests->id] = $requests->name . " ( $requests->app_no )";
            }else{
                //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
                if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                    $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->select('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                    if(count($check->get()) > 0 ){
                        $app_no= CertiCb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                                        ->whereNotIn('status',[0,4,5])
                                        ->whereIn('id',$check)
                                        ->whereIn('status',[17,18])
                                        ->orderby('id','desc')
                                        ->pluck('title', 'id');
                    }
                }else{
                    $app_no = CertiCb::select(DB::raw("CONCAT(name,' ',app_no) AS title"),'id')
                                        ->whereNotIn('status',[0,4,5])
                                        ->whereIn('status',[17,18])
                                        ->orderby('id','desc')
                                        ->pluck('title', 'id');
                }
            }
            $attach_path       = $this->attach_path;
            return view('certify/cb.certificate_export_cb.create', compact('app_no', 'app_token','attach_path') );
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
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->validate([
                'app_certi_cb_id' => 'required',
            ]);

            $requestData = $request->all();

            try {
                if($request->submit == "submit"){

                    $requestData['created_by'] =   auth()->user()->runrecno;
                    $certi_cb = CertiCb::findOrFail($request->app_certi_cb_id);
                    $certi_cb->update(['cb_name'=>$request->cb_name]);

                    $config = HP::getConfig();

                    if(in_array($request->status, ['0','1','2'])){
                        if(!is_null($certi_cb) && $certi_cb->status <= 18){
                            $certi_cb->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                            $certi_cb->save();
                        }

                     }else  if($request->status == 3){ 
                        $certi_cb->status  =  19;  // ลงนามเรียบร้อย
                        $certi_cb->save();
                     }else  if($request->status == 4){ 
                            $certi_cb->status  =  20;  // จัดส่งใบรับรองระบบงาน
                            $certi_cb->save();
                     }

                    $requestData['cb_name']         =  $request->cb_name;
                    $requestData['check_badge']     =  isset($request->check_badge) ? $request->check_badge : null;
                    $requestData['type_standard']   =  $certi_cb->type_standard ?? null;
                    $requestData['contact_name']    =  $certi_cb->contactor_name ?? null;
                    $requestData['contact_mobile']  =  $certi_cb->telephone ?? null;
                    $requestData['contact_tel']     =  $certi_cb->contact_tel ?? null;
                    $requestData['contact_email']   =  $certi_cb->email ?? null;
                    $requestData['date_start']      =  !empty($request->date_start) ?   HP::convertDate($request->date_start,true) : null;
                    $requestData['date_end']        =  !empty($request->date_end) ?  HP::convertDate($request->date_end,true) : null;
                    $requestData['cer_type']        =  (!empty($config->check_electronic_certificate) && $config->check_electronic_certificate == 1)?2:1;

                   //Upload File
                    if($request->hasFile('attachs')) {
                        $files = $request->file('attachs');
                        $requestData['attach_client_name'] = $files->getClientOriginalName();
                        $requestData['attachs']     =  $this->storeFile($request->attachs, $certi_cb->app_no) ;
                    }

                    $export_cb = CertiCBExport::where('app_certi_cb_id',$certi_cb->id )->first();
                    if( is_null( $export_cb) ){
                        $export_cb = CertiCBExport::create($requestData);
                    }else{
                        $export_cb->update($requestData);
                    }
                    // $certi_cb->update(['status'=> 18]); // ออกใบรับรอง และ ลงนาม


                    if( isset($requestData['detail']) ){

                        $list_detail = $requestData['detail'];

                        $new_path_file = $this->attach_path.$export_cb->app_no;
                                     CertiCBFileAll::where('app_certi_cb_id', $export_cb->app_certi_cb_id)->update(['state' => 0]);
                        foreach( $list_detail AS $item ){

                                if(isset($item['id'])){
                                    $obj =     CertiCBFileAll::findOrFail($item['id']);
                                    if(is_null($obj)){
                                     $obj = new CertiCBFileAll;
                                    }
                                }else{
                                   $obj = new CertiCBFileAll;
                                }
                                    $obj->app_no            = $export_cb->app_no;
                                    $obj->app_certi_cb_id   =  $export_cb->app_certi_cb_id;
                                    $obj->ref_id            = $export_cb->id;
                                    $obj->ref_table         =  (new CertiCBExport)->getTable();
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

                    $pathfileTemp = 'files/Tempfile/'.($export_cb->app_no);

                    if(Storage::directories($pathfileTemp)){
                        Storage::deleteDirectory($pathfileTemp);
                    }
                    $this->save_certicb_export_mapreq($certi_cb->id,$export_cb->id);


                    if($export_cb->status == 4){
                        //E-mail
                        $this->set_mail($export_cb,$certi_cb);
                    }
                    return redirect('certify/certificate-export-cb')->with('flash_message', 'เพิ่ม เรียบร้อยแล้ว');
                }else{

                    return  $this->ExportCB($request,$request->app_certi_cb_id);
                }

            } catch (\Exception $e) {
                return redirect('certify/certificate-export-cb')->with('flash_message', 'เกิดข้อผิดพลาดในการบันทึก');
            }

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
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('edit-'.$model)) {
            $export_cb = CertiCBExport::findOrFail($id);

            $app_no = CertiCb::select(DB::raw("CONCAT(name,' ( ',app_no,' )') AS title"),'id')->pluck('title', 'id')->toArray();
            if(array_key_exists((int)$export_cb->app_certi_cb_id,$app_no)){
                $export_cb->title =  $app_no[$export_cb->app_certi_cb_id] ?? null;
            }

            $cb_name = CertiCb::select(DB::raw("name AS cb_name"),'id')->pluck('cb_name', 'id')->toArray();
            if(array_key_exists((int)$export_cb->app_certi_cb_id,$cb_name)){
                $export_cb->cb_name =  $cb_name[$export_cb->app_certi_cb_id] ?? null;
            }

            $export_cb->date_start =  !empty($export_cb->date_start) ?   HP::revertDate($export_cb->date_start,true) : null;
            $export_cb->date_end =  !empty($export_cb->date_end) ?  HP::revertDate($export_cb->date_end,true) : null;

            // $certicb_file_all  = !empty($export_cb->CertiCbTo->cert_cbs_file_all)?$export_cb->CertiCbTo->cert_cbs_file_all:[];
            $certicb_file_all  =  $export_cb->CertiCBFileAll;

            // dd($certicb_file_all);
            $attach_path       = $this->attach_path;
            return view('certify/cb.certificate_export_cb.edit', compact('export_cb','certicb_file_all','attach_path'));
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
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('edit-'.$model)) {
            try {
                $export_cb =  CertiCBExport::findOrFail(base64_decode($id));

                $requestData = $request->all();

                if($request->submit == "submit"){

                    $requestData['updated_by']      =   auth()->user()->runrecno;

                    $certi_cb = CertiCb::findOrFail($export_cb->app_certi_cb_id);
                    $certi_cb->update(['cb_name'=>$request->cb_name,'check_badge'=>$request->check_badge]);

                //   if($request->status <= 2){
                //     if($request->status == 2 && !is_null($certi_cb) && $certi_cb->status <= 18){
                //         $certi_cb->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                //         $certi_cb->save();
                //     }

                    if(in_array($request->status, ['0','1','2'])){
                        if(!is_null($certi_cb) && $certi_cb->status <= 18){
                            $certi_cb->status  =  18 ;  // ออกใบรับรอง และ ลงนาม
                            $certi_cb->save();
                        }

                    }else  if($request->status == 3){ 
                        $certi_cb->status  =  19;  // ลงนามเรียบร้อย
                        $certi_cb->save();
                     }else  if($request->status == 4){ 
                            $certi_cb->status  =  20;  // จัดส่งใบรับรองระบบงาน
                            $certi_cb->save();
                     }

                    $requestData['cb_name']             =  $request->cb_name;
                    $requestData['check_badge']         = isset($request->check_badge) ? $request->check_badge : null;
                    $requestData['date_start']          = !empty($request->date_start) ?   HP::convertDate($request->date_start,true) : null;
                    $requestData['date_end']            = !empty($request->date_end) ?   HP::convertDate($request->date_end,true) : null;

                  //Upload File
                    if($request->hasFile('attachs')) {
                        $files = $request->file('attachs');
                        $requestData['attach_client_name'] = $files->getClientOriginalName();
                        $requestData['attachs']     =  $this->storeFile($request->attachs, $certi_cb->app_no) ;
                    }



                    $export_cb->update($requestData);

                    if( isset($requestData['detail']) ){

                        $list_detail = $requestData['detail'];

                        $new_path_file = $this->attach_path.$export_cb->app_no;

                            // CertiCBFileAll::where('app_certi_cb_id', $export_cb->app_certi_cb_id)->update(['state' => 0]);
                            $app_certi_cb_id = CertiCbExportMapreq::where('certificate_exports_id', $export_cb->id)->pluck('app_certi_cb_id');
                            CertiCBFileAll::whereIn('app_certi_cb_id',$app_certi_cb_id)->update(['state' => 0]);

                        foreach( $list_detail AS $item ){

                                if(isset($item['id'])){
                                    $obj =     CertiCBFileAll::findOrFail($item['id']);
                                    if(is_null($obj)){
                                     $obj = new CertiCBFileAll;
                                    }
                               }else{
                                   $obj = new CertiCBFileAll;
                               }
                                    $obj->app_no            = $export_cb->app_no;
                                    $obj->app_certi_cb_id  =  $export_cb->app_certi_cb_id;
                                    $obj->ref_id            = $export_cb->id;
                                    $obj->ref_table         =  (new CertiCBExport)->getTable();
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
                            $obj =     CertiCBFileAll::findOrFail($item);
                            if(!is_null($obj)){
                                $obj->status_cancel  = 1;
                                $obj->created_cancel =  auth()->user()->getKey();
                                $obj->date_cancel    =  date('Y-m-d H:i:s');
                                $obj->save();
                            }
                        }
                    }

                    $this->save_certicb_export_mapreq($certi_cb->id,$export_cb->id);

                    if($export_cb->status == 4){
                    //E-mail
                        $this->set_mail($export_cb,$certi_cb);
                    }

                    return redirect('certify/certificate-export-cb')->with('flash_message', 'เรียบร้อยแล้ว');
                }else{

                    return $this->ExportCB($request,$export_cb->app_certi_cb_id);
                }

                return redirect('certify/certificate-export-cb')->with('flash_message', ' เรียบร้อยแล้ว!');
            } catch (\Exception $e) {

                echo $e->getMessage();
                exit;
                return redirect('certify/certificate-export-cb')->with('flash_message', 'เกิดข้อผิดพลาดในการบันทึก');
            }


        }
        abort(403);

    }

    private function save_certicb_export_mapreq($app_certi_cb_id, $certificate_exports_id)
    {
        $mapreq =  CertiCbExportMapreq::where('app_certi_cb_id',$app_certi_cb_id)->where('certificate_exports_id', $certificate_exports_id)->first();
        if(Is_null($mapreq)){
            $mapreq = new CertiCbExportMapreq;
        }
        $mapreq->app_certi_cb_id       = $app_certi_cb_id;
        $mapreq->certificate_exports_id = $certificate_exports_id;
        $mapreq->save();
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
        $model = str_slug('certificateexportcb','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new CertificateExportCB;
            CertificateExportCB::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            CertificateExportCB::destroy($id);
          }

          return redirect('certify/certificate-export-c-b')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

        // สำหรับเพิ่มรูปไปที่ store
        public function storeFile($files, $app_no = 'files_cb',$name =null)
        {
            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
            if ($files) {
                 $attach_path  =  $this->attach_path.$no;

                $file_extension = $files->getClientOriginalExtension();
                $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
                $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
                $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
                Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName));
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
        }


    public function ExportCB($request,$certi_cb = null)
    {
        if(!is_null($certi_cb)){
            $CertiCb = CertiCb::findOrFail($certi_cb);

            $file = CertiCBFileAll::where('state',1)
                                    ->where('app_certi_cb_id',$certi_cb)
                                    ->first();
            if($certi_cb == 21){
                $certi_cb = 7;
            }

            // return $certi_id;
             $formula = Formula::where('id', 'like', $CertiCb->type_standard)
                                    ->whereState(1)->first();

            if(!is_null($file) && !is_null($file->attach_pdf) ){
                //   $url  = urlencode( url("certify/check/files_ib/". basename($file->attach_pdf)));
                //   $attach = explode("/",$file->attach_pdf);
                //   $attach_pdf  =   utf8_decode($attach[1]);
                //  $url  =  url('certify/check/files_cb/'.$file->attach_pdf);
                 $url  =   url('/certify/check_files_cb/'. rtrim(strtr(base64_encode($certi_cb), '+/', '-_'), '=') );
                //ข้อมูลภาพ QR Code
                //  $string = mb_convert_encoding($url, 'UTF-8' , 'ISO-8859-1');
                 $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                              ->size(500)->errorCorrection('H')
                              ->generate($url);

            }


           $data_export = [
                        'app_no'             => $request->app_no,
                        'name'               => !empty($request->name_standard) ? $request->name_standard : null,
                        'name_en'            =>  isset($request->name_standard_en) ?  '('.$request->name_standard_en.')' : '&emsp;',
                        'lab_name_font_size' => $this->CalFontSize($request->name_standard),
                        'certificate'        => $request->certificate,
                        'name_unit'          => $request->name_unit,
                        'address'            => $this->FormatAddress($request),
                        'lab_name_font_size_address' => $this->CalFontSize($this->FormatAddress($request)),
                        'address_en'         => $this->FormatAddressEn($request),
                        'formula'            => $request->formula,
                        'formula_en'         =>  isset($request->formula_en) ?   $request->formula_en : '&emsp;',
                        'accereditatio_no'   => $request->accereditatio_no,
                        'accereditatio_no_en'   => $request->accereditatio_no_en,
                        'date_start'         =>  !empty($request->date_start)? HP::convertDate($request->date_start,true) : null,
                        'date_end'           => !empty($request->date_end)? HP::convertDate($request->date_end,true) : null,
                        'date_start_en'      => !empty($request->date_start) ? HP::formatDateENertify(HP::convertDate($request->date_start,true)) : null ,
                        'date_end_en'        => !empty($request->date_end) ? HP::formatDateENFull($request->date_end) : null ,
                        'formula_title'      => !empty($CertiCb->FormulaTo->title) ? 'หน่วยรับรอง '.$CertiCb->FormulaTo->title : null,
                        'name_standard'      => !empty($request->name_standard) ? $request->name_standard : null,
                        'check_badge'        => isset($request->check_badge) ? $request->check_badge : null,
                        'image_qr'           => isset($image_qr) ? $image_qr : null,
                        'url'                => isset($url) ? $url : null,
                        'attach_pdf'         => isset($file->attach_pdf) ? $file->attach_pdf : null ,
                        'condition_th'       => !empty($formula->condition_th ) ? $formula->condition_th  : null ,
                        'condition_en'       => !empty($formula->condition_en ) ? $formula->condition_en  : null ,
                        'imagery'            =>  !empty($CertiCb->CertiCBFormulasTo->imagery) ?  $CertiCb->CertiCBFormulasTo->imagery : '-',
                        'image'              =>  !empty($CertiCb->CertiCBFormulasTo->image) ?  $CertiCb->CertiCBFormulasTo->image : '-',
                        'lab_name_font_size_condition' => !empty($formula->condition_th) ? $this->CalFontSizeCondition($formula->condition_th)  : '11',
                        'branch_th'          =>  !empty($CertiCb->CertificationBranchTo->title) ?  $CertiCb->CertificationBranchTo->title : '',
                        'branch_en'          =>  !empty($CertiCb->CertificationBranchTo->title_en) ?  '('.$CertiCb->CertificationBranchTo->title_en.')' : '',
                        'type_standard'      =>  $formula->id ?? null
                       ];

         $pdf = PDF::loadView('certify/cb/certificate_export_cb/pdf/certificate-thai', $data_export);
         return $pdf->stream("scope-thai.pdf");
         $files =   $CertiCb->EsurvTrader->trader_id.'_CB_'.$CertiCb->app_no;  // ชื่อไฟล์

        //  $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
        //  $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        //       //delete old pic if exists
        //     if (File::exists($public . $path)) {
        //         File::delete($public . $path);
        //      }
        //  Storage::put($path, $pdf->output());
        //  return redirect('certify/certificate-export/FilePdf/'.$files);
        //  return $pdf->stream($request->certificate.'-scope-thai.pdf');
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
     public function CalFontSize($certificate_for){
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
                // }  else if($i>80 && $i<90){
                //     $font = 8;
                // }  else if($i>90 && $i<100){
                //     $font = 7;
                // }  else if($i>100 && $i<120){
                //     $font = 6;
                // }  else if($i>120 && $i<130){
                //     $font = 5;
                // }  else if($i>130){
                //     $font = 4;
                // }   else{
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
                }  else if($i>120 && $i<130){
                    $font = 5;
                }  else if($i>130){
                    $font = 4;
                }  else{
                    $font = 11;
                }
                return $font;

         }


    private function FormatAddress($request){

        $address   = '';
        $address .= $request->address;

        if($request->allay!=''){
          $address .=  " หมู่ที่ " . $request->allay;
        }

        if($request->village_no!='' && $request->village_no !='-'  && $request->village_no !='--'){
          $address .=  " ซอย "  . $request->village_no;
        }

        if($request->road!='' && $request->road !='-'  && $request->road !='--'){
          $address .=  " ถนน".$request->road;
        }

        if($request->district_name!=''){
            if($request->province_name=='กรุงเทพมหานคร'){
                $address .= " แขวง".$request->district_name;
            }else{
                $address .= " ตำบล".$request->district_name;

            }
        }

        if($request->amphur_name!=''){
            if($request->province_name=='กรุงเทพมหานคร'){
                $address .= " เขต".$request->amphur_name;
            }else{
                $address .= " อำเภอ".$request->amphur_name;
            }
        }

        if($request->province_name!=''){
            if($request->province_name=='กรุงเทพมหานคร'){
                $address .=  " ".$request->province_name;
            }else{
                $address .=  " จังหวัด".$request->province_name;
            }
        }

        // if($request->postcode!=''){
        //     $address[] =  "รหัสไปรษณีย์ " . $request->postcode;
        // }
        return $address;
        // return implode(' ', $address);
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

    public function apiGetAddress($id){
        $certi_cb = CertiCb::findOrFail($id);
        if(!is_null($certi_cb)){
            $last   = CertiCBExport::where('type_standard',$certi_cb->type_standard)->whereYear('created_at',Carbon::now())->count() + 1;
            $all   = CertiCBExport::count() + 1;
            $formula = Formula::where('id', 'like', $certi_cb->type_standard)->whereState(1)->first();
            $certi_cb->certificate      =  $this->running() ?? null;
            $certi_cb->province_name    =  $certi_cb->basic_province->PROVINCE_NAME ?? null;
            $certi_cb->province_name_en    =  $certi_cb->basic_province->PROVINCE_NAME_EN ?? null;

            $certi_cb->amphur_name      =  $certi_cb->amphur_id ?? null;
            $certi_cb->district_name    =  $certi_cb->district_id ?? null;
            $certi_cb->trader_operater_name    =  $certi_cb->EsurvTraderTitle ?? null;
            // $certi_cb->amphur_name   =  $certi_cb->basic_amphur->AMPHUR_NAME ?? null;
            // $certi_cb->district_name =  $certi_cb->basic_district->DISTRICT_NAME ?? null;
            $certi_cb->formula          =  !is_null($formula) ? $formula->title   : null;
            $certi_cb->formula_en       =   !is_null($formula)  ? $formula->title_en   : null;

            $lab_type = ['1'=>'Testing','2'=>'Cal','3'=>'IB','4'=>'CB'];
            $accereditatio_no = '';
            if(array_key_exists("4",$lab_type)){
                $accereditatio_no .=  $lab_type[4].'-';
            }
            if(!is_null($certi_cb->app_no)){
                $app_no = explode('-', $certi_cb->app_no);
                $accereditatio_no .= $app_no[2].'-';
            }
            if(!is_null($last)){
                $accereditatio_no .=  str_pad($last, 3, '0', STR_PAD_LEFT).'-'.(date('Y') +543);
            }
            $certi_cb->accereditatio_no  =   $accereditatio_no ? $accereditatio_no : null;
            $certi_cb->date_start =  HP::revertDate(date('Y-m-d'),true);
            $date_end =  HP::DatePlus(date('Y-m-d'),3,'year');
            $certi_cb->date_end = HP::revertDate($date_end,true);
        }
        return response()->json([
            'certi_cb'      => $certi_cb ?? '-',
         ]);
    }

    public function apiGetDate($date)
    {
        $data_date =  HP::DatePlus($date,6,'year');
        $date_end = HP::revertDate($data_date,true);

        return response()->json([
            'date' => $date_end ?? '-',
        ]);
    }

    public function GetAddress($id,$address = null)
    {
        $certi_cb = CertiCb::findOrFail($id);
        $data = [];
        if($address == 2){ //ที่อยู่สาขา
            $data['address']        =    $certi_cb->address ?? null;
            $data['allay']          =    $certi_cb->allay ?? null;
            $data['village_no']     =    $certi_cb->village_no ?? null;
            $data['road']           =    $certi_cb->road ?? null;
            $data['province_name']  =    $certi_cb->basic_province->PROVINCE_NAME ?? null;
            $data['amphur_name']    =    $certi_cb->amphur_id ?? null;
            $data['district_name']  =    $certi_cb->district_id ?? null;
            $data['postcode']       =    $certi_cb->postcode ?? null;
        }else{ // ที่อยู่บริษัท
            $data['address']        =    $certi_cb->EsurvTrader->address_no ?? null;
            $data['allay']          =    $certi_cb->EsurvTrader->moo ?? null;
            $data['village_no']     =    $certi_cb->EsurvTrader->soi ?? null;
            $data['road']           =    $certi_cb->EsurvTrader->street ?? null;
            $data['province_name']  =    $certi_cb->EsurvTrader->province ?? null;
            $data['amphur_name']    =    $certi_cb->EsurvTrader->district ?? null;
            $data['district_name']  =    $certi_cb->EsurvTrader->subdistrict ?? null;
            $data['postcode']       =    $certi_cb->EsurvTrader->zipcode ?? null;
        }
        return response()->json([
            'data' => $data ?? '-',
        ]);
    }


    public function set_mail($export_cb,$certi_cb) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        if(!is_null($certi_cb->email)){
            $attachs = '';
            $attach_path  =  $this->attach_path;
            if(!empty($export_cb->certificate_path) && !empty($export_cb->certificate_newfile)){
                $attachs =  $export_cb->certificate_path.'/' .$export_cb->certificate_newfile;
                  if(HP::checkFileStorage($attachs)){
                      HP::getFileStoragePath($attachs);
                  }
             }else if(!empty($export_cb->attachs)){
                    $attachs =  $attach_path.$export_cb->attachs;

                    if(HP::checkFileStorage($attachs)){
                           HP::getFileStoragePath($attachs);
                     }
             }

              $mail = new  CBExportMail([
                                       'email'                  =>  auth()->user()->email ?? 'admin@admin.com',
                                       'export_cb'              => $export_cb,
                                       'certi_cb'               => $certi_cb,
                                       'attachs'                => !empty($attachs) ? $attachs : '',
                                       'url'                    => $url.'certify/applicant-cb',
                                     ]);
             Mail::to($certi_cb->email)->send($mail);
        }
      }

      public function running()
      {
          if(date('m') >= 10){
              $date = date('y')+44;
          }else{
              $date = date('y')+43;
          }
          $running =  CertiCBExport::get()->count();
          $running_no =  str_pad(($running + 1), 4, '0', STR_PAD_LEFT);
          return (date('y') + 43).'L:CB'.$running_no;
      }

    // ไฟล์แนบท้าย
    public function addAttach(Request $request)
    {
        try {
            $certi_cb = CertiCb::where('id', $request->app_certi_cb_id)->first();
            if (!is_null($certi_cb)) {

                $requestData = $request->all();

                CertiCBFileAll::where('app_certi_cb_id', $request->app_certi_b_id)->update(['state' => 0]);

                $obj = new CertiCBFileAll;
                $obj->app_certi_cb_id = $request->app_certi_cb_id;

                $obj->start_date = !empty($request->start_date)?HP::convertDate($request->start_date, true):null;
                $obj->end_date =  !empty($request->end_date)?HP::convertDate($request->end_date, true):null;
                $obj->state = 1;

                $check = false;
                if( $request->hasFile('attach')  ){
                    $check = true;
                    $attach = $request->file('attach');
                    $obj->attach = $this->storeFile( $attach , $certi_cb->app_no);
                    $obj->attach_client_name  = $attach->getClientOriginalName();
                }

                if( $request->hasFile('attach_pdf')  ){
                    $check = true;
                    $attach_pdf = $request->file('attach_pdf');
                    $obj->attach_pdf = $this->storeFile( $attach_pdf , $certi_cb->app_no);
                    $obj->attach_pdf_client_name  = $attach_pdf->getClientOriginalName();
                }

                if( $check == true){
                    $obj->save();
                }

                if (!is_null($request->id)) {
                    return redirect('certify/certificate-export-cb/' . $request->id . '/edit')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                } else {
                    return redirect('certify/certificate-export-cb')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
                }
            }
            return redirect('certify/certificate-export-cb')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect('certify/certificate-export-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
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
        $Cost = CertiCBFileAll::findOrFail($id);
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
        $model = str_slug('certificateexportcb', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $id = $request->input('certicb_file_id');
            $state = $request->input('state');

            $result = CertiCBFileAll::findOrFail($id);
                      CertiCBFileAll::where('app_certi_cb_id', $result->app_certi_cb_id)->update(['state' => 0]);
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
        $data = CertiCBFileAll::where('id', $id)->first();
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

    public function check_api_pid(Request $request)
    {
         $data  =  CertiCb::findOrFail($request->id);
         return response()->json([
                                 'message' =>  HP_API_PID::CheckDataApiPid($data,(new CertiCb)->getTable())
                                  ]);
    }

    public function delete_file_certificate($id)
    {
      try {
            $export_cb = CertiCBExport::findOrFail($id);
            if(!is_null($export_cb)){
                $attach_path  =  $this->attach_path;
                if(!empty($export_cb->certificate_path) && !empty($export_cb->certificate_newfile)){
                     $attachs =  $export_cb->certificate_path.'/' .$export_cb->certificate_newfile;
                      if(HP::checkFileStorage($attachs)){
                        Storage::delete("/".$attachs);
                      }
                      $export_cb->certificate_path = null;
                      $export_cb->certificate_file = null;
                      $export_cb->certificate_newfile = null;
                      $export_cb->save();
                 }else if(!empty($export_cb->attachs)){
                        $attachs =  $attach_path.$export_cb->attachs;
                        if(HP::checkFileStorage($attachs)){
                               HP::getFileStoragePath($attachs);
                         }
                         $export_cb->attachs = null;
                         $export_cb->attach_client_name = null;
                         $export_cb->save();
                    }
                 }
           return redirect()->back()->with('flash_message', 'ลบไฟล์เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('message_error', 'เกิดข้อผิดพลาดกรุณาลบใหม่');
        }
    }
}
