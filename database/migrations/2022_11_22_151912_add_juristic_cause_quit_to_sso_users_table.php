<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJuristicCauseQuitToSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('juristic_cause_quit')->nullable()->after('juristic_status')->comment('สาเหตุที่เลิกกิจการ กรณี juristic_status=4');
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
            $table->dropColumn(['juristic_cause_quit']);
        });
    }
}
