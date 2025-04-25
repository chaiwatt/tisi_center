<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIbcbUserIdToSection5IbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->integer('ibcb_user_id')->nullable()->after('ibcb_name')->comment('id ตาราง sso_users ผู้ใช้งานของ ibcb นี้');
            $table->foreign('ibcb_user_id')
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
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->dropForeign(['ibcb_user_id']);
            $table->dropColumn(['ibcb_user_id']);
        });
    }
}
