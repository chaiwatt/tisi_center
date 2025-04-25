<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsReportPowerBiVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_report_power_bi_visit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id')->comment('session id ของผู้ใช้งาน');
            $table->unsignedInteger('power_bi_id')->comment("id ตาราง configs_report_power_bi");
            $table->dateTime('visit_at')->nullable()->comment('เข้าดูเมื่อ');
            $table->foreign('power_bi_id')
                  ->references('id')
                  ->on('configs_report_power_bi')
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
        Schema::dropIfExists('configs_report_power_bi_visit');
    }
}
