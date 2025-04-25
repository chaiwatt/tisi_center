<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnMenuJsonsToRolesSettingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_setting_groups', function (Blueprint $table) {
            $table->text('menu_jsons')->nullable()->comment('ชื่อไฟล์ Json Menu ');

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
            $table->dropColumn(['menu_jsons']);
        });
    }
}
