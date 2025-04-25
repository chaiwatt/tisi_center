<?php

namespace App\Http\Controllers\Certify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;

use App\CertificateExport;
use App\Models\Certify\ApplicantIB\CertiIBExport; 
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use DB;
class DashboardController extends Controller
{
    public function index(Request $request)
    {

        return view('certify.dashboard.index');  // , compact('certi')

    }

    public function draw_app(Request $request)
    {
        $filter_year_app = $request->input('filter_year_app');
        $certi_labs = CertiLab::select(DB::raw('SUM(IF(`start_date`>0, 1, 0)) AS sum_labs,
                                                 year(start_date) AS year'
                                              )
                                      )
                                ->whereNotNull('start_date')      
                                ->when($filter_year_app, function ($query, $filter_year_app){
                                    return  $query->where(DB::raw('year(start_date)'), $filter_year_app);
                                })                              
                              ->groupBy(DB::raw('year(start_date)'))
                              ->orderby('id','desc')
                              ->get();
        $certi_cbs = CertiCb::select(DB::raw('SUM(IF(`start_date`>0, 1, 0)) AS sum_cbs,
                                                 year(start_date) AS year'
                                              )
                                      )
                              ->whereNotNull('start_date')      
                                ->when($filter_year_app, function ($query, $filter_year_app){
                                    return  $query->where(DB::raw('year(start_date)'), $filter_year_app);
                                })                   
                              ->groupBy(DB::raw('year(start_date)'))
                              ->orderby('id','desc')
                              ->get()->pluck('sum_cbs','year')->toArray();    
      $certi_ibs = CertiIb::select(DB::raw('SUM(IF(`start_date`>0, 1, 0)) AS sum_ibs,
                                                 year(start_date) AS year'
                                              )
                                      )
                                ->whereNotNull('start_date')      
                                ->when($filter_year_app, function ($query, $filter_year_app){
                                    return  $query->where(DB::raw('year(start_date)'), $filter_year_app);
                                })                            
                              ->groupBy(DB::raw('year(start_date)'))
                              ->orderby('id','desc')
                              ->get()->pluck('sum_ibs','year')->toArray();    

        if(count($certi_labs) > 0){
            $datas = [];
            foreach($certi_labs as $item){
                $object = (object)[];
                $object->year         = $item->year ;
                $object->sum_labs    =  $item->sum_labs ;
                if(array_key_exists($item->year,$certi_cbs)){
                    $object->sum_cbs    =  $certi_cbs[$item->year] ;
                }else{
                    $object->sum_cbs    =  0 ;
                }
                if(array_key_exists($item->year,$certi_ibs)){
                    $object->sum_ibs    =  $certi_ibs[$item->year] ;
                }else{
                    $object->sum_ibs    =  0 ;
                }
                 $datas[] = $object;
            }
            return response()->json([
                            'message' =>  true,
                            'datas' => $datas
                        ]);
        }else{
            return response()->json([
                                'message' =>  false 
                             ]);
        }           
    }

    public function chart_cer(Request $request)
    {
        $filter_year_cer = $request->input('filter_year_cer');
        $certi_labs = CertificateExport::select(DB::raw('SUM(IF(`certificate_date_start`>0, 1, 0)) AS sum_labs,
                                                 year(certificate_date_start) AS year'
                                              )
                                      ) 
                                ->whereNotNull('certificate_date_start')   
                                ->whereIn('status',[3])      
                                ->when($filter_year_cer, function ($query, $filter_year_cer){
                                    return  $query->where(DB::raw('year(certificate_date_start)'), $filter_year_cer);
                                })                              
                              ->groupBy(DB::raw('year(certificate_date_start)'))
                              ->orderby('id','desc')
                              ->get();
                
        $certi_cbs = CertiCBExport::select(DB::raw('SUM(IF(`date_start`>0, 1, 0)) AS sum_cbs,
                                                 year(date_start) AS year'
                                              )
                                      )
                              ->whereNotNull('date_start')   
                               ->whereIn('status',[19])         
                                ->when($filter_year_cer, function ($query, $filter_year_cer){
                                    return  $query->where(DB::raw('year(date_start)'), $filter_year_cer);
                                })                   
                              ->groupBy(DB::raw('year(date_start)'))
                              ->orderby('id','desc')
                              ->get()->pluck('sum_cbs','year')->toArray();    
      $certi_ibs = CertiIBExport::select(DB::raw('SUM(IF(`date_start`>0, 1, 0)) AS sum_ibs,
                                                 year(date_start) AS year'
                                              )
                                      )
                                ->whereNotNull('date_start')  
                                ->whereIn('status',[19])          
                                ->when($filter_year_cer, function ($query, $filter_year_cer){
                                    return  $query->where(DB::raw('year(date_start)'), $filter_year_cer);
                                })                            
                              ->groupBy(DB::raw('year(date_start)'))
                              ->orderby('id','desc')
                              ->get()->pluck('sum_ibs','year')->toArray();    

        if(count($certi_labs) > 0){
            $datas = [];
            foreach($certi_labs as $item){
                $object = (object)[];
                $object->year         = $item->year ;
                $object->sum_labs    =  $item->sum_labs ;
                if(array_key_exists($item->year,$certi_cbs)){
                    $object->sum_cbs    =  $certi_cbs[$item->year] ;
                }else{
                    $object->sum_cbs    =  0 ;
                }
                if(array_key_exists($item->year,$certi_ibs)){
                    $object->sum_ibs    =  $certi_ibs[$item->year] ;
                }else{
                    $object->sum_ibs    =  0 ;
                }
                 $datas[] = $object;
            }
            return response()->json([
                            'message' =>  true,
                            'datas' => $datas
                        ]);
        }else{
            return response()->json([
                                'message' =>  false 
                             ]);
        }           
    }


    
}
