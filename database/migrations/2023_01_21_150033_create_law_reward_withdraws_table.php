<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no',255)->nullable()->comment('เลขอ้างอิงการเบิกจ่าย');
            $table->string('plan_name',255)->nullable()->comment('ชื่อแผนงาน');
            $table->string('cost_center',255)->nullable()->comment('ศูนย์ต้นทุน');
            $table->string('category',255)->nullable()->comment('หมวดหมู่รายจ่าย');
            $table->string('year_code',255)->nullable()->comment('รหัสปีงบประมาณ');
            $table->string('activity_main_name',255)->nullable()->comment('ชื่อกิจกรรมหลัก');
            $table->string('activity_main_code',255)->nullable()->comment('รหัสกิจกรรมหลัก');
            $table->string('activity_small_name',255)->nullable()->comment('ชื่อกิจกรรมย่อย');
            $table->string('activity_small_code',255)->nullable()->comment('รหัสกิจกรรมย่อย'); 
            $table->integer('forerunner_id')->nullable()->comment('ผู้เบิก ID : user_register');
            $table->integer('status')->nullable()->comment('สถานะ 1.อยู่ระหว่างเบิกจ่าย, 2.เบิกจ่ายเรียนร้อย');
            $table->integer('filter_type')->nullable()->comment('เบิกค่าใช้จ่ายในคดี : 1.รายคดี, 2.รายเดือน, 3.ช่วงวันที่');
            $table->string('filter_case_number')->nullable()->comment('เบิกค่าใช้จ่ายในคดี :  เลขคดี');
            $table->date('filter_paid_date_start')->nullable()->comment('เบิกค่าใช้จ่ายในคดี : วันที่เริ่ม');
            $table->date('filter_paid_date_end')->nullable()->comment('เบิกค่าใช้จ่ายในคดี : วันที่สิ้นสุด');
            $table->enum('check_file', ['1', '0'])->default('0')->comment('ต้องการแนบไฟล์เอง 1.แนบไฟล์เอง');
            $table->date('approve_date')->nullable()->comment('วันที่อนุมัติเบิกจ่าย'); 
            $table->text('approve_remark')->nullable()->comment('หมายเหตุอนุมัติเบิกจ่าย'); 
            $table->enum('approve_status', ['1', '0'])->default('0')->comment('แจ้งเตือนไปยังอีเมล 1.ผู้สิทธิ์ได้รับเงินรางวัล');
            $table->text('approve_emails')->nullable()->comment('รหัสกิจกรรมย่อย เก็บ json'); 
            $table->integer('approve_by')->nullable()->comment('ผู้บันทึกอนุมัติเบิกจ่าย ID : user_register');
            $table->datetime('approve_at')->nullable()->comment('วันที่บันทึกอนุมัติเบิกจ่าย ID : user_register');
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
        Schema::dropIfExists('law_reward_withdraws');
    }
}
