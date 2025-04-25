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
use ZipArchive;
use App\Models\Law\Cases\LawCasesForm;   
use App\Models\Law\Reward\LawlRewardRecepts;
use App\Models\Law\Reward\LawlRewardWithdraws;   
use App\Models\Law\Reward\LawlRewardWithdrawsDetails;  
use App\Models\Law\Reward\LawlRewardWithdrawsDetailsSub;  

use App\Models\Law\Reward\LawlRewardReceptsDetails;  

use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Reward\MailWithdraws;
class LawWithdrawsController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/withdraws/';
    }



    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');

        $model = str_slug('law-reward-withdraws','-');
        //ผู้ใช้งาน
        $user = auth()->user();
        $query =  LawlRewardWithdraws::query()  
                                            ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                switch ( $filter_condition_search ):
                                                    case "1":
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return $query->Where('reference_no', 'LIKE', '%' . $search_full . '%');
                                                        break;
                                                    default:
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->Where('reference_no', 'LIKE', '%' . $search_full . '%');
                                                                });
                                                        break;
                                                endswitch;
                                            })
                                            ->when($filter_status, function ($query, $filter_status){
                                                 return $query->where('status',$filter_status);
                                                
                                           })
                                           ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงเฉพาะรายการที่บันทึก
                                                 return  $query->where('created_by', $user->getKey());
                                            });


   
        
           
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('reference_no', function ($item) {
                                return  !empty($item->reference_no) ? $item->reference_no : '';
                            })
                            ->addColumn('number', function ($item) {
                                return  !empty($item->law_reward_withdraws_detail_many) ? count($item->law_reward_withdraws_detail_many) : '';
                            })
                            ->addColumn('type', function ($item) {
                                return  !empty($item->WithdrawsTypeText) ? $item->WithdrawsTypeText : '';
                            })
                            ->addColumn('amounts', function ($item) {
                                return  !empty($item->law_reward_withdraws_detail_many) ? number_format($item->law_reward_withdraws_detail_many->sum('amount'),2) : '0.00';
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StatusHtml) ? $item->StatusHtml : '';
                            })
                            ->addColumn('evidence', function ($item) {
                                $btn ='<a    href="'.url('/law/reward/withdraws/print_pdf/'. base64_encode($item->id)).'"     target="_blank">
                                                <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="30" width="30" >
                                        </a>';
                                $btn .=' <a href="' . url('/law/reward/withdraws/download/' .base64_encode($item->id) ) . '"  class="download font-medium-6" target="_blank">
                                            <img src="'.asset('icon/icon-zip.jpg').'"   class="rounded-circle"  height="30" width="30" >
                                       </a>'; 
                               
                             return $btn;
                                             
                            })
                            ->addColumn('created_at', function ($item) {
                                $text  = !empty($item->user_created->FullName) ? $item->user_created->FullName : '-';
                                $text  .= !empty($item->created_at) ? '<br/>'.HP::DateThai($item->created_at) : '';
                            return $text;
                            })
                   
                            ->addColumn('action', function ($item) {
                                return self::buttonActionLaw($item, 'law/reward/withdraws', 'law-reward-withdraws');
                           })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['type', 'status', 'evidence', 'created_at', 'action'])
                            ->make(true);
    }

    public static function buttonActionLaw($data, $action_url, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true)
    {
        $form_action = '';
        // if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true ){
        //       $form_action .= ' <a href="' . url('/' . $action_url . '/' . $data->id . '/edit') . '" 
        //                           class="btn btn-icon btn-circle btn-light-warning">
        //                         <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
        //                         </a>';
        // }else{
        //       $form_action .= ' <span
        //                              class="btn btn-icon btn-circle btn-light-warning not-allowed">
        //                         <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
        //                         </span>';
        // }

        // if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true) {
        //     $form_action .=' <a    href="'.url('/' . $action_url . '/print_pdf/'. base64_encode($data->id)).'"     target="_blank">
        //                                  <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="30" width="30" >
        //                   </a>';
        //   }else{
        //      $form_action .= ' <span class="rounded-circle  not-allowed" > <img src="'.asset('icon/pdf03.png').'"  height="30" width="30" > </span>';  
        //   }
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true ){
            if($data->status == '2'){
                $btn    =    'btn-light-success';
            }else{
                $btn     =   'btn-light-primary';
            }
              $form_action .= ' <a href="' . url('/' . $action_url . '/' . $data->id . '/edit') . '" 
                                  class="btn btn-icon btn-circle '.$btn.'">
                                <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
                                </a>';
        } 
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true) {
            $emails = '';
            if(!empty($data->approve_emails)){
                $emails = $data->approve_emails;
            }else  if(count($data->EmailsTitle) > 0){
                $emails = implode(",",$data->EmailsTitle);
            }
            if($data->status == '2'){
                $btn    =    'btn-light-success';
            }else{
                $btn     =   'btn-light-warning';
            }
            $form_action .= ' <span 
                                data-id="'.$data->id.'"  
                                data-status="'.$data->status.'"  
                                data-approve_date="'.(!empty($data->approve_date) ?   HP::revertDate($data->approve_date,true) :  HP::revertDate(date("Y-m-d"),true)).'"   
                                data-approve_remark="'.$data->approve_remark.'"  
                                data-url="'.(!empty($data->attach_file) ?  $data->attach_file->url : '').'"  
                                data-filename="'.(!empty($data->attach_file) ?  $data->attach_file->filename : '').'"  
                                data-approve_status="'.$data->approve_status.'"
                                data-approve_emails="'.$emails.'"  
                 
                                class="btn btn-icon btn-circle '.$btn.' withdraws"> 
                                <i class="fa  fa-file-text-o"  style="font-size: 1.5em;"></i> 
                            </span>';
          } 
  
        return $form_action;
    }

 
    public function index()
    {
        $model = str_slug('law-reward-withdraws','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/withdraws",  "name" => 'เบิกเงินรางวัล' ],
            ];
 
            return view('laws.reward.withdraws.index',compact('breadcrumbs'));
        }
        abort(403);
    }
    public function create()
    {
        $model = str_slug('law-reward-withdraws','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                                [ "link" => "/law/reward/withdraws",  "name" => 'เบิกเงินรางวัล' ],
                                [ "link" => "/law/reward/withdraws/create",  "name" => 'เพิ่ม' ],
                            ];
            return view('laws.reward.withdraws.create',compact('breadcrumbs'));
        }
        return abort(403);;

    }

    public function store(Request $request)
    {
        $model = str_slug('law-reward-withdraws','-');
        if(auth()->user()->can('add-'.$model)) {
  
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all(); 
            $requestData['reference_no']     =   self::reference_no();
            $requestData['check_file']       = isset($request->check_file) ? '1' : '0';
            $requestData['status']           = '1' ;
            if($request->filter_type == '2'){  // รายเดือน
                $requestData['filter_paid_date_start'] = !empty($request->filter_paid_date) ? HP::convertDate($request->filter_paid_date,true) : null;
            }else if($request->filter_type == '3'){  // ช่วงวันที่
                $requestData['filter_paid_date_start'] = !empty($request->filter_paid_date_start) ? HP::convertDate($request->filter_paid_date_start,true) : null;
                $requestData['filter_paid_date_end'] = !empty($request->filter_paid_date_end) ? HP::convertDate($request->filter_paid_date_end,true) : null;
            }

            $withdraws = LawlRewardWithdraws::create($requestData);

            $details =  $request['details'];
            if(!empty($details)){   
                self::law_withdraws_details($withdraws,$details,$request); 
            }
 
            return redirect('law/reward/withdraws')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return abort(403);
    }
    public function edit($id)
    {
        $model = str_slug('law-reward-withdraws','-');
        if(auth()->user()->can('edit-'.$model)) {
            $withdraws = LawlRewardWithdraws::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/withdraws",  "name" => 'เบิกเงินรางวัล' ],
                [ "link" => "/law/reward/withdraws/$id/edit",  "name" => 'แก้ไข' ],
            ];

            return view('laws.reward.withdraws.edit', compact('withdraws','breadcrumbs'));
        }
        return abort(403);;
    }

    public function update(Request $request, $id)
    {
        $model = str_slug('law-reward-withdraws','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['check_file']       = isset($request->check_file) ? '1' : '0';
            $requestData['status']           = '1' ;
            if($request->filter_type == '2'){  // รายเดือน
                $requestData['filter_paid_date_month'] = !empty($request->filter_paid_date_month) ? $request->filter_paid_date_month : null;
                $requestData['filter_paid_date_year'] = !empty($request->filter_paid_date_year) ? $request->filter_paid_date_year: null;
            }else if($request->filter_type == '3'){  // ช่วงวันที่
                $requestData['filter_paid_date_start'] = !empty($request->filter_paid_date_start) ? HP::convertDate($request->filter_paid_date_start,true) : null;
                $requestData['filter_paid_date_end'] = !empty($request->filter_paid_date_end) ? HP::convertDate($request->filter_paid_date_end,true) : null;
            }

           $withdraws = LawlRewardWithdraws::findOrFail($id);
           $withdraws->update($requestData);

           $details =  $request['details'];
           if(!empty($details)){   
               self::law_withdraws_details($withdraws,$details,$request); 
           }

            return redirect('law/reward/withdraws')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
        return abort(403);;

    }



    
    public function law_withdraws_details($withdraws,$lists,$request)
    {      
                 $subs =  $request['subs'];
        foreach($lists['item_checkbox'] as $key => $item ){
            if(!is_null($item)){
                $details                              =  LawlRewardWithdrawsDetails::where('case_number', @$item)->where('withdraws_id', $withdraws->id)->first();
                if(is_null($details)){
                    $details                          = new LawlRewardWithdrawsDetails;
                    $details->created_by              = auth()->user()->getKey();
                }
                $details->withdraws_id                 = $withdraws->id;
                $details->case_number                  = $item;
                $details->income_number                 = !empty($lists['income_number'][$key]) ? $lists['income_number'][$key]:null;
                $details->amount                      = !empty($lists['amount'][$key]) ?  str_replace(",","",$lists['amount'][$key]):null;
                $details->remark                      = !empty($lists['remark'][$key]) ?  $lists['remark'][$key]:null;
                $details->save();
                if(!empty($subs[$key]) && array_key_exists($key,$subs) ){
                    self::law_withdraws_details_sub($withdraws,$details,$subs[$key],$request);
                }

            }

         }
    
    }

    public function law_withdraws_details_sub($withdraws,$details,$lists,$request)
    {      
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        foreach($lists['recepts_id'] as $key => $item ){
            if(!is_null($item)){
                $sub                              =  LawlRewardWithdrawsDetailsSub::where('law_reward_recepts_id', @$item)->where('withdraws_details_id',$details->id)->first();
                if(is_null($sub)){
                    $sub                          = new LawlRewardWithdrawsDetailsSub;
                }
                $sub->withdraws_id                = $details->withdraws_id ?? null;
                $sub->withdraws_details_id        = $details->id ?? null;
                $sub->law_reward_recepts_id       = $item;
                $recepts = LawlRewardRecepts::findOrFail($item);
                if(!is_null($recepts)){
                    $sub->name                    = $recepts->name  ?? null;
                    // $sub->amount                  = $recepts->amount  ?? null;
                }
                $sub->amount                      = !empty($lists['amount'][$key]) ?  str_replace(",","",$lists['amount'][$key]):null;
                $sub->law_basic_reward_group_id   = !empty($lists['law_basic_reward_group_id'][$key]) ?  $lists['law_basic_reward_group_id'][$key]:null;
                $sub->law_reward_staff_lists_id   = !empty($lists['law_reward_staff_lists_id'][$key]) ?  $lists['law_reward_staff_lists_id'][$key]:null;
                $sub->status                      = !empty($lists['status'][$key]) ?  $lists['status'][$key]:null;
                $sub->remark                      = !empty($lists['remark'][$key]) ?  $lists['remark'][$key]:null;
                $sub->save();
                
                if( !empty($lists['attach']) && array_key_exists($key,$lists['attach'])  ){
                    HP::singleFileUploadLaw(
                        $lists['attach'][$key],
                        $this->attach_path.$withdraws->reference_no,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawlRewardWithdrawsDetailsSub)->getTable() ),
                          $sub->id,
                        'attach_receipt',
                        'ไฟล์แนบใบสำคัญรับเงิน'
                    );
                }
            }
        }

    }

    
    public function reference_no()
    {      
        $prototype = [];
        $prototype[] = 'AC'.(date("y") +43);
        $reference_nos = LawlRewardWithdraws::select('reference_no')
                            ->whereYear('created_at',date("Y"))
                            ->pluck('reference_no');

              if(count($reference_nos) > 0){
                $list_code = [];
                foreach($reference_nos as $max_no ){
                    $new_run = explode('-',  $max_no);
                    if(strlen($new_run[1]) == 4){
                        $list_code[$new_run[1]] = $new_run[1];
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
        return implode('-', $prototype);
    }


    
    public function data_detail_list(Request $request)
    {
        $filter_type             = $request->input('filter_type');
        $filter_case_number      = $request->input('filter_case_number');
        $filter_paid_date_month      = $request->input('filter_paid_date_month');
        $filter_paid_date_year       = $request->input('filter_paid_date_year');
        $filter_paid_date_start  = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end    = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;
     
        $query =  LawlRewardReceptsDetails::query()
                                                ->with(['law_reward_recepts_to','law_reward_staff_lists_to','law_reward_withdraws_detail_to'])  
                                                ->whereHas('law_reward_recepts_to', function ($query2) {
                                                    return  $query2->WhereIn('status',['1','2'])->WhereNull('cancel_by');
                                                })
                                                ->doesntHave('law_reward_withdraws_detail_to')
                                                ->whereHas('law_reward_staff_lists_to', function ($query2) {
                                                    return  $query2->Where('created_by',auth()->user()->getKey());
                                                })
                                                ->when($filter_type, function ($query, $filter_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                                    switch ( $filter_type ):
                                                        case "1":
                                                            return   $query->whereHas('law_reward_staff_lists_to', function ($query2) use ($filter_case_number){
                                                                            if(!empty($filter_case_number)){
                                                                                return $query2->Where('case_number', $filter_case_number);
                                                                            }else{
                                                                                return $query2->WhereNull('id');
                                                                            }
                                                                            
                                                                          });
                                                          
                                                            break;
                                                        case "2":
                                                            return   $query->whereHas('law_reward_staff_lists_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                        return  $query2->with(['law_cases_payments_to'])  
                                                                               ->whereHas('law_cases_payments_to', function ($query3) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                                        if(!is_null($filter_paid_date_year)){
                                                                                            return  $query3->whereMonth('paid_date',$filter_paid_date_month)->whereYear('paid_date',$filter_paid_date_year);
                                                                                    }else{
                                                                                          return  $query3->whereMonth('paid_date',$filter_paid_date_month);
                                                                                    }
                                                                               });
                                                                         });
                                                            case "3":
                                                                return   $query->whereHas('law_reward_staff_lists_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                    return  $query2->with(['law_cases_payments_to'])  
                                                                           ->whereHas('law_cases_payments_to', function ($query3) use  ($filter_paid_date_start,$filter_paid_date_end){
                                                                                   if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                                    return $query3->whereDate('paid_date', '>=', $filter_paid_date_start)
                                                                                                    ->whereDate('paid_date', '<=', $filter_paid_date_end);
                                                                                }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                                    return  $query3->WhereDate('paid_date',$filter_paid_date_start);
                                                                                }
                                                                           });
                                                                     });
                                                       
                                                             break;
                                                        default:
                                                        break;
                                                    endswitch;
                                                })
                                                
                                                ->groupBy('case_number')
                                                ->orderBy('id', 'DESC') ;

       
       $model = str_slug('law-reward-withdraws','-');
       
           
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox"   name="details[item_checkbox]['.$item->case_number.']"  class="item_checkbox" value="'. $item->case_number .'">';
                            })
                            ->addColumn('case_number', function ($item) {
                                return  !empty($item->case_number) ? $item->case_number : '';
                            })
                            ->addColumn('quantity', function ($item) {
                                return  !empty($item->law_reward_recepts_details_many) ? count($item->law_reward_recepts_details_many) : '0';
                            })
                            ->addColumn('amounts', function ($item) {
                                return  !empty($item->law_reward_recepts_details_many) ? number_format($item->law_reward_recepts_details_many->sum('amount'),2) : '0.00';
                            })
                            ->addColumn('pay_account', function ($item) {
                                  $button = '';
                                   if(!empty($item->law_reward_staff_lists_to->law_case_id)){
                                        $button .= ' <a   href="'.url('/law/reward/calculations/print_pdf/'. base64_encode($item->law_reward_staff_lists_to->law_case_id)).'"  target="_blank">
                                                        <img src="'.asset('icon/i-pdf.png').'"   height="30" width="30" >
                                                 </a>';

                                   }
                                  return $button;
                            })
                            ->addColumn('evidence', function ($item) {

                                  $button = '';
                                  $all =  !empty($item->law_reward_recepts_details_many) ? count($item->law_reward_recepts_details_many) : '0';
                                //   $send =  !empty($item->law_reward_recepts_details2_many) ? count($item->law_reward_recepts_details2_many) : '0'; 
                                      $send = 0;
                                      $html = '<div class="modal fade" id="evidence'. $item->case_number .'"  data-backdrop="static" tabindex="-1" role="dialog"   aria-hidden="true">';
                                      $html .=     '<div class="modal-dialog   modal-xl"  >';
                                      $html .=        '<div class="modal-content">';   
                                      $html .=          '<div class="modal-header">';
                                      $html .=              '<button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>';
                                      $html .=              ' <h4 class="modal-title text-left"  >หลักฐานใบสำคัญรับเงิน</h4>';
                                      $html .=           '</div>';
                                      $html .=          '<div class="modal-body">';
                                      $html .=      '<table class="table table-striped">';
                                      $html .=      '<thead>';
                                      $html .=         '<tr>';
                                      $html .=            '<th class="text-center text-top" width="5%" rowspan="2">ลำดับ</th>';
                                      $html .=            '<th class="text-center text-top" width="10%" rowspan="2">ชื่อสิทธิ์</th>';
                                      $html .=            '<th class="text-center text-top" width="10%" rowspan="2">กลุ่มผู้มีสิทธิ์</th>';
                                      $html .=            '<th class="text-center text-top" width="20%" rowspan="2">ใบสำคัญรับเงิน(ลงนาม)</th>';
                                      $html .=            '<th class="text-center text-top" width="10%" rowspan="2">จำนวนเงิน</th>';
                                      $html .=            '<th class="text-center text-top" width="20%" colspan="2">สถานะ</th>';
                                      $html .=            '<th class="text-center text-top" width="15%"rowspan="2">หมายเหตุ</th>';
                                      $html .=         '</tr>';
                                      $html .=         '<tr>';
                                      $html .=            '<th class="text-center  text-top" width="10%">ขอรับเงิน</th>';
                                      $html .=            '<th class="text-center  text-top" width="10%">ไม่ขอรับเงิน</th>';
                                      $html .=         '</tr>';
                                      $html .=      '</thead>';
                                      $html .=      '<tbody   id="tbody_'. $item->case_number .'" >';
                                      
                                         $amount = 0;
                                         $different = 0;
                                      if(count($item->law_reward_recepts_details_many)  > 0){
                                        foreach($item->law_reward_recepts_details_many as $key => $item1){
                                                $recepts =  $item1->law_reward_recepts_to;
                                                if(!is_null($recepts)){
                                                    if(!is_null($recepts->attach_evidence_file)){
                                                        $attach = $recepts->attach_evidence_file;
                                                      
                                                       $url    =  '<a   href="'.(url('funtions/get-law-view/files/'.($attach->url).'/'.(!empty($attach->filename) ? $attach->filename :  basename($attach->url)))).'"   class="link_file"  target="_blank">    
                                                                    <img src="'.asset('icon/i-pdf.png').'"  height="30" width="30" >
                                                                 </a> <span  class="edit_file">แก้ไข</span>';
                                                        $hide = 'hide';
                                                    }else{
                                                        $url  = '';
                                                        $hide = '';
                                                    }
                                                    if($recepts->status == '2'){
                                                        // $checked1 = 'checked';
                                                        // $checked2 = '';
                                                        $send += 1;
                                                    }else{
                                                        // $checked1 = '';
                                                        // $checked2 = 'checked';
                                                    }

                                                  
                                                        $checked1 = 'checked';
                                                        $checked2 = '';
                                               

                                                    $file =  '<div class="fileinput fileinput-new input-group '.$hide.'" data-provides="fileinput" >';
                                                    $file .= '<div class="form-control" data-trigger="fileinput">';
                                                    $file .=       '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                                                    $file .=          '<span class="fileinput-filename"></span>';
                                                    $file .=  '</div>';
                                                    $file .=   ' <span class="input-group-addon btn btn-default btn-file">';
                                                    $file .=   '<span class="fileinput-new">เลือกไฟล์</span>';
                                                    $file .=   '<span class="fileinput-exists">เปลี่ยน</span>';
                                                    $file .=   '<input type="file" name="subs['.$item->case_number.'][attach]['.($recepts->id).']"  accept=".jpg,.png,.pdf" class="check_max_size_file">';
                                                    $file .=   '</span>';
                                                    $file .=  '<a href="#" class="input-group-addon btn btn-default fileinput-exists delete-exists" data-dismiss="fileinput">ลบ</a>';
                                                    $file .='</div>';


                                                    $html .= '<tr>';
                                                    $html .= '<td  class="text-top text-center" >'.($key+1).'</td>';
                                                    $html .= '<td class="text-top text-left" >'.($recepts->name).'</td>';
                                                    $html .= '<td class="text-top text-left" >'.(!empty($item1->law_reward_staff_lists_to->law_reward_group_to->title) ? $item1->law_reward_staff_lists_to->law_reward_group_to->title : '');
                                                    $html .= '<input type="hidden"   name="subs['.$item->case_number.'][law_basic_reward_group_id]['.($recepts->id).']"  value="'.(!empty($item1->law_reward_staff_lists_to->law_reward_group_to->id) ? $item1->law_reward_staff_lists_to->law_reward_group_to->id : '').'">';
                                                    $html .= '</td>';
                                                    $html .= '<td class="text-top text-center " >'.($url).''.($file).'</td>';
                                                    $html .= '<td class="text-top text-right" >'.(!empty($item1->amount) ? number_format($item1->amount,2) : '0.00').'</td>'; 
                                                    $html .= '<td  class="text-top text-center" > <input type="radio"   class="check" data-radio="iradio_square-green" '.($checked1).'  name="subs['.$item->case_number.'][status]['.($recepts->id).']"  value="1"> </td>';
                                                    $html .= '<td  class="text-top text-center" >  <input type="radio"  class="check"  data-radio="iradio_square-green"   '.($checked2).' name="subs['.$item->case_number.'][status]['.($recepts->id).']"   value="2"> </td>';
                                                    $html .=  '<td class="text-top text-left" > ';
                                                    $html .=  '<textarea   name="subs['.$item->case_number.'][remark]['.($recepts->id).']" rows="1"></textarea> ';
                                                    $html .=  '<input type="hidden"   name="subs['.$item->case_number.'][recepts_id]['.($recepts->id).']"  value="'.($recepts->id).'">';
                                                    $html .=  '<input type="hidden"   name="subs['.$item->case_number.'][law_reward_staff_lists_id]['.($recepts->id).']"  value="'.(!empty($item1->law_reward_staff_lists_id) ? $item1->law_reward_staff_lists_id : '').'">';
                                                    $html .=  '<input type="hidden"   name="subs['.$item->case_number.'][amount]['.($recepts->id).']"  value="'.(!empty($item1->amount) ? number_format($item1->amount,2) : '0.00').'" class="amount">';
                                                    $html .=  '</td>'; 
                                
                                                    $html .=  '</tr>';
                                                          $amount += !empty($item1->amount) ? $item1->amount : 0;
                                                    //  if($recepts->status == '1'){
                                                          $different += !empty($item1->amount) ? $item1->amount : 0;
                                                    //  }
                                                }
                                            } 
                                        }
                                      $html .=     '</tbody>';

                                      $html .= '<tfoot id="tfoot_'. $item->case_number .'">';
                                      $html .= ' <tr>';
                                      $html .=    '<td  class="text-top text-right" colspan="4"><b>รวม</b></td>';
                                      $html .=    '<td  class="text-top text-right" >'. number_format($amount,2) .'</td>';
                                      $html .=    '<td  class="" colspan="3"></td>';
                                      $html .=    '</tr>';
                                      $html .= ' <tr>';
                                      $html .=    '<td  class="text-top text-right" colspan="4"><b>ส่วนต่าง</b></td>';
                                      $html .=    '<td  class="text-top text-right different"  >'. number_format($different,2) .'</td>';
                                      $html .=    '<td  class="" colspan="3"></td>';
                                      $html .=    '</tr>';
                                      $html .= '</tfoot>';
                                      $html .= '</table>';

                                      $html .= '<p class="text-left text-muted" > หมายเหตุ : ส่วนต่าง หมายถึง ยอดเงินทั้งหมดที่สิทธิ์ได้รับเงิน ไม่ประสงค์ขอรับเงิน ซึ่งเงินดังกล่าวตกเป็นรายได้แผ่นดิน</p>';

                                      $html .='             <div class="text-center ">';
                                      $html .=                  '<button type="button" class="btn btn-primary save_evidence"  data-case_number="'. $item->case_number .'"   data-dismiss="modal" aria-label="Close">';
                                      $html .=                      'บันทีก';
                                      $html .=                  '</button>&nbsp;&nbsp;';
                                      $html .=                  '<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">';
                                      $html .=                      'ยกเลิก';
                                      $html .=                  '</button>';
                                      $html .=              '</div>';

                                      $html .=            '</div>';
                                      $html .=        '</div>';
                                      $html .=   '</div>';
                                      $html .= '</div>';
                                  $button .= ' <span  class="evidence" data-case_number="'. $item->case_number .'" ><span class="send-'. $item->case_number .'">'.($send).'</span>/'.($all).'</span>'.$html;

                                return $button;
                            })
                            ->addColumn('action', function ($item) {
                                    $all =  !empty($item->law_reward_recepts_details_many) ? count($item->law_reward_recepts_details_many) : '0';
                                    $amount = !empty($item->law_reward_recepts_details_many) ? number_format($item->law_reward_recepts_details_many->sum('amount'),2) : '0.00';

                                    $button = '  <textarea name="details[remark]['.$item->case_number.']" class="form-control"  rows="1"></textarea>';
                                    $button .= '<input type="hidden"   name="details[case_number]['.$item->case_number.']"  value="'.$item->case_number.'">';
                                    $button .= '<input type="hidden"   name="details[income_number]['.$item->case_number.']"  value="'.$all.'">';
                                    $button .= '<input type="hidden"   name="details[amount]['.$item->case_number.']"  value="'.$amount.'">';
                              return $button;
                          })

                          
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'pay_account', 'evidence', 'action'])
                            ->make(true);
    }


    public function print_pdf($id, $type="I")
    {
        $id =   base64_decode($id);
        $withdraws = LawlRewardWithdraws::findOrFail($id);
        $mpdf = new Mpdf([
                           'format'            => 'A4',
                           'mode'              => 'utf-8',
                           'default_font_size' => '15',
                        ]);    

    if($withdraws->filter_type  == '1'){ // รายคดี
        $withdraws_text =  !empty($withdraws->filter_case_number) ?  'ระหว่างเลขคดี '.$withdraws->filter_case_number : '';
    }else    if($withdraws->filter_type  == '2'){ // รายเดือน
        $withdraws_text =   !empty($withdraws->filter_paid_date_month)  && !empty($withdraws->filter_paid_date_year) ? 'ระหว่างเดือน '.HP::MonthList()[$filter_paid_date_month].' '.HP::TenYearListReport()[$withdraws->filter_paid_date_year]  : '';
    }else    if($withdraws->filter_type  == '3'){ // ช่วงวันที่
        $withdraws_text =   !empty($withdraws->filter_paid_date_start)  && !empty($withdraws->filter_paid_date_end) ?   'ระหว่างวันที่ '.HP::DateFormatGroupFullTh($withdraws->filter_paid_date_start,$withdraws->filter_paid_date_end) : '';
    }else{
        $withdraws_text = '';
    }
                 

        
       $data_app = [
                   'withdraws'         => $withdraws,
                   'withdraws_text'     => $withdraws_text
                   ];



        $html  = view('laws.reward.withdraws.pdf', $data_app);
        $mpdf->WriteHTML($html);
        $title = "เบิกเงินรางวัล_".date('Ymd_hms').".pdf";  
        $mpdf->SetTitle($title);
        $mpdf->Output($title, "I");
    }

    public function update_withdraws(Request $request)
    {      
       
        $message = false;
        if(!empty($request->withdraws_id) ){
            $id = $request->withdraws_id;
            $withdraws = LawlRewardWithdraws::findOrFail($id);
            if(!is_null($withdraws)){
                     $approve_emails = [];
                 if(!empty($request->approve_emails)){
                        $emails  = explode(',',$request->approve_emails);
                        if(count($emails) > 0){
                            foreach( $emails as $email ){
                                if(!is_null($email) &&  filter_var( $email, FILTER_VALIDATE_EMAIL) &&  !in_array( $email, $approve_emails)){
                                    $approve_emails[] = $email;
                                }
                            }
                         }
                 }

                $requestData['status']           = 2;
                $requestData['approve_date']   = !empty($request->approve_date) ? HP::convertDate($request->approve_date,true) : null;
                $requestData['approve_remark']   = !empty($request->approve_remark) ? $request->approve_remark   :  null ;  
                $requestData['approve_status']     = !empty($request->approve_status) ? $request->approve_status   :  '0' ;  
                $requestData['approve_emails']     = !empty($approve_emails) ?  implode(",",$approve_emails)  :  null ;  
                $requestData['approve_at']       = date('Y-m-d H:i:s'); 
                $requestData['approve_by']       =  auth()->user()->getKey();
                $withdraws->update($requestData); 
                    //อัพโหลดไฟล์
                    $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                 // หนังสือแจ้งปรับเปรียบเทียบ
                    if(isset($request->attach)){
                        if ($request->hasFile('attach')) {
                         $file_payin =    HP::singleFileUploadLaw(
                                                                $request->file('attach') ,
                                                                $this->attach_path.$withdraws->reference_no,
                                                                ( $tax_number),
                                                                (auth()->user()->FullName ?? null),
                                                                'Law',
                                                                ((new LawlRewardWithdraws)->getTable()),
                                                                $withdraws->id,
                                                                'evidence',
                                                                'หลักฐานการอนุมัติเบิกจ่าย'
                                         );
                           if(!empty($file_payin) && HP::checkFileStorage($file_payin->url)){
                                HP::getFileStoragePath($file_payin->url);
                            }
                        }
                    }
                    
                    if( count($approve_emails) > 0 ){
                        $data_app = [
                                    'withdraws'      => $withdraws,
                                    'title'          => "แจ้งผลการเบิกเงินรางวัล เลขที่อ้างอิง $withdraws->reference_no" 
                                ]; 
                                
                        $log_email =  HP_Law::getInsertLawNotifyEmail(2,
                                                                    ((new LawlRewardWithdraws)->getTable()),
                                                                     $withdraws->id,
                                                                    'แจ้งผลการเบิกเงินรางวัล',
                                                                    "แจ้งผลการเบิกเงินรางวัล เลขที่อ้างอิง $withdraws->reference_no",
                                                                    view('mail.Law.Reward.withdraws', $data_app),
                                                                    null,  
                                                                    null,  
                                                                    implode(",",$approve_emails)
                                                                    );

                            $html = new MailWithdraws($data_app);
                            Mail::to($approve_emails)->send($html);
                    }
                    $message = true; 
            }
         
        }
        return response()->json([ 'message' => $message  ]);
    }



    public function download($id)
    {      
        $id =   base64_decode($id);
        $withdraws = LawlRewardWithdraws::findOrFail($id);
        $details =  LawlRewardWithdrawsDetails::where('withdraws_id',$id)->get();
        $details_sub = LawlRewardWithdrawsDetailsSub::where('withdraws_id',$id)->get();
 

        $public_dir = public_path("uploads/files");
        $zipFileName = 'AllDocuments.zip';
  
 
        if(!is_null($withdraws)) {
            //ข้อมูลไฟล์แนบ
 
            // Create ZipArchive Obj
            $zip = new ZipArchive;

                 if ($zip->open($public_dir . '/' . $zipFileName, \ZipArchive::CREATE | ZipArchive::OVERWRITE ) === TRUE) {

                    // บัญชีการจ่ายเงินรางวัล
                    if(count($details) > 0){
                        foreach($details as $item){
                            if(!empty($item->law_cases->id)){ 
                                $file = $this->print_calculations_pdf($item->law_cases->id,$withdraws->reference_no);
                                if(!empty($file)){
                                      $zip->addFile($file, basename($file));
                                }  
                            }
                        }
                    }
 
                       //หลักฐานใบสำคัญรับเงิน (เบิกเงินรางวัล)
                    if(count($details_sub) > 0){
                     
                         foreach($details_sub as $item){

                            //  ใบสำคัญรับเงิน
                                $staff_lists = $item->law_reward_recepts_to;
                            if(!is_null($staff_lists)){
                                $recepts =  !empty($staff_lists->attach_evidence_file) ?  $staff_lists->attach_evidence_file  : null ;
                                if(!is_null($recepts)){
                                    $file = $this->getFileStorage($recepts->url,$recepts->filename,$withdraws->reference_no);
                                    if(!empty($file)){
                                          $zip->addFile($file, basename($file));
                                    }  
                                }
                            }
             
                            //หลักฐานใบสำคัญรับเงิน (เบิกเงินรางวัล)   
                            $attach2 = $item->attach_evidence_file;
                            if(!is_null($attach2)){
                                $file = $this->getFileStorage($attach2->url,$attach2->filename,$withdraws->reference_no);
                                if(!empty($file)){
                                      $zip->addFile($file, basename($file));
                                }  
                            }
                         }
                    }

                        // เบิกเงินรางวัล
                        if(!is_null($withdraws->attach_file)){
                            $attach = $withdraws->attach_file;
                            if(!is_null($attach)){
                                $file = $this->getFileStorage($attach->url,$attach->filename,$withdraws->reference_no);
                                if(!empty($file)){
                                $zip->addFile($file, basename($file));
                                }  
                            }
                        }
                     $zip->close();
                }
        
             return response()->download(public_path("uploads/files/{$zipFileName}"));
         } else {
              return redirect('law/reward/withdraws')->with('error_message', 'ไม่พบไฟล์แนบในมาตรฐานนี้!');
         }
   }

   public  static function print_calculations_pdf($id,$reference_no)
     {

          $cases                         = LawCasesForm::findOrFail($id);
         if(!is_null($cases)){
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
            $title = "คำนวณสินบน_".@$cases->case_number."_".date('Ymd_hms').".pdf";  
            $mpdf->SetTitle($title);
            $path             = public_path('uploads/');
            $attach_path  =  'law_attach/withdraws/'.$reference_no;
    
            if(!File::isDirectory($path.$attach_path)){
               File::makeDirectory($path.$attach_path, 0777, true, true);
           }  
             $file_path = $path.$attach_path.'/'.$title;
             $mpdf->Output($file_path, "F");
             return $file_path;
         }else{
            return '';
         }


     
     }

  public static function getFileStorage($file_path,$filename = '',$reference_no)
   {//get file from storage
             $check =   explode(".",$filename);
            if(count($check) == '2'){
                $filename = $check[0].'_'.date('Ymd_hms').'.'.$check[1];
            }
  
         $result = '';
          $public = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();
          $attach_path     =  public_path('uploads/law_attach/withdraws/').$reference_no .'/'.$filename ;
 
         if (is_file($attach_path)) {//ถ้ามีไลฟ์ที่พร้อมแสดงอยู่แล้ว
              $result   = $attach_path;
        } else {
  
           $exists = Storage::exists($file_path);
           if ($exists) {//ถ้ามีไฟล์ใน storage
              HP::getFileStoragePath($file_path);
               $opts  = array( 
                                    'http'=>array(
                                                 'header' => "Content-Disposition: attachment; filename=$filename"
                                                ),
                             );
                 $context 	= stream_context_create($opts);   
                 $file 	= file_get_contents($public . $file_path, false, $context);       
                 $byte_put = file_put_contents($attach_path,$file, FILE_APPEND);
                 if ($byte_put !== false) {
                    $result   = $attach_path;
                 }
               
           }
 
        }

        return $result;
   }


}
