<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
use DB; 
use Yajra\Datatables\Datatables;
use App\Models\Certify\CertifyLogEmail;
use App\Models\Sso\User AS SSO_User;
class CertifyApplicant extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('cerreport-applicant','-');
        if(auth()->user()->can('view-'.$model)) {


            return view('cerreport.applicant.index');
        }
        abort(403);
    }


    public function data_list(Request $request)
    {
        
        $filter_search = $request->input('filter_search');
        $filter_certify = $request->input('filter_certify');
    
        $query = CertifyLogEmail::query()    
                                    ->whereNotNull('certify')                   
                                  ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search ); 
                                    $query->where(function ($query2) use($search_full) {
                                          $user_ids =     SSO_User::Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tax_number,' ','')"), 'LIKE', "%".$search_full."%") 
                                                                    ->select('id');
                                             return    $query2->whereIn('user_id', $user_ids)->Orwhere(DB::raw("REPLACE(app_no,' ','')"), 'LIKE', "%".$search_full."%");

                                        });
                                    }) 
                                     ->when($filter_certify, function ($query, $filter_certify){
                                        return  $query->where('certify', $filter_certify);
                                       })
                                       ->groupBy('app_table')
                                       ->groupBy('app_id')
                                       ->groupBy('certify')
                                       ->orderby('id','desc');
     
      return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                        return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('app_no', function ($item) {
                                    if(in_array($item->certify,[4,5,6])){
                                        $app_no =    DB::table("$item->app_table")->where('id',$item->app_id)->orderby('id','desc')->value('reference_refno');
                                    }else{
                                        $app_no =    DB::table("$item->app_table")->where('id',$item->app_id)->orderby('id','desc')->value('app_no');
                                    }
                                     return $app_no ?? null;
                            })
                            ->addColumn('name', function ($item) {
                                    $text =   !empty($item->sso_user_to->name)? $item->sso_user_to->name:'';
                                    $text .=   !empty($item->sso_user_to->tax_number)? '<br/>'.$item->sso_user_to->tax_number:'';
                                     return $text;
                            })
                            ->addColumn('number', function ($item) {
                                   return   CertifyLogEmail::where('app_id',$item->app_id)->where('app_table',$item->app_table)->get()->count();
                           })
                           ->addColumn('certify', function ($item) {
                                    return  !empty($item->CertifyTitle) ? $item->CertifyTitle :null;
                           })
                            ->addColumn('action', function ($item) {
                                    return HP::buttonAction( $item->id, 'cerreport/certify-applicant','Cerreport\\CertifyApplicant@destroy', 'cerreport-applicant',true,false,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id','desc');
                            })
                            ->rawColumns(['checkbox', 'name', 'action'])
                            ->make(true); 
                                    
    }

    public function show($id)
    {
        $model = str_slug('cerreport-applicant','-');
        if(auth()->user()->can('view-'.$model)) {
          $log = CertifyLogEmail::findOrFail($id);
      
          $log_mails =  CertifyLogEmail::where('app_id',$log->app_id)->where('app_table',$log->app_table)->orderBy('id','desc')->get();

            return view('cerreport.applicant.show', compact('log', 'log_mails'));
        }
        return   abort(403);
    }

}
