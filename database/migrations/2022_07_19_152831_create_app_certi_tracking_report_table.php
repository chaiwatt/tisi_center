<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->date('report_date')->nullable()->comment('วันที่ประชุม');
            $table->integer('report_status')->nullable()->comment('มติคณะอนุกรรมการ 1. เห็นชอบ 2.ไม่เห็นชอบ');
            $table->text('details')->nullable()->comment('รายละเอียด');
            $table->integer('status_confirm')->nullable()->comment('ผปก. ยื่นยันใบรับรอง');
            $table->date('date_confirm')->nullable()->comment('วันที่ยืนยันคำขอรับใบรับรอง');
            $table->integer('status_alert')->nullable()->comment('1. แจ้งเตือน 30 วัน');
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
        Schema::dropIfExists('app_certi_tracking_report');
    }
}
