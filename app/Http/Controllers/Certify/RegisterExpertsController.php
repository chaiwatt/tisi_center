<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Certify\RegisterExpert;
use App\Models\Certify\RegisterExpertBoard;
use App\Models\Certify\RegisterExpertMatch;
use App\Models\Certify\RegisterExpertAssign;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\Expert\AssignAlertMail;
use App\Mail\Expert\ExpertAlertMail;

use HP;
use Storage;

use Illuminate\Support\Facades\DB;
use PDF;
use File;

use App\User as user_general;

use App\Models\Sso\User;

class RegisterExpertsController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/expert/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    { 
        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('view-'.$model)) {
            $filter = [];
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['perPage'] = $request->get('perPage', 10);

            // $reg_fname_id  = user_general::where('reg_fname','LIKE','%'.$filter['filter_search'].'%')->get()->pluck('runrecno');

            // dd($reg_fname_id);

            $Query = new RegisterExpert;
            if ($filter['filter_search']!='') {
                $Query = $Query->where('taxid','LIKE', '%'.$filter['filter_search'].'%')
                ->orWhere('head_name','LIKE','%'.$filter['filter_search'].'%')
                ->orWhere('ref_no','LIKE','%'.$filter['filter_search'].'%')
                ->orWhereHas('expert_assigns', function ($query) use ($filter) {
                    $filter_search = $filter['filter_search'];
                    $reg_fname_id  = user_general::where('reg_fname','LIKE','%'.$filter_search.'%')->get()->pluck('runrecno');
                    $query->whereIn('user_id', $reg_fname_id);
                });
            }
            if ($filter['filter_status']!='') {
                $Query = $Query->where('status',$filter['filter_status']);
            }

            $registerexperts = $Query ->orderby('id','desc')->sortable()->paginate($filter['perPage']);

            $select_users  = user_general::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
            ->whereIn('reg_subdepart',[1802])
            ->orderbyRaw('CONVERT(title USING tis620)')
            ->pluck('title','runrecno');

            $status  = ['1'=>'ยื่นคำขอ','2'=>'อยู่ระหว่างการตรวจสอบคำขอ','3'=>'ตีกลับคำขอ','4'=>'ตรวจสอบคำขอแก้ไข','5'=>'เอกสารผ่านการตรวจสอบ','6'=>'อนุมัติการขึ้นทะเบียน','7'=>'ยกเลิกคำขอ','8'=>'ยกเลิกผู้เชี่ยวชาญ'];

            return view('certify.register-experts.index', compact('registerexperts', 'filter','select_users','status'));
        }
        abort(403);
    }


   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify.register-experts.create');
        }
        abort(403);

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
        $model = str_slug('registerexperts', '-');
        if (auth()->user()->can('add-' . $model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $SetStandardUser = RegisterExpert::create($requestData);
            $this->save_detail($SetStandardUser, $request);
            return redirect('certify/register-experts')->with('flash_message', 'เพิ่ม รายละเอียดผู้เชี่ยวชาญ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('view-'.$model)) {
            $registerexperts = RegisterExpert::findOrFail($id);
            $user =  User::where('id',$registerexperts->created_by)->first();
            $status  = ['1'=>'ยื่นคำขอ','2'=>'อยู่ระหว่างการตรวจสอบคำขอ','3'=>'ตีกลับคำขอ','4'=>'ตรวจสอบคำขอแก้ไข','5'=>'เอกสารผ่านการตรวจสอบ','6'=>'อนุมัติการขึ้นทะเบียน','7'=>'ยกเลิกคำขอ','8'=>'ยกเลิกผู้เชี่ยวชาญ'];
            $expert_type = RegisterExpertMatch::where('expert_id', $id)->pluck('expert_type');
            $board_type = RegisterExpertBoard::where('expert_id', $id)->pluck('board_type');
            return view('certify.register-experts.show', compact('registerexperts','user','status','expert_type','board_type'));
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
        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('edit-'.$model)) {
            $registerexperts = RegisterExpert::findOrFail($id);
            $user =  User::where('id',$registerexperts->created_by)->first();

            $status  = ['1'=>'ยื่นคำขอ','2'=>'อยู่ระหว่างการตรวจสอบคำขอ','3'=>'ตีกลับคำขอ','4'=>'ตรวจสอบคำขอแก้ไข','5'=>'เอกสารผ่านการตรวจสอบ','6'=>'อนุมัติการขึ้นทะเบียน','7'=>'ยกเลิกคำขอ','8'=>'ยกเลิกผู้เชี่ยวชาญ'];
            $expert_type = RegisterExpertMatch::where('expert_id', $id)->pluck('expert_type');
            $board_type = RegisterExpertBoard::where('expert_id', $id)->pluck('board_type');

            // dd(self::genExpertNo());

            // dd($expert_type);
            // dd($user);
            // return $setstandard;
            return view('certify.register-experts.edit', compact('registerexperts','user','status','expert_type','board_type'));
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
        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update

            $expert_type = RegisterExpertMatch::where('expert_id', $id)->delete();

            foreach($request->expert_type as $item){
                $expert_type = new RegisterExpertMatch;
                $expert_type->expert_id = $id;
                $expert_type->expert_type = $item;
                $expert_type->save();
            }

            // $board_type = RegisterExpertBoard::where('expert_id', $id)->delete();

            // foreach($request->board_type as $item){
            //     $board_type = new RegisterExpertBoard();
            //     $board_type->expert_id = $id;
            //     $board_type->board_type = $item;
            //     $board_type->save();
            // }

            $requestData = $request->all();
            $registerexperts = RegisterExpert::findOrFail($id);

            if($registerexperts->expert_no==""){
                $registerexperts->expert_no =  self::genExpertNo();
            }
            $registerexperts->update($requestData);
            
            // $expert_type_ids = RegisterExpertAssign::where('register_expert_id', $id)->get();

            // foreach ($expert_type_ids as $expert_type_data) {

                // dd($expert_type_data->assign_name->reg_email);
                // dd($expert_type_data->assign_name->reg_fname);
      

            // $mail = new AssignAlertMail([
            //     'apps' => $app ?? null,
            //     'email' => auth()->user()->reg_email ?? 'admin@admin.com',
            //     'reg_fname' => ($expert_type_data->assign_name->reg_fname) ? $expert_type_data->assign_name->reg_fname : null
            // ]);

        // Mail::to($reg_email)->send($mail);

            // }


            // $expert_type_ids = RegisterExpertAssign::where('register_expert_id', $id)->get()->pluck('user_id');

            // foreach ($expert_type_ids as $expert_type_id) {

            //     // ชื่อเจ้าหน้าที่รับผิดชอบตรวจสอบ
            //     $reg_fname = user_general::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))
            //         ->where('runrecno', $expert_type_id)
            //         ->whereNotNull('reg_fname')
            //         ->first()
            //         ->pluck('title');

            //     // ส่ง E-mail
            //     $reg_email = user_general::select('reg_email')
            //         ->where('runrecno', $expert_type_id)
            //         ->whereNotNull('reg_email')
            //         ->first()
            //         ->pluck('reg_email');



            //     $mail = new AssignAlertMail([
            //             'apps' => $app ?? null,
            //             'email' => auth()->user()->reg_email ?? 'admin@admin.com',
            //             'reg_fname' => (count($reg_fname) > 0) ? implode(", ", $reg_fname) : null
            //         ]);

            //     Mail::to($reg_email)->send($mail);
            // }
          
      


         
            // $this->save_detail($setstandard,$request);
            return redirect('certify/register-experts')->with('flash_message', 'เพิ่ม รายละเอียดผู้เชี่ยวชาญ เรียบร้อยแล้ว');
        }
        abort(403);

    }


    public function storeFile($files,$tax_number='0000000000000',$prefix_name=null)
    {
        if ($files) {
            $attach_path  =  $this->attach_path;
            $fullFileName = $prefix_name.str_random(12).'_datetime'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
            $storagePath = Storage::putFileAs($attach_path.'/'.$tax_number.'/', $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $attach_path.''.$storageName;
        }else{
            return null;
        }
    }

    public function remove_file($type)
    {
        $user_login = auth()->user()->getKey();
        $expert  =  RegisterExpert::where('created_by',$user_login)->first();
        if(!is_null($expert)){
            $expert->historycv_file = null;
            $expert->bank_file = null;
            $expert->save();
             return   'true';
        }else{
            return   'false';
        }
    }

    public function genExpertNo(){

        $year = date('Y')+543; // ปีปัจจุบัน
        $max_no = RegisterExpert::orderBy('id', 'desc')->value('expert_no');

		if (!is_null($max_no) && $max_no != '') {
            $old_runno = substr($max_no,-4);
            $max_new =  $old_runno+1;
		} else {
			$max_new = 1;
		}

		$running_no = str_pad($max_new, 4, '0', STR_PAD_LEFT);

        return "EP".$year.$running_no;
	}

    public function assign(Request $request)
    {
        $checker = $request->input('checker');
        $apps = $request->input('apps');
        $user =   user_general::where('runrecno',auth()->user()->runrecno)->first();

        // dd($apps);

        if (count($checker) > 0  && count($apps) != 0) {
              // ชื่อเจ้าหน้าที่รับผิดชอบตรวจสอบ
             $reg_fname = user_general::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))
                                ->whereIn('runrecno',$checker)
                                ->whereNotNull('reg_fname')
                                ->pluck('title')
                                ->toArray();
             // ส่ง E-mail
             $reg_email = user_general::select('reg_email')
                                ->whereIn('runrecno',$checker)
                                ->whereNotNull('reg_email')
                                ->pluck('reg_email')
                                ->toArray();
            // $RegisterExpert =  RegisterExpert::select('app_no','name','status','token')->whereIN('id',$apps)->get();

            foreach ($apps as $app_id) {
                   $app = RegisterExpert::find($app_id);
                if ($app){
                     // เช็คคำขอมอบหมายให้เจ้าหน้าที่หรือยัง
                    if($app->status == 1){
                       $app->update(['status'=> 2]);
                    }
                    $this->save_expert_assign($checker,$app_id);
                    // if (count($reg_email) > 0) {
                    //     $mail = new IBAssignStaffMail([
                    //             'apps' => $app ?? null,
                    //             'email' => auth()->user()->reg_email ?? 'admin@admin.com',
                    //             'reg_fname' => (count($reg_fname) > 0) ? implode(", ", $reg_fname) : null
                    //         ]);

                    //     Mail::to($reg_email)->send($mail);
                    // }
                }
            }

 
          }
          return redirect('certify/register-experts')->with('flash_message', 'เพิ่ม เจ้าหน้าที่ที่รับผิดชอบ เรียบร้อยแล้ว');

    }

    private function save_expert_assign($checker, $id){
        RegisterExpertAssign::where('register_expert_id', $id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['register_expert_id'] = $id;
          $input['user_id'] = $item;
          $input['created_by'] = auth()->user()->runrecno;
          RegisterExpertAssign::create($input);
        }
      }

      public function destroy($id, Request $request)
      {
          $model = str_slug('registerexperts','-');
          if(auth()->user()->can('delete-'.$model)) {
  
            $requestData = $request->all();
  
            if(array_key_exists('cb', $requestData)){
              $ids = $requestData['cb'];
              $db = new RegisterExpert;
              RegisterExpert::whereIn($db->getKeyName(), $ids)->delete();
            }else{
                RegisterExpert::destroy($id);
            }
  
            return redirect('certify/register-experts')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
          }
          abort(403);
  
      }

      public function update_state(Request $request){

        $model = str_slug('registerexperts','-');
        if(auth()->user()->can('edit-'.$model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new RegisterExpert;
              RegisterExpert::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
  
          return redirect('certify/register-experts')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
  
        abort(403);
  
      }

 
}
