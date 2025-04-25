<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameInWsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ws_log', function (Blueprint $table) {
            $table->renameColumn('client_id', 'app_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ws_log', function (Blueprint $table) {
            $table->renameColumn('app_name', 'client_id');
        });
    }
}
