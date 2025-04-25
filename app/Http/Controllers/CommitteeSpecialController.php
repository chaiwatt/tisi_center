<?php

namespace App\Http\Controllers;

use App\CommitteeInDepartment;
use App\CommitteeSpecial;
use App\CommitteeLists;
use App\Models\Basic\AppointDepartment; 
use App\Models\Certify\RegisterExpert;
use App\Models\Basic\BoardType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CommitteeSpecialController extends Controller
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
        $model = str_slug('committee','-');
        if(auth()->user()->can('view-'.$model)) {
            $filter = [];
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', '');

            $Query = new CommitteeSpecial;

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                    $query->where('committee_group','LIKE','%'.$filter['filter_search'].'%')
                        ->orWhere('appoint_number','LIKE','%'.$filter['filter_search'].'%')
                        ->orWhere('message','LIKE','%'.$filter['filter_search'].'%');
                });
            }

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date']);
                $Query = $Query->whereBetween('appoint_date', [$start->toDateString(),$end->toDateString()]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $Query = $Query->whereDate('appoint_date',$start->toDateString());
            }

            $committees = $Query->orderby('id','desc')->paginate($filter['perPage']);

            return view('certify/committee/index', compact('committees', 'filter'));
        }
        abort(403);
        //abort(403);
//        $committee_special = CommitteeSpecial::orderBy('created_at','desc')->get();
//        return view('certify/committee/index',['committees'=>$committee_special]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('add-'.$model)) {
            $departments = DB::table('basic_departments')->where('state',1)->get();
            return view('certify.committee.create',['departments'=>$departments]);        
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
        $model = str_slug('committee','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request,[
                'title' => 'required' ,
                'appoint_number' => 'required',
                'appoint_date'=> 'required',
            ]);

            $requestData = $request->all();


            $file_path_toDB = null;

            $committeeSpecial = new CommitteeSpecial([
                'committee_group'=>$request->title,
                'appoint_number'=>$request->appoint_number,
                'expert_group_id'=>$request->expert_group_id,
                'appoint_date'=> Carbon::createFromFormat('d/m/Y',$request->appoint_date),
                'message'=>$request->message ?? null,
                'user_id' => Auth::user()->runrecno,
                'faculty' =>  !empty($request->faculty) ? $request->faculty : null,
                'faculty_no' =>  !empty($request->faculty_no) ? $request->faculty_no : null,
                'product_group_id' =>  !empty($request->faculty) ? $request->product_group_id : null,
                'token'=>str_random(20)
            ]);

            if($request->hasFile('authorize_file')) {
                $files = $request->file('authorize_file');
                $destinationPath = storage_path('/files/authorize_files/');
                $fileOriginal = $files->getClientOriginalName();
                $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                $path = $filename.'-'.time() . '.' . $files->getClientOriginalExtension();
                $files->move($destinationPath, $path);
                $file_authorize_toDB = $path;
                $committeeSpecial->authorize_file = $file_authorize_toDB;
            }

            try{
                $committeeSpecial->save();
                $this->save_bcertify_committee_lists($requestData,$committeeSpecial);
            }catch (\Exception $x){
                echo "เกิดข้อผิดพลาด";
            }
            if($request->hasFile('attachs')) {
                $files = $request->file('attachs');
                $destinationPath = storage_path('/files/appointment_files/');
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
                    $file_path_toDB = $path;
                    try{
                        DB::table('appointment_files')->insert(
                            [
                                'committee_special_id' => $committeeSpecial->id,
                                'file_path' => $file_path_toDB,
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

            if ($request->departmentInputDetail){
                foreach (json_decode($request->departmentInputDetail) as $data){
                    $committee_type = null;
                    if ($data->committee_type == 'ผู้ทรงวุฒิ'){
                        $committee_type = 0;
                    }elseif ($data->committee_type == 'ผู้แทนหลัก'){
                        $committee_type = 1;
                    }elseif ($data->committee_type == 'ผู้แทนสำรอง'){
                        $committee_type = 2;
                    }elseif ($data->committee_type == 'ฝ่ายเลขานุการ'){
                        $committee_type = 3;
                    }
                    $committeeInDepartment = new CommitteeInDepartment([
                        'committee_special_id' => $committeeSpecial->id,
                        'department_id' => $data->department,
                        'name' => $data->name,
                        'committee_type' => $committee_type,
                        'level'=> $data->level,
                        'represent_group'=> $data->legate,
                        'position'=> $data->position,
                        'address' => $data->address,
                        'tel' => $data->telephone,
                        'fax' => $data->fax,
                        'email' => $data->email,
                        'token' => str_random(20)
                    ]);
                    try{
                        $committeeInDepartment->save();
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }

            return redirect()->route('committee.index')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommitteeSpecial  $committeeSpecial
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('view-'.$model)) {
            $committeeSpecial = CommitteeSpecial::whereToken($token)->first();
            if ($committeeSpecial){
                return view('certify.committee.show',['committeeSpecial'=>$committeeSpecial]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommitteeSpecial  $committeeSpecial
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('edit-'.$model)) {
            $committeeSpecial = CommitteeSpecial::whereToken($token)->first();
 
            if ($committeeSpecial){
                $bcertify_committee_lists  = CommitteeLists::where('committee_special_id',$committeeSpecial->id)->get();
                $departments = DB::table('basic_departments')->where('state',1)->get();
                return view('certify.committee.edit',['committeeSpecial'=>$committeeSpecial,
                                                      'departments'=>$departments,
                                                      'bcertify_committee_lists'=>$bcertify_committee_lists
                                                    ]);
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommitteeSpecial  $committeeSpecial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $token)
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request,[
                'title' => 'required' ,
                'appoint_number' => 'required',
                'appoint_date'=> 'required',
            ]);
            $requestData = $request->all();
            
            $committeeSpecial = CommitteeSpecial::whereToken($token)->first();
            if ($committeeSpecial){
                $file_path_toDB = null;

                try{
                    $appoint_date = Carbon::createFromFormat('d/m/Y',$request->appoint_date);
                }catch (\Exception $x){
                    $appoint_date = Carbon::createFromFormat('Y-m-d',$request->appoint_date);
                }

                $committeeSpecial->committee_group = $request->title;
                $committeeSpecial->appoint_number = $request->appoint_number;
                $committeeSpecial->expert_group_id = $request->expert_group_id;
                $committeeSpecial->appoint_date = $appoint_date;
                $committeeSpecial->message = $request->message ?? null;
                $committeeSpecial->faculty = !empty($request->faculty) ? $request->faculty : null;
                $committeeSpecial->faculty_no = !empty($request->faculty_no) ? $request->faculty_no : null;
                $committeeSpecial->product_group_id =   !empty($request->faculty) ? $request->product_group_id : null;
                $committeeSpecial->user_id = Auth::user()->runrecno;

                if($request->hasFile('authorize_file')) {
                    $files = $request->file('authorize_file');
                    $destinationPath = storage_path('/files/authorize_files/');
                    $fileOriginal = $files->getClientOriginalName();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $path = $filename.'-'.time() . '.' . $files->getClientOriginalExtension();
                    $files->move($destinationPath, $path);
                    $file_authorize_toDB = $path;
                    $committeeSpecial->authorize_file = $file_authorize_toDB;
                }

                try{
                    $committeeSpecial->save();
                    $this->save_bcertify_committee_lists($requestData,$committeeSpecial);
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
                if($request->hasFile('attachs')) {
                    $files = $request->file('attachs');
                    $destinationPath = storage_path('/files/appointment_files/');
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
                        $file_path_toDB = $path;
                        try{
                            DB::table('appointment_files')->insert(
                                [
                                    'committee_special_id' => $committeeSpecial->id,
                                    'file_path' => $file_path_toDB,
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

                if ($request->departmentInputDetail){
                    foreach (json_decode($request->departmentInputDetail) as $data){
                        $committee_type = null;
                        if ($data->committee_type == 'ผู้ทรงวุฒิ'){
                            $committee_type = 0;
                        }elseif ($data->committee_type == 'ผู้แทนหลัก'){
                            $committee_type = 1;
                        }elseif ($data->committee_type == 'ผู้แทนสำรอง'){
                            $committee_type = 2;
                        }elseif ($data->committee_type == 'ฝ่ายเลขานุการ'){
                            $committee_type = 3;
                        }
                        $committeeInDepartment = new CommitteeInDepartment([
                            'committee_special_id' => $committeeSpecial->id,
                            'department_id' => $data->department,
                            'name' => $data->name,
                            'committee_type' => $committee_type,
                            'level'=> $data->level,
                            'represent_group'=> $data->legate,
                            'position'=> $data->position,
                            'address' => $data->address,
                            'tel' => $data->telephone,
                            'fax' => $data->fax,
                            'email' => $data->email,
                            'token' => str_random(20)
                        ]);
                        try{
                            $committeeInDepartment->save();
                        }catch (\Exception $x){
                            echo "เกิดข้อผิดพลาด";
                        }
                    }
                }
                return redirect()->route('committee.index')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommitteeSpecial  $committeeSpecial
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('delete-'.$model)) {
            $requestData = $request->all();
            if ($id == 'all'){
                if(array_key_exists('cb', $requestData)){
                    $ids = $requestData['cb'];
                    try{
                        CommitteeSpecial::whereIn('token', $ids)->each(function ($item) {
                            $item->delete();
                        });
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }else{
                CommitteeSpecial::destroy($id);
            }
            return redirect()->route('committee.index')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function removeCommitteeInDepartment($token){
        $model = str_slug('committee','-');
        if(auth()->user()->can('edit-'.$model)) {
            $committeeIndepartment = CommitteeInDepartment::whereToken($token)->first();
            if ($committeeIndepartment){
                try{
                    $committeeIndepartment->delete();
                    return redirect()->back()->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
            }
            abort(404);
        }
        abort(403);
    }

    public function removeFiles($type,$token,$path){
        $model = str_slug('committee','-');
        if(auth()->user()->can('edit-'.$model)) {
            try{
                if ($type == 'authorize'){
                    $file = storage_path().'/files/authorize_files/'.$path;
                    CommitteeSpecial::whereToken($token)->update(['authorize_file'=>null]);
                }
                if ($type == 'other'){
                    $file = storage_path().'/files/appointment_files/'.$path;
                    DB::table('appointment_files')->where('token', $token)->delete();
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

    public function updateCommitteeInDepartment(Request $request,$token)
    {
        $model = str_slug('committee','-');
        if(auth()->user()->can('edit-'.$model)) {
            $committeeIndepartment = CommitteeInDepartment::whereToken($token)->first();
            if ($committeeIndepartment){
                $committee_type = null;
                if ($request->committee_type == 'ผู้ทรงวุฒิ'){
                    $committee_type = 0;
                }elseif ($request->committee_type == 'ผู้แทนหลัก'){
                    $committee_type = 1;
                }elseif ($request->committee_type == 'ผู้แทนสำรอง'){
                    $committee_type = 2;
                }elseif ($request->committee_type == 'ฝ่ายเลขานุการ'){
                    $committee_type = 3;
                }
                $committeeIndepartment->department_id = $request->department;
                $committeeIndepartment->name = $request->peopleName_departure;
                $committeeIndepartment->committee_type = $committee_type;
                $committeeIndepartment->level = $request->level;
                $committeeIndepartment->represent_group = $request->legate;
                $committeeIndepartment->position = $request->position;
                $committeeIndepartment->address = $request->address;
                $committeeIndepartment->tel = $request->telephone;
                $committeeIndepartment->fax = $request->fax;
                $committeeIndepartment->email = $request->email;
                try{
                    $committeeIndepartment->save();
                    return redirect()->back()->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
            }
            abort(404);
        }
        abort(403);
    }

    public function get_position_name($register_experts_id,$group_id = '') {
        $register_experts = RegisterExpert::where('id',$register_experts_id)->first();
        $appoint_departments = AppointDepartment::where('id',$register_experts->department_id)->first();
        $board_types = BoardType::select('title','id')->where('expert_group_id',$group_id)->get();  

        return response()->json([
                                  'title' =>   !empty($appoint_departments->title) ? $appoint_departments->title : '',
                                  'board_types' =>   count($board_types) > 0 ? $board_types : []
                               ]);

    }

    
    public function get_expert_groups($group_id) {
        $board_types = BoardType::select('title','id')->where('expert_group_id',$group_id)->get();  
        return response()->json([
                                  'board_types' =>   count($board_types) > 0 ? $board_types : []
                               ]);

    }

    private function save_bcertify_committee_lists($requestData, $committeeSpecial){
        if(!empty($requestData['expert_id'] ) && count($requestData['expert_id'] ) > 0){

            CommitteeLists::where('committee_special_id', $committeeSpecial->id)->delete();
            foreach($requestData['expert_id'] as $key => $item) {
                $input = [];
                $input['committee_special_id']   = $committeeSpecial->id;
                $input['expert_id']              = $item;
                $input['expert_name']            = !empty( $requestData['expert_name'][$key]) ?  $requestData['expert_name'][$key] : null;
                $input['department_name']        = !empty( $requestData['department_name'][$key]) ?  $requestData['department_name'][$key] : null;
                $input['committee_qualified']    = !empty( $requestData['committee_qualified'][$key]) ?  $requestData['committee_qualified'][$key] : null;
                $input['committee_position']     = !empty( $requestData['committee_position'][$key]) ?  $requestData['committee_position'][$key] : null;
                $input['created_by']             = auth()->user()->getKey();
                CommitteeLists::create($input);
            }
        }
    }

}
