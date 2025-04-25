<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseBookOffendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_book_offend', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_result_id')->nullable()->comment('ID ตาราง law_case_result');
            $table->string('offend_number',100)->nullable()->comment('กระทำความผิด : เลขที่หนังสือ');
            $table->date('offend_date')->nullable()->comment('กระทำความผิด : วันที่ในหนังสือ');
            $table->string('offend_title',200)->nullable()->comment('กระทำความผิด : เรื่อง');
            $table->string('offend_send',200)->nullable()->comment('กระทำความผิด : สิ่งที่ส่งมาด้วย');
            $table->string('offend_lawyer',200)->nullable()->comment('กระทำความผิด : นิติกรเจ้าของสำนวน (runrecno ตาราง user_register)');
            $table->string('offend_signed',200)->nullable()->comment('กระทำความผิด : ผู้มีอำนาจลงนาม สมอ. (runrecno ตาราง user_register)');
            $table->string('accept_place',100)->nullable()->comment('คำให้การ : สถานที่ทำการ');
            $table->date('accept_date')->nullable()->comment('คำให้การ : วันที่หนังสือ');
            $table->integer('accept_power')->nullable()->comment('คำให้การ : อำนาจเปรียบเทียบปรับ 1.เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม (สมอ), 2.คณะกรรมการเปรียบเทียบ');
            $table->text('accept_board')->nullable()->comment('คำให้การ : กรรมการผู้มีอำนาจ / ตำแหน่ง (json)');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('law_case_book_offend');
    }
}
