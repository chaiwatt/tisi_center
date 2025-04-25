<?php

namespace App\Http\Controllers\Bsection5;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Basic\Tis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Bsection5\TestItem;
use App\Models\Bsection5\TestItemTools;

use stdClass;

class TestItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {

        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');
        $filter_tis_id      = $request->input('filter_tis_id');
        $filter_type        = $request->input('filter_type');
        $filter_test_item   = $request->input('filter_test_item');

        $query = Tis::query()->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->where( function($query) use($search_full) {
                                            $query->where(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%")
                                                    ->Orwhere(DB::Raw("REPLACE(tb3_TisThainame,' ','')"),  'LIKE', "%$search_full%")
                                                    ->OrwhereHas('test_item_data', function ($query) use($search_full) {
                                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                    });
                                        });
                                    })
                                    ->when(is_numeric($filter_status), function ($query) use ($filter_status){
                                        return $query->where('status', $filter_status);
                                    })
                                    ->when($filter_tis_id, function ($query, $filter_tis_id){
                                        return $query->where('tb3_TisAutono', $filter_tis_id);
                                    })
                                    ->when($filter_type, function ($query, $filter_type){
                                        return $query->whereHas('test_item_data', function ($query) use($filter_type) {
                                                            $query->where('type', $filter_type);
                                                        });
                                    })
                                    ->when($filter_test_item, function ($query, $filter_test_item){

                                        if( $filter_test_item == 1){
                                            return $query->whereHas('test_item_data', function ($query) {
                                                                $query->whereNotNull('id');
                                                            });
                                        }else{
                                            return $query->Has('test_item_data','==',0);
                                        }

                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('title', function ($item) {
                                return (!empty($item->tb3_TisThainame)?$item->tb3_TisThainame:null).( !is_null($item->tb3_TisEngname )?'<div><small><em>('.$item->tb3_TisEngname.')</em></small></div>':null );
                            })
                            ->addColumn('tis_tisno', function ($item) {
                                return $item->tb3_Tisno;
                            })
                            ->addColumn('std_format', function ($item) {
                                return $item->StandardFormat;
                            })
                            ->addColumn('status', function ($item) {
                                return  $item->StandardStatus ;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction($item->getKey(), 'bsection5/test_item','Bsection5\\TestItemController@destroy', 'bsection5-testitem',false,true,false);
                            })
                            ->addColumn('test_item', function ($item){
                                return  ($item->test_item_data()->count() >= '1' )?'มีรายการทดสอบ':'ยังไม่มีรายการทดสอบ' ;
                            })
                            ->order(function ($query) {
                                $query->orderBy('tb3_TisAutono', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'tools','title', 'type'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bsection5.test-item.index');
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bsection5.test-item.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            if( !empty($requestData['tis_id']) && is_numeric($requestData['tis_id']) ){
                $standard = Tis::find($requestData['tis_id']);
                $requestData['tis_tisno'] = !is_null($standard) && !empty($standard->tb3_Tisno)?$standard->tb3_Tisno:null;
            }

            $testitem = TestItem::create($requestData);

            if( !isset($requestData['parent_id']) ){
                $requestMain['main_topic_id'] = $testitem->id;
                $requestMain['level'] = 1;
                $testitem->update($requestMain);
            }else{
                $this->GetLevel($requestData['parent_id'], $testitem);
            }

            if( isset($requestData['test_tools_ids']) && !empty($requestData['test_tools_ids'])  ){

                $test_tools_id = $requestData['test_tools_ids'];

                foreach( $test_tools_id as $item ){

                    if( is_numeric($item) ){

                        $tools = TestItemTools::where('test_tools_id', $item )->where('bsection5_test_item_id', $testitem->id )->first();
                        if(is_null($tools)){
                            $tools = new TestItemTools;
                        }
                        $tools->bsection5_test_item_id = $testitem->id;
                        $tools->test_tools_id = $item;
                        $tools->save();

                    }

                }

            }

            return redirect('bsection5/test_item')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');

        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('view-'.$model)) {
            $testitem = TestItem::findOrFail($id);
            $tools = TestItemTools::where('bsection5_test_item_id', $testitem->id )->pluck('test_tools_id', 'test_tools_id')->toArray();
            return view('bsection5.test-item.show', compact('testitem', 'tools'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('edit-'.$model)) {

            return redirect('bsection5/test_item/std-data-item?tis_id='.$id);

            // $testitem = TestItem::findOrFail($id);
            // $tools = TestItemTools::where('bsection5_test_item_id', $testitem->id )->pluck('test_tools_id', 'test_tools_id')->toArray();
            // return view('bsection5.test-item.edit', compact('testitem', 'tools'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $testitem = TestItem::findOrFail($id);
            $testitem->update($requestData);

            if( !isset($requestData['parent_id']) ){
                $requestMain['main_topic_id'] = $testitem->id;
                $requestMain['level'] = 1;
                $testitem->update($requestMain);
            }else{
                $this->GetLevel($requestData['parent_id'], $testitem);
            }

            if( isset($requestData['test_tools_ids']) && !empty($requestData['test_tools_ids'])  ){

                $test_tools_id = $requestData['test_tools_ids'];

                $list_ids = [];
                foreach($test_tools_id as $item){
                    $list_ids[] = $item;
                }
                $list_id = array_diff($list_ids, [null]);

                TestItemTools::where('bsection5_test_item_id', $testitem->id)
                                ->when($list_id, function ($query, $list_id){
                                    return $query->whereNotIn('test_tools_id', $list_id);
                                })
                                ->delete();

                foreach( $test_tools_id as $item ){

                    if( is_numeric($item) ){

                        $tools = TestItemTools::where('test_tools_id', $item )->where('bsection5_test_item_id', $testitem->id )->first();
                        if(is_null($tools)){
                            $tools = new TestItemTools;
                        }
                        $tools->bsection5_test_item_id = $testitem->id;
                        $tools->test_tools_id = $item;
                        $tools->save();

                    }

                }

            }

            return redirect('bsection5/test_item')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');

        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('delete-'.$model)) {
            TestItem::destroy($id);
            return redirect('bsection5/test_item')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result = TestItem::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }

    }

    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');

        $result = TestItem::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request)
    {
        $id_status = $request->input('id_status');
        $state = $request->input('state');

        $result = TestItem::where('id', $id_status)  ->update(['state' => $state]);

        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    public function GetLevel($parent_id, $testitem)
    {

        $main_parent =  TestItem::where('id', $parent_id )->first();
        if( !is_null($main_parent) ){

            if( $main_parent->type == 1 ){

                $requestMain['main_topic_id'] = $main_parent->id;
                $testitem->update($requestMain);

                $requestLevel['level'] = 2;
                $testitem->update($requestLevel);

            }else{

                if( !is_null($main_parent->main_topic_id) ){
                    $requestMain['main_topic_id'] = $main_parent->main_topic_id;
                    $testitem->update($requestMain);
                }

                if( !is_null($main_parent->level) && is_numeric($main_parent->level) ){
                    $requestLevel['level'] = (int)$main_parent->level + 1;
                    $testitem->update($requestLevel);
                }

            }

        }

    }

    public function GetDataTestItem(Request $request)
    {
        $tis_id = $request->get('tis_id');

        $orderby = "CAST(SUBSTRING_INDEX(no,'.',1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',2),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',3),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',4),'.',-1) as UNSIGNED),";
        $orderby .= "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no,'.',5),'.',-1) as UNSIGNED)";

        $testitem = TestItem::Where('tis_id', $tis_id)->where('type',1)->groupBy('main_topic_id')->orderby(DB::raw( $orderby ))->get();
        $level = 0;
        $list =   $this->LoopItem($testitem , $level);
        return response()->json($list);
    }

    public function GetTestItemTypeMain(Request $request)
    {
        $tis_id               = $request->get('tis_id');
        $filter_main_topic_id = $request->get('filter_main_topic_id');
        $filter_parent_id     = $request->get('filter_parent_id');
        $filter_check_main    = $request->get('filter_check_main');
        $type                 = $request->get('type');
        $id                   = $request->get('id');
        $edit                 = $request->get('edit');

        $check =  ( $filter_main_topic_id ==  $filter_parent_id )?true:false; // ถ้าข้อหลักตรงกับข้อย่อย

        $result = TestItem::whereIn('type', [1,2])
                            ->Where('tis_id', $tis_id)
                            ->when($filter_main_topic_id, function ($query, $filter_main_topic_id) use($filter_check_main){
                                if( $filter_check_main == 'true'){
                                    return $query->where('id', $filter_main_topic_id);
                                }else{
                                    return $query->where('main_topic_id', $filter_main_topic_id);
                                }
                            })
                            ->when($filter_parent_id, function ($query, $filter_parent_id) use($type,  $check){
                                if(  $check == false && $type == 3 ){
                                    return $query->where('id', $filter_parent_id);
                                }
                            })
                            ->when($id, function ($query, $id) use($type,$edit){
                                if(  $type == 2 && $edit == 1 ){
                                    $query->whereNotIn('id', [$id]);
                                }
                            })
                            ->select(DB::raw("CONCAT( '(',CASE WHEN type = 1  THEN  'หัวข้อทดสอบ' WHEN type = 2 THEN 'หัวข้อทดสอบย่อย' END, ') '  ,IF(no IS NULL, '', no) , IF(no IS NULL, '', ' '),title) AS no"),'id')
                            ->orderBy('main_topic_id')
                            ->pluck('no', 'id');


        return response()->json($result);
    }

    public function LoopItem($testitem , $level)
    {
        $txt = [];
        $level++;
        $i = 0;

        $model = str_slug('bsection5-testitem','-');

        foreach ( $testitem AS $item ){

            $parent_id = null;
            $main_topic_id = null;
            $check_delete = true;
            if( $item->type == 1){
                $main_topic_id = $item->id;

                if( $item->app_lab_scope()->groupBy('test_item_id')->count()  >= 1 || $item->main_test_item_parent_data()->withCount(['app_lab_scope'])->get()->sum( 'app_lab_scope_count') >= 1 ){
                    $check_delete = false;
                }else if( $item->lab_scope()->groupBy('test_item_id')->count() >= 1 || $item->main_test_item_parent_data()->withCount(['lab_scope'])->get()->sum( 'lab_scope_count') >= 1 ){
                    $check_delete = false;
                }

            }else  if( $item->type == 2){
                $parent_id = $item->id;
                $main_topic_id = $item->main_topic_id;

                if( $item->app_lab_scope()->groupBy('test_item_id')->count()  >= 1 || $item->TestItemParentData()->withCount(['lab_scope'])->get()->sum( 'lab_scope_count') >= 1 ){
                    $check_delete = false;
                }else if( $item->lab_scope()->groupBy('test_item_id')->count() >= 1 || $item->TestItemParentData()->withCount(['lab_scope'])->get()->sum( 'lab_scope_count') >= 1 ){
                    $check_delete = false;
                }

            }else  if( $item->type == 3){
                $parent_id = $item->parent_id;
                $main_topic_id = $item->main_topic_id;

                if( $item->app_lab_scope()->groupBy('test_item_id')->count()  >= 1 ){
                    $check_delete = false;
                }else if( $item->lab_scope()->groupBy('test_item_id')->count() >= 1 ){
                    $check_delete = false;
                }
            }

            $key_on = ++$i;
            $btn = '';
            if(auth()->user()->can('delete-'.$model)) {
                $btn .= '<button '.( ($check_delete == false) ?'disabled':''  ).' type="button" class="btn btn-link text-danger pull-right '.( ($check_delete == true) ?'btn_delete_test_item':''  ).' " data-main_topic_id="'.($main_topic_id).'"  data-parent_id="'.($item->parent_id).'" data-id="'.($item->id).'" data-type="'.($item->type).'" data-title="'.(( !empty($item->no)?'ข้อ '.$item->no.' ':'('.( $key_on).') ' ).$item->title).'">ลบ</button>';
            }
            if(auth()->user()->can('edit-'.$model)) {
                $btn .= '<button type="button" class="btn btn-link text-warning pull-right btn_edit_test_item" data-edit="1" data-id="'.($item->id).'" data-main_topic_id="'.($main_topic_id).'"  data-parent_id="'.($parent_id).'" data-type="'.($item->type).'" data-title="'.(( !empty($item->no)?'ข้อ '.$item->no.' ':'('.( $key_on ).') ' ).$item->title).'">แก้ไข</button>';
            }

            if( in_array($item->type, [1,2]) && auth()->user()->can('add-'.$model) ){
                $btn .= '<button type="button" class="btn btn-link text-success pull-right btn_add_test_item" data-edit="0" data-id="'.($item->id).'" data-main_topic_id="'.($main_topic_id).'"  data-parent_id="'.($parent_id).'" data-type="'.($item->type).'" data-title="'.(( !empty($item->no)?'ข้อ '.$item->no.' ':'('.( $key_on).') ' ).$item->title).'">เพิ่ม</button>';
            }

            $data = new stdClass;
            $data->text = (( !empty($item->no)?'ข้อ '.$item->no.' ':'('.( $key_on ).') ' ).$item->title).(isset($btn)?$btn:null);
            $data->href = '#parent_level_id-'.$item->id;
            $result = $this->LoopItem($item->TestItemParentData, $level);
            $data->tags = [ count($result) ];
            if(count( $result) >= 1 ){
                $data->nodes =  $result;
            }
            $txt[] =   $data;
        }


        return $txt;
    }

    public function StdFormDataItem(Request $request)
    {
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('edit-'.$model)) {

            $tis_id = $request->input('tis_id');

            return view('bsection5.test-item.std-form.create',compact('tis_id'));
        }
        abort(403);

    }

    public function SaveStdTestItem(Request $request)
    {
        $requestData = $request->all();
        $msg = 'error';

        if( empty($requestData['id']) ){ //สร้างใหม่
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            if( !empty($requestData['tis_id']) && is_numeric($requestData['tis_id']) ){
                $standard = Tis::find($requestData['tis_id']);
                $requestData['tis_tisno'] = !is_null($standard) && !empty($standard->tb3_Tisno)?$standard->tb3_Tisno:null;
            }

            //รูปแบบผลทดสอบ
            if(array_key_exists('format_result_detail', $requestData)){
                $requestData['format_result_detail'] = json_encode($requestData['format_result_detail'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $testitem = TestItem::create($requestData);

            if( !isset($requestData['parent_id']) ){
                $requestMain['main_topic_id'] = $testitem->id;
                $requestMain['level'] = 1;
                $testitem->update($requestMain);
            }else{
                $this->GetLevel($requestData['parent_id'], $testitem);
            }

            $msg = 'success';

        }else{ //อัพเดท

            $id = $requestData['id'];

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            //รูปแบบผลทดสอบ
            if(array_key_exists('format_result_detail', $requestData)){
                $requestData['format_result_detail'] = json_encode($requestData['format_result_detail'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $testitem = TestItem::findOrFail($id);
            $testitem->update($requestData);

            if( !isset($requestData['parent_id']) ){
                $requestMain['main_topic_id'] = $testitem->id;
                $requestMain['level'] = 1;
                $testitem->update($requestMain);
            }else{
                $this->GetLevel($requestData['parent_id'], $testitem);
            }

            $msg = 'success';

        }

        if( isset($requestData['test_tools_ids']) && !empty($requestData['test_tools_ids'])  ){

            $test_tools_id = $requestData['test_tools_ids'];

            $list_ids = [];
            foreach($test_tools_id as $item){
                $list_ids[] = $item;
            }
            $list_id = array_diff($list_ids, [null]);

            TestItemTools::where('bsection5_test_item_id', $testitem->id)
                            ->when($list_id, function ($query, $list_id){
                                return $query->whereNotIn('test_tools_id', $list_id);
                            })
                            ->delete();

            foreach( $test_tools_id as $item ){

                if( is_numeric($item) ){

                    $tools = TestItemTools::where('test_tools_id', $item )->where('bsection5_test_item_id', $testitem->id )->first();
                    if(is_null($tools)){
                        $tools = new TestItemTools;
                    }
                    $tools->bsection5_test_item_id = $testitem->id;
                    $tools->test_tools_id = $item;
                    $tools->save();

                }

            }

        }

        return response()->json(['msg' => $msg ]);

    }

    public function GetTestItemData($id)
    {
        $testitem = TestItem::findOrFail($id);
        $testitem->tools = TestItemTools::where('bsection5_test_item_id', $testitem->id )->pluck('test_tools_id', 'test_tools_id')->toArray();
        return response()->json($testitem);
    }

    public function delete_std_test_item($id){

        $result = TestItem::where('id', $id);

        $data = $result->first();
        if(  $data->type == 1 ){
            TestItem::where('main_topic_id',  $data->id )->delete();
            $result->delete();
        }else if( $data->type == 2 ){
            TestItem::where('parent_id',  $data->id )->delete();
            $result->delete();
        }else if( $data->type == 3 ){
            $result->delete();
        }

        echo 'DataDeleted';
        exit;

    }

    public function example_input(Request $request){

        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('view-'.$model)) {
            $data = $request->all();
            return view('bsection5.test-item.std-form.example_input', compact('data'));
        }
        abort(403);

    }

    public function example_input_submit(){
        $model = str_slug('bsection5-testitem','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bsection5.test-item.std-form.example_input_submit');
        }
        abort(403);
    }

}
