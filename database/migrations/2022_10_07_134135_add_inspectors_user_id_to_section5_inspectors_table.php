<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInspectorsUserIdToSection5InspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->integer('inspectors_user_id')->nullable()->comment('id ตาราง sso_users ผู้ใช้งาน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->dropColumn(['inspectors_user_id']);  
        });
    }
}
