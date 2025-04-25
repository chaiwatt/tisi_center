<?php

namespace App\Http\Controllers\Laws\Config;

use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Config\LawConfigNotification;
use App\Models\Law\Config\LawConfigNotificationDetail;

class LawConfigNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_search     = $request->input('filter_search');
        $filter_status     = $request->input('filter_status');
        $filter_created_at = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawConfigNotification::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                    });
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('state', $filter_status);
                                    }else{
                                        return $query->where('state', '<>', 1)->orWhereNull('state');
                                    }
                                })
                                ->when($filter_created_at, function ($query, $filter_created_at){
                                    return $query->whereDate('created_at', $filter_created_at);
                                })
                                ->with('reward_group')
                                ->with('arrest');

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('color', function ($item) {
                                return $item->ColorHtml;
                            })
                            ->addColumn('condition', function ($item) {
                                return $item->CoditionHtml;
                            })
                            ->addColumn('amount', function ($item) {
                                return $item->amount;
                            })
                            ->addColumn('status', function ($item) {
                                return  @$item->StateIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('updated_by', function ($item) {
                                $updated_by = '';
                                $updated_by .= !empty($item->UpdatedName)?$item->UpdatedName:'-';
                                $updated_by .= !empty($item->updated_at)?' <br> '.'('.HP::DateThai($item->updated_at).')':null;
                                return $updated_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/config/notification','Laws\Config\\LawConfigNotificationController@destroy', 'law-config-notification');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by', 'updated_by', 'color', 'condition', 'created_at'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/notification",  "name" => 'ตั้งค่าแจ้งเตือนวันครบกำหนดชำระเงิน' ],
            ];
            return view('laws.config.notification.index',compact('breadcrumbs'));
        }
        abort(403);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/notification",  "name" => 'ตั้งค่าแจ้งเตือนวันครบกำหนดชำระเงิน' ],
                [ "link" => "/law/config/notification/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.config.notification.create',compact('breadcrumbs'));
        }
        return response(view('403'), 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $config_reward = LawConfigNotification::create($requestData);
            self::saveLawConfigNotificationDetail($config_reward, $request);

            return redirect('law/config/notification')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);
    }

    public function saveLawConfigNotificationDetail($config_reward, $request) {
        $repeater_conditions = $request->get('repeater-condition');
        if(!empty($repeater_conditions) && count($repeater_conditions) > 0){
            foreach($repeater_conditions as $repeater_condition){
                $old_id = !empty($repeater_condition['detail_old_id'])?$repeater_condition['detail_old_id']:null;
                $create_or_update = !empty($old_id)?'updated_by':'created_by';
                $repeater_condition['law_config_notification_id'] = $config_reward->id;
                $repeater_condition[$create_or_update ] = auth()->user()->getKey();
                LawConfigNotificationDetail::where('law_config_notification_id', $config_reward->id)->updateOrCreate(['id' => $old_id], $repeater_condition);
            }
        }
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
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('view-'.$model)) {
            $config_notification = LawConfigNotification::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/notification",  "name" => 'ตั้งค่าแจ้งเตือนวันครบกำหนดชำระเงิน' ],
                [ "link" => "/law/config/notification/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('laws.config.notification.show', compact('config_notification','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('edit-'.$model)) {
            $config_notification = LawConfigNotification::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/notification",  "name" => 'ตั้งค่าแจ้งเตือนวันครบกำหนดชำระเงิน' ],
                [ "link" => "/law/config/notification/$id/edit",  "name" => 'แก้ไข' ],

            ];
            return view('laws.config.notification.edit', compact('config_notification','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $config_reward = LawConfigNotification::findOrFail($id);
            $config_reward->update($requestData);

            $detail_old_ids = collect($request->get('repeater-condition'))->pluck('detail_old_id')->toArray();
            LawConfigNotificationDetail::where('law_config_notification_id', $config_reward->id)
                                        ->when($detail_old_ids, function($query, $detail_old_ids){
                                            return $query->whereNotIn('id', $detail_old_ids);
                                        })->delete();
            self::saveLawConfigNotificationDetail($config_reward, $request);

            return redirect('law/config/notification')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawConfigNotification::destroy($id);
            LawConfigNotificationDetail::where('law_config_notification_id', $id)->delete();
            return redirect('law/config/notification')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-config-notification','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawConfigNotification;
            $resulte = LawConfigNotification::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result = LawConfigNotification::whereIn('id', $id_publish);
        LawConfigNotificationDetail::whereIn('law_config_notification_id', $id_publish)->delete();
        if($result->delete())
        {
            echo 'success';
        }
    }

}
