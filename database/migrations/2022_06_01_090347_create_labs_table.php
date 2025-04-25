<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_labs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable()->comment('ชื่อห่วยงาน');
            $table->string('taxid', 255)->nullable()->comment('เลขนิติบุคคล');

            $table->string('lab_code', 255)->nullable()->comment('ห้องปฏิบัติการ: รหัสปฏิบัติการ');
            $table->text('lab_name')->nullable()->comment('ห้องปฏิบัติการ: ชื่อห้องปฏิบัติการ');
            $table->string('lab_address')->nullable()->comment('ห้องปฏิบัติการ: ที่อยู่');
            $table->string('lab_moo')->nullable()->comment('ห้องปฏิบัติการ: หมู่');
            $table->string('lab_soi')->nullable()->comment('ห้องปฏิบัติการ: ซอย');
            $table->string('lab_building',255)->nullable()->comment('ห้องปฏิบัติการ: ข้อมูลผู้ยื่นขอ อาคาร/หมู่บ้าน');
            $table->string('lab_road')->nullable()->comment('ห้องปฏิบัติการ: ซอยถนน');
            $table->integer('lab_subdistrict_id')->nullable()->comment('ห้องปฏิบัติการ: ตำบล/แขวง');
            $table->integer('lab_district_id')->nullable()->comment('ห้องปฏิบัติการ: อำเภอ/เขต');
            $table->integer('lab_province_id')->nullable()->comment('ห้องปฏิบัติการ: จังหวัด');
            $table->string('lab_zipcode')->nullable()->comment('ห้องปฏิบัติการ: รหัสไปรษณีย์');
            $table->string('lab_phone')->nullable()->comment('ห้องปฏิบัติการ: เบอร์โทรศัพท์');
            $table->string('lab_fax')->nullable()->comment('ห้องปฏิบัติการ: เบอร์แฟกซ์');

            $table->string('co_name')->nullable()->comment('ผู้ประสานงาน: ชื่อผู้ประสานงาน');
            $table->string('co_position')->nullable()->comment('ผู้ประสานงาน: ตำแหน่งผู้ประสานงาน');
            $table->string('co_mobile')->nullable()->comment('ผู้ประสานงาน: เบอร์มือถือ');
            $table->string('co_phone')->nullable()->comment('ผู้ประสานงาน: เบอร์โทรศัพท์');
            $table->string('co_fax')->nullable()->comment('ผู้ประสานงาน: เบอร์แฟกซ์');
            $table->string('co_email')->nullable()->comment('ผู้ประสานงาน: อีเมล');

            $table->date('lab_start_date')->nullable()->comment('วันที่เริ่มเป็น LAB');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_lab_application_no', 255)->nullable()->comment('อ้างอิงเลขที่คำขอ');
            
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
        Schema::dropIfExists('section5_labs');
    }
}
