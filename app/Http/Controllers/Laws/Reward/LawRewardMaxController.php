<?php

namespace App\Http\Controllers\Laws\Reward;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use DB;
use HP;

use  App\Models\Law\Config\LawConfigSection; 
use  App\Models\Law\Config\LawConfigRewardMax;
use App\Models\Law\Basic\LawSection;


class LawRewardMaxController extends Controller
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
 
        $query =  LawConfigSection::query()
                                        ->with([
                                            'basic_section', 'law_config_reward_max_to'
                                        ])
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('basic_section', function ($query)  use ($search_full) {
                                                                   $query->Where(DB::raw("REPLACE(number,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                             });
                                                    break;
                                                case "2":
                                                       $search_full = str_replace(' ', '', $filter_search);
                                                       $section_relation_ids =  LawSection::select('id')->Where(DB::raw("REPLACE(number,' ','')") , 'LIKE', '%' . $search_full . '%')->pluck('id')->toArray();
                                                        $query->where(function($query) use($section_relation_ids) {
                                                               $room_count  =  (array)$section_relation_ids;
                                                                if(count($room_count) > 0){
                                                                    $query->whereJsonContains('section_relation', (string)$room_count[0]);
                                                                    info (count($room_count));
                                                                    for($i = 1; $i <= count($room_count) - 1; $i++) {
                                                                        $query->orWhereJsonContains('section_relation',(string)$room_count[$i]);
                                                                    }
                                                                }else{
                                                                    $query->whereNull('section_relation');
                                                                }

                                                           return $query;
                                                       });
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    $section_relation_ids =  LawSection::select('id')->Where(DB::raw("REPLACE(number,' ','')") , 'LIKE', '%' . $search_full . '%')->pluck('id')->toArray();
                                                    return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->whereHas('basic_section', function ($query) use($search_full){
                                                                                    $query->Where(DB::raw("REPLACE(number,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                                            });
                                                                 })
                                                                 ->orwhere(function($query) use($section_relation_ids) {
                                                                    $room_count  =  (array)$section_relation_ids;
                                                             
                                                                     if(count($room_count) > 0){
                                                                         $query->whereJsonContains('section_relation', (string)$room_count[0]);
                                                                         info (count($room_count));
                                                                         for($i = 1; $i <= count($room_count) - 1; $i++) {
                                                                             $query->orWhereJsonContains('section_relation',(string)$room_count[$i]);
                                                                         }
                                                                     }else{
                                                                         $query->whereNull('section_relation');
                                                                     }
     
                                                                    return $query;
                                                               });
                                                                 
                                                          
                                                    break;
                                            endswitch;
                                        }) 
                                        ->when($filter_status, function ($query, $filter_status){
                                                    if($filter_status == '1'){
                                                        return   $query->whereHas('law_config_reward_max_to', function($query) use ($filter_status){
                                                                        $query->whereNotNull('id');
                                                                     });
                                                    }else{
                                                      $section_ids = LawConfigRewardMax::select('law_config_section_id')->groupBy('law_config_section_id');
                                                      return   $query->whereNotIn('id',$section_ids);
                                                    }
                                           });
                                      

 
        $model = str_slug('law-reward-reward-max','-');
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('number', function ($item) {
                                   return   !empty($item->basic_section->number) ?  $item->basic_section->number : '';
                             })
                             ->addColumn('section_relation', function ($item) {
                                 return   !empty($item->SectionRelationNumber) ?  $item->SectionRelationNumber : '';
                            })
                            ->addColumn('title', function ($item) {
                                return   !empty($item->LawConfigRewardMaxTitle) ? $item->LawConfigRewardMaxTitle :   '';
                            }) 
                            ->addColumn('status', function ($item) {
                                return   !empty($item->law_config_reward_max_to) ?  'กำหนดแล้ว' :   'ไม่กำหนดแล้ว';
                            }) 
                            ->addColumn('full_name', function ($item) {
                                return   !empty($item->law_config_reward_max_to->user_created->FullName) ? $item->law_config_reward_max_to->user_created->FullName :'-';
                              })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->law_config_reward_max_to->created_at) ? HP::DateThai($item->law_config_reward_max_to->created_at)   :'-';    
                            })
                            ->addColumn('action', function ($item) {
                                return '<button type="button" class="btn btn-icon btn-circle btn-light-info  reward_max btn-xs circle"  
                                            data-id="'.$item->id.'" >
                                            <i class="fa fa-pencil  "  style="font-size: 1.5em;">
                                        </button>';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['section_relation','title', 'action'])
                            ->make(true);
    }


    public function index()
    {
        $model = str_slug('law-reward-reward-max','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/reward/reward_max",  "name" => 'กำหนดเพดานเงินคำนวณ' ],
            ];
            return view('laws.reward.reward_max.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function get_data_reward_max(Request $request)
    {
        $message = false;
        $datas = [];
        $id =  $request->id;
        $conditions =   ['='=>'เท่ากับ','<='=>'ไม่เกิน','>='=>'เกิน'];
        if(!empty($id) ){
              $message = true;
              $reward_maxs =  LawConfigRewardMax::where('law_config_section_id',$id)->get();
              if(count($reward_maxs) > 0){
                    foreach($reward_maxs as $item){
                        $object = (object)[];
                        $object->id                       =  $item->id ;
                        $object->arrest_id                =  !empty($item->law_basic_arrest_id) ? $item->law_basic_arrest_id : '';
                        $object->arrest_text              =  !empty($item->arrest->title) ? $item->arrest->title : '';
                        $object->deducted_id              =  !empty($item->condition_percentage) ? $item->condition_percentage : '';
                        $object->deducted_text            =  !empty($item->condition_percentage) && array_key_exists($item->condition_percentage,$conditions) ? $conditions[$item->condition_percentage] : '';
                        $object->number                   =  !empty($item->amount)   ? $item->amount : '';
                        $object->select_amount_id         =  !empty($item->condition_money) ? $item->condition_money : '';
                        $object->select_amount_text       =  !empty($item->condition_money) && array_key_exists($item->condition_money,$conditions) ? $conditions[$item->condition_money] : '';
                        $object->money                    =  !empty($item->money)   ?  number_format($item->money,2) : '';
                        $datas[]                          = $object;
                    }
              }
         }
          return response()->json([ 'message'=> $message ,'datas' => $datas]);
    }

    public function save(Request $request)
    {
        $message     = false;
        $requestData = $request->all();
        $id          = $requestData['reward_max_id'];
  
        if( !empty($requestData['reward-list']) ){

            $lists = $requestData['reward-list'];

            $list_id_data = [];
            foreach($lists as $item ){
                if( isset($item['id']) && !empty($item['id']) ){
                    $list_id_data[] = $item['id'];
                }
            }
            $lists_id = array_diff($list_id_data, [null]); 

            //ลบข้อมูลเดิม
            LawConfigRewardMax::where('law_config_section_id', $id  )
                                    ->when($lists_id, function ($query, $lists_id){
                                        return $query->whereNotIn('id', $lists_id);
                                    })
                                    ->delete();

            foreach($lists as $item ){
                
                $reward_max                    = LawConfigRewardMax::where('law_config_section_id', $id  )->where('id', $item['id'] )->first();
                if(is_null($reward_max)){
                    $reward_max                = new LawConfigRewardMax;
                    $reward_max->created_by    = auth()->user()->getKey();
                }else{
                    $reward_max->updated_by    = auth()->user()->getKey();
                }

                $reward_max->law_config_section_id =  $id ;
                $reward_max->law_basic_arrest_id   = !empty( $item['arrest_id']) ? $item['arrest_id'] : null;
                $reward_max->condition_percentage  = !empty( $item['deducted_id']) ? $item['deducted_id'] : null;
                $reward_max->amount                = !empty( $item['number']) ? $item['number'] : null;
                $reward_max->condition_money       = !empty( $item['select_amount_id']) ? $item['select_amount_id'] : null;
                $reward_max->money                 = !empty(str_replace(",","", $item['amount']))?str_replace(",","",$item['amount']):null;
                $reward_max->state                 = '1';
                $reward_max->save();
            }
            $message = true; 
        }
        return response()->json([ 'message'=> $message]);
    }
}
