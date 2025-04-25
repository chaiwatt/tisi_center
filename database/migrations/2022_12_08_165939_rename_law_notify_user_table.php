<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawNotifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_notify_user', function (Blueprint $table) {
            $table->dropColumn(['email']); 
        });
        Schema::table('law_notify', function (Blueprint $table) {
            $table->string('email',255)->nullable()->comment('รายชื่ออีเมลส่งถึง (json)')->after('notify_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_notify_user', function (Blueprint $table) {
            $table->string('email',255)->nullable()->comment('รายชื่ออีเมลส่งถึง (json)');
        });

        Schema::table('law_notify', function (Blueprint $table) {
            $table->dropColumn(['email']); 
        });
    }
}
