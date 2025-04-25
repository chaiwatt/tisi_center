<?php

namespace App\Http\Controllers\Laws\Reward;

use DB;
use HP;


use File;
use HP_Law;
use App\User;
use stdClass;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Reward\LawRewards;  
use Illuminate\Support\Facades\Storage;
use App\Models\Law\Basic\LawRewardGroup;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Basic\LawDivisionType;
 
use App\Models\Law\Cases\LawCasesForm;   
use App\Models\Law\Config\LawConfigReward;

use  App\Models\Law\Config\LawConfigSection; 
use App\Models\Law\Config\LawConfigRewardSub;
use  App\Models\Law\Config\LawConfigRewardMax;
use App\Models\Law\Cases\LawCasesResultSection;
use App\Models\Law\Reward\LawlRewardStaffLists;  

use App\Models\Law\Basic\LawBasicDivisionCategory;
use App\Models\Law\Reward\LawlRewardCalculation1;  
use App\Models\Law\Reward\LawlRewardCalculation2;  
use App\Models\Law\Reward\LawlRewardCalculation3;    

class LawCalculationsController extends Controller
{
  
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/calculations/';
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');

        $filter_basic_section_id      = $request->input('filter_basic_section_id');
        $filter_calculate_start_date       = !empty($request->input('filter_calculate_start_date'))? HP::convertDate($request->input('filter_calculate_start_date'),true):null;
        $filter_calculate_end_date         = !empty($request->input('filter_calculate_end_date'))? HP::convertDate($request->input('filter_calculate_end_date'),true):null;
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;
        $filter_users      = $request->input('filter_users');
        $model = str_slug('law-reward-calculations','-');
        //ผู้ใช้งาน
        $user = auth()->user();

        $query =  LawCasesForm::query()
                                  ->with(['law_cases_payments_many' => function($query2) {
                                                return  $query2->orderBy('id', 'DESC');
                                    }])  
                                    ->with(['law_reward_to'])  
                                    ->whereHas('law_cases_payments_many', function ($query2) {
                                        return  $query2->WhereNotNull('ref_id')->Where('paid_status','2');
                                    })
                                    ->whereIn('status',['12','13','14','15'])
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('case_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('offend_name', 'LIKE', '%' . $search_full . '%')->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where('case_number', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                                if($filter_status == 'null'){
                                                      $law_case_ids =  LawRewards::select('law_case_id')->WhereNotNull('law_case_id');
                                                    return $query->WhereNotIn('id',$law_case_ids);
                                                }else{
                                                     return   $query->whereHas('law_reward_to', function ($query2) use ($filter_status){
                                                                                 return  $query2->Where('status',$filter_status);
                                                                              });
                                                }
                                        })
                                        ->when($filter_calculate_start_date, function ($query, $filter_calculate_start_date) use($filter_calculate_end_date){
                                            if(!is_null($filter_calculate_start_date) && !is_null($filter_calculate_end_date) ){
                                              return  $query->with(['law_cases_payments_to'])  
                                                            ->whereHas('law_cases_payments_to', function ($query2)  use($filter_calculate_start_date,$filter_calculate_end_date) {
                                                       return $query2->whereBetween('paid_date',[$filter_calculate_start_date,$filter_calculate_end_date]);
                                                    });  
                                            }else if(!is_null($filter_calculate_start_date) && is_null($filter_calculate_end_date)){
                                                return  $query->with(['law_cases_payments_to'])  
                                                        ->whereHas('law_cases_payments_to', function ($query2)  use($filter_calculate_start_date) {
                                                        return $query2->whereDate('paid_date',$filter_calculate_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                  return  $query->whereHas('law_reward_to', function ($query2)  use($filter_start_date,$filter_end_date) {
                                                       return $query2->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                                    });  
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                 return  $query->whereHas('law_reward_to', function ($query2)  use($filter_start_date) {
                                                        return $query2->whereDate('created_at',$filter_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){

                                            $law_case_result_id =  LawCasesResultSection::select('law_case_result_id')->wherein('section',$filter_basic_section_id);
                                            $law_case_ids =  LawCasesResult::select('law_case_id')->wherein('id',$law_case_result_id);
                                    
                                            return $query->WhereIn('id',$law_case_ids);
                                        })
                                        ->when($filter_users, function ($query, $filter_users){
                                            return  $query->whereHas('law_reward_to', function ($query2) use ($filter_users)  {
                                                             return  $query2->Where('created_by',$filter_users);
                                                        });  
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงเฉพาะรายการที่บันทึก
                                            return   $query->DoesntHave('law_reward_to')
                                                           ->orwhereHas('law_reward_to', function ($query2) use ($user) {
                                                            return  $query2->where('created_by', $user->getKey());
                                                           }) ;
 
                                        }) ;
                                    

 
      
        
           
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return  !empty($item->case_number) ? $item->case_number : '';
                            })
                            ->addColumn('offend_name', function ($item) {
                                    $text  = !empty($item->offend_name) ? $item->offend_name : '';
                                    $text  .= !empty($item->offend_taxid) ? '<br/>'.$item->offend_taxid : '';
                                return $text;
                            }) 
                            ->addColumn('law_basic_section', function ($item) {
                                $text  =   !empty($item->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$item->law_cases_result_to->OffenseSectionNumber)  : '';
                                $text  .= !empty($item->law_basic_arrest_to->title) ?   '<br/><span class="text-muted">('.$item->law_basic_arrest_to->title.')</span>' : '';
                                return  $text;
                            })
                            ->addColumn('amount', function ($item) {
                                return  !empty($item->law_cases_payments_to->amount) ?   number_format($item->law_cases_payments_to->amount,2) : null; 
                            })
                            ->addColumn('status', function ($item) { 
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                    $paid_date =  !empty($item->law_cases_payments_to->paid_date) ? '<br/>'.HP::DateThai($item->law_cases_payments_to->paid_date)  :  '';
                                    return  '<span class="text-success">ชำระเงินแล้ว</span>'.$paid_date;
                                 }else{
                                    return  '<span class="text-muted">รอสร้าง Pay-in</span>';
                                }
                            })  
                            ->addColumn('status_reward', function ($item) { 
                                  return   !empty($item->law_reward_to->StatusHtml) ?   $item->law_reward_to->StatusHtml  :  '<span class="text-muted">รอคำนวณเงิน</span>';
                            })  
                            ->addColumn('user_created', function ($item) {
                                    $text  = !empty($item->law_reward_to->user_created->FullName) ? $item->law_reward_to->user_created->FullName : '-';
                                    $text  .= !empty($item->law_reward_to->created_at) ? '<br/>'.HP::DateThai($item->law_reward_to->created_at) : '';
                                return $text;
                            })
                       
                            ->addColumn('action', function ($item)   use ($model){
                                $button = '';
                                if (auth()->user()->can('edit-'.$model)){
                                  $btn_color = !empty($item->law_reward_to->StatusHtml)?'btn-light-success':'btn-light-warning';
                                  $button .= '<a  href="'.url('law/reward/calculations/'.$item->id.'/edit').'"    class="btn btn-icon btn-circle '.$btn_color.'">
                                                                     <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
                                                </a>';
                                 }
                                   if (auth()->user()->can('printing-'.$model)){
                                    if ( (!empty($item->law_reward_to->status) && in_array($item->law_reward_to->status,['2','3','4','5']))   ){
                                        $button .= ' <a   href="'.url('/law/reward/calculations/print_pdf/'. base64_encode($item->id)).'"  target="_blank">
                                                          <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="30" width="30" >
                                                   </a>';
                                    }else{
                                       $button .= ' <span class="not-allowed" > <img src="'.asset('icon/pdf03.png').'"   class="rounded-circle"  height="30" width="30" > </span>';
                                    }
                                   }
                    
                                 return $button;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'case_number', 'offend_name', 'law_basic_section', 'status', 'status_reward', 'user_created', 'action'])
                            ->make(true);
    }


    public function index()
    {
        $model = str_slug('law-reward-calculations','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/calculations",  "name" => 'คำนวณสินบน' ],
            ];
            return view('laws.reward.calculations.index',compact('breadcrumbs'));
        }
        abort(403);
    }
 
    public function edit($id)
    {
       $model = str_slug('law-reward-calculations','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $cases = LawCasesForm::findOrFail($id);


            // รายชื่อผู้มีสิทธิ์ได้รับเงิน
            if(!empty($cases->law_reward_to->law_reward_staff_lists_many) && count($cases->law_reward_to->law_reward_staff_lists_many) > 0){
                $cases->staff_lists                              =  $cases->law_reward_to->law_reward_staff_lists_many;
                $cases->whistleblower                            =  $cases->law_reward_to->law_reward_staff_lists_many->where('basic_reward_group_id','9')->count();
            }else  if(!empty($cases->cases_staff) && count($cases->cases_staff) > 0){
                $cases->staff_lists                              =  $cases->cases_staff;
                $cases->whistleblower                            =  '0';
          
            }else{
                $cases->staff_lists                              =  [];
                $cases->whistleblower                            =  '0';
            }
 


            // ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน
              $paid_amount =  !empty($cases->law_reward_to->paid_amount) ? $cases->law_reward_to->paid_amount : '0.00'; 
              $section_id = !empty($cases->law_cases_result_to->law_case_result_section_many)   ?   $cases->law_cases_result_to->law_case_result_section_many->pluck('section')->toArray()  :  [];
              $sections =   LawConfigSection::where(function($query) use($section_id) {
                                                    $room_count  =  $section_id;
                                                    if(count($room_count) > 0){
                                                        $query->whereJsonContains('section_relation', (string)$room_count[0]);
                                                        info (count($room_count));
                                                        for($i = 1; $i <= count($room_count) - 1; $i++) {
                                                            $query->orWhereJsonContains('section_relation',(string)$room_count[$i]);
                                                        }
                                                    }else{
                                                         $query->whereNull('id');
                                                    }
                                                    return $query;
                                          })->get();
                    
            if(count($sections) > 0){
                $reward_max =   LawConfigRewardMax::select('law_config_section_id','amount','money')->whereIn('law_config_section_id',$sections->pluck('id'))->where('law_basic_arrest_id',$cases->law_basic_arrest_id)->orderby('money','asc')->get();   
                if(count($reward_max) > 0){
                        $reward = $reward_max->last();
                        $cases->config_section                  = $sections;
                        $cases->division                        = !empty($reward->amount) ?  $reward->amount : '40';
                        $cases->amount                          = (($paid_amount * $cases->division) / 100);
                        $cases->max                             = !empty($reward->money)  ?  $reward->money : '0.00';
                        $cases->difference                      =   '0.00'  ;
                        $cases->total                           = ($cases->amount > $cases->max)  ? ($cases->amount - $cases->max) : $cases->amount ;
           
                }else{ 
                        if($cases->whistleblower != '0'){
                            $cases->division                        =  '60';
                        }else{
                            $cases->division                        =  '80';
                        }
                        $cases->amount                          = (($paid_amount * $cases->division) / 100);
                        $cases->max                             =  '0.00';
                        $cases->difference                      =  '0.00'  ;
                        $cases->total                           =  '0.00'  ;
                }
            }else{
                      if($cases->whistleblower != '0'){
                            $cases->division                        =  '60';
                        }else{
                            $cases->division                        =  '80';
                        }
                        $cases->amount                          = (($paid_amount * $cases->division) / 100);
                        $cases->max                             =  '0.00';
                        $cases->difference                      =   '0.00'  ;
                        $cases->total                           =  '0.00'  ;
            } 
   
            // ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล
            $config_reward = LawConfigReward::where('arrest_id',$cases->law_basic_arrest_id)->where('state',1)->first();
            if(!empty($config_reward) && count($config_reward->law_config_reward_sub_many) > 0){
                $config_reward_sub = $config_reward->law_config_reward_sub_many ;   
                $cases->law_config_reward_id  = !empty($config_reward->id) ?  $config_reward->id : null;
            }else{
                $config_reward_sub = [] ;   
            }
 
            
            if((!empty($cases->law_reward_to->step_froms) && $cases->law_reward_to->step_froms == '1') || (!empty($cases->law_reward_to->status) && $cases->law_reward_to->status == '99')){
                $cases->step_froms                     = 2;
            }else    if(!empty($cases->law_reward_to->step_froms) && $cases->law_reward_to->step_froms >= '2'){
                $cases->step_froms                     = 3;
            }else{
                $cases->step_froms                     = 1;
            }
        
            $cases->law_basic_offend_type_id      = !empty($cases->law_basic_offend_type_to->title) ?  $cases->law_basic_offend_type_to->title : null;
            $cases->law_basic_section_id          = !empty($cases->SectionListName) ?  $cases->SectionListName : null; 
            $cases->ref_id                        = !empty($cases->law_offend_type_to->title) ?  $cases->law_offend_type_to->title : null;
            $cases->date_impound                  = !empty($cases->date_impound) ?  HP::revertDate($cases->date_impound,true) : null;
            $cases->law_basic_resource_id         = !empty($cases->law_basic_resource_to->title) ?  $cases->law_basic_resource_to->title : null;
            $cases->law_basic_arrest              = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;

            // การชำระเงิน
            $payments                             =  $cases->law_cases_payments_to ; 
            


           $categorys       = LawBasicDivisionCategory::where('state',1)->get();
           $division_type   = LawDivisionType::where('division_category_id',2)->where('state',1)->get();


            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/calculations",  "name" => 'คำนวณสินบน' ],
                [ "link" => "/law/reward/calculations/$id/edit",  "name" => 'แก้ไข' ],
            ];
      

            return view('laws.reward.calculations.edit', compact('cases', 'payments', 'categorys', 'division_type', 'config_reward_sub', 'breadcrumbs'));
        }
        return abort(403);;
    }

    public function update(Request $request, $id)
    {
       $model = str_slug('law-reward-calculations','-');
        if(auth()->user()->can('edit-'.$model)) {
 
      
            $cases = LawCasesForm::findOrFail($id);
    
            if(!is_null($cases)){

                    // สินบน
                    $reward =  LawRewards::where('law_case_id',$cases->id)->first();
                    if(is_null($reward)){
                        $reward =  new LawRewards;
                        $reward->created_by         = auth()->user()->getKey();
                    }else{
                        $reward->updated_by         = auth()->user()->getKey();
                    }


                if( $request->step_froms == 1){  // รายชื่อผู้มีสิทธิ์ได้รับเงิน

                            $reward->law_case_id              = !empty($cases->id) ?  $cases->id : null;
                            $reward->case_number              = !empty($cases->case_number) ?  $cases->case_number : null;
                            $reward->law_case_payments_id     = !empty($cases->law_cases_payments_to->id) ?  $cases->law_cases_payments_to->id : null;  
                            $reward->paid_amount              = !empty($cases->law_cases_payments_to->amount) ?  $cases->law_cases_payments_to->amount : null;   
                            $reward->paid_date                = !empty($cases->law_cases_payments_to->paid_date) ?  $cases->law_cases_payments_to->paid_date : null;  
                            $reward->receiptcode              = !empty($cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode) ?  $cases->law_cases_payments_to->app_certi_transaction_pay_in_to->ReceiptCode : null;  
                            $reward->step_froms               = !empty($request->step_froms) ?  $request->step_froms : null;
                            $reward->status                   = '1';
                            $reward->save();

                            // ผลเปรียบเทียบปรับ
                            $staffs =  $request['staffs'];
                         
                            if(!empty($staffs)){   
                                self::law_reward_staff_lists($reward,$staffs); 
                            }
                }else   if( $request->step_froms == 2){ // คำนวณ
                            $reward->edit_income              =  isset($request->edit_income) ? '1' : '0' ;  
                            $reward->edit_proportion          =  isset($request->edit_proportion) ? '1' : '0' ;  
                            $reward->edit_reward              =  isset($request->edit_reward) ? '1' : '0' ;  
                            $reward->step_froms               = !empty($request->step_froms) ?  $request->step_froms : null;
                            $reward->status                   = !empty($request->status) ?  $request->status : null;
                            $reward->law_config_reward_id     = !empty($cases->law_config_reward_id) ?  $cases->law_config_reward_id : null;
                            // ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน
                            $calculation1 =  $request['calculation1'];
                            if(!empty($calculation1)){   
                                self::law_calculation1($reward,$calculation1,$request->cal_type1); 
                            }

                           // ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน
                             $calculation2 =  $request['calculation2'];
                            if(!empty($calculation2)){   
                                self::law_calculation2($reward,$calculation2,$request->cal_type2); 
                            }

                             // ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล
                             $calculation3 =  $request['calculation3'];
                            if(!empty($calculation3)){   
                                self::law_calculation3($reward,$calculation3,$request->cal_type3); 
                            }

                            // หักเป็นรายได้แผ่นดิน
                            $reward->government_total           =  !empty($reward->law_calculation1_many->where('basic_division_category_id','1')->sum('amount')) ? $reward->law_calculation1_many->where('basic_division_category_id','1')->sum('amount') : null;
                            // เงินสินบน เงินรางวัล ค่าใช้จ่ายดำเนินงาน
                            $reward->group_total                =  !empty($reward->law_calculation1_many->where('basic_division_category_id','!=','1')->sum('amount')) ? $reward->law_calculation1_many->where('basic_division_category_id','!=','1')->sum('amount') : null;
                            // ค่าใช้จ่ายในการดำเนินการ
                            $reward->operate_total              =  !empty($reward->law_calculation2_many->where('division_type_name', 'ค่าใช้จ่ายในการดำเนินการ')->sum('amount')) ? $reward->law_calculation2_many->where('division_type_name','ค่าใช้จ่ายในการดำเนินการ')->sum('amount') : null;
                             // เงินสินบน
                            $reward->bribe_total                =  !empty($reward->law_calculation2_many->where('division_type_name', 'เงินสินบน')->sum('amount')) ? $reward->law_calculation2_many->where('division_type_name','เงินสินบน')->sum('amount') : null;
                              // เงินรางวัล
                            $reward->reward_total               =  !empty($reward->law_calculation2_many->where('division_type_name','เงินรางวัล')->sum('amount')) ? $reward->law_calculation2_many->where('division_type_name','เงินรางวัล')->sum('amount') : null;
                            $reward->save();
                }else   if( $request->step_froms == 3){ // คำนวณ

                            $reward->step_froms               = !empty($request->step_froms) ?  $request->step_froms : null;
                            $reward->status                   = !empty($request->status) ?  $request->status : null;
                            $reward->save();
                } 
                
            } 
            if(in_array($request->step_froms,[1,2])){
                return redirect('law/reward/calculations/'.$id.'/edit')->with('flash_message', 'บักทึกเรียบร้อยแล้ว!');
            }else{
                return redirect('law/reward/calculations')->with('flash_message', 'บักทึกเรียบร้อยแล้ว!');
            }
      
        
        }
        return abort(403);;

    }


    public function law_calculation3($reward,$lists,$cal_type = '1')
    {      
            $list_id_data = [];
            if(isset($lists['id'])){
                foreach($lists['id'] as $item ){
                $list_id_data[] = $item;
                }
            }

             $lists_id = array_diff($list_id_data, [null]); 

            //ลบข้อมูลเดิม
            LawlRewardCalculation3::where('law_reward_id',$reward->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

 
          
        foreach($lists['law_basic_reward_group_id'] as $key => $item ){
            if(!is_null($item)){
                $calculation                              =  LawlRewardCalculation3::where('law_reward_id',$reward->id)->where('id', @$lists['id'][$key])->where('law_basic_reward_group_id', @$item)->first();
                if(is_null($calculation)){
                    $calculation                          = new LawlRewardCalculation3;
                    $calculation->created_by              = auth()->user()->getKey();
                }
                $calculation->law_reward_id               = $reward->id;
                $calculation->law_basic_reward_group_id   = $item;
                $group = LawRewardGroup::findOrFail($item);
                if(!is_null($group)){
                    $calculation->name                   = $group->title;
                }
                $calculation->cal_type                    = !empty($cal_type) ? $cal_type :null;
                $calculation->law_reward_calculation_id   = !empty($lists['law_reward_calculation_id'][$key]) ? $lists['law_reward_calculation_id'][$key]:null;
                $calculation->division                    = !empty($lists['division'][$key]) ? $lists['division'][$key]:null;
                $calculation->amount                      = !empty($lists['amount'][$key]) ?  str_replace(",","",$lists['amount'][$key]):null;
                $calculation->average                     = !empty($lists['average'][$key]) ?  str_replace(",","",$lists['average'][$key]):'0';
                $calculation->total                       = !empty($lists['total'][$key]) ?  str_replace(",","",$lists['total'][$key]):null;
                $calculation->remark                      = !empty($lists['remark'][$key]) ?  $lists['remark'][$key]:null;
                $calculation->save();

            }

         }
    
    }


    public function law_calculation2($reward,$lists,$cal_type = '1')
    {      
            $list_id_data = [];
            if(isset($lists['id'])){
                foreach($lists['id'] as $item ){
                $list_id_data[] = $item;
                }
            }

             $lists_id = array_diff($list_id_data, [null]); 

            //ลบข้อมูลเดิม
            LawlRewardCalculation2::where('law_reward_id',$reward->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

 
          
        foreach($lists['basic_division_type_id'] as $key => $item ){
            if(!is_null($item)){
                $calculation                              =  LawlRewardCalculation2::where('law_reward_id',$reward->id)->where('id', @$lists['id'][$key])->where('basic_division_type_id', @$item)->first();
                if(is_null($calculation)){
                    $calculation                          = new LawlRewardCalculation2;
                    $calculation->created_by              = auth()->user()->getKey();
                }
                $calculation->law_reward_id               = $reward->id;
                $calculation->basic_division_type_id      = $item;
                $division_type = LawDivisionType::findOrFail($item);
                if(!is_null($division_type)){
                    $calculation->division_type_name      = $division_type->title;
                }
                $calculation->cal_type                    = !empty($cal_type) ? $cal_type :null;
                $calculation->division                    = !empty($lists['division'][$key]) ? $lists['division'][$key]:null;
                $calculation->amount                      = !empty($lists['amount'][$key]) ?  str_replace(",","",$lists['amount'][$key]):null;
                $calculation->average                     = !empty($lists['average'][$key]) ?  str_replace(",","",$lists['average'][$key]):'0';;
                $calculation->total                       = !empty($lists['total'][$key]) ?  str_replace(",","",$lists['total'][$key]):null;
                $calculation->remark                      = !empty($lists['remark'][$key]) ?  $lists['remark'][$key]:null;
                $calculation->save();

            }

         }
    
    }


    public function law_calculation1($reward,$lists,$cal_type = '1')
    {      
            $list_id_data = [];
            if(isset($lists['id'])){
                foreach($lists['id'] as $item ){
                $list_id_data[] = $item;
                }
            }

             $lists_id = array_diff($list_id_data, [null]); 

            //ลบข้อมูลเดิม
            LawlRewardCalculation1::where('law_reward_id',$reward->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

 
          
        foreach($lists['basic_division_category_id'] as $key => $item ){
            if(!is_null($item)){
                $calculation                              =  LawlRewardCalculation1::where('law_reward_id',$reward->id)->where('id', @$lists['id'][$key])->where('basic_division_category_id', @$item)->first();
                if(is_null($calculation)){
                    $calculation                          = new LawlRewardCalculation1;
                    $calculation->created_by              = auth()->user()->getKey();
                }
                $calculation->law_reward_id               = $reward->id;
                $calculation->basic_division_category_id  = $item;
                $categorys = LawBasicDivisionCategory::findOrFail($item);
                if(!is_null($categorys)){
                    $calculation->division_name           = $categorys->title;
                }
                $calculation->cal_type                    = !empty($cal_type) ? $cal_type :null;
                $calculation->division                    = !empty($lists['division'][$key]) ? $lists['division'][$key]:null;
                $calculation->amount                      = !empty($lists['amount'][$key]) ?  str_replace(",","",$lists['amount'][$key]):null;
                $calculation->max                         = !empty($lists['max'][$key]) ?  str_replace(",","",$lists['max'][$key]):null;
                $calculation->difference                  = !empty($lists['difference'][$key]) ?  str_replace(",","",$lists['difference'][$key]):null;
                $calculation->total                       = !empty($lists['total'][$key]) ?  str_replace(",","",$lists['total'][$key]):null;
                $calculation->remark                      = !empty($lists['remark'][$key]) ?  $lists['remark'][$key]:null;
                $calculation->save();

            }

         }
    
    }

    
    public function law_reward_staff_lists($reward,$lists)
    {      
            $list_id_data = [];
            if(isset($lists['id'])){
                foreach($lists['id'] as $item ){
                $list_id_data[] = $item;
                }
            }

             $lists_id = array_diff($list_id_data, [null]); 

            //ลบไฟล์
            $old =  LawlRewardStaffLists::where('law_reward_id',$reward->id)
                                        ->when($lists_id, function ($query, $lists_id){
                                            return $query->whereNotIn('id', $lists_id);
                                        })
                                        ->get();
            foreach(  $old  AS $item ){
                if(  !empty($item->file_law_attach_calculations_to)  ){
                    $attach =  AttachFileLaw::find($item->file_law_attach_calculations_to->id);
                    if( !empty($attach) && !empty($attach->url) ){    
                        if( HP::checkFileStorage( '/'.$attach->url) ){
                            Storage::delete( '/'.$attach->url );
                            $attach->delete();
                        }
                    }
                }
            }

            //ลบข้อมูลเดิม
            LawlRewardStaffLists::where('law_reward_id',$reward->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

 
          
        foreach($lists['name'] as $key => $item ){
            if(!is_null($item)){
                $staff                              =  LawlRewardStaffLists::where('law_reward_id',$reward->id)->where('id', @$lists['id'][$key])->first();
                if(is_null($staff)){
                    $staff                          = new LawlRewardStaffLists;
                    $staff->created_by              = auth()->user()->getKey();
                }else{
                    $staff->updated_by              = auth()->user()->getKey();  
                }
                $staff->law_reward_id               = $reward->id;
                $staff->law_case_id                 = !empty($reward->law_case_id) ?  $reward->law_case_id : null;
                $staff->case_number                 = !empty($reward->case_number) ?  $reward->case_number : null;
                $staff->basic_reward_group_id       = !empty($lists['basic_reward_group_id'][$key]) ? $lists['basic_reward_group_id'][$key]:null;
                $staff->depart_type                 = !empty($lists['depart_type'][$key]) ?  $lists['depart_type'][$key]:null;
                $staff->sub_department_id           = !empty($lists['sub_department_id'][$key]) ?  $lists['sub_department_id'][$key]:null;
                $staff->basic_department_id         = !empty($lists['basic_department_id'][$key]) ?  $lists['basic_department_id'][$key]:null;
                $staff->taxid                       = !empty($lists['taxid'][$key]) ?  (int)$lists['taxid'][$key]:null;
                $staff->name                        = $item;
                $staff->address                     = !empty($lists['address'][$key]) ?  $lists['address'][$key]:null;
                $staff->mobile                      = !empty($lists['mobile'][$key]) ?  $lists['mobile'][$key]:null;
                $staff->email                       = !empty($lists['email'][$key]) ?  $lists['email'][$key]:null;
                $staff->basic_bank_id               = !empty($lists['basic_bank_id'][$key]) ?  $lists['basic_bank_id'][$key]:null;
                $staff->basic_bank_name             = !empty($lists['basic_bank_name'][$key]) ?  $lists['basic_bank_name'][$key]:null;
                $staff->bank_account_name           = !empty($lists['bank_account_name'][$key]) ?  $lists['bank_account_name'][$key]:null;
                $staff->bank_account_number         = !empty($lists['bank_account_number'][$key]) ? str_replace("(","", str_replace(")","",$lists['bank_account_number'][$key] ))   :null;
                $staff->save();

                if(!empty($lists['file_attach'][$key]) && !empty($lists['input_file_attach_name'][$key])){
                    self::storeFile($lists['file_attach'][$key], $lists['input_file_attach_name'][$key], $staff, (new LawlRewardStaffLists)->getTable(), 'attach_calculations');
                }else  if(!empty($lists['attach_ids'][$key]) && !empty($lists['attach_ids'][$key])){
                    self::storeCopyFile($lists['attach_ids'][$key],$staff,(new LawlRewardStaffLists)->getTable(), 'attach_calculations');
                }


            }

         }
    
    }

    public function update_document(Request $request)
    {
        
        $requestData = $request->all();

        $pathfile = 'files/Tempfile/'.($requestData['case_number']);
        $obj = new stdClass;

        if( $request->hasFile('attach') ){
            $attach                 = $request->file('attach');
            $file_extension         = $attach->getClientOriginalExtension();
            $storageName            = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;
            $storagePath            = Storage::putFileAs( $pathfile, $attach,  str_replace(" ","",$storageName) );
            $obj->file_attach       =  HP::getFileStorage($storagePath);
            $obj->file_attach_odl   =  $attach->getClientOriginalName();
            $obj->file_attach_icon  =  HP::FileExtension($obj->file_attach_odl) ;
            $obj->file_attach_path  = $storagePath;
        }else{

            $obj->file_attach       = '';
            $obj->file_attach_icon  = '';
            $obj->file_attach_odl   = '';
            $obj->file_attach_path  = '';
        }

        return response()->json( $obj );

    } 

    public function storeFile($attach, $filename = '', $staff = '', $table_name = '',$section)
    {
 
                if( !empty($attach) &&  Storage::exists("/".$attach)){


                    $attach_file =  AttachFileLaw::where('ref_id',$staff->id)->where('ref_table',$table_name)->first();
                    if( !empty($attach_file) && !empty($attach_file->url) ){    
                        if( HP::checkFileStorage( '/'.$attach_file->url) ){
                            Storage::delete( '/'.$attach_file->url );
                            $attach_file->delete();
                        }
                    }


                    $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                    $attach_path     =   $this->attach_path.$staff->case_number;

                    $cut = explode("/", $attach );
                    $file_name = end($cut);
                    $file_extension = pathinfo( $file_name , PATHINFO_EXTENSION );


                    $fullFileName    =  (str_random(10).'-date_time'.date('Ymd_hms'));
                    $path            =  $attach_path.'/'.$fullFileName.'.'.$file_extension;;
 
                    Storage::copy($attach, $path );

                    $file_size       = Storage::size($path);
     

                    $request =  AttachFileLaw::create([
                                     'tax_number'        => $tax_number,
                                     'username'          =>  (auth()->user()->FullName ?? null),
                                     'systems'           => 'Law',
                                     'ref_table'         => $table_name,
                                     'ref_id'            => $staff->id,
                                     'url'               => $path,
                                     'filename'          => $filename,
                                     'new_filename'      => $fullFileName,
                                     'caption'           => null,
                                     'size'              => $file_size,
                                     'file_properties'   => $file_extension,
                                     'section'           => $section,
                                     'created_by'        => auth()->user()->getKey(),
                                     'created_at'        => date('Y-m-d H:i:s')
                                 ]);
                    return $request;
                }else{
                    return null;
                }
     }

     public function storeCopyFile($id,$staff = '',$table_name  = '',$section  = '')
     {
               $attach_file =  AttachFileLaw::where('id',$id)->first();
               if(!empty($attach_file) && HP::checkFileStorage($attach_file->url)){
                    HP::getFileStoragePath($attach_file->url);
              
                     $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
 
                    $attach_path     =   $this->attach_path.$staff->case_number;
 
                     $cut = explode("/", $attach_file->url );
                     $file_name = end($cut);
                     $file_extension = pathinfo( $file_name , PATHINFO_EXTENSION );

                     $path             = public_path('uploads/');
                     $file_path        = $path.$attach_file->url;
                     if(is_file($file_path)){
                        $fullFileName    =  (str_random(10).'-date_time'.date('Ymd_hms'));
                        $paths           =  $attach_path.'/'.$fullFileName.'.'.$file_extension;
                        $file_ftp        = Storage::put($paths, File::get($file_path));
                        $request =  AttachFileLaw::create([
                                        'tax_number'        => $tax_number,
                                        'username'          =>  (auth()->user()->FullName ?? null),
                                        'systems'           => 'Law',
                                        'ref_table'         => $table_name,
                                        'ref_id'            => $staff->id,
                                        'url'               => $paths,
                                        'filename'          => $attach_file->filename ?? null,
                                        'new_filename'      => $fullFileName,
                                        'caption'           => null,
                                        'size'              => $attach_file->size ?? null,
                                        'file_properties'   => $file_extension,
                                        'section'           => $section,
                                        'created_by'        => auth()->user()->getKey(),
                                        'created_at'        => date('Y-m-d H:i:s')
                                    ]);
                        return $request;
                     }
                 }else{
                     return null;
                 }
      }
     public function print_pdf($id)
     {
         $id                            =   base64_decode($id);
         $cases                         = LawCasesForm::findOrFail($id);
         $cases->law_basic_arrest       = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;
         $mpdf = new Mpdf([
                            'format'            => 'A4',
                            'mode'              => 'utf-8',
                            'default_font_size' => '15',
                         ]);    
         
        $data_app = [
                    'cases'         => $cases
                    ];



         $html  = view('laws.reward.calculations.pdf', $data_app);
         $mpdf->WriteHTML($html);
         $mpdf->AddPage('P');
         $html2  = view('laws.reward.calculations.pdf2', $data_app);
         $mpdf->WriteHTML($html2);
         $title = "คำนวณสินบน_".date('Ymd_hms').".pdf";  
         $mpdf->SetTitle($title);
         $mpdf->Output($title, "I");
     }

     public function get_taxid(Request $request)
     {
        $message = false;
        $datas = new User;
        $search = str_replace('-', '', $request->input('taxid'));
        // $user = User::select('reg_fname', 'reg_lname', 'reg_email', 'reg_phone', 'reg_subdepart')->Where(DB::raw("REPLACE(reg_13ID,'-','')"), 'LIKE', "%".$search."%")->first();
        $user = User::where(function($query) use($search) {
                        $query->where(DB::raw("REPLACE(reg_13ID,'-','')"), 'LIKE' ,'%'.$search.'%') ;
                    })
                    ->where(function($query) {
                        $query->WhereNotNull(DB::raw("REPLACE(reg_13ID,'-','')"));
                    })
                    ->first();

        if(!is_null($user)){
            $user->name         = !empty($user->reg_fname) && !empty($user->reg_lname)  ? $user->reg_fname.' '.$user->reg_lname : '';
            $user->email        = !empty($user->reg_email) ? $user->reg_email : '';
            $user->phone        = !empty($user->reg_phone) ? $user->reg_phone : '';
            $user->subdepart    = !empty($user->reg_subdepart) ? $user->reg_subdepart : '';
            $datas  = $user;
            $message = true;
        }
         
        return response()->json([ 'message'=> $message, 'datas' => $datas]);
 
     } 
     public function config_reward(Request $request)
     {
        $message = false;
        $datas = [];
        $reward_id = $request->input('reward_id');
        $cases_id  = $request->input('cases_id');
        $config_reward = LawConfigReward::where('id',$reward_id)->first();
        if(!empty($config_reward) && count($config_reward->law_config_reward_sub_many) > 0){
            $config_reward_sub = $config_reward->law_config_reward_sub_many ;   
            foreach($config_reward_sub as $item){
                $decimal = '0';
                if(!empty($item->amount)){
                    $division =  explode(".",$item->amount);
                    if(!empty($division) && count($division) == '2'){
                        if($division[1] > 0){
                            $decimal = $division[0].'.'.mb_substr($division[1],0,1);    
                        }else{
                            $decimal = $division[0];    
                        }
                    }else{
                        $decimal =  number_format($item->amount);
                    }
                }

                $average = LawlRewardStaffLists::where('law_case_id',$cases_id)->where('basic_reward_group_id',$item->reward_group_id)->get()->count();

                $object                         = (object)[]; 
                $object->id                     =  !empty($item->id) ?  $item->id : '';  
                $object->reward_group_id        =  !empty($item->reward_group_id) ?  $item->reward_group_id : '';  
                $object->title                  =  !empty($item->law_reward_group_to->title) ?  $item->law_reward_group_to->title : '';  
                $object->decimal                = $decimal ;
                $object->average                = $average ;
                $datas[]                        = $object;
            }
            $message = true;
        }


         
        return response()->json([ 'message'=> $message, 'datas' => $datas]);
 
     } 

}
