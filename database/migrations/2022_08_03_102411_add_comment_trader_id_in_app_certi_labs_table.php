<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentTraderIdInAppCertiLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->string('trader_id', 50)->comment('trader_autonumber ของตาราง user_trader (ไม่ได้ใช้แล้ว)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->string('trader_id', 50)->comment('')->change();
        });
    }
}
