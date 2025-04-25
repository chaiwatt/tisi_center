<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb4_tisilicense_change', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tbl_licenseNo',255)->nullable()->comment('เลขที่ใบอนุญาต');
            $table->string('tbl_licenseType',10)->nullable()->comment('ประเภทใบอนุญาต (ส, ท, น, นค)');
            $table->string('refno',255)->nullable()->comment('เลขที่คำขอ');
            $table->integer('pageNo')->nullable()->comment('หน้าในใบอนุญาตลำดับที่ 3');
            $table->integer('change_type')->nullable()->comment('ประเภทการเปลี่ยนแปลง 1.มอ.8, 2.มอ.8/1, 3.มอ.9, 4.ขอเปลี่ยนแปลงชื่อในอนุญาต, 5.ขอเปลี่ยนแปลงที่ตั้งสำนักงานแห่งใหญ่, 6.ขอเปลี่ยนแปลงชื่อโรงงานที่ทำประเทศ, 7.ขอเปลี่ยนแปลงที่ตั้งโรงงานที่ทำที่ไม่ใช่การย้ายสถานที่ในประเทศ, 8.ขอเปลี่ยนแปลงเลขทะเบียนโรงงาน, 9.ขอแปรสภาพ, 10.ขอควบรวมกิจการ, 11.ขอรวมใบอนุญาต, 12.ขออื่นๆ');
            $table->text('change_field')->nullable()->comment('ฟิดล์ที่เปลี่ยน');
            $table->text('change_from')->nullable()->comment('เปลี่ยนจาก');
            $table->text('change_to')->nullable()->comment('เปลี่ยนเป็น');
            $table->integer('ordering')->nullable()->comment('ลำดับ อ้างอิงตาม ใบอนุญาต และ ประเถท');
            $table->timestamp('created_at')->nullable()->comment('วันที่บันทึก');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->timestamp('updated_at')->nullable()->comment('วันที่อัพเดพ');
            $table->integer('updated_by')->nullable()->comment('ผู้อัพเดพ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb4_tisilicense_change');
    }
}
