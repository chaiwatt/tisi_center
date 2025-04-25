<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBookManageAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_book_manage_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_book_manage_id')->nullable()->comment('id ตาราง law_book_manage');
            $table->text('access')->nullable()->comment('สิทธิ์การเข้าถึง 1=บุคคลทั่วไป, 2=สมาชิกเว็บไชต์(ผู้ประกอบการ), 3=เจ้าหน้าที่ สมอ.');
            $table->text('access_tisi')->nullable()->comment('sub_department_id (กรณีทั้งหมดให้เป็นค่า null)');
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
        Schema::dropIfExists('law_book_manage_access');
    }
}
