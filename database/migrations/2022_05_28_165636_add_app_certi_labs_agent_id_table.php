<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabsAgentIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->integer('agent_id')->after('created_by')->nullable()->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
    }
}
