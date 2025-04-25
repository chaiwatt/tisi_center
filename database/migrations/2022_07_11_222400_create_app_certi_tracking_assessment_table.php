<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAssessmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('auditors_id')->nullable()->comment('TB :app_certi_tracking_auditors');
            $table->string('name',255)->nullable()->comment('ชื่อผู้ยื่นคำขอ');  
            $table->string('laboratory_name',255)->nullable()->comment('ชื่อห้องปฏิบัติการ');  
            $table->date('report_date')->nullable()->comment('วันที่ทำรายงาน');  
            $table->integer('bug_report')->nullable()->comment('รายงานข้อบกพร่อง 1.มี 2.ไม่มี');  
            $table->integer('degree')->nullable()->comment('0.ฉบับร่าง 1.จนท ส่งให้ ผปก 2.ผปก ส่งให้ จนท');  
            $table->integer('status')->nullable()->comment('1. ยืนยัน Scope 2. ขอแก้ไข Scope');
            $table->integer('main_state')->nullable()->comment('การตรวจประเมิน 1.เอกสารครบแล้ว 2.แต่งตั้งคณะกรรมการเพิ่ม');
            $table->date('date_car')->nullable()->comment('วันที่ทำรายงาน');  
            $table->integer('status_car')->nullable()->comment('1.แจ้งเตือน 60 วัน 2.แจ้งเตือน 90 วัน');  
            $table->text('details')->nullable()->comment('รายละเอียด');  
            $table->integer('state')->nullable();
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
        Schema::dropIfExists('app_certi_tracking_assessment');
    }
}
