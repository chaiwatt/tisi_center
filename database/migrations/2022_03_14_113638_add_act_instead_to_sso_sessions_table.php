<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActInsteadToSsoSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_sessions', function (Blueprint $table) {
            $table->integer('act_instead')->nullable()->comment('ดำเนินการแทน user ใด เก็บค่า id ตาราง sso_users ถ้าเป็น null ดำเนินการในฐานะตัวเอง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_sessions', function (Blueprint $table) {
            $table->dropColumn(['act_instead']);
        });
    }
}
