<?php

namespace App\Http\Controllers\Laws\Cases;

use HP;
use HP_Law;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\DB;

use App\User;  
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Law\Log\LawNotify; 

use App\Models\Law\File\AttachFileLaw;

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderCases;
use App\Models\Law\Offense\LawOffenderLicense;
use App\Models\Law\Offense\LawOffenderProduct;
use App\Models\Law\Offense\LawOffenderStandard;


use App\Models\Law\Cases\LawCasesForm;  
use App\Models\Law\Cases\LawCasesCompare;
use App\Models\Law\Cases\LawCasesFactBook;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesCompareBook;
use App\Models\Law\Cases\LawCasesResultSection;
use App\Models\Law\Cases\LawCasesPaymentsDetail;
use App\Models\Law\Cases\LawCasesCompareAmounts; 
use App\Models\Law\Cases\LawCasesCompareCalculate;
use App\Models\Law\Cases\LawCasesPayments;
use App\Models\Law\Cases\LawCasesLicenses;
use App\Models\Law\Cases\LawCasesStandard;

use App\Models\Certify\CertiSettingPayment;

use PhpOffice\PhpWord\TemplateProcessor;
use App\Mail\Mail\Law\Cases\MailCompares;

class LawComparesController extends Controller
{
  
    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/compares/';
        $this->permission  = str_slug('law-cases-compares','-');

    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = ($request->input('filter_status') == "0")?'-1':$request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_basic_section_id = $request->input('filter_basic_section_id');
        $filter_created_at = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;
        $filter_case_number = $request->input('filter_tisi_no');
        $filter_payment_status = $request->input('filter_payment_status');
        $query =  LawCasesForm::query()
                                    ->where(function($query){
                                        $query->whereIn('status',['5','7','8','9','10','11','12','13','14','15']);
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            $search_full = str_replace(' ', '', $filter_search);
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    return $query->Where('ref_no', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "2":
                                                    return $query->where(function ($query2) use($search_full) {
                                                                    $query2->Where('offend_name', 'LIKE', '%' . $search_full . '%')->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%');
                                                                });
                                                    break;
                                                case "3":
                                                    return $query->Where('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "4":
                                                    return $query->Where('case_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                default:
                              
                                                    return  $query->where(function ($query2) use($search_full) {
                                                            $query2->Where('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('case_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                              return $query->whereIn('status',$filter_status);
                                         })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                            return $query->where('tis_id', $filter_tisi_no);
                                        })
                                        ->when($filter_case_number, function ($query, $filter_case_number){
                                            return $query->where('case_number', $filter_case_number);
                                        })
                                        ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){
                                            $law_case_result_id =  LawCasesResultSection::select('law_case_result_id')->wherein('section',$filter_basic_section_id);
                                            $law_case_ids =  LawCasesResult::select('law_case_id')->wherein('id',$law_case_result_id);
                                            return $query->WhereIn('id',$law_case_ids);
                                        })
                                        ->when($filter_created_at, function ($query, $filter_created_at){
                                            return $query->whereDate('created_at', $filter_created_at);
                                        })
                                        ->when($filter_payment_status, function ($query, $filter_payment_status){
                                            return   $query->with(['law_cases_payments_to'])  
                                                             ->whereHas('law_cases_payments_to', function ($query2) use ($filter_payment_status){
                                                                return  $query2->Where('status',$filter_payment_status);
                                                          });
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->where(function($query){
                                                $query->where('lawyer_by', Auth::user()->getKey())
                                                    ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number) ? $item->case_number : '<em>รอดำเนินการ</em>';
                            })
                            ->addColumn('offend_name', function ($item) {
                                $text  = !empty($item->offend_name) ? $item->offend_name : '';
                                $text  .= !empty($item->offend_taxid) ? '<div>'.$item->offend_taxid.'</div>' : '';
                                return $text;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return  !empty($item->StandardNo) ? $item->StandardNo : '';
                            }) 
                            ->addColumn('law_basic_section', function ($item) {
                                return  !empty($item->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$item->law_cases_result_to->OffenseSectionNumber)  : '-';
                            })
                            ->addColumn('number', function ($item) {
                                return !empty($item->law_cases_impound_to->impound_product) ? $item->law_cases_impound_to->impound_product->sum('amount_impounds') : '0';
                            })
                            ->addColumn('total', function ($item) {
                                return  !empty($item->law_cases_impound_to->total_value) ? number_format($item->law_cases_impound_to->total_value,2) : number_format('0',2);
                            })
                            ->addColumn('amount', function ($item) {
                                 if(!empty($item->law_cases_payments_to)){
                                    $pay_in          = !empty($item->law_cases_payments_to->file_law_cases_pay_in_to)?$item->law_cases_payments_to->file_law_cases_pay_in_to:null;
                                    $payments_detail = !empty($item->law_cases_payments_to->law_cases_payments_detail_to) ? $item->law_cases_payments_to->law_cases_payments_detail_to:null;
                                    $cancel_status   = !empty($item->law_cases_payments_to->cancel_status)?  $item->law_cases_payments_to->cancel_status : null;
                                    $status          = !empty($item->law_cases_payments_to->status)?  $item->law_cases_payments_to->status : null;
                                    $condition_type  = !empty($item->law_cases_payments_to->condition_type)?  $item->law_cases_payments_to->condition_type : null;
                                     $txt            = !empty($payments_detail->amount)?number_format($payments_detail->amount, 2): ''; 
                                     
                                    if (!empty($pay_in) && is_null($cancel_status) && (!empty($condition_type) && $condition_type == '1')){
                                        $url         = url('funtions/get-law-view/files/'.$pay_in->url.'/'.(!empty($pay_in->filename) ? $pay_in->filename :  basename($pay_in->url))); 
                                        $txt         .= '<div><a href="' .( $url ). '" title="Pay In" target="_blank"><img src="'.asset('icon/pdf02.png').'" class="rounded-circle"  height="27" width="27" ></a></div>';
                                    }else if(!is_null($status) &&  $status == '1'){
                                        $txt         .= '<div><i class="text-muted">(รอสร้างใบแจ้งชำระ)</i></div>';
                                    }
                                 }else if($item->status == '7'){ //ส่งเรื่องดำเนินคดี
                                        $txt          = '';
                                 }else{
                                        $txt          = '<i class="text-muted">รอเปรียบเทียบปรับ</i>';
                                 }

                                return $txt; 
                            }) 
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer_to->FullName)   ? $item->user_lawyer_to->FullName: '<i class="text-muted">(รอมอบหมาย)</i>';
                            })
                            ->addColumn('status', function ($item) { 
                                return  !empty($item->StatusColorHtml) ? $item->StatusColorHtml : '';
                            }) 
                            ->addColumn('action', function ($item) {
                                return self::buttonActionLaw($item, 'law/cases/compares', 'law-cases-compares');
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
                            ->rawColumns(['checkbox',  'offend_name', 'amount', 'lawyer_name', 'status', 'action','case_number'])
                            ->make(true);
    }

    public static function buttonActionLaw($data, $action_url, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true)
    {
        $form_action = '';

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add-' . str_slug($str_slug_name)) && $show_edit === true) :

            $arr         = [ 'ยินยอมเปรียบเทียบปรับ' => 9 , 'ไม่ยินยอมเปรียบเทียบปรับ' => 10 ];
            $compares    =  $data->law_log_working_compares_to;
            $class       = !empty($compares)?'btn-light-success':'btn-light-primary';
            $status      = ( !empty($compares) && array_key_exists( $compares->status, $arr ) )?$arr[ $compares->status ]:null;

            $span_data   =  'data-id="'.$data->id.'"';
            $span_data   .= 'data-status="'.$status.'"'; 
            $span_data   .= 'data-compare_type="'.$data->compare_type.'"';
            $span_data   .= 'data-compare_date="'.( !empty($data->compare_date) ?  HP::revertDate($data->compare_date, true)    :  HP::revertDate(date("Y-m-d"), true)).'"';
            $span_data   .= 'data-compare_remark="'.$data->compare_remark.'"';
            $span_data   .= 'data-url="'.( !empty($data->file_law_cases_compare_to->url) ?  url('funtions/get-law-view/files/'.$data->file_law_cases_compare_to->url.'/'.(!empty($data->file_law_cases_compare_to->filename) ? $data->file_law_cases_compare_to->filename :  basename($data->file_law_cases_compare_to->url)))  : '').'"';
            $span_data   .= 'data-filename="'.( !empty($data->file_law_cases_compare_to->filename) ? $data->file_law_cases_compare_to->filename  : '').'"';

            $form_action .= ' <span title="บันทึกผลยินยอมเปรียบเทียบปรับ" '.($span_data).' class="btn btn-icon btn-circle '.$class.' compare_case">  <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i> </span>';

        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('printing-' . str_slug($str_slug_name)) && $show_edit === true){

            $class       = ($data->status >= '7' && ( !empty($data->compare_book) || (!empty($data->compare_calculate) &&  count($data->compare_calculate) > 0)  || !empty($data->fact_books) ) )?'btn-light-success':'btn-light-warning';
            $allowed     = ($data->status >= '7')?'':'not-allowed';
            $icon        = '<i class="fa fa-file-text-o"  style="font-size: 1.5em;"></i>';
            $url         = ($data->status >= '7')?url('/law/cases/compares/printing/'.$data->id):'javascript:void(0)';

            $form_action .= '<a href="' .($url). '" title="จัดทําหนังสือแจ้งเปรียบเทียบปรับ" class="btn btn-icon btn-circle '.$class.' '.($allowed).' m-l-5">'.( $icon ).'</a>';

        }

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true){

            $class       = ($data->status >= '7' && !empty($data->law_log_working_consider_adjusting_to))?'btn-light-success':'btn-light-info';
            $allowed     = ($data->status >= '7')?'':'not-allowed';
            $icon        = '<i class="fa fa-bitcoin"  style="font-size: 1.5em;"></i>';
            $url         = ($data->status >= '7')? url('/' . $action_url . '/' . $data->id . '/consider-adjusting'):'javascript:void(0)';

            $form_action .= '<a href="' .($url). '" title="บันทึกผลพิจารณาเปรียบเทียบปรับ" class="btn btn-icon btn-circle '.$class.' '.($allowed).' m-l-5">'.( $icon ).'</a>';
        }
        return $form_action;
    }

    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/compares",  "name" => 'เปรียบเทียบปรับ' ],
            ];
            return view('laws.cases.compares.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function save_compares(Request $request)
    {      
     
        $message = false;
        if(!empty($request->compare_id) ){
            $case = LawCasesForm::findOrFail($request->compare_id);
            if(!is_null($case)){
                $requestData['status']                    = $request->status_id ?? null;
                $requestData['compare_type']              =  !empty($request->compare_type) ? '1' : '0';
                $requestData['compare_date']              =  !empty($request->compare_date) ?  HP::convertDate($request->compare_date,true) : null;
                $requestData['compare_remark']            =  $request->compare_remark ?? null;
                $requestData['compare_by']                =  auth()->user()->getKey();
                $requestData['compare_at']                = date("Y-m-d H:i:s") ;
                $case->update($requestData); 
                $message = true;

                if( !empty($case->offend_taxid) && $request->status_id == 9  ){
                    $this->SaveLawOffender($case);
                }     

                HP_Law::InsertLawLogWorking(         
                    1,
                    ((new LawCasesForm)->getTable()),
                    $case->id,
                    $case->ref_no ?? null,
                    'เปรียบเทียบปรับ',
                    'บันทึกผลยินยอมเปรียบเทียบปรับ',
                    ($request->status_id == '9') ?  'ยินยอมเปรียบเทียบปรับ' : 'ไม่ยินยอมเปรียบเทียบปรับ',
                    $case->remark ?? null
                );

            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            // หลักฐานบันทึกคำให้การ
            if(isset($request->attachs)){
                if ($request->hasFile('attachs')) {
                    HP::singleFileUploadLaw(
                        $request->file('attachs') ,
                        $this->attach_path.$case->case_number,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesForm)->getTable()),
                        $case->id,
                        'compare',
                        'หลักฐานบันทึกคำให้การ'
                    );
                }
            }
         }
        }   

        return response()->json([ 'message' => $message  ]);
    }


    public function SaveLawOffender($lawcaes)
    {
        $offender = LawOffender::where('taxid', $lawcaes->offend_taxid )->first();

        //ข้อมูลผู้กระทำผิด
        if( is_null( $offender ) ){

            $user_offend = $lawcaes->user_offend;

            $DataOffender['sso_users_id'] = !empty($lawcaes->offend_sso_users_id)?$lawcaes->offend_sso_users_id:null;
            $DataOffender['type_id']      = !empty($user_offend->applicanttype_id)?$user_offend->applicanttype_id:null;
            $DataOffender['name']         = !empty($lawcaes->offend_name)?$lawcaes->offend_name:null;
            $DataOffender['taxid']        = !empty($lawcaes->offend_taxid)?$lawcaes->offend_taxid:null;

            //ที่อยู่
            $DataOffender['address_no']     = !empty($lawcaes->address_no)?$lawcaes->address_no:null;
            $DataOffender['moo']            = !empty($lawcaes->offend_moo)?$lawcaes->offend_moo:null;
            $DataOffender['soi']            = !empty($lawcaes->offend_soi)?$lawcaes->offend_soi:null;
            $DataOffender['building']       = !empty($lawcaes->offend_building)?$lawcaes->offend_building:null;
            $DataOffender['street']         = !empty($lawcaes->offend_street)?$lawcaes->offend_street:null;
            $DataOffender['subdistrict_id'] = !empty($lawcaes->offend_subdistrict_id)?$lawcaes->offend_subdistrict_id:null;
            $DataOffender['district_id']    = !empty($lawcaes->offend_district_id)?$lawcaes->offend_district_id:null;
            $DataOffender['province_id']    = !empty($lawcaes->offend_province_id)?$lawcaes->offend_province_id:null;
            $DataOffender['zipcode']        = !empty($lawcaes->offend_zipcode)?$lawcaes->offend_zipcode:null;
            $DataOffender['tel']            = !empty($lawcaes->offend_tel)?$lawcaes->offend_tel:null;
            $DataOffender['email']          = !empty($lawcaes->offend_email)?$lawcaes->offend_email:null;

            //กรรมการ
            $DataOffender['power']          = !empty($lawcaes->offend_power)?$lawcaes->offend_power:null;
            //วันที่กระทำความผิด
            $DataOffender['date_offender']  = !empty($lawcaes->offend_date)?$lawcaes->offend_date:null;
            //สถานะ
            $DataOffender['state']          = 1;
            $offender = LawOffender::create($DataOffender);

        }

        if( !is_null( $offender ) ){

            //คดี
            $cases = LawOffenderCases::updateOrCreate(
                [
                    'law_offender_id'     => $offender->id,
                    'law_cases_id'        => $lawcaes->id
                ],
                [
                    'law_offender_id'     => $offender->id,
                    'law_cases_id'        => $lawcaes->id,

                    'case_number'         => !empty($lawcaes->case_number)?$lawcaes->case_number:null,
                    'date_offender_case'  => !empty($lawcaes->offend_date)?$lawcaes->offend_date:null,

                    //ฝ่าฝืนตามมาตรา
                    'section'             => !empty($lawcaes->result_section)?$lawcaes->result_section->pluck('section')->toArray():null,
                    //บทลงโทษ
                    'punish'              => !empty($lawcaes->result_section)?$lawcaes->result_section->pluck('punish')->toArray():null,
                    //ดำเนินการทางอาญา
                    'case_person'         => !empty($lawcaes->law_cases_result_to)?$lawcaes->law_cases_result_to->person:0,
                    //ดำเนินการปกครอง
                    'case_license'        => !empty($lawcaes->law_cases_result_to)?$lawcaes->law_cases_result_to->license:0,
                    //ดำเนินการของกลาง
                    'case_product'        => !empty($lawcaes->law_cases_result_to)?$lawcaes->law_cases_result_to->product:0,
                    //วันที่ปิดคดี
                    'date_close'          => !empty($lawcaes->close_date)?$lawcaes->close_date:null,
                    //นิติกรเจ้าคดี
                    'lawyer_by'           => !empty($lawcaes->lawyer_by)?$lawcaes->lawyer_by:null,

                    //วันที่ได้รับมอบหมาย
                    'assign_date'         => !empty($lawcaes->assign_at)?$lawcaes->assign_at:null,
                    //ประเภทหน่วยงาน 
                    'depart_type'         => !empty($lawcaes->owner_depart_type)?$lawcaes->owner_depart_type:null,
                    //กอง/กลุ่ม (กรณีภายใน)
                    'sub_department_id'   => !empty($lawcaes->owner_sub_department_id)?$lawcaes->owner_sub_department_id:null,
                    //ชื่อหน่วยงาน (กรณีภายนอก) 
                    'basic_department_id' => !empty($lawcaes->owner_basic_department_id)?$lawcaes->owner_basic_department_id:null,
                    //ชื่อหน่วยงาน/กอง/กลุ่ม 
                    'department_name'     => !empty($lawcaes->owner_department_name)?$lawcaes->owner_department_name:null,
                    //มูลค่าของกลาง
                    'total_price'         => !empty($lawcaes->cases_impound)?$lawcaes->cases_impound->sum('total_value'):0,
                    //ค่าเปรียบเทียบปรับ
                    'total_compare'       => !empty($lawcaes->law_cases_payments_to->law_cases_payments_detail_to->amount)?$lawcaes->law_cases_payments_to->law_cases_payments_detail_to->amount : 0,
                    //วันที่ชำระเงินค่าปรับ
                    'payment_date'        => !empty($lawcaes->law_cases_payments_to->paid_date)?$lawcaes->law_cases_payments_to->paid_date :null,

                ]
            );

            //มอก.
            if( !empty($lawcaes->cases_standards) && count($lawcaes->cases_standards) >= 1 ){
                foreach( $lawcaes->cases_standards AS $standard ){
                    if( !empty($standard->tis_id) ){
                        //มอก. Tb3
                        $tis = $standard->tis;
                        LawOffenderStandard::updateOrCreate(
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'tis_id'                   => $standard->tis_id,
                            ],
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'case_number'              => $cases->case_number,
                                'law_cases_id'             => $cases->law_cases_id,

                                'tis_id'                   => $standard->tis_id,
                                'tb3_tisno'                => $standard->tb3_tisno,
                                'tis_name'                 => !empty($tis->tb3_TisThainame)?$tis->tb3_TisThainame:null,
                            ]
                        );
                    }
                }
            }

            //ใบอนุญาต
            if( !empty($lawcaes->cases_licenses) && count($lawcaes->cases_licenses) >= 1 ){
                foreach( $lawcaes->cases_licenses AS $licenses ){
                    //ใบอนุญาต Tb4
                    $tisi_license = $licenses->tisi_license;
                    if( !empty($licenses->license_number) && !empty( $tisi_license ) ){
                        LawOffenderLicense::updateOrCreate(
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'tb4_tisilicense_id'       => $tisi_license->Autono,
                            ],
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'case_number'              => $cases->case_number,
                                'law_cases_id'             => $cases->law_cases_id,

                                'tb4_tisilicense_id'       => $tisi_license->Autono,
                                'license_number'           => trim($tisi_license->tbl_licenseNo),
                            ]
                        );
                    }
                }
            }

            //ผลิตภัณฑ์
            if(  !empty($lawcaes->impound_products) && count($lawcaes->impound_products) >= 1 ){
                foreach( $lawcaes->impound_products AS $product ){
                    if( !empty($product->detail) ){
                        LawOffenderProduct::updateOrCreate(
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'detail'                   => trim($product->detail),
                            ],
                            [
                                'law_offender_id'          => $offender->id,
                                'law_offenders_cases_id'   => $cases->id,
                                'case_number'              => $cases->case_number,
                                'law_cases_id'             => $cases->law_cases_id,

                                'detail'                   => trim($product->detail),
                                'amount'                   => !empty($product->total)?str_replace(",","",$product->total):null,
                                'unit'                     => !empty($product->unit)?$product->unit:null,
                                'total_price'              => !empty($product->total_price)?str_replace(",","",$product->total_price):null,
                            ]
                        );
                    }
                }
            }

        }

    }

    public function consider_adjusting($id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $cases                                = LawCasesForm::findOrFail($id);
            $cases->law_basic_arrest_id           = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;
            $cases->law_basic_offend_type_id      = !empty($cases->law_basic_offend_type_to->title) ?  $cases->law_basic_offend_type_to->title : null;
            $cases->law_basic_section_id          = !empty($cases->SectionListName) ?  $cases->SectionListName : null; 
            $cases->ref_id                        = !empty($cases->law_offend_type_to->title) ?  $cases->law_offend_type_to->title : null;
            $cases->date_impound                  = !empty($cases->date_impound) ?  HP::revertDate($cases->date_impound,true) : null; 
            $cases->law_basic_resource_id         = !empty($cases->law_basic_resource_to->title) ?  $cases->law_basic_resource_to->title : null;
            $cases->law_basic_arrest              = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;

            $cases->amount                        = !empty($cases->compare_book->amount) ?    number_format($cases->compare_book->amount,2) : null;
            $cases->detail                        = !empty($cases->compare_book->detail) ?  $cases->compare_book->detail : null;

            if(!empty($cases->law_log_working_consider_adjusting_to)){
                $compares =   $cases->law_log_working_consider_adjusting_to;
                if($compares->status == 'ส่งเรื่องดำเนินคดี'){
                    $cases->status =   '7'; // ส่งเรื่องดำเนินคดี
                }else{
                    $cases->status =   '11'; // บันทึกผลแจ้งเปรียบเทียบปรับ
                }
            }

            $compare                              =  LawCasesCompare::where('law_cases_id', $cases->id )->first();

            if(!empty($compare->law_cases_compare_amounts_many) && count($compare->law_cases_compare_amounts_many)  > 0){
                $compare_amounts =  $compare->law_cases_compare_amounts_many;
                $law_notify =    LawNotify::where('ref_table',(new LawCasesCompare)->getTable())->where('ref_id',$compare->id)->where('name_system','ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ')->orderby('id','desc')->first();
            }else{
                $compare_amounts =   [new LawCasesCompareAmounts];
                $law_notify = null;
            }

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/compares",  "name" => 'เปรียบเทียบปรับ' ],
                [ "link" => "/law/cases/compares/$id/consider_adjusting",  "name" => 'ผลพิจารณาเปรียบเทียบปรับ' ],

            ];
 
 
            return view('laws.cases.compares.consider_adjusting', compact('cases', 'compare_amounts', 'compare', 'law_notify', 'breadcrumbs'));
        }
        abort(403);
    }

    public function save_consider_adjusting(Request $request, $id)
    {
     
        if(auth()->user()->can('edit-'.$this->permission)) {

            $tax_number  = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $cases       = LawCasesForm::findOrFail($id);

            if(!empty($cases)){

                $cases->status     =  !empty($request->status) ? $request->status :   @$cases->status ;
                $cases->save();

                HP_Law::InsertLawLogWorking(
                    1,
                    ((new LawCasesForm)->getTable()),
                    $cases->id,
                    $cases->ref_no ?? null,
                    'เปรียบเทียบปรับ',
                    'ผลพิจารณาเปรียบเทียบปรับ',
                    ($cases->status == '11') ?  'บันทึกผลแจ้งเปรียบเทียบปรับ' : 'ส่งเรื่องดำเนินคดี',
                    null
                );

   if( in_array(  $cases->status  , [11]) ){  // บันทึกผลแจ้งเปรียบเทียบปรับ

                         //    start ผลพิจารณาเปรียบเทียบปรับ
                        $compare =  $request['compare'];
                        if(!empty($compare)){ 
                            $law_compare = LawCasesCompare::where('law_cases_id', $cases->id )->first();
                            if(is_null($law_compare)){
                                $law_compare                      =  new LawCasesCompare;
                                $law_compare->created_by          =  auth()->user()->getKey();
                            }else{
                                $law_compare->updated_by          =  auth()->user()->getKey();
                            }

                            $law_compare->law_cases_id       =  $cases->id;
                            $law_compare->case_number        =  $cases->case_number ?? null;
                            $law_compare->book_number        =  !empty($compare['book_number']) ? $compare['book_number'] : null;
                            $law_compare->book_date          =  !empty($compare['book_date']) ? HP::convertDate($compare['book_date'],true):null;
                            $law_compare->save();

                            // หนังสือแจ้งปรับเปรียบเทียบ
                            if(isset($request->attachs)){
                                if ($request->hasFile('attachs')) {
                                    $attachs =  HP::singleFileUploadLaw(
                                                $request->file('attachs') ,
                                                $this->attach_path.$cases->case_number,
                                                ( $tax_number),
                                                (auth()->user()->FullName ?? null),
                                                'Law',
                                                ((new LawCasesCompare)->getTable()),
                                                $law_compare->id,
                                                'compare',
                                                'หนังสือแจ้งปรับเปรียบเทียบ'
                                            );
                                    if(!is_null($attachs) && HP::checkFileStorage($attachs->url)){
                                        HP::getFileStoragePath($attachs->url);
                                    }
                                }
                            }

                            // ผลเปรียบเทียบปรับ
                            $compare_amount =  $request['compare_amount'];
                            if(!empty($compare_amount)){   
                                self::law_cases_operations($law_compare,$compare_amount); 
                            }
                        }else{
                            $law_compare = LawCasesCompare::where('law_cases_id', $cases->id )->first();
                        }
                        // end ผลพิจารณาเปรียบเทียบปรับ

                    $ref_table = (new LawCasesForm)->getTable();
                    $payments = LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->orderby('id','desc')->first();
                    if(is_null($payments)){
                        $payments              = new LawCasesPayments;
                        $payments->created_by  = auth()->user()->getKey();
                    }
                        $payments->status      =  !empty($request->payment_status) ? $request->payment_status:'1';
                        $payments->ref_table   = $ref_table;
                        $payments->ref_id      = $id;
        if(isset($payments->status) && in_array($payments->status,[2])){   // สร้างใบแจ้งชำระ
                            // start ข้อมูลใบแจ้งชำระ (Pay-in)
                            if($request->condition_type == '1'){ // เรียกเก็บเงินค่าปรับ
                                if($cases->law_basic_arrest_id == '1'){ // ไม่มีการจับกุม
                                    $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',1)->first();
                                    $certify = '7';
                                }else{ // มีการจับกุม
                                    $setting_payment = CertiSettingPayment::where('certify',8)->where('payin',1)->where('type',1)->first();
                                    $certify = '8';
                                }
                                
                                $arrContextOptions=array();
                                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                                    $arrContextOptions["ssl"] = array(
                                                                    "verify_peer" => false,
                                                                    "verify_peer_name" => false,
                                                                );
                                }
        
                                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$cases->case_number-1", false, stream_context_create($arrContextOptions));
                                $api = json_decode($content);
                                if(!empty($api)){
        
                                    $transaction = HP::TransactionPayIn1($id,$ref_table,$certify,'1',$api,"$cases->case_number-1");
                                    $payments->app_certi_transaction_pay_in_id     =  $transaction->id;
                                    $payments->save();

                                    $law_compare->total              =  !empty($payments->amount) ? $payments->amount:null;
                                    $law_compare->save();

                                    $file_payin  = self::storeFilePayin($setting_payment,$cases,$payments,(new LawCasesPayments)->getTable(),'attach_payin','ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ');
                                    if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                                        HP::getFileStoragePath($file_payin->url);
                                    }
        
                                }
                            }else{  // เรียกเก็บเงินค่าปรับนอกระบบ
        
                                $payments->ref_table       = $ref_table;
                                $payments->ref_id          = $id;
                                $payments->condition_type  =  !empty($request->condition_type) ? $request->condition_type : '2' ; // ไม่เรียกเก็บค่าปรับ 
                                $payments->amount           = !empty($law_compare->law_cases_compare_amounts_many->sum('amount')) ? str_replace(",","",$law_compare->law_cases_compare_amounts_many->sum('amount')) : null ; 
                                if(!empty($request['payment'])){   
                                    $payment                 = $request['payment'];
                                    if(!empty($payment)){   
                                        $payments->remark      = $payment['remark'];
                                    }
                                }
                     
                                $payments->save();
                                $law_compare->total              =  !empty($payments->amount) ? $payments->amount:null;
                                $law_compare->save();
                                $detail                             =  LawCasesPaymentsDetail::where('law_case_payments_id',$payments->id)->first();
                                if(is_null($detail)){
                                    $detail                          = new LawCasesPaymentsDetail;
                                    $detail->created_by              = auth()->user()->getKey();
                                }
                                $detail->law_case_payments_id        = $payments->id;  
                                $detail->fee_name                    = 'ค่าปรับเปรียบเทียบคดี'.(!empty($cases->law_basic_arrest_to->title)  ? ' (กรณี'.$cases->law_basic_arrest_to->title.')' : '') ;
                                $detail->remark_fee_name             = !empty($payments->remark) ? $payments->remark:null;
                                $detail->amount                      = !empty($payments->amount) ? $payments->amount:null;
                                $detail->save();
                            }
                            // end ข้อมูลใบแจ้งชำระ (Pay-in)

                            // start ส่งอีเมลแจ้งเตือนไปยังผู้กระทำความผิด
                            if(!empty($request->email_results) && count(explode(",",$request->email_results)) > 0){
                                $email_results = [];
                                foreach(explode(",",$request->email_results)as $email){
                                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                                        $email_results[] =  $email;
                                    }
                                }
        
                                if(count($email_results) > 0){
        
                                    $data_app = [
                                                    'case'           => $cases,
                                                    'law_compare'    => $law_compare ,
                                                    'title'          => 'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ ของ '.(!empty($cases->offend_name)?$cases->offend_name:null).' เลขอ้างอิง '.(!empty($cases->ref_no)?$cases->ref_no:null),
                                                    'attachs'        => !empty($attachs) && HP::checkFileStorage($attachs->url) ? $attachs->url : '',
                                                    'file_payin'     => !empty($file_payin) && HP::checkFileStorage($file_payin->url) ? $file_payin->url : '',
                                                ];
        
                                    $log_email =  HP_Law::getInsertLawNotifyEmail(
                                        1,
                                        ((new LawCasesCompare)->getTable()),
                                         $law_compare->id,
                                        'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ',
                                        'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ ของ '.(!empty($cases->offend_name)?$cases->offend_name:null).' เลขอ้างอิง '.(!empty($cases->ref_no)?$cases->ref_no:null),
                                        view('mail.Law.Cases.compares', $data_app),
                                        null,  
                                        null,  
                                        json_encode($email_results)   
                                    );
        
                                    $html = new MailCompares($data_app);
                                    Mail::to($email_results)->send($html);
                                }
                            }
                            // end ส่งอีเมลแจ้งเตือนไปยังผู้กระทำความผิด

               }else{  // รอสร้างใบแจ้งชำระ
                                $payments->save();
               }
             }

    }
            return redirect('law/cases/compares')->with('flash_message', 'บันทึกเรียบร้อยแล้ว!');
        }
        return response(view('403'), 403);

    }

    public function law_cases_operations($law_compare,$lists)
    {      
        $list_id_data = [];
        if(isset($lists['id'])){
            foreach($lists['id'] as $item ){
                $list_id_data[] = $item;
            }
        }
       
        $lists_id = array_diff($list_id_data, [null]); 

        //ลบข้อมูลเดิม
        LawCasesCompareAmounts::where('law_case_compare_id',$law_compare->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

          
        foreach($lists['detail_amounts'] as $key => $item ){
            if(!is_null($item)){
                $compare                             =  LawCasesCompareAmounts::where('id', @$lists['id'][$key])->first();
                if(is_null($compare)){
                    $compare                          = new LawCasesCompareAmounts;
                    $compare->created_by              = auth()->user()->getKey();
                }
                $compare->law_case_compare_id         = $law_compare->id;
                $compare->detail_amounts              =  $item;
                $compare->amount                      = !empty( $lists['amount'][$key])?  str_replace(",","",$lists['amount'][$key]):null;
                $compare->save();

            }

        }
                    
    }


    public function storeFilePayin($setting_payment, $cases = null, $payments = '', $table_name = '', $section = '',$attach_text  = '')
    {

        $mgs = null;
        if( !empty( $cases ) ){

            $arrContextOptions=array();
            $tax_number  = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $url         =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$cases->case_number-1";
            // $filename =  'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ_'.$cases->case_number.'_'.date('Ymd_hms').'.pdf';
            $filename    =   $cases->case_number.'_'.date('Ymd_hms').'.pdf';
                    
            if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                $arrContextOptions["ssl"] = array(  "verify_peer" => false,  "verify_peer_name" => false,    );
            }

            $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));

            if( $url_pdf ){
                $attach_path     = $this->attach_path.$cases->case_number;
                $fullFileName    = date('Ymd_hms').'.pdf';
                $path            = $attach_path.'/'.$fullFileName;
                $storagePath     = Storage::put($path, $url_pdf);
                $file_size       = Storage::size($path);
                $file_types      = explode('.',  basename($fullFileName)) ;
                $file_extension  = end($file_types);
                $request =  AttachFileLaw::create([
                                                    'tax_number'        => $tax_number,
                                                    'username'          =>  (auth()->user()->FullName ?? null),
                                                    'systems'           => 'Law',
                                                    'ref_table'         => $table_name,
                                                    'ref_id'            => $payments->id,
                                                    'url'               => $path,
                                                    'filename'          => $filename,
                                                    'new_filename'      => $fullFileName,
                                                    'caption'           => $attach_text,
                                                    'size'              => $file_size,
                                                    'file_properties'   => $file_extension,
                                                    'section'           => $section,
                                                    'created_by'        => auth()->user()->getKey(),
                                                    'created_at'        => date('Y-m-d H:i:s')
                                                ]);
                $mgs = $request;
            }
        }

        return $mgs;
    }

    public function check_payments_date(Request $request)
    {
        $start_date    =   $request->input('start_date');
        $amount_date   =   $request->input('amount_date');
        $start_date    = !empty($start_date) ? HP::convertDate($start_date,true):null;
        $end_date      =  HP::DatePlus($start_date,$amount_date);
        $end_date      = !empty($end_date) ? HP::revertDate($end_date,true):null;
        return response()->json([ 'message' =>  true,  'end_date' =>  $end_date  ]);     
    }
    
    public function check_pay_in(Request $request)
    {
        $arrContextOptions=array();

        $id              =   $request->input('id');
        $amount          =   $request->input('amount');
        $start_date      =   $request->input('start_date');
        $amount_date     =   $request->input('amount_date');
        $cases           = LawCasesForm::findOrFail($id);
      

        if(!is_null($cases)){
            if($cases->law_basic_arrest_id == '1'){ // ไม่มีการจับกุม
                $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',1)->first();
            }else{ // มีการจับกุม
                $setting_payment = CertiSettingPayment::where('certify',8)->where('payin',1)->where('type',1)->first();
            }
       if(!is_null($setting_payment)){ 

            $ref_table = (new LawCasesForm)->getTable();
            $payments  = LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->where('ordering','1')->orderby('id','desc')->first();
            if(is_null($payments)){
                $payments =  new LawCasesPayments;
                $payments->created_by  =  auth()->user()->getKey();
            }else{ 
                $payments->updated_by  =  auth()->user()->getKey();
            }
                    
            $payments->ref_table       = $ref_table;
            $payments->ref_id          = $id;
            $payments->condition_type  =  '1'; //  เรียกเก็บเงินค่าปรับ 
            $payments->start_date      =  !empty($start_date) ? HP::convertDate($start_date,true):null;
            $payments->amount_date     =  $amount_date;  
            $payments->end_date        =   HP::DatePlus($payments->start_date,$payments->amount_date);
            $payments->amount          =  !empty($amount) ? str_replace(",","",$amount):null;
            $payments->name            = !empty($request->name) ? $request->name : null;
            $payments->paid_status     =  '1'; //  ยังไม่ชำระเงิน 
            $payments->ordering        =  '1'; //  ครั้งแรก 
            $payments->save();

            $detail                             =  LawCasesPaymentsDetail::where('law_case_payments_id',$payments->id)->first();
            if(is_null($detail)){
                $detail                          = new LawCasesPaymentsDetail;
                $detail->created_by              = auth()->user()->getKey();
            }
            $detail->law_case_payments_id        = $payments->id; 
            $detail->fee_name                    = 'ค่าปรับเปรียบเทียบคดี'.(!empty($cases->law_basic_arrest_to->title)  ? ' (กรณี'.$cases->law_basic_arrest_to->title.')' : '') ;
            $detail->remark_fee_name             = !empty($request->remark_fee_name) ? $request->remark_fee_name:null;
            $detail->amount                      = !empty($payments->amount) ? $payments->amount:null;
            $detail->save();
        

                    
            if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                $arrContextOptions["ssl"] = array( "verify_peer" => false,"verify_peer_name" => false,  );
            }
            $content = file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$cases->case_number-1", false, stream_context_create($arrContextOptions));
            $api     = json_decode($content);

            if(!is_null($api) && is_array($api) && array_key_exists(0, $api) && property_exists($api[0], 'error')){
                return response()->json([ 'message'   => false, 'status_error' => $api[0]->error->message  ]);
            }elseif(!is_null($api) && $api->returnCode != '000'){
                return response()->json([  'message'  => false, 'status_error' => HP::getErrorCode($api->returnCode) ]);
	         }else{
                return response()->json([ 'message' =>  true ]);
            }
        }else{
            return response()->json([ 'message' =>  false ,'status_error' => 'ยังไม่มีการเชื่อมต่อ api' ]);
 
        }
        }else{
            return response()->json([ 'message' =>  false ,'status_error' => 'ยังไม่มีการเชื่อมต่อ api' ]);
 
        }
       
    }

    public function printing(Request $request, $id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/compares",  "name" => 'เปรียบเทียบปรับ' ],
            ];

        

            $lawcases   = LawCasesForm::findOrFail($id);
            $lawcompare = LawCasesCompare::where('law_cases_id', $lawcases->id )->first();

            $active = $request->input('active');
            if(!empty($active)){
                if($active == '3'){
                    $lawcases->active1 = '';
                    $lawcases->active2 = '';
                    $lawcases->active3 = 'active';
                }else if($active == '2'){
                    $lawcases->active1 = '';
                    $lawcases->active2 = 'active';
                    $lawcases->active3 = '';
                }else{
                    $lawcases->active1 = 'active';
                    $lawcases->active2 = '';
                    $lawcases->active3 = '';
                }
  
            }else{
                $lawcases->active1 = 'active';
                $lawcases->active2 = '';
                $lawcases->active3 = '';
            }

            return view('laws.cases.compares.printing',compact('breadcrumbs','lawcases' , 'lawcompare'));
        }
        abort(403);
  
    }

    public function printing_update(Request $request, $id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {

            $requestData = $request->all();

            $lawcases   = LawCasesForm::findOrFail($id);

            $compare_book = LawCasesCompareBook::updateOrCreate(
                [
                    'law_cases_id'        => $lawcases->id ,
                ],
                [
                    'law_cases_id'        => $lawcases->id,
                    'book_date'           => !empty($requestData['book_date'])?$requestData['book_date']:null,

                    'title'               => !empty($requestData['title'])?$requestData['title']:null,
                    'send_to'             => !empty($requestData['send_to'])?$requestData['send_to']:null,

                    'refer'               => (!empty($requestData['repeater-refer']) && !empty(array_diff( array_column($requestData['repeater-refer'], 'refer'), [null] )))?array_diff( array_column( $requestData['repeater-refer'] , 'refer'), [null] ):null,

                    'offend_name'         => !empty($requestData['offend_name'])?$requestData['offend_name']:null,
                    'offend_address'      => !empty($requestData['offend_address'])?$requestData['offend_address']:null,

                    'detail'              => !empty($requestData['detail'])?$requestData['detail']:null,
                    'amount'              => !empty($requestData['amount'])?str_replace(",", '',$requestData['amount']):null,

                    'created_by'          => auth()->user()->getKey()
        
                ]
            );

            //ถ้าเลขที่หนังสือว่าง
            if( empty( $compare_book->book_number ) ){
                //เลขรัน
                $running_no =  HP::ConfigFormat('LawCasesBookCompare', (new LawCasesCompareBook)->getTable(), 'book_number', null, null, null);
                $check = LawCasesCompareBook::where('book_number', $running_no)->whereNotNull('book_number')->first();
                if(!is_null($check)){
                    $running_no =  HP::ConfigFormat('LawCasesBookCompare', (new LawCasesCompareBook)->getTable(), 'book_number', null, null, null);
                }
                $requestData['book_number'] = $running_no;

                $compare_book->book_number = !empty($requestData['book_number'])?$requestData['book_number']:null;
                $compare_book->save();
            }
            return redirect('law/cases/compares/printing/'.$lawcases->id.'?active=3'  )->with('flash_message', 'บันทึกเรียบร้อยแล้ว!');

        }
    }

    public function calculate_update(Request $request, $id)
    {       
        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData = $request->all();

            $lawcases   = LawCasesForm::findOrFail($id);

            if( isset($requestData['calculate']) ){

                $calculate_list = $requestData['calculate'];

                foreach( $calculate_list AS $item ){
                    LawCasesCompareCalculate::updateOrCreate(
                        [
                            'law_cases_id'        => $lawcases->id,
                            'law_case_result_section_id' => $item['law_result_section_id'],
        
                        ],
                        [
                            'law_cases_id'        => $lawcases->id,
                            'law_case_result_section_id' => $item['law_result_section_id'],
                            
                            'cal_type'                   => !empty($requestData['cal_type'])?$requestData['cal_type']:null,
                            'mistake'                     => !empty($item['mistake'])?(str_replace(",", '', $item['mistake'])):null,
                            'division'                   => !empty($item['division'])?(str_replace(",", '', $item['division'])):null,
                            'total_value'                => !empty($item['total_value'])?(str_replace(",", '', $item['total_value'])):null,
                            'amount'                     => !empty($item['amount'])?(str_replace(",", '', $item['amount'])):null,
        
                            'created_by'                 => auth()->user()->getKey()
                        ]
                    );
                } 

            }

            return redirect('law/cases/compares/printing/'.$lawcases->id.'?active=2' )->with('flash_message', 'บันทึกเรียบร้อยแล้ว!');

        }
    }

    public function fact_update(Request $request, $id)
    {       
        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData = $request->all();

            $lawcases   = LawCasesForm::findOrFail($id);

            LawCasesFactBook::updateOrCreate(
                [
                    'law_cases_id'           => $lawcases->id,
                ],
                [
                    'law_cases_id'           => $lawcases->id,
                    'created_by'             => auth()->user()->getKey(),
                    'fact_book_numbers'      => !empty($requestData['fact_book_numbers'])?$requestData['fact_book_numbers']:null,
                    'fact_book_date'         => !empty($requestData['fact_book_date']) ? $requestData['fact_book_date']:null,
                    'fact_offend_name'       => !empty($requestData['fact_offend_name'])?$requestData['fact_offend_name']:null,
                    'fact_detection_date'    => !empty($requestData['fact_detection_date']) ? HP::convertDate($requestData['fact_detection_date'],true):null,
                    'fact_locale'            => !empty($requestData['fact_locale'])?$requestData['fact_locale']:null,
                    'fact_maker_by'          => !empty($requestData['fact_maker_by'])?$requestData['fact_maker_by']:null,
                    'fact_lawyer_by'         => !empty($requestData['fact_lawyer_by'])?$requestData['fact_lawyer_by']:null,

                    'fact_license_currently' => !empty($requestData['fact_license_currently'])?$requestData['fact_license_currently']:null,
                    'fact_product_marking'   => !empty($requestData['fact_product_marking'])?$requestData['fact_product_marking']:null,
                    'fact_product_sell'      => !empty($requestData['fact_product_sell'])?$requestData['fact_product_sell']:null,
                    'fact_product_reclaim'   => !empty($requestData['fact_product_reclaim'])?$requestData['fact_product_reclaim']:null,

                ]
            );

            return redirect('law/cases/compares/printing/'.$lawcases->id.'?active=2'  )->with('flash_message', 'บันทึกเรียบร้อยแล้ว!');

        }
    }
    
}
