<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBasicDivisionCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_basic_division_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('ชื่อข้อมูลความเชี่ยวชาญ');
            $table->boolean('state')->nullable()->comment('สถานะ (เปิด/ปิด');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้อัพเดท');
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
        Schema::dropIfExists('law_basic_division_category');
    }
}
