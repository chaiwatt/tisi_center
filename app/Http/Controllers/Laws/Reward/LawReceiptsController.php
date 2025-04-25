<?php

namespace App\Http\Controllers\Laws\Reward;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Yajra\Datatables\Datatables;
use HP;
use HP_Law;
use stdClass;
use Mpdf\Mpdf;
use Storage;
use File;
use Segment;


use App\Models\Law\Cases\LawCasesForm;   

use App\Models\Law\Reward\LawlRewardStaffLists;  
use App\Models\Law\Reward\LawlRewardRecepts;   
use App\Models\Law\Reward\LawlRewardReceptsDetails;  
use App\Models\Law\Reward\LawlRewardCalculation3;  
use App\Models\Law\File\AttachFileLaw;

use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Reward\MailReceipts;


class LawReceiptsController extends Controller
{

    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/receipts/';
    }




    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');

        $filter_type                 = $request->input('filter_type');
        $filter_case_number          = $request->input('filter_case_number'); 
        $filter_paid_date_month      = $request->input('filter_paid_date_month');
        $filter_paid_date_year       = $request->input('filter_paid_date_year');
        $filter_paid_date_start      = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end        = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;
        $model = str_slug('law-reward-receipts','-');
        //ผู้ใช้งาน
        $user = auth()->user();
        $query =  LawlRewardStaffLists::query()
                                            ->with(['law_reward_recepts_detail_many' => function($query2) {
                                                return  $query2->orderBy('id', 'DESC');
                                            }])  
                                            ->with(['law_reward_to','law_cases_payments_to'])  
                                            ->whereHas('law_reward_to', function ($query2) {
                                                return  $query2->WhereIn('status',['2','3','4','5']);
                                            })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        switch ( $filter_condition_search ):
                                            case "1":
                                                $search_full = str_replace(' ', '', $filter_search);
                                                return $query->Where('case_number', 'LIKE', '%' . $search_full . '%');
                                                break;
                                            case "2":
                                                $search_full = str_replace(' ', '', $filter_search);
                                                return $query->Where('name', 'LIKE', '%' . $search_full . '%');
                                                break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('taxid', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                            default:
                                                $search_full = str_replace(' ', '', $filter_search);
                                                return  $query->where(function ($query2) use($search_full) {
                                                           $query2->Where('case_number', 'LIKE', '%' . $search_full . '%')
                                                                ->OrWhere('name', 'LIKE', '%' . $search_full . '%') 
                                                                ->OrWhere('taxid', 'LIKE', '%' . $search_full . '%');
                                                        });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_type, function ($query, $filter_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                        switch ( $filter_type ):
                                            case "1":
                                                return $query->Where('case_number', 'LIKE', '%' . $filter_case_number . '%');
                                                break;
                                            case "2":
                                                return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                    if(!is_null($filter_paid_date_year)){
                                                                         return  $query2->whereMonth('paid_date',$filter_paid_date_month)->whereYear('paid_date',$filter_paid_date_year);
                                                                    }else{
                                                                        return  $query2->whereMonth('paid_date',$filter_paid_date_month);
                                                                    }
                                                            });
                                                break;
                                                case "3":
                                                    return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                  if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                        return $query2->whereDate('paid_date', '>=', $filter_paid_date_start)
                                                                                        ->whereDate('paid_date', '<=', $filter_paid_date_end);
                                                                    }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                        return  $query2->WhereDate('paid_date',$filter_paid_date_start);
                                                                    }
                                                             });
                                           
                                                 break;
                                            default:
                                            break;
                                        endswitch;
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                            if($filter_status == 'null'){
                                                 return  $query->with(['law_reward_recepts_detail_case_number_to'])  
                                                              ->doesntHave('law_reward_recepts_detail_case_number_to');
                                            }else{
                                                 return   $query->whereHas('law_reward_recepts_detail_to', function ($query2) use ($filter_status){
                                                                             return  $query2->with(['law_reward_recepts_to'])  
                                                                                            ->whereHas('law_reward_recepts_to', function ($query2) use ($filter_status){
                                                                                                return  $query2->Where('status',$filter_status)->WhereNull('cancel_by');
                                                                                            });
                                                          });
                                            }
                                    })  
                                    ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงเฉพาะรายการที่บันทึก
                                        return   $query->whereHas('law_reward_to', function ($query2) use ($user){
                                                          return  $query2->where('created_by', $user->getKey());
                                         });
                                    })
                                    ->groupBy('taxid')
                                    ->groupBy('basic_reward_group_id')
                                    ->groupBy('law_case_id') ;



        
           
        return Datatables::of($query)
                            ->addIndexColumn()
                            // ->addColumn('checkbox', function ($item) {
                            //     if(!empty($item->RewardReceptsTo) ){
                            //         return  '';
                            //     }else{
                            //         return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            //     }
                             
                            // })
                            ->addColumn('case_number', function ($item) {
                                 $text  =    !empty($item->law_reward_to->created_at) ?   ' คำนวณเมื่อวันที่  : : '.HP::DateThai($item->law_reward_to->created_at) : '';
                                 $text  .= !empty($item->law_reward_to->user_created->FullName) ? ' โดย '.$item->law_reward_to->user_created->FullName : '-';
                                return  !empty($item->case_number) ? 'เลขคดี : '. $item->case_number.$text : '';
                            })
                            ->addColumn('name', function ($item) {
                                $text  = !empty($item->name) ? $item->name : '';
                                $text  .= !empty($item->taxid) ? '<br/>'.$item->taxid : '';
                            return $text;
                            })
                            ->addColumn('awardees', function ($item) {
                                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                    return  !empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : '';
                                }else{
                                    return  !empty($item->law_calculation3_to->name) ? $item->law_calculation3_to->name : '';
                                }
                            })
                            ->addColumn('division', function ($item) {
                               
                                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                    if( !empty($item->law_calculation2_to->average) &&  $item->law_calculation2_to->average > 1){
                                        return  !empty($item->law_calculation2_to->division) ? HP::number_format(($item->law_calculation2_to->division / $item->law_calculation2_to->average),2).'%' : '';
                                     }else{
                                       return  !empty($item->law_calculation2_to->division) ? HP::number_format($item->law_calculation2_to->division,2).'%' : '';
                                     }
                                }else{
                                    if( !empty($item->law_calculation3_to->average) &&  $item->law_calculation3_to->average > 1){
                                        return  !empty($item->law_calculation3_to->division) ? HP::number_format(($item->law_calculation3_to->division / $item->law_calculation3_to->average),2).'%' : '';
                                     }else{
                                       return  !empty($item->law_calculation3_to->division) ? HP::number_format($item->law_calculation3_to->division,2).'%' : '';
                                     }
                                }
                            })
                            ->addColumn('total', function ($item) {
                                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                       return  !empty($item->law_calculation2_to->total) ?  number_format($item->law_calculation2_to->total,2)  : '0.00';
                                 }else{
                                       return  !empty($item->law_calculation3_to->total) ? number_format($item->law_calculation3_to->total,2) : '0.00';
                                 }
                                // return  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->total) ? number_format($item->law_reward_recepts_detail_to->law_reward_recepts_to->total,2) : '0.00';
                            })
                            ->addColumn('deduct_amount', function ($item) {
                                $deduct_amount =  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount : '0.00';
                                $deduct_amount +=  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount : '0.00';
                                return number_format($deduct_amount,2);
                            })
                            ->addColumn('amount', function ($item) {
                                return  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->amount) ? number_format($item->law_reward_recepts_detail_to->law_reward_recepts_to->amount,2) : '0.00';
                            })
                            ->addColumn('evidence', function ($item) {
                                        $button = '';

                                        if (!empty($item->RewardReceptsTo)){
                                            $recepts =  $item->RewardReceptsTo;
                                            $button .=  $recepts->conditon_type == '1' ? '<b>ส่งหลักฐานกลับ</b>' :  '<b>ไม่ต้องส่งหลักฐานกลับ</b>';

                                            if ((!empty($recepts->attach_evidence_file))){
                                                $attach = $recepts->attach_evidence_file;
                                                $url = url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)));
                                                $filename = !empty($attach->filename) ?  $attach->filename : '';  
                                                $fullname = !empty($attach->user_created->FullName) ?  $attach->user_created->FullName: '-';  
                                                $send_remark = !empty($recepts->send_remark) ?  $recepts->send_remark : '-';
                                                $send_date = !empty($recepts->send_date) ?  HP::DateThai($recepts->send_date) : '-';  
                                                $button .=   '<br/><span class="mouse-link send-show" data-url="'.$url.'" data-filename="'.$filename.'" data-fullname="'.$fullname.'" data-send_date="'.$send_date.'" data-send_remark="'.$send_remark.'">(แนบหลักฐานแล้ว)</span>';
                                            }else{
                                                $button .=  $recepts->conditon_type == '1' ? 
                                                            '<br/><span class="text-muted send-add pointer"  data-id="'.$recepts->id.'"  data-conditon_type="'.$recepts->conditon_type.'" >(รอแนบหลักฐานกลับมา)</span>' :  
                                                            '<br/><span class="text-muted send-add pointer"   data-id="'.$recepts->id.'"  data-conditon_type="'.$recepts->conditon_type.'">(แนบหลักฐาน)</span>';
                                            }
                                        }else{
                                                $button .= '-';
                                        } 

                              
                                        return $button;
                            })
                            ->addColumn('status', function ($item) { 
                                 $text  =  !empty($item->RewardReceptsTo->ReceptsTypeText) ?  '<p class="text-muted">('.$item->RewardReceptsTo->ReceptsTypeText.')</p>' : '';
                                return  !empty($item->RewardReceptsTo->StatusHtml) ?  $item->RewardReceptsTo->StatusHtml.$text :  '<span class="text-muted">รอสร้างใบสำคัญรับเงิน</span>';
                            })
                            ->addColumn('action', function ($item)   use ($model){
                                     $button = '';
                                 if ((!empty($item->RewardReceptsTo->attach_receipt_file))){
                                        $attach = $item->RewardReceptsTo->attach_receipt_file;
                                        $url = url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)));
                                        $button .= ' <a   href="'.$url.'"  target="_blank">
                                                       <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="30" width="30" >
                                                </a>';
                                 }else{
                                       $button .= ' <span class="not-allowed" > <img src="'.asset('icon/pdf03.png').'"   class="rounded-circle"  height="30" width="30" > </span>';
                                 }
                                 return $button;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'name', 'evidence', 'status', 'action'])
                            ->make(true);
    }


    public function index()
    {
        $model = str_slug('law-reward-receipts','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/receipts",  "name" => 'ใบสำคัญรับเงิน' ],
            ];
    
            return view('laws.reward.receipts.index',compact('breadcrumbs'));
        }
        abort(403);
    }

 

    public function create(Request $request)
    {
        $model = str_slug('law-reward-receipts','-');
        if(auth()->user()->can('add-'.$model)) {

 
 
            $breadcrumbs = [
                                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                                [ "link" => "/law/reward/receipts",  "name" => 'ใบสำคัญรับเงิน' ],
                                [ "link" => "/law/reward/receipts/create",  "name" => 'เพิ่ม' ],
                            ];
 
            return view('laws.reward.receipts.create',compact('breadcrumbs' ));
        }
        return abort(403);;
    }


    

    public function store(Request $request)
    {
        $model = str_slug('law-reward-receipts','-');;
        if(auth()->user()->can('add-'.$model)) {
          
            if($request->condition_group == '1'){ // แบบกรุ๊ปตามผู้มีสิทธิ์
                $details =  $request['details'];
                if(!empty($details)){   
                    self::law_details_condition_group1($details,$request); 
                }
            }else{  // แบบไม่กรุ๊ปตามผู้มีสิทธิ์  
          
                $details =  $request['details'];

                if(!empty($details)){   
                    self::law_details_condition_group2($details,$request); 
                }
            }
          
            return redirect('law/reward/receipts')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return abort(403);;
    }
 
    public function law_details_condition_group1($lists,$request)
    {      
        $ordering  = LawlRewardRecepts::WhereDate('created_at',date("Y-m-d"))->groupBy('ordering')->get()->count();
        $ordering  += 1;
        $config = HP::getConfig();
       
        foreach($lists['ids'] as $key => $item ){
               $datas= LawlRewardStaffLists::WhereIn('id',explode(",",$item))->get();

            if(count($datas) > 0){
                  $data                                         =  $datas->first();
                  $recepts                                      =  new LawlRewardRecepts;
                  $recepts->recept_no                           =  self::recept_no();
                  $recepts->recepts_type                        =  !empty($request->recepts_type) ? $request->recepts_type : null;
                  $recepts->filter_case_number                  =   !empty($request->filter_case_number) ? $request->filter_case_number : null;
                  $recepts->filter_paid_date_month              =   !empty($request->filter_paid_date_month) ? $request->filter_paid_date_month : null;
                  $recepts->filter_paid_date_year               =   !empty($request->filter_paid_date_year) ? $request->filter_paid_date_year : null;
                  $recepts->filter_paid_date_start              =   !empty($request->filter_paid_date_start) ? HP::convertDate($request->filter_paid_date_start,true) : null;
                  $recepts->filter_paid_date_end                =   !empty($request->filter_paid_date_end) ? HP::convertDate($request->filter_paid_date_end,true) : null;
                  $recepts->recept_date                         =  date("Y-m-d");
                  $recepts->taxid                               =  !empty($data->taxid) ? $data->taxid : null;
                  $recepts->name                                =  !empty($data->name) ? $data->name : null;
                  $recepts->address                             =  !empty($data->address) ? $data->address : null;
             
                  $recepts->deduct                              =   !empty($lists['deducts'][explode(",",$item)[0]]) ? $config->number_deduct_money : null;
                  $recepts->deduct_vat                          =   !empty($lists['deducts_vat'][explode(",",$item)[0]]) ?  $config->number_deduct_vat : null;
                 

                  $recepts->status                              = '1';
                  $recepts->condition_group                     =  !empty($request->condition_group) ? $request->condition_group : null;
                  $recepts->set_item                            =  !empty($request->set_item) ? $request->set_item : null;
                  $recepts->conditon_type                       =  !empty($request->conditon_type) ? $request->conditon_type : null;
                  $recepts->due_date                            =  !empty($request->due_date) ? HP::convertDate($request->due_date,true) : null;
                  $recepts->notices                             =  !empty($request->notices) ? $request->notices : '0';
                  $recepts->ordering                            =  $ordering;
                  $recepts->created_by                          =  auth()->user()->getKey();
                  $recepts->save();
                  $i = 1;
                  $total = 0;
                foreach($datas as $key => $item1 ){
                        $details                               =  new   LawlRewardReceptsDetails;
                        $details->law_reward_recepts_id        =  !empty($recepts->id) ? $recepts->id : null;
                        $details->law_reward_staff_lists_id    =  !empty($item1->id) ? $item1->id : null;
                        $details->case_number                  =  !empty($item1->case_number) ? $item1->case_number : null;
                        $details->item                         =  (!empty($item1->law_case_to->offend_name) ?  '1) '.$item1->law_case_to->offend_name : '');
                    if($item1->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส
                        $details->amount                       = !empty($item1->law_calculation2_to->total)  ? $item1->law_calculation2_to->total : '0';
                    }else{
                        $details->amount                       = !empty($item1->law_calculation3_to->total)  ? $item1->law_calculation3_to->total : '0';
                     }
                        $details->created_by                   =   auth()->user()->getKey();
                        $details->save();
                          if($item1->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส
                            $total  +=  !empty($item1->law_calculation2_to->total)  ? $item1->law_calculation2_to->total : '0';
                          }else{
                            $total  +=  !empty($item1->law_calculation3_to->total)  ? $item1->law_calculation3_to->total : '0';
                          }
               
                 }
                 self::file_pdf_save($item,$data,$details,$recepts,(!empty($request->set_item) ? implode(",",$request->set_item) : ''));
                 
                  $recept =  LawlRewardRecepts::where('id',$recepts->id)->first();
                  if(!is_null($recept)){

                  if(!empty($total)){
                     $totals = $total;
                     $amount = $total;
        
                    //  หัก เก็บเป็นเงินสวัสดิการ
                     if(!empty($recept->deduct)){
                             $number1 =  $config->number_deduct_money;
                             $deduct =  ($totals * $number1) / 100;
                             $recept->deduct_amount  =  !empty($deduct)  ? $deduct : null;
                             $amount -= $deduct;
                     }
                     //  หัก ภาษีมูลค่าเพิ่ม
                     if(!empty($recept->deduct_vat)){
                             $number2 =  $config->number_deduct_vat;
                             $deduct_vat =  ($totals * $number2) / 100;
                             $recept->deduct_vat_amount  =  !empty($deduct_vat)  ? $deduct_vat : null;
                             $amount -= $deduct_vat;
                     }
                     $recept->amount            =  !empty($amount)  ? $amount : null;
                     $recept->amount_th         =  !empty($amount)  ? HP::bahtText($amount,'บาท') : null;
                  }
                    $recept->save();
                  }

            } 
            
         }    
         
    }

    public function law_details_condition_group2($lists,$request)
    {      
        $ordering  = LawlRewardRecepts::WhereDate('created_at',date("Y-m-d"))->groupBy('ordering')->get()->count();
        $ordering  += 1;
        $config = HP::getConfig();

        foreach($lists['id'] as $key => $item ){
               $data = LawlRewardStaffLists::findOrFail($item);
            if(!is_null($data)){
                  $recepts                          =  new LawlRewardRecepts;
                  $recepts->recept_no               =  self::recept_no();
                  $recepts->recepts_type            =  !empty($request->recepts_type) ? $request->recepts_type : null;
                  $recepts->filter_case_number      =   !empty($request->filter_case_number) ? $request->filter_case_number : null;
                  $recepts->filter_paid_date_month  =   !empty($request->filter_paid_date_month) ? $request->filter_paid_date_month : null;
                  $recepts->filter_paid_date_year   =   !empty($request->filter_paid_date_year) ? $request->filter_paid_date_year : null;
                  $recepts->filter_paid_date_start  =   !empty($request->filter_paid_date_start) ? HP::convertDate($request->filter_paid_date_start,true) : null;
                  $recepts->filter_paid_date_end    =   !empty($request->filter_paid_date_end) ? HP::convertDate($request->filter_paid_date_end,true) : null;
     
          
                  $recepts->recept_date             =  date("Y-m-d");
                  $recepts->taxid                   =  !empty($data->taxid) ? $data->taxid : null;
                  $recepts->name                    =  !empty($data->name) ? $data->name : null;
                  $recepts->address                 =  !empty($data->address) ? $data->address : null;


                  $recepts->deduct                  =   !empty($lists['deducts'][$item]) ? $config->number_deduct_money : null;
                  $recepts->deduct_vat              =   !empty($lists['deducts_vat'][$item]) ?  $config->number_deduct_vat : null;

                  if($data->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส
                    $recepts->total                 =  !empty($data->law_calculation2_to->total)  ? $data->law_calculation2_to->total : null;
                  }else{
                    $recepts->total                 =  !empty($data->law_calculation3_to->total)  ? $data->law_calculation3_to->total : null;
                
                  }
          
                  if(!empty($recepts->total)){
                     $totals = $recepts->total;
                     $amount = $recepts->total;
        
                    //  หัก เก็บเป็นเงินสวัสดิการ
                     if(!empty($recepts->deduct)){
                             $number1 =  $config->number_deduct_money;
                             $deduct =  ($totals * $number1) / 100;
                             $recepts->deduct_amount  =  !empty($deduct)  ? $deduct : null;
                             $amount -= $deduct;
                     }
                     //  หัก ภาษีมูลค่าเพิ่ม
                     if(!empty($recepts->deduct_vat)){
                             $number2 =  $config->number_deduct_vat;
                             $deduct_vat =  ($totals * $number2) / 100;
                             $recepts->deduct_vat_amount  =  !empty($deduct_vat)  ? $deduct_vat : null;
                             $amount -= $deduct_vat;
                     }
                            $recepts->amount           =  !empty($amount)  ? $amount : null;
                            $recepts->amount_th       =  !empty($amount)  ? HP::bahtText($amount,'บาท') : null;
                  }


                  $recepts->status          = '1';
                  $recepts->condition_group =  !empty($request->condition_group) ? $request->condition_group : null;
                  $recepts->set_item        =  !empty($request->set_item) ? $request->set_item : null;
                  $recepts->conditon_type   =  !empty($request->conditon_type) ? $request->conditon_type : null;
                  $recepts->due_date        =  !empty($request->due_date) ? HP::convertDate($request->due_date,true) : null;
                  $recepts->notices         =  !empty($request->notices) ? $request->notices : '0';
                  $recepts->ordering        =  $ordering;
                  $recepts->created_by      =  auth()->user()->getKey();
                  $recepts->save();
               

                  $details                               =  new   LawlRewardReceptsDetails;
                  $details->law_reward_recepts_id        =  !empty($recepts->id) ? $recepts->id : null;
                  $details->law_reward_staff_lists_id    =  !empty($data->id) ? $data->id : null;
                  $details->case_number                  =  !empty($data->case_number) ? $data->case_number : null;
                  $details->item                         =  (!empty($data->law_case_to->offend_name) ? '1) '.$data->law_case_to->offend_name : '');
                  if($data->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส
                    $details->amount                       =  !empty($data->law_calculation2_to->total)  ? $data->law_calculation2_to->total : null;
                  }else{
                    $details->amount                       =  !empty($data->law_calculation3_to->total)  ? $data->law_calculation3_to->total : null;
                  }

                  $details->created_by                   =   auth()->user()->getKey();
                  $details->save();
                  self::file_pdf_save($data->id,$data,$details,$recepts,(!empty($request->set_item) ? implode(",",$request->set_item) : ''));

            } 
            
         }    
         
    }


    public function recept_no()
    {      
        $prototype = [];
        $prototype[] = (date("y") +43);
        $prototype[] = date("m");
        $recept_no = LawlRewardRecepts::select('recept_no')
                            ->whereYear('created_at',date("Y"))
                            ->whereMonth('created_at',date("m"))
                            ->pluck('recept_no');

              if(count($recept_no) > 0){
                $list_code = [];
                foreach($recept_no as $max_no ){
                    $new_run = explode('/',  $max_no);
                    if(strlen($new_run[2]) == 4){
                        $list_code[$new_run[2]] = $new_run[2];
                    }
                }


                if(count($list_code) > 0){
                    usort($list_code, function($x, $y) {
                        return $x > $y;
                        });
                    $last = end($list_code);
                    $max_new = ((int)$last  + 1);; //บวกค่า 1
                }else{
                    $max_new = 1;
                }

            }else{
                $max_new = 1;
            }
            $prototype[] = str_pad($max_new, 4, 0, STR_PAD_LEFT); //แทนค่าให้ครบตามจำนวนหลัก ด้วย 0 แล้ว เก็บลง Array
        return implode('/', $prototype);
    }

    public function file_pdf_save($ids,$data,$details,$recepts,$set_item ='10,1')
    {
    
        $case_number             =   $data->case_number;  
        $taxid                   =   $data->taxid;  
        $recepts_type            =   !empty($recepts->recepts_type) ? $recepts->recepts_type : null;
        $filter_case_number      =   !empty($recepts->filter_case_number) ? $recepts->filter_case_number : null;
        $filter_paid_date_month  =   !empty($recepts->filter_paid_date_month) ? $recepts->filter_paid_date_month : null;
        $filter_paid_date_year   =   !empty($recepts->filter_paid_date_year) ? $recepts->filter_paid_date_year : null;
        $filter_paid_date_start  =   !empty($recepts->filter_paid_date_start) ? $recepts->filter_paid_date_start : null;
        $filter_paid_date_end    =   !empty($recepts->filter_paid_date_end) ? $recepts->filter_paid_date_end : null;

        $deducts                 =   !empty($recepts->deduct) ? $recepts->deduct : null;
        $deducts_vat             =   !empty($recepts->deduct_vat) ? $recepts->deduct_vat : null;
     
        $config = HP::getConfig();

         $query =  LawlRewardStaffLists::query()
                                        ->with(['law_reward_to','law_reward_recepts_detail_to'])  
                                        ->whereHas('law_reward_to', function ($query2) {
                                            return  $query2->WhereIn('status',['2','3','4','5']);
                                        })
                                        ->when($ids, function ($query, $ids){
                                            return    $query->WhereIn('id',explode(",",$ids));
                                        })
                                        ->groupBy('taxid')
                                        ->groupBy('basic_reward_group_id')
                                        ->groupBy('law_case_id')
                                        ->get();
                
                                        
        $set_item  = explode(",",$set_item);
        if(count($set_item) == 2){
            $set = $set_item[0];
        }else{
            $set = 10;
        }

        if($recepts_type  == '1'){ // รายคดี
            $recepts_text =  !empty($filter_case_number) ?  'เงินรางวัลเลขคดี '.$filter_case_number : '';
        }else    if($recepts_type  == '2'){ // รายเดือน
            $recepts_text =   !empty($filter_paid_date_month)  && !empty($filter_paid_date_year) ? 'เงินรางวัลประจำเดือน '.HP::MonthList()[$filter_paid_date_month].' '.HP::TenYearListReport()[$filter_paid_date_year]  : '';
        }else    if($recepts_type  == '3'){ // ช่วงวันที่
            $recepts_text =   !empty($filter_paid_date_start)  && !empty($filter_paid_date_end) ?   'เงินรางวัลช่วงวันที่ '.HP::DateFormatGroupFullTh($filter_paid_date_start,$filter_paid_date_end) : '';
        }else{
            $recepts_text = '';
        }
                            
        $mpdf = new Mpdf([
                           'format'            => 'A4',
                           'mode'              => 'utf-8',
                           'default_font_size' => '15',
                        ]);   
 

        $x = 1;
        if(count($query) > 0){

            $query1   =  $query->whereIn('basic_reward_group_id',['1','2','12']);
 
            if(count($query1) > 0){
                $array =   $this->SetListStatement($query1,$set);
                $totals =   $this->SetTotal($query1);
                $sums  = $totals;
                 $deduct  = '';
                if(!empty($deducts)){
                        $number1 =  $config->number_deduct_money;
                        $deduct =  ($totals * $number1) / 100;
                        $sums -= $deduct;
                }
                $deduct_vat  = '';
                if(!empty($deducts_vat)){
                        $number2 =  $config->number_deduct_vat;
                        $deduct_vat =  ($totals * $number2) / 100;
                        $sums -= $deduct_vat;
                }
                $x = 1;
                while ($x <=  count($array)) {
                      $datas =  $array[$x]; // data
                      $item =  $query1->first();
                      $data_app = [
                                    'datas'         => $datas,
                                    'x'             => $x,
                                    'set'           => $set,
                                    'count'         => count($array), 
                                    'totals'        => $totals,
                                    'deduct'        => $deduct,
                                    'deduct_vat'    => $deduct_vat,
                                    'sums'          => $sums,
                                    'items'         => !empty($item) ? $this->get_segment_array($item) : '',
                                    'recepts_text'  => $recepts_text
                                ];
                               
                    $html  = view('laws.reward.receipts.pdf', $data_app);
                 
                    if($x > 1){
                        $mpdf->AddPage('P');
                    }
               
                    $mpdf->WriteHTML($html);
               
                    $x++;
                }
            }
 
            $query2   =  $query->whereNotIn('basic_reward_group_id',['1','2','12']);  //เจ้าของเรื่อง, ผู้มีส่วนร่วมในการจับกุม, เจ้าหน้าที่ตำรวจผู้จับกุม
      
            if(count($query2) > 0){
                $array =   $this->SetListStatement2($query2,$set);
   
                $x = 1;
                while ($x <=  count($array)) {
                      $datas  =  $array[$x]; // data
                      $totals =   $this->SetTotal2($datas);
                      $sums   = $totals;
                       $deduct  = '';
                       if(!empty($deducts)){
                              $number1 =  $config->number_deduct_money;
                              $deduct =  ($totals * $number1) / 100;
                              $sums -= $deduct;
                      }
                      $deduct_vat  = '';
                      if(!empty($deducts_vat)){
                              $number2 =  $config->number_deduct_vat;
                              $deduct_vat =  ($totals * $number2) / 100;
                              $sums -= $deduct_vat;
                      }


                      $item =  $query2->first();
                      $data_app = [
                                    'datas'         => $datas,
                                    'x'             => $x,
                                    'set'           => $set,
                                    'count'         => count($array), 
                                    'totals'        => $totals,
                                    'deduct'        => $deduct,
                                    'deduct_vat'    => $deduct_vat,
                                    'sums'          => $sums,
                                    'items'         => !empty($item) ? $this->get_segment_array($item) : '',
                                    'recepts_text'  => $recepts_text
                                ];
                               
                    $html  = view('laws.reward.receipts.pdf2', $data_app);
                 
                    if($x > 1){
                        $mpdf->AddPage('P');
                    }
               
                    $mpdf->WriteHTML($html);
               
                    $x++;
                }

            }

        }else{
                $mpdf->WriteHTML('no information');
        }



        $title = "receipts-".str_replace("/","",$recepts->recept_no)."-".date('Ymdhms').".pdf";  
    
        $mpdf->SetTitle($title);
      
          $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
 

          $path             = public_path('uploads/');
          $attach_path  =  $this->attach_path.$case_number;

          if(!File::isDirectory($path.$attach_path)){
              File::makeDirectory($path.$attach_path, 0777, true, true);
          }  
          $file_path = $path.$attach_path.'/'.$title;
          $mpdf->Output($file_path, "F");
         if(is_file($file_path)){
                //  อัพไฟล์ ftp
                $storageName    = str_random(10).'-date_time'.date('Ymd_hms') . '.pdf' ;
 
                $fullFileName    =  date('Ymd_hms').'.pdf';
                $paths           =  $attach_path.'/'.$fullFileName;
                $file_ftp        = Storage::put($paths, File::get($file_path));
 
            if($file_ftp == true){
                $file_size       = File::size($path);
                $file_types      =   explode('.',  basename($fullFileName)) ;
                $file_extension  =  end($file_types);
                $file_payin =  AttachFileLaw::create([
                                                    'tax_number'        => $tax_number,
                                                    'username'          =>  (auth()->user()->FullName ?? null),
                                                    'systems'           => 'Law',
                                                    'ref_table'         => (new LawlRewardRecepts)->getTable(),
                                                    'ref_id'            => $recepts->id ?? null,
                                                    'url'               => $paths,
                                                    'filename'          => $fullFileName,
                                                    'new_filename'      => $title,
                                                    'size'              => $file_size,
                                                    'file_properties'   => $file_extension,
                                                    'section'           => 'receipt_pdf',
                                                    'created_by'        => auth()->user()->getKey(),
                                                    'created_at'        => date('Y-m-d H:i:s')
                                               ]);
                if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                    HP::getFileStoragePath($file_payin->url);
                }
            }
          }  


          if( (!empty($recepts) && $recepts->notices == '1')  &&  (!empty($data->email) &&  filter_var($data->email, FILTER_VALIDATE_EMAIL)) ){
 
            if($recepts_type  == '1'){ // รายคดี
                $recepts_text =  !empty($filter_case_number) ?  'เลขคดี '.$filter_case_number : '';
            }else    if($recepts_type  == '2'){ // รายเดือน
                $recepts_text =   !empty($filter_paid_date_month)  && !empty($filter_paid_date_year) ? 'ประจำเดือน '.HP::MonthList()[$filter_paid_date_month].' '.HP::TenYearListReport()[$filter_paid_date_year]  : '';
            }else    if($recepts_type  == '3'){ // ช่วงวันที่
                $recepts_text =   !empty($filter_paid_date_start)  && !empty($filter_paid_date_end) ?   'ช่วงวันที่ '.HP::DateFormatGroupFullTh($filter_paid_date_start,$filter_paid_date_end) : '';
            }else{
                $recepts_text = '';
            }


            $data_app = [
                        'staff_lists'    => $data,
                        'recepts'         => $recepts,
                        'title'          => "แจ้งใบสำคัญรับเงิน จากส่วนแบ่งเงินค่าปรับ $recepts_text",
                        'attachs'        => !empty($file_payin) && HP::checkFileStorage($file_payin->url) ? $file_payin->url : '',
                    ]; 
                    
            $log_email =  HP_Law::getInsertLawNotifyEmail(2,
                                                        ((new LawlRewardRecepts)->getTable()),
                                                        $recepts->id,
                                                        'จัดส่งหนังสือแจ้งการกระทำความผิด',
                                                        "แจ้งใบสำคัญรับเงิน จากส่วนแบ่งเงินค่าปรับ $recepts_text",
                                                        view('mail.Law.Reward.receipts', $data_app),
                                                        null,  
                                                        null,  
                                                        $data->email
                                                        );

                $html = new MailReceipts($data_app);
                Mail::to($data->email)->send($html);
           }
    }
    
    
    public function get_datas_html(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');

        $recepts_type             = $request->input('recepts_type');
        $filter_case_number      = $request->input('filter_case_number','');
        $filter_paid_date_month  = $request->input('filter_paid_date_month','');
        $filter_paid_date_year   = $request->input('filter_paid_date_year','');
        $filter_paid_date_start  = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end    = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;
     

        $query =  LawlRewardStaffLists::query()
                                     ->with(['law_reward_to','law_reward_recepts_detail_to'])  
                                    ->whereHas('law_reward_to', function ($query2) {
                                        return  $query2->WhereIn('status',['2','3','4','5']);
                                    })
                                    ->with(['law_reward_recepts_detail_case_number_to'])  
                                     ->doesntHave('law_reward_recepts_detail_case_number_to')
                                    //  ->Where('created_by',auth()->user()->getKey())
                                    ->when($recepts_type, function ($query, $recepts_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                        switch ( $recepts_type ):
                                            case "1":
                                                return $query->Where('case_number', 'LIKE', '%' . $filter_case_number . '%');
                                                break;
                                            case "2":
                                                return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                if(!is_null($filter_paid_date_year)){
                                                                     return  $query2->whereMonth('paid_date',$filter_paid_date_month)->whereYear('paid_date',$filter_paid_date_year);
                                                                }else{
                                                                    return  $query2->whereMonth('paid_date',$filter_paid_date_month);
                                                                }
                                                        });
                                                break;
                                            case "3":
                                                    return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                  if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                        return $query2->whereDate('paid_date', '>=', $filter_paid_date_start)
                                                                                        ->whereDate('paid_date', '<=', $filter_paid_date_end);
                                                                    }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                        return  $query2->WhereDate('paid_date',$filter_paid_date_start);
                                                                    }
                                                             });
                                           
                                                 break;
                                            default:
                                            break;
                                        endswitch;
                                    })
                                    ->groupBy('taxid')
                                    ->groupBy('basic_reward_group_id')
                                    ->groupBy('law_case_id')
                                    ->get();

        $taxids = [];
        $message = false;
        $htmls = '';
        $check = [];
        $k = 1;
        $config = HP::getConfig();
        if(count($query) > 0){
              $message = true;
                foreach($query as $key =>  $item){
                        $ids =   $query->where('taxid',$item->taxid)->pluck('id');
                    if(count($ids)){
                        $taxids[$item->taxid] =     $ids->implode(',');
                    }
                }

                foreach($query as $item){
                    if(array_key_exists($item->taxid,$taxids)){
                        $ids =  $taxids[$item->taxid];
                     }else{
                        $ids = $item->id;
                     }
                     if(!in_array($item->taxid,$check)){
                         $i = 1;
                        $check[$item->taxid] =  $item->taxid;
                     }else{
                          $i = 0;
                     }
                     $depart_type = $item->depart_type ;
                     $htmls .= '<tr>';

                     $htmls .= '<td class="text-center text-top">';
                     $htmls .=  ($k++);
                     $htmls .= '</td>';

                    //  $htmls .= '<td class="text-top">';
                    //  $htmls .= '<span class="span'.$i.'">';
                    //  $htmls .= 'แสดงเมื่อบันทึก';
                    //  $htmls .= '</span>';
                    //  $htmls .= '</td>';

                     $htmls .= '<td class="text-top">';
                     $htmls .=  !empty($item->name) ? $item->name : '';
                     $htmls .= '<input type="hidden"  name="details[id][]"  value="'.$item->id.'">' ;  
                     $htmls .= '<input type="hidden"  name="details[ids]['.$ids.']"  value="'.$ids.'">' ;  
                     $htmls .= '</td>';

                     $htmls .= '<td class="text-top">';
                     $text  = !empty($item->mobile) ? $item->mobile : '-';
                     $text  .= !empty($item->email) ? '<br/>'.$item->email : '';
                     $htmls .=  $text;
                     $htmls .= '</td>';

                     $htmls .= '<td class="text-top">';
                     $text   = !empty($item->case_number) ? $item->case_number : '';
                     $text  .= !empty($item->law_case_to->offend_name) ? ' : '.$item->law_case_to->offend_name : '';
                     $htmls .=  $text;
                     $htmls .= '</td>';

                     $htmls .= '<td class="text-top">';
                     $htmls .= !empty($item->law_case_to->law_basic_arrest_to->title) ? $item->law_case_to->law_basic_arrest_to->title : '' ;
                     $htmls .= '</td>';
              
                     $htmls .= '<td class="text-top">';
                     if($item->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส  
                        $htmls .= !empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : '';
                     }else{  
                        $htmls .= !empty($item->law_calculation3_to->name) ? $item->law_calculation3_to->name : ''  ;
                     } 
                     $htmls .= '</td>';

                     $htmls .= '<td class="text-top text-right">';
                     if($item->basic_reward_group_id == '9'){ // ผู้แจ้งเบาะแส  
                        $htmls .= !empty($item->law_calculation2_to->total) ? number_format($item->law_calculation2_to->total,2) : '';
                        $htmls .= '<input type="hidden"  class="total"  value="'.(!empty($item->law_calculation2_to->total) ? number_format($item->law_calculation2_to->total,2) : '0.00').'">' ;
                     }else{  
                        $htmls .= !empty($item->law_calculation3_to->total) ? number_format($item->law_calculation3_to->total,2)  : '';
                        $htmls .= '<input type="hidden"  class="total"  value="'.(!empty($item->law_calculation3_to->total) ? number_format($item->law_calculation3_to->total,2) : '0.00').'">' ;
                     } 
                     $htmls .= '</td>';

                     if ($config->check_deduct_money == '1'){ //  หักเงินเก็บเป็นสวัสดีการ สมอ.
                         $checked1  = '';
                        $htmls .= '<td class="text-center text-top">'; 
                        $htmls .= '<span class="span'.$i.'">';
                            if(!empty($config->agency_deduct_money)){
                               $deducts = json_decode($config->agency_deduct_money,true);   
                                 if(!empty($deducts) && in_array($depart_type,$deducts)){
                                   $checked1  = 'checked';
                               }
                            }  
                         $htmls .=    '<input type="checkbox" name="details[deducts]['.$item->id.']" class="deducts" '.$checked1.'  value="1">';
                         $htmls .= '</span>';
                        $htmls .= '</td>'; 
                     }
                     if ($config->check_deduct_vat == '1'){ // หักภาษีมูลค่าเพิ่ม VAT
                        $checked2  = '';
                        $htmls .= '<td class="text-center text-top">'; 
                        $htmls .= '<span class="span'.$i.'">';
                        if(!empty($config->agency_deduct_vat)){
                             $deducts_vat = json_decode($config->agency_deduct_vat,true);   
                              if(!empty($deducts_vat) && in_array($depart_type,$deducts_vat)){
                                $checked2  = 'checked';
                            }
                         }  
                         $htmls .=    '<input type="checkbox" name="details[deducts_vat]['.$item->id.']" class="deducts_vat"  '.$checked2.'  value="1">';
                         $htmls .= '</span>';
                        $htmls .= '</td>'; 
                     }
                     $htmls .= '<td class="text-top">';
                     $htmls .= '<span class="span'.$i.'">';
                     $htmls .= '<span class="preview" data-id="'.$item->id.'"  data-ids="'.$ids.'"  data-case_number="'.$item->case_number.'"  data-taxid="'.$item->taxid.'" >Preview</span>';
                     $htmls .= '</span>';
                     $htmls .= '</td>';

                     $htmls .= '</tr>';
                }
        }
        return response()->json([ 'message' => $message,'htmls' => $htmls  ]);
    }

    public function preview(Request $request)
    {
        $ids                     = $request->input('id');
        $type                    = $request->input('type','I');
        $case_number             = $request->input('case_number');
        $taxid                   = $request->input('taxid');
        $deducts               = $request->input('deducts');
        $deducts_vat           = $request->input('deducts_vat');
        $set_item                = $request->input('set_item','10,1');
        $recepts_type            = $request->input('recepts_type','');
        $filter_case_number      = $request->input('filter_case_number','');
        $filter_paid_date_month  = $request->input('filter_paid_date_month','');
        $filter_paid_date_year   = $request->input('filter_paid_date_year','');
        $filter_paid_date_start  = $request->input('filter_paid_date_start','');
        $filter_paid_date_end    = $request->input('filter_paid_date_end','');
 
        
        $config = HP::getConfig();

         $query =  LawlRewardStaffLists::query()
                                         ->with(['law_reward_to','law_reward_recepts_detail_to'])  
                                        ->whereHas('law_reward_to', function ($query2) {
                                            return  $query2->WhereIn('status',['2','3','4','5']);
                                        })
                                        ->when($ids, function ($query, $ids){
                                            return    $query->WhereIn('id',explode(",",$ids));
                                        })
                                        ->groupBy('taxid')
                                        ->groupBy('basic_reward_group_id')
                                        ->groupBy('law_case_id')
                                        ->get();
                     
        $set_item  = explode(",",$set_item);
        if(count($set_item) == 2){
            $set = $set_item[0];
        }else{
            $set = 10;
        }

        if($recepts_type  == '1'){ // รายคดี
            $recepts_text =  !empty($filter_case_number) ?  'เงินรางวัลเลขคดี '.$filter_case_number : '';
        }else    if($recepts_type  == '2'){ // รายเดือน
            $recepts_text =   !empty($filter_paid_date_month)  && !empty($filter_paid_date_year) ? 'เงินรางวัลประจำเดือน '.HP::MonthList()[$filter_paid_date_month].' '.HP::TenYearListReport()[$filter_paid_date_year]  : '';
        }else    if($recepts_type  == '3'){ // ช่วงวันที่
            $recepts_text =   !empty($filter_paid_date_start)  && !empty($filter_paid_date_end) ?   'เงินรางวัลช่วงวันที่ '.HP::DateFormatGroupFullTh($filter_paid_date_start,$filter_paid_date_end) : '';
        }else{
            $recepts_text = '';
        }
 
        $mpdf = new Mpdf([
                           'format'            => 'A4',
                           'mode'              => 'utf-8',
                           'default_font_size' => '15',
                        ]);   
        if($type == 'I'){
            $mpdf->SetWatermarkText("DRAFT");
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->showWatermarkText = true;
            $mpdf->watermarkTextAlpha = 0.15; 
        }


     
        if(count($query) > 0){

            $query1   =  $query->whereIn('basic_reward_group_id',['1','2','12']);
 
            if(count($query1) > 0){
                $array =   $this->SetListStatement($query1,$set);
                $totals =   $this->SetTotal($query1);
                $sums  = $totals;
                 $deduct  = '';
                if($deducts == '1'){
                        $number1 =  $config->number_deduct_money;
                        $deduct =  ($totals * $number1) / 100;
                        $sums -= $deduct;
                }
                $deduct_vat  = '';
                if($deducts_vat == '1'){
                        $number2 =  $config->number_deduct_vat;
                        $deduct_vat =  ($totals * $number2) / 100;
                        $sums -= $deduct_vat;
                }
                $x = 1;
                while ($x <=  count($array)) {
                      $datas =  $array[$x]; // data
                      $item =  $query1->first();
                      $data_app = [
                                    'datas'         => $datas,
                                    'x'             => $x,
                                    'set'           => $set,
                                    'count'         => count($array), 
                                    'totals'        => $totals,
                                    'deduct'        => $deduct,
                                    'deduct_vat'    => $deduct_vat,
                                    'sums'          => $sums,
                                    'items'         => !empty($item) ? $this->get_segment_array($item) : '',
                                    'recepts_text'  => $recepts_text
                                ];
                               
                    $html  = view('laws.reward.receipts.pdf', $data_app);
                 
                    if($x > 1){
                        $mpdf->AddPage('P');
                    }
               
                    $mpdf->WriteHTML($html);
               
                    $x++;
                }
            }
 
            $query2   =  $query->whereNotIn('basic_reward_group_id',['1','2','12']);  //เจ้าของเรื่อง, ผู้มีส่วนร่วมในการจับกุม, เจ้าหน้าที่ตำรวจผู้จับกุม
      
            if(count($query2) > 0){
                $array =   $this->SetListStatement2($query2,$set);
   
                $x = 1;
                while ($x <=  count($array)) {
                      $datas  =  $array[$x]; // data
                      $totals =   $this->SetTotal2($datas);
                      $sums   = $totals;
                       $deduct  = '';
                      if($deducts == '1'){
                              $number1 =  $config->number_deduct_money;
                              $deduct =  ($totals * $number1) / 100;
                              $sums -= $deduct;
                      }
                      $deduct_vat  = '';
                      if($deducts_vat == '1'){
                              $number2 =  $config->number_deduct_vat;
                              $deduct_vat =  ($totals * $number2) / 100;
                              $sums -= $deduct_vat;
                      }


                      $item =  $query2->first();
                      $data_app = [
                                    'datas'         => $datas,
                                    'x'             => $x,
                                    'set'           => $set,
                                    'count'         => count($array), 
                                    'totals'        => $totals,
                                    'deduct'        => $deduct,
                                    'deduct_vat'    => $deduct_vat,
                                    'sums'          => $sums,
                                    'items'         => !empty($item) ? $this->get_segment_array($item) : '',
                                    'recepts_text'  => $recepts_text
                                ];
                               
                    $html  = view('laws.reward.receipts.pdf2', $data_app);
                 
                    if($x > 1){
                        $mpdf->AddPage('P');
                    }
               
                    $mpdf->WriteHTML($html);
               
                    $x++;
                }

            }

        }else{
                $mpdf->WriteHTML('no information');
        }


        $title = "receipts-".date('Ymdhms').".pdf";  
        $mpdf->SetTitle($title);

        $mpdf->Output($title, "I");
       
    }


    public  function SetListStatement($data = [],$set = 10){
        if($set == '1'){
            $set = '2';
        }
        $request = [];
        $i = 0;
        $key1 = 1;
        $array =  [];
        foreach($data as $key =>  $item){
                    $total = '0.00';
                    $text =    !empty($item->law_case_to->law_basic_arrest_to->title) ?  'คดี'.$item->law_case_to->law_basic_arrest_to->title  : '' ;
                    if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส

                                        $text .=    !empty($item->law_reward_group_to->title) ? ' ในฐานะ'.$item->law_reward_group_to->title : '';

                                        $group  =  @$item->law_calculation2_many->where('id','<=',$item->law_calculation2_to->id)->count();

                                if( !empty($item->law_calculation2_to->average) &&  $item->law_calculation2_to->average > 1){

                                        $text .=    !empty($item->law_calculation2_to->division) ? ' (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($item->law_calculation2_to->division / $item->law_calculation2_to->average),2).' %)' : '';
                                }else{
           
                                        $text .=    !empty($item->law_calculation2_to->division) ? ' (กลุ่มที่ '.$group.' ร้อยละ '. HP::number_format(($item->law_calculation2_to->division),2).' %)': '' ;
                                }
    
                                $total =  !empty($item->law_calculation2_to->total) ? $item->law_calculation2_to->total : '0.00';
                    }else{
                                $text .=    !empty($item->law_calculation3_to->name) ? ' ในฐานะ'.$item->law_calculation3_to->name : '';

                                 
                                 $group  =  @$item->law_calculation3_no_many->where('id','<=',$item->law_calculation3_to->id)->count();

                                if( !empty($item->law_calculation3_to->average) &&  $item->law_calculation3_to->average > 1){
                                    $text .=     !empty($item->law_calculation3_to->division) ?  ' (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($item->law_calculation3_to->division / $item->law_calculation3_to->average),2).'%)' : '';
                                 }else{
                                    $text .=     !empty($item->law_calculation3_to->division) ?  ' (กลุ่มที่ '.$group.'  ร้อยละ '.  HP::number_format($item->law_calculation3_to->division,2).'%)' : '';
                                 }
    
    
                                $total = !empty($item->law_calculation3_to->total) ? $item->law_calculation3_to->total : '0.00';
                    }
               
                    if(in_array($text,$array)){
                         $no++;
                         $i++;      
                    }else{
                         $array[] =  $text;
                         $no = 1;
                         $i  += 2;      
                    }
    
                    $std                            = new stdClass;
                    $std->no                        = $no;
                    $std->basic_reward_group_id     = $item->basic_reward_group_id;
                    $std->text                      = $text;
                    $std->offend_name               = $no.'. '.(!empty($item->law_case_to->offend_name) ? 'ชื่อคดี '.$item->law_case_to->offend_name : '');
                    $std->total                     =  $total;
                  
                    if($i <= $set){
                        $request[$key1][] = $std;
                    }else{
                        $key1 ++;
                        $i = 0;
                        $request[$key1][] = $std;
                    }
        }
   
        // exit;
        return $request;
      }

      

        public  function SetListStatement2($data = [],$set = 10){
            if($set == '1'){
                $set = '2';
            }
            $request = [];
         
            $array =  $check  = $groups = [];
            foreach($data as $key =>  $item){
                    $total = '0.00';
                
                    if(!in_array($item->basic_reward_group_id,$groups)){ // กลุ่มอื่นๆ

                        $law_case_ids  =  $data->where('basic_reward_group_id',$item->basic_reward_group_id)->pluck('law_case_id');
                        if(count($law_case_ids) > 0){
                         
                         
                                $law_case_get = LawCasesForm::select('id', 'law_basic_arrest_id')->whereIn('id',$law_case_ids)->orderbyRaw('law_basic_arrest_id asc')->get(); 
                       
                                if(count($law_case_get) > 0){
                                          $total1 = $total2 = 0;
                                          $arrest0 = $arrest1 = 0;
                                    foreach($law_case_get as   $item2){

                                           $reward_staff_lists =    LawlRewardStaffLists::where('law_case_id',$item2->id)->where('basic_reward_group_id',$item->basic_reward_group_id)->first();
                                         
                                            if(!empty($reward_staff_lists)){
                                         
                                                if($reward_staff_lists->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                        
                                                     if(!empty($reward_staff_lists->law_calculation2_to)){

                                                        $calculation2 =   $reward_staff_lists->law_calculation2_to;

                                                         $group        =  @$reward_staff_lists->law_calculation2_many->where('id','<=',$calculation2->id)->count();

                                                         $text1 = '' ;  
                                                         $text2 = '' ; 

                                                        if(!empty($item2->law_basic_arrest_id) && $item2->law_basic_arrest_id == '2'){  //มีการจับกุม

                                                            if( !empty($calculation2->average) &&  $calculation2->average > 1){
                                                                $text1 =     !empty($calculation2->division) ?  'คดีจับกุม (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($calculation2->division / $calculation2->average),2).'%)' : '';
                                                             }else{
                                                                $text1 =     !empty($calculation2->division) ?  'คดีจับกุม (กลุ่มที่ '.$group.'  ร้อยละ '.  HP::number_format($calculation2->division,2).'%)' : '';
                                                             }

                                                             if(!in_array($text1,$check)){
                                                                $arrest0 = 0;
                                                                $total1  = 0;
                                                             }

                                                            $total1 +=  !empty($calculation2->total) ? $calculation2->total : '0.00';
                                                            $arrest0 ++;

                                                            $std                           = new stdClass;
                                                            $std->text                     = $text1;
                                                            $std->arrest                   = $arrest0;
                                                            $std->total                    = $total1;
                                                            $std->basic_reward_group_id    = $item->basic_reward_group_id;
                                                            $array[$text1]                 = $std;
                                                            $check[]                       = $text1;

                                                        }else{   //ไม่มีการจับกุม 

                                                             if( !empty($calculation2->average) &&  $calculation2->average > 1){
                                                                $text2 =     !empty($calculation2->division) ?  'คดีไม่จับกุม (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($calculation2->division / $calculation3->average),2).'%)' : '';
                                                             }else{
                                                                $text2 =     !empty($calculation2->division) ?  'คดีไม่จับกุม (กลุ่มที่ '.$group.'  ร้อยละ '.  HP::number_format($calculation2->division,2).'%)' : '';
                                                             }
                                                                if(!in_array($text2,$check)){
                                                                    $arrest1 = 0;
                                                                    $total2  = 0;
                                                                }
                                                               $total2 +=   !empty($calculation2->total) ? $calculation2->total : '0.00';
                                                               $arrest1 ++;

                                                               $std                           = new stdClass;
                                                               $std->text                     = $text2;
                                                               $std->arrest                   = $arrest1;
                                                               $std->total                    = $total2;
                                                               $std->basic_reward_group_id    = $item->basic_reward_group_id;
                                                               $array[$text2]                 = $std;
                                                          
                                                               $check[] = $text2;
                                                        }

                                                     }  

                                                }else{

                                                     if(!empty($reward_staff_lists->law_calculation3_to)){

                                                        $calculation3 =  $reward_staff_lists->law_calculation3_to;

                                                         $group        =  @$reward_staff_lists->law_calculation3_no_many->where('id','<=',$calculation3->id)->count();
                                                        //  $group        = '1'; 
                                                         $text1 = '' ;  
                                                         $text2 = '' ; 

                                                        if(!empty($item2->law_basic_arrest_id) && $item2->law_basic_arrest_id == '2'){  //มีการจับกุม

                                                            if( !empty($calculation3->average) &&  $calculation3->average > 1){
                                                                $text1 =     !empty($calculation3->division) ?  'คดีจับกุม (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($calculation3->division / $calculation3->average),2).'%)' : '';
                                                             }else{
                                                                $text1 =     !empty($calculation3->division) ?  'คดีจับกุม (กลุ่มที่ '.$group.'  ร้อยละ '.  HP::number_format($calculation3->division,2).'%)' : '';
                                                             }


                                                             if(!in_array($text1,$check)){
                                                                    $arrest0 = 0;
                                                                    $total1  = 0;
                                                               }
  
                                                               $total1 +=  !empty($calculation3->total) ? $calculation3->total : '0.00';
                                                               $arrest0 ++;

                                                               $std                           = new stdClass;
                                                               $std->text                     = $text1;
                                                               $std->arrest                   = $arrest0;
                                                               $std->total                    = $total1;
                                                               $std->basic_reward_group_id    = $item->basic_reward_group_id;
                                                               $array[$text1]                 = $std;
                                                               $check[] = $text1;

                                                        }else{   //ไม่มีการจับกุม 

                                                             if( !empty($calculation3->average) &&  $calculation3->average > 1){
                                                                $text2 =     !empty($calculation3->division) ?  'คดีไม่จับกุม (กลุ่มที่ '.$group.' ร้อยละ '.  HP::number_format(($calculation3->division / $calculation3->average),2).'%)' : '';
                                                             }else{
                                                                $text2 =     !empty($calculation3->division) ?  'คดีไม่จับกุม (กลุ่มที่ '.$group.'  ร้อยละ '.  HP::number_format($calculation3->division,2).'%)' : '';
                                                             }

                                                              if(!in_array($text2,$check)){
                                                                    $arrest1 = 0;
                                                                    $total2  = 0;
                                                              }

                                                               $total2 +=   !empty($calculation3->total) ? $calculation3->total : '0.00';
                                                               $arrest1 ++;

                                                               $std                           = new stdClass;
                                                               $std->text                     = $text2;
                                                               $std->arrest                   = $arrest1;
                                                               $std->total                    = $total2;
                                                               $std->basic_reward_group_id    = $item->basic_reward_group_id;
                                                               $array[$text2]                 = $std;
                                                          
                                                               $check[] = $text2;
                                                        }

                                                    
                                                     }  
                                                }
                                            } 

                                    }

                                      $groups[]    = $item->basic_reward_group_id;   
                                      $key1 = 1;
                                      $i    = 0;

                    
                                      $check2                       = [];  
                                    if(count($array) > 0){
                                        foreach($array as $item1){
                                              if($item1->arrest > 0){

                                                if(in_array($item1->text,$check2)){
                                                    $i++;      
                                               }else{
                                                    $check2[] =  $item1->text;
                                                    $i  ++;      
                                               }

                                               if($i == 1){
                                                    $std1                           = new stdClass;
                                                    $std1->basic_reward_group_id    = $item->basic_reward_group_id;
                                                    $std1->text                     = !empty($item->law_reward_group_to->title) ?  'ในฐานะ'.$item->law_reward_group_to->title : '';
                                                    $std1->total                    = '';  
                                                    $request[$key1][]               = $std1;
                                               }

                                                $std3                          = new stdClass;
                                                $std3->basic_reward_group_id   = $item1->basic_reward_group_id;
                                                $std3->text                    = $item1->text.' <b>[จำนวน '.$item1->arrest.' คดี]</b>' ; ;
                                                $std3->total                   = $item1->total;

                                                if($i <= $set){
                                                   $request[$key1][]           = $std3;
                                                }else{
                                                    $key1 ++;
                                                    $i = 0;
                                                    $request[$key1][]         = $std3;
                                                }

                                               

                                            }   
                                        }
                                    }
                                 
                                  
                                }

 
                        }
                    
                    }

            }
          
            return $request;
        }

        
 
      public  function SetTotal($data = []){
            $request = 0;
        foreach($data as $key =>  $item){
                 $total = '0.00';
                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                    $total =  !empty($item->law_calculation2_to->total) ? $item->law_calculation2_to->total : '0.00';
                }else{

                    $total = !empty($item->law_calculation3_to->total) ? $item->law_calculation3_to->total : '0.00';
                }
               $request  +=  $total;
  
        }
        return $request;
      }

 
       public  function SetTotal2($data = []){
            $request = 0;
        foreach($data as $key =>  $item){
               if(!empty( $item->total)){
                $request  +=  $item->total;
               }
        }
        return $request;
      }

      
        public function get_segment_array($item,$number =40){
           $address = !empty($item->address) ? $item->address: '';
           $name    = !empty($item->name) ? $item->name : '' ;

            $segment    =  new   Segment;
            $array      =  $segment->get_segment_array($address);
            $data1      =  [];
            $data2      =  [];
            $data3      =  [];
            $count_word = 0;
            $number2    = ($number*2);
            foreach ($array as $key => $value) {
                $count_word += HP::countString($value);
                if ($count_word <= $number) {
                    $data1[] = $value;
                } else if ($count_word > $number && $count_word <= $number2) {
                    $data2[] = $value;
                } else if ($count_word > $number2) {
                    $data3[] = $value;
                }
            }
            $result = [];
            if(count($data1) > 0){
                $object             =  (object)[];
                $object->name       =   $name;
                $object->address    =    implode('', $data1);
                $result[]           =    $object;
            }

            if(count($data2) > 0){
                $object             =  (object)[];
                $object->name       =   $name;
                $object->address    =    implode('', $data2);
                $result[]           =    $object;
            }

            if(count($data3) > 0){
                $object             =  (object)[];
                $object->name       =   $name;
                $object->address    =    implode('', $data3);
                $result[]           =    $object;
            }
            return  $result;
        }
    
        public function update_receipts(Request $request)
        {      
            $message = false;
            if(!empty($request->id) ){
                $id = $request->id;
                $receipt = LawlRewardRecepts::findOrFail($id);
                if(!is_null($receipt)){
                    $receipt->status = '2'; 
                    $receipt->send_status = '1';
                    $receipt->send_date =  date("Y-m-d H:i:s");
                    $receipt->send_remark =  !empty($request->send_remark) ? $request->send_remark : null;
                    $receipt->save();    
                              
                    $case_number = (!empty($receipt->lawl_reward_recepts_details_to->case_number) ?  $receipt->lawl_reward_recepts_details_to->case_number  : '0000000000000');
                        //ไฟล์เเนบ
                    if(isset( $request->attach )  && $request->hasFile('attach') ){
                            $attach_path = 'law_attach/receipts/';
                            HP::singleFileUploadlaw(
                                    $request->file('attach') ,
                                    $attach_path.$case_number,
                                    '0000000000000',
                                    null,
                                    'Law',
                                    ( (new LawlRewardRecepts)->getTable() ),
                                    $receipt->id,
                                    'evidence',
                                    'ไฟล์แนบหลักฐานใบสำคัญรับ'
                                );
                    }
                }
                $message = true; 
            }
            return response()->json([ 'message' => $message  ]);
        }
    


}
