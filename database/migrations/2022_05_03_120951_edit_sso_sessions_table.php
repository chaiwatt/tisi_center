<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditSsoSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_sessions', function (Blueprint $table) {
            $table->dropColumn(['payload', 'status']);
            $table->dateTime('last_visit_at')->nullable()->comment('เข้าถึงระบบล่าสุด');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_sessions', function (Blueprint $table) {
            $table->string('payload')->comment('ข้อความที่เก็บ');
			$table->integer('status')->nullable();
            $table->dropColumn(['last_visit_at']);
        });
    }
}
