<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationInspectorsStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_inspectors_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable()->comment('ไอดีคำขอ');
            $table->string('application_no',30)->nullable()->comment('เลขที่คำขอ');
            $table->integer('staff_id')->nullable()->comment('id เจ้าหน้าที่ผู้รับผิดชอบ (เชื่อมกับตาราง user_register)');
            $table->date('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');            
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
        Schema::dropIfExists('section5_application_inspectors_staff');
    }
}
