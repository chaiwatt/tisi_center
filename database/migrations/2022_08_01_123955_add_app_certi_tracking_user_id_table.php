<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingUserIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking', function (Blueprint $table) {
            $table->string('tax_id',15)->nullable()->after('status_id')->comment('เลขบัตรประชาชน');
            $table->integer('user_id')->nullable()->after('tax_id')->comment('id ตาราง sso_users ผู้บันทึก');
            $table->integer('agent_id')->nullable()->after('user_id')->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking', function (Blueprint $table) {
            $table->dropColumn(['tax_id','user_id','agent_id']);
        });
    }
}
