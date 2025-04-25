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
use App\Models\Law\Cases\LawCasesPaymentsDetail;
use App\Models\Law\Cases\LawCasesCompare;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Law\Cases\LawCasesCompareAmounts; 
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Log\LawNotify; 

use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Cases\MailCompares;
use Illuminate\Support\Facades\Auth;

class LawPayinController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/payin/';
    }

 
    
    public function index()
    {
        $model = str_slug('law-cases-payin','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/payin",  "name" => 'ใบชำระเงิน Pay-in - เปรียบเทียบปรับ' ],
            ];
            return view('laws.cases.payin.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_payments_detail  = $request->input('filter_payments_detail');
        $filter_ref1             = $request->input('filter_ref1');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;
        $filter_status_pay       = $request->input('filter_status_pay');
        


        $model                   = str_slug('law-cases-payin','-');

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
                                            if($filter_status == '1'){
                                                return  $query->with(['law_cases_payments_many'])  
                                                             ->doesntHave('law_cases_payments_many');
                                           }else{
                                               return  $query->whereHas('law_cases_payments_many', function ($query2)   {
                                                              return  $query2->WhereNotNull('ref_id');
                                                     });  
                                           }
                                        })
                                        ->when($filter_payments_detail, function ($query, $filter_payments_detail){
                                           return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_payments_detail) {
                                                     return   $query2->whereHas('law_cases_payments_detail_to', function ($query3)  use($filter_payments_detail) {
                                                              return  $query3->Where('fee_name',$filter_payments_detail);
                                                        });  
                                                     });  
                                           
                                        })
                                        ->when($filter_ref1, function ($query, $filter_ref1){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_ref1) {
                                                      return   $query2->whereHas('app_certi_transaction_pay_in_to', function ($query3)  use($filter_ref1) {
                                                               return  $query3->Where('Ref_1',$filter_ref1);
                                                         });  
                                                      });  
                                            
                                         })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                              return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date,$filter_end_date) {
                                                       return $query2->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                                    });  
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date) {
                                                        return $query2->whereDate('created_at',$filter_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_status_pay, function ($query, $filter_status_pay){
                                             return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                            ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_status_pay)  {
                                                           return  $query2->Where('paid_status',$filter_status_pay);
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
                            ->addColumn('checkbox', function ($item) {
                                if(!empty($item->law_cases_payments_to->paid_status)  && ($item->law_cases_payments_to->paid_status == '2' || $item->law_cases_payments_to->cancel_status == '1'  || $item->law_cases_payments_to->end_date < date("Y-m-d") ) ){
                                    return '';
                                }else{
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"    value="'. $item->id .'">';
                                }
                               
                            })
                            ->addColumn('case_number', function ($item) {
                                    $text  = !empty($item->case_number) ? $item->case_number : '';
                                if(count($item->law_cases_payments_cancel_status_many) >= '1'){
                                    $text .= '<br/><span class="font-medium-7 record" data-id="'.$item->id.'" >(ประวัติ '.count($item->law_cases_payments_cancel_status_many).' ครั้ง)</span>';
                                }
                                return $text;
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
                            ->addColumn('status', function ($item) { 
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                    return  '<span class="text-success">ชำระแล้ว</span>';
                                 }  else if(!empty($item->law_cases_payments_to)){
                                     $payment  =  $item->law_cases_payments_to;
                                     if(!is_null($payment->end_date)){
                                          $number_pay_in = (strtotime($payment->end_date) - strtotime(date('Y-m-d'))) /(60*60*24);
                                        if($payment->end_date >= date("Y-m-d")){
                                           return  '<span class="text-success">สร้าง Pay-in แล้ว</span><br/><span class="text-warning">(รอชำระ '.$number_pay_in. ' วัน)</span>';
                                        }else{
                                           return  '<span class="text-success">สร้าง Pay-in แล้ว</span><br/><span class="text-danger">(เกินกำหนด '.$number_pay_in. ' วัน)</span>';
                                        }
                                     }else{
                                           return  '<span class="text-danger">รอสร้าง Pay-in</span>';
                                     }
                                }
                                else{
                                    return  '<span class="text-muted">รอสร้าง Pay-in</span>';
                                }
                            })  

                            ->addColumn('ref', function ($item) {
                                return !empty($item->law_cases_payments_to->app_certi_transaction_pay_in_to->Ref_1) ? $item->law_cases_payments_to->app_certi_transaction_pay_in_to->Ref_1: '';
                            })
                            ->addColumn('user_created', function ($item) {
                                    $text  = !empty($item->law_cases_payments_to->user_created->FullName) ? $item->law_cases_payments_to->user_created->FullName : '';
                                    $text  .= !empty($item->law_cases_payments_to->created_at) ? '<br/>'.HP::DateThai($item->law_cases_payments_to->created_at) : '';
                                return $text;
                            })
                       
                            ->addColumn('action', function ($item)   use ($model){
                                $button = '';
 
                                if(!empty($item->law_cases_payments_to) && auth()->user()->can('edit-'.$model)){
                                    $payment  =  $item->law_cases_payments_to;
                                    if(!is_null($payment->end_date) && $payment->paid_status != '2'){
                                       if($payment->end_date < date("Y-m-d") || $payment->cancel_status == '1'){
                                         $button .= ' <a   href="' . url('law/cases/payin/'.$item->id.'/edit')  . ' "    title="Pay In"  >
                                                       <img src="'.asset('icon/money-icon.png').'"   class="rounded-circle"  height="35" width="35" >
                                             </a>';
                                       }
                                    } 
                               }
                   
                               if (!empty($item->law_cases_payments_to->file_law_cases_pay_in_to) && auth()->user()->can('printing-'.$model)){
                                  $pay_in = $item->law_cases_payments_to->file_law_cases_pay_in_to;
                                  $button .= ' <a   href="' . url('funtions/get-law-view/files/'.$pay_in->url.'/'.(!empty($pay_in->filename) ? $pay_in->filename :  basename($pay_in->url)))  . ' "    title="Pay In"    target="_blank">
                                                       <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="35" width="35" >
                                             </a>';
                                 }
                                return $button;

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
                            ->rawColumns(['checkbox', 'case_number', 'offend_name', 'status', 'user_created', 'action'])
                            ->make(true);
    }


    public function edit($id)
    {
        $model = str_slug('law-cases-payin','-');
        if(auth()->user()->can('edit-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/payin",  "name" => 'สร้างใบชำระเงิน (Pay-in)' ],
                [ "link" => "/law/cases/payin/".$id."/edit",  "name" => 'แก้ไข' ],
            ];

            $cases    = LawCasesForm::findOrFail($id);
            $payment  =  $cases->law_cases_payments_to;


            $compare                              =  LawCasesCompare::where('law_cases_id', $cases->id )->first();
            if(!empty($compare->law_cases_compare_amounts_many) && count($compare->law_cases_compare_amounts_many)  > 0){
                $compare_amounts =  $compare->law_cases_compare_amounts_many;
                $law_notify =    LawNotify::where('ref_table',(new LawCasesCompare)->getTable())->where('ref_id',$compare->id)->where('name_system','ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ')->orderby('id','desc')->first();
            }else{
                $compare_amounts =   [new LawCasesCompareAmounts];
                $law_notify = null;
            }


            return view('laws.cases.payin.edit',compact('breadcrumbs', 'cases', 'payment', 'compare_amounts', 'law_notify'));

        }
        abort(403);
    }
 
    public function update(Request $request, $id)
    {
        $model = str_slug('law-cases-payin','-');
        if(auth()->user()->can('edit-'.$model)) {

            $cases    = LawCasesForm::findOrFail($id);
            $law_compare = LawCasesCompare::where('law_cases_id', $id)->first();
            if(!is_null($cases) && !is_null($law_compare)){
                $ref_table = (new LawCasesForm)->getTable();
                $payments = LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->orderby('id','desc')->first();
                $payments->status     =  '2';
            if($request->condition_type == '1'){ // เรียกเก็บเงินค่าปรับ 


                    // $detail                             =  LawCasesPaymentsDetail::where('law_case_payments_id',$payments->id)->first();
                // if(is_null($detail)){
                    // $detail                          = new LawCasesPaymentsDetail;
                    // $detail->created_by              = auth()->user()->getKey();
                // }
                    // $detail->law_case_payments_id        = $payments->id; 
                //    $detail->fee_name                    = 'ค่าปรับเปรียบเทียบคดี';
                    // $detail->fee_name                    = 'ค่าปรับเปรียบเทียบคดี'.(!empty($cases->law_basic_arrest_to->title)  ? ' (กรณี'.$cases->law_basic_arrest_to->title.')' : '') ;
                    // $detail->remark_fee_name             =!empty($request->remark_fee_name) ? $request->remark_fee_name:null;
                    // $detail->amount                      = !empty($payments->amount) ? $payments->amount:null;
                    // $detail->save();

              
                $arrContextOptions=array();
 
                if($cases->law_basic_arrest_id == '1'){ // ไม่มีการจับกุม
                    $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',1)->first();
                    $certify = '7';
                }else{ // มีการจับกุม
                    $setting_payment = CertiSettingPayment::where('certify',8)->where('payin',1)->where('type',1)->first();
                    $certify = '8';
                }

                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$cases->case_number-$payments->ordering", false, stream_context_create($arrContextOptions));
                $api = json_decode($content);
   
                if(!empty($api)){
                        $transaction = HP::TransactionPayIn1($id,$ref_table,$certify,$payments->ordering,$api,"$cases->case_number-$payments->ordering");
                      
                        $payments->app_certi_transaction_pay_in_id     =  $transaction->id;
                        $payments->save();
                    
                        $file_payin  = self::storeFilePayin($setting_payment,$cases,$payments,(new LawCasesPayments)->getTable(),'attach_payin','ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ');
                
                        if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                           HP::getFileStoragePath($file_payin->url);
                       }

    
                }

               }else{

                            if(is_null($payments)){
                                $payments              = new LawCasesPayments;
                                $payments->created_by  = auth()->user()->getKey();
                            } 
                            $payments->ref_table       = $ref_table;
                            $payments->ref_id          = $id;
                            $payments->condition_type  =  !empty($request->condition_type) ? $request->condition_type : '2' ; // ไม่เรียกเก็บค่าปรับ 
        
                            $payment                   = $request['payment'];
                            if(!empty($payment)){   
                                $payments->remark      = $payment['remark'];
                            }
                            $payments->save();
                }

                $law_compare->total              =  !empty($payments->amount) ? $payments->amount:null;
                $law_compare->save();

                // ผลเปรียบเทียบปรับ
                $compare_amount =  $request['compare_amount'];
                if(!empty($compare_amount)){   
                    self::law_cases_operations($law_compare,$compare_amount); 
                }

                $attachs = $law_compare->file_law_cases_compare_to;
                if(!is_null($attachs) && HP::checkFileStorage($attachs->url)){
                        HP::getFileStoragePath($attachs->url);
                        $url        =  $attachs->url;
                 }
 
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
                                'law_compare'    => $law_compare,
                                'title'          => "ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ เลขที่อ้างอิง $cases->ref_no",
                                'attachs'        => !empty($url)  ? $url : '',
                                'file_payin'        => !empty($file_payin) && HP::checkFileStorage($file_payin->url) ? $file_payin->url : '',
                            ]; 
                            
                    $log_email =  HP_Law::getInsertLawNotifyEmail(1,
                                                                ((new LawCasesCompare)->getTable()),
                                                                $law_compare->id,
                                                                'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ',
                                                                "ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ  เลขที่อ้างอิง $cases->ref_no",
                                                                view('mail.Law.Cases.compares', $data_app),
                                                                null,  
                                                                null,  
                                                                json_encode($email_results)   
                                                                );

                        $html = new MailCompares($data_app);
                          Mail::to($email_results)->send($html);
                }
              }
            }
            return redirect('law/cases/payin')->with('flash_message', 'เรียบร้อยแล้ว');

        }
        abort(403);
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

    public function save_payin(Request $request)
    {      
                $message = false;
            if(!empty($request->ids) && count($request->ids) > 0){
                    $ref_table = (new LawCasesForm)->getTable();
                foreach($request->ids as $id){ 
                         $requestData   = [];
                        $payment =  LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->orderby('id','desc')->first();
                    if(!is_null($payment)){ 
                        $payment->cancel_status  =  '1' ; // ยกเลิก
                        $payment->cancel_remark = !empty($request->cancel_remark) ? $request->cancel_remark : null;
                        $payment->cancel_by     =  auth()->user()->getKey();
                        $payment->cancel_at     =  date('Y-m-d H:i:s');
                        $payment->save(); 
                    }
                } 
                $message = true;
            }

            return response()->json([
                                     'message' => $message
                                    ]);
      
    }
 


    public function storeFilePayin($setting_payment, $cases = '', $payments = '', $table_name = '', $section = '',$attach_text  = '')
    {
              $arrContextOptions=array();
                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$cases->case_number-$payments->ordering";
                // $filename =  'ใบแจ้งชำระเงินค่าปรับเปรียบเทียบ_'.$cases->case_number.'_'.date('Ymd_hms').'.pdf';
                $filename =   $cases->case_number.'_'.date('Ymd_hms').'.pdf';
                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
   
                $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));
                if ($url_pdf) {
                    $attach_path     =   $this->attach_path.$cases->case_number;
                    $fullFileName    =  date('Ymd_hms').'.pdf';
                     $path           =  $attach_path.'/'.$fullFileName;
                    $storagePath     = Storage::put($path, $url_pdf);
                    $file_size       = Storage::size($path);
                    $file_types      =   explode('.',  basename($fullFileName)) ;
                    $file_extension  =  end($file_types);
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
                    return $request;
                }else{
                    return null;
                }
     }


     public function check_pay_in(Request $request)
     {
          $arrContextOptions=array();
  
          $id             =   $request->input('id');
          $amount         =   $request->input('amount');
          $start_date     =   $request->input('start_date');
          $amount_date    =   $request->input('amount_date');
          $ref_table = (new LawCasesForm)->getTable();
          $cases        = LawCasesForm::findOrFail($id);

              $payment  =  $cases->law_cases_payments_to;
  
              if( !is_null($cases) && !is_null($payment) ){

                    if($cases->law_basic_arrest_id == '1'){ // ไม่มีการจับกุม
                        $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',1)->first();
                    }else{ // มีการจับกุม
                        $setting_payment = CertiSettingPayment::where('certify',8)->where('payin',1)->where('type',1)->first();
                    }

             if(!is_null($setting_payment)){
                    $count        = LawCasesPayments::select('id')->where('ref_table',$ref_table)->where('ref_id',$id)->where('condition_type','1')
                                        ->orderby('id','desc')
                                        ->get()->count();
                   if($payment->end_date < date("Y-m-d") || $payment->cancel_status == '1'){
                       $ordering    =  ($count +1);
                    }else{
                      $ordering     =  $count;
                    }
      
                      $payments = LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->where('ordering',$ordering)->orderby('id','desc')->first();
                      if(is_null($payments)){
                          $payments =  new LawCasesPayments;
                          $payments->created_by       =  auth()->user()->getKey();
                      }else{ 
                          $payments->updated_by       =  auth()->user()->getKey();
                      }
                      
                      $payments->ref_table       = $ref_table;
                      $payments->ref_id          = $id;
                      $payments->condition_type  =  '1'; //  เรียกเก็บเงินค่าปรับ 
                      $payments->start_date      =  !empty($start_date) ? HP::convertDate($start_date,true):null;
                      $payments->amount_date     =  $amount_date;  
                      $payments->end_date        =   HP::DatePlus($payments->start_date,$payments->amount_date);
                      $payments->amount          =  !empty($amount) ? str_replace(",","",$amount):null;
                      $payments->paid_status     =  '1'; //  ยังไม่ชำระเงิน 
                      $payments->ordering        =  $ordering; //  ครั้งแรก 
                      $payments->name            = !empty($request->name) ? $request->name : null;
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
                  $arrContextOptions["ssl"] = array(
                                                  "verify_peer" => false,
                                                  "verify_peer_name" => false,
                                              );
              }

              $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$cases->case_number-$ordering", false, stream_context_create($arrContextOptions));
              $api = json_decode($content);
              if(!is_null($api) && is_array($api) && array_key_exists(0, $api) && property_exists($api[0], 'error')){
                  return response()->json([
                                              'message'      =>  false,
                                              'status_error' => HP::getErrorCode($api->returnCode)
                                          ]);
               }elseif(!is_null($api) && $api->returnCode != '000'){
                      return response()->json([
                                               'message'      => false,
                                               'status_error' => $api[0]->error->message
                                              ]);
                }else{
                  return response()->json([
                                              'message' =>  true
                                          ]);
                }
 
            }else{
                return response()->json([
                                    'message' =>  false,
                                    'status_error' => 'ยังไม่มีการเชื่อมต่อ api'
                                    ]);
            }
        }else{
            return response()->json([
                                'message' =>  false,
                                'status_error' => 'ยังไม่มีการเชื่อมต่อ api'
                                ]);
        }
 
         
     }

     public function data_payments(Request $request)
     {

       $id              =   $request->input('id');
       $ref_table       = (new LawCasesForm)->getTable();
       $payments        = LawCasesPayments::where('ref_table',$ref_table)->where('ref_id',$id)->where('cancel_status','1')->get();
       $data            = [];
       if(count($payments) > 0){
           foreach($payments as $payment){
                $object                         = (object)[]; 
                $object->amount                 =  !empty($payment->amount) ? number_format($payment->amount,2) : ''; 
                $object->ref1                   =  !empty($payment->app_certi_transaction_pay_in_to->Ref_1) ? $payment->app_certi_transaction_pay_in_to->Ref_1 : ''; 
                $object->full_name              = !empty($payment->user_created->FullName) ? $payment->user_created->FullName : '';
                $object->cancel_remark          = !empty($payment->cancel_remark) ? $payment->cancel_remark : '';
                $object->created_at             = !empty($payment->created_at) ? HP::DateThai($payment->created_at) : '';
     
                if (!empty($payment->file_law_cases_pay_in_to)){
                    $pay_in = $payment->file_law_cases_pay_in_to;
                   $object->button              = ' <a   href="' . url('funtions/get-law-view/files/'.$pay_in->url.'/'.(!empty($pay_in->filename) ? $pay_in->filename :  basename($pay_in->url)))  . ' "    title="Pay In"    target="_blank">
                                                         <img src="'.asset('icon/pdf02.png').'"   class="rounded-circle"  height="30" width="30" >
                                                    </a>';
                }else{
                    $object->button              = '';
                }
                $data[]                         = $object;
           }
       }
       return response()->json([
                                      'message' => count($payments) > 0 ? true : false,
                                      'datas' => $data
                                      ]);

     }

}
