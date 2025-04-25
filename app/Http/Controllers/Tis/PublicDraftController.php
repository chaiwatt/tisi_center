<?php

namespace App\Http\Controllers\Tis;

use App\Models\Basic\Department;
use App\Models\Basic\SetFormat;
use App\Models\Tis\PublicDraft;
use App\Models\Tis\SetStandard;
use App\Models\Tis\Standard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Storage;
use HP;
use SHP;



class PublicDraftController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth', ['except' => ['create','store','index']]);


        $this->attach_path = '/files/public_draft/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('public_draft','-');
        if(auth()->user() || Auth::guest()) {

            $filter = [];
            $filter['perPage'] = $request->get('perPage', '');
            $filter['filter_type'] = $request->get('filter_type', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_format'] = $request->get('filter_format', '');
            $filter['filter_branch'] = $request->get('filter_branch', '');
            $filter['filter_standard'] = $request->get('filter_standard', '');

            $Query = new PublicDraft();

            if ($filter['filter_search'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_search'];
                                    $query->where('title', 'LIKE', "%{$search_text}%")
                                    ->orWhere('number_book', 'LIKE', "%{$search_text}%");
                         });
            }

            if ($filter['filter_type'] != ''){
                $Query = $Query->where('public_draft_type',$filter['filter_type']);
            }

            if ($filter['filter_format'] != ''){
                $Query = $Query->where('set_format_id',$filter['filter_format']);
            }

            if ($filter['filter_branch'] != ''){
                $Query = $Query->where('product_group_id',$filter['filter_branch']);
            }

            if ($filter['filter_standard'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_standard'];
                                    $query->where('tis_no', 'LIKE', "%{$search_text}%");
                         });
            }

            if ($filter['filter_status']!='') {
                $Query = $Query->where('status',$filter['filter_status']);
            }

            $publicdrafts = $Query->paginate($filter['perPage']);
            return view('tis.public_draft.index', compact('filter', 'publicdrafts'));
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
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('tis.public_draft.create');
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
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'public_draft_type' => 'required',
        			'set_format_id' => 'required',
                    'tis_no' => 'required',
                    'mask_date' => 'required',
                    'anniversary_date' => 'required'
            ]);

            $public_draft = new PublicDraft([
                'public_draft_type'=>$request->public_draft_type,
                'set_format_id'=>$request->set_format_id,
                'tis_no'=>$request->tis_no,
                'set_standard_id'=>$request->set_standard_id,
                'product_group_id'=>$request->product_group_id,
                'title'=>$request->title,
                'number_book'=>$request->number_book,
                'mask_date'=>$request->mask_date?Carbon::createFromFormat("d/m/Y",$request->mask_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null,
                'anniversary_date'=>$request->anniversary_date?Carbon::createFromFormat("d/m/Y",$request->anniversary_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null,
                'lock_qr'=>$request->lock_qr ? 'locked':'unlocked',
                'basic_staff_groups_id'=>$request->staff_group,
                'result_draft'=>$request->result_draft,
                'status'=>1,
                'created_by'=>Auth::user()->getKey(),
                'token'=>str_random(20),
            ]);
            try{
                $public_draft->save();
            }catch (\Exception $x){
                abort(404);
            }
            if ($public_draft->id){
                if ($request->attach_files){
                    $numberCountMore = 0;
                    foreach ($request->attach_files as $file){
                        if ($file){
                            $path = $this->storeFile($file,$request->attach_name[$numberCountMore],$this->attach_path);
                            try{
                                $name = $request->attach_name[$numberCountMore];
                            }catch (\Exception $x){
                                $name = null;
                            }
                            DB::table('tis_public_draft_attaches')->insert([
                                'public_draft_id'=>$public_draft->id,
                                'file_name'=>$name,
                                'file_path'=>$path,
                                'token'=>str_random(20),
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                        }
                        $numberCountMore ++;
                    }
                }
            }
            return redirect('tis/public_draft')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($token)
    {
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('view-'.$model)) {
            $public_draft = PublicDraft::whereToken($token)->first();
            if ($public_draft){
                return view('tis.public_draft.show',['public_draft'=>$public_draft]);
            }
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $public_draft = PublicDraft::whereToken($token)->first();
            $public_draft['mask_date'] = $public_draft['mask_date']?Carbon::createFromFormat("Y-m-d",$public_draft['mask_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $public_draft['anniversary_date'] = $public_draft['anniversary_date']?Carbon::createFromFormat("Y-m-d",$public_draft['anniversary_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;

            if ($public_draft){
                return view('tis.public_draft.edit',['public_draft'=>$public_draft]);
            }
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
    public function update(Request $request, $token)
    {
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
                'public_draft_type' => 'required',
                'set_format_id' => 'required',
                'tis_no' => 'required',
                'mask_date' => 'required',
                'anniversary_date' => 'required'
            ]);
            $public_draft = PublicDraft::whereToken($token)->first();
            if ($public_draft){
                $public_draft->public_draft_type = $request->public_draft_type;
                $public_draft->set_format_id = $request->set_format_id;
                $public_draft->tis_no = $request->tis_no;
                $public_draft->set_standard_id = $request->set_standard_id;
                $public_draft->product_group_id = $request->product_group_id;
                $public_draft->title = $request->title;
                $public_draft->number_book = $request->number_book;
                $public_draft->mask_date=$request->mask_date?Carbon::createFromFormat("d/m/Y",$request->mask_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
                $public_draft->anniversary_date =$request->anniversary_date?Carbon::createFromFormat("d/m/Y",$request->anniversary_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
                $public_draft->lock_qr = $request->lock_qr;
                $public_draft->basic_staff_groups_id = $request->staff_group;
                $public_draft->result_draft = $request->result_draft;
                $public_draft->updated_by = Auth::user()->getKey();
                if ($request->result_draft){
//                    if ($public_draft->public_draft_type == 1 && $public_draft->result_draft == 2){
                    if ($public_draft->public_draft_type == 1){
                        Standard::where('id', $public_draft->set_standard_id)->update(['review_status'=>$public_draft->result_draft]); // 1 เดิม ,2 ทบทวน
                    }
//                    elseif ($public_draft->public_draft_type == 0){
//                        SetStandard::where('tis_no', $public_draft->tis_no)->update(['review_status'=>$public_draft->result_draft]);
//                    }
                }
                try{
                    $public_draft->save();
                }catch (\Exception $x){echo 'เกิดปัญหาในการบันทึก';}
                if ($request->attach_files) {
                    $numberCountMore = 0;
                    foreach ($request->attach_files as $file){
                        if ($file){
                            $path = $this->storeFile($file,$request->attach_name[$numberCountMore],$this->attach_path);
                            try{
                                $name = $request->attach_name[$numberCountMore];
                            }catch (\Exception $x){
                                $name = null;
                            }
                            DB::table('tis_public_draft_attaches')->insert([
                                'public_draft_id'=>$public_draft->id,
                                'file_name'=>$name,
                                'file_path'=>$path,
                                'token'=>str_random(20),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                        }
                        $numberCountMore ++;
                    }
                }
            }
            return redirect('tis/public_draft')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('delete-'.$model)) {
            $requestData = $request->all();
            if ($id == 'all'){
                if(array_key_exists('cb', $requestData)){
                    $ids = $requestData['cb'];
                    try{
                        PublicDraft::whereIn('token', $ids)->each(function ($item){
                            $item->delete();
                        });
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }else{
                $draft = PublicDraft::whereToken($id)->first(); // use as token
                if ($draft){
                    try{
                        $draft->delete();
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }
            return redirect('tis/public_draft')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function createDate($dateString)
    {
        if ($dateString){
            try{
                $date = Carbon::createFromFormat('d/m/Y',$dateString) ?? null;
            }catch (\Exception $x){
                $date = Carbon::createFromFormat('d-m-Y',$dateString) ?? null;
            }
        }else{
            $date = null;
        }
        return $date;
    }

    // สำหรับไปที่ store
    public function storeFile($files, $name = null,$path)
    {

        if ($files) {
            $file_extension = $files->getClientOriginalExtension();
            if(in_array($file_extension,['docx','doc'])){
                $storagePath = Storage::putFile($path,$files);
            }else{
                $storagePath = Storage::put($path, $files);
            }   
            $storageName = basename($storagePath); // Extract the filename
            return  $no.'/'.$storageName;
        }else{
            return null;
        }
        // if ($path && $files){
        //     $destinationPath = storage_path($path);
        //     $fileClientOriginal = $files->getClientOriginalName();
        //     $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
        //     $fullFileName = ($name ?? $filename).'-'.str_random(2).time() . '.' . $files->getClientOriginalExtension();
        //     $files->move($destinationPath, $fullFileName);
        //     $file_certificate_toDB = $path . $fullFileName;
        //     return $file_certificate_toDB;
        // }
        // return null;
    }


    /*
      **** Update Status ****
    */
    public function update_status(Request $request){
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $requestData = $request->all();
            if(array_key_exists('cb', $requestData)){
                $ids = $requestData['cb'];
                try{
                    PublicDraft::whereIn('token', $ids)->update(['status' => $requestData['status']]);
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
            }
            return redirect('tis/public_draft')->with('flash_message', 'แก้ไขสถานะเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function removeFiles($path){
        try{
            $file = storage_path().$this->attach_path.$path;
            if (!File::exists($file)) {
                return Response::make("File does not exist.", 404);
            }
            if(is_file($file)){
                File::delete($file);
            }else {
                echo "File does not exist";
            }
            return true;
        }catch (\Exception $x){
            return false;
        }
    }

    public function removeFilesWithMessage($path,$token){
        $model = str_slug('public_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $obj = DB::table('tis_public_draft_attaches')->where('token',$token)->delete();
            try{
                $file = storage_path().$this->attach_path.$path;
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

    public function getFormatApi()
    {
        $form_data = array();
        $val_type = $_POST['val_type'];
        $format = null;
        if ($val_type == 0){ // เวียนร่าง
            $format = SetFormat::whereIn('id',[1,2])->get();

        }elseif ($val_type == 1){ // เวียนทบทวน
            $format = SetFormat::where('id',2)->get();
        }
        if ($format->count() > 0){
            $form_data['format'] = $format;
            $form_data['status'] = true;
        }else{
            $form_data['status'] = false;
        }

        return response()->json([
            'status'=>$form_data['status'],
            'format'=>$form_data['format']
        ]);
    }

    public function getNumberFormula()
    {
        $form_data = array();
        $val_type = $_POST['val_type'];
//        $standard_type = $_POST['standard_type'];
        $number_formula = null;
//        if ($val_type == 1 && $standard_type == 2){ //ดึงจาก D112 // ทบทวน // เวียนทบทวน
        if ($val_type == 1){ //ดึงจาก D112 // ทบทวน // เวียนทบทวน
            $number_formula = Standard::where('state', 1)->get();
        }else{ // ดึงจาก D115 เวียนร่าง
            $number_formula = SetStandard::where('state', 1)->get();
        }

        if ($number_formula->count() > 0){
            $form_data['number_formula'] = $number_formula;
            $form_data['status'] = true;
        }else{
            $form_data['status'] = false;
        }

        return response()->json([
            'status'=>$form_data['status'],
            'number_formula'=>$form_data['number_formula']
        ]);
    }

    public function standardName_branch()
    {
        $form_data = array();
        $val_type = $_POST['val_type'];
        $tis_no = $_POST['tis_no'];

        $name_branch = null;
        $product_group = null;
        $staff = null;
        if ($val_type == 0){ // ดึงจาก D115
            $name_branch = SetStandard::where('id',$tis_no)->where('state', 1)->first();
            if ($name_branch){
                $product_group = $name_branch->product_group;
                if ($product_group){
                    $staff = $product_group->getStaff_ProductGroup->getStaffGroup; // return StaffGroup
                }
            }
        }
        if ($val_type == 1){ //ดึงจาก D112
            $name_branch = Standard::where('id',$tis_no)->where('state', 1)->first();
            if ($name_branch){
                $product_group = $name_branch->product_group;
                if ($product_group){
                    $staff = $product_group->getStaff_ProductGroup->getStaffGroup; // return StaffGroup
                }
            }
        }

        if ($name_branch->count() > 0){
            $form_data['name_branch'] = $name_branch;
            $form_data['product_group'] = $product_group;
            $form_data['staff_group'] = $staff;
            $form_data['status'] = true;
        }else{
            $form_data['status'] = false;
        }

        return response()->json([
            'status'=>$form_data['status'],
            'name_branch'=>$form_data['name_branch'],
            'product_group' => $form_data['product_group'],
            'staff_group' => $form_data['staff_group']
        ]);
    }


       public function apiGetStandards() {
        $set_standard_id = PublicDraft::where('result_draft',2)->pluck('set_standard_id');
        $standards = Standard::whereIn('id',$set_standard_id)->get();
        foreach ($standards as $standard) {
            $attachs = json_decode($standard['attach']);
            if (!is_null($attachs)&&count($attachs)>0) {
                foreach ($attachs as $attach) {
                    $attach->check = HP::checkFileStorage($this->attach_path.$attach->file_name);
                    $attach->href = HP::getFileStorage($this->attach_path.$attach->file_name);
                }
            } else {
                $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            }
            $standard->attaches = $attachs;
            $standard->refers = json_decode($standard['refer']);
        }
        return response()->json(compact('standards'));
    }

}
