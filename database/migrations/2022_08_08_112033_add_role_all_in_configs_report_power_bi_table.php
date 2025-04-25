<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleAllInConfigsReportPowerBiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_report_power_bi', function (Blueprint $table) {
            $table->enum('role_all', ['0', '1'])->default('0')->comment('เข้าดูได้ทั้งหมดทุกกลุ่ม 0=ไม่ใช่(ดูเพิ่มเติมที่ตาราง configs_report_power_bi_role), 1=ใช่')->after('state');
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
            $table->dropColumn(['role_all']);
        });
    }
}
