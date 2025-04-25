<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySignCertificateOtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_sign_certificate_otp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Ref_otp', 255)->nullable()->comment('รหัสอ้างอิง');
            $table->string('otp', 255)->nullable()->comment('ชื่อผู้ลงนาม');
            $table->dateTime('Req_date')->nullable()->comment('วัน/เวลา ที่ส่งคำขอ');
            $table->integer('Req_by')->nullable()->comment('ผู้ส่งการขอ  OTP');
            $table->dateTime('Confirm_date')->nullable()->comment('วัน/เวลา ที่ยืนยัน');
            $table->integer('Confirm_by')->nullable()->comment('ผู้บันทึกการยืนยันตัวตน');
            $table->integer('state')->nullable()->comment('สถานะ  1-ส่งคำขอรับรหัส , 2-ยืนยันตัวตน , 3-หมดเวลาการยืนยันตัวตน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_sign_certificate_otp');
    }
}
