<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCfCerToAppCertiCbReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_report', function (Blueprint $table) {
            $table->integer('cf_cer')->nullable()->comment('ยืนยันรับใบรับรองระบบงาน')->after('status_confirm');
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
            $table->dropColumn(['cf_cer']);
        });
    }
}
