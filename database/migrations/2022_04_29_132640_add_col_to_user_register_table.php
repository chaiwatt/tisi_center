<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToUserRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_register', function (Blueprint $table) {
            $table->string('Line_token', 45)->nullable()->comment('ผอ.แจ็ค')->after('change_sub_temp');
            $table->string('Line_userId', 35)->nullable()->comment('ผอ.แจ็ค')->after('Line_token');
            $table->string('position', 150)->nullable()->comment('ตำแหน่ง(แป้ง)')->after('Line_userId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_register', function (Blueprint $table) {
            $table->dropColumn(['Line_token', 'Line_userId', 'position']);
        });
    }
}
