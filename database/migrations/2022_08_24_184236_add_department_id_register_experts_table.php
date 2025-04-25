<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentIdRegisterExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->integer('department_id')->nullable()->comment('idตาราง : basic_appoint_departments')->after('operation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->dropColumn(['department_id']);
        });
    }
}
