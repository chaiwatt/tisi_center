<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgentIdToSection5ApplicationIbcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->integer('agent_id')->nullable()->comment('id ตาราง sso_users')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->dropColumn(['agent_id']);
        });
    }
}
