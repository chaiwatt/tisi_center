<?php

namespace App\Http\Controllers\SSO;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sso\User AS SSO_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use HP;
use HP_WS;
use DB;
use App\Models\Section5\Labs;
use App\Models\Section5\Ibcbs;
use App\Models\Section5\Inspectors;
use App\Models\Section5\LabsScope;
use App\Models\Section5\IbcbsScope;
use App\Models\Section5\IbcbsScopeTis;
use App\Models\Section5\InspectorsScope;
use App\Models\Section5\LabsScopeDetail;
use App\Models\Section5\IbcbsScopeDetail;
use App\Models\Section5\InspectorsScopeTis;
use App\Models\Basic\Branch;
use App\Models\Basic\BranchTis;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\RosUserGroupMap;
use App\Models\Bsection5\TestItem;
use App\Models\Tis\Standard;

class MigrateSection5Controller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Migrate ข้อมูลผู้ตรวจประเมิน
     */
    public function inspector()
    {
        $ims = DB::table("champ_inspector")->select('*')->get();

        foreach ($ims as $im) {

            echo $im->id;

            //บันทึกตารางผู้ตรวจ
            $address = HP::GetIDAddress($im->tambon, $im->amphur, $im->province);
            $user = SSO_User::where('tax_number', trim($im->tax))->where('email', trim($im->email))->first();

            $inspector = new Inspectors;
            $inspector->inspectors_code        = HP::ConfigFormat('Inspectors', (new Inspectors)->getTable(), 'inspectors_code', null, null, null);
            $inspector->inspectors_prefix      = $im->prefix;
            $inspector->inspectors_first_name  = $im->name;
            $inspector->inspectors_last_name   = $im->lastname;
            $inspector->inspectors_taxid       = $im->tax;
            $inspector->inspectors_address     = $im->address;
            $inspector->inspectors_moo         = $im->moo;
            $inspector->inspectors_soi         = $im->soi;
            $inspector->inspectors_road        = $im->road;
            $inspector->inspectors_subdistrict = $address->subdistrict_id;
            $inspector->inspectors_district    = $address->district_id;
            $inspector->inspectors_province    = $address->province_id;
            $inspector->inspectors_zipcode     = $im->zipcode;
            $inspector->inspectors_position    = $im->position;
            $inspector->inspectors_phone       = $im->tel;
            $inspector->inspectors_fax         = $im->fax;
            $inspector->inspectors_mobile      = $im->mobile;
            $inspector->inspectors_email       = $im->email;
            $inspector->agency_name            = $im->department;
            $inspector->agency_taxid           = $im->department_tax;
            $inspector->inspector_first_date   = $im->register_date;
            $inspector->state                  = 1;
            $inspector->inspectors_user_id     = !is_null($user) ? $user->id : null ;
            $inspector->save();

            //บันทึกขอบข่าย
            $branch_strings = explode(',', $im->branchs);
            foreach ($branch_strings as $branch_string) {
                $branch_string = trim($branch_string);
                $branch = Branch::where('title', 'LIKE', "%$branch_string%")->first();
                if(!is_null($branch)){
                    $inspector_scope = new InspectorsScope;
                    $inspector_scope->inspectors_id   = $inspector->id;
                    $inspector_scope->inspectors_code = $inspector->inspectors_code;
                    $inspector_scope->branch_id       = $branch->id;
                    $inspector_scope->branch_group_id = $branch->branch_group_id;
                    $inspector_scope->agency_taxid    = $inspector->agency_taxid;
                    $inspector_scope->start_date      = $inspector->inspector_first_date;
                    $inspector_scope->end_date        = $im->expire_date;
                    $inspector_scope->state           = 1;
                    $inspector_scope->type            = 2;
                    $inspector_scope->save();

                    //บันทึกมอก.
                    foreach ($branch->branch_tis as $key => $tis_map) {
                        $tis = $tis_map->tis_standards;
                        $inspector_scope_tis = new InspectorsScopeTis;
                        $inspector_scope_tis->inspector_scope_id = $inspector_scope->id;
                        $inspector_scope_tis->inspectors_code    = $inspector->inspectors_code;
                        $inspector_scope_tis->tis_id             = $tis->id;
                        $inspector_scope_tis->tis_no             = $tis->tis_tisno;
                        $inspector_scope_tis->tis_name           = $tis->title;
                        $inspector_scope_tis->state              = 1;
                        $inspector_scope_tis->save();
                    }

                }else{
                    echo "ไม่พบรายสาขา: $branch_string";
                }

            }

            echo '<br>';

        }

    }

    /**
     * Migrate ข้อมูลหน่วยตรวจประเมิน (IB)
     */
    public function ib()
    {
        $ims = DB::table("champ_ib")->select('*')->get();

        $user_ids = RosUserGroupMap::where('group_id', 16)->pluck('user_id')->toArray(); //id ผู้ใช้งานในระบบ e-licese;

        foreach ($ims as $im) {

            echo $im->id;

            //บันทึกตาราง IB
            $address = HP::GetIDAddress($im->ibcb_subdistrict_id, $im->ibcb_district_id, $im->ibcb_province_id);
            $user = SSO_User::where('tax_number', $im->taxid)->where('email', $im->co_email)->first();

            $ib = new Ibcbs;
            $ib->ibcb_code        = HP::ConfigFormat('IB-CB', (new Ibcbs)->getTable(), 'ibcb_code', 'IB', null, null);
            $ib->ibcb_type        = $im->ibcb_type;
            $ib->name             = $im->name;
            $ib->taxid            = $im->taxid;
            $ib->ibcb_name        = $im->name;
            $ib->ibcb_user_id     = !is_null($user) ? $user->id : null;
            $ib->ibcb_address     = $im->ibcb_address;
            $ib->ibcb_building    = $im->ibcb_building;
            $ib->ibcb_moo         = $im->ibcb_moo;
            $ib->ibcb_soi         = $im->ibcb_soi;
            $ib->ibcb_road        = $im->ibcb_road;
            $ib->ibcb_subdistrict_id = $address->subdistrict_id;
            $ib->ibcb_district_id    = $address->district_id;
            $ib->ibcb_province_id    = $address->province_id;
            $ib->ibcb_zipcode     = $im->ibcb_zipcode;
            $ib->ibcb_phone       = $im->ibcb_phone;
            $ib->ibcb_fax         = $im->ibcb_fax;
            $ib->co_name          = $im->co_name;
            $ib->co_position      = $im->co_position;
            $ib->co_mobile        = $im->co_mobile;
            $ib->co_phone         = $im->co_phone;
            $ib->co_fax           = $im->co_fax;
            $ib->co_email         = $im->co_email;
            $ib->ibcb_start_date  = $im->ibcb_start_date;
            $ib->state            = 1;
            $ib->ref_ibcb_application_no = null;
            $ib->type             = 2;
            $ib->save();

            //อัพเดทข้อมูล e-License
            $elicense_user = RosUsers::where('name', $im->name)->whereIn('id', $user_ids)->first();
            if(!is_null($elicense_user)){
                $elicense_user->ibcb_code = $ib->ibcb_code;
                $elicense_user->save();
            }else{
                echo 'ไม่พบข้อมูล user e-licese';
            }

            //บันทึกขอบข่าย
            $tis_list = explode(',', $im->tis_no);
            foreach ($tis_list as $tis_item) {
                $tis = BranchTis::where('tis_tisno', trim($tis_item))->first();
                if(!is_null($tis)){

                    $scope = IbcbsScope::where('ibcb_id', $ib->id)->where('branch_group_id', $tis->branch_groups_id)->where('start_date', $im->ibcb_start_date)->first();
                    if(is_null($scope)){
                        $scope = new IbcbsScope;
                        $scope->ibcb_id               = $ib->id;
                        $scope->ibcb_code             = $ib->ibcb_code;
                        $scope->branch_group_id       = $tis->branch_groups_id;
                        $scope->isic_no               = null;
                        $scope->start_date            = $im->ibcb_start_date;
                        $scope->end_date              = null;
                        $scope->state                 = 1;
                        $scope->ref_ibcb_application_no = null;
                        $scope->type                  = 2;
                        $scope->save();
                    }

                    $scope_detail = IbcbsScopeDetail::where('ibcb_id', $ib->id)->where('branch_id', $tis->branch_id)->first();
                    if(is_null($scope_detail)){
                        $scope_detail = new IbcbsScopeDetail;
                        $scope_detail->ibcb_id       = $ib->id;
                        $scope_detail->ibcb_code     = $ib->ibcb_code;
                        $scope_detail->ibcb_scope_id = $scope->id;
                        $scope_detail->branch_id     = $tis->branch_id;
                        $scope_detail->audit_result  = 1;
                        $scope_detail->type          = 2;
                        $scope_detail->save();
                    }

                    $scope_tis = IbcbsScopeTis::where('ibcb_code', $ib->ibcb_code)->where('tis_id', $tis->tis_id)->first();
                    if(is_null($scope_tis)){
                        $scope_tis = new IbcbsScopeTis;
                        $scope_tis->ibcb_scope_id        = $scope->id;
                        $scope_tis->ibcb_scope_detail_id = $scope_detail->id;
                        $scope_tis->tis_id               = $tis->tis_id;
                        $scope_tis->tis_no               = $tis->tis_tisno;
                        $scope_tis->ibcb_code            = $ib->ibcb_code;
                        $scope_tis->type                 = 2;
                        $scope_tis->save();
                    }

                }else{
                    echo "ไม่พบมาตรฐาน: $tis_item<br>";
                }
            }

            echo '<br>';

        }

    }

    /**
     * Migrate ข้อมูล Lab
     */
    public function lab()
    {
        $ims = DB::table("champ_lab")->select('*')->get();

        $user_ids = RosUserGroupMap::where('group_id', 15)->pluck('user_id')->toArray(); //id ผู้ใช้งานในระบบ e-licese;

        foreach ($ims as $im) {

            echo $im->id;

            //บันทึกตาราง IB
            $address = HP::GetIDAddress($im->lab_subdistrict_id, $im->lab_district_id, $im->lab_province_id);
            $user = SSO_User::where('name', $im->name)->where('email', $im->co_email)->first();

            $lab = new Labs;
            $lab->lab_code           = HP::ConfigFormat('LAB', (new Labs)->getTable(), 'lab_code', null, null, null);
            $lab->name               = $im->name;
            $lab->taxid              = $im->taxid;
            $lab->lab_name           = $im->lab_name;
            $lab->lab_user_id        = !is_null($user) ? $user->id : null;
            $lab->lab_address        = $im->lab_address;
            $lab->lab_building       = $im->lab_building;
            $lab->lab_moo            = $im->lab_moo;
            $lab->lab_soi            = $im->lab_soi;
            $lab->lab_road           = $im->lab_road;
            $lab->lab_subdistrict_id = $address->subdistrict_id;
            $lab->lab_district_id    = $address->district_id;
            $lab->lab_province_id    = $address->province_id;
            $lab->lab_zipcode        = $im->lab_zipcode;
            $lab->lab_phone          = $im->lab_phone;
            $lab->lab_fax            = $im->lab_fax;
            $lab->co_name            = $im->co_name;
            $lab->co_position        = $im->co_position;
            $lab->co_mobile          = $im->co_mobile;
            $lab->co_phone           = $im->co_phone;
            $lab->co_fax             = $im->co_fax;
            $lab->co_email           = $im->co_email;
            $lab->lab_start_date     = $im->lab_start_date;
            $lab->state              = 1;
            $lab->ref_lab_application_no = null;
            $lab->save();

            //อัพเดทข้อมูล e-License
            $elicense_user = RosUsers::where('name', $im->name)->whereIn('id', $user_ids)->first();
            if(!is_null($elicense_user)){
                $elicense_user->lab_code = $lab->lab_code;
                $elicense_user->save();
            }else{
                echo 'ไม่พบข้อมูล user e-licese';
            }

            //บันทึกขอบข่าย
            $tis_list = explode(',', $im->tis_tisno);
            foreach ($tis_list as $tis_item) {

                $tis_item = trim($tis_item);
                if(empty($tis_item)){
                    continue;
                }

                $tis = Standard::where('tis_tisno', $tis_item)->first();
                if(is_null($tis)){
                    $tis = new Standard;
                    $tis->tis_tisno = $tis_item;
                }

                //รายการทดสอบ
                $test_items = TestItem::where('tis_id', $tis->id)->get();
                $test_items = $test_items->count() > 0 ? $test_items : [(new TestItem)];

                foreach ($test_items as $test_item) {

                    $scope = LabsScope::where('lab_id', $lab->id)->where('test_item_id', $test_item->id)->where('tis_id', $tis->id)->first();
                    if(is_null($scope)){//ถ้ายังไม่มี
                        $scope = new LabsScope;
                        $scope->lab_id                 = $lab->id;
                        $scope->lab_code               = $lab->lab_code;
                        $scope->tis_id                 = $tis->id;
                        $scope->tis_tisno              = $tis->tis_tisno;
                        $scope->test_item_id           = $test_item->id;
                        $scope->ref_lab_application_no = null;
                        $scope->start_date             = $im->lab_start_date;
                        $scope->end_date               = $im->lab_end_date;
                        $scope->state                  = 1;
                        $scope->remarks                = null;
                        $scope->type                   = 2;
                        $scope->close_state_date       = null;
                        $scope->close_remarks          = null;
                        $scope->close_date             = null;
                        $scope->close_by               = null;
                        $scope->save();
                    }

                    //id เครื่องมือทดสอบ
                    $test_item_tools = $test_item->TestItemToolsData;
                    foreach ($test_item_tools as $test_item_tool) {

                        //เครื่องมือทดสอบ
                        $test_tool = $test_item_tool->test_tool;

                        $scope_detail = LabsScopeDetail::where('lab_scope_id', $scope->id)->where('test_tools_id', $test_item_tool->test_tools_id)->first();
                        if(is_null($scope_detail)){
                            $scope_detail = new LabsScopeDetail;
                            $scope_detail->lab_id        = $lab->id;
                            $scope_detail->lab_code      = $lab->lab_code;
                            $scope_detail->lab_scope_id  = $scope->id;
                            $scope_detail->test_tools_id = $test_item_tool->test_tools_id;
                            $scope_detail->test_tools_no = null;
                            $scope_detail->start_date    = $im->lab_start_date;
                            $scope_detail->end_date      = $im->lab_end_date;
                            $scope_detail->state         = 1;
                            $scope_detail->type          = 2;
                            $scope_detail->save();
                        }

                    }

                }

            }

            echo '<br>';

        }

    }

}
