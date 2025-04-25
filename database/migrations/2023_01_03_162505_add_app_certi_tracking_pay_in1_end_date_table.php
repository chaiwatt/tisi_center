<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingPayIn1EndDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_pay_in1', function (Blueprint $table) {
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดชำระ')->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_pay_in1', function (Blueprint $table) {
            $table->dropColumn(['end_date']);  
        });
    }
}
