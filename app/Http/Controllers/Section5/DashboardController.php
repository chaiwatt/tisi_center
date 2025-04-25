<?php

namespace App\Http\Controllers\Section5;

use App\Http\Controllers\Controller;
use App\Models\Basic\Tis;
use App\Models\Bsection5\Workgroup;
use App\Models\Bsection5\WorkGroupIBStaff;
use App\Models\Bsection5\Workgroupstaff;
use App\Models\Section5\Ibcbs;
use App\Models\Section5\IbcbsScopeDetail;
use App\Models\Section5\IbcbsScopeTis;
use App\Models\Section5\Labs;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //หน้า Dashboard Menu
    public function index(){

        $column_name = (new Tis)->getKeyName();

        //มาตรฐานที่ถูกยกเลิกของผู้ดูแล IBCB
        $tis_id_ibcbs = [];
        $user = auth()->user();
        $work_group_ib_staffs = WorkGroupIBStaff::where('user_reg_id', $user->getKey())->get();
        foreach ($work_group_ib_staffs as $key => $work_group_ib_staff) {
            $work_group_ib = $work_group_ib_staff->work_group_ib;
            foreach ($work_group_ib->ib_workgroup_branch as $key => $ib_workgroup_branch) {
                $branch_group = $ib_workgroup_branch->branch_group;
                foreach ($branch_group->branchs as $key => $branch) {
                    $branch_tis_id = $branch->branch_tis->pluck('tis_id');
                    $tis_list = Tis::whereIn($column_name, $branch_tis_id)
                               ->where('status', 5)
                               ->pluck($column_name)
                               ->toArray();
                    $tis_id_ibcbs = array_merge($tis_id_ibcbs, $tis_list);
                }
            }
        }

        //IBCB ที่มีมาตรฐานที่ถูกยกเลิก
        $ibcbs = Ibcbs::whereHas('scopes_group', function ($query) use ($tis_id_ibcbs) {
                    $query->whereHas('scopes_tis', function ($query) use ($tis_id_ibcbs) {
                        $query->whereIn('tis_id', $tis_id_ibcbs);
                    });
                 })->get();
        foreach($ibcbs as $ibcb){
            //ขอบข่าย
            $ibcb->scope_amount = IbcbsScopeDetail::where('ibcb_id', $ibcb->id)
                                                  ->whereHas('scope_tis', function ($query) use ($tis_id_ibcbs) {
                                                        $query->whereIn('tis_id', $tis_id_ibcbs);
                                                  })->count();
            //มอก.
            $ibcb->tis_amount = IbcbsScopeTis::whereIn('tis_id', $tis_id_ibcbs)
                                            ->whereHas('ibcb_scope', function ($query) use ($ibcb) {
                                                $query->where('ibcb_id', $ibcb->id);
                                            })->count();

        }

        //มาตรฐานที่ถูกยกเลิกของผู้ดูแล Lab
        $tis_ids = [];
        $work_group_staffs = Workgroupstaff::where('user_reg_id', $user->getKey())->get();
        foreach ($work_group_staffs as $key => $work_group_staff) {
            $work_group = $work_group_staff->work_group;
            if(count($work_group->workgroup_std)>0){
                $work_group_tis_id = $work_group->workgroup_std->pluck('tis_id');
                $tis_list = Tis::whereIn($column_name, $work_group_tis_id)
                               ->where('status', 5)
                               ->pluck($column_name)
                               ->toArray();
                $tis_ids = array_merge($tis_ids, $tis_list);
            }
        }

        //Lab ที่มีมาตรฐานที่ถูกยกเลิก
        $labs = Labs::whereHas('section5_labs_scopes', function ($query) use ($tis_ids) {
                    $query->whereIn('tis_id', $tis_ids);
                })
                ->with('section5_labs_scopes')
                ->get(); 
        foreach($labs as $lab){
            //มอก.
            $lab->tis_amount = Tis::whereIn($column_name, $lab->section5_labs_scopes->pluck('tis_id'))
                                   ->where('status', 5)
                                   ->count();
        }     
   
        return view('admin/section5', compact('ibcbs', 'labs'));
    }

}
