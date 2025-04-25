<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBasicDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_basic_department', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('หน่วยงาน');
            $table->string('title_short',255)->nullable()->comment('หน่วยงาน(ย่อ)');
            $table->integer('type')->nullable()->comment('1: หน่วยงานภายใน 2: หน่วยงานภายนอก ');
            $table->boolean('state')->nullable()->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
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
        Schema::dropIfExists('law_basic_department');
    }
}
