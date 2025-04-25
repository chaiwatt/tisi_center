<?php

namespace App\Http\Controllers\Laws\Reward;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Yajra\Datatables\Datatables;
use DB;
use HP;
use App\Models\Law\Config\LawConfigReward;
use App\Models\Law\Config\LawConfigRewardSub;
 

class LawRewardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
 
        $query =  LawConfigReward::query()
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('title', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                                $query2->Where('title', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                                if($filter_status != 1){
                                                    return $query->where('state','!=','1');
                                                }else{
                                                      return $query->where('state',$filter_status);
                                                }
                                        })
                                        ;

 
        $model = str_slug('law-reward-reward','-');
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('title', function ($item) {
                                return   !empty($item->title) ?  $item->title : '';
                            }) 
                            ->addColumn('arrest', function ($item) {
                                return    !empty($item->arrest->title) ?  $item->arrest->title : '';
                            }) 
                            ->addColumn('operation', function ($item) {
                                return    !empty($item->OperationTitle) ?   implode(", ",$item->OperationTitle)  : '';
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
                                return HP::buttonActionLaw( $item->id, 'law/reward/reward','Laws\Reward\\LawRewardController@destroy', 'law-reward-reward',false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['section_relation', 'action'])
                            ->make(true);
    } 
    


    public function index()
    {
        $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/reward",  "name" => 'สัดส่วนผู้มีสิทธิ์ได้รับเงิน' ],
            ];
            return view('laws.reward.reward.index',compact('breadcrumbs'));
        }
        abort(403);
    }
    public function create()
    {
        $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('add-'.$model)) {
            $reward_subs =  [new LawConfigRewardSub];
            $breadcrumbs = [
                                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                                [ "link" => "/law/reward/reward",  "name" => 'สัดส่วนผู้มีสิทธิ์ได้รับเงิน' ],
                                [ "link" => "/law/reward/reward/create",  "name" => 'เพิ่ม' ],
                            ];
            return view('laws.reward.reward.create',compact('reward_subs', 'breadcrumbs'));
        }
        return abort(403);;

    }

    public function store(Request $request)
    {
        $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('add-'.$model)) {
      
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            // การดำเนินการงานคดี
            if(isset($request->operation_id) && count($request->operation_id) > 0){
                $requestData['operation_id']  = json_encode($request->operation_id);
            }else{
               $requestData['operation_id']  = null;
            }
            
            $reward = LawConfigReward::create($requestData);

            // กลุ่มผู้สิทธิ์ได้รับเงินรางวัล
            if( isset($request['sub'])  && !is_null($request['sub'])){
                self::law_config_reward_sub($reward,$request['sub']); 
            }

            return redirect('law/reward/reward')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return abort(403);;
    }

 

    public function edit($id)
    {
       $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('edit-'.$model)) {
            $reward = LawConfigReward::findOrFail($id);

            if(!is_null($reward->operation_id)){
                $operations = json_decode($reward->operation_id,true);
                if(!is_null($operations) && $operations != '0'){
                    $reward->operations = $operations;
                }
             }

             $reward_subs =  LawConfigRewardSub::where('law_config_reward_id',$id)->get();
             if(count($reward_subs) == 0){
                $reward_subs =  [new LawConfigRewardSub];
             }

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/reward",  "name" => 'สัดส่วนผู้มีสิทธิ์ได้รับเงิน' ],
                [ "link" => "/law/reward/reward/$id/edit",  "name" => 'แก้ไข' ],
            ];


            return view('laws.reward.reward.edit', compact('reward', 'reward_subs', 'breadcrumbs'));
        }
        return abort(403);;
    }


    public function update(Request $request, $id)
    {
       $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            // การดำเนินการงานคดี
            if(isset($request->operation_id) && count($request->operation_id) > 0){
                $requestData['operation_id']  = json_encode($request->operation_id);
            }else{
               $requestData['operation_id']  = null;
            }

            $reward = LawConfigReward::findOrFail($id);
            $reward->update($requestData);

           // กลุ่มผู้สิทธิ์ได้รับเงินรางวัล
            if( isset($request['sub'])  && !is_null($request['sub'])){
                self::law_config_reward_sub($reward,$request['sub']); 
            }

            return redirect('law/reward/reward')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
        return abort(403);;

    }

    public function destroy($id)
    {
       $model = str_slug('law-reward-reward','-');
        if(auth()->user()->can('delete-'.$model)) {
             LawConfigReward::where('id',$id)->delete();
             LawConfigRewardSub::where('law_config_reward_id',$id)->delete();
            return redirect('law/reward/reward')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }
 

 
    public function law_config_reward_sub($reward,$lists)
    {      
     
        $list_id_data = [];
        if(isset($lists['id'])){
            foreach($lists['id'] as $item ){
            $list_id_data[] = $item;
            }
        }
        $lists_id = array_diff($list_id_data, [null]); 

        //ลบข้อมูลเดิม
        LawConfigRewardSub::where('law_config_reward_id',$reward->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();
          
        foreach($lists['reward_group_id'] as $key => $item ){

                $sub =  LawConfigRewardSub::where('id', @$lists['id'][$key])->first();
                if(is_null($sub)){
                    $sub                = new LawConfigRewardSub;
                    $sub->created_by    = auth()->user()->getKey();
                }else{
                    $sub->updated_by    = auth()->user()->getKey();
                }
      
                $sub->law_config_reward_id = $reward->id;
                $sub->reward_group_id      =  $item;
                $sub->amount               = !empty(str_replace(",","", $lists['amount'][$key]))?str_replace(",","",$lists['amount'][$key]):null;
                $sub->save();

          }
                        

    }

}
