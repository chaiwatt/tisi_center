<?php

namespace App\Http\Controllers\Certify;

use Storage;
use App\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bcertify\TestBranch;
use App\Http\Controllers\Controller;
use App\Mail\LAB\LABAssignStaffMail;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Bcertify\BranchLabAdress;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\Report;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\ReportFile;
use App\Models\Certify\Applicant\Information;
use App\Models\Certify\Applicant\StatusTrait;
use App\Models\Certify\Applicant\CertiLabInfo;
use App\Models\Bcertify\LabCalScopeUsageStatus;
use App\Models\Certify\Applicant\CertiLabPlace;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\Applicant\CertiLabProgram;
use App\Models\Certify\Applicant\CostCertificate;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertiLabCheckBox;
use App\Models\Certify\Applicant\CertiLabEmployee;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Certify\Applicant\CertiLabAttachMore;
use App\Models\Certify\Applicant\CertiLabDeleteFile;
use App\Models\Certify\Applicant\CertiLabMaterialLef;
use App\Models\Certify\Applicant\CertiLabCheckBoxImage;
use App\Models\Certify\Applicant\AssessmentGroupAuditor;

class CheckAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $filter['perPage'] = $request->get('perPage', 10);

        $ao = new CertiLab;
        $arrStatus = $ao->arrStatus2();
        $branches = collect();
        $Query = CertiLab::where('status', '>=', StatusTrait::$STATUS_REQUEST);
        if ($filter['at']!='') { // ความสามารถห้องปฏิบัติการ
            $Query = $Query->where('lab_type', $filter['at']);
            $ao->get_branches($filter['at'])->each(function ($branch) use ($branches) {
                $branches->put($branch->id, $branch->title);
            });
        }

        if ($filter['b']!='' && $filter['at']!='') { // สาขา
            $Query = $Query->where('branch_name', $filter['b']);
        }

        if ($filter['s']!='') { // สถานะคำขอ
            $Query = $Query->where('status', $filter['s']);
        }

        if ($filter['c']!='') { // สถานะคำขอ
            $Query = $Query->whereHas('assessment', function ($query) use ($filter) {
                $query->where('checker_id', $filter['c']);
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

        if ($filter['q']!='') { // สถานะคำขอ
            $Query = $Query->where(function ($query) use ($filter) {
                $key = $filter['q'];
                $query->where('app_no', 'like', '%'.$key.'%');
                $query->orWhereHas('trader', function ($query) use ($key) {
                    $query->where('name', 'like', '%'.$key.'%');
                });
            });
        }

        $users = User::orderBy('reg_fname')->get();
        $select_users = array();
        foreach ($users as $user) {
            $select_users[$user->runrecno] = $user->reg_fname . ' ' . $user->reg_lname;
        }

        $apps = $Query->sortable()->paginate($filter['perPage']);
        return view('certify.check_assessment.index', compact(
            'select_users','apps','filter','branches','arrStatus'
        ));
    }


    public function apiGetAuditors()
    {
        $auditors = BoardAuditor::orderBy('created_at', 'asc')->get();
        return response()->json(compact('auditors'));
    }

    public function apiGetGroups(Assessment $ca)
    {
        $groups = $ca->groups()->with('files')->with('auditors.auditor.auditor_information.auditor')->orderBy('created_at', 'asc')->get();
        return response()->json(compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function assign(Request $request)
    {
        $checker = User::find($request->input('checker'));
        $apps = $request->input('apps');
        if ($checker && count($apps) != 0) {
            foreach ($apps as $app_id) {
                $app = CertiLab::find($app_id);
                if ($app && !$app->assessment) {
                        $ass = new Assessment;
                        $ass->app_certi_lab_id = $app->id;
                        $ass->checker_id = $checker->runrecno;
                    try {
                        $ass->save();

                        $cost_ass = new CostAssessment;
                        $cost_ass->app_certi_assessment_id = $ass->id;
                        $cost_ass->app_certi_lab_id = $app->id;
                        $cost_ass->save();

                        $report = new Report;
                        $report->app_certi_assessment_id = $ass->id;
                        $report->app_certi_lab_id = $app->id;
                        $report->save();

                        $costcerti = new CostCertificate;
                        $costcerti->app_certi_assessment_id = $ass->id;
                        $costcerti->app_certi_lab_id = $app->id;
                        $costcerti->save();

                    } catch (Exception $x) {
                        return back()->withInput();
                    }
                }
            }

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
            if(count($reg_email) > 0){
                      $mail = new LABAssignStaffMail(['apps'=>  ($CertiIb->count() > 0) ? $CertiIb : null,
                                                      'email'=> auth()->user()->reg_email ?? 'admin@admin.com',
                                                      'reg_fname' => (count($reg_fname) > 0) ? implode(", ",$reg_fname) : null
                                                     ]);

                 Mail::to($reg_email)->send($mail);
              }
            return redirect(route('check_assessment.index'))->with('flash_message', 'มอบหมายงานเรียบร้อย');
        }
        return back()->withInput();
    }

    public function agree(Request $request, Assessment $ca) {
//        dd($ca);
        try {
            $agree_status = $request->agree;
            $ca->agree_status = $agree_status ? 1 : null;
            $ca->save();

            $app = $ca->applicant;
            if ($agree_status) {
                $app->status = StatusTrait::$STATUS_AUDITOR_DETAIL;
                $app->save();
            }

            return redirect()->route('check_assessment.show', ['ca' => $ca])->with('flash_message', 'อัพเดทสถานะเรียบร้อย');
        } catch (Exception $x) {
            return back();
        }
    }


    public function store(Request $request)
    {
        //
    }

    public function apiStore(Request $request)
    {
        try {
            // Validate
            $check_date = Carbon::createFromFormat('d/m/Y', $request->check_date);
            $ca = Assessment::findOrFail($request->assessment_id);
            $labels = $request->labels;
            if (count($labels) == 0) {
                throw new Exception('ข้อผิดพลาด');
            }

            $group = new AssessmentGroup;
            $group->app_certi_assessment_id = $ca->id;
            $group->app_certi_lab_id = $ca->applicant->id;
            $group->checker_id = auth()->user()->runrecno;
            $group->assessment_date = $check_date;
            $group->save();

            foreach ($labels as $label) {
                $auditor_id = $label['value'];
                $auditor = BoardAuditor::findOrFail($auditor_id);
                if ($auditor) {
                    $ga = new AssessmentGroupAuditor;
                    $ga->app_certi_assessment_group_id = $group->id;
                    $ga->app_certi_lab_id = $ca->applicant->id;
                    $ga->auditor_id = $auditor->id;
                    $ga->save();
                }
            }

            $group->files;
            return response()->json([
                'check_date' => $check_date->format('d-m-Y'),
                'group' => $group
            ], 201);
        } catch (Exception $x) {
            return response()->json([
                'message' => 'ข้อมูลไม่ถูกต้อง'
            ], 400);
        }
    }


    public function show(Assessment $ca)
    {
        
        $checking_list = [
            9 => 'รับคำขอ',
            10 => 'ประมาณค่าใช้จ่าย',
            11 => 'ขอความเห็นประมาณค่าใช้จ่าย',
            12 => 'แต่งตั้งคณะผู้ตรวจประเมิน',
            13 => 'ขอความเห็นแต่งตั้งคณะผู้ตรวจประเมิน',
            14 => 'แจ้งรายละเอียดค่าตรวจประเมิน',
            15 => 'ชำระเงินค่าตรวจประเมิน',
            16 => 'ตรวจสอบการชำระค่าตรวจประเมิน',
            17 => 'ตรวจประเมิน',
            18 => 'สรุปรายงานเสนออนุกรรมการ',
            19 => 'แจ้งรายละเอียดชำระค่าใบรับรอง',
            20 => 'ชำระค่าใบรับรอง',
            21 => 'ตรวจสอบชำระค่าใบรับรอง',
            22 => 'ออกใบรับรอง'
        ];

        $maxStatus = StatusTrait::$STATUS_APP_EXPORT; // ใช้สำหรับป้องกันสถานะมากกว่าที่มี

        return view('certify.check_assessment.detail', compact('ca', 'checking_list', 'maxStatus'));
    }

    public function showCertificateLabDetail(CertiLab $certilab)
    {
        // dd('ok');
        $previousUrl = app('url')->previous();
        $certi_lab = $certilab;
        $certi_information = Information::where('app_certi_lab_id',$certi_lab->id)->first();
        $certi_lab_info = CertiLabInfo::where('app_certi_lab_id',$certi_lab->id)->first();
        if(is_null($certi_lab_info)){
            $certi_lab_info = new CertiLabInfo;
        }
        $certi_lab_place = CertiLabPlace::where('app_certi_lab_id',$certi_lab->id)->first();
        if(is_null($certi_lab_place)){
            $certi_lab_place = new CertiLabPlace;
        }
        $certi_lab_check_box = CertiLabCheckBox::where('app_certi_lab_id',$certi_lab->id)->first();
        if(is_null($certi_lab_check_box)){
            $certi_lab_check_box = new CertiLabCheckBox;
        }
        if(!is_null($certi_lab_check_box)){
            $certi_lab_chack_box_image = CertiLabCheckBoxImage::where('app_certi_lab_check_box_id',$certi_lab_check_box->id)->get();
        }else{
            $certi_lab_chack_box_image = null;
        }

        $certi_lab_employees = CertiLabEmployee::where('app_certi_lab_id',$certi_lab->id)->get();
        $certi_lab_mat = CertiLabMaterialLef::where('app_certi_lab_id',$certi_lab->id)->get();
        $certi_lab_program = CertiLabProgram::where('app_certi_lab_id',$certi_lab->id)->get();
        $certi_lab_attach_more = CertiLabAttachMore::where('app_certi_lab_id',$certi_lab->id)->get();
        $certi_lab_attach_all4 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '4')
                                                  ->get();
         $certi_lab_attach_all5 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '5')
                                                  ->get();        
        $certi_lab_attach_all61 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                   ->where('file_section', '61')
                                                   ->get();
        $certi_lab_attach_all62 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '62')
                                                  ->get();
        $certi_lab_attach_all71 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '71')
                                                  ->get();
        $certi_lab_attach_all72 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '72')
                                                  ->get();
        $certi_lab_attach_all8 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '8')
                                                  ->get();
        $certi_lab_attach_all9 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '9')
                                                  ->get();
                                  
        $certi_lab_attach_all10 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                                                  ->where('file_section', '10')
                                                  ->get();
        $attaches = DB::table('bcertify_config_attach_forms')->select('*')->where('form',1)->get();

        if ($certi_lab_info->petitioner == 1){
            $certi_lab_info->petitioner_name = "นิติบุคคล";
        }
        elseif ($certi_lab_info->petitioner == 2){
            $certi_lab_info->petitioner_name = "เป็นนิติบุคคลที่มีกิจกรรมอื่นนอกเหนือจากกิจกรรม ทดสอบ/สอบเทียบ";
        }
        elseif ($certi_lab_info->petitioner == 3){
            $certi_lab_info->petitioner_name = "เป็นหน่วยงานของรัฐ";
        }
        elseif ($certi_lab_info->petitioner == 4){
            $certi_lab_info->petitioner_name = "เป็นรัฐวิสาหกิจ";
        }
        elseif ($certi_lab_info->petitioner == 5){
            $certi_lab_info->petitioner_name = "เป็นสถาบันการศึกษา";
        }
        elseif ($certi_lab_info->petitioner == 6){
            $certi_lab_info->petitioner_name = "เป็นสถาบันวิชาชีพ";
        }
        elseif ($certi_lab_info->petitioner == 7){
            $certi_lab_info->petitioner_name = "อื่นๆ";
        }
        $CertiLabDeleteFile = CertiLabDeleteFile::where('app_certi_lab_id', $certi_lab->id) ->get();
        // dd($certi_lab_attach_all4);


        $labCalScopeUsageStatus = LabCalScopeUsageStatus::where('app_certi_lab_id', $certi_lab->id)
        ->where('status', 2)
        ->first();


    
        $labCalScopeTransactions = $labCalScopeUsageStatus ? 
        $labCalScopeUsageStatus->transactions()->with([
        'calibrationBranch',
        'calibrationBranchInstrumentGroup',
        'calibrationBranchInstrument',
        'calibrationBranchParam1',
        'calibrationBranchParam2'
        ])->get() : [];

        if (is_null($labCalScopeTransactions)) {
        $labCalScopeTransactions = [];
        }


        $branchLabAdresses = BranchLabAdress::where('app_certi_lab_id', $certi_lab->id)->with([
                            'certiLab', 
                            'province', 
                            'amphur', 
                            'district'
                        ])->get();

        $labCalScopeTransactionGroups = LabCalScopeUsageStatus::where('app_certi_lab_id', $certi_lab->id)
                        ->where('status', 1)
                        ->select('group', 'created_at') // เลือกฟิลด์ที่ต้องการ
                        ->get()
                        ->unique('group') // ทำให้ค่า group ไม่ซ้ำกัน
                        ->values(); // รีเซ็ต index ของ Collection

        $labTestRequest = LabTestRequest::with([
                'certiLab', 
                'labTestTransactions.labTestMeasurements'
            ])
            ->where('app_certi_lab_id', $certi_lab->id)
            ->get();

        $labCalRequest = LabCalRequest::with([
                'certiLab', 
                'labCalTransactions.labCalMeasurements.labCalMeasurementRanges'
            ])
            ->where('app_certi_lab_id', $certi_lab->id)
            ->get();

        return view('certify.certiLab-show.show',[
            'certi_information' => $certi_information,
            'certi_lab' => $certi_lab,
            'certi_lab_info' => $certi_lab_info,
            'certi_lab_place' => $certi_lab_place,
            'certi_lab_check_box' => $certi_lab_check_box,
            'certi_lab_chack_box_image' => $certi_lab_chack_box_image,
            'certi_lab_employees' => $certi_lab_employees,
            'certi_lab_mat' => $certi_lab_mat,
            'certi_lab_program' => $certi_lab_program,
            'certi_lab_attach_more' => $certi_lab_attach_more,
            'certi_lab_attach_all4' => $certi_lab_attach_all4,
            'certi_lab_attach_all5' => $certi_lab_attach_all5,
            'certi_lab_attach_all61' => $certi_lab_attach_all61,
            'certi_lab_attach_all62' => $certi_lab_attach_all62,
            'certi_lab_attach_all71' => $certi_lab_attach_all71,
            'certi_lab_attach_all72' => $certi_lab_attach_all72,
            'certi_lab_attach_all8' => $certi_lab_attach_all8,
            'certi_lab_attach_all9' => $certi_lab_attach_all9,
            'certi_lab_attach_all10' => $certi_lab_attach_all10,
            'attaches' => $attaches,
            'previousUrl'=>$previousUrl,
            'labCalScopeTransactions' => $labCalScopeTransactions,
            'branchLabAdresses' => $branchLabAdresses,
            'labCalScopeTransactionGroups' => $labCalScopeTransactionGroups,
            'labTestRequest' => $labTestRequest,
            'labCalRequest' => $labCalRequest,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $certi_lab = CertiLab::find($id);
        $test_branchs = TestBranch::pluck('title', 'id');

        return view('certify.certiLab-edit.index', [
            'certi_lab' => $certi_lab,
            'test_branchs' => $test_branchs
        ]);

    }


    public function update(Request $request, $id)
    {

        //ลบทั้งหมดไปก่อน
        CertifyTestScope::where('app_certi_lab_id', $id)->delete();

        $branch_ids = $request->input('branch_id');
        $test_lists = $request->input('test_list');
        $test_methods = $request->input('test_method');
        $test_details = $request->input('test_detail');

        foreach ($branch_ids as $key => $branch_id) {
            $certify_test_scope = new CertifyTestScope;
            $certify_test_scope->app_certi_lab_id = $id;
            $certify_test_scope->branch_id = $branch_id;
            $certify_test_scope->test_list = $test_lists[$key];
            $certify_test_scope->test_method = $test_methods[$key];
            $certify_test_scope->test_detail = $test_details[$key];
            $certify_test_scope->token = str_random(20);
            $certify_test_scope->save();
        }

        return redirect()->route('show.certificate.applicant.edit', ['certilab' => $id])->with('flash_message', 'อัพเดทข้อมูลเรียบร้อย');
    }

    public function updateReport(Request $request, Report $report) {
        $request->validate([
            'meetdate' => 'required',
            'resolution' => 'required',
            'savedate' => 'required',
        ]);

        try {
            $meetdate = Carbon::createFromFormat('d/m/Y', $request->meetdate);
            $savedate = Carbon::createFromFormat('d/m/Y', $request->savedate);

            $report->meet_date = $meetdate;
            $report->save_date = $savedate;
            $report->status = $request->input('resolution');
            $report->desc = $request->input('desc') ?? null;

            $reportFile = $request->file('report');
            if ($request->hasFile('report')) {
                $path = $this->storeFile($reportFile, $report, null);
                File::delete(storage_path($report->file));
                $report->file = $path;
            }

            $files = $request->file('file');
            $names = $request->input('name');
            if ($request->hasFile('file')) {
                foreach ($report->files as $file) {
                    File::delete(storage_path($file->file));
                    $file->delete();
                }

                foreach ($files as $key => $file) {
                    $file_desc = $names[$key] ?? null;
                    $input = [
                        'app_certi_report_assessment_id' => $report->id,
                        'file_desc' => $file_desc,
                        'file' => $this->storeFile($file, $report, $file_desc),
                        'created_by' => auth()->user()->runrecno,
                    ];

                    if (!ReportFile::create($input)) {
                        return $this->error();
                    }
                }
            }

            $report->save();

            return redirect()->route('check_assessment.show', ['ca' => $report->assessment])->with('flash_message', 'อัพเดทเรียบร้อย');
        } catch (Exception $x) {
            echo $x;
        }
    }


    public function updateCostCertificate(Request $request, CostCertificate $costcertificate) {
        $request->validate([
            'amount' => 'required',
            'savedate' => 'required',
        ]);

        try {
            $savedate = Carbon::createFromFormat('d/m/Y', $request->savedate);

            $costcertificate->amount = $request->amount ?? 0;
            $costcertificate->report_date = $savedate;
            $costcertificate->status_confirmed = $request->status_confirmed ? 1 : null;
            $costcertificate->status_later = $request->status_later ? 1 : null;

            $reportFile = $request->file('report');
            if ($request->hasFile('report')) {
                $path = $this->storeFile($reportFile, $costcertificate, null);
                File::delete(storage_path($costcertificate->amount_file));
                $costcertificate->amount_file = $path;
            }

            $costcertificate->save();

            $app = $costcertificate->applicant;
            if ($costcertificate->status_confirmed || $costcertificate->status_later) {
                $app->status = StatusTrait::$STATUS_APP_EXPORT;
                $app->save();
            }

            return redirect()->route('check_assessment.show', ['ca' => $costcertificate->assessment])->with('flash_message', 'อัพเดทเรียบร้อย');
        } catch (Exception $x) {
            echo $x;
        }
    }

    public function updateCost(Request $request, CostAssessment $cost) {
        $request->validate([
            'comment_number' => 'required',
            'savedate' => 'required',
        ]);

        try {
            $savedate = Carbon::createFromFormat('d/m/Y', $request->savedate);
            $amount = $request->comment_number;

            $cost->amount = $amount;
            $cost->report_date = $savedate;

            $file = $request->file('file');
            if ($request->hasFile('file')) {
                $amount_invoice = $this->storeFile($file, $cost, null);
                if ($cost->amount_invoice) {
                    File::delete(storage_path($cost->amount_invoice));
                }

                $cost->amount_invoice = $amount_invoice;
            }

            $cost->save();

            return redirect()->route('check_assessment.show', ['ca' => $cost->assessment])->with('flash_message', 'อัพเดทเรียบร้อย');
        } catch (Exception $x) {
            echo $x;
//            return back()->withInput();
        }
    }

    public function updateCostConfirmed(Request $request, CostAssessment $cost) {
        try {
            $status_confirmed = $request->status_confirmed;
            $cost->status_confirmed = $status_confirmed ? 1 : null;
            $cost->save();

            $app = $cost->applicant;
            if ($cost->status_confirmed) {
                $app->status = StatusTrait::$STATUS_AUDITOR;
                $app->save();
            }

            return redirect()->route('check_assessment.show', ['ca' => $cost->assessment])->with('flash_message', 'อัพเดทสถานะเรียบร้อย');
        } catch (Exception $x) {
            return back();
        }
    }

    public function updateNoticeConfirmed(Request $request, Assessment $ca)
    {
        try {
            $status_confirmed = $request->status_confirmed;
            $ca->assessment_status = $status_confirmed ? 1 : null;
            $ca->save();

            $app = $ca->applicant;
            if ($ca->assessment_status) {
                $app->status = StatusTrait::$STATUS_REPORT;
                $app->save();
            }

            return redirect()->route('check_assessment.show', ['ca' => $ca])->with('flash_message', 'อัพเดทสถานะเรียบร้อย');
        } catch (Exception $x) {
            return back();
        }
    }

    public function updateStatus(Request $request, Assessment $ca)
    {
        $status = $request->status;
        $app = $ca->applicant;
        if ($app) {
            if ($app->status >= StatusTrait::$STATUS_REQUEST && $app->status <= StatusTrait::$STATUS_APP_EXPORT) {
                $app->status = $status;
                $app->save();

                return redirect()->route('check_assessment.show', ['ca' => $ca])->with('flash_message', 'อัพเดทสถานะเรียบร้อย');
            }
        }
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function storeFile($files, $model, $name = null)
    {
        $path = '/files/applicants/check_files/';
        $destinationPath = Storage::disk()->getAdapter()->getPathPrefix().$path;
        if ($files) {
            // $destinationPath = storage_path($path);
            $fileClientOriginal = $files->getClientOriginalName();
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName = ($name ?? $filename).'-'.time() . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $fullFileName);
            $file_certificate_toDB = $path . $fullFileName;

            return $file_certificate_toDB;
        }
        return $model->file;
    }
}
