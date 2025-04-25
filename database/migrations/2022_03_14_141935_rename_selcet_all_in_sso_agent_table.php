<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSelcetAllInSsoAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->renameColumn('selcet_all', 'select_all');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->renameColumn('select_all', 'selcet_all');
        });
    }
}
