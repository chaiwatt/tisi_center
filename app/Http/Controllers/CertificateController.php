<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\CommitteeInDepartment;
use App\CommitteeSpecial;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Bcertify\Formula;
use App\Models\Bcertify\InspectBranch;
use App\Models\Bcertify\TestBranch;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CertificateController extends Controller
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
        $model = str_slug('certificate','-');
        if(auth()->user()->can('view-'.$model)) {
            $filter = [];
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['filter_start_date_exp'] = $request->get('filter_start_date_exp', '');
            $filter['filter_end_date_exp'] = $request->get('filter_end_date_exp', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', '');
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_assessment'] = $request->get('filter_assessment', '');
            $filter['filter_standard'] = $request->get('filter_standard', '');
            $filter['filter_cerType'] = $request->get('filter_cerType', '');

            $Query = new Certificate;

            if ($filter['filter_search']!='') {
                $Query = $Query->where('request_number','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('certificate_file_number','LIKE','%'.$filter['filter_search'].'%');
            }

            if ($filter['filter_cerType']!='') {
                $Query = $Query->where('certificate_option',$filter['filter_cerType']);
            }

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date']);
                $Query = $Query->whereBetween('certified_date', [$start->toDateString(),$end->toDateString()]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $Query = $Query->whereDate('certified_date',$start->toDateString());
            }

            /////////////////////////////////

            if ($filter['filter_start_date_exp'] != null && $filter['filter_end_date_exp'] != null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date_exp']);
                $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date_exp']);
                $Query = $Query->whereBetween('certified_exp', [$start->toDateString(),$end->toDateString()]);

            }elseif ($filter['filter_start_date_exp'] != null && $filter['filter_end_date_exp'] == null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date_exp']);
                $Query = $Query->whereDate('certified_exp',$start->toDateString());
            }

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state',$filter['filter_state']);
            }

            if ($filter['filter_assessment']!='') {
                $filter_assessment = $filter['filter_assessment'];
                if ($filter_assessment == 3 || $filter_assessment == 4){
                    $filter_assessment = [3,4];
                    $Query = $Query->whereIn('assessment_type',$filter_assessment);
                }else{
                    $Query = $Query->where('assessment_type',$filter_assessment);
                }
            }

            if ($filter['filter_standard']!='') {
                $Query = $Query->where('formula_id',$filter['filter_standard']);
            }

            $certificates = $Query->paginate($filter['perPage']);

            return view('certify/certificate/index', compact('certificates', 'filter'));
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
        $model = str_slug('certificate','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify/certificate/create');
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
        $model = str_slug('certificate','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request,[
                'radio' => 'required' ,
                'assessment_type' => 'required',
                'unit_name' => 'required',
                'cerFileNumber'=> 'required',
                'standardNumber'=> 'required',
                'branch'=> 'required',
                'certified_date'=> 'required',
                'certified_exp'=> 'required',
                'CertificateAttach'=>'required|file',
                'state'=> 'required'
            ]);

            if ($request->lab_status){
                $lab_status = serialize($request->lab_status);
            }else{
                $lab_status = null;
            }

            $file_certificate_toDB = null;
            $other_file_toDB = null;

            $certificate = new Certificate([
                'certificate_option'=>$request->radio,
                'request_number'=>$request->requestNumber,
                'unit_name' => $request->unit_name,
                'lab_status' => $lab_status,
                'assessment_type'=>$request->assessment_type,
                'certificate_file_number'=>$request->cerFileNumber,
                'certificate_number'=>$request->cerNumber,
                'formula_id'=>$request->standardNumber,
                'certified_date'=>Carbon::createFromFormat('d/m/Y',$request->certified_date),
                'certified_exp'=>Carbon::createFromFormat('d/m/Y',$request->certified_exp),
                'user_id'=>Auth::user()->runrecno,
                'state'=>$request->state,
                'token'=>str_random(20)
            ]);

            if($request->hasFile('CertificateAttach')) {
                $files = $request->file('CertificateAttach');
                $destinationPath = storage_path('/files/certificate_files/');
                $fileOriginal = $files->getClientOriginalName();
                $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                $path = $filename.'-'.time() . '.' . $files->getClientOriginalExtension();
                $files->move($destinationPath, $path);
                $file_certificate_toDB = $path;
                $certificate->certificate_file = $file_certificate_toDB;
            }

            try{
                $certificate->save();
                foreach ($request->branch as $branch){
                    DB::table('certificate_branches')->insert([
                        'certificate_id'=>$certificate->id,
                        'branch_id'=>$branch,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }catch (\Exception $x){
                echo "เกิดข้อผิดพลาด";
            }

            if($request->hasFile('attachs')) {
                $files = $request->file('attachs');
                $destinationPath = storage_path('/files/otherCertificate_files/');
                $lap = 0;
                foreach ($files as $inFile){
                    $fileOriginal = $inFile->getClientOriginalName();
                    if ($request->attach_filenames[$lap] != null){
                        $filename = $request->attach_filenames[$lap];
                        $path = $filename.'.' . $inFile->getClientOriginalExtension();
                    }else{
                        $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                        $path = $filename.'-'.time() . '.' . $inFile->getClientOriginalExtension();
                    }
                    $inFile->move($destinationPath, $path);
                    $other_file_toDB = $path;
                    try{
                        DB::table('other_certificate_files')->insert(
                            [
                                'certificate_id' => $certificate->id,
                                'file_path' => $other_file_toDB,
                                'token' => str_random(20),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]
                        );
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                    $lap ++;
                }
            }
            return redirect()->route('certificate.index')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $model = str_slug('certificate','-');
        if(auth()->user()->can('view-'.$model)) {
            $certificate = Certificate::whereToken($token)->first();
            if ($certificate){
                $files = DB::table('other_certificate_files')->select('file_path','token','created_at')->where('certificate_id',$certificate->id)->get();
                return view('certify.certificate.show',['certificate'=>$certificate,'other_files'=>$files]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $model = str_slug('certificate','-');
        if(auth()->user()->can('edit-'.$model)) {
            $certificate = Certificate::whereToken($token)->first();
            if ($certificate){
                $standardNumber = $certificate->assessment_type;
                if ($standardNumber == 3 || $standardNumber == 4){
                    $formula_search = 3;
                }else{
                    $formula_search = $standardNumber;
                }
                $branches = null;
                $formulas = Formula::where('applicant_type',$formula_search)->whereState(1)->get();
                $files = DB::table('other_certificate_files')->select('file_path','token','created_at')->where('certificate_id',$certificate->id)->get();

                if ($standardNumber == '2'){
                    $branches = InspectBranch::whereState(1)->get();
                }elseif ($standardNumber == '1'){
                    $branches = CertificationBranch::whereState(1)->get();
                }elseif ($standardNumber == '3'){
                    $branches = TestBranch::whereState(1)->get();
                }elseif ($standardNumber == '4'){
                    $branches = CalibrationBranch::whereState(1)->get();
                }
                return view('certify.certificate.edit',['certificate'=>$certificate,'formulas'=>$formulas,'branches'=>$branches,'other_files'=>$files]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $token)
    {
        $model = str_slug('certificate','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request,[
                'radio' => 'required' ,
                'assessment_type' => 'required',
                'unit_name' => 'required',
                'cerFileNumber'=> 'required',
                'standardNumber'=> 'required',
                'branch'=> 'required',
                'certified_date'=> 'required',
                'certified_exp'=> 'required',
                'state'=> 'required'
            ]);

            if ($request->lab_status){
                $lab_status = serialize($request->lab_status);
            }else{
                $lab_status = null;
            }

            $certificate = Certificate::whereToken($token)->first();
            if ($certificate){

                try{
                    $certified_date = Carbon::createFromFormat('d/m/Y',$request->certified_date);
                    $certified_exp = Carbon::createFromFormat('d/m/Y',$request->certified_exp);
                }catch (\Exception $x){
                    $certified_date = Carbon::createFromFormat('Y-m-d',$request->certified_date);
                    $certified_exp = Carbon::createFromFormat('Y-m-d',$request->certified_exp);
                }
                $certificate->certificate_option = $request->radio;
                $certificate->request_number = $request->requestNumber;
                $certificate->assessment_type = $request->assessment_type;
                $certificate->unit_name = $request->unit_name;
                $certificate->lab_status = $lab_status;
                $certificate->certificate_file_number = $request->cerFileNumber;
                $certificate->certificate_number = $request->cerNumber;
                $certificate->formula_id = $request->standardNumber;
                $certificate->certified_date = $certified_date;
                $certificate->certified_exp = $certified_exp;
                $certificate->user_id = Auth::user()->runrecno;
                $certificate->state = $request->state;

                if($request->hasFile('CertificateAttach')) {
                    $files = $request->file('CertificateAttach');
                    $destinationPath = storage_path('/files/certificate_files/');
                    $fileOriginal = $files->getClientOriginalName();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $path = $filename.'-'.time() . '.' . $files->getClientOriginalExtension();
                    $files->move($destinationPath, $path);
                    $file_certificate_toDB = $path;
                    $certificate->certificate_file = $file_certificate_toDB;
                }

                try{
                    $certificate->save();
                    DB::table('certificate_branches')->where('certificate_id',$certificate->id)->delete(); // ลบของเก่า
                    foreach ($request->branch as $branch){
                        DB::table('certificate_branches')->insert([
                            'certificate_id'=>$certificate->id,
                            'branch_id'=>$branch,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }

                if($request->hasFile('attachs')) {
                    $files = $request->file('attachs');
                    $destinationPath = storage_path('/files/otherCertificate_files/');
                    $lap = 0;
                    foreach ($files as $inFile){
                        $fileOriginal = $inFile->getClientOriginalName();
                        if ($request->attach_filenames[$lap] != null){
                            $filename = $request->attach_filenames[$lap];
                            $path = $filename.'.' . $inFile->getClientOriginalExtension();
                        }else{
                            $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                            $path = $filename.'-'.time() . '.' . $inFile->getClientOriginalExtension();
                        }
                        $inFile->move($destinationPath, $path);
                        $other_file_toDB = $path;
                        try{
                            DB::table('other_certificate_files')->insert(
                                [
                                    'certificate_id' => $certificate->id,
                                    'file_path' => $other_file_toDB,
                                    'token' => str_random(20),
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now()
                                ]
                            );
                        }catch (\Exception $x){
                            echo "เกิดข้อผิดพลาด";
                        }
                        $lap ++;
                    }
                }
                return redirect()->route('certificate.index')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว!');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $model = str_slug('certificate','-');
        if(auth()->user()->can('delete-'.$model)) {
            $requestData = $request->all();
            if ($id == 'all'){
                if(array_key_exists('cb', $requestData)){
                    $ids = $requestData['cb'];
                    try{
                        Certificate::whereIn('token', $ids)->each(function ($item){
                            $item->delete();
                        });
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }else{
                Certificate::destroy($id);
            }
            return redirect()->route('certificate.index')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function removeFiles($type,$token,$path){
        $model = str_slug('certificate','-');
        if(auth()->user()->can('edit-'.$model)) {
            try{

                if ($type == 'certificate'){
                    $file = storage_path().'/files/certificate_files/'.$path;
                    Certificate::whereToken($token)->update(['certificate_file'=>null]);
                }
                if ($type == 'other'){
                    $file = storage_path().'/files/otherCertificate_files/'.$path;
                    DB::table('other_certificate_files')->where('token', $token)->delete();
                }
                if (!File::exists($file)) {
                    return Response::make("File does not exist.", 404);
                }
                if(is_file($file)){
                    File::delete($file);
                }else {
                    echo "File does not exist";
                }
                return redirect()->back()->with('flash_message', 'ลบไฟล์แล้ว!');
            }catch (\Exception $x){
                echo "เกิดข้อผิดพลาด";
            }
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){
        $model = str_slug('certificate','-');
        if(auth()->user()->can('edit-'.$model)) {
            $requestData = $request->all();

            if(array_key_exists('cb', $requestData)){
                $ids = $requestData['cb'];
                try{
                    Certificate::whereIn('token', $ids)->update(['state' => $requestData['state']]);
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
            }
            return redirect('certificate')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }


    public function getApplicantType()
    {
        $form_data = array();
        $standardNumber = $_POST['assessment_type'];

        $formula = Formula::where('applicant_type',$standardNumber)->whereState(1)->get();

        if ($formula->count() > 0){
            $form_data['formula'] = $formula;
            $form_data['status'] = true;
        }else{
            $form_data['status'] = false;
        }

        return json_encode($form_data, JSON_UNESCAPED_UNICODE);

    }

    public function getBranch()
    {
        $form_data = array();
        $standardNumber = $_POST['assessment_type'];
        $branch = null;
        if ($standardNumber == '2'){
            $branch = InspectBranch::whereState(1)->get();
        }elseif ($standardNumber == '1'){
            $branch = CertificationBranch::whereState(1)->get();
        }elseif ($standardNumber == '3'){
            $branch = TestBranch::whereState(1)->get();
        }elseif ($standardNumber == '4'){
            $branch = CalibrationBranch::whereState(1)->get();
        }

        if ($branch->count() > 0){
            $form_data['branch'] = $branch;
            $form_data['status'] = true;
        }else{
            $form_data['status'] = false;
        }

        return json_encode($form_data, JSON_UNESCAPED_UNICODE);

    }

    public function getCheckExistLevel()
    {
        $form_data = array();
        $idSpecial = $_POST['idSpecial'];
        $department = $_POST['department'];
        $level = $_POST['level'];

        $check = CommitteeInDepartment::where('committee_special_id',$idSpecial)
                                        ->where('committee_type',2)
                                        ->where('department_id',$department)
                                        ->where('level',$level)->get();
        if ($check->count() > 0){
            $form_data['isHave'] = true;
        }else{
            $form_data['isHave'] = false;
        }

        return json_encode($form_data, JSON_UNESCAPED_UNICODE);

    }

    public function alertSettingPage()
    {
        $red = DB::table('certificate_alert')->select('*')->where('color','red')->first();
        $yellow = DB::table('certificate_alert')->select('*')->where('color','yellow')->first();
        $green = DB::table('certificate_alert')->select('*')->where('color','green')->first();
        return view('certify.alert_setting.setting',['red_color'=>$red ?? null,'yellow_color'=>$yellow ?? null,'green_color'=>$green ?? null]);
    }

    public function alertSetting(Request $request)
    {
//        dd($request);
        if ($request->red_status){
            if ($request->condition_red == 'between'){
                if ($request->red_day_start && $request->red_day_end){
                    DB::table('certificate_alert')->where('color','red')->update(['status'=>'on','condition'=>$request->condition_red,'date_start'=>$request->red_day_start,'date_end'=>$request->red_day_end]);
                }
            }else{
                if ($request->red_day_start){
                    $red = DB::table('certificate_alert')->where('color','red')->update(['status'=>'on','condition'=>$request->condition_red,'date_start'=>$request->red_day_start,'date_end'=>null]);
                }
            }

        }else{
            DB::table('certificate_alert')->where('color','red')->update(['status'=>'off','condition'=>null]);
        }

        if ($request->yellow_status){
            if ($request->condition_yellow == 'between'){
                if ($request->yellow_day_start && $request->yellow_day_end){
                    DB::table('certificate_alert')->where('color','yellow')->update(['status'=>'on','condition'=>$request->condition_yellow,'date_start'=>$request->yellow_day_start,'date_end'=>$request->yellow_day_end]);
                }
            }else{
                if ($request->yellow_day_start){
                    DB::table('certificate_alert')->where('color','yellow')->update(['status'=>'on','condition'=>$request->condition_yellow,'date_start'=>$request->yellow_day_start,'date_end'=>null]);
                }
            }
        }else{
            DB::table('certificate_alert')->where('color','yellow')->update(['status'=>'off','condition'=>null]);
        }

        if ($request->green_status){
            if ($request->condition_green == 'between'){
                if ($request->green_day_start && $request->green_day_end){
                    DB::table('certificate_alert')->where('color','green')->update(['status'=>'on','condition'=>$request->condition_green,'date_start'=>$request->green_day_start,'date_end'=>$request->green_day_end]);
                }
            }else{
                if ($request->yellow_day_start){
                    DB::table('certificate_alert')->where('color','green')->update(['status'=>'on','condition'=>$request->condition_green,'date_start'=>$request->green_day_start,'date_end'=>null]);
                }
            }
        }else{
            DB::table('certificate_alert')->where('color','green')->update(['status'=>'off','condition'=>null]);
        }
        return redirect()->back()->with('flash_message', 'ตั้งค่าการแจ้งเตือนเรียบร้อยแล้ว!');
    }

    public function checkEXP(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', '');
        $filter['alert_level'] = $request->get('alert_level', '');
        $filter['filter_type'] = $request->get('filter_type', '');
        $filter['cerNumber_search'] = $request->get('cerNumber_search', '');

        $Query = new Certificate;

        if ($filter['alert_level']!='') {
            if ($filter['alert_level'] == 'red'){
                $red = DB::table('certificate_alert')->select('*')->where('color','red')->first();
                $far = $red->date_start;
                $Query = $Query->whereRaw('certified_exp <= DATE_ADD(NOW(), INTERVAL '.$far.' DAY)');
            }elseif ($filter['alert_level'] == 'yellow'){
                $yellow = DB::table('certificate_alert')->select('*')->where('color','yellow')->first();
                $start = $yellow->date_start;
                $end = $yellow->date_end;
                $Query = $Query->whereRaw('certified_exp >= DATE_ADD(NOW(), INTERVAL '.$start.' DAY)')->whereRaw('certified_exp <= DATE_ADD(NOW(), INTERVAL '.$end.' DAY)');
            }elseif ($filter['alert_level'] == 'green'){
                $green = DB::table('certificate_alert')->select('*')->where('color','green')->first();
                $far = $green->date_start;
                $Query = $Query->whereRaw('certified_exp >= DATE_ADD(NOW(), INTERVAL '.$far.' DAY)');
            }
        }

        if ($filter['filter_type']!='') {
            $filter_assessment = $filter['filter_type'];
            if ($filter_assessment == 3 || $filter_assessment == 4){
                $filter_assessment = [3,4];
                $Query = $Query->whereIn('assessment_type',$filter_assessment);
            }else{
                $Query = $Query->where('assessment_type',$filter_assessment);
            }
        }

        if ($filter['cerNumber_search']!='') {
            $Query = $Query->where('certificate_file_number','LIKE','%'.$filter['cerNumber_search'].'%');
        }

        $prepareNumber = [];
        $prepareQuery = $Query->whereState(1)->get()
            ->reject(function($element) {
                $arr = explode('$',$element->checkExpire());
                $color = $arr[1];
                $date = $arr[0];
                if ($date == 0){
                    $color = 'bg-danger';
                }elseif ($date < 0){
                    $color = 'bg-danger';
                }
                if ($color == ''){
                    return $element;
                }
                return null;
            });
        foreach ($prepareQuery as $cer){
            array_push($prepareNumber,$cer->id);
        }
        $certificates = $Query->whereIn('id',$prepareNumber)->orderBy(DB::raw('DATEDIFF(certificates.certified_exp, NOW())'))->paginate($filter['perPage']);// certificates เป็นชื่อ table ใส่ก็ได้ ไม่ใส่ก้ได้
        return view('certify.alert_setting.index', compact('certificates', 'filter'));
    }
}
