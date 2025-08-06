<?php

namespace App\Http\Controllers\Certify\CB;

use DB;
use HP;

use File;
use PDF; 
use Storage;
use App\User;
use stdClass;
use HP_API_PID;
use Carbon\Carbon;
use App\Http\Requests;
use App\CheckCertificateCB;
use App\IpaymentCompanycode;
use Illuminate\Http\Request;
use App\Mail\CB\CBReportMail;
use App\Mail\CB\CBRequestMail;
use App\Mail\CB\CBPayInOneMail;
use App\Mail\CB\CBPayInTwoMail;
use App\Models\Basic\Feewaiver;
use App\Mail\CB\CBDocumentsMail;
use App\Models\Certify\PayInAll;
use App\Mail\CB\CBInformPayInOne;
use App\Mail\CB\CBAssignStaffMail;
use App\Http\Controllers\Controller;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CB\CBScopeEditAlertMail;
use App\Models\Bcertify\StatusAuditor;
use App\Models\Certify\TransactionPayIn;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\ApplicantCB\CertiCBCost;
use App\Models\Bcertify\CbRequestRejectTracking;

use App\Models\Certify\ApplicantCB\CertiCBCheck;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantCB\CertiCBReport;
use App\Models\Certify\ApplicantCB\CertiCBReview;
use App\Models\Certify\ApplicantCB\CertiCBStatus;
use App\Models\Certify\ApplicantIB\CertiIBStatus;
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBPayInOne;
use App\Models\Certify\ApplicantCB\CertiCBPayInTwo;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCbExportMapreq;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessmentBug;

class CheckCertificateCBController extends Controller
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
        
        $model = str_slug('checkcertificatecb','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['filter_inspector'] = $request->get('filter_inspector', '');
            $filter['filter_name'] = $request->get('filter_name', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiCb;
            $Query = $Query->select('app_certi_cb.*');
            if ($filter['filter_status']!='') {
                if ($filter['filter_status'] == "25") {
                    $Query = $Query->where('review',1);
                }else{
                    $Query = $Query->where('status', $filter['filter_status']);
                }
            }else{
                $Query = $Query->where('status', '>=', '1');
            }




            if ($filter['filter_search'] != '') {
                $Query = $Query->where(function ($query) use ($filter) {
                    $search = str_replace(' ', '', $filter['filter_search']);
                    $query->where(DB::raw("REPLACE(name,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(name_standard,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(app_no,' ','')"), 'like', '%'.$search.'%');
                });
            }

            
            if ($filter['filter_inspector']!='') { // เจ้าหน้าที่ตรวจสอบ
                $Query = $Query->whereHas('certi_cb_checks', function ($query) use ($filter) {
                    $query->where('user_id', $filter['filter_inspector']);
                });
            }

            if ($filter['filter_name']!='') { // หน่วยงาน
                $Query = $Query->where(function ($query) use ($filter) {
                    $key = str_replace(' ', '', $filter['filter_name']);
                    $query->where(DB::raw("REPLACE(name,' ','')"), 'like', '%'.$key.'%');
                });
            }

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = HP::convertDate($filter['filter_start_date'],true);
                $end = HP::convertDate($filter['filter_end_date'],true);
                $Query = $Query->whereBetween('created_at', [$start,$end]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start =  HP::convertDate($filter['filter_start_date'],true);
                $Query = $Query->whereDate('created_at',$start);
            }

            
             if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                dd(auth()->user()->RoleListId);
                $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                    // dd($check->latest()->first());
                     $Query = $Query->LeftJoin('app_certi_cb_check','app_certi_cb_check.app_certi_cb_id','=','app_certi_cb.id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }

            // dd($Query->latest()->first());
            $certi_cbs =  $Query->orderby('id','desc')
                               ->sortable()
                                ->paginate($filter['perPage']);

             $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                            ->whereIn('reg_subdepart',[1803])
                            ->orderbyRaw('CONVERT(title USING tis620)')
                            ->pluck('title','runrecno');


            $status  = CertiIBStatus::select('title','id')->whereNotIn('id',[0])->pluck('title','id');


            return view('certify/cb/check_certificate_cb.index', compact('certi_cbs', 'filter','select_users','status'));
        }
        abort(403);

    }


    public function assign(Request $request)
    {
  try {

        $checker = $request->input('checker');
        $apps = $request->input('apps');
        $tb = new CertiCb;
        if (count($checker) > 0  && count($apps) > 0) {

            // ชื่อเจ้าหน้าที่รับผิดชอบตรวจสอบ
             $reg_fname = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))
                                ->whereIn('runrecno',$checker)
                                ->whereNotNull('reg_fname')
                                ->pluck('title')
                                ->toArray();
             // ส่ง E-mail
             $reg_email = User::select('reg_email')
                                ->whereIn('runrecno',$checker)
                                ->whereNotNull('reg_email')
                                ->pluck('reg_email')
                                ->toArray();

            $CertiCb =  CertiCb::select('app_no','name','status','token')->whereIN('id',$apps)->get();

            foreach ($apps as $app_id) {
                   $app = CertiCb::find($app_id);
                if ($app){
                     // เช็คคำขอมอบหมายให้เจ้าหน้าที่หรือยัง
                    if($app->status == 1){
                       $app->update(['status'=> 2]);
                    }
                   $examiner = $this->save_check_examiner($checker,$app_id);
                   if(count($reg_email) > 0){
            
                   $data_app = [ 'apps'      =>  $app ?? null,
                                'email'     => auth()->user()->reg_email ?? 'admin@admin.com',
                                'reg_fname' => (count($reg_fname) > 0) ? implode(", ",$reg_fname) : null
                             ];
                   
                         $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiCb)->getTable(),
                                                                    $examiner->id,
                                                                    (new CertiCBCheck)->getTable(),
                                                                    1,
                                                                    'ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถหน่วยรับรอง',
                                                                    view('mail.CB.assign_staff', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    auth()->user()->reg_email ?? null,
                                                                    implode(',',(array)$reg_email),
                                                                    null,
                                                                    null,
                                                                    null
                                                                 );

                           $html = new CBAssignStaffMail($data_app);
                           $mail =  Mail::to($reg_email)->send($html);
                
                           if(is_null($mail) && !empty($log_email)){
                               HP::getUpdateCertifyLogEmail($log_email->id);
                           }
 
                    }
                }
              }
          }
        return redirect('certify/check_certificate-cb')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว');
      } catch (\Exception $e) {
        return redirect('certify/check_certificate-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
      }
    }
    private function save_check_examiner($checker, $id){
        CertiCBCheck::where('app_certi_cb_id', $id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['app_certi_cb_id'] = $id;
          $input['user_id'] = $item;
          $input['created_by'] = auth()->user()->runrecno;
        $examiner =   CertiCBCheck::create($input);
        }
        return $examiner;
      }

      public function showCertificatecbDetail($CertiCb)
      {
          $previousUrl = app('url')->previous();
          $certi_cb = CertiCb::where('app_no',$CertiCb)->firstOrFail();
          $tis_data = SSO_User::find($certi_cb->created_by);
          $attach_path = $this->attach_path;//path ไฟล์แนบ

          return view('certify/cb/check_certificate_cb.detail', compact('certi_cb','previousUrl','tis_data','attach_path'));
      }


    public function show($token)
    {
        // dd('ok');
        $model = str_slug('checkcertificatecb','-');
        if(auth()->user()->can('view-'.$model)) {
            $certi_cb = CertiCb::where('token',$token)->first();
            // ประวัติคำขอ
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $history  =  CertiCbHistory::where('app_certi_cb_id',$certi_cb->id)
                                        ->orderby('id','desc')
                                        ->get();
             $attach_path = $this->attach_path;//path ไฟล์แนบ
        return view('certify/cb/check_certificate_cb.show', compact('certi_cb','history','attach_path'));
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {
        // dd('ok');
        $model = str_slug('checkcertificatecb','-');
        if(auth()->user()->can('edit-'.$model)) {
            $request->validate([
              'status' => 'required|in:2,3,4,5,6,7,9,15,27',
            ]);
      try {
            $tb = new CertiCb;
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $requestData = $request->all();
            $requestData['save_date'] =  $request->save_date ? HP::convertDate($request->save_date,true) : null;
            $certi_cb = CertiCb::findOrFail($id);

            $status = $request->status;
          
       // status = 2,3,5,6
        if (in_array($status, ['3','4','5'])) { // 3. ขอเอกสารเพิ่มเติม 4. ยกเลิกคำขอ 5. ไม่ผ่านการตรวจสอบ

            if($status == 3){
                
                $section = 6; // ขอเอกสารเพิ่มเติม
                $system = 1;
                // $system_mail = 3; //ขอเอกสารเพิ่มเติม
                $requestData['details']         =  $request->desc ?? null;
                CbRequestRejectTracking::where('app_certi_cb_id',$certi_cb->id)->delete();
                $rejectTracking = new CbRequestRejectTracking();
                $rejectTracking->app_certi_cb_id = $certi_cb->id;
                $rejectTracking->date = Carbon::now(); 
                $rejectTracking->save();
                // dd($status,'ok');
            }else if($status == 4){
                $section = 7;  // ยกเลิกคำขอ
                $system = 2;
                // $system_mail = 4; // ยกเลิกคำขอ
                $requestData['details']         =      null;
                $requestData['desc_delete']     =  $request->desc ?? null;
            }else if($status == 5){
                $section = 8; // ไม่ผ่านการตรวจสอบ
                $system = 3;
                // $system_mail = 5; // ไม่ผ่านการตรวจสอบ
                $requestData['details']         =      null;
                $requestData['desc_delete']     =  $request->desc ?? null;
            }

           $certi_cb->update($requestData);

            if ($request->hasFile('file')) {
                $attachs = [];
                foreach ($request->file as $index => $item){
                    $certi_cb_attach_more = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id  = $certi_cb->id;
                    $certi_cb_attach_more->table_name       =  $tb->getTable();
                    $certi_cb_attach_more->file_section     = $section ?? null;
                    $certi_cb_attach_more->file             = $this->storeFile($item,$certi_cb->app_no);
                    $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                    $certi_cb_attach_more->token            = str_random(16);
                    $certi_cb_attach_more->save();

                    $list  = new  stdClass;
                    $list->file_desc        =    $certi_cb_attach_more->file_desc ;
                    $list->file             =    $certi_cb_attach_more->file ;
                    $list->file_client_name =    $certi_cb_attach_more->file_client_name ;
                    $list->attach_path      =    $this->attach_path ;
                    $attachs[]              =    $list;
                }
            }
            // log
            CertiCbHistory::create([
                                    'app_certi_cb_id'   => $certi_cb->id ?? null,
                                    'system'            => isset($system) ? $system : null,
                                    'table_name'        => $tb->getTable(),
                                    'status'            => $certi_cb->status ?? null,
                                    'ref_id'            => $certi_cb->id,
                                    'details_one'       => $certi_cb->details ?? null,
                                    'details_two'       => $certi_cb->desc_delete ?? null,
                                    'attachs'           => isset($attachs) ?  json_encode($attachs) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                                  ]);

        if(!is_null($certi_cb->email)){
             // mail
             $title_status =  ['3'=>'ขอเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
             $data_status =  ['3'=>'แนบเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
 
            $data_app = ['certi_cb'     => $certi_cb ?? '-',
                        'desc'         =>  $certi_cb->details ?? '-',
                        'status'       =>   $status,
                        'title'        =>   array_key_exists($status,$title_status) ?$title_status[$status] : null,
                        'data'         =>   array_key_exists($status,$data_status) ?$data_status[$status] : null,
                        'name'         =>   !empty($certi_cb->name)  ?   $certi_cb->name  : '-',
                        'attachs'      =>  isset($attachs) ?  $attachs   : '-',
                        'url'          =>  $url.'certify/applicant-cb',
                        'email'        =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                        'email_cc'     =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                        'email_reply'  => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                        ];
            
                    $log_email =  HP::getInsertCertifyLogEmail( $certi_cb->app_no,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            3,
                                                            $title_status[$status]  ?? null,
                                                            view('mail.CB.documents', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new CBDocumentsMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
         }
               
      }

            if (in_array($status, ['6'])) {
                $requestData['app_no']      =  isset($certi_cb->app_no) ?  str_replace("RQ-","",$certi_cb->app_no) : @$certi_cb->app_no;
                $requestData['get_date']    =   date('Y-m-d h:m:s');
                $certi_cb->update($requestData);
                if($certi_cb && !is_null($certi_cb->email)){
                    $cost = CertiCBCost::where('app_certi_cb_id',$certi_cb->id)->first();
                    if(is_null($cost)){
                        $cost = new CertiCBCost;
                        $cost->app_certi_cb_id = $certi_cb->id;
                        $cost->draft = 0;
                        $cost->created_by = auth()->user()->runrecno;
                        $cost->save();
                    }

             $data_app = [  'certi_cb'       => $certi_cb ?? '-',
                            'url'           => $url.'certify/applicant-cb' ?? '-',
                            'email'         => !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'      => !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                            'email_reply'   => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                         ];
            
                    $log_email =  HP::getInsertCertifyLogEmail( $certi_cb->app_no,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            3,
                                                            'รับคำขอรับบริการ',
                                                            view('mail.CB.request', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new CBRequestMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    

                 }
            }

            if(isset($certi_cb->token)){
                return redirect('certify/check_certificate-cb/'.$certi_cb->token .'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
            }else{
            return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
            }

       } catch (\Exception $e) {
             return redirect('certify/check_certificate-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }  
    }
        abort(403);
    }
    public function DataShow($id = null)
    {
        $previousUrl = app('url')->previous();
        $history  =  CertiCbHistory::findOrFail($id);
        return view('certify/cb/check_certificate_cb.history_detail',  compact('previousUrl',
                                                                               'history'
                                                                             ));
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
   
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
        }

        //  ลบไฟล์หลักฐาน
        public function delete_file($id)
        {
                $Cost = CertiCBAttachAll::findOrFail($id);
                if(!is_null($Cost)){
                    $Cost->delete();
                    $file = 'true';
                }else{
                     $file = 'false';
                }

          return  $file;

        }
     public function GetCBPayInOne($id = null,$token = null)
     {
            $previousUrl = app('url')->previous();
            $pay_in  =  CertiCBPayInOne::findOrFail($id);  
            $attach_path = $this->attach_path;//path ไฟล์แนบ
        
            if(is_null($pay_in->conditional_type) && !empty($pay_in->CertiCBAuditorsTo->StartDateBoardAuditor)){
                  $start_date      = $pay_in->CertiCBAuditorsTo->StartDateBoardAuditor->start_date ?? null;
                  $feewaiver  =  Feewaiver::where('certify',3)
                                        ->where('payin1_status',1)
                                        ->whereDate('payin1_start_date','<=',$start_date)
                                        ->whereDate('payin1_end_date','>=',$start_date)
                                        ->first();
                if(!is_null($feewaiver)){
                    $pay_in->conditional = 2; // เรียกเก็บค่าธรรมเนียม
                }else{
                    $pay_in->conditional = 1; // ยกเว้นค่าธรรมเนียม
                }
            }else{
                $feewaiver = null;
            }
            return view('certify.cb.check_certificate_cb.pay_in_one',  compact('previousUrl',
                                                                                'pay_in',
                                                                                'attach_path',
                                                                                'feewaiver'
                                                                              ));
     }
  public function CertiCBPayInOne(Request $request, $id){
        // dd($request->all());
        $arrContextOptions=array();
         $attach_path =  $this->attach_path ;
         $tb = new CertiCBPayInOne;
         $config = HP::getConfig();
         $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
         $PayIn = CertiCBPayInOne::findOrFail($id);
       

    try {
        $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
        // dd($PayIn->state);

      if($PayIn->state == null){

                    $PayIn->conditional_type = $request->conditional_type;
                    $PayIn->created_by          =  auth()->user()->runrecno;
                    $PayIn->state = 1;  // ส่งให้ ผปก.
                    $PayIn->start_date =   isset($request->start_date)?  HP::convertDate($request->start_date,true) : @$PayIn->start_date;
                    $PayIn->amount =      isset($request->amount)?str_replace(",","",$request->amount): @$PayIn->amount;
                    $PayIn->amount_bill =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):@$PayIn->amount_bill;
                    $PayIn->save();

            if($PayIn->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม

                    $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',1)->where('type',1)->first();
                    $app_no =  $certi_cb->app_no;
                    if(!is_null($setting_payment) ){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                            "verify_peer" => false,
                                                            "verify_peer_name" => false,
                                                        );
                        }
                        $app_no          =  $certi_cb->app_no;
                        $timestamp = Carbon::now()->timestamp;
                        $refNo = $app_no.'-'.$PayIn->auditors_id.''.$timestamp;

                        // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$app_no-$PayIn->auditors_id", false, stream_context_create($arrContextOptions));
                        $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));

                        $api = json_decode($content);

                        $invoiceFile = null;
                        $invoiceileName = null;
                        // if(strpos($setting_payment->data, '127.0.0.1')===0){
                        // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                        $host = parse_url($setting_payment->data, PHP_URL_HOST);

                        if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {                            
                            $invoiceFile =    $this->storeFilePayin($setting_payment,$certi_cb->app_no,$PayIn->auditors_id);
                        }else{
                            
                            $invoiceFile=   $this->storeFilePayinDemo($setting_payment,$app_no,$PayIn->auditors_id);
                            $invoiceileName = basename($invoiceFile);
                        }

                        // dd($invoiceileName);
                
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->ref_id               = $PayIn->id;
                        $certi_cb_attach_more->file_section         = '1';
                        $certi_cb_attach_more->file_desc            = 'เรียกเก็บค่าธรรมเนียม';
                        $certi_cb_attach_more->file                 =  $invoiceFile;
                        $certi_cb_attach_more->file_client_name     =  $invoiceileName;
                        $certi_cb_attach_more->token                =  str_random(16);
                        $certi_cb_attach_more->save();
        
                        $transaction = HP::TransactionPayIn1($PayIn->id,$tb->getTable(),'3','1',$api,$app_no.'-'.$PayIn->auditors_id,$timestamp);

                        // HP::TransactionPayIn1($find_cost_assessment->id,$ao->getTable(),'1','1',$api,$app_no.'-'.$find_cost_assessment->app_certi_assessment_id,$timestamp);

                        $file =  $PayIn->FileAttachPayInOne1To->file ?? null;
                        if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                            HP::getFileStoragePath($attach_path.$file);
                        }
                }
            }else  if($PayIn->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                    $feewaiver  =  Feewaiver::where('certify',3)->first();
                    if(!is_null($feewaiver->payin1_file)){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->ref_id               = $PayIn->id;
                        $certi_cb_attach_more->file_section         = '1';
                        $certi_cb_attach_more->file_desc            = 'ยกเว้นค่าธรรมเนียม';
                        $certi_cb_attach_more->file                 =  $feewaiver->payin1_file;
                        $certi_cb_attach_more->file_client_name     =  $feewaiver->payin1_file_client_name;
                        $certi_cb_attach_more->token                =  str_random(16);
                        $certi_cb_attach_more->save();

                        $file =  $PayIn->FileAttachPayInOne1To->file ?? null;
                        if(!is_null($file) && HP::checkFileStorage($file)){
                            HP::getFileStoragePath($file);
                        } 
                    }

                    $PayIn->start_date_feewaiver        =  $feewaiver->payin2_start_date ?? null;
                    $PayIn->end_date_feewaiver          =  $feewaiver->payin2_end_date ?? null;
                    $PayIn->save();
   
            }else  if($PayIn->conditional_type == 3){  // ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
                    $PayIn->detail = $request->detail ?? null;
                    $PayIn->save();
                if($request->attach && $request->hasFile('attach')){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->ref_id               = $PayIn->id;
                        $certi_cb_attach_more->file_section         = '1';
                        $certi_cb_attach_more->file_desc            = 'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม';
                        $certi_cb_attach_more->file                 =   $this->storeFile($request->attach,$certi_cb->app_no);
                        $certi_cb_attach_more->file_client_name     =  HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                        $certi_cb_attach_more->token                =  str_random(16);
                        $certi_cb_attach_more->save();
                        $file =  $PayIn->FileAttachPayInOne1To->file ?? null;
                        if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                            HP::getFileStoragePath($attach_path.$file);
                        }
                }
            }
      
                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiCBAuditors::findOrFail($PayIn->auditors_id);
                if(!is_null($auditor)){
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                    $auditor->save();
                }

                self::insert_payin_all(1,$PayIn);
                // LOG
                $data = CertiCBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id', 'auditors_id')
                                        ->where('id',$id)
                                        ->first();
                CertiCbHistory::create([
                                        'app_certi_cb_id'       =>  $PayIn->app_certi_cb_id ?? null,
                                        'auditors_id'           =>  $PayIn->auditors_id ?? null,
                                        'system'                => 6, //Pay-In ครั้งที่ 1
                                        'table_name'            => $tb->getTable(),
                                        'ref_id'                => $PayIn->id,
                                        'status'                => $PayIn->status ?? null,
                                        'details_one'           => $PayIn->amount ?? null,
                                        'details_two'           => $PayIn->start_date ?? null,
                                        'details_three'         => $PayIn->CertiCBAuditorsTo->auditor ?? null,
                                        'details_four'          => $PayIn->remark ?? null,
                                        'details_five'          => json_encode($data) ?? null,
                                        'attachs'               => $PayIn->FileAttachPayInOne1To->file ?? null,
                                        'attach_client_name'    => $PayIn->FileAttachPayInOne1To->file_client_name ?? null,
                                        'created_by'            =>  auth()->user()->runrecno
                                      ]);
                // Mail
                if(!is_null($certi_cb->email)){ // แจ้งเตือนผู้ประกอบการ
                   $data_app = [
                                'PayIn'            => $PayIn,
                                'certi_cb'         => $certi_cb ?? '-',
                                'attachs'          => !empty($certi_cb->FileAttachPayInOne1To->file) ? $certi_cb->FileAttachPayInOne1To->file : '',
                                'url'              => $url.'certify/applicant-cb' ?? '-',
                                'email'            => !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'         => !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                'email_reply'      => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                              ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no.'-'.@$PayIn->auditors_id,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiCBPayInOne)->getTable(),
                                                            3,
                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                            view('mail.CB.pay_in_one', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            !empty($PayIn->FileAttachPayInOne1To->file) ?  'certify/check/file_cb_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :   basename($PayIn->FileAttachPayInOne1To->file) ) : null
                                                            );

                    $html = new CBPayInOneMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    } 
 
                }
                // $certi_cb->status = 12;// แจ้งรายละเอียดค่าตรวจประเมิน 
                // $certi_cb->save();                  
        }else{
// dd($request->ReceiptCreateDate);
            if($request->status == 1){
                $PayIn->remark =  null;
                $PayIn->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                $PayIn->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
             }else{
                $PayIn->state = 1;  // ส่งให้ ผปก.
                $PayIn->remark = $request->remark ?? null;
                $PayIn->status = 0;
             }

                $PayIn->condition_pay     =  !empty($request->condition_pay) ?  $request->condition_pay : null ; 
                $PayIn->save();
            
             if(!empty($request->ReceiptCreateDate)){
                 $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiCBPayInOne)->getTable())->orderby('id','desc')->first();
                 if(!is_null($transaction_payin)){
                     $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                     $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                     $transaction_payin->save();
                 }
             }
 

                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiCBAuditors::findOrFail($PayIn->auditors_id);
             if(!is_null($auditor)){
                if($PayIn->state == 3){
                    $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                }else{
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                }
                   $auditor->save();
             }

                // LOG
                $data = CertiCBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id','auditors_id','condition_pay')
                                        ->where('id',$id)
                                        ->first();
              CertiCbHistory::create([
                                        'app_certi_cb_id'   =>  $PayIn->app_certi_cb_id ?? null,
                                        'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                        'system'            => 6, //Pay-In ครั้งที่ 1
                                        'table_name'        => $tb->getTable(),
                                        'ref_id'            => $PayIn->id,
                                        'status'            => $PayIn->status ?? null,
                                        'details_one'       => $PayIn->amount ?? null,
                                        'details_two'       => $PayIn->start_date ?? null,
                                        'details_three'     => $PayIn->CertiIBAuditorsTo->auditor ?? null,
                                        'details_four'      => $PayIn->remark ?? null,
                                        'details_five'      =>  json_encode($data) ?? null,
                                        'attachs'           => $PayIn->FileAttachPayInOne1To->file ?? null,
                                        'attach_client_name'=> $PayIn->FileAttachPayInOne1To->file_client_name ?? null,
                                        'attachs_file'      => $PayIn->FileAttachPayInOne2To->file ?? null,
                                        'evidence'          => $PayIn->FileAttachPayInOne2To->file_client_name ?? null,
                                        'created_by'        =>  auth()->user()->runrecno
                                    ]);

      if(!is_null($certi_cb->email)){
            if($PayIn->state == 1){  // แจ้งเตือนผู้ประกอบการ   
                   $data_app = [
                                'PayIn'            => $PayIn,
                                'certi_cb'         => $certi_cb ?? '-',
                                'attachs'          => !empty($certi_cb->FileAttachPayInOne1To->file) ? $certi_cb->FileAttachPayInOne1To->file : '',
                                'url'              => $url.'certify/applicant-cb' ?? '-',
                                'email'            => !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'         => !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                'email_reply'      => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                             ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no.'-'.@$PayIn->auditors_id,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiCBPayInOne)->getTable(),
                                                            3,
                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                            view('mail.CB.pay_in_one', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            !empty($PayIn->FileAttachPayInOne1To->file) ?  'certify/check/file_cb_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :   basename($PayIn->FileAttachPayInOne1To->file) ) : null
                                                            );

                    $html = new CBPayInOneMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    } 
                //   $certi_cb->status = 12;// แจ้งรายละเอียดค่าตรวจประเมิน 
                //   $certi_cb->save();  
            }else{
                if(count($certi_cb->EmailStaffAssign) > 0){
                   $data_app = [
                                'PayIn'        => $PayIn,
                                'certi_cb'     => $certi_cb,
                                'url'          => $url.'certify/applicant-ib',
                                'email'        =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'     =>  !empty($certi_cb->DataEmailDirectorAndLtIBCC) ? $certi_cb->DataEmailDirectorAndLtIBCC : 'cb@tisi.mail.go.th',
                                'email_reply'  => !empty($certi_cb->DataEmailDirectorIBReply) ? $certi_cb->DataEmailDirectorIBReply : 'cb@tisi.mail.go.th'
                                ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no.'-'.@$PayIn->auditors_id,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiCBPayInOne)->getTable(),
                                                            3,
                                                            'ใบรับรองหน่วยรับรอง',
                                                            view('mail.CB.inform_pay_in_one', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            implode(',',(array)$certi_cb->EmailStaffAssign),
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new CBInformPayInOne($data_app);
                    $mail =  Mail::to($certi_cb->EmailStaffAssign)->send($mail);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    } 
                }
            //    $certi_cb->status = 14;// แจ้งรายละเอียดค่าตรวจประเมิน 
            //    $certi_cb->save();  
            }
          }

         }

        //  http://127.0.0.1:8081/certify/check_certificate-cb/oxaqnAyCLQQYouAu/show/236
         if(isset($certi_cb->token)){
            return redirect('certify/check_certificate-cb/'.$certi_cb->token.'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
         }else{
            return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
        }

    
    } catch (\Exception $e) {
        return redirect('certify/check_certificate-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    }    

   }

   public function storeFilePayinDemo($setting_payment, $app_no = 'files_lab', $auditor_id = '')
   {
      $baseUrl = strstr($setting_payment->data, '/api', true);

      $url = $baseUrl. '/images/PayIn2.pdf';

      // ดาวน์โหลดเนื้อหา PDF (Demo)
    //   $pdf_content = file_get_contents($url);

       $contextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        if (strpos($setting_payment->data, 'https') === 0) {
            $context = stream_context_create($contextOptions);
            $pdf_content = file_get_contents($url, false, $context);
        } else {
            $pdf_content = file_get_contents($url);
        }

      // dd($pdf_content);
           
       $no  = str_replace("RQ-","",$app_no);
       $no  = str_replace("-","_",$no);
       if ($pdf_content) {
           $attach_path  =  $this->attach_path.$no;
           $fullFileName =  $no.'-'.date('Ymd_hms').'.pdf';
           $storagePath = Storage::put($attach_path.'/'.$fullFileName, $pdf_content);


          $filePath = $attach_path .'/'. $fullFileName;
          if (Storage::disk('ftp')->exists($filePath)) {
           //    dd('File Path on Server: ' . $filePath);
               return  $no.'/'.$fullFileName;
          } else {
             return null;
          }
           
       }else{
           return null;
       }
    }


    
    public function SaveReview(Request $request, $id){
        $report = CertiCBReport::findOrFail($id);
        $certi_cb = CertiCb::findOrFail($report->app_certi_cb_id);
        if($request->report_status == "2"){
            $certiCBSaveAssessmentIds = CertiCBSaveAssessment::where('app_certi_cb_id',$report->app_certi_cb_id)->pluck('id')->toArray();
            CertiCBSaveAssessmentBug::whereIn('assessment_id',$certiCBSaveAssessmentIds)->delete();
            CertiCBSaveAssessment::where('app_certi_cb_id',$report->app_certi_cb_id)->delete();
            CertiCb::findOrFail($report->app_certi_cb_id)->update([
                'status' => 10
            ]);
            CertiCbHistory::create([
                'app_certi_cb_id'      => $report->app_certi_cb_id ?? null,
                'system'               => 9,
                'table_name'           => (new CertiCBReport)->getTable(),
                'ref_id'               => $report->id,
                'details_one'          =>  null,
                'details_two'          =>  $report->details ?? null,
                'status'               => null,
                'file'                 => null,
                'file_client_name'     =>  null,
                'attachs'              => null,
                'created_by'           =>  auth()->user()->runrecno
         ]);
        }else{
            CertiCBReport::findOrFail($id)->update([
                'review_approve' => 2
            ]);
        }

        
        $json = $this->copyScopeCbFromAttachement($report->app_certi_cb_id);
        $copiedScopes = json_decode($json, true);

        $tb = new CertiCBReport;
        $certi_cb_attach_more = new CertiCBAttachAll();
        $certi_cb_attach_more->app_certi_cb_id      = $report->app_certi_cb_id ?? null;
        $certi_cb_attach_more->ref_id               = $report->id;
        $certi_cb_attach_more->table_name           = $tb->getTable();
        $certi_cb_attach_more->file_section         = '1';
        $certi_cb_attach_more->file                 = $copiedScopes[0]['attachs'];
        $certi_cb_attach_more->file_client_name     = $copiedScopes[0]['file_client_name'];
        $certi_cb_attach_more->token                = str_random(16);
        $certi_cb_attach_more->save();

        if(isset($certi_cb->token)){
            return redirect('certify/check_certificate-cb/'.$certi_cb->token.'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
         }else{
            return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
        }

    }

    public function askToEditCbScope(Request $request)
    {
        // dd($request->all());
        // $report = CertiCBReport::findOrFail($request->appId);
        CertiCb::findOrFail($request->appId)->update([
            'require_scope_update' => 1
        ]);

        $this->askToEditCbScopeEmail($request->appId,$request->details);
    }

    public function askToEditCbScopeEmail($id,$message)
    {
        $certi_cb = CertiCb::findOrFail($id);
 //Mail
                    $config = HP::getConfig();
                   
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                    $data_app =[
                                'certi_cb'             => $certi_cb ?? '-',
                                'message'     => $message,
                                'url'                  => $url.'certify/applicant-cb' ?? '-',
                                'email'                =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'             =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                'email_reply'          => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                              ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            null,
                                                            (new CertiCb)->getTable(),
                                                            3,
                                                            'สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ',
                                                            view('mail.CB.scope_edit', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new CBScopeEditAlertMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    } 
 
    }
      // สรุปรายงานและเสนออนุกรรมการฯ
        public function UpdateReport(Request $request, $id){

            // dd($request->all());

            $report = CertiCBReport::findOrFail($id);
            $certi_cb = CertiCb::findOrFail($report->app_certi_cb_id);
            $tb = new CertiCBReport;

            $requestData = $request->all();
            $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
            $requestData['start_date'] =  !empty($request->start_date)?HP::convertDate($request->start_date, true):null;
            $requestData['end_date'] =  !empty($request->end_date)?HP::convertDate($request->end_date, true):null;
            $requestData['issue_date'] =  !empty($request->issue_date)?HP::convertDate($request->issue_date, true):null;
            $requestData['created_by'] =   auth()->user()->runrecno;


            $report->update($requestData);

            // CertiCBFileAll::where('app_certi_cb_id',$certi_cb->id)->update([
            //     'start_date' => !empty($request->start_date)?HP::convertDate($request->start_date):null,
            //     'end_date' => !empty($request->end_date)?HP::convertDate($request->end_date):null,
            //     'issue_date' => !empty($request->issue_date)?HP::convertDate($request->issue_date):null,
            // ]);

             // รายงาน  ขอบข่ายที่ได้รับการเห็นชอบ
            // if($request->file_loa && $request->report_status == 1 && $request->hasFile('file_loa')){
            if($request->report_status == 1){   
                        $certiCBAttachAll = CertiCBAttachAll::where('app_certi_cb_id',$report->app_certi_cb_id)->where('table_name','app_certi_cb_assessment')->where('file_section',2)->first(); 
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $report->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id               = $report->id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->file_section         = '1';
                        // $certi_cb_attach_more->file                 = $this->storeFile($request->file_loa,$certi_cb->app_no);
                        // $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName(@$request->file_loa->getClientOriginalName());
                        $certi_cb_attach_more->file                 = $certiCBAttachAll->file;
                        $certi_cb_attach_more->file_client_name     = $certiCBAttachAll->file_client_name;
                        $certi_cb_attach_more->token                = str_random(16);
                        $certi_cb_attach_more->save();

                        //แนบท้าย
                        if(isset($certi_cb_attach_more->file)){
                            $certi_id =  $certi_cb->id;
                            CertiCBFileAll::where('app_certi_cb_id',$certi_id)->update(['state' => 0]);
                             CertiCBFileAll::create([                            
                                                        'ref_id'                    =>  $report->id,
                                                        'ref_table'                 =>  (new CertiCBReport)->getTable(),
                                                        'app_certi_cb_id'           =>  $certi_id,
                                                        'app_no'                    =>  $certi_cb->app_no,
                                                        'attach_pdf'                =>  $certi_cb_attach_more->file,
                                                        'attach_pdf_client_name'    =>  $certi_cb_attach_more->file_client_name,
                                                        'start_date'                => !empty($request->start_date)?HP::convertDate($request->start_date, true):null,
                                                        'end_date'                  => !empty($request->end_date)?HP::convertDate($request->end_date, true):null,
                                                        'issue_date'                => !empty($request->issue_date)?HP::convertDate($request->issue_date, true):null,
                                                        'state'                     => 1
                                                     ]);
                         }

            }
            // ไฟล์แนบ
            if($request->file && $request->hasFile('file')){
                foreach ($request->file as $index => $item){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $report->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id               = $report->id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->file_section         = '2';
                        $certi_cb_attach_more->file                 = $this->storeFile($item,$certi_cb->app_no);
                        $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName(@$item->getClientOriginalName());
                        $certi_cb_attach_more->file_desc            = $request->file_desc[$index] ?? null;
                        $certi_cb_attach_more->token                = str_random(16);
                        $certi_cb_attach_more->save();
                }
             }

            if($report->report_status == 1){
                $certi_cb->update(['status'=>13]); // รอยืนยันคำขอ
            }else{
                $certi_cb->update(['status'=>12]); //สรุปรายงานและเสนออนุกรรมการฯ
            }
                // LOG
                CertiCbHistory::create([
                                            'app_certi_cb_id'      => $report->app_certi_cb_id ?? null,
                                            'system'               => 9,
                                            'table_name'           => $tb->getTable(),
                                            'ref_id'               => $report->id,
                                            'details_one'          =>  $report->report_date ?? null,
                                            'details_two'          =>  $report->details ?? null,
                                            'status'               =>  $report->report_status ?? null,
                                            'file'                 =>  !empty($report->FileAttachReport1To->file) ? $report->FileAttachReport1To->file : null,
                                            'file_client_name'     =>  !empty($report->FileAttachReport1To->file_client_name) ? $report->FileAttachReport1To->file_client_name : null,
                                            'attachs'              => (count($report->FileAttachReport2Many) > 0) ? json_encode($report->FileAttachReport2Many) : null,
                                            'created_by'           =>  auth()->user()->runrecno
                                     ]);

            if(!is_null($certi_cb->email) && $report->report_status == 1){
                    //Mail
                    $config = HP::getConfig();
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                    $data_app =[
                                'report'               => $report,
                                'certi_cb'             => $certi_cb ?? '-',
                                'full_name'            => $certi_cb->FullRegName ?? '-',
                                'url'                  => $url.'certify/applicant-cb' ?? '-',
                                'email'                =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'             =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                'email_reply'          => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                              ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $report->id,
                                                            (new CertiCBReport)->getTable(),
                                                            3,
                                                            'สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ',
                                                            view('mail.CB.report', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            $certi_cb->email,
                                                            !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                            !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new CBReportMail($data_app);
                    $mail =  Mail::to($certi_cb->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    } 
 
             }

            // if(isset($certi_cb->token)){
            //     return redirect('certify/check_certificate-cb/'.$certi_cb->token)->with('flash_message', 'เรียบร้อยแล้ว');
            // }else{
            //     return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
            // }

            if(isset($certi_cb->token)){
                return redirect('certify/check_certificate-cb/'.$certi_cb->token.'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
             }else{
                return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
            }

    }


    
public function copyScopeCbFromAttachement($certiCbId)
{
    $copiedScoped = null;
    $fileSection = null;

    $app = CertiCb::find($certiCbId);

    $latestRecord = CertiCBAttachAll::where('app_certi_cb_id', $certiCbId)
    ->where('file_section', 3)
    ->where('table_name', 'app_certi_cb')
    ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
    ->first();

    $existingFilePath = 'files/applicants/check_files_cb/' . $latestRecord->file ;

    // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
    if (HP::checkFileStorage($existingFilePath)) {
        $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
        $no  = str_replace("RQ-","",$app->app_no);
        $no  = str_replace("-","_",$no);
        $dlName = 'scope_'.basename($existingFilePath);
        $attach_path  =  'files/applicants/check_files_cb/'.$no.'/';

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

    public function GetCBPayInTwo($id = null,$token = null)
     {
        $previousUrl = app('url')->previous();
        $payin2  =  CertiCBPayInTwo::findOrFail($id);
        $attach_path = $this->attach_path;//path ไฟล์แนบ
         $feewaiver  =  Feewaiver::where('certify',3)
                               ->where('payin2_status',1)
                                ->whereDate('payin2_start_date','<=',date('Y-m-d'))
                                ->whereDate('payin2_end_date','>=',date('Y-m-d'))
                                ->first();
         if(is_null($payin2->conditional_type)){
            if(!is_null($feewaiver)){
                $payin2->conditional = 2; // เรียกเก็บค่าธรรมเนียม
            }else{
                $payin2->conditional = 1; // ยกเว้นค่าธรรมเนียม
            }
          }
        return view('certify/cb/check_certificate_cb.pay_in_two', compact('previousUrl',
                                                                          'payin2',
                                                                          'attach_path',
                                                                          'feewaiver'
                                                                         ));
     }

    // แนบใบ Pay-in ครั้งที่ 2
     public function CreatePayInTwo(Request $request, $id){
    //    dd('create',$request->all());
            $arrContextOptions=array();

         $PayIn = CertiCBPayInTwo::findOrFail($id);
         $tb = new CertiCBPayInTwo;
         $attach_path =  $this->attach_path ;

        try {
              if(!is_null($PayIn)  ){
                    $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);

                    $PayIn->conditional_type    = $request->conditional_type;
                    $PayIn->report_date         =  date('Y-m-d'); 
                    $PayIn->degree              =  1; 
                    $PayIn->created_by          =  auth()->user()->runrecno;

                 if($PayIn->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม

                    $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',2)->where('type',1)->first();
   
                    if(!is_null($setting_payment)){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                            "verify_peer" => false,
                                                            "verify_peer_name" => false,
                                                        );
                        }


                        $timestamp = Carbon::now()->timestamp;
                        $refNo = $certi_cb->app_no.'-'.$timestamp;

                        // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$certi_cb->app_no", false, stream_context_create($arrContextOptions));
                        $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));
                        $api = json_decode($content);


                        // if(strpos($setting_payment->data, '127.0.0.1')===0){
                        // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                        $host = parse_url($setting_payment->data, PHP_URL_HOST);

                        if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {                        
                            $payInfile            =   $this->storeFilePayin($setting_payment,$certi_cb->app_no);
                        }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                            
                            $payInfile             =   $this->storeFilePayinDemo($setting_payment,$certi_cb->app_no);
                        }
    

                        $PayIn->amount_fixed    = 1000;
                        $PayIn->amount          = !empty(str_replace(",","",$api->app_check))?str_replace(",","",$api->app_check):null;
                        $PayIn->amount_fee      = !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                        $PayIn->save();

                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->ref_id               = $PayIn->id;
                        $certi_cb_attach_more->file_section         = '1';
                        $certi_cb_attach_more->file_desc            = 'เรียกเก็บค่าธรรมเนียม';
                        // $certi_cb_attach_more->file                 =  $this->storeFilePayin($setting_payment,$certi_cb->app_no);
                        $certi_cb_attach_more->file                 =  $payInfile;
                        $certi_cb_attach_more->file_client_name     =  isset($certi_cb_attach_more->file) ? basename($certi_cb_attach_more->file)  : null;
                        $certi_cb_attach_more->token                =  str_random(16);
                        $certi_cb_attach_more->save();

                        
        
                        $transaction = HP::TransactionPayIn2($id,$tb->getTable(),'3','2',$api,$timestamp);
                       
                      
                         $file =  $PayIn->FileAttachPayInTwo1To->file ?? null;
                         
                         if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                             $path = HP::getFileStoragePath($attach_path.$file);
                            //  dd($path);
                         }
                   }
                }else  if($PayIn->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                        $feewaiver  =  Feewaiver::where('certify',3)->first();
                        if(!is_null($feewaiver->payin2_file)){
                            $certi_cb_attach_more = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                            $certi_cb_attach_more->table_name           = $tb->getTable();
                            $certi_cb_attach_more->ref_id               = $PayIn->id;
                            $certi_cb_attach_more->file_section         = '1';
                            $certi_cb_attach_more->file_desc            = 'ยกเว้นค่าธรรมเนียม';
                            $certi_cb_attach_more->file                 =  $feewaiver->payin2_file;
                            $certi_cb_attach_more->file_client_name     =  $feewaiver->payin2_file_client_name;
                            $certi_cb_attach_more->token                =  str_random(16);
                            $certi_cb_attach_more->save();
    
                            $file =  $PayIn->FileAttachPayInTwo1To->file ?? null;
                            if(!is_null($file) && HP::checkFileStorage($file)){
                                HP::getFileStoragePath($file);
                            } 
                        }
                        $PayIn->start_date_feewaiver        =  $feewaiver->payin2_start_date ?? null;
                        $PayIn->end_date_feewaiver          =  $feewaiver->payin2_end_date ?? null;
                        $PayIn->save();

                }else  if($PayIn->conditional_type == 3){  // ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
                            $PayIn->remark = $request->remark ?? null;
                            $PayIn->save(); 
 
                        if($request->attach && $request->hasFile('attach')){
                            $certi_cb_attach_more = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id      = $PayIn->app_certi_cb_id;
                            $certi_cb_attach_more->table_name           = $tb->getTable();
                            $certi_cb_attach_more->ref_id               = $PayIn->id;
                            $certi_cb_attach_more->file_section         = '1';
                            $certi_cb_attach_more->file_desc            = 'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม';
                            $certi_cb_attach_more->file                 =   $this->storeFile($request->attach,$certi_cb->app_no);
                            $certi_cb_attach_more->file_client_name     =  HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                            $certi_cb_attach_more->token                =  str_random(16);
                            $certi_cb_attach_more->save();
                            $file =  $PayIn->FileAttachPayInTwo1To->file ?? null;
                            if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                                HP::getFileStoragePath($attach_path.$file);
                            }
                        }
                          
                 }
                         self::insert_payin_all(2,$PayIn);
                      $data = CertiCBPayInTwo::select('report_date', 'amount','amount_fixed', 'amount_fee', 'degree','status','conditional_type','remark','start_date_feewaiver','end_date_feewaiver')->where('id',$PayIn->id)->first();
                       CertiCbHistory::create([
                                              'app_certi_cb_id'     => $PayIn->app_certi_cb_id ?? null,
                                              'system'              => 10,
                                              'table_name'          => $tb->getTable(),
                                              'ref_id'              => $PayIn->id,
                                              'status'              => $PayIn->status ?? null,
                                              'details_one'         =>  json_encode($data) ?? null,
                                              'attachs'             =>  !empty($PayIn->FileAttachPayInTwo1To->file)  ? $PayIn->FileAttachPayInTwo1To->file : '',
                                              'attach_client_name'  =>  !empty($PayIn->FileAttachPayInTwo1To->file_client_name)  ? $PayIn->FileAttachPayInTwo1To->file_client_name : null, 
                                              'created_by'          =>  auth()->user()->runrecno
                                            ]);
                     //Mail
 
                  if(!is_null($certi_cb->email)){
                        $config = HP::getConfig();
                        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                        $data_app =[
                                    'PayIn'           => $PayIn,
                                    'certi_cb'        => $certi_cb,
                                    'attachs'         => !empty($PayIn->FileAttachPayInTwo1To->file)  ? $PayIn->FileAttachPayInTwo1To->file : '',
                                    'full_name'       => $certi_cb->FullRegName ?? '-',
                                    'url'             => $url.'certify/applicant-cb' ?? '-',
                                    'email'           =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                    'email_cc'        =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                    'email_reply'     => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                                ];

                        $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                                $certi_cb->id,
                                                                (new CertiCb)->getTable(),
                                                                $PayIn->id,
                                                                (new CertiCBPayInTwo)->getTable(),
                                                                3,
                                                                'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                                view('mail.CB.pay_in_two', $data_app),
                                                                $certi_cb->created_by,
                                                                $certi_cb->agent_id,
                                                                auth()->user()->getKey(),
                                                                !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                                $certi_cb->email,
                                                                !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                                !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                                !empty($PayIn->FileAttachPayInTwo1To->file) ?  'certify/check/file_cb_client/'.$PayIn->FileAttachPayInTwo1To->file.'/'.( !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name :   basename($PayIn->FileAttachPayInTwo1To->file) ) : null
                                                                );

                        $html = new CBPayInTwoMail($data_app);
                        $mail =  Mail::to($certi_cb->email)->send($html);
            
                        if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                        } 
                } 

 
                    
                    $certi_cb->status = 15;  //แจ้งรายละเอียดการชำระค่าใบรับรอง
                    $certi_cb->save();

                    $Report = CertiCBReport::where('app_certi_cb_id',$certi_cb->id)->orderby('id','desc')->first();
                    if(!is_null($Report)){
                      $Report->update(['status_alert' => 2]);
                   }

        return redirect('certify/check_certificate-cb/'.$certi_cb->token.'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
         }
    } catch (\Exception $e) {
        return redirect('certify/check_certificate-cb')->with('message_error', 'เกิดข้อผิดพลาดในการบันทึก');
    }
        
 }

        // สำหรับเพิ่มรูปไปที่ store
        public function storeFilePayin($setting_payment, $app_no = 'files_cb', $auditor_id = '')
        {
            $arrContextOptions=array();
            if($auditor_id != ''){
                $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no-$auditor_id";
            }else{
                $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no";
            }
            if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                $arrContextOptions["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                            );
            }


            $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));
            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
            if ($url_pdf) {
                $attach_path  =  $this->attach_path.$no;
                $fullFileName =  $no.'-'.date('Ymd_hms').'.pdf';
                $storagePath = Storage::put($attach_path.'/'.$fullFileName, $url_pdf);
                return  $no.'/'.$fullFileName;

            }else{
                return null;
            }
        }

  public function UpdatePayInTwo(Request $request, $id){
    $requestData = $request->all();
    $PayIn = CertiCBPayInTwo::findOrFail($id);
    $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
     try {
            $tb = new CertiCBPayInTwo;
            if($request->status_confirmed == 1){
                $requestData['degree'] = 3;
                if($certi_cb->standard_change == 1  || is_null($certi_cb->certificate_export_to2)){ // ขอใบรับรอง
                    $certi_cb->update([ 'status' =>17 ]);   // ยืนยันการชำระเงินค่าใบรับรอง
                }else{
                    $certi_cb->update([ 'status' =>18 ]);   // ออกใบรับรอง และ ลงนาม
                }

                // เงื่อนไขเช็คมีใบรับรอง 
                $this->save_certicb_export_mapreq( $certi_cb );
                
            }else{
                $requestData['degree'] = 1;
                $requestData['detail'] = $request->detail ?? null ;
                $certi_cb->update(['status' => 15]); //แจ้งรายละเอียดการชำระค่าใบรับรอง

            }
            $requestData['report_date'] = @$PayIn->report_date ?? null;
            $requestData['status']      = $request->status_confirmed ?? 2 ;
            $requestData['condition_pay'] =  !empty($request->condition_pay) ?  $request->condition_pay : null ; 
            $PayIn->update($requestData);

            if(!empty($request->ReceiptCreateDate)){
                $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiCBPayInTwo)->getTable())->orderby('id','desc')->first();
                if(!is_null($transaction_payin)){
                    $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                    $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                    $transaction_payin->save();
                }
            }



                    $data = CertiCBPayInTwo::select('report_date', 'amount', 'amount_fee', 'degree','status','detail')
                                    ->where('id',$id)
                                    ->first();
                    CertiCbHistory::create([
                                        'app_certi_cb_id'   => $PayIn->app_certi_cb_id ?? null,
                                        'system'            => 10,
                                        'table_name'        => $tb->getTable(),
                                        'ref_id'            => $PayIn->id,
                                        'status'            => $PayIn->status ?? null,
                                        'details_one'       =>  json_encode($data) ?? null,
                                        'attachs'           =>  !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                                        'attach_client_name'=>   !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name : null,
                                        'attachs_file'      =>  !empty($PayIn->FileAttachPayInTwo2To->file) ? $PayIn->FileAttachPayInTwo2To->file : null,
                                        'evidence'          =>   !empty($PayIn->FileAttachPayInTwo2To->file_client_name) ? $PayIn->FileAttachPayInTwo2To->file_client_name : null,
                                        'created_by'        =>  auth()->user()->runrecno
                                        ]);

            //Mail
            if(!is_null($certi_cb->email)  && $PayIn->status == 2 ){

                $config = HP::getConfig();
                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                $data_app =[
                            'PayIn'         => $PayIn,
                            'certi_cb'      => $certi_cb,
                            'attachs'       => !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                            'full_name'     => $certi_cb->FullRegName ?? '-',
                            'url'           => $url.'certify/applicant-cb' ?? '-',
                            'email'         =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'      =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                            'email_reply'   => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                            ];

                $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                        $certi_cb->id,
                                                        (new CertiCb)->getTable(),
                                                        $PayIn->id,
                                                        (new CertiCBPayInTwo)->getTable(),
                                                        3,
                                                        'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                        view('mail.CB.pay_in_two', $data_app),
                                                        $certi_cb->created_by,
                                                        $certi_cb->agent_id,
                                                        auth()->user()->getKey(),
                                                        !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                        $certi_cb->email,
                                                        !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                        !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                        !empty($PayIn->FileAttachPayInTwo1To->file) ?  'certify/check/file_cb_client/'.$PayIn->FileAttachPayInTwo1To->file.'/'.( !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name :   basename($PayIn->FileAttachPayInTwo1To->file) ) : null
                                                        );

                $html = new CBPayInTwoMail($data_app);
                $mail =  Mail::to($certi_cb->email)->send($html);
    
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                } 

               
                }
        if(isset($certi_cb->token)){
            // return redirect('certify/check_certificate-cb/'.$certi_cb->token)->with('flash_message', 'เรียบร้อยแล้ว');
            return redirect('certify/check_certificate-cb/'.$certi_cb->token.'/show/'.$certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
        }else{
            return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
        }
    } catch (\Exception $e) {
            return redirect('certify/check_certificate-cb')->with('message_error', 'เกิดข้อผิดพลาดในการบันทึก');
    }

 }

    public function UpdateAttacho(Request $request, $id){
                    $certi_cb = CertiCb::findOrFail($id);
                     // ประวัติการแนบไฟล์ แนบท้าย
                     if($request->attach  &&   $request->attach_pdf   &&  $request->hasFile('attach')   &&   $request->hasFile('attach_pdf') ){

                          CertiCBFileAll::where('app_certi_cb_id',$certi_cb->id)->update(['state' => 0]);
                          $certLabs   = CertiCBFileAll::create([
                                                                'app_certi_cb_id'       =>  $certi_cb->id,
                                                                'attach'                =>  $this->storeFile($request->attach,$certi_cb->app_no)?? '',
                                                                'attach_client_name'    =>   HP::ConvertCertifyFileName($request->attach->getClientOriginalName()) ?? '',
                                                                'attach_pdf'            =>   $this->storeFile($request->attach_pdf,$certi_cb->app_no) ?? '',
                                                                'attach_pdf_client_name'=>   HP::ConvertCertifyFileName($request->attach_pdf->getClientOriginalName()) ?? '',
                                                                'state'                 =>   1
                                                                ]);
                   }else{

                      if($request->state){
                          CertiCBFileAll::where('app_certi_cb_id',$certi_cb->id)->update(['state' => 0]);
                          $certLabs = CertiCBFileAll::findOrFail($request->state);
                          $certLabs->update(['state' => 1]);
                      }

                   }
                  if(isset($certi_cb->token)){
                        return redirect('certify/check_certificate-cb/'.$certi_cb->token)->with('flash_message', 'เรียบร้อยแล้ว');
                   }else{
                        return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
                  }
             }

  //แต่งตั้งคณะทบทวนฯ
    public function UpdateReview(Request $request, $id){
        // dd($request->all());
        $review = CertiCBReview::findOrFail($id);
        $certi_cb = CertiCb::findOrFail($review->app_certi_cb_id);
        // dd($review);
        if(!is_null($review)){
            $tb = new CertiCBReview;
             //คณะผู้ตรวจประเมิน
            if($request->evidence){
                $certi_cb_attach_more = new CertiCBAttachAll();
                $certi_cb_attach_more->app_certi_cb_id      = $review->app_certi_cb_id ?? null;
                $certi_cb_attach_more->ref_id               = $review->id;
                $certi_cb_attach_more->table_name           = $tb->getTable();
                $certi_cb_attach_more->file_section         = '1';
                $certi_cb_attach_more->file                 = $this->storeFile($request->evidence,$certi_cb->app_no);
                $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName(@$request->evidence->getClientOriginalName());
                $certi_cb_attach_more->token                = str_random(16);
                $certi_cb_attach_more->save();
            }
             //ผลการตรวจคณะผู้ตรวจประเมิน
             if($request->attach){
                $certi_cb_attach_more = new CertiCBAttachAll();
                $certi_cb_attach_more->app_certi_cb_id      = $review->app_certi_cb_id ?? null;
                $certi_cb_attach_more->ref_id               = $review->id;
                $certi_cb_attach_more->table_name           = $tb->getTable();
                $certi_cb_attach_more->file_section         = '2';
                $certi_cb_attach_more->file                 = $this->storeFile($request->attach,$certi_cb->app_no);
                $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName(@$request->attach->getClientOriginalName());
                $certi_cb_attach_more->token                = str_random(16);
                $certi_cb_attach_more->save();
            }
            $review->review = isset($request->review) ? 2: 1;
            $review->save();

             CertiCbHistory::create([
                                    'app_certi_cb_id'       => $review->app_certi_cb_id ?? null,
                                    'system'                => 11,
                                    'table_name'            => $tb->getTable(),
                                    'ref_id'                => $review->id,
                                    'details_one'           => $review->review  ?? null,
                                    'file'                  => !empty($review->FileReview1->file) ? $review->FileReview1->file : null,
                                    'file_client_name'      => !empty($review->FileReview1->file_client_name) ? $review->FileReview1->file_client_name : null,
                                    'attachs'               =>  !empty($review->FileReview2->file) ? $review->FileReview2->file : null,
                                    'attach_client_name'    =>  !empty($review->FileReview2->file_client_name) ? $review->FileReview2->file_client_name : null,
                                    'created_by'            =>  auth()->user()->runrecno
                                  ]);

            if($review->review == 2 && !is_null($review->FileReview1) && !is_null($review->FileReview2)){
                if(!is_null($certi_cb)){
                   $certi_cb->update(['review' => 2,'status' => 12]);  // สรุปรายงานและเสนออนุกรรมการฯ
                   $report = new CertiCBReport;  //สรุปรายงานและเสนออนุกรรมการฯ
                   $report->app_certi_cb_id =  $certi_cb->id;
                   $report->review_approve = "1";
                   $report->save();
                }
            }
        }
        // dd($certi_cb->id);
        if(isset($certi_cb->token)){
            return redirect('certify/check_certificate-cb/'.$certi_cb->token. '/show/'. $certi_cb->id)->with('flash_message', 'เรียบร้อยแล้ว');
        }else{
                return redirect('certify/check_certificate-cb')->with('flash_message', 'เรียบร้อยแล้ว');
        }
   }




    public function certificate_detail($token = null)
    {
        $certi_cb_primary = CertiCb::where('token',$token)->firstOrfail();
                // dd($certi_cb_primary);
        if(!empty($certi_cb_primary->certi_cb_export_mapreq_to)){
             $certi_cb_mapreq = CertiCbExportMapreq::where('certificate_exports_id', $certi_cb_primary->certi_cb_export_mapreq_to->certificate_exports_id)->orderBy('id')->firstOrfail();
             if(!empty($certi_cb_mapreq->app_certi_cb_to)){
                 $certi_cb = $certi_cb_mapreq->app_certi_cb_to;
             }
        }
        if(!empty($certi_cb->certi_cb_export_mapreq_to)){
        $export               =  $certi_cb->app_certi_cb_export;
        $cert_cbs_file_all    =  !empty($export->CertiCbTo->cert_ibs_file_all_order_desc) ?  $export->CertiCbTo->cert_ibs_file_all_order_desc : []; 
         // ใบรับรอง และ ขอบข่าย    
         if(!is_null($certi_cb->certi_cb_export_mapreq_to)){
            $certificate =  !empty($certi_cb->certi_cb_export_mapreq_to->app_certi_cb_export_to->certificate) ? $certi_cb->certi_cb_export_mapreq_to->app_certi_cb_export_to->certificate : null;
            if(!is_null($certificate)){
                     $export_no         =  CertiCBExport::where('certificate',$certificate);
                    if(count($export_no->get()) > 0){

                      $ib_ids = [];
                      if($export_no->pluck('app_certi_cb_id')->count() > 0){
                          foreach ($export_no->pluck('app_certi_cb_id') as $item) {
                              if(!in_array($item,$ib_ids)){
                                 $ib_ids[] =  $item;
                              }
                          }
                      }

                      if($certi_cb->certi_cb_export_mapreq_to->certicb_export_mapreq_group_many->count() > 0){
                          foreach ($certi_cb->certi_cb_export_mapreq_to->certicb_export_mapreq_group_many->pluck('app_certi_cb_id') as $item) {
                              if(!in_array($item,$ib_ids)){
                                  $ib_ids[] =  $item;
                              }
                          }
                      }

                      // ขอบข่าย
                      $file_alls =  CertiCBFileAll::whereIn('app_certi_cb_id',$ib_ids)->whereNotIn('status_cancel',[1])->get();
                      if(count($file_alls) > 0){
                          $cert_cbs_file_all =  $file_alls;
                      }
              
                 } 
            }
         }
      }


        return view('certify/cb/check_certificate_cb.certificate_detail', compact('certi_cb','cert_cbs_file_all', 'certi_cb_primary' ));
    }


    public function update_document(Request $request)
    {
        $certi_cb = CertiCb::where('id',$request->app_certi_cb_id)->first();
        $attach_path            =  $this->attach_path;
        if(!is_null($certi_cb)){

            CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->update(['state' => 0]);
            $requestData = $request->all();
            $requestData['start_date']           =  HP::convertDate($request->start_date,true) ?? null;
            $requestData['end_date']             =  HP::convertDate($request->end_date,true) ?? null;
            if( $request->hasFile('file_word') ){
                $requestData['attach_client_name'] =  HP::ConvertCertifyFileName($request->file_word->getClientOriginalName());
                $requestData['attach']             = self::storeFile($request->file_word , $request->app_no );
            }

            if( $request->hasFile('file_pdf') ){
                $requestData['attach_pdf_client_name'] =  HP::ConvertCertifyFileName($request->file_pdf->getClientOriginalName());
                $requestData['attach_pdf']             = self::storeFile($request->file_pdf , $request->app_no );
            }
            
            $requestData['state']              = 1;
            $requestData['app_no']             = $request->app_no;
            $requestData['app_certi_cb_id']   = $certi_cb->id;
            $requestData['ref_table']          = (new CertiCb)->getTable();
            $requestData['ref_id']             = $request->ref_id;

             // ประวัติการแนบไฟล์ แนบท้าย
             if(  $request->form == 'create'){
                    $certCbs   = CertiCBFileAll::create($requestData);

                    $certi_cb->status       =   '20';
                    $certi_cb->save();

                    $obj = new stdClass;
                    $obj->id                =  $certCbs->id;
                    $obj->app_no            =  $certCbs->app_no; 
                    $obj->file_word         =   !empty($certCbs->attach)   ?  HP::getFileStorage($attach_path.$certCbs->attach)    : '';
                    $obj->file_pdf          =   !empty($certCbs->attach_pdf)   ?  HP::getFileStorage($attach_path.$certCbs->attach_pdf)    : '';
                    $obj->start_date        =   !empty($certCbs->start_date)   ? HP::revertDate($certCbs->start_date,true) : '';
                    $obj->start_date_th     =   !empty($certCbs->start_date)   ? HP::DateThai($certCbs->start_date) : '';
                    $obj->end_date          =   !empty($certCbs->end_date)   ? HP::revertDate($certCbs->end_date,true) : '';
                    $obj->end_date_th       =   !empty($certCbs->end_date)   ? HP::DateThai($certCbs->end_date) : '';
                    $obj->created_at        =   !empty($certCbs->created_at)   ? HP::DateThai($certCbs->created_at) : '';
                    $obj->state             =   !empty($certCbs->state)   ? $certCbs->state : '';
                    return response()->json( $obj );
            }else{
                    $certCbs   = CertiCBFileAll::findOrFail($request->id);
                    $certCbs->update($requestData);

                    $certi_cb->status               =   '20';
                    $certi_cb->save();

                    $datas = [];
                    $alls =  CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->whereNotIn('status_cancel',[1])->get();
                    if(count($alls) > 0){
                        foreach ($alls as $index => $item){ 
                            $obj = new stdClass;
                            $obj->id                =  $item->id;
                            $obj->app_no            =  $item->app_no; 
                            $obj->file_word         =   !empty($item->attach)   ?  HP::getFileStorage($attach_path.$item->attach)    : '';
                            $obj->file_pdf          =   !empty($item->attach_pdf)   ?  HP::getFileStorage($attach_path.$item->attach_pdf)    : '';
                            $obj->start_date        =   !empty($item->start_date)   ? HP::revertDate($item->start_date,true) : '';
                            $obj->start_date_th     =   !empty($item->start_date)   ? HP::DateThai($item->start_date) : '';
                            $obj->end_date          =   !empty($item->end_date)   ? HP::revertDate($item->end_date,true) : '';
                            $obj->end_date_th       =   !empty($item->end_date)   ? HP::DateThai($item->end_date) : '';
                            $obj->created_at        =   !empty($item->created_at)   ? HP::DateThai($item->created_at) : '';
                            $obj->state             =   !empty($item->state)   ? $item->state : '';
                            $datas[]                = $obj;
                        }
            
                    }
                    return response()->json([
                        'datas'      => $datas
                    ]);
            }

 

      
        }

     

    }


    
    public function del_attach(Request $request)
    { 
 
        $requestData =  [];
        $requestData['status_cancel']           =  1;
        $requestData['created_cancel']          =   auth()->user()->getKey();
        $requestData['date_cancel']             =    date('Y-m-d H:i:s');
        $result  =  CertLabsFileAll::findOrFail($request->id);
        if($result) {
            $result->update($requestData);
            return 'success';
        } else {
            return "not success";
        }
 

    }




   public function check_pay_in_cb(Request $request)
   {
    
        $arrContextOptions = array();
           $id =   $request->input('id');
           $payin =   $request->input('payin');

       if($payin == '1'){ // pay in ครั้งที่ 1
                $pay_in = CertiCBPayInOne::findOrFail($id);

                // dd($pay_in);
            if(!is_null($pay_in)){

                $pay_in->start_date     =  isset($request->start_date)?  $request->start_date : null;
                $pay_in->amount         =  isset($request->amount)?str_replace(",","",$request->amount):  null;
                $pay_in->amount_bill    =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):  null;
                $pay_in->save();

                $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',1)->where('type',1)->first();
                $certi_cb = CertiCb::findOrFail($pay_in->app_certi_cb_id);
                $app_no =  $certi_cb->app_no;

                $app_no =  $certi_cb->app_no;
                $data_ref1 = $app_no.'-'.$pay_in->auditors_id;

                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
                
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$data_ref1", false, stream_context_create($arrContextOptions));

              

                $api = json_decode($content);
                // dd($api);
                if(!is_null($api) && $api->returnCode != '000'){
                    // $pay_in->amount =  null;
                    // $pay_in->amount_bill = null;
                    // $pay_in->start_date = null;
                    // $pay_in->save();
                    return response()->json([
                                             'message'      =>  false,
                                             'status_error' => HP::getErrorCode($api->returnCode)
                                            ]);
                }else{
                    return response()->json([
                                             'message' =>  true
                                            ]);
                }

            }else{
                     return response()->json([
                                             'message' =>  true
                                            ]);
            }
       }else{
            $pay_in = CertiCBPayInTwo::findOrFail($id);
            $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',2)->where('type',1)->first();

            // dd($setting_payment);

            if(!is_null($setting_payment)){
                $certi_cb = CertiCb::findOrFail($pay_in->app_certi_cb_id);
                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false,
                                    );
                }
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$certi_cb->app_no", false, stream_context_create($arrContextOptions));

                    $api = json_decode($content);
                    // dd($api);
                    if(!is_null($api) && $api->returnCode != '000'){
                        return response()->json([
                                                'message'      =>  false,
                                                'status_error' => HP::getErrorCode($api->returnCode)
                                                ]);
                    }else{
                        return response()->json([
                                                'message' =>  true
                                                ]);
                    }
            }else{
                return response()->json([
                                        'message' =>  true
                                        ]);
            }
       }
   }


   public function check_api_pid(Request $request)
   {
        $data  =  CertiCb::findOrFail($request->id);
        return response()->json([
                                'message' =>  HP_API_PID::CheckDataApiPid($data,(new CertiCb)->getTable())   
                                 ]);
   }

   public function insert_payin_all($type, $item)
   {
 
       if($type == 1){ // lab

                if(!empty($item->CertiCbCostTo) && !is_null($item->conditional_type)){
                    $app = $item->CertiCbCostTo;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiCBPayInOne)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CertiCBPayInOne)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = !empty($item->amount) ?  $item->amount: null ;
                    $pay_in->start_date             = !empty($item->start_date) ?  $item->start_date: null ;
                    $pay_in->detail                 = !empty($item->detail) ?  $item->detail: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->name_standard) ?  $app->name_standard: null ;
                    $pay_in->auditors_name          = !empty($item->CertiCBAuditorsTo->auditor) ?  $item->CertiCBAuditorsTo->auditor : null ;
                    $pay_in->certify                = 3;
                    $pay_in->state                  = 1;
                    $pay_in->created_by             = !empty($item->reporter_id) ?  $item->reporter_id: null ; 
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
                    if( !empty($item->FileAttachPayInOne1To) ){
                        $attach = [];
                        $attach_file  = $item->FileAttachPayInOne1To;
                        $file               =   basename($attach_file->file);
                        $attach['url']            =   'certify/check/file_client/'.$attach_file->file  ;
                        $attach['new_filename']   =  $file;
                        $attach['filename']       =  $attach_file->file_client_name;
                        $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                    }
                    $pay_in->save();  
                    echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                    echo '<br/>';
                }

       }else{ 
                 if(!empty($item->CertiCbCostTo) && !is_null($item->conditional_type)){
                    $app = $item->CertiCbCostTo;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiCBPayInTwo)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CertiCBPayInTwo)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = null ;
                    $pay_in->start_date             = !empty($item->report_date) ?  $item->report_date: null ;
                    $pay_in->detail                 = !empty($item->detail) ?  $item->detail: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->name_standard) ?  $app->name_standard: null ;
                    $pay_in->auditors_name          =  null ;
                    $pay_in->certify                = 3;
                    $pay_in->state                  = 2;
                    $pay_in->created_by             = !empty($item->created_by) ?  $item->created_by: null ; 
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
                    if( !empty($item->FileAttachPayInTwo1To) ){
                        $attach = [];
                        $attach_file  = $item->FileAttachPayInTwo1To;
                        $file               =   basename($attach_file->file);
                        $attach['url']            =   'certify/check/file_client/'.$attach_file->file  ;
                        $attach['new_filename']   =  $file;
                        $attach['filename']       =  $attach_file->file_client_name;
                        $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                    }
                    $pay_in->save();  
                    echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                    echo '<br/>';
                }
       } 
       
   }

   private function save_certicb_export_mapreq($certi_cb)
   {
         $app_certi_cb             = CertiCb::with([
                                                   'app_certi_cb_export' => function($q){
                                                       $q->whereIn('status',['0','1','2','3','4']);
                                                   }
                                               ])
                                               ->where('created_by', $certi_cb->created_by)
                                               ->whereNotIn('status', ['0','4'])
                                               ->where('type_standard', $certi_cb->type_standard)
                                               ->first();
        if(!Is_null($app_certi_cb)){
            $certificate_exports_id = !empty($app_certi_cb->app_certi_cb_export->id) ? $app_certi_cb->app_certi_cb_export->id : null;
             if(!Is_null($certificate_exports_id)){
                      $mapreq =  CertiCbExportMapreq::where('app_certi_cb_id',$certi_cb->id)->where('certificate_exports_id', $certificate_exports_id)->first();
                      if(Is_null($mapreq)){
                          $mapreq = new CertiCbExportMapreq;
                      }
                      $mapreq->app_certi_cb_id       = $certi_cb->id;
                      $mapreq->certificate_exports_id = $certificate_exports_id;
                      $mapreq->save();
             }
        }
   }
   
   public function update_delete(Request $request)
   {
       $certi_cb = CertiCb::findOrFail($request->input('del_id'));
       if(!empty($certi_cb)){
           $request->request->add(['deleted_by' => @auth()->user()->getKey()]);
           $request->request->add(['deleted_at' => date('Y-m-d h:i:s')]);
           $request->request->add(['status' => 4]);
           $certi_cb->update($request->all());
           $attach_files_del_names = $request->get('attach_files_del_name');
           if(!empty($attach_files_del_names) && count($attach_files_del_names) > 0){
               foreach( $attach_files_del_names as $key=>$file ){
                   if($request->hasFile("attach_files_del.{$key}")){
                       HP::singleLabCancalFileUpload(
                           $request->file("attach_files_del.{$key}"),
                           $this->attach_path,
                           $certi_cb,
                           $request->input("attach_files_del_name.{$key}")
                       );
                   }
               }
           }
       }
       return redirect()->back();
   }
   
}
