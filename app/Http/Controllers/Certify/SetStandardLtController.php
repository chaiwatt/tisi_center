<?php

namespace App\Http\Controllers\Certify;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Certify\SetStandards;
use App\Models\Tis\TisiEstandardDraft;
use App\Models\Tis\TisiEstandardDraftPlan;

class SetStandardLtController extends Controller
{
    public function index(Request $request)
    {
       
        $roles = !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : [];
        // $not_admin = (!in_array(1, $roles) && !in_array(25, $roles) && !in_array(44, $roles) );

        // 1. กำหนดว่า role ไหนบ้างที่เป็น admin
        // 64 คือ ลท ที่มาสร้างหนังสือประชุมและนัดหมายการประชุม
        // ลท สำหรับ e-standard
        $role = Role::where('name','ลท สำหรับ e-standard')->first();
        $admin_roles = [1, 25, 44, $role->id];

        // 3. ถ้าต้องการตัวแปร $not_admin ก็แค่กลับค่า
        $not_admin = empty(array_intersect($roles, $admin_roles));

        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');

        $query = SetStandards::query()->with([
            'estandard_plan_to'
        ])

        //  ->whereHas('estandard_plan_to.estandard_offers_to', function ($q) {
        //     $q->where('proposer_type', 'sdo_advanced');
        // })

        ->where(function ($query) {
            $query->whereHas('estandard_plan_to.estandard_offers_to', function ($q) {
                $q->where('proposer_type', 'sdo_advanced');
            })
            ->orWhereNotNull('standard_circular_doc_status');
        })

        ->when($not_admin, function ($query) {
            return $query->where(function ($query) {
                return $query->whereHas('estandard_plan_to', function ($query) {
                    return $query->where('assign_id', auth()->user()->getKey());
                })
                ->orWhereHas('estandard_plan_to.tisi_estandard_draft_to', function ($query) {
                    return $query->where('created_by', auth()->user()->getKey());
                });
            });
        })

        ->when($filter_search, function ($query, $filter_search) {
            $search_full = str_replace(' ', '', $filter_search);
            $query->where(function ($query2) use ($search_full) {
                $query2->whereHas('estandard_plan_to', function ($query) use ($search_full) {
                    return $query->where(DB::raw("REPLACE(tis_name,' ','')"), 'LIKE', "%".$search_full."%");
                })
                ->orWhere(DB::raw("REPLACE(projectid,' ','')"), 'LIKE', "%".$search_full."%");
            });
        })
        ->when($filter_method_id, function ($query, $filter_method_id) {
            $query->where('method_id', $filter_method_id);
        })
        ->when($filter_standard_type, function ($query, $filter_standard_type) {
            $draft_plan = TisiEstandardDraftPlan::select('id')->where('std_type', $filter_standard_type);
            $query->whereIn('plan_id', $draft_plan);
        })
        ->when($filter_year, function ($query, $filter_year) {
            $draft = TisiEstandardDraft::select('id')->where('draft_year', $filter_year);
            $draft_plan = TisiEstandardDraftPlan::select('id')->whereIn('draft_id', $draft);
            $query->whereIn('plan_id', $draft_plan);
        })
        ->when($filter_status, function ($query, $filter_status) {
            if ($filter_status == '-1') {
                $query->where('status_id', 0);
            } else {
                $query->where('status_id', $filter_status);
            }
        })

       
        ->orderBy('id', 'DESC'); 

        //  dd( $query->get());
  
            return view('certify.set-standard-lt.index', [
                'setStandards' => $query->paginate(10) // Paginate with 10 records per page
            ]);
        
       
    }
}
