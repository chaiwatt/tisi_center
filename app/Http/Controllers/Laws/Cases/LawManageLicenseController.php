<?php

namespace App\Http\Controllers\Laws\Cases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use HP;
use HP_Law;
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesLicenseResult;
use App\Models\Law\Log\LawNotify; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mail\Law\Cases\ManageLicense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

use App\Models\Basic\TisiLicense;

use App\Models\Tb4\TisiLicensePause;
use App\Models\Tb4\TisiLicenseCancel;
use App\Models\Tb4\TisiCancelReason;
use Carbon\Carbon;

class LawManageLicenseController extends Controller
{
    
    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/cases_manage_license';
        $this->permission  = str_slug('law-cases-manage-licenses','-');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_basic_section_id = $request->input('filter_basic_section_id');

        $query =  LawCasesResult::query() 
                                        ->where('license','1')
                                        ->with([
                                            'law_case_to' 
                                        ])
                                        ->where(function($query){
                                            $query->whereHas('law_case_to',function($query){
                                                $query->whereNotIn('status',['0','99']);
                                            });
                                        })
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                        $query->Where(DB::raw("REPLACE(case_number,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                              });
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                        $query->Where(DB::raw("REPLACE(offend_name,' ','')")  , 'LIKE', '%' . $search_full . '%')
                                                                             ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')") , 'LIKE', '%' . $search_full . '%');
                                                              });
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                 $query->Where(DB::raw("REPLACE(case_number,' ','')")  , 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere(DB::raw("REPLACE(offend_name,' ','')") , 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')") , 'LIKE', '%' . $search_full . '%');
                                                                  });
                                                    break;
                                            endswitch;
                                        }) 
                                        ->when($filter_status, function ($query, $filter_status){
                                                if($filter_status == '-1'){
                                                      $law_case_ids =  LawCasesLicenseResult::select('law_case_id')->groupBy('law_case_id');
                                                     $query->WhereNotIn('law_case_id', $law_case_ids) ;
                                                }else{
                                                    return  $query->whereHas('law_case_license_result_to', function ($query)  use ($filter_status) {
                                                               $query->Where('status_result', $filter_status) ;
                                                           });
                                                }
                                            
                                         })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                                 return    $query->whereHas('law_case_to', function ($query)  use ($filter_tisi_no) {
                                                                $query->Where('tis_id', $filter_tisi_no) ;
                                                             });
                                         })
                                         ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){
                                               return    $query->whereHas('law_case_to', function ($query)  use ($filter_basic_section_id) {
                                                                $query->where(function($query) use($filter_basic_section_id) {
                                                                        $room_count  =  (array)$filter_basic_section_id;
                                                                            if(count($room_count) > 0){
                                                                                $query->whereJsonContains('law_basic_section_id', (string)$room_count[0]);
                                                                                info (count($room_count));
                                                                                for($i = 1; $i <= count($room_count) - 1; $i++) {
                                                                                    $query->orWhereJsonContains('law_basic_section_id',(string)$room_count[$i]);
                                                                                }
                                                                            }else{
                                                                                $query->whereNull('law_basic_section_id');
                                                                            }
                                                                    return $query;
                                                                });
                                                          });
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->whereHas('law_case_to',function($query){
                                                $query->where('lawyer_by', Auth::user()->getKey())
                                                    ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {

                                $lawcases          = $item->law_case_to;
                                $ref_no            = !empty( $lawcases->ref_no )?$lawcases->ref_no:null;
                                $offend_name       = !empty( $lawcases->offend_name )?$lawcases->offend_name:null;
                                $offend_taxid      = !empty( $lawcases->offend_taxid )?$lawcases->offend_taxid:null;
                                $case_number       = !empty( $lawcases->case_number )?$lawcases->case_number:null; 
                                $license_pause     = !empty($item->law_case_license_result_to->license_pause)?$item->law_case_license_result_to->license_pause:null;
                                $date_pause_cancel = !empty( $license_pause->date_pause_cancel )?$license_pause->date_pause_cancel:null; 

                                if( (!empty($item->law_case_license_result_to) && $item->law_case_license_result_to->status_result == 2 && $item->law_case_license_result_to->date_pause_end >= date('Y-m-d'))  && auth()->user()->can('edit-'.$this->permission) ){
                                    $data_input    =  'data-ref_no="'.$ref_no.'"'; 
                                    $data_input    .= 'data-offend_name="'.$offend_name.'"';
                                    $data_input    .= 'data-case_number="'.$case_number.'"';
                                    $data_input    .= 'data-offend_taxid="'.$offend_taxid.'"';
                                    $data_input    .= 'data-offend_taxid="'.$offend_taxid.'"';

                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" '.($data_input).' value="'. $item->id .'">';
                                }
                                
                            })
                            ->addColumn('case_number', function ($item) {
                                $text  = !empty($item->law_case_to->case_number) ? $item->law_case_to->case_number : '';
                                $text  .= !empty($item->law_case_to->ref_no) ? '<br/><span class="text-muted">'.$item->law_case_to->ref_no.'</span>' : '';
                                return $text;
                            })
                            ->addColumn('offend_name', function ($item) {
                                $text  = !empty($item->law_case_to->offend_name) ? $item->law_case_to->offend_name : '';
                                $text  .= !empty($item->law_case_to->offend_taxid) ? '<br/>'.$item->law_case_to->offend_taxid : '';
                                return $text;
                            })
                            ->addColumn('offend_license_number', function ($item) {
                                return  !empty($item->law_case_to->offend_license_number) ? $item->law_case_to->offend_license_number : '';
                            }) 
                            ->addColumn('tis', function ($item) {
                                return  !empty($item->law_case_to->tis->tb3_TisThainame ) ? $item->law_case_to->tis->tb3_TisThainame : '';
                            }) 
                            ->addColumn('law_basic_section', function ($item) {
                                return  !empty($item->law_case_to->section_list)  ? ($item->law_case_to->SectionListName):'N/A'; 
                            })
                            ->addColumn('status_result', function ($item){ 

                                $license_pause  =  !empty($item->law_case_license_result_to->license_pause)?$item->law_case_license_result_to->license_pause:null;

                                $txt = !empty($item->law_case_license_result_to->StatusResultColorHtmlWithDatePause) ? $item->law_case_license_result_to->StatusResultColorHtmlWithDatePause :  '<i class="text-muted">รอดำเนินการ</i>';
                                if( !empty( $license_pause ) && !empty( $license_pause->date_pause_cancel) ){
                                    $txt .= '<div><span class="text-danger">ยกเลิกพักใช้</span></div>';
                                    $txt .= '<div>'.(HP::DateThai($license_pause->date_pause_cancel)).'</div>';
                                }
                                return $txt;
                            })
                            ->addColumn('status_license', function ($item){ 
                                return  !empty($item->law_case_to->StatusLicenseColorHtml) ? $item->law_case_to->StatusLicenseColorHtml :  '<i class="text-muted">รอดำเนินการ</i>';
                           })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->law_case_to->user_lawyer_to->FullName)   ? $item->law_case_to->user_lawyer_to->FullName: '<i class="text-muted">(รอมอบหมาย)</i>';
                            })
                            ->addColumn('action', function ($item) {
                                $html                    = '';
                                $license_pause           = !empty($item->law_case_license_result_to->license_pause)?$item->law_case_license_result_to->license_pause:null;

                                if( auth()->user()->can('add-'.$this->permission) ){
                                    $class               = !empty($item->law_case_license_result_to)?'btn-light-success':' btn-light-warning';
                                    $html                = '<a href="' . url('/law/cases/manage_license/' .$item->id . '/edit') . '"title="แก้ไข" class="btn btn-icon btn-circle '.($class).'"><i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i></a>';
                                }

                                if( (!empty($item->law_case_license_result_to) && $item->law_case_license_result_to->status_result == 2 && $item->law_case_license_result_to->date_pause_end >= date('Y-m-d')) && auth()->user()->can('edit-'.$this->permission)  ){

                                    $lawcases            = $item->law_case_to;
                                    $ref_no              = !empty( $lawcases->ref_no )?$lawcases->ref_no:null;
                                    $offend_name         = !empty( $lawcases->offend_name )?$lawcases->offend_name:null;
                                    $offend_taxid        = !empty( $lawcases->offend_taxid )?$lawcases->offend_taxid:null;
                                    $case_number         = !empty( $lawcases->case_number )?$lawcases->case_number:null; 

                                    $date_pause_cancel   = !empty( $license_pause->date_pause_cancel )?HP::revertDate($license_pause->date_pause_cancel,true):null; 
                                    $remark_pause_cancel = !empty( $license_pause->remark_pause_cancel )?$license_pause->remark_pause_cancel:null; 

                                    $class               = !empty($date_pause_cancel)?'btn-light-success':' btn-light-primary';

                                    $data                =  'data-ref_no="'.($ref_no ).'"';
                                    $data                .= 'data-case_number="'.($case_number ).'"';
                                    $data                .= 'data-offend_name="'.($offend_name ).'"';
                                    $data                .= 'data-offend_taxid="'.($offend_taxid ).'"';
                                    $data                .= 'data-id="'.($item->id ).'"';

                                    $data                .= 'data-date_pause_cancel="'.($date_pause_cancel ).'"';
                                    $data                .= 'data-remark_pause_cancel="'.($remark_pause_cancel ).'"';

                                    $icon                = '<i class="fa fa-clock-o" aria-hidden="true" style="font-size: 1.5em;"></i>';

                                    $html                .= '<button type="button" class="btn btn-icon btn-circle '.($class).' m-l-5 btn_cancel_pause" '.( $data ).'>'.( $icon ).'</button>';
                                }

                                return $html;
                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesResult)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'case_number', 'offend_name', 'status_result', 'status_license', 'lawyer_name', 'action'])
                            ->make(true);
    }

    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage_license",  "name" => 'ดำเนินการกับใบอนุญาต' ],
            ];
            return view('laws.cases.manage_license.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function edit($id)
    {    
        if(auth()->user()->can('edit-'.$this->permission)) {
            $result = LawCasesResult::findOrFail($id);
         
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage_license",  "name" => 'ดำเนินการกับใบอนุญาต (ทางปกครอง)' ],
                [ "link" => "/law/cases/manage_license/$id/edit",  "name" => 'แก้ไข' ],
            ];
            return view('laws.cases.manage_license.edit', compact('result', 'law_notify', 'breadcrumbs'));
        }
        return response(view('403'), 403);
    }


    public function update(Request $request, $id)
    {
        
        if(auth()->user()->can('edit-'.$this->permission)) {
            
            $result = LawCasesResult::findOrFail($id);

            $requestData = $request->all();

            if( !empty($result->law_case_id) && !empty($result->law_case_to) ){
                $case = $result->law_case_to;

                $license_result = LawCasesLicenseResult::updateOrCreate(
                    [  
                        'law_case_id'               => $result->law_case_id
                    ],
                    [
                        'law_case_id'               => $result->law_case_id,

                        //ใบอนุญาต
                        'offend_tb4_tisilicense_id' => !empty($case->offend_tb4_tisilicense_id)?$case->offend_tb4_tisilicense_id:null,
                        'offend_license_number'     => !empty($case->offend_license_number)?$case->offend_license_number:null,

                        //สถานะใบอนุญาต
                        'status_result'             => !empty($requestData['status_result'])?$requestData['status_result']:null,
                        //หมายเหตุ
                        'remark'                    => !empty($requestData['remark'])?$requestData['remark']:null,

                        //พักใช้งาน
                        'date_pause_amount'         => !empty($requestData['date_pause_amount'])?$requestData['date_pause_amount']:null,
                        'date_pause_start'          => !empty($requestData['date_pause_start'])?HP::convertDate($requestData['date_pause_start'],true):null,
                        'date_pause_end'            => !empty($requestData['date_pause_end'])?HP::convertDate($requestData['date_pause_end'],true):null,

                        //เพิกถอน 
                        'date_revoke'               => !empty($requestData['date_revoke'])?HP::convertDate($requestData['date_revoke'],true):null,
                        'basic_revoke_type_id'      => !empty($requestData['basic_revoke_type_id'])?$requestData['basic_revoke_type_id']:null,

                        'created_by'                => auth()->user()->getKey(),
                        'status'                    => 1

                    ]
                );

                //update ตารางหลัก lawcases
                LawCasesForm::where('id', $result->law_case_id)->update(['status_license' => 1]);

                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                // หลักฐานผลการพิจารณา
                if(isset($request->attachs)){
                    if ($request->hasFile('attachs')) {
                        $file = HP::singleFileUploadLaw(
                            $request->file('attachs') ,
                            $this->attach_path,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            ((new LawCasesLicenseResult)->getTable()),
                            $license_result->id,
                            'attachs',
                            'หลักฐานผลการพิจารณา'
                        );
                        if(!is_null($file) && HP::checkFileStorage($file->url)){
                            HP::getFileStoragePath($file->url);
                        }
                    }
                }

                //กรณีพักใช้
                if( in_array($license_result->status_result , [2] )  ){
                    $this->UpdateTb4Pause($case , $license_result);
                    //ลบตารางยกเลิก
                    TisiLicenseCancel::where('tbl_licenseNo', $license_result->offend_license_number )->where('case_number', $case->case_number )->delete();
                }
                //กรณีเพิกถอน
                if( in_array($license_result->status_result , [3] )  ){
                    $this->UpdateTb4Cancel($case , $license_result);
                    //ลบตารางพัก
                    TisiLicensePause::where('tbl_licenseNo', $license_result->offend_license_number )->where('case_number', $case->case_number )->delete();
                }

                $arr_status = LawCasesLicenseResult::status_result_list();
                $remark = array_key_exists($license_result->status_result, $arr_status )?$arr_status[ $license_result->status_result ]:'รอดำเนินการ';
                if(  $license_result->status_result == 2 ){
                    $remark .= ' '.HP::DateThai($license_result->date_pause_start). " ถึง " . HP::DateThai($license_result->date_pause_end).' '.(!empty($license_result->remark)?$license_result->remark:null);
                }else if(  $license_result->status_result == 3 ){
                    $remark .= ' '.HP::DateThai($license_result->date_revoke).' '.(!empty($license_result->remark)?$license_result->remark:null);
                }else{
                    $remark .= !empty($license_result->remark)?$license_result->remark:null;
                }

                HP_Law::InsertLawLogWorking(         
                    1,
                    ((new LawCasesForm)->getTable()),
                    $case->id,
                    $case->ref_no ?? null,
                    'ดำเนินการกับใบอนุญาต',
                    'ดำเนินการกับใบอนุญาต (ทางปกครอง)',
                    $case->status_license == 1?'ดำเนินการเรียบร้อย':'รอดำเนินการ' ,
                    $remark
                );

                // การแจ้งเตือน
                if( !empty($case) &&  (!empty($requestData['email_results']) || !empty($requestData['funnel_system'])) ){

                    //อีเมลที่แจ้งเตือน
                    $email_results = [];
                    foreach(explode(",",$request->email_results)as $email){
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                            $email_results[] = $email;
                        }
                    }

                    // ช่องทางแจ้งเตือน
                    $channels  = [];
                    if(!empty($request->funnel_system)){
                        $channels[] = $request->funnel_system;
                    }
                    if(!empty($request->funnel_email)){
                        $channels[] = $request->funnel_email;
                    }

                    // แจ้งเตือนไปยัง
                    $notify_types  = [];
                    if(!empty($request->owner_email)){
                        $notify_types[] = $request->owner_email;
                    }
                    if(!empty($request->owner_contact_email)){
                        $notify_types[] = $request->owner_contact_email;
                    }
                    if(!empty($request->offend_contact_email)){
                        $notify_types[] = $request->offend_contact_email;
                    }
                    if(!empty($request->reg_email)){
                        $notify_types[] = $request->reg_email;
                    }
                    $url = '<a href="' . url('law/cases/tracks').'">คลิกที่นี่</a>';
                    // ข้อมูล
                    $data_app = [
                                    'case'            => $case,
                                    'result'          => $result,
                                    'url'             => $url,
                                    'license_result'  => $license_result,
                                    'title'           => "e-Legal แจ้งผลการพิจารณาใบอนุญาต ของ $case->offend_name เลขคดี $case->case_number",
                                    'attachs'         => !empty($license_result->FileAttachTo->url)  && is_file('uploads/'.$license_result->FileAttachTo->url) ? $license_result->FileAttachTo->url : ''
                                ];

                    //บันทึก Notify
                    $log_email = HP_Law::getInsertLawNotifyEmail(
                                    1,
                                    ((new LawCasesLicenseResult)->getTable()),
                                    $license_result->id,
                                    'ดำเนินการกับใบอนุญาต(ทางปกครอง)',
                                    "e-Legal แจ้งผลการพิจารณาใบอนุญาต ของ $case->offend_name เลขคดี $case->case_number",
                                    view('mail.Law.Cases.manage_license', $data_app),
                                    (count($channels) > 0 ?  json_encode($channels)  : null),  
                                    (count($notify_types) > 0 ?  json_encode($notify_types)  : null),   
                                    json_encode($email_results)   
                                );

                    if(count($email_results) > 0){
                        $html = new ManageLicense($data_app);
                        Mail::to($email_results)->send($html);
                    }
                }

                
                return redirect('law/cases/manage_license')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
    
            }
        
        }
        return response(view('403'), 403);

    }

    public function UpdateTb4Pause($lawcases , $license_result)
    {
        
        if( !empty($license_result->offend_license_number) && !empty($lawcases->case_number)  ){

            $attach = null;
            if( !empty($license_result->FileAttachTo) ){
                $attach = $license_result->FileAttachTo;
            }

            TisiLicensePause::updateOrCreate(
                [  
                    'tbl_licenseNo'               => $license_result->offend_license_number,
                    'case_number'                 => $lawcases->case_number
                ],
                [
                    //เลขที่ใบอนุญาต
                    'tbl_licenseNo'               => $license_result->offend_license_number,
                    //เลขคดี
                    'case_number'                 => $lawcases->case_number,
                    //ระบบงาน
                    'input_data'                  => 'Law',
                    //วันเริ่มพักใบอนุญาต
                    'date_pause_start'            => !empty($license_result->date_pause_start)?$license_result->date_pause_start:null,
                    //พักถึงวันที่
                    'date_pause_end'              => !empty($license_result->date_pause_end)?$license_result->date_pause_end:null,
                    //หมายเหตุ (ถ้ามี)
                    'remark'                      => !empty($license_result->remark)?$license_result->remark:null,
                    //ไฟล์หลักฐาน
                    'evidence_file'               => !empty( $attach )?$attach->url:null,
                    //ผู้บันทึกพักใบอนุญาต
                    'created_by'                  => auth()->user()->getKey(),

                ]
            );

            //Update Tb4License
            $License = TisiLicense::where( 'tbl_licenseNo', $license_result->offend_license_number)->update(['tbl_licenseStatus'=> '0', 'tbl_license_Inative' => '1']);
        }

    }

    public function UpdateTb4Cancel($lawcases , $license_result)
    {
        if( !empty($license_result->offend_license_number) && !empty($lawcases->case_number) ){

            $tb4_tisilicense = $license_result->tb4_tisilicense;
            $tis             = !empty($tb4_tisilicense->tis)?$tb4_tisilicense->tis:null;
            $trader          = !empty($tb4_tisilicense->user)?$tb4_tisilicense->user:null;

            //ผู้บันทึก
            $user            = auth()->user();
            $subdepart       = $user->subdepart;
            $depart          = !empty($subdepart->department)?$subdepart->department:null;

            $attach = null;
            if( !empty($license_result->FileAttachTo) ){
                $attach = $license_result->FileAttachTo;
            }

            TisiLicenseCancel::updateOrCreate(
                [  
                    'tbl_licenseNo'               => $license_result->offend_license_number,
                    'case_number'                 => $lawcases->case_number
                ],
                [
                    //เลขที่ใบอนุญาต
                    'tbl_licenseNo'               => $license_result->offend_license_number,
                    //มอก.
                    'tbl_tisiNo'                  => !empty($tis->tb3_Tisno)?$tis->tb3_Tisno:null,
                    //เลขคดี
                    'case_number'                 => $lawcases->case_number,
                    //ระบบงาน
                    'input_data'                  => 'Law',
                    //ผู้ประกอบการ
                    'tbl_tradeName'               => !empty($trader->name)?$trader->name:null,
                    //วันที่แจ้งยกเลิก
                    'tbl_cancelDate'              => !empty($license_result->date_revoke)?$license_result->date_revoke:null,
                    //กองที่แจ้ง
                    'tbl_depart'                  => !empty($depart->depart_name)?$depart->depart_name:null,
                    //กลุ่มที่แจ้ง
                    'tbl_sub'                     => !empty($subdepart->sub_departname)?$subdepart->sub_departname:null,
                    //เหตุผลที่ยกเลิก
                    'reason_code'                 => !empty($license_result->basic_revoke_type_id)?$license_result->basic_revoke_type_id:null,
                    //หมายเหตุ
                    'tbl_reason'                  => !empty($license_result->remark)?$license_result->remark:null,
                    //ไฟล์ขอแจ้งยกเลิก
                    'pdf_path'                    => !empty( $attach )?$attach->url:null,
                    //ผู้ทำการยกเลิก
                    'crby'                        => $user->FullName,
                    //วันที่ทำการยกเลิก
                    'date_create'                 => Carbon::now()

                ]
            );  

            //Update Tb4License
            $License = TisiLicense::where( 'tbl_licenseNo', $license_result->offend_license_number)->update(['tbl_licenseStatus'=> '0', 'tbl_license_Inative' => '3']);

        }
    }

    public function update_cancel_cancel(Request $request)
    {
        $requestData = $request->all();

        $ids = $requestData['id'];

        $msg = 'error';

        if( is_array($ids) ){
            
            $data = LawCasesResult::whereIn( 'id', $ids )->get();

            foreach(  $data AS $result  ){
                if( !empty($result->law_case_id) && !empty($result->law_case_to) ){

                    $lawcases       = $result->law_case_to;
                    $license_result = $result->law_case_license_result_to;

                    if( empty( $license_result->license_pause) ){
                        $tb4_tisilicense = $license_result->tb4_tisilicense;
                        $tis             = !empty($tb4_tisilicense->tis)?$tb4_tisilicense->tis:null;
                        $trader          = !empty($tb4_tisilicense->user)?$tb4_tisilicense->user:null;
            
                        //ผู้บันทึก
                        $user            = auth()->user();
                        $subdepart       = $user->subdepart;
                        $depart          = !empty($subdepart->department)?$subdepart->department:null;
            
                        $attach = null;
                        if( !empty($license_result->FileAttachTo) ){
                            $attach = $license_result->FileAttachTo;
                        }
            
                        $Pause =  TisiLicensePause::updateOrCreate(
                            [  
                                'tbl_licenseNo'               => $license_result->offend_license_number,
                                'case_number'                 => $lawcases->case_number
                            ],
                            [
                                //เลขที่ใบอนุญาต
                                'tbl_licenseNo'               => $license_result->offend_license_number,
                                //เลขคดี
                                'case_number'                 => $lawcases->case_number,
                                //ระบบงาน
                                'input_data'                  => 'Law',
                                //วันเริ่มพักใบอนุญาต
                                'date_pause_start'            => !empty($license_result->date_pause_start)?$license_result->date_pause_start:null,
                                //พักถึงวันที่
                                'date_pause_end'              => !empty($license_result->date_pause_end)?$license_result->date_pause_end:null,
                                //หมายเหตุ (ถ้ามี)
                                'remark'                      => !empty($license_result->remark)?$license_result->remark:null,
                                //ไฟล์หลักฐาน
                                'evidence_file'               => !empty( $attach )?$attach->url:null,
                                //ผู้บันทึกพักใบอนุญาต
                                'created_by'                  => auth()->user()->getKey(),

                                //ยกเลิกพักใช้
                                'date_pause_cancel'           => !empty($requestData['date_pause_cancel'])?HP::convertDate($requestData['date_pause_cancel'],true):null,
                                'remark_pause_cancel'         => !empty($requestData['remark_pause_cancel'])?$requestData['remark_pause_cancel']:null,
                                'pause_cancel_by'             => auth()->user()->getKey(),
                                'pause_cancel_at'             => Carbon::now(),
            
                            ]
                        );  

                    }else{

                        //Update ตารางพักใช้
                        $Pause = TisiLicensePause::where('tbl_licenseNo', $license_result->offend_license_number )
                                        ->where('case_number', $lawcases->case_number )
                                        ->update([
                                            'date_pause_cancel'    => !empty($requestData['date_pause_cancel'])?HP::convertDate($requestData['date_pause_cancel'],true):null,
                                            'remark_pause_cancel'  => !empty($requestData['remark_pause_cancel'])?$requestData['remark_pause_cancel']:null,
                                            'pause_cancel_by'      => auth()->user()->getKey(),
                                            'pause_cancel_at'      => Carbon::now(),
                                        ]);
                    }
                    //Update Tb4License
                    $License = TisiLicense::where( 'tbl_licenseNo', $license_result->offend_license_number)->update(['tbl_licenseStatus'=> '1', 'tbl_license_Inative' => null ]);

                    $date_pause_cancel   = !empty($requestData['date_pause_cancel'])?HP::convertDate($requestData['date_pause_cancel'],true):null;
                    $remark_pause_cancel = !empty($requestData['remark_pause_cancel'])?$requestData['remark_pause_cancel']:null;
                    $remark              = 'ยกเลิกพักใช้ เมื่อวันที่ '.(HP::DateThai(  $date_pause_cancel )).' '.($remark_pause_cancel);

                    HP_Law::InsertLawLogWorking(         
                        1,
                        ((new LawCasesForm)->getTable()),
                        $lawcases->id,
                        $lawcases->ref_no ?? null,
                        'ดำเนินการกับใบอนุญาต',
                        'ดำเนินการกับใบอนุญาต (ทางปกครอง)',
                        $lawcases->status_license == 1?'ดำเนินการเรียบร้อย':'รอดำเนินการ' ,
                        $remark
                    );
    

                    $msg = 'success';

                }
            }
        }
        return response()->json($msg);
    }

}
