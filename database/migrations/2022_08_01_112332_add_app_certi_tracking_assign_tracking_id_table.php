<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingAssignTrackingIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_assign', function (Blueprint $table) {
            $table->unsignedInteger('tracking_id')->nullable()->after('id')->comment('TB : app_certi_tracking . id');

            // $table->foreign('tracking_id')
            //       ->references('id')
            //       ->on('app_certi_tracking')
            //       ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_assign', function (Blueprint $table) {
            // $table->dropForeign(['tracking_id']);
            $table->dropColumn(['tracking_id']);
        }); 
    }
}
