<?php

namespace App\Http\Controllers\FuntionCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;

use App\User;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;

use App\Models\Bsection5\TestItem;
use App\Models\Bsection5\TestItemTools;
use App\Models\Bsection5\TestTool;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\WorkGroupIBBranch;
use App\Models\Bsection5\Workgroup;
use App\Models\Bsection5\Workgrouptis;
use App\Models\Bsection5\Workgroupstaff;

use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Section5\ApplicationInspectorScope;
use App\Models\Section5\ApplicationIbcbScope;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use App\Models\Besurv\Signer;

class Section5Controller extends Controller
{
    public function GetBranchData($id_group)
    {
        if( $id_group === 'ALL' ){
            $data =  Branch::get();
        }else{
            $data =  Branch::where('branch_group_id', $id_group )->get();
        }

        return response()->json($data);
    }


    public function AllMenuSection5()
    {
        $menu = [];
        if( Auth::check() ){

            $laravelMenuSection5 = [];
            //ระบบขึ้นทะเบียนตาม(ม.5)
            if (File::exists(base_path('resources/laravel-admin/new-menu-section5.json'))) {
                $laravelMenuSection5 = json_decode(File::get(base_path('resources/laravel-admin/new-menu-section5.json')));
            }

            //INS
            if( isset( $laravelMenuSection5->menus[0]->items[1]->sub_menus ) ){  
                $menuItem          = new stdClass;
                $menuItem->title   = 'ผู้ตรวจ/ผู้ประเมิน';
                $menuItem->submenu = $this->SetMenu($laravelMenuSection5->menus[0]->items[1]->sub_menus, 'section5_application_ins');
                $menu[] = $menuItem;
            }

            //IBCB
            if( isset( $laravelMenuSection5->menus[0]->items[2]->sub_menus ) ){  
                $menuItem          = new stdClass;
                $menuItem->title   = 'IB/CB';
                $menuItem->submenu = $this->SetMenu($laravelMenuSection5->menus[0]->items[2]->sub_menus, 'section5_application_ibcb');
                $menu[] = $menuItem;
            }

            //Labs
            if( isset( $laravelMenuSection5->menus[0]->items[3]->sub_menus ) ){  
                $menuItem          = new stdClass;
                $menuItem->title   = 'LAB';
                $menuItem->submenu = $this->SetMenu($laravelMenuSection5->menus[0]->items[3]->sub_menus, 'section5_application_lab');
                $menu[] = $menuItem;
            }

            //Report
            if( isset( $laravelMenuSection5->menus[0]->items[4]->sub_menus ) ){  
                $menuItem          = new stdClass;
                $menuItem->title   = 'รายงาน';
                $menuItem->submenu = $this->SetMenu($laravelMenuSection5->menus[0]->items[4]->sub_menus, 'section5_report');
                $menu[] = $menuItem;
            }

            //Basic
            if( isset( $laravelMenuSection5->menus[0]->items[0]->sub_menus ) ){  
                $menuItem          = new stdClass;
                $menuItem->title   = 'ข้อมูลพื้นฐาน';
                $menuItem->submenu = $this->SetMenu($laravelMenuSection5->menus[0]->items[0]->sub_menus, 'section5_basic');
                $menu[] = $menuItem;
            }

        }
        return $menu;

    }


    public function SetMenu($menu, $type = '')
    {
     
        $arr_mian = [ 'section5_basic' => '', 'section5_application_ins' => 'ของผู้ตรวจสอบการทำผลิตภัณฑ์ฯ' ];
        $arr_code = [ 'section5_basic' => 'bg-green', 'section5_application_ins' => 'bg-success' ];


        $submenu = [];

        foreach( $menu AS $item ){

            if( auth()->user()->can('view-'.str_slug(  $item->title )) ){

                $submenu[] =  [
                                "short" => $item->display,
                                "title" => $item->display.( array_key_exists( $type, $arr_mian  )?$arr_mian[$type]:'' ),
                                "slug"  => $item->title,
                                "icon"  => $item->icon,
                                "url"   => $item->url,
                                "color" =>  array_key_exists( $type, $arr_code  )?$arr_code[$type]:"bg-success",
                                "class" => $this->ClassNameInfo( $item->display )

                            ];
            }

        }

        return $submenu;

    }


    public function ClassNameInfo($string)
    {
        $count =  mb_strlen( $string, 'UTF-8' );

        if( $count >= 25 && $count < 33  ){
            $class = 'info-count2';
        }else if( $count >= 33   ){
            $class = 'info-count3';
        }else{
            $class = 'info-count';
        }

        return $class;
    }

    public function save_test_tools(Request $request)
    {

        $test_item =  $request->get('test_item');
        $test_tool =  $request->get('test_tool');
        $test_tool_id =  $request->get('test_tool_id');
        $type =  $request->get('type');

        if( $type == 1){
            $check = TestTool::where(DB::raw("REPLACE(title,' ','')"), $test_tool )->first();
        }else{
            $check = TestTool::where( 'id', $test_tool_id )->first();
        }

        $tools_id = null;

        if( !is_null($test_item) ){

            if( is_null($check) ){

                $newtools['title'] = $test_tool;
                $newtools['state'] = 1;
                $newtools['created_by'] = 0;

                $tools = TestTool::create($newtools);
                $tools_id = $tools->id;
                $item_tools = TestItemTools::Where('bsection5_test_item_id', $test_item )->where( 'test_tools_id', $tools->id  )->first();

                if( is_null($item_tools) ){

                    $toolsT = new TestItemTools;
                    $toolsT->bsection5_test_item_id = $test_item;
                    $toolsT->test_tools_id = $tools->id;
                    $toolsT->save();
                }

                $mgs = 'success';

            }else{

                $tools = $check;
                $tools_id = $tools->id;
                $item_tools = TestItemTools::Where('bsection5_test_item_id', $test_item )->where( 'test_tools_id', $tools->id  )->first();

                if( is_null($item_tools) ){

                    $toolsT = new TestItemTools;
                    $toolsT->bsection5_test_item_id = $test_item;
                    $toolsT->test_tools_id = $tools->id;
                    $toolsT->save();

                }

                $mgs = 'success';
            }

        }else{
            $mgs = "not success";
        }

        $data = new stdClass;
        $data->mgs = $mgs;
        $data->tools_id = $tools_id;

        return response()->json($data);


    }

    public function GetBasicTools($test_item_id)
    {
        $data = TestTool::where(function($query) use($test_item_id){
                                $ids = DB::table((new TestItemTools)->getTable().' AS item')
                                            ->leftJoin((new TestTool)->getTable().' AS tools', 'tools.id', '=', 'item.test_tools_id')
                                            ->where( function($query) use($test_item_id ) {
                                                $query->where('item.bsection5_test_item_id',  $test_item_id);
                                            })
                                            ->select('tools.id');

                                $query->whereNotIn('id',  $ids);
                            })
                            ->select('title', 'id')
                            ->get();
        return response()->json($data);

    }

    public function GetTestItemTools($id)
    {

        if(  !empty($id) && is_numeric($id) ){

            $data = DB::table((new TestItemTools)->getTable().' AS item')
                        ->leftJoin((new TestTool)->getTable().' AS tools', 'tools.id', '=', 'item.test_tools_id')
                        ->where( function($query) use($id ) {
                            $query->where('item.bsection5_test_item_id',  $id);
                        })
                        ->whereNotNull('tools.id')
                        ->select('tools.title', 'tools.id')
                        ->get();

            return response()->json($data);

        }

    }

    public function GetTestItem($tis_id)
    {

        if(  !empty($tis_id) && is_numeric($tis_id) ){

            $main = TestItem::where('tis_id', $tis_id)
                            ->where('type', 1)
                            ->with('main_test_item_parent_data')
                            ->get();

            $list = [];
            foreach( $main AS $mains ){

                $parent = $mains->main_test_item_parent_data->where('input_result', 1);

                if( count( $parent ) >= 1 ){

                    foreach( $parent AS $parents ){
                        $data = new stdClass;
                        $data->id = $parents->id;
                        if(  $parents->type == 1 ){
                            $data->title = ( !empty( $parents->no )?$parents->no.' ' :null ).$parents->title;
                        }else{
                            $data->title = ( !empty( $parents->no )?$parents->no.' ' :null ).$parents->title.' <em>(ภายใต้หัวข้อทดสอบ '.(  ( !empty( $mains->no )?$mains->no.' ' :null ).$mains->title ).')</em>';
                        }

                        $list[] =  $data;
                    }

                }
            }

            return response()->json($list);

        }

    }

    public function ApplicationLabsScope($id)
    {
        $applicationlab = ApplicationLab::findOrFail($id);

        $scope_group = $applicationlab->app_scope_standard()->with('tis_standards')->select('tis_id')->groupBy('tis_id')->get();

        $test_item_id = $applicationlab->app_scope_standard()->pluck('test_item_id', 'test_item_id')->toArray();

        $list = [];
        foreach( $scope_group AS $group ){

            $tis_standards = $group->tis_standards;
            $tis_id = $group->tis_id;
            $lab_id = $applicationlab->id;

            $testitem = TestItem::Where('tis_id', $tis_id)
                        ->where('type',1)
                        ->where( function($query) use($lab_id, $tis_id){
                            $ids = DB::table((new ApplicationLabScope)->getTable().' AS scope')
                                        ->leftJoin((new TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                        ->where('scope.application_lab_id', $lab_id )
                                        ->where('test.tis_id', $tis_id )
                                        ->select('test.main_topic_id');
                            $query->whereIn('id', $ids  );
                        })
                        ->groupBy('main_topic_id')
                        ->orderby('no')
                        ->get();

            $level = 0;
            $result = $this->LoopItemApplication($testitem, $level, $test_item_id);
            $data = new stdClass;
            $data->text = $tis_standards->tis_tisno.' : '.$tis_standards->title;
            $data->tags = [ count($result) ];
            $data->nodes =  $result;

            $list[] = $data;

        }

        return response()->json($list);
    }

    public function LoopItemApplication($testitem , $level, $test_item_id)
    {
        $txt = [];
        $level++;
        $i = 0;

        foreach ( $testitem AS $item ){
            ++$i;

            $data = new stdClass;
            $data->text = (( !empty($item->no)?'ข้อ '.$item->no.' ':'' ).$item->title);
            $result = $this->LoopItemApplication($item->TestItemParentData, $level, $test_item_id);
            $data->tags = [ count($result) ];

            $data->tags = [ count($result) ];
            if(count( $result) >= 1 ){
                $data->nodes =  $result;
                $txt[] =   $data; 
            }else{
                if( in_array( $item->id,  $test_item_id ) ){
                    $txt[] =   $data; 
                }
            }

        }

        return $txt;
    }

    public function workgroup_ib_staff(Request $request){
        $ids = $request->get('ids');
        $app_type = $request->get('app_type');

        $branch_group_ids = [];
        if(  $app_type == 'ins'){
            $branch_group_ids = ApplicationInspectorScope::whereIn('application_id', $ids)->select('branch_group_id');
        }else if( $app_type == 'ibcb' ){
            $branch_group_ids = ApplicationIbcbScope::whereIn('application_id', $ids)->select('branch_group_id');
        }

        //query id สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
        $workgroup_query    = WorkGroupIB::whereHas('ib_workgroup_branch', function($query) use ($branch_group_ids){
                                                $query->whereIn('branch_group_id', $branch_group_ids);
                                            })
                                            ->where('state', 1) //เฉพาะกลุ่มที่เปิดใช้งาน
                                            ->select('id');

        $staff_ids = WorkGroupIBStaff::whereIn('workgroup_id', $workgroup_query)->select('user_reg_id');

        $users = User::where('status', 1)
                     ->whereIn('runrecno', $staff_ids)
                     ->select(DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name"), 'runrecno')
                     ->pluck('name', 'runrecno');

        return response()->json($users);
    }

    public function workgroup_lab_staff(Request $request){
        $ids = $request->get('ids');

        $tis_ids = ApplicationLabScope::whereIn('application_lab_id', $ids)->select('tis_id');

        // query id มอก.ตามเจ้าหน้าที่ที่รับผิดชอบ
        $workgroup_query = Workgroup::whereHas('workgroup_std', function($query) use ($tis_ids){
                                            $query->whereIn('tis_id', $tis_ids);
                                        })
                                        ->where('state', 1) //เฉพาะกลุ่มที่เปิดใช้งาน
                                        ->select('id');

        $staff_ids  = Workgroupstaff::whereIn('workgroup_id', $workgroup_query)->select('user_reg_id');

        $users = User::where('status', 1)
                        ->whereIn('runrecno', $staff_ids)
                        ->select(DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name"), 'runrecno')
                        ->pluck('name', 'runrecno');

        return response()->json($users);

    }

    public function signPosition($id) {
        $signer =  Signer::where('id',$id)->first();
        if(!is_null($signer)){
            return response()->json([
                    'sign_position'=> !empty($signer->position) ? $signer->position : ' ' ,
                    'sign_name'=> !empty($signer->title) ? $signer->title : ' ' 
                ]);
        }
   
    }
}
