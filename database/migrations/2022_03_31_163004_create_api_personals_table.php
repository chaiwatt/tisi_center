<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiPersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_personals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->comment('ไอดีการเรียกใช้ API');
            $table->string('agent_id',255)->nullable()->comment('ค่าเลขบัตรประชาชนที่ส่งไป');
            $table->string('citizenID',255)->nullable()->comment('เลขบัตรประชาชน');
            $table->integer('age')->nullable()->comment('อายุ');
            $table->string('dateOfBirth')->nullable()->comment('วันเดือนปีเกิด');
            $table->string('dateOfMoveIn')->nullable()->comment('วันเดือนปีที่ย้ายเข้ามาในบ้าน');

            $table->string('fatherName')->nullable()->comment('ชื่อบิดา');
            $table->string('fatherNationalityCode')->nullable()->comment('รหัสสัญชาติบิดา');
            $table->string('fatherNationalityDesc')->nullable()->comment('สัญชาติบิดา');
            $table->string('fatherPersonalID')->nullable()->comment('เลขประจําตัวประชาชนบิดา');

            $table->string('firstName')->nullable()->comment('ชื่อตัว');
            $table->string('fullnameAndRank')->nullable()->comment('คํานําหน้านาม/ยศ ชื่อตัว-สกุล');
            $table->string('genderCode')->nullable()->comment('รหัสเพศ');
            $table->string('genderDesc')->nullable()->comment('เพศ');
            $table->string('lastName')->nullable()->comment('ชื่อสกุล');
            $table->string('middleName')->nullable()->comment('ชื่อกลาง');

            $table->string('motherName')->nullable()->comment('ชื่อมารดา');
            $table->string('motherNationalityCode')->nullable()->comment('รหัสสัญชาติมารดา');
            $table->string('motherNationalityDesc')->nullable()->comment('สัญชาติมารดา');
            $table->string('motherPersonalID')->nullable()->comment('เลขประจําตัวประชาชนมารดา');

            $table->string('NationalityCode')->nullable()->comment('รหัสสัญชาติ');
            $table->string('NationalityDesc')->nullable()->comment('สัญชาติ');
            $table->string('ownerStatusDesc')->nullable()->comment('สถานะภาพเจ้าบ้าน');
            $table->string('statusOfPersonCode')->nullable()->comment('รหัสสถานะภาพบุคคล');
            $table->string('statusOfPersonDesc')->nullable()->comment('สถานะภาพบุคคล');
            $table->string('titleCode')->nullable()->comment('รหัสคํานําหน้านาม');
            $table->string('titleDesc')->nullable()->comment('คํานําหน้านาม');
            $table->string('titleName')->nullable()->comment('คํานําหน้านามแบบเต็ม');
            $table->string('titleSex')->nullable()->comment('รหัสตรวจสอบคํานําหน้านาม');
            $table->timestamps();
            $table->foreign('request_id')
                  ->references('id')
                  ->on('api_requests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_personals');
    }
}
