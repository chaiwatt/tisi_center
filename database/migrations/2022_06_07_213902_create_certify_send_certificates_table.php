<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySendCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_send_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sign_name', 255)->nullable()->comment('ชื่อผู้ลงนาม');
            $table->string('sign_position', 255)->nullable()->comment('ตำแหน่ง');
            $table->integer('sign_check')->nullable()->comment('1-ปฏิบัติแทน');
            $table->string('sign_id', 255)->nullable()->comment('TB : besurv_signers . ID');
            $table->integer('certificate_type')->nullable()->comment('ประเภทใบรับรอง 1-CB , 2-IB , 3-LAB');
            $table->integer('state')->nullable()->comment('สถานะ 99-ร่าง , 1-นำส่งใบรับรองระบบงานลงนาม , 2-อยู่ระหว่างยืนยันการลงนาม , 3-ลงนามใบรับรองเรียบร้อย');
            $table->integer('created_by')->nullable()->comment('จนท. ที่นำส่ง');
            $table->integer('updated_by')->nullable()->comment('จนท. ที่อัพเดพ');
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
        Schema::dropIfExists('certify_send_certificates');
    }
}
