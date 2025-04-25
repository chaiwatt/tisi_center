<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardReceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_recepts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recept_no',255)->nullable()->comment('เลขอ้างอิงใบสำคัญรับเงิน');
            $table->integer('recepts_type')->nullable()->comment('รูปแบบ 1.รายคดี, 2.รายเดือน, 3.ช่วงวันที่');
            $table->text('recepts_type_detail')->nullable()->comment('เก็บค่าที่ระบุตามรูปแบบนั้นๆ');
            $table->string('recept_place',255)->nullable()->comment('เขียนที่');
            $table->date('recept_date')->nullable()->comment('วันที่ออก');
            $table->string('taxid',255)->nullable()->comment('เก็บค่าที่ระบุตามรูปแบบนั้นๆ');
            $table->string('name',255)->nullable()->comment('ชื่อเต็ม');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->decimal('amount',30,2)->nullable()->comment('จำนวนเงิน');
            $table->string('amount_th',255)->nullable()->comment('จำนวนเงินที่ได้รับ');
            $table->integer('status')->nullable()->comment('สถานะ 1.รอดำเนินการ, 2.เรียนร้อย');
            $table->integer('condition_group')->nullable()->comment('เงื่อนไขการสร้าง  1.แบบกรุ๊ปตามผู้มีสิทธิ์, 2.แบบไม่กรุ๊ปตามผู้มีสิทธิ์');
            $table->text('set_item')->nullable()->comment('กำหนดแสดงรายการ (json)');
            $table->integer('conditon_type')->nullable()->comment('เงื่อนไขตอบกลับ 1.ส่งหลักฐานกลับ, 2.ไม่ส่งหลักฐานกลับ');
            $table->date('due_date')->nullable()->comment('วันครบกำหนดส่งกลับ'); 
            $table->enum('notices', ['1', '0'])->default('0')->comment('แจ้งเตือนไปยังเมล 1.แจ้งเตือน');
            $table->enum('send_status', ['1', '0'])->default('0')->comment('ส่งหลักฐานกลับ 1.ส่งกลับแล้ว');
            $table->dateTime('send_date')->nullable()->comment('วันที่ส่งหลักฐานกลับ');
            $table->smallInteger('ordering')->unsigned()->nullable()->comment('การเรียงลำดับ');
            $table->text('send_remark')->nullable()->comment('หมายเหตุ');
            $table->integer('cancel_by')->nullable()->comment('ผู้ยกเลิก ID : user_register');
            $table->dateTime('cancel_at')->nullable()->comment('วันที่ยกเลิก');
            $table->text('cancel_remark')->nullable()->comment('หมายเหตุยกเลิก');
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
        Schema::dropIfExists('law_reward_recepts');
    }
}
