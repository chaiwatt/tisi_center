<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloseToSection5IbcbsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs_scopes', function (Blueprint $table) {
            $table->dateTime('close_state_date')->nullable()->comment('วันที่ปิดการใช้โดยระบบ');
            $table->text('close_remarks')->nullable()->comment('หมายเหตุ:ปิดการใช้งาน');
            $table->bigInteger('close_by')->nullable()->comment('ผู้บันทึกปิดการใช้งาน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_ibcbs_scopes', function (Blueprint $table) {
            $table->dropColumn(['close_state_date', 'close_remarks', 'close_by']);  
        });
    }
}
