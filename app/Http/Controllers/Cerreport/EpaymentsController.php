<?php

namespace App\Http\Controllers\Cerreport;


use App\Models\Certify\TransactionPayIn;
use App\Http\Controllers\Controller;
use HP;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\BoardAuditorDate;

use App\Models\Certify\ApplicantIB\CertiIBPayInOne; 
use App\Models\Certify\ApplicantIB\CertiIBAuditors; 
use App\Models\Certify\ApplicantIB\CertiIBAuditorsDate; 

use App\Models\Certify\ApplicantCB\CertiCBPayInOne;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsDate;

use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingAuditorsDate;



class EpaymentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $model = str_slug('cerreport-epayments','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('cerreport.epayments.index');
        }
        abort(403);
    }

    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_type = $request->input('filter_type');
        $filter_certify = $request->input('filter_certify');
        $filter_status_confirmed = $request->input('filter_status_confirmed');
        $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
        $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
        $filter_check_start_date = !empty($request->get('filter_check_start_date'))?HP::convertDate($request->get('filter_check_start_date'),true):null;
        $filter_check_end_date = !empty($request->get('filter_check_end_date'))?HP::convertDate($request->get('filter_check_end_date'),true):null;
       
                  

        $query = TransactionPayIn::query()
                                     ->whereIn('certify',[1,2,3,4,5,6])
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->Where( DB::raw("REPLACE(appno,' ','')"),  'LIKE', "%$search_full%")
                                             ->OrWhere(DB::raw("REPLACE(ref1,' ','')"), 'LIKE', "%".$search_full."%")
                                             ->OrWhere(DB::raw("REPLACE(ReceiptCode,' ','')"), 'LIKE', "%".$search_full."%");
                                    })
                                    ->when($filter_status_confirmed, function ($query, $filter_status_confirmed){
                                        if($filter_status_confirmed == 1){
                                            return $query->where('status_confirmed', $filter_status_confirmed);
                                        }else{
                                            return $query->where('status_confirmed','!=','1')->OrWhereNull('status_confirmed');
                                        }
                                    })
                                    ->when($filter_certify, function ($query, $filter_certify){
                                            return $query->where('certify', $filter_certify);
                                    })
                                    ->when($filter_type, function ($query, $filter_type){
                                        return $query->where('state', $filter_type);
                                })
                                    ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                            return $query->whereBetween('invoiceStartDate',[$filter_start_date,$filter_end_date]);
                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                            return $query->whereDate('invoiceStartDate',$filter_start_date);
                                        }
                                    })
                                    ->when($filter_check_start_date, function ($query, $filter_check_start_date) use($filter_check_end_date){
                                        if(!is_null($filter_check_start_date) && !is_null($filter_check_end_date) ){
                                             // lab
                                           $id_labs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CostAssessment)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new Assessment)->getTable().' AS assessment', 'assessment.id', '=', 'cost.app_certi_assessment_id') 
                                                            ->leftJoin((new BoardAuditor)->getTable().' AS auditor', 'auditor.id', '=', 'assessment.auditor_id') 
                                                            ->leftJoin((new BoardAuditorDate)->getTable().' AS auditor_date', 'auditor_date.board_auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CostAssessment)->getTable())
                                                            ->whereNotNull('assessment.auditor_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                                    
                                              // ib
                                            $id_ibs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiIBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiIBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiIBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);
                                            
                                             // cb
                                             $id_cbs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiCBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiCBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiCBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);

                                            //  tracking
                                            $id_trackings =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new TrackingPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new TrackingAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new TrackingAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new TrackingPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);
                                
                          
                                               return $query->whereIn('id',$id_labs)->OrwhereIn('id',$id_ibs)->OrwhereIn('id',$id_cbs)->OrwhereIn('id',$id_trackings);
 

                                        }else if(!is_null($filter_check_start_date) && is_null($filter_check_end_date)){
                                              // lab
                                           $id_labs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CostAssessment)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new Assessment)->getTable().' AS assessment', 'assessment.id', '=', 'cost.app_certi_assessment_id') 
                                                            ->leftJoin((new BoardAuditor)->getTable().' AS auditor', 'auditor.id', '=', 'assessment.auditor_id') 
                                                            ->leftJoin((new BoardAuditorDate)->getTable().' AS auditor_date', 'auditor_date.board_auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CostAssessment)->getTable())
                                                            ->whereNotNull('assessment.auditor_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                                    
                                              // ib
                                            $id_ibs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiIBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiIBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiIBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                            
                                             // cb
                                             $id_cbs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiCBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiCBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiCBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);

                                            //  tracking
                                            $id_trackings =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new TrackingPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new TrackingAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new TrackingAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new TrackingPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                
                          
                                               return $query->whereIn('id',$id_labs)->OrwhereIn('id',$id_ibs)->OrwhereIn('id',$id_cbs)->OrwhereIn('id',$id_trackings);
                                        }
                                    })  ;


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('appno', function ($item) {
                                return !empty($item->appno)?$item->appno:null;
                            })

                            ->addColumn('state', function ($item) {
                                if( $item->state == 1){
                                    return 'ค่าตรวจประเมิน';
                                }else if( $item->state == 2){
                                    return 'ค่าตรวจธรรมเนียมใบรับรอง';
                                }else{
                                    return '-';
                                }
                            })
                            ->addColumn('certify', function ($item) {
                                if( $item->certify == 1){
                                    return 'ห้องปฏิบัติการ';
                                }else if( $item->certify == 2){
                                    return 'หน่วยตรวจ';
                                }else if( $item->certify == 3){
                                    return 'หน่วยรับรอง';
                                }else if( $item->certify == 4){
                                    return 'ห้องปฏิบัติการ(ติดตาม)';
                                }else if( $item->certify == 5){
                                    return 'หน่วยตรวจ(ติดตาม)';
                                }else if( $item->certify == 6){
                                    return 'หน่วยรับรอง(ติดตาม)';
                                }else{
                                    return '-';
                                }
                            })
                            ->addColumn('date_exam', function ($item) {
                                return !empty($item->DateExamination)?@$item->DateExamination:null;
                            })
                            
                            ->addColumn('Ref', function ($item) {
                                return  (!empty($item->Ref_1)?'Ref.1:'.$item->Ref_1:'-').
                                        (!empty($item->Ref_2)?'<br>'.'Ref.2:'.$item->Ref_2:null);
                            })
                            ->addColumn('invoiceStartDate', function ($item) {
                                return  !empty($item->invoiceStartDate)?HP::DateThai($item->invoiceStartDate):null;
                            })
                            ->addColumn('invoiceEndDate', function ($item) {
                                return  !empty($item->invoiceEndDate)?HP::DateThai($item->invoiceEndDate):null;
                            })
                            ->addColumn('PaymentDate', function ($item) {
                                return  !empty($item->PaymentDate)?HP::DateThai($item->PaymentDate):null;
                            })
                            ->addColumn('amount', function ($item) {
                                return !empty($item->amount)?   number_format($item->amount,2) :null;
                            })
                            ->addColumn('ReceiptCode', function ($item) {
                                return !empty($item->ReceiptCode)?$item->ReceiptCode:null;
                            })
                            ->addColumn('bank_code', function ($item) {
                                return !empty($item->BankCode)?$item->BankCode:null;
                            })

                            ->addColumn('amount_bill', function ($item) {
                                return !empty($item->PayAmountBill) ?  number_format($item->PayAmountBill,2) :null;
                            })
                            ->addColumn('status', function ($item) {
                                if( $item->status_confirmed == 1){
                                    return 'ชำระค่าธรรมเนียมเรียบร้อย';
                                }else{
                                    return 'แจ้งชำระค่าธรรมเนียม';
                                }
                            })
                            ->addColumn('action', function ($item) {

                                 $text =   HP::buttonAction( $item->id, 'cerreport/epayments','Cerreport\EpaymentsController@destroy', 'cerreport-epayments',true,false,false);
                                if((is_null($item->status_confirmed) || $item->status_confirmed != 1) && !Is_null($item->ref1)){
                                    $text .=  ' <span class="btn btn-warning btn-xs transaction_payin"  data-ref1="'.$item->ref1.'"> <i class="fa fa-check"></i> </span>';
                                }
                                return $text;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id','desc');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'Ref','title','date_exam'])
                            ->make(true);
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
        $model = str_slug('cerreport-epayments','-');
        if(auth()->user()->can('view-'.$model)) {
            $epayment =TransactionPayIn::findOrFail($id);
            return view('cerreport.epayments.show', compact('epayment'));
        }
        abort(403);
    }




    public function export_excel(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_type = $request->input('filter_type');
        $filter_certify = $request->input('filter_certify');
        $filter_status_confirmed = $request->input('filter_status_confirmed');
        $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
        $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
        $filter_check_start_date = !empty($request->get('filter_check_start_date'))?HP::convertDate($request->get('filter_check_start_date'),true):null;
        $filter_check_end_date = !empty($request->get('filter_check_end_date'))?HP::convertDate($request->get('filter_check_end_date'),true):null;
       
        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB
        $query = TransactionPayIn::whereIn('certify',[1,2,3,4,5,6])
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->Where( DB::raw("REPLACE(appno,' ','')"),  'LIKE', "%$search_full%")
                                             ->OrWhere(DB::raw("REPLACE(ref1,' ','')"), 'LIKE', "%".$search_full."%")
                                             ->OrWhere(DB::raw("REPLACE(ReceiptCode,' ','')"), 'LIKE', "%".$search_full."%");
                                    })
                                    ->when($filter_status_confirmed, function ($query, $filter_status_confirmed){
                                            if($filter_status_confirmed == 1){
                                                return $query->where('status_confirmed', $filter_status_confirmed);
                                            }else{
                                                return $query->where('status_confirmed','!=','1')->OrWhereNull('status_confirmed');
                                            }
                                    })
                                    ->when($filter_certify, function ($query, $filter_certify){
                                            return $query->where('certify', $filter_certify);
                                    })
                                    ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                            return $query->whereBetween('invoiceStartDate',[$filter_start_date,$filter_end_date]);
                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                            return $query->whereDate('invoiceStartDate',$filter_start_date);
                                        }
                                    })
                                    ->when($filter_check_start_date, function ($query, $filter_check_start_date) use($filter_check_end_date){
                                        if(!is_null($filter_check_start_date) && !is_null($filter_check_end_date) ){
                                             // lab
                                           $id_labs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CostAssessment)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new Assessment)->getTable().' AS assessment', 'assessment.id', '=', 'cost.app_certi_assessment_id') 
                                                            ->leftJoin((new BoardAuditor)->getTable().' AS auditor', 'auditor.id', '=', 'assessment.auditor_id') 
                                                            ->leftJoin((new BoardAuditorDate)->getTable().' AS auditor_date', 'auditor_date.board_auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CostAssessment)->getTable())
                                                            ->whereNotNull('assessment.auditor_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                                    
                                              // ib
                                            $id_ibs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiIBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiIBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiIBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);
                                            
                                             // cb
                                             $id_cbs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiCBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiCBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiCBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);

                                            //  tracking
                                            $id_trackings =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new TrackingPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new TrackingAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new TrackingAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new TrackingPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date)
                                                            ->whereDate('auditor_date.end_date','<=',$filter_check_end_date);
                                
                          
                                               return $query->whereIn('id',$id_labs)->OrwhereIn('id',$id_ibs)->OrwhereIn('id',$id_cbs)->OrwhereIn('id',$id_trackings);
 

                                        }else if(!is_null($filter_check_start_date) && is_null($filter_check_end_date)){
                                              // lab
                                           $id_labs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CostAssessment)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new Assessment)->getTable().' AS assessment', 'assessment.id', '=', 'cost.app_certi_assessment_id') 
                                                            ->leftJoin((new BoardAuditor)->getTable().' AS auditor', 'auditor.id', '=', 'assessment.auditor_id') 
                                                            ->leftJoin((new BoardAuditorDate)->getTable().' AS auditor_date', 'auditor_date.board_auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CostAssessment)->getTable())
                                                            ->whereNotNull('assessment.auditor_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                                    
                                              // ib
                                            $id_ibs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiIBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiIBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiIBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                            
                                             // cb
                                             $id_cbs =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new CertiCBPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new CertiCBAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new CertiCBAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new CertiIBPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);

                                            //  tracking
                                            $id_trackings =    DB::table((new TransactionPayIn)->getTable().' AS pay_in')
                                                            ->select('pay_in.id')
                                                            ->leftJoin((new TrackingPayInOne)->getTable().' AS cost', 'cost.id', '=', 'pay_in.ref_id') 
                                                            ->leftJoin((new TrackingAuditors)->getTable().' AS auditor', 'auditor.id', '=', 'cost.auditors_id') 
                                                            ->leftJoin((new TrackingAuditorsDate)->getTable().' AS auditor_date', 'auditor_date.auditors_id', '=', 'auditor.id') 
                                                            ->where('pay_in.table_name',(new TrackingPayInOne)->getTable())
                                                            ->whereNotNull('cost.auditors_id')
                                                            ->where('pay_in.state',1)
                                                            ->whereDate('auditor_date.start_date','>=',$filter_check_start_date);
                                
                          
                                               return $query->whereIn('id',$id_labs)->OrwhereIn('id',$id_ibs)->OrwhereIn('id',$id_cbs)->OrwhereIn('id',$id_trackings);
                                        }
                                    }) 
                                    ->orderBy('id','desc')
                                    ->get();

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงาน e-Payment');
            $sheet->mergeCells('A1:J1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:J2');
            $sheet->getStyle('A2:J2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'ลำดับ');
            $sheet->setCellValue('B3', 'เลขที่คำขอ');
            $sheet->setCellValue('C3', 'ประเภทค่าใช้จ่าย');
            $sheet->setCellValue('D3', 'การรับรอง');
            $sheet->setCellValue('E3', 'วันที่ตรวจประเมิน');
            $sheet->setCellValue('F3', 'เลขอ้างอิงการแจ้งชำระ');
            $sheet->setCellValue('G3', 'วันที่แจ้งชำระ');
            $sheet->setCellValue('H3', 'จำนวนเงิน');
            $sheet->setCellValue('I3', 'รหัสใบเสร็จรับเงิน');
            $sheet->setCellValue('J3', 'จำนวนที่ชำระ');


            $row = 3; //start row
            $amount = 0;
        if(count($query) > 0){
            foreach ($query as $key => $item) {


                if( $item->state == 1){
                    $state = 'ค่าตรวจประเมิน';
                }else if( $item->state == 2){
                    $state = 'ค่าธรรมเนียมใบรับรอง';
                }else{
                    $state = '-';
                }


                if( $item->certify == 1){
                    $certify = 'ห้องปฏิบัติการ';
                }else if( $item->certify == 2){
                    $certify = 'หน่วยตรวจ';
                }else if( $item->certify == 3){
                    $certify = 'หน่วยรับรอง';
                }else if( $item->certify == 4){
                    $certify = 'ห้องปฏิบัติการ(ติดตาม)';
                }else if( $item->certify == 5){
                    $certify = 'หน่วยตรวจ(ติดตาม)';
                }else if( $item->certify == 6){
                    $certify = 'หน่วยรับรอง(ติดตาม)';
                }else{
                    $certify = '-';
                }

                $Ref =  (!empty($item->Ref_1)?'Ref.1:'.$item->Ref_1:'-');
                $Ref .=   (!empty($item->Ref_2)? "\n".'Ref.2:'.$item->Ref_2:'');

                $row++;
                $sheet->setCellValue('A' . $row,$key+1);
                $sheet->setCellValue('B' . $row, !empty($item->appno)?$item->appno:'');
                $sheet->setCellValue('C' . $row, $state);
                $sheet->setCellValue('D' . $row, $certify);
                $sheet->setCellValue('E' . $row, !empty($item->DateExamination)? str_replace(", ","\n",@$item->DateExamination):'');
                $sheet->setCellValue('F' . $row,  $Ref); 
                $sheet->setCellValue('G' . $row, !empty($item->invoiceStartDate)?HP::DateThai($item->invoiceStartDate):'');
                $sheet->setCellValue('H' . $row, !empty($item->amount)?   $item->amount :''); 
 
                $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');
                $sheet->setCellValue('I' . $row, !empty($item->ReceiptCode)?$item->ReceiptCode:'');
                $sheet->setCellValue('J' . $row, !empty($item->PayAmountBill) ?  $item->PayAmountBill:'');  
                $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal('right');
            }
        }
            $last_i = $row;
            $amount = 'H4' . ':H' . $last_i;
            $amount_bill = 'J4'  . ':J' . $last_i;
            $row++;
   
            $sheet->setCellValue('A'.$row, 'รวม');
            $sheet->mergeCells('A'.$row.':G'.$row);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');

            $sheet->setCellValue('H'.$row,'=SUM(' . $amount . ')');
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');

            $sheet->setCellValue('J'.$row,'=SUM(' . $amount_bill . ')');
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal('right');
              //ใส่ขอบดำ
              $style_borders = [
                'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                ]
            ];
            $sheet->getStyle('A3:J'.$row)->applyFromArray($style_borders);

            $sheet->getStyle('H4:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('J4:J'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

            //Set Column Width
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $filename = 'Payment_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }




}
