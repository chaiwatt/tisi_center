<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchTypeToAppCertiLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->enum('branch_type', ['1', '2'])->nullable()->comment('ประเภทสาขา 1.สำนักงานใหญ่, 2.สาขา')->after('branch_name');
            $table->string('applicanttype_id')->nullable()->comment('ประเภทผู้สมัคร')->after('app_no');
            $table->string('branch')->nullable()->comment('สาขาที่ระบุ')->after('branch_type');
            $table->string('certificate_exports_id')->nullable()->comment('id ตาราง certificate_exports .')->after('lab_type');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->dropColumn(['branch_type','applicanttype_id','certificate_exports_id','branch']);
        });
    }
}
