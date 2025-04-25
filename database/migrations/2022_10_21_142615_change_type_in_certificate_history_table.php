<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeInCertificateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_history', function (Blueprint $table) {
            $table->longText('details_table')->nullable()->comment('รายละเอียด (table)')->change();
            $table->longText('details_date')->nullable()->comment('วันที่ตรวจประเมิน //ระบบคณะผู้ตรวจประเมิน')->change();
            $table->longText('details_cost_confirm')->nullable()->comment('confirm ค่าใช้จ่าย')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_history', function (Blueprint $table) {
            $table->text('details_table')->nullable()->comment('รายละเอียด (table)')->change();
            $table->text('details_date')->nullable()->comment('วันที่ตรวจประเมิน //ระบบคณะผู้ตรวจประเมิน')->change();
            $table->text('details_cost_confirm')->nullable()->comment('confirm ค่าใช้จ่าย')->change();
        });
    }
}
