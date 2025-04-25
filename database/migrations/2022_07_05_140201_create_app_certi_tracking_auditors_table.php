<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAuditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_auditors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->string('no',255)->nullable()->comment('ชื่อผู้ยื่นคำขอ');
            $table->string('auditor',255)->nullable()->comment('ชื่อคณะผู้ตรวจประเมิน');
            $table->string('vehicle',255)->nullable()->comment('1.ส่งให้ผู้ประกอบการ 2.ผู้ประกอบการส่งให้เจ้าหน้าที่');
            $table->integer('status')->nullable()->comment('1.เห็นชอบดำเนินการแต่งตั้ง 2.ไม่เห็นชอบ');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->integer('step_id')->nullable()->comment('TB : app_certi_tracking_auditors_step');
            $table->integer('status_cancel')->nullable()->comment('สถานะ  1 = ยกเลิก');
            $table->text('reason_cancel')->nullable()->comment('หมายเหตุยกเลิก');
            $table->integer('created_cancel')->nullable()->comment('ผู้บันทึกยกเลิก');
            $table->datetime('date_cancel')->nullable()->comment('วันที่ยกเลิก');
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
        Schema::dropIfExists('app_certi_tracking_auditors');
    }
}
