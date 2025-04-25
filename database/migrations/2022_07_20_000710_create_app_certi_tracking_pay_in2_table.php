<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingPayIn2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_pay_in2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_type')->nullable()->comment('1.ห้องหน่วยรับรอง , 2.หน่วยตรวจสอบ , 3.ห้องปฏิบัติการ');
            $table->string('reference_refno',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง'); 
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            
            $table->integer('conditional_type')->nullable()->comment('เงื่อนไขการชำระเงิน 1.เรียกเก็บค่าธรรมเนียม , 2.ยกเว้นค่าธรรมเนียม , 3.ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ');
            $table->decimal('amount',15,2)->nullable()->comment('ค่าธรรมเนียมคำขอ');
            $table->decimal('amount_fee',15,2)->nullable()->comment('ค่าธรรมเนียมใบรับรอง');
            $table->decimal('amount_fixed',15,2)->nullable()->comment('จำนวนเงินคงที่');
            $table->integer('status')->nullable()->comment('สถานะ 1.รับชำระเงินเรียบร้อยแล้ว 2.ยังไม่ชำระเงิน');
            $table->date('report_date')->nullable()->comment('วันที่แจ้งชำระ');
            $table->text('detail')->nullable()->comment(' หมายเหตุ ยังไม่ชำระเงิน');

            $table->text('remark')->nullable()->comment('หมายเหตุชำระเงินนอกระบบ');     
            $table->date('start_date_feewaiver')->nullable()->comment('วันที่เริ่มยกเว้นค่าธรรมเนียม');
            $table->date('end_date_feewaiver')->nullable()->comment('วันที่สิ้นสุดยกเว้นค่าธรรมเนียม');
            
            $table->integer('status_cancel')->nullable()->comment('สถานะ  1 = ยกเลิกค่าธรรมเนียม');
            $table->integer('created_cancel')->nullable()->comment('ผู้ยกเลิกค่าธรรมเนียม');
            $table->date('date_cancel')->nullable()->comment('วันที่ยกเลิกค่าธรรมเนียม');
            $table->integer('state')->nullable()->comment('1. ส่งให้ ผปก 2.ส่งให้ ผจท');
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
        Schema::dropIfExists('app_certi_tracking_pay_in2');
    }
}
