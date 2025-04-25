<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\AttachFile;

use App\Models\Certify\Standard;
use App\Models\Certify\StandardIcs;
use App\Models\Certify\StandardSendmail;
use App\Models\Certify\GazetteStandard;
use App\Models\Certify\Gazette;
use App\Models\Besurv\Signer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

use App\Mail\CertifyStandardIsbn;
use Illuminate\Support\Facades\Mail;

use Yajra\Datatables\Datatables;
use HP;
use DB; 
use stdClass;

class StandardController extends Controller
{
    
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/standard/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.standards.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

        $model = str_slug('certifystandard', '-');
        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');
        $filter_status = $request->input('filter_status');

        $query = Standard::query()->when($not_admin, function ($query){
                                            return $query->where(function($query){
                                                return $query->where('created_by', auth()->user()->getKey())
                                                            ->orWhereHas('certify_standard_sendmail', function($query){
                                                                return $query->where('user_by', auth()->user()->getKey());
                                                            });
                                            });
                                    })
                                    ->when($filter_search, function ($query, $filter_search) {
                                        $search_full = str_replace(' ', '', $filter_search);
                                        $query->where(function ($query2) use ($search_full) {
                                            $query2->Where(DB::raw("REPLACE(std_no,' ','')"), 'LIKE', "%" . $search_full . "%")
                                                ->OrWhere(DB::raw("REPLACE(std_title,' ','')"), 'LIKE', "%" . $search_full . "%");
                                        });
                                    })
                                    ->when($filter_state, function ($query, $filter_state) {
                                        $query->where('publish_state', $filter_state);
                                    })  
                                     ->when($filter_status, function ($query, $filter_status) {
                                        $query->where('status_id', $filter_status);
                                    });


        return Datatables::of($query)->addIndexColumn()
                                        ->addColumn('checkbox', function ($item) {
                                            return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="' . $item->id . '">';
                                        })
                                        ->addColumn('set_standard_id', function ($item) {
                                            return   !empty($item->standard_type->title) ? $item->standard_type->title : '';
                                        })
                                        ->addColumn('std_no', function ($item) {
                                            return   !empty($item->std_no) ? $item->std_no : '';
                                        })
                                        ->addColumn('std_title', function ($item) {
                                            return   !empty($item->std_title) ? $item->std_title : '';
                                        })
                                        ->addColumn('isbn_no', function ($item) use($not_admin){
                                            $btn = '';
                                            $standard_sendmail = StandardSendmail::where('std_id',$item->id)->pluck('user_by')->toArray();

                                            if ((count($standard_sendmail) > 0 && in_array(auth()->user()->getKey(),$standard_sendmail )) || !$not_admin) {//เห็นเฉพาะคนที่เลือก

                                                if ($item->status_id >= 5 && !empty($item->isbn_no)) {
                                                    $btn = '<a class="btn_edit_isbn" data-id="'.($item->id).'"title="'.'ผู้บันทึก:'. (!empty($item->CreatedName) ? $item->CreatedName : '').' เวลา:'.(!empty($item->updated_at) ? HP::DateTimeThaiAndTime($item->updated_at) : '').'"> '.$item->isbn_no.'</a> ';
                                                }else if ($item->status_id == 5) {
                                                    $btn = '<button type="button" class="btn btn-warning btn-xs waves-effect waves-light btn_edit_isbn" data-id="'.($item->id).'" title="'.'ผู้บันทึก:'.(!empty($item->CreatedName) ? $item->CreatedName : '').' เวลา:'.(!empty($item->updated_at) ? HP::DateTimeThaiAndTime($item->updated_at) : '').'"> <i class="fa fa-pencil-square-o"></i></button> ';
                                                }else if ($item->status_id == 4) {
                                                    $btn = '<button type="button" class="btn btn-light btn-xs waves-effect waves-light" data-id="'.($item->id).'"> <i class="fa fa-pencil-square-o  text-white"></i></button> ';
                                                }
                                              
                                            }
                                            return $btn;
                                        })
                                        ->addColumn('status_id', function ($item) {
                                            return   !empty($item->SetStandardStatus) ? $item->SetStandardStatus : '';
                                        })
                                        ->addColumn('publish_state', function ($item) {
                                            return   !empty($item->PublishStatus) ? $item->PublishStatus : '';
                                        })
                                        ->addColumn('action', function ($item) use($model) {
                                            return HP::buttonAction($item->id, 'certify/standards', 'Certify\\StandardController@destroy', 'certifystandard', true, true,  (auth()->user()->can('delete-'.$model)?true:false) );
                                        })
                                        ->order(function ($query) {
                                            $query->orderBy('id', 'DESC');
                                        })
                                        ->rawColumns(['checkbox', 'status', 'action','isbn_no'])
                                        ->make(true);
                                } 


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify/standards.create');
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
        $model = str_slug('certifystandard', '-');

        if (auth()->user()->can('add-' . $model)) {

            try {
                if ($request->submit == "submit") {

                    $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
                    $requestData = $request->all();

                    $standard_full = '';
                    $standard_full .= !empty($requestData['std_no']) ? $requestData['std_no'] : null;
                    $standard_full .= !empty($requestData['std_book'])  ?' เล่ม '.$requestData['std_book'] : null;
                    $standard_full .= !empty($requestData['std_year'])  ?'-'. $requestData['std_year'] : null;
                    $requestData['std_full']                 =   $standard_full;

                    $requestData['std_sign_date']            =   !empty($requestData['std_sign_date']) ? HP::convertDate($requestData['std_sign_date'], true) : null;
                    $requestData['gazette_post_date']        =   !empty($requestData['gazette_post_date']) ? HP::convertDate($requestData['gazette_post_date'], true) : null;
                    $requestData['gazette_effective_date']   =   !empty($requestData['gazette_effective_date']) ? HP::convertDate($requestData['gazette_effective_date'], true) : null;
                    $requestData['revoke_date']              =   !empty($requestData['revoke_date']) ? HP::convertDate($requestData['revoke_date'], true) : null;
                    $requestData['std_price']                =   !empty(str_replace(",","", $requestData['std_price']))?str_replace(",","",$requestData['std_price']):null;
                    $requestData['gazette_state']            =   !empty($requestData['gazette_state'] )?1:null;

                    //ไฟล์การลงนามการจัดทำมาตรฐาน
                    if ($request->hasFile('std_file')) {
                        $attach = $request->file('std_file');
                        $requestData['std_file']  = $this->storeFile($attach, $requestData['std_no']);
                    }

                    //ไฟล์ประกาศการยกเลิก
                    if ($request->hasFile('revoke_file')) {
                        $attach = $request->file('revoke_file');
                        $requestData['revoke_file']  = $this->storeFile($attach, $requestData['revoke_file']);
                    }

          
                    $standard = Standard::create($requestData);
                    $ics_id   = !empty($requestData['ics'])?$requestData['ics']:[];
                    $user_by  = !empty($requestData['user_by'])?$requestData['user_by']:[];

                    if(!empty($ics_id) && count($ics_id) > 0){
                        $this->save_ics($standard, $ics_id);

                    }

                    if((!empty($user_by) && count($user_by) > 0) && $request->input('status_id') == 5){
                        $this->save_sendmail($standard, $user_by);
                        $this->sendmail($standard_full, $user_by);
    
                    }
        
                    return redirect('certify/standards')->with('flash_message', 'เพิ่ม Certify/Standard เรียบร้อยแล้ว');
                } else {
                    return  $this->cover_pdf($request);
                }
            } catch (\Exception $e) {
                return redirect('certify/standards')->with('flash_message', 'เกิดข้อผิดพลาดในการบันทึก');
            }
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
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('view-'.$model)) {
            $standard = Standard::findOrFail($id);
            $standard->std_sign_date       =  !empty($standard->std_sign_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->std_sign_date)) ,true) : '';   
            $standard->gazette_post_date   =  !empty($standard->gazette_post_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->gazette_post_date)) ,true) : '';   
            $standard->gazette_effective_date   =  !empty($standard->gazette_effective_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->gazette_effective_date)) ,true) : '';   
            $standard->revoke_date   =  !empty($standard->revoke_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->revoke_date)) ,true) : '';   

            return view('certify/standards.show', compact('standard'));
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
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('edit-'.$model)) {
            $standard = Standard::findOrFail($id);
            
            $standard_ics       = StandardIcs::where('std_id',$standard->id)->pluck('ics_id');
            $standard_sendmail  = StandardSendmail::where('std_id',$standard->id)->pluck('user_by');

            //ระบบประกาศราชกิจจานุเบกษา
            $gazette_standard   = GazetteStandard::where('standard_id',$id)->first();
            if(!empty($gazette_standard)){
            $gazette            = Gazette::where('id',$gazette_standard->gazette_id)->first();
            $file_gazette       = AttachFile::where('ref_table', (new Gazette )->getTable() )->where('ref_id', $gazette->id)->where('section', 'file_gazette')->first();
            }

            $standard->std_sign_date            =  !empty($standard->std_sign_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->std_sign_date)) ,true) : '';   
            $standard->gazette_post_date        =  !empty($standard->gazette_post_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->gazette_post_date)) ,true) : '';   
            $standard->gazette_effective_date   =  !empty($standard->gazette_effective_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->gazette_effective_date)) ,true) : '';   
            $standard->revoke_date              =  !empty($standard->revoke_date) ? HP::revertDate(date('Y-m-d', strtotime($standard->revoke_date)) ,true) : '';   
            $standard->std_price                =  !empty($standard->std_price )? number_format($standard->std_price,2):null; 
            $standard->isbn_by                  =  !empty($standard->isbn_no) ?$standard->UpdatedName:null; 

            //ระบบประกาศราชกิจจานุเบกษา 
            $standard->gazette_state            =  !empty($gazette) ?1:$standard->gazette_state; 
            $standard->gazette_book             =  !empty($gazette->gazette_book) ?$gazette->gazette_book:null; 
            $standard->gazette_no               =  !empty($gazette->gazette_no) ?$gazette->gazette_no:null; 
            $standard->gazette_section          =  !empty($gazette->gazette_space) ?$gazette->gazette_space:null; 
            $standard->gazette_post_date        =  !empty($gazette->gazette_date) ? HP::revertDate(date('Y-m-d', strtotime($gazette->gazette_date)) ,true) : ''; 
            $standard->gazette_effective_date   =  !empty($gazette->enforce_date) ? HP::revertDate(date('Y-m-d', strtotime($gazette->enforce_date)) ,true) : ''; 
            $standard->gazette_file             =  !empty($file_gazette->url) ? $file_gazette->url:null; 


            return view('certify/standards.edit', compact('standard','standard_ics','standard_sendmail'));
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
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('edit-'.$model)) {
            try {
                if($request->submit == "submit"){
                
                    $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
                    $requestData = $request->all();

                    $standard_full = '';
                    $standard_full .= !empty($requestData['std_no']) ? $requestData['std_no'] : null;
                    $standard_full .= !empty($requestData['std_book'])  ?' เล่ม '.$requestData['std_book'] : null;
                    $standard_full .= !empty($requestData['std_year'])  ?'-'. $requestData['std_year'] : null;

                    $requestData['std_full']                 =   $standard_full;
                    $requestData['std_sign_date']            =   !empty($requestData['std_sign_date']) ? HP::convertDate($requestData['std_sign_date'],true) : null;  
                    $requestData['gazette_post_date']        =   !empty($requestData['gazette_post_date']) ? HP::convertDate($requestData['gazette_post_date'],true) : null;  
                    $requestData['gazette_effective_date']   =   !empty($requestData['gazette_effective_date']) ? HP::convertDate($requestData['gazette_effective_date'],true) : null;  
                    $requestData['revoke_date']              =   !empty($requestData['revoke_date']) ? HP::convertDate($requestData['revoke_date'],true) : null;
                    $requestData['std_price']                =   !empty(str_replace(",","", $requestData['std_price']))?str_replace(",","",$requestData['std_price']):null;
                    $requestData['gazette_state']            =   !empty($requestData['gazette_state'] )?1:null;

                    //ไฟล์การลงนามการจัดทำมาตรฐาน
                    if ($request->hasFile('std_file')) {
                        $attach = $request->file('std_file');
                        $requestData['std_file']  = $this->storeFile($attach, $requestData['std_no']);
                    }

                    //ไฟล์ประกาศการยกเลิก
                    if ($request->hasFile('revoke_file')) {
                        $attach = $request->file('revoke_file');
                        $requestData['revoke_file']  = $this->storeFile($attach, $requestData['revoke_file']);
                    }
            
                    $standard = Standard::findOrFail($id);
                    $standard->update($requestData);

                    $ics_id   = !empty($requestData['ics'])?$requestData['ics']:[];
                    $user_by  = !empty($requestData['user_by'])?$requestData['user_by']:[];

                    if(!empty($ics_id) && count($ics_id) > 0){
                        $this->save_ics($standard, $ics_id);

                    }
                    
                    if((!empty($user_by) && count($user_by) > 0) && $request->input('status_id') == 5){
                        $this->save_sendmail($standard, $user_by);
                        $this->sendmail($standard_full, $user_by);
    
                    }

                    return redirect('certify/standards')->with('flash_message', 'แก้ไข Certify/Standard เรียบร้อยแล้ว!');

                }else{
                    return  $this->cover_pdf($request);
                }

            } catch (\Exception $e) {

                echo $e->getMessage();
                exit;
                return redirect('certify/standards')->with('flash_message', 'เกิดข้อผิดพลาดในการบันทึก');
            }

        }
        abort(403);

    }


    public function save_standards(Request $request){
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('edit-'.$model)) {
            

            if( !empty( $request->get('id')  ) ){
                $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            }else{
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            }

            $requestData = $request->all();
            
            try {
               
                $standard_full                         = '';
                $standard_full                         .= !empty($requestData['std_no']) ? $requestData['std_no'] : null;
                $standard_full                         .= !empty($requestData['std_book'])  ?' เล่ม '.$requestData['std_book'] : null;
                $standard_full                         .= !empty($requestData['std_year'])  ?'-'. $requestData['std_year'] : null;
                $requestData['std_full']               =   $standard_full;

                if( in_array( $requestData['step_tap'], [1] ) ){
                    $requestData['std_sign_date']          = !empty($requestData['std_sign_date']) ? HP::convertDate($requestData['std_sign_date'],true) : null; 
                    $requestData['std_price']              = !empty($requestData['std_price'])?str_replace(",","",$requestData['std_price']):null;
                    $requestData['step_tap']               = in_array($requestData['step_status_1'], [5])?2:$requestData['step_tap'];
                    $requestData['status_id']              = $requestData['step_status_1'];
                }else if(  in_array( $requestData['step_tap'], [2] )  ){
                    $requestData['step_tap']               = in_array($requestData['step_status_2'], [6])?3:$requestData['step_tap'];
                    $requestData['status_id']              = $requestData['step_status_2'];
                }else if(  in_array( $requestData['step_tap'], [3] )  ){
                    $requestData['std_sign_date']          = !empty($requestData['std_sign_date']) ? HP::convertDate($requestData['std_sign_date'],true) : null;  
                    $requestData['std_price']              = !empty($requestData['std_price'])?str_replace(",","",$requestData['std_price']):null;
                    $requestData['step_tap']               = in_array($requestData['step_status_3'], [7])?4:$requestData['step_tap'];
                    $requestData['status_id']              = $requestData['step_status_3'];
                }else if(  in_array( $requestData['step_tap'], [4] )  ){
                    $requestData['gazette_post_date']      = !empty($requestData['gazette_post_date']) ? HP::convertDate($requestData['gazette_post_date'],true) : null;  
                    $requestData['gazette_effective_date'] = !empty($requestData['gazette_effective_date']) ? HP::convertDate($requestData['gazette_effective_date'],true) : null;  
                    $requestData['revoke_date']            = !empty($requestData['revoke_date']) ? HP::convertDate($requestData['revoke_date'],true) : null;
                    $requestData['gazette_state']          = !empty($requestData['gazette_state'] )?1:null;
                    $requestData['step_tap']               = in_array($requestData['step_status_4'], [8])?4:$requestData['step_tap'];
                    $requestData['status_id']              = $requestData['step_status_4'];
                }

                //ไฟล์การลงนามการจัดทำมาตรฐาน
                if ($request->hasFile('std_file')) {
                    $attach                   = $request->file('std_file');
                    $requestData['std_file']  = $this->storeFile($attach, $requestData['std_no']);
                }

                //ไฟล์ประกาศการยกเลิก
                if ($request->hasFile('revoke_file')) {
                    $attach                      = $request->file('revoke_file');
                    $requestData['revoke_file']  = $this->storeFile($attach, $requestData['revoke_file']);
                }

                if( !empty( $request->get('id')  ) ){
                    $id       = $request->get('id');
                    $standard = Standard::findOrFail($id);
                    $standard->update($requestData);
                    $url      = url('certify/standards/'.$id.'/edit');
                }else{//บันทึกใหม่
                    $requestData['publish_state'] = 1;
                    $standard = Standard::create($requestData);
                    $url      = url('certify/standards/');
                }

                $ics_id   = !empty($requestData['ics'])?$requestData['ics']:[];
                $user_by  = !empty($requestData['user_by'])?$requestData['user_by']:[];

                if(!empty($ics_id) && count($ics_id) > 0){
                    $this->save_ics($standard, $ics_id);

                }

                if((!empty($user_by) && count($user_by) > 0) && $standard->status_id == 5){
                    $this->save_sendmail($standard, $user_by);
                    $this->sendmail($standard_full, $user_by);
                }
                return response()->json(['msg' => 'success' , 'state' => 'success', 'url' => $url ]);

            } catch (\Exception $e) {

                $msg = $e->getMessage();
                return response()->json(['msg' => $msg , 'state' => 'error', 'url' => '']);
            }

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
    public function destroy($id, Request $request)
    {
        $model = str_slug('certifystandard','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Standard;
            Standard::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Standard::destroy($id);
          }

          return redirect('certify/standards')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('certifystandard','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Standard;
          Standard::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/standards')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    // สำหรับเพิ่มรูปไปที่ store
    public function storeFile($files, $app_no = 'std_no', $name = null)
    {
        if ($files) {
            $attach_path  = $this->attach_path.$app_no;

            $file_extension = $files->getClientOriginalExtension();
            $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName =   str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

            $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $storagePath;
        }else{
            return null;
        }
    }


    public function cover_pdf(Request $request)
    {

        if(!is_null($request)){
            if(!empty($request->id)){
                $standard = Standard::find($request->id);
            }
            $data_export = [
                                'std_no'          => !empty($request->std_no) ? $request->std_no : (!empty($standard->std_no)?$standard->std_no:'N/A'),
                                'std_title'       => !empty($request->std_title) ? $request->std_title : (!empty($standard->std_title)?$standard->std_title:'N/A'),
                                'std_title_en'    => !empty($request->std_title_en) ? $request->std_title_en : (!empty($standard->std_title_en)?$standard->std_title_en:'N/A'),
                                'isbn_no'         => !empty($request->isbn_no) ? $request->isbn_no : 'N/A'
                            ]; 

            $pdf = PDF::loadView('certify/standards/pdf/cover-thai', $data_export);
            return $pdf->stream("scope-thai.pdf");
    
        }
        abort(403);
      

    }

    //เลือกอัพเดทสถานะการเผยแพร่สถานะแบบทั้งหมด        
    public function publish_state(Request $request)
    {
        $id_array = $request->input('id');
        $standard = Standard::whereIn('id', $id_array)->update(['publish_state'=>2]);
            if($standard)
            {
                echo 'Data Updated';
            }
    }

    public function load_data_isbn($id)
    {
        $standard = Standard::findOrFail($id);
        $isbn_by  = User::where('runrecno',$standard->isbn_by )->first();

        $data = new stdClass;
        $data->id                  = $standard->id;
        $data->isbn_no             = $standard->isbn_no;
        $data->isbn_issue_at       = !empty($standard->isbn_issue_at)?HP::revertDate($standard->isbn_issue_at,true):'';
        $data->isbn_by             = !empty($isbn_by)?$isbn_by->reg_fname.' '.$isbn_by->reg_lname:auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $data->isbn_at             = !empty($standard->isbn_at)?HP::revertDate($standard->isbn_at,true):HP::revertDate(date('Y-m-d'),true);
        $data->isbn_file           = ! empty($standard->isbn_file)? HP::getFileStorage($standard->isbn_file):'';
        $data->isbn_file_extension = !empty($standard->isbn_file)? HP::FileExtension($standard->isbn_file):'';

        return response()->json($data);
    }

    public function update_isbn(Request $request)
    {
        $requestData = $request->all();
        
        if(!empty($requestData['id'])){
            $standard = Standard::where('id', $requestData['id'])->first();

            $standard->isbn_by       = auth()->user()->getKey();
            $standard->isbn_at       = date('Y-m-d H:i:s');
            $standard->isbn_no       = !empty($requestData['isbn_no'])?$requestData['isbn_no']:null;
            $standard->isbn_issue_at =  !empty($requestData['isbn_issue_at'])?HP::convertDate($requestData['isbn_issue_at'], true):null;

            if( $standard->status_id == 5){
                $standard->status_id = 6;
            }
                
            if ($request->hasFile('isbn_file')) {
                $attach = $request->file('isbn_file');
                $standard->isbn_file = $this->storeFile($attach, $requestData['isbn_no']);

            }
            $standard->save();
        }
      
        return response()->json(['msg' => 'success' ]);
    }

    private function save_ics($standard, $ics_id){
        if(!empty($ics_id) && count($ics_id) > 0){

            StandardIcs::where('std_id', $standard->id)->delete();
            foreach($ics_id as $key => $item) {
                $input = [];
                $input['std_id']    = $standard->id;
                $input['ics_id']    = $item;
                $input['created_by']     = auth()->user()->getKey();
                StandardIcs::create($input);
            }

        }
    }
    private function save_sendmail($standard, $user_by){
        if(!empty($user_by) && count($user_by) > 0){

            StandardSendmail::where('std_id', $standard->id)->delete();
            foreach($user_by as $key => $item) {
                $input = [];
                $input['std_id']         = $standard->id;
                $input['user_by']        = $item;
                $input['created_by']     = auth()->user()->getKey();
                StandardSendmail::create($input);
            }

        }
    }

    private function sendmail($standard_full, $user_by){
        $users = User::select(DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name")  )->whereIn('runrecno', $user_by)->pluck('name')->toArray();
        $name = '';
        foreach ($users as $key => $user) {
            $name .=  !empty($user)?$user.' ' :'';
        }

        $emails = user::whereIn('runrecno',$user_by)->pluck('reg_email')->toArray();
        foreach ($emails as $key => $email) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                unset($emails[$key]);
            }
        }

            if(count($emails) > 0){
                $mail_format = new CertifyStandardIsbn([
                                                        'standard_full'=> $standard_full,
                                                        'name'=> !empty($name)?$name:null,
                                                        'url'=>  url('/certify/standards'),
                                                        ]);
                Mail::to($emails)->send($mail_format);
            }
    }

    public function signPosition($id) {
        $signer =  Signer::where('id',$id)->first();
        if(!is_null($signer)){
                return response()->json([
                    'sign_position'=> !empty($signer->position) ? $signer->position : ' ' ,
                 ]);
        }
   
    }
}
