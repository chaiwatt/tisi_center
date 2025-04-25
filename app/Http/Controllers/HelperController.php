<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Permission;

class HelperController extends Controller
{
    // เรียก http://127.0.0.1:8081/add-permission
    public function addPermission()
    {
        $admin = User::where('reg_email','=','admin@admin.com')->withTrashed()->first();

        $admin_role = Role::find(1);

        // $bcertify_scope_lab_test_add    = Permission::firstOrCreate(['name' => 'add-bcertify-scope-lab-test']);
        // $bcertify_scope_lab_test_view   = Permission::firstOrCreate(['name' => 'view-bcertify-scope-lab-test']);
        // $bcertify_scope_lab_test_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-scope-lab-test']);
        // $bcertify_scope_lab_test_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-scope-lab-test']);

        // if (!$admin->hasPermission($bcertify_scope_lab_test_add)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_test_add);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_test_view)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_test_view);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_test_edit)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_test_edit);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_test_delete)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_test_delete);
        // }
        
        // $bcertify_scope_lab_cal_add    = Permission::firstOrCreate(['name' => 'add-bcertify-scope-lab-cal']);
        // $bcertify_scope_lab_cal_view   = Permission::firstOrCreate(['name' => 'view-bcertify-scope-lab-cal']);
        // $bcertify_scope_lab_cal_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-scope-lab-cal']);
        // $bcertify_scope_lab_cal_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-scope-lab-cal']);

        // if (!$admin->hasPermission($bcertify_scope_lab_cal_add)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_cal_add);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_cal_view)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_cal_view);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_cal_edit)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_cal_edit);
        // }
        // if (!$admin->hasPermission($bcertify_scope_lab_cal_delete)) {
        //     $admin_role->givePermissionTo($bcertify_scope_lab_cal_delete);
        // }

        // $bcertify_scope_cb_add    = Permission::firstOrCreate(['name' => 'add-bcertify-scope-cb']);
        // $bcertify_scope_cb_view   = Permission::firstOrCreate(['name' => 'view-bcertify-scope-cb']);
        // $bcertify_scope_cb_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-scope-cb']);
        // $bcertify_scope_cb_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-scope-cb']);

        // if (!$admin->hasPermission($bcertify_scope_cb_add)) {
        //     $admin_role->givePermissionTo($bcertify_scope_cb_add);
        // }
        // if (!$admin->hasPermission($bcertify_scope_cb_view)) {
        //     $admin_role->givePermissionTo($bcertify_scope_cb_view);
        // }
        // if (!$admin->hasPermission($bcertify_scope_cb_edit)) {
        //     $admin_role->givePermissionTo($bcertify_scope_cb_edit);
        // }
        // if (!$admin->hasPermission($bcertify_scope_cb_delete)) {
        //     $admin_role->givePermissionTo($bcertify_scope_cb_delete);
        // }

        // $bcertify_scope_ib_add    = Permission::firstOrCreate(['name' => 'add-bcertify-scope-ib']);
        // $bcertify_scope_ib_view   = Permission::firstOrCreate(['name' => 'view-bcertify-scope-ib']);
        // $bcertify_scope_ib_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-scope-ib']);
        // $bcertify_scope_ib_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-scope-ib']);

        // if (!$admin->hasPermission($bcertify_scope_ib_add)) {
        //     $admin_role->givePermissionTo($bcertify_scope_ib_add);
        // }
        // if (!$admin->hasPermission($bcertify_scope_ib_view)) {
        //     $admin_role->givePermissionTo($bcertify_scope_ib_view);
        // }
        // if (!$admin->hasPermission($bcertify_scope_ib_edit)) {
        //     $admin_role->givePermissionTo($bcertify_scope_ib_edit);
        // }
        // if (!$admin->hasPermission($bcertify_scope_ib_delete)) {
        //     $admin_role->givePermissionTo($bcertify_scope_ib_delete);
        // }

        // $lab_scope_request_add    = Permission::firstOrCreate(['name' => 'add-lab-scope-request']);
        // $lab_scope_request_view   = Permission::firstOrCreate(['name' => 'view-lab-scope-request']);
        // $lab_scope_request_edit   = Permission::firstOrCreate(['name' => 'edit-lab-scope-request']);
        // $lab_scope_request_delete = Permission::firstOrCreate(['name' => 'delete-lab-scope-request']);

        // if (!$admin->hasPermission($lab_scope_request_add)) {
        //     $admin_role->givePermissionTo($lab_scope_request_add);
        // }
        // if (!$admin->hasPermission($lab_scope_request_view)) {
        //     $admin_role->givePermissionTo($lab_scope_request_view);
        // }
        // if (!$admin->hasPermission($lab_scope_request_edit)) {
        //     $admin_role->givePermissionTo($lab_scope_request_edit);
        // }
        // if (!$admin->hasPermission($lab_scope_request_delete)) {
        //     $admin_role->givePermissionTo($lab_scope_request_delete);
        // }

        // $cb_scope_request_add    = Permission::firstOrCreate(['name' => 'add-cb-scope-request']);
        // $cb_scope_request_view   = Permission::firstOrCreate(['name' => 'view-cb-scope-request']);
        // $cb_scope_request_edit   = Permission::firstOrCreate(['name' => 'edit-cb-scope-request']);
        // $cb_scope_request_delete = Permission::firstOrCreate(['name' => 'delete-cb-scope-request']);

        // if (!$admin->hasPermission($cb_scope_request_add)) {
        //     $admin_role->givePermissionTo($cb_scope_request_add);
        // }
        // if (!$admin->hasPermission($cb_scope_request_view)) {
        //     $admin_role->givePermissionTo($cb_scope_request_view);
        // }
        // if (!$admin->hasPermission($cb_scope_request_edit)) {
        //     $admin_role->givePermissionTo($cb_scope_request_edit);
        // }
        // if (!$admin->hasPermission($cb_scope_request_delete)) {
        //     $admin_role->givePermissionTo($cb_scope_request_delete);
        // }

        // $ib_scope_request_add    = Permission::firstOrCreate(['name' => 'add-ib-scope-request']);
        // $ib_scope_request_view   = Permission::firstOrCreate(['name' => 'view-ib-scope-request']);
        // $ib_scope_request_edit   = Permission::firstOrCreate(['name' => 'edit-ib-scope-request']);
        // $ib_scope_request_delete = Permission::firstOrCreate(['name' => 'delete-ib-scope-request']);

        // if (!$admin->hasPermission($ib_scope_request_add)) {
        //     $admin_role->givePermissionTo($ib_scope_request_add);
        // }
        // if (!$admin->hasPermission($ib_scope_request_view)) {
        //     $admin_role->givePermissionTo($ib_scope_request_view);
        // }
        // if (!$admin->hasPermission($ib_scope_request_edit)) {
        //     $admin_role->givePermissionTo($ib_scope_request_edit);
        // }
        // if (!$admin->hasPermission($ib_scope_request_delete)) {
        //     $admin_role->givePermissionTo($ib_scope_request_delete);
        // }

        // $ib_scope_request_add    = Permission::firstOrCreate(['name' => 'add-ib-scope-request']);
        // $ib_scope_request_view   = Permission::firstOrCreate(['name' => 'view-ib-scope-request']);
        // $ib_scope_request_edit   = Permission::firstOrCreate(['name' => 'edit-ib-scope-request']);
        // $ib_scope_request_delete = Permission::firstOrCreate(['name' => 'delete-ib-scope-request']);

        // if (!$admin->hasPermission($ib_scope_request_add)) {
        //     $admin_role->givePermissionTo($ib_scope_request_add);
        // }
        // if (!$admin->hasPermission($ib_scope_request_view)) {
        //     $admin_role->givePermissionTo($ib_scope_request_view);
        // }
        // if (!$admin->hasPermission($ib_scope_request_edit)) {
        //     $admin_role->givePermissionTo($ib_scope_request_edit);
        // }
        // if (!$admin->hasPermission($ib_scope_request_delete)) {
        //     $admin_role->givePermissionTo($ib_scope_request_delete);
        // }


        // $auditor_assignment_add    = Permission::firstOrCreate(['name' => 'add-auditorassignment']);
        // $auditor_assignment_view   = Permission::firstOrCreate(['name' => 'view-auditorassignment']);
        // $auditor_assignment_edit   = Permission::firstOrCreate(['name' => 'edit-auditorassignment']);
        // $auditor_assignment_delete = Permission::firstOrCreate(['name' => 'delete-auditorassignment']);

        // if (!$admin->hasPermission($auditor_assignment_add)) {
        //     $admin_role->givePermissionTo($auditor_assignment_add);
        // }
        // if (!$admin->hasPermission($auditor_assignment_view)) {
        //     $admin_role->givePermissionTo($auditor_assignment_view);
        // }
        // if (!$admin->hasPermission($auditor_assignment_edit)) {
        //     $admin_role->givePermissionTo($auditor_assignment_edit);
        // }
        // if (!$admin->hasPermission($auditor_assignment_delete)) {
        //     $admin_role->givePermissionTo($auditor_assignment_delete);
        // }

        // $auditor_assignment_add    = Permission::where('name','add-auditorassignment')->first();
        // $auditor_assignment_view   = Permission::where('name','view-auditorassignment')->first();
        // $auditor_assignment_edit   = Permission::where('name','edit-auditorassignment')->first();
        // $auditor_assignment_delete = Permission::where('name','delete-auditorassignment')->first();

        // $std_directoer_role = Role::find(25);
    
        // $std_directoer_role->givePermissionTo($auditor_assignment_add);
        // $std_directoer_role->givePermissionTo($auditor_assignment_view);
        // $std_directoer_role->givePermissionTo($auditor_assignment_edit);
        // $std_directoer_role->givePermissionTo($auditor_assignment_delete);
        

        // $board_auditor_doc_review_add    = Permission::firstOrCreate(['name' => 'add-board-auditor-doc-review']);
        // $board_auditor_doc_review_view   = Permission::firstOrCreate(['name' => 'view-board-auditor-doc-review']);
        // $board_auditor_doc_review_edit   = Permission::firstOrCreate(['name' => 'edit-board-auditor-doc-review']);
        // $board_auditor_doc_review_delete = Permission::firstOrCreate(['name' => 'delete-board-auditor-doc-review']);

        // if (!$admin->hasPermission($board_auditor_doc_review_add)) {
        //     $admin_role->givePermissionTo($board_auditor_doc_review_add);
        // }
        // if (!$admin->hasPermission($board_auditor_doc_review_view)) {
        //     $admin_role->givePermissionTo($board_auditor_doc_review_view);
        // }
        // if (!$admin->hasPermission($board_auditor_doc_review_edit)) {
        //     $admin_role->givePermissionTo($board_auditor_doc_review_edit);
        // }
        // if (!$admin->hasPermission($board_auditor_doc_review_delete)) {
        //     $admin_role->givePermissionTo($board_auditor_doc_review_delete);
        // }

        // $save_board_auditor_doc_review_add    = Permission::firstOrCreate(['name' => 'add-save-board-auditor-doc-review']);
        // $save_board_auditor_doc_review_view   = Permission::firstOrCreate(['name' => 'view-save-board-auditor-doc-review']);
        // $save_board_auditor_doc_review_edit   = Permission::firstOrCreate(['name' => 'edit-save-board-auditor-doc-review']);
        // $save_board_auditor_doc_review_delete = Permission::firstOrCreate(['name' => 'delete-save-board-auditor-doc-review']);

        // if (!$admin->hasPermission($save_board_auditor_doc_review_add)) {
        //     $admin_role->givePermissionTo($save_board_auditor_doc_review_add);
        // }
        // if (!$admin->hasPermission($save_board_auditor_doc_review_view)) {
        //     $admin_role->givePermissionTo($save_board_auditor_doc_review_view);
        // }
        // if (!$admin->hasPermission($save_board_auditor_doc_review_edit)) {
        //     $admin_role->givePermissionTo($save_board_auditor_doc_review_edit);
        // }
        // if (!$admin->hasPermission($save_board_auditor_doc_review_delete)) {
        //     $admin_role->givePermissionTo($save_board_auditor_doc_review_delete);
        // }

        // $assessment_report_assignment_add    = Permission::firstOrCreate(['name' => 'add-assessment-report-assignment']);
        // $assessment_report_assignment_view   = Permission::firstOrCreate(['name' => 'view-assessment-report-assignment']);
        // $assessment_report_assignment_edit   = Permission::firstOrCreate(['name' => 'edit-assessment-report-assignment']);
        // $assessment_report_assignment_delete = Permission::firstOrCreate(['name' => 'delete-assessment-report-assignment']);

        // if (!$admin->hasPermission($assessment_report_assignment_add)) {
        //     $admin_role->givePermissionTo($assessment_report_assignment_add);
        // }
        // if (!$admin->hasPermission($assessment_report_assignment_view)) {
        //     $admin_role->givePermissionTo($assessment_report_assignment_view);
        // }
        // if (!$admin->hasPermission($assessment_report_assignment_edit)) {
        //     $admin_role->givePermissionTo($assessment_report_assignment_edit);
        // }
        // if (!$admin->hasPermission($assessment_report_assignment_delete)) {
        //     $admin_role->givePermissionTo($assessment_report_assignment_delete);
        // }

        // $lab_scope_review_add    = Permission::firstOrCreate(['name' => 'add-lab-scope-review']);
        // $lab_scope_review_view   = Permission::firstOrCreate(['name' => 'view-lab-scope-review']);
        // $lab_scope_review_edit   = Permission::firstOrCreate(['name' => 'edit-lab-scope-review']);
        // $lab_scope_review_delete = Permission::firstOrCreate(['name' => 'delete-lab-scope-review']);

        // if (!$admin->hasPermission($lab_scope_review_add)) {
        //     $admin_role->givePermissionTo($lab_scope_review_add);
        // }
        // if (!$admin->hasPermission($lab_scope_review_view)) {
        //     $admin_role->givePermissionTo($lab_scope_review_view);
        // }
        // if (!$admin->hasPermission($lab_scope_review_edit)) {
        //     $admin_role->givePermissionTo($lab_scope_review_edit);
        // }
        // if (!$admin->hasPermission($lab_scope_review_delete)) {
        //     $admin_role->givePermissionTo($lab_scope_review_delete);
        // }


        // auditor-tracking-assignment

        // $auditor_tracking_assignment_add    = Permission::firstOrCreate(['name' => 'add-auditor-tracking-assignment']);
        // $auditor_tracking_assignment_view   = Permission::firstOrCreate(['name' => 'view-auditor-tracking-assignment']);
        // $auditor_tracking_assignment_edit   = Permission::firstOrCreate(['name' => 'edit-auditor-tracking-assignment']);
        // $auditor_tracking_assignment_delete = Permission::firstOrCreate(['name' => 'delete-auditor-tracking-assignment']);

        // if (!$admin->hasPermission($auditor_tracking_assignment_add)) {
        //     $admin_role->givePermissionTo($auditor_tracking_assignment_add);
        // }
        // if (!$admin->hasPermission($auditor_tracking_assignment_view)) {
        //     $admin_role->givePermissionTo($auditor_tracking_assignment_view);
        // }
        // if (!$admin->hasPermission($auditor_tracking_assignment_edit)) {
        //     $admin_role->givePermissionTo($auditor_tracking_assignment_edit);
        // }
        // if (!$admin->hasPermission($auditor_tracking_assignment_delete)) {
        //     $admin_role->givePermissionTo($auditor_tracking_assignment_delete);
        // }

        // $tracking_assessment_report_assignment_add    = Permission::firstOrCreate(['name' => 'add-tracking-assessment-report-assignment']);
        // $tracking_assessment_report_assignment_view   = Permission::firstOrCreate(['name' => 'view-tracking-assessment-report-assignment']);
        // $tracking_assessment_report_assignment_edit   = Permission::firstOrCreate(['name' => 'edit-tracking-assessment-report-assignment']);
        // $tracking_assessment_report_assignment_delete = Permission::firstOrCreate(['name' => 'delete-tracking-assessment-report-assignment']);

        // if (!$admin->hasPermission($tracking_assessment_report_assignment_add)) {
        //     $admin_role->givePermissionTo($tracking_assessment_report_assignment_add);
        // }
        // if (!$admin->hasPermission($tracking_assessment_report_assignment_view)) {
        //     $admin_role->givePermissionTo($tracking_assessment_report_assignment_view);
        // }
        // if (!$admin->hasPermission($tracking_assessment_report_assignment_edit)) {
        //     $admin_role->givePermissionTo($tracking_assessment_report_assignment_edit);
        // }
        // if (!$admin->hasPermission($tracking_assessment_report_assignment_delete)) {
        //     $admin_role->givePermissionTo($tracking_assessment_report_assignment_delete);
        // }

        // $setting_team_cb_add    = Permission::firstOrCreate(['name' => 'add-setting-team-cb']);
        // $setting_team_cb_view   = Permission::firstOrCreate(['name' => 'view-setting-team-cb']);
        // $setting_team_cb_edit   = Permission::firstOrCreate(['name' => 'edit-setting-team-cb']);
        // $setting_team_cb_delete = Permission::firstOrCreate(['name' => 'delete-setting-team-cb']);

        // if (!$admin->hasPermission($setting_team_cb_add)) {
        //     $admin_role->givePermissionTo($setting_team_cb_add);
        // }
        // if (!$admin->hasPermission($setting_team_cb_view)) {
        //     $admin_role->givePermissionTo($setting_team_cb_view);
        // }
        // if (!$admin->hasPermission($setting_team_cb_edit)) {
        //     $admin_role->givePermissionTo($setting_team_cb_edit);
        // }
        // if (!$admin->hasPermission($setting_team_cb_delete)) {
        //     $admin_role->givePermissionTo($setting_team_cb_delete);
        // }

        // $setting_team_ib_add    = Permission::firstOrCreate(['name' => 'add-setting-team-ib']);
        // $setting_team_ib_view   = Permission::firstOrCreate(['name' => 'view-setting-team-ib']);
        // $setting_team_ib_edit   = Permission::firstOrCreate(['name' => 'edit-setting-team-ib']);
        // $setting_team_ib_delete = Permission::firstOrCreate(['name' => 'delete-setting-team-ib']);

        // if (!$admin->hasPermission($setting_team_ib_add)) {
        //     $admin_role->givePermissionTo($setting_team_ib_add);
        // }
        // if (!$admin->hasPermission($setting_team_ib_view)) {
        //     $admin_role->givePermissionTo($setting_team_ib_view);
        // }
        // if (!$admin->hasPermission($setting_team_ib_edit)) {
        //     $admin_role->givePermissionTo($setting_team_ib_edit);
        // }
        // if (!$admin->hasPermission($setting_team_ib_delete)) {
        //     $admin_role->givePermissionTo($setting_team_ib_delete);
        // }


        // $ib_board_auditor_doc_review_add    = Permission::firstOrCreate(['name' => 'add-ib-board-auditor-doc-review']);
        // $ib_board_auditor_doc_review_view   = Permission::firstOrCreate(['name' => 'view-ib-board-auditor-doc-review']);
        // $ib_board_auditor_doc_review_edit   = Permission::firstOrCreate(['name' => 'edit-ib-board-auditor-doc-review']);
        // $ib_board_auditor_doc_review_delete = Permission::firstOrCreate(['name' => 'delete-ib-board-auditor-doc-review']);

        // if (!$admin->hasPermission($ib_board_auditor_doc_review_add)) {
        //     $admin_role->givePermissionTo($ib_board_auditor_doc_review_add);
        // }
        // if (!$admin->hasPermission($ib_board_auditor_doc_review_view)) {
        //     $admin_role->givePermissionTo($ib_board_auditor_doc_review_view);
        // }
        // if (!$admin->hasPermission($ib_board_auditor_doc_review_edit)) {
        //     $admin_role->givePermissionTo($ib_board_auditor_doc_review_edit);
        // }
        // if (!$admin->hasPermission($ib_board_auditor_doc_review_delete)) {
        //     $admin_role->givePermissionTo($ib_board_auditor_doc_review_delete);
        // }

        // $save_ib_board_auditor_doc_review_add    = Permission::firstOrCreate(['name' => 'add-save-ib-board-auditor-doc-review']);
        // $save_ib_board_auditor_doc_review_view   = Permission::firstOrCreate(['name' => 'view-save-ib-board-auditor-doc-review']);
        // $save_ib_board_auditor_doc_review_edit   = Permission::firstOrCreate(['name' => 'edit-save-ib-board-auditor-doc-review']);
        // $save_ib_board_auditor_doc_review_delete = Permission::firstOrCreate(['name' => 'delete-save-ib-board-auditor-doc-review']);

        // if (!$admin->hasPermission($save_ib_board_auditor_doc_review_add)) {
        //     $admin_role->givePermissionTo($save_ib_board_auditor_doc_review_add);
        // }
        // if (!$admin->hasPermission($save_ib_board_auditor_doc_review_view)) {
        //     $admin_role->givePermissionTo($save_ib_board_auditor_doc_review_view);
        // }
        // if (!$admin->hasPermission($save_ib_board_auditor_doc_review_edit)) {
        //     $admin_role->givePermissionTo($save_ib_board_auditor_doc_review_edit);
        // }
        // if (!$admin->hasPermission($save_ib_board_auditor_doc_review_delete)) {
        //     $admin_role->givePermissionTo($save_ib_board_auditor_doc_review_delete);
        // }

        return "done";

    }
}
