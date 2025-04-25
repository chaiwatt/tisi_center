<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendMailToAppCertiTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking', function (Blueprint $table) {
            $table->boolean('send_mail')->nullable()->comment('1=ส่งเมล')->after('agent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking', function (Blueprint $table) {
            $table->dropColumn(['send_mail']);
        });
    }
}
