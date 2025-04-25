<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ws_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 50)->nullable();
            $table->string('client_title')->nullable();
            $table->string('api_name', 50)->nullable();
            $table->string('ip', 50)->comment('ที่อยู่ไอพี')->nullable();
            $table->string('status', 5)->comment('รหัสสถานะ')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('request_time')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_log');
    }
}
