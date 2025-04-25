<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToRolesSettingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_setting_groups', function (Blueprint $table) {
            $table->string('urls')->nullable()->comment('link ระบบ');
			$table->string('icons')->nullable()->comment('icons');
			$table->string('colors')->nullable()->comment('colors');
            $table->integer('ordering')->default(0);
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
            $table->dropColumn(['urls', 'icons', 'colors','ordering']);
            
        });
    }
}
