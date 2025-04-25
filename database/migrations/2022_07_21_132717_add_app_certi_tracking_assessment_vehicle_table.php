<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingAssessmentVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_assessment', function (Blueprint $table) {
            $table->integer('vehicle')->nullable()->comment('1.ส่งให้ผู้ประกอบการ 2.ผู้ประกอบการส่งให้เจ้าหน้าที่')->after('details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_assessment', function (Blueprint $table) {
              $table->dropColumn(['vehicle']);
        });
    }
}
