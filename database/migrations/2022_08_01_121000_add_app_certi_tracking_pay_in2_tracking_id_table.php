<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingPayIn2TrackingIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_pay_in2', function (Blueprint $table) {
            $table->unsignedInteger('tracking_id')->nullable()->after('id')->comment('TB : app_certi_tracking . id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_pay_in2', function (Blueprint $table) {
            $table->dropColumn(['tracking_id']);
        });
    }
}
