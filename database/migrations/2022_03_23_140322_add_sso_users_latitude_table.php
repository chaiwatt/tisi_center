<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoUsersLatitudeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('latitude', 255)->nullable()->comment('พิกัดที่ตั้ง (ลองจิจูด)')->after('fax');
            $table->string('longitude', 255)->nullable()->comment('พิกัดที่ตั้ง (ละติจูด)')->after('latitude');
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
            $table->dropColumn(['latitude','longitude']);
        });
    }
}
