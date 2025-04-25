<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAppCertiCbExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->string('certificate_running')->nullable()->after('reference_date')->comment('เลขที่ใบรับรอง');
            $table->string('accreditation_running')->nullable()->after('certificate_running')->comment('หมายเลขการรับรอง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->dropColumn(['certificate_running', 'accreditation_running']);
        });
    }
}
