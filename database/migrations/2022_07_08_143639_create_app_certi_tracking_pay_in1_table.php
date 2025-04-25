<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingPayIn1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_pay_in1', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('conditional_type')->nullable()->comment('เงื่อนไขการชำระเงิน 1.เรียกเก็บค่าธรรมเนียม , 2.ยกเว้นค่าธรรมเนียม , 3.ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ');
            $table->integer('auditors_id')->nullable()->comment('ID TB : app_certi_tracking_auditors');
            $table->decimal('amount_bill',15,2)->nullable()->comment('จำนวนเงิน');
            $table->date('start_date')->nullable()->comment('วันที่แจ้งชำระ');
            $table->integer('status')->nullable()->comment('สถานะ');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->text('detail')->nullable()->comment('หมายเหตุ');
            $table->integer('state')->nullable()->comment('1. ส่งให้ ผปก 2.ส่งให้ ผจท');
            $table->date('start_date_feewaiver')->nullable()->comment('วันที่เริ่มยกเว้นค่าธรรมเนียม');
            $table->date('end_date_feewaiver')->nullable()->comment('วันที่สิ้นสุดยกเว้นค่าธรรมเนียม');
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
        Schema::dropIfExists('app_certi_tracking_pay_in1');
    }
}
