<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->comment('ชื่อวันหยุด');
            $table->string('title_en',255)->nullable()->comment('ชื่อวันหยุดภาษาอังกฤษ');
            $table->integer('fis_year')->comment('ปีของวันหยุด ค.ศ.');
            $table->date('holiday_date')->comment('วันเดือนปีที่หยุด');
            $table->integer('ordering')->nullable()->comment('ลำดับ');
            $table->boolean('state')->nullable()->comment('สถานะ (เปิด/ปิด');
            $table->bigInteger('created_by')->nullable()->unsigned()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->unsigned()->comment('ผู้อัพเดท');
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
        Schema::dropIfExists('basic_holidays');
    }
}
