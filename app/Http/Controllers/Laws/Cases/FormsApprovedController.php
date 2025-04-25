<?php

namespace App\Http\Controllers\Laws\Cases;
use HP;
use HP_Law;
use App\Http\Requests;
use App\Models\Basic\Tis;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesStandard;
use App\Models\Law\Cases\LawCasesLevelApprove;
use App\Models\Config\ConfigsEvidence;
use App\Models\Config\ConfigsEvidenceGroup;
use App\Models\Law\Cases\LawCasesImpoundProduct;
use App\Models\Law\Config\LawConfigEmailNotis;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Cases\MailFormsApproved;
use App\Mail\Mail\Law\Cases\MailCasesConfig;
use Session;


class FormsApprovedController extends Controller
{
    private $permission;
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission       = str_slug('law-cases-forms-approved','-');
        $this->attach_path = 'law_attach/cases/forms_approved/';
    }



    
    public function data_list(Request $request)
    {
        $filter_condition_search     = $request->input('filter_condition_search');
        $filter_search               = $request->input('filter_search');
        $filter_status_approve       = $request->input('filter_status_approve');
        $filter_violate_section      = $request->input('filter_violate_section');

        $model = str_slug('law-cases-forms-approved','-');
        //ผู้ใช้งาน
        $role_ids   = !empty(auth()->user()->RoleIds) ? auth()->user()->RoleIds : [];
        $rights      = !empty(auth()->user()->subdepart->CheckRight) ? auth()->user()->subdepart->CheckRight : [];
       
        $roles  = [ 
            '7'=>'จนท',
            '6'=>'ผก',
            '5'=>'ผอ',
            '4'=>'ทป',
            '2'=>'รมอ',
            '1'=>'ลมอ'
        ];

        $query = LawCasesForm::query()  
                                     ->whereNotIn('status',['0'])
                                     ->whereIn('status_approve',['1','2'])
                                     ->whereIn('approve_type',['1'])
                                     ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "2":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "3":
                                                return $query->where(function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                            default:
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                        endswitch;
                                    })
            
                                    ->when($filter_violate_section, function ($query, $filter_violate_section){
                                        foreach($filter_violate_section as $item){
                                            return $query->whereJsonContains('law_basic_section_id', $item);
                                        }
                                    })
                                    ->when($filter_status_approve, function ($query, $filter_status_approve){
                                        return $query->where('status_approve', $filter_status_approve);
                                    })
                                    // ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($rights, $filter_search, $filter_condition_search, $filter_violate_section, $filter_status_approve) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                    ->when(!auth()->user()->can('view_all-'.$model), function($query) use ($role_ids)  {
                                   
                                        if( in_array('1',$role_ids) ||   in_array('56',$role_ids)){  // สิทธิ์ Admin

                                        }else{ 
                                            return  $query->with(['law_cases_level_approve_to'])  
                                                         ->whereHas('law_cases_level_approve_to', function ($query2)  {
                                                                  return $query2->Where('authorize_userid',auth()->user()->getKey());
                                                         });
                                        }
                                            // if(!empty($rights) && count($rights) > 0){ 
                                            //         if(!in_array('All',$rights)){
                                            //             return  $query->with(array('cases_standards' => function($query2)  use ($rights) {
                                            //                               return  $query2->WhereIn('tb3_tisno', $rights);
                                            //                           }))->whereHas('cases_standards', function ($query2) use ($rights) {
                                            //                                 return  $query2->WhereIn('tb3_tisno', $rights);
                                            //                          });  
                                            //         }
                                            // }else{
                                        // return  $query->with(['law_cases_level_approve_to'])  
                                        //         ->whereHas('law_cases_level_approve_to', function ($query2)  {
                                        //                  return $query2->Where('authorize_userid',auth()->user()->getKey());
                                        //         })
                                        //         ->Orwhere(function($query) use ( $filter_search, $filter_condition_search, $filter_violate_section, $filter_status_approve) {
                                        //             $query->where('created_by', auth()->user()->getKey())
                                        //                     ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        //                         $search_full = str_replace(' ', '', $filter_search);
                        
                                        //                         switch ( $filter_condition_search ):
                                        //                             case "1":
                                        //                                 return $query->where(function($query) use ($search_full){
                                        //                                                     $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                 });
                                        //                                 break;
                                        //                             case "2":
                                        //                                 return $query->where(function($query) use ($search_full){
                                        //                                                     $query->where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                 });
                                        //                                 break;
                                        //                             case "3":
                                        //                                 return $query->where(function($query) use ($search_full){
                                        //                                                         $query->Where(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                     });
                                        //                                 break;
                                        //                             default:
                                        //                                 return $query->where(function($query) use ($search_full){
                                        //                                                     $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%")
                                        //                                                         ->orWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                        //                                                         ->orWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                     });
                                        //                                 break;
                                        //                         endswitch;
                                        //                     })
                                    
                                        //                     ->when($filter_violate_section, function ($query, $filter_violate_section){
                                        //                         foreach($filter_violate_section as $item){
                                        //                             return $query->whereJsonContains('law_basic_section_id', $item);
                                        //                         }
                                        //                     })
                                        //                     ->when($filter_status_approve, function ($query, $filter_status_approve){
                                        //                         return $query->where('status_approve', $filter_status_approve);
                                        //                     });
                                        //         }) ->Orwhere(function($query) use ($rights, $filter_search, $filter_condition_search, $filter_violate_section, $filter_status_approve) {
                                        //                 if(!empty($rights) && count($rights) > 0 && !in_array('All',$rights)){
                                        //                         return  $query->with(array('cases_standards' => function($query2)  use ($rights) {
                                        //                                         return  $query2->WhereIn('tb3_tisno', $rights);
                                        //                                     }))->whereHas('cases_standards', function ($query2) use ($rights) {
                                        //                                             return  $query2->WhereIn('tb3_tisno', $rights);
                                        //                                     }) ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        //                                         $search_full = str_replace(' ', '', $filter_search);
                                        
                                        //                                         switch ( $filter_condition_search ):
                                        //                                             case "1":
                                        //                                                 return $query->where(function($query) use ($search_full){
                                        //                                                                     $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                                 });
                                        //                                                 break;
                                        //                                             case "2":
                                        //                                                 return $query->where(function($query) use ($search_full){
                                        //                                                                     $query->where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                                 });
                                        //                                                 break;
                                        //                                             case "3":
                                        //                                                 return $query->where(function($query) use ($search_full){
                                        //                                                                         $query->Where(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                                     });
                                        //                                                 break;
                                        //                                             default:
                                        //                                                 return $query->where(function($query) use ($search_full){
                                        //                                                                     $query->where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%")
                                        //                                                                         ->orWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                        //                                                                         ->orWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                        //                                                                     });
                                        //                                                 break;
                                        //                                         endswitch;
                                        //                                     })
                                                    
                                        //                                     ->when($filter_violate_section, function ($query, $filter_violate_section){
                                        //                                         foreach($filter_violate_section as $item){
                                        //                                             return $query->whereJsonContains('law_basic_section_id', $item);
                                        //                                         }
                                        //                                     })
                                        //                                     ->when($filter_status_approve, function ($query, $filter_status_approve){
                                        //                                         return $query->where('status_approve', $filter_status_approve);
                                        //                                     });  
                                        //                     }
                                        //         }) ;  
                                            // }
                                        // }    
                                    });
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('ref_no', function ($item) {
                                $text   =  $item->ref_no;
                                $text  .= !empty($item->case_number)?'<div>('.$item->case_number.')</div>':'';
                                return $text;
                            })
                            ->addColumn('name_taxid', function ($item) {
                                return $item->NameAndTaxid;
                            })
 
                            ->addColumn('law_basic_section', function ($item) {
                                return $item->SectionListName;
                            })
                            ->addColumn('number1', function ($item) use ($role_ids, $roles)  {
                                 $text = '';
                                if(!empty($item->law_cases_level_approve1)){
                                       $approve1 = $item->law_cases_level_approve1;
                                       $fullname =  !empty($approve1->authorize_name) ?  $approve1->authorize_name : '' ;
                                       $text .=    !empty($fullname) ?  $fullname.'<br/>' : '' ;
                                    if($approve1->status == '1'){
                                         $text .=   '<span class="text-muted" >(รอดำเนินการ)</span>';
                                    }else if($approve1->status == '2'){
                                        if(auth()->user()->getKey() == $approve1->authorize_userid || in_array('1',$role_ids) ||   in_array('56',$role_ids) ){
                                            $shortname =  array_key_exists($approve1->role,$roles) ?  $roles[$approve1->role] : ''   ;
                                            // $shortname =  !empty($approve1->subdepartment->sub_depart_shortname) ?  $approve1->subdepartment->sub_depart_shortname : '' ;
                                            $position =  !empty($approve1->position) ?  $approve1->position : '' ;
                                            //  ส่งเรื่องต่อไปยัง
                                            $approve2      = $item->law_cases_level_approve2;
                                            $send_position =  !empty($approve2->authorize_name) ?  'ลำดับ 2 : '.$approve2->authorize_name : 'ส่งกองกฎหมาย' ;
                                            $text .=   '<span class="text-sugar" 
                                                         data-id="'.$item->id.'"  
                                                         data-approve_id="'.$approve1->id.'"  
                                                         data-level="1" 
                                                         data-shortname="'.$shortname.'"   
                                                         data-fullname="'.$fullname.'"   
                                                         data-position="'.$position.'"   
                                                         data-send_position="'.$send_position.'"  
                                                     >(รอพิจารณา)</span>';
                                        }else{
                                            $text .=   '<span class="text-muted" >(รอพิจารณา)</span>';
                                        }
                                    }else if($approve1->status == '3'){
                                        $text .=   '<span class="text-success" >(เห็นชอบ)</span>';
                                    }else if($approve1->status == '4'){
                                        $text .=   '<span class="text-danger" >(ไม่เห็นชอบ)</span>';
                                    }else{
                                        $text .=   '-';
                                    }
                                }else{
                                    $text .=   '<i class="text-muted" >ไม่มี</i>';
                                }
                                return $text;
                            })
                            ->addColumn('number2', function ($item) use  ($role_ids, $roles)  {
                                $text = '';
                                if(!empty($item->law_cases_level_approve2)){
                                       $approve2 = $item->law_cases_level_approve2;
                                       $fullname =  !empty($approve2->authorize_name) ?  $approve2->authorize_name : '' ;
                                       $text .=    !empty($fullname) ?  $fullname.'<br/>' : '' ;
                                    if($approve2->status == '1'){
                                         $text .=   '<span class="text-muted" >(รอดำเนินการ)</span>';
                                    }else if($approve2->status == '2'){
                                      if(auth()->user()->getKey() == $approve2->authorize_userid  || in_array('1',$role_ids) ||   in_array('56',$role_ids) ){
                                         $shortname =  array_key_exists($approve2->role,$roles) ?  $roles[$approve2->role] : ''   ;
                                        //  $shortname =  !empty($approve2->subdepartment->sub_depart_shortname) ?  $approve2->subdepartment->sub_depart_shortname : '' ;
                                         $position =  !empty($approve2->position) ?  $approve2->position : '' ;
                                         //  ส่งเรื่องต่อไปยัง
                                         $approve3      = $item->law_cases_level_approve3;
                                         $send_position =  !empty($approve3->authorize_name) ?  'ลำดับ 3 : '.$approve3->authorize_name : 'ส่งกองกฎหมาย' ;
                                         $text .=   '<span class="text-sugar" 
                                                      data-id="'.$item->id.'"  
                                                      data-approve_id="'.$approve2->id.'"  
                                                      data-level="2" 
                                                      data-shortname="'.$shortname.'"   
                                                      data-fullname="'.$fullname.'"   
                                                      data-position="'.$position.'"   
                                                      data-send_position="'.$send_position.'"  
                                                  >(รอพิจารณา)</span>';
                                        }else{
                                            $text .=   '<span class="text-muted" >(รอพิจารณา)</span>';
                                        }
                                    }else if($approve2->status == '3'){
                                        $text .=   '<span class="text-success" >(เห็นชอบ)</span>';
                                    }else if($approve2->status == '4'){
                                        $text .=   '<span class="text-danger" >(ไม่เห็นชอบ)</span>';
                                    }else{
                                        $text .=   '-';
                                    }
                                }else{
                                    $text .=   '<i class="text-muted" >ไม่มี</i>';
                                } 
                                return $text;
                            })
                            ->addColumn('number3', function ($item) use  ($role_ids, $roles)  {
                                $text = '';
                                if(!empty($item->law_cases_level_approve3)){
                                      $approve3 = $item->law_cases_level_approve3;
                                      $fullname =   !empty($approve3->authorize_name) ?  $approve3->authorize_name : '' ;
                                      $text .=    !empty($fullname) ?  $fullname.'<br/>' : '' ;
                                    if($approve3->status == '1'){
                                         $text .=   '<span class="text-muted" >(รอดำเนินการ)</span>';
                                    }else if($approve3->status == '2'){
                                       if(auth()->user()->getKey() == $approve3->authorize_userid   || in_array('1',$role_ids) ||   in_array('56',$role_ids) ){
                                        $shortname =  array_key_exists($approve3->role,$roles) ?  $roles[$approve3->role] : ''   ;
                                        // $shortname =  !empty($approve3->subdepartment->sub_depart_shortname) ?  $approve3->subdepartment->sub_depart_shortname : '' ;
                                        $position =  !empty($approve3->position) ?  $approve3->position : '' ;
                                        //  ส่งเรื่องต่อไปยัง
                                        $approve4      = $item->law_cases_level_approve4;
                                        $send_position =  !empty($approve4->authorize_name) ?  'ลำดับ 4 : '.$approve4->authorize_name : 'ส่งกองกฎหมาย' ;
                                        $text .=   '<span class="text-sugar" 
                                                     data-id="'.$item->id.'"  
                                                     data-approve_id="'.$approve3->id.'"  
                                                     data-level="3" 
                                                     data-shortname="'.$shortname.'"   
                                                     data-fullname="'.$fullname.'"   
                                                     data-position="'.$position.'"   
                                                     data-send_position="'.$send_position.'"  
                                                 >(รอพิจารณา)</span>';
                                        }else{
                                            $text .=   '<span class="text-muted" >(รอพิจารณา)</span>';
                                        }
                                    }else if($approve3->status == '3'){
                                        $text .=   '<span class="text-success" >(เห็นชอบ)</span>';
                                    }else if($approve3->status == '4'){
                                        $text .=   '<span class="text-danger" >(ไม่เห็นชอบ)</span>';
                                    }else{
                                        $text .=   '-';
                                    }
                                }else{
                                    $text .=   '<i class="text-muted" >ไม่มี</i>';
                                } 
                                return $text;
                            })
                            ->addColumn('number4', function ($item)  use  ($role_ids, $roles)   {
                                $text = '';
                                if(!empty($item->law_cases_level_approve4)){
                                      $approve4  = $item->law_cases_level_approve4;
                                      $fullname =   !empty($approve4->authorize_name) ?  $approve4->authorize_name : '' ;
                                      $text .=    !empty($fullname) ?  $fullname.'<br/>' : '' ;
                                    if($approve4->status == '1'){
                                         $text .=   '<span class="text-muted" >(รอดำเนินการ)</span>';
                                    }else if($approve4->status == '2'){
                                        if(auth()->user()->getKey() == $approve4->authorize_userid   || in_array('1',$role_ids) ||   in_array('56',$role_ids) ){
                                               $shortname =  array_key_exists($approve4->role,$roles) ?  $roles[$approve4->role] : ''   ;
                                            // $shortname =  !empty($approve4->subdepartment->sub_depart_shortname) ?  $approve4->subdepartment->sub_depart_shortname : '' ;
                                               $position =  !empty($approve4->position) ?  $approve4->position : '' ;
                                            //  ส่งเรื่องต่อไปยัง
                                            $approve5      = $item->law_cases_level_approve5;
                                            $send_position =  !empty($approve5->position) ?  'ลำดับ 4 : '.$approve5->position : 'ส่งกองกฎหมาย' ;
                                            $text .=   '<span class="text-sugar" 
                                                     data-id="'.$item->id.'"  
                                                     data-approve_id="'.$approve4->id.'"  
                                                     data-level="4" 
                                                     data-shortname="'.$shortname.'"   
                                                     data-fullname="'.$fullname.'"   
                                                     data-position="'.$position.'"   
                                                     data-send_position="'.$send_position.'"  
                                                 >(รอพิจารณา)</span>';
                                        }else{
                                            $text .=   '<span class="text-muted" >(รอพิจารณา)</span>';
                                        }
                                    }else if($approve4->status == '3'){
                                        $text .=   '<span class="text-success" >(เห็นชอบ)</span>';
                                    }else if($approve4->status == '4'){
                                        $text .=   '<span class="text-danger" >(ไม่เห็นชอบ)</span>';
                                    }else{
                                        $text .=   '-';
                                    }
                                }else{
                                    $text .=   '<i class="text-muted" >ไม่มี</i>';
                                } 
                                return $text;
                            })
                            ->addColumn('status', function ($item) {
                                $text = '<div>';
                                if($item->status_approve == '1'){
                                        $text .= 'อยู่ระหว่างพิจารณา';
                                }else{
                                        $text .= '<span class="text-success">พิจารณาครบถ้วน</span>';
                                }
                                $text .= '</div>';
                                if($item->approve_type == 1){
                                    $text .= '<div style="margin-top: 5px;">';
                                    $data_input =   'data-id="'.($item->id).'"';
                                    $text .= '<a href="javascript:void(0)" class="show_approve" '.( $data_input ).' ><i class="fa fa-info-circle"  style="font-size: 1.5em;"></i></a>';
                                    $text .= '</div>';
                                }
                                return $text;
                            })
                            ->addColumn('action', function ($item) {                
                                    $url     = auth()->user()->can('view-'.$this->permission)?url('law/cases/forms_approved/'.$item->id):'javascript:void(0)';
                                    $allowed = auth()->user()->can('view-'.$this->permission)?'':'not-allowed';
                                    return  '<a  href="'.( $url ).'"  class="btn btn-icon btn-circle btn-light-info '.($allowed).'" ><i class="fa fa-search" style="font-size: 1.5em;"></i> </a>'; 
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
                            ->rawColumns(['checkbox', 'ref_no', 'name_taxid', 'law_basic_section', 'number1', 'number2', 'number3','number4', 'status', 'action'])
                            ->make(true);
    }
    public function index(Request $request)
    {
        $model = str_slug('law-cases-forms-approved','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/forms_approved",  "name" => 'พิจารณางานคดี' ],
            ];
            $filter_search = !empty($request->get('search')) ? $request->get('search') : null;
            $rights      = !empty(auth()->user()->subdepart->CheckRight) ? auth()->user()->subdepart->CheckRight : [];
            $approves = [];
            //ผู้ใช้งาน
            $role_ids = !empty(auth()->user()->RoleIds) ? auth()->user()->RoleIds : [];
            // if(!in_array('1',$role_ids) && !in_array('56',$role_ids)  ){
               
                $approves = LawCasesForm::whereNotIn('status',['0'])
                                    ->whereIn('status_approve',['1','2'])
                                    ->whereIn('approve_type',['1'])
                                    ->when(!auth()->user()->can('view_all-'.$model), function($query)  {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            return  $query->with(['law_cases_level_approve_to'])  
                                                        ->whereHas('law_cases_level_approve_to', function ($query2)  {
                                                            return $query2->where('status', '2')->Where('authorize_userid',auth()->user()->getKey());
                                                        });
                                   })->get();
                  if(Session::get('data-check-approve') != 'false'){
                       Session::put('data-check-approve', 'true');   
                  }
            // }
 

            return view('laws.cases.forms_approved.index',compact('breadcrumbs','filter_search', 'approves'));
        }
        abort(403);
    }

    public function update_form(Request $request)
    {
     
        $id                     = $request->get('id');
        $approve_id             = $request->get('approve_id');
        $level                  = $request->get('level'); 
        $status                 = $request->get('status');
        $status_cases           = $request->get('status_cases');
        $user_id                = $request->get('user_id');
        $remark                 = $request->get('remark');
        $lawcasesform = LawCasesForm::findOrFail($id);
        $approve  = LawCasesLevelApprove::where('id',$approve_id)->first();
        $message = false;
        if(!is_null($approve)){
               $message = true;
                if($status == '1'){  // เห็นชอบ
                    $approve->status = '3'; // เห็นชอบ

                    // อัพเดทผู้พิจารณาลำดับต่อไป
                    $level = ((int)$level +1); 
                    $level_approve =    LawCasesLevelApprove::where('law_cases_id',$id)->where('level', $level)->first();
                    if(!empty($level_approve)){
                        $level_approve->status = '2'; // รอพิจารณา
                        $level_approve->save();
                    }

                }else{ // ไม่เห็นชอบ
                    $approve->status = '4'; // ไม่เห็นชอบ
                    $approve->remark = !empty($remark) ? $remark : null  ; // ความคิดเห็น
                    $approve->return_to = !empty($user_id) ? $user_id : null  ; // ส่งเรื่องกลับไปยัง

                    $lawcasesform->status = $status_cases; 
                    if($status_cases == '99'){
                        $lawcasesform->cancel_remark = !empty($remark) ? $remark : null;
                        $lawcasesform->cancel_by     =  auth()->user()->getKey();
                        $lawcasesform->cancel_at     =  date('Y-m-d H:i:s');
                        $approve->causes = 'ยกเลิก'  ; // เนื่องจาก   
                    }else{
                         $approve->causes = 'ขอข้อมูลเพิ่มเติม (ตีกลับ)'  ; // เนื่องจาก   
                    }
                    $lawcasesform->save();

                }
                $approve->remark = !empty($remark) ? $remark : null  ; // ความคิดเห็น

              $approve->save();

               $text    =  [ 
                                '1' => 'รอดำเนินการ',
                                '2' => 'รอพิจารณา',
                                '3' => 'เห็นชอบ',
                                '4' => 'ไม่เห็นชอบ',
                           ] ;

              HP_Law::InsertLawLogWorking(         
                                            1,
                                            ((new LawCasesForm)->getTable()),
                                            $lawcasesform->id,
                                            $lawcasesform->ref_no ?? null,
                                            'แจ้งงานคดี',
                                            'พิจารณางานคดี',
                                             array_key_exists($approve->status,$text) ? $text[$approve->status] : null   ,
                                            $approve->remark 
                                        );
                                        

        }


    
        // อัพเดทสถานะหลัก
         $approves =    LawCasesLevelApprove::where('law_cases_id',$id)->get();
         if(count($approves) == count($approves->where('status','3'))){
                $lawcasesform->status_approve = '2';  // พิจารณาครบถ้วย
                $lawcasesform->status = '1';  // ส่งงานคดสีําเรจ
                $lawcasesform->save();
                $this->send_mail($lawcasesform);
         }else  if($status == '1'){  // เห็นชอบ

              if(!empty($level_approve) && (auth()->user()->getKey() != $level_approve->authorize_userid) ){
                 $user = $level_approve->user_authorize_userid;
                 $case = LawCasesForm::findOrFail($id);
                if(!empty($case)   && !empty($approve)  && !empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                    $url = url('law/cases/forms_approved').'?search='.$case->ref_no;
                    $data_app = [
                                    'case'           => $case, 
                                    'user'           => $user,
                                    'approve'        => $approve, 
                                    'level_approve'  => $level_approve,  
                                    'title'          => "ขอให้พิจารณาข้อมูลงานคดี ขอ $case->offend_name เลขอ้างอิง $case->ref_no",
                                    'url'            => $url
                             ]; 
                     HP_Law::getInsertLawNotifyEmail(1,
                                                  ((new LawCasesLevelApprove)->getTable()),
                                                     $approve->id,
                                                    'ขอให้พิจารณาข้อมูลงานคดี',
                                                    "ขอให้พิจารณาข้อมูลงานคดี ขอ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                    view('mail.Law.Cases.forms_approved', $data_app),
                                                    null,  
                                                    null,  
                                                    $user->reg_email
                                                 );
                     $html = new MailFormsApproved($data_app);
                     Mail::to($user->reg_email)->send($html);
                }
              }

         }
           Session::put('data-check-approve', 'false');
           return redirect('law/cases/forms_approved/'.$lawcasesform->id)->with('flash_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    public function update(Request $request)
    {
     
        $id                     = $request->get('id');
        $approve_id             = $request->get('approve_id');
        $level                  = $request->get('level'); 
        $status                 = $request->get('status');
        $status_cases           = $request->get('status_cases');
        $user_id                = $request->get('user_id');
        $remark                 = $request->get('remark');
        $lawcasesform = LawCasesForm::findOrFail($id);
        $approve  = LawCasesLevelApprove::where('id',$approve_id)->first();
        $message = false;
        if(!is_null($approve)){
               $message = true;
                if($status == '1'){  // เห็นชอบ
                    $approve->status = '3'; // เห็นชอบ

                    // อัพเดทผู้พิจารณาลำดับต่อไป
                    $level = ((int)$level +1); 
                    $level_approve =    LawCasesLevelApprove::where('law_cases_id',$id)->where('level', $level)->first();
                    if(!empty($level_approve)){
                        $level_approve->status = '2'; // รอพิจารณา
                        $level_approve->save();
                    }

                }else{ // ไม่เห็นชอบ
                    $approve->status = '4'; // ไม่เห็นชอบ
                    $approve->remark = !empty($remark) ? $remark : null  ; // ความคิดเห็น
                    $approve->return_to = !empty($user_id) ? $user_id : null  ; // ส่งเรื่องกลับไปยัง

                    $lawcasesform->status = $status_cases; 
                    if($status_cases == '99'){
                        $lawcasesform->cancel_remark = !empty($remark) ? $remark : null;
                        $lawcasesform->cancel_by     =  auth()->user()->getKey();
                        $lawcasesform->cancel_at     =  date('Y-m-d H:i:s');
                        $approve->causes = 'ยกเลิก'  ; // เนื่องจาก   
                    }else{
                         $approve->causes = 'ขอข้อมูลเพิ่มเติม (ตีกลับ)'  ; // เนื่องจาก   
                    }
                    $lawcasesform->save();

                    if(isset($request->attachs)){
                        if ($request->hasFile('attachs')) {
                            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                            $attachs =  HP::singleFileUploadLaw(
                                        $request->file('attachs') ,
                                        $this->attach_path.$lawcasesform->ref_no,
                                        ( $tax_number),
                                        (auth()->user()->FullName ?? null),
                                        'Law',
                                        ((new LawCasesLevelApprove)->getTable()),
                                        $approve->id,
                                        'forms_approved',
                                        'ผลพิจารณาคดี'
                                    );
                            if(!is_null($attachs) && HP::checkFileStorage($attachs->url)){
                                HP::getFileStoragePath($attachs->url);
                            }
                        }
                    }

                }
                $approve->remark = !empty($remark) ? $remark : null  ; // ความคิดเห็น
                $approve->updated_by = auth()->id();

              $approve->save();

               $text    =  [ 
                                '1' => 'รอดำเนินการ',
                                '2' => 'รอพิจารณา',
                                '3' => 'เห็นชอบ',
                                '4' => 'ไม่เห็นชอบ',
                           ] ;

              HP_Law::InsertLawLogWorking(         
                                            1,
                                            ((new LawCasesForm)->getTable()),
                                            $lawcasesform->id,
                                            $lawcasesform->ref_no ?? null,
                                            'แจ้งงานคดี',
                                            'พิจารณางานคดี',
                                             array_key_exists($approve->status,$text) ? $text[$approve->status] : null   ,
                                            $approve->remark 
                                        );
                                        

        }


    
        // อัพเดทสถานะหลัก
         $approves =    LawCasesLevelApprove::where('law_cases_id',$id)->get();
         if(count($approves) == count($approves->where('status','3'))){
                $lawcasesform->status_approve = '2';  // พิจารณาครบถ้วย
                $lawcasesform->status = '1';  // ส่งงานคดสีําเรจ
                $lawcasesform->save();
                $this->send_mail($lawcasesform);
         }else  if($status == '1'){  // เห็นชอบ

              if(!empty($level_approve) && (auth()->user()->getKey() != $level_approve->authorize_userid) ){
                 $user = $level_approve->user_authorize_userid;
                 $case = LawCasesForm::findOrFail($id);
                if(!empty($case)   && !empty($approve)  && !empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                    $url = url('law/cases/forms_approved').'?search='.$case->ref_no;
                    $data_app = [
                                    'case'           => $case, 
                                    'user'           => $user,
                                    'approve'        => $approve, 
                                    'level_approve'  => $level_approve,  
                                    'title'          => "ขอให้พิจารณาข้อมูลงานคดี ขอ $case->offend_name เลขอ้างอิง $case->ref_no",
                                    'url'            => $url
                             ]; 
                     HP_Law::getInsertLawNotifyEmail(1,
                                                  ((new LawCasesLevelApprove)->getTable()),
                                                     $approve->id,
                                                    'ขอให้พิจารณาข้อมูลงานคดี',
                                                    "ขอให้พิจารณาข้อมูลงานคดี ขอ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                    view('mail.Law.Cases.forms_approved', $data_app),
                                                    null,  
                                                    null,  
                                                    $user->reg_email
                                                 );
                     $html = new MailFormsApproved($data_app);
                     Mail::to($user->reg_email)->send($html);
                }
              }

         }
           Session::put('data-check-approve', 'false');

        return response()->json([ 'message' => $message]);


    }
    public function get_user_approve(Request $request)
    {
        $id                     = $request->get('id');
        $approve_id             = $request->get('approve_id');
        $level                  = $request->get('level');
        $message = false;
        $datas = [];     
        $law_cases = LawCasesForm::find($id); 
        if(!empty($law_cases->CreatedName)){
            $datas[]   =  ['id' => $law_cases->created_by, 'text' => 'ผู้แจ้งคดี : '. $law_cases->CreatedName];
            $message = true;
        }
        $approves =    LawCasesLevelApprove::where('law_cases_id',$id)->where('level', '<=', $level)->orderbyRaw('level asc')->get();
        if(count($approves) > 0){
            foreach($approves as $approve){
                if( !empty($approve->authorize_name)){
                    // $datas[$approve->authorize_userid]   =  'ลำดับ '.$approve->level.' : '. $approve->subdepartment->sub_depart_shortname ;
                    $datas[]   =  ['id' => $approve->authorize_userid, 'text' => 'ลำดับ '.$approve->level.' : '. $approve->authorize_name];
                    $message = true;
                }
            }
        }
        return response()->json([ 'message' => $message , 'datas' => $datas ]);
    }

    public function send_mail($lawcasesform){  

        $config = LawConfigEmailNotis::whereNotNull('email_list')->where('id',1)->first();

        if(!is_null($config)){
            $emails =  json_decode($config->email_list,true);
            $mail_list = [];
            foreach($emails as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$mail_list)){
                        $mail_list[] =  $email;
                    }
            }

            if(count($mail_list) > 0  && $lawcasesform->status != '0'){
                $url  =  url('/law/cases/assigns/'.$lawcasesform->id);
                // ข้อมูล
                $data_app = [
                            'url' => $url,
                            'lawcasesform' => $lawcasesform,
                            'title'        => 'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง'.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null)
                        ];

            HP_Law::getInsertLawNotifyEmail(1,
                                            ((new LawCasesForm)->getTable()),
                                            $lawcasesform->id,
                                            'แจ้งงานคดี',
                                            'ขอให้ตรวจสอบข้อมูลงานคดีผลิตภัณฑ์อุตสาหกรรม ของ '.(!empty($lawcasesform->offend_name)?$lawcasesform->offend_name:null).' เลขอ้างอิง'.(!empty($lawcasesform->ref_no)?$lawcasesform->ref_no:null),
                                            view('mail.Law.Cases.cases-forms-config', $data_app),
                                            null,  
                                            null,   
                                            json_encode($mail_list)   
                                            );

            $html = new MailCasesConfig($data_app);
             Mail::to($mail_list)->send($html);

            }
        } 
       
    }
    

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
 
            $lawcase = LawCasesForm::findOrFail($id);
            $lawcase->reg_email                     = !empty($lawcase->law_cases_assign_to->user_created->reg_email)  ? $lawcase->law_cases_assign_to->user_created->reg_email : null;
            $lawcase->law_basic_arrest_id           = !empty($lawcase->law_basic_arrest_to->title) ?  $lawcase->law_basic_arrest_to->title : null;
            $lawcase->law_basic_offend_type_id      = !empty($lawcase->law_basic_offend_type_to->title) ?  $lawcase->law_basic_offend_type_to->title : null;
            $lawcase->ref_id                        = !empty($lawcase->law_offend_type_to->title) ?  $lawcase->law_offend_type_to->title : null;
            $lawcase->date_impound                  = !empty($lawcase->date_impound) ?  HP::revertDate($lawcase->date_impound,true) : null;
            $lawcase->law_basic_resource_id         = !empty($lawcase->law_basic_resource_to->title) ?  $lawcase->law_basic_resource_to->title : null;
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/forms_approved",  "name" => 'พิจารณางานคดี' ],
                [ "link" => "/law/cases/forms_approved/$id",  "name" => 'รายละเอียด' ],

            ];
            $approves =    LawCasesLevelApprove::where('law_cases_id',$id)->orderbyRaw('level asc')->get();
            Session::put('data-check-approve', 'false');   
            return view('laws.cases.forms_approved.show', compact('lawcase', 'breadcrumbs', 'approves' ));
        }
        abort(403);
    }
    
}
