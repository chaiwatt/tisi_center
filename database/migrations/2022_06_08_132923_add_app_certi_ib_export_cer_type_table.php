<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiIbExportCerTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->integer('cer_type')->nullable()->comment('ประเภทการออกใบอนุญาต (1.แบบธรรมดา, 2.แบบอิเล็กทรอนิกส์)');
            $table->string('certificate_path',255)->nullable()->comment('Path เก็บไฟล์ใบอนุญาต');
            $table->string('certificate_file',255)->nullable()->comment('ชื่อไฟล์ที่เก็บข้อมูล ก่อน Sign');
            $table->string('certificate_newfile',255)->nullable()->comment('ชื่อไฟล์ที่เก็บข้อมูล หลัง Sign');
            $table->string('documentId',255)->nullable()->comment('String ส าหรับอ้างอิง PDF หรือ PDF/A-3 ที่ลงทะเบียนไว้');
            $table->string('signtureid',255)->nullable()->comment('id ยืนยันการลงชื่ออิเล็กทรอนิกส์ส าหรับ DoumentID ดังกล่าวแล้ว');
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
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->dropColumn(['cer_type','certificate_path','certificate_file','certificate_newfile','documentId','signtureid','status_revoke','date_revoke','reason_revoke','user_revoke']);
        });
    }
}
 