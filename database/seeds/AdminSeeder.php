<?php

use Faker\Factory;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Permission;

class AdminSeeder extends DatabaseSeeder
{

    public function run()
    {

        $admin = User::where('reg_email','=','admin@admin.com')->withTrashed()->first();

        if($admin == null){
            $admin = new User();
            $admin->reg_email = 'admin@admin.com';
            $admin->reg_fname = 'Admin';
            $admin->reg_lname = 'TISI';
            $admin->reg_pword = md5("1234");
            $admin->reg_ip = '127.0.0.1';
            $admin->reg_13ID = '';
            $admin->reg_intital = '';
            $admin->reg_subdepart = '';
            $admin->reg_pis = '';
            $admin->reg_phone = '';
            $admin->reg_wphone = '';
            $admin->reg_uname = '';
            $admin->reg_unmd5 = '';
            $admin->reg_fileName = '';
            $admin->reg_update = date('Y-m-d');
            $admin->save();
        }

        if($admin->profile == null){
            $profile = new \App\Profile();
            $profile->user_runrecno = $admin->runrecno;
            $profile->save();
        }

        //Creating Roles
        $admin_role = Role::firstOrcreate(['name' => 'admin']);
        if(is_null($admin_role->label)){
          $admin_role->label = 'staff';
          $admin_role->save();
        }
        $permission = Permission::firstOrcreate(['name' => 'All Permission']);

        if(!$admin->hasRole('admin')){
            $admin->assignRole('admin');
            $admin_role->givePermissionTo($permission);
        }

        //Assigning default permissions to Admin
        $blog_add = Permission::firstOrCreate([
            'name' => 'add-blog'
        ]);
        $blog_view = Permission::firstOrCreate([
            'name' => 'view-blog'
        ]);
        $blog_edit = Permission::firstOrCreate([
            'name' => 'edit-blog'
        ]);
        $blog_delete = Permission::firstOrCreate([
            'name' => 'delete-blog'
        ]);

        if(!$admin->hasPermission($blog_add)){
            $admin_role->givePermissionTo($blog_add);
        }

        if(!$admin->hasPermission($blog_view)) {
            $admin_role->givePermissionTo($blog_view);
        }

        if(!$admin->hasPermission($blog_edit)) {
            $admin_role->givePermissionTo($blog_edit);
        }

        if(!$admin->hasPermission($blog_delete)) {
            $admin_role->givePermissionTo($blog_delete);
        }

        $blog_category_add = Permission::firstOrCreate([
            'name' => 'add-blog-category'
        ]);
        $blog_category_view = Permission::firstOrCreate([
            'name' => 'view-blog-category'
        ]);
        $blog_category_edit = Permission::firstOrCreate([
            'name' => 'edit-blog-category'
        ]);
        $blog_category_delete = Permission::firstOrCreate([
            'name' => 'delete-blog-category'
        ]);

        if(!$admin->hasPermission($blog_category_add)) {
            $admin_role->givePermissionTo($blog_category_add);
        }
        if(!$admin->hasPermission($blog_category_view)) {
            $admin_role->givePermissionTo($blog_category_view);
        }
        if(!$admin->hasPermission($blog_category_edit)) {
            $admin_role->givePermissionTo($blog_category_edit);
        }
        if(!$admin->hasPermission($blog_category_delete)) {
            $admin_role->givePermissionTo($blog_category_delete);
        }

        //จังหวัด
        $province_add = Permission::firstOrCreate([
            'name' => 'add-province'
        ]);
        $province_view = Permission::firstOrCreate([
            'name' => 'view-province'
        ]);
        $province_edit = Permission::firstOrCreate([
            'name' => 'edit-province'
        ]);
        $province_delete = Permission::firstOrCreate([
            'name' => 'delete-province'
        ]);

        if(!$admin->hasPermission($province_add)) {
            $admin_role->givePermissionTo($province_add);
        }
        if(!$admin->hasPermission($province_view)) {
            $admin_role->givePermissionTo($province_view);
        }
        if(!$admin->hasPermission($province_edit)) {
            $admin_role->givePermissionTo($province_edit);
        }
        if(!$admin->hasPermission($province_delete)) {
            $admin_role->givePermissionTo($province_delete);
        }

        //ชื่อกลุ่มผลิตภัณฑ์สาขา
        $product_group_add = Permission::firstOrCreate([
            'name' => 'add-product-group'
        ]);
        $product_group_view = Permission::firstOrCreate([
            'name' => 'view-product-group'
        ]);
        $product_group_edit = Permission::firstOrCreate([
            'name' => 'edit-product-group'
        ]);
        $product_group_delete = Permission::firstOrCreate([
            'name' => 'delete-product-group'
        ]);

        if(!$admin->hasPermission($product_group_add)) {
            $admin_role->givePermissionTo($product_group_add);
        }
        if(!$admin->hasPermission($product_group_view)) {
            $admin_role->givePermissionTo($product_group_view);
        }
        if(!$admin->hasPermission($product_group_edit)) {
            $admin_role->givePermissionTo($product_group_edit);
        }
        if(!$admin->hasPermission($product_group_delete)) {
            $admin_role->givePermissionTo($product_group_delete);
        }

        //ประเภทมาตรฐาน
        $standard_type_add = Permission::firstOrCreate([
            'name' => 'add-standard-type'
        ]);
        $standard_type_view = Permission::firstOrCreate([
            'name' => 'view-standard-type'
        ]);
        $standard_type_edit = Permission::firstOrCreate([
            'name' => 'edit-standard-type'
        ]);
        $standard_type_delete = Permission::firstOrCreate([
            'name' => 'delete-standard-type'
        ]);

        if(!$admin->hasPermission($standard_type_add)) {
            $admin_role->givePermissionTo($standard_type_add);
        }
        if(!$admin->hasPermission($standard_type_view)) {
            $admin_role->givePermissionTo($standard_type_view);
        }
        if(!$admin->hasPermission($standard_type_edit)) {
            $admin_role->givePermissionTo($standard_type_edit);
        }
        if(!$admin->hasPermission($standard_type_delete)) {
            $admin_role->givePermissionTo($standard_type_delete);
        }

        //หมวดหมู่
        $cluster_add = Permission::firstOrCreate([
            'name' => 'add-cluster'
        ]);
        $cluster_view = Permission::firstOrCreate([
            'name' => 'view-cluster'
        ]);
        $cluster_edit = Permission::firstOrCreate([
            'name' => 'edit-cluster'
        ]);
        $cluster_delete = Permission::firstOrCreate([
            'name' => 'delete-cluster'
        ]);

        if(!$admin->hasPermission($cluster_add)) {
            $admin_role->givePermissionTo($cluster_add);
        }
        if(!$admin->hasPermission($cluster_view)) {
            $admin_role->givePermissionTo($cluster_view);
        }
        if(!$admin->hasPermission($cluster_edit)) {
            $admin_role->givePermissionTo($cluster_edit);
        }
        if(!$admin->hasPermission($cluster_delete)) {
            $admin_role->givePermissionTo($cluster_delete);
        }

        //วิธีจัดทำ
        $method_add = Permission::firstOrCreate([
            'name' => 'add-method'
        ]);
        $method_view = Permission::firstOrCreate([
            'name' => 'view-method'
        ]);
        $method_edit = Permission::firstOrCreate([
            'name' => 'edit-method'
        ]);
        $method_delete = Permission::firstOrCreate([
            'name' => 'delete-method'
        ]);

        if(!$admin->hasPermission($method_add)) {
            $admin_role->givePermissionTo($method_add);
        }
        if(!$admin->hasPermission($method_view)) {
            $admin_role->givePermissionTo($method_view);
        }
        if(!$admin->hasPermission($method_edit)) {
            $admin_role->givePermissionTo($method_edit);
        }
        if(!$admin->hasPermission($method_delete)) {
            $admin_role->givePermissionTo($method_delete);
        }

        //อุตสาหกรรมเป้าหมาย
        $industry_target_add = Permission::firstOrCreate([
            'name' => 'add-industry-target'
        ]);
        $industry_target_view = Permission::firstOrCreate([
            'name' => 'view-industry-target'
        ]);
        $industry_target_edit = Permission::firstOrCreate([
            'name' => 'edit-industry-target'
        ]);
        $industry_target_delete = Permission::firstOrCreate([
            'name' => 'delete-industry-target'
        ]);

        if(!$admin->hasPermission($industry_target_add)) {
            $admin_role->givePermissionTo($industry_target_add);
        }
        if(!$admin->hasPermission($industry_target_view)) {
            $admin_role->givePermissionTo($industry_target_view);
        }
        if(!$admin->hasPermission($industry_target_edit)) {
            $admin_role->givePermissionTo($industry_target_edit);
        }
        if(!$admin->hasPermission($industry_target_delete)) {
            $admin_role->givePermissionTo($industry_target_delete);
        }

        //สถานะการดำเนินงาน
        $status_operation_add = Permission::firstOrCreate([
            'name' => 'add-status-operation'
        ]);
        $status_operation_view = Permission::firstOrCreate([
            'name' => 'view-status-operation'
        ]);
        $status_operation_edit = Permission::firstOrCreate([
            'name' => 'edit-status-operation'
        ]);
        $status_operation_delete = Permission::firstOrCreate([
            'name' => 'delete-status-operation'
        ]);

        if(!$admin->hasPermission($status_operation_add)) {
            $admin_role->givePermissionTo($status_operation_add);
        }
        if(!$admin->hasPermission($status_operation_view)) {
            $admin_role->givePermissionTo($status_operation_view);
        }
        if(!$admin->hasPermission($status_operation_edit)) {
            $admin_role->givePermissionTo($status_operation_edit);
        }
        if(!$admin->hasPermission($status_operation_delete)) {
            $admin_role->givePermissionTo($status_operation_delete);
        }

        //ประเภทของคณะกรรมการ
        $board_type_add = Permission::firstOrCreate([
            'name' => 'add-board-type'
        ]);
        $board_type_view = Permission::firstOrCreate([
            'name' => 'view-board-type'
        ]);
        $board_type_edit = Permission::firstOrCreate([
            'name' => 'edit-board-type'
        ]);
        $board_type_delete = Permission::firstOrCreate([
            'name' => 'delete-board-type'
        ]);

        if(!$admin->hasPermission($board_type_add)) {
            $admin_role->givePermissionTo($board_type_add);
        }
        if(!$admin->hasPermission($board_type_view)) {
            $admin_role->givePermissionTo($board_type_view);
        }
        if(!$admin->hasPermission($board_type_edit)) {
            $admin_role->givePermissionTo($board_type_edit);
        }
        if(!$admin->hasPermission($board_type_delete)) {
            $admin_role->givePermissionTo($board_type_delete);
        }

        //รูปแบบมาตรฐาน
        $standard_format_add = Permission::firstOrCreate([
            'name' => 'add-standard-format'
        ]);
        $standard_format_view = Permission::firstOrCreate([
            'name' => 'view-standard-format'
        ]);
        $standard_format_edit = Permission::firstOrCreate([
            'name' => 'edit-standard-format'
        ]);
        $standard_format_delete = Permission::firstOrCreate([
            'name' => 'delete-standard-format'
        ]);

        if(!$admin->hasPermission($standard_format_add)) {
            $admin_role->givePermissionTo($standard_format_add);
        }
        if(!$admin->hasPermission($standard_format_view)) {
            $admin_role->givePermissionTo($standard_format_view);
        }
        if(!$admin->hasPermission($standard_format_edit)) {
            $admin_role->givePermissionTo($standard_format_edit);
        }
        if(!$admin->hasPermission($standard_format_delete)) {
            $admin_role->givePermissionTo($standard_format_delete);
        }

        //รูปแบบการกำหนดมาตรฐาน
        $set_format_add = Permission::firstOrCreate([
            'name' => 'add-set-format'
        ]);
        $set_format_view = Permission::firstOrCreate([
            'name' => 'view-set-format'
        ]);
        $set_format_edit = Permission::firstOrCreate([
            'name' => 'edit-set-format'
        ]);
        $set_format_delete = Permission::firstOrCreate([
            'name' => 'delete-set-format'
        ]);

        if(!$admin->hasPermission($set_format_add)) {
            $admin_role->givePermissionTo($set_format_add);
        }
        if(!$admin->hasPermission($set_format_view)) {
            $admin_role->givePermissionTo($set_format_view);
        }
        if(!$admin->hasPermission($set_format_edit)) {
            $admin_role->givePermissionTo($set_format_edit);
        }
        if(!$admin->hasPermission($set_format_delete)) {
            $admin_role->givePermissionTo($set_format_delete);
        }

        //ตั้งค่าวาระ
        $config_term_add = Permission::firstOrCreate([
            'name' => 'add-config-term'
        ]);
        $config_term_view = Permission::firstOrCreate([
            'name' => 'view-config-term'
        ]);
        $config_term_edit = Permission::firstOrCreate([
            'name' => 'edit-config-term'
        ]);
        $config_term_delete = Permission::firstOrCreate([
            'name' => 'delete-config-term'
        ]);

        if(!$admin->hasPermission($config_term_add)) {
            $admin_role->givePermissionTo($config_term_add);
        }
        if(!$admin->hasPermission($config_term_view)) {
            $admin_role->givePermissionTo($config_term_view);
        }
        if(!$admin->hasPermission($config_term_edit)) {
            $admin_role->givePermissionTo($config_term_edit);
        }
        if(!$admin->hasPermission($config_term_delete)) {
            $admin_role->givePermissionTo($config_term_delete);
        }

        //หน่วยงาน
        $department_add = Permission::firstOrCreate([
            'name' => 'add-department'
        ]);
        $department_view = Permission::firstOrCreate([
            'name' => 'view-department'
        ]);
        $department_edit = Permission::firstOrCreate([
            'name' => 'edit-department'
        ]);
        $department_delete = Permission::firstOrCreate([
            'name' => 'delete-department'
        ]);

        if(!$admin->hasPermission($department_add)) {
            $admin_role->givePermissionTo($department_add);
        }
        if(!$admin->hasPermission($department_view)) {
            $admin_role->givePermissionTo($department_view);
        }
        if(!$admin->hasPermission($department_edit)) {
            $admin_role->givePermissionTo($department_edit);
        }
        if(!$admin->hasPermission($department_delete)) {
            $admin_role->givePermissionTo($department_delete);
        }

        //ภาค
        $geography_add = Permission::firstOrCreate([
            'name' => 'add-geography'
        ]);
        $geography_view = Permission::firstOrCreate([
            'name' => 'view-geography'
        ]);
        $geography_edit = Permission::firstOrCreate([
            'name' => 'edit-geography'
        ]);
        $geography_delete = Permission::firstOrCreate([
            'name' => 'delete-geography'
        ]);

        if(!$admin->hasPermission($geography_add)) {
            $admin_role->givePermissionTo($geography_add);
        }
        if(!$admin->hasPermission($geography_view)) {
            $admin_role->givePermissionTo($geography_view);
        }
        if(!$admin->hasPermission($geography_edit)) {
            $admin_role->givePermissionTo($geography_edit);
        }
        if(!$admin->hasPermission($geography_delete)) {
            $admin_role->givePermissionTo($geography_delete);
        }

        //อำเภอ
        $amphur_add = Permission::firstOrCreate([
            'name' => 'add-amphur'
        ]);
        $amphur_view = Permission::firstOrCreate([
            'name' => 'view-amphur'
        ]);
        $amphur_edit = Permission::firstOrCreate([
            'name' => 'edit-amphur'
        ]);
        $amphur_delete = Permission::firstOrCreate([
            'name' => 'delete-amphur'
        ]);

        if(!$admin->hasPermission($amphur_add)) {
            $admin_role->givePermissionTo($amphur_add);
        }
        if(!$admin->hasPermission($amphur_view)) {
            $admin_role->givePermissionTo($amphur_view);
        }
        if(!$admin->hasPermission($amphur_edit)) {
            $admin_role->givePermissionTo($amphur_edit);
        }
        if(!$admin->hasPermission($amphur_delete)) {
            $admin_role->givePermissionTo($amphur_delete);
        }

        //ตำบล
        $district_add = Permission::firstOrCreate([
            'name' => 'add-district'
        ]);
        $district_view = Permission::firstOrCreate([
            'name' => 'view-district'
        ]);
        $district_edit = Permission::firstOrCreate([
            'name' => 'edit-district'
        ]);
        $district_delete = Permission::firstOrCreate([
            'name' => 'delete-district'
        ]);

        if(!$admin->hasPermission($district_add)) {
            $admin_role->givePermissionTo($district_add);
        }
        if(!$admin->hasPermission($district_view)) {
            $admin_role->givePermissionTo($district_view);
        }
        if(!$admin->hasPermission($district_edit)) {
            $admin_role->givePermissionTo($district_edit);
        }
        if(!$admin->hasPermission($district_delete)) {
            $admin_role->givePermissionTo($district_delete);
        }

        //กลุ่มเจ้าหน้าที่
        $staff_group_add = Permission::firstOrCreate([
            'name' => 'add-staff-group'
        ]);
        $staff_group_view = Permission::firstOrCreate([
            'name' => 'view-staff-group'
        ]);
        $staff_group_edit = Permission::firstOrCreate([
            'name' => 'edit-staff-group'
        ]);
        $staff_group_delete = Permission::firstOrCreate([
            'name' => 'delete-staff-group'
        ]);

        if(!$admin->hasPermission($staff_group_add)) {
            $admin_role->givePermissionTo($staff_group_add);
        }
        if(!$admin->hasPermission($staff_group_view)) {
            $admin_role->givePermissionTo($staff_group_view);
        }
        if(!$admin->hasPermission($staff_group_edit)) {
            $admin_role->givePermissionTo($staff_group_edit);
        }
        if(!$admin->hasPermission($staff_group_delete)) {
            $admin_role->givePermissionTo($staff_group_delete);
        }

        //ข้อมูลมาตรฐานมอก.
        $standard_add = Permission::firstOrCreate([
            'name' => 'add-standard'
        ]);
        $standard_view = Permission::firstOrCreate([
            'name' => 'view-standard'
        ]);
        $standard_edit = Permission::firstOrCreate([
            'name' => 'edit-standard'
        ]);
        $standard_delete = Permission::firstOrCreate([
            'name' => 'delete-standard'
        ]);

        if(!$admin->hasPermission($standard_add)) {
            $admin_role->givePermissionTo($standard_add);
        }
        if(!$admin->hasPermission($standard_view)) {
            $admin_role->givePermissionTo($standard_view);
        }
        if(!$admin->hasPermission($standard_edit)) {
            $admin_role->givePermissionTo($standard_edit);
        }
        if(!$admin->hasPermission($standard_delete)) {
            $admin_role->givePermissionTo($standard_delete);
        }

        //คณะกรรมการ
        $board_add = Permission::firstOrCreate([
            'name' => 'add-board'
        ]);
        $board_view = Permission::firstOrCreate([
            'name' => 'view-board'
        ]);
        $board_edit = Permission::firstOrCreate([
            'name' => 'edit-board'
        ]);
        $board_delete = Permission::firstOrCreate([
            'name' => 'delete-board'
        ]);

        if(!$admin->hasPermission($board_add)) {
            $admin_role->givePermissionTo($board_add);
        }
        if(!$admin->hasPermission($board_view)) {
            $admin_role->givePermissionTo($board_view);
        }
        if(!$admin->hasPermission($board_edit)) {
            $admin_role->givePermissionTo($board_edit);
        }
        if(!$admin->hasPermission($board_delete)) {
            $admin_role->givePermissionTo($board_delete);
        }

        //แต่งตั้งคณะกรรมการ
        $appoint_add = Permission::firstOrCreate([
            'name' => 'add-appoint'
        ]);
        $appoint_view = Permission::firstOrCreate([
            'name' => 'view-appoint'
        ]);
        $appoint_edit = Permission::firstOrCreate([
            'name' => 'edit-appoint'
        ]);
        $appoint_delete = Permission::firstOrCreate([
            'name' => 'delete-appoint'
        ]);

        if(!$admin->hasPermission($appoint_add)) {
            $admin_role->givePermissionTo($appoint_add);
        }
        if(!$admin->hasPermission($appoint_view)) {
            $admin_role->givePermissionTo($appoint_view);
        }
        if(!$admin->hasPermission($appoint_edit)) {
            $admin_role->givePermissionTo($appoint_edit);
        }
        if(!$admin->hasPermission($appoint_delete)) {
            $admin_role->givePermissionTo($appoint_delete);
        }

        //มาตรฐาน (สก.)
        $formula_add = Permission::firstOrCreate([
            'name' => 'add-formula'
        ]);
        $formula_view = Permission::firstOrCreate([
            'name' => 'view-formula'
        ]);
        $formula_edit = Permission::firstOrCreate([
            'name' => 'edit-formula'
        ]);
        $formula_delete = Permission::firstOrCreate([
            'name' => 'delete-formula'
        ]);

        if(!$admin->hasPermission($formula_add)) {
            $admin_role->givePermissionTo($formula_add);
        }
        if(!$admin->hasPermission($formula_view)) {
            $admin_role->givePermissionTo($formula_view);
        }
        if(!$admin->hasPermission($formula_edit)) {
            $admin_role->givePermissionTo($formula_edit);
        }
        if(!$admin->hasPermission($formula_delete)) {
            $admin_role->givePermissionTo($formula_delete);
        }

        //ผู้ลงนาม (สก.)
        $signer_add = Permission::firstOrCreate([
            'name' => 'add-signer'
        ]);
        $signer_view = Permission::firstOrCreate([
            'name' => 'view-signer'
        ]);
        $signer_edit = Permission::firstOrCreate([
            'name' => 'edit-signer'
        ]);
        $signer_delete = Permission::firstOrCreate([
            'name' => 'delete-signer'
        ]);

        if(!$admin->hasPermission($signer_add)) {
            $admin_role->givePermissionTo($signer_add);
        }
        if(!$admin->hasPermission($signer_view)) {
            $admin_role->givePermissionTo($signer_view);
        }
        if(!$admin->hasPermission($signer_edit)) {
            $admin_role->givePermissionTo($signer_edit);
        }
        if(!$admin->hasPermission($signer_delete)) {
            $admin_role->givePermissionTo($signer_delete);
        }

        //สภาพห้องปฏิบัติการ (สก.)
        $lab_condition_add = Permission::firstOrCreate([
            'name' => 'add-lab-condition'
        ]);
        $lab_condition_view = Permission::firstOrCreate([
            'name' => 'view-lab-condition'
        ]);
        $lab_condition_edit = Permission::firstOrCreate([
            'name' => 'edit-lab-condition'
        ]);
        $lab_condition_delete = Permission::firstOrCreate([
            'name' => 'delete-lab-condition'
        ]);

        if(!$admin->hasPermission($lab_condition_add)) {
            $admin_role->givePermissionTo($lab_condition_add);
        }
        if(!$admin->hasPermission($lab_condition_view)) {
            $admin_role->givePermissionTo($lab_condition_view);
        }
        if(!$admin->hasPermission($lab_condition_edit)) {
            $admin_role->givePermissionTo($lab_condition_edit);
        }
        if(!$admin->hasPermission($lab_condition_delete)) {
            $admin_role->givePermissionTo($lab_condition_delete);
        }

        //สาขาการสอบเทียบ (สก.)
        $calibration_branch_add = Permission::firstOrCreate([
            'name' => 'add-calibration-branch'
        ]);
        $calibration_branch_view = Permission::firstOrCreate([
            'name' => 'view-calibration-branch'
        ]);
        $calibration_branch_edit = Permission::firstOrCreate([
            'name' => 'edit-calibration-branch'
        ]);
        $calibration_branch_delete = Permission::firstOrCreate([
            'name' => 'delete-calibration-branch'
        ]);

        if(!$admin->hasPermission($calibration_branch_add)) {
            $admin_role->givePermissionTo($calibration_branch_add);
        }
        if(!$admin->hasPermission($calibration_branch_view)) {
            $admin_role->givePermissionTo($calibration_branch_view);
        }
        if(!$admin->hasPermission($calibration_branch_edit)) {
            $admin_role->givePermissionTo($calibration_branch_edit);
        }
        if(!$admin->hasPermission($calibration_branch_delete)) {
            $admin_role->givePermissionTo($calibration_branch_delete);
        }

        //หมวดหมู่รายการสอบเทียบ (สก.)
        $calibration_group_add = Permission::firstOrCreate([
            'name' => 'add-calibration-group'
        ]);
        $calibration_group_view = Permission::firstOrCreate([
            'name' => 'view-calibration-group'
        ]);
        $calibration_group_edit = Permission::firstOrCreate([
            'name' => 'edit-calibration-group'
        ]);
        $calibration_group_delete = Permission::firstOrCreate([
            'name' => 'delete-calibration-group'
        ]);

        if(!$admin->hasPermission($calibration_group_add)) {
            $admin_role->givePermissionTo($calibration_group_add);
        }
        if(!$admin->hasPermission($calibration_group_view)) {
            $admin_role->givePermissionTo($calibration_group_view);
        }
        if(!$admin->hasPermission($calibration_group_edit)) {
            $admin_role->givePermissionTo($calibration_group_edit);
        }
        if(!$admin->hasPermission($calibration_group_delete)) {
            $admin_role->givePermissionTo($calibration_group_delete);
        }

        //รายการสอบเทียบ (สก.)
        $calibration_item_add = Permission::firstOrCreate([
            'name' => 'add-calibration-item'
        ]);
        $calibration_item_view = Permission::firstOrCreate([
            'name' => 'view-calibration-item'
        ]);
        $calibration_item_edit = Permission::firstOrCreate([
            'name' => 'edit-calibration-item'
        ]);
        $calibration_item_delete = Permission::firstOrCreate([
            'name' => 'delete-calibration-item'
        ]);

        if(!$admin->hasPermission($calibration_item_add)) {
            $admin_role->givePermissionTo($calibration_item_add);
        }
        if(!$admin->hasPermission($calibration_item_view)) {
            $admin_role->givePermissionTo($calibration_item_view);
        }
        if(!$admin->hasPermission($calibration_item_edit)) {
            $admin_role->givePermissionTo($calibration_item_edit);
        }
        if(!$admin->hasPermission($calibration_item_delete)) {
            $admin_role->givePermissionTo($calibration_item_delete);
        }

        //สาขาการทดสอบ (สก.)
        $test_branch_add = Permission::firstOrCreate([
            'name' => 'add-test-branch'
        ]);
        $test_branch_view = Permission::firstOrCreate([
            'name' => 'view-test-branch'
        ]);
        $test_branch_edit = Permission::firstOrCreate([
            'name' => 'edit-test-branch'
        ]);
        $test_branch_delete = Permission::firstOrCreate([
            'name' => 'delete-test-branch'
        ]);

        if(!$admin->hasPermission($test_branch_add)) {
            $admin_role->givePermissionTo($test_branch_add);
        }
        if(!$admin->hasPermission($test_branch_view)) {
            $admin_role->givePermissionTo($test_branch_view);
        }
        if(!$admin->hasPermission($test_branch_edit)) {
            $admin_role->givePermissionTo($test_branch_edit);
        }
        if(!$admin->hasPermission($test_branch_delete)) {
            $admin_role->givePermissionTo($test_branch_delete);
        }

        //หมวดหมู่ผลิตภัณฑ์ (สก.)
        $product_category_add = Permission::firstOrCreate([
            'name' => 'add-product-category'
        ]);
        $product_category_view = Permission::firstOrCreate([
            'name' => 'view-product-category'
        ]);
        $product_category_edit = Permission::firstOrCreate([
            'name' => 'edit-product-category'
        ]);
        $product_category_delete = Permission::firstOrCreate([
            'name' => 'delete-product-category'
        ]);

        if(!$admin->hasPermission($product_category_add)) {
            $admin_role->givePermissionTo($product_category_add);
        }
        if(!$admin->hasPermission($product_category_view)) {
            $admin_role->givePermissionTo($product_category_view);
        }
        if(!$admin->hasPermission($product_category_edit)) {
            $admin_role->givePermissionTo($product_category_edit);
        }
        if(!$admin->hasPermission($product_category_delete)) {
            $admin_role->givePermissionTo($product_category_delete);
        }

        //รายการผลิตภัณฑ์ (สก.)
        $product_item_add = Permission::firstOrCreate([
            'name' => 'add-product-item'
        ]);
        $product_item_view = Permission::firstOrCreate([
            'name' => 'view-product-item'
        ]);
        $product_item_edit = Permission::firstOrCreate([
            'name' => 'edit-product-item'
        ]);
        $product_item_delete = Permission::firstOrCreate([
            'name' => 'delete-product-item'
        ]);

        if(!$admin->hasPermission($product_item_add)) {
            $admin_role->givePermissionTo($product_item_add);
        }
        if(!$admin->hasPermission($product_item_view)) {
            $admin_role->givePermissionTo($product_item_view);
        }
        if(!$admin->hasPermission($product_item_edit)) {
            $admin_role->givePermissionTo($product_item_edit);
        }
        if(!$admin->hasPermission($product_item_delete)) {
            $admin_role->givePermissionTo($product_item_delete);
        }

        //รายการทดสอบ (สก.)
        $test_item_add = Permission::firstOrCreate([
            'name' => 'add-test-item'
        ]);
        $test_item_view = Permission::firstOrCreate([
            'name' => 'view-test-item'
        ]);
        $test_item_edit = Permission::firstOrCreate([
            'name' => 'edit-test-item'
        ]);
        $test_item_delete = Permission::firstOrCreate([
            'name' => 'delete-test-item'
        ]);

        if(!$admin->hasPermission($test_item_add)) {
            $admin_role->givePermissionTo($test_item_add);
        }
        if(!$admin->hasPermission($test_item_view)) {
            $admin_role->givePermissionTo($test_item_view);
        }
        if(!$admin->hasPermission($test_item_edit)) {
            $admin_role->givePermissionTo($test_item_edit);
        }
        if(!$admin->hasPermission($test_item_delete)) {
            $admin_role->givePermissionTo($test_item_delete);
        }

        //ประเภทการตรวจ (IB) (สก.)
        $inspect_type_add = Permission::firstOrCreate([
            'name' => 'add-inspect-type'
        ]);
        $inspect_type_view = Permission::firstOrCreate([
            'name' => 'view-inspect-type'
        ]);
        $inspect_type_edit = Permission::firstOrCreate([
            'name' => 'edit-inspect-type'
        ]);
        $inspect_type_delete = Permission::firstOrCreate([
            'name' => 'delete-inspect-type'
        ]);

        if(!$admin->hasPermission($inspect_type_add)) {
            $admin_role->givePermissionTo($inspect_type_add);
        }
        if(!$admin->hasPermission($inspect_type_view)) {
            $admin_role->givePermissionTo($inspect_type_view);
        }
        if(!$admin->hasPermission($inspect_type_edit)) {
            $admin_role->givePermissionTo($inspect_type_edit);
        }
        if(!$admin->hasPermission($inspect_type_delete)) {
            $admin_role->givePermissionTo($inspect_type_delete);
        }

        //หมวดหมู่การตรวจ (สก.)
        $inspect_category_add = Permission::firstOrCreate([
            'name' => 'add-inspect-category'
        ]);
        $inspect_category_view = Permission::firstOrCreate([
            'name' => 'view-inspect-category'
        ]);
        $inspect_category_edit = Permission::firstOrCreate([
            'name' => 'edit-inspect-category'
        ]);
        $inspect_category_delete = Permission::firstOrCreate([
            'name' => 'delete-inspect-category'
        ]);

        if(!$admin->hasPermission($inspect_category_add)) {
            $admin_role->givePermissionTo($inspect_category_add);
        }
        if(!$admin->hasPermission($inspect_category_view)) {
            $admin_role->givePermissionTo($inspect_category_view);
        }
        if(!$admin->hasPermission($inspect_category_edit)) {
            $admin_role->givePermissionTo($inspect_category_edit);
        }
        if(!$admin->hasPermission($inspect_category_delete)) {
            $admin_role->givePermissionTo($inspect_category_delete);
        }

        //หมวดหมู่การตรวจ (สก.)
        $inspect_branch_add = Permission::firstOrCreate([
            'name' => 'add-inspect-branch'
        ]);
        $inspect_branch_view = Permission::firstOrCreate([
            'name' => 'view-inspect-branch'
        ]);
        $inspect_branch_edit = Permission::firstOrCreate([
            'name' => 'edit-inspect-branch'
        ]);
        $inspect_branch_delete = Permission::firstOrCreate([
            'name' => 'delete-inspect-branch'
        ]);

        if(!$admin->hasPermission($inspect_branch_add)) {
            $admin_role->givePermissionTo($inspect_branch_add);
        }
        if(!$admin->hasPermission($inspect_branch_view)) {
            $admin_role->givePermissionTo($inspect_branch_view);
        }
        if(!$admin->hasPermission($inspect_branch_edit)) {
            $admin_role->givePermissionTo($inspect_branch_edit);
        }
        if(!$admin->hasPermission($inspect_branch_delete)) {
            $admin_role->givePermissionTo($inspect_branch_delete);
        }

        //ชนิดและช่วงการตรวจ (สก.)
        $inspect_kind_add = Permission::firstOrCreate([
            'name' => 'add-inspect-kind'
        ]);
        $inspect_kind_view = Permission::firstOrCreate([
            'name' => 'view-inspect-kind'
        ]);
        $inspect_kind_edit = Permission::firstOrCreate([
            'name' => 'edit-inspect-kind'
        ]);
        $inspect_kind_delete = Permission::firstOrCreate([
            'name' => 'delete-inspect-kind'
        ]);

        if(!$admin->hasPermission($inspect_kind_add)) {
            $admin_role->givePermissionTo($inspect_kind_add);
        }
        if(!$admin->hasPermission($inspect_kind_view)) {
            $admin_role->givePermissionTo($inspect_kind_view);
        }
        if(!$admin->hasPermission($inspect_kind_edit)) {
            $admin_role->givePermissionTo($inspect_kind_edit);
        }
        if(!$admin->hasPermission($inspect_kind_delete)) {
            $admin_role->givePermissionTo($inspect_kind_delete);
        }

        //สาขาการรับรอง (CB) (สก.)
        $certification_branch_add = Permission::firstOrCreate([
            'name' => 'add-certification-branch'
        ]);
        $certification_branch_view = Permission::firstOrCreate([
            'name' => 'view-certification-branch'
        ]);
        $certification_branch_edit = Permission::firstOrCreate([
            'name' => 'edit-certification-branch'
        ]);
        $certification_branch_delete = Permission::firstOrCreate([
            'name' => 'delete-certification-branch'
        ]);

        if(!$admin->hasPermission($certification_branch_add)) {
            $admin_role->givePermissionTo($certification_branch_add);
        }
        if(!$admin->hasPermission($certification_branch_view)) {
            $admin_role->givePermissionTo($certification_branch_view);
        }
        if(!$admin->hasPermission($certification_branch_edit)) {
            $admin_role->givePermissionTo($certification_branch_edit);
        }
        if(!$admin->hasPermission($certification_branch_delete)) {
            $admin_role->givePermissionTo($certification_branch_delete);
        }

        //ประเภทอุตสาหกรรม (ISIC) (สก.)
        $industry_type_add = Permission::firstOrCreate([
            'name' => 'add-industry-type'
        ]);
        $industry_type_view = Permission::firstOrCreate([
            'name' => 'view-industry-type'
        ]);
        $industry_type_edit = Permission::firstOrCreate([
            'name' => 'edit-industry-type'
        ]);
        $industry_type_delete = Permission::firstOrCreate([
            'name' => 'delete-industry-type'
        ]);

        if(!$admin->hasPermission($industry_type_add)) {
            $admin_role->givePermissionTo($industry_type_add);
        }
        if(!$admin->hasPermission($industry_type_view)) {
            $admin_role->givePermissionTo($industry_type_view);
        }
        if(!$admin->hasPermission($industry_type_edit)) {
            $admin_role->givePermissionTo($industry_type_edit);
        }
        if(!$admin->hasPermission($industry_type_delete)) {
            $admin_role->givePermissionTo($industry_type_delete);
        }

        //IAF (สก.)
        $iaf_add = Permission::firstOrCreate([
            'name' => 'add-iaf'
        ]);
        $iaf_view = Permission::firstOrCreate([
            'name' => 'view-iaf'
        ]);
        $iaf_edit = Permission::firstOrCreate([
            'name' => 'edit-iaf'
        ]);
        $iaf_delete = Permission::firstOrCreate([
            'name' => 'delete-iaf'
        ]);

        if(!$admin->hasPermission($iaf_add)) {
            $admin_role->givePermissionTo($iaf_add);
        }
        if(!$admin->hasPermission($iaf_view)) {
            $admin_role->givePermissionTo($iaf_view);
        }
        if(!$admin->hasPermission($iaf_edit)) {
            $admin_role->givePermissionTo($iaf_edit);
        }
        if(!$admin->hasPermission($iaf_delete)) {
            $admin_role->givePermissionTo($iaf_delete);
        }

        //Enms (สก.)
        $enms_add = Permission::firstOrCreate([
            'name' => 'add-enms'
        ]);
        $enms_view = Permission::firstOrCreate([
            'name' => 'view-enms'
        ]);
        $enms_edit = Permission::firstOrCreate([
            'name' => 'edit-enms'
        ]);
        $enms_delete = Permission::firstOrCreate([
            'name' => 'delete-enms'
        ]);

        if(!$admin->hasPermission($enms_add)) {
            $admin_role->givePermissionTo($enms_add);
        }
        if(!$admin->hasPermission($enms_view)) {
            $admin_role->givePermissionTo($enms_view);
        }
        if(!$admin->hasPermission($enms_edit)) {
            $admin_role->givePermissionTo($enms_edit);
        }
        if(!$admin->hasPermission($enms_delete)) {
            $admin_role->givePermissionTo($enms_delete);
        }

        //GHG (สก.)
        $ghg_add = Permission::firstOrCreate([
            'name' => 'add-ghg'
        ]);
        $ghg_view = Permission::firstOrCreate([
            'name' => 'view-ghg'
        ]);
        $ghg_edit = Permission::firstOrCreate([
            'name' => 'edit-ghg'
        ]);
        $ghg_delete = Permission::firstOrCreate([
            'name' => 'delete-ghg'
        ]);

        if(!$admin->hasPermission($ghg_add)) {
            $admin_role->givePermissionTo($ghg_add);
        }
        if(!$admin->hasPermission($ghg_view)) {
            $admin_role->givePermissionTo($ghg_view);
        }
        if(!$admin->hasPermission($ghg_edit)) {
            $admin_role->givePermissionTo($ghg_edit);
        }
        if(!$admin->hasPermission($ghg_delete)) {
            $admin_role->givePermissionTo($ghg_delete);
        }

        //สถานะผู้ตรวจประเมิน (สก.)
        $status_auditor_add = Permission::firstOrCreate([
            'name' => 'add-status-auditor'
        ]);
        $status_auditor_view = Permission::firstOrCreate([
            'name' => 'view-status-auditor'
        ]);
        $status_auditor_edit = Permission::firstOrCreate([
            'name' => 'edit-status-auditor'
        ]);
        $status_auditor_delete = Permission::firstOrCreate([
            'name' => 'delete-status-auditor'
        ]);

        if(!$admin->hasPermission($status_auditor_add)) {
            $admin_role->givePermissionTo($status_auditor_add);
        }
        if(!$admin->hasPermission($status_auditor_view)) {
            $admin_role->givePermissionTo($status_auditor_view);
        }
        if(!$admin->hasPermission($status_auditor_edit)) {
            $admin_role->givePermissionTo($status_auditor_edit);
        }
        if(!$admin->hasPermission($status_auditor_delete)) {
            $admin_role->givePermissionTo($status_auditor_delete);
        }

        //สถานะการดำเนินงาน (สก.)
        $status_progress_add = Permission::firstOrCreate([
            'name' => 'add-status-progress'
        ]);
        $status_progress_view = Permission::firstOrCreate([
            'name' => 'view-status-progress'
        ]);
        $status_progress_edit = Permission::firstOrCreate([
            'name' => 'edit-status-progress'
        ]);
        $status_progress_delete = Permission::firstOrCreate([
            'name' => 'delete-status-progress'
        ]);

        if(!$admin->hasPermission($status_progress_add)) {
            $admin_role->givePermissionTo($status_progress_add);
        }
        if(!$admin->hasPermission($status_progress_view)) {
            $admin_role->givePermissionTo($status_progress_view);
        }
        if(!$admin->hasPermission($status_progress_edit)) {
            $admin_role->givePermissionTo($status_progress_edit);
        }
        if(!$admin->hasPermission($status_progress_delete)) {
            $admin_role->givePermissionTo($status_progress_delete);
        }

        //ตั้งค่าเอกสารแนบ (สก.)
        $config_attach_add = Permission::firstOrCreate([
            'name' => 'add-config-attach'
        ]);
        $config_attach_view = Permission::firstOrCreate([
            'name' => 'view-config-attach'
        ]);
        $config_attach_edit = Permission::firstOrCreate([
            'name' => 'edit-config-attach'
        ]);
        $config_attach_delete = Permission::firstOrCreate([
            'name' => 'delete-config-attach'
        ]);

        if(!$admin->hasPermission($config_attach_add)) {
            $admin_role->givePermissionTo($config_attach_add);
        }
        if(!$admin->hasPermission($config_attach_view)) {
            $admin_role->givePermissionTo($config_attach_view);
        }
        if(!$admin->hasPermission($config_attach_edit)) {
            $admin_role->givePermissionTo($config_attach_edit);
        }
        if(!$admin->hasPermission($config_attach_delete)) {
            $admin_role->givePermissionTo($config_attach_delete);
        }

        //ขอบข่ายการรับรอง (สก.)
        $certification_scope_add = Permission::firstOrCreate([
            'name' => 'add-certification-scope'
        ]);
        $certification_scope_view = Permission::firstOrCreate([
            'name' => 'view-certification-scope'
        ]);
        $certification_scope_edit = Permission::firstOrCreate([
            'name' => 'edit-certification-scope'
        ]);
        $certification_scope_delete = Permission::firstOrCreate([
            'name' => 'delete-certification-scope'
        ]);

        if(!$admin->hasPermission($certification_scope_add)) {
            $admin_role->givePermissionTo($certification_scope_add);
        }
        if(!$admin->hasPermission($certification_scope_view)) {
            $admin_role->givePermissionTo($certification_scope_view);
        }
        if(!$admin->hasPermission($certification_scope_edit)) {
            $admin_role->givePermissionTo($certification_scope_edit);
        }
        if(!$admin->hasPermission($certification_scope_delete)) {
            $admin_role->givePermissionTo($certification_scope_delete);
        }

        //จัดการข้อมูล Web Service
        $web_service_add = Permission::firstOrCreate([
            'name' => 'add-web-service'
        ]);
        $web_service_view = Permission::firstOrCreate([
            'name' => 'view-web-service'
        ]);
        $web_service_edit = Permission::firstOrCreate([
            'name' => 'edit-web-service'
        ]);
        $web_service_delete = Permission::firstOrCreate([
            'name' => 'delete-web-service'
        ]);

        if(!$admin->hasPermission($web_service_add)) {
            $admin_role->givePermissionTo($web_service_add);
        }
        if(!$admin->hasPermission($web_service_view)) {
            $admin_role->givePermissionTo($web_service_view);
        }
        if(!$admin->hasPermission($web_service_edit)) {
            $admin_role->givePermissionTo($web_service_edit);
        }
        if(!$admin->hasPermission($web_service_delete)) {
            $admin_role->givePermissionTo($web_service_delete);
        }

        //กำหนดมาตรฐาน
        $set_standard_add = Permission::firstOrCreate([
            'name' => 'add-set-standard'
        ]);
        $set_standard_view = Permission::firstOrCreate([
            'name' => 'view-set-standard'
        ]);
        $set_standard_edit = Permission::firstOrCreate([
            'name' => 'edit-set-standard'
        ]);
        $set_standard_delete = Permission::firstOrCreate([
            'name' => 'delete-set-standard'
        ]);

        if(!$admin->hasPermission($set_standard_add)) {
            $admin_role->givePermissionTo($set_standard_add);
        }
        if(!$admin->hasPermission($set_standard_view)) {
            $admin_role->givePermissionTo($set_standard_view);
        }
        if(!$admin->hasPermission($set_standard_edit)) {
            $admin_role->givePermissionTo($set_standard_edit);
        }
        if(!$admin->hasPermission($set_standard_delete)) {
            $admin_role->givePermissionTo($set_standard_delete);
        }

        //ตั้งค่าระบบ
        $config_add = Permission::firstOrCreate([
            'name' => 'add-config'
        ]);
        $config_view = Permission::firstOrCreate([
            'name' => 'view-config'
        ]);
        $config_edit = Permission::firstOrCreate([
            'name' => 'edit-config'
        ]);
        $config_delete = Permission::firstOrCreate([
            'name' => 'delete-config'
        ]);

        if(!$admin->hasPermission($config_add)) {
            $admin_role->givePermissionTo($config_add);
        }
        if(!$admin->hasPermission($config_view)) {
            $admin_role->givePermissionTo($config_view);
        }
        if(!$admin->hasPermission($config_edit)) {
            $admin_role->givePermissionTo($config_edit);
        }
        if(!$admin->hasPermission($config_delete)) {
            $admin_role->givePermissionTo($config_delete);
        }

        //ดูข้อมูลบริษัท
        $juristic_add = Permission::firstOrCreate([
            'name' => 'add-juristic'
        ]);
        $juristic_view = Permission::firstOrCreate([
            'name' => 'view-juristic'
        ]);
        $juristic_edit = Permission::firstOrCreate([
            'name' => 'edit-juristic'
        ]);
        $juristic_delete = Permission::firstOrCreate([
            'name' => 'delete-juristic'
        ]);

        if(!$admin->hasPermission($juristic_add)) {
            $admin_role->givePermissionTo($juristic_add);
        }
        if(!$admin->hasPermission($juristic_view)) {
            $admin_role->givePermissionTo($juristic_view);
        }
        if(!$admin->hasPermission($juristic_edit)) {
            $admin_role->givePermissionTo($juristic_edit);
        }
        if(!$admin->hasPermission($juristic_delete)) {
            $admin_role->givePermissionTo($juristic_delete);
        }

        //ดูข้อมูลบุคคล
        $personal_add = Permission::firstOrCreate([
            'name' => 'add-personal'
        ]);
        $personal_view = Permission::firstOrCreate([
            'name' => 'view-personal'
        ]);
        $personal_edit = Permission::firstOrCreate([
            'name' => 'edit-personal'
        ]);
        $personal_delete = Permission::firstOrCreate([
            'name' => 'delete-personal'
        ]);

        if(!$admin->hasPermission($personal_add)) {
            $admin_role->givePermissionTo($personal_add);
        }
        if(!$admin->hasPermission($personal_view)) {
            $admin_role->givePermissionTo($personal_view);
        }
        if(!$admin->hasPermission($personal_edit)) {
            $admin_role->givePermissionTo($personal_edit);
        }
        if(!$admin->hasPermission($personal_delete)) {
            $admin_role->givePermissionTo($personal_delete);
        }

        //ดูข้อมูลบริษัท
        $rd_vat_view = Permission::firstOrCreate([
            'name' => 'view-rd-vat'
        ]);
        if(!$admin->hasPermission($rd_vat_view)) {
            $admin_role->givePermissionTo($rd_vat_view);
        }

        //ดูข้อมูลโรงงาน
        $industry_view = Permission::firstOrCreate([
            'name' => 'view-industry'
        ]);
        if(!$admin->hasPermission($industry_view)) {
            $admin_role->givePermissionTo($industry_view);
        }

        //กิจกรรมของ GHG
        $ghg_activity_add = Permission::firstOrCreate([
            'name' => 'add-ghg-activity'
        ]);
        $ghg_activity_view = Permission::firstOrCreate([
            'name' => 'view-ghg-activity'
        ]);
        $ghg_activity_edit = Permission::firstOrCreate([
            'name' => 'edit-ghg-activity'
        ]);
        $ghg_activity_delete = Permission::firstOrCreate([
            'name' => 'delete-ghg-activity'
        ]);

        if(!$admin->hasPermission($ghg_activity_add)) {
            $admin_role->givePermissionTo($ghg_activity_add);
        }
        if(!$admin->hasPermission($ghg_activity_view)) {
            $admin_role->givePermissionTo($ghg_activity_view);
        }
        if(!$admin->hasPermission($ghg_activity_edit)) {
            $admin_role->givePermissionTo($ghg_activity_edit);
        }
        if(!$admin->hasPermission($ghg_activity_delete)) {
            $admin_role->givePermissionTo($ghg_activity_delete);
        }

        //ตั้งค่าหน่วยนับของมาตรฐาน
        $tis_unit_add = Permission::firstOrCreate([
            'name' => 'add-tis-unit'
        ]);
        $tis_unit_view = Permission::firstOrCreate([
            'name' => 'view-tis-unit'
        ]);
        $tis_unit_edit = Permission::firstOrCreate([
            'name' => 'edit-tis-unit'
        ]);
        $tis_unit_delete = Permission::firstOrCreate([
            'name' => 'delete-tis-unit'
        ]);

        if(!$admin->hasPermission($tis_unit_add)) {
            $admin_role->givePermissionTo($tis_unit_add);
        }
        if(!$admin->hasPermission($tis_unit_view)) {
            $admin_role->givePermissionTo($tis_unit_view);
        }
        if(!$admin->hasPermission($tis_unit_edit)) {
            $admin_role->givePermissionTo($tis_unit_edit);
        }
        if(!$admin->hasPermission($tis_unit_delete)) {
            $admin_role->givePermissionTo($tis_unit_delete);
        }

        //รับแจ้งปริมาณการผลิต
        $receive_volume_add = Permission::firstOrCreate([
            'name' => 'add-receive-volume'
        ]);
        $receive_volume_view = Permission::firstOrCreate([
            'name' => 'view-receive-volume'
        ]);
        $receive_volume_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-volume'
        ]);
        $receive_volume_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-volume'
        ]);

        if(!$admin->hasPermission($receive_volume_add)) {
            $admin_role->givePermissionTo($receive_volume_add);
        }
        if(!$admin->hasPermission($receive_volume_view)) {
            $admin_role->givePermissionTo($receive_volume_view);
        }
        if(!$admin->hasPermission($receive_volume_edit)) {
            $admin_role->givePermissionTo($receive_volume_edit);
        }
        if(!$admin->hasPermission($receive_volume_delete)) {
            $admin_role->givePermissionTo($receive_volume_delete);
        }

        //รับแจ้งการเปลี่ยนแปลงที่มีผลต่อคุณภาพ
        $receive_change_add = Permission::firstOrCreate([
            'name' => 'add-receive-change'
        ]);
        $receive_change_view = Permission::firstOrCreate([
            'name' => 'view-receive-change'
        ]);
        $receive_change_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-change'
        ]);
        $receive_change_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-change'
        ]);

        if(!$admin->hasPermission($receive_change_add)) {
            $admin_role->givePermissionTo($receive_change_add);
        }
        if(!$admin->hasPermission($receive_change_view)) {
            $admin_role->givePermissionTo($receive_change_view);
        }
        if(!$admin->hasPermission($receive_change_edit)) {
            $admin_role->givePermissionTo($receive_change_edit);
        }
        if(!$admin->hasPermission($receive_change_delete)) {
            $admin_role->givePermissionTo($receive_change_delete);
        }

        //รับแจ้งการเปลี่ยนแปลงที่มีผลต่อคุณภาพ
        $receive_quality_control_add = Permission::firstOrCreate([
            'name' => 'add-receive-quality-control'
        ]);
        $receive_quality_control_view = Permission::firstOrCreate([
            'name' => 'view-receive-quality-control'
        ]);
        $receive_quality_control_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-quality-control'
        ]);
        $receive_quality_control_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-quality-control'
        ]);

        if(!$admin->hasPermission($receive_quality_control_add)) {
            $admin_role->givePermissionTo($receive_quality_control_add);
        }
        if(!$admin->hasPermission($receive_quality_control_view)) {
            $admin_role->givePermissionTo($receive_quality_control_view);
        }
        if(!$admin->hasPermission($receive_quality_control_edit)) {
            $admin_role->givePermissionTo($receive_quality_control_edit);
        }
        if(!$admin->hasPermission($receive_quality_control_delete)) {
            $admin_role->givePermissionTo($receive_quality_control_delete);
        }

        //รับแจ้งผลการตรวจสอบผลิตภัณฑ์
        $receive_inspection_add = Permission::firstOrCreate([
            'name' => 'add-receive-inspection'
        ]);
        $receive_inspection_view = Permission::firstOrCreate([
            'name' => 'view-receive-inspection'
        ]);
        $receive_inspection_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-inspection'
        ]);
        $receive_inspection_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-inspection'
        ]);

        if(!$admin->hasPermission($receive_inspection_add)) {
            $admin_role->givePermissionTo($receive_inspection_add);
        }
        if(!$admin->hasPermission($receive_inspection_view)) {
            $admin_role->givePermissionTo($receive_inspection_view);
        }
        if(!$admin->hasPermission($receive_inspection_edit)) {
            $admin_role->givePermissionTo($receive_inspection_edit);
        }
        if(!$admin->hasPermission($receive_inspection_delete)) {
            $admin_role->givePermissionTo($receive_inspection_delete);
        }

        //รับแจ้งผลการตรวจสอบผลิตภัณฑ์
        $receive_calibrate_add = Permission::firstOrCreate([
            'name' => 'add-receive-calibrate'
        ]);
        $receive_calibrate_view = Permission::firstOrCreate([
            'name' => 'view-receive-calibrate'
        ]);
        $receive_calibrate_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-calibrate'
        ]);
        $receive_calibrate_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-calibrate'
        ]);

        if(!$admin->hasPermission($receive_calibrate_add)) {
            $admin_role->givePermissionTo($receive_calibrate_add);
        }
        if(!$admin->hasPermission($receive_calibrate_view)) {
            $admin_role->givePermissionTo($receive_calibrate_view);
        }
        if(!$admin->hasPermission($receive_calibrate_edit)) {
            $admin_role->givePermissionTo($receive_calibrate_edit);
        }
        if(!$admin->hasPermission($receive_calibrate_delete)) {
            $admin_role->givePermissionTo($receive_calibrate_delete);
        }

        //หน่วยงานตรวจ
        $inspector_add = Permission::firstOrCreate([
            'name' => 'add-inspector'
        ]);
        $inspector_view = Permission::firstOrCreate([
            'name' => 'view-inspector'
        ]);
        $inspector_edit = Permission::firstOrCreate([
            'name' => 'edit-inspector'
        ]);
        $inspector_delete = Permission::firstOrCreate([
            'name' => 'delete-inspector'
        ]);

        if(!$admin->hasPermission($inspector_add)) {
            $admin_role->givePermissionTo($inspector_add);
        }
        if(!$admin->hasPermission($inspector_view)) {
            $admin_role->givePermissionTo($inspector_view);
        }
        if(!$admin->hasPermission($inspector_edit)) {
            $admin_role->givePermissionTo($inspector_edit);
        }
        if(!$admin->hasPermission($inspector_delete)) {
            $admin_role->givePermissionTo($inspector_delete);
        }

        //รับเรื่องอื่นๆ
        $other_add = Permission::firstOrCreate([
            'name' => 'add-other'
        ]);
        $other_view = Permission::firstOrCreate([
            'name' => 'view-other'
        ]);
        $other_edit = Permission::firstOrCreate([
            'name' => 'edit-other'
        ]);
        $other_delete = Permission::firstOrCreate([
            'name' => 'delete-other'
        ]);

        if(!$admin->hasPermission($other_add)) {
            $admin_role->givePermissionTo($other_add);
        }
        if(!$admin->hasPermission($other_view)) {
            $admin_role->givePermissionTo($other_view);
        }
        if(!$admin->hasPermission($other_edit)) {
            $admin_role->givePermissionTo($other_edit);
        }
        if(!$admin->hasPermission($other_delete)) {
            $admin_role->givePermissionTo($other_delete);
        }

        //บันทึกยกเลิกใบอนุญาต
        $license_cancel_add = Permission::firstOrCreate([
            'name' => 'add-license-cancel'
        ]);
        $license_cancel_view = Permission::firstOrCreate([
            'name' => 'view-license-cancel'
        ]);
        $license_cancel_edit = Permission::firstOrCreate([
            'name' => 'edit-license-cancel'
        ]);
        $license_cancel_delete = Permission::firstOrCreate([
            'name' => 'delete-license-cancel'
        ]);

        if(!$admin->hasPermission($license_cancel_add)) {
            $admin_role->givePermissionTo($license_cancel_add);
        }
        if(!$admin->hasPermission($license_cancel_view)) {
            $admin_role->givePermissionTo($license_cancel_view);
        }
        if(!$admin->hasPermission($license_cancel_edit)) {
            $admin_role->givePermissionTo($license_cancel_edit);
        }
        if(!$admin->hasPermission($license_cancel_delete)) {
            $admin_role->givePermissionTo($license_cancel_delete);
        }

        //บันทึกตรวจติดตามผล
        $follow_up_add = Permission::firstOrCreate([
            'name' => 'add-follow-up'
        ]);
        $follow_up_view = Permission::firstOrCreate([
            'name' => 'view-follow-up'
        ]);
        $follow_up_edit = Permission::firstOrCreate([
            'name' => 'edit-follow-up'
        ]);
        $follow_up_delete = Permission::firstOrCreate([
            'name' => 'delete-follow-up'
        ]);
        $follow_up_other = Permission::firstOrCreate([
            'name' => 'other-follow-up'
        ]);
        $follow_up_poko_approve = Permission::firstOrCreate([
            'name' => 'poko_approve-follow-up'
        ]);

        if(!$admin->hasPermission($follow_up_add)) {
            $admin_role->givePermissionTo($follow_up_add);
        }
        if(!$admin->hasPermission($follow_up_view)) {
            $admin_role->givePermissionTo($follow_up_view);
        }
        if(!$admin->hasPermission($follow_up_edit)) {
            $admin_role->givePermissionTo($follow_up_edit);
        }
        if(!$admin->hasPermission($follow_up_delete)) {
            $admin_role->givePermissionTo($follow_up_delete);
        }
        if(!$admin->hasPermission($follow_up_other)) {
            $admin_role->givePermissionTo($follow_up_other);
        }
        if(!$admin->hasPermission($follow_up_poko_approve)) {
            $admin_role->givePermissionTo($follow_up_poko_approve);
        }

        //คณะกรรมการเฉพาะด้าน
        $committee_add = Permission::firstOrCreate([
            'name' => 'add-committee'
        ]);
        $committee_view = Permission::firstOrCreate([
            'name' => 'view-committee'
        ]);
        $committee_edit = Permission::firstOrCreate([
            'name' => 'edit-committee'
        ]);
        $committee_delete = Permission::firstOrCreate([
            'name' => 'delete-committee'
        ]);

        if(!$admin->hasPermission($committee_add)) {
            $admin_role->givePermissionTo($committee_add);
        }
        if(!$admin->hasPermission($committee_view)) {
            $admin_role->givePermissionTo($committee_view);
        }
        if(!$admin->hasPermission($committee_edit)) {
            $admin_role->givePermissionTo($committee_edit);
        }
        if(!$admin->hasPermission($committee_delete)) {
            $admin_role->givePermissionTo($committee_delete);
        }

        //คณะกรรมการเฉพาะด้าน
        $auditor_add = Permission::firstOrCreate([
            'name' => 'add-auditor'
        ]);
        $auditor_view = Permission::firstOrCreate([
            'name' => 'view-auditor'
        ]);
        $auditor_edit = Permission::firstOrCreate([
            'name' => 'edit-auditor'
        ]);
        $auditor_delete = Permission::firstOrCreate([
            'name' => 'delete-auditor'
        ]);

        if(!$admin->hasPermission($auditor_add)) {
            $admin_role->givePermissionTo($auditor_add);
        }
        if(!$admin->hasPermission($auditor_view)) {
            $admin_role->givePermissionTo($auditor_view);
        }
        if(!$admin->hasPermission($auditor_edit)) {
            $admin_role->givePermissionTo($auditor_edit);
        }
        if(!$admin->hasPermission($auditor_delete)) {
            $admin_role->givePermissionTo($auditor_delete);
        }

        //ใบรับรองระบบงาน
        $certificate_add = Permission::firstOrCreate([
            'name' => 'add-certificate'
        ]);
        $certificate_view = Permission::firstOrCreate([
            'name' => 'view-certificate'
        ]);
        $certificate_edit = Permission::firstOrCreate([
            'name' => 'edit-certificate'
        ]);
        $certificate_delete = Permission::firstOrCreate([
            'name' => 'delete-certificate'
        ]);

        if(!$admin->hasPermission($certificate_add)) {
            $admin_role->givePermissionTo($certificate_add);
        }
        if(!$admin->hasPermission($certificate_view)) {
            $admin_role->givePermissionTo($certificate_view);
        }
        if(!$admin->hasPermission($certificate_edit)) {
            $admin_role->givePermissionTo($certificate_edit);
        }
        if(!$admin->hasPermission($certificate_delete)) {
            $admin_role->givePermissionTo($certificate_delete);
        }

        //ใบรับรองระบบงาน
        $board_review_add = Permission::firstOrCreate([
            'name' => 'add-board-review'
        ]);
        $board_review_view = Permission::firstOrCreate([
            'name' => 'view-board-review'
        ]);
        $board_review_edit = Permission::firstOrCreate([
            'name' => 'edit-board-review'
        ]);
        $board_review_delete = Permission::firstOrCreate([
            'name' => 'delete-board-review'
        ]);

        if(!$admin->hasPermission($board_review_add)) {
            $admin_role->givePermissionTo($board_review_add);
        }
        if(!$admin->hasPermission($board_review_view)) {
            $admin_role->givePermissionTo($board_review_view);
        }
        if(!$admin->hasPermission($board_review_edit)) {
            $admin_role->givePermissionTo($board_review_edit);
        }
        if(!$admin->hasPermission($board_review_delete)) {
            $admin_role->givePermissionTo($board_review_delete);
        }

        //เผยแพร่/เวียนร่างมาตรฐาน
        $public_draft_add = Permission::firstOrCreate([
            'name' => 'add-public-draft'
        ]);
        $public_draft_view = Permission::firstOrCreate([
            'name' => 'view-public-draft'
        ]);
        $public_draft_edit = Permission::firstOrCreate([
            'name' => 'edit-public-draft'
        ]);
        $public_draft_delete = Permission::firstOrCreate([
            'name' => 'delete-public-draft'
        ]);

        if(!$admin->hasPermission($public_draft_add)) {
            $admin_role->givePermissionTo($public_draft_add);
        }
        if(!$admin->hasPermission($public_draft_view)) {
            $admin_role->givePermissionTo($public_draft_view);
        }
        if(!$admin->hasPermission($public_draft_edit)) {
            $admin_role->givePermissionTo($public_draft_edit);
        }
        if(!$admin->hasPermission($public_draft_delete)) {
            $admin_role->givePermissionTo($public_draft_delete);
        }

        //ระบบข้อมูลความคิดเห็นสาธารณะ
        $idea_public_add = Permission::firstOrCreate([
            'name' => 'add-idea-public'
        ]);
        $idea_public_view = Permission::firstOrCreate([
            'name' => 'view-idea-public'
        ]);
        $idea_public_edit = Permission::firstOrCreate([
            'name' => 'edit-idea-public'
        ]);
        $idea_public_delete = Permission::firstOrCreate([
            'name' => 'delete-idea-public'
        ]);

        if(!$admin->hasPermission($idea_public_add)) {
            $admin_role->givePermissionTo($idea_public_add);
        }
        if(!$admin->hasPermission($idea_public_view)) {
            $admin_role->givePermissionTo($idea_public_view);
        }
        if(!$admin->hasPermission($idea_public_edit)) {
            $admin_role->givePermissionTo($idea_public_edit);
        }
        if(!$admin->hasPermission($idea_public_delete)) {
            $admin_role->givePermissionTo($idea_public_delete);
      }

      //ระบบรายงานข้อคิดเห็นต่อร่างมาตรฐาน
        $report_comment_add = Permission::firstOrCreate([
            'name' => 'add-report-comment'
        ]);
        $report_comment_view = Permission::firstOrCreate([
            'name' => 'view-report-comment'
        ]);
        $report_comment_edit = Permission::firstOrCreate([
            'name' => 'edit-report-comment'
        ]);
        $report_comment_delete = Permission::firstOrCreate([
            'name' => 'delete-report-comment'
        ]);

        if(!$admin->hasPermission($report_comment_add)) {
            $admin_role->givePermissionTo($report_comment_add);
        }
        if(!$admin->hasPermission($report_comment_view)) {
            $admin_role->givePermissionTo($report_comment_view);
        }
        if(!$admin->hasPermission($report_comment_edit)) {
            $admin_role->givePermissionTo($report_comment_edit);
        }
        if(!$admin->hasPermission($report_comment_delete)) {
            $admin_role->givePermissionTo($report_comment_delete);
        }

      //จัดการผู้ใช้งานเจ้าหน้าที่ในระบบนี้
        $user_add = Permission::firstOrCreate([
            'name' => 'add-user'
        ]);
        $user_view = Permission::firstOrCreate([
            'name' => 'view-user'
        ]);
        $user_edit = Permission::firstOrCreate([
            'name' => 'edit-user'
        ]);
        $user_delete = Permission::firstOrCreate([
            'name' => 'delete-user'
        ]);

        if(!$admin->hasPermission($user_add)) {
            $admin_role->givePermissionTo($user_add);
        }
        if(!$admin->hasPermission($user_view)) {
            $admin_role->givePermissionTo($user_view);
        }
        if(!$admin->hasPermission($user_edit)) {
            $admin_role->givePermissionTo($user_edit);
        }
        if(!$admin->hasPermission($user_delete)) {
            $admin_role->givePermissionTo($user_delete);
        }

      //จัดการผู้ใช้งานเจ้าหน้าที่ในระบบนี้ ที่ถูกลบ
        $user_deleted_add = Permission::firstOrCreate([
            'name' => 'add-user-deleted'
        ]);
        $user_deleted_view = Permission::firstOrCreate([
            'name' => 'view-user-deleted'
        ]);
        $user_deleted_edit = Permission::firstOrCreate([
            'name' => 'edit-user-deleted'
        ]);
        $user_deleted_delete = Permission::firstOrCreate([
            'name' => 'delete-user-deleted'
        ]);

        if(!$admin->hasPermission($user_deleted_add)) {
            $admin_role->givePermissionTo($user_deleted_add);
        }
        if(!$admin->hasPermission($user_deleted_view)) {
            $admin_role->givePermissionTo($user_deleted_view);
        }
        if(!$admin->hasPermission($user_deleted_edit)) {
            $admin_role->givePermissionTo($user_deleted_edit);
        }
        if(!$admin->hasPermission($user_deleted_delete)) {
            $admin_role->givePermissionTo($user_deleted_delete);
        }

      //ข้อมูลผู้ประกอบการ
        $trader_add = Permission::firstOrCreate([
            'name' => 'add-trader'
        ]);
        $trader_view = Permission::firstOrCreate([
            'name' => 'view-trader'
        ]);
        $trader_edit = Permission::firstOrCreate([
            'name' => 'edit-trader'
        ]);
        $trader_delete = Permission::firstOrCreate([
            'name' => 'delete-trader'
        ]);

        if(!$admin->hasPermission($trader_add)) {
            $admin_role->givePermissionTo($trader_add);
        }
        if(!$admin->hasPermission($trader_view)) {
            $admin_role->givePermissionTo($trader_view);
        }
        if(!$admin->hasPermission($trader_edit)) {
            $admin_role->givePermissionTo($trader_edit);
        }
        if(!$admin->hasPermission($trader_delete)) {
            $admin_role->givePermissionTo($trader_delete);
        }

        //ระบบรายงานข้อมูลมาตรฐานที่เปิดใช้ในปัจจุบัน
        $standard_report_add = Permission::firstOrCreate([
            'name' => 'add-standard-report'
        ]);
        $standard_report_view = Permission::firstOrCreate([
            'name' => 'view-standard-report'
        ]);
        $standard_report_edit = Permission::firstOrCreate([
            'name' => 'edit-standard-report'
        ]);
        $standard_report_delete = Permission::firstOrCreate([
            'name' => 'delete-standard-report'
        ]);

        if(!$admin->hasPermission($standard_report_add)) {
            $admin_role->givePermissionTo($standard_report_add);
        }
        if(!$admin->hasPermission($standard_report_view)) {
            $admin_role->givePermissionTo($standard_report_view);
        }
        if(!$admin->hasPermission($standard_report_edit)) {
            $admin_role->givePermissionTo($standard_report_edit);
        }
        if(!$admin->hasPermission($standard_report_delete)) {
            $admin_role->givePermissionTo($standard_report_delete);
        }

        //ระบบแสดงความคิดเห็นต่อร่างมาตรฐาน
        $comment_standard_drafts_add = Permission::firstOrCreate([
            'name' => 'add-comment-standard-drafts'
        ]);
        $comment_standard_drafts_view = Permission::firstOrCreate([
            'name' => 'view-comment-standard-drafts'
        ]);
        $comment_standard_drafts_edit = Permission::firstOrCreate([
            'name' => 'edit-comment-standard-drafts'
        ]);
        $comment_standard_drafts_delete = Permission::firstOrCreate([
            'name' => 'delete-comment-standard-drafts'
        ]);

        if(!$admin->hasPermission($comment_standard_drafts_add)) {
            $admin_role->givePermissionTo($comment_standard_drafts_add);
        }
        if(!$admin->hasPermission($comment_standard_drafts_view)) {
            $admin_role->givePermissionTo($comment_standard_drafts_view);
        }
        if(!$admin->hasPermission($comment_standard_drafts_edit)) {
            $admin_role->givePermissionTo($comment_standard_drafts_edit);
        }
        if(!$admin->hasPermission($comment_standard_drafts_delete)) {
            $admin_role->givePermissionTo($comment_standard_drafts_delete);
        }

        //ระบบรับฟังข้อคิดเห็นในการทบทวนมาตรฐาน
        $comment_standard_reviews_add = Permission::firstOrCreate([
            'name' => 'add-comment-standard-reviews'
        ]);
        $comment_standard_reviews_view = Permission::firstOrCreate([
            'name' => 'view-comment-standard-reviews'
        ]);
        $comment_standard_reviews_edit = Permission::firstOrCreate([
            'name' => 'edit-comment-standard-reviews'
        ]);
        $comment_standard_reviews_delete = Permission::firstOrCreate([
            'name' => 'delete-comment-standard-reviews'
        ]);

        if(!$admin->hasPermission($comment_standard_reviews_add)) {
            $admin_role->givePermissionTo($comment_standard_reviews_add);
        }
        if(!$admin->hasPermission($comment_standard_reviews_view)) {
            $admin_role->givePermissionTo($comment_standard_reviews_view);
        }
        if(!$admin->hasPermission($comment_standard_reviews_edit)) {
            $admin_role->givePermissionTo($comment_standard_reviews_edit);
        }
        if(!$admin->hasPermission($comment_standard_reviews_delete)) {
            $admin_role->givePermissionTo($comment_standard_reviews_delete);
        }

        //ระบบรายงานข้อคิดเห็นต่อร่างมาตรฐาน
        $report_comment_standard_drafts_add = Permission::firstOrCreate([
            'name' => 'add-report-comment-standard-drafts'
        ]);
        $report_comment_standard_drafts_view = Permission::firstOrCreate([
            'name' => 'view-report-comment-standard-drafts'
        ]);
        $report_comment_standard_drafts_edit = Permission::firstOrCreate([
            'name' => 'edit-report-comment-standard-drafts'
        ]);
        $report_comment_standard_drafts_delete = Permission::firstOrCreate([
            'name' => 'delete-report-comment-standard-drafts'
        ]);

        if(!$admin->hasPermission($report_comment_standard_drafts_add)) {
            $admin_role->givePermissionTo($report_comment_standard_drafts_add);
        }
        if(!$admin->hasPermission($report_comment_standard_drafts_view)) {
            $admin_role->givePermissionTo($report_comment_standard_drafts_view);
        }
        if(!$admin->hasPermission($report_comment_standard_drafts_edit)) {
            $admin_role->givePermissionTo($report_comment_standard_drafts_edit);
        }
        if(!$admin->hasPermission($report_comment_standard_drafts_delete)) {
            $admin_role->givePermissionTo($report_comment_standard_drafts_delete);
        }


        //ระบบตั้งค่าบันทึกการดำเนินการทางกฏหมาย
        $masterlawoperations_add = Permission::firstOrCreate([
            'name' => 'add-masterlawoperation'
        ]);
        $masterlawoperations_view = Permission::firstOrCreate([
            'name' => 'view-masterlawoperation'
        ]);
        $masterlawoperations_edit = Permission::firstOrCreate([
            'name' => 'edit-masterlawoperation'
        ]);
        $masterlawoperations_delete = Permission::firstOrCreate([
            'name' => 'delete-masterlawoperation'
        ]);

        if(!$admin->hasPermission($masterlawoperations_add)) {
            $admin_role->givePermissionTo($masterlawoperations_add);
        }
        if(!$admin->hasPermission($masterlawoperations_view)) {
            $admin_role->givePermissionTo($masterlawoperations_view);
        }
        if(!$admin->hasPermission($masterlawoperations_edit)) {
            $admin_role->givePermissionTo($masterlawoperations_edit);
        }
        if(!$admin->hasPermission($masterlawoperations_delete)) {
            $admin_role->givePermissionTo($masterlawoperations_delete);
        }

        //ระบบบันทึกการดำเนินการทางกฏหมาย
        $lawoperation_add = Permission::firstOrCreate([
            'name' => 'add-lawoperation'
        ]);
        $lawoperation_view = Permission::firstOrCreate([
            'name' => 'view-lawoperation'
        ]);
        $lawoperation_edit = Permission::firstOrCreate([
            'name' => 'edit-lawoperation'
        ]);
        $lawoperation_delete = Permission::firstOrCreate([
            'name' => 'delete-lawoperation'
        ]);

        if(!$admin->hasPermission($lawoperation_add)) {
            $admin_role->givePermissionTo($lawoperation_add);
        }
        if(!$admin->hasPermission($lawoperation_view)) {
            $admin_role->givePermissionTo($lawoperation_view);
        }
        if(!$admin->hasPermission($lawoperation_edit)) {
            $admin_role->givePermissionTo($lawoperation_edit);
        }
        if(!$admin->hasPermission($lawoperation_delete)) {
            $admin_role->givePermissionTo($lawoperation_delete);
        }

        //ระบบรับ - แจ้งผลการทดสอบ (สำหรับ LAB)
        $report_product_add = Permission::firstOrCreate([
            'name' => 'add-report-product'
        ]);
        $report_product_view = Permission::firstOrCreate([
            'name' => 'view-report-product'
        ]);
        $report_product_edit = Permission::firstOrCreate([
            'name' => 'edit-report-product'
        ]);
        $report_product_delete = Permission::firstOrCreate([
            'name' => 'delete-report-product'
        ]);

        if(!$admin->hasPermission($report_product_add)) {
            $admin_role->givePermissionTo($report_product_add);
        }
        if(!$admin->hasPermission($report_product_view)) {
            $admin_role->givePermissionTo($report_product_view);
        }
        if(!$admin->hasPermission($report_product_edit)) {
            $admin_role->givePermissionTo($report_product_edit);
        }
        if(!$admin->hasPermission($report_product_delete)) {
            $admin_role->givePermissionTo($report_product_delete);
        }

        //ระบบประเมินผลทดสอบ (จาก LAB)
        $test_product_add = Permission::firstOrCreate([
            'name' => 'add-test-product'
        ]);
        $test_product_view = Permission::firstOrCreate([
            'name' => 'view-test-product'
        ]);
        $test_product_edit = Permission::firstOrCreate([
            'name' => 'edit-test-product'
        ]);
        $test_product_delete = Permission::firstOrCreate([
            'name' => 'delete-test-product'
        ]);
        $test_product_other = Permission::firstOrCreate([
            'name' => 'other-test-product'
        ]);
        $test_product_poko_approve = Permission::firstOrCreate([
            'name' => 'poko_approve-test-product'
        ]);
        $test_product_poao_approve = Permission::firstOrCreate([
            'name' => 'poao_approve-test-product'
        ]);

        if(!$admin->hasPermission($test_product_add)) {
            $admin_role->givePermissionTo($test_product_add);
        }
        if(!$admin->hasPermission($test_product_view)) {
            $admin_role->givePermissionTo($test_product_view);
        }
        if(!$admin->hasPermission($test_product_edit)) {
            $admin_role->givePermissionTo($test_product_edit);
        }
        if(!$admin->hasPermission($test_product_delete)) {
            $admin_role->givePermissionTo($test_product_delete);
        }
        if(!$admin->hasPermission($test_product_other)) {
            $admin_role->givePermissionTo($test_product_other);
        }
        if(!$admin->hasPermission($test_product_poko_approve)) {
            $admin_role->givePermissionTo($test_product_poko_approve);
        }
        if(!$admin->hasPermission($test_product_poao_approve)) {
            $admin_role->givePermissionTo($test_product_poao_approve);
        }

        //มอบหมายงานประเมินผล (จาก LAB)
        $assign_product_add = Permission::firstOrCreate([
            'name' => 'add-assign-product'
        ]);
        $assign_product_view = Permission::firstOrCreate([
            'name' => 'view-assign-product'
        ]);
        $assign_product_edit = Permission::firstOrCreate([
            'name' => 'edit-assign-product'
        ]);
        $assign_product_delete = Permission::firstOrCreate([
            'name' => 'delete-assign-product'
        ]);

        if(!$admin->hasPermission($assign_product_add)) {
            $admin_role->givePermissionTo($assign_product_add);
        }
        if(!$admin->hasPermission($assign_product_view)) {
            $admin_role->givePermissionTo($assign_product_view);
        }
        if(!$admin->hasPermission($assign_product_edit)) {
            $admin_role->givePermissionTo($assign_product_edit);
        }
        if(!$admin->hasPermission($assign_product_delete)) {
            $admin_role->givePermissionTo($assign_product_delete);
        }

        //ออกใบรับรองงาน
        $certificate_export_add = Permission::firstOrCreate([
            'name' => 'add-certificate-export'
        ]);
        $certificate_export_view = Permission::firstOrCreate([
            'name' => 'view-certificate-export'
        ]);
        $certificate_export_edit = Permission::firstOrCreate([
            'name' => 'edit-certificate-export'
        ]);
        $certificate_export_delete = Permission::firstOrCreate([
            'name' => 'delete-certificate-export'
        ]);

        if(!$admin->hasPermission($certificate_export_add)) {
            $admin_role->givePermissionTo($certificate_export_add);
        }
        if(!$admin->hasPermission($certificate_export_view)) {
            $admin_role->givePermissionTo($certificate_export_view);
        }
        if(!$admin->hasPermission($certificate_export_edit)) {
            $admin_role->givePermissionTo($certificate_export_edit);
        }
        if(!$admin->hasPermission($certificate_export_delete)) {
            $admin_role->givePermissionTo($certificate_export_delete);
        }

        //แจ้งเตือนข้อมูลใบรับรอง
        $certificate_expire_check_add = Permission::firstOrCreate([
            'name' => 'add-certificate-expire-check'
        ]);
        $certificate_expire_check_view = Permission::firstOrCreate([
            'name' => 'view-certificate-expire-check'
        ]);
        $certificate_expire_check_edit = Permission::firstOrCreate([
            'name' => 'edit-certificate-expire-check'
        ]);
        $certificate_expire_check_delete = Permission::firstOrCreate([
            'name' => 'delete-certificate-expire-check'
        ]);

        if(!$admin->hasPermission($certificate_expire_check_add)) {
            $admin_role->givePermissionTo($certificate_expire_check_add);
        }
        if(!$admin->hasPermission($certificate_expire_check_view)) {
            $admin_role->givePermissionTo($certificate_expire_check_view);
        }
        if(!$admin->hasPermission($certificate_expire_check_edit)) {
            $admin_role->givePermissionTo($certificate_expire_check_edit);
        }
        if(!$admin->hasPermission($certificate_expire_check_delete)) {
            $admin_role->givePermissionTo($certificate_expire_check_delete);
        }

        //รายงานสรุปคำขอรับบริการ
        $summary_request_service_add = Permission::firstOrCreate([
            'name' => 'add-summary-request-service'
        ]);
        $summary_request_service_view = Permission::firstOrCreate([
            'name' => 'view-summary-request-service'
        ]);
        $summary_request_service_edit = Permission::firstOrCreate([
            'name' => 'edit-summary-request-service'
        ]);
        $summary_request_service_delete = Permission::firstOrCreate([
            'name' => 'delete-summary-request-service'
        ]);

        if(!$admin->hasPermission($summary_request_service_add)) {
            $admin_role->givePermissionTo($summary_request_service_add);
        }
        if(!$admin->hasPermission($summary_request_service_view)) {
            $admin_role->givePermissionTo($summary_request_service_view);
        }
        if(!$admin->hasPermission($summary_request_service_edit)) {
            $admin_role->givePermissionTo($summary_request_service_edit);
        }
        if(!$admin->hasPermission($summary_request_service_delete)) {
            $admin_role->givePermissionTo($summary_request_service_delete);
        }

        //ตรวจสอบคำขอ
        $check_certificate_add = Permission::firstOrCreate([
            'name' => 'add-check-certificate'
        ]);
        $check_certificate_view = Permission::firstOrCreate([
            'name' => 'view-check-certificate'
        ]);
        $check_certificate_edit = Permission::firstOrCreate([
            'name' => 'edit-check-certificate'
        ]);
        $check_certificate_delete = Permission::firstOrCreate([
            'name' => 'delete-check-certificate'
        ]);
        $check_certificate_other = Permission::firstOrCreate([
            'name' => 'other-check-certificate'
        ]);
        $check_certificate_assign_work = Permission::firstOrCreate([
            'name' => 'assign_work-check-certificate'
        ]);

        if(!$admin->hasPermission($check_certificate_add)) {
            $admin_role->givePermissionTo($check_certificate_add);
        }
        if(!$admin->hasPermission($check_certificate_view)) {
            $admin_role->givePermissionTo($check_certificate_view);
        }
        if(!$admin->hasPermission($check_certificate_edit)) {
            $admin_role->givePermissionTo($check_certificate_edit);
        }
        if(!$admin->hasPermission($check_certificate_delete)) {
            $admin_role->givePermissionTo($check_certificate_delete);
        }
        if(!$admin->hasPermission($check_certificate_other)) {
            $admin_role->givePermissionTo($check_certificate_other);
        }
        if(!$admin->hasPermission($check_certificate_assign_work)) {
            $admin_role->givePermissionTo($check_certificate_assign_work);
        }

        //ตรวจประเมิน
        $check_assessment_add = Permission::firstOrCreate([
            'name' => 'add-check-assessment'
        ]);
        $check_assessment_view = Permission::firstOrCreate([
            'name' => 'view-check-assessment'
        ]);
        $check_assessment_edit = Permission::firstOrCreate([
            'name' => 'edit-check-assessment'
        ]);
        $check_assessment_delete = Permission::firstOrCreate([
            'name' => 'delete-check-assessment'
        ]);
        $check_assessment_other = Permission::firstOrCreate([
            'name' => 'other-check-assessment'
        ]);
        $check_assessment_assign_work = Permission::firstOrCreate([
            'name' => 'assign_work-check-assessment'
        ]);

        if(!$admin->hasPermission($check_assessment_add)) {
            $admin_role->givePermissionTo($check_assessment_add);
        }
        if(!$admin->hasPermission($check_assessment_view)) {
            $admin_role->givePermissionTo($check_assessment_view);
        }
        if(!$admin->hasPermission($check_assessment_edit)) {
            $admin_role->givePermissionTo($check_assessment_edit);
        }
        if(!$admin->hasPermission($check_assessment_delete)) {
            $admin_role->givePermissionTo($check_assessment_delete);
        }
        if(!$admin->hasPermission($check_assessment_other)) {
            $admin_role->givePermissionTo($check_assessment_other);
        }
        if(!$admin->hasPermission($check_assessment_assign_work)) {
            $admin_role->givePermissionTo($check_assessment_assign_work);
        }

        //ตรวจประเมิน
        $estimated_cost_add = Permission::firstOrCreate([
            'name' => 'add-estimated-cost'
        ]);
        $estimated_cost_view = Permission::firstOrCreate([
            'name' => 'view-estimated-cost'
        ]);
        $estimated_cost_edit = Permission::firstOrCreate([
            'name' => 'edit-estimated-cost'
        ]);
        $estimated_cost_delete = Permission::firstOrCreate([
            'name' => 'delete-estimated-cost'
        ]);

        if(!$admin->hasPermission($estimated_cost_add)) {
            $admin_role->givePermissionTo($estimated_cost_add);
        }
        if(!$admin->hasPermission($estimated_cost_view)) {
            $admin_role->givePermissionTo($estimated_cost_view);
        }
        if(!$admin->hasPermission($estimated_cost_edit)) {
            $admin_role->givePermissionTo($estimated_cost_edit);
        }
        if(!$admin->hasPermission($estimated_cost_delete)) {
            $admin_role->givePermissionTo($estimated_cost_delete);
        }

        //ข้อบกพร่อง/ข้อสังเกต
        $save_assessment_add = Permission::firstOrCreate([
            'name' => 'add-save-assessment'
        ]);
        $save_assessment_view = Permission::firstOrCreate([
            'name' => 'view-save-assessment'
        ]);
        $save_assessment_edit = Permission::firstOrCreate([
            'name' => 'edit-save-assessment'
        ]);
        $save_assessment_delete = Permission::firstOrCreate([
            'name' => 'delete-save-assessment'
        ]);

        if(!$admin->hasPermission($save_assessment_add)) {
            $admin_role->givePermissionTo($save_assessment_add);
        }
        if(!$admin->hasPermission($save_assessment_view)) {
            $admin_role->givePermissionTo($save_assessment_view);
        }
        if(!$admin->hasPermission($save_assessment_edit)) {
            $admin_role->givePermissionTo($save_assessment_edit);
        }
        if(!$admin->hasPermission($save_assessment_delete)) {
            $admin_role->givePermissionTo($save_assessment_delete);
        }

        //รับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศ (21 ทวิ)
        $receive_applicant_21bi_add = Permission::firstOrCreate([
            'name' => 'add-receive-applicant-21bi'
        ]);
        $receive_applicant_21bi_view = Permission::firstOrCreate([
            'name' => 'view-receive-applicant-21bi'
        ]);
        $receive_applicant_21bi_edit = Permission::firstOrCreate([
            'name' => 'edit-receive-applicant-21bi'
        ]);
        $receive_applicant_21bi_delete = Permission::firstOrCreate([
            'name' => 'delete-receive-applicant-21bi'
        ]);

        if(!$admin->hasPermission($receive_applicant_21bi_add)) {
            $admin_role->givePermissionTo($receive_applicant_21bi_add);
        }
        if(!$admin->hasPermission($receive_applicant_21bi_view)) {
            $admin_role->givePermissionTo($receive_applicant_21bi_view);
        }
        if(!$admin->hasPermission($receive_applicant_21bi_edit)) {
            $admin_role->givePermissionTo($receive_applicant_21bi_edit);
        }
        if(!$admin->hasPermission($receive_applicant_21bi_delete)) {
            $admin_role->givePermissionTo($receive_applicant_21bi_delete);
        }

        //รายงานแผน - ผล การปฏิบัติงาน (งาน, เงิน)
        $report_performance_add = Permission::firstOrCreate([
            'name' => 'add-report-performance'
        ]);
        $report_performance_view = Permission::firstOrCreate([
            'name' => 'view-report-performance'
        ]);
        $report_performance_edit = Permission::firstOrCreate([
            'name' => 'edit-report-performance'
        ]);
        $report_performance_delete = Permission::firstOrCreate([
            'name' => 'delete-report-performance'
        ]);

        if(!$admin->hasPermission($report_performance_add)) {
            $admin_role->givePermissionTo($report_performance_add);
        }
        if(!$admin->hasPermission($report_performance_view)) {
            $admin_role->givePermissionTo($report_performance_view);
        }
        if(!$admin->hasPermission($report_performance_edit)) {
            $admin_role->givePermissionTo($report_performance_edit);
        }
        if(!$admin->hasPermission($report_performance_delete)) {
            $admin_role->givePermissionTo($report_performance_delete);
        }


        //หมวดส่งเสริมผู้ประกอบการ (กก.)
        $promote_trader_add = Permission::firstOrCreate([
            'name' => 'add-promote-trader'
        ]);
        $promote_trader_view = Permission::firstOrCreate([
            'name' => 'view-promote-trader'
        ]);
        $promote_trader_edit = Permission::firstOrCreate([
            'name' => 'edit-promote-trader'
        ]);
        $promote_trader_delete = Permission::firstOrCreate([
            'name' => 'delete-promote-trader'
        ]);

        if(!$admin->hasPermission($promote_trader_add)) {
            $admin_role->givePermissionTo($promote_trader_add);
        }
        if(!$admin->hasPermission($promote_trader_view)) {
            $admin_role->givePermissionTo($promote_trader_view);
        }
        if(!$admin->hasPermission($promote_trader_edit)) {
            $admin_role->givePermissionTo($promote_trader_edit);
        }
        if(!$admin->hasPermission($promote_trader_delete)) {
            $admin_role->givePermissionTo($promote_trader_delete);
        }

        $tisuseresurvs_add    = Permission::firstOrCreate(['name' => 'add-tis-user-esurvs']);
        $tisuseresurvs_view   = Permission::firstOrCreate(['name' => 'view-tisuseresurvs']);
        $tisuseresurvs_edit   = Permission::firstOrCreate(['name' => 'edit-tisuseresurvs']);
        $tisuseresurvs_delete = Permission::firstOrCreate(['name' => 'delete-tisuseresurvs']);

        if (!$admin->hasPermission($tisuseresurvs_add)) {
          $admin_role->givePermissionTo($tisuseresurvs_add);
        }
        if (!$admin->hasPermission($tisuseresurvs_view)) {
          $admin_role->givePermissionTo($tisuseresurvs_view);
        }
        if (!$admin->hasPermission($tisuseresurvs_edit)) {
          $admin_role->givePermissionTo($tisuseresurvs_edit);
        }
        if (!$admin->hasPermission($tisuseresurvs_delete)) {
          $admin_role->givePermissionTo($tisuseresurvs_delete);
        }

        //รับคำขอการทำผลิตภัณฑ์เพื่อส่งออก (ตามมาตรา 20 ตรี)
        $accept_export_view   = Permission::firstOrCreate(['name' => 'view-accept-export']);
        $accept_export_edit   = Permission::firstOrCreate(['name' => 'edit-accept-export']);

        if (!$admin->hasPermission($accept_export_view)) {
          $admin_role->givePermissionTo($accept_export_view);
        }
        if (!$admin->hasPermission($accept_export_edit)) {
          $admin_role->givePermissionTo($accept_export_edit);
        }

        //คำขอทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นเฉพาะคราว (ตามมาตรา 20 ทวิ)
        $accept_import_view   = Permission::firstOrCreate(['name' => 'view-accept-import']);
        $accept_import_edit   = Permission::firstOrCreate(['name' => 'edit-accept-import']);

        if (!$admin->hasPermission($accept_import_view)) {
          $admin_role->givePermissionTo($accept_import_view);
        }
        if (!$admin->hasPermission($accept_import_edit)) {
          $admin_role->givePermissionTo($accept_import_edit);
        }

        //แจ้งผลการทำเพื่อการส่งออก (ตามมาตรา 20 ตรี)
        $report_export_view   = Permission::firstOrCreate(['name' => 'view-report-export']);
        $report_export_edit   = Permission::firstOrCreate(['name' => 'edit-report-export']);

        if (!$admin->hasPermission($report_export_view)) {
          $admin_role->givePermissionTo($report_export_view);
        }
        if (!$admin->hasPermission($report_export_edit)) {
          $admin_role->givePermissionTo($report_export_edit);
        }

        //แจ้งการทำเพื่อใช้ในราชอาณาจักร (ตามมาตรา 20 ทวิ)
        $report_import_view   = Permission::firstOrCreate(['name' => 'view-report-import']);
        $report_import_edit   = Permission::firstOrCreate(['name' => 'edit-report-import']);

        if (!$admin->hasPermission($report_import_view)) {
          $admin_role->givePermissionTo($report_import_view);
        }
        if (!$admin->hasPermission($report_import_edit)) {
          $admin_role->givePermissionTo($report_import_edit);
        }

        //ระบบบันทึกการตรวจควบคุมคุณภาพ
        $control_performance_add = Permission::firstOrCreate([
            'name' => 'add-control-performance'
        ]);
        $control_performance_view = Permission::firstOrCreate([
            'name' => 'view-control-performance'
        ]);
        $control_performance_edit = Permission::firstOrCreate([
            'name' => 'edit-control-performance'
        ]);
        $control_performance_delete = Permission::firstOrCreate([
            'name' => 'delete-control-performance'
        ]);
        $control_performance_other = Permission::firstOrCreate([
            'name' => 'other-control-performance'
        ]);
        $control_performance_poko_approve = Permission::firstOrCreate([
            'name' => 'poko_approve-control-performance'
        ]);
        $control_performance_poao_approve = Permission::firstOrCreate([
            'name' => 'poao_approve-control-performance'
        ]);
        if(!$admin->hasPermission($control_performance_add)) {
            $admin_role->givePermissionTo($control_performance_add);
        }
        if(!$admin->hasPermission($control_performance_view)) {
            $admin_role->givePermissionTo($control_performance_view);
        }
        if(!$admin->hasPermission($control_performance_edit)) {
            $admin_role->givePermissionTo($control_performance_edit);
        }
        if(!$admin->hasPermission($control_performance_delete)) {
            $admin_role->givePermissionTo($control_performance_delete);
        }
        if(!$admin->hasPermission($control_performance_other)) {
            $admin_role->givePermissionTo($control_performance_other);
        }
        if(!$admin->hasPermission($control_performance_poko_approve)) {
            $admin_role->givePermissionTo($control_performance_poko_approve);
        }
        if(!$admin->hasPermission($control_performance_poao_approve)) {
            $admin_role->givePermissionTo($control_performance_poao_approve);
        }

        //ระบบบันทึกการตรวจควบคุมฯ
        $control_check_add = Permission::firstOrCreate([
            'name' => 'add-control-check'
        ]);
        $control_check_view = Permission::firstOrCreate([
            'name' => 'view-control-check'
        ]);
        $control_check_edit = Permission::firstOrCreate([
            'name' => 'edit-control-check'
        ]);
        $control_check_delete = Permission::firstOrCreate([
            'name' => 'delete-control-check'
        ]);
        $control_check_other = Permission::firstOrCreate([
            'name' => 'other-control-check'
        ]);
        $control_check_poko_approve = Permission::firstOrCreate([
            'name' => 'poko_approve-control-check'
        ]);
        $control_check_poao_approve = Permission::firstOrCreate([
            'name' => 'poao_approve-control-check'
        ]);

        if(!$admin->hasPermission($control_check_add)) {
            $admin_role->givePermissionTo($control_check_add);
        }
        if(!$admin->hasPermission($control_check_view)) {
            $admin_role->givePermissionTo($control_check_view);
        }
        if(!$admin->hasPermission($control_check_edit)) {
            $admin_role->givePermissionTo($control_check_edit);
        }
        if(!$admin->hasPermission($control_check_delete)) {
            $admin_role->givePermissionTo($control_check_delete);
        }
        if(!$admin->hasPermission($control_check_other)) {
            $admin_role->givePermissionTo($control_check_other);
        }
        if(!$admin->hasPermission($control_check_poko_approve)) {
            $admin_role->givePermissionTo($control_check_poko_approve);
        }
        if(!$admin->hasPermission($control_check_poao_approve)) {
            $admin_role->givePermissionTo($control_check_poao_approve);
        }

        //ระบบบันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม
        $control_freeze_add = Permission::firstOrCreate([
            'name' => 'add-control-freeze'
        ]);
        $control_freeze_view = Permission::firstOrCreate([
            'name' => 'view-control-freeze'
        ]);
        $control_freeze_edit = Permission::firstOrCreate([
            'name' => 'edit-control-freeze'
        ]);
        $control_freeze_delete = Permission::firstOrCreate([
            'name' => 'delete-control-freeze'
        ]);

        if(!$admin->hasPermission($control_freeze_add)) {
            $admin_role->givePermissionTo($control_freeze_add);
        }
        if(!$admin->hasPermission($control_freeze_view)) {
            $admin_role->givePermissionTo($control_freeze_view);
        }
        if(!$admin->hasPermission($control_freeze_edit)) {
            $admin_role->givePermissionTo($control_freeze_edit);
        }
        if(!$admin->hasPermission($control_freeze_delete)) {
            $admin_role->givePermissionTo($control_freeze_delete);
        }

        //ทำแผนตรวจติดตาม
        $control_follow_add = Permission::firstOrCreate([
            'name' => 'add-control-follow'
        ]);
        $control_follow_view = Permission::firstOrCreate([
            'name' => 'view-control-follow'
        ]);
        $control_follow_edit = Permission::firstOrCreate([
            'name' => 'edit-control-follow'
        ]);
        $control_follow_delete = Permission::firstOrCreate([
            'name' => 'delete-control-follow'
        ]);

        if(!$admin->hasPermission($control_follow_add)) {
            $admin_role->givePermissionTo($control_follow_add);
        }
        if(!$admin->hasPermission($control_follow_view)) {
            $admin_role->givePermissionTo($control_follow_view);
        }
        if(!$admin->hasPermission($control_follow_edit)) {
            $admin_role->givePermissionTo($control_follow_edit);
        }
        if(!$admin->hasPermission($control_follow_delete)) {
            $admin_role->givePermissionTo($control_follow_delete);
        }

        //รายงานแจ้งปริมาณการผลิต
        $report_volume_view = Permission::firstOrCreate([
            'name' => 'view-report-volume'
        ]);

        if(!$admin->hasPermission($report_volume_view)) {
            $admin_role->givePermissionTo($report_volume_view);
        }

        //รายงานแจ้งเปลี่ยนแปลง
        $report_change_view = Permission::firstOrCreate([
            'name' => 'view-report-change'
        ]);

        if(!$admin->hasPermission($report_change_view)) {
            $admin_role->givePermissionTo($report_change_view);
        }

        //รายงานแจ้งผลการประเมิน QC
        $report_quality_control_view = Permission::firstOrCreate([
            'name' => 'view-report-quality-control'
        ]);

        if(!$admin->hasPermission($report_quality_control_view)) {
            $admin_role->givePermissionTo($report_quality_control_view);
        }

        //รายงานแจ้งผลทดสอบ
        $report_inspection_view = Permission::firstOrCreate([
            'name' => 'view-report-inspection'
        ]);

        if(!$admin->hasPermission($report_inspection_view)) {
            $admin_role->givePermissionTo($report_inspection_view);
        }

        //รายงานแจ้งผลการสอบเทียบ
        $report_calibrate_view = Permission::firstOrCreate([
            'name' => 'view-report-calibrate'
        ]);

        if(!$admin->hasPermission($report_calibrate_view)) {
            $admin_role->givePermissionTo($report_calibrate_view);
        }

        //
        $certify_alert_setting_view = Permission::firstOrCreate([
            'name' => 'view-certify-alert-setting'
        ]);
        $certify_alert_setting_edit = Permission::firstOrCreate([
            'name' => 'edit-certify-alert-setting'
        ]);

        if(!$admin->hasPermission($certify_alert_setting_view)) {
            $admin_role->givePermissionTo($certify_alert_setting_view);
        }
        if(!$admin->hasPermission($certify_alert_setting_edit)) {
            $admin_role->givePermissionTo($certify_alert_setting_edit);
        }

        //แต่งตั้งคณะผู้ตรวจประเมิน (สก.)
        $board_auditor_add = Permission::firstOrCreate([
            'name' => 'add-board-auditor'
        ]);
        $board_auditor_view = Permission::firstOrCreate([
            'name' => 'view-board-auditor'
        ]);
        $board_auditor_edit = Permission::firstOrCreate([
            'name' => 'edit-board-auditor'
        ]);
        $board_auditor_delete = Permission::firstOrCreate([
            'name' => 'delete-board-auditor'
        ]);

        if(!$admin->hasPermission($board_auditor_add)) {
            $admin_role->givePermissionTo($board_auditor_add);
        }
        if(!$admin->hasPermission($board_auditor_view)) {
            $admin_role->givePermissionTo($board_auditor_view);
        }
        if(!$admin->hasPermission($board_auditor_edit)) {
            $admin_role->givePermissionTo($board_auditor_edit);
        }
        if(!$admin->hasPermission($board_auditor_delete)) {
            $admin_role->givePermissionTo($board_auditor_delete);
        }

        //ระบบใบรับ - นำส่งตัวอย่าง (กต.)
        $save_example_add = Permission::firstOrCreate([
            'name' => 'add-save-example'
        ]);
        $save_example_view = Permission::firstOrCreate([
            'name' => 'view-save-example'
        ]);
        $save_example_edit = Permission::firstOrCreate([
            'name' => 'edit-save-example'
        ]);
        $save_example_delete = Permission::firstOrCreate([
            'name' => 'delete-save-example'
        ]);
        $save_example_other = Permission::firstOrCreate([
            'name' => 'other-save-example'
        ]);


        if(!$admin->hasPermission($save_example_add)) {
            $admin_role->givePermissionTo($save_example_add);
        }
        if(!$admin->hasPermission($save_example_view)) {
            $admin_role->givePermissionTo($save_example_view);
        }
        if(!$admin->hasPermission($save_example_edit)) {
            $admin_role->givePermissionTo($save_example_edit);
        }
        if(!$admin->hasPermission($save_example_delete)) {
            $admin_role->givePermissionTo($save_example_delete);
        }
        if(!$admin->hasPermission($save_example_other)) {
            $admin_role->givePermissionTo($save_example_other);
        }


        //รับ-แจ้งผลการทดสอบ (สำหรับ LAB) (กต.)
        $report_product_add = Permission::firstOrCreate([
            'name' => 'add-report-product'
        ]);
        $report_product_view = Permission::firstOrCreate([
            'name' => 'view-report-product'
        ]);
        $report_product_edit = Permission::firstOrCreate([
            'name' => 'edit-report-product'
        ]);
        $report_product_delete = Permission::firstOrCreate([
            'name' => 'delete-report-product'
        ]);

        if(!$admin->hasPermission($report_product_add)) {
            $admin_role->givePermissionTo($report_product_add);
        }
        if(!$admin->hasPermission($report_product_view)) {
            $admin_role->givePermissionTo($report_product_view);
        }
        if(!$admin->hasPermission($report_product_edit)) {
            $admin_role->givePermissionTo($report_product_edit);
        }
        if(!$admin->hasPermission($report_product_delete)) {
            $admin_role->givePermissionTo($report_product_delete);
        }

        $results_product_add = Permission::firstOrCreate([
            'name' => 'add-results-product'
        ]);
        $results_product_view = Permission::firstOrCreate([
            'name' => 'view-results-product'
        ]);
        $results_product_edit = Permission::firstOrCreate([
            'name' => 'edit-results-product'
        ]);
        $results_product_delete = Permission::firstOrCreate([
            'name' => 'delete-results-product'
        ]);

        if(!$admin->hasPermission($results_product_add)) {
            $admin_role->givePermissionTo($results_product_add);
        }
        if(!$admin->hasPermission($results_product_view)) {
            $admin_role->givePermissionTo($results_product_view);
        }
        if(!$admin->hasPermission($results_product_edit)) {
            $admin_role->givePermissionTo($results_product_edit);
        }
        if(!$admin->hasPermission($results_product_delete)) {
            $admin_role->givePermissionTo($results_product_delete);
        }

        $tisilicensenotification_add = Permission::firstOrCreate([
            'name' => 'add-tisilicensenotification'
        ]);
        $tisilicensenotification_view = Permission::firstOrCreate([
            'name' => 'view-tisilicensenotification'
        ]);
        $tisilicensenotification_edit = Permission::firstOrCreate([
            'name' => 'edit-tisilicensenotification'
        ]);
        $tisilicensenotification_delete = Permission::firstOrCreate([
            'name' => 'delete-tisilicensenotification'
        ]);

        if(!$admin->hasPermission($tisilicensenotification_add)) {
            $admin_role->givePermissionTo($tisilicensenotification_add);
        }
        if(!$admin->hasPermission($tisilicensenotification_view)) {
            $admin_role->givePermissionTo($tisilicensenotification_view);
        }
        if(!$admin->hasPermission($tisilicensenotification_edit)) {
            $admin_role->givePermissionTo($tisilicensenotification_edit);
        }
        if(!$admin->hasPermission($tisilicensenotification_delete)) {
            $admin_role->givePermissionTo($tisilicensenotification_delete);
        }

    //หน่วยงาน
        $appoint_department_add = Permission::firstOrCreate([
            'name' => 'add-appoint-department'
        ]);
        $appoint_department_view = Permission::firstOrCreate([
            'name' => 'view-appoint-department'
        ]);
        $appoint_department_edit = Permission::firstOrCreate([
            'name' => 'edit-appoint-department'
        ]);
        $appoint_department_delete = Permission::firstOrCreate([
            'name' => 'delete-appoint-department'
        ]);

        if(!$admin->hasPermission($appoint_department_add)) {
            $admin_role->givePermissionTo($appoint_department_add);
        }
        if(!$admin->hasPermission($appoint_department_view)) {
            $admin_role->givePermissionTo($appoint_department_view);
        }
        if(!$admin->hasPermission($appoint_department_edit)) {
            $admin_role->givePermissionTo($appoint_department_edit);
        }
        if(!$admin->hasPermission($appoint_department_delete)) {
            $admin_role->givePermissionTo($appoint_department_delete);
        }


         //แจ้งข้อมูลใบอนุญาต
        $tisi_license_notification_add = Permission::firstOrCreate([
            'name' => 'add-tisi-license-notification'
        ]);
        $tisi_license_notification_view = Permission::firstOrCreate([
            'name' => 'view-tisi-license-notification'
        ]);
        $tisi_license_notification_edit = Permission::firstOrCreate([
            'name' => 'edit-tisi-license-notification'
        ]);
        $tisi_license_notification_delete = Permission::firstOrCreate([
            'name' => 'delete-tisi-license-notification'
        ]);

        if(!$admin->hasPermission($tisi_license_notification_add)) {
            $admin_role->givePermissionTo($tisi_license_notification_add);
        }
        if(!$admin->hasPermission($tisi_license_notification_view)) {
            $admin_role->givePermissionTo($tisi_license_notification_view);
        }
        if(!$admin->hasPermission($tisi_license_notification_edit)) {
            $admin_role->givePermissionTo($tisi_license_notification_edit);
        }
        if(!$admin->hasPermission($tisi_license_notification_delete)) {
            $admin_role->givePermissionTo($tisi_license_notification_delete);
        }


         //หน่วยงาน
            $tisusercertify_add = Permission::firstOrCreate([
                'name' => 'add-tisusercertify'
            ]);
            $tisusercertify_view = Permission::firstOrCreate([
                'name' => 'view-tisusercertify'
            ]);
            $tisusercertify_edit = Permission::firstOrCreate([
                'name' => 'edit-set_standard_user'
            ]);
            $tisusercertify_delete = Permission::firstOrCreate([
                'name' => 'delete-tisusercertify'
            ]);

            if(!$admin->hasPermission($tisusercertify_add)) {
                $admin_role->givePermissionTo($tisusercertify_add);
            }
            if(!$admin->hasPermission($tisusercertify_view)) {
                $admin_role->givePermissionTo($tisusercertify_view);
            }
            if(!$admin->hasPermission($tisusercertify_edit)) {
                $admin_role->givePermissionTo($tisusercertify_edit);
            }
            if(!$admin->hasPermission($tisusercertify_delete)) {
                $admin_role->givePermissionTo($tisusercertify_delete);
            }

            $applicantlabs_add = Permission::firstOrCreate([
                'name' => 'add-applicantlabs'
            ]);
            $applicantlabs_view = Permission::firstOrCreate([
                'name' => 'view-applicantlabs'
            ]);
            $applicantlabs_edit = Permission::firstOrCreate([
                'name' => 'edit-applicantlabs'
            ]);
            $applicantlabs_delete = Permission::firstOrCreate([
                'name' => 'delete-applicantlabs'
            ]);

            if(!$admin->hasPermission($applicantlabs_add)) {
                $admin_role->givePermissionTo($applicantlabs_add);
            }
            if(!$admin->hasPermission($applicantlabs_view)) {
                $admin_role->givePermissionTo($applicantlabs_view);
            }
            if(!$admin->hasPermission($applicantlabs_edit)) {
                $admin_role->givePermissionTo($applicantlabs_edit);
            }
            if(!$admin->hasPermission($applicantlabs_delete)) {
                $admin_role->givePermissionTo($applicantlabs_delete);
            }

            $checkcertificateib_add = Permission::firstOrCreate([
                'name' => 'add-checkcertificateib'
            ]);
            $checkcertificateib_view = Permission::firstOrCreate([
                'name' => 'view-checkcertificateib'
            ]);
            $checkcertificateib_edit = Permission::firstOrCreate([
                'name' => 'edit-checkcertificateib'
            ]);
            $checkcertificateib_delete = Permission::firstOrCreate([
                'name' => 'delete-checkcertificateib'
            ]);

            if(!$admin->hasPermission($checkcertificateib_add)) {
                $admin_role->givePermissionTo($checkcertificateib_add);
            }
            if(!$admin->hasPermission($checkcertificateib_view)) {
                $admin_role->givePermissionTo($checkcertificateib_view);
            }
            if(!$admin->hasPermission($checkcertificateib_edit)) {
                $admin_role->givePermissionTo($checkcertificateib_edit);
            }
            if(!$admin->hasPermission($checkcertificateib_delete)) {
                $admin_role->givePermissionTo($checkcertificateib_delete);
            }

            $estimatedcostib_add = Permission::firstOrCreate([
                'name' => 'add-estimatedcostib'
            ]);
            $estimatedcostib_view = Permission::firstOrCreate([
                'name' => 'view-estimatedcostib'
            ]);
            $estimatedcostib_edit = Permission::firstOrCreate([
                'name' => 'edit-estimatedcostib'
            ]);
            $estimatedcostib_delete = Permission::firstOrCreate([
                'name' => 'delete-estimatedcostib'
            ]);

            if(!$admin->hasPermission($estimatedcostib_add)) {
                $admin_role->givePermissionTo($estimatedcostib_add);
            }
            if(!$admin->hasPermission($estimatedcostib_view)) {
                $admin_role->givePermissionTo($estimatedcostib_view);
            }
            if(!$admin->hasPermission($estimatedcostib_edit)) {
                $admin_role->givePermissionTo($estimatedcostib_edit);
            }
            if(!$admin->hasPermission($estimatedcostib_delete)) {
                $admin_role->givePermissionTo($estimatedcostib_delete);
            }

            $auditorib_add = Permission::firstOrCreate([
                'name' => 'add-auditorib'
            ]);
            $auditorib_view = Permission::firstOrCreate([
                'name' => 'view-auditorib'
            ]);
            $auditorib_edit = Permission::firstOrCreate([
                'name' => 'edit-auditorib'
            ]);
            $auditorib_delete = Permission::firstOrCreate([
                'name' => 'delete-auditorib'
            ]);

            if(!$admin->hasPermission($auditorib_add)) {
                $admin_role->givePermissionTo($auditorib_add);
            }
            if(!$admin->hasPermission($auditorib_view)) {
                $admin_role->givePermissionTo($auditorib_view);
            }
            if(!$admin->hasPermission($auditorib_edit)) {
                $admin_role->givePermissionTo($auditorib_edit);
            }
            if(!$admin->hasPermission($auditorib_delete)) {
                $admin_role->givePermissionTo($auditorib_delete);
            }

            $saveassessmentib_add = Permission::firstOrCreate([
                'name' => 'add-saveassessmentib'
            ]);
            $saveassessmentib_view = Permission::firstOrCreate([
                'name' => 'view-saveassessmentib'
            ]);
            $saveassessmentib_edit = Permission::firstOrCreate([
                'name' => 'edit-saveassessmentib'
            ]);
            $saveassessmentib_delete = Permission::firstOrCreate([
                'name' => 'delete-saveassessmentib'
            ]);

            if(!$admin->hasPermission($saveassessmentib_add)) {
                $admin_role->givePermissionTo($saveassessmentib_add);
            }
            if(!$admin->hasPermission($saveassessmentib_view)) {
                $admin_role->givePermissionTo($saveassessmentib_view);
            }
            if(!$admin->hasPermission($saveassessmentib_edit)) {
                $admin_role->givePermissionTo($saveassessmentib_edit);
            }
            if(!$admin->hasPermission($saveassessmentib_delete)) {
                $admin_role->givePermissionTo($saveassessmentib_delete);
            }

            $certificateexportib_add = Permission::firstOrCreate([
                'name' => 'add-certificateexportib'
            ]);
            $certificateexportib_view = Permission::firstOrCreate([
                'name' => 'view-certificateexportib'
            ]);
            $certificateexportib_edit = Permission::firstOrCreate([
                'name' => 'edit-certificateexportib'
            ]);
            $certificateexportib_delete = Permission::firstOrCreate([
                'name' => 'delete-certificateexportib'
            ]);

            if(!$admin->hasPermission($certificateexportib_add)) {
                $admin_role->givePermissionTo($certificateexportib_add);
            }
            if(!$admin->hasPermission($certificateexportib_view)) {
                $admin_role->givePermissionTo($certificateexportib_view);
            }
            if(!$admin->hasPermission($certificateexportib_edit)) {
                $admin_role->givePermissionTo($certificateexportib_edit);
            }
            if(!$admin->hasPermission($certificateexportib_delete)) {
                $admin_role->givePermissionTo($certificateexportib_delete);
            }

        //รับคำขอการนำเข้าผลิตภัณฑ์เพื่อส่งออก (ตามมาตรา 21 ตรี)
        $accept21_export_view   = Permission::firstOrCreate(['name' => 'view-accept21-export']);
        $accept21_export_edit   = Permission::firstOrCreate(['name' => 'edit-accept21-export']);

        if (!$admin->hasPermission($accept21_export_view)) {
          $admin_role->givePermissionTo($accept21_export_view);
        }
        if (!$admin->hasPermission($accept21_export_edit)) {
          $admin_role->givePermissionTo($accept21_export_edit);
        }

        //รับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (ตามมาตรา 21 ทวิ)
        $accept21_import_view   = Permission::firstOrCreate(['name' => 'view-accept21-import']);
        $accept21_import_edit   = Permission::firstOrCreate(['name' => 'edit-accept21-import']);

        if (!$admin->hasPermission($accept21_import_view)) {
          $admin_role->givePermissionTo($accept21_import_view);
        }
        if (!$admin->hasPermission($accept21_import_edit)) {
          $admin_role->givePermissionTo($accept21_import_edit);
        }

        //รับแจ้งปริมาณการนำเข้าเพื่อส่งออก (ตามมาตรา 21 ตรี)
        $report21_export_view   = Permission::firstOrCreate(['name' => 'view-report21-export']);
        $report21_export_edit   = Permission::firstOrCreate(['name' => 'edit-report21-export']);

        if (!$admin->hasPermission($report21_export_view)) {
          $admin_role->givePermissionTo($report21_export_view);
        }
        if (!$admin->hasPermission($report21_export_edit)) {
          $admin_role->givePermissionTo($report21_export_edit);
        }

        //รับแจ้งปริมาณการนำเข้าเพื่อใช้ในราชอาณาจักร (ตามมาตรา 21 ทวิ)
        $report21_import_view   = Permission::firstOrCreate(['name' => 'view-report21-import']);
        $report21_import_edit   = Permission::firstOrCreate(['name' => 'edit-report21-import']);

        if (!$admin->hasPermission($report21_import_view)) {
          $admin_role->givePermissionTo($report21_import_view);
        }
        if (!$admin->hasPermission($report21_import_edit)) {
          $admin_role->givePermissionTo($report21_import_edit);
        }

            $checkcertificatecb_add = Permission::firstOrCreate([
                'name' => 'add-checkcertificatecb'
            ]);
            $checkcertificatecb_view = Permission::firstOrCreate([
                'name' => 'view-checkcertificatecb'
            ]);
            $checkcertificatecb_edit = Permission::firstOrCreate([
                'name' => 'edit-checkcertificatecb'
            ]);
            $checkcertificatecb_delete = Permission::firstOrCreate([
                'name' => 'delete-checkcertificatecb'
            ]);

            if(!$admin->hasPermission($checkcertificatecb_add)) {
                $admin_role->givePermissionTo($checkcertificatecb_add);
            }
            if(!$admin->hasPermission($checkcertificatecb_view)) {
                $admin_role->givePermissionTo($checkcertificatecb_view);
            }
            if(!$admin->hasPermission($checkcertificatecb_edit)) {
                $admin_role->givePermissionTo($checkcertificatecb_edit);
            }
            if(!$admin->hasPermission($checkcertificatecb_delete)) {
                $admin_role->givePermissionTo($checkcertificatecb_delete);
            }


            $estimatedcostcb_add = Permission::firstOrCreate([
                'name' => 'add-estimatedcostcb'
            ]);
            $estimatedcostcb_view = Permission::firstOrCreate([
                'name' => 'view-estimatedcostcb'
            ]);
            $estimatedcostcb_edit = Permission::firstOrCreate([
                'name' => 'edit-estimatedcostcb'
            ]);
            $estimatedcostcb_delete = Permission::firstOrCreate([
                'name' => 'delete-estimatedcostcb'
            ]);

            if(!$admin->hasPermission($estimatedcostcb_add)) {
                $admin_role->givePermissionTo($estimatedcostcb_add);
            }
            if(!$admin->hasPermission($estimatedcostcb_view)) {
                $admin_role->givePermissionTo($estimatedcostcb_view);
            }
            if(!$admin->hasPermission($estimatedcostcb_edit)) {
                $admin_role->givePermissionTo($estimatedcostcb_edit);
            }
            if(!$admin->hasPermission($estimatedcostcb_delete)) {
                $admin_role->givePermissionTo($estimatedcostcb_delete);
            }

            $auditorcb_add = Permission::firstOrCreate([
                'name' => 'add-auditorcb'
            ]);
            $auditorcb_view = Permission::firstOrCreate([
                'name' => 'view-auditorcb'
            ]);
            $auditorcb_edit = Permission::firstOrCreate([
                'name' => 'edit-auditorcb'
            ]);
            $auditorcb_delete = Permission::firstOrCreate([
                'name' => 'delete-auditorcb'
            ]);

            if(!$admin->hasPermission($auditorcb_add)) {
                $admin_role->givePermissionTo($auditorcb_add);
            }
            if(!$admin->hasPermission($auditorcb_view)) {
                $admin_role->givePermissionTo($auditorcb_view);
            }
            if(!$admin->hasPermission($auditorcb_edit)) {
                $admin_role->givePermissionTo($auditorcb_edit);
            }
            if(!$admin->hasPermission($auditorcb_delete)) {
                $admin_role->givePermissionTo($auditorcb_delete);
            }

            $saveassessmentcb_add = Permission::firstOrCreate([
                'name' => 'add-saveassessmentcb'
            ]);
            $saveassessmentcb_view = Permission::firstOrCreate([
                'name' => 'view-saveassessmentcb'
            ]);
            $saveassessmentcb_edit = Permission::firstOrCreate([
                'name' => 'edit-saveassessmentcb'
            ]);
            $saveassessmentcb_delete = Permission::firstOrCreate([
                'name' => 'delete-saveassessmentcb'
            ]);

            if(!$admin->hasPermission($saveassessmentcb_add)) {
                $admin_role->givePermissionTo($saveassessmentcb_add);
            }
            if(!$admin->hasPermission($saveassessmentcb_view)) {
                $admin_role->givePermissionTo($saveassessmentcb_view);
            }
            if(!$admin->hasPermission($saveassessmentcb_edit)) {
                $admin_role->givePermissionTo($saveassessmentcb_edit);
            }
            if(!$admin->hasPermission($saveassessmentcb_delete)) {
                $admin_role->givePermissionTo($saveassessmentcb_delete);
            }

            $certificateexportcb_add = Permission::firstOrCreate([
                'name' => 'add-certificateexportcb'
            ]);
            $certificateexportcb_view = Permission::firstOrCreate([
                'name' => 'view-certificateexportcb'
            ]);
            $certificateexportcb_edit = Permission::firstOrCreate([
                'name' => 'edit-certificateexportcb'
            ]);
            $certificateexportcb_delete = Permission::firstOrCreate([
                'name' => 'delete-certificateexportcb'
            ]);

            if(!$admin->hasPermission($certificateexportcb_add)) {
                $admin_role->givePermissionTo($certificateexportcb_add);
            }
            if(!$admin->hasPermission($certificateexportcb_view)) {
                $admin_role->givePermissionTo($certificateexportcb_view);
            }
            if(!$admin->hasPermission($certificateexportcb_edit)) {
                $admin_role->givePermissionTo($certificateexportcb_edit);
            }
            if(!$admin->hasPermission($certificateexportcb_delete)) {
                $admin_role->givePermissionTo($certificateexportcb_delete);
            }

            $standard_formulas_add = Permission::firstOrCreate([
                'name' => 'add-standardformulas'
            ]);
            $standard_formulas_view = Permission::firstOrCreate([
                'name' => 'view-standardformulas'
            ]);
            $standard_formulas_edit = Permission::firstOrCreate([
                'name' => 'edit-standardformulas'
            ]);
            $standard_formulas_delete = Permission::firstOrCreate([
                'name' => 'delete-standardformulas'
            ]);

            if(!$admin->hasPermission($standard_formulas_add)) {
                $admin_role->givePermissionTo($standard_formulas_add);
            }
            if(!$admin->hasPermission($standard_formulas_view)) {
                $admin_role->givePermissionTo($standard_formulas_view);
            }
            if(!$admin->hasPermission($standard_formulas_edit)) {
                $admin_role->givePermissionTo($standard_formulas_edit);
            }
            if(!$admin->hasPermission($standard_formulas_delete)) {
                $admin_role->givePermissionTo($standard_formulas_delete);
            }

            $costestimation_add = Permission::firstOrCreate([
                'name' => 'add-costestimation'
            ]);
            $costestimation_view = Permission::firstOrCreate([
                'name' => 'view-costestimation'
            ]);
            $costestimation_edit = Permission::firstOrCreate([
                'name' => 'edit-costestimation'
            ]);
            $costestimation_delete = Permission::firstOrCreate([
                'name' => 'delete-costestimation'
            ]);

            if(!$admin->hasPermission($costestimation_add)) {
                $admin_role->givePermissionTo($costestimation_add);
            }
            if(!$admin->hasPermission($costestimation_view)) {
                $admin_role->givePermissionTo($costestimation_view);
            }
            if(!$admin->hasPermission($costestimation_edit)) {
                $admin_role->givePermissionTo($costestimation_edit);
            }
            if(!$admin->hasPermission($costestimation_delete)) {
                $admin_role->givePermissionTo($costestimation_delete);
            }

            // ics
            $ics_add = Permission::firstOrCreate([
                'name' => 'add-ics'
            ]);
            $ics_view = Permission::firstOrCreate([
                'name' => 'view-ics'
            ]);
            $ics_edit = Permission::firstOrCreate([
                'name' => 'edit-ics'
            ]);
            $ics_delete = Permission::firstOrCreate([
                'name' => 'delete-ics'
            ]);

            if(!$admin->hasPermission($ics_add)) {
                $admin_role->givePermissionTo($ics_add);
            }
            if(!$admin->hasPermission($ics_view)) {
                $admin_role->givePermissionTo($ics_view);
            }
            if(!$admin->hasPermission($ics_edit)) {
                $admin_role->givePermissionTo($ics_edit);
            }
            if(!$admin->hasPermission($ics_delete)) {
                $admin_role->givePermissionTo($ics_delete);
            }

            $certificateexportlab_add = Permission::firstOrCreate([
                'name' => 'add-certificateexportlab'
            ]);
            $certificateexportlab_view = Permission::firstOrCreate([
                'name' => 'view-certificateexportlab'
            ]);
            $certificateexportlab_edit = Permission::firstOrCreate([
                'name' => 'edit-certificateexportlab'
            ]);
            $certificateexportlab_delete = Permission::firstOrCreate([
                'name' => 'delete-certificateexportlab'
            ]);

            if(!$admin->hasPermission($certificateexportlab_add)) {
                $admin_role->givePermissionTo($certificateexportlab_add);
            }
            if(!$admin->hasPermission($certificateexportlab_view)) {
                $admin_role->givePermissionTo($certificateexportlab_view);
            }
            if(!$admin->hasPermission($certificateexportlab_edit)) {
                $admin_role->givePermissionTo($certificateexportlab_edit);
            }
            if(!$admin->hasPermission($certificateexportlab_delete)) {
                $admin_role->givePermissionTo($certificateexportlab_delete);
            }

        //รับคำขอการนำเข้าผลิตภัณฑ์เพื่อนำมาใช้เอง (ตามมาตรา 21)
        $accept21own_import_view   = Permission::firstOrCreate(['name' => 'view-accept21own-import']);
        $accept21own_import_edit   = Permission::firstOrCreate(['name' => 'edit-accept21own-import']);

        if (!$admin->hasPermission($accept21own_import_view)) {
          $admin_role->givePermissionTo($accept21own_import_view);
        }
        if (!$admin->hasPermission($accept21own_import_edit)) {
          $admin_role->givePermissionTo($accept21own_import_edit);
        }

        //รับแจ้งปริมาณการนำเข้าเพื่อนำมาใช้เอง (ตามมาตรา 21)
        $report21own_import_view   = Permission::firstOrCreate(['name' => 'view-report21own-import']);
        $report21own_import_edit   = Permission::firstOrCreate(['name' => 'edit-report21own-import']);

        if (!$admin->hasPermission($report21own_import_view)) {
          $admin_role->givePermissionTo($report21own_import_view);
        }
        if (!$admin->hasPermission($report21own_import_edit)) {
          $admin_role->givePermissionTo($report21own_import_edit);
        }

         //ข้อมูลผู้ประกอบการ (สก.)
        $soko_add = Permission::firstOrCreate([
            'name' => 'add-soko'
        ]);
        $soko_view = Permission::firstOrCreate([
            'name' => 'view-soko'
        ]);
        $soko_edit = Permission::firstOrCreate([
            'name' => 'edit-soko'
        ]);
        $soko_delete = Permission::firstOrCreate([
            'name' => 'delete-soko'
        ]);

        if(!$admin->hasPermission($soko_add)) {
            $admin_role->givePermissionTo($soko_add);
        }
        if(!$admin->hasPermission($soko_view)) {
            $admin_role->givePermissionTo($soko_view);
        }
        if(!$admin->hasPermission($soko_edit)) {
            $admin_role->givePermissionTo($soko_edit);
        }
        if(!$admin->hasPermission($soko_delete)) {
            $admin_role->givePermissionTo($soko_delete);
        }


        $set_attach_add = Permission::firstOrCreate([
            'name' => 'add-set_attach'
        ]);
        $set_attach_view = Permission::firstOrCreate([
            'name' => 'view-set_attach'
        ]);
        $set_attach_edit = Permission::firstOrCreate([
            'name' => 'edit-set_attach'
        ]);
        $set_attach_delete = Permission::firstOrCreate([
            'name' => 'delete-set_attach'
        ]);

        if(!$admin->hasPermission($set_attach_add)) {
            $admin_role->givePermissionTo($set_attach_add);
        }
        if(!$admin->hasPermission($set_attach_view)) {
            $admin_role->givePermissionTo($set_attach_view);
        }
        if(!$admin->hasPermission($set_attach_edit)) {
            $admin_role->givePermissionTo($set_attach_edit);
        }
        if(!$admin->hasPermission($set_attach_delete)) {
            $admin_role->givePermissionTo($set_attach_delete);
        }


        $import_comment_add = Permission::firstOrCreate([
            'name' => 'add-import_comment'
        ]);
        $import_comment_view = Permission::firstOrCreate([
            'name' => 'view-import_comment'
        ]);
        $import_comment_edit = Permission::firstOrCreate([
            'name' => 'edit-import_comment'
        ]);
        $import_comment_delete = Permission::firstOrCreate([
            'name' => 'delete-import_comment'
        ]);

        if(!$admin->hasPermission($import_comment_add)) {
            $admin_role->givePermissionTo($import_comment_add);
        }
        if(!$admin->hasPermission($import_comment_view)) {
            $admin_role->givePermissionTo($import_comment_view);
        }
        if(!$admin->hasPermission($import_comment_edit)) {
            $admin_role->givePermissionTo($import_comment_edit);
        }
        if(!$admin->hasPermission($import_comment_delete)) {
            $admin_role->givePermissionTo($import_comment_delete);
        }


        $note_std_draft_add = Permission::firstOrCreate([
            'name' => 'add-note-std-draft'
        ]);
        $note_std_draft_view = Permission::firstOrCreate([
            'name' => 'view-note-std-draft'
        ]);
        $note_std_draft_edit = Permission::firstOrCreate([
            'name' => 'edit-note-std-draft'
        ]);
        $note_std_draft_delete = Permission::firstOrCreate([
            'name' => 'delete-note-std-draft'
        ]);

        if(!$admin->hasPermission($note_std_draft_add)) {
            $admin_role->givePermissionTo($note_std_draft_add);
        }
        if(!$admin->hasPermission($note_std_draft_view)) {
            $admin_role->givePermissionTo($note_std_draft_view);
        }
        if(!$admin->hasPermission($note_std_draft_edit)) {
            $admin_role->givePermissionTo($note_std_draft_edit);
        }
        if(!$admin->hasPermission($note_std_draft_delete)) {
            $admin_role->givePermissionTo($note_std_draft_delete);
        }

        $listen_std_draft_add = Permission::firstOrCreate([
            'name' => 'add-listen-std-draft'
        ]);
        $listen_std_draft_view = Permission::firstOrCreate([
            'name' => 'view-listen-std-draft'
        ]);
        $listen_std_draft_edit = Permission::firstOrCreate([
            'name' => 'edit-listen-std-draft'
        ]);
        $listen_std_draft_delete = Permission::firstOrCreate([
            'name' => 'delete-listen-std-draft'
        ]);

        if(!$admin->hasPermission($listen_std_draft_add)) {
            $admin_role->givePermissionTo($listen_std_draft_add);
        }
        if(!$admin->hasPermission($listen_std_draft_view)) {
            $admin_role->givePermissionTo($listen_std_draft_view);
        }
        if(!$admin->hasPermission($listen_std_draft_edit)) {
            $admin_role->givePermissionTo($listen_std_draft_edit);
        }
        if(!$admin->hasPermission($listen_std_draft_delete)) {
            $admin_role->givePermissionTo($listen_std_draft_delete);
        }

        $commentstandardreviewsanddrafts_add    = Permission::firstOrCreate(['name' => 'add-commentstandardreviewsanddrafts']);
      $commentstandardreviewsanddrafts_view   = Permission::firstOrCreate(['name' => 'view-commentstandardreviewsanddrafts']);
      $commentstandardreviewsanddrafts_edit   = Permission::firstOrCreate(['name' => 'edit-commentstandardreviewsanddrafts']);
      $commentstandardreviewsanddrafts_delete = Permission::firstOrCreate(['name' => 'delete-commentstandardreviewsanddrafts']);

      if (!$admin->hasPermission($commentstandardreviewsanddrafts_add)) {
        $admin_role->givePermissionTo($commentstandardreviewsanddrafts_add);
      }
      if (!$admin->hasPermission($commentstandardreviewsanddrafts_view)) {
        $admin_role->givePermissionTo($commentstandardreviewsanddrafts_view);
      }
      if (!$admin->hasPermission($commentstandardreviewsanddrafts_edit)) {
        $admin_role->givePermissionTo($commentstandardreviewsanddrafts_edit);
      }
      if (!$admin->hasPermission($commentstandardreviewsanddrafts_delete)) {
        $admin_role->givePermissionTo($commentstandardreviewsanddrafts_delete);
      }

      $listenstddraftresults_add    = Permission::firstOrCreate(['name' => 'add-listenstddraftresults']);
      $listenstddraftresults_view   = Permission::firstOrCreate(['name' => 'view-listenstddraftresults']);
      $listenstddraftresults_edit   = Permission::firstOrCreate(['name' => 'edit-listenstddraftresults']);
      $listenstddraftresults_delete = Permission::firstOrCreate(['name' => 'delete-listenstddraftresults']);

      if (!$admin->hasPermission($listenstddraftresults_add)) {
        $admin_role->givePermissionTo($listenstddraftresults_add);
      }
      if (!$admin->hasPermission($listenstddraftresults_view)) {
        $admin_role->givePermissionTo($listenstddraftresults_view);
      }
      if (!$admin->hasPermission($listenstddraftresults_edit)) {
        $admin_role->givePermissionTo($listenstddraftresults_edit);
      }
      if (!$admin->hasPermission($listenstddraftresults_delete)) {
        $admin_role->givePermissionTo($listenstddraftresults_delete);
      }

      $feewaiver_add    = Permission::firstOrCreate(['name' => 'add-feewaiver']);
      $feewaiver_view   = Permission::firstOrCreate(['name' => 'view-feewaiver']);
      $feewaiver_delete = Permission::firstOrCreate(['name' => 'delete-feewaiver']);

      if (!$admin->hasPermission($feewaiver_add)) {
        $admin_role->givePermissionTo($feewaiver_add);
      }
      if (!$admin->hasPermission($feewaiver_view)) {
        $admin_role->givePermissionTo($feewaiver_view);
      }
      if (!$admin->hasPermission($feewaiver_delete)) {
        $admin_role->givePermissionTo($feewaiver_delete);
      }


        $signers_add = Permission::firstOrCreate([
            'name' => 'add-signers'
        ]);
        $signers_view = Permission::firstOrCreate([
            'name' => 'view-signers'
        ]);
        $signers_edit = Permission::firstOrCreate([
            'name' => 'edit-signers'
        ]);
        $signers_delete = Permission::firstOrCreate([
            'name' => 'delete-signers'
        ]);

        if(!$admin->hasPermission($signers_add)) {
            $admin_role->givePermissionTo($signers_add);
        }
        if(!$admin->hasPermission($signers_view)) {
            $admin_role->givePermissionTo($signers_view);
        }
        if(!$admin->hasPermission($signers_edit)) {
            $admin_role->givePermissionTo($signers_edit);
        }
        if(!$admin->hasPermission($signers_delete)) {
            $admin_role->givePermissionTo($signers_delete);
        }


      $signers_add    = Permission::firstOrCreate(['name' => 'add-signers']);
      $signers_view   = Permission::firstOrCreate(['name' => 'view-signers']);
      $signers_edit   = Permission::firstOrCreate(['name' => 'edit-signers']);
      $signers_delete = Permission::firstOrCreate(['name' => 'delete-signers']);

      if (!$admin->hasPermission($signers_add)) {
        $admin_role->givePermissionTo($signers_add);
      }
      if (!$admin->hasPermission($signers_view)) {
        $admin_role->givePermissionTo($signers_view);
      }
      if (!$admin->hasPermission($signers_edit)) {
        $admin_role->givePermissionTo($signers_edit);
      }
      if (!$admin->hasPermission($signers_delete)) {
        $admin_role->givePermissionTo($signers_delete);
      }

      $qrcodes_add    = Permission::firstOrCreate(['name' => 'add-qrcodes']);
      $qrcodes_view   = Permission::firstOrCreate(['name' => 'view-qrcodes']);
      $qrcodes_edit   = Permission::firstOrCreate(['name' => 'edit-qrcodes']);
      $qrcodes_delete = Permission::firstOrCreate(['name' => 'delete-qrcodes']);

      if (!$admin->hasPermission($qrcodes_add)) {
        $admin_role->givePermissionTo($qrcodes_add);
      }
      if (!$admin->hasPermission($qrcodes_view)) {
        $admin_role->givePermissionTo($qrcodes_view);
      }
      if (!$admin->hasPermission($qrcodes_edit)) {
        $admin_role->givePermissionTo($qrcodes_edit);
      }
      if (!$admin->hasPermission($qrcodes_delete)) {
        $admin_role->givePermissionTo($qrcodes_delete);
      }

      $ssourl_add    = Permission::firstOrCreate(['name' => 'add-ssourl']);
      $ssourl_view   = Permission::firstOrCreate(['name' => 'view-ssourl']);
      $ssourl_edit   = Permission::firstOrCreate(['name' => 'edit-ssourl']);
      $ssourl_delete = Permission::firstOrCreate(['name' => 'delete-ssourl']);

      if (!$admin->hasPermission($ssourl_add)) {
        $admin_role->givePermissionTo($ssourl_add);
      }
      if (!$admin->hasPermission($ssourl_view)) {
        $admin_role->givePermissionTo($ssourl_view);
      }
      if (!$admin->hasPermission($ssourl_edit)) {
        $admin_role->givePermissionTo($ssourl_edit);
      }
      if (!$admin->hasPermission($ssourl_delete)) {
        $admin_role->givePermissionTo($ssourl_delete);
      }

      $ssourlgroup_add    = Permission::firstOrCreate(['name' => 'add-ssourlgroup']);
      $ssourlgroup_view   = Permission::firstOrCreate(['name' => 'view-ssourlgroup']);
      $ssourlgroup_edit   = Permission::firstOrCreate(['name' => 'edit-ssourlgroup']);
      $ssourlgroup_delete = Permission::firstOrCreate(['name' => 'delete-ssourlgroup']);

      if (!$admin->hasPermission($ssourlgroup_add)) {
        $admin_role->givePermissionTo($ssourlgroup_add);
      }
      if (!$admin->hasPermission($ssourlgroup_view)) {
        $admin_role->givePermissionTo($ssourlgroup_view);
      }
      if (!$admin->hasPermission($ssourlgroup_edit)) {
        $admin_role->givePermissionTo($ssourlgroup_edit);
      }
      if (!$admin->hasPermission($ssourlgroup_delete)) {
        $admin_role->givePermissionTo($ssourlgroup_delete);
      }

      $ws_log_view = Permission::firstOrCreate(['name' => 'view-ws-log']);

      if (!$admin->hasPermission($ws_log_view)) {
        $admin_role->givePermissionTo($ws_log_view);
      }

      $user_sso_add    = Permission::firstOrCreate(['name' => 'add-user-sso']);
      $user_sso_view   = Permission::firstOrCreate(['name' => 'view-user-sso']);
      $user_sso_edit   = Permission::firstOrCreate(['name' => 'edit-user-sso']);
      $user_sso_delete = Permission::firstOrCreate(['name' => 'delete-user-sso']);

      if (!$admin->hasPermission($user_sso_add)) {
        $admin_role->givePermissionTo($user_sso_add);
      }
      if (!$admin->hasPermission($user_sso_view)) {
        $admin_role->givePermissionTo($user_sso_view);
      }
      if (!$admin->hasPermission($user_sso_edit)) {
        $admin_role->givePermissionTo($user_sso_edit);
      }
      if (!$admin->hasPermission($user_sso_delete)) {
        $admin_role->givePermissionTo($user_sso_delete);
      }

      $expertgroups_add    = Permission::firstOrCreate(['name' => 'add-expertgroups']);
      $expertgroups_view   = Permission::firstOrCreate(['name' => 'view-expertgroups']);
      $expertgroups_edit   = Permission::firstOrCreate(['name' => 'edit-expertgroups']);
      $expertgroups_delete = Permission::firstOrCreate(['name' => 'delete-expertgroups']);

      if (!$admin->hasPermission($expertgroups_add)) {
        $admin_role->givePermissionTo($expertgroups_add);
      }
      if (!$admin->hasPermission($expertgroups_view)) {
        $admin_role->givePermissionTo($expertgroups_view);
      }
      if (!$admin->hasPermission($expertgroups_edit)) {
        $admin_role->givePermissionTo($expertgroups_edit);
      }
      if (!$admin->hasPermission($expertgroups_delete)) {
        $admin_role->givePermissionTo($expertgroups_delete);
      }

        //จัดการข้อมูล Api Service
        $api_service_add = Permission::firstOrCreate([
            'name' => 'add-api-service'
        ]);
        $api_service_view = Permission::firstOrCreate([
            'name' => 'view-api-service'
        ]);
        $api_service_edit = Permission::firstOrCreate([
            'name' => 'edit-api-service'
        ]);
        $api_service_delete = Permission::firstOrCreate([
            'name' => 'delete-api-service'
        ]);

        if(!$admin->hasPermission($api_service_add)) {
            $admin_role->givePermissionTo($api_service_add);
        }
        if(!$admin->hasPermission($api_service_view)) {
            $admin_role->givePermissionTo($api_service_view);
        }
        if(!$admin->hasPermission($api_service_edit)) {
            $admin_role->givePermissionTo($api_service_edit);
        }
        if(!$admin->hasPermission($api_service_delete)) {
            $admin_role->givePermissionTo($api_service_delete);
        }

        //ทะเบียนผู้เชี่ยวชาญ
        $experts_add    = Permission::firstOrCreate(['name' => 'add-experts']);
        $experts_view   = Permission::firstOrCreate(['name' => 'view-experts']);
        $experts_edit   = Permission::firstOrCreate(['name' => 'edit-experts']);
        $experts_delete = Permission::firstOrCreate(['name' => 'delete-experts']);

        if (!$admin->hasPermission($experts_add)) {
          $admin_role->givePermissionTo($experts_add);
        }
        if (!$admin->hasPermission($experts_view)) {
          $admin_role->givePermissionTo($experts_view);
        }
        if (!$admin->hasPermission($experts_edit)) {
          $admin_role->givePermissionTo($experts_edit);
        }
        if (!$admin->hasPermission($experts_delete)) {
          $admin_role->givePermissionTo($experts_delete);
        }

        //รับคำขอเป็นหน่วยตรวจสอบ (IB)
        $accept_inspection_unit_add    = Permission::firstOrCreate(['name' => 'add-accept-inspection-unit']);
        $accept_inspection_unit_view   = Permission::firstOrCreate(['name' => 'view-accept-inspection-unit']);
        $accept_inspection_unit_edit   = Permission::firstOrCreate(['name' => 'edit-accept-inspection-unit']);
        $accept_inspection_unit_delete = Permission::firstOrCreate(['name' => 'delete-accept-inspection-unit']);

        if (!$admin->hasPermission($accept_inspection_unit_add)) {
          $admin_role->givePermissionTo($accept_inspection_unit_add);
        }
        if (!$admin->hasPermission($accept_inspection_unit_view)) {
          $admin_role->givePermissionTo($accept_inspection_unit_view);
        }
        if (!$admin->hasPermission($accept_inspection_unit_edit)) {
          $admin_role->givePermissionTo($accept_inspection_unit_edit);
        }
        if (!$admin->hasPermission($accept_inspection_unit_delete)) {
          $admin_role->givePermissionTo($accept_inspection_unit_delete);
        }

        //รับคำขอเป็นผู้ตรวจสอบ (LAB)
        $application_lab_accept_add    = Permission::firstOrCreate(['name' => 'add-application-lab-accept']);
        $application_lab_accept_view   = Permission::firstOrCreate(['name' => 'view-application-lab-accept']);
        $application_lab_accept_edit   = Permission::firstOrCreate(['name' => 'edit-application-lab-accept']);
        $application_lab_accept_delete = Permission::firstOrCreate(['name' => 'delete-application-lab-accept']);
        $application_lab_accept_other = Permission::firstOrCreate(['name' => 'other-application-lab-accept']);
        $application_lab_accept_assign_work = Permission::firstOrCreate(['name' => 'assign_work-application-lab-accept']);
        $application_lab_accept_view_all = Permission::firstOrCreate(['name' => 'view_all-application-lab-accept']);

        if (!$admin->hasPermission($application_lab_accept_add)) {
          $admin_role->givePermissionTo($application_lab_accept_add);
        }
        if (!$admin->hasPermission($application_lab_accept_view)) {
          $admin_role->givePermissionTo($application_lab_accept_view);
        }
        if (!$admin->hasPermission($application_lab_accept_edit)) {
          $admin_role->givePermissionTo($application_lab_accept_edit);
        }
        if (!$admin->hasPermission($application_lab_accept_delete)) {
          $admin_role->givePermissionTo($application_lab_accept_delete);
        }
        if (!$admin->hasPermission($application_lab_accept_other)) {
          $admin_role->givePermissionTo($application_lab_accept_other);
        }
        if (!$admin->hasPermission($application_lab_accept_assign_work)) {
          $admin_role->givePermissionTo($application_lab_accept_assign_work);
        }
        if (!$admin->hasPermission($application_lab_accept_view_all)) {
          $admin_role->givePermissionTo($application_lab_accept_view_all);
        }

        //ผู้เชี่ยวชาญ
        $registerexperts_add    = Permission::firstOrCreate(['name' => 'add-registerexperts']);
        $registerexperts_view   = Permission::firstOrCreate(['name' => 'view-registerexperts']);
        $registerexperts_edit   = Permission::firstOrCreate(['name' => 'edit-registerexperts']);
        $registerexperts_delete = Permission::firstOrCreate(['name' => 'delete-registerexperts']);
        $registerexperts_assign_work = Permission::firstOrCreate(['name' => 'assign_work-registerexperts']);

        if (!$admin->hasPermission($registerexperts_add)) {
            $admin_role->givePermissionTo($registerexperts_add);
        }
        if (!$admin->hasPermission($registerexperts_view)) {
            $admin_role->givePermissionTo($registerexperts_view);
        }
        if (!$admin->hasPermission($registerexperts_edit)) {
            $admin_role->givePermissionTo($registerexperts_edit);
        }
        if (!$admin->hasPermission($registerexperts_delete)) {
            $admin_role->givePermissionTo($registerexperts_delete);
        }
        if (!$admin->hasPermission($registerexperts_assign_work)) {
            $admin_role->givePermissionTo($registerexperts_assign_work);
        }

        //จัดการชื่อผลิตภัณฑ์อุสาหกรรม
        $standard_product_name_add    = Permission::firstOrCreate(['name' => 'add-standardproduct-name']);
        $standard_product_name_view   = Permission::firstOrCreate(['name' => 'view-standardproduct-name']);
        $standard_product_name_edit   = Permission::firstOrCreate(['name' => 'edit-standardproduct-name']);
        $standard_product_name_delete = Permission::firstOrCreate(['name' => 'delete-standardproduct-name']);
        $standard_product_name_assign_work = Permission::firstOrCreate(['name' => 'assign_work-standardproduct-name']);

        if (!$admin->hasPermission($standard_product_name_add)) {
            $admin_role->givePermissionTo($standard_product_name_add);
        }
        if (!$admin->hasPermission($standard_product_name_view)) {
            $admin_role->givePermissionTo($standard_product_name_view);
        }
        if (!$admin->hasPermission($standard_product_name_edit)) {
            $admin_role->givePermissionTo($standard_product_name_edit);
        }
        if (!$admin->hasPermission($standard_product_name_delete)) {
            $admin_role->givePermissionTo($standard_product_name_delete);
        }
        if (!$admin->hasPermission($standard_product_name_assign_work)) {
            $admin_role->givePermissionTo($standard_product_name_assign_work);
        }


        $standardtypes_add    = Permission::firstOrCreate(['name' => 'add-standardtypes']);
        $standardtypes_view   = Permission::firstOrCreate(['name' => 'view-standardtypes']);
        $standardtypes_edit   = Permission::firstOrCreate(['name' => 'edit-standardtypes']);
        $standardtypes_delete = Permission::firstOrCreate(['name' => 'delete-standardtypes']);

        if (!$admin->hasPermission($standardtypes_add)) {
            $admin_role->givePermissionTo($standardtypes_add);
        }
        if (!$admin->hasPermission($standardtypes_view)) {
            $admin_role->givePermissionTo($standardtypes_view);
        }
        if (!$admin->hasPermission($standardtypes_edit)) {
            $admin_role->givePermissionTo($standardtypes_edit);
        }
        if (!$admin->hasPermission($standardtypes_delete)) {
            $admin_role->givePermissionTo($standardtypes_delete);
        }

        $configs_evidence_systems_add    = Permission::firstOrCreate(['name' => 'add-configs-evidence-systems']);
        $configs_evidence_systems_view   = Permission::firstOrCreate(['name' => 'view-configs-evidence-systems']);
        $configs_evidence_systems_edit   = Permission::firstOrCreate(['name' => 'edit-configs-evidence-systems']);
        $configs_evidence_systems_delete = Permission::firstOrCreate(['name' => 'delete-configs-evidence-systems']);

        if (!$admin->hasPermission($configs_evidence_systems_add)) {
            $admin_role->givePermissionTo($configs_evidence_systems_add);
        }
        if (!$admin->hasPermission($configs_evidence_systems_view)) {
            $admin_role->givePermissionTo($configs_evidence_systems_view);
        }
        if (!$admin->hasPermission($configs_evidence_systems_edit)) {
            $admin_role->givePermissionTo($configs_evidence_systems_edit);
        }
        if (!$admin->hasPermission($configs_evidence_systems_delete)) {
            $admin_role->givePermissionTo($configs_evidence_systems_delete);
        }

        $configs_evidence_groups_add    = Permission::firstOrCreate(['name' => 'add-configs-evidence-groups']);
        $configs_evidence_groups_view   = Permission::firstOrCreate(['name' => 'view-configs-evidence-groups']);
        $configs_evidence_groups_edit   = Permission::firstOrCreate(['name' => 'edit-configs-evidence-groups']);
        $configs_evidence_groups_delete = Permission::firstOrCreate(['name' => 'delete-configs-evidence-groups']);

        if (!$admin->hasPermission($configs_evidence_groups_add)) {
            $admin_role->givePermissionTo($configs_evidence_groups_add);
        }
        if (!$admin->hasPermission($configs_evidence_groups_view)) {
            $admin_role->givePermissionTo($configs_evidence_groups_view);
        }
        if (!$admin->hasPermission($configs_evidence_groups_edit)) {
            $admin_role->givePermissionTo($configs_evidence_groups_edit);
        }
        if (!$admin->hasPermission($configs_evidence_groups_delete)) {
            $admin_role->givePermissionTo($configs_evidence_groups_delete);
        }


        $standardsoffers_add         = Permission::firstOrCreate(['name' => 'add-standardsoffers']);
        $standardsoffers_view        = Permission::firstOrCreate(['name' => 'view-standardsoffers']);
        $standardsoffers_edit        = Permission::firstOrCreate(['name' => 'edit-standardsoffers']);
        $standardsoffers_delete      = Permission::firstOrCreate(['name' => 'delete-standardsoffers']);
        $standardsoffers_other       = Permission::firstOrCreate(['name' => 'other-standardsoffers']);
        $standardsoffers_assign_work = Permission::firstOrCreate(['name' => 'assign_work-standardsoffers']);

        if (!$admin->hasPermission($standardsoffers_add)) {
            $admin_role->givePermissionTo($standardsoffers_add);
        }
        if (!$admin->hasPermission($standardsoffers_view)) {
            $admin_role->givePermissionTo($standardsoffers_view);
        }
        if (!$admin->hasPermission($standardsoffers_edit)) {
            $admin_role->givePermissionTo($standardsoffers_edit);
        }
        if (!$admin->hasPermission($standardsoffers_delete)) {
            $admin_role->givePermissionTo($standardsoffers_delete);
        }
        if (!$admin->hasPermission($standardsoffers_other)) {
            $admin_role->givePermissionTo($standardsoffers_other);
        }
        if (!$admin->hasPermission($standardsoffers_assign_work)) {
            $admin_role->givePermissionTo($standardsoffers_assign_work);
        }

        $standarddrafts_add         = Permission::firstOrCreate(['name' => 'add-standarddrafts']);
        $standarddrafts_view        = Permission::firstOrCreate(['name' => 'view-standarddrafts']);
        $standarddrafts_edit        = Permission::firstOrCreate(['name' => 'edit-standarddrafts']);
        $standarddrafts_delete      = Permission::firstOrCreate(['name' => 'delete-standarddrafts']);
        $standarddrafts_other       = Permission::firstOrCreate(['name' => 'other-standarddrafts']);
        $standarddrafts_assign_work = Permission::firstOrCreate(['name' => 'assign_work-standarddrafts']);

        if (!$admin->hasPermission($standarddrafts_add)) {
            $admin_role->givePermissionTo($standarddrafts_add);
        }
        if (!$admin->hasPermission($standarddrafts_view)) {
            $admin_role->givePermissionTo($standarddrafts_view);
        }
        if (!$admin->hasPermission($standarddrafts_edit)) {
            $admin_role->givePermissionTo($standarddrafts_edit);
        }
        if (!$admin->hasPermission($standarddrafts_delete)) {
            $admin_role->givePermissionTo($standarddrafts_delete);
        }
        if (!$admin->hasPermission($standarddrafts_other)) {
            $admin_role->givePermissionTo($standarddrafts_other);
        }
        if (!$admin->hasPermission($standarddrafts_assign_work)) {
            $admin_role->givePermissionTo($standarddrafts_assign_work);
        }

        $standardplans_add    = Permission::firstOrCreate(['name' => 'add-standardplans']);
        $standardplans_view   = Permission::firstOrCreate(['name' => 'view-standardplans']);
        $standardplans_edit   = Permission::firstOrCreate(['name' => 'edit-standardplans']);
        $standardplans_delete = Permission::firstOrCreate(['name' => 'delete-standardplans']);

        if (!$admin->hasPermission($standardplans_add)) {
            $admin_role->givePermissionTo($standardplans_add);
        }
        if (!$admin->hasPermission($standardplans_view)) {
            $admin_role->givePermissionTo($standardplans_view);
        }
        if (!$admin->hasPermission($standardplans_edit)) {
            $admin_role->givePermissionTo($standardplans_edit);
        }
        if (!$admin->hasPermission($standardplans_delete)) {
            $admin_role->givePermissionTo($standardplans_delete);
        }

        $standardconfirmplans_add    = Permission::firstOrCreate(['name' => 'add-standardconfirmplans']);
        $standardconfirmplans_view   = Permission::firstOrCreate(['name' => 'view-standardconfirmplans']);
        $standardconfirmplans_edit   = Permission::firstOrCreate(['name' => 'edit-standardconfirmplans']);
        $standardconfirmplans_delete = Permission::firstOrCreate(['name' => 'delete-standardconfirmplans']);

        if (!$admin->hasPermission($standardconfirmplans_add)) {
            $admin_role->givePermissionTo($standardconfirmplans_add);
        }
        if (!$admin->hasPermission($standardconfirmplans_view)) {
            $admin_role->givePermissionTo($standardconfirmplans_view);
        }
        if (!$admin->hasPermission($standardconfirmplans_edit)) {
            $admin_role->givePermissionTo($standardconfirmplans_edit);
        }
        if (!$admin->hasPermission($standardconfirmplans_delete)) {
            $admin_role->givePermissionTo($standardconfirmplans_delete);
        }

        $branchgroup_add    = Permission::firstOrCreate(['name' => 'add-branchgroup']);
        $branchgroup_view   = Permission::firstOrCreate(['name' => 'view-branchgroup']);
        $branchgroup_edit   = Permission::firstOrCreate(['name' => 'edit-branchgroup']);
        $branchgroup_delete = Permission::firstOrCreate(['name' => 'delete-branchgroup']);

        if (!$admin->hasPermission($branchgroup_add)) {
            $admin_role->givePermissionTo($branchgroup_add);
        }
        if (!$admin->hasPermission($branchgroup_view)) {
            $admin_role->givePermissionTo($branchgroup_view);
        }
        if (!$admin->hasPermission($branchgroup_edit)) {
            $admin_role->givePermissionTo($branchgroup_edit);
        }
        if (!$admin->hasPermission($branchgroup_delete)) {
            $admin_role->givePermissionTo($branchgroup_delete);
        }

        $branch_add    = Permission::firstOrCreate(['name' => 'add-branch']);
        $branch_view   = Permission::firstOrCreate(['name' => 'view-branch']);
        $branch_edit   = Permission::firstOrCreate(['name' => 'edit-branch']);
        $branch_delete = Permission::firstOrCreate(['name' => 'delete-branch']);

        if (!$admin->hasPermission($branch_add)) {
            $admin_role->givePermissionTo($branch_add);
        }
        if (!$admin->hasPermission($branch_view)) {
            $admin_role->givePermissionTo($branch_view);
        }
        if (!$admin->hasPermission($branch_edit)) {
            $admin_role->givePermissionTo($branch_edit);
        }
        if (!$admin->hasPermission($branch_delete)) {
            $admin_role->givePermissionTo($branch_delete);
        }

        $testmethod_add    = Permission::firstOrCreate(['name' => 'add-testmethod']);
        $testmethod_view   = Permission::firstOrCreate(['name' => 'view-testmethod']);
        $testmethod_edit   = Permission::firstOrCreate(['name' => 'edit-testmethod']);
        $testmethod_delete = Permission::firstOrCreate(['name' => 'delete-testmethod']);

        if (!$admin->hasPermission($testmethod_add)) {
            $admin_role->givePermissionTo($testmethod_add);
        }
        if (!$admin->hasPermission($testmethod_view)) {
            $admin_role->givePermissionTo($testmethod_view);
        }
        if (!$admin->hasPermission($testmethod_edit)) {
            $admin_role->givePermissionTo($testmethod_edit);
        }
        if (!$admin->hasPermission($testmethod_delete)) {
            $admin_role->givePermissionTo($testmethod_delete);
        }

        $testtools_add    = Permission::firstOrCreate(['name' => 'add-testtools']);
        $testtools_view   = Permission::firstOrCreate(['name' => 'view-testtools']);
        $testtools_edit   = Permission::firstOrCreate(['name' => 'edit-testtools']);
        $testtools_delete = Permission::firstOrCreate(['name' => 'delete-testtools']);

        if (!$admin->hasPermission($testtools_add)) {
            $admin_role->givePermissionTo($testtools_add);
        }
        if (!$admin->hasPermission($testtools_view)) {
            $admin_role->givePermissionTo($testtools_view);
        }
        if (!$admin->hasPermission($testtools_edit)) {
            $admin_role->givePermissionTo($testtools_edit);
        }
        if (!$admin->hasPermission($testtools_delete)) {
            $admin_role->givePermissionTo($testtools_delete);
        }

        $bsection5_unit_add    = Permission::firstOrCreate(['name' => 'add-bsection5-unit']);
        $bsection5_unit_view   = Permission::firstOrCreate(['name' => 'view-bsection5-unit']);
        $bsection5_unit_edit   = Permission::firstOrCreate(['name' => 'edit-bsection5-unit']);
        $bsection5_unit_delete = Permission::firstOrCreate(['name' => 'delete-bsection5-unit']);

        if (!$admin->hasPermission($bsection5_unit_add)) {
            $admin_role->givePermissionTo($bsection5_unit_add);
        }
        if (!$admin->hasPermission($bsection5_unit_view)) {
            $admin_role->givePermissionTo($bsection5_unit_view);
        }
        if (!$admin->hasPermission($bsection5_unit_edit)) {
            $admin_role->givePermissionTo($bsection5_unit_edit);
        }
        if (!$admin->hasPermission($bsection5_unit_delete)) {
            $admin_role->givePermissionTo($bsection5_unit_delete);
        }

        $bsection5_testitem_add    = Permission::firstOrCreate(['name' => 'add-bsection5-testitem']);
        $bsection5_testitem_view   = Permission::firstOrCreate(['name' => 'view-bsection5-testitem']);
        $bsection5_testitem_edit   = Permission::firstOrCreate(['name' => 'edit-bsection5-testitem']);
        $bsection5_testitem_delete = Permission::firstOrCreate(['name' => 'delete-bsection5-testitem']);

        if (!$admin->hasPermission($bsection5_testitem_add)) {
            $admin_role->givePermissionTo($bsection5_testitem_add);
        }
        if (!$admin->hasPermission($bsection5_testitem_view)) {
            $admin_role->givePermissionTo($bsection5_testitem_view);
        }
        if (!$admin->hasPermission($bsection5_testitem_edit)) {
            $admin_role->givePermissionTo($bsection5_testitem_edit);
        }
        if (!$admin->hasPermission($bsection5_testitem_delete)) {
            $admin_role->givePermissionTo($bsection5_testitem_delete);
        }

        $manage_lab_add              = Permission::firstOrCreate(['name' => 'add-manage-lab']);
        $manage_lab_view             = Permission::firstOrCreate(['name' => 'view-manage-lab']);
        $manage_lab_edit             = Permission::firstOrCreate(['name' => 'edit-manage-lab']);
        $manage_lab_delete           = Permission::firstOrCreate(['name' => 'delete-manage-lab']);
        $manage_lab_other            = Permission::firstOrCreate(['name' => 'other-manage-lab']);
        $manage_lab_sync_to_elicense = Permission::firstOrCreate(['name' => 'sync_to_elicense-manage-lab']);
        $manage_lab_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-manage-lab']);

        if (!$admin->hasPermission($manage_lab_add)) {
            $admin_role->givePermissionTo($manage_lab_add);
        }
        if (!$admin->hasPermission($manage_lab_view)) {
            $admin_role->givePermissionTo($manage_lab_view);
        }
        if (!$admin->hasPermission($manage_lab_edit)) {
            $admin_role->givePermissionTo($manage_lab_edit);
        }
        if (!$admin->hasPermission($manage_lab_delete)) {
            $admin_role->givePermissionTo($manage_lab_delete);
        }
        if (!$admin->hasPermission($manage_lab_other)) {
            $admin_role->givePermissionTo($manage_lab_other);
        }
        if (!$admin->hasPermission($manage_lab_sync_to_elicense)) {
            $admin_role->givePermissionTo($manage_lab_sync_to_elicense);
        }
        if (!$admin->hasPermission($manage_lab_poko_approve)) {
            $admin_role->givePermissionTo($manage_lab_poko_approve);
        }


        $application_lab_approve_add    = Permission::firstOrCreate(['name' => 'add-application-lab-approve']);
        $application_lab_approve_view   = Permission::firstOrCreate(['name' => 'view-application-lab-approve']);
        $application_lab_approve_edit   = Permission::firstOrCreate(['name' => 'edit-application-lab-approve']);
        $application_lab_approve_delete = Permission::firstOrCreate(['name' => 'delete-application-lab-approve']);
        $application_lab_approve_other = Permission::firstOrCreate(['name' => 'other-application-lab-approve']);
        $application_lab_approve_view_all = Permission::firstOrCreate(['name' => 'view_all-application-lab-approve']);

        if (!$admin->hasPermission($application_lab_approve_add)) {
            $admin_role->givePermissionTo($application_lab_approve_add);
        }
        if (!$admin->hasPermission($application_lab_approve_view)) {
            $admin_role->givePermissionTo($application_lab_approve_view);
        }
        if (!$admin->hasPermission($application_lab_approve_edit)) {
            $admin_role->givePermissionTo($application_lab_approve_edit);
        }
        if (!$admin->hasPermission($application_lab_approve_delete)) {
            $admin_role->givePermissionTo($application_lab_approve_delete);
        }
        if (!$admin->hasPermission($application_lab_approve_other)) {
            $admin_role->givePermissionTo($application_lab_approve_other);
        }
        if (!$admin->hasPermission($application_lab_approve_view_all)) {
            $admin_role->givePermissionTo($application_lab_approve_view_all);
        }

        $applicationlabaudit_add          = Permission::firstOrCreate(['name' => 'add-application-lab-audit']);
        $applicationlabaudit_view         = Permission::firstOrCreate(['name' => 'view-application-lab-audit']);
        $applicationlabaudit_edit         = Permission::firstOrCreate(['name' => 'edit-application-lab-audit']);
        $applicationlabaudit_delete       = Permission::firstOrCreate(['name' => 'delete-application-lab-audit']);
        $applicationlabaudit_other        = Permission::firstOrCreate(['name' => 'other-application-lab-audit']);
        $applicationlabaudit_view_all     = Permission::firstOrCreate(['name' => 'view_all-application-lab-audit']);
        $applicationlabaudit_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-application-lab-audit']);
        //$applicationlabaudit_assign_work = Permission::firstOrCreate(['name' => 'assign_work-application-lab-audit']);
        Permission::where('name', 'assign_work-application-lab-audit')->delete();;

        if (!$admin->hasPermission($applicationlabaudit_add)) {
            $admin_role->givePermissionTo($applicationlabaudit_add);
        }
        if (!$admin->hasPermission($applicationlabaudit_view)) {
            $admin_role->givePermissionTo($applicationlabaudit_view);
        }
        if (!$admin->hasPermission($applicationlabaudit_edit)) {
            $admin_role->givePermissionTo($applicationlabaudit_edit);
        }
        if (!$admin->hasPermission($applicationlabaudit_delete)) {
            $admin_role->givePermissionTo($applicationlabaudit_delete);
        }
        if (!$admin->hasPermission($applicationlabaudit_other)) {
          $admin_role->givePermissionTo($applicationlabaudit_other);
        }
        if (!$admin->hasPermission($applicationlabaudit_view_all)) {
          $admin_role->givePermissionTo($applicationlabaudit_view_all);
        }
        if (!$admin->hasPermission($applicationlabaudit_poko_approve)) {
          $admin_role->givePermissionTo($applicationlabaudit_poko_approve);
        }
        // if (!$admin->hasPermission($applicationlabaudit_assign_work)) {
        //   $admin_role->givePermissionTo($applicationlabaudit_assign_work);
        // }

        $bsection5_workgroup_add    = Permission::firstOrCreate(['name' => 'add-bsection5-workgroup']);
        $bsection5_workgroup_view   = Permission::firstOrCreate(['name' => 'view-bsection5-workgroup']);
        $bsection5_workgroup_edit   = Permission::firstOrCreate(['name' => 'edit-bsection5-workgroup']);
        $bsection5_workgroup_delete = Permission::firstOrCreate(['name' => 'delete-bsection5-workgroup']);

        if (!$admin->hasPermission($bsection5_workgroup_add)) {
            $admin_role->givePermissionTo($bsection5_workgroup_add);
        }
        if (!$admin->hasPermission($bsection5_workgroup_view)) {
            $admin_role->givePermissionTo($bsection5_workgroup_view);
        }
        if (!$admin->hasPermission($bsection5_workgroup_edit)) {
            $admin_role->givePermissionTo($bsection5_workgroup_edit);
        }
        if (!$admin->hasPermission($bsection5_workgroup_delete)) {
            $admin_role->givePermissionTo($bsection5_workgroup_delete);
        }

        $bsection5_workgroup_ib_add    = Permission::firstOrCreate(['name' => 'add-bsection5-workgroup-ib']);
        $bsection5_workgroup_ib_view   = Permission::firstOrCreate(['name' => 'view-bsection5-workgroup-ib']);
        $bsection5_workgroup_ib_edit   = Permission::firstOrCreate(['name' => 'edit-bsection5-workgroup-ib']);
        $bsection5_workgroup_ib_delete = Permission::firstOrCreate(['name' => 'delete-bsection5-workgroup-ib']);

        if (!$admin->hasPermission($bsection5_workgroup_ib_add)) {
            $admin_role->givePermissionTo($bsection5_workgroup_ib_add);
        }
        if (!$admin->hasPermission($bsection5_workgroup_ib_view)) {
            $admin_role->givePermissionTo($bsection5_workgroup_ib_view);
        }
        if (!$admin->hasPermission($bsection5_workgroup_ib_edit)) {
            $admin_role->givePermissionTo($bsection5_workgroup_ib_edit);
        }
        if (!$admin->hasPermission($bsection5_workgroup_ib_delete)) {
            $admin_role->givePermissionTo($bsection5_workgroup_ib_delete);
        }

        $sendcertificates_add    = Permission::firstOrCreate(['name' => 'add-sendcertificates']);
        $sendcertificates_view   = Permission::firstOrCreate(['name' => 'view-sendcertificates']);
        $sendcertificates_edit   = Permission::firstOrCreate(['name' => 'edit-sendcertificates']);
        $sendcertificates_delete = Permission::firstOrCreate(['name' => 'delete-sendcertificates']);

        if (!$admin->hasPermission($sendcertificates_add)) {
            $admin_role->givePermissionTo($sendcertificates_add);
        }
        if (!$admin->hasPermission($sendcertificates_view)) {
            $admin_role->givePermissionTo($sendcertificates_view);
        }
        if (!$admin->hasPermission($sendcertificates_edit)) {
            $admin_role->givePermissionTo($sendcertificates_edit);
        }
        if (!$admin->hasPermission($sendcertificates_delete)) {
            $admin_role->givePermissionTo($sendcertificates_delete);
        }

        $application_inspectors_audit_add          = Permission::firstOrCreate(['name' => 'add-application-inspectors-audit']);
        $application_inspectors_audit_view         = Permission::firstOrCreate(['name' => 'view-application-inspectors-audit']);
        $application_inspectors_audit_edit         = Permission::firstOrCreate(['name' => 'edit-application-inspectors-audit']);
        $application_inspectors_audit_delete       = Permission::firstOrCreate(['name' => 'delete-application-inspectors-audit']);
        $application_inspectors_audit_other        = Permission::firstOrCreate(['name' => 'other-application-inspectors-audit']);
        $application_inspectors_audit_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-application-inspectors-audit']);
        $application_inspectors_audit_view_all     = Permission::firstOrCreate(['name' => 'view_all-application-inspectors-audit']);

        if (!$admin->hasPermission($application_inspectors_audit_add)) {
            $admin_role->givePermissionTo($application_inspectors_audit_add);
        }
        if (!$admin->hasPermission($application_inspectors_audit_view)) {
            $admin_role->givePermissionTo($application_inspectors_audit_view);
        }
        if (!$admin->hasPermission($application_inspectors_audit_edit)) {
            $admin_role->givePermissionTo($application_inspectors_audit_edit);
        }
        if (!$admin->hasPermission($application_inspectors_audit_delete)) {
            $admin_role->givePermissionTo($application_inspectors_audit_delete);
        }
        if (!$admin->hasPermission($application_inspectors_audit_other)) {
            $admin_role->givePermissionTo($application_inspectors_audit_other);
        }
        if (!$admin->hasPermission($application_inspectors_audit_poko_approve)) {
            $admin_role->givePermissionTo($application_inspectors_audit_poko_approve);
        }
        if (!$admin->hasPermission($application_inspectors_audit_view_all)) {
            $admin_role->givePermissionTo($application_inspectors_audit_view_all);
        }


        $signcertificates_add    = Permission::firstOrCreate(['name' => 'add-signcertificates']);
        $signcertificates_view   = Permission::firstOrCreate(['name' => 'view-signcertificates']);
        $signcertificates_edit   = Permission::firstOrCreate(['name' => 'edit-signcertificates']);
        $signcertificates_delete = Permission::firstOrCreate(['name' => 'delete-signcertificates']);

        if (!$admin->hasPermission($signcertificates_add)) {
            $admin_role->givePermissionTo($signcertificates_add);
        }
        if (!$admin->hasPermission($signcertificates_view)) {
            $admin_role->givePermissionTo($signcertificates_view);
        }
        if (!$admin->hasPermission($signcertificates_edit)) {
            $admin_role->givePermissionTo($signcertificates_edit);
        }
        if (!$admin->hasPermission($signcertificates_delete)) {
            $admin_role->givePermissionTo($signcertificates_delete);
        }

        $application_inspectors_agreement_add    = Permission::firstOrCreate(['name' => 'add-application-inspectors-agreement']);
        $application_inspectors_agreement_view   = Permission::firstOrCreate(['name' => 'view-application-inspectors-agreement']);
        $application_inspectors_agreement_edit   = Permission::firstOrCreate(['name' => 'edit-application-inspectors-agreement']);
        $application_inspectors_agreement_delete = Permission::firstOrCreate(['name' => 'delete-application-inspectors-agreement']);
        $application_inspectors_agreement_other = Permission::firstOrCreate(['name' => 'other-application-inspectors-agreement']);
        $application_inspectors_agreement_view_all = Permission::firstOrCreate(['name' => 'view_all-application-inspectors-agreement']);

        if (!$admin->hasPermission($application_inspectors_agreement_add)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_add);
        }
        if (!$admin->hasPermission($application_inspectors_agreement_view)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_view);
        }
        if (!$admin->hasPermission($application_inspectors_agreement_edit)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_edit);
        }
        if (!$admin->hasPermission($application_inspectors_agreement_delete)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_delete);
        }
        if (!$admin->hasPermission($application_inspectors_agreement_other)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_other);
        }
        if (!$admin->hasPermission($application_inspectors_agreement_view_all)) {
            $admin_role->givePermissionTo($application_inspectors_agreement_view_all);
        }

        $cerreport_epayments_add    = Permission::firstOrCreate(['name' => 'add-cerreport-epayments']);
        $cerreport_epayments_view   = Permission::firstOrCreate(['name' => 'view-cerreport-epayments']);
        $cerreport_epayments_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-epayments']);
        $cerreport_epayments_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-epayments']);

        if (!$admin->hasPermission($cerreport_epayments_add)) {
          $admin_role->givePermissionTo($cerreport_epayments_add);
        }
        if (!$admin->hasPermission($cerreport_epayments_view)) {
          $admin_role->givePermissionTo($cerreport_epayments_view);
        }
        if (!$admin->hasPermission($cerreport_epayments_edit)) {
          $admin_role->givePermissionTo($cerreport_epayments_edit);
        }
        if (!$admin->hasPermission($cerreport_epayments_delete)) {
          $admin_role->givePermissionTo($cerreport_epayments_delete);

        }


        $manage_inspector_add    = Permission::firstOrCreate(['name' => 'add-manage-inspector']);
        $manage_inspector_view   = Permission::firstOrCreate(['name' => 'view-manage-inspector']);
        $manage_inspector_edit   = Permission::firstOrCreate(['name' => 'edit-manage-inspector']);
        $manage_inspector_delete = Permission::firstOrCreate(['name' => 'delete-manage-inspector']);
        $manage_inspector_other        = Permission::firstOrCreate(['name' => 'other-manage-inspector']);
        $manage_inspector_sync_to_elicense = Permission::firstOrCreate(['name' => 'sync_to_elicense-manage-inspector']);
        $manage_inspector_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-manage-inspector']);

        if (!$admin->hasPermission($manage_inspector_add)) {
          $admin_role->givePermissionTo($manage_inspector_add);
        }
        if (!$admin->hasPermission($manage_inspector_view)) {
          $admin_role->givePermissionTo($manage_inspector_view);
        }
        if (!$admin->hasPermission($manage_inspector_edit)) {
          $admin_role->givePermissionTo($manage_inspector_edit);
        }
        if (!$admin->hasPermission($manage_inspector_delete)) {
          $admin_role->givePermissionTo($manage_inspector_delete);
        }
        if (!$admin->hasPermission($manage_inspector_other)) {
            $admin_role->givePermissionTo($manage_inspector_other);
        }
        if (!$admin->hasPermission($manage_inspector_sync_to_elicense)) {
            $admin_role->givePermissionTo($manage_inspector_sync_to_elicense);
        }
        if (!$admin->hasPermission($manage_inspector_poko_approve)) {
            $admin_role->givePermissionTo($manage_inspector_poko_approve);
        }

        $bsection5_standard_add    = Permission::firstOrCreate(['name' => 'add-bsection5-standard']);
        $bsection5_standard_view   = Permission::firstOrCreate(['name' => 'view-bsection5-standard']);
        $bsection5_standard_edit   = Permission::firstOrCreate(['name' => 'edit-bsection5-standard']);
        $bsection5_standard_delete = Permission::firstOrCreate(['name' => 'delete-bsection5-standard']);

        if (!$admin->hasPermission($bsection5_standard_add)) {
            $admin_role->givePermissionTo($bsection5_standard_add);
        }
        if (!$admin->hasPermission($bsection5_standard_view)) {
            $admin_role->givePermissionTo($bsection5_standard_view);
        }
        if (!$admin->hasPermission($bsection5_standard_edit)) {
            $admin_role->givePermissionTo($bsection5_standard_edit);
        }
        if (!$admin->hasPermission($bsection5_standard_delete)) {
            $admin_role->givePermissionTo($bsection5_standard_delete);
        }

        $application_ibcb_audit_add          = Permission::firstOrCreate(['name' => 'add-application-ibcb-audit']);
        $application_ibcb_audit_view         = Permission::firstOrCreate(['name' => 'view-application-ibcb-audit']);
        $application_ibcb_audit_edit         = Permission::firstOrCreate(['name' => 'edit-application-ibcb-audit']);
        $application_ibcb_audit_delete       = Permission::firstOrCreate(['name' => 'delete-application-ibcb-audit']);
        $application_ibcb_audit_other        = Permission::firstOrCreate(['name' => 'other-application-ibcb-audit']);
        $application_ibcb_audit_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-application-ibcb-audit']);
        $application_ibcb_audit_view_all     = Permission::firstOrCreate(['name' => 'view_all-application-ibcb-audit']);

        if (!$admin->hasPermission($application_ibcb_audit_add)) {
            $admin_role->givePermissionTo($application_ibcb_audit_add);
        }
        if (!$admin->hasPermission($application_ibcb_audit_view)) {
            $admin_role->givePermissionTo($application_ibcb_audit_view);
        }
        if (!$admin->hasPermission($application_ibcb_audit_edit)) {
            $admin_role->givePermissionTo($application_ibcb_audit_edit);
        }
        if (!$admin->hasPermission($application_ibcb_audit_delete)) {
            $admin_role->givePermissionTo($application_ibcb_audit_delete);
        }
        if (!$admin->hasPermission($application_ibcb_audit_other)) {
            $admin_role->givePermissionTo($application_ibcb_audit_other);
        }
        if (!$admin->hasPermission($application_ibcb_audit_poko_approve)) {
            $admin_role->givePermissionTo($application_ibcb_audit_poko_approve);
        }
        if (!$admin->hasPermission($application_ibcb_audit_view_all)) {
            $admin_role->givePermissionTo($application_ibcb_audit_view_all);
        }

        $manage_ibcb_add    = Permission::firstOrCreate(['name' => 'add-manage-ibcb']);
        $manage_ibcb_view   = Permission::firstOrCreate(['name' => 'view-manage-ibcb']);
        $manage_ibcb_edit   = Permission::firstOrCreate(['name' => 'edit-manage-ibcb']);
        $manage_ibcb_delete = Permission::firstOrCreate(['name' => 'delete-manage-ibcb']);
        $manage_ibcb_other        = Permission::firstOrCreate(['name' => 'other-manage-ibcb']);
        $manage_ibcb_sync_to_elicense = Permission::firstOrCreate(['name' => 'sync_to_elicense-manage-ibcb']);
        $manage_ibcb_poko_approve = Permission::firstOrCreate(['name' => 'poko_approve-manage-ibcb']);

        if (!$admin->hasPermission($manage_ibcb_add)) {
            $admin_role->givePermissionTo($manage_ibcb_add);
        }
        if (!$admin->hasPermission($manage_ibcb_view)) {
            $admin_role->givePermissionTo($manage_ibcb_view);
        }
        if (!$admin->hasPermission($manage_ibcb_edit)) {
            $admin_role->givePermissionTo($manage_ibcb_edit);
        }
        if (!$admin->hasPermission($manage_ibcb_delete)) {
            $admin_role->givePermissionTo($manage_ibcb_delete);
        }
        if (!$admin->hasPermission($manage_ibcb_other)) {
            $admin_role->givePermissionTo($manage_ibcb_other);
        }
        if (!$admin->hasPermission($manage_ibcb_sync_to_elicense)) {
            $admin_role->givePermissionTo($manage_ibcb_sync_to_elicense);
        }
        if (!$admin->hasPermission($manage_ibcb_poko_approve)) {
            $admin_role->givePermissionTo($manage_ibcb_poko_approve);
        }

        $application_ibcb_approve_add    = Permission::firstOrCreate(['name' => 'add-application-ibcb-approve']);
        $application_ibcb_approve_view   = Permission::firstOrCreate(['name' => 'view-application-ibcb-approve']);
        $application_ibcb_approve_edit   = Permission::firstOrCreate(['name' => 'edit-application-ibcb-approve']);
        $application_ibcb_approve_delete = Permission::firstOrCreate(['name' => 'delete-application-ibcb-approve']);
        $application_ibcb_approve_other = Permission::firstOrCreate(['name' => 'other-application-ibcb-approve']);
        $application_ibcb_approve_view_all = Permission::firstOrCreate(['name' => 'view_all-application-ibcb-approve']);

        if (!$admin->hasPermission($application_ibcb_approve_add)) {
            $admin_role->givePermissionTo($application_ibcb_approve_add);
        }
        if (!$admin->hasPermission($application_ibcb_approve_view)) {
            $admin_role->givePermissionTo($application_ibcb_approve_view);
        }
        if (!$admin->hasPermission($application_ibcb_approve_edit)) {
            $admin_role->givePermissionTo($application_ibcb_approve_edit);
        }
        if (!$admin->hasPermission($application_ibcb_approve_delete)) {
            $admin_role->givePermissionTo($application_ibcb_approve_delete);
        }
        if (!$admin->hasPermission($application_ibcb_approve_other)) {
            $admin_role->givePermissionTo($application_ibcb_approve_other);
        }
        if (!$admin->hasPermission($application_ibcb_approve_view_all)) {
            $admin_role->givePermissionTo($application_ibcb_approve_view_all);
        }

        $configs_format_code_add    = Permission::firstOrCreate(['name' => 'add-configs-format-code']);
        $configs_format_code_view   = Permission::firstOrCreate(['name' => 'view-configs-format-code']);
        $configs_format_code_edit   = Permission::firstOrCreate(['name' => 'edit-configs-format-code']);
        $configs_format_code_delete = Permission::firstOrCreate(['name' => 'delete-configs-format-code']);

        if (!$admin->hasPermission($configs_format_code_add)) {
            $admin_role->givePermissionTo($configs_format_code_add);
        }
        if (!$admin->hasPermission($configs_format_code_view)) {
            $admin_role->givePermissionTo($configs_format_code_view);
        }
        if (!$admin->hasPermission($configs_format_code_edit)) {
            $admin_role->givePermissionTo($configs_format_code_edit);
        }
        if (!$admin->hasPermission($configs_format_code_delete)) {
            $admin_role->givePermissionTo($configs_format_code_delete);
        }

        $trackinglabs_add    = Permission::firstOrCreate(['name' => 'add-trackinglabs']);
        $trackinglabs_view   = Permission::firstOrCreate(['name' => 'view-trackinglabs']);
        $trackinglabs_edit   = Permission::firstOrCreate(['name' => 'edit-trackinglabs']);
        $trackinglabs_delete = Permission::firstOrCreate(['name' => 'delete-trackinglabs']);

        if (!$admin->hasPermission($trackinglabs_add)) {
            $admin_role->givePermissionTo($trackinglabs_add);
        }
        if (!$admin->hasPermission($trackinglabs_view)) {
            $admin_role->givePermissionTo($trackinglabs_view);
        }
        if (!$admin->hasPermission($trackinglabs_edit)) {
            $admin_role->givePermissionTo($trackinglabs_edit);
        }
        if (!$admin->hasPermission($trackinglabs_delete)) {
            $admin_role->givePermissionTo($trackinglabs_delete);
        }

        $trackinglabs_other = Permission::firstOrCreate(['name' => 'other-trackinglabs']);
        if (!$admin->hasPermission($trackinglabs_other)) {
            $admin_role->givePermissionTo($trackinglabs_other);
        }
        $trackinglabs_follow_up_before = Permission::firstOrCreate(['name' => 'follow_up_before-trackinglabs']);
        if (!$admin->hasPermission($trackinglabs_follow_up_before)) {
            $admin_role->givePermissionTo($trackinglabs_follow_up_before);
        }
        $trackinglabs_receive_inspection = Permission::firstOrCreate(['name' => 'receive_inspection-trackinglabs']);
        if (!$admin->hasPermission($trackinglabs_receive_inspection)) {
            $admin_role->givePermissionTo($trackinglabs_receive_inspection);
        }
        $trackinglabs_assign_work = Permission::firstOrCreate(['name' => 'assign_work-trackinglabs']);
        if (!$admin->hasPermission($trackinglabs_assign_work)) {
            $admin_role->givePermissionTo($trackinglabs_assign_work);
        }

        $auditorlabs_add    = Permission::firstOrCreate(['name' => 'add-auditorlabs']);
        $auditorlabs_view   = Permission::firstOrCreate(['name' => 'view-auditorlabs']);
        $auditorlabs_edit   = Permission::firstOrCreate(['name' => 'edit-auditorlabs']);
        $auditorlabs_delete = Permission::firstOrCreate(['name' => 'delete-auditorlabs']);

        if (!$admin->hasPermission($auditorlabs_add)) {
            $admin_role->givePermissionTo($auditorlabs_add);
        }
        if (!$admin->hasPermission($auditorlabs_view)) {
            $admin_role->givePermissionTo($auditorlabs_view);
        }
        if (!$admin->hasPermission($auditorlabs_edit)) {
            $admin_role->givePermissionTo($auditorlabs_edit);
        }
        if (!$admin->hasPermission($auditorlabs_delete)) {
            $admin_role->givePermissionTo($auditorlabs_delete);
        }

        $configs_manual_add    = Permission::firstOrCreate(['name' => 'add-configs-manual']);
        $configs_manual_view   = Permission::firstOrCreate(['name' => 'view-configs-manual']);
        $configs_manual_edit   = Permission::firstOrCreate(['name' => 'edit-configs-manual']);
        $configs_manual_delete = Permission::firstOrCreate(['name' => 'delete-configs-manual']);

        if (!$admin->hasPermission($configs_manual_add)) {
          $admin_role->givePermissionTo($configs_manual_add);
        }
        if (!$admin->hasPermission($configs_manual_view)) {
          $admin_role->givePermissionTo($configs_manual_view);
        }
        if (!$admin->hasPermission($configs_manual_edit)) {
          $admin_role->givePermissionTo($configs_manual_edit);
        }
        if (!$admin->hasPermission($configs_manual_delete)) {
          $admin_role->givePermissionTo($configs_manual_delete);
        }

        $assessmentlabs_add    = Permission::firstOrCreate(['name' => 'add-assessmentlabs']);
        $assessmentlabs_view   = Permission::firstOrCreate(['name' => 'view-assessmentlabs']);
        $assessmentlabs_edit   = Permission::firstOrCreate(['name' => 'edit-assessmentlabs']);
        $assessmentlabs_delete = Permission::firstOrCreate(['name' => 'delete-assessmentlabs']);

        if (!$admin->hasPermission($assessmentlabs_add)) {
            $admin_role->givePermissionTo($assessmentlabs_add);
        }
        if (!$admin->hasPermission($assessmentlabs_view)) {
            $admin_role->givePermissionTo($assessmentlabs_view);
        }
        if (!$admin->hasPermission($assessmentlabs_edit)) {
            $admin_role->givePermissionTo($assessmentlabs_edit);
        }
        if (!$admin->hasPermission($assessmentlabs_delete)) {
            $admin_role->givePermissionTo($assessmentlabs_delete);
        }

        $application_inspectors_accept_add    = Permission::firstOrCreate(['name' => 'add-application-inspectors-accept']);
        $application_inspectors_accept_view   = Permission::firstOrCreate(['name' => 'view-application-inspectors-accept']);
        $application_inspectors_accept_edit   = Permission::firstOrCreate(['name' => 'edit-application-inspectors-accept']);
        $application_inspectors_accept_delete = Permission::firstOrCreate(['name' => 'delete-application-inspectors-accept']);
        $application_inspectors_accept_other = Permission::firstOrCreate(['name' => 'other-application-inspectors-accept']);
        $application_inspectors_accept_assign_work = Permission::firstOrCreate(['name' => 'assign_work-application-inspectors-accept']);
        $application_inspectors_accept_view_all = Permission::firstOrCreate(['name' => 'view_all-application-inspectors-accept']);

        if (!$admin->hasPermission($application_inspectors_accept_add)) {
            $admin_role->givePermissionTo($application_inspectors_accept_add);
        }
        if (!$admin->hasPermission($application_inspectors_accept_view)) {
            $admin_role->givePermissionTo($application_inspectors_accept_view);
        }
        if (!$admin->hasPermission($application_inspectors_accept_edit)) {
            $admin_role->givePermissionTo($application_inspectors_accept_edit);
        }
        if (!$admin->hasPermission($application_inspectors_accept_delete)) {
            $admin_role->givePermissionTo($application_inspectors_accept_delete);
        }
        if (!$admin->hasPermission($application_inspectors_accept_other)) {
            $admin_role->givePermissionTo($application_inspectors_accept_other);
        }
        if (!$admin->hasPermission($application_inspectors_accept_assign_work)) {
            $admin_role->givePermissionTo($application_inspectors_accept_assign_work);
        }
        if (!$admin->hasPermission($application_inspectors_accept_view_all)) {
            $admin_role->givePermissionTo($application_inspectors_accept_view_all);
        }

        $application_ibcb_accept_add         = Permission::firstOrCreate(['name' => 'add-application-ibcb-accept']);
        $application_ibcb_accept_view        = Permission::firstOrCreate(['name' => 'view-application-ibcb-accept']);
        $application_ibcb_accept_edit        = Permission::firstOrCreate(['name' => 'edit-application-ibcb-accept']);
        $application_ibcb_accept_delete      = Permission::firstOrCreate(['name' => 'delete-application-ibcb-accept']);
        $application_ibcb_accept_other       = Permission::firstOrCreate(['name' => 'other-application-ibcb-accept']);
        $application_ibcb_accept_assign_work = Permission::firstOrCreate(['name' => 'assign_work-application-ibcb-accept']);
        $application_ibcb_accept_view_all    = Permission::firstOrCreate(['name' => 'view_all-application-ibcb-accept']);

        if (!$admin->hasPermission($application_ibcb_accept_add)) {
            $admin_role->givePermissionTo($application_ibcb_accept_add);
        }
        if (!$admin->hasPermission($application_ibcb_accept_view)) {
            $admin_role->givePermissionTo($application_ibcb_accept_view);
        }
        if (!$admin->hasPermission($application_ibcb_accept_edit)) {
            $admin_role->givePermissionTo($application_ibcb_accept_edit);
        }
        if (!$admin->hasPermission($application_ibcb_accept_delete)) {
            $admin_role->givePermissionTo($application_ibcb_accept_delete);
        }
        if (!$admin->hasPermission($application_ibcb_accept_other)) {
            $admin_role->givePermissionTo($application_ibcb_accept_other);
        }
        if (!$admin->hasPermission($application_ibcb_accept_assign_work)) {
            $admin_role->givePermissionTo($application_ibcb_accept_assign_work);
        }
        if (!$admin->hasPermission($application_ibcb_accept_view_all)) {
            $admin_role->givePermissionTo($application_ibcb_accept_view_all);
        }


        $trackingcb_add    = Permission::firstOrCreate(['name' => 'add-trackingcb']);
        $trackingcb_view   = Permission::firstOrCreate(['name' => 'view-trackingcb']);
        $trackingcb_edit   = Permission::firstOrCreate(['name' => 'edit-trackingcb']);
        $trackingcb_delete = Permission::firstOrCreate(['name' => 'delete-trackingcb']);

        if (!$admin->hasPermission($trackingcb_add)) {
            $admin_role->givePermissionTo($trackingcb_add);
        }
        if (!$admin->hasPermission($trackingcb_view)) {
            $admin_role->givePermissionTo($trackingcb_view);
        }
        if (!$admin->hasPermission($trackingcb_edit)) {
            $admin_role->givePermissionTo($trackingcb_edit);
        }
        if (!$admin->hasPermission($trackingcb_delete)) {
            $admin_role->givePermissionTo($trackingcb_delete);
        }

        $trackingcb_other = Permission::firstOrCreate(['name' => 'other-trackingcb']);
        if (!$admin->hasPermission($trackingcb_other)) {
            $admin_role->givePermissionTo($trackingcb_other);
        }
        $trackingcb_follow_up_before = Permission::firstOrCreate(['name' => 'follow_up_before-trackingcb']);
        if (!$admin->hasPermission($trackingcb_follow_up_before)) {
            $admin_role->givePermissionTo($trackingcb_follow_up_before);
        }
        $trackingcb_receive_inspection = Permission::firstOrCreate(['name' => 'receive_inspection-trackingcb']);
        if (!$admin->hasPermission($trackingcb_receive_inspection)) {
            $admin_role->givePermissionTo($trackingcb_receive_inspection);
        }
        $trackingcb_assign_work = Permission::firstOrCreate(['name' => 'assign_work-trackingcb']);
        if (!$admin->hasPermission($trackingcb_assign_work)) {
            $admin_role->givePermissionTo($trackingcb_assign_work);
        }

        $tracking_cb_add    = Permission::firstOrCreate(['name' => 'add-tracking-cb']);
        $tracking_cb_view   = Permission::firstOrCreate(['name' => 'view-tracking-cb']);
        $tracking_cb_edit   = Permission::firstOrCreate(['name' => 'edit-tracking-cb']);
        $tracking_cb_delete = Permission::firstOrCreate(['name' => 'delete-tracking-cb']);

        if (!$admin->hasPermission($tracking_cb_add)) {
            $admin_role->givePermissionTo($tracking_cb_add);
        }
        if (!$admin->hasPermission($tracking_cb_view)) {
            $admin_role->givePermissionTo($tracking_cb_view);
        }
        if (!$admin->hasPermission($tracking_cb_edit)) {
            $admin_role->givePermissionTo($tracking_cb_edit);
        }
        if (!$admin->hasPermission($tracking_cb_delete)) {
            $admin_role->givePermissionTo($tracking_cb_delete);
        }

        $tracking_lab_add    = Permission::firstOrCreate(['name' => 'add-tracking-lab']);
        $tracking_lab_view   = Permission::firstOrCreate(['name' => 'view-tracking-lab']);
        $tracking_lab_edit   = Permission::firstOrCreate(['name' => 'edit-tracking-lab']);
        $tracking_lab_delete = Permission::firstOrCreate(['name' => 'delete-tracking-lab']);

        if (!$admin->hasPermission($tracking_lab_add)) {
            $admin_role->givePermissionTo($tracking_lab_add);
        }
        if (!$admin->hasPermission($tracking_lab_view)) {
            $admin_role->givePermissionTo($tracking_lab_view);
        }
        if (!$admin->hasPermission($tracking_lab_edit)) {
            $admin_role->givePermissionTo($tracking_lab_edit);
        }
        if (!$admin->hasPermission($tracking_lab_delete)) {
            $admin_role->givePermissionTo($tracking_lab_delete);
        }

        $auditorcb_add    = Permission::firstOrCreate(['name' => 'add-auditorcb']);
        $auditorcb_view   = Permission::firstOrCreate(['name' => 'view-auditorcb']);
        $auditorcb_edit   = Permission::firstOrCreate(['name' => 'edit-auditorcb']);
        $auditorcb_delete = Permission::firstOrCreate(['name' => 'delete-auditorcb']);

        if (!$admin->hasPermission($auditorcb_add)) {
            $admin_role->givePermissionTo($auditorcb_add);
        }
        if (!$admin->hasPermission($auditorcb_view)) {
            $admin_role->givePermissionTo($auditorcb_view);
        }
        if (!$admin->hasPermission($auditorcb_edit)) {
            $admin_role->givePermissionTo($auditorcb_edit);
        }
        if (!$admin->hasPermission($auditorcb_delete)) {
            $admin_role->givePermissionTo($auditorcb_delete);
        }

        $assessmentcb_add    = Permission::firstOrCreate(['name' => 'add-assessmentcb']);
        $assessmentcb_view   = Permission::firstOrCreate(['name' => 'view-assessmentcb']);
        $assessmentcb_edit   = Permission::firstOrCreate(['name' => 'edit-assessmentcb']);
        $assessmentcb_delete = Permission::firstOrCreate(['name' => 'delete-assessmentcb']);

        if (!$admin->hasPermission($assessmentcb_add)) {
            $admin_role->givePermissionTo($assessmentcb_add);
        }
        if (!$admin->hasPermission($assessmentcb_view)) {
            $admin_role->givePermissionTo($assessmentcb_view);
        }
        if (!$admin->hasPermission($assessmentcb_edit)) {
            $admin_role->givePermissionTo($assessmentcb_edit);
        }
        if (!$admin->hasPermission($assessmentcb_delete)) {
            $admin_role->givePermissionTo($assessmentcb_delete);
        }

        $trackingib_add    = Permission::firstOrCreate(['name' => 'add-trackingib']);
        $trackingib_view   = Permission::firstOrCreate(['name' => 'view-trackingib']);
        $trackingib_edit   = Permission::firstOrCreate(['name' => 'edit-trackingib']);
        $trackingib_delete = Permission::firstOrCreate(['name' => 'delete-trackingib']);

        if (!$admin->hasPermission($trackingib_add)) {
            $admin_role->givePermissionTo($trackingib_add);
        }
        if (!$admin->hasPermission($trackingib_view)) {
            $admin_role->givePermissionTo($trackingib_view);
        }
        if (!$admin->hasPermission($trackingib_edit)) {
            $admin_role->givePermissionTo($trackingib_edit);
        }
        if (!$admin->hasPermission($trackingib_delete)) {
            $admin_role->givePermissionTo($trackingib_delete);
        }

        $trackingib_other = Permission::firstOrCreate(['name' => 'other-trackingib']);
        if (!$admin->hasPermission($trackingib_other)) {
            $admin_role->givePermissionTo($trackingib_other);
        }
        $trackingib_follow_up_before = Permission::firstOrCreate(['name' => 'follow_up_before-trackingib']);
        if (!$admin->hasPermission($trackingib_follow_up_before)) {
            $admin_role->givePermissionTo($trackingib_follow_up_before);
        }
        $trackingib_receive_inspection = Permission::firstOrCreate(['name' => 'receive_inspection-trackingib']);
        if (!$admin->hasPermission($trackingib_receive_inspection)) {
            $admin_role->givePermissionTo($trackingib_receive_inspection);
        }
        $trackingib_assign_work = Permission::firstOrCreate(['name' => 'assign_work-trackingib']);
        if (!$admin->hasPermission($trackingib_assign_work)) {
            $admin_role->givePermissionTo($trackingib_assign_work);
        }

        $tracking_ib_add    = Permission::firstOrCreate(['name' => 'add-tracking-ib']);
        $tracking_ib_view   = Permission::firstOrCreate(['name' => 'view-tracking-ib']);
        $tracking_ib_edit   = Permission::firstOrCreate(['name' => 'edit-tracking-ib']);
        $tracking_ib_delete = Permission::firstOrCreate(['name' => 'delete-tracking-ib']);

        if (!$admin->hasPermission($tracking_ib_add)) {
            $admin_role->givePermissionTo($tracking_ib_add);
        }
        if (!$admin->hasPermission($tracking_ib_view)) {
            $admin_role->givePermissionTo($tracking_ib_view);
        }
        if (!$admin->hasPermission($tracking_ib_edit)) {
            $admin_role->givePermissionTo($tracking_ib_edit);
        }
        if (!$admin->hasPermission($tracking_ib_delete)) {
            $admin_role->givePermissionTo($tracking_ib_delete);
        }

        $auditorib_add    = Permission::firstOrCreate(['name' => 'add-auditorib']);
        $auditorib_view   = Permission::firstOrCreate(['name' => 'view-auditorib']);
        $auditorib_edit   = Permission::firstOrCreate(['name' => 'edit-auditorib']);
        $auditorib_delete = Permission::firstOrCreate(['name' => 'delete-auditorib']);

        if (!$admin->hasPermission($auditorib_add)) {
            $admin_role->givePermissionTo($auditorib_add);
        }
        if (!$admin->hasPermission($auditorib_view)) {
            $admin_role->givePermissionTo($auditorib_view);
        }
        if (!$admin->hasPermission($auditorib_edit)) {
            $admin_role->givePermissionTo($auditorib_edit);
        }
        if (!$admin->hasPermission($auditorib_delete)) {
            $admin_role->givePermissionTo($auditorib_delete);
        }

        $assessmentib_add    = Permission::firstOrCreate(['name' => 'add-assessmentib']);
        $assessmentib_view   = Permission::firstOrCreate(['name' => 'view-assessmentib']);
        $assessmentib_edit   = Permission::firstOrCreate(['name' => 'edit-assessmentib']);
        $assessmentib_delete = Permission::firstOrCreate(['name' => 'delete-assessmentib']);

        if (!$admin->hasPermission($assessmentib_add)) {
            $admin_role->givePermissionTo($assessmentib_add);
        }
        if (!$admin->hasPermission($assessmentib_view)) {
            $admin_role->givePermissionTo($assessmentib_view);
        }
        if (!$admin->hasPermission($assessmentib_edit)) {
            $admin_role->givePermissionTo($assessmentib_edit);
        }
        if (!$admin->hasPermission($assessmentib_delete)) {
            $admin_role->givePermissionTo($assessmentib_delete);
        }

        $certifystandard_add    = Permission::firstOrCreate(['name' => 'add-certifystandard']);
        $certifystandard_view   = Permission::firstOrCreate(['name' => 'view-certifystandard']);
        $certifystandard_edit   = Permission::firstOrCreate(['name' => 'edit-certifystandard']);
        $certifystandard_delete = Permission::firstOrCreate(['name' => 'delete-certifystandard']);

        if (!$admin->hasPermission($certifystandard_add)) {
            $admin_role->givePermissionTo($certifystandard_add);
        }
        if (!$admin->hasPermission($certifystandard_view)) {
            $admin_role->givePermissionTo($certifystandard_view);
        }
        if (!$admin->hasPermission($certifystandard_edit)) {
            $admin_role->givePermissionTo($certifystandard_edit);
        }
        if (!$admin->hasPermission($certifystandard_delete)) {
            $admin_role->givePermissionTo($certifystandard_delete);
        }

        $setstandard_add    = Permission::firstOrCreate(['name' => 'add-setstandard']);
        $setstandard_view   = Permission::firstOrCreate(['name' => 'view-setstandard']);
        $setstandard_edit   = Permission::firstOrCreate(['name' => 'edit-setstandard']);
        $setstandard_delete = Permission::firstOrCreate(['name' => 'delete-setstandard']);

        if (!$admin->hasPermission($setstandard_add)) {
            $admin_role->givePermissionTo($setstandard_add);
        }
        if (!$admin->hasPermission($setstandard_view)) {
            $admin_role->givePermissionTo($setstandard_view);
        }
        if (!$admin->hasPermission($setstandard_edit)) {
            $admin_role->givePermissionTo($setstandard_edit);
        }
        if (!$admin->hasPermission($setstandard_delete)) {
            $admin_role->givePermissionTo($setstandard_delete);
        }


        $report_test_factory_add    = Permission::firstOrCreate(['name' => 'add-report-test-factory']);
        $report_test_factory_view   = Permission::firstOrCreate(['name' => 'view-report-test-factory']);
        $report_test_factory_edit   = Permission::firstOrCreate(['name' => 'edit-report-test-factory']);
        $report_test_factory_delete = Permission::firstOrCreate(['name' => 'delete-report-test-factory']);

        if (!$admin->hasPermission($report_test_factory_add)) {
            $admin_role->givePermissionTo($report_test_factory_add);
        }
        if (!$admin->hasPermission($report_test_factory_view)) {
            $admin_role->givePermissionTo($report_test_factory_view);
        }
        if (!$admin->hasPermission($report_test_factory_edit)) {
            $admin_role->givePermissionTo($report_test_factory_edit);
        }
        if (!$admin->hasPermission($report_test_factory_delete)) {
            $admin_role->givePermissionTo($report_test_factory_delete);
        }

        $report_test_product_add    = Permission::firstOrCreate(['name' => 'add-report-test-product']);
        $report_test_product_view   = Permission::firstOrCreate(['name' => 'view-report-test-product']);
        $report_test_product_edit   = Permission::firstOrCreate(['name' => 'edit-report-test-product']);
        $report_test_product_delete = Permission::firstOrCreate(['name' => 'delete-report-test-product']);

        if (!$admin->hasPermission($report_test_product_add)) {
            $admin_role->givePermissionTo($report_test_product_add);
        }
        if (!$admin->hasPermission($report_test_product_view)) {
            $admin_role->givePermissionTo($report_test_product_view);
        }
        if (!$admin->hasPermission($report_test_product_edit)) {
            $admin_role->givePermissionTo($report_test_product_edit);
        }
        if (!$admin->hasPermission($report_test_product_delete)) {
            $admin_role->givePermissionTo($report_test_product_delete);
        }

        $report_sso_login_view = Permission::firstOrCreate(['name' => 'view-report-sso-login']);
        if (!$admin->hasPermission($report_sso_login_view)) {
            $admin_role->givePermissionTo($report_sso_login_view);
        }

        $report_user_login_view = Permission::firstOrCreate(['name' => 'view-report-user-login']);
        if (!$admin->hasPermission($report_user_login_view)) {
            $admin_role->givePermissionTo($report_user_login_view);
        }

        $configs_report_power_bi_group_add    = Permission::firstOrCreate(['name' => 'add-configs-report-power-bi-group']);
        $configs_report_power_bi_group_view   = Permission::firstOrCreate(['name' => 'view-configs-report-power-bi-group']);
        $configs_report_power_bi_group_edit   = Permission::firstOrCreate(['name' => 'edit-configs-report-power-bi-group']);
        $configs_report_power_bi_group_delete = Permission::firstOrCreate(['name' => 'delete-configs-report-power-bi-group']);

        if (!$admin->hasPermission($configs_report_power_bi_group_add)) {
            $admin_role->givePermissionTo($configs_report_power_bi_group_add);
        }
        if (!$admin->hasPermission($configs_report_power_bi_group_view)) {
            $admin_role->givePermissionTo($configs_report_power_bi_group_view);
        }
        if (!$admin->hasPermission($configs_report_power_bi_group_edit)) {
            $admin_role->givePermissionTo($configs_report_power_bi_group_edit);
        }
        if (!$admin->hasPermission($configs_report_power_bi_group_delete)) {
            $admin_role->givePermissionTo($configs_report_power_bi_group_delete);
        }

        $configs_report_power_bi_add    = Permission::firstOrCreate(['name' => 'add-configs-report-power-bi']);
        $configs_report_power_bi_view   = Permission::firstOrCreate(['name' => 'view-configs-report-power-bi']);
        $configs_report_power_bi_edit   = Permission::firstOrCreate(['name' => 'edit-configs-report-power-bi']);
        $configs_report_power_bi_delete = Permission::firstOrCreate(['name' => 'delete-configs-report-power-bi']);

        if (!$admin->hasPermission($configs_report_power_bi_add)) {
            $admin_role->givePermissionTo($configs_report_power_bi_add);
        }
        if (!$admin->hasPermission($configs_report_power_bi_view)) {
            $admin_role->givePermissionTo($configs_report_power_bi_view);
        }
        if (!$admin->hasPermission($configs_report_power_bi_edit)) {
            $admin_role->givePermissionTo($configs_report_power_bi_edit);
        }
        if (!$admin->hasPermission($configs_report_power_bi_delete)) {
            $admin_role->givePermissionTo($configs_report_power_bi_delete);
        }

        $report_power_bi_view = Permission::firstOrCreate(['name' => 'view-report-power-bi']);

        if (!$admin->hasPermission($report_power_bi_view)) {
            $admin_role->givePermissionTo($report_power_bi_view);
        }

        $meetingstandards_add    = Permission::firstOrCreate(['name' => 'add-meetingstandards']);
        $meetingstandards_view   = Permission::firstOrCreate(['name' => 'view-meetingstandards']);
        $meetingstandards_edit   = Permission::firstOrCreate(['name' => 'edit-meetingstandards']);
        $meetingstandards_delete = Permission::firstOrCreate(['name' => 'delete-meetingstandards']);

        if (!$admin->hasPermission($meetingstandards_add)) {
            $admin_role->givePermissionTo($meetingstandards_add);
        }
        if (!$admin->hasPermission($meetingstandards_view)) {
            $admin_role->givePermissionTo($meetingstandards_view);
        }
        if (!$admin->hasPermission($meetingstandards_edit)) {
            $admin_role->givePermissionTo($meetingstandards_edit);
        }
        if (!$admin->hasPermission($meetingstandards_delete)) {
            $admin_role->givePermissionTo($meetingstandards_delete);
        }

        $meetingtypes_add    = Permission::firstOrCreate(['name' => 'add-meetingtypes']);
        $meetingtypes_view   = Permission::firstOrCreate(['name' => 'view-meetingtypes']);
        $meetingtypes_edit   = Permission::firstOrCreate(['name' => 'edit-meetingtypes']);
        $meetingtypes_delete = Permission::firstOrCreate(['name' => 'delete-meetingtypes']);

        if (!$admin->hasPermission($meetingtypes_add)) {
            $admin_role->givePermissionTo($meetingtypes_add);
        }
        if (!$admin->hasPermission($meetingtypes_view)) {
            $admin_role->givePermissionTo($meetingtypes_view);
        }
        if (!$admin->hasPermission($meetingtypes_edit)) {
            $admin_role->givePermissionTo($meetingtypes_edit);
        }
        if (!$admin->hasPermission($meetingtypes_delete)) {
            $admin_role->givePermissionTo($meetingtypes_delete);
        }

        $cerreport_certificate_add    = Permission::firstOrCreate(['name' => 'add-cerreport-certificate']);
        $cerreport_certificate_view   = Permission::firstOrCreate(['name' => 'view-cerreport-certificate']);
        $cerreport_certificate_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-certificate']);
        $cerreport_certificate_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-certificate']);

        if (!$admin->hasPermission($cerreport_certificate_add)) {
            $admin_role->givePermissionTo($cerreport_certificate_add);
        }
        if (!$admin->hasPermission($cerreport_certificate_view)) {
            $admin_role->givePermissionTo($cerreport_certificate_view);
        }
        if (!$admin->hasPermission($cerreport_certificate_edit)) {
            $admin_role->givePermissionTo($cerreport_certificate_edit);
        }
        if (!$admin->hasPermission($cerreport_certificate_delete)) {
            $admin_role->givePermissionTo($cerreport_certificate_delete);
        }

        $cerreport_payins_add    = Permission::firstOrCreate(['name' => 'add-cerreport-payins']);
        $cerreport_payins_view   = Permission::firstOrCreate(['name' => 'view-cerreport-payins']);
        $cerreport_payins_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-payins']);
        $cerreport_payins_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-payins']);

        if (!$admin->hasPermission($cerreport_payins_add)) {
            $admin_role->givePermissionTo($cerreport_payins_add);
        }
        if (!$admin->hasPermission($cerreport_payins_view)) {
            $admin_role->givePermissionTo($cerreport_payins_view);
        }
        if (!$admin->hasPermission($cerreport_payins_edit)) {
            $admin_role->givePermissionTo($cerreport_payins_edit);
        }
        if (!$admin->hasPermission($cerreport_payins_delete)) {
            $admin_role->givePermissionTo($cerreport_payins_delete);
        }

        $acc_auditors_add    = Permission::firstOrCreate(['name' => 'add-acc-auditors']);
        $acc_auditors_view   = Permission::firstOrCreate(['name' => 'view-acc-auditors']);
        $acc_auditors_edit   = Permission::firstOrCreate(['name' => 'edit-acc-auditors']);
        $acc_auditors_delete = Permission::firstOrCreate(['name' => 'delete-acc-auditors']);

        if (!$admin->hasPermission($acc_auditors_add)) {
            $admin_role->givePermissionTo($acc_auditors_add);
        }
        if (!$admin->hasPermission($acc_auditors_view)) {
            $admin_role->givePermissionTo($acc_auditors_view);
        }
        if (!$admin->hasPermission($acc_auditors_edit)) {
            $admin_role->givePermissionTo($acc_auditors_edit);
        }
        if (!$admin->hasPermission($acc_auditors_delete)) {
            $admin_role->givePermissionTo($acc_auditors_delete);
        }
        $cerreport_certificate_export_add    = Permission::firstOrCreate(['name' => 'add-cerreport-certificate-export']);
        $cerreport_certificate_export_view   = Permission::firstOrCreate(['name' => 'view-cerreport-certificate-export']);
        $cerreport_certificate_export_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-certificate-export']);
        $cerreport_certificate_export_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-certificate-export']);

        if (!$admin->hasPermission($cerreport_certificate_export_add)) {
            $admin_role->givePermissionTo($cerreport_certificate_export_add);
        }
        if (!$admin->hasPermission($cerreport_certificate_export_view)) {
            $admin_role->givePermissionTo($cerreport_certificate_export_view);
        }
        if (!$admin->hasPermission($cerreport_certificate_export_edit)) {
            $admin_role->givePermissionTo($cerreport_certificate_export_edit);
        }
        if (!$admin->hasPermission($cerreport_certificate_export_delete)) {
            $admin_role->givePermissionTo($cerreport_certificate_export_delete);
        }

        $cerreport_certified_formula_add    = Permission::firstOrCreate(['name' => 'add-cerreport-certified-formula']);
        $cerreport_certified_formula_view   = Permission::firstOrCreate(['name' => 'view-cerreport-certified-formula']);
        $cerreport_certified_formula_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-certified-formula']);
        $cerreport_certified_formula_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-certified-formula']);

        if (!$admin->hasPermission($cerreport_certified_formula_add)) {
            $admin_role->givePermissionTo($cerreport_certified_formula_add);
        }
        if (!$admin->hasPermission($cerreport_certified_formula_view)) {
            $admin_role->givePermissionTo($cerreport_certified_formula_view);
        }
        if (!$admin->hasPermission($cerreport_certified_formula_edit)) {
            $admin_role->givePermissionTo($cerreport_certified_formula_edit);
        }
        if (!$admin->hasPermission($cerreport_certified_formula_delete)) {
            $admin_role->givePermissionTo($cerreport_certified_formula_delete);
        }

        $cerreport_applicant_add    = Permission::firstOrCreate(['name' => 'add-cerreport-applicant']);
        $cerreport_applicant_view   = Permission::firstOrCreate(['name' => 'view-cerreport-applicant']);
        $cerreport_applicant_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-applicant']);
        $cerreport_applicant_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-applicant']);

        if (!$admin->hasPermission($cerreport_applicant_add)) {
            $admin_role->givePermissionTo($cerreport_applicant_add);
        }
        if (!$admin->hasPermission($cerreport_applicant_view)) {
            $admin_role->givePermissionTo($cerreport_applicant_view);
        }
        if (!$admin->hasPermission($cerreport_applicant_edit)) {
            $admin_role->givePermissionTo($cerreport_applicant_edit);
        }
        if (!$admin->hasPermission($cerreport_applicant_delete)) {
            $admin_role->givePermissionTo($cerreport_applicant_delete);
        }

        $permission_add    = Permission::firstOrCreate(['name' => 'add-permission']);
        $permission_view   = Permission::firstOrCreate(['name' => 'view-permission']);
        $permission_edit   = Permission::firstOrCreate(['name' => 'edit-permission']);
        $permission_delete = Permission::firstOrCreate(['name' => 'delete-permission']);

        if (!$admin->hasPermission($permission_add)) {
            $admin_role->givePermissionTo($permission_add);
        }
        if (!$admin->hasPermission($permission_view)) {
            $admin_role->givePermissionTo($permission_view);
        }
        if (!$admin->hasPermission($permission_edit)) {
            $admin_role->givePermissionTo($permission_edit);
        }
        if (!$admin->hasPermission($permission_delete)) {
            $admin_role->givePermissionTo($permission_delete);
        }

        $report_standard_status_add    = Permission::firstOrCreate(['name' => 'add-report-standard-status']);
        $report_standard_status_view   = Permission::firstOrCreate(['name' => 'view-report-standard-status']);
        $report_standard_status_edit   = Permission::firstOrCreate(['name' => 'edit-report-standard-status']);
        $report_standard_status_delete = Permission::firstOrCreate(['name' => 'delete-report-standard-status']);

        if (!$admin->hasPermission($report_standard_status_add)) {
            $admin_role->givePermissionTo($report_standard_status_add);
        }
        if (!$admin->hasPermission($report_standard_status_view)) {
            $admin_role->givePermissionTo($report_standard_status_view);
        }
        if (!$admin->hasPermission($report_standard_status_edit)) {
            $admin_role->givePermissionTo($report_standard_status_edit);
        }
        if (!$admin->hasPermission($report_standard_status_delete)) {
            $admin_role->givePermissionTo($report_standard_status_delete);
        }

        $gazette_add    = Permission::firstOrCreate(['name' => 'add-gazette']);
        $gazette_view   = Permission::firstOrCreate(['name' => 'view-gazette']);
        $gazette_edit   = Permission::firstOrCreate(['name' => 'edit-gazette']);
        $gazette_delete = Permission::firstOrCreate(['name' => 'delete-gazette']);

        if (!$admin->hasPermission($gazette_add)) {
            $admin_role->givePermissionTo($gazette_add);
        }
        if (!$admin->hasPermission($gazette_view)) {
            $admin_role->givePermissionTo($gazette_view);
        }
        if (!$admin->hasPermission($gazette_edit)) {
            $admin_role->givePermissionTo($gazette_edit);
        }
        if (!$admin->hasPermission($gazette_delete)) {
            $admin_role->givePermissionTo($gazette_delete);
        }

        $law_departments_add    = Permission::firstOrCreate(['name' => 'add-law-departments']);
        $law_departments_view   = Permission::firstOrCreate(['name' => 'view-law-departments']);
        $law_departments_edit   = Permission::firstOrCreate(['name' => 'edit-law-departments']);
        $law_departments_delete = Permission::firstOrCreate(['name' => 'delete-law-departments']);

        if (!$admin->hasPermission($law_departments_add)) {
            $admin_role->givePermissionTo($law_departments_add);
        }
        if (!$admin->hasPermission($law_departments_view)) {
            $admin_role->givePermissionTo($law_departments_view);
        }
        if (!$admin->hasPermission($law_departments_edit)) {
            $admin_role->givePermissionTo($law_departments_edit);
        }
        if (!$admin->hasPermission($law_departments_delete)) {
            $admin_role->givePermissionTo($law_departments_delete);
        }
        $bcertify_reason_add    = Permission::firstOrCreate(['name' => 'add-bcertify-reason']);
        $bcertify_reason_view   = Permission::firstOrCreate(['name' => 'view-bcertify-reason']);
        $bcertify_reason_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-reason']);
        $bcertify_reason_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-reason']);

        if (!$admin->hasPermission($bcertify_reason_add)) {
            $admin_role->givePermissionTo($bcertify_reason_add);
        }
        if (!$admin->hasPermission($bcertify_reason_view)) {
            $admin_role->givePermissionTo($bcertify_reason_view);
        }
        if (!$admin->hasPermission($bcertify_reason_edit)) {
            $admin_role->givePermissionTo($bcertify_reason_edit);
        }
        if (!$admin->hasPermission($bcertify_reason_delete)) {
            $admin_role->givePermissionTo($bcertify_reason_delete);   
	    }

        $law_department_stakeholder_add    = Permission::firstOrCreate(['name' => 'add-law-department-stakeholder']);
        $law_department_stakeholder_view   = Permission::firstOrCreate(['name' => 'view-law-department-stakeholder']);
        $law_department_stakeholder_edit   = Permission::firstOrCreate(['name' => 'edit-law-department-stakeholder']);
        $law_department_stakeholder_delete = Permission::firstOrCreate(['name' => 'delete-law-department-stakeholder']);

        if (!$admin->hasPermission($law_department_stakeholder_add)) {
            $admin_role->givePermissionTo($law_department_stakeholder_add);
        }
        if (!$admin->hasPermission($law_department_stakeholder_view)) {
            $admin_role->givePermissionTo($law_department_stakeholder_view);
        }
        if (!$admin->hasPermission($law_department_stakeholder_edit)) {
            $admin_role->givePermissionTo($law_department_stakeholder_edit);
        }
        if (!$admin->hasPermission($law_department_stakeholder_delete)) {
            $admin_role->givePermissionTo($law_department_stakeholder_delete);
        }

        $law_sections_add    = Permission::firstOrCreate(['name' => 'add-law-sections']);
        $law_sections_view   = Permission::firstOrCreate(['name' => 'view-law-sections']);
        $law_sections_edit   = Permission::firstOrCreate(['name' => 'edit-law-sections']);
        $law_sections_delete = Permission::firstOrCreate(['name' => 'delete-law-sections']);

        if (!$admin->hasPermission($law_sections_add)) {
            $admin_role->givePermissionTo($law_sections_add);
        }
        if (!$admin->hasPermission($law_sections_view)) {
            $admin_role->givePermissionTo($law_sections_view);
        }
        if (!$admin->hasPermission($law_sections_edit)) {
            $admin_role->givePermissionTo($law_sections_edit);
        }
        if (!$admin->hasPermission($law_sections_delete)) {
            $admin_role->givePermissionTo($law_sections_delete);
        }

        $mail_test_view = Permission::firstOrCreate(['name' => 'view-mail-test']);
        if (!$admin->hasPermission($mail_test_view)) {
            $admin_role->givePermissionTo($mail_test_view);
        }

        $report_factory_inspection_view = Permission::firstOrCreate(['name' => 'view-report-factory-inspection']);
        if (!$admin->hasPermission($report_factory_inspection_view)) {
            $admin_role->givePermissionTo($report_factory_inspection_view);
        }

        $report_product_lab_view = Permission::firstOrCreate(['name' => 'view-report-product-lab']);
        if (!$admin->hasPermission($report_product_lab_view)) {
            $admin_role->givePermissionTo($report_product_lab_view);
        }

        $report_example_lab_view = Permission::firstOrCreate(['name' => 'view-report-example-lab']);
        if (!$admin->hasPermission($report_example_lab_view)) {
            $admin_role->givePermissionTo($report_example_lab_view);
        }

        $setion5_application_ibcb_add    = Permission::firstOrCreate(['name' => 'add-setion5-application-ibcb']);
        $setion5_application_ibcb_view   = Permission::firstOrCreate(['name' => 'view-setion5-application-ibcb']);
        $setion5_application_ibcb_edit   = Permission::firstOrCreate(['name' => 'edit-setion5-application-ibcb']);
        $setion5_application_ibcb_delete = Permission::firstOrCreate(['name' => 'delete-setion5-application-ibcb']);

        if (!$admin->hasPermission($setion5_application_ibcb_add)) {
            $admin_role->givePermissionTo($setion5_application_ibcb_add);
        }
        if (!$admin->hasPermission($setion5_application_ibcb_view)) {
            $admin_role->givePermissionTo($setion5_application_ibcb_view);
        }
        if (!$admin->hasPermission($setion5_application_ibcb_edit)) {
            $admin_role->givePermissionTo($setion5_application_ibcb_edit);
        }
        if (!$admin->hasPermission($setion5_application_ibcb_delete)) {
            $admin_role->givePermissionTo($setion5_application_ibcb_delete);
        }

        $setion5_application_inspectors_add    = Permission::firstOrCreate(['name' => 'add-setion5-application-inspector']);
        $setion5_application_inspectors_view   = Permission::firstOrCreate(['name' => 'view-setion5-application-inspector']);
        $setion5_application_inspectors_edit   = Permission::firstOrCreate(['name' => 'edit-setion5-application-inspector']);
        $setion5_application_inspectors_delete = Permission::firstOrCreate(['name' => 'delete-setion5-application-inspector']);

        if (!$admin->hasPermission($setion5_application_inspectors_add)) {
            $admin_role->givePermissionTo($setion5_application_inspectors_add);
        }
        if (!$admin->hasPermission($setion5_application_inspectors_view)) {
            $admin_role->givePermissionTo($setion5_application_inspectors_view);
        }
        if (!$admin->hasPermission($setion5_application_inspectors_edit)) {
            $admin_role->givePermissionTo($setion5_application_inspectors_edit);
        }
        if (!$admin->hasPermission($setion5_application_inspectors_delete)) {
            $admin_role->givePermissionTo($setion5_application_inspectors_delete);
        }

        $setion5_application_lab_add    = Permission::firstOrCreate(['name' => 'add-setion5-application-lab']);
        $setion5_application_lab_view   = Permission::firstOrCreate(['name' => 'view-setion5-application-lab']);
        $setion5_application_lab_edit   = Permission::firstOrCreate(['name' => 'edit-setion5-application-lab']);
        $setion5_application_lab_delete = Permission::firstOrCreate(['name' => 'delete-setion5-application-lab']);

        if (!$admin->hasPermission($setion5_application_lab_add)) {
            $admin_role->givePermissionTo($setion5_application_lab_add);
        }
        if (!$admin->hasPermission($setion5_application_lab_view)) {
            $admin_role->givePermissionTo($setion5_application_lab_view);
        }
        if (!$admin->hasPermission($setion5_application_lab_edit)) {
            $admin_role->givePermissionTo($setion5_application_lab_edit);
        }
        if (!$admin->hasPermission($setion5_application_lab_delete)) {
            $admin_role->givePermissionTo($setion5_application_lab_delete);
        }


        $bcertify_setting_fee_add    = Permission::firstOrCreate(['name' => 'add-bcertify-setting-fee']);
        $bcertify_setting_fee_view   = Permission::firstOrCreate(['name' => 'view-bcertify-setting-fee']);
        $bcertify_setting_fee_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-setting-fee']);
        $bcertify_setting_fee_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-setting-fee']);

        if (!$admin->hasPermission($bcertify_setting_fee_add)) {
            $admin_role->givePermissionTo($bcertify_setting_fee_add);
        }
        if (!$admin->hasPermission($bcertify_setting_fee_view)) {
            $admin_role->givePermissionTo($bcertify_setting_fee_view);
        }
        if (!$admin->hasPermission($bcertify_setting_fee_edit)) {
            $admin_role->givePermissionTo($bcertify_setting_fee_edit);
        }
        if (!$admin->hasPermission($bcertify_setting_fee_delete)) {
            $admin_role->givePermissionTo($bcertify_setting_fee_delete);
        }


        $bcertify_setting_running_add    = Permission::firstOrCreate(['name' => 'add-bcertify-setting-running']);
        $bcertify_setting_running_view   = Permission::firstOrCreate(['name' => 'view-bcertify-setting-running']);
        $bcertify_setting_running_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-setting-running']);
        $bcertify_setting_running_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-setting-running']);

        if (!$admin->hasPermission($bcertify_setting_running_add)) {
            $admin_role->givePermissionTo($bcertify_setting_running_add);
        }
        if (!$admin->hasPermission($bcertify_setting_running_view)) {
            $admin_role->givePermissionTo($bcertify_setting_running_view);
        }
        if (!$admin->hasPermission($bcertify_setting_running_edit)) {
            $admin_role->givePermissionTo($bcertify_setting_running_edit);
        }
        if (!$admin->hasPermission($bcertify_setting_running_delete)) {
            $admin_role->givePermissionTo($bcertify_setting_running_delete);
        }

        $law_process_product_add    = Permission::firstOrCreate(['name' => 'add-law-process-product']);
        $law_process_product_view   = Permission::firstOrCreate(['name' => 'view-law-process-product']);
        $law_process_product_edit   = Permission::firstOrCreate(['name' => 'edit-law-process-product']);
        $law_process_product_delete = Permission::firstOrCreate(['name' => 'delete-law-process-product']);

        if (!$admin->hasPermission($law_process_product_add)) {
            $admin_role->givePermissionTo($law_process_product_add);
        }
        if (!$admin->hasPermission($law_process_product_view)) {
            $admin_role->givePermissionTo($law_process_product_view);
        }
        if (!$admin->hasPermission($law_process_product_edit)) {
            $admin_role->givePermissionTo($law_process_product_edit);
        }
        if (!$admin->hasPermission($law_process_product_delete)) {
            $admin_role->givePermissionTo($law_process_product_delete);
        }

        $law_resource_add    = Permission::firstOrCreate(['name' => 'add-law-resource']);
        $law_resource_view   = Permission::firstOrCreate(['name' => 'view-law-resource']);
        $law_resource_edit   = Permission::firstOrCreate(['name' => 'edit-law-resource']);
        $law_resource_delete = Permission::firstOrCreate(['name' => 'delete-law-resource']);

        if (!$admin->hasPermission($law_resource_add)) {
            $admin_role->givePermissionTo($law_resource_add);
        }
        if (!$admin->hasPermission($law_resource_view)) {
            $admin_role->givePermissionTo($law_resource_view);
        }
        if (!$admin->hasPermission($law_resource_edit)) {
            $admin_role->givePermissionTo($law_resource_edit);
        }
        if (!$admin->hasPermission($law_resource_delete)) {
            $admin_role->givePermissionTo($law_resource_delete);
        }

        $law_division_type_add    = Permission::firstOrCreate(['name' => 'add-law-division-type']);
        $law_division_type_view   = Permission::firstOrCreate(['name' => 'view-law-division-type']);
        $law_division_type_edit   = Permission::firstOrCreate(['name' => 'edit-law-division-type']);
        $law_division_type_delete = Permission::firstOrCreate(['name' => 'delete-law-division-type']);

        if (!$admin->hasPermission($law_division_type_add)) {
            $admin_role->givePermissionTo($law_division_type_add);
        }
        if (!$admin->hasPermission($law_division_type_view)) {
            $admin_role->givePermissionTo($law_division_type_view);
        }
        if (!$admin->hasPermission($law_division_type_edit)) {
            $admin_role->givePermissionTo($law_division_type_edit);
        }
        if (!$admin->hasPermission($law_division_type_delete)) {
            $admin_role->givePermissionTo($law_division_type_delete);
        }

        $law_reward_group_add    = Permission::firstOrCreate(['name' => 'add-law-reward-group']);
        $law_reward_group_view   = Permission::firstOrCreate(['name' => 'view-law-reward-group']);
        $law_reward_group_edit   = Permission::firstOrCreate(['name' => 'edit-law-reward-group']);
        $law_reward_group_delete = Permission::firstOrCreate(['name' => 'delete-law-reward-group']);

        if (!$admin->hasPermission($law_reward_group_add)) {
            $admin_role->givePermissionTo($law_reward_group_add);
        }
        if (!$admin->hasPermission($law_reward_group_view)) {
            $admin_role->givePermissionTo($law_reward_group_view);
        }
        if (!$admin->hasPermission($law_reward_group_edit)) {
            $admin_role->givePermissionTo($law_reward_group_edit);
        }
        if (!$admin->hasPermission($law_reward_group_delete)) {
            $admin_role->givePermissionTo($law_reward_group_delete);
        }


        $law_type_file_add    = Permission::firstOrCreate(['name' => 'add-law-type-file']);
        $law_type_file_view   = Permission::firstOrCreate(['name' => 'view-law-type-file']);
        $law_type_file_edit   = Permission::firstOrCreate(['name' => 'edit-law-type-file']);
        $law_type_file_delete = Permission::firstOrCreate(['name' => 'delete-law-type-file']);

        if (!$admin->hasPermission($law_type_file_add)) {
            $admin_role->givePermissionTo($law_type_file_add);
        }
        if (!$admin->hasPermission($law_type_file_view)) {
            $admin_role->givePermissionTo($law_type_file_view);
        }
        if (!$admin->hasPermission($law_type_file_edit)) {
            $admin_role->givePermissionTo($law_type_file_edit);
        }
        if (!$admin->hasPermission($law_type_file_delete)) {
            $admin_role->givePermissionTo($law_type_file_delete);
        }

        $law_config_sections_add    = Permission::firstOrCreate(['name' => 'add-law-config-sections']);
        $law_config_sections_view   = Permission::firstOrCreate(['name' => 'view-law-config-sections']);
        $law_config_sections_edit   = Permission::firstOrCreate(['name' => 'edit-law-config-sections']);
        $law_config_sections_delete = Permission::firstOrCreate(['name' => 'delete-law-config-sections']);

        if (!$admin->hasPermission($law_config_sections_add)) {
            $admin_role->givePermissionTo($law_config_sections_add);
        }
        if (!$admin->hasPermission($law_config_sections_view)) {
            $admin_role->givePermissionTo($law_config_sections_view);
        }
        if (!$admin->hasPermission($law_config_sections_edit)) {
            $admin_role->givePermissionTo($law_config_sections_edit);
        }
        if (!$admin->hasPermission($law_config_sections_delete)) {
            $admin_role->givePermissionTo($law_config_sections_delete);
        }

        $law_config_reward_add    = Permission::firstOrCreate(['name' => 'add-law-config-reward']);
        $law_config_reward_view   = Permission::firstOrCreate(['name' => 'view-law-config-reward']);
        $law_config_reward_edit   = Permission::firstOrCreate(['name' => 'edit-law-config-reward']);
        $law_config_reward_delete = Permission::firstOrCreate(['name' => 'delete-law-config-reward']);

        if (!$admin->hasPermission($law_config_reward_add)) {
            $admin_role->givePermissionTo($law_config_reward_add);
        }
        if (!$admin->hasPermission($law_config_reward_view)) {
            $admin_role->givePermissionTo($law_config_reward_view);
        }
        if (!$admin->hasPermission($law_config_reward_edit)) {
            $admin_role->givePermissionTo($law_config_reward_edit);
        }
        if (!$admin->hasPermission($law_config_reward_delete)) {
            $admin_role->givePermissionTo($law_config_reward_delete);
        }

        $law_config_notification_add    = Permission::firstOrCreate(['name' => 'add-law-config-notification']);
        $law_config_notification_view   = Permission::firstOrCreate(['name' => 'view-law-config-notification']);
        $law_config_notification_edit   = Permission::firstOrCreate(['name' => 'edit-law-config-notification']);
        $law_config_notification_delete = Permission::firstOrCreate(['name' => 'delete-law-config-notification']);

        if (!$admin->hasPermission($law_config_notification_add)) {
            $admin_role->givePermissionTo($law_config_notification_add);
        }
        if (!$admin->hasPermission($law_config_notification_view)) {
            $admin_role->givePermissionTo($law_config_notification_view);
        }
        if (!$admin->hasPermission($law_config_notification_edit)) {
            $admin_role->givePermissionTo($law_config_notification_edit);
        }
        if (!$admin->hasPermission($law_config_notification_delete)) {
            $admin_role->givePermissionTo($law_config_notification_delete);
        }

        $law_book_type_add    = Permission::firstOrCreate(['name' => 'add-law-book-type']);
        $law_book_type_view   = Permission::firstOrCreate(['name' => 'view-law-book-type']);
        $law_book_type_edit   = Permission::firstOrCreate(['name' => 'edit-law-book-type']);
        $law_book_type_delete = Permission::firstOrCreate(['name' => 'delete-law-book-type']);

        if (!$admin->hasPermission($law_book_type_add)) {
            $admin_role->givePermissionTo($law_book_type_add);
        }
        if (!$admin->hasPermission($law_book_type_view)) {
            $admin_role->givePermissionTo($law_book_type_view);
        }
        if (!$admin->hasPermission($law_book_type_edit)) {
            $admin_role->givePermissionTo($law_book_type_edit);
        }
        if (!$admin->hasPermission($law_book_type_delete)) {
            $admin_role->givePermissionTo($law_book_type_delete);
        }

        $law_book_group_add    = Permission::firstOrCreate(['name' => 'add-law-book-group']);
        $law_book_group_view   = Permission::firstOrCreate(['name' => 'view-law-book-group']);
        $law_book_group_edit   = Permission::firstOrCreate(['name' => 'edit-law-book-group']);
        $law_book_group_delete = Permission::firstOrCreate(['name' => 'delete-law-book-group']);

        if (!$admin->hasPermission($law_book_group_add)) {
            $admin_role->givePermissionTo($law_book_group_add);
        }
        if (!$admin->hasPermission($law_book_group_view)) {
            $admin_role->givePermissionTo($law_book_group_view);
        }
        if (!$admin->hasPermission($law_book_group_edit)) {
            $admin_role->givePermissionTo($law_book_group_edit);
        }
        if (!$admin->hasPermission($law_book_group_delete)) {
            $admin_role->givePermissionTo($law_book_group_delete);
        }

        $law_book_search_add    = Permission::firstOrCreate(['name' => 'add-law-book-search']);
        $law_book_search_view   = Permission::firstOrCreate(['name' => 'view-law-book-search']);
        $law_book_search_edit   = Permission::firstOrCreate(['name' => 'edit-law-book-search']);
        $law_book_search_delete = Permission::firstOrCreate(['name' => 'delete-law-book-search']);

        if (!$admin->hasPermission($law_book_search_add)) {
            $admin_role->givePermissionTo($law_book_search_add);
        }
        if (!$admin->hasPermission($law_book_search_view)) {
            $admin_role->givePermissionTo($law_book_search_view);
        }
        if (!$admin->hasPermission($law_book_search_edit)) {
            $admin_role->givePermissionTo($law_book_search_edit);
        }
        if (!$admin->hasPermission($law_book_search_delete)) {
            $admin_role->givePermissionTo($law_book_search_delete);
        }

        $law_book_manage_add    = Permission::firstOrCreate(['name' => 'add-law-book-manage']);
        $law_book_manage_view   = Permission::firstOrCreate(['name' => 'view-law-book-manage']);
        $law_book_manage_edit   = Permission::firstOrCreate(['name' => 'edit-law-book-manage']);
        $law_book_manage_delete = Permission::firstOrCreate(['name' => 'delete-law-book-manage']);

        if (!$admin->hasPermission($law_book_manage_add)) {
            $admin_role->givePermissionTo($law_book_manage_add);
        }
        if (!$admin->hasPermission($law_book_manage_view)) {
            $admin_role->givePermissionTo($law_book_manage_view);
        }
        if (!$admin->hasPermission($law_book_manage_edit)) {
            $admin_role->givePermissionTo($law_book_manage_edit);
        }
        if (!$admin->hasPermission($law_book_manage_delete)) {
            $admin_role->givePermissionTo($law_book_manage_delete);
        }

        $law_license_report_add    = Permission::firstOrCreate(['name' => 'add-license-report']);
        $law_license_report_view   = Permission::firstOrCreate(['name' => 'view-license-report']);
        $law_license_report_edit   = Permission::firstOrCreate(['name' => 'edit-license-report']);
        $law_license_report_delete = Permission::firstOrCreate(['name' => 'delete-license-report']);

        if (!$admin->hasPermission($law_license_report_add)) {
            $admin_role->givePermissionTo($law_license_report_add);
        }
        if (!$admin->hasPermission($law_license_report_view)) {
            $admin_role->givePermissionTo($law_license_report_view);
        }
        if (!$admin->hasPermission($law_license_report_edit)) {
            $admin_role->givePermissionTo($law_license_report_edit);
        }
        if (!$admin->hasPermission($law_license_report_delete)) {
            $admin_role->givePermissionTo($law_license_report_delete);
        }

        $report_roles_add    = Permission::firstOrCreate(['name' => 'add-report-roles']);
        $report_roles_view   = Permission::firstOrCreate(['name' => 'view-report-roles']);
        $report_roles_edit   = Permission::firstOrCreate(['name' => 'edit-report-roles']);
        $report_roles_delete = Permission::firstOrCreate(['name' => 'delete-report-roles']);

        if (!$admin->hasPermission($report_roles_add)) {
            $admin_role->givePermissionTo($report_roles_add);
        }
        if (!$admin->hasPermission($report_roles_view)) {
            $admin_role->givePermissionTo($report_roles_view);
        }
        if (!$admin->hasPermission($report_roles_edit)) {
            $admin_role->givePermissionTo($report_roles_edit);
        }
        if (!$admin->hasPermission($report_roles_delete)) {
            $admin_role->givePermissionTo($report_roles_delete);
        }

        $law_basic_job_type_add    = Permission::firstOrCreate(['name' => 'add-law-basic-job-type']);
        $law_basic_job_type_view   = Permission::firstOrCreate(['name' => 'view-law-basic-job-type']);
        $law_basic_job_type_edit   = Permission::firstOrCreate(['name' => 'edit-law-basic-job-type']);
        $law_basic_job_type_delete = Permission::firstOrCreate(['name' => 'delete-law-basic-job-type']);

        if (!$admin->hasPermission($law_basic_job_type_add)) {
            $admin_role->givePermissionTo($law_basic_job_type_add);
        }
        if (!$admin->hasPermission($law_basic_job_type_view)) {
            $admin_role->givePermissionTo($law_basic_job_type_view);
        }
        if (!$admin->hasPermission($law_basic_job_type_edit)) {
            $admin_role->givePermissionTo($law_basic_job_type_edit);
        }
        if (!$admin->hasPermission($law_basic_job_type_delete)) {
            $admin_role->givePermissionTo($law_basic_job_type_delete);
        }

        $law_basic_status_operation_add    = Permission::firstOrCreate(['name' => 'add-law-basic-status-operation']);
        $law_basic_status_operation_view   = Permission::firstOrCreate(['name' => 'view-law-basic-status-operation']);
        $law_basic_status_operation_edit   = Permission::firstOrCreate(['name' => 'edit-law-basic-status-operation']);
        $law_basic_status_operation_delete = Permission::firstOrCreate(['name' => 'delete-law-basic-status-operation']);

        if (!$admin->hasPermission($law_basic_status_operation_add)) {
            $admin_role->givePermissionTo($law_basic_status_operation_add);
        }
        if (!$admin->hasPermission($law_basic_status_operation_view)) {
            $admin_role->givePermissionTo($law_basic_status_operation_view);
        }
        if (!$admin->hasPermission($law_basic_status_operation_edit)) {
            $admin_role->givePermissionTo($law_basic_status_operation_edit);
        }
        if (!$admin->hasPermission($law_basic_status_operation_delete)) {
            $admin_role->givePermissionTo($law_basic_status_operation_delete);
        }

        $law_track_receive_add         = Permission::firstOrCreate(['name' => 'add-law-track-receive']);
        $law_track_receive_view        = Permission::firstOrCreate(['name' => 'view-law-track-receive']);
        $law_track_receive_edit        = Permission::firstOrCreate(['name' => 'edit-law-track-receive']);
        $law_track_receive_delete      = Permission::firstOrCreate(['name' => 'delete-law-track-receive']);
        $law_track_receive_other       = Permission::firstOrCreate(['name' => 'other-law-track-receive']);
        $law_track_receive_assign_work = Permission::firstOrCreate(['name' => 'assign_work-law-track-receive']);
        $law_track_receive_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-track-receive']);

        if (!$admin->hasPermission($law_track_receive_add)) {
            $admin_role->givePermissionTo($law_track_receive_add);
        }
        if (!$admin->hasPermission($law_track_receive_view)) {
            $admin_role->givePermissionTo($law_track_receive_view);
        }
        if (!$admin->hasPermission($law_track_receive_edit)) {
            $admin_role->givePermissionTo($law_track_receive_edit);
        }
        if (!$admin->hasPermission($law_track_receive_delete)) {
            $admin_role->givePermissionTo($law_track_receive_delete);
        }
        if (!$admin->hasPermission($law_track_receive_other)) {
            $admin_role->givePermissionTo($law_track_receive_other);
        }
        if (!$admin->hasPermission($law_track_receive_assign_work)) {
            $admin_role->givePermissionTo($law_track_receive_assign_work);
        }
        if (!$admin->hasPermission($law_track_receive_view_all)) {
            $admin_role->givePermissionTo($law_track_receive_view_all);
        }

        $law_track_operation_add         = Permission::firstOrCreate(['name' => 'add-law-track-operation']);
        $law_track_operation_view        = Permission::firstOrCreate(['name' => 'view-law-track-operation']);
        $law_track_operation_edit        = Permission::firstOrCreate(['name' => 'edit-law-track-operation']);
        $law_track_operation_delete      = Permission::firstOrCreate(['name' => 'delete-law-track-operation']);
        $law_track_operation_other       = Permission::firstOrCreate(['name' => 'other-law-track-operation']);
        $law_track_operation_assign_work = Permission::firstOrCreate(['name' => 'assign_work-law-track-operation']);
        $law_track_operation_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-track-operation']);

        if (!$admin->hasPermission($law_track_operation_add)) {
            $admin_role->givePermissionTo($law_track_operation_add);
        }
        if (!$admin->hasPermission($law_track_operation_view)) {
            $admin_role->givePermissionTo($law_track_operation_view);
        }
        if (!$admin->hasPermission($law_track_operation_edit)) {
            $admin_role->givePermissionTo($law_track_operation_edit);
        }
        if (!$admin->hasPermission($law_track_operation_delete)) {
            $admin_role->givePermissionTo($law_track_operation_delete);
        }
        if (!$admin->hasPermission($law_track_operation_other)) {
            $admin_role->givePermissionTo($law_track_operation_other);
        }
        if (!$admin->hasPermission($law_track_operation_assign_work)) {
            $admin_role->givePermissionTo($law_track_operation_assign_work);
        }
        if (!$admin->hasPermission($law_track_operation_view_all)) {
            $admin_role->givePermissionTo($law_track_operation_view_all);
        }

        $law_basic_category_operation_add    = Permission::firstOrCreate(['name' => 'add-law-basic-category-operation']);
        $law_basic_category_operation_view   = Permission::firstOrCreate(['name' => 'view-law-basic-category-operation']);
        $law_basic_category_operation_edit   = Permission::firstOrCreate(['name' => 'edit-law-basic-category-operation']);
        $law_basic_category_operation_delete = Permission::firstOrCreate(['name' => 'delete-law-basic-category-operation']);

        if (!$admin->hasPermission($law_basic_category_operation_add)) {
            $admin_role->givePermissionTo($law_basic_category_operation_add);
        }
        if (!$admin->hasPermission($law_basic_category_operation_view)) {
            $admin_role->givePermissionTo($law_basic_category_operation_view);
        }
        if (!$admin->hasPermission($law_basic_category_operation_edit)) {
            $admin_role->givePermissionTo($law_basic_category_operation_edit);
        }
        if (!$admin->hasPermission($law_basic_category_operation_delete)) {
            $admin_role->givePermissionTo($law_basic_category_operation_delete);
        }

        $law_listen_ministry_add         = Permission::firstOrCreate(['name' => 'add-law-listen-ministry']);
        $law_listen_ministry_view        = Permission::firstOrCreate(['name' => 'view-law-listen-ministry']);
        $law_listen_ministry_edit        = Permission::firstOrCreate(['name' => 'edit-law-listen-ministry']);
        $law_listen_ministry_delete      = Permission::firstOrCreate(['name' => 'delete-law-listen-ministry']);
        $law_listen_ministry_other       = Permission::firstOrCreate(['name' => 'other-law-listen-ministry']);
        $law_listen_ministry_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-listen-ministry']);
        $law_listen_ministry_printing    = Permission::firstOrCreate(['name' => 'printing-law-listen-ministry']);

        if (!$admin->hasPermission($law_listen_ministry_add)) {
            $admin_role->givePermissionTo($law_listen_ministry_add);
        }
        if (!$admin->hasPermission($law_listen_ministry_view)) {
            $admin_role->givePermissionTo($law_listen_ministry_view);
        }
        if (!$admin->hasPermission($law_listen_ministry_edit)) {
            $admin_role->givePermissionTo($law_listen_ministry_edit);
        }
        if (!$admin->hasPermission($law_listen_ministry_delete)) {
            $admin_role->givePermissionTo($law_listen_ministry_delete);
        }
        if (!$admin->hasPermission($law_listen_ministry_other)) {
            $admin_role->givePermissionTo($law_listen_ministry_other);
        }
        if (!$admin->hasPermission($law_listen_ministry_view_all)) {
            $admin_role->givePermissionTo($law_listen_ministry_view_all);
        }
        if (!$admin->hasPermission($law_listen_ministry_printing)) {
            $admin_role->givePermissionTo($law_listen_ministry_printing);
        }

        $law_track_views_add         = Permission::firstOrCreate(['name' => 'add-law-track-views']);
        $law_track_views_view        = Permission::firstOrCreate(['name' => 'view-law-track-views']);
        $law_track_views_edit        = Permission::firstOrCreate(['name' => 'edit-law-track-views']);
        $law_track_views_delete      = Permission::firstOrCreate(['name' => 'delete-law-track-views']);
        $law_track_views_other       = Permission::firstOrCreate(['name' => 'other-law-track-views']);
        $law_track_views_assign_work = Permission::firstOrCreate(['name' => 'assign_work-law-track-views']);
        $law_track_views_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-track-views']);

        if (!$admin->hasPermission($law_track_views_add)) {
            $admin_role->givePermissionTo($law_track_views_add);
        }
        if (!$admin->hasPermission($law_track_views_view)) {
            $admin_role->givePermissionTo($law_track_views_view);
        }
        if (!$admin->hasPermission($law_track_views_edit)) {
            $admin_role->givePermissionTo($law_track_views_edit);
        }
        if (!$admin->hasPermission($law_track_views_delete)) {
            $admin_role->givePermissionTo($law_track_views_delete);
        }
        if (!$admin->hasPermission($law_track_views_other)) {
            $admin_role->givePermissionTo($law_track_views_other);
        }
        if (!$admin->hasPermission($law_track_views_assign_work)) {
            $admin_role->givePermissionTo($law_track_views_assign_work);
        }
        if (!$admin->hasPermission($law_track_views_view_all)) {
            $admin_role->givePermissionTo($law_track_views_view_all);
        }

        $law_report_log_working_add         = Permission::firstOrCreate(['name' => 'add-law-report-log-working']);
        $law_lreport_log_working_view        = Permission::firstOrCreate(['name' => 'view-law-report-log-working']);
        $law_report_log_working_edit        = Permission::firstOrCreate(['name' => 'edit-law-report-log-working']);
        $law_report_log_working_delete      = Permission::firstOrCreate(['name' => 'delete-law-report-log-working']);


        if (!$admin->hasPermission($law_report_log_working_add)) {
            $admin_role->givePermissionTo($law_report_log_working_add);
        }
        if (!$admin->hasPermission($law_lreport_log_working_view)) {
            $admin_role->givePermissionTo($law_lreport_log_working_view);
        }
        if (!$admin->hasPermission($law_report_log_working_edit)) {
            $admin_role->givePermissionTo($law_report_log_working_edit);
        }
        if (!$admin->hasPermission($law_report_log_working_delete)) {
            $admin_role->givePermissionTo($law_report_log_working_delete);
        }

        $law_cases_forms_add         = Permission::firstOrCreate(['name' => 'add-law-cases-forms']);
        $law_cases_forms_view        = Permission::firstOrCreate(['name' => 'view-law-cases-forms']);
        $law_cases_forms_edit        = Permission::firstOrCreate(['name' => 'edit-law-cases-forms']);
        $law_cases_forms_delete      = Permission::firstOrCreate(['name' => 'delete-law-cases-forms']);
        $law_cases_forms_other       = Permission::firstOrCreate(['name' => 'other-law-cases-forms']);
        $law_cases_forms_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-cases-forms']);

        if (!$admin->hasPermission($law_cases_forms_add)) {
            $admin_role->givePermissionTo($law_cases_forms_add);
        }
        if (!$admin->hasPermission($law_cases_forms_view)) {
            $admin_role->givePermissionTo($law_cases_forms_view);
        }
        if (!$admin->hasPermission($law_cases_forms_edit)) {
            $admin_role->givePermissionTo($law_cases_forms_edit);
        }
        if (!$admin->hasPermission($law_cases_forms_delete)) {
            $admin_role->givePermissionTo($law_cases_forms_delete);
        }
        if (!$admin->hasPermission($law_cases_forms_other)) {
            $admin_role->givePermissionTo($law_cases_forms_other);
        }
        if (!$admin->hasPermission($law_cases_forms_view_all)) {
            $admin_role->givePermissionTo($law_cases_forms_view_all);
        }

        $law_report_summary_track_person_add    = Permission::firstOrCreate(['name' => 'add-law-report-summary-track-person']);
        $law_report_summary_track_person_view   = Permission::firstOrCreate(['name' => 'view-law-report-summary-track-person']);
        $law_report_summary_track_person_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-summary-track-person']);
        $law_report_summary_track_person_delete = Permission::firstOrCreate(['name' => 'delete-law-report-summary-track-person']);
        $law_report_summary_track_person_other = Permission::firstOrCreate(['name' => 'other-law-report-summary-track-person']);
        $law_report_summary_track_person_export = Permission::firstOrCreate(['name' => 'export-law-report-summary-track-person']);

        if (!$admin->hasPermission($law_report_summary_track_person_add)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_add);
        }
        if (!$admin->hasPermission($law_report_summary_track_person_view)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_view);
        }
        if (!$admin->hasPermission($law_report_summary_track_person_edit)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_edit);
        }
        if (!$admin->hasPermission($law_report_summary_track_person_delete)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_delete);
        }
        if (!$admin->hasPermission($law_report_summary_track_person_other)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_other);
        }
        if (!$admin->hasPermission($law_report_summary_track_person_export)) {
            $admin_role->givePermissionTo($law_report_summary_track_person_export);
        }


        $law_dashboard_view        = Permission::firstOrCreate(['name' => 'view-law-dashboard']);

        if (!$admin->hasPermission($law_dashboard_view)) {
            $admin_role->givePermissionTo($law_dashboard_view);
        }

        $law_case_assign_add                 = Permission::firstOrCreate(['name' => 'add-law-cases-assign']);
        $law_lreport_log_working_view        = Permission::firstOrCreate(['name' => 'view-law-cases-assign']);
        $law_case_assign_edit                = Permission::firstOrCreate(['name' => 'edit-law-cases-assign']);
        $law_case_assign_delete              = Permission::firstOrCreate(['name' => 'delete-law-cases-assign']);
        $law_case_assign_other               = Permission::firstOrCreate(['name' => 'other-law-cases-assign']);
        $law_case_assign_assign_work         = Permission::firstOrCreate(['name' => 'assign_work-law-cases-assign']);
        $law_case_assign_view_all            = Permission::firstOrCreate(['name' => 'view_all-law-cases-assign']);
        
        if (!$admin->hasPermission($law_case_assign_add)) {
            $admin_role->givePermissionTo($law_case_assign_add);
        }
        if (!$admin->hasPermission($law_lreport_log_working_view)) {
            $admin_role->givePermissionTo($law_lreport_log_working_view);
        }
        if (!$admin->hasPermission($law_case_assign_edit)) {
            $admin_role->givePermissionTo($law_case_assign_edit);
        }
        if (!$admin->hasPermission($law_case_assign_delete)) {
            $admin_role->givePermissionTo($law_case_assign_delete);
        }
        if (!$admin->hasPermission($law_case_assign_other)) {
            $admin_role->givePermissionTo($law_case_assign_other);
        }
        if (!$admin->hasPermission($law_case_assign_assign_work)) {
            $admin_role->givePermissionTo($law_case_assign_assign_work);
        }
        if (!$admin->hasPermission($law_case_assign_view_all)) {
            $admin_role->givePermissionTo($law_case_assign_view_all);
        }

        //แจ้งเตือน
        $law_notifys_add      = Permission::firstOrCreate(['name' => 'add-law-notifys']);
        $law_notifys_view     = Permission::firstOrCreate(['name' => 'view-law-notifys']);
        $law_notifys_edit     = Permission::firstOrCreate(['name' => 'edit-law-notifys']);
        $law_notifys_delete   = Permission::firstOrCreate(['name' => 'delete-law-notifys']);
        $law_notifys_view_all = Permission::firstOrCreate(['name' => 'view_all-law-notifys']);

        if (!$admin->hasPermission($law_notifys_add)) {
            $admin_role->givePermissionTo($law_notifys_add);
        }
        if (!$admin->hasPermission($law_notifys_view)) {
            $admin_role->givePermissionTo($law_notifys_view);
        }
        if (!$admin->hasPermission($law_notifys_edit)) {
            $admin_role->givePermissionTo($law_notifys_edit);
        }
        if (!$admin->hasPermission($law_notifys_delete)) {
            $admin_role->givePermissionTo($law_notifys_delete);
        }
        if (!$admin->hasPermission($law_notifys_view_all)) {
            $admin_role->givePermissionTo($law_notifys_view_all);
        }

        $law_listen_ministry_response_add         = Permission::firstOrCreate(['name' => 'add-law-listen-ministry-response']);
        $law_listen_ministry_response_view        = Permission::firstOrCreate(['name' => 'view-law-listen-ministry-response']);
        $law_listen_ministry_response_edit        = Permission::firstOrCreate(['name' => 'edit-law-listen-ministry-response']);
        $law_listen_ministry_response_delete      = Permission::firstOrCreate(['name' => 'delete-law-listen-ministry-response']);
        $law_listen_ministry_response_other       = Permission::firstOrCreate(['name' => 'other-law-listen-ministry-response']);
        $law_listen_ministry_response_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-listen-ministry-response']);
        $law_listen_ministry_response_printing    = Permission::firstOrCreate(['name' => 'printing-law-listen-ministry-response']);

        if (!$admin->hasPermission($law_listen_ministry_response_add)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_add);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_view)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_view);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_edit)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_edit);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_delete)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_delete);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_other)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_other);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_view_all)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_view_all);
        }
        if (!$admin->hasPermission($law_listen_ministry_response_printing)) {
            $admin_role->givePermissionTo($law_listen_ministry_response_printing);
        }


        $law_config_system_category_add    = Permission::firstOrCreate(['name' => 'add-law-config-system-category']);
        $law_config_system_category_view   = Permission::firstOrCreate(['name' => 'view-law-config-system-category']);
        $law_config_system_category_edit   = Permission::firstOrCreate(['name' => 'edit-law-config-system-category']);
        $law_config_system_category_delete = Permission::firstOrCreate(['name' => 'delete-law-config-system-category']);

        if (!$admin->hasPermission($law_config_system_category_add)) {
            $admin_role->givePermissionTo($law_config_system_category_add);
        }
        if (!$admin->hasPermission($law_config_system_category_view)) {
            $admin_role->givePermissionTo($law_config_system_category_view);
        }
        if (!$admin->hasPermission($law_config_system_category_edit)) {
            $admin_role->givePermissionTo($law_config_system_category_edit);
        }
        if (!$admin->hasPermission($law_config_system_category_delete)) {
            $admin_role->givePermissionTo($law_config_system_category_delete);
        }

        
        $law_listen_ministry_summary_add       = Permission::firstOrCreate(['name' => 'add-law-listen-ministry-summary']);
        $law_listen_ministry_summary_view      = Permission::firstOrCreate(['name' => 'view-law-listen-ministry-summary']);
        $law_listen_ministry_summary_edit      = Permission::firstOrCreate(['name' => 'edit-law-listen-ministry-summary']);
        $law_listen_ministry_summary_delete    = Permission::firstOrCreate(['name' => 'delete-law-listen-ministry-summary']);
        $law_listen_ministry_summary_other     = Permission::firstOrCreate(['name' => 'other-law-listen-ministry-summary']);
        $law_listen_ministry_summary_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-listen-ministry-summary']);
        $law_listen_ministry_summary_printing  = Permission::firstOrCreate(['name' => 'printing-law-listen-ministry-summary']);

        if (!$admin->hasPermission($law_listen_ministry_summary_add)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_add);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_view)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_view);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_edit)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_edit);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_delete)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_delete);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_other)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_other);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_view_all)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_view_all);
        }
        if (!$admin->hasPermission($law_listen_ministry_summary_printing)) {
            $admin_role->givePermissionTo($law_listen_ministry_summary_printing);
        }

        $law_basic_listen_type_add    = Permission::firstOrCreate(['name' => 'add-law-basic-listen-type']);
        $law_basic_listen_type_view   = Permission::firstOrCreate(['name' => 'view-law-basic-listen-type']);
        $law_basic_listen_type_edit   = Permission::firstOrCreate(['name' => 'edit-law-basic-listen-type']);
        $law_basic_listen_type_delete = Permission::firstOrCreate(['name' => 'delete-law-basic-listen-type']);

        if (!$admin->hasPermission($law_basic_listen_type_add)) {
            $admin_role->givePermissionTo($law_basic_listen_type_add);
        }
        if (!$admin->hasPermission($law_basic_listen_type_view)) {
            $admin_role->givePermissionTo($law_basic_listen_type_view);
        }
        if (!$admin->hasPermission($law_basic_listen_type_edit)) {
            $admin_role->givePermissionTo($law_basic_listen_type_edit);
        }
        if (!$admin->hasPermission($law_basic_listen_type_delete)) {
            $admin_role->givePermissionTo($law_basic_listen_type_delete);
        }

        $law_report_book_list_add    = Permission::firstOrCreate(['name' => 'add-law-report-book-list']);
        $law_report_book_list_view   = Permission::firstOrCreate(['name' => 'view-law-report-book-list']);
        $law_report_book_list_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-book-list']);
        $law_report_book_list_delete = Permission::firstOrCreate(['name' => 'delete-law-report-book-list']);
        $law_report_book_list_other = Permission::firstOrCreate(['name' => 'other-law-report-book-list']);
        $law_report_book_list_export = Permission::firstOrCreate(['name' => 'export-law-report-book-list']);

        if (!$admin->hasPermission($law_report_book_list_add)) {
            $admin_role->givePermissionTo($law_report_book_list_add);
        }
        if (!$admin->hasPermission($law_report_book_list_view)) {
            $admin_role->givePermissionTo($law_report_book_list_view);
        }
        if (!$admin->hasPermission($law_report_book_list_edit)) {
            $admin_role->givePermissionTo($law_report_book_list_edit);
        }
        if (!$admin->hasPermission($law_report_book_list_delete)) {
            $admin_role->givePermissionTo($law_report_book_list_delete);
        }
        if (!$admin->hasPermission($law_report_book_list_other)) {
            $admin_role->givePermissionTo($law_report_book_list_other);
        }
        if (!$admin->hasPermission($law_report_book_list_export)) {
            $admin_role->givePermissionTo($law_report_book_list_export);
        }

        //ผลพิจารณางานคดี (law-cases-result)
        $law_case_result_add                 = Permission::firstOrCreate(['name' => 'add-law-cases-result']);
        $law_case_result_view                = Permission::firstOrCreate(['name' => 'view-law-cases-result']);
        $law_case_result_edit                = Permission::firstOrCreate(['name' => 'edit-law-cases-result']);
        $law_case_result_delete              = Permission::firstOrCreate(['name' => 'delete-law-cases-result']);
        $law_case_result_other               = Permission::firstOrCreate(['name' => 'other-law-cases-result']);
        $law_case_result_view_all            = Permission::firstOrCreate(['name' => 'view_all-law-cases-result']);
        $law_case_result_printing            = Permission::firstOrCreate(['name' => 'printing-law-cases-result']);
        
        if (!$admin->hasPermission($law_case_result_add)) {
            $admin_role->givePermissionTo($law_case_result_add);
        }
        if (!$admin->hasPermission($law_case_result_view)) {
            $admin_role->givePermissionTo($law_case_result_view);
        }
        if (!$admin->hasPermission($law_case_result_edit)) {
            $admin_role->givePermissionTo($law_case_result_edit);
        }
        if (!$admin->hasPermission($law_case_result_delete)) {
            $admin_role->givePermissionTo($law_case_result_delete);
        }
        if (!$admin->hasPermission($law_case_result_other)) {
            $admin_role->givePermissionTo($law_case_result_other);
        }
        if (!$admin->hasPermission($law_case_result_view_all)) {
            $admin_role->givePermissionTo($law_case_result_view_all);
        }
        if (!$admin->hasPermission($law_case_result_printing)) {
            $admin_role->givePermissionTo($law_case_result_printing);
        }

        $law_case_offender_add       = Permission::firstOrCreate(['name' => 'add-law-cases-offender']);
        $law_case_offender_view      = Permission::firstOrCreate(['name' => 'view-law-cases-offender']);
        $law_case_offender_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-offender']);
        $law_case_offender_delete    = Permission::firstOrCreate(['name' => 'delete-law-cases-offender']);
        
        if (!$admin->hasPermission($law_case_offender_add)) {
            $admin_role->givePermissionTo($law_case_offender_add);
        }
        if (!$admin->hasPermission($law_case_offender_view)) {
            $admin_role->givePermissionTo($law_case_offender_view);
        }
        if (!$admin->hasPermission($law_case_offender_edit)) {
            $admin_role->givePermissionTo($law_case_offender_edit);
        }
        if (!$admin->hasPermission($law_case_offender_delete)) {
            $admin_role->givePermissionTo($law_case_offender_delete);
        }

       //ตั้งค่างานคดี
        $config_law_add = Permission::firstOrCreate([
            'name' => 'add-config-law'
        ]);
        $config_law_view = Permission::firstOrCreate([
            'name' => 'view-config-law'
        ]);

        if(!$admin->hasPermission($config_law_add)) {
            $admin_role->givePermissionTo($config_law_add);
        }
        if(!$admin->hasPermission($config_law_view)) {
            $admin_role->givePermissionTo($config_law_view);
        }

        //ดำเนินการกับผลิตภัณฑ์ (law-cases-manage-products)
        $law_case_manage_products_add       = Permission::firstOrCreate(['name' => 'add-law-cases-manage-products']);
        $law_case_manage_products_view      = Permission::firstOrCreate(['name' => 'view-law-cases-manage-products']);
        $law_case_manage_products_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-manage-products']);
        $law_case_manage_products_delete    = Permission::firstOrCreate(['name' => 'delete-law-cases-manage-products']);
        $law_case_manage_products_other     = Permission::firstOrCreate(['name' => 'other-law-cases-manage-products']);
        $law_case_manage_products_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-cases-manage-products']);
        $law_case_manage_products_printing  = Permission::firstOrCreate(['name' => 'printing-law-cases-manage-products']);
        
        if (!$admin->hasPermission($law_case_manage_products_add)) {
            $admin_role->givePermissionTo($law_case_manage_products_add);
        }
        if (!$admin->hasPermission($law_case_manage_products_view)) {
            $admin_role->givePermissionTo($law_case_manage_products_view);
        }
        if (!$admin->hasPermission($law_case_manage_products_edit)) {
            $admin_role->givePermissionTo($law_case_manage_products_edit);
        }
        if (!$admin->hasPermission($law_case_manage_products_delete)) {
            $admin_role->givePermissionTo($law_case_manage_products_delete);
        }
        if (!$admin->hasPermission($law_case_manage_products_other)) {
            $admin_role->givePermissionTo($law_case_manage_products_other);
        }
        if (!$admin->hasPermission($law_case_manage_products_view_all)) {
            $admin_role->givePermissionTo($law_case_manage_products_view_all);
        }
        if (!$admin->hasPermission($law_case_manage_products_printing)) {
            $admin_role->givePermissionTo($law_case_manage_products_printing);
        }

        //บันทึกจัดส่งหนังสือ (law-cases-delivery)
        $law_cases_delivery_add         = Permission::firstOrCreate(['name' => 'add-law-cases-delivery']);
        $law_cases_delivery_view        = Permission::firstOrCreate(['name' => 'view-law-cases-delivery']);
        $law_cases_delivery_edit        = Permission::firstOrCreate(['name' => 'edit-law-cases-delivery']);
        $law_cases_delivery_delete      = Permission::firstOrCreate(['name' => 'delete-law-cases-delivery']);
        $law_cases_delivery_other       = Permission::firstOrCreate(['name' => 'other-law-cases-delivery']);
        $law_cases_delivery_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-cases-delivery']);
        $law_cases_delivery_printing    = Permission::firstOrCreate(['name' => 'printing-law-cases-delivery']);

        if (!$admin->hasPermission($law_cases_delivery_add)) {
            $admin_role->givePermissionTo($law_cases_delivery_add);
        }
        if (!$admin->hasPermission($law_cases_delivery_view)) {
            $admin_role->givePermissionTo($law_cases_delivery_view);
        }
        if (!$admin->hasPermission($law_cases_delivery_edit)) {
            $admin_role->givePermissionTo($law_cases_delivery_edit);
        }
        if (!$admin->hasPermission($law_cases_delivery_delete)) {
            $admin_role->givePermissionTo($law_cases_delivery_delete);
        }
        if (!$admin->hasPermission($law_cases_delivery_other)) {
            $admin_role->givePermissionTo($law_cases_delivery_other);
        }
        if (!$admin->hasPermission($law_cases_delivery_view_all)) {
            $admin_role->givePermissionTo($law_cases_delivery_view_all);
        }
        if (!$admin->hasPermission($law_cases_delivery_printing)) {
            $admin_role->givePermissionTo($law_cases_delivery_printing);
        }

        $law_basic_delivery_add         = Permission::firstOrCreate(['name' => 'add-law-basic-delivery']);
        $law_basic_delivery_view        = Permission::firstOrCreate(['name' => 'view-law-basic-delivery']);
        $law_basic_delivery_edit        = Permission::firstOrCreate(['name' => 'edit-law-basic-delivery']);
        $law_basic_delivery_delete      = Permission::firstOrCreate(['name' => 'delete-law-basic-delivery']);

        if (!$admin->hasPermission($law_basic_delivery_add)) {
            $admin_role->givePermissionTo($law_basic_delivery_add);
        }
        if (!$admin->hasPermission($law_basic_delivery_view)) {
            $admin_role->givePermissionTo($law_basic_delivery_view);
        }
        if (!$admin->hasPermission($law_basic_delivery_edit)) {
            $admin_role->givePermissionTo($law_basic_delivery_edit);
        }
        if (!$admin->hasPermission($law_basic_delivery_delete)) {
            $admin_role->givePermissionTo($law_basic_delivery_delete);
        }

        $law_listen_ministry_track_add    = Permission::firstOrCreate(['name' => 'add-law-listen-ministry-track']);
        $law_listen_ministry_track_view   = Permission::firstOrCreate(['name' => 'view-law-listen-ministry-track']);
        $law_listen_ministry_track_edit   = Permission::firstOrCreate(['name' => 'edit-law-listen-ministry-track']);
        $law_listen_ministry_track_delete = Permission::firstOrCreate(['name' => 'delete-law-listen-ministry-track']);
        $law_listen_ministry_track_other     = Permission::firstOrCreate(['name' => 'other-law-listen-ministry-track']);
        $law_listen_ministry_track_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-listen-ministry-track']);

        if (!$admin->hasPermission($law_listen_ministry_track_add)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_add);
        }
        if (!$admin->hasPermission($law_listen_ministry_track_view)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_view);
        }
        if (!$admin->hasPermission($law_listen_ministry_track_edit)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_edit);
        }
        if (!$admin->hasPermission($law_listen_ministry_track_delete)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_delete);
        }
        if (!$admin->hasPermission($law_listen_ministry_track_other)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_other);
        }
        if (!$admin->hasPermission($law_listen_ministry_track_view_all)) {
            $admin_role->givePermissionTo($law_listen_ministry_track_view_all);
        }
               
        //ดำเนินงานคดี (law-cases-operations)
        $law_case_operations_view      = Permission::firstOrCreate(['name' => 'view-law-cases-operations']);
        $law_case_operations_add       = Permission::firstOrCreate(['name' => 'add-law-cases-operations']);
        $law_case_operations_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-operations']);
        $law_case_operations_other     = Permission::firstOrCreate(['name' => 'other-law-cases-operations']);
        $law_case_operations_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-cases-operations']);
        $law_case_operations_printing  = Permission::firstOrCreate(['name' => 'printing-law-cases-operations']);

        if (!$admin->hasPermission($law_case_operations_view)) {
            $admin_role->givePermissionTo($law_case_operations_view);
        }
        if (!$admin->hasPermission($law_case_operations_add)) {
            $admin_role->givePermissionTo($law_case_operations_add);
        }
        if (!$admin->hasPermission($law_case_operations_edit)) {
            $admin_role->givePermissionTo($law_case_operations_edit);
        }
        if (!$admin->hasPermission($law_case_operations_other)) {
            $admin_role->givePermissionTo($law_case_operations_other);
        }
        if (!$admin->hasPermission($law_case_operations_view_all)) {
            $admin_role->givePermissionTo($law_case_operations_view_all);
        }
        if (!$admin->hasPermission($law_case_operations_printing)) {
            $admin_role->givePermissionTo($law_case_operations_printing);
        }

        //ดำเนินการกับใบอนุญาต (law-cases-manage-licenses)
        $law_cases_manage_licenses_view      = Permission::firstOrCreate(['name' => 'view-law-cases-manage-licenses']);
        $law_cases_manage_licenses_add       = Permission::firstOrCreate(['name' => 'add-law-cases-manage-licenses']);
        $law_cases_manage_licenses_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-manage-licenses']);
        $law_cases_manage_licenses_other     = Permission::firstOrCreate(['name' => 'other-law-cases-manage-licenses']);
        $law_cases_manage_licenses_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-cases-manage-licenses']);
        $law_cases_manage_licenses_printing  = Permission::firstOrCreate(['name' => 'printing-law-cases-manage-licenses']);

        if (!$admin->hasPermission($law_cases_manage_licenses_view)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_view);
        }
        if (!$admin->hasPermission($law_cases_manage_licenses_add)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_add);
        }
        if (!$admin->hasPermission($law_cases_manage_licenses_edit)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_edit);
        }
        if (!$admin->hasPermission($law_cases_manage_licenses_other)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_other);
        }
        if (!$admin->hasPermission($law_cases_manage_licenses_view_all)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_view_all);
        }
        if (!$admin->hasPermission($law_cases_manage_licenses_printing)) {
            $admin_role->givePermissionTo($law_cases_manage_licenses_printing);
        }

        $law_trader_book_search_add    = Permission::firstOrCreate(['name' => 'add-law-trader-book-search']);
        $law_trader_book_search_view   = Permission::firstOrCreate(['name' => 'view-law-trader-book-search']);
        $law_trader_book_search_edit   = Permission::firstOrCreate(['name' => 'edit-law-trader-book-search']);
        $law_trader_book_search_delete = Permission::firstOrCreate(['name' => 'delete-law-trader-book-search']);

        if (!$admin->hasPermission($law_trader_book_search_add)) {
            $admin_role->givePermissionTo($law_trader_book_search_add);
        }
        if (!$admin->hasPermission($law_trader_book_search_view)) {
            $admin_role->givePermissionTo($law_trader_book_search_view);
        }
        if (!$admin->hasPermission($law_trader_book_search_edit)) {
            $admin_role->givePermissionTo($law_trader_book_search_edit);
        }
        if (!$admin->hasPermission($law_trader_book_search_delete)) {
            $admin_role->givePermissionTo($law_trader_book_search_delete);
        }

        $law_trader_case_offender_add       = Permission::firstOrCreate(['name' => 'add-law-trader-cases-offender']);
        $law_trader_case_offender_view      = Permission::firstOrCreate(['name' => 'view-law-trader-cases-offender']);
        $law_trader_case_offender_edit      = Permission::firstOrCreate(['name' => 'edit-law-trader-cases-offender']);
        $law_trader_case_offender_delete    = Permission::firstOrCreate(['name' => 'delete-law-trader-cases-offender']);
        
        if (!$admin->hasPermission($law_trader_case_offender_add)) {
            $admin_role->givePermissionTo($law_trader_case_offender_add);
        }
        if (!$admin->hasPermission($law_trader_case_offender_view)) {
            $admin_role->givePermissionTo($law_trader_case_offender_view);
        }
        if (!$admin->hasPermission($law_trader_case_offender_edit)) {
            $admin_role->givePermissionTo($law_trader_case_offender_edit);
        }
        if (!$admin->hasPermission($law_trader_case_offender_delete)) {
            $admin_role->givePermissionTo($law_trader_case_offender_delete);
        }

        $law_report_summary_law_cases_add    = Permission::firstOrCreate(['name' => 'add-law-report-summary-law-cases']);
        $law_report_summary_law_cases_view   = Permission::firstOrCreate(['name' => 'view-law-report-summary-law-cases']);
        $law_report_summary_law_cases_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-summary-law-cases']);
        $law_report_summary_law_cases_delete = Permission::firstOrCreate(['name' => 'delete-law-report-summary-law-cases']);
        $law_report_summary_law_cases_other = Permission::firstOrCreate(['name' => 'other-law-report-summary-law-cases']);
        $law_report_summary_law_cases_export = Permission::firstOrCreate(['name' => 'export-law-report-summary-law-cases']);

        if (!$admin->hasPermission($law_report_summary_law_cases_add)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_add);
        }
        if (!$admin->hasPermission($law_report_summary_law_cases_view)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_view);
        }
        if (!$admin->hasPermission($law_report_summary_law_cases_edit)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_edit);
        }
        if (!$admin->hasPermission($law_report_summary_law_cases_delete)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_delete);
        }
        if (!$admin->hasPermission($law_report_summary_law_cases_other)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_other);
        }
        if (!$admin->hasPermission($law_report_summary_law_cases_export)) {
            $admin_role->givePermissionTo($law_report_summary_law_cases_export);
        }

        //ติดตามงานคดี (law-cases-tracks)
        $law_cases_tracks_view      = Permission::firstOrCreate(['name' => 'view-law-cases-tracks']);
        $law_cases_tracks_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-tracks']);
        $law_cases_tracks_other     = Permission::firstOrCreate(['name' => 'other-law-cases-tracks']);
        $law_cases_tracks_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-cases-tracks']);
        $law_cases_tracks_printing  = Permission::firstOrCreate(['name' => 'printing-law-cases-tracks']);
        if (!$admin->hasPermission($law_cases_tracks_view)) {
            $admin_role->givePermissionTo($law_cases_tracks_view);
        }
        if (!$admin->hasPermission($law_cases_tracks_edit)) {
            $admin_role->givePermissionTo($law_cases_tracks_edit);
        }
        if (!$admin->hasPermission($law_cases_tracks_other)) {
            $admin_role->givePermissionTo($law_cases_tracks_other);
        }
        if (!$admin->hasPermission($law_cases_tracks_view_all)) {
            $admin_role->givePermissionTo($law_cases_tracks_view_all);
        }
        if (!$admin->hasPermission($law_cases_tracks_printing)) {
            $admin_role->givePermissionTo($law_cases_tracks_printing);
        }

        //เปรียบเทียบปรับ (law-cases-compares)
        $law_cases_compares_add       = Permission::firstOrCreate(['name' => 'add-law-cases-compares']);
        $law_cases_compares_view      = Permission::firstOrCreate(['name' => 'view-law-cases-compares']);
        $law_cases_compares_edit      = Permission::firstOrCreate(['name' => 'edit-law-cases-compares']);
        $law_cases_compares_other     = Permission::firstOrCreate(['name' => 'other-law-cases-compares']);
        $law_cases_compares_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-cases-compares']);
        $law_cases_compares_printing  = Permission::firstOrCreate(['name' => 'printing-law-cases-compares']);
        Permission::where('name', 'delete-law-cases-compares')->delete();

        if (!$admin->hasPermission($law_cases_compares_add)) {
            $admin_role->givePermissionTo($law_cases_compares_add);
        }
        if (!$admin->hasPermission($law_cases_compares_view)) {
            $admin_role->givePermissionTo($law_cases_compares_view);
        }
        if (!$admin->hasPermission($law_cases_compares_edit)) {
            $admin_role->givePermissionTo($law_cases_compares_edit);
        }
        if (!$admin->hasPermission($law_cases_compares_other)) {
            $admin_role->givePermissionTo($law_cases_compares_other);
        }
        if (!$admin->hasPermission($law_cases_compares_view_all)) {
            $admin_role->givePermissionTo($law_cases_compares_view_all);
        }
        if (!$admin->hasPermission($law_cases_compares_printing)) {
            $admin_role->givePermissionTo($law_cases_compares_printing);
        }
        
        //ใบแจ้งชำระเงิน (Payin) (law-cases-payin)
        $law_cases_payin_view                = Permission::firstOrCreate(['name' => 'view-law-cases-payin']);
        $law_cases_payin_edit                = Permission::firstOrCreate(['name' => 'edit-law-cases-payin']);
        $law_cases_payin_delete              = Permission::firstOrCreate(['name' => 'delete-law-cases-payin']);
        $law_cases_payin_other               = Permission::firstOrCreate(['name' => 'other-law-cases-payin']);
        $law_cases_payin_view_all            = Permission::firstOrCreate(['name' => 'view_all-law-cases-payin']);
        $law_cases_payin_printing            = Permission::firstOrCreate(['name' => 'printing-law-cases-payin']);

        if (!$admin->hasPermission($law_cases_payin_view)) {
            $admin_role->givePermissionTo($law_cases_payin_view);
        }
        if (!$admin->hasPermission($law_cases_payin_edit)) {
            $admin_role->givePermissionTo($law_cases_payin_edit);
        }
        if (!$admin->hasPermission($law_cases_payin_delete)) {
            $admin_role->givePermissionTo($law_cases_payin_delete);
        }
        if (!$admin->hasPermission($law_cases_payin_other)) {
            $admin_role->givePermissionTo($law_cases_payin_other);
        }
        if (!$admin->hasPermission($law_cases_payin_view_all)) {
            $admin_role->givePermissionTo($law_cases_payin_view_all);
        }
        if (!$admin->hasPermission($law_cases_payin_printing)) {
            $admin_role->givePermissionTo($law_cases_payin_printing);
        }

        //ตรวจสอบการชำระ (law-cases-payment)
        $law_cases_payment_add                = Permission::firstOrCreate(['name' => 'add-law-cases-payment']);
        $law_cases_payment_view                = Permission::firstOrCreate(['name' => 'view-law-cases-payment']);
        $law_cases_payment_edit                = Permission::firstOrCreate(['name' => 'edit-law-cases-payment']);
        $law_cases_payment_other               = Permission::firstOrCreate(['name' => 'other-law-cases-payment']);
        $law_cases_payment_view_all            = Permission::firstOrCreate(['name' => 'view_all-law-cases-payment']);
        $law_cases_payment_printing            = Permission::firstOrCreate(['name' => 'printing-law-cases-payment']);
        if (!$admin->hasPermission($law_cases_payment_add)) {
            $admin_role->givePermissionTo($law_cases_payment_add);
        }
        if (!$admin->hasPermission($law_cases_payment_view)) {
            $admin_role->givePermissionTo($law_cases_payment_view);
        }
        if (!$admin->hasPermission($law_cases_payment_edit)) {
            $admin_role->givePermissionTo($law_cases_payment_edit);
        }
        if (!$admin->hasPermission($law_cases_payment_other)) {
            $admin_role->givePermissionTo($law_cases_payment_other);
        }
        if (!$admin->hasPermission($law_cases_payment_view_all)) {
            $admin_role->givePermissionTo($law_cases_payment_view_all);
        }
        if (!$admin->hasPermission($law_cases_payment_printing)) {
            $admin_role->givePermissionTo($law_cases_payment_printing);
        }
 
        $law_report_book_stakeholder_add    = Permission::firstOrCreate(['name' => 'add-law-report-department-stakeholder']);
        $law_report_book_stakeholder_view   = Permission::firstOrCreate(['name' => 'view-law-report-department-stakeholder']);
        $law_report_book_stakeholder_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-department-stakeholder']);
        $law_report_book_stakeholder_delete = Permission::firstOrCreate(['name' => 'delete-law-report-department-stakeholder']);
        $law_report_book_stakeholder_other = Permission::firstOrCreate(['name' => 'other-law-report-department-stakeholder']);
        $law_report_book_stakeholder_export = Permission::firstOrCreate(['name' => 'export-law-report-department-stakeholder']);

        if (!$admin->hasPermission($law_report_book_stakeholder_add)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_add);
        }
        if (!$admin->hasPermission($law_report_book_stakeholder_view)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_view);
        }
        if (!$admin->hasPermission($law_report_book_stakeholder_edit)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_edit);
        }
        if (!$admin->hasPermission($law_report_book_stakeholder_delete)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_delete);
        }
        if (!$admin->hasPermission($law_report_book_stakeholder_other)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_other);
        }
        if (!$admin->hasPermission($law_report_book_stakeholder_export)) {
            $admin_role->givePermissionTo($law_report_book_stakeholder_export);
        }

        // ประเภทการแบ่งเงิน
        $law_reward_divsion_type_add                 = Permission::firstOrCreate(['name' => 'add-law-reward-divsion-type']);
        $law_reward_divsion_type_view                = Permission::firstOrCreate(['name' => 'view-law-reward-divsion-type']);
        $law_reward_divsion_type_edit                = Permission::firstOrCreate(['name' => 'edit-law-reward-divsion-type']);
        $law_reward_divsion_type_delete              = Permission::firstOrCreate(['name' => 'delete-law-reward-divsion-type']);
        if (!$admin->hasPermission($law_reward_divsion_type_add)) {
            $admin_role->givePermissionTo($law_reward_divsion_type_add);
        }
        if (!$admin->hasPermission($law_reward_divsion_type_view)) {
            $admin_role->givePermissionTo($law_reward_divsion_type_view);
        }
        if (!$admin->hasPermission($law_reward_divsion_type_edit)) {
            $admin_role->givePermissionTo($law_reward_divsion_type_edit);
        }
        if (!$admin->hasPermission($law_reward_divsion_type_delete)) {
            $admin_role->givePermissionTo($law_reward_divsion_type_delete);
        }

        // กำหนดเพดานเงินคำนวณ
        $law_reward_reward_max_add                 = Permission::firstOrCreate(['name' => 'add-law-reward-reward-max']);
        $law_reward_reward_max_view                = Permission::firstOrCreate(['name' => 'view-law-reward-reward-max']);
        $law_reward_reward_max_edit                = Permission::firstOrCreate(['name' => 'edit-law-reward-reward-max']);
        $law_reward_reward_max_delete              = Permission::firstOrCreate(['name' => 'delete-law-reward-reward-max']);
        if (!$admin->hasPermission($law_reward_reward_max_add)) {
            $admin_role->givePermissionTo($law_reward_reward_max_add);
        }
        if (!$admin->hasPermission($law_reward_reward_max_view)) {
            $admin_role->givePermissionTo($law_reward_reward_max_view);
        }
        if (!$admin->hasPermission($law_reward_reward_max_edit)) {
            $admin_role->givePermissionTo($law_reward_reward_max_edit);
        }
        if (!$admin->hasPermission($law_reward_reward_max_delete)) {
            $admin_role->givePermissionTo($law_reward_reward_max_delete);
        }

        // สัดส่วนผู้มีสิทธิได้รับเงิน
        $law_reward_reward_add                 = Permission::firstOrCreate(['name' => 'add-law-reward-reward']);
        $law_reward_reward_view                = Permission::firstOrCreate(['name' => 'view-law-reward-reward']);
        $law_reward_reward_edit                = Permission::firstOrCreate(['name' => 'edit-law-reward-reward']);
        $law_reward_reward_delete              = Permission::firstOrCreate(['name' => 'delete-law-reward-reward']);
        if (!$admin->hasPermission($law_reward_reward_add)) {
            $admin_role->givePermissionTo($law_reward_reward_add);
        }
        if (!$admin->hasPermission($law_reward_reward_view)) {
            $admin_role->givePermissionTo($law_reward_reward_view);
        }
        if (!$admin->hasPermission($law_reward_reward_edit)) {
            $admin_role->givePermissionTo($law_reward_reward_edit);
        }
        if (!$admin->hasPermission($law_reward_reward_delete)) {
            $admin_role->givePermissionTo($law_reward_reward_delete);
        }

        // คำนวณสินบน
        $law_reward_calculations_add                 = Permission::firstOrCreate(['name' => 'add-law-reward-calculations']);
        $law_reward_calculations_view                = Permission::firstOrCreate(['name' => 'view-law-reward-calculations']);
        $law_reward_calculations_edit                = Permission::firstOrCreate(['name' => 'edit-law-reward-calculations']);
        $law_reward_calculations_view_all            = Permission::firstOrCreate(['name' => 'view_all-law-reward-calculations']);
        $law_reward_calculations_printing            = Permission::firstOrCreate(['name' => 'printing-law-reward-calculations']);

        if (!$admin->hasPermission($law_reward_calculations_add)) {
            $admin_role->givePermissionTo($law_reward_calculations_add);
        }
        if (!$admin->hasPermission($law_reward_calculations_view)) {
            $admin_role->givePermissionTo($law_reward_calculations_view);
        }
        if (!$admin->hasPermission($law_reward_calculations_edit)) {
            $admin_role->givePermissionTo($law_reward_calculations_edit);
        }
        if (!$admin->hasPermission($law_reward_calculations_view_all)) {
            $admin_role->givePermissionTo($law_reward_calculations_view_all);
        }
        if (!$admin->hasPermission($law_reward_calculations_printing)) {
            $admin_role->givePermissionTo($law_reward_calculations_printing);
        }
 
        $basic_banks_add    = Permission::firstOrCreate(['name' => 'add-accounting-basic-banks']);
        $basic_banks_view   = Permission::firstOrCreate(['name' => 'view-accounting-basic-banks']);
        $basic_banks_edit   = Permission::firstOrCreate(['name' => 'edit-accounting-basic-banks']);
        $basic_banks_delete = Permission::firstOrCreate(['name' => 'delete-accounting-basic-banks']);

        if(!$admin->hasPermission($basic_banks_add)){
          $admin_role->givePermissionTo($basic_banks_add);
        }

        if(!$admin->hasPermission($basic_banks_view)){
            $admin_role->givePermissionTo($basic_banks_view);
        }

        if(!$admin->hasPermission($basic_banks_edit)){
            $admin_role->givePermissionTo($basic_banks_edit);
        }

        if(!$admin->hasPermission($basic_banks_delete)){
            $admin_role->givePermissionTo($basic_banks_delete);
        }

        //ข้อมูลผู้รับใบเสร็จเงิน
        $accounting_receipt_info_add    = Permission::firstOrCreate(['name' => 'add-accounting-receipt-info']);
        $accounting_receipt_info_view   = Permission::firstOrCreate(['name' => 'view-accounting-receipt-info']);
        $accounting_receipt_info_edit   = Permission::firstOrCreate(['name' => 'edit-accounting-receipt-info']);
        $accounting_receipt_info_delete = Permission::firstOrCreate(['name' => 'delete-accounting-receipt-info']);

        if(!$admin->hasPermission($accounting_receipt_info_add)){
          $admin_role->givePermissionTo($accounting_receipt_info_add);
        }

        if(!$admin->hasPermission($accounting_receipt_info_view)){
            $admin_role->givePermissionTo($accounting_receipt_info_view);
        }

        if(!$admin->hasPermission($accounting_receipt_info_edit)){
            $admin_role->givePermissionTo($accounting_receipt_info_edit);
        }

        if(!$admin->hasPermission($accounting_receipt_info_delete)){
            $admin_role->givePermissionTo($accounting_receipt_info_delete);
        }

        $law_reward_receipts_add    = Permission::firstOrCreate(['name' => 'add-law-reward-receipts']);
        $law_reward_receipts_view   = Permission::firstOrCreate(['name' => 'view-law-reward-receipts']);
        $law_reward_receipts_edit   = Permission::firstOrCreate(['name' => 'edit-law-reward-receipts']);
        $law_reward_receipts_delete = Permission::firstOrCreate(['name' => 'delete-law-reward-receipts']);
        $law_reward_receipts_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-reward-receipts']);

        if(!$admin->hasPermission($law_reward_receipts_add)){
          $admin_role->givePermissionTo($law_reward_receipts_add);
        }
        
        if(!$admin->hasPermission($law_reward_receipts_view)){
            $admin_role->givePermissionTo($law_reward_receipts_view);
        }
        
        if(!$admin->hasPermission($law_reward_receipts_edit)){
            $admin_role->givePermissionTo($law_reward_receipts_edit);
        }
        
        if(!$admin->hasPermission($law_reward_receipts_delete)){
            $admin_role->givePermissionTo($law_reward_receipts_delete);
        }

        if (!$admin->hasPermission($law_reward_receipts_view_all)) {
            $admin_role->givePermissionTo($law_reward_receipts_view_all);
        }

        $law_reward_withdraws_add    = Permission::firstOrCreate(['name' => 'add-law-reward-withdraws']);
        $law_reward_withdraws_view   = Permission::firstOrCreate(['name' => 'view-law-reward-withdraws']);
        $law_reward_withdraws_edit   = Permission::firstOrCreate(['name' => 'edit-law-reward-withdraws']);
        $law_reward_withdraws_delete = Permission::firstOrCreate(['name' => 'delete-law-reward-withdraws']);
        $law_reward_withdraws_printing  = Permission::firstOrCreate(['name' => 'printing-law-reward-withdraws']);
        $law_reward_withdraws_view_all  = Permission::firstOrCreate(['name' => 'view_all-law-reward-withdraws']);

        if(!$admin->hasPermission($law_reward_withdraws_add)){
          $admin_role->givePermissionTo($law_reward_withdraws_add);
        }
        
        if(!$admin->hasPermission($law_reward_withdraws_view)){
            $admin_role->givePermissionTo($law_reward_withdraws_view);
        }
        
        if(!$admin->hasPermission($law_reward_withdraws_edit)){
            $admin_role->givePermissionTo($law_reward_withdraws_edit);
        }
        
        if(!$admin->hasPermission($law_reward_withdraws_delete)){
            $admin_role->givePermissionTo($law_reward_withdraws_delete);
        }
        if (!$admin->hasPermission($law_reward_withdraws_printing)) {
            $admin_role->givePermissionTo($law_reward_withdraws_printing);
        }
        if (!$admin->hasPermission($law_reward_withdraws_view_all)) {
            $admin_role->givePermissionTo($law_reward_withdraws_view_all);
        }

        $law_report_rewards_add    = Permission::firstOrCreate(['name' => 'add-law-report-rewards']);
        $law_report_rewards_view   = Permission::firstOrCreate(['name' => 'view-law-report-rewards']);
        $law_report_rewards_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-rewards']);
        $law_report_rewards_delete = Permission::firstOrCreate(['name' => 'delete-law-report-rewards']);
        $law_report_rewards_other = Permission::firstOrCreate(['name' => 'other-law-report-rewards']);
        $law_report_rewards_export = Permission::firstOrCreate(['name' => 'export-law-report-rewards']);
        
        if(!$admin->hasPermission($law_report_rewards_add)){
          $admin_role->givePermissionTo($law_report_rewards_add);
        }
        
        if(!$admin->hasPermission($law_report_rewards_view)){
            $admin_role->givePermissionTo($law_report_rewards_view);
        }
        
        if(!$admin->hasPermission($law_report_rewards_edit)){
            $admin_role->givePermissionTo($law_report_rewards_edit);
        }
        
        if(!$admin->hasPermission($law_report_rewards_delete)){
            $admin_role->givePermissionTo($law_report_rewards_delete);
        }
        if(!$admin->hasPermission($law_report_rewards_other)){
            $admin_role->givePermissionTo($law_report_rewards_other);
        }
        if(!$admin->hasPermission($law_report_rewards_export)){
            $admin_role->givePermissionTo($law_report_rewards_export);
        }

        $role_setting_group_add    = Permission::firstOrCreate(['name' => 'add-role-setting-group']);
        $role_setting_group_view   = Permission::firstOrCreate(['name' => 'view-role-setting-group']);
        $role_setting_group_edit   = Permission::firstOrCreate(['name' => 'edit-role-setting-group']);
        $role_setting_group_delete = Permission::firstOrCreate(['name' => 'delete-role-setting-group']);

        if (!$admin->hasPermission($role_setting_group_add)) {
            $admin_role->givePermissionTo($role_setting_group_add);
        }
        if (!$admin->hasPermission($role_setting_group_view)) {
            $admin_role->givePermissionTo($role_setting_group_view);
        }
        if (!$admin->hasPermission($role_setting_group_edit)) {
            $admin_role->givePermissionTo($role_setting_group_edit);
        }
        if (!$admin->hasPermission($role_setting_group_delete)) {
            $admin_role->givePermissionTo($role_setting_group_delete);
        }

        $cerreport_logesignaures_view   = Permission::firstOrCreate(['name' => 'view-cerreport-logesignaures']);
        $cerreport_logesignaures_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-logesignaures']);
        if (!$admin->hasPermission($cerreport_logesignaures_view)) {
            $admin_role->givePermissionTo($cerreport_logesignaures_view);
        }
        if (!$admin->hasPermission($cerreport_logesignaures_edit)) {
            $admin_role->givePermissionTo($cerreport_logesignaures_edit);
        }

        $role_report_std_certifies_view   = Permission::firstOrCreate(['name' => 'view-report-std-certifies']);

        if (!$admin->hasPermission($role_report_std_certifies_view)) {
            $admin_role->givePermissionTo($role_report_std_certifies_view);
        }

        $basic_holiday_add    = Permission::firstOrCreate(['name' => 'add-basic-holiday']);
        $basic_holiday_view   = Permission::firstOrCreate(['name' => 'view-basic-holiday']);
        $basic_holiday_edit   = Permission::firstOrCreate(['name' => 'edit-basic-holiday']);
        $basic_holiday_delete = Permission::firstOrCreate(['name' => 'delete-basic-holiday']);

        if (!$admin->hasPermission($basic_holiday_add)) {
            $admin_role->givePermissionTo($basic_holiday_add);
        }
        if (!$admin->hasPermission($basic_holiday_view)) {
            $admin_role->givePermissionTo($basic_holiday_view);
        }
        if (!$admin->hasPermission($basic_holiday_edit)) {
            $admin_role->givePermissionTo($basic_holiday_edit);
        }
        if (!$admin->hasPermission($basic_holiday_delete)) {
            $admin_role->givePermissionTo($basic_holiday_delete);
        }

        $configs_import_holiday_add    = Permission::firstOrCreate(['name' => 'add-configs-import-holiday']);
        $configs_import_holiday_view   = Permission::firstOrCreate(['name' => 'view-configs-import-holiday']);
        $configs_import_holiday_edit   = Permission::firstOrCreate(['name' => 'edit-configs-import-holiday']);
        $configs_import_holiday_delete = Permission::firstOrCreate(['name' => 'delete-configs-import-holiday']);

        if (!$admin->hasPermission($configs_import_holiday_add)) {
            $admin_role->givePermissionTo($configs_import_holiday_add);
        }
        if (!$admin->hasPermission($configs_import_holiday_view)) {
            $admin_role->givePermissionTo($configs_import_holiday_view);
        }
        if (!$admin->hasPermission($configs_import_holiday_edit)) {
            $admin_role->givePermissionTo($configs_import_holiday_edit);
        }
        if (!$admin->hasPermission($configs_import_holiday_delete)) {
            $admin_role->givePermissionTo($configs_import_holiday_delete);
        }

        $mail_test_view = Permission::firstOrCreate(['name' => 'view-mail-test']);
        if (!$admin->hasPermission($mail_test_view)) {
            $admin_role->givePermissionTo($mail_test_view);
        }
             
        $law_report_rewards_persons_view   = Permission::firstOrCreate(['name' => 'view-law-report-rewards-persons']);
        $law_report_rewards_persons_export = Permission::firstOrCreate(['name' => 'export-law-report-rewards-persons']);

        if(!$admin->hasPermission($law_report_rewards_persons_view)){
            $admin_role->givePermissionTo($law_report_rewards_persons_view);
        }
        if(!$admin->hasPermission($law_report_rewards_persons_export)){
            $admin_role->givePermissionTo($law_report_rewards_persons_export);
        }
                     
        $law_report_payments_view   = Permission::firstOrCreate(['name' => 'view-law-report-payments']);
        $law_report_payments_export = Permission::firstOrCreate(['name' => 'export-law-report-payments']);

        if(!$admin->hasPermission($law_report_payments_view)){
            $admin_role->givePermissionTo($law_report_payments_view);
        }
        if(!$admin->hasPermission($law_report_payments_export)){
            $admin_role->givePermissionTo($law_report_payments_export);
        }
        
        $law_offend_type_add    = Permission::firstOrCreate(['name' => 'add-law-offend-type']);
        $law_offend_type_view   = Permission::firstOrCreate(['name' => 'view-law-offend-type']);
        $law_offend_type_edit   = Permission::firstOrCreate(['name' => 'edit-law-offend-type']);
        $law_offend_type_delete = Permission::firstOrCreate(['name' => 'delete-law-offend-type']);

        if (!$admin->hasPermission($law_offend_type_add)) {
            $admin_role->givePermissionTo($law_offend_type_add);
        }
        if (!$admin->hasPermission($law_offend_type_view)) {
            $admin_role->givePermissionTo($law_offend_type_view);
        }
        if (!$admin->hasPermission($law_offend_type_edit)) {
            $admin_role->givePermissionTo($law_offend_type_edit);
        }
        if (!$admin->hasPermission($law_offend_type_delete)) {
            $admin_role->givePermissionTo($law_offend_type_delete);
        }

        $law_report_summary_law_offender_cases_add    = Permission::firstOrCreate(['name' => 'add-law-report-summary-law-offender-cases']);
        $law_report_summary_law_offender_cases_view   = Permission::firstOrCreate(['name' => 'view-law-report-summary-law-offender-cases']);
        $law_report_summary_law_offender_cases_edit   = Permission::firstOrCreate(['name' => 'edit-law-report-summary-law-offender-cases']);
        $law_report_summary_law_offender_cases_delete = Permission::firstOrCreate(['name' => 'delete-law-report-summary-law-offender-cases']);
        $law_report_summary_law_offender_cases_other  = Permission::firstOrCreate(['name' => 'other-law-report-summary-law-offender-cases']);
        $law_report_summary_law_offender_cases_export = Permission::firstOrCreate(['name' => 'export-law-report-summary-law-offender-cases']);

        if (!$admin->hasPermission($law_report_summary_law_offender_cases_add)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_add);
        }
        if (!$admin->hasPermission($law_report_summary_law_offender_cases_view)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_view);
        }
        if (!$admin->hasPermission($law_report_summary_law_offender_cases_edit)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_edit);
        }
        if (!$admin->hasPermission($law_report_summary_law_offender_cases_delete)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_delete);
        }
        if (!$admin->hasPermission($law_report_summary_law_offender_cases_other)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_other);
        }
        if (!$admin->hasPermission($law_report_summary_law_offender_cases_export)) {
            $admin_role->givePermissionTo($law_report_summary_law_offender_cases_export);
        }

        $report_standard_branch_add    = Permission::firstOrCreate(['name' => 'add-report-standard-branch']);
        $report_standard_branch_view   = Permission::firstOrCreate(['name' => 'view-report-standard-branch']);
        $report_standard_branch_edit   = Permission::firstOrCreate(['name' => 'edit-report-standard-branch']);
        $report_standard_branch_delete = Permission::firstOrCreate(['name' => 'delete-report-standard-branch']);

        if (!$admin->hasPermission($report_standard_branch_add)) {
            $admin_role->givePermissionTo($report_standard_branch_add);
        }
        if (!$admin->hasPermission($report_standard_branch_view)) {
            $admin_role->givePermissionTo($report_standard_branch_view);
        }
        if (!$admin->hasPermission($report_standard_branch_edit)) {
            $admin_role->givePermissionTo($report_standard_branch_edit);
        }
        if (!$admin->hasPermission($report_standard_branch_delete)) {
            $admin_role->givePermissionTo($report_standard_branch_delete);
        }


        $report_elicense_roles_add    = Permission::firstOrCreate(['name' => 'add-report-elicense-roles']);
        $report_elicense_roles_view   = Permission::firstOrCreate(['name' => 'view-report-elicense-roles']);
        $report_elicense_roles_edit   = Permission::firstOrCreate(['name' => 'edit-report-elicense-roles']);
        $report_elicense_roles_delete = Permission::firstOrCreate(['name' => 'delete-report-elicense-roles']);

        if (!$admin->hasPermission($report_elicense_roles_add)) {
            $admin_role->givePermissionTo($report_elicense_roles_add);
        }
        if (!$admin->hasPermission($report_elicense_roles_view)) {
            $admin_role->givePermissionTo($report_elicense_roles_view);
        }
        if (!$admin->hasPermission($report_elicense_roles_edit)) {
            $admin_role->givePermissionTo($report_elicense_roles_edit);
        }
        if (!$admin->hasPermission($report_elicense_roles_delete)) {
            $admin_role->givePermissionTo($report_elicense_roles_delete);
        }

        $bcertify_setting_config_add    = Permission::firstOrCreate(['name' => 'add-bcertify-setting-config']);
        $bcertify_setting_config_view   = Permission::firstOrCreate(['name' => 'view-bcertify-setting-config']);
        $bcertify_setting_config_edit   = Permission::firstOrCreate(['name' => 'edit-bcertify-setting-config']);
        $bcertify_setting_config_delete = Permission::firstOrCreate(['name' => 'delete-bcertify-setting-config']);

        if (!$admin->hasPermission($bcertify_setting_config_add)) {
            $admin_role->givePermissionTo($bcertify_setting_config_add);
        }
        if (!$admin->hasPermission($bcertify_setting_config_view)) {
            $admin_role->givePermissionTo($bcertify_setting_config_view);
        }
        if (!$admin->hasPermission($bcertify_setting_config_edit)) {
            $admin_role->givePermissionTo($bcertify_setting_config_edit);
        }
        if (!$admin->hasPermission($bcertify_setting_config_delete)) {
            $admin_role->givePermissionTo($bcertify_setting_config_delete);
        }



 
        $law_cases_forms_approved_view        = Permission::firstOrCreate(['name' => 'view-law-cases-forms-approved']);
        $law_cases_forms_approved_edit        = Permission::firstOrCreate(['name' => 'edit-law-cases-forms-approved']);
        $law_cases_forms_approved_view_all    = Permission::firstOrCreate(['name' => 'view_all-law-cases-forms-approved']);
        if (!$admin->hasPermission($law_cases_forms_approved_view)) {
            $admin_role->givePermissionTo($law_cases_forms_approved_view);
        }
        if (!$admin->hasPermission($law_cases_forms_approved_edit)) {
            $admin_role->givePermissionTo($law_cases_forms_approved_edit);
        }
        if (!$admin->hasPermission($law_cases_forms_approved_view_all)) {
            $admin_role->givePermissionTo($law_cases_forms_approved_view_all);
          }

        $ws_moi_log_view = Permission::firstOrCreate(['name' => 'view-ws-moi-log']);
        if (!$admin->hasPermission($ws_moi_log_view)) {
            $admin_role->givePermissionTo($ws_moi_log_view);
        }
        
        $cerreport_system_certification_add    = Permission::firstOrCreate(['name' => 'add-cerreport-system-certification']);
        $cerreport_system_certification_view   = Permission::firstOrCreate(['name' => 'view-cerreport-system-certification']);
        $cerreport_system_certification_edit   = Permission::firstOrCreate(['name' => 'edit-cerreport-system-certification']);
        $cerreport_system_certification_delete = Permission::firstOrCreate(['name' => 'delete-cerreport-system-certification']);

        if (!$admin->hasPermission($cerreport_system_certification_add)) {
            $admin_role->givePermissionTo($cerreport_system_certification_add);
        }
        if (!$admin->hasPermission($cerreport_system_certification_view)) {
            $admin_role->givePermissionTo($cerreport_system_certification_view);
        }
        if (!$admin->hasPermission($cerreport_system_certification_edit)) {
            $admin_role->givePermissionTo($cerreport_system_certification_edit);
        }
        if (!$admin->hasPermission($cerreport_system_certification_delete)) {
            $admin_role->givePermissionTo($cerreport_system_certification_delete);
        }

        $certify_authorities_lt_add    = Permission::firstOrCreate(['name' => 'add-certify-authorities-lt']);
        $certify_authorities_lt_view   = Permission::firstOrCreate(['name' => 'view-certify-authorities-lt']);
        
        if (!$admin->hasPermission($certify_authorities_lt_add)) {
            $admin_role->givePermissionTo($certify_authorities_lt_add);
        }
        if (!$admin->hasPermission($certify_authorities_lt_view)) {
            $admin_role->givePermissionTo($certify_authorities_lt_view);
        }

        $report_labs_add    = Permission::firstOrCreate(['name' => 'add-report-labs']);
        $report_labs_view   = Permission::firstOrCreate(['name' => 'view-report-labs']);
        $report_labs_edit   = Permission::firstOrCreate(['name' => 'edit-report-labs']);
        $report_labs_delete = Permission::firstOrCreate(['name' => 'delete-report-labs']);
        $report_labs_other  = Permission::firstOrCreate(['name' => 'other-report-labs']);
        $report_labs_export = Permission::firstOrCreate(['name' => 'export-report-labs']);
        $report_labs_view_all = Permission::firstOrCreate(['name' => 'view_all-report-labs']);


        if (!$admin->hasPermission($report_labs_add)) {
            $admin_role->givePermissionTo($report_labs_add);
        }
        if (!$admin->hasPermission($report_labs_view)) {
            $admin_role->givePermissionTo($report_labs_view);
        }
        if (!$admin->hasPermission($report_labs_edit)) {
            $admin_role->givePermissionTo($report_labs_edit);
        }
        if (!$admin->hasPermission($report_labs_delete)) {
            $admin_role->givePermissionTo($report_labs_delete);
        }
        if (!$admin->hasPermission($report_labs_other)) {
            $admin_role->givePermissionTo($report_labs_other);
        }
        if (!$admin->hasPermission($report_labs_export)) {
            $admin_role->givePermissionTo($report_labs_export);
        }
        if (!$admin->hasPermission($report_labs_view_all)) {
            $admin_role->givePermissionTo($report_labs_view_all);
        }


        $law_report_listen_ministry_mail_view   = Permission::firstOrCreate(['name' => 'view-law-report-listen-ministry-mail']);
        $law_report_listen_ministry_mail = Permission::firstOrCreate(['name' => 'export-law-report-listen-ministry-mail']);

        if(!$admin->hasPermission($law_report_listen_ministry_mail_view)){
            $admin_role->givePermissionTo($law_report_listen_ministry_mail_view);
        }
        if(!$admin->hasPermission($law_report_listen_ministry_mail)){
            $admin_role->givePermissionTo($law_report_listen_ministry_mail);
        }


        $this->command->info('Admin User created with username admin@admin.com and password 1234');
    }

    
}
