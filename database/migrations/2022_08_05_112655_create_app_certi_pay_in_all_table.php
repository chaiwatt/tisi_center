<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiPayInAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_pay_in_all', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('certify')->nullable()->comment('1.LAB, 2.IB, 3.CB, 4.LAB(ติดตาม), 5.IB(ติดตาม), 6.CB(ติดตาม)');
            $table->integer('conditional_type')->nullable()->comment('เงื่อนไขการชำระเงิน 1.เรียกเก็บค่าธรรมเนียม , 2.ยกเว้นค่าธรรมเนียม , 3.ชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆ');
            $table->decimal('amount',15,2)->nullable()->comment('จำนวนเงิน');
            $table->date('start_date')->nullable()->comment('วันที่แจ้งชำระ'); 
            $table->string('name',255)->nullable()->comment('ชื่อผู้ยื่นขอ');
            $table->string('app_no',255)->nullable()->comment('เลขที่คำขอ');
            $table->string('tax_id',255)->nullable()->comment('เลขบัตรประชาชน');
            $table->text('name_unit')->nullable()->comment('ชื่อห้องปฏิบัติการ/ชื่อหน่วยตรวจสอบ/ชื่อหน่วยรับรอง');
            $table->string('auditors_name',255)->nullable()->comment('ชื่อคณะผู้ตรวจประเมิน');
            $table->integer('state')->nullable()->comment('1.ใบแจ้งชำระเงินค่าตรวจประเมินและค่าธรรมเนียมคำขอ 2.ค่าธรรมเนียมใบรับรอง');
            $table->text('detail')->nullable()->comment('หมายเหตุ');
            $table->date('start_date_feewaiver')->nullable()->comment('วันที่เริ่มยกเว้นค่าธรรมเนียม');
            $table->date('end_date_feewaiver')->nullable()->comment('วันที่สิ้นสุดยกเว้นค่าธรรมเนียม');
            $table->text('attach')->nullable()->comment('ไฟล์แนบ');
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
        Schema::dropIfExists('app_certi_pay_in_all');
    }
}
