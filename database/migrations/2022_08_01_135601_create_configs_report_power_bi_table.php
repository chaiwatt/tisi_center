<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsReportPowerBiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_report_power_bi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('ชื่อรายงาน');
            $table->unsignedInteger('group_id')->nullable()->comment('id ตาราง configs_report_power_bi_group');
            $table->string('url')->nullable()->comment('URL รายงาน Power BI');
            $table->string('icon')->nullable()->comment('icons');
			$table->string('color')->nullable()->comment('colors');
            $table->boolean('state')->nullable()->comment('สถานะ 1=เปิดใช้งาน, 0=ปิดใช้งาน');
            $table->integer('ordering')->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs_report_power_bi');
    }
}
