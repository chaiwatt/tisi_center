<?php

namespace App\Http\Controllers\Certify\IB;

use DB;
use HP;

use PDF;
use File;
use Storage;
use App\User;
use stdClass;
use HP_API_PID;
use Carbon\Carbon;
use App\Http\Requests;
use App\CheckCertificateIB;
use App\IpaymentCompanycode;
use Illuminate\Http\Request;

use App\Mail\IB\IBReportMail;
use App\Mail\IB\IBRequestMail;
use App\Mail\IB\IBPayInOneMail;
use App\Mail\IB\IBPayInTwoMail;
use App\Models\Basic\Feewaiver;
use App\Mail\IB\IBDocumentsMail;
use App\Models\Certify\PayInAll;
use App\Mail\IB\IBInformPayInOne;
use App\Mail\IB\IBAssignStaffMail;
use App\Http\Controllers\Controller;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Support\Facades\Mail;
use App\Mail\IB\IBScopeEditAlertMail;
use App\Models\Certify\TransactionPayIn;

use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certificate\IbScopeTransaction;
use App\Models\Certify\ApplicantIB\CertiIBCheck;
use App\Models\Certify\ApplicantIB\CertiIBExport;

use App\Models\Certify\ApplicantIB\CertiIBReport;
use App\Models\Certify\ApplicantIB\CertiIBReview;
use App\Models\Certify\ApplicantIB\CertiIBStatus;
use App\Models\Certify\ApplicantIB\CertiIbHistory;
use App\Models\Certify\ApplicantIB\CertiIBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBFileAll; 
use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
use App\Models\Certify\ApplicantIB\CertiIBPayInTwo;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certify\ApplicantIB\CertiIbExportMapreq;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessmentBug;
use App\Models\Certify\ApplicantIB\CertiIBCost; // ประมาณการค่าใช้จ่าย

class CheckCertificateIBController extends Controller
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
      
        $model = str_slug('checkcertificateib','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['filter_type_unit'] = $request->get('filter_type_unit', '');
            $filter['filter_inspector'] = $request->get('filter_inspector', '');
            $filter['filter_name'] = $request->get('filter_name', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiIb;
            $Query = $Query->select('app_certi_ib.*')->where('status','>=','1');

            if ($filter['filter_status']!='') {
                if ($filter['filter_status'] == "25") {
                    $Query = $Query->where('review',1);
                }else{
                    $Query = $Query->where('status', $filter['filter_status']);
                }
            }

            if ($filter['filter_search'] != '') {
                $Query = $Query->where(function ($query) use ($filter) {
                    $search = str_replace(' ', '', $filter['filter_search']);
                    $query->where(DB::raw("REPLACE(name,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(name_unit,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'like', '%'.$search.'%')
                            ->OrWhere(DB::raw("REPLACE(app_no,' ','')"), 'like', '%'.$search.'%');
                });
            }

            if ($filter['filter_type_unit']!='') {
                    $Query = $Query->where('type_unit', $filter['filter_type_unit']);
            }

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = HP::convertDate($filter['filter_start_date'],true);
                $end = HP::convertDate($filter['filter_end_date'],true);
                $Query = $Query->whereBetween('created_at', [$start,$end]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start =  HP::convertDate($filter['filter_start_date'],true);
                $Query = $Query->whereDate('created_at',$start);
            }

            if ($filter['filter_inspector']!='') { // เจ้าหน้าที่ตรวจสอบ
                $Query = $Query->whereHas('certi_ib_checks', function ($query) use ($filter) {
                    $query->where('user_id', $filter['filter_inspector']);
                });
            }

            if ($filter['filter_name']!='') { // หน่วยงาน
                $Query = $Query->where(function ($query) use ($filter) {
                    $key = str_replace(' ', '', $filter['filter_name']);
                    $query->where(DB::raw("REPLACE(name,' ','')"), 'like', '%'.$key.'%');
                });
            }

             //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_ib_check','app_certi_ib_check.app_certi_ib_id','=','app_certi_ib.id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }

            $certiIbs =  $Query->orderby('id','desc')
                               ->sortable()
                                ->paginate($filter['perPage']);

             $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                            ->whereIn('reg_subdepart',[1802])
                            ->orderbyRaw('CONVERT(title USING tis620)')
                            ->pluck('title','runrecno');

            $status  = CertiIBStatus::select('title','id')->whereNotIn('id',[0])->pluck('title','id');


            return view('certify/ib/check_certificate_ib.index', compact('certiIbs','filter','select_users','status'));
        }
        abort(403);

    }

    public function assign(Request $request)
    {
        $checker = $request->input('checker');
        $apps = $request->input('apps');
        $user =   User::where('runrecno',auth()->user()->runrecno)->first();
        if (count($checker) > 0  && count($apps) != 0) {
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
            $CertiIb =  CertiIb::select('app_no','name','status','token')->whereIN('id',$apps)->get();

            foreach ($apps as $app_id) {
                   $app = CertiIb::find($app_id);
                if ($app){
                     // เช็คคำขอมอบหมายให้เจ้าหน้าที่หรือยัง
                    if($app->status == 1){
                       $app->update(['status'=> 2]);
                    }
                    $examiner = $this->save_check_examiner($checker,$app_id);
                    if(count($reg_email) > 0){
                       $data_app = [ 'apps'      =>  $app ?? null,
                                     'email'      => auth()->user()->reg_email ?? 'admin@admin.com',
                                     'reg_fname'  => (count($reg_fname) > 0) ? implode(", ",$reg_fname) : null
                                  ];
                   
                         $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiIb)->getTable(),
                                                                    $examiner->id,
                                                                    (new CertiIBCheck)->getTable(),
                                                                    2,
                                                                    'ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถหน่วยตรวจ',
                                                                    view('mail.IB.assign_staff', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    auth()->user()->reg_email ?? null,
                                                                    implode(',',(array)$reg_email),
                                                                    null,
                                                                    null,
                                                                    null
                                                                 );

                           $html = new IBAssignStaffMail($data_app);
                           $mail =  Mail::to($reg_email)->send($html);
                
                           if(is_null($mail) && !empty($log_email)){
                               HP::getUpdateCertifyLogEmail($log_email->id);
                           }
 
                      }
                }
            }


          }
             return redirect('certify/check_certificate-ib')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว');

    }


    private function save_check_examiner($checker, $id){
        CertiIBCheck::where('app_certi_ib_id', $id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['app_certi_ib_id'] = $id;
          $input['user_id'] = $item;
          $input['created_by'] = auth()->user()->runrecno;
          $examiner =CertiIBCheck::create($input);
        }
        return $examiner;
      }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */

    public function showCertificateIbDetail($certiIb)
    {
        // dd('ok');
        $previousUrl = app('url')->previous();
        $certi_ib = CertiIb::where('app_no',$certiIb)->first();
        $tis_data = SSO_User::find($certi_ib->created_by);
        $ibScopeTransactions = IbScopeTransaction::where('certi_ib_id', $certi_ib->id)
        ->with([
            'ibMainCategoryScope',
            'ibSubCategoryScope',
            'ibScopeTopic',
            'ibScopeDetail'
        ])
        ->get();

        return view('certify/ib/check_certificate_ib.detail', compact('certi_ib','previousUrl','tis_data','ibScopeTransactions'));
    }

    public function show($token)
    {

        $model = str_slug('checkcertificateib','-');
        if(auth()->user()->can('view-'.$model)) {
            $certi_ib = CertiIb::where('token',$token)->first();
            if(!is_null($certi_ib)){
                    // ประวัติคำขอ
            $history  =  CertiIbHistory::where('app_certi_ib_id',$certi_ib->id)
                                        ->orderby('id','desc')
                                        ->get();

             return view('certify/ib/check_certificate_ib.show', compact('certi_ib','history'));
            }else{
                abort(403);
            }
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {
      
        $model = str_slug('checkcertificateib','-');
        if(auth()->user()->can('edit-'.$model)) {

 try {

            $request->validate([
                'status' => 'required|in:2,3,4,5,6,7,9,15,27',
            ]);
            $tb = new CertiIb;
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $user =   User::where('runrecno',auth()->user()->runrecno)->first();

            $requestData = $request->all();
            $requestData['save_date']   =  $request->save_date ? HP::convertDate($request->save_date,true) : null;
            $certi_ib = CertiIb::findOrFail($id);

            $status = $request->status;
            //dd($status);
       // status = 2,3,5,6
        if (in_array($status, ['3','4','5'])) { // 3. ขอเอกสารเพิ่มเติม 4. ยกเลิกคำขอ 5. ไม่ผ่านการตรวจสอบ
            if($status == 3){
                $section = 9; // ขอเอกสารเพิ่มเติม
                $system = 1;
                $requestData['details']     =  $request->desc ?? null;
            }else if($status == 4){
                $section = 8;  // ยกเลิกคำขอ
                $system = 2;
                $requestData['details']     =  null;
                $requestData['desc_delete'] =  $request->desc ?? null;
            }else if($status == 5){
                $section = 10; // ไม่ผ่านการตรวจสอบ
                $system = 3;
                $requestData['details']     =  null;
                $requestData['desc_delete'] =  $request->desc ?? null;
            }

           $certi_ib->update($requestData);

            if ($request->hasFile('file')) {
                $attachs = [];
                foreach ($request->file as $index => $item){
                    $certi_ib_attach_more                   = new CertiIBAttachAll();
                    $certi_ib_attach_more->app_certi_ib_id  = $certi_ib->id;
                    $certi_ib_attach_more->table_name       = $tb->getTable();
                    $certi_ib_attach_more->file_section     = $section ?? null;
                    $certi_ib_attach_more->file             = $this->storeFile($item,$certi_ib->app_no);
                    $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                    $certi_ib_attach_more->token            = str_random(16);
                    $certi_ib_attach_more->save();

                    $list                   = new  stdClass;
                    $list->file_desc        = $certi_ib_attach_more->file_desc ;
                    $list->file             = $certi_ib_attach_more->file ;
                    $list->file_client_name = $certi_ib_attach_more->file_client_name ;
                    $list->attach_path      = $this->attach_path ;
                    $attachs[]              = $list;
                }
            }
             // log
            CertiIbHistory::create([
                                    'app_certi_ib_id'   => $certi_ib->id ?? null,
                                    'system'            => isset($system) ? $system : null,
                                    'table_name'        => $tb->getTable(),
                                    'status'            => $certi_ib->status ?? null,
                                    'ref_id'            => $certi_ib->id,
                                    'details_one'       => $certi_ib->details ?? null,
                                    'details_two'       => $certi_ib->desc_delete ?? null,
                                    'attachs'           => isset($attachs) ?  json_encode($attachs) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                                  ]);
        // mail
        $title_status =  ['3'=>'ขอเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
        $data_status =  ['3'=>'แนบเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
        if(!is_null($certi_ib->email)){

            $data_app = [  'certi_ib'    => $certi_ib ?? '-',
                            'desc'        => $certi_ib->details ?? '-',
                            'status'      => $status,
                            'title'       => array_key_exists($status,$title_status) ?$title_status[$status] : null,
                            'data'        => array_key_exists($status,$data_status) ?$data_status[$status] : null,
                            'name'        => !empty($certi_ib->name)  ?   $certi_ib->name  : '-',
                            'attachs'     => isset($attachs) ?  $attachs   : '-',
                            'url'         => $url.'certify/applicant-ib' ?? '-',
                            'email'       => !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                            'email_cc'    => !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                            'email_reply' => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                         ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            2,
                                                            $title_status[$status]  ?? null,
                                                            view('mail.IB.documents', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new IBDocumentsMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
         }

     }
            if (in_array($status, ['6'])) {
                $requestData['app_no'] =  isset($certi_ib->app_no) ?  str_replace("RQ-","",$certi_ib->app_no) : @$certi_ib->app_no;
                $requestData['get_date'] =   date('Y-m-d h:m:s');
                $certi_ib->update($requestData);
                if($certi_ib && !is_null($certi_ib->email)){
                    $cost = CertiIBCost::where('app_certi_ib_id',$certi_ib->id)->first();
                    if(is_null($cost)){
                        $cost = new CertiIBCost;
                        $cost->app_certi_ib_id = $certi_ib->id;
                        $cost->draft = 0;
                        $cost->created_by = auth()->user()->runrecno;
                        $cost->save();
                    }
                    $data_app = [
                                'certi_ib'    => $certi_ib,
                                'url'         => $url.'certify/applicant-ib' ?? '-',
                                'email'       => !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'    => !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                                'email_reply' => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                            ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            2,
                                                            'รับคำขอรับบริการ',
                                                            view('mail.IB.request', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new IBRequestMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
                 }

            }

            if(isset($certi_ib->token)){
                return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
            }else{
                return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
            }
  } catch (\Exception $e) {
            return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
 } 
        }

        abort(403);

    }


    public function copyScopeIbFromAttachement($certiIbId)
    {
        $copiedScoped = null;
        $fileSection = null;
    
        $app = CertiIb::find($certiIbId);
    
        $latestRecord = CertiIBAttachAll::where('app_certi_ib_id', $certiIbId)
        ->where('file_section', 3)
        ->where('table_name', 'app_certi_ib')
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();
    
        $existingFilePath = 'files/applicants/check_files_ib/' . $latestRecord->file ;
    
        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files_ib/'.$no.'/';
    
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

    public function GetIBPayInOne($id = null,$token = null)
    {
         
        $previousUrl = app('url')->previous();
           $pay_in  =  CertiIBPayInOne::findOrFail($id);

            // dd($pay_in);

             if(is_null($pay_in->conditional_type) && !empty($pay_in->CertiIBAuditorsTo->StartDateBoardAuditor)){
                  $start_date      = $pay_in->CertiIBAuditorsTo->StartDateBoardAuditor->start_date ?? null;
                  $feewaiver  =  Feewaiver::where('certify',2)
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


           return view('certify.ib.check_certificate_ib.pay_in_one',  compact('previousUrl',
                                                                               'pay_in',
                                                                               'feewaiver'
                                                                             ));
    }
    public function CertiIBPayInOne(Request $request, $id){
     
        $arrContextOptions = array();

         $attach_path =  $this->attach_path ;
try {
         $tb = new CertiIBPayInOne;
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
         $PayIn = CertiIBPayInOne::findOrFail($id);
         $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);

        if(!is_null($PayIn)){
           
        if($PayIn->state == null){
                    $PayIn->conditional_type = $request->conditional_type;
                    $PayIn->created_by          =  auth()->user()->runrecno;
                    $PayIn->state = 1;  // ส่งให้ ผปก.
                    $PayIn->start_date =   isset($request->start_date)?  HP::convertDate($request->start_date,true) : @$PayIn->start_date;
                    $PayIn->amount =      isset($request->amount)?str_replace(",","",$request->amount): @$PayIn->amount;
                    $PayIn->amount_bill =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):@$PayIn->amount_bill;
                  
                    $PayIn->save();

            if($PayIn->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม
               

                $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',1)->where('type',1)->first();



                if(!is_null($setting_payment)){ // เรียกเก็บค่าธรรมเนียม
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                        "verify_peer" => false,
                                                        "verify_peer_name" => false,
                                                    );
                    }

                    $app_no          =  $certi_ib->app_no;
                    $timestamp = Carbon::now()->timestamp;
                    $refNo = $app_no.'-'.$PayIn->auditors_id.''.$timestamp;

                    // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$certi_ib->app_no-$PayIn->auditors_id", false, stream_context_create($arrContextOptions));
                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));

                    $api = json_decode($content);

                    $certi_ib_attach_more = new CertiIBAttachAll();
                    $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                    $certi_ib_attach_more->table_name           = $tb->getTable();
                    $certi_ib_attach_more->ref_id               = $PayIn->id;
                    $certi_ib_attach_more->file_section         = '1';
                    $certi_ib_attach_more->file_desc            = 'เรียกเก็บค่าธรรมเนียม';


                    // if(strpos($setting_payment->data, '127.0.0.1')===0){
                    // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {  
                    $host = parse_url($setting_payment->data, PHP_URL_HOST);

                    if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {
                        $certi_ib_attach_more->file                 =  $this->storeFilePayin($setting_payment,$certi_ib->app_no,$PayIn->auditors_id);
                    }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                        
                        $certi_ib_attach_more->file =   $this->storeFilePayinDemo($setting_payment,$certi_ib->app_no,$PayIn->auditors_id);
                    }


                    $certi_ib_attach_more->file_client_name     =  isset($certi_ib_attach_more->file) ? basename($certi_ib_attach_more->file)  : null;
                    $certi_ib_attach_more->token                =  str_random(16);
                    $certi_ib_attach_more->save();
    
                    $transaction = HP::TransactionPayIn1($PayIn->id,$tb->getTable(),'2','1',$api,$app_no.'-'.$PayIn->auditors_id,$timestamp);

                    // $transaction = HP::TransactionPayIn1($PayIn->id,$tb->getTable(),'2','1',$api,$certi_ib->app_no.'-'.$PayIn->auditors_id);

                     $file =  $PayIn->FileAttachPayInOne1To->file ?? null;
                     if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                         HP::getFileStoragePath($attach_path.$file);
                     }
               }
            }else  if($PayIn->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                    $feewaiver  =  Feewaiver::where('certify',2)->first();
                    if(!is_null($feewaiver->payin1_file)){
                        $certi_ib_attach_more = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                        $certi_ib_attach_more->table_name           = $tb->getTable();
                        $certi_ib_attach_more->ref_id               = $PayIn->id;
                        $certi_ib_attach_more->file_section         = '1';
                        $certi_ib_attach_more->file_desc            = 'ยกเว้นค่าธรรมเนียม';
                        $certi_ib_attach_more->file                 =  $feewaiver->payin1_file;
                        $certi_ib_attach_more->file_client_name     =  $feewaiver->payin1_file_client_name;
                        $certi_ib_attach_more->token                =  str_random(16);
                        $certi_ib_attach_more->save();

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
                        $certi_ib_attach_more = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                        $certi_ib_attach_more->table_name           = $tb->getTable();
                        $certi_ib_attach_more->ref_id               = $PayIn->id;
                        $certi_ib_attach_more->file_section         = '1';
                        $certi_ib_attach_more->file_desc            = 'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม';
                        $certi_ib_attach_more->file                 =   $this->storeFile($request->attach,$certi_ib->app_no);
                        $certi_ib_attach_more->file_client_name     =  HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                        $certi_ib_attach_more->token                =  str_random(16);
                        $certi_ib_attach_more->save();
                        $file =  $PayIn->FileAttachPayInOne1To->file ?? null;
                        if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                            HP::getFileStoragePath($attach_path.$file);
                        }
                }
            }
                self::insert_payin_all(1,$PayIn);
                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiIBAuditors::findOrFail($PayIn->auditors_id);
                if(!is_null($auditor)){
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                    $auditor->save();
                }

                // LOG
                $data = CertiIBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id', 'auditors_id')
                            ->where('id',$id)
                            ->first();
                CertiIbHistory::create([
                                        'app_certi_ib_id'   =>  $PayIn->app_certi_ib_id ?? null,
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
                                        'created_by'        =>  auth()->user()->runrecno
                                        ]);
                // Mail
                if(!is_null($certi_ib->email)){ // แจ้งเตือนผู้ประกอบการ

                   $data_app = [
                                'PayIn'        => $PayIn,
                                'certi_ib'     => $certi_ib,
                                'attachs'      => !empty($PayIn->FileAttachPayInOne1To->file) ? $PayIn->FileAttachPayInOne1To->file : '',
                                'url'          => $url.'certify/applicant-ib',
                                'email'        =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'     =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                'email_reply'  => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                            ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no.'-'.$PayIn->auditors_id,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiIBPayInOne)->getTable(),
                                                            2,
                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                            view('mail.IB.pay_in_one', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            !empty($PayIn->FileAttachPayInOne1To->file) ?  'certify/check/file_ib_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :   basename($PayIn->FileAttachPayInOne1To->file) ) : null
                                                            );

                    $html = new IBPayInOneMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
  
                }
                // $certi_ib->status = 12;// แจ้งรายละเอียดค่าตรวจประเมิน
                // $certi_ib->save();
        }else{
            
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
                  $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiIBPayInOne)->getTable())->orderby('id','desc')->first();
                  if(!is_null($transaction_payin)){
                      $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                      $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                      $transaction_payin->save();
                  }
              }
  
              

                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiIBAuditors::findOrFail($PayIn->auditors_id);
             if(!is_null($auditor)){
                if($PayIn->state == 3){
                    $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                }else{
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                }
                   $auditor->save();
             }
             
                // LOG
                $data = CertiIBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id', 'auditors_id','condition_pay')
                            ->where('id',$id)
                            ->first();

              CertiIbHistory::create([
                                        'app_certi_ib_id'   =>  $PayIn->app_certi_ib_id ?? null,
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

      if(!is_null($certi_ib->email)){
            if($PayIn->state == 1){  // แจ้งเตือนผู้ประกอบการ
                   $data_app = [
                                'PayIn'        => $PayIn,
                                'certi_ib'     => $certi_ib,
                                'attachs'      => !empty($PayIn->FileAttachPayInOne1To->file) ? $PayIn->FileAttachPayInOne1To->file : '',
                                'url'          => $url.'certify/applicant-ib',
                                'email'        =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'     =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                'email_reply'  => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                             ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no.'-'.$PayIn->auditors_id,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiIBPayInOne)->getTable(),
                                                            2,
                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                            view('mail.IB.pay_in_one', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            !empty($PayIn->FileAttachPayInOne1To->file) ?  'certify/check/file_ib_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :   basename($PayIn->FileAttachPayInOne1To->file) ) : null
                                                            );
                    $html = new IBPayInOneMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
                //   $certi_ib->status = 12;// แจ้งรายละเอียดค่าตรวจประเมิน
                //   $certi_ib->save();
            }else{
                if(count($certi_ib->EmailStaffAssign) > 0){
                    
                     $data_app = [
                                    'PayIn'        => $PayIn,
                                    'certi_ib'     => $certi_ib,
                                    'url'          => $url.'certify/applicant-ib',
                                    'email'        =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                    'email_cc'     =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                    'email_reply'  => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                                 ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no.'-'.$PayIn->auditors_id,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiIBPayInOne)->getTable(),
                                                            2,
                                                            $PayIn->status == 1 ? 'ค่าบริการในการตรวจประเมิน': 'ค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                            view('mail.IB.inform_pay_in_one', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            implode(',',(array)$certi_ib->EmailStaffAssign),
                                                            !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new IBInformPayInOne($data_app);
                    $mail =  Mail::to($certi_ib->EmailStaffAssign)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
                }
            //    $certi_ib->status = 14;// แจ้งรายละเอียดค่าตรวจประเมิน
            //    $certi_ib->save();

            }
         }

     }

 }
         if(isset($certi_ib->token)){
            return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
         }else{
            return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
         }
  } catch (\Exception $e) {
            return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  }

  }
        // สรุปรายงานและเสนออนุกรรมการฯ

    public function SaveReview(Request $request, $id){
        // dd($request->all());
            $report = CertiIBReport::findOrFail($id);
            $certi_ib = CertiIb::findOrFail($report->app_certi_ib_id);
            if($request->report_status == "2"){
                $certiIBSaveAssessmentIds = CertiIBSaveAssessment::where('app_certi_ib_id',$report->app_certi_ib_id)->pluck('id')->toArray();
                CertiIBSaveAssessmentBug::whereIn('assessment_id',$certiIBSaveAssessmentIds)->delete();
                CertiIBSaveAssessment::where('app_certi_ib_id',$report->app_certi_ib_id)->delete();
                CertiIb::findOrFail($report->app_certi_ib_id)->update([
                    'status' => 10
                ]);
                CertiIbHistory::create([
                    'app_certi_ib_id'      => $report->app_certi_ib_id ?? null,
                    'system'               => 9,
                    'table_name'           => (new CertiIBReport)->getTable(),
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
                CertiIBReport::findOrFail($id)->update([
                    'review_approve' => 2
                ]);
            }
    
            
            $json = $this->copyScopeIbFromAttachement($report->app_certi_ib_id);
            $copiedScopes = json_decode($json, true);
    
            $tb = new CertiIBReport;
            $certi_ib_attach_more = new CertiIBAttachAll();
            $certi_ib_attach_more->app_certi_ib_id      = $report->app_certi_ib_id ?? null;
            $certi_ib_attach_more->ref_id               = $report->id;
            $certi_ib_attach_more->table_name           = $tb->getTable();
            $certi_ib_attach_more->file_section         = '1';
            $certi_ib_attach_more->file                 = $copiedScopes[0]['attachs'];
            $certi_ib_attach_more->file_client_name     = $copiedScopes[0]['file_client_name'];
            $certi_ib_attach_more->token                = str_random(16);
            $certi_ib_attach_more->save();
    
            if(isset($certi_ib->token)){
                return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
             }else{
                return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
            }
    
        }

        public function askToEditIbScope(Request $request)
        {       
            
            CertiIb::findOrFail($request->appId)->update([
                'require_scope_update' => 1
            ]);
        

            $this->askToEditIbScopeEmail($request->appId,$request->details);
        }


        public function askToEditIbScopeEmail($id,$message)
        {
            
            $certi_ib = CertiIb::findOrFail($id);
             $config = HP::getConfig();
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                    $data_app = [
                                'certi_ib'      => $certi_ib,
                                'message'      => $message,
                                'url'           => $url.'certify/applicant-ib' ?? '-',
                                'email'         =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'      =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                'email_reply'   => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                                 ];
            // dd($data_app)   ;
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            null,
                                                            (new CertiIb)->getTable(),
                                                            2,
                                                            'ขอให้แก้ไขขอบข่าย',
                                                            view('mail.IB.scope_edit', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new IBScopeEditAlertMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
        }

    public function UpdateReport(Request $request, $id)
    {
        // dd($request->all());
   try {
            $report = CertiIBReport::findOrFail($id);
            $certi_ib = CertiIb::findOrFail($report->app_certi_ib_id);
            $tb = new CertiIBReport;

            $requestData = $request->all();
            $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
            $requestData['start_date'] =  !empty($request->start_date)?HP::convertDate($request->start_date, true):null;
            $requestData['end_date'] =  !empty($request->end_date)?HP::convertDate($request->end_date, true):null;
            $requestData['created_by'] =   auth()->user()->runrecno;

            $report->update($requestData);

            CertiIBFileAll::where('app_certi_ib_id',$certi_ib->id)->update([
                'start_date' => !empty($request->start_date)?HP::convertDate($request->start_date):null,
                'end_date' => !empty($request->end_date)?HP::convertDate($request->end_date):null,
            ]);
           
             // รายงาน file_loa
            // if($request->file_loa && $request->hasFile('file_loa')){
                // $certiIBAttachAll = CertiIBAttachAll::where('app_certi_ib_id',$report->app_certi_ib_id)
                //         ->where('table_name','app_certi_ib_assessment')
                //         ->where('file_section',2)->first(); 

                        $certiIBAttachAll = CertiIBAttachAll::where('app_certi_ib_id', $report->app_certi_ib_id)
                            ->where('table_name', 'app_certi_ib_assessment')
                            ->where('file_section', 2)
                            ->orderBy('id', 'desc') // เรียง id จากมากไปน้อย = ล่าสุดมาก่อน
                            ->first(); // ดึงแถวแรก = ล่าสุด

                
                        $certi_ib_attach_more = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $report->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $report->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '1';
                        // $certi_ib_attach_more->file             = $this->storeFile($request->file_loa,$certi_ib->app_no);
                        // $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file_loa->getClientOriginalName());
                        $certi_ib_attach_more->file             = $certiIBAttachAll->file;
                        $certi_ib_attach_more->file_client_name = $certiIBAttachAll->file_client_name;
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                       
                        //แนบท้าย
                        if(isset($certi_ib_attach_more->file)){
                           
                            CertiIBFileAll::where('app_certi_ib_id',$certi_ib->id)->update(['state' => 0]);
                             CertiIBFileAll::create([
                                                        'ref_id'                 =>  $report->id,
                                                        'ref_table'              =>  (new CertiIBReport)->getTable(),
                                                        'app_certi_ib_id'        =>  $certi_ib->id,
                                                        'app_no'                 =>  $certi_ib->app_no,
                                                        'attach_pdf'             =>  $certi_ib_attach_more->file,
                                                        'attach_pdf_client_name' =>  $certi_ib_attach_more->file_client_name,
                                                        'start_date'             => !empty($request->start_date)?HP::convertDate($request->start_date, true):null,
                                                        'end_date'               => !empty($request->end_date)?HP::convertDate($request->end_date, true):null,
                                                        'state'                  => 1
                                                     ]);
                         }

            // }
            // ไฟล์แนบ
            // if($request->file && $request->hasFile('file')){
                // foreach ($request->file as $index => $item){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $report->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $report->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '2';
                        // $certi_ib_attach_more->file             = $this->storeFile($item,$certi_ib->app_no);
                        // $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->file             =  $certi_ib_attach_more->file;
                        $certi_ib_attach_more->file_client_name = $certi_ib_attach_more->file_client_name;
                        // $certi_ib_attach_more->file_desc        = $request->file_desc[$index] ?? null;
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                // }

                // dd('ok');
            //  }
             if($report->report_status == 1){
                $certi_ib->update(['status'=>13]); // รอยืนยันคำขอ
            }else{
                $certi_ib->update(['status'=>12]); //สรุปรายงานและเสนออนุกรรมการฯ
            }
        
             // LOG
             $data = CertiIBReport::select('report_date', 'report_status', 'details')
                            ->where('id',$id)
                            ->first();
              CertiIbHistory::create([
                                          'app_certi_ib_id'      => $report->app_certi_ib_id ?? null,
                                          'system'               => 9,
                                          'table_name'           => $tb->getTable(),
                                          'ref_id'               => $report->id,
                                          'details_one'          => json_encode($data) ?? null,
                                          'file'                 => !empty($report->FileAttachReport1To->file) ? $report->FileAttachReport1To->file : null,
                                          'file_client_name'     =>  !empty($report->FileAttachReport1To->file_client_name) ? $report->FileAttachReport1To->file_client_name : null,
                                          'attachs'              => (count($report->FileAttachReport2Many) > 0) ? json_encode($report->FileAttachReport2Many) : null,
                                          'created_by'           => auth()->user()->runrecno
                                   ]);

            //Mail
            if(!is_null($certi_ib->email) && $report->report_status == 1){
                    $config = HP::getConfig();
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                    $data_app = [
                                'report'        => $report,
                                'certi_ib'      => $certi_ib,
                                'url'           => $url.'certify/applicant-ib' ?? '-',
                                'email'         =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'      =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                'email_reply'   => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                                 ];
            
                    $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $report->id,
                                                            (new CertiIBReport)->getTable(),
                                                            2,
                                                            'สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ',
                                                            view('mail.IB.report', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            $certi_ib->email,
                                                            !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :   'ib@tisi.mail.go.th',
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                            null
                                                            );

                    $html = new IBReportMail($data_app);
                    $mail =  Mail::to($certi_ib->email)->send($html);
        
                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }    
             }

         if(isset($certi_ib->token)){
                return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
            }else{
                return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
            }

  } catch (\Exception $e) {
            return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  }

     }

     public function GetIBPayInTwo($id = null,$token = null)
     {
        $previousUrl = app('url')->previous();
        $payin2  =  CertiIBPayInTwo::findOrFail($id);
        $attach_path  =  $this->attach_path;
        $feewaiver  =  Feewaiver::where('certify',2)
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
        return view('certify/ib/check_certificate_ib.pay_in_two', compact('previousUrl',
                                                                          'payin2',
                                                                          'attach_path',
                                                                          'feewaiver'
                                                                         ));
     }
     // แนบใบ Pay-in ครั้งที่ 2
    public function CreatePayInTwo(Request $request ,$id){

        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $PayIn = CertiIBPayInTwo::findOrFail($id);
        $attach_path  =  $this->attach_path;
        $tb = new CertiIBPayInTwo;
        $arrContextOptions = array();

   try {
        if(!is_null($PayIn)){
                   $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
                        $PayIn->conditional_type    = $request->conditional_type;
                        $PayIn->report_date         =  date('Y-m-d');
                        $PayIn->degree              =  1;
                        $PayIn->created_by          =  auth()->user()->runrecno;
                if($PayIn->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม

                    $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',2)->where('type',1)->first();

                    if(!is_null($setting_payment)){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                            "verify_peer" => false,
                                                            "verify_peer_name" => false,
                                                        );
                        }
                        $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$certi_ib->app_no", false, stream_context_create($arrContextOptions));
                        $api = json_decode($content);
                        $PayIn->amount_fixed    = 1000;
                        $PayIn->amount          = !empty(str_replace(",","",$api->app_check))?str_replace(",","",$api->app_check):null;
                        $PayIn->amount_fee      = !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                        $PayIn->save();

                        $certi_ib_attach_more = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                        $certi_ib_attach_more->table_name           = $tb->getTable();
                        $certi_ib_attach_more->ref_id               = $PayIn->id;
                        $certi_ib_attach_more->file_section         = '1';
                        $certi_ib_attach_more->file_desc            = 'เรียกเก็บค่าธรรมเนียม';
                        


                        // if(strpos($setting_payment->data, '127.0.0.1')===0){
                        // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {  
                        $host = parse_url($setting_payment->data, PHP_URL_HOST);

                        if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {
                            
                            $certi_ib_attach_more->file                 =  $this->storeFilePayin($setting_payment,$certi_ib->app_no);
                        }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                            
                            $certi_ib_attach_more->file =   $this->storeFilePayinDemo($setting_payment,$certi_ib->app_no);
                        }



                        $certi_ib_attach_more->file_client_name     =  isset($certi_ib_attach_more->file) ? basename($certi_ib_attach_more->file)  : null;
                        $certi_ib_attach_more->token                =  str_random(16);
                        $certi_ib_attach_more->save();

                        $transaction = HP::TransactionPayIn2($id,$tb->getTable(),'2','2',$api);

                         $file =  $PayIn->FileAttachPayInTwo1To->file ?? null;
                         if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                             HP::getFileStoragePath($attach_path.$file);
                         }
                   }
                }else  if($PayIn->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                        $feewaiver  =  Feewaiver::where('certify',2)->first();
                        if(!is_null($feewaiver->payin2_file)){
                            $certi_ib_attach_more = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                            $certi_ib_attach_more->table_name           = $tb->getTable();
                            $certi_ib_attach_more->ref_id               = $PayIn->id;
                            $certi_ib_attach_more->file_section         = '1';
                            $certi_ib_attach_more->file_desc            = 'ยกเว้นค่าธรรมเนียม';
                            $certi_ib_attach_more->file                 =  $feewaiver->payin2_file;
                            $certi_ib_attach_more->file_client_name     =  $feewaiver->payin2_file_client_name;
                            $certi_ib_attach_more->token                =  str_random(16);
                            $certi_ib_attach_more->save();

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
                            $certi_ib_attach_more = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id      = $PayIn->app_certi_ib_id;
                            $certi_ib_attach_more->table_name           = $tb->getTable();
                            $certi_ib_attach_more->ref_id               = $PayIn->id;
                            $certi_ib_attach_more->file_section         = '1';
                            $certi_ib_attach_more->file_desc            = 'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม';
                            $certi_ib_attach_more->file                 =   $this->storeFile($request->attach,$certi_ib->app_no);
                            $certi_ib_attach_more->file_client_name     =  HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                            $certi_ib_attach_more->token                =  str_random(16);
                            $certi_ib_attach_more->save();
                            $file =  $PayIn->FileAttachPayInTwo1To->file ?? null;
                            if(!is_null($file) && HP::checkFileStorage($attach_path.$file)){
                                HP::getFileStoragePath($attach_path.$file);
                            }
                        }

                }
                self::insert_payin_all(2,$PayIn);
                $data = CertiIBPayInTwo::select('report_date', 'amount','amount_fixed', 'amount_fee', 'degree','status','conditional_type','remark','start_date_feewaiver','end_date_feewaiver')->where('id',$PayIn->id)->first();
                CertiIbHistory::create([
                                       'app_certi_ib_id'        => $PayIn->app_certi_ib_id ?? null,
                                       'system'                 => 10,
                                       'table_name'             => $tb->getTable(),
                                       'ref_id'                 => $PayIn->id,
                                       'status'                 => $PayIn->status ?? null,
                                       'details_one'            => json_encode($data) ?? null,
                                       'attachs'                => !empty($PayIn->FileAttachPayInTwo1To->file)  ? $PayIn->FileAttachPayInTwo1To->file : null,
                                       'attach_client_name'     => !empty($PayIn->FileAttachPayInTwo1To->file_client_name)  ? $PayIn->FileAttachPayInTwo1To->file_client_name : null,
                                       'created_by'             => auth()->user()->runrecno
                                     ]);

                        //Mail
                        if(!is_null($certi_ib->email)){
                            $data_app = [
                                            'PayIn'        => $PayIn,
                                            'certi_ib'     => $certi_ib,
                                            'attachs'      =>  !empty($PayIn->FileAttachPayInTwo1To->file)  ? $PayIn->FileAttachPayInTwo1To->file :'',
                                            'url'          => $url.'certify/applicant-ib',
                                            'email'        =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                            'email_cc'     =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                                            'email_reply'  => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                                         ];
                    
                            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                                    $certi_ib->id,
                                                                    (new CertiIb)->getTable(),
                                                                    $PayIn->id,
                                                                    (new CertiIBPayInTwo)->getTable(),
                                                                    2,
                                                                   'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                                    view('mail.IB.pay_in_two', $data_app),
                                                                    $certi_ib->created_by,
                                                                    $certi_ib->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                                    $certi_ib->email,
                                                                    !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :   'ib@tisi.mail.go.th',
                                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                                    !empty($PayIn->FileAttachPayInTwo1To->file) ?  'certify/check/file_ib_client/'.$PayIn->FileAttachPayInTwo1To->file.'/'.( !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name :   basename($PayIn->FileAttachPayInTwo1To->file) ) : null
                                                                    );
        
                            $html = new IBPayInTwoMail($data_app);
                            $mail =  Mail::to($certi_ib->email)->send($html);
                
                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }    
                        }

                    $certi_ib->status = 15;  //แจ้งรายละเอียดการชำระค่าใบรับรอง
                    $certi_ib->save();

                    $Report = CertiIBReport::where('app_certi_ib_id',$certi_ib->id)->orderby('id','desc')->first();
                    if(!is_null($Report)){
                        $Report->update(['status_alert' => 2]);
                    }

            return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
            }

  } catch (\Exception $e) {
            return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  }

}
public function storeFilePayinDemo($setting_payment, $app_no = 'files_ib', $auditor_id = '')
{

    $baseUrl = strstr($setting_payment->data, '/api', true);

    $url = $baseUrl. '/images/PayIn2.pdf';

    // ดาวน์โหลดเนื้อหา PDF (Demo)
    // $pdf_content = file_get_contents($url);

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

    // ลบ "RQ-" ออกจาก $app_no และเปลี่ยน "-" เป็น "_"
    $no  = str_replace("RQ-","",$app_no);
    $no  = str_replace("-","_",$no);

    // สร้าง path สำหรับบันทึกไฟล์
    $attach_path  =  $this->attach_path.$no;
    $fullFileName = $no . '-' . date('Ymd_His') . '.pdf';

    // บันทึกไฟล์ใน storage path
    $storagePath = Storage::disk('uploads')->put($attach_path . '/' . $fullFileName, $pdf_content);
    
    return  $no.'/'.$fullFileName;
}



        // สำหรับเพิ่มรูปไปที่ store
 public function storeFilePayin($setting_payment, $app_no = 'files_ib', $auditor_id = '')
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
    try {
        $requestData = $request->all();
        $PayIn = CertiIBPayInTwo::findOrFail($id);
        $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
        $tb = new CertiIBPayInTwo;
        if($request->status_confirmed == 1){
            $requestData['degree'] = 3;

            if($certi_ib->standard_change == 1  || is_null($certi_ib->certificate_export_to2)){ // ขอใบรับรอง
                $certi_ib->update([ 'status' =>17 ]);   // ยืนยันการชำระเงินค่าใบรับรอง
            }else{
                $certi_ib->update([ 'status' =>18 ]);   // ออกใบรับรอง และ ลงนาม
            }

            // เงื่อนไขเช็คมีใบรับรอง 
            $this->save_certiib_export_mapreq( $certi_ib );

        }else{
            $requestData['degree'] = 1;
            $requestData['detail'] = $request->detail ?? null ;
            $certi_ib->update(['status' => 15]); //แจ้งรายละเอียดการชำระค่าใบรับรอง

        }

        $requestData['status'] = $request->status_confirmed ?? 2 ;
        $requestData['condition_pay'] =  !empty($request->condition_pay) ?  $request->condition_pay : null ; 
        $PayIn->update($requestData);

        if(!empty($request->ReceiptCreateDate)){
            $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiIBPayInTwo)->getTable())->orderby('id','desc')->first();
            if(!is_null($transaction_payin)){
                $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                $transaction_payin->save();
            }
        }

            $data = CertiIBPayInTwo::select('report_date', 'amount','amount_fixed', 'amount_fee', 'degree','status','conditional_type','remark','start_date_feewaiver','end_date_feewaiver','condition_pay')->where('id',$PayIn->id)->first();
             CertiIbHistory::create([
                                    'app_certi_ib_id'       => $PayIn->app_certi_ib_id ?? null,
                                    'system'                => 10,
                                    'table_name'            => $tb->getTable(),
                                    'ref_id'                => $PayIn->id,
                                    'status'                => $PayIn->status ?? null,
                                    'details_one'           =>  json_encode($data) ?? null,
                                    'attachs'               =>  !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                                    'attach_client_name'    =>   !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name : null,
                                    'attachs_file'          =>  !empty($PayIn->FileAttachPayInTwo2To->file) ? $PayIn->FileAttachPayInTwo2To->file : null,
                                    'evidence'              =>   !empty($PayIn->FileAttachPayInTwo2To->file_client_name) ? $PayIn->FileAttachPayInTwo2To->file_client_name : null,
                                    'created_by'            =>  auth()->user()->runrecno
                                  ]);
        //Mail
        if(!is_null($certi_ib->email) && $PayIn->status == 2 ){
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $data_app = [ 
                            'PayIn'         => $PayIn,
                            'certi_ib'      => $certi_ib,
                            'attachs'       =>  !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                            'full_name'     => $certi_ib->FullRegName ?? '-',
                            'url'           => $url.'certify/applicant-ib' ?? '-',
                            'email'         =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                            'email_cc'      =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : 'ib@tisi.mail.go.th',
                            'email_reply'   => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'               
                            ];
                
            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                    $certi_ib->id,
                                                    (new CertiIb)->getTable(),
                                                    $PayIn->id,
                                                    (new CertiIBPayInTwo)->getTable(),
                                                    2,
                                                    'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                    view('mail.IB.pay_in_two', $data_app),
                                                    $certi_ib->created_by,
                                                    $certi_ib->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                    $certi_ib->email,
                                                    !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :   'ib@tisi.mail.go.th',
                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                    !empty($PayIn->FileAttachPayInTwo1To->file) ?  'certify/check/file_ib_client/'.$PayIn->FileAttachPayInTwo1To->file.'/'.( !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name :   basename($PayIn->FileAttachPayInTwo1To->file) ) : null
                                                    );

            $html = new IBPayInTwoMail($data_app);
            $mail =  Mail::to($certi_ib->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }   

         }
        if(isset($certi_ib->token)){
                return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
        }else{
                return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
         }

  } catch (\Exception $e) {
            return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  }

}

     public function UpdateAttacho(Request $request, $id){
     try {
            $certi_ib = CertiIb::findOrFail($id);
             // ประวัติการแนบไฟล์ แนบท้าย
             if($request->attach  &&   $request->attach_pdf   &&  $request->hasFile('attach')   &&   $request->hasFile('attach_pdf') ){

                  CertiIBFileAll::where('app_certi_ib_id',$certi_ib->id)->update(['state' => 0]);
                  $certLabs   = CertiIBFileAll::create([
                                              'app_certi_ib_id'       =>  $certi_ib->id,
                                              'attach'                =>  $this->storeFile($request->attach,$certi_ib->app_no) ?? '',
                                              'attach_client_name'    =>   HP::ConvertCertifyFileName($request->attach->getClientOriginalName()) ?? '',
                                              'attach_pdf'            =>   $this->storeFile($request->attach_pdf,$certi_ib->app_no) ?? '',
                                              'attach_pdf_client_name'=>   HP::ConvertCertifyFileName($request->attach_pdf->getClientOriginalName()) ?? '',
                                              'state'                 => 1
                                           ]);
           }else{

              if($request->state){
                  CertiIBFileAll::where('app_certi_ib_id',$certi_ib->id)->update(['state' => 0]);
                  $certLabs = CertiIBFileAll::findOrFail($request->state);
                  $certLabs->update(['state' => 1]);
              }

           }
     if(isset($certi_ib->token)){
           return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
     }else{
           return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
      }

   } catch (\Exception $e) {
        return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
   }

 }

    public function DataShow($id = null)
    {
        $previousUrl = app('url')->previous();
        $history  =  CertiIbHistory::findOrFail($id);

        return view('certify/ib/check_certificate_ib.history_detail',  compact('previousUrl',
                                                                               'history'
                                                                             ));
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


  //แต่งตั้งคณะทบทวนฯ
    public function UpdateReview(Request $request, $id){
    try {
        $review = CertiIBReview::findOrFail($id);
        $certi_ib = CertiIb::findOrFail($review->app_certi_ib_id);
        if(!is_null($review)){
            $tb = new CertiIBReview;
             //คณะผู้ตรวจประเมิน
            if($request->evidence){
                $certi_ib_attach_more = new CertiIBAttachAll();
                $certi_ib_attach_more->app_certi_ib_id  = $review->app_certi_ib_id ?? null;
                $certi_ib_attach_more->ref_id           = $review->id;
                $certi_ib_attach_more->table_name       = $tb->getTable();
                $certi_ib_attach_more->file_section     = '1';
                $certi_ib_attach_more->file             = $this->storeFile($request->evidence,$certi_ib->app_no);
                $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->evidence->getClientOriginalName());
                $certi_ib_attach_more->token            = str_random(16);
                $certi_ib_attach_more->save();

                $review->files =   $certi_ib_attach_more->file;
            }
             //ผลการตรวจคณะผู้ตรวจประเมิน
             if($request->attach){
                $certi_ib_attach_more = new CertiIBAttachAll();
                $certi_ib_attach_more->app_certi_ib_id  = $review->app_certi_ib_id ?? null;
                $certi_ib_attach_more->ref_id           = $review->id;
                $certi_ib_attach_more->table_name       = $tb->getTable();
                $certi_ib_attach_more->file_section     = '2';
                $certi_ib_attach_more->file             = $this->storeFile($request->attach,$certi_ib->app_no);
                $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                $certi_ib_attach_more->token            = str_random(16);
                $certi_ib_attach_more->save();

                $review->attach =   $certi_ib_attach_more->attach;
            }
            $review->review = isset($request->review) ? 2: 1;
            $review->save();

             CertiIbHistory::create([
                                    'app_certi_ib_id'       => $review->app_certi_ib_id ?? null,
                                    'system'                => 11,
                                    'table_name'            => $tb->getTable(),
                                    'ref_id'                => $review->id,
                                    'details_one'           => $review->review  ?? null,
                                    'file'                  => !empty($review->FileReview1->file) ? $review->FileReview1->file : null,
                                    'file_client_name'      => !empty($review->FileReview1->file_client_name) ? $review->FileReview1->file_client_name : null,
                                    'attachs'               =>  !empty($review->FileReview2->file) ? $review->FileReview2->file : null,
                                    'attach_client_name'    =>  !empty($review->FileReview2->file_client_name) ? $review->FileReview2->file_client_name : null,
                                    'created_by'            => auth()->user()->runrecno
                                  ]);

            if($review->review == 2 && !is_null($review->FileReview1) && !is_null($review->FileReview2)){
                if(!is_null($certi_ib)){
                    $certi_ib->update(['review' => 2,'status' => 12]);  // สรุปรายงานและเสนออนุกรรมการฯ
                    $report = new CertiIBReport;  //สรุปรายงานและเสนออนุกรรมการฯ
                    $report->app_certi_ib_id =  $certi_ib->id;
                    $report->review_approve = "1";
                    $report->save();
                }
            }

        }

        if(isset($certi_ib->token)){
                return redirect('certify/check_certificate-ib/'.$certi_ib->token)->with('flash_message', 'เรียบร้อยแล้ว');
        }else{
                return redirect('certify/check_certificate-ib')->with('flash_message', 'เรียบร้อยแล้ว');
        }
  } catch (\Exception $e) {
        return redirect('certify/check_certificate-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  }


   }



   
   public function certificate_detail($token = null)
   {

       $certi_ib_primary = CertiIb::where('token', $token)->firstOrfail();
       
       if(!empty($certi_ib_primary->certi_ib_export_mapreq_to)){
        
            $certi_ib_mapreq = CertiIbExportMapreq::where('certificate_exports_id', $certi_ib_primary->certi_ib_export_mapreq_to->certificate_exports_id)->orderBy('id')->firstOrfail();
            // dd( $certi_ib_primary->id,$certi_ib_primary->certi_ib_export_mapreq_to, $certi_ib_mapreq);
            if(!empty($certi_ib_mapreq->app_certi_ib_to)){
                $certi_ib = $certi_ib_mapreq->app_certi_ib_to;
            }
       }

    //    dd($certi_ib_primary,$certi_ib);

       
       if(!empty($certi_ib->certi_ib_export_mapreq_to)){
           $export               =  $certi_ib->app_certi_ib_export;  
           $certiib_file_all    =  !empty($export->CertiIBCostTo->cert_ibs_file_all_order_desc) ?  $export->CertiIBCostTo->cert_ibs_file_all_order_desc : []; 

         // ใบรับรอง และ ขอบข่าย    
         if(!is_null($certi_ib->certi_ib_export_mapreq_to)){
            $certificate =  !empty($certi_ib->certi_ib_export_mapreq_to->app_certi_ib_export_to->certificate) ? $certi_ib->certi_ib_export_mapreq_to->app_certi_ib_export_to->certificate : null;



            if(!is_null($certificate)){
                     $export_no         =  CertiIBExport::where('certificate',$certificate);
                     
                    if(count($export_no->get()) > 0){

                      $ib_ids = [];
                      if($export_no->pluck('app_certi_ib_id')->count() > 0){
                          foreach ($export_no->pluck('app_certi_ib_id') as $item) {
                              if(!in_array($item,$ib_ids)){
                                 $ib_ids[] =  $item;
                              }
                          }
                      }

                    //   dd($certi_ib->certi_ib_export_mapreq_to, $export,$certificate,$export_no->get()->count(),$ib_ids);

                      if($certi_ib->certi_ib_export_mapreq_to->certiib_export_mapreq_group_many->count() > 0){
                          foreach ($certi_ib->certi_ib_export_mapreq_to->certiib_export_mapreq_group_many->pluck('app_certi_ib_id') as $item) {
                              if(!in_array($item,$ib_ids)){
                                  $ib_ids[] =  $item;
                              }
                          }
                      }

                      // ขอบข่าย
                      $file_alls =  CertiIBFileAll::whereIn('app_certi_ib_id',$ib_ids)->orderby('created_at','desc')->whereNotIn('status_cancel',[1])->get();
                      if(count($file_alls) > 0){
                          $certiib_file_all =  $file_alls;
                      }
              
                 } 
            }
       }

    //    dd($certiib_file_all);
            // $certi_ib_mapreq = CertiIbExportMapreq::where('certificate_exports_id', $certi_ib->certi_ib_export_mapreq_to->certificate_exports_id)->orderBy('id')->firstOrfail();
            // if(!empty($certi_ib_mapreq->app_certi_ib_to)){ app_certi_ib_export_to2
            //     $certi_ib = $certi_ib_mapreq->app_certi_ib_to;
                 
                return view('certify.ib.check_certificate_ib.certificate_detail', compact('certi_ib', 'certiib_file_all', 'certi_ib_primary'));
            // }
       }
       abort(404);
   }


   public function update_document(Request $request)
   {
       $certi_ib = CertiIb::where('id',$request->app_certi_ib_id)->first();
    
       if(!is_null($certi_ib)){
        $attach_path            =  $this->attach_path;
            CertiIBFileAll::where('app_certi_ib_id', $certi_ib->id)->update(['state' => 0]);
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
           $requestData['app_certi_ib_id']   = $certi_ib->id;
           $requestData['ref_table']          = (new CertiIb)->getTable();
           $requestData['ref_id']             = $request->ref_id;

            // ประวัติการแนบไฟล์ แนบท้าย
            if(  $request->form == 'create'){
                   $certibs   = CertiIBFileAll::create($requestData);

                   $certi_ib->status       =   '20';
                   $certi_ib->save();

                   $obj = new stdClass;
                   $obj->id                =  $certibs->id;
                   $obj->app_no            =  $certibs->app_no; 
                   $obj->file_word         =   !empty($certibs->attach)   ?  HP::getFileStorage($attach_path.$certibs->attach)    : '';
                   $obj->file_pdf          =   !empty($certibs->attach_pdf)   ?  HP::getFileStorage($attach_path.$certibs->attach_pdf)    : '';
                   $obj->start_date        =   !empty($certibs->start_date)   ? HP::revertDate($certibs->start_date,true) : '';
                   $obj->start_date_th     =   !empty($certibs->start_date)   ? HP::DateThai($certibs->start_date) : '';
                   $obj->end_date          =   !empty($certibs->end_date)   ? HP::revertDate($certibs->end_date,true) : '';
                   $obj->end_date_th       =   !empty($certibs->end_date)   ? HP::DateThai($certibs->end_date) : '';
                   $obj->created_at        =   !empty($certibs->created_at)   ? HP::DateThai($certibs->created_at) : '';
                   $obj->state             =   !empty($certibs->state)   ? $certibs->state : '';
                   return response()->json( $obj );
           }else{
                   $certibs   = CertiIBFileAll::findOrFail($request->id);
                   $certibs->update($requestData);

                   $certi_ib->status       =   '20';
                   $certi_ib->save();

                    $datas = [];
                    $alls =  CertiIBFileAll::where('app_certi_ib_id', $certi_ib->id)->whereNotIn('status_cancel',[1])->get();
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
       $result  =  CertiIBFileAll::findOrFail($request->id);
       if($result) {
           $result->update($requestData);
           return 'success';
       } else {
           return "not success";
       }


   }

   public function check_pay_in_ib(Request $request)
   {


            $arrContextOptions=array();
           $id =   $request->input('id');
           $payin =   $request->input('payin');
       if($payin == '1'){ // pay in ครั้งที่ 1
                $pay_in = CertiIBPayInOne::findOrFail($id);
            if(!is_null($pay_in)){

                $pay_in->start_date     =  isset($request->start_date)?  $request->start_date : null;
                $pay_in->amount         =  isset($request->amount)?str_replace(",","",$request->amount):  null;
                $pay_in->amount_bill    =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):  null;
                $pay_in->save();

                $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',1)->where('type',1)->first();
                $certi_ib = CertiIb::findOrFail($pay_in->app_certi_ib_id);
                $app_no =  $certi_ib->app_no;

                $data_ref1 = $app_no.'-'.$pay_in->auditors_id;

                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
                // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$app_no-$pay_in->auditors_id", false, stream_context_create($arrContextOptions));
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$data_ref1", false, stream_context_create($arrContextOptions));
                // $pay_in->amount         = null;
                // $pay_in->amount_bill    = null;
                // $pay_in->start_date     = null;
                // $pay_in->save();

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
       }else{
              $pay_in = CertiIBPayInTwo::findOrFail($id);
              $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',2)->where('type',1)->first();
             if(!is_null($setting_payment)){
                $certi_ib = CertiIb::findOrFail($pay_in->app_certi_ib_id);
                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$certi_ib->app_no", false, stream_context_create($arrContextOptions));
                $api = json_decode($content);
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
      
             $data  =  CertiIb::findOrFail($request->id);
              return response()->json([
                                          'message' =>  HP_API_PID::CheckDataApiPid($data,(new CertiIb)->getTable())   
                                     ]);
   }
   public function insert_payin_all($type, $item)
   {
 
       if($type == 1){ // lab

                if(!empty($item->CertiIBCostTo) && !is_null($item->conditional_type)){
                    $app = $item->CertiIBCostTo;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiIBPayInOne)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CertiIBPayInOne)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = !empty($item->amount) ?  $item->amount: null ;
                    $pay_in->start_date             = !empty($item->start_date) ?  $item->start_date: null ;
                    $pay_in->detail                 = !empty($item->detail) ?  $item->detail: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->name_unit) ?  $app->name_unit: null ;
                    $pay_in->auditors_name          = !empty($item->CertiIBAuditorsTo->auditor) ?  $item->CertiIBAuditorsTo->auditor : null ;
                    $pay_in->certify                = 2;
                    $pay_in->state                  = 1;
                    $pay_in->created_by             = !empty($item->reporter_id) ?  $item->reporter_id: null ; 
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
                    if( !empty($item->FileAttachPayInOne1To) ){
                        $attach = [];
                        $attach_file  = $item->FileAttachPayInOne1To;
                        $file               =   basename($attach_file->file);
                        $attach['url']            =   'certify/check/file_ib_client/'.$attach_file->file  ;
                        $attach['new_filename']   =  $file;
                        $attach['filename']       =  $attach_file->file_client_name;
                        $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null; 
                    }
                    $pay_in->save();  
                    echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                    echo '<br/>';
                }

       }else{ 
                 if(!empty($item->CertiIBCostTo) && !is_null($item->conditional_type)){
                    $app = $item->CertiIBCostTo;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CertiIBPayInTwo)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CertiIBPayInTwo)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = null ;
                    $pay_in->start_date             = !empty($item->report_date) ?  $item->report_date: null ;
                    $pay_in->detail                 = !empty($item->remark) ?  $item->remark: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->name_unit) ?  $app->name_unit: null ;
                    $pay_in->auditors_name          =  null ;
                    $pay_in->certify                = 2;
                    $pay_in->state                  = 2;
                    $pay_in->created_by             = !empty($item->created_by) ?  $item->created_by: null ; 
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
                    if( !empty($item->FileAttachPayInTwo1To) ){
                        $attach = [];
                        $attach_file  = $item->FileAttachPayInTwo1To;
                        $file               =   basename($attach_file->file);
                        $attach['url']            =   'certify/check/file_ib_client/'.$attach_file->file  ;
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

   private function save_certiib_export_mapreq($certi_ib)
   {
         $app_certi_ib             = CertiIb::with([
                                                   'app_certi_ib_export' => function($q){
                                                       $q->whereIn('status',['0','1','2','3','4']);
                                                   }
                                               ])
                                               ->where('created_by', $certi_ib->created_by)
                                               ->whereNotIn('status', ['0','4'])
                                               ->where('type_standard', $certi_ib->type_standard)
                                               ->first();
        if(!Is_null($app_certi_ib)){
            $certificate_exports_id = !empty($app_certi_ib->app_certi_ib_export->id) ? $app_certi_ib->app_certi_ib_export->id : null;
             if(!Is_null($certificate_exports_id)){
                      $mapreq =  CertiIbExportMapreq::where('app_certi_ib_id',$certi_ib->id)->where('certificate_exports_id', $certificate_exports_id)->first();
                      if(Is_null($mapreq)){
                          $mapreq = new  CertiIbExportMapreq;
                      }
                      $mapreq->app_certi_ib_id       = $certi_ib->id;
                      $mapreq->certificate_exports_id = $certificate_exports_id;
                      $mapreq->save();
             }
        }
   }
   
   public function update_delete(Request $request)
   {
       $certi_ib = CertiIb::findOrFail($request->input('del_id'));
       if(!empty($certi_ib)){
           $request->request->add(['deleted_by' => @auth()->user()->getKey()]);
           $request->request->add(['deleted_at' => date('Y-m-d h:i:s')]);
           $request->request->add(['status' => 4]);
           $certi_ib->update($request->all());
           $attach_files_del_names = $request->get('attach_files_del_name');
           if(!empty($attach_files_del_names) && count($attach_files_del_names) > 0){
               foreach( $attach_files_del_names as $key=>$file ){
                   if($request->hasFile("attach_files_del.{$key}")){
                       HP::singleLabCancalFileUpload(
                           $request->file("attach_files_del.{$key}"),
                           $this->attach_path,
                           $certi_ib,
                           $request->input("attach_files_del_name.{$key}")
                       );
                   }
               }
           }
       }
       return redirect()->back();
   }


}
