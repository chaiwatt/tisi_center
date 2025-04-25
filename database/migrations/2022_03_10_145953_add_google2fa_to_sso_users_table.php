<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogle2faToSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->tinyInteger('google2fa_status')->default(0)->comment('Login 2 ขั้นตอน Google Authenticator 0=ปิดใช้, 1=เปิดใช้');
            $table->string('google2fa_secret', 1024)->nullable()->comment('Login 2 ขั้นตอน Google Authenticator รหัสลับที่ใช้เชื่อมกับบัญชีนี้');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['google2fa_status', 'google2fa_secret']);
        });
    }
}
