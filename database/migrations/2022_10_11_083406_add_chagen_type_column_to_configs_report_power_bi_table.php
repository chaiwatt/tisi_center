<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChagenTypeColumnToConfigsReportPowerBiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_report_power_bi', function (Blueprint $table) {
            $table->text('url')->nullable()->comment('URL รายงาน Power BI')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs_report_power_bi', function (Blueprint $table) {
            $table->string('url', 255)->nullable()->comment('URL รายงาน Power BI')->change();
        });
    }
}
