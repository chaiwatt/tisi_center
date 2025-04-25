<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumsFromLawConfigNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_config_notification', function (Blueprint $table) {
            $table->dropColumn(['color', 'condition', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_config_notification', function (Blueprint $table) {
            $table->string('color')->nullable()->comment('สี เก็บชื่อ class css : danger, warning, success');
            $table->string('condition')->nullable()->comment('เงื่อนไข < = >');
            $table->tinyInteger('amount')->default(0)->comment('จำนวน(วัน)');
        });
    }
}
