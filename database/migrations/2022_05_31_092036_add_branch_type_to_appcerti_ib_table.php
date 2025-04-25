<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchTypeToAppcertiIbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->string('type_standard')->nullable()->comment('ตามมาตรฐานเลข')->after('standard_change');
            $table->enum('branch_type', ['1', '2'])->nullable()->comment('ประเภทสาขา 1.สำนักงานใหญ่, 2.สาขา')->after('type_standard');
            $table->string('applicanttype_id')->nullable()->comment('ประเภทผู้สมัคร')->after('app_no');
            $table->string('branch')->nullable()->comment('สาขาที่ระบุ')->after('branch_type');
            $table->string('app_certi_ib_export_id')->nullable()->comment('id ตาราง app_certi_ib_export .')->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->dropColumn(['type_standard','branch_type','branch','app_certi_ib_export_id','applicanttype_id']);
        });
    }
}
