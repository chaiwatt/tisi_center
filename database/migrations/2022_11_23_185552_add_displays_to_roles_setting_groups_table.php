<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplaysToRolesSettingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_setting_groups', function (Blueprint $table) {
            $table->integer('displays')->default(0)->comment('สถานะ 1 = แสดงที่ระบบ Role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_setting_groups', function (Blueprint $table) {
            $table->dropColumn(['displays']);
        });
    }
}
