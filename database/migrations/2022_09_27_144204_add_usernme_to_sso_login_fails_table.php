<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernmeToSsoLoginFailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_login_fails', function (Blueprint $table) {
            $table->string('username', 150)->nullable()->comment('username ที่ใช้เข้าสู่ระบบ')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_login_fails', function (Blueprint $table) {
            $table->dropColumn(['username']);
        });
    }
}
