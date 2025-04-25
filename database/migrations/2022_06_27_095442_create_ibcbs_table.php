<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('ibcb_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');
            $table->integer('ibcb_type')->nullable()->comment('ประเภทหน่วยตรวจสอบ');

            $table->string('name',255)->nullable()->comment('ชื่อ-นามสกุล');
            $table->string('taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');

            $table->string('ibcb_name')->nullable()->comment('หน่วยงาน: ชื่อหน่วยงาน');
            $table->string('ibcb_address')->nullable()->comment('หน่วยงาน: ที่อยู่');
            $table->string('ibcb_building')->nullable()->comment('หน่วยงาน: อาคาร/หมู่บ้าน');
            $table->string('ibcb_moo')->nullable()->comment('หน่วยงาน: หมู่');
            $table->string('ibcb_soi')->nullable()->comment('หน่วยงาน: ซอย');
            $table->string('ibcb_road')->nullable()->comment('หน่วยงาน: ซอยถนน');
            $table->integer('ibcb_subdistrict_id')->nullable()->comment('หน่วยงาน: ตำบล/แขวง');
            $table->integer('ibcb_district_id')->nullable()->comment('หน่วยงาน: อำเภอ/เขต');
            $table->integer('ibcb_province_id')->nullable()->comment('หน่วยงาน: จังหวัด');
            $table->string('ibcb_zipcode')->nullable()->comment('หน่วยงาน: รหัสไปรษณีย์');
            $table->string('ibcb_phone')->nullable()->comment('หน่วยงาน: เบอร์โทรศัพท์');
            $table->string('ibcb_fax')->nullable()->comment('หน่วยงาน: เบอร์แฟกซ์');

            $table->string('co_name')->nullable()->comment('ผู้ประสานงาน: ชื่อผู้ประสานงาน');
            $table->string('co_position')->nullable()->comment('ผู้ประสานงาน: ตำแหน่งผู้ประสานงาน');
            $table->string('co_mobile')->nullable()->comment('ผู้ประสานงาน: เบอร์มือถือ');
            $table->string('co_phone')->nullable()->comment('ผู้ประสานงาน: เบอร์โทรศัพท์');
            $table->string('co_fax')->nullable()->comment('ผู้ประสานงาน: เบอร์แฟกซ์');
            $table->string('co_email')->nullable()->comment('ผู้ประสานงาน: อีเมล');

            $table->datetime('ibcb_start_date')->nullable()->comment('วันที่เริ่มเป็นหน่วยตรวจสอบ IB/CB');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_ibcb_application_no',100)->nullable()->comment('เลขที่คำขอ');

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
        Schema::dropIfExists('section5_ibcbs');
    }
}
