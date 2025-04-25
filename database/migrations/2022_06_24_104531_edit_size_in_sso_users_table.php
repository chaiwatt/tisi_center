<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditSizeInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('soi', 80)->nullable()->comment('ซอย(ที่ตั้งสำนักงานใหญ่/ที่อยู่ตามทะเบียนบ้าน)')->change();
            $table->string('contact_soi', 80)->comment('ซอย(ที่อยู่ที่ติดต่อได้)')->nullable()->change();
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
            $table->string('soi', 50)->nullable()->comment('ซอย(ที่ตั้งสำนักงานใหญ่/ที่อยู่ตามทะเบียนบ้าน)')->change();
            $table->string('contact_soi', 50)->comment('ซอย(ที่อยู่ที่ติดต่อได้)')->nullable()->change();
        });
    }
}
