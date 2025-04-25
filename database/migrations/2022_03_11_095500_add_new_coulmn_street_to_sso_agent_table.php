<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCoulmnStreetToSsoAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->string('head_street',255)->nullable()->comment('ถนน ผู้มอบ')->after('head_soi');
            $table->string('agent_street',255)->nullable()->comment('ถนน ผู้รับมอบ')->after('agent_soi');
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
            $table->dropColumn(['head_street', 'agent_street"']);
        });
    }
}
