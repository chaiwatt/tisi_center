<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawNotifyToLawNotifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_notify_user', function (Blueprint $table) {
            $table->unsignedInteger('law_notify_id')->nullable()->comment('ID : law_notify')->after('id');
            
            $table->foreign('law_notify_id')
                    ->references('id')
                    ->on('law_notify')
                    ->onDelete('cascade');
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
            $table->dropForeign(['law_notify_id']);
            $table->dropColumn(['law_notify_id']);
            
        });
    }
}
