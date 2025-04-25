<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkToConfigsReportPowerBiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_report_power_bi', function (Blueprint $table) {
            $table->foreign('group_id')
                  ->references('id')
                  ->on('configs_report_power_bi_group')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
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
            $table->dropForeign(['group_id']);
        });
    }
}
