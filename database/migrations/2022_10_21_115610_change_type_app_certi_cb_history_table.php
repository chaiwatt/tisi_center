<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeAppCertiCbHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_history', function (Blueprint $table) {
            $table->longText('details_one')->nullable()->comment('รายละเอียดตารางหลัก(จนท)')->change();
            $table->longText('details_two')->nullable()->comment('รายละเอียด(จนท)')->change();
            $table->longText('details_three')->nullable()->comment('รายละเอียด(จนท)')->change();
            $table->longText('details_four')->nullable()->comment('รายละเอียด(จนท)')->change();
            $table->longText('details_auditors_cancel')->nullable()->comment('ถ้าเป็นข้อมูล json ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน CB')->after('details_four');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_history', function (Blueprint $table) {
            $table->dropColumn('details_auditors_cancel');
        });
    }
}
