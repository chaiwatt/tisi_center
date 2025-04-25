<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingInspectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_inspection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('status')->nullable()->comment('สถานะ 1.ยืนยัน Scope 2. แก้ไข Scope');
            $table->datetime('created_date')->nullable()->comment('วันที่ยืนยัน Scope');
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
        Schema::dropIfExists('app_certi_tracking_inspection');
    }
}
