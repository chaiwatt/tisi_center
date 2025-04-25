<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AttachFile;
use App\Models\Tis\Standard as standard;
use App\Models\Basic\Department;
use App\Models\Tis\ImportComment;
use App\Models\Tis\ListenStdDraft;
use App\Models\Tis\PublicDraft; 
use App\Models\Tis\NoteStdDraft;
use App\Models\Tis\ListenStdDraftDetail;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use HP;

class ImportCommentController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'tis_attach/import_comment/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
     {
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_date'] = $request->get('filter_start_date');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['perPage'] = $request->get('perPage', 10);
 
            $Query = new ImportComment;

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = HP::convertDate($filter['filter_start_date'],true);
                $end = HP::convertDate($filter['filter_end_date'],true);
                $Query = $Query->whereBetween('save_date', [$start,$end]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start =  HP::convertDate($filter['filter_start_date'],true);
                $Query = $Query->whereDate('save_date',$start);
            }

            $import_comment = $Query->sortable()->with('user_created')
                                       ->with('user_updated')
                                       ->paginate($filter['perPage']);
             $attach_path = $this->attach_path;
            return view('tis.import_comment.index', compact('import_comment', 'filter','attach_path'));
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
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('add-'.$model)) {

            $attach_excel  = [];
            $attachs_excel_exists = [];

            $import_comment = new ImportComment;

            return view('tis.import_comment.create',compact('import_comment','attach_excel','attachs_excel_exists'));
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
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
		]);
            $requestData = $request->all();
           
            $requestData['created_by'] = auth()->user()->getKey();
            // $requestData['save_date'] = $request->save_date?Carbon::createFromFormat("d/m/Y",$request->save_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['save_date'] =  date('Y-m-d');

            if ($single_file = $request->file('attach_excel')) {

                    $storagePath = Storage::put($this->attach_path, $single_file);
                    $storageName = basename($storagePath); // Extract the filename

                    $single_attach = ['file_name' => $storageName,
                                        'file_client_name' => $single_file->getClientOriginalName()
                                    ];
                    $requestData['attach_excel'] = json_encode($single_attach);

            } else {
                    $requestData['attach_excel'] = $import_comment->attach_excel;
            }

            $import_comment = ImportComment::create($requestData);

            return redirect('tis/import_comment/'.$import_comment->id. '/edit')->with('flash_message', 'เพิ่ม ImportComment เรียบร้อยแล้ว');
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
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('view-'.$model)) {
            $import_comment = ImportComment::findOrFail($id);
            return view('tis.import_comment.show', compact('import_comment'));
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
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('edit-'.$model)) {
            $import_comment = ImportComment::findOrFail($id);
            $import_comment['save_date'] = $import_comment['save_date']?Carbon::createFromFormat("Y-m-d",$import_comment['save_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):date('Y-m-d');

               //ไฟล์แนบ
            $attach_excel = json_decode($import_comment['attach_excel']);
            $attach_excel = !is_null($attach_excel)?$attach_excel:[(object)['file_name'=>'', 'file_client_name'=>'']];
            $attach_path = $this->attach_path;

            // $count_rows = $this->CountRow($id);
        
            return view('tis.import_comment.edit', compact('import_comment','attach_excel','attach_path'));
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
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
                'attach_excel' => 'required|file',
		   ]);
            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();
            $requestData['state'] = $request->has('state')?1:0;
            $requestData['status'] = 1;
            $requestData['import_detail'] = null;
            $requestData['error_detail'] = null;
            
            if ($single_file = $request->file('attach_excel')) {

                    $storagePath = Storage::put($this->attach_path, $single_file);
                    $storageName = basename($storagePath); // Extract the filename

                    $single_attach = ['file_name' => $storageName,
                                        'file_client_name' => $single_file->getClientOriginalName()
                                    ];
                    $requestData['attach_excel'] = json_encode($single_attach);

            } else {
                    $requestData['attach_excel'] = $import_comment->attach_excel;
            }

            $import_comment = ImportComment::findOrFail($id);
            $import_comment->update($requestData);

            return redirect('tis/import_comment/'.$import_comment->id.'/edit')->with('flash_message', 'ImportComment updated!');
        }
        abort(403);

    }

    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
      $model = str_slug('import-comment','-');
      if(auth()->user()->can('edit-'.$model)) {

          $id = $request->input('id');
          $state = $request->input('state');
          $result = ImportComment::where('id', $id)->update(['state' => $state]);

          if($result) {
            return 'success';
          } else {
            return "not success";
          }

      }else{
        abort(403);
      }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('import-comment','-');
        if(auth()->user()->can('delete-'.$model)) {
            ImportComment::destroy($id);
            return redirect('tis/import_comment')->with('flash_message', 'ImportComment deleted!');
        }
        abort(403);

    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
      $id_array = $request->input('id');
      $comment = ImportComment::whereIn('id', $id_array);
      if($comment->delete())
      {
          echo 'Data Deleted';
      }

    }

    //เลือกเผยแพร่หรือไม่เผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
      $arr_publish = $request->input('id_publish');
      $state = $request->input('state');

      $result = ImportComment::whereIn('id', $arr_publish)->update(['state' => $state]);
      if($result)
      {
        echo 'success';
      } else {
        echo "not success";
      }

    }

    private function getClassName() {

        $path = explode('\\', __CLASS__);
        unset($path[0], $path[1], $path[2]);
        return implode('\\', $path);
    }

    function list_by_scholarshipsource($fiscalyear_id) {

        $fund_details = FundDetail::where('fiscalyear_id',$fiscalyear_id)->where('status',7)->pluck('scholarshipsource_id');
        $scholarshipsource = Scholarshipsource::whereIn('id',$fund_details)->get();
        return response()->json($scholarshipsource);

    }

    function list_by_scholarshipname($fiscalyear_id,$source_id) {

        $fund_details = FundDetail::where('fiscalyear_id',$fiscalyear_id)->where('scholarshipsource_id',$source_id)->where('status',7)->pluck('scholarshipname_id');
        $scholarship_name = ScholarshipName::whereIn('id',$fund_details)->get();
        return response()->json($scholarship_name);

    }

    //--- ไฟล์แนบเอกสารที่เกี่ยวข้อง ----
	 public static function getTableName()
	 {
		 $model = new ImportComment();
		 return $model->getTable();
     }

     //--- ไฟล์แนบเอกสารที่เกี่ยวข้อง ----

    function UploadExcel($id){

        $import_comment = ImportComment::findOrFail($id);

        $department = Department::pluck('id', 'title')->toArray();    
        $arr = [
            'ยืนยันตามมาตรฐานดังกล่าว'=>'confirm_standard',
            'เห็นควรแก้ไขปรับปรุงมาตรฐานดังกล่าว'=>'revise_standard',
            'ยกเลิกมาตรฐานดังกล่าว'=>'cancel_standard',
            'ไม่มีข้อคิดเห็น'=>'no_comment'
        ];

        // if(!is_null($import_comment) && $import_comment->status==2){
        //     return response()->json($this->responseResultImport($id));
        // }
     
        //ไฟล์แนบ
        $attach_excel = json_decode($import_comment['attach_excel']);
        $attach_excel = !is_null($attach_excel)?$attach_excel:[(object)['file_name'=>'', 'file_client_name'=>'']];
        $attach_path = $this->attach_path;

        $row_error_import = [];
        $result = ['yield'=>false, 'message'=>''];



        //create directly an object instance of the IOFactory class, and load the xlsx file
        $fxls = HP::getFileStoragePath($attach_path.$attach_excel->file_name);

        $excel_path = public_path()."/uploads/".$attach_path.$attach_excel->file_name;

        // dd($path);

        //เช็คชนิดไฟล์
        $file_type = mime_content_type($excel_path);

        // dd($file_type);
          if($file_type!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
              $error_detail = (object)[];
              $error_detail->error_level = 0;
              $error_detail->error_message = 'รูปแบบไฟล์ไม่ถูกต้อง ต้องเป็น Office Open XML SpreadsheetML (.xlsx)';
              $this->UploadProgress($id, 2, $error_detail);
              goto end;
          }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel_path);
        $sheet         =  $spreadsheet->setActiveSheetIndex(1);


        //read excel data and store it into an array
        $xls_data = $sheet->toArray(null, true, true, true);
        unset($xls_data[1]);
 
        $stan_dard_data  = [];
        $applications_error = [];
  
          $i = 0;
        foreach($xls_data as $index => $item){
         
            $item = (object)$item; //ข้อมูล excel แถวที่จะนำเข้า
           if($item->B!='' && !is_null($item->B) && $item->B != "NULL"){

                   if( ($item->K!='' && !is_null($item->K) && $item->K != "NULL" ? $item->K  : null) ){
                            $data_date =    explode("-",$item->K);
                            if(count($data_date) > 1){
                                 $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
                            }else{
                                $date =   date('Y-m-d') ;      
                            }
                    }else{
                                $date =   date('Y-m-d') ;      
                    }
                    $i++;
                if($item->D!='' && !is_null($item->D) && $item->D != "NULL"){
                    $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->where('tis_book',str_replace(" ","",$item->D))->first();
                    if(!is_null($stan_dard) &&
                       !is_null($stan_dard->note_std_draft_to) &&   
                        $date >=  $stan_dard->note_std_draft_to->start_date &&   
                       $date <=   $stan_dard->note_std_draft_to->end_date   ){
                        $stan_dard_data[] = $index;
                        $error_row = $this->CheckExcelValid($item,1,true); // ผ่าน
                        $applications_error[] = $error_row;
                    }else{
                         $error_row = $this->CheckExcelValid($item,1,false); // ไม่ผ่าน
                         $applications_error[] = $error_row;
                    }
                }else{
                    $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->first();
                    if(!is_null($stan_dard) &&
                        !is_null($stan_dard->note_std_draft_to) &&   
                        $date >=  $stan_dard->note_std_draft_to->start_date &&   
                        $date <=   $stan_dard->note_std_draft_to->end_date   ){
                        $stan_dard_data[] = $index;
                        $error_row = $this->CheckExcelValid($item,2,true); // ผ่าน
                        $applications_error[] = $error_row;
                    }else{
                        $error_row = $this->CheckExcelValid($item,2,false);  // ไม่ผ่าน
                        $applications_error[] = $error_row;
                    }
                }
            }

        }
 
        if($i == count($stan_dard_data)){
            $applications_data  = [];
            foreach($xls_data as $index => $item){
              $item = (object)$item; //ข้อมูล excel แถวที่จะนำเข้า 
              if($item->B!='' && !is_null($item->B) && $item->B != "NULL"){
  
                      $listen_std_draft = new ListenStdDraft;
                      $listen_std_draft->created_by = auth()->user()->getKey();
                      $listen_std_draft->name    =  ($item->G!='' && !is_null($item->G) && $item->G != "NULL" ? $item->G  : null) ; 
                      $listen_std_draft->tel     =  ($item->H!='' && !is_null($item->H) && $item->H != "NULL" ? $item->H  : null) ; 
                      $listen_std_draft->email   =  ($item->I!='' && !is_null($item->I) && $item->I != "NULL" ? $item->I  : null) ; 

                        if( ($item->K!='' && !is_null($item->K) && $item->K != "NULL" ? $item->K  : null) ){
                            $data_date =    explode("-",$item->K);
                            if(count($data_date) > 1){
                               $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
                               $listen_std_draft->created_at   =   $date ; 
                            }
                        }

                      if(array_key_exists($item->J,$department) ){
                          $listen_std_draft->department_id = $department[$item->J];
                      }else{
                          $listen_std_draft->department_name = ($item->J!='' && !is_null($item->J) && $item->J != "NULL" ? $item->J  : null) ;
                      }

                      if(array_key_exists($item->E,$arr) ){
                        $listen_std_draft->comment =  $arr[$item->E] ;
                      }
             
  
                if($item->D=='' && is_null($item->D) && $item->D == "NULL"){
                      $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->where('tis_book',str_replace(" ","",$item->D))->first();

                      if( ($item->K!='' && !is_null($item->K) && $item->K != "NULL" ? $item->K  : null) ){
                                $data_date =    explode("-",$item->K);
                                if(count($data_date) > 1){
                                    $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
                                }else{
                                    $date =   date('Y-m-d') ;      
                                }
                        }else{
                                    $date =   date('Y-m-d') ;      
                        }

                      if(!is_null($stan_dard) &&
                        !is_null($stan_dard->note_std_draft_to) &&   
                          $date >=  $stan_dard->note_std_draft_to->start_date &&   
                          $date <=   $stan_dard->note_std_draft_to->end_date   ){

                          $listen_std_draft->note_std_draft_id = $stan_dard->note_std_draft_to->id ?? null;
                          $listen_std_draft->save();

                          if($item->F!='' && !is_null($item->F) && $item->F!= "NULL"){
                            $this->listen_std_draft_detail($item->F,$listen_std_draft->id);
                          }

                          $listen_std_draft->status = 1; 
                          $applications_data[$index] = $listen_std_draft;

                      }else{ 
                          
                           $listen_std_draft->status = 0;
                           $listen_std_draft->tis_no_error = @$item->B.' - '.@$item->C ;
                           $listen_std_draft->error =  $this->CheckValid($item,2);
                           $applications_data[$index] = $listen_std_draft;

                      }
                 }else{
                      $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->first();
                      if(!is_null($stan_dard) &&
                        !is_null($stan_dard->note_std_draft_to) &&   
                        $date  >=  $stan_dard->note_std_draft_to->start_date &&   
                        $date  <=   $stan_dard->note_std_draft_to->end_date   ){

                         $listen_std_draft->note_std_draft_id = $stan_dard->note_std_draft_to->id ?? null;
                         $listen_std_draft->save();
                         
                         if($item->F!='' && !is_null($item->F) && $item->F!= "NULL"){
                            $this->listen_std_draft_detail($item->F,$listen_std_draft->id);
                          }
                          $listen_std_draft->status = 1; 
                          $applications_data[$index] = $listen_std_draft;
                      }else{ 
                           $listen_std_draft->status = 0;
                           $listen_std_draft->tis_no_error = @$item->B.' - '.@$item->C ;
                           $listen_std_draft->error =  $this->CheckValid($item,2);
                           $applications_data[$index] = $listen_std_draft;
                      }
                  }                
                }
            }
              $this->UploadProgress($id, 2, null, $applications_data);
              $import_comment->status = 2;
              $import_comment->save();
              end:
              return response()->json([
                                        'message' => 'true',
                                        'data' =>$this->responseResultImport($id)
                                     ]);
              
        }else{
            $this->UploadProgress($id, 1, $applications_error, null);
            return response()->json([
                                      'message' => 'false',
                                       'data' => $applications_error
                                     ]);
        }
  
     }
 
     function CheckExcelValid($row,$type = 1,$status = false){
        $department = Department::pluck('id', 'title')->toArray();    
        $error_row = [];
        $error = [];    
        $date = '';
     
        $error_row['tis_no']    =  ($row->B!='' && !is_null($row->B) && $row->B != "NULL" ? $row->B  : '') ; 
        $error_row['tis_year']     =  ($row->C!='' && !is_null($row->C) && $row->C != "NULL" ? $row->C  : '') ; 
        $error_row['tis_book']   =  ($row->D!='' && !is_null($row->I) && $row->D != "NULL" ? $row->D  : '') ; 

        $error_row['tel']     =  ($row->H!='' && !is_null($row->H) && $row->H != "NULL" ? $row->H  : null) ; 
        $error_row['email']   =  ($row->I!='' && !is_null($row->I) && $row->I != "NULL" ? $row->I  : null) ; 
        //ชื่อผู้ให้ความคิดเห็น
        $error_row['name']    = ($row->G!='' && !is_null($row->G) && $row->G != "NULL" ? $row->G  : null) ; 
        // ชื่อหน่วยงาน
        if(array_key_exists($row->J,$department) ){
            $error_row['department'] = $department[$row->J];
        }else{
            $error_row['department'] = ($row->J!='' && !is_null($row->J) && $row->J != "NULL" ? $row->J  : null) ;
        }

        if( ($row->K!='' && !is_null($row->K) && $row->K != "NULL" ? $row->K  : null) ){
             $data_date =    explode("-",$row->K);
             if(count($data_date) > 1){
                $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
                $error_row['created_at']   =   HP::DateThai($date)  ; 
             }else{
                $error_row['created_at']   =   HP::DateThai(date('Y-m-d')) ;      
             }
        }else{
            $error_row['created_at']   =   HP::DateThai(date('Y-m-d')) ;      
        }

        if($status == false){

            $error_row['status']   =  '<span class=" text-danger">นำเข้าไม่ได้</span>';    // ไม่ผ่าน

            $tis_no = standard::where('tis_no',str_replace(" ","",$row->B))->first();
            if(!is_null($tis_no)  &&  is_null($tis_no->note_std_draft_to)){ //เลข มอก.
              $error[] =  'B <i class="fa fa-arrow-right"></i>  ไม่มีเลข มอก. '.$row->B;  
            }
            $tis_year = standard::where('tis_year',str_replace(" ","",$row->C))->first();
            if(!is_null($tis_year)  &&  is_null($tis_year->note_std_draft_to)){ //ปี มอก.
              $error[] =  'C <i class="fa fa-arrow-right"></i>  ไม่มีปี มอก. '.$row->C;
            }
            $tis_book = standard::where('tis_book',str_replace(" ","",$row->D))->first();
            if(!is_null($tis_book) &&  is_null($tis_book->note_std_draft_to)  && $type == 1){ //เล่มที่ มอก.
              $error[] = 'D <i class="fa fa-arrow-right"></i> ไม่มีเล่มที่ มอก. '.$row->D ;
            }

            if($row->D!='' && !is_null($row->D) && $row->D != "NULL"){ 
                $stan_dard = standard::where('tis_no',str_replace(" ","",$row->B))->where('tis_year',str_replace(" ","",$row->C))->where('tis_book',str_replace(" ","",$row->D))->first();
                if(!is_null($stan_dard) &&
                !is_null($stan_dard->note_std_draft_to) && $date != '') {
                  $error[] =  'วันที่'.  HP::DateThai($date) .'ยังไม่ได้การเผยแพร่';
                } 
            }else{
                $stan_dard = standard::where('tis_no',str_replace(" ","",$row->B))->where('tis_year',str_replace(" ","",$row->C))->first();
                if(!is_null($stan_dard) &&
                   !is_null($stan_dard->note_std_draft_to)  && $date != '') {
                   $error[] =  'วันที่'.  HP::DateThai($date) .'ยังไม่ได้การเผยแพร่';
                }
            }

            $error_row['error']   = implode("<br/>",$error);
        }else{
            $error_row['status']   =  '<span class=" text-success">นำเข้าได้</span>';    // ผ่าน
            $error_row['error']    =  '';
        }     


        return $error_row;
    }



    function InsertDataExcel($id){
        $import_comment = ImportComment::findOrFail($id);
        $department = Department::pluck('id', 'title')->toArray();    
        $arr = [
            'ยืนยันตามมาตรฐานดังกล่าว'=>'confirm_standard',
            'เห็นควรแก้ไขปรับปรุงมาตรฐานดังกล่าว'=>'revise_standard',
            'ยกเลิกมาตรฐานดังกล่าว'=>'cancel_standard',
            'ไม่มีข้อคิดเห็น'=>'no_comment'
        ];
        //ไฟล์แนบ
        $attach_excel = json_decode($import_comment['attach_excel']);
        $attach_excel = !is_null($attach_excel)?$attach_excel:[(object)['file_name'=>'', 'file_client_name'=>'']];
        $attach_path = $this->attach_path;

        $excel_path = public_path()."/uploads/".$attach_path.$attach_excel->file_name;
        //เช็คชนิดไฟล์
        $file_type = mime_content_type($excel_path);

        // dd($file_type);
          if($file_type!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
              $error_detail = (object)[];
              $error_detail->error_level = 0;
              $error_detail->error_message = 'รูปแบบไฟล์ไม่ถูกต้อง ต้องเป็น Office Open XML SpreadsheetML (.xlsx)';
              $this->UploadProgress($id, 2, $error_detail);
              goto end;
          }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel_path);
        $sheet         =  $spreadsheet->setActiveSheetIndex(1);


        //read excel data and store it into an array
        $xls_data = $sheet->toArray(null, true, true, true);
        unset($xls_data[1]);
        $applications_data  = [];
          foreach($xls_data as $index => $item){
            $item = (object)$item; //ข้อมูล excel แถวที่จะนำเข้า
            if($item->B!='' && !is_null($item->B) && $item->B != "NULL"){

                    $listen_std_draft = new ListenStdDraft;
                    $listen_std_draft->created_by = auth()->user()->getKey();
                    $listen_std_draft->name    =  ($item->G!='' && !is_null($item->G) && $item->G != "NULL" ? $item->G  : null) ; 
                    $listen_std_draft->tel     =  ($item->H!='' && !is_null($item->H) && $item->H != "NULL" ? $item->H  : null) ; 
                    $listen_std_draft->email   =  ($item->I!='' && !is_null($item->I) && $item->I != "NULL" ? $item->I  : null) ; 
                    if(array_key_exists($item->J,$department) ){
                        $listen_std_draft->department_id = $department[$item->J];
                    }else{
                        $listen_std_draft->department_name = ($item->J!='' && !is_null($item->J) && $item->J != "NULL" ? $item->J  : null) ;
                    }
                    
                    if(array_key_exists($item->E,$arr) ){
                        $listen_std_draft->comment =  $arr[$item->E] ;
                      }

                    if( ($item->K!='' && !is_null($item->K) && $item->K != "NULL" ? $item->K  : null) ){
                        $data_date =    explode("-",$item->K);
                        if(count($data_date) > 1){
                             $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
                           $listen_std_draft->created_at   =   $date ; 
                        }else{
                            $date =   date('Y-m-d') ;     
                            $listen_std_draft->created_at   = date('Y-m-d H:i:s');      
                        }
                    }else{
                        $date =   date('Y-m-d') ;     
                        $listen_std_draft->created_at  = date('Y-m-d H:i:s'); 
                    }
                    
              if($item->D=='' && is_null($item->D) && $item->D == "NULL"){
                    $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->where('tis_book',str_replace(" ","",$item->D))->first();
                    if(!is_null($stan_dard) &&
                        !is_null($stan_dard->note_std_draft_to) &&   
                        $date >=  $stan_dard->note_std_draft_to->start_date &&   
                        $date <=   $stan_dard->note_std_draft_to->end_date   ){

                        $listen_std_draft->note_std_draft_id = $stan_dard->note_std_draft_to->id ?? null;
                        $listen_std_draft->save();
                        if($item->F!='' && !is_null($item->F) && $item->F!= "NULL"){
                            $this->listen_std_draft_detail($item->F,$listen_std_draft->id);
                          }
                          $listen_std_draft->status = 1;
                          $applications_data[$index] = $listen_std_draft;
                     }else{
                          $listen_std_draft->status = 0;
                          $listen_std_draft->error =  $this->CheckValid($item,1);
                          $listen_std_draft->tis_no_error = @$item->B.'เล่น '.@$item->D.' - '.@$item->C ;
                          $applications_data[$index] = $listen_std_draft;
                     }
               }else{
                    $stan_dard = standard::where('tis_no',str_replace(" ","",$item->B))->where('tis_year',str_replace(" ","",$item->C))->first();
                 if(!is_null($stan_dard) &&
                    !is_null($stan_dard->note_std_draft_to) &&   
                    $date >=  $stan_dard->note_std_draft_to->start_date &&   
                    $date <=   $stan_dard->note_std_draft_to->end_date   ){

                       $listen_std_draft->note_std_draft_id = $stan_dard->note_std_draft_to->id ?? null;
                       $listen_std_draft->save();
                       if($item->F!='' && !is_null($item->F) && $item->F!= "NULL"){
                        $this->listen_std_draft_detail($item->F,$listen_std_draft->id);
                      }
                       $listen_std_draft->status = 1; 
                       $applications_data[$index] = $listen_std_draft;
                   }else{ 
                        $listen_std_draft->status = 0;
                        $listen_std_draft->tis_no_error = @$item->B.' - '.@$item->C ;
                        $listen_std_draft->error =  $this->CheckValid($item,2);
                        $applications_data[$index] = $listen_std_draft;
                   }
                }                
              }
          }
 
          $this->UploadProgress($id, 2, null, $applications_data);
          $import_comment->status = 2;
          $import_comment->save();
          end:
          return response()->json([
                                    'message' => 'true',
                                    'data' =>$this->responseResultImport($id)
                                 ]);
    }
     
    function listen_std_draft_detail($data,$id){
        $datas =   explode("&",$data);
        if(count($datas) > 0){
            foreach($datas as $item){
                $list = new   ListenStdDraftDetail; 
                $list->listen_std_draft_id  =$id;
                $list->comment_detail  = $item;
                $list->save();
            }
        }
    }   
    function CheckValid($row,$type = 1){

        $date = ''; 
        if( ($row->K!='' && !is_null($row->K) && $row->K != "NULL" ? $row->K  : null) ){
            $data_date =    explode("-",$row->K);
            if(count($data_date) > 1){
               $date =  ($data_date[2]-543).'-'.$data_date[1].'-'.$data_date[0];
            } 
       } 

        $error = [];
        $tis_no = standard::where('tis_no',str_replace(" ","",$row->B))->first();
        if(!is_null($tis_no)  &&  is_null($tis_no->note_std_draft_to)){ //เลข มอก.
          $error[] =  'B <i class="fa fa-arrow-right"></i>  ไม่มีเลข มอก. '.$row->B;  
        }
        $tis_year = standard::where('tis_year',str_replace(" ","",$row->C))->first();
        if(!is_null($tis_year)  &&  is_null($tis_year->note_std_draft_to)){ //ปี มอก.
          $error[] =  'C <i class="fa fa-arrow-right"></i>  ไม่มีปี มอก. '.$row->C;
        }
        $tis_book = standard::where('tis_book',str_replace(" ","",$row->D))->first();
        if(!is_null($tis_book) &&  is_null($tis_book->note_std_draft_to)  && $type == 1){ //เล่มที่ มอก.
          $error[] = 'D <i class="fa fa-arrow-right"></i> ไม่มีเล่มที่ มอก. '.$row->D ;
        }
        if($row->D!='' && !is_null($row->D) && $row->D != "NULL"){ 
            $stan_dard = standard::where('tis_no',str_replace(" ","",$row->B))->where('tis_year',str_replace(" ","",$row->C))->where('tis_book',str_replace(" ","",$row->D))->first();
            if(!is_null($stan_dard) &&
            !is_null($stan_dard->note_std_draft_to) && $date != '') {
              $error[] =  'วันที่'.  HP::DateThai($date) .'ยังไม่ได้การเผยแพร่';
            } 
        }else{
            $stan_dard = standard::where('tis_no',str_replace(" ","",$row->B))->where('tis_year',str_replace(" ","",$row->C))->first();
            if(!is_null($stan_dard) &&
               !is_null($stan_dard->note_std_draft_to)  && $date != '') {
               $error[] =  'วันที่'.  HP::DateThai($date) .'ยังไม่ได้การเผยแพร่';
            }
        }
        return  implode("<br/>",$error);
    }

    function CountRow($id){
        $attachs_other = AttachFile::where('table_name', self::getTableName())->where('ref_id', $id)->where('section', 'attachs_other')->first();
        $fxls = public_path('/storage/uploads/'.$attachs_other->url);
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fxls);
        $objWorksheet = $spreadsheet->setActiveSheetIndex(0);

        $highestRow = $objWorksheet->getHighestRow();
        return  $highestRow;
    }

    function CheckExcelHeader($dataHeader){ //เช็คหัวคอลัมถ์
        $headerList = array('A'=>'NO',
                            'B'=>'เลข มอก.',
                            'C'=>'ปี มอก.',
                            'D'=>'เล่มที่ มอก.',
                            'E'=>'ความคิดเห็น',
                            'F'=>'ความคิดเห็นเพิ่มเติม',
                            'G'=>'ชื่อผู้ให้ความคิดเห็น',
                            'H'=>'เบอร์โทรติดต่อ',
                            'I'=>'email',
                            'J'=>'ชื่อหน่วยงาน'
                        );
		$header_error = array();

		foreach($headerList as $key=>$value){
            if(array_key_exists($key,$dataHeader)){
                if($dataHeader[$key] != $value){
                     $header_error[$key] = array('data'=>$dataHeader[$key], 'comment'=>'หัวตารางต้องเป็น "'.$value.'" เท่านั้น');
                 }
            }
		}

		return $header_error;

	}

  function CheckExcelEmpty($row){//เช็คแถวของ excel ว่าว่างหรือไม่

      $col_rang = $this->createColumnsArray('AP');//คอลัมภ์ที่เช็ค

      $empty = true;
      foreach ($row as $col => $value) {
        $value = trim($value);
        if(!is_null($value) && $value!='' && in_array($col, $col_rang)){//ถ้าค่าไม่ว่างและอยู่ในช่วงคอลัมที่จะใช้นำเข้า
          $empty = false;
          break;
        }
      }

      return $empty;

  }


  function createColumnsArray($end_column, $first_letters = '')
  {
    $columns = array();
    $length = strlen($end_column);
    $letters = range('A', 'Z');

    // Iterate over 26 letters.
    foreach ($letters as $letter) {
        // Paste the $first_letters before the next.
        $column = $first_letters . $letter;

        // Add the column to the final array.
        $columns[] = $column;

        // If it was the end column that was added, return the columns.
        if ($column == $end_column)
            return $columns;
    }

    // Add the column children.
    foreach ($columns as $column) {
        // Don't itterate if the $end_column was already set in a previous itteration.
        // Stop iterating if you've reached the maximum character length.
        if (!in_array($end_column, $columns) && strlen($column) < $length) {
            $new_columns = $this->createColumnsArray($end_column, $column);
            // Merge the new columns which were created with the final columns array.
            $columns = array_merge($columns, $new_columns);
        }
    }

    return $columns;
  }

  function convertDate($date, $minus = true)
  {
      $negative = $minus === true ? 543 : 0;
      $dates = explode('-', $date);
      return count($dates) == 3 ? ($dates['2'] - $negative) . '-' . $dates[1] . '-' . $dates[0] : '-';
  }

  function UploadProgress($id, $status, $error_detail=NULL, $import_detail=NULL){

      $import_comment = ImportComment::findOrFail($id);
      $import_comment->status = $status;
      if(!is_null($error_detail)){
          $import_comment->error_detail = json_encode($error_detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
      }
      if(!is_null($import_detail)){
          $import_comment->import_detail = json_encode($import_detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
      }

      $import_comment->save();
      return $import_comment;
  }

  //แสดง
  function showResultImport($id){
      $import_comment = ImportComment::findOrFail($id);
       return response()->json([
                                    'message' => 'true',
                                    'data' =>$this->responseResultImport($id)
                                 ]);
  }

  //แสดงข้อมูลผลการนำเข้า
  function responseResultImport($id , $type = 1){
      $department = Department::pluck('title', 'id')->toArray();                               
      $public_draft = NoteStdDraft::get();
      $data_title = [];
      $data_tis_no = [];
      $data_get_stand = [];
      foreach($public_draft as $item){
            $data_title[$item->id] = $item->title ?? "n/a" ;
            $data_tis_no[$item->id]  =  $item->tis_no  ?? "n/a" ;
            $data_get_stand[$item->id] =  $item->ProductGroupName  ?? "n/a" ;
        }      
      $response = [];
      $import_comment = ImportComment::findOrFail($id);

      if($import_comment->status=='2'){//ถ้านำเข้าแล้ว
            $import_detail = json_decode($import_comment->import_detail);//รายการที่นำเข้า
            foreach ($import_detail as $key => $item) {
                $data = (object)[];     
                $data->created_at =  HP::DateThai($item->created_at) ?? null;
                $data->name =   !empty($item->name) && $item->name !=  "NULL" ? $item->name : '';  
                $data->department =    !empty($item->department_id) && array_key_exists($item->department_id,$department) ?  $department[$item->department_id]  : @$item->department_name ;
                if( !empty($item->note_std_draft_id)){
                    $data->title =  array_key_exists($item->note_std_draft_id,$data_title) ?  $data_title[$item->note_std_draft_id]  : "n/a" ;
                    $data->tis_no =  array_key_exists($item->note_std_draft_id,$data_tis_no) ?  $data_tis_no[$item->note_std_draft_id]  : "n/a" ;
                    $data->get_stand =  array_key_exists($item->note_std_draft_id,$data_get_stand) ?  $data_get_stand[$item->note_std_draft_id]  : "n/a" ;
                }else{
                    $data->title = ''; 
                    $data->tis_no = !empty($item->tis_no_error)  ?  $item->tis_no_error : ''; 
                    $data->get_stand = '';
                }

                $data->tel  =  !empty($item->tel) && $item->tel !=  "NULL" ? $item->tel : ''; 
                $data->email =  !empty($item->email) && $item->email !=  "NULL" ? $item->email : '';
                if( !empty($item->status)  && $item->status == 1){
                    $data->status  =  '<span class=" text-success">นำเข้าแล้ว</span>';    //  
                }else{
                    $data->status  =  '<span class=" text-danger">นำเข้าไม่ได้</span>';    //  
                }
                $data->error  =  !empty($item->error)   ? $item->error : '';  //  
             
                $response[] = $data;
            }
      }
      
      return $response;
  }


    public function storeFile($files, $app_no = 'files_cb',$name =null)
    {
        if ($files) {
            $file_extension = $files->getClientOriginalExtension();
            if(in_array($file_extension,['docx','doc'])){
                $storagePath = Storage::putFile($this->attach_path,$files);
            }else{
                $storagePath = Storage::put($this->attach_path, $files);
            }
            $storageName = basename($storagePath); // Extract the filename
            return  $storageName;
        }else{
            return null;
        }

    }

}
