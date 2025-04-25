<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_cases_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_case_id')->nullable()->comment('ID ตาราง law_cases');
            $table->integer('send_type')->nullable()->comment('ประเภทการส่ง');
            $table->string('send_no', 255)->nullable()->comment('ครั้งที่ส่ง');
            $table->string('title', 255)->nullable()->comment('เรื่อง');
            $table->string('send_to', 255)->nullable()->comment('เรียน');
            $table->integer('condition')->nullable()->comment('เงื่อนไข 1 ตอบกลับ , 2 ไม่ต้องตอบกลับ');
            $table->date('date_due')->nullable()->comment('วันที่ครบกำหนด');
            $table->text('attach_response')->nullable()->comment('ไฟล์ที่ตอบกลับ');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->text('response_remark')->nullable()->comment('ตอบกลับ : หมายเหตุ');
            $table->text('response_name')->nullable()->comment('ตอบกลับ : ชื่อสกุล');
            $table->text('response_tel')->nullable()->comment('ตอบกลับ : เบอร์โทร');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('law_case_id')
                    ->references('id')
                    ->on('law_cases')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_cases_delivery');
    }
}
