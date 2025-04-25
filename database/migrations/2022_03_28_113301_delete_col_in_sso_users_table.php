<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['activation', 'otpKey', 'otep', 'requireReset', 'token_otp']);
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
            $table->string('activation', 100)->default('');
            $table->string('otpKey', 1000)->default('')->comment('Two factor authentication encrypted keys');
            $table->string('otep', 1000)->default('')->comment('One time emergency passwords');
            $table->boolean('requireReset')->default(0)->comment('Require user to reset password on next login');
            $table->string('token_otp')->nullable()->comment('Token OTP');
        });
    }
}
