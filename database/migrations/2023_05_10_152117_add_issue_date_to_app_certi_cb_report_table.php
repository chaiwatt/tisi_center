<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssueDateToAppCertiCbReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_report', function (Blueprint $table) {
            $table->date('issue_date')->nullable()->comment('ออกให้ ณ วันที่')->after('end_date');
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
            $table->dropColumn(['issue_date']);
        });
    }
}
