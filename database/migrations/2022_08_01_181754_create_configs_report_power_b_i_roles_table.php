<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsReportPowerBIRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_report_power_bi_role', function (Blueprint $table) {
            $table->unsignedInteger('power_bi_id')->comment("id ตาราง configs_report_power_bi");
            $table->unsignedInteger('role_id')->comment("id ตาราง roles");
            $table->foreign('power_bi_id')
                  ->references('id')
                  ->on('configs_report_power_bi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs_report_power_bi_role');
    }
}
