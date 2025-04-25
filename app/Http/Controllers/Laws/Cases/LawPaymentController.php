<?php

namespace App\Http\Controllers\Laws\Cases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use HP_Law;
use Storage; 
 
use App\Models\Law\Cases\LawCasesForm;  
 use App\Models\Law\Cases\LawCasesPayments;
use Illuminate\Support\Facades\Auth;

class LawPaymentController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/payment/';
    }

 
    
    public function index()
    {
        $model = str_slug('law-cases-payment','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/payment",  "name" => 'ตรวจสอบการชำระ' ],
            ];

    
                                    
            return view('laws.cases.payment.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_payments_detail  = $request->input('filter_payments_detail');
        $filter_amount_date      = $request->input('filter_amount_date');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;
        $filter_paid_channel     = $request->input('filter_paid_channel');
        $filter_users            = $request->input('filter_users');
        $model                   = str_slug('law-cases-payment','-');
 
        $query =  LawCasesForm::query()
                                    ->where(function($query){
                                        $query->whereIn('status',['5','7','8','9','10','11','12','13','14','15']);
                                    })
                                     ->with(array('law_cases_payments_many' => function($query2) {
                                            return  $query2->orderBy('id', 'DESC');
                                     }))  
                                     ->whereHas('law_cases_payments_many', function ($query2) {
                                        return  $query2->WhereNotNull('ref_id');
                                    })
                                    ->with(['law_cases_compare_to'])  
                                    ->whereHas('law_cases_compare_to', function ($query2) {
                                        return  $query2->WhereNotNull('law_cases_id');
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('ref_no', 'LIKE', '%' . $search_full . '%');
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
                                                               $query2->Where('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                           ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_status)  {
                                                          return   $query2->Where('paid_status',$filter_status)->WhereIn('id', LawCasesPayments::select(DB::raw('MAX(id) AS id') )->groupBy('ref_id'));  });  
                                        })
                                        ->when($filter_payments_detail, function ($query, $filter_payments_detail){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_payments_detail) {
                                                      return   $query2->whereHas('law_cases_payments_detail_to', function ($query3)  use($filter_payments_detail) {
                                                               return  $query3->Where('fee_name',$filter_payments_detail);
                                                         });  
                                                      });  
                                            
                                         })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                              return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date,$filter_end_date) {
                                                       return $query2->whereBetween('end_date',[$filter_start_date,$filter_end_date]);
                                                    });  
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date) {
                                                            return $query2->whereDate('end_date',$filter_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_amount_date, function ($query, $filter_amount_date){
                                            if(!is_null($filter_amount_date) && $filter_amount_date == 1){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2)  {
                                                          return  $query2->whereDate('end_date','<=',date('Y-m-d'))->Where('paid_status',1);
                                                    });  
                                            }elseif(!is_null($filter_amount_date) && $filter_amount_date == 2){
                                                return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2) {
                                                                return  $query2->whereDate('end_date','>=',date('Y-m-d'))->Where('paid_status',1);
                                                    });  
                                            }
                                        })
                                        ->when($filter_paid_channel, function ($query, $filter_paid_channel){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2) use ($filter_paid_channel)  {
                                                          return  $query2->Where('paid_channel',$filter_paid_channel);
                                                    });  
                                        })
                                        ->when($filter_users, function ($query, $filter_users){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2) use ($filter_users)  {
                                                            if($filter_users == 'null'){
                                                                return  $query2->WhereNull('updated_by')->Where('paid_status','2');
                                                            }else{
                                                                return  $query2->Where('updated_by',$filter_users);
                                                            }
                                                       
                                                        });  
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$model), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->where(function($query){
                                                $query->where('created_by', Auth::user()->getKey())
                                                    ->Orwhere('lawyer_by', Auth::user()->getKey())
                                                    ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number) ? $item->case_number : '';
                            })
                            ->addColumn('offend_name', function ($item) {
                                    $text  = !empty($item->offend_name) ? $item->offend_name : '';
                                    $text  .= !empty($item->offend_taxid) ? '<br/>'.$item->offend_taxid : '';
                                return $text;
                            })
                            ->addColumn('fee_name', function ($item) {
                                return  !empty($item->law_cases_payments_to->law_cases_payments_detail_to->fee_name) ?  $item->law_cases_payments_to->law_cases_payments_detail_to->fee_name : null; 
                            })  
                            ->addColumn('amount', function ($item) {
                                return  !empty($item->law_cases_payments_to->law_cases_payments_detail_to->amount) ?   number_format($item->law_cases_payments_to->law_cases_payments_detail_to->amount,2) : null; 
                            })
                            ->addColumn('end_date', function ($item) {
                                $text = '';
                                if( !empty($item->law_cases_payments_to)){
                                    if($item->law_cases_payments_to->paid_status == '1'){
                                       $text =  !empty($item->law_cases_payments_to->NumberOfDaysHtml) ?  '<br/>'.$item->law_cases_payments_to->NumberOfDaysHtml  :  ''; 
                                    }
                                }
                                return !empty($item->law_cases_payments_to->end_date) ?   HP::DateThai($item->law_cases_payments_to->end_date).$text  : '';
                            })
                            ->addColumn('status', function ($item) { 
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                        $paid_date =  !empty($item->law_cases_payments_to->paid_date) ? '<br/>'.HP::DateThai($item->law_cases_payments_to->paid_date)  :  '';
                                    return  '<span class="text-success">ชำระเงินแล้ว</span>'.$paid_date;
                                 } 
                                else{
                                    return  '<span class="text-orange">ยังไม่ชำระเงิน</span>';
                                }
                            })  
                            ->addColumn('paid_channel', function ($item) { 
                                    return  !empty($item->law_cases_payments_to->PaidChannelText) ? $item->law_cases_payments_to->PaidChannelText  :  '';
                              })  
                            ->addColumn('user_updated', function ($item) {
                                 $text = '';
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                     $text  .=  !empty($item->law_cases_payments_to->user_updated->FullName) ? $item->law_cases_payments_to->user_updated->FullName : "e-Payment";
                                }else{
                                    $text  .=   '-';
                                }
                                return $text;
                            })
                            ->addColumn('action', function ($item)   use ($model){
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                    $btn_color = 'btn-light-success';
                                }else{
                                    $btn_color = 'btn-light-warning';
                                }
                                return self::buttonActionLaw($item, 'law/cases/payment', 'law-cases-payment',true,true,$btn_color);
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
                            ->rawColumns(['checkbox', 'offend_name', 'status', 'end_date', 'user_updated', 'action'])
                            ->make(true);
    }

    
    public static function buttonActionLaw($data, $action_url, $str_slug_name, $show_view = true, $show_edit = true ,$btn_color )
    {
        $form_action = '';
 
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true  ){
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $data->id) . '" title="ดูรายละเอียด" class="btn btn-icon btn-circle btn-light-info"> <i class="fa  fa-search"  style="font-size: 1.5em;"></i> </a>';
        }else{
            // $form_action .= ' <span class="btn btn-icon btn-circle btn-light-info not-allowed"> <i class="fa  fa-search"  style="font-size: 1.5em;"></i> </span>';
        }

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true  ){
              $form_action .= ' <a href="' . url('/' . $action_url . '/' . $data->id . '/edit') . '" 
                                title="บันทึกผลพิจารณาเปรียบเทียบปรับ" class="btn btn-icon btn-circle '.$btn_color.'">
                                <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
                                </a>';
        }else{
            //   $form_action .= ' <span
            //                     title="บันทึกผลพิจารณาเปรียบเทียบปรับ" class="btn btn-icon btn-circle btn-light-success not-allowed">
            //                     <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i>
            //                     </span>';
        }
  
        return $form_action;
    }

    public function edit($id)
    {
        $model = str_slug('law-cases-payment','-');
        if(auth()->user()->can('edit-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/payment",  "name" => 'ตรวจสอบการชำระ' ],
                [ "link" => "/law/cases/payment/".$id."/edit",  "name" => 'แก้ไข' ],
            ];

            $cases          = LawCasesForm::findOrFail($id);
            $payment        =  $cases->law_cases_payments_to;
            $cases->ref1    =  !empty($cases->law_cases_payments_to->app_certi_transaction_pay_in_to->Ref_1) ? $cases->law_cases_payments_to->app_certi_transaction_pay_in_to ->Ref_1: '';
            return view('laws.cases.payment.edit',compact('breadcrumbs', 'cases', 'payment'));

        }
        abort(403);
    }
 
    public function update(Request $request, $id)
    {
        $model = str_slug('law-cases-payment','-');
        if(auth()->user()->can('edit-'.$model)) {

            $cases    = LawCasesForm::findOrFail($id);
            if(!is_null($cases)){

     
                $payment        =  $cases->law_cases_payments_to;
                if(!is_null($payment)){
                    $payments                       = LawCasesPayments::findOrFail($payment->id);
                    $payments->paid_status          = !empty($request->paid_status) ? $request->paid_status : null;
                    $payments->paid_date            = !empty($request->paid_date) ? HP::convertDate($request->paid_date,true) : null;
                    $payments->paid_type            = !empty($request->paid_type) ? $request->paid_type : null;
                    $payments->paid_channel         = !empty($request->paid_channel) ? $request->paid_channel : null;
                    $payments->paid_channel_remark  = !empty($request->paid_channel_remark) ? $request->paid_channel_remark : null;
                    $payments->remark               = !empty($request->remark) ? $request->remark : null;
                    $payments->updated_by           = auth()->user()->getKey();
                    $payments->save();

                    $cases->status           = '12' ; //ตรวจสอบการชำระเงินแล้ว;
                    $cases->save();
                    
                    if( !empty($request->attachs_bill)  && $request->hasFile('attachs_bill')){
                        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                              HP::singleFileUploadLaw(
                                $request->file('attachs_bill') ,
                                $this->attach_path. $cases->case_number,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Law',
                                (  (new LawCasesPayments)->getTable() ),
                                 $payments->id,
                                'attachs_bill',
                                null
                            );
                     }
                }
           }
            
            return redirect('law/cases/payment')->with('flash_message', 'เรียบร้อยแล้ว');

        }
        abort(403);
    }

    public function show($id)
    {
        $model = str_slug('law-cases-payment','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/payment",  "name" => 'ตรวจสอบการชำระ' ],
                [ "link" => "/law/cases/payment/".$id."/edit",  "name" => 'แก้ไข' ],
            ];

            $cases          = LawCasesForm::findOrFail($id);
            $payment        =  $cases->law_cases_payments_to;
            $cases->ref1    =  !empty($cases->law_cases_payments_to->app_certi_transaction_pay_in_to->Ref_1) ? $cases->law_cases_payments_to->app_certi_transaction_pay_in_to ->Ref_1: '';
            return view('laws.cases.payment.show',compact('breadcrumbs', 'cases', 'payment'));

        }
        abort(403);
    }
 

    

}
