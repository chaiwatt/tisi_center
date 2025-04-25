<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseImpoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_impounds', function (Blueprint $table) {
            $table->increments('id')->comment('รหัสประจำตาราง');
            $table->integer('law_case_id')->comment('ID : ตาราง law_case');
            $table->integer('impound_status')->comment('มีผลิตภัณฑ์ยึด-อายัด หรือไม่ 1=มี 2=ไม่มี');
            $table->string('ref_tb')->nullable()->comment('ตารางอ้างอิง');
            $table->string('ref_id')->nullable()->comment('เลขอ้างอิงยึด-อายัด');
            $table->date('date_impound')->nullable()->comment('วันที่ยึด-อายัด');
            $table->string('location',200)->nullable()->comment('สถานที่ตรวจยึด');
            $table->string('storage_name',255)->nullable()->comment('ชื่อสถานที่จัดเก็บผลิตภัณฑ์');
            $table->string('storage_address_no',200)->nullable()->comment('เลขที่จัดเก็บผลิตภัณฑ์');
            $table->string('storage_soi',50)->nullable()->comment('จัดเก็บผลิตภัณฑ์ ตรอก/ซอย');
            $table->string('storage_street',50)->nullable()->comment('จัดเก็บผลิตภัณฑ์ ถนน');
            $table->string('storage_moo',20)->nullable()->comment('จัดเก็บผลิตภัณฑ์ หมู่ที่');
            $table->integer('storage_subdistrict_id')->nullable()->comment('จัดเก็บผลิตภัณฑ์ ตำบล/แขวง');
            $table->integer('storage_district_id')->nullable()->comment('จัดเก็บผลิตภัณฑ์ อำเภอ/เขต');
            $table->integer('storage_province_id')->nullable()->comment('จัดเก็บผลิตภัณฑ์ จังหวัด');
            $table->string('storage_zipcode',10)->nullable()->comment('จัดเก็บผลิตภัณฑ์ รหัสไปรษณีย์');
            $table->integer('law_basic_resource_id')->nullable()->comment('แหล่งที่มาของราคาผลิตภัณฑ์ ตาราง law_basic_resource');
            $table->mediumInteger('total_value')->nullable()->comment('รวมมูลค่าของกลาง');
            $table->bigInteger('created_by')->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('law_case_impounds');
    }
}
