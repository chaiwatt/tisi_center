<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertificateExportsDropStatusIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            $table->dropColumn(['status_id','reference_check']);
        });
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->dropColumn(['status_id','reference_check']);
        });
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->dropColumn(['status_id','reference_check']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            $table->integer('status_id')->nullable()->after('status')->comment('สถานะขั้นตอนขอต่ออายุ');
            $table->integer('reference_check')->nullable()->after('reference_refno')->comment('สถานะเลขอ้างอิง 1=มอบหมายแล้ว');
        });
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->integer('status_id')->nullable()->after('status')->comment('สถานะขั้นตอนขอต่ออายุ');
            $table->integer('reference_check')->nullable()->after('reference_refno')->comment('สถานะเลขอ้างอิง 1=มอบหมายแล้ว');
        });
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->integer('status_id')->nullable()->after('status')->comment('สถานะขั้นตอนขอต่ออายุ');
            $table->integer('reference_check')->nullable()->after('reference_refno')->comment('สถานะเลขอ้างอิง 1=มอบหมายแล้ว');
        });
    }
}
