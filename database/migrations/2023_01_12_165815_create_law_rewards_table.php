<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_id')->comment('ID : ตาราง law_case');
            $table->string('case_number',255)->nullable()->comment('เลขคดี');
            $table->integer('law_case_payments_id')->nullable()->comment('อ้างอิงตารางการจ่ายเงิน ID : law_case_payments');
            $table->decimal('paid_amount',30,2)->nullable()->comment('ยอดเงินค่าปรับ');
            $table->dateTime('paid_date')->nullable()->comment('วันที่ชำระเงิน');
            $table->string('receiptcode',255)->nullable()->comment('เลขที่ใบเสร็จ');
            $table->integer('step_froms')->nullable()->comment('ขั้นตอน 1.รายชื่อผู้มีสิทธิ์ได้รับเงิน, 2.คำนวณ, 3.พิมพ์ใบสรุปการคำนวณ');
            $table->decimal('government_total',30,2)->nullable()->comment('คำนวณส่วนที่ 1 หักเป็นรายได้แผ่นดิน');
            $table->decimal('group_total',30,2)->nullable()->comment('คำนวณส่วนที่ 1 เงินสินบน เงินรางวัล ค่าใช้จ่ายในการดำเนิน');
            $table->decimal('operate_total',30,2)->nullable()->comment('คำนวณส่วนที่ 2 ค่าใช้จ่ายดำเนินงาน');
            $table->decimal('bribe_total',30,2)->nullable()->comment('คำนวณส่วนที่ 2 เงินสินบน');
            $table->decimal('reward_total',30,2)->nullable()->comment('คำนวณส่วนที่ 2 เงินรางวัล');
            $table->integer('status')->nullable()->comment('สถานะ null.รอคำนวณเงิน, 99.ฉบับร่าง, 1.อยู่ระหว่างคำนวณ, 2.ยืนยันการคำนวณ, 3.อยู่ระหว่างรวบรวมหลักฐานเพื่อเบิกจ่าย, 4.อยู่ระหว่างขอเบิกจ่าย, 5.เบิกจ่ายเรียบร้อย');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข ID : user_register');
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
        Schema::dropIfExists('law_rewards');
    }
}
