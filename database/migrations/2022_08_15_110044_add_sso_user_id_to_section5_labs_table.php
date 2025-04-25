<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoUserIdToSection5LabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs', function (Blueprint $table) {
            $table->integer('lab_user_id')->nullable()->after('lab_name')->comment('id ตาราง sso_users ผู้ใช้งานของ Lab นี้');
            $table->foreign('lab_user_id')
                  ->references('id')
                  ->on('sso_users')
                  ->onDelete('SET NULL')
                  ->onUpdate('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs', function (Blueprint $table) {
            $table->dropForeign(['lab_user_id']);
            $table->dropColumn(['lab_user_id']);
        });
    }
}
