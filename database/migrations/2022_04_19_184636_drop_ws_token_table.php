<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropWsTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('ws_token');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('ws_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('ClientID', 255);
            $table->string('token', 255);
        });
    }
}
