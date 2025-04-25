<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnuseConfigsReportPowerBiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_report_power_bi', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color']);
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
            $table->string('icon')->nullable()->comment('icons');
			$table->string('color')->nullable()->comment('colors');
        });
    }
}
