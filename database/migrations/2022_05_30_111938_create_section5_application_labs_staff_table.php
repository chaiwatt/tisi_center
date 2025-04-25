<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationLabsStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_no')->nullable()->comment('เลขที่คำขอ');
            $table->integer('staff_id')->nullable()->comment('id เจ้าหน้าที่ผู้รับผิดชอบ (เชื่อมกับตาราง user_register)');
            $table->date('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->string('assign_comment')->nullable()->comment('หมายเหตุ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
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
        Schema::dropIfExists('section5_application_labs_staff');
    }
}
