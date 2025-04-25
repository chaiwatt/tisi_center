<?php

namespace App\Http\Controllers\Tis;

use App\Models\Basic\Department;
use App\Models\Basic\SetFormat;
use App\Models\Tis\PublicDraft;
use App\Models\Tis\SetStandard;
use App\Models\Tis\Standard;
use App\Models\Tis\NoteStdDraft;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Storage;
use HP;
use SHP;
use App\Models\Tis\ListenStdDraft;

class NoteStdDraftController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');

        $this->attach_path = '/files/note_std_draft/';
        $this->attach_std_path = 'tis_attach/standard/';
        $this->attach_set_std_path = 'tis_attach/set_standard/';

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['perPage'] = $request->get('perPage', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status_publish'] = $request->get('filter_status_publish', '');
            $filter['filter_result_draft'] = $request->get('filter_result_draft', '');

            $Query = new NoteStdDraft();

            if ($filter['filter_search'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_search'];
                                    $query->where('tis_no', 'LIKE', "%{$search_text}%")
                                    ->orWhere('title', 'LIKE', "%{$search_text}%");
                         });
            }

            if ($filter['filter_status_publish']!='') {
                if($filter['filter_status_publish'] == 1){
                    $Query = $Query->where('status_publish',1);
                }else{
                    $Query = $Query->where('status_publish','!=',1);
                }
            }

            if ($filter['filter_result_draft']!='' && $filter['filter_result_draft']!='w') {

                $Query = $Query->where('result_draft',$filter['filter_result_draft']);
            } else if ($filter['filter_result_draft']=='w'){
                // dd($filter['filter_result_draft']);
                $Query = $Query->whereNull('result_draft');
            }

            $note_std_drafts = $Query->paginate($filter['perPage']);

            return view('tis.note_std_draft.index', compact('filter', 'note_std_drafts'));
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
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('tis.note_std_draft.create');
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
        $model = str_slug('note_std_draft','-');
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
        $model = str_slug('note_std_draft','-');
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
    public function edit($id)
    {
        $model = str_slug('note_std_draft','-');
        if(auth()->user() || Auth::guest()) {

            $note_std_draft = NoteStdDraft::findOrFail($id);

            if($note_std_draft['result_draft']=='2'){

              $note_std_draft['minis_dated_compulsory'] = $note_std_draft['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
              $note_std_draft['issue_date_compulsory'] = $note_std_draft['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
              $note_std_draft['gaz_date_compulsory'] = $note_std_draft['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;

            }
           $arr = [
                    'confirm_standard'=>'ยืนยันตามมาตรฐานดังกล่าว',
                    'revise_standard'=>'เห็นควรแก้ไขปรับปรุงมาตรฐานดังกล่าว',
                    'cancel_standard'=>'ยกเลิกมาตรฐานดังกล่าว',
                    'no_comment'=>'ไม่มีข้อคิดเห็น'
                 ];
                 $comments = [];
             foreach($arr as $key => $item){
                $data = (object)[];
                $data->title =$item;
                $data->number = ListenStdDraft::where('note_std_draft_id',$id)->where('comment',$key)->get()->count();
                $comments[] = $data;
             }

            $note_std_draft->start_date = !empty($note_std_draft->start_date)?   HP::revertDate($note_std_draft->start_date,true) : null;
            $note_std_draft->end_date =  !empty($note_std_draft->end_date)?   HP::revertDate($note_std_draft->end_date,true) : null;
            $note_std_draft->comments = $comments;
     
            // echo "120"; exit;

            // $standard = Standard::where('id', $note_std_draft->standard_id)->first();
            // $set_standard = SetStandard::where('standard_id', $note_std_draft->standard_id)->first();

            // if($standard->government_gazette=='y'){ //มาตรฐานที่ประกาศราชกิจจาแล้ว
            //     $attachs = json_decode($standard['attach']);
            //     foreach($attachs as $key=>$item){

            //             $attachs[$key] = ['file_name' => $item->file_name,
            //                         'file_client_name' => $item->file_client_name,
            //                         'file_note' => $item->file_note,
            //                         'file_checkbox' => 'n'
            //                         ];
            //     }
            //     $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'']];
            // } else {
            //     $standard_attachs = json_decode($standard['attach']);
            //     $set_standard_attachs = json_decode($set_standard['attach']);
            //     $attachs = array_merge($standard_attachs, $set_standard_attachs);
            //       foreach($attachs as $key=>$item){


            //             $attachs[$key] = ['file_name' => $item->file_name,
            //                         'file_client_name' => $item->file_client_name,
            //                         'file_note' => $item->file_note,
            //                         'file_checkbox' => 'n'
            //                         ];
            //     }
            //     $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'']];
            // }

            $attach_std_path = $this->attach_std_path;
            $attach_set_std_path = $this->attach_set_std_path;
            $attachs = json_decode($note_std_draft['attach']);
           
                        //ไฟล์แนบ
            $attach_note = json_decode($note_std_draft->attach_note, true);
            // dd($attachs);
            $note_std_draft->attach_note = !is_null($attach_note)&&count($attach_note)>0?$attach_note:[['file_name'=>'', 'file_client_name'=>'', 'file_note'=> null]];
            $attach_path = $this->attach_path;
 
            // dd($attachs);

            return view('tis/note_std_draft/edit', compact('note_std_draft', 'attachs', 'attach_std_path', 'attach_set_std_path','attach_path'));
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
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required'
        		]);
                
            $note_std_draft = NoteStdDraft::findOrFail($id);
            $requestData = $request->all(); 
            
            $requestData['start_date'] = isset($request->start_date)?Carbon::createFromFormat("d/m/Y",$request->start_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['end_date'] =  isset($request->end_date)?Carbon::createFromFormat("d/m/Y",$request->end_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
         
            $requestData['status_publish'] =  isset($request->status_publish)? $request->status_publish :0;
            if($requestData['result_draft']=='2'){

                $requestData['minis_dated_compulsory'] = $request->minis_dated_compulsory?Carbon::createFromFormat("d/m/Y",$request->minis_dated_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
                $requestData['issue_date_compulsory'] = $request->issue_date_compulsory?Carbon::createFromFormat("d/m/Y",$request->issue_date_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
                $requestData['gaz_date_compulsory'] = $request->gaz_date_compulsory?Carbon::createFromFormat("d/m/Y",$request->gaz_date_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
               
                $requestDataSTD['standard_format_id'] = 2;
                $requestDataSTD['minis_dated_compulsory'] =   $requestData['minis_dated_compulsory'];
                $requestDataSTD['issue_date_compulsory']=   $requestData['issue_date_compulsory'];
                $requestDataSTD['amount_date_compulsory'] = $requestData['amount_date_compulsory'];
                $requestDataSTD['gaz_date_compulsory'] =   $requestData['gaz_date_compulsory'];
                $requestDataSTD['gaz_no_compulsory'] = $requestData['gaz_no_compulsory'];
                $requestDataSTD['gaz_space_compulsory'] = $requestData['gaz_space_compulsory'];

                $requestDataSTD['announce_compulsory'] =  'y';

                Standard::where('id', $note_std_draft->standard_id)->update( $requestDataSTD );

            } else {

                $requestData['minis_dated_compulsory'] = null;
                $requestData['issue_date_compulsory'] = null;
                $requestData['gaz_date_compulsory'] = null;

            }

            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['updated_by'] = auth()->user()->getKey();//user update



            // if($request->file_checkbox_selected){
            //     foreach($request->file_name_selected as $key=>$item){
            //           $attachs[] = ['file_name' => $item,
            //                         'file_client_name' => $requestData['file_client_name_selected'][$key],
            //                         'file_note' => $requestData['file_note_selected'][$key]
            //                         ];

            //         if (array_key_exists($key,$request->file_checkbox_selected)){
            //             $attachs[] =  ['file_checkbox' => 'y'];
            //         } else {
            //             $attachs[] =  ['file_checkbox' => 'n'];
            //         }

            //     }
            // } else {
            //    foreach($request->file_name_selected as $key=>$item){
            //           $attachs[] = ['file_name' => $item,
            //                         'file_client_name' => $requestData['file_client_name_selected'][$key],
            //                         'file_note' => $requestData['file_note_selected'][$key],
            //                         'file_checkbox' => 'n'
            //                         ];

            //     }
            // }

             if($request->file_checkbox_selected){
                foreach($request->file_name_selected as $key=>$item){
                      $attachs[$key] = ['file_name' => $item,
                                    'file_client_name' => $requestData['file_client_name_selected'][$key],
                                    'file_note' => $requestData['file_note_selected'][$key],
                                    'file_checkbox' => array_key_exists($key,$request->file_checkbox_selected)?'y':'n',
                                    'file_from' => $requestData['file_from_selected'][$key]
                                    ];
                }
            } else {
                  foreach($request->file_name_selected as $key=>$item){
                      $attachs[$key] = ['file_name' => $item,
                                    'file_client_name' => $requestData['file_client_name_selected'][$key],
                                    'file_note' => $requestData['file_note_selected'][$key],
                                    'file_checkbox' => 'n',
                                    'file_from' => $requestData['file_from_selected'][$key]
                                    ];
                }
            }


            //   dd($requestData);

            $requestData['attach'] = json_encode($attachs, JSON_UNESCAPED_UNICODE);

            //ข้อมูลไฟล์แนบ
            $attach_notes = array_values((array)json_decode($note_std_draft->attach_note));
                //ไฟล์แนบ ที่ถูกกดลบ
                foreach ($attach_notes as $key => $attach) {
                    if(in_array($attach->file_name, $requestData['attach_filenames'])===false){//ถ้าไม่มีไฟล์เดิมกลับมา
                    unset($attach_notes[$key]);
                    Storage::delete($this->attach_path.$attach->file_name);
                    }
                }
            //ไฟล์แนบ เพิ่มเติม
            if ($files = $request->file('attach_notes')) {


                foreach ($files as $key => $file) {

                    //Upload File
                    $storagePath = Storage::put($this->attach_path, $file);
                    $newFile = basename($storagePath); // Extract the filename

                    if($requestData['attach_filenames'][$key]!=''){//ถ้าเป็นแถวเดิมที่มีในฐานข้อมูลอยู่แล้ว
                        //วนลูปค้นหาไฟล์เดิม
                        foreach ($attach_notes as $key2 => $attach) {

                            if($attach->file_name == $requestData['attach_filenames'][$key]){//ถ้าเจอแถวที่ตรงกันแล้ว

                            Storage::delete($this->attach_path.$attach->file_name);//ลบไฟล์เก่า

                            $attach->file_name = $newFile;//แก้ไขชื่อไฟล์ใน object
                            $attach->file_client_name = $file->getClientOriginalName();//แก้ไขชื่อไฟล์ของผู้ใช้ใน object
                            break;
                            }
                        }

                    }else{//แถวที่เพิ่มมาใหม่

                        $attach_notes[] = ['file_name'=>$newFile,
                                           'file_client_name'=>$file->getClientOriginalName(),
                                           'file_note'=>$requestData['attach_text'][$key]
                                    ];
                    }

                }
            }
            $requestData['attach_note'] = json_encode(array_values($attach_notes), JSON_UNESCAPED_UNICODE);

            $note_std_draft->update($requestData);

            return redirect('tis/note_std_draft')->with('flash_message', 'แก้ไข note_std_draft เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function update_backup(Request $request, $id)
    {
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required'
        		]);

            $note_std_draft = NoteStdDraft::findOrFail($id);
            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update

            if($request->file_checkbox_selected){
                foreach($request->file_name_selected as $key=>$item){
                      $attachs[] = ['file_name' => $item,
                                    'file_client_name' => $requestData['file_client_name_selected'][$key],
                                    'file_note' => $requestData['file_note_selected'][$key]
                                    ];

                    if (array_key_exists($key,$request->file_checkbox_selected)){
                        $attachs[] =  ['file_checkbox' => 'y'];
                    } else {
                        $attachs[] =  ['file_checkbox' => 'n'];
                    }

                }
            } else {
               foreach($request->file_name_selected as $key=>$item){
                      $attachs[] = ['file_name' => $item,
                                    'file_client_name' => $requestData['file_client_name_selected'][$key],
                                    'file_note' => $requestData['file_note_selected'][$key],
                                    'file_checkbox' => 'n'
                                    ];

                }
            }

            $requestData['attach'] = json_encode($attachs, JSON_UNESCAPED_UNICODE);

            // dd($requestData);

            $note_std_draft->update($requestData);

            return redirect('tis/note_std_draft')->with('flash_message', 'แก้ไข note_std_draft เรียบร้อยแล้ว!');
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
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('delete-'.$model)) {
            $requestData = $request->all();
            if ($id == 'all'){
                if(array_key_exists('cb', $requestData)){
                    $ids = $requestData['cb'];
                    try{
                        NoteStdDraft::whereIn('id', $ids)->each(function ($item){
                            $item->delete();
                        });
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }else{
                $draft = NoteStdDraft::where('id', $id)->first(); // use as token
                if ($draft){
                    try{
                        $draft->delete();
                    }catch (\Exception $x){
                        echo "เกิดข้อผิดพลาด";
                    }
                }
            }
            return redirect('tis/note_std_draft')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
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
        if ($path && $files){
            $destinationPath = storage_path($path);
            $fileClientOriginal = $files->getClientOriginalName();
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName = ($name ?? $filename).'-'.str_random(2).time() . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $fullFileName);
            $file_certificate_toDB = $path . $fullFileName;
            return $file_certificate_toDB;
        }
        return null;
    }


    /*
      **** Update Status ****
    */
    public function update_status(Request $request){
        $model = str_slug('note_std_draft','-');
        if(auth()->user()->can('edit-'.$model)) {
            $requestData = $request->all();
            if(array_key_exists('cb', $requestData)){
                $ids = $requestData['cb'];
                try{
                    NoteStdDraft::whereIn('id', $ids)->update(['state' => $requestData['state']]);
                }catch (\Exception $x){
                    echo "เกิดข้อผิดพลาด";
                }
            }
            return redirect('tis/note_std_draft')->with('flash_message', 'แก้ไขสถานะเรียบร้อยแล้ว!');
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

    public function form($id)
    {
        $model = str_slug('standard','-');
        if(auth()->user() || Auth::guest()) {

            $note_std_draft = Standard::where('id', $id)->first();
            $set_standard = SetStandard::where('standard_id', $id)->first();
            $note_std_draft['tis_no'] = $note_std_draft->tis_no.(!empty($note_std_draft->tis_book)?' เล่ม '.($note_std_draft->tis_book):''). "-".$note_std_draft->tis_year;

            if($note_std_draft->government_gazette=='y'){ //มาตรฐานที่ประกาศราชกิจจาแล้ว
                $attachs = json_decode($note_std_draft['attach']);
                $attachs = !is_null($attachs)&&count($attachs)>0?$attachs[] = ['file_checkbox' => 'n']:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'']];
            } else {
                $standard_attachs = json_decode($note_std_draft['attach']);
                $set_standard_attachs = json_decode($set_standard['attach']);
                $attachs = array_merge($standard_attachs, $set_standard_attachs);
                $attachs = !is_null($attachs)&&count($attachs)>0?$attachs[] = ['file_checkbox' => 'n']:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'']];
            }

            $attach_path = $this->attach_path;

            return view('tis/note_std_draft/edit', compact('note_std_draft', 'attachs', 'attach_path'));
        }
        abort(403);
    }

    public function save_note_std_draft(Request $request)
    {
        $std_id = $request->get('std_id');
        $government_gazette = $request->get('government_gazette');

        $requestData = $request->all();


        $have_note = NoteStdDraft::where('standard_id', $std_id)->first();

        if( !is_null($have_note) ){
            return response()->json([
            'status' => 'already_have',
            'message_data' => "พบมาตรฐาน ".$have_note->tis_no." ".$have_note->title." ในระบบเวียนร่างและประกาศรับฟังความคิดเห็นร่างกฎกระทรวงแล้ว"
            ]);
        } else {

            $standard = Standard::where('id', $std_id)->first();
            $set_standard = SetStandard::where('id', $standard->set_std_id)->first();
            $tis_no = $standard->tis_no.(!empty($standard->tis_book)?' เล่ม '.($standard->tis_book):''). "-".$standard->tis_year;

                if( $standard->government_gazette == 'y' ){ //มาตรฐานที่ประกาศราชกิจจาแล้ว
                    $attachs = json_decode($standard['attach']);

                        if (!is_null($attachs)&&count($attachs)>0) {
                            foreach($attachs as $key=>$item){
                                $attachs[$key] = [
                                    'file_name' => $item->file_name,
                                    'file_client_name' => $item->file_client_name,
                                    'file_note' => $item->file_note,
                                    'file_checkbox' => 'n',
                                    'file_from' => 'standard'
                                ];
                            }
                        } else {
                            $attachs = [(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'n', 'file_from' => 'standard']];
                        }

                } else {

                    $standard_attachs = json_decode($standard['attach']);

                   
                    if (!is_null($standard_attachs)&&count($standard_attachs)>0) {
                        foreach($standard_attachs as $key=>$item){
                            $standard_attachs[$key] = [
                                'file_name' => $item->file_name,
                                'file_client_name' => $item->file_client_name,
                                'file_note' => $item->file_note,
                                'file_checkbox' => 'n',
                                'file_from' => 'standard'
                            ];
                        }
                    } else {
                        $standard_attachs = [(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'n', 'file_from'=>'standard']];
                    }
                   
                    if( !is_null($set_standard) && !empty($set_standard['attach']) ){
                        $set_standard_attachs = json_decode($set_standard['attach']);
                        if (!is_null($set_standard_attachs)&&count($set_standard_attachs)>0) {
                            foreach($set_standard_attachs as $key=>$item){
                                $set_standard_attachs[$key] = [
                                    'file_name' => $item->file_name,
                                    'file_client_name' => $item->file_client_name,
                                    'file_note' => $item->file_note,
                                    'file_checkbox' => 'n',
                                    'file_from' => 'set_standard'
                                ];
                            }
                        } else {
                            $set_standard_attachs = [(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'n', 'file_from'=>'set_standard']];
                        }
                    }else{
                        $set_standard_attachs = [(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'', 'file_checkbox'=>'n', 'file_from'=>'set_standard']];
                    }

                    $attachs = array_merge($standard_attachs, $set_standard_attachs);
                }

            $note_std_draft = [];
            $note_std_draft['standard_id'] = $std_id;
            $note_std_draft['tis_no'] = $tis_no;
            $note_std_draft['title'] = $standard->title;
            $note_std_draft['title_en'] = $standard->title_en;
            $note_std_draft['attach'] = json_encode($attachs, JSON_UNESCAPED_UNICODE);
            $note_std_draft['state'] = 0;
            $note_std_draft['created_by'] = auth()->user()->getKey(); //user create;

            $result = NoteStdDraft::create($note_std_draft);

            if($result){
                return response()->json([
                'status' => 'success',
                'message_data' => "เพิ่มข้อมูลมาตรฐาน ".$tis_no." ".$standard->title." เรียบร้อยแล้ว"
                ]);
            } else {
                return response()->json([
                'status' => 'error'
                ]);
            }

        }

    }

}
