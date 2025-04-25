<?php

namespace App\Http\Controllers\Bcertify;

use App\cr;
use App\Models\Basic\Amphur;
use App\Models\Basic\Department;
use App\Models\Basic\District;
use App\Models\Bcertify\AuditorAssessmentExperience;
use App\Models\Bcertify\AuditorEducation;
use App\Models\Bcertify\AuditorExpertise;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Bcertify\AuditorTraining;
use App\Models\Bcertify\AuditorWorkExperience;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CalibrationItem;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Bcertify\CertificationScope;
use App\Models\Bcertify\Enms;
use App\Models\Bcertify\Formula;
use App\Models\Bcertify\Ghg;
use App\Models\Bcertify\Iaf;
use App\Models\Bcertify\IndustryType;
use App\Models\Bcertify\InspectBranch;
use App\Models\Bcertify\InspectCategory;
use App\Models\Bcertify\InspectType;
use App\Models\Bcertify\ProductItem;
use App\Models\Bcertify\StatusAuditor;
use App\Models\Bcertify\TestBranch;
use App\Models\Bcertify\TestItem;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\BoardAuditorInformation;
use App\Models\Certify\BoardReviewInformation;
use App\User;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $model = str_slug('auditor','-');
        if(auth()->user()->can('view-'.$model)) {
            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_formulas'] = $request->get('filter_formulas', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new AuditorInformation;
//        if ($filter['filter_state']!='') {
//            $Query = $Query->where('state', $filter['filter_state']);
//        }

            if ($filter['filter_search']!='') {
                $Query = $Query->where('fname_th','LIKE','%'.$filter['filter_search'].'%')
                    ->orwhere('lname_th','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('fname_en','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('lname_en','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('tel','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('created_at','LIKE','%'.$filter['filter_search'].'%');

            }

            if ($filter['filter_department'] != "" ){
                $Query = $Query->where('department_id', $filter['filter_department']);
            }

            if ($filter['filter_status'] != "" ){
                $Query = $Query->where('status', $filter['filter_status']);

            }

            if ($filter['filter_formulas'] != "" ){
                $Query = $Query->whereHas('auditor_expertise',function($query) use ($filter) {
                    $query->where('standard', '=',$filter['filter_formulas']);
                });
            }


            $informationAuditors = $Query->sortable()
                ->paginate($filter['perPage']);




            $departments = Department::where('state',1)->get();
            $formulas = Formula::where('state',1)->get();


            $auditor_department = array();
            foreach ($departments as $sa) {
                $auditor_department[$sa->id] = $sa->title;
            }

            $auditor_formulas = array();
            foreach ($formulas as $sa) {
                $auditor_formulas[$sa->id] = $sa->title;
            }

            return view('bcertify.auditor.index',[
                'departments' => $auditor_department,
                'formulas' => $auditor_formulas,
                'auditors' => $informationAuditors,
                'filter' => $filter,
            ]);
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('auditor','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.auditor.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
        $model = str_slug('auditor','-');
        if(auth()->user()->can('add-'.$model)) {

            
            $data_edu = json_decode($request->education_history);
            $data_train = json_decode($request->training);
            $experience = json_decode($request->data_experience);
            $data_expertise = json_decode($request->data_all_expertise);
            $data_check = json_decode($request->data_all_check);

            DB::beginTransaction();
            try {

                if ($request->choice == "on") {
                    $request->choice = 1;
                }else{
                    $request->choice = 0;
                }
    
                if ( $request->title == 1){
                    $request->title = "นาย";
                    $request->title_en = "MR.";
                }
                elseif ($request->title == 2){
                    $request->title = "นาง";
                    $request->title_en = "Miss.";
                }
                elseif ($request->title == 3){
                    $request->title = "นางสาว";
                    $request->title_en = "MRS.";
                }
    
                if ($request->group == "none"){
                    $request->group = null;
                }

                // information
                $auditor_information = new AuditorInformation([
                    // 'number_auditor' => $numberAuditor,
                    'number_auditor' => $request->regis_number,
                    'title_th' => $request->title,
                    'fname_th' => $request->th_fname,
                    'lname_th' => $request->th_lname,
                    'title_en' => $request->title_en,
                    'fname_en' => $request->en_fname,
                    'lname_en' => $request->en_lname,
                    'address' => $request->address,
                    'province_id' => $request->province,
                    'amphur_id' => $request->amphur,
                    'district_id' => $request->district,
                    'email' => $request->email,
                    'tel' => $request->tel,
                    'department_id' => $request->department,
                    'position' => $request->position,
                    'status_ab' => $request->choice,
                    'group_id' => $request->group,
                    'status' => $request->onOrOff,
                    'token' => str_random(16),
                    'user_id' => Auth::user()->runrecno,
                ]);
                $auditor_information->save();

                // education
                foreach ($data_edu as $data){
                    $auditor_education = new AuditorEducation([
                        'auditor_id' => $auditor_information->id,
                        'year' => $data->year,
                        'level_education' => $data->level_education,
                        'major_education' => $data->major_education,
                        'school_name' => $data->school_name,
                        'country' => $data->country,
                        'token' => str_random(16),
                    ]);
                    $auditor_education->save();
                }

                // training
                foreach ($data_train as $train){
                //                dd(Carbon::createFromFormat('d/m/Y', $train->end_date));
                    $auditor_training = new AuditorTraining([
                                                                'auditor_id' => $auditor_information->id,
                                                                'course_name' => $train->course_name,
                                                                'department_name' => $train->department_name,
                                                                'start_training' => Carbon::createFromFormat('d/m/Y', $train->start_training),
                                                                'end_training' => Carbon::createFromFormat('d/m/Y', $train->end_training),
                                                                'token' => str_random(16),
                                                            ]);
                    $auditor_training->save();
                }

                // experience
                foreach ($experience as $dataEx){
                    $auditor_exexperience = new AuditorWorkExperience([
                        'auditor_id' => $auditor_information->id,
                        'year' => $dataEx->year,
                        'position' => $dataEx->position,
                        'department' => $dataEx->department,
                        'role' => $dataEx->role,
                        'token' => str_random(16),
                    ]);
                    $auditor_exexperience->save();
                }

                foreach ($data_expertise as $expertise){
                    if (sizeof($expertise) != 0){
                        foreach ($expertise as $data){
                            $find_standard = Formula::where('title',$data->standard)->first();
                            $split_status = explode(',',$data->auditor_status);
                            $number_status = array();
                            foreach ($split_status as $status){
                                $find = StatusAuditor::where('id',$status)->first();
                                array_push($number_status,$find->id);
                            }
                            if ($data->show_type == 1){
                                $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                                $path_branch = CertificationBranch::class;
                                $auditor_expertise = new AuditorExpertise([
                                    'auditor_id' => $auditor_information->id,
                                    'type_of_assessment' => $data->show_type,
                                    'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                    'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                                    'branch_path' => @$path_branch,
                                    'scope_name' => @$data->scope_name,
                                    'specialized_expertise' => $data->specialized_expertise,
                                    'auditor_status' => implode(',',$number_status),
                                    'token' => str_random(16),
                                ]);
                                $auditor_expertise->save();
                            }
                            elseif ($data->show_type == 2){
                                $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                                $path_branch = InspectBranch::class;
                                $type_exam = InspectType::where('title',$data->typeCheck)->first();
                                $cat = InspectCategory::where('title',$data->cat)->first();
                                $auditor_expertise = new AuditorExpertise([
                                    'auditor_id' => $auditor_information->id,
                                    'type_of_assessment' => $data->show_type,
                                    'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                    'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                                    'branch_path' => $path_branch,
                                    'type_of_examination' =>  (!is_null($type_exam)? $type_exam->id:null), 
                                    'examination_category' =>    (!is_null($cat)? $cat->id:null), 
                                    'specialized_expertise' => $data->specialized_expertise,
                                    'auditor_status' => implode(',',$number_status),
                                    'token' => str_random(16),
                                ]);
                                $auditor_expertise->save();
                            }
                            elseif ($data->show_type == 3 || $data->show_type == 4 ){
                                if ($data->show_type == 3 ) {
                                    $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                                    $path_branch = CalibrationBranch::class;
                                    $list = CalibrationItem::where('title',$data->listCalibation)->first();
                                    $auditor_expertise = new AuditorExpertise([
                                        'auditor_id' => $auditor_information->id,
                                        'type_of_assessment' => $data->show_type,
                                        'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                        'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                        'branch_path' => $path_branch,
                                        'calibration_list' =>   (!is_null($list)? $list->id:null),   
                                        'specialized_expertise' => $data->specialized_expertise,
                                        'auditor_status' => implode(',',$number_status),
                                        'token' => str_random(16),
                                    ]);
                                    $auditor_expertise->save();
                                }
                                elseif ($data->show_type == 4){
                                    $find_branch = TestBranch::where('title',$data->showBranch)->first();
                                    $path_branch = TestBranch::class;
                                    $find_product = ProductItem::where('title',$data->exProduct)->first();
                                    $find_test_list = TestItem::where('title',$data->testList)->first();
                                    $auditor_expertise = new AuditorExpertise([
                                        'auditor_id' => $auditor_information->id,
                                        'type_of_assessment' => $data->show_type,
                                        'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                                        'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                        'branch_path' => $path_branch,
                                        'product' => (!is_null($find_product)? $find_product->id:null),
                                        'test_list' =>  (!is_null($find_test_list)? $find_test_list->id:null), 
                                        'specialized_expertise' => $data->specialized_expertise,
                                        'auditor_status' => implode(',',$number_status),
                                        'token' => str_random(16),
                                    ]);
                                    $auditor_expertise->save();
                                }
                            }
    
    
                        }
                    }
    
                }

                foreach ($data_check as $check){
                    if (sizeof($check) != 0){
                        foreach ($check as $data){
    //                    echo $data->standard;
                            $find_standard = Formula::where('title',$data->standard)->first();
                            $find_status = StatusAuditor::where('id',$data->showStaus)->first();
                            if ($data->checkType == 1){
                                $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                                $path_branch = CertificationBranch::class;
                                $assessment = new AuditorAssessmentExperience([
                                    'auditor_id' => $auditor_information->id,
                                    'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                    'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                    'type_of_assessment' => $data->checkType,
                                    'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                    'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                    'branch_path' => $path_branch,
                                    'scope_name' => $data->scope_name,
                                    'role' => $data->role,
                                    'auditor_status' =>    (!is_null($find_status)? $find_status->id:null),   
                                    'token' => str_random(16),
                                ]);
                                $assessment->save();
                            }
                            elseif ($data->checkType == 2){
                                $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                                $path_branch = InspectBranch::class;
                                $type_exam = InspectType::where('title',$data->typeCheck)->first();
                                $cat = InspectCategory::where('title',$data->cat)->first();
                                $assessment = new AuditorAssessmentExperience([
                                    'auditor_id' => $auditor_information->id,
                                    'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                    'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                    'type_of_assessment' => $data->checkType,
                                    'standard' =>   (!is_null($find_standard)? $find_standard->id:null),  
                                    'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                    'branch_path' => $path_branch,
                                    'type_of_examination' =>   (!is_null($type_exam)? $type_exam->id:null),  
                                    'examination_category' => (!is_null($cat)? $cat->id:null),   
                                    'role' => $data->role,
                                    'auditor_status' =>  (!is_null($find_status)? $find_status->id:null),   
                                    'token' => str_random(16),
                                ]);
                                $assessment->save();
                            }
                            elseif ($data->checkType == 3 || $data->checkType == 4 ){
                                if ($data->checkType == 3 ) {
                                    $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                                    $path_branch = CalibrationBranch::class;
                                    $list = CalibrationItem::where('title',$data->listCalibation)->first();
                                    $assessment = new AuditorAssessmentExperience([
                                        'auditor_id' => $auditor_information->id,
                                        'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                        'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                        'type_of_assessment' => $data->checkType,
                                        'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                                        'branch_id' =>   (!is_null($find_branch)? $find_branch->id:null),  
                                        'branch_path' => $path_branch,
                                        'calibration_list' =>  (!is_null($list)? $list->id:null),    
                                        'role' => $data->role,
                                        'auditor_status' =>  (!is_null($find_status)? $find_status->id:null),   
                                        'token' => str_random(16),
                                    ]);
                                    $assessment->save();
                                }
                                elseif ($data->checkType == 4){
                                    $find_branch = TestBranch::where('title',$data->showBranch)->first();
                                    $path_branch = TestBranch::class;
                                    $find_product = ProductItem::where('title',$data->exProduct)->first();
                                    $find_test_list = TestItem::where('title',$data->testList)->first();
                                    $assessment = new AuditorAssessmentExperience([
                                        'auditor_id' => $auditor_information->id,
                                        'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                        'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                        'type_of_assessment' => $data->checkType,
                                        'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                        'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                        'branch_path' => $path_branch,
                                        'product' => (!is_null($find_product)? $find_product->id:null),
                                        'test_list' =>   (!is_null($find_test_list)? $find_test_list->id:null), 
                                        'role' =>  $data->role,
                                        'auditor_status' =>  (!is_null($find_status)? $find_status->id:null),   
                                        'token' => str_random(16),
                                    ]);
                                    $assessment->save();
                                }
                            }
    
                        }
                    }
                }

                DB::commit();
                // all good

                return redirect(route('bcertify.auditor.show',['token'=>$auditor_information->token]))->with('success_message','สร้าง Auditor เรียบร้อยแล้ว');
            } catch (\Exception $e) {

                DB::rollback();

                echo $e->getMessage();
                exit;
                // something went wrong
            }

//        dd($data_check);

            // $order_year = sprintf('%03d',AuditorInformation::whereYear('created_at',Carbon::now()->year)->get()->count()+1);
            // $year = Carbon::now()->format('y');
            // $all_auditor = sprintf('%04d',\App\Models\Bcertify\AuditorInformation::get()->count()+1);
            // $numberAuditor = $year.$order_year."/".$all_auditor;

            // expertise

//            dd($data_check);

            // return redirect(route('bcertify.auditor.show',['token'=>$auditor_information->token]))->with('success_message','สร้าง Auditor เรียบร้อยแล้ว');

        }

        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {

        $model = str_slug('auditor','-');
        if(auth()->user()->can('view-'.$model)) {
            $auditor_information = AuditorInformation::where('token',$token)->first();
            $auditor_education = AuditorEducation::where('auditor_id',$auditor_information->id)->get();
            $auditor_training = AuditorTraining::where('auditor_id',$auditor_information->id)->get();
            $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor_information->id)->get();
            $auditor_work = AuditorWorkExperience::where('auditor_id',$auditor_information->id)->get();
            $auditor_assessment = AuditorAssessmentExperience::where('auditor_id',$auditor_information->id)->get();


            return view('bcertify.auditor.show',[
                'information' => $auditor_information,
                'educations' => $auditor_education,
                'trainings' => $auditor_training,
                'expertises' => $auditor_expertise,
                'works' => $auditor_work,
                'assessments' => $auditor_assessment,
            ]);
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {

        $model = str_slug('auditor','-');
        if(auth()->user()->can('edit-'.$model)) {
            $auditor_information = AuditorInformation::where('token',$token)->first();


            $auditor_education = AuditorEducation::where('auditor_id',$auditor_information->id)->get();
            $auditor_training = AuditorTraining::where('auditor_id',$auditor_information->id)->get()->each(function ($at) {$at->show_type = $at->type_of_assessment;$at->start_training = Carbon::parse($at->start_training)->format('d/m/Y');$at->end_training = Carbon::parse($at->end_training)->format('d/m/Y');});
            $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor_information->id)->get()->each(function($ae) {$ae->auditor;$ae->show_type = $ae->type_of_assessment;});
            $auditor_work = AuditorWorkExperience::where('auditor_id',$auditor_information->id)->get();
            $auditor_assessment = AuditorAssessmentExperience::where('auditor_id',$auditor_information->id)->get()->each(function ($aa) {$aa->checkType = $aa->type_of_assessment;});

            foreach ($auditor_education as $edu){
                $find = DB::table('tb_country')->select('*')->where('id',$edu->country)->first();
                $edu->contry_show = $find->title;
            }

            foreach ($auditor_expertise as $branch){

                $branch->standard =  $branch->formula->title;
                $branch->typeCheck =  $branch->type['title'];
                $branch->cat =  $branch->category['title'];
                $branch->listCalibation =  $branch->calibration['title'];
                $branch->exProduct=  $branch->product_show['title'];
                $branch->testList=  $branch->test['title'];
                $branch->showBranch=  $branch->branchable['title'];


                // CB
                $branch->show_branch_value=  $branch->branchable['id'];
                $branch->show_standard_value=  $branch->formula['id'];

                //IB
                $branch->show_inspection_value=  $branch->type['id'];
                $branch->show_category_value=  $branch->category['id'];

                // Lab Exam
                $branch->show_calibration_value=  $branch->calibration['id'];

                // Lab Test
                $branch->show_product_value=  $branch->product_show['id'];
                $branch->show_test_value=  $branch->test['id'];


                $status = explode(",",$branch->auditor_status);
                $data_status = array();
                foreach ($status as $show_status){
                    $name_status = StatusAuditor::where('id',$show_status)->first();
                    array_push($data_status,$name_status->title);
                }

                $branch->find_status = implode(',',$data_status);
            }


            foreach ($auditor_assessment as $check){
                $check->standard =  $check->formula->title;
                $check->typeCheck =  $check->type['title'];
                $check->cat =  $check->category['title'];
                $check->listCalibation =  $check->calibration['title'];
                $check->exProduct=  $check->product_show['title'];
                $check->testList=  $check->test['title'];
                $check->showBranch=  $check->branchable['title'];
                $check->showStaus=  $check->statusAuditor['id'];

                $check->first_date = Carbon::parse($check->start_date)->format('d/m/Y');
                $check->second_date = Carbon::parse($check->end_date)->format('d/m/Y');

                // CB
                $check->branch_value = $check->branchable['id'];
                $check->standard_value = $check->formula->id;

                // IB
                $check->type_inspection_value =  $check->type['id'];
                $check->category_value =  $check->category['id'];

                // Lab Exam
                $check->calibration_value =  $check->calibration['id'];

                // Lab test
                $check->product_value =  $check->product_show['id'];
                $check->lab_test_value =  $check->test['id'];
            }


            return view('bcertify.auditor.edit',[
                'auditor' => $auditor_information,
                'educations' => $auditor_education,
                'trainings' => $auditor_training,
                'expertise' => $auditor_expertise,
                'works' => $auditor_work,
                'assessments' => $auditor_assessment,
            ]);
        }

        abort(403);
    }


    public function editEducation($token)
    {
        $auditor_information = AuditorInformation::where('token',$token)->first();
        $auditor_education = AuditorEducation::where('auditor_id',$auditor_information->id)->get();

        foreach ($auditor_education as $edu){
            $find = DB::table('tb_country')->select('*')->where('id',$edu->country)->first();
            $edu->contry_show = $find->title;
        }

        return view('bcertify.auditor.editeducation',[
            'educations'=>$auditor_education ,
            'auditor'=>$auditor_information,
        ]);
    }

    public function editTraining($token)
    {
        $auditor_information = AuditorInformation::where('token',$token)->first();
        $auditor_training = AuditorTraining::where('auditor_id',$auditor_information->id)->get()->each(function ($at) {$at->show_type = $at->type_of_assessment;$at->start_training = Carbon::parse($at->start_training)->format('d/m/Y');$at->end_training = Carbon::parse($at->end_training)->format('d/m/Y');});

        return view('bcertify.auditor.edittraining',[
           'trainings' =>  $auditor_training,
            'auditor' => $auditor_information,
        ]);
    }


    public function editExpertise($token)
    {
        $auditor_information = AuditorInformation::where('token',$token)->first();
        $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor_information->id)->get()->each(function($ae) {$ae->auditor;$ae->show_type = $ae->type_of_assessment;});

        foreach ($auditor_expertise as $branch){

            $branch->standard =  $branch->formula->title;
            $branch->typeCheck =  $branch->type['title'];
            $branch->cat =  $branch->category['title'];
            $branch->listCalibation =  $branch->calibration['title'];
            $branch->exProduct=  $branch->product_show['title'];
            $branch->testList=  $branch->test['title'];
            $branch->showBranch=  $branch->branchable['title'];


            // CB
            $branch->show_branch_value=  $branch->branchable['id'];
            $branch->show_standard_value=  $branch->formula['id'];

            //IB
            $branch->show_inspection_value=  $branch->type['id'];
            $branch->show_category_value=  $branch->category['id'];

            // Lab Exam
            $branch->show_calibration_value=  $branch->calibration['id'];

            // Lab Test
            $branch->show_product_value=  $branch->product_show['id'];
            $branch->show_test_value=  $branch->test['id'];


            $status = explode(",",$branch->auditor_status);
            $data_status = array();
            foreach ($status as $show_status){
                $name_status = StatusAuditor::where('id',$show_status)->first();
                array_push($data_status,$name_status->title);
            }

            $branch->find_status = implode(',',$data_status);
        }


        return view('bcertify.auditor.editexpertise',[
            'expertise' =>  $auditor_expertise,
            'auditor' => $auditor_information,
        ]);
    }

    public function editWork($token)
    {
        $auditor_information = AuditorInformation::where('token',$token)->first();
        $auditor_work = AuditorWorkExperience::where('auditor_id',$auditor_information->id)->get();

        return view('bcertify.auditor.editworkexperience',[
            'works' =>  $auditor_work,
            'auditor' => $auditor_information,
        ]);
    }


    public function editAssessment($token)
    {
        $auditor_information = AuditorInformation::where('token',$token)->first();
        $auditor_assessment = AuditorAssessmentExperience::where('auditor_id',$auditor_information->id)->get()->each(function ($aa) {$aa->checkType = $aa->type_of_assessment;});


        foreach ($auditor_assessment as $check){
            $check->standard =  $check->formula->title;
            $check->typeCheck =  $check->type['title'];
            $check->cat =  $check->category['title'];
            $check->listCalibation =  $check->calibration['title'];
            $check->exProduct=  $check->product_show['title'];
            $check->testList=  $check->test['title'];
            $check->showBranch=  $check->branchable['title'];
            $check->showStaus=  $check->statusAuditor['id'];

            $check->first_date = Carbon::parse($check->start_date)->format('d/m/Y');
            $check->second_date = Carbon::parse($check->end_date)->format('d/m/Y');

            // CB
            $check->branch_value = $check->branchable['id'];
            $check->standard_value = $check->formula->id;

            // IB
            $check->type_inspection_value =  $check->type['id'];
            $check->category_value =  $check->category['id'];

            // Lab Exam
            $check->calibration_value =  $check->calibration['id'];

            // Lab test
            $check->product_value =  $check->product_show['id'];
            $check->lab_test_value =  $check->test['id'];
        }


        return view('bcertify.auditor.editassessment',[
            'assessments' =>  $auditor_assessment,
            'auditor' => $auditor_information,
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $model = str_slug('auditor','-');
        if(auth()->user()->can('edit-'.$model)) {
            $auditor_information = AuditorInformation::where('id',$id)->first();
            if ($auditor_information->status == 1){
                $auditor_information->status = 0 ;
            }
            else{
                $auditor_information->status = 1 ;
            }
            $auditor_information->save();
            return redirect(route('bcertify.auditor'))->with('success_message','เปลี่ยนสถานะเรียบร้อย');
        }
        abort(403);

    }


    public function updateStore(Request $request)
    {

        if ($request->choice == "on"){
            $request->choice = 1;
        }
        else{
            $request->choice = 0;
            $request->group = null;
        }


        if ( $request->title == 1){
            $request->title = "นาย";
            $request->title_en = "MR.";
        }
        elseif ($request->title == 2){
            $request->title = "นาง";
            $request->title_en = "Miss.";
        }
        elseif ($request->title == 3){
            $request->title = "นางสาว";
            $request->title_en = "MRS.";
        }

            // dd($request);
        // update information
        $auditor_information = AuditorInformation::where('token',$request->token)->first();
        $auditor_information->title_th = $request->title;
        $auditor_information->fname_th = $request->th_fname;   // เพิ่ม
        $auditor_information->lname_th = $request->th_lname;   // เพิ่ม
        $auditor_information->title_en = $request->title_en;
        $auditor_information->fname_en = $request->en_fname;   // เพิ่ม
        $auditor_information->lname_en = $request->en_lname;   // เพิ่ม
        $auditor_information->address = $request->address;
        $auditor_information->province_id = $request->province;
        $auditor_information->amphur_id = $request->amphur;   // เพิ่ม
        $auditor_information->district_id = $request->district;
        $auditor_information->email = $request->email;
        $auditor_information->tel = $request->tel;
        $auditor_information->department_id = $request->department;
        $auditor_information->position = $request->position;
        $auditor_information->status_ab = $request->choice;
        $auditor_information->group_id = $request->group;
        $auditor_information->status = $request->onOrOff;
        $auditor_information->save();


        // update Education
        $auditor_education = AuditorEducation::where('auditor_id',$auditor_information->id)->get();
        foreach ($auditor_education as $edu_delete){
            $edu_delete->delete();
        }

        $data_edu = json_decode($request->education_history);
        foreach ($data_edu as $data){
            $add_education = new AuditorEducation([
                'auditor_id' => $auditor_information->id,
                'year' => $data->year,
                'level_education' => $data->level_education,
                'major_education' => $data->major_education,
                'school_name' => $data->school_name,
                'country' => $data->country,
                'token' => str_random(16),
            ]);
            $add_education->save();
        }


        // update Training
        $auditor_training = AuditorTraining::where('auditor_id',$auditor_information->id)->get();
        foreach ($auditor_training as $delete_train){
            $delete_train->delete();
        }

        $data_train = json_decode($request->training);
        foreach ($data_train as $train){
            $auditor_training = new AuditorTraining([
                'auditor_id' => $auditor_information->id,
                'course_name' => $train->course_name,
                'department_name' => $train->department_name,
                'start_training' => Carbon::createFromFormat('d/m/Y', $train->start_training),
                'end_training' => Carbon::createFromFormat('d/m/Y', $train->end_training),
                'token' => str_random(16),
            ]);
            $auditor_training->save();
        }


        // update Experience
        $auditor_experiecne = AuditorWorkExperience::where('auditor_id',$auditor_information->id)->get();
        foreach ($auditor_experiecne as $delete_experience){
            $delete_experience->delete();
        }

        $experience = json_decode($request->data_experience);
        foreach ($experience as $dataEx){
            $auditor_exexperience = new AuditorWorkExperience([
                'auditor_id' => $auditor_information->id,
                'year' => $dataEx->year,
                'position' => $dataEx->position,
                'department' => $dataEx->department,
                'role' => $dataEx->role,
                'token' => str_random(16),
            ]);
            $auditor_exexperience->save();
        }



        // update Expertise
        $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor_information->id)->get();
        foreach ($auditor_expertise as $delete_expertise){
            $delete_expertise->delete();
        }

        $data_expertise = json_decode($request->data_all_expertise);
        foreach ($data_expertise as $expertise){
            if (sizeof($expertise) != 0){
                foreach ($expertise as $data){
                    $find_standard = Formula::where('title',$data->standard)->first();
                    $split_status = explode(',',$data->find_status);
                    $number_status = array();
                    foreach ($split_status as $status){
                        $find = StatusAuditor::where('title',$status)->first();
                        array_push($number_status,$find->id);
                    }
                    if ($data->show_type == 1){
                        $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                        $path_branch = CertificationBranch::class;
                        $auditor_expertise = new AuditorExpertise([
                            'auditor_id' => $auditor_information->id,
                            'type_of_assessment' => $data->show_type,
                            'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'scope_name' => $data->scope_name,
                            'specialized_expertise' => $data->specialized_expertise,
                            'auditor_status' => implode(',',$number_status),
                            'token' => str_random(16),
                        ]);
                        $auditor_expertise->save();
                    }
                    elseif ($data->show_type == 2){
                        $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                        $path_branch = InspectBranch::class;
                        $type_exam = InspectType::where('title',$data->typeCheck)->first();
                        $cat = InspectCategory::where('title',$data->cat)->first();
                        $auditor_expertise = new AuditorExpertise([
                            'auditor_id' => $auditor_information->id,
                            'type_of_assessment' => $data->show_type,
                            'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'type_of_examination' =>  (!is_null($type_exam)? $type_exam->id:null),   
                            'examination_category' =>   (!is_null($cat)? $cat->id:null),   
                            'specialized_expertise' => $data->specialized_expertise,
                            'auditor_status' => implode(',',$number_status),
                            'token' => str_random(16),
                        ]);
                        $auditor_expertise->save();
                    }
                    elseif ($data->show_type == 3 || $data->show_type == 4 ){
                        if ($data->show_type == 3 ) {
                            $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                            $path_branch = CalibrationBranch::class;
                            $list = CalibrationItem::where('title',$data->listCalibation)->first();
                            $auditor_expertise = new AuditorExpertise([
                                'auditor_id' => $auditor_information->id,
                                'type_of_assessment' => (!is_null($data)?  $data->show_type :null), 
                                'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                'branch_path' => $path_branch,
                                'calibration_list' =>  (!is_null($list)? $list->id:null), 
                                'specialized_expertise' => $data->specialized_expertise,
                                'auditor_status' => implode(',',$number_status),
                                'token' => str_random(16),
                            ]);
                            $auditor_expertise->save();
                        }
                        elseif ($data->show_type == 4){
                            $find_branch = TestBranch::where('title',$data->showBranch)->first();
                            $find_product = ProductItem::where('title',$data->exProduct)->first();
                            $find_test_list = TestItem::where('title',$data->testList)->first();
                            $path_branch = TestBranch::class;
                            $auditor_expertise = new AuditorExpertise([
                                'auditor_id' => $auditor_information->id,
                                'type_of_assessment' => $data->show_type,
                                'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                                'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                                'branch_path' => $path_branch,
                                'product' => (!is_null($find_product)? $find_product->id:null),
                                'test_list' =>   (!is_null($find_test_list)? $find_test_list->id:null), 
                                'specialized_expertise' => $data->specialized_expertise,
                                'auditor_status' => implode(',',$number_status),
                                'token' => str_random(16),
                            ]);
                            $auditor_expertise->save();
                        }
                    }


                }
            }

        }
        // update experience
        $auditor_experience = AuditorAssessmentExperience::where('auditor_id',$auditor_information->id)->get();
        foreach ($auditor_experience as $delete_experience){
            $delete_experience->delete();
        }

        $data_check = json_decode($request->data_all_check);
        foreach ($data_check as $check){
            if (sizeof($check) != 0){
                foreach ($check as $data){
                    $find_standard = Formula::where('title',$data->standard)->first();
                    $find_status = StatusAuditor::where('id',$data->showStaus)->first();
                    if ($data->checkType == 1){
                        $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                        $path_branch = CertificationBranch::class;
                        $assessment = new AuditorAssessmentExperience([
                            'auditor_id' => $auditor_information->id,
                            'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                            'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                            'type_of_assessment' => $data->checkType,
                            'standard' => (!is_null($find_standard)?$find_standard->id:null),
                            'branch_id' => (!is_null($find_branch)?$find_branch->id:null),
                            'branch_path' => $path_branch,
                            'scope_name' => $data->scope_name,
                            'role' => $data->role,
                            'auditor_status' => (!is_null($find_status)? $find_status->id:null),   
                            'token' => str_random(16),
                        ]);
                        $assessment->save();
                    }
                    elseif ($data->checkType == 2){
                        $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                        $path_branch = InspectBranch::class;
                        $type_exam = InspectType::where('title',$data->typeCheck)->first();
                        $cat = InspectCategory::where('title',$data->cat)->first();
                        $assessment = new AuditorAssessmentExperience([
                            'auditor_id' => $auditor_information->id,
                            'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                            'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                            'type_of_assessment' => $data->checkType,
                            'standard' => (!is_null($find_standard)?$find_standard->id:null),
                            'branch_id' => (!is_null($find_branch)?$find_branch->id:null),
                            'type_of_examination' => !is_null($type_exam)?$type_exam->id:null,
                            'examination_category' => !is_null($cat)?($cat->id):null,
                            'role' => $data->role,
                            'auditor_status' => (!is_null($find_status)? $find_status->id:null),   
                            'token' => str_random(16),
                        ]);
                        $assessment->save();
                    }
                    elseif ($data->checkType == 3 || $data->checkType == 4 ){
                        if ($data->checkType == 3 ) {
                            $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                            $path_branch = CalibrationBranch::class;
                            $list = CalibrationItem::where('title',$data->listCalibation)->first();
                            $assessment = new AuditorAssessmentExperience([
                                'auditor_id' => $auditor_information->id,
                                'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                'type_of_assessment' => $data->checkType,
                                'standard' => (!is_null($find_standard)?$find_standard->id:null),
                                'branch_id' => (!is_null($find_branch)?$find_branch->id:null),

                                'branch_path' => $path_branch,
                                'calibration_list' =>  (!is_null($list)? $list->id:null), 
                                'role' => $data->role,
                                'auditor_status' => (!is_null($find_status)? $find_status->id:null), 
                                'token' => str_random(16),
                            ]);
                            $assessment->save();
                        }
                        elseif ($data->checkType == 4){
                            $find_branch = TestBranch::where('title',$data->showBranch)->first();
                            $path_branch = TestBranch::class;
                            $find_product = ProductItem::where('title',$data->exProduct)->first();
                            $find_test_list = TestItem::where('title',$data->testList)->first();
                            $assessment = new AuditorAssessmentExperience([
                                'auditor_id' => $auditor_information->id,
                                'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                'type_of_assessment' => $data->checkType,
                                'standard' => (!is_null($find_standard)?$find_standard->id:null),
                                'branch_id' => (!is_null($find_branch)?$find_branch->id:null),
                                'branch_path' => $path_branch,
                                'product' => (!is_null($find_product)?$find_product->id:null),
                                'test_list' => !is_null($find_test_list)?$find_test_list->id:null,
                                'role' =>  $data->role,
                                'auditor_status' =>  (!is_null($find_status)?$find_status->id:null),
                                'token' => str_random(16),
                            ]);
                            $assessment->save();
                        }
                    }

                }
            }
        }
        return redirect(route('bcertify.auditor'))->with('success_message','Update ข้อมูลเรียบร้อย');

    }


    public function updateEducation(Request $request)
    {
        $auditor = AuditorInformation::where('id',$request->auditor)->first();
        $auditor_education = AuditorEducation::where('auditor_id',$auditor->id)->get();
        foreach ($auditor_education as $edu_delete){
            $edu_delete->delete();
        }

        $new_education = json_decode($request->education_history);

        foreach ($new_education as $data){
            $add_education = new AuditorEducation([
                'auditor_id' => $auditor->id,
                'year' => $data->year,
                'level_education' => $data->level_education,
                'major_education' => $data->major_education,
                'school_name' => $data->school_name,
                'country' => $data->country,
                'token' => str_random(16),
            ]);
            $add_education->save();
        }

       return redirect(route('bcertify.auditor.show',['token'=>$auditor->token]))->with('success_message','แก้ไข Education เรีนบร้อยแล้ว');
    }


    public function updateTraining(Request $request)
    {

        $auditor = AuditorInformation::where('id',$request->auditor)->first();
        $auditor_training = AuditorTraining::where('auditor_id',$auditor->id)->get();
        foreach ($auditor_training as $delete_train){
            $delete_train->delete();
        }

        $data_train = json_decode($request->training);
        foreach ($data_train as $train){
            $auditor_training = new AuditorTraining([
                'auditor_id' => $auditor->id,
                'course_name' => $train->course_name,
                'department_name' => $train->department_name,
                'start_training' => Carbon::createFromFormat('d/m/Y', $train->start_training),
                'end_training' => Carbon::createFromFormat('d/m/Y', $train->end_training),
                'token' => str_random(16),
            ]);
            $auditor_training->save();
        }

        return redirect(route('bcertify.auditor.show',['token'=>$auditor->token]))->with('success_message','แก้ไข Training เรีนบร้อยแล้ว');

    }


    public function updateWork(Request $request)
    {

        $auditor = AuditorInformation::where('id',$request->auditor)->first();
        $auditor_training = AuditorWorkExperience::where('auditor_id',$auditor->id)->get();

        foreach ($auditor_training as $delete_experience){
            $delete_experience->delete();
        }

        $experience = json_decode($request->work);
        foreach ($experience as $dataEx){
            $auditor_exexperience = new AuditorWorkExperience([
                'auditor_id' => $auditor->id,
                'year' => $dataEx->year,
                'position' => $dataEx->position,
                'department' => $dataEx->department,
                'role' => $dataEx->role,
                'token' => str_random(16),
            ]);
            $auditor_exexperience->save();
        }

        return redirect(route('bcertify.auditor.show',['token'=>$auditor->token]))->with('success_message','แก้ไข Work Experience เรีนบร้อยแล้ว');

    }


    public function updateExpertise(Request $request)
    {
        // update Expertise
        $auditor = AuditorInformation::where('id',$request->auditor)->first();
        $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor->id)->get();
        foreach ($auditor_expertise as $delete_expertise){
            $delete_expertise->delete();
        }

        $data_expertise = json_decode($request->data_all_expertise);
        foreach ($data_expertise as $expertise){
            if (sizeof($expertise) != 0){
                foreach ($expertise as $data){
                    $find_standard = Formula::where('title',$data->standard)->first();
                    $split_status = explode(',',$data->find_status);
                    $number_status = array();
                    foreach ($split_status as $status){
                        $find = StatusAuditor::where('title',$status)->first();
                        array_push($number_status,$find->id);
                    }
                    if ($data->show_type == 1){
                        $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                        $path_branch = CertificationBranch::class;
                        $auditor_expertise = new AuditorExpertise([
                            'auditor_id' => $auditor->id,
                            'type_of_assessment' => $data->show_type,
                            'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'scope_name' => $data->scope_name,
                            'specialized_expertise' => $data->specialized_expertise,
                            'auditor_status' => implode(',',$number_status),
                            'token' => str_random(16),
                        ]);
                        $auditor_expertise->save();
                    }
                    elseif ($data->show_type == 2){
                        $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                        $path_branch = InspectBranch::class;
                        $type_exam = InspectType::where('title',$data->typeCheck)->first();
                        $cat = InspectCategory::where('title',$data->cat)->first();
                        $auditor_expertise = new AuditorExpertise([
                            'auditor_id' => $auditor->id,
                            'type_of_assessment' => $data->show_type,
                            'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'type_of_examination' =>   (!is_null($type_exam)? $type_exam->id:null),    
                            'examination_category' =>  (!is_null($cat)? $cat->id:null),    
                            'specialized_expertise' => $data->specialized_expertise,
                            'auditor_status' => implode(',',$number_status),
                            'token' => str_random(16),
                        ]);
                        $auditor_expertise->save();
                    }
                    elseif ($data->show_type == 3 || $data->show_type == 4 ){
                        if ($data->show_type == 3 ) {
                            $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                            $path_branch = CalibrationBranch::class;
                            $list = CalibrationItem::where('title',$data->listCalibation)->first();
                            $auditor_expertise = new AuditorExpertise([
                                'auditor_id' => $auditor->id,
                                'type_of_assessment' => $data->show_type,
                                'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                                'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                                'branch_path' => $path_branch,
                                'calibration_list' =>    (!is_null($list)? $list->id:null),     
                                'specialized_expertise' => $data->specialized_expertise,
                                'auditor_status' => implode(',',$number_status),
                                'token' => str_random(16),
                            ]);
                            $auditor_expertise->save();
                        }
                        elseif ($data->show_type == 4){
                            $find_branch = TestBranch::where('title',$data->showBranch)->first();
                            $find_product = ProductItem::where('title',$data->exProduct)->first();
                            $find_test_list = TestItem::where('title',$data->testList)->first();
                            $path_branch = TestBranch::class;
                            $auditor_expertise = new AuditorExpertise([
                                'auditor_id' => $auditor->id,
                                'type_of_assessment' => $data->show_type,
                                'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                                'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                                'branch_path' => $path_branch,
                                'product' => (!is_null($find_product)? $find_product->id :null),
                                'test_list' =>    (!is_null($find_test_list)? $find_test_list->id:null), 
                                'specialized_expertise' => $data->specialized_expertise,
                                'auditor_status' => implode(',',$number_status),
                                'token' => str_random(16),
                            ]);
                            $auditor_expertise->save();
                        }
                    }


                }
            }

        }
        return redirect(route('bcertify.auditor.show',['token'=>$auditor->token]))->with('success_message','แก้ไข Expertise เรีนบร้อยแล้ว');

    }


    public function updateAssessment(Request $request)
    {

        // update experience
        $auditor = AuditorInformation::where('id',$request->auditor)->first();
        $auditor_experience = AuditorAssessmentExperience::where('auditor_id',$auditor->id)->get();
        foreach ($auditor_experience as $delete_experience){
            $delete_experience->delete();
        }

        $data_check = json_decode($request->data_all_check);
        foreach ($data_check as $check){
            if (sizeof($check) != 0){
                foreach ($check as $data){
                    $find_standard = Formula::where('title',$data->standard)->first();
                    $find_status = StatusAuditor::where('id',$data->showStaus)->first();
                    if ($data->checkType == 1){
                        $find_branch = CertificationBranch::where('title',$data->showBranch)->first();
                        $path_branch = CertificationBranch::class;
                        $assessment = new AuditorAssessmentExperience([
                            'auditor_id' => $auditor->id,
                            'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                            'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                            'type_of_assessment' => $data->checkType,
                            'standard' =>  (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'scope_name' => $data->scope_name,
                            'role' => $data->role,
                            'auditor_status' => (!is_null($find_status)? $find_status->id:null),   
                            'token' => str_random(16),
                        ]);
                        $assessment->save();
                    }
                    elseif ($data->checkType == 2){
                        $find_branch = InspectBranch::where('title',$data->showBranch)->first();
                        $path_branch = InspectBranch::class;
                        $type_exam = InspectType::where('title',$data->typeCheck)->first();
                        $cat = InspectCategory::where('title',$data->cat)->first();
                        $assessment = new AuditorAssessmentExperience([
                            'auditor_id' => $auditor->id,
                            'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                            'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                            'type_of_assessment' => $data->checkType,
                            'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                            'branch_id' => (!is_null($find_branch)? $find_branch->id:null),  
                            'branch_path' => $path_branch,
                            'type_of_examination' =>    (!is_null($type_exam)? $type_exam->id:null),     
                            'examination_category' =>  (!is_null($cat)? $cat->id:null),   
                            'role' => $data->role,
                            'auditor_status' => (!is_null($find_status)? $find_status->id:null),   
                            'token' => str_random(16),
                        ]);
                        $assessment->save();
                    }
                    elseif ($data->checkType == 3 || $data->checkType == 4 ){
                        if ($data->checkType == 3 ) {
                            $find_branch = CalibrationBranch::where('title',$data->showBranch)->first();
                            $path_branch = CalibrationBranch::class;
                            $list = CalibrationItem::where('title',$data->listCalibation)->first();
                            $assessment = new AuditorAssessmentExperience([
                                'auditor_id' => $auditor->id,
                                'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                'type_of_assessment' => $data->checkType,
                                'standard' => (!is_null($find_standard)? $find_standard->id:null),  
                                'branch_id' =>  (!is_null($find_branch)? $find_branch->id:null),  
                                'branch_path' => $path_branch,
                                'calibration_list' => (!is_null($list)? $list->id:null),     
                                'role' => $data->role,
                                'auditor_status' =>  (!is_null($find_status)? $find_status->id:null),   
                                'token' => str_random(16),
                            ]);
                            $assessment->save();
                        }
                        elseif ($data->checkType == 4){
                            $find_branch = TestBranch::where('title',$data->showBranch)->first();
                            $path_branch = TestBranch::class;
                            $find_product = ProductItem::where('title',$data->exProduct)->first();
                            $find_test_list = TestItem::where('title',$data->testList)->first();
                            $assessment = new AuditorAssessmentExperience([
                                'auditor_id' => $auditor->id,
                                'start_date' => Carbon::createFromFormat('d/m/Y', $data->first_date),
                                'end_date' => Carbon::createFromFormat('d/m/Y', $data->second_date),
                                'type_of_assessment' => $data->checkType,
                                'standard' => (!is_null($find_standard)?$find_standard->id:null),
                                'branch_id' => (!is_null($find_branch)?$find_branch->id:null),
                                'branch_path' => $path_branch,
                                'product' => (!is_null($find_product)? $find_product->id :null),
                                'test_list' =>  (!is_null($find_test_list)? $find_test_list->id:null), 
                                'role' =>  $data->role,
                                'auditor_status' =>  (!is_null($find_status)? $find_status->id:null),   
                                'token' => str_random(16),
                            ]);
                            $assessment->save();
                        }
                    }

                }
            }
        }
        return redirect(route('bcertify.auditor.show',['token'=>$auditor->token]))->with('success_message','แก้ไข Assessment เรีนบร้อยแล้ว');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {

        $model = str_slug('auditor','-');
        if(auth()->user()->can('delete-'.$model)) {
            try {
                $auditor_information = AuditorInformation::where('token',$token)->first();
                $auditor_education = AuditorEducation::where('auditor_id',$auditor_information->id)->get()->count();
                $auditor_training = AuditorTraining::where('auditor_id',$auditor_information->id)->get()->count();
                $auditor_expertise = AuditorExpertise::where('auditor_id',$auditor_information->id)->get()->count();
                $auditor_work = AuditorWorkExperience::where('auditor_id',$auditor_information->id)->get()->count();
                $auditor_assessment = AuditorAssessmentExperience::where('auditor_id',$auditor_information->id)->get()->count();
                $board = BoardAuditorInformation::where('auditor_id',$auditor_information->id)->get()->count();
                $review = BoardReviewInformation::where('auditor_id',$auditor_information->id)->get()->count();

                //$auditor_education != 0 || $auditor_training != 0 || $auditor_expertise != 0 || $auditor_work != 0 || $auditor_assessment != 0 ||
                if ($board != 0 || $review != 0){
                    return redirect(route('bcertify.auditor'))->with('flash_message','Auditor ยังใช้งานในตำแหน่งอื่น');
                }
                else{

                    $auditor_information->auditor_education()->delete();
                    $auditor_information->auditor_training()->delete();
                    $auditor_information->auditor_expertise()->delete();
                    $auditor_information->auditor_work_experience()->delete();
                    $auditor_information->auditor_assessment()->delete();


                    $auditor_board = BoardAuditorInformation::where('auditor_id',$auditor_information->id)->get()->each(function ($ab) {$ab->delete();});
                    $auditor_review = BoardReviewInformation::where('auditor_id',$auditor_information->id)->get()->each(function ($ar) {$ar->delete();});
                    $auditor_information->delete();
                    return redirect(route('bcertify.auditor'))->with('success_message','ลบ Auditor เรีนบร้อยแล้ว');
                }
            }
            catch (\Exception $x){
                return redirect(route('bcertify.auditor'))->with('flash_message','Auditor ยังใช้งานในตำแหน่งอื่น');
            }
        }
        abort(403);
    }


    public function apiStandard(Request $request)
    {
        $data = array();

        if ($request->select == 1){
            $branch = CertificationBranch::where('state',1)->get();
            array_push($data,$branch);
        }
        elseif ($request->select == 2){
            $branch = InspectBranch::where('state',1)->get();
            array_push($data,$branch);
        }
        elseif ($request->select == 3 ){
            $branch = CalibrationBranch::where('state',1)->get();
            array_push($data,$branch);
        }
        elseif ($request->select == 4 ){
            $branch = TestBranch::where('state',1)->get();
            array_push($data,$branch);
        }

        if ($request->select == 3 || $request->select == 4){
            $request->select = 3 ;
        }
        $formulas = Formula::where('applicant_type',$request->select)->where('state',1)->get();
        array_push($data,$formulas);
        $data_formulas = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data_formulas;
    }


    public function apiScope(Request $request)
    {
        $branch = CertificationBranch::where('title',$request->select_branch)->where('state',1)->first();
        $scope = CertificationScope::where('certification_branch_id',$branch->id)->where('state',1)->first();
        $data = array();
        if ($scope->scope_type == "ISIC"){
            $information_scope = IndustryType::where('state',1)->get();
            array_push($data,$information_scope);
        }
        elseif ($scope->scope_type == "IAF"){
            $information_scope = Iaf::where('state',1)->get();
            array_push($data,$information_scope);
        }
        elseif ($scope->scope_type == "Enms"){
            $information_scope = Enms::where('state',1)->get();
            array_push($data,$information_scope);
        }
        elseif ($scope->scope_type == "GHG"){
            $information_scope = Ghg::where('state',1)->get();
            array_push($data,$information_scope);
        }
        $data_scope = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data_scope ;
    }


    public function apiCalibration(Request $request)
    {
        $branch = CalibrationBranch::where('title',$request->select_branch)->where('state',1)->first();
        $calibration = CalibrationItem::where('state',1)->where('calibration_branch_id',$branch->id)->get();
        $data_calibration = json_encode($calibration,JSON_UNESCAPED_UNICODE);
        return $data_calibration;
    }

    public function apiInspection(Request $request)
    {
        $inspection = array();
        $typeInspection = InspectType::where('state',1)->get();
        $categoriesInspection = InspectCategory::where('state',1)->get();
        array_push($inspection,$typeInspection,$categoriesInspection);
        $data_typeInspection = json_encode($inspection,JSON_UNESCAPED_UNICODE);
        return $data_typeInspection;
    }


    public function apiProduct(Request $request)
    {
        $data = array();
        $checkBranch = TestBranch::where('title',$request->select_branch)->where('state',1)->first();
        $products = ProductItem::where('test_branch_id',$checkBranch->id)->where('state',1)->get();
        $list = TestItem::where('test_branch_id',$checkBranch->id)->where('state',1)->get();
        array_push($data,$products,$list);
        $data_product = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data_product;


    }


    public function apiProvince(Request $request)
    {

        $amphur = Amphur::whereNull('state')->where('PROVINCE_ID',$request->select)->orderBy('AMPHUR_NAME','asc')->get();
        $data_amphur = json_encode($amphur,JSON_UNESCAPED_UNICODE);
        echo $data_amphur ;
    }

    public function apiAmphur(Request $request)
    {

        $district = District::whereNull('state')->where('AMPHUR_ID',$request->select)->orderBy('DISTRICT_NAME','asc')->get();
        $data_district = json_encode($district,JSON_UNESCAPED_UNICODE);
        echo $data_district ;
    }


}
