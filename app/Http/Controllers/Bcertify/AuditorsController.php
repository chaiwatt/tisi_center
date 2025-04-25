<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use HP;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditorsController extends Controller
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
           
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new AuditorInformation;
 
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
 

            if ($filter['filter_formulas'] != "" ){
                $Query = $Query->whereHas('auditor_expertise',function($query) use ($filter) {
                    $query->where('standard', '=',$filter['filter_formulas']);
                });
            }


            $informationAuditors = $Query->sortable()
                ->paginate($filter['perPage']);

            
            return view('bcertify.auditors.index',[
                                                    'auditors' => $informationAuditors,
                                                    'filter' => $filter
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
            return view('bcertify.auditors.create');
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
             $requestData = $request->all();

             if(isset($requestData['information'])){
                 $prefix_th =  ['1'=>'นาย','2'=>'นาง','3'=>'นางสาว'];
                 $prefix_en =  ['1'=>'นาย','2'=>'นาง','3'=>'นางสาว'];
                $information = $requestData['information'];
                $information['group_id'] =  !empty($information['group']) ? $information['group'] : null;   
                if(isset($request->choice)){
                    $information['status_ab'] = 1;
                }else{ 
                    $information['status_ab'] = 0;
                  
                }

// start ข้อมูลส่วนตัว
                // ข้อมูลส่วนตัว
                $information['number_auditor'] =  !empty($information['regis_number']) ? $information['regis_number'] : null;
                $information['title_th'] = array_key_exists($information['title'],$prefix_th) ?  $prefix_th[$information['title']] : null ;
                $information['fname_th'] =  !empty($information['th_fname']) ? $information['th_fname'] : null;
                $information['lname_th'] =  !empty($information['th_lname']) ? $information['th_lname'] : null;
                $information['title_en'] =   array_key_exists($information['title'],$prefix_en) ?  $prefix_en[$information['title']] : null ;
                $information['fname_en'] =  !empty($information['en_fname']) ? $information['en_fname'] : null;
                $information['lname_en'] =  !empty($information['en_lname']) ? $information['en_lname'] : null;
                //  ที่อยู่
                $information['province_id'] =  !empty($information['province']) ? $information['province'] : null;
                $information['amphur_id'] =  !empty($information['amphur']) ? $information['amphur'] : null;
                $information['district_id'] =  !empty($information['district']) ? $information['district'] : null;
                //เลขทะเบียนผู้ประเมิน
                $information['department_id'] =  !empty($information['department']) ? $information['department'] : null;
                $information['status'] =  !empty($information['onOrOff']) ? $information['onOrOff'] : null;
                $information['token'] = str_random(16); 
                $information['user_id'] = Auth::user()->runrecno;
                $auditor_information = AuditorInformation::create($information);
   // end ข้อมูลส่วนตัว     
    // start การศึกษา   
    if(isset($requestData['education'])){
        $this->ItemsEvaluate($requestData['education'],$auditor_information);
    }
    // end การศึกษา   
    // start การฝึกอบรม   
    if(isset($requestData['training'])){
    $this->ItemsAuditorTraining($requestData['training'],$auditor_information);
    }
    // end   การฝึกอบรม   

    // start ข้อมูลความเชี่ยวชาญ CB  
    if(isset($requestData['expertise_cb'])){
    $this->Items_Auditor_Expertise_Cb($requestData['expertise_cb'],$auditor_information);
    }
    // end   ข้อมูลความเชี่ยวชาญ CB   
    // start ข้อมูลความเชี่ยวชาญ IB  
    if(isset($requestData['expertise_ib'])){
    $this->Items_Auditor_Expertise_Ib($requestData['expertise_ib'],$auditor_information);
    }
    // end   ข้อมูลความเชี่ยวชาญ IB      
    // start ข้อมูลความเชี่ยวชาญ  LAB สอบเทียบ  
    if(isset($requestData['expertise_calibration'])){
    $this->Items_Auditor_Expertise__Calibration($requestData['expertise_calibration'],$auditor_information);
    }
    // end   ข้อมูลความเชี่ยวชาญ  LAB สอบเทียบ     
    // start ข้อมูลความเชี่ยวชาญ  LAB ทดสอบ  
    if(isset($requestData['expertise_test'])){
    $this->Items_Auditor_Expertise_Test($requestData['expertise_test'],$auditor_information);
    }
    // end   ข้อมูลความเชี่ยวชาญ  LAB ทดสอบ    

    // start ประสบการณ์การทำงาน   
    if(isset($requestData['experience'])){
    $this->ItemsAuditorWorkExperience($requestData['experience'],$auditor_information);
    }
    // end   ประสบการณ์การทำงาน   

    // start ประสบการณ์การตรวจประเมิน cb
    if(isset($requestData['experience_cb'])){
    $this->ItemsAuditorAssessmentExperienceCb($requestData['experience_cb'],$auditor_information);
    }
    // end   ประสบการณ์การตรวจประเมิน cb
    // start ประสบการณ์การตรวจประเมิน ib
    if(isset($requestData['experience_ib'])){
    $this->ItemsAuditorAssessmentExperienceIB($requestData['experience_ib'],$auditor_information);
    }
    // end   ประสบการณ์การตรวจประเมิน ib
    // start ประสบการณ์การตรวจประเมิน   LAB สอบเทียบ
    if(isset($requestData['experience_calibration'])){
    $this->Items_Auditor_Assessment_Experience_LAB_Calibration($requestData['experience_calibration'],$auditor_information);
    }
    // end   ประสบการณ์การตรวจประเมิน  LAB สอบเทียบ
    // start ประสบการณ์การตรวจประเมิน   LAB ทดสอบ
    if(isset($requestData['experience_test'])){
    $this->Items_Auditor_Assessment_Experience_LAB_Text($requestData['experience_test'],$auditor_information);
    }
    // end   ประสบการณ์การตรวจประเมิน  LAB ทดสอบ
 
   }
             return redirect('bcertify/auditors')->with('flash_message', 'เรียบร้อยแล้ว');
         }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('auditor','-');
        if(auth()->user()->can('edit-'.$model)) {
           $prefix_th =  ['นาย'=>'1','นาง'=>'2','นางสาว'=>'3'];
           $information = AuditorInformation::findOrFail(base64_decode($id));
           $information->title = !empty($information->title_th) && array_key_exists($information->title_th,$prefix_th) ?  $prefix_th[$information->title_th]: null;
            if($information->status == 0){
                $information->onOrOff = true;
            }
            if($information->status_ab == 1){
                $information->choice = true;
            }
 
           return view('bcertify.auditors.edit', compact('information'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('auditor','-');
        if(auth()->user()->can('add-'.$model)) {
             $requestData = $request->all();
   
             if(isset($requestData['information'])){
                 $prefix_th =  ['1'=>'นาย','2'=>'นาง','3'=>'นางสาว'];
                 $prefix_en =  ['1'=>'นาย','2'=>'นาง','3'=>'นางสาว'];
                $information = $requestData['information'];
                $information['group_id'] =  !empty($information['group']) ? $information['group'] : null;   
                if(isset($request->choice)){
                    $information['status_ab'] = 1;
                }else{ 
                    $information['status_ab'] = 0;
                }

// start ข้อมูลส่วนตัว
                // ข้อมูลส่วนตัว
                $information['number_auditor'] =  !empty($information['regis_number']) ? $information['regis_number'] : null;
                $information['title_th'] = array_key_exists($information['title'],$prefix_th) ?  $prefix_th[$information['title']] : null ;
                $information['fname_th'] =  !empty($information['th_fname']) ? $information['th_fname'] : null;
                $information['lname_th'] =  !empty($information['th_lname']) ? $information['th_lname'] : null;
                $information['title_en'] =   array_key_exists($information['title'],$prefix_en) ?  $prefix_en[$information['title']] : null ;
                $information['fname_en'] =  !empty($information['en_fname']) ? $information['en_fname'] : null;
                $information['lname_en'] =  !empty($information['en_lname']) ? $information['en_lname'] : null;
                //  ที่อยู่
                $information['province_id'] =  !empty($information['province']) ? $information['province'] : null;
                $information['amphur_id'] =  !empty($information['amphur']) ? $information['amphur'] : null;
                $information['district_id'] =  !empty($information['district']) ? $information['district'] : null;
                //เลขทะเบียนผู้ประเมิน
                $information['department_id'] =  !empty($information['department']) ? $information['department'] : null;
                $information['status'] =  !empty($information['onOrOff']) ? $information['onOrOff'] : null;
                $information['token'] = str_random(16); 
                $information['user_id'] = Auth::user()->runrecno;
                $auditor_information = AuditorInformation::findOrFail($id);
                $auditor_information->update($information);
   // end ข้อมูลส่วนตัว 

   // start การศึกษา   
      if(isset($requestData['education'])){
          $this->ItemsEvaluate($requestData['education'],$auditor_information);
      }else{
          $auditor_information->auditor_education()->delete();
      }
    // end การศึกษา   
    // start การฝึกอบรม   
     if(isset($requestData['training'])){
           $this->ItemsAuditorTraining($requestData['training'],$auditor_information);
      }else{
            $auditor_information->auditor_training()->delete();
      }
    // end   การฝึกอบรม   

    // start ข้อมูลความเชี่ยวชาญ CB  
    if(isset($requestData['expertise_cb'])){
        $this->Items_Auditor_Expertise_Cb($requestData['expertise_cb'],$auditor_information);
    }else{
        $auditor_information->auditor_expertise_cb()->delete();
     }
    // end   ข้อมูลความเชี่ยวชาญ CB   
   // start ข้อมูลความเชี่ยวชาญ IB  
    if(isset($requestData['expertise_ib'])){
        $this->Items_Auditor_Expertise_Ib($requestData['expertise_ib'],$auditor_information);
    }else{
        $auditor_information->auditor_expertise_ib()->delete();
    }
    // end   ข้อมูลความเชี่ยวชาญ IB      
   // start ข้อมูลความเชี่ยวชาญ  LAB สอบเทียบ  
   if(isset($requestData['expertise_calibration'])){
       $this->Items_Auditor_Expertise__Calibration($requestData['expertise_calibration'],$auditor_information);
    }else{
        $auditor_information->auditor_expertise_calibration()->delete();
    }
    // end   ข้อมูลความเชี่ยวชาญ  LAB สอบเทียบ     
    // start ข้อมูลความเชี่ยวชาญ  LAB ทดสอบ  
   if(isset($requestData['expertise_test'])){
       $this->Items_Auditor_Expertise_Test($requestData['expertise_test'],$auditor_information);
    }else{
        $auditor_information->auditor_expertise_test()->delete();
    }
    // end   ข้อมูลความเชี่ยวชาญ  LAB ทดสอบ    

   // start ประสบการณ์การทำงาน   
    if(isset($requestData['experience'])){
        $this->ItemsAuditorWorkExperience($requestData['experience'],$auditor_information);
    }else{
        $auditor_information->auditor_work_experience()->delete();
    }
     // end   ประสบการณ์การทำงาน   
 
     // start ประสบการณ์การตรวจประเมิน cb
    if(isset($requestData['experience_cb'])){
        $this->ItemsAuditorAssessmentExperienceCb($requestData['experience_cb'],$auditor_information);
    }else{
        $auditor_information->auditor_assessment_experience_cb()->delete();
    }
     // end   ประสบการณ์การตรวจประเมิน cb
     // start ประสบการณ์การตรวจประเมิน ib
     if(isset($requestData['experience_ib'])){
        $this->ItemsAuditorAssessmentExperienceIB($requestData['experience_ib'],$auditor_information);
    }else{
        $auditor_information->auditor_assessment_experience_ib()->delete();
    }
     // end   ประสบการณ์การตรวจประเมิน ib
     // start ประสบการณ์การตรวจประเมิน   LAB สอบเทียบ
     if(isset($requestData['experience_calibration'])){
        $this->Items_Auditor_Assessment_Experience_LAB_Calibration($requestData['experience_calibration'],$auditor_information);
    }else{
        $auditor_information->auditor_assessment_experience_lab_calibration()->delete();
    }
     // end   ประสบการณ์การตรวจประเมิน  LAB สอบเทียบ
     // start ประสบการณ์การตรวจประเมิน   LAB ทดสอบ
     if(isset($requestData['experience_test'])){
        $this->Items_Auditor_Assessment_Experience_LAB_Text($requestData['experience_test'],$auditor_information);
     }else{
        $auditor_information->auditor_assessment_experience_lab_test()->delete();
     }
     // end   ประสบการณ์การตรวจประเมิน  LAB ทดสอบ
     }
              
             return redirect('bcertify/auditors')->with('flash_message', 'เรียบร้อยแล้ว');
         }
        abort(403);
    }


    // การศึกษา
    public function ItemsEvaluate($data, $auditor_information) {  
        try {
            $auditor_information->auditor_education()->delete();
            foreach ((array)$data['year'] as $key => $item){
                $add_education = new AuditorEducation;
                $add_education->auditor_id          =   $auditor_information->id;
                $add_education->year                =   !empty($item) ? $item : null;   
                $add_education->level_education     =   !empty($data['education'][$key]) ? $data['education'][$key] : null;   
                $add_education->major_education     =   !empty($data['major'][$key]) ? $data['major'][$key] : null;   
                $add_education->school_name         =   !empty($data['school_name'][$key]) ? $data['school_name'][$key] : null;   
                $add_education->country             =   !empty($data['country'][$key]) ? $data['country'][$key] : null;   
                $add_education->token               =   str_random(16); 
                $add_education->save(); 
            }
        } catch (Exception $x) {
            throw $x;
        }
    }

    // การฝึกอบรม
    public function ItemsAuditorTraining($data, $auditor_information) {  
        try {
            $auditor_information->auditor_training()->delete();
            foreach ((array)$data['course_name'] as $key => $item){
                $add_education = new AuditorTraining;
                $add_education->auditor_id       =    $auditor_information->id;
                $add_education->course_name      =    !empty($item) ? $item : null;   
                $add_education->department_name  =    !empty($data['department_name'][$key]) ? $data['department_name'][$key] : null;   
                $add_education->start_training   =    !empty($data['start_training'][$key]) ? HP::convertDate($data['start_training'][$key],true) : null;   
                $add_education->end_training     =    !empty($data['end_training'][$key]) ? HP::convertDate($data['end_training'][$key],true) : null;   
                $add_education->token = str_random(16); 
                $add_education->save(); 
            }
        } catch (Exception $x) {
            throw $x;
        }   
    }

      // ความเชี่ยวชาญ cb
      public function Items_Auditor_Expertise_Cb($data, $auditor_information) {  
        try {
            $auditor_information->auditor_expertise_cb()->delete();
            foreach ((array)$data['standard'] as $key => $item){
                $expertise_cb = new AuditorExpertise; 
                $expertise_cb->auditor_id           =    $auditor_information->id;
                $expertise_cb->type_of_assessment   =    1;   // CB  
                $expertise_cb->standard             =    $item;    // มาตรฐาน
                $expertise_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $expertise_cb->branch_path          =     CertificationBranch::class; 
                $expertise_cb->scope_name           =    !empty($data['check_scope'][$key]) ? $data['check_scope'][$key] : null;   // ขอบข่าย
                $expertise_cb->specialized_expertise=    !empty($data['specialized_expertise'][$key]) ? $data['specialized_expertise'][$key] : null;   // ความเชี่ยวชาญเฉพาะด้าน
                $expertise_cb->auditor_status       =    !empty($data['number_status'][$key]) ? $data['number_status'][$key] : null;   // สถานะผู้ประเมิน
                $expertise_cb->token                =    str_random(16); 
                $expertise_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }

      // ความเชี่ยวชาญ ib
      public function Items_Auditor_Expertise_Ib($data, $auditor_information) {  
        try {
            $auditor_information->auditor_expertise_ib()->delete();
            foreach ((array)$data['standard'] as $key => $item){
                $expertise_cb = new AuditorExpertise; 
                $expertise_cb->auditor_id           =    $auditor_information->id;
                $expertise_cb->type_of_assessment   =    2;   // IB  
                $expertise_cb->standard             =    $item;    // มาตรฐาน
                $expertise_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $expertise_cb->branch_path          =     InspectBranch::class;  
                $expertise_cb->type_of_examination  =    !empty($data['type_of_examination'][$key]) ? $data['type_of_examination'][$key] : null;   // ประเภทหน่วยตรวจ
                $expertise_cb->examination_category =    !empty($data['examination_category'][$key]) ? $data['examination_category'][$key] : null;   // หมวดหมู่การตรวจ
                $expertise_cb->specialized_expertise=    !empty($data['specialized_expertise'][$key]) ? $data['specialized_expertise'][$key] : null;   // ความเชี่ยวชาญเฉพาะด้าน
                $expertise_cb->auditor_status       =    !empty($data['number_status'][$key]) ? $data['number_status'][$key] : null;   // สถานะผู้ประเมิน
                $expertise_cb->token                =    str_random(16); 
                $expertise_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }
      // ความเชี่ยวชาญ LAB สอบเทียบ
      public function Items_Auditor_Expertise__Calibration($data, $auditor_information) {  
        try {
            $auditor_information->auditor_expertise_calibration()->delete();
            foreach ((array)$data['standard'] as $key => $item){
                $expertise_cb = new AuditorExpertise; 
                $expertise_cb->auditor_id           =    $auditor_information->id;
                $expertise_cb->type_of_assessment   =    3;   // LAB สอบเทียบ  
                $expertise_cb->standard             =    $item;    // มาตรฐาน
                $expertise_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $expertise_cb->branch_path          =     CalibrationBranch::class;  
                $expertise_cb->calibration_list     =    !empty($data['calibration_list'][$key]) ? $data['calibration_list'][$key] : null;   // หมวดหมู่การตรวจ
                $expertise_cb->auditor_status       =    !empty($data['number_status'][$key]) ? $data['number_status'][$key] : null;   // สถานะผู้ประเมิน
                $expertise_cb->specialized_expertise=    !empty($data['specialized_expertise'][$key]) ? $data['specialized_expertise'][$key] : null;   // ความเชี่ยวชาญเฉพาะด้าน
                $expertise_cb->token                =    str_random(16); 
                $expertise_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }

     // ความเชี่ยวชาญ LAB ทดสอบ
      public function Items_Auditor_Expertise_Test($data, $auditor_information) {  
        try {
            $auditor_information->auditor_expertise_test()->delete();
            foreach ((array)$data['standard'] as $key => $item){
                $expertise_cb = new AuditorExpertise; 
                $expertise_cb->auditor_id           =    $auditor_information->id;
                $expertise_cb->type_of_assessment   =    4;   // LAB ทดสอบ  
                $expertise_cb->standard             =    $item;    // มาตรฐาน
                $expertise_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $expertise_cb->branch_path          =     TestBranch::class;  
                $expertise_cb->product              =     !empty($data['product'][$key]) ? $data['product'][$key] : null;   // ผลิตภัณฑ์
                $expertise_cb->test_list            =    !empty($data['test_list'][$key]) ? $data['test_list'][$key] : null;   // รายการทดสอบ
                $expertise_cb->specialized_expertise=    !empty($data['specialized_expertise'][$key]) ? $data['specialized_expertise'][$key] : null;   // ความเชี่ยวชาญเฉพาะด้าน
                $expertise_cb->auditor_status       =    !empty($data['number_status'][$key]) ? $data['number_status'][$key] : null;   // สถานะผู้ประเมิน
                $expertise_cb->token                =    str_random(16); 
                $expertise_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }

    // ประสบการณ์การทำงาน
    public function ItemsAuditorWorkExperience($data, $auditor_information) {  
        try {
            $auditor_information->auditor_work_experience()->delete();
            foreach ((array)$data['experience_year'] as $key => $item){
                $auditor_exexperience = new AuditorWorkExperience;
                $auditor_exexperience->auditor_id       =    $auditor_information->id;
                $auditor_exexperience->year             =    !empty($item) ? $item : null;   
                $auditor_exexperience->position         =    !empty($data['experience_position'][$key]) ? $data['experience_position'][$key] : null;   
                $auditor_exexperience->department       =    !empty($data['experience_department'][$key]) ? $data['experience_department'][$key] : null;   
                $auditor_exexperience->role             =    !empty($data['experience_character'][$key]) ? $data['experience_character'][$key] : null;   
                $auditor_exexperience->token            =    str_random(16); 
                $auditor_exexperience->save(); 
            }
        } catch (Exception $x) {
            throw $x;
        }   
    }

      // ประสบการณ์การตรวจประเมิน cb
      public function ItemsAuditorAssessmentExperienceCb($data, $auditor_information) {  
        try {
            $auditor_information->auditor_assessment_experience_cb()->delete();
            foreach ((array)$data['start_date'] as $key => $item){
                $experience_cb = new AuditorAssessmentExperience; 
                $experience_cb->auditor_id           =    $auditor_information->id;
                $experience_cb->start_date           =   HP::convertDate($item,true);      // start วันที่ตรวจประเมิน
                $experience_cb->end_date             =   !empty($data['end_date'][$key]) ? HP::convertDate($data['end_date'][$key],true) : null;     // end  วันที่ตรวจประเมิน
                $experience_cb->type_of_assessment   =    1;   // CB  
                $experience_cb->standard             =    !empty($data['standard'][$key]) ? $data['standard'][$key] : null;    // มาตรฐาน
                $experience_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $experience_cb->branch_path          =     CertificationBranch::class; 
                $experience_cb->scope_name           =    !empty($data['experience_check_scope'][$key]) ? $data['experience_check_scope'][$key] : null;   // ขอบข่าย
                $experience_cb->role                 =    !empty($data['experience_check_role'][$key]) ? $data['experience_check_role'][$key] : null;   // บทบาทหน้าที่
                $experience_cb->auditor_status       =    !empty($data['experience_check_status'][$key]) ? $data['experience_check_status'][$key] : null;   // สถานะผู้ประเมิน
                $experience_cb->token                =    str_random(16); 
                $experience_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }
     // ประสบการณ์การตรวจประเมิน ib
      public function ItemsAuditorAssessmentExperienceIB($data, $auditor_information) {  
        try {
            $auditor_information->auditor_assessment_experience_ib()->delete();
            foreach ((array)$data['start_date'] as $key => $item){
                $experience_cb = new AuditorAssessmentExperience; 
                $experience_cb->auditor_id           =    $auditor_information->id;
                $experience_cb->start_date           =   HP::convertDate($item,true);      // start วันที่ตรวจประเมิน
                $experience_cb->end_date             =   !empty($data['end_date'][$key]) ? HP::convertDate($data['end_date'][$key],true) : null;     // end  วันที่ตรวจประเมิน
                $experience_cb->type_of_assessment   =    2;   // IB
                $experience_cb->standard             =    !empty($data['standard'][$key]) ? $data['standard'][$key] : null;    // มาตรฐาน
                $experience_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $experience_cb->branch_path          =    InspectBranch::class; 
                $experience_cb->type_of_examination  =    !empty($data['type_of_examination'][$key]) ? $data['type_of_examination'][$key] : null;   // ประเภทหน่วยตรวจ
                $experience_cb->examination_category =    !empty($data['examination_category'][$key]) ? $data['examination_category'][$key] : null;   // หมวดหมู่การตรวจ
                $experience_cb->role                 =    !empty($data['experience_check_role'][$key]) ? $data['experience_check_role'][$key] : null;   // บทบาทหน้าที่
                $experience_cb->auditor_status       =    !empty($data['experience_check_status'][$key]) ? $data['experience_check_status'][$key] : null;   // สถานะผู้ประเมิน
                $experience_cb->token                =    str_random(16); 
                $experience_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }

     // ประสบการณ์การตรวจประเมิน LAB สอบเทียบ
      public function Items_Auditor_Assessment_Experience_LAB_Calibration($data, $auditor_information) {  
        try {
            $auditor_information->auditor_assessment_experience_lab_calibration()->delete();
            foreach ((array)$data['start_date'] as $key => $item){
                $experience_cb = new AuditorAssessmentExperience; 
                $experience_cb->auditor_id           =    $auditor_information->id;
                $experience_cb->start_date           =   HP::convertDate($item,true);      // start วันที่ตรวจประเมิน
                $experience_cb->end_date             =   !empty($data['end_date'][$key]) ? HP::convertDate($data['end_date'][$key],true) : null;     // end  วันที่ตรวจประเมิน
                $experience_cb->type_of_assessment   =    3;   // LAB สอบเทียบ
                $experience_cb->standard             =    !empty($data['standard'][$key]) ? $data['standard'][$key] : null;    // มาตรฐาน
                $experience_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $experience_cb->branch_path          =    CalibrationBranch::class; 
                $experience_cb->calibration_list     =    !empty($data['calibration_list'][$key]) ? $data['calibration_list'][$key] : null;   // รายการสอบเทียบ
                $experience_cb->role                 =    !empty($data['experience_check_role'][$key]) ? $data['experience_check_role'][$key] : null;   // บทบาทหน้าที่
                $experience_cb->auditor_status       =    !empty($data['experience_check_status'][$key]) ? $data['experience_check_status'][$key] : null;   // สถานะผู้ประเมิน
                $experience_cb->token                =    str_random(16); 
                $experience_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
      }

    // ประสบการณ์การตรวจประเมิน LAB ทดสอบ
      public function Items_Auditor_Assessment_Experience_LAB_Text($data, $auditor_information) {  
        try {
            $auditor_information->auditor_assessment_experience_lab_test()->delete();
            foreach ((array)$data['start_date'] as $key => $item){
                $experience_cb = new AuditorAssessmentExperience; 
                $experience_cb->auditor_id           =    $auditor_information->id;
                $experience_cb->start_date           =   HP::convertDate($item,true);      // start วันที่ตรวจประเมิน
                $experience_cb->end_date             =   !empty($data['end_date'][$key]) ? HP::convertDate($data['end_date'][$key],true) : null;     // end  วันที่ตรวจประเมิน
                $experience_cb->type_of_assessment   =    4;   // LAB ทดสอบ
                $experience_cb->standard             =    !empty($data['standard'][$key]) ? $data['standard'][$key] : null;    // มาตรฐาน
                $experience_cb->branch_id            =    !empty($data['find_status'][$key]) ? $data['find_status'][$key] : null;      // สาขา
                $experience_cb->branch_path          =    TestBranch::class; 
                $experience_cb->product              =    !empty($data['product'][$key]) ? $data['product'][$key] : null;   // ผลิตภัณฑ์
                $experience_cb->test_list            =    !empty($data['test_list'][$key]) ? $data['test_list'][$key] : null;   // รายการทดสอบ
                $experience_cb->role                 =    !empty($data['experience_check_role'][$key]) ? $data['experience_check_role'][$key] : null;   // บทบาทหน้าที่
                $experience_cb->auditor_status       =    !empty($data['experience_check_status'][$key]) ? $data['experience_check_status'][$key] : null;   // สถานะผู้ประเมิน
                $experience_cb->token                =    str_random(16); 
                $experience_cb->save(); 
            }
          } catch (Exception $x) {
             throw $x;
        }   
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
     // มาตรฐาน
    public function apiStandard(Request $request)
    {
        $branch =  [];
        if ($request->select == 1){
            $branch = CertificationBranch::select('title','id')->where('state',1)->get();
        }
        elseif ($request->select == 2){
            $branch = InspectBranch::select('title','id')->where('state',1)->get();
        }
        elseif ($request->select == 3 ){
            $branch = CalibrationBranch::select('title','id')->where('state',1)->get();
        }
        elseif ($request->select == 4 ){
            $branch = TestBranch::select('title','id')->where('state',1)->get();
        }

        // มาตรฐาน
        if ($request->select == 3 || $request->select == 4){
            $request->select = 3 ;
        }
        $formulas = Formula::select('title','id')->where('applicant_type',$request->select)->where('state',1)->get();
        return response()->json([
                                  'datas'=> $branch,
                                  'formulas' => $formulas
                               ]);
    }

    // สาขา -> ขอบข่าย
    public function apiScope(Request $request)
    {
        $scope = CertificationScope::where('certification_branch_id',$request->id)->where('state',1)->first();
        $information_scope = [] ;
        if(!is_null($scope)){
            if ($scope->scope_type == "ISIC"){
                $information_scope = IndustryType::where('state',1)->get();
            }
            elseif ($scope->scope_type == "IAF"){
                $information_scope = Iaf::where('state',1)->get();
            }
            elseif ($scope->scope_type == "Enms"){
                $information_scope = Enms::where('state',1)->get();
            }
            elseif ($scope->scope_type == "GHG"){
                $information_scope = Ghg::where('state',1)->get();
            }
        }
        return response()->json([
                                    'datas'=> $information_scope
                                  ]);
    }
    //  ประเภทการตรวจประเมิน ->  ประเภทหน่วยตรวจ และ หมวดหมู่การตรวจ
    public function apiInspection(Request $request)
    {
        $typeInspection = InspectType::where('state',1)->get();
        $categoriesInspection = InspectCategory::where('state',1)->get();
        return response()->json([
                                  'type_inspection'=> $typeInspection,
                                  'categories_inspection'=> $categoriesInspection,
                               ]);
    }
    // สาขา -> รายการสอบเทียบ
    public function apiCalibration(Request $request)
    {
        $calibration = CalibrationItem::where('state',1)->where('calibration_branch_id',$request->id)->get();
        return response()->json([
                                 'datas'=> $calibration
                               ]);
    }
    public function apiProduct(Request $request)
    {
   
        $products = ProductItem::where('test_branch_id',$request->id)->where('state',1)->get();
        $list = TestItem::where('test_branch_id',$request->id)->where('state',1)->get();
        return response()->json([
                                  'products'=> $products,
                                   'test'=> $list,
                                ]);


    }

    public function update_status($id)
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
            return redirect('bcertify/auditors')->with('success_message','เปลี่ยนสถานะเรียบร้อย');
        }
        abort(403);

    }
}
