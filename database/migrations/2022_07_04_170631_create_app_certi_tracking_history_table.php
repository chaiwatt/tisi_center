<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง'); 
            $table->integer('system')->nullable()->comment('1.มอบหมาย');
            $table->longText('details_one')->nullable()->comment('รายละเอียด');
            $table->longText('details_two')->nullable()->comment('รายละเอียด');
            $table->longText('details_three')->nullable()->comment('รายละเอียด');
            $table->longText('details_four')->nullable()->comment('รายละเอียด');
            $table->longText('details_five')->nullable()->comment('รายละเอียด');
            $table->longText('details_auditors_cancel')->nullable()->comment('ถ้าเป็นข้อมูล json ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน CB');
            $table->longText('file')->nullable()->comment('หลักฐาน');
            $table->longText('attachs')->nullable()->comment('หลักฐาน');
            $table->longText('attachs_car')->nullable()->comment('ไฟล์แนบ car'); 
            $table->integer('status')->nullable();
            $table->integer('status_scope')->nullable();
            $table->Text('remark')->nullable();
            $table->Text('attachs_file')->nullable();
            $table->Text('evidence')->nullable();
            $table->date('date')->nullable()->comment('วันที่บันทึก (ผปก)');
            $table->integer('user_id')->nullable()->comment('ผู้บันทึก (ผปก) id TB : sso_users');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก (จนท)');
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
        Schema::dropIfExists('app_certi_tracking_history');
    }
}
