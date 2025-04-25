<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_groups', function (Blueprint $table) {
            $table->integer('role_id')->nullable()->comment('id ตาราง role');
            $table->integer('setting_systems_id')->nullable()->comment('id ตาราง setting_systems');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_groups');
    }
}
