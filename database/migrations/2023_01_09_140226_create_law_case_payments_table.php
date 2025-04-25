<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ref_table')->nullable()->comment('ตารางอ้างอิง');
            $table->integer('ref_id')->nullable()->comment('ID ตารางอ้างอิง');
            $table->integer('condition_type')->nullable()->comment('เงื่อนไขการชำระ : 1.เรียกเก็บเงินค่าปรับ, 2.ไม่เรียกเก็บค่าปรับ');
            $table->date('start_date')->nullable()->comment('วันที่แจ้งชำระ');
            $table->date('amount_date')->nullable()->comment('ชำระภายใน/วัน');
            $table->date('end_date')->nullable()->comment('วันที่ครบกำหนดชำระ');
            $table->decimal('amount',12,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('paid_status')->nullable()->comment('สถานะชำระเงิน : 1.ยังไม่ชำระเงิน, 2.ชำระเงินแล้ว');
            $table->date('paid_date')->nullable()->comment('วันที่ชำระ');
            $table->integer('paid_type')->nullable()->comment('ประเภท : 1.Pay-in (กรมบัญชีกลาง), 2.นอกระบบ (เช่น ชำระ ณ สมอ. หรืออื่นๆ)');
            $table->integer('app_certi_transaction_pay_in_id')->nullable()->comment('ID : app_certi_transaction_pay_in');
            $table->integer('paid_channel')->nullable()->comment('ช่องทางชำระ : 1.เงินโอน, 2.เงินสด, 3.เช็คธนาคาร (ระบุ)');
            $table->text('paid_channel_remark')->nullable()->comment('ช่องทางชำระ (กรณีช่องทางที่ระบุ)');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
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
        Schema::dropIfExists('law_case_payments');
    }
}
 