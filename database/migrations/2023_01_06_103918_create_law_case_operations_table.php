<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseOperationsTable extends Migration
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
            $table->string('case_number', 255)->nullable()->comment('เลขคดี  ตาราง law_cases');
            $table->integer('operation_type')->nullable()->comment('1.ดำเนินการทางอาญา(ผู้กระทำความผิด), 2.ดำเนินการปกครอง(ใบอนุญาต), 3.ดำเนินการของกลาง(ผลิตภัณฑ์)');
            $table->integer('status_job_track_id')->nullable()->comment('ID ตาราง law_basic_status_operate');
            $table->date('operation_date')->nullable()->comment('วันที่ดำเนินการ/ติดตาม');
            $table->date('due_date')->nullable()->comment('วันที่ครบกำหนด');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
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
        Schema::dropIfExists('law_case_operations');
    }
}
