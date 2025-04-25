<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSection5ApplicationLabsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_report', function (Blueprint $table) {
            $table->tinyInteger('report_approve')->nullable()->comment('สถานะการพิจารณาสรุปรายงาน');
            $table->text('report_approve_description')->nullable()->comment('รายละเอียดการพิจารณาสรุปรายงาน');
            $table->integer('report_approve_by')->nullable()->comment('อนุมัติโดย');
            $table->integer('report_updated_by')->nullable()->comment('แก้ไขการอนุมัติโดย');
            $table->timestamp('report_approve_at')->nullable()->comment('วันที่อนุมัติ');
            $table->timestamp('report_updated_at')->nullable()->comment('วันที่แก้ไขการอนุมัติ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_report', function (Blueprint $table) {
            $table->dropColumn(['report_approve', 'report_approve_description', 'report_approve_by', 'report_updated_by', 'report_approve_at', 'report_updated_at']);
        });
    }
}
