<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseStaffListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_staff_lists', function (Blueprint $table) {
            $table->increments('id')->comment('รหัสประจำตาราง');
            $table->integer('law_case_impound_id')->comment('ID : ตาราง law_case');
            $table->integer('depart_type')->nullable()->comment('เจ้าของคดี : ประเภทหน่วยงาน 1 = ภายใน (สมอ.) 2 = ภายนอก');
            $table->integer('sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department');
            $table->integer('basic_department_id')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน (กรณีภายนอก) ตารางอ้างอิง law_basic_department');
            $table->string('department_name')->nullable()->comment('ชื่อหน่วยงาน/กอง/กลุ่ม');
            $table->string('name',80)->comment('ชื่อเจ้าหน้าที่');
            $table->string('address',150)->nullable()->comment('ที่อยู่');
            $table->integer('basic_reward_group_id')->nullable()->comment('หน้าที่ในคดี อ้างอิงตาราง law_basic_reward_group');
            $table->bigInteger('created_by')->comment('ผู้บันทึก');
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
        Schema::dropIfExists('law_case_staff_lists');
    }
}
