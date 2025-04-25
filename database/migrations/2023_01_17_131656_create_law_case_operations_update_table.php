<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseOperationsUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_cases_id')->nullable()->comment('ID law_cases');
            $table->string('case_number', 255)->nullable()->comment('เลขคดี  ตาราง law_cases');
            $table->integer('status')->nullable()->comment('สถานะ 99=ไม่ต้องดำเนินการใดๆ 1=รอดำเนินการ 2=อยู่ระหว่างดำเนินการ 3=ดำเนินการเสร็จสิ้น');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('law_case_operations');
    }
}
