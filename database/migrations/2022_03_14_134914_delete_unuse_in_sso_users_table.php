<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUnuseInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['consumer_secret', 'agent_id', 'consumer_key']);
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
            $table->string('consumer_secret')->nullable()->comment('ConsumerSecret ');
			$table->string('agent_id')->nullable()->comment('AgentID');
			$table->string('consumer_key')->nullable()->comment('Consumer-Key ');
        });
    }
}
