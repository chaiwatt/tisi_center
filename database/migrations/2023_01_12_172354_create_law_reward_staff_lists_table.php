<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardStaffListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_staff_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_id')->comment('ID : ตาราง law_case');
            $table->string('case_number',255)->nullable()->comment('เลขคดี');
            $table->integer('law_reward_id')->nullable()->comment('ID : law_rewards'); 
            $table->integer('basic_reward_group_id')->nullable()->comment('ส่วนร่วมในคดี ID : law_basic_reward_group');
            $table->integer('depart_type')->nullable()->comment('ประเภทหน่วยงาน 1.ภายใน(สมอ.), 2.ภายนอก');
            $table->string('depart_name',255)->nullable()->comment('ชื่อหน่วยงาน');
            $table->string('sub_department_id',10)->nullable()->comment('กอง/กลุ่ม (กรณีภายใน) sub_id : sub_department');
            $table->integer('basic_department_id')->nullable()->comment('ชื่อหน่วยงาน (กรณีภายนอก) ID : law_basic_department');
            $table->string('taxid',50)->nullable()->comment('TAXID / Gen จากระบบ');
            $table->string('name',255)->nullable()->comment('ชื่อ - สกุล');
            $table->string('address',255)->nullable()->comment('ที่อยู่');
            $table->string('mobile',50)->nullable()->comment('เบอร์มือถือ');
            $table->string('email',50)->nullable()->comment('อีเมล');
            $table->integer('basic_bank_id')->nullable()->comment('ID : basic_bank');
            $table->string('basic_bank_name',255)->nullable()->comment('ชื่อธนาคาร');
            $table->string('bank_account_name',50)->nullable()->comment('ชื่อบัญชี');
            $table->string('bank_accoun_number',50)->nullable()->comment('เลขบัญชี');
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
        Schema::dropIfExists('law_reward_staff_lists');
    }
}
