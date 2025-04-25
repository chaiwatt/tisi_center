<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySendCertificateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_send_certificate_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('send_certificate_list_id')->nullable()->comment('id ตาราง certify_send_certificate_lists');
            $table->integer('certificate_type')->nullable()->comment('ประเภทใบรับรอง 1-CB , 2-IB , 3-LAB');
            $table->string('certificate_tb', 255)->nullable()->comment('ตารางใบรับรองจามประเภท');
            $table->integer('certificate_id')->nullable()->comment('ID ตารางใบรับรองจามประเภท');
            $table->string('certificate_no', 255)->nullable()->comment('เลขที่ใบรับรอง.');
            $table->string('app_no', 255)->nullable()->comment('เลขคำขอ');
            $table->string('name', 255)->nullable()->comment('ชื่อผู้ยื่นขอรับรองการรับรอง ');
            $table->string('tax_id', 50)->nullable()->comment('เลขบัตรประชาชน');
            $table->integer('sign_id')->nullable()->comment('TB : besurv_signers . ID');
            $table->string('certificate_path',255)->nullable()->comment('Path เก็บไฟล์ใบอนุญาต');
            $table->string('certificate_file',255)->nullable()->comment('ชื่อไฟล์ที่เก็บข้อมูล ก่อน Sign');
            $table->string('certificate_newfile',255)->nullable()->comment('ชื่อไฟล์ที่เก็บข้อมูล หลัง Sign');
            $table->string('documentId',255)->nullable()->comment('String ส าหรับอ้างอิง PDF หรือ PDF/A-3 ที่ลงทะเบียนไว้');
            $table->string('signtureid',255)->nullable()->comment('id ยืนยันการลงชื่ออิเล็กทรอนิกส์ส าหรับ DoumentID ดังกล่าวแล้ว');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('certify_send_certificate_history');
    }
}
