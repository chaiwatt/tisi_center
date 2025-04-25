<?php

namespace App\Http\Controllers\Laws\Cases;

use HP;
use HP_Law;
use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use App\Models\Basic\Tis;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Asurv\EsurvTers21;
use App\Models\Basic\TisiLicense;

use App\Models\Besurv\Department;
use App\Models\Csurv\ControlCheck;
use Illuminate\Support\Facades\DB;
use App\Models\Basic\SubDepartment;
use Collective\Html\FormFacade as Form;
use App\Http\Controllers\Controller;

use App\Models\Law\Basic\LawSection;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Mail;
use App\Models\Config\ConfigsEvidence;
use App\Models\Law\Cases\LawCasesForm;


use App\Models\Law\File\AttachFileLaw;

use App\Models\Law\Basic\LawDepartment;
use Illuminate\Support\Facades\Storage;
use App\Models\Csurv\ControlPerformance;
use App\Models\Law\Basic\LawRewardGroup;  
use App\Models\Law\Cases\LawCasesImpound;
use App\Models\Law\Track\LawTrackReceive;
use App\Models\Law\Cases\LawCasesLicenses;
use App\Models\Law\Cases\LawCasesStandard;
 
use App\Models\Law\Config\LawConfigReward;
use App\Models\Config\ConfigsEvidenceGroup;
use App\Models\Law\Cases\LawCasesStaffList;
use App\Mail\Mail\Law\Cases\MailCasesConfig;
use Symfony\Component\Console\Helper\Helper;
use App\Mail\Mail\Law\Cases\MailCasesApprove;
use App\Models\Law\Config\LawConfigRewardSub;

use App\Models\Law\Cases\LawCasesLevelApprove;
use App\Models\Law\Config\LawConfigEmailNotis;
use App\Models\Law\Cases\LawCasesImpoundProduct;

class LawCasesFormController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/cases/forms/';
    }

    public function data_list(Request $request)
    {
        $filter_condition_search          = $request->input('filter_condition_search');
        $filter_search                    = $request->input('filter_search');
        $filter_status                    = $request->input('filter_status') ;
        $filter_status                    = $filter_status == '0' ? '-1':$filter_status;
        $filter_tisno                     = $request->input('filter_tisno');
        $filter_violate_section           = $request->input('filter_violate_section');
        $filter_date_impound              = $request->input('filter_date_impound');
        $filter_offend_date               = $request->input('filter_offend_date'); 
        $filter_deperment_type            = $request->input('filter_deperment_type');
        $filter_owner_basic_department_id = $request->input('filter_owner_basic_department_id');      
        $filter_owner_sub_department_id   = $request->input('filter_owner_sub_department_id');

        $rights                           = !empty(auth()->user()->subdepart->CheckRight) ? auth()->user()->subdepart->CheckRight : [];
        $model                            = str_slug('law-cases-forms','-');
        //ผู้ใช้งาน
        $user = auth()->user();
       
        $query = LawCasesForm::query()
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "2":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "3":
                                                return $query->where(function($query) use ($search_full){
                                                                        $query->where(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                            default:
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_tisno, function ($query, $filter_tisno){
                                        return   $query->where('tis_id', $filter_tisno);
                                    })
                                    ->when($filter_violate_section, function ($query, $filter_violate_section){
                                            foreach($filter_violate_section as $item){
                                                return $query->whereJsonContains('law_basic_section_id', $item);
                                            }
                                    })
                                    ->when($filter_date_impound, function ($query, $filter_date_impound){
                                        return $query->where('date_impound',  HP::convertDate($filter_date_impound, true));
                                    })
                                    ->when($filter_offend_date, function ($query, $filter_offend_date){
                                        return $query->where('offend_date',  HP::convertDate($filter_offend_date, true));
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if($filter_status == '-1'){
                                           return  $query->where('status',strval(0));
                                        }else{
                                          return  $query->where('status',$filter_status);
                                        }
                                    })
                                    ->when($filter_deperment_type, function ($query, $filter_deperment_type){
                                        $query->where('owner_depart_type', $filter_deperment_type);
                                    })
                                    ->when($filter_owner_basic_department_id, function ($query, $filter_owner_basic_department_id){
                                        $query->where('owner_basic_department_id', $filter_owner_basic_department_id);
                                    })
                                    ->when($filter_owner_sub_department_id, function ($query, $filter_owner_sub_department_id){
                                        $query->where('owner_sub_department_id', $filter_owner_sub_department_id);
                                    })
                                    ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($rights) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                        $query->where(function($query) use ($rights){ 
                                            if(is_array(auth()->user()->RoleListId) && (in_array('7',auth()->user()->RoleListId) || in_array('5',auth()->user()->RoleListId)) ){ // ผก.
                                                if(!in_array('All',$rights)){
                                                    return  $query->with(array('cases_standards' => function($query2)  use ($rights) {
                                                                    return  $query2->WhereIn('tb3_tisno', $rights);
                                                                }))->whereHas('cases_standards', function ($query2) use ($rights) {
                                                                        return  $query2->WhereIn('tb3_tisno', $rights);
                                                                });  
                                                }
                                            }else{ // ผู้บันทึก
                                                $query->where('created_by', Auth::user()->getKey()); 
                                            }
                                        });            
                                });
 
 
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('ref_no', function ($item) {
                                $text   =  $item->ref_no;
                                $text  .= !empty($item->case_number)?'<div>('.$item->case_number.')</div>':'';
                                if(count($item->law_log_working_bounce_many) > 0){
                                    $data_log_working = base64_encode(json_encode($item->WorkingBounceList));
                                    $text .= '<div class="bounce tip view-log-modal" data-log="'.$data_log_working.'" data-ref_no="'.$item->ref_no.'">[ประวัติแจ้งแก้ไข]<span class="tooltiptext">คลิกเพื่อดูประวัติการแก้ไข</span> </div>';
                                 }
                                return $text;
                            })
                            ->addColumn('name_taxid', function ($item) {
                                return $item->NameAndTaxid;
                            })
                            ->addColumn('tis_license_no', function ($item) {
                                return $item->TisnoAndLicenseNumber;
                            })
                            ->addColumn('law_basic_section', function ($item) {
                                return $item->SectionListName;
                            })
                            ->addColumn('owner_name', function ($item) {
                                return $item->owner_name.'<br>'.($item->ShortnameDepartment).('<div class="text-muted">('.(@$item->DeparmentTypeName).')</div>');

                            })
                            ->addColumn('created_by', function ($item) {
                                return  !empty($item->created_at) && !empty($item->CreatedName)   ? $item->CreatedName.'<br/>'.HP::DateTimeThaiPipe($item->created_at):null;
                            })
                            ->addColumn('status', function ($item) {
                                $status_close = '';
                                if(!empty($item->status_close)&&$item->status_close==1){
                                    $status_close = '<br><u>(ปิดงานคดี)</u>';
                                }

                                if($item->status=='99'){
                                    $html = '<button type="button" class="close_the_case" style="border: none;background-color: #ffffff;"  
                                                data-id="'.$item->id.'"     
                                                data-status="'.$item->StatusText.'"   
                                                data-cancel_remark="'.$item->cancel_remark.'"   
                                                data-cancel_at="'.(!empty($item->cancel_at)?HP::DateThai($item->cancel_at):null).'"     
                                                data-owner_taxid="'.$item->owner_taxid.'">
                                                <i class="fa fa-close text-danger"></i> '.$item->StatusText.'
                                            </button>';
                                    return $html.$status_close;
                                }else{
                                    return $item->StatusColorHtml.$status_close;
                                }
                            })
                            ->addColumn('approve', function ($item) {
                                if($item->approve_type == 1){
                                    $data_input =   'data-id="'.($item->id).'"';
                                    return '<a  href="javascript:void(0)" class="show_approve"  '.( $data_input ).' >'.$item->ApproveTypeText.'<br><i class="text-muted">'.$item->CsesLevelApproveRole.'</i></a>';
                                }else{
                                    return  !empty($item->ApproveTypeText) ? $item->ApproveTypeText:'ยังไม่ส่งเรื่องพิจารณา';
                                }
                            })
                            ->addColumn('action', function ($item)  {     
                                return HP::buttonActionLawCasesform( $item->id, 'law/cases/forms','Laws\Cases\\LawCasesFormController@destroy', 'law-cases-forms',true, true, true, false, $item->status);
                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesForm)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by', 'name_taxid', 'tis_license_no','law_basic_section','ref_no','approve','owner_name'])
                            ->make(true);
    }

    public function data_department(Request $request)
    {
        $law_case_impound_id     = $request->input('law_case_impound_id');

        $query = LawCasesStaffList::query()
                              ->where('law_case_impound_id', $law_case_impound_id);
                        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('name', function ($item) {
                                return $item->name;
                            })
                            ->addColumn('address', function ($item) {
                                return $item->address;
                            })
                            ->addColumn('department', function ($item) {
                                return $item->sub_department->sub_depart_shortname??'n/a';
                            })
                            ->addColumn('reward_group', function ($item) {
                                return $item->StatusText;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'law/cases/forms','Laws\Cases\\LawCasesFormController@destroy', 'law-cases-forms', false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['address', 'department', 'action'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/forms",  "name" => 'แจ้งงานคดี' ],
            ];
 
            return view('laws.cases.forms.index',compact('breadcrumbs'));
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
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/forms",  "name" => 'แจ้งงานคดี' ],
                [ "link" => "/law/cases/forms/create",  "name" => 'เพิ่ม' ],
            ];

            $configs_evidences = ConfigsEvidence::leftjoin((new ConfigsEvidenceGroup)->getTable().' AS evidence_groups', 'evidence_groups.id', '=', 'configs_evidences.evidence_group_id')
            ->select('configs_evidences.*')
            ->where('configs_evidences.evidence_group_id', 6)
            ->where('configs_evidences.state', 1)
            ->where('evidence_groups.state', 1)
            ->orderBy('configs_evidences.ordering')
            ->get();

            return view('laws.cases.forms.create',compact('breadcrumbs', 'configs_evidences'));
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
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData                     = $request->all();
      
            $submit_type = 1;   // บันทึก
            if( isset($requestData['submit_type']) && $requestData['submit_type'] == 2 ){
                $submit_type = 2;  // ฉบับร่าง
            }

            $user = auth()->user();
            $login_full_name = $user->reg_fname.' '.$user->reg_lname;

            //เลขรัน
            $running_no =  HP::ConfigFormat('LawCasesForm', (new LawCasesForm)->getTable(), 'ref_no', null, null, null);
            $check = LawCasesForm::where('ref_no', $running_no)->first();
            if(!is_null($check)){
                $running_no =  HP::ConfigFormat('LawCasesForm', (new LawCasesForm)->getTable(), 'ref_no', null, null, null);
            }

            if(!empty($request->input('offend_power')) && is_array($request->input('offend_power'))){
                $offend_power = array_diff($request->input('offend_power'), [null]);
                $requestData['offend_power'] = !empty($offend_power)?$offend_power:null;
            }
            
            $requestData['ref_no']                    =  !empty($running_no)?$running_no:'-';
            $requestData['owner_department_name']     = !empty($request->input('owner_department_name'))? $request->input('owner_department_name'):$user->subdepart->sub_depart_shortname;
            $requestData['owner_sub_department_id']   = !empty($request->input('owner_sub_department_id'))? $request->input('owner_sub_department_id'):null;
            $requestData['owner_basic_department_id'] = !empty($request->input('owner_basic_department_id'))? $request->input('owner_basic_department_id'):null;
            $requestData['owner_case_by']             = !empty($request->input('owner_case_by'))? $request->input('owner_case_by'):$user->runrecno;
            $requestData['owner_name']                = !empty($request->input('owner_name'))? $request->input('owner_name'):$login_full_name;
            $requestData['owner_email']               = !empty($request->input('owner_email'))? $request->input('owner_email'):$user->reg_email;
            $requestData['owner_taxid']               = !empty($request->input('owner_taxid'))? $request->input('owner_taxid'):str_replace("-","", $user->reg_13ID);
            $requestData['owner_tel']                 = !empty($request->input('owner_tel'))? $request->input('owner_tel'):$user->reg_wphone;
            $requestData['owner_phone']               = !empty($request->input('owner_phone'))? $request->input('owner_phone'):$user->reg_phone;
            $requestData['owner_contact_options']     = !empty($request->input('owner_contact_options'))? $request->input('owner_contact_options'):null;
            $requestData['owner_contact_name']        = !empty($request->input('owner_contact_name'))? $request->input('owner_contact_name'):$login_full_name;
            $requestData['owner_contact_phone']       = !empty($request->input('owner_contact_phone'))? $request->input('owner_contact_phone'):$user->reg_phone;
            $requestData['owner_contact_email']       = !empty($request->input('owner_contact_email'))? $request->input('owner_contact_email'):$user->reg_email;

            $requestData['offend_date']               = !empty($requestData['offend_date'])?HP::convertDate( $requestData['offend_date'],true):null;
            $requestData['offend_report_date']        = !empty($requestData['offend_report_date'])?HP::convertDate( $requestData['offend_report_date'],true):null;
            $requestData['offend_accept_date']        = !empty($requestData['offend_accept_date'])?HP::convertDate( $requestData['offend_accept_date'],true):null;
            $requestData['foreign']                   = isset($request->foreign) ?  '1' : '0';

            if($submit_type == 2){ 
                $requestData['status'] = '0'; //ฉบับร่าง
            }else if($requestData['approve_type'] == 1){ 
                $requestData['status'] =  98; //แจ้งงานคดีสำเร็จ(รอผู้มีอำนาจพิจารณา)
                $requestData['status_approve'] =  '1'; 
            }else{
                $requestData['status'] =  1; //แจ้งงานคดีสำเร็จ
            }
            
            // ใบอนุญาต
            if(!empty($requestData['licenses'])){   
                $licenses =  $requestData['licenses'];
                if(!empty($licenses)){   
                    $requestData['offend_license_number']     =    !empty($licenses['license_number'][0])? $licenses['license_number'][0] :null;  
                }
            }
   
           // มาตรฐานผลิตภัณฑ์อุตสาหกรรม
           if(!empty($requestData['standard'])){   
                $standard =  $requestData['standard'];
                if(!empty($standard)){   
                   $requestData['tb3_tisno']     =    !empty($standard['tb3_tisno'][0])? $standard['tb3_tisno'][0] :null;  
                }
           }
   
            $lawcasesform =   LawCasesForm::create($requestData);

            // ใบอนุญาต
            if(!empty($requestData['licenses'])){   
                $licenses =  $requestData['licenses'];
                if(!empty($licenses)){   
                    self::law_case_licenses($lawcasesform,$licenses); 
                }
             }
            // มาตรฐานผลิตภัณฑ์อุตสาหกรรม
            if(!empty($requestData['standard'])){   
                $standard =  $requestData['standard'];
                if(!empty($standard)){   
                    self::law_case_standard($lawcasesform,$standard); 
                }
             }

            //รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
            $this->SaveStaff($lawcasesform, $requestData);
            //รายการผลิตภัณฑ์ตรวจยึด-อายัด (ของกลาง)
            $this->SaveImpound($lawcasesform, $requestData);
            //บันทึกไฟล์
            $this->SaveFile($lawcasesform, $request);
            //ส่วนที่ 6 : การพิจารณา
            $this->SaveLevelApprove($lawcasesform, $requestData);
        
            
            try{//ส่งเมลให้ผู้รับคำขอตามตั้งค่า
                if($lawcasesform->status != '98'){
                    $this->send_mail($lawcasesform);
                }
            } catch (\Exception $e) {
                return redirect('law/cases/forms')->with('message_error', 'เกิดข้อผิดพลาดส่งเมลให้ผู้รับคำขอ');
            }
            return redirect('law/cases/forms')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function SaveLevelApprove( $lawcases, $requestData )   //ส่วนที่ 6 : การพิจารณา
    {

        $lawcaselevelapproves =  LawCasesLevelApprove::where('law_cases_id',$lawcases->id);
        if(!empty($lawcaselevelapproves->get()) && $lawcaselevelapproves->count() > 0){
            $lawcaselevelapproves->delete();
        }

        if( isset($requestData['repeater-approve']) && $lawcases->approve_type == '1'){ //ขออนุมัติผ่านระบบ
            $list = $requestData['repeater-approve'];
            
            foreach( $list as $key=>$item ){
            $user_register        =  User::where('runrecno',$item['authorize_userid'])->first();

                $lawcaselevelapproves                    = new LawCasesLevelApprove;
                $lawcaselevelapproves->created_by        = auth()->user()->getKey();
                $lawcaselevelapproves->law_cases_id      = $lawcases->id;
                $lawcaselevelapproves->level             = $key+1;
                $lawcaselevelapproves->status            = ($lawcaselevelapproves->level == '1')?'2':'1';
                $lawcaselevelapproves->role              = !empty( $item['role'])?$item['role']:null;
                $lawcaselevelapproves->send_department   = !empty( $item['send_department'])?$item['send_department']:null;
                $lawcaselevelapproves->authorize_name    = !empty( $user_register->FullName)?$user_register->FullName:null;
                $lawcaselevelapproves->position          = !empty( $item['position'])?$item['position']:null;
                $lawcaselevelapproves->acting            = isset( $item['acting'])?1:0;
                $lawcaselevelapproves->authorize_userid  = !empty( $item['authorize_userid'])?$item['authorize_userid']:null;
                $lawcaselevelapproves->save();

                //ส่งเมล
                if($lawcaselevelapproves->level == 1 &&  $lawcases->status == '98' ){
                    $email = !empty($user_register->reg_email)?$user_register->reg_email:null;
                    if(filter_var($email, FILTER_VALIDATE_EMAIL) && $lawcases->status != '0'){
                            if($lawcases->approve_type == '1'){
                                $url  =  url('/law/cases/forms_approved');
                            }else{
                                $url  =  url('/law/cases/assigns/'.$lawcases->id);
                            }
                            // ข้อมูล
                            $data_app = [
                                        'url' => $url,
                                        'lawcasesform' => $lawcases,
                                        'title'        => 'ขอให้พิจารณาข้อมูลงานคดี ของ '.(!empty($lawcases->offend_name)?$lawcases->offend_name:null).' เลขอ้างอิง'.(!empty($lawcases->ref_no)?$lawcases->ref_no:null),
                                        'authorize_name'=> (!empty( $user_register->FullName)?$user_register->FullName:null)
                                    ];
            
                        HP_Law::getInsertLawNotifyEmail(1,
                                                        ((new LawCasesForm)->getTable()),
                                                        $lawcases->id,
                                                        'แจ้งงานคดี',
                                                        'ขอให้พิจารณาข้อมูลงานคดี ของ '.(!empty($lawcases->offend_name)?$lawcases->offend_name:null).' เลขอ้างอิง'.(!empty($lawcases->ref_no)?$lawcases->ref_no:null),
                                                        view('mail.Law.Cases.cases-forms-approve', $data_app),
                                                        null,  
                                                        null,   
                                                        $email   
                                                        );
            
                        $html = new MailCasesApprove($data_app);
                        Mail::to($email)->send($html);
                    }
                }
          
            }
       
        }

        

    }


    public function SaveStaff( $lawcases, $requestData )
    {
        if( isset($requestData['staff-list']) ){

            $staff_lists = $requestData['staff-list'];

            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            $folder_app = $lawcases->ref_no;

            $list_ids = [];

            foreach( $staff_lists as $items ){
                if( !empty($items['id']) ){
                    $list_ids[] = $items['id'];
                }
            }

            LawCasesStaffList::where('law_cases_id', $lawcases->id )->whereNotIn('id', $list_ids)->delete();
      
            foreach( $staff_lists as  $items ){
               
                    $staff                          =  LawCasesStaffList::where('law_cases_id',$lawcases->id)->where('id', @$items['id'])->first();
                if(is_null($staff)){
                    $staff                          = new LawCasesStaffList;
                    $staff->created_by              = auth()->user()->getKey();
                }else{
                    $staff->updated_by              = auth()->user()->getKey();  
                }
                    $staff->law_cases_id          = $lawcases->id;
                    $staff->depart_type           =!empty($items['depart_type']) ? $items['depart_type'] : null;
                    $staff->basic_reward_group_id = !empty($items['basic_reward_group_id']) ? $items['basic_reward_group_id'] : null;
                    $staff->sub_department_id     = !empty($items['sub_department_id']) ? $items['sub_department_id'] : null;
                    $staff->basic_department_id   = !empty($items['basic_department_id']) ? $items['basic_department_id'] : null;
                    $staff->department_name       = !empty($items['department_name']) ? $items['department_name'] : null;
                    $staff->name                  = !empty($items['name']) ? $items['name'] : null;
                    $staff->taxid                 = !empty($items['taxid']) ?  (int)$items['taxid'] : null;
                    $staff->address               = !empty($items['address']) ? $items['address'] : null;
                    $staff->mobile                = !empty($items['mobile']) ? $items['mobile'] : null;
                    $staff->email                 = !empty($items['email']) ? $items['email'] : null;
                    $staff->basic_bank_id         = !empty($items['basic_bank_id']) ? $items['basic_bank_id'] : null;
                    $staff->bank_account_name     = !empty($items['bank_account_name']) ? $items['bank_account_name'] : null;
                    $staff->bank_account_number   = !empty($items['bank_account_number']) ?  str_replace("(","", str_replace(")","", $items['bank_account_number'] )) : null;
                    $staff->save();
             
                // $staff =  LawCasesStaffList::updateOrCreate(
                //     [
                //         'id'                    => $items['id'],
                //         'law_cases_id'          => $lawcases->id
                //     ],
                //     [
                //         'law_cases_id'          => $lawcases->id,
                //         'depart_type'           => $items['depart_type'],

                //         'basic_reward_group_id' => !empty($items['basic_reward_group_id'])?$items['basic_reward_group_id']:null,

                //         'sub_department_id'     => !empty($items['sub_department_id'])?$items['sub_department_id']:null,
                //         'basic_department_id'   => !empty($items['basic_department_id'])?$items['basic_department_id']:null,
                //         'department_name'       => !empty($items['department_name'])?$items['department_name']:null,

                //         'name'                  => !empty($items['name'])?$items['name']:null,
                //         'taxid'                 => !empty($items['taxid'])?$items['taxid']:null,
                //         'address'               => !empty($items['address'])?$items['address']:null,
                //         'mobile'                => !empty($items['mobile'])?$items['mobile']:null,
                //         'email'                 => !empty($items['email'])?$items['email']:null,

                //         'basic_bank_id'         => !empty($items['basic_bank_id'])?$items['basic_bank_id']:null,
                //         'bank_account_name'     => !empty($items['bank_account_name'])?$items['bank_account_name']:null,
                //         'bank_account_number'   => !empty($items['bank_account_number'])?$items['bank_account_number']:null,

                //         'created_by'            => auth()->user()->getKey(),
                //     ]
                // );

                if( !empty($items['file']) ){
                    HP::CopyFile(
                        $items['file'] ,
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawCasesStaffList)->getTable() ),
                        $staff->id,
                        'law_book_bank',
                        'ไฟล์สมุดบัญชี'
                    );
                }

            }

        }else{
            LawCasesStaffList::where('law_cases_id', $lawcases->id )->delete();
        }
    }


    public function SaveImpound(  $lawcases, $requestData  )
    {

        if( $requestData['offend_impound_type'] == 1){
            $lawcasesimpound = LawCasesImpound::updateOrCreate(
                [
                    'law_case_id' => $lawcases->id,
                ],
                [
                    'law_case_id'               => $lawcases->id,
                    'impound_status'            => !empty($requestData['offend_impound_type'])?$requestData['offend_impound_type']:null,
                    'date_impound'              => !empty($requestData['date_impound']) ?  HP::convertDate( $requestData['date_impound'],true) : null,
                    'same_product'              => !empty($requestData['same_product']) ? '1' : '0',
                    'ref_id'                    => !empty($requestData['ref_id'])?$requestData['ref_id']:null,
                    'location'                  => !empty($requestData['location'])?$requestData['location']:null,
                    'storage_name'              => !empty($requestData['storage_name'])?$requestData['storage_name']:null,
                    'storage_address_no'        => !empty($requestData['storage_address_no'])?$requestData['storage_address_no']:null,
                    'storage_soi'               => !empty($requestData['storage_soi'])?$requestData['storage_soi']:null,
                    'storage_moo'               => !empty($requestData['storage_moo'])?$requestData['storage_moo']:null,
                    'storage_street'            => !empty($requestData['storage_street'])?$requestData['storage_street']:null,
                    'storage_subdistrict_id'    => !empty($requestData['storage_subdistrict_id'])?$requestData['storage_subdistrict_id']:null,
                    'storage_district_id'       => !empty($requestData['storage_district_id'])?$requestData['storage_district_id']:null,
                    'storage_province_id'       => !empty($requestData['storage_province_id'])?$requestData['storage_province_id']:null,
                    'storage_zipcode'           => !empty($requestData['storage_zipcode'])?$requestData['storage_zipcode']:null,
                    'storage_tel'               => !empty($requestData['storage_tel'])?$requestData['storage_tel']:null,
                    'law_basic_resource_id'     => !empty($requestData['law_basic_resource_id'])?$requestData['law_basic_resource_id']:null,
                    'total_value'               => !empty($requestData['total_value'])?str_replace(",","",$requestData['total_value']):null
        
                ]
            );

            if( !is_null($lawcasesimpound) ){
                
                if(!empty($requestData['inner-list']) && is_array($requestData['inner-list']) && count($requestData['inner-list']) > 0){

                    $inner_lists = $requestData['inner-list'];

                    $list_id_data = [];
                    foreach($inner_lists as $inner_list){
                        if(isset($inner_list['impound_product_id'])){
                            $list_id_data[] = $inner_list['impound_product_id'];
                        }
                    }
                    $lists_id = array_diff($list_id_data, [null]);
        
                    $law_case_impound_products_old =  LawCasesImpoundProduct::where('law_case_impound_id',$lawcasesimpound->id)
                                                ->when($lists_id, function ($query, $lists_id){
                                                    return $query->whereNotIn('id', $lists_id);
                                                });
                    
                    if(!empty($law_case_impound_products_old->get()) && count($law_case_impound_products_old->get()) > 0){
                        //ลบข้อมูลเดิม
                        $law_case_impound_products_old->delete();
                    }

                    foreach( $inner_lists as $product ){
             
                        $law_case_impound_products =  LawCasesImpoundProduct::where('id',$product['impound_product_id'])->first();
                        if(is_null($law_case_impound_products)){
                            $law_case_impound_products             = new LawCasesImpoundProduct;
                            $law_case_impound_products->created_by = auth()->user()->getKey();
                            $law_case_impound_products->created_at =  date('Y-m-d H:i:s');
                        }else{
                            $law_case_impound_products->updated_by = auth()->user()->getKey();
                            $law_case_impound_products->updated_at =  date('Y-m-d H:i:s');
                        }
                        $law_case_impound_products->law_case_impound_id = $lawcasesimpound->id;
                        $law_case_impound_products->detail = !empty($product['detail'])?$product['detail']:null;
                        $law_case_impound_products->amount_impounds = !empty($product['amount_impounds'])?$product['amount_impounds']:'0';
                        $law_case_impound_products->amount_keep = !empty($product['amount_keep'])?$product['amount_keep']:'0';
                        $law_case_impound_products->unit = !empty($product['unit'])?$product['unit']:null;
                        $law_case_impound_products->total = !empty($product['total'])?$product['total']:'0';
                        $law_case_impound_products->price = !empty($product['price'])?str_replace(",","",$product['price']):'0';
                        $law_case_impound_products->total_price = !empty($product['total_price'])?str_replace(",","",$product['total_price']):'0';
                        $law_case_impound_products->save();
    
                    }

                    // foreach( $inner_lists as $product ){

                    //     LawCasesImpoundProduct::updateOrCreate(
                    //         [  
                    //             'id'                 => $product['impound_product_id']
                    //         ],
                    //         [
                    //             'law_case_impound_id' => $lawcasesimpound->id,
                    //             'detail'              => !empty($product['detail'])?$product['detail']:null,
                    //             'amount_impounds'     => !empty($product['amount_impounds'])?$product['amount_impounds']:'0',
                    //             'amount_keep'         => !empty($product['amount_keep'])?$product['amount_keep']:'0',
                    //             'unit'                => !empty($product['unit'])?$product['unit']:null,
                    //             'total'               => !empty($product['total'])?$product['total']:'0',
                    //             'price'               => !empty($product['price'])?str_replace(",","",$product['price']):'0',
                    //             'total_price'         => !empty($product['total_price'])?str_replace(",","",$product['total_price']):'0',
                    //             'updated_by'          => auth()->user()->getKey(),
                    //             'updated_at'          => date('Y-m-d H:i:s')
                    //         ]
                    //     );
                        
                    // }

                }

            }
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
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('view-'.$model)) {
            // $config = LawConfigEmailNotis::whereNotNull('email_list')->where('id',1)->first();

            $lawcasesform = LawCasesForm::findOrFail($id);
            $lawcasesform->offend_date = !empty($lawcasesform->offend_date) ? HP::revertDate($lawcasesform->offend_date, true) : null;
            $breadcrumbs = [
                ["link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home"],
                ["link" => "/law/cases/forms",  "name" => 'แจ้งงานคดีผลิตภัณฑ์อุตสาหกรรม'],
                ["link" => "/law/cases/forms/$id",  "name" => 'รายละเอียด'],
            ];

            $configs_evidences = ConfigsEvidence::leftjoin((new ConfigsEvidenceGroup)->getTable() . ' AS evidence_groups', 'evidence_groups.id', '=', 'configs_evidences.evidence_group_id')
                ->select('configs_evidences.*')
                ->where('configs_evidences.evidence_group_id', 6)
                ->where('configs_evidences.state', 1)
                ->where('evidence_groups.state', 1)
                ->orderBy('configs_evidences.ordering')
                ->get();

            // $tis_thainame = Tis::where('tb3_TisAutono', $lawcasesform->tis_id)->select('tb3_TisThainame')->first();
            // $license_pdf = TisiLicense::where('tbl_licenseNo', $lawcasesform->offend_license_number)->select('license_pdf')->first();
            // $lawcasesimpound = LawCasesImpound::where('law_case_id', $id)->first();
            // $lawcasesimpound->date_impound = !empty($lawcasesimpound->date_impound) ? HP::revertDate($lawcasesimpound->date_impound, true) : null;
            $lawcasesimpoundproduct = !empty($lawcasesform->law_cases_impound_to->id) ? LawCasesImpoundProduct::where('law_case_impound_id', $lawcasesform->law_cases_impound_to->id)->get() : [];

            //    แสดงผลิตภัณฑ์เดียวกันส่วนที่ 2
            $lawcasesform->date_impound =    !empty($lawcasesform->date_impound)?$lawcasesform->law_cases_impound_to->date_impound:null;

            return view('laws.cases.forms.show', compact('lawcasesform','breadcrumbs', 'lawcasesimpoundproduct' ,'configs_evidences' ));
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
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('edit-'.$model)) {
      
            $lawcasesform = LawCasesForm::findOrFail($id);
            $breadcrumbs = [
                ["link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home"],
                ["link" => "/law/cases/forms",  "name" => 'แจ้งงานคดีผลิตภัณฑ์อุตสาหกรรม'],
                ["link" => "/law/cases/forms/$id/edit",  "name" => 'แก้ไข'],
            ];

            $lawcasesform->offend_date = !empty($lawcasesform->offend_date) ? HP::revertDate($lawcasesform->offend_date, true) : null;

          

            $configs_evidences = ConfigsEvidence::leftjoin((new ConfigsEvidenceGroup)->getTable() . ' AS evidence_groups', 'evidence_groups.id', '=', 'configs_evidences.evidence_group_id')
                ->select('configs_evidences.*')
                ->where('configs_evidences.evidence_group_id', 6)
                ->where('configs_evidences.state', 1)
                ->where('evidence_groups.state', 1)
                ->orderBy('configs_evidences.ordering')
                ->get();
            

            // $tis_thainame = Tis::where('tb3_TisAutono', $lawcasesform->tis_id)->select('tb3_TisThainame')->first();
            // $license_pdf = TisiLicense::where('tbl_licenseNo', $lawcasesform->offend_license_number)->select('license_pdf')->first();
            // $lawcasesimpound = LawCasesImpound::where('law_case_id', $id)->first();
           
           // $lawcasesform->law_cases_impound_to->date_impound = ($lawcasesform->law_cases_impound_to->date_impound) ? HP::revertDate($lawcasesform->law_cases_impound_to->date_impound, true) : null;
            $lawcasesimpoundproduct = !empty($lawcasesform->law_cases_impound_to->id) ? LawCasesImpoundProduct::where('law_case_impound_id', $lawcasesform->law_cases_impound_to->id)->get() : [];

             //    แสดงผลิตภัณฑ์เดียวกันส่วนที่ 2
             $lawcasesform->date_impound =    !empty($lawcasesform->date_impound)?$lawcasesform->law_cases_impound_to->date_impound:null;
 
            return view('laws.cases.forms.edit', compact('lawcasesform','breadcrumbs', 'lawcasesimpoundproduct' ,'configs_evidences' ));
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
       
        $model = str_slug('law-cases-forms','-');
        if(auth()->user()->can('edit-'.$model)) {
  
            $lawcasesform = LawCasesForm::findOrFail($id);
            $requestData = $request->all();

            $submit_type = 1;   // บันทึก
            if( isset($requestData['submit_type']) && $requestData['submit_type'] == 2 ){
                $submit_type = 2;  // ฉบับร่าง
            }

            $user = !empty($lawcasesform->user_created)?$lawcasesform->user_created:auth()->user();
            $login_full_name = $user->reg_fname.' '.$user->reg_lname;

            if($lawcasesform->ref_no=='-'){
                //เลขรัน
                $running_no =  HP::ConfigFormat('LawCasesForm', (new LawCasesForm)->getTable(), 'ref_no', null, null, null);
                $check = LawCasesForm::where('ref_no', $running_no)->first();
                if(!is_null($check)){
                    $running_no =  HP::ConfigFormat('LawCasesForm', (new LawCasesForm)->getTable(), 'ref_no', null, null, null);
                }
                $requestData['ref_no']      =  $running_no;
            }
                      
            if(!empty($request->input('offend_power')) && is_array($request->input('offend_power'))){
                $offend_power = array_diff($request->input('offend_power'), [null]);
                $requestData['offend_power'] = !empty($offend_power)?$offend_power:null;
            }

            $requestData['owner_department_name']     = !empty($request->input('owner_department_name'))? $request->input('owner_department_name'):$user->subdepart->sub_depart_shortname;
            $requestData['owner_sub_department_id']   = !empty($request->input('owner_sub_department_id'))? $request->input('owner_sub_department_id'):null;
            $requestData['owner_basic_department_id'] = !empty($request->input('owner_basic_department_id'))? $request->input('owner_basic_department_id'):null;
            $requestData['owner_case_by']             = !empty($request->input('owner_case_by'))? $request->input('owner_case_by'):$user->runrecno;
            $requestData['owner_name']                = !empty($request->input('owner_name'))? $request->input('owner_name'):$login_full_name;
            $requestData['owner_email']               = !empty($request->input('owner_email'))? $request->input('owner_email'):$user->reg_email;
            $requestData['owner_taxid']               = !empty($request->input('owner_taxid'))? $request->input('owner_taxid'):str_replace("-","", $user->reg_13ID);
            $requestData['owner_tel']                 = !empty($request->input('owner_tel'))? $request->input('owner_tel'):$user->reg_wphone;
            $requestData['owner_phone']               = !empty($request->input('owner_phone'))? $request->input('owner_phone'):$user->reg_phone;
            $requestData['owner_contact_options']     = !empty($request->input('owner_contact_options'))? $request->input('owner_contact_options'):null;
            $requestData['owner_contact_name']        = !empty($request->input('owner_contact_name'))? $request->input('owner_contact_name'):$login_full_name;
            $requestData['owner_contact_phone']       = !empty($request->input('owner_contact_phone'))? $request->input('owner_contact_phone'):$user->reg_phone;
            $requestData['owner_contact_email']       = !empty($request->input('owner_contact_email'))? $request->input('owner_contact_email'):$user->reg_email;

            $requestData['offend_date']               = !empty($requestData['offend_date'])?HP::convertDate( $requestData['offend_date'],true):null;
            $requestData['offend_report_date']        = !empty($requestData['offend_report_date'])?HP::convertDate( $requestData['offend_report_date'],true):null;
            $requestData['offend_accept_date']        = !empty($requestData['offend_accept_date'])?HP::convertDate( $requestData['offend_accept_date'],true):null;
            $status = '';
            
            if($submit_type == 2){ //ฉบับร่าง
                $requestData['status'] =  '0';
            }else if($requestData['approve_type'] == 1){ 
                $requestData['status'] =  98; //แจ้งงานคดีสำเร็จ(รอผู้มีอำนาจพิจารณา)
                $requestData['status_approve'] =  '1'; 
            }else  if(!empty($lawcasesform->status) && $lawcasesform->status == '3'){ //กรณีตีกลับ
                $requestData['status'] = 2;
                $status = '2';
            }else if(!is_null($lawcasesform->status) && $lawcasesform->status <= '1'){ //ห้ามย้อนสถานะ
                $requestData['status'] = 1;
                $status = '1';
            }

            $requestData['updated_by']                = auth()->user()->getKey();

            // ใบอนุญาต
            if(!empty($requestData['licenses'])){   
                $licenses =  $requestData['licenses'];
                if(!empty($licenses)){   
                    $requestData['offend_license_number']     =    !empty($licenses['license_number'][0])? $licenses['license_number'][0] :null;  
                }
            }
   
           // มาตรฐานผลิตภัณฑ์อุตสาหกรรม
           if(!empty($requestData['standard'])){   
                $standard =  $requestData['standard'];
                if(!empty($standard)){   
                   $requestData['tb3_tisno']     =    !empty($standard['tb3_tisno'][0])? $standard['tb3_tisno'][0] :null;  
                }
            }
   

            $lawcasesform->update($requestData);

            // ใบอนุญาต
            if(!empty($requestData['licenses'])){   
                $licenses =  $requestData['licenses'];
                if(!empty($licenses)){   
                    self::law_case_licenses($lawcasesform,$licenses); 
                }
             }

            // มาตรฐานผลิตภัณฑ์อุตสาหกรรม
            if(!empty($requestData['standard'])){   
                $standard =  $requestData['standard'];
                if(!empty($standard)){   
                    self::law_case_standard($lawcasesform,$standard); 
                }
             }

            //รายชื่อพนักงานเจ้าหน้าที่ผู้มีส่วนร่วมในคดี
            $this->SaveStaff($lawcasesform, $requestData);
            //รายการผลิตภัณฑ์ตรวจยึด-อายัด (ของกลาง)
            $this->SaveImpound($lawcasesform, $requestData);
            //บันทึกไฟล์
            $this->SaveFile($lawcasesform, $request);
            //ส่วนที่ 6 : การพิจารณา
            $this->SaveLevelApprove($lawcasesform, $requestData);
                        //Log
            // $arr_status = LawCasesForm::status_list();
                            // (array_key_exists( $requestData['status'], $arr_status )?$arr_status[ $requestData['status'] ]:'-')
             if($status == '2'){
                        HP_Law::InsertLawLogWorking(         
                                                        1,
                                                        ((new LawCasesForm)->getTable()),
                                                        $lawcasesform->id,
                                                        $lawcasesform->ref_no ?? null,
                                                        'แจ้งงานคดี',
                                                        'ส่งข้อมูลข้อมูลเพิ่มเติม',
                                                        'อยู่ระหว่างตรวจสอบข้อมูล'
                                                   );
                    try{//ส่งเมลให้ผู้รับคำขอตามตั้งค่า 
                        $this->send_bounce_mail($lawcasesform);
                    } catch (\Exception $e) {
                        return redirect('law/cases/forms')->with('message_error', 'เกิดข้อผิดพลาดส่งเมลให้ผู้รับคำขอ');
                    }
              }else{
                     HP_Law::InsertLawLogWorking(         
                                                        1,
                                                        ((new LawCasesForm)->getTable()),
                                                        $lawcasesform->id,
                                                        $lawcasesform->ref_no ?? null,
                                                        'แจ้งงานคดี',
                                                        'แก้ไขข้อมูล',
                                                        'แจ้งงานคดีสำเร็จ'
                                                   );
                    try{//ส่งเมลให้ผู้รับคำขอตามตั้งค่า 
                        if($lawcasesform->status != '98'){
                            $this->send_mail($lawcasesform);
                        }
                    } catch (\Exception $e) {
                        return redirect('law/cases/forms')->with('message_error', 'เกิดข้อผิดพลาดส่งเมลให้ผู้รับคำขอ');
                    }
              }

            return redirect('law/cases/forms')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
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
    public function destroy($id)
    {
        $model = str_slug('law-listen-ministry','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawCasesForm::destroy($id);
            return redirect('law/listen/ministry')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-listen-ministry','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawCasesForm;
            $result = LawCasesForm::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($result){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result = LawCasesForm::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }


    public function SaveFile($lawcasesform, $request)
    {

        $requestData = $request->all();

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        $folder_app = $lawcasesform->ref_no;

        if(!empty( $requestData['evidences']) && is_array($requestData['evidences']) && count($requestData['evidences']) > 0){

            $evidences = $requestData['evidences'];

            foreach( $evidences as $evidence ){

                if( !empty($evidence['evidence_file_config']) ){
                    HP::singleFileUploadlaw(
                        $evidence['evidence_file_config'],
                        $this->attach_path.$folder_app,
                        ($tax_number),
                        (auth()->user()->FullName ?? null),
                        'CasesForm',
                        (  (new LawCasesForm)->getTable() ),
                        $lawcasesform->id,
                        'evidence_file_config',
                        !empty($evidence['setting_title'])?$evidence['setting_title']:null,
                        !empty($evidence['setting_id'])?$evidence['setting_id']:null
                    );
                }

            }

        }

        if(!empty( $requestData['repeater-file'])){

            $repeater_file = $requestData['repeater-file'];

            foreach( $repeater_file as $file ){

                if( isset($file['evidence_file_other']) && !empty($file['evidence_file_other']) ){
                    HP::singleFileUploadlaw(
                        $file['evidence_file_other'],
                        $this->attach_path.$folder_app,
                        ($tax_number),
                        (auth()->user()->FullName ?? null),
                        'CasesForm',
                        (  (new LawCasesForm)->getTable() ),
                        $lawcasesform->id,
                        'evidence_file_other',
                        !empty($file['file_documents'])?$file['file_documents']:null
                    );
                }

            }

        }

    }

    public function get_owner_department($depart_type) {
        if($depart_type == '1'){

            $sql = "(CASE 
                        WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                        ELSE  department.depart_nameShort
                    END) AS title";

            $depart = SubDepartment::leftjoin((new Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                    ->select( DB::raw($sql), 'sub_id' )
                                    ->get();
        }
        if($depart_type == '2'){
            $depart = LawDepartment::where('type', 2)->where('state',1)->get();
        }
        return response()->json($depart);

    }

    public function infomation_save(Request $request)
    {
            $requestData = $request->all();
            $requestData['status']    =  1;
            $requestData['created_by']  = auth()->user()->getKey();

            $lawcasesstafflist = LawCasesStaffList::create($requestData);

            if($lawcasesstafflist){
                $msg = 'success';
            }else{
                $msg = 'error';
            }

            return response()->json(['msg' => $msg ]);
      
    }

    public function save_cancel(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $message = 'unsuccess';
        $ref_no = '-';
        if ($id_publish) {
            $lawcasesform = LawCasesForm::findOrFail($id_publish);
            if (!is_null($lawcasesform)) {
                $lawcasesform->status  =  '99'; // ยกเลิก
                $lawcasesform->cancel_remark = !empty($request->cancel_remark) ? $request->cancel_remark : null;
                $lawcasesform->cancel_by     =  auth()->user()->getKey();
                $lawcasesform->cancel_at     =  date('Y-m-d H:i:s');
                $lawcasesform->save();
            }
            $message = 'success';
            $ref_no = $lawcasesform->ref_no;
        }
        return response()->json(['msg' => $message, 'ref_no' => $ref_no]);
    }

    public function send_mail($lawcasesform){  

        $config = LawConfigEmailNotis::whereNotNull('email_list')->where('id',1)->first();

        if(!is_null($config)){
            $emails =  json_decode($config->email_list,true);
            $mail_list = [];
            foreach($emails as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$mail_list)){
                        $mail_list[] =  $email;
                    }
            }

            if(count($mail_list) > 0  && $lawcasesform->status != '0'){
                $url  =  url('/law/cases/assigns/'.$lawcasesform->id);
                // ข้อมูล
                $data_app = [
                            'url' => $url,
                            'lawcasesform' => $lawcasesform,
                            'title'        => 'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง'.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null)
                        ];

            HP_Law::getInsertLawNotifyEmail(1,
                                            ((new LawCasesForm)->getTable()),
                                            $lawcasesform->id,
                                            'แจ้งงานคดี',
                                            'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง'.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null),
                                            view('mail.Law.Cases.cases-forms-config', $data_app),
                                            null,  
                                            null,   
                                            json_encode($mail_list)   
                                            );

            $html = new MailCasesConfig($data_app);
             Mail::to($mail_list)->send($html);

            }
        } 
       
    }

    public function send_bounce_mail($lawcasesform){  
        if(!is_null($lawcasesform->user_lawyer_to->reg_email)){
            $email = $lawcasesform->user_lawyer_to->reg_email;
            if(filter_var($email, FILTER_VALIDATE_EMAIL) && $lawcasesform->status != '0'){
                $mail_list    = [];
                $mail_list[]  =  $email;
                    $url  =  url('/law/cases/results/'.$lawcasesform->id.'/document');
                    // ข้อมูล
                    $data_app = [
                                'url' => $url,
                                'lawcasesform' => $lawcasesform,
                                'title'           => 'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง '.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null)
                            ];

                HP_Law::getInsertLawNotifyEmail(1,
                                                ((new LawCasesForm)->getTable()),
                                                $lawcasesform->id,
                                                'แจ้งงานคดี',
                                                'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง'.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null),
                                                view('mail.Law.Cases.cases-forms-config', $data_app),
                                                null,  
                                                null,   
                                                json_encode($mail_list)   
                                                );

                $html = new MailCasesConfig($data_app);
               Mail::to($mail_list)->send($html);
            }
        } 
    }


    public function section_relation($section_id){
        $section_title =  LawSection::where('id',$section_id)->first();

    return response()->json($section_title);
    }

    public function user_register($userid){
        $user_register =  user::where('runrecno',$userid)->first();

    return response()->json($user_register);
    }



    public function law_case_licenses($cases,$lists)
    {      
        $list_id_data = [];
        if(isset($lists['license_number'])){
            foreach($lists['license_number'] as $item ){
                $list_id_data[] = $item;
            }
        }
       
        $lists_id = array_diff($list_id_data, [null]); 

        //ลบข้อมูลเดิม
        LawCasesLicenses::where('law_cases_id',$cases->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('license_number', $lists_id);
                                })
                                ->delete();

          
        foreach($lists['license_number'] as $key => $item ){
            if(!is_null($item)){
                $licenses                             =  LawCasesLicenses::where('law_cases_id', @$cases->id)->where('license_number',$item)->first();
                if(is_null($licenses)){
                    $licenses                          = new LawCasesLicenses;
                 
                }
                $licenses->law_cases_id                 = $cases->id;
                $licenses->ref_no                       = $cases->ref_no;
                $licenses->license_number              =  $item;
                $licenses->save();

            }

        }
                    
    }


    public function law_case_standard($cases,$lists)
    {      
        $list_id_data = [];
        if(isset($lists['tb3_tisno'])){
            foreach($lists['tb3_tisno'] as $item ){
                $list_id_data[] = $item;
            }
        }
       
        $lists_id = array_diff($list_id_data, [null]); 

        //ลบข้อมูลเดิม
        LawCasesStandard::where('law_cases_id',$cases->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('tb3_tisno', $lists_id);
                                })
                                ->delete();

          
        foreach($lists['tb3_tisno'] as $key => $item ){
            if(!is_null($item)){
                $standard                             =  LawCasesStandard::where('law_cases_id', @$cases->id)->where('tb3_tisno',$item)->first();
                if(is_null($standard)){
                    $standard                          = new LawCasesStandard;
                 
                }

                $tis_id  = Tis::where('tb3_Tisno',$item)->value('tb3_TisAutono');

                $standard->law_cases_id                 = $cases->id;
                $standard->ref_no                      = $cases->ref_no;
                $standard->tis_id                      = $tis_id ?? null;
                $standard->tb3_tisno                   = $item;
                $standard->save();

            }

        } 
                    
    }
 
    public function license_numbers(Request $request)
    {
        $offend_ref_no = $request->input('offend_ref_no'); 
        $offend_taxid    = $request->input('offend_taxid');
        $id              = $request->input('id');
        $message = false; 
        $datas = [];
        $offend_ref_tb = '';
        $i = 0;
        if(!empty($offend_ref_no)){
            $control_check = ControlCheck::where('auto_id_doc',$offend_ref_no)->where('operation','2')->where('status','4')->first();
            $control_performance = ControlPerformance::where('auto_id_doc',$offend_ref_no)->where('conclude_result','3')->where('status','4')->first();
            if(!is_null($control_check)){ // ระบบบันทึกการตรวจควบคุมฯ 
                 $licenses  =   HP::get_license3($control_check->tradeName,$control_check->tbl_tisiNo);
                 if(count($licenses) > 0){
                    foreach($licenses as $item){ 
                        if($item->tbl_licenseStatus == '1'){
                         $object   = (object)[];
                         $object->license_no  = $item->tbl_licenseNo;
                         if(!empty($id)){
                            $object->check       = self::get_license_check2($id,$item->tbl_licenseNo)==null ? '0' : '1';
                         }else{
                            $object->check       = HP::get_license_check2($control_check->id,$item->tbl_licenseNo)==null ? '0' : '1';
                         }
                   
                         $object->url         = "http://appdb.tisi.go.th/tis_dev/p4_license_report/file/$item->license_pdf";
                         $object->status      = $item->tbl_licenseStatus;
                         $datas[]  = $object;
                         $i = 1;
                        }
                    }
                   
                         $offend_ref_tb =  (new ControlCheck)->getTable(); 
                 }
            }else  if(!is_null($control_performance)){ // ระบบบันทึกการตรวจประเมินระบบควบคุมคุณภาพ
                $licenses  =   HP::get_license3($control_performance->tradeName,$control_performance->tbl_tisiNo);
                if(count($licenses) > 0){
                   foreach($licenses as $item){
                    if($item->tbl_licenseStatus == '1'){
                        $object   = (object)[];
                        $object->license_no  = $item->tbl_licenseNo;
                        if(!empty($id)){
                            $object->check       = self::get_license_check2($id,$item->tbl_licenseNo)==null ? '0' : '1';
                         }else{
                            $object->check       = HP::get_license_check2($control_performance->id,$control_performance->tbl_licenseNo)==null ? '0' : '1';
                         }
                        $object->url         = "http://appdb.tisi.go.th/tis_dev/p4_license_report/file/$item->license_pdf";
                        $object->status      = $item->tbl_licenseStatus;
                        $datas[]  = $object;
                        $i = 1;
                     }
                   }
                        $offend_ref_tb =  (new ControlPerformance)->getTable(); 
                }
            }
        }else   if(!empty($offend_taxid)){

            $licenses  =        TisiLicense::where('tbl_taxpayer', $offend_taxid)->select('tbl_licenseNo', 'tbl_licenseStatus', 'license_pdf')->get();
            if(count($licenses) > 0){
                foreach($licenses as $item){
                    if($item->tbl_licenseStatus == '1'){
                        $object   = (object)[];
                        $object->license_no  = $item->tbl_licenseNo;
                        if(!empty($id)){
                            $object->check       = self::get_license_check2($id,$item->tbl_licenseNo)==null ? '0' : '1';
                         }else{
                            $object->check       = '0' ;
                         }
                     
                        $object->url         = "http://appdb.tisi.go.th/tis_dev/p4_license_report/file/$item->license_pdf";
                        $object->status      = '1';
                        $datas[]  = $object;
                        $i = 1;
                    }
                }
                
            }
        }

        if( $i == 1){
            $message =  true; 
        }

        return response()->json(['message' => $message, 'datas' => $datas, 'offend_ref_tb' => $offend_ref_tb]);
    }

    public function get_license_check2($id,$tbl_licenseNo)
    {
        return LawCasesLicenses::where('law_cases_id',$id)->where('license_number', trim($tbl_licenseNo))->value('id');
    }


    public function get_offend_ref_tb(Request $request)
    {
        $offend_taxid = $request->input('offend_taxid',''); 
        $message = false; 
        $offend_ref_no  =  ControlCheck::select('auto_id_doc')
                                        ->where('operation','2')
                                        ->where('status','4')
                                        ->where('tradeName',$offend_taxid) ;
     
         $performances     =  ControlPerformance::select('auto_id_doc')
                                ->where('conclude_result','3')
                                ->where('status','4')
                                ->where('tradeName',$offend_taxid)   ;
         if($performances->count() > 0){
               $offend_ref_no->union($performances);
         }
         if(count($offend_ref_no->get()) > 0){
            $message =  true; 
         }
       
        return response()->json(['message' => $message, 'datas' => $offend_ref_no->get() ]);
 
    }

    public function get_level_approves($cases_id) {
            $level_approves = LawCasesLevelApprove::where('law_cases_id',$cases_id)
                                                ->leftjoin((new Department)->getTable() . ' AS department', 'department.did', '=', 'law_case_level_approves.send_department')
                                                ->leftjoin((new SubDepartment)->getTable() . ' AS subdepartment', 'subdepartment.sub_id', '=', 'law_case_level_approves.position')
                                                ->orderby('id','ASC')
                                                ->get();
            if(count($level_approves) > 0){
                foreach($level_approves as $approve){
                    if(!empty($approve->file_law_cases_approves_to)  && $approve->status  == '4'){
                        $attach = $approve->file_law_cases_approves_to;
                        $approve->attach = '<a href="'. HP::getFileStorage($attach->url) .'" target="_blank" title="'. (!empty($attach->filename) ? $attach->filename : 'ไฟล์แนบ') .'"> '.(HP::FileExtension($attach->url) ?? '' ).'</a>' ;
                    }else{
                        $approve->attach  = '';
                    }
                    $approve->format_create_at_time = $approve->FormatCreateAtTime;
                }
            } 
        return response()->json($level_approves);

    }

    public function table_tbody_approve($sub_department_id){

        $role  = [ 
            '7'=>'จนท',
            '6'=>'ผก',
            '5'=>'ผอ',
            '4'=>'ทป',
            '2'=>'รมอ',
            '1'=>'ลมอ'
          ];
          $did =  SubDepartment::where('sub_id', $sub_department_id)->select('did');

          $sql = "(CASE 
                      WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                      ELSE  department.depart_nameShort
                  END) AS title";

          $sub_department =  SubDepartment::leftjoin((new Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                  ->select( DB::raw($sql), 'sub_id' )
                                  ->whereIn('department.did',$did)
                                  ->pluck('title', 'sub_id');

        $userid = User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                                            ->whereHas('subdepart', function($query) use ($sub_department_id){
                                                                $query->where('sub_id', $sub_department_id); 
                                                            })->pluck('title', 'id');
         $defaults = [ '6', '5','2','1' ];   

        $tr = '';
        foreach($defaults as $default){

            $tr .= '<tr  data-repeater-item>';
            $tr .= '   <td class="text-top text-center">';
            $tr .= '      <span class="td_approve_no">1</span>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-top">';
            $tr .= '      <div class="form-group col-md-12">';
            $tr .=             Form::select('role',$role, $default, ['class' => 'form-control ', 'placeholder'=>'- เลือกกอง -', 'required' => true ]);
            $tr .= '      </div>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-center">';
            $tr .= '      <div class="form-group col-md-12">';
            $tr .=              Form::select('department',$sub_department,null, ['class' => 'form-control send_department', 'placeholder'=>'- เลือกกอง -', 'required' => true]);
            $tr .= '      </div>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-center">';
            $tr .= '      <div class="form-group col-md-12">';
            $tr .=              Form::select('authorize_userid',$userid,null, ['class' => 'form-control authorize_userid_approve', 'placeholder'=>'- เลือกผู้มีอำนาจพิจารณา -', 'required' => true ]);
            $tr .= '      </div>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-center">';
            $tr .= '      <div class="form-group col-md-12">';
            $tr .=              Form::text('position',null , ['class' => 'form-control position_approve', 'required' => 'required']);
            $tr .= '      </div>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-center">';
            $tr .= '      <div class="form-group col-md-12">';
            $tr .=              Form::checkbox('acting', '1', null, ['data-color'=>'#13dafe' ,'class'=>'acting']);
            $tr .= '      </div>';
            $tr .= '   </td>';
            $tr .= '   <td class="text-top text-center">';
            $tr .= '      <button type="button" class="btn btn-danger btn-sm btn_file_remove" data-repeater-delete>';
            $tr .= '          <i class="fa fa-times"></i>';
            $tr .= '      </button>';
            $tr .= '   </td>';
            $tr .= '</tr>';
        }

    return response()->json($tr);
    }


    
    public function get_m_bs_reward_group(Request $request){

        $arrest_id          = $request->input('arrest_id');
        $reward_group       = $reward_group_not = $check1 = $check2 =  []; 

         $rewards  = LawConfigReward::select('id','arrest_id')->where('state','1')->get();
         if(count($rewards) > 0){
            foreach($rewards  as $reward){
                if(count($reward->law_config_reward_sub_many) > 0){
                    foreach($reward->law_config_reward_sub_many as $item ){
                        if(!empty($item->law_reward_group_to)){
                            $object                  = (object)[];
                            $object->id              =  $item->law_reward_group_to->id ;
                            $object->title           =  $item->law_reward_group_to->title; 
                            if($reward->arrest_id == '1'){ //ไม่มีการจับกุม
                                if(!in_array(  $object->id ,$check2)){
                                    $reward_group_not[]      = $object;
                                    $check2[]                = $object->id;
                                }
                            }else{ // มีการจับกุม
                                if(!in_array(  $object->id ,$check1)){
                                    $reward_group[]          = $object;
                                    $check1[]                = $object->id;
                                }
                            }
                        }
                          
                    } 
                }
            }
         }
        $have = (count($reward_group) > 0);
        $dont_have = (count($reward_group_not) > 0);
        $law_reward_group  = LawRewardGroup::where('title', 'ผู้แจ้งเบาะแส')->first();
        if(!empty($law_reward_group) && ($have || $dont_have)){
            $object = (object)[];
            $object->id =  $law_reward_group->id;
            $object->title =  $law_reward_group->title; 
            if($dont_have && !in_array($object->id, $check2)){
                $reward_group_not[] = $object;
                $check2[] = $object->id;
            }
            if($have && !in_array($object->id, $check1)){
                $reward_group[] = $object;
                $check1[] = $object->id;
            }
        }
       return response()->json(['check1'=>$check1 , 'reward_group'=>$reward_group , 'check2'=>$check2 , 'reward_group_not'=>$reward_group_not]);

    }

    public function get_user_departments(Request $request){

        $sub_id          = $request->input('sub_id');
        $role            = $request->input('role');
        $users           =  User::where('status', 1)->where('reg_subdepart',$sub_id)->where('role',$role)->pluck('runrecno');
        $did            =  SubDepartment::where('sub_id',$sub_id)->value('did');
        if(!empty($users) && count($users) > 0){
            $users =  User::where('status', 1)->where('reg_subdepart',$sub_id)->where('role',$role)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->get();
        }else{
            $users =  User::where('status', 1)->where('reg_subdepart',$sub_id)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->get();
        }

       return response()->json(['users'=>$users,'did'=> $did]);

    }

    public function get_file_additionals(Request $request){
        $id                     = $request->input('id');
        $lawcasesform           = LawCasesForm::findOrFail($id);
        $attachs                =[];
        $additional_files       = $lawcasesform->attach_files()->where('section', 'additional_files')->get();
         if(count($additional_files) > 0){
             foreach($additional_files as $attach){
                $object                         = (object)[];
                $object->id                     =  ( !empty($attach->id) ? $attach->id:'') ;
                $object->caption                =  ( !empty($attach->caption) ? $attach->caption:'') ;
                $object->url                    =  HP::getFileStorage($attach->url)  ;
                $object->filename               =  ( !empty($attach->filename) ? $attach->filename:'') ;
                $object->delete                 =  url('law/delete-files/'.($attach->id).'/'.base64_encode('law/cases/forms/'.$lawcasesform->id.'/edit') ) ;
                $attachs[]                      = $object;
             }
         }
       return response()->json(['attachs'=>$attachs]);
    }

    public function save_additionals(Request $request){
        $id                     = $request->input('id');
        $lawcasesform           = LawCasesForm::findOrFail($id);
        $requestData            =       $request->all();
        if(!empty( $requestData['additionals'])){
            $repeater_file = $requestData['additionals'];
            $folder_app = $lawcasesform->ref_no;
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            foreach( $repeater_file as $file ){

                if( isset($file['additionals_file']) && !empty($file['additionals_file']) ){
                    HP::singleFileUploadlaw(
                        $file['additionals_file'],
                        $this->attach_path.$folder_app,
                        ($tax_number),
                        (auth()->user()->FullName ?? null),
                        'CasesForm',
                        (  (new LawCasesForm)->getTable() ),
                        $lawcasesform->id,
                        'additional_files',
                        !empty($file['file_documents'])?$file['file_documents']:null
                    );
                }

            }
        }
       return response()->json(['lawcasesform'=>$lawcasesform]);
    }

    public function delete_file_additionals(Request $request){
        $id                     = $request->input('id');
        $attach =  AttachFileLaw::findOrFail($id);
        if( !empty($attach) && !empty($attach->url) ){    
            if( HP::checkFileStorage( '/'.$attach->url) ){
                Storage::delete( '/'.$attach->url );
                $attach->delete();
            }
            return response()->json(['attach'=>$attach]);
        }   
    }
}




