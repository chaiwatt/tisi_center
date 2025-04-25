<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiIbExportReferenceRefnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->string('reference_refno',255)->nullable()->after('certificate')->comment('เลขอ้างอิง');
            $table->integer('reference_check')->nullable()->after('reference_refno')->comment('สถานะเลขอ้างอิง 1=มอบหมายแล้ว');
            $table->datetime('reference_date')->nullable()->after('reference_check')->comment('วันที่เลขอ้างอิง');
            $table->integer('status_id')->nullable()->after('status')->comment('สถานะขั้นตอนขอต่ออายุ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->dropColumn(['reference_refno','reference_check','reference_date', 'status_id']);
        });
    }
}
