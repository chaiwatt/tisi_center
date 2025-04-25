<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySendCertificateHistoryStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_send_certificate_history', function (Blueprint $table) {	
            $table->string('certificate_oldfile',255)->nullable()->comment('ชื่อไฟล์ที่เก็บข้อมูล(เก่า)');
            $table->integer('status')->nullable()->comment('สถานะ 1-สำเร็จ, 2-ไม่สำเร็จ');
            $table->integer('status_revoke')->nullable()->comment('สถานการณ์ 1-ยกเลิกใบรับรอง');
            $table->dateTime('date_revoke')->nullable()->comment('วัน/เวลา ที่ยกเลิกใบรับรอง');
            $table->text('reason_revoke')->nullable()->comment('เหตุผลการยกเลิกใบรับรอง');
            $table->integer('user_revoke')->nullable()->comment('ผู้ยกเลิกใบรับรอง');
        });
    }

    /** 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_send_certificate_history', function (Blueprint $table) {
            $table->dropColumn(['status']); 
        });
    }
}
