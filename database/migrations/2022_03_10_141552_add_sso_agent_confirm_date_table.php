<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoAgentConfirmDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->renameColumn('user_agent', 'agent_taxid');
            $table->dateTime('confirm_date')->nullable()->comment('วันที่ยืนยันสถานะ')->change();
            $table->dateTime('revoke_date')->nullable()->comment('วันที่บันทึกการการสิ้นสุดการมอบหมาย')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->renameColumn('agent_taxid', 'user_agent');
            $table->dateTime('confirm_date')->nullable()->comment('วันที่ยืนยันสถานะ')->change();
            $table->date('confirm_date')->nullable()->comment('วันที่ยืนยันสถานะ')->change();
            $table->date('revoke_date')->nullable()->comment('วันที่บันทึกการการสิ้นสุดการมอบหมาย')->change();
        });
    }
}
