<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSetstandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('projectid',255)->nullable()->comment('รหัสกำหนดมาตรฐาน');
            $table->integer('plan_id')->nullable()->comment('id ตารางแผน'); 
            $table->integer('method_id')->nullable()->comment('id ตาราง basic_methods'); 
            $table->integer('format_id')->nullable()->comment('id ตาราง basic_set_formats'); 
            $table->integer('plan_time')->nullable()->comment('ประมาณการจำนวนครั้งการประชุม');
            $table->decimal('estimate_cost',10,2)->nullable()->comment('การประมาณการค่าใช้จ่าย');
            $table->integer('status_id')->nullable()->comment('สถานะการณ์การกำหนดมาตรฐาน 1. อยู่ระหว่างดำเนินการ 2.อยู่ระหว่างการประชุม 3.อยู่ระหว่างสรุปรายงานการประชุม 4.อยู่ระหว่างจัดทำมาตรฐาน');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('certify_setstandards');
    }
}