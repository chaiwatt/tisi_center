<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGazettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_gazette', function (Blueprint $table) {
            $table->increments('id');
         
            $table->string('title')->nullable()->comment('เรื่อง');
            $table->string('gazette_book')->nullable()->comment('ฉบับ');        
            $table->string('gazette_no')->nullable()->comment('เล่ม');
            $table->string('gazette_space')->nullable()->comment('ตอน');
            $table->date('gazette_date')->nullable()->comment('วันที่ประกาศราชกิจจานุเบกษา');
            $table->string('enforce_day', 50)->nullable()->comment('จำนวนวันที่มึผลนับจากประกาศ');
            $table->date('enforce_date')->nullable()->comment('วันที่มีผลบังคับใช้');
            $table->string('gazette_signname')->nullable()->comment('ผู้ลงนาม');
            $table->string('gazette_position')->nullable()->comment('ตำแหน่งผู้ลงนาม');
            $table->text('gazette_attach')->nullable()->comment('ไฟล์แนบ');
            $table->integer('std_type_id')->nullable()->comment('id ตาราง bcertify_standard_type');
            $table->boolean('state')->nullable();
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
        Schema::drop('certify_gazette');
    }
}
