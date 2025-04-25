<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBasicDepartmentStakeholderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_basic_department_stakeholder', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable()->comment('ชื่อหน่วยงาน');
            $table->string('address_no', 150)->nullable()->comment('เลขที่ อาคาร ชั้น ห้อง ชื่อหมู่บ้าน');
            $table->string('moo', 80)->nullable()->comment('หมู่');
            $table->string('soi', 80)->nullable()->comment('ตรอก/ซอย');
            $table->string('street', 80)->nullable()->comment('ถนน');
            $table->integer('subdistrict_id')->nullable()->comment('id ตาราง district');
            $table->integer('district_id')->nullable()->comment('id ตาราง amphur');
            $table->integer('province_id')->nullable()->comment('id ตาราง province');
            $table->string('zipcode', 5)->nullable()->comment('รหัสไปรษณีย์');
            $table->string('tel', 30)->nullable()->comment('เบอร์โทร');
            $table->string('fax', 30)->nullable()->comment('แฟกซ์');
            $table->string('mobile', 30)->nullable()->comment('เบอร์มือถือ');
            $table->string('email', 100)->nullable()->comment('อีเมล');
            $table->tinyInteger('state')->default(1)->comment('สถานะการใช้งาน 1=ใช้งาน, 0=ไม่ใช้งาน');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
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
        Schema::dropIfExists('law_basic_department_stakeholder');
    }
}
