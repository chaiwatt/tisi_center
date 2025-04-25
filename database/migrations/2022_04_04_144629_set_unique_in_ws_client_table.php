<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetUniqueInWsClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ws_client', function (Blueprint $table) {
            $table->string('app_name')->unique()->comment('app_name ใช้ส่งมาใน API')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ws_client', function (Blueprint $table) {
            $table->dropUnique('ws_client_app_name_unique');
        });
    }
}
