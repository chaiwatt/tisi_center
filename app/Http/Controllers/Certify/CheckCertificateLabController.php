<?php

namespace App\Http\Controllers\Certify;
use HP;
use PDF;
use Storage;
use App\User;
use stdClass;
use Mpdf\Mpdf;
use HP_API_PID;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\CertificateExport;

use App\IpaymentCompanycode;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Helpers\EpaymentDemo;
use App\Mail\CertifyAuditFees;
use App\Mail\Lab\CertifyPayIn1;
use App\Mail\Lab\CertifyReport;
use App\Models\Basic\Feewaiver;
use App\Mail\Lab\CertifyRequest;
use App\Models\Certify\PayInAll;
use App\Mail\CheckCertificateLab;
use App\Mail\Lab\CertifyDocuments;
use Illuminate\Support\Facades\DB;
use App\Models\Bcertify\TestBranch;
use App\Services\CreateLabScopePdf;
use App\Http\Controllers\Controller;
use App\Mail\CertifySummarizeReport;
use App\Mail\Lab\LABAssignStaffMail;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Certify\Applicant\Cost;
use App\Mail\CertifyNotCostCertificate;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Certify\Applicant\Check;
use App\Mail\Lab\CertifyConfirmedPayIn1;
use App\Mail\Lab\CertifyCostCertificate;
use App\Models\Bcertify\LabTestCategory;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\Report;
use App\Models\Certify\TransactionPayIn;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Bcertify\TestBranchCategory;
use App\Models\Certify\Applicant\CheckFile;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\ReportFile;
use App\Models\Certify\Applicant\StatusTrait;
use App\Models\Certify\Applicant\CheckExaminer;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Bcertify\LabRequestRejectTracking;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\Applicant\CostCertificate;
use App\Models\Certify\Applicant\CostItemConFirm;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Certify\Applicant\AssessmentExaminer;
use App\Models\Certify\Applicant\CertifyLabCalibrate;
use App\Models\Certify\Applicant\CertiLabExportMapreq;
use App\Models\Certify\Applicant\CostAssessmentHistory;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class CheckCertificateLabController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files/';
    }

    public function index(Request $request)
    {
       
        $keyword = $request->get('search');
        $filter = [];

        $filter['at'] = $request->get('at', '');
        $filter['b'] = $request->get('b', '');
        $filter['s'] = $request->get('s', '');
        $filter['c'] = $request->get('c', '');
        $filter['filter_start_date'] = $request->get('filter_start_date', '');
        $filter['filter_end_date'] = $request->get('filter_end_date', '');
        $filter['q'] = $request->get('q', '');
        $filter['sort'] = $request->get('sort', '');
        $filter['filter_name'] = $request->get('filter_name', '');
        $filter['direction'] = $request->get('direction', '');
        $filter['perPage'] = $request->get('perPage', 10);

        $ao = new CertiLab;
        $arrStatus = $ao->arrStatus2();
        $branches = collect();
        $Query = CertiLab::select('app_certi_labs.*')->where('status','>=','1');
        if ($filter['at']!='') { // ความสามารถห้องปฏิบัติการ
            $Query = $Query->where('lab_type', $filter['at']);
            $ao->get_branches($filter['at'])->each(function ($branch) use ($branches) {
                $branches->put($branch->id, $branch->title);
            });
        }

        if ($filter['b']!='' && $filter['at']!='') { // สาขา
            $Query = $Query->whereHas('certi_test_scope', function ($query) use ($filter) {
                                $query->where('branch_id', $filter['b']);
                            });
        }

        if ($filter['s']!='') { // สถานะคำขอ
            $Query = $Query->where('status', $filter['s']);
        }

        if ($filter['b']!='' && $filter['at']!='') { // สาขา
            $Query = $Query->whereHas('certi_test_scope', function ($query) use ($filter) {
                                $query->where('branch_id', $filter['b']);
                            });
        }

        if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
            $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
            $end = Carbon::createFromFormat('d/m/Y H:i:s',$filter['filter_end_date'] . '23:59:59');
            $Query = $Query->whereBetween('created_at', [$start->toDateString(),$end]);

        } elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
            $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
            $Query = $Query->whereDate('created_at',$start->toDateString());
        }

        if ($filter['filter_name']!='') { // หน่วยงาน
            $Query = $Query->where(function ($query) use ($filter) {
                $key = str_replace(' ', '', $filter['filter_name']);
                $query->where(DB::raw("REPLACE(name,' ','')"), 'like', '%'.$key.'%');

            });
        }

        if ($filter['q']!='') { // สถานะคำขอ
            $Query = $Query->where(function ($query) use ($filter) {
                $key = str_replace(' ', '', $filter['q']);
                $query->where('app_no', 'like', '%'.$key.'%')
                    ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'like', '%'.$key.'%')
                    ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'like', '%'.$key.'%')
                    ->OrWhere(DB::raw("REPLACE(app_no,' ','')"), 'like', '%'.$key.'%');
            });
        }
        // dd(auth()->user()->runrecno);
        $examiner = CheckExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); //เจ้าหน้าที่ รับผิดชอบ  สก.
        $User =   User::where('runrecno',auth()->user()->runrecno)->first();
        $select_users = array();
        if($User->IsGetIdRoles() == 'false'){  //ไม่ใช่ admin , ผอ , ลท

            if(!is_null($examiner) && count($examiner) > 0  && !in_array('22',auth()->user()->RoleListId)){
                $Query = $Query->LeftJoin((new CheckExaminer)->getTable().' AS check_exminer', 'check_exminer.app_certi_lab_id','=','app_certi_labs.id')
                                ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่ที่ได้มอบหมาย
            }else{
                if(isset($User) && !is_null($User->reg_subdepart) && (in_array('11',$User->BasicRoleUser) || in_array('22',$User->BasicRoleUser))  ) {  //ผู้อำนวยการกอง ของ สก.
                    $Query = $Query->where('subgroup',$User->reg_subdepart);
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }

            $select_users  = User::where('reg_subdepart',$User->reg_subdepart)  //มอบ เจ้าหน้าที่ รับผิดชอบ  สก.
                            ->whereNotIn('runrecno',[$User->runrecno])
                            ->select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                            ->orderbyRaw('CONVERT(title USING tis620)')
                            ->pluck('title','runrecno');

         }else{

             $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                            ->whereIn('reg_subdepart',[1804,1805,1806])
                            ->orderbyRaw('CONVERT(title USING tis620)')
                            ->pluck('title','runrecno');
         }

        if($filter['sort'] != '' && $filter['direction'] != ''){
            $apps = $Query->sortable()->paginate($filter['perPage']);
        }else{
            $apps = $Query ->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        }


        return view('certify.check_certificate_lab.index', compact(
            'select_users','apps','filter','branches','arrStatus'
        ));
    }

    public function apiGetApp(CertiLab $app) {
        $app->trader;
        $app->str_lab_type = $app->assessment_type("th");
        $app->str_branches = $app->get_branch() ? $app->get_branch()->title : '-';
        $app->str_created_at = $app->created_at->format('d/m/Y');
        $app->str_status = $app->getStatus();
        $app->str_checker = $app->assessment ? $app->assessment->checker->full_name : '-';

        return response()->json([
            'app' => $app
        ], 200);
    }


    public function assign(Request $request)
    {
// dd('ok');
    //  try {

        $checker = $request->input('checker');
        $apps = $request->input('apps');
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
            foreach ($apps as $app_id) {
                $app = CertiLab::find($app_id);
                if ($app){
                     // ตรวจสอบคำขอใบรับรองห้องปฏิบัติการ
                    $check =  Check::where('app_certi_lab_id',$app->id)->first();
                    if(is_null($check)){
                        $check = new Check;
                    }
                    $check->app_certi_lab_id = $app->id;
                    $check->checker_id = null;
                    $check->save();

                     // เช็คคำขอมอบหมายให้เจ้าหน้าที่หรือยัง
                     $examiner =  CheckExaminer::where('app_certi_lab_id', $check->app_certi_lab_id)->first();
                    if(is_null($examiner) && $app->status < 6){
                       $app->update(['status'=> 2]);
                    }


                    //มอบหมาบ คำขอ
                    // $asessment =  Assessment::where('app_certi_lab_id',$app_id)->first();
                    // if(is_null($asessment)){
                    //     $asessment = new Assessment;
                    // }
                    // $asessment->app_certi_lab_id = $app->id;
                    // $asessment->checker_id = null;
                    // $asessment->save();
                     $examiner = $this->save_check_examiner($check, $checker);
                                $this->save_assessments_examiner($app, $checker);
                    if(count($reg_email) > 0){

                        $data_app = ['app'=>  $app,
                                    'email'=> auth()->user()->reg_email ?? 'admin@admin.com',
                                    'reg_fname' => (count($reg_fname) > 0) ? implode(", ",$reg_fname) : null
                                    ];

                         $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $examiner->id,
                                                                    (new CheckExaminer)->getTable(),
                                                                    1,
                                                                    'ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการ',
                                                                    view('mail.Lab.assign_staff', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    auth()->user()->reg_email ?? null,
                                                                    implode(',',(array)$reg_email),
                                                                    null,
                                                                    null,
                                                                    null
                                                                 );

                           $html = new LABAssignStaffMail($data_app);
                           $mail =  Mail::to($reg_email)->send($html);

                           if(is_null($mail) && !empty($log_email)){
                               HP::getUpdateCertifyLogEmail($log_email->id);
                           }


                   }

                }
            }
            return redirect(route('check_certificate.index'))->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }

        // } catch (\Exception $e) {
        //     return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        // }
    }

     private function save_check_examiner($check, $checker){
        $examiner = '';
        CheckExaminer::where('app_certi_lab_checks_id', $check->id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['app_certi_lab_checks_id'] = $check->id;
          $input['app_certi_lab_id'] = $check->app_certi_lab_id;
          $input['user_id'] = $item;
         $examiner =     CheckExaminer::create($input);
        }
        return  $examiner;
      }
      private function save_assessments_examiner($app, $checker){
        AssessmentExaminer::where('app_certi_lab_assessments_id', $app->id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['app_certi_lab_id'] = $app->id;
          $input['user_id'] = $item;
          AssessmentExaminer::create($input);
        }
      }

      public function update_payin1(Request $request, $id)
      {

             $previousUrl = app('url')->previous();
             $find_cost_assessment  =  CostAssessment::findOrFail($id);
             $certi_lab  =  CertiLab::findOrFail($find_cost_assessment->app_certi_lab_id);

            if(!empty($find_cost_assessment->assessment->board_auditor_to)){
                $auditor  =  $find_cost_assessment->assessment->board_auditor_to;

                $find_cost_assessment->date_board_auditor  =    !empty($auditor->DataBoardAuditorDateMail) ?  $auditor->DataBoardAuditorDateMail : null;
                if(!is_null($find_cost_assessment->conditional_type)){
                    $find_cost_assessment->amount          =    !empty($auditor->amount) ?   number_format($auditor->amount,2) : null;
                }else{
                    $find_cost_assessment->amount          =    !empty($auditor->SumCostItemConFirm) ?   number_format($auditor->SumCostItemConFirm,2) : null;
                }
                $find_cost_assessment->sum_cost            =    !empty($auditor->SumCostItemConFirm) ?   number_format($auditor->SumCostItemConFirm,2) : null;
                $find_cost_assessment->auditor             =    !empty($auditor->auditor) ?  $auditor->auditor : null;
                $find_cost_assessment->cost_item_confirm   =    !empty($auditor->cost_item_confirm) ?  $auditor->cost_item_confirm : null;

                $start_date      = $auditor->StartDateBoardAuditor->start_date ?? null;
                if(!is_null($start_date)){
                    $feewaiver       =   Feewaiver::where('certify',1)
                                                    ->where('payin1_status',1)
                                                    ->whereDate('payin1_start_date','<=',$start_date)
                                                    ->whereDate('payin1_end_date','>=',$start_date)
                                                    ->first();
                    if(!is_null($feewaiver)){
                        $find_cost_assessment->conditional = 2; // เรียกเก็บค่าธรรมเนียม
                    }else{
                        $find_cost_assessment->conditional = 1; // ยกเว้นค่าธรรมเนียม
                    }


                }else{

                    $feewaiver = null;
                }


            }else{
                $feewaiver = null;
            }

             $attach_path = $this->attach_path;//path ไฟล์แนบ

             return view('certify.check_certificate_lab.pay_in_one',  compact('previousUrl',
                                                                                 'find_cost_assessment',
                                                                                 'certi_lab',
                                                                                 'attach_path',
                                                                                 'feewaiver'
                                                                               ));
      }

    public function DataPayIn(Request $request,$id)
    {
            // dd($request->all(),$id);
            $arrContextOptions=array();
            $attach_path            =  $this->attach_path ;
            $find_cost_assessment   =  CostAssessment::findOrFail($id);  //  ตารางธรรรมเนียม
            $ao                     = new CostAssessment;
            // dd($find_cost_assessment);
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
    // try {

      
        
        if(!is_null($find_cost_assessment) && !is_null($find_cost_assessment->app_certi_lab_id)){
            
            $certi_lab = CertiLab::findOrFail($find_cost_assessment->app_certi_lab_id); // ตารางใบสมัคร
            $conditional_type = $request->conditional_type;
            // $conditional_type = 1;

            
            if($find_cost_assessment->state == null){
                
                        $find_cost_assessment->conditional_type         = $conditional_type;
                        $find_cost_assessment->amount                   =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):0;
                        $find_cost_assessment->amount_bill              =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):0;
                        $find_cost_assessment->state = 1;  // ส่งให้ ผปก.
                       
                 if($find_cost_assessment->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม
                        $find_cost_assessment->report_date =  isset($request->report_date)?  HP::convertDate($request->report_date,true) :null;
                        $find_cost_assessment->reporter_id = auth()->user()->runrecno;

                        // $find_cost_assessment->save();

                        $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',1)->first();

                        $app_no          =  $certi_lab->app_no;
                        
                        
                    if(!is_null($setting_payment)){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                              );
                        }
                        $timestamp = Carbon::now()->timestamp;
                        $refNo = $app_no.'-'.$find_cost_assessment->app_certi_assessment_id.$timestamp;
                        // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$app_no-$find_cost_assessment->app_certi_assessment_id", false, stream_context_create($arrContextOptions));
                        $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));

                       

                        $api = json_decode($content,false);

                    //    dd($setting_payment->data);

                    //    $host = parse_url($setting_payment->data, PHP_URL_HOST);

                    //    if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                    //        dd('No Contains IP');
                    //    } else {
                    //        dd('Contain IP');
                    //    }
                            
                        
                        // if(strpos($setting_payment->data, '127.0.0.1')===0){
                        if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                            // dd("here");
                            // dd($content,"$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo",$api);
                            $find_cost_assessment->amount_invoice =   $this->storeFilePayin($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                        }else{
                            
                            $find_cost_assessment->amount_invoice =   $this->storeFilePayinDemo($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                        }
                        // dd('break');
                        // $find_cost_assessment->amount_invoice =   $this->storeFilePayin($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);

                        $find_cost_assessment->file_client_name =   isset($find_cost_assessment->amount_invoice) ? basename($find_cost_assessment->amount_invoice)  : null;
                        
                        $find_cost_assessment->save();

                        $transacion = HP::TransactionPayIn1($find_cost_assessment->id,$ao->getTable(),'1','1',$api,$app_no.'-'.$find_cost_assessment->app_certi_assessment_id,$timestamp);
                        // dd($transacion);
                        
                     }

                 }else if($find_cost_assessment->conditional_type == 2){ // ยกเว้นค่าธรรมเนียม
                        $feewaiver  =  Feewaiver::where('certify',1)->first();
                        if(!is_null($feewaiver->payin1_file)){
                            $find_cost_assessment->amount_invoice       =  $feewaiver->payin1_file;
                            $find_cost_assessment->file_client_name     =  $feewaiver->payin1_file_client_name;
                        }
                        $find_cost_assessment->start_date_feewaiver =  $feewaiver->payin1_start_date;
                        $find_cost_assessment->end_date_feewaiver   =   $feewaiver->payin1_end_date;
                        $find_cost_assessment->reporter_id          = auth()->user()->runrecno;
                        $find_cost_assessment->save();

                        if(!is_null($find_cost_assessment->amount_invoice) &&  HP::checkFileStorage($find_cost_assessment->amount_invoice)){
                            HP::getFileStoragePath($find_cost_assessment->amount_invoice);
                        }
                    }else if($find_cost_assessment->conditional_type == 3){ // ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ
                           $find_cost_assessment->detail        = $request->detail;
                           $find_cost_assessment->reporter_id   = auth()->user()->runrecno;
                        if($request->other_attach && $request->hasFile('other_attach')){
                            $find_cost_assessment->amount_invoice =   $this->storeFile($request->other_attach,$certi_lab->app_no);
                            $find_cost_assessment->file_client_name =  HP::ConvertCertifyFileName($request->other_attach->getClientOriginalName());
                        }
                        $find_cost_assessment->save();
                    }

                    self::insert_payin_all(1,$find_cost_assessment);
                    
                    if(!is_null($find_cost_assessment->amount_invoice) && HP::checkFileStorage($attach_path.$find_cost_assessment->amount_invoice) ){
                        HP::getFileStoragePath($attach_path.$find_cost_assessment->amount_invoice);
                     }

                     $cost = CostAssessment::select('amount','report_date','app_certi_assessment_id','remark','conditional_type', 'start_date_feewaiver', 'end_date_feewaiver')->where('id',$find_cost_assessment->id)->get()->toArray();
                     CertificateHistory::create([
                                                    'app_no'            =>  $certi_lab->app_no ?? null,
                                                    'system'            =>  3,
                                                    'table_name'        => $ao->getTable(),
                                                    'ref_id'            => $find_cost_assessment->id,
                                                    'details'           =>  (count($cost) > 0) ? json_encode($cost) : null,
                                                    'attachs'           => $find_cost_assessment->amount_invoice ?? null,
                                                    'attach_client_name'=> $find_cost_assessment->file_client_name ?? null,
                                                    'created_by'        =>  auth()->user()->runrecno
                                                 ]);

                    if(!is_null($certi_lab->email)){ // แจ้งเตือนผู้ประกอบการ

                        // Mail
                        $dataMail   = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                        $EMail      =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';


                        $data_app = [
                                        'PayIn'         => $find_cost_assessment,
                                        'certi_lab'     => $certi_lab,
                                        'url'           => $url.'certify/applicant',
                                        'attachs'       => !empty($find_cost_assessment->amount_invoice)  ?  $find_cost_assessment->amount_invoice : '',
                                        'email'         => 'nsc@tisi.mail.go.th',
                                        'email_cc'      => !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  [$EMail],
                                        'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  [$EMail]
                                     ];

                         $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                                    $certi_lab->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $find_cost_assessment->id,
                                                                    (new CostAssessment)->getTable(),
                                                                    1,
                                                                    'แจ้งค่าบริการในการตรวจประเมิน',
                                                                    view('mail.Lab.pay_in_one', $data_app),
                                                                    $certi_lab->created_by,
                                                                    $certi_lab->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    'nsc@tisi.mail.go.th',
                                                                    $certi_lab->email,
                                                                    !empty($certi_lab->DataEmailDirectorLABCC) ? implode(',',(array)$certi_lab->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($certi_lab->DataEmailDirectorLABReply) ? implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :  $EMail,
                                                                    !empty($find_cost_assessment->amount_invoice) ?  'certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ) : null
                                                                 );

                           $html = new CertifyPayIn1($data_app);
                           $mail =  Mail::to($certi_lab->email)->send($html);

                           if(is_null($mail) && !empty($log_email)){
                               HP::getUpdateCertifyLogEmail($log_email->id);
                           }

                    }

             }else{
                // dd('check point save pay-in1:',$find_cost_assessment ,$request->conditional_type,$find_cost_assessment->state,$request->status_confirmed,$request->ReceiptCreateDate,$request->condition_pay,$find_cost_assessment->assessment->auditor_id);
                    if($request->status_confirmed == 1){
                        $find_cost_assessment->remark           =  null;
                        $find_cost_assessment->state            = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                        $find_cost_assessment->status_confirmed = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                    }else{
                        $find_cost_assessment->state            = 1;  // ส่งให้ ผปก.
                        $find_cost_assessment->remark           = $request->remark ?? null;
                        $find_cost_assessment->status_confirmed = 0;
                    }
                    
                    $find_cost_assessment->condition_pay     =  !empty($request->condition_pay) ?  $request->condition_pay : null ; 
                    $find_cost_assessment->save();
                
                    if(!empty($request->ReceiptCreateDate)){
                        $transaction_payin  =  TransactionPayIn::where('ref_id',$find_cost_assessment->id)->where('table_name', (new CostAssessment)->getTable())->orderby('id','desc')->first();
                        if(!is_null($transaction_payin)){
                            $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                            $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                            $transaction_payin->save();
                        }
                    }

                        // dd($find_cost_assessment->assessment->auditor_id);

                    if(!empty($find_cost_assessment->assessment->auditor_id)){
                        // สถานะ แต่งตั้งคณะกรรมการ
                        $auditor = BoardAuditor::findOrFail($find_cost_assessment->assessment->auditor_id);
                        if(!is_null($auditor)){
                            if($find_cost_assessment->state == 3){
                                $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                            }else{
                                $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                            }
                            $auditor->save();
                        }
                    }

                    $cost = CostAssessment::select('amount','report_date','app_certi_assessment_id','remark','conditional_type', 'start_date_feewaiver', 'end_date_feewaiver','detail','status_confirmed','condition_pay')->where('id',$find_cost_assessment->id)->get()->toArray();
                    $History = CertificateHistory::create([
                                                   'app_no'            =>  $certi_lab->app_no ?? null,
                                                   'system'            =>  3,
                                                   'table_name'        =>  $ao->getTable(),
                                                   'status'            =>  $find_cost_assessment->status_confirmed ?? null,
                                                   'ref_id'            =>  $find_cost_assessment->id,
                                                   'details'           =>  (count($cost) > 0) ? json_encode($cost) : null,
                                                   'attachs'           =>  $find_cost_assessment->amount_invoice ?? null,
                                                   'attach_client_name'=>  $find_cost_assessment->file_client_name ?? null,
                                                   'attachs_file'      =>  $find_cost_assessment->invoice ?? null,
                                                   'evidence'          =>  $find_cost_assessment->invoice_client_name ?? null,
                                                   'created_by'        =>  auth()->user()->runrecno
                                                 ]);
                        // Mail
                       $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                       $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';
                       if($find_cost_assessment->status_confirmed == 1){

                           if(isset($certi_lab->check) && count($certi_lab->check->EmailStaffAssign) > 0 ){ // แจ้งเตือนเจ้าหน้าที่รับผิดชอบ

                                $data_app = [
                                                'email'         =>  'nsc@tisi.mail.go.th',
                                                'PayIn'         => $find_cost_assessment,
                                                'certi_lab'     => $certi_lab,
                                                'email'         => !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                                'email_cc'      => !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  [$EMail],
                                                'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  [$EMail]
                                            ];
                     
                                $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                                            $certi_lab->id,
                                                                            (new CertiLab)->getTable(),
                                                                            $find_cost_assessment->id,
                                                                            (new CostAssessment)->getTable(),
                                                                            1,
                                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                                            view('mail.Lab.inform_pay_in_one', $data_app),
                                                                            $certi_lab->created_by,
                                                                            $certi_lab->agent_id,
                                                                            auth()->user()->getKey(),
                                                                            'nsc@tisi.mail.go.th',
                                                                            !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                                                            !empty($certi_lab->DataEmailDirectorLABCC) ? implode(',',(array)$certi_lab->DataEmailDirectorLABCC)   :  $EMail,
                                                                            !empty($certi_lab->DataEmailDirectorLABReply) ? implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :  $EMail,
                                                                            null
                                                                        );

                                $html = new CertifyConfirmedPayIn1($data_app);
                                $mail =  Mail::to($certi_lab->check->EmailStaffAssign)->send($html);

                                if(is_null($mail) && !empty($log_email)){
                                     HP::getUpdateCertifyLogEmail($log_email->id);
                                }

                             }
                       }else{

                           if(!is_null($certi_lab->email)){ // แจ้งเตือนผู้ประกอบการ
                                $dataMail   = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                                $EMail      =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';
                                $data_app = [
                                            'email'         =>  'nsc@tisi.mail.go.th',
                                            'PayIn'         => $find_cost_assessment,
                                            'certi_lab'     => $certi_lab,
                                            'url'           => $url.'certify/applicant',
                                            'attachs'       => !empty($find_cost_assessment->amount_invoice)  ?  $find_cost_assessment->amount_invoice : '',
                                            'email_cc'      => !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  $EMail,
                                            'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                                            ];

                                $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                                            $certi_lab->id,
                                                                            (new CertiLab)->getTable(),
                                                                            $find_cost_assessment->id,
                                                                            (new CostAssessment)->getTable(),
                                                                            1,
                                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                                            view('mail.Lab.pay_in_one', $data_app),
                                                                            $certi_lab->created_by,
                                                                            $certi_lab->agent_id,
                                                                            auth()->user()->getKey(),
                                                                            'nsc@tisi.mail.go.th',
                                                                            $certi_lab->email,
                                                                            !empty($certi_lab->DataEmailDirectorLABCC) ? implode(',',(array)$certi_lab->DataEmailDirectorLABCC)   :  $EMail,
                                                                            !empty($certi_lab->DataEmailDirectorLABReply) ? implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :  $EMail,
                                                                            !empty($find_cost_assessment->amount_invoice) ?  'certify/check/file_client/'.$find_cost_assessment->amount_invoice.'/'.( !empty($find_cost_assessment->file_client_name) ? $find_cost_assessment->file_client_name :   basename($find_cost_assessment->amount_invoice) ) : null
                                                                        );

                                $html = new CertifyPayIn1($data_app);
                                $mail =  Mail::to($certi_lab->email)->send($html);

                                if(is_null($mail) && !empty($log_email)){
                                    HP::getUpdateCertifyLogEmail($log_email->id);
                                }
                            }
                       }



             }

            if(!is_null($certi_lab->check->id)){
                return redirect('certify/check_certificate/'. $certi_lab->check->id .'/show')->with('flash_message', 'บันทึกเรียบร้อย');
            }else{
                return redirect('certify/check_certificate')->with('flash_message', 'บันทึกเรียบร้อย');
            }

        }

    // } catch (\Exception $e) {
    //     return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // }
    }


    public function DataStatusConfirmed(Request $request,$id)
    {

    try {
        $assessmenapp_certits  =  CostAssessment::findOrFail($id);  //  ตารางธรรรมเนียม
        $certi_lab = CertiLab::findOrFail($assessmenapp_certits->app_certi_lab_id);
        $ao = new CostAssessment;
        if(!is_null($certi_lab)  && !is_null($assessmenapp_certits)){
            if($request->assessmen_status == 1){
                $certi_lab->update(['status'=> 17]); // ยืนยันการชำระเงินค่าตรวจประเมิน
                $assessmenapp_certits->update(['status_confirmed'=> 1, 'detail'=> null]);
            }else{
                $certi_lab->update(['status'=> 15]); // แจ้งรายละเอียดค่าตรวจประเมิน
                $assessmenapp_certits->update(['status_confirmed'   => 0,
                                                'invoice'           =>'',
                                                'detail'            => $request->detail ?? null
                                             ]);
            }

             $Cost = CostAssessment::select('amount','report_date','app_certi_assessment_id','remark','conditional_type', 'start_date_feewaiver', 'end_date_feewaiver','detail','status_confirmed')->where('id',$assessmenapp_certits->id)->get()->toArray();
             $History = CertificateHistory::create([
                                            'app_no'            =>  $certi_lab->app_no ?? null,
                                            'system'            =>  3,
                                            'table_name'        =>  $ao->getTable(),
                                            'status'            =>  $assessmenapp_certits->status_confirmed ?? null,
                                            'ref_id'            =>  $assessmenapp_certits->id,
                                            'details'           =>  (count($Cost) > 0) ? json_encode($Cost) : null,
                                            'attachs'           =>  $assessmenapp_certits->amount_invoice ?? null,
                                            'attach_client_name'=>  $assessmenapp_certits->file_client_name ?? null,
                                            'attachs_file'      =>  $find_cost_assessment->invoice ?? null,
                                            'evidence'          =>  $find_cost_assessment->invoice_client_name ?? null,
                                            'created_by'        =>  auth()->user()->runrecno
                                          ]);
              // Mail
              $config = HP::getConfig();
              $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';
                if($assessmenapp_certits->status_confirmed == 1){
                    if(isset($certi_lab->check) && count($certi_lab->check->EmailStaffAssign) > 0 ){ // แจ้งเตือนเจ้าหน้าที่รับผิดชอบ
                            $mail = new  CertifyConfirmedPayIn1([
                                                                'email'         =>  'nsc@tisi.mail.go.th',
                                                                'PayIn'         => $assessmenapp_certits,
                                                                'certi_lab'     => $certi_lab,
                                                                'email'         => !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                                                'email_cc'      => !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  $EMail,
                                                                'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                                                               ]);
                         Mail::to($certi_lab->check->EmailStaffAssign)->send($mail);
                      }
                }else{

                    if(!is_null($certi_lab->email)){ // แจ้งเตือนผู้ประกอบการ
                      $mail = new  CertifyPayIn1([
                                                    'email'         => 'nsc@tisi.mail.go.th',
                                                    'PayIn'         => $assessmenapp_certits,
                                                    'certi_lab'     => $certi_lab,
                                                    'url'           => $url.'certify/applicant' ?? '-',
                                                    'attachs'       => $assessmenapp_certits->amount_invoice ?? '',
                                                    'email'         =>  !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                                    'email_cc'      =>  !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  $EMail,
                                                    'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                                                 ]);
                        Mail::to($certi_lab->email)->send($mail);
                     }
                }

        }

        if(!is_null($certi_lab->id)){
            return redirect('certify/check_certificate/'. $certi_lab->id .'/show')->with('flash_message', 'บันทึกเรียบร้อย');
        }else{
            return redirect(route('check_certificate.index'))->with('flash_message', 'บันทึกเรียบร้อย');
        }

    } catch (\Exception $e) {
        return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    }
 }

    public function ReportAssessments(Request $request,$id)
    {


        // dd($request->all());

    //  try {
        $report = Report::findOrFail($id);

        if(!is_null($report)){

            $CertiLab = CertiLab::where('id',$report->app_certi_lab_id)->first();

            $report->update([
                            'meet_date'             => $request->meet_date?Carbon::createFromFormat("d/m/Y",$request->meet_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null,
                            'desc'                  => $request->desc??null,
                            'save_date'             => date('Y-m-d'),
                            'status'                => $request->report_status??2,
                            'file_loa'              => ($request->file_loa  && $request->hasFile('file_loa'))  ? $this->storeFile($request->file_loa,$CertiLab->app_no) : @$report->file_loa,
                            'file_loa_client_name'  => ($request->file_loa  && $request->hasFile('file_loa'))  ?  HP::ConvertCertifyFileName($request->file_loa->getClientOriginalName()) : @$report->file_loa_client_name,
                            'start_date'            => !empty($request->start_date)?HP::convertDate($request->start_date, true):null,
                            'end_date'              => !empty($request->end_date)?HP::convertDate($request->end_date, true):null,
                            'created_by'            => auth()->user()->runrecno
                          ]);

                if ($request->hasFile('file')) {
                    foreach ($report->files as $file) {
                        File::delete(storage_path($file->file));
                        $file->delete();
                    }

                    $files = $request->file('file');
                    $names = $request->input('file_desc');
                    foreach ($files as $key => $file) {
                        $file_desc = $names[$key] ?? null;
                        $input = [
                            'app_certi_report_assessment_id' => $report->id,
                            'file_desc'                      => $file_desc,
                            'file'                           => $this->storeFile($file,$CertiLab->app_no),
                            'file_client_name'               =>  HP::ConvertCertifyFileName($file->getClientOriginalName()),
                            'created_by'                     => auth()->user()->runrecno,
                        ];

                        if (!ReportFile::create($input)) {
                            return $this->error();
                        }
                    }
                }
                 //ประวัติการบันทึก สรุปรายงานและเสนออนุกรรมการฯ
                  $data_report = Report::select('meet_date','status','desc','save_date','start_date','end_date', 'created_by')
                                            ->where('id',$report->id)
                                            ->orderby('id','desc')
                                            ->first();
                  $report_file  = ReportFile::select('file_desc','file','file_client_name')
                                            ->where('app_certi_report_assessment_id',$report->id)
                                            ->get()
                                            ->toArray();



                // $user =   User::where('runrecno',auth()->user()->runrecno)->first();

                // CertLabsFileAll::where('app_certi_lab_id',$CertiLab->id)->update([
                //     'start_date' => !empty($request->start_date)?HP::convertDate($request->start_date):null,
                //     'end_date' => !empty($request->end_date)?HP::convertDate($request->end_date):null,
                // ]);

                if(!is_null($CertiLab) && $report->status ==1){

                   $CertiLab->update([
                                      'status'                    => 21, // update สถานะ attach_client_name
                                      'attach_pdf'                => $report->file_loa ?? '',  // แนบท้าย ที่ใช้งาน
                                      'attach_pdf_client_name'    =>  $report->file_loa_client_name ?? '',
                                     ]);
                     //   ประวัติการแนบไฟล์ แนบท้าย
                    if(!is_null($CertiLab->attach_pdf)){
                        if($CertiLab->purpose_type > 1){  //   ต่ออายุใบรับรอง , ขยายขอบข่าย , การเปลี่ยนแปลงมาตรฐาน
                            $certilab_id =  !empty($CertiLab->certificate_export_to2->certificate_for) ? $CertiLab->certificate_export_to2->certificate_for : $CertiLab->id;
                        }else{ // ยื่นขอครั้งแรก
                            $certilab_id =  $CertiLab->id;
                        }
                        CertLabsFileAll::where('app_certi_lab_id',$certilab_id)->update(['state' => 0]);
                        CertLabsFileAll::create([
                                                'ref_id'                    =>  $report->id,
                                                'ref_table'                 =>  (new Report)->getTable(),
                                                'app_certi_lab_id'          =>  $certilab_id,
                                                'app_no'                    =>  $CertiLab->app_no,
                                                'attach_pdf'                =>  $CertiLab->attach_pdf ?? null,
                                                'attach_pdf_client_name'    =>  $CertiLab->attach_pdf_client_name ?? null,
                                                'start_date'                =>  $report->start_date ?? null,
                                                'end_date'                  =>  $report->end_date ?? null,
                                                'state' => 1
                                              ]);
                    }

                   $ao = new Report;
                   CertificateHistory::create([
                                                'app_no'            => $CertiLab->app_no ?? null,
                                                'system'            => 5,
                                                'table_name'        => $ao->getTable(),
                                                'ref_id'            => $report->id,
                                                'details'           => json_encode($data_report) ?? null,
                                                'file'              => $report->file_loa ?? null,
                                                'file_client_name'  => $report->file_loa_client_name ?? null,
                                                'attachs'           => (count($report_file) > 0) ? json_encode($report_file) : null,
                                                'created_by'        =>  auth()->user()->runrecno
                                               ]);

                   //   Pay-in ครั้งที่ 2
                    $costcerti  = CostCertificate::where('app_certi_lab_id',$CertiLab->id)->first();
                   if(is_null($costcerti)){
                      $costcerti = new CostCertificate;
                   }
                    $costcerti->app_certi_assessment_id = $CertiLab->assessment->id ?? null;
                    $costcerti->app_certi_lab_id = $CertiLab->id;
                    $costcerti->save();

                    $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                    $EMail =  array_key_exists($CertiLab->subgroup,$dataMail)  ? $dataMail[$CertiLab->subgroup] :'admin@admin.com';
                    //Mail
                    $config = HP::getConfig();
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                    $data_app = [
                                    'email'=>  auth()->user()->email ?? 'admin@admin.com',
                                    'report' => $report,
                                    'report_file' => (count($report_file) > 0) ? json_encode($report_file)  : null,
                                    'certi_lab'=> $CertiLab,
                                    'full_name'=> $CertiLab->FullRegName ?? '-',
                                    'url' => $url.'certify/applicant' ?? '-',
                                    'email'=>  !empty($CertiLab->DataEmailCertifyCenter) ? $CertiLab->DataEmailCertifyCenter : $EMail,
                                    'email_cc'=>  !empty($CertiLab->DataEmailDirectorLABCC) ? $CertiLab->DataEmailDirectorLABCC :  $EMail,
                                    'email_reply' => !empty($CertiLab->DataEmailDirectorLABReply) ? $CertiLab->DataEmailDirectorLABReply :  $EMail
                             ];

                   $log_email =  HP::getInsertCertifyLogEmail( $CertiLab->app_no,
                                                            $CertiLab->id,
                                                            (new CertiLab)->getTable(),
                                                            $report->id,
                                                            (new Report)->getTable(),
                                                            1,
                                                            'สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ',
                                                            view('mail.Lab.request', $data_app),
                                                            $CertiLab->created_by,
                                                            $CertiLab->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($CertiLab->DataEmailCertifyCenter) ?  implode(',',(array)$CertiLab->DataEmailCertifyCenter)  : $EMail,
                                                            $CertiLab->email,
                                                            !empty($CertiLab->DataEmailDirectorLABCC) ? implode(',',(array)$CertiLab->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($CertiLab->DataEmailDirectorLABReply) ?implode(',',(array)$CertiLab->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                        );

                    $html = new CertifyReport($data_app);
                    $mail =  Mail::to($CertiLab->email)->send($html);

                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }

                }else{
                    $ao = new Report;
                    CertificateHistory::create([
                                     'app_no'=> $CertiLab->app_no ?? null,
                                     'system'=>5,
                                     'table_name'=> $ao->getTable(),
                                     'ref_id'=> $report->id,
                                     'details'=>    json_encode($data_report) ?? null,
                                     'file'=>    $report->file_loa ?? null,
                                     'attachs'=> (count($report_file) > 0) ? json_encode($report_file) : null,
                                     'created_by' =>  auth()->user()->runrecno
                                   ]);
                }
        }

        if(!is_null($request->id)){
            return redirect('certify/check_certificate/'. $request->id .'/show')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }else{
            return redirect('certify/check_certificate')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }
    // } catch (\Exception $e) {
    //     return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // }

    }

    public function GetPayInTwo($id = null,$token = null)
    {
       $previousUrl = app('url')->previous();
       $payin2  =  CostCertificate::findOrFail($id);
    //    dd($payin2);
       $attach_path =  $this->attach_path ;
        $feewaiver  =  Feewaiver::where('certify',1)
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


       return view('certify/check_certificate_lab.pay_in_two', compact('previousUrl',
                                                                         'payin2',
                                                                         'attach_path',
                                                                         'feewaiver'
                                                                        ));
    }

    public function storeFilePayinDemo($setting_payment, $app_no = 'files_lab', $auditor_id = '')
    {
       $baseUrl = strstr($setting_payment->data, '/api', true);

       $url = $baseUrl. '/images/Payin2.pdf';

       // ดาวน์โหลดเนื้อหา PDF (Demo)
       $pdf_content = file_get_contents($url);

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

    public function storeFilePayinDemo_notuse($setting_payment, $app_no = 'files_lab', $auditor_id = '')
    {

        $baseUrl = strstr($setting_payment->data, '/api', true);

        $url = $baseUrl. '/images/Payin2.pdf';

        // ดาวน์โหลดเนื้อหา PDF (Demo)
        $pdf_content = file_get_contents($url);
  
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
    public function storeFilePayin($setting_payment, $app_no = 'files_lab', $auditor_id = '')
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

    public function CostCertificate(Request $request,$id)
    {
      
        try {
            $conditional_type = $request->conditional_type;
            $arrContextOptions = array();
            $costcerti = CostCertificate::findOrFail($id);
            $ao = new CostCertificate;
            $attach_path =  $this->attach_path ;
            
            if(!is_null($costcerti)){
                        $CertiLab = CertiLab::findOrFail($costcerti->app_certi_lab_id);
                        $costcerti->conditional_type = $conditional_type;
                        $costcerti->notification_date   =  date('Y-m-d');
                        $costcerti->created_by          =  auth()->user()->runrecno;
                if($costcerti->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม
                    // dd($request->all());
                    $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',1)->first();
                    if(!is_null($setting_payment)){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                              );
                        }
                        $timestamp = Carbon::now()->timestamp;
                        $refNo = $CertiLab->app_no.'-'.$timestamp;

                        $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));
                        // dd($content);

                        $api = json_decode($content);
                        
                        // $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);


                        // if(strpos($setting_payment->data, '127.0.0.1')===0){
                        if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                            
                            $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);
                        }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                            
                            $costcerti->attach              =   $this->storeFilePayinDemo($setting_payment,$CertiLab->app_no);
                        }




                        $costcerti->attach_client_name  =   basename($costcerti->attach);
                        $costcerti->amount_fixed        =   1000;
                        $costcerti->amount_fee          =  !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                        $costcerti->save();
                        
                        $transaction = HP::TransactionPayIn2($id,$ao->getTable(),'1','2',$api,$timestamp);
                        // dd($api);
                        if(HP::checkFileStorage($attach_path.$costcerti->attach)){
                            HP::getFileStoragePath($attach_path.$costcerti->attach);
                        }
                   }
                }else  if($costcerti->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                        $feewaiver  =  Feewaiver::where('certify',1)->first();
                        if(!is_null($feewaiver->payin2_file)){
                            $costcerti->attach                  =  $feewaiver->payin2_file;
                            $costcerti->attach_client_name      =  $feewaiver->payin2_file_client_name;
                        }
                        $costcerti->start_date_feewaiver    =  $feewaiver->payin2_start_date;
                        $costcerti->end_date_feewaiver      =  $feewaiver->payin2_end_date;
                        $costcerti->save();

                    if(!is_null($costcerti->attach) && HP::checkFileStorage($costcerti->attach)){
                        HP::getFileStoragePath($costcerti->attach);
                    }
                }else  if($costcerti->conditional_type == 3){  // ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
                        $costcerti->remark = $request->remark ?? null;
                    if($request->attach && $request->hasFile('attach')){
                        $costcerti->attach              =   $this->storeFile($request->attach,$CertiLab->app_no);
                        $costcerti->attach_client_name  =  HP::ConvertCertifyFileName($request->attach->getClientOriginalName());
                    }
                        $costcerti->save();

                        if(!is_null($costcerti->attach) && HP::checkFileStorage($attach_path.$costcerti->attach)){
                            HP::getFileStoragePath($attach_path.$costcerti->attach);
                        }
                }


                  $CertiLab->status =  23;  //แจ้งรายละเอียดการชำระค่าใบรับรอง
                  $CertiLab->save();
                  self::insert_payin_all(2,$costcerti);

               if(!is_null($CertiLab)){
                     $Report = Report::where('app_certi_lab_id',$CertiLab->id)->orderby('id','desc')->first();
                     if(!is_null($Report)){
                         $Report->update(['status_alert' => 2]);
                     }
                    //log
                     $detail_costcerti = CostCertificate::select('amount','amount_fee','notification_date','detail','start_date_feewaiver','end_date_feewaiver','remark','conditional_type')->where('id',$costcerti->id)->orderby('id','desc')->first();
                     CertificateHistory::create([
                                                    'app_no'            => $CertiLab->app_no ?? null,
                                                    'system'            =>6,
                                                    'table_name'        => $ao->getTable(),
                                                    'ref_id'            => $costcerti->id,
                                                    'details'           =>  json_encode($detail_costcerti) ?? null,
                                                    'attachs'           =>  $costcerti->attach ?? null,
                                                    'attach_client_name'=>  $costcerti->attach_client_name ?? null,
                                                    'created_by'        =>  auth()->user()->runrecno
                                               ]);

                   if(!is_null($CertiLab->email)){
                            // ส่ง E-mail
                            $config = HP::getConfig();
                            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                            $EMail =  array_key_exists($CertiLab->subgroup,$dataMail)  ? $dataMail[$CertiLab->subgroup] :'admin@admin.com';
                            $data_app = [
                                        'email'         => 'nsc@tisi.mail.go.th',
                                        'PayIn'         => $costcerti,
                                        'CertiLab'      => $CertiLab,
                                        'attach'        =>  !empty($costcerti->attach) ? $costcerti->attach : '',
                                        'url'           => $url.'certify/applicant'  ,
                                        'email_cc'      => !empty($CertiLab->DataEmailDirectorLABCC) ? $CertiLab->DataEmailDirectorLABCC :  $EMail,
                                        'email_reply'   => !empty($CertiLab->DataEmailDirectorLABReply) ? $CertiLab->DataEmailDirectorLABReply :  $EMail
                                     ];

                           $log_email =  HP::getInsertCertifyLogEmail( $CertiLab->app_no,
                                                                    $CertiLab->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $costcerti->id,
                                                                    (new CostCertificate)->getTable(),
                                                                    1,
                                                                    'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                                    view('mail.Lab.pay_in_two', $data_app),
                                                                    $CertiLab->created_by,
                                                                    $CertiLab->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    'nsc@tisi.mail.go.th',
                                                                    $CertiLab->email,
                                                                    !empty($CertiLab->DataEmailDirectorLABCC) ?  implode(',',(array)$CertiLab->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($CertiLab->DataEmailDirectorLABReply) ? implode(',',(array)$CertiLab->DataEmailDirectorLABReply)   :  $EMail,
                                                                    !empty($costcerti->attach) ?   'certify/check/file_client/'. $costcerti->attach .'/'. $costcerti->attach_client_name : null
                                                                );

                            $html = new CertifyCostCertificate($data_app);
                            $mail =  Mail::to($CertiLab->email)->send($html);

                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }
                    }





                 }
             }

        if(!empty($CertiLab->check->id)){
            return redirect('certify/check_certificate/'. $CertiLab->check->id .'/show')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }else{
            return redirect('certify/check_certificate')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }

        } catch (\Exception $e) {
            return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }
    }

    public function ReceiveCertificate(Request $request, $id)
    {
      
        
        try {
            $costcerti =   CostCertificate::findOrFail($id);
            
            if(!is_null($costcerti)){
                $CertiLab = CertiLab::where('id',$costcerti->app_certi_lab_id)->first();
                $this->save_certilab_export_mapreq( $CertiLab );
                // dd($request->all(),$CertiLab );
                 $attach =  $costcerti->attach ?? null ;//
                 $invoice =  $costcerti->invoice ?? null ;//

                if($request->status_confirmed == 1)
                {
                    $costcerti->update([
                                        'status_confirmed'  =>  1 ?? 2,
                                        'detail'            =>  null,
                                        'condition_pay'     =>   !empty($request->condition_pay) ?  $request->condition_pay : null,
                                      ]);
 
                        if(!empty($request->ReceiptCreateDate)){
                            $transaction_payin  =  TransactionPayIn::where('ref_id',$costcerti->id)->where('table_name', (new CostCertificate)->getTable())->orderby('id','desc')->first();
                            if(!is_null($transaction_payin)){
                                $transaction_payin->ReceiptCreateDate     =  !empty($request->ReceiptCreateDate) ?  HP::convertDate($request->ReceiptCreateDate,true) : null ; 
                                $transaction_payin->ReceiptCode           =  !empty($request->ReceiptCode) ?  $request->ReceiptCode : null ; 
                                $transaction_payin->save();
                            }
                        }           
                        if($CertiLab->purpose_type == 1 || (!is_null($CertiLab) && is_null($CertiLab->certificate_export_to2)) ){ // ขอใบรับรอง
                            $CertiLab->update([ 'status' =>25 ]);   // ยืนยันการชำระเงินค่าใบรับรอง
                        }else{
                             $CertiLab->update([ 'status' =>28 ]);   // ออกใบรับรอง และ ลงนาม
                        }
                        
                        // เงื่อนไขเช็คมีใบรับรอง 
                        $this->save_certilab_export_mapreq( $CertiLab );

                }
                else
                {
                    $CertiLab->update([  'status' =>23]); //แจ้งรายละเอียดการชำระค่าใบรับรอง
                     $costcerti->update([
                                         'status_confirmed'=> 2,
                                         'invoice'=> null,
                                         'detail'=> $request->detail ?? null,
                                      ]);


                         // Mail  ผู้ประกอบการ +  ผก.
                        if(!is_null($CertiLab->email)){

                            $config = HP::getConfig();
                            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

                            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                            $EMail =  array_key_exists($CertiLab->subgroup,$dataMail)  ? $dataMail[$CertiLab->subgroup] :'admin@admin.com';
                            $data_app = [
                                            'email'         => 'nsc@tisi.mail.go.th',
                                            'PayIn'         => $costcerti,
                                            'CertiLab'      => $CertiLab,
                                            'attach'        => $attach,
                                            'url'           => $url.'certify/applicant' ,
                                            'email_cc'      =>  !empty($CertiLab->DataEmailDirectorLABCC) ? $CertiLab->DataEmailDirectorLABCC :  $EMail,
                                            'email_reply'   => !empty($CertiLab->DataEmailDirectorLABReply) ? $CertiLab->DataEmailDirectorLABReply :  $EMail
                                        ];

                           $log_email =  HP::getInsertCertifyLogEmail($CertiLab->app_no,
                                                                    $CertiLab->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $costcerti->id,
                                                                    (new CostCertificate)->getTable(),
                                                                    1,
                                                                    'แจ้งตรวจสอบการชำระค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง',
                                                                    view('mail.Lab.pay_in_two', $data_app),
                                                                    $CertiLab->created_by,
                                                                    $CertiLab->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    'nsc@tisi.mail.go.th',
                                                                    $CertiLab->email,
                                                                    !empty($CertiLab->DataEmailDirectorLABCC) ? implode(',',(array)$CertiLab->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($CertiLab->DataEmailDirectorLABReply) ?implode(',',(array)$CertiLab->DataEmailDirectorLABReply)   :  $EMail,
                                                                    !empty($costcerti->attach) ?  'certify/check/file_client/'. $costcerti->attach .'/'. $costcerti->attach_client_name : null
                                                                );

                            $html = new CertifyCostCertificate($data_app);
                            $mail =  Mail::to($CertiLab->email)->send($html);

                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }

                        }

                }

                 $ao = new CostCertificate;
                 $detail_costcerti =   CostCertificate::select('amount','amount_fee','notification_date','detail','start_date_feewaiver','end_date_feewaiver','remark','conditional_type')
                                                ->where('id',$costcerti->id)
                                                ->orderby('id','desc')
                                                ->first();
                 CertificateHistory::create([
                                            'app_no'        => $CertiLab->app_no ?? null,
                                            'system'        => 6, // Pay-In ครั้งที่ 2
                                            'table_name'    => $ao->getTable(),
                                            'ref_id'        => $costcerti->id,
                                            'details'       =>  json_encode($detail_costcerti) ?? null,
                                            'status'        => $request->status_confirmed ?? null,
                                            'attachs'       => $attach ?? null,
                                            'attachs_file'  => $invoice ?? null,
                                            'created_by'    =>  auth()->user()->runrecno
                                         ]);
             }

        if(!empty($CertiLab->check->id)){
            return redirect('certify/check_certificate/'. $CertiLab->check->id .'/show')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }else{
            return redirect('certify/check_certificate')->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }

        } catch (\Exception $e) {
            return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }
    }


    // ไฟล์แนบท้าย
    public function UpDateAttach(Request $request)
    {
      try {
        $certi_lab = CertiLab::where('id',$request->app_certi_lab_id)->first();
        if(!is_null($certi_lab)){

             // ประวัติการแนบไฟล์ แนบท้าย
         if($request->attach  &&   $request->attach_pdf){

              CertLabsFileAll::where('app_certi_lab_id',$request->app_certi_lab_id)->update(['state' => 0]);
                $certLabs   = CertLabsFileAll::create([
                                                        'app_certi_lab_id'      =>  $certi_lab->id,
                                                        'attach'                => ($request->attach  && $request->hasFile('attach'))  ?    $this->storeFile($request->attach,$certi_lab->app_no) : null,
                                                        'attach_client_name'    => ($request->attach  && $request->hasFile('attach'))  ?  HP::ConvertCertifyFileName($request->attach->getClientOriginalName())  : null,
                                                        'attach_pdf'            => ($request->attach_pdf  && $request->hasFile('attach_pdf'))  ?  $this->storeFile($request->attach_pdf,$certi_lab->app_no)  : null,
                                                        'attach_pdf_client_name'=> ($request->attach_pdf  && $request->hasFile('attach_pdf'))  ?    HP::ConvertCertifyFileName($request->attach_pdf->getClientOriginalName()) : null,
                                                        'state' => 1
                                                    ]);
                 // แนบท้าย ที่ใช้งาน
                $certi_lab->update([
                                    'attach'                    =>   $certLabs->attach  ?? @$certi_lab->attach,
                                    'attach_pdf'                =>   $certLabs->attach_pdf  ?? @$certi_lab->attach_pdf,
                                    'attach_pdf_client_name'    =>   $certLabs->attach_pdf_client_name  ?? @$certi_lab->attach_pdf_client_name
                                 ]);

         }else{

            if($request->state){
                CertLabsFileAll::where('app_certi_lab_id',$request->app_certi_lab_id)->update(['state' => 0]);
                $certLabs = CertLabsFileAll::findOrFail($request->state);
                $certLabs->update(['state' => 1]);
                  // แนบท้าย ที่ใช้งาน
                   $certi_lab->update([
                                        'attach'                      =>   $certLabs->attach  ?? @$certi_lab->attach,
                                        'attach_pdf'                  =>   $certLabs->attach_pdf  ?? @$certi_lab->attach_pdf,
                                        'attach_pdf_client_name'      =>   $certLabs->attach_pdf_client_name  ?? @$certi_lab->attach_pdf_client_name
                                     ]);
            }

         }




            if(!is_null($request->id)){
                return redirect('certify/check_certificate/'. $request->id .'/show')->with('flash_message', 'มอบหมายงานเรียบร้อย');
            }else{
                return redirect('certify/check_certificate')->with('flash_message', 'มอบหมายงานเรียบร้อย');
            }
        }
         return redirect('certify/check_certificate')->with('flash_message', 'มอบหมายงานเรียบร้อย');

        } catch (\Exception $e) {
            return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }

    }


    public function show(Check $cc)
    {
        // dd('helo');
        // $checking_list = [
        //     2 => 'อยู่ระหว่างการตรวจสอบ',
        //     3 => 'ขอเอกสารเพิ่มเติม',
        //     5 => 'ไม่ผ่านการตรวจสอบ',
        //     6 => 'ผ่านการตรวจสอบ',
        //     7 => 'รอชำระค่าธรรมเนียม',
        //     9 => 'รับคำขอ',
        // ];
        //การประมาณการค่าใช้จ่าย
        $Cost      = Cost::where('app_certi_lab_id',$cc->app_certi_lab_id)->orderByDesc('id')->first();
        $certi_lab = CertiLab::where('id',$cc->app_certi_lab_id)->first();


        if($certi_lab->status  ==  7 &&  !empty($certi_lab->certi_auditors_many->where('status','1'))  &&  count($certi_lab->certi_auditors_many->where('status','1')) > 0){
            foreach($certi_lab->certi_auditors_many->where('status','1') as $auditor){
                $assessment = Assessment::where('app_certi_lab_id',$certi_lab->id)->where('auditor_id',$auditor->id)->first();
                if(!is_null($assessment)){
                    $cost_ass = CostAssessment::where('app_certi_lab_id',$certi_lab->id)->where('app_certi_assessment_id',$assessment->id)->first();
                    if(is_null($cost_ass)){
                        $cost_ass = new CostAssessment;
                    }
                    $cost_ass->app_certi_assessment_id  = $assessment->id  ?? null;
                    $cost_ass->app_certi_lab_id         = $certi_lab->id;
                    $cost_ass->save();
                }
            }
        }

        $status_cancel        = $certi_lab->certi_auditors->pluck('status_cancel')->toArray();
        $find_cost_assessment = CostAssessment::where('app_certi_lab_id',$cc->app_certi_lab_id)->orderByDesc('id')->first();
        $assessment           = Assessment::where('app_certi_lab_id', $cc->app_certi_lab_id)->orderByDesc('id')->first();
        $assessment           = is_null($assessment) ? new Assessment : $assessment;

        // สถานะหลัก
        $cc->status  = $cc->applicant->status  ?? null;
        $app_no      = "RQ-LAB";
        if(!empty($cc->applicant->app_no)){
            $appno = explode("-",$cc->applicant->app_no);
            if(count($appno) == '3'){
                $app_no =  $app_no.'-'.$appno[1].'-'.$appno[2];
            }
        }
        $history   = CertificateHistory::where('app_no',$cc->applicant->app_no)->orwhere('app_no',$app_no)->orderByDesc('id')->get();
        $feewaiver = Feewaiver::where('certify',1)->first();

        $TBApp = ( new CertiLab )->getTable();
        $TBCer = ( new CertificateExport )->getTable();
        if( !empty($certi_lab->status) && in_array(  $certi_lab->status ,[ 25, 26, 27, 28 ] ) ){

            //หาใบรับรอง
            $CheckLabCertify = DB::table("$TBApp")
                                    ->join("$TBCer", function ($join) use($TBApp, $TBCer) {
                                        $join->on("$TBCer.certificate_for", '=', "$TBApp.id");
                                    })
                                    ->where(["$TBApp.created_by" =>  $certi_lab->created_by, "$TBApp.standard_id" => $certi_lab->standard_id, "$TBApp.lab_type" => $certi_lab->lab_type])
                                    ->WhereNotIn( "$TBCer.status", [99] )
                                    ->select("$TBCer.id")
                                    ->orderByDesc('id')
                                    ->first();
            //ไม่พบใบรับรองให้ข้ามการ insert
            if( is_null($CheckLabCertify) || empty( $CheckLabCertify->id ) ){
                goto EndCheckMap;
            }

            //Map ใบสมัครกับใบรับรอง
            $MapCertify      = CertiLabExportMapreq::where(['app_certi_lab_id' => $cc->app_certi_lab_id, 'certificate_exports_id' =>  $CheckLabCertify->id ])->first();
            if( !is_null($CheckLabCertify) && is_null(  $MapCertify ) ){
                CertiLabExportMapreq::firstOrCreate(['app_certi_lab_id' => $cc->app_certi_lab_id, 'certificate_exports_id' =>  $CheckLabCertify->id ], ['app_certi_lab_id' => $cc->app_certi_lab_id, 'certificate_exports_id' =>  $CheckLabCertify->id ]);             
            }
            EndCheckMap:
        }

       $copiedScopes = $this->copyScopeLabFromAttachement($cc->applicant);
    //    dd($copiedScopes);


        return view('certify.check_certificate_lab.detail', compact('cc', 'Cost', 'find_cost_assessment','assessment', 'history', 'feewaiver',  'certi_lab','status_cancel','copiedScopes' ))  ;
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

    public function DataShow($id = null)
    {
        $previousUrl = app('url')->previous();
        $history  =  CertificateHistory::findOrFail($id);

        return view('certify.check_certificate_lab.history_detail',
                                                                 compact('previousUrl',
                                                                         'history'
                                                                ));
    }


    public function update(Request $request, Check $cc)
    {
        // dd($request->status);
        // $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();

        $request->validate([
            'status' => 'required|in:2,3,4,5,6,7,9,15,27',
            // 'desc' => 'required_if:status,==,3,5',
            'amount' => 'required_if:status,==,7',
        ]);
    // try {
        $status = $request->status;

        $app = $cc->applicant;
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
        $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';

        // dd($app->email,$app->DataEmailCertifyCenter,$app->DataEmailDirectorLABCC,$app->DataEmailDirectorLABReply);
        // status = 2,3,5,6
        if (in_array($status, ['3','4','5'])) { // 3. ขอเอกสารเพิ่มเติม 4. ยกเลิกคำขอ 5. ไม่ผ่านการตรวจสอบ

            $input = [
                'desc' => $request->desc,
                'report_date' => $request->save_date?Carbon::createFromFormat("d/m/Y",$request->save_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null
            ];

            if (!$cc->update($input)) {
                return $this->error();
            }

            $files = $request->file('file');
            $names = $request->name;

            if ($request->hasFile('file')) {
                $attachs = [];
                foreach ($cc->files as $file) {
                    File::delete(storage_path($file->file));
                    $file->delete();
                }
                foreach ($files as $key => $file) {
                    $file_desc = $names[$key] ?? null;
                    $input = [
                        'check_id' => $cc->id,
                        'status' => $status ?? null,
                        'file_desc' => $file_desc,
                        'file' => $this->storeFile($file, $app->app_no),
                        'file_client_name' => HP::ConvertCertifyFileName($file->getClientOriginalName()),
                        'created_by' => auth()->user()->runrecno,
                    ];
                    $check_file =   CheckFile::create($input);
                    $list  = new  stdClass;
                    $list->file =    $check_file->file ;
                    $list->file_client_name =    $check_file->file_client_name ;
                    $attachs[] = $list;
                }
            }


            if($status == 3){
                $system = 8;
                LabRequestRejectTracking::where('app_certi_lab_id',$app->id)->delete();
                $rejectTracking = new LabRequestRejectTracking();
                $rejectTracking->app_certi_lab_id = $app->id;
                $rejectTracking->date = Carbon::now(); 
                $rejectTracking->save();
            }else if($status == 4){
                $system = 9;
            }else if($status == 5){
                $system = 10;
            }


            $ao = new CertiLab;
            $History = CertificateHistory::create([
                                            'app_no'=> $app->app_no ?? null,
                                            'system'=> isset($system) ? $system : null,
                                            'table_name'=> $ao->getTable(),
                                            'status'=> $app->status ?? null,
                                            'ref_id'=> $app->id,
                                            'details'=> $cc->desc ?? null,
                                            'attachs'=> isset($attachs) ?  json_encode($attachs)   : null,
                                            'created_by' =>  auth()->user()->runrecno
                                            ]);

            $title_status =  ['3'=>'ขอเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
            $data_status =  ['3'=>'แนบเอกสารเพิ่มเติม','4'=>'ยกเลิกคำขอ','5'=>'ไม่ผ่านการตรวจสอบ'];
            if(array_key_exists($status,$title_status))
            {
                $data_app =  ['certi_lab'  => $app,
                                'desc'      =>  $request->desc ?? null,
                                'status'    =>  $status,
                                'title'     =>   $title_status[$status] ,
                                'data'      =>   array_key_exists($status,$data_status) ?$data_status[$status] : null,
                                'attachs'   =>  isset($attachs) ?  $attachs   : '-',
                                'url'       =>  $url.'certify/applicant' ,
                                'email'     =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                'email_cc'  =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                'email_reply' => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                            ];

                $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            1,
                                                            $title_status[$status],
                                                            view('mail.Lab.documents', $data_app),
                                                            $app->created_by,
                                                            $app->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                            $app->email,
                                                            !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                        );

                $html = new CertifyDocuments($data_app);
                $mail =  Mail::to($app->email)->send($html);

                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
            }
        }

        // status = 9
        else if (in_array($status, ['9'])) {

              if($app){
                $tmpPath = 'files/applicants/check_files';
                    if($app->lab_type  == 3){
                        $app->app_no =  isset($app->app_no) ?  str_replace("RQ-LAB","TEST",$app->app_no) : @$app->app_no;    // update ตัด RQ- คำขอ
                        // ตรวจสอบว่ามีโฟลเดอร์อยู่หรือไม่ ถ้าไม่มีให้สร้าง
                       
                        // if (!Storage::disk('uploads')->exists($tmpPath. '/' . $app->app_no)) {
                        //     // dd($app->app_no);
                        //     Storage::disk('uploads')->makeDirectory($tmpPath. '/' . $app->app_no);
                        // }
                    }else{
                        $app->app_no =  isset($app->app_no) ?  str_replace("RQ-LAB","CAL",$app->app_no) : @$app->app_no;    // update ตัด RQ- คำขอ
                        
                        // if (!Storage::disk('uploads')->exists($tmpPath. '/' . $app->app_no)) {
                        //     // dd($app->app_no);
                        //     Storage::disk('uploads')->makeDirectory($tmpPath. '/' . $app->app_no);
                        // }
                    }
                    $app->get_date =   date('Y-m-d h:m:s');

                    $cost = Cost::where('app_certi_lab_id',$app->id)->first();
                    if(is_null($cost)){
                        $cost = new Cost;
                        $cost->app_certi_assessment_id = $app->assessment->id ?? null;
                        $cost->app_certi_lab_id = $app->id;
                        $cost->checker_id = auth()->user()->runrecno;
                        $cost->draft = 0;
                        $cost->save();
                    }
                 }

            $input = [
                'report_date' => $request->save_date?Carbon::createFromFormat("d/m/Y",$request->save_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null
            ];


                $data_app =  [  'certi_lab' => $app,
                                'email'     => auth()->user()->reg_email ?? 'admin@admin.com',
                                'url'       => $url.'certify/applicant' ,
                                'email'     =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                'email_reply' => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                             ];

                $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            1,
                                                            'รับคำขอรับบริการ',
                                                            view('mail.Lab.request', $data_app),
                                                            $app->created_by,
                                                            $app->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                            $app->email,
                                                            !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                        );

                    $html = new CertifyRequest($data_app);
                    $mail =  Mail::to($app->email)->send($html);

                    if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                    }

                  ///save scope to pdf


            if (!$cc->update($input)) {
                return $this->error();
            }
        }

        // update status to Applicant
        if ($app) {
                $app->status = $status;
                $app->save();
            return redirect()->route('check_certificate.show', ['cc' => $cc])->with('flash_message', 'อัพเดทเรียบร้อยแล้ว');
        }



    // } catch (\Exception $e) {
    //     return redirect('certify/check_certificate')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // }

    }




    public function certificate_detail($token = null)
    {
        // dd('ok');
        $certi_lab = CertiLab::where('token',$token)->first();

        // เงื่อนไขเดิม
           $certilab_file_all    =  !empty($export->CertiLabTo->cert_labs_file_all) ?  $export->CertiLabTo->cert_labs_file_all : [];  
           $export               =  @$certi_lab->certificate_export_to2;

         // ใบรับรอง และ ขอบข่าย    
          if(!is_null($certi_lab->certi_lab_export_mapreq_to)){
                  $certificate_no =  !empty($certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no) ? $certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no : null;
                  if(!is_null($certificate_no)){
                        $export_no         =  CertificateExport::where('certificate_no',$certificate_no);
                          if(count($export_no->get()) > 0){

                            $lab_ids = [];
                            if($export_no->pluck('certificate_for')->count() > 0){
                                foreach ($export_no->pluck('certificate_for') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                       $lab_ids[] =  $item;
                                    }
                                }
                            }

                            if($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0){
                                foreach ($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                        $lab_ids[] =  $item;
                                    }
                                }
                            }

                            // ขอบข่าย
                            $file_alls =  CertLabsFileAll::whereIn('app_certi_lab_id',$lab_ids)->whereNotIn('status_cancel',[1])->get();
                            if(count($file_alls) > 0){
                                $certilab_file_all =  $file_alls;
                            }

                            // dd($certilab_file_all);
    
                        // ใบรับรอง
                        // $exports =   $export_no->WhereNotNull('attachs')->OrWhereNotNull('certificate_newfile') ->orderby('id','desc')->first();  
                        //   $exports =   $export_no->orderby('id','desc')->first();  
                         $export =    !empty($certi_lab->certi_lab_export_mapreq_to->certificate_export) ? $certi_lab->certi_lab_export_mapreq_to->certificate_export : null;
                    
                    } 
              }
          }

        return view('certify.check_certificate_lab.certificate_detail', compact('export','certi_lab','certilab_file_all' ));
    }


    public function update_document(Request $request)
    {
       
        $certi_lab = CertiLab::where('id',$request->ref_id)->first();

           $purposes            =  ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
          $attach_path            =  $this->attach_path;
          
           $lab_ids = [];
        if(!is_null($certi_lab)){
            dd($request->all());
         // ใบรับรอง และ ขอบข่าย    
          if(!is_null($certi_lab->certi_lab_export_mapreq_to)){
                  $certificate_no =  !empty($certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no) ? $certi_lab->certi_lab_export_mapreq_to->certificate_export->certificate_no : null;
                  if(!is_null($certificate_no)){
                        $export_no         =  CertificateExport::where('certificate_no',$certificate_no);
                          if(count($export_no->get()) > 0){

                     
                            if($export_no->pluck('certificate_for')->count() > 0){
                                foreach ($export_no->pluck('certificate_for') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                       $lab_ids[] =  $item;
                                    }
                                }
                            }

                            if($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->count() > 0){
                                foreach ($certi_lab->certi_lab_export_mapreq_to->certilab_export_mapreq_group_many->pluck('app_certi_lab_id') as $item) {
                                    if(!in_array($item,$lab_ids)){
                                        $lab_ids[] =  $item;
                                    }
                                }
                            }

                            // ขอบข่าย
                           CertLabsFileAll::whereIn('app_certi_lab_id',$lab_ids)->update(['state' => '0']);
                    } 
              }
          }else{
              CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)->update(['state' => '0']);
          }

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
            $requestData['app_certi_lab_id']   = $certi_lab->id;
            $requestData['ref_table']          = (new CertiLab)->getTable();
            $requestData['ref_id']             = $request->ref_id;

             // ประวัติการแนบไฟล์ แนบท้าย
             if(  $request->form == 'create'){
                             // แนบท้าย ที่ใช้งาน
                            $certLabs   = CertLabsFileAll::create($requestData);

                            $certi_lab->attach                    =   $certLabs->attach  ?? @$certi_lab->attach;
                            $certi_lab->attach_pdf                =   $certLabs->attach_pdf  ?? @$certi_lab->attach_pdf;
                            $certi_lab->attach_pdf_client_name    =   $certLabs->attach_pdf_client_name  ?? @$certi_lab->attach_pdf_client_name;
                            $certi_lab->status                    =   '28';
                            $certi_lab->save();
          

                            $obj = new stdClass;
                            $obj->id                =  $certLabs->id;
                            $obj->app_no            =  $certLabs->app_no;
                            $obj->file_word         =   !empty($certLabs->attach)   ?  HP::getFileStorage($attach_path.$certLabs->attach)    : '';
                            $obj->file_pdf          =   !empty($certLabs->attach_pdf)   ?  HP::getFileStorage($attach_path.$certLabs->attach_pdf)    : '';
                            $obj->start_date        =   !empty($certLabs->start_date)   ? HP::revertDate($certLabs->start_date,true) : '';
                            $obj->start_date_th     =   !empty($certLabs->start_date)   ? HP::DateThai($certLabs->start_date) : '';
                            $obj->end_date          =   !empty($certLabs->end_date)   ? HP::revertDate($certLabs->end_date,true) : ''; 
                            $obj->end_date_th       =   !empty($certLabs->end_date)   ? HP::DateThai($certLabs->end_date) : '';
                            $obj->created_at        =   !empty($certLabs->created_at)   ? HP::DateThai($certLabs->created_at) : '';
                            $purpose_id             =   CertiLab::where('app_no',$certLabs->app_no)->value('purpose_type');
                            if(!empty($purpose_id) &&  array_key_exists($purpose_id,$purposes)  ){
                                $obj->purpose_text      =    '<p class="text-muted"><i>'.$purposes[$purpose_id].'</i></p>'  ;
                            }else{
                                $obj->purpose_text      =  '';
                            }
                            $obj->state             =    '1'; 
                            return response()->json( $obj );

            }else{
                    $certLabs   = CertLabsFileAll::findOrFail($request->id);
                    $certLabs->update($requestData);
                    // แนบท้าย ที่ใช้งาน
              
                    $certi_lab->attach                    =   $certLabs->attach  ?? @$certi_lab->attach;
                    $certi_lab->attach_pdf                =   $certLabs->attach_pdf  ?? @$certi_lab->attach_pdf;
                    $certi_lab->attach_pdf_client_name    =  $certLabs->attach_pdf_client_name  ?? @$certi_lab->attach_pdf_client_name;
                    $certi_lab->status                    =   '28';
                    $certi_lab->save();

                    $datas = [];
                    if(count($lab_ids) > 0){
                        $alls =  CertLabsFileAll::whereIn('app_certi_lab_id', $lab_ids)->whereNotIn('status_cancel',[1])->get();
                    }else{
                        $alls =  CertLabsFileAll::where('app_certi_lab_id', $certi_lab->id)->whereNotIn('status_cancel',[1])->get();
                    }
       
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

                            $purpose_id             =   CertiLab::where('app_no',$item->app_no)->value('purpose_type');
                            if(!empty($purpose_id) &&  array_key_exists($purpose_id,$purposes)  ){
                                $obj->purpose_text      =    '<p class="text-muted"><i>'.$purposes[$purpose_id].'</i></p>'  ;
                            }else{
                                $obj->purpose_text      =  '';
                            }
                        
                            $obj->state             = $item->state;
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






    public function storeFile($files, $app_no = 'files_lab',$name =null)
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

    public function error() {
        return back()->withInput();
    }

    public function apiGetFiles(Check $cc) {
        return response()->json([
            'files' => $cc->files ?? array()
        ]);
    }


    public function delete_attach($id) {
       $CheckFile =   CheckFile::where('id',$id)->first();
          $status = '';
       if(!is_null($CheckFile)){
          $CheckFile->delete();
           $status = 'true';
       }else{
           $status = 'false';
       }
        return response()->json([
            'status' =>  $status
        ]);
    }


    public function DeleteReportFile($id)
    {
            $Check = ReportFile::where('id',$id)->first();
            if(!is_null($Check)){
                $Check->delete();
                $file = 'true';
            }else{
                 $file = 'false';
            }
         return  $file;

    }

    public function export_word($id)
    {
        $certi_lab = CertiLab::where('id',$id)->first();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();

        if($certi_lab->lab_type ==3 && $certi_lab->certi_test_scope->count() > 0 ){

            $templateProcessor = new TemplateProcessor(public_path('/word/applicant_test.docx'));
            $templateProcessor->cloneRow('item', count($certi_lab->certi_test_scope));

                $i = 1;
                $TestScope = CertifyTestScope::select('branch_id')->where('app_certi_lab_id',$id)->groupBy('branch_id')->get();
                foreach ($TestScope as $item) {
                    $branchs = CertifyTestScope::where('app_certi_lab_id',$id)
                                                   ->where('branch_id',$item->branch_id)->get();
                    foreach ($branchs as $key => $branch ) {
                        if($key == 0){
                            $templateProcessor->setValue('title#'.$i,!empty($branch->Tablebranch->title) ? $branch->Tablebranch->title : '-');
                        }else{
                            $templateProcessor->setValue('title#'.$i,' ');
                        }
                        $templateProcessor->setValue('item#'.$i, !empty($branch->TableCategoryProduct->title) ? ($key +1).'. '.$branch->TableCategoryProduct->title : '-');
                        $i++;
                    }
                }
              $templateProcessor->saveAs(storage_path('เอกสารประกอบคําขอรับใบรับรองห้องปฏิบัติการทดสอบ.docx'));
              $fontStyle->setName('THSarabunPSK');
              return response()->download(storage_path('เอกสารประกอบคําขอรับใบรับรองห้องปฏิบัติการทดสอบ.docx'));
        }

        if($certi_lab->lab_type == 4 && $certi_lab->certi_lab_calibrate->count() > 0 ){

            $templateProcessor = new TemplateProcessor(public_path('/word/applicant_calibration.docx'));
            $templateProcessor->cloneRow('item', count($certi_lab->certi_lab_calibrate));

                $i = 1;
                $calibrate = CertifyLabCalibrate::select('branch_id')->where('app_certi_lab_id',$id)->groupBy('branch_id')->get();
                foreach ($calibrate as $item) {
                    $calibrates = CertifyLabCalibrate::where('app_certi_lab_id',$id)
                                                   ->where('branch_id',$item->branch_id)->get();
                    foreach ($calibrates as $key => $brates ) {
                        if($key == 0){
                            $templateProcessor->setValue('title#'.$i,!empty($brates->TableCalibrationBranch->title) ? $brates->TableCalibrationBranch->title : '-');
                        }else{
                            $templateProcessor->setValue('title#'.$i,' ');
                        }
                        $templateProcessor->setValue('item#'.$i, !empty($brates->TableCalibrationGroup->title) ? ($key +1).'. '.$brates->TableCalibrationGroup->title : '-');
                        $i++;
                    }
                }

              $templateProcessor->saveAs(storage_path('เอกสารประกอบคําขอรับใบรับรองห้องปฏิบัติการสอบเทียบ.docx'));
              $fontStyle->setName('THSarabunPSK');
              return response()->download(storage_path('เอกสารประกอบคําขอรับใบรับรองห้องปฏิบัติการสอบเทียบ.docx'));
        }
   }


   public function check_pay_in_lab(Request $request)
   {
       
        $arrContextOptions = array();
        $lab_id =   $request->input('lab_id');
        $payin =   $request->input('payin');
        $id  =   $request->input('id');
        $certi_lab = CertiLab::findOrFail($lab_id);
       
       if($payin == '1'){ // pay in ครั้งที่ 1
        
            $cost_assessment = CostAssessment::findOrFail($id);
            
            if(!is_null($cost_assessment)){
                // dd($cost_assessment,$request->amount);
                $cost_assessment->app_certi_assessment_id =  $cost_assessment->app_certi_assessment_id ??  null;
                $cost_assessment->amount =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):0;
                $cost_assessment->amount_bill =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):0;
                $cost_assessment->report_date =   !empty($request->report_date)?$request->report_date:null;
                $cost_assessment->save();
                
                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',1)->first();

                $app_no =  $certi_lab->app_no;
                $data_ref1 = $app_no.'-'.$cost_assessment->app_certi_assessment_id;

                // dd($data_ref1);

                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false,
                                      );
                }

                // $content = '';
                // if(strpos($setting_payment->data, '127.0.0.1')===0){
                //     $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$data_ref1", false, stream_context_create($arrContextOptions));
                // }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                //     $content = EpaymentDemo::pmt1($data_ref1, $setting_payment->pid, 'json');
                //     $content = $content->getContent();
                // }

                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$data_ref1", false, stream_context_create($arrContextOptions));
                // $content =  file_get_contents("$setting_payment->data?pid=4&out=json&Ref1=IB-67-004", false, stream_context_create($arrContextOptions));
                // $content =  file_get_contents("$setting_payment->data?pid=8&out=json&Ref1=TEST-67-091-1792", false, stream_context_create($arrContextOptions));
                // $content =  file_get_contents("https://www4.tisi.go.th/epayment/pmt2.asp?pid=4&out=json&Ref1=IB-67-004", false, stream_context_create($arrContextOptions));
                
                 $api = json_decode($content,false);

                //  $cost_assessment->amount =   0.00;
                //  $cost_assessment->amount_bill = 0.00;
                //  $cost_assessment->report_date = null;
                //  $cost_assessment->save();

                if(!is_null($api) && is_object($api) && $api->returnCode != '000'){
                    return response()->json([
                                             'message'      =>  false,
                                             'status_error' => HP::getErrorCode($api->returnCode)
                                            ]);
                }elseif(!is_null($api) && is_array($api) && array_key_exists(0, $api) && property_exists($api[0], 'error')){
                    return response()->json([
                                             'message'      => false,
                                             'status_error' => $api[0]->error->message
                                            ]);
                }else{
                    //พบรายการ payin
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
               $cost_certificate = CostCertificate::where('app_certi_lab_id',$lab_id)->orderby('id','desc')->first();
              
            if(!is_null($cost_certificate)){
                // dd($certi_lab->app_no);
                $cost_certificate->notification_date =  date('Y-m-d');
                $cost_certificate->save();

                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',1)->first();
                
               
                $app_no =  $certi_lab->app_no;

               

                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false,
                                      );
                }
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$app_no", false, stream_context_create($arrContextOptions));
                

                 $api = json_decode($content);
                //  dd($api);
                //  $cost_certificate->notification_date =  null;
                //  $cost_certificate->save();

                if(!is_null($api) && property_exists($api, 'returnCode') && $api->returnCode != '000'){

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

           $data  =  CertiLab::findOrFail($request->id);

        //    if($request->type == true){
        //              return response()->json([
        //                                      'message' =>  $certi_lab
        //                                  ]);
        //    }else{
                 return response()->json([
                                             'message' =>  HP_API_PID::CheckDataApiPid($data,(new CertiLab)->getTable())
                                         ]);
        //    }

   }

   public function insert_payin_all($type, $item)
   {

       if($type == 1){ // lab

                if(!empty($item->applicant) && !is_null($item->conditional_type)){
                    $app = $item->applicant;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CostAssessment)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CostAssessment)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = !empty($item->amount) ?  $item->amount: null ;
                    $pay_in->start_date             = !empty($item->report_date) ?  $item->report_date: null ;
                    $pay_in->detail                 = !empty($item->detail) ?  $item->detail: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->lab_name) ?  $app->lab_name: null ;
                    $pay_in->auditors_name          = !empty($item->assessment->board_auditor_to->auditor) ?  $item->assessment->board_auditor_to->auditor : null ;
                    $pay_in->certify                = 1;
                    $pay_in->state                  = 1;
                    $pay_in->created_by             = !empty($item->reporter_id) ?  $item->reporter_id: null ;
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
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

       }else{
                 if(!empty($item->applicant) && !is_null($item->conditional_type)){
                    $app = $item->applicant;
                    $pay_in = PayInAll::where('ref_id',$item->id)->where('ref_table',(new CostCertificate)->getTable())->first();
                    if(is_null($pay_in)){
                        $pay_in = new  PayInAll;
                    }
                    $pay_in->ref_id                 =  $item->id ;
                    $pay_in->ref_table              = (new CostCertificate)->getTable() ;
                    $pay_in->conditional_type       = !empty($item->conditional_type) ?  $item->conditional_type: null ;
                    $pay_in->amount                 = null ;
                    $pay_in->start_date             = !empty($item->notification_date) ?  $item->notification_date: null ;
                    $pay_in->detail                 = !empty($item->detail) ?  $item->detail: null ;
                    $pay_in->start_date_feewaiver   = !empty($item->start_date_feewaiver) ?  $item->start_date_feewaiver: null ;
                    $pay_in->end_date_feewaiver     = !empty($item->end_date_feewaiver) ?  $item->end_date_feewaiver: null ;
                    $pay_in->app_no                 = !empty($app->app_no) ?  $app->app_no: null ;
                    $pay_in->name                   = !empty($app->name) ?  $app->name: null ;
                    $pay_in->tax_id                 = !empty($app->tax_id) ?  $app->tax_id: null ;
                    $pay_in->name_unit              = !empty($app->lab_name) ?  $app->lab_name: null ;
                    $pay_in->auditors_name          =  null ;
                    $pay_in->certify                = 1;
                    $pay_in->state                  = 2;
                    $pay_in->created_by             = !empty($item->created_by) ?  $item->created_by: null ;
                    $pay_in->created_at             = !empty($item->created_at) ?  $item->created_at: null ;
                    $pay_in->updated_at             = !empty($item->updated_at) ?  $item->updated_at: null ;
                    if( !empty($item->attach)   && !empty($item->attach_client_name) ){
                        $attach = [];
                        $attach_file               =   basename($item->attach);
                        $attach['url']            =   'certify/check/file_client/'.str_replace($attach_file,"",$item->attach)   ;
                        $attach['new_filename']   = $attach_file;
                        $attach['filename']       =  $item->attach_client_name;
                        $pay_in->attach =   (count($attach) > 0) ? json_encode($attach) : null;
                    }
                    $pay_in->save();
                    echo '-<span style="color:#33cc33;">ครั้งที่ 1 = '.$app->app_no.' : '.$app->name .'</span>';
                    echo '<br/>';
                }
       }

   }

   
   private function save_certilab_export_mapreq($certi_lab)
   {
         $app_certi_lab         = CertiLab::with([
                                                   'certificate_exports_to' => function($q){
                                                       $q->whereIn('status',['0','1','2','3','4']);
                                                   }
                                               ])
                                               ->where('created_by', $certi_lab->created_by)
                                               ->whereNotIn('status', ['0','4'])
                                               ->where('standard_id', $certi_lab->standard_id)
                                               ->where('lab_type', $certi_lab->lab_type)
                                               ->first();
        // if(!Is_null($app_certi_lab)){
        
        if($app_certi_lab !== null){    
          
            $certificate_exports_id = !empty($app_certi_lab->certificate_exports_to->id) ? $app_certi_lab->certificate_exports_to->id : null;
            // dd($certificate_exports_id);
            //  if(!Is_null($certificate_exports_id)){
            if($certificate_exports_id !== null){
                 $mapreq =  CertiLabExportMapreq::where('app_certi_lab_id',$certi_lab->id)->where('certificate_exports_id', $certificate_exports_id)->first();
                 if(Is_null($mapreq)){
                     $mapreq = new  CertiLabExportMapreq;
                 }
                 $mapreq->app_certi_lab_id       = $certi_lab->id;
                 $mapreq->certificate_exports_id = $certificate_exports_id;
                 $mapreq->save();
             }
        }
   }
   
    public function update_delete(Request $request)
    {
        $certi_lab = CertiLab::findOrFail($request->input('del_id'));
        if(!empty($certi_lab)){
            $request->request->add(['deleted_by' => @auth()->user()->getKey()]);
            $request->request->add(['deleted_at' => date('Y-m-d h:i:s')]);
            $request->request->add(['status' => 4]);
            $certi_lab->update($request->all());
            $attach_files_del_names = $request->get('attach_files_del_name');
            if(!empty($attach_files_del_names) && count($attach_files_del_names) > 0){
                foreach( $attach_files_del_names as $key=>$file ){
                    if($request->hasFile("attach_files_del.{$key}")){
                        HP::singleLabCancalFileUpload(
                            $request->file("attach_files_del.{$key}"),
                            $this->attach_path,
                            $certi_lab,
                            $request->input("attach_files_del_name.{$key}")
                        );
                    }
                }
            }
        }
        return redirect()->back();
    }


    public function getTestScopeData($id)
    {
 
     $latestCertiLab = CertiLab::find($id);
 
     $company = [];
 
     if ($latestCertiLab) {
         // ดึง LabCalRequest ที่มี app_certi_lab_id ตรงกับ $latestCertiLab->id (ทุกรายการ)
         $labTestRequests = LabTestRequest::with([
             'labTestTransactions.labTestMeasurements'
         ])->where('app_certi_lab_id', $latestCertiLab->id)->get();
 
         // สร้างข้อมูลในรูปแบบของ $company
         foreach ($labTestRequests as $key => $labTestRequest) {

           
             $data = [];
             foreach ($labTestRequest->labTestTransactions as $transaction) {
                
                 $transactionData = [
                     'index' => $transaction->index,
                     'category' =>  $transaction->category,
                     'category_th' => $transaction->category_th,
                     'description' => $transaction->description,
                     'standard' => $transaction->standard,
                     'test_field' => $transaction->test_field,
                     'test_field_eng' => $transaction->test_field_eng,
                     'code' => $transaction->code,
                     'key' => $transaction->key,
                     'measurements' => [],
                 ];
 
                 foreach ($transaction->labTestMeasurements as $measurement) {

                     $measurementData = [
                         'name' => $measurement->name,
                         'name_eng' => $measurement->name_eng,
                         'description' => $measurement->description,
                         'detail' => $measurement->detail,
                         'type' => $measurement->type,
                         'ranges' => [],
                     ];
 

 
                     $transactionData['measurements'][] = $measurementData;
                 }
 
                 $data[] = $transactionData;
             }
 
             // dd($labCalRequest->no);
             // สร้างชุดข้อมูลที่แบ่งตาม id, station_type, lab_type
               $company[] = [
                 "id" => $key + 1,  // ให้เพิ่ม 1 เพื่อเริ่มจาก 1
                 "station_type" => $key === '0' ? "main" : "branch" . ($key),  // กำหนดประเภท station
                 "lab_type" => $labTestRequest->certiLab->lab_type,  // lab_type จาก certiLab
                 "app_certi_lab" => $labTestRequest->certiLab,  // lab_type จาก certiLab
                 // เพิ่มคีย์ใหม่จากฟิลด์ใน lab_cal_requests
                 "no" => trim($labTestRequest->no ?? '') ?: null,
                 "moo" => trim($labTestRequest->moo ?? '') ?: null,
                 "soi" => trim($labTestRequest->soi ?? '') ?: null,
                 "street" => trim($labTestRequest->street ?? '') ?: null,
                 "province_name" => trim($labTestRequest->province_name ?? '') ?: null,
                 "amphur_name" => trim($labTestRequest->amphur_name ?? '') ?: null,
                 "tambol_name" => trim($labTestRequest->tambol_name ?? '') ?: null,
                 "postal_code" => trim($labTestRequest->postal_code ?? '') ?: null,
                 "no_eng" => trim($labTestRequest->no_eng ?? '') ?: null,
                 "moo_eng" => trim($labTestRequest->moo_eng ?? '') ?: null,
                 "soi_eng" => trim($labTestRequest->soi_eng ?? '') ?: null,
                 "street_eng" => trim($labTestRequest->street_eng ?? '') ?: null,
                 "tambol_name_eng" => trim($labTestRequest->tambol_name_eng ?? '') ?: null,
                 "amphur_name_eng" => trim($labTestRequest->amphur_name_eng ?? '') ?: null,
                 "province_name_eng" => trim($labTestRequest->province_name_eng ?? '') ?: null,
 
                 "scope" => $data
 
             ];
         }
     }
 
     // ส่งข้อมูลกลับในรูปแบบ JSON
     return response()->json($company);
    }


   public function getCalScopeData($id)
   {

    $latestCertiLab = CertiLab::find($id);

    $company = [];

    if ($latestCertiLab) {
        // ดึง LabCalRequest ที่มี app_certi_lab_id ตรงกับ $latestCertiLab->id (ทุกรายการ)
        $labCalRequests = LabCalRequest::with([
            'labCalTransactions.labCalMeasurements.labCalMeasurementRanges'
        ])->where('app_certi_lab_id', $latestCertiLab->id)->get();

        // สร้างข้อมูลในรูปแบบของ $company
        foreach ($labCalRequests as $key => $labCalRequest) {
            $data = [];
            foreach ($labCalRequest->labCalTransactions as $transaction) {


              $calibration_branch_name_en = null;

              if($transaction->category !== null){
                $calibrationBranch = CalibrationBranch::find($transaction->category);
                if($calibrationBranch!==null)
                {
                  $calibration_branch_name_en  = $calibrationBranch->title_en;
                }
              }

                $instrument_name = null;

                if($transaction->instrument !== null){
                  $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::find($transaction->instrument);
                  if($calibrationBranchInstrumentGroup!==null)
                  {
                    $instrument_name  = $calibrationBranchInstrumentGroup->name;
                  }
                }

                $instrument_two_name = null;

                if($transaction->instrument_two !== null){
                  $calibrationBranchInstrument = CalibrationBranchInstrument::find($transaction->instrument_two);
                  if($calibrationBranchInstrument!==null)
                  {
                    $instrument_two_name  = $calibrationBranchInstrument->name;
                  }
                }

                $transactionData = [
                    'index' => $transaction->index,
                    'category' => $calibration_branch_name_en,
                    'category_th' => $transaction->category_th,
                    'instrument' => $instrument_name,
                    'instrument_two' => $instrument_two_name,
                    'description' => $transaction->description,
                    'standard' => $transaction->standard,
                    'code' => $transaction->code,
                    'key' => $transaction->key,
                    'measurements' => [],
                ];

                foreach ($transaction->labCalMeasurements as $measurement) {
                    $measurementData = [
                        'name' => $measurement->name,
                        'type' => $measurement->type,
                        'ranges' => [],
                    ];

                    foreach ($measurement->labCalMeasurementRanges as $range) {
                        $rangeData = [
                            'description' => $range->description,
                            'range' => $range->range,
                            'uncertainty' => $range->uncertainty,
                        ];

                        $measurementData['ranges'][] = $rangeData;
                    }

                    $transactionData['measurements'][] = $measurementData;
                }

                $data[] = $transactionData;
            }

            // dd($labCalRequest->no);
            // สร้างชุดข้อมูลที่แบ่งตาม id, station_type, lab_type
              $company[] = [
                "id" => $key + 1,  // ให้เพิ่ม 1 เพื่อเริ่มจาก 1
                "station_type" => $key === '0' ? "main" : "branch" . ($key),  // กำหนดประเภท station
                "lab_type" => $labCalRequest->certiLab->lab_type,  // lab_type จาก certiLab
                "app_certi_lab" => $labCalRequest->certiLab,  // lab_type จาก certiLab
                // เพิ่มคีย์ใหม่จากฟิลด์ใน lab_cal_requests
                "no" => trim($labCalRequest->no ?? '') ?: null,
                "moo" => trim($labCalRequest->moo ?? '') ?: null,
                "soi" => trim($labCalRequest->soi ?? '') ?: null,
                "street" => trim($labCalRequest->street ?? '') ?: null,
                "province_name" => trim($labCalRequest->province_name ?? '') ?: null,
                "amphur_name" => trim($labCalRequest->amphur_name ?? '') ?: null,
                "tambol_name" => trim($labCalRequest->tambol_name ?? '') ?: null,
                "postal_code" => trim($labCalRequest->postal_code ?? '') ?: null,
                "no_eng" => trim($labCalRequest->no_eng ?? '') ?: null,
                "moo_eng" => trim($labCalRequest->moo_eng ?? '') ?: null,
                "soi_eng" => trim($labCalRequest->soi_eng ?? '') ?: null,
                "street_eng" => trim($labCalRequest->street_eng ?? '') ?: null,
                "tambol_name_eng" => trim($labCalRequest->tambol_name_eng ?? '') ?: null,
                "amphur_name_eng" => trim($labCalRequest->amphur_name_eng ?? '') ?: null,
                "province_name_eng" => trim($labCalRequest->province_name_eng ?? '') ?: null,

                "scope" => $data

            ];
        }
    }

    // ส่งข้อมูลกลับในรูปแบบ JSON
    return response()->json($company);
   }

    public function getCalPageList($scopes,$pdfData,$details)
    {

        $pageArray = $this->getFirstCalPageList($scopes,$pdfData,$details);

        $firstPageArray = $pageArray[0];

        // ดึงค่า index ด้วย array_map และ array access
        $indexes = array_map(function ($item) {
            return $item->index;
        }, $firstPageArray[0]);

        $filteredScopes = array_filter($scopes, function ($item) use ($indexes) {
            return !in_array($item->index, $indexes);
        });
        
        $filteredScopes = array_values($filteredScopes);

        $pageArray = $this->getOtherCalPageList($filteredScopes,$pdfData,$details);

        $mergedArray = array_merge($firstPageArray, $pageArray);
        return $mergedArray;
    }
    
    public function getFirstCalPageList($scopes,$pdfData,$details)
    {
        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 8, // ระบุขอบด้านซ้าย
            'margin_right'     => 3, // ระบุขอบด้านขวา
            // 'margin_top'       => 97, // ระบุขอบด้านบน
            // 'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'margin_top'       => 108, // ระบุขอบด้านบน
            'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         

        $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        
        $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
        $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

        $footer = view('certify.scope_pdf.calibration.cal-scope-footer', [
            'qrImage' => null,
            'sign1Image' => null,
            'sign2Image' => null,
            'sign3Image' => null
        ]);

        $viewBlade = "certify.scope_pdf.calibration.cal-scope-first-header";

        if ($pdfData->siteType == "multi")
        {
            $viewBlade = "certify.scope_pdf.calibration.cal-scope-first-header-multi";
        }
        // $scopes = $details->scope;
        $header = view($viewBlade, [
          'branchNo' => null,
          'company' => $details,
          'pdfData' => $pdfData
        ]);
        $mpdf->SetHTMLHeader($header,2);
        $mpdf->SetHTMLFooter($footer,2);
        
        $html = view('certify.scope_pdf.calibration.pdf-cal-scope', [
                'scopes' => collect($scopes)
            ]);
        $mpdf->WriteHTML($html);

        // แปลง PDF เป็น String
        $pdfContent = $mpdf->Output('', 'S');

        // ใช้ PdfParser อ่าน PDF จาก String
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);

        $chunks = $this->generateRangesWithCalData($scopes,$pdf);

        $firstPage = array_slice($chunks, 0, 1);

        $remainingItems = array_slice($chunks, 1);

        return [$firstPage,$remainingItems,$chunks];
    }

    public function getOtherCalPageList($scope,$pdfData,$details)
    {
        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 8, // ระบุขอบด้านซ้าย
            'margin_right'     => 3, // ระบุขอบด้านขวา
            'margin_top'       => 97, // ระบุขอบด้านบน
            'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         

        // $data = $this->getMeasurementsData()->getData();

        $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
        $mpdf->WriteHTML($stylesheet, 1);

        // $company = $data->main;
        
        $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
        $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

        $footer = view('certify.scope_pdf.calibration.cal-scope-footer', [
            'qrImage' => null,
            'sign1Image' => null,
            'sign2Image' => null,
            'sign3Image' => null
        ]);

        $header = view('certify.scope_pdf.calibration.cal-scope-first-header', [
          'company' => $details,
          'pdfData' => $pdfData
        ]);
        $mpdf->SetHTMLHeader($header,2);
        $mpdf->SetHTMLFooter($footer,2);
        
        $html = view('certify.scope_pdf.calibration.pdf-cal-scope', [
                'scopes' => collect($scope)
            ]);
        $mpdf->WriteHTML($html);

        // แปลง PDF เป็น String
        $pdfContent = $mpdf->Output('', 'S');

        // ใช้ PdfParser อ่าน PDF จาก String
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);

        $chunks = $this->generateRangesWithCalData($scope,$pdf);
        
        // $firstPage = reset($chunks);

        // $remainingItems = array_slice($chunks, 1);

        // dd($chunks,$firstPage,$remainingItems);

        return $chunks;
   
    }

    function generateRangesWithCalData($data, $pdf)
    {
        $maxNumber = []; // เก็บตัวเลขที่มากที่สุดของแต่ละหน้า

        // ดึงข้อความและค้นหาตัวเลขที่มากที่สุดในแต่ละหน้า
        foreach ($pdf->getPages() as $pageNumber => $page) {
            preg_match_all('/\*(\d+)\*/', $page->getText(), $matches); // ค้นหาตัวเลขในรูปแบบ *number*
            if (!empty($matches[1])) {
                $maxNumber[$pageNumber + 1] = max($matches[1]); // เก็บเลขที่มากที่สุดในหน้า
            }
        }
        // สร้างช่วงข้อมูลตาม maxNumber และดึงค่าจาก $data
        $start = 0;
        return array_map(function ($end) use (&$start, $data) {
            $range = range($start, (int)$end); // สร้างช่วง index
            $start = (int)$end + 1; // อัปเดตค่าเริ่มต้นสำหรับช่วงถัดไป
            return array_map(function ($index) use ($data) {
                return $data[$index] ?? null; // ดึงค่าจาก $data ตาม index
            }, $range);
        }, $maxNumber);
    }

  public function generatePdfLabCalScope($id)
  {

      $siteType = "single";
      $data = $this->getCalScopeData($id)->getData();
      
      if(count($data) > 1){
          $siteType = "multi";
      }
      $mpdfArray = []; 

    // วนลูปข้อมูล
      foreach ($data as $key => $details) {

        $scopes = $details->scope;

          // ใช้ array_map เพื่อดึงค่าของ 'key' จากแต่ละรายการใน $scopes
          $keys = array_map(function ($item) {
            return $item->key;
          }, $scopes);

          // ใช้ array_unique เพื่อลบค่าซ้ำใน $keys
          $uniqueKeys = array_unique($keys);

          $pdfData =  (object)[
            'certificate_no' => 'xx-LBxxx',
            'acc_no' => '',
            'book_no' => '',
            'from_date_th' => '',
            'from_date_en' => '',
            'to_date_th' => '',
            'to_date_en' => '',
            'uniqueKeys' => $uniqueKeys,
            'siteType' => $siteType
        ];

          // dd($uniqueKeys);

          $scopePages = $this->getCalPageList($scopes,$pdfData,$details);
          
          $type = 'I';
          $fontDirs = [public_path('fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
          $fontData = [
              'thsarabunnew' => [
                  'R' => "THSarabunNew.ttf",
                  'B' => "THSarabunNew-Bold.ttf",
                  'I' => "THSarabunNew-Italic.ttf",
                  'BI' => "THSarabunNew-BoldItalic.ttf",
              ],
          ];
  
          if ($siteType == "single") {
              $mpdf = new Mpdf([
                  'PDFA'             => $type == 'F' ? true : false,
                  'PDFAauto'         => $type == 'F' ? true : false,
                  'format'           => 'A4',
                  'mode'             => 'utf-8',
                  'default_font_size'=> '15',
                  'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                  'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                  'default_font'     => 'thsarabunnew',
                  'margin_left'      => 6,
                  'margin_right'     => 5,
                  'margin_top'       => 97,
                  'margin_bottom'    => 40,
                  'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
              ]);
          } else { // multiple
              if($key == 0){
                  // $marginTop = 108;
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 108,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }else{
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 85,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }
            
          }
                
  
          $data = $this->getCalScopeData($id)->getData();
  
          $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
          $mpdf->WriteHTML($stylesheet, 1);
  
          // $mpdf->SetWatermarkImage(public_path(...), opacity, [size], [position]); 
  
          $mpdf->SetWatermarkImage(public_path('images/nc_logo.jpg'), 1, [23, 23], [170, 4]);
  
          $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark
  
          // เพิ่ม Text Watermark
        //   $mpdf->SetWatermarkText('Confidential', 0.1); // ระบุข้อความและ opacity
        //   $mpdf->showWatermarkText = true; // เปิดใช้งาน text watermark
              
          $signImage = public_path('images/sign.jpg');
          $sign1Image = public_path('images/sign1.png');
  
          // $footer = view('certify.scope_pdf.calibration.cal-scope-footer', [
          //     'sign1Image' => null,
          //     'sign2Image' => null,
          //     'sign3Image' => null
          // ]);
          // $mpdf->SetHTMLFooter($footer,2);
  
          $headerBlade = "certify.scope_pdf.calibration.cal-scope-first-header";
          $branchNo = null;

          if ($siteType == "multi")
          {
              $branchNo = $key + 1;
              if ($key == 0){
                  $headerBlade = "certify.scope_pdf.calibration.cal-scope-first-header-multi";
              }else{
                  $headerBlade = "certify.scope_pdf.calibration.cal-scope-first-header-multi-branch";
              }   
          }
          
          foreach ($scopePages as $index => $scopes) {
              if ($index == 0) {
                  $firstPageHeader = view($headerBlade, [
                      'branchNo' => $branchNo,
                      'company' => $details,
                      'pdfData' => $pdfData
                  ]);
                  $mpdf->SetHTMLHeader($firstPageHeader, 2);
                  $html = view('certify.scope_pdf.calibration.pdf-cal-scope', [
                      'scopes' => collect($scopes)
                  ]);
                  $mpdf->WriteHTML($html);
              } else if ($index > 0) {
  
                  $header = view('certify.scope_pdf.calibration.cal-scope-other-header', [
                      'branchNo' => null,
                      'company' => $details,
                      'pdfData' => $pdfData
                  ]);
                  $mpdf->SetHTMLHeader($header, 2);
                  $mpdf->AddPage('', '', '', '', '', 6, 5, 75, 30); 
                  $html = view('certify.scope_pdf.calibration.pdf-cal-scope', [
                      'scopes' => collect($scopes)
                  ]);
                  $mpdf->WriteHTML($html);
              }
          }

          $mpdfArray[$key] = $mpdf;
      }

      $combinedPdf = new \Mpdf\Mpdf([
          'PDFA'             => $type == 'F' ? true : false,
          'PDFAauto'         => $type == 'F' ? true : false,
          'format'           => 'A4',
          'mode'             => 'utf-8',
          'default_font_size'=> '15',
          'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
          'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
          'default_font'     => 'thsarabunnew',
          'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
      ]);

      $combinedPdf->SetImportUse();
      
      // สร้างไฟล์ PDF ชั่วคราวจาก `$mpdfArray`
      $tempFiles = []; // เก็บรายชื่อไฟล์ชั่วคราว
      foreach ($mpdfArray as $key => $mpdf) {
          $tempFileName = "{$key}.pdf"; // เช่น main.pdf, branch0.pdf
          $mpdf->Output($tempFileName, \Mpdf\Output\Destination::FILE); // บันทึก PDF ชั่วคราว
          $tempFiles[] = $tempFileName;
      }

      // รวม PDF
      foreach ($tempFiles as $fileName) {
          $pageCount = $combinedPdf->SetSourceFile($fileName); // เปิดไฟล์ PDF
          for ($i = 1; $i <= $pageCount; $i++) {
              $templateId = $combinedPdf->ImportPage($i);
              $combinedPdf->AddPage();
              $combinedPdf->UseTemplate($templateId);

              // ดึง HTML Footer จาก Blade Template
              $signImage = public_path('images/sign.jpg');
              $footer = view('certify.scope_pdf.calibration.cal-scope-footer', [
                  'sign1Image' => $signImage, // ส่งรูปภาพที่ต้องการใช้
                  'sign2Image' => $signImage,
                  'sign3Image' => $signImage
              ])->render();

              // ตั้งค่า Footer ใหม่สำหรับหน้า PDF
              $combinedPdf->SetHTMLFooter($footer);
          }
      }

      // ส่งออกไฟล์ PDF
      $combinedPdf->Output('combined.pdf', \Mpdf\Output\Destination::INLINE);

      // ลบไฟล์ชั่วคราว
      foreach ($tempFiles as $fileName) {
          unlink($fileName);
      }

  }


  public function generatePdfLabTestScope($id)
  {
      $siteType = "single";
      $data = $this->getTestScopeData($id)->getData();
      
      // dd(count($data));
      if(count($data) > 1){
          $siteType = "multi";
      }
      $mpdfArray = []; 

    // วนลูปข้อมูล
      foreach ($data as $key => $details) {

        $scopes = $details->scope;

        $keys = array_map(function ($item) {
        return $item->key;
        }, $scopes);

        // ใช้ array_unique เพื่อลบค่าซ้ำใน $keys
        $uniqueKeys = array_unique($keys);

        $pdfData =  (object)[
            'certificate_no' => 'xx-LBxxx',
            'acc_no' => '',
            'book_no' => '',
            'from_date_th' => '',
            'from_date_en' => '',
            'to_date_th' => '',
            'to_date_en' => '',
            'uniqueKeys' => $uniqueKeys,
            'siteType' => $siteType
        ];

        // dd($pdfData);


          $scopePages = $this->getPageTestList($scopes,$pdfData,$details);
          
          $type = 'I';
          $fontDirs = [public_path('fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
          $fontData = [
              'thsarabunnew' => [
                  'R' => "THSarabunNew.ttf",
                  'B' => "THSarabunNew-Bold.ttf",
                  'I' => "THSarabunNew-Italic.ttf",
                  'BI' => "THSarabunNew-BoldItalic.ttf",
              ],
          ];
  
          if ($siteType == "single") {
              
              $mpdf = new Mpdf([
                  'PDFA'             => $type == 'F' ? true : false,
                  'PDFAauto'         => $type == 'F' ? true : false,
                  'format'           => 'A4',
                  'mode'             => 'utf-8',
                  'default_font_size'=> '15',
                  'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                  'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                  'default_font'     => 'thsarabunnew',
                  'margin_left'      => 6,
                  'margin_right'     => 5,
                  'margin_top'       => 88,
                  'margin_bottom'    => 40,
                  'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
              ]);
          } else { // multiple
   
              if($key == 0){
                  
                  // $marginTop = 108;
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 99,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }else{
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 76,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }
            
          }
                
  
          $data = $this->getTestScopeData($id)->getData();
  
          $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
          $mpdf->WriteHTML($stylesheet, 1);
  
          // $mpdf->SetWatermarkImage(public_path(...), opacity, [size], [position]); 
  
          $mpdf->SetWatermarkImage(public_path('images/nc_logo.jpg'), 1, [23, 23], [170, 4]);
  
          $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark
  
          // เพิ่ม Text Watermark
          $mpdf->SetWatermarkText('Confidential', 0.1); // ระบุข้อความและ opacity
          $mpdf->showWatermarkText = true; // เปิดใช้งาน text watermark
              
          $signImage = public_path('images/sign.jpg');
          $sign1Image = public_path('images/sign1.png');
  
          // $footer = view('pdf.scope.calibration.cal-scope-footer', [
          //     'sign1Image' => null,
          //     'sign2Image' => null,
          //     'sign3Image' => null
          // ]);
          // $mpdf->SetHTMLFooter($footer,2);
         
          $headerBlade = "certify.scope_pdf.test.test-scope-first-header";
          $branchNo = null;

          if ($siteType == "multi")
          {
              $branchNo = $key + 1;
              if ($key == 0){
                  $headerBlade = "certify.scope_pdf.test.test-scope-first-header-multi";
              }else{
                  $headerBlade = "certify.scope_pdf.test.test-scope-first-header-multi-branch";
              }   
          }
          // $headerBlade = "certify.scope_pdf.test.test-scope-first-header-multi";
          // dd($scopePages);
          foreach ($scopePages as $index => $scopes) {
              if ($index == 0) {
                  
                  $firstPageHeader = view($headerBlade, [
                      'branchNo' => $branchNo,
                      'company' => $details,
                      'pdfData' => $pdfData
                  ]);
                  $mpdf->SetHTMLHeader($firstPageHeader, 2);
                  $html = view('certify.scope_pdf.test.pdf-test-scope', [
                      'scopes' => collect($scopes) // ส่งเฉพาะส่วนย่อยไปที่ blade
                  ]);
                  $mpdf->WriteHTML($html);
              } else if ($index > 0) {
                  $header = view('certify.scope_pdf.test.test-scope-other-header', []);
                  $mpdf->SetHTMLHeader($header, 2);
                  $mpdf->AddPage('', '', '', '', '', 6, 5, 65, 40); 
                  $html = view('certify.scope_pdf.test.pdf-test-scope', [
                      'scopes' => collect($scopes) // ส่งเฉพาะส่วนย่อยไปที่ blade
                  ]);
                  $mpdf->WriteHTML($html);
              }
          }

          $mpdfArray[$key] = $mpdf;
      }
      

      // $title = "scope";
      // $mpdfArray[0]->Output($title, 'I'); 

      // dd(count($mpdfArray));
      $combinedPdf = new \Mpdf\Mpdf([
          'PDFA'             => $type == 'F' ? true : false,
          'PDFAauto'         => $type == 'F' ? true : false,
          'format'           => 'A4',
          'mode'             => 'utf-8',
          'default_font_size'=> '15',
          'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
          'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
          'default_font'     => 'thsarabunnew',
          'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
      ]);

      $combinedPdf->SetImportUse();
      
      // สร้างไฟล์ PDF ชั่วคราวจาก `$mpdfArray`
      $tempFiles = []; // เก็บรายชื่อไฟล์ชั่วคราว
      foreach ($mpdfArray as $key => $mpdf) {
          $tempFileName = "{$key}.pdf"; // เช่น main.pdf, branch0.pdf
          $mpdf->Output($tempFileName, \Mpdf\Output\Destination::FILE); // บันทึก PDF ชั่วคราว
          $tempFiles[] = $tempFileName;
      }

      // รวม PDF
      foreach ($tempFiles as $fileName) {
          $pageCount = $combinedPdf->SetSourceFile($fileName); // เปิดไฟล์ PDF
          for ($i = 1; $i <= $pageCount; $i++) {
              $templateId = $combinedPdf->ImportPage($i);
              $combinedPdf->AddPage();
              $combinedPdf->UseTemplate($templateId);

              // ดึง HTML Footer จาก Blade Template
              $signImage = public_path('images/sign.jpg');
              $footer = view('certify.scope_pdf.test.test-scope-footer', [
                  'sign1Image' => $signImage, // ส่งรูปภาพที่ต้องการใช้
                  'sign2Image' => $signImage,
                  'sign3Image' => $signImage
              ])->render();

              // ตั้งค่า Footer ใหม่สำหรับหน้า PDF
              $combinedPdf->SetHTMLFooter($footer);
          }
      }

      // ส่งออกไฟล์ PDF
      $combinedPdf->Output('combined.pdf', \Mpdf\Output\Destination::INLINE);

      // ลบไฟล์ชั่วคราว
      foreach ($tempFiles as $fileName) {
          unlink($fileName);
      }


  }
  
  public function getPageTestList($scopes,$pdfData,$details)
  {

      $pageArray = $this->getFirstTestPageList($scopes,$pdfData,$details);
      // dd($pageArray);

      $firstPageArray = $pageArray[0];

      

      // ดึงค่า index ด้วย array_map และ array access
      $indexes = array_map(function ($item) {
          return $item->index;
      }, $firstPageArray[0]);

     

      $filteredScopes = array_filter($scopes, function ($item) use ($indexes) {
          return !in_array($item->index, $indexes);
      });

     
      
      $filteredScopes = array_values($filteredScopes);

    

      $pageArray = $this->getOtherTestPageList($filteredScopes,$pdfData,$details);

   

      $mergedArray = array_merge($firstPageArray, $pageArray);

      // dd($indexes,$scopes,$filteredScopes,$pageArray, $mergedArray);
      return $mergedArray;
  }


  public function getFirstTestPageList($scopes,$pdfData,$details)
  {
      $type = 'I';
      $fontDirs = [public_path('fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
      $fontData = [
          'thsarabunnew' => [
              'R' => "THSarabunNew.ttf",
              'B' => "THSarabunNew-Bold.ttf",
              'I' => "THSarabunNew-Italic.ttf",
              'BI' => "THSarabunNew-BoldItalic.ttf",
          ],
      ];

      $mpdf = new Mpdf([
          'PDFA' 	=>  $type == 'F' ? true : false,
          'PDFAauto'	 =>  $type == 'F' ? true : false,
          'format'            => 'A4',
          'mode'              => 'utf-8',
          'default_font_size' => '15',
          'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
          'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
          'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
          'margin_left'      => 8, // ระบุขอบด้านซ้าย
          'margin_right'     => 3, // ระบุขอบด้านขวา
          // 'margin_top'       => 97, // ระบุขอบด้านบน
          // 'margin_bottom'    => 40, // ระบุขอบด้านล่าง
          'margin_top'       => 99, // ระบุขอบด้านบน
          'margin_bottom'    => 40, // ระบุขอบด้านล่าง
          'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
      ]);         
     
      $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
      $mpdf->WriteHTML($stylesheet, 1);
      
      $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
      $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

      $footer = view('certify.scope_pdf.test.test-scope-footer', [
          'qrImage' => null,
          'sign1Image' => null,
          'sign2Image' => null,
          'sign3Image' => null
      ]);

      $viewBlade = "certify.scope_pdf.test.test-scope-first-header";

      if ($pdfData->siteType == "multi")
      {
          $viewBlade = "certify.scope_pdf.test.test-scope-first-header-multi";
      }

      $header = view($viewBlade, [
        'branchNo' => null,
        'company' => $details,
        'pdfData' => $pdfData
    ]);
      $mpdf->SetHTMLHeader($header,2);
      $mpdf->SetHTMLFooter($footer,2);
      
      $html = view('certify.scope_pdf.test.pdf-test-scope', [
              'scopes' => collect($scopes)
          ]);
      $mpdf->WriteHTML($html);
      
      // แปลง PDF เป็น String
      $pdfContent = $mpdf->Output('', 'S');

      // ใช้ PdfParser อ่าน PDF จาก String
      $parser = new Parser();
      $pdf = $parser->parseContent($pdfContent);

      $chunks = $this->generateRangesWithTestData($scopes,$pdf);
      // dd($scopes);
      $firstPage = array_slice($chunks, 0, 1);

      $remainingItems = array_slice($chunks, 1);
     
      return [$firstPage,$remainingItems,$chunks];
  }

  public function getOtherTestPageList($scope,$pdfData,$details)
  {
      $type = 'I';
      $fontDirs = [public_path('fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
      $fontData = [
          'thsarabunnew' => [
              'R' => "THSarabunNew.ttf",
              'B' => "THSarabunNew-Bold.ttf",
              'I' => "THSarabunNew-Italic.ttf",
              'BI' => "THSarabunNew-BoldItalic.ttf",
          ],
      ];

      $mpdf = new Mpdf([
          'PDFA' 	=>  $type == 'F' ? true : false,
          'PDFAauto'	 =>  $type == 'F' ? true : false,
          'format'            => 'A4',
          'mode'              => 'utf-8',
          'default_font_size' => '15',
          'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
          'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
          'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
          'margin_left'      => 8, // ระบุขอบด้านซ้าย
          'margin_right'     => 3, // ระบุขอบด้านขวา
          'margin_top'       => 97, // ระบุขอบด้านบน
          'margin_bottom'    => 40, // ระบุขอบด้านล่าง
          'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
      ]);         

      // $data = $this->getMeasurementsData()->getData();

      $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
      $mpdf->WriteHTML($stylesheet, 1);

      // $company = $data->main;
      
      $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
      $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

      $footer = view('certify.scope_pdf.test.test-scope-footer', [
          'qrImage' => null,
          'sign1Image' => null,
          'sign2Image' => null,
          'sign3Image' => null
      ]);

      $header = view('certify.scope_pdf.test.test-scope-first-header', [
        'branchNo' => null,
        'company' => $details,
        'pdfData' => $pdfData
      ]);
      $mpdf->SetHTMLHeader($header,2);
      $mpdf->SetHTMLFooter($footer,2);
      
      $html = view('certify.scope_pdf.test.pdf-test-scope', [
              'scopes' => collect($scope)
          ]);
      $mpdf->WriteHTML($html);

      // แปลง PDF เป็น String
      $pdfContent = $mpdf->Output('', 'S');

      // ใช้ PdfParser อ่าน PDF จาก String
      $parser = new Parser();
      $pdf = $parser->parseContent($pdfContent);

      $chunks = $this->generateRangesWithTestData($scope,$pdf);
      
      // $firstPage = reset($chunks);

      // $remainingItems = array_slice($chunks, 1);

      // dd($chunks,$firstPage,$remainingItems);

      return $chunks;
 
  }

  function generateRangesWithTestData($data, $pdf)
  {
      $maxNumber = []; // เก็บตัวเลขที่มากที่สุดของแต่ละหน้า

      // ดึงข้อความและค้นหาตัวเลขที่มากที่สุดในแต่ละหน้า
      foreach ($pdf->getPages() as $pageNumber => $page) {
          preg_match_all('/\*(\d+)\*/', $page->getText(), $matches); // ค้นหาตัวเลขในรูปแบบ *number*
          if (!empty($matches[1])) {
              $maxNumber[$pageNumber + 1] = max($matches[1]); // เก็บเลขที่มากที่สุดในหน้า
          }
      }
      // สร้างช่วงข้อมูลตาม maxNumber และดึงค่าจาก $data
      $start = 0;
      return array_map(function ($end) use (&$start, $data) {
          $range = range($start, (int)$end); // สร้างช่วง index
          $start = (int)$end + 1; // อัปเดตค่าเริ่มต้นสำหรับช่วงถัดไป
          return array_map(function ($index) use ($data) {
              return $data[$index] ?? null; // ดึงค่าจาก $data ตาม index
          }, $range);
      }, $maxNumber);
  }

}
