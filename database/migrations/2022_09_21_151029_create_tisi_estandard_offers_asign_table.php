<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardOffersAsignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_offers_asign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->nullable()->comment('ID ตารางความคิดเห็น');
            $table->integer('user_id')->nullable()->comment('ID ตาราง จนท. ผู้รับมอบ');
            $table->integer('ordering')->nullable()->comment('1. ผอ. เห็นคำขอก่อน , 2. ผอ. มอบหมายให้ ผก. , 3. ผก. มอบหมายเจ้าหน้าที่ ภายในกลุ่ม ได้');
            $table->integer('status')->nullable()->comment('สถานะ 1.ใช้งาน 2.ไม่ใช้งาน');
            $table->integer('assign_by')->nullable()->comment('ID ตาราง จนท. ผู้มอบ');
            $table->datetime('assign_date')->nullable()->comment('วันที่ได้รับมอบหมาย');

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
        Schema::dropIfExists('tisi_estandard_offers_asign');
    }
}
