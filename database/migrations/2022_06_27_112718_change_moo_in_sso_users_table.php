<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMooInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('moo', 80)->nullable()->comment('หมู่(ที่ตั้งสำนักงานใหญ่/ที่อยู่ตามทะเบียนบ้าน)')->change();
            $table->string('contact_moo', 80)->comment('หมู่(ที่อยู่ที่ติดต่อได้)')->nullable()->change();
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
            $table->string('moo', 30)->nullable()->comment('หมู่(ที่ตั้งสำนักงานใหญ่/ที่อยู่ตามทะเบียนบ้าน)')->change();
            $table->string('contact_moo', 30)->comment('หมู่(ที่อยู่ที่ติดต่อได้)')->nullable()->change();
        });
    }
}
