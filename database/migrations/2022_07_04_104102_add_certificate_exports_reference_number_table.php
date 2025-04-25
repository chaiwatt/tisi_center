<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertificateExportsReferenceNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
             $table->string('reference_refno',255)->nullable()->after('certificate_no')->comment('เลขอ้างอิง');
             $table->integer('reference_check')->nullable()->after('reference_refno')->comment('สถานะเลขอ้างอิง 1=มอบหมายแล้ว');
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
        Schema::table('certificate_exports', function (Blueprint $table) {
            $table->dropColumn(['reference_refno','reference_check','status_id']);
        });
    }
}
