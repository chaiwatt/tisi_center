<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccereditationNoToAppCertiLabsAndAppCertiIbAndAppCertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->string('accereditation_no')->nullable()->after('certificate_exports_id')->comment('หมายเลขการรับรอง');
        });
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->string('accereditation_no')->nullable()->after('app_certi_ib_export_id')->comment('หมายเลขการรับรอง');
        });
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->string('accereditation_no')->nullable()->after('app_certi_cb_export_id')->comment('หมายเลขการรับรอง');
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
            $table->dropColumn(['accereditation_no']); 
        });
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->dropColumn(['accereditation_no']); 
        });
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropColumn(['accereditation_no']); 
        });
    }
}
