<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSsoLoginFailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_login_fails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip_address', 255)->nullable()->comment('ไอพีแอดเดรส');
            $table->dateTime('login_at')->comment('วันเวลา Login เข้าสู่ระบบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_login_fails');
    }
}
