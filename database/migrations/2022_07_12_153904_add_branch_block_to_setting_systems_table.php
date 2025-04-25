<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchBlockToSettingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->integer('branch_block')->default(0)->comment('ไม่ให้สาขาใช้งาน 1=ใช่ (ไม่ให้ใช้งาน), 0=ไม่ (ให้ใช้งาน)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->dropColumn(['branch_block']);
        });
    }
}
