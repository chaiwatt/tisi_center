<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndDateToAppCertiCbReportAndAppCertiIbReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_report', function (Blueprint $table) {
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดคำขอรับใบรับรอง')->after('start_date');
        });
        Schema::table('app_certi_ib_report', function (Blueprint $table) {
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดคำขอรับใบรับรอง')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_report', function (Blueprint $table) {
            $table->dropColumn(['end_date']);
        });
        Schema::table('app_certi_ib_report', function (Blueprint $table) {
            $table->dropColumn(['end_date']);
        });
    }
}
