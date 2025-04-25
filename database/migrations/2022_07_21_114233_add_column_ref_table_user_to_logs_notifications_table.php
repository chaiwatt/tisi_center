<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRefTableUserToLogsNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs_notifications', function (Blueprint $table) {
            $table->text('ref_table_user')->nullable()->comment('Users : จากตาราง')->after('users_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs_notifications', function (Blueprint $table) {
            $table->dropColumn(['ref_table_user']);
        });
    }
}
