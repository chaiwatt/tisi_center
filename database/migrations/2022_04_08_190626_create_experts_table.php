<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_experts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trader_id')->comment('Id เจ้าของคำขอ รหัส user_trader.id //id');
            $table->string('taxid',15)->comment('เลข 13 หลัก ผู้ประกอบการ');
            $table->string('head_name',255)->comment('ชื่อผู้ประกอบการ');
            $table->string('head_address_no',255)->nullable()->comment('ที่ตั้ง ผู้ประกอบการ');
            $table->string('head_village',255)->nullable()->comment('อาคาร/หมู่บ้าน ผู้ประกอบการ');
            $table->string('head_moo',255)->nullable()->comment('หมู่ ผู้ประกอบการ');
            $table->string('head_soi',255)->nullable()->comment('ตรอก/ซอย ผู้ประกอบการ');
            $table->string('head_subdistrict',255)->nullable()->comment('ตำบล/แขวง ผู้ประกอบการ');
            $table->string('head_district',255)->nullable()->comment('อำเภอ/เขต ผู้ประกอบการ');
            $table->string('head_province',255)->nullable()->comment('จังหวัด ผู้ประกอบการ');
            $table->string('head_zipcode',5)->nullable()->comment('รหัสไปรษณีย์ ผู้ประกอบการ');
            $table->string('contact_address_no',255)->nullable()->comment('ที่ตั้ง ติดต่อผู้ประกอบการ');
            $table->string('contact_village',255)->nullable()->comment('อาคาร/หมู่บ้าน ติดต่อผู้ประกอบการ');
            $table->string('contact_moo',255)->nullable()->comment('หมู่ ติดต่อผู้ประกอบการ');
            $table->string('contact_soi',255)->nullable()->comment('ตรอก/ซอย ติดต่อผู้ประกอบการ');
            $table->string('contact_subdistrict',255)->nullable()->comment('ตำบล/แขวง ติดต่อผู้ประกอบการ');
            $table->string('contact_district',255)->nullable()->comment('อำเภอ/เขต ติดต่อผู้ประกอบการ');
            $table->string('contact_province',255)->nullable()->comment('จังหวัด ติดต่อผู้ประกอบการ');
            $table->string('contact_zipcode',5)->nullable()->comment('รหัสไปรษณีย์ ติดต่อผู้ประกอบการ');
            $table->string('mobile_phone',25)->comment('เบอร์โทรศัพท์มือถือ');
            $table->string('email',255)->comment('E-Mail ที่ใช้ในการติดต่อ');
            $table->integer('operation_id')->comment('Id ตาราง user_trader.id //id');
            $table->string('ref_no',255)->nullable()->comment('เลขที่คำขอ');
            $table->string('bank_name',255)->nullable()->comment('ชื่อธนาคาร');
            $table->string('bank_title',255)->nullable()->comment('ชื่อเจ้าของบัญชี');
            $table->string('bank_number',255)->nullable()->comment('เลขที่บัญชี');
            $table->text('bank_file')->nullable()->comment('ไฟล์แนบ หน้า Book Bank');
            $table->dateTime('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->integer('assign_by')->nullable()->comment('ผู้ที่ได้รับมอบหมาย');
            $table->dateTime('receive_date')->nullable()->comment('วันที่รับคำขอ');
            $table->integer('receive_by')->nullable()->comment('จนท. ผู้รับคำขอ');
            $table->text('detail')->nullable()->comment('รายละเอียด');
            $table->dateTime('confirm_date')->nullable()->comment('วัน/เวลา ที่อนุมัติ');
            $table->integer('confirm_by')->nullable()->comment('จนท. อนุมัติคำขอ');
            $table->string('expert_no',255)->nullable()->comment('รหัสผู้เชี่ยวชาญ');
            $table->dateTime('revoke_date')->nullable()->comment('วันที่ยกเลิก ผู้เชี่ยวชาญ');
            $table->text('revoke_detail')->nullable()->comment('รายละเอียดการยกเลิก ผู้เชี่ยวชาญ');
            $table->integer('revoke_by')->nullable()->comment('ผู้บันทึกการยกเลิก ผู้เชี่ยวชาญ');
            $table->integer('state')->comment('1.ยื่นคำขอ, 2.อยู่ระหว่างการตรวจสอบคำขอ, 3.ตีกลับคำขอ, 4.ตรวจสอบคำขอแก้ไข, 5.เอกกสารผ่านการตรวจสอบ, 6.อนุมัติการขึ้นทะเบียน, 7.ยกเลิกคำขอ, 8.ยกเลิกผู้เชี่ยวชาญ');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('register_experts');
    }
}
