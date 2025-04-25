<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameInWsClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ws_client', function (Blueprint $table) {
            $table->renameColumn('ClientID', 'app_name');
            $table->renameColumn('ClientSecret', 'app_secret');
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
            $table->renameColumn('app_name', 'ClientID');
            $table->renameColumn('app_secret', 'ClientSecret');
        });
    }
}
