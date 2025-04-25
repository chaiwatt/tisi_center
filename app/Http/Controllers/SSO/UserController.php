<?php

namespace App\Http\Controllers\SSO;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sso\User AS SSO_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Basic\Prefix;
use App\Models\Sso\UserHistory;
use HP;
use HP_WS;
use Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'media/com_user/';
    }

    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_type   = $request->input('filter_type');
        $filter_block  = $request->input('filter_block');
        $filter_state  = $request->input('filter_state');

        $registerDate_start = $request->input('registerDate_start');
        $registerDate_end   = $request->input('registerDate_end');

        $lastvisitDate_start = $request->input('lastvisitDate_start');
        $lastvisitDate_end   = $request->input('lastvisitDate_end');

        $query = SSO_User::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                                $query->Where(DB::Raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->Orwhere(DB::Raw("REPLACE(tax_number,' ','')"),  'LIKE', "%$search_full%")
                                                        ->Orwhere(DB::Raw("REPLACE(tax_number,'-','')"),  'LIKE', "%$search_full%")
                                                        ->Orwhere(DB::Raw("REPLACE(username,' ','')"),  'LIKE', "%$search_full%")
                                                        ->Orwhere(DB::Raw("REPLACE(email,' ','')"),  'LIKE', "%$search_full%");
                                            });
                                })
                                ->when($filter_type, function ($query, $filter_type){
                                    $query->where('applicanttype_id', $filter_type);
                                })
                                ->when(!is_null($filter_block), function ($query) use ($filter_block){
                                    $query->where('block', $filter_block);
                                })
                                ->when($filter_state, function ($query, $filter_state){
                                    $query->where('state', $filter_state);
                                })
                                ->when($registerDate_start, function ($query, $registerDate_start) use( $registerDate_end ){
                                    $registerDate_start = HP::convertDate($registerDate_start, true);
                                    if( is_null($registerDate_end) ){
                                        return $query->whereDate('registerDate', '=', $registerDate_start);
                                    }else{
                                        return $query->whereDate('registerDate', '>=', $registerDate_start);
                                    }
                                })
                                ->when($registerDate_end, function ($query, $registerDate_end) use($registerDate_start){
                                    $registerDate_end = HP::convertDate($registerDate_end, true);
                                    if( is_null($registerDate_start) ){
                                        return $query->whereDate('registerDate', '=', $registerDate_end);
                                    }else{
                                        return $query->whereDate('registerDate', '<=', $registerDate_end);
                                    }
                                })
                                ->when($lastvisitDate_start, function ($query, $lastvisitDate_start) use( $lastvisitDate_end ){
                                    $lastvisitDate_start = HP::convertDate($lastvisitDate_start, true);
                                    if( is_null($lastvisitDate_end) ){
                                        return $query->whereDate('lastvisitDate', '=', $lastvisitDate_start);
                                    }else{
                                        return $query->whereDate('lastvisitDate', '>=', $lastvisitDate_start);
                                    }
                                })
                                ->when($lastvisitDate_end, function ($query, $lastvisitDate_end) use($lastvisitDate_start){
                                    $lastvisitDate_end = HP::convertDate($lastvisitDate_end, true);
                                    if( is_null($lastvisitDate_start) ){
                                        return $query->whereDate('lastvisitDate', '=', $lastvisitDate_end);
                                    }else{
                                        return $query->whereDate('lastvisitDate', '<=', $lastvisitDate_end);
                                    }
                                })
                                ->orderby('registerDate', 'DESC');

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" class="item_checkbox" name="cb[]" class="cb" value="'. $item->id .'" data-state="'. $item->state.'">';
                            })
                            ->addColumn('applicant_types', function ($item) {
                                $applicant_types =  HP::applicant_types();
                                return  array_key_exists($item->applicanttype_id, $applicant_types) ? $applicant_types[$item->applicanttype_id] : '<i class="text-muted">ไม่มีข้อมูล</i>';
                            })
                            ->addColumn('name', function ($item) {
                                return ($item->name).' '.($item->check_api==1 ? '<i class="fa fa-check-circle-o text-success" title="ตรวจสอบกับหน่วยงานที่เกี่ยวข้องแล้ว"></i>' : null ).'<div>('.($item->tax_number).')</div>';
                            })
                            ->addColumn('date_birth', function ($item) {
                                $date = null;
                                if( $item->applicanttype_id == 2 ){
                                    $date =  HP::DateThaiFull($item->date_of_birth);
                                }else{
                                    $date =  HP::DateThaiFull($item->date_niti);
                                }
                                return $date;
                            })
                            ->addColumn('branch_code', function ($item) {
                                return !empty($item->branch_code) ? $item->branch_code : '-';
                            })
                            ->addColumn('email', function ($item) {
                                return (!empty($item->email) ? $item->email : null).'<div>('.($item->username).')</div>';;
                            })
                            ->addColumn('register_date', function ($item) {
                                return !empty($item->registerDate) ? HP::DateTimeFullThai($item->registerDate) : '-';
                            })
                            ->addColumn('lastvisit_date', function ($item) {
                                return !empty($item->lastvisitDate) ? HP::DateTimeFullThai($item->lastvisitDate) : '-';
                            })
                            ->addColumn('status', function ($item) {
                                $history = $item->user_history_group_many()->where('data_field','block')->orderBy('id','desc')->first();
                                $span    = $item->block!=1 ? '(<span class="text-success">ใช้งาน</span>)' : '(<span class="text-danger" title="วันที่:'.( !empty($history->created_at)?HP::revertDate($history->created_at):null ).' เนื่องจาก:'.( !empty($history->remark)?$history->remark:null ).'">บล็อค</span>)';
                                return (!empty($item->StateNameHtml) ? $item->StateNameHtml : null).'<div>'.($span).'</div>';;
                            })
                            ->addColumn('action', function ($item) {

                                $btn = '';
                                $model = str_slug('user-sso','-');
                                if(auth()->user()->can('view-'.$model)) {
                                    $btn .= ' <a href="'. url('/sso/user-sso/' . $item->getKey()).'" title="View soko" class="btn btn-info btn-xs">  <i class="fa fa-eye" aria-hidden="true"></i>  </a>';
                                }

                                if(auth()->user()->can('edit-'.$model)) {
                                    $btn .= ' <a href="'. url('/sso/user-sso/' . $item->getKey() . '/edit') .'" title="Edit soko" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                                }
                                return $btn;
                            })
                            ->rawColumns(['checkbox', 'name', 'email', 'action','applicant_types','status'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('sso.user.index');
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
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('add-'.$model)) {
            $roles = Role::all();
            $trader_roles = [];
            return view('sso.user.create', compact( 'roles', 'trader_roles'));
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
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->validate([
                'password' => 'required|string'
            ]);
            $prefix                             = Prefix::where('state',1)->pluck('initial', 'id');
            $requests                           = $request->all();
            $requestData                        =  $requests['jform'];

            //Gen ข้อมูลชื่อผู้ใช้งานและรหัสสาขา
            if($requestData['branch_type']==2){
                $branch = $this->genBranchData($requestData['tax_number']);
                $requestData['username']    = $branch->username;
                $requestData['branch_code'] = $branch->branch_code;
            }

            // $system                             = $requestData['system'];
            $requestData['contact_tax_id']      =  isset($requestData['contact_tax_id']) ?  self::PregReplace($requestData['contact_tax_id']) : null;
            // $requestData['contact_tel']         =  isset($requestData['contact_tel']) ?  self::PregReplace($requestData['contact_tel']) : null;
            $requestData['contact_tel']         =  isset($requestData['contact_tel']) ?   $requestData['contact_tel'] : null;
            $requestData['contact_phone_number']=  isset($requestData['contact_phone_number']) ?  self::PregReplace($requestData['contact_phone_number']) : null;
            $requestData['tax_number']          =  isset($requestData['tax_number']) ?  self::PregReplace($requestData['tax_number']) : null;
            $requestData['username']            =  isset($requestData['username']) ? $requestData['username'] : null;
            $requestData['password']            =  Hash::make($request->password);
            $requestData['tel']                 =  isset($requestData['contact_tel']) ? $requestData['contact_tel'] : null;
            $requestData['fax']                 =  isset($requestData['contact_fax']) ? $requestData['contact_fax'] : null;
            // $requestData['password']            =  md5($request->password);

         if(in_array($requestData['applicanttype_id'],[5])){ //ชื่อผู้ประกอบการ   อื่นๆ
            $requestData['date_niti']           =  HP::convertDate($requestData['date_birthday']);  // วันที่จดทะเบียนอื่นๆ
            $requestData['name']                =  $requestData['another_name'] ;
        }else if(in_array($requestData['applicanttype_id'],[4])){ //ชื่อผู้ประกอบการ   ส่วนราชการ
            $requestData['date_niti']           =  HP::convertDate($requestData['date_birthday']);  // วันที่จดทะเบียนส่วนราชการ
            $requestData['name']                =  $requestData['service_name'] ;
        }else  if(in_array($requestData['applicanttype_id'],[3])){ //ชื่อผู้ประกอบการ  คณะบุคคล
            $requestData['date_niti']           =  HP::convertDate($requestData['date_birthday']);  // วันที่จดทะเบียนนิติบุคคล
            $requestData['name']                =  $requestData['faculty_name'] ;
        }else if(in_array($requestData['applicanttype_id'],[2])){ //ชื่อผู้ประกอบการ  บุคคลธรรมดา
            $requestData['date_of_birth']       = HP::convertDate($requestData['date_birthday']);  // วันเกิดบุคคลธรรมดา
            $requestData['name']                =  (isset($requestData['person_first_name']) && isset($requestData['person_last_name']))   ? $requestData['person_first_name'].' '. $requestData['person_last_name']  : null;
            $requestData['prefix_name']         = $requestData['person_prefix_name'];
            $requestData['prefix_text']         = $prefix[$requestData['person_prefix_name']];
        }else{  // ชื่อผู้ประกอบการ นิติบุคคล
            $prefix_name                        = ['1'=>'บริษัทจำกัด','2'=>'บริษัทมหาชนจำกัด','3'=>'ห้างหุ้นส่วนจำกัด','4'=>'ห้างหุ้นส่วนสามัญนิติบุคคล'];
            $requestData['date_niti']           =  HP::convertDate($requestData['date_birthday']);  // วันที่จดทะเบียนนิติบุคคล
            $requestData['prefix_name']         = $requestData['prefix_name'];
            $requestData['prefix_text']         = array_key_exists($requestData['prefix_name'],$prefix_name) ? $prefix_name[$requestData['prefix_name']] : null;

        }
            $requestData['contact_name']        =  (isset($requestData['contact_first_name']) && isset($requestData['contact_last_name']))   ? $requestData['contact_first_name'].' '. $requestData['contact_last_name']  : null;
            $requestData['contact_prefix_name'] = $requestData['contact_prefix_name'];
            $requestData['contact_prefix_text'] = $prefix[$requestData['contact_prefix_name']] ?? null;

            if(is_file($request->personfile)){
                $requestData['personfile']     =   self::storeFile($request->personfile, $requestData['username']);
            }



            $requestData['registerDate']        =  date('Y-m-d H:i:s');
            $requestData['lastvisitDate']       =  date('Y-m-d H:i:s');
            $requestData['state']               = 1;
            // $requestData['state']               = 2;
            $requestData['block']               = 1;
            // $requestData['block']               = 0;
            $requestData['params']              = '{}';
            $requestData['department_id']       = '0';
            $requestData['agency_tel']          = '';
            $requestData['authorize_data']      = '';

            $requestData['google2fa_status'] = array_key_exists('google2fa_status', $requestData) ? 1 : 0 ;

            $user = SSO_User::create($requestData);


            //บันทึก Roles
            RoleUser::where('user_id', $user->getKey())->delete();//ลบออกก่อน

            $requestData                        = $request->all();
            if(isset($requestData['roles'])){
                $roles = [];
                foreach ((array)$requestData['roles'] as $role_id) {
                    $roles[] = ['role_id' => $role_id, 'user_id' => $user->getKey()];
                }
                RoleUser::insert($roles);
            }

            $config = HP::getConfig();
             $mail = new RegisterMail(['email'        =>  'e-Accreditation@tisi.mail.go.th' ?? '-',
                                        'name'        =>  !empty($user->name)  ?  $user->name  : '-',
                                        'username'     =>  !empty($user->username)  ?  $user->username  : '-',
                                        'password'    =>  !empty($request->password)  ? $request->password : '',
                                        'link'        =>   !empty($config->url_sso)  ?     $config->url_sso.'activated-mail/'.base64_encode($user->id)   : url('')
                                    ]);

                if($user->email){
                     Mail::to($user->email)->send($mail);
                }


            return redirect('sso/user-sso')->with('flash_message', 'เพิ่มข้อมูลผู้ใช้งานเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function genBranchData($tax_number){

        $username    = null;
        $branch_code = null;

        $users = SSO_User::where('tax_number', $tax_number)->orderby('username', 'desc')->get();

        if(is_null($users)){//เป็นสาขาแรก
            $branch_code = '0001';
            $username = $tax_number.$branch_code;
        }else{
            $last_branch = $users->first();//สาขาล่าสุดที่มีในระบบ
            $next_number = (int)$last_branch->branch_code;

            next:
            $next_branch = str_pad(++$next_number, 4, '0', STR_PAD_LEFT);
            $branch_code = $next_branch;
            $username = $tax_number.$next_branch;

            if(count($users->where('username', $username))>0){//ยังมีซ้ำ
                goto next;
            }
        }

        return (object)compact('username', 'branch_code');
    }

    public function PregReplace($request)
    {
        return preg_replace("/[^a-z\d]/i", '', $request);
    }
   // สำหรับเพิ่มรูปไปที่ store
    public function storeFile($files, $tax_number)
    {

        if ($files) {
            $attach_path        =  $this->attach_path.$tax_number;
            $filename           =  HP::ConvertCertifyFileName(@$files->getClientOriginalName());
            $fullFileName       =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

            $storagePath        = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $file_name          = basename($storagePath); // Extract the filename
            $corporatefile[]    = array('realfile' => $file_name, 'filename' => $filename);
            return   json_encode($corporatefile);
        }else{
            return null;
        }
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
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('view-'.$model)) {

            $user = SSO_User::findOrFail($id);
            $roles = Role::all();
            $trader_roles = RoleUser::where('user_id', $user->getKey())->pluck('role_id', 'role_id')->toArray();
            $user->date_niti     = !is_null($user->date_niti) ? HP::revertDate($user->date_niti,true) : null ;
            $user->date_of_birth = !is_null($user->date_of_birth) ? HP::revertDate($user->date_of_birth,true) : null ;

            return view('sso.user.show', compact('user', 'roles', 'trader_roles'));
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
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('edit-'.$model)) {

            $user = SSO_User::findOrFail($id);
            $user->date_niti     = !is_null($user->date_niti) ? HP::revertDate($user->date_niti,true) : null ;
            $user->date_of_birth = !is_null($user->date_of_birth) ? HP::revertDate($user->date_of_birth,true) : null ;

            $user->name = !empty($user->prefix_text) && mb_strpos($user->name, $user->prefix_text)===0 ? mb_substr($user->name, mb_strlen($user->prefix_text)) : $user->name ;

            $roles = Role::all();
            $trader_roles = RoleUser::where('user_id', $user->getKey())->pluck('role_id', 'role_id')->toArray();

            return view('sso.user.edit', compact('user', 'roles', 'trader_roles'));
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
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [

        	]);
            $requestData = $request->all();
            $requestData['date_niti'] = !empty($requestData['date_niti']) ? HP::convertDate($requestData['date_niti'],true) : null ;//converdate
            $requestData['date_of_birth'] = !empty($requestData['date_of_birth']) ? HP::convertDate($requestData['date_of_birth'],true) : null ;//converdate
            if(array_key_exists('password', $requestData)){//รหัสผ่าน
                if(!empty($requestData['password'])){
                    $requestData['password'] = Hash::make($requestData['password']);
                    $requestData['lastResetTime'] = date('Y-m-d H:i:s');
                }else{
                    unset($requestData['password']);
                }
            }

            if(array_key_exists('prefix_name', $requestData)){//คำนำหน้าชื่อ
                $prefixs = [];
                if($requestData['applicanttype_id']==1){//นิติบุคคล
                    $prefixs = HP::company_prefixs();
                }elseif ($requestData['applicanttype_id']==2) {//บุคคลธรรมดา
                    $prefixs = HP::person_prefixs();
                }
                if(array_key_exists($requestData['prefix_name'], $prefixs)){
                    $requestData['prefix_text'] = $prefixs[$requestData['prefix_name']];
                }
            }

            $prefix = Prefix::find($requestData['contact_prefix_name']); //คำนำหน้าชื่อ
            $requestData['contact_prefix_text'] = !is_null($prefix) ? $prefix->title : null;

            $requestData['google2fa_status'] = array_key_exists('google2fa_status', $requestData) ? 1 : 0 ;

            //รายการที่จะแก้ไข
            $user = SSO_User::findOrFail($id);

            $requestData['contact_name']        =  (isset($requestData['contact_first_name']) && isset($requestData['contact_last_name']))   ? $requestData['contact_first_name'].' '. $requestData['contact_last_name']  : null;
            $requestData['contact_prefix_name'] = $requestData['contact_prefix_name'];

            //เอกสารแนบการยืนยันตัวตน
            if(is_file($request->personfile)){
                $requestData['personfile'] = self::storeFile($request->personfile, $user->username);
            }

            $user_array = $user->toArray();

            //เก็บ Log
            foreach ($requestData as $key => $value) {
                if(array_key_exists($key, $user_array) ){
                    if($user_array[$key]!=$value){
                        UserHistory::Add($user->id,
                                         $key,
                                         $user_array[$key],
                                         $value,
                                         $requestData['remark'],
                                         null
                                        );
                    }
                }
            }

            $user->update($requestData);

            //บันทึก Roles
            RoleUser::where('user_id', $user->getKey())->delete();//ลบออกก่อน

            if(isset($requestData['roles'])){
                $roles = [];
                foreach ((array)$requestData['roles'] as $role_id) {
                    $roles[] = ['role_id' => $role_id, 'user_id' => $user->getKey()];
                }
                RoleUser::insert($roles);
            }

            return redirect('sso/user-sso')->with('flash_message', 'แก้ไขข้อมูลผู้ใช้งานเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function block(Request $request)
    {
        $model = str_slug('user-sso','-');
        if(auth()->user()->can('delete-'.$model)) {

            $requestData = $request->all();

            $db = new SSO_User;

            if(array_key_exists('cb', $requestData)){

                $ids = $requestData['cb'];
                $remark = $requestData['remark'];

                foreach ($ids as $id) {
                    $user = SSO_User::find($id);
                    UserHistory::Add($user->id,
                                     'block',
                                     $user->block,
                                     1,
                                     $remark,
                                     null
                                    );
                }

                SSO_User::whereIn($db->getKeyName(), $ids)->update(['block' => 1]);

            }

            return redirect('sso/user-sso')->with('flash_message', 'บล็อคผู้ใช้งานเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function unblock(Request $request)
    {
        $model = str_slug('user-sso','-');

        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $db = new SSO_User;

            if(array_key_exists('cb', $requestData)){

                $ids = $requestData['cb'];

                foreach ($ids as $id) {
                    $user = SSO_User::find($id);
                    UserHistory::Add($user->id,
                                     'block',
                                     $user->block,
                                     0,
                                     null,
                                     null
                                    );
                }

                SSO_User::whereIn($db->getKeyName(), $ids)->update(['block' => 0]);
            }

            return 'success';
            
        }
        
        return "not success";

    }

    public function confirm_status(Request $request)
    {
        $result = 'not success';

        $model = str_slug('user-sso','-');
        if(auth()->user()->can('delete-'.$model)) {

            $requestData = $request->all();

            $db = new SSO_User;

            if(array_key_exists('cb', $requestData)){
                $ids = $requestData['cb'];

                foreach ($ids as $id) {
                    $user = SSO_User::find($id);

                    if($user->state!=2){
                        UserHistory::Add($user->id,
                            'state',
                            $user->state,
                            2,
                            null,
                            null
                        );
                    }

                    if($user->block!=0){
                        UserHistory::Add($user->id,
                            'block',
                            $user->block,
                            0,
                            null,
                            null
                        );
                    }
                }

                SSO_User::whereIn($db->getKeyName(), $ids)->update(['state' => 2, 'block' => 0]);
            }
            $result = 'success';
        }

        return $result;

    }

    public function checkemailexits(Request $req)
    {
        $email = $req->email;
        $emailcheck = SSO_User::where('email', $email)->count();
        $res = $emailcheck > 0 ? "already" : '' ;

        return $res;
    }

    public function datatype(Request $req)
    {
        $config = HP::getConfig();

        $response = [];
        if($req->applicanttype_id == 1){ // การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก 0105553080958

            if(HP::check_number_counter($req->tax_id, 14)){//เป็นเลข 14 หลัก

                // $url = $config->tisi_api_factory_url;//'https://www3.tisi.go.th/moiapi/srv.asp?pid=4';
                // $data = array(
                //             'val'    => $req->tax_id,
                //             'IP'     => $req->ip,    // IP Address,
                //             'Refer'  => 'center.tisi.go.th'
                //             );
                // $options = array(
                //         'http' => array(
                //             'header'  => "Content-type: application/x-www-form-urlencoded",
                //             'method'  => 'POST',
                //             'content' => http_build_query($data),
                //         )
                // );
                // if(strpos($url, 'https')===0){//ถ้าเป็น https
                //     $options["ssl"] = array(
                //                             "verify_peer" => false,
                //                             "verify_peer_name" => false,
                //                       );
                // }
                // $context  = stream_context_create($options);
                // $json_data = file_get_contents($url, false, $context);
                // $api = json_decode($json_data);

                $api = HP_WS::getIndustry($req->tax_id, $req->ip);

                if(!empty($api->result)){ // Start   14 หลัก
                    $result                        = $api->result[0];
                    $response['applicanttype_id']  = 1;       // ประเภทผู้ประกอบการ
                    $response['JuristicType']      = $result->FID ;
                    $response['prefix_id']         =   ''  ;        // คำนำหน้า
                    $response['juristic_status']   = '';
                    $response['tax_id']            = $result->FID ?? '';        // Username สำหรับเข้าใช้งาน
                    $response['name']              = $result->FNAME ?? '';
                    $response['name_last']         = '';
                    $response['RegisterDate']       = !empty($result->STARTDATE) ? HP::revertDate(date('Y-m-d', strtotime($result->STARTDATE)),false) : '';
                    $response['address']            =  $result->FADDR ?? ''; // ที่อยู่
                    $response['moo']                =  $result->FMOO ?? ''; //  หมู่
                    $response['soi']                =  $result->SOI ?? ''; // ซอย
                    $response['road']               =  $result->ROAD ?? ''; //  ถนน
                    $response['ampur']              =  $result->AMPNAME ?? ''; // แขวง/อำเภอ
                    $response['tumbol']             =  $result->TUMNAME ?? ''; //  ตำบล/แขวง
                    $response['province']           =  $result->PRONAME ?? ''; // จังหวัด
                    $zipcode  = HP::getZipcode($result->AMPNAME,$result->TUMNAME, $result->PRONAME);
                    if(!empty($zipcode)){
                        $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                    }else{
                        $response['zipcode']            =  ''; // รหัสไปรษณีย์
                    }
                    $response['country_code']   =  '';  // รหัสประเทศ

                    $response['phone']              =  $result->Phone ?? ''; // โทรศัพท์
                    $response['email']              =  $result->Email ?? ''; // อีเมล

                }

            }elseif(HP::check_number_counter($req->tax_id, 13)){

                    // $url = $config->tisi_api_corporation_url;//'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';
                    // $data = array(
                    //         'val'   => $req->tax_id,
                    //         'IP'    => $req->ip,       // IP Address,
                    //         'Refer' => 'center.tisi.go.th'
                    //         );
                    // $options = array(
                    //         'http' => array(
                    //             'header'  => "Content-type: application/x-www-form-urlencoded",
                    //             'method'  => 'POST',
                    //             'content' => http_build_query($data),
                    //         )
                    // );
                    // if(strpos($url, 'https')===0){//ถ้าเป็น https
                    //     $options["ssl"] = array(
                    //                             "verify_peer" => false,
                    //                             "verify_peer_name" => false,
                    //                       );
                    // }
                    // $context  = stream_context_create($options);
                    // $json_data = file_get_contents($url, false, $context);
                    // $api = json_decode($json_data);

                    $api = HP_WS::getJuristic($req->tax_id, $req->ip);

                    $data_prefix                   = ['บริษัทจำกัด'=>'1','บริษัทมหาชนจำกัด'=>'2','ห้างหุ้นส่วนจำกัด'=>'3','ห้างหุ้นส่วนสามัญนิติบุคคล'=>'4'];
                    $juristic_status               = ['ยังดำเนินกิจการอยู่'=>'1', 'ฟื้นฟู'=>'2', 'คืนสู่ทะเบียน'=>'3'];
                    if(!empty($api->JuristicName_TH)){ // Start การดึงข้อมูลนิติบุคคลจาก DBD ด้วยเลขนิติบุคคล 13 หลัก
                        $response['applicanttype_id']  = 1;       // ประเภทผู้ประกอบการ
                        $response['JuristicType']      =  $api->JuristicType ;
                        $response['prefix_id']         =  array_key_exists($api->JuristicType,$data_prefix) ? $data_prefix[$api->JuristicType] : ''  ;        // คำนำหน้า
                        $response['juristic_status']   =  array_key_exists($api->JuristicStatus,$juristic_status) ? $juristic_status[$api->JuristicStatus] : $api->JuristicStatus ;  //สถานะนิติบุคคล
                        $response['tax_id']            = $api->JuristicID ?? '';        // Username สำหรับเข้าใช้งาน
                        if(in_array($api->JuristicType,['บริษัทจำกัด','บริษัทมหาชนจำกัด'])){
                            $response['name']              = 'บริษัท '.$api->JuristicName_TH ?? '';
                        }else{
                            $response['name']              = $api->JuristicName_TH ?? '';
                        }

                        $response['name_last']         = '';
                        $response['RegisterDate']      = !empty($api->RegisterDate) ? substr($api->RegisterDate,6) .'/'.substr($api->RegisterDate,4,-2).'/'.substr($api->RegisterDate,0,4) : '';

                        if(!empty($api->CommitteeInformations)){  // ข้อมูลคณะกรรมการ
                            $prefixs                            = Prefix::pluck('id', 'initial');
                            $informations                       =  min($api->CommitteeInformations);
                            $response['first_name']             =  $informations->FirstName ?? ''; // ชื่อ
                            $response['last_name']              =  $informations->LastName ?? ''; // สกุล
                            if($informations->Title == 'น.ส.'){
                                $response['contact_prefix_name']    =   '3'; // คำนำหน้า
                            }else{
                                $response['contact_prefix_name']    =  array_key_exists($informations->Title,$prefixs) ? $prefixs[$informations->Title] : ''; // คำนำหน้า
                            }

                        }else{
                            $response['first_name']             =  ''; // ชื่อ
                            $response['last_name']              =  ''; // สกุล
                            $response['contact_prefix_name']    =  ''; // คำนำหน้า
                        }

                    if( count($api->AddressInformations) > 0){  // in_array($api->JuristicType,['บริษัทจำกัด']) &&
                        
                        $address = max($api->AddressInformations);
                        $ampur_temp = $address->Ampur;
                        $address = HP::format_address_company_api($address);

                        $response['address']            =  $address->AddressNo ?? ''; // ที่อยู่
                        $response['moo']                =  $address->Moo ?? ''; //  หมู่
                        $response['soi']                =  $address->Soi ?? ''; // ซอย
                        $response['road']               =  $address->Road ?? ''; //  ถนน
                        $response['ampur']              =  $address->Ampur ?? ''; // แขวง/อำเภอ
                        $response['tumbol']             =  $address->Tumbol ?? ''; //  ตำบล/แขวง
                        $response['province']           =  $address->Province ?? ''; // จังหวัด

                        $zipcode  = HP::getZipcode($address->Tumbol, $ampur_temp, $address->Province);
                        if(!empty($zipcode)){
                            $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                        }else{
                            $response['zipcode']            =  ''; // รหัสไปรษณีย์
                        }

                        $response['phone']              =  $address->Phone ?? ''; // โทรศัพท์
                        $response['email']              =  $address->Email ?? ''; // อีเมล
                        $response['country_code']       =  '';  // รหัสประเทศ

                    }else{
                        $response['address']            =  ''; // ที่อยู่
                        $response['moo']                =  ''; //  หมู่
                        $response['soi']                =  ''; // ซอย
                        $response['road']               =  ''; //  ถนน
                        $response['tumbol']             =  ''; //  ตำบล/แขวง
                        $response['ampur']              =  ''; // แขวง/อำเภอ
                        $response['province']           =  ''; // จังหวัด
                        $response['zipcode']            =  ''; // รหัสไปรษณีย์
                        $response['phone']              =  ''; // โทรศัพท์
                        $response['email']              =  ''; // อีเมล
                        $response['country_code']       =  '';  // รหัสประเทศ
                    }
                }
            }

        }else if(in_array($req->applicanttype_id, [2, 4, 5]) && HP::check_number_counter($req->tax_id, 13)){

            // $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';
            // $data = array(
            //         'val'   => $req->tax_id,
            //         'IP'    => $req->ip,
            //         'Refer' => 'center.tisi.go.th'
            //         );
            // $options = array(
            //         'http' => array(
            //             'header'  => "Content-type: application/x-www-form-urlencoded",
            //             'method'  => 'POST',
            //             'content' => http_build_query($data),
            //         )
            // );
            // if(strpos($url, 'https')===0){//ถ้าเป็น https
            //     $options["ssl"] = array(
            //                             "verify_peer" => false,
            //                             "verify_peer_name" => false,
            //                       );
            // }
            // $context  = stream_context_create($options);
            // $json_data = file_get_contents($url, false, $context);
            // $api = json_decode($json_data);
            $api = HP_WS::getPersonal($req->tax_id, $req->ip);

            if(!empty($api->firstName)){
                $prefixs                      = Prefix::pluck('id', 'initial')->toArray();
                $response['applicanttype_id']  = 2;       // ประเภทผู้ประกอบการ
                $response['JuristicType']      =  $api->titleName ;
                $response['prefix_id']         = array_key_exists($api->titleDesc,$prefixs) ? $prefixs[$api->titleDesc] : ''; // คำนำหน้า
                $response['juristic_status']   = '';
                $response['tax_id']            = $api->JuristicID ?? '';        // Username สำหรับเข้าใช้งาน
                $response['name']              = $api->firstName ?? '';
                $response['name_last']         = $api->lastName ?? '';
                $response['RegisterDate']      = !empty($api->dateOfBirth) ? substr($api->dateOfBirth,6) .'/'.substr($api->dateOfBirth,4,-2).'/'.substr($api->dateOfBirth,0,4) : '';
            }else{
                $response['applicanttype_id']  = 2;       // ประเภทผู้ประกอบการ
                $response['JuristicType']      = '';
                $response['prefix_id']         = '';        // คำนำหน้า
                $response['juristic_status']   = '';
                $response['tax_id']            = '';        // Username สำหรับเข้าใช้งาน
                $response['name']              = '';
                $response['name_last']         = '';
                $response['RegisterDate']      = '';
            }

            // $url = $config->tisi_api_house_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=3';
            // $data = array(
            //         'val'       => $req->tax_id,
            //         'IP'        => $req->ip,      // IP Address,
            //         'Refer'     => 'center.tisi.go.th'
            //         );
            // $options = array(
            //         'http' => array(
            //             'header'  => "Content-type: application/x-www-form-urlencoded",
            //             'method'  => 'POST',
            //             'content' => http_build_query($data),
            //         )
            // );
            // if(strpos($url, 'https')===0){//ถ้าเป็น https
            //     $options["ssl"] = array(
            //                             "verify_peer" => false,
            //                             "verify_peer_name" => false,
            //                       );
            // }
            // $context  = stream_context_create($options);
            // $json_data = file_get_contents($url, false, $context);
            // $address = json_decode($json_data);
            $address = HP_WS::getPersonalHouse($req->tax_id, $req->ip);

            if(!empty($address->houseNo)){
                $response['address']            =  $address->houseNo ?? ''; // ที่อยู่
                $response['moo']                =  $address->villageNo ?? ''; //  หมู่
                $response['soi']                =  $address->alleyDesc ?? ''; // ซอย
                $response['road']               =  $address->roadDesc ?? ''; //  ถนน
                $response['tumbol']             =  $address->subdistrictDesc ?? ''; //  ตำบล/แขวง
                $response['ampur']              =  $address->districtDesc ?? ''; // แขวง/อำเภอ
                $response['province']           =  $address->provinceDesc ?? ''; // จังหวัด

                $zipcode  = HP::getZipcode($address->subdistrictDesc,$address->districtDesc, $address->provinceDesc);
                if(!empty($zipcode)){
                    $response['zipcode']            = $zipcode ?? ''; // รหัสไปรษณีย์
                }else{
                    $response['zipcode']            =  ''; // รหัสไปรษณีย์
                }
                $response['phone']              =  ''; // โทรศัพท์
                $response['email']              =  ''; // อีเมล

            }else{
                $response['address']            =  ''; // ที่อยู่
                $response['moo']                =  ''; //  หมู่
                $response['soi']                =  ''; // ซอย
                $response['road']               =  ''; //  ถนน
                $response['tumbol']             =  ''; //  ตำบล/แขวง
                $response['ampur']              =  ''; // แขวง/อำเภอ
                $response['province']           =  ''; // จังหวัด
                $response['zipcode']            =  ''; // รหัสไปรษณีย์
                $response['phone']              =  ''; // โทรศัพท์
                $response['email']              =  ''; // อีเมล
                $response['country_code']   =  '';  // รหัสประเทศ
            }
        }else if($req->applicanttype_id == 3 && HP::check_number_counter($req->tax_id, 13)){

                // $url = $config->tisi_api_faculty_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
                // $data = array(
                //             'val'   => $req->tax_id,
                //             'IP'    => $req->ip,       // IP Address,
                //             'Refer' => 'center.tisi.go.th'
                //             );
                //     $options = array(
                //             'http' => array(
                //                 'header'  => "Content-type: application/x-www-form-urlencoded",
                //                 'method'  => 'POST',
                //                 'content' => http_build_query($data),
                //             )
                //     );
                //     if(strpos($url, 'https')===0){//ถ้าเป็น https
                //         $options["ssl"] = array(
                //                                 "verify_peer" => false,
                //                                 "verify_peer_name" => false,
                //                           );
                //     }
                //     $context  = stream_context_create($options);
                //     $json_data = file_get_contents($url, false, $context);
                //     $api = json_decode($json_data);
                    
                    $api = HP_WS::getRdVat($req->tax_id, $req->ip);

                    if(!empty($api->vName)){
                        $response['juristic_status']   = '';
                        $response['applicanttype_id']  = 3;       // ประเภทผู้ประกอบการ
                        $response['prefix_id']         = $api->vBranchTitleName; // คำนำหน้า
                        $response['tax_id']            = $api->vNID ?? '';        // Username สำหรับเข้าใช้งาน
                        $response['name']              = $api->vBranchName ?? '';
                        $response['name_last']         =  '';
                        if(!empty($api->vBusinessFirstDate)){
                             $date =     explode("/",$api->vBusinessFirstDate);
                            $response['RegisterDate']      = $date[2].'/'.$date[1].'/'.($date[0] +543);
                        }else{
                            $response['RegisterDate']      =  '';
                        }

                        $response['address']            =  $api->vHouseNumber ?? '';  // ที่อยู่
                        $response['moo']                =  $api->vMooNumber ?? ''; //  หมู่
                        $response['soi']                =  $api->vSoiName ?? ''; // ซอย
                        $response['road']               =   ''; //  ถนน
                        $response['tumbol']             =  $api->vThambol ?? ''; //  ตำบล/แขวง
                        $response['ampur']              =  $api->vAmphur ?? ''; // แขวง/อำเภอ
                        $response['province']           =  $api->vProvince ?? ''; // จังหวัด
                        $response['zipcode']            =  $api->vPostCode ?? ''; // รหัสไปรษณีย์
                        $response['phone']              =  ''; // โทรศัพท์
                        $response['email']              =  ''; // อีเมล
                        $response['country_code']   =  '';  // รหัสประเทศ
                    }

        }
        return response()->json($response);
    }
    public function check_tax_number(Request $req)
    {
         $response = [];

         $user = SSO_User::where('tax_number', $req->tax_id)->first();
         if(!is_null($user) &&   !in_array($req->applicanttype_id,[1]) ){
                    $response['check'] = true;
                    $response['branch_code'] = false;
                    $response['applicant_type'] = $user->ApplicantTypeTitle ?? 'คณะบุคคล';
         }else  if(!is_null($user) &&  in_array($req->applicanttype_id,[1]) ){
             if($req->branch_type == 2 ){
                   $branch_type = SSO_User::where('tax_number', $req->tax_id)->where('branch_type',2)->where('branch_code',$req->branch_code)->first();
                 if(!is_null($branch_type)){
                    $response['check'] = true;
                    $response['branch_code'] = true;
                    $response['name'] = $user->name ?? '';
                 }else{
                    $response['check'] = false;
                    $response['branch_code'] = false;
                 }

             }else{
                $response['check'] = true;
                $response['branch_code'] = false;
             }

         }
         else{
            $response['check'] = false;
            $response['branch_code'] = false;
         }

         $email = SSO_User::where('email', $req->email)->first();
         if(!is_null($email)){
            $response['email'] = true;
         }else{
            $response['email'] = false;
         }
        return response()->json($response);
    }
    public function get_tax_number(Request $req)
    {
         $response = [];
         $user = SSO_User::where('tax_number', $req->tax_id)->first();
         if(!is_null($user)){
            $response['check'] = true;
         }else{
            $response['check'] = false;
         }

        $person = HP::check_number_counter($req->tax_id, 13) ? $this->getPerson($req->tax_id, $req->ip) : null ;
        if(is_null($person)){//ไม่พบข้อมูลในทะเบียนราษฎร์
            $response['person'] =  'ขออภัยเลขประจำตัวประชาชน '. $req->tax_id . ' ไม่พบในทะเบียนราษฎร์กรุณาติดต่อเจ้าหน้าที่';
        }elseif($person->statusOfPersonCode == '1'){//เสียชีวิต
            $response['person'] =  'เลขประจำตัวประชาชน '. $req->tax_id . ' ไม่สามารถลงทะเบียนได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>เสียชีวิต</u>';
        }elseif(!is_null($user) && $person->firstName.' '.$person->lastName != $user->name && $person->titleName.$person->firstName.' '.$person->lastName != $user->name){//ชื่อไม่ตรงกับในระบบ
            $response['person'] =   'เลขประจำตัวประชาชน '. $req->tax_id . ' ชื่อในระบบไม่ตรงกับในทะเบียนราษฎร์กรุณาติดต่อเจ้าหน้าที่';
        }else{
            $response['person'] =   true;
        }

        return response()->json($response);
    }

    private function getPerson($tax_id, $ip){

        $person = null;

        // $config = HP::getConfig();

        // $url = $config->tisi_api_person_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=2';

        // $data = array(
        //         'val'   => $tax_id,
        //         'IP'    => $ip,
        //         'Refer' =>  'center.tisi.go.th'
        //         );
        // $options = array(
        //         'http' => array(
        //             'header'  => "Content-type: application/x-www-form-urlencoded",
        //             'method'  => 'POST',
        //             'content' => http_build_query($data),
        //         )
        // );
        // if(strpos($url, 'https')===0){//ถ้าเป็น https
        //     $options["ssl"] = array(
        //                             "verify_peer" => false,
        //                             "verify_peer_name" => false,
        //                       );
        // }
        // $context  = stream_context_create($options);
        // $json_data = file_get_contents($url, false, $context);
        // $api = json_decode($json_data);

        $api = HP_WS::getPersonal($tax_id, $ip);

        if(!empty($api->firstName)){
            $person = $api;
        }

        return $person;
    }

    public function get_legal_entity(Request $req)
    {
         $response = [];
         $user = SSO_User::where('tax_number', $req->tax_id)->where('applicanttype_id', 1)->first();
         if(!is_null($user)){
            $response['check'] = true;
            $response['status'] = 'เลขนิติบุคคล ' .  $req->tax_id  .' มีการลงทะเบียนในระบบแล้ว:&nbsp;<u>'.($user->name ?? '').'</u>';
         }else{
            $response['check'] = false;
         }

        $entity = HP::check_number_counter($req->tax_id, 13) ? self::CheckLegalEntity($req->tax_id) : 'false' ;
        if($entity != 'false'){
            $response['juristic_status'] = $entity;
        }else{
            $response['juristic_status'] = false;
        }

        return response()->json($response);
    }


    public function CheckLegalEntity($tax_number)
    {

        // $config = HP::getConfig();

            $response = 'false';
        //     $url = $config->tisi_api_corporation_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=1';
        //     $data = array(
        //             'val' => $tax_number,
        //             'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
        //             'Refer' => 'center.tisi.go.th'
        //             );
        //     $options = array(
        //             'http' => array(
        //                 'header'  => "Content-type: application/x-www-form-urlencoded",
        //                 'method'  => 'POST',
        //                 'content' => http_build_query($data),
        //             )
        //     );
        //     if(strpos($url, 'https')===0){//ถ้าเป็น https
        //         $options["ssl"] = array(
        //                                 "verify_peer" => false,
        //                                 "verify_peer_name" => false,
        //                           );
        //     }
        //     $context  = stream_context_create($options);
        //     $json_data = file_get_contents($url, false, $context);
        //     $api = json_decode($json_data);

        $api = HP_WS::getJuristic($tax_number, (new Request)->ip());

        if(!empty($api->JuristicName_TH)){
            // $response = 'true';
            $juristic_status = ['ยังดำเนินกิจการอยู่' => '1', 'ฟื้นฟู' => '2', 'คืนสู่ทะเบียน' => '3'];
            $status =  array_key_exists($api->JuristicStatus,$juristic_status) ? $juristic_status[$api->JuristicStatus] : $api->JuristicStatus ;  //สถานะนิติบุคคล
            $response =  $status;
        }
        return $response;

    }

    public function get_legal_faculty(Request $req)
    {
         $response = [];
         $user = SSO_User::where('tax_number', $req->tax_id)->first();
         if(!is_null($user)){
            $response['check'] = true;
            $response['applicant_type'] = $user->ApplicantTypeTitle ?? 'คณะบุคคล';
         }else{
            $response['check'] = false;
            $response['applicant_type'] = false;
         }
         $faculty = HP::check_number_counter($req->tax_id, 13) ? self::getFaculty($req->tax_id) : 'false' ;
         if($faculty != 'false'){
             $response['branch_title'] = $faculty;
         }else{
             $response['branch_title'] = false;
         }


        return response()->json($response);
    }

    public function getFaculty($tax_number)
    {

        $config = HP::getConfig();

        $response = 'false';
        // $url = $config->tisi_api_faculty_url; //'https://www3.tisi.go.th/moiapi/srv.asp?pid=5';
        // $data = array(
        //         'val' => $tax_number,
        //         'IP' =>  $_SERVER['REMOTE_ADDR'],    // IP Address,
        //         'Refer' => 'center.tisi.go.th'
        //         );
        // $options = array(
        //         'http' => array(
        //             'header'  => "Content-type: application/x-www-form-urlencoded",
        //             'method'  => 'POST',
        //             'content' => http_build_query($data),
        //         )
        // );
        // if(strpos($url, 'https')===0){//ถ้าเป็น https
        //     $options["ssl"] = array(
        //                             "verify_peer" => false,
        //                             "verify_peer_name" => false,
        //                         );
        // }
        // $context  = stream_context_create($options);
        // $json_data = file_get_contents($url, false, $context);
        // $api = json_decode($json_data);

        $api = HP_WS::getRdVat($tax_number, (new Request)->ip);
        
        if(!empty($api->vBranchTitleName)){
            $response = $api->vBranchTitleName;
        }
        return $response;

    }


    // เช็คเลข 13 หลัก
    public function get_taxid(Request $req)
    {
         $response = [];
         $user = SSO_User::where('tax_number', $req->tax_id)->first();
         if(!is_null($user)){
            $response['check'] = true;
            $response['applicant_type'] = $user->ApplicantTypeTitle ?? 'นิติบุคคล';
         }else{
            $response['check'] = false;
         }

if(!empty($req->tax_id) && HP::check_number_counter($req->tax_id, 13)){
    $entity  =  self::CheckLegalEntity($req->tax_id);   // นิติบุคคล
    if($entity != 'false' && !in_array($entity,[1,2,3])){
        $response['status']            = 'หมายเลข  '. $req->tax_id . ' เป็นนิติบุคคล ไม่สามารถลงทะเบียนได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>'.$entity.'</u>';
        $response['check_api']         = true;
        $response['type']              = 1;
    }else    if(in_array($entity,[1,2,3])){
        $response['status']            = 'หมายเลข ' . $req->tax_id .' เป็นนิติบุคคล ท่านต้องการลงทะเทียนประเภทนิติบุคคลหรือไม่';
        $response['check_api']         = true;
        $response['type']              = 1;
    }else{
        $person = $this->getPerson($req->tax_id, $req->ip);  // บุคคลธรรมดา
        if(is_null($person)){//ไม่พบข้อมูลในทะเบียนราษฎร์
            $faculty = self::getFaculty($req->tax_id);
            if($faculty == 'คณะบุคคล'){
                $response['status']    =  'หมายเลข ' . $req->tax_id .' เป็นคณะบุคคล  ท่านต้องการลงทะเทียนประเภทคณะบุคคลหรือไม่';
                $response['check_api'] = true;
                $response['type']      = 3;
            }else{
                $response['status']    = false;
                $response['check_api'] = false;
                $response['type']      = 3;
            }
        }elseif($person->statusOfPersonCode == '1'){//เสียชีวิต
               $response['status']         =  'หมายเลข  '. $req->tax_id . ' เป็นเลขประจำตัวประชาชน ไม่สามารถลงทะเบียนได้ เนื่องจากมีสถานะเป็น:&nbsp;<u>เสียชีวิต</u>';
               $response['check_api']      = true;
               $response['type']           = 2;
               $response['person']         = 1;
        }else{
               $response['status']         =  'หมายเลข  '. $req->tax_id . ' เป็นบุคคลธรรมดา ท่านต้องการลงทะเทียนประเภทบุคคลธรรมดาหรือไม่';
               $response['check_api']      = true;
               $response['type']           = 2;
               $response['person']         = 0;
        }
    }
}else{
    $response['check_api']      = false;
}

        return response()->json($response);
    }
    // เช็คอีเมล
    public function check_email(Request $req)
    {
        $response = [];
        $user = SSO_User::where('email', $req->email)->first();
        if(!is_null($user)){
           $response['check'] = true;
           $response['status'] = 'กรุณากรอกใหม่ เนื่องจาก e-Mail นี้ได้ลงทะเทียนในระบบบริการอิเล็กทรอนิกส์ สมอ.';
        }else{
           $response['check'] = false;
        }
        if(filter_var($req->email, FILTER_VALIDATE_EMAIL) ){
            $response['check_email'] = true;
         }else{
            $response['status_email'] = 'กรุณากรอกใหม่ เนื่องจากรูปแบบ e-Mail ไม่ถูกต้อง :&nbsp;<u>'. $req->email . '</u>';
            $response['check_email'] = false;
         }

        return $response;
    }
   // เช็คอีเมลสาขา
    public function check_branch_code(Request $req)
    {
         $response = [];
        $user = SSO_User::where('tax_number', $req->tax_number)->where('branch_code', $req->branch_code)->first();
        if(!is_null($user)){
           $response['check'] = true;
           $response['status'] = 'กรุณากรอกใหม่ เนื่องจากรหัสสาขานี้ได้ลงทะเทียนในระบบบริการอิเล็กทรอนิกส์ สมอ. :&nbsp;<u>'. $user->name . '</u>';;
        }else{
           $response['check'] = false;
        }
        return $response;
    }

    //gen username กรณีสาขา
    public function get_next_username_branch(Request $request){
        $tax_number = $request->get('tax_number');
        $result = null;
        if(!empty($tax_number)){
            $result = $this->genBranchData($tax_number);
        }
        return response()->json($result);
    }

    //เปรียบเทียบข้อมูลผู้ใช้งานผปก.กับกรมการปกครอง
    public function ComparePersonal(Request $request){

        $user_id = $request->get('user_id');
        $user = SSO_User::findOrFail($user_id);

        $result = (object)['status' => 'fail', 'msg' => ''];

        if(!is_null($user)){

            if(HP::check_number_counter($user->tax_number, 13)){
                $person = HP_WS::getPersonal($user->tax_number, $request->ip()); //ข้อมูลบุคคล
            }else{
                $person = (object)['Code' => 'invalid format', 'Message' => 'รูปแบบเลขประจำตัวประชาชนไม่ถูกต้อง'];
            }
   
            if(isset($person->Code)){
                if($person->Code=='00404'){
                    $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมการปกครอง</div>';
                }else{
                    $result->msg = '<div class="text-center">'.$person->Message.'</div>';
                }
            }elseif($person->status=='no-connect'){
                $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
            }elseif(isset($person->firstName)){//ได้ข้อมูล

                $births               = str_split($person->dateOfBirth, 2);
                $births[2]            = $births[2]=='00' ? '01' : $births[2];//เดือน 00
                $births[3]            = $births[3]=='00' ? '01' : $births[3];//วันที่ 00
                $person_date_of_birth = HP::DateThai((($births[0].$births[1])-543).'-'.$births[2].'-'.$births[3]);
                $user_date_of_birth   = HP::DateThai($user->date_of_birth);

                $data_tables = [];//เก็บข้อมูลเพิ่มแสดงในตาราง
                $data_tables[] = ['label' => 'คำนำหน้าชื่อ', 'old' => $user->prefix_text, 'new' => $person->titleName, 'status' => (trim($user->prefix_text) == trim($person->titleName))];
                $data_tables[] = ['label' => 'ชื่อ', 'old' => $user->person_first_name, 'new' => $person->firstName, 'status' => (trim($user->person_first_name) == trim($person->firstName))];
                $data_tables[] = ['label' => 'สกุล', 'old' => $user->person_last_name, 'new' => $person->lastName, 'status' => (trim($user->person_last_name) == trim($person->lastName))];
                $data_tables[] = ['label' => 'วันเกิด', 'old' => $user_date_of_birth, 'new' => $person_date_of_birth, 'status' => ($user_date_of_birth == $person_date_of_birth)];

                //ข้อมูลทะเบียนบ้าน
                $house = HP_WS::getPersonalHouse($user->tax_number, $request->ip());
                if(isset($house->Message)){//มีปัญหาในการดึงข้อมูล
                    $data_tables[] = ['label_head' => 'ข้อมูลทะเบียนบ้าน', 'data' => $house->Message, 'status' => false];
                }elseif(isset($house->Code)){//มีปัญหาในการดึงข้อมูล
                    $data_tables[] = ['label_head' => 'ข้อมูลทะเบียนบ้าน', 'data' => $house->Code, 'status' => false];
                }elseif(isset($house->status) && $house->status=='no-connect'){
                    $data_tables[] = ['label_head' => 'ข้อมูลทะเบียนบ้าน', 'data' => 'ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้', 'status' => false];
                }else {

                    $house_district = mb_strpos($house->districtDesc, 'เขต')===0 ? mb_substr($house->districtDesc, 3) : $house->districtDesc ; //อำเภอ/เขต ตัดคำว่าเขตข้างหน้าออก
                    $house_soi      = trim($house->alleyWayDesc.' '.$house->alleyDesc);//ตรอก+ซอย

                    //แปลง 0 หรือ - เป็น null
                    $house->houseNo   = HP::FormatToNull($house->houseNo);
                    $house->villageNo = HP::FormatToNull($house->villageNo);
                    $house_soi        = HP::FormatToNull($house_soi);
                    $house->roadDesc  = HP::FormatToNull($house->roadDesc);

                    $data_tables[] = ['label_head' => 'ข้อมูลทะเบียนบ้าน', 'data' => null, 'status' => true];
                    $data_tables[] = ['label' => 'เลขที่', 'old' => $user->address_no, 'new' => $house->houseNo, 'status' => ($user->address_no == $house->houseNo)];
                    $data_tables[] = ['label' => 'หมู่', 'old' => $user->moo, 'new' => $house->villageNo, 'status' => ($user->moo == $house->villageNo)];
                    $data_tables[] = ['label' => 'ตรอก/ซอย', 'old' => $user->soi, 'new' => $house_soi, 'status' => ($user->soi == $house_soi)];
                    $data_tables[] = ['label' => 'ถนน', 'old' => $user->street, 'new' => $house->roadDesc, 'status' => ($user->street == $house->roadDesc)];
                    $data_tables[] = ['label' => 'ตำบล/แขวง', 'old' => $user->subdistrict, 'new' => $house->subdistrictDesc, 'status' => ($user->subdistrict == $house->subdistrictDesc)];
                    $data_tables[] = ['label' => 'อำเภอ/เขต', 'old' => $user->district, 'new' => $house_district, 'status' => ($user->district == $house_district)];
                    $data_tables[] = ['label' => 'จังหวัด', 'old' => $user->province, 'new' => $house->provinceDesc, 'status' => ($user->province == $house->provinceDesc)];
                }

                $msg_html  = '<div class="row">';
                $msg_html .= '    <div class="col-md-12">';
                $msg_html .= '        <div class="table-responsive">';
                $msg_html .= '            <table class="table-striped table color-bordered-table info-bordered-table table-hover">';
                $msg_html .= '                <thead>';
                $msg_html .= '                    <tr><th class="text-center font-15">ชื่อข้อมูล</th><th class="text-center font-15">ข้อมูลในระบบ</th><th class="text-center font-15">ข้อมูลจากกรมการปกครอง</th></tr>';
                $msg_html .= '                </thead>';
                $msg_html .= '                <tbody>';
                    foreach ($data_tables as $data_table) {
                        if(array_key_exists('label_head', $data_table)){ //หัวข้อ
                            $msg_html .= '<tr class="'.($data_table['status'] ? '' : 'danger').'"><td><b>'.$data_table['label_head'].'</b></td><td colspan="2">'.$data_table['data'].'</td></tr>';
                        }else {
                            $msg_html .= '<tr class="'.($data_table['status'] ? 'success' : 'danger').'"><td><b>'.$data_table['label'].'</b></td><td>'.$data_table['old'].'</td><td>'.$data_table['new'].'</td></tr>';
                        }
                    }
                $msg_html .= '                </tbody>';
                $msg_html .= '            </table>';
                $msg_html .= '        </div>';
                $msg_html .= '    </div>';
                $msg_html .= '</div>';

                $result->status = 'success';
                $result->msg    = $msg_html;
            }
        }

        return response()->json($result);

    }

    //เปรียบเทียบข้อมูลผู้ใช้งานผปก.กับกรมพัฒนาธุรกิจการค้า
    public function CompareCompany(Request $request){

        $user_id = $request->get('user_id');
        $user = SSO_User::findOrFail($user_id);

        $result = (object)['status' => 'fail', 'msg' => ''];

        if(!is_null($user)){

            if(HP::check_number_counter($user->tax_number, 13)){
                $company = HP_WS::getJuristic($user->tax_number, $request->ip());
            }else{//รูปแบบเลขนิติบุคคลไม่ถูกต้อง
                $company = (object)['result' => 'รูปแบบเลขประจำตัวนิติบุคคลไม่ถูกต้อง'];
            }

            if(isset($company->result)){
                if($company->result=='Bad Request'){
                    $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมพัฒนาธุรกิจการค้า</div>';
                }else{
                    $result->msg = '<div class="text-center">'.$company->result.'</div>';
                }
            }elseif(isset($company->Result)){
                $result->msg  = '<div class="text-center">เว็บเซอร์วิสปลายทางไม่พร้อมให้บริการ</div>';
                $result->msg .= isset($company->Code) ? '<div class="text-center font-15">Code : "'.$company->Code.'"</div>' : '' ;
                $result->msg .= '<div class="text-center font-15">Message : "'.$company->Result.'"</div>';
            }elseif($company->status=='no-connect'){
                $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
            }elseif(isset($company->JuristicType)){//ได้ข้อมูล

                $user_date_niti    = HP::DateThai($user->date_niti);
                $register_dates    = str_split($company->RegisterDate, 2);
                $company_date_niti = (($register_dates[0].$register_dates[1])-543).'-'.$register_dates[2].'-'.$register_dates[3];
                $company_date_niti = HP::DateThai($company_date_niti);

                if(in_array($company->JuristicType, ['บริษัทจำกัด', 'บริษัทมหาชนจำกัด'])){
                    $company_name = 'บริษัท '.$company->JuristicName_TH;
                }else if(in_array($company->JuristicType, ['ห้างหุ้นส่วนจำกัด', 'ห้างหุ้นส่วนสามัญนิติบุคคล'])){
                    $company_name = $company->JuristicType.' '.$company->JuristicName_TH;
                }else{
                    $company_name = $company->JuristicName_TH;
                }
                $company_name = HP::replace_multi_space($company_name);

                $company_status_text   = SSO_User::ConvertJuristicStatusTextToJuristicStatus($company->JuristicStatus);//แปลงรูปแบบสถานะนิติบุคคลจาก API เป็นของระบบ SSO
                $company_status_number = SSO_User::ConvertJuristicStatusTextToNumber($company->JuristicStatus);//แปลงรูปแบบสถานะนิติบุคคลจาก API เป็นรหัส

                $data_tables = [];//เก็บข้อมูลเพิ่มแสดงในตาราง
                $data_tables[] = ['label' => 'ประเภทบริษัท', 'old' => $user->prefix_text, 'new' => $company->JuristicType, 'status' => (trim($user->prefix_text) == trim($company->JuristicType))];
                $data_tables[] = ['label' => 'วันที่จดทะเบียนนิติบุคคล', 'old' => $user_date_niti, 'new' => $company_date_niti, 'status' => ($user_date_niti==$company_date_niti)];
                $data_tables[] = ['label' => 'ชื่อบริษัท', 'old' => $user->name, 'new' => $company_name, 'status' => ($user->name==$company_name)];
                $data_tables[] = ['label' => 'สถานะนิติบุคคล', 'old' => $user->JuristicStatusText, 'new' => $company_status_text, 'status' => ($user->JuristicStatusText==$company_status_text)];

                if($user->juristic_status==4 || $company_status_number==4){//ถ้ามีข้อมูลใดข้อมูลหนึ่งเป็นเลิกกิจการ
                    $data_tables[] = ['label' => 'สาเหตุเลิกกิจการ', 'old' => $user->juristic_cause_quit, 'new' => $company->JuristicStatus, 'status' => ($user->juristic_cause_quit==$company->JuristicStatus)];
                }


                //ที่ตั้งสำนักงาน
                $data_address = [];
                $address = [];
                if(property_exists($company, 'AddressInformations') && count($company->AddressInformations) > 0){
                    foreach ($company->AddressInformations as $info) {
                        if($info->AddressName=='สำนักงานใหญ่'){
                            $address = $info;
                            break;
                        }
                    }
                    if(count((array)$address)==0){//ไม่มี สำนักงานใหญ่ ให้เอาข้อมูล Array ชุดแรกเป็นสำนักงานใหญ่
                        $address = $company->AddressInformations[0];
                    }

                    $address = HP::format_address_company_api($address);

                    //เช็คข้อมูล
                    $data_address[] = ['label' => 'เลขที่', 'old' => $user->address_no, 'new' => $address->AddressNo, 'status' => ($user->address_no==$address->AddressNo)];
                    $data_address[] = ['label' => 'หมู่', 'old' => $user->moo, 'new' => $address->Moo, 'status' => ($user->moo==$address->Moo)];
                    $data_address[] = ['label' => 'ตรอก/ซอย', 'old' => $user->soi, 'new' => $address->Soi, 'status' => ($user->soi==$address->Soi)];
                    $data_address[] = ['label' => 'ถนน', 'old' => $user->street, 'new' => $address->Road, 'status' => ($user->street==$address->Road)];
                    $data_address[] = ['label' => 'ตำบล/แขวง', 'old' => $user->subdistrict, 'new' => $address->Tumbol, 'status' => ($user->subdistrict==$address->Tumbol)];
                    $data_address[] = ['label' => 'อำเภอ/เขต', 'old' => $user->district, 'new' => $address->Ampur, 'status' => ($user->district==$address->Ampur)];
                    $data_address[] = ['label' => 'จังหวัด', 'old' => $user->province, 'new' => $address->Province, 'status' => ($user->province==$address->Province)];

                }

                $msg_html  = '<div class="row">';
                $msg_html .= '    <div class="col-md-12">';
                $msg_html .= '        <div class="table-responsive">';
                $msg_html .= '            <table class="table-striped table color-bordered-table info-bordered-table table-hover">';
                $msg_html .= '                <thead>';
                $msg_html .= '                    <tr><th class="text-center font-15">ชื่อข้อมูล</th><th class="text-center font-15">ข้อมูลในระบบ</th><th class="text-center font-15">ข้อมูลจากกรมพัฒนาธุรกิจการค้า</th></tr>';
                $msg_html .= '                </thead>';
                $msg_html .= '                <tbody>';
                                foreach ($data_tables as $data_table) {
                                    $msg_html .= '<tr class="'.($data_table['status'] ? 'success' : 'danger').'"><td><b>'.$data_table['label'].'</b></td><td>'.$data_table['old'].'</td><td>'.$data_table['new'].'</td></tr>';
                                }
                $msg_html .= '<tr><td>ที่ตั้งสำนักงานใหญ่</td><td></td><td></td></tr>';
                                foreach ($data_address as $data_table) {
                                    $msg_html .= '<tr class="'.($data_table['status'] ? 'success' : 'danger').'"><td><b>'.$data_table['label'].'</b></td><td>'.$data_table['old'].'</td><td>'.$data_table['new'].'</td></tr>';
                                }
                $msg_html .= '                </tbody>';
                $msg_html .= '            </table>';
                $msg_html .= '        </div>';
                $msg_html .= '    </div>';
                $msg_html .= '</div>';

                $result->status = 'success';
                $result->msg    = $msg_html;

            }
        }

        return response()->json($result);

    }

    //เปรียบเทียบข้อมูลผู้ใช้งานผปก.กับกรมสรรพากร
    public function CompareRd(Request $request){

        $user_id = $request->get('user_id');
        $user = SSO_User::findOrFail($user_id);

        $result = (object)['status' => 'fail', 'msg' => ''];

        if(!is_null($user)){

            if(HP::check_number_counter($user->tax_number, 13)){
                $company = HP_WS::getRdVat($user->tax_number, $request->ip());
            }else{
                $company = (object)['vMessageErr' => 'รูปแบบเลขประจำตัวผู้เสียภาษีไม่ถูกต้อง'];
            }

            if(!empty($company->vMessageErr)){
                $result->msg = '<div class="text-center">'.$company->vMessageErr.'</div>';
            }elseif($company->status=='no-connect'){
                $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
            }elseif(isset($company->vBranchName)){//ได้ข้อมูล

                //วันที่จดทะเบียนในระบบ
                $user_date_niti    = HP::DateThai($user->date_niti);

                //วันที่จดทะเบียนจาก API
                $register_date     = $company->vBusinessFirstDate;
                $register_dates    = strpos($register_date, '/')!==false ? explode('/', $register_date) : explode('-', $register_date) ; //ตัดด้วย / หรือ -
                if(count($register_dates)===3){

                    $company_date_niti = null ;
                    if (strlen($register_dates['0']) === 4) { //แบบ ปี-เดือน-วัน
                        $company_date_niti = $company->vBusinessFirstDate;
                    } elseif (strlen($register_dates['2']) === 4) { //แบบ วัน-เดือน-ปี หรือ เดือน-วัน-ปี
                        if (in_array($company->vBranchTitleName, ['ห้างหุ้นส่วนสามัญ', 'สหกรณ์', 'มหาวิทยาลัย', 'โรงเรียน', 'กิจการร่วมค้า']) || (int)$register_dates['1']>12) { //เดือน-วัน-ปี กรณี ห้างหุ้นส่วนสามัญ
                            $company_date_niti = $register_dates['2'] . '-' . str_pad($register_dates['0'], 2, "0", STR_PAD_LEFT) . '-' . str_pad($register_dates['1'], 2, "0", STR_PAD_LEFT);
                        } else { //วัน-เดือน-ปี
                            $company_date_niti = $register_dates['2'] . '-' . $register_dates['1'] . '-' . $register_dates['0'];
                        }
                    }

                    $company_date_niti = HP::DateThai($company_date_niti);
                }else{
                    $company_date_niti = null;
                }

                //แปลง 0 หรือ - เป็น null
                $company->vBranchTitleName = HP::FormatToNull($company->vBranchTitleName);
                $company->vHouseNumber     = HP::FormatToNull($company->vHouseNumber);
                $company->vMooNumber       = HP::FormatToNull($company->vMooNumber);
                $company->vSoiName         = HP::FormatToNull($company->vSoiName);
                $company->vStreetName      = HP::FormatToNull($company->vStreetName);
                $company->vBuildingName    = HP::FormatToNull($company->vBuildingName);
                $company->vFloorNumber     = HP::FormatToNull($company->vFloorNumber);
                $company->vRoomNumber      = HP::FormatToNull($company->vRoomNumber);
                $company->vVillageName     = HP::FormatToNull($company->vVillageName);

                //เลขที่
                $company->vHouseNumber = $company->vHouseNumber . ' ' . $company->vBuildingName . ' ' . $company->vFloorNumber . ' ' . $company->vRoomNumber . ' ' . $company->vVillageName; //รวมเป็นฟิลด์เดียว
                $company->vHouseNumber = HP::replace_multi_space($company->vHouseNumber); //รวม เลขที่ อาคาร ชั้น ห้อง หมู่บ้าน


                $data_tables = [];//เก็บข้อมูลเพิ่มแสดงในตาราง
                $data_tables[] = ['label' => 'ประเภท', 'old' => $user->prefix_text, 'new' => $company->vBranchTitleName, 'status' => (trim($user->prefix_text) == trim($company->vBranchTitleName))];
                $data_tables[] = ['label' => 'ชื่อ', 'old' => $user->name, 'new' => $company->vBranchName, 'status' => (trim($user->name) == trim($company->vBranchName))];
                $data_tables[] = ['label' => 'วันที่จดทะเบียน', 'old' => $user_date_niti, 'new' => $company_date_niti, 'status' => ($user_date_niti == $company_date_niti)];

                $data_address = [];
                $data_address[] = ['label' => 'เลขที่', 'old' => $user->address_no, 'new' => $company->vHouseNumber, 'status' => (trim($user->address_no) == trim($company->vHouseNumber))];
                $data_address[] = ['label' => 'หมู่ที่', 'old' => $user->moo, 'new' => $company->vMooNumber, 'status' => (trim($user->moo) == trim($company->vMooNumber))];
                $data_address[] = ['label' => 'ตรอก/ซอย', 'old' => $user->soi, 'new' => $company->vSoiName, 'status' => (trim($user->soi) == trim($company->vSoiName))];
                $data_address[] = ['label' => 'ถนน', 'old' => $user->street, 'new' => $company->vStreetName, 'status' => (trim($user->street) == trim($company->vStreetName))];
                $data_address[] = ['label' => 'ตำบล/แขวง', 'old' => $user->subdistrict, 'new' => $company->vThambol, 'status' => (trim($user->subdistrict) == trim($company->vThambol))];
                $data_address[] = ['label' => 'อำเภอ/เขต', 'old' => $user->district, 'new' => $company->vAmphur, 'status' => (trim($user->district) == trim($company->vAmphur))];
                $data_address[] = ['label' => 'จังหวัด', 'old' => $user->province, 'new' => $company->vProvince, 'status' => (trim($user->province) == trim($company->vProvince))];
                $data_address[] = ['label' => 'รหัสไปรษณีย์', 'old' => $user->zipcode, 'new' => $company->vPostCode, 'status' => (trim($user->zipcode) == trim($company->vPostCode))];

                $msg_html  = '<div class="row">';
                $msg_html .= '    <div class="col-md-12">';
                $msg_html .= '        <div class="table-responsive">';
                $msg_html .= '            <table class="table-striped table color-bordered-table info-bordered-table table-hover">';
                $msg_html .= '                <thead>';
                $msg_html .= '                    <tr><th class="text-center font-15">ชื่อข้อมูล</th><th class="text-center font-15">ข้อมูลในระบบ</th><th class="text-center font-15">ข้อมูลจากกรมสรรพากร</th></tr>';
                $msg_html .= '                </thead>';
                $msg_html .= '                <tbody>';
                                foreach ($data_tables as $data_table) {
                                    $msg_html .= '<tr class="'.($data_table['status'] ? 'success' : 'danger').'"><td><b>'.$data_table['label'].'</b></td><td>'.$data_table['old'].'</td><td>'.$data_table['new'].'</td></tr>';
                                }

                $msg_html .= '<tr class=""><td>ที่ตั้งสำนักงานใหญ่</td><td></td><td></td></tr>';
                                foreach ($data_address as $data_table) {
                                    $msg_html .= '<tr class="'.($data_table['status'] ? 'success' : 'danger').'"><td><b>'.$data_table['label'].'</b></td><td>'.$data_table['old'].'</td><td>'.$data_table['new'].'</td></tr>';
                                }

                $msg_html .= '                </tbody>';
                $msg_html .= '            </table>';
                $msg_html .= '        </div>';
                $msg_html .= '    </div>';
                $msg_html .= '</div>';

                $result->status = 'success';
                $result->msg    = $msg_html;

            }
        }

        return response()->json($result);

    }

    //อัพเดทข้อมูลประเภทผู้สมัครอัตโนมัติ
    public function auto_edit_applicanttype(Request $request){

        $id   = $request->get('id');
        $user = SSO_User::findOrFail($id);

        $result = (object)['status' => 'fail', 'not_found_count' => 0];
        if (HP::check_number_counter($user->tax_number, 13)) {

            $applicant_type_before = '<span class="text-danger">'.$user->ApplicantTypeTitle.'</span>';

            //ลำดับการเรียกฟังก์ชั่นเช็คกับ API
            $order_checks = [
                             1 => 'api_check_juristic',
                             2 => 'api_check_personal',
                             3 => 'api_check_rdvat'
                            ];
            
            $service_checks = [
                                1 => 'API กรมพัฒนาธุรกิจการค้า',
                                2 => 'API กรมการปกครอง',
                                3 => 'API กรมสรรพากร',
                              ];


            //ถ้าไม่ใช่หน่วยงานราชการและอื่นๆ ให้สลับลำดับการเรียกใช้ API ตามที่ผู้ใช้งานเรียกมาก่อน                
            if(!in_array($user->applicanttype_id, ['4', '5'])){
                $tmp                                   = $order_checks[1];
                $order_checks[1]                       = $order_checks[$user->applicanttype_id];
                $order_checks[$user->applicanttype_id] = $tmp;
            }

            foreach($order_checks as $applicanttype_id => $function_name){
                if($result->status=='fail'){
                    $result = $this->{$function_name}($user, $request, $result, $applicant_type_before);

                    if(property_exists($result, 'msg')){
                        $result->message_list[] = ['service_name' => $service_checks[$applicanttype_id], 'msg' => $result->msg];
                        if(property_exists($result, 'not_found')){
                            //จำนวน API ที่หาแล้วไม่พบ
                            if(!property_exists($result, 'not_found_count')){
                                $result->not_found_count = 0;
                            }
                            $result->not_found_count++; //จำนวน API ที่ค้นหาข้อมูลไม่พบ
                        }
                    }

                }
            }

            if($result->not_found_count==3 && !in_array($user->applicanttype_id, ['4', '5'])){ //หาไม่พบทั้ง 3 API และเป็นนิติบุคคล บุคคลธรรมดา หรือคณะบุคคล ให้อัพเดทเป็นอื่นๆ
                $this->update_applicanttype($user, 5);
                $result->status = 'success';
                $result->operation = 'changed';
                $result->msg = "ระบบเปลี่ยนแปลงประเภทการลงทะเบียนของเลขประจำตัวผู้เสียภาษี <b>$user->tax_number</b> <br>จากประเภท $applicant_type_before เป็น <span class=\"text-success\">$user->ApplicantTypeTitle</span>";
            }

            // #01 ตรวจสอบกับกรมพัฒนาธุรกิจการค้า
            // if($user->applicanttype_id!=1){
            //     $result = $this->api_check_juristic($user, $request, $result, $applicant_type_before);
            // }

            // #02 ตรวจสอบกับกรมการปกครอง
            // if($result->status=='fail' && $user->applicanttype_id!=2){//ถ้ายังไม่ได้ถูกแก้ไข
            //     $result = $this->api_check_personal($user, $request, $result, $applicant_type_before);
            // }

            // #03 ตรวจสอบกับกรมสรรพากร
            // if($result->status=='fail' && $user->applicanttype_id!=3){//ถ้ายังไม่ได้ถูกแก้ไข
            //     $result = $this->api_check_rdvat($user, $request, $result, $applicant_type_before);
            // }

        } else {//ไม่ใช่เลข 13 หลัก ไม่ต้องค้นหาใน API
            $result->status    = 'success';
            $result->operation = 'not-change';
            $result->msg       = 'รูปแบบเลขประจำตัวไม่ใช่ 13 หลัก';
        }

        return response()->json($result);

    }

    //ตรวจสอบว่าเป็นประเภทนิติบุคคลหรือไม่
    private function api_check_juristic($user, $request, $result, $applicant_type_before){

        unset($result->not_found);

        $company = HP_WS::getJuristic($user->tax_number, $request->ip());
        if (isset($company->result)) {
            if ($company->result == 'Bad Request') {
                $result->not_found = true;
                $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมพัฒนาธุรกิจการค้า</div>';
            } else {
                $result->msg = '<div class="text-center">' . $company->result . '</div>';
            }
        } elseif (isset($company->Result)) {
            $result->msg = '<div class="text-center">เว็บเซอร์วิสปลายทางไม่พร้อมให้บริการ</div>';
            $result->msg .= isset($company->Code) ? '<div class="text-center font-15">Code : "' . $company->Code . '"</div>' : '';
            $result->msg .= '<div class="text-center font-15">Message : "' . $company->Result . '"</div>';
        } elseif ($company->status == 'no-connect') {
            $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
        } elseif (isset($company->JuristicType)) { //พบข้อมูลในกรมพัฒนาธุรกิจการค้า
            if($user->applicanttype_id!=1){
                $this->update_applicanttype($user, 1);
                $result->status    = 'success';
                $result->operation = 'changed';
                $result->msg       = "ระบบเปลี่ยนแปลงประเภทการลงทะเบียนของเลขประจำตัวผู้เสียภาษี <b>$user->tax_number</b> <br>จากประเภท $applicant_type_before เป็น <span class=\"text-success\">$user->ApplicantTypeTitle</span>";
            }else{
                $result->status    = 'success';
                $result->operation = 'not-change';
                $result->msg       = "เป็นประเภท $user->ApplicantTypeTitle อยู่แล้ว";
            }
        }

        return $result;

    }

    //ตรวจสอบว่าเป็นบุคคลธรรมดาหรือไม่
    private function api_check_personal($user, $request, $result, $applicant_type_before){

        unset($result->not_found);

        $person = HP_WS::getPersonal($user->tax_number, $request->ip()); //ข้อมูลบุคคล

        if (isset($person->Code)) {
            if ($person->Code == '00404') {
                $result->not_found = true;
                $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมการปกครอง</div>';
            } elseif(isset($person->Message)) {
                if($person->Message=='CitizenID is not specify'){
                    $result->not_found = true;
                    $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมการปกครอง</div>';
                }else{
                    $result->msg = '<div class="text-center">' . $person->Message . '</div>';
                }
            }
        } elseif ($person->status == 'no-connect') {
            $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
        } elseif (isset($person->firstName)) { //ได้ข้อมูล
            if($user->applicanttype_id!=2){
                $this->update_applicanttype($user, 2);
                $result->status = 'success';
                $result->operation = 'changed';
                $result->msg = "ระบบเปลี่ยนแปลงประเภทการลงทะเบียนของเลขประจำตัวผู้เสียภาษี <b>$user->tax_number</b> <br>จากประเภท $applicant_type_before เป็น <span class=\"text-success\">$user->ApplicantTypeTitle</span>";
            }else{
                $result->status    = 'success';
                $result->operation = 'not-change';
                $result->msg = "เป็นประเภท $user->ApplicantTypeTitle อยู่แล้ว";
            }
        }

        return $result;

    }

    //ตรวจสอบว่าเป็นคณะบุคคลหรือไม่
    private function api_check_rdvat($user, $request, $result, $applicant_type_before){

        unset($result->not_found);

        $company = HP_WS::getRdVat($user->tax_number, $request->ip());

        if (!empty($company->vMessageErr)) {
            if(strpos($company->vMessageErr, 'Data not found')!==false){
                $result->not_found = true;
            }
            $result->msg = '<div class="text-center">' . $company->vMessageErr . '</div>';
        } elseif ($company->status == 'no-connect') {
            $result->msg = '<div class="text-center">ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้</div>';
        } elseif (!empty($company->vBranchName)) { //ได้ข้อมูล

            $config = HP::getConfig();
            $faculty_title_allows = explode(',', $config->faculty_title_allow);

            $result->status = 'success';
            if (in_array($company->vBranchTitleName, $faculty_title_allows)) { //เป็นประเภทคณะบุคคล
                if($user->applicanttype_id!=3){
                    $this->update_applicanttype($user, 3);
                    $result->operation = 'changed';
                    $result->msg = "ระบบเปลี่ยนแปลงประเภทการลงทะเบียนของเลขประจำตัวผู้เสียภาษี <b>$user->tax_number</b> <br>จากประเภท $applicant_type_before เป็น <span class=\"text-success\">$user->ApplicantTypeTitle</span>";
                }else{
                    $result->operation = 'not-change';
                    $result->msg       = "เป็นประเภท $user->ApplicantTypeTitle อยู่แล้ว";
                }
            } else {
                $result->status = 'fail';
                $result->operation = 'not-change';
                $result->msg = 'พบข้อมูลในกรมสรรพากร ไม่ใช่คณะบุคคล';
            }
        }else{
            $result->not_found = true;
            $result->msg = '<div class="text-center">ไม่พบข้อมูลในกรมสรรพากร</div>';
        }

        return $result;

    }

    //อัพเดทประเภทผู้ใช้งาน
    private function update_applicanttype($user, $applicant_type_new){

        //เก็บประวัติการแก้ไข
        UserHistory::Add($user->id,
                        'applicanttype_id',
                        $user->applicanttype_id,
                        $applicant_type_new,
                        null,
                        0,
                        'system:center'
                    );

        $user->applicanttype_id = $applicant_type_new;
        $user->save();
    }

}
