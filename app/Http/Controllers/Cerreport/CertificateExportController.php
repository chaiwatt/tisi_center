<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CertificateExport;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;

use DB;

class CertificateExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('cerreport-certificate-export','-');
        if(auth()->user()->can('view-'.$model)) {

        $filter = [];
        $filter['filter_certify'] = $request->get('filter_certify', '');
        $filter_certify =      $filter['filter_certify'];
        $filter['filter_years'] = $request->get('filter_years', '');
        $filter_years =      $filter['filter_years'];
 
        $export_labs = CertificateExport::query()
                                ->select(DB::raw('SUM(IF(`certificate_for`>0, 1, 0)) AS sum_ref,year(created_at) AS year'))
                                ->when($filter_certify, function ($query, $filter_certify){
                                    if($filter_certify == 1){
                                        return $query->whereNotNull('certificate_newfile');
                                    }else{
                                        return $query->whereNotNull('attachs');
                                    }
                                })
                                 ->when($filter_years, function ($query, $filter_years){
                                    return $query->where(DB::raw('year(created_at)'),$filter_years);
                                })
                                ->groupBy(DB::raw('year(created_at)'))
                                ->orderby('id','desc') ->get()->pluck('sum_ref', 'year')->toArray() ;

         $export_cbs = CertiCBExport::query()
                                ->select(DB::raw('SUM(IF(`app_certi_cb_id`>0, 1, 0)) AS sum_ref,year(created_at) AS year'))
                                ->when($filter_certify, function ($query, $filter_certify){
                                    if($filter_certify == 1){
                                        return $query->whereNotNull('certificate_newfile');
                                    }else{
                                        return $query->whereNotNull('attachs');
                                    }
                                })
                                 ->when($filter_years, function ($query, $filter_years){
                                    return $query->where(DB::raw('year(created_at)'),$filter_years);
                                })
                                ->groupBy(DB::raw('year(created_at)'))
                                ->orderby('id','desc') ->get()->pluck('sum_ref', 'year')->toArray() ;

         $export_ibs = CertiIBExport::query()
                                ->select(DB::raw('SUM(IF(`app_certi_ib_id`>0, 1, 0)) AS sum_ref,year(created_at) AS year'))
                                ->when($filter_certify, function ($query, $filter_certify){
                                    if($filter_certify == 1){
                                        return $query->whereNotNull('certificate_newfile');
                                    }else{
                                        return $query->whereNotNull('attachs');
                                    }
                                })
                                 ->when($filter_years, function ($query, $filter_years){
                                    return $query->where(DB::raw('year(created_at)'),$filter_years);
                                })
                                ->groupBy(DB::raw('year(created_at)'))
                                ->orderby('id','desc') ->get()->pluck('sum_ref', 'year')->toArray() ;

            $start_year = date('Y') - 4;
            $end_year = date('Y');               
           $years = [];
         if(!empty($filter_years)){
            $years[$filter_years] = $filter_years +543;
         }else{
            for ($i = $end_year; $i >= $start_year; $i--) {
                $years[$i] = $i +543;
            }
         }   
         $filter_years = [];                    
         for ($i = $end_year; $i >= $start_year; $i--) {
            $filter_years[$i] = $i +543;
         }   
            return view('cerreport.certificate-export.index', compact('filter','filter_years','years','export_labs','export_cbs','export_ibs'));
        }
        abort(403);
    }

    public function data_list(Request $request)
    {
        
        $filter_search = $request->input('filter_search');
        $filter_certify = $request->input('filter_certify');
        $filter_conditional_type = $request->input('filter_conditional_type');
        $query = PayInAll::query()                       
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search ); 
                                    $query->where(function ($query2) use($search_full) {
                                        return   $query2->Where(DB::raw("REPLACE(app_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(auditors_name,' ','')"), 'LIKE', "%".$search_full."%") ;
                                        });
                                    }) 
                                    ->when($filter_conditional_type, function ($query, $filter_conditional_type){
                                        return  $query->where('conditional_type', $filter_conditional_type);
                                     })
                                     ->when($filter_certify, function ($query, $filter_certify){
                                        return  $query->where('certify', $filter_certify);
                                       })
                                    ;
                  
      return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                        return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('app_no', function ($item) {
                                     return $item->app_no ?? null;
                            })
                            ->addColumn('name', function ($item) {
                                    $text =   !empty($item->name)? $item->name:'';
                                    $text .=   !empty($item->tax_id)? '<br/>'.$item->tax_id:'';
                                     return $text;
                            })
                            ->addColumn('conditional_type', function ($item) {
                                    return $item->ConditionalTypeName ?? null;
                           })
                           ->addColumn('start_date', function ($item) {
                                    return  !empty($item->start_date)?HP::DateThai($item->start_date):null;
                           })


                            ->addColumn('action', function ($item) {
                                    return HP::buttonAction( $item->id, 'cerreport/payins','Cerreport\\PayInController@destroy', 'cerreport-payins',true,false,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id','desc');
                            })
                            ->rawColumns(['checkbox', 'name','attach', 'action'])
                            ->make(true); 
                                    
    }

}