<?php

namespace App\Http\Controllers\Laws\Reward;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Yajra\Datatables\Datatables;
use DB;
use HP;

use App\Models\Law\Basic\LawDivisionType;

class LawCdivsionTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');
 

        $query = LawDivisionType::query()
                                        ->with([
                                            'division_category_to'
                                        ])
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return         $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                    break;
                                               case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                     return    $query->whereHas('division_category_to', function ($query)  use ($search_full) {
                                                                      $query->Where(DB::raw("REPLACE(title,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                               });
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                            })->orwhereHas('division_category_to', function ($query)  use ($search_full) {
                                                                  $query->Where(DB::raw("REPLACE(title,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                              });
                                                    break;
                                            endswitch;
                                        })
 
                                        ->when($filter_status, function ($query, $filter_status){
                                            if( $filter_status == 1){
                                                return $query->where('state', $filter_status);
                                            }else{
                                                return $query->where('state', '<>', 1)->orWhereNull('state');
                                            }
                                        }) ;


 
        $model = str_slug('law-reward-divsion-type','-');
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })                
                            ->addColumn('division_category', function ($item) {
                                return !empty($item->division_category_to->title)?$item->division_category_to->title:null;
                            })    
                            ->addColumn('status', function ($item) {
                                return    !empty($item->StateTitle) ?  $item->StateTitle : '';
                            }) 
                            ->addColumn('full_name', function ($item) {
                                return   !empty($item->user_created->FullName) ? $item->user_created->FullName :'-';
                              })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->created_at) ?HP::DateThai($item->created_at):'-';
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/reward/divsion-type','Laws\Reward\\LawCdivsionTypeController@destroy', 'law-reward-divsion-type');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'division_category', 'created_by','title', 'created_at','date'])
                            ->make(true);
    }


    public function index()
    {
        $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/divsion-type",  "name" => 'ประเภทการแบ่งเงิน' ],
            ];
            return view('laws.reward.divsion_type.index',compact('breadcrumbs'));
        }
        abort(403);
    }
    public function create()
    {
        $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                                [ "link" => "/law/reward/divsion-type",  "name" => 'ประเภทการแบ่งเงิน' ],
                                [ "link" => "/law/reward/divsion-type/create",  "name" => 'เพิ่ม' ],
                            ];
            return view('laws.reward.divsion_type.create',compact('breadcrumbs'));
        }
        return abort(403);;

    }

    public function store(Request $request)
    {
        $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('add-'.$model)) {
      
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

             LawDivisionType::create($requestData);
            return redirect('law/reward/divsion-type')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return abort(403);;
    }

    public function show($id)
    {
       $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('view-'.$model)) {
            $division_type = LawDivisionType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/divsion-type",  "name" => 'ประเภทการแบ่งเงิน' ],
                [ "link" => "/law/reward/divsion-type/$id",  "name" => 'รายละเอียด' ],
            ];
            return view('laws.reward.divsion_type.show', compact('division_type','breadcrumbs'));
        }
        return abort(403);;
    }

    public function edit($id)
    {
       $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            $division_type = LawDivisionType::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/divsion-type",  "name" => 'ประเภทการแบ่งเงิน' ],
                [ "link" => "/law/reward/divsion-type/$id/edit",  "name" => 'แก้ไข' ],
            ];
            return view('laws.reward.divsion_type.edit', compact('division_type','breadcrumbs'));
        }
        return abort(403);;
    }

    public function update(Request $request, $id)
    {
       $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
           $division_type = LawDivisionType::findOrFail($id);
           $division_type->update($requestData);

            return redirect('law/reward/divsion-type')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
        return abort(403);;

    }

    public function destroy($id)
    {
       $model = str_slug('law-reward-divsion-type','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawDivisionType::where('id',$id)->delete();
            return redirect('law/reward/divsion-type')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }
 
}
