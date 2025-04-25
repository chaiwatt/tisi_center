<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingReportStartDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_report', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('วันที่เริ่มขอบข่าย')->after('details');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดขอบข่าย')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_report', function (Blueprint $table) {
            $table->dropColumn(['start_date','end_date']);
        });
    }
}
