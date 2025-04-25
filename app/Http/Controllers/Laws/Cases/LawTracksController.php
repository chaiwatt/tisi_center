<?php

namespace App\Http\Controllers\Laws\Cases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesImpoundProduct;
class LawTracksController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-cases-tracks','-');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = ($request->input('filter_status') == "0")?'-1':$request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_tbl_license_no   = $request->input('filter_tbl_license_no');
        $filter_created_at       = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $filter_sub_department   = $request->input('filter_sub_department');
 
        $filter_close_status     = $request->input('filter_close_status');
        $filter_status_pay       = $request->input('filter_status_pay');

        $filter_start_money       = !empty($request->input('filter_start_money'))?  str_replace(",","",$request->input('filter_start_money')) :null;
        $filter_end_money         = !empty($request->input('filter_end_money'))?  str_replace(",","",$request->input('filter_end_money')) :null;
 


        $query =  LawCasesForm::query()
                                   // ->where(function($query){
                                    //     $query->whereNotIn('status',['0','99'])
                                    //             ->whereHas('law_cases_result_to', function ($query)   {
                                    //                 $query->WhereNotNull('law_case_id');
                                    //             });
                                    // })
                                    ->where(function($query){
                                        $query->whereNotIn('status',['0','99']) ->where('status','>=','2');
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            $search_full = str_replace(' ', '', $filter_search);
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    //เลขที่อ้างอิง
                                                    return $query->Where('ref_no', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "2":
                                                    //เลขคดี
                                                    return $query->Where('case_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "3":
                                                    //ผู้รับผิดชอบ
                                                    return $query->whereHas('user_assign_to', function ($query) use($search_full){
                                                                        $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                case "4":
                                                    //นิติกร
                                                    return $query->whereHas('user_lawyer_to', function ($query) use($search_full){
                                                                        $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                case "5":
                                                    //ผู้ประกอบการ/TAXID
                                                    $query->where(function ($query) use($search_full) {
                                                                $query->Where('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                                case "6":
                                                    //เลขที่ใบอนุญาต
                                                    $query->where(function ($query) use($search_full) {
                                                                $query->Where('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                                default:
                                                    return  $query->where(function ($query2) use($search_full) {
                                                                $query2->Where('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('case_number', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrwhereHas('user_assign_to', function ($query) use($search_full){
                                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                        })
                                                                        ->OrwhereHas('user_lawyer_to', function ($query) use($search_full){
                                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                        });
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            if( $filter_status == '-1'){
                                                return $query->where('status', 0);
                                            }else{
                                                return $query->where('status',$filter_status);
                                            }
                                        })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                            return $query->where('tis_id', $filter_tisi_no);
                                        })
                                        ->when($filter_tbl_license_no, function ($query, $filter_tbl_license_no){
                                            return $query->where('offend_license_number',$filter_tbl_license_no);
                                        })
                                        ->when($filter_created_at, function ($query, $filter_created_at){
                                            return $query->whereDate('created_at', $filter_created_at);
                                        })
                                        ->when($filter_sub_department, function ($query, $filter_sub_department){
                                            return $query->whereHas('user_assign_to', function ($query) use($filter_sub_department){
                                                                $query->Where("reg_subdepart", $filter_sub_department);
                                                            });
                                        })
                                        ->when($filter_close_status, function ($query, $filter_close_status){
                                            if( $filter_close_status == '-1'){
                                                return $query->where('status_close', 0);
                                            }else{
                                                return $query->where('status_close',$filter_close_status);
                                            }
                                        })
                                        ->when($filter_status_pay, function ($query, $filter_status_pay){
                                            return  $query->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_status_pay)  {
                                                                return  $query2->Where('paid_status',$filter_status_pay);
                                                            });  
                                        })

                                        ->when($filter_start_money, function ($query, $filter_start_money) use  ($filter_end_money){
                                            return  $query->whereHas('law_cases_impound_to', function ($query2)  use ($filter_start_money,$filter_end_money){
                                                          if(!is_null($filter_start_money) && !is_null($filter_end_money) ){
                                                            return $query2->where('total_value', '>=', $filter_start_money)
                                                                           ->where('total_value', '<=', $filter_end_money);
                                                           }else if(!is_null($filter_start_money) && is_null($filter_end_money)){
                                                             return  $query2->where('total_value',$filter_start_money);
                                                          }
                                                         
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
                                $text  = !empty($item->case_number) ? $item->case_number : '<div>รอดำเนินการ</div>';
                                $text  .= !empty($item->ref_no) ? '<div><span class="text-muted">'.$item->ref_no.'</span></div>' : '';
                                return $text;
                            })
                            ->addColumn('offend_name', function ($item) {
                                return ( !empty( $item->offend_name )? $item->offend_name:null  ).('<div><em>('.(!empty( $item->offend_taxid )? $item->offend_taxid:null ).')</em></div>');
                            })
                            ->addColumn('offend_taxid', function ($item) {
                               return   !empty($item->offend_taxid) ? $item->offend_taxid : '';
                            })
                            ->addColumn('tb3_TisThainame', function ($item) {
                                return  !empty($item->StandardNo) ? $item->StandardNo : '';
                            }) 
                            ->addColumn('offense_section_number', function ($item) {
                                return  !empty($item->law_cases_result_to->OffenseSectionNumber)  ?  implode(", ",$item->law_cases_result_to->OffenseSectionNumber) : '-';
                            })
                            ->addColumn('status', function ($item){ 
                                $txt = !empty($item->StatusColorHtml) ? $item->StatusColorHtml : '';
                                $txt .= ( !is_null($item->status_close) && $item->status_close == 1 )?'<div><span class="text-success">[ปิดงาน]</span></div>':'';
                                return  $txt;
                            })
                            ->addColumn('assign_name', function ($item) {
                                 $text =    !empty($item->user_assign_to->subdepart->sub_depart_shortname) ?  '<br/><span class="text-muted">('.$item->user_assign_to->subdepart->sub_depart_shortname.')</span>' : '';
                                return !empty($item->user_assign_to->FullName) ? $item->user_assign_to->FullName.$text : '<span class="text-muted">(รอมอบหมาย)</span>';
                            })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer_to->FullName) ? $item->user_lawyer_to->FullName: '<i class="text-muted">(รอมอบหมาย)</i>';
                            })
                            ->addColumn('total', function ($item) {
                                return  !empty($item->law_cases_impound_to->total_value) ? number_format($item->law_cases_impound_to->total_value,2) : number_format('0',2);
                            })
                            ->addColumn('payin', function ($item) {
                                $law_payments = $item->law_cases_payments_cancel_status_to;
                                // $txt          = !empty($law_payments->law_cases_payments_detail_to->amount) ?   number_format($law_payments->law_cases_payments_detail_to->amount,2) : null;
                                $txt          = !empty($law_payments->amount) ?   number_format($law_payments->amount,2) : '';

                                $payin        = '<div><span class="text-muted">รอดำเนินการ</span></div>';
                                if( !empty($law_payments->paid_status)  && $law_payments->paid_status == '2' ){
                                    $payin    = '<div><span class="text-success">ชำระแล้ว</span></div>';
                                }else if( !empty($law_payments->paid_status)  && $law_payments->paid_status == '1'){
                                    $payin          = '<div><span class="text-danger">รอสร้าง Pay-in</span></div>';
                                    if(!empty($law_payments->end_date)){
                                        $count_end  = (strtotime($law_payments->end_date) - strtotime(date('Y-m-d'))) /(60*60*24);
                                        $payin      = '<div><span class="text-success">สร้าง Pay-in แล้ว</span></div>';
                                        if($law_payments->end_date >= date("Y-m-d")){
                                            $payin .= '<span class="text-warning">(รอชำระ '.$count_end. ' วัน)</span>';
                                        }else{
                                            $payin .= '<span class="text-danger">(เกินกำหนด '.$count_end. ' วัน)</span>';
                                        }
                                    }
                                }    
                                return  $txt.$payin; 
                            })
                            ->addColumn('action', function ($item){
                                if(auth()->user()->can('view-'.$this->permission)) {
                                    return  '<a  href="'.(url('law/cases/tracks/'.$item->id)).'"  class="btn btn-icon btn-circle btn-light-info " > <i class="fa fa-search"  style="font-size: 1.5em;"></i> </a>'; 
                                }else{
                                    return  '<button   type="button"  class="btn btn-icon btn-circle btn-light-info not-allowed  " > <i class="fa fa-search"  style="font-size: 1.5em;"></i> </button>'; 
                                }
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
                            ->rawColumns(['offense_section_number', 'status', 'action', 'case_number', 'lawyer_name', 'assign_name', 'offend_name','payin'])
                            ->make(true);
    }


    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/tracks",  "name" => 'ติดตามงานคดี' ],
            ];
            return view('laws.cases.tracks.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $cases = LawCasesForm::findOrFail($id);
            $cases->law_basic_arrest     = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;
            $result             =  LawCasesResult::where('law_case_id', $cases->id )->first();
            $license_result     =   !empty($result->law_case_license_result_to)  ?$result->law_case_license_result_to : null; 

            $product_result     =   !empty($cases->product_result)  ?$cases->product_result : null;             
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/ministry",  "name" => 'ติดตามงานคดี' ],
                [ "link" => "/law/cases/tracks/$id/edit",  "name" => 'รายละเอียด' ],

            ];

            return view('laws.cases.tracks.view', compact('cases', 'result', 'license_result', 'product_result',  'breadcrumbs'));
        }
         abort(403);
    }
 
}
