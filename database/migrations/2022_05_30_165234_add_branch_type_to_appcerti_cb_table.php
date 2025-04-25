<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchTypeToAppcertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->enum('branch_type', ['1', '2'])->nullable()->comment('ประเภทสาขา 1.สำนักงานใหญ่, 2.สาขา')->after('name_standard');
            $table->string('applicanttype_id')->nullable()->comment('ประเภทผู้สมัคร')->after('app_no');
            $table->string('branch')->nullable()->comment('สาขาที่ระบุ')->after('branch_type');
            $table->string('app_certi_cb_export_id')->nullable()->comment('id ตาราง app_certi_cb_export .')->after('standard_change');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropColumn(['branch_type','applicanttype_id','app_certi_cb_export_id','branch']);
        });
    }
}
